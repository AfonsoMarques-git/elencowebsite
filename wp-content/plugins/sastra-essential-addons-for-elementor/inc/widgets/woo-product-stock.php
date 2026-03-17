<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Product_Stock extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-product-stock';
	}

	public function get_title() {
		return esc_html__( 'Product Stock', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-product-stock';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_product') ? [ 'tmpcoder-woocommerce-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-stock', 'product', 'stock' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_stock',
			[
				'label' => esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'in_stock_heading',
			[
				'label' => esc_html__( 'In Stock', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'in_stock_availability_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'In Stock', 'sastra-essential-addons-for-elementor' ),
				'default' => esc_html__( 'In Stock', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'availability_managing_text',
			[
				'label' => esc_html__( 'Show Managing Stock Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'in_stock_availability_managing_text',
			[
				'label' => esc_html__( 'Managing Stock Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Only {stock} left In Stock', 'sastra-essential-addons-for-elementor' ),
				'default' => esc_html__( 'Only {stock} left In Stock', 'sastra-essential-addons-for-elementor' ),
				'condition' => ['availability_managing_text' => 'yes']
			]
		);

		$this->add_control(
			'preorder_text_display',
			[
				'label' => esc_html__( 'Show Pre-order Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'preorder_text',
			[
				'label' => esc_html__( 'Pre-order Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Preorder', 'sastra-essential-addons-for-elementor' ),
				'default' => esc_html__( 'Preorder', 'sastra-essential-addons-for-elementor' ),
				'condition' => ['preorder_text_display' => 'yes']
			]
		);

		$this->add_control(
			'product_in_stock_icon',
			[
				'label'   => esc_html__('Select Icon', 'sastra-essential-addons-for-elementor'),
				'type'    => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
				'default' => [
					'value'   => 'fas fa-check-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'product_in_stock_color',
			[
				'label'     => esc_html__('Icon Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-stock .in-stock i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-product-stock .in-stock svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'product_in_stock_text_color',
			[
				'label'     => esc_html__('Text Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-stock .in-stock' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'out_of_stock_heading',
			[
				'label' => esc_html__( 'Out Of Stock', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_out_of_stock_icon',
			[
				'label'   => esc_html__('Select Icon', 'sastra-essential-addons-for-elementor'),
				'type'    => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
				'default' => [
					'value'   => 'fas fa-times-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'out_of_stock_availability_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Out of Stock', 'sastra-essential-addons-for-elementor' ),
				'default' => esc_html__( 'Out of Stock', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'product_out_of_stock_color',
			[
				'label'     => esc_html__('Icon Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-stock .out-of-stock i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-product-stock .out-of-stock svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'product_out_of_stock_text_color',
			[
				'label'     => esc_html__('Text Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-stock .out-of-stock' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'backorder_heading',
			[
				'label' => esc_html__( 'Available On Backorder', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'backorder_availability_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'On Backorder', 'sastra-essential-addons-for-elementor' ),
				'default' => esc_html__( 'On Backorder', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'product_available_on_backorder_icon',
			[
				'label'   => esc_html__('Select Icon', 'sastra-essential-addons-for-elementor'),
				'type'    => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
				'default' => [
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'product_available_on_backorder_color',
			[
				'label'     => esc_html__('Icon Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF4F40',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-stock .available-on-backorder i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-product-stock .available-on-backorder svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'product_available_on_backorder_text_color',
			[
				'label'     => esc_html__('Text Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-stock .available-on-backorder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_svg_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'SVG Size', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
                'separator' => 'before',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-stock svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				]
			]
		);

		$this->add_responsive_control(
			'product_icon_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Spacing', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
                'separator' => 'before',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-stock .in-stock i' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .tmpcoder-product-stock .out-of-stock i' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .tmpcoder-product-stock .available-on-backorder i' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .tmpcoder-product-stock svg' => 'margin-right: {{SIZE}}px;',
				]
			]
		);

		$this->add_responsive_control(
			'product_stock_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
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
					'{{WRAPPER}} .tmpcoder-product-stock p' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_stock_typography',
				'label' => esc_html__('Typography', 'sastra-essential-addons-for-elementor'),
				'selector' => '{{WRAPPER}} .tmpcoder-product-stock p',
				'fields_options' => [
					'typography'     => [
						'default' => 'custom',
					],
					'font_family'    => [
						'default' => '',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'sastra-essential-addons-for-elementor'),
						'default' => [
							'size' => '13',
							'unit' => 'px'
						],
						'size_units' => ['px'],
					],
				],
            ]
		);

        $this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, Controls_Manager::TAB_STYLE );
    }
    
    protected function render() {

    	if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<h2>'. esc_html__( 'WooCommerce is NOT active!', 'sastra-essential-addons-for-elementor' ) .'</h2>';
			return;
		}

        $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
        global $product;

		if( tmpcoder_is_preview_mode() ){
        	$lastId = tmpcoder_get_last_product_id();
			$product = wc_get_product($lastId);
        }
        else
        {
			$product = wc_get_product();
        }

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

        $icon = '';
        $stock_status = $product->get_stock_status();
        $availability = $product->get_availability();

        if ( 'instock' == $stock_status ) {
            $icon = isset($settings['product_in_stock_icon']) ? $settings['product_in_stock_icon'] : '';
        } elseif ( 'outofstock' == $stock_status ) {
            $icon = isset($settings['product_out_of_stock_icon']) ? $settings['product_out_of_stock_icon'] : '';
        } elseif ( 'onbackorder' == $stock_status ) {
            $icon = isset($settings['product_available_on_backorder_icon']) ? $settings['product_available_on_backorder_icon'] : '';
        }

		if ( $product->is_on_backorder() ) {
			$stock_html = $availability['availability'] ? $availability['availability'] : esc_html($settings['backorder_availability_text']);
		} elseif ( $product->is_in_stock() ) {

			$manage_stock   = $product->managing_stock();
			$stock_quantity = $product->get_stock_quantity();

			$stock_html = $availability['availability'] ? $availability['availability'] : esc_html($settings['in_stock_availability_text']);
			
			if ($manage_stock && $stock_quantity > 0 && $settings['availability_managing_text'] == 'yes') {
				
				$stock_html = str_replace('{stock}', $stock_quantity, $settings['in_stock_availability_managing_text']);
			}

		} else {
			$stock_html = $availability['availability'] ? $availability['availability'] : esc_html($settings['out_of_stock_availability_text']);
		}

		if ($stock_status === 'preorder' && $settings['preorder_text_display'] == 'yes' ) {
			$stock_html = $availability['availability'] ? $availability['availability'] : esc_html($settings['preorder_text']);
		}

        echo '<div class="tmpcoder-product-stock">';
            echo '<p class="' . esc_attr($availability['class']) . '">';

            if(!empty($icon)) {
                \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
            }
            
            echo wp_kses_post(apply_filters( 'woocommerce_stock_html', $stock_html, wp_kses_post($availability['availability']), $product ));
        echo '</div>';
    }
}