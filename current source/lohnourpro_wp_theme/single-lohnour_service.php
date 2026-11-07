<?php
/**
 * The template for displaying single Program (Service CPT) details
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			
			// Get parent pathways
			$pathways = get_the_terms( get_the_ID(), 'lohnour_pathway' );
			$pathway_links = array();
			if ( ! empty( $pathways ) && ! is_wp_error( $pathways ) ) {
				foreach ( $pathways as $pw ) {
					$pathway_links[] = '<a href="' . esc_url( get_term_link( $pw ) ) . '" style="color: var(--color-accent); text-decoration: none;">' . esc_html( $pw->name ) . '</a>';
				}
			}
			?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'service-detail-article' ); ?>>
				<?php
				$service_breadcrumbs = array(
					array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
					array( 'label' => __( 'Services', 'twentytwentyfive-child' ), 'url' => home_url( '/services' ) ),
				);
				if ( ! empty( $pathways ) && ! is_wp_error( $pathways ) ) {
					$service_breadcrumbs[] = array( 'label' => $pathways[0]->name, 'url' => get_term_link( $pathways[0] ) );
				}
				$service_breadcrumbs[] = array( 'label' => get_the_title() );

				$card_excerpt = get_post_meta( get_the_ID(), '_lohnour_card_excerpt', true );
				if ( empty( $card_excerpt ) && has_excerpt() ) {
					$card_excerpt = get_the_excerpt();
				}

				get_template_part( 'template-parts/page-header', null, array(
					'title'       => get_the_title(),
					'subtitle'    => $card_excerpt,
					'breadcrumbs' => $service_breadcrumbs,
				) );
				?>

				<!-- Content Grid -->
				<div class="split-layout" style="display: grid; grid-template-columns: 1.6fr 1fr; gap: var(--spacing-xl); align-items: start;">
					
					<!-- Left Main Content Column -->
					<div class="entry-content">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="service-featured-image" style="margin-bottom: var(--spacing-md); border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--color-glass-border);">
								<?php the_post_thumbnail( 'large', array( 'style' => 'width: 100%; height: auto; display: block; object-fit: cover; max-height: 400px;' ) ); ?>
							</div>
						<?php endif; ?>

						<div class="prose-content" style="color: var(--color-text-muted); line-height: 1.7; font-size: 1.1rem; margin-bottom: var(--spacing-lg);">
							<?php the_content(); ?>
						</div>

						<?php
						$benefits_meta = get_post_meta( get_the_ID(), '_lohnour_sidebar_benefits', true );
						
						$benefits = array();
						if ( ! empty( $benefits_meta ) ) {
							$benefits = array_map( 'trim', explode( "\n", str_replace( "\r", "", $benefits_meta ) ) );
							$benefits = array_filter( $benefits );
						}
						if (empty($benefits)) {
							$benefits = array(
								'Direct Trainer Access',
								'Staggered Real-World Projects',
								'Access to Source Code Templates'
							);
						}
						?>
					</div>

					<!-- Right Sticky Sidebar Card -->
					<aside class="service-sidebar-card glass-card" style="position: sticky; top: 100px; padding: var(--spacing-lg); border-radius: var(--radius-md);">
						<h3 class="headline-xs" style="margin-bottom: var(--spacing-sm); color: var(--color-accent);">Get Started</h3>
						<p style="font-size: 0.9rem; color: var(--color-text-muted); margin-bottom: var(--spacing-md);">
							Enroll in this program to build practical competency through project-based learning.
						</p>

						<ul class="sidebar-features-list" style="list-style: none; padding: 0; margin: 0 0 var(--spacing-lg) 0; font-size: 0.875rem; color: var(--color-text-muted);">
							<?php foreach ( $benefits as $benefit ) : ?>
								<li style="margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
									<span style="color: var(--color-accent);">✓</span> <?php echo esc_html( $benefit ); ?>
								</li>
							<?php endforeach; ?>
						</ul>

						<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary" style="display: block; text-align: center; width: 100%; box-sizing: border-box;">Inquire About Program</a>
					</aside>

				</div>
			</article>
			
		<?php
		endwhile;
		?>
	</div>
</main>

<?php get_footer(); ?>
