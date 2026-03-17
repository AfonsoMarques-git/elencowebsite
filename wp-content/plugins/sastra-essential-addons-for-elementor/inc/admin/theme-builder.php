<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
    
require_once (TMPCODER_PLUGIN_DIR . 'inc/admin/includes/tmpcoder-templates-loop.php');

// Register Menus
function tmpcoder_addons_add_theme_builder_menu() {
	add_submenu_page( TMPCODER_THEME.'-welcome', 'Site Builder', 'Site Builder', 'manage_options', 'spexo-welcome&tab=site-builder', 'tmpcoder_addons_theme_builder_page', 20 );
}

add_action( 'admin_menu', 'tmpcoder_addons_add_theme_builder_menu', 99 );

function tmpcoder_addons_theme_builder_page() {
 
?>

<div class="wrap tmpcoder-settings-page-wrap">

    <div class="tmpcoder-settings-page tmpcoder-site-builder-page">
        <form method="post" action="options.php">
            <?php

            // Active Tab
            $active_tab = isset( $_GET['layout_type'] ) ? sanitize_text_field( wp_unslash( $_GET['layout_type'] ) ) : 'type_header';// phpcs:ignore WordPress.Security.NonceVerification.Recommended

            ?>

            <!-- Template ID Holder -->
            <input type="hidden" name="tmpcoder_template" id="tmpcoder_template" value="">


            <div class="change-conditions-popup">
                <!-- Conditions Popup -->
                <?php TMPCODER_Templates_Loop::render_conditions_popup(true); ?>
            </div>

            <!-- Create Templte Popup -->
            <?php TMPCODER_Templates_Loop::render_create_template_popup(); ?>

            <?php TMPCODER_Templates_Loop::render_delete_template_confirm_popup(); ?>

            <!-- Tabs -->
            <div class="site-builder-main common-box-shadow tmpcoder-layout-tabs">
                <header>
                    <div class="tmpcoder-import-demo-left">
                        <div class="tmpcoder-import-demo-logo">
                            <h1><?php esc_html_e('Site Builder', 'sastra-essential-addons-for-elementor'); ?></h1>
                            <div class="tmpcoder-prebuilt-demo-doc-link">
                                <a href="<?php echo esc_url(TMPCODER_DOCUMENTATION_URL.'site-builder-overview') ?>" target="_blank" class="btn-link">
                                    <i class="dashicons dashicons-external"></i>
                                    <?php esc_html_e('How to use Site Builder', 'sastra-essential-addons-for-elementor')?>
                                </a>
                            </div>
                        </div>
                        
                    </div>

                    <div class="tmpcoder-import-demo-right">

                    </div>
                </header>
                <div class="nav-tab-wrapper tmpcoder-nav-tab-wrapper">
                    <a href="?page=spexo-welcome&tab=site-builder&layout_type=type_header" data-title="type_header" class="nav-tab <?php echo ($active_tab == 'type_header') ? 'nav-tab-active' : ''; ?>">
                        <?php esc_html_e( 'Header', 'sastra-essential-addons-for-elementor' ); ?>
                    </a>
                    <a href="?page=spexo-welcome&tab=site-builder&layout_type=type_footer" data-title="type_footer" class="nav-tab <?php echo ($active_tab == 'type_footer') ? 'nav-tab-active' : ''; ?>">
                        <?php esc_html_e( 'Footer', 'sastra-essential-addons-for-elementor' ); ?>
                    </a>
                    <a href="?page=spexo-welcome&tab=site-builder&layout_type=type_archive" data-title="type_archive" class="nav-tab <?php echo ($active_tab == 'type_archive') ? 'nav-tab-active' : ''; ?>">
                        <?php esc_html_e( 'Post Archive', 'sastra-essential-addons-for-elementor' ); ?>
                    </a>
                    <a href="?page=spexo-welcome&tab=site-builder&layout_type=type_single_post" data-title="type_single_post" class="nav-tab <?php echo ($active_tab == 'type_single_post') ? 'nav-tab-active' : ''; ?>">
                        <?php esc_html_e( 'Single Post', 'sastra-essential-addons-for-elementor' ); ?>
                    </a>
                    <a href="?page=spexo-welcome&tab=site-builder&layout_type=type_search_result_page" data-title="type_search_result_page" class="nav-tab <?php echo ($active_tab == 'type_search_result_page') ? 'nav-tab-active' : ''; ?>">
                        <?php esc_html_e( 'Search Results Page', 'sastra-essential-addons-for-elementor' ); ?>
                    </a>
                    <a href="?page=spexo-welcome&tab=site-builder&layout_type=type_404" data-title="type_404" class="nav-tab <?php echo ($active_tab == 'type_404') ? 'nav-tab-active' : ''; ?>">
                        <?php esc_html_e( '404 Page', 'sastra-essential-addons-for-elementor' ); ?>
                    </a>
                    <a href="?page=spexo-welcome&tab=site-builder&layout_type=type_product_archive" data-title="type_product_archive" class="nav-tab <?php echo esc_attr($active_tab == 'type_product_archive' ? 'nav-tab-active' : ''); ?>">
                        <?php esc_html_e( 'Product Archive', 'sastra-essential-addons-for-elementor' ); ?>
                    </a>
                    <a href="?page=spexo-welcome&tab=site-builder&layout_type=type_product_category" data-title="type_product_category" class="nav-tab <?php echo esc_attr($active_tab == 'type_product_category' ? 'nav-tab-active' : ''); ?>">
                        <?php esc_html_e( 'Product Category', 'sastra-essential-addons-for-elementor' ); ?>
                    </a>
                    <a href="?page=spexo-welcome&tab=site-builder&layout_type=type_single_product" data-title="type_single_product" class="nav-tab <?php echo esc_attr($active_tab == 'type_single_product' ? 'nav-tab-active' : ''); ?>">
                        <?php esc_html_e( 'Single Product', 'sastra-essential-addons-for-elementor' ); ?>
                    </a>
                    <a href="?page=spexo-welcome&tab=site-builder&layout_type=type_global_template" data-title="type_global_template" class="nav-tab <?php echo esc_attr($active_tab == 'type_global_template' ? 'nav-tab-active' : ''); ?>">
                        <?php esc_html_e( 'Global Templates', 'sastra-essential-addons-for-elementor' ); ?>
                    </a>
                </div>

        <?php
            if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
                $url = '';
                if ( 'tmpcoder_tab_my_templates' === $active_tab ) {
                    $url = admin_url( 'edit.php?post_type=elementor_library&tabs_group=library' );
                } else {
                    $url = admin_url( 'edit.php?s&post_status=all&post_type=tmpcoder_templates&tmpcoder_template_type='. str_replace("tmpcoder_tab_", "", $active_tab) .'&filter_action=Filter' );
                }
                echo '<a href="' . esc_url( $url ) . '" class="button button-primary tmpcoder-translate-templates tmpcoder-options-button"><span class="dashicons dashicons-admin-site"></span><span>'.esc_html__('Translate WPML Templates', 'sastra-essential-addons-for-elementor').'</span></a>';
            }
        ?>
            <?php if ( $active_tab == 'type_header' ) : ?>

                <!-- Save Conditions -->
                <input type="hidden" name="tmpcoder_type_header_conditions" id="tmpcoder_type_header_conditions" value="<?php echo esc_attr(get_option('tmpcoder_type_header_conditions', '[]')); ?>">

                <?php TMPCODER_Templates_Loop::render_theme_builder_templates( 'type_header' ); ?>

            <?php elseif ( $active_tab == 'type_footer' ) : ?>

                <!-- Save Conditions -->
                <input type="hidden" name="tmpcoder_type_footer_conditions" id="tmpcoder_type_footer_conditions" value="<?php echo esc_attr(get_option('tmpcoder_type_footer_conditions', '[]')); ?>">

                <?php TMPCODER_Templates_Loop::render_theme_builder_templates( 'type_footer' ); ?>

            <?php elseif ( $active_tab == 'type_archive' ) : ?>

                <!-- Save Conditions -->
                <input type="hidden" name="tmpcoder_type_archive_conditions" id="tmpcoder_type_archive_conditions" value="<?php echo esc_attr(get_option('tmpcoder_type_archive_conditions', '[]')); ?>">

                <?php TMPCODER_Templates_Loop::render_theme_builder_templates( 'type_archive' ); ?>

            <?php elseif ( $active_tab == 'type_search_result_page' ) : ?>

                <!-- Save Conditions -->
                <input type="hidden" name="tmpcoder_type_search_result_page_conditions" id="tmpcoder_type_search_result_page_conditions" value="<?php echo esc_attr(get_option('tmpcoder_type_search_result_page_conditions', '[]')); ?>">

                <?php TMPCODER_Templates_Loop::render_theme_builder_templates( 'type_search_result_page' ); ?>

            <?php elseif ( $active_tab == 'type_404' ) : ?>

                <!-- Save Conditions -->
                <input type="hidden" name="tmpcoder_type_404_conditions" id="tmpcoder_type_404_conditions" value="<?php echo esc_attr(get_option('tmpcoder_type_404_conditions', '[]')); ?>">

                <?php TMPCODER_Templates_Loop::render_theme_builder_templates( 'type_404' ); ?>

            <?php elseif ( $active_tab == 'type_single_post' ) : ?>

                <!-- Save Conditions -->
                <input type="hidden" name="tmpcoder_type_single_post_conditions" id="tmpcoder_type_single_post_conditions" value="<?php echo esc_attr(get_option('tmpcoder_type_single_post_conditions', '[]')); ?>">

                <?php TMPCODER_Templates_Loop::render_theme_builder_templates( 'type_single_post' ); ?>

            <?php elseif ( $active_tab == 'type_product_archive' ) : ?>
                
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <!-- Save Conditions -->
                    <input type="hidden" name="tmpcoder_type_product_archive_conditions" id="tmpcoder_type_product_archive_conditions" value="<?php echo esc_attr(get_option('tmpcoder_type_product_archive_conditions', '[]')); ?>">

                    <?php TMPCODER_Templates_Loop::render_theme_builder_templates( 'type_product_archive' ); ?>
                <?php else : ?>
                    <div class="tmpcoder-activate-woo-notice"><span class="dashicons dashicons-info-outline"></span> <?php esc_html_e('Please install/activate WooCommerce in order to create product archive templates!', 'sastra-essential-addons-for-elementor'); ?></div>
                <?php endif; ?>

            <?php elseif ( $active_tab == 'type_product_category' ) : ?>
                
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <!-- Save Conditions -->
                    <input type="hidden" name="tmpcoder_type_product_category_conditions" id="tmpcoder_type_product_category_conditions" value="<?php echo esc_attr(get_option('tmpcoder_type_product_category_conditions', '[]')); ?>">

                    <?php TMPCODER_Templates_Loop::render_theme_builder_templates( 'type_product_category' ); ?>
                <?php else : ?>
                    <div class="tmpcoder-activate-woo-notice"><span class="dashicons dashicons-info-outline"></span> <?php esc_html_e('Please install/activate WooCommerce in order to create product archive templates!', 'sastra-essential-addons-for-elementor'); ?></div>
                <?php endif; ?>

            <?php elseif ( $active_tab == 'type_single_product' ) : ?>

                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <!-- Save Conditions -->
                    <input type="hidden" name="tmpcoder_type_single_product_conditions" id="tmpcoder_type_single_product_conditions" value="<?php echo esc_attr(get_option('tmpcoder_type_single_product_conditions', '[]')); ?>">

                    <?php TMPCODER_Templates_Loop::render_theme_builder_templates( 'type_single_product' ); ?>
                <?php else : ?>
                    <div class="tmpcoder-activate-woo-notice"><span class="dashicons dashicons-info-outline"></span> <?php esc_html_e('Please install/activate WooCommerce in order to create product single templates!', 'sastra-essential-addons-for-elementor') ?></div>
                <?php endif ; ?>

            <?php elseif ( $active_tab == 'type_global_template' ) : ?>

            <?php TMPCODER_Templates_Loop::render_elementor_saved_templates( 'type_global_template' ); ?>

            <?php endif; ?>

            <div class="tmpcoder-settings-page-header">
                <!-- Custom Template -->
                <div class="tmpcoder-preview-buttons">
                    <div class="tmpcoder-user-template">
                        <img src="<?php echo esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/create-template-icon.svg'); ?>">

                        <?php 

                        $active_tab_label = ucwords(str_replace('_', ' ', str_replace('type_', '', $active_tab)));

                        if ($active_tab == 'type_404') {
                            $active_tab_label = '404 Page';
                        }
                        if ($active_tab == 'type_global_template') {
                            $active_tab_label = str_replace('Template', '', $active_tab_label);
                        }

                        ?>

                        <span><?php echo esc_html(sprintf( 
                            /* translators: %s: template type */
                            __( 'Create %s Template', 'sastra-essential-addons-for-elementor' ), esc_html($active_tab_label))); ?></span>

                        <?php
                        if ( ! class_exists( 'WooCommerce' ) && isset($_GET['layout_type']) && ('type_product_archive' === $_GET['layout_type'] || 'type_single_product' === $_GET['layout_type'] || 'type_single_product' === $_GET['layout_type'] || 'type_product_category' === $_GET['layout_type'] )) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                            echo '<div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        </form>

    

    </div>

</div>


<?php

} // End tmpcoder_addons_theme_builder_page()

/**
 * Enqueue scripts and styles for theme builder page
 */
function tmpcoder_theme_builder_enqueue_scripts( $hook ) {
	// Check if we're on the spexo-welcome page with site-builder tab
	if ( false !== strpos( $hook, 'spexo-welcome' ) ) {
		// Only enqueue on site-builder tab
		if ( isset( $_GET['tab'] ) && 'site-builder' === sanitize_key( $_GET['tab'] ) ) {
			wp_enqueue_style( 'tmpcoder-plugin-import-demos', plugins_url( 'inc/admin/import/assets/css/tmpcoder-plugin-import-demos' . tmpcoder_script_suffix() . '.css', TMPCODER_PLUGIN_FILE ), [], TMPCODER_PLUGIN_VER, false );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'tmpcoder_theme_builder_enqueue_scripts' );