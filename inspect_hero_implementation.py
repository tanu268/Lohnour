import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# 1. Print HTML around hero banner
hero_html = re.search(r'<header class="program-hero.*?</header>', text, re.DOTALL)
if hero_html:
    print("=== HERO HTML ===")
    lines = hero_html.group(0).splitlines()
    for l in lines[:10]:
        print(l)
    print("...")
    for l in lines[-30:]:
        print(l)

# 2. Search for CSS related to banner and hero
print("\n=== HERO CSS RULES ===")
css_matches = re.findall(r'(\/\*.*?\*\/|\.[a-zA-Z0-9_\-\.\:\s,>\[\]="]+)\{([^}]+)\}', text)
for sel, rules in css_matches:
    if any(k in sel for k in ['banner', 'hero', 'program-hero']):
        print(f"{sel.strip()} {{\n{rules.strip()}\n}}\n")
