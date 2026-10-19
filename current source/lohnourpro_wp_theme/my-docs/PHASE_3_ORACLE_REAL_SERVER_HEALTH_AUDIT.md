# PHASE 3 — ORACLE REAL SERVER HEALTH AUDIT

## 1. SSH Verification
✓ VERIFIED
- **Target:** ubuntu@193.122.206.31
- **Hostname:** wordpress-server
- **Authenticated User:** ubuntu

## 2. Operating System
✓ VERIFIED
- **Ubuntu Version:** Ubuntu 22.04.5 LTS (jammy)
- **Kernel:** 6.8.0-1062-oracle x86_64

## 3. Compute
✓ VERIFIED
- **CPU:** 2 Cores
- **RAM:** 11Gi Total, 10Gi Available
- **Uptime:** ~52 minutes

## 4. Storage
✓ VERIFIED
- **Total:** 45G
- **Used:** 4.4G
- **Available:** 41G
- `/var/www/html` resides on the main `/dev/sda1` partition with 41G available.

## 5. MySQL
✓ VERIFIED
- **Version:** 8.0.46-0ubuntu0.22.04.4 for Linux on x86_64
- **Service Status:** Active (running)
- **Listening Address/Port:** 127.0.0.1:3306 and 127.0.0.1:33060

## 6. Nginx
✓ VERIFIED
- **Version:** nginx/1.18.0 (Ubuntu)
- **Service Status:** Active (running)
- **Active Web Root:** `/var/www/html`
- **client_max_body_size:** NOT CONFIGURED (defaults to 1M)

## 7. PHP
✗ MISSING
- **Version:** `bash: php: command not found`
- **Active php.ini:** N/A
- **upload_max_filesize:** N/A
- **post_max_size:** N/A
- **memory_limit:** N/A
- **max_execution_time:** N/A
- **max_input_time:** N/A
- **max_file_uploads:** N/A

## 8. PHP-FPM
✗ MISSING
- **Installed version/service:** None found
- **Status:** N/A

## 9. Current Web Root
⚠ REQUIRES VERIFICATION (permissions will need to change for WP)
- **Path:** `/var/www/html`
- **Contents:** Only `index.nginx-debian.html` is present
- **Ownership:** `root:root`
- **Permissions:** `drwxr-xr-x` (755)

## 10. Listening Services
✓ VERIFIED
- **Port 80:** Nginx (IPv4 / IPv6)
- **Port 22:** sshd
- **Port 3306:** mysqld (localhost)
- **Port 33060:** mysqld (localhost)
- **Port 111 / 53:** rpcbind / systemd-resolve

## 11. Migration Readiness
Based ONLY on the actual observed server state, identify:

**🛑 BLOCKERS:**
- PHP is NOT installed on the server. WordPress cannot run, and therefore the `.wpress` archive cannot be imported.

**Prerequisites:**
- Install PHP, PHP-FPM, and necessary WordPress PHP extensions (e.g., `php-mysql`, `php-xml`, `php-curl`, `php-zip`, `php-mbstring`, `php-gd`, `php-intl`).

**Configuration changes that may be required:**
- Set Nginx `client_max_body_size` to `128M` (or higher) to accept the 98.4MB `.wpress` upload.
- Configure `upload_max_filesize = 128M` and `post_max_size = 128M` in `php.ini`.
- Change ownership of `/var/www/html` from `root:root` to `www-data:www-data` so WordPress can write files during the archive extraction.
- Configure Nginx server blocks to properly route PHP requests to PHP-FPM.
