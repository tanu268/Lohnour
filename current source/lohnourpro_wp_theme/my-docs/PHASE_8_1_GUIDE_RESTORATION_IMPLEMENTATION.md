# LOHNOUR PRO — PHASE 8.1 GUIDE RESTORATION IMPLEMENTATION

## Status
PASS

## Files Modified
- `archive-user_guide.php`
- `single-user_guide.php`

## Files Created
- `my-docs/PHASE_8_1_GUIDE_RESTORATION_FORENSIC.md`
- `my-docs/PHASE_8_1_GUIDE_RESTORATION_IMPLEMENTATION.md`

## Files Protected
- `single-lohnour_service.php`
- `archive-lohnour_service.php`
- `page-services.php`
- `page-corporate.php`
- `front-page.php`
- `taxonomy-lohnour_pathway.php`
- `inc/post-types.php`
- `inc/taxonomies.php`
- `header.php`
- `footer.php`

## Data Sources
- **Guide Curriculum**: Navigated via `post_parent` native WP relationship.
- **Module Grouping**: `_guide_module_name` meta value on child guides.
- **Module Ordering**: `_guide_modules_list` meta value on the parent guide.
- **Article Ordering**: Native `menu_order` field on child guides.

## Guide → Article Logic
Articles are queried based on the root parent. The root ID is determined using `wp_get_post_parent_id()`. If the current view is the root topic itself, it calculates the curriculum relative to itself. If it is an article, it fetches the root parent to display context, build the navigation menu, and bound the Previous/Next queries strictly within that parent.

## Module Grouping Logic
Siblings fetched from the root Guide are looped and grouped into an array indexed by their `_guide_module_name` meta value. If a lesson lacks a module name, it falls back to 'General Resources'.

## Module Ordering Logic
The `_guide_modules_list` array is fetched from the parent post. A mapping array of `[ModuleName => OrderIndex]` is created. The grouped sibling modules array is then sorted using `uksort` to align with the parent's intended visual sequence.

## Article Ordering Logic
The initial `WP_Query` fetching siblings is ordered by `menu_order ASC`, ensuring the child articles are sorted sequentially before they are grouped into modules.

## Structural Progress Calculation
The structured curriculum array is flattened to determine the total number of articles (`$total_articles`) and the current article's index position (`$current_position`). 
- **Progress Value**: Calculated as `($current_position / $total_articles) * 100`.
- **Display Text**: Rendered cleanly as `Article X of Y`.
- **Constraint Checklist**: No fake completion metrics, user percentages, or time-remaining stats were created. It is entirely structural data.

## TOC Generation
A custom PHP Regex parses the raw HTML output of `the_content()` after WordPress filters have been applied. It identifies `<h2>` and `<h3>` tags, strips inner HTML, generates a sanitized slug, and injects an `id` attribute if one is not natively present. The matches are collected into an array and rendered in the Right sidebar as standard anchor links.

## Previous/Next Logic
Instead of using native WP adjacent post functions (which may leak into other Guides or Posts), the script identifies the `$current_index` within the flattened, ordered curriculum array. The previous and next posts are simply `$current_index - 1` and `$current_index + 1`. This safely restricts pagination to the current Curriculum.

## Responsive Behavior
- **Desktop (≥ 1025px)**: 3-column layout (Curriculum 300px | Article 1fr | TOC 250px).
- **Tablet (769px - 1024px)**: 2-column layout. The Right TOC is hidden, focusing the view on the Curriculum and Article.
- **Mobile (≤ 768px)**: Single column. The Left Curriculum is collapsed behind an accessible toggle button ("Course Curriculum"). Sequential pagination aligns vertically.

## Accessibility
- Module dropdowns utilize `aria-expanded` and semantic `<button>` elements.
- The mobile curriculum toggle utilizes `aria-controls`.
- Native browser focus works for all anchor tags in the curriculum and TOC.
- Hover behaviors are replicated visually using colors that pass contrast checks in both light and dark modes.

## Security
- The TOC generator uses `sanitize_title()` for IDs and `esc_attr()` when injecting them.
- `esc_url()` and `esc_html()` are used consistently across all dynamic data points.
- DOM manipulation does not output raw user data without sanitization.

## Performance
- **Zero external libraries**: The layout uses native CSS grid/flexbox.
- **Vanilla JS**: The module toggles, mobile toggle, and sticky TOC highlighting rely on a 40-line vanilla JavaScript block including the native `IntersectionObserver` API. No React or jQuery dependencies.
- **Minimal Queries**: Curriculum grouping and previous/next generation occur in memory using the single fetched `$siblings` array instead of executing redundant queries.

## Limitations
- Structural progress does not persist across user sessions since there is no backend tracking structure.
- The TOC regex logic relies on standard HTML structures. Highly customized shortcodes that output nested/malformed heading tags may not parse cleanly, although standard Gutenberg blocks are perfectly supported.
- `post_parent => 0` forces child articles to only be discovered via their Parent Guide. They will not appear independently in the Guide Hub, which aligns with LMS intent but may restrict SEO if children lack standalone value.
