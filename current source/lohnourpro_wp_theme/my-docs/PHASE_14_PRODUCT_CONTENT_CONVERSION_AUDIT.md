# PHASE 14 — PRODUCT CONTENT & CONVERSION FORENSIC AUDIT

## Executive Summary
The LOHNOUR PRO Phase 13 architecture is well-structured at the routing level. However, a forensic content audit reveals that terminology and internal linking do not fully respect the defined product model. Specifically, the Homepage features broken taxonomy links for Programs, and the Guides architecture erroneously labels itself as a "Course Curriculum", blurring the distinction between free editorial guides and paid structured learning.

## Current Architecture
The frozen architecture is correctly implemented across primary templates:
- Programs (`/programs/`)
- Services (`/services/` -> Corporate Training)
- Coaching (`/coaching/`)
- Guides (`/guides/`)
- Blog (`/blog/`)

## Route Health
- `/programs/` - 200 OK
- `/services/` - 200 OK
- `/coaching/` - 200 OK
- `/guides/` - 200 OK
- `/blog/` - 200 OK
- **FAIL**: `/programs/pathway/professionals/`, `/programs/pathway/students/`, `/programs/pathway/faculty/`, `/programs/pathway/organizations/` (All returning 404 from Homepage links).

## Information Architecture Findings
- **Guides vs Programs (P1):** Guides are defined as "structured educational/editorial resources" and "NOT Programs". However, the Guides UI (`single-user_guide.php`) uses a sidebar titled "Course Curriculum". This is a major semantic collision.
- **Organizations Pathway Clash (P1):** The homepage links to a `/programs/pathway/organizations/` which implies organizations should buy a Program. But the established IA dictates organizations belong in Services (`/services/corporate/`).

## Terminology Findings
- **Program vs Course (P1):** Guides use the term "Course" and "Course Curriculum", creating direct confusion with Programs.
- **Program vs Training (P2):** The Homepage (`front-page.php`) refers to Programs interchangeably as "Programs" and "AI Training".
- **Coaching (P2):** The Homepage CTA says "Find a Coach", implying a marketplace, but the Coaching hub provides a "Request a Consultation" flow.

## Homepage Findings
- Value proposition and target audience are clear.
- **P1:** The 4 "Who We Serve" cards link to non-existent pathway slugs and one contradicts the Services architecture.
- **P2:** The Hero CTA "Find a Coach" could be improved to better reflect the consultation nature of the service.
- **P2:** The "AI Training" terminology in Section 04 clashes with the standard "Programs" label.

## Programs Findings
- The catalog filter UI and search function are well-implemented.
- Card hierarchies and labeling use "Program" and "Pathway" consistently on the archive.

## Services Findings
- Repair from Phase 13 was successful. Services correctly focuses on "Business / organizational solutions".
- "Corporate Training" is positioned clearly as the primary service.

## Coaching Findings
- The distinction between Individual and Group coaching is clear.
- No false promises of booking engines or coach marketplaces on the Coaching pages. The CTAs correctly funnel to a contact form.

## Guides Findings
- **P1:** The Guides sidebar uses "Course Curriculum", which implies the user is in a Program, not an editorial Guide. 
- **P1:** The Guide Meta box in the backend (`inc/post-meta.php`) says "Parent Course/Topic". This breaks the content governance distinction.

## Blog Findings
- The Blog is intact and uses classic WordPress Post taxonomy ("Latest Insights", "Articles"). It successfully remains separate from Guides and Programs.

## CTA Inventory
| Location | CTA | Destination | Purpose | Status |
|---|---|---|---|---|
| Homepage Hero | "Explore Programs" | `/programs/` | Discovery | PASS |
| Homepage Hero | "Find a Coach" | `/coaching/` | Discovery | P2 (Misleading Label) |
| Homepage "Who We Serve" | "Explore" (x4) | `/programs/pathway/...` | Pathway Discovery | P1 (404 Dead Ends) |
| Coaching Hub | "Request a Consultation" | `/contact/` | Contact | PASS |
| Services Hub | "Submit an Inquiry" | `/contact/` | Corporate Inquiry | PASS |

## Internal Link Findings
- Header and Footer links are correct and functional.
- Breadcrumbs are consistent and functional.
- **P1:** The 4 pathway links on the Homepage are dead ends (404).

## Content Consistency Findings
- The About and Contact pages maintain a consistent tone.
- The use of "Course" in the Guides section is the only major terminology inconsistency.

## Responsive Findings
- Grid layouts and cards scale correctly across Desktop, Tablet, and Mobile.
- The sticky sidebar in the Guides section functions correctly and features a mobile toggle.

## Accessibility Findings
- Semantic HTML tags are properly used.
- Button/link distinction is clear.
- Form inputs have sufficient contrast.

## Technical UX Findings
- **P2:** The Contact page features a visible placeholder `// AWAITING_FORM_SHORTCODE` if a form isn't injected. This looks unpolished in production.
- PHP and Console are otherwise error-free.

## Security Findings
- No rogue authentication scripts or debug files were found.

## Product Journey Findings
- Learner Journey: Fails at the Homepage -> Pathway step due to 404 links.
- Organization Journey: Succeeds if using the Services navigation, but fails if using the Homepage "Organizations" pathway card.
- Coaching Journey: Succeeds.
- Knowledge/Reader Journeys: Succeeds, though terminology is confusing in Guides.

## SEO Findings
- Canonical behavior and permalinks are standard. 
- Titles and meta descriptions rely on WordPress defaults.

## Design Consistency Findings
- Colors, border-radiuses, shadows, and spacing tokens (`--spacing-md`, `--radius-lg`) are applied consistently across all templates.
- SVG iconography follows a consistent line-weight.

## CMS / Editability Findings
- Homepage content is heavily hardcoded in PHP.
- `page-contact.php` mixes hardcoded layout with a dynamic content area for the form shortcode.
- Guides use custom meta boxes for hierarchical relationships.

## Severity Matrix

| ID | Area | Finding | Severity | Evidence | Recommended Action |
|----|------|---------|----------|----------|--------------------|
| 01 | Homepage | "Who We Serve" cards point to 404 Pathways | P1 | `front-page.php` lines 106-156 | Update URLs to valid pathways, route Organizations to `/services/corporate/`. |
| 02 | Guides | Uses "Course Curriculum" terminology | P1 | `single-user_guide.php` line 189, 205 | Rename "Course Curriculum" to "Guide Contents" or "Guide Structure". |
| 03 | Guides | Backend uses "Course/Topic" terminology | P1 | `inc/post-meta.php` line 305 | Rename labels in backend to "Guide Parent" and "Guide Module". |
| 04 | Homepage | CTA says "Find a Coach" | P2 | `front-page.php` line 22 | Change to "Explore Coaching" to match the consultation flow. |
| 05 | Contact | Placeholder text visible | P2 | `page-contact.php` line 89 | Remove the visible raw placeholder text if empty. |
| 06 | Homepage | "AI Training" used for Programs | P3 | `front-page.php` line 174 | Unify nomenclature to "Programs" or "Structured Learning". |

## STAGE B IMPLEMENTATION

Status: PASS

Files Modified:
- `front-page.php`
- `single-user_guide.php`
- `inc/post-meta.php`
- `page-contact.php`

P1 Repairs:
- Repaired the 4 broken pathway links in `front-page.php` ("Who We Serve" section) to point to the actual registered taxonomy slugs.
- Re-routed the "Organizations" card on the homepage to point to `/services/corporate/`, aligning with the product architecture.
- Replaced "Course Curriculum" with "Guide Contents" in `single-user_guide.php` to prevent semantic overlap between Programs and Guides.
- Replaced "Parent Course/Topic" with "Parent Guide" in `inc/post-meta.php` for correct admin governance.

P2 Repairs:
- Updated the Hero CTA in `front-page.php` from "Find a Coach" to "Explore Coaching" to accurately reflect the consultation flow.
- Changed "AI Training" to "Structured Learning" in `front-page.php` Section 04 to respect the boundary between Programs and Corporate Training Services.
- Replaced the developer-facing `// AWAITING_FORM_SHORTCODE` string in `page-contact.php` with a user-friendly text block directing users to email or call.

P3 Repairs:
- Unified Programs references in the homepage to remove confusing variations.

Routes Verified:
All checked endpoints correctly return HTTP 200, including the repaired pathways.

Terminology Changes:
- Course -> Guide (in Guides UI and admin meta)
- AI Training -> Structured Learning (in Homepage Section 04)
- Find a Coach -> Explore Coaching (in Homepage Hero)

Contact Placeholder Handling:
- Safely handled the empty post content condition by emitting a visually consistent, non-technical fallback text directing users to use the email and phone channels already displayed on the page.

Regression QA:
- All core architectures (Programs, Services, Coaching, Guides, Blog) are unaffected and function correctly.

Security QA:
- No rogue authentication scripts, test scripts, or autologin scripts found in the WP root directory.

Remaining Limitations:
- Homepage content is still largely hardcoded, meaning further copy adjustments require developer intervention.
