import subprocess
import json
import sys

def run_cmd(cmd):
    p = subprocess.run(cmd, shell=True, capture_output=True, text=True)
    return p.stdout.strip(), p.stderr.strip(), p.returncode

print("=== PHASE 12 INTERNAL ACCEPTANCE AUDIT ===")

# 1 & 2. Container health
wp_status, _, _ = run_cmd("docker inspect lohnour-wordpress --format '{{.State.Status}} ({{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}})'")
db_status, _, _ = run_cmd("docker inspect lohnour-db --format '{{.State.Status}} ({{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}})'")
print(f"lohnour-wordpress: {wp_status}")
print(f"lohnour-db: {db_status}")

# 3, 4, 5, 6, 7. Database & WordPress version check via wp-load.php
php_check = """<?php
define('WP_USE_THEMES', false);
require_once '/var/www/html/wp-load.php';
global $wpdb;
require '/var/www/html/wp-includes/version.php';
$tables = $wpdb->get_col('SHOW TABLES');
$posts = $wpdb->get_var('SELECT COUNT(ID) FROM wp_posts');
$db_rev = get_option('db_version');
echo json_encode([
    'db_connected' => $wpdb->check_connection(),
    'table_count' => count($tables),
    'tables' => $tables,
    'post_count' => (int)$posts,
    'wp_version' => $wp_version,
    'db_version' => (int)$db_rev
]);
"""
with open("/tmp/wp_check.php", "w") as f:
    f.write(php_check)

out, err, code = run_cmd("docker cp /tmp/wp_check.php lohnour-wordpress:/tmp/wp_check.php && docker exec lohnour-wordpress php /tmp/wp_check.php && docker exec lohnour-wordpress rm -f /tmp/wp_check.php")
print(f"WP check out: [{out}]")
print(f"WP check err: [{err}]")
if code == 0 and out:
    data = json.loads(out)
    print("Database connected:", data['db_connected'])
    print("Table count:", data['table_count'])
    print("Tables:", data['tables'])
    print("Post count:", data['post_count'])
    print("WordPress version:", data['wp_version'])
    print("Database revision:", data['db_version'])
else:
    print(f"Error checking WP: {err} (code: {code})")

# 8. Mount points
wp_mounts, _, _ = run_cmd("docker inspect lohnour-wordpress --format '{{json .Mounts}}'")
db_mounts, _, _ = run_cmd("docker inspect lohnour-db --format '{{json .Mounts}}'")
print(f"WordPress mounts:\n{wp_mounts}")
print(f"MariaDB mounts:\n{db_mounts}")

# 9 & 10. Port exposure
wp_ports, _, _ = run_cmd("docker inspect lohnour-wordpress --format '{{json .NetworkSettings.Ports}}'")
db_ports, _, _ = run_cmd("docker inspect lohnour-db --format '{{json .NetworkSettings.Ports}}'")
print(f"WordPress ports: {wp_ports}")
print(f"MariaDB ports: {db_ports}")

# 11. .env permissions
env_stat, _, _ = run_cmd("stat -c '%a %U:%G' /opt/lohnour/.env")
print(f".env stat: {env_stat}")

# 12. Portainer & Node-RED status
portainer_status, _, _ = run_cmd("docker inspect portainer --format '{{.State.Status}}'")
nodered_status, _, _ = run_cmd("docker inspect nodered --format '{{.State.Status}}'")
print(f"Portainer: {portainer_status}")
print(f"Node-RED: {nodered_status}")

# 13 & 14. Restart counts / crash loops
wp_restarts, _, _ = run_cmd("docker inspect lohnour-wordpress --format 'RestartCount: {{.RestartCount}}'")
db_restarts, _, _ = run_cmd("docker inspect lohnour-db --format 'RestartCount: {{.RestartCount}}'")
print(f"WordPress restarts: {wp_restarts}")
print(f"MariaDB restarts: {db_restarts}")

# 15. Internal routes
routes = ["/", "/wp-login.php", "/wp-json/", "/about/", "/services/"]
print("\n--- Internal Route Probes (via proxy_network) ---")
for r in routes:
    cmd = f"docker run --rm --network proxy_network curlimages/curl:8.5.0 -s -o /dev/null -w '%{{http_code}}' --max-time 10 -H 'Host: pro.lohnour.com' -H 'X-Forwarded-Proto: https' http://lohnour-wordpress{r}"
    code_out, _, _ = run_cmd(cmd)
    print(f"Route {r} -> HTTP {code_out}")

