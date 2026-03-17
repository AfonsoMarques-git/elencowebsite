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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Media_Grid extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-media-grid';
	}

	public function get_title() {
		return esc_html__( 'Image Grid/Slider/Carousel', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category' ];
	}

	public function get_keywords() {
		return [ 'image gallery', 'image slider', 'image carousel', 'image grid', 'media grid', 'massonry grid', 'isotope', 'massonry grid', 'filterable grid' ];
	}

	public function get_script_depends() {

		$depends = [ 'tmpcoder-isotope' => true, 'tmpcoder-slick' => true, 'tmpcoder-lightgallery' => true, 'tmpcoder-grid-widgets' => true ];

		if ( ! tmpcoder_elementor()->preview->is_preview_mode() ) {
			$settings = $this->get_settings();
			$settings_new = $this->get_settings_for_display();
			$settings = array_merge( $settings, $settings_new );

			if ( $settings['layout_select'] != 'slider' ) {
				unset( $depends['tmpcoder-slick'] );
			}if ( $settings['layout_select'] != 'masonry' && $settings['layout_select'] != 'fitRows' ) {
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

		$depends = [ 'tmpcoder-animations-css' => true, 'tmpcoder-link-animations-css' => true, 'tmpcoder-loading-animations-css' => true, 'tmpcoder-lightgallery-css' => true, 'tmpcoder-grid-widgets' => true ];

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

	public function add_control_query_randomize() {
		$this->add_control(
			'query_randomize',
			[
				'label' => sprintf( 
                            /* translators: %s: Randomize Query */
                            __( 'Randomize Query %s', 'sastra-essential-addons-for-elementor' ), 
                            '<i class="eicon-pro-icon"></i>' 
                        ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control no-distance'
			]
		);
	}

	public function add_control_order_posts() {
        $this->add_control(
			'order_posts',
			[
				'label' => esc_html__( 'Order By', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'label_block' => false,
				'options' => [
					'date' => esc_html__( 'Date', 'sastra-essential-addons-for-elementor'),
					'pro-tl' => esc_html__( 'Title (Pro)', 'sastra-essential-addons-for-elementor'),
					'pro-mf' => esc_html__( 'Last Modified (Pro)', 'sastra-essential-addons-for-elementor'),
					'pro-d' => esc_html__( 'Post ID (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-ar' => esc_html__( 'Post Author (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-cc' => esc_html__( 'Comment Count (Pro)', 'sastra-essential-addons-for-elementor' )
				],
				'condition' => [
					'query_randomize!' => 'rand',
					'query_selection!' => 'manual'
				]
			]
		);
	}
	
	public function add_control_layout_columns() {
		$this->add_responsive_control(
			'layout_columns',
			[
				'label' => esc_html__( 'Columns', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 4,
				'widescreen_default' => 4,
				'laptop_default' => 4,
				'tablet_extra_default' => 4,
				'tablet_default' => 3,
				'mobile_extra_default' => 3,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'sastra-essential-addons-for-elementor' ),
					2 => esc_html__( 'Two', 'sastra-essential-addons-for-elementor' ),
					3 => esc_html__( 'Three', 'sastra-essential-addons-for-elementor' ),
					4 => esc_html__( 'Four', 'sastra-essential-addons-for-elementor' ),
					5 => esc_html__( 'Five', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-grid-columns-%s',
				'separator' => 'before',
				'render_type' => 'template',
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				]
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
					'fade' => esc_html__( 'Fade', 'sastra-essential-addons-for-elementor' ),
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
					3 => esc_html__( 'Three', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-grid-slider-columns-%s',
				'frontend_available' => true,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
	}

	public function add_control_layout_slider_rows() {
		$this->add_control(
			'layout_slider_rows',
			[
				'label' => esc_html__( 'Rows (Carousel)', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 1,
				'classes' => 'tmpcoder-pro-control no-distance',
				'options' => [
					1 => esc_html__( 'One', 'sastra-essential-addons-for-elementor' ),
					2 => esc_html__( 'Two', 'sastra-essential-addons-for-elementor' ),
					3 => esc_html__( 'Three', 'sastra-essential-addons-for-elementor' ),
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
	}
	
	public function add_control_layout_slider_nav_hover() {
		
	}
	
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
	
	public function add_controls_group_layout_slider_autoplay() {

	}
	
	public function add_option_element_select() {
		return [
			'title' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
			'caption' => esc_html__( 'Caption', 'sastra-essential-addons-for-elementor' ),
			'date' => esc_html__( 'Date', 'sastra-essential-addons-for-elementor' ),
			'time' => esc_html__( 'Time', 'sastra-essential-addons-for-elementor' ),
			'author' => esc_html__( 'Author', 'sastra-essential-addons-for-elementor' ),
			'lightbox' => esc_html__( 'Lightbox', 'sastra-essential-addons-for-elementor' ),
			'separator' => esc_html__( 'Separator', 'sastra-essential-addons-for-elementor' ),
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
					'zoom-in' => esc_html__( 'Zoom In', 'sastra-essential-addons-for-elementor' ),
					'pro-zo' => esc_html__( 'Zoom Out (Pro)', 'sastra-essential-addons-for-elementor' ),
					'grayscale-in' => esc_html__( 'Grayscale In', 'sastra-essential-addons-for-elementor' ),
					'pro-go' => esc_html__( 'Grayscale Out (Pro)', 'sastra-essential-addons-for-elementor' ),
					'blur-in' => esc_html__( 'Blur In', 'sastra-essential-addons-for-elementor' ),
					'pro-bo' => esc_html__( 'Blur Out (Pro)', 'sastra-essential-addons-for-elementor' ),
					'slide' => esc_html__( 'Slide', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'zoom-in',
			]
		);
	}
	
	public function add_control_lightbox_popup_thumbnails() {
		
	}
	
	public function add_control_lightbox_popup_thumbnails_default() {
		
	}
	
	public function add_control_lightbox_popup_sharing() {
		
	}
	
	public function add_control_filters_deeplinking() {
		$this->add_control(
			'filters_deeplinking',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Enable Deep Linking %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control no-distance'
			]
		);		
	}
	
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
				],
				'separator' => 'before',
			]
		);
	}
	
	public function add_control_filters_icon() {}
	
	public function add_control_filters_icon_align() {}

	public function add_control_filters_count() {
		$this->add_control(
			'filters_count',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Count %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control no-distance'
			]
		);
	}
	
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
				],
				'separator' => 'after'
			]
		);
	}

	public function add_section_style_likes() {}

	public function add_section_style_sharing() {}
	
	public function add_control_grid_item_even_bg_color() {}
	
	public function add_control_grid_item_even_border_color() {}
	
	public function add_control_overlay_color() {
		$this->add_control(
			'overlay_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.4)',
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
	
	public function add_control_tax1_pointer_color_hr() {}
	
	public function add_control_tax1_pointer() {}
	
	public function add_control_tax1_pointer_height() {}
	
	public function add_control_tax1_pointer_animation() {}
	
	public function add_control_tax2_pointer_color_hr() {}
	
	public function add_control_tax2_pointer() {}
	
	public function add_control_tax2_pointer_height() {}
	
	public function add_control_tax2_pointer_animation() {}
	
	public function add_control_filters_pointer_color_hr() {}
	
	public function add_control_filters_pointer() {}
	
	public function add_control_filters_pointer_height() {}
	
	public function add_control_filters_pointer_animation() {}
	
	public function add_control_stack_grid_slider_nav_position() {}
	
	public function add_control_grid_slider_dots_hr() {}

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

		$this->add_control_pagination_type();

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
				'default' => 'yes',
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
				'default' => 'yes',
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
				'default' => 'yes',
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

		// Get Available Taxonomies
		$post_taxonomies = get_object_taxonomies( 'attachment', 'objects' );
		$post_taxonomy_names = [];

		$this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Selection', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Auto', 'sastra-essential-addons-for-elementor' ),
					'manual' => esc_html__( 'Manual', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$this->add_control_order_posts();

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
					'query_selection!' => 'manual'
				]
			]
		);

		$this->add_control(
			'query_author',
			[
				'label' => esc_html__( 'Authors', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_users',
				'multiple' => true,
				'label_block' => true,
				'separator' => 'before',
				'condition' => [
					'query_selection' => 'dynamic',
				],
			]
		);

		// Taxonomies
		foreach ( $post_taxonomies as $slug => $tax ) {
			global $wp_taxonomies;
			$post_type = '';

			if ( isset($wp_taxonomies[$slug]) && isset($wp_taxonomies[$slug]->object_type[0]) ) {
				$post_type = $wp_taxonomies[$slug]->object_type[0];
			}

			$post_taxonomy_names[ $slug ] = $tax->label;

			$this->add_control(
				'query_taxonomy_'. $slug,
				[
					'label' => $tax,
					'type' => 'tmpcoder-ajax-select2',
					'options' => 'ajaxselect2/get_taxonomies',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'query_source' => $post_type,
						'query_selection' => 'dynamic',
					],
				]
			);
		}

		// Exclude
		$this->add_control(
			'query_exclude_attachment',
			[
				'label' => esc_html__( 'Exclude Images', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_posts_by_post_type',
				'query_slug' => 'attachment',
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_selection' => 'dynamic',
				],
			]
		);

		// Manual Selection
		$this->add_control(
			'query_manual_attachment',
			[
				'label' => esc_html__( 'Add Images', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::GALLERY,
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'query_selection' => 'manual',
				],
			]
		);

		$this->add_control(
			'query_posts_per_page',
			[
				'label' => esc_html__( 'Items Per Page', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 12,
				'min' => 0,
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
					'query_selection' => 'dynamic',
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
				'default' => 'No Images Found!',
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);

		$this->add_control_query_randomize();
		
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

		$this->add_control(
			'layout_select',
			[
				'label' => esc_html__( 'Select Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'masonry',
				'options' => [
					'fitRows' => esc_html__( 'FitRows - Equal Height', 'sastra-essential-addons-for-elementor' ),
					'masonry' => esc_html__( 'Masonry - Unlimited Height', 'sastra-essential-addons-for-elementor' ),
					'slider' => esc_html__( 'Slider / Carousel', 'sastra-essential-addons-for-elementor' ),
				],
				'label_block' => true,
				'frontend_available' => true
			]
		);

		$this->add_control(
			'layout_even_feture',
			[
				'label' => esc_html__( 'Even (Image Same Height)', 'sastra-essential-addons-for-elementor' ),
				'description' => esc_html__('Please note that Even doesn\'t work with masonry', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'layout_select!' => 'masonry',
				],
				'prefix_class' => 'tmpcoder-media-grid-even-layout-'
			]
		);

		$this->add_responsive_control(
			'layout_even_img_height',
			[
				'label' => esc_html__( 'Image Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 250,
				],
				'widescreen_default' => [
					'size' => 250,
				],
				'laptop_default' => [
					'size' => 200,
				],
				'tablet_extra_default' => [
					'size' => 150,
				],
				'tablet_default' => [
					'size' => 150,
				],
				'mobile_extra_default' => [
					'size' => 100,
				],
				'mobile_default' => [
					'size' => 100,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'condition' => [
					'layout_even_feture' => 'yes',
					'layout_select!' => 'masonry',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-image-wrap' => 'height: {{SIZE}}px;'
				]
			]
		);

		$this->add_control(
			'countdown_apply_changes',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-update-preview editor-tmpcoder-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
				'separator' => 'after',
				'condition' => [
					'layout_even_feture' => 'yes',
					'layout_select!' => 'masonry',
				],
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

		$this->add_responsive_control(
			'layout_gutter_hr',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'widescreen_default' => [
					'size' => 10,
				],
				'laptop_default' => [
					'size' => 10,
				],
				'tablet_extra_default' => [
					'size' => 10,
				],
				'tablet_default' => [
					'size' => 10,
				],
				'mobile_extra_default' => [
					'size' => 10,
				],
				'mobile_default' => [
					'size' => 10,
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
					'size' => 10,
				],
				'widescreen_default' => [
					'size' => 10,
				],
				'laptop_default' => [
					'size' => 10,
				],
				'tablet_extra_default' => [
					'size' => 10,
				],
				'tablet_default' => [
					'size' => 10,
				],
				'mobile_extra_default' => [
					'size' => 10,
				],
				'mobile_default' => [
					'size' => 10,
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

		$this->add_responsive_control(
			'layout_filters',
			[
				'label' => esc_html__( 'Show Filters', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				// 'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-filters' => 'display:{{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
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

		$this->add_control_layout_animation();

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

		$this->add_control_layout_slider_rows();

		$this->add_control(
			'layout_slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'default' => 2,
				'frontend_available' => true,
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
					'size' => 0,
				],			
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-grid-slider-rows-2 .tmpcoder-grid .tmpcoder-grid-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-grid-slider-rows-3 .tmpcoder-grid .tmpcoder-grid-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-grid-slider-rows-2 .tmpcoder-grid .slick-list' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-grid-slider-rows-3 .tmpcoder-grid .slick-list' => 'margin-bottom: -{{SIZE}}{{UNIT}};'
				],
				'render_type' => 'template',
				'condition' => [
					'layout_slider_amount!' => '1',
					'layout_select' => 'slider',
				]
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

		$this->add_controls_group_layout_slider_autoplay();

		$this->add_control(
			'layout_slider_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
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
					// 'layout_slider_amount' => 1,
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

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => array_merge( $this->add_option_element_select(), $post_taxonomy_names ),
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_location',
			[
				'label' => esc_html__( 'Location', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'over',
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
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
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
				'default' => 'h4',
				'condition' => [
					'element_select' => 'title',
				]
			]
		);

		$repeater->add_control(
			'element_dropcap',
			[
				'label' => esc_html__( 'Enable Drop Cap', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [ 'caption' ],
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
					'element_select' => [ 'title', 'caption' ],
				]
			]
		);

		$repeater->add_control(
			'element_show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [ 'author' ]
				]
			]
		);

		$repeater->add_control(
			'element_avatar_size',
			[
				'label' => esc_html__( 'Avatar Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 32,
				'min' => 8,
				'condition' => [
					'element_select' => [ 'author' ],
					'element_show_avatar' => 'yes'
				],
				'separator' => 'after'
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
						'caption',
						'date',
						'time',
						'author',
						'likes',
						'sharing',
						'lightbox',
						'separator',
						'post_format',
					],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_tax_style',
			[
				'label' => esc_html__( 'Select Styling', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tmpcoder-grid-tax-style-1',
				'options' => [
					'tmpcoder-grid-tax-style-1' => esc_html__( 'Taxonomy Style 1', 'sastra-essential-addons-for-elementor' ),
					'tmpcoder-grid-tax-style-2' => esc_html__( 'Taxonomy Style 2', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'element_select!' => [
						'title',
						'caption',
						'date',
						'time',
						'author',
						'likes',
						'sharing',
						'lightbox',
						'separator',
					],
				],
				'separator' => 'after'
			]
		);

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
						'caption',
						'separator',
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
						'caption',
						'separator',
					],
					'element_extra_text_pos!' => 'none'
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
						'caption',
						'separator',
						'likes',
						'sharing',
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
				'exclude_inline_options' => 'svg',
				'default' => [
					'value' => 'fas fa-expand',
					'library' => 'fa-solid',
				],
				'condition' => [
					'element_select!' => [
						'title',
						'caption',
						'separator',
						'likes',
						'sharing',
					],
					'element_extra_icon_pos!' => 'none'
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
					[
						'element_select' => 'lightbox',
						'element_location' => 'over',
						'element_extra_icon_pos' => 'before',
						'element_animation' => 'fade-in',
						'element_animation_size' => 'large',
						'element_lightbox_overlay' => 'yes',
						'element_sharing_trigger_icon' => 'fas fa-share',
					],
					[
						'element_select' => 'title',
						'element_location' => 'over',
						'element_extra_icon_pos' => 'before',
						'element_animation' => 'fade-in',
						'element_animation_size' => 'large',
						'element_sharing_trigger_icon' => 'fas fa-share',
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
				'default' => 'yes',
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
				'label' => esc_html__( 'Overlay Height', 'sastra-essential-addons-for-elementor' ),
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
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-animations-alt',
				'default' => 'fade-in',
				'condition' => ['media_overlay_on_off' => 'yes']
			]
		);

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

		$this->add_control_image_effects();

		$this->add_control(
			'image_effects_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
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
				'default' => 'small',
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
				// 'condition' => [
				// 	'element_select' => [ 'lightbox' ],
				// ],
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
				'options' => $post_taxonomy_names,
				'default' => 'media_category',
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
				'default' => 'All Photos',
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
				'default' => 'left',
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

		// $this->tmpcoder_pagination_tab_content();

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
			'grid_item_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-above-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-item-below-content' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_grid_item_even_bg_color();

		$this->add_control(
			'grid_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-above-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-item-below-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-item-below-content' => 'border-color: {{VALUE}}',
				],
			]
		);

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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-above-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-grid-item-below-content' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .tmpcoder-grid-item-above-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-below-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_responsive_control(
            'image_alignement',
            [
                'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'selectors_dictionary' => [
					'center' => 'margin: auto;',
					'right' => 'float: right;',
					'left' => 'float: left;'
				],
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
					'{{WRAPPER}} .tmpcoder-grid-image-wrap img' => '{{VALUE}};',
				],
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
				'condition' => [
					'media_overlay_on_off' => 'yes'
				],
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
				'default' => '#ffffff',
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
				'default' => '#ffffff',
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
				'name' => 'title_typography',
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
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-title .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Caption ----------
		$this->start_controls_section(
			'section_style_caption',
			[
				'label' => esc_html__( 'Caption', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-caption .inner-block p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'caption_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-caption .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'caption_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-caption .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'caption_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-caption'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'caption_dropcap_typography',
				'label' => esc_html__( 'Drop Cap Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-caption.tmpcoder-enable-dropcap p:first-child:first-letter'
			]
		);

		$this->add_responsive_control(
			'caption_justify',
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
					'{{WRAPPER}} .tmpcoder-grid-item-caption .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'caption_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-item-caption .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_border_width',
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
					'{{WRAPPER}} .tmpcoder-grid-item-caption .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'caption_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'caption_padding',
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
					'{{WRAPPER}} .tmpcoder-grid-item-caption .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'caption_margin',
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
					'{{WRAPPER}} .tmpcoder-grid-item-caption .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Date -------------
		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-date .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-date .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-date .inner-block > span' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-date .inner-block span[class*="tmpcoder-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-date .inner-block [class*="tmpcoder-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-item-date .inner-block [class*="tmpcoder-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-date'
			]
		);

		$this->add_control(
			'date_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-item-date .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_border_width',
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
					'{{WRAPPER}} .tmpcoder-grid-item-date .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'date_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'date_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-date .tmpcoder-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-date .tmpcoder-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_icon_spacing',
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
					'{{WRAPPER}} .tmpcoder-grid-item-date .tmpcoder-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-date .tmpcoder-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'date_padding',
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
					'{{WRAPPER}} .tmpcoder-grid-item-date .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'date_margin',
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
					'{{WRAPPER}} .tmpcoder-grid-item-date .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Time -------------
		$this->start_controls_section(
			'section_style_time',
			[
				'label' => esc_html__( 'Time', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'time_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-time .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'time_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-time .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'time_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-time .inner-block > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'time_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-time .inner-block span[class*="tmpcoder-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'time_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-time .inner-block [class*="tmpcoder-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-item-time .inner-block [class*="tmpcoder-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'time_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-time'
			]
		);

		$this->add_control(
			'time_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-item-time .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'time_border_width',
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
					'{{WRAPPER}} .tmpcoder-grid-item-time .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'time_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'time_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-time .tmpcoder-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-time .tmpcoder-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'time_icon_spacing',
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
					'{{WRAPPER}} .tmpcoder-grid-item-time .tmpcoder-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-time .tmpcoder-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'time_padding',
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
					'{{WRAPPER}} .tmpcoder-grid-item-time .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'time_margin',
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
					'{{WRAPPER}} .tmpcoder-grid-item-time .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Author -----------
		$this->start_controls_section(
			'section_style_author',
			[
				'label' => esc_html__( 'Author', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_author_style' );

		$this->start_controls_tab(
			'tab_grid_author_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'author_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'author_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block span[class*="tmpcoder-grid-extra-text"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_author_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'author_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'author_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'author_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-item-author'
			]
		);

		$this->add_control(
			'author_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_border_width',
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
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'author_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'author_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'author_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-item-author .tmpcoder-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-author .tmpcoder-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_icon_spacing',
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
					'{{WRAPPER}} .tmpcoder-grid-item-author .tmpcoder-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-item-author .tmpcoder-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'author_avatar_spacing',
			[
				'label' => esc_html__( 'Avatar Spacing', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}} .tmpcoder-grid-item-author img' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'author_padding',
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
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'author_margin',
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
					'{{WRAPPER}} .tmpcoder-grid-item-author .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

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
				'default' => '#ffffff',
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
				'name' => 'lightbox_typography',
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
					'size' => 5,
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
					'bottom' => 10,
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
				'default' => '#E8E8E8',
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
		// Section: Taxonomy Style 1 ------
		$this->start_controls_section(
			'section_style_tax1',
			[
				'label' => esc_html__( 'Taxonomy Style 1', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_tax1_style' );

		$this->start_controls_tab(
			'tab_grid_tax1_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'tax1_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax1_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block span[class*="tmpcoder-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block [class*="tmpcoder-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block [class*="tmpcoder-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_tax1_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'tax1_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .tmpcoder-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .tmpcoder-pointer-item:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax1_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_tax1_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_tax1_pointer();

		$this->add_control_tax1_pointer_height();

		$this->add_control_tax1_pointer_animation();

		$this->add_control(
			'tax1_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .tmpcoder-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .tmpcoder-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tax1_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-tax-style-1'
			]
		);

		$this->add_control(
			'tax1_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax1_border_width',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'tax1_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tax1_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .tmpcoder-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .tmpcoder-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax1_icon_spacing',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .tmpcoder-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .tmpcoder-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tax1_gutter',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tax1_padding',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'tax1_margin',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'tax1_radius',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-1 .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Taxonomy Style 2 -
		$this->start_controls_section(
			'section_style_tax2',
			[
				'label' => esc_html__( 'Taxonomy Style 2', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_tax2_style' );

		$this->start_controls_tab(
			'tab_grid_tax2_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'tax2_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax2_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax2_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_tax2_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'tax2_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .tmpcoder-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .tmpcoder-pointer-item:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax2_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax2_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_tax2_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_tax2_pointer();

		$this->add_control_tax2_pointer_height();

		$this->add_control_tax2_pointer_animation();

		$this->add_control(
			'tax2_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .tmpcoder-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .tmpcoder-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tax2_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-tax-style-2'
			]
		);

		$this->add_control(
			'tax2_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax2_border_width',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'tax2_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tax2_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .tmpcoder-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .tmpcoder-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax2_icon_spacing',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .tmpcoder-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .tmpcoder-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tax2_gutter',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tax2_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'tax2_margin',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'tax2_radius',
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
					'{{WRAPPER}} .tmpcoder-grid-tax-style-2 .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'default' => 'rgba(255,255,255,0.8)',
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
				'default' => '#ffffff',
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
					'bottom' => 0,
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
				'default' => '#222222',
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
				'default' => '#ffffff',
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
				'name' => 'filters_typography',
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
				'size_units' => [ 'px', ],
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
		// Section: Pagination ----------
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
				'default' => '#ffffff',
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
				'name' => 'pagination_typography',
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
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'size' => 35,
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
					'right' => 15,
					'bottom' => 8,
					'left' => 15,
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

	}


	// Get Taxonomies Related to Post Type
	public function get_related_taxonomies() {
		$relations = [];
		$post_types = get_custom_types_of( 'post', false );

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

		// $max_num_pages
		return $max_num_pages;
	}

	// Main Query Args
	public function get_main_query_args() {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$author = ! empty( $settings[ 'query_author' ] ) ? implode( ',', $settings[ 'query_author' ] ) : '';

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
		
		$offset = ( $paged - 1 ) * $settings['query_posts_per_page'] + $settings[ 'query_offset' ];

		if ( ! tmpcoder_is_availble() ) {
			$settings[ 'query_randomize' ] = '';
			$settings['order_posts'] = 'date';
		}

		$query_order_by = (!empty($settings['query_randomize']) && '' != $settings['query_randomize']) ? $settings['query_randomize'] : $settings['order_posts'];

		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$query_order_by = 'post__in';
		}

		$ids_array = '';		

		if ($settings[ 'query_exclude_attachment' ]) {
			
			$slug_args = [
			    'post_type'      => 'attachment',
			    'posts_per_page' => -1,
			    'post_name__in'  => $settings[ 'query_exclude_attachment' ],
			    'fields'         => 'ids' 
			];

			$ids_array = get_posts( $slug_args );
		}

		// Dynamic
		$args = [
			'post_type' => 'attachment',
        	'post_status' => 'inherit',
			'tax_query' => $this->get_tax_query_args(),
			'post__not_in' => $ids_array,
			'posts_per_page' => $settings['query_posts_per_page'],
			'orderby' => $query_order_by,
			'author' => $author,
			'paged' => $paged,
			'offset' => $offset
		];

		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			$attachments = $settings['query_manual_attachment'];

			$attachments_count = 0;

			if (!empty($attachments) ) {
				$attachments_count = count($attachments);
			}

			for ( $i = 0; $i < $attachments_count; $i++ ) { 
				array_push( $post_ids, $settings['query_manual_attachment'][$i]['id'] );
			}

			$orderby = (empty($settings[ 'query_randomize' ]) || '' === $settings[ 'query_randomize' ]) ? 'post__in' : 'rand';

			$args = [
				'post_type' => 'attachment',
        		'post_status' => 'inherit',
				'post__in' => $post_ids,
				'orderby' => $orderby,
				'paged' => $paged,
				'posts_per_page' => $settings['query_posts_per_page'],
				'paged' => $paged,
			];
		}

		if ( 'rand' !== $query_order_by && 'manual' !== $settings['query_selection'] ) {
			$args['order'] = $settings['order_direction'];
		}

		return $args;
	}

	// Taxonomy Query Args
	public function get_tax_query_args() {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$tax_query = [];

		foreach ( get_object_taxonomies( 'attachment' ) as $tax ) {
			if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
				array_push( $tax_query, [
					'taxonomy' => $tax,
					'field' => 'id',
					'terms' => $settings[ 'query_taxonomy_'. $tax ]
				] );
			}
		}

		return $tax_query;
	}

	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

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
            // 'pro-zi' ==  $settings['image_effects'] ||
			if ( 'pro-zo' ==  $settings['image_effects'] || 'pro-go' ==  $settings['image_effects'] || 'pro-bo' ==  $settings['image_effects'] ) {
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

	// Render Post Thumbnail
	public function render_post_thumbnail( $settings ) {
		$id = get_the_ID();

		$settings[ 'layout_image_crop' ] = ['id' => $id];
		$main_thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'layout_image_crop' );

		$image_original_class = 'wp-image-'.$id;
		$custom_image_class = $image_original_class.' grid-main-image tmpcoder-anim-timing-'.$settings[ 'image_effects_animation_timing'];
		$main_thumbnail_html = str_replace($image_original_class, $custom_image_class, $main_thumbnail_html);

		echo '<div class="tmpcoder-grid-image-wrap" data-src="'. esc_url( wp_get_attachment_url( $id ) ) .'">';
			echo wp_kses_post($main_thumbnail_html);
		echo '</div>';
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
	public function render_post_title( $settings, $class ) {
		$title_pointer = ! tmpcoder_is_availble() ? 'none' : $this->get_settings()['title_pointer'];
		$title_pointer_animation = ! tmpcoder_is_availble() ? 'fade' : $this->get_settings()['title_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['title_pointer']) && 'none' !== $this->get_settings()['title_pointer']) ? 'class="tmpcoder-pointer-item"' : '';

		$class .= ' tmpcoder-pointer-'. $title_pointer;
		$class .= ' tmpcoder-pointer-line-fx tmpcoder-pointer-fx-'. $title_pointer_animation;

		echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['element_title_tag']) ) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a '. wp_kses_post($pointer_item_class) .' href="'. esc_url( get_the_permalink() ) .'">';
					echo esc_html(wp_trim_words( get_the_title(), $settings['element_word_count'] ));
				echo '</a>';
			echo '</div>';
		echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['element_title_tag']) ) .'>';
	}

	// Render Post Excerpt
	public function render_post_excerpt( $settings, $class ) {
		$dropcap_class = 'yes' === $settings['element_dropcap'] ? ' tmpcoder-enable-dropcap' : '';
		$class .= $dropcap_class;

		if ( '' === get_the_excerpt() ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<p>'. esc_html(wp_trim_words( get_the_excerpt(), $settings['element_word_count'] )) .'</p>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Date
	public function render_post_date( $settings, $class ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
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

				// Date
				echo esc_html(apply_filters( 'the_date', get_the_date( '' ), get_option( 'date_format' ), '', '' ));

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
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Time
	public function render_post_time( $settings, $class ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
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

				// Time
				echo esc_html(get_the_time(''));

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
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Author
	public function render_post_author( $settings, $class ) {
		$author_id =  get_post_field( 'post_author' );

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="tmpcoder-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				// Author
				echo '<a href="'. esc_url( get_author_posts_url( $author_id ) ) .'">';

				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();
		
					echo '<span class="tmpcoder-grid-extra-icon-left">';
					echo wp_kses($extra_icon, tmpcoder_wp_kses_allowed_html()); 
				 	echo '</span>';
				}
					if ( 'yes' === $settings['element_show_avatar'] ) {
						echo get_avatar( $author_id, $settings['element_avatar_size'] );
					}

					echo '<span>'. esc_html(get_the_author_meta( 'display_name', $author_id )) .'</span>';

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();
		
					echo '<span class="tmpcoder-grid-extra-icon-right">';
					echo wp_kses($extra_icon, tmpcoder_wp_kses_allowed_html()); 
				 	echo '</span>';
				}
				echo '</a>';

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="tmpcoder-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Likes
	public function render_post_likes( $settings, $class, $post_id ) {}

	// Render Post Sharing
	public function render_post_sharing_icons( $settings, $class ) {}

	// Render Post Lightbox
	public function render_post_lightbox( $settings, $class, $post_id ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = get_the_post_thumbnail_url( $post_id );

				// Audio Post Type
				if ( 'audio' === get_post_format() ) {
					// Load Meta Value
					if ( 'meta' === $settings['element_lightbox_pfa_select'] ) {
						$utilities = new Utilities();
						$meta_value = get_post_meta( $post_id, $settings['element_lightbox_pfa_meta'], true );

						// URL
						if ( false === strpos( $meta_value, '<iframe ' ) ) {
							add_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50, 3 );
								$track_url = wp_oembed_get( $meta_value );
							remove_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50 );

						// Iframe
						} else {
							$track_url = filter_oembed_results( $meta_value );
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
							$video = \Elementor\Embed::get_video_properties( filter_oembed_results($meta_value) );
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

				if ( $lightbox_source == false ) {
					$lightbox_source = wp_get_attachment_url( $post_id );
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
	public function render_post_element_separator( $settings, $class ) {
		echo '<div class="'. esc_attr($class .' '. $settings['element_separator_style']) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Render Post Taxonomies
	public function render_post_taxonomies( $settings, $class, $post_id ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;

		$tax1_pointer = ! tmpcoder_is_availble() ? 'none' : $this->get_settings()['tax1_pointer'];
		$tax1_pointer_animation = ! tmpcoder_is_availble() ? 'fade' : $this->get_settings()['tax1_pointer_animation'];
		$tax2_pointer = ! tmpcoder_is_availble() ? 'none' : $this->get_settings()['tax2_pointer'];
		$tax2_pointer_animation = ! tmpcoder_is_availble() ? 'fade' : $this->get_settings()['tax2_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['tax1_pointer']) && 'none' !== $this->get_settings()['tax1_pointer']) || (isset($this->get_settings()['tax2_pointer']) && 'none' !== $this->get_settings()['tax2_pointer']) ? 'class="tmpcoder-pointer-item"' : '';

		// Pointer Class
		if ( 'tmpcoder-grid-tax-style-1' === $settings['element_tax_style'] ) {
			$class .= ' tmpcoder-pointer-'. $tax1_pointer;
			$class .= ' tmpcoder-pointer-line-fx tmpcoder-pointer-fx-'. $tax1_pointer_animation;
		} else {
			$class .= ' tmpcoder-pointer-'. $tax2_pointer;
			$class .= ' tmpcoder-pointer-line-fx tmpcoder-pointer-fx-'. $tax2_pointer_animation;
		}

		echo '<div class="'. esc_attr($class .' '. $settings['element_tax_style']) .'">';
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

	// Get Elements
	public function get_elements( $type, $settings, $class, $post_id ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
			case 'title':
				$this->render_post_title( $settings, $class );
				break;

			case 'caption':
				$this->render_post_excerpt( $settings, $class );
				break;

			case 'date':
				$this->render_post_date( $settings, $class );
				break;

			case 'time':
				$this->render_post_time( $settings, $class );
				break;

			case 'author':
				$this->render_post_author( $settings, $class );
				break;

			case 'likes':
				$this->render_post_likes( $settings, $class, $post_id );
				break;

			case 'sharing':
				$this->render_post_sharing_icons( $settings, $class );
				break;

			case 'lightbox':
				$this->render_post_lightbox( $settings, $class, $post_id );
				break;

			case 'separator':
				$this->render_post_element_separator( $settings, $class );
				break;
			
			default:
				$this->render_post_taxonomies( $settings, $class, $post_id );
				break;
		}

	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $post_id ) {
		$locations = [];

		foreach ( $settings['grid_elements'] as $data ) {
			$place = $data['element_location'];
			$align_vr = $data['element_align_vr'];

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
							$class  = 'tmpcoder-grid-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' tmpcoder-grid-item-display-'. $data['element_display'];
							$class .= ' tmpcoder-grid-item-align-'. $data['element_align_hr'];
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $post_id );
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
						$class  = 'tmpcoder-grid-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' tmpcoder-grid-item-display-'. $data['element_display'];
						$class .= ' tmpcoder-grid-item-align-'. $data['element_align_hr'];

						// Element
						$this->get_elements( $data['element_select'], $data, $class, $post_id );
					}
				echo '</div>';
			}
		}
	}

	// Render Grid Filters
	public function render_grid_filters( $settings ) {
		$taxonomy = $settings['filters_select'];

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
		$left_separator = 'left' === $settings['filters_separator_align'] ? '<em class="tmpcoder-grid-filters-sep">'. esc_attr($settings['filters_separator']) .'</em>' : '';
		$right_separator = 'right' === $settings['filters_separator_align'] ? '<em class="tmpcoder-grid-filters-sep">'. esc_attr($settings['filters_separator']) .'</em>' : '';

		// Count
		$post_count = 'yes' === $settings['filters_count'] ? '<sup data-brackets="'. esc_attr($settings['filters_count_brackets']) .'"></sup>' : '';

		// Pointer Class
		$pointer_class  = ' tmpcoder-pointer-'. $settings['filters_pointer'];
		$pointer_class .= ' tmpcoder-pointer-line-fx tmpcoder-pointer-fx-'. $settings['filters_pointer_animation'];
		$pointer_item_class = (isset($settings['filters_pointer']) && 'none' !== $settings['filters_pointer']) ? 'tmpcoder-pointer-item' : '';
		$pointer_item_class_name = (isset($settings['filters_pointer']) && 'none' !== $settings['filters_pointer']) ? 'tmpcoder-pointer-item' : '';

		// Filters List
		echo '<ul class="tmpcoder-grid-filters elementor-clearfix tmpcoder-grid-filters-sep-'. esc_attr($settings['filters_separator_align']) .'">';

		// All Filter
		if ( 'yes' === $settings['filters_all'] && 'yes' !== $settings['filters_linkable'] ) {
			echo '<li class="'. wp_kses_post($pointer_class) .'">';
			echo wp_kses('<span data-filter="*" class="tmpcoder-active-filter '. wp_kses_post($pointer_item_class_name) .'">'. $left_icon . esc_html($settings['filters_all_text']) . $right_icon . $post_count .'</span>'. $right_separator, tmpcoder_wp_kses_allowed_html()); 
			echo '</li>';
		}

		// Custom Filters
		if ( $settings['query_selection'] === 'dynamic' && ! empty( $custom_filters ) ) {
			$parent_filters = [];
			
			foreach ( $custom_filters as $key => $term_id ) {
				$filter = get_term_by( 'id', $term_id, $taxonomy );
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $filter->slug : $taxonomy .'-'. $filter->slug;

				// Parent Filters
				if ( 0 === $filter->parent ) {
					$children = get_term_children( $filter->term_id, $taxonomy );
					$data_role = ! empty($children) ? ' data-role="parent"' : '';

					echo '<li'. esc_attr($data_role) .' class="'. esc_attr($pointer_class) .'">';
						if ( 'yes' !== $settings['filters_linkable'] ) {
							echo wp_kses( $left_separator .'<span '. $pointer_item_class .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</span>'. $right_separator, tmpcoder_wp_kses_allowed_html()); 
						} else {
							echo wp_kses($left_separator .'<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $filter->term_id, $taxonomy )) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</a>'. $right_separator, tmpcoder_wp_kses_allowed_html()); 
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

					echo '<li'. esc_attr($data_role) .' class="'. esc_attr($pointer_class) .'">';
						if ( 'yes' !== $settings['filters_linkable'] ) {
							echo wp_kses($left_separator .'<span '. $pointer_item_class .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</span>'. $right_separator, tmpcoder_wp_kses_allowed_html()); 
						} else {
							echo wp_kses($left_separator .'<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $filter->term_id, $taxonomy )) .'" data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</a>'. $right_separator, tmpcoder_wp_kses_allowed_html()); 
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
				$children = get_term_children( $parent_filter, $taxonomy );
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $parent->slug : $taxonomy .'-'. $parent->slug;

				echo '<ul data-parent=".'. esc_attr(urldecode( $data_attr )) .'" class="tmpcoder-sub-filters">';

				echo '<li data-role="back" class="'. esc_attr($pointer_class) .'">';
					echo '<span class="tmpcoder-back-filter" data-filter=".'. esc_attr(urldecode( $data_attr )) .'">';
						echo '<i class="fas fa-long-arrow-alt-left"></i>&nbsp;&nbsp;'. esc_html__( 'Back', 'sastra-essential-addons-for-elementor' );
					echo '</span>';
				echo '</li>';

				foreach ( $children as $child ) {
					$sub_filter = get_term_by( 'id', $child, $taxonomy );
					$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $sub_filter->slug : $taxonomy .'-'. $sub_filter->slug;

					echo '<li data-role="sub" class="'. esc_attr($pointer_class) .'">';
						echo wp_kses( $left_separator .'<span '. $pointer_item_class .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($sub_filter->name) . $right_icon . $post_count .'</span>'. $right_separator, tmpcoder_wp_kses_allowed_html()); 
					echo '</li>';
				}

				echo '</ul>';
			}
		}

		echo '</ul>';
	}

	// Render Grid Pagination
	public function render_grid_pagination( $settings ) {
		// Return if Disabled
		if ( 'yes' !== $settings['layout_pagination'] || 1 === $this->get_max_num_pages( $settings ) || 'slider' === $settings['layout_select'] ) {
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
					echo wp_kses( tmpcoder_get_icon( $settings['pagination_on_icon'], 'left' ), tmpcoder_wp_kses_allowed_html()); 
					echo esc_html($settings['pagination_older_text']);
				echo '</a>';
			} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
				echo '<span class="tmpcoder-prev-post-link tmpcoder-disabled-arrow">';
					echo wp_kses(tmpcoder_get_icon( $settings['pagination_on_icon'], 'left' ),tmpcoder_wp_kses_allowed_html()); 
					echo esc_html($settings['pagination_older_text']);
				echo '</span>';
			}

			if ( $paged > 1 ) {
				echo '<a href="'. esc_url(get_pagenum_link( $paged - 1, true )) .'" class="tmpcoder-next-post-link">';
					echo esc_html($settings['pagination_newer_text']);
					echo wp_kses(tmpcoder_get_icon( $settings['pagination_on_icon'], 'right' ),tmpcoder_wp_kses_allowed_html()); 
				echo '</a>';
			} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
				echo '<span class="tmpcoder-next-post-link tmpcoder-disabled-arrow">';
					echo esc_html($settings['pagination_newer_text']);
					echo wp_kses(tmpcoder_get_icon( $settings['pagination_on_icon'], 'right' ),tmpcoder_wp_kses_allowed_html()); 
				echo '</span>';
			}

		// Numbered
		} elseif ( 'numbered' === $settings['pagination_type'] ) {
			$range = $settings['pagination_range'];
			$showitems = ( $range * 2 ) + 1;

			if ( 1 !== $pages ) {

			    if ( 'yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last'] ) {
			    	echo '<div class="tmpcoder-grid-pagi-left-arrows">';

				    if ( 'yes' === $settings['pagination_first_last'] ) {
				    	if ( $paged >= 2 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( 1, true )) .'" class="tmpcoder-first-page">';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_fl_icon'], 'left' ),tmpcoder_wp_kses_allowed_html()); 
					    		echo '<span>'. esc_html($settings['pagination_first_text']) .'</span>';
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="tmpcoder-first-page tmpcoder-disabled-arrow">';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_fl_icon'], 'left' ),tmpcoder_wp_kses_allowed_html()); 
					    		echo '<span>'. esc_html($settings['pagination_first_text']) .'</span>';
					    	echo '</span>';
				    	}
				    }

				    if ( 'yes' === $settings['pagination_prev_next'] ) {
				    	if ( $paged > 1 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $paged - 1, true )) .'" class="tmpcoder-prev-page">';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_pn_icon'], 'left' ),tmpcoder_wp_kses_allowed_html()); 
					    		echo '<span>'. esc_html($settings['pagination_prev_text']) .'</span>';
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="tmpcoder-prev-page tmpcoder-disabled-arrow">';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_pn_icon'], 'left' ),tmpcoder_wp_kses_allowed_html()); 
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
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_pn_icon'], 'right' ),tmpcoder_wp_kses_allowed_html()); 
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="tmpcoder-next-page tmpcoder-disabled-arrow">';
					    		echo '<span>'. esc_html($settings['pagination_next_text']) .'</span>';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_pn_icon'], 'right' ),tmpcoder_wp_kses_allowed_html());
					    	echo '</span>';
				    	}
				    }

				    if ( 'yes' === $settings['pagination_first_last'] ) {
				    	if ( $paged <= $pages - 1 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $pages, true )) .'" class="tmpcoder-last-page">';
					    		echo '<span>'. esc_html($settings['pagination_last_text']) .'</span>';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_fl_icon'], 'right' ),tmpcoder_wp_kses_allowed_html()); 
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="tmpcoder-last-page tmpcoder-disabled-arrow">';
					    		echo '<span>'. esc_html($settings['pagination_last_text']) .'</span>';
					    		echo wp_kses(tmpcoder_get_icon( $settings['pagination_fl_icon'], 'right' ),tmpcoder_wp_kses_allowed_html()); 
					    	echo '</span>';
				    	}
				    }
				    echo '</div>';
			    }
			}

		// Load More / Infinite Scroll
		} else {
			echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="tmpcoder-load-more-btn" data-e-disable-page-transition >';
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
			$settings['filters_deeplinking'] = '';
			$settings['filters_count'] = '';
			$settings['filters_default_filter'] = '';

			if ( 'pro-fd' == $settings['filters_animation'] || 'pro-fs' == $settings['filters_animation'] ) {
				$settings['filters_animation'] = 'zoom';
			}
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

		//:TODO
		$layout_settings = [
			'layout' => $settings['layout_select'],
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
			'filters_default_filter' => $settings['filters_default_filter'],
			'filters_linkable' => $settings['filters_linkable'],
			'filters_count' => $settings['filters_count'],
			'filters_hide_empty' => $settings['filters_hide_empty'],
			'filters_animation' => $settings['filters_animation'],
			'filters_animation_duration' => $settings['filters_animation_duration'],
			'filters_animation_delay' => $settings['filters_animation_delay'],
			'pagination_type' => $settings['pagination_type'],
			'pagination_max_pages' => $this->get_max_num_pages( $settings ),
		];

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

	// Slider Settings
	public function add_slider_settings( $settings ) {
		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

		if ( ! tmpcoder_is_availble() ) {
			$settings['layout_slider_autoplay'] = '';
			$settings['layout_slider_autoplay_duration'] = 0;
			$settings['layout_slider_pause_on_hover'] = '';
		}

		if ( 'pro-3' == $settings['layout_slider_amount'] || 'pro-4' == $settings['layout_slider_amount'] || 'pro-5' == $settings['layout_slider_amount'] || 'pro-6' == $settings['layout_slider_amount'] ) {
			$settings['layout_slider_amount'] = 2;
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
			'sliderRows' => isset($settings['layout_slider_rows']) ? $settings['layout_slider_rows'] : 1
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
			'download' => $settings['lightbox_popup_download']
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
		// Get Posts
		$posts = new \WP_Query( $this->get_main_query_args() );

		// Loop: Start
		if ( $posts->have_posts() ) :

		$post_index = 0;

		// Grid Settings
		if ( 'slider' !== $settings['layout_select'] ) {
			// Filters
			$this->render_grid_filters( $settings );

			$this->add_grid_settings( $settings );
			$render_attribute = $this->get_render_attribute_string( 'grid-settings' );

		// Slider Settings
		} else {
			$this->add_slider_settings( $settings );
			$render_attribute = $this->get_render_attribute_string( 'slider-settings' );
		}

		// Grid Wrap
		echo wp_kses_post('<section class="tmpcoder-grid tmpcoder-media-grid elementor-clearfix" '. $render_attribute .'>');

		while ( $posts->have_posts() ) : $posts->the_post();

			// $post_index++;
			// if ( !tmpcoder_is_availble() && $post_index > 12 ) {
			// 	return;
			// }

			if ( ! wp_attachment_is( 'image', get_the_ID() ) ) {
				continue;
			}

			// Post Class
			$post_class = implode( ' ', get_post_class( 'tmpcoder-grid-item elementor-clearfix', get_the_ID() ) );

			// Grid Item
			echo '<article class="'. esc_attr( $post_class ) .'">';

			// Inner Wrapper
			echo '<div class="tmpcoder-grid-item-inner">';

			// Content: Above Media
			$this->get_elements_by_location( 'above', $settings, get_the_ID() );

			// Media
			echo '<div class="tmpcoder-grid-media-wrap'. esc_attr($this->get_image_effect_class( $settings )) .' ">';

				if (isset($settings['overlay_post_link'])) {
					
					if( 'yes' === $settings['overlay_post_link'] ) {
						$open_links_in_new_tab = 'yes' === $this->get_settings()['open_links_in_new_tab'] ? '_blank' : '_self';
						echo '<a target="'. esc_attr($open_links_in_new_tab) .'" class="tmpcoder-grid-media-link" href="'. esc_url( get_the_permalink( get_the_ID() ) ) .'"></a>';
					}
				}

				// Post Thumbnail
				$this->render_post_thumbnail( $settings, get_the_ID() );

				// Media Hover
				echo '<div class="tmpcoder-grid-media-hover tmpcoder-animation-wrap">';
					// Media Overlay
					$this->render_media_overlay( $settings );

					// Content: Over Media
					$this->get_elements_by_location( 'over', $settings, get_the_ID() );

				echo '</div>';
			echo '</div>';

			// Content: Below Media
			$this->get_elements_by_location( 'below', $settings, get_the_ID() );

			echo '</div>'; // End .tmpcoder-grid-item-inner

			echo '</article>'; // End .tmpcoder-grid-item

		endwhile;

		// reset
		wp_reset_postdata();

		// Grid Wrap
		echo '</section>';

		if ( 'slider' === $settings['layout_select'] ) {
			// Slider Navigation (only when enabled)
			if ( $settings['layout_slider_nav'] === 'yes' ) {
				echo '<div class="tmpcoder-grid-slider-arrow-container">';
					$nav_icon = isset($settings['layout_slider_nav_icon']) ? $settings['layout_slider_nav_icon'] : 'svg-angle-1-left';
					echo '<div class="tmpcoder-grid-slider-prev-arrow tmpcoder-grid-slider-arrow" id="tmpcoder-grid-slider-prev-'. esc_attr($this->get_id()).'">'.wp_kses(tmpcoder_get_icon( $nav_icon, '' ), tmpcoder_wp_kses_allowed_html()) .'</div>'; 
					echo '<div class="tmpcoder-grid-slider-next-arrow tmpcoder-grid-slider-arrow" id="tmpcoder-grid-slider-next-'. esc_attr($this->get_id()).'">'.wp_kses(tmpcoder_get_icon( $nav_icon, '' ),tmpcoder_wp_kses_allowed_html()).'</div>'; 
				echo '</div>';
			}

			// Slider Dots
			echo '<div class="tmpcoder-grid-slider-dots"></div>';
		}

		// Pagination
		$this->render_grid_pagination( $settings );

		// No Posts Found
		else:

			echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';

		// Loop: End
		endif;
	}
}