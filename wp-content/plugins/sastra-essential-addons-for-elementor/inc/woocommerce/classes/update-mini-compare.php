<?php
namespace TMPCODER\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TMPCODER_Update_Mini_Compare setup
 *
 * @since 1.0
 */
class TMPCODER_Update_Mini_Compare { 

    /**
    ** Constructor
    */
    public function __construct() {
        add_action( 'wp_ajax_update_mini_compare',[$this, 'update_mini_compare'] );
        add_action( 'wp_ajax_nopriv_update_mini_compare',[$this, 'update_mini_compare'] );
    }

	// Add two new functions for handling cookies
	public function get_compare_from_cookie() {
        $blog_id = get_current_blog_id();
        $cookie_key = 'tmpcoder_compare_'.$blog_id;
        if (isset($_COOKIE['tmpcoder_compare'])) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE['tmpcoder_compare'])), true);
        } else if ( isset($_COOKIE[$cookie_key]) ) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE[$cookie_key])), true);
        }
        return array();
	}
    
    function update_mini_compare() {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons') ) {
            return;
        }
        
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        
        if ($user_id > 0) {
            if (is_multisite()) {
                $compare_key = 'tmpcoder_compare_'.get_current_blog_id();
            } else {
                $compare_key = 'tmpcoder_compare';
            }
            $compare = get_user_meta($user_id, $compare_key, true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }

        $product = wc_get_product( $product_id );
        $product_data = [];
        if ( $product ) {
            $product_data['product_url'] = $product->get_permalink();
            $product_data['product_image'] = $product->get_image();
            $product_data['product_title'] = $product->get_title();
            $product_data['product_price'] = $product->get_price_html();
            $product_data['product_id'] = $product->get_id();
            $product_data['compare_count'] = sizeof($compare);
        }

       wp_send_json($product_data);

       wp_die();
    }
}

new TMPCODER_Update_Mini_Compare();