# LOHNOUR — PHASE 7.1 SERVICES ROUTE REPAIR

## Root Cause
The 404 error for `/programs/pathway/{slug}/` was caused by a WordPress rewrite rule collision, not an incorrect URL or missing database entity.
Because the `lohnour_service` Custom Post Type (slug `programs`) and the `lohnour_pathway` taxonomy (slug `programs/pathway`) were both registered on the default `init` priority (10) within files included sequentially, WordPress generated the CPT rewrite rule `programs/[^/]+/([^/]+)/?$` BEFORE the taxonomy rule.
When the URL `/programs/pathway/faculty-development/` was requested, WordPress matched the CPT attachment regex (interpreting `pathway` as the parent post and `faculty-development` as the attachment) instead of routing to the taxonomy archive.
The fix was to assign `add_action( 'init', 'lohnour_register_taxonomies', 9 )` to ensure taxonomy rewrite rules take precedence over CPT rules.

## Professional Upskilling
- **Actual WordPress entity**: Taxonomy term (lohnour_pathway)
- **Actual slug**: `professional-ai-upskilling`
- **Actual URL**: `/programs/pathway/professional-ai-upskilling/`

## Faculty Development
- **Actual WordPress entity**: Taxonomy term (lohnour_pathway)
- **Actual slug**: `faculty-development`
- **Actual URL**: `/programs/pathway/faculty-development/`

## Files Modified
- `inc/taxonomies.php`

## Files Deleted
- `setup_corporate_page.php`
- `dump_pages.php`
- `dump_terms.php`
- `debug_rewrite.php`
- `flush_rules.php`
- `flush_rules_hard.php`

## Files Not Modified
- `page-services.php` (already correctly mapped in Phase 7)
- `taxonomy-lohnour_pathway.php`
- `single-lohnour_service.php`
- `archive-lohnour_service.php`
- `front-page.php`
- `assets/css/*`
- `inc/post-types.php`

## Verification

| Test | Result |
|---|---|
| Services page | PASS |
| Professional Upskilling link | PASS |
| Faculty Development link | PASS |
| Actual Professional destination | PASS |
| Actual Faculty destination | PASS |
| Programs catalog | PASS |
| Program detail | PASS |
| Pathway route | PASS |
| Light mode | PASS |
| Dark mode | PASS |
| Mobile | PASS |
| Console | PASS |
| PHP | PASS |
| Regression | PASS |
