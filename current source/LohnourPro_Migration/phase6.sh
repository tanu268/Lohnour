#!/bin/bash
set -e

echo '=== STEP 1: PRE-RESTORE AUDIT ==='
sudo docker ps
sudo docker images
sudo docker volume ls
sudo docker network ls
df -h
free -h
sha256sum /opt/lohnour/migration/localhost-lohnourpro-03-Oct-2026.wpress

echo '=== STEP 2: VERIFY CLEAN WORDPRESS ==='
sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress wp theme list --allow-root || true
sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress wp plugin list --allow-root || true

echo '=== STEP 3: CREATE ROLLBACK CHECKPOINT ==='
CP_NAME="phase6-pre-restore-$(date +%Y%m%d-%H%M)"
sudo docker cp lohnour-wordpress-1:/var/www/html /opt/lohnour/${CP_NAME}_wp_html
source /opt/lohnour/.env
sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T db mysqldump -u root -p"${DB_ROOT_PASSWORD}" --all-databases > /opt/lohnour/${CP_NAME}_db.sql
echo "Checkpoint created: ${CP_NAME}"

echo '=== STEP 4: INITIALIZE WORDPRESS ==='
IS_INSTALLED=$(sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress wp core is-installed --allow-root || echo "no")
if [ "$IS_INSTALLED" != "yes" ]; then
    ADMIN_PASS="${WP_ADMIN_PASSWORD:-${ADMIN_PASSWORD}}"
    sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress wp core install --url="http://localhost" --title="Lohnour Migration Temp" --admin_user="${ADMIN_USER:-lohnouradmin}" --admin_password="${ADMIN_PASS}" --admin_email="${ADMIN_EMAIL:-admin@lohnour.com}" --allow-root
fi

echo '=== STEP 5: INSTALL MIGRATION PLUGIN ==='
sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress wp plugin install all-in-one-wp-migration --activate --allow-root

echo '=== STEP 6 & 7: PREPARE ARCHIVE & CHECK LIMITS ==='
sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress mkdir -p /var/www/html/wp-content/ai1wm-backups
sudo docker cp /opt/lohnour/migration/localhost-lohnourpro-03-Oct-2026.wpress lohnour-wordpress-1:/var/www/html/wp-content/ai1wm-backups/
sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress chown www-data:www-data /var/www/html/wp-content/ai1wm-backups/localhost-lohnourpro-03-Oct-2026.wpress
sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress php -r 'echo "upload_max_filesize: " . ini_get("upload_max_filesize") . "\npost_max_size: " . ini_get("post_max_size") . "\nmemory_limit: " . ini_get("memory_limit") . "\nmax_execution_time: " . ini_get("max_execution_time") . "\n";'

echo '=== STEP 8: VERIFY ARCHIVE BEFORE IMPORT ==='
sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress sha256sum /var/www/html/wp-content/ai1wm-backups/localhost-lohnourpro-03-Oct-2026.wpress

echo '=== STEP 9: ATTEMPT WP-CLI RESTORE ==='
if sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress wp help ai1wm --allow-root >/dev/null 2>&1; then
    echo "WP-CLI AI1WM supported. Restoring..."
    sudo docker compose -f /opt/lohnour/docker-compose.yml exec -T wordpress wp ai1wm restore localhost-lohnourpro-03-Oct-2026.wpress --yes --allow-root
else
    echo "MANUAL_RESTORE_REQUIRED: WP-CLI AI1WM restore requires premium extension or is unsupported in free CLI."
fi
