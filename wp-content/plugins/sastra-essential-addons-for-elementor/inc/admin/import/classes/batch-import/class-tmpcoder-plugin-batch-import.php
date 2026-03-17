<?php
/**
 * Batch Import
 *
 * @package Spexo Addons for Elementor
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'TMPCODER_Batch_Import' ) ) :

	/**
	 * Batch Import
	 *
	 * @since 1.0.0
	 */
	class TMPCODER_Batch_Import {

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 * @var object Class object.
		 * @access private
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
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

			// WP Core Files.
			require_once ABSPATH . 'wp-admin/includes/image.php';

			// Image Downloader.
			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/importers/batch-processing/helpers/class-tmpcoder-plugin-image-importer.php';
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	TMPCODER_Batch_Import::get_instance();

endif;
