# Implementation Route Map

## Current State vs Target State

| Content Type | Current Route (As Is) | Target Route (To Be) | Status | Gap Notes |
|---|---|---|---|---|
| **Homepage** | `/` | `/` | Aligned | Needs UI update to match Part 1 design. |
| **Programs Catalog** | `/services` | `/programs` | Misaligned | CPT `lohnour_service` rewrite rule needs update. |
| **Program Detail** | `/services/{slug}` | `/programs/{slug}` | Misaligned | Rewrite rule update required. |
| **Audience Pathway** | `/pathway/{slug}` | `/programs/pathway/{slug}` | Misaligned | Taxonomy rewrite rule needs update. |
| **Guides Catalog** | `/guides` | `/guides` | Aligned | Validate UI matches editorial design. |
| **Guide Detail** | `/guides/{slug}` | `/guides/{slug}` | Aligned | Validate UI matches editorial design. |
| **Services Hub** | N/A (Conflicts with Programs) | `/services` | Missing | Needs a dedicated WordPress Page or template. |
| **Corporate Training** | N/A | `/services/corporate` | Missing | Needs WordPress Page. |
| **Corporate Inquiry** | N/A | `/services/corporate/inquiry`| Missing | Needs WordPress Page + Form. |
| **Coaching Hub** | N/A | `/coaching` | Missing | Needs WordPress Page. |
| **Indiv. Coaching** | N/A | `/coaching/individual` | Missing | Needs WordPress Page + Demo Data. |
| **Coach Profile** | N/A | `/coaches/{id}` | Missing | Needs WordPress Page + Demo Data. |
| **Request Coaching** | N/A | `/request-coaching` | Missing | Needs WordPress Page + Form. |
| **Group Coaching** | N/A | `/coaching/group` | Missing | Needs WordPress Page. |
| **About** | `/about` | `/about` | Aligned | Needs UI update to match approved design. |
| **Contact** | `/contact` | `/contact` | Aligned | General contact, UI update needed. |

## Required Action (Phase 1)
Modify `inc/post-types.php` to change `lohnour_service` slug from `services` to `programs`.
Modify `inc/taxonomies.php` to change `lohnour_pathway` slug from `pathway` to `programs/pathway`.
Ensure redirect rules flush and existing internal links are updated.
