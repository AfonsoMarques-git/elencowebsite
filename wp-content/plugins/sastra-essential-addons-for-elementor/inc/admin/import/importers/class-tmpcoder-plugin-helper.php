<?php
/**
 * Spexo Addons for Elementor Helper
 *
 * @since  1.0.0
 * @package Spexo Addons for Elementor
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'TMPCODER_Helper' ) ) :

	/**
	 * TMPCODER_Helper
	 *
	 * @since 1.0.0
	 */
	class TMPCODER_Helper {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Instance
		 * @since 1.0.0
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
		 * Download File Into Uploads Directory
		 *
		 * @since  1.0.0 Added $overrides argument to override the uploaded file actions.
		 *
		 * @param  string $file Download File URL.
		 * @param  array  $overrides Upload file arguments.
		 * @param  int    $timeout_seconds Timeout in downloading the XML file in seconds.
		 * @return array        Downloaded file data.
		 */
		public static function download_file( $file = '', $overrides = array(), $timeout_seconds = 300 ) {

			// Gives us access to the download_url() and wp_handle_sideload() functions.
			require_once ABSPATH . 'wp-admin/includes/file.php';

			// Download file to temp dir.
			$temp_file = download_url( $file, $timeout_seconds );

			// WP Error.
			if ( is_wp_error( $temp_file ) ) {
				return array(
					'success' => false,
					'data'    => $temp_file->get_error_message(),
				);
			}

			// Array based on $_FILE as seen in PHP file uploads.
			$file_args = array(
				'name'     => basename( $file ),
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize( $temp_file ),
			);

			$defaults = array(

				// Tells WordPress to not look for the POST form
				// fields that would normally be present as
				// we downloaded the file from a remote server, so there
				// will be no form fields
				// Default is true.
				'test_form'   => false,

				// Setting this to false lets WordPress allow empty files, not recommended.
				// Default is true.
				'test_size'   => true,

				// A properly uploaded file will pass this test. There should be no reason to override this one.
				'test_upload' => true,

				'mimes'       => array(
					'xml'  => 'text/xml',
					'json' => 'text/plain',
					'zip'  => 'application/zip',
					'wie'  => 'text/plain',
				),
			);

			$overrides = wp_parse_args( $overrides, $defaults );

			// Move the temporary file into the uploads directory.
			$results = wp_handle_sideload( $file_args, $overrides );

			tmpcoder_error_log( wp_json_encode( $results ) );
			TMPCODER_Importer_Log::add( wp_json_encode( $results ) );

			if ( isset( $results['error'] ) ) {
				return array(
					'success' => false,
					'data'    => $results,
				);
			}

			// Success.
			return array(
				'success' => true,
				'data'    => $results,
			);
		}

		/**
		 * Get data from a file
		 *
		 * @param string $file_path file path where the content should be saved.
		 * @return string $data, content of the file or WP_Error object with error message.
		 */
		public static function data_from_file( $file_path ) {
			
			// By this point, the $wp_filesystem global should be working, so let's use it to read a file.

			global $wp_filesystem;

			$data = $wp_filesystem->get_contents( $file_path );

			if ( ! $data ) {
				return new \WP_Error(
					'failed_reading_file_from_server',
					sprintf( /* translators: %1$s - br HTML tag, %2$s - file path */
						__( 'An error occurred while reading a file from your server! Tried reading file from path: %1$s%2$s.', 'sastra-essential-addons-for-elementor' ),
						'<br>',
						$file_path
					)
				);
			}

			// Return the file data.
			return $data;
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	TMPCODER_Helper::get_instance();

endif;
