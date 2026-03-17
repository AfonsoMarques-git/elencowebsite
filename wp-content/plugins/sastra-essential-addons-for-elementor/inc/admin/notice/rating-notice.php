<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TMPCODER_Rating_Notice {
    private $past_date;

    public function __construct() {

        global $pagenow;
        $this->past_date = false == get_option('tmpcoder_rating_maybe_later_time') ? strtotime( '-14 days' ) : strtotime('-7 days');

        if ( current_user_can( 'administrator' ) ) {
            if ( empty(get_option('tmpcoder_rating_dismiss_notice', false)) && empty(get_option('tmpcoder_rating_already_rated', false)) ) {
                add_action( 'admin_init', [$this, 'check_plugin_install_time'] );
            }
        }

        if ( is_admin() ) {
            wp_register_style( 'tmpcoder-rating-notice-style', false );
            wp_enqueue_style( 'tmpcoder-rating-notice-style' );
            wp_add_inline_style( 'tmpcoder-rating-notice-style', $this->tmpcoder_get_rating_notice_css() );
        }

        add_action( 'wp_ajax_tmpcoder_rating_dismiss_notice', [$this, 'tmpcoder_rating_dismiss_notice'] );
        add_action( 'wp_ajax_tmpcoder_rating_maybe_later', [$this, 'tmpcoder_rating_maybe_later'] );
        add_action( 'wp_ajax_tmpcoder_rating_already_rated', [$this, 'tmpcoder_rating_already_rated'] );
        add_action( 'wp_ajax_tmpcoder_rating_need_help', [$this, 'tmpcoder_rating_need_help'] );
    }

    public function check_plugin_install_time() {   
        
        $install_date = get_option('tmpcoder_sastra_elementor_addon_activation_time');

        if ( false == get_option('tmpcoder_rating_maybe_later_time') && false !== $install_date && $this->past_date >= $install_date ) {
            add_action( 'admin_notices', [$this, 'render_rating_notice' ]);
        } else if ( false != get_option('tmpcoder_rating_maybe_later_time') && $this->past_date >= get_option('tmpcoder_rating_maybe_later_time') ) {
            add_action( 'admin_notices', [$this, 'render_rating_notice' ]);
        }
    }

    public function tmpcoder_rating_maybe_later() {
        check_ajax_referer( 'tmpcoder-plugin-notice-js', 'nonce' );

        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Unauthorized action.', 'sastra-essential-addons-for-elementor' ) ) );
        }

        update_option( 'tmpcoder_rating_maybe_later_time', strtotime('now') );
        wp_send_json_success( array( 'message' => __( 'Reminder set for 7 days.', 'sastra-essential-addons-for-elementor' ) ) );
    }

    function tmpcoder_rating_already_rated() {
        check_ajax_referer( 'tmpcoder-plugin-notice-js', 'nonce' );

        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Unauthorized action.', 'sastra-essential-addons-for-elementor' ) ) );
        }

        update_option( 'tmpcoder_rating_already_rated' , true );
        wp_send_json_success( array( 'message' => __( 'Thank you for your rating!', 'sastra-essential-addons-for-elementor' ) ) );
    }
    
    public function tmpcoder_rating_dismiss_notice() {
        check_ajax_referer( 'tmpcoder-plugin-notice-js', 'nonce' );

        if ( !current_user_can( 'manage_options' ) ) { 
            wp_send_json_error( array( 'message' => __( 'Unauthorized action.', 'sastra-essential-addons-for-elementor' ) ) );
        }

        update_option( 'tmpcoder_rating_dismiss_notice', true );
        wp_send_json_success( array( 'message' => __( 'Notice dismissed.', 'sastra-essential-addons-for-elementor' ) ) );
    }

    public function tmpcoder_rating_need_help() {
        check_ajax_referer( 'tmpcoder-plugin-notice-js', 'nonce' );

        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Unauthorized action.', 'sastra-essential-addons-for-elementor' ) ) );
        }

        // Reset Activation Time if user Needs Help
        update_option( 'tmpcoder_sastra_elementor_addon_activation_time', strtotime('now') );
        wp_send_json_success( array( 'message' => __( 'We\'re here to help!', 'sastra-essential-addons-for-elementor' ) ) );
    }

    public function render_rating_notice() {
        global $pagenow;

        if ( is_admin() ) {
            $plugin_info = get_plugin_data( TMPCODER_PLUGIN_FILE , true, true );

            $rating_notice = 
                '<i class=" tmpcoder-rating-notice-dismiss tmpcoder-notice-banner-dismiss tmpcoder-upgrade-pro-notice-dismiss" role="button" aria-label="Dismiss this notice." tabindex="0"></i>
                <div class="tmpcoder-notice-aside tmpcoder-upgrade-pro-notice-aside">
                    <div class="tmpcoder-notice-icon-wrapper">
                        <img src="'.esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/logo-40x40.svg').'" width="24" height="24">
                    </div>
                </div>
                <div class="tmpcoder-notice-content tmpcoder-upgrade-pro-notice-content">
                    <h3>'.sprintf(
                        /* translators: 1: Plugin Name. */
                        esc_html__( '⭐ Enjoy %1$s? Rate Us!', 'sastra-essential-addons-for-elementor' ), esc_html( ucfirst(TMPCODER_PLUGIN_NAME) ) ).'</h3>

                    <p>'.esc_html__( 'Could you please do us a BIG favor and give it a 5-star rating on WordPress? It really helps us grow and keep improving!.', 'sastra-essential-addons-for-elementor' ).'</p>

                    <div class="tmpcoder-notice-actions">
                        '.sprintf( '<a href="%s" class="button button-primary tmpcoder-notice-button tmpcoder-rating-link tmpcoder-upgrade-pro-button" target="_blank">%s</a>', TMPCODER_RATING_LINK, esc_html__( 'OK, you deserve it', 'sastra-essential-addons-for-elementor' ) ).'
                        <a class="tmpcoder-maybe-later"><span class="dashicons dashicons-clock"></span> '.esc_html__('Maybe Later', 'sastra-essential-addons-for-elementor').'</a>
                        <a class="tmpcoder-already-rated"><span class="dashicons dashicons-yes"></span> '.esc_html__('I already did', 'sastra-essential-addons-for-elementor').'</a>
                        <a href="' . esc_url(TMPCODER_NEED_HELP_URL) . '" target="_blank" class="tmpcoder-need-support"><span class="dashicons dashicons-sos"></span> '.esc_html__('Help me first', 'sastra-essential-addons-for-elementor').'</a>
                    </div>
                </div>';

                echo '<div class="notice-1 notice-info tmpcoder-rating-notice tmpcoder-upgrade-pro-notice tmpcoder-notice-banner tmpcoder-notice-banner-dismissible tmpcoder-notice-banner-extended">' . wp_kses_post($rating_notice) .'</div>';
        }
    }

    private function tmpcoder_get_rating_notice_css() {
        return '
            .tmpcoder-maybe-later,
            .tmpcoder-already-rated,
            .tmpcoder-need-support,
            .tmpcoder-notice-dismiss-2 {
                text-decoration: none;
                margin-left: 15px;
                font-size: 14px;
                cursor: pointer;
                color: #5729d9;
                vertical-align:sub;

            }
            .tmpcoder-already-rated .dashicons,
            .tmpcoder-maybe-later .dashicons,
            .tmpcoder-need-support .dashicons,
            .tmpcoder-notice-dismiss-2 .dashicons {
                vertical-align: middle;
                color: #5729d9;
                transition: all 0.3s ease;
            }
            .tmpcoder-already-rated:hover .dashicons,
            .tmpcoder-maybe-later:hover .dashicons,
            .tmpcoder-need-support:hover .dashicons,
            .tmpcoder-notice-dismiss-2:hover .dashicons {
                color: #135e96;
            }
        ';
    }

}

if ( 'Spexo Addons for Elementor' === TMPCODER_PLUGIN_NAME ) {
    new TMPCODER_Rating_Notice();
}