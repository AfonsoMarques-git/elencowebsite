<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

require_once TMPCODER_PLUGIN_DIR . 'inc/wizard/wizard-functions.php';
require_once TMPCODER_PLUGIN_DIR . 'inc/wizard/wizard-ajax-api.php';

add_action('admin_enqueue_scripts', 'tmpcoder_enqueue_wizard_script');
function tmpcoder_enqueue_wizard_script(){
    $current_screen = get_current_screen();
    if ( isset($current_screen->base) && $current_screen->base == 'admin_page_tmpcoder-setup-wizard' ){
        
        wp_enqueue_style( 'tmpcoder-wizard-admin-css', TMPCODER_PLUGIN_URI . '/inc/wizard/css/wizard-style'.Theme_Setup_Wizard_Class::script_suffix().'.css', false, tmpcoder_get_plugin_version() );

        wp_enqueue_script( 'tmpcoder-wizard-admin-js', TMPCODER_PLUGIN_URI .'/inc/wizard/js/wizard'.Theme_Setup_Wizard_Class::script_suffix().'.js', ['jquery'], tmpcoder_get_plugin_version(), true );

        $current_theme = (is_object(wp_get_theme()->parent())) ? wp_get_theme()->parent() : wp_get_theme();

        wp_localize_script(
            'tmpcoder-wizard-admin-js',
            'tmpcoderMessages',
            array(
                'theme_active' => wp_get_theme('spexo')->exists() && $current_theme->get_stylesheet() == 'spexo' ? true : false,
                'wizard_step' => get_option(TMPCODER_PLUGIN_KEY.'_wizard_step'),
                'form_nonce'  => wp_nonce_field( 'tmpcoder_install_plugins'),
                'get_plugin_nonce'  => wp_create_nonce( 'tmpcoder_get_plugins'),
                'get_pro_addons_info_nonce'  => wp_create_nonce( 'tmpcoder_get_pro_addons_info'),
                'ok_text'     => esc_html("OK",'sastra-essential-addons-for-elementor'),
                "next_step_btn" => esc_html("Next Step",'sastra-essential-addons-for-elementor'),
                'site_setting_saving' => esc_html("Theme Installing...",'sastra-essential-addons-for-elementor'),
                'required_plugin_installing' => esc_html("Required Plugin Installing",'sastra-essential-addons-for-elementor'),
                'getting_required_plugins' => esc_html("Required Plugin Info Getting",'sastra-essential-addons-for-elementor'),
                'loading_license_form' => esc_html("Spexo Addons Pro Info Getting...",'sastra-essential-addons-for-elementor'),
                'install_required_plugins' => esc_html("Install Required Plugins",'sastra-essential-addons-for-elementor'),
                'install_required_plugins_text' => sprintf("Make sure %s is running the most recent version. %s is designed to work with the required plugins listed below.", esc_html(TMPCODER_PLUGIN_NAME), esc_html(TMPCODER_PLUGIN_NAME)),
                'install_and_activate' => esc_html("Install & Activate",'sastra-essential-addons-for-elementor'),
                'installed_and_activate' => esc_html("Installed & Activate",'sastra-essential-addons-for-elementor'),
                'installed_and_activated'  => esc_html("Activated",'sastra-essential-addons-for-elementor'),
                "congrats_message" => esc_html("The Setup Wizard has completed setting up your website successfully. Now, it's time for you to edit your website and explore its prominent features.",'sastra-essential-addons-for-elementor'),
                "network_error" => esc_html("check network connection, try again.",'sastra-essential-addons-for-elementor'),
                'license_error' => esc_html('License register getting error, try again. click on "License Activation" Tab','sastra-essential-addons-for-elementor'),
            )        
        );
    }
}

class Theme_Setup_Wizard_Class {

    /**
     * @var Theme_Setup_Wizard_Class
     */
    private static $_instance;

    /**
     * @return Theme_Setup_Wizard_Class
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    function __construct(){
        add_action( 'admin_menu', [$this, 'register_newpage'] );
        add_action( 'admin_notices', [$this, 'wizard_admin_notice_success'] );
    }

    public static function script_suffix() {
        return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
    }

    function wizard_admin_notice_success() {
        if ( isset($_GET['saved']) && $_GET['saved'] == "plugin-wizard" ){ // phpcs:ignore WordPress.Security.NonceVerification.Recommended 
            delete_option(TMPCODER_PLUGIN_KEY.'_wizard_step');
            update_option(TMPCODER_PLUGIN_KEY.'_wizard_done', 1);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Congrats, The Setup Wizard has successfully set up your website.', 'sastra-essential-addons-for-elementor' ); ?></p>
            </div>
            <?php
        }
    }

    function register_newpage(){
        add_submenu_page('tmpcoder-setup-wizard', 'Wizard', 'Setup Wizard', 'manage_options', 'tmpcoder-setup-wizard', [$this, 'wps_theme_func']);
    }
    
    function wps_theme_func(){
           
        $current_user = wp_get_current_user();
        $display_name = $current_user->display_name;
        
        // WP Active Color
        global $_wp_admin_css_colors;
        $current_color_scheme = get_user_option( 'admin_color' );
        $colors = $_wp_admin_css_colors[$current_color_scheme];
        $colors = $colors->colors;
        $active_color = $colors[2];

        $theme_slug = 'spexo';
        $current_theme = (is_object(wp_get_theme()->parent())) ? wp_get_theme()->parent() : wp_get_theme();

        $theme_next_label = __('Next', 'sastra-essential-addons-for-elementor');
        $theme_info = tmpcoder_get_theme_info($theme_slug);
        $theme_activated = 0;
        $theme_installed = 0;
        if ( wp_get_theme($theme_slug)->exists() && $current_theme->get_stylesheet() !== $theme_slug ){
            $theme_installed = 1;
            $theme_next_label = __('Activate', 'sastra-essential-addons-for-elementor');
        }else if( wp_get_theme($theme_slug)->exists() && $current_theme->get_stylesheet() == $theme_slug ){
            $theme_activated = 1;
        }else{
            $theme_next_label = __('Install and Activate', 'sastra-essential-addons-for-elementor');
        }
        
        ?>
        <div class="wrap tmpcoder-container">
            <hr class="wp-header-end">            
            <header class="tmpcoder-license-activation-header">
                <div>
                    <div class="tmpcoder-license-activation-logo">
                        <div class="license-activation-header-logo"><img src="<?php echo esc_url( TMPCODER_ADDONS_ASSETS_URL.'images/spexo-logo-web.svg' ); ?>">
                        </div>
                        <span class="wizard-header">
                        <h1><?php echo esc_html( sprintf(
                            /* translators: %s is User Name. */
                         apply_filters( 'theme_admin_welcome_title', __( 'Welcome To Plugin Setup Wizard, %s!', 'sastra-essential-addons-for-elementor' ) ), ucfirst( $display_name ) ) ); ?></h1>
                        <p class="theme-welcome-text"><?php echo esc_html( sprintf( 
                            /* translators: %s is Plugin Name. */
                            apply_filters( 'theme_admin_setup_welcome_text', __( 'We recommend making use of the %s Setup Wizard to create your website. the easiest way to get started.', 'sastra-essential-addons-for-elementor' ) ) , TMPCODER_PLUGIN_NAME) ); ?></p>
                        </span>
                    </div>
                </div>
            </header>

            <div class="theme-wizard-main">
                <ul class="nav-tab-wrapper theme-wizard-nav wp-clearfix <?php echo $theme_activated ? 'theme-active':'' ?> ">
                    <?php 
                    $wizard_steps = array();
                    $wizard_steps[1] = '<li class="nav-tab theme-installation" data-tab="theme-installation">
                        <span class="step-number">1</span>'.esc_html('Install Theme', 'sastra-essential-addons-for-elementor').'
                    </li>';                    

                    // $wizard_steps[2] = '<li class="nav-tab select-editor disabled" data-tab="select-editor">
                    // <span class="step-number">2</span>'.esc_html('Select Page Builder', 'sastra-essential-addons-for-elementor').'
                    // </li>';       

                    $wizard_steps[3] = '<li class="nav-tab install-plugins disabled" data-tab="install-plugins">
                        <span class="step-number">2</span>'.esc_html('Install Required Plugins', 'sastra-essential-addons-for-elementor').'
                    </li>';

                    $wizard_steps[4] = '<li class="nav-tab license-registration disabled" data-tab="license-registration">
                        <span class="step-number">3</span>'.esc_html('Get Spexo Addons Pro', 'sastra-essential-addons-for-elementor').
                    '</li>';

                    foreach ($wizard_steps as $wizard_key => $wizard_value) {
                        echo wp_kses_post($wizard_value);
                    }

                    ?>
                                    
                </ul>
                <div id="theme-installation" class="tab-content tab-content-theme-installation">
                
                    <div class="tmpcoder-message-box theme-install-part">
                        <h2 class="wizard-heading"><?php esc_html_e('Install Theme', 'sastra-essential-addons-for-elementor'); ?></h2>
                        <p class="wizard-title-text"><?php esc_html_e('Our theme is required for prebuilt webistes.', 'sastra-essential-addons-for-elementor'); ?></p>
                        <form class="theme-installation-frm" method="POST">
                            <?php if ( is_object($theme_info) ){ ?>
                                <div class="feature-section recommended-plugins three-col ">

                                    <div class="col plugin_box">
                                        <div class="theme-grid-box">
                                        <img src="<?php echo esc_url($theme_info->screenshot_url); ?>" alt="<?php echo esc_attr($theme_info->name); ?>" class="plugin-preview" loading="lazy" />

                                        <div class="action_bar">
                                            <span class="theme-name"><?php echo esc_html($theme_info->name); ?></span>
                                            <?php 
                                            if ( $theme_activated ){ ?>
                                                <label class="theme-status"><?php esc_html_e('Activated', 'sastra-essential-addons-for-elementor'); ?></label>
                                                <?php 
                                            }else if ( $theme_installed ){
                                                ?>
                                                <label class="theme-status"><?php esc_html_e('Installed and Activate', 'sastra-essential-addons-for-elementor'); ?></label>
                                                <?php
                                            }else{
                                                ?>
                                                <label class="theme-status"><?php esc_html_e('Install and Activate', 'sastra-essential-addons-for-elementor'); ?></label>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        </div>
                                    </div>                                    
                                </div>
                            <?php } ?>
                            <div class="next-step-action">
                                <?php 
                                $dashboard_url = admin_url();
                                if (is_plugin_active( 'elementor/elementor.php' )) {
                                    $dashboard_url .= 'admin.php?page=spexo-welcome';
                                }
                                ?>
                                <a class="tmpcoder-skip-wizard-link" data-url="<?php echo esc_url($dashboard_url) ?>" href="javascript:void(0)"><?php esc_html_e('Skip Setup & Go to Dashboard','sastra-essential-addons-for-elementor'); ?>   
                                </a>
                                <input type="hidden" name="action" value="tmpcoder_theme_install_func" />
                                <input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'tmpcoder_install_theme' ) ); ?>" />
                                <input type="submit" class="button button-primary next-step-btn" value="<?php echo esc_attr($theme_next_label); ?>" />
                            </div>
                        </form>
                    </div>
                </div>
                <div id="install-plugins" class="tab-content tab-content-install-plugins">
                    <div class="tmpcoder-message-box install-plugin-part">
                    </div>
                </div>
                <div id="license-registration" class="tab-content tab-content-license-registration">
                    <div class="tmpcoder-message-box">

                    </div>
                </div>
                <div class="process-loader hide">
                    <span class="loader-image"></span>
                    <span class="loader-text"></span>
                </div>
            </div>
        </div>

        <div class="tmpcoder-skip-wizard-popup-wrap tmpcoder-admin-popup-wrap">
            <div class="tmpcoder-skip-wizard-popup tmpcoder-admin-popup">
                <div id="tmpcoder-skip-wizard-confirm-popup" class="mfp-hide">
                    <h2 class="popup-heading"> <?php esc_html_e('Skip the Setup Wizard?','sastra-essential-addons-for-elementor') ?> </h2>
                    <div class="popup-content">
                        <p class="popup-message"><?php echo wp_kses_post(__('Heads up! <strong>This action is non-reversible</strong> and you won’t be able to access the setup wizard again. Are you sure you want to skip setup wizard?', 'sastra-essential-addons-for-elementor')); ?></p>
                        <a class="button button-primary popup-close"><?php esc_html_e('Continue Setup', 'sastra-essential-addons-for-elementor') ?></a>
                        <a class="button button-secondary tmpcoder-skip-wizard-confirm-button"><?php esc_html_e('Yes, Skip', 'sastra-essential-addons-for-elementor') ?></a>
                    </div>
                </div>
            </div>
        </div>

        <?php 
    }    
}

new Theme_Setup_Wizard_Class();
