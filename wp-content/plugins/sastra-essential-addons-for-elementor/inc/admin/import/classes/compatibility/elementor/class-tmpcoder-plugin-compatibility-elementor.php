<?php
/**
 * Spexo Addons for Elementor Compatibility for 'Elementor'
 *
 * @package Spexo Addons for Elementor
 * @since 1.0.0
 */

namespace Tmpcoder\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( '\Elementor\Plugin' ) ) {
	return;
}

if ( ! class_exists( 'TMPCODER_Compatibility_Elementor' ) ) :

	/**
	 * Elementor Compatibility
	 *
	 * @since 1.0.0
	 */
	class TMPCODER_Compatibility_Elementor {

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

			/**
			 * Add Slashes
			 *
			 * @todo    Elementor already have below code which works on defining the constant `WP_LOAD_IMPORTERS`.
			 * After defining the constant `WP_LOAD_IMPORTERS` in WP CLI it was not works.
			 * Try to remove below duplicate code in future.
			 */
			
			if ( ! wp_doing_ajax() || ( defined( 'ELEMENTOR_VERSION' ) && 
				version_compare( ELEMENTOR_VERSION, '3.0.0', '>=' ) ) ) {

				remove_filter( 'wp_import_post_meta', array( 'Elementor\Compatibility', 'on_wp_import_post_meta' ) );
				remove_filter( 'tmpcoder_importer.pre_process.post_meta', array( 'Elementor\Compatibility', 'on_tmpcoder_importer_pre_process_post_meta' ) );

				add_filter( 'wp_import_post_meta', array( $this, 'on_wp_import_post_meta' ) );
				add_filter( 'tmpcoder_importer.pre_process.post_meta', array( $this, 'on_tmpcoder_importer_pre_process_post_meta' ) );
			}

			add_action( 'tmpcoder_before_delete_imported_posts', array( $this, 'force_delete_kit' ), 10, 2 );
			add_action( 'tmpcoder_before_sse_import', array( $this, 'disable_attachment_metadata' ) );

			add_action( 'tmpcoder_after_plugin_activation', array( $this, 'disable_elementor_redirect' ) );
		}

		/**
		 * Disable Elementor redirect.
		 *
		 * @return void.
		 */
		public function disable_elementor_redirect() {
			$elementor_redirect = get_transient( 'elementor_activation_redirect' );

			if ( ! empty( $elementor_redirect ) && '' !== $elementor_redirect ) {
				delete_transient( 'elementor_activation_redirect' );
			}
		}

		/**
		 * Disable the attachment metadata
		 */
		public function disable_attachment_metadata() {
			remove_filter(
				'wp_update_attachment_metadata', array(
					\Elementor\Plugin::$instance->uploads_manager->get_file_type_handlers( 'svg' ),
					'set_svg_meta_data',
				), 10, 2
			);
		}

		/**
		 * Force Delete Elementor Kit
		 *
		 * Delete the previously imported Elementor kit.
		 *
		 * @param int    $post_id     Post name.
		 * @param string $post_type   Post type.
		 */
		public function force_delete_kit( $post_id = 0, $post_type = '' ) {

			if ( ! $post_id ) {
				return;
			}

			if ( 'elementor_library' === $post_type ) {
				$_GET['force_delete_kit'] = true;
			}
		}

		/**
		 * Process post meta before WP importer.
		 *
		 * Normalize Elementor post meta on import, We need the `wp_slash` in order
		 * to avoid the unslashing during the `add_post_meta`.
		 * Also converts serialized _elementor_data to JSON format as Elementor expects JSON.
		 *
		 * Fired by `wp_import_post_meta` filter.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $post_meta Post meta.
		 *
		 * @return array Updated post meta.
		 */
		public function on_wp_import_post_meta( $post_meta ) {
			foreach ( $post_meta as &$meta ) {
				if ( '_elementor_data' === $meta['key'] ) {
					$value = $meta['value'];
					
					// Handle different data formats
					if ( is_serialized( $value ) ) {
						// Value is in PHP serialized format - convert to JSON
						$unserialized_data = maybe_unserialize( $value );
						
						// Convert to JSON format as Elementor expects JSON, not serialized PHP arrays
						if ( is_array( $unserialized_data ) || is_object( $unserialized_data ) ) {
							$value = wp_json_encode( $unserialized_data );
						}
					} elseif ( is_array( $value ) || is_object( $value ) ) {
						// Value is already an array/object (shouldn't happen normally, but handle it)
						$value = wp_json_encode( $value );
					} elseif ( is_string( $value ) ) {
						// Value is already a string - verify it's valid JSON
						$decoded = json_decode( $value, true );
						if ( json_last_error() !== JSON_ERROR_NONE ) {
							// Not valid JSON - might be corrupted, but we'll let Elementor handle it
							// or it could be HTML/plain text which Elementor will convert
						}
						// If it's valid JSON, keep it as is (no need to re-encode)
					}
					
					// Ensure value is a string before applying wp_slash
					if ( ! is_string( $value ) ) {
						$value = wp_json_encode( $value );
					}
					
					// Apply wp_slash to prevent unslashing during add_post_meta
					$meta['value'] = wp_slash( $value );
					break;
				}
			}

			return $post_meta;
		}

		/**
		 * Process post meta before WXR importer.
		 *
		 * Normalize Elementor post meta on import with the new WP_importer, We need
		 * the `wp_slash` in order to avoid the unslashing during the `add_post_meta`.
		 * Also converts serialized _elementor_data to JSON format as Elementor expects JSON.
		 *
		 * Fired by `tmpcoder_importer.pre_process.post_meta` filter.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $post_meta Post meta.
		 *
		 * @return array Updated post meta.
		 */
		public function on_tmpcoder_importer_pre_process_post_meta( $post_meta ) {
			if ( '_elementor_data' === $post_meta['key'] ) {
				$value = $post_meta['value'];
				
				// Handle different data formats
				if ( is_serialized( $value ) ) {
					// Value is in PHP serialized format - convert to JSON
					$unserialized_data = maybe_unserialize( $value );
					
					// Convert to JSON format as Elementor expects JSON, not serialized PHP arrays
					if ( is_array( $unserialized_data ) || is_object( $unserialized_data ) ) {
						$value = wp_json_encode( $unserialized_data );
					}
				} elseif ( is_array( $value ) || is_object( $value ) ) {
					// Value is already an array/object (shouldn't happen normally, but handle it)
					$value = wp_json_encode( $value );
				} elseif ( is_string( $value ) ) {
					// Value is already a string - verify it's valid JSON
					$decoded = json_decode( $value, true );
					if ( json_last_error() !== JSON_ERROR_NONE ) {
						// Not valid JSON - might be corrupted, but we'll let Elementor handle it
						// or it could be HTML/plain text which Elementor will convert
					}
					// If it's valid JSON, keep it as is (no need to re-encode)
				}
				
				// Ensure value is a string before applying wp_slash
				if ( ! is_string( $value ) ) {
					$value = wp_json_encode( $value );
				}
				
				// Apply wp_slash to prevent unslashing during add_post_meta
				$post_meta['value'] = wp_slash( $value );
			}

			return $post_meta;
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	TMPCODER_Compatibility_Elementor::get_instance();

endif;
