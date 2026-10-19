with open('current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

style_lines = lines[1510:2894]
for idx, line in enumerate(style_lines):
    if line.strip().startswith('/*') and any(s in line for s in ['===', '1.', '2.', '3.', '4.', '5.', '6.', '7.', '8.', '9.', '10.', '11.', '12.', '13.', '14.', '15.', '16.', '@media']):
        print(f"Line {1511 + idx}: {line.strip()[:80]}")
