import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"
with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# Find all 8 modules
modules = re.findall(r'<article id="curriculum-mod-(\d+)" class="curriculum-guide-module-block" data-module="(\d+)">(.*?)</article>', text, re.DOTALL)
print(f"Total curriculum modules found: {len(modules)}")

for mod_num, data_mod, mod_html in modules:
    print(f"\n--- MODULE {mod_num} (data-module={data_mod}) ---")
    # 1. Header Button
    header_btn = re.search(r'<button [^>]*class="module-accordion-header"[^>]*>(.*?)</button>', mod_html, re.DOTALL)
    if header_btn:
        title = re.search(r'<span class="module-title-text">(.*?)</span>', header_btn.group(1), re.DOTALL)
        clean_title = re.sub(r'<\?php.*?\?>', '', title.group(1)).strip() if title else "NONE"
        dur = re.search(r'<span class="module-duration-badge">(.*?)</span>', header_btn.group(1), re.DOTALL)
        clean_dur = dur.group(1).strip() if dur else "NONE"
        print(f"  Header: Title='{clean_title}' | Duration='{clean_dur}'")
    
    # 2. Overview paragraph
    overview = re.search(r'<p class="module-block-overview">(.*?)</p>', mod_html, re.DOTALL)
    if overview:
        clean_ov = re.sub(r'<\?php.*?\?>', '', overview.group(1)).strip()
        print(f"  Overview: '{clean_ov[:60]}...'")
    else:
        print("  WARNING: No module-block-overview found!")

    # 3. Inner Accordions
    inners = re.findall(r'<div id="curriculum-mod-\d+-(learn|practical|outcome)" class="guides-accordion-item">(.*?)</div>\s*(?=<div id="curriculum-mod-\d+-|$)', mod_html, re.DOTALL)
    print(f"  Inner accordions count: {len(inners)}")
    for inner_type, inner_html in inners:
        title_m = re.search(r'<span class="accordion-title">(.*?)</span>', inner_html, re.DOTALL)
        clean_inner_title = re.sub(r'<\?php.*?\?>', '', title_m.group(1)).strip() if title_m else "NONE"
        has_tags = '<ul class="guide-topic-tags">' in inner_html
        has_activity = '<div class="guide-activity-box">' in inner_html
        has_outcome = '<div class="guide-outcome-box">' in inner_html
        has_progression = '<div class="progression-pill-row">' in inner_html
        print(f"    - Type: {inner_type:<10} | Title: '{clean_inner_title}' | tags={has_tags}, activity={has_activity}, outcome={has_outcome}, progression={has_progression}")
