<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use TMPCODER\Modules\TMPCODER_Post_Likes;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Woo_Grid extends Widget_Base {
	
	public $my_upsells;
	public $crossell_ids;

	public function get_name() {
		return 'tmpcoder-woo-grid';
	}

	public function get_title() {
		return esc_html__( 'Product Grid/Slider/Carousel', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-gallery-grid';
	}

	public function get_categories() {

		if (tmpcoder_show_theme_buider_widget_on('type_product_archive') || tmpcoder_show_theme_buider_widget_on('type_product_category') || tmpcoder_show_theme_buider_widget_on('type_single_product')) {
			return [ 'tmpcoder-woocommerce-builder-widgets'];
		}else{
			return ['tmpcoder-widgets-category'];
		}
	}

	public function get_keywords() {
		return [ 'shop grid', 'product grid', 'woocommerce', 'product slider', 'product carousel', 'isotope', 'massonry grid', 'filterable grid', 'loop grid' ];
	}

	public function get_script_depends() {

		$depends = [ 'tmpcoder-isotope' => true, 'tmpcoder-slick' => true, 'tmpcoder-lightgallery' => true, 'tmpcoder-grid-widgets' => true ];

		if ( ! tmpcoder_elementor()->preview->is_preview_mode() ) {
			$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

			if ( $settings['layout_select'] != 'slider' ) {
				unset( $depends['tmpcoder-slick'] );
			}if ( $settings['layout_select'] != 'masonry' && $settings['layout_select'] != 'fitRows' && $settings['layout_select'] != 'list' ) {
				unset( $depends['tmpcoder-isotope'] );
			}

			$filtered = array_filter($settings['grid_elements'], function($element) {
			    return isset($element['element_select']) && $element['element_select'] === 'lightbox';
			});

			if (!$filtered) {
				unset( $depends['tmpcoder-lightgallery'] );	
			}
		}

		return array_keys($depends);
	}

	public function get_style_depends() {

		$depends = [ 'tmpcoder-animations-css' => true, 'tmpcoder-link-animations-css' => true, 'tmpcoder-button-animations-css' => true, 'tmpcoder-loading-animations-css' => true, 'tmpcoder-lightgallery-css' => true, 'tmpcoder-grid-widgets' => true ];

		if ( !tmpcoder_elementor()->preview->is_preview_mode() ) {

			$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
			$filtered = array_filter($settings['grid_elements'], function($element) {
			    return isset($element['element_select']) && $element['element_select'] === 'lightbox';
			});

			if ($settings['layout_pagination'] != 'yes') {
				unset( $depends['tmpcoder-loading-animations-css'] );	
			}

			if (!$filtered) {
				unset( $depends['tmpcoder-lightgallery-css'] );	
			}
		}

		return array_keys($depends);
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_secondary_img_on_hover() {
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

	public function add_control_open_links_in_new_tab() {
		$this->add_control(
			'open_links_in_new_tab',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Open Links in New Tab %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control no-distance'
			]
		);
	}

	public function add_control_query_selection() {
		$this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Query Products', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'sastra-essential-addons-for-elementor' ),
					'manual' => esc_html__( 'Manual', 'sastra-essential-addons-for-elementor' ),
					'current' => esc_html__( 'Current Query', 'sastra-essential-addons-for-elementor' ),
					'pro-fr' => esc_html__( 'Featured (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-os' => esc_html__( 'On Sale (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-us' => esc_html__( 'Upsell (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-cs' => esc_html__( 'Cross-sell (Pro)', 'sastra-essential-addons-for-elementor' ),					
					'pro-rp' => esc_html__( 'Related Product (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
                'frontend_available' => true,
			]
		);
	}
	
	public function add_control_query_orderby() {
		$this->add_control(
			'query_orderby',
			[
				'label' => esc_html__( 'Order By', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'default' => esc_html__( 'Default', 'sastra-essential-addons-for-elementor' ),
					'date' => esc_html__( 'Date', 'sastra-essential-addons-for-elementor' ),
					'sales' => esc_html__( 'Sales', 'sastra-essential-addons-for-elementor' ),
					'rating' => esc_html__( 'Rating', 'sastra-essential-addons-for-elementor' ),
					'price-low' => esc_html__( 'Price - Low to High', 'sastra-essential-addons-for-elementor' ),
					'price-high' => esc_html__( 'Price - High to Low', 'sastra-essential-addons-for-elementor' ),
					'pro-rn' => esc_html__( 'Random (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'query_selection' => [ 'dynamic', 'onsale', 'featured', 'upsell', 'cross-sell','related-product' ],
				],
                'frontend_available' => true,
			]
		);
	}
	
	public function add_control_layout_select() {
		$this->add_control(
			'layout_select',
			[
				'label' => esc_html__( 'Select Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fitRows',
				'options' => [
					'fitRows' => esc_html__( 'FitRows - Equal Height', 'sastra-essential-addons-for-elementor' ),
					'list' => esc_html__( 'List Style', 'sastra-essential-addons-for-elementor' ),
					'slider' => esc_html__( 'Slider / Carousel', 'sastra-essential-addons-for-elementor' ),
					'pro-ms' => esc_html__( 'Masonry - Unlimited Height (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'label_block' => true,
                'frontend_available' => true,
			]
		);
	}

	public function add_control_layout_columns() {
		$this->add_responsive_control(
			'layout_columns',
			[
				'label' => esc_html__( 'Columns', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 3,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'sastra-essential-addons-for-elementor' ),
					2 => esc_html__( 'Two', 'sastra-essential-addons-for-elementor' ),
					3 => esc_html__( 'Three', 'sastra-essential-addons-for-elementor' ),
					'pro-4' => esc_html__( 'Four (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-5' => esc_html__( 'Five (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-6' => esc_html__( 'Six (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-grid-columns-%s',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
                ],
                'frontend_available' => true,
			]
		);
	}
	
	public function add_control_layout_animation() {
		$this->add_control(
			'layout_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'sastra-essential-addons-for-elementor' ),
					'zoom' => esc_html__( 'Zoom', 'sastra-essential-addons-for-elementor' ),
					'pro-fd' => esc_html__( 'Fade (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-fs' => esc_html__( 'Fade + SlideUp (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'selectors_dictionary' => [
					'default' => '',
					'zoom' => 'opacity: 0; transform: scale(0.01)',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-inner' => '{{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);
	}
	
	public function add_control_sort_and_results_count() {
		$this->add_control(
			'layout_sort_and_results_count',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Sorting %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);
	}
	
	public function add_section_grid_sorting() {}
	
	public function add_section_style_sort_and_results() {}

	public function add_control_layout_slider_amount() {
		$this->add_responsive_control(
			'layout_slider_amount',
			[
				'label' => esc_html__( 'Columns (Carousel)', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 2,
				'widescreen_default' => 2,
				'laptop_default' => 2,
				'tablet_extra_default' => 2,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'sastra-essential-addons-for-elementor' ),
					2 => esc_html__( 'Two', 'sastra-essential-addons-for-elementor' ),
					'pro-3' => esc_html__( 'Three (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-4' => esc_html__( 'Four (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-5' => esc_html__( 'Five (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-6' => esc_html__( 'Six (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-grid-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
	}
	
	public function add_control_layout_slider_nav_hover() {}
	
	public function add_control_layout_slider_dots_position() {
		$this->add_control(
			'layout_slider_dots_position',
			[
				'label' => esc_html__( 'Pagination Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'sastra-essential-addons-for-elementor' ),
					'pro-vr' => esc_html__( 'Vertical (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-grid-slider-dots-',
				'render_type' => 'template',
				'condition' => [
					// 'layout_slider_dots' => 'yes',
					'layout_select' => 'slider',
				],
			]
		);
	}
	
	public function add_control_stack_layout_slider_autoplay() {}

	public function add_option_element_select() {
		return [
			'title' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
			'excerpt' => esc_html__( 'Excerpt', 'sastra-essential-addons-for-elementor' ),
			'product_cat' => esc_html__( 'Categories', 'sastra-essential-addons-for-elementor' ),
			'product_tag' => esc_html__( 'Tags', 'sastra-essential-addons-for-elementor' ),
			'pro-cfa' => esc_html__( 'Custom Fields/Attributes', 'sastra-essential-addons-for-elementor' ),
			'status' => esc_html__( 'Status', 'sastra-essential-addons-for-elementor' ),
			'price' => esc_html__( 'Price', 'sastra-essential-addons-for-elementor' ),
			'pro-sd' => esc_html__( 'Sale Dates (Pro)', 'sastra-essential-addons-for-elementor' ),
			'rating' => esc_html__( 'Rating', 'sastra-essential-addons-for-elementor' ),
			'add-to-cart' => esc_html__( 'Add to Cart', 'sastra-essential-addons-for-elementor' ),
			'pro-ws' => esc_html__( 'Wishlist Button (Pro)', 'sastra-essential-addons-for-elementor' ),
			'pro-cm' => esc_html__( 'Compare Button (Pro)', 'sastra-essential-addons-for-elementor' ),
			'lightbox' => esc_html__( 'Lightbox', 'sastra-essential-addons-for-elementor' ),
			'separator' => esc_html__( 'Separator', 'sastra-essential-addons-for-elementor' ),
			'pro-lk' => esc_html__( 'Likes (Pro)', 'sastra-essential-addons-for-elementor' ),
			'pro-shr' => esc_html__( 'Sharing (Pro)', 'sastra-essential-addons-for-elementor' ),
		];
	}

	public function add_repeater_args_element_custom_field() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_btn_link() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_new_tab() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_style() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_text() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_show_count() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_6() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_action() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_direction() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_tooltip() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_show_added_tc_popup() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_show_added_to_wishlist_popup() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_show_added_to_compare_popup() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_trim_text_by() {
		return [
			'word_count' => esc_html__( 'Word Count', 'sastra-essential-addons-for-elementor' ),
			'pro-lc' => esc_html__( 'Letter Count (Pro)', 'sastra-essential-addons-for-elementor' )
		];
	}
	
	public function add_control_overlay_animation_divider() {}
	
	public function add_control_overlay_image() {}
	
	public function add_control_overlay_image_width() {}
	
	public function add_control_image_effects() {
		$this->add_control(
			'image_effects',
			[
				'label' => esc_html__( 'Select Effect', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'pro-zi' => esc_html__( 'Zoom In (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-zo' => esc_html__( 'Zoom Out (Pro)', 'sastra-essential-addons-for-elementor' ),
					'grayscale-in' => esc_html__( 'Grayscale In', 'sastra-essential-addons-for-elementor' ),
					'pro-go' => esc_html__( 'Grayscale Out (Pro)', 'sastra-essential-addons-for-elementor' ),
					'blur-in' => esc_html__( 'Blur In', 'sastra-essential-addons-for-elementor' ),
					'pro-bo' => esc_html__( 'Blur Out (Pro)', 'sastra-essential-addons-for-elementor' ),
					'slide' => esc_html__( 'Slide', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'none',
			]
		);
	}
	
	public function add_control_lightbox_popup_thumbnails() {}
	
	public function add_control_lightbox_popup_thumbnails_default() {}
	
	public function add_control_lightbox_popup_sharing() {}
	
	public function add_control_filters_deeplinking() {}
	
	public function add_control_filters_animation() {
		$this->add_control(
			'filters_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'sastra-essential-addons-for-elementor' ),
					'zoom' => esc_html__( 'Zoom', 'sastra-essential-addons-for-elementor' ),
					'pro-fd' => esc_html__( 'Fade (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-fs' => esc_html__( 'Fade + SlideUp (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'separator' => 'before',
			]
		);
	}
	
	public function add_control_filters_icon() {}
	
	public function add_control_filters_icon_align() {}

	public function add_control_filters_count() {}
	
	public function add_control_filters_count_superscript() {}
	
	public function add_control_filters_count_brackets() {}
	
	public function add_control_filters_default_filter() {}
	
	public function add_control_pagination_type() {
		$this->add_control(
			'pagination_type',
			[
				'label' => esc_html__( 'Select Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'load-more',
				'options' => [
					'default' => esc_html__( 'Default', 'sastra-essential-addons-for-elementor' ),
					'numbered' => esc_html__( 'Numbered', 'sastra-essential-addons-for-elementor' ),
					'load-more' => esc_html__( 'Load More Button', 'sastra-essential-addons-for-elementor' ),
					'pro-is' => esc_html__( 'Infinite Scrolling (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'separator' => 'after'
			]
		);
	}

	public function add_section_added_to_cart_popup() {}
	
	public function add_section_style_likes() {}
	
	public function add_section_style_sharing() {}
	
	public function add_section_style_custom_field1() {}
	
	public function add_section_style_custom_field2() {}
	
	public function add_control_grid_item_even_bg_color() {}
	
	public function add_control_grid_item_even_border_color() {}
	
	public function add_control_overlay_color() {
		$this->add_control(
			'overlay_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.25)',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg' => 'background-color: {{VALUE}}',
				],
			]
		);
	}
	
	public function add_control_overlay_blend_mode() {}
	
	public function add_control_overlay_border_color() {}
	
	public function add_control_overlay_border_type() {}
	
	public function add_control_overlay_border_width() {}
	
	public function add_control_title_pointer_color_hr() {}
	
	public function add_control_title_pointer() {}
	
	public function add_control_title_pointer_height() {}
	
	public function add_control_title_pointer_animation() {}
	
	public function add_control_categories_pointer_color_hr() {}
	
	public function add_control_categories_pointer() {}
	
	public function add_control_categories_pointer_height() {}
	
	public function add_control_categories_pointer_animation() {}
	
	public function add_control_tags_pointer_color_hr() {}
	
	public function add_control_tags_pointer() {}
	
	public function add_control_tags_pointer_height() {}
	
	public function add_control_tags_pointer_animation() {}
	
	public function add_control_add_to_cart_animation() {}
	
	public function add_control_add_to_cart_animation_height() {}
	
	public function add_control_filters_pointer_color_hr() {}
	
	public function add_control_filters_pointer() {}
	
	public function add_control_filters_pointer_height() {}
	
	public function add_control_filters_pointer_animation() {}
	
	public function add_control_stack_grid_slider_nav_position() {}
	
	public function add_control_grid_slider_dots_hr() {}

	public function add_control_atc_popup_repeater() {

	}

	public function tmpcoder_pagination_tab_content(){
			
		// Tab: Content ==============
		// Section: Pagination -------
		$this->start_controls_section(
			'section_grid_pagination',
			[
				'label' => esc_html__( 'Pagination', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_pagination' => 'yes',
				],
			]
		);

        $this->add_control(
			'section_grid_pagination_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => 
             		'<strong>Note:</strong> This widget pagination not worked in Site Builder > Post/Product Single Layouts.',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control_pagination_type();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'pagination_type', ['pro-is'] );

		$this->add_control(
			'pagination_older_text',
			[
				'label' => esc_html__( 'Older Posts Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Older Posts',
				'condition' => [
					'pagination_type' => 'default',
				],
			]
		);

		$this->add_control(
			'pagination_newer_text',
			[
				'label' => esc_html__( 'Newer Posts Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Newer Posts',
				'condition' => [
					'pagination_type' => 'default',
				]
			]
		);

		$this->add_control(
			'pagination_on_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => tmpcoder_get_svg_icons_array( 'arrows', [
					'fas fa-angle' => esc_html__( 'Angle', 'sastra-essential-addons-for-elementor' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'sastra-essential-addons-for-elementor' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'sastra-essential-addons-for-elementor' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'sastra-essential-addons-for-elementor' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'sastra-essential-addons-for-elementor' ),
				] ),
				'condition' => [
					'pagination_type' => 'default'
				],
			]
		);

		$this->add_control(
			'pagination_prev_next',
			[
				'label' => esc_html__( 'Previous & Next Buttons', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'pagination_type' => 'numbered',
				],
			]
		);

		$this->add_control(
			'pagination_prev_text',
			[
				'label' => esc_html__( 'Prev Page Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Previous Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_prev_next' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_next_text',
			[
				'label' => esc_html__( 'Next Page Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Next Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_prev_next' => 'yes',
				]
			]
		);

		$this->add_control(
			'pagination_pn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => tmpcoder_get_svg_icons_array( 'arrows', [
					'fas fa-angle' => esc_html__( 'Angle', 'sastra-essential-addons-for-elementor' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'sastra-essential-addons-for-elementor' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'sastra-essential-addons-for-elementor' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'sastra-essential-addons-for-elementor' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'sastra-essential-addons-for-elementor' ),
				] ),
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_prev_next' => 'yes'
				],
			]
		);

		$this->add_control(
			'pagination_first_last',
			[
				'label' => esc_html__( 'First & Last Buttons', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'pagination_type' => 'numbered',
				],
			]
		);

		$this->add_control(
			'pagination_first_text',
			[
				'label' => esc_html__( 'First Page Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'First Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_first_last' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_last_text',
			[
				'label' => esc_html__( 'Last Page Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Last Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_first_last' => 'yes',
				]
			]
		);

		$this->add_control(
			'pagination_fl_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => tmpcoder_get_svg_icons_array( 'arrows', [
					'fas fa-angle' => esc_html__( 'Angle', 'sastra-essential-addons-for-elementor' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'sastra-essential-addons-for-elementor' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'sastra-essential-addons-for-elementor' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'sastra-essential-addons-for-elementor' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'sastra-essential-addons-for-elementor' ),
				] ),
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_first_last' => 'yes'
				],
			]
		);

		$this->add_control(
			'pagination_disabled_arrows',
			[
				'label' => esc_html__( 'Show Disabled Buttons', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'pagination_type' => [ 'default', 'numbered' ],
				],
			]
		);

		$this->add_control(
			'pagination_range',
			[
				'label' => esc_html__( 'Range', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
				'min' => 1,
				'condition' => [
					'pagination_type' => 'numbered',
				]
			]
		);

		$this->add_control(
			'pagination_load_more_text',
			[
				'label' => esc_html__( 'Load More Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Load More',
				'condition' => [
					'pagination_type' => 'load-more',
				]
			]
		);

		$this->add_control(
			'pagination_finish_text',
			[
				'label' => esc_html__( 'Finish Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'No more items.',
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
				]
			]
		);

		$this->add_control(
			'pagination_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'loader-1',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'loader-1' => esc_html__( 'Loader 1', 'sastra-essential-addons-for-elementor' ),
					'loader-2' => esc_html__( 'Loader 2', 'sastra-essential-addons-for-elementor' ),
					'loader-3' => esc_html__( 'Loader 3', 'sastra-essential-addons-for-elementor' ),
					'loader-4' => esc_html__( 'Loader 4', 'sastra-essential-addons-for-elementor' ),
					'loader-5' => esc_html__( 'Loader 5', 'sastra-essential-addons-for-elementor' ),
					'loader-6' => esc_html__( 'Loader 6', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
				]
			]
		);

		$this->add_control(
			'pagination_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'prefix_class' => 'tmpcoder-grid-pagination-',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'pagination_type!' => 'infinite-scroll',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function tmpcoder_section_filter_options(){

		// Tab: Content ==============
		// Section: Filters ----------
		$this->start_controls_section(
			'section_grid_filters',
			[
				'label' => esc_html__( 'Filters', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_filters' => 'yes',
				],
			]
		);

		$this->add_control(
			'filters_select',
			[
				'label' => esc_html__( 'Select Taxonomy', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'product_cat' => esc_html__( 'Categories', 'sastra-essential-addons-for-elementor' ),
					'product_tag' => esc_html__( 'Tags', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'product_cat',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'filters_linkable',
			[
				'label' => esc_html__( 'Set Linkable Filters', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'filters_hide_empty',
			[
				'label' => esc_html__( 'Hide Empty Filters', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'filters_linkable!' => 'yes',
				],
			]
		);

		$this->add_control_filters_deeplinking();

		$this->add_control(
			'filters_all',
			[
				'label' => esc_html__( 'Show "All" Filter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'filters_linkable!' => 'yes',
				],
			]
		);

		$this->add_control(
			'filters_all_text',
			[
				'label' => esc_html__( '"All" Filter Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'All',
				'condition' => [
					'filters_all' => 'yes',
					'filters_linkable!' => 'yes',
				],
			]
		);

		$this->add_control_filters_count();

		$this->add_control_filters_count_superscript();

		$this->add_control_filters_count_brackets();

		$this->add_control_filters_default_filter();

		$this->add_control_filters_icon();

		$this->add_control_filters_icon_align();

		$this->add_control(
			'filters_separator',
			[
				'label' => esc_html__( 'Separator', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'filters_separator_align',
			[
				'label' => esc_html__( 'Separator Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'condition' => [
					'filters_separator!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'filters_align',
			[
				'label' => esc_html__( 'Align', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_filters_animation();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'filters_animation', ['pro-fd', 'pro-fs'] );

		$this->add_control(
			'filters_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					'filters_animation!' => 'default',
				],
			]
		);

		$this->add_control(
			'filters_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.05,
				'condition' => [
					'filters_animation!' => 'default'
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Query ------------
		$this->start_controls_section(
			'section_grid_query',
			[
				'label' => esc_html__( 'Query', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_query_selection();

        $this->add_control(
			'order_direction',
			[
				'label' => esc_html__( 'Order', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'label_block' => false,
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'sastra-essential-addons-for-elementor'),
					'DESC' => esc_html__( 'Descending', 'sastra-essential-addons-for-elementor'),
				],
				'condition' => [
					'query_randomize!' => 'rand',
				]
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'query_selection', ['pro-fr','pro-os','pro-us','pro-cs','pro-rp'] );

		$this->add_control_query_orderby();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'query_orderby', ['pro-rn'] );

		// Categories
		$this->add_control(
			'query_taxonomy_product_cat',
			[
				'label' => esc_html__( 'Categories', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_taxonomies',
				'query_slug' => 'product_cat',
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_selection' => [ 'dynamic', 'onsale', 'featured' ],
				],
			]
		);

		// Tags
		$this->add_control(
			'query_taxonomy_product_tag',
			[
				'label' => esc_html__( 'Tags', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_taxonomies',
				'query_slug' => 'product_tag',
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_selection' => [ 'dynamic', 'onsale', 'featured' ],
				],
			]
		);

		// Exclude
		$this->add_control(
			'query_exclude_products',
			[
				'label' => esc_html__( 'Exclude Products', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_posts_by_post_type',
				'query_slug' => 'product',
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_selection!' => [ 'manual', 'onsale', 'current', 'upsell', 'cross-sell','related-product' ],
				],
			]
		);

		// Manual Selection
		$this->add_control(
			'query_manual_products',
			[
				'label' => esc_html__( 'Select Products', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_posts_by_post_type',
				'query_slug' => 'product',
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_selection' => 'manual',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'query_posts_per_page',
			[
				'label' => esc_html__( 'Products Per Page', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 9,
				'min' => 0,
				'condition' => [
					'query_selection!' => 'current',
				],
			]
		);

		$this->add_control(
			'query_offset',
			[
				'label' => esc_html__( 'Offset', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'condition' => [
					'query_selection' => [ 'dynamic', 'current' ],
				]
			]
		);

		$this->add_control(
			'query_not_found_text',
			[
				'label' => esc_html__( 'Not Found Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'No Products Found!',
				'condition' => [
					'query_selection' => [ 'dynamic', 'current' ],
				]
			]
		);

		$this->add_control(
			'query_randomize',
			[
				'label' => esc_html__( 'Randomize Query', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'rand',
				'condition' => [
					'query_selection' => [ 'manual', 'current' ],
				]
			]
		);

		$this->add_control(
			'query_exclude_no_images',
			[
				'label' => esc_html__( 'Exclude Items without Thumbnail', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);		

		$this->add_control(
			'query_exclude_out_of_stock',
			[
				'label' => esc_html__( 'Exclude Out Of Stock', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);

		$this->add_control(
			'current_query_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => 
             		'To set <strong>Posts per Page</strong> for all <strong>Shop Pages</strong>, navigate to <strong><a href="'.esc_url(admin_url( '?page=spexo-welcome&tab=settings' )).'" target="_blank">Spexo Addons > Settings<a></strong>.',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
					'query_selection' => 'current',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Layout -----------
		$this->start_controls_section(
			'section_grid_layout',
			[
				'label' => esc_html__( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_layout_select();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'layout_select', ['pro-ms'] );

		$this->add_control(
			'stick_last_element_to_bottom',
			[
				'label' => esc_html__( 'Last Element to Bottom', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'render_type' => 'template',
				// 'separator' => 'before',
				'condition' => [
					'layout_select' => 'fitRows',
				]
			]
		);

		$this->add_control(
            'last_element_position',
            [
                'label' => esc_html__( 'Last Element Position', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors_dictionary' => [
					'left' => 'left: 0; right: auto;',
					'center' => 'left: 50%; transform: translateX(-50%);',
					'right' => 'left: auto; right: 0;'
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-grid-last-element-yes .tmpcoder-grid-item-below-content>div:last-child' => '{{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
            ]
        );

		$this->add_control(
			'tmpcoder_fallback_image_switch',
			[
				'label' => esc_html__( 'Show Fallback Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tmpcoder_fallback_image',
			[
				'label' => esc_html__( 'Fallback Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'separator' => 'after',
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => TMPCODER_ADDONS_ASSETS_URL.'images/woo-placeholder.png',
				],
				'condition' => [
					'tmpcoder_fallback_image_switch' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'layout_image_crop',
				'default' => 'full',
			]
		);

		$this->add_control_layout_columns();

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'grid_columns_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Grid Columns</span> option is fully supported<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-woo-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		// Media
		$this->add_control(
			'layout_list_media_section',
			[
				'label' => esc_html__( 'Media', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'list',
				],
			]
		);

		$this->add_control(
			'layout_list_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
					'right' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
					'zigzag' => esc_html__( 'ZigZag', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'layout_select' => 'list',
				],
			]
		);

		$this->add_control(
			'layout_list_media_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'layout_select' => 'list',
				]
			]
		);

		$this->add_control(
			'layout_list_media_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
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
				'condition' => [
					'layout_select' => 'list',
				]
			]
		);

		$this->add_responsive_control(
			'layout_gutter_hr',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'widescreen_default' => [
					'size' => 20,
				],
				'laptop_default' => [
					'size' => 20,
				],
				'tablet_extra_default' => [
					'size' => 20,
				],
				'tablet_default' => [
					'size' => 20,
				],
				'mobile_extra_default' => [
					'size' => 20,
				],
				'mobile_default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'layout_gutter_vr',
			[
				'label' => esc_html__( 'Vertical Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'widescreen_default' => [
					'size' => 30,
				],
				'laptop_default' => [
					'size' => 30,
				],
				'tablet_extra_default' => [
					'size' => 30,
				],
				'tablet_default' => [
					'size' => 30,
				],
				'mobile_extra_default' => [
					'size' => 30,
				],
				'mobile_default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				],
				'frontend_available' => true
			]
		);

		$this->add_control_sort_and_results_count();

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'sort_and_results_count_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Grid Sorting</span> option is available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-woo-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->add_responsive_control(
			'layout_filters',
			[
				'label' => esc_html__( 'Show Filters', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters' => 'display:{{VALUE}};',
				],
				'render_type' => 'template',
				// 'separator' => 'before',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);

		$this->add_control(
			'layout_pagination',
			[
				'label' => esc_html__( 'Show Pagination', 'sastra-essential-addons-for-elementor' ),
				'description' => esc_html__('Please note that Pagination doesn\'t work in editor', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'render_type' => 'template',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);

		$this->add_control_open_links_in_new_tab();

		$this->add_control_layout_animation();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'layout_animation', ['pro-fd', 'pro-fs'] );

		$this->add_control(
			'layout_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					'layout_animation!' => 'default',
					'layout_select!' => 'slider',
				],
			]
		);

		$this->add_control(
			'layout_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.05,
				'condition' => [
					'layout_animation!' => 'default',
					'layout_select!' => 'slider',
				],
			]
		);

		$this->add_control_layout_slider_amount();

		$this->add_control(
			'layout_slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'frontend_available' => true,
				'default' => 2,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'layout_slider_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],			
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'layout_slider_amount!' => '1',
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'layout_slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow' => 'display:{{VALUE}} !important;',
				],
				'separator' => 'before',
				'frontend_available' => true,
				'condition' => [
					'layout_select' => 'slider',
				]
			]
		);

		$this->add_control_layout_slider_nav_hover();

		$this->add_control(
			'layout_slider_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg-angle-1-left',
				'options' => tmpcoder_get_svg_icons_array( 'arrows', [
					'fas fa-angle-left' => esc_html__( 'Angle', 'sastra-essential-addons-for-elementor' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'sastra-essential-addons-for-elementor' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'sastra-essential-addons-for-elementor' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'sastra-essential-addons-for-elementor' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'sastra-essential-addons-for-elementor' ),
				] ),
				'separator' => 'after',
				'condition' => [
					'layout_slider_nav' => 'yes',
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'layout_slider_dots',
			[
				'label' => esc_html__( 'Pagination', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-table'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-dots' => 'display:{{VALUE}};',
				],
				'condition' => [
					'layout_select' => 'slider',
				],
                'frontend_available' => true,
			]
		);

		$this->add_control_layout_slider_dots_position();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'layout_slider_dots_position', ['pro-vr'] );

		$this->add_control_stack_layout_slider_autoplay();

		$this->add_control(
			'layout_slider_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'after',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
		
		$this->add_control(
			'layout_slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'sastra-essential-addons-for-elementor' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'sastra-essential-addons-for-elementor' ),
					'fade' => esc_html__( 'Fade', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'layout_slider_amount' => 1,
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'layout_slider_effect_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					// 'layout_slider_amount' => 1,
					'layout_select' => 'slider',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Upsell / Cross-sell Title
		$this->start_controls_section(
			'section_grid_linked_products',
			[
				'label' => esc_html__( 'Upsell / Cross-sell / Related Product Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'query_selection' => ['upsell', 'cross-sell' , 'related-product'],
					// 'layout_select!' => 'slider'
				]
			]
		);

		$this->add_control(
			'grid_linked_products_heading',
			[
				'label' => esc_html__( 'Heading', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'You may be interested in...',
				'condition' => [
					'query_selection' => ['upsell', 'cross-sell','related-product'],
				]
			]
		);

		$this->add_control(
			'grid_linked_products_heading_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h2',
				'condition' => [
					'query_selection' => ['upsell'],
					'grid_linked_products_heading!' => ''
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->add_section_grid_sorting();

		$this->tmpcoder_section_filter_options();

		$this->tmpcoder_pagination_tab_content();

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_grid_elements',
			[
				'label' => esc_html__( 'Elements', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$element_select = $this->add_option_element_select();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => $element_select,
				'separator' => 'after'
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'woo-grid', 'element_select', ['pro-lk', 'pro-shr', 'pro-sd', 'pro-ws', 'pro-cm'] );

		// tmpcoder_upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'grid', 'element_select', [] );

		$repeater->add_control(
			'element_location',
			[
				'label' => esc_html__( 'Location', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'below',
				'options' => [
					'above' => esc_html__( 'Above Media', 'sastra-essential-addons-for-elementor' ),
					'over' => esc_html__( 'Over Media', 'sastra-essential-addons-for-elementor' ),
					'below' => esc_html__( 'Below Media', 'sastra-essential-addons-for-elementor' ),
				]
			]
		);

		$repeater->add_control(
			'element_display',
			[
				'label' => esc_html__( 'Display', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'inline' => esc_html__( 'Inline', 'sastra-essential-addons-for-elementor' ),
					'block' => esc_html__( 'Seperate Line', 'sastra-essential-addons-for-elementor' ),
					'custom' => esc_html__( 'Custom Width', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$repeater->add_control(
			'element_custom_width',
			[
				'label' => esc_html__( 'Element Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'element_display' => 'custom',
				],
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$repeater->add_control(
	            'element_align_pro_notice',
	            [
					'raw' => 'Vertical Align option is available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-woo-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'tmpcoder-pro-notice',
					'condition' => [
						'element_location' => 'over',
					],
				]
	        );
		}

		$repeater->add_control(
			'element_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'middle',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
            'element_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
            ]
        );

		$repeater->add_control(
			'tmpcoder_enable_title_attribute',
			[
				'label' => esc_html__( 'Enable Title Attribute', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'render_type' => 'template',
				'condition' => [
					'element_select' => 'title',
				]
			]
		);

		$repeater->add_control(
			'element_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h3',
				'condition' => [
					'element_select' => 'title',
				]
			]
		);

		$repeater->add_control(
			'element_trim_text_by',
			[
				'label' => esc_html__( 'Trim Text By', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'word_count',
				'options' => $this->add_repeater_args_element_trim_text_by(),
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'title', 'excerpt' ],
				]
			]
		);

		$repeater->add_control(
			'element_word_count',
			[
				'label' => esc_html__( 'Word Count', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'title', 'excerpt' ],
					'element_trim_text_by' => 'word_count'
				]
			]
		);

		$repeater->add_control(
			'element_letter_count',
			[
				'label' => esc_html__( 'Letter Count', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 40,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'title', 'excerpt' ],
					'element_trim_text_by' => 'letter_count'
				]
			]
		);

		$repeater->add_control(
			'element_tax_sep',
			[
				'label' => esc_html__( 'Separator', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ', ',
				'condition' => [
					'element_select!' => [
						'title',
						'likes',
						'sharing',
						'lightbox',
						'separator',
						'post_format',
						'status',
						'price',
						'rating',
						'add-to-cart',
						'wishlist-button',
						'compare-button'
					],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_sale_dates_layout',
			[
				'label' => esc_html__( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'sastra-essential-addons-for-elementor' ),
					'block' => esc_html__( 'Block', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'element_select' => [
						'sale_dates',
					]
				]
			]
		);

		$repeater->add_control(
			'element_sale_dates_sep',
			[
				'label' => esc_html__( 'Separator', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => ' - ',
				'condition' => [
					'element_select' => [
						'sale_dates',
					],
					'element_sale_dates_layout' => 'inline'
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control( 'element_custom_field', $this->add_repeater_args_element_custom_field() );

		$repeater->add_control( 'element_custom_field_btn_link', $this->add_repeater_args_element_custom_field_btn_link() );

		$repeater->add_control( 'element_custom_field_style', $this->add_repeater_args_element_custom_field_style() );

		$repeater->add_control( 'element_custom_field_new_tab', $this->add_repeater_args_element_custom_field_new_tab() );

		$repeater->add_control( 'element_like_icon', $this->add_repeater_args_element_like_icon() );

		$repeater->add_control( 'element_like_show_count', $this->add_repeater_args_element_like_show_count() );

		$repeater->add_control( 'element_like_text', $this->add_repeater_args_element_like_text() );

		$repeater->add_control( 'element_sharing_icon_1', $this->add_repeater_args_element_sharing_icon_1() );

		$repeater->add_control( 'element_sharing_icon_2', $this->add_repeater_args_element_sharing_icon_2() );

		$repeater->add_control( 'element_sharing_icon_3', $this->add_repeater_args_element_sharing_icon_3() );

		$repeater->add_control( 'element_sharing_icon_4', $this->add_repeater_args_element_sharing_icon_4() );

		$repeater->add_control( 'element_sharing_icon_5', $this->add_repeater_args_element_sharing_icon_5() );

		$repeater->add_control( 'element_sharing_icon_6', $this->add_repeater_args_element_sharing_icon_6() );

		$repeater->add_control( 'element_sharing_trigger', $this->add_repeater_args_element_sharing_trigger() );

		$repeater->add_control( 'element_sharing_trigger_icon', $this->add_repeater_args_element_sharing_trigger_icon() );

		$repeater->add_control( 'element_sharing_trigger_action', $this->add_repeater_args_element_sharing_trigger_action() );

		$repeater->add_control( 'element_sharing_trigger_direction', $this->add_repeater_args_element_sharing_trigger_direction() );

		$repeater->add_control( 'element_sharing_tooltip', $this->add_repeater_args_element_sharing_tooltip() );

		$repeater->add_control(
			'element_lightbox_pfa_select',
			[
				'label' => esc_html__( 'Post Format Audio', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'sastra-essential-addons-for-elementor' ),
					'meta' => esc_html__( 'Meta Value', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfa_meta',
			[
				'label' => esc_html__( 'Audio Meta Value', 'sastra-essential-addons-for-elementor' ),
				// 'type' => Controls_Manager::SELECT2,
				'type' => 'tmpcoder-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				// 'options' => $post_meta_keys[1],
				'options' => 'ajaxselect2/get_custom_meta_keys',
				'query_slug' => 'product_cat',
				'condition' => [
					'element_select' => 'lightbox',
					'element_lightbox_pfa_select' => 'meta',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfv_select',
			[
				'label' => esc_html__( 'Post Format Video', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'sastra-essential-addons-for-elementor' ),
					'meta' => esc_html__( 'Meta Value', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfv_meta',
			[
				'label' => esc_html__( 'Video Meta Value', 'sastra-essential-addons-for-elementor' ),
				// 'type' => Controls_Manager::SELECT2,
				'type' => 'tmpcoder-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				// 'options' => $post_meta_keys[1],
				'options' => 'ajaxselect2/get_custom_meta_keys',
				'query_slug' => 'product_cat',
				'condition' => [
					'element_select' => 'lightbox',
					'element_lightbox_pfv_select' => 'meta',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'lightbox' ],
				],
			]
		);

		$repeater->add_control(
			'element_separator_style',
			[
				'label' => esc_html__( 'Select Styling', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tmpcoder-grid-sep-style-1',
				'options' => [
					'tmpcoder-grid-sep-style-1' => esc_html__( 'Separator Style 1', 'sastra-essential-addons-for-elementor' ),
					'tmpcoder-grid-sep-style-2' => esc_html__( 'Separator Style 2', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'element_select' => 'separator',
				]
			]
		);

		$repeater->add_control(
			'element_rating_style',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => 'Icon 1',
					'style-2' => 'Icon 2',
				],
				'default' => 'style-2',
				'condition' => [
					'element_select' => 'rating',
				],
			]
		);

		$repeater->add_control(
			'element_rating_score',
			[
				'label' => esc_html__( 'Show Score', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => 'rating',
				],
			]
		);

		$repeater->add_control(
			'element_rating_unmarked_style',
			[
				'label' => esc_html__( 'Unmarked Style', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'solid' => [
						'title' => esc_html__( 'Solid', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-star',
					],
					'outline' => [
						'title' => esc_html__( 'Outline', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-star-o',
					],
				],
				'default' => 'outline',
				'condition' => [
					'element_select' => 'rating',
					'element_rating_score!' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'element_status_offstock',
			[
				'label' => esc_html__( 'Show Out of Stock Badge', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => 'status',
				],
			]
		);

		$repeater->add_control(
			'element_status_instock',
			[
				'label' => esc_html__( 'Show In Stock Badge', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'element_select' => 'status',
				],
			]
		);

		$repeater->add_control(
			'element_status_featured',
			[
				'label' => esc_html__( 'Show Featured Badge', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_select' => 'status',
				],
			]
		);

		$repeater->add_control(
			'element_addcart_simple_txt',
			[
				'label' => esc_html__( 'Simple Item Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Add to Cart',
				'condition' => [
					'element_select' => 'add-to-cart',
				]
			]
		);

		$repeater->add_control(
			'element_addcart_grouped_txt',
			[
				'label' => esc_html__( 'Grouped Item Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Select Options',
				'condition' => [
					'element_select' => 'add-to-cart',
				]
			]
		);

		$repeater->add_control(
			'element_addcart_variable_txt',
			[
				'label' => esc_html__( 'Variable Item Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'View Products',
				'separator' => 'after',
				'condition' => [
					'element_select' => 'add-to-cart',
				]
			]
		);

		$repeater->add_control( 'element_show_added_tc_popup', $this->add_repeater_args_element_show_added_tc_popup() );

		$repeater->add_control( 'element_show_added_to_wishlist_popup', $this->add_repeater_args_element_show_added_to_wishlist_popup() );

		$repeater->add_control( 'element_show_added_to_compare_popup', $this->add_repeater_args_element_show_added_to_compare_popup() );

		$repeater->add_control(
			'element_open_links_in_new_tab',
			[
				'label' => esc_html__( 'Open Links in New Tab', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'element_select' => ['wishlist-button', 'compare-button']
				]
			]
		);

		$repeater->add_control(
			'element_extra_text_pos',
			[
				'label' => esc_html__( 'Extra Text Display', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'before' => esc_html__( 'Before Element', 'sastra-essential-addons-for-elementor' ),
					'after' => esc_html__( 'After Element', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'separator',
						'status',
						'price',
						'sale_dates',
						'rating',
						'add-to-cart',
						'wishlist-button',
						'compare-button',
						'excerpt'
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'condition' => [
					'element_select!' => [
						'title',
						'separator',
						'status',
						'price',
						'sale_dates',
						'rating',
						'add-to-cart',
						'wishlist-button',
						'compare-button',
						'excerpt'
					],
					'element_extra_text_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'show_sale_starts_date',
			[
				'label' => esc_html__( 'Sale Starts Date', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [
						'sale_dates'
					]
				],
			]
		);

		$repeater->add_control(
			'element_sale_starts_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'element_select' => [
						'sale_dates'
					],
					'show_sale_starts_date' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'show_sale_ends_date',
			[
				'label' => esc_html__( 'Sale Ends Date', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [
						'sale_dates'
					]
				],
			]
		);

		$repeater->add_control(
			'element_sale_ends_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'element_select' => [
						'sale_dates'
					],
					'show_sale_ends_date' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon_pos',
			[
				'label' => esc_html__( 'Extra Icon Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'before' => esc_html__( 'Before Element', 'sastra-essential-addons-for-elementor' ),
					'after' => esc_html__( 'After Element', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'separator',
						'likes',
						'sharing',
						'status',
						'price',
						'sale_dates',
						'rating',
						'excerpt',
						'wishlist-button',
						'compare-button'
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'element_select!' => [
						'title',
						'separator',
						'likes',
						'sharing',
						'status',
						'price',
						'rating',
						'wishlist-button',
						'compare-button'
					],
					'element_extra_icon_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'element_select' => [
						'wishlist-button',
						'compare-button'
					]
				]
			]
		);

		$repeater->add_control(
			'add_wishlist_icon',
			[
				'label' => esc_html__( 'Add Wishlist Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-heart',
					'library' => 'fa-solid',
				],
				'condition' => [
					'element_select' => 'wishlist-button',
					'show_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'remove_wishlist_icon',
			[
				'label' => esc_html__( 'Remove Wishlist Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-heart',
					'library' => 'fa-solid',
				],
				'condition' => [
					'element_select' => 'wishlist-button',
					'show_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'show_text',
			[
				'label' => esc_html__( 'Show Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'element_select' => [
						'wishlist-button',
						'compare-button'
					]
				]
			]
		);

		$repeater->add_control(
			'add_to_wishlist_text',
			[
				'label' => esc_html__( 'Add Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Add to Wishlist',
				'condition' => [
					'element_select' => [
						'wishlist-button'
					]
				]
			]
		);

		$repeater->add_control(
			'add_to_compare_text',
			[
				'label' => esc_html__( 'Add Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Add to Compare',
				'condition' => [
					'element_select' => [
						'compare-button'
					]
				]
			]
		);

		$repeater->add_control(
			'remove_from_wishlist_text',
			[
				'label' => esc_html__( 'Remove Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Remove from Wishlist',
				'condition' => [
					'element_select' => [
						'wishlist-button'
					]
				]
			]
		);

		$repeater->add_control(
			'remove_from_compare_text',
			[
				'label' => esc_html__( 'Remove Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Remove from Compare',
				'condition' => [
					'element_select' => [
						'compare-button'
					]
				]
			]
		);

		$repeater->add_control(
			'animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-animations',
				'default' => 'none',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'woo-grid', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt',] );

		$repeater->add_control(
			'element_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
			'element_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-animation-wrap:hover {{CURRENT_ITEM}}' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => tmpcoder_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'woo-grid', 'element_animation_timing', tmpcoder_animation_timing_pro_conditions() );

		$repeater->add_control(
			'element_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'sastra-essential-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
					'large' => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'large',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_disable_mobile',
			[
				'label' => esc_html__( 'Disable on Mobile/Tablet', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_responsive_control(
			'element_show_on',
			[
				'label' => esc_html__( 'Show on this Device', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'position: absolute; left: -99999999px;',
					'yes' => 'position: static; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'grid_elements',
			[
				'label' => esc_html__( 'Grid Elements', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					/* [
						'element_select' => 'status',
						'element_location' => 'over',
						'element_align_vr' => 'middle',
						'element_align_hr' => 'middle',
						'element_animation' => 'fade-in',
					], */
                    [
                        'element_select' => 'title',
                    ],
					[
						'element_select' => 'product_cat',
					],
					[
                        'element_select' => 'price',
					],
                    [
                        'element_select' => 'rating',
                    ],
					[
						'element_select' => 'add-to-cart',
					],
				],
				'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
				'prevent_empty' => false,
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'media_overlay_on_off',
			[
				'label' => esc_html__( 'Media Overlay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
			]
		);	

		$this->add_responsive_control(
			'overlay_width',
			[
				'label' => esc_html__( 'Overlay Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
				'condition' => ['media_overlay_on_off' => 'yes']
			]
		);

		$this->add_responsive_control(
			'overlay_hegiht',
			[
				'label' => esc_html__( 'Overlay Hegiht', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
				'condition' => ['media_overlay_on_off' => 'yes']
			]
		);

		$this->add_control(
			'overlay_post_link',
			[
				'label' => esc_html__( 'Link to Single Page', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'after',
				// 'condition' => ['media_overlay_on_off' => 'yes']
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-animations-alt',
				'default' => 'fade-in',
				'condition' => ['media_overlay_on_off' => 'yes']
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt',] );

		$this->add_control(
			'overlay_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
					'media_overlay_on_off' => 'yes'
				],
			]
		);

		$this->add_control(
			'overlay_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-animation-wrap:hover .tmpcoder-grid-media-hover-bg' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
					'media_overlay_on_off' => 'yes'
				],
			]
		);

		$this->add_control(
			'overlay_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => tmpcoder_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'overlay_animation!' => 'none',
					'media_overlay_on_off' => 'yes'
				],
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'overlay_animation_timing', tmpcoder_animation_timing_pro_conditions() );

		$this->add_control(
			'overlay_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'sastra-essential-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
					'large' => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'large',
				'condition' => [
					'overlay_animation!' => 'none',
					'media_overlay_on_off' => 'yes'
				],
			]
		);

		$this->add_control(
			'overlay_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'overlay_animation!' => 'none',
					'media_overlay_on_off' => 'yes'
				],
			]
		);

		$this->add_control_overlay_animation_divider();

		$this->add_control_overlay_image();

		$this->add_control_overlay_image_width();

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Image Effects ----
		$this->start_controls_section(
			'section_image_effects',
			[
				'label' => esc_html__( 'Image Effects', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_secondary_img_on_hover();

		$this->add_control_image_effects();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'image_effects', ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'] );

		$this->add_control(
			'image_effects_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-media-wrap img' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-media-wrap:hover img' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => tmpcoder_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'image_effects_animation_timing', tmpcoder_animation_timing_pro_conditions() );

		$this->add_control(
			'image_effects_size',
			[
				'label' => esc_html__( 'Animation Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'sastra-essential-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
					'large' => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'medium',
				'condition' => [
					'image_effects!' => ['none', 'slide'],
				]
			]
		);

		$this->add_control(
			'image_effects_direction',
			[
				'label' => esc_html__( 'Animation Direction', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'Top', 'sastra-essential-addons-for-elementor' ),
					'right' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
					'bottom' => esc_html__( 'Bottom', 'sastra-essential-addons-for-elementor' ),
					'left' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'bottom',
				'condition' => [
					'image_effects!' => 'none',
					'image_effects' => 'slide'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Lightbox Popup ----
		$this->start_controls_section(
			'section_lightbox_popup',
			[
				'label' => esc_html__( 'Lightbox Popup', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'lightbox_popup_autoplay',
			[
				'label' => esc_html__( 'Autoplay Slides', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_progressbar',
			[
				'label' => esc_html__( 'Show Progress Bar', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'lightbox_popup_autoplay' => 'true'
				]
			]
		);

		$this->add_control(
			'lightbox_popup_pause',
			[
				'label' => esc_html__( 'Autoplay Speed', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'condition' => [
					'lightbox_popup_autoplay' => 'true',
				],
			]
		);

		$this->add_control(
			'lightbox_popup_counter',
			[
				'label' => esc_html__( 'Show Counter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_captions',
			[
				'label' => esc_html__( 'Show Captions', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control_lightbox_popup_thumbnails();

		$this->add_control_lightbox_popup_thumbnails_default();

		$this->add_control_lightbox_popup_sharing();

		$this->add_control(
			'lightbox_popup_zoom',
			[
				'label' => esc_html__( 'Show Zoom Button', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_fullscreen',
			[
				'label' => esc_html__( 'Show Full Screen Button', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_download',
			[
				'label' => esc_html__( 'Show Download Button', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// $this->add_section_grid_sorting();


		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'woo-grid', [
			'Grid Columns 1,2,3,4,5,6',
			'Masonry Layout',
			'Products Slider Columns (Carousel) 1,2,3,4,5,6',
			'Secondary Featured Image',
			'Current Page Query, Random Products Query',
			'Infinite Scrolling Pagination',
			'Products Slider Autoplay options',
			'Products Slider Advanced Navigation Positioning',
			'Products Slider Advanced Pagination Positioning',
			'Advanced Products Likes',
			'Advanced Products Sharing',
			'Advanced Grid Loading Animations (Fade in & Slide Up)',
			'Advanced Grid Elements Positioning',
			'Unlimited Image Overlay Animations',
			'Image overlay GIF upload option',
			'Image Overlay Blend Mode',
			'Image Effects: Zoom, Grayscale, Blur',
			'Lightbox Thumbnail Gallery, Lightbox Image Sharing Button',
			'Grid Category Filter Deeplinking',
			'Grid Category Filter Icons select',
			'Grid Category Filter Count',
			'Grid Item Even/Odd Background Color',
			'Title, Category, Read More Advanced Link Hover Animation',
			'Open Links in New Tab',
			'Custom Fields/Attributes Support (Pro)',
			'Wishlist & Compare Buttons (Pro)'
		] );
		
		// Styles ====================
		// Section: Grid Item --------
		$this->start_controls_section(
			'section_style_grid_item',
			[
				'label' => esc_html__( 'Grid Item', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'grid_item_styles_selector',
			[
				'label' => esc_html__( 'Apply Styles To', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inner' => esc_html__( 'Inner Elements', 'sastra-essential-addons-for-elementor' ),
					'wrapper' => esc_html__( 'Wrapper', 'sastra-essential-addons-for-elementor' )
				],
				'default' => 'inner',
				'prefix_class' => 'tmpcoder-item-styles-'
			]
		);

		$this->add_control(
			'grid_item_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-above-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-item-below-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.tmpcoder-item-styles-wrapper .tmpcoder-grid-item' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'grid_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-item-styles-inner .tmpcoder-grid-item-above-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.tmpcoder-item-styles-inner .tmpcoder-grid-item-below-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.tmpcoder-item-styles-wrapper .tmpcoder-grid-item' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control_grid_item_even_bg_color();

		$this->add_control_grid_item_even_border_color();

		$this->add_control(
			'grid_item_border_type',
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
					'{{WRAPPER}}.tmpcoder-item-styles-inner .tmpcoder-grid-item-above-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.tmpcoder-item-styles-inner .tmpcoder-grid-item-below-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.tmpcoder-item-styles-wrapper .tmpcoder-grid-item' => 'border-style: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grid_item_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-item-styles-inner .tmpcoder-grid-item-above-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-item-styles-inner .tmpcoder-grid-item-below-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-item-styles-wrapper .tmpcoder-grid-item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'grid_item_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'grid_item_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-item-styles-wrapper .tmpcoder-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'grid_item_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-above-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-below-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'grid_item_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Grid Media -------
		$this->start_controls_section(
			'section_style_grid_media',
			[
				'label' => esc_html__( 'Grid Media', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'grid_media_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-image-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'grid_media_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-image-wrap' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_media_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-image-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'grid_media_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'grid_media_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => ['media_overlay_on_off' => 'yes']
			]
		);
		
		$this->add_control_overlay_color();

		$this->add_control_overlay_blend_mode();

		$this->add_control_overlay_border_color();

		$this->add_control_overlay_border_type();

		$this->add_control_overlay_border_width();

		$this->add_control(
			'overlay_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-media-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_title_style' );

		$this->start_controls_tab(
			'tab_grid_title_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block a' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_title_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'title_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_title_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_title_pointer();

		$this->add_control_title_pointer_height();

		$this->add_control_title_pointer_animation();

		$this->add_control(
			'title_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-item-title .tmpcoder-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-item-title .tmpcoder-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-title a'
			]
		);

		$this->add_control(
			'title_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'title_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 8,
					'left' => 0,
                    'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Excerpt ----------
		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label' => esc_html__( 'Excerpt', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-excerpt .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'excerpt_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-excerpt .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'excerpt_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-excerpt .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-excerpt'
			]
		);

		$this->add_responsive_control(
			'excerpt_justify',
			[
				'label' => esc_html__( 'Justify Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'widescreen_default' => '',
				'laptop_default' => '',
				'tablet_extra_default' => '',
				'tablet_default' => '',
				'mobile_extra_default' => '',
				'mobile_default' => '',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'text-align: justify;'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-excerpt .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'excerpt_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-item-excerpt .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'excerpt_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-excerpt .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'excerpt_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-excerpt .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'excerpt_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-excerpt .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Categories -------
		$this->start_controls_section(
			'section_style_categories',
			[
				'label' => esc_html__( 'Categories', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_categories_style' );

		$this->start_controls_tab(
			'tab_grid_categories_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'categories_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'categories_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'categories_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'categories_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block span[class*="tmpcoder-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'categories_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block [class*="tmpcoder-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block [class*="tmpcoder-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_categories_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'categories_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-product-categories .tmpcoder-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-product-categories .tmpcoder-pointer-item:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'categories_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'categories_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_categories_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_categories_pointer();

		$this->add_control_categories_pointer_height();

		$this->add_control_categories_pointer_animation();

		$this->add_control(
			'categories_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-product-categories .tmpcoder-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-product-categories .tmpcoder-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'categories_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-product-categories'
			]
		);

		$this->add_control(
			'categories_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'categories_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'categories_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'categories_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .tmpcoder-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-product-categories .tmpcoder-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'categories_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .tmpcoder-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-product-categories .tmpcoder-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'categories_gutter',
			[
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'categories_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'categories_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 8,
					'left' => 0,
                    'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'categories_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-categories .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Tags -------------
		$this->start_controls_section(
			'section_style_tags',
			[
				'label' => esc_html__( 'Tags', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_tags_style' );

		$this->start_controls_tab(
			'tab_grid_tags_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'tags_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tags_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tags_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tags_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block span[class*="tmpcoder-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tags_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block [class*="tmpcoder-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block [class*="tmpcoder-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_tags_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'tags_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-product-tags .tmpcoder-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-product-tags .tmpcoder-pointer-item:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tags_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tags_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_tags_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_tags_pointer();

		$this->add_control_tags_pointer_height();

		$this->add_control_tags_pointer_animation();

		$this->add_control(
			'tags_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-product-tags .tmpcoder-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-product-tags .tmpcoder-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tags_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-product-tags'
			]
		);

		$this->add_control(
			'tags_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tags_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'tags_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tags_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .tmpcoder-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-product-tags .tmpcoder-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tags_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .tmpcoder-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-product-tags .tmpcoder-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tags_gutter',
			[
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tags_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'tags_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'tags_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-product-tags .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Rating -----------
		$this->start_controls_section(
			'section_style_product_rating',
			[
				'label' => esc_html__( 'Rating', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'product_rating_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-woo-rating i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-woo-rating i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-woo-rating svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'product_rating_score_color',
			[
				'label' => esc_html__( 'Score Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-woo-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-woo-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-woo-rating svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-woo-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-woo-rating span' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_rating_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-woo-rating span'
			]
		);

		$this->add_responsive_control(
			'product_rating_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-rating .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Status -----------
		$this->start_controls_section(
			'section_style_product_status',
			[
				'label' => esc_html__( 'Status', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'product_status_os_color',
			[
				'label'  => esc_html__( 'On Sale Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > .tmpcoder-woo-onsale' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_os_bg_color',
			[
				'label'  => esc_html__( 'On Sale BG Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > .tmpcoder-woo-onsale' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_os_border_color',
			[
				'label'  => esc_html__( 'On Sale Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > .tmpcoder-woo-onsale' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'product_status_ft_color',
			[
				'label'  => esc_html__( 'Featured Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > .tmpcoder-woo-featured' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_ft_bg_color',
			[
				'label'  => esc_html__( 'Featured BG Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > .tmpcoder-woo-featured' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_ft_border_color',
			[
				'label'  => esc_html__( 'Featured Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > .tmpcoder-woo-featured' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'product_status_oos_color',
			[
				'label'  => esc_html__( 'Out of Stock Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > .tmpcoder-woo-outofstock' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_oos_bg_color',
			[
				'label'  => esc_html__( 'Out of Stock BG Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > .tmpcoder-woo-outofstock' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_oos_border_color',
			[
				'label'  => esc_html__( 'Out of Stock Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > .tmpcoder-woo-outofstock' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_status_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > span'
			]
		);

		$this->add_control(
			'product_status_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_status_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_status_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'product_status_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 10,
					'bottom' => 3,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'product_status_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 5,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'product_status_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'product_status_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-status .inner-block > span',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Price ------------
		$this->start_controls_section(
			'section_style_product_price',
			[
				'label' => esc_html__( 'Price', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_price_old_color',
			[
				'label'  => esc_html__( 'Old Price Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#33333399',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span del' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_price_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_price_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_price_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span'
			]
		);

		$this->add_control(
			'product_price_old_font_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Old Price Font Size', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span del' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'product_price_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_price_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_price_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'product_price_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'product_price_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 8,
					'left' => 0,
                    'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'product_price_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'product_price_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-price .inner-block > span',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Sale Dates ------------
		$this->start_controls_section(
			'section_style_product_sale_dates',
			[
				'label' => esc_html__( 'Sale Dates', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_control(
			'product_sale_dates_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-sale_dates .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'product_sale_dates_old_color',
			[
				'label'  => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-sale_dates .inner-block span.tmpcoder-grid-extra-text-left' => 'color: {{VALUE}} !important',
				],
			]
		);
		
		$this->add_control(
			'product_sale_dates_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-sale_dates .inner-block > span' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'product_sale_dates_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-sale_dates .inner-block > span.tmpcoder-sale-dates' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_sale_dates_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-sale_dates .inner-block > .tmpcoder-sale-dates'
			]
		);
		
		$this->add_control(
			'product_sale_dates_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-item-sale_dates .inner-block > .tmpcoder-sale-dates' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'product_sale_dates_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-sale_dates .inner-block > .tmpcoder-sale-dates' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_sale_dates_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);
		
		$this->add_responsive_control(
			'product_sale_dates_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-sale_dates .inner-block > .tmpcoder-sale-dates' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'product_sale_dates_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-sale_dates .inner-block > .tmpcoder-sale-dates' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);
		
		$this->add_control(
			'product_sale_dates_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-sale_dates .inner-block > .tmpcoder-sale-dates' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);
		
		$this->end_controls_section();

		// Styles ====================
		// Section: Add to Cart ------
		$this->start_controls_section(
			'section_style_add_to_cart',
			[
				'label' => esc_html__( 'Add to Cart', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_add_to_cart_style' );

		$this->start_controls_tab(
			'tab_grid_add_to_cart_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'add_to_cart_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_add_to_cart_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'add_to_cart_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
                'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a.tmpcoder-button-none:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a.added_to_cart:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a:after' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow_hr',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block :hover a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'add_to_cart_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control_add_to_cart_animation();

		$this->add_control(
			'add_to_cart_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control_add_to_cart_animation_height();

		$this->add_control(
			'add_to_cart_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-add-to-cart a'
			]
		);

		$this->add_control(
			'add_to_cart_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .tmpcoder-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .tmpcoder-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'add_to_cart_border_type',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'add_to_cart_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
                    'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'add_to_cart_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'add_to_cart_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 8,
					'right' => 25,
					'bottom' => 8,
					'left' => 25,
                    'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'add_to_cart_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 8,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
                    'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'add_to_cart_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
                    'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-add-to-cart .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Button Styles ------------
		$this->start_controls_section(
			'section_wishlist_button_styles',
			[
				'label' => esc_html__( 'Add to Wishlist', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
			'btn_color',
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
			'btn_bg_color',
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
		
		// Tab: Style ==============
		// Section: Button Styles ------------
		$this->start_controls_section(
			'section_compare_button_styles',
			[
				'label' => esc_html__( 'Add to Compare', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'comp_tabs_btn_styles' );

		$this->start_controls_tab(
			'comp_tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'comp_btn_text_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-add span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-add i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-add svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-add' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-add' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comp_btn_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-compare-add, {{WRAPPER}} .tmpcoder-compare-remove',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'comp_btn_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-compare-add span, {{WRAPPER}} .tmpcoder-compare-add i, .tmpcoder-compare-remove span, {{WRAPPER}} .tmpcoder-compare-remove i',
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
			'comp_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-add' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-compare-add span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-compare-add i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-compare-remove' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-compare-remove span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-compare-remove i' => 'transition-duration: {{VALUE}}s'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'comp_tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'comp_btn_hover_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4400',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-add:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-add:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-add:hover span' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_btn_hover_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4400',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-add:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_btn_hover_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-add:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comp_btn_box_shadow_hr',
				'selector' => '{{WRAPPER}} .tmpcoder-compare-add:hover, WRAPPER}} .tmpcoder-compare-remove:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'comp_tab_remove_btn',
			[
				'label' => esc_html__( 'Remove', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'comp_remove_btn_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4400',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-remove span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-remove i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-remove svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-remove:hover span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-remove:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-remove:hover svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_remove_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4F40',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-remove' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-remove:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_remove_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-remove' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-compare-remove:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'comp_button_padding',
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
					'{{WRAPPER}} .tmpcoder-compare-add' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-compare-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comp_button_margin',
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
					// '{{WRAPPER}} .tmpcoder-compare-add' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					// '{{WRAPPER}} .tmpcoder-compare-remove' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-compare-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'comp_button_border_type',
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
					'{{WRAPPER}} .tmpcoder-compare-add' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-compare-remove' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comp_button_border_width',
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
					'{{WRAPPER}} .tmpcoder-compare-add' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-compare-remove' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'comp_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-compare-add' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-compare-remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		$this->add_section_added_to_cart_popup();

		// Styles =======================
		// Section: Likes ---------------
		$this->add_section_style_likes();

		// Styles =========================
		// Section: Sharing ---------------
		$this->add_section_style_sharing();

		// Styles ====================
		// Section: Lightbox ---------
		$this->start_controls_section(
			'section_style_lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_lightbox_style' );

		$this->start_controls_tab(
			'tab_grid_lightbox_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'lightbox_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-lightbox i',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_lightbox_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'lightbox_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'lightbox_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'lightbox_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lightbox_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-lightbox'
			]
		);

		$this->add_control(
			'lightbox_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'lightbox_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'lightbox_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'lightbox_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .tmpcoder-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .tmpcoder-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'lightbox_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'lightbox_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'lightbox_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-lightbox .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Separator Style 1 
		$this->start_controls_section(
			'section_style_separator1',
			[
				'label' => esc_html__( 'Separator Style 1', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator1_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-1 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator1_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-1:not(.tmpcoder-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-sep-style-1.tmpcoder-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator1_height',
			[
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-1 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator1_border_type',
			[
				'label' => esc_html__( 'Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'sastra-essential-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'sastra-essential-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'sastra-essential-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'sastra-essential-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-1 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator1_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator1_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-1 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Separator Style 2 
		$this->start_controls_section(
			'section_style_separator2',
			[
				'label' => esc_html__( 'Separator Style 2', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator2_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-2 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator2_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-2:not(.tmpcoder-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-sep-style-2.tmpcoder-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator2_height',
			[
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-2 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_border_type',
			[
				'label' => esc_html__( 'Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'sastra-essential-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'sastra-essential-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'sastra-essential-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'sastra-essential-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-2 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator2_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-sep-style-2 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Navigation -------
		$this->start_controls_section(
			'tmpcoder__section_style_grid_slider_nav',
			[
				'label' => esc_html__( 'Slider Navigation', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_slider_nav_style' );

		$this->start_controls_tab(
			'tab_grid_slider_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'grid_slider_nav_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_slider_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'grid_slider_nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'grid_slider_nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow svg' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_font_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_size',
			[
				'label' => esc_html__( 'Box Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'grid_slider_nav_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'grid_slider_nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control_stack_grid_slider_nav_position();

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'tmpcoder__section_style_grid_slider_dots',
			[
				'label' => esc_html__( 'Slider Pagination', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_slider_dots' );

		$this->start_controls_tab(
			'tab_grid_slider_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'grid_slider_dots_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.35)',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_dots_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_slider_dots_active',
			[
				'label' => esc_html__( 'Active', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'grid_slider_dots_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-dots .slick-active .tmpcoder-grid-slider-dot' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'grid_slider_dots_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-dots .slick-active .tmpcoder-grid-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'grid_slider_dots_width',
			[
				'label' => esc_html__( 'Box Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-dot' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'grid_slider_dots_height',
			[
				'label' => esc_html__( 'Box Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-dot' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'grid_slider_dots_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-slider-dot' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grid_slider_dots_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'grid_slider_dots_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'grid_slider_dots_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit' => '%',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'grid_slider_dots_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-grid-slider-dots-horizontal .tmpcoder-grid-slider-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-grid-slider-dots-vertical .tmpcoder-grid-slider-dot' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control_grid_slider_dots_hr();
		
		$this->add_responsive_control(
			'grid_slider_dots_vr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 96,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-slider-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Upsell / Cross-sell Title
		$this->start_controls_section(
			'section_style_linked_products',
			[
				'label' => esc_html__( 'Upsell / Cross-sell / Related Product Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'query_selection' => ['upsell', 'cross-sell','related-product'],
					// 'layout_select!' => 'slider'
				]
			]
		);

		$this->add_control(
			'linked_products_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-linked-products-heading' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'linked_products',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-linked-products-heading *'
			]
		);

		$this->add_responsive_control(
			'linked_products_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 3,
					'right' => 15,
					'bottom' => 3,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-linked-products-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'linked_products_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Grid', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-linked-products-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
				// 'separator' => 'before'
			]
		);

		$this->add_control(
			'linked_products_alignment',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'default' => 'left',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-linked-products-heading *' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->add_section_style_sort_and_results();

		// Styles ====================
		// Section: Filters ----------
		$this->start_controls_section(
			'section_style_filters',
			[
				'label' => esc_html__( 'Filters', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_filters' => 'yes',
				],
			]
		);

		$this->add_control(
			'active_styles_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Apply active filter styles from the hover tab.', 'sastra-essential-addons-for-elementor'),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info'
			]
		);

		$this->start_controls_tabs( 'tabs_grid_filters_style' );

		$this->start_controls_tab(
			'tab_grid_filters_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'filters_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-filters li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filters_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li > a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-filters li > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'filters_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li > a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-filters li > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filters_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-filters li > a, {{WRAPPER}} .tmpcoder-grid-filters li > span',
			]
		);

		$this->add_control(
			'filters_wrapper_color',
			[
				'label'  => esc_html__( 'Wrapper Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_filters_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'filters_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li > a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-filters li > span:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-filters li > .tmpcoder-active-filter' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filters_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li > a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-filters li > span:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-filters li > .tmpcoder-active-filter' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'filters_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li > a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-filters li > span:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-filters li > .tmpcoder-active-filter' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control_filters_pointer_color_hr();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filters_box_shadow_hr',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-filters li > a:hover, {{WRAPPER}} .tmpcoder-grid-filters li > span:hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_filters_pointer();

		$this->add_control_filters_pointer_height();

		$this->add_control_filters_pointer_animation();

		$this->add_control(
			'filters_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li > a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-filters li > span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'filters_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-filters li'
			]
		);

		$this->add_control(
			'filters_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-filters li > a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-grid-filters li > span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'filters_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-filters li > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'filters_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'filters_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Grid', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'filters_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-filters-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 5,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 3,
					'right' => 15,
					'bottom' => 3,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-filters li > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'filters_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-filters li > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_pagination' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_pagination_style' );

		$this->start_controls_tab(
			'tab_grid_pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-pagination-finish' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-pagination a, {{WRAPPER}} .tmpcoder-grid-pagination > div > span',
			]
		);

		$this->add_control(
			'pagination_loader_color',
			[
				'label'  => esc_html__( 'Loader Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-double-bounce .tmpcoder-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-wave .tmpcoder-rect' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-spinner-pulse' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-chasing-dots .tmpcoder-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-three-bounce .tmpcoder-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-fading-circle .tmpcoder-circle:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ]
				]
			]
		);

		$this->add_control(
			'pagination_wrapper_color',
			[
				'label'  => esc_html__( 'Wrapper Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_pagination_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'pagination_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination a:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span:not(.tmpcoder-disabled-arrow):hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span:not(.tmpcoder-disabled-arrow):hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span:not(.tmpcoder-disabled-arrow):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow_hr',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-pagination a:hover, {{WRAPPER}} .tmpcoder-grid-pagination > div > span:not(.tmpcoder-disabled-arrow):hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pagination_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-pagination svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-pagination'
			]
		);

		$this->add_responsive_control(
			'pagination_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_border_type',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Grid', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'pagination_gutter',
			[
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-prev-post-link i' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-next-post-link i' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-first-page i' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-prev-page i' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-next-page i' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-last-page i' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-prev-post-link svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-next-post-link svg' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-first-page svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-prev-page svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-next-page svg' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination .tmpcoder-last-page svg' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Password Protected
		$this->start_controls_section(
			'section_style_pwd_protected',
			[
				'label' => esc_html__( 'Password Protected', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'pwd_protected_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-protected' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pwd_protected_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-protected' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pwd_protected_input_color',
			[
				'label'  => esc_html__( 'Input Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-protected input' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pwd_protected_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-protected p'
			]
		);

		$this->end_controls_section();

		// Styles =======================
		// Section: Custom Field Style 1
		$this->add_section_style_custom_field1();

		// Styles =======================
		// Section: Custom Field Style 2
		$this->add_section_style_custom_field2();
	}


	// Get Taxonomies Related to Post Type
	public function get_related_taxonomies() {
		$relations = [];
		$post_types = tmpcoder_get_custom_types_of( 'post', false );

		foreach ( $post_types as $slug => $title ) {
			$relations[$slug] = [];

			foreach ( get_object_taxonomies( $slug ) as $tax ) {
				array_push( $relations[$slug], $tax );
			}
		}

		return wp_json_encode( $relations );
	}

	// Get Max Pages
	public function get_max_num_pages( $settings ) {
		$query = new \WP_Query( $this->get_main_query_args() );
		$max_num_pages = intval( ceil( $query->max_num_pages ) );
		// Reset
		wp_reset_postdata();

		return $max_num_pages;
	}

	// Main Query Args
	public function get_main_query_args() {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if ( ! tmpcoder_is_availble() ) {
			$settings['query_selection'] = 'pro-cr' == $settings['query_selection'] ? 'dynamic' : $settings['query_selection'];
			$settings['query_orderby'] = 'pro-rn' == $settings['query_orderby'] ? 'date' : $settings['query_orderby'];
		}

		// Get Paged
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		
		if ( empty($settings['query_offset']) ) {
			$settings[ 'query_offset' ] = 0;
		}
		
		$query_posts_per_page = $settings['query_posts_per_page'];
		if ( empty($query_posts_per_page) ) {
			$query_posts_per_page = -1;
		}
		
		$offset = ( $paged - 1 ) * $query_posts_per_page + $settings[ 'query_offset' ];

		$ids_array = '';

		if ($settings['query_exclude_products']) {
			
			$slug_args = [
			    'post_type'      => 'product',
			    'posts_per_page' => -1,
			    'post_name__in'  => $settings[ 'query_exclude_products' ],
			    'fields'         => 'ids' 
			];
			
			$ids_array = get_posts( $slug_args );
		}

		// Dynamic
		$args = [
			'post_type' => 'product',
			'tax_query' => $this->get_tax_query_args(),
			'meta_query' => $this->get_meta_query_args(),
			'post__not_in' => $ids_array,
			'posts_per_page' => $settings['query_posts_per_page'],
			'orderby' => 'date',
			'paged' => $paged,
			'offset' => $offset
		];

		// Featured
		if ( 'featured' === $settings['query_selection'] ) {
			$args['tax_query'][] = [
				'taxonomy' => 'product_visibility',
				'field' => 'term_taxonomy_id',
				'terms' => wc_get_product_visibility_term_ids()['featured'],
			];
		}

		// On Sale
		if ( 'onsale' === $settings['query_selection'] ) {
			// $args['post__in'] = wc_get_product_ids_on_sale();
			$args['meta_query'] = array(
				'relation' => 'OR',
				array( // Simple products type
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				),
				array( // Variable products type
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				)
			);
		}
		
		if ( 'upsell' === $settings['query_selection'] ) {
			// Get Product
			$product = wc_get_product();
	
			if ( ! $product ) {
				return;
			}
	
			$meta_query = WC()->query->get_meta_query();
	
			$this->my_upsells = $product->get_upsell_ids();

			if ( !empty($this->my_upsells) ) {
				$args = array(
					'post_type' => 'product',
					'post__not_in' => $settings[ 'query_exclude_products' ],
					'ignore_sticky_posts' => 1,
					// 'no_found_rows' => 1,
					'posts_per_page' => $settings['query_posts_per_page'],
					'orderby' => 'post__in',
					'order' => $settings['order_direction'],
					'paged' => 2,
					'post__in' => $this->my_upsells,
					'meta_query' => $meta_query
				);
				
			} else {
				$args['post_type'] = ['none'];
			}
			
		}
		if ( 'related-product' === $settings['query_selection'] ) {

			// Get Product cat id 
			if (tmpcoder_is_elementor_editor_mode()) {
				$current_product_id = tmpcoder_get_last_product_id();
			}
			else
			{
				global $product;
				if ( ! $product ) {
					return;
				}
				$current_product_id = $product->get_id();
			}

			$cats_array = array();
			$terms = wp_get_post_terms( $current_product_id, 'product_cat' );
			foreach ( $terms as $term ) {
				$children = get_term_children( $term->term_id, 'product_cat' );
				if ( !sizeof( $children ) )
				$cats_array[] = $term->term_id;
			  }			  
			$meta_query = WC()->query->get_meta_query();
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

			if ( !empty($cats_array) ) {
				$args = array(
					'post_type' => 'product',
					'post__not_in' => array( $current_product_id ),
					'ignore_sticky_posts' => 1,
					// 'no_found_rows' => false,
					'posts_per_page' => $settings['query_posts_per_page'],
					'orderby' => 'post__in',
					'order' => $settings['order_direction'],
					'paged'      => $paged,
					'meta_query' => $meta_query,
					'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field' => 'id',
							'terms' => $cats_array,
						),
					),
				);		
			} else {
				$args['post_type'] = ['none'];
			}
		}


		if ( 'cross-sell' === $settings['query_selection'] ) {
			// Get Product
			$this->crossell_ids = [];
			
			if( is_cart() ) {
				$items = WC()->cart->get_cart();
	
				foreach($items as $item => $values) {
					$product = $values['data'];
					$cross_sell_products = $product->get_cross_sell_ids();
					foreach($cross_sell_products as $cs_product) {
						array_push($this->crossell_ids, $cs_product);
					}
				  }
			}

			if ( is_single() ) {
				$product = wc_get_product();
		
				if ( ! $product ) {
					return;
				}

				$this->crossell_ids = $product->get_cross_sell_ids();
			}
	
			// $meta_query = WC()->query->get_meta_query();
			
			if ( !empty($this->crossell_ids) ) {
				$args = [
					'post_type' => 'product',
					'post__not_in' => $settings[ 'query_exclude_products' ],
					'tax_query' => $this->get_tax_query_args(),
					'ignore_sticky_posts' => 1,
					// 'no_found_rows' => 1,
					'posts_per_page' => $settings['query_posts_per_page'],
					// 'orderby' => 'post__in',
					'order' => $settings['order_direction'],
					'paged' => $paged,
					'post__in' => $this->crossell_ids,
					// 'meta_query' => $meta_query
				];
			} else {
				$args['post_type'] = 'none';
			}
		}

		// Default Order By
		if ( 'sales' === $settings['query_orderby'] ) {
			$args['meta_key'] = 'total_sales';
			$args['order'] = $settings['order_direction'];
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'rating' === $settings['query_orderby'] ) {
			$args['meta_key'] = '_wc_average_rating';
			$args['order'] = $settings['order_direction'];
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'price-low' === $settings['query_orderby'] ) {
			$args['meta_key'] = '_price';
			$args['order'] = $settings['order_direction'];
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'price-high' === $settings['query_orderby'] ) {
			$args['meta_key'] = '_price';
			if($settings['order_direction'] == 'ASC') {
				$args['order'] = 'DESC';
			} else{
				$args['order'] = 'ASC';
			}	
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'random' === $settings['query_orderby'] ) {
			$args['orderby']  = 'rand';
		} elseif ( 'date' === $settings['query_orderby'] ) {
			$args['orderby']  = 'date';
		} else {
			$args['orderby']  = 'menu_order';
			$args['order']  = $settings['order_direction'];
		}

		// Exclude Items without F/Image
		if ( 'yes' === $settings['query_exclude_no_images'] ) {
			$args['meta_key'] = '_thumbnail_id';
		}

		// Exclude Out Of Stock
		if ( 'yes' === $settings['query_exclude_out_of_stock'] ) {
			$args['meta_query'] = [
				[
					'key'     => '_stock_status',
					'value'   => 'outofstock',
					'compare' => 'NOT LIKE',
				]
			];
		}

		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_products' ]) ) {
				$post_ids = $settings[ 'query_manual_products' ];
			}

			$args = [
				'post_type' => 'product',
				// 'post__in' => $post_ids,
				'post_name__in' => $post_ids,
				'posts_per_page' => $settings['query_posts_per_page'],
				'orderby' => $settings[ 'query_randomize' ],
				'paged' => $paged,
			];
		}

		// Get Post Type
		if ( 'current' === $settings[ 'query_selection' ] && true !== \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			global $wp_query;

			// Products Per Page
			if ( is_product_category() ) {
				$posts_per_page = intval(tmpcoder_get_settings('tmpcoder_woo_shop_cat_ppp', 6));
			} elseif ( is_product_tag() ) {
				$posts_per_page = intval(tmpcoder_get_settings('tmpcoder_woo_shop_tag_ppp', 6));
			} else {
				$posts_per_page = intval(tmpcoder_get_settings('tmpcoder_woo_shop_ppp', 6));
			}

			$args = $wp_query->query_vars;
			$args['tax_query'] = $this->get_tax_query_args();
			$args['meta_query'] = $this->get_meta_query_args();
			$args['posts_per_page'] = $posts_per_page;
			if (!empty($settings['query_randomize'])) {
				$args['orderby'] = $settings['query_randomize'];
			}
		}

		// Sorting
		if ( isset( $_GET['orderby'] ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( 'popularity' === $_GET['orderby'] ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'rating' === $_GET['orderby'] ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$args['meta_key'] = '_wc_average_rating';
				$args['order'] = $settings['order_direction'];
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'price' === $_GET['orderby'] ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$args['meta_key'] = '_price';
				$args['order'] = 'ASC';
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'price-desc' === $_GET['orderby'] ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$args['meta_key'] = '_price';
				$args['order'] = 'DESC';
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'random' === $_GET['orderby'] ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$args['orderby']  = 'rand';
			} elseif ( 'date' === $_GET['orderby'] ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$args['orderby']  = 'date';
			} else if ( 'title' === $_GET['orderby'] ){// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$args['orderby']  = 'title';
				$args['order'] = 'ASC';
			} else if ( 'title-desc' === $_GET['orderby'] ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$args['orderby']  = 'title';
				$args['order'] = 'DESC';
			} else {
				$args['order'] = $settings['order_direction'];
				$args['orderby']  = 'menu_order';
			}
		}

		// Search
		if ( isset( $_GET['psearch'] ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args['s'] = sanitize_text_field(wp_unslash($_GET['psearch']));// phpcs:ignore WordPress.Security.NonceVerification.Recommended
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

	// Meta Query Args
	public function get_meta_query_args(){
        $meta_query = WC()->query->get_meta_query();

		// Price Filter Args
        if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $meta_query = array_merge( ['relation' => 'AND'], $meta_query );
            $meta_query[] = [
                [
                    'key' => '_price',
                    'value' => [ wc_clean( sanitize_text_field(wp_unslash($_GET['min_price'])) ), wc_clean( sanitize_text_field(wp_unslash($_GET['max_price'])) ) ],// phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                ],
            ];
        }

		return $meta_query;
    }

	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

		// Disable Animation on Mobile
		if ( 'overlay' !== $object ) {
			if ( 'yes' === $data[$object .'_animation_disable_mobile'] && wp_is_mobile() ) {
				return $class;
			}
		}

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' tmpcoder-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' tmpcoder-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' tmpcoder-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' tmpcoder-anim-transparency';
			}
		}

		return $class;
	}

	// Get Image Effect Class
	public function get_image_effect_class( $settings ) {
		$class = '';

		if ( ! tmpcoder_is_availble() ) {
			if ( 'pro-zi' ==  $settings['image_effects'] || 'pro-zo' ==  $settings['image_effects'] || 'pro-go' ==  $settings['image_effects'] || 'pro-bo' ==  $settings['image_effects'] ) {
				$settings['image_effects'] = 'none';
			}
		}

		// Animation Class
		if ( 'none' !== $settings['image_effects'] ) {
			$class .= ' tmpcoder-'. $settings['image_effects'];
		}
		
		// Slide Effect
		if ( 'slide' !== $settings['image_effects'] ) {
			$class .= ' tmpcoder-effect-size-'. $settings['image_effects_size'];
		} else {
			$class .= ' tmpcoder-effect-dir-'. $settings['image_effects_direction'];
		}

		return $class;
	}

	// Render Password Protected Input
	public function render_password_protected_input( $settings ) {
		if ( ! post_password_required() ) {
			return;
		}

		add_filter( 'the_password_form', function () {
			$output  = '<form action="'. esc_url(home_url( 'wp-login.php?action=postpass' )) .'" method="post">';
			$output .= '<i class="fas fa-lock"></i>';
			$output .= '<p>'. esc_html(get_the_title()) .'</p>';
			$output .= '<input type="password" name="post_password" id="post-'. esc_attr(get_the_id()) .'" placeholder="'. esc_html__( 'Type and hit Enter...', 'sastra-essential-addons-for-elementor' ) .'">';
			$output .= '</form>';

			return $output;
		} );

		echo '<div class="tmpcoder-grid-item-protected tmpcoder-cv-container">';

			echo '<div class="tmpcoder-cv-outer">';
				echo '<div class="tmpcoder-cv-inner">';
					echo wp_kses(get_the_password_form(), tmpcoder_wp_kses_allowed_html()); 
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Thumbnail
	public function render_product_thumbnail( $settings ) {
		$id  = get_post_thumbnail_id();

		$src = Group_Control_Image_Size::get_attachment_image_src( $id, 'layout_image_crop', $settings );

		$settings['layout_image_crop'] = ['id' => $id];
		$product_image_html = Group_Control_Image_Size::get_attachment_image_html($settings,'layout_image_crop');

		$image_original_class = 'wp-image-'.$id;
		$custom_image_class = $image_original_class.' tmpcoder-anim-timing-'.$settings[ 'image_effects_animation_timing'];
		$product_image_html = str_replace($image_original_class, $custom_image_class, $product_image_html);

		if ('' === get_post_meta( $id, '_wp_attachment_image_alt', true )) {
			$product_image_html = preg_replace( '/<img(.*?)alt="(.*?)"(.*?)>/i', '<img$1alt="'.get_the_title().'"$3>', $product_image_html );
		}

		if ( 
			get_post_meta(get_the_ID(), 'tmpcoder_secondary_image_id') 
			&& !empty(get_post_meta(get_the_ID(), 'tmpcoder_secondary_image_id')) 
			&& tmpcoder_get_settings('tmpcoder_meta_secondary_image_product') === 'on'
		) {

			$src2 = Group_Control_Image_Size::get_attachment_image_src( get_post_meta(get_the_ID(), 'tmpcoder_secondary_image_id')[0], 'layout_image_crop', $settings );

			$settings['layout_image_crop'] = ['id' => get_post_meta(get_the_ID(), 'tmpcoder_secondary_image_id', true)];
			$second_image_html = Group_Control_Image_Size::get_attachment_image_html($settings,'layout_image_crop');

			$second_original_class = 'wp-image-'.get_post_meta(get_the_ID(), 'tmpcoder_secondary_image_id', true);
			$second_image_class = $second_original_class.' tmpcoder-anim-timing-'.$settings[ 'image_effects_animation_timing'];
			$product_image_html = str_replace($second_original_class, $second_image_class.' tmpcoder-hidden-img', $product_image_html);
		} else {
			$settings['secondary_img_on_hover'] = 'no';
			$second_image_html = '';
			$src2 = '';
		}

		// if ( has_post_thumbnail() ) {
			echo '<div class="tmpcoder-grid-image-wrap" data-src="'. esc_url( $src ) .'"  data-img-on-hover="'. esc_attr($settings['secondary_img_on_hover']) .'" data-src-secondary="'. esc_url( $src2 ) .'">';

				if (!has_post_thumbnail() && isset($settings['tmpcoder_fallback_image_switch']) && $settings['tmpcoder_fallback_image_switch'] == 'yes') {
						
					if (isset($settings['tmpcoder_fallback_image']['url']) && $settings['tmpcoder_fallback_image']['url'] != '') {
						$product_image_html = '<img src="'.esc_url($settings['tmpcoder_fallback_image']['url']).'" width="366" height="366" alt="'.esc_attr(get_the_title()).'">';
						$src = $settings['tmpcoder_fallback_image']['url'];
					}
				}

				echo wp_kses_post($product_image_html); 

				if ( 'yes' == $settings['secondary_img_on_hover'] && !empty($src2)) {	
					echo wp_kses_post($second_image_html);
				}
			echo '</div>';
		// }
	}

	// Render Media Overlay
	public function render_media_overlay( $settings ) {
		echo '<div class="tmpcoder-grid-media-hover-bg '. esc_attr($this->get_animation_class( $settings, 'overlay' )) .'" data-url="'. esc_url( get_the_permalink( get_the_ID() ) ) .'">';

			if ( tmpcoder_is_availble() ) {
				if ( !empty($settings['overlay_image']['url']) && '' !== $settings['overlay_image']['url'] ) {
					$overlay_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'overlay_image' );
					echo wp_kses_post($overlay_image);
				}
			}
		echo '</div>';
	}

	// Render Post Title
	public function render_product_title( $settings, $class ) {
		$title_pointer = ! tmpcoder_is_availble() ? 'none' : $this->get_settings()['title_pointer'];
		$title_pointer_animation = ! tmpcoder_is_availble() ? 'fade' : $this->get_settings()['title_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['title_pointer']) && 'none' !== $this->get_settings()['title_pointer']) ? 'class=tmpcoder-pointer-item' : '';
		$open_links_in_new_tab = 'yes' === $this->get_settings()['open_links_in_new_tab'] ? '_blank' : '_self';

		$class .= ' tmpcoder-pointer-'. $title_pointer;
		$class .= ' tmpcoder-pointer-line-fx tmpcoder-pointer-fx-'. $title_pointer_animation;

		echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['element_title_tag']) ) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';

				$title_tag = '';

				if (isset($settings['tmpcoder_enable_title_attribute']) && $settings['tmpcoder_enable_title_attribute'] == 'yes') {
					
					if ( 'word_count' === $settings['element_trim_text_by'] ) {
						$title_tag = esc_html(wp_trim_words( get_the_title(), $settings['element_word_count'] ));
					} else {
						$title_tag = esc_html(substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...');
					}
				}

				echo '<a title="'.esc_attr($title_tag).'" target="'. esc_attr($open_links_in_new_tab) .'"  '. esc_attr($pointer_item_class) .' href="'. esc_url( get_the_permalink() ) .'">';
				if ( 'word_count' === $settings['element_trim_text_by'] ) {
					echo esc_html(wp_trim_words( get_the_title(), $settings['element_word_count'] ));
				} else {
					echo esc_html(substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...');
				}
				echo '</a>';
			echo '</div>';
		echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['element_title_tag']) ) .'>';

		// do_action( 'woocommerce_shop_loop_item_title' );
	}

	// Render Post Excerpt
	public function render_product_excerpt( $settings, $class ) {
		if ( '' === get_the_excerpt() ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			if ( 'word_count' === $settings['element_trim_text_by']) {
			  echo '<p>'. esc_html(wp_trim_words( get_the_excerpt(), $settings['element_word_count'] )) .'</p>';
			} else {
			  // echo '<p>'. substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...' . '</p>';
			  echo '<p>'. esc_html(implode('', array_slice( str_split(get_the_excerpt()), 0, $settings['element_letter_count'] ))) .'...' .'</p>';
			}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Categories
	public function render_product_categories( $settings, $class, $post_id ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;

		// Pointer Class
		$categories_pointer = ! tmpcoder_is_availble() ? 'none' : $this->get_settings()['categories_pointer'];
		$categories_pointer_animation = ! tmpcoder_is_availble() ? 'fade' : $this->get_settings()['categories_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['categories_pointer']) && 'none' !== $this->get_settings()['categories_pointer']) ? 'class=tmpcoder-pointer-item' : '';

		$class .= ' tmpcoder-pointer-'. $categories_pointer;
		$class .= ' tmpcoder-pointer-line-fx tmpcoder-pointer-fx-'. $categories_pointer_animation;

		echo '<div class="'. esc_attr($class) .' tmpcoder-grid-product-categories">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="tmpcoder-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="tmpcoder-grid-extra-icon-left">';
					echo wp_kses($extra_icon, tmpcoder_wp_kses_allowed_html());
					echo '</span>';
				}

				// Taxonomies
				foreach ( $terms as $term ) {
					echo '<a '. esc_attr($pointer_item_class) .' href="'. esc_url(get_term_link( $term->term_id )) .'">'. esc_html( $term->name );
						if ( ++$count !== count( $terms ) ) {
							echo '<span class="tax-sep">'. esc_html($settings['element_tax_sep']) .'</span>';
						}
					echo '</a>';
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="tmpcoder-grid-extra-icon-right">';
					echo wp_kses($extra_icon, tmpcoder_wp_kses_allowed_html());
					echo '</span>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="tmpcoder-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Tags
	public function render_product_tags( $settings, $class, $post_id ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;

		// Pointer Class
		$tags_pointer = ! tmpcoder_is_availble() ? 'none' : $this->get_settings()['tags_pointer'];
		$tags_pointer_animation = ! tmpcoder_is_availble() ? 'fade' : $this->get_settings()['tags_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['tags_pointer']) && 'none' !== $this->get_settings()['tags_pointer']) ? 'class=tmpcoder-pointer-item' : '';

		$class .= ' tmpcoder-pointer-'. $tags_pointer;
		$class .= ' tmpcoder-pointer-line-fx tmpcoder-pointer-fx-'. $tags_pointer_animation;

		echo '<div class="'. esc_attr($class) .' tmpcoder-grid-product-tags">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="tmpcoder-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="tmpcoder-grid-extra-icon-left">';
					echo wp_kses($extra_icon, tmpcoder_wp_kses_allowed_html());
					echo '</span>';
				}

				// Taxonomies
				foreach ( $terms as $term ) {
					echo '<a '. esc_attr($pointer_item_class) .' href="'. esc_url(get_term_link( $term->term_id )) .'">'. esc_html( $term->name );
						if ( ++$count !== count( $terms ) ) {
							echo '<span class="tax-sep">'. esc_html($settings['element_tax_sep']) .'</span>';
						}
					echo '</a>';
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="tmpcoder-grid-extra-icon-right">';
					echo wp_kses($extra_icon, tmpcoder_wp_kses_allowed_html());
					echo '</span>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="tmpcoder-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Custom Fields/Attributes
	public function render_product_custom_fields( $settings, $class, $post_id ) {

	}

	// Render Post Likes
	public function render_product_likes( $settings, $class, $post_id ) {}

	// Render Post Sharing
	public function render_product_sharing_icons( $settings, $class ) {}

	// Render Post Lightbox
	public function render_product_lightbox( $settings, $class, $post_id ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = get_the_post_thumbnail_url( $post_id );

				// Audio Post Type
				if ( 'audio' === get_post_format() ) {
					// Load Meta Value
					if ( 'meta' === $settings['element_lightbox_pfa_select'] ) {
						// $utilities = new Utilities();
						$meta_value = get_post_meta( $post_id, $settings['element_lightbox_pfa_meta'], true );

						// URL
						if ( false === strpos( $meta_value, '<iframe ' ) ) {
							add_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50, 3 );
								$track_url = wp_oembed_get( $meta_value );
							remove_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50 );

						// Iframe
						} else {
							$track_url = tmpcoder_filter_oembed_results( $meta_value );
						}

						$lightbox_source = $track_url;
					}

				// Video Post Type
				} elseif ( 'video' === get_post_format() ) {
					// Load Meta Value
					if ( 'meta' === $settings['element_lightbox_pfv_select'] ) {
						$meta_value = get_post_meta( $post_id, $settings['element_lightbox_pfv_meta'], true );

						// URL
						if ( false === strpos( $meta_value, '<iframe ' ) ) {
							$video = \Elementor\Embed::get_video_properties( $meta_value );

						// Iframe
						} else {
							$video = \Elementor\Embed::get_video_properties( tmpcoder_filter_oembed_results($meta_value) );
						}

						// Provider URL
						if ( 'youtube' === $video['provider'] ) {
							$video_url = '//www.youtube.com/embed/'. $video['video_id'] .'?feature=oembed&autoplay=1&controls=1';
						} elseif ( 'vimeo' === $video['provider'] ) {
							$video_url = 'https://player.vimeo.com/video/'. $video['video_id'] .'?autoplay=1#t=0';
						}

						// Add Lightbox Attributes
						if ( isset( $video_url ) ) {
							$lightbox_source = $video_url;
						}
					}
				}

				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="tmpcoder-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Lightbox Icon
					echo '<i class="'. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="tmpcoder-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

				echo '</span>';

				// Media Overlay
				if ( 'yes' === $settings['element_lightbox_overlay'] ) {
					echo '<div class="tmpcoder-grid-lightbox-overlay"></div>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Element Separator
	public function render_product_element_separator( $settings, $class ) {
		echo '<div class="'. esc_attr($class .' '. $settings['element_separator_style']) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Render Status
	public function render_product_status( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';

			// Sale
			 if ( $product->is_on_sale() ) {
				echo '<span class="tmpcoder-woo-onsale">'. esc_html__( 'Sale', 'sastra-essential-addons-for-elementor' ) .'</span>';
			}

			// Stock Status
			if ( 'yes' === $settings['element_status_offstock'] && $product->is_in_stock() == false && 
				 ! ( $product->is_type( 'variable' ) && $product->get_stock_quantity() > 0 ) ) {
				echo '<span class="tmpcoder-woo-outofstock">'. esc_html__( 'Out of Stock', 'sastra-essential-addons-for-elementor' ) .'</span>';
			}

			// In Stock Badge (Optional, Woo-style improvement)
			if ( 'yes' === $settings['element_status_instock'] && $product->is_in_stock() ) {
				$instock_text = apply_filters( 'tmpcoder_instock_label_text', __( 'In Stock', 'sastra-essential-addons-for-elementor' ), $product, $settings );
				if ( !empty($instock_text) ){
    				echo '<span class="tmpcoder-woo-instock">' . esc_html( $instock_text ) . '</span>';
				}
			}

			// Featured
			if ( 'yes' === $settings['element_status_featured'] && $product->is_featured() ) {
				echo '<span class="tmpcoder-woo-featured">'. esc_html__( 'Featured', 'sastra-essential-addons-for-elementor' ) .'</span>';
			}

			/**
			 * Action hook for 3rd party badge insertion.
			 */
			do_action( 'tmpcoder_product_status_badges', $product, $settings );

			echo '</div>';
		echo '</div>';
	}

	// Render Add To Cart
	public function render_product_add_to_cart( $settings, $class ) {
		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		// Get Button Class
		$button_class = implode( ' ', array_filter( [
			'product_type_'. $product->get_type(),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
		] ) );

		$add_to_cart_animation = ! tmpcoder_is_availble() ? 'tmpcoder-button-none' : $this->get_settings()['add_to_cart_animation'];

		$popup_notification_animation = isset($this->get_settings_for_display()['popup_notification_animation']) ? $this->get_settings_for_display()['popup_notification_animation'] : '';
		$popup_notification_fade_out_in = isset($this->get_settings_for_display()['popup_notification_fade_out_in']) ? $this->get_settings_for_display()['popup_notification_fade_out_in'] : '';
		$popup_notification_animation_duration = isset($this->get_settings_for_display()['popup_notification_animation_duration']) ? $this->get_settings_for_display()['popup_notification_animation_duration'] : '';

		$attributes = [
			'rel="nofollow"',
			'class="'. esc_attr($button_class) .' tmpcoder-button-effect '. esc_attr($add_to_cart_animation) .' '. (!$product->is_in_stock() && 'simple' === $product->get_type() ? 'tmpcoder-atc-not-clickable' : '').'"',
			'aria-label="'. esc_attr($product->add_to_cart_description()) .'"',
			'data-product_id="'. esc_attr($product->get_id()) .'"',
			'data-product_sku="'. esc_attr($product->get_sku()) .'"',
			'data-atc-popup="'. $settings['element_show_added_tc_popup']  .'"',
			'data-atc-animation="'. $popup_notification_animation  .'"',
			'data-atc-fade-out-in="'. $popup_notification_fade_out_in  .'"',
			'data-atc-animation-time="'. $popup_notification_animation_duration  .'"'
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();

		// Icon: Before
		if ( 'before' === $settings['element_extra_icon_pos'] ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
			$extra_icon = ob_get_clean();

			$button_HTML .= '<span class="tmpcoder-grid-extra-icon-left">'. $extra_icon .'</span>';
		}

		// Button Text
		if ( 'simple' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_simple_txt'];

			if ( 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
				array_push( $attributes, 'href="'. esc_url( get_permalink( $page_id ) .'/?add-to-cart='. get_the_ID() ) .'"' );
			} else {
				array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
			}
		} elseif ( 'grouped' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_grouped_txt'];
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} elseif ( 'variable' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_variable_txt'];
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} else if ( 'pw-gift-card' === $product->get_type() ) {
			$button_HTML .= esc_html__('Select Amount', 'sastra-essential-addons-for-elementor');
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} else {
			array_push( $attributes, 'href="'. esc_url( $product->get_product_url() ) .'"' );
			$button_HTML .= get_post_meta( get_the_ID(), '_button_text', true ) ? get_post_meta( get_the_ID(), '_button_text', true ) : 'Buy Product';
		}

		// Icon: After
		if ( 'after' === $settings['element_extra_icon_pos'] ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
			$extra_icon = ob_get_clean();

			$button_HTML .= '<span class="tmpcoder-grid-extra-icon-right">'. $extra_icon .'</span>';
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			
			if ( $button_HTML != apply_filters( 'woocommerce_loop_add_to_cart_link', $button_HTML, $product ) ) {
				echo wp_kses_post(apply_filters( 'woocommerce_loop_add_to_cart_link', $button_HTML, $product ));
			} else {

				// Button HTML
				echo wp_kses('<a '. implode( ' ', $attributes ) .'><span>'. $button_HTML .'</span></a>', tmpcoder_wp_kses_allowed_html() );
			}
		
			echo '</div>';
		echo '</div>';
	}

	// Add two new functions for handling cookies
	public function get_wishlist_from_cookie() {
        $blog_id = get_current_blog_id();
        $cookie_key = 'tmpcoder_wishlist_'.$blog_id;
        if (isset($_COOKIE['tmpcoder_wishlist'])) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE['tmpcoder_wishlist'])), true);
        } else if ( isset($_COOKIE[$cookie_key]) ) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE[$cookie_key])),true);
        }
        return array();
	}

	// Render Wishlist Button
	public function render_product_wishlist_button( $settings, $class ) {
		global $product;
		
		if ( !tmpcoder_is_availble() ) {
			return;
		}

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

        $user_id = get_current_user_id();
		
		if ($user_id > 0) {
			if (is_multisite()) {
	            $wishlist_key = 'tmpcoder_wishlist_'.get_current_blog_id();
	        } else {
	            $wishlist_key = 'tmpcoder_wishlist';
	        }
			$wishlist = get_user_meta( get_current_user_id(), $wishlist_key, true );
		} else {
			$wishlist = $this->get_wishlist_from_cookie();
		}
		
		if ( ! $wishlist ) {
			$wishlist = array();
		}

		$popup_notification_animation = isset($this->get_settings_for_display()['popup_notification_animation']) ? $this->get_settings_for_display()['popup_notification_animation'] : '';
		$popup_notification_fade_out_in = isset($this->get_settings_for_display()['popup_notification_fade_out_in']) ? $this->get_settings_for_display()['popup_notification_fade_out_in'] : '';
		$popup_notification_animation_duration = isset($this->get_settings_for_display()['popup_notification_animation_duration']) ? $this->get_settings_for_display()['popup_notification_animation_duration'] : '';

		$wishlist_attributes = [
			'data-wishlist-url' => get_option('tmpcoder_wishlist_page') ? get_option('tmpcoder_wishlist_page') : '',
			'data-atw-popup='. $settings['element_show_added_to_wishlist_popup'],
			'data-atw-animation='. $popup_notification_animation,
			'data-atw-fade-out-in='. $popup_notification_fade_out_in,
			'data-atw-animation-time='. $popup_notification_animation_duration,
			'data-open-in-new-tab='. $settings['element_open_links_in_new_tab']
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();
		
		$button_add_title = '';
		$button_remove_title = '';
		$add_to_wishlist_content = '';
		$remove_from_wishlist_content = '';		

		if ( 'yes' === $settings['show_icon'] ) {
			// -------------------------------
			// Add to Wishlist Icon
			// -------------------------------
			if ( ! empty( $settings['add_wishlist_icon']['value'] ) && is_array( $settings['add_wishlist_icon']['value'] ) ) {
				// If a custom SVG icon is provided, render it safely
				$add_to_wishlist_content .= wp_kses( tmpcoder_render_svg_icon( $settings['add_wishlist_icon'] ), tmpcoder_wp_kses_allowed_html() );
			} else {
				// Otherwise, use the provided class or fallback to default 'far fa-heart'
				$add_wishlist_icon = ! empty( $settings['add_wishlist_icon']['value'] ) ? $settings['add_wishlist_icon']['value'] : 'far fa-heart';
				$add_to_wishlist_content .= '<i class="' . esc_attr( $add_wishlist_icon ) . '"></i>';
			}

			// -------------------------------
			// Remove from Wishlist Icon
			// -------------------------------
			if ( ! empty( $settings['remove_wishlist_icon']['value'] ) && is_array( $settings['remove_wishlist_icon']['value'] ) ) {
				// If a custom SVG icon is provided, render it safely
				$remove_from_wishlist_content .= wp_kses( tmpcoder_render_svg_icon( $settings['remove_wishlist_icon'] ), tmpcoder_wp_kses_allowed_html() );
			} else {
				// Otherwise, use the provided class or fallback to default 'fas fa-heart'
				$remove_wishlist_icon = ! empty( $settings['remove_wishlist_icon']['value'] ) ? $settings['remove_wishlist_icon']['value'] : 'fas fa-heart';
				$remove_from_wishlist_content .= '<i class="' . esc_attr( $remove_wishlist_icon ) . '"></i>';
			}
		}

		if ( 'yes' === $settings['show_text'] ) {
			$add_to_wishlist_content .= ' <span>'. esc_html($settings['add_to_wishlist_text']) .'</span>';
		} else {
			$add_to_wishlist_text = ! empty( $settings['add_to_wishlist_text'] ) 
				? esc_attr( $settings['add_to_wishlist_text'] ) 
				: __( 'Add to Wishlist', 'sastra-essential-addons-for-elementor' );

			$remove_from_wishlist_text = ! empty( $settings['remove_from_wishlist_text'] ) 
				? esc_attr( $settings['remove_from_wishlist_text'] ) 
				: __( 'Remove from Wishlist', 'sastra-essential-addons-for-elementor' );

			$button_add_title = 'title=' . $add_to_wishlist_text ;
			$button_remove_title = 'title=' . $remove_from_wishlist_text ;
		}

		if ( 'yes' === $settings['show_text'] ) {
			$remove_from_wishlist_content .= ' <span>'. esc_html($settings['remove_from_wishlist_text']) .'</span>';
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
	
			$remove_button_hidden = !in_array( $product->get_id(), $wishlist ) ? 'tmpcoder-button-hidden' : '';
			$add_button_hidden = in_array( $product->get_id(), $wishlist ) ? 'tmpcoder-button-hidden' : '';
		
			// '. implode( ' ', $wishlist_attributes ) .'
			echo '<button class="tmpcoder-wishlist-add '. esc_attr($add_button_hidden) .'" '. esc_attr($button_add_title) .' data-product-id=' . esc_attr($product->get_id()) . ''. ' ' . esc_attr(implode( ' ', $wishlist_attributes )) .' >'. wp_kses( $add_to_wishlist_content, tmpcoder_wp_kses_allowed_html() ) .'</button>';

			echo '<button class="tmpcoder-wishlist-remove '. esc_attr($remove_button_hidden) .'" '. esc_attr($button_remove_title) .' data-product-id="' . esc_attr($product->get_id()) . '">'. wp_kses( $remove_from_wishlist_content, tmpcoder_wp_kses_allowed_html() ) .'</button>';

			echo '</div>';
		echo '</div>';
	}
	
	// Add two new functions for handling cookies
	public function get_compare_from_cookie() {
        $blog_id = get_current_blog_id();
        $cookie_key = 'tmpcoder_compare_'.$blog_id;
        if (isset($_COOKIE['tmpcoder_compare'])) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE['tmpcoder_compare'])), true);
        } else if ( isset($_COOKIE[$cookie_key]) ) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE[$cookie_key])), true);
        }
        return array();
	}

	// Render Compare Button
	public function render_product_compare_button( $settings, $class ) {
		global $product;

		if ( !tmpcoder_is_availble() ) {
			return;
		}
		
		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

        $user_id = get_current_user_id();
		
		if ($user_id > 0) {
			if (is_multisite()) {
		        $compare_key = 'tmpcoder_compare_'.get_current_blog_id();
		    } else {
		        $compare_key = 'tmpcoder_compare';
		    }
			$compare = get_user_meta(  $user_id, $compare_key, true );
		
			if ( ! $compare ) {
				$compare = array();
			}
		} else {
			$compare = $this->get_compare_from_cookie();
		}

		$popup_notification_animation = isset($this->get_settings_for_display()['popup_notification_animation']) ? $this->get_settings_for_display()['popup_notification_animation'] : '';
		$popup_notification_fade_out_in = isset($this->get_settings_for_display()['popup_notification_fade_out_in']) ? $this->get_settings_for_display()['popup_notification_fade_out_in'] : '';
		// $popup_notification_animation_duration = isset($this->get_settings_for_display()['popup_notification_animation_duration']) ? $this->get_settings_for_display()['popup_notification_animation_duration'] : '';
		$popup_notification_animation_duration = isset($settings['popup_notification_animation_duration']) ? $settings['popup_notification_animation_duration'] : ' ';

		$compare_attributes = [
			// 'data-compare-url' => get_option('tmpcoder_compare_page') ? get_option('tmpcoder_compare_page') : '',
			'data-compare-url' => tmpcoder_get_settings('tmpcoder_compare_page') ? tmpcoder_get_settings('tmpcoder_compare_page') : ' ',
			'data-atcompare-popup='. $settings['element_show_added_to_compare_popup']  .'',
			'data-atcompare-animation='. $popup_notification_animation  .'',
			'data-atcompare-fade-out-in='. $popup_notification_fade_out_in  .'',
			'data-atcompare-animation-time='. $popup_notification_animation_duration  .'',
			'data-open-in-new-tab='. $settings['element_open_links_in_new_tab'] .''
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();
		
		$add_to_compare_content = '';
		$remove_from_compare_content = '';
		$button_add_title = '';
		$button_remove_title = '';
		

		if ( 'yes' === $settings['show_icon'] ) {
			$add_to_compare_content .= '<i class="fas fa-exchange-alt"></i>';
			$remove_from_compare_content .= '<i class="fas fa-exchange-alt"></i>';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$add_to_compare_content .= ' <span>'. esc_html($settings['add_to_compare_text']) .'</span>';
		} else {
			$button_add_title = 'title='. $settings['add_to_compare_text'].'';
			$button_remove_title = 'title='. esc_attr($settings['remove_from_compare_text']) .'';

			$add_to_compare_text = ! empty( $settings['add_to_compare_text'] ) 
				? esc_attr( $settings['add_to_compare_text'] ) 
				: __( 'Add to Compare', 'sastra-essential-addons-for-elementor' );

			$remove_from_compare_text = ! empty( $settings['remove_from_compare_text'] ) 
				? esc_attr( $settings['remove_from_compare_text'] ) 
				: __( 'Remove from Compare', 'sastra-essential-addons-for-elementor' );

			$button_add_title = 'title=' . $add_to_compare_text ;
			$button_remove_title = 'title=' . $remove_from_compare_text ;
		}

		if ( 'yes' === $settings['show_text'] ) {
			$remove_from_compare_content .= ' <span>'. esc_html($settings['remove_from_compare_text']) .'</span>';
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
	
			$remove_button_hidden = !in_array( $product->get_id(), $compare ) ? 'tmpcoder-button-hidden' : '';
			$add_button_hidden = in_array( $product->get_id(), $compare ) ? 'tmpcoder-button-hidden' : '';
		
			echo '<button class="tmpcoder-compare-add '. esc_attr($add_button_hidden) .'" '. esc_attr($button_add_title) .' data-product-id="' . esc_attr($product->get_id()) . '"'. ' ' . esc_attr(implode( ' ', $compare_attributes )) .' >'. wp_kses_post($add_to_compare_content) .'</button>';
			echo '<button class="tmpcoder-compare-remove '. esc_attr($remove_button_hidden) .'" '. esc_attr($button_remove_title) .' data-product-id="' . esc_attr($product->get_id()) . '">'. wp_kses_post($remove_from_compare_content) .'</button>';

			echo '</div>';
		echo '</div>';
	}

	// Render Rating
	public function render_product_rating( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		$rating_amount = floatval( $product->get_average_rating() );
		$round_rating = (int)$rating_amount;
		$rating_icon = '&#xE934;';

		if ( 'style-1' === $settings['element_rating_style'] ) {
			$style_class = ' tmpcoder-woo-rating-style-1';
			if ( 'outline' === $settings['element_rating_unmarked_style'] ) {
				$rating_icon = '&#xE933;';
			}
		} elseif ( 'style-2' === $settings['element_rating_style'] ) {
			$rating_icon = '&#9733;';
			$style_class = ' tmpcoder-woo-rating-style-2';

			if ( 'outline' === $settings['element_rating_unmarked_style'] ) {
				$rating_icon = '&#9734;';
			}
		}

		echo '<div class="'. esc_attr($class . $style_class) .'">';
			echo '<div class="inner-block">';

				echo '<div class="tmpcoder-woo-rating">';

				if ( 'yes' === $settings['element_rating_score'] ) {
					if ( $rating_amount == 1 || $rating_amount == 2 || $rating_amount == 3 || $rating_amount == 4 || $rating_amount == 5 )  {
						$rating_amount = $rating_amount .'.0';
					}

					echo '<i class="tmpcoder-rating-icon-10">'. esc_html($rating_icon) .'</i>';
					echo '<span>'. esc_html($rating_amount) .'</span>';
				} else {
					for ( $i = 1; $i <= 5; $i++ ) {
						if ( $i <= $rating_amount ) {
							echo '<i class="tmpcoder-rating-icon-full">'. esc_html($rating_icon) .'</i>';
						} elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) {
							echo '<i class="tmpcoder-rating-icon-'. esc_attr((( $rating_amount - $round_rating ) * 10)) .'">'. esc_html($rating_icon) .'</i>';
						} else {
							echo '<i class="tmpcoder-rating-icon-empty">'. esc_html($rating_icon) .'</i>';
						}
			     	}
				}

				echo '</div>';

			echo '</div>';
		echo '</div>';
	}

	// Render Price
	public function render_product_price( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';

			echo '<span>'. wp_kses_post($product->get_price_html()) .'</span>';
			$sale_price_dates_to    = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
			echo esc_html($sale_price_dates_to);

			echo '</div>';
		echo '</div>';
	}


	public function render_product_sale_dates( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		// $sale_price_dates_from  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
		// $sale_price_dates_to  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
		$sale_price_dates_from  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_from', true ) ) ? date_i18n(get_option('date_format'), $date ) : '';
		$sale_price_dates_to  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n(get_option('date_format'), $date ) : '';
		
		if ( ( 'yes' == $settings['show_sale_starts_date'] && !empty($sale_price_dates_from) ) || ( 'yes' == $settings['show_sale_ends_date'] && !empty($sale_price_dates_to) ) ) {
			echo '<div class="'. esc_attr($class) .'">';
				echo '<div class="inner-block">';

					echo '<span class="tmpcoder-sale-dates">';
		
						// Text: Before
						if ( (!empty($settings['element_sale_starts_text']) && '' !== $settings['element_sale_starts_text']) && !empty($sale_price_dates_from) ) {
							echo '<span class="tmpcoder-grid-extra-text-left">'. esc_html( $settings['element_sale_starts_text'] ) .'</span> ';
						}
						
						if ( !empty($sale_price_dates_from) ) {
							echo  '<span>'. esc_html($sale_price_dates_from) .'</span>';
						}


						if ( !empty($settings['element_sale_dates_sep']) && 'inline' == $settings['element_sale_dates_layout'] ) {
							if ( !empty($sale_price_dates_from) && !empty($sale_price_dates_to) ) {
								echo esc_html($settings['element_sale_dates_sep']);
							}
						}

						if ( 'block' == $settings['element_sale_dates_layout'] && !empty($sale_price_dates_form) && !empty($sale_price_dates_to) ) {
							echo '<br>';
						}
		
						// Text: Before
						if ( (!empty($settings['element_sale_ends_text']) && '' !== $settings['element_sale_ends_text']) && !empty($sale_price_dates_to) ) {
							echo '<span class="tmpcoder-grid-extra-text-left">'. esc_html( $settings['element_sale_ends_text'] ) .'</span> ';
						}

						if ( !empty($sale_price_dates_to) ) {
							echo  '<span>'. esc_html($sale_price_dates_to) .'</span>';
						}

					echo '</span>';
	
				echo '</div>';
			echo '</div>';
		}
	}

	// Get Elements
	public function get_elements( $type, $settings, $class, $post_id ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-sd' == $type || 'pro-ws' == $type || 'pro-cm' == $type || 'pro-cfa' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
			case 'title':
				$this->render_product_title( $settings, $class );
				break;

			case 'excerpt':
				$this->render_product_excerpt( $settings, $class );
				break;

			case 'product_cat':
				$this->render_product_categories( $settings, $class, $post_id );
				break;

			case 'product_tag':
				$this->render_product_tags( $settings, $class, $post_id );
				break;

			case 'likes':
				$this->render_product_likes( $settings, $class, $post_id );
				break;

			case 'sharing':
				$this->render_product_sharing_icons( $settings, $class );
				break;

			case 'lightbox':
				$this->render_product_lightbox( $settings, $class, $post_id );
				break;

			case 'separator':
				$this->render_product_element_separator( $settings, $class );
				break;

			case 'status':
				$this->render_product_status( $settings, $class );
				break;

			case 'price':
				$this->render_product_price( $settings, $class );
				break;

			case 'sale_dates':
				$this->render_product_sale_dates( $settings, $class );
				break;

			case 'rating':
				$this->render_product_rating( $settings, $class );
				break;

			case 'add-to-cart':
				$this->render_product_add_to_cart( $settings, $class );
				break;
			case 'wishlist-button':
				if ( tmpcoder_is_availble() ) {
					$this->render_product_wishlist_button( $settings, $class );
				}
				break;
			case 'compare-button':
				if ( tmpcoder_is_availble() ) {
					$this->render_product_compare_button( $settings, $class );
				}
				break;
			case 'custom-field':
				$this->render_product_custom_fields( $settings, $class, $post_id );
				break;
		}

	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $post_id ) {
		$locations = [];

		foreach ( $settings['grid_elements'] as $data ) {
			$place = $data['element_location'] ?? '';
			$align_vr = $data['element_align_vr'] ?? '';

			if ( ! tmpcoder_is_availble() ) {
				$align_vr = 'middle';
			}

			if ( ! isset($locations[$place]) ) {
				$locations[$place] = [];
			}
			
			if ( 'over' === $place ) {
				if ( ! isset($locations[$place][$align_vr]) ) {
					$locations[$place][$align_vr] = [];
				}

				array_push( $locations[$place][$align_vr], $data );
			} else {
				array_push( $locations[$place], $data );
			}
		}

		if ( ! empty( $locations[$location] ) ) {

			if ( 'over' === $location ) {
				foreach ( $locations[$location] as $align => $elements ) {
					if ( 'middle' === $align ) {
						echo '<div class="tmpcoder-cv-container"><div class="tmpcoder-cv-outer"><div class="tmpcoder-cv-inner">';
					}

					echo '<div class="tmpcoder-grid-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $elements as $data ) {
							
							// Get Class
							$class  = 'tmpcoder-grid-item-'. ($data['element_select'] ?? '');
							$class .= ' elementor-repeater-item-'. ($data['_id'] ?? '');
							$class .= ' tmpcoder-grid-item-display-'. ($data['element_display'] ?? '');
							$class .= ' tmpcoder-grid-item-align-'. ($data['element_align_hr'] ?? '');
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'] ?? '', $data, $class, $post_id );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				echo '<div class="tmpcoder-grid-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {

						// Get Class
						$class  = 'tmpcoder-grid-item-'. ($data['element_select'] ?? '');
						$class .= ' elementor-repeater-item-'. ($data['_id'] ?? '');
						$class .= ' tmpcoder-grid-item-display-'. ($data['element_display'] ?? '');
						$class .= ' tmpcoder-grid-item-align-'. ($data['element_align_hr'] ?? '');

						// Element
						$this->get_elements( $data['element_select'] ?? '', $data, $class, $post_id );
					}
				echo '</div>';
			}

		}
	}

	// Render Sort & Results
	public function render_grid_sorting( $settings, $posts ) {}

	// Render Grid Filters
	public function render_grid_filters( $settings ) {
		$taxonomy = $settings['filters_select'] ?? '';

		// Return if Disabled
		if ( '' === $taxonomy || ! isset( $settings[ 'query_taxonomy_'. $taxonomy ] ) ) {
			return;
		}

		// Get Custom Filters
		$custom_filters = $settings[ 'query_taxonomy_'. $taxonomy ];

		if ( ! tmpcoder_is_availble() ) {
			$settings['filters_default_filter'] = '';
			$settings['filters_icon_align'] = '';
			$settings['filters_count'] = '';
			$settings['filters_pointer'] = 'none';
			$settings['filters_pointer_animation'] = 'none';
		}

		// Icon
		$left_icon = 'left' === $settings['filters_icon_align'] ? '<i class="'. esc_attr($settings['filters_icon']['value']) .' tmpcoder-grid-filters-icon-left"></i>' : '';
		$right_icon = 'right' === $settings['filters_icon_align'] ? '<i class="'. esc_attr($settings['filters_icon']['value']) .' tmpcoder-grid-filters-icon-right"></i>' : '';
		
		// Separator
		$left_separator = 'left' === $settings['filters_separator_align'] ? '<em class="tmpcoder-grid-filters-sep">'. esc_html($settings['filters_separator']) .'</em>' : '';
		$right_separator = 'right' === $settings['filters_separator_align'] ? '<em class="tmpcoder-grid-filters-sep">'. esc_html($settings['filters_separator']) .'</em>' : '';

		// Count
		$post_count = 'yes' === $settings['filters_count'] ? '<sup data-brackets="'. esc_attr($settings['filters_count_brackets']) .'"></sup>' : '';

		// Pointer Class
		$pointer_class  = ' tmpcoder-pointer-'. $settings['filters_pointer'];
		$pointer_class .= ' tmpcoder-pointer-line-fx tmpcoder-pointer-fx-'. $settings['filters_pointer_animation'];
		$pointer_item_class = (isset($settings['filters_pointer']) && 'none' !== $settings['filters_pointer']) ? 'class=tmpcoder-pointer-item' : '';
		$pointer_item_class_name = (isset($settings['filters_pointer']) && 'none' !== $settings['filters_pointer']) ? 'tmpcoder-pointer-item' : '';

		// Filters List
		echo '<ul class="tmpcoder-grid-filters elementor-clearfix tmpcoder-grid-filters-sep-'. esc_attr($settings['filters_separator_align']) .'">';

		// All Filter
		if ( 'yes' === $settings['filters_all'] && 'yes' !== $settings['filters_linkable'] ) {
			echo '<li class="'. esc_attr($pointer_class) .'">';
			echo wp_kses('<span data-filter="*" class="tmpcoder-active-filter '. $pointer_item_class_name .'">'. $left_icon . esc_html($settings['filters_all_text']) . $right_icon . $post_count .'</span>'. $right_separator, array(
                'span' => array(
                    'class' => array(),
                    'data-filter' => array(),
                ),
                'i' => array(
                    'class'=> array(),
                ),
                'sup' => array(
                    'data-brackets'=> array(),
                )
            ));
			echo '</li>';
		}

		// Custom Filters
		if ( $settings['query_selection'] === 'dynamic' && ! empty( $custom_filters ) ) {
			$parent_filters = [];
			
			foreach ( $custom_filters as $key => $term_id ) {
				// $filter = get_term_by( 'id', $term_id, $taxonomy );
				$filter = get_term_by( 'slug', $term_id, $taxonomy );
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $filter->slug : $taxonomy .'-'. $filter->slug;

				// Parent Filters
				if ( 0 === $filter->parent ) {
					$children = get_term_children( $filter->term_id, $taxonomy );
					$data_role = ! empty($children) ? ' data-role="parent"' : '';
					// $data_role = ! empty($children) ? ' data-role=parent' : '';

					echo '<li'. esc_attr($data_role) .' class="'. esc_attr($pointer_class) .'">';
						if ( 'yes' !== $settings['filters_linkable'] ) {
							echo wp_kses(''. $left_separator .'<span '. $pointer_item_class .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</span>'. $right_separator, array(
                                'span' => array(
                                    'class' => array(),
                                    'data-filter' => array(),
                                ),
                                'i' => array(
                                    'class'=> array(),
                                ),
                                'sup' => array(
				                    'data-brackets'=> array(),
				                )
                            ));
						} else {
							echo wp_kses(''. $left_separator .'<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $filter->term_id, $taxonomy )) .'" data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . wp_kses_post($post_count) .'</a>'. $right_separator, array(
                                'a' => array(
                                    'href' => array(),
                                    'class' => array(),
                                    'data-filter' => array(),
                                ),
                                'i' => array(
                                    'class'=> array(),
                                ),
                                'sup' => array(
				                    'data-brackets'=> array(),
				                )
                            ));
						}
					echo '</li>';

				// Get Sub Filters
				} else {
					array_push( $parent_filters, $filter->parent );
				}
			}

		// All Filters
		} else {
			$all_filters = get_terms( $taxonomy );
			$parent_filters = [];

			foreach ( $all_filters as $key => $filter ) {
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $filter->slug : $taxonomy .'-'. $filter->slug;

				// Parent Filters
				if ( 0 === $filter->parent ) {
					$children = get_term_children( $filter->term_id, $taxonomy );
					$data_role = ! empty($children) ? ' data-role="parent"' : '';
					$hidden_class = $this->get_hidden_filter_class($filter->slug, $settings);

					// $sub = $filter->count.'</sub>';
					// $post_count = str_replace('</sub>', $sub, $post_count);

					echo '<li'. esc_attr($data_role) .' class="'. esc_attr($pointer_class) . esc_attr($hidden_class) .'">';
						if ( 'yes' !== $settings['filters_linkable'] ) {
							echo wp_kses_post(''. $left_separator .'<span '. esc_attr($pointer_item_class) .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. wp_kses_post($left_icon) . esc_html($filter->name) . wp_kses_post($right_icon) . wp_kses_post($post_count) .'</span>'. $right_separator);
						} else {

							echo wp_kses_post(''. $left_separator .'<a '. esc_attr($pointer_item_class) .' href="'. esc_url(get_term_link( $filter->term_id, $taxonomy )) .'" data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. wp_kses_post($left_icon) . esc_html($filter->name) . wp_kses_post($right_icon) . wp_kses_post($post_count) .'</a>'. $right_separator);
						}
					echo '</li>';

				// Get Sub Filters
				} else {
					array_push( $parent_filters, $filter->parent );
				}
			}
		}

		// Sub Filters
		if ( 'yes' !== $settings['filters_linkable'] ) {
			foreach ( array_unique( $parent_filters ) as $key => $parent_filter ) {
				$parent = get_term_by( 'id', $parent_filter, $taxonomy );
				// $parent = get_term_by( 'slug', $parent_filter, $taxonomy );
				$children = get_term_children( $parent_filter, $taxonomy );
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $parent->slug : $taxonomy .'-'. $parent->slug;

				echo '<ul data-parent=".'. esc_attr(urldecode($data_attr)) .'" class="tmpcoder-sub-filters">';

				echo '<li data-role="back" class="'. esc_attr($pointer_class) .'">';
					echo '<span class="tmpcoder-back-filter" data-filter=".'. esc_attr(urldecode( $data_attr )) .'">';
						echo '<i class="fas fa-long-arrow-alt-left"></i>&nbsp;&nbsp;'. esc_html__( 'Back', 'sastra-essential-addons-for-elementor' );
					echo '</span>';
				echo '</li>';

				foreach ( $children as $child ) {
					$sub_filter = get_term_by( 'id', $child, $taxonomy );
					// $sub_filter = get_term_by( 'slug', $child, $taxonomy );
					$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $sub_filter->slug : $taxonomy .'-'. $sub_filter->slug;

					echo '<li data-role="sub" class="'. esc_attr($pointer_class) .'">';
						echo wp_kses(''. $left_separator .'<span '. $pointer_item_class .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($sub_filter->name) . $right_icon . esc_html($post_count) .'</span>'. $right_separator, array(
                            'span' => array(
                                'class' => array(),
                                'data-filter' => array(),
                            ),
                            'i' => array(
                                'class'=> array(),
                            )
                        ) );
					echo '</li>';
				}

				echo '</ul>';
			}
		}

		echo '</ul>';
	}

	public function get_hidden_filter_class($slug, $settings) {
		$posts = new \WP_Query( $this->get_main_query_args() );
		$visible_categories = [];

		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$categories = get_the_category();

				foreach ($categories as $key => $category) {
					array_push($visible_categories, $category->slug);
				}
			}

			$visible_categories = array_unique($visible_categories);

			wp_reset_postdata();
		}

		return ( ! in_array($slug, $visible_categories) && 'yes' == $settings['filters_hide_empty'] ) ? ' tmpcoder-hidden-element' : '';
	}

	// Render Grid Pagination
	public function render_grid_pagination( $settings ) {
		// Return if Disabled
		if ( 'yes' !== $settings['layout_pagination'] || 1 === $this->get_max_num_pages( $settings ) || 'slider' === $settings['layout_select'] ) {
			return;
		}
		
		if ( (isset($this->my_upsells) && (count($this->my_upsells) <= $settings['query_posts_per_page'])) || (isset($this->crossell_ids) && (count($this->crossell_ids) <= $settings['query_posts_per_page'])) ) {
			return;
		}

		global $paged;
		$pages = $this->get_max_num_pages( $settings );
		$paged = empty( $paged ) ? 1 : $paged;

		if ( ! tmpcoder_is_availble() ) {
			$settings['pagination_type'] = 'pro-is' == $settings['pagination_type'] ? 'default' : $settings['pagination_type'];
		}

		echo '<div class="tmpcoder-grid-pagination elementor-clearfix tmpcoder-grid-pagination-'. esc_attr($settings['pagination_type']) .'">';

		// Default
		if ( 'default' === $settings['pagination_type'] ) {
			if ( $paged < $pages ) {
				echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="tmpcoder-prev-post-link">';
					echo wp_kses(tmpcoder_get_icon( $settings['pagination_on_icon'], 'left' ), tmpcoder_wp_kses_allowed_html());
					echo esc_html($settings['pagination_older_text']);
				echo '</a>';
			} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
				echo '<span class="tmpcoder-prev-post-link tmpcoder-disabled-arrow">';
					echo wp_kses(tmpcoder_get_icon( $settings['pagination_on_icon'], 'left' ), tmpcoder_wp_kses_allowed_html());
					echo esc_html($settings['pagination_older_text']);
				echo '</span>';
			}

			if ( $paged > 1 ) {
				echo '<a href="'. esc_url(get_pagenum_link( $paged - 1, true )) .'" class="tmpcoder-next-post-link">';
					echo esc_html($settings['pagination_newer_text']);
					echo wp_kses(tmpcoder_get_icon( $settings['pagination_on_icon'], 'right' ), tmpcoder_wp_kses_allowed_html());
				echo '</a>';
			} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
				echo '<span class="tmpcoder-next-post-link tmpcoder-disabled-arrow">';
					echo esc_html($settings['pagination_newer_text']);
					echo wp_kses(tmpcoder_get_icon( $settings['pagination_on_icon'], 'right' ), tmpcoder_wp_kses_allowed_html());
				echo '</span>';
			}

		// Numbered
		} elseif ( 'numbered' === $settings['pagination_type'] ) {

			$range = intval($settings['pagination_range']);

			$showitems = ( $range * 2 ) + 1;

			if ( 1 !== $pages ) {

			    if ( 'yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last'] ) {
			    	echo '<div class="tmpcoder-grid-pagi-left-arrows">';

				    if ( 'yes' === $settings['pagination_first_last'] ) {
				    	if ( $paged >= 2 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( 1, true )) .'" class="tmpcoder-first-page">';
					    	// echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="tmpcoder-first-page">';
					    	// echo '<a href="'. esc_url(substr(get_pagenum_link( $paged + 1, true ), 0, strpos(get_pagenum_link( $paged + 1, true ), '?orderby'))) .'" class="tmpcoder-first-page">';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_fl_icon'], 'left' ), tmpcoder_wp_kses_allowed_html());
					    		echo '<span>'. esc_html($settings['pagination_first_text']) .'</span>';
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="tmpcoder-first-page tmpcoder-disabled-arrow">';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_fl_icon'], 'left' ), tmpcoder_wp_kses_allowed_html());
					    		echo '<span>'. esc_html($settings['pagination_first_text']) .'</span>';
					    	echo '</span>';
				    	}
				    }

				    if ( 'yes' === $settings['pagination_prev_next'] ) {
				    	if ( $paged > 1 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $paged - 1, true )) .'" class="tmpcoder-prev-page">';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_pn_icon'], 'left' ), tmpcoder_wp_kses_allowed_html());
					    		echo '<span>'. esc_html($settings['pagination_prev_text']) .'</span>';
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="tmpcoder-prev-page tmpcoder-disabled-arrow">';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_pn_icon'], 'left' ), tmpcoder_wp_kses_allowed_html());
					    		echo '<span>'. esc_html($settings['pagination_prev_text']) .'</span>';
					    	echo '</span>';
				    	}
				    }

				    echo '</div>';
			    }

			    for ( $i = 1; $i <= $pages; $i++ ) {
			        if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
						if ( $paged === $i ) {
							echo '<span class="tmpcoder-grid-current-page">'. esc_html($i) .'</span>';
						} else {
							// var_dump(get_pagenum_link( $i, true ), substr(get_pagenum_link( $i, true ), 0, strpos(get_pagenum_link( $i, true ), '?orderby')));
							echo '<a href="'. esc_url(get_pagenum_link( $i, true )) .'">'. esc_html($i) .'</a>';
						}
			        }
			    }

			    if ( 'yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last'] ) {
			    	echo '<div class="tmpcoder-grid-pagi-right-arrows">';

				    if ( 'yes' === $settings['pagination_prev_next'] ) {
				    	if ( $paged < $pages ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="tmpcoder-next-page">';
					    		echo '<span>'. esc_html($settings['pagination_next_text']) .'</span>';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_pn_icon'], 'right' ), tmpcoder_wp_kses_allowed_html());
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="tmpcoder-next-page tmpcoder-disabled-arrow">';
					    		echo '<span>'. esc_html($settings['pagination_next_text']) .'</span>';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_pn_icon'], 'right' ), tmpcoder_wp_kses_allowed_html());
					    	echo '</span>';
				    	}
				    }

				    if ( 'yes' === $settings['pagination_first_last'] ) {
				    	if ( $paged <= $pages - 1 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $pages, true )) .'" class="tmpcoder-last-page">';
					    		echo '<span>'. esc_html($settings['pagination_last_text']) .'</span>';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_fl_icon'], 'right' ), tmpcoder_wp_kses_allowed_html());
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="tmpcoder-last-page tmpcoder-disabled-arrow">';
					    		echo '<span>'. esc_html($settings['pagination_last_text']) .'</span>';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_fl_icon'], 'right' ), tmpcoder_wp_kses_allowed_html());
					    	echo '</span>';
				    	} 
				    }

				    echo '</div>';
			    }
			}

		// Load More / Infinite Scroll
		} else {
			echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="tmpcoder-load-more-btn" data-e-disable-page-transition>';
			// echo '<a href="'. esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ) .'" class="tmpcoder-load-more-btn" data-e-disable-page-transition>';
			// echo '<a href="'. esc_url(get_next_posts_page_link()) .'" class="tmpcoder-load-more-btn" data-e-disable-page-transition>';
				echo esc_html($settings['pagination_load_more_text']);
			echo '</a>';

			echo '<div class="tmpcoder-pagination-loading">';
				switch ( $settings['pagination_animation'] ) {
					case 'loader-1':
						echo '<div class="tmpcoder-double-bounce">';
							echo '<div class="tmpcoder-child tmpcoder-double-bounce1"></div>';
							echo '<div class="tmpcoder-child tmpcoder-double-bounce2"></div>';
						echo '</div>';
						break;
					case 'loader-2':
						echo '<div class="tmpcoder-wave">';
							echo '<div class="tmpcoder-rect tmpcoder-rect1"></div>';
							echo '<div class="tmpcoder-rect tmpcoder-rect2"></div>';
							echo '<div class="tmpcoder-rect tmpcoder-rect3"></div>';
							echo '<div class="tmpcoder-rect tmpcoder-rect4"></div>';
							echo '<div class="tmpcoder-rect tmpcoder-rect5"></div>';
						echo '</div>';
						break;
					case 'loader-3':
						echo '<div class="tmpcoder-spinner tmpcoder-spinner-pulse"></div>';
						break;
					case 'loader-4':
						echo '<div class="tmpcoder-chasing-dots">';
							echo '<div class="tmpcoder-child tmpcoder-dot1"></div>';
							echo '<div class="tmpcoder-child tmpcoder-dot2"></div>';
						echo '</div>';
						break;
					case 'loader-5':
						echo '<div class="tmpcoder-three-bounce">';
							echo '<div class="tmpcoder-child tmpcoder-bounce1"></div>';
							echo '<div class="tmpcoder-child tmpcoder-bounce2"></div>';
							echo '<div class="tmpcoder-child tmpcoder-bounce3"></div>';
						echo '</div>';
						break;
					case 'loader-6':
						echo '<div class="tmpcoder-fading-circle">';
							echo '<div class="tmpcoder-circle tmpcoder-circle1"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle2"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle3"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle4"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle5"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle6"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle7"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle8"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle9"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle10"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle11"></div>';
							echo '<div class="tmpcoder-circle tmpcoder-circle12"></div>';
						echo '</div>';
						break;
					
					default:
						break;
				}
			echo '</div>';

			echo '<p class="tmpcoder-pagination-finish">'. esc_html($settings['pagination_finish_text']) .'</p>';
		}

		echo '</div>';
	}

	// Grid Settings
	public function add_grid_settings( $settings ) {
		if ( ! tmpcoder_is_availble() ) {
			$settings['layout_select'] = 'pro-ms' == $settings['layout_select'] ? 'fitRows' : $settings['layout_select'];
			$settings['filters_deeplinking'] = '';
			$settings['filters_count'] = '';
			$settings['filters_default_filter'] = '';

			if ( 'pro-fd' == $settings['filters_animation'] || 'pro-fs' == $settings['filters_animation'] ) {
				$settings['filters_animation'] = 'zoom';
			}
		}

		if ( 'fitRows' == $settings['layout_select'] ) {
			$stick_last_element_to_bottom = $settings['stick_last_element_to_bottom'];
		} else {
			$stick_last_element_to_bottom = 'no';
		}

		$gutter_hr_widescreen = isset($settings['layout_gutter_hr_widescreen']['size']) ? $settings['layout_gutter_hr_widescreen']['size'] : $settings['layout_gutter_hr']['size'];
		$gutter_hr_desktop = $settings['layout_gutter_hr']['size'];
		$gutter_hr_laptop = isset($settings['layout_gutter_hr_laptop']['size']) ? $settings['layout_gutter_hr_laptop']['size'] : $gutter_hr_desktop;
		$gutter_hr_tablet_extra = isset($settings['layout_gutter_hr_tablet_extra']['size']) ? $settings['layout_gutter_hr_tablet_extra']['size'] : $gutter_hr_laptop;
		$gutter_hr_tablet = isset($settings['layout_gutter_hr_tablet']['size']) ? $settings['layout_gutter_hr_tablet']['size'] : $gutter_hr_tablet_extra;
		$gutter_hr_mobile_extra = isset($settings['layout_gutter_hr_mobile_extra']['size']) ? $settings['layout_gutter_hr_mobile_extra']['size'] : $gutter_hr_tablet;
		$gutter_hr_mobile = isset($settings['layout_gutter_hr_mobile']['size']) ? $settings['layout_gutter_hr_mobile']['size'] : $gutter_hr_mobile_extra;

		$gutter_vr_widescreen = isset($settings['layout_gutter_vr_widescreen']['size']) ? $settings['layout_gutter_vr_widescreen']['size'] : $settings['layout_gutter_vr']['size'];
		$gutter_vr_desktop = $settings['layout_gutter_vr']['size'];
		$gutter_vr_laptop = isset($settings['layout_gutter_vr_laptop']['size']) ? $settings['layout_gutter_vr_laptop']['size'] : $gutter_vr_desktop;
		$gutter_vr_tablet_extra = isset($settings['layout_gutter_vr_tablet_extra']['size']) ? $settings['layout_gutter_vr_tablet_extra']['size'] : $gutter_vr_laptop;
		$gutter_vr_tablet = isset($settings['layout_gutter_vr_tablet']['size']) ? $settings['layout_gutter_vr_tablet']['size'] : $gutter_vr_tablet_extra;
		$gutter_vr_mobile_extra = isset($settings['layout_gutter_vr_mobile_extra']['size']) ? $settings['layout_gutter_vr_mobile_extra']['size'] : $gutter_vr_tablet;
		$gutter_vr_mobile = isset($settings['layout_gutter_vr_mobile']['size']) ? $settings['layout_gutter_vr_mobile']['size'] : $gutter_vr_mobile_extra;

		$layout_settings = [
			'layout' => $settings['layout_select'],
			'stick_last_element_to_bottom' => $stick_last_element_to_bottom,
			'columns_desktop' => $settings['layout_columns'],
			'gutter_hr' => $gutter_hr_desktop,
			'gutter_hr_mobile' => $gutter_hr_mobile,
			'gutter_hr_mobile_extra' => $gutter_hr_mobile_extra,
			'gutter_hr_tablet' => $gutter_hr_tablet,
			'gutter_hr_tablet_extra' => $gutter_hr_tablet_extra,
			'gutter_hr_laptop' => $gutter_hr_laptop,
			'gutter_hr_widescreen' => $gutter_hr_widescreen,
			'gutter_vr' => $gutter_vr_desktop,
			'gutter_vr_mobile' => $gutter_vr_mobile,
			'gutter_vr_mobile_extra' => $gutter_vr_mobile_extra,
			'gutter_vr_tablet' => $gutter_vr_tablet,
			'gutter_vr_tablet_extra' => $gutter_vr_tablet_extra,
			'gutter_vr_laptop' => $gutter_vr_laptop,
			'gutter_vr_widescreen' => $gutter_vr_widescreen,
			'animation' => $settings['layout_animation'],
			'animation_duration' => $settings['layout_animation_duration'],
			'animation_delay' => $settings['layout_animation_delay'],
			'deeplinking' => $settings['filters_deeplinking'],
			'filters_linkable' => $settings['filters_linkable'],
			'filters_default_filter' => $settings['filters_default_filter'],
			'filters_count' => $settings['filters_count'],
			'filters_hide_empty' => $settings['filters_hide_empty'],
			'filters_animation' => $settings['filters_animation'],
			'filters_animation_duration' => $settings['filters_animation_duration'],
			'filters_animation_delay' => $settings['filters_animation_delay'],
			'pagination_type' => $settings['pagination_type'],
			'pagination_max_pages' => $this->get_max_num_pages( $settings ),
		];

		if ( 'list' === $settings['layout_select'] ) {
			$layout_settings['media_align'] = $settings['layout_list_align'];
			$layout_settings['media_width'] = $settings['layout_list_media_width']['size'];
			$layout_settings['media_distance'] = $settings['layout_list_media_distance']['size'];
		}

		if ( ! tmpcoder_is_availble() ) {
			$settings['lightbox_popup_thumbnails'] = '';
			$settings['lightbox_popup_thumbnails_default'] = '';
			$settings['lightbox_popup_sharing'] = '';
		}

		$layout_settings['lightbox'] = [
			'selector' => '.tmpcoder-grid-image-wrap',
			'iframeMaxWidth' => '60%',
			'hash' => false,
			'autoplay' => $settings['lightbox_popup_autoplay'],
			'pause' => $settings['lightbox_popup_pause'] * 1000,
			'progressBar' => $settings['lightbox_popup_progressbar'],
			'counter' => $settings['lightbox_popup_counter'],
			'controls' => $settings['lightbox_popup_arrows'],
			'getCaptionFromTitleOrAlt' => $settings['lightbox_popup_captions'],
			'thumbnail' => $settings['lightbox_popup_thumbnails'],
			'showThumbByDefault' => $settings['lightbox_popup_thumbnails_default'],
			'share' => $settings['lightbox_popup_sharing'],
			'zoom' => $settings['lightbox_popup_zoom'],
			'fullScreen' => $settings['lightbox_popup_fullscreen'],
			'download' => $settings['lightbox_popup_download'],
		];

		$this->add_render_attribute( 'grid-settings', [
			'data-settings' => wp_json_encode( $layout_settings ),
		] );
	}

	public function add_slider_settings( $settings ) {
		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

		if ( ! tmpcoder_is_availble() ) {
			$settings['layout_slider_autoplay'] = '';
			$settings['layout_slider_autoplay_duration'] = 0;
			$settings['layout_slider_pause_on_hover'] = '';
		}

		$slider_options = [
			'rtl' => $slider_is_rtl,
			'infinite' => ( $settings['layout_slider_loop'] === 'yes' ),
			'speed' => absint( $settings['layout_slider_effect_duration'] * 1000 ),
			'arrows' => true,
			'dots' => true,
			'autoplay' => ( $settings['layout_slider_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( $settings['layout_slider_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['layout_slider_pause_on_hover'],
			'prevArrow' => '#tmpcoder-grid-slider-prev-'. $this->get_id(),
			'nextArrow' => '#tmpcoder-grid-slider-next-'. $this->get_id(),
			'sliderSlidesToScroll' => +$settings['layout_slides_to_scroll'],
		];

		if ( ! tmpcoder_is_availble() ) {
			$settings['lightbox_popup_thumbnails'] = '';
			$settings['lightbox_popup_thumbnails_default'] = '';
			$settings['lightbox_popup_sharing'] = '';
		}

		// Lightbox Settings
		$slider_options['lightbox'] = [
			'selector' => 'article:not(.slick-cloned) .tmpcoder-grid-image-wrap',
			'iframeMaxWidth' => '60%',
			'hash' => false,
			'autoplay' => $settings['lightbox_popup_autoplay'],
			'pause' => $settings['lightbox_popup_pause'] * 1000,
			'progressBar' => $settings['lightbox_popup_progressbar'],
			'counter' => $settings['lightbox_popup_counter'],
			'controls' => $settings['lightbox_popup_arrows'],
			'getCaptionFromTitleOrAlt' => $settings['lightbox_popup_captions'],
			'thumbnail' => $settings['lightbox_popup_thumbnails'],
			'showThumbByDefault' => $settings['lightbox_popup_thumbnails_default'],
			'share' => $settings['lightbox_popup_sharing'],
			'zoom' => $settings['lightbox_popup_zoom'],
			'fullScreen' => $settings['lightbox_popup_fullscreen'],
			'download' => $settings['lightbox_popup_download'],
		];

		if ( $settings['layout_slider_amount'] === 1 && $settings['layout_slider_effect'] === 'fade' ) {
			$slider_options['fade'] = true;
		}

		$this->add_render_attribute( 'slider-settings', [
			'dir' => esc_attr( $slider_direction ),
			'data-slick' => wp_json_encode( $slider_options ),
		] );
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$settings_new = $this->get_settings_for_display();
		$settings = array_merge( $settings, $settings_new );

		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<h2>'. esc_html__( 'WooCommerce is NOT active!', 'sastra-essential-addons-for-elementor' ) .'</h2>';
			return;
		}
		// Get Posts
		$posts = new \WP_Query( $this->get_main_query_args() );

		// Loop: Start
		if ( $posts->have_posts() ) :

		$post_index = 0;

		if ( ('upsell' === $settings['query_selection'] && (!empty($settings['grid_linked_products_heading']) && '' !== $settings['grid_linked_products_heading'])) || ('cross-sell' === $settings['query_selection'])  && (!empty($settings['grid_linked_products_heading']) && '' !== $settings['grid_linked_products_heading']) || ('related-product' === $settings['query_selection'])  && (!empty($settings['grid_linked_products_heading']) && '' !== $settings['grid_linked_products_heading'])) {
			echo '<div class="tmpcoder-grid-linked-products-heading">';
				echo '<'. tag_escape( tmpcoder_validate_html_tag($settings['grid_linked_products_heading_tag']) ) .'>'. esc_html( $settings['grid_linked_products_heading'] ) .'</'. tag_escape( tmpcoder_validate_html_tag($settings['grid_linked_products_heading_tag']) ).'>';
			echo '</div>';
		}
		
		// Grid Settings
		if ( 'slider' !== $settings['layout_select'] ) {
			
			if ( 'upsell' !== $settings['query_selection'] && 'cross-sell' !== $settings['query_selection'] && 'related-product' !== $settings['query_selection'] ) {
				// Sort & Results
				$this->render_grid_sorting( $settings, $posts );

				if ( !((is_product_category() || is_product_tag()) && !tmpcoder_is_availble()) ) {
					// Filters
					$this->render_grid_filters( $settings );
				}
			}

			$this->add_grid_settings( $settings );
			$render_attribute = $this->get_render_attribute_string( 'grid-settings' );

		// Slider Settings
		} else {
			$this->add_slider_settings( $settings );
			$render_attribute = $this->get_render_attribute_string( 'slider-settings' );
		}

		// Grid Wrap
		echo wp_kses_post('<section class="tmpcoder-grid elementor-clearfix" '. $render_attribute .' data-found-posts = '. $posts->found_posts .'>');

		while ( $posts->have_posts() ) : $posts->the_post();

			// Post Class
			$post_class = implode( ' ', get_post_class( 'tmpcoder-grid-item elementor-clearfix', get_the_ID() ) );

			// Grid Item
			echo '<article class="'. esc_attr( $post_class ) .'">';
			echo '<ul class="tmpcoder-grid-items-swatches-parent">';
			echo '<li>';

			// Password Protected Form
			$this->render_password_protected_input( $settings );

			// Inner Wrapper
			echo '<div class="tmpcoder-grid-item-inner">';

			// Content: Above Media
			$this->get_elements_by_location( 'above', $settings, get_the_ID() );

			// Media
			echo '<div class="tmpcoder-grid-media-wrap'. esc_attr($this->get_image_effect_class( $settings )) .' " data-overlay-link="'. esc_attr( $settings['overlay_post_link'] ) .'">';

				if( 'yes' === $settings['overlay_post_link'] ) {
					$open_links_in_new_tab = 'yes' === $this->get_settings()['open_links_in_new_tab'] ? '_blank' : '_self';
					echo '<a target="'. esc_attr($open_links_in_new_tab) .'" class="tmpcoder-grid-media-link" href="'. esc_url( get_the_permalink( get_the_ID() ) ) .'"></a>';
				}

				// Post Thumbnail
				$this->render_product_thumbnail( $settings, get_the_ID() );

				// Media Hover
				echo '<div class="tmpcoder-grid-media-hover tmpcoder-animation-wrap">';

					// Filter to compensate woo incompatibility
					echo wp_kses_post(apply_filters('tmpcoder_grid_media_hover_content', '', get_the_ID()));

					// Media Overlay
					$this->render_media_overlay( $settings );

					// Content: Over Media
					$this->get_elements_by_location( 'over', $settings, get_the_ID() );

				echo '</div>';
			echo '</div>';

			// Content: Below Media
			$this->get_elements_by_location( 'below', $settings, get_the_ID() );

			echo '</div>'; // End .tmpcoder-grid-item-inner
			echo '</li>'; 
			echo '</ul>';
			echo '</article>'; // End .tmpcoder-grid-item

		endwhile;

		// reset
		wp_reset_postdata();

		// Grid Wrap
		echo '</section>';

		if ( 'slider' === $settings['layout_select'] ) {
			if ( $posts->found_posts > (int) $settings['layout_slider_amount'] &&  (int) $settings['layout_slider_amount'] < $settings['query_posts_per_page'] ) {
				// Slider Navigation (only when enabled)
				if ( $settings['layout_slider_nav'] === 'yes' ) {
					echo '<div class="tmpcoder-grid-slider-arrow-container">';
						$nav_icon = isset($settings['layout_slider_nav_icon']) ? $settings['layout_slider_nav_icon'] : 'svg-angle-1-left';
						echo '<div class="tmpcoder-grid-slider-prev-arrow tmpcoder-grid-slider-arrow" id="tmpcoder-grid-slider-prev-'. esc_attr($this->get_id()) .'">'. wp_kses(tmpcoder_get_icon( $nav_icon, '' ), tmpcoder_wp_kses_allowed_html()) .'</div>';
						echo '<div class="tmpcoder-grid-slider-next-arrow tmpcoder-grid-slider-arrow" id="tmpcoder-grid-slider-next-'. esc_attr($this->get_id()) .'">'. wp_kses(tmpcoder_get_icon( $nav_icon, '' ), tmpcoder_wp_kses_allowed_html()) .'</div>';
					echo '</div>';
				}

				// Slider Dots
				echo '<div class="tmpcoder-grid-slider-dots"></div>';
			}
		}	

		// Pagination
		$this->render_grid_pagination( $settings );
		// No Posts Found
		else:

			if ('upsell' !== $settings['query_selection'] && 'cross-sell' !== $settings['query_selection']) {
				echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';
			}

		// Loop: End
		endif;
	}
}