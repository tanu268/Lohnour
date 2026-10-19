import mysql.connector
import json

try:
    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="lohnour_old"
    )
    cursor = conn.cursor(dictionary=True)
    print("SUCCESS: Connected to local database lohnour_old\n")
    
    # 1. Options
    cursor.execute("SELECT option_name, option_value FROM wp_options WHERE option_name IN ('siteurl', 'home', 'permalink_structure', 'template', 'stylesheet')")
    options = {row['option_name']: row['option_value'] for row in cursor.fetchall()}
    print("=== WP OPTIONS ===")
    for k, v in options.items():
        print(f"  {k}: {v}")
    print()
    
    # 2. All Published / Draft Pages
    cursor.execute("""
        SELECT ID, post_name, post_title, post_type, post_status, post_parent, LENGTH(post_content) as content_len
        FROM wp_posts 
        WHERE post_type IN ('page', 'post', 'lohnour_service', 'lohnour_workshop', 'user_guide')
        ORDER BY post_type, post_parent, ID
    """)
    posts = cursor.fetchall()
    
    print(f"=== ALL POSTS / PAGES ({len(posts)} total) ===")
    print(f"{'ID':<6} | {'Type':<18} | {'Parent':<6} | {'Slug':<25} | {'Status':<8} | {'Len':<6} | {'Title'}")
    print("-" * 100)
    for p in posts:
        print(f"{p['ID']:<6} | {p['post_type']:<18} | {p['post_parent']:<6} | {p['post_name']:<25} | {p['post_status']:<8} | {p['content_len']:<6} | {p['post_title']}")
    print()
    
    # 3. Postmeta for templates
    page_ids = [str(p['ID']) for p in posts if p['post_type'] == 'page']
    if page_ids:
        cursor.execute(f"""
            SELECT post_id, meta_key, meta_value 
            FROM wp_postmeta 
            WHERE post_id IN ({','.join(page_ids)}) AND meta_key = '_wp_page_template'
        """)
        meta = cursor.fetchall()
        print("=== PAGE TEMPLATES (_wp_page_template) ===")
        for m in meta:
            print(f"  Page ID {m['post_id']}: {m['meta_value']}")
        print()

    # 4. Specific Route Slugs Inspection
    target_slugs = ['services', 'corporate', 'coaching', 'individual', 'group', 'about', 'contact', 'guides', 'blog', 'home']
    cursor.execute(f"""
        SELECT ID, post_name, post_title, post_type, post_status, post_parent, LENGTH(post_content) as content_len
        FROM wp_posts 
        WHERE post_name IN ({','.join(['%s']*len(target_slugs))})
    """, target_slugs)
    matched = cursor.fetchall()
    print("=== MATCHED SLUGS IN WP_POSTS ===")
    for m in matched:
        print(f"  Slug '{m['post_name']}': ID={m['ID']}, Type={m['post_type']}, Title='{m['post_title']}', Parent={m['post_parent']}, Status={m['post_status']}")
        
    conn.close()

except Exception as e:
    print(f"ERROR connecting with mysql-connector: {e}")
    # Fallback to sqlite or raw reading if needed
