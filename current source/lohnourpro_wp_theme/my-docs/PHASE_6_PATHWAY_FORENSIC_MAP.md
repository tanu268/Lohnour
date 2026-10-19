# LOHNOUR — PHASE 6 PATHWAY FORENSIC MAP

## Taxonomy
- **Taxonomy Name:** `lohnour_pathway`
- **Registration Location:** `inc/taxonomies.php`
- **Public Visibility:** Yes, `publicly_queryable => true` (implicitly or explicitly via defaults)
- **Rewrite Configuration:** `slug => 'programs/pathway'`
- **Actual Term Slugs:** Fetched dynamically via `get_queried_object()`. (e.g. students, professionals, corporate, etc based on DB).
- **Actual Term Names:** Fetched dynamically via `get_queried_object()->name`.

## Route
- **Current Route:** `/programs/pathway/{slug}/`
- **Template:** `taxonomy-lohnour_pathway.php`

## Data Sources
- **Pathway Title:** `get_queried_object()->name`
- **Pathway Description:** `get_queried_object()->description`
- **Programs Query:** Main WP_Query in `taxonomy-lohnour_pathway.php` (no custom query needed as WordPress core naturally queries posts belonging to the current taxonomy term for taxonomy archives).
- **Program Title:** `get_the_title()`
- **Program Excerpt:** `get_post_meta( get_the_ID(), '_lohnour_card_excerpt', true )` or `get_the_excerpt()`
- **Program Image:** `get_the_post_thumbnail()`
- **Program Link:** `get_permalink()`

## Proposed Changes
1. **Hero & Breadcrumbs:** Replace the generic `get_template_part('template-parts/page-header')` with a custom, premium `.page-hero` mirroring the styling established in Phase 4 and 5. The breadcrumb will accurately route: `Home` -> `Programs` -> `{Pathway Name}`.
2. **Context Section:** Introduce a short editorial block displaying the taxonomy description directly below the title, giving context to the audience segment.
3. **Program Cards:** Update the `.service-card` loop to use the exact HTML/CSS structure established in Phase 4 (`.lohnour-card.card-program`) ensuring uniformity across the entire Programs experience.
4. **Empty State:** Implement `.state-container` fallback for empty pathways.
5. **Final CTA:** Append a concluding `.catalog-discovery` section linking back to `/programs/`.
