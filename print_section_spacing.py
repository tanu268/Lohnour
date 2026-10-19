import re

with open('current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    content = f.read()

style = re.search(r'<style[^>]*>(.*?)</style>', content, re.DOTALL).group(1)

# Clean comments and look at key sections
sections_of_interest = [
    ('.program-section', 'General Section Container'),
    ('.program-facts', 'Program Facts'),
    ('.program-anchor-nav', 'Anchor Nav'),
    ('.section-heading-wrapper', 'Section Headings'),
    ('.overview-grid', 'Overview'),
    ('.philosophy-card', 'Philosophy Card'),
    ('.module-summary', 'Module Summary'),
    ('.curriculum-guide', 'Curriculum Guide Layout'),
    ('.guides-accordion', 'Accordion items'),
    ('.build-', 'Build progression'),
    ('.outcomes-', 'Outcomes'),
    ('.career-', 'Career / Skills / Applications'),
    ('.coaching-flow', 'Coaching format'),
    ('.scope-', 'Scope included/not included'),
    ('.pricing-', 'Pricing / Tuition'),
    ('.faq-', 'FAQs'),
    ('.cta-', 'Final CTA')
]

for selector, label in sections_of_interest:
    print(f"\n==================== {label} ({selector}) ====================")
    # find rules mentioning selector
    matches = re.findall(rf'([^{{}}]*{re.escape(selector)}[^{{}}]*)\{{([^}}]+)\}}', style)
    for sel, body in matches:
        # print if has padding, margin, gap
        if any(k in body for k in ['padding', 'margin', 'gap', 'height']):
            b = " ".join(body.split())
            print(f"  {sel.strip()} -> {b}")
