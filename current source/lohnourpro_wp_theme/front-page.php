<?php
/**
 * The front page template file
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main">

	<!-- 01 - HERO SECTION (Original Composition) -->
	<section class="hero-section">
		<div class="container">
			<div class="hero-content">
				<h1 class="hero-title">Learn AI from people <br><span class="highlight">who actually do it.</span></h1>
				<p class="hero-description">
					Practical AI learning for professionals, students, faculty and organizations.
				</p>
				<div class="hero-actions">
					<a href="<?php echo esc_url( home_url( '/programs/' ) ); ?>" class="btn btn-primary btn-lg">Explore Programs</a>
					<a href="<?php echo esc_url( home_url( '/coaching/' ) ); ?>" class="btn btn-outline btn-lg">Explore Coaching</a>
				</div>
			</div>
			
			<div class="hero-visual">
				<!-- Sophisticated Agentic AI Orchestration Graphic -->
				<div class="agent-orchestration-graphic">
					<div class="agent-node central-agent">
						<span class="node-label">AI Agent</span>
						<span class="node-status">Active</span>
					</div>
					
					<div class="agent-path path-1"></div>
					<div class="agent-path path-2"></div>
					<div class="agent-path path-3"></div>
					
					<div class="agent-node child-node node-1">
						<span class="node-label">Data Analysis</span>
						<div class="progress-bar"><div class="progress"></div></div>
					</div>
					<div class="agent-node child-node node-2">
						<span class="node-label">Workflow Automation</span>
						<div class="progress-bar"><div class="progress"></div></div>
					</div>
					<div class="agent-node child-node node-3">
						<span class="node-label">Insights Generation</span>
						<div class="progress-bar"><div class="progress"></div></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- 02 - TRUSTED BY / SOCIAL PROOF SECTION (Original Composition) -->
	<section class="trusted-by-section">
		<div class="container">
			<p class="section-label text-center">Trusted by</p>
			<div class="logo-grid">
				<div class="logo-track">
					<?php
					$logo_dir = get_stylesheet_directory() . '/assets/images/logos';
					$logos = array();
					
					if ( is_dir( $logo_dir ) ) {
						$logo_files = glob( $logo_dir . '/*.{png,jpg,jpeg,svg,webp}', GLOB_BRACE );
						if ( ! empty( $logo_files ) ) {
							foreach ( $logo_files as $file ) {
								$filename = basename( $file );
								$logos[] = array(
									'type' => 'img',
									'url'  => get_stylesheet_directory_uri() . '/assets/images/logos/' . $filename,
									'alt'  => pathinfo( $filename, PATHINFO_FILENAME )
								);
							}
						}
					}
					
					// Repeat logos twice to ensure seamless scrolling
					for($i=0; $i<2; $i++) {
						foreach ( $logos as $logo ) {
							echo '<div class="logo-item">';
							if ( $logo['type'] === 'img' ) {
								echo '<img src="' . esc_url( $logo['url'] ) . '" alt="' . esc_attr( $logo['alt'] ) . '" style="height: 32px; width: auto; max-width: 140px; display: block; object-fit: contain;">';
							} else {
								echo '<svg width="140" height="40" viewBox="0 0 140 40" fill="none" xmlns="http://www.w3.org/2000/svg">' . $logo['svg'] . '</svg>';
							}
							echo '</div>';
						}
					}
					?>
				</div>
			</div>
		</div>
	</section>

	<!-- 03 - WHO WE SERVE SECTION (New IA, preserving design tokens) -->
	<section class="who-we-serve-section" style="padding: var(--spacing-xl) 0; background: var(--color-bg-surface);">
		<div class="container">
			<div class="section-header text-center" style="margin-bottom: var(--spacing-lg);">
				<h2 class="section-title">Who We Serve</h2>
				<p class="section-subtitle">Tailored AI upskilling for different learning needs.</p>
			</div>
			
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-md);">
				<a href="<?php echo esc_url( home_url( '/programs/pathway/professional-ai-upskilling/' ) ); ?>" class="lohnour-card card-service" style="display: block; transition: transform 0.2s;">
					<div class="card-content" style="padding: var(--spacing-md);">
						<div style="margin-bottom: var(--spacing-sm); color: var(--color-accent);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="15" x2="23" y2="15"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="15" x2="4" y2="15"></line></svg>
						</div>
						<h3 class="card-title">Professionals</h3>
						<p class="card-excerpt">Practical AI skills for modern work.</p>
						<div class="card-footer" style="margin-top: var(--spacing-md);">
							<span class="link-arrow">Explore</span>
						</div>
					</div>
				</a>

				<a href="<?php echo esc_url( home_url( '/programs/pathway/student-ai-programs/' ) ); ?>" class="lohnour-card card-service" style="display: block; transition: transform 0.2s;">
					<div class="card-content" style="padding: var(--spacing-md);">
						<div style="margin-bottom: var(--spacing-sm); color: var(--color-accent);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
						</div>
						<h3 class="card-title">Students</h3>
						<p class="card-excerpt">Build practical AI capabilities for your career.</p>
						<div class="card-footer" style="margin-top: var(--spacing-md);">
							<span class="link-arrow">Explore</span>
						</div>
					</div>
				</a>

				<a href="<?php echo esc_url( home_url( '/programs/pathway/faculty-development/' ) ); ?>" class="lohnour-card card-service" style="display: block; transition: transform 0.2s;">
					<div class="card-content" style="padding: var(--spacing-md);">
						<div style="margin-bottom: var(--spacing-sm); color: var(--color-accent);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
						</div>
						<h3 class="card-title">Faculty</h3>
						<p class="card-excerpt">Apply AI effectively in teaching and education.</p>
						<div class="card-footer" style="margin-top: var(--spacing-md);">
							<span class="link-arrow">Explore</span>
						</div>
					</div>
				</a>

				<a href="<?php echo esc_url( home_url( '/services/corporate/' ) ); ?>" class="lohnour-card card-service" style="display: block; transition: transform 0.2s;">
					<div class="card-content" style="padding: var(--spacing-md);">
						<div style="margin-bottom: var(--spacing-sm); color: var(--color-accent);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
						</div>
						<h3 class="card-title">Organizations</h3>
						<p class="card-excerpt">Build AI capability across your teams.</p>
						<div class="card-footer" style="margin-top: var(--spacing-md);">
							<span class="link-arrow">Explore</span>
						</div>
					</div>
				</a>
			</div>
		</div>
	</section>

	<!-- 04 - HOW YOU LEARN SECTION (New IA, preserving design tokens) -->
	<section class="how-you-learn-section" style="padding: var(--spacing-xl) 0; background: var(--color-bg);">
		<div class="container">
			<div class="section-header text-center" style="margin-bottom: var(--spacing-lg);">
				<h2 class="section-title">How You Learn</h2>
				<p class="section-subtitle">Multiple delivery formats for our AI programs.</p>
			</div>
			
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--spacing-md);">
				<div class="lohnour-card" style="padding: var(--spacing-md);">
					<div style="margin-bottom: var(--spacing-sm); color: var(--color-text-muted);">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
					</div>
					<h3 class="card-title">Structured Learning</h3>
					<p class="card-excerpt" style="flex-grow: 1;">Structured practical learning experiences for teams and cohorts.</p>
					<div style="margin-top: var(--spacing-md);">
						<a href="<?php echo esc_url( home_url( '/programs/' ) ); ?>" class="link-arrow">Explore Programs</a>
					</div>
				</div>

				<div class="lohnour-card" style="padding: var(--spacing-md); display: flex; flex-direction: column;">
					<div style="margin-bottom: var(--spacing-sm); color: var(--color-text-muted);">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
					</div>
					<h3 class="card-title">Individual Coaching</h3>
					<p class="card-excerpt" style="flex-grow: 1;">Personalized guidance from an expert tailored to your goals.</p>
					<div style="margin-top: var(--spacing-md);">
						<a href="<?php echo esc_url( home_url( '/coaching/individual/' ) ); ?>" class="link-arrow">Explore Individual Coaching</a>
					</div>
				</div>

				<div class="lohnour-card" style="padding: var(--spacing-md); display: flex; flex-direction: column;">
					<div style="margin-bottom: var(--spacing-sm); color: var(--color-text-muted);">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
					</div>
					<h3 class="card-title">Group Coaching</h3>
					<p class="card-excerpt" style="flex-grow: 1;">Learn alongside a small group guided by an expert.</p>
					<div style="margin-top: var(--spacing-md);">
						<a href="<?php echo esc_url( home_url( '/coaching/group/' ) ); ?>" class="link-arrow">Explore Group Coaching</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- 05 - HOW IT WORKS / WHY US SECTION (Original Composition) -->
	<section class="why-us-section py-xl">
		<div class="container">
			<div class="split-layout">
				<div class="split-content">
					<h2 class="section-title">Why Lohnour Pro?</h2>
					<p class="section-subtitle">We don't teach theory. We teach implementation.</p>
					
					<ul class="feature-list">
						<li>
							<div class="feature-icon">✓</div>
							<div class="feature-text">
								<h4>Practical, Not Theoretical</h4>
								<p>Focus on tools and workflows you can use immediately.</p>
							</div>
						</li>
						<li>
							<div class="feature-icon">✓</div>
							<div class="feature-text">
								<h4>Customized for You or Your Team</h4>
								<p>Training built around your specific industry, role, and day-to-day workflows.</p>
							</div>
						</li>
						<li>
							<div class="feature-icon">✓</div>
							<div class="feature-text">
								<h4>Hands-On Learning</h4>
								<p>Interactive sessions where you build alongside the instructor.</p>
							</div>
						</li>
					</ul>
				</div>
				<div class="split-visual" style="display: flex; align-items: center; justify-content: center; height: 100%;">
					<!-- Elaborated Workflow Diagram -->
					<div class="workflow-diagram glass-card" style="width: 100%; max-width: 500px; padding: var(--spacing-lg);">
						<div class="diagram-grid">
							<!-- Inputs Column -->
							<div class="diagram-column">
								<span class="column-header">Inputs</span>
								<div class="node-box">Unstructured Docs</div>
								<div class="node-box">User Context</div>
								<div class="node-box">API Data</div>
							</div>
							
							<!-- Connector -->
							<div class="diagram-connector">
								<svg width="24" height="60" viewBox="0 0 24 60" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.3;">
									<path d="M0 10 H20 V30 H0 M20 30 H0 M0 50 H20" stroke="var(--color-text-muted)" stroke-width="1.5" stroke-dasharray="3 3"/>
								</svg>
								<span class="arrow-symbol">→</span>
							</div>
							
							<!-- Agent Core -->
							<div class="diagram-column">
								<span class="column-header">Orchestrator</span>
								<div class="node-box active">
									<span class="node-title">Custom Workflows</span>
									<span class="node-subtitle">Company Knowledge</span>
								</div>
								<div class="tools-tray">
									<span class="tool-tag">Web Search</span>
									<span class="tool-tag">Internal Docs</span>
								</div>
							</div>
							
							<!-- Connector -->
							<div class="diagram-connector">
								<span class="arrow-symbol">→</span>
							</div>
							
							<!-- Outcomes -->
							<div class="diagram-column">
								<span class="column-header">Outcomes</span>
								<div class="node-box">Saved Time</div>
								<div class="node-box">Clean Report</div>
								<div class="node-box">Auto Action</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- 06 - FEATURED PROGRAMS SECTION (New IA, dynamic) -->
	<section class="featured-programs-section" style="padding: var(--spacing-xl) 0; background: var(--color-bg);">
		<div class="container">
			<div class="section-header text-center" style="margin-bottom: var(--spacing-lg);">
				<h2 class="section-title">Featured Programs</h2>
				<p class="section-subtitle">Real-world AI upskilling programs available now.</p>
			</div>
			
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: var(--spacing-md);">
				<?php
				$programs_query = new WP_Query( array(
					'post_type'      => 'lohnour_service',
					'posts_per_page' => 3,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				) );

				if ( $programs_query->have_posts() ) :
					while ( $programs_query->have_posts() ) : $programs_query->the_post();
						?>
						<a href="<?php the_permalink(); ?>" class="lohnour-card card-program" style="display: flex; flex-direction: column; overflow: hidden; transition: transform 0.2s;">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="card-image" style="aspect-ratio: 16/9; width: 100%; overflow: hidden; background: var(--color-bg-alt);">
									<?php the_post_thumbnail( 'medium', array( 'style' => 'width: 100%; height: 100%; object-fit: cover; display: block;' ) ); ?>
								</div>
							<?php else: ?>
								<div class="card-image" style="aspect-ratio: 16/9; width: 100%; background: var(--color-bg-alt); display: flex; align-items: center; justify-content: center;">
									<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-border)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
								</div>
							<?php endif; ?>
							
							<div class="card-content" style="padding: var(--spacing-md); flex-grow: 1; display: flex; flex-direction: column;">
								<div style="margin-bottom: var(--spacing-xs);">
									<span class="badge">Program</span>
								</div>
								
								<h3 class="card-title" style="margin-bottom: var(--spacing-xs);"><?php the_title(); ?></h3>
								<?php 
								$card_excerpt = get_post_meta( get_the_ID(), '_lohnour_card_excerpt', true );
								if ( empty( $card_excerpt ) ) {
									$card_excerpt = wp_trim_words( get_the_excerpt(), 15, '...' );
								}
								?>
								<p class="card-excerpt" style="flex-grow: 1; color: var(--color-text-secondary);"><?php echo esc_html( $card_excerpt ); ?></p>
								
								<div class="card-footer" style="margin-top: var(--spacing-md);">
									<span class="link-arrow">View Program</span>
								</div>
							</div>
						</a>
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					echo '<p>No programs found.</p>';
				endif;
				?>
			</div>
			
			<div class="text-center" style="margin-top: var(--spacing-lg);">
				<a href="<?php echo esc_url( home_url( '/programs/' ) ); ?>" class="btn btn-outline">Explore All Programs</a>
			</div>
		</div>
	</section>

	<!-- 07 - LEARNING EXPERIENCE SECTION (Original Composition) -->
	<section class="process-section py-xl bg-alt">
		<div class="container">
			<div class="section-header text-center">
				<h2 class="section-title">The Learning Experience</h2>
				<p class="section-subtitle">A structured journey to ensure maximum ROI.</p>
			</div>
			
			<div class="process-grid">
				<div class="timeline-line-horizontal"></div>
				<div class="process-card glass-card hover-lift">
					<div class="process-marker">01</div>
					<h4>Understand<br>Goals</h4>
					<p>We analyze your or your team's current capabilities and specific learning objectives to establish a baseline.</p>
				</div>
				<div class="process-card glass-card hover-lift">
					<div class="process-marker">02</div>
					<h4>Customize<br>Curriculum</h4>
					<p>We design a highly practical, project-based learning agenda tailored specifically to your daily workflows.</p>
				</div>
				<div class="process-card glass-card hover-lift">
					<div class="process-marker">03</div>
					<h4>Deliver<br>Training</h4>
					<p>Intensive, practical, hands-on sessions focused on building custom workflows you can implement the very next day.</p>
				</div>
				<div class="process-card glass-card hover-lift">
					<div class="process-marker">04</div>
					<h4>Post-Training<br>Support</h4>
					<p>Continued resources, follow-up guidance, and practical playbooks to ensure lasting, independent adoption.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- 08 - TESTIMONIALS & CASE STUDIES SECTION (Original Composition) -->
	<section class="proof-section py-xl bg-alt">
		<div class="container">
			<div class="section-header text-center">
				<h2 class="section-title">Proven Impact</h2>
				<p class="section-subtitle">Real results from our training programs.</p>
			</div>
			
			<div class="testimonial-wrapper">
				<button class="testimonial-arrow prev-arrow" id="testiPrev" aria-label="Previous testimonial">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
				</button>
				
				<div class="testimonial-grid" id="testimonialGrid">
					<div class="testimonial-card glass-card">
						<p class="quote">"This workshop completely changed how I look at artificial intelligence. Instead of just learning how to write better prompts, I learned how to build and deploy actual AI agents that automate complex workflows from start to finish."</p>
						<div class="author">
							<div class="author-info">
								<strong>Vedik Bohara</strong>
								<span class="role">B. Tech. CSE Undergrad</span>
								<span class="org">Sri Aurobindo Institute of Technology</span>
							</div>
						</div>
					</div>
					<div class="testimonial-card glass-card">
						<p class="quote">"You didn't just take a session, you created a vibe here. I just wanted to give you a reward title: 'Engagement guru' Samarpit."</p>
						<div class="author">
							<div class="author-info">
								<strong>Renuka Pravin Jorigal</strong>
								<span class="role">Senior Developer</span>
								<span class="org">Wipro</span>
							</div>
						</div>
					</div>
					<div class="testimonial-card glass-card">
						<p class="quote">"It is such an interactive session, the energy is so insane. Thank you Samarpit Shrivastava Sir for your valuable session."</p>
						<div class="author">
							<div class="author-info">
								<strong>Rishi Parashar</strong>
								<span class="role">Student Ambassador - Microsoft</span>
								<span class="org">Gyan Ganga College of Technology, Jabalpur</span>
							</div>
						</div>
					</div>
					<div class="testimonial-card glass-card">
						<p class="quote">"The workshop was extremely practical and engaging. The hands-on sessions made complex Agentic AI concepts easy to understand. I now feel confident building AI applications with LLM APIs, tool calling, conversation memory, and multi-agent orchestration."</p>
						<div class="author">
							<div class="author-info">
								<strong>Aditya Dubey</strong>
								<span class="role">B. Tech. CSE Undergrad</span>
								<span class="org">Sri Aurobindo Institute of Technology</span>
							</div>
						</div>
					</div>
					<div class="testimonial-card glass-card">
						<p class="quote">"It was a really wonderful session, very interactive and engaging. I learned so many new things and truly enjoyed every moment. Thank you for making it such a great experience."</p>
						<div class="author">
							<div class="author-info">
								<strong>Tanushree Bandopadhyay</strong>
								<span class="role">QA Engineer</span>
								<span class="org">Wipro</span>
							</div>
						</div>
					</div>
					<div class="testimonial-card glass-card">
						<p class="quote">"Thank you so much sir for such an insightful and interactive session. The learnings that you have delivered are very valuable."</p>
						<div class="author">
							<div class="author-info">
								<strong>Garvi Jain</strong>
								<span class="role">Campus Ambassador - PhysicsWallah</span>
								<span class="org">Mody University of Science and Technology</span>
							</div>
						</div>
					</div>
				</div>

				<button class="testimonial-arrow next-arrow" id="testiNext" aria-label="Next testimonial">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
				</button>

				<div class="testimonial-controls">
					<div class="testimonial-dots" id="testiDots">
						<!-- Dots generated by JS -->
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- 09 - MEET THE FOUNDER SECTION (Original Composition) -->
	<section class="founder-section py-xl">
		<div class="container">
			<div class="split-layout">
				<div class="split-visual">
					<div class="founder-image-wrapper">
						<?php 
						$image_url = '';
						$image_extensions = array( 'webp', 'jpg', 'jpeg', 'png' );
						foreach ( $image_extensions as $ext ) {
							if ( file_exists( get_stylesheet_directory() . '/assets/images/founder.' . $ext ) ) {
								$image_url = get_stylesheet_directory_uri() . '/assets/images/founder.' . $ext;
								break;
							}
						}
						if ( $image_url ) : ?>
							<img src="<?php echo esc_url( $image_url ); ?>" alt="Samarpit - Founder of Lohnour Pro" style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md);">
						<?php else : ?>
							<div class="founder-image-placeholder">
								<span>Founder Portrait</span>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="split-content">
					<div class="hero-badge">Lead Instructor</div>
					<h2 class="section-title">Samarpit Shrivastava</h2>
					<p class="section-subtitle" style="margin-bottom: var(--spacing-md);">Corporate AI Trainer & Founder of Lohnour Pro</p>
					
					<div class="founder-bio">
						<p>I help organizations and professionals bridge the gap between AI capabilities and practical, daily business workflows. Through intensive, hands-on training, I ensure your team doesn't just understand AI theoretically, but actually implements it to save hours and drive revenue.</p>
					</div>

					<div class="founder-socials" style="display: flex; gap: 12px; align-items: center; margin: var(--spacing-md) 0;">
						<a href="https://www.linkedin.com/in/samarpit-shrivastava/" target="_blank" rel="noopener" class="founder-social-link" aria-label="LinkedIn">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
						</a>
						<a href="https://x.com/manoflohnour" target="_blank" rel="noopener" class="founder-social-link" aria-label="Twitter">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
						</a>
						<a href="https://www.instagram.com/manoflohnour/" target="_blank" rel="noopener" class="founder-social-link" aria-label="Instagram">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
						</a>
					</div>

					<div class="founder-stats" style="display: flex; gap: var(--spacing-lg); margin: var(--spacing-md) 0 var(--spacing-sm) 0;">
						<div class="stat-item">
							<span class="stat-number" style="font-size: 2rem; font-family: var(--font-heading); font-weight: 600; color: var(--color-accent);">1000+</span>
							<span class="stat-label" style="display: block; font-size: 0.75rem; color: var(--color-text-muted); text-transform: uppercase;">Professionals Trained</span>
						</div>
						<div class="stat-item">
							<span class="stat-number" style="font-size: 2rem; font-family: var(--font-heading); font-weight: 600; color: var(--color-accent);">700+</span>
							<span class="stat-label" style="display: block; font-size: 0.75rem; color: var(--color-text-muted); text-transform: uppercase;">Hours of Training Delivered</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- 10 - CORPORATE TRAINING SECTION (New IA, preserving design tokens) -->
	<section class="corporate-training-section" style="padding: var(--spacing-xl) 0; background: var(--color-bg);">
		<div class="container text-center">
			<div style="max-width: 800px; margin: 0 auto; padding: var(--spacing-xl); border: 1px solid var(--color-border-subtle); border-radius: var(--radius-lg); background: var(--color-bg-surface);">
				<h2 class="section-title">Corporate Training</h2>
				<p style="font-size: 1.25rem; margin-bottom: var(--spacing-lg);">Customized AI training for organizations and teams.</p>
				<a href="<?php echo esc_url( home_url( '/services/corporate/' ) ); ?>" class="btn btn-primary">Explore Corporate Training</a>
			</div>
		</div>
	</section>

	<!-- 11 - LATEST GUIDES SECTION (New IA, dynamic) -->
	<section class="latest-guides-section" style="padding: var(--spacing-xl) 0; background: var(--color-bg-surface);">
		<div class="container">
			<div class="section-header" style="margin-bottom: var(--spacing-lg);">
				<h2 class="section-title">Latest Guides</h2>
			</div>
			
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: var(--spacing-md);">
				<?php
				$guides_query = new WP_Query( array(
					'post_type'      => 'user_guide',
					'posts_per_page' => 3,
				) );

				if ( $guides_query->have_posts() ) :
					while ( $guides_query->have_posts() ) : $guides_query->the_post();
						?>
						<a href="<?php the_permalink(); ?>" class="lohnour-card card-guide" style="display: flex; flex-direction: column; overflow: hidden; transition: transform 0.2s;">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="card-image" style="aspect-ratio: 3/2; width: 100%; overflow: hidden; background: var(--color-bg-alt);">
									<?php the_post_thumbnail( 'medium', array( 'style' => 'width: 100%; height: 100%; object-fit: cover; display: block;' ) ); ?>
								</div>
							<?php else: ?>
								<div class="card-image" style="aspect-ratio: 3/2; width: 100%; background: var(--color-bg-alt); display: flex; align-items: center; justify-content: center;">
									<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-border)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
								</div>
							<?php endif; ?>
							
							<div class="card-content" style="padding: var(--spacing-md); flex-grow: 1; display: flex; flex-direction: column;">
								<div style="margin-bottom: var(--spacing-xs);">
									<?php
									$terms = get_the_terms( get_the_ID(), 'guide_category' ); 
									if ( $terms && ! is_wp_error( $terms ) ) {
										echo '<span class="badge">' . esc_html( $terms[0]->name ) . '</span>';
									} else {
										echo '<span class="badge">Guide</span>';
									}
									?>
								</div>
								
								<h3 class="card-title" style="margin-bottom: var(--spacing-xs);"><?php the_title(); ?></h3>
								<p class="card-excerpt" style="flex-grow: 1; color: var(--color-text-secondary);"><?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?></p>
								
								<div class="card-footer" style="margin-top: var(--spacing-md);">
									<span class="link-arrow">Read Guide</span>
								</div>
							</div>
						</a>
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					echo '<p>No guides published yet.</p>';
				endif;
				?>
			</div>
			<div class="text-center" style="margin-top: var(--spacing-lg);">
				<a href="<?php echo esc_url( home_url( '/guides/' ) ); ?>" class="btn btn-outline">Explore All Guides</a>
			</div>
		</div>
	</section>

	<!-- 12 - FINAL CTA SECTION (Original Composition) -->
	<section class="cta-section py-xxl immersive-cta">
		<div class="container text-center">
			<div class="cta-content">
				<h2 class="section-title headline-lg">Ready to master the AI era?</h2>
				<p class="section-subtitle">Let's discuss your team's upskilling goals or your individual learning path.</p>
				<div class="cta-actions">
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary btn-lg">Contact Us</a>
				</div>
			</div>
		</div>
	</section>

</main>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Testimonial Carousel Logic
		const grid = document.getElementById('testimonialGrid');
		const prevBtn = document.getElementById('testiPrev');
		const nextBtn = document.getElementById('testiNext');
		const dotsContainer = document.getElementById('testiDots');
		
		if (grid && prevBtn && nextBtn && dotsContainer) {
			let cards = grid.querySelectorAll('.testimonial-card');
			if (cards.length > 0) {
				let visibleCount = 1;
				let totalDots = 1;
				let activeIndex = 0;
				let autoScrollInterval;

				function calculateLayout() {
					const gridWidth = grid.clientWidth;
					const cardWidth = cards[0].offsetWidth;
					visibleCount = Math.round(gridWidth / cardWidth) || 1;
					totalDots = Math.max(1, cards.length - visibleCount + 1);
					
					dotsContainer.innerHTML = '';
					for (let i = 0; i < totalDots; i++) {
						const dot = document.createElement('button');
						dot.classList.add('testimonial-dot');
						dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
						if (i === activeIndex) dot.classList.add('active');
						
						dot.addEventListener('click', () => {
							activeIndex = i;
							scrollToIndex(activeIndex);
						});
						dotsContainer.appendChild(dot);
					}
					
					if (activeIndex >= totalDots) {
						activeIndex = totalDots - 1;
					}
					updateActiveState();
				}

				function scrollToIndex(index) {
					const gap = parseInt(window.getComputedStyle(grid).gap || 0);
					const cardWidth = cards[0].offsetWidth + gap;
					grid.scrollTo({
						left: index * cardWidth,
						behavior: 'smooth'
					});
					updateActiveState();
				}

				function updateActiveState() {
					const dots = dotsContainer.querySelectorAll('.testimonial-dot');
					dots.forEach((dot, index) => {
						dot.classList.toggle('active', index === activeIndex);
					});
				}

				grid.addEventListener('scroll', () => {
					const gap = parseInt(window.getComputedStyle(grid).gap || 0);
					const cardWidth = cards[0].offsetWidth + gap;
					const scrollPos = grid.scrollLeft;
					
					let index = Math.round(scrollPos / cardWidth);
					if (index >= totalDots) index = totalDots - 1;
					if (index < 0) index = 0;
					
					activeIndex = index;
					updateActiveState();
				});

				prevBtn.addEventListener('click', () => {
					resetAutoScroll();
					if (activeIndex === 0) {
						activeIndex = totalDots - 1;
					} else {
						activeIndex--;
					}
					scrollToIndex(activeIndex);
				});

				nextBtn.addEventListener('click', () => {
					resetAutoScroll();
					if (activeIndex >= totalDots - 1) {
						activeIndex = 0;
					} else {
						activeIndex++;
					}
					scrollToIndex(activeIndex);
				});

				function startAutoScroll() {
					autoScrollInterval = setInterval(() => {
						if (activeIndex >= totalDots - 1) {
							activeIndex = 0;
						} else {
							activeIndex++;
						}
						scrollToIndex(activeIndex);
					}, 6000);
				}

				function resetAutoScroll() {
					clearInterval(autoScrollInterval);
					startAutoScroll();
				}

				let resizeTimeout;
				window.addEventListener('resize', () => {
					clearTimeout(resizeTimeout);
					resizeTimeout = setTimeout(() => {
						calculateLayout();
					}, 150);
				});

				calculateLayout();
				startAutoScroll();
			}
		}

		// Logo Auto Scroll Logic
		const logoGridElement = document.querySelector('.logo-grid');
		if (logoGridElement) {
			let isHovered = false;
			let isDown = false;
			let startX;
			let scrollLeft;

			logoGridElement.addEventListener('mousedown', (e) => {
				isDown = true;
				isHovered = true;
				startX = e.pageX - logoGridElement.offsetLeft;
				scrollLeft = logoGridElement.scrollLeft;
			});
			logoGridElement.addEventListener('mouseleave', () => {
				isDown = false;
				isHovered = false;
			});
			logoGridElement.addEventListener('mouseup', () => {
				isDown = false;
			});
			logoGridElement.addEventListener('mousemove', (e) => {
				if (!isDown) return;
				e.preventDefault();
				const x = e.pageX - logoGridElement.offsetLeft;
				const walk = (x - startX) * 1.5;
				logoGridElement.scrollLeft = scrollLeft - walk;
			});

			logoGridElement.addEventListener('mouseenter', () => {
				if (!isDown) isHovered = true;
			});

			logoGridElement.addEventListener('touchstart', () => isHovered = true, {passive: true});
			logoGridElement.addEventListener('touchend', () => {
				setTimeout(() => isHovered = false, 1000);
			}, {passive: true});
			
			function autoScrollLogos() {
				if (!isHovered && !isDown) {
					logoGridElement.scrollLeft += 0.5;
					if (logoGridElement.scrollLeft >= logoGridElement.scrollWidth / 2) {
						logoGridElement.scrollLeft = 0;
					}
				}
				requestAnimationFrame(autoScrollLogos);
			}
			requestAnimationFrame(autoScrollLogos);
		}
	});
</script>

<?php get_footer(); ?>
