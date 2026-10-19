import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"
with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# Match <a> tags
a_tags = re.findall(r'<a\s+([^>]+)>(.*?)</a>', text, re.DOTALL)
print(f"Total <a> tags: {len(a_tags)}")
for attrs, content in a_tags:
    php_texts = re.findall(r"esc_html(?:_e|__)\(\s*['\"]([^'\"]+)['\"]", content)
    clean_text = " / ".join(php_texts) if php_texts else re.sub(r'<[^>]+>', '', content).strip()
    cls_match = re.search(r'class="([^"]+)"', attrs)
    cls = cls_match.group(1) if cls_match else "no-class"
    print(f"  [{cls}] -> '{clean_text}'")
