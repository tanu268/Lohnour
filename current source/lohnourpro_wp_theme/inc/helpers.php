<?php
/**
 * Helper and Utility Functions
 *
 * @package Twenty_Twenty_Five_Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Cleanly output an SVG icon from the theme's asset folder.
 *
 * @param string $icon_name Name of the icon file (excluding extension).
 * @param array  $classes   Optional extra classes.
 * @return string Inline SVG content or empty string on failure.
 */
function lohnour_get_svg( $icon_name, $classes = array() ) {
	$file_path = LOHNOUR_THEME_DIR . '/assets/images/' . $icon_name . '.svg';

	if ( ! file_exists( $file_path ) ) {
		return '';
	}

	$svg = file_get_contents( $file_path );

	if ( ! empty( $classes ) ) {
		$class_string = implode( ' ', array_map( 'sanitize_html_class', $classes ) );
		$svg          = str_replace( '<svg', '<svg class="' . esc_attr( $class_string ) . '"', $svg );
	}

	return $svg;
}

/**
 * Retrieve pathway terms ordered by priority:
 * Corporate (1) > Individual / Working Professional (2) > Student (3) > Faculty (4).
 *
 * @param array $args Optional get_terms arguments.
 * @return array Array of ordered WP_Term objects.
 */
function lohnour_get_ordered_pathways( $args = array() ) {
	$default_args = array(
		'taxonomy'   => 'lohnour_pathway',
		'hide_empty' => false,
	);
	$parsed_args  = wp_parse_args( $args, $default_args );
	$terms        = get_terms( $parsed_args );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return $terms;
	}

	$priority = array(
		'corporate'    => 1,
		'individual'   => 2,
		'professional' => 2,
		'student'      => 3,
		'faculty'      => 4,
		'educator'     => 4,
	);

	usort( $terms, function( $a, $b ) use ( $priority ) {
		$weight_a = 99;
		$weight_b = 99;

		$name_a = strtolower( $a->name . ' ' . $a->slug );
		$name_b = strtolower( $b->name . ' ' . $b->slug );

		foreach ( $priority as $key => $weight ) {
			if ( strpos( $name_a, $key ) !== false ) {
				$weight_a = min( $weight_a, $weight );
			}
			if ( strpos( $name_b, $key ) !== false ) {
				$weight_b = min( $weight_b, $weight );
			}
		}

		if ( $weight_a === $weight_b ) {
			return 0;
		}
		return ( $weight_a < $weight_b ) ? -1 : 1;
	} );

	return $terms;
}
