<?php
/**
 * Template Name: Agentic AI Foundations Program
 * The dedicated program template for Agentic AI Foundations.
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main program-page agentic-ai-foundations-page">

	<!-- 1. PROGRAM HERO SECTION -->
	<header class="program-hero bg-alt">
		<div class="container program-hero-container">

			<!-- 1.1 Breadcrumb Context -->
			<nav class="program-hero-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'twentytwentyfive-child' ); ?>">
				<ol class="breadcrumb-list">
					<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'twentytwentyfive-child' ); ?></a></li>
					<li class="breadcrumb-separator" aria-hidden="true">/</li>
					<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/services' ) ); ?>"><?php esc_html_e( 'Services', 'twentytwentyfive-child' ); ?></a></li>
					<li class="breadcrumb-separator" aria-hidden="true">/</li>
					<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/coaching' ) ); ?>"><?php esc_html_e( 'Coaching', 'twentytwentyfive-child' ); ?></a></li>
					<li class="breadcrumb-separator" aria-hidden="true">/</li>
					<li class="breadcrumb-item breadcrumb-current" aria-current="page"><span><?php esc_html_e( 'Agentic AI Foundations', 'twentytwentyfive-child' ); ?></span></li>
				</ol>
			</nav>

			<!-- 1.2 Program Hero Grid (Architected for content + future featured image in Phase 5) -->
			<div class="program-hero-grid">
				<div class="program-hero-content">


					<h1 class="program-hero-title">
						<?php esc_html_e( 'Agentic AI Foundations', 'twentytwentyfive-child' ); ?>
					</h1>

					<p class="program-hero-desc">
						<?php esc_html_e( 'Build practical AI agents, automate workflows, and understand how intelligent systems are designed.', 'twentytwentyfive-child' ); ?>
					</p>

					<!-- Program Meta Chips -->
					<div class="program-hero-meta" aria-label="<?php esc_attr_e( 'Program Highlights', 'twentytwentyfive-child' ); ?>">
						<div class="meta-chip">
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
							<span><strong>30</strong> Hours</span>
						</div>
						<div class="meta-chip">
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
							<span>~2 Months (~5 hrs/wk)</span>
						</div>
						<div class="meta-chip">
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
							<span>Max 5 Seats</span>
						</div>
						<div class="meta-chip">
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="2"></circle><path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"></path></svg>
							<span>Live Founder-Led</span>
						</div>
					</div>

					<!-- Program CTA Actions -->
					<div class="program-hero-actions">
						<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary btn-lg hero-btn-primary">
							<?php esc_html_e( 'Enquire About Next Cohort', 'twentytwentyfive-child' ); ?>
						</a>
						<a href="#curriculum" class="btn btn-outline btn-lg hero-btn-secondary">
							<?php esc_html_e( 'Explore 30-Hour Curriculum ↓', 'twentytwentyfive-child' ); ?>
						</a>
					</div>

				</div>

				<!-- 1.6 Theme-Aware Responsive Program Banner (Phase 5A) -->
				<div class="program-hero-banner" aria-label="<?php esc_attr_e( 'Program Banner', 'twentytwentyfive-child' ); ?>">
					<img 
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/agentic-ai-foundations-dark.png' ); ?>" 
						alt="<?php esc_attr_e( 'Agentic AI Foundations participants building AI agent systems', 'twentytwentyfive-child' ); ?>" 
						class="banner-theme-dark" 
						width="1774" 
						height="887" 
						decoding="async" 
						fetchpriority="high"
					>
					<img 
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/agentic-ai-foundations-light.jpeg' ); ?>" 
						alt="<?php esc_attr_e( 'Agentic AI Foundations participants building AI agent systems', 'twentytwentyfive-child' ); ?>" 
						class="banner-theme-light" 
						width="1600" 
						height="800" 
						decoding="async" 
						fetchpriority="high"
					>
				</div>
			</div>

		</div>
	</header>

	<!-- 2. ANCHOR NAVIGATION -->
	<nav class="program-anchor-nav" aria-label="<?php esc_attr_e( 'Program Sections', 'twentytwentyfive-child' ); ?>">
		<div class="container">
			<ul class="anchor-nav-list">
				<li><a href="#overview" class="anchor-link active"><?php esc_html_e( 'Overview', 'twentytwentyfive-child' ); ?></a></li>
				<li><a href="#why-program" class="anchor-link"><?php esc_html_e( 'Why This Program', 'twentytwentyfive-child' ); ?></a></li>
				<li><a href="#what-youll-learn" class="anchor-link"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></a></li>
				<li><a href="#curriculum" class="anchor-link"><?php esc_html_e( 'Curriculum', 'twentytwentyfive-child' ); ?></a></li>
				<li><a href="#what-youll-build" class="anchor-link"><?php esc_html_e( "What You'll Build", 'twentytwentyfive-child' ); ?></a></li>
				<li><a href="#outcomes" class="anchor-link"><?php esc_html_e( 'Outcomes', 'twentytwentyfive-child' ); ?></a></li>
				<li><a href="#format" class="anchor-link"><?php esc_html_e( 'Format', 'twentytwentyfive-child' ); ?></a></li>
				<li><a href="#faqs" class="anchor-link"><?php esc_html_e( 'FAQs', 'twentytwentyfive-child' ); ?></a></li>
			</ul>
		</div>
	</nav>

	<div class="container program-body-container">

		<!-- 4. PROGRAM OVERVIEW -->
		<section id="overview" class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Foundational Architecture', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Program Overview', 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'A practical, build-first coaching cohort designed to take you from foundational concepts to working autonomous agent architectures.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<div class="overview-grid">
				<div class="overview-main glass-card">
					<h3><?php esc_html_e( 'What is Agentic AI Foundations?', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Agentic AI Foundations is an intensive, small-group live coaching program that teaches the core principles, engineering frameworks, and practical implementation patterns needed to build autonomous AI systems.', 'twentytwentyfive-child' ); ?></p>
					<p><?php esc_html_e( 'Rather than treating AI as a static text prompt interface, this program treats AI as an autonomous decision engine capable of using external tools, interacting with APIs, executing multi-step task loops, and collaborating in multi-agent workflows.', 'twentytwentyfive-child' ); ?></p>
					<p><?php esc_html_e( 'Delivered live in cohorts of no more than 5 participants, each session focuses on direct hands-on implementation: writing code, configuring automated workflow nodes, connecting tools, and testing live agent behaviors with personal feedback from the instructor.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="overview-highlights glass-card">
					<h3><?php esc_html_e( 'Key Cohort Highlights', 'twentytwentyfive-child' ); ?></h3>
					<ul class="highlights-list">
						<li>
							<span class="bullet-icon" aria-hidden="true"></span>
							<div>
								<strong><?php esc_html_e( 'Build-First Pedagogy', 'twentytwentyfive-child' ); ?></strong>
								<p><?php esc_html_e( 'Every concept connects directly to a live build—from single prompt chains to tool-calling agents.', 'twentytwentyfive-child' ); ?></p>
							</div>
						</li>
						<li>
							<span class="bullet-icon" aria-hidden="true"></span>
							<div>
								<strong><?php esc_html_e( 'Small Cohort Intimacy', 'twentytwentyfive-child' ); ?></strong>
								<p><?php esc_html_e( 'Strictly capped at 5 participants to guarantee direct code review, real-time debugging, and deep technical discussion.', 'twentytwentyfive-child' ); ?></p>
							</div>
						</li>
						<li>
							<span class="bullet-icon" aria-hidden="true"></span>
							<div>
								<strong><?php esc_html_e( 'Framework-Agnostic Clarity', 'twentytwentyfive-child' ); ?></strong>
								<p><?php esc_html_e( 'Master fundamental agent mechanics in pure Python and n8n before relying on complex abstraction layers.', 'twentytwentyfive-child' ); ?></p>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</section>

		<!-- 5. WHY THIS PROGRAM -->
		<section id="why-program" class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'The Learning Philosophy', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Why This Program', 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'A disciplined conceptual journey from basic prompting to autonomous orchestration.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<div class="philosophy-card glass-card">
				<p class="lead-text">
					<?php esc_html_e( 'The purpose of this program is not to make exaggerated claims of turning you into an "AI expert in 30 hours." Its purpose is to give you the solid, grounded mental models and practical engineering skills required to design, build, and evaluate functional agentic systems.', 'twentytwentyfive-child' ); ?>
				</p>

				<div class="progression-flow">
					<div class="step-pill"><span>01</span> <?php esc_html_e( 'Understand', 'twentytwentyfive-child' ); ?></div>
					<div class="step-arrow">→</div>
					<div class="step-pill"><span>02</span> <?php esc_html_e( 'Prompt', 'twentytwentyfive-child' ); ?></div>
					<div class="step-arrow">→</div>
					<div class="step-pill"><span>03</span> <?php esc_html_e( 'Automate', 'twentytwentyfive-child' ); ?></div>
					<div class="step-arrow">→</div>
					<div class="step-pill"><span>04</span> <?php esc_html_e( 'Code', 'twentytwentyfive-child' ); ?></div>
					<div class="step-arrow">→</div>
					<div class="step-pill"><span>05</span> <?php esc_html_e( 'Build Agents', 'twentytwentyfive-child' ); ?></div>
					<div class="step-arrow">→</div>
					<div class="step-pill"><span>06</span> <?php esc_html_e( 'Evaluate', 'twentytwentyfive-child' ); ?></div>
					<div class="step-arrow">→</div>
					<div class="step-pill highlight"><span>07</span> <?php esc_html_e( 'Orchestrate', 'twentytwentyfive-child' ); ?></div>
				</div>

				<div class="philosophy-columns">
					<div class="phil-col">
						<h3><?php esc_html_e( 'Clear Mental Models', 'twentytwentyfive-child' ); ?></h3>
						<p><?php esc_html_e( 'Understand precisely where a simple prompt is enough, where a deterministic automation is superior, and where an autonomous agent provides true leverage.', 'twentytwentyfive-child' ); ?></p>
					</div>
					<div class="phil-col">
						<h3><?php esc_html_e( 'Practical Independence', 'twentytwentyfive-child' ); ?></h3>
						<p><?php esc_html_e( 'Gain confidence connecting external tools, structuring JSON outputs, writing resilient Python handlers, and managing agent state without black-box confusion.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>
			</div>
		</section>

		<!-- 6. WHAT YOU'LL LEARN -->
		<section id="what-youll-learn" class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Curriculum Overview', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'Eight structured modules covering 30 live instructional and hands-on building hours.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<div class="module-summary-grid">
				<div class="module-summary-card glass-card hover-lift">
					<div class="mod-num">01</div>
					<h3><?php esc_html_e( 'AI, LLM & Agentic Foundations', 'twentytwentyfive-child' ); ?></h3>
					<span class="mod-time">3 Hours</span>
					<p><?php esc_html_e( 'Foundation models, tokens, context windows, model limitations, and distinguishing workflows from true agents.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="module-summary-card glass-card hover-lift">
					<div class="mod-num">02</div>
					<h3><?php esc_html_e( 'Prompt Engineering & LLM Control', 'twentytwentyfive-child' ); ?></h3>
					<span class="mod-time">3 Hours</span>
					<p><?php esc_html_e( 'Structured outputs, JSON enforcement, prompt chaining, dynamic templates, and hallucination reduction.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="module-summary-card glass-card hover-lift">
					<div class="mod-num">03</div>
					<h3><?php esc_html_e( 'Generative AI & Multimodal Systems', 'twentytwentyfive-child' ); ?></h3>
					<span class="mod-time">2 Hours</span>
					<p><?php esc_html_e( 'Multi-modal model capabilities, audio, visual inputs, and combining modalities into cohesive content pipelines.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="module-summary-card glass-card hover-lift">
					<div class="mod-num">04</div>
					<h3><?php esc_html_e( 'AI Workflows & Automation with n8n', 'twentytwentyfive-child' ); ?></h3>
					<span class="mod-time">4 Hours</span>
					<p><?php esc_html_e( 'Triggers, webhooks, API requests, data transformations, and embedding AI decision points into workflows.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="module-summary-card glass-card hover-lift">
					<div class="mod-num">05</div>
					<h3><?php esc_html_e( 'Python for AI Systems', 'twentytwentyfive-child' ); ?></h3>
					<span class="mod-time">4 Hours</span>
					<p><?php esc_html_e( 'Targeted Python fundamentals: API requests, JSON parsing, error handling, and making direct LLM model calls.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="module-summary-card glass-card hover-lift">
					<div class="mod-num">06</div>
					<h3><?php esc_html_e( 'Building AI Agents', 'twentytwentyfive-child' ); ?></h3>
					<span class="mod-time">6 Hours</span>
					<p><?php esc_html_e( 'The core module: Model + Instructions + Tools + State. Function calling, tool execution loops, and autonomous decisions.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="module-summary-card glass-card hover-lift">
					<div class="mod-num">07</div>
					<h3><?php esc_html_e( 'Planning, Memory & Evaluation', 'twentytwentyfive-child' ); ?></h3>
					<span class="mod-time">4 Hours</span>
					<p><?php esc_html_e( 'Task decomposition, state management, reflection loops, reliability evaluation, and human-in-the-loop guardrails.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="module-summary-card glass-card hover-lift">
					<div class="mod-num">08</div>
					<h3><?php esc_html_e( 'Multi-Agent Systems + Capstone', 'twentytwentyfive-child' ); ?></h3>
					<span class="mod-time">4 Hours</span>
					<p><?php esc_html_e( 'Specialized agent delegation patterns, coordinator architectures, and constructing the comprehensive final Capstone.', 'twentytwentyfive-child' ); ?></p>
				</div>
			</div>
		</section>

				<!-- 7. 30-HOUR CURRICULUM (GUIDE-STYLE MODULE NAVIGATION & CONTENT) -->
		<section id="curriculum" class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Interactive Detailed Breakdown', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( '30-Hour Curriculum', 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'Explore the full week-by-week module syllabus. Inspect topics, hands-on activities, and tangible learning outcomes.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<!-- Curriculum Module Content Area -->
			<div class="curriculum-guide-layout">
				<div class="curriculum-guide-content">

					<!-- MODULE 1 BLOCK -->
										<!-- MODULE 1 BLOCK -->
					<article id="curriculum-mod-1" class="curriculum-guide-module-block" data-module="1">
						<h3 class="module-accordion-heading">
							<button type="button" class="module-accordion-header" id="curriculum-mod-1-header" aria-expanded="false" aria-controls="curriculum-mod-1-content">
								<span class="module-header-main">
									<span class="module-number-pill">01</span>
									<span class="module-title-text"><span class="module-prefix"><?php esc_html_e( 'Module 1 — ', 'twentytwentyfive-child' ); ?></span><?php esc_html_e( 'AI, LLM & Agentic Foundations', 'twentytwentyfive-child' ); ?></span>
								</span>
								<span class="module-header-meta">
									<span class="module-duration-badge">3 Hours</span>
									<span class="module-toggle-icon" aria-hidden="true">+</span>
								</span>
							</button>
						</h3>

						<div id="curriculum-mod-1-content" class="module-accordion-content" role="region" aria-labelledby="curriculum-mod-1-header">
							<div class="module-accordion-body">
								<p class="module-block-overview">
								<?php esc_html_e( 'Establish precise conceptual grounding in generative models, token dynamics, inference mechanics, and the architectural differences between static chatbot prompts, automated workflows, and true autonomous agents.', 'twentytwentyfive-child' ); ?>
							</p>

								<div class="guides-accordion-container">
							<!-- What You'll Learn -->
							<div id="curriculum-mod-1-learn" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-1-learn-content">
									<span class="accordion-title"><?php esc_html_e( "What You'll Learn", 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-1-learn-content" class="guides-accordion-content">
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
							</div>
						</div>
					</article>

					<!-- MODULE 2 BLOCK -->
										<!-- MODULE 2 BLOCK -->
					<article id="curriculum-mod-2" class="curriculum-guide-module-block" data-module="2">
						<h3 class="module-accordion-heading">
							<button type="button" class="module-accordion-header" id="curriculum-mod-2-header" aria-expanded="false" aria-controls="curriculum-mod-2-content">
								<span class="module-header-main">
									<span class="module-number-pill">02</span>
									<span class="module-title-text"><span class="module-prefix"><?php esc_html_e( 'Module 2 — ', 'twentytwentyfive-child' ); ?></span><?php esc_html_e( 'Prompt Engineering & LLM Control', 'twentytwentyfive-child' ); ?></span>
								</span>
								<span class="module-header-meta">
									<span class="module-duration-badge">3 Hours</span>
									<span class="module-toggle-icon" aria-hidden="true">+</span>
								</span>
							</button>
						</h3>

						<div id="curriculum-mod-2-content" class="module-accordion-content" role="region" aria-labelledby="curriculum-mod-2-header">
							<div class="module-accordion-body">
								<p class="module-block-overview">
								<?php esc_html_e( 'Transition from conversational trial-and-error prompting to disciplined prompt engineering, structuring instructions, constraints, and dynamic inputs to produce deterministic, parser-ready outputs.', 'twentytwentyfive-child' ); ?>
							</p>

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
							</div>
						</div>
					</article>

					<!-- MODULE 3 BLOCK -->
										<!-- MODULE 3 BLOCK -->
					<article id="curriculum-mod-3" class="curriculum-guide-module-block" data-module="3">
						<h3 class="module-accordion-heading">
							<button type="button" class="module-accordion-header" id="curriculum-mod-3-header" aria-expanded="false" aria-controls="curriculum-mod-3-content">
								<span class="module-header-main">
									<span class="module-number-pill">03</span>
									<span class="module-title-text"><span class="module-prefix"><?php esc_html_e( 'Module 3 — ', 'twentytwentyfive-child' ); ?></span><?php esc_html_e( 'Generative AI & Multimodal Systems', 'twentytwentyfive-child' ); ?></span>
								</span>
								<span class="module-header-meta">
									<span class="module-duration-badge">2 Hours</span>
									<span class="module-toggle-icon" aria-hidden="true">+</span>
								</span>
							</button>
						</h3>

						<div id="curriculum-mod-3-content" class="module-accordion-content" role="region" aria-labelledby="curriculum-mod-3-header">
							<div class="module-accordion-body">
								<p class="module-block-overview">
								<?php esc_html_e( 'Expand beyond text into multi-modal models capable of processing and synthesizing images, audio, and documents within unified pipelines.', 'twentytwentyfive-child' ); ?>
							</p>

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
							</div>
						</div>
					</article>

					<!-- MODULE 4 BLOCK -->
										<!-- MODULE 4 BLOCK -->
					<article id="curriculum-mod-4" class="curriculum-guide-module-block" data-module="4">
						<h3 class="module-accordion-heading">
							<button type="button" class="module-accordion-header" id="curriculum-mod-4-header" aria-expanded="false" aria-controls="curriculum-mod-4-content">
								<span class="module-header-main">
									<span class="module-number-pill">04</span>
									<span class="module-title-text"><span class="module-prefix"><?php esc_html_e( 'Module 4 — ', 'twentytwentyfive-child' ); ?></span><?php esc_html_e( 'AI Workflows & Automation with n8n', 'twentytwentyfive-child' ); ?></span>
								</span>
								<span class="module-header-meta">
									<span class="module-duration-badge">4 Hours</span>
									<span class="module-toggle-icon" aria-hidden="true">+</span>
								</span>
							</button>
						</h3>

						<div id="curriculum-mod-4-content" class="module-accordion-content" role="region" aria-labelledby="curriculum-mod-4-header">
							<div class="module-accordion-body">
								<p class="module-block-overview">
								<?php esc_html_e( 'Master workflow automation using n8n to connect live systems, webhooks, databases, and AI nodes into resilient production automations.', 'twentytwentyfive-child' ); ?>
							</p>

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
							</div>
						</div>
					</article>

					<!-- MODULE 5 BLOCK -->
										<!-- MODULE 5 BLOCK -->
					<article id="curriculum-mod-5" class="curriculum-guide-module-block" data-module="5">
						<h3 class="module-accordion-heading">
							<button type="button" class="module-accordion-header" id="curriculum-mod-5-header" aria-expanded="false" aria-controls="curriculum-mod-5-content">
								<span class="module-header-main">
									<span class="module-number-pill">05</span>
									<span class="module-title-text"><span class="module-prefix"><?php esc_html_e( 'Module 5 — ', 'twentytwentyfive-child' ); ?></span><?php esc_html_e( 'Python for AI Systems', 'twentytwentyfive-child' ); ?></span>
								</span>
								<span class="module-header-meta">
									<span class="module-duration-badge">4 Hours</span>
									<span class="module-toggle-icon" aria-hidden="true">+</span>
								</span>
							</button>
						</h3>

						<div id="curriculum-mod-5-content" class="module-accordion-content" role="region" aria-labelledby="curriculum-mod-5-header">
							<div class="module-accordion-body">
								<p class="module-block-overview">
								<?php esc_html_e( 'Focus strictly on the practical Python required to interact with AI models: HTTP clients, JSON processing, environment management, and structured function calling.', 'twentytwentyfive-child' ); ?>
							</p>

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
							</div>
						</div>
					</article>

					<!-- MODULE 6 BLOCK -->
										<!-- MODULE 6 BLOCK -->
					<article id="curriculum-mod-6" class="curriculum-guide-module-block" data-module="6">
						<h3 class="module-accordion-heading">
							<button type="button" class="module-accordion-header" id="curriculum-mod-6-header" aria-expanded="false" aria-controls="curriculum-mod-6-content">
								<span class="module-header-main">
									<span class="module-number-pill">06</span>
									<span class="module-title-text"><span class="module-prefix"><?php esc_html_e( 'Module 6 — ', 'twentytwentyfive-child' ); ?></span><?php esc_html_e( 'Building AI Agents', 'twentytwentyfive-child' ); ?></span>
								</span>
								<span class="module-header-meta">
									<span class="module-duration-badge">6 Hours</span>
									<span class="module-toggle-icon" aria-hidden="true">+</span>
								</span>
							</button>
						</h3>

						<div id="curriculum-mod-6-content" class="module-accordion-content" role="region" aria-labelledby="curriculum-mod-6-header">
							<div class="module-accordion-body">
								<p class="module-block-overview">
								<?php esc_html_e( 'The foundational core: understand the anatomy of an agent (Model + Instructions + Tools + State) and implement the observe-decide-execute loop that enables autonomy.', 'twentytwentyfive-child' ); ?>
							</p>

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
							</div>
						</div>
					</article>

					<!-- MODULE 7 BLOCK -->
										<!-- MODULE 7 BLOCK -->
					<article id="curriculum-mod-7" class="curriculum-guide-module-block" data-module="7">
						<h3 class="module-accordion-heading">
							<button type="button" class="module-accordion-header" id="curriculum-mod-7-header" aria-expanded="false" aria-controls="curriculum-mod-7-content">
								<span class="module-header-main">
									<span class="module-number-pill">07</span>
									<span class="module-title-text"><span class="module-prefix"><?php esc_html_e( 'Module 7 — ', 'twentytwentyfive-child' ); ?></span><?php esc_html_e( 'Agentic Systems: Planning, Memory & Evaluation', 'twentytwentyfive-child' ); ?></span>
								</span>
								<span class="module-header-meta">
									<span class="module-duration-badge">4 Hours</span>
									<span class="module-toggle-icon" aria-hidden="true">+</span>
								</span>
							</button>
						</h3>

						<div id="curriculum-mod-7-content" class="module-accordion-content" role="region" aria-labelledby="curriculum-mod-7-header">
							<div class="module-accordion-body">
								<p class="module-block-overview">
								<?php esc_html_e( 'Equip agents with task decomposition, short/long-term memory, self-reflection loops, and evaluation criteria to make them predictable and safe.', 'twentytwentyfive-child' ); ?>
							</p>

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
							</div>
						</div>
					</article>

					<!-- MODULE 8 BLOCK (CAPSTONE) -->
										<!-- MODULE 8 BLOCK -->
					<article id="curriculum-mod-8" class="curriculum-guide-module-block" data-module="8">
						<h3 class="module-accordion-heading">
							<button type="button" class="module-accordion-header" id="curriculum-mod-8-header" aria-expanded="false" aria-controls="curriculum-mod-8-content">
								<span class="module-header-main">
									<span class="module-number-pill">08</span>
									<span class="module-title-text"><span class="module-prefix"><?php esc_html_e( 'Module 8 — ', 'twentytwentyfive-child' ); ?></span><?php esc_html_e( 'Multi-Agent Systems + Capstone', 'twentytwentyfive-child' ); ?></span>
								</span>
								<span class="module-header-meta">
									<span class="module-duration-badge">4 Hours</span>
									<span class="module-toggle-icon" aria-hidden="true">+</span>
								</span>
							</button>
						</h3>

						<div id="curriculum-mod-8-content" class="module-accordion-content" role="region" aria-labelledby="curriculum-mod-8-header">
							<div class="module-accordion-body">
								<p class="module-block-overview">
								<?php esc_html_e( 'Understand when a single agent becomes insufficient and multiple specialized agents are required. Learn foundational coordinator patterns, agent delegation, and build the final Capstone project.', 'twentytwentyfive-child' ); ?>
							</p>

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
							<div id="curriculum-mod-8-practical" class="guides-accordion-item">
								<button class="guides-accordion-header" aria-expanded="false" aria-controls="mod-8-practical-content">
									<span class="accordion-title"><?php esc_html_e( 'Capstone Project: AI Research & Decision Assistant', 'twentytwentyfive-child' ); ?></span>
									<span class="accordion-icon" aria-hidden="true">+</span>
								</button>
								<div id="mod-8-practical-content" class="guides-accordion-content">
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
							</div>
						</div>
					</article>

				</div><!-- /.curriculum-guide-content -->

			</div><!-- /.curriculum-guide-layout -->
		</section>

		<!-- 8. WHAT YOU'LL BUILD -->
		<section id="what-youll-build" class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Tangible Deliverables', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( "What You'll Build", 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'A progressive series of functional artifacts created throughout your 30 hours of live building.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<div class="build-progression-grid">
				<div class="build-card glass-card hover-lift">
					<div class="build-step">01</div>
					<h3><?php esc_html_e( 'Prompt Pipeline', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Deterministic system instructions with few-shot examples and strict JSON validation.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="build-card glass-card hover-lift">
					<div class="build-step">02</div>
					<h3><?php esc_html_e( 'AI Content Workflow', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Multimodal processing pipeline coordinating text and visual synthesis from a single input.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="build-card glass-card hover-lift">
					<div class="build-step">03</div>
					<h3><?php esc_html_e( 'n8n Automation', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Production-ready workflow connecting webhooks, data nodes, and AI reasoning points.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="build-card glass-card hover-lift">
					<div class="build-step">04</div>
					<h3><?php esc_html_e( 'Python AI Bridge', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Targeted script handling API requests, token management, error handling, and structured parsing.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="build-card glass-card hover-lift">
					<div class="build-step">05</div>
					<h3><?php esc_html_e( 'Single AI Agent', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'An autonomous tool-calling agent that iterates through an observe-and-decide execution loop.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="build-card glass-card hover-lift">
					<div class="build-step">06</div>
					<h3><?php esc_html_e( 'Evaluated Agent System', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Agent with conversation memory, reflection self-correction, and human-in-the-loop guardrails.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="build-card glass-card hover-lift capstone-card">
					<div class="build-step">07</div>
					<h3><?php esc_html_e( 'Capstone: Multi-Agent System', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'AI Research & Decision Assistant: Coordinator orchestrating Researcher, Analyst, and Writer specialist agents.', 'twentytwentyfive-child' ); ?></p>
				</div>
			</div>
		</section>

		<!-- 9. PROGRAM OUTCOMES -->
		<section id="outcomes" class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Concrete Capabilities', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Program Outcomes', 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'The practical competencies you will possess upon completing this foundational coaching program.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<div class="outcomes-statement-card glass-card">
				<div class="north-star-quote">
					<p>
						"<?php esc_html_e( 'By the end of the program, you will understand how modern agentic systems work, know when an agent is the right solution, design AI-powered workflows, connect models to external tools and APIs, build basic AI systems with Python, create a functional single-agent system, evaluate and improve agent behavior, and build a simple multi-agent workflow.', 'twentytwentyfive-child' ); ?>"
					</p>
				</div>

				<div class="outcomes-checklist-grid">
					<div class="outcome-item">
						<span class="check-icon">✓</span>
						<div>
							<strong><?php esc_html_e( 'Architectural Literacy', 'twentytwentyfive-child' ); ?></strong>
							<p><?php esc_html_e( 'Articulate clearly how model instructions, tool definitions, memory, and loops interact to create agentic behavior.', 'twentytwentyfive-child' ); ?></p>
						</div>
					</div>

					<div class="outcome-item">
						<span class="check-icon">✓</span>
						<div>
							<strong><?php esc_html_e( 'Pragmatic Tool Selection', 'twentytwentyfive-child' ); ?></strong>
							<p><?php esc_html_e( 'Determine when a problem requires a simple prompt, a deterministic workflow, or an autonomous agent.', 'twentytwentyfive-child' ); ?></p>
						</div>
					</div>

					<div class="outcome-item">
						<span class="check-icon">✓</span>
						<div>
							<strong><?php esc_html_e( 'Hands-on Implementation', 'twentytwentyfive-child' ); ?></strong>
							<p><?php esc_html_e( 'Build, run, and debug Python-based agents and n8n automations that interact with live third-party APIs.', 'twentytwentyfive-child' ); ?></p>
						</div>
					</div>

					<div class="outcome-item">
						<span class="check-icon">✓</span>
						<div>
							<strong><?php esc_html_e( 'Safety & Guardrails', 'twentytwentyfive-child' ); ?></strong>
							<p><?php esc_html_e( 'Incorporate human-in-the-loop approvals, structured output validation, and failure-handling mechanisms.', 'twentytwentyfive-child' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- 10. SKILLS YOU'LL BUILD -->
		<section class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Toolbox & Disciplines', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( "Skills You'll Build", 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'Direct competencies cultivated through hands-on exercises across the 8 modules.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<div class="skills-tag-cloud">
				<span class="skill-tag">Agentic AI</span>
				<span class="skill-tag">Prompt Engineering</span>
				<span class="skill-tag">Generative AI</span>
				<span class="skill-tag">Python for AI</span>
				<span class="skill-tag">AI Automation</span>
				<span class="skill-tag">n8n Workflows</span>
				<span class="skill-tag">API Integration</span>
				<span class="skill-tag">Workflow Design</span>
				<span class="skill-tag">AI Agents</span>
				<span class="skill-tag">Tool Calling</span>
				<span class="skill-tag">Agent Evaluation</span>
				<span class="skill-tag">Multi-Agent Systems</span>
			</div>
		</section>

		<!-- 11. CAREER APPLICATIONS -->
		<section class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Practical Relevance', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Where These Skills Can Be Applied', 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'Foundational competencies for modern AI implementation across engineering, operations, and product roles.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<div class="career-grid">
				<div class="career-card glass-card">
					<h3><?php esc_html_e( 'AI Automation Specialist', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Designing and maintaining intelligent business workflows that connect enterprise tools with LLM decision nodes.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="career-card glass-card">
					<h3><?php esc_html_e( 'AI Workflow Developer', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Constructing automated data pipelines, prompt chains, and automated tasks using n8n and Python.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="career-card glass-card">
					<h3><?php esc_html_e( 'AI Solutions Associate', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Prototyping practical AI implementations and evaluating technology options for teams and clients.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="career-card glass-card">
					<h3><?php esc_html_e( 'Prompt Engineer', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Crafting disciplined system instructions, JSON output schemas, and testing pipelines for reliable AI apps.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="career-card glass-card">
					<h3><?php esc_html_e( 'AI Implementation Specialist', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Assisting organizations with the pragmatic adoption of agents and safe, human-reviewed automation.', 'twentytwentyfive-child' ); ?></p>
				</div>
			</div>

			<p class="career-disclaimer">
				<?php esc_html_e( 'Note: This program provides grounded engineering foundations and practical skills. It does not provide job placement guarantees or formal academic degree credentials.', 'twentytwentyfive-child' ); ?>
			</p>
		</section>

		<!-- 12. HOW COACHING WORKS -->
		<section id="format" class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'The Coaching Experience', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'How Coaching Works', 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'This is live small-group coaching, not a pre-recorded video repository.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<div class="coaching-flow-grid">
				<div class="coaching-step glass-card">
					<div class="step-num">01</div>
					<h3><?php esc_html_e( 'Live Cohort Sessions', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( '30 total hours delivered live over approximately 2 months (~5 hours per week of focused instruction and interactive coding).', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="coaching-step glass-card">
					<div class="step-num">02</div>
					<h3><?php esc_html_e( 'Maximum 5 Participants', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Small-group cohorts ensure your questions are answered immediately and every participant receives individual attention.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="coaching-step glass-card">
					<div class="step-num">03</div>
					<h3><?php esc_html_e( 'Founder-Led Instruction', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Guided directly by Lohnour Pro leadership with active industry perspective, clear explanations, and no teaching assistants.', 'twentytwentyfive-child' ); ?></p>
				</div>

				<div class="coaching-step glass-card">
					<div class="step-num">04</div>
					<h3><?php esc_html_e( 'Hands-On Building & Code Reviews', 'twentytwentyfive-child' ); ?></h3>
					<p><?php esc_html_e( 'Write code, connect live APIs, debug agent loops during sessions, and receive real-time constructive feedback.', 'twentytwentyfive-child' ); ?></p>
				</div>
			</div>
		</section>

		<!-- 13. WHAT'S INCLUDED / WHAT'S NOT INCLUDED -->
		<section class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Scope Clarity', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Scope & Boundaries', 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'Clear expectations regarding what is taught and what lies outside the foundational focus of this program.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<div class="scope-grid">
				<!-- What's Included -->
				<div class="scope-card glass-card included-card">
					<h3 class="scope-title">
						<span class="scope-icon check">✓</span>
						<?php esc_html_e( "What's Included", 'twentytwentyfive-child' ); ?>
					</h3>
					<ul class="scope-list">
						<li><?php esc_html_e( '30 hours of live instruction and collaborative coding', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'Founder-led coaching and real-time guidance', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'Small group format strictly capped at 5 participants', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'Hands-on practical exercises for every module', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'Single-agent and multi-agent implementation work', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'n8n workflow automation and API node integration', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'Focused Python-based AI system development', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'Comprehensive Capstone project (AI Research & Decision Assistant)', 'twentytwentyfive-child' ); ?></li>
					</ul>
				</div>

				<!-- What's Not Included -->
				<div class="scope-card glass-card excluded-card">
					<h3 class="scope-title">
						<span class="scope-icon xmark">✕</span>
						<?php esc_html_e( "What's Not Included (Scope Clarity)", 'twentytwentyfive-child' ); ?>
					</h3>
					<p class="scope-note">
						<?php esc_html_e( 'A focused foundation designed to build the right mental models and practical skills before moving into advanced frameworks and production architecture.', 'twentytwentyfive-child' ); ?>
					</p>
					<ul class="scope-list">
						<li><?php esc_html_e( 'LangChain, LangGraph, AutoGen, or CrewAI frameworks', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'Model training, fine-tuning, or reinforcement learning', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'Advanced vector database clustering or complex RAG topologies', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'Kubernetes, MLOps, or complex cloud orchestration', 'twentytwentyfive-child' ); ?></li>
						<li><?php esc_html_e( 'Placement guarantees, job referrals, or accredited certificates', 'twentytwentyfive-child' ); ?></li>
					</ul>
				</div>
			</div>
		</section>

		<!-- 14. PRICING / ENROLLMENT -->
		<section class="program-section">
			<div class="pricing-card glass-card text-center">
				<span class="card-badge"><?php esc_html_e( 'Next Cohort Intake', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Cohort Enrollment', 'twentytwentyfive-child' ); ?></h2>
				<p class="pricing-desc">
					<?php esc_html_e( 'Cohorts are strictly limited to 5 participants to maintain hands-on founder-led coaching, active code reviews, and high-engagement discussions.', 'twentytwentyfive-child' ); ?>
				</p>

				<div class="pricing-callout">
					<div class="callout-pill"><?php esc_html_e( '5 Seats Max Per Cohort', 'twentytwentyfive-child' ); ?></div>
					<div class="callout-pill"><?php esc_html_e( 'Live & Founder-Led', 'twentytwentyfive-child' ); ?></div>
					<div class="callout-pill"><?php esc_html_e( '30 Total Hours (~2 Months)', 'twentytwentyfive-child' ); ?></div>
				</div>

				<div class="pricing-actions">
					<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary btn-lg">
						<?php esc_html_e( 'Enquire About the Next Cohort', 'twentytwentyfive-child' ); ?>
					</a>
				</div>
				<p class="pricing-subtext">
					<?php esc_html_e( 'Schedule a brief conversation to verify prerequisites, discuss scheduling, and reserve your seat in the upcoming cohort.', 'twentytwentyfive-child' ); ?>
				</p>
			</div>
		</section>

		<!-- 15. FAQ PREVIEW -->
		<section id="faqs" class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Common Questions', 'twentytwentyfive-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Frequently Asked Questions', 'twentytwentyfive-child' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'Clear answers to essential questions regarding cohort structure, technical prerequisites, and outcomes.', 'twentytwentyfive-child' ); ?></p>
			</div>

			<div class="faq-accordion-container" id="faq-accordion">
				
				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'Is this live or self-paced?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'This is 100% live coaching. You meet in small-group sessions for instruction, collaborative exercises, and direct live coding rather than watching static pre-recorded videos.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'How many people are in each cohort?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'Each cohort is strictly capped at a maximum of 5 participants to guarantee personalized feedback, live troubleshooting, and meaningful engagement.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'How long is the program and what is the weekly commitment?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'The program spans 30 instructional and building hours delivered across approximately 2 months at roughly 5 hours per week.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'Do I need prior Agentic AI experience?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'No prior experience with AI agents is required. The curriculum begins with fundamental generative AI and prompting mental models before building toward autonomous loops.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'Do I need prior Python experience?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'Basic familiarity with code concepts is helpful, but Module 5 covers the exact subset of Python required for AI systems (variables, dictionaries, JSON, HTTP requests, and basic error handling).', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'Will we build actual AI agents?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'Yes. In Module 6 and Module 7, you construct functional agents with external tool calling, reflection loops, and autonomous execution cycles.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'Will we learn n8n?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'Yes. Module 4 is dedicated to n8n fundamentals: configuring triggers, nodes, webhooks, and integrating AI decision points into operational workflows.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'Will we learn LangChain or LangGraph?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'No. This Foundations program focuses on raw, transparent architectures in Python and n8n so you master how agents genuinely function without framework abstractions.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'Will we build multi-agent systems?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'Yes. Module 8 covers foundational coordinator and specialist delegation patterns, culminating in the AI Research & Decision Assistant Capstone project.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'Is cloud deployment or Kubernetes included?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'No. The curriculum is focused on core agent mechanics, local execution, and API integration rather than advanced cloud infrastructure or MLOps.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'What happens after completing the program?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'You will have working codebases, functional automations, and the independent mental model needed to build production systems or pursue advanced framework specializations.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

				<div class="faq-item glass-card">
					<button class="faq-header" aria-expanded="false">
						<span><?php esc_html_e( 'How do I join the next cohort?', 'twentytwentyfive-child' ); ?></span>
						<span class="faq-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-content" hidden>
						<p><?php esc_html_e( 'Use the consultation form on the Contact page to enquire. Because each cohort is capped at 5 seats, enrollment is confirmed on a rolling basis following a brief alignment discussion.', 'twentytwentyfive-child' ); ?></p>
					</div>
				</div>

			</div>
		</section>

		<!-- 16. FINAL CTA -->
		<section class="cta-section glass-card text-center">
			<h2 class="cta-title"><?php esc_html_e( 'Ready to build your foundations in Agentic AI?', 'twentytwentyfive-child' ); ?></h2>
			<p class="cta-desc">
				<?php esc_html_e( 'Strictly limited to 5 participants per cohort for direct founder-led guidance and hands-on code reviews.', 'twentytwentyfive-child' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary btn-lg">
				<?php esc_html_e( 'Enquire About the Next Cohort', 'twentytwentyfive-child' ); ?>
			</a>
		</section>

	</div>
</main>

<style>
/* ==========================================================================
   PROGRAM PAGE SCOPED STYLES (.program-page)
   Strictly isolated to .program-page to ensure zero global leakage.
   ========================================================================== */

.program-page {
	padding-top: var(--header-clearance, 140px);
	padding-bottom: var(--spacing-xl);
}

/* 1. Program Hero Refined Composition (Phase 1) */
.program-page .program-hero {
	position: relative;
	padding: var(--spacing-sm, 16px) 0 var(--spacing-md, 32px) 0;
	background: var(--color-bg-alt);
	border-bottom: 1px solid var(--color-border-subtle);
}

.program-page .program-hero-container {
	position: relative;
	z-index: 1;
}

/* 1.1 Breadcrumbs Context */
.program-page .program-hero-breadcrumb {
	margin-bottom: var(--spacing-sm);
}

.program-page .program-hero-breadcrumb .breadcrumb-list {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 8px;
	list-style: none;
	padding: 0;
	margin: 0;
	font-size: 0.85rem;
}

.program-page .program-hero-breadcrumb .breadcrumb-item {
	display: inline-flex;
	align-items: center;
	color: var(--color-text-muted);
}

.program-page .program-hero-breadcrumb a {
	color: var(--color-text-secondary);
	text-decoration: none;
	transition: color var(--transition-fast);
}

.program-page .program-hero-breadcrumb a:hover {
	color: var(--color-accent);
}

.program-page .program-hero-breadcrumb a:focus-visible {
	outline: 2px solid var(--color-accent);
	outline-offset: 2px;
	border-radius: 2px;
}

.program-page .program-hero-breadcrumb .breadcrumb-separator {
	color: var(--color-border);
	user-select: none;
	font-size: 0.8rem;
}

.program-page .program-hero-breadcrumb .breadcrumb-current {
	color: var(--color-text);
	font-weight: 500;
}

/* 1.2 Base Unified Hero Banner Card */
.program-page .program-hero-grid {
	position: relative;
	border-radius: var(--radius-lg, 12px);
	overflow: hidden;
	border: 1px solid var(--color-border-subtle);
	background: var(--color-bg-surface);
	box-shadow: var(--shadow-subtle, 0 4px 20px rgba(0, 0, 0, 0.04));
}

/* Theme Switching Mechanism for Program Banner with Full Visual Clarity */
.program-page .program-hero-banner .banner-theme-dark {
	display: block;
	opacity: 1;
}
.program-page .program-hero-banner .banner-theme-light {
	display: none;
	opacity: 1;
}

html.light-theme .program-page .program-hero-banner .banner-theme-dark,
[data-theme="light"] .program-page .program-hero-banner .banner-theme-dark {
	display: none !important;
}
html.light-theme .program-page .program-hero-banner .banner-theme-light,
[data-theme="light"] .program-page .program-hero-banner .banner-theme-light {
	display: block !important;
	opacity: 1;
}

html.dark-theme .program-page .program-hero-banner .banner-theme-dark,
[data-theme="dark"] .program-page .program-hero-banner .banner-theme-dark {
	display: block !important;
	opacity: 1;
}
html.dark-theme .program-page .program-hero-banner .banner-theme-light,
[data-theme="dark"] .program-page .program-hero-banner .banner-theme-light {
	display: none !important;
}

/* Meta chip shared styling */
.program-page .meta-chip {
	display: inline-flex;
	align-items: center;
	gap: 7px;
	padding: 8px 14px;
	font-size: 0.825rem;
	color: var(--color-text);
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-md);
	backdrop-filter: blur(8px);
	-webkit-backdrop-filter: blur(8px);
	transition: border-color var(--transition-fast), transform var(--transition-fast);
}

.program-page .meta-chip:hover {
	border-color: var(--color-accent);
	transform: translateY(-1px);
}

.program-page .meta-chip svg {
	color: var(--color-accent);
	flex-shrink: 0;
}

.program-page .meta-chip strong {
	color: var(--color-accent);
	font-weight: 700;
}

/* Action buttons shared styling */
.program-page .hero-btn-primary,
.program-page .hero-btn-secondary {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	text-decoration: none;
	transition: transform var(--transition-fast), box-shadow var(--transition-fast), background-color var(--transition-fast), border-color var(--transition-fast);
}

.program-page .hero-btn-primary:focus-visible,
.program-page .hero-btn-secondary:focus-visible {
	outline: 2px solid var(--color-accent);
	outline-offset: 3px;
}

/* ==========================================================================
   DESKTOP (min-width: 901px): Dual-Panel Hero Composition
   LEFT: Clean structured content panel (title, desc, meta chips, CTAs)
   RIGHT: Dedicated featured visual panel (people, laptop, AI diagrams)
   ========================================================================== */
@media (min-width: 901px) {
	.program-page .program-hero-grid {
		display: grid;
		grid-template-columns: 1.16fr 0.84fr;
		align-items: stretch;
		min-height: 480px;
	}

	/* Disable background scrim - content and image are separate panels */
	.program-page .program-hero-grid::after {
		display: none;
	}

	/* Text content on LEFT panel */
	.program-page .program-hero-content {
		position: relative;
		z-index: 2;
		padding: 44px 32px 44px 44px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		min-height: 480px;
	}

	.program-page .program-hero-title {
		font-family: var(--font-heading);
		font-size: clamp(2rem, 3.2vw, 2.75rem);
		font-weight: 700;
		line-height: 1.15;
		letter-spacing: -0.025em;
		color: var(--color-text);
		margin: 0 0 14px 0;
		text-wrap: balance;
	}

	.program-page .program-hero-desc {
		font-size: clamp(0.95rem, 1.25vw, 1.075rem);
		line-height: 1.55;
		color: var(--color-text-secondary);
		max-width: 540px;
		margin: 0 0 20px 0;
	}

	.program-page .program-hero-meta {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 8px;
		margin-bottom: 24px;
		max-width: 540px;
	}

	.program-page .meta-chip {
		padding: 6px 12px;
		font-size: 0.775rem;
	}

	/* CTA Buttons strictly SIDE-BY-SIDE horizontally on desktop */
	.program-page .program-hero-actions {
		display: flex;
		flex-direction: row;
		align-items: center;
		gap: 16px;
		flex-wrap: wrap;
		margin-bottom: 0;
	}

	.program-page .hero-btn-primary,
	.program-page .hero-btn-secondary {
		padding: 11px 18px;
		font-size: 0.9rem;
		white-space: nowrap;
		flex-shrink: 0;
	}

	/* Dedicated featured visual panel on RIGHT */
	.program-page .program-hero-banner {
		position: relative;
		inset: auto;
		width: 100%;
		height: 100%;
		min-height: 480px;
		margin: 0;
		border: none;
		border-left: 1px solid var(--color-border-subtle);
		border-radius: 0;
		box-shadow: none;
		background: transparent;
		z-index: 1;
		overflow: hidden;
	}

	.program-page .program-hero-banner img {
		width: 100%;
		height: 100%;
		display: block;
		object-fit: cover;
		object-position: 68% center;
		border-radius: 0;
	}
}

/* Medium Desktop / Small Landscape (1050px to 901px) */
@media (max-width: 1050px) and (min-width: 901px) {
	.program-page .program-hero-grid {
		grid-template-columns: 1.18fr 0.82fr;
		min-height: 460px;
	}
	.program-page .program-hero-content {
		padding: 32px 20px 32px 28px;
		min-height: 460px;
	}
	.program-page .program-hero-title {
		font-size: 2rem;
	}
	.program-page .program-hero-desc {
		font-size: 0.95rem;
		max-width: 480px;
		margin-bottom: 16px;
	}
	.program-page .program-hero-meta {
		max-width: 480px;
		gap: 6px;
		margin-bottom: 20px;
	}
	/* CTA Buttons strictly SIDE-BY-SIDE horizontally on 1050px to 901px */
	.program-page .program-hero-actions {
		display: flex;
		flex-direction: row;
		align-items: center;
		gap: 14px;
		flex-wrap: wrap;
	}
	.program-page .hero-btn-primary,
	.program-page .hero-btn-secondary {
		width: auto;
		max-width: none;
		white-space: nowrap;
		flex-shrink: 0;
	}
	.program-page .program-hero-banner {
		min-height: 460px;
	}
	.program-page .program-hero-banner img {
		object-position: 68% center;
	}
}

/* ==========================================================================
   TABLET & MOBILE (max-width: 900px): Natural stacked composition
   Content sits above the banner image without overlapping people or laptop
   ========================================================================== */
@media (max-width: 900px) {
	.program-page .program-hero-grid {
		display: flex;
		flex-direction: column;
		gap: 0;
		min-height: auto;
	}

	.program-page .program-hero-grid::after {
		display: none;
	}

	.program-page .program-hero-content {
		position: relative;
		max-width: 100%;
		min-height: auto;
		padding: 32px 28px 24px 28px;
		z-index: 2;
	}

	.program-page .program-hero-title {
		font-family: var(--font-heading);
		font-size: clamp(1.85rem, 4vw, 2.35rem);
		font-weight: 700;
		line-height: 1.2;
		letter-spacing: -0.025em;
		color: var(--color-text);
		margin: 0 0 10px 0;
		text-wrap: balance;
	}

	.program-page .program-hero-desc {
		font-size: 1.05rem;
		line-height: 1.55;
		color: var(--color-text-secondary);
		max-width: 680px;
		margin: 0 0 16px 0;
	}

	.program-page .program-hero-meta {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 8px;
		margin-bottom: 22px;
	}

	.program-page .meta-chip {
		padding: 6px 12px;
		font-size: 0.8rem;
	}

	.program-page .program-hero-actions {
		display: flex;
		align-items: center;
		gap: 16px;
		flex-wrap: wrap;
		margin-bottom: 0;
	}

	.program-page .program-hero-banner {
		position: relative;
		inset: auto;
		width: 100%;
		height: auto;
		margin: 0;
		border: none;
		border-top: 1px solid var(--color-border-subtle);
		border-radius: 0;
		box-shadow: none;
		background: transparent;
		overflow: hidden;
	}

	.program-page .program-hero-banner img {
		width: 100%;
		height: auto;
		aspect-ratio: 2 / 1;
		display: block;
		object-fit: cover;
		object-position: center;
		border-radius: 0;
	}
}

/* Mobile View (640px and below) */
@media (max-width: 640px) {
	.program-page .program-hero {
		padding: var(--spacing-xs, 8px) 0 var(--spacing-md, 32px) 0;
		text-align: center;
	}

	.program-page .program-hero-breadcrumb {
		margin-bottom: var(--spacing-xs);
	}

	.program-page .program-hero-breadcrumb .breadcrumb-list {
		justify-content: center;
		font-size: 0.775rem;
		gap: 6px;
	}

	.program-page .program-hero-content {
		display: flex;
		flex-direction: column;
		align-items: center;
		text-align: center;
		padding: 24px 18px 20px 18px;
	}

	.program-page .program-hero-title {
		font-size: clamp(1.75rem, 7vw, 2.25rem);
		line-height: 1.2;
		text-align: center;
		margin-bottom: 8px;
	}

	.program-page .program-hero-desc {
		font-size: 0.95rem;
		line-height: 1.55;
		text-align: center;
		margin-left: auto;
		margin-right: auto;
		margin-bottom: 14px;
	}

	.program-page .program-hero-meta {
		justify-content: center;
		gap: 6px;
		margin-bottom: 20px;
		width: 100%;
	}

	.program-page .meta-chip {
		font-size: 0.775rem;
		padding: 6px 10px;
	}

	.program-page .program-hero-actions {
		display: flex;
		flex-direction: column;
		align-items: center;
		width: 100%;
		gap: 14px;
	}

	.program-page .hero-btn-primary,
	.program-page .hero-btn-secondary {
		width: 100%;
		max-width: 340px;
		text-align: center;
		justify-content: center;
	}

	.program-page .program-hero-grid {
		border-radius: var(--radius-md, 8px);
	}
}

/* Tiny screens (380px and below) */
@media (max-width: 380px) {
	.program-page .program-hero-title {
		font-size: 1.7rem;
	}

	.program-page .meta-chip {
		width: 100%;
		justify-content: center;
	}
}

/* 2. Sticky Anchor Navigation */
.program-page .program-anchor-nav {
	position: sticky;
	top: var(--site-header-height, 81px);
	z-index: 90;
	background: var(--color-header-bg, rgba(5, 5, 5, 0.9));
	backdrop-filter: blur(12px);
	-webkit-backdrop-filter: blur(12px);
	border-bottom: 1px solid var(--color-border-subtle);
	padding: 10px 0;
}

.program-page .anchor-nav-list {
	display: flex;
	gap: var(--spacing-md);
	list-style: none;
	margin: 0;
	padding: 0;
	overflow-x: auto;
	white-space: nowrap;
	-webkit-overflow-scrolling: touch;
	scrollbar-width: none;
}

.program-page .anchor-nav-list::-webkit-scrollbar {
	display: none;
}

.program-page .anchor-link {
	font-size: 0.85rem;
	font-weight: 500;
	color: var(--color-text-muted);
	padding: 6px 0;
	text-decoration: none;
	transition: color var(--transition-fast), border-color var(--transition-fast);
	border-bottom: 2px solid transparent;
}

.program-page .anchor-link:hover,
.program-page .anchor-link.active {
	color: var(--color-accent) !important;
	border-bottom-color: var(--color-accent);
}

/* 3. Section Structure & Vertical Rhythm (Phase 4 Spacing Refinement) */
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

.program-page .section-heading-wrapper .section-title,
.program-page .pricing-card .section-title {
	font-size: clamp(1.45rem, 2.5vw, 2.15rem);
	font-family: var(--font-heading);
	font-weight: 700;
	color: var(--color-text);
	margin-bottom: 6px;
	line-height: 1.2;
	letter-spacing: -0.02em;
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
	font-size: 1.2rem;
	font-family: var(--font-heading);
	font-weight: 600;
	line-height: 1.35;
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
	width: 6px;
	height: 6px;
	border-radius: 50%;
	background-color: var(--color-accent);
	margin-top: 8px;
	flex-shrink: 0;
	display: inline-block;
}

.program-page .highlights-list strong {
	font-size: 0.95rem;
	font-weight: 600;
	color: var(--color-text);
	line-height: 1.35;
	display: block;
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

.program-page .phil-col h3,
.program-page .phil-col h4 {
	font-size: 1.12rem;
	font-family: var(--font-heading);
	font-weight: 600;
	line-height: 1.35;
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

.program-page .module-summary-card h3,
.program-page .module-summary-card h4 {
	font-size: 1.08rem;
	font-family: var(--font-heading);
	font-weight: 600;
	line-height: 1.3;
	margin-bottom: 4px;
	color: var(--color-text);
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

/* 7. Curriculum Layout: Full-Width Module Content Area & Primary Accordion */
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

.program-page .build-card h3,
.program-page .build-card h4 {
	font-size: 1.08rem;
	font-family: var(--font-heading);
	font-weight: 600;
	line-height: 1.35;
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
	font-style: normal;
	font-weight: 500;
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

.program-page .career-card h3,
.program-page .career-card h4 {
	font-size: 1.08rem;
	font-family: var(--font-heading);
	font-weight: 600;
	line-height: 1.35;
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

.program-page .coaching-step h3,
.program-page .coaching-step h4 {
	font-size: 1.08rem;
	font-family: var(--font-heading);
	font-weight: 600;
	line-height: 1.35;
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
	content: '';
	position: absolute;
	left: 4px;
	top: 8px;
	width: 5px;
	height: 5px;
	border-radius: 50%;
	background-color: var(--color-accent);
}

/* 14. Pricing / Enrollment - Premium Spacing Refinement */
.program-page .pricing-card {
	padding: clamp(2.25rem, 5vw, 3.25rem) clamp(1.5rem, 4vw, 2.5rem);
	border-radius: var(--radius-lg);
	border-color: var(--color-glass-border);
}

.program-page .pricing-card .card-badge {
	display: inline-block;
	margin-bottom: 12px;
}

.program-page .pricing-card .section-title {
	margin-bottom: 14px;
}

.program-page .pricing-desc {
	font-size: 1.05rem;
	color: var(--color-text-secondary);
	max-width: 650px;
	margin: 0 auto 22px auto;
	line-height: 1.55;
}

.program-page .pricing-callout {
	display: flex;
	flex-wrap: wrap;
	justify-content: center;
	gap: 8px;
	margin-bottom: 30px;
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
	margin-bottom: 18px;
}

.program-page .pricing-subtext {
	font-size: 0.82rem;
	color: var(--color-text-muted);
	max-width: 500px;
	margin: 0 auto;
	line-height: 1.5;
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
	font-size: clamp(1.45rem, 2.5vw, 2.15rem);
	font-family: var(--font-heading);
	font-weight: 700;
	margin-bottom: 8px;
	color: var(--color-text);
	line-height: 1.2;
	letter-spacing: -0.02em;
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
		gap: 8px;
	}

	.program-page .curriculum-guide-module-block {
		padding: 0;
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
	}


	.program-page .pricing-card,
	.program-page .cta-section {
		padding: 2rem 1.15rem;
	}

	.program-page .pricing-card .card-badge {
		margin-bottom: 10px;
	}

	.program-page .pricing-card .section-title {
		margin-bottom: 12px;
	}

	.program-page .pricing-desc {
		margin-bottom: 18px;
	}

	.program-page .pricing-callout {
		margin-bottom: 24px;
	}

	.program-page .pricing-actions {
		margin-bottom: 16px;
	}
}

@media (max-width: 480px) {
	.program-page .curriculum-guide-module-block .module-accordion-header {
		padding: 10px 12px;
		gap: 8px;
	}
	.program-page .curriculum-guide-module-block .module-header-main {
		gap: 8px;
		align-items: flex-start;
	}
	.program-page .curriculum-guide-module-block .module-number-pill {
		margin-top: 2px;
		font-size: 0.7rem;
		padding: 2px 6px;
	}
	.program-page .curriculum-guide-module-block .module-title-text {
		font-size: 0.92rem;
		line-height: 1.3;
	}
	.program-page .curriculum-guide-module-block .module-header-meta {
		gap: 8px;
		align-items: flex-start;
		margin-top: 2px;
	}
	.program-page .curriculum-guide-module-block .module-duration-badge {
		font-size: 0.7rem;
		padding: 2px 7px;
	}
	.program-page .curriculum-guide-module-block .module-accordion-body {
		padding: 12px 12px 16px 12px;
	}
}
</style>

<script>
/**
 * Scoped Interactions for Agentic AI Foundations Program Page
 * Handles:
 * 1. Curriculum interactive accordion (single-open, smooth animation)
 * 2. FAQ preview accordion
 * 3. Sticky anchor nav scroll-spy and smooth scrolling
 */
document.addEventListener('DOMContentLoaded', function() {

	// 1. Module Primary Accordion (Single-Open, Smooth Height/Opacity Transition)
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
	});

	// 2. FAQ Accordion Functionality
	const faqItems = document.querySelectorAll('.faq-item');
	faqItems.forEach(item => {
		const header = item.querySelector('.faq-header');
		const content = item.querySelector('.faq-content');

		header.addEventListener('click', function(e) {
			e.preventDefault();
			const isOpen = item.classList.contains('active');

			// Close all other FAQ accordion items
			faqItems.forEach(otherItem => {
				otherItem.classList.remove('active');
				const otherHeader = otherItem.querySelector('.faq-header');
				const otherContent = otherItem.querySelector('.faq-content');
				if (otherHeader) otherHeader.setAttribute('aria-expanded', 'false');
				if (otherContent) {
					otherContent.style.maxHeight = null;
					otherContent.setAttribute('hidden', '');
				}
			});

			if (!isOpen) {
				item.classList.add('active');
				header.setAttribute('aria-expanded', 'true');
				content.removeAttribute('hidden');
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

	// 2. Anchor Navigation Smooth Scroll & Scroll-Spy
	const siteHeader = document.querySelector('.site-header');
	const anchorNav = document.querySelector('.program-anchor-nav');

	function updateHeaderHeightVar() {
		if (siteHeader) {
			const h = Math.round(siteHeader.getBoundingClientRect().height);
			document.documentElement.style.setProperty('--site-header-height', h + 'px');
		}
	}
	updateHeaderHeightVar();
	window.addEventListener('resize', updateHeaderHeightVar, { passive: true });

	const anchorLinks = document.querySelectorAll('.anchor-nav-list .anchor-link');
	const sections = [];

	anchorLinks.forEach(link => {
		const targetId = link.getAttribute('href');
		if (targetId && targetId.startsWith('#')) {
			const section = document.querySelector(targetId);
			if (section) {
				sections.push({ id: targetId, link: link, section: section });
			}
		}
	});

	// Handle all in-page anchor links including hero CTA buttons and anchor-nav links
	const inPageAnchors = document.querySelectorAll('a[href^="#"]:not([href="#"])');
	inPageAnchors.forEach(link => {
		link.addEventListener('click', function(e) {
			const targetId = link.getAttribute('href');
			if (targetId && targetId.length > 1) {
				const targetElem = document.querySelector(targetId);
				if (targetElem) {
					e.preventDefault();
					const headerH = siteHeader ? siteHeader.getBoundingClientRect().height : 81;
					const navH = anchorNav ? anchorNav.getBoundingClientRect().height : 46;
					const totalOffset = headerH + navH + 20; // 20px comfortable clearance below sticky nav
					const targetY = targetElem.getBoundingClientRect().top + window.pageYOffset - totalOffset;

					window.scrollTo({
						top: Math.max(0, targetY),
						behavior: 'smooth'
					});

					if (history.pushState) {
						history.pushState(null, null, targetId);
					}
				}
			}
		});
	});

	// Scroll-Spy for active anchor link
	if (sections.length > 0) {
		const onScroll = function() {
			const headerH = siteHeader ? siteHeader.getBoundingClientRect().height : 81;
			const navH = anchorNav ? anchorNav.getBoundingClientRect().height : 46;
			const scrollPos = window.scrollY + headerH + navH + 40;
			let currentSection = sections[0];

			for (let i = 0; i < sections.length; i++) {
				if (sections[i].section.offsetTop <= scrollPos) {
					currentSection = sections[i];
				}
			}

			anchorLinks.forEach(l => l.classList.remove('active'));
			if (currentSection && currentSection.link) {
				currentSection.link.classList.add('active');
			}
		};

		window.addEventListener('scroll', onScroll, { passive: true });
	}

});
</script>

<?php get_footer(); ?>
