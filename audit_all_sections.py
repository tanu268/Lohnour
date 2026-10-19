import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# Find HTML between <main> and </main>
main_match = re.search(r'<main[^>]*>(.*?)</main>', text, re.DOTALL)
if not main_match:
    print("Could not find <main>")
    sys.exit(1)

main_html = main_match.group(1)

# Find all headings in order of appearance
headings = re.findall(r'<(h[1-6])[^>]*class="([^"]*)"[^>]*>(.*?)</\1>', main_html, re.DOTALL)
print(f"Total headings in main: {len(headings)}")

for tag, cls, body in headings:
    # clean body
    clean = re.sub(r'<[^>]+>', '', body).strip()
    clean = re.sub(r'<\?php.*?\?>', '', clean).strip()
    if not clean:
        m = re.search(r"esc_html_e\(\s*'([^']+)'", body)
        clean = m.group(1) if m else "..."
    # Collapse whitespace
    clean = " ".join(clean.split())
    print(f"<{tag:<2}> class='{cls:<35}': {clean[:70]}")
