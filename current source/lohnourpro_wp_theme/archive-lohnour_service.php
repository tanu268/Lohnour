<?php
/**
 * The template for displaying services archive (Pathways & their Programs)
 * Redesigned to show selected programs under each pathway category.
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">
	<div class="container">
		<?php
		get_template_part( 'template-parts/page-header', null, array(
			'title'       => __( 'Training Programs', 'twentytwentyfive-child' ),
			'subtitle'    => __( 'Explore our hands-on, practical AI training programs categorized by learning pathways.', 'twentytwentyfive-child' ),
			'breadcrumbs' => array(
				array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
				array( 'label' => __( 'Services', 'twentytwentyfive-child' ) ),
			),
		) );
		?>

		<?php
		$pathways = lohnour_get_ordered_pathways( array(
			'taxonomy'   => 'lohnour_pathway',
			'hide_empty' => false,
		) );

		if ( ! empty( $pathways ) && ! is_wp_error( $pathways ) ) :
			foreach ( $pathways as $pathway ) :
				$featured_ids = get_term_meta( $pathway->term_id, '_lohnour_featured_programs', true );
				$display_limit = get_term_meta( $pathway->term_id, '_lohnour_display_limit', true );
				if ( empty( $display_limit ) ) {
					$display_limit = 3;
				}

				// Build query args
				$args = array(
					'post_type'      => 'lohnour_service',
					'post_status'    => 'publish',
					'posts_per_page' => intval( $display_limit ),
				);

				if ( ! empty( $featured_ids ) && is_array( $featured_ids ) ) {
					$args['post__in'] = $featured_ids;
					$args['orderby'] = 'post__in';
				} else {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'lohnour_pathway',
							'field'    => 'term_id',
							'terms'    => $pathway->term_id,
						),
					);
				}

				$programs_query = new WP_Query( $args );

				?>
					<section class="pathway-section" style="margin-bottom: var(--spacing-xl);">
						<div class="pathway-header" style="margin-bottom: var(--spacing-md); display: flex; justify-content: space-between; align-items: flex-end;">
							<div>
								<h2 class="section-title" style="font-size: 1.75rem; margin-bottom: 4px;"><?php echo esc_html( $pathway->name ); ?></h2>
								<p class="section-subtitle" style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 0;"><?php echo esc_html( $pathway->description ); ?></p>
							</div>
						</div>
					<?php if ( $programs_query->have_posts() ) : ?>
						<div class="programs-grid">
							<?php
							while ( $programs_query->have_posts() ) :
								$programs_query->the_post();
								?>
								<a href="<?php the_permalink(); ?>" class="service-card glass-card hover-lift" style="display: flex; flex-direction: column; height: 100%; text-decoration: none; overflow: hidden; padding: 0;">
									<?php if ( has_post_thumbnail() ) : ?>
										<div class="card-image-wrapper" style="position: relative; width: 100%; aspect-ratio: 16 / 9; height: auto; border-bottom: 1px solid var(--color-glass-border); overflow: hidden;">
											<?php the_post_thumbnail( 'medium', array( 'style' => 'width: 100%; height: 100%; object-fit: cover; display: block;' ) ); ?>
											<span class="card-badge" style="position: absolute; top: 12px; right: 12px; z-index: 2; background: rgba(0,0,0,0.6); color: #fff; border-color: rgba(255,255,255,0.2);">Program</span>
										</div>
									<?php endif; ?>
									
									<div class="card-body" style="padding: var(--spacing-md); display: flex; flex-direction: column; flex-grow: 1;">
										<?php if ( ! has_post_thumbnail() ) : ?>
											<div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: var(--spacing-sm);">
												<div class="card-icon" style="margin-bottom: 0; color: var(--color-accent);">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
												</div>
												<span class="card-badge">Program</span>
											</div>
										<?php endif; ?>
										
										<h3 class="card-title" style="margin-bottom: var(--spacing-xs);"><?php the_title(); ?></h3>
										<?php 
										$card_excerpt = get_post_meta( get_the_ID(), '_lohnour_card_excerpt', true );
										if ( empty( $card_excerpt ) ) {
											$card_excerpt = get_the_excerpt();
										}
										?>
										<p class="card-desc" style="flex-grow: 1; margin-bottom: var(--spacing-md);"><?php echo esc_html( $card_excerpt ); ?></p>
										<span class="btn btn-outline" style="align-self: flex-start; padding: 0.4rem 0.8rem; font-size: 0.7rem;">Learn More</span>
									</div>
								</a>
								<?php
							endwhile;
							?>
												</div>
					<?php else : ?>
						<p style="color: var(--color-text-muted); font-size: 0.95rem; margin-top: var(--spacing-sm);">No active programs available in this pathway at the moment.</p>
					<?php endif; ?>
						<div style="margin-top: var(--spacing-md);">
							<a href="<?php echo esc_url( get_term_link( $pathway ) ); ?>" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.75rem; text-decoration: none;">View All Programs &rarr;</a>
						</div>
					</section>
					<?php
				wp_reset_postdata();
			endforeach;
		else :
			?>
			<p style="text-align: center; color: var(--color-text-muted);">No training pathways found.</p>
		<?php
		endif;
		?>
	</div>
</main>

<?php get_footer(); ?>

