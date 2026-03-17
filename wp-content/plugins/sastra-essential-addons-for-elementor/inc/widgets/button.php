<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
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

class TMPCODER_Button extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-button';
	}

	public function get_title() {
		return esc_html__( 'Button', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-button';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_header') ? [ 'tmpcoder-header-builder-widgets'] : ['tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'button' ];
	}
	
	public function get_style_depends() {
		return [ 'tmpcoder-button-animations-css', 'tmpcoder-button' ];
	}

    public function get_custom_help_url() {
    	return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_icon_style() {
		$this->add_control(
			'icon_style',
			[
				'label' => esc_html__( 'Select Style', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'sastra-essential-addons-for-elementor' ),
					'pro-bk' => esc_html__( 'Block (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-ibk' => esc_html__( 'Inline Block (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-button-icon-style-',
				'separator' => 'before',
			]
		);
	}

	public function add_control_icon_width() {}

	public function add_section_style_icon() {}

	public function add_section_tooltip() {}

	public function add_section_style_tooltip() {}

	public function render_pro_element_tooltip( $settings ) {}
	
	protected function register_controls() {

		// Section: Button ----------
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Click here',
			]
		);

		$this->add_control(
			'button_url',
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
			'button_hover_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-button-animations',
				'default' => 'tmpcoder-button-none',
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'button', 'button_hover_animation', ['pro-wnt','pro-rlt','pro-rrt'] );
		
		$this->add_control(
			'button_hover_anim_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					// '{{WRAPPER}} .tmpcoder-button' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					// '{{WRAPPER}} .tmpcoder-button::before' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button .tmpcoder-button-icon' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button .tmpcoder-button-icon svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button .tmpcoder-button-text' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-button .tmpcoder-button-content' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
                    '{{WRAPPER}} .tmpcoder-button' => 'transition: all {{VALUE}}s ease;',
                    '{{WRAPPER}} .tmpcoder-button::before' => 'transition: all {{VALUE}}s ease;',
				],
			]
		);

		$this->add_control(
			'button_hover_animation_height',
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
					'button_hover_animation' => ['tmpcoder-button-underline-from-left','tmpcoder-button-underline-from-center','tmpcoder-button-underline-from-right','tmpcoder-button-underline-reveal','tmpcoder-button-overline-reveal','tmpcoder-button-overline-from-left','tmpcoder-button-overline-from-center','tmpcoder-button-overline-from-right']
				],
			]
		);

		$this->add_control(
			'button_hover_animation_text',
			[
				'label' => esc_html__( 'Effect Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go',
				'condition' => [
					'button_hover_animation' => ['tmpcoder-button-winona','tmpcoder-button-rayen-left','tmpcoder-button-rayen-right']
				],
			]
		);

		$this->add_responsive_control(
			'button_width',
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
				/*'default' => [
					'unit' => 'px',
					'size' => 160,
				],*/
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'frontend_available' => true,
			]
		);


		$this->add_responsive_control(
			'button_position',
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
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_content_align',
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
					'{{WRAPPER}} .tmpcoder-button-content' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-button-text' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_id',
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

		// Section: Icon -------------
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'select_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_icon_style();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'button', 'icon_style', ['pro-bk', 'pro-ibk'] );

		$this->add_control(
			'icon_position',
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
				'prefix_class' => 'tmpcoder-button-icon-position-',
				'separator' => 'before',
			]
		);

		$this->add_control_icon_width();

		$this->add_control(
			'icon_size',
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
					'{{WRAPPER}} .tmpcoder-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-button-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-button-icon-position-left .tmpcoder-button-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-button-icon-position-right .tmpcoder-button-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_style' => ['inline', 'pro-bk', 'pro-ibk'],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltip ---------
		$this->add_section_tooltip();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'button', [
			__('Advanced Tooltip options', 'sastra-essential-addons-for-elementor'),
			__('Advanced Button Styles', 'sastra-essential-addons-for-elementor'),
			__('Advanced Hover Animations - Change Text on Hover', 'sastra-essential-addons-for-elementor'),
		] );

		// Styles
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_button',
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
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#5729d9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-button'
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-text' => 'color: {{VALUE}}',
					'{{WRAPPER}}.tmpcoder-button-icon-style-inline .tmpcoder-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}}.tmpcoder-button-icon-style-inline .tmpcoder-button-icon svg' => 'fill: {{VALUE}}',
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
					'{{WRAPPER}} .tmpcoder-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-button-text,{{WRAPPER}} .tmpcoder-button::after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
                    'color' => [
						'default' => '#FFFFF',
					],
				],
				'selector' => '	{{WRAPPER}} [class*="elementor-animation"]:hover,
								{{WRAPPER}} .tmpcoder-button::before,
								{{WRAPPER}} .tmpcoder-button::after',
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button:hover .tmpcoder-button-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button::after' => 'color: {{VALUE}}',
					'{{WRAPPER}}.tmpcoder-button-icon-style-inline .tmpcoder-button:hover .tmpcoder-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}}.tmpcoder-button-icon-style-inline .tmpcoder-button:hover .tmpcoder-button-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => tmpcoder_elementor_global_colors('primary_color'),
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-button:hover',
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
					'top' => 12,
					'right' => 24,
					'bottom' => 12,
					'left' => 24,
                    'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-button-icon-style-inline .tmpcoder-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-button-icon-style-block .tmpcoder-button-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-button-icon-style-inline-block .tmpcoder-button-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-button::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button' => 'border-style: {{VALUE}};',
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
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Icon ---------------
		$this->add_section_style_icon();

		// Styles
		// Section: Tooltip ---------------
		$this->add_section_style_tooltip();
	
	}

	protected function render() {
		
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		
		$btn_element = 'div';
		$btn_url =  $settings['button_url']['url'];

	?>
	
	<?php if ( (!empty($settings['button_text']) && '' !== $settings['button_text']) || (!empty($settings['select_icon']['value']) && '' !== $settings['select_icon']['value']) ) : ?>
		
		<?php 	
		
		$this->add_render_attribute( 'button_attribute', 'class', 'tmpcoder-button tmpcoder-button-effect '. $settings['button_hover_animation'] );
			
		if ( !empty($settings['button_hover_animation_text']) && '' !== $settings['button_hover_animation_text'] ) {
			$this->add_render_attribute( 'button_attribute', 'data-text', $settings['button_hover_animation_text'] );
		}	

		if ( '' !== $btn_url ) {

			$btn_element = 'a';

			$this->add_render_attribute( 'button_attribute', 'href', $settings['button_url']['url'] );

			if ( $settings['button_url']['is_external'] ) {
				$this->add_render_attribute( 'button_attribute', 'target', '_blank' );
			}

			if ( $settings['button_url']['nofollow'] ) {
				$this->add_render_attribute( 'button_attribute', 'nofollow', '' );
			}
		}

		if ( !empty($settings['button_id']) && '' !== $settings['button_id'] ) {
			$this->add_render_attribute( 'button_attribute', 'id', $settings['button_id']  );
		}

		$this->add_render_attribute( 'button_class_attribute', 'class', 'tmpcoder-button-wrap elementor-clearfix'  );

		if ( !empty($settings['button_width']['size']) && '' !== $settings['button_width']['size'] ) {
			$this->add_render_attribute( 'button_class_attribute', 'class', 'tmpcoder-button-custom-width'  );
		}

		?>

		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'button_class_attribute' ) ); ?> >
		<?php echo wp_kses_post('<'.esc_html($btn_element).' '.$this->get_render_attribute_string( 'button_attribute' ).'>');
        ?>	
			<span class="tmpcoder-button-content">
				<?php if ( !empty($settings['button_text']) && '' !== $settings['button_text'] ) : ?>
					<span class="tmpcoder-button-text"><?php echo esc_html( $settings['button_text'] ); ?></span>
				<?php endif; ?>
				
				<?php if ( !empty($settings['select_icon']['value']) && '' !== $settings['select_icon']['value'] ) : ?>
					<span class="tmpcoder-button-icon"><?php \Elementor\Icons_Manager::render_icon( $settings['select_icon'] ); ?></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($btn_element); ?>>

		<?php $this->render_pro_element_tooltip( $settings ); ?>
		</div>
	
	<?php endif; ?>

	<?php

	}
}