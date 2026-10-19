import sys, re
sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Let's inspect the exact lines of each module article in the file
pattern = r'(<article id="curriculum-mod-(\d+)" class="curriculum-guide-module-block" data-module="\2">)(.*?)(</article>)'
matches = list(re.finditer(pattern, content, re.DOTALL))
print(f"Matched {len(matches)} module articles.")

for m in matches:
    mod_num = m.group(2)
    mod_body = m.group(3)
    
    # Check headers
    kicker = re.search(r'<span class="module-kicker">([^<]+)</span>', mod_body)
    duration = re.search(r'<span class="module-duration-badge">([^<]+)</span>', mod_body)
    title = re.search(r'<h3 class="module-block-title">(.*?)</h3>', mod_body, re.DOTALL)
    overview = re.search(r'<p class="module-block-overview">(.*?)</p>', mod_body, re.DOTALL)
    accordions = re.search(r'(<div class="guides-accordion-container">.*)', mod_body, re.DOTALL)
    
    print(f"Module {mod_num}:")
    print(f"  Kicker: {kicker.group(1) if kicker else 'N/A'}")
    print(f"  Duration: {duration.group(1) if duration else 'N/A'}")
    print(f"  Title found: {bool(title)}")
    print(f"  Overview found: {bool(overview)}")
    print(f"  Accordions found: {bool(accordions)}")
