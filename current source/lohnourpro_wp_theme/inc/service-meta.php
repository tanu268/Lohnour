<?php
/**
 * Register custom meta boxes for Program (lohnour_service CPT) posts.
 * Provides custom input for Sidebar Benefits list.
 *
 * @package Twenty_Twenty_Five_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Register Meta Box
add_action( 'add_meta_boxes', 'lohnour_service_register_meta_boxes' );
function lohnour_service_register_meta_boxes() {
	add_meta_box(
		'lohnour_service_meta_box',
		esc_html__( 'Program Sidebar Details', 'lohnourpro' ),
		'lohnour_service_meta_box_callback',
		'lohnour_service',
		'normal',
		'high'
	);
}

// Render Form Callback
function lohnour_service_meta_box_callback( $post ) {
	wp_nonce_field( 'lohnour_service_save_meta', 'lohnour_service_meta_nonce' );

	$benefits = get_post_meta( $post->ID, '_lohnour_sidebar_benefits', true );
	?>
	<div style="font-family: sans-serif; padding: 10px 0;">
		<!-- Card Excerpt Section -->
		<h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 20px; margin-bottom: 15px; font-size: 14px; font-weight: 600; color: #1d2327;">Card Excerpt</h3>
		<div style="margin-bottom: 20px;">
			<label style="display: block; font-weight: 500; margin-bottom: 4px;">Short Excerpt (Displayed on Cards)</label>
			<textarea name="lohnour_card_excerpt" rows="3" style="width: 100%; max-width: 500px;" placeholder="Enter a short excerpt to display on program listing cards..."><?php echo esc_textarea( get_post_meta( $post->ID, '_lohnour_card_excerpt', true ) ); ?></textarea>
			<p class="description">This description will only show up on cards on services/pathways pages, not on the individual program detail page itself.</p>
		</div>

		<!-- Sidebar Benefits Section -->
		<h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 15px; font-size: 14px; font-weight: 600; color: #1d2327;">Sidebar Get Started Details</h3>
		<div>
			<label style="display: block; font-weight: 500; margin-bottom: 4px;">Sidebar Benefits List (One per line)</label>
			<textarea name="lohnour_sidebar_benefits" rows="5" style="width: 100%; max-width: 500px;" placeholder="e.g.&#10;Direct Trainer Access&#10;Staggered Real-World Projects&#10;Access to Source Code Templates"><?php echo esc_textarea( $benefits ); ?></textarea>
			<p class="description">Enter each benefit checkmark point on a new line.</p>
		</div>
	</div>
	<?php
}

// Save Meta Box Data
add_action( 'save_post_lohnour_service', 'lohnour_service_save_meta_box_data' );
function lohnour_service_save_meta_box_data( $post_id ) {
	if ( ! isset( $_POST['lohnour_service_meta_nonce'] ) || ! wp_verify_nonce( $_POST['lohnour_service_meta_nonce'], 'lohnour_service_save_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['lohnour_card_excerpt'] ) ) {
		$value = sanitize_textarea_field( $_POST['lohnour_card_excerpt'] );
		update_post_meta( $post_id, '_lohnour_card_excerpt', $value );
	}

	if ( isset( $_POST['lohnour_sidebar_benefits'] ) ) {
		$value = sanitize_textarea_field( $_POST['lohnour_sidebar_benefits'] );
		update_post_meta( $post_id, '_lohnour_sidebar_benefits', $value );
	}
}
