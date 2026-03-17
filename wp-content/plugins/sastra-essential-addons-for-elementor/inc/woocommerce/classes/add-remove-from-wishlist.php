<?php
namespace TMPCODER\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TMPCODER_Add_Remove_From_Wishlist setup
 *
 * @since 1.0
 */
class TMPCODER_Add_Remove_From_Wishlist { 

    /**
    ** Constructor
    */
    public function __construct() {
        add_action( 'wp_ajax_add_to_wishlist',[$this, 'add_to_wishlist'] );
        add_action( 'wp_ajax_nopriv_add_to_wishlist',[$this, 'add_to_wishlist'] );
        add_action( 'wp_ajax_remove_from_wishlist', [$this, 'remove_from_wishlist'] );
        add_action( 'wp_ajax_nopriv_remove_from_wishlist', [$this, 'remove_from_wishlist'] );
    }
    
    function add_to_wishlist() {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons')) {
            exit; // Get out of here, the nonce is rotten!
        }

        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        if (is_multisite()) {
            $wishlist_key = 'tmpcoder_wishlist_'.get_current_blog_id();
        } else {
            $wishlist_key = 'tmpcoder_wishlist';
        }
        
        // NEW CODE
        if ($user_id > 0) {
            // $wishlist = get_user_meta( get_current_user_id(), 'tmpcoder_wishlist', true );
            $wishlist = get_user_meta($user_id, $wishlist_key, true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }
    
        if (in_array($product_id, $wishlist)) {
            wp_send_json_error(array('message' => esc_html__('Product is already in wishlist.', 'sastra-essential-addons-for-elementor')));
            return;
        }
    
        $wishlist[] = $product_id;
    
        if ($user_id > 0) {
            update_user_meta($user_id, $wishlist_key, $wishlist);
        } else {
            $this->set_wishlist_to_cookie($wishlist);
        }

        wp_send_json_success();
    }
    
    function remove_from_wishlist() {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons')) {
            exit; // Get out of here, the nonce is rotten!
        }

        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        if (is_multisite()) {
            $wishlist_key = 'tmpcoder_wishlist_'.get_current_blog_id();
        } else {
            $wishlist_key = 'tmpcoder_wishlist';
        }

        if ($user_id > 0) {
            $wishlist = get_user_meta($user_id, $wishlist_key, true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }
    
        $wishlist = array_diff($wishlist, array($product_id));
    
        if ($user_id > 0) {
            update_user_meta($user_id, $wishlist_key, $wishlist);
        } else {
            $this->set_wishlist_to_cookie($wishlist);
        }

        wp_send_json_success();
    }
    
    function get_wishlist_from_cookie() {
        $blog_id = get_current_blog_id();
        $cookie_key = 'tmpcoder_wishlist_'.$blog_id;
        if (isset($_COOKIE['tmpcoder_wishlist'])) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE['tmpcoder_wishlist'])), true);
        } else if ( isset($_COOKIE[$cookie_key]) ) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE[$cookie_key])), true);
        }
        return array();
    }
    
    function set_wishlist_to_cookie($wishlist) {
        if ( is_multisite() ) {
            setcookie('tmpcoder_wishlist_'. get_current_blog_id() .'', wp_json_encode($wishlist), time() + (86400 * 10), '/'); // Expires in 7 days
        } else {
            setcookie('tmpcoder_wishlist', wp_json_encode($wishlist), time() + (86400 * 10), '/'); // Expires in 7 days
        }
    }
}

new TMPCODER_Add_Remove_From_Wishlist();