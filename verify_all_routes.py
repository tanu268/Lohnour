import urllib.request
import re
import ssl

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

routes = [
    "/",
    "/services/",
    "/about/",
    "/contact/",
    "/blog/",
    "/guides/",
    "/services/corporate/",
    "/coaching/",
    "/coaching/agentic-ai-foundations/",
    "/coaching/individual/",
    "/coaching/group/",
    "/faqs/"
]

base_url = "https://pro.lohnour.com"

print(f"{'Route':<25} | {'HTTP':<5} | {'Size(B)':<8} | {'Title':<35} | {'Main Content Len'}")
print("-" * 95)

for r in routes:
    url = base_url + r
    req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            body = resp.read().decode("utf-8", errors="replace")
            status = resp.status
    except urllib.error.HTTPError as e:
        status = e.code
        body = e.read().decode("utf-8", errors="replace")
    except Exception as e:
        print(f"{r:<25} | ERR: {e}")
        continue

    title_match = re.search(r"<title>(.*?)</title>", body, re.IGNORECASE)
    title = title_match.group(1).strip() if title_match else "NO_TITLE"
    
    main_match = re.search(r"<main[^>]*>(.*?)</main>", body, re.IGNORECASE | re.DOTALL)
    main_len = len(main_match.group(1).strip()) if main_match else 0
    
    print(f"{r:<25} | {status:<5} | {len(body):<8} | {title[:35]:<35} | {main_len} chars")
