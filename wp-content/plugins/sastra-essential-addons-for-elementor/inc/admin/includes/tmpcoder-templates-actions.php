<?php
use Elementor\TemplateLibrary\Source_Base;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once TMPCODER_PLUGIN_DIR.'inc/header-footer-helper/header-footer-elements.php';

/**
 * TMPCODER_Templates_Actions setup
 *
 * @since 1.0
 */
class TMPCODER_Templates_Actions {

	/**
	** Constructor
	*/
	public function __construct() {

		// Import Library Template
		add_action( 'wp_ajax_tmpcoder_import_library_template', [ $this, 'tmpcoder_import_library_template' ] );

		// Create Template
		add_action( 'wp_ajax_tmpcoder_create_template', [ $this, 'tmpcoder_create_template' ] );

		// Reset Template
		add_action( 'wp_ajax_tmpcoder_delete_template', [ $this, 'tmpcoder_delete_template' ] );

		// Save Conditions
		add_action( 'wp_ajax_tmpcoder_save_template_conditions', [ $this, 'tmpcoder_save_template_conditions' ] );

		// Select Conditions
		add_action( 'wp_ajax_tmpcoder_select_popup_conditions', [ $this, 'tmpcoder_select_popup_conditions' ] );

		// Register Elementor AJAX Actions
		add_action( 'elementor/ajax/register_actions', [ $this, 'tmpcoder_register_elementor_ajax_actions' ] );
	}

	/**
	** Select Template Conditions
	*/

	public function tmpcoder_select_popup_conditions() {

		if ( ! isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'tmpcoder-plugin-options-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$template_id = isset($_POST['template_id']) ? sanitize_text_field(wp_unslash($_POST['template_id'])): false;

		$include_locations = get_post_meta( $template_id, 'tmpcoder_target_include_locations', true );

		TMPCODER_Target_Rules_Fields::target_rule_settings_field(
			'bsf-target-rules-location',
			[
				'title'          => __( 'Display Rules', 'sastra-essential-addons-for-elementor' ),
				'value'          => '[{"type":"basic-global","specific":null}]',
				'tags'           => 'site,enable,target,pages',
				'rule_type'      => 'display',
				'add_rule_label' => __( 'Add Display Rule', 'sastra-essential-addons-for-elementor' ),
			],
			$include_locations
		);
		exit();
	}

	/**
	** Save Template Conditions
	*/
	public function tmpcoder_save_template_conditions() {

		if ( ! isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'tmpcoder-plugin-options-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$template = isset($_POST['template']) ? sanitize_text_field(wp_unslash($_POST['template'])): false;

		$post_id = tmpcoder_get_template_id($template);

        // Usage with $_POST
        if ( isset($_POST['bsf-target-rules-location']) ){
		    $sanitized_post = json_decode(sanitize_text_field(wp_unslash($_POST['bsf-target-rules-location'])) , true);
            if ( empty($sanitized_post) ){
                $sanitized_post = array();
            }
        }else{
            $sanitized_post = array();
        }

        if ( isset($sanitized_post['specific']) && !empty($sanitized_post['specific']) ){
            $sanitized_post['specific'] = array_filter($sanitized_post['specific']);
            if ( !empty($sanitized_post['specific']) ){
                $specificArr = array();
                foreach ($sanitized_post['specific'] as $key => $value) {
                    $specificArr = array_merge($specificArr, $value);
                }
                $sanitized_post['specific'] = $specificArr;
            }
        }

        $tmpcoder_target_rules_array = array();
        $tmpcoder_target_rules_array['bsf-target-rules-location'] = $sanitized_post;

		$target_locations = TMPCODER_Target_Rules_Fields::get_format_rule_value( $tmpcoder_target_rules_array, 'bsf-target-rules-location' );

        update_post_meta( $post_id, 'tmpcoder_target_include_locations', $target_locations );
	}

	/**
	** Import Library Template
	*/

	public function tmpcoder_import_library_template() {

		if ( ! isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'tmpcoder-library-frontend-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        $source = new TMPCODER_Library_Source();
		$slug = isset($_POST['slug']) ? sanitize_text_field(wp_unslash($_POST['slug'])) : '';
		$kit = isset($_POST['kit']) ? sanitize_text_field(wp_unslash($_POST['kit'])) : '';
		$section = isset($_POST['section']) ? sanitize_text_field(wp_unslash($_POST['section'])) : '';

        $data = $source->get_data([
        	'template_id' => $slug,
			'kit_id' => $kit,
			'section_id' => $section,
			'code' => '03DE8-0CE62-C7A95-893AA-91A8F',
        ]);
        
		echo wp_json_encode($data);
	}

	/**
	** Reset Template
	*/
	public function tmpcoder_delete_template() {

		if ( !isset($_POST['nonce']) || !isset($_POST['template_slug']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])),'delete_post-'.sanitize_text_field(wp_unslash($_POST['template_slug']) ) ) || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$template_slug = isset($_POST['template_slug']) ? sanitize_text_field(wp_unslash($_POST['template_slug'])): '';
		
		$template_library = isset($_POST['template_library']) ? sanitize_text_field(wp_unslash($_POST['template_library'])): '';

		$post = get_page_by_path( $template_slug, OBJECT, $template_library );
		
		if ( get_post_type($post->ID) == TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE || get_post_type($post->ID) == 'elementor_library') {
			wp_delete_post( $post->ID, true );
		}
	}

	/**
	** Create Template
	*/
	public function tmpcoder_create_template() {

		if ( !isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'tmpcoder-plugin-options-js') || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$user_template_type = isset($_POST['user_template_type']) ? sanitize_text_field(wp_unslash($_POST['user_template_type'])): false;
		$user_template_library = isset($_POST['user_template_library']) ? sanitize_text_field(wp_unslash($_POST['user_template_library'])): false;
		$user_template_title = isset($_POST['user_template_title']) ? sanitize_text_field(wp_unslash($_POST['user_template_title'])): false;
		$user_template_slug = isset($_POST['user_template_slug']) ? sanitize_text_field(wp_unslash($_POST['user_template_slug'])): false;
		
		$check_post_type =( $user_template_library == TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE || $user_template_library == 'elementor_library' );

		if ( $user_template_title && $check_post_type ) {
			// Create
			$template_id = wp_insert_post(array (
				'post_type' 	=> $user_template_library,
				'post_title' 	=> $user_template_title,
				'post_name' 	=> $user_template_slug,
				'post_content' 	=> '',
				'post_status' 	=> 'publish'
			));

			// Set Types
			if ( TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE === sanitize_text_field(wp_unslash($_POST['user_template_library'])) ) {

				wp_set_object_terms( $template_id, [$user_template_type, 'user'], 'tmpcoder_template_type' );

				if ( 'popup' === sanitize_text_field(wp_unslash($_POST['user_template_type'])) ) {
					update_post_meta( $template_id, '_elementor_template_type', 'tmpcoder-popups' );
				} else {
					if ( 'type_header' === sanitize_text_field(wp_unslash($_POST['user_template_type'])) ) {
						update_post_meta( $template_id, '_elementor_template_type', 'tmpcoder-theme-builder-header' );
						update_post_meta( $template_id, 'tmpcoder_template_type', $user_template_type );
					} elseif ( 'type_footer' === sanitize_text_field(wp_unslash($_POST['user_template_type'])) ) {
						update_post_meta( $template_id, '_elementor_template_type', 'tmpcoder-theme-builder-footer' );
						update_post_meta( $template_id, 'tmpcoder_template_type', $user_template_type );
					} else {
						update_post_meta( $template_id, '_elementor_template_type', 'tmpcoder-theme-builder' );
						update_post_meta( $template_id, 'tmpcoder_template_type', $user_template_type );
					}

					update_post_meta( $template_id, 'tmpcoder_template_type', $user_template_type );
				}
			} else {
				update_post_meta( $template_id, '_elementor_template_type', 'page' );
			}

			// Set Canvas Template
			update_post_meta( $template_id, '_wp_page_template', 'elementor_canvas' ); //tmp - maybe set for tmpcoder_templates only

			// Send ID to JS
			echo absint($template_id);
		}

		flush_rewrite_rules();
	}

	/**
	** Register Elementor AJAX Actions
	*/

	public function tmpcoder_register_elementor_ajax_actions( Ajax $ajax )
	{
		// Elementor Search Data
		$ajax->register_ajax_action( 'tmpcoder_backend_search_query_results_func', function( $data ) {
			if ( strpos($_SERVER['SERVER_NAME'],'instawp') || strpos($_SERVER['SERVER_NAME'],'tastewp') ) {
			// return;
		}
	    
	    $search_query = isset($data['search_query']) ? sanitize_text_field(wp_unslash($data['search_query'])) : '';

	    $type = isset($data['type']) ? sanitize_text_field(wp_unslash($data['type'])) : '';

	    $req_params = array(
	        'action'    	=> 'save_search_query_data',
	        'search_query'  => $search_query,
	        'type' => $type,
	    );
	    		
	    $options = array(
	        'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),
	        'user-agent' => 'tmpcoder-plugin-user-agent',
	    );
	    
	    $api_url = TMPCODER_UPDATES_URL;
	    $theme_request = wp_remote_get(add_query_arg($req_params, $api_url), $options);
		} );
	}
}

/**
 * TMPCODER_Templates_Actions setup
 *
 * @since 1.0
 */
class TMPCODER_Library_Source extends \Elementor\TemplateLibrary\Source_Base {

	public function get_id() {
		return 'tmpcoder-layout-manager';
	}

	public function get_title() {
		return 'TMPCODER Layout Manager';
	}

	public function register_data() {}

	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot save template to a TMPCODER layout manager' );
	}

	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot update template to a TMPCODER layout manager' );
	}

	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot delete template from a TMPCODER layout manager' );
	}

	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot export template from a TMPCODER layout manager' );
	}

	public function get_items( $args = [] ) {
		return [];
	}

	public function get_item( $template_id ) {
		$templates = $this->get_items();

		return $templates[ $template_id ];
	}

	public function request_template_data( $template_id, $kit_id, $section_id ) {
		if ( empty( $template_id ) ) {
			return;
		}

		if ( '' !== $kit_id ) {
			$url =  TMPCODER_DEMO_IMPORT_API . 'template-kit/'. $kit_id .'/';
            
		} elseif ( '' !== $section_id ) {
			$url = TMPCODER_DEMO_IMPORT_API . 'prebuild-section/';
		} else {
			$url = TMPCODER_DEMO_IMPORT_API . 'prebuild-block/';
		}

		$req_params = [];
		$license_key = apply_filters('tmpcoder_request_param_pro_license_key', $req_params);
		$license_key_params = isset( $license_key ) ? '&'.http_build_query( $license_key ) : '' ;

		// Avoid apc_cache_info()
		$randomNum = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 7);
		
        $response = wp_remote_get($url . $template_id .'.json?='. $randomNum.$license_key_params, [
			'timeout'   => 60,
			'sslverify' => false,
            'user-agent' => 'templatescoder-user-agent',
            'headers' => array( 'Referer' => site_url() ),
		] );
		
		return wp_remote_retrieve_body( $response );
	}

    /**
	** Disable Extra Image Sizes
	*/
    public static function disable_extra_image_sizes( $new_sizes, $image_meta, $attachment_id ) {
		$all_attachments = get_option( 'tmpcoder_st_attachments', array() );

		// If the cron job is already scheduled, bail.
		if ( in_array( $attachment_id, $all_attachments, true ) ) {
			return $new_sizes;
		}

		$all_attachments[] = $attachment_id;

		update_option( 'tmpcoder_st_attachments', $all_attachments, 'no' );

		// Return blank array of sizes to not generate any sizes in this request.
		return array();
	}

    /**
	** Regenerate Extra Image Sizes
	*/
	public static function regenerate_extra_image_sizes() {
		$all_attachments = get_option( 'tmpcoder_st_attachments', array() );
	
		if ( empty( $all_attachments ) ) {
			return;
		}
	
		foreach ( $all_attachments as $attachment_id ) {
			$file = get_attached_file( $attachment_id );
			if ( false !== $file ) {
				wp_generate_attachment_metadata( $attachment_id, $file );
			}
		}
		update_option( 'tmpcoder_st_attachments', array(), 'no' );
	}

	public function get_data( array $args ) {
		$data = $this->request_template_data( $args['template_id'], $args['kit_id'], $args['section_id'] );

		$data = json_decode( $data, true );

		if ( empty( $data ) || empty( $data['content'] ) ) {
			throw new \Exception( 'Template does not have any content' );
		}

		add_filter( 'intermediate_image_sizes_advanced', [$this, 'disable_extra_image_sizes'], 10, 3 );

		$parallax_bg = get_option('tmpcoder-parallax-background', 'on');
		$parallax_multi = get_option('tmpcoder-parallax-multi-layer', 'on');

		// Disable Extensions during Import
		if ( 'on' === $parallax_bg ) {
			update_option('tmpcoder-parallax-background', '');
		}
		if ( 'on' === $parallax_multi ) {
			update_option('tmpcoder-parallax-multi-layer', '');
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );		
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		// Enable Back
		if ( 'on' === $parallax_bg ) {
			update_option('tmpcoder-parallax-background', 'on');
		}
		if ( 'on' === $parallax_multi ) {
			update_option('tmpcoder-parallax-multi-layer', 'on');
		}

		return $data;
	}
}