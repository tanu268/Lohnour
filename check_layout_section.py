with open('current source/lohnourpro_wp_theme/assets/css/layout.css', 'r', encoding='utf-8') as f:
    layout_css = f.read()

import re
print("layout.css section rules:")
for m in re.finditer(r'(?:^|\})\s*([^\{]*section[^\{]*)\{([^\}]+)\}', layout_css):
    print(m.group(1).strip(), "->", " ".join(m.group(2).split()))
