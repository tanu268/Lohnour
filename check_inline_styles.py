import re

with open('current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

# Only inspect the HTML portion before <style>
html_part = text[:text.find('<style')]
inline_styles = re.findall(r'style="([^"]*)"', html_part)
print(f"Total inline styles in HTML: {len(inline_styles)}")
for s in inline_styles:
    print(" ", s)
