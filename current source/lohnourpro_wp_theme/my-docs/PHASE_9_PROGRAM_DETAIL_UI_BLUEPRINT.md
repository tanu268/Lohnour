# PHASE 9 — PROGRAM DETAIL UI BLUEPRINT

## 1. Recommended Final Page Structure
Based on the forensic audit and existing data constraints, the following structure represents the maximum premium experience achievable without fabricating data or backend logic:

1. **Global Header** (Protected)
2. **Breadcrumb / Taxonomy Nav**
3. **Program Hero** (Title, Excerpt, Primary CTA)
4. **Main Layout (2-Column Desktop)**
   - **Main Content (Left):**
     - Featured Image
     - Program Overview (Rich text)
     - Learning Outcomes (Elevated UI for Benefits)
   - **Sticky Sidebar (Right):**
     - Target Audience & Topics
     - Action CTA (Inquiry Card)
5. **Related Programs** (Cross-sell grid)
6. **Global Footer** (Protected)

*(Note: Sections like Curriculum, Projects, FAQs, and Snapshot statistics are omitted because the underlying data model does not currently support them.)*

## 2. Page Wireframe
```text
┌─────────────────────────────────────────────────────────────┐
│ GLOBAL HEADER                                               │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Breadcrumb (Home / Programs / Title)                        │
│                                                             │
│ PROGRAM HERO                                                │
│                                                             │
│ Pathway Badge                                               │
│ Title                                                       │
│ Excerpt                                                     │
│ [ Book a Consultation ]                                     │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ┌───────────────────────────────────────┐ ┌───────────────┐ │
│ │ FEATURED IMAGE                        │ │ STICKY SIDEBAR│ │
│ └───────────────────────────────────────┘ │               │ │
│                                           │ Who this is for │
│ PROGRAM OVERVIEW                          │ [Audience]    │ │
│ (Content from the_content)                │               │ │
│                                           │ Topics        │ │
│                                           │ [Industry]    │ │
│                                           │               │ │
│ WHAT YOU'LL LEARN                         │ Ready to begin? │
│ - Benefit 1           - Benefit 3         │ [ Contact Us ]│ │
│ - Benefit 2           - Benefit 4         │               │ │
│                                           └───────────────┘ │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ EXPLORE RELATED PROGRAMS                                    │
│ [ Program Card ] [ Program Card ] [ Program Card ]          │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

## 3. Desktop Blueprint
**Layout**:
- Container: `1300px` max-width.
- Grid: `2fr 1fr` split (Main Content / Sidebar).
- Gap: `var(--spacing-xl)`.

**Components**:
- **Hero**: Align left. Max-width text constraint (`900px` for title, `760px` for excerpt) to maintain readability.
- **Image**: Spans full width of the left column. Border radius `var(--radius-lg)`.
- **Outcomes**: Displayed as a 2-column grid of list items with custom icon bullet points.
- **Sidebar**: Sticky positioning `top: calc(var(--header-clearance) + var(--spacing-md))`. Designed as a premium glass-card or bordered surface.

## 4. Tablet Blueprint
**Layout**:
- Breakpoint: `< 992px`
- Grid: Collapses to `1fr` (single column).

**Adjustments**:
- Sidebar drops below the "What You'll Learn" section.
- Hero text constraints adjust to `100%`.
- Related Programs grid shifts from 3 columns to 2 columns.

## 5. Mobile Blueprint
**Layout**:
- Breakpoint: `< 768px`
- Grid: `1fr` (single column).

**Adjustments**:
- Typography scales down (e.g., `h1` from `3rem` to `2.25rem`).
- Padding reduces (e.g., `var(--spacing-xl)` to `var(--spacing-lg)`).
- "What You'll Learn" drops to a 1-column list.
- Related Programs grid shifts to 1 column.

## 6. Components Required
- **Breadcrumb Navigation**: Existing.
- **Pathway Badge**: Existing token.
- **Content Prose Block**: Existing `.prose-content` class.
- **Outcome List**: Requires UI enhancement (icon + text flex layout).
- **Sticky Inquiry Card**: Requires structural polish (border, background, typography refinement) to match the reference's premium feel.
- **Program Card**: Existing component from Programs Catalog.

## 7. CTA Strategy
**Current Constraint**: No booking/payment engine.
**Strategy**: The page is an information and lead-generation tool.
**Primary CTA Text**: "Book a Consultation" or "Discuss This Program".
**Destination**: `/contact/`.
**Placement**:
1. Hero section (for high-intent returning users).
2. Sticky Sidebar (always accessible during scrolling).

## 8. Data Gaps & Future Backend Requirements
To achieve the full reference UI in the future, the following backend structures must be built:
1. **Curriculum Engine**: A hierarchical model (modules/lessons) attached to Programs, distinct from Guides.
2. **Program Snapshot Meta**: Custom fields for Duration, Price, Skill Level, Format, and Delivery Method.
3. **Relationship Fields**: Linking Testimonials and Case Studies specifically to individual Programs.
4. **FAQ Module**: A repeater field for FAQs.

## 9. Risks
- **Over-designing**: Attempting to force a complex UI (like accordions for curriculum) when the data is just unstructured HTML in `the_content()`.
- **Theme Consistency**: The new styling must use existing LOHNOUR design tokens (vars) to ensure Dark Mode compatibility.

## 10. Protected Files
Do NOT modify:
- `single-lohnour_service.php` (in Phase 9)
- `inc/post-types.php`
- `inc/taxonomies.php`
- `archive-lohnour_service.php`
- `functions.php`
- All Guide and Homepage templates.
