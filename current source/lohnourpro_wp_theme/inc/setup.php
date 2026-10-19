<?php
/**
 * Theme Setup and Assets Enqueuing
 *
 * @package Twenty_Twenty_Five_Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue stylesheets and scripts.
 */
function twentytwentyfive_child_enqueue_assets() {
	// Enqueue parent theme styles.
	wp_enqueue_style(
		'twentytwentyfive-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'twentytwentyfive' )->get( 'Version' )
	);

	// Enqueue child theme styles.
	wp_enqueue_style(
		'twentytwentyfive-child-style',
		get_stylesheet_uri(),
		array( 'twentytwentyfive-parent-style' ),
		LOHNOUR_THEME_VERSION
	);

	// Enqueue modular theme.css (which imports base, layout, components, utilities, and pages CSS).
	if ( file_exists( LOHNOUR_THEME_DIR . '/assets/css/theme.css' ) ) {
		wp_enqueue_style(
			'lohnour-theme-style',
			LOHNOUR_THEME_URI . '/assets/css/theme.css',
			array( 'twentytwentyfive-child-style' ),
			LOHNOUR_THEME_VERSION
		);
	}

	// Enqueue core JS.
	if ( file_exists( LOHNOUR_THEME_DIR . '/assets/js/main.js' ) ) {
		wp_enqueue_script(
			'lohnour-main-script',
			LOHNOUR_THEME_URI . '/assets/js/main.js',
			array(),
			LOHNOUR_THEME_VERSION,
			true
		);
	}

	// Enqueue navigation JS.
	if ( file_exists( LOHNOUR_THEME_DIR . '/assets/js/navigation.js' ) ) {
		wp_enqueue_script(
			'lohnour-navigation-script',
			LOHNOUR_THEME_URI . '/assets/js/navigation.js',
			array( 'lohnour-main-script' ),
			LOHNOUR_THEME_VERSION,
			true
		);
	}

	// Enqueue animations JS.
	if ( file_exists( LOHNOUR_THEME_DIR . '/assets/js/animations.js' ) ) {
		wp_enqueue_script(
			'lohnour-animations-script',
			LOHNOUR_THEME_URI . '/assets/js/animations.js',
			array( 'lohnour-main-script' ),
			LOHNOUR_THEME_VERSION,
			true
		);
	}

	// Enqueue core accordion block script module early if post contains the accordion block,
	// to ensure the Interactivity API import map is printed correctly in wp_head on classic templates.
	if ( has_block( 'core/accordion' ) || has_block( 'core/accordion-item' ) ) {
		wp_enqueue_script_module( '@wordpress/block-library/accordion/view' );
	}

	// Enqueue guides CPT CSS and JS.
	if ( ( is_singular( 'user_guide' ) || is_post_type_archive( 'user_guide' ) || is_tax( 'guide_sector' ) ) ) {
		if ( file_exists( LOHNOUR_THEME_DIR . '/assets/css/guides.css' ) ) {
			wp_enqueue_style(
				'lohnour-guides-style',
				LOHNOUR_THEME_URI . '/assets/css/guides.css',
				array( 'lohnour-theme-style' ),
				LOHNOUR_THEME_VERSION
			);
		}
		if ( file_exists( LOHNOUR_THEME_DIR . '/assets/js/guides.js' ) ) {
			wp_enqueue_script(
				'lohnour-guides-script',
				LOHNOUR_THEME_URI . '/assets/js/guides.js',
				array( 'lohnour-main-script' ),
				LOHNOUR_THEME_VERSION,
				true
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_child_enqueue_assets' );

/**
 * Register theme features, navigation menus, and custom block pattern categories.
 */
function twentytwentyfive_child_setup() {
	// Add standard WordPress theme supports.
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'automatic-feed-links' );

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'twentytwentyfive-child' ),
			'footer'  => __( 'Footer Navigation', 'twentytwentyfive-child' ),
			'social'  => __( 'Social Links', 'twentytwentyfive-child' ),
		)
	);

	// Register a pattern category for custom layout patterns.
	register_block_pattern_category(
		'lohnour-pro',
		array( 'label' => __( 'Lohnour Pro Sections', 'twentytwentyfive-child' ) )
	);
}
add_action( 'after_setup_theme', 'twentytwentyfive_child_setup' );

/**
 * Register widget areas.
 */
function twentytwentyfive_child_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer Newsletter', 'twentytwentyfive-child' ),
			'id'            => 'footer-newsletter',
			'description'   => __( 'Add your Brevo or Newsletter widget here.', 'twentytwentyfive-child' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s footer-newsletter-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-heading">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar Newsletter', 'twentytwentyfive-child' ),
			'id'            => 'blog-sidebar-newsletter',
			'description'   => __( 'Add your Brevo or Newsletter widget here for the blog sidebar.', 'twentytwentyfive-child' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s blog-newsletter-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title-classic" style="display:none;">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'twentytwentyfive_child_widgets_init' );
