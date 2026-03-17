<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Social_Share extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-sharing-buttons';
	}

	public function get_title() {
		return esc_html__( 'Sharing Buttons', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-share';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'social sharing', 'sharing buttons', ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

    public function get_style_depends() {
		return [ 'tmpcoder-sharing-buttons' ];
	}

	public function add_repeater_args_sharing_custom_label() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_control_sharing_show_icon() {}

	public function add_control_sharing_columns() {
		$this->add_responsive_control(
			'sharing_columns',
			[
				'label' => esc_html__( 'Columns', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'0' => esc_html__( 'Auto', 'sastra-essential-addons-for-elementor' ),
					'1' => esc_html__( '1', 'sastra-essential-addons-for-elementor' ),
					'2' => esc_html__( '2', 'sastra-essential-addons-for-elementor' ),
					'pro-3' => esc_html__( '3 (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-4' => esc_html__( '4 (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-5' => esc_html__( '5 (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-6' => esc_html__( '6 (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => '0',
				'prefix_class' => 'elementor-grid%s-',
			]
		);
	}

	public function add_control_sharing_show_label() {}

	public function add_control_sharing_icon_border_radius() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_sharing_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$repeater = new Repeater();

		$repeater->add_control(
			'sharing_icon',
			[
				'label' => esc_html__( 'Network', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fab fa-facebook-f' => 'Facebook',
					'fab fa-x-twitter' => 'Twitter',
					'fab fa-linkedin-in' => 'Linkedin',
					'fab fa-pinterest-p' => 'Pinterest',
					'fab fa-reddit' => 'Reddit',
					'fab fa-tumblr' => 'Tumblr',
					'fab fa-digg' => 'Digg',
					'fab fa-xing' => 'Xing',
					'fab fa-vk' => 'vKontakte',
					'fab fa-odnoklassniki' => 'Odnoklassniki',
					'fab fa-get-pocket' => 'Pocket',
					'fab fa-skype' => 'Skype',
					'fab fa-whatsapp' => 'WhatsApp',
					'fab fa-telegram' => 'Telegram',
					'fas fa-envelope' => 'Email',
					'fas fa-print' => 'Print',
				],
				'default' => 'fab fa-facebook-f',
			]
		);

		$repeater->add_control( 'sharing_custom_label', $this->add_repeater_args_sharing_custom_label() );

		$repeater->add_control(
			'show_whatsapp_title',
			[
				'label' => esc_html__( 'Show Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default' => 'yes',
				'condition' => [
					'sharing_icon' => 'fab fa-whatsapp'
				]
			]
		);

		$repeater->add_control(
			'show_whatsapp_excerpt',
			[
				'label' => esc_html__( 'Show Excerpt', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'sharing_icon' => 'fab fa-whatsapp'
				]
			]
		);

		$this->add_control(
			'sharing_buttons',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'sharing_icon' => 'fab fa-facebook-f',
					],
					[
						'sharing_icon' => 'fab fa-x-twitter',
					],
					[
						'sharing_icon' => 'fab fa-linkedin-in',
					],
				],
				'title_field' => '<i class="{{{ sharing_icon }}}"></i> Social Icon',
			]
		);

		if ( !tmpcoder_is_availble() ) {
			$this->add_control(
				'sharing_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Buttons are available<br> in the <strong><a href="https://spexoaddons.com/?ref=rea-plugin-panel-sharing-buttons-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Layout ----------
		$this->start_controls_section(
			'section_sharing_layout',
			[
				'label' => esc_html__( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'tmpcoder_enable_title_attribute',
			[
				'label' => esc_html__( 'Enable Title Attribute', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'render_type' => 'template',
			]
		);

		$this->add_control_sharing_columns();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'sharing-buttons', 'sharing_columns', ['pro-3', 'pro-4', 'pro-5', 'pro-6'] );

		$this->add_control_sharing_show_icon();

		$this->add_control_sharing_show_label();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'sharing-buttons', [
			'Add Unlimited Social Icons',
			'Custom Social Media Label',
			'Layout Columns 1,2,3,4,5,6',
			'Only Labels - Show/Hide Icon',
			'Only Icons - Show/Hide Label',
			'Advanced Styling options',
		] );
		
		// Tab: Styles ==============
		// Section: Layout ----------
		$this->start_controls_section(
			'section_styles_sharing_layout',
			[
				'label' => esc_html__( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sharing_gutter_hr',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-grid-0) .elementor-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-grid-0 .tmpcoder-sharing-buttons a' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .tmpcoder-sharing-buttons a' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .tmpcoder-sharing-buttons a' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}}.elementor-grid-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
				],
			]
		);

		$this->add_responsive_control(
			'sharing_gutter_vr',
			[
				'label' => esc_html__( 'Vertical Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-grid-0) .elementor-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-grid-0 .tmpcoder-sharing-buttons a' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .tmpcoder-sharing-buttons a' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .tmpcoder-sharing-buttons a' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sharing_icon_width',
			[
				'label' => esc_html__( 'Icon Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 45,
				],
				'range' => [
					'px' => [
						'min' => 15,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon i' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'sharing_icon_height',
			[
				'label' => esc_html__( 'Icon Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 45,
				],
				'range' => [
					'px' => [
						'min' => 15,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon svg' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-label' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sharing_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 18,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'sharing_label_spacing',
			[
				'label' => esc_html__( 'Label Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-label' => 'padding: 0 {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sharing_label_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-label',
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'sharing_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				],
				'separator' => 'before'
			]
		);

		$this->add_control_sharing_icon_border_radius();

		$this->add_control(
			'sharing_button_border_radius',
			[
				'label' => esc_html__( 'Button Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'sharing_button_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Justified', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons' => 'justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Styles ==============
		// Section: Styles ----------
		$this->start_controls_section(
			'section_styles_sharing_styles',
			[
				'label' => esc_html__( 'Styles', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sharing_custom_colors',
			[
				'label' => esc_html__( 'Use Custom Colors', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'after'
			]
		);

		$this->add_control(
			'sharing_icon_bg_tr',
			[
				'label' => esc_html__( 'Icon Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'sharing_custom_colors' => '',
				]
			]
		);

		$this->add_control(
			'sharing_label_bg',
			[
				'label' => esc_html__( 'Label Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'sharing_show_label' => 'yes',
					'sharing_custom_colors' => '',
				]
			]
		);

		$this->add_control(
			'sharing_label_bg_tr',
			[
				'label' => esc_html__( 'Label Background Transparency', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => '1',
					'yes' => '0.92'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-label' => 'opacity: {{VALUE}};',
				],
				'condition' => [
					'sharing_show_label' => 'yes',
					'sharing_custom_colors' => '',
					'sharing_label_bg' => 'yes',
				]
			]
		);

		$this->start_controls_tabs(
			'tabs_sharing_custom_colors', [
				'condition' => [
					'sharing_custom_colors' => 'yes',
				]
			]
		);

		$this->start_controls_tab(
			'tab_sharing_custom_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'sharing_icon_color',
			[
				'label'  => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon svg' => 'fill: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'sharing_icon_bg_color',
			[
				'label'  => esc_html__( 'Icon Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon svg' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_label_color',
			[
				'label'  => esc_html__( 'Label Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-label' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_control(
			'sharing_label_bg_color',
			[
				'label'  => esc_html__( 'Label Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4183C5',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-label' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_control(
			'sharing_label_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_sharing_custom_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'sharing_icon_color_hr',
			[
				'label'  => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_icon_bg_color_hr',
			[
				'label'  => esc_html__( 'Icon Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4183C5',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon:hover i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon:hover svg' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_label_color_hr',
			[
				'label'  => esc_html__( 'Label Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon:hover .tmpcoder-sharing-label' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_control(
			'sharing_label_bg_color_hr',
			[
				'label'  => esc_html__( 'Label Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon:hover .tmpcoder-sharing-label' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'sharing_show_label' => 'yes',
				]
			]
		);

		$this->add_control(
			'sharing_label_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'sharing_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-sharing-buttons .tmpcoder-sharing-icon span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if ( ! tmpcoder_is_availble() ) {
			$settings['sharing_custom_colors'] = '';
			$settings['sharing_show_label'] = '';
			$settings['sharing_label_bg'] = '';
			$settings['sharing_show_icon'] = 'yes';
		}

		$class  = (empty($settings['sharing_custom_colors']) || '' === $settings['sharing_custom_colors']) ? ' tmpcoder-sharing-official' : '';
		$class .= (empty($settings['sharing_show_label']) || '' === $settings['sharing_show_label']) ? ' tmpcoder-sharing-label-off' : '';
		$class .= (empty($settings['sharing_icon_bg_tr']) || '' === $settings['sharing_icon_bg_tr']) ? ' tmpcoder-sharing-icon-tr' : '';
		$class .= (empty($settings['sharing_label_bg']) || '' === $settings['sharing_label_bg']) ? ' tmpcoder-sharing-label-tr' : '';

		echo '<div class="tmpcoder-sharing-buttons elementor-grid'. esc_attr($class) .'">';
		
		$count = 0;
		foreach( $settings['sharing_buttons'] as $button ) {
			if ( !tmpcoder_is_availble() && $count === 4 ) {
				break;
			}

			$sharing_icon = str_replace( 'fab ', '', $button['sharing_icon'] );
			$sharing_icon = str_replace( 'fas ', '', $sharing_icon );
			$sharing_icon = str_replace( 'fa-', '', $sharing_icon );

			$args = [
				'icons' => $settings['sharing_show_icon'],
				'network' => $sharing_icon,
				'labels' => $settings['sharing_show_label'],
				'custom_label' => $button['sharing_custom_label'],
				'tooltip' => 'no',
				'tmpcoder_enable_title_attribute' => $settings['tmpcoder_enable_title_attribute'],
				'url' => esc_url( get_the_permalink() ),
				'title' => esc_html( get_the_title() ),
				'text' => esc_html( get_the_excerpt() ),
				'image' => esc_url( get_the_post_thumbnail_url() )
			];

			if ( isset($button['show_whatsapp_excerpt']) && isset($button['show_whatsapp_title']) ) {
				$args['show_whatsapp_title'] = $button['show_whatsapp_title'];
				$args['show_whatsapp_excerpt'] = $button['show_whatsapp_excerpt'];
			}

			echo '<div class="elementor-grid-item">';
				echo wp_kses(tmpcoder_get_post_sharing_icon( $args ), tmpcoder_wp_kses_allowed_html()); 
			echo '</div>';

			$count++;
		}

		echo '</div>';
	}
	
}