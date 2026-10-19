# PHASE 5 — WORDPRESS BOOTSTRAP REPORT

## MySQL
- **Database Created:** `lohnour_wp` (UTF-8 compatible character set and collation)
- **Database User Created:** `lohnour_wp_user` (with a strong securely generated password)
- **Privilege Scope Verified:** User has privileges strictly isolated to `lohnour_wp.*`. Global privileges were not granted.
- **MySQL Status:** Active, listening exclusively on `localhost` (127.0.0.1).

## WordPress
- **WordPress Core Installed:** Yes
- **Location:** `/var/www/html`
- **Version:** Latest officially distributed version (downloaded via `wp-cli`).
- **wp-config Status:** Successfully generated with the securely created database credentials. Secure salts and keys have been fetched from the official API and properly injected.

## Nginx
- **Status:** Active (running)
- **nginx -t Result:** 
  - `nginx: the configuration file /etc/nginx/nginx.conf syntax is ok`
  - `nginx: configuration file /etc/nginx/nginx.conf test is successful`

## PHP-FPM
- **Status:** Active (running)
- **Version:** PHP 8.1.2-1ubuntu2.26 (cli) / PHP 8.1 FastCGI Process Manager

## Filesystem
- **Ownership:** 
  - Web root (`/var/www/html`): `root:www-data`
  - Content directory (`/var/www/html/wp-content`): `www-data:www-data` (to allow future migration plugin extractions)
- **Directory Permissions:** `755` (`drwxr-xr-x`) globally applied.
- **File Permissions:** `644` (`-rw-r--r--`) globally applied, except for `wp-config.php` which is restricted to `640` (`-rw-r-----`). No `777` permissions exist.

## HTTP
- **Response from `http://193.122.206.31/`:** 
  - Returned HTTP `302 Found`.
  - Header `X-Redirect-By: WordPress` is present, confirming PHP execution.
  - Redirects to `/wp-admin/install.php`, verifying that the unconfigured installation is functioning correctly and successfully connecting to the database.

## Security
- **Credential Exposure Check:** No credentials were leaked in this report, the terminal logs, or in any publicly accessible file.
- **wp-config Protection:** Verified not downloadable as raw text. When requested, Nginx passes it to PHP-FPM which executes it natively, resulting in a redirect. Permissions restricted to `640` and owned by `root:www-data`.
- **MySQL Binding:** Verified bound strictly to localhost port `3306` from the previous phase. No external exposure.
- **Temporary Files Removed:** The bootstrap shell script used to execute creation commands securely was successfully deleted from the server (`/tmp/bootstrap.sh`).

## Remaining Migration Steps
1. Run the WordPress installation wizard to finalize the bootstrap (or inject a bootstrap admin user via `wp-cli`).
2. Install the `All-in-One WP Migration` plugin.
3. Transfer the LOHNOUR `.wpress` archive from the local Windows environment to the Oracle server.
4. Execute the restoration of the `.wpress` archive.
5. Apply the updated local `lohnourpro_wp_theme` over the restored files.
6. Flush permalinks and verify route functionality.
7. Perform the final domain mapping and configure HTTPS.
