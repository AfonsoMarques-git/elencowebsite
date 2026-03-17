<?php
/**
 * TMPCODER_GeneratePress_Compat setup
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_GeneratePress_Compat {

	/**
	 * Instance of TMPCODER_GeneratePress_Compat
	 *
	 * @var TMPCODER_GeneratePress_Compat
	 */
	private static $instance;

	/**
	 * TMPCODER_Theme_Layouts_Base() Class
	 */
	private $render_templates;

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new TMPCODER_GeneratePress_Compat();

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
			add_action( 'template_redirect', [ $this, 'generatepress_setup_header' ] );
			add_action( 'generate_header', [$this->render_templates, 'tmpcoder_replace_header'] );
			add_action( 'elementor/page_templates/canvas/before_content', [ $this->render_templates, 'tmpcoder_add_canvas_header' ] );
		}

		if ( $this->render_templates->get_dynamic_content_id('type_footer') ) {
			add_action( 'template_redirect', [ $this, 'generatepress_setup_footer' ] );
			add_action( 'generate_footer', [$this->render_templates, 'tmpcoder_replace_footer'] );
			add_action( 'elementor/page_templates/canvas/after_content', [ $this->render_templates, 'tmpcoder_add_canvas_footer' ] );
		}
	}

	/**
	 * Disable header from the theme.
	 */
	public function generatepress_setup_header() {
		remove_action( 'generate_header', 'generate_construct_header' );
	}

	/**
	 * Disable footer from the theme.
	 */
	public function generatepress_setup_footer() {
		remove_action( 'generate_footer', 'generate_construct_footer_widgets', 5 );
		remove_action( 'generate_footer', 'generate_construct_footer' );
	}

}

TMPCODER_GeneratePress_Compat::instance();
