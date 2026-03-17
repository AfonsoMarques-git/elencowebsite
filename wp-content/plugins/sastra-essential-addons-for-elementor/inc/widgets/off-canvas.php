<?php
namespace TMPCODER\Widgets;
use Elementor;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Offcanvas extends Widget_Base {

	protected $nav_menu_index = 1;
	
	public function get_name() {
		return 'tmpcoder-offcanvas';
	}

	public function get_title() {
		return esc_html__( 'Off-Canvas Content', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-sidebar';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_header') ? [ 'tmpcoder-header-builder-widgets'] : ['tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'offcanvas', 'menu', 'nav', 'content', 'off canvas', 'sidebar', 'ofcanvas', 'popup' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-link-animations-css', 'tmpcoder-offcanvas' ];
	}

	public function get_script_depends() {
		return [ 'tmpcoder-offcanvas' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_offcanvas_position() {
		$this->add_control(
            'offcanvas_position',
            [
                'label'        => esc_html__('Position', 'sastra-essential-addons-for-elementor'), 
                'type'         => Controls_Manager::SELECT,
                'label_block'  => false,
                'default'      => 'right',
				'render_type' => 'template',
                'options'      => [
                    'right' => esc_html__('Right', 'sastra-essential-addons-for-elementor'),
                    'pro-lf'  => esc_html__('Left (Pro)', 'sastra-essential-addons-for-elementor'),
                    'pro-tp'   => esc_html__('Top (Pro)', 'sastra-essential-addons-for-elementor'),
                    'pro-btm'  => esc_html__('Bottom (Pro)', 'sastra-essential-addons-for-elementor'),
                    'pro-mdl'  => esc_html__('Middle (Pro)', 'sastra-essential-addons-for-elementor'),
                    'pro-rl'  => esc_html__('Relative (Pro)', 'sastra-essential-addons-for-elementor'),
				]
            ]
        );
	}

	public function add_responsive_control_offcanvas_box_width() {
		$this->add_responsive_control(
			'offcanvas_box_width',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Width %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SLIDER,
				'classes' => 'tmpcoder-pro-control',
				'size_units' => ['px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 3000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'condition' => [
					'offcanvas_position' => ['left', 'right', 'middle', 'relative']
				]
			]
		);
	}

	public function add_responsive_control_offcanvas_box_height() {
		$this->add_responsive_control(
			'offcanvas_box_height',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Height %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SLIDER,
				'classes' => 'tmpcoder-pro-control',
				'size_units' => ['px', '%', 'vh'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 3000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'vh',
					'size' => 30,
				],
				'condition' => [
					'offcanvas_position' => ['top', 'bottom', 'middle', 'relative']
				]
			]
		);
	}

	public function add_control_offcanvas_entrance_animation() {
		$this->add_control(
			'offcanvas_entrance_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'sastra-essential-addons-for-elementor' ),
					'pro-sl' => esc_html__( 'Slide (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-gr' => esc_html__( 'Grow (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-offcanvas-entrance-animation-'
			]
		);
	}

	public function add_control_offcanvas_entrance_type() {
		
	}

	public function add_control_offcanvas_animation_duration() {
		
	}

	public function add_control_offcanvas_open_by_default() {
		
	}

	public function add_control_offcanvas_reverse_header () {
		$this->add_control(
			'offcanvas_reverse_header',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Reverse Header %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'description' => esc_html__('Reverse Close Icon and Title Locations', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'classes' => 'tmpcoder-pro-control no-distance',
			]
		);
	}

	public function add_control_offcanvas_button_icon() {

		$this->add_control(
			'offcanvas_button_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-bars',
					'library' => 'fa-solid',
				],
				'condition' => [
					'offcanvas_show_button_icon' => 'yes'
				]
			]
		);
	}

	public function tmpcoder_offcanvas_template( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		$edit_link = '<span class="tmpcoder-template-edit-btn" data-permalink="'. get_permalink( $id ) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_tmpcoder_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_offcanvas_content',
			[
				'label' => 'Content',
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'offcanvas_template',
			[
				'label'	=> esc_html__( 'Select Template', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
			]
		);

		$this->add_control(
			'offcanvas_show_header_title',
			[
				'label' => esc_html__( 'Header Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'offcanvas_title', [
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Offcanvas', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'offcanvas_show_header_title' => 'yes'
				]
			]
		);

		$this->add_control_offcanvas_position();

		$this->add_responsive_control(
			'offcanvas_relative_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-wrap-relative' => 'top: calc(100% + {{SIZE}}px);',
				],
				'condition' => [
					'offcanvas_position' => 'relative'
				]
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'offcanvas', 'offcanvas_position', ['pro-lf', 'pro-tp', 'pro-btm', 'pro-mdl', 'pro-rl'] );

		$this->add_responsive_control_offcanvas_box_width();

		$this->add_responsive_control_offcanvas_box_height();

		$this->add_control_offcanvas_entrance_animation();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'offcanvas', 'offcanvas_entrance_animation', ['pro-sl', 'pro-gr'] );

		$this->add_control_offcanvas_entrance_type();

		$this->add_control_offcanvas_animation_duration();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'offcanvas', 'offcanvas_entrance_type', ['pro-ps'] );

		$this->add_control_offcanvas_open_by_default();

		$this->add_control(
			'offcanvas_button_heading',
			[
				'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_show_button_title',
			[
				'label' => esc_html__( 'Show Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'offcanvas_button_title', 
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Click Here', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'offcanvas_show_button_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'offcanvas_show_button_icon',
			[
				'label' => esc_html__( 'Show Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'default' => 'yes'
			]
		);

		$this->add_control_offcanvas_button_icon();

		// TMPCODER INFO -  hide if no text
		$this->add_responsive_control(
			'offcanvas_button_icon_distance',
			[
				'label' => esc_html__( 'Icon Distance', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger svg' => 'margin-right: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'offcanvas_show_button_icon' => 'yes',
					'offcanvas_show_button_title' => 'yes',
					'offcanvas_button_title!' => ''
				]
			]
		);

		$this->add_responsive_control(
            'offcanvas_button_alignment',
            [
                'label'        => esc_html__('Align', 'sastra-essential-addons-for-elementor'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'default'      => 'center',
				'render_type' => 'template',
                'options'      => [
                    'left' => [
                        'title' => esc_html__('left', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__('Center', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-h-align-right',
                    ],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-container' => 'text-align: {{VALUE}}'
				],
				'prefix_class' => 'tmpcoder-offcanvas-align-'
            ]
        );

        $this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'offcanvas', [
			'Advanced Positioning',
			'Advanced Entrance Animations',
			'Custom Width & Height',
			'Open Offcanvas by Default',
			'Trigger Button Icon Select',
			'Close Icon Positioning'
		] );

		// Tab: Style ==============
		// Section: Button ------------
		$this->start_controls_section(
			'section_style_offcanvas_button',
			[
				'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger svg' => 'fill: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-offcanvas-trigger',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-offcanvas-trigger',
			]
		);
		
		$this->add_responsive_control(
			'button_icon_size',
			[
				'label' => esc_html__( 'SVG Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger:hover svg' => 'fill: {{VALUE}}'

				],
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-offcanvas-trigger:hover',
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
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
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
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();
		
		// Tab: Style ==============
		// Section: Header ------------
		$this->start_controls_section(
			'section_style_offcanvas_header',
			[
				'label' => esc_html__( 'Header', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control_offcanvas_reverse_header();

		$this->add_responsive_control(
			'offcanvas_header_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-offcanvas-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_close_icon_heading',
			[
				'label' => esc_html__( 'Close Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_close_icon_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-close-offcanvas' => 'color: {{VALUE}};',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-close-offcanvas' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_close_icon_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#eb0000',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-close-offcanvas:hover' => 'color: {{VALUE}};',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-close-offcanvas:hover' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_responsive_control(
			'offcanvas_close_icon_font_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-close-offcanvas i' => 'font-size: {{SIZE}}{{UNIT}};',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-close-offcanvas i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-close-offcanvas svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-close-offcanvas svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'offcanvas_title_heading',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'offcanvas_show_header_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'offcanvas_title_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-title' => 'color: {{VALUE}};',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-offcanvas-title' => 'color: {{VALUE}};'
				],
				'condition' => [
					'offcanvas_show_header_title' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'offcanvas_title',
				'selector' => '{{WRAPPER}} .tmpcoder-offcanvas-title, .tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-offcanvas-title',
				'condition' => [
					'offcanvas_show_header_title' => 'yes'
				]
			]
		);

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: Box ------------
		$this->start_controls_section(
			'section_style_offcanvas_box',
			[
				'label' => esc_html__( 'Container', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'offcanvas_box_style',
			[
				'label' => esc_html__( 'Container', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'offcanvas_box_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-content' => 'background-color: {{VALUE}};',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-offcanvas-content' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_box_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-content' => 'border-color: {{VALUE}}',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-offcanvas-content' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'offcanvas_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-offcanvas-content, .tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-offcanvas-content',
				'fields_options' => [
					'box_shadow_type' =>
						[ 
							'default' =>'yes' 
						],
					'box_shadow' => [
						'default' =>
							[
								'horizontal' => 0,
								'vertical' => 0,
								'blur' => 5,
								'spread' => 0,
								'color' => 'rgba(0,0,0,0.1)'
							]
					]
				]
			]
		);

		$this->add_control(
			'offcanvas_box_border_style',
			[
				'label' => esc_html__( 'Border Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
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
					'{{WRAPPER}} .tmpcoder-offcanvas-content' => 'border-style: {{VALUE}};',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-offcanvas-content' => 'border-style: {{VALUE}};'
				]
			]
		);
	
		$this->add_responsive_control(
			'offcanvas_box_border_width',
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
					'{{WRAPPER}} .tmpcoder-offcanvas-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-offcanvas-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
				'condition' =>[
					'offcanvas_box_border_style!' => 'none',
				],
			]
		);
	
		$this->add_responsive_control(
				'offcanvas_box_border_radius',
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
						'{{WRAPPER}} .tmpcoder-offcanvas-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-offcanvas-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'separator' => 'after',
				]
		);

		$this->add_responsive_control(
			'offcanvas_box_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.tmpcoder-offcanvas-wrap-{{ID}} .tmpcoder-offcanvas-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_overlay_style',
			[
				'label' => esc_html__( 'Overlay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_overlay_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#07070733',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-wrap' => 'background-color: {{VALUE}};',
					'.tmpcoder-offcanvas-wrap-{{ID}}' => 'background-color: {{VALUE}};'
				],
				// 'condition' => [
				// 	'offcanvas_entrance_type!' => 'reveal'
				// ]
			]
		);

		$this->add_control(
			'offcanvas_scrollbar_heading',
			[
				'label' => esc_html__( 'Scrollbar', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_scrollbar_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-content::-webkit-scrollbar-thumb' => 'border-left-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-offcanvas-content::-webkit-scrollbar-thumb' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'scrollbar_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-offcanvas-content::-webkit-scrollbar-thumb' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-offcanvas-content::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + 3px);',
				]
			]
		);

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if ( ! tmpcoder_is_availble() ) {
			$settings['offcanvas_position'] = 'right';
			$settings['offcanvas_entrance_animation'] = 'fade';
		}

		$this->add_render_attribute(
			'offcanvas-wrapper',
			[
				'class' => [ 'tmpcoder-offcanvas-container' ],
				'data-offcanvas-open' => ! tmpcoder_is_availble() ? 'no' : $settings['offcanvas_open_by_default'],
			]
		);

		?>

		<?php echo wp_kses_post('<div '.$this->get_render_attribute_string( 'offcanvas-wrapper' ).'>'); ?>
			<button class="tmpcoder-offcanvas-trigger">
				<?php if ( 'yes' === $settings['offcanvas_show_button_icon'] && !empty($settings['offcanvas_button_icon']) ) : 
					\Elementor\Icons_Manager::render_icon( $settings['offcanvas_button_icon'] );
				endif; ?>
				<?php if ( 'yes' === $settings['offcanvas_show_button_title'] && !empty($settings['offcanvas_button_title']) ) : ?>
					<span><?php echo esc_html($settings['offcanvas_button_title']) ?></span>
				<?php endif; ?>
			</button>

			<div class="tmpcoder-offcanvas-wrap tmpcoder-offcanvas-wrap-<?php echo esc_html($settings['offcanvas_position']) ?>">
				<div class="tmpcoder-offcanvas-content tmpcoder-offcanvas-content-<?php echo esc_html($settings['offcanvas_position']) ?>">
					<div class="tmpcoder-offcanvas-header">
						<span class="tmpcoder-close-offcanvas">
							<i class="fa fa-times" aria-hidden="true"></i>
						</span>
						<?php if ( 'yes' === $settings['offcanvas_show_header_title'] && !empty($settings['offcanvas_title']) ) : ?>
							<span class="tmpcoder-offcanvas-title"><?php echo esc_html($settings['offcanvas_title']) ?></span>
						<?php endif; ?>
					</div>
					<?php
						if ( !empty($settings['offcanvas_template']) ) {
							echo wp_kses($this->tmpcoder_offcanvas_template($settings['offcanvas_template']), tmpcoder_wp_kses_allowed_html());
						} else {
							echo '<p>'. esc_html__('Please select a template!', 'sastra-essential-addons-for-elementor') .'</p>';
						}
					?>
				</div>
			</div>
		</div>
        
    <?php }
}