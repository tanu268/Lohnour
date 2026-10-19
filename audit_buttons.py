import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"
with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# Find all <a> or <button> with class containing btn
btns = re.findall(r'<(?:a|button)[^>]*class="([^"]*btn[^"]*)"[^>]*>(.*?)</(?:a|button)>', text, re.DOTALL)
print(f"Total buttons found: {len(btns)}")
for cls, content in btns:
    # extract php gettext text
    php_texts = re.findall(r"esc_html(?:_e|__)\(\s*['\"]([^'\"]+)['\"]", content)
    clean_text = " / ".join(php_texts) if php_texts else re.sub(r'<[^>]+>', '', content).strip()
    print(f"  class='{cls}' | text='{clean_text}'")
