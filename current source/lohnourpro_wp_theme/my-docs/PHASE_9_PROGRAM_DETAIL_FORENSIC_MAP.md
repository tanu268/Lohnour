# PHASE 9 — PROGRAM DETAIL FORENSIC MAP

## 1. Current WordPress Architecture
- **Template:** `single-lohnour_service.php`
- **CPT:** `lohnour_service`
- **Taxonomies Supported:** `lohnour_pathway`, `lohnour_audience`, `lohnour_industry`
- **Custom Meta Fields:** 
  - `_lohnour_card_excerpt` (Short excerpt for cards)
  - `_lohnour_sidebar_benefits` (Line-separated list of benefits)
- **Standard Fields:** Title, Content (`the_content`), Excerpt, Thumbnail.

## 2. Existing Program Sections
Based on `single-lohnour_service.php`:
1. **Hero & Breadcrumbs:** Displays pathway badge, title, excerpt, and a primary CTA ("Book a Consultation").
2. **Featured Image:** Displayed above the main content on the left.
3. **Program Overview:** Displays `the_content()`.
4. **What You'll Learn:** A list generated from `_lohnour_sidebar_benefits`.
5. **Right Sidebar (Sticky):** Contains Audience/Topics badges and an action CTA ("Ready to begin? -> Book a Consultation").
6. **Related Programs:** 3 related programs queried by matching `lohnour_pathway`.

## 3. Data Availability Matrix
| Reference Section | Purpose | Existing LOHNOUR Data | Can Build Now? | Notes |
|---|---|---|---|---|
| Hero | Program intro | AVAILABLE | YES | Uses Title, Excerpt, Pathway badge |
| Program Snapshot | Quick stats (Duration, Level) | NOT AVAILABLE | NO | No meta fields for Duration, Delivery, Price, etc. |
| Overview | Detailed description | AVAILABLE | YES | Driven by `the_content()` |
| Learning Outcomes | Key takeaways | AVAILABLE | YES | Driven by `_lohnour_sidebar_benefits` |
| Curriculum/Modules | Structured learning map | NOT AVAILABLE | NO | Programs do not use the hierarchical module structure that Guides use. Do NOT fabricate. |
| Projects | Practical outcomes | NOT AVAILABLE | NO | Future data requirement |
| Testimonials | Social proof | PARTIAL | NO | Testimonial CPT exists, but no relationship mapping exists for Programs. |
| FAQs | Objection handling | NOT AVAILABLE | NO | Future data requirement unless embedded in `the_content()` |
| Sticky Inquiry | Conversion | AVAILABLE | YES | Sidebar already supports `/contact/` route. |
| Related Programs | Cross-sell | AVAILABLE | YES | Already implemented based on Pathway term. |

## 4. Current vs Target Experience
**CURRENT:** 
A standard 2-column WordPress post layout. It's clean but functions more like a basic article than a premium marketing/learning landing page. It lacks structured data like Curriculum, Projects, Duration, and Pricing.

**WHAT EXISTS:** 
Title, Excerpt, Image, Content, Audience, Industry, Pathway, Benefits, Related Programs, basic CTA to `/contact/`.

**WHAT IS MISSING (Functional & Data Gaps):**
- Curriculum hierarchy (Modules/Lessons)
- Program snapshot (Duration, Level, Format, Price)
- Social proof (Ratings, Testimonial relationships)
- Projects/Case Studies relationships
- FAQs

**WHAT SHOULD BE PRESERVED:**
- The existing data model (do not invent fields).
- The routing and taxonomy architecture.
- The conversion strategy (inquiry via `/contact/` rather than a non-existent checkout).

**WHAT SHOULD CHANGE (Visual/UX Gaps):**
- The visual hierarchy and presentation of existing data to feel more premium and structured, adapting the reference UI where data permits.
- Hero needs to feel like a high-end product landing area rather than a blog header.
- "What You'll Learn" can be elevated visually.
- The Sticky sidebar can be designed as a premium conversion card.
