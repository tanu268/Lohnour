import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

old_theme_css = """/* Theme Switching Mechanism for Program Banner */
.program-page .program-hero-banner .banner-theme-dark {
	display: block;
}
.program-page .program-hero-banner .banner-theme-light {
	display: none;
}

html.light-theme .program-page .program-hero-banner .banner-theme-dark {
	display: none !important;
}
html.light-theme .program-page .program-hero-banner .banner-theme-light {
	display: block !important;
}

html.dark-theme .program-page .program-hero-banner .banner-theme-dark {
	display: block !important;
}
html.dark-theme .program-page .program-hero-banner .banner-theme-light {
	display: none !important;
}"""

new_theme_css = """/* Theme Switching Mechanism for Program Banner with Surgical Opacity Refinement */
.program-page .program-hero-banner .banner-theme-dark {
	display: block;
	opacity: 0.80;
}
.program-page .program-hero-banner .banner-theme-light {
	display: none;
	opacity: 0.85;
}

html.light-theme .program-page .program-hero-banner .banner-theme-dark,
[data-theme="light"] .program-page .program-hero-banner .banner-theme-dark {
	display: none !important;
}
html.light-theme .program-page .program-hero-banner .banner-theme-light,
[data-theme="light"] .program-page .program-hero-banner .banner-theme-light {
	display: block !important;
	opacity: 0.85;
}

html.dark-theme .program-page .program-hero-banner .banner-theme-dark,
[data-theme="dark"] .program-page .program-hero-banner .banner-theme-dark {
	display: block !important;
	opacity: 0.80;
}
html.dark-theme .program-page .program-hero-banner .banner-theme-light,
[data-theme="dark"] .program-page .program-hero-banner .banner-theme-light {
	display: none !important;
}"""

if old_theme_css in content:
    content = content.replace(old_theme_css, new_theme_css, 1)
    print("Theme switching CSS successfully updated with opacity rules.")
else:
    print("ERROR: Could not find exact old theme CSS block!")
    sys.exit(1)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print(f"Successfully saved {file_path}")
