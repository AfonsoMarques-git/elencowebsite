<?php
namespace TMPCODER\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TMPCODER_Add_Remove_From_Compare setup
 *
 * @since 1.0
 */
class TMPCODER_Add_Remove_From_Compare { 

    /**
    ** Constructor
    */
    public function __construct() {
        add_action( 'wp_ajax_add_to_compare',[$this, 'add_to_compare'] );
        add_action( 'wp_ajax_nopriv_add_to_compare',[$this, 'add_to_compare'] );
        add_action( 'wp_ajax_remove_from_compare', [$this, 'remove_from_compare'] );
        add_action( 'wp_ajax_nopriv_remove_from_compare', [$this, 'remove_from_compare'] );
    }
    
    function add_to_compare() {

        if ( !isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons') ) {
            exit; // Get out of here, the nonce is rotten!
        }

        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        if (is_multisite()) {
            $compare_key = 'tmpcoder_compare_'.get_current_blog_id();
        } else {
            $compare_key = 'tmpcoder_compare';
        }

        // NEW CODE
        if ($user_id > 0) {
            $compare = get_user_meta($user_id, $compare_key, true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }
    
        if (in_array($product_id, $compare)) {
            wp_send_json_error(array('message' => esc_html__('Product is already in compare.', 'sastra-essential-addons-for-elementor')));
            return;
        }
    
        $compare[] = $product_id;
    
        if ($user_id > 0) {
            update_user_meta($user_id, $compare_key, $compare);
        } else {
            $this->set_compare_to_cookie($compare);
        }

        wp_send_json_success();
    }
    
    function remove_from_compare() {

        if ( !isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons') ) {
            exit; // Get out of here, the nonce is rotten!
        }

        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();
        
        if (is_multisite()) {
            $compare_key = 'tmpcoder_compare_'.get_current_blog_id();
        } else {
            $compare_key = 'tmpcoder_compare';
        }

        if ($user_id > 0) {
            $compare = get_user_meta($user_id, $compare_key, true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }
    
        $compare = array_diff($compare, array($product_id));
    
        if ($user_id > 0) {
            update_user_meta($user_id, $compare_key, $compare);
        } else {
            $this->set_compare_to_cookie($compare);
        }

        wp_send_json_success();
    }
    
    function get_compare_from_cookie() {
        $blog_id = get_current_blog_id();
        $cookie_key = 'tmpcoder_compare_'.$blog_id;
        if (isset($_COOKIE['tmpcoder_compare'])) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE['tmpcoder_compare'])), true);
        } else if ( isset($_COOKIE[$cookie_key]) ) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE[$cookie_key])), true);
        }
        return array();
    }
    
    function set_compare_to_cookie($compare) {
        if ( is_multisite() ) {
            setcookie( sanitize_key('tmpcoder_compare_'. get_current_blog_id() .''), wp_json_encode($compare), time() + (86400 * 10), '/'); // Expires in 7 days
        } else {
            setcookie( sanitize_key('tmpcoder_compare'), wp_json_encode($compare), time() + (86400 * 10), '/'); // Expires in 7 days
        }
    }
}

new TMPCODER_Add_Remove_From_Compare();