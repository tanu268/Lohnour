import re

with open("d:/lohnourpro/lohnour_old_clean.sql", "r", encoding="utf-16le") as f:
    sql = f.read()

# Search for _wp_page_template
meta_matches = re.findall(r"\((\d+),\s*(\d+),\s*'_wp_page_template',\s*'([^']*)'\)", sql)

print(f"Found {len(meta_matches)} _wp_page_template records:\n")
for m in meta_matches:
    meta_id, post_id, tpl = m
    print(f"  Post ID {post_id:<5} -> Template: '{tpl}'")
