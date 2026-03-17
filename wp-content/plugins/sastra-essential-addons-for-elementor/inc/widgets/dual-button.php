<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Dual_Button extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-dual-button';
	}

	public function get_title() {
		return esc_html__( 'Dual Button', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-dual-button';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_header') ? [ 'tmpcoder-header-builder-widgets'] : ['tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'dual button', 'double button' ];
	}
	
	public function get_style_depends() {
		return [ 'tmpcoder-button-animations-css', 'tmpcoder-dual-button' ];
	}

    public function get_custom_help_url() {
    	return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_middle_badge() {}
	
	public function add_control_middle_badge_type() {}
	
	public function add_control_middle_badge_text() {}
	
	public function add_control_middle_badge_icon() {}
	
	public function add_section_style_middle_badge() {}
	
	public function add_section_tooltip_a() {}
	
	public function add_section_tooltip_b() {}
	
	public function add_section_style_tooltip() {}

	protected function register_controls() {

		// Section: General ---------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_responsive_control(
			'general_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
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
					],
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-button' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'dual_button_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Button Middle Badge(icon) and<br> Custom Button Toolip</span> options are available in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-dual-button-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->add_control_middle_badge();

		$this->add_control_middle_badge_type();

		$this->add_control_middle_badge_text();

		$this->add_control_middle_badge_icon();

		$this->end_controls_section(); // End Controls Section

		// Section: Button #1 ---------
		$this->start_controls_section(
			'section_button_a',
			[
				'label' => esc_html__( 'First Button', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'button_a_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Button 1',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'button_a_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'default' => [
					'url' => '#link',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'button_a_hover_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-button-animations',
				'default' => 'tmpcoder-button-none',
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'dual-button', 'button_a_hover_animation', ['pro-wnt','pro-rlt','pro-rrt'] );
		
		$this->add_control(
			'button_a_hover_anim_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-a::before' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-a::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-a::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-a .tmpcoder-button-icon-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-a .tmpcoder-button-icon-a svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-a .tmpcoder-button-text-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-a .tmpcoder-button-content-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_control(
			'button_a_hover_animation_height',
			[
				'label' => esc_html__( 'Effect Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [					
					'{{WRAPPER}} [class*="tmpcoder-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} [class*="tmpcoder-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'button_a_hover_animation' => ['tmpcoder-button-underline-from-left','tmpcoder-button-underline-from-center','tmpcoder-button-underline-from-right','tmpcoder-button-underline-reveal','tmpcoder-button-overline-reveal','tmpcoder-button-overline-from-left','tmpcoder-button-overline-from-center','tmpcoder-button-overline-from-right']
				],
			]
		);

		$this->add_control(
			'button_a_hover_animation_text',
			[
				'label' => esc_html__( 'Effect Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go',
				'condition' => [
					'button_a_hover_animation' => ['tmpcoder-button-winona','tmpcoder-button-rayen-left','tmpcoder-button-rayen-right']
				],
			]
		);

		$this->add_responsive_control(
			'button_a_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 180,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-a-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_a_content_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
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
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-content-a' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-button-text-a' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_a_id',
			[
				'label' => esc_html__( 'Button ID', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'sastra-essential-addons-for-elementor' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this button is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'sastra-essential-addons-for-elementor' ),
				'label_block' => false,
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Icon #1 -----------
		$this->start_controls_section(
			'section_icon_a',
			[
				'label' => esc_html__( 'First Button Icon', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'select_icon_a',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_a_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
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
				'prefix_class' => 'tmpcoder-button-icon-a-position-',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_a_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-icon-a' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-button-icon-a svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_a_distance',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-button-icon-a-position-left .tmpcoder-button-icon-a' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-button-icon-a-position-right .tmpcoder-button-icon-a' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltip #1 --------
		$this->add_section_tooltip_a();

		// Section: Button #2 ---------
		$this->start_controls_section(
			'section_button_b',
			[
				'label' => esc_html__( 'Second Button', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'button_b_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button 2',
			]
		);

		$this->add_control(
			'button_b_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label' => esc_html__( 'Link', 'sastra-essential-addons-for-elementor' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'show_label' => false,
				'default' => [
					'url' => '#',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_b_hover_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-button-animations',
				'default' => 'tmpcoder-button-none',
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'dual-button', 'button_b_hover_animation', ['pro-wnt','pro-rlt','pro-rrt'] );
		
		$this->add_control(
			'button_b_hover_anim_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-b::before' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-b::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-b::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-b .tmpcoder-button-icon-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-b .tmpcoder-button-text-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button-b .tmpcoder-button-content-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_control(
			'button_b_hover_animation_height',
			[
				'label' => esc_html__( 'Effect Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [					
					'{{WRAPPER}} [class*="tmpcoder-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} [class*="tmpcoder-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'button_b_hover_animation' => ['tmpcoder-button-underline-from-left','tmpcoder-button-underline-from-center','tmpcoder-button-underline-from-right','tmpcoder-button-underline-reveal','tmpcoder-button-overline-reveal','tmpcoder-button-overline-from-left','tmpcoder-button-overline-from-center','tmpcoder-button-overline-from-right']
				],
			]
		);

		$this->add_control(
			'button_b_hover_animation_text',
			[
				'label' => esc_html__( 'Effect Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go',
				'condition' => [
					'button_b_hover_animation' => ['tmpcoder-button-winona','tmpcoder-button-rayen-left','tmpcoder-button-rayen-right']
				],
			]
		);

		$this->add_responsive_control(
			'button_b_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 180,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-b-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_b_content_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
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
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-content-b' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-button-text-b' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_b_id',
			[
				'label' => esc_html__( 'Button ID', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'sastra-essential-addons-for-elementor' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this button is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'sastra-essential-addons-for-elementor' ),
				'label_block' => false,
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Icon #2 -----------
		$this->start_controls_section(
			'section_icon_b',
			[
				'label' => esc_html__( 'Second Button Icon', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'select_icon_b',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_b_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
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
				'prefix_class' => 'tmpcoder-button-icon-b-position-',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_b_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-icon-b' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-button-icon-b svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_b_distance',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-button-icon-b-position-left .tmpcoder-button-icon-b' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-button-icon-b-position-right .tmpcoder-button-icon-b' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltip #2 --------
		$this->add_section_tooltip_b();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'dual-button', [
			__('Middle Badge Text & Icon options', 'sastra-essential-addons-for-elementor'),
			__('Advanced Tooltip options', 'sastra-essential-addons-for-elementor'),
		] );

		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'general_padding',
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
					'{{WRAPPER}} .tmpcoder-button-a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-button-a::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-button-b' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-button-b::after' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'general_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'general_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-button-text-a,{{WRAPPER}} .tmpcoder-button-a::after,{{WRAPPER}} .tmpcoder-button-text-b,{{WRAPPER}} .tmpcoder-button-b::after',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button #1----------
		$this->start_controls_section(
			'section_style_button_a',
			[
				'label' => esc_html__( 'First Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_a_colors' );

		$this->start_controls_tab(
			'tab_button_a_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_a_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
					'color' => [
						'default' => '#FFFFFF',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-button-a'
			]
		);

		$this->add_control(
			'button_a_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-text-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-icon-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-icon-a svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_a_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-button-a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_a_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_a_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
					'color' => [
						'default' => tmpcoder_elementor_global_colors('primary_color'),
					],
				],
				'selector' => '	{{WRAPPER}} .tmpcoder-button-a[class*="elementor-animation"]:hover,
								{{WRAPPER}} .tmpcoder-button-a::before,
								{{WRAPPER}} .tmpcoder-button-a::after',
			]
		);

		$this->add_control(
			'button_a_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-a:hover .tmpcoder-button-text-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-a::after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-a:hover .tmpcoder-button-icon-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-a:hover .tmpcoder-button-icon-a svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_a_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-button-a:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_a_border',
				'label' => esc_html__( 'Border', 'sastra-essential-addons-for-elementor' ),
				'fields_options' => [
					'border' => [
						'default' => 'solid',
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
					'color' => [
						'default' => tmpcoder_elementor_global_colors('primary_color'),
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-button-a',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_a_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 0,
					'left' => 5,
                    'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button #2----------
		$this->start_controls_section(
			'section_style_button_b',
			[
				'label' => esc_html__( 'Second Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_b_colors' );

		$this->start_controls_tab(
			'tab_button_b_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_b_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
					'color' => [
						'default' => '#FFFFFF',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-button-b'
			]
		);

		$this->add_control(
			'button_b_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-text-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-icon-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-icon-b svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_b_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-button-b',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_b_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_b_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
					'color' => [
						'default' => tmpcoder_elementor_global_colors('primary_color'),
					],
				],
				'selector' => '	{{WRAPPER}} .tmpcoder-button-b[class*="elementor-animation"]:hover,
								{{WRAPPER}} .tmpcoder-button-b::before,
								{{WRAPPER}} .tmpcoder-button-b::after',
			]
		);

		$this->add_control(
			'button_b_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-b:hover .tmpcoder-button-text-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-b::after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-b:hover .tmpcoder-button-icon-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-b:hover .tmpcoder-button-icon-b svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_b_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-button-b:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_b_border',
				'label' => esc_html__( 'Border', 'sastra-essential-addons-for-elementor' ),
				'fields_options' => [
                    'border' => [ // Border type
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => 1,
                            'right' => 1,
                            'bottom' => 1,
                            'left' => 0,
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
						'default' => tmpcoder_elementor_global_colors('primary_color'),
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-button-b',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_b_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 3,
					'bottom' => 3,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-b' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Middle Badge ---------------
		$this->add_section_style_middle_badge();

		// Styles
		// Section: Tooltip ---------
		$this->add_section_style_tooltip();
	
	}

	public function render_pro_element_middle_badge() {}

	public function render_pro_element_tooltip_a() {}

	public function render_pro_element_tooltip_b() {}

	protected function render() {

	$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
	$btn_a_element = 'div';
	$btn_b_element = 'div';
	$btn_a_url =  $settings['button_a_url']['url'];
	$btn_b_url =  $settings['button_b_url']['url'];
	
	?>
	
	<div class="tmpcoder-dual-button">
		<?php if ( (!empty($settings['button_a_text']) && '' !== $settings['button_a_text']) || (!empty($settings['select_icon_a']['value']) && '' !== $settings['select_icon_a']['value']) ) : ?>
		
		<?php 	
		
		$this->add_render_attribute( 'button_a_attribute', 'class', 'tmpcoder-button-a tmpcoder-button-effect '. $settings['button_a_hover_animation'] );
			
		if ( !empty($settings['button_a_hover_animation_text']) && '' !== $settings['button_a_hover_animation_text'] ) {
			$this->add_render_attribute( 'button_a_attribute', 'data-text', $settings['button_a_hover_animation_text'] );
		}	

		if ( '' !== $btn_a_url ) {

			$btn_a_element = 'a';

			$this->add_render_attribute( 'button_a_attribute', 'href', $settings['button_a_url']['url'] );

			if ( $settings['button_a_url']['is_external'] ) {
				$this->add_render_attribute( 'button_a_attribute', 'target', '_blank' );
			}

			if ( $settings['button_a_url']['nofollow'] ) {
				$this->add_render_attribute( 'button_a_attribute', 'nofollow', '' );
			}
		}

		if ( !empty($settings['button_a_id']) && '' !== $settings['button_a_id'] ) {
			$this->add_render_attribute( 'button_a_attribute', 'id', $settings['button_a_id']  );
		}

		?>

		<div class="tmpcoder-button-a-wrap elementor-clearfix">
		<?php echo wp_kses_post('<'. esc_html($btn_a_element).' '.$this->get_render_attribute_string( 'button_a_attribute' ).'>');
        ?>
			
			<span class="tmpcoder-button-content-a">
				<?php if ( !empty($settings['button_a_text']) && '' !== $settings['button_a_text'] ) : ?>
					<span class="tmpcoder-button-text-a"><?php echo esc_html( $settings['button_a_text'] ); ?></span>
				<?php endif; ?>
				
				<?php if ( !empty($settings['select_icon_a']['value']) && '' !== $settings['select_icon_a']['value'] ) : ?>
					<span class="tmpcoder-button-icon-a"><?php \Elementor\Icons_Manager::render_icon( $settings['select_icon_a'] ); ?></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($btn_a_element); ?>>

		<?php $this->render_pro_element_tooltip_a(); ?>

		<?php $this->render_pro_element_middle_badge(); ?>

		</div>

		<?php endif; ?>

		<?php if ( (!empty($settings['button_b_text']) && '' !== $settings['button_b_text']) || (!empty($settings['select_icon_b']['value']) && '' !== $settings['select_icon_b']['value']) ) : ?>
			
		<?php 	
		
		$this->add_render_attribute( 'button_b_attribute', 'class', 'tmpcoder-button-b tmpcoder-button-effect '. $settings['button_b_hover_animation'] );
			
		if ( !empty($settings['button_b_hover_animation_text']) && '' !== $settings['button_b_hover_animation_text'] ) {
			$this->add_render_attribute( 'button_b_attribute', 'data-text', $settings['button_b_hover_animation_text'] );
		}	

		if ( '' !== $btn_b_url ) {

			$btn_b_element = 'a';

			$this->add_render_attribute( 'button_b_attribute', 'href', $settings['button_b_url']['url'] );

			if ( $settings['button_b_url']['is_external'] ) {
				$this->add_render_attribute( 'button_b_attribute', 'target', '_blank' );
			}

			if ( $settings['button_b_url']['nofollow'] ) {
				$this->add_render_attribute( 'button_b_attribute', 'nofollow', '' );
			}
		}

		if ( !empty($settings['button_b_id']) && '' !== $settings['button_b_id'] ) {
			$this->add_render_attribute( 'button_b_attribute', 'id', $settings['button_b_id']  );
		}

		?>

		<div class="tmpcoder-button-b-wrap elementor-clearfix">
		<?php echo wp_kses_post('<'. esc_html($btn_b_element).' '. $this->get_render_attribute_string( 'button_b_attribute' ).'>'); ?>
			
			<span class="tmpcoder-button-content-b">
				<?php if ( !empty($settings['button_b_text']) && '' !== $settings['button_b_text'] ) : ?>
					<span class="tmpcoder-button-text-b"><?php echo esc_html( $settings['button_b_text'] ); ?></span>
				<?php endif; ?>
				
				<?php if ( !empty($settings['select_icon_b']['value']) && '' !== $settings['select_icon_b']['value'] ) : ?>
					<span class="tmpcoder-button-icon-b"><?php \Elementor\Icons_Manager::render_icon( $settings['select_icon_b'] ); ?></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($btn_b_element); ?>>

		<?php $this->render_pro_element_tooltip_b(); ?>
		</div>
	
		<?php endif; ?>
	</div>
	<?php

	}
}