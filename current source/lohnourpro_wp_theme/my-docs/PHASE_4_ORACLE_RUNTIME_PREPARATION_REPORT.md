# PHASE 4 — ORACLE RUNTIME PREPARATION REPORT

## 1. Installation
- **PHP Version:** PHP 8.1.2
- **PHP-FPM Version:** PHP 8.1 FastCGI Process Manager
- **Installed Extensions:** `php8.1-mysql`, `php8.1-xml`, `php8.1-curl`, `php8.1-zip`, `php8.1-mbstring`, `php8.1-gd`, `php8.1-intl` (Modules verified: mysqli, PDO, pdo_mysql, curl, dom, xml, zip, mbstring, gd, intl).

## 2. PHP Configuration
- **Previous values (defaults):**
  - `upload_max_filesize` = 2M
  - `post_max_size` = 8M
  - `memory_limit` = 128M
  - `max_execution_time` = 30
  - `max_input_time` = 60
- **New values:**
  - `upload_max_filesize` = 128M
  - `post_max_size` = 128M
  - `memory_limit` = 256M
  - `max_execution_time` = 300
  - `max_input_time` = 300
- **Configuration Backup Path:** `/etc/php/8.1/fpm/php.ini.bak`

## 3. Nginx
- **Configuration Changes:** 
  - Added `index.php` to the `index` directive.
  - Added `client_max_body_size 128M;`
  - Uncommented the FastCGI block to pass `.php` requests to `unix:/run/php/php8.1-fpm.sock`.
- **client_max_body_size:** 128M
- **Nginx Configuration Backup Path:** `/etc/nginx/sites-available/default.bak`
- **nginx -t Result:** 
  - `nginx: the configuration file /etc/nginx/nginx.conf syntax is ok`
  - `nginx: configuration file /etc/nginx/nginx.conf test is successful`

## 4. PHP-FPM
- **Service Name:** `php8.1-fpm`
- **Service Status:** Active (running)
- **Socket Path:** `/run/php/php8.1-fpm.sock`

## 5. Filesystem
- **Ownership:** `root:root` for `/var/www/html`
- **Permissions:** `drwxr-xr-x` (755)
- *(Note: Ownership/permissions have NOT been changed as instructed. They will need to be changed to `www-data` prior to WordPress installation.)*

## 6. Validation
- **PHP Test:** Verified PHP CLI executes successfully (`php -v`).
- **PHP-FPM Test:** A temporary PHP script (`test.php`) was executed via Nginx. It successfully parsed the `.php` file and verified the new `ini_get` values for the 128M limits. The file was immediately deleted.
- **Nginx Test:** Nginx service restarted successfully; configuration tests passed.
- **PHP Module Verification:** `php -m` confirms all WordPress prerequisites (mysqli, curl, zip, dom, mbstring, gd, intl) are active.

## 7. Security
- **Backups Created:** Yes (`/etc/php/8.1/fpm/php.ini.bak`, `/etc/nginx/sites-available/default.bak`).
- **Secrets:** No secrets, SSH keys, passwords, or tokens were exposed or left on the server. The temporary test file did not expose `phpinfo()` and was deleted.

## 8. Remaining Blockers
- **None discovered for the environment runtime.** 
- (We must address the `/var/www/html` permissions in the next phase before proceeding with WordPress extraction).
