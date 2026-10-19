# PRODUCT MODEL

## Audience
- **Current State**: Handled natively via `lohnour_pathway` custom taxonomy attached to `lohnour_service` (Programs).
- **Supported Values**: Professional, Student, Faculty, Organizations.
- **Status**: **VERIFIED**

## Offering / Service & Program
- **Current State**: Represented via the `lohnour_service` Custom Post Type. This CPT represents the actual *Program* offering (e.g., AI in Academic Curricula).
- **Limitations**: The CPT slug is `service` and the route is `/services`. However, the architecture requires these to be treated as `Programs` located at `/programs`, freeing up `/services` for consulting/B2B.
- **Status**: **NEEDS URL REWRITE / CPT ALIGNMENT**

## Cohort
- **Current State**: Completely missing. There is no `cohort` CPT, nor any metadata on `lohnour_service` indicating start dates, capacity, schedule, or pricing.
- **Status**: **MISSING (DEPENDENCY UNMET)**

## Group Coaching
- **Current State**: Conceptually presented on the homepage, but lacks a backend model. Cannot be distinguished from standard Programs currently.
- **Status**: **MISSING (DEPENDENCY UNMET)**

## 1:1 Coaching
- **Current State**: Missing. No `coach` CPT, no booking functionality, no directory.
- **Status**: **MISSING (DEPENDENCY UNMET)**

## Corporate Training
- **Current State**: The generic `/contact/` form serves this purpose indirectly. There is no dedicated B2B Inquiry capability.
- **Status**: **PARTIAL (UI MISSING)**

## Transaction Models
1. **Enrollment (Cohort/Program)**: No backend support. Needs to remain "Book a Consultation" (Inquiry).
2. **Booking (Coach)**: No backend support. Must not build fake calendar UI.
3. **Inquiry (Corporate/General)**: Handled by `/contact/`.
