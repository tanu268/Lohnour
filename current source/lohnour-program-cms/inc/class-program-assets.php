<?php
/**
 * Program Assets and Template Loader
 *
 * @package Lohnour_Program_CMS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lohnour_Program_Assets {

	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_assets' ), 20 );
		add_filter( 'template_include', array( __CLASS__, 'load_program_template' ), 100 );
	}

	/**
	 * Enqueue Admin Scripts & Styles for Program Editor.
	 */
	public static function enqueue_admin_assets( $hook ) {
		global $post_type, $post;

		if ( ! in_array( $hook, array( 'post-new.php', 'post.php' ), true ) ) {
			return;
		}

		if ( 'lohnour_program' !== $post_type ) {
			return;
		}

		// WordPress Media Uploader
		wp_enqueue_media();

		// jQuery UI Sortable for drag-and-drop module ordering
		wp_enqueue_script( 'jquery-ui-sortable' );

		// Admin Styles
		wp_enqueue_style(
			'lohnour-program-admin-css',
			LOHNOUR_PROGRAM_CMS_URI . 'assets/admin/css/admin-program.css',
			array(),
			LOHNOUR_PROGRAM_CMS_VERSION
		);

		// Admin Script
		wp_enqueue_script(
			'lohnour-program-admin-js',
			LOHNOUR_PROGRAM_CMS_URI . 'assets/admin/js/admin-program.js',
			array( 'jquery', 'jquery-ui-sortable' ),
			LOHNOUR_PROGRAM_CMS_VERSION,
			true
		);

		// Localize Script for media upload labels
		wp_localize_script(
			'lohnour-program-admin-js',
			'lohnourProgramAdmin',
			array(
				'chooseImage' => __( 'Choose Image', 'lohnourpro' ),
				'useImage'    => __( 'Use This Image', 'lohnourpro' ),
				'confirmRemoveModule' => __( 'Are you sure you want to remove this module?', 'lohnourpro' ),
				'confirmRemoveFAQ'    => __( 'Are you sure you want to remove this FAQ item?', 'lohnourpro' ),
				'confirmRemoveCard'   => __( 'Are you sure you want to remove this item?', 'lohnourpro' ),
			)
		);
	}

	/**
	 * Enqueue Front-End Assets for Single Program.
	 */
	public static function enqueue_frontend_assets() {
		if ( ! is_singular( 'lohnour_program' ) ) {
			return;
		}

		// Prefer theme asset if it exists, else use plugin fallback
		$theme_css_path = get_stylesheet_directory() . '/assets/css/program-single.css';
		$theme_css_uri  = get_stylesheet_directory_uri() . '/assets/css/program-single.css';
		$theme_js_path  = get_stylesheet_directory() . '/assets/js/program-single.js';
		$theme_js_uri   = get_stylesheet_directory_uri() . '/assets/js/program-single.js';

		if ( file_exists( $theme_css_path ) ) {
			wp_enqueue_style( 'lohnour-program-single', $theme_css_uri, array(), LOHNOUR_PROGRAM_CMS_VERSION );
		} else {
			wp_enqueue_style( 'lohnour-program-single', LOHNOUR_PROGRAM_CMS_URI . 'assets/css/program-single.css', array(), LOHNOUR_PROGRAM_CMS_VERSION );
		}

		if ( file_exists( $theme_js_path ) ) {
			wp_enqueue_script( 'lohnour-program-single', $theme_js_uri, array(), LOHNOUR_PROGRAM_CMS_VERSION, true );
		} else {
			wp_enqueue_script( 'lohnour-program-single', LOHNOUR_PROGRAM_CMS_URI . 'assets/js/program-single.js', array(), LOHNOUR_PROGRAM_CMS_VERSION, true );
		}
	}

	/**
	 * Intercept Template Hierarchy for lohnour_program.
	 */
	public static function load_program_template( $template ) {
		if ( is_singular( 'lohnour_program' ) ) {
			// 1. Check child theme
			$theme_template = locate_template( array( 'single-lohnour_program.php' ) );
			if ( ! empty( $theme_template ) ) {
				return $theme_template;
			}

			// 2. Fallback to plugin template
			$plugin_template = LOHNOUR_PROGRAM_CMS_DIR . 'templates/single-lohnour_program.php';
			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		return $template;
	}
}
