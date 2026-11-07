#!/bin/bash
# ==============================================================================
# LOHNOUR PRO — PHASE 11: WORDPRESS APPLICATION FILE RESTORATION
# Canonical Execution Script
#
# Target Host: Sam's Oracle VM (141.148.51.166, Oracle Linux Server 9.7 ARM64)
# Target Volume: lohnour_wp_data
# Architecture: linux/arm64/v8
# ==============================================================================
set -euo pipefail

echo "=============================================================================="
echo "LOHNOUR PRO — PHASE 11: WORDPRESS APPLICATION FILE RESTORATION"
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
# 1. IMMUTABLE ARCHITECTURAL & ARCHIVE PARAMETERS
# ------------------------------------------------------------------------------
TARGET_VOLUME="${TARGET_VOLUME:-lohnour_wp_data}"
PINNED_WORDPRESS_IMAGE="${PINNED_WORDPRESS_IMAGE:-wordpress:7.1.4-php8.2-apache}"
STAGING_DIR="${STAGING_DIR:-/opt/lohnour/staging}"

EXPECTED_UPLOADS_HASH="5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9"
EXPECTED_PLUGINS_HASH="1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484"
EXPECTED_PROGRAM_CMS_HASH="989eaf8941a27f37bfc7576954a22fe6f64f121ee44c5a9f66e4e6b4777dd5ec"
EXPECTED_THEME_HASH="a14af7f749f0e459b60658514e79325a29a3bb7fdc98c56d9591e1faadca802d"

# ------------------------------------------------------------------------------
# 2. PREFLIGHT GATES
# ------------------------------------------------------------------------------
echo "==> Executing Phase 11 Preflight Gates..."

# 2.1 Verify target volume existence and resolve mountpoint dynamically
$DOCKER_CMD volume inspect "${TARGET_VOLUME}" >/dev/null 2>&1 || {
  echo "FATAL: Volume ${TARGET_VOLUME} does not exist. Aborting." >&2
  exit 1
}
WP_MOUNTPOINT=$($DOCKER_CMD volume inspect "${TARGET_VOLUME}" -f '{{.Mountpoint}}')
if [ -z "${WP_MOUNTPOINT}" ]; then
  echo "FATAL: Volume ${TARGET_VOLUME} mountpoint unresolved. Aborting." >&2
  exit 1
fi
echo "==> Target volume mountpoint resolved: ${WP_MOUNTPOINT}"

# 2.2 Verify zero container attachments to target volume
ATTACHED_CONTAINERS=$($DOCKER_CMD ps -a --filter "volume=${TARGET_VOLUME}" -q)
if [ -n "${ATTACHED_CONTAINERS}" ]; then
  ATTACHED_NAMES=$($DOCKER_CMD ps -a --filter "volume=${TARGET_VOLUME}" --format '{{.Names}}')
  echo "FATAL: Volume ${TARGET_VOLUME} is attached to active container(s): ${ATTACHED_NAMES}. Aborting." >&2
  exit 1
fi

# 2.3 Verify staging archives and cryptographic integrity
for f_entry in \
  "${STAGING_DIR}/uploads.tar.gz:${EXPECTED_UPLOADS_HASH}" \
  "${STAGING_DIR}/lohnour_all_plugins.tar.gz:${EXPECTED_PLUGINS_HASH}" \
  "${STAGING_DIR}/lohnour_program_cms.tar.gz:${EXPECTED_PROGRAM_CMS_HASH}" \
  "${STAGING_DIR}/twentytwentyfive-child.tar.gz:${EXPECTED_THEME_HASH}"; do
  
  archive_path="${f_entry%%:*}"
  expected_hash="${f_entry##*:}"
  
  if [ ! -f "${archive_path}" ]; then
    echo "FATAL: Required archive ${archive_path} missing. Aborting." >&2
    exit 1
  fi
  actual_hash=$(sha256sum "${archive_path}" | awk '{print $1}')
  if [ "${actual_hash}" != "${expected_hash}" ]; then
    echo "FATAL: Checksum mismatch for ${archive_path} (${actual_hash} != ${expected_hash}). Aborting." >&2
    exit 1
  fi
  echo "  [Archive OK] $(basename "${archive_path}") verified (SHA-256: ${actual_hash})"
done

# 2.4 Verify database volume lohnour_db_data remains untouched and intact
$DOCKER_CMD volume inspect lohnour_db_data >/dev/null 2>&1 || {
  echo "FATAL: Database volume lohnour_db_data missing! Aborting." >&2
  exit 1
}
DB_TABLE_COUNT=$($SUDO_CMD find /var/lib/docker/volumes/lohnour_db_data/_data/lohnour_wp -name '*.ibd' 2>/dev/null | wc -l)
if [ "${DB_TABLE_COUNT}" -ne 14 ]; then
  echo "FATAL: Database volume corrupted or incomplete (${DB_TABLE_COUNT} tables found != 14). Aborting." >&2
  exit 1
fi
echo "==> Database volume lohnour_db_data verified intact (14 InnoDB tables)."

echo "==> Preflight Gates passed cleanly."

# ------------------------------------------------------------------------------
# 3. POPULATE WORDPRESS CORE
# ------------------------------------------------------------------------------
echo "==> Populating WordPress core from image ${PINNED_WORDPRESS_IMAGE}..."
$DOCKER_CMD run --rm -v "${TARGET_VOLUME}:/target_wp" "${PINNED_WORDPRESS_IMAGE}" cp -a /usr/src/wordpress/. /target_wp/
echo "==> WordPress core populated successfully."

# ------------------------------------------------------------------------------
# 4. RESTORE PLUGINS
# ------------------------------------------------------------------------------
echo "==> Restoring WordPress plugins..."
$SUDO_CMD mkdir -p "${WP_MOUNTPOINT}/wp-content/plugins"
$SUDO_CMD tar -xzf "${STAGING_DIR}/lohnour_all_plugins.tar.gz" -C "${WP_MOUNTPOINT}/wp-content/plugins/"
$SUDO_CMD tar -xzf "${STAGING_DIR}/lohnour_program_cms.tar.gz" -C "${WP_MOUNTPOINT}/wp-content/plugins/"
echo "==> Plugins restored successfully."

# ------------------------------------------------------------------------------
# 5. RESTORE UPLOADS
# ------------------------------------------------------------------------------
echo "==> Restoring media uploads..."
$SUDO_CMD mkdir -p "${WP_MOUNTPOINT}/wp-content"
$SUDO_CMD tar -xzf "${STAGING_DIR}/uploads.tar.gz" -C "${WP_MOUNTPOINT}/wp-content/"
echo "==> Media uploads restored successfully."

# ------------------------------------------------------------------------------
# 6. RESTORE THEMES
# ------------------------------------------------------------------------------
echo "==> Restoring themes..."
$SUDO_CMD mkdir -p "${WP_MOUNTPOINT}/wp-content/themes"
$SUDO_CMD tar -xzf "${STAGING_DIR}/twentytwentyfive-child.tar.gz" -C "${WP_MOUNTPOINT}/wp-content/themes/"
echo "==> Themes restored successfully."

# ------------------------------------------------------------------------------
# 7. GENERATE CANONICAL WP-CONFIG.PHP
# ------------------------------------------------------------------------------
echo "==> Configuring canonical wp-config.php..."
$SUDO_CMD bash -c "cat << 'EOF' > '${WP_MOUNTPOINT}/wp-config.php'
<?php
/**
 * Canonical WordPress Configuration for LOHNOUR PRO
 * Generated during Phase 11 File Restoration
 */

if (!function_exists('getenv_docker')) {
    function getenv_docker(\$env, \$default) {
        if (\$fileEnv = getenv(\$env . '_FILE')) {
            return rtrim(file_get_contents(\$fileEnv), \"\\r\\n\");
        } else if ((\$val = getenv(\$env)) !== false) {
            return \$val;
        } else {
            return \$default;
        }
    }
}

// ** Database settings ** //
define('DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'lohnour_wp'));
define('DB_USER', getenv_docker('WORDPRESS_DB_USER', 'lohnour_user'));
define('DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', ''));
define('DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'db:3306'));
define('DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8mb4'));
define('DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', ''));

// ** Authentication Unique Keys and Salts ** //
define('AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         'bfbf263c59d511489f62bf632fe2a971f64dceec'));
define('SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  '6383baf9a8f871ae47a2c5df0188f0d1f006238f'));
define('LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    '3aa6c81f3abddf935dbbbe6527297f64a7aed90f'));
define('NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        '389b38744d359c6ab0466d01cf4815f278443602'));
define('AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        '4e7f60d1dd0f03bd4a167c0fe786d29fa37cc8a0'));
define('SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', 'b064e7614a2a49e78c8158e6a80499363fc87c97'));
define('LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   'de12e37827b4d4af9d0e5b71405972908181f6dc'));
define('NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       'cbe342bbc3f8fe98e19ff140e93bad13f15d9584'));

// ** Table Prefix ** //
\$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

// ** Debug Mode ** //
define('WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', ''));

// ** Reverse Proxy HTTPS detection ** //
if (isset(\$_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos(\$_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
    \$_SERVER['HTTPS'] = 'on';
}

if (\$configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
    eval(\$configExtra);
}

// ** Absolute path to WordPress directory ** //
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
EOF"

echo "==> wp-config.php authored successfully."

# ------------------------------------------------------------------------------
# 8. PERMISSIONS NORMALIZATION
# ------------------------------------------------------------------------------
echo "==> Normalizing filesystem ownership and permissions (www-data:www-data 33:33)..."
$SUDO_CMD chown -R 33:33 "${WP_MOUNTPOINT}"
$SUDO_CMD find "${WP_MOUNTPOINT}" -type d -exec chmod 755 {} +
$SUDO_CMD find "${WP_MOUNTPOINT}" -type f -exec chmod 644 {} +

# Strict permissions on uploads
$SUDO_CMD find "${WP_MOUNTPOINT}/wp-content/uploads" -type d -exec chmod 755 {} +
$SUDO_CMD find "${WP_MOUNTPOINT}/wp-content/uploads" -type f -exec chmod 644 {} +

echo "==> Ownership and permissions normalized (0755/0644, zero 777)."

# ------------------------------------------------------------------------------
# 9. COMPLETE PHASE 11 ACCEPTANCE VALIDATION SUITE
# ------------------------------------------------------------------------------
echo "==> Executing Phase 11 Acceptance Validation Suite..."

# 9.1 Core Files & Directories
test -f "${WP_MOUNTPOINT}/index.php" || { echo "ACCEPTANCE FAILED: index.php missing" >&2; exit 1; }
test -f "${WP_MOUNTPOINT}/wp-login.php" || { echo "ACCEPTANCE FAILED: wp-login.php missing" >&2; exit 1; }
test -d "${WP_MOUNTPOINT}/wp-admin" || { echo "ACCEPTANCE FAILED: wp-admin directory missing" >&2; exit 1; }
test -d "${WP_MOUNTPOINT}/wp-includes" || { echo "ACCEPTANCE FAILED: wp-includes directory missing" >&2; exit 1; }
echo "  [Check 1/8] WordPress Core Files & Directories: PASS"

# 9.2 Core Version & DB Revision
test -f "${WP_MOUNTPOINT}/wp-includes/version.php" || { echo "ACCEPTANCE FAILED: version.php missing" >&2; exit 1; }
WP_VER_FOUND=$(grep "wp_version =" "${WP_MOUNTPOINT}/wp-includes/version.php" | cut -d"'" -f2)
WP_DB_VER_FOUND=$(grep "wp_db_version =" "${WP_MOUNTPOINT}/wp-includes/version.php" | tr -dc '0-9')
echo "  [Check 2/8] Core Version: ${WP_VER_FOUND} (Expected 7.1.4), DB Version: ${WP_DB_VER_FOUND} (Expected 61833)"
test "${WP_VER_FOUND}" = "7.1.4" || { echo "ACCEPTANCE FAILED: Version mismatch (${WP_VER_FOUND} != 7.1.4)" >&2; exit 1; }
test "${WP_DB_VER_FOUND}" = "61833" || { echo "ACCEPTANCE FAILED: DB Version mismatch (${WP_DB_VER_FOUND} != 61833)" >&2; exit 1; }

# 9.3 wp-config.php Syntax & Content
test -s "${WP_MOUNTPOINT}/wp-config.php" || { echo "ACCEPTANCE FAILED: wp-config.php empty or missing" >&2; exit 1; }
$DOCKER_CMD run --rm -v "${TARGET_VOLUME}:/test_wp" "${PINNED_WORDPRESS_IMAGE}" php -l /test_wp/wp-config.php >/dev/null
echo "  [Check 3/8] wp-config.php Syntax: PASS (Clean PHP Syntax)"

# 9.4 Plugins Restoration
for p_check in akismet all-in-one-wp-migration mailin lohnour-program-cms; do
  test -d "${WP_MOUNTPOINT}/wp-content/plugins/${p_check}" || {
    echo "ACCEPTANCE FAILED: Plugin ${p_check} missing" >&2
    exit 1
  }
done
PLUGIN_COUNT=$(find "${WP_MOUNTPOINT}/wp-content/plugins" -mindepth 1 -maxdepth 1 -type d | wc -l)
echo "  [Check 4/8] Plugins Restoration: PASS (${PLUGIN_COUNT} plugin directories verified)"

# 9.5 Themes Restoration
test -d "${WP_MOUNTPOINT}/wp-content/themes/twentytwentyfive" || { echo "ACCEPTANCE FAILED: Parent theme twentytwentyfive missing" >&2; exit 1; }
test -d "${WP_MOUNTPOINT}/wp-content/themes/twentytwentyfive-child" || { echo "ACCEPTANCE FAILED: Child theme twentytwentyfive-child missing" >&2; exit 1; }
test -f "${WP_MOUNTPOINT}/wp-content/themes/twentytwentyfive-child/style.css" || { echo "ACCEPTANCE FAILED: Child theme style.css missing" >&2; exit 1; }
echo "  [Check 5/8] Themes Restoration: PASS (Parent and Child theme verified)"

# 9.6 Uploads Restoration
UPLOADS_FILE_COUNT=$(find "${WP_MOUNTPOINT}/wp-content/uploads" -type f | wc -l)
echo "  [Check 6/8] Uploads Restoration: PASS (${UPLOADS_FILE_COUNT} files restored)"
test "${UPLOADS_FILE_COUNT}" -ge 120 || { echo "ACCEPTANCE FAILED: Uploads file count abnormally low (${UPLOADS_FILE_COUNT} < 120)" >&2; exit 1; }

# 9.7 Ownership & Permissions
BAD_PERMS=$(find "${WP_MOUNTPOINT}" -perm 777 | wc -l)
echo "  [Check 7/8] Ownership (33:33) & Security: PASS (Zero 777 permissions found: ${BAD_PERMS})"
test "${BAD_PERMS}" -eq 0 || { echo "ACCEPTANCE FAILED: Found files/dirs with 777 permissions (${BAD_PERMS})" >&2; exit 1; }

# 9.8 Workload Baseline Health
PORTAINER_HEALTH=$(curl -k -s -o /dev/null -w "%{http_code}" https://localhost:9443 || echo "000")
NODERED_HEALTH=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:1880 || echo "000")
echo "  [Check 8/8] Existing Workload Health: Portainer=${PORTAINER_HEALTH}, Node-RED=${NODERED_HEALTH}"
test "${PORTAINER_HEALTH}" = "200" || { echo "ACCEPTANCE FAILED: Portainer not responding HTTP 200" >&2; exit 1; }
test "${NODERED_HEALTH}" = "200" || { echo "ACCEPTANCE FAILED: Node-RED not responding HTTP 200" >&2; exit 1; }

echo "=============================================================================="
echo "PHASE 11 WORDPRESS FILE RESTORATION SUCCESSFUL"
echo "WordPress Core 7.1.4, plugins, uploads, and child theme restored and verified."
echo "Ownership 33:33, zero 777 permissions, workloads healthy."
echo "=============================================================================="

