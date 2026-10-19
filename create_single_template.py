import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

template_code = '''<?php
/**
 * Single Program Template
 * Reusable dynamic template for all Lohnour Programs (CPT: lohnour_program).
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header();

$post_id    = get_the_ID();
$facts      = Lohnour_Program_Renderer::get_facts( $post_id );
$banners    = Lohnour_Program_Renderer::get_banners( $post_id );
$ctas       = Lohnour_Program_Renderer::get_ctas( $post_id );
$overview   = Lohnour_Program_Renderer::get_overview( $post_id );
$why        = Lohnour_Program_Renderer::get_why( $post_id );
$curriculum = Lohnour_Program_Renderer::get_curriculum( $post_id );
$modules    = $curriculum['modules'];
$build      = Lohnour_Program_Renderer::get_build( $post_id );
$outcomes   = Lohnour_Program_Renderer::get_outcomes( $post_id );
$skills     = Lohnour_Program_Renderer::get_skills( $post_id );
$career     = Lohnour_Program_Renderer::get_career( $post_id );
$format     = Lohnour_Program_Renderer::get_format( $post_id );
$scope      = Lohnour_Program_Renderer::get_scope( $post_id );
$enrollment = Lohnour_Program_Renderer::get_enrollment( $post_id );
$faqs_data  = Lohnour_Program_Renderer::get_faqs( $post_id );
$faqs       = $faqs_data['items'];
$closing    = Lohnour_Program_Renderer::get_closing_cta( $post_id );

$hero_desc = get_post_meta( $post_id, '_lohnour_program_hero_desc', true );
if ( empty( $hero_desc ) ) {
	$hero_desc = get_the_excerpt();
}
?>

<main id="primary" class="site-main program-page">

	<!-- 1. PROGRAM HERO SECTION -->
	<header class="program-hero bg-alt">
		<div class="container program-hero-container">

			<!-- 1.1 Breadcrumb Context -->
			<nav class="program-hero-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'lohnourpro' ); ?>">
				<ol class="breadcrumb-list">
					<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'lohnourpro' ); ?></a></li>
					<li class="breadcrumb-separator" aria-hidden="true">/</li>
					<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/services' ) ); ?>"><?php esc_html_e( 'Services', 'lohnourpro' ); ?></a></li>
					<li class="breadcrumb-separator" aria-hidden="true">/</li>
					<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/coaching' ) ); ?>"><?php esc_html_e( 'Coaching', 'lohnourpro' ); ?></a></li>
					<li class="breadcrumb-separator" aria-hidden="true">/</li>
					<li class="breadcrumb-item breadcrumb-current" aria-current="page"><span><?php the_title(); ?></span></li>
				</ol>
			</nav>

			<!-- 1.2 Program Hero Grid -->
			<div class="program-hero-grid">
				<div class="program-hero-content">

					<h1 class="program-hero-title">
						<?php the_title(); ?>
					</h1>

					<?php if ( ! empty( $hero_desc ) ) : ?>
						<p class="program-hero-desc">
							<?php echo esc_html( $hero_desc ); ?>
						</p>
					<?php endif; ?>

					<!-- Program Meta Chips -->
					<div class="program-hero-meta" aria-label="<?php esc_attr_e( 'Program Highlights', 'lohnourpro' ); ?>">
						<?php if ( ! empty( $facts['duration_hours'] ) ) : ?>
							<div class="meta-chip">
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
								<span><?php echo esc_html( $facts['duration_hours'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $facts['length_text'] ) ) : ?>
							<div class="meta-chip">
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
								<span><?php echo esc_html( $facts['length_text'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $facts['max_participants'] ) ) : ?>
							<div class="meta-chip">
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
								<span><?php echo esc_html( $facts['max_participants'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $facts['delivery_style'] ) ) : ?>
							<div class="meta-chip">
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="2"></circle><path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"></path></svg>
								<span><?php echo esc_html( $facts['delivery_style'] ); ?></span>
							</div>
						<?php endif; ?>
					</div>

					<!-- Program CTA Actions -->
					<div class="program-hero-actions">
						<a href="<?php echo esc_url( $ctas['primary_url'] ); ?>" class="btn btn-primary btn-lg hero-btn-primary">
							<?php echo esc_html( $ctas['primary_label'] ); ?>
						</a>
						<a href="<?php echo esc_url( $ctas['secondary_url'] ); ?>" class="btn btn-outline btn-lg hero-btn-secondary">
							<?php echo esc_html( $ctas['secondary_label'] ); ?>
						</a>
					</div>

				</div>

				<!-- 1.6 Theme-Aware Responsive Program Banner -->
				<div class="program-hero-banner" aria-label="<?php esc_attr_e( 'Program Banner', 'lohnourpro' ); ?>">
					<img 
						src="<?php echo esc_url( $banners['dark_url'] ); ?>" 
						alt="<?php echo esc_attr( $banners['alt'] ); ?>" 
						class="banner-theme-dark" 
						width="1774" 
						height="887" 
						decoding="async" 
						fetchpriority="high"
					>
					<img 
						src="<?php echo esc_url( $banners['light_url'] ); ?>" 
						alt="<?php echo esc_attr( $banners['alt'] ); ?>" 
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
	<nav class="program-anchor-nav" aria-label="<?php esc_attr_e( 'Program Sections', 'lohnourpro' ); ?>">
		<div class="container">
			<ul class="anchor-nav-list">
				<li><a href="#overview" class="anchor-link active"><?php esc_html_e( 'Overview', 'lohnourpro' ); ?></a></li>
				<li><a href="#why-program" class="anchor-link"><?php esc_html_e( 'Why This Program', 'lohnourpro' ); ?></a></li>
				<li><a href="#what-youll-learn" class="anchor-link"><?php esc_html_e( "What You'll Learn", 'lohnourpro' ); ?></a></li>
				<li><a href="#curriculum" class="anchor-link"><?php esc_html_e( 'Curriculum', 'lohnourpro' ); ?></a></li>
				<li><a href="#what-youll-build" class="anchor-link"><?php esc_html_e( "What You'll Build", 'lohnourpro' ); ?></a></li>
				<li><a href="#outcomes" class="anchor-link"><?php esc_html_e( 'Outcomes', 'lohnourpro' ); ?></a></li>
				<li><a href="#format" class="anchor-link"><?php esc_html_e( 'Format', 'lohnourpro' ); ?></a></li>
				<li><a href="#faqs" class="anchor-link"><?php esc_html_e( 'FAQs', 'lohnourpro' ); ?></a></li>
			</ul>
		</div>
	</nav>

	<div class="container program-body-container">

		<!-- 4. PROGRAM OVERVIEW -->
		<section id="overview" class="program-section">
			<div class="section-heading-wrapper">
				<?php if ( ! empty( $overview['badge'] ) ) : ?>
					<span class="card-badge"><?php echo esc_html( $overview['badge'] ); ?></span>
				<?php endif; ?>
				<h2 class="section-title"><?php echo esc_html( $overview['title'] ); ?></h2>
				<?php if ( ! empty( $overview['subtitle'] ) ) : ?>
					<p class="section-subtitle"><?php echo esc_html( $overview['subtitle'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="overview-grid">
				<div class="overview-main glass-card">
					<?php if ( ! empty( $overview['main_h3'] ) ) : ?>
						<h3><?php echo esc_html( $overview['main_h3'] ); ?></h3>
					<?php endif; ?>
					<?php foreach ( (array) $overview['paragraphs'] as $p_text ) : ?>
						<p><?php echo esc_html( $p_text ); ?></p>
					<?php endforeach; ?>
				</div>

				<div class="overview-highlights glass-card">
					<?php if ( ! empty( $overview['highlights_h3'] ) ) : ?>
						<h3><?php echo esc_html( $overview['highlights_h3'] ); ?></h3>
					<?php endif; ?>
					<ul class="highlights-list">
						<?php foreach ( (array) $overview['highlights'] as $hl ) : ?>
							<li>
								<span class="bullet-icon" aria-hidden="true"></span>
								<div>
									<strong><?php echo esc_html( $hl['title'] ); ?></strong>
									<p><?php echo esc_html( $hl['desc'] ); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</section>

		<!-- 5. WHY THIS PROGRAM -->
		<section id="why-program" class="program-section">
			<div class="section-heading-wrapper">
				<?php if ( ! empty( $why['badge'] ) ) : ?>
					<span class="card-badge"><?php echo esc_html( $why['badge'] ); ?></span>
				<?php endif; ?>
				<h2 class="section-title"><?php echo esc_html( $why['title'] ); ?></h2>
				<?php if ( ! empty( $why['subtitle'] ) ) : ?>
					<p class="section-subtitle"><?php echo esc_html( $why['subtitle'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="philosophy-card glass-card">
				<?php if ( ! empty( $why['lead_text'] ) ) : ?>
					<p class="lead-text">
						<?php echo esc_html( $why['lead_text'] ); ?>
					</p>
				<?php endif; ?>

				<?php if ( ! empty( $why['flow_steps'] ) ) : ?>
					<div class="progression-flow">
						<?php
						$total_steps = count( $why['flow_steps'] );
						foreach ( $why['flow_steps'] as $idx => $st ) :
							$is_last = ( $idx === $total_steps - 1 );
							?>
							<div class="step-pill <?php echo $is_last ? 'highlight' : ''; ?>">
								<span><?php echo esc_html( $st['num'] ); ?></span> <?php echo esc_html( $st['label'] ); ?>
							</div>
							<?php if ( ! $is_last ) : ?>
								<div class="step-arrow">→</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $why['columns'] ) ) : ?>
					<div class="philosophy-columns">
						<?php foreach ( $why['columns'] as $col ) : ?>
							<div class="phil-col">
								<h3><?php echo esc_html( $col['title'] ); ?></h3>
								<p><?php echo esc_html( $col['desc'] ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<!-- 6. WHAT YOU'LL LEARN -->
		<section id="what-youll-learn" class="program-section">
			<div class="section-heading-wrapper">
				<span class="card-badge"><?php esc_html_e( 'Curriculum Overview', 'lohnourpro' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( "What You'll Learn", 'lohnourpro' ); ?></h2>
				<p class="section-subtitle"><?php printf( esc_html__( '%d structured modules covering live instructional and hands-on building hours.', 'lohnourpro' ), count( $modules ) ); ?></p>
			</div>

			<div class="module-summary-grid">
				<?php foreach ( $modules as $mod ) : ?>
					<div class="module-summary-card glass-card hover-lift">
						<div class="mod-num"><?php echo esc_html( $mod['number'] ); ?></div>
						<h3><?php echo esc_html( $mod['title'] ); ?></h3>
						<?php if ( ! empty( $mod['duration'] ) ) : ?>
							<span class="mod-time"><?php echo esc_html( $mod['duration'] ); ?></span>
						<?php endif; ?>
						<p><?php echo esc_html( wp_trim_words( $mod['overview'], 20, '...' ) ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<!-- 7. DETAILED CURRICULUM BREAKDOWN -->
		<section id="curriculum" class="program-section">
			<div class="section-heading-wrapper">
				<?php if ( ! empty( $curriculum['badge'] ) ) : ?>
					<span class="card-badge"><?php echo esc_html( $curriculum['badge'] ); ?></span>
				<?php endif; ?>
				<h2 class="section-title"><?php echo esc_html( $curriculum['title'] ); ?></h2>
				<?php if ( ! empty( $curriculum['subtitle'] ) ) : ?>
					<p class="section-subtitle"><?php echo esc_html( $curriculum['subtitle'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="curriculum-guide-layout">
				<div class="curriculum-guide-content">

					<?php foreach ( $modules as $idx => $mod ) :
						$mod_num_clean = (int) $mod['number'];
						$mod_id        = 'curriculum-mod-' . $mod_num_clean;
						$mod_header_id = $mod_id . '-header';
						$mod_ctrl_id   = $mod_id . '-content';
						?>
						<!-- MODULE <?php echo esc_html( $mod['number'] ); ?> BLOCK -->
						<article id="<?php echo esc_attr( $mod_id ); ?>" class="curriculum-guide-module-block" data-module="<?php echo esc_attr( $mod_num_clean ); ?>">
							<h3 class="module-accordion-heading">
								<button type="button" class="module-accordion-header" id="<?php echo esc_attr( $mod_header_id ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $mod_ctrl_id ); ?>">
									<span class="module-header-main">
										<span class="module-number-pill"><?php echo esc_html( $mod['number'] ); ?></span>
										<span class="module-title-text">
											<?php if ( ! empty( $mod['prefix'] ) ) : ?>
												<span class="module-prefix"><?php echo esc_html( $mod['prefix'] ); ?></span>
											<?php endif; ?>
											<?php echo esc_html( $mod['title'] ); ?>
										</span>
									</span>
									<span class="module-header-meta">
										<?php if ( ! empty( $mod['duration'] ) ) : ?>
											<span class="module-duration-badge"><?php echo esc_html( $mod['duration'] ); ?></span>
										<?php endif; ?>
										<span class="module-toggle-icon" aria-hidden="true">+</span>
									</span>
								</button>
							</h3>

							<div id="<?php echo esc_attr( $mod_ctrl_id ); ?>" class="module-accordion-content" role="region" aria-labelledby="<?php echo esc_attr( $mod_header_id ); ?>">
								<div class="module-accordion-body">
									<?php if ( ! empty( $mod['overview'] ) ) : ?>
										<p class="module-block-overview">
											<?php echo esc_html( $mod['overview'] ); ?>
										</p>
									<?php endif; ?>

									<div class="guides-accordion-container">
										<!-- What You'll Learn -->
										<?php if ( ! empty( $mod['learn_topics'] ) ) : ?>
											<div id="<?php echo esc_attr( $mod_id ); ?>-learn" class="guides-accordion-item">
												<button class="guides-accordion-header" aria-expanded="false" aria-controls="<?php echo esc_attr( $mod_id ); ?>-learn-content">
													<span class="accordion-title"><?php esc_html_e( "What You'll Learn", 'lohnourpro' ); ?></span>
													<span class="accordion-icon" aria-hidden="true">+</span>
												</button>
												<div id="<?php echo esc_attr( $mod_id ); ?>-learn-content" class="guides-accordion-content">
													<div class="guides-accordion-inner">
														<ul class="guide-topic-tags">
															<?php foreach ( (array) $mod['learn_topics'] as $topic ) : ?>
																<li><?php echo esc_html( $topic ); ?></li>
															<?php endforeach; ?>
														</ul>
													</div>
												</div>
											</div>
										<?php endif; ?>

										<!-- Practical / Hands-On Activity -->
										<?php if ( ! empty( $mod['practical'] ) ) : ?>
											<div id="<?php echo esc_attr( $mod_id ); ?>-practical" class="guides-accordion-item">
												<button class="guides-accordion-header" aria-expanded="false" aria-controls="<?php echo esc_attr( $mod_id ); ?>-practical-content">
													<span class="accordion-title"><?php esc_html_e( 'Practical / Hands-On Activity', 'lohnourpro' ); ?></span>
													<span class="accordion-icon" aria-hidden="true">+</span>
												</button>
												<div id="<?php echo esc_attr( $mod_id ); ?>-practical-content" class="guides-accordion-content">
													<div class="guides-accordion-inner">
														<?php if ( ! empty( $mod['progression_pills'] ) ) : ?>
															<div class="progression-pill-row">
																<?php
																$total_pills = count( $mod['progression_pills'] );
																foreach ( $mod['progression_pills'] as $pidx => $ptext ) :
																	$is_last_pill = ( $pidx === $total_pills - 1 );
																	?>
																	<span class="pill-step <?php echo $is_last_pill ? 'highlight' : ''; ?>"><?php echo esc_html( $ptext ); ?></span>
																	<?php if ( ! $is_last_pill ) : ?>
																		<span class="pill-sep">→</span>
																	<?php endif; ?>
																<?php endforeach; ?>
															</div>
														<?php endif; ?>
														<div class="guide-activity-box">
															<p><?php echo esc_html( $mod['practical'] ); ?></p>
														</div>
													</div>
												</div>
											</div>
										<?php endif; ?>

										<!-- Module Outcome -->
										<?php if ( ! empty( $mod['outcome'] ) ) : ?>
											<div id="<?php echo esc_attr( $mod_id ); ?>-outcome" class="guides-accordion-item">
												<button class="guides-accordion-header" aria-expanded="false" aria-controls="<?php echo esc_attr( $mod_id ); ?>-outcome-content">
													<span class="accordion-title"><?php esc_html_e( 'Module Outcome', 'lohnourpro' ); ?></span>
													<span class="accordion-icon" aria-hidden="true">+</span>
												</button>
												<div id="<?php echo esc_attr( $mod_id ); ?>-outcome-content" class="guides-accordion-content">
													<div class="guides-accordion-inner">
														<div class="guide-outcome-box">
															<p><?php echo esc_html( $mod['outcome'] ); ?></p>
														</div>
													</div>
												</div>
											</div>
										<?php endif; ?>
									</div><!-- /.guides-accordion-container -->
								</div>
							</div>
						</article>
					<?php endforeach; ?>

				</div><!-- /.curriculum-guide-content -->
			</div><!-- /.curriculum-guide-layout -->
		</section>

		<!-- 8. WHAT YOU'LL BUILD -->
		<?php if ( ! empty( $build['cards'] ) ) : ?>
			<section id="what-youll-build" class="program-section">
				<div class="section-heading-wrapper">
					<?php if ( ! empty( $build['badge'] ) ) : ?>
						<span class="card-badge"><?php echo esc_html( $build['badge'] ); ?></span>
					<?php endif; ?>
					<h2 class="section-title"><?php echo esc_html( $build['title'] ); ?></h2>
					<?php if ( ! empty( $build['subtitle'] ) ) : ?>
						<p class="section-subtitle"><?php echo esc_html( $build['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="build-progression-grid">
					<?php
					$total_build_cards = count( $build['cards'] );
					foreach ( $build['cards'] as $bidx => $bcard ) :
						$is_capstone = ( $bidx === $total_build_cards - 1 );
						?>
						<div class="build-card glass-card hover-lift <?php echo $is_capstone ? 'capstone-card' : ''; ?>">
							<div class="build-step"><?php echo esc_html( $bcard['step'] ); ?></div>
							<h3><?php echo esc_html( $bcard['title'] ); ?></h3>
							<p><?php echo esc_html( $bcard['desc'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- 9. PROGRAM OUTCOMES -->
		<?php if ( ! empty( $outcomes['items'] ) ) : ?>
			<section id="outcomes" class="program-section">
				<div class="section-heading-wrapper">
					<?php if ( ! empty( $outcomes['badge'] ) ) : ?>
						<span class="card-badge"><?php echo esc_html( $outcomes['badge'] ); ?></span>
					<?php endif; ?>
					<h2 class="section-title"><?php echo esc_html( $outcomes['title'] ); ?></h2>
					<?php if ( ! empty( $outcomes['subtitle'] ) ) : ?>
						<p class="section-subtitle"><?php echo esc_html( $outcomes['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="outcomes-statement-card glass-card">
					<?php if ( ! empty( $outcomes['north_star_quote'] ) ) : ?>
						<div class="north-star-quote">
							<p>"<?php echo esc_html( $outcomes['north_star_quote'] ); ?>"</p>
						</div>
					<?php endif; ?>

					<div class="outcomes-checklist-grid">
						<?php foreach ( $outcomes['items'] as $item ) : ?>
							<div class="outcome-item">
								<span class="check-icon">✓</span>
								<div>
									<strong><?php echo esc_html( $item['title'] ); ?></strong>
									<p><?php echo esc_html( $item['desc'] ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- 10. SKILLS YOU'LL BUILD -->
		<?php if ( ! empty( $skills['tags'] ) ) : ?>
			<section class="program-section">
				<div class="section-heading-wrapper">
					<?php if ( ! empty( $skills['badge'] ) ) : ?>
						<span class="card-badge"><?php echo esc_html( $skills['badge'] ); ?></span>
					<?php endif; ?>
					<h2 class="section-title"><?php echo esc_html( $skills['title'] ); ?></h2>
					<?php if ( ! empty( $skills['subtitle'] ) ) : ?>
						<p class="section-subtitle"><?php echo esc_html( $skills['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="skills-tag-cloud">
					<?php foreach ( $skills['tags'] as $tag ) : ?>
						<span class="skill-tag"><?php echo esc_html( $tag ); ?></span>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- 11. WHERE THESE SKILLS CAN BE APPLIED -->
		<?php if ( ! empty( $career['cards'] ) ) : ?>
			<section class="program-section">
				<div class="section-heading-wrapper">
					<?php if ( ! empty( $career['badge'] ) ) : ?>
						<span class="card-badge"><?php echo esc_html( $career['badge'] ); ?></span>
					<?php endif; ?>
					<h2 class="section-title"><?php echo esc_html( $career['title'] ); ?></h2>
					<?php if ( ! empty( $career['subtitle'] ) ) : ?>
						<p class="section-subtitle"><?php echo esc_html( $career['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="career-grid">
					<?php foreach ( $career['cards'] as $ccard ) : ?>
						<div class="career-card glass-card">
							<h3><?php echo esc_html( $ccard['title'] ); ?></h3>
							<p><?php echo esc_html( $ccard['desc'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( ! empty( $career['disclaimer'] ) ) : ?>
					<p class="career-disclaimer">
						<?php echo esc_html( $career['disclaimer'] ); ?>
					</p>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<!-- 12. HOW COACHING WORKS -->
		<?php if ( ! empty( $format['steps'] ) ) : ?>
			<section id="format" class="program-section">
				<div class="section-heading-wrapper">
					<?php if ( ! empty( $format['badge'] ) ) : ?>
						<span class="card-badge"><?php echo esc_html( $format['badge'] ); ?></span>
					<?php endif; ?>
					<h2 class="section-title"><?php echo esc_html( $format['title'] ); ?></h2>
					<?php if ( ! empty( $format['subtitle'] ) ) : ?>
						<p class="section-subtitle"><?php echo esc_html( $format['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="coaching-flow-grid">
					<?php foreach ( $format['steps'] as $fstep ) : ?>
						<div class="coaching-step glass-card">
							<div class="step-num"><?php echo esc_html( $fstep['num'] ); ?></div>
							<h3><?php echo esc_html( $fstep['title'] ); ?></h3>
							<p><?php echo esc_html( $fstep['desc'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- 13. SCOPE & BOUNDARIES -->
		<?php if ( ! empty( $scope['included'] ) || ! empty( $scope['excluded'] ) ) : ?>
			<section class="program-section">
				<div class="section-heading-wrapper">
					<?php if ( ! empty( $scope['badge'] ) ) : ?>
						<span class="card-badge"><?php echo esc_html( $scope['badge'] ); ?></span>
					<?php endif; ?>
					<h2 class="section-title"><?php echo esc_html( $scope['title'] ); ?></h2>
					<?php if ( ! empty( $scope['subtitle'] ) ) : ?>
						<p class="section-subtitle"><?php echo esc_html( $scope['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="scope-grid">
					<!-- What's Included -->
					<?php if ( ! empty( $scope['included'] ) ) : ?>
						<div class="scope-card glass-card included-card">
							<h3 class="scope-title">
								<span class="scope-icon check">✓</span>
								<?php esc_html_e( "What's Included", 'lohnourpro' ); ?>
							</h3>
							<ul class="scope-list">
								<?php foreach ( $scope['included'] as $inc_item ) : ?>
									<li><?php echo esc_html( $inc_item ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<!-- What's Not Included -->
					<?php if ( ! empty( $scope['excluded'] ) ) : ?>
						<div class="scope-card glass-card excluded-card">
							<h3 class="scope-title">
								<span class="scope-icon xmark">✕</span>
								<?php esc_html_e( "What's Not Included (Scope Clarity)", 'lohnourpro' ); ?>
							</h3>
							<?php if ( ! empty( $scope['scope_note'] ) ) : ?>
								<p class="scope-note">
									<?php echo esc_html( $scope['scope_note'] ); ?>
								</p>
							<?php endif; ?>
							<ul class="scope-list">
								<?php foreach ( $scope['excluded'] as $exc_item ) : ?>
									<li><?php echo esc_html( $exc_item ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- 14. COHORT ENROLLMENT -->
		<section class="program-section">
			<div class="pricing-card glass-card text-center">
				<?php if ( ! empty( $enrollment['badge'] ) ) : ?>
					<span class="card-badge"><?php echo esc_html( $enrollment['badge'] ); ?></span>
				<?php endif; ?>
				<h2 class="section-title"><?php echo esc_html( $enrollment['title'] ); ?></h2>
				<?php if ( ! empty( $enrollment['desc'] ) ) : ?>
					<p class="pricing-desc">
						<?php echo esc_html( $enrollment['desc'] ); ?>
					</p>
				<?php endif; ?>

				<?php if ( ! empty( $enrollment['pills'] ) ) : ?>
					<div class="pricing-callout">
						<?php foreach ( $enrollment['pills'] as $pill ) : ?>
							<div class="callout-pill"><?php echo esc_html( $pill ); ?></div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="pricing-actions">
					<a href="<?php echo esc_url( $enrollment['cta_url'] ); ?>" class="btn btn-primary btn-lg">
						<?php echo esc_html( $enrollment['cta_label'] ); ?>
					</a>
				</div>
				<?php if ( ! empty( $enrollment['subtext'] ) ) : ?>
					<p class="pricing-subtext">
						<?php echo esc_html( $enrollment['subtext'] ); ?>
					</p>
				<?php endif; ?>
			</div>
		</section>

		<!-- 15. FAQ ACCORDION -->
		<?php if ( ! empty( $faqs ) ) : ?>
			<section id="faqs" class="program-section">
				<div class="section-heading-wrapper">
					<?php if ( ! empty( $faqs_data['badge'] ) ) : ?>
						<span class="card-badge"><?php echo esc_html( $faqs_data['badge'] ); ?></span>
					<?php endif; ?>
					<h2 class="section-title"><?php echo esc_html( $faqs_data['title'] ); ?></h2>
					<?php if ( ! empty( $faqs_data['subtitle'] ) ) : ?>
						<p class="section-subtitle"><?php echo esc_html( $faqs_data['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="faq-accordion-container" id="faq-accordion">
					<?php foreach ( $faqs as $faq ) : ?>
						<div class="faq-item glass-card">
							<button class="faq-header" aria-expanded="false">
								<span><?php echo esc_html( $faq['question'] ); ?></span>
								<span class="faq-toggle-icon" aria-hidden="true">+</span>
							</button>
							<div class="faq-content" hidden>
								<p><?php echo wp_kses_post( $faq['answer'] ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- 16. FINAL CTA CARD -->
		<?php if ( ! empty( $closing ) ) : ?>
			<section class="cta-section glass-card text-center">
				<h2 class="cta-title"><?php echo esc_html( $closing['title'] ); ?></h2>
				<p class="cta-desc">
					<?php echo esc_html( $closing['desc'] ); ?>
				</p>
				<a href="<?php echo esc_url( $closing['cta_url'] ); ?>" class="btn btn-primary btn-lg">
					<?php echo esc_html( $closing['cta_label'] ); ?>
				</a>
			</section>
		<?php endif; ?>

	</div><!-- /.container -->
</main>

<?php
get_footer();
'''

# Write to theme
with open(r'current source/lohnourpro_wp_theme/single-lohnour_program.php', 'w', encoding='utf-8') as f:
    f.write(template_code)
print("Written to theme: single-lohnour_program.php")

# Write to plugin templates
os.makedirs(r'current source/lohnour-program-cms/templates', exist_ok=True)
with open(r'current source/lohnour-program-cms/templates/single-lohnour_program.php', 'w', encoding='utf-8') as f:
    f.write(template_code)
print("Written to plugin: templates/single-lohnour_program.php")
