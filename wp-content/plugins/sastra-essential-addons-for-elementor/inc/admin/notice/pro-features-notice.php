<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TMPCODER_Pro_Features_Notice {

    public function __construct() {
        if ( ! tmpcoder_is_availble() && ! defined( 'TMPCODER_ADDONS_PRO_VERSION' ) ) {

            if ( current_user_can( 'administrator' ) ) {
                $version = $this->get_plugin_version();
                if ( $version && ! get_option( 'tmpcoder_pro_features_dismiss_notice_' . $version ) ) {
                    add_action( 'admin_init', [ $this, 'tmpcoder_render_notice' ] );
                    add_action( 'admin_enqueue_scripts', [ $this, 'tmpcoder_enqueue_scripts' ] );
                }
            }

            add_action( 'wp_ajax_tmpcoder_pro_features_dismiss_notice', [ $this, 'tmpcoder_pro_features_dismiss_notice' ] );
        }
    }

    private function get_plugin_version() {
        if ( ! function_exists( 'get_plugin_data' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $data = get_plugin_data( TMPCODER_PLUGIN_FILE );
        return isset( $data['Version'] ) ? sanitize_text_field( $data['Version'] ) : false;
    }

    public function tmpcoder_render_notice() {
        add_action( 'admin_notices', [ $this, 'tmpcoder_render_pro_features_notice' ] );
    }

    public function tmpcoder_pro_features_dismiss_notice() {
        check_ajax_referer( 'tmpcoder-plugin-notice-js', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Unauthorized action.', 'sastra-essential-addons-for-elementor' ) );
        }

        $version = $this->get_plugin_version();
        if ( $version ) {
            add_option( 'tmpcoder_pro_features_dismiss_notice_' . $version, true );
        }

        wp_send_json_success( 'Notice dismissed.' );
    }

    public function tmpcoder_render_pro_features_notice() {
        global $current_screen;

        if ( is_admin() && isset( $current_screen->id ) && $current_screen->id === 'toplevel_page_spexo-welcome' ) {

            if (isset($_GET['tab'])) {
                return;
            }

            $notice_banner_api = TMPCODER_Remote_Api::tmpcoder_get_latest_updates_notice_banner_dashboard(true);

            $tmpcoder_notice_banner = isset($notice_banner_api['data']) ? $notice_banner_api['data']:'';

            if ( !empty($tmpcoder_notice_banner) ){
                echo wp_kses($tmpcoder_notice_banner,tmpcoder_wp_kses_allowed_html());
            }    
        }
    }

    public function tmpcoder_enqueue_scripts( $hook_suffix ) {
        if ( 'toplevel_page_spexo-welcome' !== $hook_suffix || isset($_GET['tab']) ) {
            return;
        }

        wp_enqueue_script( 'tmpcoder-confetti-js', TMPCODER_PLUGIN_URI . 'assets/js/admin/lib/confetti/confetti'.tmpcoder_script_suffix().'.js', [ 'jquery' ], null, true );

        // Localize variables
        wp_add_inline_script(
            'tmpcoder-confetti-js',
            "
            jQuery(document).ready(function($) {
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                $('body').addClass('tmpcoder-pro-features-body');
                $(document).find('.tmpcoder-pro-features-notice-wrap').css('opacity', 1);

                if ($('#tmpcoder-pro-notice-confetti').length) {
                    const tmpcoderConfetti = confetti.create(document.getElementById('tmpcoder-pro-notice-confetti'), {
                        resize: true
                    });

                    setTimeout(function () {
                        tmpcoderConfetti({
                            particleCount: 150,
                            origin: { x: 1, y: 2 },
                            gravity: 0.3,
                            spread: 50,
                            ticks: 150,
                            angle: 120,
                            startVelocity: 60,
                            colors: ['#0e6ef1', '#f5b800', '#ff344c', '#98e027', '#9900f1']
                        });
                    }, 500);

                    setTimeout(function () {
                        tmpcoderConfetti({
                            particleCount: 150,
                            origin: { x: 0, y: 2 },
                            gravity: 0.3,
                            spread: 50,
                            ticks: 200,
                            angle: 60,
                            startVelocity: 60,
                            colors: ['#0e6ef1', '#f5b800', '#ff344c', '#98e027', '#9900f1']
                        });
                    }, 900);
                }
            });
            "
        );

        // Register a dummy stylesheet to attach inline CSS
        wp_register_style( 'tmpcoder-pro-features-notice-style', false );
        wp_enqueue_style( 'tmpcoder-pro-features-notice-style' );
        wp_add_inline_style( 'tmpcoder-pro-features-notice-style', $this->get_inline_css() );
    }

    private function get_inline_css() {
        return '
        .tmpcoder-pro-features-body {
            overflow: hidden;
        }
        .tmpcoder-pro-features-notice-wrap {
            position: absolute;
            display: block;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            z-index: 999;
            background-color: rgba(0, 0, 0, 0.2);
            opacity: 0;
        }
        .tmpcoder-pro-features-notice.notice {
            display: flex !important;
            position: fixed !important;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            width: 410px;
            align-items: center;
            padding: 30px 30px 30px 40px;
            border: none !important;
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
            z-index: 999;
            border-radius: 3px;
        }
        .tmpcoder-pro-features-notice h3 {
            font-size: 32px;
            margin: 0 0 20px;
        }
        .tmpcoder-pro-features-notice h3 span {
            font-size: 12px;
            color: #fff;
            background-color: #f51f3d;
            padding: 2px 12px;
            border-radius: 3px;
            display: inline-block;
            margin-bottom: 15px;
        }
        .tmpcoder-pro-features-notice p {
            font-size: 14px;
            margin: 10px 0 25px;
        }
        .tmpcoder-pro-features-notice ul a {
            display: block;
            text-decoration: none;
        }
        .tmpcoder-pro-banner-btn {
            background-image: linear-gradient(120deg, #5729d9 0%, #bf1864 100%);
            color: #ffffff;
            display: inline-block;
            text-decoration: none;
            text-transform: uppercase;
            margin:0;
        }
        .tmpcoder-pro-banner-btn:focus {
            color:#ffffff;
        }
        .tmpcoder-pro-banner-btn.tmpcoder-dynamic-tutorial {
            background: #e1ad01;
        }
        #tmpcoder-pro-notice-confetti {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        ';
    }
}

new TMPCODER_Pro_Features_Notice();
