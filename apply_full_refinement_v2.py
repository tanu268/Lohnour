import sys, re, os

sys.stdout.reconfigure(encoding='utf-8')

backup_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php.bak_pre_final_uiux_refinement"
target_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

if not os.path.exists(backup_path):
    print(f"ERROR: Backup file {backup_path} not found!")
    sys.exit(1)

with open(backup_path, "r", encoding="utf-8") as f:
    content = f.read()

print(f"Loaded backup: {len(content)} characters.")

# =========================================================================
# 1. BULLET CONSISTENCY (HTML & CSS)
# =========================================================================

# 1a. Replace sparkle ✦ with clean accessible circular bullet
old_bullet = '<span class="bullet-icon">✦</span>'
new_bullet = '<span class="bullet-icon" aria-hidden="true"></span>'
if old_bullet in content:
    count = content.count(old_bullet)
    content = content.replace(old_bullet, new_bullet)
    print(f"Replaced {count} instances of {old_bullet}")
else:
    print("ERROR: old_bullet not found!")
    sys.exit(1)

# 1b. Style .bullet-icon as a clean circular dot in CSS
old_hl_bullet_css = """.program-page .highlights-list .bullet-icon {
\tcolor: var(--color-accent);
\tfont-size: 0.9rem;
\tmargin-top: 2px;
}"""

new_hl_bullet_css = """.program-page .highlights-list .bullet-icon {
\twidth: 6px;
\theight: 6px;
\tborder-radius: 50%;
\tbackground-color: var(--color-accent);
\tmargin-top: 8px;
\tflex-shrink: 0;
\tdisplay: inline-block;
}"""

if old_hl_bullet_css in content:
    content = content.replace(old_hl_bullet_css, new_hl_bullet_css, 1)
    print("PASS: highlights-list bullet CSS updated.")
else:
    print("ERROR: old_hl_bullet_css not found!")
    sys.exit(1)

# 1c. Style .scope-list li::before as clean circular dot
old_scope_pattern = r"\.program-page \.scope-list li::before\s*\{[^}]*content:\s*['\"][^'\"]*['\"][^}]*\}"
m = re.search(old_scope_pattern, content)
if m:
    old_scope_bullet_css = m.group(0)
    new_scope_bullet_css = """.program-page .scope-list li::before {
\tcontent: '';
\tposition: absolute;
\tleft: 4px;
\ttop: 8px;
\twidth: 5px;
\theight: 5px;
\tborder-radius: 50%;
\tbackground-color: var(--color-accent);
}"""
    content = content.replace(old_scope_bullet_css, new_scope_bullet_css, 1)
    print("PASS: scope-list bullet CSS updated.")
else:
    print("ERROR: old_scope_bullet_css not found!")
    sys.exit(1)

# =========================================================================
# 2. TYPOGRAPHY HIERARCHY — HTML TAG STANDARDIZATION (H4 -> H3 under H2)
# =========================================================================

# 2a. Section 5: .phil-col
content, c5 = re.subn(
    r'<div class="phil-col">\s*<h4>(.*?)</h4>',
    r'<div class="phil-col">\n\t\t\t\t\t\t<h3>\1</h3>',
    content
)
print(f"Section 5 phil-col: updated {c5} headings to <h3>")

# 2b. Section 6: .module-summary-card
content, c6 = re.subn(
    r'(<div class="module-summary-card[^"]*">\s*<div class="mod-num">[^<]+</div>\s*)<h4>(.*?)</h4>',
    r'\1<h3>\2</h3>',
    content
)
print(f"Section 6 module-summary-card: updated {c6} headings to <h3>")

# 2c. Section 8: .build-card
content, c8 = re.subn(
    r'(<div class="build-card[^"]*">\s*<div class="build-step">[^<]+</div>\s*)<h4>(.*?)</h4>',
    r'\1<h3>\2</h3>',
    content
)
print(f"Section 8 build-card: updated {c8} headings to <h3>")

# 2d. Section 11: .career-card
content, c11 = re.subn(
    r'(<div class="career-card[^"]*">\s*)<h4>(.*?)</h4>',
    r'\1<h3>\2</h3>',
    content
)
print(f"Section 11 career-card: updated {c11} headings to <h3>")

# 2e. Section 12: .coaching-step
content, c12 = re.subn(
    r'(<div class="coaching-step[^"]*">\s*<div class="step-num">[^<]+</div>\s*)<h4>(.*?)</h4>',
    r'\1<h3>\2</h3>',
    content
)
print(f"Section 12 coaching-step: updated {c12} headings to <h3>")

total_h3_updates = c5 + c6 + c8 + c11 + c12
print(f"Total card headings updated to <h3>: {total_h3_updates} (expected 26)")

# =========================================================================
# 3. TYPOGRAPHY HIERARCHY — CSS STANDARDIZATION
# =========================================================================

# 3a. Section Title H2 standard: clamp(1.45rem, 2.5vw, 2.15rem)
# Ensures H1 (44px..27.2px) > H2 (34.4px..23.2px) > H3 (19.2px..17.28px) at all viewports
old_section_title_css = """.program-page .section-heading-wrapper .section-title {
\tfont-size: 2rem;
\tmargin-bottom: 6px;
\tcolor: var(--color-text);
}"""

new_section_title_css = """.program-page .section-heading-wrapper .section-title,
.program-page .pricing-card .section-title {
\tfont-size: clamp(1.45rem, 2.5vw, 2.15rem);
\tfont-family: var(--font-heading);
\tfont-weight: 700;
\tcolor: var(--color-text);
\tmargin-bottom: 6px;
\tline-height: 1.2;
\tletter-spacing: -0.02em;
}"""

if old_section_title_css in content:
    content = content.replace(old_section_title_css, new_section_title_css, 1)
    print("PASS: section-title CSS updated.")
else:
    print("ERROR: old_section_title_css not found!")
    sys.exit(1)

# 3b. CTA Title H2 standard: match clamp(1.45rem, 2.5vw, 2.15rem)
old_cta_title_css = """.program-page .cta-title {
\tfont-size: clamp(1.75rem, 3.5vw, 2.25rem);
\tmargin-bottom: 8px;
\tcolor: var(--color-text);
}"""

new_cta_title_css = """.program-page .cta-title {
\tfont-size: clamp(1.45rem, 2.5vw, 2.15rem);
\tfont-family: var(--font-heading);
\tfont-weight: 700;
\tmargin-bottom: 8px;
\tcolor: var(--color-text);
\tline-height: 1.2;
\tletter-spacing: -0.02em;
}"""

if old_cta_title_css in content:
    content = content.replace(old_cta_title_css, new_cta_title_css, 1)
    print("PASS: cta-title CSS updated.")
else:
    print("ERROR: old_cta_title_css not found!")
    sys.exit(1)

# 3c. Tiny screens (380px and below) H1 adjustment to 1.7rem (27.2px) so H1 > H2 (23.2px)
old_tiny_h1 = """.program-page .program-hero-title {
\t\tfont-size: 1.6rem;
\t}"""

new_tiny_h1 = """.program-page .program-hero-title {
\t\tfont-size: 1.7rem;
\t}"""

if old_tiny_h1 in content:
    content = content.replace(old_tiny_h1, new_tiny_h1, 1)
    print("PASS: tiny screen H1 updated to 1.7rem.")
else:
    print("WARNING: old_tiny_h1 not matched directly, checking regex")
    content = re.sub(
        r'(@media\s*\(max-width:\s*380px\)\s*\{\s*\.program-page\s*\.program-hero-title\s*\{\s*font-size:\s*)1\.6rem;',
        r'\g<1>1.7rem;',
        content
    )
    print("PASS: tiny screen H1 regex replaced.")

# 3d. Overview H3s: 1.2rem
old_overview_h3 = """.program-page .overview-main h3,
.program-page .overview-highlights h3 {
\tfont-size: 1.35rem;
\tmargin-bottom: 10px;
\tcolor: var(--color-text);
}"""

new_overview_h3 = """.program-page .overview-main h3,
.program-page .overview-highlights h3 {
\tfont-size: 1.2rem;
\tfont-family: var(--font-heading);
\tfont-weight: 600;
\tline-height: 1.35;
\tmargin-bottom: 10px;
\tcolor: var(--color-text);
}"""

if old_overview_h3 in content:
    content = content.replace(old_overview_h3, new_overview_h3, 1)
    print("PASS: overview H3 CSS updated.")
else:
    print("ERROR: old_overview_h3 not found!")
    sys.exit(1)

# 3e. Phil col card titles: 1.12rem
old_phil_h4 = """.program-page .phil-col h4 {
\tfont-size: 1.1rem;
\tmargin-bottom: 6px;
\tcolor: var(--color-text);
}"""

new_phil_h3 = """.program-page .phil-col h3,
.program-page .phil-col h4 {
\tfont-size: 1.12rem;
\tfont-family: var(--font-heading);
\tfont-weight: 600;
\tline-height: 1.35;
\tmargin-bottom: 6px;
\tcolor: var(--color-text);
}"""

if old_phil_h4 in content:
    content = content.replace(old_phil_h4, new_phil_h3, 1)
    print("PASS: phil-col H3/H4 CSS updated.")
else:
    print("ERROR: old_phil_h4 not found!")
    sys.exit(1)

# 3f. Module summary card titles: 1.08rem
old_mod_sum_h4 = """.program-page .module-summary-card h4 {
\tfont-size: 1rem;
\tmargin-bottom: 4px;
\tcolor: var(--color-text);
\tline-height: 1.3;
}"""

new_mod_sum_h3 = """.program-page .module-summary-card h3,
.program-page .module-summary-card h4 {
\tfont-size: 1.08rem;
\tfont-family: var(--font-heading);
\tfont-weight: 600;
\tline-height: 1.3;
\tmargin-bottom: 4px;
\tcolor: var(--color-text);
}"""

if old_mod_sum_h4 in content:
    content = content.replace(old_mod_sum_h4, new_mod_sum_h3, 1)
    print("PASS: module-summary-card H3/H4 CSS updated.")
else:
    print("ERROR: old_mod_sum_h4 not found!")
    sys.exit(1)

# 3g. Build card titles: 1.08rem
old_build_h4 = """.program-page .build-card h4 {
\tfont-size: 1.05rem;
\tmargin-bottom: 4px;
\tcolor: var(--color-text);
}"""

new_build_h3 = """.program-page .build-card h3,
.program-page .build-card h4 {
\tfont-size: 1.08rem;
\tfont-family: var(--font-heading);
\tfont-weight: 600;
\tline-height: 1.35;
\tmargin-bottom: 4px;
\tcolor: var(--color-text);
}"""

if old_build_h4 in content:
    content = content.replace(old_build_h4, new_build_h3, 1)
    print("PASS: build-card H3/H4 CSS updated.")
else:
    print("ERROR: old_build_h4 not found!")
    sys.exit(1)

# 3h. Career card titles: 1.08rem
old_career_h4 = """.program-page .career-card h4 {
\tfont-size: 1.02rem;
\tmargin-bottom: 4px;
\tcolor: var(--color-text);
}"""

new_career_h3 = """.program-page .career-card h3,
.program-page .career-card h4 {
\tfont-size: 1.08rem;
\tfont-family: var(--font-heading);
\tfont-weight: 600;
\tline-height: 1.35;
\tmargin-bottom: 4px;
\tcolor: var(--color-text);
}"""

if old_career_h4 in content:
    content = content.replace(old_career_h4, new_career_h3, 1)
    print("PASS: career-card H3/H4 CSS updated.")
else:
    print("ERROR: old_career_h4 not found!")
    sys.exit(1)

# 3i. Coaching step card titles: 1.08rem
old_step_h4 = """.program-page .coaching-step h4 {
\tfont-size: 1.05rem;
\tmargin-bottom: 4px;
\tcolor: var(--color-text);
}"""

new_step_h3 = """.program-page .coaching-step h3,
.program-page .coaching-step h4 {
\tfont-size: 1.08rem;
\tfont-family: var(--font-heading);
\tfont-weight: 600;
\tline-height: 1.35;
\tmargin-bottom: 4px;
\tcolor: var(--color-text);
}"""

if old_step_h4 in content:
    content = content.replace(old_step_h4, new_step_h3, 1)
    print("PASS: coaching-step H3/H4 CSS updated.")
else:
    print("ERROR: old_step_h4 not found!")
    sys.exit(1)

# Write output file without BOM
with open(target_path, "wb") as f:
    f.write(content.encode("utf-8"))

print(f"SUCCESS: Saved updated file to {target_path}")
