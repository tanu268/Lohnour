import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# Search for all h1, h2, h3, h4, h5 in the file
headings = re.finditer(r'<(h[1-6])([^>]*)>(.*?)</\1>', text, re.DOTALL)

for i, m in enumerate(headings):
    tag = m.group(1)
    attrs = m.group(2).strip()
    body = m.group(3)
    clean = " ".join(re.sub(r'<[^>]+>', '', re.sub(r'<\?php.*?\?>', '', body)).split())
    if not clean:
        esc_m = re.search(r"esc_html_e\(\s*'([^']+)'", body)
        clean = esc_m.group(1) if esc_m else "..."
    # get line number
    line_num = text[:m.start()].count('\n') + 1
    print(f"L{line_num:<4} <{tag}> {attrs:<40} : {clean[:50]}")
