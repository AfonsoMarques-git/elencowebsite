<?php
/**
 * Storefront theme compatibility.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Storefront_Compat {

	/**
	 * Instance of TMPCODER_Storefront_Compat.
	 *
	 * @var TMPCODER_Storefront_Compat
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
			self::$instance = new TMPCODER_Storefront_Compat();

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
			add_action( 'storefront_before_header', [$this->render_templates, 'tmpcoder_replace_header'], 500 );
			add_action( 'elementor/page_templates/canvas/before_content', [ $this->render_templates, 'tmpcoder_add_canvas_header' ] );
		}

		if ( $this->render_templates->get_dynamic_content_id('type_footer') ) {
			add_action( 'template_redirect', [ $this, 'setup_footer' ], 10 );
			add_action( 'storefront_after_footer', [$this->render_templates, 'tmpcoder_replace_footer'], 500 );
			add_action( 'elementor/page_templates/canvas/after_content', [ $this->render_templates, 'tmpcoder_add_canvas_footer' ] );
		}

		if ( $this->render_templates->get_dynamic_content_id('type_header') || $this->render_templates->get_dynamic_content_id('type_header') ) {
			add_action( 'wp_head', [ $this, 'styles' ] );
		}
	}

	/**
	 * Add inline CSS to hide empty divs for header and footer in storefront
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function styles() {
		$css = '';

		if ( $this->render_templates->get_dynamic_content_id('type_header') ) {
			$css .= '.site-header {
				display: none;
			}';
		}

		if ( $this->render_templates->get_dynamic_content_id('type_footer') ) {
			$css .= '.site-footer {
				display: none;
			}';
		}

        wp_register_style( 'tmpcoder-disable-storefront-hf', false );
		wp_enqueue_style( 'tmpcoder-disable-storefront-hf' );
        wp_add_inline_style('tmpcoder-disable-storefront-hf', $css);
	}

	/**
	 * Disable header from the theme.
	 */
	public function setup_header() {
		for ( $priority = 0; $priority < 200; $priority ++ ) {
			remove_all_actions( 'storefront_header', $priority );
		}
	}

	/**
	 * Disable footer from the theme.
	 */
	public function setup_footer() {
		for ( $priority = 0; $priority < 200; $priority ++ ) {
			remove_all_actions( 'storefront_footer', $priority );
		}
	}

}

TMPCODER_Storefront_Compat::instance();
