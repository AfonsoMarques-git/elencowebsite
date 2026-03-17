<?php

namespace TMPCODER\Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Spexo_Addons_Elementor\Classes\Helper as HelperClass;
use Spexo_Addons_Elementor\Template\Content\Product_Grid as Product_Grid_Trait;
use Spexo_Addons_Elementor\Traits\Helper;
use Spexo_Addons_Elementor\Traits\Woo_Product_Comparable;

class Product_Grid extends Widget_Base
{
    use Woo_Product_Comparable;
    use Helper;
    use Product_Grid_Trait;

    private $is_show_custom_add_to_cart = false;
    private $simple_add_to_cart_button_text;
    private $variable_add_to_cart_button_text;
    private $grouped_add_to_cart_button_text;
    private $external_add_to_cart_button_text;
    private $default_add_to_cart_button_text;
    /**
     * @var int
     */
    protected $page_id;

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $is_type_instance = $this->is_type_instance();

        if ( ! $is_type_instance && null === $args ) {
            throw new \Exception( '`$args` argument is required when initializing a full widget instance.' );
        }

        if ( $is_type_instance && class_exists('woocommerce')) {
            $this->load_quick_view_asset();
        }
    }

    public function get_name()
    {
        return 'eicon-woocommerce';
    }

    public function get_title()
    {
        return esc_html__('Product Grid (Classic)', 'sastra-essential-addons-for-elementor');
    }

    public function get_icon()
    {
        return 'tmpcoder-icon eicon-gallery-grid';
    }

    public function get_categories() {
        if (tmpcoder_show_theme_buider_widget_on('type_product_archive') || tmpcoder_show_theme_buider_widget_on('type_product_category') || tmpcoder_show_theme_buider_widget_on('type_single_product')) {
            return [ 'tmpcoder-woocommerce-builder-widgets'];
        }else{
            return ['tmpcoder-widgets-category'];
        }
    }

    public function get_keywords()
    {
        return [
            'woo',
            'woocommerce',
            'spexo woocommerce',
            'spexo woo product grid',
            'spexo woocommerce product grid',
            'product gallery',
            'woocommerce grid',
            'gallery',
            'spexo',
            'spexo addons',
        ];
    }

    public function has_widget_inner_wrapper(): bool {
        return ! HelperClass::tmpcoder_e_optimized_markup();
    }

    public function get_custom_help_url()
    {
        return TMPCODER_NEED_HELP_URL;
    }

    public function get_style_depends()
    {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
            'tmpcoder-woo-product-grid-classic',
            'tmpcoder-load-more-products',
            'tmpcoder-quick-view',
        ];
    }

    public function get_script_depends()
    {
        return [
            'tmpcoder-woo-grid-general',
            'font-awesome-4-shim',
            'tmpcoder-isotope',
            'tmpcoder-load-more-products',
            'tmpcoder-woo-grid-classic',
            'tmpcoder-quick-view',
            'tmpcoder-woo-grid-classic-wishlist',
            'tmpcoder-slick',
            // 'tmpcoder-grid-widgets',
        ];
    }

    public function add_to_cart_button_custom_text($default)
    {
        if ($this->is_show_custom_add_to_cart) {
            global $product;
            switch ($product->get_type()) {
                case 'external':
                    return $this->external_add_to_cart_button_text;
                case 'grouped':
                    return $this->grouped_add_to_cart_button_text;
                case 'simple':
                    if ( ! $product->is_in_stock() ) {
                        return $this->default_add_to_cart_button_text;
                    }
                    return $this->simple_add_to_cart_button_text;
                case 'variable':
                    return $this->variable_add_to_cart_button_text;
                default:
                    return $this->default_add_to_cart_button_text;
            }
        }

        if( 'Read more' === $default ) {
            return esc_html__( 'View More', 'sastra-essential-addons-for-elementor' );
        }

        return $default;
    }

    protected function tmpcoder_get_product_orderby_options()
    {
        return apply_filters('tmpcoder/product-grid/orderby-options', [
            'ID'         => __('Product ID', 'sastra-essential-addons-for-elementor'),
            'title'      => __('Product Title', 'sastra-essential-addons-for-elementor'),
            '_price'     => __('Price', 'sastra-essential-addons-for-elementor'),
            '_sku'       => __('SKU', 'sastra-essential-addons-for-elementor'),
            'date'       => __('Date', 'sastra-essential-addons-for-elementor'),
            'modified'   => __('Last Modified Date', 'sastra-essential-addons-for-elementor'),
            'parent'     => __('Parent Id', 'sastra-essential-addons-for-elementor'),
            'rand'       => __('Random', 'sastra-essential-addons-for-elementor'),
            'menu_order' => __('Menu Order', 'sastra-essential-addons-for-elementor'),
        ]);
    }

    protected function tmpcoder_get_product_filterby_options()
    {
        return apply_filters('tmpcoder/product-grid/filterby-options', [
            'recent-products'       => esc_html__('Recent Products', 'sastra-essential-addons-for-elementor'),
            'featured-products'     => esc_html__('Featured Products', 'sastra-essential-addons-for-elementor'),
            'best-selling-products' => esc_html__('Best Selling Products', 'sastra-essential-addons-for-elementor'),
            'sale-products'         => esc_html__('Sale Products', 'sastra-essential-addons-for-elementor'),
            'top-products'          => esc_html__('Top Rated Products', 'sastra-essential-addons-for-elementor'),
            'related-products'      => esc_html__('Related Products', 'sastra-essential-addons-for-elementor'),
            'manual'                => esc_html__('Manual Selection', 'sastra-essential-addons-for-elementor'),
        ]);
    }

    protected function register_controls()
    {
        $this->init_content_wc_notice_controls();
        if (!function_exists('WC')) {
            return;
        }
        // Content Controls
        $this->init_content_layout_controls();
        $this->init_content_product_settings_controls();
        $this->tmpcoder_product_badges();
        $this->init_content_addtocart_controls();
        $this->init_content_load_more_controls();
        $this->tmpcoder_product_pagination();

        // Product Compare
        $this->init_content_product_compare_controls();
        $this->init_content_table_settings_controls();

        // Style Controls---------------
        $this->init_style_product_controls();
        $this->init_style_color_typography_controls();
        $this->init_style_addtocart_controls();
        $this->tmpcoder_init_style_addtowishlist_controls();        
        $this->sale_badge_style();
        $this->tmpcoder_product_action_buttons();
        $this->tmpcoder_product_action_buttons_style();

        /**
         * Load More Button Style Controls!
         */
        // do_action('tmpcoder/controls/load_more_button_style', $this);
        $this->load_more_button_style();

        /**
         * Pagination Style Controls!
         */
        $this->tmpcoder_product_pagination_style();

        /**
         * Pagination Style Controls!
         */
        $this->tmpcoder_product_view_popup_style();
        // Product Compare Table Style
        $container_class = '.tmpcoder-wcpc-modal';
        $table = ".tmpcoder-wcpc-modal .tmpcoder-wcpc-wrapper table";
        $table_title = ".tmpcoder-wcpc-modal .tmpcoder-wcpc-wrapper .wcpc-title";
        $table_title_wrap = ".tmpcoder-wcpc-modal .tmpcoder-wcpc-wrapper .first-th";
        $compare_btn_condition = [
            'tmpcoder_product_grid_style_preset!' => [
                'tmpcoder-product-preset-5',
                'tmpcoder-product-preset-6',
                'tmpcoder-product-preset-7',
                'tmpcoder-product-preset-8',
            ],
            'tmpcoder_product_grid_layout!' => 'list',
        ];
        $this->init_style_compare_button_controls($compare_btn_condition);
        $this->init_style_content_controls(compact('container_class'));
        $this->init_style_table_controls(compact('table', 'table_title', 'table_title_wrap'));
        $this->init_style_close_button_controls();
        
        // Slider Navigation Style Controls
        $this->init_style_slider_navigation_controls();
        
        // Slider Pagination Style Controls
        $this->init_style_slider_pagination_controls();
    }

    protected function init_content_layout_controls()
    {
        $this->start_controls_section(
            'tmpcoder_section_product_grid_layouts',
            [
                'label' => esc_html__('Layouts', 'sastra-essential-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_layout',
            [
                'label'   => __( 'Layout', 'sastra-essential-addons-for-elementor' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'grid' => [
                        'title' => esc_html__( 'Grid', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-gallery-grid',
                    ],
                    'masonry' => [
                        'title' => esc_html__( 'Masonry', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-gallery-masonry',
                    ],
                    'list' => [
                        'title' => esc_html__( 'List', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-post-list',
                    ],
                ],
                'default' => 'masonry',
                'toggle'  => false,
            ]
        );

        // Slider Enable/Disable Switch

        if (tmpcoder_is_availble()) {
            
            $this->add_control(
                'tmpcoder_enable_slider',
                [
                    'label' => esc_html__('Enable Slider', 'sastra-essential-addons-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'sastra-essential-addons-for-elementor'),
                    'label_off' => esc_html__('No', 'sastra-essential-addons-for-elementor'),
                    'return_value' => 'yes',
                    'default' => '',
                    'separator' => 'before',
                    'condition' => ['tmpcoder_product_grid_layout' => 'grid'],
                    'description'  => __( 'This option will only work with the grid layout; it will not work with list or masonry layouts.', 'sastra-essential-addons-for-elementor' )
                ]
            );
        }
        else
        {
            $this->add_control(
                'tmpcoder_enable_slider_pro',
                [
                    // Translators: %s is the icon.
                    'label' => sprintf( __( 'Enable Slider %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'sastra-essential-addons-for-elementor'),
                    'label_off' => esc_html__('No', 'sastra-essential-addons-for-elementor'),
                    'return_value' => 'yes',
                    'default' => '',
                    'separator' => 'before',
                    'condition' => ['tmpcoder_product_grid_layout' => 'grid'],
                    'classes' => 'tmpcoder-pro-control no-distance',
                    'description'  => __( 'This option will only work with the grid layout; it will not work with list or masonry layouts.', 'sastra-essential-addons-for-elementor' )
                ]
            );    
        }

        $image_path = TMPCODER_PLUGIN_URI . 'assets/images/admin/layout-previews/woo-product-grid-preset-';
        $this->add_control(
            'tmpcoder_product_grid_style_preset',
            [
                'label'       => esc_html__( 'Style Preset', 'sastra-essential-addons-for-elementor' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'tmpcoder-product-default' => [
                        'title' => esc_html__('Default', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . 'default.png'
                    ],
                    'tmpcoder-product-simple' => [
                        'title' => esc_html__('Simple Style', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . 'simple.png'
                    ],
                    'tmpcoder-product-reveal' => [
                        'title' => esc_html__('Reveal Style', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . 'reveal.png'
                    ],
                    'tmpcoder-product-overlay' => [
                        'title' => esc_html__('Overlay Style', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . 'overlay.png'
                    ],
                    'tmpcoder-product-preset-5' => [
                        'title' => esc_html__('Preset 5', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . '5.png'
                    ],
                    'tmpcoder-product-preset-6' => [
                        'title' => esc_html__('Preset 6', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . '6.png'
                    ],
                    'tmpcoder-product-preset-7' => [
                        'title' => esc_html__('Preset 7', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . '7.png'
                    ],
                    'tmpcoder-product-preset-8' => [
                        'title' => esc_html__('Preset 8', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . '8.png'
                    ],
                ],
                'default'     => 'tmpcoder-product-simple',
                'label_block' => true,
                'toggle'      => false,
                'image_choose'=> true,
                'condition'   => [
                    'tmpcoder_product_grid_layout' => [ 'grid', 'masonry' ],
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_style_default_preset_notice',
            [
                'label' => '',
                'type'  => Controls_Manager::RAW_HTML,
                'raw'   => esc_html__( 'We do not recommend this preset, as it is rendered directly by WooCommerce functions. This may lead to broken styles and limited functionality for some controls.', 'sastra-essential-addons-for-elementor' ),
                'content_classes' => 'tmpcoder-warning',
                'condition'   => [
                    'tmpcoder_product_grid_layout' => [ 'grid', 'masonry' ],
                    'tmpcoder_product_grid_style_preset' => 'tmpcoder-product-default',
                ],
            ]
        );

        $image_path = TMPCODER_PLUGIN_URI . 'assets/images/admin/layout-previews/woo-product-grid-list-preset-';
        $this->add_control(
            'tmpcoder_product_list_style_preset',
            [
                'label'       => esc_html__( 'Style Preset', 'sastra-essential-addons-for-elementor' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'tmpcoder-product-list-preset-1' => [
                        'title' => esc_html__('Preset 1', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . '1.png'
                    ],
                    'tmpcoder-product-list-preset-2' => [
                        'title' => esc_html__('Preset 2', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . '2.png'
                    ],
                    'tmpcoder-product-list-preset-3' => [
                        'title' => esc_html__('Preset 3', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . '3.png'
                    ],
                    'tmpcoder-product-list-preset-4' => [
                        'title' => esc_html__('Preset 4', 'sastra-essential-addons-for-elementor'),
                        'image' => $image_path . '4.png'
                    ],
                ],
                'default'     => 'tmpcoder-product-list-preset-1',
                'label_block' => true,
                'toggle'      => false,
                'image_choose'=> true,
                'condition'   => [
                    'tmpcoder_product_grid_layout' => [ 'list' ],
                ],
            ]
        );
        
        $this->add_responsive_control(
            'tmpcoder_product_grid_column',
            [
                'label' => esc_html__('Columns', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '4',
                'options' => [
                    '1' => [
                        'title' => esc_html__( '1', 'sastra-essential-addons-for-elementor' ),
                        'text'  => '1',
                    ],
                    '2' => [
                        'title' => esc_html__( '2', 'sastra-essential-addons-for-elementor' ),
                        'text'  => '2',
                    ],
                    '3' => [
                        'title' => esc_html__( '3', 'sastra-essential-addons-for-elementor' ),
                        'text'  => '3',
                    ],
                    '4' => [
                        'title' => esc_html__( '4', 'sastra-essential-addons-for-elementor' ),
                        'text'  => '4',
                    ],
                    '5' => [
                        'title' => esc_html__( '5', 'sastra-essential-addons-for-elementor' ),
                        'text'  => '5',
                    ],
                    '6' => [
                        'title' => esc_html__( '6', 'sastra-essential-addons-for-elementor' ),
                        'text'  => '6',
                    ],
                ],
                'toggle' => true,
                'prefix_class' => 'tmpcoder-product-grid-column%s-',
                'condition' => [
                    'tmpcoder_product_grid_layout!' => 'list',
                    // 'tmpcoder_enable_slider!' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_list_column',
            [
                'label' => esc_html__('Columns', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                    '1' => esc_html__('1', 'sastra-essential-addons-for-elementor'),
                    '2' => esc_html__('2', 'sastra-essential-addons-for-elementor'),
                ],
                'toggle' => true,
                'prefix_class' => 'tmpcoder-product-list-column%s-',
                'condition' => [
                    'tmpcoder_product_grid_layout' => 'list',
                ],
            ]
        );

        // Slider Controls
        $this->add_responsive_control(
            'tmpcoder_slider_columns',
            [
                'label' => esc_html__('Columns (Carousel)', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => esc_html__('1', 'sastra-essential-addons-for-elementor'),
                    '2' => esc_html__('2', 'sastra-essential-addons-for-elementor'),
                    '3' => esc_html__('3', 'sastra-essential-addons-for-elementor'),
                    '4' => esc_html__('4', 'sastra-essential-addons-for-elementor'),
                    '5' => esc_html__('5', 'sastra-essential-addons-for-elementor'),
                    '6' => esc_html__('6', 'sastra-essential-addons-for-elementor'),
                ],
                'condition' => [
                    'tmpcoder_enable_slider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_slides_to_scroll',
            [
                'label' => esc_html__('Slides to Scroll', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('1', 'sastra-essential-addons-for-elementor'),
                    '2' => esc_html__('2', 'sastra-essential-addons-for-elementor'),
                    '3' => esc_html__('3', 'sastra-essential-addons-for-elementor'),
                ],
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_slider_gutter',
            [
                'label' => esc_html__('Gutter', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid.slider .slick-slide' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
                    '{{WRAPPER}} .tmpcoder-product-grid.slider .slick-list' => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
                ],
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_navigation',
            [
                'label' => esc_html__('Navigation', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'sastra-essential-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'sastra-essential-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tmpcoder_slider_nav_icon',
            [
                'label' => esc_html__('Select Nav Icon', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fas fa-angle',
                'options' => [
                    'fas fa-angle' => esc_html__('Angle', 'sastra-essential-addons-for-elementor'),
                    'fas fa-arrow' => esc_html__('Arrow', 'sastra-essential-addons-for-elementor'),
                    'fas fa-chevron' => esc_html__('Chevron', 'sastra-essential-addons-for-elementor'),
                    'fas fa-caret' => esc_html__('Caret', 'sastra-essential-addons-for-elementor'),
                ],
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                    'tmpcoder_slider_navigation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_pagination',
            [
                'label' => esc_html__('Pagination', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'sastra-essential-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'sastra-essential-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tmpcoder_slider_autoplay',
            [
                'label' => esc_html__('Autoplay', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'sastra-essential-addons-for-elementor'),
                'label_off' => esc_html__('Off', 'sastra-essential-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tmpcoder_slider_autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed (ms)', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3000,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                    'tmpcoder_slider_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_pause_on_hover',
            [
                'label' => esc_html__('Pause on Hover', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'sastra-essential-addons-for-elementor'),
                'label_off' => esc_html__('Off', 'sastra-essential-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                    'tmpcoder_slider_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_infinite_loop',
            [
                'label' => esc_html__('Infinite Loop', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'sastra-essential-addons-for-elementor'),
                'label_off' => esc_html__('Off', 'sastra-essential-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_show_product_sale_badge',
            [
                'label' => esc_html__( 'Show Badge ?', 'sastra-essential-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'sastra-essential-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tmpcoder_wc_loop_hooks',
            [
                'label'        => esc_html__( 'WooCommerce Loop Hooks', 'sastra-essential-addons-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'ON', 'sastra-essential-addons-for-elementor' ),
                'label_off'    => esc_html__( 'OFF', 'sastra-essential-addons-for-elementor' ),
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => '',
                'description'  => __( 'This will enable WooCommerce loop Before and After hooks. It may break your layout.', 'sastra-essential-addons-for-elementor' )
            ]
        );

        do_action( 'tmpcoder/product_grid/layout/controls', $this );

        $this->end_controls_section();
    }

    protected function init_content_product_settings_controls()
    {
        $this->start_controls_section(
            'tmpcoder_section_product_grid_settings', 
            [
            'label' => esc_html__('Query', 'sastra-essential-addons-for-elementor'),
        ]);

        $this->add_control(
            'post_type',
            [
                'label'   => __( 'Source', 'sastra-essential-addons-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'product',
                'options' => [
                    'product'        => esc_html__( 'Products', 'sastra-essential-addons-for-elementor' ),
                    'source_dynamic' => esc_html__( 'Dynamic', 'sastra-essential-addons-for-elementor' ),
                    'source_archive' => esc_html__( 'Archive', 'sastra-essential-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_global_dynamic_source_warning_text',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __( 'This option will only affect in <strong>Archive page of Elementor Theme Builder</strong> dynamically.', 'sastra-essential-addons-for-elementor' ),
                'content_classes' => 'tmpcoder-warning',
                'condition'       => [
                    'post_type' => [ 'source_dynamic', 'source_archive' ],
                ],
            ]
        );

        $this->add_control(
            'current_query_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => 
                    'To set <strong>Posts per Page</strong> for all <strong>Shop Pages</strong>, navigate to <strong><a href="'.esc_url(admin_url( '?page=spexo-welcome&tab=settings' )).'" target="_blank">Spexo Addons > Settings<a></strong>.',
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'       => [
                    'post_type' => [ 'source_dynamic', 'source_archive' ],
                ],
            ]
        );

        if ( !apply_filters( 'tmpcoder/is_plugin_active', 'woocommerce/woocommerce.php' ) ) {
            $this->add_control(
                'ea_product_grid_woo_required',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'sastra-essential-addons-for-elementor' ),
                    'content_classes' => 'tmpcoder-warning',
                ]
            );
        }

        $this->add_control(
            'tmpcoder_product_grid_product_filter', 
            [
                'label' => esc_html__('Filter By', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'recent-products',
                'options' => $this->tmpcoder_get_product_filterby_options(),
                'condition' => [
                'post_type' => 'product',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_global_related_products_warning_text',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __( 'This filter will only affect in <strong>Single Product</strong> page of <strong>Elementor Theme Builder</strong> dynamically.', 'sastra-essential-addons-for-elementor' ),
                'content_classes' => 'tmpcoder-warning',
                'condition'       => [
                    'tmpcoder_product_grid_product_filter' => 'related-products',
                ],
            ]
        );

        $this->add_control('orderby', [
            'label' => __('Order By', 'sastra-essential-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => $this->tmpcoder_get_product_orderby_options(),
            'default' => 'date',
            'condition' => [
                'tmpcoder_product_grid_product_filter!' => [ 'best-selling-products', 'top-products' ],
                'post_type!' => 'source_archive',
            ]

        ]);

        $this->add_control('order', [
            'label' => __('Order', 'sastra-essential-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending',
            ],
            'default' => 'desc',
            'condition' => [
                'post_type!' => 'source_archive',
            ]

        ]);

        $this->add_control('tmpcoder_product_grid_products_count', [
            'label' => __('Products Count', 'sastra-essential-addons-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 4,
            'min' => 1,
            'max' => 1000,
            'step' => 1,
            'condition' => [
                'post_type!' => 'source_archive',
            ]
        ]);

        $this->add_control('product_offset', [
            'label' => __('Offset', 'sastra-essential-addons-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'tmpcoder_product_grid_product_filter!' => 'manual',
                'post_type!' => 'source_archive',
            ]
        ]);

        if ( current_user_can( 'administrator' ) ) {
            $this->add_control(
                'tmpcoder_product_grid_products_status',
                [
                    'label'       => __( 'Product Status', 'sastra-essential-addons-for-elementor' ),
                    'type'        => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple'    => true,
                    'default'     => [ 'publish', 'pending', 'future' ],
                    'options'     => $this->tmpcoder_get_product_statuses(),
                    'condition'   => [
                        'tmpcoder_product_grid_product_filter!' => 'manual',
                        'post_type!' => 'source_archive',
                    ]
                ]
            );
        }

        $this->add_control('tmpcoder_product_grid_categories', [
            'label' => esc_html__('Product Categories', 'sastra-essential-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'multiple' => true,
            'options' => HelperClass::get_terms_list('product_cat', 'slug'),
            'condition'   => [
              'post_type' => 'product',
              'tmpcoder_product_grid_product_filter!' => [ 'manual' , 'related-products' ],
            ],
        ]);

        $this->add_control('tmpcoder_product_grid_tags', [
            'label'       => esc_html__('Product Tags', 'sastra-essential-addons-for-elementor'),
            'type'        => Controls_Manager::SELECT2,
            'label_block' => true,
            'multiple'    => true,
            'options'     => HelperClass::get_terms_list('product_tag', 'slug'),
            'condition'   => [
              'post_type!' => 'source_dynamic',
              'tmpcoder_product_grid_product_filter!' => [ 'manual' , 'related-products' ],
            ],
        ]);

        // Manual Selection
        $this->add_control(
            'tmpcoder_product_grid_products_in',
            [
                'label' => esc_html__( 'Select Products', 'sastra-essential-addons-for-elementor' ),
                'type' => 'tmpcoder-ajax-select2',
                'options' => 'ajaxselect2/get_posts_by_post_type',
                'query_slug' => 'product',
                'multiple' => true,
                'label_block' => true,
                'source_name' => 'post_type',
                'source_type' => 'product',
                'condition'   => [
                    'post_type!' => 'source_dynamic',
                    'tmpcoder_product_grid_product_filter' => 'manual'
                ],
            ]
        );

        // $this->add_control(
        //     'tmpcoder_product_not_in', 
        // [
        //     'label'       => esc_html__('Exclude Products', 'sastra-essential-addons-for-elementor'),
        //     'type'        => 'tmpcoder-select2',
        //     'label_block' => true,
        //     'multiple'    => true,
        //     'source_name' => 'post_type',
        //     'source_type' => 'product',
        //     'condition'   => [
        //       'post_type' => 'product',
        //       'tmpcoder_product_grid_product_filter!' => [ 'manual' , 'related-products' ],
        //     ],
        // ]);

        $this->add_control(
            'tmpcoder_product_not_in',
            [
                'label' => esc_html__( 'Exclude Products', 'sastra-essential-addons-for-elementor' ),
                'type' => 'tmpcoder-ajax-select2',
                'options' => 'ajaxselect2/get_posts_by_post_type',
                'query_slug' => 'product',
                'multiple' => true,
                'label_block' => true,
                'source_name' => 'post_type',
                'source_type' => 'product',
                'condition'   => [
                  'post_type' => 'product',
                  'tmpcoder_product_grid_product_filter!' => [ 'manual' , 'related-products' ],
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_cat_not_in', 
            [
            'label'       => esc_html__('Exclude Categories', 'sastra-essential-addons-for-elementor'),
            'type'        => Controls_Manager::SELECT2,
            'label_block' => true,
            'multiple'    => true,
            'options'     => HelperClass::get_terms_list('product_cat', 'slug'),
            'condition'   => [
              'post_type' => 'product',
              'tmpcoder_product_grid_product_filter!' => [ 'manual' , 'related-products' ],
            ],
        ]);

        $this->add_control(
            'allow_order_dropdown',
            [
                'label'        => esc_html__( 'Allow Order', 'sastra-essential-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'sastra-essential-addons-for-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'   => [
                  'post_type' => 'source_archive',
                ],
            ]
        );

        $this->add_control(
            'allow_customizer_settings',
            [
                'label'        => esc_html__( 'Allow Customozer Settings', 'sastra-essential-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'sastra-essential-addons-for-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'   => [
                  'post_type' => 'source_archive',
                ],
            ]
        );

        $wc_settings_url = admin_url( 'admin.php?page=wc-settings&tab=products&section=inventory' );
        $this->add_control(
            'tmpcoder_product_show_stockout',
            [
                'label'        => esc_html__( 'Out of Stock', 'sastra-essential-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'sastra-essential-addons-for-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description' => sprintf(
                    /* Translators: %s is the wc setting url. */
                    __( 'Uncheck the WooCommerce Settings <a href="%s" target="_blank">Out of stock visibility</a> option. This will not work otherwise.', 'sastra-essential-addons-for-elementor' ),
                    esc_url( $wc_settings_url )
                )
            ]
        );

        $this->add_control('product_type_logged_users', [
            'label' => __('Product Type', 'sastra-essential-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'description' => __('For logged in users only!', 'sastra-essential-addons-for-elementor'),
            'options' => [
                '' => __('Select', 'sastra-essential-addons-for-elementor'),
                'purchased' => __('Purchased Only', 'sastra-essential-addons-for-elementor'),
                'not-purchased' => __('Not Purchased Only', 'sastra-essential-addons-for-elementor'),
            ],
            'default' => '',

        ]);

        $this->add_control(
            'tmpcoder_product_grid_title_html_tag',
            [
                'label'       => __( 'Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
                'label_block' => true,
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'h1' => [
                        'title' => esc_html__( 'H1', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h1',
                    ],
                    'h2' => [
                        'title' => esc_html__( 'H2', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h2',
                    ],
                    'h3' => [
                        'title' => esc_html__( 'H3', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h3',
                    ],
                    'h4' => [
                        'title' => esc_html__( 'H4', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h4',
                    ],
                    'h5' => [
                        'title' => esc_html__( 'H5', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h5',
                    ],
                    'h6' => [
                        'title' => esc_html__( 'H6', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h6',
                    ],
                    'div' => [
                        'title' => esc_html__( 'Div', 'sastra-essential-addons-for-elementor' ),
                        'text'  => 'div',
                    ],
                    'span' => [
                        'title' => esc_html__( 'Span', 'sastra-essential-addons-for-elementor' ),
                        'text'  => 'span',
                    ],
                    'p' => [
                        'title' => esc_html__( 'P', 'sastra-essential-addons-for-elementor' ),
                        'text'  => 'P',
                    ],
                ],
                'default'   => 'h2',
                'toggle'    => false,
                'condition'=> [
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default'
                ]
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_rating', 
        [
            'label'        => esc_html__('Product Rating?', 'sastra-essential-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Show', 'sastra-essential-addons-for-elementor' ),
            'label_off'    => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor' ),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => [
                'tmpcoder_product_grid_style_preset!' => ['tmpcoder-product-preset-8'],
            ],

        ]);

        if ( apply_filters('tmpcoder/pro_enabled', false) ) {
            $this->add_control(
                'tmpcoder_product_rating_type',
                [
                    'label'     => esc_html__( 'Type', 'sastra-essential-addons-for-elementor' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'stars',
                    'options'   => [
                        'stars'        => esc_html__( '&#10029;&#10029;&#10029;&#10029;&#10027;', 'sastra-essential-addons-for-elementor' ),
                        'stars-number' => esc_html__( '&#10029; 4.7', 'sastra-essential-addons-for-elementor' ),
                        'number'       => esc_html__( '4.7/5', 'sastra-essential-addons-for-elementor' ),
                    ],
                    'condition' => [
                        'tmpcoder_product_grid_rating' => 'yes',
                        'tmpcoder_product_grid_style_preset!' => [ 'tmpcoder-product-default', 'tmpcoder-product-preset-8' ],
                    ],
                    'separator' => 'after'
                ]
            );
        }

        $this->add_control(
            'tmpcoder_product_sold_count', [
                'label'        => esc_html__( 'Show Sold Count?', 'sastra-essential-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default'
                ]
            ]
        );

        $this->add_control(
            'tmpcoder_product_sold_count_type',
            [
                'label'     => esc_html__( 'Type', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'number',
                'options'   => [
                    'number'     => esc_html__( 'Only Count', 'sastra-essential-addons-for-elementor' ),
                    'bar-number' => esc_html__( 'Count with Progress Bar', 'sastra-essential-addons-for-elementor' ),
                    'bar'        => esc_html__( 'Only Progress Bar', 'sastra-essential-addons-for-elementor' ),
                ],
                'condition' => [
                    'tmpcoder_product_sold_count' => 'yes',
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default'
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_sold_count_bar_width',
            [
                'label'       => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ '%' ],
                'range'       => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'     => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'condition'   => [
                    'tmpcoder_product_sold_count' => 'yes',
                    'tmpcoder_product_sold_count_type!' => 'number',
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default'
                ],
                'description' => esc_html__( 'This width applied in progress bar for those products which stocks are not managed', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'tmpcoder_product_sold_count_bar_height',
            [
                'label'      => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products .product .tmpcoder-product-sold-count-progress-bar' => 'height: {{SIZE}}{{UNIT}};'
                ],
                'condition'  => [
                    'tmpcoder_product_sold_count'       => 'yes',
                    'tmpcoder_product_sold_count_type!' => 'number',
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default'
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_sold_count_text',
            [
                'label'       => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => '[sold_count] Sold',
                'condition'   => [
                    'tmpcoder_product_sold_count' => 'yes',
                    'tmpcoder_product_sold_count_type!' => 'bar',
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default'
                ],
                'ai' => [
                        'active' => false
                ],
                'description' => __( '<strong>[sold_count]</strong> Will be replaced with actual amount.', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'tmpcoder_product_sold_count_text_align',
            [
                'label'     => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'condition' => [
                    'tmpcoder_product_sold_count'       => 'yes',
                    'tmpcoder_product_sold_count_type!' => 'bar',
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default'
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-sold-count-number' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_price',
            [
                'label'        => esc_html__('Product Price?', 'sastra-essential-addons-for-elementor'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'sastra-essential-addons-for-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor' ),
                'return_value' => 'yes',
                'separator' => 'before',
                'default'      => 'yes',
                'condition'    => [
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_excerpt',
            [
                'label'        => esc_html__('Short Description?', 'sastra-essential-addons-for-elementor'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'sastra-essential-addons-for-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'tmpcoder_product_grid_layout' => 'list',
                ],
            ]
        );
        $this->add_control(
            'tmpcoder_product_grid_excerpt_length',
            [
                'label' => __('Excerpt Words', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '10',
                'condition' => [
                    'tmpcoder_product_grid_excerpt' => 'yes',
                    'tmpcoder_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_excerpt_expanison_indicator',
            [
                'label' => esc_html__('Expansion Indicator', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => '...',
                'condition' => [
                    'tmpcoder_product_grid_excerpt' => 'yes',
                    'tmpcoder_product_grid_layout' => 'list',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'tmpcoder_product_grid_image_size',
                'exclude' => ['custom'],
                'default' => 'full',
                'label_block' => true,
            ]
        );

        if ( tmpcoder_is_availble() ) {
            if ( tmpcoder_get_settings('tmpcoder_meta_secondary_image_product')  ===  'on' ) {
                $this->add_control(
                    'secondary_img_on_hover',
                    [
                        'label' => esc_html__( 'Secondary Img on Hover', 'sastra-essential-addons-for-elementor' ),
                        'type' => Controls_Manager::SWITCHER,
                        'render_type' => 'template',
                    ]
                );
            } else {
                $this->add_control(
                    'secondary_img_on_hover',
                    [
                        'type' => Controls_Manager::RAW_HTML,
                        // Translators: %s is the url.
                        'raw' => sprintf( __( '<strong>Note:</strong> Navigate to <a href="%s" target="_blank">Spexo Addons > Settings</a><br> to enable the <strong>Secondary Image on Hover</strong> feature.', 'sastra-essential-addons-for-elementor' ), admin_url( 'admin.php?page=spexo-welcome&tab=settings' )),
                        'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                    ]
                );
            }
        } else {
            $this->add_control(
                'secondary_img_on_hover',
                [
                    // Translators: %s is the icon.
                    'label' => sprintf( __( '2nd Image on Hover %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
                    'type' => Controls_Manager::SWITCHER,
                    'classes' => 'tmpcoder-pro-control no-distance'
                ]
            );
        }

        $this->add_control('show_compare', [
            'label'     => esc_html__('Product Compare?', 'sastra-essential-addons-for-elementor'),
            'type'      => Controls_Manager::SWITCHER,
            'label_on'  => esc_html__( 'Show', 'sastra-essential-addons-for-elementor' ),
            'label_off' => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor' ),
        ]);

        $this->add_control(
            'tmpcoder_product_grid_image_clickable',
            [
                'label'        => esc_html__('Image Clickable?', 'sastra-essential-addons-for-elementor'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'           => 'no',
                'condition'    => [
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default',
                ],
            ]
        );

        if ( tmpcoder_is_availble() ) {
            $this->add_control(
                'tmpcoder_product_grid_wishlist',
                [
                    'label'        => esc_html__( 'Wishlist?', 'sastra-essential-addons-for-elementor' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'sastra-essential-addons-for-elementor' ),
                    'label_off'    => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor' ),
                    'return_value' => 'yes',
                    'default'      => 'no',
                    'separator' => 'before',
                ]
            );
        } else {
            $this->add_control(
                'tmpcoder_product_grid_wishlist',
                [
                    // Translators: %s is the icon.
                    'label' => sprintf( __( 'Wishlist? %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
                    'type' => Controls_Manager::SWITCHER,
                    'classes' => 'tmpcoder-pro-control no-distance',
                    'separator' => 'before',
                ]
            );
        }


        $this->add_control(
            'show_icon',
            [
                'label' => esc_html__( 'Show Icon', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                // 'separator' => 'before',
                'condition'    => [
                    'tmpcoder_product_grid_wishlist' => 'yes',
                ],
            ]   
        );

        $this->add_control(
            'show_text',
            [
                'label' => esc_html__( 'Show Text', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition'    => [
                    'tmpcoder_product_grid_wishlist' => 'yes',
                ],
            ]   
        );

        $this->add_control(
            'add_to_wishlist_text',
            [
                'label' => esc_html__( 'Add Text', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Add to Wishlist',
                'condition' => [
                    'tmpcoder_product_grid_wishlist' => 'yes',
                    // 'show_text' => 'yes',    
                ],
            ]
        );

        $this->add_control(
            'remove_from_wishlist_text',
            [
                'label' => esc_html__( 'Remove Text', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Remove from Wishlist',
                'condition' => [
                    'tmpcoder_product_grid_wishlist' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function init_content_addtocart_controls()
    {
        $this->start_controls_section(
            'tmpcoder_product_grid_add_to_cart_section',
            [
                'label' => esc_html__('Add To Cart', 'sastra-essential-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'show_add_to_cart_custom_text',
            [
                'label'        => __('Custom text', 'sastra-essential-addons-for-elementor'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default'      => '',
            ]
        );

        $this->add_control(
            'add_to_cart_simple_product_button_text',
            [
                'label' => esc_html__('Simple Product', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default' => esc_html__('Buy Now', 'sastra-essential-addons-for-elementor'),
                'condition' => [
                    'show_add_to_cart_custom_text' => 'true',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );
        $this->add_control(
            'add_to_cart_variable_product_button_text',
            [
                'label' => esc_html__('Variable Product', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default' => esc_html__('Select options', 'sastra-essential-addons-for-elementor'),
                'condition' => [
                    'show_add_to_cart_custom_text' => 'true',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );
        $this->add_control(
            'add_to_cart_grouped_product_button_text',
            [
                'label' => esc_html__('Grouped Product', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default' => esc_html__('View products', 'sastra-essential-addons-for-elementor'),
                'condition' => [
                    'show_add_to_cart_custom_text' => 'true',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );
        $this->add_control(
            'add_to_cart_external_product_button_text',
            [
                'label' => esc_html__('External Product', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default' => esc_html__('Buy Now', 'sastra-essential-addons-for-elementor'),
                'condition' => [
                    'show_add_to_cart_custom_text' => 'true',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );
        $this->add_control(
            'add_to_cart_default_product_button_text',
            [
                'label' => esc_html__('Default Product', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default' => esc_html__('Read More', 'sastra-essential-addons-for-elementor'),
                'condition' => [
                    'show_add_to_cart_custom_text' => 'true',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->end_controls_section(); # end of section 'add to cart'
    }

    protected function init_content_load_more_controls()
    {
        $this->start_controls_section('tmpcoder_product_grid_load_more_section', [
            'label'      => esc_html__('Load More', 'sastra-essential-addons-for-elementor'),
            'conditions' => [
                'terms' => [
                    [
                        'relation' => 'or',
                        'terms'    => [
                            [
                                'name'     => 'tmpcoder_product_grid_layout',
                                'operator' => 'in',
                                'value'    => [ 'masonry' ],
                            ],
                            [
                                'name'     => 'show_pagination',
                                'operator' => '!=',
                                'value'    => 'true'
                            ],
                        ]
                    ],
                    [
                        'name'     => 'post_type',
                        'operator' => '!==',
                        'value'    => 'source_archive'
                    ]

                ],
            ],
        ]);

        $this->add_control(
            'show_load_more',
            [
                'label'   => esc_html__( 'Load More', 'sastra-essential-addons-for-elementor' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'no' => [
                        'title' => esc_html__( 'Disable', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-ban',
                    ],
                    'true' => [
                        'title' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-button',
                    ],
                    'infinity' => [
                        'title' => esc_html__( 'Infinity Scroll', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-image-box',
                    ],
                ],
                'default'   => 'no',
                'toggle'    => false,
            ]
        );

        $this->add_control(
            'load_more_infinityscroll_offset',
            [
                'label'       => esc_html__('Scroll Offset (px)', 'sastra-essential-addons-for-elementor'),
                'type'        => Controls_Manager::NUMBER,
                'dynamic'     => [ 'active' => false ],
                'label_block' => false,
                'default'     => '-200',
                'description' => esc_html__('Set the position of loading to the viewport before it ends from view', 'sastra-essential-addons-for-elementor'),
                'condition'   => [
                    'show_load_more' => 'infinity',
                ],
            ]
        );

        $this->add_control('show_load_more_text', [
            'label'       => esc_html__('Label Text', 'sastra-essential-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'label_block' => false,
            'default'     => esc_html__('Load More', 'sastra-essential-addons-for-elementor'),
            'ai'          => [ 'active' => false ],
            'condition'   => [
                'show_load_more' => 'true',
            ],
        ]);

        $this->end_controls_section(); # end of section 'Load More'
    }

    protected function init_style_product_controls()
    {
        $this->start_controls_section(
            'tmpcoder_product_grid_styles',
            [
                'label' => esc_html__('Products', 'sastra-essential-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_grid_content_alignment',
            [
                'label' => __('Alignment', 'sastra-essential-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid:not(.list) .woocommerce ul.products li.product' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid:not(.list) .woocommerce ul.products li.product .star-rating' => 'margin-{{VALUE}}: 0;',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => '!=',
                            'value' => [
                                'list',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => 'in',
                            'value' => [
                                'tmpcoder-product-default',
                                'tmpcoder-product-simple',
                                'tmpcoder-product-reveal',
                                'tmpcoder-product-overlay',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_background_color',
            [
                'label' => esc_html__('Content Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product, {{WRAPPER}} .tmpcoder-product-grid .icons-wrap.block-box-style' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product.tmpcoder-product-list-preset-4 .product-details-wrap' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product.tmpcoder-product-list-preset-3, {{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product.tmpcoder-product-list-preset-4'
                    => 'background-color: transparent;',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => 'in',
                            'value' => [
                                'grid',
                                'list',
                                'masonry',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_list_style_preset',
                            'operator' => '!=',
                            'value' => [
                                'tmpcoder-product-list-preset-3',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_border_color',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ada8a8',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .price-wrap, {{WRAPPER}} .tmpcoder-product-grid .title-wrap' => 'border-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => '!in',
                            'value' => [
                                'grid',
                                'masonry',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_list_style_preset',
                            'operator' => '==',
                            'value' => 'tmpcoder-product-list-preset-3',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_peoduct_grid_padding',
            [
                'label' => __('Padding', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => '!=',
                            'value' => [
                                'list',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => 'in',
                            'value' => [
                                'tmpcoder-product-default',
                                'tmpcoder-product-simple',
                                'tmpcoder-product-reveal',
                                'tmpcoder-product-overlay',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->start_controls_tabs('tmpcoder_product_grid_tabs', [
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' => 'tmpcoder_product_grid_layout',
                        'operator' => 'in',
                        'value' => [
                            'grid',
                            'mesonry',
                        ]
                    ],
                    [
                        'name' => 'tmpcoder_product_list_style_preset',
                        'operator' => '!in',
                        'value' => [
                            'tmpcoder-product-list-preset-3',
                            'tmpcoder-product-list-preset-4',
                        ]
                    ]
                ]
            ],
        ]);

        $this->start_controls_tab('tmpcoder_product_grid_tabs_normal', ['label' => esc_html__('Normal', 'sastra-essential-addons-for-elementor')]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tmpcoder_peoduct_grid_border',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#eee',
                    ],
                ],
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product',
                'condition' => [
                    'tmpcoder_product_grid_style_preset' => [
                        'tmpcoder-product-default',
                        'tmpcoder-product-simple',
                        'tmpcoder-product-overlay',
                        'tmpcoder-product-preset-5',
                        'tmpcoder-product-preset-6',
                        'tmpcoder-product-preset-7',
                        'tmpcoder-product-preset-8',
                    ]
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tmpcoder_peoduct_grid_shadow',
                'label' => __('Box Shadow', 'sastra-essential-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product',
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab('tmpcoder_product_grid_hover_styles', ['label' => esc_html__('Hover', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_product_grid_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'tmpcoder_peoduct_grid_border_border!' => '',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tmpcoder_product_grid_box_shadow_hover',
                'label' => __('Box Shadow', 'sastra-essential-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'tmpcoder_peoduct_grid_border_radius',
            [
                'label' => esc_html__('Border Radius', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product woocommerce-loop-product__link img' => 'border-radius: {{TOP}}px {{RIGHT}}px 0 0;',
                    '{{WRAPPER}} .tmpcoder-product-grid.list .woocommerce ul.products li.product .woocommerce-loop-product__link img' => 'border-radius: {{TOP}}px 0 0 {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_grid_image_width',
            [
                'label' => esc_html__('Image Width(%)', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid.list .tmpcoder-product-wrap .product-image-wrap' => 'width: {{SIZE}}%;',
                ],
                'condition' => [
                    'tmpcoder_product_grid_layout' => 'list',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_details_heading',
            [
                'label' => __('Product Details', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => 'in',
                            'value' => [
                                'grid',
                                'list',
                                'masonry',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => '!in',
                            'value' => [
                                'tmpcoder-product-default',
                                'tmpcoder-product-simple',
                                'tmpcoder-product-reveal',
                                'tmpcoder-product-overlay',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_grid_details_alignment',
            [
                'label' => __('Alignment', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .product-details-wrap' => 'text-align: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => '!=',
                            'value' => [
                                'list',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => '!in',
                            'value' => [
                                'tmpcoder-product-default',
                                'tmpcoder-product-simple',
                                'tmpcoder-product-reveal',
                                'tmpcoder-product-overlay',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_grid_inner_padding',
            [
                'label' => __('Padding', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid.grid .tmpcoder-product-wrap .product-details-wrap, {{WRAPPER}} .tmpcoder-product-grid.masonry .tmpcoder-product-wrap .product-details-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => '!=',
                            'value' => [
                                'list',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => '!in',
                            'value' => [
                                'tmpcoder-product-default',
                                'tmpcoder-product-simple',
                                'tmpcoder-product-reveal',
                                'tmpcoder-product-overlay',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_list_padding',
            [
                'label' => esc_html__('Padding (PX)', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid.list .tmpcoder-product-list-preset-1 .tmpcoder-product-wrap .product-details-wrap, {{WRAPPER}} .tmpcoder-product-grid.list .tmpcoder-product-list-preset-4 .tmpcoder-product-wrap .product-details-wrap' => 'padding: {{SIZE}}px;',
                    '{{WRAPPER}} .tmpcoder-product-grid.list .tmpcoder-product-list-preset-2 .tmpcoder-product-wrap' => 'padding: {{SIZE}}px;',
                    '{{WRAPPER}} .tmpcoder-product-grid.list .tmpcoder-product-list-preset-2 .tmpcoder-product-wrap .product-details-wrap' => 'padding: 0 0 0 {{SIZE}}px;',
                    '{{WRAPPER}} .tmpcoder-product-grid.list .tmpcoder-product-list-preset-3 .tmpcoder-product-wrap .product-details-wrap' => 'padding: 0 0 0 {{SIZE}}px;',
                ],
                'condition' => [
                    'tmpcoder_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_list_content_width',
            [
                'label' => esc_html__('Width (%)', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid.list .tmpcoder-product-wrap .product-details-wrap' => 'width: {{SIZE}}%;',
                ],
                'condition' => [
                    'tmpcoder_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function init_style_color_typography_controls()
    {

        $this->start_controls_section(
            'tmpcoder_section_product_grid_typography',
            [
                'label' => esc_html__('Color &amp; Typography', 'sastra-essential-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_product_title_heading',
            [
                'label' => __('Product Title', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_product_title_color',
            [
                'label' => esc_html__('Product Title Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .woocommerce-loop-product__title, {{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .tmpcoder-product-title h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_grid_product_title_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .woocommerce-loop-product__title, {{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .tmpcoder-product-title h2',
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_product_price_heading',
            [
                'label' => __('Product Price', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_product_price_color',
            [
                'label' => esc_html__('Price Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .price, {{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .tmpcoder-product-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_product_sale_price_color',
            [
                'label' => esc_html__('Sale Price Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .price ins, {{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .tmpcoder-product-price ins' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_grid_product_price_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .price,{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .tmpcoder-product-price',
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_product_rating_heading',
            [
                'label' => __('Star Rating', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_product_rating_color',
            [
                'label' => esc_html__('Rating Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2b01e',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce .star-rating::before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce .star-rating span::before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce .tmpcoder-star-rating' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_grid_product_rating_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .star-rating,{{WRAPPER}} .tmpcoder-product-grid .woocommerce .tmpcoder-star-rating',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => '!in',
                            'value' => [
                                'tmpcoder-product-preset-5',
                                'tmpcoder-product-preset-6',
                                'tmpcoder-product-preset-7',
                                'tmpcoder-product-preset-8',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => '!==',
                            'value' => 'list'
                        ]
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_grid_product_rating_size',
            [
                'label' => esc_html__('Icon Size', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .star-rating' => 'font-size: {{SIZE}}px!important;',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => 'in',
                            'value' => [
                                'tmpcoder-product-preset-5',
                                'tmpcoder-product-preset-6',
                                'tmpcoder-product-preset-7',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => '==',
                            'value' => 'list'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_product_desc_heading',
            [
                'label' => __('Product Description', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'tmpcoder_product_grid_layout' => 'list',
                    'tmpcoder_product_grid_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_product_desc_color',
            [
                'label' => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .tmpcoder-product-excerpt' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'tmpcoder_product_grid_layout' => 'list',
                    'tmpcoder_product_grid_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_grid_product_desc_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce ul.products li.product .tmpcoder-product-excerpt',
                'condition' => [
                    'tmpcoder_product_grid_layout' => 'list',
                    'tmpcoder_product_grid_excerpt' => 'yes',
                ],
            ]
        );

        do_action( 'tmpcoder/product_grid/style_settings/control/after_color_typography', $this );

        $this->end_controls_section();
    }

    /**
     * Load More Button Style
     *
     */
    function load_more_button_style()
    {
        $this->start_controls_section(
            'tmpcoder_section_load_more_btn',
            [
                'label' => __('Load More', 'sastra-essential-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_load_more' => ['yes', '1', 'true', 'infinity'],
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_post_grid_load_more_btn_padding',
            [
                'label' => esc_html__('Padding', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_post_grid_load_more_btn_margin',
            [
                'label' => esc_html__('Margin', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-load-more-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_post_grid_load_more_btn_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-load-more-button',
            ]
        );

        $this->start_controls_tabs('tmpcoder_post_grid_load_more_btn_tabs');

        // Normal State Tab
        $this->start_controls_tab('tmpcoder_post_grid_load_more_btn_normal', ['label' => esc_html__('Normal', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_post_grid_load_more_btn_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                // 'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-load-more-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_post_grid_load_more_btn_normal_loader_color',
            [
                'label' => esc_html__('Loader Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5729d9',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-load-more-button .button__loader' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_cta_btn_normal_bg_color',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                // 'default' => '#5729d9',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-load-more-button' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tmpcoder_post_grid_load_more_btn_normal_border',
                'label' => esc_html__('Border', 'sastra-essential-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .tmpcoder-load-more-button',
            ]
        );

        $this->add_control(
            'tmpcoder_post_grid_load_more_btn_border_radius',
            [
                'label' => esc_html__('Border Radius', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-load-more-button' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tmpcoder_post_grid_load_more_btn_shadow',
                'selector' => '{{WRAPPER}} .tmpcoder-load-more-button',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab('tmpcoder_post_grid_load_more_btn_hover', ['label' => esc_html__('Hover', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_post_grid_load_more_btn_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                // 'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-load-more-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_post_grid_load_more_btn_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                // 'default' => '#27bdbd',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-load-more-button:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_post_grid_load_more_btn_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-load-more-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]

        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tmpcoder_post_grid_load_more_btn_hover_shadow',
                'selector' => '{{WRAPPER}} .tmpcoder-load-more-button:hover',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tmpcoder_post_grid_loadmore_button_alignment',
            [
                'label' => __('Button Alignment', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-load-more-button-wrap' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function sale_badge_style(){
        $this->start_controls_section(
            'tmpcoder_section_product_grid_sale_badge_style',
            [
                'label' => esc_html__('Badges', 'sastra-essential-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'tmpcoder_show_product_sale_badge' => 'yes'
                ]
            ]
        );

        // stock out badge
        $this->add_control(
            'tmpcoder_product_grid_sale_out_badge_heading',
            [
                'label' => __('Sale Badge', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_sale_badge_color',
            [
                'label' => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .tmpcoder-onsale' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_sale_badge_background',
            [
                'label' => esc_html__('Background', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff2a13',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .tmpcoder-onsale' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce ul.products li.product .tmpcoder-onsale:not(.outofstock).sale-preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_grid_sale_badge_typography',
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .tmpcoder-onsale:not(.outofstock)',
            ]
        );

        // stock out badge
        $this->add_control(
            'tmpcoder_product_grid_stock_out_badge_heading',
            [
                'label' => __('Stock Out', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_stock_out_badge_color',
            [
                'label' => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .tmpcoder-onsale.outofstock' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_stock_out_badge_background',
            [
                'label' => esc_html__('Background', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff2a13',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .tmpcoder-onsale.outofstock' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce ul.products li.product .tmpcoder-onsale.outofstock.sale-preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_grid_stock_out_badge_typography',
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .tmpcoder-onsale.outofstock',
            ]
        );

        $this->end_controls_section();
    }

    protected function init_style_addtocart_controls()
    {
        // add to cart button
        $this->start_controls_section(
            'tmpcoder_section_product_grid_add_to_cart_styles',
            [
                'label' => esc_html__('Add To Cart', 'sastra-essential-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'tmpcoder_product_grid_style_preset!' => [
                        'tmpcoder-product-preset-5',
                        'tmpcoder-product-preset-6',
                        'tmpcoder-product-preset-7',
                        'tmpcoder-product-preset-8',
                    ],
                    'tmpcoder_product_grid_layout!' => 'list',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_add_to_cart_padding',
            [
                'label' => __('Padding', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button,
                    {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button,
                    {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .product-link,
                    {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist,
                    {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_add_to_cart_radius',
            [
                'label' => __('Radius', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button,
                    {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button,
                    {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .product-link,
                    {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist,
                    {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'tmpcoder_product_grid_add_to_cart_is_gradient_bg',
            [
                'label' => __('Use Gradient Background', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'sastra-essential-addons-for-elementor'),
                'label_off' => __('No', 'sastra-essential-addons-for-elementor'),
                'return_value' => 'yes',
            ]
        );

        $this->start_controls_tabs('tmpcoder_product_grid_add_to_cart_style_tabs');

        $this->start_controls_tab('normal', ['label' => esc_html__('Normal', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_product_grid_add_to_cart_color',
            [
                'label' => esc_html__('Button Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button, 
                    {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .product-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tmpcoder_product_grid_add_to_cart_gradient_background',
                'label' => __('Background', 'sastra-essential-addons-for-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button,
                {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button,
                {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .product-link,
                {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist,
                {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart',
                'condition' => [
                    'tmpcoder_product_grid_add_to_cart_is_gradient_bg' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_add_to_cart_background',
            [
                'label' => esc_html__('Background', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button, 
                    {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .product-link' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'tmpcoder_product_grid_add_to_cart_is_gradient_bg' => ''
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tmpcoder_product_grid_add_to_cart_border',
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button, 
                {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button, 
                {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .product-link, 
                {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist, 
                {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_grid_add_to_cart_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button,
                {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button',
                'condition' => [
                    'tmpcoder_product_grid_style_preset' => ['tmpcoder-product-default', 'tmpcoder-product-simple'],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('tmpcoder_product_grid_add_to_cart_hover_styles', ['label' => esc_html__('Hover', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_product_grid_add_to_cart_hover_color',
            [
                'label' => esc_html__('Button Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button:hover,
                    {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tmpcoder_product_grid_add_to_cart_hover_gradient_background',
                'label' => __('Background', 'sastra-essential-addons-for-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button:hover,
                {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button:hover,
                {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover,
                {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist:hover,
                {{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover',
                'condition' => [
                    'tmpcoder_product_grid_add_to_cart_is_gradient_bg' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'tmpcoder_product_grid_add_to_cart_hover_background',
            [
                'label' => esc_html__('Background', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button:hover,
                    {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'tmpcoder_product_grid_add_to_cart_is_gradient_bg' => '',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_add_to_cart_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button:hover,
                    {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid.tmpcoder-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function tmpcoder_init_style_addtowishlist_controls()
    {
        $this->start_controls_section(
            'section_wishlist_button_styles',
            [
                'label' => esc_html__( 'Add to Wishlist', 'sastra-essential-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'tmpcoder_product_grid_wishlist' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_btn_styles' );

        $this->start_controls_tab(
            'tab_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'wishlist_btn_color',
            [
                'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-add i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-add svg' => 'fill: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'wishlist_btn_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .tmpcoder-wishlist-add, {{WRAPPER}} .tmpcoder-wishlist-remove',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-wishlist-add span, {{WRAPPER}} .tmpcoder-wishlist-add i, .tmpcoder-wishlist-remove span, {{WRAPPER}} .tmpcoder-wishlist-remove i',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => '16',
                            'unit' => 'px',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'btn_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .tmpcoder-wishlist-add span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .tmpcoder-wishlist-add i' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove span' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove i' => 'transition-duration: {{VALUE}}s'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-add:hover svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-add:hover span' => 'color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow_hr',
                'selector' => '{{WRAPPER}} .tmpcoder-wishlist-add:hover, WRAPPER}} .tmpcoder-wishlist-remove:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_remove_btn',
            [
                'label' => esc_html__( 'Remove', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'remove_btn_text_color',
            [
                'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4400',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-remove span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove:hover span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove:hover svg' => 'fill: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'remove_btn_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF4F40',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-remove' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'remove_btn_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFF',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-remove' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 15,
                    'bottom' => 5,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ), 
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    // '{{WRAPPER}} .tmpcoder-wishlist-add' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    // '{{WRAPPER}} .tmpcoder-wishlist-remove' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-grid-item-wishlist-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'button_border_type',
            [
                'label' => esc_html__( 'Border Type', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
                    'solid' => esc_html__( 'Solid', 'sastra-essential-addons-for-elementor' ),
                    'double' => esc_html__( 'Double', 'sastra-essential-addons-for-elementor' ),
                    'dotted' => esc_html__( 'Dotted', 'sastra-essential-addons-for-elementor' ),
                    'dashed' => esc_html__( 'Dashed', 'sastra-essential-addons-for-elementor' ),
                    'groove' => esc_html__( 'Groove', 'sastra-essential-addons-for-elementor' ),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove' => 'border-style: {{VALUE}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_border_width',
            [
                'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'button_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-wishlist-add' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-wishlist-remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function tmpcoder_product_badges()
    {
        $this->start_controls_section(
            'tmpcoder_section_product_badges',
            [
                'label' => esc_html__('Sale / Stock Out Badge', 'sastra-essential-addons-for-elementor'),
                'condition' => [
                    'tmpcoder_show_product_sale_badge' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'tmpcoder_product_sale_badge_preset',
            [
                'label' => esc_html__('Style', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'sale-preset-1',
                'options' => [
                    'sale-preset-1' => esc_html__('Preset 1', 'sastra-essential-addons-for-elementor'),
                    'sale-preset-2' => esc_html__('Preset 2', 'sastra-essential-addons-for-elementor'),
                    'sale-preset-3' => esc_html__('Preset 3', 'sastra-essential-addons-for-elementor'),
                    'sale-preset-4' => esc_html__('Preset 4', 'sastra-essential-addons-for-elementor'),
                    'sale-preset-5' => esc_html__('Preset 5', 'sastra-essential-addons-for-elementor'),

                ],
                'condition' => [
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_sale_badge_alignment',
            [
                'label' => __('Alignment', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'condition' => [
                    'tmpcoder_product_grid_layout!' => 'list',
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-default',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_sale_text',
            [
                'label'       => esc_html__( 'Sale Text', 'sastra-essential-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'separator' => 'before',
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_stockout_text',
            [
                'label'   => esc_html__( 'Stock Out Text', 'sastra-essential-addons-for-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Out of stock', 'sastra-essential-addons-for-elementor' ),
                'ai'      => [ 'active' => false ],
            ]
        );

        $this->end_controls_section();
    }

    protected function tmpcoder_product_action_buttons()
    {
        $this->start_controls_section(
            'tmpcoder_section_product_action_buttons',
            [
                'label' => esc_html__('Buttons', 'sastra-essential-addons-for-elementor'),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => 'in',
                            'value' => [
                                'tmpcoder-product-preset-5',
                                'tmpcoder-product-preset-6',
                                'tmpcoder-product-preset-7',
                                'tmpcoder-product-preset-8',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => '==',
                            'value' => 'list'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_quick_view',
            [
                'label' => esc_html__('Show Quick view?', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tmpcoder_product_quick_view_title_tag',
            [
                'label'       => __('Quick view Title Tag', 'sastra-essential-addons-for-elementor'),
                'label_block' => true,
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'h1' => [
                        'title' => esc_html__( 'H1', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h1',
                    ],
                    'h2' => [
                        'title' => esc_html__( 'H2', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h2',
                    ],
                    'h3' => [
                        'title' => esc_html__( 'H3', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h3',
                    ],
                    'h4' => [
                        'title' => esc_html__( 'H4', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h4',
                    ],
                    'h5' => [
                        'title' => esc_html__( 'H5', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h5',
                    ],
                    'h6' => [
                        'title' => esc_html__( 'H6', 'sastra-essential-addons-for-elementor' ),
                        'icon'  => 'eicon-editor-h6',
                    ],
                    'div' => [
                        'title' => esc_html__( 'Div', 'sastra-essential-addons-for-elementor' ),
                        'text'  => 'div',
                    ],
                    'span' => [
                        'title' => esc_html__( 'Span', 'sastra-essential-addons-for-elementor' ),
                        'text'  => 'span',
                    ],
                    'p' => [
                        'title' => esc_html__( 'P', 'sastra-essential-addons-for-elementor' ),
                        'text'  => 'P',
                    ],
                ],
                'default'   => 'h1',
                'separator' => 'after',
                'toggle'    => false,
                'condition' => [
                    'tmpcoder_product_grid_quick_view' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_action_buttons_preset',
            [
                'label' => esc_html__('Style Preset', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'details-block-style',
                'options' => [
                    'details-block-style' => esc_html__('Preset 1', 'sastra-essential-addons-for-elementor'),
                    'details-block-style-2' => esc_html__('Preset 2', 'sastra-essential-addons-for-elementor'),
                ],
                'condition' => [
                    'tmpcoder_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function tmpcoder_product_action_buttons_style()
    {
        $this->start_controls_section(
            'tmpcoder_section_product_grid_buttons_styles',
            [
                'label' => esc_html__('Button', 'sastra-essential-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => 'in',
                            'value' => [
                                'tmpcoder-product-preset-5',
                                'tmpcoder-product-preset-6',
                                'tmpcoder-product-preset-7',
                                'tmpcoder-product-preset-8',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => '==',
                            'value' => 'list'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_buttons_preset5_background',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap.block-style' => 'background: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => 'in',
                            'value' => [
                                'grid',
                                'masonry',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => '==',
                            'value' => 'tmpcoder-product-preset-5',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_buttons_icon_size',
            [
                'label' => esc_html__('Icons Size', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid.list .tmpcoder-product-wrap .icons-wrap li a i' => 'font-size: {{SIZE}}px;',
                ],
                'condition' => [
                    'tmpcoder_product_grid_layout' => 'list',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_grid_buttons_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .icons-wrap li.add-to-cart a',
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_buttons_preset5_border_color',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .icons-wrap.block-style li' => 'border-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => 'in',
                            'value' => [
                                'grid',
                                'masonry',
                            ],
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => '==',
                            'value' => 'tmpcoder-product-preset-5',
                        ],
                    ],
                ],
            ]
        );

        $this->start_controls_tabs('tmpcoder_product_grid_buttons_style_tabs');

        $this->start_controls_tab('tmpcoder_product_grid_buttons_style_tabs_normal', ['label' => esc_html__('Normal', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_product_grid_buttons_color',
            [
                'label' => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-compare-icon' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_buttons_background',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap li a' => 'background-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => '!==',
                            'value' => 'tmpcoder-product-preset-5'
                        ],
                        [
                            'name' => 'tmpcoder_product_grid_layout',
                            'operator' => '==',
                            'value' => 'list'
                        ]
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tmpcoder_product_grid_buttons_border',
                'selector' => '{{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button,
                {{WRAPPER}} .tmpcoder-product-grid .woocommerce li.product .button.add_to_cart_button, 
                {{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap li a',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => '!==',
                            'value' => 'tmpcoder-product-preset-5'
                        ],
                        [
                            'name' => 'tmpcoder_product_action_buttons_preset',
                            'operator' => '==',
                            'value' => 'details-block-style-2'
                        ]
                    ],
                ],
            ]
        );
        $this->add_control(
            'tmpcoder_product_grid_buttons_border_radius',
            [
                'label' => esc_html__('Border Radius', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap:not(.details-block-style-2) li a' => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap.details-block-style-2 li:only-child a' => 'border-radius: {{SIZE}}px!important;',
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap.details-block-style-2 li:first-child a' => 'border-radius: {{SIZE}}px 0 0 {{SIZE}}px;',
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap.details-block-style-2 li:last-child a' => 'border-radius: 0 {{SIZE}}px {{SIZE}}px 0;',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_buttons_top_spacing',
            [
                'label' => esc_html__('Top Spacing', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap' => 'margin-top: {{SIZE}}px;',
                ],
                'condition' => [
                    'tmpcoder_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('tmpcoder_product_grid_buttons_hover_styles', ['label' => esc_html__('Hover', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_product_grid_buttons_hover_color',
            [
                'label' => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F5EAFF',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_buttons_hover_background',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap li a:hover' => 'background-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'tmpcoder_product_grid_style_preset',
                            'operator' => '!==',
                            'value' => 'tmpcoder-product-preset-5'
                        ],
                        [
                            'name' => 'tmpcoder_product_action_buttons_preset',
                            'operator' => '!==',
                            'value' => 'details-block-style-2'
                        ]
                    ]
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_buttons_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-product-wrap .icons-wrap li a:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'tmpcoder_product_grid_buttons_border_border!' => '',
                    'tmpcoder_product_grid_style_preset!' => 'tmpcoder-product-preset-5',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function tmpcoder_product_pagination()
    {

        $this->start_controls_section(
            'tmpcoder_product_grid_pagination_section',
            [
                'label' => __('Pagination', 'sastra-essential-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'tmpcoder_product_grid_layout' => ['grid', 'list'],
                    'show_load_more!'          => 'true',
                    'post_type!'               => 'source_archive',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'        => __('pagination', 'sastra-essential-addons-for-elementor'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'sastra-essential-addons-for-elementor'),
                'label_off'    => __('Hide', 'sastra-essential-addons-for-elementor'),
                'return_value' => 'true',
                'default'      => '',
            ]
        );

        $this->add_control(
            'pagination_prev_label',
            [
                'label'     => __('Previous Label', 'sastra-essential-addons-for-elementor'),
                'default'   => __('←', 'sastra-essential-addons-for-elementor'),
                'condition' => [
                    'show_pagination' => 'true',
                ]
            ]
        );

        $this->add_control(
            'pagination_next_label',
            [
                'label'     => __('Next Label', 'sastra-essential-addons-for-elementor'),
                'default'   => __('→', 'sastra-essential-addons-for-elementor'),
                'condition' => [
                    'show_pagination' => 'true',
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function tmpcoder_product_pagination_style()
    {
        $this->start_controls_section(
            'tmpcoder_section_product_pagination_style',
            [
                'label' => __('Pagination', 'sastra-essential-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'terms'    => [
                                [
                                    'name'     => 'show_pagination',
                                    'operator' => ' === ',
                                    'value'    => 'true'
                                ],
                                [
                                    'name'     => 'tmpcoder_product_grid_layout',
                                    'operator' => 'in',
                                    'value'    => ['grid', 'list']
                                ]
                            ]
                        ],
                        [
                            'name'     => 'post_type',
                            'operator' => ' === ',
                            'value'    => 'source_archive'
                        ]

                    ]
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_grid_pagination_alignment',
            [
                'label' => __('Alignment', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_grid_pagination_top_spacing',
            [
                'label' => esc_html__('Top Spacing', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination' => 'margin-top: {{SIZE}}px;',
                    '{{WRAPPER}} {{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination' => 'margin-top: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_grid_pagination_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-woo-pagination,
                                {{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination,
                                {{WRAPPER}} .tmpcoder-woo-pagination ul li a',
            ]
        );

        $this->start_controls_tabs('tmpcoder_product_grid_pagination_tabs');

        // Normal State Tab
        $this->start_controls_tab('tmpcoder_product_grid_pagination_normal', ['label' => esc_html__('Normal', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_product_grid_pagination_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#2F436C',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_pagination_normal_bg_color',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination a' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tmpcoder_product_grid_pagination_normal_border',
                'label' => esc_html__('Border', 'sastra-essential-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .tmpcoder-woo-pagination a, {{WRAPPER}} .tmpcoder-woo-pagination span, 
                {{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination a',
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab('tmpcoder_product_grid_pagination_hover', ['label' => esc_html__('Hover', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_product_grid_pagination_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_pagination_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination a:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination a:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_pagination_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination a:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination a:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'tmpcoder_product_grid_pagination_normal_border_border!' => '',
                ]
            ]

        );
        $this->end_controls_tab();

        // Active State Tab
        $this->start_controls_tab('tmpcoder_product_grid_pagination_active', ['label' => esc_html__('Active', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_product_grid_pagination_hover_text_active',
            [
                'label' => esc_html__('Text Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination .current' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination .current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_pagination_active_bg_color',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination .current' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination .current' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_grid_pagination_active_border_color',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination .current' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid-pagination .woocommerce-pagination .current' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'tmpcoder_product_grid_pagination_normal_border_border!' => '',
                ]
            ]

        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'tmpcoder_product_grid_pagination_border_radius',
            [
                'label' => esc_html__('Border Radius', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-woo-pagination li > *' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        // Pagination Loader
        $this->add_control(
            'tmpcoder_product_pagination_loader',
            [
                'label' => __('Loader', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tmpcoder_product_pagination_loader_color',
            [
                'label' => __('Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}}.tmpcoder-product-loader::after' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function tmpcoder_product_view_popup_style()
    {
        $this->start_controls_section(
            'tmpcoder_product_popup',
            [
                'label' => __('Popup', 'sastra-essential-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_title',
            [
                'label' => __('Title', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_popup_title_typography',
                'label' => __('Typography', 'sastra-essential-addons-for-elementor'),
                'selector' => '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product .product_title',
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_title_color',
            [
                'label' => __('Title Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#252525',
                'selectors' => [
//                    '{{WRAPPER}} .tmpcoder-product-popup .tmpcoder-product-quick-view-title.product_title.entry-title' => 'color: {{VALUE}};',
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product .product_title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_price',
            [
                'label' => __('Price', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_popup_price_typography',
                'label' => __('Typography', 'sastra-essential-addons-for-elementor'),
                'selector' => '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product .price',
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_price_color',
            [
                'label' => __('Price Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0242e4',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product .price' => 'color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_sale_price_color',
            [
                'label' => __('Sale Price Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff2a13',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product .price ins' => 'color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_content',
            [
                'label' => __('Content', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tmpcoder_product_popup_content_typography',
                'label' => __('Typography', 'sastra-essential-addons-for-elementor'),
                'selector' => '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product .woocommerce-product-details__short-description',
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_content_color',
            [
                'label' => __('Content Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#707070',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_review_link_color',
            [
                'label' => __('Review Link Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ccc',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .product_meta a.woocommerce-review-link, .tmpcoder-popup-details-render .elementor-element-{{ID}} .product_meta a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'tmpcoder_product_popup_review_link_hover',
            [
                'label' => __('Review Link Hover', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ccc',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} a.woocommerce-review-link:hover, .tmpcoder-popup-details-render .elementor-element-{{ID}} .product_meta a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_table_border_color',
            [
                'label' => __('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ccc',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product table tbody tr, {{WRAPPER}} .tmpcoder-product-popup.woocommerce div.product .product_meta' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        // Sale
        $this->add_control(
            'tmpcoder_product_popup_sale_style',
            [
                'label' => __('Sale', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tmpcoder_product_popup_sale_typo',
                'label'    => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
                'selector' => '.tmpcoder-popup-details-render .elementor-element-{{ID}} .tmpcoder-onsale',
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_sale_color',
            [
                'label'     => __( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .tmpcoder-onsale' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
        $this->add_control(
            'tmpcoder_product_popup_sale_bg_color',
            [
                'label'     => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .tmpcoder-onsale' => 'background-color: {{VALUE}}!important;',
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .tmpcoder-onsale:not(.outofstock).sale-preset-4:after'        => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
                ],
            ]
        );

        // Quantity
        $this->add_control(
            'tmpcoder_product_popup_quantity',
            [
                'label'     => __( 'Quantity', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tmpcoder_product_popup_quantity_typo',
                'label'    => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
                'selector' => '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product form.cart div.quantity .qty, {{WRAPPER}} .tmpcoder-product-popup.woocommerce div.product form.cart div.quantity > a',
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_quantity_color',
            [
                'label'     => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product form.cart div.quantity .qty, {{WRAPPER}} .tmpcoder-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .tmpcoder-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_quantity_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product form.cart div.quantity .qty, {{WRAPPER}} .tmpcoder-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .tmpcoder-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_quantity_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product form.cart div.quantity .qty, {{WRAPPER}} .tmpcoder-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .tmpcoder-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'border-color: {{VALUE}};',
                    // OceanWP
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} div.product form.cart div.quantity .qty:focus'                                                                                                                                                                         => 'border-color: {{VALUE}};',
                ],
            ]
        );

        // Cart Button
        $this->add_control(
            'tmpcoder_product_popup_cart_button',
            [
                'label' => __('Cart Button', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tmpcoder_product_popup_cart_button_typo',
                'label'    => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
                'selector' => '.tmpcoder-popup-details-render .elementor-element-{{ID}} .button, .tmpcoder-popup-details-render .elementor-element-{{ID}} button.button.alt',
            ]
        );

        $this->start_controls_tabs('tmpcoder_product_popup_cart_button_style_tabs');

        $this->start_controls_tab('tmpcoder_product_popup_cart_button_style_tabs_normal', ['label' => esc_html__('Normal', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_product_popup_cart_button_color',
            [
                'label'     => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .button, .tmpcoder-popup-details-render .elementor-element-{{ID}} button.button.alt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_cart_button_background',
            [
                'label'     => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#8040FF',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .button, .tmpcoder-popup-details-render .elementor-element-{{ID}} button.button.alt' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'tmpcoder_product_popup_cart_button_border',
                'selector' => '.tmpcoder-popup-details-render .elementor-element-{{ID}} .button, .tmpcoder-popup-details-render .elementor-element-{{ID}} button.button.alt',
            ]
        );
        $this->add_control(
            'tmpcoder_product_popup_cart_button_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .button, .tmpcoder-popup-details-render .elementor-element-{{ID}} button.button.alt' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('tmpcoder_product_popup_cart_button_hover_styles', ['label' => esc_html__('Hover', 'sastra-essential-addons-for-elementor')]);

        $this->add_control(
            'tmpcoder_product_popup_cart_button_hover_color',
            [
                'label'     => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F5EAFF',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .button:hover, .tmpcoder-popup-details-render .elementor-element-{{ID}} button.button.alt:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_cart_button_hover_background',
            [
                'label'     => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F12DE0',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .button:hover, .tmpcoder-popup-details-render .elementor-element-{{ID}} button.button.alt:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_cart_button_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .button:hover, .tmpcoder-popup-details-render .elementor-element-{{ID}} button.button.alt:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'tmpcoder_product_popup_cart_button_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // SKU
        $this->add_control(
            'tmpcoder_product_popup_sku_style',
            [
                'label' => __('SKU', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tmpcoder_product_popup_sku_typo',
                'label'    => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
                'selector' => '.tmpcoder-popup-details-render .elementor-element-{{ID}} .product_meta',
            ]
        );


        $this->add_control(
            'tmpcoder_product_popup_sku_title_color',
            [
                'label'     => __( 'Title Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .product_meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_sku_content_color',
            [
                'label'     => __( 'Content Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .product_meta .sku, .tmpcoder-popup-details-render .elementor-element-{{ID}} .product_meta a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_sku_hover_color',
            [
                'label'     => __( 'Hover Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} .product_meta a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_close_button_style',
            [
                'label'     => __( ' Close Button', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_popup_close_button_icon_size',
            [
                'label'      => __( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} button.tmpcoder-product-popup-close' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_popup_close_button_size',
            [
                'label'      => __( 'Button Size', 'sastra-essential-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} button.tmpcoder-product-popup-close' => 'max-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_close_button_color',
            [
                'label'     => __( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} button.tmpcoder-product-popup-close' => 'color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_close_button_bg',
            [
                'label'     => __( 'Background', 'sastra-essential-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} button.tmpcoder-product-popup-close' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_product_popup_close_button_border_radius',
            [
                'label'      => __( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}} button.tmpcoder-product-popup-close' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'tmpcoder_product_popup_close_button_box_shadow',
                'label'    => __( 'Box Shadow', 'sastra-essential-addons-for-elementor' ),
                'selector' => '.tmpcoder-popup-details-render .elementor-element-{{ID}} button.tmpcoder-product-popup-close',
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_product_popup_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '.tmpcoder-popup-details-render .elementor-element-{{ID}}.tmpcoder-product-popup-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'tmpcoder_product_popup_background',
                'label'    => __( 'Background', 'sastra-essential-addons-for-elementor' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '.tmpcoder-popup-details-render .elementor-element-{{ID}}.tmpcoder-product-popup-details',
                'exclude'  => [
                    'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tmpcoder_product_popup_box_shadow',
                'label' => __('Box Shadow', 'sastra-essential-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .tmpcoder-product-popup .tmpcoder-product-popup-details',
            ]
        );

        $this->end_controls_section();
    }

    function tmpcoder_customize_woo_prod_thumbnail_size( $size ) {
        $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

        return $settings['tmpcoder_product_grid_image_size_size'];
    }

    protected function render()
    {
        if (!function_exists('WC')) {
            return;
        }

        $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

        // normalize for load more fix
        $settings['layout_mode']    = $settings["tmpcoder_product_grid_layout"];
        $widget_id                  = esc_attr( $this->get_id() );
        $settings['tmpcoder_widget_id'] = $widget_id;
        $is_product_archive         = is_product_tag() || is_product_category() || is_shop() || is_product_taxonomy();

        if ( $settings['post_type'] === 'source_dynamic' && is_archive() || ! empty( $_REQUEST['post_type'] ) ) {
            $settings['posts_per_page'] = $settings['tmpcoder_product_grid_products_count'] ?: 4;
            $settings['offset']         = $settings['product_offset'];
            $args                       = HelperClass::get_query_args( $settings );
            $args                       = HelperClass::get_dynamic_args( $settings, $args );
        } else {
            $args = $this->build_product_query( $settings );
        }

        if (isset($settings['allow_order_dropdown']) && 'yes' === $settings['allow_order_dropdown']) {
            do_action('woocommerce_before_shop_loop');
        }

        $no_products_found = 0;

        if ( is_user_logged_in() ) {
            $product_purchase_type = ! empty( $settings['product_type_logged_users'] ) ? sanitize_text_field( $settings['product_type_logged_users'] ) : '';

            if (  in_array( $product_purchase_type, ['purchased', 'not-purchased'] ) ) {
                $user_ordered_products = HelperClass::tmpcoder_get_all_user_ordered_products();
                $no_products_found = empty( $user_ordered_products ) && 'purchased' === $product_purchase_type ? 1 : 0;
 
                if ( ! empty( $user_ordered_products ) && 'purchased' === $product_purchase_type ){
                    $args['post__in'] = $user_ordered_products;
                }

                if ( ! empty( $user_ordered_products ) && 'not-purchased' === $product_purchase_type ){
                    $args['post__not_in'] = $user_ordered_products;
                }
            }
        }

        if ( ! empty( $settings['tmpcoder_product_not_in'] ) ) {

            $ids_array = '';
            $slug_args = [
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'post_name__in'  => $settings[ 'tmpcoder_product_not_in' ],
                'fields'         => 'ids' 
            ];
            
            $ids_array = get_posts( $slug_args );

            if ( ! empty( $args['post__not_in'] ) ) {
                $post_not_in = array_merge( $args['post__not_in'], $ids_array);
                $args['post__not_in'] = $post_not_in;
            } else {
                $args['post__not_in'] = $ids_array;
            }
        }

        $this->is_show_custom_add_to_cart       = boolval( $settings['show_add_to_cart_custom_text'] );
        $this->simple_add_to_cart_button_text   = $settings['add_to_cart_simple_product_button_text'];
        $this->variable_add_to_cart_button_text = $settings['add_to_cart_variable_product_button_text'];
        $this->grouped_add_to_cart_button_text  = $settings['add_to_cart_grouped_product_button_text'];
        $this->external_add_to_cart_button_text = $settings['add_to_cart_external_product_button_text'];
        $this->default_add_to_cart_button_text  = $settings['add_to_cart_default_product_button_text'];

        if ( Plugin::$instance->documents->get_current() ) {
            $this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
        }
        // render dom
        $wrap_attributes = [
            'class'          => [
                "tmpcoder-product-grid",
                $settings['tmpcoder_product_grid_style_preset'],
                $settings['tmpcoder_product_grid_layout']
            ],
            'id'             => 'tmpcoder-product-grid',
            'data-widget-id' => $widget_id,
            'data-page-id'   => $this->page_id,
            'data-nonce'     => wp_create_nonce( 'tmpcoder_product_grid' ),
        ];

        // if ($settings['tmpcoder_product_grid_layout'] == 'slider') {
        //     $wrap_attributes['class'][] = 'tmpcoder-product-simple';
        // }

        // Add slider-specific data attributes
        if ( isset($settings['tmpcoder_enable_slider']) && 'yes' === $settings['tmpcoder_enable_slider'] ) {
            $wrap_attributes['class'][] = 'slider';
            $wrap_attributes['data-slider-columns'] = $settings['tmpcoder_slider_columns'];
            $wrap_attributes['data-slider-columns-tablet'] = $settings['tmpcoder_slider_columns_tablet'] ?? '2';
            $wrap_attributes['data-slider-columns-mobile'] = $settings['tmpcoder_slider_columns_mobile'] ?? '1';
            $wrap_attributes['data-slider-slides-to-scroll'] = $settings['tmpcoder_slider_slides_to_scroll'];
            $wrap_attributes['data-slider-navigation'] = $settings['tmpcoder_slider_navigation'];
            $wrap_attributes['data-slider-nav-icon'] = $settings['tmpcoder_slider_nav_icon'];
            $wrap_attributes['data-slider-pagination'] = $settings['tmpcoder_slider_pagination'];
            $wrap_attributes['data-slider-autoplay'] = $settings['tmpcoder_slider_autoplay'];
            $wrap_attributes['data-slider-autoplay-speed'] = $settings['tmpcoder_slider_autoplay_speed'] ?? 3000;
            $wrap_attributes['data-slider-pause-on-hover'] = $settings['tmpcoder_slider_pause_on_hover'];
            $wrap_attributes['data-slider-infinite-loop'] = $settings['tmpcoder_slider_infinite_loop'];
        }

        $this->add_render_attribute( 'wrap', $wrap_attributes );

        add_filter( 'woocommerce_product_add_to_cart_text', [
            $this,
            'add_to_cart_button_custom_text',
        ] );
        ?>

        <div <?php $this->print_render_attribute_string('wrap'); ?> >
            <div class="woocommerce">
                <?php
                do_action( 'tmpcoder_woo_before_product_loop', $settings['tmpcoder_product_grid_style_preset'] );

                $template_name = ! empty( $settings['tmpcoder_product_grid_style_preset'] ) ? $settings['tmpcoder_product_grid_style_preset'] : 'tmpcoder-product-simple';


                if ( 'list' === $settings['tmpcoder_product_grid_layout'] ) {
                    $template_name = $settings['tmpcoder_product_list_style_preset'];
                }
                
                // if ( strpos( $template_name, 'tmpcoder-product-' ) === 0 ) {
                //     $template_name = str_replace( 'tmpcoder-product-', '', $template_name );
                // }
                if ( strpos( $template_name, 'tmpcoder-product-' ) === 0 ) {
                    $template_name = str_replace( 'tmpcoder-product-', '', $template_name );
                }
                $template                       = $this->get_template( $template_name );
                $settings['loadable_file_name'] = $this->get_filename_only( $template );
                $dir_name                       = $this->get_temp_dir_name( $settings['loadable_file_name'] );
                $found_posts                    = 0;
                $post_offset                    = isset( $settings['product_offset'] ) ? absint( $settings['product_offset'] ) : 0;

                $display_setting = get_option( 'woocommerce_category_archive_display' );

                if ( file_exists( $template ) ) {
                    $settings['tmpcoder_page_id'] = $this->page_id ? $this->page_id : get_the_ID();

                    if( $settings['post_type'] === 'source_archive' && ( ( is_archive() && $is_product_archive ) || is_search() ) ){
                        global $wp_query;
                        $query = $wp_query;
                        $args = $wp_query->query_vars;

                        // $args['tax_query'] = $this->get_tax_query_args();
                        // $query = new \WP_Query( $args );

                    } else {
                        $query = new \WP_Query( $args );
                    }

                    $allow_customizer_settings = isset($settings['allow_customizer_settings']) && $settings['allow_customizer_settings'] == 'yes' ? true : false;

                    if ( $query->have_posts() && ! $no_products_found ) {

                        $found_posts        = $query->found_posts - $post_offset;
                        $max_page           = ceil( $found_posts / absint( $args['posts_per_page'] ) );
                        $args['max_page']   = $max_page;
                        $args['total_post'] = $found_posts;
                        if ( ($display_setting === 'subcategories' || $display_setting === 'both') && $allow_customizer_settings == true ) {

                            woocommerce_product_loop_start();

                            if ( wc_get_loop_prop( 'total' ) ) {
                                while ( have_posts() ) {
                                    the_post();
                                    
                                    do_action( 'woocommerce_shop_loop' );

                                    wc_get_template_part( 'content', 'product' );
                                }
                            }

                            woocommerce_product_loop_end();
                        }
                        else
                        {
                             if ( isset($settings['tmpcoder_enable_slider']) && 'yes' === $settings['tmpcoder_enable_slider'] ) {
                                // Slider layout with navigation and pagination containers
                                echo '<div class="tmpcoder-slider-container">';
                                
                                // Navigation arrows (if enabled)
                                if ( 'yes' === $settings['tmpcoder_slider_navigation'] ) {
                                    $nav_icon_class = str_replace('fas fa-', '', $settings['tmpcoder_slider_nav_icon']);
                                    echo '<div class="tmpcoder-slider-nav">';
                                    echo '<button class="tmpcoder-slider-prev"><i class="fas fa-' . esc_attr($nav_icon_class) . '-left"></i></button>';
                                    echo '<button class="tmpcoder-slider-next"><i class="fas fa-' . esc_attr($nav_icon_class) . '-right"></i></button>';
                                    echo '</div>';
                                }
                                
                                printf( '<ul class="products tmpcoder-product-slider" data-layout-mode="%s">', esc_attr( $settings["tmpcoder_product_grid_layout"] ) );
                                
                                while ( $query->have_posts() ) {
                                    $query->the_post();
                                    include( realpath( $template ) );
                                }
                                wp_reset_postdata();
                                
                                echo '</ul>';
                                
                                // Pagination dots (if enabled)
                                if ( 'yes' === $settings['tmpcoder_slider_pagination'] ) {
                                    echo '<div class="tmpcoder-slider-dots"></div>';
                                }
                                
                                echo '</div>';
                            } else {
                                printf( '<ul class="products" data-layout-mode="%s">', esc_attr( $settings["tmpcoder_product_grid_layout"] ) );

                                while ( $query->have_posts() ) {
                                    $query->the_post();
                                    include( realpath( $template ) );
                                }
                                wp_reset_postdata();

                                echo '</ul>';
                            }

                            do_action( 'tmpcoder_woo_after_product_loop' );

                        }
                    } 
                    else {
                        echo '<p class="no-posts-found">' . esc_html__( 'No posts found!', 'sastra-essential-addons-for-elementor' ) . '</p>';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }

                } else {
                    echo '<p class="no-posts-found">' . esc_html__( 'No layout found!', 'sastra-essential-addons-for-elementor' ) . '</p>';
                }

                if ( 'true' == $settings['show_pagination'] && 'source_archive' !== $settings['post_type'] ) {
                    $settings['tmpcoder_widget_name'] = $this->get_name();
                    $settings['tmpcoder_product_grid_template'] = $template_name;
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo HelperClass::tmpcoder_pagination( $args, $settings );
                }

                if( 'source_archive' === $settings['post_type'] ){
                    echo "<div class='tmpcoder-product-grid-pagination' >";
                        // woocommerce_pagination();
                        $total_pages = $query->max_num_pages;

                        if ( $total_pages > 1 ) {
                            $pagination = paginate_links([
                                'total'     => $total_pages,
                                'current'   => max(1, get_query_var('paged')),
                                'format'    => '?paged=%#%',
                                'type'      => 'array',
                                'prev_text' => '←',
                                'next_text' => '→',
                            ]);

                            if ( is_array( $pagination ) ) {
                                echo '<nav class="woocommerce-pagination">';
                                    echo '<ul class="page-numbers">';
                                    foreach ( $pagination as $page ) {
                                        echo '<li>' . $page . '</li>';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    }
                                    echo '</ul>';
                                echo '</nav>';
                            }
                        }
                    echo "</div>";

                }

                if ( $found_posts > $args['posts_per_page'] && 'source_archive' !== $settings['post_type'] ) {
                    $this->print_load_more_button( $settings, $args, $dir_name );
                }

                do_action( 'tmpcoder_woo_after_product_loop', $settings['tmpcoder_product_grid_style_preset'] );
                ?>
            </div>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                var $scope = jQuery(".elementor-element-<?php echo esc_js( $this->get_id() ); ?>");
                var $products = $('.products', $scope);
                var $layout_mode = $products.data('layout-mode');
                
                if ( $layout_mode === 'masonry' ) {
                    // init isotope
                    var $isotope_products = $products.isotope({
                        itemSelector: "li.product",
                        layoutMode: $layout_mode,
                        percentPosition: true
                    });

                    $isotope_products.imagesLoaded().progress( function() {
                        $isotope_products.isotope('layout');
                    })
                    
                }
            });
        </script>
        <?php
        remove_filter('woocommerce_product_add_to_cart_text', [
            $this,
            'add_to_cart_button_custom_text',
        ]);
        remove_filter( 'single_product_archive_thumbnail_size', [ $this, 'tmpcoder_customize_woo_prod_thumbnail_size' ] );
    }

    /**
     * build_product_query
     * @param $settings
     * @return array
     */
    public function build_product_query( $settings ){
        $args = [
            'post_type' => 'product',
            'post_status'    => ! empty( $settings['tmpcoder_product_grid_products_status'] ) ? $settings['tmpcoder_product_grid_products_status'] : [ 'publish', 'pending', 'future' ],
            'posts_per_page' => $settings['tmpcoder_product_grid_products_count'] ?: 4,
            'order' => (isset($settings['order']) ? $settings['order'] : 'desc'),
            'offset' => $settings['product_offset'],
            'tax_query' => $this->get_tax_query_args(),
            // 'tax_query' => [
            //     'relation' => 'AND',
            //     [
            //         'taxonomy' => 'product_visibility',
            //         'field' => 'name',
            //         'terms' => ['exclude-from-search', 'exclude-from-catalog'],
            //         'operator' => 'NOT IN',
            //     ],
            // ],
        ];

        if ( is_singular() ) {
            $args['post__not_in'] = [ get_the_ID() ];
        }

        // price & sku filter
        if ($settings['orderby'] == '_price') {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_price';
        } else if ($settings['orderby'] == '_sku') {
            $args['orderby'] = 'meta_value meta_value_num';
            $args['meta_key'] = '_sku';
        } else {
            $args['orderby'] = (isset($settings['orderby']) ? $settings['orderby'] : 'date');
        }

        if ( ! empty( $settings['tmpcoder_product_grid_categories'] ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $settings['tmpcoder_product_grid_categories'],
                'operator' => 'IN',
            ];
        }

        if ( ! empty( $settings['tmpcoder_product_cat_not_in'] ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $settings['tmpcoder_product_cat_not_in'],
                'operator' => 'NOT IN',
            ];
        }

        if ( ! empty( $settings['tmpcoder_product_grid_tags'] ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => $settings['tmpcoder_product_grid_tags'],
                'operator' => 'IN',
            ];
        }

        $args['meta_query'] = ['relation' => 'AND'];

        if ( get_option('woocommerce_hide_out_of_stock_items') == 'yes' || 'yes' !== $settings['tmpcoder_product_show_stockout'] ) {
            $args['meta_query'][] = [
                'key' => '_stock_status',
                'value' => 'instock'
            ];
        }

        if( function_exists('whols_lite') ){
            $args['meta_query'] = array_filter( apply_filters( 'woocommerce_product_query_meta_query', $args['meta_query'], new \WC_Query() ) );
        }

        if ($settings['tmpcoder_product_grid_product_filter'] == 'featured-products') {
            $args['tax_query'] = [
                'relation' => 'AND',
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                ],
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => ['exclude-from-search', 'exclude-from-catalog'],
                    'operator' => 'NOT IN',
                ],
            ];

            if ($settings['tmpcoder_product_grid_categories']) {
                $args['tax_query'][] = [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $settings['tmpcoder_product_grid_categories'],
                ];
            }
        }
        else if ($settings['tmpcoder_product_grid_product_filter'] == 'best-selling-products') {
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }
        else if ($settings['tmpcoder_product_grid_product_filter'] == 'sale-products') {
            $args['post__in']  = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
        }
        else if ($settings['tmpcoder_product_grid_product_filter'] == 'top-products') {
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }
        else if ( $settings['tmpcoder_product_grid_product_filter'] == 'related-products' ) {
            $current_product_id = get_the_ID();
            $product_categories = wp_get_post_terms( $current_product_id, 'product_cat', array( 'fields' => 'ids' ) );
            $product_tags       = wp_get_post_terms( $current_product_id, 'product_tag', array( 'fields' => 'names' ) );
            $args['tax_query'] = array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $product_categories,
                    'operator' => 'IN',
                ),
                array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'name',
                    'terms'    => $product_tags,
                    'operator' => 'IN',
                ),
            );

        }
        else if( $settings['tmpcoder_product_grid_product_filter'] == 'manual' ){

            $args['post_name__in'] = $settings['tmpcoder_product_grid_products_in'] ? $settings['tmpcoder_product_grid_products_in'] : [ 0 ];
        }

        return $args;
    }

    // Taxonomy Query Args
    public function get_tax_query_args() {
        $tax_query = [];

        // Filters Query
        if ( isset($_GET['tmpcoderfilters']) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $selected_filters = WC()->query->get_layered_nav_chosen_attributes();

            if ( !empty($selected_filters) ) {
                foreach ( $selected_filters as $taxonomy => $data ) {
                    array_push($tax_query, [
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $data['terms'],
                        'operator' => 'and' === $data['query_type'] ? 'AND' : 'IN',
                        'include_children' => false,
                    ]);
                }
            }

            // Product Categories
            if ( isset($_GET['filter_product_cat']) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                array_push($tax_query, [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => explode( ',', sanitize_text_field(wp_unslash($_GET['filter_product_cat'])) ),// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    'operator' => 'IN',
                    'include_children' => true, // test this needed or not for hierarchy
                ]);
            }

            // Product Tags
            if ( isset($_GET['filter_product_tag']) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                array_push($tax_query, [
                    'taxonomy' => 'product_tag',
                    'field' => 'slug',
                    'terms' => explode( ',', sanitize_text_field(wp_unslash($_GET['filter_product_tag'])) ),// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    'operator' => 'IN',
                    'include_children' => true, // test this needed or not for hierarchy
                ]);
            } 

        // Grid Query
        } else {
            $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

            if ( isset($_GET['tmpcoder_select_product_cat']) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                if ( $_GET['tmpcoder_select_product_cat'] != '0' ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    // Get category from URL
                    $category = sanitize_text_field(wp_unslash($_GET['tmpcoder_select_product_cat']));// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                
                    array_push( $tax_query, [
                        'taxonomy' => 'product_cat',
                        'field' => 'id',
                        'terms' => $category
                    ] );
                }
            }

            if ( isset($_GET['product_cat']) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                if ( $_GET['product_cat'] != '0' ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    // Get category from URL
                    $category = sanitize_text_field(wp_unslash($_GET['product_cat']));// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                
                    array_push( $tax_query, [
                        'taxonomy' => 'product_cat',
                        'field' => 'id',
                        'terms' => $category
                    ] );
                }
            } else {
                foreach ( get_object_taxonomies( 'product' ) as $tax ) {
                    if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
                        array_push( $tax_query, [
                            'taxonomy' => $tax,
                            'field' => 'slug',
                            'terms' => $settings[ 'query_taxonomy_'. $tax ]
                        ] );
                    }
                }
            }
    
        }

        // Filter by rating.
        if ( isset( $_GET['filter_rating'] ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended

            $product_visibility_terms  = wc_get_product_visibility_term_ids();
            
            $filter_rating = array_filter( array_map( 'absint', explode( ',', sanitize_text_field(wp_unslash( $_GET['filter_rating'] )) ) ) );// phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $rating_terms  = array();
            for ( $i = 1; $i <= 5; $i ++ ) {
                if ( in_array( $i, $filter_rating, true ) && isset( $product_visibility_terms[ 'rated-' . $i ] ) ) {
                    $rating_terms[] = $product_visibility_terms[ 'rated-' . $i ];
                }
            }
            if ( ! empty( $rating_terms ) ) {
                $tax_query[] = array(
                    'taxonomy'      => 'product_visibility',
                    'field'         => 'term_taxonomy_id',
                    'terms'         => $rating_terms,
                    'operator'      => 'IN',
                );
            }
        }

        return $tax_query;
    }

    protected function tmpcoder_get_product_statuses() {
        return apply_filters( 'tmpcoder/woo-product-grid/product-statuses', [
            'publish'       => esc_html__( 'Publish', 'sastra-essential-addons-for-elementor' ),
            'draft'         => esc_html__( 'Draft', 'sastra-essential-addons-for-elementor' ),
            'pending'       => esc_html__( 'Pending Review', 'sastra-essential-addons-for-elementor' ),
            'future'        => esc_html__( 'Schedule', 'sastra-essential-addons-for-elementor' ),
        ] );
    }

    public function load_quick_view_asset(){
        add_action('wp_footer',function (){
            if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
                if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
                    wp_enqueue_script( 'zoom' );
                }
                if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
                    wp_enqueue_script( 'flexslider' );
                }
                
                if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
                    wp_enqueue_script( 'photoswipe-ui-default' );
                    wp_enqueue_style( 'photoswipe-default-skin' );
                    if ( ! Plugin::$instance->editor->is_edit_mode() && has_action( 'wp_footer', 'woocommerce_photoswipe' ) === false ) {
                        add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
                    }
                }
                wp_enqueue_script( 'wc-add-to-cart-variation' );
                wp_enqueue_script( 'wc-single-product' );
            }
        });
    }

    protected function init_style_slider_navigation_controls()
    {
        $this->start_controls_section(
            'tmpcoder_section_slider_navigation_style',
            [
                'label' => esc_html__('Slider Navigation', 'sastra-essential-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                    'tmpcoder_slider_navigation' => 'yes',
                ],
            ]
        );

        // Navigation Tabs
        $this->start_controls_tabs('tmpcoder_slider_nav_tabs');

        // Normal Tab
        $this->start_controls_tab(
            'tmpcoder_slider_nav_normal',
            [
                'label' => esc_html__('Normal', 'sastra-essential-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'tmpcoder_slider_nav_color',
            [
                'label' => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_nav_bg_color',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_nav_border_color_',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_nav_transition_duration',
            [
                'label' => esc_html__('Transition Duration', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'tmpcoder_slider_nav_hover',
            [
                'label' => esc_html__('Hover', 'sastra-essential-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'tmpcoder_slider_nav_hover_color',
            [
                'label' => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_nav_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_nav_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Size Controls
        $this->add_responsive_control(
            'tmpcoder_slider_nav_font_size',
            [
                'label' => esc_html__('Font Size', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 16,
                ],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_slider_nav_box_size',
            [
                'label' => esc_html__('Box Size', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 40,
                ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Border Controls
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tmpcoder_slider_nav_border',
                'label' => esc_html__('Border Type', 'sastra-essential-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .tmpcoder-slider-nav button',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_slider_nav_border_radius',
            [
                'label' => esc_html__('Border Radius', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => '%',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Positioning Controls
        $this->add_control(
            'tmpcoder_slider_nav_positioning',
            [
                'label' => esc_html__('Positioning', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'default' => esc_html__('Default', 'sastra-essential-addons-for-elementor'),
                    'custom' => esc_html__('Custom', 'sastra-essential-addons-for-elementor'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_slider_nav_margin',
            [
                'label' => esc_html__('Margin', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'tmpcoder_slider_nav_positioning' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_slider_nav_vertical_position',
            [
                'label' => esc_html__('Vertical Position', 'sastra-essential-addons-for-elementor'),
                'size_units' => [ '%', 'px' ],
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50,
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-nav' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tmpcoder_slider_nav_positioning' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_slider_nav_left_position',
            [
                'label' => esc_html__('Left Position', 'sastra-essential-addons-for-elementor'),
                'size_units' => [ '%', 'px' ],
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tmpcoder_slider_nav_positioning' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_slider_nav_right_position',
            [
                'label' => esc_html__('Right Position', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tmpcoder_slider_nav_positioning' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function init_style_slider_pagination_controls()
    {
        $this->start_controls_section(
            'tmpcoder_section_slider_pagination_style',
            [
                'label' => esc_html__('Slider Pagination', 'sastra-essential-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                   'tmpcoder_enable_slider' => 'yes',
                    'tmpcoder_slider_pagination' => 'yes',
                ],
            ]
        );

        // Pagination Tabs
        $this->start_controls_tabs('tmpcoder_slider_pagination_tabs');

        // Normal Tab
        $this->start_controls_tab(
            'tmpcoder_slider_pagination_normal',
            [
                'label' => esc_html__('Normal', 'sastra-essential-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'tmpcoder_slider_pagination_color',
            [
                'label' => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_pagination_bg_color',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_pagination_border_color_',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active Tab
        $this->start_controls_tab(
            'tmpcoder_slider_pagination_active',
            [
                'label' => esc_html__('Active', 'sastra-essential-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'tmpcoder_slider_pagination_active_color',
            [
                'label' => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5729D9',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_pagination_active_bg_color',
            [
                'label' => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5729D9',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tmpcoder_slider_pagination_active_border_color',
            [
                'label' => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li.slick-active button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Size Controls
        $this->add_responsive_control(
            'tmpcoder_slider_pagination_size',
            [
                'label' => esc_html__('Size', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 12,
                ],
                'range' => [
                    'px' => [
                        'min' => 4,
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_slider_pagination_spacing',
            [
                'label' => esc_html__('Spacing', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 8,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Border Controls
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tmpcoder_slider_pagination_border',
                'label' => esc_html__('Border Type', 'sastra-essential-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li button',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_slider_pagination_border_radius',
            [
                'label' => esc_html__('Border Radius', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => '%',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-dots .slick-dots li button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Positioning Controls
        $this->add_responsive_control(
            'tmpcoder_slider_pagination_margin',
            [
                'label' => esc_html__('Margin', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tmpcoder_slider_pagination_alignment',
            [
                'label' => esc_html__('Alignment', 'sastra-essential-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'sastra-essential-addons-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-slider-dots .slick-dots' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-grid .tmpcoder-slider-dots' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

}
