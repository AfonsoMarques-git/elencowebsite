<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Promo_Box extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-promo-box';
	}

	public function get_title() {
		return esc_html__( 'Promo Box', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-image';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category' ];
	}

	public function get_keywords() {
		return [ 'image hover', 'image effects', 'image box', 'promo box', 'banner box', 'animated banner', 'interactive banner' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-animations-css', 'tmpcoder-promo-box' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_image_style() {
		$this->add_control(
			'image_style',
			[
				'label' => esc_html__( 'Style', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => esc_html__( 'Cover', 'sastra-essential-addons-for-elementor' ),
					'pro-cs' => esc_html__( 'Classic (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-promo-box-style-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_border_animation() {
		$this->add_control(
			'border_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'oscar' => esc_html__( 'Oscar', 'sastra-essential-addons-for-elementor' ),
					'jazz' => esc_html__( 'Jazz', 'sastra-essential-addons-for-elementor' ),
					'pro-ll' => esc_html__( 'Layla (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-bb' => esc_html__( 'Bubba (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-rm' => esc_html__( 'Romeo (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-cc' => esc_html__( 'Chicho (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-ap' => esc_html__( 'Apollo (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'oscar',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);
	}

	public function add_control_image_position() {}

	public function add_control_image_min_width() {}

	public function add_control_image_min_height() {}

	public function add_control_content_bg_color() {}

	public function add_control_content_hover_bg_color() {}

	public function add_section_badge() {}

	public function add_section_style_badge() {}

	public function add_control_group_icon_animation_section() {}

	public function add_control_group_title_animation_section() {}

	public function add_control_group_description_animation_section() {}

	public function add_control_group_btn_animation_section() {}

	public function add_args_animation_timings() {
		return tmpcoder_animation_timings();
	}

	protected function register_controls() {
		
		// Section: Image ------------
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_image_style();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'promo-box', 'image_style', ['pro-cs']);

		$this->add_control_image_position();

		$this->add_control_image_min_width();

		$this->add_control_image_min_height();

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Content ----------
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'content_icon_type',
            [
                'label' => esc_html__( 'Select Icon Type', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
                    'icon' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
                    'image' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
                ],
            ]
        );

		$this->add_control(
			'content_image',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'content_image_size',
				'default' => 'full',
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'content_icon',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'content_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'content_title',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Banner Title',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'sastra-essential-addons-for-elementor' ),
					'h2' => esc_html__( 'H2', 'sastra-essential-addons-for-elementor' ),
					'h3' => esc_html__( 'H3', 'sastra-essential-addons-for-elementor' ),
					'h4' => esc_html__( 'H4', 'sastra-essential-addons-for-elementor' ),
					'h5' => esc_html__( 'H5', 'sastra-essential-addons-for-elementor' ),
					'h6' => esc_html__( 'H6', 'sastra-essential-addons-for-elementor' ),
					'div' => esc_html__( 'div', 'sastra-essential-addons-for-elementor' ),
					'span' => esc_html__( 'span', 'sastra-essential-addons-for-elementor' ),
					'p' => esc_html__( 'p', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'content_description',
			[
				 'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem Ipsum is simply dumy text of the printing typesetting industry lorem ipsum.',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_link_type',
			[
				'label' => esc_html__( 'Link Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'title' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
					'btn' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
					// 'btn-title' => esc_html__( 'Title & Button', 'sastra-essential-addons-for-elementor' ), TODO: add or remove?
					'box' => esc_html__( 'Box', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_link',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label' => esc_html__( 'Link', 'sastra-essential-addons-for-elementor' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'default' => [
					'url' => '#',
				],
				'separator' => 'before',
				'condition' => [
					'content_link_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Click here',
				'separator' => 'before',
				'condition' => [
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'content_btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Badge ---------
		$this->add_section_badge();

		// Section: Effects ----------
		$this->start_controls_section(
			'section_effectz',
			[
				'label' => esc_html__( 'Effects', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'hover_animation_section',
			[
				'label' => esc_html__( 'Hover Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control_border_animation();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'promo-box', 'border_animation', ['pro-ll','pro-bb','pro-rm','pro-cc','pro-ap',] );

		$this->add_control(
			'border_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay::after' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay::before' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'border_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay::after' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay::before' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);



		$this->add_control(
			'border_animation_section',
			[
				'label' => esc_html__( 'Hover Border Style', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'border_animation_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'default' => 'rgba(255,255,255,0.93)',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay::after' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-border-anim-apollo::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-border-anim-romeo::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-border-anim-romeo::after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'border_animation_type',
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
					'{{WRAPPER}} .tmpcoder-border-anim-layla::before' => 'border-top-style: {{VALUE}};border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-border-anim-layla::after' => 'border-left-style: {{VALUE}};border-right-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-border-anim-oscar::before' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-border-anim-bubba::before' => 'border-top-style: {{VALUE}};border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-border-anim-bubba::after' => 'border-left-style: {{VALUE}};border-right-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-border-anim-chicho::before' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-border-anim-jazz::after' => 'border-top-style: {{VALUE}};border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => [ 'none', 'apollo', 'romeo' ],
				],
			]
		);

		$this->add_control(
			'border_animation_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay::before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay::after' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-border-anim-romeo::before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-border-anim-romeo::after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => [ 'none', 'apollo' ],
				],
			]
		);

		$this->add_control(
			'border_animation_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-border-anim-layla::before' => 'top: calc({{SIZE}}{{UNIT}} + 20px);right: {{SIZE}}{{UNIT}};bottom: calc({{SIZE}}{{UNIT}} + 20px);left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-border-anim-layla::after' => 'top: {{SIZE}}{{UNIT}};right: calc({{SIZE}}{{UNIT}} + 20px);bottom: {{SIZE}}{{UNIT}};left: calc({{SIZE}}{{UNIT}} + 20px);',
					'{{WRAPPER}} .tmpcoder-border-anim-oscar::before' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-border-anim-bubba::before' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-border-anim-bubba::after' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-border-anim-chicho::before' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => [ 'none', 'apollo', 'romeo', 'jazz' ],
				],	
			]
		);

		$this->add_control(
			'hover_animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'image_animation_section',
			[
				'label' => esc_html__( 'Image Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'zoom-in' => esc_html__( 'Zoom In', 'sastra-essential-addons-for-elementor' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'sastra-essential-addons-for-elementor' ),
					'move-left' => esc_html__( 'Move Left', 'sastra-essential-addons-for-elementor' ),
					'move-right' => esc_html__( 'Move Right', 'sastra-essential-addons-for-elementor' ),
					'move-up' => esc_html__( 'Move Top', 'sastra-essential-addons-for-elementor' ),
					'move-down' => esc_html__( 'Move Bottom', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'zoom-in',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-bg-image' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-bg-image' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
					'image_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->add_args_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'image[url]!' => '',
					'image_animation!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'promo-box', 'image_animation_timing', tmpcoder_animation_timing_pro_conditions() );

		$this->add_control_group_icon_animation_section();

		$this->add_control_group_title_animation_section();

		$this->add_control_group_description_animation_section();

		$this->add_control_group_btn_animation_section();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'promo-box', [
			'Classic Layout - Image & Content Side to Side with Image Width & Position options',
			'Advanced Image Hover Animations',
			'Advanced Content Hover Animations - Icon, Title, Description, Button separately',
			'Advanced Badge (Ribon) options',
		] );
		
		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_content_colors' );

		$this->start_controls_tab(
			'tab_content_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_control_content_bg_color();

		$this->add_control(
			'content_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-promo-box-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_title_color',
			[
				'label' => esc_html__( 'Title Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-promo-box-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_description_color',
			[
				'label' => esc_html__( 'Description Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control_content_hover_bg_color();

		$this->add_control(
			'content_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_hover_title_color',
			[
				'label' => esc_html__( 'Title Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_hover_description_color',
			[
				'label' => esc_html__( 'Description Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'content_trans_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-content' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-promo-box-icon i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-promo-box-icon svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-promo-box-title span' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-promo-box-title a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-promo-box-description p' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_responsive_control(
			'content_min_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1000,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 280,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-content' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 30,
					'right' => 30,
					'bottom' => 30,
					'left' => 30,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
				],
			]
		);


		$this->add_control(
			'content_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'sastra-essential-addons-for-elementor' ),
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
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end'
				],
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-content' =>  '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
					'{{WRAPPER}} .tmpcoder-promo-box-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Image
		$this->add_control(
			'content_image_section',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'content_image_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-icon img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);


		// Icon
		$this->add_control(
			'content_icon_section',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'content_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'content_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 27,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-content .tmpcoder-promo-box-icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tmpcoder-promo-box-content .tmpcoder-promo-box-icon svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'content_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-content .tmpcoder-promo-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type!' => 'none',
				],	
			]
		);

		$this->add_control(
			'content_icon_border_radius',
			[
				'type' => Controls_Manager::SLIDER,
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
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-content .tmpcoder-promo-box-icon img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		// Title
		$this->add_control(
			'content_title_section',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_title_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-promo-box-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_title_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-promo-box-title',
			]
		);

		$this->add_responsive_control(
			'content_title_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Description
		$this->add_control(
			'content_description_section',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_description_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-promo-box-description',
			]
		);

		$this->add_responsive_control(
			'content_description_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button ------
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_link_type' => [ 'btn', 'btn-title' ],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_colors' );

		$this->start_controls_tab(
			'tab_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-promo-box-btn'
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-promo-box-btn svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-promo-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-btn',
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-btn svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-promo-box-btn svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-promo-box-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 8,
					'right' => 17,
					'bottom' => 8,
					'left' => 17,
				],
				'selectors' => [
					'{{WRAPPER}}  .tmpcoder-promo-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_type',
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
					'{{WRAPPER}}  .tmpcoder-promo-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius',
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
					'{{WRAPPER}} .tmpcoder-promo-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Badge -----------
		$this->add_section_style_badge();

		// Styles
		// Section: Overlay ----------
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Overlay', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_overlay_colors' );

		$this->start_controls_tab(
			'tab_overlay_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Overlay Color', 'sastra-essential-addons-for-elementor' ),
				'default' => 'rgba(112, 127, 239, 0.89)',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'bg_css_filters',
				'selector' => '{{WRAPPER}} .tmpcoder-promo-box-bg-image',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_overlay_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_hover_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Overlay Color', 'sastra-essential-addons-for-elementor' ),
				'default' => 'rgba(255, 52, 139, 0.65)',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-bg-overlay' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'bg_css_filters_hover',
				'selector' => '{{WRAPPER}} .tmpcoder-promo-box:hover .tmpcoder-promo-box-bg-image',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'overlay_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
					'multiply' => esc_html__( 'Multiply', 'sastra-essential-addons-for-elementor' ),
					'screen' => esc_html__( 'Screen', 'sastra-essential-addons-for-elementor' ),
					'overlay' => esc_html__( 'Overlay', 'sastra-essential-addons-for-elementor' ),
					'darken' => esc_html__( 'Darken', 'sastra-essential-addons-for-elementor' ),
					'lighten' => esc_html__( 'Lighten', 'sastra-essential-addons-for-elementor' ),
					'color-dodge' => esc_html__( 'Color-dodge', 'sastra-essential-addons-for-elementor' ),
					'color-burn' => esc_html__( 'Color-burn', 'sastra-essential-addons-for-elementor' ),
					'hard-light' => esc_html__( 'Hard-light', 'sastra-essential-addons-for-elementor' ),
					'soft-light' => esc_html__( 'Soft-light', 'sastra-essential-addons-for-elementor' ),
					'difference' => esc_html__( 'Difference', 'sastra-essential-addons-for-elementor' ),
					'exclusion' => esc_html__( 'Exclusion', 'sastra-essential-addons-for-elementor' ),
					'hue' => esc_html__( 'Hue', 'sastra-essential-addons-for-elementor' ),
					'saturation' => esc_html__( 'Saturation', 'sastra-essential-addons-for-elementor' ),
					'color' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
					'luminosity' => esc_html__( 'luminosity', 'sastra-essential-addons-for-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-promo-box-bg-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	public function render_pro_element_badge() {}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$settings_new = $this->get_settings_for_display();
		$settings = array_merge( $settings, $settings_new );

		$image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'] ?? 0, 'image_size', $settings );
		$content_image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['content_image']['id']??'', 'content_image_size', $settings );

		if ( ! $image_src ) {
			$image_src = $settings['image']['url'] ?? '';
		}

		if ( ! $content_image_src ) {
			$content_image_src = $settings['content_image']['url'] ?? '';
		}

		$content_btn_element = 'div';
		$content_link = $settings['content_link']['url'] ?? '';

		if ( '' !== $content_link ) {

			$content_btn_element = 'a';

			$this->add_render_attribute( 'link_attribute', 'href', $settings['content_link']['url'] ?? '' );

			if ( $settings['content_link']['is_external'] ?? false ) {
				$this->add_render_attribute( 'link_attribute', 'target', '_blank' );
			}

			if ( $settings['content_link']['nofollow'] ?? false ) {
				$this->add_render_attribute( 'link_attribute', 'nofollow', '' );
			}
		}

		// Animations
		if ( ! tmpcoder_is_availble() ) {
			$settings['title_animation'] = 'none';
			$settings['description_animation'] = 'none';
			$settings['btn_animation'] = 'none';
			$settings['icon_animation'] = 'none';

			if ( 'none' != $settings['border_animation'] && 'oscar' != $settings['border_animation'] && 'jazz' != $settings['border_animation'] ) {
				$settings['border_animation'] = 'oscar';
			}
		}

		$this->add_render_attribute( 'title_attribute', 'class', 'tmpcoder-promo-box-title' );
		if ( 'none' !== $settings['title_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' tmpcoder-anim-transparency' : '';
			$this->add_render_attribute( 'title_attribute', 'class', 'tmpcoder-anim-transparency tmpcoder-anim-size-medium tmpcoder-element-'. $settings['title_animation'] .' tmpcoder-anim-timing-'. $settings['title_animation_timing'] .' tmpcoder-anim-size-'. $settings['title_animation_size']. $anim_transparency );	
		}

		$this->add_render_attribute( 'description_attribute', 'class', 'tmpcoder-promo-box-description' );
		if ( 'none' !== $settings['description_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' tmpcoder-anim-transparency' : '';
			$this->add_render_attribute( 'description_attribute', 'class', 'tmpcoder-anim-transparency tmpcoder-anim-size-medium tmpcoder-element-'. $settings['description_animation'] .' tmpcoder-anim-timing-'. $settings['description_animation_timing'] .' tmpcoder-anim-size-'. $settings['description_animation_size']. $anim_transparency );	
		}

		$this->add_render_attribute( 'btn_attribute', 'class', 'tmpcoder-promo-box-btn-wrap' );
		if ( 'none' !== $settings['btn_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' tmpcoder-anim-transparency' : '';
			$this->add_render_attribute( 'btn_attribute', 'class', 'tmpcoder-anim-transparency tmpcoder-anim-size-medium tmpcoder-element-'. $settings['btn_animation'] .' tmpcoder-anim-timing-'. $settings['btn_animation_timing'] .' tmpcoder-anim-size-'. $settings['btn_animation_size']. $anim_transparency );	
		}

		$this->add_render_attribute( 'icon_attribute', 'class', 'tmpcoder-promo-box-icon' );
		if ( 'none' !== $settings['icon_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' tmpcoder-anim-transparency' : '';
			$this->add_render_attribute( 'icon_attribute', 'class', 'tmpcoder-anim-transparency tmpcoder-anim-size-medium tmpcoder-element-'. $settings['icon_animation'] .' tmpcoder-anim-timing-'. $settings['icon_animation_timing'] .' tmpcoder-anim-size-'. $settings['icon_animation_size']. $anim_transparency );	
		}

		?>

		<div class="tmpcoder-promo-box tmpcoder-animation-wrap">

			<?php if ( 'box' === $settings['content_link_type'] ): ?>
			<?php echo wp_kses_post('<a class="tmpcoder-promo-box-link" '. $this->get_render_attribute_string( 'link_attribute' ).'></a>'); ?>
			<?php endif; ?>
				
			<?php if ( $image_src ) : ?>
				<div class="tmpcoder-promo-box-image">
					<div class="tmpcoder-promo-box-bg-image tmpcoder-bg-anim-<?php echo esc_attr($settings['image_animation']); ?> tmpcoder-anim-timing-<?php echo esc_attr( $settings['image_animation_timing'] ); ?>" style="background-image:url(<?php echo esc_url( $image_src ); ?>);"></div>
					<div class="tmpcoder-promo-box-bg-overlay tmpcoder-border-anim-<?php echo esc_attr($settings['border_animation']); ?>"></div>
				</div>
			<?php endif; ?>
			
			<div class="tmpcoder-promo-box-content">

				<?php if ( 'none' !== $settings['content_icon_type'] ) : ?>
				<?php echo wp_kses_post('<div '. $this->get_render_attribute_string('icon_attribute').'>'); ?>
					<?php if ( 'icon' === $settings['content_icon_type'] && (!empty($settings['content_icon']['value']) && '' !== $settings['content_icon']['value']) ) : ?>

						<?php 

						if (is_array($settings['content_icon']['value'])) {
							
							echo wp_kses($this->render_th_icon($settings['content_icon']),tmpcoder_wp_kses_allowed_html());
						}
						else
						{
							?>
							<i class="<?php echo esc_attr( $settings['content_icon']['value'] ?? '' ); ?>"></i>
							<?php
						}

						?>

					<?php elseif ( 'image' === $settings['content_icon_type'] && $content_image_src ) : ?>
						<?php 
							$settings[ 'layout_image_crop' ] = ['id' => $settings['content_image']['id'] ?? 0];
							$content_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'layout_image_crop' );
							echo wp_kses_post($content_image);
						?>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php

				if ( !empty($settings['content_title']) && '' !== $settings['content_title'] ) {

					echo wp_kses_post('<'. esc_attr( tmpcoder_validate_html_tag($settings['content_title_tag']) ) .' '. $this->get_render_attribute_string( 'title_attribute' ) .'>');
					if ( 'title' === $settings['content_link_type'] || 'btn-title' === $settings['content_link_type']  ) {
						echo wp_kses_post('<a '. $this->get_render_attribute_string( 'link_attribute' ).'>');
					}

					echo '<span>'. wp_kses_post($settings['content_title']) .'</span>';
				
					if ( 'title' === $settings['content_link_type'] || 'btn-title' === $settings['content_link_type']  ) {
						echo '</a>';
					}

					echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['content_title_tag']) ) .'>';
				}

				?>

				<?php if ( !empty($settings['content_description']) && '' !== $settings['content_description'] ) : ?>
					<?php echo wp_kses_post('<div '. $this->get_render_attribute_string( 'description_attribute' ).'>'); ?>
						<?php echo wp_kses_post('<p>'. $settings['content_description'] .'</p>'); ?>	
					</div>						
				<?php endif; ?>

				<?php if ( 'btn' === $settings['content_link_type'] || 'btn-title' === $settings['content_link_type'] ) : ?>
					<?php echo wp_kses_post('<div '. $this->get_render_attribute_string( 'btn_attribute' ).'>'); ?>
                    <?php echo wp_kses_post('<'. esc_html($content_btn_element).' class="tmpcoder-promo-box-btn" '. $this->get_render_attribute_string( 'link_attribute' ).'>');?>

							<?php if ( !empty($settings['content_btn_text']) && '' !== $settings['content_btn_text'] ) : ?>
							<span class="tmpcoder-promo-box-btn-text"><?php echo esc_html($settings['content_btn_text']); ?></span>		
							<?php endif; ?>

							<?php if ( !empty($settings['content_btn_icon']['value']) && '' !== $settings['content_btn_icon']['value'] ) : ?>
							<span class="tmpcoder-promo-box-btn-icon">

								<?php

								if (is_array($settings['content_btn_icon']['value'])) {
									echo wp_kses($this->render_th_icon($settings['content_btn_icon']), tmpcoder_wp_kses_allowed_html());
								}
								else{
									?>
									<i class="<?php echo esc_attr( $settings['content_btn_icon']['value'] ); ?>"></i>
									<?php
								}
								?>
							</span>
							<?php endif; ?>
						</<?php echo esc_html($content_btn_element); ?>>
					</div>	
				<?php endif; ?>
			</div>

			<?php $this->render_pro_element_badge(); ?>
		</div>

		<?php
	}

	public function render_th_icon($item) {
		ob_start();
		\Elementor\Icons_Manager::render_icon($item, ['aria-hidden' => 'true']);
		return ob_get_clean();
	}
}