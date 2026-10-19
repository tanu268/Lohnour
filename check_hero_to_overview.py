import sys
sys.stdout.reconfigure(encoding='utf-8')

with open('current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

overview_idx = text.find('<section id="overview"')
hero_idx = text.find('class="program-hero')
print(f"Hero idx: {hero_idx}, Overview idx: {overview_idx}")
if hero_idx != -1 and overview_idx != -1:
    snippet = text[hero_idx-20:overview_idx]
    # Print lines that have tags
    for line in snippet.split('\n'):
        if any(k in line for k in ['<header', '</header', '<nav', '</nav', '<div', '</div', '<section', 'id=']):
            print(line[:120])
