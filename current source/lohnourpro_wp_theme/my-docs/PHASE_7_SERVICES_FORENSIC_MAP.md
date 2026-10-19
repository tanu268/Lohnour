# LOHNOUR — PHASE 7 SERVICES FORENSIC MAP

## Services Route
`/services/` (Handled by `page-services.php`)

## Corporate Route
`/services/corporate/` (New page created in WP; handled by `page-corporate.php` which will be newly implemented).

## Existing Pages
- **Home**: `/` (Handled by `front-page.php`)
- **Services**: `/services/` (Handled by `page-services.php`)
- **Corporate Training**: `/services/corporate/` (WP Page created, to be handled by `page-corporate.php`)
- **About Us**: `/about/`
- **Contact Us**: `/contact/`

## Existing Service Data
Services are presented as a routing hub. Based on the actual WP terms, the offerings map as follows:
- **AI Training**: Maps to `/programs/` (Entire catalog).
- **AI Upskilling**: Maps to `/programs/pathway/professional-ai-upskilling/`.
- **Faculty Development**: Maps to `/programs/pathway/faculty-development/`.
- **Corporate Training**: Maps to `/services/corporate/` (B2B landing page).

## Proposed Changes
1. **`page-services.php`**: Clean up the generic HTML and apply the strict Phase 4 `.lohnour-card` format. Ensure buttons point to the verified routes above instead of 404 dead-ends.
2. **`page-corporate.php`**: Create this template to serve the newly created Corporate Training page.
   - Use the approved visual system.
   - Add a strict B2B Hero.
   - Route the CTA explicitly to the existing `/contact/` flow.
   - Avoid creating false transactions, checkouts, booking forms, or fake testimonials.
