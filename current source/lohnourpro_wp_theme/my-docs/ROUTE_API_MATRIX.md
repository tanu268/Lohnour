# ROUTE & API MATRIX

| Route | Page Template | Data Needed | Existing API | Missing API | UI State |
|---|---|---|---|---|---|
| `/programs` | `archive-lohnour_service.php` | Program catalog | `lohnour_service` | None | Success |
| `/programs/:slug` | `single-lohnour_service.php` | Program Details, Cohorts | `lohnour_service` | Cohorts, Pricing | Success (Static fallback) |
| `/coaching/individual`| Missing | Coach Directory | None | `coach` CPT | Missing |
| `/coaches/:id` | Missing | Coach Profile, Availability | None | `coach` CPT, Booking | Missing |
| `/coaching/group` | Missing | Group Programs | None | `cohort` CPT | Missing |
| `/services/corporate` | Missing | Corporate details | None | Inquiry Form API | Missing |
| `/guides` | `archive-user_guide.php` | Guides catalog | `user_guide` | None | Success |
| `/guides/:slug` | `single-user_guide.php`| Guide details | `user_guide` | None | Success |
