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
 * Handle 301 Redirects for Old Program and Pathway URLs
 */








/**
 * Landing Page Meta Boxes for editable Hero & CTA content.
 */
function lohnour_landing_page_meta_boxes( $post_type, $post ) {
    if ( $post_type !== 'page' ) {
        return;
    }

    $allowed_slugs = array(
        'services',
        'corporate',
        'coaching',
        'individual',
        'group'
    );
    
    if ( in_array( $post->post_name, $allowed_slugs, true ) ) {
        add_meta_box(
            'lohnour_landing_meta',
            'Landing Page Hero & CTA',
            'lohnour_landing_meta_callback',
            'page',
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'lohnour_landing_page_meta_boxes', 10, 2);

function lohnour_landing_meta_callback($post) {
    $hero_title = get_post_meta($post->ID, '_lohnour_hero_title', true);
    $hero_subtitle = get_post_meta($post->ID, '_lohnour_hero_subtitle', true);
    $cta_label = get_post_meta($post->ID, '_lohnour_cta_label', true);
    
    echo '<div style="margin-bottom:15px;">';
    echo '<label style="display:block; font-weight:bold; margin-bottom:5px;">Hero Title (Overrides default template title)</label>';
    echo '<input type="text" name="lohnour_hero_title" value="' . esc_attr($hero_title) . '" style="width:100%;">';
    echo '<p class="description">Plain text recommended. CSS will handle wrapping.</p>';
    echo '</div>';
    
    echo '<div style="margin-bottom:15px;">';
    echo '<label style="display:block; font-weight:bold; margin-bottom:5px;">Hero Subtitle</label>';
    echo '<textarea name="lohnour_hero_subtitle" rows="3" style="width:100%;">' . esc_textarea($hero_subtitle) . '</textarea>';
    echo '</div>';
    
    echo '<div style="margin-bottom:15px;">';
    echo '<label style="display:block; font-weight:bold; margin-bottom:5px;">Primary CTA Label</label>';
    echo '<input type="text" name="lohnour_cta_label" value="' . esc_attr($cta_label) . '" style="width:100%;">';
    echo '</div>';
    
    wp_nonce_field('lohnour_landing_meta_save', 'lohnour_landing_meta_nonce');
}

function lohnour_save_landing_meta($post_id) {
    if (!isset($_POST['lohnour_landing_meta_nonce']) || !wp_verify_nonce($_POST['lohnour_landing_meta_nonce'], 'lohnour_landing_meta_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_page', $post_id)) return;

    if (isset($_POST['lohnour_hero_title'])) {
        update_post_meta($post_id, '_lohnour_hero_title', wp_kses_post($_POST['lohnour_hero_title']));
    }
    if (isset($_POST['lohnour_hero_subtitle'])) {
        update_post_meta($post_id, '_lohnour_hero_subtitle', wp_kses_post($_POST['lohnour_hero_subtitle']));
    }
    if (isset($_POST['lohnour_cta_label'])) {
        update_post_meta($post_id, '_lohnour_cta_label', sanitize_text_field($_POST['lohnour_cta_label']));
    }
}
add_action('save_post_page', 'lohnour_save_landing_meta');
