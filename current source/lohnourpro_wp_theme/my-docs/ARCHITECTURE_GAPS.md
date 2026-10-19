# ARCHITECTURE GAPS

## 1. Information Architecture vs Terminology Conflict
- **Gap**: The target architecture expects `Programs` at `/programs` and `Services` at `/services` (for corporate consulting). Currently, the codebase uses `lohnour_service` CPT for Programs, and they live at `/services`.
- **Impact**: Routing and breadcrumbs are semantically incorrect.

## 2. Missing CPTs for Coaching Model
- **Gap**: The architecture introduces `Coaches`, `1:1 Coaching`, and `Group Coaching`.
- **Impact**: We cannot build Coach Directories or Coach Profiles without a `coach` CPT. Any UI built for this would be filled with fabricated, hardcoded data.

## 3. Missing Transactional Backend
- **Gap**: The architecture describes `Enrollment`, `Booking`, and `Checkout`. 
- **Impact**: WordPress does not currently have WooCommerce or any booking plugin active that supports this. The only valid transaction type right now is **Inquiry** via `/contact`.

## 4. Guide Semantics
- **Gap**: Guides (`user_guide` CPT) exist but are purely editorial. The architecture warns against treating them as paid products without evidence.
- **Validation**: Confirmed they are just editorial resources.

## Conclusion & Implementation Strategy
1. **Priority 1**: Rewrite the `lohnour_service` CPT slug to `/programs` to align with the Program Experience architecture.
2. **Priority 2**: Build out `/services/corporate` as a purely informational/inquiry route.
3. **Priority 3**: Do **NOT** build Enrollment or Booking UI, as the backend dependencies (Cohorts, Pricing, Calendars) are completely missing. Use Inquiry CTAs ("Book a Consultation" / "Request Coaching") instead.
