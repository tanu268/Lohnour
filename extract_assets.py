import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

with open(r'current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

# 1. Extract CSS
style_m = re.search(r'<style>(.*?)</style>', text, re.DOTALL)
if style_m:
    css_content = style_m.group(1).strip()
    with open(r'current source/lohnourpro_wp_theme/assets/css/program-single.css', 'w', encoding='utf-8') as f_css:
        f_css.write(css_content + '\n')
    print(f"Extracted CSS: {len(css_content)} bytes")
else:
    print("ERROR: Could not find <style> block")

# 2. Extract JS
script_m = re.search(r'<script>(.*?)</script>', text, re.DOTALL)
if script_m:
    js_content = script_m.group(1).strip()
    with open(r'current source/lohnourpro_wp_theme/assets/js/program-single.js', 'w', encoding='utf-8') as f_js:
        f_js.write(js_content + '\n')
    print(f"Extracted JS: {len(js_content)} bytes")
else:
    print("ERROR: Could not find <script> block")
