# HOMEPAGE FORENSIC MAP

## Hero Section
- **PURPOSE:** Discovery and Primary Navigation
- **TARGET USER:** All Users
- **CURRENT OBJECT:** Static HTML
- **CURRENT CTA:** "Explore Programs", "Book a Consultation"
- **CURRENT ROUTE:** `/services`, `/contact`
- **CURRENT DATA SOURCE:** Static
- **CURRENT API:** None
- **INTENDED ARCHITECTURE:** Navigate to `/programs` instead of `/services`.
- **REQUIRED CHANGE:** Update URL to `/programs` when the route is migrated.

## Who We Serve
- **PURPOSE:** Audience segmentation
- **TARGET USER:** Professionals, Students, Faculty, Organizations
- **CURRENT OBJECT:** `lohnour_pathway` taxonomy terms
- **CURRENT CTA:** "Explore [Audience]"
- **CURRENT ROUTE:** `/services/pathway/:slug`
- **CURRENT DATA SOURCE:** `lohnour_pathway` taxonomy
- **CURRENT API:** WP Taxonomy Loop
- **INTENDED ARCHITECTURE:** Correctly maps to Audience conceptually.
- **REQUIRED CHANGE:** None functionally, but routes might change if `/services` moves to `/programs`.

## How You Learn
- **PURPOSE:** Explains Delivery Formats (Training, Individual Coaching, Group Coaching)
- **TARGET USER:** All Users
- **CURRENT OBJECT:** Static HTML Cards
- **CURRENT CTA:** None
- **CURRENT ROUTE:** None
- **CURRENT DATA SOURCE:** Static HTML
- **CURRENT API:** None
- **INTENDED ARCHITECTURE:** Needs to link to `/programs`, `/coaching/individual`, `/coaching/group`.
- **REQUIRED CHANGE:** Add appropriate CTAs pointing to the required landing pages (once those pages exist).

## Featured Programs
- **PURPOSE:** Highlight available training programs
- **TARGET USER:** Individuals/Organizations looking to upskill
- **CURRENT OBJECT:** `lohnour_service` CPT posts
- **CURRENT CTA:** "View Program" (Card), "Explore All Programs"
- **CURRENT ROUTE:** `/services/:slug`, `/services`
- **CURRENT DATA SOURCE:** `lohnour_service` CPT
- **CURRENT API:** WP_Query
- **INTENDED ARCHITECTURE:** Programs.
- **REQUIRED CHANGE:** URL restructuring from `/services` to `/programs` to free up `/services` for consulting.

## The Learning Experience & Trainer Enablement & Founder
- **PURPOSE:** Trust signals and methodological explanation
- **TARGET USER:** All Users
- **CURRENT OBJECT:** Static HTML
- **CURRENT CTA:** None
- **CURRENT ROUTE:** None
- **CURRENT DATA SOURCE:** Static
- **CURRENT API:** None
- **INTENDED ARCHITECTURE:** Brand/Marketing logic.
- **REQUIRED CHANGE:** None.

## Latest Guides
- **PURPOSE:** Highlight editorial content/resources
- **TARGET USER:** Learners/Prospects
- **CURRENT OBJECT:** `user_guide` CPT
- **CURRENT CTA:** "Read Guide", "Explore All Guides"
- **CURRENT ROUTE:** `/guides/:slug`, `/guides`
- **CURRENT DATA SOURCE:** `user_guide` CPT
- **CURRENT API:** WP_Query
- **INTENDED ARCHITECTURE:** Content/Resources.
- **REQUIRED CHANGE:** Validate Guide definition (currently implemented purely as informational content, no checkout).
