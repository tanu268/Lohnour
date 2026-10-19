#!/usr/bin/env bash
# Remove -e to ensure everything executes even if a command yields no output

WP_CONT="lohnour-wordpress-1"
NPM_CONT="lohnour-npm-1"
DB_CONT="lohnour-db-1"

echo "=== 3. ACTIVE THEME & TEMPLATE RESOLUTION ==="
echo "--- Active Theme ---"
sudo docker exec "$WP_CONT" wp theme list --allow-root

echo ""
echo "--- Theme Files in twentytwentyfive-child ---"
sudo docker exec "$WP_CONT" ls -la /var/www/html/wp-content/themes/twentytwentyfive-child/

echo ""
echo "--- Simulating WordPress Template Hierarchy via WP-CLI ---"
sudo docker exec "$WP_CONT" wp eval '
$urls = [
    "/",
    "/services/",
    "/services/corporate/",
    "/coaching/",
    "/coaching/individual/",
    "/coaching/group/",
    "/guides/",
    "/about/",
    "/contact/",
    "/blog/"
];

foreach ($urls as $u) {
    $req = trim($u, "/");
    echo "ROUTE: " . $u . "\n";
    $page = get_page_by_path($req);
    if ($page) {
        $tpl = get_page_template_slug($page->ID);
        echo "  Matched Page: ID=" . $page->ID . ", Title=\"" . $page->post_title . "\", Slug=" . $page->post_name . ", Parent=" . $page->post_parent . "\n";
        echo "  Assigned Template Slug: " . ($tpl ? $tpl : "default (page.php)") . "\n";
    } else {
        echo "  get_page_by_path(\"" . $req . "\") returned NULL\n";
    }
}
' --allow-root

echo ""
echo "=== 4. DATABASE PAGE RECORDS (POSTS & TEMPLATES) ==="
echo "--- All Published Pages ---"
sudo docker exec "$WP_CONT" wp post list --post_type=page --post_status=publish,draft,private --fields=ID,post_name,post_title,post_parent,post_status --allow-root

echo ""
echo "--- Page Template Meta for Published Pages ---"
sudo docker exec "$WP_CONT" wp db query "SELECT post_id, meta_key, meta_value FROM wp_postmeta WHERE meta_key = '_wp_page_template';" --allow-root

echo ""
echo "--- Check Custom Post Types & Terms ---"
sudo docker exec "$WP_CONT" wp post-type list --allow-root
sudo docker exec "$WP_CONT" wp post list --post_type=lohnour_service,lohnour_workshop --fields=ID,post_name,post_title,post_type,post_status --allow-root

echo ""
echo "=== 5. WORDPRESS URL SETTINGS & RUNTIME CONFIG ==="
echo "--- siteurl & home ---"
sudo docker exec "$WP_CONT" wp option get siteurl --allow-root
sudo docker exec "$WP_CONT" wp option get home --allow-root

echo ""
echo "--- wp-config.php WP_HOME / WP_SITEURL constants ---"
sudo docker exec "$WP_CONT" grep -iE 'WP_HOME|WP_SITEURL|WP_DEBUG' /var/www/html/wp-config.php || echo "None defined in wp-config.php"

echo ""
echo "=== 6. SEARCH FOR REMAINING OLD/MIGRATED URLS ==="
echo "--- Search in wp_options for localhost:8081 ---"
sudo docker exec "$WP_CONT" wp db query "SELECT option_name, option_value FROM wp_options WHERE option_value LIKE '%localhost:8081%';" --allow-root
echo "--- Search in wp_options for 127.0.0.1 or 172. ---"
sudo docker exec "$WP_CONT" wp db query "SELECT option_name, SUBSTRING(option_value, 1, 60) FROM wp_options WHERE option_value LIKE '%127.0.0.1%' OR option_value LIKE '%172.%';" --allow-root
echo "--- Search in active theme files for hardcoded URLs or localhost ---"
sudo docker exec "$WP_CONT" grep -rnE 'localhost|127\.0\.0\.1' /var/www/html/wp-content/themes/twentytwentyfive-child/ || echo "No localhost in active theme"

echo ""
echo "=== 7. APACHE/PHP DIRECT VS NPM COMPARISON ==="
echo "--- Direct Apache response for / inside container ---"
sudo docker exec "$WP_CONT" curl -s -I -H "Host: pro.lohnour.com" http://localhost/ | head -n 10
echo "--- Direct Apache response for /services/ inside container ---"
sudo docker exec "$WP_CONT" curl -s -I -H "Host: pro.lohnour.com" http://localhost/services/ | head -n 10
echo "--- Direct Apache response for /about/ inside container ---"
sudo docker exec "$WP_CONT" curl -s -I -H "Host: pro.lohnour.com" http://localhost/about/ | head -n 10
echo "--- NPM to WordPress response for /services/ ---"
sudo docker exec "$NPM_CONT" curl -s -I -H "Host: pro.lohnour.com" http://wordpress/services/ | head -n 10

echo ""
echo "=== 8. CHECK PERMALINK STRUCTURE & REWRITE RULES ==="
sudo docker exec "$WP_CONT" wp rewrite structure --allow-root
sudo docker exec "$WP_CONT" wp db query "SELECT option_name, option_value FROM wp_options WHERE option_name = 'permalink_structure';" --allow-root

echo ""
echo "=== 9. INSPECT TEMPLATE SOURCE FILES (page.php, page-about.php, etc.) ==="
echo "--- Header of template files ---"
sudo docker exec "$WP_CONT" head -n 25 /var/www/html/wp-content/themes/twentytwentyfive-child/page-about.php 2>/dev/null || true
sudo docker exec "$WP_CONT" head -n 25 /var/www/html/wp-content/themes/twentytwentyfive-child/page-contact.php 2>/dev/null || true
sudo docker exec "$WP_CONT" head -n 25 /var/www/html/wp-content/themes/twentytwentyfive-child/page.php 2>/dev/null || true
sudo docker exec "$WP_CONT" head -n 25 /var/www/html/wp-content/themes/twentytwentyfive-child/archive-lohnour_service.php 2>/dev/null || true

echo ""
echo "=== 10. CHECK inc/contact-form.php (HEAD/BOM CHECK) ==="
sudo docker exec "$WP_CONT" head -n 5 /var/www/html/wp-content/themes/twentytwentyfive-child/inc/contact-form.php
sudo docker exec "$WP_CONT" xxd -l 32 /var/www/html/wp-content/themes/twentytwentyfive-child/inc/contact-form.php 2>/dev/null || od -c /var/www/html/wp-content/themes/twentytwentyfive-child/inc/contact-form.php | head -n 5

echo "=== FORENSIC GATHERING COMPLETE ==="
