<?php
/**
 * Register Custom Post Types
 *
 * @package Twenty_Twenty_Five_Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register all theme custom post types.
 */
function lohnour_register_post_types() {

	// 1. Services
	$service_labels = array(
		'name'               => _x( 'Services', 'post type general name', 'twentytwentyfive-child' ),
		'singular_name'      => _x( 'Service', 'post type singular name', 'twentytwentyfive-child' ),
		'menu_name'          => _x( 'Services', 'admin menu', 'twentytwentyfive-child' ),
		'name_admin_bar'     => _x( 'Service', 'add new on admin bar', 'twentytwentyfive-child' ),
		'add_new'            => _x( 'Add New', 'service', 'twentytwentyfive-child' ),
		'add_new_item'       => __( 'Add New Service', 'twentytwentyfive-child' ),
		'new_item'           => __( 'New Service', 'twentytwentyfive-child' ),
		'edit_item'          => __( 'Edit Service', 'twentytwentyfive-child' ),
		'view_item'          => __( 'View Service', 'twentytwentyfive-child' ),
		'all_items'          => __( 'All Services', 'twentytwentyfive-child' ),
		'search_items'       => __( 'Search Services', 'twentytwentyfive-child' ),
		'not_found'          => __( 'No services found.', 'twentytwentyfive-child' ),
		'not_found_in_trash' => __( 'No services found in Trash.', 'twentytwentyfive-child' ),
	);

	$service_args = array(
		'labels'             => $service_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'programs', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-star-filled',
		'show_in_rest'       => true, // Enables Gutenberg Block Editor
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
	);
	register_post_type( 'lohnour_service', $service_args );

	// 2. Workshops
	$workshop_labels = array(
		'name'               => _x( 'Workshops', 'post type general name', 'twentytwentyfive-child' ),
		'singular_name'      => _x( 'Workshop', 'post type singular name', 'twentytwentyfive-child' ),
		'menu_name'          => _x( 'Workshops', 'admin menu', 'twentytwentyfive-child' ),
		'name_admin_bar'     => _x( 'Workshop', 'add new on admin bar', 'twentytwentyfive-child' ),
		'add_new'            => _x( 'Add New', 'workshop', 'twentytwentyfive-child' ),
		'add_new_item'       => __( 'Add New Workshop', 'twentytwentyfive-child' ),
		'new_item'           => __( 'New Workshop', 'twentytwentyfive-child' ),
		'edit_item'          => __( 'Edit Workshop', 'twentytwentyfive-child' ),
		'view_item'          => __( 'View Workshop', 'twentytwentyfive-child' ),
		'all_items'          => __( 'All Workshops', 'twentytwentyfive-child' ),
		'search_items'       => __( 'Search Workshops', 'twentytwentyfive-child' ),
		'not_found'          => __( 'No workshops found.', 'twentytwentyfive-child' ),
		'not_found_in_trash' => __( 'No workshops found in Trash.', 'twentytwentyfive-child' ),
	);

	$workshop_args = array(
		'labels'             => $workshop_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'workshops', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 21,
		'menu_icon'          => 'dashicons-welcome-learn-more',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
	);
	register_post_type( 'lohnour_workshop', $workshop_args );

	// 3. Testimonials (No single detail pages needed)
	$testimonial_labels = array(
		'name'               => _x( 'Testimonials', 'post type general name', 'twentytwentyfive-child' ),
		'singular_name'      => _x( 'Testimonial', 'post type singular name', 'twentytwentyfive-child' ),
		'menu_name'          => _x( 'Testimonials', 'admin menu', 'twentytwentyfive-child' ),
		'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'twentytwentyfive-child' ),
		'add_new'            => _x( 'Add New', 'testimonial', 'twentytwentyfive-child' ),
		'add_new_item'       => __( 'Add New Testimonial', 'twentytwentyfive-child' ),
		'new_item'           => __( 'New Testimonial', 'twentytwentyfive-child' ),
		'edit_item'          => __( 'Edit Testimonial', 'twentytwentyfive-child' ),
		'view_item'          => __( 'View Testimonial', 'twentytwentyfive-child' ),
		'all_items'          => __( 'All Testimonials', 'twentytwentyfive-child' ),
		'search_items'       => __( 'Search Testimonials', 'twentytwentyfive-child' ),
		'not_found'          => __( 'No testimonials found.', 'twentytwentyfive-child' ),
		'not_found_in_trash' => __( 'No testimonials found in Trash.', 'twentytwentyfive-child' ),
	);

	$testimonial_args = array(
		'labels'             => $testimonial_labels,
		'public'             => true,
		'publicly_queryable' => false, // Prevents individual URL access
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 22,
		'menu_icon'          => 'dashicons-testimonial',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
	);
	register_post_type( 'lohnour_testimonial', $testimonial_args );

	// 5. Case Studies
	$case_labels = array(
		'name'               => _x( 'Case Studies', 'post type general name', 'twentytwentyfive-child' ),
		'singular_name'      => _x( 'Case Study', 'post type singular name', 'twentytwentyfive-child' ),
		'menu_name'          => _x( 'Case Studies', 'admin menu', 'twentytwentyfive-child' ),
		'name_admin_bar'     => _x( 'Case Study', 'add new on admin bar', 'twentytwentyfive-child' ),
		'add_new'            => _x( 'Add New', 'case-study', 'twentytwentyfive-child' ),
		'add_new_item'       => __( 'Add New Case Study', 'twentytwentyfive-child' ),
		'new_item'           => __( 'New Case Study', 'twentytwentyfive-child' ),
		'edit_item'          => __( 'Edit Case Study', 'twentytwentyfive-child' ),
		'view_item'          => __( 'View Case Study', 'twentytwentyfive-child' ),
		'all_items'          => __( 'All Case Studies', 'twentytwentyfive-child' ),
		'search_items'       => __( 'Search Case Studies', 'twentytwentyfive-child' ),
		'not_found'          => __( 'No case studies found.', 'twentytwentyfive-child' ),
		'not_found_in_trash' => __( 'No case studies found in Trash.', 'twentytwentyfive-child' ),
	);

	$case_args = array(
		'labels'             => $case_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'case-studies', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 24,
		'menu_icon'          => 'dashicons-portfolio',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
	);
	register_post_type( 'lohnour_case_study', $case_args );

	// 6. User Guides
	$guide_labels = array(
		'name'               => _x( 'User Guides', 'post type general name', 'twentytwentyfive-child' ),
		'singular_name'      => _x( 'User Guide', 'post type singular name', 'twentytwentyfive-child' ),
		'menu_name'          => _x( 'User Guides', 'admin menu', 'twentytwentyfive-child' ),
		'name_admin_bar'     => _x( 'User Guide', 'add new on admin bar', 'twentytwentyfive-child' ),
		'add_new'            => _x( 'Add New', 'user-guide', 'twentytwentyfive-child' ),
		'add_new_item'       => __( 'Add New User Guide', 'twentytwentyfive-child' ),
		'new_item'           => __( 'New User Guide', 'twentytwentyfive-child' ),
		'edit_item'          => __( 'Edit User Guide', 'twentytwentyfive-child' ),
		'view_item'          => __( 'View User Guide', 'twentytwentyfive-child' ),
		'all_items'          => __( 'All User Guides', 'twentytwentyfive-child' ),
		'search_items'       => __( 'Search User Guides', 'twentytwentyfive-child' ),
		'not_found'          => __( 'No guides found.', 'twentytwentyfive-child' ),
		'not_found_in_trash' => __( 'No guides found in Trash.', 'twentytwentyfive-child' ),
	);

	$guide_args = array(
		'labels'             => $guide_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'guides', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true, // Hierarchical for Topics and Lessons
		'menu_position'      => 25,
		'menu_icon'          => 'dashicons-welcome-learn-more',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'author', 'revisions' ),
	);
	register_post_type( 'user_guide', $guide_args );
}
add_action( 'init', 'lohnour_register_post_types' );
