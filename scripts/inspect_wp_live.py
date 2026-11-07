import subprocess
import json

php_code = """<?php
define('WP_USE_THEMES', false);
require '/var/www/html/wp-load.php';
global $wpdb;
echo json_encode([
    'tables' => count($wpdb->get_col('SHOW TABLES')),
    'posts' => (int)$wpdb->get_var('SELECT COUNT(ID) FROM wp_posts'),
    'siteurl' => get_option('siteurl'),
    'home' => get_option('home'),
    'db_version' => (int)get_option('db_version')
]);
"""

with open("/tmp/check_wp_stats.php", "w") as f:
    f.write(php_code)

p = subprocess.run("docker cp /tmp/check_wp_stats.php lohnour-wordpress:/tmp/check_wp_stats.php && docker exec lohnour-wordpress php /tmp/check_wp_stats.php && docker exec lohnour-wordpress rm -f /tmp/check_wp_stats.php", shell=True, capture_output=True, text=True)
print("WP STATS:", p.stdout.strip())

