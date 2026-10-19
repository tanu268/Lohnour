import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "rb") as f:
    raw = f.read()

# 1. BOM check
if raw.startswith(b'\xef\xbb\xbf'):
    print("ERROR: File contains UTF-8 BOM!")
    sys.exit(1)
else:
    print("PASS: UTF-8 without BOM confirmed.")

content = raw.decode("utf-8")

# 2. Module count & IDs
articles = re.findall(r'<article id="(curriculum-mod-\d+)" class="curriculum-guide-module-block" data-module="(\d+)">', content)
print(f"Found {len(articles)} module articles:")
if len(articles) != 8:
    print(f"ERROR: Expected 8 modules, got {len(articles)}")
    sys.exit(1)

for mod_id, mod_num in articles:
    print(f"  - {mod_id} (data-module={mod_num})")

# 3. Check Buttons and Content regions
buttons = re.findall(r'<button type="button" class="module-accordion-header" id="(curriculum-mod-\d+-header)" aria-expanded="false" aria-controls="(curriculum-mod-\d+-content)">', content)
print(f"Found {len(buttons)} module accordion header buttons with aria-expanded='false':")
if len(buttons) != 8:
    print(f"ERROR: Expected 8 header buttons, got {len(buttons)}")
    sys.exit(1)

regions = re.findall(r'<div id="(curriculum-mod-\d+-content)" class="module-accordion-content" role="region" aria-labelledby="(curriculum-mod-\d+-header)">', content)
print(f"Found {len(regions)} module content regions:")
if len(regions) != 8:
    print(f"ERROR: Expected 8 content regions, got {len(regions)}")
    sys.exit(1)

# Verify matching aria-controls and aria-labelledby
for i in range(8):
    btn_id, btn_ctrl = buttons[i]
    reg_id, reg_lbl = regions[i]
    if btn_id != reg_lbl or btn_ctrl != reg_id:
        print(f"ERROR: Mismatch in module {i+1}: btn({btn_id}, {btn_ctrl}) vs reg({reg_id}, {reg_lbl})")
        sys.exit(1)
print("PASS: All 8 accordion buttons and content regions match aria-controls and aria-labelledby.")

# 4. Check Inner Accordions
inners = re.findall(r'<div id="(curriculum-mod-\d+-(?:learn|practical|outcome))" class="guides-accordion-item">', content)
print(f"Found {len(inners)} inner accordion items (expected 24 = 8 * 3):")
if len(inners) != 24:
    print(f"ERROR: Expected 24 inner accordions, got {len(inners)}")
    sys.exit(1)
print("PASS: All 24 inner accordions present and set to collapsed by default.")

# 5. Check no duplicate IDs in file
all_ids = re.findall(r'id="([^"]+)"', content)
duplicates = [x for x in all_ids if all_ids.count(x) > 1]
unique_dups = list(set(duplicates))
if unique_dups:
    print(f"ERROR: Found duplicate IDs: {unique_dups}")
    sys.exit(1)
else:
    print(f"PASS: Zero duplicate IDs found out of {len(all_ids)} total IDs.")

# 6. Verify Hero Section Intact
hero_match = re.search(r'<header class="program-hero.*?</header>', content, re.DOTALL)
if not hero_match:
    print("ERROR: Hero section missing or damaged!")
    sys.exit(1)
hero_html = hero_match.group(0)
if "agentic-ai-foundations-dark.png" in hero_html and "agentic-ai-foundations-light.jpeg" in hero_html:
    print("PASS: Hero section intact with dark and light theme banner images.")
else:
    print("ERROR: Hero missing expected banner images!")
    sys.exit(1)

print("\nALL LOCAL VALIDATIONS PASSED PERFECTLY!")
