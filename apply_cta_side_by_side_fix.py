import sys
import os

PAGE_PATH = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(PAGE_PATH, "r", encoding="utf-8") as f:
    content = f.read()

# Define start and end anchors for lines 1633 to 1771
start_anchor = "@media (min-width: 901px) {\n\t.program-page .program-hero-grid {"
end_anchor = "\t.program-page .program-hero-banner img {\n\t\tobject-position: 68% center;\n\t}\n}"

if start_anchor not in content:
    print("ERROR: start_anchor not found!")
    sys.exit(1)

if end_anchor not in content:
    print("ERROR: end_anchor not found!")
    sys.exit(1)

start_idx = content.find(start_anchor)
end_idx = content.find(end_anchor) + len(end_anchor)

old_block = content[start_idx:end_idx]
print(f"Found old block: {len(old_block)} characters")

new_block = """@media (min-width: 901px) {
\t.program-page .program-hero-grid {
\t\tdisplay: grid;
\t\tgrid-template-columns: 1.16fr 0.84fr;
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
\t\tpadding: 44px 32px 44px 44px;
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
\t\tmax-width: 540px;
\t\tmargin: 0 0 20px 0;
\t}

\t.program-page .program-hero-meta {
\t\tdisplay: flex;
\t\tflex-wrap: wrap;
\t\talign-items: center;
\t\tgap: 8px;
\t\tmargin-bottom: 24px;
\t\tmax-width: 540px;
\t}

\t.program-page .meta-chip {
\t\tpadding: 6px 12px;
\t\tfont-size: 0.775rem;
\t}

\t/* CTA Buttons strictly SIDE-BY-SIDE horizontally on desktop */
\t.program-page .program-hero-actions {
\t\tdisplay: flex;
\t\tflex-direction: row;
\t\talign-items: center;
\t\tgap: 16px;
\t\tflex-wrap: wrap;
\t\tmargin-bottom: 0;
\t}

\t.program-page .hero-btn-primary,
\t.program-page .hero-btn-secondary {
\t\tpadding: 11px 18px;
\t\tfont-size: 0.9rem;
\t\twhite-space: nowrap;
\t\tflex-shrink: 0;
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
\t\tgrid-template-columns: 1.18fr 0.82fr;
\t\tmin-height: 460px;
\t}
\t.program-page .program-hero-content {
\t\tpadding: 32px 20px 32px 28px;
\t\tmin-height: 460px;
\t}
\t.program-page .program-hero-title {
\t\tfont-size: 2rem;
\t}
\t.program-page .program-hero-desc {
\t\tfont-size: 0.95rem;
\t\tmax-width: 480px;
\t\tmargin-bottom: 16px;
\t}
\t.program-page .program-hero-meta {
\t\tmax-width: 480px;
\t\tgap: 6px;
\t\tmargin-bottom: 20px;
\t}
\t/* CTA Buttons strictly SIDE-BY-SIDE horizontally on 1050px to 901px */
\t.program-page .program-hero-actions {
\t\tdisplay: flex;
\t\tflex-direction: row;
\t\talign-items: center;
\t\tgap: 14px;
\t\tflex-wrap: wrap;
\t}
\t.program-page .hero-btn-primary,
\t.program-page .hero-btn-secondary {
\t\twidth: auto;
\t\tmax-width: none;
\t\twhite-space: nowrap;
\t\tflex-shrink: 0;
\t}
\t.program-page .program-hero-banner {
\t\tmin-height: 460px;
\t}
\t.program-page .program-hero-banner img {
\t\tobject-position: 68% center;
\t}
}"""

updated = content[:start_idx] + new_block + content[end_idx:]

with open(PAGE_PATH, "w", encoding="utf-8", newline="\n") as f:
    f.write(updated)

print("SUCCESS: CTA Side-by-side fix applied cleanly.")
