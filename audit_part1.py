import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"
with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

print("=== 1. FONT FAMILIES IN CSS ===")
ffs = set(re.findall(r'font-family:\s*([^;]+);', content))
for ff in sorted(ffs):
    print(f"  {ff.strip()}")

print("\n=== 2. HEADINGS IN HTML ===")
headings = re.findall(r'<(h[1-6])([^>]*)>(.*?)</\1>', content, re.DOTALL)
for tag, attrs, text in headings:
    clean = re.sub(r'<[^>]+>', '', text)
    clean = re.sub(r'<\?php.*?\?>', '', clean).strip()
    cls_match = re.search(r'class="([^"]+)"', attrs)
    cls = cls_match.group(1) if cls_match else "none"
    print(f"  [{tag.upper()}] class='{cls}' | {clean[:50]}")

print("\n=== 3. SECTION TITLES & CTA TITLES IN CSS ===")
title_rules = re.findall(r'([^{}\n]*title[^{}]*\{[^}]*\})', content)
for r in title_rules:
    clean_r = " ".join(r.split())
    if any(k in clean_r for k in ['font-size', 'font-family', 'font-weight', 'line-height']):
        print(f"  {clean_r}")

print("\n=== 4. H3 / CARD HEADING RULES IN CSS ===")
h3_rules = re.findall(r'([^{}\n]*h3[^{}]*\{[^}]*\})', content)
for r in h3_rules:
    clean_r = " ".join(r.split())
    print(f"  {clean_r}")
