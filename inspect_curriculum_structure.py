import sys

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    lines = f.readlines()

print(f"Total lines: {len(lines)}")

curriculum_start = -1
curriculum_end = -1

for i, line in enumerate(lines):
    if 'id="curriculum"' in line:
        curriculum_start = i
    if curriculum_start != -1 and '</section>' in line and i > curriculum_start and curriculum_end == -1:
        curriculum_end = i

print(f"Curriculum section spans lines {curriculum_start+1} to {curriculum_end+1}")

# Let's inspect CSS lines related to curriculum
css_lines = []
for i, line in enumerate(lines):
    if 'curriculum' in line.lower() and i < curriculum_start:
        css_lines.append((i+1, line.rstrip()))

print(f"\nFound {len(css_lines)} CSS lines referencing curriculum (showing first 30):")
for ln, l in css_lines[:30]:
    print(f"{ln}: {l}")

# Let's inspect JS lines related to curriculum
js_lines = []
for i, line in enumerate(lines):
    if ('curriculum' in line.lower() or 'accordion' in line.lower()) and i > curriculum_end:
        js_lines.append((i+1, line.rstrip()))

print(f"\nFound {len(js_lines)} JS lines referencing curriculum/accordion:")
for ln, l in js_lines:
    print(f"{ln}: {l}")
