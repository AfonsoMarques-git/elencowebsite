<?php
/**
 * Spexo Addons for Elementor Compatibility for 3rd party plugins.
 *
 * @package Spexo Addons for Elementor
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'TMPCODER_Compatibility' ) ) :

	/**
	 * Spexo Addons for Elementor Compatibility
	 *
	 * @since 1.0.0
	 */
	class TMPCODER_Compatibility {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class object.
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object initialized object of class.
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Plugin - Elementor.
			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/classes/compatibility/elementor/class-tmpcoder-plugin-compatibility-elementor.php';
		}
	}

	/**
	 * Kicking this off by calling 'instance()' method
	 */
	TMPCODER_Compatibility::instance();

endif;


