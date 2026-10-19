# CTA AUDIT

| CTA | Current Destination | Intended Destination | Status |
|---|---|---|---|
| Explore Programs | `/services/` | `/programs/` | **NEEDS REVISION** (Terminology mismatch) |
| Book a Consultation | `/contact/` | `/contact/` or `/services/corporate` | **VALID** (For Inquiry Transaction) |
| Explore [Audience] | `/services/pathway/:slug`| `/programs/pathway/:slug` | **NEEDS REVISION** (Terminology mismatch) |
| View Program | `/services/:slug` | `/programs/:slug` | **NEEDS REVISION** (Terminology mismatch) |
| Explore All Guides | `/guides/` | `/guides/` | **CORRECT** |
| Read Guide | `/guides/:slug` | `/guides/:slug` | **CORRECT** |

## Missing CTAs
- **Find a Coach**: No destination exists (`/coaches` missing).
- **Enroll / Join Cohort**: No destination exists (No enrollment flow/cohorts).
- **Book Session**: No destination exists (No booking flow).
- **Submit Requirement**: Currently handled via generic `/contact/`. Needs dedicated `/services/corporate`.
