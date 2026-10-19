import sys, re
sys.stdout.reconfigure(encoding='utf-8')

with open(r'D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

modules = re.findall(r'<article id="(curriculum-mod-\d+)" class="([^"]*)"[^>]*>(.*?)</article>', text, re.DOTALL)
print(f'Found {len(modules)} module articles:')

for mod_id, cls, body in modules:
    kicker = re.search(r'class="module-kicker">([^<]+)<', body)
    duration = re.search(r'class="module-duration-badge">([^<]+)<', body)
    title_match = re.search(r'class="module-block-title">(.*?)</h3>', body, re.DOTALL)
    if title_match:
        t_raw = title_match.group(1).strip()
        # extract string from esc_html_e if present
        m_esc = re.search(r"esc_html_e\(\s*'([^']+)'", t_raw)
        t_txt = m_esc.group(1) if m_esc else t_raw
    else:
        t_txt = 'None'
    
    k_txt = kicker.group(1).strip() if kicker else 'None'
    d_txt = duration.group(1).strip() if duration else 'None'
    
    # Check inner active items
    active_inners = re.findall(r'class="guides-accordion-item\s+active"', body)
    
    print(f'[{mod_id}] {k_txt} | {t_txt} | {d_txt} | active_inners={len(active_inners)}')
