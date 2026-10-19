import sys
sys.stdout.reconfigure(encoding='utf-8')

with open('current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

hero_start = text.find('<header class="program-hero')
hero_end = text.find('</header>', hero_start) + 9
print("=== HERO HTML IN page-agentic-ai-foundations.php ===")
print(text[hero_start:hero_end])
