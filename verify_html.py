import urllib.request
import ssl
import re

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

base = "https://pro.lohnour.com"
routes = ["/", "/services/", "/coaching/", "/about/", "/contact/", "/blog/", "/guides/"]

for r in routes:
    url = base + r
    resp = urllib.request.urlopen(url, context=ctx)
    html = resp.read().decode('utf-8')
    status = resp.status
    title = re.search(r'<title>(.*?)</title>', html)
    title_str = title.group(1) if title else "NO_TITLE"
    
    # Check services dropdown in navbar
    has_dropdown = '<ul class="sub-menu">' in html and 'Training' in html and 'Coaching' in html
    
    print(f"Route: {r:<15} | Status: {status} | Dropdown: {has_dropdown} | Title: {title_str[:40]}")
