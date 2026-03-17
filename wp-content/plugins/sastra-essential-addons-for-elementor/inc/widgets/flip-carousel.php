<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Flip_Carousel extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-flip-carousel';
	}

	public function get_title() {
		return esc_html__( 'Flip Carousel', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-media-carousel';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'flip carousel', 'flip', 'carousel', 'flip slider' ];
	}

	public function get_script_depends() {
		return [ 'tmpcoder-flipster', 'tmpcoder-flip-carousel' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-flipster-css', 'tmpcoder-flip-carousel' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }
	
	public function add_controls_group_autoplay() {
		$this->add_control(
			'autoplay',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Autoplay %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control no-distance'
			]
		);
	}

    protected function register_controls() {

		$this->start_controls_section(
			'section_flip_carousel',
			[
				'label' => esc_html__( 'Slides', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

        $repeater = new Repeater();

        $repeater->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => TMPCODER_ADDONS_ASSETS_URL . 'images/flip-image.png',
				],
			]
		);

		$repeater->add_control(
			'slide_text',
			[
				'label' => esc_html__( 'Image Caption', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Image Caption',
				'description' => 'Show/Hide Image Caption from Settings tab.'
				// 'condition' => [
				// 	'enable_figcaption' => 'yes'
				// ]
			]
		);
		
		$repeater->add_control(
			'enable_slide_link',
			[
				'label' => __( 'Enable Slide Link', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$repeater->add_control(
			'slide_link',
			[
				'label' => __( 'Link', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'enable_slide_link' => 'yes'
				]
			]
		);
        
		$this->add_control(
			'carousel_elements',
			[
				'label' => esc_html__( 'Carousel Elements', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => esc_html__('title', 'sastra-essential-addons-for-elementor'),
						'image' => TMPCODER_ADDONS_ASSETS_URL . 'images/flip-image.png'
					],
					[
						'element_select' => esc_html__('title', 'sastra-essential-addons-for-elementor'),
						'image' => TMPCODER_ADDONS_ASSETS_URL . 'images/flip-image.png'
					],
					[
						'element_select' => esc_html__('title', 'sastra-essential-addons-for-elementor'),
						'image' => TMPCODER_ADDONS_ASSETS_URL . 'images/flip-image.png'
					],
				],
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'slider_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-flip-carousel-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=sastra-addon-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

        $this->end_controls_section();

		$this->start_controls_section(
			'section_flip_carousel_settings',
			[
				'label' => esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'flip_carousel_image_size',
				'default' => 'medium_large',
				// 'exclude' => ['custom']
			]
		);

		$this->add_control(
			'spacing',
			[
				'label' => __( 'Slide Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => -0.6,
				'min' => -1,
				'max' => 1,
				'step' => 0.1
			] 
		);

		$this->add_control(
			'carousel_type',
			[
				'label' => esc_html__( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'coverflow',
				'separator' => 'before',
				'options' => [
					'coverflow' => esc_html__( 'Cover Flow', 'sastra-essential-addons-for-elementor' ),
					'carousel' => esc_html__( 'Carousel', 'sastra-essential-addons-for-elementor' ),
					'flat' => esc_html__( 'Flat', 'sastra-essential-addons-for-elementor' )
				],
			]
		);

		$this->add_control(
			'starts_from_center',
			[
				'label' => __( 'Item Starts From Center', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_controls_group_autoplay();

		$this->add_control(
			'loop',
			[
				'label' => __( 'Loop', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'play_on_click',
			[
				'label' => __( 'Slide on Click', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'play_on_scroll',
			[
				'label' => __( 'Play on Scroll', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		
		$this->add_responsive_control(
			'show_navigation',
			[
				'label' => __( 'Show Navigation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'display:{{VALUE}} !important;',
				],
				'render_type' => 'template',
			]
		);

		$svg_array = tmpcoder_get_svg_icons_array( 'arrows', [] );

		$this->add_control(
			'flip_carousel_nav_icon',
			[
				'label' => esc_html__( 'Navigation Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-arrow-icons',
				// 'type' => Controls_Manager::SELECT,
				// 'options' => $svg_array, 
				'default' => 'fas fa-angle',
				'condition' => [
					'show_navigation' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Show Pagination', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'default' => ''
			]
		);
		
		$this->add_control(
			'pagination_position',
			[
				'label' => esc_html__( 'Pagination Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__( 'Above Image', 'sastra-essential-addons-for-elementor' ),
					'after' => esc_html__( 'Below Image', 'sastra-essential-addons-for-elementor' )
				],
				'render_type' => 'template',
				'prefix_class' => 'tmpcoder-flip-pagination-',
				'condition' => [
					'show_pagination' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'enable_figcaption',
			[
				'label' => __( 'Show Image Caption', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'flipcaption_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__( 'Above Image', 'sastra-essential-addons-for-elementor' ),
					'after' => esc_html__( 'Below Image', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'enable_figcaption' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );
		
		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'flip-carousel', [
			'Add Unlimited Slides',
			'Slider Autoplay options',
		] );

        $this->start_controls_section(
			'section_flip_carousel_navigation_styles',
			[
				'label' => esc_html__( 'Navigation', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_navigation' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
			'style_tabs_navigation'
		);

		$this->start_controls_tab(
			'navigation_style_normal_tab',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .flipster__button i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .flipster__button svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'navigation_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation',
				'label' => __( 'Box Shadow', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .flipster__button',
			]
		);

		$this->add_control(
			'navigation_transition',
			[
				'label' => esc_html__( 'Transition', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}} .flipster__button i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__button svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'navigation_style_hover_tab',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_control(
			'navigation_icon_color_hover',
			[
				'label'  => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipster__button:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .flipster__button:hover svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'navigation_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#423EC0',
				'selectors' => [
					'{{WRAPPER}} .flipster__button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'navigation_border_color_hover',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipster__button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation_hover',
				'label' => __( 'Box Shadow', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .flipster__button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'icon_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flipster__button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_responsive_control(
			'icon_bg_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',	
				]
			]
		);

		$this->add_control(
			'border',
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
					'{{WRAPPER}} button.flipster__button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'icon_border_width',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} button.flipster__button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				],
				'condition' => [
					'border!' => 'none'
				]
			]
		);
		
		$this->add_control(
			'icon_border_radius',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} button.flipster__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				]
			]
		);

		$this->end_controls_section();
				
        $this->start_controls_section(
			'section_flip_carousel_pagination_styles',
			[
				'label' => esc_html__( 'Pagination', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_pagination' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
			'style_tabs_pagination'
		);

		$this->start_controls_tab(
			'pagination_style_normal_tab',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item .flipster__nav__link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#8D8AE1',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DDDDDD',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_pagination',
				'label' => __( 'Box Shadow', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .flipster__nav__item',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_content_typography',
				'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .flipster__nav__link',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'pagination_transition',
			[
				'label' => esc_html__( 'Transition', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item .flipster__nav__link' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__nav__item' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__nav__item i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__nav__item svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'pagination_style_hover_tab',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_control(
			'pagination_color_hover',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DDDDDD',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item .flipster__nav__link:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .flipster__nav__item--current .flipster__nav__link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .flipster__nav__item--current' => 'background-color: {{VALUE}} !important',
				],
			]
		);
		
		$this->add_control(
			'pagination_border_color_hover',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DDDDDD',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_pagination_hover',
				'label' => __( 'Box Shadow', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .flipster__nav__item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'pagination_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flipster__nav__link::after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'show_pagination' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'pagination_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-carousel .flipster__nav__item' => 'margin: 0 {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'pagination_margin',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Distance', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-flip-pagination-after .tmpcoder-flip-carousel .flipster__nav' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-flip-pagination-before .tmpcoder-flip-carousel .flipster__nav' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flipster__button' => 'top: calc(50% - {{SIZE}}{{UNIT}});'
				],
			]
		);

		$this->add_control(
			'pagination_border',
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
					'{{WRAPPER}} .flipster__nav__item' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);
		 
		$this->add_control(
			'pagination_border_width',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}} .flipster__nav__link::after' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				],
				'condition' => [
					'pagination_border!' => 'none'
				],
			]
		);
		
		$this->add_control(
			'pagination_border_radius',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}} .flipster__nav__link::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				]
			]
		);

		$this->end_controls_section();
				
        $this->start_controls_section(
			'section_flip_carousel_caption_styles',
			[
				'label' => esc_html__( 'Caption', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_figcaption' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'caption_style_tabs' );

		$this->start_controls_tab(
			'caption_style_tabs_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
			
		$this->add_control(
			'caption_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .flipcaption' => 'color: {{VALUE}}',
				],
			]
		);
			
		$this->add_control(
			'caption_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .flipcaption' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography_caption',
				'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .flipcaption',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_style' => [
						'default' => 'normal'
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'caption_transition',
			[
				'label' => esc_html__( 'Transition', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .flipcaption' => '-webkit-transition: all {{VALUE}}s ease !important; transition: all {{VALUE}}s ease !important;',
				]
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'caption_style_tabs_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);
			
		$this->add_control(
			'caption_color_hover',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipcaption:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'flipcaption_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .flipcaption span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'flipcaption_alignment',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'separator' => 'before',
				'options' => [
					'left' => [
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
				],
                'selectors' => [
					'{{WRAPPER}} .flipcaption' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->end_controls_section();
    }

	public function flip_carousel_attributes($settings) {
		
		$icon_prev = '';
		$icon_next = '';

		// Navigation
		if (isset($settings['flip_carousel_nav_icon'])) {
			$icon_prev = '<span class="tmpcoder-flip-carousel-navigation">'. tmpcoder_get_icon( $settings['flip_carousel_nav_icon'], 'left' ) .'</span>';
		}

		if (isset($settings['flip_carousel_nav_icon'])) {
			$icon_next = '<span class="tmpcoder-flip-carousel-navigation">'. tmpcoder_get_icon( $settings['flip_carousel_nav_icon'], 'right' ) .'</span>';
		}

		if ( ! tmpcoder_is_availble() ) {
			$settings['autoplay'] = false;
			$settings['autoplay_milliseconds'] = 0;
			$settings['pause_on_hover'] = false;
		}

		$attributes = [
			'starts_from_center' => $settings['starts_from_center'],
			'carousel_type' => $settings['carousel_type'],
			'loop' => $settings['loop'],
			'autoplay' => $settings['autoplay'],
			'autoplay_milliseconds' => $settings['autoplay_milliseconds'],
			'pause_on_hover' => $settings['pause_on_hover'],
			'play_on_click' => $settings['play_on_click'],
			'play_on_scroll' => $settings['play_on_scroll'],
			'pagination_position' => $settings['pagination_position'],
			'spacing' => $settings['spacing'],
			'button_prev' => $icon_prev,
			'button_next' => $icon_next,
			'pagination_bg_color_hover' => isset($settings['pagination_bg_color_hover']) ? $settings['pagination_bg_color_hover'] : ''
		];

		return wp_json_encode($attributes);
	}

    protected function render() {

		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

        if ( $settings['carousel_elements'] ) {
			$i = 0;
			echo '<div class="tmpcoder-flip-carousel-wrapper">';
            echo '<div class="tmpcoder-flip-carousel" data-settings="'. esc_attr($this->flip_carousel_attributes($settings)) .'">';
            echo '<ul class="tmpcoder-flip-items-wrapper">';
            foreach ( $settings['carousel_elements'] as $key => $element ) {
				if ( ! tmpcoder_is_availble() && $key === 4 ) {
					break;
				}

				if ( ! empty( $element['slide_link']['url'] ) ) {
					$this->add_link_attributes( 'slide_link'. $i, $element['slide_link'] );
				}

				if ( Utils::get_placeholder_image_src() === $element['image']['url'] ) {
					$flip_slide_image = '<img src='. Utils::get_placeholder_image_src() .' />';
				} if (TMPCODER_ADDONS_ASSETS_URL . 'images/flip-image.png' === $element['image']['url']) {
					$flip_slide_image = '<img src="'. esc_url($element['image']['url']) .'" />';
				} else {
					$alt = isset($element['image']['alt']) ? $element['image']['alt'] : '';
					$settings[ 'flip_carousel_image_size' ] = ['id' => $element['image']['id']];
					$flip_slide_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'flip_carousel_image_size' );
				}

				if ( 'yes' === $settings['enable_figcaption'] ) {
					$figcaption = '<figcaption class="flipcaption"><span style="width: 100%;">'. esc_html($element['slide_text']) .'</span></figcaption>';
				} else {
					$figcaption = '';
				}

				$inner_figure = 'after' === $settings['flipcaption_position']
						? ''. $flip_slide_image . $figcaption .''
						: ''. $figcaption . $flip_slide_image .'';

				$figure = 'yes' === $element['enable_slide_link']
						? '<a '. $this->get_render_attribute_string( 'slide_link'. $i ) .'>' . wp_kses_post($inner_figure) . '</a>'
						: $inner_figure;

                echo '<li class="tmpcoder-flip-item" data-flip-title="">';
					echo '<figure>';
						echo wp_kses($figure, tmpcoder_wp_kses_allowed_html());
					echo '</figure>';
				echo '</li>';
				$i++;
            }
            echo '</ul>';
            echo '</div>';
			echo '</div>';
        }
    }
}