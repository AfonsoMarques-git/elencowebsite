<?php
/**
 * Spexo Addons for Elementor
 *
 * @since  1.0.0
 * @package Spexo Addons for Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tmpcoder' ) ) :

	/**
	 * Tmpcoder
	 */
	class Tmpcoder {

		/**
		 * API URL which is used to get the response from.
		 *
		 * @since  1.0.0
		 * @var (String) URL
		 */
		public $api_url;
		
		/**
		 * Instance of Tmpcoder
		 *
		 * @since  1.0.0
		 * @var (Object) Tmpcoder
		 */
		private static $instance = null;

		/**
		 * Ajax
		 *
		 * @since  1.1.0
		 * @var (Array) $ajax
		 */
		private $ajax = array();

		/**
		 * Instance of Tmpcoder.
		 *
		 * @since  1.0.0
		 *
		 * @return object Class object.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 */
		private function __construct() {

			$this->includes();

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			
			// AJAX.
			$this->ajax = array(
				'tmpcoder-plugin-reset-posts' => 'reset_posts',
			);

			foreach ( $this->ajax as $ajax_hook => $ajax_callback ) {
				add_action( 'wp_ajax_' . $ajax_hook, array( $this, $ajax_callback ) );
			}
		}

		/**
		 * Reset posts in chunks.
		 *
		 * @since 1.0.0
		 */
		public function reset_posts() {
			
			if ( wp_doing_ajax() ) {
				check_ajax_referer( 'spexo-addons', '_ajax_nonce' );

				if ( ! current_user_can( 'manage_options' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'sastra-essential-addons-for-elementor' ) );
				}
			}

			do_action('tmpcoder_before_import_start');

			set_transient( 'tmpcoder_import_started', 'yes', HOUR_IN_SECONDS );

			TMPCODER_Error_Handler::get_instance()->start_error_handler();

			// Suspend bunches of stuff in WP core.
			wp_defer_term_counting( true );
			wp_defer_comment_counting( true );
			wp_suspend_cache_invalidation( true );

			$posts = tmpcoder_get_reset_post_data();

			if ( ! empty( $posts ) ) {

				foreach ( $posts as $key => $post_id ) {

					$post_id = absint( $post_id );

					if ( $post_id ) {
						$post_type = get_post_type( $post_id );
						$message   = 'Deleted - Post ID ' . $post_id . ' - ' . $post_type . ' - ' . get_the_title( $post_id );

						do_action( 'tmpcoder_before_delete_imported_posts', $post_id, $post_type );

						TMPCODER_Importer_Log::add( $message );
						wp_delete_post( $post_id, true );
					}
				}
			}

			// Re-enable stuff in core.
			wp_suspend_cache_invalidation( false );
			wp_cache_flush();
			foreach ( get_taxonomies() as $tax ) {
				delete_option( "{$tax}_children" );
				_get_term_hierarchy( $tax );
			}

			delete_option('tmpcoder_current_active_demo');

			if ( defined('TMPCODER_THEME_OPTION_NAME') ){
                delete_option(TMPCODER_THEME_OPTION_NAME . '-transients');
                delete_option(TMPCODER_THEME_OPTION_NAME);
				$TMPCODER_THEME_OPTIONS_DATA_CLASS = TMPCODER_THEME_OPTIONS_DATA_CLASS;

				if ( class_exists($TMPCODER_THEME_OPTIONS_DATA_CLASS) && function_exists('tmpcoder_redux_options_update_theme_variable') ){
					$theme_options = $TMPCODER_THEME_OPTIONS_DATA_CLASS::tmpcoder_get_all_data();

                    // https://devs.redux.io/configuration/api.html#available-methods
					// Redux::all_instances();
					$default_sections = Redux::construct_sections(TMPCODER_THEME_OPTION_NAME);
					if ( !empty($default_sections) ){
						foreach($default_sections as $sec_key => $sec_val){
							if ( !empty($sec_val['fields']) ){
								foreach($sec_val['fields'] as $f_key => $f_val){
									$field_key = $f_val['id'];
									if ( isset($f_val['default']) ){
										$theme_options[$field_key] = $f_val['default'];	
									}								
								}
							}
						}
					}
					if ( !empty($theme_options) ){
						tmpcoder_redux_options_update_theme_variable($theme_options, '', '');
					}
                }
            }

			wp_defer_term_counting( false );
			wp_defer_comment_counting( false );

			TMPCODER_Error_Handler::get_instance()->stop_error_handler();

			if ( wp_doing_ajax() ) {
				wp_send_json_success();
			}
		}

		/**
		 * Get theme install, active or inactive status.
		 *
		 * @since 1.0.0
		 *
		 * @return string Theme status
		 */
		public function get_theme_status() {

			$theme = (is_object(wp_get_theme()->parent())) ? wp_get_theme()->parent() : wp_get_theme();

			// Theme installed and activate.
			if ( defined('THEME_NAME') && (THEME_NAME === $theme->name || THEME_NAME === $theme->parent_theme) ) {
				return 'installed-and-active';
			}

			// Theme installed but not activate.
			foreach ( (array) wp_get_themes() as $theme_dir => $theme ) {
				if ( defined('THEME_NAME') && (THEME_NAME === $theme->name || THEME_NAME === $theme->parent_theme) ) {
					return 'installed-but-inactive';
				}
			}

			return 'not-installed';
		}

		/**
		 * Loads textdomain for the plugin.
		 *
		 * @since 1.0.0
		 */
		
		public function load_textdomain() {
			
		}

		/**
		 * Get the API URL.
		 *
		 * @since  1.0.0
		 */

		public static function get_api_domain() {

			return defined( 'STARTER_TEMPLATES_REMOTE_URL' ) ? STARTER_TEMPLATES_REMOTE_URL : apply_filters( 'tmpcoder_api_domain', 'https://themes.templatescoder.com/' );
		}

		/**
		 * Getter for $api_url
		 *
		 * @since  1.0.0
		 */
		public function get_api_url() {
			return $this->api_url;
		}

		/**
		 * Load all the required files in the importer.
		 *
		 * @since  1.0.0
		 */
		private function includes() {

			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/classes/import-helper-functions.php';

			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/classes/class-tmpcoder-plugin-error-handler.php';

			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/classes/compatibility/class-tmpcoder-plugin-compatibility.php';
			
			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/classes/class-tmpcoder-plugin-importer.php';

			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/classes/batch-import/class-tmpcoder-plugin-batch-import.php';
		}

		/**
		 * Get an instance of WP_Filesystem_Direct.
		 *
		 * @since 1.0.0
		 * @return object A WP_Filesystem_Direct instance.
		 */
		public static function get_filesystem() {

			global $wp_filesystem;

			require_once ABSPATH . '/wp-admin/includes/file.php';

			WP_Filesystem();

			return $wp_filesystem;
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Tmpcoder::get_instance();

endif;
