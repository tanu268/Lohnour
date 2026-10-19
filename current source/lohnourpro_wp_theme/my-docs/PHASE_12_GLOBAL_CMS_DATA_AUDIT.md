# LOHNOUR PRO — PHASE 12
# GLOBAL CMS & DATA QUALITY AUDIT

## 1. Executive Summary
This Phase 12 forensic audit maps the entire content and CMS architecture of the LOHNOUR PRO implementation. The current architecture successfully supports the platform's visual design while avoiding premature infrastructure. However, the audit reveals several areas where hardcoded PHP strings handle content that a non-developer administrator would typically expect to control via the WordPress UI. Additionally, a semantic mismatch exists where the "Programs" product relies on a legacy post type slug (`lohnour_service`). Overall, the architecture is healthy and scalable, but requires minor CMS administration and editability improvements to hand it over to a client safely.

## 2. Canonical Content Model

| Content Type | WordPress Type | URL | Template | Taxonomies | Custom Fields | Status |
|---|---|---|---|---|---|---|
| Programs | CPT: `lohnour_service` | `/programs/{slug}` | `single-lohnour_service.php` | `lohnour_pathway`, `lohnour_audience`, `lohnour_industry` | `_lohnour_card_excerpt`, `_lohnour_sidebar_benefits`, `_is_featured_post` | Active |
| Pathways | Taxonomy: `lohnour_pathway`| `/programs/pathway/{slug}` | `taxonomy-lohnour_pathway.php`| N/A | `_featured_programs` (Term Meta) | Active |
| Guides | CPT: `user_guide` | `/guides/{slug}` | `single-user_guide.php` | `guide_sector`, `guide_tags` | `_guide_module_name`, `_guide_modules_list` | Active |
| Services | Page | `/services/` | `page-services.php` | N/A | None | Active |
| Corp. Services | Page | `/services/corporate/` | `page-corporate.php` | N/A | None | Active |
| Coaching | Page | `/coaching/` | `page-coaching.php` | N/A | None | Active |
| Indiv. Coaching| Page | `/coaching/individual/`| `page-individual.php` | N/A | None | Active |
| Group Coaching | Page | `/coaching/group/` | `page-group.php` | N/A | None | Active |
| Workshops | CPT: `lohnour_workshop` | `/workshops/{slug}` | `single-lohnour_workshop.php`| `lohnour_audience`, `lohnour_skill_level`, `lohnour_industry` | None identified | Dormant |
| Case Studies | CPT: `lohnour_case_study` | `/case-studies/{slug}`| `single-lohnour_case_study.php`| `lohnour_industry` | None identified | Dormant |
| Testimonials | CPT: `lohnour_testimonial`| N/A | N/A | None | None identified | Dormant |

## 3. Programs Audit
- **Entities:** Represented via `lohnour_service` CPT.
- **Metadata:** Fully utilizes `_lohnour_card_excerpt` for cards and `_lohnour_sidebar_benefits` for the right-hand metadata pane. The `_is_featured_post` toggle effectively manages homepage placement.
- **Taxonomies:** Properly grouped using `lohnour_pathway`.
- **Finding:** The architecture is structurally sound, but the internal slug `lohnour_service` is semantically confusing since the public offering is called "Programs".

## 4. Services Audit
- **Entities:** Currently entirely managed by standard Pages (`page-services.php` and `page-corporate.php`).
- **Mismatch:** The CPT `lohnour_service` actually controls Programs. The real Services are standard Pages with hardcoded content. 
- **Risk:** This is a **Confusing but safe** legacy naming artifact. A new admin might click "Services" in the WP Admin expecting to edit the Corporate Services page, but would actually be editing Programs.

## 5. Guides Audit
- **Entities:** `user_guide` CPT.
- **Hierarchy:** Uses standard `post_parent` and `menu_order` natively.
- **Metadata:** Leverages `_guide_module_name` and `_guide_modules_list` meta keys to manage grouping effectively.
- **Finding:** Robust, native WP behavior. Fully CMS manageable. No orphaned articles detected via code inspection.

## 6. Coaching Audit
- **Entities:** WordPress Pages.
- **Structure:** Static content hardcoded in `page-coaching.php`, `page-individual.php`, and `page-group.php`.
- **Finding:** Completely safe. No premature booking infrastructure. CMS editability is poor (requires code edits for text changes), but structurally sound for this phase.

## 7. Custom Field Inventory

| Meta Key | Used By | Purpose | Source | Active? | Recommendation |
|---|---|---|---|---|---|
| `_lohnour_card_excerpt` | `lohnour_service` | Short summary for cards | Custom Metabox | ACTIVE | Keep |
| `_lohnour_sidebar_benefits` | `lohnour_service` | Array of sidebar highlights | Custom Metabox | ACTIVE | Keep |
| `_is_featured_post` | Multiple CPTs | Homepage display toggle | Custom Metabox | ACTIVE | Keep |
| `_guide_module_name` | `user_guide` | Name of parent module | Custom Metabox | ACTIVE | Keep |
| `_guide_modules_list`| `user_guide` | Ordering of modules | Custom Metabox | ACTIVE | Keep |

## 8. Taxonomy Inventory

| Taxonomy | Post Type | Purpose | Used? | Terms | Risk |
|---|---|---|---|---|---|
| `lohnour_pathway` | `lohnour_service` | Grouping Programs | YES | Active | Low |
| `lohnour_audience` | `lohnour_service`, `workshop`| Target learner group | YES | Active | Low |
| `lohnour_industry` | `lohnour_service`, `case_study`| Vertical targeting | YES | Active | Low |
| `guide_sector` | `user_guide` | Categorizing guides | YES | Active | Low |
| `guide_tags` | `user_guide` | Secondary sorting | YES | Active | Low |
| `lohnour_skill_level`| `lohnour_workshop` | Skill prerequisite | NO | Dormant | Low |

## 9. Template Responsibility Matrix

| Template | CMS Data | Hardcoded Content | Mixed | Risk |
|---|---|---|---|---|
| `front-page.php` | Dynamic Loops | Hero, Trust Bar, Process | Mixed | Medium |
| `single-lohnour_service.php`| Post Content, Meta | Structure | Mixed | Low |
| `taxonomy-lohnour_pathway.php`| Term Loop | Hero | Mixed | Low |
| `page-services.php` | None | Entire content | Hardcoded | High |
| `page-corporate.php` | None | Entire content | Hardcoded | High |
| `page-coaching.php` | None | Entire content | Hardcoded | High |
| `page-individual.php` | None | Entire content | Hardcoded | High |
| `page-group.php` | None | Entire content | Hardcoded | High |

## 10. Admin Editability Matrix
Can the administrator:
- Edit Program content? **YES**
- Edit Guide content? **YES**
- Edit Guide module structure? **YES**
- Edit Services page content? **NO** (Hardcoded in PHP)
- Edit Coaching page content? **NO** (Hardcoded in PHP)
- Edit Homepage Hero? **NO** (Hardcoded in PHP)

## 11. Content Duplication Audit
- **CTAs:** Many CTAs linking to `/contact/` are hardcoded across multiple templates. 
- **Methodologies:** The "Understand, Plan, Grow" messaging is duplicated directly in the PHP files for Individual and Group coaching templates.

## 12. Legacy / Dead Code Audit
- **`lohnour_workshop` CPT:** SAFE TO REMOVE LATER. Not currently surfaced in the UI.
- **`lohnour_case_study` CPT:** SAFE TO REMOVE LATER.
- **`lohnour_testimonial` CPT:** SAFE TO REMOVE LATER.
- **`lohnour_service` CPT Slug:** MUST RETAIN FOR NOW. Renaming this requires a full database migration of posts and rewrite flushing.

## 13. Route Consistency Audit
- `/` - 200
- `/programs/` - 200
- `/programs/pathway/{slug}/` - 200
- `/services/` - 200
- `/services/corporate/` - 200
- `/guides/` - 200
- `/coaching/` - 200
- `/coaching/individual/` - 200
- `/coaching/group/` - 200
- `/contact/` - 200

## 14. SEO / URL Safety Audit
- **Canonical URLs:** Relying on default WordPress behavior.
- **Titles:** Using safe `get_the_title()` rendering.
- **Slug Consistency:** Strong. Custom rewrite logic for Pathways handles hierarchy safely.
- **Risk:** Hardcoded pages (Services/Coaching) cannot utilize standard Yoast/RankMath meta description boxes easily because the content exists outside the WordPress editor.

## 15. Content Quality Audit
- Some Programs have empty excerpts or missing benefits metadata. 
- The hardcoded content strategy prevents standard WordPress spell-checking or revision history for Services and Coaching pages.

## 16. Data Model Health Scores
- **Programs:** 8/10 (Structurally excellent, but naming mismatch is annoying).
- **Services:** 4/10 (Entirely hardcoded PHP; non-editable).
- **Guides:** 9/10 (Excellent native WP implementation).
- **Coaching:** 4/10 (Entirely hardcoded PHP; non-editable).
- **Templates:** 6/10 (Clean code, but too much content mixed with presentation).
- **Admin Editability:** 5/10 (Major site areas are inaccessible to non-developers).
- **Routes:** 9/10 (Clean, canonical, and stable).

## 17. P0 / P1 / P2 Findings
- **P0:** NONE (The site is stable and routing functions correctly).
- **P1 — Admin Editability:** The Services and Coaching pages must be connected to standard WordPress `the_content()` or ACF fields so administrators can actually edit the text.
- **P1 — SEO Meta:** Hardcoded pages cannot easily use SEO plugins.
- **P2 — Semantic Naming:** The internal `lohnour_service` CPT should eventually be renamed to `lohnour_program` via a database migration script.

## 18. Architectural Verdict
**YELLOW**
The application is robust, fast, and technically sound (no broken routes, clean data structures for Programs/Guides). However, the reliance on hardcoded PHP templates for top-level pages (Homepage, Services, Coaching) severely limits CMS maintainability.

## 19. Recommended Next Phase
`PHASE 12.1 — ADMIN EDITABILITY IMPROVEMENT`
(Transitioning hardcoded PHP content in Page templates to standard WordPress Editor or Custom Fields).

## 20. Explicit Non-Goals
- No production files were modified.
- No CPTs were renamed or deleted.
- No database queries altered existing data.
- No taxonomies were modified.
