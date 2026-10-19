<?php
/**
 * Template Part: Page Header Component
 *
 * Renders the standardized hero/header section for pages and archives.
 * Structure:
 *   1. Page Title (H1)
 *   2. Subtitle / Description
 *   3. Dynamic Breadcrumbs Chip (.hero-badge)
 *
 * @package Twenty_Twenty_Five_Child
 * @since 1.0.0
 *
 * Expected $args:
 *   - 'title'       (string) The main heading text.
 *   - 'subtitle'    (string) The descriptive text below the heading.
 *   - 'breadcrumbs' (array)  Array of breadcrumb items: [ ['label' => 'Home', 'url' => '...'], ['label' => 'Services'] ]
 *   - 'badge'       (string) Optional raw badge/chip text if breadcrumbs are not supplied.
 *   - 'class'       (string) Optional extra CSS classes for the <header> wrapper.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title       = isset( $args['title'] ) ? $args['title'] : get_the_title();
$subtitle    = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
$breadcrumbs = isset( $args['breadcrumbs'] ) && is_array( $args['breadcrumbs'] ) ? $args['breadcrumbs'] : array();
$badge       = isset( $args['badge'] ) ? $args['badge'] : '';
$class       = isset( $args['class'] ) ? ' ' . esc_attr( $args['class'] ) : '';
?>

<header class="page-header<?php echo $class; ?>">
	<?php if ( ! empty( $title ) ) : ?>
		<h1 class="page-title section-title headline-lg"><?php echo wp_kses_post( $title ); ?></h1>
	<?php endif; ?>

	<?php if ( ! empty( $subtitle ) ) : ?>
		<div class="section-subtitle"><?php echo wp_kses_post( $subtitle ); ?></div>
	<?php endif; ?>

	<?php if ( ! empty( $badge ) ) : ?>
		<div class="hero-badge">
			<span><?php echo esc_html( $badge ); ?></span>
		</div>
	<?php endif; ?>
</header>
