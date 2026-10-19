# CURRENT PAGE INVENTORY

| Current Route | Page Template | Purpose | Main Components | CTA | Destination | API / Data Source | Status |
|---|---|---|---|---|---|---|---|
| `/` | `front-page.php` | Homepage discovery | Hero, Who We Serve, How You Learn, Featured Programs, Latest Guides | Explore Programs, Book a Consultation | `/services`, `/contact` | WP Post Loop & Static | CORRECT |
| `/services/` | `archive-lohnour_service.php` | Program catalog | Program Grid, `.service-card` | View Program | `/services/:slug` | `lohnour_service` CPT | NEEDS REVISION (URL mismatch with target `/programs`) |
| `/services/:slug` | `single-lohnour_service.php` | Program detail | Hero, Prose Content, Sidebar | Book a Consultation, Back to Programs | `/contact/`, `/services/` | `lohnour_service` CPT | NEEDS REVISION (Lacks Enrollment/Cohort data) |
| `/services/pathway/:slug`| `taxonomy-lohnour_pathway.php`| Audience filter | Program Grid | View Program | `/services/:slug` | `lohnour_pathway` Tax | CORRECT |
| `/guides/` | `archive-user_guide.php` | Guides catalog | Guide Grid | Read Guide | `/guides/:slug` | `user_guide` CPT | CORRECT |
| `/guides/:slug` | `single-user_guide.php` | Guide detail | Content | - | - | `user_guide` CPT | CORRECT |
| `/about/` | `page-about.php` | About Lohnour | Static Content | - | - | Page | CORRECT |
| `/contact/` | `page-contact.php` | Inquiry Form | Form | Submit | - | Page | NEEDS REVISION (Should it be Corporate Training?) |
| `/coaching/individual` | - | Find a coach | - | - | - | Missing | MISSING |
| `/coaching/group` | - | Group cohorts | - | - | - | Missing | MISSING |
| `/coaches/:id` | - | Coach Profile | - | - | - | Missing | MISSING |
| `/services/corporate` | - | Corporate Inquiry| - | - | - | Missing | MISSING |
