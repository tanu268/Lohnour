import requests
from bs4 import BeautifulSoup
import re
import time
from urllib.parse import urljoin, urlparse

BASE_URL = 'http://localhost:8081'

routes = [
    '/',
    '/programs/',
    '/services/',
    '/services/corporate/',
    '/corporate/',
    '/coaching/',
    '/coaching/individual/',
    '/coaching/group/',
    '/individual/',
    '/group/',
    '/guides/',
    '/about/',
    '/contact/',
    '/blog/',
    '/privacy-policy/',
    # CPTs
    '/programs/ai-for-executive-leadership/',
    '/programs/team-workflow-automation/',
    '/programs/agentic-ai-developer-track/',
    '/programs/generative-ai-productivity/',
    '/programs/practical-prompt-engineering/',
    '/programs/ai-in-academic-curricula/',
    # Guides
    '/guides/introduction-to-crewai/',
    '/guides/designing-agent-communications/',
    '/guides/deploying-agents-to-production/',
    '/guides/monitoring-agent-decisions/',
    '/guides/test-parent-guide/',
    '/guides/agentic-ai-mastery/',
    '/guides/what-is-an-ai-agent/',
    '/guides/setting-up-langchain/',
    '/guides/giving-agents-browser-access/',
    # Taxonomy
    '/programs/pathway/corporate-ai-training/',
    '/programs/pathway/faculty-development/',
    '/programs/pathway/professional-ai-upskilling/',
    '/programs/pathway/student-ai-programs/'
]

print("=== 1. ROUTE MATRIX ===")
results = []
for route in routes:
    try:
        url = BASE_URL + route
        r = requests.get(url, allow_redirects=True)
        final_url = r.url
        chain = " -> ".join([resp.url for resp in r.history] + [final_url]) if r.history else "No redirect"
        soup = BeautifulSoup(r.text, 'html.parser')
        title = soup.title.string.strip() if soup.title else 'No Title'
        
        # PHP Error Check
        php_error = "Fatal error" in r.text or "Warning:" in r.text or "Notice:" in r.text
        wp_error = "WordPress database error" in r.text
        
        status = r.status_code
        time.sleep(0.5)
        if r.history:
            classification = "PASS WITH REDIRECT" if status == 200 else "FAIL"
        else:
            classification = "PASS" if status == 200 else "FAIL"
        
        main_content = "Yes" if soup.find('main') else "No"
            
        print(f"Route: {route}")
        print(f"  Status: {status} | Class: {classification}")
        print(f"  Final: {final_url}")
        print(f"  Chain: {chain}")
        print(f"  Title: {title}")
        print(f"  Main Content: {main_content}")
        print(f"  PHP Error: {php_error} | WP Error: {wp_error}")
        print("---")
    except Exception as e:
        print(f"Route {route} FAILED: {e}")

print("\n=== 2. LINK CRAWL & ASSET AUDIT ===")
crawl_targets = ['/', '/programs/', '/services/', '/coaching/', '/guides/']
seen_links = set()
broken_links = []
suspicious_links = []
asset_issues = []

for target in crawl_targets:
    time.sleep(0.5)
    r = requests.get(BASE_URL + target)
    soup = BeautifulSoup(r.text, 'html.parser')
    
    # Check links
    for a in soup.find_all('a', href=True):
        href = a['href']
        if href not in seen_links:
            seen_links.add(href)
            if '127.0.0.1' in href or 'localhost:8080' in href:
                suspicious_links.append(f"Found in {target}: {href}")
    
    # Check assets
    for tag, attr in [('link', 'href'), ('script', 'src'), ('img', 'src')]:
        for el in soup.find_all(tag, **{attr: True}):
            url = el[attr]
            if '127.0.0.1' in url or 'localhost:8080' in url:
                asset_issues.append(f"Bad asset in {target}: {url}")
            
print("Suspicious Links:")
for l in suspicious_links: print(l)
if not suspicious_links: print("None")

print("\nAsset Issues (localhost/127.0.0.1):")
for a in asset_issues: print(a)
if not asset_issues: print("None")
