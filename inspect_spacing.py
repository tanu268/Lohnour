import re

with open('current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Look for .program-section styles and general section padding
print("=== General section and layout styles ===")
matches = re.findall(r'(\.program-[^{]*\{[^}]*\})', content)
for m in matches:
    if any(k in m for k in ['padding', 'margin', 'gap']):
        print(m.strip().replace('\n', ' '))

print("\n=== All section headings and descriptions ===")
matches2 = re.findall(r'(\.program-[^{]*(?:header|title|desc|eyebrow|lead|subtitle)[^{]*\{[^}]*\})', content)
for m in matches2:
    if any(k in m for k in ['margin', 'padding', 'gap']):
        print(m.strip().replace('\n', ' '))

print("\n=== Grid and card styles ===")
matches3 = re.findall(r'(\.program-[^{]*(?:grid|card|box|list|item)[^{]*\{[^}]*\})', content)
for m in matches3:
    if any(k in m for k in ['margin', 'padding', 'gap']):
        print(m.strip().replace('\n', ' '))
