import re

with open("d:/lohnourpro/lohnour_old_clean.sql", "r", encoding="utf-16le") as f:
    sql = f.read()

# Let's search for all occurrences of (ID, ..., 'page') or (ID, ..., 'lohnour_service')
# In MySQL, post_type is towards the end: ...,0,'page','',0)
page_matches = re.findall(r"\((\d+),(\d+),'[^']*','[^']*','(?:\\.|[^'])*','((?:\\.|[^'])*)','(?:\\.|[^'])*','([^']*)','[^']*','[^']*','(?:\\.|[^'])*','([^']*)','(?:\\.|[^'])*','(?:\\.|[^'])*','[^']*','[^']*','(?:\\.|[^'])*',(\d+),'[^']*',(\d+),'([^']*)'", sql)

print(f"Matched {len(page_matches)} posts:\n")
print(f"{'ID':<6} | {'Type':<18} | {'Parent':<6} | {'Status':<8} | {'Slug':<30} | {'Title'}")
print("-" * 110)

for m in page_matches:
    post_id, author, title, status, slug, parent, menu_order, post_type = m
    if post_type in ['page', 'lohnour_service', 'lohnour_workshop', 'user_guide', 'post']:
        print(f"{post_id:<6} | {post_type:<18} | {parent:<6} | {status:<8} | {slug:<30} | {title}")
