# Implementation Risk Register

## Identified Risks & Mitigations

1. **Routing Conflict for `/services`**
   - **Risk**: The current site uses `/services` for the Programs catalog. When we change Programs to `/programs`, the `/services` route will 404 unless we immediately create the new Services Hub page. Existing backlinks might break.
   - **Mitigation**: Update CPT rewrite rules. Create the new `/services` page immediately in WordPress. Implement 301 redirects if needed for SEO.

2. **Form Plugin Dependency**
   - **Risk**: The master plan requires forms with validation, error, and success states for `/contact`, `/request-coaching`, and `/services/corporate/inquiry`.
   - **Mitigation**: Verify the exact form plugin currently in use. If none exists that supports these states via shortcode, propose the simplest standard solution (e.g., CF7) without inventing a custom API.

3. **Data Loss During Rewrite Modification**
   - **Risk**: Flushing rewrite rules can temporarily break site links.
   - **Mitigation**: Ensure `flush_rewrite_rules()` is run exactly once after updating `inc/post-types.php`.

4. **Coaching Demo Data**
   - **Risk**: Demo data might be confused for real data or become permanent.
   - **Mitigation**: Explicitly label demo sections. Hardcode them in templates rather than polluting the WP Database with fake CPTs.

5. **Responsive Menu Collapse**
   - **Risk**: Changing the header structure might break existing JS for the mobile menu.
   - **Mitigation**: Audit `assets/js/navigation.js` before making sweeping DOM changes to `header.php`.
