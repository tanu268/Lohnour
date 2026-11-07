import subprocess

# 1. Authoritative Caddyfile
caddyfile_content = """{
\tadmin off
}

pro.lohnour.com {
\tencode zstd gzip

\treverse_proxy lohnour-wordpress:80 {
\t\ttransport http {
\t\t\tresponse_header_timeout 300s
\t\t}
\t}

\theader {
\t\tStrict-Transport-Security "max-age=31536000"
\t\tX-Content-Type-Options "nosniff"
\t\tX-Frame-Options "SAMEORIGIN"
\t\tReferrer-Policy "strict-origin-when-cross-origin"
\t\t-Server
\t}

\trequest_body {
\t\tmax_size 512MB
\t}

\tlog {
\t\toutput stdout
\t\tformat console
\t\tlevel INFO
\t}
}
"""

with open("d:/lohnourpro/Caddyfile", "w", encoding="utf-8") as f:
    f.write(caddyfile_content)
print("Updated local d:/lohnourpro/Caddyfile")

# 2. Authoritative docker-compose.yml
compose_content = """name: lohnour-stack

services:
  # ----------------------------------------------------------------------------
  # Database Engine: MariaDB 10.11 LTS
  # ----------------------------------------------------------------------------
  db:
    image: mariadb@sha256:0de9840f23463c22eb4892d9bfd63086367e4bffe9b68ff4b62621d6eced854c
    container_name: lohnour-db
    restart: unless-stopped
    # Port 3306 is strictly private to lohnour_db_network; zero host ports exposed
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD:?DB_ROOT_PASSWORD is required}
      MYSQL_DATABASE: ${DB_NAME:-lohnour_wp}
      MYSQL_USER: ${DB_USER:-lohnour_user}
      MYSQL_PASSWORD: ${DB_PASSWORD:?DB_PASSWORD is required}
    volumes:
      - lohnour_db_data:/var/lib/mysql
    networks:
      - lohnour_db_network
    healthcheck:
      test: ["CMD", "healthcheck.sh", "--connect", "--innodb_initialized"]
      interval: 10s
      timeout: 5s
      retries: 5
      start_period: 20s

  # ----------------------------------------------------------------------------
  # Web Application: WordPress (Apache runtime)
  # ----------------------------------------------------------------------------
  wordpress:
    image: wordpress:7.1.4-php8.2-apache
    container_name: lohnour-wordpress
    restart: unless-stopped
    # Application port is strictly private to the container network; zero host ports exposed
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_NAME: ${DB_NAME:-lohnour_wp}
      WORDPRESS_DB_USER: ${DB_USER:-lohnour_user}
      WORDPRESS_DB_PASSWORD: ${DB_PASSWORD:?DB_PASSWORD is required}
      WORDPRESS_TABLE_PREFIX: wp_
    volumes:
      - lohnour_wp_data:/var/www/html
      - /opt/lohnour/config/uploads.ini:/usr/local/etc/php/conf.d/uploads.ini:ro
    networks:
      - proxy_network
      - lohnour_db_network
    healthcheck:
      test: ["CMD-SHELL", "curl -f -s -H 'Host: pro.lohnour.com' http://localhost/ || exit 1"]
      interval: 15s
      timeout: 5s
      retries: 5
      start_period: 30s
    depends_on:
      db:
        condition: service_healthy

  # ----------------------------------------------------------------------------
  # Ingress Reverse Proxy: Caddy v2 LTS (ARM64 Immutable)
  # ----------------------------------------------------------------------------
  caddy:
    image: caddy@sha256:78bd35025c7ba85961dd149ae670315129954e77abe2383ac47c89106b35ade9
    container_name: lohnour-caddy
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - /opt/lohnour/Caddyfile:/etc/caddy/Caddyfile:ro
      - caddy_data:/data
      - caddy_config:/config
    networks:
      - proxy_network
    depends_on:
      - wordpress

# ==============================================================================
# Volume Declarations (Pre-created production persistent volumes)
# ==============================================================================
volumes:
  lohnour_wp_data:
    external: true
    name: lohnour_wp_data
  lohnour_db_data:
    external: true
    name: lohnour_db_data
  caddy_data:
    name: caddy_data
  caddy_config:
    name: caddy_config

# ==============================================================================
# Virtual Network Declarations (Pre-created production networks)
# ==============================================================================
networks:
  proxy_network:
    external: true
    name: proxy_network
  lohnour_db_network:
    external: true
    name: lohnour_db_network
"""

with open("d:/lohnourpro/docker-compose.yml", "w", encoding="utf-8") as f:
    f.write(compose_content)
print("Updated local d:/lohnourpro/docker-compose.yml")

