#!/bin/bash
set -e

TIMESTAMP=$(date +"%Y%m%d-%H%M")
BACKUP_DIR="/opt/lohnour/phase5-pre-url-migration-${TIMESTAMP}"

echo "=========================================================="
echo "PHASE 5 EXECUTION & VERIFICATION SCRIPT"
echo "=========================================================="

echo "--- 1. SAFETY CHECK & ROLLBACK CHECKPOINT ---"
sudo mkdir -p "$BACKUP_DIR"
DB_PASS=$(grep DB_ROOT_PASSWORD /opt/lohnour/.env | cut -d= -f2)

echo "Dumping database to $BACKUP_DIR/lohnour_wp_pre_migration.sql..."
sudo docker exec lohnour-db-1 mariadb-dump -u root -p"$DB_PASS" lohnour_wp > /tmp/lohnour_wp_pre_migration.sql
sudo mv /tmp/lohnour_wp_pre_migration.sql "$BACKUP_DIR/"
sudo cp -p /opt/lohnour/docker-compose.yml "$BACKUP_DIR/"
sudo chown -R ubuntu:ubuntu "$BACKUP_DIR"
ls -la "$BACKUP_DIR"
test -s "$BACKUP_DIR/lohnour_wp_pre_migration.sql" && echo "Backup verified: NON-EMPTY"

echo ""
echo "--- 2. FORENSIC BASELINE ---"
echo "Baseline siteurl: $(sudo docker exec -w /var/www/html lohnour-wordpress-1 wp option get siteurl --allow-root)"
echo "Baseline home:    $(sudo docker exec -w /var/www/html lohnour-wordpress-1 wp option get home --allow-root)"

echo "Database occurrences of http://localhost:8081:"
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

echo "Database occurrences of https://pro.lohnour.com:"
sudo docker exec lohnour-db-1 mariadb -u root -p"$DB_PASS" lohnour_wp -e \
  "SELECT 'wp_options' as tbl, count(*) as count FROM wp_options WHERE option_value LIKE '%https://pro.lohnour.com%'
   UNION ALL
   SELECT 'wp_posts (post_content)', count(*) FROM wp_posts WHERE post_content LIKE '%https://pro.lohnour.com%';"

echo "Table count:"
sudo docker exec lohnour-db-1 mariadb -u root -p"$DB_PASS" lohnour_wp -se \
  "SELECT count(*) FROM information_schema.tables WHERE table_schema = 'lohnour_wp';"

echo ""
echo "--- 3. PERFORM SAFE WORDPRESS URL MIGRATION ---"
sudo docker exec -w /var/www/html lohnour-wordpress-1 wp search-replace \
  'http://localhost:8081' \
  'https://pro.lohnour.com' \
  --all-tables \
  --precise \
  --recurse-objects \
  --skip-columns=guid \
  --report-changed-only \
  --allow-root

echo ""
echo "--- 4. VERIFY SITEURL AND HOME ---"
echo "After siteurl: $(sudo docker exec -w /var/www/html lohnour-wordpress-1 wp option get siteurl --allow-root)"
echo "After home:    $(sudo docker exec -w /var/www/html lohnour-wordpress-1 wp option get home --allow-root)"

echo ""
echo "--- 5. VERIFY ZERO OLD URL REFERENCES IN DATABASE ---"
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
echo "--- 6. VERIFY ASSET GENERATION IN PRODUCTION HTML ---"
curl -s -L --max-time 15 https://pro.lohnour.com/ > /tmp/prod_home.html
echo "Occurrences of http://localhost:8081 in generated HTML: $(grep -c 'http://localhost:8081' /tmp/prod_home.html || true)"
echo "Occurrences of https://pro.lohnour.com in generated HTML: $(grep -c 'https://pro.lohnour.com' /tmp/prod_home.html || true)"

echo ""
echo "Sample stylesheet links in HTML:"
grep -o 'href="https://pro.lohnour.com/[^"]*\.css[^"]*"' /tmp/prod_home.html | head -5 || true

echo "Sample script links in HTML:"
grep -o 'src="https://pro.lohnour.com/[^"]*\.js[^"]*"' /tmp/prod_home.html | head -5 || true

echo "Sample image links in HTML:"
grep -o 'src="https://pro.lohnour.com/[^"]*\.\(png\|jpg\|jpeg\|webp\|svg\)[^"]*"' /tmp/prod_home.html | head -5 || true

echo ""
echo "--- 7. BROWSER / HTTP ROUTE VERIFICATION ---"
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

for route in "${ROUTES[@]}"; do
  STATUS=$(curl -s -o /dev/null -w "%{http_code}" -L --max-time 10 "https://pro.lohnour.com${route}")
  echo "Route ${route} -> HTTP ${STATUS}"
done

echo ""
echo "--- 8 & 9. MIXED CONTENT / CONSOLE CHECK ---"
echo "Checking for mixed content (http://) in asset links:"
grep -o 'src="http://[^"]*"' /tmp/prod_home.html || echo "NO http:// asset src found."
grep -o 'href="http://[^"]*\.css[^"]*"' /tmp/prod_home.html || echo "NO http:// css href found."

echo "Checking for 127.0.0.1 or localhost or 172. in HTML:"
grep -E '127\.0\.0\.1|localhost|172\.(18|19)\.' /tmp/prod_home.html || echo "NO internal IP/localhost references found."

echo ""
echo "--- 10. CONTACT + SERVICES SANITY CHECK ---"
curl -s -L --max-time 15 https://pro.lohnour.com/contact/ > /tmp/prod_contact.html
echo "Contact page checks:"
grep -qi "Send an Inquiry" /tmp/prod_contact.html && echo "- 'Send an Inquiry': PRESENT" || echo "- 'Send an Inquiry': NOT FOUND"
grep -qi "hello@pro.lohnour.com" /tmp/prod_contact.html && echo "- 'hello@pro.lohnour.com': PRESENT" || echo "- 'hello@pro.lohnour.com': NOT FOUND"
grep -q "83492" /tmp/prod_contact.html && echo "- Old phone number: PRESENT (WARNING)" || echo "- Old phone number: ABSENT (VERIFIED)"

curl -s -L --max-time 15 https://pro.lohnour.com/services/ > /tmp/prod_services.html
echo "Services page checks:"
grep -qi "Corporate AI Training" /tmp/prod_services.html && echo "- 'Corporate AI Training': PRESENT" || echo "- 'Corporate AI Training': NOT FOUND"
grep -qi "Professional AI Upskilling" /tmp/prod_services.html && echo "- 'Professional AI Upskilling': PRESENT" || echo "- 'Professional AI Upskilling': NOT FOUND"
grep -qi "AI for Executive Leadership" /tmp/prod_services.html && echo "- 'AI for Executive Leadership': PRESENT (WARNING)" || echo "- 'AI for Executive Leadership': ABSENT (VERIFIED)"
grep -qi "View All Programs" /tmp/prod_services.html && echo "- 'View All Programs': PRESENT" || echo "- 'View All Programs': NOT FOUND"

echo ""
echo "--- 11. INFRASTRUCTURE & CONTAINER SAFETY ---"
sudo docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
sudo systemctl is-active nginx || echo "Host Nginx: INACTIVE (VERIFIED)"
sudo systemctl is-enabled nginx || echo "Host Nginx: DISABLED (VERIFIED)"
test -d /var/www/html && echo "/var/www/html: EXISTS & UNTOUCHED"

echo ""
echo "=========================================================="
echo "PHASE 5 EXECUTION COMPLETE"
echo "=========================================================="
