import sys, re

sys.stdout.reconfigure(encoding='utf-8')

with open(r'D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

matches = re.findall(r'(\.[^{]+)\{([^}]*opacity[^}]*)\}', text)
print(f"Found {len(matches)} rules mentioning opacity:")
for sel, rules in matches:
    if any(k in sel for k in ['hero', 'banner']):
        print(f"{sel.strip()} {{\n{rules.strip()}\n}}\n")
