with open('current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

print(f"Total lines in page-agentic-ai-foundations.php: {len(lines)}")
for i, line in enumerate(lines):
    if '<style' in line:
        print(f"Style start at line {i+1}")
    if '</style>' in line:
        print(f"Style end at line {i+1}")
