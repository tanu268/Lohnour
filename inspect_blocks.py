import sys, re
sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    orig = f.read()

# Let's inspect CSS around line 2125 to 2280
css_match = re.search(r'/\* 7\. Curriculum Layout: Full-Width Module Content Area \*/.*?(?=/\* 8\.|\Z)', orig, re.DOTALL)
if css_match:
    print("Found Curriculum CSS block, length:", len(css_match.group(0)))
else:
    print("Could not find Curriculum CSS block directly.")

# Let's inspect JS block for Curriculum
js_match = re.search(r'// 1\. Module Block Accordions.*?(?=// 2\. FAQ Accordion Functionality|\Z)', orig, re.DOTALL)
if js_match:
    print("Found Curriculum JS block, length:", len(js_match.group(0)))
else:
    print("Could not find Curriculum JS block directly.")
