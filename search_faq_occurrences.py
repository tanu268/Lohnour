import os, re

theme_dir = r"D:\lohnourpro\current source\lohnourpro_wp_theme"
for f in os.listdir(theme_dir):
    if f.endswith(".php"):
        p = os.path.join(theme_dir, f)
        text = open(p, "r", encoding="utf-8", errors="ignore").read()
        if "faq" in text.lower():
            matches = re.findall(r'.{0,40}faq.{0,40}', text, re.IGNORECASE)
            print(f"=== {f} ({len(matches)} matches) ===")
            for m in matches[:3]:
                print(f"   {m.strip()}")
