<?php
/**
 * Custom admin fields for lohnour_pathway taxonomy terms.
 * Offers a premium double-column selection interface for adding, removing,
 * and ordering featured programs.
 *
 * @package Twenty_Twenty_Five_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// 1. Add fields form (for new term screen)
add_action( 'lohnour_pathway_add_form_fields', 'lohnour_pathway_add_meta_fields', 10, 1 );
function lohnour_pathway_add_meta_fields( $taxonomy ) {
	$programs = get_posts( array(
		'post_type'      => 'lohnour_service',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );
	?>
	<div class="form-field term-featured-programs-wrap">
		<label><?php esc_html_e( 'Featured Programs Selection', 'lohnourpro' ); ?></label>
		<?php lohnour_render_program_selector( array(), $programs ); ?>
	</div>
	<div class="form-field term-display-limit-wrap">
		<label for="lohnour_display_limit"><?php esc_html_e( 'Display Limit', 'lohnourpro' ); ?></label>
		<input type="number" name="lohnour_display_limit" id="lohnour_display_limit" value="3" min="1" max="12" style="width: 100px;">
		<p class="description"><?php esc_html_e( 'Define the max number of program cards to show in this section on the services page.', 'lohnourpro' ); ?></p>
	</div>
	<?php
}

// 2. Edit fields form (for edit term screen)
add_action( 'lohnour_pathway_edit_form_fields', 'lohnour_pathway_edit_meta_fields', 10, 2 );
function lohnour_pathway_edit_meta_fields( $term, $taxonomy ) {
	$featured_programs = get_term_meta( $term->term_id, '_lohnour_featured_programs', true );
	$display_limit     = get_term_meta( $term->term_id, '_lohnour_display_limit', true );

	if ( ! is_array( $featured_programs ) ) {
		$featured_programs = array();
	}
	if ( empty( $display_limit ) ) {
		$display_limit = 3;
	}

	$programs = get_posts( array(
		'post_type'      => 'lohnour_service',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'tax_query'      => array(
			array(
				'taxonomy' => 'lohnour_pathway',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			),
		),
	) );
	?>
	<tr class="form-field term-featured-programs-wrap">
		<th scope="row"><label><?php esc_html_e( 'Featured Programs Selection', 'lohnourpro' ); ?></label></th>
		<td>
			<?php lohnour_render_program_selector( $featured_programs, $programs ); ?>
		</td>
	</tr>
	<tr class="form-field term-display-limit-wrap">
		<th scope="row"><label for="lohnour_display_limit"><?php esc_html_e( 'Display Limit', 'lohnourpro' ); ?></label></th>
		<td>
			<input type="number" name="lohnour_display_limit" id="lohnour_display_limit" value="<?php echo esc_attr( $display_limit ); ?>" min="1" max="12" style="width: 100px;">
			<p class="description"><?php esc_html_e( 'Define the max number of program cards to show in this section on the services page.', 'lohnourpro' ); ?></p>
		</td>
	</tr>
	<?php
}

// Helper to render the interactive two-column UI
function lohnour_render_program_selector( $featured_ids, $all_programs ) {
	// Separate programs into featured (in saved order) and available
	$featured_items = array();
	$available_items = array();

	// Map programs by ID
	$programs_map = array();
	foreach ( $all_programs as $p ) {
		$programs_map[ $p->ID ] = $p;
	}

	// Load featured in the exact saved order
	foreach ( $featured_ids as $fid ) {
		if ( isset( $programs_map[ $fid ] ) ) {
			$featured_items[] = $programs_map[ $fid ];
		}
	}

	// Available is anything not in featured
	foreach ( $all_programs as $p ) {
		if ( ! in_array( $p->ID, $featured_ids ) ) {
			$available_items[] = $p;
		}
	}
	?>
	<div class="lohnour-selector-wrapper" style="display: flex; gap: 20px; max-width: 650px; font-family: sans-serif; margin-bottom: 10px;">
		
		<!-- Left: Available List -->
		<div class="lohnour-selector-col" style="flex: 1; border: 1px solid #ccd0d4; background: #fff; border-radius: 4px; padding: 10px; box-shadow: inset 0 1px 2px rgba(0,0,0,.07);">
			<h4 style="margin: 0 0 10px; border-bottom: 1px solid #eee; padding-bottom: 6px; font-size: 13px; font-weight: 600; color: #1d2327;">Available Programs</h4>
			<ul id="lohnour-available-list" style="margin: 0; padding: 0; list-style: none; max-height: 250px; overflow-y: auto;">
				<?php if ( empty( $available_items ) ) : ?>
					<li class="no-items" style="color: #646970; font-style: italic; font-size: 12px; padding: 6px 0;">No programs available</li>
				<?php else : ?>
					<?php foreach ( $available_items as $item ) : ?>
						<li data-id="<?php echo esc_attr( $item->ID ); ?>" style="display: flex; justify-content: space-between; align-items: center; padding: 6px 8px; border-bottom: 1px solid #f0f0f1; margin: 0; font-size: 12px; color: #2c3338;">
							<span><?php echo esc_html( $item->post_title ); ?></span>
							<button type="button" class="button button-small lohnour-add-btn" style="color: #2271b1; border-color: #2271b1; font-weight: 500;">Add +</button>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>

		<!-- Right: Featured Sorted List -->
		<div class="lohnour-selector-col" style="flex: 1; border: 1px solid #ccd0d4; background: #fff; border-radius: 4px; padding: 10px; box-shadow: inset 0 1px 2px rgba(0,0,0,.07);">
			<h4 style="margin: 0 0 10px; border-bottom: 1px solid #eee; padding-bottom: 6px; font-size: 13px; font-weight: 600; color: #1d2327;">Featured on Page (Sort Order)</h4>
			<ul id="lohnour-featured-list" style="margin: 0; padding: 0; list-style: none; max-height: 250px; overflow-y: auto;">
				<?php if ( empty( $featured_items ) ) : ?>
					<li class="no-items" style="color: #646970; font-style: italic; font-size: 12px; padding: 6px 0;">None selected (shows latest)</li>
				<?php endif; ?>
				
				<?php foreach ( $featured_items as $item ) : ?>
					<li data-id="<?php echo esc_attr( $item->ID ); ?>" style="display: flex; justify-content: space-between; align-items: center; padding: 6px 8px; border-bottom: 1px solid #f0f0f1; margin: 0; font-size: 12px; color: #2c3338;">
						<input type="hidden" name="lohnour_featured_programs[]" value="<?php echo esc_attr( $item->ID ); ?>">
						<span style="font-weight: 500;"><?php echo esc_html( $item->post_title ); ?></span>
						<div style="display: flex; gap: 3px;">
							<button type="button" class="button button-small lohnour-up-btn" style="padding: 0 4px;">↑</button>
							<button type="button" class="button button-small lohnour-down-btn" style="padding: 0 4px;">↓</button>
							<button type="button" class="button button-small lohnour-remove-btn" style="color: #b32d2e; border-color: #b32d2e; margin-left: 4px;">Remove</button>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

	</div>
	<p class="description"><?php esc_html_e( 'Use Add to feature a program. Use ↑ and ↓ to order them, and click Remove to withdraw them.', 'lohnourpro' ); ?></p>

	<!-- Script to handle dynamic addition, removal, and ordering -->
	<script>
	jQuery(document).ready(function($) {
		const $available = $('#lohnour-available-list');
		const $featured = $('#lohnour-featured-list');

		// 1. Add item to featured
		$(document).on('click', '.lohnour-add-btn', function(e) {
			e.preventDefault();
			const $li = $(this).closest('li');
			const id = $li.data('id');
			const title = $li.find('span').text();

			// Remove placeholder if present
			$featured.find('.no-items').remove();

			// Append to featured list
			const newItemHtml = `
				<li data-id="${id}" style="display: flex; justify-content: space-between; align-items: center; padding: 6px 8px; border-bottom: 1px solid #f0f0f1; margin: 0; font-size: 12px; color: #2c3338;">
					<input type="hidden" name="lohnour_featured_programs[]" value="${id}">
					<span style="font-weight: 500;">${title}</span>
					<div style="display: flex; gap: 3px;">
						<button type="button" class="button button-small lohnour-up-btn" style="padding: 0 4px;">↑</button>
						<button type="button" class="button button-small lohnour-down-btn" style="padding: 0 4px;">↓</button>
						<button type="button" class="button button-small lohnour-remove-btn" style="color: #b32d2e; border-color: #b32d2e; margin-left: 4px;">Remove</button>
					</div>
				</li>
			`;
			$featured.append(newItemHtml);

			// Remove from available list
			$li.remove();
			if ($available.children('li').length === 0) {
				$available.append('<li class="no-items" style="color: #646970; font-style: italic; font-size: 12px; padding: 6px 0;">No programs available</li>');
			}
		});

		// 2. Remove item from featured
		$(document).on('click', '.lohnour-remove-btn', function(e) {
			e.preventDefault();
			const $li = $(this).closest('li');
			const id = $li.data('id');
			const title = $li.find('span').text();

			// Remove placeholder in available if present
			$available.find('.no-items').remove();

			// Append to available list
			const returnItemHtml = `
				<li data-id="${id}" style="display: flex; justify-content: space-between; align-items: center; padding: 6px 8px; border-bottom: 1px solid #f0f0f1; margin: 0; font-size: 12px; color: #2c3338;">
					<span>${title}</span>
					<button type="button" class="button button-small lohnour-add-btn" style="color: #2271b1; border-color: #2271b1; font-weight: 500;">Add +</button>
				</li>
			`;
			$available.append(returnItemHtml);

			// Sort available list alphabetically
			const $items = $available.children('li').not('.no-items').get();
			$items.sort(function(a, b) {
				return $(a).find('span').text().localeCompare($(b).find('span').text());
			});
			$.each($items, function(i, li) {
				$available.append(li);
			});

			// Remove from featured
			$li.remove();
			if ($featured.children('li').length === 0) {
				$featured.append('<li class="no-items" style="color: #646970; font-style: italic; font-size: 12px; padding: 6px 0;">None selected (shows latest)</li>');
			}
		});

		// 3. Move Up
		$(document).on('click', '.lohnour-up-btn', function(e) {
			e.preventDefault();
			const $li = $(this).closest('li');
			const $prev = $li.prev();
			if ($prev.length > 0 && !$prev.hasClass('no-items')) {
				$li.insertBefore($prev);
			}
		});

		// 4. Move Down
		$(document).on('click', '.lohnour-down-btn', function(e) {
			e.preventDefault();
			const $li = $(this).closest('li');
			const $next = $li.next();
			if ($next.length > 0 && !$next.hasClass('no-items')) {
				$li.insertAfter($next);
			}
		});
	});
	</script>
	<?php
}

// 3. Save fields data
add_action( 'created_lohnour_pathway', 'lohnour_pathway_save_meta_fields', 10, 2 );
add_action( 'edited_lohnour_pathway', 'lohnour_pathway_save_meta_fields', 10, 2 );
function lohnour_pathway_save_meta_fields( $term_id, $tt_id ) {
	// Only update/delete if the request comes from our taxonomy field form
	if ( ! isset( $_POST['lohnour_display_limit'] ) ) {
		return;
	}

	if ( isset( $_POST['lohnour_featured_programs'] ) ) {
		$sanitized_ids = array_map( 'intval', $_POST['lohnour_featured_programs'] );
		update_term_meta( $term_id, '_lohnour_featured_programs', $sanitized_ids );
	} else {
		delete_term_meta( $term_id, '_lohnour_featured_programs' );
	}

	update_term_meta( $term_id, '_lohnour_display_limit', intval( $_POST['lohnour_display_limit'] ) );
}
