<?php
/**
 * The template for displaying a single Pathway taxonomy term archive (lists Programs inside it)
 * Phase 6 Implementation
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header();

$current_term = get_queried_object();
$title = $current_term->name;
$description = $current_term->description;
?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: 0;">
	
	<!-- 1. BREADCRUMB & PATHWAY HERO -->
	<header class="page-hero bg-alt" style="padding: var(--spacing-xxl) 0 var(--spacing-xl) 0; border-bottom: 1px solid var(--color-border-subtle); margin-bottom: var(--spacing-xl);">
		<div class="container text-center">
			


			<div style="margin-bottom: var(--spacing-sm);">
				<span class="badge" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-text-muted); background: transparent; border: 1px solid var(--color-border); padding: 4px 12px; border-radius: var(--radius-full);">Pathway</span>
			</div>
			
			<h1 class="headline-lg" style="margin-bottom: var(--spacing-md); font-family: var(--font-heading);">
				<?php echo esc_html( $title ); ?>
			</h1>
			
			<?php if ( ! empty( $description ) ) : ?>
				<p class="section-subtitle" style="max-width: 600px; margin: 0 auto; color: var(--color-text-secondary); font-size: 1.125rem; line-height: 1.6;">
					<?php echo wp_kses_post( $description ); ?>
				</p>
			<?php endif; ?>
			
		</div>
	</header>

	<!-- 2. PROGRAM COLLECTION -->
	<section class="pathway-programs" style="padding-bottom: var(--spacing-xxl);">
		<div class="container">
			
			<div class="results-meta" style="margin-bottom: var(--spacing-lg); color: var(--color-text-secondary); font-size: 0.95rem; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: var(--spacing-sm);">
				 <?php
				 global $wp_query;
				 $total_results = $wp_query->found_posts;
				 echo sprintf( esc_html( _n( '%s program available', '%s programs available', $total_results, 'twentytwentyfive-child' ) ), number_format_i18n( $total_results ) );
				 ?>
			</div>

			<?php if ( have_posts() ) : ?>
				
				<div class="programs-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: var(--spacing-lg);">
					<?php while ( have_posts() ) : the_post(); ?>
						
						<!-- Phase 4 Consistent Program Card -->
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
									<span class="badge" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-accent);"><?php echo esc_html( $title ); ?></span>
								</div>
								
								<h3 class="card-title" style="margin-bottom: var(--spacing-xs); font-size: 1.35rem; color: var(--color-text-graphite); line-height: 1.3; font-weight: 600;"><?php the_title(); ?></h3>
								<?php 
								$card_excerpt = get_post_meta( get_the_ID(), '_lohnour_card_excerpt', true );
								if ( empty( $card_excerpt ) ) {
									$card_excerpt = wp_trim_words( get_the_excerpt(), 18, '...' );
								}
								?>
								<p class="card-excerpt" style="flex-grow: 1; color: var(--color-text-secondary); font-size: 0.95rem; margin-bottom: var(--spacing-lg); line-height: 1.5;"><?php echo esc_html( $card_excerpt ); ?></p>
								
								<div class="card-footer" style="margin-top: auto; padding-top: var(--spacing-md); border-top: 1px solid var(--color-border-subtle);">
									<span class="link-arrow" style="color: var(--color-text-graphite); font-weight: 500; font-size: 0.95rem;">View Program →</span>
								</div>
							</div>
						</a>
						
					<?php endwhile; ?>
				</div>
				
				<!-- Pagination -->
				<div class="pagination-wrapper" style="margin-top: var(--spacing-xxl); display: flex; justify-content: center;">
					<?php
					the_posts_pagination( array(
						'prev_text' => '← Previous',
						'next_text' => 'Next →',
						'class'     => 'lohnour-pagination',
						'mid_size'  => 2
					) );
					?>
				</div>
				<style>
					.lohnour-pagination .nav-links { display: flex; gap: 8px; align-items: center; }
					.lohnour-pagination .page-numbers { padding: 8px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); text-decoration: none; color: var(--color-text-secondary); background: var(--color-bg-surface); transition: all 0.2s; }
					.lohnour-pagination .page-numbers:hover { border-color: var(--color-text-graphite); color: var(--color-text-graphite); }
					.lohnour-pagination .page-numbers.current { background: var(--color-text-graphite); color: var(--color-bg-surface); border-color: var(--color-text-graphite); }
					.lohnour-pagination .dots { background: transparent; border: none; padding: 0; pointer-events: none; }
				</style>
				
			<?php else : ?>
				
				<!-- EMPTY STATE -->
				<div class="state-container" style="text-align: center; padding: var(--spacing-xxl) var(--spacing-md); background: var(--color-bg-surface); border: 1px dashed var(--color-border); border-radius: var(--radius-lg);">
					<svg class="state-icon" style="margin: 0 auto var(--spacing-md); color: var(--color-text-muted); width: 48px; height: 48px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					<h3 class="state-title" style="font-size: 1.25rem; margin-bottom: 8px; color: var(--color-text-graphite);">No programs are currently available for this pathway.</h3>
					<p class="state-desc" style="color: var(--color-text-secondary); margin-bottom: var(--spacing-md);">Check back soon for new learning opportunities.</p>
					<div>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'lohnour_service' ) ); ?>" class="btn btn-outline">Explore All Programs →</a>
					</div>
				</div>
				
			<?php endif; ?>
		</div>
	</section>

	<!-- 3. FINAL CTA -->
	<section class="pathway-final-cta" style="padding: var(--spacing-xxl) 0; border-top: 1px solid var(--color-border-subtle); background: var(--color-bg-alt); text-align: center;">
		<div class="container">
			<h2 style="font-size: 1.75rem; margin-bottom: var(--spacing-sm); color: var(--color-text-graphite);">Didn't find what you were looking for?</h2>
			<p style="color: var(--color-text-secondary); margin-bottom: var(--spacing-lg); max-width: 500px; margin-left: auto; margin-right: auto;">Browse our full catalog of structured AI learning experiences.</p>
			<div style="display: flex; gap: var(--spacing-sm); justify-content: center; flex-wrap: wrap;">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'lohnour_service' ) ); ?>" class="btn btn-primary btn-lg">Explore All Programs</a>
				<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-outline btn-lg">Contact LOHNOUR</a>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
