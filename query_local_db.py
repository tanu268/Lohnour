import subprocess

def run_query(db, sql):
    cmd = [
        "D:\\xampp\\mysql\\bin\\mysql.exe",
        "-P", "3307",
        "-h", "127.0.0.1",
        "-u", "root",
        db,
        "-e", sql
    ]
    res = subprocess.run(cmd, capture_output=True, text=True)
    return res.stdout, res.stderr

print("==================================================")
print("1. QUERYING wp_posts IN lohnour_old FOR TARGET SLUGS")
print("==================================================")
sql = """
SELECT ID, post_name, post_title, post_type, post_status, post_parent 
FROM wp_posts 
WHERE post_name IN ('services', 'corporate', 'coaching', 'individual', 'group', 'about', 'contact', 'blog', 'home', 'guides');
"""
out, err = run_query("lohnour_old", sql)
print("STDOUT:\n" + out)

print("==================================================")
print("2. QUERYING ALL PAGES IN lohnour_old")
print("==================================================")
sql_pages = """
SELECT ID, post_name, post_title, post_type, post_status, post_parent 
FROM wp_posts 
WHERE post_type = 'page';
"""
out, err = run_query("lohnour_old", sql_pages)
print("STDOUT:\n" + out)

print("==================================================")
print("3. QUERYING _wp_page_template IN lohnour_old")
print("==================================================")
sql_meta = """
SELECT post_id, meta_key, meta_value 
FROM wp_postmeta 
WHERE meta_key = '_wp_page_template';
"""
out, err = run_query("lohnour_old", sql_meta)
print("STDOUT:\n" + out)

print("==================================================")
print("4. QUERYING wp_posts IN OTHER LOCAL DATABASES")
print("==================================================")
for other_db in ["lohnour_current", "lohnour_pro"]:
    print(f"--- Database: {other_db} ---")
    out, err = run_query(other_db, sql)
    print(out if out else "No matches or error: " + err)
