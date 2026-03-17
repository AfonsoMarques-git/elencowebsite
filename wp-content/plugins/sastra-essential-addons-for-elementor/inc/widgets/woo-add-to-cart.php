<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Button;
use Elementor\Controls_Stack;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;
use Elementor\Core\Responsive\Responsive;
use Elementor\Utils;
use Elementor\Includes\Widgets\Traits\Button_Trait;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Woo_Add_To_Cart extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-woo-add-to-cart';
	}

	public function get_title() {
		return esc_html__( 'Product Add to Cart', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-product-add-to-cart';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_product') ? [ 'tmpcoder-woocommerce-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-add-to-cart', 'product', 'add-to-cart' ];
	}

	public function get_script_depends() {
		return ['wc-add-to-cart', 'wc-add-to-cart-variation', 'wc-single-product', 'tmpcoder-product-add-to-cart'];
	}

	public function get_style_depends() {
		return ['tmpcoder-product-add-to-cart'];
	}


	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			// 'section_product_title',
			'section_add_to_cart_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'wc_style_warning',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The style of this widget can`t display in elementor editor, so pleace contact the frontend of your site', 'sastra-essential-addons-for-elementor' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

		$this->add_control(
			'ajax_add_to_cart',
			[
				'label' => esc_html__( 'Enable AJAX Add To Cart', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'text',
			[
				'label' => esc_html__( 'Button Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Add to Cart', 'sastra-essential-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Add to Cart', 'sastra-essential-addons-for-elementor' ),
			]
		);


		$this->add_control(
			'qty_label',
			[
				'label' => esc_html__( 'Quantity Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Qty : ', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				],
				'label_block' => false,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
                'default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_layout',
			[
				'label' => esc_html__( 'Select Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'label_block' => false,
				'options' => [
					'column' => [
						'title' => esc_html__( 'Vertical', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'row' => [
						'title' => esc_html__( 'Horizontal', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'prefix_class' => 'tmpcoder-add-to-cart-layout-',
				'selectors_dictionary' => [
					'row' => 'display: flex; align-items: center;',
					'column' => 'display: flex; flex-direction: column;',
				],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-add-to-cart .cart' => '{{VALUE}};'
                ],
				'default' => 'column',
				'separator' => 'before'
			]
		);

        $this->add_responsive_control(
            'add_to_cart_alignment',
            [
                'label'     => esc_html__('Text Align', 'sastra-essential-addons-for-elementor'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'Center',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .single_variation_wrap' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .added_to_cart' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_button_alignment',
            [
                'label'     => esc_html__('Button Horizontal Align', 'sastra-essential-addons-for-elementor'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'tmpcoder-product-adc-align-',
                'default'   => 'left',
				'condition' => [
					'add_to_cart_layout' => 'column'
				]
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_buttons_vr',
            [
                'label'     => esc_html__('Button Vertical Align', 'sastra-essential-addons-for-elementor'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'end'   => [
                        'title' => esc_html__('Top', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'start'  => [
                        'title' => esc_html__('Bottom', 'sastra-essential-addons-for-elementor'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-add-to-cart .cart button' => 'align-self: {{VALUE}}',
                    '{{WRAPPER}} .single_variation_wrap' => 'align-self: {{VALUE}}',
                ],
				'condition' => [
					'add_to_cart_layout' => 'row'
				]
            ]
        );

		$this->add_control( 
			'add_to_cart_variations_layout',
			[
				'label' => esc_html__( 'Choose An Option Display', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'options' => [
					'row' => esc_html__( 'Inline', 'sastra-essential-addons-for-elementor' ),
					'column' =>  esc_html__( 'Separate', 'sastra-essential-addons-for-elementor' )
				],
				'prefix_class' => 'tmpcoder-variations-layout-',
				'selectors_dictionary' => [
					'row' => '',
					'column' => 'display: flex; flex-direction: column;',
				],
                'selectors' => [
                    '{{WRAPPER}} .variations tr' => '{{VALUE}};',
                ],
				'default' => 'column',
				'separator' => 'before'
			]
		);

		$this->add_control(
			// 'product_buttons_layout',
			'add_to_cart_buttons_layout',
			[
				'label' => esc_html__( 'Button Display', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'row' => esc_html__( 'Inline', 'sastra-essential-addons-for-elementor' ),
					'column' => esc_html__( 'Separate', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-buttons-layout-',
				'selectors_dictionary' => [
					'row' => 'flex-direction: row;',
					'column' => 'flex-direction: column;',
				],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-variation-add-to-cart' => '{{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-simple-qty-wrap' => 'display: flex; {{VALUE}};'
                ],
				'default' => 'row',
			]
		);

		$this->add_control(
            'quantity_btn_position',
            [
                'label'   => esc_html__('Quantity Input Style', 'sastra-essential-addons-for-elementor'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'after',
				'prefix_class' => 'tmpcoder-product-qty-align-',
                'options' => [
                    'default' => esc_html__('Default (Browser)', 'sastra-essential-addons-for-elementor'),
                    'before' => esc_html__('Triggers Left', 'sastra-essential-addons-for-elementor'),
                    'after' => esc_html__('Triggers Right', 'sastra-essential-addons-for-elementor'),
                    'both' => esc_html__('Triggers Left-Right', 'sastra-essential-addons-for-elementor'),
                ],
				'render_type' => 'template',
            ]
        );

		$this->end_controls_section(); // End Controls Section
		
		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles ====================
		// Section: Add to Cart Quantity
		$this->start_controls_section(
			'section_style_qty-label',
			[
				'label' => esc_html__( 'Add to Cart Quantity Lable', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_qty-label_style' );

		$this->start_controls_tab(
			'tab_qty-label_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'qty-label_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'qty-label_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'qty-label_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E0E0E0',
				'selectors' => [
					
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'qty-label_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label' => 'transition-duration: {{VALUE}}s',
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_qty-label_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'qty-label_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'qty-label_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'qty-label_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '20',
							'unit' => 'px',
						],
					],
				]
			]
		);		

		$this->add_control(
			'qty_label_border_type',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'qty_label_border_width',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'qty_label_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'qty_label_radius',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'qty_label_margin',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// Styles ====================
		// Section: Add to Cart Quantity
		$this->start_controls_section(
			'section_style_quantity',
			[
				'label' => esc_html__( 'Add to Cart Quantity', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_quantity_style' );

		$this->start_controls_tab(
			'tab_quantity_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'quantity_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					
					// '{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'quantity_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'quantity_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E0E0E0',
				'selectors' => [
					
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'quantity_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty' => 'transition-duration: {{VALUE}}s',
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_quantity_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'quantity_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					
					// '{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'quantity_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'quantity_dimensions',
			[
				'label' => esc_html__( 'Quantity', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_qty_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '20',
							'unit' => 'px',
						],
					],
				]
			]
		);		

		$this->add_responsive_control(
			'quantity_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'add_to_cart_quantity_height',
			[
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 43,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'height: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}}.tmpcoder-product-qty-align-both .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'add_to_cart_quantity_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 51,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty' => 'width: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'add_to_cart_quantity_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}}.tmpcoder-buttons-layout-row .tmpcoder-product-add-to-cart .tmpcoder-simple-qty-wrap .tmpcoder-quantity-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-buttons-layout-column .tmpcoder-product-add-to-cart .tmpcoder-simple-qty-wrap .tmpcoder-quantity-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-buttons-layout-row .tmpcoder-product-add-to-cart .variations_button .tmpcoder-quantity-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-buttons-layout-column .tmpcoder-product-add-to-cart .variations_button .tmpcoder-quantity-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'quantity_border_type',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'quantity_border_width',
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
					// '{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .quantity .qty' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					

				],
				'condition' => [
					'quantity_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'quantity_radius',
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
					// '{{WRAPPER}}.tmpcoder-product-qty-align-before .qty' => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0 !important;',
					// '{{WRAPPER}}.tmpcoder-product-qty-align-before .tmpcoder-quantity-wrapper i:first-child' => 'border-radius: {{TOP}}{{UNIT}} 0 0 0 !important;',
					// '{{WRAPPER}}.tmpcoder-product-qty-align-before .tmpcoder-quantity-wrapper i:last-child' => 'border-radius: 0 0 0 {{LEFT}}{{UNIT}} !important;',
					
					// '{{WRAPPER}}.tmpcoder-product-qty-align-after .qty' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{Left}}{{UNIT}} !important;',
					// // '{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i:first-child' => 'border-radius: 0 {{RIGHT}}{{UNIT}} 0 0 !important;',
					// // '{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i:last-child' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} 0 !important;',

					// '{{WRAPPER}}.tmpcoder-product-qty-align-both .qty' => 'border-radius: 0 !important;',
					// '{{WRAPPER}}.tmpcoder-product-qty-align-both .tmpcoder-quantity-wrapper i:first-child' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{Left}}{{UNIT}} !important;',
					// '{{WRAPPER}}.tmpcoder-product-qty-align-both .tmpcoder-quantity-wrapper i:last-child' => 'border-radius: 0 {{Right}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0 !important;',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					// '{{WRAPPER}} .tmpcoder-product-add-to-cart .qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',

					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'qty_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					// '{{WRAPPER}} .tmpcoder-product-add-to-cart .qty' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'qty_padding',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					// '{{WRAPPER}} tmpcoder-product-add-to-cart .qty' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		/* Quantity Trigger Start */

		$this->add_control(
			'quantity_triggers_heading',
			[
				'label' => esc_html__( 'Trigger Icons', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'trigger_icon_type',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'plus-minus' => esc_html__( 'Plus Minus ', 'sastra-essential-addons-for-elementor' ),
					'up-down' => esc_html__( 'Up Down', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'plus-minus',
			]
		);

		$this->add_responsive_control(
			'quantity_icons_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'quantity_icons_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'trigger_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E0E0E0',
				'selectors' => [
					// '{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i' => 'color: {{VALUE}}!important;',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'trigger_hover_color',
			[
				'label'  => esc_html__( 'Hover Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E0E0E0',
				'selectors' => [
					// '{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i:hover' => 'color: {{VALUE}}!important;',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i:hover' => 'color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'trigger_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E0E0E0',
				'selectors' => [
					// '{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i' => 'border-color: {{VALUE}}!important;',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .tmpcoder-quantity-wrapper i' => 'border-color: {{VALUE}}!important;',
				],
				'condition' => [
					'trigger_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'trigger_padding',
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
					'{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'plus_triggers_heading',
			[
				'label' => esc_html__( 'Plus Icons', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'trigger_border_type',
			[
				'label' => esc_html__( 'Border Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'sastra-essential-addons-for-elementor' ),
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'solid' => esc_html__( 'Solid', 'sastra-essential-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'sastra-essential-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'sastra-essential-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'sastra-essential-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i:first-child' => 'border-style: {{VALUE}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-both .tmpcoder-quantity-wrapper i:last-child' => 'border-style: {{VALUE}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-before .tmpcoder-quantity-wrapper i:first-child' => 'border-style: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'trigger_border_width',
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
					'{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i:first-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-both .tmpcoder-quantity-wrapper i:last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-before .tmpcoder-quantity-wrapper i:first-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
				'condition' => [
					'trigger_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'trigger_radius',
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
					'{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i:first-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-both .tmpcoder-quantity-wrapper i:last-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-before .tmpcoder-quantity-wrapper i:first-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'minus_triggers_heading',
			[
				'label' => esc_html__( 'Minus Icons', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'minus_trigger_border_type',
			[
				'label' => esc_html__( 'Border Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'sastra-essential-addons-for-elementor' ),
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'solid' => esc_html__( 'Solid', 'sastra-essential-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'sastra-essential-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'sastra-essential-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'sastra-essential-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i:last-child' => 'border-style: {{VALUE}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-both .tmpcoder-quantity-wrapper i:first-child' => 'border-style: {{VALUE}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-before .tmpcoder-quantity-wrapper i:last-child' => 'border-style: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'minus_trigger_border_width',
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
					'{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i:last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-both .tmpcoder-quantity-wrapper i:first-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-before .tmpcoder-quantity-wrapper i:last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
				'condition' => [
					'trigger_border_type!' => 'none',
				]
			]
		);
		
		$this->add_control(
			'minus_trigger_radius',
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
					'{{WRAPPER}}.tmpcoder-product-qty-align-after .tmpcoder-quantity-wrapper i:last-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-both .tmpcoder-quantity-wrapper i:first-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}}.tmpcoder-product-qty-align-before .tmpcoder-quantity-wrapper i:last-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			]
		);

		/* Quantity Trigger End */

		$this->end_controls_section();

		// Styles ====================
		// Section: Add to Cart Button
		$this->start_controls_section(
			'section_style_add_to_cart',
			[
				'label' => esc_html__( 'Add to Cart Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_add_to_cart_style' );

		$this->start_controls_tab(
			'tab_add_to_cart_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'add_to_cart_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart a.added_to_cart' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart a.added_to_cart' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart,',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '16',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'add_to_cart_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'transition-duration: {{VALUE}}'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_add_to_cart_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'add_to_cart_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2D26ED',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow_hr',
				'selector' => '{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button:hover, {{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'add_to_cart_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 165,
				],
				'selectors' => [
					'{{WRAPPER}}  .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart' => 'width: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'add_to_cart_height',
			[
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 43,
				],
				'selectors' => [
					'{{WRAPPER}}  .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart' => 'height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'table_distance',
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-add-to-cart-layout-row table' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-add-to-cart-layout-column table' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-add-to-cart-layout-row .tmpcoder-product-add-to-cart form.cart .woocommerce-variation-add-to-cart' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-add-to-cart-layout-column .tmpcoder-product-add-to-cart form.cart .woocommerce-variation-add-to-cart' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'add_to_cart_margin',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'add_to_cart_border_type',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'add_to_cart_border_width',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'add_to_cart_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'add_to_cart_radius',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-product-add-to-cart  a.added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Grouped -------
		$this->start_controls_section(
			'section_grouped_styles',
			[
				'label' => esc_html__( 'Grouped Product', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'add_to_cart_group',
			[
				'label'     => esc_html__('Variable Product', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'add_to_cart_group_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFFF7',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_group_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_group_border_color',
			[
				'label'     => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'group_title_heading',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grouped_title_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__label a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__label label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'grouped_title_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__label a, {{WRAPPER}} .woocommerce-grouped-product-list-item__label label, {{WRAPPER}} .woocommerce-grouped-product-list-item .button',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'grouped_price_heading',
			[
				'label' => esc_html__( 'Price', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grouped_price_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__price span' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'grouped_price_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__price span',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'grouped_table_border_type',
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
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'grouped_table_border_width',
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
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'grouped_table_border_type!' => 'none',
				]
			]
		);

		$this->add_responsive_control(
			'grouped_product_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 12,
					'right' => 12,
					'bottom' => 12,
					'left' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart form.cart .group_table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Variations -------
		$this->start_controls_section(
			'section_variation_styles',
			[
				'label' => esc_html__( 'Variable Product', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'add_to_cart_label',
			[
				'label'     => esc_html__('Attribute Name', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'add_to_cart_label_color',
			[
				'label'     => esc_html__('Label Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .variations th label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_label_border_color',
			[
				'label'     => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} form.cart .variations th' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} form.cart .variations td' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_label_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFFF2',
				'selectors' => [
					'{{WRAPPER}} .variations tr th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_label_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .variations tr:nth-child(even) th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_variation_names',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .variations th.label label',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_responsive_control(
			'variation_name_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 7,
					'bottom' => 7,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .variations th.label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'add_to_cart_value',
			[
				'label'     => esc_html__('Attribute Value', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'add_to_cart_value_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .variations tr td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_value_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .variations tr:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'variations_table_label_width',
			[
				'label' => esc_html__( 'Label Width', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-variations-layout-row .variations tr th' => 'width: {{SIZE}}%;',
					'{{WRAPPER}}.tmpcoder-variations-layout-column .variations tr th' => 'width: {{SIZE}}%;',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'variations_table_border_type',
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
					'{{WRAPPER}} form.cart .variations td' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} form.cart .variations th' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variations_table_border_width',
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
					'{{WRAPPER}} form.cart .variations td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} form.cart .variations th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'variations_table_border_type!' => 'none',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_style_variations_select',
			[
				'label' => esc_html__( 'Variations Select', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->start_controls_tabs(
			'variation_select_style_tabs'
		);
		
		$this->start_controls_tab(
			'variation_select_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_color',
			[
				'label'     => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .variations select' => 'color: {{VALUE}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_border_color',
			[
				'label'     => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .variations select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_bg_color',
			[
				'label'     => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .variations select' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_variation_select',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .variations select, {{WRAPPER}} .variations option , .variations .cfvsw-swatches-option',
			]
		);

		$this->add_control(
			'variations_select_border_type',
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
					'{{WRAPPER}} .variations select' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option' => 'border-style: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'variations_select_border_width',
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
					'{{WRAPPER}} .variations select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'variations_select_border_type!' => 'none',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_select_focus_tab',
			[
				'label' => esc_html__( 'Focus', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_color_focus',
			[
				'label'     => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .variations select:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_border_color_focus',
			[
				'label'     => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .variations select:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_bg_color_focus',
			[
				'label'     => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .variations select:focus' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'variations_select_border_type_focus',
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
					'{{WRAPPER}} .variations select:focus' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option:hover' => 'border-style: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'variations_select_border_width_focus',
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
					'{{WRAPPER}} .variations select:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'variations_select_border_type_focus!' => 'none',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_select_active_tab',
			[
				'label' => esc_html__( 'Active', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_color_active',
			[
				'label'     => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .variations .cfvsw-selected-swatch' => 'color: {{VALUE}}!important;',
					// '{{WRAPPER}} .variations select:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_border_color_active',
			[
				'label'     => esc_html__('Border Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .variations .cfvsw-selected-swatch' => 'border-color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_bg_color_active',
			[
				'label'     => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .variations .cfvsw-selected-swatch' => 'background-color: {{VALUE}}!important;',
					// '{{WRAPPER}} .variations select:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'variations_select_border_type_active',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .variations .cfvsw-selected-swatch' => 'border-style: {{VALUE}}!important;',
					// '{{WRAPPER}} .variations select:focus' => 'border-style: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'variations_select_border_width_active',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .variations .cfvsw-selected-swatch' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					// '{{WRAPPER}} .variations .cfvsw-swatches-option:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'variations_select_border_type_active!' => 'none',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'variation_select_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} form.cart .variations select' => 'width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} form.cart .variations .cfvsw-swatches-option' => 'width: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'variation_select_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .variations select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'variation_select_margin',
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
					'{{WRAPPER}} form.cart .variations select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} form.cart .variations .cfvsw-swatches-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					// '{{WRAPPER}} form.cart .variations .cfvsw-swatches-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
				]
			]
		);

		$this->add_control(
			'variations_select_border_radius',
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
					'{{WRAPPER}} .variations select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .variations .cfvsw-swatches-option' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // variations select section

		$this->start_controls_section(
			'section_style_variations_description',
			[
				'label' => esc_html__( 'Variations Item Info', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'variation_description_heading',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_description_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-description p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variation_description_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .woocommerce-variation-description p',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'variation_description_alignment',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-description p' => 'text-align: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_price_heading',
			[
				'label' => esc_html__( 'Price', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_price_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-price span' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variation_price_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .woocommerce-variation-price span',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'variation_price_alignment',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'description' => esc_html__('For Variable Products Only', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-price' => 'text-align: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_availability_heading',
			[
				'label' => esc_html__( 'Availability', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_availability_color_in_stock',
			[
				'label'  => esc_html__( 'In Stock Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-availability p.stock' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-variation-availability p.in-stock' => 'color: {{VALUE}}',
					'{{WRAPPER}} p.stock' => 'color: {{VALUE}}',
					'{{WRAPPER}} p.in-stock' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'variation_availability_color_out_of_stock',
			[
				'label'  => esc_html__( 'Out Of Stock Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4F40',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-availability p.stock.out-of-stock' => 'color: {{VALUE}}',
					'{{WRAPPER}} p.stock.out-of-stock' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variation_availability_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .woocommerce-variation-availability p.stock, {{WRAPPER}} .woocommerce-variation-availability p.stock'
			]
		);

		$this->add_control(
			'variation_availability_alignment',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-availability p.stock' => 'text-align: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Reset Options Button
		$this->start_controls_section(
			'section_style_reset',
			[
				'label' => esc_html__( 'Reset Options Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'reset_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CECECE',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .reset_variations' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'reset_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .reset_variations' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'reset_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .reset_variations' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'reset_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-product-add-to-cart .reset_variations',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '16',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'reset_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .reset_variations' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'reset_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 20,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .reset_variations' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'reset_border_type',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .reset_variations' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'reset_border_width',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .reset_variations' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'reset_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'reset_radius',
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
					'{{WRAPPER}} .tmpcoder-product-add-to-cart .reset_variations' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function change_clear_text() {
	   echo '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'sastra-essential-addons-for-elementor' ) . '</a>';
	}
 
	function custom_wc_add_to_cart_message( $message, $product_id ) { 
		// Translators: %s is the product title.
		$message = sprintf(esc_html__('%s has been added to your cart. Thank you for shopping!', 'sastra-essential-addons-for-elementor'), get_the_title( $product_id ) ); 
		return $message; 
	}

	function action_woocommerce_add_to_cart() {
		return 'product is added to your cart!';
	}

	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;

		ob_start();

		?>
		<a class="cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'sastra-essential-addons-for-elementor' ); ?>"><?php WC()->cart->get_cart_contents_count(); ?></a>

		<?php

		$fragments['a.cart-customlocation'] = ob_get_clean();

		return $fragments;

	}

	function woocommerce_add_to_cart_button_text_archives() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		$instance = $this;

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		$instance->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-left',
				],
			],
			'text' => [
				'class' => 'elementor-button-text',
			],
		] );

		echo wp_kses("<span ".$instance->get_render_attribute_string( 'content-wrapper' ).">", tmpcoder_wp_kses_allowed_html());

		if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : 
			echo wp_kses("<span ".$instance->get_render_attribute_string( 'icon-align' ).">", tmpcoder_wp_kses_allowed_html());

			if ( $is_new || $migrated ) {

				Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
			}else{

				echo "<i class=". esc_attr( $settings['icon'] ) . " aria-hidden='true'></i>";
			}

			echo "</span>";
		endif;

		echo wp_kses("<span ".$instance->get_render_attribute_string( 'text' ).">". esc_html($settings['text']) ."</span>", tmpcoder_wp_kses_allowed_html());

		echo "</span>";
	}

	protected function render() {
			
		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<h2>'. esc_html__( 'WooCommerce is NOT active!', 'sastra-essential-addons-for-elementor' ) .'</h2>';
			return;
		}

		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		
		$icon_class = '';
		$icon_fa_plus = 'fa-plus';
		$icon_fa_minus = 'fa-minus';
		if (isset($settings['trigger_icon_type']) && $settings['trigger_icon_type'] == 'up-down') {
			$icon_class = 'tmpcoder-up-down-icon';
			$icon_fa_plus = 'fa-angle-up';
			$icon_fa_minus = 'fa-angle-down';
		}

		$this->add_render_attribute(
			'add_to_cart_wrapper',
			[
				'id' => 'add-to-cart-attributes',
				'class' => [ 'tmpcoder-product-add-to-cart', $icon_class ],
				'layout-settings' => $settings['quantity_btn_position'],
				'data-ajax-add-to-cart' => $settings['ajax_add_to_cart']
			]
		);
		
		global $product;

		// Get Product
		if( tmpcoder_is_preview_mode() ){
        	$lastId  = tmpcoder_get_last_product_id();
			$product = wc_get_product($lastId);
        }
        else
        {
			$product = wc_get_product();
        }

		if ( ! $product ) {
			return;
		}

		$btn_arg = [
			'position' => $settings['quantity_btn_position']
		];

		add_action('woocommerce_before_add_to_cart_quantity', function () use ($btn_arg, $product, $icon_fa_plus, $icon_fa_minus) {
			if ($product->is_type('simple')) {
				echo '<div class="tmpcoder-simple-qty-wrap">';
			}
			echo '<div class="tmpcoder-quantity-wrapper">';

			if($btn_arg['position'] === 'before') {
				echo wp_kses('<div class="tmpcoder-add-to-cart-icons-wrap"><i class="fas '.esc_attr($icon_fa_plus).'"></i><i class="fas '.$icon_fa_minus.'"></i></i></div>', tmpcoder_wp_kses_allowed_html());
			}

			if($btn_arg['position'] === 'both') { 
				
				echo '<i class="fas '.esc_attr($icon_fa_minus).'"></i>';
			}

		},10);

		add_action('woocommerce_after_add_to_cart_quantity', function () use ($btn_arg, $icon_fa_plus, $icon_fa_minus) {

			if($btn_arg['position'] === 'after') {
				echo wp_kses('<div class="tmpcoder-add-to-cart-icons-wrap"><i class="fas '.esc_attr($icon_fa_plus).'"></i><i class="fas '.esc_attr($icon_fa_minus).'"></i></i></div>', tmpcoder_wp_kses_allowed_html());
			}

			if($btn_arg['position'] === 'both') { 
				
				echo '<i class="fas '.esc_attr($icon_fa_plus).'"></i>';
			}

			echo '</div>';

		});

		add_action('woocommerce_after_add_to_cart_button', function () use ($product) {
			
			if ($product->is_type('simple')) {
				echo '</div>';
			}
		});

		if ( 'yes' !== $settings['ajax_add_to_cart'] ) {
			do_action( 'woocommerce_before_single_product' ); // locate it in condition if ajax activated
		}

		if (isset($settings['qty_label']) && !empty($settings['qty_label']) && $settings['qty_label'] != '') {

			$label = $settings['qty_label'];

			add_action( 'woocommerce_before_add_to_cart_quantity', function() use ($label)
				{
				 	$this->add_qty_label_before_add_to_card( $label ); 
				}
			,5);
		}
		
		add_filter( 'wc_add_to_cart_message', [$this,'custom_wc_add_to_cart_message'], 10, 2 );
		
		add_filter('add_to_cart_fragments', [$this, 'woocommerce_header_add_to_cart_fragment']);

		// add_action( 'woocommerce_add_to_cart', 'action_woocommerce_add_to_cart', 10, 6 );

        if (!$product->is_type('external')) {
		    // Change add to cart text on single product page
		    add_filter( 'woocommerce_product_single_add_to_cart_text', [$this,'woocommerce_add_to_cart_button_text_archives'] );  
        }
		
		echo wp_kses('<div '. $this->get_render_attribute_string( 'add_to_cart_wrapper' ) .'>', tmpcoder_wp_kses_allowed_html());
		
		woocommerce_template_single_add_to_cart();
        
		echo '</div>';
	}
	
	function add_qty_label_before_add_to_card($label)
	{
		echo wp_kses('<span class="qty-label">'.esc_html( $label ).' </span>', tmpcoder_wp_kses_allowed_html());
	}
}