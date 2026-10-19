# LOHNOUR — PHASE 3.2 HOMEPAGE FORENSIC DIFF

## 1. Original sections vs Phase 3.1 sections
**Original (front-page.php.old)**: 
- Hero Section (`.hero-section`)
- Trusted By / Social Proof Section (`.trusted-by-section`)
- What We Do Section (`.what-we-do-section`)
- Training Areas (Services) Section (`.training-areas-section`)
- Why Lohnour Pro Section (`.why-us-section`)
- Learning Experience Section (`.process-section`)
- Meet the Founder Section (`.founder-section`)
- Testimonials & Case Studies Section (`.proof-section`)
- Final CTA Section (`.cta-section`)

**Phase 3.1 modifications**:
While Phase 3.1 successfully fetched the original content, it wrapped them in completely new DOM hierarchies, such as:
- `<section style="padding: var(--spacing-xxl)...">`
- `<div style="display: grid; grid-template-columns: 1fr; gap... class="hero-grid">`
- `<div class="split-layout">` replaced by `<div style="display: grid..." class="why-us-grid">`
- `.testimonial-card` styling was manually injected as inline styles rather than using the original `.proof-section` architecture.

## 2. Original visual components lost/simplified in Phase 3.1
- **Hero**: The Phase 3.1 implementation replaced the original `.hero-section` > `.container` > `.hero-content` & `.hero-visual` flex layout with a CSS grid `style="display: grid..."`. It added borders, backgrounds, and padding inside the visual that altered the premium "borderless" feel of the agent orchestrator graphic.
- **Trusted By**: The logo carousel in Phase 3.1 stripped the CSS classes and injected an aggressive inline hover state (`onmouseover="this.style.filter=..."`) overriding the original CSS transitions.
- **Why Lohnour Pro (Workflow)**: The `split-layout` was replaced with a hardcoded CSS grid. Inline styles stripped the original margin/padding relationships dictated by `pages.css`.
- **Testimonials**: Phase 3.1 hardcoded inline styles (`min-width: 300px; flex: 0 0 100%`) instead of utilizing the original CSS that perfectly positioned the arrows and dots.
- **Founder**: Phase 3.1 overrode the `.split-visual` and `.split-layout` classes, hardcoding an `aspect-ratio: 4/5` and stripping the `.hero-badge`.

## 3. Original CSS
The original CSS still lives robustly in `assets/css/pages.css` and `assets/css/layout.css`:
- `.hero-section`, `.hero-content`, `.hero-visual`
- `.split-layout`, `.split-content`, `.split-visual`
- `.process-grid`, `.process-card`, `.process-marker`
- `.testimonial-wrapper`, `.testimonial-grid`, `.testimonial-card`
- `.founder-section`, `.founder-image-wrapper`

## 4. Original JavaScript
The original `.testimonial-grid` scrolling logic and `.logo-track` auto-scrolling logic were mostly preserved in Phase 3.1 but were modifying elements that had their CSS properties aggressively overridden by inline styles.

## 5. Asset Inventory
- `assets/images/logos/*`
- `assets/images/founder.webp/jpg/png`
- Agent graphic DOM nodes
- Workflow diagram DOM nodes

## Conclusion of the Diff
Phase 3.1 attempted to force the original content into a completely generic set of inline-styled grid components. This caused a loss of nuanced padding, layout responsiveness, and premium spacing defined in the theme's CSS. A true literal copy-paste of the original HTML classes was required for the original blocks.
