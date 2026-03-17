<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TMPCODER_Plugin_Notice {
    public function __construct() {

        if ( current_user_can('administrator') ) {

            if ( !get_option('tmpcoder_plugin_update_dismiss_notice_' . get_plugin_data(TMPCODER_PLUGIN_FILE)['Version']) ) {
                add_action( 'admin_init', [$this, 'tmpcoder_render_notice'] );
            }
        }

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [$this, 'tmpcoder_enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_tmpcoder_plugin_update_dismiss_notice', [$this, 'tmpcoder_plugin_update_dismiss_notice'] );
    }

    public function tmpcoder_render_notice() {
        add_action( 'admin_notices', [$this, 'render_plugin_update_notice' ]);
    }
    
    public function tmpcoder_plugin_update_dismiss_notice() {

		if ( !isset($_POST['nonce']) && !wp_verify_nonce( $_POST['nonce'], 'tmpcoder-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit;
		}

        add_option( 'tmpcoder_plugin_update_dismiss_notice_' . get_plugin_data(TMPCODER_PLUGIN_FILE)['Version'], true );
    }

    public function render_plugin_update_notice() {
        global $current_screen;

        if ( is_admin() ) {

            if ( ! isset( $current_screen->id ) || 'spexo-addons_page_tmpcoder-import-demo' === $current_screen->id || 
                'update' === $current_screen->id || $current_screen->id === 'admin_page_tmpcoder-setup-wizard' || (isset($_GET['tab']) && $_GET['tab'] == 'prebuilt-blocks') 
            ) {
                return;
            }

            $notice_banner_api = TMPCODER_Remote_Api::tmpcoder_get_latest_updates_notice_banner_dashboard();

            $tmpcoder_notice_banner = isset($notice_banner_api['data']) ? $notice_banner_api['data']:'';

            if ( !empty($tmpcoder_notice_banner) ){
                echo wp_kses($tmpcoder_notice_banner,tmpcoder_wp_kses_allowed_html());
            }
        }
    }

    public static function tmpcoder_enqueue_scripts() {
        
        // Load Confetti
        wp_enqueue_script( 'tmpcoder-confetti-js', TMPCODER_PLUGIN_URI . 'assets/js/admin/lib/confetti/confetti'.tmpcoder_script_suffix().'.js', [ 'jquery' ], null, true );

        // Localize variables
        wp_add_inline_script(
            'tmpcoder-confetti-js',
            "
            jQuery(document).ready(function($) {

                if ($('#tmpcoder-update-notice-confetti').length) {
                    const tmpcoderConfetti = confetti.create(document.getElementById('tmpcoder-update-notice-confetti'), {
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
        wp_register_style( 'tmpcoder-admin-inline-style', false );
        wp_enqueue_style( 'tmpcoder-admin-inline-style' );

        $inline_css = "
            .toplevel_page_spexo-welcome .tmpcoder-plugin-update-notice, .spexo-addons_page_spexo_addons_global_settings .tmpcoder-plugin-update-notice {
                margin:30px!important;
                margin-bottom:0px!important;
            }  
            .tmpcoder-plugin-update-notice {
                position: relative;
                display: flex;
                align-items: center;
                margin-top: 20px;
                margin-bottom: 20px;
                padding: 30px 30px 30px 40px;
                border: 0 !important;
                box-shadow: 0 0 5px rgba(0,0,0,0.1);
                border-radius: 10px;
                border-left: 4px solid #bf1864 !important;
            }
            .tmpcoder-plugin-update-notice-logo {
                display: none;
                margin-right: 30px;
            }
            .tmpcoder-plugin-update-notice-logo img {
                max-width: 100%;
            }
            .tmpcoder-plugin-update-notice h3 {
                font-size: 36px;
                margin: 0 0 20px 0;
            }
            .tmpcoder-plugin-update-notice h3 span {
                display: inline-block;
                margin-bottom: 15px;
                font-size: 12px;
                color: #fff;
                background-color: #f51f3d;
                padding: 2px 12px 4px;
                border-radius: 3px;
            }
            .tmpcoder-plugin-update-notice p {
                margin: 10px 0 15px;
                font-size: 14px;
            }
            .tmpcoder-plugin-update-notice ul {
                display: flex;
            }
            .tmpcoder-plugin-update-notice ul a {
                display: block;
                text-decoration: none;
            }
            .tmpcoder-plugin-update-notice ul li a:after {
                content: ' ';
                display: inline-block;
                position: relative;
                top: -2px;
                width: 5px;
                height: 5px;
                margin-left: 12px;
                margin-right: 12px;
                background-color: #e0e0e0;
                transform: rotate(45deg);
            }
            .tmpcoder-plugin-update-notice ul li:last-child a:after {
                display: none;
            }
            .tmpcoder-get-started-button.button-primary {
                background-color: #6A4BFF;
            }
            .tmpcoder-get-started-button.button-primary:hover {
                background-color: #583ed7;
            }
            .tmpcoder-get-started-button.button-secondary {
                border: 1px solid #6A4BFF;
                color: #6A4BFF;
            }
            .tmpcoder-get-started-button.button-secondary:hover {
                background-color: #6A4BFF;
                border: 2px solid #6A4BFF;
                color: #fff;
            }
            .tmpcoder-get-started-button {
                padding: 5px 25px !important;
            }
            .tmpcoder-get-started-button .dashicons {
                font-size: 12px;
                line-height: 28px;
            }
            .tmpcoder-plugin-update-notice .image-wrap {
                margin-left: auto;
            }
            .tmpcoder-plugin-update-notice .image-wrap img {
                zoom: 0.45;
            }
            @media screen and (max-width: 1366px) {
                .tmpcoder-plugin-update-notice h3 {
                    font-size: 32px;
                }
                .tmpcoder-plugin-update-notice .image-wrap img {
                    zoom: 0.4;
                }
            }
            @media screen and (max-width: 1280px) {
                .tmpcoder-plugin-update-notice .image-wrap img {
                    zoom: 0.35;
                }
            }
            #tmpcoder-update-notice-confetti {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
            }
        ";

        wp_add_inline_style( 'tmpcoder-admin-inline-style', $inline_css );

    }
}

new TMPCODER_Plugin_Notice();
