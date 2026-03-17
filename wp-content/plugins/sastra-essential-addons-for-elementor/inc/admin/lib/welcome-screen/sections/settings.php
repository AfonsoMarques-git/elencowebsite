<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$is_pro     = tmpcoder_is_availble();
$ai_manager = class_exists( '\Spexo_Addons\AI\Spexo_AI_Manager' ) ? \Spexo_Addons\AI\Spexo_AI_Manager::get_instance() : null;

$settings_tabs = [
    [
        'id'          => 'woocommerce',
        'icon'        => 'dashicons-cart',
        'label'       => esc_html__( 'WooCommerce', 'sastra-essential-addons-for-elementor' ),
        'description' => esc_html__( 'Control shop pagination, gallery thumbnails, and template overrides.', 'sastra-essential-addons-for-elementor' ),
        'sections'    => [],
    ],
];

$ai_sections = [
    'openai'      => '',
    'editor'      => '',
    'alt'         => '',
    'translation' => '',
    'quota'       => '',
    'stats'       => '',
];

if ( $ai_manager ) {
    $settings_tabs[] = [
        'id'          => 'ai-settings',
        'icon'        => 'dashicons-star-filled',
        'label'       => esc_html__( 'AI Settings', 'sastra-essential-addons-for-elementor' ),
        'description' => esc_html__( 'AI-powered features and tools.', 'sastra-essential-addons-for-elementor' ),
        'sections'    => [
            [ 'target' => 'ai-openai', 'label' => esc_html__( 'Configuration', 'sastra-essential-addons-for-elementor' ), 'icon' => 'dashicons-admin-settings' ],
            [ 'target' => 'ai-editor-tools', 'label' => esc_html__( 'Tools & Workflow', 'sastra-essential-addons-for-elementor' ), 'icon' => 'dashicons-admin-tools' ],
            [ 'target' => 'ai-usage-quota', 'label' => esc_html__( 'Usage & Limits', 'sastra-essential-addons-for-elementor' ), 'icon' => 'dashicons-chart-area' ],
        ],
    ];

    $ai_sections['openai'] = $ai_manager->get_ai_settings_box_html([
        'wrap'          => false,
        'include_nonce' => true,
        'sections'      => [ 'spexo_ai_openai_section' ],
    ]);

    $ai_sections['editor'] = $ai_manager->get_ai_settings_box_html([
        'wrap'     => false,
        'sections' => [ 'spexo_ai_editor_section' ],
    ]);

    $ai_sections['alt'] = $ai_manager->get_ai_settings_box_html([
        'wrap'     => false,
        'sections' => [ 'spexo_ai_alt_text_section' ],
    ]);

    $ai_sections['translation'] = $ai_manager->get_ai_settings_box_html([
        'wrap'     => false,
        'sections' => [ 'spexo_ai_translation_section' ],
    ]);

    $ai_sections['quota'] = $ai_manager->get_ai_settings_box_html([
        'wrap'     => false,
        'sections' => [ 'spexo_ai_quota_section' ],
    ]);

    $ai_sections['stats'] = $ai_manager->get_ai_settings_box_html([
        'wrap'     => false,
        'sections' => [ 'spexo_ai_stats_section' ],
    ]);
}

// Add Media & Post Types as separate top-level item
$settings_tabs[] = [
    'id'          => 'media-post-types',
    'icon'        => 'dashicons-format-video',
    'label'       => esc_html__( 'Media & Post Types', 'sastra-essential-addons-for-elementor' ),
    'description' => esc_html__( 'Configure featured video behaviour and secondary featured images.', 'sastra-essential-addons-for-elementor' ),
    'sections'    => [],
];

// Add Smooth Scrolling as separate top-level item
$settings_tabs[] = [
    'id'          => 'smooth-scroll',
    'icon'        => 'dashicons-arrow-down-alt',
    'label'       => esc_html__( 'Smooth Scrolling', 'sastra-essential-addons-for-elementor' ),
    'description' => esc_html__( 'Makes scrolling feel smooth and responsive across the site.', 'sastra-essential-addons-for-elementor' ),
    'sections'    => [],
];

// Add Integration as separate top-level item
$settings_tabs[] = [
    'id'          => 'integration',
    'icon'        => 'dashicons-admin-generic',
    'label'       => esc_html__( 'Integration', 'sastra-essential-addons-for-elementor' ),
    'description' => esc_html__( 'Connect your MailChimp account to synchronize subscribers.', 'sastra-essential-addons-for-elementor' ),
    'sections'    => [],
];

$default_tab  = $settings_tabs[0];
$upgrade_link = TMPCODER_PURCHASE_PRO_URL . '?ref=tmpcoder-plugin-backend-settings-woo-pro#purchasepro';
?>

<div class="spexo-settings-page">

    <form method="post" action="options.php" class="spexo-settings-form" data-spexo-ai-form="true" autocomplete="off">
        <?php 
            settings_fields( 'tmpcoder-settings' );
            do_settings_sections( 'tmpcoder-settings' );
        ?>

        <div class="spexo-settings-shell">
            <aside class="spexo-settings-sidebar">
                <ul class="spexo-settings-nav" aria-label="<?php esc_attr_e( 'Settings sections', 'sastra-essential-addons-for-elementor' ); ?>">
                    <?php foreach ( $settings_tabs as $index => $tab ) : ?>
                        <li class="spexo-settings-nav-item<?php echo esc_attr( 0 === $index ? ' spexo-default-active' : '' ); ?><?php echo esc_attr( ! empty( $tab['sections'] ) ? ' has-children' : '' ); ?>"
                             data-panel="<?php echo esc_attr( $tab['id'] ); ?>"
                             data-panel-title="<?php echo esc_attr( $tab['label'] ); ?>"
                             data-panel-description="<?php echo esc_attr( $tab['description'] ); ?>">
                            <a href="javascript:void(0);" class="spexo-settings-nav-toggle">
                                <span class="dashicons <?php echo esc_attr( $tab['icon'] ); ?>" aria-hidden="true"></span>
                                <span><?php echo esc_html( $tab['label'] ); ?></span>
                            </a>
                            <?php if ( ! empty( $tab['sections'] ) ) : ?>
                                <ul class="spexo-settings-nav-children">
                                    <?php foreach ( $tab['sections'] as $child ) : ?>
                                        <li>
                                            <a href="javascript:void(0);" class="spexo-settings-sub-link" data-target="<?php echo esc_attr( $child['target'] ); ?>">
                                                <?php if ( ! empty( $child['icon'] ) ) : ?>
                                                    <span class="dashicons <?php echo esc_attr( $child['icon'] ); ?>" aria-hidden="true"></span>
                                                <?php endif; ?>
                                                <span><?php echo esc_html( $child['label'] ); ?></span>
                                                </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <div class="spexo-settings-content">

                <?php settings_errors( 'tmpcoder-settings' ); ?>

                <div class="spexo-settings-header">
                    <h2 class="spexo-settings-header__title" data-spexo-active-title><?php echo esc_html( $default_tab['label'] ); ?></h2>
                    <div class="spexo-settings-header__actions">
                    
                        <button type="button" class="button button-secondary spexo-reset-section"><?php esc_html_e( 'Reset Section', 'sastra-essential-addons-for-elementor' ); ?></button>
                        
                        <?php submit_button( esc_html__( 'Save Settings', 'sastra-essential-addons-for-elementor' ), 'button button-primary spexo-settings-save', '', false ); ?>
                    </div>
                </div>
                                        
                <div class="spexo-settings-alert" role="status" aria-live="polite">
                    <span><?php esc_html_e( 'Settings have changed, you should save them!', 'sastra-essential-addons-for-elementor' ); ?></span>
                </div>

                <div class="spexo-settings-panels">
                    <div class="spexo-settings-panel spexo-default-active" data-panel="smooth-scroll">
                        <section class="spexo-settings-section-card spexo-subpanel spexo-default-active" id="smooth-scroll-section" data-subpanel="smooth-scroll-section">
                            <div class="spexo-settings-section-card__body">
                                <?php
                                // Check if Pro Addon hook is available (Pro plugin is active)
                                if ( has_action( 'tmpcoder_smooth_scroll_options' ) ) {
                                    do_action( 'tmpcoder_smooth_scroll_options' );
                                } else {
                                    // Show locked content only from Free Addon
                                    ?>

                                    <div class="spexo-ai-settings-field-content">
                                        <div class="spexo-woo-config-toggle-wrapper">
                                            <div class="spexo-woo-config-toggle-label">
                                                <strong class="spexo-woo-config-toggle-title"><?php esc_html_e( 'Smooth Scroll', 'sastra-essential-addons-for-elementor' ); ?></strong>
                                                <span class="spexo-woo-config-toggle-description"><?php esc_html_e( 'Upgrade to unlock smooth scrolling controls.', 'sastra-essential-addons-for-elementor' ); ?></span>
                                            </div>
                                            <div class="spexo-checkbox-toggle">
                                                <input type="checkbox" id="smooth-scroll" disabled="disabled">
                                                <label for="smooth-scroll"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </section>
                    </div>

                    <div class="spexo-settings-panel" data-panel="woocommerce">
                        <div class="spexo-woocommerce-sections-wrapper">
                        <section class="spexo-settings-section-card spexo-subpanel spexo-default-active" id="woo-page-config" data-subpanel="woo-page-config" style="display: block;">
                            <div class="sub-header">
                                <div>
                                    <h3><?php esc_html_e( 'Page Configuration', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                    <p><?php esc_html_e( 'Control the number of products shown across WooCommerce archives.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                </div>
                            </div>
                            <div class="spexo-settings-section-card__body">
                                <div class="spexo-woo-page-config-grid">
                                    <div class="form-group-wrapper">
                                        <?php
                                        $woo_page_fields = [
                                            [ 'id' => 'tmpcoder_woo_shop_ppp', 'label' => esc_html__( 'Shop Page', 'sastra-essential-addons-for-elementor' ), 'icon' => 'shopping-bag' ],
                                            [ 'id' => 'tmpcoder_woo_shop_cat_ppp', 'label' => esc_html__( 'Category', 'sastra-essential-addons-for-elementor' ), 'icon' => 'grid' ],
                                            [ 'id' => 'tmpcoder_woo_shop_tag_ppp', 'label' => esc_html__( 'Tags', 'sastra-essential-addons-for-elementor' ), 'icon' => 'tag' ],
                                        ];
                                        foreach ( $woo_page_fields as $field ) :
                                            $value = tmpcoder_get_settings( $field['id'], 12 );
                                            ?>
                                                <div class="form-group">
                                                        <div class="spexo-woo-page-config-field">
                                                        <label class="spexo-woo-page-config-label" for="<?php echo esc_attr( $field['id'] ); ?>">
                                                            <?php if ( 'shopping-bag' === $field['icon'] ) : ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag text-gray-400" aria-hidden="true"><path d="M16 10a4 4 0 0 1-8 0"></path><path d="M3.103 6.034h17.794"></path><path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"></path></svg>
                                                            <?php elseif ( 'grid' === $field['icon'] ) : ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-grid text-gray-400" aria-hidden="true"><rect width="7" height="7" x="3" y="3" rx="1"></rect><rect width="7" height="7" x="14" y="3" rx="1"></rect><rect width="7" height="7" x="14" y="14" rx="1"></rect><rect width="7" height="7" x="3" y="14" rx="1"></rect></svg>
                                                            <?php elseif ( 'tag' === $field['icon'] ) : ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag text-gray-400" aria-hidden="true"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"></path><circle cx="7.5" cy="7.5" r=".5" fill="#9ca3af"></circle></svg>
                                                            <?php endif; ?>
                                                            <?php echo esc_html( $field['label'] ); ?>
                                                        </label>
                                                        <div class="spexo-woo-page-config-input-wrapper">
                                                            <input type="number" min="1" step="1" class="spexo-woo-page-config-input form-control-number-label form-control" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php disabled( ! $is_pro ); ?> data-default="<?php echo esc_attr( 12 ); ?>">
                                                            <span class="spexo-woo-page-config-suffix">items</span>
                                                        </div>
                                                        <?php if ( ! $is_pro ) : ?>
                                                            <p class="spexo-woo-page-config-pro-note">
                                                                <span><?php esc_html_e( 'Available in Pro', 'sastra-essential-addons-for-elementor' ); ?></span>
                                                                <a href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade', 'sastra-essential-addons-for-elementor' ); ?></a>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="spexo-settings-section-card spexo-subpanel spexo-default-active" id="woo-images" data-subpanel="woo-images" style="display: block;">
                            <div class="sub-header">
                                <div>
                                    <h3><?php esc_html_e( 'Product Images', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                </div>
                            </div>
                            <div class="spexo-settings-section-card__body">
                                <?php if ( $is_pro ) : ?>
                                    <div class="spexo-woo-images-content">
                                        <div class="form-group-wrapper pb-16">
                                            <div class="form-group">
                                                <div class="spexo-woo-page-config-field">
                                                    <label class="spexo-woo-images-label" for="tmpcoder_woo_gallery_thumbnail_size"><?php esc_html_e( 'Product Gallery Thumbnail Size', 'sastra-essential-addons-for-elementor' ); ?></label>
                                                    <select name="tmpcoder_woo_gallery_thumbnail_size" id="tmpcoder_woo_gallery_thumbnail_size" class="spexo-woo-images-select form-control">
                                                    <?php
                                                        $current_size = tmpcoder_get_settings( 'tmpcoder_woo_gallery_thumbnail_size', 'default' );
                                                        printf( '<option value="default" %1$s>%2$s</option>', selected( $current_size, 'default', false ), esc_html__( 'Default (WooCommerce)', 'sastra-essential-addons-for-elementor' ) );

                                                        global $_wp_additional_image_sizes;
                                                        $standard_sizes = [
                                                            'thumbnail'     => esc_html__( 'Thumbnail', 'sastra-essential-addons-for-elementor' ),
                                                            'medium'        => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
                                                            'medium_large'  => esc_html__( 'Medium Large', 'sastra-essential-addons-for-elementor' ),
                                                            'large'         => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
                                                        ];

                                                        foreach ( $standard_sizes as $size_key => $size_label ) {
                                                            $width  = absint( get_option( "{$size_key}_size_w", 0 ) );
                                                            $height = absint( get_option( "{$size_key}_size_h", 0 ) );
                                                            if ( $width > 0 && $height > 0 ) {
                                                                echo '<option value="' . esc_attr( $size_key ) . '" ' . selected( $current_size, $size_key, false ) . '>' . esc_html( $size_label ) . ' (' . esc_html( (string) $width ) . '×' . esc_html( (string) $height ) . ')</option>';
                                                            }
                                                        }

                                                        if ( function_exists( 'wc_get_image_size' ) ) {
                                                            $woo_sizes = [
                                                                'woocommerce_thumbnail'        => esc_html__( 'WooCommerce Thumbnail', 'sastra-essential-addons-for-elementor' ),
                                                                'woocommerce_single'           => esc_html__( 'WooCommerce Single', 'sastra-essential-addons-for-elementor' ),
                                                                'woocommerce_gallery_thumbnail'=> esc_html__( 'WooCommerce Gallery', 'sastra-essential-addons-for-elementor' ),
                                                            ];

                                                            foreach ( $woo_sizes as $size_key => $size_label ) {
                                                                if ( isset( $_wp_additional_image_sizes[ $size_key ] ) ) {
                                                                    $width  = absint( $_wp_additional_image_sizes[ $size_key ]['width'] );
                                                                    $height = absint( $_wp_additional_image_sizes[ $size_key ]['height'] );
                                                                    echo '<option value="' . esc_attr( $size_key ) . '" ' . selected( $current_size, $size_key, false ) . '>' . esc_html( $size_label ) . ' (' . esc_html( (string) $width ) . '×' . esc_html( (string) $height ) . ')</option>';
                                                                }
                                                            }
                                                        }

                                                        printf( '<option value="full" %1$s>%2$s</option>', selected( $current_size, 'full', false ), esc_html__( 'Full Size (Original)', 'sastra-essential-addons-for-elementor' ) );
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="spexo-woo-images-warning">
                                            <div class="spexo-woo-images-warning-icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" fill="#fbbf24"/>
                                                </svg>
                                            </div>
                                            <div class="spexo-woo-images-warning-content">
                                                <h3 class="spexo-woo-images-warning-title"><?php esc_html_e( 'Regeneration Required', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                                <p class="spexo-woo-images-warning-text"><?php esc_html_e( 'After changing this setting, you may need to regenerate product images using a plugin like "Regenerate Thumbnails."', 'sastra-essential-addons-for-elementor' ); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="spexo-pro-locked">
                                        <div class="spexo-locked-text">
                                            <strong><?php esc_html_e( 'Pro Feature', 'sastra-essential-addons-for-elementor' ); ?></strong>
                                            <p><?php esc_html_e( 'Select custom gallery thumbnail sizes with Pro.', 'sastra-essential-addons-for-elementor' ); ?><br/>
                                            <span class="spexo-woo-page-config-pro-note"><?php esc_html_e( 'The available thumbnail sizes : Thumbnail (150×150), Medium (300×300), Large (1024×1024), Full Size (Original).', 'sastra-essential-addons-for-elementor' ); ?></span>
                                        </p>
                                            
                                        </div>
                                        <a class="spexo-upgrade-link" href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade to Pro', 'sastra-essential-addons-for-elementor' ); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>

                        <section class="spexo-settings-section-card spexo-subpanel spexo-default-active" id="woo-config" data-subpanel="woo-config" style="display: block;">
                            <div class="sub-header">
                                <div>
                                    <h3><?php esc_html_e( 'WooCommerce Config', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                </div>
                            </div>
                            <div class="spexo-settings-section-card__body">
                                <div class="spexo-woo-config-content">
                                    <?php
                                    // PRO-only section - content loaded from PRO plugin via hook
                                    if ( $is_pro && has_action( 'tmpcoder_woocommerce_config_settings' ) ) {
                                        do_action( 'tmpcoder_woocommerce_config_settings' );
                                    } else {
                                        // Show locked content for free users
                                        ?>
                                        <div class="spexo-pro-locked">
                                            <div class="spexo-locked-text">
                                                <strong><?php esc_html_e( 'Pro Feature', 'sastra-essential-addons-for-elementor' ); ?></strong>
                                                <p><?php esc_html_e( 'Unlock WooCommerce template overrides with Pro.', 'sastra-essential-addons-for-elementor' ); ?><br/>
                                            <span class="spexo-woo-page-config-pro-note"><?php esc_html_e( 'Templates like Cart Template, Mini Cart Drawer, Custom Notices & Add Wishlist To My Account Option etc.', 'sastra-essential-addons-for-elementor' ); ?></span>
                                                
                                            </div>
                                            <a class="spexo-upgrade-link" href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade to Pro', 'sastra-essential-addons-for-elementor' ); ?></a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </section>

                        <section class="spexo-settings-section-card spexo-subpanel spexo-default-active" id="woo-pages" data-subpanel="woo-pages" style="display: block;">
                            <div class="sub-header">
                                <div>
                                    <h3><?php esc_html_e( 'Page Selection', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                </div>
                            </div>
                            <div class="spexo-settings-section-card__body">
                                <?php if ( $is_pro ) : ?>
                                    <?php
                                    $pages = get_pages();
                                    $wishlist_page = tmpcoder_get_settings( 'tmpcoder_wishlist_page' );
                                    $compare_page  = tmpcoder_get_settings( 'tmpcoder_compare_page' );
                                    ?>
                                    <div class="form-group-wrapper">
                                        <div class="form-group">
                                            <div class="spexo-woo-page-config-field">
                                                <label class="spexo-woo-pages-label" for="tmpcoder_wishlist_page"><?php esc_html_e( 'Select Wishlist Page', 'sastra-essential-addons-for-elementor' ); ?></label>
                                                <select name="tmpcoder_wishlist_page" id="tmpcoder_wishlist_page" class="spexo-woo-pages-select form-control">
                                                    <option value=""><?php esc_html_e( 'Select page...', 'sastra-essential-addons-for-elementor' ); ?></option>
                                                    <?php foreach ( $pages as $page ) : ?>
                                                        <option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $wishlist_page, $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="spexo-woo-page-config-field">
                                                <label class="spexo-woo-pages-label" for="tmpcoder_compare_page"><?php esc_html_e( 'Select Compare Page', 'sastra-essential-addons-for-elementor' ); ?></label>
                                                <select name="tmpcoder_compare_page" id="tmpcoder_compare_page" class="spexo-woo-pages-select form-control">
                                                    <option value=""><?php esc_html_e( 'Select page...', 'sastra-essential-addons-for-elementor' ); ?></option>
                                                    <?php foreach ( $pages as $page ) : ?>
                                                        <option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $compare_page, $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="spexo-pro-locked">
                                        <div class="spexo-locked-text">
                                            <strong><?php esc_html_e( 'Wishlist & Compare Pages', 'sastra-essential-addons-for-elementor' ); ?></strong>
                                            <p><?php esc_html_e( 'Assign dedicated wishlist and compare pages with Pro.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                </div>
                                        <a class="spexo-upgrade-link" href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade to Pro', 'sastra-essential-addons-for-elementor' ); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                        </div>
                    </div>
                                
                    <div class="spexo-settings-panel" data-panel="media-post-types">
                        <div class="spexo-media-sections-wrapper">
                        <section class="spexo-settings-section-card spexo-subpanel spexo-default-active" id="featured-video" data-subpanel="featured-video">
                            <div class="sub-header">
                                <div>
                                    <h3><?php esc_html_e( 'Featured Video Option', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                    <p><?php esc_html_e( 'Enable featured videos for supported post types.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                </div>
                            </div>
                            <div class="spexo-settings-section-card__body">
                                        <?php 
                                // Check if Pro license is active
                                if ( $is_pro && has_action( 'tmpcoder_video_options_tab' ) ) {
                                    do_action( 'tmpcoder_video_options_tab' );
                                } else {
                                    // Show locked content when license is inactive or Pro plugin not available
                                    ?>
                                    <div class="spexo-pro-locked">
                                        <div class="spexo-locked-text">
                                            <strong><?php esc_html_e( 'Featured Videos', 'sastra-essential-addons-for-elementor' ); ?></strong>
                                            <p><?php esc_html_e( 'Enable custom video sources for each post type with Pro.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                    </div>
                                        <a class="spexo-upgrade-link" href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade to Pro', 'sastra-essential-addons-for-elementor' ); ?></a>
                                </div>
                                <?php 
                                }
                                ?>
                            </div>
                        </section>

                        <section class="spexo-settings-section-card spexo-subpanel" id="display-mode" data-subpanel="display-mode">
                            <div class="sub-header">
                                <div>
                                    <h3><?php esc_html_e( 'Display Mode', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                    <p><?php esc_html_e( 'Choose the default video provider for featured videos.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                </div>
                            </div>
                            <div class="spexo-settings-section-card__body">
                                <?php
                                // Check if Pro license is active
                                if ( $is_pro && has_action( 'tmpcoder_video_display_mode_tab' ) ) {
                                    do_action( 'tmpcoder_video_display_mode_tab' );
                                } else {
                                    // Show locked content when license is inactive or Pro plugin not available
                                    ?>
                                    <div class="spexo-pro-locked">
                                        <div class="spexo-locked-text">
                                            <strong><?php esc_html_e( 'Display Mode', 'sastra-essential-addons-for-elementor' ); ?></strong>
                                            <p><?php esc_html_e( 'Select between YouTube or Vimeo playback with Pro.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                        </div>
                                        <a class="spexo-upgrade-link" href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade to Pro', 'sastra-essential-addons-for-elementor' ); ?></a>
                                    </div>
                                        <?php
                                }
                                ?>
                            </div>
                        </section>

                        <section class="spexo-settings-section-card spexo-subpanel" id="video-settings" data-subpanel="video-settings">
                            <div class="sub-header">
                                <div>
                                    <h3><?php esc_html_e( 'Video Settings', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                    <p><?php esc_html_e( 'Choose autoplay behaviour for popup playback.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                </div>
                            </div>
                            <div class="spexo-settings-section-card__body">
                                <?php
                                // Check if Pro license is active
                                if ( $is_pro && has_action( 'tmpcoder_video_settings_tab' ) ) {
                                    do_action( 'tmpcoder_video_settings_tab' );
                                } else {
                                    // Show locked content when license is inactive or Pro plugin not available
                                    ?>
                                    <div class="spexo-pro-locked">
                                        <div class="spexo-locked-text">
                                            <strong><?php esc_html_e( 'Autoplay', 'sastra-essential-addons-for-elementor' ); ?></strong>
                                            <p><?php esc_html_e( 'Automatically play featured videos inside popups with Pro.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                        </div>
                                        <a class="spexo-upgrade-link" href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade to Pro', 'sastra-essential-addons-for-elementor' ); ?></a>
                                    </div>
                            <?php
                                }
                            ?>
                            </div>
                        </section>
                            
                        <section class="spexo-settings-section-card spexo-subpanel" id="secondary-image" data-subpanel="secondary-image">
                            <div class="sub-header">
                                <div>
                                    <h3><?php esc_html_e( 'Secondary Featured Image', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                    <p><?php esc_html_e( 'Add a secondary featured image metabox to selected post types (Hover effect).', 'sastra-essential-addons-for-elementor' ); ?></p>
                                </div>
                            </div>
                            <div class="spexo-settings-section-card__body">
                                <?php if ( $is_pro ) : ?>
                                    <?php do_action( 'tmpcoder_secondary_image_options_tab' ); ?>
                                <?php else : ?>
                                    <div class="spexo-pro-locked">
                                        <div class="spexo-locked-text">
                                            <strong><?php esc_html_e( 'Pro Feature', 'sastra-essential-addons-for-elementor' ); ?></strong>
                                            <p><?php esc_html_e( 'Add a secondary featured image metabox to selected post types with Pro.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                        </div>
                                        <a class="spexo-upgrade-link" href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade to Pro', 'sastra-essential-addons-for-elementor' ); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                        </div>
                    </div>

                    <?php if ( $ai_manager ) : ?>
                        <div class="spexo-settings-panel" data-panel="ai-settings">
                            <div class="spexo-ai-sections-wrapper">
                            <section class="spexo-settings-section-card spexo-subpanel spexo-default-active" id="ai-openai" data-subpanel="ai-openai">
                                <div class="sub-header">
                                    <div>
                                        <h3><?php esc_html_e( 'OpenAI API Settings', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                        <p><?php esc_html_e( 'Enter your OpenAI API key and select the model for AI features.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                    </div>
                                </div>
                                <div class="spexo-settings-section-card__body spexo-ai-card">
                                    <?php echo $ai_sections['openai']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </div>
                            </section>

                            <section class="spexo-settings-section-card spexo-subpanel" id="ai-editor-tools" data-subpanel="ai-editor-tools">
                                <div class="sub-header">
                                    <div>
                                        <h3><?php esc_html_e( 'Editor Integration', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                        <p><?php esc_html_e( 'Control the integration of AI features in the Elementor editor.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                    </div>
                                </div>
                                <div class="spexo-settings-section-card__body spexo-ai-card">
                                    <?php echo $ai_sections['editor']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </div>
                            </section>

                            <section class="spexo-settings-section-card spexo-subpanel" id="ai-alt-text" data-subpanel="ai-alt-text">
                                <div class="sub-header">
                                    <div>
                                        <h3><?php esc_html_e( 'Alt Text Settings', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                        <p><?php esc_html_e( 'Configure automatic alt text generation for images in Media Library.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                    </div>
                                </div>
                                <div class="spexo-settings-section-card__body spexo-ai-card">
                                    <?php echo $ai_sections['alt']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </div>
                            </section>

                            <section class="spexo-settings-section-card spexo-subpanel" id="ai-translation" data-subpanel="ai-translation">
                                <div class="sub-header">
                                    <div>
                                        <h3><?php esc_html_e( 'Translation Settings', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                        <p><?php esc_html_e( 'Enable AI-powered translation tools for Elementor.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                    </div>
                                </div>
                                <div class="spexo-settings-section-card__body spexo-ai-card">
                                    <?php echo $ai_sections['translation']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </div>
                            </section>

                            <section class="spexo-settings-section-card spexo-subpanel" id="ai-usage-quota" data-subpanel="ai-usage-quota">
                                <div class="sub-header">
                                    <div>
                                        <h3><?php esc_html_e( 'Usage Quota Settings', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                        <p><?php esc_html_e( 'Set daily token quotas for your workspace.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                    </div>
                                </div>
                                <div class="spexo-settings-section-card__body spexo-ai-card">
                                    <?php echo $ai_sections['quota']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </div>
                            </section>

                            <section class="spexo-settings-section-card spexo-subpanel" id="ai-usage-stats" data-subpanel="ai-usage-stats">
                                <div class="sub-header">
                                    <div>
                                        <h3><?php esc_html_e( 'Usage Statistics', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                        <p><?php esc_html_e( 'Monitor how many tokens have been consumed.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                    </div>
                                </div>
                                <div class="spexo-settings-section-card__body spexo-ai-card">
                                    <?php echo $ai_sections['stats']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </div>
                            </section>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="spexo-settings-panel" data-panel="integration">
                        <section class="spexo-settings-section-card spexo-subpanel spexo-default-active" id="integration-section" data-subpanel="integration-section">
                            <div class="spexo-integration-content">
                                <div class="spexo-integration-card">
                                    <div class="sub-header spexo-integration-card-header">
                                        <h3 class="spexo-integration-card-title"><?php esc_html_e( 'MailChimp', 'sastra-essential-addons-for-elementor' ); ?></h3>
                                    </div>
                                    <div class="spexo-integration-card-body">
                                        <div class="form-group-wrapper">
                                            <div class="form-group">
                                                <label class="spexo-integration-label" for="tmpcoder_mailchimp_api_key">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail text-gray-400" aria-hidden="true"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path><rect x="2" y="4" width="20" height="16" rx="2"></rect></svg>
                                                    <span><?php esc_html_e('MailChimp API Key', 'sastra-essential-addons-for-elementor'); ?></span>
                                                </label>
                                                <p class="spexo-integration-description"><?php esc_html_e( 'Connect your MailChimp account to synchronize subscribers.', 'sastra-essential-addons-for-elementor' ); ?></p>
                                                <input type="text" class="spexo-woo-page-config-input form-control" name="tmpcoder_mailchimp_api_key" id="tmpcoder_mailchimp_api_key" value="<?php echo esc_attr( tmpcoder_get_settings( 'tmpcoder_mailchimp_api_key' ) ); ?>" placeholder="<?php echo esc_attr__( 'Enter your MailChimp API Key', 'sastra-essential-addons-for-elementor' ); ?>" data-default="" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" aria-label="<?php echo esc_attr__( 'MailChimp API Key', 'sastra-essential-addons-for-elementor' ); ?>">
                                                <a class="spexo-integration-link" href="https://mailchimp.com/help/about-api-keys/" target="_blank" rel="noopener noreferrer">
                                                    <?php esc_html_e( 'Where can I find my API Key?', 'sastra-essential-addons-for-elementor' ); ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="spexo-settings-footer">
                    <div class="spexo-settings-footer__text">
                    </div>
                    <div class="spexo-settings-footer__actions">
                    
                        <button type="button" class="button button-secondary spexo-reset-section"><?php esc_html_e( 'Reset Section', 'sastra-essential-addons-for-elementor' ); ?></button>
                        
                        <?php submit_button( esc_html__( 'Save Settings', 'sastra-essential-addons-for-elementor' ), 'button button-primary spexo-settings-save', '', false ); ?>

            </div>
                </div>
            </div>
        </div>  

        <div class="welcome-backend-loader">
            <img src="<?php echo esc_url( TMPCODER_ADDONS_ASSETS_URL . 'images/backend-loader.gif' ); ?>" alt="" width="80" height="80" />
        </div>
        <div class="tmpcoder-settings-saved">
            <span><?php esc_html_e( 'Options Updated', 'sastra-essential-addons-for-elementor' ); ?></span>
            <span class="dashicons dashicons-smiley"></span>
        </div>
        
        <script type="text/javascript">
        (function() {
            // Immediately set the correct active section before page renders to prevent flicker
            var hash = window.location.hash;
            var activePanel = null;
            var activeSubpanel = null;
            
            // Try to get active panel from hash
            if (hash && hash.length > 1) {
                var panelId = hash.substring(1);
                // Try to match known panel IDs first (most specific matches)
                var knownPanels = ['smooth-scroll', 'woocommerce', 'media-post-types', 'ai-settings', 'integration'];
                for (var i = 0; i < knownPanels.length; i++) {
                    if (panelId.indexOf(knownPanels[i]) === 0) {
                        activePanel = knownPanels[i];
                        // Extract subpanel if present (e.g., "woocommerce-woo-page-config")
                        if (panelId.length > knownPanels[i].length) {
                            activeSubpanel = panelId.substring(knownPanels[i].length + 1);
                        }
                        break;
                    }
                }
                // If no match, try splitting by first dash
                if (!activePanel && panelId.indexOf('-') > -1) {
                    activePanel = panelId.split('-')[0];
                } else if (!activePanel) {
                    activePanel = panelId;
                }
            }
            
            // Fallback to localStorage if no hash
            if (!activePanel) {
                try {
                    activePanel = localStorage.getItem('spexo_settings_active_panel');
                    if (activePanel) {
                        try {
                            activeSubpanel = localStorage.getItem('spexo_settings_active_subpanel_' + activePanel);
                        } catch(e) {}
                    }
                } catch(e) {}
            }
            
            // Inject CSS immediately to hide default sections and show correct one
            var style = document.createElement('style');
            style.id = 'spexo-initial-section-fix';
            
            if (activePanel) {
                // Hide default active items/panels
                style.textContent = 
                    '.spexo-settings-nav-item.spexo-default-active, ' +
                    '.spexo-settings-panel.spexo-default-active, ' +
                    '.spexo-subpanel.spexo-default-active { display: none !important; visibility: hidden !important; }' +
                    '.spexo-settings-nav-item[data-panel="' + activePanel + '"] { display: block !important; opacity: 1 !important; visibility: visible !important; }' +
                    '.spexo-settings-nav-item[data-panel="' + activePanel + '"].has-children { display: block !important; }' +
                    '.spexo-settings-nav-item[data-panel="' + activePanel + '"].has-children > .spexo-settings-nav-children { display: flex !important; visibility: visible !important; }' +
                    '.spexo-settings-panel[data-panel="' + activePanel + '"] { display: block !important; visibility: visible !important; }';
                
                // Show specific subpanel if found
                if (activeSubpanel) {
                    style.textContent += 
                        '.spexo-settings-panel[data-panel="' + activePanel + '"] .spexo-subpanel { display: none !important; visibility: hidden !important; }' +
                        '.spexo-settings-panel[data-panel="' + activePanel + '"] .spexo-subpanel[id="' + activeSubpanel + '"], ' +
                        '.spexo-settings-panel[data-panel="' + activePanel + '"] .spexo-subpanel[data-subpanel="' + activeSubpanel + '"] { display: block !important; visibility: visible !important; }';
                } else {
                    // Show first subpanel of active panel
                    style.textContent += 
                        '.spexo-settings-panel[data-panel="' + activePanel + '"] .spexo-subpanel { display: none !important; visibility: hidden !important; }' +
                        '.spexo-settings-panel[data-panel="' + activePanel + '"] .spexo-subpanel:first-child { display: block !important; visibility: visible !important; }';
                }
            } else {
                // No saved state - show default (first) section
                style.textContent = 
                    '.spexo-settings-nav-item.spexo-default-active, ' +
                    '.spexo-settings-panel.spexo-default-active, ' +
                    '.spexo-subpanel.spexo-default-active { display: block !important; visibility: visible !important; }';
            }
            
            // Verify the panel exists in the DOM before applying styles
            if (activePanel) {
                var panelExists = document.querySelector('.spexo-settings-panel[data-panel="' + activePanel + '"]');
                if (!panelExists) {
                    // Panel doesn't exist, show default instead
                    activePanel = null;
                    style.textContent = 
                        '.spexo-settings-nav-item.spexo-default-active, ' +
                        '.spexo-settings-panel.spexo-default-active, ' +
                        '.spexo-subpanel.spexo-default-active { display: block !important; visibility: visible !important; }';
                }
            }
            
            document.head.appendChild(style);
        })();
        </script>
    </form>
</div>
