<?php
/**
 * Post Meta Settings & Admin Quick-Toggle for Featured Posts.
 *
 * @package Twenty_Twenty_Five_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * 1. Register Meta Box on Post/Guide Edit Screen.
 */
add_action( 'add_meta_boxes', 'lohnour_register_post_featured_meta_box' );
function lohnour_register_post_featured_meta_box() {
	// Standard Posts
	add_meta_box(
		'lohnour_post_featured_meta_box',
		esc_html__( 'Featured Article Settings', 'lohnourpro' ),
		'lohnour_post_featured_meta_box_callback',
		'post',
		'side',
		'high'
	);
	// User Guides
	add_meta_box(
		'lohnour_post_featured_meta_box',
		esc_html__( 'Featured Guide Settings', 'lohnourpro' ),
		'lohnour_post_featured_meta_box_callback',
		'user_guide',
		'side',
		'high'
	);
}

/**
 * Meta Box Callback.
 */
function lohnour_post_featured_meta_box_callback( $post ) {
	if ( 'user_guide' === $post->post_type && $post->post_parent > 0 ) {
		echo '<p class="description">' . esc_html__( 'Only parent guides can be featured.', 'lohnourpro' ) . '</p>';
		return;
	}
	wp_nonce_field( 'lohnour_save_post_featured', 'lohnour_post_featured_nonce' );
	$is_featured = get_post_meta( $post->ID, '_is_featured_post', true );
	?>
	<div style="padding: 5px 0;">
		<label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 500;">
			<input type="checkbox" name="lohnour_is_featured_post" value="1" <?php checked( $is_featured, '1' ); ?> />
			<span>Feature this item</span>
		</label>
		<p class="description" style="margin-top: 6px; font-size: 12px; color: #646970;">
			Checked items will appear in the "Featured" widgets/sidebars.
		</p>
	</div>
	<?php
}

/**
 * Save Meta Box Data.
 */
add_action( 'save_post', 'lohnour_save_post_featured_meta' );
function lohnour_save_post_featured_meta( $post_id ) {
	if ( ! isset( $_POST['lohnour_post_featured_nonce'] ) || ! wp_verify_nonce( $_POST['lohnour_post_featured_nonce'], 'lohnour_save_post_featured' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$post = get_post( $post_id );
	if ( 'user_guide' === $post->post_type && $post->post_parent > 0 ) {
		return; // Cannot save featured on child guide posts
	}

	if ( isset( $_POST['lohnour_is_featured_post'] ) && $_POST['lohnour_is_featured_post'] === '1' ) {
		update_post_meta( $post_id, '_is_featured_post', '1' );
	} else {
		delete_post_meta( $post_id, '_is_featured_post' );
	}
}

/**
 * 2. Add "Featured" Column to Posts List Table.
 */
add_filter( 'manage_posts_columns', 'lohnour_add_featured_column' );
add_filter( 'manage_user_guide_posts_columns', 'lohnour_add_featured_column' );
function lohnour_add_featured_column( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $title ) {
		if ( $key === 'date' ) {
			$new_columns['lohnour_featured'] = esc_html__( 'Featured', 'lohnourpro' );
		}
		$new_columns[$key] = $title;
	}
	if ( ! isset( $new_columns['lohnour_featured'] ) ) {
		$new_columns['lohnour_featured'] = esc_html__( 'Featured', 'lohnourpro' );
	}
	return $new_columns;
}

/**
 * Render Content for "Featured" Column.
 */
add_action( 'manage_posts_custom_column', 'lohnour_render_featured_column', 10, 2 );
add_action( 'manage_user_guide_posts_custom_column', 'lohnour_render_featured_column', 10, 2 );
function lohnour_render_featured_column( $column_name, $post_id ) {
	if ( $column_name === 'lohnour_featured' ) {
		$post = get_post( $post_id );
		if ( 'user_guide' === $post->post_type && $post->post_parent > 0 ) {
			echo '<span style="color:#999; font-size:16px;">—</span>';
			return;
		}
		$is_featured = get_post_meta( $post_id, '_is_featured_post', true );
		$checked     = ( $is_featured === '1' ) ? 'checked' : '';
		$nonce       = wp_create_nonce( 'lohnour_toggle_featured_' . $post_id );
		?>
		<label class="lohnour-toggle-switch" style="display: inline-block; position: relative; width: 38px; height: 22px;">
			<input type="checkbox" class="lohnour-featured-toggle" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>" <?php echo $checked; ?> style="opacity: 0; width: 0; height: 0;">
			<span class="lohnour-toggle-slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?php echo ( $checked ? '#2271b1' : '#ccc' ); ?>; transition: .3s; border-radius: 22px;">
				<span class="lohnour-toggle-knob" style="position: absolute; content: ''; height: 16px; width: 16px; left: <?php echo ( $checked ? '19px' : '3px' ); ?>; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%;"></span>
			</span>
		</label>
		<?php
	}
}

/**
 * 3. Handle AJAX Toggle Request.
 */
add_action( 'wp_ajax_lohnour_toggle_featured_post', 'lohnour_handle_ajax_toggle_featured_post' );
function lohnour_handle_ajax_toggle_featured_post() {
	$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
	$nonce   = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

	if ( ! $post_id || ! wp_verify_nonce( $nonce, 'lohnour_toggle_featured_' . $post_id ) ) {
		wp_send_json_error( array( 'message' => 'Invalid request / security check failed.' ) );
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( array( 'message' => 'Permission denied.' ) );
	}

	$post = get_post( $post_id );
	if ( 'user_guide' === $post->post_type && $post->post_parent > 0 ) {
		wp_send_json_error( array( 'message' => 'Only parent guides can be featured.' ) );
	}

	$is_featured = get_post_meta( $post_id, '_is_featured_post', true );
	if ( $is_featured === '1' ) {
		delete_post_meta( $post_id, '_is_featured_post' );
		$new_state = false;
	} else {
		update_post_meta( $post_id, '_is_featured_post', '1' );
		$new_state = true;
	}

	wp_send_json_success( array( 'featured' => $new_state ) );
}

/**
 * 4. Admin Footer Script for AJAX Toggle.
 */
add_action( 'admin_footer', 'lohnour_featured_toggle_admin_script' );
function lohnour_featured_toggle_admin_script() {
	$screen = get_current_screen();
	if ( ! $screen || $screen->base !== 'edit' || ( $screen->post_type !== 'post' && $screen->post_type !== 'user_guide' ) ) {
		return;
	}
	?>
	<script>
	jQuery(document).ready(function($) {
		$('.lohnour-featured-toggle').on('change', function() {
			var $checkbox = $(this);
			var $slider   = $checkbox.siblings('.lohnour-toggle-slider');
			var $knob     = $slider.find('.lohnour-toggle-knob');
			var postId    = $checkbox.data('post-id');
			var nonce     = $checkbox.data('nonce');
			var isChecked = $checkbox.is(':checked');

			// Disable checkbox temporarily
			$checkbox.prop('disabled', true);

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'lohnour_toggle_featured_post',
					post_id: postId,
					nonce: nonce
				},
				success: function(response) {
					$checkbox.prop('disabled', false);
					if (response.success) {
						if (response.data.featured) {
							$slider.css('background-color', '#2271b1');
							$knob.css('left', '19px');
							$checkbox.prop('checked', true);
						} else {
							$slider.css('background-color', '#ccc');
							$knob.css('left', '3px');
							$checkbox.prop('checked', false);
						}
					} else {
						// Revert if error
						$checkbox.prop('checked', !isChecked);
						alert(response.data.message || 'Error toggling featured status');
					}
				},
				error: function() {
					$checkbox.prop('disabled', false);
					$checkbox.prop('checked', !isChecked);
					alert('Server error occurred.');
				}
			});
		});
	});
	</script>
	<?php
}

/**
 * 5. Register User Guide settings meta box.
 */
add_action( 'add_meta_boxes', 'lohnour_register_user_guide_meta_boxes' );
function lohnour_register_user_guide_meta_boxes() {
	add_meta_box(
		'lohnour_user_guide_settings',
		esc_html__( 'User Guide Settings', 'lohnourpro' ),
		'lohnour_user_guide_settings_callback',
		'user_guide',
		'normal',
		'high'
	);
}

function lohnour_user_guide_settings_callback( $post ) {
	wp_nonce_field( 'lohnour_save_user_guide_meta', 'lohnour_user_guide_nonce' );
	
	$module_name = get_post_meta( $post->ID, '_guide_module_name', true );
	$modules_list = get_post_meta( $post->ID, '_guide_modules_list', true );
	if ( ! is_array( $modules_list ) ) {
		$modules_list = array();
	}

	// Fetch all potential parent topics (level 1 guides)
	$topics = get_posts( array(
		'post_type'      => 'user_guide',
		'post_parent'    => 0,
		'posts_per_page' => -1,
		'exclude'        => array( $post->ID ), // Prevent nesting inside itself
	) );

	// Build map of all parent modules for dynamic JS lookup
	$all_parent_modules = array();
	foreach ( $topics as $t ) {
		$mods = get_post_meta( $t->ID, '_guide_modules_list', true );
		if ( ! is_array( $mods ) || empty( $mods ) ) {
			// Fallback: Scan child lessons of this parent and collect unique modules
			$child_lessons = get_posts( array(
				'post_type'      => 'user_guide',
				'post_parent'    => $t->ID,
				'posts_per_page' => -1,
			) );
			$mods = array();
			$unique_names = array();
			foreach ( $child_lessons as $child ) {
				$m_name = get_post_meta( $child->ID, '_guide_module_name', true );
				if ( ! empty( $m_name ) && ! in_array( $m_name, $unique_names ) ) {
					$unique_names[] = $m_name;
				}
			}
			
			// Sort names alphanumerically to keep a logical baseline order
			usort( $unique_names, function( $a, $b ) {
				preg_match( '/Module\s+(\d+)/i', $a, $a_match );
				preg_match( '/Module\s+(\d+)/i', $b, $b_match );
				if ( isset( $a_match[1] ) && isset( $b_match[1] ) ) {
					return (int)$a_match[1] - (int)$b_match[1];
				}
				return strcasecmp( $a, $b );
			} );

			foreach ( $unique_names as $index => $name ) {
				$mods[] = array(
					'name'  => $name,
					'order' => ( $index + 1 ) * 10
				);
			}
			if ( ! empty( $mods ) ) {
				update_post_meta( $t->ID, '_guide_modules_list', $mods );
			}
		}
		$all_parent_modules[$t->ID] = is_array( $mods ) ? $mods : array();
	}
	?>
	<table class="form-table">
		<tr>
			<th><label for="guide_parent_id"><?php esc_html_e( 'Parent Guide', 'lohnourpro' ); ?></label></th>
			<td>
				<select name="guide_parent_id" id="guide_parent_id" class="large-text">
					<option value="0"><?php esc_html_e( '(no parent) - Make this a Parent Guide', 'lohnourpro' ); ?></option>
					<?php foreach ( $topics as $t ) : ?>
						<option value="<?php echo esc_attr( $t->ID ); ?>" <?php selected( $post->post_parent, $t->ID ); ?>>
							<?php echo esc_html( $t->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php esc_html_e( 'Select the parent guide topic. If you select (no parent), this guide becomes a main Topic overview page.', 'lohnourpro' ); ?></p>
			</td>
		</tr>

		<!-- Parent Modules Manager -->
		<tr id="parent_modules_manager_row">
			<th><label><?php esc_html_e( 'Modules Manager', 'lohnourpro' ); ?></label></th>
			<td>
				<div id="modules_list_container">
					<?php foreach ( $modules_list as $index => $mod ) : ?>
						<div class="module-item-row" style="margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">
							<input type="text" name="guide_modules[<?php echo $index; ?>][name]" value="<?php echo esc_attr( $mod['name'] ); ?>" placeholder="Module Name" style="width: 250px;" />
							<input type="number" name="guide_modules[<?php echo $index; ?>][order]" value="<?php echo esc_attr( $mod['order'] ); ?>" placeholder="Order" style="width: 70px;" min="0" />
							<button type="button" class="button remove-module-btn">Remove</button>
						</div>
					<?php endforeach; ?>
				</div>
				<button type="button" id="add_module_btn" class="button" style="margin-top: 10px;">+ Add Module</button>
				<p class="description"><?php esc_html_e( 'Define the modules for this guide and set their display order (e.g. Module 1: 10, Module 2: 20). Articles under this guide can select from these modules.', 'lohnourpro' ); ?></p>
			</td>
		</tr>

		<!-- Child Module Selector -->
		<tr id="guide_module_name_row">
			<th><label for="guide_module_select"><?php esc_html_e( 'Module Name', 'lohnourpro' ); ?></label></th>
			<td>
				<select name="guide_module_select" id="guide_module_select" class="large-text">
					<!-- Populated by JS -->
				</select>
				<div id="guide_new_module_wrapper" style="margin-top: 8px; display: none;">
					<input type="text" name="guide_new_module_name" id="guide_new_module_name" class="large-text" placeholder="e.g. Module 3: Advanced APIs" />
					<p class="description"><?php esc_html_e( 'Type the name of the new module. It will be added to the parent guide options.', 'lohnourpro' ); ?></p>
				</div>
			</td>
		</tr>

		<tr id="guide_menu_order_row">
			<th><label for="guide_menu_order"><?php esc_html_e( 'Lesson Order', 'lohnourpro' ); ?></label></th>
			<td>
				<input type="number" name="guide_menu_order" id="guide_menu_order" value="<?php echo esc_attr( $post->menu_order ); ?>" class="small-text" min="0" />
				<p class="description"><?php esc_html_e( 'Set the order of this lesson. e.g. 10, 20, 30.', 'lohnourpro' ); ?></p>
			</td>
		</tr>
	</table>

	<script>
	jQuery(document).ready(function($) {
		var parentModulesMap = <?php echo json_encode( $all_parent_modules ); ?>;
		var initialModuleName = <?php echo json_encode( $module_name ); ?>;

		function populateModules() {
			var parentId = $('#guide_parent_id').val();
			var select = $('#guide_module_select');
			select.empty();

			if (parentId !== '0') {
				var modules = parentModulesMap[parentId] || [];
				
				// Add existing modules
				modules.forEach(function(mod) {
					var option = $('<option></option>').val(mod.name).text(mod.name);
					if (mod.name === initialModuleName) {
						option.prop('selected', true);
					}
					select.append(option);
				});

				// Add new option
				var newOpt = $('<option value="__new__"></option>').text('<?php esc_html_e( "[ + Create a new module ]", "lohnourpro" ); ?>');
				if (initialModuleName && !modules.some(m => m.name === initialModuleName)) {
					// Fallback if not found in list
					var customOpt = $('<option></option>').val(initialModuleName).text(initialModuleName).prop('selected', true);
					select.prepend(customOpt);
				}
				select.append(newOpt);
			}
			toggleNewModuleInput();
		}

		function toggleNewModuleInput() {
			if ($('#guide_module_select').val() === '__new__') {
				$('#guide_new_module_wrapper').show();
			} else {
				$('#guide_new_module_wrapper').hide();
			}
		}

		function toggleLessonFields() {
			var parentId = $('#guide_parent_id').val();
			if (parentId === '0') {
				$('#guide_menu_order_row').hide();
				$('#guide_module_name_row').hide();
				$('#parent_modules_manager_row').show();
			} else {
				$('#guide_menu_order_row').show();
				$('#guide_module_name_row').show();
				$('#parent_modules_manager_row').hide();
				populateModules();
			}
		}

		// Parent Modules Manager Actions
		var moduleIndex = $('#modules_list_container .module-item-row').length;
		$('#add_module_btn').on('click', function(e) {
			e.preventDefault();
			var row = $('<div class="module-item-row" style="margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">' +
				'<input type="text" name="guide_modules[' + moduleIndex + '][name]" placeholder="Module Name" style="width: 250px;" /> ' +
				'<input type="number" name="guide_modules[' + moduleIndex + '][order]" placeholder="Order" style="width: 70px;" min="0" value="' + ((moduleIndex + 1) * 10) + '" /> ' +
				'<button type="button" class="button remove-module-btn">Remove</button>' +
				'</div>');
			$('#modules_list_container').append(row);
			moduleIndex++;
		});

		$(document).on('click', '.remove-module-btn', function(e) {
			e.preventDefault();
			$(this).closest('.module-item-row').remove();
		});

		$('#guide_parent_id').on('change', toggleLessonFields);
		$('#guide_module_select').on('change', toggleNewModuleInput);
		toggleLessonFields(); // Trigger on page load
	});
	</script>
	<?php
}

add_action( 'save_post', 'lohnour_save_user_guide_meta' );
function lohnour_save_user_guide_meta( $post_id ) {
	if ( ! isset( $_POST['lohnour_user_guide_nonce'] ) || ! wp_verify_nonce( $_POST['lohnour_user_guide_nonce'], 'lohnour_save_user_guide_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Update Modules List on Parent
	if ( isset( $_POST['guide_modules'] ) && is_array( $_POST['guide_modules'] ) ) {
		$modules_list = array();
		foreach ( $_POST['guide_modules'] as $mod ) {
			$name = sanitize_text_field( $mod['name'] );
			$order = intval( $mod['order'] );
			if ( ! empty( $name ) ) {
				$modules_list[] = array(
					'name'  => $name,
					'order' => $order
				);
			}
		}
		usort( $modules_list, function( $a, $b ) {
			return $a['order'] - $b['order'];
		} );
		update_post_meta( $post_id, '_guide_modules_list', $modules_list );
	} else if ( isset( $_POST['guide_parent_id'] ) && intval( $_POST['guide_parent_id'] ) === 0 ) {
		delete_post_meta( $post_id, '_guide_modules_list' );
	}

	// Save Child Module selection
	$selected_parent_id = isset( $_POST['guide_parent_id'] ) ? intval( $_POST['guide_parent_id'] ) : 0;
	$final_module_name = '';

	if ( $selected_parent_id > 0 ) {
		if ( isset( $_POST['guide_module_select'] ) && $_POST['guide_module_select'] === '__new__' && ! empty( $_POST['guide_new_module_name'] ) ) {
			$final_module_name = sanitize_text_field( $_POST['guide_new_module_name'] );

			$parent_modules = get_post_meta( $selected_parent_id, '_guide_modules_list', true );
			if ( ! is_array( $parent_modules ) ) {
				$parent_modules = array();
			}

			$exists = false;
			foreach ( $parent_modules as $mod ) {
				if ( strcasecmp( $mod['name'], $final_module_name ) === 0 ) {
					$exists = true;
					break;
				}
			}

			if ( ! $exists ) {
				$max_order = 0;
				foreach ( $parent_modules as $mod ) {
					if ( $mod['order'] > $max_order ) {
						$max_order = $mod['order'];
					}
				}
				$parent_modules[] = array(
					'name'  => $final_module_name,
					'order' => $max_order + 10
				);
				update_post_meta( $selected_parent_id, '_guide_modules_list', $parent_modules );
			}
		} else if ( isset( $_POST['guide_module_select'] ) && $_POST['guide_module_select'] !== '__new__' ) {
			$final_module_name = sanitize_text_field( $_POST['guide_module_select'] );
		}
	}

	if ( ! empty( $final_module_name ) ) {
		update_post_meta( $post_id, '_guide_module_name', $final_module_name );
	}

	// Update Page Attributes (Parent ID & Menu Order)
	if ( isset( $_POST['guide_parent_id'] ) || isset( $_POST['guide_menu_order'] ) ) {
		$parent_id  = isset( $_POST['guide_parent_id'] ) ? intval( $_POST['guide_parent_id'] ) : 0;
		$menu_order = isset( $_POST['guide_menu_order'] ) ? intval( $_POST['guide_menu_order'] ) : 0;

		remove_action( 'save_post', 'lohnour_save_user_guide_meta' );
		wp_update_post( array(
			'ID'          => $post_id,
			'post_parent' => $parent_id,
			'menu_order'  => ( $parent_id === 0 ) ? 0 : $menu_order,
		) );
		add_action( 'save_post', 'lohnour_save_user_guide_meta' );
	}
}

