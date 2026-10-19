# LOHNOUR PRO — PHASE 11
# COACHING CONTENT ARCHITECTURE & DATA STRATEGY

## 1. Executive Summary
The Coaching architecture was established in Phase 10.2 as a set of standard WordPress Pages utilizing safe, static templates. This strategy correctly avoided premature complexity, fake data, and technical debt. As LOHNOUR PRO grows, the coaching product may require structured data (e.g., distinct coaching "Offers" or "Coach Profiles"). However, introducing those entities today—when the product only consists of high-level informational pages routing to a contact form—would be architectural over-engineering. The recommendation is to retain the current Page architecture for now, with a clear evolution path defined for when transactional logic is actually required.

## 2. Current Coaching Architecture
- `/coaching/` (Hub) → `page-coaching.php` (WP Page ID 154)
- `/coaching/individual/` → `page-individual.php` (WP Page ID 155)
- `/coaching/group/` → `page-group.php` (WP Page ID 156)
- **Data Model:** Standard WordPress `page` post type.
- **Transactions:** Handled via external inquiry (`/contact/`).

## 3. Current Data Inventory
Currently, all coaching content is hardcoded directly into the template files. 
- **WordPress Database:** Only the Page Titles and Slugs exist in the database.
- **Templates:** Contain all Hero text, product descriptions, methodology steps ("Understand, Plan, Grow"), target audiences, and CTAs.
- **Missing Data:** No coach profiles, pricing, session dates, availability, or cohorts exist.

## 4. Existing WordPress Architecture
The repository heavily leverages Custom Post Types (CPTs) for structured entities:
- `lohnour_service` (Programs)
- `lohnour_workshop` (Workshops)
- `user_guide` (Guides)
- `lohnour_case_study`
- `lohnour_testimonial`

Taxonomies are used effectively for categorization (e.g., `lohnour_pathway`, `lohnour_audience`).

## 5. Programs vs Coaching Comparison
- **Programs (`lohnour_service`):** Structured catalog items with specific benefits, durations, formats, pathways, and dynamic template rendering.
- **Coaching:** Currently conceptual. There is no "catalog" of coaching offers. There is only a broad description of "Individual" and "Group" methodologies.
- **Conclusion:** Coaching does not currently justify a CPT like Programs because there are only two static concepts, not a repeatable dataset of 10+ distinct offers.

## 6. Content Ownership Matrix

| Information | WordPress Page | CPT | Taxonomy | Custom Field | Future Backend |
|---|---|---|---|---|---|
| Coaching Hub | YES | - | - | - | - |
| Indiv. Coaching | YES | - | - | - | - |
| Group Coaching | YES | - | - | - | - |
| Methodology | YES (Hardcoded) | - | - | - | - |
| Audience | YES (Hardcoded) | - | - | - | - |
| Coaching Offer | - | FUTURE | - | - | - |
| Coach Identity | - | FUTURE | - | - | - |
| Pricing | - | - | - | - | FUTURE |
| Availability | - | - | - | - | FUTURE |
| Booking | - | - | - | - | FUTURE |

## 7. Recommended Coaching Architecture
**Option A: Pages Only (RECOMMENDED FOR CURRENT PHASE)**
- **Why:** The current business offering is purely informational. Adding a CPT for "Coaching Offers" when there are only two static pages creates unnecessary maintenance overhead.
- **When to upgrade:** We should only upgrade to Option B (Pages + Coaching CPT) when LOHNOUR PRO begins selling distinct, named coaching packages (e.g., "Executive AI Leadership Coaching", "Team Prompt Engineering Workshop").

## 8. Proposed Future Data Model
If structured Coaching Offers become necessary, the conceptual model should be:
**CPT: `lohnour_coaching`**
- Title (Required)
- Slug (Required)
- Type (Taxonomy: Individual / Group) (Required)
- Short Description (Required)
- Format (Custom Field) (Optional)
- Duration (Custom Field) (Optional)
- Focus Areas (Repeater/Content) (Required)
- Base Price (Custom Field) (Future)
- Booking CTA URL (Custom Field) (Future)

## 9. Taxonomy Strategy
- **`coaching_type` (New):** Useful if distinguishing between 1:1, Group, or Retainer formats.
- **`lohnour_audience` (Reuse):** We should reuse the existing Program audience taxonomy to maintain platform consistency (e.g., Professionals, Students, Organizations).
- **`lohnour_industry` (Reuse):** Reuse existing industry taxonomy if coaching becomes vertical-specific.

## 10. Future Evolution Path
1. **Current:** Pages + Inquiry CTA (Status Quo)
2. **Next:** Structured Coaching Offers CPT (when specific packages emerge)
3. **Later:** Coach Profiles CPT (when multiple coaches are onboarded)
4. **Later:** Availability / Booking API Integration (when manual scheduling fails to scale)

## 11. Migration Strategy
If we migrate to structured data in the future:
- `/coaching/` remains a standard Page acting as the Archive/Hub.
- `/coaching/individual/` and `/coaching/group/` remain Pages acting as category landing pages.
- New CPT `lohnour_coaching` would exist at `/coaching/{offer-slug}/` (e.g., `/coaching/executive-ai/`).
- **No URL changes** to existing pages. No redirects required.

## 12. File-Level Impact Map
**No change now:**
- `page-coaching.php`
- `page-individual.php`
- `page-group.php`
- `functions.php`
- `inc/post-types.php`

**Future likely changes (if migrating):**
- `inc/post-types.php` (To register `lohnour_coaching`)
- `single-lohnour_coaching.php` (To render individual offers)

## 13. Risk Register
- **Premature CPT Creation:** Severity: Medium. Probability: High if unchecked. Mitigation: Stick to Pages until >3 distinct offers exist.
- **Mixing Informational & Transactional:** Severity: High. Probability: Low. Mitigation: Explicitly route all CTAs to `/contact/` until a real booking system is integrated.
- **Taxonomy Duplication:** Severity: Low. Probability: Medium. Mitigation: Reuse `lohnour_audience` and `lohnour_industry`.

## 14. Explicit Non-Goals
- We will NOT create a `coach` CPT.
- We will NOT create booking, availability, or payment engines.
- We will NOT refactor the current Phase 10.2 pages.

## 15. Phase 11.1 Recommendation
**MAINTAIN CURRENT ARCHITECTURE (NO ACTION REQUIRED)**
The forensic evidence strongly indicates that the current Page architecture is perfectly scaled for the current product reality. Introducing structured Coaching data (Phase 11.1) is NOT recommended at this time, as it would constitute premature architecture. We should wait for a business requirement that demands dynamic coaching packages before building the CPT.
