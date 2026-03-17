<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Before_After extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-before-after';
	}

	public function get_title() {
		return esc_html__( 'Before After', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-image-before-after';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category' ];
	}

	public function get_keywords() {
		return [ 'image compare', 'image comparison', 'before after image' ];
	}

	public function get_script_depends() {
		return [ 'jquery-event-move', 'tmpcoder-before-after' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-before-after' ];
	}

    public function get_custom_help_url() {
    	return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_direction() {
		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'sastra-essential-addons-for-elementor' ),
					'pro-vr' => esc_html__( 'Vertical (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'horizontal',
				'separator' => 'before',
			]
		);
	}

	public function add_control_trigger() {
		$this->add_control(
			'trigger',
			[
				'label' => esc_html__( 'Trigger', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'drag' => esc_html__( 'Click & Drag', 'sastra-essential-addons-for-elementor' ),
					'pro-ms' => esc_html__( 'Mouse Hover (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'drag',
			]
		);
	}

	public function add_control_divider_position() {}

	public function add_control_label_display() {
		$this->add_control(
			'label_display',
			[
				'label' => esc_html__( 'Display', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'default' => esc_html__( 'by Default', 'sastra-essential-addons-for-elementor' ),
					'pro-hv' => esc_html__( 'on Hover (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'default',
			]
		);
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'image_upload_1',
			[
				'label' => esc_html__( 'Upload Image 1', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => TMPCODER_ADDONS_ASSETS_URL.'images/before.jpg' ,
				],
			]
		);

		$this->add_control(
			'image_upload_2',
			[
				'label' => esc_html__( 'Upload Image 2', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => TMPCODER_ADDONS_ASSETS_URL.'images/after.jpg' ,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
			]
		);

		$this->add_control_direction();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'before-after', 'direction', ['pro-vr'] );

		$this->add_control_trigger();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'before-after', 'trigger', ['pro-ms'] );

		$this->add_control(
			'divider_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fa-angle',
				'options' => [
					'fa-caret' => esc_html__( 'Caret', 'sastra-essential-addons-for-elementor' ),
					'fa-angle' => esc_html__( 'Angle', 'sastra-essential-addons-for-elementor' ),
					'fa-arrow' => esc_html__( 'Arrow', 'sastra-essential-addons-for-elementor' ),
					'fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'sastra-essential-addons-for-elementor' ),
					'fa-chevron' => esc_html__( 'Chevron', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$this->add_control_divider_position();

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Labels -----------
		$this->start_controls_section(
			'section_labels',
			[
				'label' => esc_html__( 'Labels', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_label_display();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'before-after', 'label_display', ['pro-hv'] );

		$this->add_control(
			'label_image_1',
			[
				'label' => esc_html__( 'Image 1 Label', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'After',
				'placeholder'=> esc_html__( 'After', 'sastra-essential-addons-for-elementor' ),
				'separator' => 'before',
				'condition' => [
					'label_display!' => 'none',
				]
			]
		);

		$this->add_control(
			'label_image_2',
			[
				'label' => esc_html__( 'Image 2 Label', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Before',
				'placeholder'=> esc_html__( 'Before', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'label_display!' => 'none',
				]
			]
		);

		$this->add_control(
			'label_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start'    => [
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-end',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-label' => 'align-items: {{VALUE}}; justify-content: {{VALUE}}',
				],
				'condition' => [
					'label_display!' => 'none',
				]
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Divider ----------
		$this->start_controls_section(
			'section_style_divider',
			[
				'label' => esc_html__( 'Divider', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'divider_line_color',
			[
				'label'  => esc_html__( 'Line Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-divider-icons:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ba-divider-icons:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_icon_color',
			[
				'label'  => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-divider-icons .fa' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ba-divider-icons .fa' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_icon_color_bg',
			[
				'label'  => esc_html__( 'Icon Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-divider-icons' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_icon_color_border',
			[
				'label'  => esc_html__( 'Icon Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-divider-icons' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_thickness',
			[
				'label' => esc_html__( 'Line Thickness', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-horizontal .tmpcoder-ba-divider-icons:before' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ba-horizontal .tmpcoder-ba-divider-icons:after' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider-icons:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider-icons:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'divider_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-divider-icons .fa' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'divider_icon_width_hr',
			[
				'label' => esc_html__( 'Icon Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-horizontal .tmpcoder-ba-divider-icons .fa' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ba-horizontal .tmpcoder-ba-divider' => 'margin-left: calc(-{{SIZE}}{{UNIT}} - {{divider_icon_bdw.SIZE}}px);',
					'{{WRAPPER}} .tmpcoder-ba-horizontal .tmpcoder-ba-divider-icons:before' => 'left: calc({{SIZE}}{{UNIT}} - {{divider_thickness.SIZE}}px / 2 + {{divider_icon_bdw.SIZE}}px);',
					'{{WRAPPER}} .tmpcoder-ba-horizontal .tmpcoder-ba-divider-icons:after' => 'left: calc({{SIZE}}{{UNIT}} - {{divider_thickness.SIZE}}px / 2 + {{divider_icon_bdw.SIZE}}px);',
				],
				'condition' => [
					'direction' => ['horizontal', 'pro-vr']
				]
			]
		);

		$this->add_responsive_control(
			'divider_icon_height_hr',
			[
				'label' => esc_html__( 'Icon Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-horizontal .tmpcoder-ba-divider-icons .fa' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ba-horizontal .tmpcoder-ba-divider-icons:before' => 'bottom: calc(50% + {{divider_icon_bdw.SIZE}}px + {{SIZE}}{{UNIT}} / 2 - 0.7px);',
					'{{WRAPPER}} .tmpcoder-ba-horizontal .tmpcoder-ba-divider-icons:after' => 'top: calc(50% + {{divider_icon_bdw.SIZE}}px + {{SIZE}}{{UNIT}} / 2 + 0.1px);',
				],
				'separator' => 'after',
				'condition' => [
					'direction' => ['horizontal', 'pro-vr']
				]
			]
		);

		$this->add_responsive_control(
			'divider_icon_width_vr',
			[
				'label' => esc_html__( 'Icon Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider-icons .fa' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider-icons:before' => 'right: calc(50% + {{SIZE}}{{UNIT}} / 2 + {{divider_icon_bdw.SIZE}}px);',
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider-icons:after' => 'left: calc(50% + {{SIZE}}{{UNIT}} / 2 + {{divider_icon_bdw.SIZE}}px);',
				],
				'condition' => [
					'direction' => 'vertical'
				]
			]
		);

		$this->add_responsive_control(
			'divider_icon_height_vr',
			[
				'label' => esc_html__( 'Icon Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider-icons .fa' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider-icons .fa:first-child' => 'line-height: calc({{SIZE}}{{UNIT}} * 1.3);',
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider-icons .fa:last-child' => 'line-height: calc({{SIZE}}{{UNIT}} * 0.8);',
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider' => 'margin-top: calc(-{{SIZE}}{{UNIT}} - {{divider_icon_bdw.SIZE}}px);',
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider-icons:before' => 'top: calc({{SIZE}}{{UNIT}} - {{divider_thickness.SIZE}}px / 2 + {{divider_icon_bdw.SIZE}}px);',
					'{{WRAPPER}} .tmpcoder-ba-vertical .tmpcoder-ba-divider-icons:after' => 'top: calc({{SIZE}}{{UNIT}} - {{divider_thickness.SIZE}}px / 2 + {{divider_icon_bdw.SIZE}}px);',
				],
				'separator' => 'after',
				'condition' => [
					'direction' => 'vertical'
				]
			]
		);

		$this->add_control(
			'divider_icon_border_type',
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
					'{{WRAPPER}} .tmpcoder-ba-divider-icons' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_icon_bdw',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-divider-icons' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'divider_icon_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'divider_icon_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-divider-icons' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'before-after', [
			'Vertical Image Comparison',
			'Move Images on Mouse Move (Hover)',
			'Set Default Divider Position (% After Image to show)',
			'Show Labels on Image Hover',
		] );

		// Styles ====================
		// Section: Labels -----------
		$this->start_controls_section(
			'section_style_labels',
			[
				'label' => esc_html__( 'Labels', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'label_display!' => 'none',
				]
			]
		);

		$this->add_control(
			'labels_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-label > div' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'labels_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-label > div' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'labels_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-label > div' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'labels_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-ba-label > div',
			]
		);

		$this->add_control(
			'labels_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'labels_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-ba-label > div'
			]
		);

		$this->add_control(
			'labels_border_type',
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
					'{{WRAPPER}} .tmpcoder-ba-label > div' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'labels_border_width',
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
					'{{WRAPPER}} .tmpcoder-ba-label > div' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'labels_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'labels_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 7,
					'right' => 15,
					'bottom' => 7,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ba-label > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'labels_radius',
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
					'{{WRAPPER}} .tmpcoder-ba-label > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if ( ! tmpcoder_is_availble() ) {
			$settings['direction'] = 'horizontal';
			$settings['trigger'] = 'drag';
			$settings['divider_position'] = 50;
			$settings['label_display'] = 'pro-hv' == $settings['label_display'] ? 'none' : $settings['label_display'];
		}

		// Class
		$class  = ' tmpcoder-ba-'. $settings['direction'];
		$class .= ' tmpcoder-ba-labels-'. $settings['label_display'];

		// Icon Direction
		$icon_dir_first  = 'horizontal' === $settings['direction'] ? 'left' : 'up';
		$icon_dir_second = 'horizontal' === $settings['direction'] ? 'right' : 'down';

		// Image Source
		$image_1_src = Group_Control_Image_Size::get_attachment_image_src( $settings['image_upload_1']['id'], 'image_size', $settings );

		$image_2_src = Group_Control_Image_Size::get_attachment_image_src( $settings['image_upload_2']['id'], 'image_size', $settings );

		// Divider
		echo '<div class="tmpcoder-ba-image-container'. esc_attr($class) .'" data-position="'. esc_attr($settings['divider_position']) .'" data-trigger="'. esc_attr($settings['trigger']) .'">';
			
		// Defaults
		$image_upload_1 = '';
		$image_upload_2 = '';
		
		if ( !empty($settings['image_upload_1']['url']) && '' !== ($settings['image_upload_1']['url'] ?? '') ) {
			$settings[ 'image_upload_1_1' ] = ['id' => $settings['image_upload_1']['id'] ?? 0];
			$image_upload_1 = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_upload_1_1' );
		}
		if ( !empty($settings['image_upload_2']['url']) && '' !== ($settings['image_upload_2']['url'] ?? '') ) {
			$settings[ 'image_upload_2_2' ] = ['id' => $settings['image_upload_2']['id'] ?? 0];
			$image_upload_2 = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_upload_2_2' );
		}

			// Image 1
			echo '<div class="tmpcoder-ba-image-1">';
				if ($image_upload_1) {
					echo wp_kses_post($image_upload_1);
				}
				else
				{
					echo "<img src='".esc_url($settings['image_upload_1']['url'] ?? '')."'>";
				}
			echo '</div>';
			
			// Image 2
			echo '<div class="tmpcoder-ba-image-2">';
				if ($image_upload_2) {
					echo wp_kses_post($image_upload_2);
				}
				else
				{
					echo "<img src='".esc_url($settings['image_upload_2']['url'] ?? '')."'>";
				}
			echo '</div>';

			// Divider
			echo '<div class="tmpcoder-ba-divider">';
				echo '<div class="tmpcoder-ba-divider-icons">';
					echo '<i class="fa '. esc_attr(($settings['divider_icon'] ?? '') .'-'. $icon_dir_first) .'"></i>';
					echo '<i class="fa '. esc_attr(($settings['divider_icon'] ?? '') .'-'. $icon_dir_second) .'"></i>';
				echo '</div>';
			echo '</div>';

			// Label 1
			if ( !empty($settings['label_image_1']) && '' !== ($settings['label_image_1'] ?? '') ) {
				echo '<div class="tmpcoder-ba-label tmpcoder-ba-label-1">';
					echo '<div>'. esc_html($settings['label_image_1'] ?? '') .'</div>';
				echo '</div>';
			}

			// Label 2
			if ( !empty($settings['label_image_2']) && '' !== ($settings['label_image_2'] ?? '') ) {
				echo '<div class="tmpcoder-ba-label tmpcoder-ba-label-2">';
					echo '<div>'. esc_html($settings['label_image_2'] ?? '') .'</div>';
				echo '</div>';
			}

		echo '</div>';

	}
	
}