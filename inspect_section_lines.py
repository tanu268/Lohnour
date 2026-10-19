import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

with open(r'current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

# Let's see the line numbers and section tags
lines = text.splitlines()
for idx, line in enumerate(lines[:1700]):
    if '<section' in line or '</section>' in line:
        print(f"L{idx+1:4}: {line.strip()}")
