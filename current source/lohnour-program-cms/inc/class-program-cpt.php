<?php
/**
 * Program Custom Post Type Registration
 *
 * @package Lohnour_Program_CMS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lohnour_Program_CPT {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
	}

	public static function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Programs', 'Post type general name', 'lohnourpro' ),
			'singular_name'         => _x( 'Program', 'Post type singular name', 'lohnourpro' ),
			'menu_name'             => _x( 'Programs', 'Admin Menu text', 'lohnourpro' ),
			'name_admin_bar'        => _x( 'Program', 'Add New on Toolbar', 'lohnourpro' ),
			'add_new'               => __( 'Add New', 'lohnourpro' ),
			'add_new_item'          => __( 'Add New Program', 'lohnourpro' ),
			'new_item'              => __( 'New Program', 'lohnourpro' ),
			'edit_item'             => __( 'Edit Program', 'lohnourpro' ),
			'view_item'             => __( 'View Program', 'lohnourpro' ),
			'all_items'             => __( 'All Programs', 'lohnourpro' ),
			'search_items'          => __( 'Search Programs', 'lohnourpro' ),
			'parent_item_colon'     => __( 'Parent Programs:', 'lohnourpro' ),
			'not_found'             => __( 'No programs found.', 'lohnourpro' ),
			'not_found_in_trash'    => __( 'No programs found in Trash.', 'lohnourpro' ),
			'featured_image'        => _x( 'Program Cover Image', 'Overrides the "Featured Image" phrase for this post type.', 'lohnourpro' ),
			'set_featured_image'    => _x( 'Set program cover image', 'Overrides the "Set featured image" phrase for this post type.', 'lohnourpro' ),
			'remove_featured_image' => _x( 'Remove program cover image', 'Overrides the "Remove featured image" phrase for this post type.', 'lohnourpro' ),
			'use_featured_image'    => _x( 'Use as program cover image', 'Overrides the "Use as featured image" phrase for this post type.', 'lohnourpro' ),
			'archives'              => _x( 'Program archives', 'The post type archive label used in nav menus.', 'lohnourpro' ),
			'insert_into_item'      => _x( 'Insert into program', 'Overrides the "Insert into post" phrase.', 'lohnourpro' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this program', 'Overrides the "Uploaded to this post" phrase.', 'lohnourpro' ),
			'filter_items_list'     => _x( 'Filter programs list', 'Screen reader text for the filter links.', 'lohnourpro' ),
			'items_list_navigation' => _x( 'Programs list navigation', 'Screen reader text for the pagination.', 'lohnourpro' ),
			'items_list'            => _x( 'Programs list', 'Screen reader text for the items list.', 'lohnourpro' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => 'coaching',
				'with_front' => false,
			),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 21.5, // Between Workshops (21) and Testimonials (22)
			'menu_icon'          => 'dashicons-awards',
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
		);

		register_post_type( 'lohnour_program', $args );
	}
}
