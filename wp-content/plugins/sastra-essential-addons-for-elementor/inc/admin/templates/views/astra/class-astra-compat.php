<?php
/**
 * TMPCODER_Astra_Compat setup
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Astra theme compatibility.
 */
class TMPCODER_Astra_Compat {

	/**
	 * Instance of TMPCODER_Astra_Compat.
	 *
	 * @var TMPCODER_Astra_Compat
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
			self::$instance = new TMPCODER_Astra_Compat();

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
			add_action( 'template_redirect', [ $this, 'astra_setup_header' ], 10 );
			add_action( 'astra_header', [$this->render_templates, 'tmpcoder_replace_header'] );
			add_action( 'elementor/page_templates/canvas/before_content', [ $this->render_templates, 'tmpcoder_add_canvas_header' ] );
		}

		if ( $this->render_templates->get_dynamic_content_id('type_footer') ) {
			add_action( 'template_redirect', [ $this, 'astra_setup_footer' ], 10 );
			add_action( 'astra_footer', [$this->render_templates, 'tmpcoder_replace_footer'] );
			add_action( 'elementor/page_templates/canvas/after_content', [ $this->render_templates, 'tmpcoder_add_canvas_footer' ] );
		}
	}

	/**
	 * Disable header from the theme.
	 */
	public function astra_setup_header() {
		remove_action( 'astra_header', 'astra_header_markup' );

		// Remove the new header builder action.
		if ( class_exists( '\Astra_Builder_Helper' ) && \Astra_Builder_Helper::$is_header_footer_builder_active ) {
			remove_action( 'astra_header', [ Astra_Builder_Header::get_instance(), 'prepare_header_builder_markup' ] );
		}
	}

	/**
	 * Disable footer from the theme.
	 */
	public function astra_setup_footer() {
		remove_action( 'astra_footer', 'astra_footer_markup' );

		// Remove the new footer builder action.
		if ( class_exists( '\Astra_Builder_Helper' ) && \Astra_Builder_Helper::$is_header_footer_builder_active ) {
			remove_action( 'astra_footer', [ Astra_Builder_Footer::get_instance(), 'footer_markup' ] );
		}
	}
}

TMPCODER_Astra_Compat::instance();
