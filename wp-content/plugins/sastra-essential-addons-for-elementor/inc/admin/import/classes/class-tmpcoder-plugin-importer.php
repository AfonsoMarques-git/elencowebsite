<?php
/**
 * Spexo Addons for Elementor Importer
 *
 * @since  1.0.0
 * @package Spexo Addons for Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'TMPCODER_Importer' ) ) {

	/**
	 * Spexo Addons for Elementor Importer
	 */

	class TMPCODER_Importer {

		/**
		 * Instance
		 *
		 * @since  1.0.0
		 * @var (Object) Class object
		 */
		public static $instance = null;

		/**
		 * Set Instance
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

		public function __construct() {

			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/classes/class-tmpcoder-plugin-importer-log.php';

			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/importers/class-tmpcoder-plugin-helper.php';

			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/importers/class-tmpcoder-plugin-widget-importer.php';

			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/importers/class-tmpcoder-plugin-options-import.php';

			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/importers/class-tmpcoder-plugin-woocommerce-attributes-importer.php';

			// Import AJAX.
			
			add_action( 'wp_ajax_tmpcoder-plugin-import-prepare-xml', array( $this, 'prepare_xml_data' ) );
			add_action( 'wp_ajax_tmpcoder-plugin-import-redux-options', array( $this, 'import_redux_options' ) );
			add_action( 'wp_ajax_tmpcoder-plugin-import-revslider-data', array( $this, 'import_revslider_data' ) );
			add_action( 'wp_ajax_tmpcoder-plugin-import-elementor-options', array( $this, 'import_elementor_options' ) );
			add_action( 'wp_ajax_tmpcoder-plugin-import-widgets', array( $this, 'import_widgets' ) );
			add_action( 'init', array( $this, 'load_importer' ) );
			add_action( 'wp_ajax_tmpcoder-plugin-reset-widgets-data', array( $this, 'reset_widgets_data' ) );
			add_action( 'wp_ajax_tmpcoder-plugin-import-end', array( $this, 'import_end' ) );
			add_action( 'tmpcoder_after_import_complete', array( $this, 'clear_related_cache' ) );
			add_action( 'wp_ajax_tmpcoder_activate_required_plugins', array( $this, 'tmpcoder_activate_required_plugins') );
			add_action( 'wp_ajax_tmpcoder_activate_required_theme', array( $this, 'tmpcoder_activate_required_theme') );
			add_action( 'wp_ajax_tmpcoder_fix_plugin_compatibility', array( $this, 'tmpcoder_fix_plugin_compatibility') );
			add_action( 'wp_ajax_tmpcoder_get_prebuilt_demos', array( $this, 'tmpcoder_get_prebuilt_demos') );
			add_action( 'wp_ajax_tmpcoder_download_revslider_plugin', array( $this, 'tmpcoder_download_revslider_plugin') );
		}

		function tmpcoder_get_prebuilt_demos(){

			if (!isset($_POST['_ajax_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajax_nonce'])), 'spexo-addons') || !current_user_can( 'manage_options' ) ) {
		      exit; // Get out of here, the nonce is rotten!
		    }

		    $demo_slug = ( isset( $_REQUEST['demo_slug'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['demo_slug'] ) ) : '';

	        $req_params = array(
	            'action'    => 'get_specific_demo_import_files',
	            'theme'     => TMPCODER_CURRENT_THEME_NAME,
	            'version'   => TMPCODER_CURRENT_THEME_VERSION,
	            'demo_slug' => $demo_slug,
	            'plugin'   => 'sastra-essential-addons-for-elementor',
	            'plugin_version'   => TMPCODER_PLUGIN_VER,
	        );

	        $req_params = apply_filters('tmpcoder_request_param_pro_license_key', $req_params);
	        
	        $options = array(
	            'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),
	            'user-agent' => 'tmpcoder-plugin-user-agent',
	            'headers' => array( 'Referer' => site_url() ),
	        );
	        
	        $theme_request = wp_remote_get(add_query_arg($req_params,TMPCODER_UPDATES_URL), $options);

	        if ( ! is_wp_error( $theme_request ) && wp_remote_retrieve_response_code($theme_request) == 200){

	            $theme_response = wp_remote_retrieve_body($theme_request);
	            $theme_response = (array) json_decode($theme_response);
	            wp_send_json_success( $theme_response );
	            
	        }else{
	            wp_send_json_error(array('status' => 'error', 'message'=> $theme_request->get_error_message()));
	        }
	    }

		/**
		** Deactivate Extra Plugins
		*/
		function tmpcoder_fix_plugin_compatibility() {

		    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons') || !current_user_can( 'manage_options' ) ) {
		      exit; // Get out of here, the nonce is rotten!
		    }

			$add_plugins = [];

		    if (isset($_POST['requiredPlugins'])) {
		    	// Sanitize each element of the plugins array
        		$plugins = array_map('sanitize_text_field',wp_unslash($_POST['requiredPlugins']));

        		if ($plugins) {
        			
        			foreach ($plugins as $slug => $value) {

						$plugin_info = tmpcoder_get_plugin_info_by_slug($slug);        
						if (isset($plugin_info['folder']) && isset($plugin_info['file'])) {
							
							$plugin_path = $plugin_info['folder'].'/'.$plugin_info['file'];					
							$add_plugins[] = $plugin_path;
						}
        			}
        		}
		    }

		    // Get currently active plugins
		    $active_plugins = (array) get_option( 'active_plugins', array() );
		    $active_plugins = array_values($active_plugins);
		    $required_plugins = [
		        'elementor/elementor.php',
		        'elementor-pro/elementor-pro.php',
		        'sastra-essential-addons-for-elementor/sastra-essential-addons-for-elementor.php',
                'sastra-addons-pro/sastra-addons-pro.php',
                'spexo-addons-pro/spexo-addons-pro.php',
		        'contact-form-7/wp-contact-form-7.php',
		        'advanced-custom-fields/acf.php',
		        'woocommerce/woocommerce.php',
		        'redux-framework/redux-framework.php',
		        'mailchimp-for-wp/mailchimp-for-wp.php',
		        'wp-mail-smtp/wp_mail_smtp.php',
		        'updraftplus/updraftplus.php',
		        'temporary-login-without-password/temporary-login-without-password.php',
		        'wp-reset/wp-reset.php'
		    ];
            if ( defined('TMPCODER_PRO_PLUGIN_KEY') ){
                $required_plugins[] = TMPCODER_PRO_PLUGIN_KEY.'/'. TMPCODER_PRO_PLUGIN_KEY .'.php';
            }

		    $required_plugins = array_merge($required_plugins,$add_plugins);

		    // Deactivate Extra Import Plugins
		    foreach ( $active_plugins as $key => $value ) {
		        if ( ! in_array($value, $required_plugins) ) {
		            $active_key = array_search($value, $active_plugins);
		            unset($active_plugins[$active_key]);
		        }
		    }

		    // Set Active Plugins
		    update_option( 'active_plugins', array_values($active_plugins) );

		    // Get Current Theme
		    $theme    = get_option( 'stylesheet' );
		    $template = get_option( 'template' ); // Get parent theme

		    $current_theme      = wp_get_theme();
		    $current_textdomain = $current_theme->get( 'TextDomain' );
		    $parent_textdomain  = $current_theme->parent() ? $current_theme->parent()->get( 'TextDomain' ) : '';

		    $allowed_theme_textdomains = apply_filters(
		    	'tmpcoder_allowed_theme_textdomains_for_import',
		    	array(
		    		'spexo',
		    		'belliza',
		    	)
		    );

		    $should_skip_switch =
		    	in_array( $current_textdomain, $allowed_theme_textdomains, true ) ||
		    	( $parent_textdomain && in_array( $parent_textdomain, $allowed_theme_textdomains, true ) );

		    if ( ! $should_skip_switch && 'spexo' !== $theme ) {
		        switch_theme( 'spexo' );
		    }
		}

		/**
		** Install/Activate Required Theme
		*/
		function tmpcoder_activate_required_theme() {

		    if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash($_POST['nonce'])), 'spexo-addons' )  || !current_user_can( 'manage_options' ) ) {
		      exit; // Get out of here, the nonce is rotten!
		    }
		    
		    // Get Current Theme
		    $theme = get_option('stylesheet');
		    $template = get_option('template'); // Get parent theme

		    // Activate Sastra Theme
		    // Don't switch if Belliza or Belliza Child theme is active
		    $is_belliza_parent = ( $template === 'belliza' );
		    $is_belliza_child = ( $theme !== 'belliza' && $template === 'belliza' && strpos( $theme, 'belliza' ) !== false );
		    
		    if ( ! $is_belliza_parent && ! $is_belliza_child ) {
		        switch_theme( 'spexo' );
		        set_transient( 'sastra_activation_notice', true );
		    }
		}
		
		public function tmpcoder_download_revslider_plugin()
		{
			if (!tmpcoder_is_availble()) {
				wp_send_json_error(__('Something went wrong','sastra-essential-addons-for-elementor'));
			}

			if (!isset($_POST['_ajax_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajax_nonce'])), 'spexo-addons') || !current_user_can( 'manage_options' ) ) {
		      exit; // Get out of here, the nonce is rotten!
		    }

		    $plugin = ( isset( $_REQUEST['plugin'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';

	        $registration  = new \TMPCODER\Admin\TMPCODER_Registration();
			$purchase_code = $registration->get_purchase_code();

	        $req_params = array(
	            'action'        => 'download_revslider_plugin',
	            'plugin'        => $plugin,
	            'plugin_version'=> '',
	            'code'      	=> $purchase_code,
	            'theme_name'    => get_option('stylesheet'),
	        );  

	        $zip_request = add_query_arg($req_params, TMPCODER_UPDATES_URL);
	        
	        $zip_response = wp_remote_get($zip_request,['user-agent' => 'templatescoder-user-agent']);

	        if (is_wp_error($zip_response)) {
				wp_send_json_error($zip_response->get_error_message());	
	        }
	        else
	        {
		        if ($zip_response['response']['code'] == 200) {
		        
	        		$zip = isset($zip_response['body']) ? json_decode($zip_response['body']) : '';		
	        		
	        		if ($zip->source) {

	        			$unzip = $this->tmpcoder_install_plugin_from_zip_url($zip->source);
	        			if ($unzip == true) {
	        				wp_send_json_success($unzip);	
	        			}
	        		}
		        }
	        }
	    }

	    public function tmpcoder_install_plugin_from_zip_url($zip_url) {
		    // Download the zip file
		    $zip_file = download_url($zip_url);

		    if (is_wp_error($zip_file)) {
		        return $zip_file; // Handle errors if download failed
		    }

		    // Unzip the downloaded file in the plugins directory
		    $result = unzip_file($zip_file, WP_PLUGIN_DIR);

		    // Delete the zip file after extraction
		    wp_delete_file($zip_file);

		    if (is_wp_error($result)) {
		        return $result; // Handle errors if extraction failed
		    }

		    return true;
		}

		/**
		** Activate Required Plugins
		*/
		public function tmpcoder_activate_required_plugins() {

		    if ( !isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons') || !current_user_can( 'manage_options' ) ) {
		      exit; // Get out of here, the nonce is rotten!
		    }

		    $plugin_slug = isset($_POST['plugin']) ? sanitize_text_field(wp_unslash($_POST['plugin'])) : '';

		    if ( $plugin_slug ) {
	            if ( !tmpcoder_is_plugin_active_by_slug($plugin_slug) ) {

	                // Get all installed plugins
				    $all_plugins = get_plugins();

				    // Loop through plugins to find the one matching the slug
				    foreach ($all_plugins as $path => $details) {
				        // Check if the slug matches the beginning of the plugin path
				        if (strpos($path, $plugin_slug.'/') === 0 || strpos($path, $plugin_slug.'.php') === 0) {
				            activate_plugin($path);
				        }
				    }
	            }
		    }
		}

		/**
		 * Load WordPress WXR importer.
		 */

		public function load_importer() {

			require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/importers/wxr-importer/class-tmpcoder-plugin-wxr-importer.php';
		}

		/**
		 * Prepare XML Data.
		 *
		 * @since  1.0.0
		 * @return void
		 */

		public function prepare_xml_data() {

			// Verify Nonce.
			check_ajax_referer( 'spexo-addons', '_ajax_nonce' );

			if ( ! current_user_can( 'customize' ) ) {
				wp_send_json_error( __( 'You are not allowed to perform this action', 'sastra-essential-addons-for-elementor' ) );
			}

			if ( ! class_exists( 'XMLReader' ) ) {
				wp_send_json_error( __( 'The XMLReader library is not available. This library is required to import the content for the website.', 'sastra-essential-addons-for-elementor' ) );
			}

			/* Disable Upload MIME Type Support of Media Library Assistant Plugin*/
			
			update_option('mla_enable_upload_mimes', 'unchecked');

			if ( version_compare( get_bloginfo( 'version' ), '5.1.0', '>=' ) ) {
		    add_filter( 'wp_check_filetype_and_ext', [ $this, 'tmpcoder_tmpcoder_real_mime_types_5_1_0' ], 10, 5, 99 );
			} else {
			    add_filter( 'wp_check_filetype_and_ext', [ $this, 'tmpcoder_real_mime_types' ], 10, 4 );
			}

			$wxr_url = ( isset( $_REQUEST['wxr_url'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['wxr_url'] ) ) : '';

			if ( ! tmpcoder_is_valid_url( $wxr_url ) ) {
				/* Translators: %s is XML URL. */
				wp_send_json_error( sprintf( __( 'Invalid Request URL - %s', 'sastra-essential-addons-for-elementor' ), $wxr_url ) );
			}

			TMPCODER_Importer_Log::add( 'Importing from XML ' . $wxr_url );

			$overrides = array(
				'wp_handle_sideload' => 'upload',
			);

			// Download XML file.
			$xml_path = TMPCODER_Helper::download_file( $wxr_url, $overrides );

			if ( $xml_path['success'] ) {

				$post = array(
					'post_title'     => basename( $wxr_url ),
					'guid'           => $xml_path['data']['url'],
					'post_mime_type' => $xml_path['data']['type'],
				);

				TMPCODER_Importer_Log::add( wp_json_encode( $post ) );
				TMPCODER_Importer_Log::add( wp_json_encode( $xml_path ) );

				// as per wp-admin/includes/upload.php.
				$post_id = wp_insert_attachment( $post, $xml_path['data']['file'] );

				TMPCODER_Importer_Log::add( wp_json_encode( $post_id ) );

				if ( is_wp_error( $post_id ) ) {
					wp_send_json_error( __( 'There was an error downloading the XML file.', 'sastra-essential-addons-for-elementor' ) );
				} else {

					update_option( 'tmpcoder_imported_wxr_id', $post_id, 'no' );
					$attachment_metadata = wp_generate_attachment_metadata( $post_id, $xml_path['data']['file'] );
					wp_update_attachment_metadata( $post_id, $attachment_metadata );
					$data        = TMPCODER_Plugin_Wxr_Importer::instance()->get_xml_data( $xml_path['data']['file'], $post_id );
					$data['xml'] = $xml_path['data'];
					wp_send_json_success( $data );
				}
			} else {
				wp_send_json_error( $xml_path['data'] );
			}
		}

		function tmpcoder_tmpcoder_real_mime_types_5_1_0( $defaults, $file, $filename, $mimes, $real_mime ) {
		    
		   $ext = pathinfo( $filename, PATHINFO_EXTENSION );
			
			// TMPCODER_Importer_Log::add('im here - '.$ext);

		  	if ( 'svg' === $ext ) {
				
				TMPCODER_Importer_Log::add($ext);
				TMPCODER_Importer_Log::add($defaults);

	      		$defaults['ext']  = 'svg';
	      		$defaults['type'] = 'image/svg+xml';
	      		return $defaults;
		  	}

		    return $this->tmpcoder_real_mimes( $defaults, $filename, $mimes );

		}

		function tmpcoder_real_mime_types( $defaults, $file, $filename, $mimes ) {

			$ext = pathinfo( $filename, PATHINFO_EXTENSION );
			
		  	if ( 'svg' === $ext ) {

				TMPCODER_Importer_Log::add($ext);
				TMPCODER_Importer_Log::add($defaults);

	      		$defaults['ext']  = 'svg';
	      		$defaults['type'] = 'image/svg+xml';
	      		return $defaults;
		  	}

		    return $this->tmpcoder_real_mimes( $defaults, $filename, $mimes );
		}

		function tmpcoder_real_mimes( $defaults, $filename, $mimes ) {
	        $defaults['ext']  = 'xml';
	        $defaults['type'] = 'text/xml';

	       //  $defaults['ext']  = 'svg';
      		// $defaults['type'] = 'image/svg+xml';
	       //  TMPCODER_Importer_Log::add($defaults);

		    return $defaults;
		}

		/**
		 * Import Redux Options
	 	 */

		public function import_redux_options(){

			// Verify Nonce.
			check_ajax_referer( 'spexo-addons', '_ajax_nonce' );

			if ( ! current_user_can( 'customize' ) ) {
				wp_send_json_error( __( 'You are not allowed to perform this action', 'sastra-essential-addons-for-elementor' ) );
			}

			// Redux plugin is not active!

			if ( ! class_exists( 'ReduxFramework' ) ) {
				wp_send_json_error( __( 'The Redux plugin is not activated, so the Redux import was skipped!', 'sastra-essential-addons-for-elementor' ) );
			}

			$redux_file_url = ( isset( $_REQUEST['redux_file_url'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['redux_file_url'] ) ) : '';

			if ( ! tmpcoder_is_valid_url( $redux_file_url ) ) {
				/* Translators: %s is File URL. */
				wp_send_json_error( sprintf( __( 'Invalid Request URL - %s', 'sastra-essential-addons-for-elementor' ), $redux_file_url ) );
			}

			$overrides = array(
				'wp_handle_sideload' => 'upload',
			);

			// Download Redux Options file.
			$redux_file = TMPCODER_Helper::download_file( $redux_file_url, $overrides );

			if ($redux_file['success']) {
					
				$redux_file_path = $redux_file['data']['file'];

				$redux_options_raw_data = TMPCODER_Helper::data_from_file( $redux_file_path );

				$redux_options_data = json_decode( $redux_options_raw_data, true );

				$redux_framework = \ReduxFrameworkInstances::get_instance( TMPCODER_THEME_OPTION_NAME );

				if ( isset( $redux_framework->args['opt_name'] ) ) {
					
					// Import Redux settings.
					$redux_framework->set_options( $redux_options_data );

			        $update_setting = 0;
			        $nav_menu_location_arr = array();
			        $theme_options = get_option(TMPCODER_THEME_OPTION_NAME);
                    if ( empty($theme_options) ){
                        $theme_options = get_option('tmpcoder_global_theme_options_sastrawp');
                    }

			        if ( $update_setting == 1 ){
			            update_option(TMPCODER_THEME_OPTION_NAME, $theme_options);
                        update_option('tmpcoder_global_theme_options_sastrawp', $theme_options); // old theme
			        }
			        
			        // ======================================================================
			        
			        update_option( 'blogdescription', ''); // remove tagline

					wp_send_json_success($redux_options_data);
					
				}
				else { /* translators: %s - the name of the Redux option. */
					
					// Write error to log file.
					TMPCODER_Importer_Log::add('The Redux option name was not found in this WP site, so it was not imported!');

					wp_send_json_error( __( 'The Redux option name was not found in this WP site, so it was not imported!', 'sastra-essential-addons-for-elementor' ) );
				}
			}
		}

        function tmpcoder_get_page_by_title($page_title){
            $query = new WP_Query(
                array(
                    'post_type'              => 'page',
                    'title'                  => $page_title,
                    'post_status'            => 'all',
                    'posts_per_page'         => 1,
                    'no_found_rows'          => true,
                    'ignore_sticky_posts'    => true,
                    'update_post_term_cache' => false,
                    'update_post_meta_cache' => false,
                    'orderby'                => 'post_date ID',
                    'order'                  => 'ASC',
                )
            );
             
            if ( ! empty( $query->post ) ) {
                $page_got_by_title = $query->post;
            } else {
                $page_got_by_title = null;
            }
            return $page_got_by_title;
        }

		/**
		 * Import Elementor Options
	 	 */

		public function import_elementor_options(){
			
			if ( wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'spexo-addons', '_ajax_nonce' );

				if ( ! current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'sastra-essential-addons-for-elementor' ) );
				}
			}

			$elementor_file_url = ( isset( $_REQUEST['elementor_file_url'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['elementor_file_url'] ) ) : '';

			if ( ! tmpcoder_is_valid_url( $elementor_file_url ) ) {
				/* Translators: %s is File URL. */
				wp_send_json_error( sprintf( __( 'Invalid Request URL - %s', 'sastra-essential-addons-for-elementor' ), $elementor_file_url ) );
			}

			$overrides = array(
				'wp_handle_sideload' => 'upload',
			);

			// Download Redux Options file.
			$elementor_file = TMPCODER_Helper::download_file( $elementor_file_url, $overrides );	

			$wp_upload_dir = wp_upload_dir();
			$extracted_path = $wp_upload_dir['basedir'].'/elementor/tmp/';

			$unzip_file = unzip_file($elementor_file['data']['file'],$extracted_path);

			if ($unzip_file) {

			    global $wp_filesystem;

				if(is_dir($extracted_path))
				{
					if ($read_extracted_files = opendir($extracted_path)) {

						$options_data_array = [];
						
						while (($file = readdir($read_extracted_files)) !== false){
					    	
					    	if (wp_check_filetype($file)['ext'] == 'json') {

					      		$file_get_contents = $wp_filesystem->get_contents($extracted_path.$file);

					      		$single_option = json_decode($file_get_contents,true);

						      	array_push($options_data_array, $single_option);	
				    		}
					    }
					}

					$customArr = ['elementor_disable_color_schemes'=> 'yes','elementor_disable_typography_schemes'=> 'yes','elementor_experiment-e_optimized_css_loading'=>'inactive'];
					
					$options_data_array = array_merge($options_data_array,$customArr);

					closedir($read_extracted_files);

				    if ( ! empty( $options_data_array ) ) {
						// Set meta for tracking the post.

						if ( is_array( $options_data_array ) ) {
							TMPCODER_Importer_Log::add( 'Imported - Site Options ' . wp_json_encode( $options_data_array ) );
							update_option( '_tmpcoder_old_site_options', $options_data_array, 'no' );
						}

						$options_importer = TMPCODER_Options_Import::instance();
						$options_importer->import_options( $options_data_array );
						
						wp_send_json_success( $options_data_array );
							
					} else {
						
						wp_send_json_error( __( 'Site options are empty!', 'sastra-essential-addons-for-elementor' ) );
					}
				}
			}
		}

		/**
		 * Import Revolution slider data
	 	 */

		public function import_revslider_data(){

			// Verify Nonce.
			check_ajax_referer( 'spexo-addons', '_ajax_nonce' );

			if ( ! current_user_can( 'customize' ) ) {
				wp_send_json_error( __( 'You are not allowed to perform this action', 'sastra-essential-addons-for-elementor' ) );
			}

			// Revolution slider plugin is not active!

			if ( ! class_exists( 'RevSliderSliderImport' ) ) {
				wp_send_json_error( __( 'The Revolution slider plugin is not activated, so the Revolution slider import was skipped!', 'sastra-essential-addons-for-elementor' ) );
			}

			$revslider_file_url = ( isset( $_REQUEST['revslider_file_url'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['revslider_file_url'] ) ) : '';

			if ( ! tmpcoder_is_valid_url( $revslider_file_url ) ) {
				/* Translators: %s is File URL. */
				wp_send_json_error( sprintf( __( 'Invalid Request URL - %s', 'sastra-essential-addons-for-elementor' ), $revslider_file_url ) );
			}

			$overrides = array(
				'wp_handle_sideload' => 'upload',
			);

			// Download revslider file .
			$revslider_file = TMPCODER_Helper::download_file( $revslider_file_url, $overrides );

			if ($revslider_file['success']) {
				
				$revslider_file_path = $revslider_file['data']['file'];
				$slider = new RevSliderSliderImport();

				ob_start();
				$result = $slider->import_slider( true, $revslider_file_path );
				ob_clean();
				ob_end_clean();

				if ( true === $result['success'] ) {
					wp_send_json_success();
				}
				else { /* translators: %s - the name of the Redux option. */
					
					// Write error to log file.
					TMPCODER_Importer_Log::add('The Revolution Slider data was not found in this WP site, so it was not imported!');

					wp_send_json_error( __( 'The Revolution Slider data was not found in this WP site, so it was not imported!', 'sastra-essential-addons-for-elementor' ) );
				}
			}
			else
			{
				wp_send_json_error($revslider_file);
			}
		}

		/**
		 * Import Widgets.
		 *
		 * @since 1.0.0
		 * @since 1.0.0 The `$widgets_data` was added.
		 *
		 * @param  string $widgets_data Widgets Data.
		 * @return void
		 */

		public function import_widgets( $widgets_data = '' ) {

			if ( wp_doing_ajax() ) {
				
				// Verify Nonce.
				check_ajax_referer( 'spexo-addons', '_ajax_nonce' );

				if ( ! current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'sastra-essential-addons-for-elementor' ) );
				}
			}

			$widgets_file_url = ( isset( $_REQUEST['widgets_file_url'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['widgets_file_url'] ) ) : '';

			if ( ! tmpcoder_is_valid_url( $widgets_file_url ) ) {
				/* Translators: %s is File URL. */
				wp_send_json_error( sprintf( __( 'Invalid Request URL - %s', 'sastra-essential-addons-for-elementor' ), $widgets_file_url ) );
			}

			$get_widgets_data = TMPCODER_Helper::data_from_file( $widgets_file_url );

			$widgets_data = json_decode($get_widgets_data);

			if ( ! empty( $widgets_data ) ) {

				TMPCODER_Widget_Importer::instance()->import_widgets_data( $widgets_data );

				$sidebars_widgets = get_option( 'sidebars_widgets', array() );

				update_option( '_tmpcoder_old_widgets_data', $sidebars_widgets, 'no' );
				TMPCODER_Importer_Log::add( 'Imported - Widgets ' . wp_json_encode( $sidebars_widgets ) );

				if ( wp_doing_ajax() ) {
					wp_send_json_success( $widgets_data );
				}
			} else {
				if ( wp_doing_ajax() ) {
					wp_send_json_error( __( 'Widget data is empty!', 'sastra-essential-addons-for-elementor' ));
				}
			}
		}

		/**
		 * Import End.
		 *
		 * @since 1.0.0
		 * @return void
		 */

		public function import_end() {

			if ( wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'spexo-addons', '_ajax_nonce' );

				if ( ! current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'sastra-essential-addons-for-elementor' ) );
				}
			}

            // Update Current demo body class
            $tmpcoder_current_active_demo = (isset($_REQUEST['tmpcoder_current_active_demo'])) ? sanitize_text_field(wp_unslash($_REQUEST['tmpcoder_current_active_demo'])) : '';

            if (!empty($tmpcoder_current_active_demo)) {

                update_option('tmpcoder_current_active_demo', $tmpcoder_current_active_demo);
            }

			update_option( 'tmpcoder_import_complete', 'yes', 'no' );
			delete_transient( 'tmpcoder_import_started' );
			delete_option( 'tmpcoder_recent_import_log_file' );

            // Elementor options cache clear
            if ( did_action( 'elementor/loaded' ) ) {
                // Automatically purge and regenerate the Elementor CSS cache
                \Elementor\Plugin::instance()->files_manager->clear_cache();
            }

            if ( function_exists('tmpcoder_redux_options_update_theme_variable') ){
            	$TMPCODER_THEME_OPTIONS_DATA_CLASS = TMPCODER_THEME_OPTIONS_DATA_CLASS;
                $theme_options = $TMPCODER_THEME_OPTIONS_DATA_CLASS::tmpcoder_get_all_data();
                tmpcoder_redux_options_update_theme_variable($theme_options, '', '');
            }

			do_action( 'tmpcoder_after_import_complete' );

			/* Update Woo commerce page setting start */
			$woo_cart_page_id = tmpcoder_get_post_id_by_meta_key_and_meta_value('tmpcoder_cart_page');
			$woo_shop_page_id = tmpcoder_get_post_id_by_meta_key_and_meta_value('tmpcoder_shop_page');
			$woo_checkout_page_id = tmpcoder_get_post_id_by_meta_key_and_meta_value('tmpcoder_checkout_page');
			$woo_myaccount_page_id = tmpcoder_get_post_id_by_meta_key_and_meta_value('tmpcoder_myaccount_page');
            $tmpcoder_privacy_policy_page_id = tmpcoder_get_post_id_by_meta_key_and_meta_value('tmpcoder_privacypolicy_page');
            
			update_option('woocommerce_cart_page_id', $woo_cart_page_id);			
			update_option('woocommerce_shop_page_id', $woo_shop_page_id);			
			update_option('woocommerce_checkout_page_id', $woo_checkout_page_id);
			update_option('woocommerce_myaccount_page_id', $woo_myaccount_page_id);
            update_option('wp_page_for_privacy_policy', $tmpcoder_privacy_policy_page_id);

            // Update Options
        	update_option( 'woocommerce_queue_flush_rewrite_rules', 'yes' );
        	/* Update Woo commerce page setting end */

            // Set Home & Blog Pages
		    $home_page_id = tmpcoder_get_post_id_by_meta_key_and_meta_value('tmpcoder_theme_page_on_front');
		    $blog_page_id = tmpcoder_get_post_id_by_meta_key_and_meta_value('tmpcoder_theme_page_for_posts');
		    if ( $home_page_id || $blog_page_id ) {
		        update_option( 'show_on_front', 'page' );
		        if ( $home_page_id ) {
		        	update_option( 'page_on_front', $home_page_id );
		    	}		        
		        if ( $blog_page_id ) {
		            update_option( 'page_for_posts', $blog_page_id );
		        }
		    }

		    // set post per page
		    $posts_per_page = get_post_meta( tmpcoder_get_post_id_by_meta_key_and_meta_value('tmpcoder_theme_posts_per_page'), 'tmpcoder_theme_posts_per_page' );
		    $posts_per_rss = get_post_meta( tmpcoder_get_post_id_by_meta_key_and_meta_value('tmpcoder_theme_posts_per_rss'), 'tmpcoder_theme_posts_per_rss' );
		    if ( $posts_per_page || $posts_per_rss ) {
		        update_option( 'show_on_front', 'page' );
		        if ( $posts_per_page ) {
		            update_option( 'posts_per_page', $posts_per_page[0] );
		        }
		        if ( $posts_per_rss ) {
		            update_option( 'posts_per_rss', $posts_per_rss[0] );
		        }
		    }

		    // Change Hello World Post Status
		    $post = get_page_by_path('hello-world', OBJECT, 'post');
		    if ( $post ) {
		        wp_update_post(array(
		        	'ID' => $post->ID,
		        	'post_status' => 'draft'
		        ));
		    }

			// Replace demo url to site url

			$tmpcoder_demo_url = (isset($_REQUEST['tmpcoder_demo_url'])) ? sanitize_text_field( wp_unslash( $_REQUEST['tmpcoder_demo_url'] ) ) : '';

			$tmpcoder_demo_url = esc_url_raw($tmpcoder_demo_url);

			$this->demo_replace_url_aciton($tmpcoder_demo_url);

			delete_option('tmpcoder_is_call_retry');
			delete_option('tmpcoder_already_exists_post');

			add_action('init', 'clear_related_cache');

			if ( wp_doing_ajax() ) {
				wp_send_json_success();
			}
		}

		public function demo_replace_url_aciton($demo_site_raw_url=''){
			
			if ( $demo_site_raw_url == "" ){
				return '';
			}

			// Replace Demo with Current
            $site_url_raw = get_site_url().'/';
            $site_url = str_replace( '/', '\/', $site_url_raw );

			$args = array(
		        'post_type' => ['theme-advanced-hook', 'tmpcoder_mega_menu', 'page'],
		        'posts_per_page' => '-1',
		        'meta_key' => '_elementor_version'
		    );

		    $elementor_pages = new WP_Query ( $args );

		    /* Replace parallax image url - start */

			$upload_dir = wp_upload_dir();
			$current_upload_url = trailingslashit($upload_dir['baseurl']); // dynamic media folder
			$current_upload_url_escaped = str_replace('/', '\\/', $current_upload_url);

			$old_prefix_raw = $demo_site_raw_url . 'wp-content/uploads/';
			$old_prefix_raw = str_replace('/', '\\/', $old_prefix_raw);

			/* Replace parallax image url - end */

		    // Check that we have query results.
		    if ( $elementor_pages->have_posts() ) {
		     
		        // Start looping over the query results.
		        while ( $elementor_pages->have_posts() ) {

		            $elementor_pages->the_post();

		            $demo_site_url = str_replace( '/', '\/', $demo_site_raw_url );
		            $demo_import_url = str_replace( '\/wp\/', '\/wp\/import-files\/', $demo_site_url );
		            $demo_import_url2 = str_replace( '\/wp\/', '/wp/import-files/', $demo_site_url );

		            // Elementor Data
		            $data = get_post_meta( get_the_ID(), '_elementor_data', true );

		            if ( ! empty( $data ) ) {
		                $data = preg_replace('/\\\{1}\/sites\\\{1}\/\d+/', '', $data);
		                $data = str_replace( $old_prefix_raw, $current_upload_url_escaped, $data );
		                $data = str_replace( $demo_site_url, $site_url, $data );
		                $data = str_replace( $demo_import_url, $site_url, $data );
		                $data = str_replace( $demo_import_url2, $site_url_raw, $data );
		                $data = json_decode( $data, true );
		            }

		            update_metadata( 'post', get_the_ID(), '_elementor_data', $data );

		            // Elementor Page Settings
		            $page_settings = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
		            $page_settings = wp_json_encode($page_settings);

		            if ( ! empty( $page_settings ) ) {
		                $page_settings = preg_replace('/\\\{1}\/sites\\\{1}\/\d+/', '', $page_settings);
		                $page_settings = str_replace( $demo_site_url, $site_url, $page_settings );
		                $page_settings = json_decode( $page_settings, true );
		            }

		            update_metadata( 'post', get_the_ID(), '_elementor_page_settings', $page_settings );

		        }
		    }

		    // Replace URLs in WordPress navigation menus
			$menus = get_terms(array('taxonomy'=> 'nav_menu','hide_empty' => true));

			foreach ($menus as $menu) {
			    $menu_items = wp_get_nav_menu_items($menu->term_id);

			    foreach ($menu_items as $menu_item) {
			        $menu_item_url = get_post_meta($menu_item->ID, '_menu_item_url', true);
			        // Replace old URL with new URL
			        $updated_url = str_replace($demo_site_raw_url, $site_url_raw, $menu_item_url);
			        // Update menu item URL
        			update_post_meta($menu_item->ID, '_menu_item_url', $updated_url);
			    }
			}

		    if ( did_action( 'elementor/loaded' ) ) {
                // Automatically purge and regenerate the Elementor CSS cache
                \Elementor\Plugin::instance()->files_manager->clear_cache();
            }
		}
		
		/**
		 * Reset widgets data
		 *
		 * @since 1.0.0
		 * @return void
		 */

		public function reset_widgets_data() {

			if ( wp_doing_ajax() ) {
				
				// Verify Nonce.

				check_ajax_referer( 'spexo-addons', '_ajax_nonce' );

				if ( ! current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'sastra-essential-addons-for-elementor' ) );
				}
			}

			// Get all old widget ids.
			$old_widgets_data = (array) get_option( '_tmpcoder_old_widgets_data', array() );
			$old_widget_ids = array();
			foreach ( $old_widgets_data as $old_sidebar_key => $old_widgets ) {
				if ( ! empty( $old_widgets ) && is_array( $old_widgets ) ) {
					$old_widget_ids = array_merge( $old_widget_ids, $old_widgets );
				}
			}

			// Process if not empty.
			$sidebars_widgets = get_option( 'sidebars_widgets', array() );
			if ( ! empty( $old_widget_ids ) && ! empty( $sidebars_widgets ) ) {

				TMPCODER_Importer_Log::add( 'DELETED - WIDGETS ' . wp_json_encode( $old_widget_ids ) );

				foreach ( $sidebars_widgets as $sidebar_id => $widgets ) {
					$widgets = (array) $widgets;

					if ( ! empty( $widgets ) && is_array( $widgets ) ) {
						foreach ( $widgets as $widget_id ) {

							if ( in_array( $widget_id, $old_widget_ids, true ) ) {
								TMPCODER_Importer_Log::add( 'DELETED - WIDGET ' . $widget_id );

								// Move old widget to inacitve list.
								$sidebars_widgets['wp_inactive_widgets'][] = $widget_id;

								// Remove old widget from sidebar.
								$sidebars_widgets[ $sidebar_id ] = array_diff( $sidebars_widgets[ $sidebar_id ], array( $widget_id ) );
							}
						}
					}
				}

				update_option( 'sidebars_widgets', $sidebars_widgets );
			}

			if ( wp_doing_ajax() ) {
				wp_send_json_success( __( 'Deleted Widgets!', 'sastra-essential-addons-for-elementor' ) );
			}
		}

		/**
		 * Clear Cache.
		 *
		 * @since  1.0.0
		 */
		public function clear_related_cache() {

			// Flush permalinks.
			flush_rewrite_rules();
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	TMPCODER_Importer::get_instance();
}
