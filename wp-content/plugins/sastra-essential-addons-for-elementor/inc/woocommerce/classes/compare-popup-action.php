<?php
namespace TMPCODER\Classes;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TMPCODER_Compare_Popup_Action setup
 *
 * @since 1.0
 */
class TMPCODER_Compare_Popup_Action { 

    public function __construct() {
        add_action('wp_ajax_tmpcoder_get_page_content', [$this, 'tmpcoder_get_page_content']);
        add_action('wp_ajax_nopriv_tmpcoder_get_page_content', [$this, 'tmpcoder_get_page_content']);
    }

    function tmpcoder_get_page_content($request) {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons')) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'sastra-essential-addons-for-elementor'),
			));
		}

        $page_id = (isset($_POST['tmpcoder_compare_page_id']) ? intval($_POST['tmpcoder_compare_page_id']) : 0);

        if ( post_password_required($page_id) || 'publish' !== get_post_status($page_id) ) {
            wp_send_json_error(array(
                'message' => esc_html__('Security check failed.', 'sastra-essential-addons-for-elementor'),
            ));
        }

        // $page_id = $request->get_param('id');
        
        // Check if the page was created with Elementor
        if (\Elementor\Plugin::$instance->db->is_built_with_elementor($page_id)) {
            $content = \Elementor\Plugin::$instance->frontend->get_builder_content($page_id);
            wp_send_json_success(array('content' => $content, 'page_url' => get_page_link( $page_id )));
            // return new WP_REST_Response(array('content' => $content), 200);
        } else {
            $page = get_post($page_id);  
            if ($page) {
                $content = apply_filters('the_content', $page->post_content);
                wp_send_json_success(array('content' => $content));
                // return new WP_REST_Response(array('content' => $content), 200);
            } else {
                wp_send_json_error(array('message' => 'Page not found'));
                // return new WP_Error('page_not_found', 'Page not found', array('status' => 404));
            }
        }
        wp_die();
    }
}

new TMPCODER_Compare_Popup_Action();