<?php
namespace TMPCODER\Widgets;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Woo_Product_Title extends Widget_Base {

	public function get_name() {
		return 'tmpcoder-woo-product-title';
	}

	public function get_title() {
		return esc_html__( 'Product Title', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-product-title';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_product') ? ['tmpcoder-woocommerce-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'title', 'heading', 'product' ];
	}

	public function get_inline_css_depends() {
		return [
			[
				'name' => 'heading',
				'is_core_dependency' => true,
			],
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'sastra-essential-addons-for-elementor' ),
					'small' => esc_html__( 'Small', 'sastra-essential-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
					'large' => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
					'xl' => esc_html__( 'XL', 'sastra-essential-addons-for-elementor' ),
					'xxl' => esc_html__( 'XXL', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'header_size',
			[
				'label' => esc_html__( 'HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h1',
			]
		);

		$this->add_responsive_control(
			'align',
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => esc_html__( 'Text Hover Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elementor-heading-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .elementor-heading-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .elementor-heading-title',
			]
		);

		$this->add_responsive_control(
	        'title_spacing',
	        [
	            'label' => esc_html__( 'Spacing', 'sastra-essential-addons-for-elementor'  ),
	            'type' => \Elementor\Controls_Manager::SLIDER,
	            'range' => [
	                'px' => [
	                    'min' => 0,
	                    'max' => 100,
	                ],
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .elementor-heading-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
	            ],
	        ]
	    );

		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );
	}

	/**
	 * Render heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<h2>'. esc_html__( 'WooCommerce is NOT active!', 'sastra-essential-addons-for-elementor' ) .'</h2>';
			return;
		}

		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		// Get Product

		if( tmpcoder_is_preview_mode() ){
        	$lastId = tmpcoder_get_last_product_id();
			$product = wc_get_product($lastId);
        }
        else
        {
			$product = wc_get_product();
        }

		if ( ! $product ) {
			return;
		}

		$this->add_render_attribute( 'heading-title', 'class', 'elementor-widget-heading' );
		$this->add_render_attribute( 'title', 'class', 'elementor-heading-title' );

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'title', 'class', 'elementor-size-' . $settings['size'] );
		}

		$this->add_inline_editing_attributes( 'title' );

		$title = $product->get_title();

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );

			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}

		$title_html = sprintf( '<span %1$s >',  $this->get_render_attribute_string( 'heading-title' ));
			$title_html .= sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $settings['header_size'] ), $this->get_render_attribute_string( 'title' ), $title );
		$title_html .= '<span>';

		// PHPCS - the variable $title_html holds safe data.
		echo wp_kses_post($title_html);
	}
}
