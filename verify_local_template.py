import re

with open(r"d:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php", "r", encoding="utf-8") as f:
    text = f.read()

modules = re.findall(r'<article id="(curriculum-mod-\d+)"', text)
print("Found modules:", modules)
assert len(modules) == 8, f"Expected 8 modules, got {len(modules)}"

for mod_id in modules:
    pattern = r'<article id="' + mod_id + r'"[\s\S]*?</article>'
    m = re.search(pattern, text)
    assert m, f"Module block not found for {mod_id}"
    block = m.group(0)
    assert "module-kicker" in block, f"kicker missing in {mod_id}"
    assert "module-duration-badge" in block, f"badge missing in {mod_id}"
    assert "module-block-title" in block, f"title missing in {mod_id}"
    assert "module-block-overview" in block, f"overview missing in {mod_id}"
    assert "What You'll Learn" in block, f"learn missing in {mod_id}"
    assert "Practical" in block, f"practical missing in {mod_id}"
    assert "Module Outcome" in block, f"outcome missing in {mod_id}"

print("All 8 modules and all required components are 100% present and intact!")
