<?php
/**
 * Plugin Name: Lohnour Program CMS
 * Plugin URI: https://pro.lohnour.com/
 * Description: Dedicated, reusable Content Management System for Lohnour Coaching Programs.
 * Version: 1.0.0
 * Author: Lohnour Architecture Team
 * Text Domain: lohnourpro
 *
 * @package Lohnour_Program_CMS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'LOHNOUR_PROGRAM_CMS_VERSION', '1.0.0' );
define( 'LOHNOUR_PROGRAM_CMS_DIR', plugin_dir_path( __FILE__ ) );
define( 'LOHNOUR_PROGRAM_CMS_URI', plugin_dir_url( __FILE__ ) );

// Load Core Components
require_once LOHNOUR_PROGRAM_CMS_DIR . 'inc/class-program-cpt.php';
require_once LOHNOUR_PROGRAM_CMS_DIR . 'inc/class-program-meta-boxes.php';
require_once LOHNOUR_PROGRAM_CMS_DIR . 'inc/class-program-assets.php';
require_once LOHNOUR_PROGRAM_CMS_DIR . 'inc/class-program-renderer.php';

/**
 * Initialize Plugin.
 */
function lohnour_program_cms_init() {
	Lohnour_Program_CPT::init();
	Lohnour_Program_Meta_Boxes::init();
	Lohnour_Program_Assets::init();
}
add_action( 'plugins_loaded', 'lohnour_program_cms_init' );

/**
 * Activation Hook: Register CPT & Flush Rewrite Rules.
 */
register_activation_hook( __FILE__, function() {
	Lohnour_Program_CPT::register_post_type();
	flush_rewrite_rules( false );
} );

/**
 * Deactivation Hook: Flush Rewrite Rules.
 */
register_deactivation_hook( __FILE__, function() {
	flush_rewrite_rules( false );
} );
