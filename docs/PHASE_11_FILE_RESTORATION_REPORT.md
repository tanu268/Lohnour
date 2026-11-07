# LOHNOUR PRO — PHASE 11 FILE RESTORATION REPORT
## Production WordPress Application File & Media Restoration Report

**Document Authority:** Lead DevOps Engineer, Senior WordPress Infrastructure Architect, Security Auditor  
**Execution Timestamp:** November 7, 2026 (13:06:27 IST / 07:36:27 UTC)  
**Governing Standard:** LOHNOUR PRO Fail-Closed Deployment Standard  
**Target Host:** Sam's Oracle VM (`141.148.51.166`, `sams-oracle-vm`, Oracle Linux Server 9.7 ARM64 `aarch64`)  
**Target Volume:** `lohnour_wp_data` (`/var/lib/docker/volumes/lohnour_wp_data/_data`)  
**Canonical Execution Script:** `/opt/lohnour/phase11_file_recovery.sh` (SHA-256: `1b5e613fca76d2970c343b15d376e32e5b0a7d8b13bee2d919428452edf8f847`)  
**Pinned Core Image:** `wordpress:7.1.4-php8.2-apache` (SHA-256: `sha256:3aa5b96774a7791d3d2bbd0e213ce56165f47d6011433ff3e78223800a545fb6`)  
**Final Status:** `PHASE_11_SUCCESS — WORDPRESS_FILES_RESTORED_AND_VERIFIED`  

---

## 1. EXECUTIVE SUMMARY & CLOSURE DECISION

Phase 11: Production WordPress Application File & Uploads Restoration has been executed and verified in one controlled pass on Sam's Oracle VM.

All staging archives were cryptographically verified before extraction. WordPress Core 7.1.4 (with database revision 61833), all 4 required plugin suites (`akismet`, `all-in-one-wp-migration`, `mailin`, `lohnour-program-cms`), the parent and child themes (`twentytwentyfive`, `twentytwentyfive-child`), and 125 media uploads were restored directly into volume `lohnour_wp_data`. File permissions were strictly normalized to `33:33` (`www-data:www-data`), with zero 777 permissions. Canonical `wp-config.php` was authored and verified with clean PHP syntax. Production database volume `lohnour_db_data` remained 100% untouched.

```
========================================================================================
FINAL PHASE 11 EXECUTION STATUS:
PHASE_11_SUCCESS — WORDPRESS_FILES_RESTORED_AND_VERIFIED
========================================================================================
```

---

## 2. PREFLIGHT GATES & ARCHIVE CRYPTOGRAPHIC VERIFICATION

Before extraction, every archive was independently validated against its authoritative cryptographic checksum:

| Archive Name | Staging Path | File Size | Authoritative SHA-256 | Verification Result |
| :--- | :--- | :---: | :--- | :---: |
| **Media Uploads** | `/opt/lohnour/staging/uploads.tar.gz` | 57 MB | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | **100% MATCH** |
| **Plugin Suite** | `/opt/lohnour/staging/lohnour_all_plugins.tar.gz` | 5.4 MB | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | **100% MATCH** |
| **Custom CMS Plugin** | `/opt/lohnour/staging/lohnour_program_cms.tar.gz` | 19 KB | `989eaf8941a27f37bfc7576954a22fe6f64f121ee44c5a9f66e4e6b4777dd5ec` | **100% MATCH** |
| **Child Theme** | `/opt/lohnour/staging/twentytwentyfive-child.tar.gz` | 6.0 MB | `a14af7f749f0e459b60658514e79325a29a3bb7fdc98c56d9591e1faadca802d` | **100% MATCH** |

### Additional Preflight Invariants:
- **Volume Isolation:** Volume `lohnour_wp_data` mountpoint dynamically resolved to `/var/lib/docker/volumes/lohnour_wp_data/_data`. Zero containers were attached.
- **Database Volume Invariance:** `lohnour_db_data` verified with exactly 14 `.ibd` tablespaces present and untouched.

---

## 3. APPLICATION RESTORATION PROCEDURE

1. **WordPress Core Extraction:**
   - Seeded from verified ARM64 image `wordpress:7.1.4-php8.2-apache` (`/usr/src/wordpress/.` $\rightarrow$ `/var/www/html/`).
   - Verified `$wp_version = '7.1.4'` and `$wp_db_version = 61833` in `wp-includes/version.php`.
2. **Plugin Restoration:**
   - Extracted `lohnour_all_plugins.tar.gz` and `lohnour_program_cms.tar.gz` into `wp-content/plugins/`.
   - Restored plugins:
     - `akismet`: Antispam protection.
     - `all-in-one-wp-migration`: Migration and backup tooling.
     - `mailin`: Brevo (Sendinblue) synchronization plugin.
     - `lohnour-program-cms`: Custom CPT and program renderer.
3. **Media Uploads Restoration:**
   - Extracted `uploads.tar.gz` into `wp-content/`.
   - Restored 125 historical image, banner, and document assets organized by date (`2026/07/`, `2026/10/`).
4. **Themes Restoration:**
   - Parent theme `twentytwentyfive` present from core image.
   - Child theme `twentytwentyfive-child` extracted into `wp-content/themes/twentytwentyfive-child/`.
5. **Canonical Configuration (`wp-config.php`):**
   - Configured with `getenv_docker()` helper for container environment injection.
   - Configured `DB_NAME` (`lohnour_wp`), `DB_USER` (`lohnour_user`), `DB_HOST` (`db:3306`), `DB_PASSWORD` (read dynamically from environment).
   - Configured reverse-proxy HTTPS detection (`HTTP_X_FORWARDED_PROTO` $\rightarrow$ `HTTPS = on`).
   - Validated PHP syntax with `php -l`.
6. **Filesystem Ownership & Permission Normalization:**
   - Ownership: `33:33` (`www-data:www-data`) across all files and directories.
   - Directory Permissions: `0755` (drwxr-xr-x).
   - File Permissions: `0644` (-rw-r--r--).
   - Zero `777` permissions across the entire volume.

---

## 4. ACCEPTANCE VALIDATION RESULTS

| # | Acceptance Test | Target Expectation | Observed Live Result | Status |
| :-: | :--- | :--- | :--- | :-: |
| **1** | Core Files & Dirs | `index.php`, `wp-login.php`, `wp-admin/`, `wp-includes/` | All present and non-empty | **PASS** |
| **2** | Core & DB Revision | Core: `7.1.4`, DB Version: `61833` | Core `7.1.4`, DB `61833` matched | **PASS** |
| **3** | `wp-config.php` Syntax | Non-empty file with valid PHP syntax | `php -l` returned `No syntax errors detected` | **PASS** |
| **4** | Plugins Restored | 4 active plugins: `akismet`, `all-in-one-wp-migration`, `mailin`, `lohnour-program-cms` | All 4 plugin directories verified | **PASS** |
| **5** | Themes Restored | `twentytwentyfive`, `twentytwentyfive-child` | Both verified with valid `style.css` | **PASS** |
| **6** | Uploads Restored | $\ge 120$ media files in `wp-content/uploads` | 125 media files verified | **PASS** |
| **7** | Security Permissions | Ownership `33:33`, `0755/0644`, zero `777` | `33:33` verified, 0 files with 777 | **PASS** |
| **8** | Workload Health | Portainer (`:9443`) and Node-RED (`:1880`) healthy | HTTP 200 on both services | **PASS** |

---

## 5. RESOURCE ACCOUNTING & AUDIT TRAIL

- **Target Volume:** `lohnour_wp_data` (Populated with 157 MB of WordPress core, plugins, uploads, and child theme).
- **Database Volume:** `lohnour_db_data` (Preserved 100% untouched; 14 InnoDB tables).
- **Staging Directory:** `/opt/lohnour/staging` (Preserved 100% intact with all 4 archives and checksum manifests).
- **Host Workloads:** Portainer (`:9443`) and Node-RED (`:1880`) running and healthy.

---

## 6. FINAL FORMAL STATUS

```
========================================================================================
FINAL FORMAL DECISION:
PHASE_11_SUCCESS — WORDPRESS_FILES_RESTORED_AND_VERIFIED
========================================================================================
```

