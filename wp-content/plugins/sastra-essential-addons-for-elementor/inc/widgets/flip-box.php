<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Flip_Box extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-flip-box';
	}

	public function get_title() {
		return esc_html__( 'Flip Box', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-flip-box';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category' ];
	}

	public function get_keywords() {
		return [ 'hover box', 'banner box', 'animated banner' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-animations-css', 'tmpcoder-flip-box' ];
	}

	public function get_script_depends() {
		return [ 'tmpcoder-flip-box' ];
	}

	public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_front_trigger () {
		$this->add_control(
			'front_trigger',
			[
				'label' => esc_html__( 'Trigger', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'box' => esc_html__( 'Box', 'sastra-essential-addons-for-elementor' ),
					'hover' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
					'pro-bt' => esc_html__( 'Button (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'separator' => 'before',
			]
		);
	}

	public function add_control_back_link_type() {
		$this->add_control(
			'back_link_type',
			[
				'label' => esc_html__( 'Link Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'title' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
					'box' => esc_html__( 'Box', 'sastra-essential-addons-for-elementor' ),
					'pro-bt' => esc_html__( 'Button (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'box',
				'separator' => 'before',
			]
		);
	}

	public function add_control_box_animation() {
		$this->add_control(
			'box_animation',
			[
				'label' => esc_html__( 'Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'flip',
				'options' => [
		     		'fade'     => esc_html__( 'Fade', 'sastra-essential-addons-for-elementor' ),
					'flip'     => esc_html__( 'Flip', 'sastra-essential-addons-for-elementor' ),
		     		'pro-sl'    => esc_html__( 'Slide (Pro)', 'sastra-essential-addons-for-elementor' ),
		     		'pro-ps'     => esc_html__( 'Push (Pro)', 'sastra-essential-addons-for-elementor' ),
		     		'pro-zi'  => esc_html__( 'Zoom In (Pro)', 'sastra-essential-addons-for-elementor' ),
		     		'pro-zo' => esc_html__( 'Zoom Out (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-flip-box-animation-',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);
	}

	protected function register_controls() {
		
		// Section: Front ------------
		$this->start_controls_section(
			'tmpcoder__section_front',
			[
				'label' => esc_html__( 'Front', 'sastra-essential-addons-for-elementor' ),
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
            'front_icon_type',
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
			'front_image',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'front_image_size',
				'default' => 'full',
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'front_icon',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_title',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' =>  esc_html__( 'Frontend Content', 'sastra-essential-addons-for-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_description',
			[
				 'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Hover mouse here to see backend content. Lorem ipsum dolor sit amet.',
				'separator' => 'before',
			]
		);

		$this->add_control_front_trigger();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'flip-box', 'front_trigger', ['pro-bt'] );

		$this->add_control(
			'front_btn_text',
			[
				'label' => esc_html__( 'Frontend Button', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Click Me',
				'condition' => [
					'front_trigger' => 'btn',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'exclude_inline_options' => ['svg'],
				'label_block' => false,
				'condition' => [
					'front_trigger' => 'btn',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Back ------------
		$this->start_controls_section(
			'tmpcoder__section_back',
			[
				'label' => esc_html__( 'Back', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
            'back_icon_type',
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
			'back_image',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'back_image_size',
				'default' => 'full',
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'back_icon',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_title',
			[
				'label' => esc_html__( 'Backend Content', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Title',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_description',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'This is backend content. Lorem ipsum dolor sit amet.',
				'separator' => 'before',
			]
		);

		$this->add_control_back_link_type();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'flip-box', 'back_link_type', ['pro-bt'] );

		$this->add_control(
			'back_link',
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
					'back_link_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'back_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Backend Button',
				'separator' => 'before',
				'condition' => [
					'back_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'back_btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'exclude_inline_options' => 'svg',
				'label_block' => false,
				'condition' => [
					'back_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->start_controls_section(
			'tmpcoder__section_settings',
			[
				'label' => esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_responsive_control(
			'box_height',
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
					'size' => 350,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 700,
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
					'{{WRAPPER}} .tmpcoder-flip-box' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-flip-box-item' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-flip-box-overlay' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_box_animation();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'flip-box', 'box_animation',['pro-sl', 'pro-ps','pro-zi', 'pro-zo',] );

		$this->add_control(
			'box_anim_3d',
			[
				'label' => esc_html__( '3D Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'tmpcoder-flip-box-animation-3d-',
				'render_type' => 'template',
				'condition' => [
					'box_animation' => 'flip',
				],
			]
		);

		$this->add_control(
			'box_anim_direction',
			[
				'label' => esc_html__( 'Animation Direction', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
		     		'left'     => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
		     		'right'    => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
		     		'up'       => esc_html__( 'Top', 'sastra-essential-addons-for-elementor' ),
		     		'down'     => esc_html__( 'Bottom', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-flip-box-anim-direction-',
				'render_type' => 'template',
				'condition' => [
					'box_animation!' => [ 'fade', 'zoom-in', 'zoom-out', ],
				],
			]
		);

		$this->add_control(
			'box_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 10,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-item' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],				
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'box_anim_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => tmpcoder_animation_timings(),
				'default' => 'ease-default',
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'flip-box', 'box_anim_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'flip-box', [
			'Flip on Button Click',
			'Advanced Flipping Animations',
		] );

		// Styles
		// Section: Front ------------
		$this->start_controls_section(
			'tmpcoder__section_style_front',
			[
				'label' => esc_html__( 'Front', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#5729d9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-front',
			]
		);

		$this->add_control(
			'front_overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'front_bg_color_image[id]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'front_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_vr_position',
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
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-content' =>  '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_align',
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
				'prefix_class' => 'tmpcoder-flip-box-front-align-',
				'render_type' => 'template',
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'front_border',
				'label' => esc_html__( 'Border', 'sastra-essential-addons-for-elementor' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-front',
				'separator' => 'before',
			]
		);

		// Image
		$this->add_control(
			'front_image_section',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'front_image_width',
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
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'front_image_distance',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-image' => 'margin-bottom:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'front_image_border_radius',
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
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		// Icon
		$this->add_control(
			'front_icon_section',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_icon_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-icon svg' => 'width: {{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],	
			]
		);

		// Title
		$this->add_control(
			'front_title_section',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_title_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_title_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-title',
			]
		);

		$this->add_control(
			'front_title_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Description
		$this->add_control(
			'front_description_section',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_description_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_description_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-description',
			]
		);

		$this->add_control(
			'front_description_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Back ------------
		$this->start_controls_section(
			'tmpcoder__section_style_back',
			[
				'label' => esc_html__( 'Back', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#FF348B',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-back',
			]
		);

		$this->add_control(
			'back_overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'back_bg_color_image[id]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'back_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_vr_position',
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
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-content' =>  '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_align',
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
				'prefix_class' => 'tmpcoder-flip-box-back-align-',
				'render_type' => 'template',
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'back_border',
				'label' => esc_html__( 'Border', 'sastra-essential-addons-for-elementor' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-back',
				'separator' => 'before',
			]
		);

		// Image
		$this->add_control(
			'back_image_section',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'back_image_width',
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
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'back_image_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-image' => 'margin-bottom:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'back_image_border_radius',
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
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		// Icon
		$this->add_control(
			'back_icon_section',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_icon_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],	
			]
		);

		// Title
		$this->add_control(
			'back_title_section',
			[
				'label' => esc_html__( 'Backend Content', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_title_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_title_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-title',
			]
		);

		$this->add_control(
			'back_title_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Description
		$this->add_control(
			'back_description_section',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_description_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_description_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-description',
			]
		);

		$this->add_control(
			'back_description_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Styles
		// Section: Front Button -----
		$this->start_controls_section(
			'tmpcoder__section_style_front_btn',
			[
				'label' => esc_html__( 'Front Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'front_trigger' => 'btn',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_front_btn_colors' );

		$this->start_controls_tab(
			'tab_front_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn'
			]
		);

		$this->add_control(
			'front_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'front_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'front_btn_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_front_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn:hover, {{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn:before, {{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn:after',
			]
		);

		$this->add_control(
			'front_btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'front_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'front_btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'front_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_btn_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'front_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}  .tmpcoder-flip-box-front .tmpcoder-flip-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_btn_border_type',
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
					'{{WRAPPER}}  .tmpcoder-flip-box-front .tmpcoder-flip-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'front_btn_border_width',
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
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'front_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'front_btn_border_radius',
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
					'{{WRAPPER}} .tmpcoder-flip-box-front .tmpcoder-flip-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Back Button ------
		$this->start_controls_section(
			'tmpcoder__section_style_back_btn',
			[
				'label' => esc_html__( 'Back Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'back_link_type' => ['btn', 'btn-title']
				],
			]
		);

		$this->start_controls_tabs( 'tabs_back_btn_colors' );

		$this->start_controls_tab(
			'tab_back_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn'
			]
		);

		$this->add_control(
			'back_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'back_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'back_btn_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_back_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn:hover, {{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn:before, {{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn:after',
			]
		);

		$this->add_control(
			'back_btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'back_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'back_btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_control(
			'back_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_btn_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'back_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}  .tmpcoder-flip-box-back .tmpcoder-flip-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_btn_border_type',
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
					'{{WRAPPER}}  .tmpcoder-flip-box-back .tmpcoder-flip-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'back_btn_border_width',
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
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'back_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'back_btn_border_radius',
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
					'{{WRAPPER}} .tmpcoder-flip-box-back .tmpcoder-flip-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	protected function render() {

		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		// $front_image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['front_image']['id'], 'front_image_size', $settings );

		$settings[ 'front_image_size' ] = ['id' => $settings['front_image']['id'] ?? 0];
		$front_image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'front_image_size' );


		// $back_image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['back_image']['id'], 'back_image_size', $settings );

		$settings[ 'back_image_size' ] = ['id' => $settings['back_image']['id'] ?? 0];
		$back_image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'back_image_size' );

		$back_btn_element = 'div';
		$back_link = $settings['back_link']['url'] ?? '';

		if ( '' !== $back_link ) {

			$back_btn_element = 'a';

			$this->add_render_attribute( 'link_attribute', 'href', $settings['back_link']['url'] ?? '' );

			if ( $settings['back_link']['is_external'] ?? false ) {
				$this->add_render_attribute( 'link_attribute', 'target', '_blank' );
			}

			if ( $settings['back_link']['nofollow'] ?? false ) {
				$this->add_render_attribute( 'link_attribute', 'nofollow', '' );
			}
		}

		?>
			
		<div class="tmpcoder-flip-box" data-trigger="<?php echo esc_attr( $settings['front_trigger'] ?? '' ); ?>">
			
			<div class="tmpcoder-flip-box-item tmpcoder-flip-box-front tmpcoder-anim-timing-<?php echo esc_attr( $settings['box_anim_timing'] ?? '' ); ?>">

				<div class="tmpcoder-flip-box-overlay"></div>

				<div class="tmpcoder-flip-box-content">
					
					<?php
					ob_start();
			        \Elementor\Icons_Manager::render_icon( $settings['front_icon'] ?? '', [ 'aria-hidden' => 'true' ] );
			        $custom_icon = ob_get_clean();
			        $custom_icon_wrapper = !empty($settings['front_icon']) ? $custom_icon : '';
					?>

					<?php if ( 'icon' === ($settings['front_icon_type'] ?? '') && (!empty($settings['front_icon']['value']) && '' !== ($settings['front_icon']['value'] ?? '')) ) : ?>
					<div class="tmpcoder-flip-box-icon">
					
					<?php if (isset($settings['front_icon']['value']) && is_array($settings['front_icon']['value']) ) {

                        echo wp_kses($custom_icon_wrapper, tmpcoder_wp_kses_allowed_html());

						}else{ ?>

						<i class="<?php echo esc_attr( $settings['front_icon']['value'] ?? '' ); ?>"></i>

					<?php } ?>

					</div>
					<?php elseif ( 'image' === ($settings['front_icon_type'] ?? '') && $front_image_html ) : ?>
					<div class="tmpcoder-flip-box-image">
						<?php echo wp_kses_post($front_image_html); ?> 
					</div>
					<?php endif; ?>
					
					<?php if ( !empty($settings['front_title']) && '' !== ($settings['front_title'] ?? '') ) : ?>
						<h3 class="tmpcoder-flip-box-title"><?php echo wp_kses_post($settings['front_title'] ?? ''); ?></h3>
					<?php endif; ?>

					<?php if ( !empty($settings['front_description']) && '' !== ($settings['front_description'] ?? '') ) : ?>
						<div class="tmpcoder-flip-box-description"><?php echo wp_kses_post($settings['front_description'] ?? ''); ?></div>						
					<?php endif; ?>	

					<?php if ( 'btn' === ($settings['front_trigger'] ?? '') ) : ?>
						<div class="tmpcoder-flip-box-btn-wrap">
							<div class="tmpcoder-flip-box-btn">
								<?php if ( !empty($settings['front_btn_text']) && '' !== ($settings['front_btn_text'] ?? '') ) : ?>
								<span class="tmpcoder-flip-box-btn-text"><?php echo esc_html($settings['front_btn_text'] ?? ''); ?></span>		
								<?php endif; ?>

								<?php if ( !empty($settings['front_btn_icon']['value']) && '' !== ($settings['front_btn_icon']['value'] ?? '') ) : ?>
								<span class="tmpcoder-flip-box-btn-icon">
									<i class="<?php echo esc_attr( $settings['front_btn_icon']['value'] ?? '' ); ?>"></i>
								</span>
								<?php endif; ?>
							</div>	
						</div>						
					<?php endif; ?>	

				</div>
			</div>

			<div class="tmpcoder-flip-box-item tmpcoder-flip-box-back tmpcoder-anim-timing-<?php echo esc_attr( $settings['box_anim_timing'] ); ?>">

				<div class="tmpcoder-flip-box-overlay"></div>
				
				<div class="tmpcoder-flip-box-content">
					
					<?php if ( 'box' === $settings['back_link_type'] ): ?>
					<?php echo wp_kses_post('<a class="tmpcoder-flip-box-link" '. $this->get_render_attribute_string( 'link_attribute' ).' ></a>'); ?>
					<?php endif; ?>

					<?php
						ob_start();
				        \Elementor\Icons_Manager::render_icon( $settings['back_icon'], [ 'aria-hidden' => 'true' ] );
				        $custom_icon = ob_get_clean();
				        $custom_icon_wrapper = !empty($settings['back_icon']) ? $custom_icon : '';
					?>

					<?php if ( 'icon' === $settings['back_icon_type'] && (!empty($settings['back_icon']['value']) && '' !== $settings['back_icon']['value']) ) : ?>
					<div class="tmpcoder-flip-box-icon">

						<?php if ( $settings['back_icon']['value'] != '' && is_array($settings['back_icon']['value'])) {
							echo wp_kses($custom_icon_wrapper, tmpcoder_wp_kses_allowed_html());
						} else{ ?>

						<i class="<?php echo esc_attr( $settings['back_icon']['value'] ); ?>"></i>

						<?php } ?>

					</div>
					<?php elseif ( 'image' === $settings['back_icon_type'] && $back_image_html ) : ?>
						<div class="tmpcoder-flip-box-image">
							<?php echo wp_kses_post($back_image_html); ?> 
						</div>
					<?php endif; ?>
					
					<?php if ( !empty($settings['back_title']) && '' !== $settings['back_title'] ) : ?>
						<h3 class="tmpcoder-flip-box-title">
							<?php
							if ( 'title' === $settings['back_link_type'] || 'btn-title' === $settings['back_link_type']  ) {
								echo wp_kses_post('<a '. $this->get_render_attribute_string( 'link_attribute' ).'>');
							}

							echo wp_kses_post($settings['back_title']);
						
							if ( 'title' === $settings['back_link_type'] || 'btn-title' === $settings['back_link_type']  ) {
								echo '</a>';
							}
							?>
						</h3>
					<?php endif; ?>

					<?php if ( !empty($settings['back_description']) && '' !== $settings['back_description'] ) : ?>
						<div class="tmpcoder-flip-box-description"><?php echo wp_kses_post($settings['back_description']); ?></div>						
					<?php endif; ?>	

					<?php if ( 'btn' === $settings['back_link_type'] || 'btn-title' === $settings['back_link_type'] ) : ?>

						<div class="tmpcoder-flip-box-btn-wrap">
							<?php echo wp_kses_post('<'. esc_html($back_btn_element) .' class="tmpcoder-flip-box-btn" '. $this->get_render_attribute_string( 'link_attribute' ) .'>'); ?>

								<?php if ( !empty($settings['back_btn_text']) && '' !== $settings['back_btn_text'] ) : ?>
								<span class="tmpcoder-flip-box-btn-text"><?php echo esc_html($settings['back_btn_text']); ?></span>		
								<?php endif; ?>

								<?php if ( !empty($settings['back_btn_icon']['value']) && '' !== $settings['back_btn_icon']['value'] ) : ?>
								<span class="tmpcoder-flip-box-btn-icon">
									<i class="<?php echo esc_attr( $settings['back_btn_icon']['value'] ); ?>"></i>
								</span>
								<?php endif; ?>

							<?php echo '</'. esc_html($back_btn_element) .'>'; ?>
						</div>						
					<?php endif; ?>	

				</div>
			</div>
		</div>

		<?php

	}
}
