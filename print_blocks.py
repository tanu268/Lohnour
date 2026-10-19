import sys, re
sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    orig = f.read()

css_match = re.search(r'/\* 7\. Curriculum Layout: Full-Width Module Content Area \*/.*?(?=/\* 8\.|\Z)', orig, re.DOTALL)
if css_match:
    print("--- CSS BLOCK ---")
    print(css_match.group(0))

js_match = re.search(r'// 1\. Module Block Accordions.*?(?=// 2\. FAQ Accordion Functionality|\Z)', orig, re.DOTALL)
if js_match:
    print("\n--- JS BLOCK ---")
    print(js_match.group(0))
