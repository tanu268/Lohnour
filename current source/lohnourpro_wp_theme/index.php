<?php
/**
 * The main template file
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main py-xl">
	<div class="container">
		<header class="page-header" style="margin-bottom: var(--spacing-lg);">
			<h1 class="page-title section-title headline-lg">Latest Insights</h1>
		</header>

		<div class="archive-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--spacing-md);">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'glass-card hover-lift' ); ?> style="padding: var(--spacing-md);">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="post-thumbnail" style="margin-bottom: var(--spacing-sm); overflow: hidden; border-radius: var(--radius-md);">
								<?php the_post_thumbnail( 'large', array( 'style' => 'width: 100%; height: auto; transition: transform var(--transition-fast);' ) ); ?>
							</div>
						<?php endif; ?>

						<h2 class="entry-title" style="font-size: 1.5rem; margin-bottom: var(--spacing-xs);"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						
						<div class="entry-summary" style="color: var(--color-text-muted); font-size: 0.875rem; margin-bottom: var(--spacing-sm);">
							<?php the_excerpt(); ?>
						</div>

						<a href="<?php the_permalink(); ?>" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.75rem;">Read More</a>
					</article>
					<?php
				endwhile;
			else :
				?>
				<p>No content found.</p>
			<?php
			endif;
			?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
