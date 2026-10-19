<?php
/**
 * Program Helper Functions & Data Getters
 *
 * @package Lohnour_Program_CMS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lohnour_Program_Renderer {

	/**
	 * Get Program Quick Facts.
	 */
	public static function get_facts( $post_id ) {
		$facts = get_post_meta( $post_id, '_lohnour_program_facts', true );
		if ( ! is_array( $facts ) ) {
			$facts = array();
		}
		return wp_parse_args( $facts, array(
			'duration_hours'   => '30 Hours',
			'length_text'      => '~2 Months (~5 hrs/wk)',
			'max_participants' => 'Max 5 Seats',
			'delivery_style'   => 'Live Founder-Led',
		) );
	}

	/**
	 * Get Theme-Aware Hero Banners.
	 */
	public static function get_banners( $post_id ) {
		$banners = get_post_meta( $post_id, '_lohnour_program_banners', true );
		if ( ! is_array( $banners ) ) {
			$banners = array();
		}

		$defaults = array(
			'dark_id'   => 0,
			'light_id'  => 0,
			'alt'       => get_the_title( $post_id ) . ' participants building systems',
		);
		$data = wp_parse_args( $banners, $defaults );

		// Resolve Dark URL
		$dark_url = '';
		if ( ! empty( $data['dark_id'] ) ) {
			$dark_url = wp_get_attachment_image_url( $data['dark_id'], 'full' );
		}
		if ( empty( $dark_url ) ) {
			$dark_url = get_stylesheet_directory_uri() . '/assets/images/agentic-ai-foundations-dark.png';
		}

		// Resolve Light URL
		$light_url = '';
		if ( ! empty( $data['light_id'] ) ) {
			$light_url = wp_get_attachment_image_url( $data['light_id'], 'full' );
		}
		if ( empty( $light_url ) ) {
			$light_url = get_stylesheet_directory_uri() . '/assets/images/agentic-ai-foundations-light.jpeg';
		}

		$data['dark_url']  = $dark_url;
		$data['light_url'] = $light_url;

		return $data;
	}

	/**
	 * Get CTAs.
	 */
	public static function get_ctas( $post_id ) {
		$ctas = get_post_meta( $post_id, '_lohnour_program_ctas', true );
		if ( ! is_array( $ctas ) ) {
			$ctas = array();
		}
		return wp_parse_args( $ctas, array(
			'primary_label'   => __( 'Enquire About Next Cohort', 'lohnourpro' ),
			'primary_url'     => home_url( '/contact' ),
			'secondary_label' => __( 'Explore 30-Hour Curriculum ↓', 'lohnourpro' ),
			'secondary_url'   => '#curriculum',
		) );
	}

	/**
	 * Get Overview Section Data.
	 */
	public static function get_overview( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_overview', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		return wp_parse_args( $data, array(
			'badge'         => __( 'Foundational Architecture', 'lohnourpro' ),
			'title'         => __( 'Program Overview', 'lohnourpro' ),
			'subtitle'      => __( 'A practical, build-first coaching cohort designed to take you from foundational concepts to working autonomous agent architectures.', 'lohnourpro' ),
			'main_h3'       => __( 'What is this program?', 'lohnourpro' ),
			'paragraphs'    => array(),
			'highlights_h3' => __( 'Key Cohort Highlights', 'lohnourpro' ),
			'highlights'    => array(),
		) );
	}

	/**
	 * Get Why This Program Data.
	 */
	public static function get_why( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_why', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		return wp_parse_args( $data, array(
			'badge'      => __( 'The Learning Philosophy', 'lohnourpro' ),
			'title'      => __( 'Why This Program', 'lohnourpro' ),
			'subtitle'   => __( 'A disciplined conceptual journey from basic prompting to autonomous orchestration.', 'lohnourpro' ),
			'lead_text'  => '',
			'flow_steps' => array(),
			'columns'    => array(),
		) );
	}

	/**
	 * Get Curriculum Modules.
	 */
	public static function get_curriculum( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_curriculum', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		return wp_parse_args( $data, array(
			'badge'    => __( 'Interactive Detailed Breakdown', 'lohnourpro' ),
			'title'    => __( '30-Hour Curriculum', 'lohnourpro' ),
			'subtitle' => __( 'Explore the full week-by-week module syllabus. Inspect topics, hands-on activities, and tangible learning outcomes.', 'lohnourpro' ),
			'modules'  => array(),
		) );
	}

	/**
	 * Get What You'll Build Data.
	 */
	public static function get_build( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_build', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		return wp_parse_args( $data, array(
			'badge'    => __( 'Tangible Deliverables', 'lohnourpro' ),
			'title'    => __( "What You'll Build", 'lohnourpro' ),
			'subtitle' => __( 'A progressive series of functional artifacts created throughout your 30 hours of live building.', 'lohnourpro' ),
			'cards'    => array(),
		) );
	}

	/**
	 * Get Program Outcomes.
	 */
	public static function get_outcomes( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_outcomes', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		return wp_parse_args( $data, array(
			'badge'            => __( 'Concrete Capabilities', 'lohnourpro' ),
			'title'            => __( 'Program Outcomes', 'lohnourpro' ),
			'subtitle'         => __( 'The practical competencies you will possess upon completing this foundational coaching program.', 'lohnourpro' ),
			'north_star_quote' => '',
			'items'            => array(),
		) );
	}

	/**
	 * Get Skills You'll Build.
	 */
	public static function get_skills( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_skills', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		return wp_parse_args( $data, array(
			'badge'    => __( 'Toolbox & Disciplines', 'lohnourpro' ),
			'title'    => __( "Skills You'll Build", 'lohnourpro' ),
			'subtitle' => __( 'Direct competencies cultivated through hands-on exercises across the modules.', 'lohnourpro' ),
			'tags'     => array(),
		) );
	}

	/**
	 * Get Career Applications.
	 */
	public static function get_career( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_career', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		return wp_parse_args( $data, array(
			'badge'      => __( 'Practical Relevance', 'lohnourpro' ),
			'title'      => __( 'Where These Skills Can Be Applied', 'lohnourpro' ),
			'subtitle'   => __( 'Foundational competencies for modern AI implementation across engineering, operations, and product roles.', 'lohnourpro' ),
			'cards'      => array(),
			'disclaimer' => __( 'Note: This program provides grounded engineering foundations and practical skills. It does not provide job placement guarantees or formal academic degree credentials.', 'lohnourpro' ),
		) );
	}

	/**
	 * Get Coaching Format.
	 */
	public static function get_format( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_format', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		return wp_parse_args( $data, array(
			'badge'    => __( 'The Coaching Experience', 'lohnourpro' ),
			'title'    => __( 'How Coaching Works', 'lohnourpro' ),
			'subtitle' => __( 'This is live small-group coaching, not a pre-recorded video repository.', 'lohnourpro' ),
			'steps'    => array(),
		) );
	}

	/**
	 * Get Scope & Boundaries.
	 */
	public static function get_scope( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_scope', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		return wp_parse_args( $data, array(
			'badge'      => __( 'Scope Clarity', 'lohnourpro' ),
			'title'      => __( 'Scope & Boundaries', 'lohnourpro' ),
			'subtitle'   => __( 'Clear expectations regarding what is taught and what lies outside the foundational focus of this program.', 'lohnourpro' ),
			'included'   => array(),
			'excluded'   => array(),
			'scope_note' => '',
		) );
	}

	/**
	 * Get Cohort Enrollment.
	 */
	public static function get_enrollment( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_enrollment', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		$enrollment = wp_parse_args( $data, array(
			'badge'     => __( 'Next Cohort Intake', 'lohnourpro' ),
			'title'     => __( 'Cohort Enrollment', 'lohnourpro' ),
			'desc'      => __( 'Cohorts are strictly limited to 5 participants to maintain hands-on founder-led coaching, active code reviews, and high-engagement discussions.', 'lohnourpro' ),
			'pills'     => array(
				__( '5 Seats Max Per Cohort', 'lohnourpro' ),
				__( 'Live & Founder-Led', 'lohnourpro' ),
				__( '30 Total Hours (~2 Months)', 'lohnourpro' ),
			),
			'cta_label' => __( 'Enquire About the Next Cohort', 'lohnourpro' ),
			'cta_url'   => home_url( '/contact' ),
			'subtext'   => __( 'Schedule a brief conversation to verify prerequisites, discuss scheduling, and reserve your seat in the upcoming cohort.', 'lohnourpro' ),
		) );
		$enrollment['cta_label'] = wp_strip_all_tags( $enrollment['cta_label'] );
		return $enrollment;
	}

	/**
	 * Get Program FAQs.
	 */
	public static function get_faqs( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_faqs', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		return wp_parse_args( $data, array(
			'badge'    => __( 'Common Questions', 'lohnourpro' ),
			'title'    => __( 'Frequently Asked Questions', 'lohnourpro' ),
			'subtitle' => __( 'Clear answers to essential questions regarding cohort structure, technical prerequisites, and outcomes.', 'lohnourpro' ),
			'items'    => array(),
		) );
	}

	/**
	 * Get Closing CTA Card.
	 */
	public static function get_closing_cta( $post_id ) {
		$data = get_post_meta( $post_id, '_lohnour_program_closing_cta', true );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		$closing = wp_parse_args( $data, array(
			'title'     => __( 'Ready to build your foundations in Agentic AI?', 'lohnourpro' ),
			'desc'      => __( 'Strictly limited to 5 participants per cohort for direct founder-led guidance and hands-on code reviews.', 'lohnourpro' ),
			'cta_label' => __( 'Enquire About the Next Cohort', 'lohnourpro' ),
			'cta_url'   => home_url( '/contact' ),
		) );
		$closing['cta_label'] = wp_strip_all_tags( $closing['cta_label'] );
		return $closing;
	}
}
