<?php
/**
 * The template for displaying single Program (Service CPT) details
 * Phase 9.1 Implementation
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main program-detail-page" style="padding-top: var(--header-clearance); padding-bottom: 0;">
	<?php
	while ( have_posts() ) :
		the_post();
		
		// Taxonomy Data
		$pathways = get_the_terms( get_the_ID(), 'lohnour_pathway' );
		$audiences = get_the_terms( get_the_ID(), 'lohnour_audience' );
		$industries = get_the_terms( get_the_ID(), 'lohnour_industry' );
		
		$card_excerpt = get_post_meta( get_the_ID(), '_lohnour_card_excerpt', true );
		if ( empty( $card_excerpt ) && has_excerpt() ) {
			$card_excerpt = get_the_excerpt();
		}
		
		$benefits_meta = get_post_meta( get_the_ID(), '_lohnour_sidebar_benefits', true );
		$benefits = array();
		if ( ! empty( $benefits_meta ) ) {
			$benefits = array_map( 'trim', explode( "\n", str_replace( "\r", "", $benefits_meta ) ) );
			$benefits = array_filter( $benefits );
		}
		?>
		
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'program-detail-article' ); ?>>
			
			<!-- 1. BREADCRUMB & HERO SECTION -->
			<header class="program-hero bg-alt" style="padding: var(--spacing-xxl) 0; border-bottom: 1px solid var(--color-border-subtle); margin-bottom: var(--spacing-xxl);">
				<div class="container" style="max-width: 1300px;">


					<div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: var(--spacing-md);">
						<?php if ( ! empty( $pathways ) && ! is_wp_error( $pathways ) ) : ?>
							<a href="<?php echo esc_url( get_term_link( $pathways[0] ) ); ?>" class="badge" style="display: inline-flex; align-items: center; padding: 6px 12px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-accent); background: rgba(var(--color-accent-rgb, 0, 112, 243), 0.1); border: 1px solid rgba(var(--color-accent-rgb, 0, 112, 243), 0.2); border-radius: 4px; text-decoration: none; transition: all 0.2s;">
								<?php echo esc_html( $pathways[0]->name ); ?>
							</a>
						<?php endif; ?>
					</div>
					
					<h1 class="headline-lg" style="margin-bottom: var(--spacing-md); font-family: var(--font-heading); max-width: 900px; line-height: 1.15; font-weight: 700; letter-spacing: -0.02em;">
						<?php the_title(); ?>
					</h1>
					
					<?php if ( ! empty( $card_excerpt ) ) : ?>
						<p class="section-subtitle" style="max-width: 760px; font-size: 1.25rem; line-height: 1.6; color: var(--color-text-secondary); margin-bottom: var(--spacing-xl); font-weight: 400;">
							<?php echo esc_html( $card_excerpt ); ?>
						</p>
					<?php endif; ?>

					<div style="display: flex; gap: var(--spacing-md); align-items: center; flex-wrap: wrap;">
						<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary" style="padding: 16px 32px; font-size: 1.05rem; font-weight: 600; border-radius: var(--radius-md);">Discuss This Program</a>
					</div>
				</div>
			</header>

			<!-- 2. MAIN LAYOUT -->
			<div class="container" style="max-width: 1300px; padding-bottom: var(--spacing-xxl);">
				<div class="sidebar-layout">
					
					<!-- Left: Content Column -->
					<div class="program-main-content">
						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="program-featured-image" style="margin-bottom: var(--spacing-xl); border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--color-border-subtle); background: var(--color-bg-alt); box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
								<?php the_post_thumbnail( 'full', array( 'style' => 'width: 100%; height: auto; display: block; max-height: 560px; object-fit: cover;' ) ); ?>
							</figure>
						<?php endif; ?>

						<!-- Program Overview -->
						<section class="program-overview prose-content" style="margin-bottom: var(--spacing-xxl);">
							<h2 style="font-size: 2rem; margin-bottom: var(--spacing-lg); color: var(--color-text-graphite); font-weight: 700; letter-spacing: -0.01em;">Program Overview</h2>
							<div class="program-content-body" style="font-size: 1.125rem; line-height: 1.8; color: var(--color-text-secondary);">
								<?php the_content(); ?>
							</div>
						</section>

						<!-- What You'll Learn -->
						<?php if ( ! empty( $benefits ) ) : ?>
							<section class="program-learning-outcomes" style="margin-bottom: var(--spacing-xxl); padding: var(--spacing-xl); background: var(--color-bg-alt); border-radius: var(--radius-lg); border: 1px solid var(--color-border-subtle);">
								<h2 style="font-size: 1.75rem; margin-bottom: var(--spacing-lg); color: var(--color-text-graphite); font-weight: 700; letter-spacing: -0.01em;">What You'll Learn</h2>
								<ul class="outcomes-grid" style="list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
									<?php foreach ( $benefits as $benefit ) : ?>
										<li style="display: flex; align-items: flex-start; gap: 16px; font-size: 1.05rem; color: var(--color-text-secondary); line-height: 1.6;">
											<div style="flex-shrink: 0; width: 28px; height: 28px; border-radius: 50%; background: var(--color-bg-surface); border: 2px solid var(--color-accent); color: var(--color-accent); display: flex; align-items: center; justify-content: center; margin-top: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
												<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
											</div>
											<span style="padding-top: 2px;"><?php echo wp_kses_post( $benefit ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							</section>
						<?php endif; ?>
					</div>

					<!-- Right: Sidebar Column -->
					<aside class="program-sidebar">
						<div class="sticky-sidebar-inner" style="position: sticky; top: calc(var(--header-clearance, 100px) + var(--spacing-lg));">
							
							<!-- Inquiry Card -->
							<div class="premium-sidebar-card" style="padding: 32px; border-radius: var(--radius-lg); margin-bottom: var(--spacing-lg); border: 1px solid var(--color-border); background: var(--color-bg-surface); box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
								
								<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: var(--spacing-md); line-height: 1.3;">Program Details</h3>

								<?php if ( ( ! empty( $audiences ) && ! is_wp_error( $audiences ) ) || ( ! empty( $pathways ) && ! is_wp_error( $pathways ) ) ) : ?>
									<div class="sidebar-meta-section" style="margin-bottom: var(--spacing-lg); padding-bottom: var(--spacing-lg); border-bottom: 1px solid var(--color-border-subtle);">
										<h4 style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 12px;">Who This Is For</h4>
										<div style="display: flex; flex-wrap: wrap; gap: 8px;">
											<?php 
											$audience_terms = ! empty( $audiences ) && ! is_wp_error( $audiences ) ? $audiences : $pathways;
											foreach ( $audience_terms as $term ) : ?>
												<span style="display: inline-flex; padding: 6px 12px; font-size: 0.875rem; font-weight: 500; background: var(--color-bg-alt); color: var(--color-text-secondary); border-radius: var(--radius-sm); border: 1px solid var(--color-border-subtle);"><?php echo esc_html( $term->name ); ?></span>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $industries ) && ! is_wp_error( $industries ) ) : ?>
									<div class="sidebar-meta-section" style="margin-bottom: var(--spacing-lg); padding-bottom: var(--spacing-lg); border-bottom: 1px solid var(--color-border-subtle);">
										<h4 style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 12px;">Topics / Industry</h4>
										<div style="display: flex; flex-wrap: wrap; gap: 8px;">
											<?php foreach ( $industries as $term ) : ?>
												<span style="display: inline-flex; padding: 6px 12px; font-size: 0.875rem; font-weight: 500; background: var(--color-bg-alt); color: var(--color-text-secondary); border-radius: var(--radius-sm); border: 1px solid var(--color-border-subtle);"><?php echo esc_html( $term->name ); ?></span>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>
								
								<!-- Action CTA -->
								<div class="sidebar-action" style="margin-top: var(--spacing-md);">
									<h4 style="font-size: 1.125rem; font-weight: 600; color: var(--color-text-graphite); margin-bottom: 10px;">Ready to begin?</h4>
									<p style="font-size: 0.95rem; color: var(--color-text-secondary); margin-bottom: 20px; line-height: 1.5;">Schedule a session with our team to discuss how this program fits your goals.</p>
									<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 14px 20px; font-weight: 600;">Request Information</a>
								</div>
							</div>
						</div>
					</aside>
				</div>
			</div>
			
			<style>
				/* Phase 9.1 CSS for Program Detail */
				
				/* Grid Layout */
				.program-detail-page .sidebar-layout {
					display: grid;
					grid-template-columns: 1fr;
					gap: var(--spacing-xxl);
					align-items: start;
				}
				
				@media (min-width: 992px) {
					.program-detail-page .sidebar-layout {
						/* ~65% content, ~35% sidebar */
						grid-template-columns: minmax(0, 1.8fr) 1fr;
					}
				}

				/* Typography adjustments for readability */
				.program-content-body p {
					margin-bottom: 1.5em;
				}
				.program-content-body h2 {
					font-size: 1.75rem;
					margin-top: 2em;
					margin-bottom: 1em;
					color: var(--color-text-graphite);
				}
				.program-content-body h3 {
					font-size: 1.5rem;
					margin-top: 1.75em;
					margin-bottom: 0.75em;
					color: var(--color-text-graphite);
				}
				.program-content-body ul, .program-content-body ol {
					margin-bottom: 1.5em;
					padding-left: 1.5em;
				}
				.program-content-body li {
					margin-bottom: 0.5em;
				}
				
				/* Dark mode specific tweaks to shadows and borders */
				html[data-theme="dark"] .premium-sidebar-card {
					box-shadow: 0 10px 30px rgba(0,0,0,0.4);
					background: var(--color-bg-alt);
				}
				html[data-theme="dark"] .program-learning-outcomes {
					background: var(--color-bg-surface);
				}
			</style>

		</article>

		<!-- 3. RELATED PROGRAMS SECTION -->
		<?php
		// Query related programs based on pathway
		$related_args = array(
			'post_type'      => 'lohnour_service',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
		);
		if ( ! empty( $pathways ) && ! is_wp_error( $pathways ) ) {
			$related_args['tax_query'] = array(
				array(
					'taxonomy' => 'lohnour_pathway',
					'field'    => 'term_id',
					'terms'    => wp_list_pluck( $pathways, 'term_id' ),
				),
			);
		}
		
		$related_query = new WP_Query( $related_args );
		if ( $related_query->have_posts() ) :
		?>
		<section class="related-programs" style="padding: var(--spacing-xxl) 0; border-top: 1px solid var(--color-border-subtle); background: var(--color-bg-alt);">
			<div class="container" style="max-width: 1300px;">
				<h2 style="font-size: 2rem; margin-bottom: var(--spacing-xl); color: var(--color-text-graphite); font-weight: 700; letter-spacing: -0.01em;">Explore Related Programs</h2>
				
				<div class="programs-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: var(--spacing-lg);">
					<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
						
						<!-- Exact same card structure as Programs Catalog -->
						<a href="<?php the_permalink(); ?>" class="lohnour-card card-program hover-lift" style="display: flex; flex-direction: column; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; border: 1px solid var(--color-border-subtle); border-radius: var(--radius-lg); background: var(--color-bg-surface); height: 100%;">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="card-image" style="aspect-ratio: 16/9; width: 100%; overflow: hidden; background: var(--color-bg-alt); border-bottom: 1px solid var(--color-border-subtle);">
									<?php the_post_thumbnail( 'medium', array( 'style' => 'width: 100%; height: 100%; object-fit: cover; display: block;' ) ); ?>
								</div>
							<?php else: ?>
								<div class="card-image" style="aspect-ratio: 16/9; width: 100%; background: var(--color-bg-alt); display: flex; align-items: center; justify-content: center; border-bottom: 1px solid var(--color-border-subtle);">
									<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-border)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
								</div>
							<?php endif; ?>
							
							<div class="card-content" style="padding: var(--spacing-lg); flex-grow: 1; display: flex; flex-direction: column;">
								<div style="margin-bottom: var(--spacing-sm); display: flex; gap: 8px; flex-wrap: wrap;">
									<?php
									$rel_pathways = get_the_terms( get_the_ID(), 'lohnour_pathway' );
									if ( $rel_pathways && ! is_wp_error( $rel_pathways ) ) {
										echo '<span class="badge" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-accent);">' . esc_html( $rel_pathways[0]->name ) . '</span>';
									} else {
										echo '<span class="badge" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-accent);">Program</span>';
									}
									?>
								</div>
								
								<h3 class="card-title" style="margin-bottom: var(--spacing-xs); font-size: 1.35rem; color: var(--color-text-graphite); line-height: 1.3; font-weight: 700;"><?php the_title(); ?></h3>
								<?php 
								$rel_excerpt = get_post_meta( get_the_ID(), '_lohnour_card_excerpt', true );
								if ( empty( $rel_excerpt ) ) {
									$rel_excerpt = wp_trim_words( get_the_excerpt(), 18, '...' );
								}
								?>
								<p class="card-excerpt" style="flex-grow: 1; color: var(--color-text-secondary); font-size: 0.95rem; margin-bottom: var(--spacing-lg); line-height: 1.6;"><?php echo esc_html( $rel_excerpt ); ?></p>
								
								<div class="card-footer" style="margin-top: auto; padding-top: var(--spacing-md); border-top: 1px solid var(--color-border-subtle);">
									<span class="link-arrow" style="color: var(--color-text-graphite); font-weight: 600; font-size: 0.95rem;">View Program →</span>
								</div>
							</div>
						</a>
						
					<?php endwhile; ?>
				</div>
			</div>
		</section>
		<?php
		endif;
		wp_reset_postdata();
		?>
		
	<?php
	endwhile;
	?>
</main>

<?php get_footer(); ?>
