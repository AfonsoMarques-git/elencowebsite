<?php
namespace TMPCODER\Widgets;

use Elementor;
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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Advanced_Accordion extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-advanced-accordion';
	}

	public function get_title() {
		return esc_html__( 'Advanced Accordion', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-toggle';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'blog', 'advanced accordion' ];
	}

	public function get_script_depends() {
		return [ '' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-animations-css', 'tmpcoder-link-animations-css', 'tmpcoder-button-animations-css', 'tmpcoder-loading-animations-css', 'tmpcoder-lightgallery-css' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_repeater_args_accordion_content_type() {
		return  [
				'editor' => esc_html__( 'Text Editor', 'sastra-essential-addons-for-elementor' ),
				'pro-tm' => esc_html__( 'Elementor Template (Pro)', 'sastra-essential-addons-for-elementor' )
		];
	}

	public function add_control_show_acc_search() {
		$this->add_control(
			'show_acc_search',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Search %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}

	public function add_section_style_search_input() {}

	public function render_search_input( $settings ) {}

    protected function register_controls() {

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_accordion_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );
        
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'accordion_title', [
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Acc Item Title' , 'sastra-essential-addons-for-elementor' ),
			]
		);
 
		$repeater->add_control(
			'accordion_content_type',
			[
				'label' => esc_html__( 'Content Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'editor',
				'options' => $this->add_repeater_args_accordion_content_type(),
				'render_type' => 'template',
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'advanced-accordion', 'accordion_content_type', ['pro-tm'] );

		$repeater->add_control(
			'accordion_content_template',
			[
				'label'	=> esc_html__( 'Select Template', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition' => [
					'accordion_content_type' => 'template',
				],
			]
		);

		$repeater->add_control(
			'accordion_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'sastra-essential-addons-for-elementor' ),
				'default' => 'Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis.',
				'condition' => [
                    'accordion_content_type!' => 'template'
				]
			]
		);

		$repeater->add_control(
			'accordion_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
				'default' => [
					'value' => 'far fa-edit',
					'library' => 'regular'
				]
			]
		);

		$this->add_control(
			'advanced_accordion',
			[
				'label' => esc_html__( 'Accordion Items', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'accordion_title' => esc_html__( 'Title #1', 'sastra-essential-addons-for-elementor' ),
						'accordion_icon' => [
							'value' => 'fas fa-desktop',
							'library' => 'solid'
						],
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'sastra-essential-addons-for-elementor' ),
					],
					[
						'accordion_title' => esc_html__( 'Title #2', 'sastra-essential-addons-for-elementor' ),
						'accordion_icon' => [
							'value' => 'fab fa-telegram-plane',
							'library' => 'brands'
						],
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'sastra-essential-addons-for-elementor' ),
					],
					[
						'accordion_title' => esc_html__( 'Title #3', 'sastra-essential-addons-for-elementor' ),
						'accordion_icon' => [
							'value' => 'fas fa-layer-group',
							'library' => 'solid'
						],
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'sastra-essential-addons-for-elementor' ),
					]
				],
				'title_field' => '{{{ accordion_title }}}',
			]
		);

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_accordion_settings',
			[
				'label' => esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'accordion_type',
            [
                'label'       => esc_html__('Accordion Type', 'sastra-essential-addons-for-elementor'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'accordion',
                'label_block' => false,
                'options'     => [
                    'accordion' => esc_html__('Accordion', 'sastra-essential-addons-for-elementor'),
                    'toggle'    => esc_html__('Toggle', 'sastra-essential-addons-for-elementor'),
                ]
            ]
        );

        $this->add_control(
            'accordion_trigger',
            [
                'label'       => esc_html__('Trigger', 'sastra-essential-addons-for-elementor'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'click',
                'label_block' => false,
                'options'     => [
                    'click' => esc_html__('Click', 'sastra-essential-addons-for-elementor'),
                    'hover'    => esc_html__('Hover', 'sastra-essential-addons-for-elementor'),
                ],
				'condition' => [
					'accordion_type' => 'accordion'
				]
            ]
        );

		$this->add_control(
			'accordion_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'span' => esc_html__( 'Span', 'sastra-essential-addons-for-elementor' ),
					'h1' => esc_html__( 'H1', 'sastra-essential-addons-for-elementor' ),
					'h2' => esc_html__( 'H2', 'sastra-essential-addons-for-elementor' ),
					'h3' => esc_html__( 'H3', 'sastra-essential-addons-for-elementor' ),
					'h4' => esc_html__( 'H4', 'sastra-essential-addons-for-elementor' ),
					'h5' => esc_html__( 'H5', 'sastra-essential-addons-for-elementor' ),
					'h6' => esc_html__( 'H6', 'sastra-essential-addons-for-elementor' )
				],
				'default' => 'span',
			]
		);

		$this->add_control(
			'interaction_speed',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'step' => 0.1,
				'min' => 0,
				'max' => 2,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'active_item',
			[
				'label' => esc_html__( 'Active Item Index', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => 1,
				'min' => 0,
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before'
			]
		);

		$this->add_control_show_acc_search();

		$this->add_control(
			'acc_search_icon',
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
					'show_acc_search' => 'yes',
				],
			]
		);

		$this->add_control(
			'acc_search_placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Search...', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'show_acc_search' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Content ==========
		// Section: Icons ---------
		$this->start_controls_section(
			'section_icon_settings',
			[
				'label' => esc_html__( 'Icons', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'change_icons_position',
            [
                'label'       => esc_html__('Position', 'sastra-essential-addons-for-elementor'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'label_block' => false,
                'options'     => [
                    'default' => esc_html__('Default', 'sastra-essential-addons-for-elementor'),
                    'reverse'    => esc_html__('Reverse', 'sastra-essential-addons-for-elementor'),
                ]
            ]
        );
 
		$this->add_control(
			'accordion_title_icon_box_style',
			[
				'label' => esc_html__( 'Box Style', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'side-box',
				'options' => [
					'no-box' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'side-box' => esc_html__( 'Side Box', 'sastra-essential-addons-for-elementor' ),
					'side-curve' => esc_html__( 'Side Curve', 'sastra-essential-addons-for-elementor' )
				],
				'prefix_class' => 'tmpcoder-advanced-accordion-icon-',
				'render_type' => 'template'
			]
		);
		
		$this->add_control(
			'accordion_title_icon_box_width',
			[
				'label' => esc_html__( 'Box Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 70,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-acc-icon-box' => 'width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);
		
		$this->add_responsive_control(
			'accordion_title_icon_after_box_width',
			[
				'label' => esc_html__( 'Triangle Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 30,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_color.VALUE}};',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button:hover .tmpcoder-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_hover_color.VALUE}};',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active .tmpcoder-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_active_color.VALUE}};',
				],
				'condition' => [
					'accordion_title_icon_box_style' => 'side-curve'
				]
			]
		);

		$this->add_control(
			'toggle_icon',
			[
				'label' => esc_html__( 'Select Toggle Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_active',
			[
				'label' => esc_html__( 'Select Toggle Icon Active', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-minus',
					'library' => 'fa-solid',
				]
			]
		);

		$this->add_control(
			'toggle_icon_rotation',
			[
				'label' => esc_html__( 'Active Icon Rotation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 0,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-active .tmpcoder-toggle-icon i' => 'transform: rotate({{SIZE}}deg); transform-origin: center;',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-active .tmpcoder-toggle-icon svg' => 'transform: rotate({{SIZE}}deg); transform-origin: center;'
				]
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'advanced-accordion', [
			__('Load Elementor Template in Accordion Panels.', 'sastra-essential-addons-for-elementor'),
			__('Enable Accordion content Live Search.', 'sastra-essential-addons-for-elementor'),
		] );

		$this->add_section_style_search_input();

		// Tab: Styles ===============
		// Section: Title ---------
		$this->start_controls_section(
			'section_style_switcher',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tab_style' );

		$this->start_controls_tab(
			'tab_normal_style',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' )
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-title-text' => 'color: {{VALUE}}'
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tab_bg_color',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#FFFFFF',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button'
			]
		);

		$this->add_control(
			'tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EAEAEA',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button, {{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-acc-title-text',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'     => [
						'default' => '400',
					]
				]
			]
		);

		$this->add_control(
			'accordion_transition',
			[
				'label' => esc_html__( 'Transition', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion button.tmpcoder-acc-button' => 'transition: all {{VALUE}}s ease-in-out;',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-title-text' => 'transition: all {{VALUE}}s ease-in-out;',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover_style',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' )
			]
		);

		$this->add_control(
			'tab_hover_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button:hover .tmpcoder-acc-title-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-title-text:hover' => 'color: {{VALUE}}'
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tab_hover_bg_color',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button:hover'
			]
		);

		$this->add_control(
			'tab_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active_style',
			[
				'label' => esc_html__( 'Active', 'sastra-essential-addons-for-elementor' )
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active .tmpcoder-acc-title-text' => 'color: {{VALUE}}'
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tab_active_bg_color',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active'
			]
		);

		$this->add_control(
			'tab_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_active_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tab_gutter',
			[
				'label' => esc_html__( 'Vertical Gutter', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_title_distance',
			[
				'label' => esc_html__( 'Title Left Distance', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-advanced-accordion-icon-no-box .tmpcoder-acc-item-title .tmpcoder-acc-title-text' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-advanced-accordion-icon-side-box .tmpcoder-acc-item-title .tmpcoder-acc-title-text' => 'margin-left: calc({{accordion_title_icon_box_width.SIZE}}{{accordion_title_icon_box_width.UNIT}} + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}}.tmpcoder-advanced-accordion-icon-side-curve .tmpcoder-acc-item-title .tmpcoder-acc-title-text' => 'margin-left: calc({{accordion_title_icon_box_width.SIZE}}{{accordion_title_icon_box_width.UNIT}} + {{accordion_title_icon_after_box_width.SIZE}}{{accordion_title_icon_after_box_width.UNIT}} + {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'tab_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 18,
					'right' => 18,
					'bottom' => 18,
					'left' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_border_type',
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
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_border_width',
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
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tab_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tab_border_radius',
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
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Icon ----------
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icons', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs( 'tab_style_icon' );

		$this->start_controls_tab(
			'tab_icon_normal_style',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' )
			]
		);

		$this->add_control(
			'tab_main_icon_color',
			[
				'label' => esc_html__( 'Main Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EDEDED',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-title-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-title-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_toggle_icon_color',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-toggle-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-toggle-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_box_color',
			[
				'label' => esc_html__( 'Icon Box Bg Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-acc-icon-box' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);

		$this->add_control(
			'accordion_icon_transition',
			[
				'label' => esc_html__( 'Transition', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-toggle-icon i' => 'transition: all {{VALUE}}s ease-in-out;',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-title-icon i' => 'transition: all {{VALUE}}s ease-in-out;',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-toggle-icon svg' => 'transition: all {{VALUE}}s ease-in-out;',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-title-icon svg' => 'transition: all {{VALUE}}s ease-in-out;',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover_style',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' )
			]
		);

		$this->add_control(
			'tab_main_hover_icon_color',
			[
				'label' => esc_html__( 'Main Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button:hover .tmpcoder-title-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button:hover .tmpcoder-title-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_toggle_hover_icon_color',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button:hover .tmpcoder-toggle-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button:hover .tmpcoder-toggle-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_box_hover_color',
			[
				'label' => esc_html__( 'Icon Box Bg Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button:hover .tmpcoder-acc-icon-box' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_active_style',
			[
				'label' => esc_html__( 'Active', 'sastra-essential-addons-for-elementor' )
			]
		);

		$this->add_control(
			'tab_main_active_icon_color',
			[
				'label' => esc_html__( 'Main Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active .tmpcoder-title-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active .tmpcoder-title-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_toggle_active_icon_color',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active .tmpcoder-toggle-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active .tmpcoder-toggle-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_box_active_color',
			[
				'label' => esc_html__( 'Icon Box Bg Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button.tmpcoder-acc-active .tmpcoder-acc-icon-box' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tab_main_icon_size',
			[
				'label' => esc_html__( 'Main Icon Size', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-title-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-title-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_toggle_icon_size',
			[
				'label' => esc_html__( 'Toggle Icon Size', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-toggle-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-button .tmpcoder-toggle-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'icon_box_border_radius',
			[
				'label' => esc_html__( 'Icon Box Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-advanced-accordion-icon-side-box .tmpcoder-advanced-accordion .tmpcoder-acc-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-advanced-accordion-icon-side-curve .tmpcoder-advanced-accordion .tmpcoder-acc-icon-box' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'accordion_title_icon_box_style!' => 'no-box'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section 

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-panel .tmpcoder-acc-panel-content' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-panel' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-panel' => 'border-color: {{VALUE}}',
				],
				// 'condition' => [
				// 	'content_border_type!' => 'none',
				// ],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-panel',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-panel .tmpcoder-acc-panel-content',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 25,
					'right' => 25,
					'bottom' => 25,
					'left' => 25,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_type',
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
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-panel' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-panel' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
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
					'{{WRAPPER}} .tmpcoder-advanced-accordion .tmpcoder-acc-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section
    }

	public function tmpcoder_accordion_template( $id ) {
		if ( empty( $id ) ) {
		return '';
		}

		$edit_link = '<span class="tmpcoder-template-edit-btn" data-permalink="'. get_permalink( $id ) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_tmpcoder_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	public function render_first_icon($settings, $acc) {
		if ( $settings['change_icons_position'] == 'reverse' ) :
			if (!empty($settings['toggle_icon'])) : ?>
				<span class="tmpcoder-toggle-icon tmpcoder-ti-close"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
				<span class="tmpcoder-toggle-icon tmpcoder-ti-open"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon_active'], [ 'aria-hidden' => 'true' ] ); ?></span>
			<?php endif ;
		else :
			if (!empty($acc['accordion_icon'])) : ?>
				<span class="tmpcoder-title-icon">
					<?php \Elementor\Icons_Manager::render_icon( $acc['accordion_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php	endif ;
		endif;
	}

	public function render_second_icon($settings, $acc) {
		if ( $settings['change_icons_position'] == 'reverse' ) :
			if (!empty($acc['accordion_icon'])) : ?>
				<span class="tmpcoder-title-icon">
					<?php \Elementor\Icons_Manager::render_icon( $acc['accordion_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php	endif ;
		else :
			if (!empty($settings['toggle_icon'])) : ?>
				<span class="tmpcoder-toggle-icon tmpcoder-ti-close"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
				<span class="tmpcoder-toggle-icon tmpcoder-ti-open"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon_active'], [ 'aria-hidden' => 'true' ] ); ?></span>
			<?php endif ;
		endif;
	}

    protected function render() {
        $settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'accordion_attributes',
			[
				'class' => [ 'tmpcoder-advanced-accordion' ],
				'data-accordion-type' => $settings['accordion_type'],
				'data-active-index' => $settings['active_item'],
				'data-accordion-trigger' => isset($settings['accordion_trigger']) ? $settings['accordion_trigger'] : 'click',
				'data-interaction-speed' => isset($settings['interaction_speed']) ? $settings['interaction_speed'] : 0.4
			]
		);

		if ( 'yes' === $settings['show_acc_search'] ) {

			$this->add_render_attribute(
				'input', [
					'placeholder' => $settings['acc_search_placeholder'],
					'class' => 'tmpcoder-acc-search-input',
					'type' => 'search',
					'title' => esc_html__( 'Search', 'sastra-essential-addons-for-elementor' ),
				]
			);

		} ?>

            <?php echo wp_kses('<div '. $this->get_render_attribute_string( 'accordion_attributes' ). ' >', array(
                'div' => array(
                    'class' => array(),
                    'data-accordion-type' => array(),
                    'data-active-index' => array(),
                    'data-accordion-trigger' => array(),
                    'data-interaction-speed' => array(),
                )
            ));
            ?>

			<?php $this->render_search_input( $settings ) ?>

                <?php 
					foreach ($settings['advanced_accordion'] as $key=>$acc) :

						$acc_content_type = $acc['accordion_content_type'];
					
					if ( ! tmpcoder_is_availble() ) {
						$acc_content_type = 'editor';
						// if ( $key === 3 ) {
						// 	break;
						// }
					}
				?>

					<div class="tmpcoder-accordion-item-wrap">
						<button class="tmpcoder-acc-button">
							<span class="tmpcoder-acc-item-title">
								<?php if ('side-box' === $settings['accordion_title_icon_box_style']) : ?>
									<div class="tmpcoder-acc-icon-box">
										<?php $this->render_first_icon($settings, $acc); ?>
									</div>
								<?php elseif ('side-curve' === $settings['accordion_title_icon_box_style']) : ?>
									<div class="tmpcoder-acc-icon-box">
										<?php $this->render_first_icon($settings, $acc); ?>
										<div class="tmpcoder-acc-icon-box-after"></div>
									</div>
								<?php else :
									$this->render_first_icon($settings, $acc); 
								endif ; ?>

								<<?php echo esc_html($settings['accordion_title_tag']) ?> class="tmpcoder-acc-title-text"><?php echo esc_html($acc['accordion_title']) ?></<?php echo esc_html($settings['accordion_title_tag']); ?>>
							</span>
							<?php $this->render_second_icon($settings, $acc); ?>
						</button>

						<div class="tmpcoder-acc-panel">
							<?php if ('editor' === $acc_content_type) : ?>
								<div class="tmpcoder-acc-panel-content"><?php echo wp_kses_post($acc['accordion_content']); ?></div>
							<?php else: 
								echo wp_kses($this->tmpcoder_accordion_template( $acc['accordion_content_template'] ), tmpcoder_wp_kses_allowed_html());
							endif; ?>
						</div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php
    }
}