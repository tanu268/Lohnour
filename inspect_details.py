import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

with open(r'current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

# Let's inspect what-youll-build section
sec_build = re.search(r'<section id="what-youll-build".*?</section>', text, re.DOTALL)
if sec_build:
    print("--- WHAT YOULL BUILD ---")
    cards = re.findall(r'<div class="build-card[^"]*".*?</div>\s*</div>', sec_build.group(0), re.DOTALL)
    print(f"Build cards count: {len(cards)}")
    for c in cards:
        t = re.search(r'<h3>(.*?)</h3>', c)
        print("  Build card:", re.sub(r'<[^>]+>', '', t.group(1)).strip() if t else "No title")

# Let's inspect outcomes section
sec_outcomes = re.search(r'<section id="outcomes".*?</section>', text, re.DOTALL)
if sec_outcomes:
    print("--- OUTCOMES ---")
    h3s = re.findall(r'<h3>(.*?)</h3>', sec_outcomes.group(0))
    for h in h3s:
        print("  Outcome h3:", re.sub(r'<[^>]+>', '', h).strip())

# Let's inspect format section
sec_format = re.search(r'<section id="format".*?</section>', text, re.DOTALL)
if sec_format:
    print("--- FORMAT ---")
    h3s = re.findall(r'<h3>(.*?)</h3>', sec_format.group(0))
    for h in h3s:
        print("  Format h3:", re.sub(r'<[^>]+>', '', h).strip())

# Let's inspect FAQs section
sec_faqs = re.search(r'<section id="faqs".*?</section>', text, re.DOTALL)
if sec_faqs:
    print("--- FAQS ---")
    faq_items = re.findall(r'<div class="faq-item".*?</div>\s*</div>\s*</div>', sec_faqs.group(0), re.DOTALL)
    print(f"FAQ items count: {len(faq_items)}")
    for fi in faq_items:
        q = re.search(r'<span class="faq-question-text">(.*?)</span>', fi)
        print("  FAQ Q:", re.sub(r'<[^>]+>', '', q.group(1)).strip() if q else "No Q")
