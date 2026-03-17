<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action("wp_ajax_tmpcoder_theme_install_func", "tmpcoder_theme_install_func");
add_action("wp_ajax_nopriv_tmpcoder_theme_install_func", "tmpcoder_theme_install_func");
function tmpcoder_theme_install_func(){

    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'tmpcoder_install_theme') ) {
    	exit; // Get out of here, the nonce is rotten!
    }
    
    if ( ! is_user_logged_in() ){
        esc_html_e("You must log in to site setup", 'sastra-essential-addons-for-elementor');
        die();
    }

    if ( ! current_user_can( 'install_themes' ) ){
        esc_html_e("Sorry, you are not allowed to install themes on this site.", 'sastra-essential-addons-for-elementor');
        die();
    }

    $theme_slug = 'spexo';
    $current_theme = (is_object(wp_get_theme()->parent())) ? wp_get_theme()->parent() : wp_get_theme();

    if ( !wp_get_theme($theme_slug)->exists() ) {

        $theme_info = tmpcoder_get_theme_info($theme_slug);

        if ( is_object($theme_info) ){

            // Include required files for installing themes
            require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
            require_once(ABSPATH . 'wp-admin/includes/theme.php');

            // Install the theme
            $upgrader = new Theme_Upgrader();
            $result = $upgrader->install($theme_info->download_link);

            esc_html_e("00000", 'sastra-essential-addons-for-elementor');

            // Check if the theme was installed successfully
            if ($result && !is_wp_error($result)) {

                switch_theme($theme_slug);

                // Flush rewrite rules to ensure proper functionality after theme switch
                flush_rewrite_rules();

                update_option(TMPCODER_PLUGIN_KEY.'_wizard_step', '1');

                wp_send_json_success(array("message"=> __('Recommended theme installed and activated successfully.','sastra-essential-addons-for-elementor') ));

            } else {
                wp_send_json_error(array('message'=> $result->get_message ));
                exit;
            }
        }

    }else if( $current_theme->get_stylesheet() !== $theme_slug && wp_get_theme($theme_slug)->exists() ){
        // Activate the theme
        switch_theme($theme_slug);
        
        // Flush rewrite rules to ensure proper functionality after theme switch
        flush_rewrite_rules();

        update_option(TMPCODER_PLUGIN_KEY.'_wizard_step', '1');
        update_option('sastrawp_wizard_page', 1);
        update_option('spexo_wizard_page', 1);
        
        esc_html_e('00000','sastra-essential-addons-for-elementor');
        echo wp_json_encode( array('success'=> true, 'data' => array("message"=> __('Recommended theme activated successfully.','sastra-essential-addons-for-elementor') ) ) );
        exit;
    }else{
        
        update_option(TMPCODER_PLUGIN_KEY.'_wizard_step', '1');

        esc_html_e('00000','sastra-essential-addons-for-elementor');

        echo wp_json_encode( array('success'=> true, 'data' => array("message"=> __('Recommended theme activated successfully.','sastra-essential-addons-for-elementor') ) ) );
        exit;
    }
}

add_action("wp_ajax_tmpcoder_wizard_pro_addons_info", "tmpcoder_wizard_pro_addons_info");
add_action("wp_ajax_nopriv_tmpcoder_wizard_pro_addons_info", "tmpcoder_wizard_pro_addons_info");
function tmpcoder_wizard_pro_addons_info(){

    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'tmpcoder_get_pro_addons_info') ) {
    	exit; // Get out of here, the nonce is rotten!
    }
    
    if ( ! is_user_logged_in() ){
        esc_html_e("You must log in to site setup", 'sastra-essential-addons-for-elementor');
        die();
    }

    $import_demo_url = admin_url().'admin.php?page=tmpcoder-import-demo&saved=plugin-wizard';

    ob_start();
        echo wp_kses_post(sprintf(
            /* translators: %s is License Activation Heading */
            '<h2 class="wizard-heading">%s</h2>', __("Get Spexo Addons Pro", 'sastra-essential-addons-for-elementor')));
            echo '<p>'.esc_html('Unlock access to all our premium widgets and features.').'</p>';
            echo '<ul class="tmpcoder-wizard-pro-features-list">
                    <li>'.esc_html('80+ Pro Widgets').'</li>
                    <li>'.esc_html('75+ Pro Prebuilt Blocks').'</li>
                    <li>'.esc_html('25+ Pro Prebuilt Sections').'</li>
                    <li>'.esc_html('30+ Pro Prebuilt WebSites').'</li>
                </ul>';

            echo "<a target='_blank' href='".esc_url(TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-setup-wizard')."' class='tmpcoder-get-pro-btn'>";
            ?>

            <img src="<?php echo esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/pro-icon.svg'); ?>">
            <span><?php echo esc_html__( 'Get Pro Now', 'sastra-essential-addons-for-elementor' ); ?></span>

            <?php
            echo "</a>";

            echo '<div class="next-step-action">';
            echo '<a href='.esc_url($import_demo_url).' class="button button-primary next-step-btn">'.esc_html('Done').'</a>';
            echo '</div>';

        $GLOBALS['show_on_wizard'] = 1;

    $output = ob_get_contents();
    ob_end_clean();

    wp_send_json_success(array( 'data'=> $output ));
}

add_action("wp_ajax_tmpcoder_get_required_plugins_func", "tmpcoder_get_required_plugins_func");
add_action("wp_ajax_nopriv_tmpcoder_get_required_plugins_func", "tmpcoder_get_required_plugins_func");
function tmpcoder_get_required_plugins_func(){

    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'tmpcoder_get_plugins') ) {
    	exit; // Get out of here, the nonce is rotten!
    }
    
    if ( ! is_user_logged_in() ){
        esc_html_e("You must log in to site setup", 'sastra-essential-addons-for-elementor');
        die();
    }

    $tgmpaClass = $GLOBALS['tgmpa'];
    $plugins = array();
    $next_step = __('Next', 'sastra-essential-addons-for-elementor');
    $activated_plugin = [];

    if ( is_object($tgmpaClass) ){
        if ( empty($tgmpaClass->plugins) ){
            $tmpcoder_mainClass = new Tmpcoder_Main_Class();
            $tmpcoder_mainClass->tmpcoder_require_plugins();
            $tgmpaClass = $GLOBALS['tgmpa'];
        }

        if ( !empty($tgmpaClass->plugins) ) {

            foreach( $tgmpaClass->plugins as $plugKey => $plugin ){

                if ($plugKey == 'sastra-essential-addons-for-elementor') {
                    continue;
                }

                $image = '';
                $plugin_info = wp_remote_get('https://api.wordpress.org/plugins/info/1.0/'.$plugKey.'.json?fields=banners,icons');

                if ( is_array( $plugin_info ) && ! is_wp_error( $plugin_info ) ) {
                    $body    = json_decode($plugin_info['body'], true);
                    if ( isset($body['icons']) ){
                        if ( isset($body['icons']['svg']) ){
                            $image = $body['icons']['svg'];
                        }else if ( isset($body['icons']['2x']) ){
                            $image = $body['icons']['2x'];
                        }else if ( isset($body['icons']['1x']) ){
                            $image = $body['icons']['1x'];
                        }else{
                            $image = $body['icons']['default'];
                        }
                    }
                }

                $plugin['image'] = $image;

                $plugin['link'] = 'https://wordpress.org/plugins/'. $plugin['slug'];

                // modify these variables with your new/old plugin values
                $plugin_slug = $plugin['slug'];
                $plugin_file_path = $plugin['file_path'];
                
                if (is_plugin_active($plugin_file_path)) {
                    array_push($activated_plugin, $plugin_slug);
                }

                if ( is_plugin_installed( $plugin_file_path ) && in_array($plugin_file_path, apply_filters('active_plugins', get_option('active_plugins'))) ){
                    $plugin['activated'] = true;
                }else if ( is_plugin_installed( $plugin_file_path ) ) {
                    $plugin['installed'] = true;
                    $next_step = __('Install & Activate', 'sastra-essential-addons-for-elementor');
                }else{
                    $next_step = __('Install & Activate', 'sastra-essential-addons-for-elementor');
                }

                array_push($plugins, $plugin);
            }

            $skip_this = false;
            if (count($activated_plugin) == 2) {
                $skip_this = true;
                update_option(TMPCODER_PRO_PLUGIN_KEY.'_wizard_step', '2');
            }
        }
    }

    if ( !empty($plugins) ){
        wp_send_json_success(
            array(
                'plugins' => $plugins,
                'message' => __('Plugins getting successfully.','sastra-essential-addons-for-elementor'),
                'next_step' => $next_step,
                'skip_this' => $skip_this
            )
        );
    }else{
        $error = __('No Required plugins found','sastra-essential-addons-for-elementor');
        wp_send_json_error(array('message'=> $error ));
    }
}

add_action("wp_ajax_tmpcoder_install_required_plugins_func", "tmpcoder_install_required_plugins_func");
add_action("wp_ajax_nopriv_tmpcoder_install_required_plugins_func", "tmpcoder_install_required_plugins_func");
function tmpcoder_install_required_plugins_func(){

    // Check if nonce is valid.
    if ( ! isset($_POST['_wpnonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash ($_POST['_wpnonce'])), 'tmpcoder_install_plugins' ) ) {
        exit;
    }
    
    if ( ! is_user_logged_in() ){
        esc_html_e("You must log in to site setup", 'sastra-essential-addons-for-elementor');
        die();
    }

    if ( ! current_user_can('install_plugins') ) {
        esc_html_e('Invalid User', 'sastra-essential-addons-for-elementor');
        die();
    }

    $plugins = '';
    if ( isset( $_POST['plugins'] ) && is_array($_POST['plugins'])) {
        // Sanitize each element of the plugins array
        $plugins = array_map('sanitize_text_field',wp_unslash($_POST['plugins']));
    }

    $tgmpaClass = $GLOBALS['tgmpa'];
    $error = array();

    ob_start(); // default print off

    if ( is_object($tgmpaClass) ){
        if ( empty($tgmpaClass->plugins) ){
            $tmpcoder_mainClass = new Tmpcoder_Main_Class();
            $tmpcoder_mainClass->tmpcoder_require_plugins();
            $tgmpaClass = $GLOBALS['tgmpa'];
        }

        if ( !empty($tgmpaClass->plugins) ) {
            foreach( $tgmpaClass->plugins as $plugKey => $plugin ){

                if ( isset($plugins[$plugKey]) && $plugins[$plugKey] == '1' ){

                    // modify these variables with your new/old plugin values
                    $plugin_slug = $plugin['slug'];
                    $plugin_file_path = $plugin['file_path'];

                    if ( is_plugin_installed( $plugin_file_path ) ) {
                        
                        tmpcoder_update_plugin( $plugin_file_path );
                        $installed = true;
                    } else {
                        $plugin_zip = $tgmpaClass->get_download_url($plugin_slug);

                        $installed = tmpcoder_install_plugin($plugin_slug);
                    }

                    if ( !is_wp_error( $installed ) && $installed ) {

                        $plugin_file_path = plugin_basefile_path($plugin_slug);

                        $activate = activate_plugin( $plugin_file_path );
                        
                        if ( is_null($activate) ) {
                            
                        }else{
                            if ( is_wp_error( $activate ) ){
                                {
                                    $error[] = $plugin['name'].': '.$activate->get_error_message();
                                }
                            }
                        }
                    } else {
                        $error[] = $plugin['name'];
                    }
                }
            }
        }
    }

    ob_end_clean();
    
    esc_html_e("  00000", 'sastra-essential-addons-for-elementor');

    if ( empty($error) ){

        update_option(TMPCODER_PLUGIN_KEY.'_wizard_step', '2');

        echo wp_json_encode( array('success'=> true, 'data' => array("message"=> __('All Required plugins installed successfully.','sastra-essential-addons-for-elementor') ) ) );
        exit;

    }else{
        $error = implode(', ', $error). __(' Could not install','sastra-essential-addons-for-elementor');
        wp_send_json_error(array('message'=> $error ));
    }
}