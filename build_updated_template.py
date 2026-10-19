import sys, re

sys.stdout.reconfigure(encoding='utf-8')

file_path = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Transform Module Articles
# Pattern to find each article
article_pattern = re.compile(
    r'<article id="curriculum-mod-(\d+)" class="curriculum-guide-module-block" data-module="\1">(.*?)</article>',
    re.DOTALL
)

matches = list(article_pattern.finditer(content))
print(f"Found {len(matches)} module articles to transform.")

module_titles = {
    1: ("01", "Module 1 — ", "AI, LLM & Agentic Foundations", "3 Hours"),
    2: ("02", "Module 2 — ", "Prompt Engineering & LLM Control", "3 Hours"),
    3: ("03", "Module 3 — ", "Generative AI & Multimodal Systems", "2 Hours"),
    4: ("04", "Module 4 — ", "AI Workflows & Automation with n8n", "4 Hours"),
    5: ("05", "Module 5 — ", "Python for AI Systems", "4 Hours"),
    6: ("06", "Module 6 — ", "Building AI Agents", "6 Hours"),
    7: ("07", "Module 7 — ", "Agentic Systems: Planning, Memory & Evaluation", "4 Hours"),
    8: ("08", "Module 8 — ", "Multi-Agent Systems + Capstone", "4 Hours"),
}

def transform_article(match):
    mod_num = int(match.group(1))
    body = match.group(2)
    
    num_str, prefix, default_title, duration_str = module_titles[mod_num]
    
    # Extract overview
    overview_match = re.search(r'<p class="module-block-overview">(.*?)</p>', body, re.DOTALL)
    if not overview_match:
        raise ValueError(f"Could not find overview in module {mod_num}")
    overview_html = overview_match.group(0).strip()
    
    # Extract existing title esc_html_e if present
    title_match = re.search(r'<h3 class="module-block-title">(.*?)</h3>', body, re.DOTALL)
    if title_match:
        raw_title = title_match.group(1).strip()
        # check if it uses esc_html_e
        m_esc = re.search(r"<\?php\s+esc_html_e\(\s*'([^']+)'", raw_title)
        if m_esc:
            title_text = m_esc.group(1)
        else:
            title_text = default_title
    else:
        title_text = default_title
        
    # Use exact title from module_titles if it has prefix or special name like Module 7
    if mod_num == 7:
        title_text = "Agentic Systems: Planning, Memory & Evaluation"
    
    # Extract duration
    dur_match = re.search(r'<span class="module-duration-badge">([^<]+)</span>', body)
    if dur_match:
        duration_str = dur_match.group(1).strip()
        
    # Extract accordions container
    acc_match = re.search(r'<div class="guides-accordion-container">.*', body, re.DOTALL)
    if not acc_match:
        raise ValueError(f"Could not find accordions container in module {mod_num}")
    acc_html = acc_match.group(0).strip()
    
    # Normalize inner accordions so all start collapsed
    # 1. remove 'active' from class="guides-accordion-item active"
    acc_html = re.sub(r'class="guides-accordion-item\s+active"', 'class="guides-accordion-item"', acc_html)
    # 2. aria-expanded="true" -> aria-expanded="false"
    acc_html = re.sub(r'aria-expanded="true"', 'aria-expanded="false"', acc_html)
    # 3. remove inline style="max-height: none;"
    acc_html = re.sub(r'\s*style="max-height:\s*none;"', '', acc_html)
    
    new_article = f"""					<!-- MODULE {mod_num} BLOCK -->
					<article id="curriculum-mod-{mod_num}" class="curriculum-guide-module-block" data-module="{mod_num}">
						<h3 class="module-accordion-heading">
							<button type="button" class="module-accordion-header" id="curriculum-mod-{mod_num}-header" aria-expanded="false" aria-controls="curriculum-mod-{mod_num}-content">
								<span class="module-header-main">
									<span class="module-number-pill">{num_str}</span>
									<span class="module-title-text"><span class="module-prefix"><?php esc_html_e( '{prefix}', 'twentytwentyfive-child' ); ?></span><?php esc_html_e( '{title_text}', 'twentytwentyfive-child' ); ?></span>
								</span>
								<span class="module-header-meta">
									<span class="module-duration-badge">{duration_str}</span>
									<span class="module-toggle-icon" aria-hidden="true">+</span>
								</span>
							</button>
						</h3>

						<div id="curriculum-mod-{mod_num}-content" class="module-accordion-content" role="region" aria-labelledby="curriculum-mod-{mod_num}-header">
							<div class="module-accordion-body">
								{overview_html}

								{acc_html}
							</div>
						</div>
					</article>"""
    return new_article

# Perform article replacement
new_content = content
for m in matches:
    old_article = m.group(0)
    new_article = transform_article(m)
    new_content = new_content.replace(old_article, new_article, 1)

print("Article replacements completed.")

# 2. Update CSS Block
old_css = """/* 7. Curriculum Layout: Full-Width Module Content Area */
.program-page .curriculum-guide-layout {
	display: block;
	max-width: 100%;
	margin: 0 auto;
}

.program-page .curriculum-guide-content {
	display: flex;
	flex-direction: column;
	gap: 20px;
	width: 100%;
	min-width: 0;
	text-align: left;
}

.program-page .curriculum-guide-module-block {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border-subtle);
	border-radius: var(--radius-md);
	padding: 20px 24px;
	scroll-margin-top: calc(var(--header-clearance, 80px) + 30px);
	transition: border-color var(--transition-fast);
	text-align: left;
}

.program-page .curriculum-guide-module-block:hover {
	border-color: rgba(255, 255, 255, 0.12);
}

.program-page .curriculum-guide-module-block .module-kicker-row {
	display: flex;
	align-items: center;
	justify-content: space-between;
	margin-bottom: 5px;
}

.program-page .curriculum-guide-module-block .module-kicker {
	font-size: 0.75rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.06em;
	color: var(--color-accent);
}

.program-page .curriculum-guide-module-block .module-duration-badge {
	font-size: 0.75rem;
	font-weight: 600;
	color: var(--color-text-muted);
	background: var(--color-bg-alt);
	padding: 2px 8px;
	border-radius: var(--radius-sm);
	border: 1px solid var(--color-border-subtle);
}

.program-page .curriculum-guide-module-block .module-block-title {
	font-size: 1.3rem;
	font-family: var(--font-heading);
	color: var(--color-text);
	margin-bottom: 8px;
	line-height: 1.25;
}

.program-page .curriculum-guide-module-block .module-block-overview {
	font-size: 0.92rem;
	line-height: 1.55;
	color: var(--color-text-secondary);
	margin-bottom: 14px;
}"""

new_css = """/* 7. Curriculum Layout: Full-Width Module Content Area & Primary Accordion */
.program-page .curriculum-guide-layout {
	display: block;
	max-width: 100%;
	margin: 0 auto;
}

.program-page .curriculum-guide-content {
	display: flex;
	flex-direction: column;
	gap: 10px;
	width: 100%;
	min-width: 0;
	text-align: left;
}

.program-page .curriculum-guide-module-block {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border-subtle);
	border-radius: var(--radius-md);
	padding: 0;
	scroll-margin-top: calc(var(--header-clearance, 80px) + 30px);
	transition: border-color var(--transition-fast), background var(--transition-fast);
	text-align: left;
	overflow: hidden;
}

.program-page .curriculum-guide-module-block:hover {
	border-color: rgba(255, 255, 255, 0.15);
}

.program-page .curriculum-guide-module-block.active {
	border-color: rgba(255, 255, 255, 0.22);
}

[data-theme="light"] .program-page .curriculum-guide-module-block:hover {
	border-color: rgba(0, 0, 0, 0.16);
}

[data-theme="light"] .program-page .curriculum-guide-module-block.active {
	border-color: rgba(0, 0, 0, 0.24);
}

.program-page .curriculum-guide-module-block .module-accordion-heading {
	margin: 0;
	padding: 0;
	font-size: 1rem;
	font-weight: normal;
	line-height: 1;
}

.program-page .curriculum-guide-module-block .module-accordion-header {
	width: 100%;
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 13px 18px;
	background: transparent;
	border: none;
	cursor: pointer;
	text-align: left;
	color: inherit;
	gap: 14px;
	transition: background var(--transition-fast);
}

.program-page .curriculum-guide-module-block .module-accordion-header:hover {
	background: rgba(255, 255, 255, 0.02);
}

[data-theme="light"] .program-page .curriculum-guide-module-block .module-accordion-header:hover {
	background: rgba(0, 0, 0, 0.02);
}

.program-page .curriculum-guide-module-block .module-accordion-header:focus-visible {
	outline: 2px solid var(--color-accent);
	outline-offset: -2px;
}

.program-page .curriculum-guide-module-block .module-header-main {
	display: inline-flex;
	align-items: center;
	gap: 12px;
	min-width: 0;
	flex: 1;
}

.program-page .curriculum-guide-module-block .module-number-pill {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	font-size: 0.75rem;
	font-weight: 700;
	letter-spacing: 0.04em;
	color: var(--color-accent);
	background: var(--color-bg-alt);
	padding: 3px 8px;
	border-radius: var(--radius-sm);
	border: 1px solid var(--color-border-subtle);
	flex-shrink: 0;
	line-height: 1.2;
}

.program-page .curriculum-guide-module-block .module-title-text {
	font-size: 1.05rem;
	font-family: var(--font-heading);
	font-weight: 600;
	color: var(--color-text);
	line-height: 1.35;
	transition: color var(--transition-fast);
}

.program-page .curriculum-guide-module-block:hover .module-title-text,
.program-page .curriculum-guide-module-block.active .module-title-text {
	color: var(--color-accent);
}

.program-page .curriculum-guide-module-block .module-prefix {
	color: var(--color-text-secondary);
	font-weight: 500;
}

.program-page .curriculum-guide-module-block.active .module-prefix {
	color: var(--color-accent);
	opacity: 0.9;
}

.program-page .curriculum-guide-module-block .module-header-meta {
	display: inline-flex;
	align-items: center;
	gap: 12px;
	flex-shrink: 0;
}

.program-page .curriculum-guide-module-block .module-duration-badge {
	font-size: 0.75rem;
	font-weight: 600;
	color: var(--color-text-muted);
	background: var(--color-bg-alt);
	padding: 3px 9px;
	border-radius: var(--radius-sm);
	border: 1px solid var(--color-border-subtle);
	white-space: nowrap;
	line-height: 1.2;
}

.program-page .curriculum-guide-module-block .module-toggle-icon {
	font-size: 1.25rem;
	font-weight: 300;
	color: var(--color-text-muted);
	transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), color var(--transition-fast);
	line-height: 1;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 18px;
	height: 18px;
	flex-shrink: 0;
}

.program-page .curriculum-guide-module-block.active .module-toggle-icon {
	transform: rotate(45deg);
	color: var(--color-accent);
}

.program-page .curriculum-guide-module-block .module-accordion-content {
	max-height: 0;
	overflow: hidden;
	opacity: 0;
	visibility: hidden;
	transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
}

.program-page .curriculum-guide-module-block.active .module-accordion-content {
	opacity: 1;
	visibility: visible;
}

.program-page .curriculum-guide-module-block .module-accordion-body {
	padding: 16px 20px 20px 20px;
	border-top: 1px solid var(--color-border-subtle);
}

.program-page .curriculum-guide-module-block .module-block-overview {
	font-size: 0.92rem;
	line-height: 1.6;
	color: var(--color-text-secondary);
	margin: 0 0 14px 0;
}"""

if old_css in new_content:
    new_content = new_content.replace(old_css, new_css, 1)
    print("CSS block successfully replaced.")
else:
    print("WARNING: Could not find exact old CSS block to replace!")

# 3. Update Media Queries for mobile
old_mq = """	.program-page .curriculum-guide-content {
		gap: 16px;
	}
	.program-page .curriculum-guide-module-block {
		padding: 16px;
	}"""

new_mq = """	.program-page .curriculum-guide-content {
		gap: 8px;
	}
	.program-page .curriculum-guide-module-block .module-accordion-header {
		padding: 11px 14px;
		gap: 10px;
	}
	.program-page .curriculum-guide-module-block .module-accordion-body {
		padding: 14px 14px 18px 14px;
	}
	.program-page .curriculum-guide-module-block .module-title-text {
		font-size: 0.96rem;
	}"""

if old_mq in new_content:
    new_content = new_content.replace(old_mq, new_mq, 1)
    print("Media query block successfully replaced.")
else:
    print("WARNING: Could not find exact old media query block to replace!")

# 4. Update JS Block
old_js = """	// 1. Module Block Accordions
	const guideAccordionHeaders = document.querySelectorAll('.curriculum-guide-module-block .guides-accordion-header');
	guideAccordionHeaders.forEach(header => {
		const item = header.closest('.guides-accordion-item');
		const content = item ? item.querySelector('.guides-accordion-content') : null;

		header.addEventListener('click', function(e) {
			e.preventDefault();
			if (!item || !content) return;

			const isOpen = item.classList.contains('active');
			if (isOpen) {
				item.classList.remove('active');
				header.setAttribute('aria-expanded', 'false');
				content.style.maxHeight = null;
			} else {
				item.classList.add('active');
				header.setAttribute('aria-expanded', 'true');
				content.style.maxHeight = content.scrollHeight + 'px';
			}
		});

		// Keyboard Accessibility (Enter & Space)
		header.addEventListener('keydown', function(e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				header.click();
			}
		});
	});"""

new_js = """	// 1. Module Primary Accordion (Single-Open, Smooth Height/Opacity Transition)
	const moduleBlocks = document.querySelectorAll('.curriculum-guide-module-block');

	function closeModule(mod) {
		const header = mod.querySelector('.module-accordion-header');
		const content = mod.querySelector('.module-accordion-content');
		if (!mod.classList.contains('active') || !content) return;

		// Set explicit measured height before animating down to 0
		content.style.maxHeight = content.scrollHeight + 'px';
		void content.offsetHeight; // Force reflow

		requestAnimationFrame(() => {
			content.style.maxHeight = '0px';
			mod.classList.remove('active');
			if (header) header.setAttribute('aria-expanded', 'false');
		});
	}

	function openModule(mod) {
		const header = mod.querySelector('.module-accordion-header');
		const content = mod.querySelector('.module-accordion-content');
		if (!content) return;

		mod.classList.add('active');
		if (header) header.setAttribute('aria-expanded', 'true');

		content.style.maxHeight = content.scrollHeight + 'px';

		function onEnd(e) {
			if (e && e.target !== content) return;
			content.removeEventListener('transitionend', onEnd);
			if (mod.classList.contains('active')) {
				content.style.maxHeight = 'none';
			}
		}
		content.addEventListener('transitionend', onEnd);
		setTimeout(onEnd, 380); // Safety fallback
	}

	moduleBlocks.forEach(mod => {
		const header = mod.querySelector('.module-accordion-header');
		if (!header) return;

		header.addEventListener('click', function(e) {
			e.preventDefault();
			const isOpen = mod.classList.contains('active');

			// Single-open logic: close all other open modules
			moduleBlocks.forEach(otherMod => {
				if (otherMod !== mod && otherMod.classList.contains('active')) {
					closeModule(otherMod);
				}
			});

			// Toggle target module
			if (isOpen) {
				closeModule(mod);
			} else {
				openModule(mod);
			}
		});

		// Keyboard Accessibility (Enter & Space)
		header.addEventListener('keydown', function(e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				header.click();
			}
		});
	});

	// 1b. Inner Disclosure Accordions (What You'll Learn, Practical, Outcome)
	const guideAccordionHeaders = document.querySelectorAll('.curriculum-guide-module-block .guides-accordion-header');
	guideAccordionHeaders.forEach(header => {
		const item = header.closest('.guides-accordion-item');
		const content = item ? item.querySelector('.guides-accordion-content') : null;
		const parentModuleContent = header.closest('.module-accordion-content');

		header.addEventListener('click', function(e) {
			e.preventDefault();
			if (!item || !content) return;

			const isOpen = item.classList.contains('active');
			if (isOpen) {
				item.classList.remove('active');
				header.setAttribute('aria-expanded', 'false');
				content.style.maxHeight = null;
			} else {
				item.classList.add('active');
				header.setAttribute('aria-expanded', 'true');
				content.style.maxHeight = content.scrollHeight + 'px';
			}

			// If parent module has a numeric max-height during animation, update it
			if (parentModuleContent && parentModuleContent.style.maxHeight && parentModuleContent.style.maxHeight !== 'none') {
				setTimeout(() => {
					if (parentModuleContent.style.maxHeight !== 'none') {
						parentModuleContent.style.maxHeight = parentModuleContent.scrollHeight + 'px';
					}
				}, 50);
			}
		});

		// Keyboard Accessibility (Enter & Space)
		header.addEventListener('keydown', function(e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				header.click();
			}
		});
	});"""

if old_js in new_content:
    new_content = new_content.replace(old_js, new_js, 1)
    print("JS block successfully replaced.")
else:
    print("WARNING: Could not find exact old JS block to replace!")

# Write to test output file
output_test_file = r"D:\lohnourpro\page-agentic-ai-foundations.php.test_output"
with open(output_test_file, "w", encoding="utf-8") as f:
    f.write(new_content)

print(f"Wrote transformed file to: {output_test_file}")
