<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Navigation_Menu extends Widget_Base {

	protected $nav_menu_index = 1;
	
	public function get_name() {
		return 'tmpcoder-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'Nav Menu', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-nav-menu';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_header') ? [ 'tmpcoder-header-builder-widgets'] : ['tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'nav menu', 'header', 'navigation menu', 'horizontal menu', 'horizontal navigation', 'vertical menu', 'vertical navigation', 'burger menu', 'hamburger menu', 'mobile menu', 'responsive menu' ];
	}

	public function get_script_depends() {
		return [ 'tmpcoder-nav-menu' ];
	}

	public function get_style_depends() {

		$depends = [ 'tmpcoder-link-animations-css' => true, 'tmpcoder-nav-menu' => true ];

		if ( !tmpcoder_elementor()->preview->is_preview_mode() ) {
			$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

			if ( $settings['menu_items_pointer'] == 'none' ) {
				unset( $depends['tmpcoder-link-animations-css'] );
			}
		}
		return array_keys($depends); 
	}

	public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function on_export( $element ) {
		unset( $element['settings']['menu'] );
		return $element;
	}

	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	public function add_control_menu_layout() {
		$this->add_control(
			'menu_layout',
			[
				'label' => esc_html__( 'Menu Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'sastra-essential-addons-for-elementor' ),
					'pro-vr' => esc_html__( 'Vertical (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'frontend_available' => true,
			]
		);
	}

	public function add_control_menu_items_pointer() {
		$this->add_control(
			'menu_items_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'underline',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'underline' => esc_html__( 'Underline', 'sastra-essential-addons-for-elementor' ),
					'overline' => esc_html__( 'Overline', 'sastra-essential-addons-for-elementor' ),
					'double-line' => esc_html__( 'Double Line', 'sastra-essential-addons-for-elementor' ),
					'pro-bd' => esc_html__( 'Border (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-bg' => esc_html__( 'Background (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-pointer-',
			]
		);
	}

	public function add_control_pointer_animation_line() {
		$this->add_control(
			'pointer_animation_line',
			[
				'label' => esc_html__( 'Hover Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'pro-sl' => 'Slide (Pro)',
					'pro-gr' => 'Grow (Pro)',
					'pro-dr' => 'Drop (Pro)',
				],
				'prefix_class' => 'tmpcoder-pointer-line-fx tmpcoder-pointer-fx-',
				'condition' => [
					'menu_items_pointer' => [ 'underline', 'overline', 'double-line' ],
				],
			]
		);
	}

	public function add_control_pointer_animation_border() {}

	public function add_control_pointer_animation_background() {}

	public function add_control_menu_items_submenu_entrance() {
		$this->add_control(
			'menu_items_submenu_entrance',
			[
				'label' => esc_html__( 'Sub Menu Entrance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'sastra-essential-addons-for-elementor' ),
					'pro-sl' => esc_html__( 'Slide (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-sub-menu-fx-',
			]
		);
	}

	public function add_control_mob_menu_display() {
		$breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

		$this->add_control(
			'mob_menu_display',
			[
				'label' => esc_html__( 'Show On', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'mobile',
				'options' => [
					/* translators: %d: Breakpoint number. */
					'mobile' => sprintf( esc_html__( 'Mobile (≤ %dpx)', 'sastra-essential-addons-for-elementor' ), $breakpoints['mobile']->get_default_value() ),
					/* translators: %d: Breakpoint number. */
					'tablet' => sprintf( esc_html__( 'Tablet (≤ %dpx)', 'sastra-essential-addons-for-elementor' ), $breakpoints['tablet']->get_default_value() ),
					'pro-nn' => esc_html__( 'Don\'t Show (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-al' => esc_html__( 'All Devices (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-nav-menu-bp-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_toggle_btn_style() {
		$this->add_control(
			'toggle_btn_style',
			[
				'label' => esc_html__( 'Style', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hamburger',
				'options' => [
					'hamburger' => esc_html__( 'Hamburger', 'sastra-essential-addons-for-elementor' ),
					'pro-tx' => esc_html__( 'Text (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);
	}

	public function add_control_sub_menu_width() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Menu -------------
		$this->start_controls_section(
			'section_menu',
			[
				'label' => 'Menu',
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu_select',
				[
					'label' => esc_html__( 'Select Menu', 'sastra-essential-addons-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'save_default' => true,
					'separator' => 'after',
					// Translators: %s is the menus link.
					'description' => sprintf( __( 'Go to <a href="%s" target="_blank">Appearance > Menus</a> to manage your menus.', 'sastra-essential-addons-for-elementor' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu_select',
				[
					'type' => Controls_Manager::RAW_HTML,
					// Translators: %s is the menu link.
					'raw' => sprintf( __( '<strong>No menus found!</strong><br><a href="%s" target="_blank">Click Here</a> to create a new Menu.', 'sastra-essential-addons-for-elementor' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control_menu_layout();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'menu_layout', ['pro-vr'] );

		$this->add_responsive_control(
			'menu_align',
			[
				'label' => esc_html__( 'Align', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'widescreen_default' => 'left',
				'laptop_default' => 'left',
				'tablet_extra_default' => 'left',
				'tablet_default' => 'left',
				'mobile_extra_default' => 'left',
				'mobile_default' => 'left',
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
				'prefix_class' => 'tmpcoder-main-nav-menu-align-%s',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'verticle_align',
			[
				'label' => esc_html__( 'Adjust Icon Vertical Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 48,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sub-icon' => 'top: {{SIZE}}{{UNIT}};',
				],
			
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Menu Items -------
		$this->start_controls_section(
			'section_menu_items',
			[
				'label' => esc_html__( 'Menu Items', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_menu_items_pointer();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'menu_items_pointer', ['pro-bd', 'pro-bg'] );

		$this->add_control_pointer_animation_line();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'pointer_animation_line', ['pro-sl', 'pro-dr', 'pro-gr']);

		$this->add_control_pointer_animation_border();

		$this->add_control_pointer_animation_background();

		$this->add_control(
			'pointer_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-menu-item.tmpcoder-pointer-item' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-menu-item.tmpcoder-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-menu-item.tmpcoder-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'menu_items_submenu_icon',
			[
				'label' => esc_html__( 'Sub Menu Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'caret-down',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'caret-down' => esc_html__( 'Triangle', 'sastra-essential-addons-for-elementor' ),
					'angle-down' => esc_html__( 'Angle', 'sastra-essential-addons-for-elementor' ),
					'chevron-down' => esc_html__( 'Chevron', 'sastra-essential-addons-for-elementor' ),
					'plus' => esc_html__( 'Plus', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-sub-icon-',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'menu_items_submenu_position',
			[
				'label' => esc_html__( 'Sub Menu Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'sastra-essential-addons-for-elementor' ),
					'absolute' => esc_html__( 'Absolute', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-sub-menu-position-',
				'condition' => [
					'menu_layout' => 'vertical',
				],
			]
		);

		$this->add_control(
			'menu_items_submenu_trigger',
			[
				'label' => esc_html__( 'Sub Menu Display', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'hover' => esc_html__( 'on Mouse Over', 'sastra-essential-addons-for-elementor' ),
					'click' => esc_html__( 'on Mouse Click', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$this->add_control_menu_items_submenu_entrance();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'menu_items_submenu_entrance', ['pro-sl'] );

		$this->end_controls_section(); // End Controls Section

		// Section: Mobile Menu ------
		$this->start_controls_section(
			'section_mobile_menu',
			[
				'label' => esc_html__( 'Mobile Menu', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_mob_menu_display();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'mob_menu_display', ['pro-nn', 'pro-al'] );

		$this->add_control(
			'mob_menu_stretch',
			[
				'label' => esc_html__( 'Stretch', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'full-width',
				'options' => [
					'auto-width' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'full-width' => esc_html__( 'Full Width', 'sastra-essential-addons-for-elementor' ),
					'custom-width' => esc_html__( 'Custom Width', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-mobile-menu-',
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'mob_menu_stretch_width',
			[
				'label' => esc_html__( 'Dropdown Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'tablet_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-mobile-menu-custom-width .tmpcoder-mobile-nav-menu' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mob_menu_stretch' => 'custom-width',
				],
			]
		);

		$this->add_control(
			'mob_menu_drdown_align',
			[
				'label' => esc_html__( 'Dropdown Align', 'sastra-essential-addons-for-elementor' ),
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
				'prefix_class' => 'tmpcoder-mobile-menu-drdown-align-',
				'condition' => [
					'mob_menu_display!' => 'none',
					'mob_menu_stretch' => [ 'custom-width', 'auto-width' ],
				],
			]
		);

		$this->add_control(
			'mob_menu_item_align',
			[
				'label' => esc_html__( 'Item Align', 'sastra-essential-addons-for-elementor' ),
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
				'prefix_class' => 'tmpcoder-mobile-menu-item-align-',
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->add_control(
			'heading_toggle_button',
			[
				'label' => esc_html__( 'Toggle Button', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->add_control_toggle_btn_style();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'toggle_btn_style', ['pro-tx'] );

		$this->add_control(
			'toggle_btn_burger',
			[
				'label' => esc_html__( 'Toggle Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'v1',
				'options' => [
					'v1' => esc_html__( 'Icon 1', 'sastra-essential-addons-for-elementor' ),
					'v2' => esc_html__( 'Icon 2', 'sastra-essential-addons-for-elementor' ),
					'v3' => esc_html__( 'Icon 3', 'sastra-essential-addons-for-elementor' ),
					'v4' => esc_html__( 'Icon 4', 'sastra-essential-addons-for-elementor' ),
					'v5' => esc_html__( 'Icon 5', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-mobile-toggle-',
				'condition' => [
					'mob_menu_display!' => 'none',
					'toggle_btn_style' => ['hamburger', 'pro-tx'],
				],
			]
		);

		$this->add_control(
			'toggle_btn_txt_1',
			[
				'label' => esc_html__( 'Toggle Open Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Menu', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'mob_menu_display!' => 'none',
					'toggle_btn_style' => 'text',
				],
			]
		);

		$this->add_control(
			'toggle_btn_txt_2',
			[
				'label' => esc_html__( 'Toggle Close Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Close', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'mob_menu_display!' => 'none',
					'toggle_btn_style' => 'text',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_btn_align',
			[
				'label' => esc_html__( 'Toggle Align', 'sastra-essential-addons-for-elementor' ),
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
				'selectors_dictionary' => [
					'left' => 'text-align: left',
					'center' => 'text-align: center',
					'right' => 'text-align: right',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle-wrap' => '{{VALUE}}',
				],
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'nav-menu', [
			'Vertical Layout',
			'Advanced Link Hover Effects: Slide, Grow, Drop',
			'SubMenu Entrance Slide Effect',
			'SubMenu Width option',
			'Advanced Display Conditions',
			'Mobile Menu Display Custom Conditions',
			'Mobile Menu Button Custom Text option',
		] );

		// Tab: Styles ===============
		// Section: Menu Items -------
		$this->start_controls_section(
			'section_style_menu_items',
			[
				'label' => esc_html__( 'Menu Items', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'menu_item_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-nav-menu .tmpcoder-menu-item,
					 {{WRAPPER}} .tmpcoder-nav-menu > .menu-item-has-children > .tmpcoder-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'menu_item_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-nav-menu .tmpcoder-menu-item:hover,
					 {{WRAPPER}} .tmpcoder-nav-menu > .menu-item-has-children:hover > .tmpcoder-sub-icon,
					 {{WRAPPER}} .tmpcoder-nav-menu .tmpcoder-menu-item.tmpcoder-active-menu-item,
					 {{WRAPPER}} .tmpcoder-nav-menu > .menu-item-has-children.current_page_item > .tmpcoder-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pointer_color_hover',
			[
				'label' => esc_html__( 'Pointer Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-pointer-line-fx .tmpcoder-menu-item:before,
					 {{WRAPPER}}.tmpcoder-pointer-line-fx .tmpcoder-menu-item:after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.tmpcoder-pointer-border-fx .tmpcoder-menu-item:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.tmpcoder-pointer-background-fx .tmpcoder-menu-item:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'menu_item_highlight',
			[
				'label' => esc_html__( 'Highlight Active Item', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'menu_items_sub_icon_size',
			[
				'label' => esc_html__( 'Sub Menu Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .menu-item-has-children .tmpcoder-sub-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-pointer-background:not(.tmpcoder-sub-icon-none) .tmpcoder-nav-menu-horizontal .menu-item-has-children .tmpcoder-pointer-item' => 'padding-right: calc({{SIZE}}px + {{menu_items_padding_hr.SIZE}}px);',
					'{{WRAPPER}}.tmpcoder-pointer-border:not(.tmpcoder-sub-icon-none) .tmpcoder-nav-menu-horizontal .menu-item-has-children .tmpcoder-pointer-item' => 'padding-right: calc({{SIZE}}px + {{menu_items_padding_hr.SIZE}}px);',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_items_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-nav-menu .tmpcoder-menu-item,{{WRAPPER}} .tmpcoder-mobile-nav-menu a,{{WRAPPER}} .tmpcoder-mobile-toggle-text',
			]
		);

		$this->add_control(
			'pointer_height',
			[
				'label' => esc_html__( 'Pointer Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'devices' => [ self::RESPONSIVE_DESKTOP, self::RESPONSIVE_TABLET ],
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-pointer-underline .tmpcoder-menu-item:after,
					 {{WRAPPER}}.tmpcoder-pointer-overline .tmpcoder-menu-item:before,
					 {{WRAPPER}}.tmpcoder-pointer-double-line .tmpcoder-menu-item:before,
					 {{WRAPPER}}.tmpcoder-pointer-double-line .tmpcoder-menu-item:after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-pointer-border-fx .tmpcoder-menu-item:before' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'menu_items_padding_hr',
			[
				'label' => esc_html__( 'Inner Horizontal Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 7,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-nav-menu .tmpcoder-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-pointer-background:not(.tmpcoder-sub-icon-none) .tmpcoder-nav-menu-vertical .menu-item-has-children .tmpcoder-sub-icon' => 'text-indent: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-pointer-border:not(.tmpcoder-sub-icon-none) .tmpcoder-nav-menu-vertical .menu-item-has-children .tmpcoder-sub-icon' => 'text-indent: -{{SIZE}}{{UNIT}};',

				]
			]
		);

		$this->add_responsive_control(
			'menu_items_padding_bg_hr',
			[
				'label' => esc_html__( 'Outer Horizontal Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-nav-menu > .menu-item' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-nav-menu-vertical .tmpcoder-nav-menu > li > .tmpcoder-sub-menu' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-main-nav-menu-align-left .tmpcoder-nav-menu-vertical .tmpcoder-nav-menu > li > .tmpcoder-sub-icon' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-main-nav-menu-align-right .tmpcoder-nav-menu-vertical .tmpcoder-nav-menu > li > .tmpcoder-sub-icon' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_items_padding_vr',
			[
				'label' => esc_html__( 'Vertical Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-nav-menu .tmpcoder-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_items_border',
				'fields_options' => [
					'border' => [
						'default' => '',
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
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-menu-item',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Sub Menu ---------
		$this->start_controls_section(
			'section_style_sub_menu',
			[
				'label' => esc_html__( 'Sub Menu', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_sub_menu_style' );

		$this->start_controls_tab(
			'tab_sub_menu_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'sub_menu_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sub-menu .tmpcoder-sub-menu-item,
					 {{WRAPPER}} .tmpcoder-sub-menu > .menu-item-has-children .tmpcoder-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_menu_color_bg',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sub-menu .tmpcoder-sub-menu-item' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_sub_menu_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'sub_menu_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sub-menu .tmpcoder-sub-menu-item:hover,
					 {{WRAPPER}} .tmpcoder-sub-menu > .menu-item-has-children .tmpcoder-sub-menu-item:hover .tmpcoder-sub-icon,
					 {{WRAPPER}} .tmpcoder-sub-menu .tmpcoder-sub-menu-item.tmpcoder-active-menu-item,
					 {{WRAPPER}} .tmpcoder-sub-menu > .menu-item-has-children.current_page_item .tmpcoder-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_menu_color_bg_hover',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sub-menu .tmpcoder-sub-menu-item:hover,
					 {{WRAPPER}} .tmpcoder-sub-menu .tmpcoder-sub-menu-item.tmpcoder-active-menu-item' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after'
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_menu_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-sub-menu .tmpcoder-sub-menu-item'
			]
		);

		$this->add_control_sub_menu_width();

		$this->add_responsive_control(
			'sub_menu_padding_hr',
			[
				'label' => esc_html__( 'Horizontal Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sub-menu .tmpcoder-sub-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-sub-menu .tmpcoder-sub-icon' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-main-nav-menu-align-right .tmpcoder-nav-menu-vertical .tmpcoder-sub-menu .tmpcoder-sub-icon' => 'left: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'sub_menu_padding_vr',
			[
				'label' => esc_html__( 'Vertical Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 13,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-sub-menu .tmpcoder-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_menu_offset',
			[
				'label' => esc_html__( 'Sub Menu Offset', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-nav-menu-horizontal .tmpcoder-nav-menu > li > .tmpcoder-sub-menu' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-nav-menu-vertical .tmpcoder-nav-menu > li > .tmpcoder-sub-menu' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'sub_menu_divider',
			[
				'label' => esc_html__( 'Item Divider', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'tmpcoder-sub-divider-',
				'default' => 'yes',
				'return_value' => 'yes'
			]
		);

		$this->add_control(
			'sub_menu_divider_color',
			[
				'label' => esc_html__( 'Divider Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-sub-divider-yes .tmpcoder-sub-menu li:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'sub_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'sub_menu_divider_height',
			[
				'label' => esc_html__( 'Divider Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-sub-divider-yes .tmpcoder-sub-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'sub_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'sub_menu_divider_ctrl',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sub_menu_border',
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
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-sub-menu',
			]
		);
		$this->add_control(
			'menu_items_badge_radius',
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
					// '{{WRAPPER}} .tmpcoder-sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-sub-menu li:first-child a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-sub-menu li:last-child a' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'sub_menu_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .tmpcoder-sub-menu',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Mobile Menu -------
		$this->start_controls_section(
			'section_style_mobile_menu',
			[
				'label' => esc_html__( 'Mobile Menu', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_mobile_menu_style' );

		$this->start_controls_tab(
			'tab_mobile_menu_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'mobile_menu_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-nav-menu a,
					 {{WRAPPER}} .tmpcoder-mobile-nav-menu .menu-item-has-children > a:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-nav-menu li' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_mobile_menu_focus',
			[
				'label' => esc_html__( 'Focus', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'mobile_menu_color_focus',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-nav-menu li a:hover,
					 {{WRAPPER}} .tmpcoder-mobile-nav-menu .menu-item-has-children > a:hover:after,
					 {{WRAPPER}} .tmpcoder-mobile-nav-menu li a.tmpcoder-active-menu-item,
					 {{WRAPPER}} .tmpcoder-mobile-nav-menu .menu-item-has-children.current_page_item > a:hover:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_bg_color_focus',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-nav-menu a:hover,
					 {{WRAPPER}} .tmpcoder-mobile-nav-menu a.tmpcoder-active-menu-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'mobile_menu_highlight',
			[
				'label' => esc_html__( 'Highlight Active Item', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes'
			]
		);

		$this->add_control(
			'mobile_menu_padding_hr',
			[
				'label' => esc_html__( 'Item Horizontal Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-nav-menu a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-mobile-nav-menu .menu-item-has-children > a:after' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_padding_vr',
			[
				'label' => esc_html__( 'Item Vertical Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-nav-menu .tmpcoder-mobile-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'mobile_menu_divider',
			[
				'label' => esc_html__( 'Item Divider', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'tmpcoder-mobile-divider-',
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_divider_color',
			[
				'label' => esc_html__( 'Divider Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-mobile-divider-yes .tmpcoder-mobile-nav-menu a' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'mobile_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'mobile_menu_divider_height',
			[
				'label' => esc_html__( 'Divider Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-mobile-divider-yes .tmpcoder-mobile-nav-menu a' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mobile_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'mobile_menu_sub_font_size',
			[
				'label' => esc_html__( 'Sub Item Font Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-nav-menu .tmpcoder-mobile-sub-menu-item' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_sub_padding_vr',
			[
				'label' => esc_html__( 'Sub Item Vertical Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-nav-menu .tmpcoder-mobile-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'mobile_menu_offset',
			[
				'label' => esc_html__( 'Dropdown Offset', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'px' => [
					'min' => 1,
					'min' => 50,
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-nav-menu' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Toggle Button ----
		$this->start_controls_section(
			'section_style_toggle_button',
			[
				'label' => esc_html__( 'Toggle Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'tab_toggle_btn_style_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'toggle_btn_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-mobile-toggle-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-mobile-toggle-line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toggle_btn_style_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'toggle_btn_color_hover',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-mobile-toggle:hover .tmpcoder-mobile-toggle-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-mobile-toggle:hover .tmpcoder-mobile-toggle-line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_btn_lines_height',
			[
				'label' => esc_html__( 'Lines Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle-line' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'toggle_btn_style' => ['hamburger', 'pro-tx'],
				],
			]
		);

		$this->add_control(
			'toggle_btn_line_space',
			[
				'label' => esc_html__( 'Space Between Lines', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle-line' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'toggle_btn_style' => ['hamburger', 'pro-tx'],
				],
			]
		);

		$this->add_control(
			'toggle_btn_width',
			[
				'label' => esc_html__( 'Button Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_btn_padding',
			[
				'label' => esc_html__( 'Button Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_border_width',
			[
				'label' => esc_html__( 'Button Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle' => 'border-width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'toggle_btn_border_radius',
			[
				'label' => esc_html__( 'Button Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mobile-toggle' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	public function custom_menu_item_classes( $atts, $item, $args, $depth ) {
		$settings = $this->get_active_settings();

		// Main or Mobile
		if ( strpos( $args->menu_id, 'mobile-menu' ) === false ) {
		    $main 	= 'tmpcoder-menu-item tmpcoder-pointer-item';
		    $sub 	= 'tmpcoder-sub-menu-item';
		    $active = $settings['menu_item_highlight'] === 'yes' ? ' tmpcoder-active-menu-item' : '';
		} else {
		    $main 	= 'tmpcoder-mobile-menu-item';
		    $sub 	= 'tmpcoder-mobile-sub-menu-item';
		    $active = $settings['mobile_menu_highlight'] === 'yes' ? ' tmpcoder-active-menu-item' : '';
		}

		$classes = $depth ? $sub : $main;

		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$classes .= $active;
		}

		if ( empty( $atts['class'] ) ) {
			$atts['class'] = $classes;
		} else {
			$atts['class'] .= ' '. $classes;
		}

		return $atts;
	}

	public function custom_sub_menu_class( $classes ) {
		$classes[] = 'tmpcoder-sub-menu';

		return $classes;
	}

	public function custom_menu_items( $output, $item, $depth, $args ) {
		$settings = $this->get_active_settings();

		if ( strpos( $args->menu_class, 'tmpcoder-nav-menu' ) !== false ) {
			if ( in_array( 'menu-item-has-children', $item->classes ) ) {
				$item_class = 'tmpcoder-menu-item tmpcoder-pointer-item';

				if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
					$item_class .= ' tmpcoder-active-menu-item';
				}

				// Sub Menu Classes
				if ( $depth > 0 ) {
					$item_class = 'tmpcoder-sub-menu-item';

					if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
						$item_class .= ' tmpcoder-active-menu-item';
					}
				}

				// Add Sub Menu Icon
				$output  ='<a href="'. esc_url($item->url) .'" class="'. esc_attr($item_class) .'">'. esc_html($item->title);
				if ( $depth > 0 ) {
					if ( 'inline' === $settings['menu_items_submenu_position'] ) {
						$output .='<i class="tmpcoder-sub-icon fas" aria-hidden="true"></i>';
					} else {
						$output .='<i class="tmpcoder-sub-icon fas tmpcoder-sub-icon-rotate" aria-hidden="true"></i>';
					}
				} else {
					if ( 'absolute' === $settings['menu_items_submenu_position'] ) {
						$output .='<i class="tmpcoder-sub-icon fas tmpcoder-sub-icon-rotate" aria-hidden="true"></i>';
					} else {
						$output .='<i class="tmpcoder-sub-icon fas" aria-hidden="true"></i>';
					}
				}

				$output .='</a>';		
			}
		}

		return $output;
	}

	protected function render() {
		$available_menus = $this->get_available_menus();
	
		if ( ! $available_menus ) {
			return;
		}

		// Get Settings
		$settings = $this->get_active_settings();

		$args = [
			'echo' => false,
			'menu' => $settings['menu_select'],
			'menu_class' => 'tmpcoder-nav-menu',
			'menu_id' => 'menu-'. $this->get_nav_menu_index() .'-'. $this->get_id(),
			'container' => '',
			'fallback_cb' => '__return_empty_string',
		];

		// Custom Menu Items
		add_filter( 'walker_nav_menu_start_el', [ $this, 'custom_menu_items' ], 10, 4 );

		// Add Custom Filters
		add_filter( 'nav_menu_link_attributes', [ $this, 'custom_menu_item_classes' ], 10, 4 );
		add_filter( 'nav_menu_submenu_css_class', [ $this, 'custom_sub_menu_class' ] );
		add_filter( 'nav_menu_item_id', '__return_empty_string' );

		// Generate Menu HTML
		$menu_html = wp_nav_menu( $args );

		// Generate Mobile Menu HTML
		$args['menu_id'] 	= 'mobile-menu-'. $this->get_nav_menu_index() .'-'. $this->get_id();
		$args['menu_class'] = 'tmpcoder-mobile-nav-menu';
		$moible_menu_html 	= wp_nav_menu( $args );

		// Remove Custom Filters
		remove_filter( 'nav_menu_link_attributes', [ $this, 'custom_menu_item_classes' ] );
		remove_filter( 'nav_menu_submenu_css_class', [ $this, 'custom_sub_menu_class' ] );
		remove_filter( 'walker_nav_menu_start_el', [ $this, 'custom_menu_items' ] );
		remove_filter( 'nav_menu_item_id', '__return_empty_string' );

		if ( empty( $menu_html ) ) {
			return;
		}

		if (!tmpcoder_is_availble()) {
			$settings['menu_layout'] = 'horizontal';
			$settings['toggle_btn_style'] = 'hamburger';
		}

		// Main Menu
		echo '<nav class="tmpcoder-nav-menu-container tmpcoder-nav-menu-'. esc_attr($settings['menu_layout']) .'" data-trigger="'. esc_attr($settings['menu_items_submenu_trigger']) .'">';
			echo wp_kses($menu_html, tmpcoder_wp_kses_allowed_html());
		echo '</nav>';

		// Mobile Menu
		echo '<nav class="tmpcoder-mobile-nav-menu-container">';

			// Toggle Button
			echo '<div class="tmpcoder-mobile-toggle-wrap">';
				echo '<div class="tmpcoder-mobile-toggle">';
					if ( $settings['toggle_btn_style'] === 'hamburger' ) {
						echo '<span class="tmpcoder-mobile-toggle-line"></span>';
						echo '<span class="tmpcoder-mobile-toggle-line"></span>';
						echo '<span class="tmpcoder-mobile-toggle-line"></span>';
					} else {
						echo '<span class="tmpcoder-mobile-toggle-text">'. esc_html($settings['toggle_btn_txt_1']) .'</span>';
						echo '<span class="tmpcoder-mobile-toggle-text">'. esc_html($settings['toggle_btn_txt_2']) .'</span>';
					}
				echo '</div>';
			echo '</div>';

			// Menu
			echo wp_kses($moible_menu_html, tmpcoder_wp_kses_allowed_html());
		echo '</nav>';
	}
	
}