# LOHNOUR — PHASE 4 PROGRAMS FORENSIC MAP

## Current Implementation
- **Template:** `archive-lohnour_service.php`
- **Data Source:** Custom Post Type `lohnour_service`.
- **Existing Structure:** The current template loops through the `lohnour_pathway` taxonomy and executes a separate `WP_Query` for each pathway, displaying a subset of programs under a pathway header.
- **Existing Styles:** Uses `.service-card`, `.glass-card`, `.hover-lift` for the cards. Global search and filter primitives (`.search-primitive`, `.filter-group`, `.filter-btn`, `.state-container`) exist in `assets/css/pages.css`.
- **Existing Behavior:** Server-rendered loops with no pagination or active filtering mechanisms. It relies on a pre-curated display logic per pathway.

## Missing Requirements
- Unified "Discovery" catalog grid rather than pre-grouped pathway sections.
- Search input functional with WordPress core search (`s` parameter restricted to post type).
- Filter controls for Audience/Pathway using standard HTTP GET parameters.
- Empty states for when no programs match the search/filter criteria.
- Pagination for the main catalog query.

## Conflicts & Risks
- **Taxonomy Usage:** The blueprint references "Audience", "Format", and "Topic". The available taxonomies registered in `inc/taxonomies.php` for `lohnour_service` are `lohnour_industry`, `lohnour_audience`, and `lohnour_pathway`. We will dynamically render filters based on `lohnour_pathway` and `lohnour_industry` (which can act as Topic) to ensure no fake fields are hallucinated. "Format" will be omitted unless a clear metadata field exists.
- **Routing:** Must ensure that `archive-lohnour_service.php` respects the main WordPress query when URL parameters (like `?s=AI` or `?lohnour_pathway=professionals`) are passed, avoiding the need for a custom REST API.

## Proposed Changes
1. **Hero Section:** Add a dedicated, concise Hero block at the top of the archive.
2. **Search & Filter UI:** Implement a standard `<form method="GET" action="<?php echo esc_url( get_post_type_archive_link( 'lohnour_service' ) ); ?>">` utilizing the `.search-primitive` and `.filter-group` CSS classes.
3. **Main Grid:** Replace the per-pathway `WP_Query` loops with a unified WordPress loop relying on the main query (`have_posts()`), or a sanitized custom `WP_Query` that ingests `$_GET` parameters for filtering.
4. **Card Adjustments:** Maintain the `.service-card` visual language but ensure the CTA links directly to `get_permalink()` and is labeled "View Program →".
5. **Empty State:** Implement `.state-container` fallback when `WP_Query` returns 0 results.
