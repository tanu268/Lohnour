#!/bin/bash
set -e

# Generate strong password that satisfies MySQL policies
DB_PASS=$(tr -dc 'A-Za-z0-9!?%=' < /dev/urandom | head -c 20)
DB_PASS="A1!z${DB_PASS}"

# Create database and user
sudo mysql -e "CREATE DATABASE IF NOT EXISTS lohnour_wp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'lohnour_wp_user'@'localhost' IDENTIFIED BY '${DB_PASS}';"
sudo mysql -e "GRANT ALL PRIVILEGES ON lohnour_wp.* TO 'lohnour_wp_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Backup and clear web root
sudo cp -a /var/www/html /var/www/html.bak
sudo rm -rf /var/www/html/*

# Install wp-cli if not present
if [ ! -f /usr/local/bin/wp ]; then
    curl -sO https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
    sudo mv wp-cli.phar /usr/local/bin/wp
fi

# Download WordPress core
sudo wp core download --path=/var/www/html --allow-root

# Create wp-config.php (this handles salts automatically)
sudo wp config create --path=/var/www/html --dbname=lohnour_wp --dbuser=lohnour_wp_user --dbpass="${DB_PASS}" --allow-root

# Set Secure Permissions
sudo chown -R root:www-data /var/www/html
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo find /var/www/html -type f -exec chmod 644 {} \;

# PHP-FPM needs write access to wp-content for uploads, plugins, themes, and migration plugins
sudo chown -R www-data:www-data /var/www/html/wp-content

# Secure wp-config.php
sudo chmod 640 /var/www/html/wp-config.php

echo "Bootstrap complete."
