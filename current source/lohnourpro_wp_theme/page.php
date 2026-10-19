<?php
/**
 * The template for displaying all pages
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php
				get_template_part( 'template-parts/page-header', null, array(
					'title'       => get_the_title(),
					'subtitle'    => has_excerpt() ? get_the_excerpt() : '',
					'breadcrumbs' => array(
						array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
						array( 'label' => get_the_title() ),
					),
				) );
				?>

				<div class="entry-content" style="color: var(--color-text-muted); font-size: 1.125rem; line-height: 1.8;">
					<?php the_content(); ?>
				</div><!-- .entry-content -->
			</article><!-- #post-<?php the_ID(); ?> -->
			<?php
		endwhile; // End of the loop.
		?>
	</div>
</main>

<?php get_footer(); ?>
