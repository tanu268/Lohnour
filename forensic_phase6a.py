import urllib.request
import urllib.parse
import ssl
import re
import time
import subprocess
import json

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

routes = [
    "/",
    "/services/",
    "/services/corporate/",
    "/coaching/",
    "/coaching/individual/",
    "/coaching/group/",
    "/guides/",
    "/about/",
    "/contact/",
    "/blog/"
]

base_url = "https://pro.lohnour.com"

print("==================================================")
print("1. ROUTE HTTP METRICS & HTML ANALYSIS")
print("==================================================")

route_data = {}

for r in routes:
    url = base_url + r
    start = time.time()
    try:
        req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"})
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            elapsed = time.time() - start
            body = resp.read().decode("utf-8", errors="replace")
            status = resp.status
            content_type = resp.headers.get("Content-Type", "")
            content_length = len(body)
            
            # Extract tags
            title_match = re.search(r"<title>(.*?)</title>", body, re.IGNORECASE)
            title = title_match.group(1) if title_match else "NONE"
            
            body_tag_match = re.search(r"<body([^>]*)>", body, re.IGNORECASE)
            body_attrs = body_tag_match.group(1) if body_tag_match else "NONE"
            
            main_match = re.search(r"<main[^>]*>(.*?)</main>", body, re.IGNORECASE | re.DOTALL)
            main_content = main_match.group(1).strip() if main_match else "NO <main> TAG"
            
            header_match = re.search(r"<header[^>]*>(.*?)</header>", body, re.IGNORECASE | re.DOTALL)
            has_header = bool(header_match)
            
            footer_match = re.search(r"<footer[^>]*>(.*?)</footer>", body, re.IGNORECASE | re.DOTALL)
            has_footer = bool(footer_match)
            
            route_data[r] = {
                "status": status,
                "elapsed": round(elapsed, 3),
                "length": content_length,
                "title": title,
                "body_attrs": body_attrs,
                "has_header": has_header,
                "has_footer": has_footer,
                "main_length": len(main_content) if main_match else 0,
                "main_snippet": main_content[:200] if main_match else "NO <main>",
            }
            
            print(f"Route: {r:22} | HTTP {status} | {content_length:6} bytes | {elapsed:.2f}s | Title: {title[:30]} | Main: {len(main_content)} chars")
            if main_match:
                print(f"   Main snippet: {repr(main_content[:120])}")
            else:
                print(f"   NO <main> tag found!")
    except Exception as e:
        print(f"Route: {r:22} | ERROR: {e}")

print("\n==================================================")
print("SAVE HTML SAMPLES FOR DEEP COMPARISON")
print("==================================================")
for path in ["/", "/services/", "/about/", "/contact/"]:
    url = base_url + path
    try:
        req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            content = resp.read().decode("utf-8", errors="replace")
            fname = "/tmp/sample_" + path.replace("/", "_") + ".html"
            with open(fname, "w", encoding="utf-8") as f:
                f.write(content)
            print(f"Saved {path} -> {fname} ({len(content)} bytes)")
    except Exception as e:
        print(f"Failed to save {path}: {e}")
