# LOHNOUR PRO — PHASE 10
# COACHING FORENSIC MAP

## A. Existing Coaching Data
- None. There are no existing WordPress posts, pages, or options dedicated to storing structured coaching data (e.g., packages, prices, availability).
- There is only visual/textual mention of Coaching on the `front-page.php` and `header.php`.

## B. Existing Coach Data
- None. There is no `coach` entity, custom post type, or user role defined in the system.

## C. Existing CPTs
- `lohnour_service` (Programs)
- `lohnour_workshop` (Workshops)
- `lohnour_testimonial` (Testimonials)
- `lohnour_case_study` (Case Studies)
- `user_guide` (Guides)
- **Result**: No `coach` or `coaching` CPTs.

## D. Existing Taxonomies
- `lohnour_pathway`, `lohnour_audience`, `lohnour_industry` (for Programs)
- `guide_category` (for Guides)
- **Result**: No taxonomies related to coaching.

## E. Existing Meta Fields
- `_lohnour_card_excerpt`, `_lohnour_sidebar_benefits` (for Programs)
- **Result**: No meta fields related to coaching.

## F. Existing Routes
- The `header.php` defines static fallback links to `/coaching/` and `/coaching/individual/`.
- The `front-page.php` defines links to `/coaching/`, `/coaching/individual/`, and `/coaching/group/`.
- There are no template files handling these routes, so they will currently result in a 404 unless standard WordPress Pages with these slugs are created.

## G. Existing Templates
- No coaching templates exist (e.g., no `page-coaching.php`, `archive-coach.php`, etc.).

## H. Existing CTAs
- "Find a Coach" -> `/coaching/individual/`
- "Explore Group Coaching" -> `/coaching/group/`
- Inquiry actions -> `/contact/`

## I. Existing Transactional Infrastructure
- None. No booking, calendar, availability, checkout, or payment integrations exist in the repository.

## J. Missing Backend Capabilities
- Coach accounts / entities
- Coach availability / calendars
- Group coaching cohorts / seat management
- Pricing / transactional systems
- Booking confirmation / enrollment

## K. Recommended Coaching Architecture
- Since there is no underlying relational data model for coaches or coaching packages, the architecture should be constructed using standard **WordPress Pages** combined with custom page templates.
- **Routes to create as Pages:**
  - `/coaching/` (Coaching Hub)
  - `/coaching/individual/`
  - `/coaching/group/`
- **Routes to skip (Unsupported):**
  - `/coaches/` (Coach Directory)
  - `/coaches/{id}/` (Coach Profile)

## L. Data Availability Matrix

| Feature | Exists? | Can Build UI? | Source / Fallback |
|---|---|---|---|
| Coach Profile | NO | NO | Cannot build. Requires backend support. |
| Coach Bio | NO | NO | Cannot build. Requires backend support. |
| Coach Expertise | NO | NO | Cannot build. Requires backend support. |
| Pricing | NO | NO | Use "Request Info" CTA instead. |
| Availability | NO | NO | Use "Request Info" CTA instead. |
| Booking | NO | NO | Route to `/contact/`. |
| Payment | NO | NO | Route to `/contact/`. |
| Coaching Hub | NO | YES | Standard WP Page + Custom Template |
| Indiv. Coaching | NO | YES | Standard WP Page + Custom Template |
| Group Coaching | NO | YES | Standard WP Page + Custom Template |
