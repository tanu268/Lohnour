import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# Find all <ul> and <ol>
lists = re.finditer(r'<(ul|ol)([^>]*)>(.*?)</\1>', text, re.DOTALL)

for i, m in enumerate(lists):
    tag = m.group(1)
    attrs = m.group(2).strip()
    body = m.group(3)
    
    # count items
    items = re.findall(r'<li[^>]*>(.*?)</li>', body, re.DOTALL)
    line_num = text[:m.start()].count('\n') + 1
    
    # get nearest preceding section or heading
    pre_text = text[:m.start()]
    sec_match = list(re.finditer(r'<section[^>]*id="([^"]*)"', pre_text))
    sec_id = sec_match[-1].group(1) if sec_match else "header"
    
    print(f"\n--- List #{i+1} at L{line_num} in #{sec_id} (<{tag} {attrs}>) ---")
    print(f"Total items: {len(items)}")
    for j, it in enumerate(items[:3]):
        clean_it = " ".join(re.sub(r'<[^>]+>', '', re.sub(r'<\?php.*?\?>', '', it)).split())
        if not clean_it:
            esc_m = re.search(r"esc_html_e\(\s*'([^']+)'", it)
            clean_it = esc_m.group(1) if esc_m else "..."
        # Check if item contains an icon, SVG, bullet, or checkmark
        has_svg = '<svg' in it
        has_symbol = any(s in it for s in ['✓', '✕', '•', '→', '-'])
        print(f"  Item {j+1} (svg={has_svg}, sym={has_symbol}): {clean_it[:60]}")
    if len(items) > 3:
        print(f"  ... and {len(items)-3} more items")
