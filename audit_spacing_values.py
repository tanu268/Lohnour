import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    text = f.read()

# Extract scoped styles section
style_match = re.search(r'<style>(.*?)</style>', text, re.DOTALL)
if not style_match:
    print("Could not find <style>")
    sys.exit(1)

css = style_match.group(1)

# Find all margin-bottom, padding, gap rules
rules = re.findall(r'(\.[a-zA-Z0-9_\-\.\:\s,>\[\]="]+)\{([^}]+)\}', css)

spacing_keys = ['padding', 'margin', 'gap', 'scroll-margin-top']

print("=== CURRENT SPACING DEFINITIONS ===")
for sel, body in rules:
    found = []
    for line in body.split(';'):
        line = line.strip()
        if any(line.startswith(k) for k in spacing_keys):
            found.append(line)
    if found and any(k in sel for k in ['program-section', 'section-heading', 'hero', 'overview', 'curriculum', 'scope', 'cta', 'card']):
        print(f"{sel.strip()}:")
        for f in found:
            print(f"    {f}")
