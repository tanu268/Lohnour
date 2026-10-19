import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

with open(r'current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

sections = re.findall(r'<section\s+id=[\'"]([^\'"]+)[\'"][^>]*>(.*?)</section>', text, re.DOTALL)
for sid, content in sections:
    title_m = re.search(r'class=[\'"]section-title[\'"][^>]*>(.*?)</h2>', content, re.DOTALL)
    badge_m = re.search(r'class=[\'"]card-badge[\'"][^>]*>(.*?)</span>', content, re.DOTALL)
    sub_m = re.search(r'class=[\'"]section-subtitle[\'"][^>]*>(.*?)</p>', content, re.DOTALL)
    title = re.sub(r'<[^>]+>', '', title_m.group(1)).strip() if title_m else 'No title'
    title = re.sub(r'<\?php.*?\?>', '', title).strip()
    if not title and title_m:
        php_m = re.search(r"esc_html_e\(\s*'([^']+)'", title_m.group(1))
        title = php_m.group(1) if php_m else "..."
    badge = re.sub(r'<[^>]+>', '', badge_m.group(1)).strip() if badge_m else 'No badge'
    badge = re.sub(r'<\?php.*?\?>', '', badge).strip()
    if not badge and badge_m:
        php_m = re.search(r"esc_html_e\(\s*'([^']+)'", badge_m.group(1))
        badge = php_m.group(1) if php_m else "..."
    sub = re.sub(r'<[^>]+>', '', sub_m.group(1)).strip() if sub_m else 'No sub'
    sub = re.sub(r'<\?php.*?\?>', '', sub).strip()
    if not sub and sub_m:
        php_m = re.search(r"esc_html_e\(\s*'([^']+)'", sub_m.group(1))
        sub = php_m.group(1) if php_m else "..."
    print(f'{sid:22} | {badge:32} | {title:35} | {sub[:60]}')
