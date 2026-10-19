<?php
/**
 * Program Custom Meta Boxes & Admin Fields
 *
 * @package Lohnour_Program_CMS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lohnour_Program_Meta_Boxes {

	public static function init() {
		add_action( 'add_meta_boxes_lohnour_program', array( __CLASS__, 'register_meta_boxes' ) );
		add_action( 'save_post_lohnour_program', array( __CLASS__, 'save_meta_boxes' ), 10, 2 );
	}

	public static function register_meta_boxes() {
		// 1. Program Quick Facts & Hero Settings
		add_meta_box(
			'lohnour_program_hero_meta',
			__( '1. Program Quick Facts & Hero Settings', 'lohnourpro' ),
			array( __CLASS__, 'render_hero_meta_box' ),
			'lohnour_program',
			'normal',
			'high'
		);

		// 2. Curriculum Module Builder (Core Repeater)
		add_meta_box(
			'lohnour_program_curriculum_meta',
			__( '2. Interactive Curriculum Builder (Modules 1 to N)', 'lohnourpro' ),
			array( __CLASS__, 'render_curriculum_meta_box' ),
			'lohnour_program',
			'normal',
			'high'
		);

		// 3. Program Overview & Philosophy
		add_meta_box(
			'lohnour_program_overview_meta',
			__( '3. Program Overview & Learning Philosophy', 'lohnourpro' ),
			array( __CLASS__, 'render_overview_meta_box' ),
			'lohnour_program',
			'normal',
			'default'
		);

		// 4. What You'll Build
		add_meta_box(
			'lohnour_program_build_meta',
			__( '4. What You\'ll Build (Tangible Deliverables)', 'lohnourpro' ),
			array( __CLASS__, 'render_build_meta_box' ),
			'lohnour_program',
			'normal',
			'default'
		);

		// 5. Outcomes & Skills
		add_meta_box(
			'lohnour_program_outcomes_meta',
			__( '5. Program Outcomes, Skills & Career Applications', 'lohnourpro' ),
			array( __CLASS__, 'render_outcomes_meta_box' ),
			'lohnour_program',
			'normal',
			'default'
		);

		// 6. Coaching Format & Scope Boundaries
		add_meta_box(
			'lohnour_program_format_meta',
			__( '6. Coaching Experience & Scope Boundaries', 'lohnourpro' ),
			array( __CLASS__, 'render_format_meta_box' ),
			'lohnour_program',
			'normal',
			'default'
		);

		// 7. Cohort Enrollment Card
		add_meta_box(
			'lohnour_program_enrollment_meta',
			__( '7. Cohort Enrollment & Actions', 'lohnourpro' ),
			array( __CLASS__, 'render_enrollment_meta_box' ),
			'lohnour_program',
			'normal',
			'default'
		);

		// 8. Program FAQs
		add_meta_box(
			'lohnour_program_faqs_meta',
			__( '8. Frequently Asked Questions (Accordion Builder)', 'lohnourpro' ),
			array( __CLASS__, 'render_faqs_meta_box' ),
			'lohnour_program',
			'normal',
			'default'
		);
	}

	/**
	 * 1. Render Hero Meta Box.
	 */
	public static function render_hero_meta_box( $post ) {
		wp_nonce_field( 'lohnour_program_save_meta', 'lohnour_program_meta_nonce' );

		$facts   = Lohnour_Program_Renderer::get_facts( $post->ID );
		$banners = Lohnour_Program_Renderer::get_banners( $post->ID );
		$ctas    = Lohnour_Program_Renderer::get_ctas( $post->ID );
		$desc    = get_post_meta( $post->ID, '_lohnour_program_hero_desc', true );
		if ( empty( $desc ) ) {
			$desc = $post->post_excerpt;
		}
		?>
		<div class="lohnour-admin-meta-wrap">
			<div class="form-group mb-4">
				<label><strong><?php esc_html_e( 'Hero Short Description / Excerpt', 'lohnourpro' ); ?></strong></label>
				<textarea name="lohnour_program_hero_desc" rows="2" class="large-text" placeholder="Build practical AI agents, automate workflows..."><?php echo esc_textarea( $desc ); ?></textarea>
			</div>

			<h4 class="lohnour-section-title"><?php esc_html_e( 'Program Metadata Chips (Hero Highlights)', 'lohnourpro' ); ?></h4>
			<div class="lohnour-grid-4">
				<div>
					<label><?php esc_html_e( 'Total Duration', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_facts[duration_hours]" value="<?php echo esc_attr( $facts['duration_hours'] ); ?>" class="regular-text" placeholder="30 Hours" />
				</div>
				<div>
					<label><?php esc_html_e( 'Program Length', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_facts[length_text]" value="<?php echo esc_attr( $facts['length_text'] ); ?>" class="regular-text" placeholder="~2 Months (~5 hrs/wk)" />
				</div>
				<div>
					<label><?php esc_html_e( 'Max Participants', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_facts[max_participants]" value="<?php echo esc_attr( $facts['max_participants'] ); ?>" class="regular-text" placeholder="Max 5 Seats" />
				</div>
				<div>
					<label><?php esc_html_e( 'Delivery Style', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_facts[delivery_style]" value="<?php echo esc_attr( $facts['delivery_style'] ); ?>" class="regular-text" placeholder="Live Founder-Led" />
				</div>
			</div>

			<h4 class="lohnour-section-title mt-4"><?php esc_html_e( 'Hero CTA Buttons', 'lohnourpro' ); ?></h4>
			<div class="lohnour-grid-2">
				<div class="p-3 bg-light border rounded">
					<label><strong><?php esc_html_e( 'Primary CTA Label', 'lohnourpro' ); ?></strong></label>
					<input type="text" name="lohnour_program_ctas[primary_label]" value="<?php echo esc_attr( $ctas['primary_label'] ); ?>" class="regular-text" />
					<label class="mt-2"><strong><?php esc_html_e( 'Primary CTA URL', 'lohnourpro' ); ?></strong></label>
					<input type="text" name="lohnour_program_ctas[primary_url]" value="<?php echo esc_attr( $ctas['primary_url'] ); ?>" class="regular-text" />
				</div>
				<div class="p-3 bg-light border rounded">
					<label><strong><?php esc_html_e( 'Secondary CTA Label', 'lohnourpro' ); ?></strong></label>
					<input type="text" name="lohnour_program_ctas[secondary_label]" value="<?php echo esc_attr( $ctas['secondary_label'] ); ?>" class="regular-text" />
					<label class="mt-2"><strong><?php esc_html_e( 'Secondary CTA Target (Anchor or URL)', 'lohnourpro' ); ?></strong></label>
					<input type="text" name="lohnour_program_ctas[secondary_url]" value="<?php echo esc_attr( $ctas['secondary_url'] ); ?>" class="regular-text" />
				</div>
			</div>

			<h4 class="lohnour-section-title mt-4"><?php esc_html_e( 'Theme-Aware Hero Banners (Media Library)', 'lohnourpro' ); ?></h4>
			<p class="description"><?php esc_html_e( 'Select dual hero banners to support light/dark theme switching. If left empty, default theme assets are used.', 'lohnourpro' ); ?></p>
			
			<div class="lohnour-grid-2">
				<!-- Dark Banner -->
				<div class="lohnour-media-box p-3 border rounded">
					<label><strong><?php esc_html_e( 'Dark Theme Banner (Shown in Dark Mode)', 'lohnourpro' ); ?></strong></label>
					<div class="preview-wrap mt-2 mb-2">
						<img src="<?php echo esc_url( $banners['dark_url'] ); ?>" class="lohnour-img-preview dark-preview" style="max-width:100%; height:auto; border-radius:4px; max-height:160px; object-fit:cover;" />
					</div>
					<input type="hidden" name="lohnour_program_banners[dark_id]" class="lohnour-img-id" value="<?php echo esc_attr( $banners['dark_id'] ); ?>" />
					<button type="button" class="button lohnour-upload-btn"><?php esc_html_e( 'Select Dark Banner', 'lohnourpro' ); ?></button>
					<button type="button" class="button lohnour-remove-img-btn" <?php echo empty( $banners['dark_id'] ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Remove', 'lohnourpro' ); ?></button>
				</div>

				<!-- Light Banner -->
				<div class="lohnour-media-box p-3 border rounded">
					<label><strong><?php esc_html_e( 'Light Theme Banner (Shown in Light Mode)', 'lohnourpro' ); ?></strong></label>
					<div class="preview-wrap mt-2 mb-2">
						<img src="<?php echo esc_url( $banners['light_url'] ); ?>" class="lohnour-img-preview light-preview" style="max-width:100%; height:auto; border-radius:4px; max-height:160px; object-fit:cover;" />
					</div>
					<input type="hidden" name="lohnour_program_banners[light_id]" class="lohnour-img-id" value="<?php echo esc_attr( $banners['light_id'] ); ?>" />
					<button type="button" class="button lohnour-upload-btn"><?php esc_html_e( 'Select Light Banner', 'lohnourpro' ); ?></button>
					<button type="button" class="button lohnour-remove-img-btn" <?php echo empty( $banners['light_id'] ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Remove', 'lohnourpro' ); ?></button>
				</div>
			</div>

			<div class="form-group mt-3">
				<label><?php esc_html_e( 'Banner Accessible Alt Text', 'lohnourpro' ); ?></label>
				<input type="text" name="lohnour_program_banners[alt]" value="<?php echo esc_attr( $banners['alt'] ); ?>" class="large-text" />
			</div>
		</div>
		<?php
	}

	/**
	 * 2. Render Curriculum Meta Box (Repeatable Modules 1..N).
	 */
	public static function render_curriculum_meta_box( $post ) {
		$curriculum = Lohnour_Program_Renderer::get_curriculum( $post->ID );
		$modules    = $curriculum['modules'];
		?>
		<div class="lohnour-admin-meta-wrap">
			<div class="lohnour-grid-3 mb-3">
				<div>
					<label><?php esc_html_e( 'Section Badge', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_curriculum[badge]" value="<?php echo esc_attr( $curriculum['badge'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'Section Title', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_curriculum[title]" value="<?php echo esc_attr( $curriculum['title'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'Section Subtitle', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_curriculum[subtitle]" value="<?php echo esc_attr( $curriculum['subtitle'] ); ?>" class="regular-text" />
				</div>
			</div>

			<p class="description"><?php esc_html_e( 'Add, reorder (drag handle), and edit modules below. You may add any number of modules for this program.', 'lohnourpro' ); ?></p>

			<div id="lohnour-modules-list" class="lohnour-sortable-list mt-3">
				<?php
				if ( ! empty( $modules ) ) :
					foreach ( $modules as $idx => $mod ) :
						self::render_module_item( $idx, $mod );
					endforeach;
				endif;
				?>
			</div>

			<button type="button" id="lohnour-add-module-btn" class="button button-primary button-large mt-3">
				<span class="dashicons dashicons-plus-alt2" style="vertical-align: middle;"></span>
				<?php esc_html_e( '+ Add New Module', 'lohnourpro' ); ?>
			</button>
		</div>

		<!-- Hidden Module Item Template for Dynamic JS Addition -->
		<script type="text/template" id="tmpl-lohnour-module-item">
			<?php self::render_module_item( '{{INDEX}}', array(
				'number'            => '{{NUM}}',
				'prefix'            => 'Module {{NUM_INT}} — ',
				'title'             => '',
				'duration'          => '3 Hours',
				'overview'          => '',
				'learn_topics'      => array(),
				'practical'         => '',
				'progression_pills' => array(),
				'outcome'           => '',
			) ); ?>
		</script>
		<?php
	}

	/**
	 * Render single module item row in admin.
	 */
	public static function render_module_item( $idx, $mod ) {
		$number    = isset( $mod['number'] ) ? $mod['number'] : sprintf( '%02d', (int)$idx + 1 );
		$prefix    = isset( $mod['prefix'] ) ? $mod['prefix'] : '';
		$title     = isset( $mod['title'] ) ? $mod['title'] : '';
		$duration  = isset( $mod['duration'] ) ? $mod['duration'] : '3 Hours';
		$overview  = isset( $mod['overview'] ) ? $mod['overview'] : '';
		$topics    = isset( $mod['learn_topics'] ) && is_array( $mod['learn_topics'] ) ? implode( "\n", $mod['learn_topics'] ) : '';
		$practical = isset( $mod['practical'] ) ? $mod['practical'] : '';
		$outcome   = isset( $mod['outcome'] ) ? $mod['outcome'] : '';
		?>
		<div class="lohnour-module-card border rounded mb-3 bg-white" data-index="<?php echo esc_attr( $idx ); ?>">
			<div class="lohnour-module-card-header p-2 bg-light border-bottom d-flex align-items-center" style="display:flex; justify-content:space-between; cursor:pointer;">
				<div style="display:flex; align-items:center; gap:10px;">
					<span class="dashicons dashicons-menu lohnour-drag-handle" style="cursor:move; color:#888;"></span>
					<strong class="module-header-title">
						<span class="module-badge-display"><?php echo esc_html( $number ); ?></span>
						<span class="module-title-display"><?php echo esc_html( $title ? $title : __( '(Untitled Module)', 'lohnourpro' ) ); ?></span>
					</strong>
				</div>
				<div style="display:flex; align-items:center; gap:8px;">
					<span class="module-duration-display badge" style="background:#e0e0e0; padding:2px 8px; border-radius:10px;"><?php echo esc_html( $duration ); ?></span>
					<button type="button" class="button button-small lohnour-toggle-module-btn"><?php esc_html_e( 'Edit', 'lohnourpro' ); ?></button>
					<button type="button" class="button button-small button-link-delete lohnour-remove-module-btn"><?php esc_html_e( 'Remove', 'lohnourpro' ); ?></button>
				</div>
			</div>
			<div class="lohnour-module-card-body p-3" style="display:none;">
				<div class="lohnour-grid-4 mb-2">
					<div>
						<label><?php esc_html_e( 'Module Number', 'lohnourpro' ); ?></label>
						<input type="text" name="lohnour_program_curriculum[modules][<?php echo esc_attr( $idx ); ?>][number]" value="<?php echo esc_attr( $number ); ?>" class="small-text lohnour-mod-num-input" />
					</div>
					<div>
						<label><?php esc_html_e( 'Module Prefix', 'lohnourpro' ); ?></label>
						<input type="text" name="lohnour_program_curriculum[modules][<?php echo esc_attr( $idx ); ?>][prefix]" value="<?php echo esc_attr( $prefix ); ?>" class="regular-text" placeholder="e.g. Module 1 — " />
					</div>
					<div style="grid-column: span 2;">
						<label><?php esc_html_e( 'Module Title', 'lohnourpro' ); ?></label>
						<input type="text" name="lohnour_program_curriculum[modules][<?php echo esc_attr( $idx ); ?>][title]" value="<?php echo esc_attr( $title ); ?>" class="large-text lohnour-mod-title-input" placeholder="e.g. AI, LLM & Agentic Foundations" />
					</div>
				</div>

				<div class="mb-2">
					<label><?php esc_html_e( 'Duration', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_curriculum[modules][<?php echo esc_attr( $idx ); ?>][duration]" value="<?php echo esc_attr( $duration ); ?>" class="regular-text lohnour-mod-duration-input" placeholder="e.g. 3.5 Hours" />
				</div>

				<div class="mb-3">
					<label><strong><?php esc_html_e( 'Module Overview Paragraph', 'lohnourpro' ); ?></strong></label>
					<textarea name="lohnour_program_curriculum[modules][<?php echo esc_attr( $idx ); ?>][overview]" rows="2" class="large-text"><?php echo esc_textarea( $overview ); ?></textarea>
				</div>

				<div class="mb-3">
					<label><strong><?php esc_html_e( 'Tab 1: What You\'ll Learn (Topics, one per line)', 'lohnourpro' ); ?></strong></label>
					<textarea name="lohnour_program_curriculum[modules][<?php echo esc_attr( $idx ); ?>][learn_topics]" rows="4" class="large-text" placeholder="Topic 1&#10;Topic 2&#10;Topic 3"><?php echo esc_textarea( $topics ); ?></textarea>
				</div>

				<div class="mb-3">
					<label><strong><?php esc_html_e( 'Tab 2: Practical / Hands-On Activity Description', 'lohnourpro' ); ?></strong></label>
					<textarea name="lohnour_program_curriculum[modules][<?php echo esc_attr( $idx ); ?>][practical]" rows="3" class="large-text"><?php echo esc_textarea( $practical ); ?></textarea>
				</div>

				<div class="mb-3">
					<label><strong><?php esc_html_e( 'Tab 3: Module Outcome Description', 'lohnourpro' ); ?></strong></label>
					<textarea name="lohnour_program_curriculum[modules][<?php echo esc_attr( $idx ); ?>][outcome]" rows="3" class="large-text"><?php echo esc_textarea( $outcome ); ?></textarea>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * 3. Render Overview Meta Box.
	 */
	public static function render_overview_meta_box( $post ) {
		$overview = Lohnour_Program_Renderer::get_overview( $post->ID );
		$why      = Lohnour_Program_Renderer::get_why( $post->ID );
		?>
		<div class="lohnour-admin-meta-wrap">
			<h4 class="lohnour-section-title"><?php esc_html_e( 'Section Heading (Overview)', 'lohnourpro' ); ?></h4>
			<div class="lohnour-grid-3 mb-3">
				<div>
					<label><?php esc_html_e( 'Badge', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_overview[badge]" value="<?php echo esc_attr( $overview['badge'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'Title', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_overview[title]" value="<?php echo esc_attr( $overview['title'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'Subtitle', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_overview[subtitle]" value="<?php echo esc_attr( $overview['subtitle'] ); ?>" class="regular-text" />
				</div>
			</div>

			<div class="mb-3">
				<label><strong><?php esc_html_e( 'Main Card Title', 'lohnourpro' ); ?></strong></label>
				<input type="text" name="lohnour_program_overview[main_h3]" value="<?php echo esc_attr( $overview['main_h3'] ); ?>" class="large-text" />
			</div>

			<div class="mb-3">
				<label><strong><?php esc_html_e( 'Main Paragraphs (One per paragraph block)', 'lohnourpro' ); ?></strong></label>
				<textarea name="lohnour_program_overview[paragraphs_raw]" rows="5" class="large-text"><?php echo esc_textarea( implode( "\n\n", (array) $overview['paragraphs'] ) ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Separate paragraphs with blank lines.', 'lohnourpro' ); ?></p>
			</div>

			<h4 class="lohnour-section-title mt-4"><?php esc_html_e( 'Key Cohort Highlights (Right Column Cards)', 'lohnourpro' ); ?></h4>
			<div class="mb-3">
				<label><strong><?php esc_html_e( 'Highlights Section Heading', 'lohnourpro' ); ?></strong></label>
				<input type="text" name="lohnour_program_overview[highlights_h3]" value="<?php echo esc_attr( $overview['highlights_h3'] ?? __( 'Key Cohort Highlights', 'lohnourpro' ) ); ?>" class="regular-text" />
			</div>

			<div id="lohnour-highlights-list" class="lohnour-sortable-list mt-3">
				<?php
				$highlights = (array) ( $overview['highlights'] ?? array() );
				if ( ! empty( $highlights ) ) :
					foreach ( $highlights as $h_idx => $hl ) :
						self::render_highlight_item( $h_idx, $hl );
					endforeach;
				endif;
				?>
			</div>

			<button type="button" id="lohnour-add-highlight-btn" class="button button-primary button-large mt-3">
				<span class="dashicons dashicons-plus-alt2" style="vertical-align: middle;"></span>
				<?php esc_html_e( '+ Add New Highlight', 'lohnourpro' ); ?>
			</button>

			<!-- Hidden Highlight Template -->
			<script type="text/template" id="tmpl-lohnour-highlight-item">
				<?php self::render_highlight_item( '{{INDEX}}', array(
					'title' => '',
					'desc'  => '',
				) ); ?>
			</script>

			<h4 class="lohnour-section-title mt-4"><?php esc_html_e( 'Why This Program (Learning Philosophy)', 'lohnourpro' ); ?></h4>
			<div class="lohnour-grid-3 mb-3">
				<div>
					<label><?php esc_html_e( 'Philosophy Badge', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_why[badge]" value="<?php echo esc_attr( $why['badge'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'Philosophy Title', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_why[title]" value="<?php echo esc_attr( $why['title'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'Philosophy Subtitle', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_why[subtitle]" value="<?php echo esc_attr( $why['subtitle'] ); ?>" class="regular-text" />
				</div>
			</div>

			<div class="mb-3">
				<label><strong><?php esc_html_e( 'Philosophy Lead Text', 'lohnourpro' ); ?></strong></label>
				<textarea name="lohnour_program_why[lead_text]" rows="3" class="large-text"><?php echo esc_textarea( $why['lead_text'] ); ?></textarea>
			</div>
		</div>
		<?php
	}

	public static function render_highlight_item( $idx, $hl ) {
		$title = isset( $hl['title'] ) ? $hl['title'] : '';
		$desc  = isset( $hl['desc'] ) ? $hl['desc'] : '';
		?>
		<div class="lohnour-highlight-card border rounded mb-2 bg-white" data-index="<?php echo esc_attr( $idx ); ?>">
			<div class="lohnour-highlight-card-header p-2 bg-light border-bottom d-flex align-items-center" style="display:flex; justify-content:space-between; align-items:center;">
				<div style="display:flex; align-items:center; gap:10px;">
					<span class="dashicons dashicons-menu lohnour-drag-handle" style="cursor:move; color:#888;"></span>
					<strong class="highlight-header-title"><?php echo esc_html( $title ? $title : __( '(New Highlight)', 'lohnourpro' ) ); ?></strong>
				</div>
				<div>
					<button type="button" class="button button-small button-link-delete lohnour-remove-highlight-btn"><?php esc_html_e( 'Remove', 'lohnourpro' ); ?></button>
				</div>
			</div>
			<div class="lohnour-highlight-card-body p-3">
				<div class="mb-2">
					<label><strong><?php esc_html_e( 'Highlight Title', 'lohnourpro' ); ?></strong></label>
					<input type="text" name="lohnour_program_overview[highlights][<?php echo esc_attr( $idx ); ?>][title]" value="<?php echo esc_attr( $title ); ?>" class="large-text lohnour-highlight-title-input" placeholder="e.g. Build-First Pedagogy" />
				</div>
				<div>
					<label><strong><?php esc_html_e( 'Highlight Description', 'lohnourpro' ); ?></strong></label>
					<textarea name="lohnour_program_overview[highlights][<?php echo esc_attr( $idx ); ?>][desc]" rows="2" class="large-text" placeholder="Description of the highlight..."><?php echo esc_textarea( $desc ); ?></textarea>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * 4. Render Build Meta Box.
	 */
	public static function render_build_meta_box( $post ) {
		$build = Lohnour_Program_Renderer::get_build( $post->ID );
		$cards = $build['cards'];
		?>
		<div class="lohnour-admin-meta-wrap">
			<div class="lohnour-grid-3 mb-3">
				<div>
					<label><?php esc_html_e( 'Badge', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_build[badge]" value="<?php echo esc_attr( $build['badge'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'Title', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_build[title]" value="<?php echo esc_attr( $build['title'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'Subtitle', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_build[subtitle]" value="<?php echo esc_attr( $build['subtitle'] ); ?>" class="regular-text" />
				</div>
			</div>

			<div id="lohnour-build-cards-list">
				<?php foreach ( $cards as $idx => $card ) : ?>
					<div class="border rounded p-2 mb-2 bg-light" style="display:flex; gap:10px; align-items:center;">
						<input type="text" name="lohnour_program_build[cards][<?php echo esc_attr( $idx ); ?>][step]" value="<?php echo esc_attr( $card['step'] ); ?>" class="small-text" style="width:50px;" />
						<input type="text" name="lohnour_program_build[cards][<?php echo esc_attr( $idx ); ?>][title]" value="<?php echo esc_attr( $card['title'] ); ?>" class="regular-text" style="width:250px;" />
						<input type="text" name="lohnour_program_build[cards][<?php echo esc_attr( $idx ); ?>][desc]" value="<?php echo esc_attr( $card['desc'] ); ?>" class="large-text" />
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * 5. Render Outcomes & Skills Meta Box.
	 */
	public static function render_outcomes_meta_box( $post ) {
		$outcomes = Lohnour_Program_Renderer::get_outcomes( $post->ID );
		$skills   = Lohnour_Program_Renderer::get_skills( $post->ID );
		$career   = Lohnour_Program_Renderer::get_career( $post->ID );
		?>
		<div class="lohnour-admin-meta-wrap">
			<div class="mb-3">
				<label><strong><?php esc_html_e( 'North Star Statement Quote', 'lohnourpro' ); ?></strong></label>
				<textarea name="lohnour_program_outcomes[north_star_quote]" rows="3" class="large-text"><?php echo esc_textarea( $outcomes['north_star_quote'] ); ?></textarea>
			</div>

			<h4 class="lohnour-section-title"><?php esc_html_e( 'Concrete Capabilities (4 Core Competencies)', 'lohnourpro' ); ?></h4>
			<?php foreach ( (array) $outcomes['items'] as $idx => $item ) : ?>
				<div class="border rounded p-2 mb-2 bg-light" style="display:flex; gap:10px;">
					<input type="text" name="lohnour_program_outcomes[items][<?php echo esc_attr( $idx ); ?>][title]" value="<?php echo esc_attr( $item['title'] ); ?>" class="regular-text" style="width:250px;" />
					<input type="text" name="lohnour_program_outcomes[items][<?php echo esc_attr( $idx ); ?>][desc]" value="<?php echo esc_attr( $item['desc'] ); ?>" class="large-text" />
				</div>
			<?php endforeach; ?>

			<h4 class="lohnour-section-title mt-4"><?php esc_html_e( 'Skills You\'ll Build (Comma-separated Tags)', 'lohnourpro' ); ?></h4>
			<textarea name="lohnour_program_skills[tags_raw]" rows="2" class="large-text"><?php echo esc_textarea( implode( ', ', (array) $skills['tags'] ) ); ?></textarea>
		</div>
		<?php
	}

	/**
	 * 6. Render Format & Scope Meta Box.
	 */
	public static function render_format_meta_box( $post ) {
		$format = Lohnour_Program_Renderer::get_format( $post->ID );
		$scope  = Lohnour_Program_Renderer::get_scope( $post->ID );
		?>
		<div class="lohnour-admin-meta-wrap">
			<h4 class="lohnour-section-title"><?php esc_html_e( 'How Coaching Works (4 Steps)', 'lohnourpro' ); ?></h4>
			<?php foreach ( (array) $format['steps'] as $idx => $st ) : ?>
				<div class="border rounded p-2 mb-2 bg-light" style="display:flex; gap:10px; align-items:center;">
					<input type="text" name="lohnour_program_format[steps][<?php echo esc_attr( $idx ); ?>][num]" value="<?php echo esc_attr( $st['num'] ); ?>" class="small-text" style="width:50px;" />
					<input type="text" name="lohnour_program_format[steps][<?php echo esc_attr( $idx ); ?>][title]" value="<?php echo esc_attr( $st['title'] ); ?>" class="regular-text" style="width:250px;" />
					<input type="text" name="lohnour_program_format[steps][<?php echo esc_attr( $idx ); ?>][desc]" value="<?php echo esc_attr( $st['desc'] ); ?>" class="large-text" />
				</div>
			<?php endforeach; ?>

			<h4 class="lohnour-section-title mt-4"><?php esc_html_e( 'Scope & Boundaries', 'lohnourpro' ); ?></h4>
			<div class="lohnour-grid-2">
				<div>
					<label><strong><?php esc_html_e( 'What\'s Included (One per line)', 'lohnourpro' ); ?></strong></label>
					<textarea name="lohnour_program_scope[included_raw]" rows="6" class="large-text"><?php echo esc_textarea( implode( "\n", (array) $scope['included'] ) ); ?></textarea>
				</div>
				<div>
					<label><strong><?php esc_html_e( 'What\'s NOT Included (One per line)', 'lohnourpro' ); ?></strong></label>
					<textarea name="lohnour_program_scope[excluded_raw]" rows="6" class="large-text"><?php echo esc_textarea( implode( "\n", (array) $scope['excluded'] ) ); ?></textarea>
				</div>
			</div>
			<div class="mt-2">
				<label><?php esc_html_e( 'Scope Note / Disclaimer', 'lohnourpro' ); ?></label>
				<input type="text" name="lohnour_program_scope[scope_note]" value="<?php echo esc_attr( $scope['scope_note'] ); ?>" class="large-text" />
			</div>
		</div>
		<?php
	}

	/**
	 * 7. Render Enrollment Meta Box.
	 */
	public static function render_enrollment_meta_box( $post ) {
		$enrollment = Lohnour_Program_Renderer::get_enrollment( $post->ID );
		?>
		<div class="lohnour-admin-meta-wrap">
			<div class="lohnour-grid-2 mb-3">
				<div>
					<label><?php esc_html_e( 'Intake Badge', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_enrollment[badge]" value="<?php echo esc_attr( $enrollment['badge'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'Intake Title', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_enrollment[title]" value="<?php echo esc_attr( $enrollment['title'] ); ?>" class="regular-text" />
				</div>
			</div>
			<div class="mb-3">
				<label><?php esc_html_e( 'Enrollment Description', 'lohnourpro' ); ?></label>
				<textarea name="lohnour_program_enrollment[desc]" rows="2" class="large-text"><?php echo esc_textarea( $enrollment['desc'] ); ?></textarea>
			</div>
			<div class="mb-3">
				<label><?php esc_html_e( 'Callout Pills (Comma-separated)', 'lohnourpro' ); ?></label>
				<input type="text" name="lohnour_program_enrollment[pills_raw]" value="<?php echo esc_attr( implode( ', ', (array) $enrollment['pills'] ) ); ?>" class="large-text" />
			</div>
			<div class="lohnour-grid-2 mb-3">
				<div>
					<label><?php esc_html_e( 'CTA Label', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_enrollment[cta_label]" value="<?php echo esc_attr( $enrollment['cta_label'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'CTA URL', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_enrollment[cta_url]" value="<?php echo esc_attr( $enrollment['cta_url'] ); ?>" class="regular-text" />
				</div>
			</div>
			<div>
				<label><?php esc_html_e( 'Enrollment Subtext', 'lohnourpro' ); ?></label>
				<input type="text" name="lohnour_program_enrollment[subtext]" value="<?php echo esc_attr( $enrollment['subtext'] ); ?>" class="large-text" />
			</div>
		</div>
		<?php
	}

	/**
	 * 8. Render FAQs Meta Box.
	 */
	public static function render_faqs_meta_box( $post ) {
		$faqs_data = Lohnour_Program_Renderer::get_faqs( $post->ID );
		$items     = $faqs_data['items'];
		?>
		<div class="lohnour-admin-meta-wrap">
			<div class="lohnour-grid-3 mb-3">
				<div>
					<label><?php esc_html_e( 'FAQ Section Badge', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_faqs[badge]" value="<?php echo esc_attr( $faqs_data['badge'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'FAQ Section Title', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_faqs[title]" value="<?php echo esc_attr( $faqs_data['title'] ); ?>" class="regular-text" />
				</div>
				<div>
					<label><?php esc_html_e( 'FAQ Section Subtitle', 'lohnourpro' ); ?></label>
					<input type="text" name="lohnour_program_faqs[subtitle]" value="<?php echo esc_attr( $faqs_data['subtitle'] ); ?>" class="regular-text" />
				</div>
			</div>

			<div id="lohnour-faqs-list" class="lohnour-sortable-list mt-3">
				<?php
				if ( ! empty( $items ) ) :
					foreach ( $items as $idx => $faq ) :
						self::render_faq_item( $idx, $faq );
					endforeach;
				endif;
				?>
			</div>

			<button type="button" id="lohnour-add-faq-btn" class="button button-primary button-large mt-3">
				<span class="dashicons dashicons-plus-alt2" style="vertical-align: middle;"></span>
				<?php esc_html_e( '+ Add New FAQ Item', 'lohnourpro' ); ?>
			</button>
		</div>

		<!-- Hidden FAQ Template -->
		<script type="text/template" id="tmpl-lohnour-faq-item">
			<?php self::render_faq_item( '{{INDEX}}', array(
				'question' => '',
				'answer'   => '',
			) ); ?>
		</script>
		<?php
	}

	public static function render_faq_item( $idx, $faq ) {
		$question = isset( $faq['question'] ) ? $faq['question'] : '';
		$answer   = isset( $faq['answer'] ) ? $faq['answer'] : '';
		?>
		<div class="lohnour-faq-card border rounded mb-2 bg-white" data-index="<?php echo esc_attr( $idx ); ?>">
			<div class="lohnour-faq-card-header p-2 bg-light border-bottom d-flex align-items-center" style="display:flex; justify-content:space-between; cursor:pointer;">
				<div style="display:flex; align-items:center; gap:10px;">
					<span class="dashicons dashicons-menu lohnour-drag-handle" style="cursor:move; color:#888;"></span>
					<strong class="faq-header-title"><?php echo esc_html( $question ? $question : __( '(New FAQ Item)', 'lohnourpro' ) ); ?></strong>
				</div>
				<div>
					<button type="button" class="button button-small lohnour-toggle-faq-btn"><?php esc_html_e( 'Edit', 'lohnourpro' ); ?></button>
					<button type="button" class="button button-small button-link-delete lohnour-remove-faq-btn"><?php esc_html_e( 'Remove', 'lohnourpro' ); ?></button>
				</div>
			</div>
			<div class="lohnour-faq-card-body p-3" style="display:none;">
				<div class="mb-2">
					<label><strong><?php esc_html_e( 'Question', 'lohnourpro' ); ?></strong></label>
					<input type="text" name="lohnour_program_faqs[items][<?php echo esc_attr( $idx ); ?>][question]" value="<?php echo esc_attr( $question ); ?>" class="large-text lohnour-faq-question-input" />
				</div>
				<div>
					<label><strong><?php esc_html_e( 'Answer', 'lohnourpro' ); ?></strong></label>
					<textarea name="lohnour_program_faqs[items][<?php echo esc_attr( $idx ); ?>][answer]" rows="3" class="large-text"><?php echo esc_textarea( $answer ); ?></textarea>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Save Meta Box Data.
	 */
	public static function save_meta_boxes( $post_id, $post ) {
		if ( ! isset( $_POST['lohnour_program_meta_nonce'] ) || ! wp_verify_nonce( $_POST['lohnour_program_meta_nonce'], 'lohnour_program_save_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// 1. Hero Description
		if ( isset( $_POST['lohnour_program_hero_desc'] ) ) {
			update_post_meta( $post_id, '_lohnour_program_hero_desc', sanitize_textarea_field( wp_unslash( $_POST['lohnour_program_hero_desc'] ) ) );
		}

		// 2. Facts
		if ( isset( $_POST['lohnour_program_facts'] ) && is_array( $_POST['lohnour_program_facts'] ) ) {
			$facts = array(
				'duration_hours'   => sanitize_text_field( wp_unslash( $_POST['lohnour_program_facts']['duration_hours'] ?? '' ) ),
				'length_text'      => sanitize_text_field( wp_unslash( $_POST['lohnour_program_facts']['length_text'] ?? '' ) ),
				'max_participants' => sanitize_text_field( wp_unslash( $_POST['lohnour_program_facts']['max_participants'] ?? '' ) ),
				'delivery_style'   => sanitize_text_field( wp_unslash( $_POST['lohnour_program_facts']['delivery_style'] ?? '' ) ),
			);
			update_post_meta( $post_id, '_lohnour_program_facts', $facts );
		}

		// 3. CTAs
		if ( isset( $_POST['lohnour_program_ctas'] ) && is_array( $_POST['lohnour_program_ctas'] ) ) {
			$ctas = array(
				'primary_label'   => sanitize_text_field( wp_strip_all_tags( wp_unslash( $_POST['lohnour_program_ctas']['primary_label'] ?? '' ) ) ),
				'primary_url'     => esc_url_raw( wp_unslash( $_POST['lohnour_program_ctas']['primary_url'] ?? '' ) ),
				'secondary_label' => sanitize_text_field( wp_strip_all_tags( wp_unslash( $_POST['lohnour_program_ctas']['secondary_label'] ?? '' ) ) ),
				'secondary_url'   => sanitize_text_field( wp_unslash( $_POST['lohnour_program_ctas']['secondary_url'] ?? '' ) ),
			);
			update_post_meta( $post_id, '_lohnour_program_ctas', $ctas );
		}

		// 4. Banners
		if ( isset( $_POST['lohnour_program_banners'] ) && is_array( $_POST['lohnour_program_banners'] ) ) {
			$banners = array(
				'dark_id'  => absint( $_POST['lohnour_program_banners']['dark_id'] ?? 0 ),
				'light_id' => absint( $_POST['lohnour_program_banners']['light_id'] ?? 0 ),
				'alt'      => sanitize_text_field( wp_unslash( $_POST['lohnour_program_banners']['alt'] ?? '' ) ),
			);
			update_post_meta( $post_id, '_lohnour_program_banners', $banners );
		}

		// 5. Curriculum Modules (Ordered Repeater)
		if ( isset( $_POST['lohnour_program_curriculum'] ) && is_array( $_POST['lohnour_program_curriculum'] ) ) {
			$curr_input = $_POST['lohnour_program_curriculum'];
			$modules    = array();

			if ( ! empty( $curr_input['modules'] ) && is_array( $curr_input['modules'] ) ) {
				foreach ( $curr_input['modules'] as $m ) {
					// Topics
					$raw_topics = isset( $m['learn_topics'] ) ? (string) $m['learn_topics'] : '';
					$topics     = array_filter( array_map( 'trim', explode( "\n", $raw_topics ) ) );

					$modules[] = array(
						'number'       => sanitize_text_field( wp_unslash( $m['number'] ?? '' ) ),
						'prefix'       => sanitize_text_field( wp_unslash( $m['prefix'] ?? '' ) ),
						'title'        => sanitize_text_field( wp_unslash( $m['title'] ?? '' ) ),
						'duration'     => sanitize_text_field( wp_unslash( $m['duration'] ?? '' ) ),
						'overview'     => wp_kses_post( wp_unslash( $m['overview'] ?? '' ) ),
						'learn_topics' => array_map( 'sanitize_text_field', $topics ),
						'practical'    => wp_kses_post( wp_unslash( $m['practical'] ?? '' ) ),
						'outcome'      => wp_kses_post( wp_unslash( $m['outcome'] ?? '' ) ),
					);
				}
			}

			$curriculum_data = array(
				'badge'    => sanitize_text_field( wp_unslash( $curr_input['badge'] ?? '' ) ),
				'title'    => sanitize_text_field( wp_unslash( $curr_input['title'] ?? '' ) ),
				'subtitle' => sanitize_text_field( wp_unslash( $curr_input['subtitle'] ?? '' ) ),
				'modules'  => $modules,
			);
			update_post_meta( $post_id, '_lohnour_program_curriculum', $curriculum_data );
		}

		// 6. Overview
		if ( isset( $_POST['lohnour_program_overview'] ) && is_array( $_POST['lohnour_program_overview'] ) ) {
			$ov = $_POST['lohnour_program_overview'];
			$raw_ps = isset( $ov['paragraphs_raw'] ) ? (string) $ov['paragraphs_raw'] : '';
			$ps = array_filter( array_map( 'trim', explode( "\n\n", $raw_ps ) ) );

			$overview_data = Lohnour_Program_Renderer::get_overview( $post_id );
			$overview_data['badge']         = sanitize_text_field( wp_unslash( $ov['badge'] ?? '' ) );
			$overview_data['title']         = sanitize_text_field( wp_unslash( $ov['title'] ?? '' ) );
			$overview_data['subtitle']      = sanitize_text_field( wp_unslash( $ov['subtitle'] ?? '' ) );
			$overview_data['main_h3']       = sanitize_text_field( wp_unslash( $ov['main_h3'] ?? '' ) );
			$overview_data['paragraphs']    = array_map( 'wp_kses_post', $ps );
			$overview_data['highlights_h3'] = sanitize_text_field( wp_unslash( $ov['highlights_h3'] ?? '' ) );

			$clean_highlights = array();
			if ( ! empty( $ov['highlights'] ) && is_array( $ov['highlights'] ) ) {
				foreach ( $ov['highlights'] as $hl ) {
					$h_title = sanitize_text_field( wp_unslash( $hl['title'] ?? '' ) );
					$h_desc  = sanitize_text_field( wp_unslash( $hl['desc'] ?? '' ) );
					if ( ! empty( $h_title ) || ! empty( $h_desc ) ) {
						$clean_highlights[] = array(
							'title' => $h_title,
							'desc'  => $h_desc,
						);
					}
				}
			}
			$overview_data['highlights'] = $clean_highlights;
			update_post_meta( $post_id, '_lohnour_program_overview', $overview_data );
		}

		// 7. Why
		if ( isset( $_POST['lohnour_program_why'] ) && is_array( $_POST['lohnour_program_why'] ) ) {
			$why = $_POST['lohnour_program_why'];
			$why_data = Lohnour_Program_Renderer::get_why( $post_id );
			$why_data['badge']     = sanitize_text_field( wp_unslash( $why['badge'] ?? '' ) );
			$why_data['title']     = sanitize_text_field( wp_unslash( $why['title'] ?? '' ) );
			$why_data['subtitle']  = sanitize_text_field( wp_unslash( $why['subtitle'] ?? '' ) );
			$why_data['lead_text'] = wp_kses_post( wp_unslash( $why['lead_text'] ?? '' ) );
			update_post_meta( $post_id, '_lohnour_program_why', $why_data );
		}

		// 8. Build
		if ( isset( $_POST['lohnour_program_build'] ) && is_array( $_POST['lohnour_program_build'] ) ) {
			$b = $_POST['lohnour_program_build'];
			$build_data = Lohnour_Program_Renderer::get_build( $post_id );
			$build_data['badge']    = sanitize_text_field( wp_unslash( $b['badge'] ?? '' ) );
			$build_data['title']    = sanitize_text_field( wp_unslash( $b['title'] ?? '' ) );
			$build_data['subtitle'] = sanitize_text_field( wp_unslash( $b['subtitle'] ?? '' ) );
			if ( ! empty( $b['cards'] ) && is_array( $b['cards'] ) ) {
				$clean_cards = array();
				foreach ( $b['cards'] as $c ) {
					$clean_cards[] = array(
						'step'  => sanitize_text_field( wp_unslash( $c['step'] ?? '' ) ),
						'title' => sanitize_text_field( wp_unslash( $c['title'] ?? '' ) ),
						'desc'  => sanitize_text_field( wp_unslash( $c['desc'] ?? '' ) ),
					);
				}
				$build_data['cards'] = $clean_cards;
			}
			update_post_meta( $post_id, '_lohnour_program_build', $build_data );
		}

		// 9. Outcomes
		if ( isset( $_POST['lohnour_program_outcomes'] ) && is_array( $_POST['lohnour_program_outcomes'] ) ) {
			$out = $_POST['lohnour_program_outcomes'];
			$out_data = Lohnour_Program_Renderer::get_outcomes( $post_id );
			$out_data['north_star_quote'] = wp_kses_post( wp_unslash( $out['north_star_quote'] ?? '' ) );
			if ( ! empty( $out['items'] ) && is_array( $out['items'] ) ) {
				$clean_items = array();
				foreach ( $out['items'] as $it ) {
					$clean_items[] = array(
						'title' => sanitize_text_field( wp_unslash( $it['title'] ?? '' ) ),
						'desc'  => sanitize_text_field( wp_unslash( $it['desc'] ?? '' ) ),
					);
				}
				$out_data['items'] = $clean_items;
			}
			update_post_meta( $post_id, '_lohnour_program_outcomes', $out_data );
		}

		// 10. Skills
		if ( isset( $_POST['lohnour_program_skills'] ) && is_array( $_POST['lohnour_program_skills'] ) ) {
			$sk = $_POST['lohnour_program_skills'];
			$sk_data = Lohnour_Program_Renderer::get_skills( $post_id );
			$raw_tags = isset( $sk['tags_raw'] ) ? (string) $sk['tags_raw'] : '';
			$tags = array_filter( array_map( 'trim', explode( ',', $raw_tags ) ) );
			$sk_data['tags'] = array_map( 'sanitize_text_field', $tags );
			update_post_meta( $post_id, '_lohnour_program_skills', $sk_data );
		}

		// 11. Format & Scope
		if ( isset( $_POST['lohnour_program_format'] ) && is_array( $_POST['lohnour_program_format'] ) ) {
			$fmt = $_POST['lohnour_program_format'];
			$fmt_data = Lohnour_Program_Renderer::get_format( $post_id );
			if ( ! empty( $fmt['steps'] ) && is_array( $fmt['steps'] ) ) {
				$clean_steps = array();
				foreach ( $fmt['steps'] as $s ) {
					$clean_steps[] = array(
						'num'   => sanitize_text_field( wp_unslash( $s['num'] ?? '' ) ),
						'title' => sanitize_text_field( wp_unslash( $s['title'] ?? '' ) ),
						'desc'  => sanitize_text_field( wp_unslash( $s['desc'] ?? '' ) ),
					);
				}
				$fmt_data['steps'] = $clean_steps;
			}
			update_post_meta( $post_id, '_lohnour_program_format', $fmt_data );
		}

		if ( isset( $_POST['lohnour_program_scope'] ) && is_array( $_POST['lohnour_program_scope'] ) ) {
			$sc = $_POST['lohnour_program_scope'];
			$sc_data = Lohnour_Program_Renderer::get_scope( $post_id );
			$inc_raw = isset( $sc['included_raw'] ) ? (string) $sc['included_raw'] : '';
			$exc_raw = isset( $sc['excluded_raw'] ) ? (string) $sc['excluded_raw'] : '';
			$sc_data['included']   = array_filter( array_map( 'trim', explode( "\n", $inc_raw ) ) );
			$sc_data['excluded']   = array_filter( array_map( 'trim', explode( "\n", $exc_raw ) ) );
			$sc_data['scope_note'] = sanitize_text_field( wp_unslash( $sc['scope_note'] ?? '' ) );
			update_post_meta( $post_id, '_lohnour_program_scope', $sc_data );
		}

		// 12. Enrollment
		if ( isset( $_POST['lohnour_program_enrollment'] ) && is_array( $_POST['lohnour_program_enrollment'] ) ) {
			$en = $_POST['lohnour_program_enrollment'];
			$raw_pills = isset( $en['pills_raw'] ) ? (string) $en['pills_raw'] : '';
			$pills = array_filter( array_map( 'trim', explode( ',', $raw_pills ) ) );

			$enroll_data = array(
				'badge'     => sanitize_text_field( wp_unslash( $en['badge'] ?? '' ) ),
				'title'     => sanitize_text_field( wp_unslash( $en['title'] ?? '' ) ),
				'desc'      => wp_kses_post( wp_unslash( $en['desc'] ?? '' ) ),
				'pills'     => array_map( 'sanitize_text_field', $pills ),
				'cta_label' => sanitize_text_field( wp_strip_all_tags( wp_unslash( $en['cta_label'] ?? '' ) ) ),
				'cta_url'   => esc_url_raw( wp_unslash( $en['cta_url'] ?? '' ) ),
				'subtext'   => sanitize_text_field( wp_unslash( $en['subtext'] ?? '' ) ),
			);
			update_post_meta( $post_id, '_lohnour_program_enrollment', $enroll_data );
		}

		// 12b. Closing CTA
		if ( isset( $_POST['lohnour_program_closing_cta'] ) && is_array( $_POST['lohnour_program_closing_cta'] ) ) {
			$cl = $_POST['lohnour_program_closing_cta'];
			$closing_data = array(
				'title'     => sanitize_text_field( wp_unslash( $cl['title'] ?? '' ) ),
				'desc'      => wp_kses_post( wp_unslash( $cl['desc'] ?? '' ) ),
				'cta_label' => sanitize_text_field( wp_strip_all_tags( wp_unslash( $cl['cta_label'] ?? '' ) ) ),
				'cta_url'   => esc_url_raw( wp_unslash( $cl['cta_url'] ?? '' ) ),
			);
			update_post_meta( $post_id, '_lohnour_program_closing_cta', $closing_data );
		}

		// 13. FAQs
		if ( isset( $_POST['lohnour_program_faqs'] ) && is_array( $_POST['lohnour_program_faqs'] ) ) {
			$fq = $_POST['lohnour_program_faqs'];
			$clean_faqs = array();

			if ( ! empty( $fq['items'] ) && is_array( $fq['items'] ) ) {
				foreach ( $fq['items'] as $item ) {
					$q = sanitize_text_field( wp_unslash( $item['question'] ?? '' ) );
					$a = wp_kses_post( wp_unslash( $item['answer'] ?? '' ) );
					if ( ! empty( $q ) ) {
						$clean_faqs[] = array(
							'question' => $q,
							'answer'   => $a,
						);
					}
				}
			}

			$faqs_data = array(
				'badge'    => sanitize_text_field( wp_unslash( $fq['badge'] ?? '' ) ),
				'title'    => sanitize_text_field( wp_unslash( $fq['title'] ?? '' ) ),
				'subtitle' => sanitize_text_field( wp_unslash( $fq['subtitle'] ?? '' ) ),
				'items'    => $clean_faqs,
			);
			update_post_meta( $post_id, '_lohnour_program_faqs', $faqs_data );
		}
	}
}
