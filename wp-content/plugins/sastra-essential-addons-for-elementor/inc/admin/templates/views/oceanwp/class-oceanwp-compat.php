<?php
/**
 * OceanWP theme compatibility.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_OceanWP_Compat {

	/**
	 * Instance of TMPCODER_OceanWP_Compat.
	 *
	 * @var TMPCODER_OceanWP_Compat
	 */
	private static $instance;

	/**
	 * TMPCODER_Render_Templates() Class
	 */
	private $render_templates;

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new TMPCODER_OceanWP_Compat();

			add_action( 'wp', [ self::$instance, 'hooks' ] );
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function hooks() {
		$this->render_templates = new TMPCODER_Theme_Layouts_Base( true );

		if ( $this->render_templates->get_dynamic_content_id('type_header') ) {
			add_action( 'template_redirect', [ $this, 'setup_header' ], 10 );
			add_action( 'ocean_header', [$this->render_templates, 'tmpcoder_replace_header'] );
			add_action( 'elementor/page_templates/canvas/before_content', [ $this->render_templates, 'tmpcoder_add_canvas_header' ] );
		}

		if ( $this->render_templates->get_dynamic_content_id('type_footer') ) {
			add_action( 'template_redirect', [ $this, 'setup_footer' ], 10 );
			add_action( 'ocean_footer', [$this->render_templates, 'tmpcoder_replace_footer'] );
			add_action( 'elementor/page_templates/canvas/after_content', [ $this->render_templates, 'tmpcoder_add_canvas_footer' ] );
		}
	}

	/**
	 * Disable header from the theme.
	 */
	public function setup_header() {
		remove_action( 'ocean_top_bar', 'oceanwp_top_bar_template' );
		remove_action( 'ocean_header', 'oceanwp_header_template' );
		remove_action( 'ocean_page_header', 'oceanwp_page_header_template' );
	}

	/**
	 * Disable footer from the theme.
	 */
	public function setup_footer() {
		remove_action( 'ocean_footer', 'oceanwp_footer_template' );
	}

}

TMPCODER_OceanWP_Compat::instance();
