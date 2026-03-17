<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( ! function_exists( 'tmpcoder_generate_admin_url' ) ) :
	function tmpcoder_generate_admin_url( $id = '' ) {
		$url = 'admin.php?page=%1$s-welcome&tab=%2$s';
		return admin_url( sprintf( $url, TMPCODER_THEME , $id ) );
	}
endif;

add_action('admin_enqueue_scripts','tmpcoder_admin_enqueue_scripts_func');

if ( ! function_exists( 'tmpcoder_admin_enqueue_scripts_func' ) ) :

	function tmpcoder_admin_enqueue_scripts_func() {
        // Stylesheet files
		wp_enqueue_style('admin-style', plugins_url( 'assets/css/admin/style'.tmpcoder_script_suffix().'.css', TMPCODER_PLUGIN_FILE ), [] , TMPCODER_PLUGIN_VER, false);
	}
	
endif;

if ( ! function_exists('tmpcoder_wp_body_open_action') ){
    add_action('tmpcoder_wp_body_open', 'tmpcoder_wp_body_open_action');
    function tmpcoder_wp_body_open_action(){
        wp_body_open();
    }
}

require_once (TMPCODER_PLUGIN_DIR . 'inc/admin/lib/welcome-screen/class-epsilon-welcome-screen.php');
require_once (TMPCODER_PLUGIN_DIR . 'inc/admin/lib/class-theme.php');

require_once (TMPCODER_PLUGIN_DIR . 'inc/woocommerce/classes/add-remove-from-wishlist.php');
require_once (TMPCODER_PLUGIN_DIR . 'inc/woocommerce/classes/check-product-in-wc.php');
require_once (TMPCODER_PLUGIN_DIR . 'inc/woocommerce/classes/update-mini-wishlist.php');
require_once (TMPCODER_PLUGIN_DIR . 'inc/woocommerce/classes/count-wishlist-compare-items.php');
require_once (TMPCODER_PLUGIN_DIR . 'inc/woocommerce/classes/add-remove-from-compare.php');
require_once (TMPCODER_PLUGIN_DIR . 'inc/woocommerce/classes/add-remove-from-compare.php');
require_once (TMPCODER_PLUGIN_DIR . 'inc/woocommerce/classes/compare-popup-action.php');
require_once (TMPCODER_PLUGIN_DIR . 'inc/woocommerce/classes/update-mini-compare.php');

if (defined('TMPCODER_CURRENT_THEME_NAME') && in_array(TMPCODER_CURRENT_THEME_NAME, array('SastraWP','Spexo', 'Belliza') ) ) {
    require_once (TMPCODER_PLUGIN_DIR . 'inc/inline-css.php');
}

if (class_exists('Elementor\Plugin')) {
    require_once (TMPCODER_PLUGIN_DIR . 'inc/admin/mega-menu.php');
    require_once (TMPCODER_PLUGIN_DIR . 'inc/admin/theme-builder.php');
    
    if (defined('TMPCODER_CURRENT_THEME_NAME') && in_array(TMPCODER_CURRENT_THEME_NAME, array('SastraWP','Spexo', 'Belliza') ) ) {    
        require_once (TMPCODER_PLUGIN_DIR . 'inc/admin/import/tmpcoder-plugin-demo-list.php');
    }

    // Rating Notice
    if ( ! function_exists('tmpcoder_add_rating_notice_action') ) {
        add_action('init', 'tmpcoder_add_rating_notice_action');
        function tmpcoder_add_rating_notice_action() {
            require TMPCODER_PLUGIN_DIR.'inc/admin/notice/rating-notice.php';
        }
    }
}

function tmpcoder_site_title_shortcode() {
    return get_bloginfo('name');
}
add_shortcode('SITE_TITLE', 'tmpcoder_site_title_shortcode');

function tmpcoder_current_year_shortcode() {
    return date_i18n('Y');
}
add_shortcode('CURRENT_YEAR', 'tmpcoder_current_year_shortcode');

function tmpcoder_copyright_sign_shortcode() {
    return "&copy;";
}
add_shortcode('COPYRIGHT', 'tmpcoder_copyright_sign_shortcode');

if (is_admin()) {
    require_once (TMPCODER_PLUGIN_DIR.'inc/admin/feedback.php');
}

if (class_exists('\Elementor\Plugin')) {
    add_action('init','tmpcoder_add_feature_notice_action');
}

function tmpcoder_add_feature_notice_action(){
    require TMPCODER_PLUGIN_DIR.'inc/admin/notice/feature-banner-notice.php';
    require TMPCODER_PLUGIN_DIR.'inc/admin/notice/pro-features-notice.php';
}

require TMPCODER_PLUGIN_DIR.'inc/traits/woo-product-comparable.php';
require TMPCODER_PLUGIN_DIR.'inc/traits/product-grid.php';
require TMPCODER_PLUGIN_DIR.'inc/traits/template-query.php';
require TMPCODER_PLUGIN_DIR.'inc/traits/helper.php';
require TMPCODER_PLUGIN_DIR.'inc/traits/ajax-handler.php';
require TMPCODER_PLUGIN_DIR.'inc/classes/helper.php';