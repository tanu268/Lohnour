import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

print("==================================================")
print("1. FONT FAMILIES IN PAGE CSS")
print("==================================================")
font_families = re.findall(r'font-family:\s*([^;]+);', content)
print("Unique font-family values:")
for ff in set(font_families):
    print(f"  - {ff.strip()} (count: {font_families.count(ff)})")

print("\n==================================================")
print("2. HEADINGS IN HTML (SEMANTIC & CLASSES)")
print("==================================================")
headings = re.findall(r'<(h[1-6])([^>]*)>(.*?)</\1>', content, re.DOTALL)
print(f"Total HTML heading tags: {len(headings)}")
for tag, attrs, text in headings:
    # clean text
    clean_text = re.sub(r'<[^>]+>', '', text)
    clean_text = re.sub(r'<\?php.*?\?>', '', clean_text).strip()
    cls_match = re.search(r'class="([^"]+)"', attrs)
    cls = cls_match.group(1) if cls_match else "none"
    print(f"  [{tag.upper()}] class='{cls}' | {clean_text[:50]}")

print("\n==================================================")
print("3. HEADING RULES IN CSS")
print("==================================================")
# Find all CSS rules targeting h1, h2, h3, h4, or classes with title/heading
css_heading_rules = re.findall(r'([^{}\n]*h[1-6][^{}]*\{[^}]*\}|[^{}\n]*title[^{}]*\{[^}]*\})', content)
for r in css_heading_rules:
    clean_r = " ".join(r.split())
    if any(k in clean_r for k in ['font-size', 'font-weight', 'line-height', 'letter-spacing']):
        print(f"  {clean_r}")

print("\n==================================================")
print("4. PARAGRAPH & BODY TEXT RULES IN CSS")
print("==================================================")
p_rules = re.findall(r'([^{}\n]*\bp\b[^{}]*\{[^}]*\}|[^{}\n]*desc[^{}]*\{[^}]*\}|[^{}\n]*text[^{}]*\{[^}]*\})', content)
for r in p_rules:
    clean_r = " ".join(r.split())
    if any(k in clean_r for k in ['font-size', 'line-height', 'color']):
        print(f"  {clean_r}")

print("\n==================================================")
print("5. METADATA & CHIPS IN CSS")
print("==================================================")
chip_rules = re.findall(r'([^{}\n]*chip[^{}]*\{[^}]*\}|[^{}\n]*meta[^{}]*\{[^}]*\}|[^{}\n]*pill[^{}]*\{[^}]*\})', content)
for r in chip_rules:
    clean_r = " ".join(r.split())
    if any(k in clean_r for k in ['font-size', 'font-weight']):
        print(f"  {clean_r}")

print("\n==================================================")
print("6. CTA & BUTTONS IN CSS")
print("==================================================")
btn_rules = re.findall(r'([^{}\n]*btn[^{}]*\{[^}]*\}|[^{}\n]*cta[^{}]*\{[^}]*\})', content)
for r in btn_rules:
    clean_r = " ".join(r.split())
    if any(k in clean_r for k in ['font-size', 'font-weight']):
        print(f"  {clean_r}")

print("\n==================================================")
print("7. LISTS & BULLETS IN CSS")
print("==================================================")
list_rules = re.findall(r'([^{}\n]*list[^{}]*\{[^}]*\}|[^{}\n]*bullet[^{}]*\{[^}]*\})', content)
for r in list_rules:
    clean_r = " ".join(r.split())
    if any(k in clean_r for k in ['font-size', 'line-height', 'padding', 'margin']):
        print(f"  {clean_r}")

print("\n==================================================")
print("8. CURRICULUM MODULE & ACCORDION TYPOGRAPHY")
print("==================================================")
curr_rules = re.findall(r'([^{}\n]*curriculum[^{}]*\{[^}]*\}|[^{}\n]*module[^{}]*\{[^}]*\}|[^{}\n]*accordion[^{}]*\{[^}]*\})', content)
for r in curr_rules:
    clean_r = " ".join(r.split())
    if any(k in clean_r for k in ['font-size', 'font-weight', 'line-height']):
        print(f"  {clean_r}")
