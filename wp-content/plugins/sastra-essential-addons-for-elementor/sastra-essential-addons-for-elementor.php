<?php
/**
 * Plugin Name: Spexo Addons for Elementor
 * Plugin URI: http://spexoaddons.com/
 * Description: Spexo Addons for Elementor is all in one solution for complete starter sites, single page templates, blocks & images. This plugin offers additional features needed by our theme, including AI-powered content generation and image creation.
 * Version: 1.0.30
 * Author: TemplatesCoder
 * Author URI:  https://templatescoder.com/
 * Elementor tested up to: 3.32.4
 * Text Domain: sastra-essential-addons-for-elementor
 * License: GPLv3
 *
 * @package Spexo Addons for Elementor
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Set constants.
 */

$theme = (is_object(wp_get_theme()->parent())) ? wp_get_theme()->parent() : wp_get_theme();

if ( ! defined( 'TMPCODER_PLUGIN_VER' ) ) {
    define( 'TMPCODER_PLUGIN_VER', '1.0.30' );
}

if ( ! defined( 'TMPCODER_PLUGIN_NAME' ) ) {
	define( 'TMPCODER_PLUGIN_NAME', 'Spexo Addons for Elementor' );
}

if ( ! defined( 'TMPCODER_PURCHASE_PRO_URL' ) ) {
	define( 'TMPCODER_PURCHASE_PRO_URL', esc_url( 'https://spexoaddons.com/spexo-addons-pro/' ) );
}

if ( ! defined( 'TMPCODER_REQUEST_NEW_FEATURE_URL' ) ) {
	define( 'TMPCODER_REQUEST_NEW_FEATURE_URL', esc_url( 'https://support.templatescoder.com/?ref=new-feature-request' ) );
}

if ( ! defined( 'TMPCODER_NEED_HELP_URL' ) ) {
	define( 'TMPCODER_NEED_HELP_URL', esc_url( 'https://support.templatescoder.com/' ) );
}

if ( !defined('TMPCODER_DEMO_IMPORT_API') ){
	define('TMPCODER_DEMO_IMPORT_API', esc_url('https://themes.templatescoder.com/sastra-addon/') );
}

if ( !defined( 'TMPCODER_SUPPORT_URL' ) ) {
	define( 'TMPCODER_SUPPORT_URL', esc_url('https://support.templatescoder.com/') );
}

if ( !defined( 'TMPCODER_DOCUMENTATION_URL' ) ) {
	define( 'TMPCODER_DOCUMENTATION_URL', esc_url('https://spexoaddons.com/documentation/') );
}
if ( !defined( 'TMPCODER_PLUGIN_SITE_URL' ) ) {
	define( 'TMPCODER_PLUGIN_SITE_URL', esc_url('https://spexoaddons.com/') );
}

if ( !defined('TMPCODER_RATING_LINK') ){
	define('TMPCODER_RATING_LINK', esc_url('https://wordpress.org/support/plugin/sastra-essential-addons-for-elementor/reviews/') );
}

if ( !defined( 'TMPCODER_PLUGIN_FILE' ) ) {
	define( 'TMPCODER_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'TMPCODER_PLUGIN_BASE' ) ) {
	define( 'TMPCODER_PLUGIN_BASE', plugin_basename( TMPCODER_PLUGIN_FILE ) );
}

if ( ! defined( 'TMPCODER_PLUGIN_DIR' ) ) {
	define( 'TMPCODER_PLUGIN_DIR', plugin_dir_path( TMPCODER_PLUGIN_FILE ) );
}

if ( ! defined( 'TMPCODER_PLUGIN_URI' ) ) {
	define( 'TMPCODER_PLUGIN_URI', plugins_url( '/', TMPCODER_PLUGIN_FILE ) );
}

if ( ! defined( 'TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE' ) ) {
	define( 'TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE', esc_html('theme-advanced-hook') );
}

if ( ! defined( 'TMPCODER_THEME_OPTIONS_DATA_CLASS' ) ) {
	define( 'TMPCODER_THEME_OPTIONS_DATA_CLASS', esc_html('Tmpcoder_Site_Settings') );
}

if ( ! defined( 'TMPCODER_HOOK_PRIFIX' ) ) {
	define( 'TMPCODER_HOOK_PRIFIX', esc_html('tmpcoder_') ); 
}

if ( ! defined( 'TMPCODER_THEME' ) ) {
	define( 'TMPCODER_THEME', esc_html('spexo') ); 
}

if ( ! defined( 'TMPCODER_UPDATES_URL' ) ) {
	define( 'TMPCODER_UPDATES_URL', esc_url('https://updates.templatescoder.com/rest-api?') );
}

if ( ! defined( 'TMPCODER_FIX_IMPORT_ISSUE_DOC_LINK' ) ) {
	define( 'TMPCODER_FIX_IMPORT_ISSUE_DOC_LINK', 'https://spexoaddons.com/documentation/how-to-fix-error-when-import-prebuilt-websites' );
}

if ( ! defined( 'TMPCODER_CURL_TIMEOUT_DOC_LINK' ) ) {
	define( 'TMPCODER_CURL_TIMEOUT_DOC_LINK', 'https://spexoaddons.com/documentation/how-to-fix-error-when-import-prebuilt-websites#' );
}

if ( ! defined( 'TMPCODER_ENABLE_TO_DOWNLOAD_XML' ) ) {
	define( 'TMPCODER_ENABLE_TO_DOWNLOAD_XML', 'https://spexoaddons.com/documentation/why-cant-store-the-xml-file-in-wp-content-folder' );
}

if ( ! defined( 'TMPCODER_RESUME_IMPORT_PROCESS_DOC_LINK' ) ) {
	define( 'TMPCODER_RESUME_IMPORT_PROCESS_DOC_LINK', 'https://spexoaddons.com/documentation/how-to-fix-the-operation-timed-out-error-while-importing-a-prebuilt-website' );
}

if ( ! defined( 'TMPCODER_ADDONS_ASSETS_URL' ) ) {
	define( 'TMPCODER_ADDONS_ASSETS_URL', esc_url(TMPCODER_PLUGIN_URI.'assets/') ); 
}

if ( ! defined( 'TMPCODER_THEME_OPTION_NAME' ) ) {
	define( 'TMPCODER_THEME_OPTION_NAME', esc_html(TMPCODER_HOOK_PRIFIX.'global_theme_options_'.$theme->get( 'TextDomain' )) );
}

if ( ! defined( 'TMPCODER_CURRENT_THEME_NAME' ) ) {
	define( 'TMPCODER_CURRENT_THEME_NAME', $theme->get( 'Name' ) );
}

if ( ! defined( 'TMPCODER_CURRENT_THEME_VERSION' ) ) {
	define( 'TMPCODER_CURRENT_THEME_VERSION', $theme->get( 'Version' ) );
}

if ( ! defined( 'TMPCODER_PLUGIN_KEY' ) ) {
    define( 'TMPCODER_PLUGIN_KEY', 'sastra_addons');
}

if ( ! defined( 'TMPCODER_PRO_PLUGIN_KEY' ) ) {
    define( 'TMPCODER_PRO_PLUGIN_KEY', 'sastra-addons-pro'); // pro plugin slug
}


/*
Admin Hooks
- import-demo tabs
- style and javascript
*/

require_once TMPCODER_PLUGIN_DIR . 'inc/admin-hooks.php';

/**
 * Spexo Addons for Elementor Setup
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'tmpcoder_setup' ) ) :
	function tmpcoder_setup() {
		require TMPCODER_PLUGIN_DIR . 'inc/wizard/index.php';
		require_once TMPCODER_PLUGIN_DIR . 'inc/classes/functions.php';
		require_once TMPCODER_PLUGIN_DIR . 'inc/admin/import/classes/class-tmpcoder-plugin.php';

		if (class_exists('\Elementor\Plugin')) {
			include_once TMPCODER_PLUGIN_DIR . 'inc/classes/widgets-cache.php';
			include_once TMPCODER_PLUGIN_DIR . 'inc/classes/assets-cache.php';
			include_once TMPCODER_PLUGIN_DIR . 'inc/classes/cache-manager.php';
			include_once TMPCODER_PLUGIN_DIR . 'inc/classes/admin-bar.php';
		}

		$tmpcoder_current_version = get_option('tmpcoder_spexo_addons_version');

		if ( $tmpcoder_current_version != TMPCODER_PLUGIN_VER ){

			/* Update all post templaes type as Elementor Full With */	
			
			tmpcoder_update_post_templates_type();

		    update_option('tmpcoder_spexo_addons_version', TMPCODER_PLUGIN_VER);
            
            $theme_setting = get_option(TMPCODER_THEME_OPTION_NAME);
            if( empty($theme_setting) ) {
                $theme_setting = get_option('tmpcoder_global_theme_options_sastrawp');
                if ( !empty($theme_setting) ){
                    update_option(TMPCODER_THEME_OPTION_NAME, $theme_setting);
                }
            }

            // Default widgets settings
            
			add_action('init', function() use ($tmpcoder_current_version) {
				if (version_compare($tmpcoder_current_version, '1.0.21', '<')) {
					tmpcoder_widgets_migrate_settings();
				}
			});
            
            tmpcoder_regenerate_elementor_css_on_update();
		}
	}
	add_action( 'plugins_loaded', 'tmpcoder_setup' );
endif;

/* Product_Registration, Theme_Updater */

require_once (TMPCODER_PLUGIN_DIR . 'inc/library/init.php');

add_action('init',function(){

	if (class_exists('Elementor\Plugin')) {
		
		require_once (TMPCODER_PLUGIN_DIR . 'inc/elementor-widgets.php');
		require_once (TMPCODER_PLUGIN_DIR . 'inc/elementor-hooks.php');
		require_once (TMPCODER_PLUGIN_DIR . 'inc/modules/ajax-search.php');

	    /* Elementor Custom Controls */
	    require_once (TMPCODER_PLUGIN_DIR . 'inc/elementor-controls.php');
	    require_once (TMPCODER_PLUGIN_DIR . 'inc/header-footer-helper/tmpcoder-plugin-advanced-hooks-loader.php');
	}

	// Load AI Features
	if (class_exists('Elementor\Plugin')) {
		require_once (TMPCODER_PLUGIN_DIR . 'inc/modules/ai/class-spexo-ai-manager.php');
		Spexo_Addons\AI\Spexo_AI_Manager::get_instance();
	}
});

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */

function tmpcoder_addons_load_plugin() {

	add_action( 'admin_notices', function(){

		$tmpcoder_notice_excluded_pages = array( 'tmpcoder-setup-wizard', 'tmpcoder-theme-wizard', 'tmpcoder-plugin-wizard' );

		if ( isset($_GET['page']) && in_array($_GET['page'], $tmpcoder_notice_excluded_pages, true) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$notice_banner_api = TMPCODER_Remote_Api::get_latest_updates_notice_banner();
		$tmpcoder_notice_banner = isset($notice_banner_api['data']) ? $notice_banner_api['data']:'';
		if ( !empty($tmpcoder_notice_banner) ){
		echo '<div class="notice1 tmpcoder-notice-banner tmpcoder-latest-updates-notice-banner">'. wp_kses_post($tmpcoder_notice_banner) .'</div>';
		}
	}, 99 );

	
	if( ! tmpcoder_is_availble() ) {
		$tmpcoder_upgrade_pro_notice_banner = get_transient('tmpcoder_upgrade_pro_notice');
	    if ( empty($tmpcoder_notice_banner) ){        	
			set_transient( "tmpcoder_upgrade_pro_notice", 'yes', 24 * HOUR_IN_SECONDS );
		}
	}

    add_action( 'admin_notices', function(){

		$tmpcoder_notice_excluded_pages = array( 'tmpcoder-setup-wizard', 'tmpcoder-theme-wizard', 'tmpcoder-plugin-wizard' );

		if ( isset($_GET['page']) && in_array($_GET['page'], $tmpcoder_notice_excluded_pages, true) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		
		/* upgrade to PRO version notice [START] */
	    if( ! tmpcoder_is_availble() ) {

        	if ( function_exists('tmpcoder_is_sastra_addons_pro_installed') && tmpcoder_is_sastra_addons_pro_installed() ){

        		if (get_transient('tmpcoder_activate_pro_notice_dismissed_' . get_current_user_id())) {
			        return;
			    }

		    	if ( ! current_user_can( 'activate_plugins' ) ) {
					return;
				}

				$plugin = defined('TMPCODER_PRO_PLUGIN_KEY') ? TMPCODER_PRO_PLUGIN_KEY.'/'.TMPCODER_PRO_PLUGIN_KEY.'.php' : '';

				if ( is_plugin_active($plugin) || is_plugin_active('sastra-addons-pro/sastra-addons-pro.php') ) {
					return;
				}

		    	$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin='. $plugin .'&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_'. $plugin );
		        	$upgrade_pro_notice = 
		        	'<i class="tmpcoder-upgrade-pro-notice-dismiss activate-pro-notice" role="button" aria-label="Dismiss this notice." tabindex="0"></i>
		    		<div class="tmpcoder-upgrade-pro-notice-aside">
						<div class="tmpcoder-upgrade-pro-notice-icon-wrapper">
							<img src="'.esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/logo-40x40.svg').'" width="24" height="24">
						</div>
					</div>
					<div class="tmpcoder-upgrade-pro-notice-content">
						<h3>'.sprintf(
							/* translators: 1: Upgrade to pro notice Title. */
							esc_html__( 'Activate Spexo Addons Pro!', 'sastra-essential-addons-for-elementor' ), esc_html( ucfirst(TMPCODER_PLUGIN_NAME) ) ).'</h3>

						<p>'.esc_html__( 'Activate to the Pro version and enjoy exclusive features. Take your experience to the next level and enhance your workflow with premium tools.', 'sastra-essential-addons-for-elementor' ).'</p>

						<div class="tmpcoder-upgrade-pro-notice-actions">
							'.sprintf( '<a href="%s" class="button button-primary tmpcoder-upgrade-pro-button">%s</a>', $activation_url, esc_html__( 'Activate Now', 'sastra-essential-addons-for-elementor' ) ).'
						</div>
					</div>';

		        echo '<div class="notice1 notice-info tmpcoder-upgrade-pro-notice tmpcoder-upgrade-pro-notice-dismissible tmpcoder-upgrade-pro-notice-extended tmpcoder-notice-banner">' . wp_kses_post($upgrade_pro_notice) .'</div>';
			}

			if (get_transient('tmpcoder_upgrade_pro_notice_dismissed_' . get_current_user_id())) {
		        return;
		    }

            $tmpcoder_upgrade_pro_notice_banner = get_transient('tmpcoder_upgrade_pro_notice');
            if ( !empty($tmpcoder_upgrade_pro_notice_banner) && !tmpcoder_is_sastra_addons_pro_installed() ){

            	$upgrade_url = TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-upgrade-notice';
            	$upgrade_pro_notice = 
            	'<i class="tmpcoder-upgrade-pro-notice-dismiss" role="button" aria-label="Dismiss this notice." tabindex="0"></i>
        		<div class="tmpcoder-upgrade-pro-notice-aside">
					<div class="tmpcoder-upgrade-pro-notice-icon-wrapper">
						<img src="'.esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/logo-40x40.svg').'" width="24" height="24">
					</div>
				</div>
				<div class="tmpcoder-upgrade-pro-notice-content">
					<h3>'.sprintf(
						/* translators: 1: Upgrade to pro notice Title. */
						esc_html__( 'Unlock the Full Potential of %1$s!', 'sastra-essential-addons-for-elementor' ), esc_html( ucfirst(TMPCODER_PLUGIN_NAME) ) ).'</h3>

					<p>'.esc_html__( 'Upgrade to the Pro version and enjoy exclusive features. Take your experience to the next level and enhance your workflow with premium tools.', 'sastra-essential-addons-for-elementor' ).'</p>

					<div class="tmpcoder-upgrade-pro-notice-actions">
						'.sprintf( '<a href="%s" class="button button-primary tmpcoder-upgrade-pro-button" target="_blank">%s</a>', $upgrade_url, esc_html__( 'Upgrade to Pro', 'sastra-essential-addons-for-elementor' ) ).'
					</div>
				</div>';

            	echo '<div class="notice1 notice-info tmpcoder-upgrade-pro-notice tmpcoder-upgrade-pro-notice-dismissible tmpcoder-upgrade-pro-notice-extended tmpcoder-notice-banner">' . wp_kses_post($upgrade_pro_notice) .'</div>';
        	}
		}
		    /* upgrade to PRO version notice [END] */
    }, 99 );
	
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'tmpcoder_addons_fail_load' );
		return;
	}
	
	if (class_exists('\Elementor\Plugin')) {
		
		$elementor_version_required = '2.0.0';
		if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
			add_action( 'admin_notices', 'tmpcoder_addon_fail_load_out_of_date' );
			return;
		}
	}

	require TMPCODER_PLUGIN_DIR.'inc/admin/notice/notice.php';
}
add_action( 'plugins_loaded', 'tmpcoder_addons_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function tmpcoder_addons_fail_load() {

	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id || $screen->parent_file == 'tmpcoder-setup-wizard') {
		return;
	}

	$tmpcoder_notice_excluded_pages = array( 'tmpcoder-setup-wizard', 'tmpcoder-theme-wizard', 'tmpcoder-plugin-wizard' );

	if ( isset($_GET['page']) && in_array($_GET['page'], $tmpcoder_notice_excluded_pages, true) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	
	$plugin = 'elementor/elementor.php';

	if ( tmpcoder_is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin='. $plugin .'&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_'. $plugin );

		$message = '<p>' . esc_html__( 'Spexo Addons for Elementor is not working because you need to activate the Elementor plugin.', 'sastra-essential-addons-for-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Elementor Now', 'sastra-essential-addons-for-elementor' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . esc_html__( 'Spexo Addons for Elementor is not working because you need to install the Elemenor plugin', 'sastra-essential-addons-for-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor Now', 'sastra-essential-addons-for-elementor' ) ) . '</p>';
	}

	echo '<div class="error"><p>'. wp_kses_post($message) .'</p></div>';
}

function tmpcoder_addon_fail_load_out_of_date() {
	
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_'. $file_path );
	$message = '<p>' . esc_html__( 'Spexo Addons for Elementor is not working because you are using an old version of Elementor.', 'sastra-essential-addons-for-elementor' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, esc_html__( 'Update Elementor Now', 'sastra-essential-addons-for-elementor' ) ) . '</p>';

	echo '<div class="error">'. wp_kses_post($message) .'</div>';
}

if ( ! function_exists( 'tmpcoder_is_elementor_installed' ) ) {
	function tmpcoder_is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();
		return isset( $installed_plugins[ $file_path ] );
	}
}

if ( ! function_exists( 'tmpcoder_is_sastra_addons_pro_installed' ) ) {
	function tmpcoder_is_sastra_addons_pro_installed() {
		$file_path = defined('TMPCODER_PRO_PLUGIN_KEY') ? TMPCODER_PRO_PLUGIN_KEY.'/'.TMPCODER_PRO_PLUGIN_KEY.'.php' : '';
		$installed_plugins = get_plugins();
		return ( isset( $installed_plugins[ $file_path ] ) || isset( $installed_plugins[ 'sastra-addons-pro/sastra-addons-pro.php' ] ) );
	}
}

function tmpcoder_plugin_activate() {
	set_transient('tmpcoder_plugin_do_activation_redirect', true, 60);
    delete_transient('tmpcoder_latest_updates_notice_banner');

    // Disable Elementor color schemes.
    update_option('elementor_disable_color_schemes', 'yes');

    // Disable Elementor typography schemes.
    update_option('elementor_disable_typography_schemes', 'yes');
}

function tmpcoder_plugin_redirect() {
	if (get_transient('tmpcoder_plugin_do_activation_redirect')) {
		delete_transient('tmpcoder_plugin_do_activation_redirect');

        // Flush rewrite rules
        flush_rewrite_rules();
		
        $redirect_url = esc_url_raw( admin_url('admin.php?page=spexo-welcome'));
        wp_safe_redirect( $redirect_url );
        exit;

	}
}

add_action('admin_init', function(){
    if ( is_admin() ) {
        $_wizard_page_redirect = get_option(TMPCODER_PLUGIN_KEY.'_wizard_page_redirect', 0);
        
        $wizard_run = get_option(TMPCODER_PLUGIN_KEY.'_wizard_page' , 0);
        if ( ($_wizard_page_redirect == 1 || $wizard_run == 0) && TMPCODER_CURRENT_THEME_NAME != 'Belliza' ) {
            delete_option(TMPCODER_PLUGIN_KEY.'_wizard_page_redirect');
            update_option(TMPCODER_PLUGIN_KEY.'_wizard_page', 1);
            update_option('sastrawp_wizard_page', 1);
            update_option('spexo_wizard_page', 1);
            wp_redirect( admin_url('admin.php?page=tmpcoder-setup-wizard') );
        }
    }
});

if ( did_action( 'elementor/loaded' ) ) {
	
	register_activation_hook(__FILE__, 'tmpcoder_plugin_activate');
	add_action('admin_init', 'tmpcoder_plugin_redirect');
}

// Set Plugin Activation Time
function tmpcoder_sastra_elementor_addon_activation_time() {//TODO: Try to locate this in rating-notice.php later if possible
	if ( false === get_option( 'tmpcoder_sastra_elementor_addon_activation_time' ) ) {
		add_option( 'tmpcoder_sastra_elementor_addon_activation_time', absint(intval(strtotime('now'))) );
	}

	if ( false === get_option( 'tmpcoder_sastra_elementor_addon_activation_time_for_sale' ) ) {
		add_option( 'tmpcoder_sastra_elementor_addon_activation_time_for_sale', absint(intval(strtotime('now'))) );
	}
	
	if ( get_option('tmpcoder_plugin_update_dismiss_notice_' . get_plugin_data(TMPCODER_PLUGIN_FILE)['Version']) ) {
		delete_option('tmpcoder_plugin_update_dismiss_notice_' . get_plugin_data(TMPCODER_PLUGIN_FILE)['Version']);
	}

	if ( is_admin() ) {
        $wizard_run = get_option(TMPCODER_PLUGIN_KEY.'_wizard_page' , 0);
        if ( $wizard_run == 0 ) {
            update_option(TMPCODER_PLUGIN_KEY.'_wizard_page_redirect', 1);
        }
    }
}

// hook already exists with template kits notice
register_deactivation_hook( __FILE__, 'tmpcoder_sastra_elementor_addon_activation_time' );

if (is_multisite() && function_exists('is_plugin_active_for_network') && is_plugin_active_for_network(plugin_basename(__FILE__))) {
    
    add_action('admin_notices', 'tmpcoder_sastra_network_activation_notice');
    add_action('network_admin_notices', 'tmpcoder_sastra_network_activation_notice');
    
    function tmpcoder_sastra_network_activation_notice() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php echo sprintf(
            	/* translators: 1: Plugin Name. */
            	esc_html__('%s should not be network activated. Please deactivate it from the network and activate it per site.','sastra-essential-addons-for-elementor'), esc_html(TMPCODER_PLUGIN_NAME)); ?>
        	</p>
        </div>
        <?php
    }
}

add_filter( 'plugin_action_links_' . plugin_basename( TMPCODER_PLUGIN_FILE ), 'tmpcoder_add_action_links' );

function tmpcoder_add_action_links( $links ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return $links;
    }

    $links = array_merge( [
        sprintf( '<a href="%s">%s</a>',
            admin_url('admin.php?page=spexo-welcome'),
            esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' )
        )
    ], $links );
    
    if ( !defined( 'TMPCODER_ADDONS_PRO_VERSION' ) ) {
        $links = array_merge( $links, [
            sprintf( '<a target="_blank" style="color:#5729d9; font-weight: bold;" href="%s">%s</a>',
                TMPCODER_PURCHASE_PRO_URL,
                esc_html__( 'Get Pro', 'sastra-essential-addons-for-elementor' )
            )
        ] );
    }
    return $links;
}