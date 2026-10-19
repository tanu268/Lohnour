# LOHNOUR — PHASE 5 PROGRAM DETAIL FORENSIC MAP

## Data Sources
- **Title**: Standard WordPress `get_the_title()`.
- **Description / Overview**: Standard WordPress `get_the_content()`.
- **Featured Image**: Standard WordPress `get_the_post_thumbnail()`.
- **Pathway**: Taxonomy `lohnour_pathway`.
- **Industry / Topic**: Taxonomy `lohnour_industry`.
- **Audience**: Taxonomy `lohnour_audience` (or fallback to pathway).
- **Learning Outcomes**: Custom Field `_lohnour_sidebar_benefits`.
- **Trainer**: **NOT AVAILABLE IN CURRENT DATA MODEL**. (No trainer CPT or meta fields exist in `post-types.php`. Will not hallucinate this section.)
- **CTA**: Direct link to `/contact/` (using existing `home_url('/contact')` established in previous phases).
- **Related Programs**: Query `lohnour_service` using the same `lohnour_pathway` terms, excluding the current post ID, limited to 3 results.

## Template Mapping
- **Template**: `single-lohnour_service.php` (Primary rendering file for `/programs/{slug}/`).

## CTA Mapping
- **Hero CTA**: Routes to `/contact` for consultation inquiries.
- **Sidebar CTA**: Routes to `/contact`.
- **Related Programs CTA**: Routes to individual `get_permalink()` endpoints.

## Related Program Logic
Selects up to 3 `lohnour_service` posts that share the identical `lohnour_pathway` terms as the current post, excluding the current post from the results.

## Missing Data
- No specific duration, pricing, or start date metadata fields exist.
- No trainer/coach relationship exists yet.
- Therefore, no transactional "checkout" data will be rendered. The CTA strictly routes to `/contact`.

## Files Modified
- `single-lohnour_service.php`

## Files Created
- `my-docs/PHASE_5_PROGRAM_DETAIL_FORENSIC_MAP.md`

## Files Intentionally Not Modified
- `archive-lohnour_service.php`
- `front-page.php`
- `inc/post-types.php`
- `inc/taxonomies.php`
- `assets/css/pages.css`
- Any Homepage components or global header/footer templates.
