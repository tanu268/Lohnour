import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Update Highlights List bullet icons from ✦ to clean accessible bullet
# Replace <span class="bullet-icon">✦</span> with <span class="bullet-icon" aria-hidden="true"></span>
old_bullet = '<span class="bullet-icon">✦</span>'
new_bullet = '<span class="bullet-icon" aria-hidden="true"></span>'
content = content.replace(old_bullet, new_bullet)
print(f"Replaced {content.count(new_bullet)} highlight bullet icons.")

# 2. Update card titles under H2 from <h4> to <h3> in HTML for semantic hierarchy
# 2a. Section 5 (#why-program): Clear Mental Models & Practical Independence
content = re.sub(
    r'<div class="phil-col">\s*<h4>(.*?)</h4>',
    r'<div class="phil-col">\n\t\t\t\t\t\t<h3>\1</h3>',
    content
)

# 2b. Section 6 (#what-youll-learn): Module summary cards
content = re.sub(
    r'<div class="module-summary-card glass-card">\s*<span class="mod-num">([^<]+)</span>\s*<h4>(.*?)</h4>',
    r'<div class="module-summary-card glass-card">\n\t\t\t\t\t<span class="mod-num">\1</span>\n\t\t\t\t\t<h3>\2</h3>',
    content
)

# 2c. Section 8 (#what-youll-build): Build cards
content = re.sub(
    r'<div class="build-card glass-card">\s*<span class="build-step">([^<]+)</span>\s*<h4>(.*?)</h4>',
    r'<div class="build-card glass-card">\n\t\t\t\t\t<span class="build-step">\1</span>\n\t\t\t\t\t<h3>\2</h3>',
    content
)

# 2d. Section 11 (Where These Skills Can Be Applied): Career cards
content = re.sub(
    r'<div class="career-card glass-card">\s*<h4>(.*?)</h4>',
    r'<div class="career-card glass-card">\n\t\t\t\t\t<h3>\1</h3>',
    content
)

# 2e. Section 12 (#format): Coaching step cards
content = re.sub(
    r'<div class="coaching-step glass-card">\s*<div class="step-num">([^<]+)</div>\s*<h4>(.*?)</h4>',
    r'<div class="coaching-step glass-card">\n\t\t\t\t\t<div class="step-num">\1</div>\n\t\t\t\t\t<h3>\2</h3>',
    content
)

print("Updated HTML card headings to <h3> for semantic hierarchy.")

# 3. Typography & Bullet CSS Refinements in <style>
# Let's locate the CSS for section titles and CTA title
old_titles_css = """.program-page .section-heading-wrapper .section-title {
	font-size: 2rem;
	font-family: var(--font-heading);
	color: var(--color-text);
	margin-bottom: 6px;
	line-height: 1.25;
}"""

new_titles_css = """.program-page .section-heading-wrapper .section-title {
	font-size: clamp(1.65rem, 2.6vw, 2.15rem);
	font-family: var(--font-heading);
	font-weight: 700;
	color: var(--color-text);
	margin-bottom: 6px;
	line-height: 1.2;
	letter-spacing: -0.02em;
}"""

if old_titles_css in content:
    content = content.replace(old_titles_css, new_titles_css, 1)
    print("Section title CSS successfully updated.")
else:
    print("WARNING: Could not find exact old section title CSS!")

# Update CTA title CSS to match section title
old_cta_title_css = """.program-page .cta-title {
	font-size: clamp(1.75rem, 3.5vw, 2.25rem);
	margin-bottom: 8px;
	color: var(--color-text);
}"""

new_cta_title_css = """.program-page .cta-title {
	font-size: clamp(1.65rem, 2.6vw, 2.15rem);
	font-family: var(--font-heading);
	font-weight: 700;
	margin-bottom: 8px;
	color: var(--color-text);
	line-height: 1.2;
	letter-spacing: -0.02em;
}"""

if old_cta_title_css in content:
    content = content.replace(old_cta_title_css, new_cta_title_css, 1)
    print("CTA title CSS successfully updated.")
else:
    print("WARNING: Could not find exact old CTA title CSS!")

# Update Overview H3s
old_overview_h3 = """.program-page .overview-main h3,
.program-page .overview-highlights h3 {
	font-size: 1.35rem;
	margin-bottom: 10px;
	color: var(--color-text);
}"""

new_overview_h3 = """.program-page .overview-main h3,
.program-page .overview-highlights h3 {
	font-size: 1.2rem;
	font-family: var(--font-heading);
	font-weight: 600;
	line-height: 1.35;
	margin-bottom: 10px;
	color: var(--color-text);
}"""

if old_overview_h3 in content:
    content = content.replace(old_overview_h3, new_overview_h3, 1)
    print("Overview H3 CSS successfully updated.")
else:
    print("WARNING: Could not find exact old overview H3 CSS!")

# Update highlights list bullet CSS
old_hl_css = """.program-page .highlights-list .bullet-icon {
	color: var(--color-accent);
	font-size: 0.9rem;
	line-height: 1;
	margin-top: 2px;
}"""

new_hl_css = """.program-page .highlights-list .bullet-icon {
	width: 6px;
	height: 6px;
	border-radius: 50%;
	background-color: var(--color-accent);
	margin-top: 8px;
	flex-shrink: 0;
	display: inline-block;
}"""

if old_hl_css in content:
    content = content.replace(old_hl_css, new_hl_css, 1)
    print("Highlights bullet CSS successfully updated.")
else:
    print("WARNING: Could not find exact old highlights bullet CSS!")

# Update Scope list bullet CSS
old_scope_bullet = """.program-page .scope-list li::before {
	content: '•';
	position: absolute;
	left: 4px;
	color: var(--color-accent);
}"""

new_scope_bullet = """.program-page .scope-list li::before {
	content: '';
	position: absolute;
	left: 4px;
	top: 8px;
	width: 5px;
	height: 5px;
	border-radius: 50%;
	background-color: var(--color-accent);
}"""

if old_scope_bullet in content:
    content = content.replace(old_scope_bullet, new_scope_bullet, 1)
    print("Scope list bullet CSS successfully updated.")
else:
    print("WARNING: Could not find exact old scope bullet CSS!")

# Update card titles CSS (h3 and h4 support for full robustness)
# 1. Phil card:
content = content.replace(
    ".program-page .phil-col h4 {",
    ".program-page .phil-col h3,\n.program-page .phil-col h4 {\n\tfont-size: 1.12rem;\n\tfont-family: var(--font-heading);\n\tfont-weight: 600;\n\tline-height: 1.35;",
    1
)

# 2. Module summary card:
content = content.replace(
    ".program-page .module-summary-card h4 {",
    ".program-page .module-summary-card h3,\n.program-page .module-summary-card h4 {\n\tfont-size: 1.08rem;\n\tfont-family: var(--font-heading);\n\tfont-weight: 600;\n\tline-height: 1.35;",
    1
)

# 3. Build card:
content = content.replace(
    ".program-page .build-card h4 {",
    ".program-page .build-card h3,\n.program-page .build-card h4 {\n\tfont-size: 1.08rem;\n\tfont-family: var(--font-heading);\n\tfont-weight: 600;\n\tline-height: 1.35;",
    1
)

# 4. Career card:
content = content.replace(
    ".program-page .career-card h4 {",
    ".program-page .career-card h3,\n.program-page .career-card h4 {\n\tfont-size: 1.08rem;\n\tfont-family: var(--font-heading);\n\tfont-weight: 600;\n\tline-height: 1.35;",
    1
)

# 5. Coaching step:
content = content.replace(
    ".program-page .coaching-step h4 {",
    ".program-page .coaching-step h3,\n.program-page .coaching-step h4 {\n\tfont-size: 1.08rem;\n\tfont-family: var(--font-heading);\n\tfont-weight: 600;\n\tline-height: 1.35;",
    1
)

print("Updated card titles CSS rules.")

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print(f"Successfully saved {file_path}")
