<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
define('DB_HOST', '127.0.0.1:3307');

$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once 'D:/xampp/htdocs/lohnour_old/wordpress/wp-load.php';

$routes = [
    '/services/',
    '/services/corporate/',
    '/coaching/',
    '/coaching/individual/',
    '/coaching/group/',
    '/about/',
    '/contact/',
    '/guides/',
    '/blog/'
];

printf("%-25s | %-12s | %-18s | %-25s | %s\n", "Route", "Is 404?", "Queried Object", "Template File", "Summary");
echo str_repeat("-", 110) . "\n";

foreach ($routes as $route) {
    global $wp, $wp_query, $wp_the_query;
    
    $_SERVER['REQUEST_URI'] = '/lohnour_old/wordpress' . $route;
    
    // Simulate real request lifecycle
    $wp->query_vars = [];
    $wp->parse_request();
    $wp->query_posts();
    
    $is_404 = $wp_query->is_404();
    $obj = get_queried_object();
    
    $obj_desc = 'NULL';
    if ($obj instanceof WP_Post) {
        $obj_desc = "Post ID={$obj->ID} ('{$obj->post_name}')";
    } elseif ($obj instanceof WP_Post_Type) {
        $obj_desc = "CPT: {$obj->name}";
    } elseif ($obj instanceof WP_Term) {
        $obj_desc = "Term: {$obj->slug}";
    }
    
    // Resolve template file using template-loader logic
    $template = '';
    if ( $wp_query->is_404() && $template = get_404_template() ) :
    elseif ( $wp_query->is_search() && $template = get_search_template() ) :
    elseif ( $wp_query->is_front_page() && $template = get_front_page_template() ) :
    elseif ( $wp_query->is_home() && $template = get_home_template() ) :
    elseif ( $wp_query->is_post_type_archive() && $template = get_post_type_archive_template() ) :
    elseif ( $wp_query->is_tax() && $template = get_taxonomy_template() ) :
    elseif ( $wp_query->is_attachment() && $template = get_attachment_template() ) :
    elseif ( $wp_query->is_single() && $template = get_single_template() ) :
    elseif ( $wp_query->is_page() && $template = get_page_template() ) :
    elseif ( $wp_query->is_category() && $template = get_category_template() ) :
    elseif ( $wp_query->is_tag() && $template = get_tag_template() ) :
    elseif ( $wp_query->is_author() && $template = get_author_template() ) :
    elseif ( $wp_query->is_date() && $template = get_date_template() ) :
    elseif ( $wp_query->is_archive() && $template = get_archive_template() ) :
    else :
        $template = get_index_template();
    endif;
    
    $tpl_name = $template ? basename($template) : 'NONE';
    
    printf("%-25s | %-12s | %-18s | %-25s | %s\n",
        $route,
        $is_404 ? 'YES (404)' : 'NO (200)',
        $obj_desc,
        $tpl_name,
        $is_404 ? 'Falls back to 404 (No DB match)' : 'Matches valid query'
    );
}
