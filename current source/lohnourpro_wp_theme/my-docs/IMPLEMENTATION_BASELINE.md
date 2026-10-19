# Implementation Baseline

## Theme Context
- **Theme**: Lohnour Pro (Twenty Twenty-Five Child Theme)
- **Version**: 1.0.6
- **Directory**: `d:\lohnourpro_wp_theme`

## Core Findings
1. **Programs vs. Services Confusion**: Currently, the CPT `lohnour_service` is being used to display "Training Programs". The route `/services` leads to `archive-lohnour_service.php` which is titled "Training Programs". This conflicts with the target architecture where Programs and Services are distinct.
2. **Pathways**: Currently implemented as a taxonomy `lohnour_pathway` associated with `lohnour_service`. Route is `/pathway`. Target is `/programs/pathway/{slug}`.
3. **Guides**: Implemented as CPT `user_guide` with taxonomies `guide_sector` and `guide_tags`. Route is `/guides`. This aligns closely with the target architecture.
4. **Coaching**: No existing CPT or templates for Coaching (`/coaching`, `/coaching/individual`, `/coaching/group`).
5. **Corporate Training / Services**: Currently lacks a distinct separation from Programs.
6. **Forms**: The Contact page (`page-contact.php`) relies on the WP Block Editor content for its form (expecting a shortcode like Contact Form 7, WPForms, etc.). There is no custom API or hardcoded form processing in the template.

## CSS / JS / Assets
- `style.css` (child theme metadata)
- `assets/css/theme.css` (modular imports)
- `assets/css/guides.css`
- `assets/js/main.js`, `navigation.js`, `animations.js`, `guides.js`

## Conclusion
The baseline reveals that `lohnour_service` must be repurposed or renamed via rewrite rules to `/programs`, and a new approach (pages or new CPT) is needed for the actual B2B "Services" and "Coaching".
