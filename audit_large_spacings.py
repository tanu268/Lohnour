import re

with open('current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

style = re.search(r'<style[^>]*>(.*?)</style>', text, re.DOTALL).group(1)

# Find all selectors and declarations
rules = re.findall(r'([^{}]+)\{([^{}]+)\}', style)
print(f"Total CSS rules in template: {len(rules)}")

# Categorize rules with large spacings
spacing_tokens = ['--spacing-xxl', '--spacing-xl', '--spacing-lg', '--spacing-md']
for sel, body in rules:
    sel_clean = sel.strip().replace('\n', ' ')
    body_clean = ' '.join(body.split())
    if any(tok in body for tok in spacing_tokens) or any(px in body for px in ['100px', '80px', '60px', '50px', '40px']):
        print(f"{sel_clean} \n   -> {body_clean}\n")
