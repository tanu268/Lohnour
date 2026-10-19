import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# 1. Extract all section titles and their tags
print("=== SECTION HEADINGS IN DOM ===")
section_blocks = re.findall(r'<section id="([^"]+)"[^>]*>(.*?)</section>', text, re.DOTALL)
for s_id, s_body in section_blocks:
    h2 = re.search(r'<h2 class="section-title">(.*?)</h2>', s_body, re.DOTALL)
    badge = re.search(r'<span class="card-badge">(.*?)</span>', s_body, re.DOTALL)
    subtitle = re.search(r'<p class="section-subtitle">(.*?)</p>', s_body, re.DOTALL)
    
    h2_text = h2.group(1).strip() if h2 else "NO H2"
    badge_text = badge.group(1).strip() if badge else "NO BADGE"
    
    # Clean PHP
    h2_clean = re.sub(r'<\?php.*?\?>', '', h2_text).strip()
    if not h2_clean:
        m = re.search(r"esc_html_e\(\s*'([^']+)'", h2_text)
        h2_clean = m.group(1) if m else h2_text
        
    badge_clean = re.sub(r'<\?php.*?\?>', '', badge_text).strip()
    if not badge_clean:
        m = re.search(r"esc_html_e\(\s*'([^']+)'", badge_text)
        badge_clean = m.group(1) if m else badge_text

    print(f"Section #{s_id}:")
    print(f"  Badge: {badge_clean}")
    print(f"  H2: {h2_clean}")
    
    # Sub-headings inside section
    sub_headings = re.findall(r'<(h[345])[^>]*class="([^"]*)"[^>]*>(.*?)</\1>', s_body, re.DOTALL)
    for sh_tag, sh_cls, sh_body in sub_headings[:5]: # show up to 5
        sh_clean = re.sub(r'<\?php.*?\?>', '', re.sub(r'<[^>]+>', '', sh_body)).strip()
        if not sh_clean:
            m = re.search(r"esc_html_e\(\s*'([^']+)'", sh_body)
            sh_clean = m.group(1) if m else "..."
        print(f"    <{sh_tag} class='{sh_cls}'>: {sh_clean[:50]}")
    if len(sub_headings) > 5:
        print(f"    ... and {len(sub_headings)-5} more <{sh_tag}> headings")

# 2. Extract CSS typography font sizes
print("\n=== CSS FONT SIZES & WEIGHTS ===")
css_rules = re.findall(r'(\.[a-zA-Z0-9_\-\.\:\s,>\[\]="]+)\{([^}]+)\}', text)
for sel, r in css_rules:
    if 'font-size' in r:
        fs = re.search(r'font-size:\s*([^;]+);', r)
        fw = re.search(r'font-weight:\s*([^;]+);', r)
        lh = re.search(r'line-height:\s*([^;]+);', r)
        print(f"{sel.strip():<55} | fs: {fs.group(1).strip() if fs else 'N/A':<20} | fw: {fw.group(1).strip() if fw else 'N/A':<8} | lh: {lh.group(1).strip() if lh else 'N/A'}")
