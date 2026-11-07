import subprocess
import json

php_script = """<?php
define('WP_USE_THEMES', false);
require_once '/var/www/html/wp-load.php';
global $wpdb, $wp_version;
require '/var/www/html/wp-includes/version.php';

$tables = $wpdb->get_col('SHOW TABLES');
$posts = $wpdb->get_var('SELECT COUNT(ID) FROM wp_posts');
$siteurl = get_option('siteurl');
$home = get_option('home');
$db_rev = get_option('db_version');
$db_name = $wpdb->dbname;
$db_host = $wpdb->dbhost;
$db_user = $wpdb->dbuser;

echo json_encode([
    'db_connected' => $wpdb->check_connection(),
    'db_name' => $db_name,
    'db_host' => $db_host,
    'db_user' => $db_user,
    'table_count' => count($tables),
    'tables' => $tables,
    'post_count' => (int)$posts,
    'wp_version' => $wp_version,
    'db_version' => (int)$db_rev,
    'siteurl' => $siteurl,
    'home' => $home
]);
"""

with open("/tmp/audit_db.php", "w") as f:
    f.write(php_script)

p = subprocess.run("docker cp /tmp/audit_db.php lohnour-wordpress:/tmp/audit_db.php && docker exec lohnour-wordpress php /tmp/audit_db.php && docker exec lohnour-wordpress rm -f /tmp/audit_db.php", shell=True, capture_output=True, text=True)

# Also inspect container status, health, and mounts
def inspect_container(name):
    cmd = f"docker inspect {name} --format '{{{{json .State}}}}|||{{{{json .Mounts}}}}|||{{{{json .NetworkSettings.Networks}}}}'"
    out = subprocess.check_output(cmd, shell=True).decode().strip()
    state, mounts, networks = out.split("|||")
    return json.loads(state), json.loads(mounts), json.loads(networks)

wp_state, wp_mounts, wp_net = inspect_container("lohnour-wordpress")
db_state, db_mounts, db_net = inspect_container("lohnour-db")

result = {
    "wp_runtime": json.loads(p.stdout.strip()) if p.returncode == 0 else {"error": p.stderr},
    "containers": {
        "lohnour-wordpress": {
            "status": wp_state.get("Status"),
            "health": wp_state.get("Health", {}).get("Status"),
            "restart_count": wp_state.get("RestartCount"),
            "started_at": wp_state.get("StartedAt"),
            "mounts": wp_mounts,
            "networks": list(wp_net.keys())
        },
        "lohnour-db": {
            "status": db_state.get("Status"),
            "health": db_state.get("Health", {}).get("Status"),
            "restart_count": db_state.get("RestartCount"),
            "started_at": db_state.get("StartedAt"),
            "mounts": db_mounts,
            "networks": list(db_net.keys())
        }
    }
}

print(json.dumps(result, indent=2))

