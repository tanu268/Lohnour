#!/bin/bash
# ==============================================================================
# LOHNOUR PRO — PHASE 12: PORTAINER WORDPRESS STACK DEPLOYMENT & VERIFICATION
# Canonical Execution & Verification Script
#
# Target Host: Sam's Oracle VM (141.148.51.166, Oracle Linux Server 9.7 ARM64)
# Project Name: lohnour-stack
# Target Compose File: /opt/lohnour/docker-compose.yml
# Target Env File: /opt/lohnour/.env (0600 root:root)
# ==============================================================================
set -euo pipefail

echo "=============================================================================="
echo "LOHNOUR PRO — PHASE 12: PORTAINER STACK DEPLOYMENT & ACCEPTANCE VERIFICATION"
echo "Execution Started: $(date -u '+%Y-%m-%d %H:%M:%S UTC')"
echo "=============================================================================="

# ------------------------------------------------------------------------------
# 0. PRIVILEGE & DOCKER COMMAND DISCOVERY
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
# 1. PREFLIGHT SAFETY GATES
# ------------------------------------------------------------------------------
echo "--- [1/6] Running Preflight Safety Gates ---"

COMPOSE_FILE="/opt/lohnour/docker-compose.yml"
ENV_FILE="/opt/lohnour/.env"
CONFIG_FILE="/opt/lohnour/config/uploads.ini"

if [ ! -f "${COMPOSE_FILE}" ]; then
  echo "FATAL: Compose file '${COMPOSE_FILE}' not found." >&2
  exit 1
fi

if [ ! -f "${ENV_FILE}" ]; then
  echo "FATAL: Env file '${ENV_FILE}' not found." >&2
  exit 1
fi

if [ ! -f "${CONFIG_FILE}" ]; then
  echo "FATAL: PHP config file '${CONFIG_FILE}' not found." >&2
  exit 1
fi

# Verify permissions
ENV_PERMS=$(stat -c "%a %U:%G" "${ENV_FILE}")
echo "Env file permissions: ${ENV_PERMS}"
if [ "${ENV_PERMS}" != "600 root:root" ]; then
  echo "Enforcing 0600 root:root on ${ENV_FILE}..."
  ${SUDO_CMD} chown root:root "${ENV_FILE}"
  ${SUDO_CMD} chmod 600 "${ENV_FILE}"
fi

# Verify volumes exist and contain data
WP_MOUNT=$(${DOCKER_CMD} volume inspect lohnour_wp_data --format '{{.Mountpoint}}' 2>/dev/null || echo "")
DB_MOUNT=$(${DOCKER_CMD} volume inspect lohnour_db_data --format '{{.Mountpoint}}' 2>/dev/null || echo "")

if [ -z "${WP_MOUNT}" ] || [ ! -d "${WP_MOUNT}" ]; then
  echo "FATAL: lohnour_wp_data volume not found." >&2
  exit 1
fi
if [ -z "${DB_MOUNT}" ] || [ ! -d "${DB_MOUNT}" ]; then
  echo "FATAL: lohnour_db_data volume not found." >&2
  exit 1
fi

WP_FILE_COUNT=$(${SUDO_CMD} find "${WP_MOUNT}" -maxdepth 2 | wc -l)
DB_FILE_COUNT=$(${SUDO_CMD} find "${DB_MOUNT}" -maxdepth 2 | wc -l)

echo "WordPress volume items: ${WP_FILE_COUNT}"
echo "Database volume items: ${DB_FILE_COUNT}"

if [ "${WP_FILE_COUNT}" -lt 5 ]; then
  echo "FATAL: lohnour_wp_data appears empty. Refusing to launch stack." >&2
  exit 1
fi
if [ "${DB_FILE_COUNT}" -lt 5 ]; then
  echo "FATAL: lohnour_db_data appears empty. Refusing to launch stack." >&2
  exit 1
fi

# Verify networks exist
if ! ${DOCKER_CMD} network inspect lohnour_db_network >/dev/null 2>&1; then
  echo "FATAL: Network lohnour_db_network does not exist." >&2
  exit 1
fi
if ! ${DOCKER_CMD} network inspect proxy_network >/dev/null 2>&1; then
  echo "FATAL: Network proxy_network does not exist." >&2
  exit 1
fi

# Verify existing workloads: Portainer and Node-RED
PORTAINER_STATUS=$(${DOCKER_CMD} inspect -f '{{.State.Status}}' portainer 2>/dev/null || echo "missing")
NODERED_STATUS=$(${DOCKER_CMD} inspect -f '{{.State.Status}}' nodered 2>/dev/null || echo "missing")

echo "Portainer status: ${PORTAINER_STATUS}"
echo "Node-RED status: ${NODERED_STATUS}"

if [ "${PORTAINER_STATUS}" != "running" ]; then
  echo "FATAL: Portainer container is not running (status: ${PORTAINER_STATUS})." >&2
  exit 1
fi
if [ "${NODERED_STATUS}" != "running" ]; then
  echo "FATAL: Node-RED container is not running (status: ${NODERED_STATUS})." >&2
  exit 1
fi

echo "Preflight safety gates: PASSED."

# ------------------------------------------------------------------------------
# 2. DEPLOY APPLICATION STACK VIA DOCKER COMPOSE
# ------------------------------------------------------------------------------
echo "--- [2/6] Deploying Stack 'lohnour-stack' ---"

cd /opt/lohnour
${SUDO_CMD} docker compose -p lohnour-stack -f "${COMPOSE_FILE}" up -d

# ------------------------------------------------------------------------------
# 3. BOUNDED READINESS WAIT LOOP
# ------------------------------------------------------------------------------
echo "--- [3/6] Awaiting Healthy Status ---"

MAX_WAIT=90
ELAPSED=0
DB_HEALTH=""
WP_HEALTH=""

echo "Polling container health for up to ${MAX_WAIT} seconds..."
while [ "${ELAPSED}" -lt "${MAX_WAIT}" ]; do
  DB_HEALTH=$(${DOCKER_CMD} inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}{{.State.Status}}{{end}}' lohnour-db 2>/dev/null || echo "not_found")
  WP_HEALTH=$(${DOCKER_CMD} inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}{{.State.Status}}{{end}}' lohnour-wordpress 2>/dev/null || echo "not_found")
  
  echo "  [${ELAPSED}s] lohnour-db: ${DB_HEALTH} | lohnour-wordpress: ${WP_HEALTH}"
  
  if [ "${DB_HEALTH}" = "healthy" ] && [ "${WP_HEALTH}" = "healthy" ]; then
    echo "Both containers report healthy!"
    break
  fi
  
  sleep 5
  ELAPSED=$((ELAPSED + 5))
done

if [ "${DB_HEALTH}" != "healthy" ] || [ "${WP_HEALTH}" != "healthy" ]; then
  echo "FATAL: Stack containers failed to reach healthy state within ${MAX_WAIT} seconds." >&2
  echo "=== lohnour-db logs ==="
  ${DOCKER_CMD} logs --tail 30 lohnour-db || true
  echo "=== lohnour-wordpress logs ==="
  ${DOCKER_CMD} logs --tail 30 lohnour-wordpress || true
  exit 1
fi

# ------------------------------------------------------------------------------
# 4. DEPLOYMENT RECONCILIATION & WORKLOAD ISOLATION AUDIT
# ------------------------------------------------------------------------------
echo "--- [4/6] Verifying Workload Isolation & Port Constraints ---"

# Confirm zero public host port exposure for MariaDB (3306)
DB_PORTS=$(${DOCKER_CMD} inspect -f '{{range $p, $conf := .HostConfig.PortBindings}}{{$p}} -> {{(index $conf 0).HostPort}} {{end}}' lohnour-db 2>/dev/null || echo "")
echo "lohnour-db published host ports: '${DB_PORTS}'"
if [ -n "${DB_PORTS}" ]; then
  echo "FATAL: MariaDB has published host ports: ${DB_PORTS}. Must be private!" >&2
  exit 1
fi

# Confirm zero host port exposure for WordPress in Phase 12
WP_PORTS=$(${DOCKER_CMD} inspect -f '{{range $p, $conf := .HostConfig.PortBindings}}{{$p}} -> {{(index $conf 0).HostPort}} {{end}}' lohnour-wordpress 2>/dev/null || echo "")
echo "lohnour-wordpress published host ports: '${WP_PORTS}'"
if [ -n "${WP_PORTS}" ]; then
  echo "FATAL: WordPress has published host ports: ${WP_PORTS}. Phase 12 requires zero host ports." >&2
  exit 1
fi

# Confirm existing workloads remain healthy and untouched
PORTAINER_AFTER=$(${DOCKER_CMD} inspect -f '{{.State.Status}}' portainer 2>/dev/null || echo "missing")
NODERED_AFTER=$(${DOCKER_CMD} inspect -f '{{.State.Status}}' nodered 2>/dev/null || echo "missing")

echo "Portainer post-deploy status: ${PORTAINER_AFTER}"
echo "Node-RED post-deploy status: ${NODERED_AFTER}"

if [ "${PORTAINER_AFTER}" != "running" ] || [ "${NODERED_AFTER}" != "running" ]; then
  echo "FATAL: Unrelated production workload was disrupted!" >&2
  exit 1
fi

# ------------------------------------------------------------------------------
# 5. INTERNAL APPLICATION & DATABASE BOOTSTRAP VERIFICATION
# ------------------------------------------------------------------------------
echo "--- [5/6] Executing Internal Application Smoke Tests ---"

# 5.1 Docker DNS: lohnour-wordpress -> db:3306
echo "Testing Docker DNS resolution from wordpress to db:3306..."
${DOCKER_CMD} exec lohnour-wordpress php -r '
$fp = @fsockopen("db", 3306, $errno, $errstr, 5);
if ($fp) {
    echo "DNS & TCP Connect SUCCESS: Connected to db:3306\n";
    fclose($fp);
} else {
    fwrite(STDERR, "FATAL: Cannot connect to db:3306 ($errno: $errstr)\n");
    exit(1);
}
'

# 5.2 PHP Runtime Limits Injection Test
echo "Verifying custom PHP runtime limits from uploads.ini..."
LIMITS_OUTPUT=$(${DOCKER_CMD} exec lohnour-wordpress php -i | grep -E 'upload_max_filesize|post_max_size|memory_limit')
echo "${LIMITS_OUTPUT}"

echo "${LIMITS_OUTPUT}" | grep -q "upload_max_filesize => 512M => 512M" || { echo "FATAL: upload_max_filesize not 512M"; exit 1; }
echo "${LIMITS_OUTPUT}" | grep -q "post_max_size => 512M => 512M" || { echo "FATAL: post_max_size not 512M"; exit 1; }
echo "${LIMITS_OUTPUT}" | grep -q "memory_limit => 512M => 512M" || { echo "FATAL: memory_limit not 512M"; exit 1; }

# 5.3 In-Container WordPress Bootstrap & Database Connectivity Test
echo "Verifying WordPress core bootstrap & database query..."
${DOCKER_CMD} exec lohnour-wordpress php -r '
define("WP_USE_THEMES", false);
require_once "/var/www/html/wp-load.php";
global $wpdb;

if (!$wpdb->check_connection()) {
    fwrite(STDERR, "FATAL: Database check_connection failed.\n");
    exit(1);
}

$siteurl = get_option("siteurl");
$home = get_option("home");
$post_count = $wpdb->get_var("SELECT COUNT(*) FROM wp_posts");
$table_count = count($wpdb->get_col("SHOW TABLES"));

echo "WordPress Bootstrap Verification:\n";
echo "  siteurl: " . $siteurl . "\n";
echo "  home: " . $home . "\n";
echo "  post_count: " . $post_count . "\n";
echo "  table_count: " . $table_count . "\n";

if ($siteurl !== "https://pro.lohnour.com" || $home !== "https://pro.lohnour.com") {
    fwrite(STDERR, "FATAL: Unexpected siteurl or home options.\n");
    exit(1);
}
if ((int)$post_count !== 92) {
    fwrite(STDERR, "FATAL: Expected exactly 92 posts, observed: " . $post_count . "\n");
    exit(1);
}
if ((int)$table_count !== 14) {
    fwrite(STDERR, "FATAL: Expected exactly 14 tables, observed: " . $table_count . "\n");
    exit(1);
}
echo "WordPress Core & Database Bootstrap: 100% PASS\n";
'

# 5.4 Internal HTTP Smoke Probe via proxy_network (Reverse Proxy Simulation)
echo "Testing internal HTTP smoke test via proxy_network (simulating HTTPS reverse proxy ingress)..."
HTTP_RESPONSE=$(${DOCKER_CMD} run --rm --network proxy_network curlimages/curl:8.5.0 \
  -s -o /dev/null -w "%{http_code}" --max-time 10 -H 'Host: pro.lohnour.com' -H 'X-Forwarded-Proto: https' http://lohnour-wordpress/)

echo "Internal HTTP response code: ${HTTP_RESPONSE}"
if [ "${HTTP_RESPONSE}" != "200" ]; then
  echo "FATAL: Expected HTTP 200 from lohnour-wordpress, got: ${HTTP_RESPONSE}" >&2
  exit 1
fi

# Fetch and verify rendered title & content
CONTENT_SAMPLE=$(${DOCKER_CMD} run --rm --network proxy_network curlimages/curl:8.5.0 \
  -s --max-time 10 -H 'Host: pro.lohnour.com' -H 'X-Forwarded-Proto: https' http://lohnour-wordpress/)

echo "--- Response Sample ---"
echo "${CONTENT_SAMPLE}" | grep -i '<title>' || true

# Verify setup wizard is NOT displayed
if echo "${CONTENT_SAMPLE}" | grep -iq "wp-admin/setup-config.php"; then
  echo "FATAL: WordPress returned setup wizard! Database connection failed or empty." >&2
  exit 1
fi

# ------------------------------------------------------------------------------
# 6. ACCEPTANCE SUMMARY
# ------------------------------------------------------------------------------
echo "=============================================================================="
echo "PHASE 12 ACCEPTANCE VERIFICATION: ALL CHECKS PASSED (10/10)"
echo "  1. lohnour-db container: healthy"
echo "  2. lohnour-wordpress container: healthy"
echo "  3. WordPress volume lohnour_wp_data: mounted and intact"
echo "  4. Database volume lohnour_db_data: mounted and preserved (14 tables)"
echo "  5. MariaDB port 3306: private, 0 host ports published"
echo "  6. WordPress host ports: 0 host ports published"
echo "  7. PHP runtime limits: 512M memory/upload/post verified"
echo "  8. Internal DNS & TCP: wordpress -> db:3306 verified"
echo "  9. WordPress bootstrap: siteurl=https://pro.lohnour.com, 92 posts verified"
echo " 10. Internal HTTP probe: HTTP 200, zero setup wizard"
echo "=============================================================================="
echo "FINAL PHASE 12 EXECUTION STATUS:"
echo "PHASE_12_SUCCESS — WORDPRESS_DEPLOYED_AND_VERIFIED"
echo "=============================================================================="

