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
	exit; // Exit if accessed directly
}

class TMPCODER_Woo_Product_Price extends Widget_Base {

	public function get_name() {
		return 'tmpcoder-woo-product-price';
	}

	public function get_title() {
		return esc_html__( 'Product Price', 'sastra-essential-addons-for-elementor' );
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_product') ? [ 'tmpcoder-woocommerce-builder-widgets'] : [];
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-product-price';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'price', 'product', 'sale' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_price_style',
			[
				'label' => esc_html__( 'Price', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .price' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .price',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sale_price_style',
			[
				'label' => esc_html__( 'Sale Price', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sale_price_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .price ins' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sale_price_typography',
				'selector' => ' {{WRAPPER}} .price ins',
			]
		);

		$this->add_control(
			'price_block',
			[
				'label' => esc_html__( 'Stacked', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'elementor-product-price-block-',
			]
		);

		$this->add_responsive_control(
			'sale_price_spacing',
			[
				'label' => esc_html__( 'Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}}:not(.elementor-product-price-block-yes) del' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}}:not(.elementor-product-price-block-yes) del' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-product-price-block-yes del' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		// tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
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
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
	        'spacing',
	        [
	            'label' => esc_html__( 'Bottom Spacing', 'sastra-essential-addons-for-elementor'  ),
	            'type' => Controls_Manager::SLIDER,
	            'range' => [
	                'px' => [
	                    'min' => 0,
	                    'max' => 100,
	                ],
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .price' => 'margin-bottom: {{SIZE}}{{UNIT}}',
	            ],
	        ]
	    );

		$this->end_controls_section();
	}

	protected function render() {
			
		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<h2>'. esc_html__( 'WooCommerce is NOT active!', 'sastra-essential-addons-for-elementor' ) .'</h2>';
			return;
		}

		global $product;
		
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

		wc_get_template( '/single-product/price.php' );
	}

	public function render_plain_content() {}

	public function get_group_name() {
		return 'woocommerce';
	}
}
