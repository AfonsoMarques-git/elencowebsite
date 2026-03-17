<?php

use TMPCODER\Classes\Pro_Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="wrap tmpcoder-settings-page-wrap">
    <div class="tmpcoder-settings-page">
        <form method="post" action="options.php">
            <?php

            // Active Tab
            $active_tab = isset($_GET['tab']) ? sanitize_text_field( wp_unslash( $_GET['tab'])) : '';// phpcs:ignore WordPress.Security.NonceVerification.Recommended
            ?>

            <?php if ( $active_tab == 'widgets' ) : ?>

            <?php

            // Settings
            settings_fields( 'tmpcoder-elements-settings' );
            do_settings_sections( 'tmpcoder-elements-settings' );

            ?>

            <div class="tmpcoder-section-info-wrap common-box-shadow">
                <div class="tmpcoder-section-info">
                    <h4><?php esc_html_e( 'Global Control of Widgets', 'sastra-essential-addons-for-elementor' ); ?></h4>
                    <p><?php esc_html_e( 'Certain widgets can be turned off to speed up the page.', 'sastra-essential-addons-for-elementor' ); ?></p>
                </div>
                <div class="tmpcoder-btn-group">
                    <input type="checkbox" name="tmpcoder-element-toggle-all" id="tmpcoder-element-toggle-all" <?php checked( get_option('tmpcoder-element-toggle-all', 'on'), 'on', true ); ?>>

                    <button type="button" class="tmpcoder-btn tmpcoder-btn-enable <?php echo ( get_option('tmpcoder-element-toggle-all') == 'on' ) ? 'active' : ''; ?>"><?php esc_html_e( 'Switch On', 'sastra-essential-addons-for-elementor' ); ?></button>
                    <button type="button" class="tmpcoder-btn tmpcoder-btn-disable <?php echo ( get_option('tmpcoder-element-toggle-all') != 'on' ) ? 'active' : ''; ?>"><?php esc_html_e( 'Switch off', 'sastra-essential-addons-for-elementor' ); ?></button>
                    <button type="button" class="tmpcoder-btn-unused"><?php esc_html_e( 'Disable Unused Widgets', 'sastra-essential-addons-for-elementor' ); ?></button>
                </div>
            </div>


            <div class="widget-box-main common-box-shadow">
                <div class="tmpcoder-elements-header">
                    <div class="search-filter-part">
                        <div class="row">
                            <div class="col-xl-9">
                                <div class="tmpcoder-elements-filters">
                                    <ul>
                                        <li data-filter="all" class="tmpcoder-active-filter"><?php esc_html_e( 'All Widgets', 'sastra-essential-addons-for-elementor' ); ?></li>
                                        <li data-filter="creative"><?php esc_html_e( 'Creative Widgets', 'sastra-essential-addons-for-elementor' ); ?></li>
                                        <li data-filter="theme"><?php esc_html_e( 'Theme Builder', 'sastra-essential-addons-for-elementor' ); ?></li>
                                        <li data-filter="woo"><?php esc_html_e( 'WooCommerce', 'sastra-essential-addons-for-elementor' ); ?></li>
                                        <li data-filter="extensions"><?php esc_html_e( 'Extensions', 'sastra-essential-addons-for-elementor' ); ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <div class="tmpcoder-widgets-search">
                                    <div class="tmpcoder-widgets-search-inner">
                                        <input class="tmpcoder-search-tracking" data-type="1" type="text" autocomplete="off" placeholder="<?php esc_html_e('Search Widgets...', 'sastra-essential-addons-for-elementor'); ?>">
                                        <span class="dashicons dashicons-search"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tmpcoder-elements-heading">
                    <h3><?php esc_html_e( 'Creative Widgets', 'sastra-essential-addons-for-elementor' ); ?></h3>
                </div>

                <div class="tmpcoder-elements tmpcoder-elements-general">
                    
                    <?php
                        $modules = tmpcoder_get_registered_modules();
                        $premium_modules = [
                            // ------ Array Value name ------
                            // 'widget name' => ['widget-slug', 'purchase pro url', 'docs link', 'tag', 'file name', 'widget class', 'widget icon'], 

                            // 'Advanced Breadcrumbs' => ['breadcrumbs-pro', TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-elements-breadcrumbs-widgets-upgrade-pro#purchasepro', '', 'pro', '', '', 'advance-breadcrumb.svg'],
                        ];

                        foreach ( array_merge($modules, $premium_modules) as $title => $data ) {
                            $slug = $data[0];
                            $icon = isset($data[6]) && !empty($data[6]) ? '<img class="widget-icon" src="'.esc_url(TMPCODER_ADDONS_ASSETS_URL.'widget-icons/'.$data[6]).'" />' : '';
                            $class = ('new' === $data[3]) ? 'tmpcoder-new-element' : '';
                            $default_value = 'on';
                            
                            $url = isset($data[1]) && !empty($data[1]) ? $data[1] : '';
                            $reff = '?ref=tmpcoder-plugin-backend-elements-widget-prev'. $data[2];
                            $live_demo_text = esc_html__('Live Demo', 'sastra-essential-addons-for-elementor');
                            $docs_url = isset($data[2]) && !empty($data[2]) ? $data[2] : '';
                            $docs_text = esc_html__('Docs', 'sastra-essential-addons-for-elementor');

                            if ( 'pro' === $data[3] && !tmpcoder_is_availble() ) {
                                $class = 'tmpcoder-pro-element';
                            }

                            if ( 'tmpcoder-pro-element' === $class) {
                                $default_value = 'off';
                                $live_demo_text = '';
                                $docs_text = '';
                                $reff = '';
                            }

                            if ( 'breadcrumbs-pro' == $data[0] && tmpcoder_is_availble() ) {
                                $url = '';
                                $docs_url = '';
                            }

                            if ($url == '' && $docs_url == '') {
                                $class .= ' no-demo-link';
                            }

                            if ( 'tmpcoder-pro-element' === $class && $docs_url == '')
                            {
                                $class .= ' no-demo-link';
                            }

                            echo '<div class="tmpcoder-element-box-inner">';
                                echo '<div class="tmpcoder-element '. esc_attr($class) .'">';
                                    echo '<a href="'. esc_url($url . $reff) .'" target="_blank"></a>';
                                    echo '<div class="wi-icon">'.wp_kses($icon, array(
                                        'img' => array(
                                            'class'=> array(),
                                            'src'=> array(),
                                        )
                                    )).'</div>';
                                    echo '<div class="tmpcoder-element-info">';
                                        echo '<div class="tmpcoder-element-content">';
                                            echo '<h3>'. esc_html($title) .'</h3>';
                                            
                                            if ($url !== '' || '' !== $docs_url) {
                                            ?>
                                            <ul>
                                                <?php
                                                if( '' !== $url && '' !== $live_demo_text ){
                                                    echo wp_kses('<li><a href="'. esc_url(TMPCODER_DEMO_IMPORT_API.$url . $reff) .'" target="_blank">'. esc_html($live_demo_text) .'</a></li>', 
                                                        array(
                                                            'li' => array(),
                                                            'a' => array(
                                                                'href' => array(),
                                                                'target' => array()
                                                            )
                                                        )
                                                    );
                                                }
                                                if( $docs_url !== '' &&  $docs_text !== '' ){
                                                    echo wp_kses(
                                                        '<li><a href="'. esc_url(TMPCODER_DOCUMENTATION_URL.$docs_url) .'" target="_blank">'. esc_html($docs_text) .'</a></li>', 
                                                        array(
                                                            'li' => array(),
                                                            'a' => array(
                                                                'href' => array(),
                                                                'target' => array()
                                                            )
                                                        )
                                                    );
                                                }
                                                ?>
                                            </ul>
                                            <?php
                                            }
                                        echo '</div>';
                                        echo '<div class="tmpcoder-element-lable">';
                                            echo '<input type="checkbox" name="tmpcoder-element-'. esc_attr($slug) .'" id="tmpcoder-element-'. esc_attr($slug) .'" '. checked( get_option('tmpcoder-element-'. $slug, $default_value), 'on', false ) .'>';
                                            echo '<label for="tmpcoder-element-'. esc_attr($slug) .'"></label>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        }
                    ?>

                </div>

                <div class="tmpcoder-elements-heading">
                    <h3><?php esc_html_e( 'Theme Builder Widgets', 'sastra-essential-addons-for-elementor' ); ?></h3>
                </div>
                <div class="tmpcoder-elements tmpcoder-elements-theme">
                <?php
                    $tmpcoder_get_theme_builder_modules = tmpcoder_get_theme_builder_modules();

                    // ------ Array Value name ------
                    // 'widget name' => ['widget-slug', 'purchase pro url', 'docs link', 'tag', 'file name', 'widget class', 'widget icon'],
                    $theme_builder_modules_pro = [
                        'Custom Field' => ['custom-field-pro', TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-elements-theme-builder-widgets-upgrade-pro#purchasepro', 'custom-field', 'pro', '', '', 'custom-field.svg'],
                        // 'Category Grid' => ['category-grid-pro', TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-elements-catgrid-widgets-upgrade-pro#purchasepro', 'category-grid', 'pro', '', '', 'woo-category-grid.svg'],
                    ];

                    foreach ( array_merge($tmpcoder_get_theme_builder_modules, $theme_builder_modules_pro) as $title => $data ) {
                        $slug = $data[0];
                        $slug = str_replace('-pro', '', $slug);
                        $icon = isset($data[6]) && !empty($data[6]) ? '<img class="widget-icon" src="'.esc_url(TMPCODER_ADDONS_ASSETS_URL.'widget-icons/'.$data[6]).'" />' : '';
                        $class = 'new' === $data[3] ? 'tmpcoder-new-element' : '';
                        $class .= ('pro' === $data[3] && !tmpcoder_is_availble()) ? ' tmpcoder-pro-element' : '';
                        $default_value = 'off';

                        $url = isset($data[1]) && !empty($data[1]) ? $data[1] : '';
                        $reff = '?ref=tmpcoder-plugin-backend-elements-widget-prev'. $data[2];
                        $live_demo_text = esc_html__('Live Demo', 'sastra-essential-addons-for-elementor');
                        $docs_url = isset($data[2]) && !empty($data[2]) ? $data[2] : '';
                        $docs_text = esc_html__('Docs', 'sastra-essential-addons-for-elementor');


                        if ( 'pro' === $data[3] && !tmpcoder_is_availble() ) {
                            $class = 'tmpcoder-pro-element';
                        } 

                        if ( 'tmpcoder-pro-element' === $class ) {
                            $default_value = 'off';
                            $reff = '';
                        }

                        echo '<div class="tmpcoder-element-box-inner">';
                            echo '<div class="tmpcoder-element '. esc_attr($class) .'">';
                                echo '<div class="wi-icon">'.wp_kses($icon, array(
                                    'img' => array(
                                        'src' => array(),
                                        'class' => array(),
                                    )
                                )).'</div>';
                                echo '<a href="'. esc_url($url . $reff) .'" target="_blank"></a>';
                                echo '<div class="tmpcoder-element-info">';
                                    echo '<div class="tmpcoder-element-content">';
                                        echo '<h3>'. esc_html($title) .'</h3>';

                                        if ($url !== '' || '' !== $docs_url) {
                                            ?>
                                            <ul>
                                                <?php
                                                /* if( '' !== $url && '' !== $live_demo_text ){
                                                    echo wp_kses('<li><a href="'. esc_url(TMPCODER_DEMO_IMPORT_API.$url . $reff) .'" target="_blank">'. esc_html($live_demo_text) .'</a></li>', 
                                                        array(
                                                            'li' => array(),
                                                            'a' => array(
                                                                'href' => array(),
                                                                'target' => array()
                                                            )
                                                        )
                                                    );
                                                } */
                                                if( $docs_url !== '' &&  $docs_text !== '' ){
                                                    echo wp_kses(
                                                        '<li><a href="'. esc_url(TMPCODER_DOCUMENTATION_URL.$docs_url) .'" target="_blank">'. esc_html($docs_text) .'</a></li>', 
                                                        array(
                                                            'li' => array(),
                                                            'a' => array(
                                                                'href' => array(),
                                                                'target' => array()
                                                            )
                                                        )
                                                    );
                                                }
                                                ?>
                                            </ul>
                                            <?php
                                        }

                                    echo '</div>';
                                    echo '<div class="tmpcoder-element-lable">';
                                        echo '<input type="checkbox" name="tmpcoder-element-'. esc_attr($slug) .'" id="tmpcoder-element-'. esc_attr($slug) .'" '. checked( get_option('tmpcoder-element-'. $slug, $default_value), 'on', false ) .'>';
                                        echo '<label for="tmpcoder-element-'. esc_attr($slug) .'"></label>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                ?>
                </div>

                <div class="tmpcoder-elements-heading">
                    <h3><?php esc_html_e( 'WooCommerce Builder Widgets', 'sastra-essential-addons-for-elementor' ); ?></h3>
                    <?php if (!class_exists('WooCommerce')) : ?>
                        <p class='tmpcoder-install-activate-woocommerce'><span class="dashicons dashicons-info-outline"></span> <?php esc_html_e( 'Install/Activate WooCommerce to use these widgets', 'sastra-essential-addons-for-elementor' ); ?></p>
                    <?php endif; ?>
                </div>
                <div class="tmpcoder-elements tmpcoder-elements-woo">
                    
                <?php
                
                // ------ Array Value name ------
                // 'widget name' => ['widget-slug', 'purchase pro url', 'docs link', 'tag', 'file name', 'widget class', 'widget icon'], 

                $woocommerce_builder_modules = tmpcoder_get_woocommerce_builder_modules();
                $premium_woo_modules = [
                    'Product Filters' => ['product-filters-pro', TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-elements-woo-prodfilter-widgets-upgrade-pro#purchasepro', 'product-filters', 'pro', '', '', 'product-filters.svg'],
                    'Product Breadcrumbs' => ['product-breadcrumbs-pro', TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-elements-woo-breadcru-widgets-upgrade-pro#purchasepro', 'product-breadcrumb', 'pro', '', '', 'product-breadcrumbs.svg'],
                    'My Account Page' => ['my-account-page-pro', TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-elements-woo-myacc-widgets-upgrade-pro#purchasepro', 'my-account-page', 'pro', '', '', 'page-my-account.svg'],
                    'Category Grid' => ['category-grid-pro', TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-elements-catgrid-widgets-upgrade-pro#purchasepro', 'category-grid', 'pro', '', '', 'woo-category-grid.svg'],
                    'Woo Category Grid' => ['woo-category-grid-pro', TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-elements-woo-catgrid-widgets-upgrade-pro#purchasepro', 'woo-category-grid', 'pro', '', '', 'woo-category-grid.svg'],
                    'Wishlist Button' => ['wishlist-button-pro', TMPCODER_PURCHASE_PRO_URL.'#purchasepro?ref=tmpcoder-plugin-backend-elements-woo-wishlist-btn-widgets-upgrade-expert#purchasepro', 'wishlist-button', 'pro', '', '', 'wishlist-button.svg'],
                    'Mini Wishlist' => ['mini-wishlist-pro', TMPCODER_PURCHASE_PRO_URL.'#purchasepro?ref=tmpcoder-plugin-backend-elements-woo-wishlist-mini-widgets-upgrade-expert#purchasepro', 'mini-wishlist', 'pro', '', '', 'mini-wishlist.svg'],
                    'Wishlist Table' => ['wishlist-pro', TMPCODER_PURCHASE_PRO_URL.'#purchasepro?ref=tmpcoder-plugin-backend-elements-woo-wishlist-widgets-upgrade-expert#purchasepro', 'wishlist-table', 'pro', '', '', 'wishlist-table.svg'],
                    'Compare Button' => ['compare-button-pro', TMPCODER_PURCHASE_PRO_URL.'#purchasepro?ref=tmpcoder-plugin-backend-elements-woo-compare-btn-widgets-upgrade-expert#purchasepro', 'compare-button', 'pro', '', '', 'compare-button.svg'],
                    'Mini Compare' => ['mini-compare-pro', TMPCODER_PURCHASE_PRO_URL.'#purchasepro?ref=tmpcoder-plugin-backend-elements-woo-compare-mini-widgets-upgrade-expert#purchasepro', 'mini-compare', 'pro', '', '', 'mini-compare.svg'],
                    'Compare Table' => ['compare-pro', TMPCODER_PURCHASE_PRO_URL.'#purchasepro?ref=tmpcoder-plugin-backend-elements-woo-compare-widgets-upgrade-expert#purchasepro', 'compare-table', 'pro', '', '', 'compare-table.svg'],
                    'Product Notice' => ['product-notice-pro', TMPCODER_PURCHASE_PRO_URL.'#purchasepro?ref=tmpcoder-plugin-backend-elements-woo-notice-widgets-upgrade-expert#purchasepro', 'product-notice', 'pro', '', '', 'product-notice.svg'],
                    'Cart Page' => ['page-cart', TMPCODER_PURCHASE_PRO_URL.'#purchasepro?ref=tmpcoder-plugin-backend-elements-woo-notice-widgets-upgrade-expert#purchasepro', 'cart-page', 'pro', '', '', 'cart-page.svg'],
                    'Checkout Page' => ['page-checkout', TMPCODER_PURCHASE_PRO_URL.'#purchasepro?ref=tmpcoder-plugin-backend-elements-woo-notice-widgets-upgrade-expert#purchasepro', 'checkout-page', 'pro', '', '', 'checkout-page.svg'],
                ];

                foreach ( array_merge($woocommerce_builder_modules, $premium_woo_modules) as $title => $data ) {
                    $slug = $data[0];
                    $slug = str_replace('-pro', '', $slug);
                    $icon = isset($data[6]) && !empty($data[6]) ? '<img class="widget-icon" src="'.esc_url(TMPCODER_ADDONS_ASSETS_URL.'widget-icons/'.$data[6]).'" />' : '';
                    $class = 'new' === $data[3] ? 'tmpcoder-new-element' : '';
                    $class .= ('pro' === $data[3] && !tmpcoder_is_availble()) ? ' tmpcoder-pro-element' : '';
                    $default_value = class_exists( 'WooCommerce' ) ? 'on' : 'off';

                    $url = isset($data[1]) && !empty($data[1]) ? $data[1] : '';
                    $reff = '?ref=tmpcoder-plugin-backend-elements-widget-prev'. $data[2];
                    $live_demo_text = esc_html__('Live Demo', 'sastra-essential-addons-for-elementor');
                    $docs_url = isset($data[2]) && !empty($data[2]) ? $data[2] : '';
                    $docs_text = esc_html__('Docs', 'sastra-essential-addons-for-elementor');

                    if ( 'pro' === $data[3] && !tmpcoder_is_availble() ) {
                        $class = 'tmpcoder-pro-element';
                    } 

                    if ( 'tmpcoder-pro-element' === $class ) {
                        $default_value = 'off';
                        $reff = '';
                    }

                    echo '<div class="tmpcoder-element-box-inner">';
                        echo '<div class="tmpcoder-element '. esc_attr($class) .'">';
                            echo '<div class="wi-icon">'.wp_kses($icon, array(
                                'img' => array(
                                    'src' => array(),
                                    'class' => array(),
                                )
                            )).'</div>';
                            echo '<a href="'. esc_url($url . $reff) .'" target="_blank"></a>';
                            echo '<div class="tmpcoder-element-info">';
                                echo '<div class="tmpcoder-element-content">';
                                    echo '<h3>'. esc_html($title) .'</h3>';

                                    if ($url !== '' || '' !== $docs_url) {
                                        ?>
                                        <ul>
                                            <?php
                                            /* if( '' !== $url && '' !== $live_demo_text ){
                                                echo wp_kses('<li><a href="'. esc_url(TMPCODER_DEMO_IMPORT_API.$url . $reff) .'" target="_blank">'. esc_html($live_demo_text) .'</a></li>', 
                                                    array(
                                                        'li' => array(),
                                                        'a' => array(
                                                            'href' => array(),
                                                            'target' => array()
                                                        )
                                                    )
                                                );
                                            } */
                                            if( $docs_url !== '' &&  $docs_text !== '' ){
                                                echo wp_kses(
                                                    '<li><a href="'. esc_url(TMPCODER_DOCUMENTATION_URL.$docs_url) .'" target="_blank">'. esc_html($docs_text) .'</a></li>', 
                                                    array(
                                                        'li' => array(),
                                                        'a' => array(
                                                            'href' => array(),
                                                            'target' => array()
                                                        )
                                                    )
                                                );
                                            }
                                            ?>
                                        </ul>
                                        <?php
                                    }

                                echo '</div>';
                                echo '<div class="tmpcoder-element-lable">';
                                    echo '<input type="checkbox" name="tmpcoder-element-'. esc_attr($slug) .'" id="tmpcoder-element-'. esc_attr($slug) .'" '. checked( get_option('tmpcoder-element-'. $slug, $default_value), 'on', false ) .'>';
                                    echo '<label for="tmpcoder-element-'. esc_attr($slug) .'"></label>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
                ?>
                </div>

                <div class="tmpcoder-elements-heading">
                    <h3><?php esc_html_e( 'Extensions', 'sastra-essential-addons-for-elementor' ); ?></h3>
                </div>
                <div class="tmpcoder-elements tmpcoder-elements-extensions">
                <?php

                    $tmpcoder_get_extension_modules = tmpcoder_get_extension_modules();

                    if ( tmpcoder_is_availble() ) {
                        unset($tmpcoder_get_extension_modules['Advanced Sticky Section (Pro)']);
                    }

                    foreach ( $tmpcoder_get_extension_modules as $title => $data ) {
                        $icon = isset($data[1]) && !empty($data[1]) ? '<img class="widget-icon" src="'.esc_url(TMPCODER_ADDONS_ASSETS_URL.'widget-icons/'.$data[1]).'" />' : '';
                        $option_name = $data[0];
                        $class_pro = isset($data[2]) && $data[2] == 'pro' ? ' tmpcoder-pro-element' : '';
                        $option_title = ucwords( preg_replace( '/-/i', ' ', preg_replace('/tmpcoder-||-toggle/i', '', $option_name ) ));

                        $docs_url = isset($data[3]) && !empty($data[3]) ? $data[3] : '';
                        $docs_text = esc_html__('Docs', 'sastra-essential-addons-for-elementor');
                        
                        if ( tmpcoder_is_availble() && $option_name == 'tmpcoder-scroll-effects-pro' ) {
                            $option_title = str_replace('Pro', '', $option_title);
                            $class_pro = '';
                        }

                        echo '<div class="tmpcoder-element-box-inner">';
                            echo '<div class="tmpcoder-element'.esc_attr($class_pro).'">';
                                echo '<div class="wi-icon">'.wp_kses($icon, array(
                                    'img' => array(
                                        'src' => array(),
                                        'class' => array(),
                                    )
                                )).'</div>';
                                echo '<div class="tmpcoder-element-info">';
                                    echo '<div class="tmpcoder-element-content">';
                                        echo '<h3>'. esc_html($option_title) .'</h3>';

                                        if ( 'tmpcoder-particles' === $option_name ) {
                                            echo wp_kses('<br><span>' . __('Tip: Edit any Section > Navigate to Style tab', 'sastra-essential-addons-for-elementor').'</span>',
                                                array(
                                                    'br' => array(),
                                                    'span' => array(),
                                                )
                                            );
                                        } elseif ( 'tmpcoder-parallax-background' === $option_name ) {
                                            echo wp_kses('<br><span>' . __('Tip: Edit any Section > Navigate to Style tab', 'sastra-essential-addons-for-elementor').'</span>',
                                                array(
                                                    'br' => array(),
                                                    'span' => array(),
                                                )
                                            );
                                        } elseif ( 'tmpcoder-parallax-multi-layer' === $option_name ) {
                                            echo wp_kses('<br><span>' . __('Tip: Edit any Section > Navigate to Style tab', 'sastra-essential-addons-for-elementor').'</span>',
                                                array(
                                                    'br' => array(),
                                                    'span' => array(),
                                                )
                                            );
                                        } elseif ( 'tmpcoder-sticky-section' === $option_name ) {
                                            echo wp_kses('<br><span>' . __('Tip: Edit any Section > Navigate to Advanced tab', 'sastra-essential-addons-for-elementor').'</span>',
                                                array(
                                                    'br' => array(),
                                                    'span' => array(),
                                                )
                                            );
                                        }
                                        elseif ( 'tmpcoder-floating-effects' === $option_name ) {
                                            echo wp_kses('<br><span>' . __('Tip: Edit any Widget > Navigate to Advanced tab', 'sastra-essential-addons-for-elementor').'</span>',
                                                array(
                                                    'br' => array(),
                                                    'span' => array(),
                                                )
                                            );
                                        }elseif ( 'tmpcoder-scroll-effects-pro' === $option_name ) {
                                            echo wp_kses('<br><span>' . __('Tip: Edit any Widget > Navigate to Advanced tab', 'sastra-essential-addons-for-elementor').'</span>',
                                                array(
                                                    'br' => array(),
                                                    'span' => array(),
                                                )
                                            );
                                            if ( !tmpcoder_is_availble() ) {
                                                echo '<a class="tmpcoder-inline-link" href="'.esc_url(TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-elements-scroll-effects-pro#purchasepro').'" target="_blank"></a>';
                                            }
                                        }
                                        elseif ( 'tmpcoder-advanced-sticky-section-pro' === $option_name ) {
                                            echo wp_kses('<br><span>' . __('Tip: Edit any Section > Navigate to Advanced tab', 'sastra-essential-addons-for-elementor').'</span>',
                                                array(
                                                    'br' => array(),
                                                    'span' => array(),
                                                )
                                            );
                                            if ( !tmpcoder_is_availble() ) {
                                                echo '<a class="tmpcoder-inline-link" href="'.esc_url(TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-elements-advanced-stiky-pro#purchasepro').'" target="_blank"></a>';
                                            }
                                        } elseif ( 'tmpcoder-custom-css' === $option_name ) {
                                            echo wp_kses('<br><span>' . __('Tip: Edit any Section > Navigate to Advanced tab', 'sastra-essential-addons-for-elementor').'</span>',
                                                array(
                                                    'br' => array(),
                                                    'span' => array(),
                                                )
                                            );
                                        }

                                        if ($url !== '' || '' !== $docs_url) {
                                            ?>
                                            <ul>
                                                <?php
                                               
                                                if( $docs_url !== '' &&  $docs_text !== '' ){
                                                    echo wp_kses(
                                                        '<li><a href="'. esc_url(TMPCODER_DOCUMENTATION_URL.$docs_url) .'" target="_blank">'. esc_html($docs_text) .'</a></li>', 
                                                        array(
                                                            'li' => array(),
                                                            'a' => array(
                                                                'href' => array(),
                                                                'target' => array()
                                                            )
                                                        )
                                                    );
                                                }
                                                ?>
                                            </ul>
                                            <?php
                                        }

                                    echo '</div>';
                                    echo '<div class="tmpcoder-element-lable">';
                                        echo '<input type="checkbox" name="'. esc_attr($option_name) .'" id="'. esc_attr($option_name) .'" '. checked( get_option(''. $option_name .'', 'on'), 'on', false ) .'>';

                                        echo '<label for="'. esc_attr($option_name) .'"></label>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                ?>
                </div>

                <div class="tmpcoder-widgets-not-found">
                    <h1><?php esc_html_e('No Widget Found.', 'sastra-essential-addons-for-elementor'); ?></h1>
                    <p><?php esc_html_e('Not available the widget you\'re searching for?', 'sastra-essential-addons-for-elementor'); ?></p>
                </div>
            </div>

            <?php 
            endif; ?>
            <div class="welcome-backend-loader">
                <img src="<?php echo esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/backend-loader.gif'); ?>" alt="" width="80" height="80" />
            </div>
            <div class="tmpcoder-settings-saved">
                <span><?php esc_html_e('Settings Saved', 'sastra-essential-addons-for-elementor'); ?></span>
                <span class="dashicons dashicons-smiley"></span>
            </div>
        </form>
    </div>
</div>
    
