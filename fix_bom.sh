#!/usr/bin/env bash
set -euo pipefail

FILE="/var/www/html/wp-content/themes/twentytwentyfive-child/inc/contact-form.php"
WP="lohnour-wordpress-1"

echo "=== CHECKING BOM BEFORE ==="
sudo docker exec "$WP" od -A x -t x1 "$FILE" | head -n 2

echo "=== CREATING BACKUP ==="
sudo docker exec "$WP" cp "$FILE" "${FILE}.bak"

echo "=== REMOVING BOM (STRIPPING 3 BYTES) ==="
sudo docker exec "$WP" sh -c "tail -c +4 '${FILE}.bak' > '${FILE}'"

echo "=== CHECKING BOM AFTER ==="
sudo docker exec "$WP" od -A x -t x1 "$FILE" | head -n 2

echo "=== VERIFYING SYNTAX ==="
sudo docker exec "$WP" php -l "$FILE"

echo "=== BOM STRIP COMPLETE ==="
