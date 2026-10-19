#!/bin/bash
set -e

echo "=== 1. WORDPRESS SITEURL & HOME ==="
DB_PASS=$(grep DB_ROOT_PASSWORD /opt/lohnour/.env | cut -d= -f2)
sudo docker exec lohnour-db-1 mariadb -u root -p"$DB_PASS" lohnour_wp -e \
  "SELECT option_name, option_value FROM wp_options WHERE option_name IN ('siteurl', 'home');"

echo ""
echo "=== 2. OCCURRENCES OF 'localhost:8081' IN DATABASE ==="
sudo docker exec lohnour-db-1 mariadb -u root -p"$DB_PASS" lohnour_wp -e \
  "SELECT 'wp_options' as tbl, count(*) as count FROM wp_options WHERE option_value LIKE '%localhost:8081%'
   UNION ALL
   SELECT 'wp_posts (post_content)', count(*) FROM wp_posts WHERE post_content LIKE '%localhost:8081%'
   UNION ALL
   SELECT 'wp_posts (guid)', count(*) FROM wp_posts WHERE guid LIKE '%localhost:8081%'
   UNION ALL
   SELECT 'wp_postmeta', count(*) FROM wp_postmeta WHERE meta_value LIKE '%localhost:8081%'
   UNION ALL
   SELECT 'wp_comments', count(*) FROM wp_comments WHERE comment_content LIKE '%localhost:8081%';"

echo ""
echo "=== 3. FETCH HTTP / HTTPS RESPONSE ==="
curl -I -s --max-time 10 -H "Host: pro.lohnour.com" http://127.0.0.1/ | head -20

echo ""
echo "=== 4. INSPECT ASSET URLS IN HTML ==="
curl -s --max-time 15 -H "Host: pro.lohnour.com" http://127.0.0.1/ > /tmp/page.html
echo "Total occurrences of localhost:8081 in fetched HTML:"
grep -o "http://localhost:8081" /tmp/page.html | wc -l

echo ""
echo "Stylesheet links in HTML (first 10):"
grep -o 'href="http://[^"]*\.css[^"]*"' /tmp/page.html | head -10 || true

echo ""
echo "Script src in HTML (first 5):"
grep -o 'src="http://[^"]*\.js[^"]*"' /tmp/page.html | head -5 || true

echo ""
echo "Image src in HTML (first 5):"
grep -o 'src="http://[^"]*\.\(png\|jpg\|jpeg\|webp\|svg\|gif\)[^"]*"' /tmp/page.html | head -5 || true

echo ""
echo "=== 5. TEST SAMPLE ASSET REACHABILITY ==="
SAMPLE_CSS=$(grep -o 'http://localhost:8081/[^"'\'' ]*\.css[^"'\'' ]*' /tmp/page.html | head -1 || true)
SAMPLE_JS=$(grep -o 'http://localhost:8081/[^"'\'' ]*\.js[^"'\'' ]*' /tmp/page.html | head -1 || true)
SAMPLE_IMG=$(grep -o 'http://localhost:8081/[^"'\'' ]*\.\(png\|jpg\|jpeg\|webp\|svg\)[^"'\'' ]*' /tmp/page.html | head -1 || true)

echo "Sample CSS URL: $SAMPLE_CSS"
echo "Sample JS URL:  $SAMPLE_JS"
echo "Sample IMG URL: $SAMPLE_IMG"

echo ""
echo "Testing asset paths against container directly:"
CSS_PATH=$(echo "$SAMPLE_CSS" | sed 's|http://localhost:8081||')
if [ -n "$CSS_PATH" ]; then
  echo "Curling $CSS_PATH on container:"
  sudo docker exec lohnour-wordpress-1 curl -s -I "http://127.0.0.1$CSS_PATH" | head -5
fi

echo ""
echo "=== 7. THEME FILES EXISTENCE INSIDE CONTAINER ==="
sudo docker exec lohnour-wordpress-1 ls -la /var/www/html/wp-content/themes/
ACTIVE_THEME=$(sudo docker exec lohnour-db-1 mariadb -u root -p"$DB_PASS" lohnour_wp -se \
  "SELECT option_value FROM wp_options WHERE option_name='template';")
echo "Active theme option: $ACTIVE_THEME"
echo "Active theme directory contents:"
sudo docker exec lohnour-wordpress-1 ls -la "/var/www/html/wp-content/themes/$ACTIVE_THEME" | head -20
