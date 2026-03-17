<?php
namespace TMPCODER\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TMPCODER_Check_Product setup
 *
 * @since 1.0
 */
class TMPCODER_Check_Product { 

    /**
    ** Constructor
    */
    public function __construct() {
        add_action( 'wp_ajax_check_product_in_wishlist', [$this, 'check_product_in_wishlist'] );
        add_action( 'wp_ajax_nopriv_check_product_in_wishlist', [$this, 'check_product_in_wishlist'] );
        add_action( 'wp_ajax_check_product_in_compare', [$this, 'check_product_in_compare'] );
        add_action( 'wp_ajax_nopriv_check_product_in_compare', [$this, 'check_product_in_compare'] );
        add_action( 'wp_ajax_check_product_in_wishlist_grid', [$this, 'check_product_in_wishlist_grid'] );
        add_action( 'wp_ajax_nopriv_check_product_in_wishlist_grid', [$this, 'check_product_in_wishlist_grid'] );
        add_action( 'wp_ajax_check_product_in_compare_grid', [$this, 'check_product_in_compare_grid'] );
        add_action( 'wp_ajax_nopriv_check_product_in_compare_grid', [$this, 'check_product_in_compare_grid'] );
    }

	// Add two new functions for handling cookies
	public function get_wishlist_from_cookie() {
        $blog_id = get_current_blog_id();
        $cookie_key = 'tmpcoder_wishlist_'.$blog_id;
        if (isset($_COOKIE['tmpcoder_wishlist'])) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE['tmpcoder_wishlist'])), true);
        } else if ( isset($_COOKIE[$cookie_key]) ) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE[$cookie_key])), true);
        }
        return array();
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
    
    function check_product_in_wishlist() {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons')) {
            exit; // Get out of here, the nonce is rotten!
        }

		$user_id = get_current_user_id();

        if ( !isset( $_POST['product_id'] ) ) {
            return;
        }

		if ($user_id > 0) {
            if (is_multisite()) {
                $wishlist_key = 'tmpcoder_wishlist_'.get_current_blog_id();
            } else {
                $wishlist_key = 'tmpcoder_wishlist';
            }
			$wishlist = get_user_meta( $user_id, $wishlist_key, true );
		
			if ( ! $wishlist ) {
				$wishlist = array();
			}
	
		} else {
			$wishlist = $this->get_wishlist_from_cookie();
            
		}
        wp_json_encode($wishlist);
        wp_send_json(in_array( intval($_POST['product_id']), $wishlist ));
        wp_die();
    }
    
    function check_product_in_compare() {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons')) {
            exit; // Get out of here, the nonce is rotten!
        }

		$user_id = get_current_user_id();

        if ( !isset( $_POST['product_id'] ) ) {
            return;
        }

		if ($user_id > 0) {
            if (is_multisite()) {
                $compare_key = 'tmpcoder_compare_'.get_current_blog_id();
            } else {
                $compare_key = 'tmpcoder_compare';
            }
			$compare = get_user_meta( $user_id, $compare_key, true );
		
			if ( ! $compare ) {
				$compare = array();
			}
	
		} else {
			$compare = $this->get_compare_from_cookie();
		}
        
        wp_send_json(in_array( intval($_POST['product_id']), $compare ));

        wp_die();
    }
    
    function check_product_in_wishlist_grid() {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons')) {
            exit; // Get out of here, the nonce is rotten!
        }

		$user_id = get_current_user_id();

		if ($user_id > 0) {
            if (is_multisite()) {
                $wishlist_key = 'tmpcoder_wishlist_'.get_current_blog_id();
            } else {
                $wishlist_key = 'tmpcoder_wishlist';
            }
			$wishlist = get_user_meta( $user_id, $wishlist_key, true );
	
		} else {
			$wishlist = $this->get_wishlist_from_cookie();
		}

		if ( ! $wishlist ) {
			$wishlist = array();
		}

        wp_send_json($wishlist);

        wp_die();
    }
    
    function check_product_in_compare_grid() {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons')) {
            exit; // Get out of here, the nonce is rotten!
        }

		$user_id = get_current_user_id();

		if ($user_id > 0) {
            if (is_multisite()) {
                $compare_key = 'tmpcoder_compare_'.get_current_blog_id();
            } else {
                $compare_key = 'tmpcoder_compare';
            }
			$compare = get_user_meta( $user_id, $compare_key, true );
		} else {
			$compare = $this->get_compare_from_cookie();
		}
		
        if ( ! $compare ) {
            $compare = array();
        }
        
        wp_send_json($compare);

        wp_die();
    }
}

new TMPCODER_Check_Product();