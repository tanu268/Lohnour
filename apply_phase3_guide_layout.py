import re
import subprocess
import sys

TARGET_FILE = r"d:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"

content = open(TARGET_FILE, "r", encoding="utf-8").read()

# 1. NEW HTML FOR SECTION 7 (GUIDE-STYLE MODULE NAVIGATION & RIGHT CONTENT AREA)
new_html = '''		<!-- 7. 30-HOUR CURRICULUM (GUIDE-STYLE MODULE NAVIGATION & CONTENT) -->
		<section id="curriculum" class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Interactive Detailed Breakdown', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( '30-Hour Curriculum', 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'Explore the full week-by-week module syllabus. Select any module from the navigation panel to inspect topics, hands-on activities, and tangible learning outcomes.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<!-- Mobile Module Outline Toggle (Visible on Mobile / Compact screens) -->
			<div class="curriculum-mobile-outline-toggle-wrapper">
				<button id="curriculum-mobile-outline-toggle" class="curriculum-mobile-outline-btn" aria-expanded="false" aria-controls="curriculum-guide-sidebar">
					<span class="btn-label-wrap">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
						<span id="mobile-nav-active-label"><?php esc_html_e( 'Module Outline (8 Modules)', 'twentytwentyfive-child' ); ?></span>
					</span>
					<svg class="btn-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
				</button>
			</div>

			<!-- Guide-Style 2-Column Grid -->
			<div class="curriculum-guide-layout">

				<!-- LEFT: MODULE NAVIGATION SIDEBAR -->
				<aside id="curriculum-guide-sidebar" class="curriculum-guide-sidebar">
					<div class="sidebar-widget curriculum-outline-widget">
						<h4 class="widget-title-classic curriculum-outline-title">
							<a href="#curriculum" class="guide-outline-parent-link active"><?php esc_html_e( 'Agentic AI Foundations', 'twentytwentyfive-child' ); ?></a>
						</h4>
						<nav class="guides-outline-menu" aria-label="<?php esc_attr_e( 'Curriculum Module Outline', 'twentytwentyfive-child' ); ?>">
							
							<!-- MODULE 1 -->
							<div class="outline-module active" data-module="1">
								<button class="outline-module-header" aria-expanded="true" aria-controls="guide-mod-content-1">
									<h5><?php esc_html_e( 'Module 1: AI, LLM & Agentic Foundations', 'twentytwentyfive-child' ); ?></h5>
									<span class="module-chevron" aria-hidden="true">▶</span>
								</button>
								<div id="guide-mod-content-1" class="outline-module-content">
									<ul class="outline-subtopics-list">
										<li><a href="#curriculum-mod-1" class="guide-outline-child-link active"><?php esc_html_e( 'Module Overview (3h)', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-1-learn" class="guide-outline-child-link"><?php esc_html_e( "What You'll Learn (13 Topics)", 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-1-practical" class="guide-outline-child-link"><?php esc_html_e( 'Practical: Architectural Choice', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-1-outcome" class="guide-outline-child-link"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></a></li>
									</ul>
								</div>
							</div>

							<!-- MODULE 2 -->
							<div class="outline-module" data-module="2">
								<button class="outline-module-header" aria-expanded="false" aria-controls="guide-mod-content-2">
									<h5><?php esc_html_e( 'Module 2: Prompt Engineering & LLM Control', 'twentytwentyfive-child' ); ?></h5>
									<span class="module-chevron" aria-hidden="true">▶</span>
								</button>
								<div id="guide-mod-content-2" class="outline-module-content">
									<ul class="outline-subtopics-list">
										<li><a href="#curriculum-mod-2" class="guide-outline-child-link"><?php esc_html_e( 'Module Overview (3h)', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-2-learn" class="guide-outline-child-link"><?php esc_html_e( "What You'll Learn (11 Topics)", 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-2-practical" class="guide-outline-child-link"><?php esc_html_e( 'Practical: Prompt Pipeline', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-2-outcome" class="guide-outline-child-link"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></a></li>
									</ul>
								</div>
							</div>

							<!-- MODULE 3 -->
							<div class="outline-module" data-module="3">
								<button class="outline-module-header" aria-expanded="false" aria-controls="guide-mod-content-3">
									<h5><?php esc_html_e( 'Module 3: Generative AI & Multimodal Systems', 'twentytwentyfive-child' ); ?></h5>
									<span class="module-chevron" aria-hidden="true">▶</span>
								</button>
								<div id="guide-mod-content-3" class="outline-module-content">
									<ul class="outline-subtopics-list">
										<li><a href="#curriculum-mod-3" class="guide-outline-child-link"><?php esc_html_e( 'Module Overview (2h)', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-3-learn" class="guide-outline-child-link"><?php esc_html_e( "What You'll Learn (8 Topics)", 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-3-practical" class="guide-outline-child-link"><?php esc_html_e( 'Practical: AI Content Pipeline', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-3-outcome" class="guide-outline-child-link"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></a></li>
									</ul>
								</div>
							</div>

							<!-- MODULE 4 -->
							<div class="outline-module" data-module="4">
								<button class="outline-module-header" aria-expanded="false" aria-controls="guide-mod-content-4">
									<h5><?php esc_html_e( 'Module 4: AI Workflows & Automation with n8n', 'twentytwentyfive-child' ); ?></h5>
									<span class="module-chevron" aria-hidden="true">▶</span>
								</button>
								<div id="guide-mod-content-4" class="outline-module-content">
									<ul class="outline-subtopics-list">
										<li><a href="#curriculum-mod-4" class="guide-outline-child-link"><?php esc_html_e( 'Module Overview (4h)', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-4-learn" class="guide-outline-child-link"><?php esc_html_e( "What You'll Learn (18 Topics)", 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-4-practical" class="guide-outline-child-link"><?php esc_html_e( 'Practical: n8n AI Automation', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-4-outcome" class="guide-outline-child-link"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></a></li>
									</ul>
								</div>
							</div>

							<!-- MODULE 5 -->
							<div class="outline-module" data-module="5">
								<button class="outline-module-header" aria-expanded="false" aria-controls="guide-mod-content-5">
									<h5><?php esc_html_e( 'Module 5: Python for AI Systems', 'twentytwentyfive-child' ); ?></h5>
									<span class="module-chevron" aria-hidden="true">▶</span>
								</button>
								<div id="guide-mod-content-5" class="outline-module-content">
									<ul class="outline-subtopics-list">
										<li><a href="#curriculum-mod-5" class="guide-outline-child-link"><?php esc_html_e( 'Module Overview (4h)', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-5-learn" class="guide-outline-child-link"><?php esc_html_e( "What You'll Learn (16 Topics)", 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-5-practical" class="guide-outline-child-link"><?php esc_html_e( 'Practical: Python AI Bridge', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-5-outcome" class="guide-outline-child-link"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></a></li>
									</ul>
								</div>
							</div>

							<!-- MODULE 6 -->
							<div class="outline-module" data-module="6">
								<button class="outline-module-header" aria-expanded="false" aria-controls="guide-mod-content-6">
									<h5><?php esc_html_e( 'Module 6: Building AI Agents', 'twentytwentyfive-child' ); ?></h5>
									<span class="module-chevron" aria-hidden="true">▶</span>
								</button>
								<div id="guide-mod-content-6" class="outline-module-content">
									<ul class="outline-subtopics-list">
										<li><a href="#curriculum-mod-6" class="guide-outline-child-link"><?php esc_html_e( 'Module Overview (6h)', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-6-learn" class="guide-outline-child-link"><?php esc_html_e( "What You'll Learn (9 Topics)", 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-6-practical" class="guide-outline-child-link"><?php esc_html_e( 'Practical: Single AI Agent', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-6-outcome" class="guide-outline-child-link"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></a></li>
									</ul>
								</div>
							</div>

							<!-- MODULE 7 -->
							<div class="outline-module" data-module="7">
								<button class="outline-module-header" aria-expanded="false" aria-controls="guide-mod-content-7">
									<h5><?php esc_html_e( 'Module 7: Planning, Memory & Evaluation', 'twentytwentyfive-child' ); ?></h5>
									<span class="module-chevron" aria-hidden="true">▶</span>
								</button>
								<div id="guide-mod-content-7" class="outline-module-content">
									<ul class="outline-subtopics-list">
										<li><a href="#curriculum-mod-7" class="guide-outline-child-link"><?php esc_html_e( 'Module Overview (4h)', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-7-learn" class="guide-outline-child-link"><?php esc_html_e( "What You'll Learn (22 Topics)", 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-7-practical" class="guide-outline-child-link"><?php esc_html_e( 'Practical: Evaluated System', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-7-outcome" class="guide-outline-child-link"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></a></li>
									</ul>
								</div>
							</div>

							<!-- MODULE 8 -->
							<div class="outline-module" data-module="8">
								<button class="outline-module-header" aria-expanded="false" aria-controls="guide-mod-content-8">
									<h5><?php esc_html_e( 'Module 8: Multi-Agent Systems + Capstone', 'twentytwentyfive-child' ); ?></h5>
									<span class="module-chevron" aria-hidden="true">▶</span>
								</button>
								<div id="guide-mod-content-8" class="outline-module-content">
									<ul class="outline-subtopics-list">
										<li><a href="#curriculum-mod-8" class="guide-outline-child-link"><?php esc_html_e( 'Module Overview (4h)', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-8-learn" class="guide-outline-child-link"><?php esc_html_e( "What You'll Learn (8 Topics)", 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-8-practical" class="guide-outline-child-link"><?php esc_html_e( 'Capstone: Multi-Agent System', 'twentytwentyfive-child' ); ?></a></li>
										<li><a href="#curriculum-mod-8-outcome" class="guide-outline-child-link"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></a></li>
									</ul>
								</div>
							</div>

						</nav>
					</div>
				</aside>

				<!-- RIGHT: CURRICULUM CONTENT AREA (GUIDE-STYLE SECTIONS WITH ACCORDIONS) -->
				<div class="curriculum-guide-content">

					<!-- MODULE 1 BLOCK -->
					<article id="curriculum-mod-1" class="curriculum-guide-module-block" data-module="1">
						<div class="module-block-header">
							<div class="module-kicker-row">
								<span class="module-kicker">MODULE 01</span>
								<span class="module-duration-badge">3 Hours</span>
							</div>
							<h3 class="module-block-title"><?php esc_html_e( 'AI, LLM & Agentic Foundations', 'twentytwentyfive-child' ); ?></h3>
							<p class="module-block-overview">
								<?php esc_html_e( 'Establish precise conceptual grounding in generative models, token dynamics, inference mechanics, and the architectural differences between static chatbot prompts, automated workflows, and true autonomous agents.', 'twentytwentyfive-child' ); ?>
							</p>
						</div>

						<div class="guides-accordion-container">
							<!-- What You'll Learn -->
							<div id="curriculum-mod-1-learn" class="guides-accordion-item active">
								<button class="guides-accordion-header" aria-expanded="true" aria-controls="mod-1-learn-content">
									<span class="accordion-title"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-1-learn-content" class="guides-accordion-content" style="max-height: none;">
									<div class="guides-accordion-inner">
										<ul class="guide-topic-tags">
											<li>Artificial Intelligence</li>
											<li>Machine Learning &amp; Deep Learning</li>
											<li>Generative AI &amp; Foundation Models</li>
											<li>Large Language Models (LLMs)</li>
											<li>Tokens &amp; Context Windows</li>
											<li>Model Inference Dynamics</li>
											<li>LLM Limitations &amp; Hallucinations</li>
											<li>Chatbot vs. LLM Application</li>
											<li>Workflow vs. Automation</li>
											<li>AI Workflows</li>
											<li>Agent Architecture Fundamentals</li>
											<li>Agentic vs. Multi-Agent Systems</li>
											<li>When an Agent is Actually Useful</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Practical -->
							<div id="curriculum-mod-1-practical" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-1-practical-content">
									<span class="accordion-title"><?php esc_html_e( 'Practical / Hands-On Activity', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-1-practical-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="guide-activity-box">
											<p><?php esc_html_e( 'Classify real-world operational problems across five distinct tiers: Prompt, Workflow, Automation, Agent, or Multi-Agent System, defending your architectural choice with latency, cost, and complexity tradeoffs.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>

							<!-- Outcome -->
							<div id="curriculum-mod-1-outcome" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-1-outcome-content">
									<span class="accordion-title"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-1-outcome-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="guide-outcome-box">
											<p><?php esc_html_e( 'You will be able to distinguish between standard prompts, deterministic automations, and true agentic systems, clearly identifying when an agent is genuinely required versus an over-engineered liability.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>

					<!-- MODULE 2 BLOCK -->
					<article id="curriculum-mod-2" class="curriculum-guide-module-block" data-module="2">
						<div class="module-block-header">
							<div class="module-kicker-row">
								<span class="module-kicker">MODULE 02</span>
								<span class="module-duration-badge">3 Hours</span>
							</div>
							<h3 class="module-block-title"><?php esc_html_e( 'Prompt Engineering & LLM Control', 'twentytwentyfive-child' ); ?></h3>
							<p class="module-block-overview">
								<?php esc_html_e( 'Transition from conversational trial-and-error prompting to disciplined prompt engineering, structuring instructions, constraints, and dynamic inputs to produce deterministic, parser-ready outputs.', 'twentytwentyfive-child' ); ?>
							</p>
						</div>

						<div class="guides-accordion-container">
							<!-- What You'll Learn -->
							<div id="curriculum-mod-2-learn" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-2-learn-content">
									<span class="accordion-title"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-2-learn-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<ul class="guide-topic-tags">
											<li>Prompt Anatomy &amp; System Instructions</li>
											<li>User Instructions &amp; Context Injection</li>
											<li>Roles, Tasks, and Operational Constraints</li>
											<li>Few-Shot Prompting &amp; In-Context Demonstrations</li>
											<li>Structured Outputs &amp; JSON Schema Enforcement</li>
											<li>Prompt Chaining Principles</li>
											<li>Dynamic Prompt Construction</li>
											<li>Prompt Testing &amp; Validation</li>
											<li>Failure Analysis &amp; Edge Cases</li>
											<li>Hallucination Reduction Techniques</li>
											<li>Grounding Fundamentals</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Practical -->
							<div id="curriculum-mod-2-practical" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-2-practical-content">
									<span class="accordion-title"><?php esc_html_e( 'Practical / Hands-On Activity', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-2-practical-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="progression-pill-row">
											<span class="pill-step">Basic Prompt</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Structured Prompt</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Reliable Prompt</span>
											<span class="pill-sep">→</span>
											<span class="pill-step highlight">Reusable Prompt Pipeline</span>
										</div>
										<div class="guide-activity-box">
											<p><?php esc_html_e( 'Construct a dynamic prompt pipeline that takes arbitrary incoming data and reliably outputs strictly validated JSON matching an explicit schema.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>

							<!-- Outcome -->
							<div id="curriculum-mod-2-outcome" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-2-outcome-content">
									<span class="accordion-title"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-2-outcome-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="guide-outcome-box">
											<p><?php esc_html_e( 'You will be able to design structured, predictable prompts and reliable outputs that downstream code or automated workflows can parse without execution errors.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>

					<!-- MODULE 3 BLOCK -->
					<article id="curriculum-mod-3" class="curriculum-guide-module-block" data-module="3">
						<div class="module-block-header">
							<div class="module-kicker-row">
								<span class="module-kicker">MODULE 03</span>
								<span class="module-duration-badge">2 Hours</span>
							</div>
							<h3 class="module-block-title"><?php esc_html_e( 'Generative AI & Multimodal Systems', 'twentytwentyfive-child' ); ?></h3>
							<p class="module-block-overview">
								<?php esc_html_e( 'Expand beyond text into multi-modal models capable of processing and synthesizing images, audio, and documents within unified pipelines.', 'twentytwentyfive-child' ); ?>
							</p>
						</div>

						<div class="guides-accordion-container">
							<!-- What You'll Learn -->
							<div id="curriculum-mod-3-learn" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-3-learn-content">
									<span class="accordion-title"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-3-learn-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<ul class="guide-topic-tags">
											<li>Text, Image, Audio &amp; Video Modalities</li>
											<li>Multimodal Model Capabilities</li>
											<li>When to Use Different Modalities</li>
											<li>Prompt Strategies Across Media</li>
											<li>Model Strengths &amp; Operational Costs</li>
											<li>AI Content Pipelines</li>
											<li>Combining Modalities for Synthesis</li>
											<li>Pipeline Validation &amp; Quality Checks</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Practical -->
							<div id="curriculum-mod-3-practical" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-3-practical-content">
									<span class="accordion-title"><?php esc_html_e( 'Practical / Hands-On Activity', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-3-practical-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="progression-pill-row">
											<span class="pill-step">Idea</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Research</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Prompt</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Text</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Image</span>
											<span class="pill-sep">→</span>
											<span class="pill-step highlight">Final Content</span>
										</div>
										<div class="guide-activity-box">
											<p><?php esc_html_e( 'Build a content pipeline that takes a single core prompt, expands it into research, generates structured copy, triggers visual generation, and outputs a completed multimodal asset.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>

							<!-- Outcome -->
							<div id="curriculum-mod-3-outcome" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-3-outcome-content">
									<span class="accordion-title"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-3-outcome-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="guide-outcome-box">
											<p><?php esc_html_e( 'You will be able to construct reliable multimodal generation flows that combine text, image, and structured data into cohesive automated production pipelines.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>

					<!-- MODULE 4 BLOCK -->
					<article id="curriculum-mod-4" class="curriculum-guide-module-block" data-module="4">
						<div class="module-block-header">
							<div class="module-kicker-row">
								<span class="module-kicker">MODULE 04</span>
								<span class="module-duration-badge">4 Hours</span>
							</div>
							<h3 class="module-block-title"><?php esc_html_e( 'AI Workflows & Automation with n8n', 'twentytwentyfive-child' ); ?></h3>
							<p class="module-block-overview">
								<?php esc_html_e( 'Master workflow automation using n8n to connect live systems, webhooks, databases, and AI nodes into resilient production automations.', 'twentytwentyfive-child' ); ?>
							</p>
						</div>

						<div class="guides-accordion-container">
							<!-- What You'll Learn -->
							<div id="curriculum-mod-4-learn" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-4-learn-content">
									<span class="accordion-title"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-4-learn-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<ul class="guide-topic-tags">
											<li>n8n Architecture &amp; Nodes</li>
											<li>Triggers &amp; Webhooks</li>
											<li>Input/Output Payloads</li>
											<li>Conditional Logic &amp; Branching</li>
											<li>Data Transformation</li>
											<li>HTTP Requests &amp; REST APIs</li>
											<li>JSON Parsing &amp; Extraction</li>
											<li>Connecting External Services</li>
											<li>LLM Nodes Integration</li>
											<li>Structured Outputs in Workflows</li>
											<li>Conditional AI Logic</li>
											<li>Error Handling &amp; Retries</li>
											<li>Debugging Live Executions</li>
											<li>Workflow Architecture</li>
											<li>Traditional Automation</li>
											<li>AI-Assisted Automation</li>
											<li>AI Workflow</li>
											<li>Agentic Workflow Boundaries</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Practical -->
							<div id="curriculum-mod-4-practical" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-4-practical-content">
									<span class="accordion-title"><?php esc_html_e( 'Practical / Hands-On Activity', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-4-practical-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="guide-activity-box">
											<p><?php esc_html_e( 'Build a production-ready n8n workflow for lead/email/research processing with an explicit AI decision point, error-recovery branch, and external service notification.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>

							<!-- Outcome -->
							<div id="curriculum-mod-4-outcome" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-4-outcome-content">
									<span class="accordion-title"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-4-outcome-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="guide-outcome-box">
											<p><?php esc_html_e( 'You will be able to build, deploy, and troubleshoot complex multi-step automations that reliably integrate AI reasoning into real-world business workflows.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>

					<!-- MODULE 5 BLOCK -->
					<article id="curriculum-mod-5" class="curriculum-guide-module-block" data-module="5">
						<div class="module-block-header">
							<div class="module-kicker-row">
								<span class="module-kicker">MODULE 05</span>
								<span class="module-duration-badge">4 Hours</span>
							</div>
							<h3 class="module-block-title"><?php esc_html_e( 'Python for AI Systems', 'twentytwentyfive-child' ); ?></h3>
							<p class="module-block-overview">
								<?php esc_html_e( 'Focus strictly on the practical Python required to interact with AI models: HTTP clients, JSON processing, environment management, and structured function calling.', 'twentytwentyfive-child' ); ?>
							</p>
						</div>

						<div class="guides-accordion-container">
							<!-- What You'll Learn -->
							<div id="curriculum-mod-5-learn" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-5-learn-content">
									<span class="accordion-title"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-5-learn-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<ul class="guide-topic-tags">
											<li>Variables &amp; Core Data Structures</li>
											<li>Functions &amp; Scope</li>
											<li>Loops &amp; Iteration</li>
											<li>Conditionals &amp; Flow Control</li>
											<li>Dictionaries &amp; Nested Data</li>
											<li>JSON Parsing &amp; Serialization</li>
											<li>File I/O Basics</li>
											<li>Modules &amp; Package Imports</li>
											<li>Virtual Environments &amp; Dependencies</li>
											<li>Environment Variables &amp; API Keys</li>
											<li>API Requests with Requests/HTTPX</li>
											<li>Error Handling &amp; Try/Except</li>
											<li>Basic Async Concepts</li>
											<li>Calling LLM APIs Directly</li>
											<li>Parsing Structured Model Responses</li>
											<li>Tool Function Signatures</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Practical -->
							<div id="curriculum-mod-5-practical" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-5-practical-content">
									<span class="accordion-title"><?php esc_html_e( 'Practical / Hands-On Activity', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-5-practical-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="progression-pill-row">
											<span class="pill-step">Python</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">LLM API</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Structured Input</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Structured Output</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">External API</span>
											<span class="pill-sep">→</span>
											<span class="pill-step highlight">LLM + API</span>
										</div>
										<div class="guide-activity-box">
											<p><?php esc_html_e( 'Write a Python program that connects to an LLM API, sends user input, parses structured JSON from the response, and uses that data to make an external API request.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>

							<!-- Outcome -->
							<div id="curriculum-mod-5-outcome" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-5-outcome-content">
									<span class="accordion-title"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-5-outcome-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="guide-outcome-box">
											<p><?php esc_html_e( 'You will have the essential programmatic confidence to build, run, and debug Python scripts that interface directly with LLM models and external services.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>

					<!-- MODULE 6 BLOCK -->
					<article id="curriculum-mod-6" class="curriculum-guide-module-block" data-module="6">
						<div class="module-block-header">
							<div class="module-kicker-row">
								<span class="module-kicker">MODULE 06</span>
								<span class="module-duration-badge">6 Hours</span>
							</div>
							<h3 class="module-block-title"><?php esc_html_e( 'Building AI Agents', 'twentytwentyfive-child' ); ?></h3>
							<p class="module-block-overview">
								<?php esc_html_e( 'The foundational core: understand the anatomy of an agent (Model + Instructions + Tools + State) and implement the observe-decide-execute loop that enables autonomy.', 'twentytwentyfive-child' ); ?>
							</p>
						</div>

						<div class="guides-accordion-container">
							<!-- What You'll Learn -->
							<div id="curriculum-mod-6-learn" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-6-learn-content">
									<span class="accordion-title"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-6-learn-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<ul class="guide-topic-tags">
											<li>Agent Definition: Model + Instructions + Tools + State</li>
											<li>Tool Use &amp; Function Calling Mechanics</li>
											<li>API, Search, Calculator &amp; File Tools</li>
											<li>Tool Selection &amp; Argument Extraction</li>
											<li>The Autonomous Agent Loop</li>
											<li>Observing Tool Outputs &amp; State Updates</li>
											<li>Stopping Conditions &amp; Loop Termination</li>
											<li>Workflow vs. Agent Architectural Divide</li>
											<li>Error Recovery in Autonomous Loops</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Practical -->
							<div id="curriculum-mod-6-practical" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-6-practical-content">
									<span class="accordion-title"><?php esc_html_e( 'Practical / Hands-On Activity', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-6-practical-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="progression-pill-row">
											<span class="pill-step">Goal</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Understand</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Plan</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Choose Tool</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Execute</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Observe</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Decide</span>
											<span class="pill-sep">→</span>
											<span class="pill-step highlight">Final Answer</span>
										</div>
										<div class="guide-activity-box">
											<p><?php esc_html_e( 'Build a functioning autonomous agent that accepts a high-level goal, autonomously selects tools, executes them in a loop, inspects results, and returns the final answer.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>

							<!-- Outcome -->
							<div id="curriculum-mod-6-outcome" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-6-outcome-content">
									<span class="accordion-title"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-6-outcome-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="guide-outcome-box">
											<p><?php esc_html_e( 'You will understand how an agent loop fundamentally differs from a linear workflow and be able to implement functional tool-calling agents from scratch.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>

					<!-- MODULE 7 BLOCK -->
					<article id="curriculum-mod-7" class="curriculum-guide-module-block" data-module="7">
						<div class="module-block-header">
							<div class="module-kicker-row">
								<span class="module-kicker">MODULE 07</span>
								<span class="module-duration-badge">4 Hours</span>
							</div>
							<h3 class="module-block-title"><?php esc_html_e( 'Planning, Memory & Evaluation', 'twentytwentyfive-child' ); ?></h3>
							<p class="module-block-overview">
								<?php esc_html_e( 'Equip agents with task decomposition, short/long-term memory, self-reflection loops, and evaluation criteria to make them predictable and safe.', 'twentytwentyfive-child' ); ?>
							</p>
						</div>

						<div class="guides-accordion-container">
							<!-- What You'll Learn -->
							<div id="curriculum-mod-7-learn" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-7-learn-content">
									<span class="accordion-title"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-7-learn-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<ul class="guide-topic-tags">
											<li>Task Decomposition</li>
											<li>Multi-Step Complex Tasks</li>
											<li>Sequential vs. Parallel Execution</li>
											<li>Predefined Workflow vs. Dynamic Planning</li>
											<li>Conversation History</li>
											<li>Short-Term Working Memory</li>
											<li>Persistent Memory Concepts</li>
											<li>Context Window Management</li>
											<li>Reflection &amp; Self-Critique</li>
											<li>Generate → Evaluate → Improve</li>
											<li>Accuracy &amp; Completion Metrics</li>
											<li>Tool Selection Correctness</li>
											<li>Reliability &amp; Consistency</li>
											<li>Latency &amp; Token Cost Tradeoffs</li>
											<li>Loop Failure Handling</li>
											<li>Dead-End Detection</li>
											<li>Human-in-the-Loop Triggers</li>
											<li>Approval Gates for Sensitive Actions</li>
											<li>Output Sanitization &amp; Guardrails</li>
											<li>Deterministic Validation</li>
											<li>Preventing Destructive Actions</li>
											<li>Safe Testing Practices</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Practical -->
							<div id="curriculum-mod-7-practical" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-7-practical-content">
									<span class="accordion-title"><?php esc_html_e( 'Practical / Hands-On Activity', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-7-practical-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="progression-pill-row">
											<span class="pill-step">Generate</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Evaluate</span>
											<span class="pill-sep">→</span>
											<span class="pill-step highlight">Improve / Guardrail</span>
										</div>
										<div class="guide-activity-box">
											<p><?php esc_html_e( 'Take the single agent from Module 6 and add a memory layer, a reflection loop that checks output validity against explicit criteria, and a human-in-the-loop approval step before executing irreversible actions.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>

							<!-- Outcome -->
							<div id="curriculum-mod-7-outcome" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-7-outcome-content">
									<span class="accordion-title"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-7-outcome-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="guide-outcome-box">
											<p><?php esc_html_e( 'You will be able to design reliable agents that decompose complex problems, maintain state across interactions, evaluate their own outputs, and operate safely within defined boundaries.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>

					<!-- MODULE 8 BLOCK (CAPSTONE) -->
					<article id="curriculum-mod-8" class="curriculum-guide-module-block" data-module="8">
						<div class="module-block-header">
							<div class="module-kicker-row">
								<span class="module-kicker">MODULE 08</span>
								<span class="module-duration-badge">4 Hours</span>
							</div>
							<h3 class="module-block-title"><?php esc_html_e( 'Multi-Agent Systems + Capstone', 'twentytwentyfive-child' ); ?></h3>
							<p class="module-block-overview">
								<?php esc_html_e( 'Understand when a single agent becomes insufficient and multiple specialized agents are required. Learn foundational coordinator patterns, agent delegation, and build the final Capstone project.', 'twentytwentyfive-child' ); ?>
							</p>
						</div>

						<div class="guides-accordion-container">
							<!-- What You'll Learn -->
							<div id="curriculum-mod-8-learn" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-8-learn-content">
									<span class="accordion-title"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-8-learn-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<ul class="guide-topic-tags">
											<li>Why Multi-Agent? Dividing Complex Domains</li>
											<li>Specialist Roles: Coordinator, Researcher, Analyst, Writer</li>
											<li>Sequential Pattern &amp; Parallel Pattern</li>
											<li>Handoff &amp; Delegation Mechanics</li>
											<li>Coordinator + Specialists Architecture</li>
											<li>Inter-Agent Communication &amp; Data Formats</li>
											<li>Synthesizing Specialist Outputs</li>
											<li>Framework-Agnostic Multi-Agent Design</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Practical / Capstone -->
							<div id="curriculum-mod-8-practical" class="guides-accordion-item active">
								<button class="guides-accordion-header" aria-expanded="true" aria-controls="mod-8-practical-content">
									<span class="accordion-title"><?php esc_html_e( 'Capstone Project: AI Research & Decision Assistant', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-8-practical-content" class="guides-accordion-content" style="max-height: none;">
									<div class="guides-accordion-inner">
										<div class="progression-pill-row">
											<span class="pill-step">User Goal</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Coordinator</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Research Agent</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Information Tools</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Analysis Agent</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Evaluation / Reflection</span>
											<span class="pill-sep">→</span>
											<span class="pill-step highlight">Writer Agent</span>
											<span class="pill-sep">→</span>
											<span class="pill-step">Final Report</span>
										</div>
										<div class="guide-activity-box">
											<p><?php esc_html_e( 'Build the AI Research & Decision Assistant: a multi-agent system where a coordinator decomposes a user goal, a research agent gathers data using search tools, an analyst agent evaluates evidence and identifies tradeoffs, and a writer agent synthesizes the final structured decision memo.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>

							<!-- Outcome -->
							<div id="curriculum-mod-8-outcome" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-8-outcome-content">
									<span class="accordion-title"><?php esc_html_e( 'Module Outcome', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-8-outcome-content" class="guides-accordion-content">
									<div class="guides-accordion-inner">
										<div class="guide-outcome-box">
											<p><?php esc_html_e( 'You will graduate with a functional multi-agent prototype, an understanding of failure modes, and clear mental models for building future agentic workflows.', 'twentytwentyfive-child' ); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>

				</div><!-- /.curriculum-guide-content -->

			</div><!-- /.curriculum-guide-layout -->
		</section>'''

# 2. NEW CSS FOR SECTION 7 (GUIDE-STYLE MODULE NAVIGATION & CONTENT ACCORDIONS)
new_css = '''/* 7. Curriculum Layout: Reusing Existing Lohnour Guide Architecture */
.program-page .curriculum-guide-layout {
	display: grid;
	grid-template-columns: 280px minmax(0, 1fr);
	gap: var(--spacing-xl);
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
	padding: var(--spacing-md);
	background: var(--color-glass);
	-webkit-backdrop-filter: blur(10px);
	backdrop-filter: blur(10px);
	border: 1px solid var(--color-glass-border);
	border-radius: var(--radius-md);
	text-align: left;
}

.program-page .curriculum-outline-title {
	font-size: 1rem;
	margin-bottom: 15px;
	border-bottom: 1px solid var(--color-glass-border);
	padding-bottom: 10px;
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
	gap: 6px;
	text-align: left;
}

.program-page .outline-module {
	margin-bottom: 6px;
	text-align: left;
	border-radius: var(--radius-sm);
	transition: background var(--transition-fast);
}

.program-page .outline-module-header {
	width: 100%;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 8px 10px;
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
	font-size: 0.82rem;
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
	margin: 4px 0 8px 0;
	padding-left: 10px;
	display: flex;
	flex-direction: column;
	gap: 3px;
	text-align: left;
}

.program-page .guide-outline-child-link {
	display: block;
	padding: 4px 8px;
	font-size: 0.78rem;
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
	gap: var(--spacing-xl);
	min-width: 0;
	text-align: left;
}

.program-page .curriculum-guide-module-block {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border-subtle);
	border-radius: var(--radius-md);
	padding: var(--spacing-lg);
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
	margin-bottom: 6px;
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
	font-size: 1.35rem;
	font-family: var(--font-heading);
	color: var(--color-text);
	margin-bottom: 10px;
	line-height: 1.25;
}

.program-page .curriculum-guide-module-block .module-block-overview {
	font-size: 0.95rem;
	line-height: 1.6;
	color: var(--color-text-secondary);
	margin-bottom: var(--spacing-md);
}

/* Accordions Inside Right Modules (Guide-Style Disclosure) */
.program-page .curriculum-guide-module-block .guides-accordion-container {
	display: flex;
	flex-direction: column;
	gap: 8px;
	margin-top: var(--spacing-sm);
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
	padding: 14px 18px;
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
	font-size: 0.92rem;
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
	padding: 4px 18px 18px 18px;
	border-top: 1px solid var(--color-border-subtle);
}

.program-page .curriculum-guide-module-block .guide-topic-tags {
	display: flex;
	flex-wrap: wrap;
	gap: 6px;
	list-style: none;
	padding: 0;
	margin: 12px 0 0 0;
}

.program-page .curriculum-guide-module-block .guide-topic-tags li {
	display: inline-flex;
	padding: 4px 10px;
	font-size: 0.8rem;
	background: var(--color-bg-alt);
	color: var(--color-text-secondary);
	border-radius: var(--radius-sm);
	border: 1px solid var(--color-border-subtle);
}

.program-page .curriculum-guide-module-block .guide-activity-box,
.program-page .curriculum-guide-module-block .guide-outcome-box {
	margin-top: 12px;
	font-size: 0.92rem;
	line-height: 1.6;
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
	margin: 0 auto var(--spacing-md) auto;
}

.program-page .curriculum-mobile-outline-btn {
	width: 100%;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 12px 16px;
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border-subtle);
	border-radius: var(--radius-md);
	color: var(--color-text);
	font-size: 0.9rem;
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
		margin-bottom: var(--spacing-md);
		width: 100%;
	}

	.program-page .curriculum-guide-sidebar.mobile-open {
		display: block;
	}
}'''

# 3. NEW JAVASCRIPT FOR SECTION 7 (SIDEBAR NAVIGATION, ACCORDIONS, SCROLL-SPY)
new_js = '''	// 1. Guide-Style Curriculum Sidebar & Accordion Interactions
	const curriculumModules = document.querySelectorAll('.outline-module');
	const moduleBlocks = document.querySelectorAll('.curriculum-guide-module-block');
	const mobileOutlineBtn = document.getElementById('curriculum-mobile-outline-toggle');
	const curriculumSidebar = document.getElementById('curriculum-guide-sidebar');
	const mobileActiveLabel = document.getElementById('mobile-nav-active-label');

	// Mobile Outline Toggle
	if (mobileOutlineBtn && curriculumSidebar) {
		mobileOutlineBtn.addEventListener('click', function(e) {
			e.preventDefault();
			const isOpen = curriculumSidebar.classList.contains('mobile-open');
			if (isOpen) {
				curriculumSidebar.classList.remove('mobile-open');
				mobileOutlineBtn.classList.remove('active');
				mobileOutlineBtn.setAttribute('aria-expanded', 'false');
			} else {
				curriculumSidebar.classList.add('mobile-open');
				mobileOutlineBtn.classList.add('active');
				mobileOutlineBtn.setAttribute('aria-expanded', 'true');
			}
		});
	}

	// Sidebar Module Accordion & Navigation
	curriculumModules.forEach(mod => {
		const header = mod.querySelector('.outline-module-header');
		const modNum = mod.getAttribute('data-module');

		if (header) {
			header.addEventListener('click', function(e) {
				e.preventDefault();
				const isExpanded = mod.classList.contains('expanded') || mod.classList.contains('active');

				// Scroll smoothly to target module block
				const targetBlock = document.getElementById('curriculum-mod-' + modNum);
				if (targetBlock) {
					targetBlock.scrollIntoView({ behavior: 'smooth', block: 'start' });
				}

				// Update active module state
				curriculumModules.forEach(otherMod => {
					otherMod.classList.remove('active');
					otherMod.classList.remove('expanded');
					const otherH = otherMod.querySelector('.outline-module-header');
					if (otherH) otherH.setAttribute('aria-expanded', 'false');
				});

				mod.classList.add('active');
				mod.classList.add('expanded');
				header.setAttribute('aria-expanded', 'true');

				// Update mobile label
				const modTitle = header.querySelector('h5');
				if (mobileActiveLabel && modTitle) {
					mobileActiveLabel.textContent = modTitle.textContent;
				}

				// Close mobile sidebar after selection on small screens
				if (window.innerWidth <= 768 && curriculumSidebar) {
					curriculumSidebar.classList.remove('mobile-open');
					if (mobileOutlineBtn) {
						mobileOutlineBtn.classList.remove('active');
						mobileOutlineBtn.setAttribute('aria-expanded', 'false');
					}
				}
			});
		}

		// Child links inside sidebar module
		const childLinks = mod.querySelectorAll('.guide-outline-child-link');
		childLinks.forEach(link => {
			link.addEventListener('click', function(e) {
				const href = link.getAttribute('href');
				if (href && href.startsWith('#')) {
					e.preventDefault();
					const targetEl = document.querySelector(href);
					if (targetEl) {
						targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });

						// If target is an accordion, open it
						if (targetEl.classList.contains('guides-accordion-item')) {
							const itemHeader = targetEl.querySelector('.guides-accordion-header');
							const itemContent = targetEl.querySelector('.guides-accordion-content');
							if (!targetEl.classList.contains('active') && itemHeader && itemContent) {
								targetEl.classList.add('active');
								itemHeader.setAttribute('aria-expanded', 'true');
								itemContent.style.maxHeight = itemContent.scrollHeight + 'px';
							}
						}
					}

					// Update active child link
					document.querySelectorAll('.guide-outline-child-link').forEach(l => l.classList.remove('active'));
					link.classList.add('active');

					// Close mobile sidebar on small screens
					if (window.innerWidth <= 768 && curriculumSidebar) {
						curriculumSidebar.classList.remove('mobile-open');
						if (mobileOutlineBtn) {
							mobileOutlineBtn.classList.remove('active');
							mobileOutlineBtn.setAttribute('aria-expanded', 'false');
						}
					}
				}
			});
		});
	});

	// Guide Accordions inside each module block
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
	});

	// Scroll-Spy to sync left module navigation as user scrolls through right content
	if (moduleBlocks.length > 0 && curriculumModules.length > 0) {
		function syncModuleScrollSpy() {
			let activeFound = false;
			const scrollPos = window.scrollY + 180;

			for (let i = moduleBlocks.length - 1; i >= 0; i--) {
				const block = moduleBlocks[i];
				const rect = block.getBoundingClientRect();
				const top = rect.top + window.scrollY;

				if (top <= scrollPos) {
					const modNum = block.getAttribute('data-module');
					curriculumModules.forEach(m => {
						if (m.getAttribute('data-module') === modNum) {
							if (!m.classList.contains('active')) {
								curriculumModules.forEach(otherM => {
									otherM.classList.remove('active');
									otherM.classList.remove('expanded');
									const otherH = otherM.querySelector('.outline-module-header');
									if (otherH) otherH.setAttribute('aria-expanded', 'false');
								});
								m.classList.add('active');
								m.classList.add('expanded');
								const mHeader = m.querySelector('.outline-module-header');
								if (mHeader) mHeader.setAttribute('aria-expanded', 'true');
								const mTitle = m.querySelector('h5');
								if (mobileActiveLabel && mTitle) {
									mobileActiveLabel.textContent = mTitle.textContent;
								}
							}
						}
					});
					activeFound = true;
					break;
				}
			}
		}

		window.addEventListener('scroll', syncModuleScrollSpy, { passive: true });
	}'''

# Replace Section 7 HTML
html_regex = re.compile(r'<!-- 7\. 30-HOUR CURRICULUM.*?<!-- 8\. WHAT YOU\'LL BUILD -->', re.DOTALL)
if not html_regex.search(content):
	print("ERROR: Could not find Section 7 HTML boundary!", file=sys.stderr)
	sys.exit(1)

content = html_regex.sub(new_html + '\n\n\t\t<!-- 8. WHAT YOU\'LL BUILD -->', content)

# Replace Section 7 CSS
css_regex = re.compile(r'/\* 7\. Curriculum Interactive Accordion.*?/\* 8\. What You\'ll Build \*/', re.DOTALL)
if not css_regex.search(content):
	print("ERROR: Could not find Section 7 CSS boundary!", file=sys.stderr)
	sys.exit(1)

content = css_regex.sub(new_css + '\n\n/* 8. What You\'ll Build */', content)

# Replace Section 7 JS
js_regex = re.compile(r'// 1\. Curriculum Accordion Functionality.*?// 2\. FAQ Accordion Functionality', re.DOTALL)
if not js_regex.search(content):
	print("ERROR: Could not find Section 7 JS boundary!", file=sys.stderr)
	sys.exit(1)

content = js_regex.sub(new_js + '\n\n\t// 2. FAQ Accordion Functionality', content)

# Write updated content
with open(TARGET_FILE, "w", encoding="utf-8") as f:
	f.write(content)

print("SUCCESS: page-agentic-ai-foundations.php successfully updated with Guide layout!")
