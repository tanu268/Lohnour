import os, re

theme_dir = r"D:\lohnourpro\current source\lohnourpro_wp_theme"
for f in os.listdir(theme_dir):
    if f.endswith(".php"):
        p = os.path.join(theme_dir, f)
        text = open(p, "r", encoding="utf-8", errors="ignore").read()
        matches = re.findall(r'href=[\'"][^\'"]*faq[^\'"]*[\'"]', text, re.IGNORECASE)
        if matches:
            print(f, matches)
