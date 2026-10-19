# Implementation Data Map

## CPTs & Taxonomies

### 1. Programs (Current: `lohnour_service`)
- **Data Source**: CPT `lohnour_service`
- **Taxonomies**: `lohnour_pathway`, `lohnour_audience`, `lohnour_industry`
- **Custom Meta**:
  - `_lohnour_card_excerpt` (Card Description)
  - `_lohnour_sidebar_benefits` (Sidebar features)
- **Target usage**: This CPT will remain the primary data source for **Programs**, but its public slug will change.

### 2. Pathways
- **Data Source**: Taxonomy `lohnour_pathway`
- **Custom Meta**:
  - `_lohnour_featured_programs` (IDs of featured programs)
  - `_lohnour_display_limit` (Number of programs to show)

### 3. Guides
- **Data Source**: CPT `user_guide`
- **Taxonomies**: `guide_sector`, `guide_tags`

### 4. Coaching (To Be Created)
- **Data Source**: HARDCODED / DEMO DATA (As per Master Plan Phase 10: "Do not invent real coaches. The design can use clearly marked demo data until the actual Coach data model exists.")

### 5. Services & Corporate Training (To Be Created)
- **Data Source**: Standard WordPress Pages (`page-services.php`, `page-corporate.php`). No new CPT required right now since Services are largely static offerings with a single CTA to inquire.

### 6. Forms / Inquiries
- **Data Source**: WordPress Editor shortcode injection (e.g., Contact Form 7 or WPForms). We will re-use the existing mechanism for `/contact`, `/services/corporate/inquiry`, and `/request-coaching`. No custom API will be invented.
