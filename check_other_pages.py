import glob
import re

print("=== Checking Guide Spacing in single-user_guide.php and guides.css ===")
with open('current source/lohnourpro_wp_theme/assets/css/guides.css', 'r', encoding='utf-8') as f:
    g_css = f.read()

for m in re.finditer(r'([^{}]+)\{([^}]+)\}', g_css):
    sel = m.group(1).strip()
    body = " ".join(m.group(2).split())
    if any(k in body for k in ['padding', 'margin', 'gap']):
        print(f"guides.css: {sel} -> {body}")

print("\n=== Checking other page templates in theme ===")
templates = glob.glob('current source/lohnourpro_wp_theme/*.php')
for t in templates:
    with open(t, 'r', encoding='utf-8', errors='ignore') as f:
        text = f.read()
    if 'section' in text and ('spacing' in text or 'margin' in text or 'padding' in text):
        m = re.findall(r'<section[^>]*class="([^"]*)"', text)
        if m:
            print(f"{t}: section classes: {set(m)}")
