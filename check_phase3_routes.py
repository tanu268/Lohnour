import urllib.request
import ssl
import re

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
    "/coaching/",
    "/coaching/agentic-ai-foundations/"
]

base_url = "https://pro.lohnour.com"

print(f"{'Route':<36} | {'Status':<6} | {'Bytes':<8} | {'Title'}")
print("-" * 80)

all_passed = True
for r in routes:
    url = base_url + r
    req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=15) as resp:
            content = resp.read().decode("utf-8", errors="replace")
            m = re.search(r"<title>(.*?)</title>", content, re.IGNORECASE)
            title = m.group(1).strip() if m else "No title"
            print(f"{r:<36} | {resp.status:<6} | {len(content):<8} | {title[:35]}")
            if resp.status != 200:
                all_passed = False
    except Exception as e:
        print(f"{r:<36} | ERR    | 0        | {e}")
        all_passed = False

if all_passed:
    print("-" * 80)
    print("ALL ROUTES RETURNED HTTP 200 OK!")
