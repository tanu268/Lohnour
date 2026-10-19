#!/bin/bash
set -e

echo '=== STEP 1: SERVER STATE ==='
sudo docker ps
sudo docker volume ls
sudo docker network ls
free -h
df -h /opt

echo '=== STEP 3 & 4: MIGRATION STORAGE ==='
sudo mkdir -p /opt/lohnour/migration
sudo chown ubuntu:ubuntu /opt/lohnour/migration
sudo chmod 700 /opt/lohnour/migration

echo '=== PREPARATION FOR PLUGIN ==='
cd /opt/lohnour
# Install WP CLI in the container for easier management
sudo docker compose exec -T wordpress bash -c "curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp" || true
# Install and activate the plugin
sudo docker compose exec -T wordpress bash -c "wp plugin install all-in-one-wp-migration --activate --allow-root" || true

echo '=== STEP 9: PHP CONFIGURATION VERIFICATION ==='
sudo docker compose exec -T wordpress bash -c "php -r 'echo \"upload_max_filesize: \" . ini_get(\"upload_max_filesize\") . \"\npost_max_size: \" . ini_get(\"post_max_size\") . \"\nmemory_limit: \" . ini_get(\"memory_limit\") . \"\nmax_execution_time: \" . ini_get(\"max_execution_time\") . \"\n\";'"

echo '=== SECURITY & ROLLBACK CHECK ==='
sudo ss -lntp | grep -E ':(80|3306|9443)' || true
sudo systemctl is-active nginx
sudo systemctl is-active mysql
