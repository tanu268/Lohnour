import sys

PAGE_PATH = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(PAGE_PATH, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Target block for Section 14 (lines 2890-2933)
old_sec14 = """/* 14. Pricing / Enrollment */
.program-page .pricing-card {
\tpadding: clamp(1.5rem, 4vw, 2.5rem);
\tborder-radius: var(--radius-lg);
\tborder-color: var(--color-glass-border);
}

.program-page .pricing-desc {
\tfont-size: 1.05rem;
\tcolor: var(--color-text-secondary);
\tmax-width: 650px;
\tmargin: 0 auto 16px auto;
\tline-height: 1.55;
}

.program-page .pricing-callout {
\tdisplay: flex;
\tflex-wrap: wrap;
\tjustify-content: center;
\tgap: 8px;
\tmargin-bottom: 16px;
}

.program-page .callout-pill {
\tpadding: 6px 14px;
\tfont-size: 0.82rem;
\tfont-weight: 600;
\tbackground: var(--color-bg-alt);
\tcolor: var(--color-text);
\tborder-radius: var(--radius-full);
\tborder: 1px solid var(--color-border-subtle);
}

.program-page .pricing-actions {
\tmargin-bottom: var(--spacing-xs);
}

.program-page .pricing-subtext {
\tfont-size: 0.82rem;
\tcolor: var(--color-text-muted);
\tmax-width: 500px;
\tmargin: 0 auto;
}"""

if old_sec14 not in content:
    print("ERROR: old_sec14 not found!")
    sys.exit(1)

new_sec14 = """/* 14. Pricing / Enrollment - Premium Spacing Refinement */
.program-page .pricing-card {
\tpadding: clamp(2.25rem, 5vw, 3.25rem) clamp(1.5rem, 4vw, 2.5rem);
\tborder-radius: var(--radius-lg);
\tborder-color: var(--color-glass-border);
}

.program-page .pricing-card .card-badge {
\tdisplay: inline-block;
\tmargin-bottom: 12px;
}

.program-page .pricing-card .section-title {
\tmargin-bottom: 14px;
}

.program-page .pricing-desc {
\tfont-size: 1.05rem;
\tcolor: var(--color-text-secondary);
\tmax-width: 650px;
\tmargin: 0 auto 22px auto;
\tline-height: 1.55;
}

.program-page .pricing-callout {
\tdisplay: flex;
\tflex-wrap: wrap;
\tjustify-content: center;
\tgap: 8px;
\tmargin-bottom: 30px;
}

.program-page .callout-pill {
\tpadding: 6px 14px;
\tfont-size: 0.82rem;
\tfont-weight: 600;
\tbackground: var(--color-bg-alt);
\tcolor: var(--color-text);
\tborder-radius: var(--radius-full);
\tborder: 1px solid var(--color-border-subtle);
}

.program-page .pricing-actions {
\tmargin-bottom: 18px;
}

.program-page .pricing-subtext {
\tfont-size: 0.82rem;
\tcolor: var(--color-text-muted);
\tmax-width: 500px;
\tmargin: 0 auto;
\tline-height: 1.5;
}"""

content = content.replace(old_sec14, new_sec14, 1)

# 2. Target block for mobile media query (around line 3062)
old_mobile = """\t.program-page .pricing-card,
\t.program-page .cta-section {
\t\tpadding: 1.5rem 1rem;
\t}"""

if old_mobile not in content:
    print("ERROR: old_mobile not found!")
    sys.exit(1)

new_mobile = """\t.program-page .pricing-card,
\t.program-page .cta-section {
\t\tpadding: 2rem 1.15rem;
\t}

\t.program-page .pricing-card .card-badge {
\t\tmargin-bottom: 10px;
\t}

\t.program-page .pricing-card .section-title {
\t\tmargin-bottom: 12px;
\t}

\t.program-page .pricing-desc {
\t\tmargin-bottom: 18px;
\t}

\t.program-page .pricing-callout {
\t\tmargin-bottom: 24px;
\t}

\t.program-page .pricing-actions {
\t\tmargin-bottom: 16px;
\t}"""

content = content.replace(old_mobile, new_mobile, 1)

with open(PAGE_PATH, "w", encoding="utf-8", newline="\n") as f:
    f.write(content)

print("SUCCESS: Cohort Enrollment spacing refinement applied cleanly.")
