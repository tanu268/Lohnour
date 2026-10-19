<?php
/**
 * Template Name: About Page
 * The template for displaying the About page.
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">
	<div class="container">
		<!-- Visually Consistent Header -->
		<?php
		get_template_part( 'template-parts/page-header', null, array(
			'title'       => __( 'About Lohnour Pro', 'twentytwentyfive-child' ),
			'subtitle'    => __( 'Simplifying Artificial Intelligence adoption through practical, business-oriented education.', 'twentytwentyfive-child' ),
			'breadcrumbs' => array(
				array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
				array( 'label' => __( 'About', 'twentytwentyfive-child' ) ),
			),
		) );
		?>

		<!-- The Bento Box Section -->
		<section class="about-bento-section" style="margin-bottom: var(--spacing-lg);">
			<div class="bento-grid" style="display: grid; grid-template-columns: repeat(12, 1fr); grid-auto-rows: minmax(180px, auto); gap: var(--spacing-md);">
				
				<!-- Large Tile (Left Column, Spans 2 rows) -->
				<div class="bento-tile glass-card hover-lift" style="grid-column: span 8; grid-row: span 2; padding: clamp(1.5rem, 3vw, 3rem); display: flex; flex-direction: column; justify-content: center; position: relative; overflow: hidden;">
					<div style="position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; background: var(--color-accent); filter: blur(120px); opacity: 0.15; border-radius: 50%; pointer-events: none;"></div>
					<div class="icon-wrapper" style="width: 56px; height: 56px; background: color-mix(in srgb, var(--color-text) 5%, transparent); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: var(--color-text); margin-bottom: var(--spacing-lg);">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
					</div>
					<h2 style="font-size: clamp(2rem, 3.5vw, 3rem); line-height: 1.1; margin-bottom: var(--spacing-md); font-weight: 700; letter-spacing: -0.02em;">No Fluff.<br><span style="color: var(--color-accent);">Pure Action.</span></h2>
					<p style="color: var(--color-text-muted); font-size: 1.1rem; line-height: 1.6; max-width: 650px; margin: 0 0 var(--spacing-sm) 0;">
						Lohnour Pro is an AI training and adoption partner dedicated to helping professionals and organizations confidently integrate artificial intelligence. We focus on intensive, hands-on education that empowers your team to work independently, while backing that capability with strategic consulting and custom workflow implementations.
					</p>
					<p style="color: var(--color-text-muted); font-size: 1.1rem; line-height: 1.6; max-width: 650px; margin: 0;">
						Our approach is strictly business-first. Instead of overwhelming you with complex jargon and theoretical concepts, we provide actionable frameworks and ready-to-deploy systems tailored to your specific operations. We believe AI should be an invisible engine that drives your productivity, empowering your workforce to focus on high-impact strategy while automating the routine execution.
					</p>
				</div>

				<!-- Small Tile 1 (Right Column, Top Row) -->
				<div class="bento-tile glass-card hover-lift" style="grid-column: span 4; grid-row: span 1; padding: clamp(1.25rem, 2vw, 2rem); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid color-mix(in srgb, var(--color-text) 20%, transparent);">
					<div>
						<div class="icon-wrapper" style="width: 48px; height: 48px; background: color-mix(in srgb, var(--color-text) 5%, transparent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-text); margin-bottom: var(--spacing-md);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
						</div>
						<h3 style="font-size: 1.25rem; margin-bottom: var(--spacing-xs);">Direct ROI Focus</h3>
						<p style="color: var(--color-text-muted); font-size: 0.95rem; line-height: 1.5; margin: 0;">
							Every lesson, every automation is engineered for one purpose: returning your investment through reclaimed time and amplified output.
						</p>
					</div>
				</div>

				<!-- Small Tile 2 (Right Column, Bottom Row) -->
				<div class="bento-tile glass-card hover-lift" style="grid-column: span 4; grid-row: span 1; padding: clamp(1.25rem, 2vw, 2rem); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid color-mix(in srgb, var(--color-text) 10%, transparent);">
					<div>
						<div class="icon-wrapper" style="width: 48px; height: 48px; background: color-mix(in srgb, var(--color-text) 5%, transparent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-text); margin-bottom: var(--spacing-md);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
						</div>
						<h3 style="font-size: 1.25rem; margin-bottom: var(--spacing-xs);">Custom Integration</h3>
						<p style="color: var(--color-text-muted); font-size: 0.95rem; line-height: 1.5; margin: 0;">
							We don't do generic seminars. To ensure your training translates into immediate ROI, we audit your operational toolstack and build custom AI implementations tailored to your workflows.
						</p>
					</div>
				</div>

			</div>
		</section>

		<!-- The Bold Statement Block -->
		<section class="statement-section" style="margin-bottom: var(--spacing-lg); text-align: center; padding: clamp(2rem, 5vw, 4rem) 0; position: relative;">
			<div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(10,10,12,0) 0%, rgba(16,185,129,0.03) 50%, rgba(10,10,12,0) 100%); z-index: -1;"></div>
			<h2 style="font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 300; line-height: 1.3; max-width: 1000px; margin: 0 auto; color: var(--color-text); letter-spacing: -0.01em;">
				<span style="opacity: 0.5;">We don't just teach AI.</span><br>
				<strong style="font-weight: 700; background: linear-gradient(135deg, var(--color-text) 0%, color-mix(in srgb, var(--color-text) 70%, transparent) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">We wire your business to use it.</strong>
			</h2>
		</section>

		<!-- The Architect Profile Section -->
		<section class="architect-section" style="margin-bottom: var(--spacing-lg);">
			<div class="architect-card glass-card hover-lift" style="display: flex; position: relative; overflow: hidden; align-items: stretch; padding: 0; min-height: 400px;">
				
				<div class="architect-visual" style="flex: 0 0 35%; position: relative; background: var(--color-bg-alt);">
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
						<img src="<?php echo esc_url( $image_url ); ?>" alt="Samarpit - The Architect" style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0;">
					<?php else : ?>
						<div class="founder-image-placeholder" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: color-mix(in srgb, var(--color-text) 5%, transparent); color: color-mix(in srgb, var(--color-text) 30%, transparent);">
							<span style="font-size: 1rem; letter-spacing: 2px;">PORTRAIT</span>
						</div>
					<?php endif; ?>
				</div>
				
				<div class="architect-content" style="flex: 1; padding: clamp(2rem, 5vw, 4rem); display: flex; flex-direction: column; justify-content: center; position: relative; z-index: 1;">
					<div style="display: flex; align-items: center; gap: 12px; margin-bottom: var(--spacing-sm);">
						<span style="display: inline-block; width: 40px; height: 1px; background: var(--color-accent);"></span>
						<span style="color: var(--color-accent); font-size: 0.85rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">Corporate AI Trainer & Founder</span>
					</div>
					<h2 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 700; margin-bottom: var(--spacing-md); line-height: 1.1;">Samarpit Shrivastava</h2>
					<p style="color: var(--color-text-muted); font-size: 1.15rem; line-height: 1.8; margin-bottom: var(--spacing-lg); max-width: 550px;">
						Bridging the massive divide between cutting-edge artificial intelligence and practical, day-to-day execution. Samarpit Shrivastava engineers the systems that allow teams to scale their capabilities without scaling their headcount.
					</p>
					
					<div style="display: flex; gap: 20px; align-items: center;">
						<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary">Initiate Contact</a>
					</div>
				</div>
			</div>
		</section>

		<!-- Theory vs Practice Section -->
		<section class="theory-practice-section" style="margin-bottom: var(--spacing-lg); padding: var(--spacing-md) 0;">
			<div class="theory-practice-grid" style="display: grid; grid-template-columns: repeat(12, 1fr); gap: var(--spacing-lg); align-items: start;">
				<div style="grid-column: span 5;">
					<h2 style="font-size: clamp(2rem, 3vw, 2.75rem); font-weight: 700; line-height: 1.2; margin: 0; color: var(--color-text); letter-spacing: -0.02em;">Capability Built<br>Through Practice.</h2>
				</div>
				<div style="grid-column: span 7;">
					<p style="color: var(--color-text-muted); font-size: 1.15rem; line-height: 1.8; margin: 0;">
						We believe that hands-on education is the absolute foundation of true AI capability. Our priority is equipping your team with practical, day-to-day skills they can use immediately. To guarantee that those skills drive measurable results, we surround our training programs with expert workflow consulting and dedicated systems integration.
					</p>
				</div>
			</div>
		</section>
		<!-- Who We Serve Section -->
		<?php
		$pathway_terms = lohnour_get_ordered_pathways( array( 'hide_empty' => false ) );
		$pathway_map   = array();
		if ( ! empty( $pathway_terms ) && ! is_wp_error( $pathway_terms ) ) {
			foreach ( $pathway_terms as $term ) {
				$name = strtolower( $term->name . ' ' . $term->slug );
				$t_link = get_term_link( $term );
				if ( is_wp_error( $t_link ) ) {
					continue;
				}
				if ( strpos( $name, 'corporate' ) !== false || strpos( $name, 'team' ) !== false ) {
					$pathway_map['corporate'] = $t_link;
				} elseif ( strpos( $name, 'individual' ) !== false || strpos( $name, 'professional' ) !== false ) {
					$pathway_map['individual'] = $t_link;
				} elseif ( strpos( $name, 'student' ) !== false ) {
					$pathway_map['student'] = $t_link;
				} elseif ( strpos( $name, 'faculty' ) !== false || strpos( $name, 'educator' ) !== false ) {
					$pathway_map['faculty'] = $t_link;
				}
			}
		}

		$link_corporate  = ! empty( $pathway_map['corporate'] ) ? $pathway_map['corporate'] : home_url( '/programs/pathway/corporate-teams' );
		$link_individual = ! empty( $pathway_map['individual'] ) ? $pathway_map['individual'] : home_url( '/programs/pathway/individual-professionals' );
		$link_student    = ! empty( $pathway_map['student'] ) ? $pathway_map['student'] : home_url( '/programs/pathway/students' );
		$link_faculty    = ! empty( $pathway_map['faculty'] ) ? $pathway_map['faculty'] : home_url( '/programs/pathway/faculty-educators' );
		?>

		<section class="who-we-serve-section" style="margin-bottom: var(--spacing-lg);">
			<div style="text-align: center; max-width: 650px; margin: 0 auto var(--spacing-lg) auto;">
				<h2 style="font-size: clamp(2rem, 3.5vw, 2.75rem); font-weight: 700; line-height: 1.2; margin: 0; color: var(--color-text); letter-spacing: -0.02em;">Who We Serve</h2>
			</div>

			<div class="who-we-serve-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--spacing-md);">
				<!-- 1. Corporate Teams -->
				<div class="glass-card hover-lift" style="padding: clamp(1.5rem, 2.5vw, 2rem); display: flex; flex-direction: column; justify-content: space-between; border-top: 3px solid var(--color-text);">
					<div>
						<div class="icon-wrapper" style="width: 48px; height: 48px; background: color-mix(in srgb, var(--color-text) 5%, transparent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-text); margin-bottom: var(--spacing-md);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
						</div>
						<span style="font-size: 0.75rem; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--color-text-muted); display: block; margin-bottom: 6px;">01. Enterprise</span>
						<h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: var(--spacing-xs); color: var(--color-text);">Corporate Teams</h3>
						<p style="color: var(--color-text-muted); font-size: 0.95rem; line-height: 1.6; margin: 0;">
							Tailored AI adoption frameworks, workflow automation, and hands-on team training designed to scale operational output without increasing headcount.
						</p>
					</div>
					<a href="<?php echo esc_url( $link_corporate ); ?>" class="btn btn-outline" style="margin-top: var(--spacing-md); align-self: flex-start;">
						Explore Offerings &rarr;
					</a>
				</div>

				<!-- 2. Individual Professionals -->
				<div class="glass-card hover-lift" style="padding: clamp(1.5rem, 2.5vw, 2rem); display: flex; flex-direction: column; justify-content: space-between; border-top: 3px solid var(--color-text);">
					<div>
						<div class="icon-wrapper" style="width: 48px; height: 48px; background: color-mix(in srgb, var(--color-text) 5%, transparent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-text); margin-bottom: var(--spacing-md);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
						</div>
						<span style="font-size: 0.75rem; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--color-text-muted); display: block; margin-bottom: 6px;">02. Career</span>
						<h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: var(--spacing-xs); color: var(--color-text);">Individual Professionals</h3>
						<p style="color: var(--color-text-muted); font-size: 0.95rem; line-height: 1.6; margin: 0;">
							Practical prompt engineering, AI toolkit integration, and personal productivity systems for ambitious specialists and decision makers.
						</p>
					</div>
					<a href="<?php echo esc_url( $link_individual ); ?>" class="btn btn-outline" style="margin-top: var(--spacing-md); align-self: flex-start;">
						Explore Offerings &rarr;
					</a>
				</div>

				<!-- 3. Students -->
				<div class="glass-card hover-lift" style="padding: clamp(1.5rem, 2.5vw, 2rem); display: flex; flex-direction: column; justify-content: space-between; border-top: 3px solid var(--color-text);">
					<div>
						<div class="icon-wrapper" style="width: 48px; height: 48px; background: color-mix(in srgb, var(--color-text) 5%, transparent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-text); margin-bottom: var(--spacing-md);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
						</div>
						<span style="font-size: 0.75rem; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--color-text-muted); display: block; margin-bottom: 6px;">03. Academia</span>
						<h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: var(--spacing-xs); color: var(--color-text);">Students</h3>
						<p style="color: var(--color-text-muted); font-size: 0.95rem; line-height: 1.6; margin: 0;">
							Intensive workshops and comprehensive long-term tracks focused on mastering practical AI tools and building real-world portfolios.
						</p>
					</div>
					<a href="<?php echo esc_url( $link_student ); ?>" class="btn btn-outline" style="margin-top: var(--spacing-md); align-self: flex-start;">
						Explore Offerings &rarr;
					</a>
				</div>

				<!-- 4. Faculty -->
				<div class="glass-card hover-lift" style="padding: clamp(1.5rem, 2.5vw, 2rem); display: flex; flex-direction: column; justify-content: space-between; border-top: 3px solid var(--color-text);">
					<div>
						<div class="icon-wrapper" style="width: 48px; height: 48px; background: color-mix(in srgb, var(--color-text) 5%, transparent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-text); margin-bottom: var(--spacing-md);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
						</div>
						<span style="font-size: 0.75rem; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--color-text-muted); display: block; margin-bottom: 6px;">04. Educators</span>
						<h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: var(--spacing-xs); color: var(--color-text);">Faculty & Educators</h3>
						<p style="color: var(--color-text-muted); font-size: 0.95rem; line-height: 1.6; margin: 0;">
							Targeted training designed to help educators build personal AI workflows and seamlessly integrate AI into academic curricula.
						</p>
					</div>
					<a href="<?php echo esc_url( $link_faculty ); ?>" class="btn btn-outline" style="margin-top: var(--spacing-md); align-self: flex-start;">
						Explore Offerings &rarr;
					</a>
				</div>
			</div>
		</section>

		<!-- Final CTA Section -->
		<section class="about-cta-section" style="margin-top: var(--spacing-md); margin-bottom: 0;">
			<div class="glass-card hover-lift" style="padding: clamp(2.5rem, 6vw, 4.5rem); text-align: center; border-radius: var(--radius-lg); position: relative; overflow: hidden;">
				<div style="position: absolute; top: -50%; left: 50%; transform: translateX(-50%); width: 400px; height: 300px; background: var(--color-accent); filter: blur(140px); opacity: 0.08; border-radius: 50%; pointer-events: none;"></div>
				<h2 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 700; line-height: 1.15; margin-bottom: var(--spacing-xs); color: var(--color-text);">Ready to wire AI into your workflows?</h2>
				<p style="color: var(--color-text-muted); font-size: 1.125rem; max-width: 600px; margin: 0 auto var(--spacing-md) auto; line-height: 1.6;">
					Whether you need intensive hands-on training to upskill your team, or expert consulting to architect your new AI workflows, we build the practical capabilities you need to scale.
				</p>
				<div style="display: flex; gap: 16px; justify-content: center; align-items: center; flex-wrap: wrap;">
					<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary btn-lg">Contact Us</a>
					<a href="<?php echo esc_url( home_url( '/programs' ) ); ?>" class="btn btn-outline btn-lg">Explore All Offerings &rarr;</a>
				</div>
			</div>
		</section>
	</div>
</main>

<style>
@media (max-width: 992px) {
	.who-we-serve-grid {
		grid-template-columns: repeat(2, 1fr) !important;
	}
	.theory-practice-grid {
		grid-template-columns: 1fr !important;
		gap: var(--spacing-lg) !important;
	}
	.theory-practice-grid > div {
		grid-column: 1 / -1 !important;
	}
	.bento-grid {
		grid-template-columns: 1fr !important;
	}
	.bento-grid > div {
		grid-column: 1 / -1 !important;
		grid-row: auto !important;
		min-width: 0;
		width: 100%;
		box-sizing: border-box;
	}
	.architect-card {
		flex-direction: column;
		text-align: center;
	}
	.architect-visual {
		flex: none !important;
		width: 100% !important;
		height: 300px !important;
	}
	.architect-content {
		display: flex;
		flex-direction: column;
		align-items: center;
	}
	.architect-content p {
		text-align: center;
	}
}

@media (max-width: 600px) {
	.who-we-serve-grid {
		grid-template-columns: 1fr !important;
	}
}
</style>

<?php get_footer(); ?>
