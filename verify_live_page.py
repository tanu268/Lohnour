import urllib.request
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

url = "https://pro.lohnour.com/coaching/agentic-ai-foundations/"
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
html = urllib.request.urlopen(req).read().decode('utf-8')

print("=== CHECK 1: CTA BUTTONS ===")
buttons = re.findall(r'<a\s+href="[^"]*"\s+class="btn[^"]*"[^>]*>.*?</a>', html, re.DOTALL)
for idx, b in enumerate(buttons, 1):
    cleaned = re.sub(r'\s+', ' ', b).strip()
    print(f"Button {idx}: {cleaned}")

print("\n=== CHECK 2: HIGHLIGHTS SECTION ===")
hl_match = re.search(r'<div class="overview-highlights.*?</ul>', html, re.DOTALL)
if hl_match:
    print(re.sub(r'\s+', ' ', hl_match.group(0)).strip())
else:
    print("NO HIGHLIGHTS FOUND!")

print("\n=== CHECK 3: ANY MALFORMED HTML IN BUTTONS ===")
malformed = re.findall(r'&quot;\s*class=&quot;', html)
print(f"Occurrences of '&quot; class=&quot;': {len(malformed)}")
