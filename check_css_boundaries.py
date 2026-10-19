import re

filepath = 'current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Verify that Phase 1 hero is untouched
hero_start = content.find('/* 1. Program Hero Refined Composition (Phase 1) */')
anchor_start = content.find('/* 2. Sticky Anchor Navigation */')
section_start = content.find('/* 3. Section Structure */')
style_end = content.find('</style>')

print(f"Indices: hero={hero_start}, anchor={anchor_start}, section={section_start}, style_end={style_end}")
assert hero_start != -1 and anchor_start != -1 and section_start != -1 and style_end != -1

# Let's inspect the exact block from section_start to style_end
current_section_css = content[section_start:style_end]
print(f"Current section CSS length: {len(current_section_css)} characters")
