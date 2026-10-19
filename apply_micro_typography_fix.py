import sys, os

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# CHANGE 1: Program Outcome Text (north-star-quote p: italic -> normal, font-weight: 500)
old_outcome_css = """.program-page .north-star-quote p {
\tfont-size: 1.15rem;
\tfont-style: italic;
\tline-height: 1.55;
\tcolor: var(--color-text);
\tmargin-bottom: var(--spacing-md);
\tpadding-bottom: var(--spacing-md);
\tborder-bottom: 1px solid var(--color-border-subtle);
}"""

new_outcome_css = """.program-page .north-star-quote p {
\tfont-size: 1.15rem;
\tfont-style: normal;
\tfont-weight: 500;
\tline-height: 1.55;
\tcolor: var(--color-text);
\tmargin-bottom: var(--spacing-md);
\tpadding-bottom: var(--spacing-md);
\tborder-bottom: 1px solid var(--color-border-subtle);
}"""

if old_outcome_css not in content:
    print("ERROR: Could not find exact old_outcome_css block!")
    sys.exit(1)

content = content.replace(old_outcome_css, new_outcome_css, 1)
print("PASS: Change 1 (Program Outcome Text non-italic) applied.")

# CHANGE 2: Key Cohort Highlights bullet-point headings (highlights-list strong)
# Add scoped CSS rule for .program-page .highlights-list strong
old_hl_p_css = """.program-page .highlights-list p {
\tfont-size: 0.9rem;
\tcolor: var(--color-text-muted);
\tmargin: 2px 0 0 0;
\tline-height: 1.5;
}"""

new_hl_p_css = """.program-page .highlights-list strong {
\tfont-size: 0.95rem;
\tfont-weight: 600;
\tcolor: var(--color-text);
\tline-height: 1.35;
\tdisplay: block;
}

.program-page .highlights-list p {
\tfont-size: 0.9rem;
\tcolor: var(--color-text-muted);
\tmargin: 2px 0 0 0;
\tline-height: 1.5;
}"""

if old_hl_p_css not in content:
    print("ERROR: Could not find exact old_hl_p_css block!")
    sys.exit(1)

content = content.replace(old_hl_p_css, new_hl_p_css, 1)
print("PASS: Change 2 (.highlights-list strong font-size refinement) applied.")

# Write back without BOM
with open(file_path, "wb") as f:
    f.write(content.encode("utf-8"))

print(f"SUCCESS: Saved updated file to {file_path}")
