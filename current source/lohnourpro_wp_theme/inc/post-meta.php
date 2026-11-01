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
		echo '<p class="description">' . esc_html__( 'Only parent course topics can be featured.', 'lohnourpro' ) . '</p>';
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
		wp_send_json_error( array( 'message' => 'Only parent course topics can be featured.' ) );
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
 * User Guide Migration Functions (Deterministic, Idempotent, Safe).
 * Used for previewing and running migration without blind assignment.
 */
function lohnour_get_user_guide_migration_preview() {
	$parents = get_posts( array(
		'post_type'      => 'user_guide',
		'post_parent'    => 0,
		'posts_per_page' => -1,
		'post_status'    => 'any',
	) );

	$preview = array(
		'parents'           => array(),
		'total_parents'     => count( $parents ),
		'total_modules'     => 0,
		'total_lessons'     => 0,
		'total_children'    => 0,
		'assigned_children' => 0,
		'unassigned'        => array(),
	);

	foreach ( $parents as $parent ) {
		$modules = get_post_meta( $parent->ID, '_guide_modules_list', true );
		if ( ! is_array( $modules ) ) {
			$modules = array();
		}

		$children = get_posts( array(
			'post_type'      => 'user_guide',
			'post_parent'    => $parent->ID,
			'posts_per_page' => -1,
			'post_status'    => 'any',
		) );

		$parent_info = array(
			'id'       => $parent->ID,
			'title'    => $parent->post_title,
			'modules'  => array(),
			'children' => array(),
		);

		foreach ( $modules as $m ) {
			$m_name = isset( $m['name'] ) ? trim( $m['name'] ) : '';
			$lessons = isset( $m['lessons'] ) && is_array( $m['lessons'] ) ? $m['lessons'] : array();
			$preview['total_lessons'] += count( $lessons );
			$parent_info['modules'][] = array(
				'name'          => $m_name,
				'order'         => isset( $m['order'] ) ? intval( $m['order'] ) : 0,
				'lessons_count' => count( $lessons ),
			);
		}
		$preview['total_modules'] += count( $modules );

		foreach ( $children as $child ) {
			$preview['total_children']++;
			$c_mod = trim( get_post_meta( $child->ID, '_guide_module_name', true ) );
			$c_les = trim( get_post_meta( $child->ID, '_guide_lesson_name', true ) );
			$child_info = array(
				'id'     => $child->ID,
				'title'  => $child->post_title,
				'module' => $c_mod,
				'lesson' => $c_les,
			);
			$parent_info['children'][] = $child_info;

			if ( ! empty( $c_les ) ) {
				$preview['assigned_children']++;
			} else {
				$preview['unassigned'][] = $child_info;
			}
		}

		$preview['parents'][] = $parent_info;
	}

	return $preview;
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
	$lesson_name = get_post_meta( $post->ID, '_guide_lesson_name', true );
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

	// Build map of all parent modules and lessons for dynamic JS lookup (strictly read-only)
	$all_parent_modules = array();
	foreach ( $topics as $t ) {
		$mods = get_post_meta( $t->ID, '_guide_modules_list', true );
		if ( ! is_array( $mods ) ) {
			$mods = array();
		}
		// Ensure each module in memory has a lessons array structure
		foreach ( $mods as $k => $m ) {
			if ( ! isset( $m['lessons'] ) || ! is_array( $m['lessons'] ) ) {
				$mods[$k]['lessons'] = array();
			}
		}
		$all_parent_modules[$t->ID] = $mods;
	}
	?>
	<table class="form-table">
		<tr>
			<th><label for="guide_parent_id"><?php esc_html_e( 'Parent Course/Topic', 'lohnourpro' ); ?></label></th>
			<td>
				<select name="guide_parent_id" id="guide_parent_id" class="large-text">
					<option value="0"><?php esc_html_e( '(no parent) - Make this a Course Topic Page', 'lohnourpro' ); ?></option>
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
					<?php foreach ( $modules_list as $m_idx => $mod ) :
						$mod_lessons = isset( $mod['lessons'] ) && is_array( $mod['lessons'] ) ? $mod['lessons'] : array();
					?>
						<div class="module-item-card" data-module-index="<?php echo $m_idx; ?>" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px; margin-bottom: 14px;">
							<div class="module-header-row" style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
								<span class="dashicons dashicons-category" style="color: #64748b;"></span>
								<input type="text" name="guide_modules[<?php echo $m_idx; ?>][name]" value="<?php echo esc_attr( $mod['name'] ); ?>" placeholder="Module Name" style="width: 260px; font-weight: 600;" />
								<input type="number" name="guide_modules[<?php echo $m_idx; ?>][order]" value="<?php echo esc_attr( $mod['order'] ); ?>" placeholder="Order" style="width: 70px;" min="0" title="Module Display Order" />
								<button type="button" class="button remove-module-btn" style="color: #b32d2e; border-color: #b32d2e; margin-left: auto;">Remove Module</button>
							</div>

							<!-- Nested Lessons Manager -->
							<div class="lessons-container" style="margin-left: 24px; padding: 10px 12px; background: #ffffff; border: 1px solid #e2e8f0; border-left: 3px solid #2271b1; border-radius: 0 4px 4px 0;">
								<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
									<span style="font-weight: 600; font-size: 12px; color: #334155; text-transform: uppercase; letter-spacing: 0.03em;"><?php esc_html_e( 'Lessons in this Module', 'lohnourpro' ); ?></span>
									<span class="description" style="font-size: 11px;"><?php esc_html_e( 'Define lessons under this module', 'lohnourpro' ); ?></span>
								</div>
								<div class="lessons-list">
									<?php foreach ( $mod_lessons as $l_idx => $les ) : ?>
										<div class="lesson-item-row" data-lesson-index="<?php echo $l_idx; ?>" style="margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
											<span class="dashicons dashicons-arrow-right-alt2" style="color: #94a3b8; font-size: 16px; width: 16px; height: 16px; line-height: 16px;"></span>
											<input type="text" name="guide_modules[<?php echo $m_idx; ?>][lessons][<?php echo $l_idx; ?>][name]" value="<?php echo esc_attr( $les['name'] ); ?>" placeholder="Lesson Name (e.g. Lesson 1)" style="width: 230px;" />
											<input type="number" name="guide_modules[<?php echo $m_idx; ?>][lessons][<?php echo $l_idx; ?>][order]" value="<?php echo esc_attr( $les['order'] ); ?>" placeholder="Order" style="width: 60px;" min="0" title="Lesson Display Order" />
											<button type="button" class="button button-small remove-lesson-btn" title="Remove Lesson">✕</button>
										</div>
									<?php endforeach; ?>
								</div>
								<button type="button" class="button button-secondary add-lesson-btn" style="margin-top: 4px; font-size: 11px;">+ Add Lesson</button>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<button type="button" id="add_module_btn" class="button button-primary" style="margin-top: 6px;">+ Add Module</button>
				<p class="description"><?php esc_html_e( 'Define the modules and lessons for this course topic and set their display order (e.g. 10, 20). Lessons under this topic can select from these modules and lessons.', 'lohnourpro' ); ?></p>
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

		<!-- Child Lesson Selector -->
		<tr id="guide_lesson_name_row">
			<th><label for="guide_lesson_select"><?php esc_html_e( 'Lesson Name', 'lohnourpro' ); ?></label></th>
			<td>
				<select name="guide_lesson_select" id="guide_lesson_select" class="large-text">
					<!-- Populated by JS based on selected module -->
				</select>
				<div id="guide_new_lesson_wrapper" style="margin-top: 8px; display: none;">
					<input type="text" name="guide_new_lesson_name" id="guide_new_lesson_name" class="large-text" placeholder="e.g. Lesson 2: Configuration" />
					<p class="description"><?php esc_html_e( 'Type the name of the new lesson. It will be added under the chosen module.', 'lohnourpro' ); ?></p>
				</div>
				<p class="description"><?php esc_html_e( 'Select the lesson under the chosen module that this guide belongs to.', 'lohnourpro' ); ?></p>
			</td>
		</tr>

		<tr id="guide_menu_order_row">
			<th><label for="guide_menu_order"><?php esc_html_e( 'Guide Display Order', 'lohnourpro' ); ?></label></th>
			<td>
				<input type="number" name="guide_menu_order" id="guide_menu_order" value="<?php echo esc_attr( $post->menu_order ); ?>" class="small-text" min="0" />
				<p class="description"><?php esc_html_e( 'Set the order of this guide inside its lesson. e.g. 10, 20, 30.', 'lohnourpro' ); ?></p>
			</td>
		</tr>
	</table>

	<script>
	jQuery(document).ready(function($) {
		var parentModulesMap = <?php echo json_encode( $all_parent_modules ); ?>;
		var initialModuleName = <?php echo json_encode( $module_name ); ?>;
		var initialLessonName = <?php echo json_encode( $lesson_name ); ?>;
		var currentSelectedLesson = initialLessonName;

		function getSelectedParentModules() {
			var parentId = $('#guide_parent_id').val();
			if (parentId !== '0' && parentModulesMap[parentId]) {
				return parentModulesMap[parentId];
			}
			return [];
		}

		function populateModules() {
			var select = $('#guide_module_select');
			var currentMod = select.val() || initialModuleName;
			select.empty();

			var modules = getSelectedParentModules();
			if (modules.length > 0) {
				modules.forEach(function(mod) {
					var option = $('<option></option>').val(mod.name).text(mod.name);
					if (mod.name === currentMod) {
						option.prop('selected', true);
					}
					select.append(option);
				});

				var newOpt = $('<option value="__new__"></option>').text('<?php esc_html_e( "[ + Create a new module ]", "lohnourpro" ); ?>');
				if (currentMod && !modules.some(function(m) { return m.name === currentMod; })) {
					var customOpt = $('<option></option>').val(currentMod).text(currentMod).prop('selected', true);
					select.prepend(customOpt);
				}
				select.append(newOpt);
			}
			toggleNewModuleInput();
			populateLessons(false);
		}

		function populateLessons(isUserModuleChange) {
			var lessonSelect = $('#guide_lesson_select');
			var selectedModule = $('#guide_module_select').val();
			lessonSelect.empty();

			if (selectedModule === '__new__') {
				var newLesOpt = $('<option value="__new__" selected></option>').text('<?php esc_html_e( "[ + Create a new lesson ]", "lohnourpro" ); ?>');
				lessonSelect.append(newLesOpt);
				toggleNewLessonInput();
				return;
			}

			var modules = getSelectedParentModules();
			var targetMod = null;
			for (var i = 0; i < modules.length; i++) {
				if (modules[i].name === selectedModule) {
					targetMod = modules[i];
					break;
				}
			}

			var lessons = (targetMod && targetMod.lessons && Array.isArray(targetMod.lessons)) ? targetMod.lessons : [];
			var lessonFound = false;

			// If module changed by user interaction, previous lesson from another module is INVALID
			if (isUserModuleChange) {
				currentSelectedLesson = '';
			}

			if (lessons.length > 0) {
				lessons.forEach(function(les) {
					var opt = $('<option></option>').val(les.name).text(les.name);
					if (les.name === currentSelectedLesson) {
						opt.prop('selected', true);
						lessonFound = true;
					}
					lessonSelect.append(opt);
				});

				// If currently selected lesson didn't belong to this module, default to first lesson
				if (!lessonFound && lessons.length > 0) {
					lessonSelect.val(lessons[0].name);
					currentSelectedLesson = lessons[0].name;
				}
			} else {
				var emptyOpt = $('<option value=""></option>').text('<?php esc_html_e( "(No lessons in this module - create one)", "lohnourpro" ); ?>');
				lessonSelect.append(emptyOpt);
			}

			var addLesOpt = $('<option value="__new__"></option>').text('<?php esc_html_e( "[ + Create a new lesson ]", "lohnourpro" ); ?>');
			if (lessons.length === 0) {
				addLesOpt.prop('selected', true);
			}
			lessonSelect.append(addLesOpt);

			toggleNewLessonInput();
		}

		function toggleNewModuleInput() {
			if ($('#guide_module_select').val() === '__new__') {
				$('#guide_new_module_wrapper').show();
			} else {
				$('#guide_new_module_wrapper').hide();
			}
		}

		function toggleNewLessonInput() {
			if ($('#guide_lesson_select').val() === '__new__') {
				$('#guide_new_lesson_wrapper').show();
			} else {
				$('#guide_new_lesson_wrapper').hide();
			}
		}

		function toggleLessonFields() {
			var parentId = $('#guide_parent_id').val();
			if (parentId === '0') {
				$('#guide_menu_order_row').hide();
				$('#guide_module_name_row').hide();
				$('#guide_lesson_name_row').hide();
				$('#parent_modules_manager_row').show();
			} else {
				$('#guide_menu_order_row').show();
				$('#guide_module_name_row').show();
				$('#guide_lesson_name_row').show();
				$('#parent_modules_manager_row').hide();
				populateModules();
			}
		}

		// Parent Modules Manager Actions
		var moduleCount = $('#modules_list_container .module-item-card').length;
		$('#add_module_btn').on('click', function(e) {
			e.preventDefault();
			var nextOrder = (moduleCount + 1) * 10;
			var cardHtml = '<div class="module-item-card" data-module-index="' + moduleCount + '" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px; margin-bottom: 14px;">' +
				'<div class="module-header-row" style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">' +
					'<span class="dashicons dashicons-category" style="color: #64748b;"></span>' +
					'<input type="text" name="guide_modules[' + moduleCount + '][name]" placeholder="Module Name" style="width: 260px; font-weight: 600;" /> ' +
					'<input type="number" name="guide_modules[' + moduleCount + '][order]" value="' + nextOrder + '" placeholder="Order" style="width: 70px;" min="0" /> ' +
					'<button type="button" class="button remove-module-btn" style="color: #b32d2e; border-color: #b32d2e; margin-left: auto;">Remove Module</button>' +
				'</div>' +
				'<div class="lessons-container" style="margin-left: 24px; padding: 10px 12px; background: #ffffff; border: 1px solid #e2e8f0; border-left: 3px solid #2271b1; border-radius: 0 4px 4px 0;">' +
					'<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">' +
						'<span style="font-weight: 600; font-size: 12px; color: #334155; text-transform: uppercase; letter-spacing: 0.03em;">Lessons in this Module</span>' +
					'</div>' +
					'<div class="lessons-list">' +
						'<div class="lesson-item-row" data-lesson-index="0" style="margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">' +
							'<span class="dashicons dashicons-arrow-right-alt2" style="color: #94a3b8; font-size: 16px; width: 16px; height: 16px; line-height: 16px;"></span>' +
							'<input type="text" name="guide_modules[' + moduleCount + '][lessons][0][name]" value="Lesson 1" placeholder="Lesson Name (e.g. Lesson 1)" style="width: 230px;" /> ' +
							'<input type="number" name="guide_modules[' + moduleCount + '][lessons][0][order]" value="10" placeholder="Order" style="width: 60px;" min="0" /> ' +
							'<button type="button" class="button button-small remove-lesson-btn" title="Remove Lesson">✕</button>' +
						'</div>' +
					'</div>' +
					'<button type="button" class="button button-secondary add-lesson-btn" style="margin-top: 4px; font-size: 11px;">+ Add Lesson</button>' +
				'</div>' +
			'</div>';
			$('#modules_list_container').append(cardHtml);
			moduleCount++;
		});

		$(document).on('click', '.remove-module-btn', function(e) {
			e.preventDefault();
			$(this).closest('.module-item-card').remove();
		});

		$(document).on('click', '.add-lesson-btn', function(e) {
			e.preventDefault();
			var $card = $(this).closest('.module-item-card');
			var mIdx = $card.data('module-index');
			var $list = $card.find('.lessons-list');
			var lCount = $list.find('.lesson-item-row').length;
			var nextLOrder = (lCount + 1) * 10;
			var rowHtml = '<div class="lesson-item-row" data-lesson-index="' + lCount + '" style="margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">' +
				'<span class="dashicons dashicons-arrow-right-alt2" style="color: #94a3b8; font-size: 16px; width: 16px; height: 16px; line-height: 16px;"></span>' +
				'<input type="text" name="guide_modules[' + mIdx + '][lessons][' + lCount + '][name]" placeholder="Lesson Name (e.g. Lesson ' + (lCount + 1) + ')" style="width: 230px;" /> ' +
				'<input type="number" name="guide_modules[' + mIdx + '][lessons][' + lCount + '][order]" value="' + nextLOrder + '" placeholder="Order" style="width: 60px;" min="0" /> ' +
				'<button type="button" class="button button-small remove-lesson-btn" title="Remove Lesson">✕</button>' +
			'</div>';
			$list.append(rowHtml);
		});

		$(document).on('click', '.remove-lesson-btn', function(e) {
			e.preventDefault();
			$(this).closest('.lesson-item-row').remove();
		});

		$('#guide_parent_id').on('change', toggleLessonFields);
		$('#guide_module_select').on('change', function() {
			toggleNewModuleInput();
			populateLessons(true); // User changed module -> validate and clear invalid lesson
		});
		$('#guide_lesson_select').on('change', function() {
			currentSelectedLesson = $(this).val();
			toggleNewLessonInput();
		});

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

	// Update Modules & Lessons List on Parent
	if ( isset( $_POST['guide_modules'] ) && is_array( $_POST['guide_modules'] ) ) {
		$modules_list = array();
		foreach ( $_POST['guide_modules'] as $mod ) {
			$name = isset( $mod['name'] ) ? sanitize_text_field( $mod['name'] ) : '';
			$order = isset( $mod['order'] ) ? intval( $mod['order'] ) : 0;
			if ( ! empty( $name ) ) {
				$lessons_list = array();
				if ( isset( $mod['lessons'] ) && is_array( $mod['lessons'] ) ) {
					foreach ( $mod['lessons'] as $les ) {
						$les_name = isset( $les['name'] ) ? sanitize_text_field( $les['name'] ) : '';
						$les_order = isset( $les['order'] ) ? intval( $les['order'] ) : 0;
						if ( ! empty( $les_name ) ) {
							$lessons_list[] = array(
								'name'  => $les_name,
								'order' => $les_order,
							);
						}
					}
					usort( $lessons_list, function( $a, $b ) {
						return $a['order'] - $b['order'];
					} );
				}

				$modules_list[] = array(
					'name'    => $name,
					'order'   => $order,
					'lessons' => $lessons_list,
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

	// Save Child Module & Lesson selection
	$selected_parent_id = isset( $_POST['guide_parent_id'] ) ? intval( $_POST['guide_parent_id'] ) : 0;
	$final_module_name = '';
	$final_lesson_name = '';

	if ( $selected_parent_id > 0 ) {
		$parent_modules = get_post_meta( $selected_parent_id, '_guide_modules_list', true );
		if ( ! is_array( $parent_modules ) ) {
			$parent_modules = array();
		}

		// 1. Resolve Module Name
		if ( isset( $_POST['guide_module_select'] ) && $_POST['guide_module_select'] === '__new__' && ! empty( $_POST['guide_new_module_name'] ) ) {
			$final_module_name = sanitize_text_field( $_POST['guide_new_module_name'] );

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
					if ( isset( $mod['order'] ) && $mod['order'] > $max_order ) {
						$max_order = $mod['order'];
					}
				}
				$parent_modules[] = array(
					'name'    => $final_module_name,
					'order'   => $max_order + 10,
					'lessons' => array(),
				);
				update_post_meta( $selected_parent_id, '_guide_modules_list', $parent_modules );
			}
		} else if ( isset( $_POST['guide_module_select'] ) && $_POST['guide_module_select'] !== '__new__' ) {
			$final_module_name = sanitize_text_field( $_POST['guide_module_select'] );
		}

		// 2. Resolve Lesson Name
		if ( isset( $_POST['guide_lesson_select'] ) && $_POST['guide_lesson_select'] === '__new__' && ! empty( $_POST['guide_new_lesson_name'] ) ) {
			$final_lesson_name = sanitize_text_field( $_POST['guide_new_lesson_name'] );
		} else if ( isset( $_POST['guide_lesson_select'] ) && $_POST['guide_lesson_select'] !== '__new__' ) {
			$final_lesson_name = sanitize_text_field( $_POST['guide_lesson_select'] );
		}

		// 3. Strict Module -> Lesson Dependency Validation
		if ( ! empty( $final_module_name ) ) {
			$matched_mod_key = null;
			foreach ( $parent_modules as $k => $m ) {
				if ( strcasecmp( $m['name'], $final_module_name ) === 0 ) {
					$matched_mod_key = $k;
					break;
				}
			}

			if ( $matched_mod_key !== null ) {
				$mod_lessons = isset( $parent_modules[$matched_mod_key]['lessons'] ) && is_array( $parent_modules[$matched_mod_key]['lessons'] ) ? $parent_modules[$matched_mod_key]['lessons'] : array();

				$lesson_exists = false;
				foreach ( $mod_lessons as $l ) {
					if ( strcasecmp( $l['name'], $final_lesson_name ) === 0 ) {
						$lesson_exists = true;
						break;
					}
				}

				if ( ! $lesson_exists ) {
					if ( isset( $_POST['guide_lesson_select'] ) && $_POST['guide_lesson_select'] === '__new__' && ! empty( $final_lesson_name ) ) {
						// Add new lesson to parent module's lessons list
						$max_l_order = 0;
						foreach ( $mod_lessons as $l ) {
							if ( isset( $l['order'] ) && $l['order'] > $max_l_order ) {
								$max_l_order = $l['order'];
							}
						}
						$mod_lessons[] = array(
							'name'  => $final_lesson_name,
							'order' => $max_l_order + 10,
						);
						$parent_modules[$matched_mod_key]['lessons'] = $mod_lessons;
						update_post_meta( $selected_parent_id, '_guide_modules_list', $parent_modules );
					} else {
						// Invalid combination: Lesson does NOT belong to the chosen Module!
						// Enforce integrity by falling back to the module's first valid lesson, or clearing
						if ( ! empty( $mod_lessons ) ) {
							$final_lesson_name = $mod_lessons[0]['name'];
						} else {
							$final_lesson_name = '';
						}
					}
				}
			}
		}
	}

	if ( ! empty( $final_module_name ) ) {
		update_post_meta( $post_id, '_guide_module_name', $final_module_name );
	}
	if ( ! empty( $final_lesson_name ) ) {
		update_post_meta( $post_id, '_guide_lesson_name', $final_lesson_name );
	} else {
		delete_post_meta( $post_id, '_guide_lesson_name' );
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

