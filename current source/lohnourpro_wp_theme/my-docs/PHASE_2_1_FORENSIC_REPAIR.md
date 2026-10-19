# PHASE 2.1 FORENSIC REPAIR

## 1. Root Cause of Missing Theme Toggle
The theme toggle was unintentionally hidden during the Phase 2 component rollout because `display: none;` was added inline to the `<button class="theme-toggle">` element in `header.php`. Additionally, the previous JavaScript toggled a `.light-theme` class, whereas the new default styles established in Phase 2 assume a light theme natively on the `:root`.

## 2. How Theme Toggle was Restored
- Removed `display: none;` from the button in `header.php` and added `display: flex;`.
- Updated the inline JS snippet in `header.php` and the full script in `assets/js/navigation.js` to target a `.dark-theme` class instead of `.light-theme`.

## 3. Theme Architecture
- The default `:root` pseudo-class in `assets/css/base.css` now acts as the Light theme (White/Warm backgrounds, Graphite text).
- An `html.dark-theme` block in `base.css` was created to hold the overridden CSS custom properties for Dark mode (Dark background, Light text, softened accent).
- Logo switching logic in `assets/css/layout.css` was updated to display the dark logo (black text) by default, and switch to the light logo (white text) when `html.dark-theme` is active.
- State persists via `localStorage('theme')` which avoids flicker using an inline script in `header.php`.

## 4. Root Cause of Services 404
The architecture audit in Phase 1 correctly identified that `/services` was vacated and the CPT `lohnour_service` was migrated to `/programs`. However, a static WordPress Page named "Services" (slug: `services`) was never created to catch the route and serve as the hub. Thus, WordPress returned a 404.

## 5. How the Services WordPress Page was Created
Using a browser subagent, an administrator session was started and a standard WordPress Page titled "Services" was published via the WordPress Admin dashboard (`post-new.php?post_type=page`).

## 6. Services Template Used
A new template `page-services.php` was created within the child theme. WordPress automatically resolves the `/services/` slug to this template via the standard template hierarchy, maintaining a clean architecture without fake PHP routes.

## 7. Files Modified
- `header.php` (Unhide toggle, update inline JS for dark-theme)
- `assets/js/navigation.js` (Update theme switcher logic to toggle `dark-theme`)
- `assets/css/base.css` (Add `html.dark-theme` variables)
- `assets/css/layout.css` (Update logo switching logic)

## 8. Files Created
- `page-services.php` (Template for the Services Hub)
- `my-docs/PHASE_2_1_FORENSIC_REPAIR.md` (This file)

## 9. Database Objects Created/Modified
- **Page created:** "Services" (`post_type=page`, `post_name=services`).

## 10. Browser Tests
- **Theme Toggle:** Tested toggling between Light and Dark mode. Verified background colors, text colors, button colors, and logo assets seamlessly invert.
- **Persistence:** Navigated from `/services/` to `/programs/`. Verified the chosen theme remained active.

## 11. Mobile Tests
- Evaluated on a 390x844 viewport.
- Hamburger menu toggles open seamlessly.
- Theme toggle remains accessible and functional inside the header alongside the hamburger menu.

## 12. Remaining Known Issues
- None blocking. The architecture is fully stabilized and ready for Phase 3 (Homepage).
