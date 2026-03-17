<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Remote_Api {

    function __construct() {

    }

    static function get_prebuilt_demos(){

        $req_params = array(
            'action'    => 'import_demo',
            'theme'     => TMPCODER_CURRENT_THEME_NAME,
            'version'   => TMPCODER_CURRENT_THEME_VERSION,
            'plugin'   => 'sastra-essential-addons-for-elementor',
            'plugin_version'   => (defined('TMPCODER_PLUGIN_VER') ? TMPCODER_PLUGIN_VER : ''),
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
            return $theme_response;
            
        }else{
            if (is_object($theme_request)) {
                return array('status' => 'error', 'message'=> $theme_request->get_error_message());
            }
        }
    }

    static function get_latest_updates_notice_banner(){

        $theme = (is_object(wp_get_theme()->parent())) ? wp_get_theme()->parent() : wp_get_theme();

        $req_params = array(
            'action'    => 'latest_updates_notice_banner',
            'theme'     => TMPCODER_CURRENT_THEME_NAME,
            'version'   => TMPCODER_CURRENT_THEME_VERSION,
            'plugin'   => 'sastra-essential-addons-for-elementor',
            'plugin_version'   => (defined('TMPCODER_PLUGIN_VER') ? TMPCODER_PLUGIN_VER : ''),
            'sastrawp_installed' => ( wp_get_theme('sastrawp')->exists() ? '1' : '0'),
            'sastrawp_activated' => ( $theme->get_stylesheet() == 'sastrawp' && wp_get_theme('sastrawp')->exists() ? '1' : '0' ),
            'spexo_installed' => ( wp_get_theme('spexo')->exists() ? '1' : '0'),
            'spexo_activated' => ( $theme->get_stylesheet() == 'spexo' && wp_get_theme('spexo')->exists() ? '1' : '0' ),
            'sastra_addons_pro_installed' => (function_exists('tmpcoder_is_sastra_addons_pro_installed') && tmpcoder_is_sastra_addons_pro_installed() ? '1' : '0' ),
        );

        $req_params = apply_filters('tmpcoder_request_param_pro_updates_notice_banner', $req_params);
        
        $options = array(
            'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),
            'user-agent' => 'tmpcoder-plugin-user-agent',
            'headers' => array( 'Referer' => site_url() ),
        );
        
        $theme_request = wp_remote_get(add_query_arg($req_params,TMPCODER_UPDATES_URL), $options);

        if ( ! is_wp_error( $theme_request ) && wp_remote_retrieve_response_code($theme_request) == 200){

            $theme_response = wp_remote_retrieve_body($theme_request);
            $theme_response = (array) json_decode($theme_response);
            return $theme_response;
            
        }else{
            if (is_object($theme_request)) {
                return array('status' => 'error', 'message'=> $theme_request->get_error_message());
            }
        }
    }

    static function tmpcoder_get_latest_updates_notice_banner_dashboard($is_pro_feature_notice=false){

        $theme = (is_object(wp_get_theme()->parent())) ? wp_get_theme()->parent() : wp_get_theme();

        $req_params = array(
            'action'    => 'tmpcoder_get_latest_updates_notice_banner_dashboard',
            'theme'     => TMPCODER_CURRENT_THEME_NAME,
            'version'   => TMPCODER_CURRENT_THEME_VERSION,
            'plugin'   => 'sastra-essential-addons-for-elementor',
            'plugin_version'   => (defined('TMPCODER_PLUGIN_VER') ? TMPCODER_PLUGIN_VER : ''),
            'sastrawp_installed' => ( wp_get_theme('sastrawp')->exists() ? '1' : '0'),
            'sastrawp_activated' => ( $theme->get_stylesheet() == 'sastrawp' && wp_get_theme('sastrawp')->exists() ? '1' : '0' ),
            'spexo_installed' => ( wp_get_theme('spexo')->exists() ? '1' : '0'),
            'spexo_activated' => ( $theme->get_stylesheet() == 'spexo' && wp_get_theme('spexo')->exists() ? '1' : '0' ),
            'sastra_addons_pro_installed' => (function_exists('tmpcoder_is_sastra_addons_pro_installed') && tmpcoder_is_sastra_addons_pro_installed() ? '1' : '0' ),
        );

        $req_params = apply_filters('tmpcoder_request_param_pro_updates_notice_banner', $req_params);
        
        if ($is_pro_feature_notice == true) {
            $req_params['is_pro_feature_notice'] = true;
        }
        
        $options = array(
            'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),
            'user-agent' => 'tmpcoder-plugin-user-agent',
            'headers' => array( 'Referer' => site_url() ),
        );
        
        $api_url = TMPCODER_UPDATES_URL;

        $theme_request = wp_remote_get(add_query_arg($req_params, $api_url), $options);

        if ( ! is_wp_error( $theme_request ) && wp_remote_retrieve_response_code($theme_request) == 200){

            $theme_response = wp_remote_retrieve_body($theme_request);
            $theme_response = (array) json_decode($theme_response);
            return $theme_response;
            
        }else{
            if (is_object($theme_request)) {
                return array('status' => 'error', 'message'=> $theme_request->get_error_message());
            }
        }
    }
}