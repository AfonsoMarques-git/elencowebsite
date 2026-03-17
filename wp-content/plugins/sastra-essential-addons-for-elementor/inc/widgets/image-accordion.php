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
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Image_Accordion extends Widget_Base {

	public $item_bg_image_url;

	public function get_name() {
		return 'tmpcoder-image-accordion';
	}

	public function get_title() {
		return esc_html__( 'Image Accordion', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-accordion';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'image accordion' ];
	}

	public function get_script_depends() {

        $depends = [ 'tmpcoder-lightgallery' => true, 'tmpcoder-image-accordion' => true];

		if ( ! tmpcoder_elementor()->preview->is_preview_mode() ) {
			$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

			$filtered = array_filter($settings['accordion_elements'], function($element) {
			    return isset($element['element_select']) && $element['element_select'] === 'lightbox';
			});

			if (!$filtered) {
				unset( $depends['tmpcoder-lightgallery'] );	
			}
		}

		return array_keys($depends);
	}

	public function get_style_depends() {

		$depends = [ 'tmpcoder-animations-css' => true, 'tmpcoder-link-animations-css' => true, 'tmpcoder-button-animations-css' => true, 'tmpcoder-lightgallery-css' => true, 'tmpcoder-image-accordion' => true];

		if ( ! tmpcoder_elementor()->preview->is_preview_mode() ) {
			$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

			$filtered = array_filter($settings['accordion_elements'], function($element) {
			    return isset($element['element_select']) && $element['element_select'] === 'lightbox';
			});

			if (!$filtered) {
				unset( $depends['tmpcoder-lightgallery-css'] );	
			}
		}

		return array_keys($depends);
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_section_lightbox_popup() {}

	public function add_section_lightbox_styles() {}

	public function add_control_accordion_direction() {
		$this->add_responsive_control(
			'accordion_direction',
			[
				'label' => esc_html__( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'row',
				'options' => [
					'row' => esc_html__('Horizontal', 'sastra-essential-addons-for-elementor'),
					'pro-cl' => esc_html__('Vertical (Pro)', 'sastra-essential-addons-for-elementor'), 
				],
				'prefix_class' => 'tmpcoder-image-accordion-',
				'default' => 'row',
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-image-accordion-row .tmpcoder-image-accordion-wrap .tmpcoder-image-accordion' => 'flex-direction: {{VALUE}};',
					'{{WRAPPER}}.tmpcoder-image-accordion-column .tmpcoder-image-accordion-wrap .tmpcoder-image-accordion' => 'flex-direction: {{VALUE}};',
				]
			]
		);
	}

	public function add_control_accordion_interaction() {
		$this->add_control(
			'accordion_interaction',
			[
				'label' => esc_html__('Interaction', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'hover' => esc_html__('Hover', 'sastra-essential-addons-for-elementor'),
					'pro-ck' => esc_html__('Click (Pro)', 'sastra-essential-addons-for-elementor'),
				],
				'render_type' => 'template',
				'default'  => 'hover',
				'prefix_class'  => 'tmpcoder-image-accordion-interaction-', 
			]
		);
	}

	public function add_control_accordion_skew() {
		$this->add_control(
			'accordion_skew',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Skew Images %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}
	
	public function add_control_image_effects() {
		$this->add_control(
			'image_effects',
			[
				'label' => esc_html__( 'Select Effect', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'pro-zi' => esc_html__( 'Zoom In (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-zo' => esc_html__( 'Zoom Out (Pro)', 'sastra-essential-addons-for-elementor' ),
					'grayscale-in' => esc_html__( 'Grayscale In', 'sastra-essential-addons-for-elementor' ),
					'pro-go' => esc_html__( 'Grayscale Out (Pro)', 'sastra-essential-addons-for-elementor' ),
					'blur-in' => esc_html__( 'Blur In', 'sastra-essential-addons-for-elementor' ),
					'pro-bo' => esc_html__( 'Blur Out (Pro)', 'sastra-essential-addons-for-elementor' ),
					'slide' => esc_html__( 'Slide', 'sastra-essential-addons-for-elementor' )
				],
				'default' => 'none',
			]
		);
	}
	
	public function add_control_overlay_color() {
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_color',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#3E3636DE',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-img-accordion-hover-bg'
			]
		);
	}
	
	public function add_control_overlay_blend_mode() {}

	public function add_option_element_select() {
		return [
			'title' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
			'description' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
			'pro-lbx' => esc_html__( 'Lightbox (Pro)', 'sastra-essential-addons-for-elementor' ),
			'button' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
			'separator' => esc_html__( 'Separator', 'sastra-essential-addons-for-elementor' ),
		];
	}
	
	public function add_control_button_animation() {
		$this->add_control(
			'button_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-button-animations',
				'default' => 'tmpcoder-button-none',
			]
		);
	}
	
	public function add_control_button_animation_height() {
		$this->add_control(
			'button_animation_height',
			[
				'label' => esc_html__( 'Animation Height', 'sastra-essential-addons-for-elementor' ),
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
				'render_type' => 'template',
				'condition' => [
					'button_animation' => [ 
						'tmpcoder-button-underline-from-left',
						'tmpcoder-button-underline-from-center',
						'tmpcoder-button-underline-from-right',
						'tmpcoder-button-underline-reveal',
						'tmpcoder-button-overline-reveal',
						'tmpcoder-button-overline-from-left',
						'tmpcoder-button-overline-from-center',
						'tmpcoder-button-overline-from-right'
					]
				],
			]
		);
	}
	
	public function add_control_lightbox_popup_thumbnails() {}
	
	public function add_control_lightbox_popup_thumbnails_default() {}
	
	public function add_control_lightbox_popup_sharing() {}

	public function add_repeater_args_element_align_hr() {
		return [
			'label' => esc_html__( 'Horizontal Align', 'sastra-essential-addons-for-elementor' ),
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
				'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: center'
			],
			'render_type' => 'template',
			'separator' => 'after'
		];
	}

    public function register_controls() {

		// Section: Accordion Options ---
		$this->start_controls_section(
			'accordion_settings',
			[
				'label' => esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_accordion_direction();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-accordion', 'accordion_direction', ['pro-cl'] );

		$this->add_control_accordion_interaction();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-accordion', 'accordion_interaction', ['pro-ck'] );

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'accordion_image_size',
				'default' => 'full',
			]
		);

		$this->add_control(
			'default_active',
			[
				'label' => __( 'Active Image By Default', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
			]
		);

		$this->add_responsive_control(
			'accordion_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1500,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-image-accordion' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'accordion_active_item_style',
			[
				'label' => esc_html__( 'Grow (Active)', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],				
				'default' => [
					'size' => 4,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-image-accordion-wrap .tmpcoder-image-accordion-item.tmpcoder-image-accordion-item-grow' => 'flex: {{SIZE}};',
				]
			]
		);

		$this->add_responsive_control(
			'accordion_items_spacing',
			[
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 0,
					'units' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100
					]
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-image-accordion-row .tmpcoder-image-accordion-item:not(:last-child)' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}}.tmpcoder-image-accordion-column .tmpcoder-image-accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}px;'
				]
			]
		);

		$this->add_responsive_control(
			'accordion_item_border',
			[
				'label'       => esc_html__( 'Border Type', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'outer'   => esc_html__( 'Outer', 'sastra-essential-addons-for-elementor' ),
					'individual' => esc_html__( 'Individual', 'sastra-essential-addons-for-elementor' )
				],
				'default'     => 'outer',
				'prefix_class' => 'tmpcoder-acc-border-',
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'accordion_item_border_radius',
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
					'{{WRAPPER}}.tmpcoder-acc-border-outer.tmpcoder-image-accordion-row .tmpcoder-image-accordion-item:first-child' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-acc-border-outer.tmpcoder-image-accordion-row .tmpcoder-image-accordion-item:last-child' => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
					'{{WRAPPER}}.tmpcoder-acc-border-outer.tmpcoder-image-accordion-column .tmpcoder-image-accordion-item:first-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}}.tmpcoder-acc-border-outer.tmpcoder-image-accordion-column .tmpcoder-image-accordion-item:last-child' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-acc-border-individual .tmpcoder-image-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control_accordion_skew();

		$this->add_control(
			'accordion_item_transition',
			[
				'label' => esc_html__( 'Grow Transition', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-image-accordion-item' => 'transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-image-accordion-item .tmpcoder-accordion-background' => 'transition-duration: {{VALUE}}s;'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_accordion_items',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'accordion_item_bg_image',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'render_type' => 'template',
				'default' => [
					'url' => TMPCODER_ADDONS_ASSETS_URL . 'images/according-image-1.jpg',
					'id' => 0,
				],
			]
		);

		$repeater->add_responsive_control(
			'bg_image_size',
			[
				'label'       => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'cover'   => esc_html__( 'Cover', 'sastra-essential-addons-for-elementor' ),
					'contain' => esc_html__( 'Contain', 'sastra-essential-addons-for-elementor' ),
					'auto'    => esc_html__( 'Auto', 'sastra-essential-addons-for-elementor' ),
				],
				'default'     => 'cover',
				'selectors'   => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.tmpcoder-image-accordion-item .tmpcoder-accordion-background' => 'background-size: {{VALUE}}',
				]
			]
		);

		$repeater->add_responsive_control(
			'bg_image_position',
			[
				'label'       => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'center center' => esc_html__( 'Center Center', 'sastra-essential-addons-for-elementor' ),
					'center left'   => esc_html__( 'Center Left', 'sastra-essential-addons-for-elementor' ),
					'center right'  => esc_html__( 'Center Right', 'sastra-essential-addons-for-elementor' ),
					'top center'    => esc_html__( 'Top Center', 'sastra-essential-addons-for-elementor' ),
					'top left'      => esc_html__( 'Top Left', 'sastra-essential-addons-for-elementor' ),
					'top right'     => esc_html__( 'Top Right', 'sastra-essential-addons-for-elementor' ),
					'bottom center' => esc_html__( 'Bottom Center', 'sastra-essential-addons-for-elementor' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'sastra-essential-addons-for-elementor' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'sastra-essential-addons-for-elementor' ),
				],
				'default'     => 'center center',
				'selectors'   => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.tmpcoder-image-accordion-item .tmpcoder-accordion-background' => 'background-position: {{VALUE}}',
				]
			]
		);

		$repeater->add_responsive_control(
			'bg_image_repeat',
			[
				'label'       => esc_html__( 'Repeat', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'repeat'    => esc_html__( 'Repeat', 'sastra-essential-addons-for-elementor' ),
					'no-repeat' => esc_html__( 'No-repeat', 'sastra-essential-addons-for-elementor' ),
					'repeat-x'  => esc_html__( 'Repeat-x', 'sastra-essential-addons-for-elementor' ),
					'repeat-y'  => esc_html__( 'Repeat-y', 'sastra-essential-addons-for-elementor' ),
				],
				'default'     => 'repeat',
				'selectors'   => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.tmpcoder-image-accordion-item .tmpcoder-accordion-background' => 'background-repeat: {{VALUE}}',
				]
			]
		);


		$repeater->add_control(
			'accordion_item_title', [
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Item 1 Title' , 'sastra-essential-addons-for-elementor' ),
				'label_block' => true,
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'accordion_item_description', [
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Lorem ipsum dolos ave nita' , 'sastra-essential-addons-for-elementor' ),
				'label_block' => true
			]
		);

		$repeater->add_control(
			'element_button_text',
			[
				'label' => esc_html__( 'Button Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button'
			]
		);

		$repeater->add_control(
			'accordion_btn_url',
			[
				'label' => esc_html__( 'Button URL', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				]
			]
		);
		
		$repeater->add_control(
			'wrapper_link',
			[
				'label' => esc_html__( 'Use Button URL as Image Link', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_block' => false,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'accordion_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						
						'accordion_item_title' => esc_html__( 'Item 1 Title', 'sastra-essential-addons-for-elementor' ),
						'accordion_item_bg_image' =>[
							'url' => TMPCODER_ADDONS_ASSETS_URL . 'images/according-image-1.jpg',
						],
					],
					[
						
						'accordion_item_title' => esc_html__( 'Item 2 Title', 'sastra-essential-addons-for-elementor' ),
						'accordion_item_bg_image' =>[
							'url' => TMPCODER_ADDONS_ASSETS_URL . 'images/according-image-2.jpg',
						],
					],
					[
						
						'accordion_item_title' => esc_html__( 'Item 3 Title', 'sastra-essential-addons-for-elementor' ),
						'accordion_item_bg_image' =>[
							'url' => TMPCODER_ADDONS_ASSETS_URL . 'images/according-image-3.jpg',
						],
					],
				],
				'title_field' => '{{{ accordion_item_title }}}',
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'accordion_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 3 Items are available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-image-accordion-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_accordion_elements',
			[
				'label' => esc_html__( 'Elements', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => $this->add_option_element_select(),
				'separator' => 'after'
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_select', ['pro-lbx'] );

		$repeater->add_control(
			'element_display',
			[
				'label' => esc_html__( 'Display', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'inline' => esc_html__( 'Inline', 'sastra-essential-addons-for-elementor' ),
					'block' => esc_html__( 'Seperate Line', 'sastra-essential-addons-for-elementor' ),
					'custom' => esc_html__( 'Custom Width', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);
 
		$repeater->add_control(
			'element_custom_width',
			[
				'label' => esc_html__( 'Element Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'element_display' => 'custom',
				],
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$repeater->add_control(
	            'element_align_pro_notice',
	            [
					'raw' => 'Vertical and Horizontal Align options are available in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-image-accordion-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'tmpcoder-pro-notice',
				]
	        );
		}

		$repeater->add_control(
			'element_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'sastra-essential-addons-for-elementor' ),
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
				]
			]
		);

		$repeater->add_control( 'element_align_hr', $this->add_repeater_args_element_align_hr() );

		$repeater->add_control(
			'element_title_tag',
			[
				'label' => esc_html__( 'Text HTML Tag', 'sastra-essential-addons-for-elementor' ),
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
				'default' => 'h2',
				'condition' => [
					'element_select' => 'title'
				]
			]
		);

		$repeater->add_control(
			'element_lightbox_icon',
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
					'element_select' => 'lightbox'
				]
			]
		);

		$repeater->add_control(
			'element_extra_text_pos',
			[
				'label' => esc_html__( 'Extra Text Display', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'before' => esc_html__( 'Before Element', 'sastra-essential-addons-for-elementor' ),
					'after' => esc_html__( 'After Element', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'description',
						'button',
						'separator',
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'condition' => [
					'element_select!' => [
						'title',
						'description',
						'button',
						'separator',
					],
					'element_extra_text_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon_pos',
			[
				'label' => esc_html__( 'Extra Icon Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'before' => esc_html__( 'Before Element', 'sastra-essential-addons-for-elementor' ),
					'after' => esc_html__( 'After Element', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'separator',
						'description',
						'lightbox'
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon',
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
					'element_select!' => [
						'title',
						'separator',
						'description',
						'lightbox'
					],
					'element_extra_icon_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'element_select' => [
						'button',
						'lightbox'
					],
				]
			]
		);


		$repeater->add_control(
			'element_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-animations',
				'default' => 'fade-in'
			]
		);

		// Upgrade to Pro Notice :TODO
		tmpcoder_upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		$repeater->add_control(
			'element_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none'
				],
			]
		);

		$repeater->add_control(
			'element_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-animation-wrap:hover {{CURRENT_ITEM}}' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none'
				],
			]
		);

		$repeater->add_control(
			'element_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => tmpcoder_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'element_animation!' => 'none'
				],
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_animation_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

		$repeater->add_control(
			'element_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'sastra-essential-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
					'large' => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'large',
				'condition' => [
					'element_animation!' => 'none'
				],
			]
		);

		$repeater->add_control(
			'element_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none'
				],
			]
		);

		$repeater->add_responsive_control(
			'element_show_on',
			[
				'label' => esc_html__( 'Show on this Device', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'position: absolute; left: -99999999px;',
					'yes' => 'position: static; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'accordion_elements',
			[
				'label' => esc_html__( 'Accordion Elements', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => 'title',
					],
					[
						'element_select' => 'description',
						'element_display' => 'inline',
					],
					[
						'element_select' => 'button',
					],
				],
				'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
				'prevent_empty' => false,
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Tab: Content ==============
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'media_overlay_on_off',
			[
				'label' => esc_html__( 'Media Overlay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);	

		$this->add_responsive_control(
			'overlay_width',
			[
				'label' => esc_html__( 'Overlay Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
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
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
				'condition' => ['media_overlay_on_off' => 'yes']
			]
		);

		$this->add_responsive_control(
			'overlay_height',
			[
				'label' => esc_html__( 'Overlay Hegiht', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
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
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
				'condition' => ['media_overlay_on_off' => 'yes']
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-animations-alt',
				'default' => 'fade-in',
				'condition' => ['media_overlay_on_off' => 'yes']
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-accordion', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		$this->add_control(
			'overlay_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
					'media_overlay_on_off' => 'yes'
				],
			]
		);

		$this->add_control(
			'overlay_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-animation-wrap:hover .tmpcoder-img-accordion-hover-bg' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
					'media_overlay_on_off' => 'yes'
				],
			]
		);

		$this->add_control(
			'overlay_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => tmpcoder_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'overlay_animation!' => 'none',
					'media_overlay_on_off' => 'yes'
				],
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image accordion', 'overlay_animation_timing', tmpcoder_animation_timing_pro_conditions());

		$this->add_control(
			'overlay_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'sastra-essential-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
					'large' => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'large',
				'condition' => [
					'overlay_animation!' => 'none',
					'media_overlay_on_off' => 'yes'
				],
			]
		);

		$this->add_control(
			'overlay_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'overlay_animation!' => 'none',
					'media_overlay_on_off' => 'yes'
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Image Effects ----
		$this->start_controls_section(
			'section_image_effects',
			[
				'label' => esc_html__( 'Image Effects', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_image_effects();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-accordion', 'image_effects', ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'] );

		$this->add_control(
			'image_effects_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-image-accordion-item .tmpcoder-accordion-background' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-image-accordion-item:hover>div' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => tmpcoder_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-accordion', 'image_effects_animation_timing', tmpcoder_animation_timing_pro_conditions());

		$this->add_control(
			'image_effects_size',
			[
				'label' => esc_html__( 'Animation Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'sastra-essential-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
					'large' => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'medium',
				'condition' => [
					'image_effects!' => ['none', 'slide'],
				]
			]
		);

		$this->add_control(
			'image_effects_direction',
			[
				'label' => esc_html__( 'Animation Direction', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'Top', 'sastra-essential-addons-for-elementor' ),
					'right' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
					'bottom' => esc_html__( 'Bottom', 'sastra-essential-addons-for-elementor' ),
					'left' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'bottom',
				'condition' => [
					'image_effects!' => 'none',
					'image_effects' => 'slide'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->add_section_lightbox_popup();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'image-accordion', [
			'Add Unlimited Images.',
			'Vertical Accordion Layout.',
			'Trigger Images on Click.',
			'Skew Images by default.',
			'Enable Image Lightbox',
			'Advanced Elements Positioning',
			'Image Effects: Zoom, Grayscale, Blur',
			'Image Overlay Blend Mode',
		] );
		
		// Styles ====================
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => ['media_overlay_on_off' => 'yes']
			]
		);
		
		$this->add_control_overlay_color();

		$this->add_control_overlay_blend_mode();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_box_shadow_1',
				'selector' => '{{WRAPPER}} .tmpcoder-image-accordion-item',
			]
		);

		$this->add_control(
			'overlay_radius',
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
					'{{WRAPPER}} .tmpcoder-img-accordion-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);


		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-title .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-title .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-title .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-img-accordion-item-title a'
			]
		);

		$this->add_control(
			'title_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-title .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-img-accordion-item-title .tmpcoder-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-img-accordion-item-title .tmpcoder-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'title_border_type',
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
					'{{WRAPPER}} .tmpcoder-img-accordion-item-title .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_border_width',
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
					'{{WRAPPER}} .tmpcoder-img-accordion-item-title .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'title_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-title .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-title .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Description ----------
		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DDDDDD',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-description .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'description_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-description .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'description_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-description .inner-block' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-img-accordion-item-description'
			]
		);

		$this->add_responsive_control(
			'description_width',
			[
				'label' => esc_html__( 'Description Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-description .inner-block' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_border_type',
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
					'{{WRAPPER}} .tmpcoder-img-accordion-item-description .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_border_width',
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
					'{{WRAPPER}} .tmpcoder-img-accordion-item-description .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'description_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'description_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-description .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-description .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Section: Accordion Title ---
		$this->start_controls_section(
			'accordion_button_styles',
			[
				'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_accordion_button_style' );

		$this->start_controls_tab(
			'tab_accordion_button_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_color',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#5729d9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a'
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-img-accordion-item-button a'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_accordion_button_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_color_hr',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '	{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a:hover'
			]
		);

		$this->add_control(
			'button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_hr',
				'selector' => '{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block :hover a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control_button_animation();

		$this->add_control(
			'button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control_button_animation_height();

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
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_width',
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
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'button_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .tmpcoder-img-accordion-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .tmpcoder-img-accordion-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 7,
					'right' => 18,
					'bottom' => 8,
					'left' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'button_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-img-accordion-item-button .inner-block a:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->add_section_lightbox_styles();

		// Styles ====================
		// Section: Separator Style 2 
		$this->start_controls_section(
			'section_style_separator2',
			[
				'label' => esc_html__( 'Separator', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator2_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-sep-style-2 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator2_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-sep-style-2:not(.tmpcoder-img-accordion-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-img-accordion-sep-style-2.tmpcoder-img-accordion-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator2_height',
			[
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-sep-style-2 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_border_type',
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
					'{{WRAPPER}} .tmpcoder-img-accordion-sep-style-2 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator2_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-img-accordion-sep-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_radius',
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
					'{{WRAPPER}} .tmpcoder-img-accordion-sep-style-2 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
    }

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $item ) {
		$locations = [];

		foreach ( $settings['accordion_elements'] as $key => $data ) {
			$place = 'over';
			$align_vr = $data['element_align_vr'];

			if ( ! tmpcoder_is_availble() ) {
				$align_vr = 'middle';
			}

			if ( ! isset($locations[$place]) ) {
				$locations[$place] = [];
			}
			
			if ( 'over' === $place ) {
				if ( ! isset($locations[$place][$align_vr]) ) {
					$locations[$place][$align_vr] = [];
				}

				array_push( $locations[$place][$align_vr], $data );
			} else {
				array_push( $locations[$place], $data );
			}
		}

		if ( ! empty( $locations[$location] ) ) {

			if ( 'over' === $location ) {
				foreach ( $locations[$location] as $align => $elements ) {

					if ( 'middle' === $align ) {
						echo '<div class="tmpcoder-cv-container"><div class="tmpcoder-cv-outer"><div class="tmpcoder-cv-inner">';
					}

					echo wp_kses_post('<div class="tmpcoder-img-accordion-media-hover-'. $align .' elementor-clearfix">');
						foreach ( $elements as $data ) {
							
							// Get Class
							$class  = 'tmpcoder-img-accordion-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' tmpcoder-img-accordion-item-display-'. $data['element_display'];
							if  ( !tmpcoder_is_availble() ) {
								$class .= ' tmpcoder-img-accordion-item-align-center';
							} else {
								$class .= ' tmpcoder-img-accordion-item-align-'. $data['element_align_hr'];
							}
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $item );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} 

		}
	}

	// Render Media Overlay
	public function render_media_overlay( $settings ) {
		echo wp_kses_post('<div class="tmpcoder-img-accordion-hover-bg '. $this->get_animation_class( $settings, 'overlay' ) .'">');

			// if ( tmpcoder_is_availble() ) {
			// 	if ( !empty($settings['overlay_image']['url']) && '' !== $settings['overlay_image']['url'] ) {
			// 		echo '<img src="'. esc_url( $settings['overlay_image']['url'] ) .'">';
			// 	}
			// }

		echo '</div>';
	}
	
	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' tmpcoder-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' tmpcoder-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' tmpcoder-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' tmpcoder-anim-transparency';
			}
		}

		return $class;
	}

	// Render Post Title
	public function render_repeater_title( $settings, $class, $item ) {

		if (!empty($item['accordion_item_title'])) :
		echo wp_kses_post('<'. tmpcoder_validate_html_tag($settings['element_title_tag']) .' class="'. esc_attr($class) .'">');
			echo '<div class="inner-block">';
				echo '<a class="tmpcoder-pointer-item">';
					echo esc_html($item['accordion_item_title']);
				echo '</a>';
			echo '</div>';
		echo wp_kses_post('</'. tmpcoder_validate_html_tag($settings['element_title_tag']) .'>');
		endif;
	}

	// Render Post Excerpt
	public function render_repeater_description( $settings, $class, $item ) {

		if ( '' === $item['accordion_item_description'] ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo wp_kses_post('<p>'. $item['accordion_item_description'] .'</p>');
			echo '</div>';
		echo '</div>';
	}

	// Render Post Read More
	public function render_repeater_button( $settings, $class, $item ) {
		$button_animation = ! tmpcoder_is_availble() ? 'tmpcoder-button-none' : $this->get_settings_for_display()['button_animation'];

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo wp_kses_post('<a '. $this->get_render_attribute_string( 'accordion_btn_url'.$item['_id'] ) .' class="tmpcoder-button-effect '. $button_animation .'">');

				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					if (is_array($settings['element_extra_icon']['value'])) {

						echo '<span class="tmpcoder-img-accordion-extra-icon-left">';
						echo wp_kses($this->render_th_icon($settings), tmpcoder_wp_kses_allowed_html());
						echo '</span>';
					}
					else
					{
						echo '<i class="tmpcoder-img-accordion-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
					}
				}

				// Read More Text
				echo '<span>'. esc_html( $item['element_button_text'] ) .'</span>';

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					if (is_array($settings['element_extra_icon']['value'])) {

						echo '<i class="tmpcoder-img-accordion-extra-icon-right">';
						echo wp_kses($this->render_th_icon($settings), tmpcoder_wp_kses_allowed_html());
						echo '</i>';
					}
					else
					{	
						echo '<i class="tmpcoder-img-accordion-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
					}
				}

				echo '</a>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Element Separator
	public function render_repeater_separator( $settings, $class ) {
		echo '<div class="'. esc_attr($class) .' tmpcoder-img-accordion-sep-style-2">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}
	
	public function render_repeater_lightbox( $settings, $class, $item ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = $this->item_bg_image_url;

				echo '<div style="opacity: 0;" class="tmpcoder-accordion-image-wrap" data-src="'. esc_url( $lightbox_source ). '">';
					echo '<img src="'. esc_url( $lightbox_source ) .'" alt="'. esc_attr( $item['accordion_item_title'] ) .'">';
				echo '</div>';
	
				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="tmpcoder-img-accordion-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}
	
					// Lightbox Icon
					if( (!empty($settings['element_lightbox_icon']) && '' != $settings['element_lightbox_icon']) ) {
						if (is_array($settings['element_lightbox_icon']['value'])) {

							echo '<i class="tmpcoder-img-accordion-extra-icon-right">';
								echo wp_kses(tmpcoder_render_svg_icon($settings['element_lightbox_icon']), tmpcoder_wp_kses_allowed_html());
							echo '</i>';
						}
						else
						{	
							echo '<i class="'. esc_attr( $settings['element_lightbox_icon']['value'] ) .'"></i>';
						}
					}
	
					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="tmpcoder-img-accordion-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}
	
				echo '</span>';

				// Media Overlay
				// if ( 'yes' === $settings['element_lightbox_overlay'] ) {
				// 	echo '<div class="tmpcoder-img-accordion-lightbox-overlay"></div>';
				// }

			echo '</div>';
		echo '</div>';
	}

	public function get_elements( $type, $settings, $class, $item ) {

		switch ( $type ) {
			case 'title':
				$this->render_repeater_title( $settings, $class, $item );
				break;

			case 'description':
				$this->render_repeater_description( $settings, $class, $item );
				break;

			case 'button':
				$this->render_repeater_button( $settings, $class, $item );
				break;

			case 'lightbox':
				$this->render_repeater_lightbox( $settings, $class, $item );
				break;

			case 'pro-lbx':
				break;

			case 'separator':
				$this->render_repeater_separator( $settings, $class );
				break;
			
			default:
				break;
		}

	}

	// Get Image Effect Class
	public function get_image_effect_class( $settings ) {
		$class = '';

		if ( ! tmpcoder_is_availble() ) {
			if ( 'pro-zi' ==  $settings['image_effects'] || 'pro-zo' ==  $settings['image_effects'] || 'pro-go' ==  $settings['image_effects'] || 'pro-bo' ==  $settings['image_effects'] ) {
				$settings['image_effects'] = 'none';
			}
		}

		// Animation Class
		if ( 'none' !== $settings['image_effects'] ) {
			$class .= ' tmpcoder-'. $settings['image_effects'];
		}
		
		// Slide Effect
		if ( 'slide' !== $settings['image_effects'] ) {
			$class .= ' tmpcoder-effect-size-'. $settings['image_effects_size'];
		} else {
			$class .= ' tmpcoder-effect-dir-'. $settings['image_effects_direction'];
		}

		return $class;
	}	


    protected function render() {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if ( ! tmpcoder_is_availble() ) {
			$settings['lightbox_popup_thumbnails'] = '';
			$settings['lightbox_popup_thumbnails_default'] = '';
			$settings['lightbox_popup_sharing'] = '';
		}

		$lightbox_settings = '';

		if ( tmpcoder_is_availble() ) {
			$lightbox_settings = [
				'selector' => '.tmpcoder-accordion-image-wrap',
				'iframeMaxWidth' => '60%',
				'hash' => false,
				'autoplay' => $settings['lightbox_popup_autoplay'],
				'pause' => $settings['lightbox_popup_pause'] * 1000,
				'progressBar' => $settings['lightbox_popup_progressbar'],
				'counter' => $settings['lightbox_popup_counter'],
				'controls' => $settings['lightbox_popup_arrows'],
				'getCaptionFromTitleOrAlt' => $settings['lightbox_popup_captions'],
				'thumbnail' => $settings['lightbox_popup_thumbnails'],
				'showThumbByDefault' => $settings['lightbox_popup_thumbnails_default'],
				'share' => $settings['lightbox_popup_sharing'],
				'zoom' => $settings['lightbox_popup_zoom'],
				'fullScreen' => $settings['lightbox_popup_fullscreen'],
				'download' => $settings['lightbox_popup_download'],
			];
		
			$this->add_render_attribute( 'lightbox-settings',[
				'lightbox' =>  wp_json_encode($lightbox_settings)
			]);
		}

		$no_column = '';

		if ( $settings['accordion_direction'] == 'column' && !tmpcoder_is_availble() ) {
			$no_column = ' tmpcoder-acc-no-column';
		}
		?>

		<div class="tmpcoder-image-accordion-wrap <?php echo esc_html($no_column) ?>">
			<?php if ( ! tmpcoder_is_availble() ) : ?>
				<div class="tmpcoder-image-accordion">
			<?php else : ?>
				<?php 
                echo wp_kses('<div class="tmpcoder-image-accordion" '. $this->get_render_attribute_string('lightbox-settings').'>', array(
                    'div' => array(
                        'class' => array(),
                        'lightbox'=> array(),
                        'style'   => array(),
                    ),
                ));
                ?>
			<?php endif ; ?>
			<?php foreach ( $settings['accordion_items'] as $key => $item ) :
			if ( ! tmpcoder_is_availble() && $key === 3 ) {
				break;
			}
			
			// Skip if item is not valid array
			if ( !is_array($item) ) {
				continue;
			}
			
			if ( isset($item['accordion_item_bg_image']) && !empty($item['accordion_item_bg_image']['id']) ) {
				$this->item_bg_image_url = Group_Control_Image_Size::get_attachment_image_src( $item['accordion_item_bg_image']['id'] ?? 0, 'accordion_image_size', $settings );
			} elseif ( isset($item['accordion_item_bg_image']) && !empty($item['accordion_item_bg_image']['url']) ) {
				$this->item_bg_image_url = $item['accordion_item_bg_image']['url'] ?? '';
			} else {
				$this->item_bg_image_url = TMPCODER_ADDONS_ASSETS_URL . 'images/according-image-1.jpg';
			}

			$layout['activeItem'] = [
				'activeWidth' => isset($settings['accordion_active_item_style']['size']) ? $settings['accordion_active_item_style']['size'] : '',
				'defaultActive' => $settings['default_active'] ?? '',
				'interaction' => tmpcoder_is_availble() ? ($settings['accordion_interaction'] ?? 'hover') : 'hover',
				'overlayLink' => 'yes' === ($item['wrapper_link'] ?? '') && isset($item['accordion_btn_url']) ? ($item['accordion_btn_url']['url'] ?? '') : '',
				'overlayLinkTarget' => isset($item['accordion_btn_url']) && ($item['accordion_btn_url']['is_external'] ?? '') === 'on' ? '_blank' : '_self'
			];

			$this->add_render_attribute( 'accordion-settings'.$key, [
				'class' => ['tmpcoder-img-accordion-media-hover', 'tmpcoder-animation-wrap'],
				'data-settings' => wp_json_encode( $layout ),
				'data-src' => $this->item_bg_image_url
			] );

			$render_attribute = $this->get_render_attribute_string( 'accordion-settings'.$key );

			if ( ! empty( $item['accordion_btn_url']['url'] ) ) {
				$this->add_link_attributes( 'accordion_btn_url'.$item['_id'], $item['accordion_btn_url'] );
			}
			?>
				<?php echo wp_kses_post('<div data-src="'.esc_url($this->item_bg_image_url).'" class="tmpcoder-image-accordion-item elementor-repeater-item-'.$item['_id'] .' '. $this->get_image_effect_class( $settings ).'">');?>

				<?php 
				$bg_style = '';
				if ( !empty($this->item_bg_image_url) ) {
					$bg_style = ' style="background-image: url(' . esc_url($this->item_bg_image_url) . ');"';
				}
				echo wp_kses_post('<div class="tmpcoder-accordion-background"' . $bg_style . '></div>');
				?>
					<?php
						echo wp_kses_post('<div '. $render_attribute .'>');
							$this->render_media_overlay( $settings );
							$this->get_elements_by_location( 'over', $settings, $item );
						echo '</div>';
					?>
				</div>
			<?php endforeach; ?>
			</div>
		</div>

		<?php
    }

	public function render_th_icon($item) {
		ob_start();
		\Elementor\Icons_Manager::render_icon($item['element_extra_icon'], ['aria-hidden' => 'true']);
		return ob_get_clean();
	}
}