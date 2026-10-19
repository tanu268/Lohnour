<?php
/**
 * Register Custom Taxonomies
 *
 * @package Twenty_Twenty_Five_Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register custom taxonomies.
 */
function lohnour_register_taxonomies() {

	// 1. Industries (e.g., Finance, Healthcare, Tech)
	$industry_labels = array(
		'name'              => _x( 'Industries', 'taxonomy general name', 'twentytwentyfive-child' ),
		'singular_name'     => _x( 'Industry', 'taxonomy singular name', 'twentytwentyfive-child' ),
		'search_items'      => __( 'Search Industries', 'twentytwentyfive-child' ),
		'all_items'         => __( 'All Industries', 'twentytwentyfive-child' ),
		'parent_item'       => __( 'Parent Industry', 'twentytwentyfive-child' ),
		'parent_item_colon' => __( 'Parent Industry:', 'twentytwentyfive-child' ),
		'edit_item'         => __( 'Edit Industry', 'twentytwentyfive-child' ),
		'update_item'       => __( 'Update Industry', 'twentytwentyfive-child' ),
		'add_new_item'      => __( 'Add New Industry', 'twentytwentyfive-child' ),
		'new_item_name'     => __( 'New Industry Name', 'twentytwentyfive-child' ),
		'menu_name'         => __( 'Industries', 'twentytwentyfive-child' ),
	);

	$industry_args = array(
		'hierarchical'      => true, // behaves like categories
		'labels'            => $industry_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'industries' ),
		'show_in_rest'      => true, // Enables in Block Editor
	);
	// Associate with Services, Case Studies, and Workshops
	register_taxonomy( 'lohnour_industry', array( 'lohnour_service', 'lohnour_case_study', 'lohnour_workshop' ), $industry_args );

	// 2. Audience (e.g., Working Professionals, HR, Students, Executives)
	$audience_labels = array(
		'name'              => _x( 'Audiences', 'taxonomy general name', 'twentytwentyfive-child' ),
		'singular_name'     => _x( 'Audience', 'taxonomy singular name', 'twentytwentyfive-child' ),
		'search_items'      => __( 'Search Audiences', 'twentytwentyfive-child' ),
		'all_items'         => __( 'All Audiences', 'twentytwentyfive-child' ),
		'parent_item'       => __( 'Parent Audience', 'twentytwentyfive-child' ),
		'parent_item_colon' => __( 'Parent Audience:', 'twentytwentyfive-child' ),
		'edit_item'         => __( 'Edit Audience', 'twentytwentyfive-child' ),
		'update_item'       => __( 'Update Audience', 'twentytwentyfive-child' ),
		'add_new_item'      => __( 'Add New Audience', 'twentytwentyfive-child' ),
		'new_item_name'     => __( 'New Audience Name', 'twentytwentyfive-child' ),
		'menu_name'         => __( 'Audiences', 'twentytwentyfive-child' ),
	);

	$audience_args = array(
		'hierarchical'      => true,
		'labels'            => $audience_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'audience' ),
		'show_in_rest'      => true,
	);
	// Associate with Services and Workshops
	register_taxonomy( 'lohnour_audience', array( 'lohnour_service', 'lohnour_workshop' ), $audience_args );

	// 3. Skill Level (e.g., Beginner, Intermediate, Advanced)
	$skill_labels = array(
		'name'              => _x( 'Skill Levels', 'taxonomy general name', 'twentytwentyfive-child' ),
		'singular_name'     => _x( 'Skill Level', 'taxonomy singular name', 'twentytwentyfive-child' ),
		'search_items'      => __( 'Search Skill Levels', 'twentytwentyfive-child' ),
		'all_items'         => __( 'All Skill Levels', 'twentytwentyfive-child' ),
		'parent_item'       => __( 'Parent Skill Level', 'twentytwentyfive-child' ),
		'parent_item_colon' => __( 'Parent Skill Level:', 'twentytwentyfive-child' ),
		'edit_item'         => __( 'Edit Skill Level', 'twentytwentyfive-child' ),
		'update_item'       => __( 'Update Skill Level', 'twentytwentyfive-child' ),
		'add_new_item'      => __( 'Add New Skill Level', 'twentytwentyfive-child' ),
		'new_item_name'     => __( 'New Skill Level Name', 'twentytwentyfive-child' ),
		'menu_name'         => __( 'Skill Levels', 'twentytwentyfive-child' ),
	);

	$skill_args = array(
		'hierarchical'      => true,
		'labels'            => $skill_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'skill-level' ),
		'show_in_rest'      => true,
	);
	// Associate with Workshops
	register_taxonomy( 'lohnour_skill_level', array( 'lohnour_workshop' ), $skill_args );

	// 4. Pathways (e.g., Corporate AI Training, Professional AI Upskilling)
	$pathway_labels = array(
		'name'              => _x( 'Pathways', 'taxonomy general name', 'twentytwentyfive-child' ),
		'singular_name'     => _x( 'Pathway', 'taxonomy singular name', 'twentytwentyfive-child' ),
		'search_items'      => __( 'Search Pathways', 'twentytwentyfive-child' ),
		'all_items'         => __( 'All Pathways', 'twentytwentyfive-child' ),
		'parent_item'       => __( 'Parent Pathway', 'twentytwentyfive-child' ),
		'parent_item_colon' => __( 'Parent Pathway:', 'twentytwentyfive-child' ),
		'edit_item'         => __( 'Edit Pathway', 'twentytwentyfive-child' ),
		'update_item'       => __( 'Update Pathway', 'twentytwentyfive-child' ),
		'add_new_item'      => __( 'Add New Pathway', 'twentytwentyfive-child' ),
		'new_item_name'     => __( 'New Pathway Name', 'twentytwentyfive-child' ),
		'menu_name'         => __( 'Pathways', 'twentytwentyfive-child' ),
	);

	$pathway_args = array(
		'hierarchical'      => true,
		'labels'            => $pathway_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'programs/pathway' ),
		'show_in_rest'      => true,
	);
	register_taxonomy( 'lohnour_pathway', array( 'lohnour_service' ), $pathway_args );

	// 5. Sectors (for User Guides)
	$sector_labels = array(
		'name'              => _x( 'Sectors', 'taxonomy general name', 'twentytwentyfive-child' ),
		'singular_name'     => _x( 'Sector', 'taxonomy singular name', 'twentytwentyfive-child' ),
		'search_items'      => __( 'Search Sectors', 'twentytwentyfive-child' ),
		'all_items'         => __( 'All Sectors', 'twentytwentyfive-child' ),
		'parent_item'       => __( 'Parent Sector', 'twentytwentyfive-child' ),
		'parent_item_colon' => __( 'Parent Sector:', 'twentytwentyfive-child' ),
		'edit_item'         => __( 'Edit Sector', 'twentytwentyfive-child' ),
		'update_item'       => __( 'Update Sector', 'twentytwentyfive-child' ),
		'add_new_item'      => __( 'Add New Sector', 'twentytwentyfive-child' ),
		'new_item_name'     => __( 'New Sector Name', 'twentytwentyfive-child' ),
		'menu_name'         => __( 'Sectors', 'twentytwentyfive-child' ),
	);

	$sector_args = array(
		'hierarchical'      => true,
		'labels'            => $sector_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'sector' ),
		'show_in_rest'      => true,
	);
	register_taxonomy( 'guide_sector', array( 'user_guide' ), $sector_args );
	// 6. Tags (for User Guides)
	$guide_tag_labels = array(
		'name'              => _x( 'Guide Tags', 'taxonomy general name', 'twentytwentyfive-child' ),
		'singular_name'     => _x( 'Guide Tag', 'taxonomy singular name', 'twentytwentyfive-child' ),
		'search_items'      => __( 'Search Guide Tags', 'twentytwentyfive-child' ),
		'all_items'         => __( 'All Guide Tags', 'twentytwentyfive-child' ),
		'edit_item'         => __( 'Edit Guide Tag', 'twentytwentyfive-child' ),
		'update_item'       => __( 'Update Guide Tag', 'twentytwentyfive-child' ),
		'add_new_item'      => __( 'Add New Guide Tag', 'twentytwentyfive-child' ),
		'new_item_name'     => __( 'New Guide Tag Name', 'twentytwentyfive-child' ),
		'menu_name'         => __( 'Guide Tags', 'twentytwentyfive-child' ),
	);

	$guide_tag_args = array(
		'hierarchical'      => false, // Tags are not hierarchical
		'labels'            => $guide_tag_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'guide-tag' ),
		'show_in_rest'      => true,
	);
	register_taxonomy( 'guide_tags', array( 'user_guide' ), $guide_tag_args );
}
add_action( 'init', 'lohnour_register_taxonomies', 9 );
