import sys
import os

PAGE_PATH = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(PAGE_PATH, "r", encoding="utf-8") as f:
    content = f.read()

# Verify BOM
raw = open(PAGE_PATH, "rb").read()
if raw.startswith(b'\xef\xbb\xbf'):
    print("WARNING: File has BOM! Stripping BOM.")
    content = content.lstrip('\ufeff')

# Define target block from line 1551 to line 1930
old_start = "/* Theme Switching Mechanism for Program Banner with Surgical Opacity Refinement */"
old_end = ".program-page .program-hero-grid {\n\t\tborder-radius: var(--radius-md, 8px);\n\t}\n}"

if old_start not in content or old_end not in content:
    print("ERROR: Target anchors not found in file!")
    sys.exit(1)

# Find start and end indices
start_idx = content.find(old_start)
end_idx = content.find(old_end) + len(old_end)

old_block = content[start_idx:end_idx]
print(f"Found old block: {len(old_block)} characters")

new_block = """/* Theme Switching Mechanism for Program Banner with Full Visual Clarity */
.program-page .program-hero-banner .banner-theme-dark {
\tdisplay: block;
\topacity: 1;
}
.program-page .program-hero-banner .banner-theme-light {
\tdisplay: none;
\topacity: 1;
}

html.light-theme .program-page .program-hero-banner .banner-theme-dark,
[data-theme="light"] .program-page .program-hero-banner .banner-theme-dark {
\tdisplay: none !important;
}
html.light-theme .program-page .program-hero-banner .banner-theme-light,
[data-theme="light"] .program-page .program-hero-banner .banner-theme-light {
\tdisplay: block !important;
\topacity: 1;
}

html.dark-theme .program-page .program-hero-banner .banner-theme-dark,
[data-theme="dark"] .program-page .program-hero-banner .banner-theme-dark {
\tdisplay: block !important;
\topacity: 1;
}
html.dark-theme .program-page .program-hero-banner .banner-theme-light,
[data-theme="dark"] .program-page .program-hero-banner .banner-theme-light {
\tdisplay: none !important;
}

/* Meta chip shared styling */
.program-page .meta-chip {
\tdisplay: inline-flex;
\talign-items: center;
\tgap: 7px;
\tpadding: 8px 14px;
\tfont-size: 0.825rem;
\tcolor: var(--color-text);
\tbackground: var(--color-bg-surface);
\tborder: 1px solid var(--color-border);
\tborder-radius: var(--radius-md);
\tbackdrop-filter: blur(8px);
\t-webkit-backdrop-filter: blur(8px);
\ttransition: border-color var(--transition-fast), transform var(--transition-fast);
}

.program-page .meta-chip:hover {
\tborder-color: var(--color-accent);
\ttransform: translateY(-1px);
}

.program-page .meta-chip svg {
\tcolor: var(--color-accent);
\tflex-shrink: 0;
}

.program-page .meta-chip strong {
\tcolor: var(--color-accent);
\tfont-weight: 700;
}

/* Action buttons shared styling */
.program-page .hero-btn-primary,
.program-page .hero-btn-secondary {
\tdisplay: inline-flex;
\talign-items: center;
\tjustify-content: center;
\ttext-decoration: none;
\ttransition: transform var(--transition-fast), box-shadow var(--transition-fast), background-color var(--transition-fast), border-color var(--transition-fast);
}

.program-page .hero-btn-primary:focus-visible,
.program-page .hero-btn-secondary:focus-visible {
\toutline: 2px solid var(--color-accent);
\toutline-offset: 3px;
}

/* ==========================================================================
   DESKTOP (min-width: 901px): Dual-Panel Hero Composition
   LEFT: Clean structured content panel (title, desc, meta chips, CTAs)
   RIGHT: Dedicated featured visual panel (people, laptop, AI diagrams)
   ========================================================================== */
@media (min-width: 901px) {
\t.program-page .program-hero-grid {
\t\tdisplay: grid;
\t\tgrid-template-columns: 1.08fr 0.92fr;
\t\talign-items: stretch;
\t\tmin-height: 480px;
\t}

\t/* Disable background scrim - content and image are separate panels */
\t.program-page .program-hero-grid::after {
\t\tdisplay: none;
\t}

\t/* Text content on LEFT panel */
\t.program-page .program-hero-content {
\t\tposition: relative;
\t\tz-index: 2;
\t\tpadding: 44px 36px 44px 44px;
\t\tdisplay: flex;
\t\tflex-direction: column;
\t\tjustify-content: center;
\t\tmin-height: 480px;
\t}

\t.program-page .program-hero-title {
\t\tfont-family: var(--font-heading);
\t\tfont-size: clamp(2rem, 3.2vw, 2.75rem);
\t\tfont-weight: 700;
\t\tline-height: 1.15;
\t\tletter-spacing: -0.025em;
\t\tcolor: var(--color-text);
\t\tmargin: 0 0 14px 0;
\t\ttext-wrap: balance;
\t}

\t.program-page .program-hero-desc {
\t\tfont-size: clamp(0.95rem, 1.25vw, 1.075rem);
\t\tline-height: 1.55;
\t\tcolor: var(--color-text-secondary);
\t\tmax-width: 520px;
\t\tmargin: 0 0 20px 0;
\t}

\t.program-page .program-hero-meta {
\t\tdisplay: flex;
\t\tflex-wrap: wrap;
\t\talign-items: center;
\t\tgap: 8px;
\t\tmargin-bottom: 24px;
\t\tmax-width: 520px;
\t}

\t.program-page .meta-chip {
\t\tpadding: 6px 12px;
\t\tfont-size: 0.775rem;
\t}

\t.program-page .program-hero-actions {
\t\tdisplay: flex;
\t\talign-items: center;
\t\tgap: 16px;
\t\tflex-wrap: wrap;
\t\tmargin-bottom: 0;
\t}

\t.program-page .hero-btn-primary,
\t.program-page .hero-btn-secondary {
\t\tpadding: 11px 18px;
\t\tfont-size: 0.9rem;
\t}

\t/* Dedicated featured visual panel on RIGHT */
\t.program-page .program-hero-banner {
\t\tposition: relative;
\t\tinset: auto;
\t\twidth: 100%;
\t\theight: 100%;
\t\tmin-height: 480px;
\t\tmargin: 0;
\t\tborder: none;
\t\tborder-left: 1px solid var(--color-border-subtle);
\t\tborder-radius: 0;
\t\tbox-shadow: none;
\t\tbackground: transparent;
\t\tz-index: 1;
\t\toverflow: hidden;
\t}

\t.program-page .program-hero-banner img {
\t\twidth: 100%;
\t\theight: 100%;
\t\tdisplay: block;
\t\tobject-fit: cover;
\t\tobject-position: 68% center;
\t\tborder-radius: 0;
\t}
}

/* Medium Desktop / Small Landscape (1050px to 901px) */
@media (max-width: 1050px) and (min-width: 901px) {
\t.program-page .program-hero-grid {
\t\tgrid-template-columns: 1.12fr 0.88fr;
\t\tmin-height: 460px;
\t}
\t.program-page .program-hero-content {
\t\tpadding: 32px 24px 32px 32px;
\t\tmin-height: 460px;
\t}
\t.program-page .program-hero-title {
\t\tfont-size: 2rem;
\t}
\t.program-page .program-hero-desc {
\t\tfont-size: 0.95rem;
\t\tmax-width: 440px;
\t\tmargin-bottom: 16px;
\t}
\t.program-page .program-hero-meta {
\t\tmax-width: 440px;
\t\tgap: 6px;
\t\tmargin-bottom: 20px;
\t}
\t.program-page .program-hero-actions {
\t\tdisplay: flex;
\t\tflex-direction: column;
\t\talign-items: flex-start;
\t\tgap: 14px;
\t}
\t.program-page .hero-btn-primary,
\t.program-page .hero-btn-secondary {
\t\twidth: 100%;
\t\tmax-width: 290px;
\t}
\t.program-page .program-hero-banner {
\t\tmin-height: 460px;
\t}
\t.program-page .program-hero-banner img {
\t\tobject-position: 68% center;
\t}
}

/* ==========================================================================
   TABLET & MOBILE (max-width: 900px): Natural stacked composition
   Content sits above the banner image without overlapping people or laptop
   ========================================================================== */
@media (max-width: 900px) {
\t.program-page .program-hero-grid {
\t\tdisplay: flex;
\t\tflex-direction: column;
\t\tgap: 0;
\t\tmin-height: auto;
\t}

\t.program-page .program-hero-grid::after {
\t\tdisplay: none;
\t}

\t.program-page .program-hero-content {
\t\tposition: relative;
\t\tmax-width: 100%;
\t\tmin-height: auto;
\t\tpadding: 32px 28px 24px 28px;
\t\tz-index: 2;
\t}

\t.program-page .program-hero-title {
\t\tfont-family: var(--font-heading);
\t\tfont-size: clamp(1.85rem, 4vw, 2.35rem);
\t\tfont-weight: 700;
\t\tline-height: 1.2;
\t\tletter-spacing: -0.025em;
\t\tcolor: var(--color-text);
\t\tmargin: 0 0 10px 0;
\t\ttext-wrap: balance;
\t}

\t.program-page .program-hero-desc {
\t\tfont-size: 1.05rem;
\t\tline-height: 1.55;
\t\tcolor: var(--color-text-secondary);
\t\tmax-width: 680px;
\t\tmargin: 0 0 16px 0;
\t}

\t.program-page .program-hero-meta {
\t\tdisplay: flex;
\t\tflex-wrap: wrap;
\t\talign-items: center;
\t\tgap: 8px;
\t\tmargin-bottom: 22px;
\t}

\t.program-page .meta-chip {
\t\tpadding: 6px 12px;
\t\tfont-size: 0.8rem;
\t}

\t.program-page .program-hero-actions {
\t\tdisplay: flex;
\t\talign-items: center;
\t\tgap: 16px;
\t\tflex-wrap: wrap;
\t\tmargin-bottom: 0;
\t}

\t.program-page .program-hero-banner {
\t\tposition: relative;
\t\tinset: auto;
\t\twidth: 100%;
\t\theight: auto;
\t\tmargin: 0;
\t\tborder: none;
\t\tborder-top: 1px solid var(--color-border-subtle);
\t\tborder-radius: 0;
\t\tbox-shadow: none;
\t\tbackground: transparent;
\t\toverflow: hidden;
\t}

\t.program-page .program-hero-banner img {
\t\twidth: 100%;
\t\theight: auto;
\t\taspect-ratio: 2 / 1;
\t\tdisplay: block;
\t\tobject-fit: cover;
\t\tobject-position: center;
\t\tborder-radius: 0;
\t}
}

/* Mobile View (640px and below) */
@media (max-width: 640px) {
\t.program-page .program-hero {
\t\tpadding: var(--spacing-xs, 8px) 0 var(--spacing-md, 32px) 0;
\t\ttext-align: center;
\t}

\t.program-page .program-hero-breadcrumb {
\t\tmargin-bottom: var(--spacing-xs);
\t}

\t.program-page .program-hero-breadcrumb .breadcrumb-list {
\t\tjustify-content: center;
\t\tfont-size: 0.775rem;
\t\tgap: 6px;
\t}

\t.program-page .program-hero-content {
\t\tdisplay: flex;
\t\tflex-direction: column;
\t\talign-items: center;
\t\ttext-align: center;
\t\tpadding: 24px 18px 20px 18px;
\t}

\t.program-page .program-hero-title {
\t\tfont-size: clamp(1.75rem, 7vw, 2.25rem);
\t\tline-height: 1.2;
\t\ttext-align: center;
\t\tmargin-bottom: 8px;
\t}

\t.program-page .program-hero-desc {
\t\tfont-size: 0.95rem;
\t\tline-height: 1.55;
\t\ttext-align: center;
\t\tmargin-left: auto;
\t\tmargin-right: auto;
\t\tmargin-bottom: 14px;
\t}

\t.program-page .program-hero-meta {
\t\tjustify-content: center;
\t\tgap: 6px;
\t\tmargin-bottom: 20px;
\t\twidth: 100%;
\t}

\t.program-page .meta-chip {
\t\tfont-size: 0.775rem;
\t\tpadding: 6px 10px;
\t}

\t.program-page .program-hero-actions {
\t\tdisplay: flex;
\t\tflex-direction: column;
\t\talign-items: center;
\t\twidth: 100%;
\t\tgap: 14px;
\t}

\t.program-page .hero-btn-primary,
\t.program-page .hero-btn-secondary {
\t\twidth: 100%;
\t\tmax-width: 340px;
\t\ttext-align: center;
\t\tjustify-content: center;
\t}

\t.program-page .program-hero-grid {
\t\tborder-radius: var(--radius-md, 8px);
\t}
}"""

updated = content[:start_idx] + new_block + content[end_idx:]

with open(PAGE_PATH, "w", encoding="utf-8", newline="\n") as f:
    f.write(updated)

print("SUCCESS: Refinement applied cleanly without BOM.")
