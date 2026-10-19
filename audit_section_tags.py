import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# Find all sections and their headings
sections = re.findall(r'(<(?:header|section)[^>]*id="([^"]*)"[^>]*>)(.*?)(</(?:header|section)>)', text, re.DOTALL)

for start_tag, s_id, body, end_tag in sections:
    print(f"\n--- Section #{s_id} ---")
    headings_in_sec = re.findall(r'<(h[1-6])[^>]*class="([^"]*)"[^>]*>(.*?)</\1>', body, re.DOTALL)
    for tag, cls, h_content in headings_in_sec:
        clean = re.sub(r'<[^>]+>', '', h_content).strip()
        clean = re.sub(r'<\?php.*?\?>', '', clean).strip()
        if not clean:
            m = re.search(r"esc_html_e\(\s*'([^']+)'", h_content)
            clean = m.group(1) if m else "..."
        print(f"  <{tag} class='{cls}'>: {clean[:60]}")
