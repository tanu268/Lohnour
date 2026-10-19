# LOHNOUR Production Migration Forensic Audit

## 1. Executive Summary
This forensic audit analyzed the local file system structure to prepare for the LOHNOUR WordPress migration to Oracle Cloud. The project does not contain a raw, unpacked WordPress installation. Instead, we have a custom child theme (`lohnourpro_wp_theme`) stored in one directory, and an All-in-One WP Migration backup file (`.wpress`) stored in another. There is no raw SQL database dump, `wp-config.php`, or unpacked `wp-content` media/plugin folders locally.

## 2. Project Type
✓ VERIFIED: WordPress Theme Repository + All-in-One WP Migration Archive (`.wpress`). Not a standard raw WordPress installation.

## 3. WordPress Core Status
✗ MISSING: `wp-admin`, `wp-includes`, `index.php`, `wp-settings.php`, and `wp-login.php` are NOT present in the local file system (they are presumably bundled inside the `.wpress` archive).

## 4. Theme Inventory
✓ VERIFIED: The active custom theme is `lohnourpro_wp_theme` (Twenty Twenty-Five Child).
- **Directory**: `d:\lohnourpro_wp_theme`
- **Version**: 1.0.0
- **Parent Theme**: `twentytwentyfive`

## 5. Plugin Inventory
✗ MISSING: Plugins are not available as raw files on the file system. They are likely bundled inside the `.wpress` archive. Cannot verify versions or names prior to extracting the archive.

## 6. Uploads / Media Inventory
✗ MISSING: The `wp-content/uploads` directory is not present natively on the file system. Media is encapsulated inside the `.wpress` archive.

## 7. Database Configuration Status
✗ MISSING: `wp-config.php` does not exist locally.
- DB_NAME = NOT PRESENT
- DB_USER = NOT PRESENT
- DB_HOST = NOT PRESENT
- DB_PASSWORD = NOT PRESENT

## 8. SQL Backup Inventory
✗ MISSING: No raw `*.sql`, `*.sql.gz`, or `*.tar.gz` database dumps were found.
✓ VERIFIED: Found an alternative backup format: `d:\LohnourPro_Migration\Website\Backup\localhost-lohnourpro-03-Oct-2026.wpress`
- **File Size**: ~98.4 MB
- **Modification Date**: October 3, 2026

## 9. Database Migration Case
✓ VERIFIED: **CASE C** (Modified)
Theme/project files are available but raw WordPress core/database dumps are unavailable natively. The database and core files are wrapped inside a proprietary `.wpress` backup file.

## 10. Build / Deployment Requirements
✓ VERIFIED: No Node.js, `npm`, `package.json`, `composer.json`, Vite, or Tailwind config files were found in the theme. The theme uses standard PHP and CSS (`style.css`), meaning no compilation steps (like `npm build`) are required before migration.

## 11. Custom Post Types
✓ VERIFIED (from `inc/post-types.php`):
- `lohnour_service`
- `lohnour_workshop`
- `lohnour_testimonial`
- `lohnour_case_study`
- `user_guide`

## 12. Taxonomies
✓ VERIFIED (from `inc/taxonomies.php`):
- `lohnour_industry`
- `lohnour_audience`
- `lohnour_skill_level`
- `lohnour_pathway`
- `guide_sector`
- `guide_tags`

## 13. Critical Routes
✓ VERIFIED: The required custom routes exist by code definition:
- `/`
- `/programs/` (from `lohnour_service` CPT)
- `/programs/{slug}/`
- `/programs/pathway/{slug}/` (from `lohnour_pathway` taxonomy)
- `/services/`
- `/services/corporate/`
- `/guides/` (from `user_guide` CPT)
- `/guides/{slug}/`
- `/about/`
- `/contact/`

## 14. Critical Theme Templates
✓ VERIFIED: The following templates were found in the theme:
- `front-page.php`
- `archive-lohnour_service.php`
- `single-lohnour_service.php`
- `taxonomy-lohnour_pathway.php`
- `archive-user_guide.php`
- `single-user_guide.php`
- `page-services.php`
- `page-corporate.php`
- `page-about.php`
- `page-contact.php`

## 15. Files Required for Migration
1. `localhost-lohnourpro-03-Oct-2026.wpress`
2. `d:\lohnourpro_wp_theme\` (entire contents, to ensure the latest theme version is active after restoring the backup)

## 16. Files NOT Required for Migration
- `d:\lohnourpro_wp_theme\.git/`
- `d:\lohnourpro_wp_theme\.gitignore`
- `d:\lohnourpro_wp_theme\my-docs/`
- `d:\LohnourPro_Migration\Website\Graphics/`

## 17. Secrets / Security Findings
✓ VERIFIED: No API keys, passwords, DB credentials, or `.env` files were exposed in the current local file system.

## 18. Missing Dependencies
✗ MISSING: Raw WordPress core and raw database dump. We are completely reliant on the `.wpress` file for restoring the content, media, database, and plugins.

## 19. Migration Risks
⚠ REQUIRES VERIFICATION: The `.wpress` format requires the `All-in-One WP Migration` plugin to restore. There is a risk that the archive exceeds the free plugin upload limit, or that it is corrupted. We cannot inspect its contents until it is restored on a live server.
⚠ REQUIRES VERIFICATION: The exact WordPress version that created the `.wpress` file is unknown.

## 20. Recommended Migration Strategy
1. Install a fresh, secure WordPress instance on the Oracle Cloud server.
2. Install the `All-in-One WP Migration` plugin (and any necessary extension to lift file size limits, if 98MB exceeds default settings).
3. Import the `.wpress` file via the WP Admin interface or WP-CLI (if the extension supports it).
4. After restoration, manually upload/overwrite the `lohnourpro_wp_theme` directory via SSH/SFTP to ensure any recent local theme changes are applied.
5. Re-save permalinks to fix the `/programs/pathway/` rewrite rules.

## 21. Pre-Migration Checklist
- [ ] Verify maximum upload limits on the Oracle server allow for a 100MB+ `.wpress` file.
- [ ] Install fresh WordPress core on Oracle VM.
- [ ] Validate that the `.wpress` file restores correctly.
- [ ] Deploy latest theme files.
- [ ] Verify all critical routes load correctly.

## 22. BLOCKERS
🛑 BLOCKER: Cannot perform a standard SQL + `wp-content` drop-in migration. We must use the `All-in-One WP Migration` restore flow.
