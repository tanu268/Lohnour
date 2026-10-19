<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-xl);">
	<div class="container text-center">
		<h1 class="headline-lg" style="margin-bottom: var(--spacing-sm);">404</h1>
		<p class="section-subtitle" style="margin-bottom: var(--spacing-lg);">The page you are looking for does not exist or has been moved.</p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary btn-lg">Return Home</a>
	</div>
</main>

<?php get_footer(); ?>
