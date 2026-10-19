# Implementation File Map

## Global Shell & Utilities
- `functions.php`: Routes templates, includes `inc/` files.
- `header.php`: Global site header, navigation (`primary` menu), dark/light mode toggle.
- `footer.php`: Global footer (implied).
- `inc/setup.php`: Enqueues scripts, styles, registers menus and sidebars.
- `inc/post-types.php`: Registers CPTs (`lohnour_service`, `lohnour_workshop`, `lohnour_case_study`, `user_guide`, `lohnour_testimonial`).
- `inc/taxonomies.php`: Registers Taxonomies (`lohnour_industry`, `lohnour_audience`, `lohnour_skill_level`, `lohnour_pathway`, `guide_sector`, `guide_tags`).
- `inc/helpers.php`: Utility functions.
- `inc/post-meta.php`, `inc/service-meta.php`, `inc/admin-fields.php`: Custom fields.

## Core Templates
- `front-page.php`: Homepage template.
- `page-about.php`: About page template.
- `page-contact.php`: Contact page template.
- `archive-lohnour_service.php`: Current "Programs" catalog (mapped to `/services`).
- `single-lohnour_service.php`: Current "Program Detail" page.
- `archive-user_guide.php`: Guides catalog.
- `single-user_guide.php`: Guide detail.
- `taxonomy-lohnour_pathway.php`: Pathway audience page.
- `taxonomy-guide_sector.php`, `taxonomy-guide_tags.php`: Guide category/tag archives.

## Missing Files (To Be Created)
- Templates for `/programs` (if we rename slug).
- Templates for `/coaching` and `/coaching/*`.
- Templates for true `/services` and `/services/corporate`.
