<?php
/**
 * The template for displaying programs archive
 * Phase 4 - Forensic Implementation of Programs Catalog
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); 

// Fetch taxonomies for the filter UI
$pathways = get_terms( array( 'taxonomy' => 'lohnour_pathway', 'hide_empty' => true ) );
$industries = get_terms( array( 'taxonomy' => 'lohnour_industry', 'hide_empty' => true ) );

// Form state
$search_query = get_search_query();
$current_pathway = isset( $_GET['lohnour_pathway'] ) ? sanitize_text_field( $_GET['lohnour_pathway'] ) : '';
$current_industry = isset( $_GET['lohnour_industry'] ) ? sanitize_text_field( $_GET['lohnour_industry'] ) : '';
?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance);">
	
	<!-- PROGRAMS HERO -->
	<section class="page-hero bg-alt" style="padding: var(--spacing-xxl) 0 var(--spacing-xl) 0; border-bottom: 1px solid var(--color-border-subtle);">
		<div class="container text-center">
			<div style="margin-bottom: var(--spacing-sm);">
				<span class="badge" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-text-muted); background: transparent; border: 1px solid var(--color-border); padding: 4px 12px; border-radius: var(--radius-full);">Programs</span>
			</div>
			<h1 class="headline-lg" style="margin-bottom: var(--spacing-md); font-family: var(--font-heading);">Learn AI. Build practical capability.</h1>
			<p class="section-subtitle" style="max-width: 600px; margin: 0 auto; color: var(--color-text-secondary); font-size: 1.125rem;">Structured AI learning experiences for professionals, students, faculty, and organizations.</p>
		</div>
	</section>

	<section class="catalog-section" style="padding: var(--spacing-xl) 0; background: var(--color-bg);">
		<div class="container">
			
			<!-- SEARCH / DISCOVERY -->
			<div class="catalog-controls" style="margin-bottom: var(--spacing-xl);">
				<form method="GET" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="catalog-filter-form">
					<input type="hidden" name="post_type" value="lohnour_service">
					
					<div class="search-primitive" style="max-width: 400px; margin-bottom: var(--spacing-md);">
						<svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
						<input type="text" name="s" class="form-control" placeholder="Search programs..." value="<?php echo esc_attr( $search_query ); ?>" style="width: 100%; border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 12px 16px 12px 40px; background: var(--color-bg-surface); color: var(--color-text); font-family: var(--font-base);">
					</div>
					
					<!-- FILTERS -->
					<?php if ( ! empty( $pathways ) && ! is_wp_error( $pathways ) ) : ?>
					<div class="filter-group" style="display: flex; gap: var(--spacing-sm); flex-wrap: wrap; align-items: center; margin-bottom: var(--spacing-sm);">
						<span style="font-size: 0.875rem; color: var(--color-text-muted); font-weight: 500; margin-right: 8px;">Audience:</span>
						<button type="submit" name="lohnour_pathway" value="" class="filter-btn <?php echo empty( $current_pathway ) ? 'is-active' : ''; ?>">All</button>
						<?php foreach ( $pathways as $pathway ) : ?>
							<button type="submit" name="lohnour_pathway" value="<?php echo esc_attr( $pathway->slug ); ?>" class="filter-btn <?php echo ( $current_pathway === $pathway->slug ) ? 'is-active' : ''; ?>">
								<?php echo esc_html( $pathway->name ); ?>
							</button>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $industries ) && ! is_wp_error( $industries ) ) : ?>
					<div class="filter-group" style="display: flex; gap: var(--spacing-sm); flex-wrap: wrap; align-items: center;">
						<span style="font-size: 0.875rem; color: var(--color-text-muted); font-weight: 500; margin-right: 8px;">Topic:</span>
						<button type="submit" name="lohnour_industry" value="" class="filter-btn <?php echo empty( $current_industry ) ? 'is-active' : ''; ?>">All</button>
						<?php foreach ( $industries as $industry ) : ?>
							<button type="submit" name="lohnour_industry" value="<?php echo esc_attr( $industry->slug ); ?>" class="filter-btn <?php echo ( $current_industry === $industry->slug ) ? 'is-active' : ''; ?>">
								<?php echo esc_html( $industry->name ); ?>
							</button>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

					<!-- Retain search term if filters are clicked -->
					<noscript>
						<button type="submit" class="btn btn-primary btn-sm" style="margin-top: 10px;">Apply Filters</button>
					</noscript>
				</form>
			</div>

			<!-- PROGRAM RESULTS -->
			<div class="results-meta" style="margin-bottom: var(--spacing-md); color: var(--color-text-secondary); font-size: 0.95rem; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: var(--spacing-sm);">
				 <?php
				 global $wp_query;
				 $total_results = $wp_query->found_posts;
				 echo sprintf( esc_html( _n( '%s program found', '%s programs found', $total_results, 'twentytwentyfive-child' ) ), number_format_i18n( $total_results ) );
				 ?>
			</div>

			<!-- PROGRAM CARDS -->
			<?php if ( have_posts() ) : ?>
				<div class="programs-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: var(--spacing-lg);">
					<?php while ( have_posts() ) : the_post(); ?>
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
									$p_terms = get_the_terms( get_the_ID(), 'lohnour_pathway' );
									if ( $p_terms && ! is_wp_error( $p_terms ) ) {
										echo '<span class="badge" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-accent);">' . esc_html( $p_terms[0]->name ) . '</span>';
									} else {
										echo '<span class="badge" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-accent);">Program</span>';
									}
									?>
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
				
				<!-- PAGINATION -->
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
				<!-- EMPTY / LOADING STATES -->
				<div class="state-container" style="text-align: center; padding: var(--spacing-xxl) var(--spacing-md); background: var(--color-bg-surface); border: 1px dashed var(--color-border); border-radius: var(--radius-lg);">
					<svg class="state-icon" style="margin: 0 auto var(--spacing-md); color: var(--color-text-muted); width: 48px; height: 48px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					<h3 class="state-title" style="font-size: 1.25rem; margin-bottom: 8px; color: var(--color-text-graphite);">No programs found</h3>
					<p class="state-desc" style="color: var(--color-text-secondary); margin-bottom: var(--spacing-md);">Try adjusting your search or filters.</p>
					<div>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'lohnour_service' ) ); ?>" class="btn btn-outline">Clear Filters</a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
