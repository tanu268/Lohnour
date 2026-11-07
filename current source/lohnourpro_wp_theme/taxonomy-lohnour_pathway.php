<?php
/**
 * The template for displaying a single Pathway taxonomy term archive (lists Programs inside it)
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header();

$current_term = get_queried_object();
$title = $current_term->name;
$description = $current_term->description;

// Resolve badge/audience
$badge = 'Pathway';
if ( stripos( $title, 'Corporate' ) !== false ) {
	$badge = 'Teams';
} elseif ( stripos( $title, 'Professional' ) !== false ) {
	$badge = 'Individuals';
} elseif ( stripos( $title, 'Student' ) !== false ) {
	$badge = 'Students';
} elseif ( stripos( $title, 'Faculty' ) !== false ) {
	$badge = 'Educators';
}
?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">
	<div class="container">
		<?php
		get_template_part( 'template-parts/page-header', null, array(
			'title'       => $title,
			'subtitle'    => $description,
			'breadcrumbs' => array(
				array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
				array( 'label' => __( 'Services', 'twentytwentyfive-child' ), 'url' => home_url( '/services' ) ),
				array( 'label' => $badge . ' Pathway' ),
			),
		) );
		?>

		<section class="pathway-section">
			<div class="programs-grid">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
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
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
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
								<span class="btn btn-outline" style="align-self: flex-start; padding: 0.5rem 1rem; font-size: 0.75rem;">View Program Details →</span>
							</div>
						</a>
						
					<?php
					endwhile;
				else :
					?>
					<p style="text-align: center; color: var(--color-text-muted); grid-column: 1 / -1;">No programs currently available in this pathway.</p>
				<?php
				endif;
				?>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>
