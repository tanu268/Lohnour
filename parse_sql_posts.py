import re

try:
    with open("d:/lohnourpro/lohnour_old_clean.sql", "r", encoding="utf-16le") as f:
        sql = f.read()
except:
    with open("d:/lohnourpro/lohnour_old_clean.sql", "r", encoding="utf-8", errors="replace") as f:
        sql = f.read()

# Let's search for lines containing wp_posts
lines = [l for l in sql.splitlines() if "INSERT INTO `wp_posts`" in l]
print(f"Found {len(lines)} lines with INSERT INTO `wp_posts`")

# Let's search for specific slugs in the SQL
for slug in ["services", "corporate", "coaching", "individual", "group", "about", "contact", "guides", "blog", "home"]:
    count = sql.count(f"'{slug}'")
    print(f"Slug '{slug}' occurrences as literal in SQL: {count}")
    # Also find post titles
    matches = re.findall(rf"\(\d+,\d+,[^,]+,[^,]+,'([^']+)',[^,]+,'([^']+)',[^,]+,[^,]+,[^,]+,'{slug}',[^,]+,[^,]+,[^,]+,[^,]+,[^,]+,(\d+),[^,]+,(\d+),'([^']+)'", sql)
    if matches:
        print(f"  -> MATCH for slug '{slug}': {matches}")
