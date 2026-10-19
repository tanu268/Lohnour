# PHASE 7D — CONTENT RECONCILIATION AUDIT

## 1. Scope
This audit identifies whether database records are required for the five missing architectural route endpoints (`services`, `corporate`, `coaching`, `group`, `individual`) and verifies if sufficient source evidence exists to reconstruct them safely, ensuring no placeholder or fabricated data is used.

## 2. Phase 7C Baseline
Phase 7C established:
- The deployed theme includes complete page templates (`page-services.php`, `page-corporate.php`, `page-coaching.php`, `page-group.php`, `page-individual.php`).
- These templates correspond to specific landing page logic defined in `functions.php`.
- The routes `/services/`, `/corporate/`, `/coaching/`, `/group/`, and `/individual/` currently return 404 because no corresponding WordPress pages exist in the active database.

## 3. Local Source Evidence
Extensive inspection of the `D:\lohnourpro_wp_theme\` local source revealed:
- **Design & Layout**: All five PHP templates are fully populated with hard-coded, production-ready HTML (Hero sections, value propositions, grids, and CTAs).
- **Graceful Fallbacks**: The templates request landing metadata (e.g., `_lohnour_hero_title`) and post content (`get_the_content()`), but gracefully fallback to robust, hard-coded default copy if no data exists. For instance, `page-coaching.php` contains the entire coaching hub layout and does not even call `get_the_content()`. 
- **Missing Exports**: There are no JSON, XML, or SQL seed files within the repository that provide database payloads for these pages. The "content" is the template itself.

## 4. Database Evidence
Read-only queries via WP-CLI confirmed:
- No pages with the post names `services`, `corporate`, `coaching`, `group`, or `individual` exist in the database (not in publish, draft, or trash states).

## 5. Landing Metadata Contract
The `functions.php` file registers the `lohnour_landing_meta` meta box and three specific fields:
- `_lohnour_hero_title` (String)
- `_lohnour_hero_subtitle` (String)
- `_lohnour_cta_label` (String)
**Contract Execution**: These fields are completely optional. The templates explicitly check if they are empty using `!empty()` and inject hard-coded fallback content natively if they are missing. Empty fields cause no errors and do not disrupt the layout.

## 6. Per-Page Evidence
| Route/Slug | Template | Source Content Found? | DB Record Exists? | Meta Exists? |
|---|---|---|---|---|
| `services` | `page-services.php` | YES (Hard-coded in Template) | NO | NO (Fallbacks active) |
| `corporate` | `page-corporate.php` | YES (Hard-coded in Template) | NO | NO (Fallbacks active) |
| `coaching` | `page-coaching.php` | YES (Hard-coded in Template) | NO | NO (Fallbacks active) |
| `group` | `page-group.php` | YES (Hard-coded in Template) | NO | NO (Fallbacks active) |
| `individual` | `page-individual.php` | YES (Hard-coded in Template) | NO | NO (Fallbacks active) |

## 7. Source of Truth Determination
Based strictly on the evidence, the classification for all five pages is:
**C. TEMPLATE-ONLY — NO SOURCE CONTENT FOUND**
The templates themselves serve as the authoritative content layer. The database records are merely required as "shells" to trigger the WordPress routing engine to load the templates.

## 8. Internal References
These five endpoints are deeply integrated into the core site architecture:
- **`header.php`**: Hard-coded navigation links point to `/programs/`, `/coaching/`, `/services/`, `/guides/`, `/about/`, `/contact/`. 
- **`header.php`**: Header CTA explicitly links to `/programs/`.
- **`front-page.php`**: Primary homepage cards and CTAs link directly to `/services/corporate/`, `/coaching/`, `/coaching/individual/`, and `/coaching/group/`.
- **`page-services.php`**: Links explicitly to `/services/corporate/`.
- **`page-coaching.php`**: Links explicitly to `/coaching/individual/` and `/coaching/group/`.
*Without these pages, the primary navigation and homepage user journeys are broken.*

## 9. Empty-Page Safety Analysis
1. *Would creating an empty WP Page make the route valid?* **Yes**, standard WP routing will resolve the URL and map it to the respective template.
2. *Would the template render correctly without page content?* **Yes**, all templates have comprehensive hard-coded fallbacks.
3. *Does the template require page content?* **No**.
4. *Does the template require landing metadata?* **No**, fallbacks handle missing meta gracefully.
5. *Does the source project provide that metadata?* **No**.
6. *Would an empty page create a misleading production page?* **No**, it will seamlessly render the intended, fully-designed LOHNOUR pages.
7. *Is the page intended to be populated later?* **Yes**, admins can eventually override the hard-coded fallbacks using the WP editor if desired.

## 10. Required Database Objects
Five empty WordPress Pages are required. No terms, menus, or taxonomies need to be created.

## 11. Required Post Metadata
None. The templates safely handle missing metadata.

## 12. Content that can be reconstructed with evidence
The structural routing nodes for the 5 pages.

## 13. Content that must NOT be invented
We must not invent arbitrary page content (`post_content`) or meta values (`_lohnour_hero_title`, etc.). The hard-coded fallbacks provided by the local source must be used as the authoritative presentation.

## 14. Proposed Database Operations
Create the following pages (empty `post_content`):
1. **Title:** Services | **Slug:** `services` | **Template:** `page-services.php`
2. **Title:** Corporate | **Slug:** `corporate` | **Template:** `page-corporate.php`
3. **Title:** Coaching | **Slug:** `coaching` | **Template:** `page-coaching.php`
4. **Title:** Group | **Slug:** `group` | **Template:** `page-group.php`
5. **Title:** Individual | **Slug:** `individual` | **Template:** `page-individual.php`

## 15. Rollback Strategy
If the injection fails or causes routing anomalies, the operation can be reversed safely via a simple WP-CLI command:
`wp post delete <ID> --force` for the newly generated page IDs.

## 16. Final Recommendation
**CREATE PAGE** for all five missing structural routes.
The creation of empty shell pages is strictly necessary, fully supported by the template architecture, and perfectly safe.

## 17. Risk Assessment
**LOW**
The creation of empty WP pages carries zero risk of breaking existing content. It strictly resolves the broken routing identified in the forensic audit, restoring intended functionality without fabricating information.

---
PHASE 7D AUDIT COMPLETE — NO DATABASE CHANGES MADE.

Execution approval required before any database/content modification.
