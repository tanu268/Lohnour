#!/usr/bin/env bash
set -euo pipefail

echo "========================================================"
echo "PHASE 6A FORENSIC DIAGNOSIS - ORACLE PRODUCTION"
echo "Date: $(date -u)"
echo "Host: $(hostname)"
echo "IP: $(curl -s -4 https://ifconfig.me || true)"
echo "========================================================"

WP_CONT="lohnour-wordpress-1"
NPM_CONT="lohnour-npm-1"
DB_CONT="lohnour-db-1"

echo ""
echo "=== 1. VERIFY ACTUAL HTTP RESPONSES (VIA NPM HTTPS) ==="
ROUTES=(
  "/"
  "/services/"
  "/services/corporate/"
  "/coaching/"
  "/coaching/individual/"
  "/coaching/group/"
  "/guides/"
  "/about/"
  "/contact/"
  "/blog/"
)

printf "%-25s | %-6s | %-12s | %-10s | %-10s | %-8s\n" "Route" "Status" "Content-Type" "Bytes" "Time(s)" "Redirect"
printf "%-25s-+-%-6s-+-%-12s-+-%-10s-+-%-10s-+-%-8s\n" "-------------------------" "------" "------------" "----------" "----------" "--------"

for r in "${ROUTES[@]}"; do
  url="https://pro.lohnour.com${r}"
  raw_file="/tmp/route_resp_$(echo "$r" | tr '/' '_').html"
  resp_meta=$(curl -s -k -w "%{http_code}|%{content_type}|%{size_download}|%{time_total}|%{redirect_url}" -o "$raw_file" "$url")
  IFS='|' read -r status ctype size duration redir <<< "$resp_meta"
  printf "%-25s | %-6s | %-12s | %-10s | %-10s | %-8s\n" "$r" "$status" "${ctype:0:12}" "$size" "$duration" "$redir"
done

echo ""
echo "=== 1B. HTML STRUCTURAL COMPARISON OF SAVED ROUTES ==="
for path in "/" "/services/" "/about/" "/contact/" "/blog/" "/guides/" "/services/corporate/" "/coaching/"; do
  file="/tmp/route_resp_$(echo "$path" | tr '/' '_').html"
  if [ -f "$file" ]; then
    title=$(grep -o -i '<title>[^<]*</title>' "$file" | head -1 || echo "NO_TITLE")
    body_tag=$(grep -o -i '<body[^>]*>' "$file" | head -1 || echo "NO_BODY_TAG")
    body_classes=$(echo "$body_tag" | grep -o -i 'class="[^"]*"' || echo "NO_CLASSES")
    main_count=$(grep -c -i '<main' "$file" || true)
    main_bytes=$(awk '/<main/{flag=1; next} /<\/main>/{flag=0} flag' "$file" | wc -c || true)
    header_count=$(grep -c -i '<header' "$file" || true)
    footer_count=$(grep -c -i '<footer' "$file" || true)
    total_bytes=$(wc -c < "$file")
    echo "--- Route: $path ---"
    echo "  Total size: $total_bytes bytes"
    echo "  Title: $title"
    echo "  Body tag classes: $body_classes"
    echo "  <header> present: $header_count | <main> present: $main_count (content ~ $main_bytes bytes) | <footer> present: $footer_count"
  fi
done

echo ""
echo "=== 2. CHECK WORDPRESS & APACHE & PHP ERROR LOGS ==="
echo "--- Docker lohnour-wordpress-1 recent logs (error/fatal/warning) ---"
sudo docker logs "$WP_CONT" --tail 200 2>&1 | grep -iE 'fatal|error|warning|notice|uncaught' | tail -n 30 || echo "None found in docker logs"

echo ""
echo "--- Apache error log inside container ---"
sudo docker exec "$WP_CONT" cat /var/log/apache2/error.log 2>/dev/null | tail -n 30 || echo "Apache error log empty or missing"

echo ""
echo "--- WordPress debug.log if present ---"
sudo docker exec "$WP_CONT" ls -lh /var/www/html/wp-content/debug.log 2>/dev/null || echo "debug.log does not exist"

echo ""
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
    // Determine query
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
sudo docker exec "$WP_CONT" wp db query "SELECT option_name, option_value FROM wp_options WHERE option_value LIKE '%localhost:8081%';" --allow-root || true
echo "--- Search in wp_options for 127.0.0.1 or 172. ---"
sudo docker exec "$WP_CONT" wp db query "SELECT option_name, SUBSTRING(option_value, 1, 60) FROM wp_options WHERE option_value LIKE '%127.0.0.1%' OR option_value LIKE '%172.%';" --allow-root || true
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
sudo docker exec "$WP_CONT" ls -la /var/www/html/wp-content/themes/twentytwentyfive-child/
echo "--- Header of template files ---"
sudo docker exec "$WP_CONT" head -n 25 /var/www/html/wp-content/themes/twentytwentyfive-child/page-about.php 2>/dev/null || true
sudo docker exec "$WP_CONT" head -n 25 /var/www/html/wp-content/themes/twentytwentyfive-child/page-contact.php 2>/dev/null || true
sudo docker exec "$WP_CONT" head -n 25 /var/www/html/wp-content/themes/twentytwentyfive-child/page.php 2>/dev/null || true
sudo docker exec "$WP_CONT" head -n 25 /var/www/html/wp-content/themes/twentytwentyfive-child/archive-lohnour_service.php 2>/dev/null || true

echo "=== FORENSIC GATHERING COMPLETE ==="
