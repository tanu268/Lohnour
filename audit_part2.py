import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"
with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

print("=== 1. AUDIT: SECTION SUBTITLES ACROSS ALL SECTIONS ===")
subtitles = re.findall(r'<p class="([^"]*subtitle[^"]*|[^"]*desc[^"]*)">(.*?)</p>', content, re.DOTALL)
for cls, text in subtitles:
    clean = re.sub(r'<\?php.*?\?>', '', text).strip()
    print(f"  class='{cls}' | {clean[:60]}")

print("\n=== 2. AUDIT: BADGES & PILLS ===")
badges = re.findall(r'<span class="([^"]*badge[^"]*|[^"]*pill[^"]*)">(.*?)</span>', content, re.DOTALL)
for cls, text in badges:
    clean = re.sub(r'<\?php.*?\?>', '', text).strip()
    print(f"  span class='{cls}' | {clean[:40]}")

pills = re.findall(r'<div class="([^"]*badge[^"]*|[^"]*pill[^"]*)">(.*?)</div>', content, re.DOTALL)
for cls, text in pills:
    clean = re.sub(r'<\?php.*?\?>', '', text).strip()
    print(f"  div class='{cls}' | {clean[:40]}")

print("\n=== 3. AUDIT: FAQ TYPOGRAPHY ===")
faq_rules = re.findall(r'([^{}\n]*faq[^{}]*\{[^}]*\})', content)
for r in faq_rules:
    clean_r = " ".join(r.split())
    if any(k in clean_r for k in ['font-size', 'font-family', 'font-weight', 'line-height', 'color']):
        print(f"  {clean_r}")

print("\n=== 4. AUDIT: ALL LISTS (UL / OL) ===")
lists = re.findall(r'<([uo]l)([^>]*)>', content)
for tag, attrs in lists:
    cls_match = re.search(r'class="([^"]+)"', attrs)
    cls = cls_match.group(1) if cls_match else "none"
    print(f"  <{tag}> class='{cls}'")

print("\n=== 5. AUDIT: BUTTONS & CTAs ===")
btns = re.findall(r'<a[^>]*class="([^"]*btn[^"]*)"[^>]*>(.*?)</a>', content, re.DOTALL)
for cls, text in btns:
    clean = re.sub(r'<\?php.*?\?>', '', text)
    clean = re.sub(r'<[^>]+>', '', clean).strip()
    print(f"  <a class='{cls}'> | {clean[:40]}")

