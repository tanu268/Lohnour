<?php
require_once( 'D:/xampp/htdocs/wordpress/wp-load.php' );

echo "--- CHECKING PROGRAMS PAGE CONFLICT ---\n";
$page = get_page_by_path('programs');
if ($page) {
    echo "CONFLICT FOUND: Page with slug 'programs' exists. ID: " . $page->ID . " Post Type: " . $page->post_type . "\n";
} else {
    echo "No page conflict for 'programs'.\n";
}

echo "\n--- CHECKING SERVICES PAGE CONFLICT ---\n";
$page = get_page_by_path('services');
if ($page) {
    echo "CONFLICT FOUND: Page with slug 'services' exists. ID: " . $page->ID . " Post Type: " . $page->post_type . "\n";
} else {
    echo "No page conflict for 'services'.\n";
}

echo "\n--- CPT REGISTRATION CHECK ---\n";
$cpt = get_post_type_object('lohnour_service');
if ($cpt) {
    echo "CPT 'lohnour_service' found.\n";
    echo "Rewrite Slug: " . (isset($cpt->rewrite['slug']) ? $cpt->rewrite['slug'] : 'N/A') . "\n";
    echo "Has Archive: " . ($cpt->has_archive ? 'Yes (' . (is_string($cpt->has_archive) ? $cpt->has_archive : 'true') . ')' : 'No') . "\n";
}

echo "\n--- TAXONOMY REGISTRATION CHECK ---\n";
$tax = get_taxonomy('lohnour_pathway');
if ($tax) {
    echo "Taxonomy 'lohnour_pathway' found.\n";
    echo "Rewrite Slug: " . (isset($tax->rewrite['slug']) ? $tax->rewrite['slug'] : 'N/A') . "\n";
}

echo "\n--- REWRITE RULES CHECK ---\n";
global $wp_rewrite;
$rules = $wp_rewrite->wp_rewrite_rules();
$found_programs = false;
foreach ($rules as $match => $query) {
    if (strpos($match, 'programs') === 0) {
        echo "Rule: $match => $query\n";
        $found_programs = true;
    }
}
if (!$found_programs) {
    echo "No rewrite rules starting with 'programs' found.\n";
}

echo "\n--- ACTIVE THEME ---\n";
echo "Theme Name: " . wp_get_theme()->get('Name') . "\n";
echo "Stylesheet: " . get_stylesheet() . "\n";
echo "Template: " . get_template() . "\n";

echo "\n--- CHECK EXISTING PROGRAMS FOR TESTING ---\n";
$programs = get_posts(array('post_type' => 'lohnour_service', 'posts_per_page' => 1));
if (!empty($programs)) {
    echo "Test Program Slug: " . $programs[0]->post_name . "\n";
} else {
    echo "No programs found.\n";
}

echo "\n--- CHECK EXISTING PATHWAYS FOR TESTING ---\n";
$pathways = get_terms(array('taxonomy' => 'lohnour_pathway', 'number' => 1, 'hide_empty' => false));
if (!empty($pathways) && !is_wp_error($pathways)) {
    echo "Test Pathway Slug: " . $pathways[0]->slug . "\n";
} else {
    echo "No pathways found.\n";
}
