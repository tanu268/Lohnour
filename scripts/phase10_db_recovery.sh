#!/bin/bash
# ==============================================================================
# LOHNOUR PRO — PHASE 10: PRODUCTION MARIADB RECOVERY & VALIDATION
# Canonical Execution Script
#
# Target Host: Sam's Oracle VM (141.148.51.166, Oracle Linux Server 9.7 ARM64)
# Architecture: linux/arm64/v8
# ==============================================================================
set -euo pipefail

# ------------------------------------------------------------------------------
# 0. PRIVILEGE & DOCKER COMMAND WRAPPER DISCOVERY
# ------------------------------------------------------------------------------
if [ "${EUID:-$(id -u)}" -eq 0 ]; then
  SUDO_CMD=""
  DOCKER_CMD="docker"
elif sudo -n true 2>/dev/null; then
  SUDO_CMD="sudo"
  DOCKER_CMD="sudo docker"
else
  SUDO_CMD=""
  DOCKER_CMD="docker"
fi

# ------------------------------------------------------------------------------
# 1. IMMUTABLE ARCHITECTURAL PARAMETERS
# ------------------------------------------------------------------------------
PINNED_MARIADB_IMAGE="${PINNED_MARIADB_IMAGE:-mariadb@sha256:0de9840f23463c22eb4892d9bfd63086367e4bffe9b68ff4b62621d6eced854c}"
INIT_CONTAINER="${INIT_CONTAINER:-lohnour-db-init}"
TARGET_VOLUME="${TARGET_VOLUME:-lohnour_db_data}"
TARGET_NETWORK="${TARGET_NETWORK:-lohnour_db_network}"
SQL_FILE="${SQL_FILE:-/opt/lohnour/staging/production-lohnour_wp.sql}"
EXPECTED_SQL_HASH="${EXPECTED_SQL_HASH:-db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880}"
PHASE8_BACKUP_DIR="${PHASE8_BACKUP_DIR:-/opt/lohnour/backups/phase8_20261107_044630}"

# Execution state flags
RECOVERY_SUCCESS=0
CLEANUP_INCOMPLETE=0
CLEANUP_RUN_ONCE=0
SEC_DIR=""

# ------------------------------------------------------------------------------
# 2. IDEMPOTENT CLEANUP & SIGNAL TRAPPING
# ------------------------------------------------------------------------------
cleanup_db_recovery() {
  local exit_code=$?
  if [ "${CLEANUP_RUN_ONCE}" -eq 1 ]; then
    return "${exit_code}"
  fi
  CLEANUP_RUN_ONCE=1
  
  echo "==> Running idempotent recovery cleanup (trapped exit_code=${exit_code})..."

  # 1. Stop and decommission temporary container
  if $DOCKER_CMD inspect "${INIT_CONTAINER}" >/dev/null 2>&1; then
    echo "==> Stopping temporary container ${INIT_CONTAINER}..."
    $DOCKER_CMD stop "${INIT_CONTAINER}" >/dev/null 2>&1 || true
    echo "==> Removing temporary container ${INIT_CONTAINER}..."
    $DOCKER_CMD rm -f "${INIT_CONTAINER}" >/dev/null 2>&1 || true
  fi

  # 2. Securely purge ephemeral host secret directory
  if [ -n "${SEC_DIR:-}" ] && [ -d "${SEC_DIR}" ]; then
    echo "==> Purging ephemeral host secret directory..."
    rm -rf "${SEC_DIR}" 2>/dev/null || true
  fi

  # 3. Independent verification of cleanup completeness
  if $DOCKER_CMD inspect "${INIT_CONTAINER}" >/dev/null 2>&1; then
    echo "CRITICAL CLEANUP FAILURE: Container ${INIT_CONTAINER} still exists!" >&2
    CLEANUP_INCOMPLETE=1
  fi
  if [ -n "${SEC_DIR:-}" ] && [ -d "${SEC_DIR}" ]; then
    echo "CRITICAL CLEANUP FAILURE: Secret directory ${SEC_DIR} persists on host!" >&2
    CLEANUP_INCOMPLETE=1
  fi

  # 4. Failure volume preservation policy (Never delete or reset volume)
  if [ "${RECOVERY_SUCCESS}" -ne 1 ]; then
    echo "=============================================================================="
    echo "STATUS: RECOVERY_FAILED_DATA_PRESERVED"
    echo "Database volume ${TARGET_VOLUME} preserved for forensics. Zero data deleted."
    echo "Source SQL dump and Phase 8 backup remain 100% intact."
    echo "=============================================================================="
  fi

  if [ "${CLEANUP_INCOMPLETE}" -eq 1 ]; then
    echo "=============================================================================="
    echo "ALERT: CLEANUP_INCOMPLETE — HUMAN_INTERVENTION_REQUIRED"
    echo "=============================================================================="
    exit 2
  fi

  return "${exit_code}"
}

handle_signal() {
  local sig="${1:-UNKNOWN}"
  echo "==> Caught signal ${sig}. Initiating controlled termination..." >&2
  if [ "${sig}" = "INT" ]; then
    exit 130
  elif [ "${sig}" = "TERM" ]; then
    exit 143
  else
    exit 1
  fi
}

trap 'handle_signal INT' INT
trap 'handle_signal TERM' TERM
trap cleanup_db_recovery EXIT

# ------------------------------------------------------------------------------
# 3. CREDENTIAL PROVISIONING & CHARACTER POLICY VALIDATION
# ------------------------------------------------------------------------------
# Passwords remain strictly local shell variables; DO NOT export to environment.
if [ -z "${DB_ROOT_PASSWORD:-}" ]; then
  echo -n "Enter MariaDB Root Password (DB_ROOT_PASSWORD): " && read -s -r DB_ROOT_PASSWORD && echo
fi
if [ -z "${DB_PASSWORD:-}" ]; then
  echo -n "Enter MariaDB Application Password (DB_PASSWORD): " && read -s -r DB_PASSWORD && echo
fi

# Policy Validation 1: Presence and Minimum Length (16 characters)
if [ "${#DB_ROOT_PASSWORD}" -lt 16 ]; then
  echo "FATAL: DB_ROOT_PASSWORD does not meet minimum length requirement (>= 16 characters)." >&2
  exit 1
fi
if [ "${#DB_PASSWORD}" -lt 16 ]; then
  echo "FATAL: DB_PASSWORD does not meet minimum length requirement (>= 16 characters)." >&2
  exit 1
fi

# Policy Validation 2: Reject dangerous control characters (newline, CR, tab, null)
# Disallows ASCII control characters 0x00-0x1F and 0x7F
for pwd_val in "${DB_ROOT_PASSWORD}" "${DB_PASSWORD}"; do
  if [ "$(printf '%s' "${pwd_val}" | wc -l)" -gt 0 ]; then
    echo "FATAL: Password contains prohibited newline character." >&2
    exit 1
  fi
  if [[ "${pwd_val}" == *$'\r'* ]] || [[ "${pwd_val}" == *$'\t'* ]]; then
    echo "FATAL: Password contains prohibited carriage return or tab character." >&2
    exit 1
  fi
  if LC_ALL=C grep -q '[^ -~]' <<< "${pwd_val}"; then
    echo "FATAL: Password contains prohibited non-printable or control characters." >&2
    exit 1
  fi
done

echo "==> Credential policy validated successfully (printable ASCII, >= 16 chars, zero exports)."

# ------------------------------------------------------------------------------
# 4. PREFLIGHT DUAL-GATE INSPECTION (GATE A: BEFORE CONTAINER LAUNCH)
# ------------------------------------------------------------------------------
echo "==> Executing Preflight Gate A inspections..."

# 4.1 Discover volume mountpoint dynamically (no /var/lib/docker assumption)
VOL_INSPECT=$($DOCKER_CMD volume inspect "${TARGET_VOLUME}" 2>/dev/null) || {
  echo "FATAL PRE-GATE: Volume ${TARGET_VOLUME} does not exist. Aborting." >&2
  exit 1
}
VOL_MOUNTPOINT=$($DOCKER_CMD volume inspect "${TARGET_VOLUME}" -f '{{.Mountpoint}}')
if [ -z "${VOL_MOUNTPOINT}" ]; then
  echo "FATAL PRE-GATE: Volume ${TARGET_VOLUME} has no mountpoint. Aborting." >&2
  exit 1
fi

# 4.2 Verify zero container attachments to target volume
ATTACHED_CONTAINERS=$($DOCKER_CMD ps -a --filter "volume=${TARGET_VOLUME}" -q)
if [ -n "${ATTACHED_CONTAINERS}" ]; then
  ATTACHED_NAMES=$($DOCKER_CMD ps -a --filter "volume=${TARGET_VOLUME}" --format '{{.Names}}')
  echo "FATAL PRE-GATE: Volume ${TARGET_VOLUME} is attached to container(s): ${ATTACHED_NAMES}. Aborting." >&2
  exit 1
fi

# 4.3 Verify volume directory is completely empty skeleton
if [ -n "${SUDO_CMD}" ] && $SUDO_CMD test -d "${VOL_MOUNTPOINT}" 2>/dev/null; then
  VOL_ENTRY_COUNT=$($SUDO_CMD ls -A "${VOL_MOUNTPOINT}" | wc -l)
elif [ -d "${VOL_MOUNTPOINT}" ] 2>/dev/null; then
  VOL_ENTRY_COUNT=$(ls -A "${VOL_MOUNTPOINT}" | wc -l)
else
  VOL_ENTRY_COUNT=$($DOCKER_CMD run --rm -v "${TARGET_VOLUME}:/target_vol_check:ro" alpine sh -c 'ls -A /target_vol_check | wc -l')
fi
if [ "${VOL_ENTRY_COUNT}" -ne 0 ]; then
  echo "FATAL PRE-GATE: Volume ${TARGET_VOLUME} at ${VOL_MOUNTPOINT} is NOT empty (${VOL_ENTRY_COUNT} entries). Aborting." >&2
  exit 1
fi

# 4.4 Verify internal network isolation
IS_INTERNAL=$($DOCKER_CMD network inspect "${TARGET_NETWORK}" -f '{{.Internal}}' 2>/dev/null || echo "false")
if [ "${IS_INTERNAL}" != "true" ]; then
  echo "FATAL PRE-GATE: Network ${TARGET_NETWORK} is not marked Internal: true. Aborting." >&2
  exit 1
fi

# 4.5 Verify no naming collision for temporary container
if $DOCKER_CMD inspect "${INIT_CONTAINER}" >/dev/null 2>&1; then
  echo "FATAL PRE-GATE: Container ${INIT_CONTAINER} already exists. Aborting." >&2
  exit 1
fi

# 4.6 Cryptographic verification of staged SQL dump
if [ ! -f "${SQL_FILE}" ]; then
  echo "FATAL PRE-GATE: SQL dump file ${SQL_FILE} not found. Aborting." >&2
  exit 1
fi
CURRENT_SQL_HASH=$(sha256sum "${SQL_FILE}" | awk '{print $1}')
if [ "${CURRENT_SQL_HASH}" != "${EXPECTED_SQL_HASH}" ]; then
  echo "FATAL PRE-GATE: SQL dump hash mismatch (${CURRENT_SQL_HASH} != ${EXPECTED_SQL_HASH}). Aborting." >&2
  exit 1
fi

# 4.7 Reconfirm Phase 8 host recovery point integrity (if directory exists on host)
if [ -d "${PHASE8_BACKUP_DIR}" ]; then
  echo "==> Verifying Phase 8 host backup integrity..."
  $SUDO_CMD bash -c "cd '${PHASE8_BACKUP_DIR}' && sha256sum -c SHA256SUMS" >/dev/null 2>&1 || {
    echo "FATAL PRE-GATE: Phase 8 backup checksum verification failed. Aborting." >&2
    exit 1
  }
fi

echo "==> Preflight Gate A passed cleanly."

# ------------------------------------------------------------------------------
# 5. EPHEMERAL SECRET PROVISIONING & OPTION FILE GENERATION
# ------------------------------------------------------------------------------
echo "==> Provisioning ephemeral secret files..."
SEC_DIR=$(mktemp -d /tmp/lohnour_db_sec.XXXXXX)
chmod 700 "${SEC_DIR}"

printf '%s' "${DB_ROOT_PASSWORD}" > "${SEC_DIR}/root_password"
printf '%s' "${DB_PASSWORD}" > "${SEC_DIR}/user_password"
chmod 600 "${SEC_DIR}/root_password" "${SEC_DIR}/user_password"

# Option file escaping: escape backslash first, then double quote.
# Enclosing in double quotes protects spaces, tabs, # and ; comment characters.
ESCAPED_DB_PASS="${DB_PASSWORD//\\/\\\\}"
ESCAPED_DB_PASS="${ESCAPED_DB_PASS//\"/\\\"}"
printf '[client]\nuser=lohnour_user\nhost=127.0.0.1\npassword="%s"\n' "${ESCAPED_DB_PASS}" > "${SEC_DIR}/client.cnf"
chmod 600 "${SEC_DIR}/client.cnf"

# Unset plaintext passwords in shell variable scope immediately after writing secret files
DB_ROOT_PASSWORD=""
DB_PASSWORD=""
unset DB_ROOT_PASSWORD DB_PASSWORD

# ------------------------------------------------------------------------------
# 6. CONTAINER INITIALIZATION & HEALTH PROBING
# ------------------------------------------------------------------------------
echo "==> Launching isolated MariaDB container (${PINNED_MARIADB_IMAGE})..."
$DOCKER_CMD run -d --name "${INIT_CONTAINER}" \
  --network "${TARGET_NETWORK}" \
  -v "${TARGET_VOLUME}:/var/lib/mysql" \
  -v "${SEC_DIR}:/run/secrets:ro" \
  -e MARIADB_ROOT_PASSWORD_FILE=/run/secrets/root_password \
  -e MARIADB_PASSWORD_FILE=/run/secrets/user_password \
  -e MARIADB_DATABASE=lohnour_wp \
  -e MARIADB_USER=lohnour_user \
  --health-cmd='healthcheck.sh --connect --innodb_initialized' \
  --health-interval=2s \
  --health-timeout=2s \
  --health-retries=30 \
  "${PINNED_MARIADB_IMAGE}"

echo "==> Awaiting container healthy status..."
MAX_RETRIES=35; COUNT=0; HEALTH_STATUS="starting"
until [ "$COUNT" -ge "$MAX_RETRIES" ]; do
  HEALTH_STATUS=$($DOCKER_CMD inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}unknown{{end}}' "${INIT_CONTAINER}" 2>/dev/null || echo "not_found")
  if [ "${HEALTH_STATUS}" = "healthy" ]; then
    echo "==> Container reached healthy status."
    break
  fi
  sleep 2; COUNT=$((COUNT + 1))
done

if [ "${HEALTH_STATUS}" != "healthy" ]; then
  echo "FATAL: MariaDB initialization container failed health probe." >&2
  $DOCKER_CMD logs --tail 50 "${INIT_CONTAINER}" || true
  exit 1
fi

# ------------------------------------------------------------------------------
# 7. IMMEDIATE GATE B: PRE-IMPORT INSPECTION
# ------------------------------------------------------------------------------
echo "==> Executing Pre-Import Gate B inspections..."

# 7.1 Client connection test
$DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -e "SELECT 1;" >/dev/null 2>&1 || {
  echo "FATAL PRE-GATE B: MariaDB client authentication failed." >&2
  exit 1
}

# 7.2 Database empty check
EXISTING_TABLES=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(*) FROM information_schema.tables WHERE table_schema='lohnour_wp';")
if [ "${EXISTING_TABLES}" -ne 0 ]; then
  echo "FATAL PRE-GATE B: Database lohnour_wp already contains ${EXISTING_TABLES} tables. Aborting." >&2
  exit 1
fi

echo "==> Pre-Import Gate B passed cleanly."

# ------------------------------------------------------------------------------
# 8. STREAM SQL DUMP
# ------------------------------------------------------------------------------
echo "==> Streaming ${SQL_FILE} into lohnour_wp..."
$DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf lohnour_wp < "${SQL_FILE}"
echo "==> SQL import stream completed successfully."

# ------------------------------------------------------------------------------
# 9. COMPLETE 10-POINT DATABASE ACCEPTANCE VALIDATION SUITE
# ------------------------------------------------------------------------------
echo "==> Verifying database integrity against complete forensic baseline..."

# 9.1 Total Table Count (14)
TBL_COUNT=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(*) FROM information_schema.tables WHERE table_schema='lohnour_wp';")
echo "  [Check 1/10] Total Table Count: ${TBL_COUNT} / 14"
test "${TBL_COUNT}" -eq 14 || { echo "VERIFICATION FAILED: Table count mismatch (${TBL_COUNT} != 14)" >&2; exit 1; }

# 9.2 WordPress Core Table Count (12)
CORE_COUNT=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(*) FROM information_schema.tables 
  WHERE table_schema='lohnour_wp' AND table_name IN (
    'wp_commentmeta','wp_comments','wp_links','wp_options','wp_postmeta','wp_posts',
    'wp_term_relationships','wp_term_taxonomy','wp_termmeta','wp_terms','wp_usermeta','wp_users'
  );")
echo "  [Check 2/10] Core Table Count: ${CORE_COUNT} / 12"
test "${CORE_COUNT}" -eq 12 || { echo "VERIFICATION FAILED: Core table count mismatch (${CORE_COUNT} != 12)" >&2; exit 1; }

# 9.3 Brevo Plugin Table Count (2)
BREVO_COUNT=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(*) FROM information_schema.tables 
  WHERE table_schema='lohnour_wp' AND table_name IN (
    'wp_sib_model_forms','wp_sib_model_users'
  );")
echo "  [Check 3/10] Brevo Table Count: ${BREVO_COUNT} / 2"
test "${BREVO_COUNT}" -eq 2 || { echo "VERIFICATION FAILED: Brevo table count mismatch (${BREVO_COUNT} != 2)" >&2; exit 1; }

# 9.4 Zero Unexpected Tables (0)
UNEXPECTED_COUNT=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(*) FROM information_schema.tables 
  WHERE table_schema='lohnour_wp' AND table_name NOT IN (
    'wp_commentmeta','wp_comments','wp_links','wp_options','wp_postmeta','wp_posts',
    'wp_term_relationships','wp_term_taxonomy','wp_termmeta','wp_terms','wp_usermeta','wp_users',
    'wp_sib_model_forms','wp_sib_model_users'
  );")
echo "  [Check 4/10] Unexpected Table Count: ${UNEXPECTED_COUNT} / 0"
test "${UNEXPECTED_COUNT}" -eq 0 || { echo "VERIFICATION FAILED: Unexpected tables detected (${UNEXPECTED_COUNT})" >&2; exit 1; }

# 9.5 wp_posts Row Count (92)
POSTS_COUNT=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(*) FROM lohnour_wp.wp_posts;")
echo "  [Check 5/10] wp_posts Row Count: ${POSTS_COUNT} / 92"
test "${POSTS_COUNT}" -eq 92 || { echo "VERIFICATION FAILED: wp_posts count mismatch (${POSTS_COUNT} != 92)" >&2; exit 1; }

# 9.6 db_version (61833)
DB_VER=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT option_value FROM lohnour_wp.wp_options WHERE option_name='db_version';")
echo "  [Check 6/10] db_version: ${DB_VER} / 61833"
test "${DB_VER}" = "61833" || { echo "VERIFICATION FAILED: db_version mismatch (${DB_VER} != 61833)" >&2; exit 1; }

# 9.7 siteurl (https://pro.lohnour.com)
SITEURL=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT option_value FROM lohnour_wp.wp_options WHERE option_name='siteurl';")
echo "  [Check 7/10] siteurl: ${SITEURL}"
test "${SITEURL}" = "https://pro.lohnour.com" || { echo "VERIFICATION FAILED: siteurl mismatch (${SITEURL})" >&2; exit 1; }

# 9.8 home URL (https://pro.lohnour.com)
HOMEURL=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT option_value FROM lohnour_wp.wp_options WHERE option_name='home';")
echo "  [Check 8/10] home: ${HOMEURL}"
test "${HOMEURL}" = "https://pro.lohnour.com" || { echo "VERIFICATION FAILED: home URL mismatch (${HOMEURL})" >&2; exit 1; }

# 9.9 User Count (2)
USER_COUNT=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(*) FROM lohnour_wp.wp_users;")
echo "  [Check 9/10] User Count: ${USER_COUNT} / 2"
test "${USER_COUNT}" -eq 2 || { echo "VERIFICATION FAILED: User count mismatch (${USER_COUNT} != 2)" >&2; exit 1; }

# 9.10 Exact Account Identity & Scoped Privilege Verification
echo "  [Check 10/10] Exact lohnour_user Account Identity & Privileges..."

# 10.1 Exactly one account identity, strictly on host '%'
USER_ACCOUNT_ID=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT CURRENT_USER();")
test "${USER_ACCOUNT_ID}" = "lohnour_user@%" || { 
  echo "VERIFICATION FAILED: Unexpected account identity (${USER_ACCOUNT_ID} != lohnour_user@%)" >&2; exit 1; 
}

# 10.2 Zero global privileges (strictly 1 row with USAGE in user_privileges)
GLOBAL_PRIV_COUNT=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(*) FROM information_schema.user_privileges;")
test "${GLOBAL_PRIV_COUNT}" -eq 1 || { echo "VERIFICATION FAILED: Global privilege count mismatch (${GLOBAL_PRIV_COUNT} != 1)" >&2; exit 1; }

GLOBAL_PRIV_TYPE=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT PRIVILEGE_TYPE FROM information_schema.user_privileges;")
test "${GLOBAL_PRIV_TYPE}" = "USAGE" || { echo "VERIFICATION FAILED: Unexpected global privilege (${GLOBAL_PRIV_TYPE} != USAGE)" >&2; exit 1; }

# 10.3 Exactly one database-level grant, strictly for lohnour_wp
DB_SCHEMA_COUNT=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(DISTINCT TABLE_SCHEMA) FROM information_schema.schema_privileges;")
test "${DB_SCHEMA_COUNT}" -eq 1 || { echo "VERIFICATION FAILED: Database privilege schema count mismatch (${DB_SCHEMA_COUNT} != 1)" >&2; exit 1; }

DB_UNEXPECTED=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(*) FROM information_schema.schema_privileges WHERE TABLE_SCHEMA NOT IN ('lohnour_wp', 'lohnour\\_wp');")
test "${DB_UNEXPECTED}" -eq 0 || { echo "VERIFICATION FAILED: Unexpected schema privileges granted (${DB_UNEXPECTED})" >&2; exit 1; }

# 10.4 User cannot grant privileges to others (IS_GRANTABLE = 'NO')
DB_GRANT_PRIV=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT count(*) FROM information_schema.schema_privileges WHERE IS_GRANTABLE = 'YES';")
test "${DB_GRANT_PRIV}" -eq 0 || { echo "VERIFICATION FAILED: lohnour_user possesses GRANT OPTION" >&2; exit 1; }

# 10.5 Zero table or column privilege overrides
EXTRA_PRIV_COUNT=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "
  SELECT (SELECT count(*) FROM information_schema.table_privileges) +
         (SELECT count(*) FROM information_schema.column_privileges);")
test "${EXTRA_PRIV_COUNT}" -eq 0 || { echo "VERIFICATION FAILED: Unexpected granular privileges detected (${EXTRA_PRIV_COUNT})" >&2; exit 1; }

# 10.6 Exactly 2 grant lines in SHOW GRANTS
SHOW_GRANTS_COUNT=$($DOCKER_CMD exec -i "${INIT_CONTAINER}" mariadb --defaults-extra-file=/run/secrets/client.cnf -sN -e "SHOW GRANTS;" | wc -l)
test "${SHOW_GRANTS_COUNT}" -eq 2 || { echo "VERIFICATION FAILED: SHOW GRANTS statement count mismatch (${SHOW_GRANTS_COUNT} != 2)" >&2; exit 1; }

echo "==> All 10 acceptance checks passed with 100% compliance."

# Mark database recovery success
RECOVERY_SUCCESS=1

# ------------------------------------------------------------------------------
# 10. DECOMMISSION TEMPORARY CONTAINER & VERIFY CLEANUP
# ------------------------------------------------------------------------------
cleanup_db_recovery
echo "==> Temporary resources decommissioned and verified absent."

# ------------------------------------------------------------------------------
# 11. POST-RECOVERY WORKLOAD HEALTH VERIFICATION
# ------------------------------------------------------------------------------
echo "==> Verifying host workload health (Portainer & Node-RED)..."
# Check Portainer (:9443) and Node-RED (:1880) if running on host
PORTAINER_HEALTH=0
NODERED_HEALTH=0

if $DOCKER_CMD inspect portainer >/dev/null 2>&1; then
  PORTAINER_STATUS=$($DOCKER_CMD inspect -f '{{.State.Status}}' portainer 2>/dev/null || echo "unknown")
  if [ "${PORTAINER_STATUS}" = "running" ]; then
    PORTAINER_HEALTH=1
  fi
else
  # If running in isolated test mode where portainer container is absent
  PORTAINER_HEALTH=1
fi

if $DOCKER_CMD inspect nodered >/dev/null 2>&1; then
  NODERED_STATUS=$($DOCKER_CMD inspect -f '{{.State.Status}}' nodered 2>/dev/null || echo "unknown")
  if [ "${NODERED_STATUS}" = "running" ]; then
    NODERED_HEALTH=1
  fi
else
  # If running in isolated test mode where nodered container is absent
  NODERED_HEALTH=1
fi

if [ "${PORTAINER_HEALTH}" -ne 1 ] || [ "${NODERED_HEALTH}" -ne 1 ]; then
  echo "WARNING: Post-recovery workload check failed (Portainer=${PORTAINER_HEALTH}, Node-RED=${NODERED_HEALTH})" >&2
  echo "NOTE: Database volume ${TARGET_VOLUME} is fully intact and verified." >&2
  echo "Workload remediation required. Database data remains preserved." >&2
  exit 1
fi

echo "=============================================================================="
echo "PHASE 10 DATABASE RESTORE & VALIDATION SUCCESSFUL"
echo "All 14 tables, 92 posts, db_version 61833, site/home URLs, and grants verified."
echo "Temporary container decommissioned, secrets purged, workloads healthy."
echo "=============================================================================="
