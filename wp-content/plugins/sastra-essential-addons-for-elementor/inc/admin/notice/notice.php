<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action('admin_enqueue_scripts','tmpcoder_demo_import_scripts_func');

if ( ! function_exists( 'tmpcoder_demo_import_scripts_func' ) ) :
    
    function tmpcoder_demo_import_scripts_func() {

        wp_enqueue_script( 'tmpcoder-theme-install-from-notice', plugins_url( 'inc/admin/notice/js/notice'.tmpcoder_script_suffix().'.js', TMPCODER_PLUGIN_FILE ), ['updates'] , TMPCODER_PLUGIN_VER, true);

        wp_localize_script( 'tmpcoder-theme-install-from-notice', 'tmpcoder_ajax_object', array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'welcome_url' => admin_url().'admin.php?page=tmpcoder-import-demo',
            'nonce' => wp_create_nonce('spexo-addons'),
            'installing' => esc_html('Installing...'),
            'activating' => esc_html('Activating...'),
        ));
    }
endif;

add_action( 'admin_notices', function(){

	/* Install Theme Notice [START] */

	// Get Current Theme
    $theme = get_option('stylesheet');

    if ( ! in_array($theme, array('sastrawp','sastrawp-child','spexo','spexo-child','belliza','belliza-child') ) ) {

		$tmpcoder_notice_excluded_pages = array( 'tmpcoder-setup-wizard', 'tmpcoder-theme-wizard', 'tmpcoder-plugin-wizard' );

		if ( isset($_GET['page']) && in_array($_GET['page'], $tmpcoder_notice_excluded_pages, true) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
     	
    	if (get_transient('tmpcoder_activate_theme_dismissed_' . get_current_user_id())) {
	        return;
	    }

	    if ( tmpcoder_is_theme_installed('spexo') ){

			$upgrade_pro_notice = 
	    	'<i class="tmpcoder-upgrade-pro-notice-dismiss activate-theme-notice" role="button" aria-label="Dismiss this notice." tabindex="0"></i>
			<div class="tmpcoder-license-expiration-notice-aside">
				<div class="tmpcoder-license-expiration-notice-icon-wrapper">
					<img src="'.esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/logo-40x40.svg').'" width="24" height="24">
				</div>
			</div>
			<div class="tmpcoder-license-expiration-notice-content">
				<h3>'.esc_html( 'Activate Spexo Theme!').'</h3>
				<p>'.esc_html('Activate the Spexo theme and unlock a pre-built website with global features.').'</p>
				<div class="tmpcoder-license-expiration-notice-actions">
					<button data-status="active" id="tmpcoder-install-active-theme-from-notice" class="button button-primary tmpcoder-license-renew-button tmpcoder-upgrade-pro-button">'.esc_html('Activate').'</button>
				</div>
			</div>';
		}
		else
		{
			$upgrade_pro_notice = 
			'<i class="tmpcoder-upgrade-pro-notice-dismiss install-theme-notice" role="button" aria-label="Dismiss this notice." tabindex="0"></i>
			<div class="tmpcoder-license-expiration-notice-aside">
				<div class="tmpcoder-license-expiration-notice-icon-wrapper">
					<img src="'.esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/logo-40x40.svg').'" width="24" height="24">
				</div>
			</div>
			<div class="tmpcoder-license-expiration-notice-content">
				<h3>'.esc_html( 'Install & Activate Spexo Theme!').'</h3>
				<p>'.esc_html('Install & Activate the Spexo theme and unlock a pre-built website with global features.').'</p>
				<div class="tmpcoder-license-expiration-notice-actions">
					<button id="tmpcoder-install-active-theme-from-notice" data-status="install" class="button button-primary tmpcoder-license-renew-button tmpcoder-upgrade-pro-button">'.esc_html('Install & Activate').'</button>
				</div>
			</div>';
		}

        echo '<div class="notice1 notice-info tmpcoder-upgrade-pro-notice tmpcoder-upgrade-pro-notice-dismissible tmpcoder-upgrade-pro-notice-extended tmpcoder-notice-banner">'.wp_kses($upgrade_pro_notice,tmpcoder_wp_kses_allowed_html()).'
        </div>';   
    }

    /* Install Theme Notice [END] */

}, 99 );

if (!function_exists('tmpcoder_is_theme_installed')) {
	
	function tmpcoder_is_theme_installed($theme_slug) {
	    $theme = wp_get_theme($theme_slug);
	    if ($theme->exists()) {
	        return true;
	    } else {
	        return false;
	    }
	}
}
