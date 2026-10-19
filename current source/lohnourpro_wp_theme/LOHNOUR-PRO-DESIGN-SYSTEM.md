# LOHNOUR PRO DESIGN SYSTEM

## 1. Design Principles
- **No Fluff, Pure Action:** The design emphasizes practical implementation and professional clarity over excessive ornamentation.
- **Glassmorphism & Darkness:** Heavy use of dark backgrounds, subtle glassmorphic elements, and stark white accents.
- **Controlled Palette:** Monochromatic base with subtle tints. Avoid loud, chaotic colors.
- **Coherence:** All new elements must integrate visually and structurally with this system.

## 2. Typography
**Typefaces**
- Primary Heading Font: `"Outfit", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`
- Primary Body Font: `"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`

**Weights & Sizing**
- Headings are generally `500` to `700` weight, with `1.1` line height and `-0.02em` letter spacing.
- Body text is typically `400` weight with `1.6` line height.

## 3. Colors
**Base System (Dark Theme Default)**
- `--color-bg`: `#050505` (Deep black/gray)
- `--color-bg-alt`: `#0a0a0a`
- `--color-text`: `#f5f5f7` (Off white)
- `--color-text-muted`: `#a1a1a6`
- `--color-border`: `#1d1d1f`
- `--color-accent`: `#ffffff` (Stark white for contrast)
- `--color-primary`: `#3b82f6` (Subtle AI blue, if needed)
- `--color-glass`: `rgba(255, 255, 255, 0.03)`
- `--color-glass-border`: `rgba(255, 255, 255, 0.08)`
- `--color-header-bg`: `rgba(5, 5, 5, 0.8)`
- `--color-footer-bg`: `#000000`
- `--color-btn-primary-hover`: `#e5e5e5`

*(A light theme inversion exists in the CSS but dark is the primary identity).*

## 4. Spacing
- `--spacing-xs`: `0.5rem`
- `--spacing-sm`: `1rem`
- `--spacing-md`: `2rem`
- `--spacing-lg`: `4rem`
- `--spacing-xl`: `8rem`
- `--spacing-xxl`: `12rem`
- `--spacing-xxxl`: `16rem`
- `--header-clearance`: `140px`

## 5. Shape Language & Containers
- `--radius-sm`: `4px`
- `--radius-md`: `8px`
- `--radius-lg`: `16px`
- `--radius-full`: `9999px`
- **Containers:** Max width `1280px`, horizontal padding `--spacing-md`. `container-sm` class exists for narrower content.

## 6. Components
### Buttons
- `.btn`: Pill-shaped (`var(--radius-full)`), `0.75rem 1.5rem` padding, font weight `500`, subtle hover transform.
- `.btn-primary`: Stark white background, black text.
- `.btn-outline`: Transparent background, white border, subtle glass hover.

### Cards
- `.glass-card`: Gradient `rgba(255,255,255,0.05)` to `0.01`, blur `20px`, `1px` glass border, `16px` radius (`--radius-lg`).

### Badges
- `.hero-badge` / `.card-badge`: Pill-shaped, small uppercase text, subtle letter spacing, glass background.

### Forms
- `.lohnour-form-input`: Pill-shaped (`--radius-full`), glass styling, stark focus outline.
- `.lohnour-form-submit`: Inherits button styles.

## 7. Responsive Rules
- Below `768px`, spacing variables scale down (e.g., `--spacing-md` becomes `1.5rem`, `--spacing-xl` becomes `4rem`).
- Header converts to a hamburger menu with a fixed full-screen overlay.
- Grids shift from multicolumn to single-column stacking.

## 8. Reusable Components (Inventory)
1. **Header** (`header.php`): Sticky glass header with theme toggle and mobile menu. Do not change.
2. **Footer** (`footer.php`): Grid layout with newsletter widget and legal links. Do not change.
3. **Primary Button** (`.btn.btn-primary`): Used in Hero and CTAs.
4. **Secondary Button** (`.btn.btn-outline`): Used in Pathway Cards.
5. **Glass Card** (`.glass-card`): Generic container for content blocks (e.g., Testimonials, Pathways).
6. **Hero Badge** (`.hero-badge`): Breadcrumb/Badge component above page titles.
7. **Bento Grid** (`.bento-grid`): Multi-column varied-height grid layout (seen in About/Contact).

## 9. Existing Inconsistencies
- Minor hardcoded colors in some inline styles instead of using CSS custom properties (e.g., `#25D366` for WhatsApp, though intentional for branding).
- `theme.json` defines some properties (like `Black`, `White`, `Dark Charcoal`) that don't perfectly map to the `base.css` variable names.
- Inline styles are heavily used in templates (e.g., `taxonomy-lohnour_pathway.php`, `front-page.php`), which duplicates layout logic outside of `layout.css`.

## 10. Broken UX / Links Discovered
- **Explore Buttons (Pathways):** The `get_term_link()` for `lohnour_pathway` taxonomy terms correctly outputs URLs like `/pathway/corporate-ai-training/`, but Apache was returning a hard 404 because WordPress rewrite rules (`.htaccess`) were not configured or flushed after taxonomy registration.
- **Footer Legal Links:** "Privacy Policy" and "Terms & Conditions" point to `#`.
- **Top Navigation:** Some fallback pages (`/blog`, `/guides`, `/services`) might return 404 if the WordPress Pages haven't been created yet.

## 11. Explicit DO NOT CHANGE Rules
- Do NOT change the primary dark-themed monochromatic aesthetic.
- Do NOT replace the sticky glass header.
- Do NOT convert pill-shaped buttons to square buttons.
- Do NOT replace standard CSS with utility frameworks like Tailwind unless strictly isolated.
