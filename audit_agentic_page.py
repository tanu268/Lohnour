import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

print("=" * 60)
print("1. SECTION STRUCTURE AUDIT")
print("=" * 60)
sections = re.findall(r'<(?:header|section)[^>]*id="([^"]*)"[^>]*class="([^"]*)"', text)
for s_id, s_cls in sections:
    print(f"  ID: #{s_id:<25} Class: {s_cls}")

print("\n" + "=" * 60)
print("2. HEADING HIERARCHY AUDIT (HTML)")
print("=" * 60)
headings = re.findall(r'<(h[1-4])[^>]*class="([^"]*)"[^>]*>(.*?)</\1>', text, re.DOTALL)
for tag, cls, body in headings:
    clean_body = re.sub(r'<[^>]+>', '', body).strip()
    clean_body = re.sub(r'<\?php.*?\?>', '', clean_body).strip()
    # If empty after removing php, extract string from esc_html_e
    if not clean_body:
        m = re.search(r"esc_html_e\(\s*'([^']+)'", body)
        if m:
            clean_body = m.group(1)
    print(f"  <{tag}> class='{cls}': {clean_body[:60]}")

print("\n" + "=" * 60)
print("3. TYPOGRAPHY CSS AUDIT")
print("=" * 60)
typo_selectors = [
    'program-hero-title',
    'section-title',
    'module-title-text',
    'module-block-title',
    'build-card-title',
    'outcome-card-title',
    'skill-category-title',
    'industry-card-title',
    'format-card-title',
    'pricing-title',
    'faq-header',
    'cta-title',
    'program-hero-desc',
    'section-subtitle',
    'module-block-overview',
    'card-badge',
    'meta-chip',
    'module-duration-badge'
]

for sel in typo_selectors:
    matches = re.findall(rf'(\.[^{{]*{sel}[^{{]*)\{{([^}}]+)\}}', text)
    print(f"\n--- Selector: {sel} ({len(matches)} rules) ---")
    for s, rules in matches:
        rules_dict = {}
        for line in rules.strip().split(';'):
            line = line.strip()
            if any(k in line for k in ['font-size', 'line-height', 'font-weight', 'letter-spacing', 'margin', 'padding']):
                parts = line.split(':', 1)
                if len(parts) == 2:
                    rules_dict[parts[0].strip()] = parts[1].strip()
        print(f"  {s.strip()}: {rules_dict}")

print("\n" + "=" * 60)
print("4. LISTS AND BULLET AUDIT")
print("=" * 60)
ul_matches = re.findall(r'<ul[^>]*class="([^"]*)"[^>]*>', text)
print(f"Found {len(ul_matches)} <ul> elements:")
for u in set(ul_matches):
    print(f"  ul.{u}")

# Check CSS for lists
list_css = re.findall(r'(\.[^{]*ul[^{]*|\.[^{]*li[^{]*|\.[^{]*list[^{]*)\{([^}]+)\}', text)
print("\nCSS rules for lists:")
for sel, r in list_css:
    if any(k in r for k in ['list-style', 'padding', 'margin', 'gap']):
        print(f"  {sel.strip()}: {[l.strip() for l in r.split(';') if any(k in l for k in ['list-style', 'padding', 'margin', 'gap'])]}")
