<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register Post Type
function tmpcoder_register_mega_menu_cpt() {
    $args = array(
        'label'               => esc_html__( 'Spexo Mega Menu', 'sastra-essential-addons-for-elementor' ),
        'public'              => true,
        'publicly_queryable'  => true,
        'rewrite'             => false,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_nav_menus'   => false,
        'exclude_from_search' => true,
        'capability_type'     => 'post',
        'supports'            => array( 'title', 'editor', 'elementor' ),
        'hierarchical'        => false,
    );

    register_post_type( 'tmpcoder_mega_menu', $args );
}

/**
** Theme Builder Template Check
*/

if (!function_exists('tmpcoder_is_theme_builder_template')) {
    
    function tmpcoder_is_theme_builder_template() {
        $current_page = get_post(get_the_ID());

        if ( $current_page ) {
            return strpos($current_page->post_name, 'user-archive') !== false || strpos($current_page->post_name, 'user-single') !== false || strpos($current_page->post_name, 'user-product') !== false;
        } else {
            return false;
        }
    }
}

// Convert to Canvas Template
function tmpcoder_convert_to_canvas_template( $template ) {
    if ( is_singular('tmpcoder_mega_menu') ) {
        return WP_PLUGIN_DIR . '/elementor/modules/page-templates/templates/canvas.php';
    }
    return $template;
}

// Init Mega Menu
function tmpcoder_init_mega_menu() {
    tmpcoder_register_mega_menu_cpt();
    add_action( 'template_include', 'tmpcoder_convert_to_canvas_template', 9999 );
}

add_action('init', 'tmpcoder_init_mega_menu', 999);


// Confinue only for Dashboard Screen
if ( !is_admin() ) return;

// Init Actions
add_filter( 'option_elementor_cpt_support', 'tmpcoder_add_mega_menu_cpt_support' );
add_filter( 'default_option_elementor_cpt_support', 'tmpcoder_add_mega_menu_cpt_support' );
add_action( 'admin_footer', 'tmpcoder_render_settings_popup', 10 );
add_action( 'wp_ajax_tmpcoder_create_mega_menu_template', 'tmpcoder_create_mega_menu_template' );
add_action( 'wp_ajax_tmpcoder_save_mega_menu_settings', 'tmpcoder_save_mega_menu_settings' );
add_action( 'admin_enqueue_scripts', 'tmpcoder_enqueue_scripts' );

// Add Elementor Editor Support
function tmpcoder_add_mega_menu_cpt_support( $value ) {
    if ( empty( $value ) ) {
        $value = [];
    }

    return array_merge( $value, ['tmpcoder_mega_menu'] ); 
}

// Create Menu Template
function tmpcoder_create_mega_menu_template() {

    if ( !isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'tmpcoder-mega-menu-js' ) || !current_user_can( 'manage_options' ) ) {
      return; // Get out of here, the nonce is rotten!
    }

    $menu_item_id = isset($_POST['item_id']) ? absint( $_POST['item_id'] ) : 0;
    $mega_menu_id = get_post_meta( $menu_item_id, 'tmpcoder-mega-menu-item', true );

    if ( ! $mega_menu_id ) {

        $mega_menu_id = wp_insert_post( array(
            'post_title'  => 'tmpcoder-mega-menu-item-' . $menu_item_id,
            'post_status' => 'publish',
            'post_type'   => 'tmpcoder_mega_menu',
        ) );

        update_post_meta( $menu_item_id, 'tmpcoder-mega-menu-item', $mega_menu_id );

    }

    $edit_link = add_query_arg(
        array(
            'post' => $mega_menu_id,
            'action' => 'elementor',
        ),
        admin_url( 'post.php' )
    );

    wp_send_json([
        'data' => [
            'edit_link' => $edit_link
        ]
    ]);
}

// Render Settings Popup
function tmpcoder_render_settings_popup() {
    $screen = get_current_screen();

    if ( 'nav-menus' !== $screen->base ) {
        return;
    }

    ?>

    <div class="tmpcoder-mm-settings-popup-wrap">
        <div class="tmpcoder-mm-settings-popup">
            <div class="tmpcoder-mm-settings-popup-header">
                <span class="tmpcoder-mm-popup-logo" style="background:url('<?php echo esc_url(TMPCODER_ADDONS_ASSETS_URL .'images/logo-40x40.svg'); ?>') no-repeat center center / contain;">SA</span>
                <span><?php esc_html_e('Spexo Mega Menu', 'sastra-essential-addons-for-elementor'); ?></span>
                <span class="tmpcoder-mm-popup-title"><?php esc_html_e('Menu Item: ', 'sastra-essential-addons-for-elementor'); ?><span></span></span>
                <span class="dashicons dashicons-no-alt tmpcoder-mm-settings-close-popup-btn"></span>
            </div>

            <?php $pro_active = tmpcoder_is_availble() ? 'data-pro-active="true"' : 'data-pro-active="false"'; ?>
            
            <div class="tmpcoder-mm-settings-wrap" <?php echo esc_attr($pro_active); ?>>
                <h4><?php esc_html_e('General', 'sastra-essential-addons-for-elementor'); ?></h4>
                <div class="tmpcoder-mm-setting tmpcoder-mm-setting-switcher">
                    <h4><?php esc_html_e('Enable Mega Menu', 'sastra-essential-addons-for-elementor'); ?></h4>
                    <input type="checkbox" id="tmpcoder_mm_enable">
                    <label for="tmpcoder_mm_enable"></label>
                </div>
                <div class="tmpcoder-mm-setting">
                    <h4><?php esc_html_e('Mega Menu Content', 'sastra-essential-addons-for-elementor'); ?></h4>
                    <button class="button button-primary tmpcoder-edit-mega-menu-btn">
                        <i class="eicon-elementor-square" aria-hidden="true"></i>
                        <?php esc_html_e('Edit with Elementor', 'sastra-essential-addons-for-elementor'); ?>
                    </button>
                </div>
                <div class="tmpcoder-mm-setting">
                    <h4><?php esc_html_e('Dropdown Position', 'sastra-essential-addons-for-elementor'); ?></h4>
                    <select id="tmpcoder_mm_position">
                        <option value="default"><?php esc_html_e('Default', 'sastra-essential-addons-for-elementor'); ?></option>
                        <option value="relative"><?php esc_html_e('Relative', 'sastra-essential-addons-for-elementor'); ?></option>
                    </select>
                </div>
                <div class="tmpcoder-mm-setting">
                    <h4><?php esc_html_e('Dropdown Width', 'sastra-essential-addons-for-elementor'); ?></h4>
                    <select id="tmpcoder_mm_width">
                        <option value="default"><?php esc_html_e('Default', 'sastra-essential-addons-for-elementor'); ?></option>
                        <?php if ( ! tmpcoder_is_availble() ) : ?>
                        <option value="pro-st"><?php esc_html_e('Fit to Section (Pro)', 'sastra-essential-addons-for-elementor'); ?></option>
                        <?php else: ?>
                        <option value="stretch"><?php esc_html_e('Fit to Section', 'sastra-essential-addons-for-elementor'); ?></option>
                        <?php endif; ?>
                        <option value="full"><?php esc_html_e('Full Width', 'sastra-essential-addons-for-elementor'); ?></option>
                        <option value="custom"><?php esc_html_e('Custom', 'sastra-essential-addons-for-elementor'); ?></option>
                    </select>
                </div>
                <div class="tmpcoder-mm-setting">
                    <h4><?php esc_html_e('Custom Width (px)', 'sastra-essential-addons-for-elementor'); ?></h4>
                    <input type="number" id="tmpcoder_mm_custom_width" value="600">
                </div>
                <div class="tmpcoder-mm-setting <?php echo !tmpcoder_is_availble() ? 'tmpcoder-mm-pro-setting' : ''; ?>">
                    <h4><?php esc_html_e('Mobile Sub Content', 'sastra-essential-addons-for-elementor'); ?></h4>
                    <div>
                        <select id="tmpcoder_mm_mobile_content">
                            <option value="mega"><?php esc_html_e('Mega Menu', 'sastra-essential-addons-for-elementor'); ?></option>
                            <option value="wp-sub"><?php esc_html_e('WordPress Sub Items', 'sastra-essential-addons-for-elementor'); ?></option>
                        </select>

                        <div class="tmpcoder-mm-pro-radio">
                            <input type="radio" name="mc" checked="checked">
                            <label>Mega Menu</label><br>
                            <input type="radio" name="mc">
                            <label>WordPress Sub Items</label>
                        </div>
                    </div>
                </div>
                <div class="tmpcoder-mm-setting <?php echo !tmpcoder_is_availble() ? 'tmpcoder-mm-pro-setting' : ''; ?>">
                    <h4><?php esc_html_e('Mobile Sub Render', 'sastra-essential-addons-for-elementor'); ?></h4>
                    <div>
                        <select id="tmpcoder_mm_render">
                            <option value="default"><?php esc_html_e('Default', 'sastra-essential-addons-for-elementor'); ?></option>
                            <option value="ajax"><?php esc_html_e('Load with AJAX', 'sastra-essential-addons-for-elementor'); ?></option>
                        </select>

                        <div class="tmpcoder-mm-pro-radio">
                            <input type="radio" name="mr" checked="checked">
                            <label>Default</label><br>
                            <input type="radio" name="mr">
                            <label>Load with AJAX</label>
                        </div>
                    </div>
                </div>

                <br>

                <h4 <?php echo !tmpcoder_is_availble() ? 'class="tmpcoder-mm-pro-heading"' : ''; ?>>
                    <?php esc_html_e('Icon', 'sastra-essential-addons-for-elementor'); ?>
                </h4>
                <div <?php echo !tmpcoder_is_availble() ? 'class="tmpcoder-mm-pro-section"' : ''; ?>>
                    <div class="tmpcoder-mm-setting tmpcoder-mm-setting-icon">
                        <h4><?php esc_html_e('Icon Select', 'sastra-essential-addons-for-elementor'); ?></h4>
                        <div><span class="tmpcoder-mm-active-icon"><i class="fas fa-ban"></i></span><span><i class="fas fa-angle-down"></i></span></div>
                        <input type="text" id="tmpcoder_mm_icon_picker" data-alpha="true" value="">
                    </div>
                    <div class="tmpcoder-mm-setting tmpcoder-mm-setting-color">
                        <h4><?php esc_html_e('Icon Color', 'sastra-essential-addons-for-elementor'); ?></h4>
                        <input type="text" id="tmpcoder_mm_icon_color" data-alpha="true" value="rgba(0,0,0,0.6);">
                    </div>
                    <div class="tmpcoder-mm-setting">
                        <h4><?php esc_html_e('Icon Size (px)', 'sastra-essential-addons-for-elementor'); ?></h4>
                        <input type="number" id="tmpcoder_mm_icon_size" value="14">
                    </div>
                </div>

                <br>

                <h4 <?php echo !tmpcoder_is_availble() ? 'class="tmpcoder-mm-pro-heading"' : ''; ?>>
                    <?php esc_html_e('Badge', 'sastra-essential-addons-for-elementor'); ?>
                </h4>
                <div <?php echo !tmpcoder_is_availble() ? 'class="tmpcoder-mm-pro-section"' : ''; ?>>
                    <div class="tmpcoder-mm-setting">
                        <h4><?php esc_html_e('Badge Text', 'sastra-essential-addons-for-elementor'); ?></h4>
                        <input type="text" id="tmpcoder_mm_badge_text" value="">
                    </div>
                    <div class="tmpcoder-mm-setting tmpcoder-mm-setting-color">
                        <h4><?php esc_html_e('Badge Text Color', 'sastra-essential-addons-for-elementor'); ?></h4>
                        <input type="text" id="tmpcoder_mm_badge_color" data-alpha="true" value="rgba(0,0,0,0.6);">
                    </div>
                    <div class="tmpcoder-mm-setting tmpcoder-mm-setting-color">
                        <h4><?php esc_html_e('Badge Background Color', 'sastra-essential-addons-for-elementor'); ?></h4>
                        <input type="text" id="tmpcoder_mm_badge_bg_color" data-alpha="true" value="rgba(0,0,0,0.6);">
                    </div>
                    <div class="tmpcoder-mm-setting tmpcoder-mm-setting-switcher">
                        <h4><?php esc_html_e('Enable Animation', 'sastra-essential-addons-for-elementor'); ?></h4>
                        <input type="checkbox" id="tmpcoder_mm_badge_animation">
                        <label for="tmpcoder_mm_badge_animation"></label>
                    </div>
                </div>
            </div>

            <div class="tmpcoder-mm-settings-popup-footer">
                <button class="button tmpcoder-save-mega-menu-btn"><?php esc_html_e('Save', 'sastra-essential-addons-for-elementor'); ?></button>
            </div>
        </div>
    </div>

    <!-- Iframe Popup -->
    <div class="tmpcoder-mm-editor-popup-wrap">
        <div class="tmpcoder-mm-editor-close-popup-btn"><span class="dashicons dashicons-no-alt"></span></div>
        <div class="tmpcoder-mm-editor-popup-iframe"></div>
    </div>
    <?php
}

// Save Mega Menu Settings
function tmpcoder_save_mega_menu_settings() {

    if ( !isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'tmpcoder-mega-menu-js' ) || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }

    if ( isset($_POST['item_settings']) ) {

        $item_settings = sanitize_text_field(wp_unslash($_POST['item_settings']));
        $item_settings = json_decode($item_settings, true);

        if ( isset($_POST['item_id']) ){
            update_post_meta( absint($_POST['item_id']), 'tmpcoder-mega-menu-settings', $item_settings );
        }
    }

    wp_send_json_success($item_settings);
}

// Get Menu Items Data
function tmpcoder_get_menu_items_data( $menu_id = false ) {

    if ( ! $menu_id ) {
        return false;
    }

    $menu = wp_get_nav_menu_object( $menu_id );

    $menu_items = wp_get_nav_menu_items( $menu );

    if ( ! $menu_items ) {
        return false;
    }

    return $menu_items;
}

// Get Mega Menu Item Settings
function tmpcoder_get_menu_items_settings() {
    $menu_items = tmpcoder_get_menu_items_data( tmpcoder_get_selected_menu_id() );

    $settings = [];

    if ( ! $menu_items ) {
        return [];
    } else {
        foreach ( $menu_items as $key => $item_object ) {
            $item_id = $item_object->ID;

            $item_meta = get_post_meta( $item_id, 'tmpcoder-mega-menu-settings', true );

            if ( !empty($item_meta) ) {
                $settings[ $item_id ] = $item_meta;
            } else {
                $settings[ $item_id ] = [];
            }
        }
        
        return $settings;
    }
}

function tmpcoder_get_selected_menu_id() {
    $nav_menus = wp_get_nav_menus( array('orderby' => 'name') );
    $menu_count = count( $nav_menus );
    if ( isset( $_GET['menu'] ) ){// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $nav_menu_selected_id = (int) $_GET['menu'];// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $add_new_screen = ( 0 == $nav_menu_selected_id ) ? true : false;
    }else{
        $nav_menu_selected_id = 0;
        $add_new_screen = false;
    }

    $current_menu_id = $nav_menu_selected_id;

    // If we have one theme location, and zero menus, we take them right into editing their first menu
    $page_count = wp_count_posts( 'page' );
    $one_theme_location_no_menus = ( 1 == count( get_registered_nav_menus() ) && ! $add_new_screen && empty( $nav_menus ) && ! empty( $page_count->publish ) ) ? true : false;

    // Get recently edited nav menu
    $recently_edited = absint( get_user_option( 'nav_menu_recently_edited' ) );
    if ( empty( $recently_edited ) && is_nav_menu( $current_menu_id ) ) {
        $recently_edited = $current_menu_id;
    }

    // Use $recently_edited if none are selected
    if ( empty( $current_menu_id ) && ! isset( $_GET['menu'] ) && is_nav_menu( $recently_edited ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $current_menu_id = $recently_edited;
    }

    // On deletion of menu, if another menu exists, show it
    if ( ! $add_new_screen && 0 < $menu_count && isset( $_GET['action'] ) && 'delete' == $_GET['action'] ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $current_menu_id = $nav_menus[0]->term_id;
    }

    // Set $current_menu_id to 0 if no menus
    if ( $one_theme_location_no_menus ) {
        $current_menu_id = 0;
    } elseif ( empty( $current_menu_id ) && ! empty( $nav_menus ) && ! $add_new_screen ) {
        // if we have no selection yet, and we have menus, set to the first one in the list
        $current_menu_id = $nav_menus[0]->term_id;
    }

    return $current_menu_id;

}

// Enqueue Scripts and Styles
function tmpcoder_enqueue_scripts( $hook ) {

    // Get Plugin Version
    $version = TMPCODER_PLUGIN_VER;

    // Deny if NOT a Menu Page
    if ( 'nav-menus.php' == $hook ) {

        // Color Picker
        wp_enqueue_style( 'wp-color-picker' );

        // Icon Picker
        wp_enqueue_script( 'tmpcoder-iconpicker-js', TMPCODER_PLUGIN_URI .'assets/js/admin/lib/iconpicker/fontawesome-iconpicker'.tmpcoder_script_suffix().'.js', ['jquery'], $version, true );
        wp_enqueue_style( 'tmpcoder-iconpicker-css', TMPCODER_PLUGIN_URI .'assets/js/admin/lib/iconpicker/fontawesome-iconpicker'.tmpcoder_script_suffix().'.css', $version, true );
        wp_enqueue_style( 'tmpcoder-el-fontawesome-css', ELEMENTOR_URL .'assets/lib/font-awesome/css/all'.tmpcoder_script_suffix().'.css', [], $version );

        if ( get_option('tmpcoder-element-mega-menu') != '' ){ 
            // enqueue CSS
            wp_enqueue_style( 'tmpcoder-mega-menu-css', TMPCODER_PLUGIN_URI .'assets/css/admin/mega-menu'.tmpcoder_script_suffix().'.css', [], $version );

            // enqueue JS
            wp_enqueue_script( 'tmpcoder-mega-menu-js', TMPCODER_PLUGIN_URI .'assets/js/admin/mega-menu'.tmpcoder_script_suffix().'.js', ['jquery','wp-color-picker'], $version, false );

            wp_localize_script( 
                'tmpcoder-mega-menu-js',
                'TmpcoderMegaMenuSettingsData',
                [
                    'settingsData' => tmpcoder_get_menu_items_settings(),
                    'nonce' => wp_create_nonce( 'tmpcoder-mega-menu-js' ),
                ]
            );
        }   

    }

}