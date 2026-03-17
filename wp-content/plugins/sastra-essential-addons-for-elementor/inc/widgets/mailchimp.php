<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Mailchimp extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-mailchimp';
	}

	public function get_title() {
		return esc_html__( 'Mailchimp', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-mailchimp';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_script_depends() {
		return [ 'tmpcoder-mailchimp'];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-mailchimp'];
	}

	public function get_keywords() {
		return [ 'subscribe', 'subscription form', 'email subscription', 'sing up form', 'singup form', 'newsletter', 'mailchimp' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_clear_fields_on_submit() {
		$this->add_control(
			'clear_fields_on_submit',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Clear Fields On Submit %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}

	public function add_control_extra_fields() {
		$this->add_control(
			'extra_fields',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Extra Fields %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}
	
	public function add_control_name_label() {}
	
	public function add_control_name_placeholder() {}
	
	public function add_control_last_name_label() {}
	
	public function add_control_last_name_placeholder() {}

	public function add_control_phone_number_label_and_placeholder() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ----------
		$this->start_controls_section(
			'section_mailchimp_settings',
			[
				'label' => esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'maichimp_audience',
			[
				'label' => esc_html__( 'Select Audience', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'def',
				'options' => tmpcoder_get_mailchimp_lists(),
			]
		);

		if ( '' == get_option('tmpcoder_mailchimp_api_key') ) {
			$this->add_control(
				'mailchimp_key_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					// Translators: %s is the icon.
					'raw' => sprintf( __( 'Navigate to <strong><a href="%1$s" target="_blank">Dashboard > %2$s > Integrations</a></strong> to set up <strong>MailChimp API Key</strong>.', 'sastra-essential-addons-for-elementor' ), admin_url( 'admin.php?page='.TMPCODER_THEME.'-welcome&tab=settings' ), TMPCODER_PLUGIN_NAME ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_mailchimp_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_clear_fields_on_submit();

		$this->add_control(
			'show_form_header',
			[
				'label' => esc_html__( 'Show Form Header', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'form_title',
			[
				'label' => esc_html__( 'Form Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Join the family!',
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'form_description',
			[
				'label' => esc_html__( 'Form Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sign up for a Newsletter.',
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'form_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-envelope',
					'library' => 'fa-regular',
				],
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'form_icon_display',
			[
				'label' => esc_html__( 'Icon Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'sastra-essential-addons-for-elementor' ),
					'left' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
				],
				'selectors_dictionary' => [
					'top' => 'display: block;margin:auto;',
					'left' => 'display: inline; margin-right: 5px;vertical-align: middle;'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-header i' => '{{VALUE}}',
					'{{WRAPPER}} .tmpcoder-mailchimp-header svg' => '{{VALUE}}',
				],
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'email_label',
			[
				'label' => esc_html__( 'Email Label', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Email',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'email_placeholder',
			[
				'label' => esc_html__( 'Email Placeholder', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'sample@mail.com',
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'mailchimp_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Name and Last Name Field</span> options are available in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-panel-mailchimp-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->add_control(
			'subscribe_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Subscribe',
			]
		);

		$this->add_control(
			'subscribe_button_loading_text',
			[
				'label' => esc_html__( 'Button Loading Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Subscribing...',
				'separator' => 'after'
			]
		);

		$this->add_control(
			'success_message',
			[
				'label' => esc_html__( 'Success Message', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'You have been successfully Subscribed!',
			]
		);

		$this->add_control(
			'error_message',
			[
				'label' => esc_html__( 'Error Message', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Ops! Something went wrong, please try again.',
			]
		);

		$this->add_control_extra_fields();

		$this->add_control_name_label();

		$this->add_control_name_placeholder();

		$this->add_control_last_name_label();

		$this->add_control_last_name_placeholder();

		$this->add_control_phone_number_label_and_placeholder();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'mailchimp', [
			'Add Extra Fields - Name, Last Name & Phone Number',
			'Clear Fields after Form Submission'
		] );

		// Styles ====================
		// Section: Container --------
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'container_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hr',
				'options' => [
					'hr' => esc_html__( 'Horizontal', 'sastra-essential-addons-for-elementor' ),
					'vr' => esc_html__( 'Vertical', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-mailchimp-layout-',
				'render_type' => 'template',
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-form'
			]
		);

		$this->add_control(
			'container_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-form' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-form',
			]
		);

		$this->add_control(
			'container_border_type',
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
					'{{WRAPPER}} .tmpcoder-mailchimp-form' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'container_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-form' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'container_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'container_radius',
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
					'{{WRAPPER}} .tmpcoder-mailchimp-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Title & Description
		$this->start_controls_section(
			'section_style_header',
			[
				'label' => esc_html__( 'Form Header', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'header_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-header' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_align_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'header_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-header i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-mailchimp-header svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'header_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 28,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-header i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-mailchimp-header svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'header_title_color',
			[
				'label' => esc_html__( 'Title Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#424242',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-header h3' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_title_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-header h3',
			]
		);

		$this->add_control(
			'header_description_color',
			[
				'label' => esc_html__( 'Description Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#606060',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-header p' => 'color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_description_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-header p',
			]
		);

		$this->add_responsive_control(
			'header_title_distance',
			[
				'label' => esc_html__( 'Title Bottom Distance', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-header h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'header_desc_distance',
			[
				'label' => esc_html__( 'Description Bottom Distance', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}} .tmpcoder-mailchimp-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Labels -----------
		$this->start_controls_section(
			'section_style_labels',
			[
				'label' => esc_html__( 'Labels', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'labels_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#818181',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields label' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'labels_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-fields label',
			]
		);

		$this->add_responsive_control(
			'labels_spacing',
			[
				'label' => esc_html__( 'Spacing', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Fields -----------
		$this->start_controls_section(
			'section_style_inputs',
			[
				'label' => esc_html__( 'Fields', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_forms_inputs_style' );

		$this->start_controls_tab(
			'tab_inputs_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#474747',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ADADAD',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_inputs_hover',
			[
				'label' => esc_html__( 'Focus', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'input_color_fc',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input:focus' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_placeholder_color_fc',
			[
				'label' => esc_html__( 'Placeholder Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input:focus::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color_fc',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input:focus' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_border_color_fc',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input:focus' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-fields input',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'input_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-fields input',
			]
		);

		$this->add_responsive_control(
			'input_height',
			[
				'label' => esc_html__( 'Input Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'input_spacing',
			[
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-mailchimp-layout-vr .tmpcoder-mailchimp-email, {{WRAPPER}}.tmpcoder-mailchimp-layout-vr .tmpcoder-mailchimp-first-name, {{WRAPPER}}.tmpcoder-mailchimp-layout-vr .tmpcoder-mailchimp-last-name, {{WRAPPER}}.tmpcoder-mailchimp-layout-vr .tmpcoder-mailchimp-phone-number' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-mailchimp-layout-hr .tmpcoder-mailchimp-email, {{WRAPPER}}.tmpcoder-mailchimp-layout-hr .tmpcoder-mailchimp-first-name, {{WRAPPER}}.tmpcoder-mailchimp-layout-hr .tmpcoder-mailchimp-last-name, {{WRAPPER}}.tmpcoder-mailchimp-layout-hr .tmpcoder-mailchimp-phone-number' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'input_border_type',
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
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_width',
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
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'input_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 15,
					'bottom' => 0,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_radius',
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
					'{{WRAPPER}} .tmpcoder-mailchimp-fields input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_subscribe_btn',
			[
				'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'subscribe_btn_align',
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
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-mailchimp-layout-vr .tmpcoder-mailchimp-subscribe' => 'align-self: {{VALUE}};',
				],
				'condition' => [
					'container_align' => 'vr'
				]
			]
		);

		$this->add_control(
			'subscribe_btn_divider1',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'container_align' => 'vr'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_subscribe_btn_style' );

		$this->start_controls_tab(
			'tab_subscribe_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'subscribe_btn_bg_color',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#5729d9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn'
			]
		);

		$this->add_control(
			'subscribe_btn_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'subscribe_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E6E2E2',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'subscribe_btn_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_subscribe_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'subscribe_btn_bg_color_hr',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#4A45D2',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn:hover'
			]
		);

		$this->add_control(
			'subscribe_btn_color_hr',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'subscribe_btn_border_color_hr',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'subscribe_btn_box_shadow_hr',
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'subscribe_btn_divider2',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'subscribe_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subscribe_btn_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn'
			]
		);

		$this->add_responsive_control(
			'subscribe_btn_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 300,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 130,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-subscribe' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'subscribe_btn_height',
			[
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'subscribe_btn_spacing',
			[
				'label' => esc_html__( 'Top Distance', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-mailchimp-layout-vr .tmpcoder-mailchimp-subscribe-btn' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'container_align' => 'vr'
				]
			]
		);

		$this->add_control(
			'subscribe_btn_border_type',
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
					'{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subscribe_btn_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'subscribe_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'subscribe_btn_radius',
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
					'{{WRAPPER}} .tmpcoder-mailchimp-subscribe-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Message ----------
		$this->start_controls_section(
			'section_style_message',
			[
				'label' => esc_html__( 'Message', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'success_message_color',
			[
				'label' => esc_html__( 'Success Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-success-message' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => esc_html__( 'Error Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fb0000',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-error-message' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'message_color_bg',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-message' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-mailchimp-message',
			]
		);

		$this->add_responsive_control(
			'message_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'message_spacing',
			[
				'label' => esc_html__( 'Spacing', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mailchimp-message' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
	}

	public function render_pro_element_extra_fields() {}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if ( tmpcoder_is_availble() ) {
			$clear_fields_on_submit = esc_attr($settings['clear_fields_on_submit']);
		} else {
			$clear_fields_on_submit = '';
		}

		?>

		<form class="tmpcoder-mailchimp-form" id="tmpcoder-mailchimp-form-<?php echo esc_attr( $this->get_id() ); ?>" method="POST" data-list-id="<?php echo esc_attr($settings['maichimp_audience']); ?>" data-clear-fields="<?php echo esc_attr($clear_fields_on_submit); ?>">
			<!-- Form Header -->
			<?php if ( 'yes' === $settings['show_form_header'] ) : ?>
			<div class="tmpcoder-mailchimp-header">
				<?php

				if(is_array($settings['form_icon']['value'])){
					ob_start();
					\Elementor\Icons_Manager::render_icon( $settings['form_icon'], [ 'aria-hidden' => 'true' ] );
					$icon = ob_get_clean();
					$form_icon = !empty($settings['form_icon']) ? '<span>'. $icon .'</span>' : '';
				}
				else
				{
			 		$form_icon = (!empty($settings['form_icon']['value']) && '' !== $settings['form_icon']['value']) ? '<i class="'. esc_attr($settings['form_icon']['value']) .'"></i>' : ''; 
				}

				?>

				<h3>
					<?php
					echo wp_kses($form_icon, tmpcoder_wp_kses_allowed_html());
					echo esc_html($settings['form_title']);
					?>
				</h3>
				<p><?php echo wp_kses( $settings['form_description'], [ 'br' => [], 'em' => [], 'strong' => [], ] ); ?></p>
			</div>
			<?php endif; ?>

			<div class="tmpcoder-mailchimp-fields">
				<!-- Email Input -->
				<div class="tmpcoder-mailchimp-email">
					<?php echo (!empty($settings['email_label']) && '' !== $settings['email_label']) ? '<label>'. esc_html($settings['email_label']) .'</label>' : ''; ?>
					<input type="email" name="tmpcoder_mailchimp_email" placeholder="<?php echo esc_attr($settings['email_placeholder']); ?>" required="required">
				</div>

				<!-- Extra Fields -->
				<?php $this->render_pro_element_extra_fields(); ?>

				<!-- Subscribe Button -->
				<div class="tmpcoder-mailchimp-subscribe">
					<button type="submit" id="tmpcoder-subscribe-<?php echo esc_attr( $this->get_id() ); ?>" class="tmpcoder-mailchimp-subscribe-btn" data-loading="<?php echo esc_attr($settings['subscribe_button_loading_text']); ?>">
				  		<?php echo esc_html($settings['subscribe_btn_text']); ?>
					</button>
				</div>
			</div>

			<!-- Success/Error Message -->
			<div class="tmpcoder-mailchimp-message">
				<span class="tmpcoder-mailchimp-success-message"><?php echo esc_html($settings['success_message']); ?></span>
				<span class="tmpcoder-mailchimp-error-message"><?php echo esc_html($settings['error_message']); ?></span>
			</div>
		</form>

		<?php
	}
	
}