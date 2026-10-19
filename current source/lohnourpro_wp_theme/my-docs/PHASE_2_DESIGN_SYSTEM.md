# PHASE 2: DESIGN SYSTEM & GLOBAL SHELL

## 1. Design Tokens
The design tokens are centralized in `assets/css/base.css`.
This file serves as the definitive source of truth for:
- **Color System:** Light editorial theme (White/Warm/Surface backgrounds, Graphite/Secondary text) with a sophisticated slate/indigo accent (`#0f172a`).
- **Typography:** Display uses `SF Pro Display` (or system fallback), Body uses `Inter`.
- **Spacing:** `xs` (8px) through `xxl` (192px).
- **Border Radius:** Restrained editorial radius (4px, 8px, 12px, 9999px).
- **Shadows:** Subtle neutral shadows, completely removing the previous heavy glassmorphism style.

## 2. Components
The component primitives are centralized in `assets/css/components.css`.
This file establishes:
- **Buttons:** `.btn`, `.btn-primary`, `.btn-secondary`, `.btn-outline` (all with pill-shape by default but capable of overrides).
- **Links:** `.link-arrow` (with hover micro-interactions).
- **Cards:** Base `.lohnour-card` primitive with extensions for `.card-program`, `.card-coach`, `.card-guide`, `.card-service` using different aspect ratios.
- **Badges:** `.badge`, `.badge-primary`, `.badge-outline`.
- **Breadcrumbs:** `.breadcrumbs` container with appropriate separator colors.
- **Section Headings:** `.section-header`, `.section-eyebrow`, `.section-title`, `.section-desc`.
- **Forms:** `.form-group`, `.form-label`, `.form-control` with focus, error, and helper states.
- **Search & Filters:** `.search-primitive`, `.filter-group`, `.filter-btn`.
- **State Primitives:** Empty/Error/Success states via `.state-container` and loading state via `.skeleton`.

## 3. Global Shell
- **Header:** Implemented in `header.php`. Modified fallback navigation to point strictly to Phase 1 architecture routes (`/programs/`, `/coaching/`, `/services/`, `/guides/`, `/about/`, `/contact/`). Header CTA points to `/coaching/individual/`.
- **Footer:** Implemented in `footer.php`. Navigation matches the header with the same strict routing.
- **Container:** Handled by `.container` in `assets/css/layout.css` providing a `1280px` max-width.

## 4. Responsive System
- Breakpoints are handled globally in `layout.css` and specifically inside `base.css` with a `@media (max-width: 768px)` block that compresses the spacing scale (`--spacing-md`, `--spacing-lg`, `--spacing-xl`) for mobile devices automatically.

## 5. Reuse Rules
- **DO NOT** write custom inline styles or bespoke CSS classes for buttons, cards, badges, or typography on new pages.
- Always inherit from `.lohnour-card`, `.btn`, `.form-control`, etc.
- Always use the CSS variables for colors (e.g. `var(--color-bg-surface)` instead of `#f5f5f7`).
