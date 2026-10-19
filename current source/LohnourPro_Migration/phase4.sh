#!/bin/bash
set -e

echo "=== STEP 1: PRE-FLIGHT ==="
docker --version
docker compose version
sudo systemctl is-active nginx
sudo systemctl is-active mysql
sudo systemctl is-active php8.1-fpm
sudo ss -lntp | grep -E ':(80|3306)' || true

echo "=== STEP 2: CREATE DIRECTORIES ==="
sudo mkdir -p /opt/lohnour
sudo chown ubuntu:ubuntu /opt/lohnour
cd /opt/lohnour

echo "=== PREPARING FILES ==="
cat << 'EOF' > docker-compose.yml
version: '3.8'

services:
  db:
    image: mariadb:10.11
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: lohnour_wp
      MYSQL_USER: lohnour_user
      MYSQL_PASSWORD: ${DB_PASSWORD}
    volumes:
      - lohnour_db_data:/var/lib/mysql
    networks:
      - lohnour_network
    healthcheck:
      test: ["CMD", "healthcheck.sh", "--connect", "--innodb_initialized"]
      interval: 10s
      timeout: 5s
      retries: 5

  wordpress:
    image: wordpress:latest
    restart: unless-stopped
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: lohnour_user
      WORDPRESS_DB_PASSWORD: ${DB_PASSWORD}
      WORDPRESS_DB_NAME: lohnour_wp
    volumes:
      - lohnour_wp_data:/var/www/html
      - ./uploads.ini:/usr/local/etc/php/conf.d/uploads.ini
    networks:
      - proxy_network
      - lohnour_network
    depends_on:
      db:
        condition: service_healthy

  npm:
    image: jc21/nginx-proxy-manager:latest
    restart: unless-stopped
    ports:
      - "8080:80"
      - "8443:443"
      - "81:81"
    volumes:
      - npm_data:/data
      - npm_letsencrypt:/etc/letsencrypt
    networks:
      - proxy_network

networks:
  proxy_network:
    name: proxy_network
  lohnour_network:
    name: lohnour_network

volumes:
  lohnour_db_data:
    name: lohnour_db_data
  lohnour_wp_data:
    name: lohnour_wp_data
  npm_data:
    name: npm_data
  npm_letsencrypt:
    name: npm_letsencrypt
EOF

cat << 'EOF' > uploads.ini
upload_max_filesize = 512M
post_max_size = 512M
memory_limit = 512M
max_execution_time = 600
max_input_time = 600
EOF

if [ ! -f .env ]; then
  echo "Generating secrets..."
  ROOT_PASS=$(openssl rand -hex 24)
  USER_PASS=$(openssl rand -hex 24)
  echo "DB_ROOT_PASSWORD=${ROOT_PASS}" > .env
  echo "DB_PASSWORD=${USER_PASS}" >> .env
  chmod 600 .env
fi

echo "=== STEP 3: DEPLOY ==="
sudo docker compose up -d

echo "=== STEP 4: VERIFY ==="
sleep 20
sudo docker ps
sudo docker compose ps
echo "--- Testing internal connectivity ---"
# WP loads properly?
sudo docker compose exec -T wordpress curl -sI http://localhost/ || true
echo "--- System resources ---"
free -h
df -h
sudo docker stats --no-stream
