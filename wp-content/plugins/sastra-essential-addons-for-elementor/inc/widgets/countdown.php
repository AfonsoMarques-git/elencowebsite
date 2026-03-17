<?php
namespace TMPCODER\Widgets;
use Elementor;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Countdown extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-countdown';
	}

	public function get_title() {
		return esc_html__( 'Countdown', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-countdown';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'evergreen countdown timer' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

    public function get_script_depends() {
		return ['tmpcoder-countdown'];
	}

	public function get_style_depends() {
		return ['tmpcoder-countdown'];
	}

	public function add_control_countdown_type() {
		$this->add_control(
			'countdown_type',
			[
				'label' => esc_html__( 'Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'due-date',
				'options' => [
					'due-date' => esc_html__( 'Due Date', 'sastra-essential-addons-for-elementor' ),
					'pro-eg' => esc_html__( 'Evergreen (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);
	}

	public function add_control_evergreen_hours() {}

	public function add_control_evergreen_minutes() {}

	public function add_control_evergreen_show_again_delay() {}

	public function add_control_evergreen_stop_after_date() {
		$this->add_control(
			'evergreen_stop_after_date',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Stop Showing after Date %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);	
	}

	public function add_control_evergreen_stop_after_date_select() {}

	protected function register_controls() {
		
		// Section: Countdown --------
		$this->start_controls_section(
			'section_countdown',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'countdown_apply_changes',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-update-preview editor-tmpcoder-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
				'separator' => 'after'
			]
		);

		$this->add_control_countdown_type();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'countdown', 'countdown_type', ['pro-eg'] );

		$due_date_default = gmdate(
			'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS )
		);

		$this->add_control(
			'due_date',
			[
				'label' => esc_html__( 'Due Date', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => $due_date_default,
				'description' => sprintf(
					// Translators: %s is timezon.
					esc_html__( 'Date set according to your timezone: %s.', 'sastra-essential-addons-for-elementor' ),
					Utils::get_timezone_string()
				),
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
				'condition' => [
					'countdown_type' => 'due-date',
				],
			]
		);

		$this->add_control_evergreen_hours();

		$this->add_control_evergreen_minutes();

		$this->add_control_evergreen_show_again_delay();

		$this->add_control_evergreen_stop_after_date();

		$this->add_control_evergreen_stop_after_date_select();

		$this->end_controls_section(); // End Controls Section

		// Section: Countdown --------
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'show_days',
			[
				'label' => esc_html__( 'Show Days', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_hours',
			[
				'label' => esc_html__( 'Show Hours', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_minutes',
			[
				'label' => esc_html__( 'Show Minutes', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_seconds',
			[
				'label' => esc_html__( 'Show Seconds', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => esc_html__( 'Show Labels', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'labels_position',
			[
				'label' => esc_html__( 'Display', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'block' => esc_html__( 'Block', 'sastra-essential-addons-for-elementor' ),
					'inline' => esc_html__( 'Inline', 'sastra-essential-addons-for-elementor' ),
				],
				'selectors_dictionary' => [
					'inline' => 'inline-block',
					'block' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-number' => 'display: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-countdown-label' => 'display: {{VALUE}}',
				],
				'separator' => 'after',	
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_days_singular',
			[
				'label' => esc_html__( 'Day', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Day',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_days_plural',
			[
				'label' => esc_html__( 'Days', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Days',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_hours_singular',
			[
				'label' => esc_html__( 'Hour', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Hour',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_hours_plural',
			[
				'label' => esc_html__( 'Hours', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Hours',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_minutes_singular',
			[
				'label' => esc_html__( 'Minute', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Minute',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_minutes_plural',
			[
				'label' => esc_html__( 'Minutes', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Minutes',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_seconds_plural',
			[
				'label' => esc_html__( 'Seconds', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Seconds',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label' => esc_html__( 'Show Separators', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Countdown --------
		$this->start_controls_section(
			'section_actions',
			[
				'label' => esc_html__( 'Expire Actions', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'timer_actions',
			[
				'label' => esc_html__( 'Actions After Timer Expires', 'sastra-essential-addons-for-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'restart-timer' => esc_html__( 'Restart Timer', 'sastra-essential-addons-for-elementor' ),
					'hide-timer' => esc_html__( 'Hide Timer', 'sastra-essential-addons-for-elementor' ),
					'hide-element' => esc_html__( 'Hide Element', 'sastra-essential-addons-for-elementor' ),
					'message' => esc_html__( 'Display Message', 'sastra-essential-addons-for-elementor' ),
					'redirect' => esc_html__( 'Redirect', 'sastra-essential-addons-for-elementor' ),
					'load-template' => esc_html__( 'Load Template', 'sastra-essential-addons-for-elementor' ),
				],
				'multiple' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hide_element_selector',
			[
				'label' => esc_html__( 'CSS Selector to Hide Element', 'sastra-essential-addons-for-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'separator' => 'before',
				'condition' => [
					'timer_actions' => 'hide-element',
				],
			]
		);

		$this->add_control(
			'display_message_text',
			[
				'label' => esc_html__( 'Display Message', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => '',
				'separator' => 'before',
				'condition' => [
					'timer_actions' => 'message',
				],
			]
		);

		$this->add_control(
			'redirect_url',
			[
				'label' => esc_html__( 'Redirect URL', 'sastra-essential-addons-for-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'separator' => 'before',
				'condition' => [
					'timer_actions' => 'redirect',
				],
			]
		);

		$this->add_control(
			'load_template' ,
			[
				'label' => esc_html__( 'Select Template', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'separator' => 'before',
				'condition' => [
					'timer_actions' => 'load-template',
				],
			]
		);

		// Restore original Post Data
		wp_reset_postdata();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'countdown', [
			__('Evergreen Timer - User Specific Timer', 'sastra-essential-addons-for-elementor'),
			__('An evergreen countdown timer is used to display the amount of time a particular user has to avail the offer. This is a great way to create a feeling of scarcity, urgency and exclusivity', 'sastra-essential-addons-for-elementor'),
		] );

		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'general_bg_color',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#5729d9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-countdown-item'
			]
		);

		$this->add_responsive_control(
			'general_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 800,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'general_gutter',
			[
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-item' => 'margin-left: calc({{SIZE}}px/2);margin-right: calc({{SIZE}}px/2);',
				],
			]
		);

		$this->add_responsive_control(
			'general_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
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
				'selector' => '{{WRAPPER}} .tmpcoder-countdown-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
	        'general_box_shadow_divider',
	        [
	            'type' => Controls_Manager::DIVIDER,
	            'style' => 'thick',
	        ]
	    );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'general_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-countdown-item',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .tmpcoder-countdown-item' => 'text-align: {{VALUE}}',
				],
				'separator' => 'after'
            ]
        );

		$this->add_control(
			'numbers_color',
			[
				'label' => esc_html__( 'Numbers Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-number' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'numbers_bg_color',
			[
				'label' => esc_html__( 'Numbers Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-number' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'numbers_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-countdown-number',
			]
		);

		$this->add_responsive_control(
			'numbers_padding',
			[
				'label' => esc_html__( 'Numbers Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 40,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'labels_color',
			[
				'label' => esc_html__( 'Labels Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-label' => 'color: {{VALUE}};',
				],
	            'separator' => 'before'
			]
		);

		$this->add_control(
			'labels_bg_color',
			[
				'label' => esc_html__( 'Labels Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-label' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'labels_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-countdown-label',
			]
		);

		$this->add_responsive_control(
			'labels_padding',
			[
				'label' => esc_html__( 'Labels Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Separator Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-separator span' => 'background: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator_size',
			[
				'label' => esc_html__( 'Separator Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-separator span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_separator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'separator_margin',
			[
				'label' => esc_html__( 'Dots Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-separator span:first-child' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'separator_circle',
			[
				'label' => esc_html__( 'Separator Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'yes' => '50%;',
					'' => 'none'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-separator span' => 'border-radius: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Content ----------
		$this->start_controls_section(
			'section_style_message',
			[
				'label' => esc_html__( 'Message', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'timer_actions' => 'message',
				],
			]
		);

		$this->add_responsive_control(
            'message_align',
            [
                'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}} .tmpcoder-countdown-message' => 'text-align: {{VALUE}}',
				],
            ]
        );

		$this->add_control(
			'message_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-message' => 'color: {{VALUE}};',
				],
	            'separator' => 'before'
			]
		);

		$this->add_control(
			'message_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-message' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-countdown-message',
			]
		);

		$this->add_responsive_control(
			'message_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'message_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-countdown-message' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	public function get_due_date_interval( $date ) {
		return strtotime( $date ) - ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
	}

	public function get_evergreen_interval( $settings ) {
		return  '0';
	}

	public function get_countdown_attributes( $settings ) {
		if ( ! tmpcoder_is_availble() ) {
			$settings['countdown_type'] = 'due-date';
			$settings['evergreen_show_again_delay'] = '0';
		}

		$atts  = ' data-type="'. esc_attr( $settings['countdown_type'] ) .'"';
		$atts .= ' data-show-again="'. esc_attr( $settings['evergreen_show_again_delay'] ) .'"';

		$atts .= ' data-actions="'. esc_attr( $this->get_expired_actions_json( $settings ) ) .'"';

		// Interval
		if ( 'evergreen' === $settings['countdown_type'] ) {
			$atts .= ' data-interval="'. esc_attr( $this->get_evergreen_interval( $settings ) ) .'"';
		} else {
			$atts .= ' data-interval="'. esc_attr( $this->get_due_date_interval( $settings['due_date'] ) ) .'"';
		}

		return $atts;
	}

	public function get_countdown_class( $settings ) {
		if ( ! tmpcoder_is_availble() ) {
			$settings['evergreen_stop_after_date'] = '';
			$settings['evergreen_stop_after_date_select'] = '';
		}

		$class = 'tmpcoder-countdown-wrap elementor-clearfix';

		if ( 'yes' === $settings['evergreen_stop_after_date'] ) {
			$current_time = intval(strtotime('now') + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);

			if ( $current_time > strtotime( $settings['evergreen_stop_after_date_select'] ) ) {
				$class = ' tmpcoder-hidden-element';
			}
		}

		return $class;
	}

	public function get_expired_actions_json( $settings ) {
		$actions = [];

		$allowed_html = [
			'a' => [
				'href' => [],
				'title' => [],
				'target' => [],
			],
			'h1' => [],
			'h2' => [],
			'h3' => [],
			'h4' => [],
			'h5' => [],
			'h6' => [],
			'b' => [],
			'strong' => [],
			'i' => [],
			'em' => [],
			'p' => [],
			'br' => [],
			'ul' => [],
			'ol' => [],
			'li' => [],
			'span' => [],
			'div' => [
				'class' => [],
			],
			'img' => [
				'src' => [],
				'alt' => [],
				'width' => [],
				'height' => [],
			],
			// Add more allowed tags and attributes as needed
		];

		if ( ! empty( $settings['timer_actions'] ) ) {
			foreach( $settings['timer_actions'] as $key => $value ) {
				switch ( $value ) {
					case 'restart-timer':
						$actions['restart-timer'] = true;
						break;
					case 'hide-timer':
						$actions['hide-timer'] = '';
						break;

					case 'hide-element':
						$actions['hide-element'] = $settings['hide_element_selector'];
						break;

					case 'message':
						$actions['message'] = wp_kses_post($settings['display_message_text'], $allowed_html);
						break;

					case 'redirect':
						$actions['redirect'] = esc_url($settings['redirect_url']) ? esc_url($settings['redirect_url']) : '#';
						break;

					case 'load-template':
						$actions['load-template'] = $settings['load_template'];
						break;
				}
			}
		}

		return wp_json_encode( $actions );
	}

	public function render_countdown_item( $settings, $item ) {
		$html = '<div class="tmpcoder-countdown-item">';
			$html .= '<span class="tmpcoder-countdown-number tmpcoder-countdown-'. esc_attr($item) .'" data-item="'. esc_attr($item) .'"></span>';

			if ( 'yes' === $settings['show_labels'] ) {
				if ( 'seconds' !== $item ) {
					$labels = [
						'singular' => $settings['labels_'. $item .'_singular'],
						'plural' => $settings['labels_'. $item .'_plural']
					];

					$html .= '<span class="tmpcoder-countdown-label" data-text="'. esc_attr(wp_json_encode( $labels )) .'">'. esc_html($settings['labels_'. $item .'_plural']) .'</span>';
				} else {
					$html .= '<span class="tmpcoder-countdown-label">'. esc_html($settings['labels_'. $item .'_plural']) .'</span>';
				}
			}
		$html .= '</div>';

		if ( $settings['show_separator'] ) {
			$html .= '<span class="tmpcoder-countdown-separator"><span></span><span></span></span>';
		}

		echo wp_kses($html, tmpcoder_wp_kses_allowed_html());
	}

	public function render_countdown_items( $settings ) {
		// Days
		if ( $settings['show_days'] ) {
			$this->render_countdown_item( $settings, 'days' );
		}

		// Hours
		if ( $settings['show_hours'] ) {
			$this->render_countdown_item( $settings, 'hours' );
		}

		// Minutes
		if ( $settings['show_minutes'] ) {
			$this->render_countdown_item( $settings, 'minutes' );
		}

		// Seconds
		if ( $settings['show_seconds'] ) {
			$this->render_countdown_item( $settings, 'seconds' );
		}
	}

	public function load_elementor_template( $settings ) {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}

		if ( ! empty( $settings['timer_actions'] ) && ! in_array( 'redirect', $settings['timer_actions'] ) ) {
			if ( in_array( 'load-template', $settings['timer_actions'] ) ) {
				// Load Elementor Template
				$load_template = \Elementor\Plugin::instance()->frontend->get_builder_content( $settings['load_template'], false ); 
				echo wp_kses($load_template, tmpcoder_wp_kses_allowed_html());
			}
		}
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		
		// Render
		echo '<div class="'. esc_attr($this->get_countdown_class( $settings )) .'"'. $this->get_countdown_attributes( $settings ) .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$this->render_countdown_items( $settings );
		echo '</div>';

		// Load Template
		$this->load_elementor_template( $settings );
	}
}