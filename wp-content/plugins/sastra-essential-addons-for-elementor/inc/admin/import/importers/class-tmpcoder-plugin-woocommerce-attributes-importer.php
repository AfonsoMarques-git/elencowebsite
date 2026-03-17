<?php
/**
 * TMPCODER Plugin WooCommerce Attributes Importer
 *
 * @since  1.0.0
 * @package Spexo Addons for Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'TMPCODER_Plugin_WooCommerce_Attributes_Importer' ) ) :

	/**
	 * TMPCODER_Plugin_WooCommerce_Attributes_Importer
	 *
	 * @since 1.0.0
	 */
	class TMPCODER_Plugin_WooCommerce_Attributes_Importer {

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
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'wp_ajax_tmpcoder-plugin-import-woocommerce-attributes', array( $this, 'import_woocommerce_attributes' ) );
			add_action( 'tmpcoder_after_import_complete', array( $this, 'update_wc_lookup_table' ) );
			
			// Variation Swatches Woo compatibility
			add_filter( 'cfvsw_is_required_screen_for_swatch_types', array( $this, 'screen_required_check' ), 10, 1 );
			add_action( 'wxr_importer.pre_process.post_meta', array( $this, 'map_new_term_id' ), 10, 2 );
		}

		/**
		 * Import WooCommerce Attributes from JSON file
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function import_woocommerce_attributes() {
			// Verify Nonce.
			check_ajax_referer( 'spexo-addons', '_ajax_nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to perform this action', 'sastra-essential-addons-for-elementor' ) );
			}

			// Check if WooCommerce is active.
			if ( ! class_exists( 'WooCommerce' ) ) {
				wp_send_json_error( __( 'WooCommerce plugin is not active. Please activate WooCommerce first.', 'sastra-essential-addons-for-elementor' ) );
			}

			// Check if Variation Swatches Woo is active (optional but recommended)
			$variation_swatches_active = is_plugin_active( 'variation-swatches-woo/variation-swatches-woo.php' );
			if ( $variation_swatches_active ) {
				TMPCODER_Importer_Log::add( 'Variation Swatches Woo plugin detected - swatch data will be imported' );
			}

			$attributes_file_url = ( isset( $_REQUEST['woocommerce_attributes_file_url'] ) ) ? urldecode( $_REQUEST['woocommerce_attributes_file_url'] ) : '';

			if ( ! $this->is_valid_url( $attributes_file_url ) ) {
				/* Translators: %s is File URL. */
				wp_send_json_error( sprintf( __( 'Invalid Request URL - %s', 'sastra-essential-addons-for-elementor' ), $attributes_file_url ) );
			}

			$overrides = array(
				'wp_handle_sideload' => 'upload',
			);

			// Download attributes file.
			$attributes_file = $this->download_file( $attributes_file_url, $overrides );

			if ( $attributes_file['success'] ) {
				$attributes_file_path = $attributes_file['data']['file'];

				$attributes_data_raw = $this->data_from_file( $attributes_file_path );
				$attributes_data = json_decode( $attributes_data_raw, true );

				if ( ! empty( $attributes_data ) && isset( $attributes_data['woocommerce_product_attributes'] ) ) {
					
					TMPCODER_Importer_Log::add( 'Starting WooCommerce Attributes Import - Data structure: ' . wp_json_encode( $attributes_data ) );

					$imported_attributes = $this->import_attributes( $attributes_data['woocommerce_product_attributes'] );

					// Import CFVSW global settings if available
					if ( isset( $attributes_data['cfvsw_global'] ) && ! empty( $attributes_data['cfvsw_global'] ) ) {
						$this->import_cfvsw_global_settings( $attributes_data['cfvsw_global'] );
					}

					// Import CFVSW shop settings if available
					if ( isset( $attributes_data['cfvsw_shop'] ) && ! empty( $attributes_data['cfvsw_shop'] ) ) {
						$this->import_cfvsw_shop_settings( $attributes_data['cfvsw_shop'] );
					}

					// Import CFVSW style settings if available
					if ( isset( $attributes_data['cfvsw_style'] ) && ! empty( $attributes_data['cfvsw_style'] ) ) {
						$this->import_cfvsw_style_settings( $attributes_data['cfvsw_style'] );
					}

					// Import CFVSW product attribute meta fields if available
					$this->import_cfvsw_product_attribute_meta( $attributes_data );

					// Import variation swatches plugin settings if available (legacy support)
					if ( isset( $attributes_data['cfvsw_settings'] ) && ! empty( $attributes_data['cfvsw_settings'] ) ) {
						$this->import_variation_swatches_settings( $attributes_data['cfvsw_settings'] );
					}

					if ( ! empty( $imported_attributes ) ) {
						wp_send_json_success( $imported_attributes );
					} else {
						wp_send_json_error( __( 'No attributes were imported. They might already exist.', 'sastra-essential-addons-for-elementor' ) );
					}
				} else {
					wp_send_json_error( __( 'Invalid attributes data format or empty data!', 'sastra-essential-addons-for-elementor' ) );
				}
			} else {
				wp_send_json_error( $attributes_file['data'] );
			}
		}

		/**
		 * Import WooCommerce product attributes
		 *
		 * @since 1.0.0
		 * @param array $attributes Array of attributes to import.
		 * @return array Imported attributes data.
		 */
		public function import_attributes( $attributes = array() ) {
			$imported_attributes = array();

			if ( empty( $attributes ) || ! function_exists( 'wc_create_attribute' ) ) {
				return $imported_attributes;
			}

			// Handle both old array format and new object format with id keys
			$attributes_to_process = array();
			if ( isset( $attributes[0] ) ) {
				// Old array format
				$attributes_to_process = $attributes;
				TMPCODER_Importer_Log::add( 'Processing attributes in old array format - ' . count( $attributes_to_process ) . ' attributes found' );
			} else {
				// New object format with id:X keys
				foreach ( $attributes as $key => $attribute ) {
					if ( strpos( $key, 'id:' ) === 0 ) {
						$attributes_to_process[] = $attribute;
					}
				}
				TMPCODER_Importer_Log::add( 'Processing attributes in new object format - ' . count( $attributes_to_process ) . ' attributes found' );
			}

			foreach ( $attributes_to_process as $key => $attribute ) {
				// Check if attribute already exists.
				if ( taxonomy_exists( 'pa_' . $attribute['attribute_name'] ) ) {
					TMPCODER_Importer_Log::add( 'Skipped - Attribute already exists: ' . $attribute['attribute_name'] );
					continue;
				}

				$args = array(
					'name'         => sanitize_text_field( $attribute['attribute_label'] ),
					'slug'         => sanitize_title( $attribute['attribute_name'] ),
					'type'         => sanitize_text_field( $attribute['attribute_type'] ),
					'order_by'     => sanitize_text_field( $attribute['attribute_orderby'] ),
					'has_archives' => (bool) $attribute['attribute_public'],
				);

				$attribute_id = wc_create_attribute( $args );

				if ( $attribute_id && ! is_wp_error( $attribute_id ) ) {
					$imported_attributes[] = array(
						'id'   => $attribute_id,
						'name' => $args['name'],
						'slug' => $args['slug'],
					);

					// Store attribute ID for future reference.
					update_option( $args['slug'] . '_attribute_id', $attribute_id );

					TMPCODER_Importer_Log::add( 'Imported - WooCommerce Attribute: ' . $args['name'] . ' (ID: ' . $attribute_id . ')' );

					// Import variation swatches data if available
					if ( isset( $attribute['variation_swatches'] ) && ! empty( $attribute['variation_swatches'] ) ) {
						$this->import_variation_swatches_data( $args['slug'], $attribute['variation_swatches'] );
					}
				} else {
					$error_message = is_wp_error( $attribute_id ) ? $attribute_id->get_error_message() : 'Unknown error';
					TMPCODER_Importer_Log::add( 'Failed - WooCommerce Attribute: ' . $args['name'] . ' - ' . $error_message );
				}
			}

			// Update WooCommerce lookup tables after importing attributes.
			$this->update_wc_lookup_table();

			return $imported_attributes;
		}

		/**
		 * Import variation swatches data for an attribute
		 *
		 * @since 1.0.0
		 * @param string $attribute_slug Attribute slug.
		 * @param array  $swatches_data Swatches data to import.
		 * @return void
		 */
		public function import_variation_swatches_data( $attribute_slug, $swatches_data ) {
			if ( ! is_plugin_active( 'variation-swatches-woo/variation-swatches-woo.php' ) ) {
				return;
			}

			$taxonomy = 'pa_' . $attribute_slug;

			foreach ( $swatches_data as $term_slug => $swatch_data ) {
				$term = get_term_by( 'slug', $term_slug, $taxonomy );
				
				if ( ! $term ) {
					continue;
				}

				// Import swatch type
				if ( isset( $swatch_data['type'] ) ) {
					update_term_meta( $term->term_id, $taxonomy . '_swatch_type', $swatch_data['type'] );
				}

				// Import swatch value based on type
				if ( isset( $swatch_data['value'] ) ) {
					switch ( $swatch_data['type'] ) {
						case 'color':
							update_term_meta( $term->term_id, $taxonomy . '_swatch_color', $swatch_data['value'] );
							break;
						case 'image':
							update_term_meta( $term->term_id, $taxonomy . '_swatch_image', $swatch_data['value'] );
							break;
						case 'label':
							update_term_meta( $term->term_id, $taxonomy . '_swatch_label', $swatch_data['value'] );
							break;
					}
				}

				// Import tooltip if available
				if ( isset( $swatch_data['tooltip'] ) ) {
					update_term_meta( $term->term_id, $taxonomy . '_swatch_tooltip', $swatch_data['tooltip'] );
				}

				TMPCODER_Importer_Log::add( 'Imported - Variation Swatch for term: ' . $term->name . ' in taxonomy: ' . $taxonomy );
			}
		}

		/**
		 * Update WooCommerce Lookup Table.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function update_wc_lookup_table() {
			if ( function_exists( 'wc_update_product_lookup_tables' ) ) {
				if ( ! wc_update_product_lookup_tables_is_running() ) {
					wc_update_product_lookup_tables();
				}
			}
		}

		/**
		 * Download File Into Uploads Directory
		 *
		 * @since 1.0.0
		 * @param string $file Download File URL.
		 * @param array  $overrides Upload file arguments.
		 * @param int    $timeout_seconds Timeout in downloading the XML file in seconds.
		 * @return array Downloaded file data.
		 */
		public function download_file( $file = '', $overrides = array(), $timeout_seconds = 300 ) {

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
		public function data_from_file( $file_path ) {
			
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

		/**
		 * Check if URL is valid
		 *
		 * @since 1.0.0
		 * @param string $url URL to validate.
		 * @return bool True if valid, false otherwise.
		 */
		public function is_valid_url( $url ) {
			return filter_var( $url, FILTER_VALIDATE_URL ) !== false;
		}


		/**
		 * Import CFVSW global settings
		 *
		 * @since 1.0.0
		 * @param array $settings Global settings to import.
		 * @return void
		 */
		public function import_cfvsw_global_settings( $settings ) {
			if ( ! is_plugin_active( 'variation-swatches-woo/variation-swatches-woo.php' ) ) {
				TMPCODER_Importer_Log::add( 'Skipped - CFVSW Global Settings (Variation Swatches Woo plugin not active)' );
				return;
			}

			update_option( 'cfvsw_global', $settings );
			TMPCODER_Importer_Log::add( 'Imported - CFVSW Global Settings: ' . wp_json_encode( $settings ) );
		}

		/**
		 * Import CFVSW shop settings
		 *
		 * @since 1.0.0
		 * @param array $settings Shop settings to import.
		 * @return void
		 */
		public function import_cfvsw_shop_settings( $settings ) {
			if ( ! is_plugin_active( 'variation-swatches-woo/variation-swatches-woo.php' ) ) {
				TMPCODER_Importer_Log::add( 'Skipped - CFVSW Shop Settings (Variation Swatches Woo plugin not active)' );
				return;
			}

			update_option( 'cfvsw_shop', $settings );
			TMPCODER_Importer_Log::add( 'Imported - CFVSW Shop Settings: ' . wp_json_encode( $settings ) );
		}

		/**
		 * Import CFVSW style settings
		 *
		 * @since 1.0.0
		 * @param array $settings Style settings to import.
		 * @return void
		 */
		public function import_cfvsw_style_settings( $settings ) {
			if ( ! is_plugin_active( 'variation-swatches-woo/variation-swatches-woo.php' ) ) {
				TMPCODER_Importer_Log::add( 'Skipped - CFVSW Style Settings (Variation Swatches Woo plugin not active)' );
				return;
			}

			update_option( 'cfvsw_style', $settings );
			TMPCODER_Importer_Log::add( 'Imported - CFVSW Style Settings: ' . wp_json_encode( $settings ) );
		}

		/**
		 * Import CFVSW product attribute meta fields
		 *
		 * @since 1.0.0
		 * @param array $data Full data array to search for meta fields.
		 * @return void
		 */
		public function import_cfvsw_product_attribute_meta( $data ) {
			if ( ! is_plugin_active( 'variation-swatches-woo/variation-swatches-woo.php' ) ) {
				return;
			}

			// Look for cfvsw_product_attribute_* meta fields
			foreach ( $data as $key => $value ) {
				if ( strpos( $key, 'cfvsw_product_attribute_' ) === 0 ) {
					update_option( $key, $value );
					TMPCODER_Importer_Log::add( 'Imported - CFVSW Product Attribute Meta: ' . $key . ' = ' . $value );
				}
			}
		}

		/**
		 * Import variation swatches plugin settings
		 *
		 * @since 1.0.0
		 * @param array $settings Plugin settings to import.
		 * @return void
		 */
		public function import_variation_swatches_settings( $settings ) {
			if ( ! is_plugin_active( 'variation-swatches-woo/variation-swatches-woo.php' ) ) {
				return;
			}

			// Import plugin settings
			if ( isset( $settings['cfvsw_general_settings'] ) ) {
				update_option( 'cfvsw_general_settings', $settings['cfvsw_general_settings'] );
				TMPCODER_Importer_Log::add( 'Imported - Variation Swatches General Settings' );
			}

			if ( isset( $settings['cfvsw_shop_page_settings'] ) ) {
				update_option( 'cfvsw_shop_page_settings', $settings['cfvsw_shop_page_settings'] );
				TMPCODER_Importer_Log::add( 'Imported - Variation Swatches Shop Page Settings' );
			}

			if ( isset( $settings['cfvsw_product_page_settings'] ) ) {
				update_option( 'cfvsw_product_page_settings', $settings['cfvsw_product_page_settings'] );
				TMPCODER_Importer_Log::add( 'Imported - Variation Swatches Product Page Settings' );
			}

			if ( isset( $settings['cfvsw_cart_page_settings'] ) ) {
				update_option( 'cfvsw_cart_page_settings', $settings['cfvsw_cart_page_settings'] );
				TMPCODER_Importer_Log::add( 'Imported - Variation Swatches Cart Page Settings' );
			}
		}

		/**
		 * Returns true/false that need screen check required to retrieve the swatch types
		 * 
		 * @since 1.0.0
		 * @return bool
		 */
		public function screen_required_check() {
			if ( ! is_plugin_active( 'variation-swatches-woo/variation-swatches-woo.php' ) ) {
				return false;
			}

			// Check if import process is running
			if ( get_option( 'tmpcoder_import_complete' ) !== 'yes' && get_transient( 'tmpcoder_import_started' ) ) {
				return true;
			}
			
			return false;
		}

		/**
		 * Map new term id for variation swatches data.
		 * 
		 * @param array $meta Meta data.
		 * @param int   $post_id Post ID.
		 * @return array Modified meta data.
		 * @since 1.0.0
		 */
		public function map_new_term_id( $meta, $post_id ) {
			// Early return if not the target meta key.
			if ( ! isset( $meta['key'] ) || ! preg_match( '/^cfvsw_product_attr_pa_/', $meta['key'] ) ) {
				return $meta;
			}

			// Early return if value is empty or not serialized.
			if ( empty( $meta['value'] ) || ! is_serialized( $meta['value'] ) ) {
				return $meta;
			}

			// Safely unserialize data.
			$unserialized_data = maybe_unserialize( $meta['value'] );
			if ( false === $unserialized_data || ! is_array( $unserialized_data ) ) {
				return $meta;
			}

			// Extract taxonomy from meta key
			$taxonomy = str_replace( 'cfvsw_product_attr_', '', $meta['key'] );
			
			// Get terms and validate.
			$terms = wp_get_post_terms( $post_id, $taxonomy );
			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				return $meta;
			}

			// Process only if we have valid swatch data.
			if ( empty( $unserialized_data['type'] ) || ! in_array( $unserialized_data['type'], array( 'color', 'image', 'label' ) ) ) {
				return $meta;
			}

			$modified = false;
			foreach ( $terms as $term ) {
				if ( ! is_object( $term ) || ! isset( $term->term_id ) ) {
					continue;
				}

				// Get the old color/image/label ID from term meta
				$old_id = get_term_meta( $term->term_id, $taxonomy . '_id', true );
				if ( '' === $old_id || ! isset( $unserialized_data[ $old_id ] ) ) {
					continue;
				}

				// Map old ID to new term ID
				$unserialized_data[ $term->term_id ] = $unserialized_data[ $old_id ];
				$modified = true;
			}

			// Only serialize and update if changes were made.
			if ( $modified ) {
				$meta['value'] = maybe_serialize( $unserialized_data );
				TMPCODER_Importer_Log::add( 'Updated swatch data for post ID: ' . $post_id . ' with taxonomy: ' . $taxonomy );
			}

			return $meta;
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	TMPCODER_Plugin_WooCommerce_Attributes_Importer::get_instance();

endif;
