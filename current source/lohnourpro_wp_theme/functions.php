<?php
/**
 * Twenty Twenty-Five Child functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Twenty_Twenty_Five_Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define theme constants.
define( 'LOHNOUR_THEME_VERSION', '1.0.6' );
define( 'LOHNOUR_THEME_DIR', get_stylesheet_directory() );
define( 'LOHNOUR_THEME_URI', get_stylesheet_directory_uri() );

/**
 * Setup and enqueue assets.
 */
require_once LOHNOUR_THEME_DIR . '/inc/setup.php';

/**
 * Custom Post Types.
 */
require_once LOHNOUR_THEME_DIR . '/inc/post-types.php';

/**
 * Custom Taxonomies.
 */
require_once LOHNOUR_THEME_DIR . '/inc/taxonomies.php';

/**
 * Custom Admin Fields for Taxonomies.
 */
require_once LOHNOUR_THEME_DIR . '/inc/admin-fields.php';

/**
 * Custom Admin Fields for CPT Service.
 */
require_once LOHNOUR_THEME_DIR . '/inc/service-meta.php';

/**
 * Featured Post Meta & Admin Quick-Toggle.
 */
require_once LOHNOUR_THEME_DIR . '/inc/post-meta.php';

/**
 * Utility and Helper Functions.
 */
require_once LOHNOUR_THEME_DIR . '/inc/helpers.php';

/**
 * Route block templates to PHP templates in child theme.
 */
add_filter( 'template_include', function( $template ) {
	if ( is_front_page() && file_exists( LOHNOUR_THEME_DIR . '/front-page.php' ) ) {
		return LOHNOUR_THEME_DIR . '/front-page.php';
	}
	// 1. Single Custom Post Types
	if ( is_singular( 'lohnour_service' ) && file_exists( LOHNOUR_THEME_DIR . '/single-lohnour_service.php' ) ) {
		return LOHNOUR_THEME_DIR . '/single-lohnour_service.php';
	}
	if ( is_singular( 'lohnour_workshop' ) && file_exists( LOHNOUR_THEME_DIR . '/single-lohnour_workshop.php' ) ) {
		return LOHNOUR_THEME_DIR . '/single-lohnour_workshop.php';
	}
	if ( is_singular( 'lohnour_case_study' ) && file_exists( LOHNOUR_THEME_DIR . '/single-lohnour_case_study.php' ) ) {
		return LOHNOUR_THEME_DIR . '/single-lohnour_case_study.php';
	}
	if ( is_singular( 'user_guide' ) && file_exists( LOHNOUR_THEME_DIR . '/single-user_guide.php' ) ) {
		return LOHNOUR_THEME_DIR . '/single-user_guide.php';
	}

	// 2. Archive Custom Post Types & Taxonomies
	if ( is_tax( 'lohnour_pathway' ) && file_exists( LOHNOUR_THEME_DIR . '/taxonomy-lohnour_pathway.php' ) ) {
		return LOHNOUR_THEME_DIR . '/taxonomy-lohnour_pathway.php';
	}
	if ( is_tax( 'guide_sector' ) && file_exists( LOHNOUR_THEME_DIR . '/taxonomy-guide_sector.php' ) ) {
		return LOHNOUR_THEME_DIR . '/taxonomy-guide_sector.php';
	}
	if ( is_tax( 'guide_tags' ) && file_exists( LOHNOUR_THEME_DIR . '/taxonomy-guide_tags.php' ) ) {
		return LOHNOUR_THEME_DIR . '/taxonomy-guide_tags.php';
	}
	if ( is_post_type_archive( 'lohnour_service' ) && file_exists( LOHNOUR_THEME_DIR . '/archive-lohnour_service.php' ) ) {
		return LOHNOUR_THEME_DIR . '/archive-lohnour_service.php';
	}
	if ( is_post_type_archive( 'lohnour_workshop' ) && file_exists( LOHNOUR_THEME_DIR . '/archive-lohnour_workshop.php' ) ) {
		return LOHNOUR_THEME_DIR . '/archive-lohnour_workshop.php';
	}
	if ( is_post_type_archive( 'lohnour_case_study' ) && file_exists( LOHNOUR_THEME_DIR . '/archive-lohnour_case_study.php' ) ) {
		return LOHNOUR_THEME_DIR . '/archive-lohnour_case_study.php';
	}
	if ( is_post_type_archive( 'user_guide' ) && file_exists( LOHNOUR_THEME_DIR . '/archive-user_guide.php' ) ) {
		return LOHNOUR_THEME_DIR . '/archive-user_guide.php';
	}

	// 3. Standard Fallbacks & Page Templates
	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( $slug && file_exists( LOHNOUR_THEME_DIR . '/page-' . $slug . '.php' ) ) {
			return LOHNOUR_THEME_DIR . '/page-' . $slug . '.php';
		}
		if ( file_exists( LOHNOUR_THEME_DIR . '/page.php' ) ) {
			return LOHNOUR_THEME_DIR . '/page.php';
		}
	}
	if ( is_single() && file_exists( LOHNOUR_THEME_DIR . '/single.php' ) ) {
		return LOHNOUR_THEME_DIR . '/single.php';
	}
	if ( is_home() && file_exists( LOHNOUR_THEME_DIR . '/home.php' ) ) {
		return LOHNOUR_THEME_DIR . '/home.php';
	}
	if ( is_category() && file_exists( LOHNOUR_THEME_DIR . '/category.php' ) ) {
		return LOHNOUR_THEME_DIR . '/category.php';
	}
	if ( is_tag() && file_exists( LOHNOUR_THEME_DIR . '/tag.php' ) ) {
		return LOHNOUR_THEME_DIR . '/tag.php';
	}
	if ( is_author() && file_exists( LOHNOUR_THEME_DIR . '/author.php' ) ) {
		return LOHNOUR_THEME_DIR . '/author.php';
	}
	if ( ( is_archive() || is_search() ) && file_exists( LOHNOUR_THEME_DIR . '/index.php' ) ) {
		return LOHNOUR_THEME_DIR . '/index.php';
	}
	if ( is_404() && file_exists( LOHNOUR_THEME_DIR . '/404.php' ) ) {
		return LOHNOUR_THEME_DIR . '/404.php';
	}
	return $template;
}, 99 );

/**
 * Contact Form Shortcode & Handler
 */
require_once LOHNOUR_THEME_DIR . '/inc/contact-form.php';
