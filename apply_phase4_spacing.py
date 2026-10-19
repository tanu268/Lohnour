import re
import sys

filepath = 'current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Locate boundaries
section_start = content.find('/* 3. Section Structure */')
style_end = content.find('</style>', section_start)

if section_start == -1 or style_end == -1:
    print("Error: Could not locate CSS boundaries.")
    sys.exit(1)

new_section_css = """/* 3. Section Structure & Vertical Rhythm (Phase 4 Spacing Refinement) */
.program-page .program-body-container {
	padding-top: var(--spacing-md);
}

.program-page .program-section {
	padding: 24px 0;
	margin-bottom: var(--spacing-md);
	scroll-margin-top: calc(var(--site-header-height, 81px) + 65px);
}

.program-page .program-section:last-of-type {
	margin-bottom: var(--spacing-sm);
}

.program-page .section-heading-wrapper {
	margin-bottom: var(--spacing-md);
}

.program-page .section-heading-wrapper .card-badge {
	margin-bottom: 6px;
	display: inline-block;
}

.program-page .section-heading-wrapper .section-title {
	font-size: 2rem;
	margin-bottom: 6px;
	color: var(--color-text);
}

.program-page .section-heading-wrapper .section-subtitle {
	font-size: 1rem;
	color: var(--color-text-muted);
	max-width: 760px;
	margin-bottom: 0;
	line-height: 1.55;
}

/* 4. Overview Layout */
.program-page .overview-grid {
	display: grid;
	grid-template-columns: 1.6fr 1.2fr;
	gap: var(--spacing-md);
}

@media (max-width: 900px) {
	.program-page .overview-grid {
		grid-template-columns: 1fr;
	}
}

.program-page .overview-main,
.program-page .overview-highlights {
	padding: clamp(1.25rem, 2.5vw, 1.75rem);
	border-radius: var(--radius-lg);
}

.program-page .overview-main h3,
.program-page .overview-highlights h3 {
	font-size: 1.35rem;
	margin-bottom: 10px;
	color: var(--color-text);
}

.program-page .overview-main p {
	font-size: 1rem;
	line-height: 1.65;
	color: var(--color-text-secondary);
	margin-bottom: 12px;
}

.program-page .overview-main p:last-child {
	margin-bottom: 0;
}

.program-page .highlights-list {
	list-style: none;
	padding: 0;
	margin: 0;
	display: flex;
	flex-direction: column;
	gap: 12px;
}

.program-page .highlights-list li {
	display: flex;
	gap: 12px;
	align-items: flex-start;
}

.program-page .highlights-list .bullet-icon {
	color: var(--color-accent);
	font-size: 0.9rem;
	margin-top: 2px;
}

.program-page .highlights-list p {
	font-size: 0.9rem;
	color: var(--color-text-muted);
	margin: 2px 0 0 0;
	line-height: 1.5;
}

/* 5. Why This Program */
.program-page .philosophy-card {
	padding: clamp(1.25rem, 2.5vw, 1.75rem);
	border-radius: var(--radius-lg);
}

.program-page .philosophy-card .lead-text {
	font-size: 1.15rem;
	line-height: 1.6;
	color: var(--color-text);
	margin-bottom: var(--spacing-md);
}

.program-page .progression-flow {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 8px;
	margin-bottom: var(--spacing-md);
	padding: 12px 16px;
	background: var(--color-bg-alt);
	border-radius: var(--radius-md);
	border: 1px solid var(--color-border-subtle);
}

.program-page .step-pill {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 6px 12px;
	border-radius: var(--radius-sm);
	font-size: 0.8rem;
	font-weight: 600;
	background: var(--color-bg-surface);
	color: var(--color-text-secondary);
	border: 1px solid var(--color-border);
}

.program-page .step-pill span {
	color: var(--color-accent);
	font-size: 0.7rem;
}

.program-page .step-pill.highlight {
	background: rgba(255, 255, 255, 0.1);
	border-color: var(--color-accent);
	color: var(--color-accent);
}

.program-page .step-arrow {
	color: var(--color-text-muted);
	font-size: 0.85rem;
}

.program-page .philosophy-columns {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: var(--spacing-md);
}

@media (max-width: 768px) {
	.program-page .philosophy-columns {
		grid-template-columns: 1fr;
	}
}

.program-page .phil-col h4 {
	font-size: 1.1rem;
	margin-bottom: 6px;
	color: var(--color-text);
}

.program-page .phil-col p {
	font-size: 0.95rem;
	line-height: 1.6;
	color: var(--color-text-muted);
	margin: 0;
}

/* 6. Module Summary Grid (What You'll Learn) */
.program-page .module-summary-grid {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: var(--spacing-sm);
}

@media (max-width: 1024px) {
	.program-page .module-summary-grid {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media (max-width: 600px) {
	.program-page .module-summary-grid {
		grid-template-columns: 1fr;
	}
}

.program-page .module-summary-card {
	padding: 16px 18px;
	border-radius: var(--radius-md);
	display: flex;
	flex-direction: column;
}

.program-page .module-summary-card .mod-num {
	font-size: 0.75rem;
	font-weight: 700;
	color: var(--color-accent);
	margin-bottom: 4px;
}

.program-page .module-summary-card h4 {
	font-size: 1rem;
	margin-bottom: 4px;
	color: var(--color-text);
	line-height: 1.3;
}

.program-page .module-summary-card .mod-time {
	font-size: 0.75rem;
	color: var(--color-text-muted);
	margin-bottom: 8px;
	display: inline-block;
}

.program-page .module-summary-card p {
	font-size: 0.85rem;
	line-height: 1.5;
	color: var(--color-text-secondary);
	margin: 0;
}

/* 7. Curriculum Layout: Reusing Existing Lohnour Guide Architecture */
.program-page .curriculum-guide-layout {
	display: grid;
	grid-template-columns: 280px minmax(0, 1fr);
	gap: 28px;
	align-items: start;
	max-width: 1300px;
	margin: 0 auto;
}

/* Left Sidebar Rail */
.program-page .curriculum-guide-sidebar {
	position: sticky;
	top: calc(var(--header-clearance, 80px) + 20px);
	max-height: calc(100vh - var(--header-clearance, 80px) - 40px);
	overflow-y: auto;
	text-align: left;
}

.program-page .curriculum-outline-widget {
	padding: 14px 16px;
	background: var(--color-glass);
	-webkit-backdrop-filter: blur(10px);
	backdrop-filter: blur(10px);
	border: 1px solid var(--color-glass-border);
	border-radius: var(--radius-md);
	text-align: left;
}

.program-page .curriculum-outline-title {
	font-size: 0.95rem;
	margin-bottom: 10px;
	border-bottom: 1px solid var(--color-glass-border);
	padding-bottom: 8px;
	text-align: left;
}

.program-page .curriculum-outline-title .guide-outline-parent-link {
	color: var(--color-text);
	text-decoration: none;
	font-weight: 700;
	display: block;
}

.program-page .guides-outline-menu {
	display: flex;
	flex-direction: column;
	gap: 4px;
	text-align: left;
}

.program-page .outline-module {
	margin-bottom: 4px;
	text-align: left;
	border-radius: var(--radius-sm);
	transition: background var(--transition-fast);
}

.program-page .outline-module-header {
	width: 100%;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 7px 9px;
	background: transparent;
	border: none;
	cursor: pointer;
	text-align: left;
	border-radius: var(--radius-sm);
	color: inherit;
	transition: background var(--transition-fast), color var(--transition-fast);
}

.program-page .outline-module-header:hover {
	background: rgba(255, 255, 255, 0.04);
}

.program-page .outline-module-header:focus-visible {
	outline: 2px solid var(--color-accent);
	outline-offset: -2px;
}

.program-page .outline-module-header h5 {
	font-size: 0.8rem;
	text-transform: uppercase;
	color: var(--color-text-muted);
	margin: 0;
	font-weight: 600;
	letter-spacing: 0.04em;
	line-height: 1.35;
	text-align: left;
	transition: color var(--transition-fast);
}

.program-page .outline-module.active .outline-module-header h5,
.program-page .outline-module-header:hover h5 {
	color: var(--color-accent);
}

.program-page .outline-module .module-chevron {
	font-size: 0.65rem;
	transition: transform 0.25s ease;
	transform: rotate(0deg);
	color: var(--color-text-muted);
	flex-shrink: 0;
	margin-left: 8px;
}

.program-page .outline-module.active .module-chevron,
.program-page .outline-module.expanded .module-chevron {
	transform: rotate(90deg);
	color: var(--color-accent);
}

.program-page .outline-module-content {
	max-height: 0;
	overflow: hidden;
	transition: max-height 0.3s ease-out;
}

.program-page .outline-module.active .outline-module-content,
.program-page .outline-module.expanded .outline-module-content {
	max-height: 250px;
}

.program-page .outline-subtopics-list {
	list-style: none;
	margin: 3px 0 6px 0;
	padding-left: 10px;
	display: flex;
	flex-direction: column;
	gap: 2px;
	text-align: left;
}

.program-page .guide-outline-child-link {
	display: block;
	padding: 3px 8px;
	font-size: 0.76rem;
	line-height: 1.4;
	text-decoration: none;
	border-radius: var(--radius-sm);
	color: var(--color-text-secondary);
	border-left: 2px solid transparent;
	transition: color var(--transition-fast), border-color var(--transition-fast), background var(--transition-fast);
}

.program-page .guide-outline-child-link:hover,
.program-page .guide-outline-child-link.active {
	color: var(--color-accent);
	border-left-color: var(--color-accent);
	background: rgba(255, 255, 255, 0.02);
}

/* Right Content Area: Module Blocks */
.program-page .curriculum-guide-content {
	display: flex;
	flex-direction: column;
	gap: 22px;
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
}

/* Accordions Inside Right Modules (Guide-Style Disclosure) */
.program-page .curriculum-guide-module-block .guides-accordion-container {
	display: flex;
	flex-direction: column;
	gap: 6px;
	margin-top: 10px;
}

.program-page .curriculum-guide-module-block .guides-accordion-item {
	border: 1px solid var(--color-border-subtle);
	border-radius: var(--radius-sm);
	background: rgba(255, 255, 255, 0.015);
	overflow: hidden;
	transition: border-color var(--transition-fast), background var(--transition-fast);
}

.program-page .curriculum-guide-module-block .guides-accordion-item:hover {
	border-color: rgba(255, 255, 255, 0.1);
}

.program-page .curriculum-guide-module-block .guides-accordion-item.active {
	border-color: rgba(255, 255, 255, 0.15);
	background: rgba(255, 255, 255, 0.03);
}

.program-page .curriculum-guide-module-block .guides-accordion-header {
	width: 100%;
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 11px 16px;
	background: transparent;
	border: none;
	cursor: pointer;
	text-align: left;
	color: inherit;
}

.program-page .curriculum-guide-module-block .guides-accordion-header:focus-visible {
	outline: 2px solid var(--color-accent);
	outline-offset: -2px;
}

.program-page .curriculum-guide-module-block .guides-accordion-header .accordion-title {
	font-size: 0.9rem;
	font-weight: 600;
	color: var(--color-text);
	transition: color var(--transition-fast);
}

.program-page .curriculum-guide-module-block .guides-accordion-item.active .accordion-title {
	color: var(--color-accent);
}

.program-page .curriculum-guide-module-block .guides-accordion-header .accordion-icon {
	font-size: 1.15rem;
	font-weight: 400;
	color: var(--color-text-muted);
	transition: transform 0.25s ease, color var(--transition-fast);
	line-height: 1;
}

.program-page .curriculum-guide-module-block .guides-accordion-item.active .accordion-icon {
	transform: rotate(45deg);
	color: var(--color-accent);
}

.program-page .curriculum-guide-module-block .guides-accordion-content {
	max-height: 0;
	overflow: hidden;
	transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.program-page .curriculum-guide-module-block .guides-accordion-inner {
	padding: 4px 16px 14px 16px;
	border-top: 1px solid var(--color-border-subtle);
}

.program-page .curriculum-guide-module-block .guide-topic-tags {
	display: flex;
	flex-wrap: wrap;
	gap: 6px;
	list-style: none;
	padding: 0;
	margin: 10px 0 0 0;
}

.program-page .curriculum-guide-module-block .guide-topic-tags li {
	display: inline-flex;
	padding: 3px 9px;
	font-size: 0.78rem;
	background: var(--color-bg-alt);
	color: var(--color-text-secondary);
	border-radius: var(--radius-sm);
	border: 1px solid var(--color-border-subtle);
}

.program-page .curriculum-guide-module-block .guide-activity-box,
.program-page .curriculum-guide-module-block .guide-outcome-box {
	margin-top: 10px;
	font-size: 0.9rem;
	line-height: 1.55;
	color: var(--color-text-secondary);
}

.program-page .curriculum-guide-module-block .progression-pill-row {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 6px;
	margin-bottom: 8px;
}

.program-page .curriculum-guide-module-block .pill-step {
	padding: 3px 8px;
	font-size: 0.75rem;
	font-weight: 500;
	background: var(--color-bg-surface);
	color: var(--color-text-secondary);
	border-radius: 4px;
	border: 1px solid var(--color-border-subtle);
}

.program-page .curriculum-guide-module-block .pill-step.highlight {
	border-color: var(--color-border);
	color: var(--color-text);
	font-weight: 600;
}

.program-page .curriculum-guide-module-block .pill-sep {
	color: var(--color-text-muted);
	font-size: 0.75rem;
}

/* Mobile Toggle and Responsive Layout */
.program-page .curriculum-mobile-outline-toggle-wrapper {
	display: none;
	max-width: 1300px;
	margin: 0 auto var(--spacing-sm) auto;
}

.program-page .curriculum-mobile-outline-btn {
	width: 100%;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 10px 14px;
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border-subtle);
	border-radius: var(--radius-md);
	color: var(--color-text);
	font-size: 0.88rem;
	font-weight: 600;
	cursor: pointer;
	transition: border-color var(--transition-fast), background var(--transition-fast);
}

.program-page .curriculum-mobile-outline-btn:hover {
	border-color: var(--color-border);
}

.program-page .curriculum-mobile-outline-btn .btn-label-wrap {
	display: flex;
	align-items: center;
	gap: 8px;
}

.program-page .curriculum-mobile-outline-btn .btn-chevron {
	transition: transform 0.25s ease;
	color: var(--color-text-muted);
}

.program-page .curriculum-mobile-outline-btn.active .btn-chevron {
	transform: rotate(180deg);
}

@media (max-width: 1024px) {
	.program-page .curriculum-guide-layout {
		grid-template-columns: 240px minmax(0, 1fr);
		gap: var(--spacing-md);
	}
}

@media (max-width: 768px) {
	.program-page .curriculum-mobile-outline-toggle-wrapper {
		display: block;
	}

	.program-page .curriculum-guide-layout {
		grid-template-columns: 1fr;
		display: flex;
		flex-direction: column;
	}

	.program-page .curriculum-guide-sidebar {
		display: none;
		position: static !important;
		max-height: none !important;
		margin-bottom: var(--spacing-sm);
		width: 100%;
	}

	.program-page .curriculum-guide-sidebar.mobile-open {
		display: block;
	}
}

/* 8. What You'll Build */
.program-page .build-progression-grid {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: var(--spacing-sm);
}

@media (max-width: 900px) {
	.program-page .build-progression-grid {
		grid-template-columns: 1fr;
	}
}

.program-page .build-card {
	padding: 16px 18px;
	border-radius: var(--radius-md);
	display: flex;
	flex-direction: column;
}

.program-page .build-card.capstone-card {
	grid-column: span 3;
	border-color: var(--color-accent);
	background: rgba(255, 255, 255, 0.04);
}

@media (max-width: 900px) {
	.program-page .build-card.capstone-card {
		grid-column: span 1;
	}
}

.program-page .build-step {
	font-size: 0.75rem;
	font-weight: 700;
	color: var(--color-accent);
	margin-bottom: 4px;
}

.program-page .build-card h4 {
	font-size: 1.05rem;
	margin-bottom: 4px;
	color: var(--color-text);
}

.program-page .build-card p {
	font-size: 0.88rem;
	line-height: 1.5;
	color: var(--color-text-secondary);
	margin: 0;
}

/* 9. Outcomes */
.program-page .outcomes-statement-card {
	padding: clamp(1.25rem, 2.5vw, 1.75rem);
	border-radius: var(--radius-lg);
}

.program-page .north-star-quote p {
	font-size: 1.15rem;
	font-style: italic;
	line-height: 1.55;
	color: var(--color-text);
	margin-bottom: var(--spacing-md);
	padding-bottom: var(--spacing-md);
	border-bottom: 1px solid var(--color-border-subtle);
}

.program-page .outcomes-checklist-grid {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 16px;
}

@media (max-width: 768px) {
	.program-page .outcomes-checklist-grid {
		grid-template-columns: 1fr;
	}
}

.program-page .outcome-item {
	display: flex;
	gap: 12px;
	align-items: flex-start;
}

.program-page .outcome-item .check-icon {
	color: var(--color-accent);
	font-weight: 700;
	margin-top: 2px;
}

.program-page .outcome-item strong {
	font-size: 0.98rem;
	color: var(--color-text);
	display: block;
	margin-bottom: 2px;
}

.program-page .outcome-item p {
	font-size: 0.88rem;
	line-height: 1.5;
	color: var(--color-text-secondary);
	margin: 0;
}

/* 10. Skills Tag Cloud */
.program-page .skills-tag-cloud {
	display: flex;
	flex-wrap: wrap;
	gap: 8px;
}

.program-page .skill-tag {
	padding: 8px 14px;
	font-size: 0.88rem;
	font-weight: 500;
	border-radius: var(--radius-full);
	background: var(--color-bg-alt);
	color: var(--color-text);
	border: 1px solid var(--color-border-subtle);
	transition: border-color var(--transition-fast), transform var(--transition-fast);
}

.program-page .skill-tag:hover {
	border-color: var(--color-accent);
	transform: translateY(-2px);
}

/* 11. Career Grid */
.program-page .career-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
	gap: var(--spacing-sm);
	margin-bottom: var(--spacing-sm);
}

.program-page .career-card {
	padding: 16px 18px;
	border-radius: var(--radius-md);
}

.program-page .career-card h4 {
	font-size: 1.02rem;
	margin-bottom: 4px;
	color: var(--color-text);
}

.program-page .career-card p {
	font-size: 0.85rem;
	line-height: 1.5;
	color: var(--color-text-secondary);
	margin: 0;
}

.program-page .career-disclaimer {
	font-size: 0.8rem;
	color: var(--color-text-muted);
	margin-top: 8px;
}

/* 12. How Coaching Works Flow */
.program-page .coaching-flow-grid {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: var(--spacing-sm);
}

@media (max-width: 992px) {
	.program-page .coaching-flow-grid {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media (max-width: 600px) {
	.program-page .coaching-flow-grid {
		grid-template-columns: 1fr;
	}
}

.program-page .coaching-step {
	padding: 16px 18px;
	border-radius: var(--radius-md);
}

.program-page .coaching-step .step-num {
	font-size: 1.35rem;
	font-family: var(--font-heading);
	font-weight: 700;
	color: var(--color-accent);
	margin-bottom: 4px;
}

.program-page .coaching-step h4 {
	font-size: 1.05rem;
	margin-bottom: 4px;
	color: var(--color-text);
}

.program-page .coaching-step p {
	font-size: 0.85rem;
	line-height: 1.5;
	color: var(--color-text-secondary);
	margin: 0;
}

/* 13. Scope (Included / Excluded) */
.program-page .scope-grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: var(--spacing-md);
}

@media (max-width: 768px) {
	.program-page .scope-grid {
		grid-template-columns: 1fr;
	}
}

.program-page .scope-card {
	padding: clamp(1.25rem, 2.5vw, 1.75rem);
	border-radius: var(--radius-lg);
}

.program-page .scope-title {
	font-size: 1.2rem;
	margin-bottom: 8px;
	display: flex;
	align-items: center;
	gap: 10px;
	color: var(--color-text);
}

.program-page .scope-icon {
	width: 24px;
	height: 24px;
	border-radius: 50%;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	font-size: 0.8rem;
	font-weight: 700;
}

.program-page .scope-icon.check {
	background: rgba(34, 197, 94, 0.15);
	color: #22c55e;
}

.program-page .scope-icon.xmark {
	background: rgba(239, 68, 68, 0.15);
	color: #ef4444;
}

.program-page .scope-note {
	font-size: 0.88rem;
	line-height: 1.5;
	color: var(--color-text-muted);
	margin-bottom: 8px;
}

.program-page .scope-list {
	list-style: none;
	padding: 0;
	margin: 0;
	display: flex;
	flex-direction: column;
	gap: 6px;
}

.program-page .scope-list li {
	font-size: 0.88rem;
	line-height: 1.5;
	color: var(--color-text-secondary);
	position: relative;
	padding-left: 18px;
}

.program-page .scope-list li::before {
	content: '•';
	position: absolute;
	left: 4px;
	color: var(--color-accent);
}

/* 14. Pricing / Enrollment */
.program-page .pricing-card {
	padding: clamp(1.5rem, 4vw, 2.5rem);
	border-radius: var(--radius-lg);
	border-color: var(--color-glass-border);
}

.program-page .pricing-desc {
	font-size: 1.05rem;
	color: var(--color-text-secondary);
	max-width: 650px;
	margin: 0 auto 16px auto;
	line-height: 1.55;
}

.program-page .pricing-callout {
	display: flex;
	flex-wrap: wrap;
	justify-content: center;
	gap: 8px;
	margin-bottom: 16px;
}

.program-page .callout-pill {
	padding: 6px 14px;
	font-size: 0.82rem;
	font-weight: 600;
	background: var(--color-bg-alt);
	color: var(--color-text);
	border-radius: var(--radius-full);
	border: 1px solid var(--color-border-subtle);
}

.program-page .pricing-actions {
	margin-bottom: var(--spacing-xs);
}

.program-page .pricing-subtext {
	font-size: 0.82rem;
	color: var(--color-text-muted);
	max-width: 500px;
	margin: 0 auto;
}

/* 15. FAQ Preview Accordion */
.program-page .faq-accordion-container {
	display: flex;
	flex-direction: column;
	gap: 6px;
	margin-bottom: var(--spacing-sm);
}

.program-page .faq-item {
	border-radius: var(--radius-md);
	overflow: hidden;
}

.program-page .faq-header {
	width: 100%;
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 12px 18px;
	background: transparent;
	border: none;
	cursor: pointer;
	text-align: left;
	font-size: 0.98rem;
	font-weight: 600;
	color: var(--color-text);
	gap: 12px;
}

.program-page .faq-toggle-icon {
	font-size: 1.25rem;
	color: var(--color-accent);
	transition: transform var(--transition-fast);
	flex-shrink: 0;
}

.program-page .faq-item.active .faq-toggle-icon {
	transform: rotate(45deg);
}

.program-page .faq-content {
	max-height: 0;
	overflow: hidden;
	transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.program-page .faq-content p {
	padding: 0 18px 12px 18px;
	font-size: 0.92rem;
	line-height: 1.55;
	color: var(--color-text-secondary);
	margin: 0;
}

.program-page .faq-preview-footer {
	margin-top: var(--spacing-sm);
}

/* 16. Final CTA */
.program-page .cta-section {
	padding: clamp(1.75rem, 4vw, 2.75rem);
	border-radius: var(--radius-lg);
}

.program-page .cta-title {
	font-size: clamp(1.75rem, 3.5vw, 2.25rem);
	margin-bottom: 8px;
	color: var(--color-text);
}

.program-page .cta-desc {
	font-size: 1rem;
	color: var(--color-text-secondary);
	max-width: 600px;
	margin: 0 auto 16px auto;
	line-height: 1.55;
}

/* Responsive Vertical Spacing Overrides */
@media (max-width: 768px) {
	.program-page .program-body-container {
		padding-top: var(--spacing-sm);
	}

	.program-page .program-section {
		padding: 14px 0;
		margin-bottom: var(--spacing-sm);
		scroll-margin-top: calc(var(--site-header-height, 81px) + 50px);
	}

	.program-page .section-heading-wrapper {
		margin-bottom: var(--spacing-sm);
	}

	.program-page .overview-main,
	.program-page .overview-highlights,
	.program-page .philosophy-card,
	.program-page .outcomes-statement-card,
	.program-page .scope-card {
		padding: 14px;
	}

	.program-page .curriculum-guide-content {
		gap: 14px;
	}

	.program-page .curriculum-guide-module-block {
		padding: 14px 16px;
	}

	.program-page .curriculum-mobile-outline-toggle-wrapper {
		margin-bottom: 10px;
	}

	.program-page .pricing-card,
	.program-page .cta-section {
		padding: 1.5rem 1rem;
	}
}
"""

new_content = content[:section_start] + new_section_css + content[style_end:]

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Successfully applied Phase 4 vertical spacing refinements to page-agentic-ai-foundations.php")
