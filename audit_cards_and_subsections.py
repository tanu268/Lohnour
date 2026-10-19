import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# Let's search for all heading tags h1-h6 in the file
all_headings = re.findall(r'<(h[1-6])[^>]*>(.*?)</\1>', text, re.DOTALL)
print(f"Total raw headings in file: {len(all_headings)}")

# Let's group headings by section
section_matches = re.finditer(r'<!--\s*(\d+\.[^>]+?)\s*-->\s*<(?:section|header)[^>]*id="([^"]*)"', text)

sections_list = []
for m in section_matches:
    sections_list.append((m.start(), m.group(1), m.group(2)))

print(f"\nFound {len(sections_list)} numbered section comments:")
for start_pos, comment, s_id in sections_list:
    print(f"  {comment:<50} (id='#{s_id}')")

# For each section, let's print all card titles, strong tags, list elements
for i in range(len(sections_list)):
    start_pos = sections_list[i][0]
    end_pos = sections_list[i+1][0] if i+1 < len(sections_list) else text.find('</main>')
    sec_text = text[start_pos:end_pos]
    sec_name = sections_list[i][1]
    sec_id = sections_list[i][2]
    
    print(f"\n==========================================")
    print(f"SECTION: {sec_name} (id='#{sec_id}')")
    print(f"==========================================")
    
    # Headings
    h_tags = re.findall(r'<(h[1-6])[^>]*class="([^"]*)"[^>]*>(.*?)</\1>', sec_text, re.DOTALL)
    for tag, cls, body in h_tags:
        clean = " ".join(re.sub(r'<[^>]+>', '', re.sub(r'<\?php.*?\?>', '', body)).split())
        if not clean:
            m = re.search(r"esc_html_e\(\s*'([^']+)'", body)
            clean = m.group(1) if m else "..."
        print(f"  [{tag}.{cls}]: {clean[:60]}")
        
    # Card / Item titles that might not be h-tags (e.g. strong, span, h4, etc.)
    cards = re.findall(r'class="([^"]*(?:card-title|title|step|badge|name)[^"]*)"', sec_text)
    if cards:
        print(f"  Classes with title/step/badge: {set(cards)}")
        
    # Lists
    lists = re.findall(r'<ul[^>]*class="([^"]*)"[^>]*>(.*?)</ul>', sec_text, re.DOTALL)
    if lists:
        print(f"  Lists ({len(lists)}):")
        for u_cls, u_body in lists:
            li_count = len(re.findall(r'<li[^>]*>', u_body))
            print(f"    - ul.{u_cls} with {li_count} <li> items")
