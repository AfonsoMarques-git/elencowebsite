<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Phone_Call extends Widget_Base {
			
	public function get_name() {
		return 'tmpcoder-phone-call';
	}

	public function get_title() {
		return esc_html__( 'Phone Call', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-tel-field';
	}

	public function get_categories() {

		return [ 'tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'Phone', 'call','phone','Phone call' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

    public function get_style_depends() {
		return [ 'tmpcoder-phone-call'];
	}

	protected function register_controls() {

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
		'telnumber',
		[
			'label' => esc_html__( 'Phone Number', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
			'default' => '123456789',
		]
	);
	$this->add_control(
		'button_position',
		[
			'label' => esc_html__( 'Button Position', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'fixed',
			'label_block' => false,
			'render_type' => 'template',
			'options' => [
				'fixed' => esc_html__( 'Fixed', 'sastra-essential-addons-for-elementor' ),
				'inline' => esc_html__( 'Inline', 'sastra-essential-addons-for-elementor' ),
				],
			'prefix_class' => 'tmpcoder-pc-btn-align-',
			'separator' => 'before',
		]
	);

	$this->add_responsive_control(
		'button_position_inline',
		[
			'label' => esc_html__( 'Inline Position', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::CHOOSE,
			'default' => 'center',
			'label_block' => false,
			'options' => [
				'flex-start' => [
					'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
					'icon' => 'eicon-h-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
					'icon' => 'eicon-h-align-center',
				],
				'flex-end' => [
					'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
					'icon' => 'eicon-h-align-right',
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tmpcoder-pc-wrapper' => 'justify-content: {{VALUE}}',
			],

			'separator' => 'before',
			'condition' => [
				'button_position' => 'inline',
			],
		]
	);
	
	$this->add_responsive_control(
		'button_position_fixed',
		[
			'label' => esc_html__( 'Fixed Position', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::CHOOSE,
			'default' => 'right',
			'label_block' => false,
			'options' => [
				'left' => [
					'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
					'icon' => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
					'icon' => 'eicon-h-align-right',
				],
			],
			'separator' => 'before',
			'default' => 'right',
			'condition' => [
				'button_position' => 'fixed',
			],
			'prefix_class' => 'tmpcoder-pc-btn-align-fixed-',
		]
	);
	$this->add_responsive_control(
		'distance_x_right',
		[
			'label' => esc_html__( 'Distance Right', 'sastra-essential-addons-for-elementor' ),
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
				'size' => 30,
			],
			'selectors' => [
				'{{WRAPPER}}.tmpcoder-pc-btn-align-fixed-right .tmpcoder-pc-btn' => 'right: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'button_position' => 'fixed',
				'button_position_fixed' => 'right',
			],
		]
	);
	$this->add_responsive_control(
		'distance_y_right',
		[
			'label' => esc_html__( 'Distance Bottom', 'sastra-essential-addons-for-elementor' ),
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
				'size' => 30,
			],
			'selectors' => [
				'{{WRAPPER}}.tmpcoder-pc-btn-align-fixed-right .tmpcoder-pc-btn' => 'bottom: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'button_position' => 'fixed',
				'button_position_fixed' => 'right',
			],
		]
	);

	$this->add_responsive_control(
		'distance_x_left',
		[
			'label' => esc_html__( 'Distance Left', 'sastra-essential-addons-for-elementor' ),
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
				'size' => 30,
			],
			'selectors' => [
				'{{WRAPPER}}.tmpcoder-pc-btn-align-fixed-left .tmpcoder-pc-btn' => 'left: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'button_position' => 'fixed',
				'button_position_fixed' => 'left',
			],
		]
	);

	$this->add_responsive_control(
		'distance_y_left',
		[
			'label' => esc_html__( 'Distance Bottom', 'sastra-essential-addons-for-elementor' ),
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
				'size' => 30,
			],
			'selectors' => [
				'{{WRAPPER}}.tmpcoder-pc-btn-align-fixed-left .tmpcoder-pc-btn' => 'bottom: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'button_position' => 'fixed',
				'button_position_fixed' => 'left',
			],
		]
	);

	$this->add_control(
		'pc_icon',
		[
			'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::ICONS,
			'skin' => 'inline',
			'label_block' => false,
			'default' => [
				'value' => 'fas fa-phone',
				'library' => 'fa-solid',
			],
			'separator' => 'before',
			'recommended' => [
				'fa-solid' => [
					'phone',
					'phone-alt',
					'search',
				],
			],
		]
	);

	$this->add_control(
			'button_txt_show',
			[
				'label' => __( 'Button Text', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'sastra-essential-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
	);

	$this->add_control(
		'pc_icon_layout_select',
		[
			'label' => esc_html__( 'Icon Align', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'right',
			'label_block' => false,
			'options' => [
				'top' => esc_html__( 'Top', 'sastra-essential-addons-for-elementor' ),
				'bottom' => esc_html__( 'Bottom', 'sastra-essential-addons-for-elementor' ),
				'right' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
				'left' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
			],
			'condition' => [
				'pc_icon[value]!' => '',
				'button_txt_show' => 'yes',
			],
			'prefix_class' => 'tmpcoder-pc-btn-icon-',
		]
	);
	
	$this->add_control(
		'button_text',
		[
			'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
			'default' => 'Call Now',
			'condition' => [
				'button_txt_show' => 'yes',
			],
		]
	);

	$this->end_controls_section();

	// Section: Request New Feature
	tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

	// Section Button ------------
	$this->start_controls_section(
		'section_style_button',
		[
			'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);


	$this->start_controls_tabs( 'tabs_pc_button_colors' );

	// Normal Tab ------------
	$this->start_controls_tab(
		'tab_pc_button_normal_colors',
		[
			'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
		]
	);

	$this->add_control(
		'button_text_color',
		[
			'label' => esc_html__( ' Color', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .tmpcoder-pc-content' => 'color: {{VALUE}}',
				'{{WRAPPER}} .tmpcoder-pc-btn-icon' => 'color: {{VALUE}}',
				'{{WRAPPER}} .tmpcoder-pc-btn-icon svg' => 'fill: {{VALUE}}',
			],
		]
	);
	
	$this->add_control(
		'button_bg_color',
		[
			'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#39B54A',
			'selectors' => [
				'{{WRAPPER}} .tmpcoder-pc-btn' => 'background-color: {{VALUE}}',
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
				'{{WRAPPER}} .tmpcoder-pc-btn' => 'border-color: {{VALUE}}',
			],
			'condition' =>[
				'border_switcher' => 'yes',
			],
		]
	);

	$this->add_group_control(
		\Elementor\Group_Control_Box_Shadow::get_type(),
		[
			'name' => 'button_box_shadow',
			'label' => __( 'Box Shadow', 'sastra-essential-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .tmpcoder-pc-btn',
		]
	);

	$this->end_controls_tab();// normal tab end

	// Hover Tab -------------
	$this->start_controls_tab(
		'tab_button_hover_colors',
		[
			'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
		]
	);

	$this->add_control(
		'button_texte_color_hover',
		[
			'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#fff',
			'selectors' => [
				// '{{WRAPPER}} .tmpcoder-pc-btn:hover > .tmpcoder-pc-btn-icon' => 'Color: {{VALUE}}',
				'{{WRAPPER}} .tmpcoder-pc-btn:hover svg' => 'fill: {{VALUE}}',
				'{{WRAPPER}} .tmpcoder-pc-btn:hover .tmpcoder-pc-text' => 'color: {{VALUE}}',
			],
		]
	);

	$this->add_control(
		'button_bg_color_hover',
		[
			'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#1FD538',
			'selectors' => [
				'{{WRAPPER}} .tmpcoder-pc-btn:hover' => 'background-color: {{VALUE}}',
			],
		]
	);

	$this->add_control(
		'button_border_color_hover',
		[
			'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#E8E8E8',
			'selectors' => [
				'{{WRAPPER}} .tmpcoder-pc-btn:hover' => 'border-color: {{VALUE}}',
			],
			'condition' =>[
				'border_switcher' => 'yes',
			],
		]
	);

	$this->add_group_control(
		\Elementor\Group_Control_Box_Shadow::get_type(),
		[
			'name' => 'button_box_shadow_hover',
			'label' => __( 'Box Shadow', 'sastra-essential-addons-for-elementor' ),
			'selector' => '{{WRAPPER}} .tmpcoder-pc-btn:hover',
		]
	);

	$this->end_controls_tab();// hover tab end

	$this->end_controls_tabs();// End tabs

	$this->add_control(
			'hover_animation_hover_duration',
			[
				'label' => __( 'Hover Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'default' => 0.3,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pc-btn' => 'transition:all {{VALUE}}s ease-in-out 0s;',
					'{{WRAPPER}} .tmpcoder-pc-btn svg' => 'transition:all {{VALUE}}s ease-in-out 0s;',
				],
				'separator' => 'before',
			]
		);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'button_typography',
			'selector' => '{{WRAPPER}} .tmpcoder-pc-content,{{WRAPPER}} .tmpcoder-pc-content::after',
			'separator' => 'before',
			'condition' => [
				'button_txt_show' => 'yes',
			],
		]
	);

	$this->add_control(
		'icon_size',
		[
			'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
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
				'size' => 13,
			],
			'selectors' => [
				'{{WRAPPER}} .tmpcoder-pc-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .tmpcoder-pc-btn-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
			],
			'separator' => 'before',
			'condition' => [
				'pc_icon[value]!' => '',
			],
		]
	);

	$this->add_control(
		'pc_icon_distance',
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
				'size' => 7,
			],
			'selectors' => [
				'{{WRAPPER}}.tmpcoder-pc-btn-icon-top .tmpcoder-pc-btn-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.tmpcoder-pc-btn-icon-left .tmpcoder-pc-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.tmpcoder-pc-btn-icon-right .tmpcoder-pc-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.tmpcoder-pc-btn-icon-bottom .tmpcoder-pc-btn-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
			'separator' => 'before',
			'condition' => [
				'pc_icon[value]!' => '',
				'button_txt_show' => 'yes',
			],
		]
	);

	$this->add_responsive_control(
		'button_padding',
		[
			'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px' ],
			'default' => [
				'top' => 13,
				'right' => 14,
				'bottom' => 13,
				'left' => 14,
			],
			'selectors' => [
				'{{WRAPPER}} .tmpcoder-pc-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);

	$this->add_control(
		'border_switcher',
		[
			'label' => esc_html__( 'Border', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
			'return_value' => 'yes',
			'label_block' => false,
			'default' => 'yes',

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
				'{{WRAPPER}} .tmpcoder-pc-btn' => 'border-style: {{VALUE}};',
			],
			'condition' =>[
				'border_switcher' => 'yes',
			],
		]
	);

	$this->add_responsive_control(
		'border_width',
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
				'{{WRAPPER}} .tmpcoder-pc-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
			'condition' =>[
					'border_switcher' => 'yes',
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
					'{{WRAPPER}} .tmpcoder-pc-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
	);

	$this->end_controls_section();

	}

	protected function render() {
	// Get Settings
	$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		echo '<div class="tmpcoder-pc-wrapper">';
			echo '<a href="tel:'. esc_attr($settings['telnumber']) .'" class="tmpcoder-pc-btn">';
				echo '<div class="tmpcoder-pc-content">';
				if ( (!empty($settings['button_text']) && '' !== $settings['button_text']) && $settings['button_txt_show'] == 'yes' ) {
					echo '<span class="tmpcoder-pc-text">'. esc_html($settings ['button_text']) .'</span>';
				}
				if ( !empty($settings['pc_icon']['value']) && '' !== $settings['pc_icon']['value'] ) {
					echo '<span class="tmpcoder-pc-btn-icon">';
			 			\Elementor\Icons_Manager::render_icon( $settings['pc_icon'] );
					echo '</span>';
				}
				echo '</div>';
			echo '</a>';
		echo '</div>';


	}

}