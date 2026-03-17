<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Product_Mini_Cart extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-product-mini-cart';
	}

	public function get_title() {
		return esc_html__( 'Product Mini Cart', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-product-images';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_header') ? [ 'tmpcoder-header-builder-widgets'] : ['tmpcoder-woo-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'spexo', 'woocommerce', 'product-mini-cart', 'product', 'mini', 'cart' ];
	}

	public function get_script_depends() {
		return ['tmpcoder-product-mini-cart'];
	}

	public function get_style_depends() {
		return ['tmpcoder-product-mini-cart'];
	}

	public function show_mini_cart_update_qty() {
		$this->add_control(
			'show_mini_cart_update_qty_pro',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Allow Update Quantity %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'class' => 'tmpcoder-pro-controle',
			]
		);
	}

	public function add_control_mini_cart_style() {
		$this->add_control(
			'mini_cart_style',
			[
				'label' => esc_html__( 'Cart Content', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'render_type' => 'template',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'pro-dd' => esc_html__( 'Dropdown (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-sb' => esc_html__( 'Sidebar (Pro)', 'sastra-essential-addons-for-elementor' )
				],
				'default' => 'none'
			]
		); 
	}

	public function add_controls_group_mini_cart_style() {}

	public function add_section_style_mini_cart() {}

	public function add_section_style_remove_icon() {}

	public function add_section_style_buttons() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_mini_cart_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'tmpcoder_particles_apply_changes',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-update-preview editor-tmpcoder-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
				'separator' => 'after'
			]
		);

		if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) {
			$this->add_control(
				'select_icon',
				[
					'label' => esc_html__('Select Icon', 'sastra-essential-addons-for-elementor'),
					'type' => Controls_Manager::ICONS,
					'skin' => 'inline',
					'label_block' => false,
					'exclude' => ['svg'],
					'default' => [
						'value' => 'fas fa-shopping-cart',
						'library' => 'solid',
					]
				]
			);
		} else {
			$this->add_control(
				'icon',
				[
					'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
						'cart-light' => esc_html__( 'Cart Light', 'sastra-essential-addons-for-elementor' ),
						'cart-medium' => esc_html__( 'Cart Medium', 'sastra-essential-addons-for-elementor' ),
						'cart-solid' => esc_html__( 'Cart Solid', 'sastra-essential-addons-for-elementor' ),
						'basket-light' => esc_html__( 'Basket Light', 'sastra-essential-addons-for-elementor' ),
						'basket-medium' => esc_html__( 'Basket Medium', 'sastra-essential-addons-for-elementor' ),
						'basket-solid' => esc_html__( 'Basket Solid', 'sastra-essential-addons-for-elementor' ),
						'bag-light' => esc_html__( 'Bag Light', 'sastra-essential-addons-for-elementor' ),
						'bag-medium' => esc_html__( 'Bag Medium', 'sastra-essential-addons-for-elementor' ),
						'bag-solid' => esc_html__( 'Bag Solid', 'sastra-essential-addons-for-elementor' )
					],
					'default' => 'cart-medium',
					'prefix_class' => 'tmpcoder-toggle-icon-',
				]
			);
		}

		$this->add_control(
			'toggle_text',
			[
				'label' => esc_html__( 'Toggle Prefix', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'price' => esc_html__( 'Total Price', 'sastra-essential-addons-for-elementor' ),
					'title' => esc_html__( 'Extra Text', 'sastra-essential-addons-for-elementor' )
				],
				'default' => 'price',
			]
		);

		$this->add_control(
			'toggle_title',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Cart', 'sastra-essential-addons-for-elementor' ),
				'default' => esc_html__( 'Cart', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'toggle_text' => 'title'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_button_alignment',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} #tmpcoder-mini-cart' => 'text-align: {{VALUE}};',
				]
			]
		);


		$this->add_control_mini_cart_style(); 

		$this->show_mini_cart_update_qty(); 

		$this->add_controls_group_mini_cart_style();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'product-mini-cart', 'mini_cart_style', ['pro-dd', 'pro-sb'] );

		$this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'product-mini-cart', [
			'Show Mini Cart Content (Products added to cart) on Mini Cart icon click',
			'Display Mini Cart Content as Dropdown or Off-Canvas Layout'
		] );
		
		// Tab: Styles ==============
		// Section: Toggle Button ----------
		$this->start_controls_section(
			'section_mini_cart_button',
			[
				'label' => esc_html__( 'Cart Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'toggle_btn_cart_icon',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'toggle_btn_icon_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-btn-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-mini-cart-btn-icon svg path' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'toggle_btn_icon_hover_color',
			[
				'label'  => esc_html__( 'Hover Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-btn-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-mini-cart-btn-icon svg:hover path' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_icon_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-mini-cart-btn-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'toggle_btn_cart_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Extra Text', 'sastra-essential-addons-for-elementor' ),
				'separator' => 'before',
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_control(
			'mini_cart_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-toggle-btn' => 'color: {{VALUE}}',
				],
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_control(
			'mini_cart_hover_color',
			[
				'label'  => esc_html__( 'Hover Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-toggle-btn:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
                'selector' => '{{WRAPPER}} .tmpcoder-mini-cart-toggle-btn, {{WRAPPER}} .tmpcoder-mini-cart-icon-count',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '13',
							'unit' => 'px',
						]
					]
				]
            ]
        );

		$this->add_responsive_control(
			'toggle_text_distance',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-btn-text' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-mini-cart-btn-price' => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_control(
			'mini_cart_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-toggle-btn' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-toggle-btn' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mini_cart_btn_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-mini-cart-toggle-btn',
			]
		);

		$this->add_responsive_control(
			'mini_cart_btn_padding',
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
					'{{WRAPPER}} .tmpcoder-mini-cart-toggle-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_btn_border_type',
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
					'{{WRAPPER}} .tmpcoder-mini-cart-toggle-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_btn_border_width',
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
					'{{WRAPPER}} .tmpcoder-mini-cart-toggle-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'mini_cart_btn_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'mini_cart_btn_border_radius',
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
					'{{WRAPPER}} .tmpcoder-mini-cart-toggle-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_item_count',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Item Count', 'sastra-essential-addons-for-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'toggle_btn_item_count_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-icon-count' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'toggle_btn_item_count_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-icon-count' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_font_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-icon-count' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_box_size',
			[
				'label' => esc_html__( 'Box Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-icon-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-mini-cart-icon-count' => 'bottom: {{SIZE}}{{UNIT}}; left: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		// Tab: Styles ==============
		// Section: Mini Cart ---------------
		$this->add_section_style_mini_cart();

		// Tab: Styles ==============
		// Section: Remove Icon ----------
		$this->add_section_style_remove_icon();

		// Tab: Style ==============
		// Section: Buttons --------
		$this->add_section_style_buttons();

    } 

	public function render_mini_cart_toggle($settings) {

		if ( null === WC()->cart ) {
			return;
		}

		$product_count = WC()->cart->get_cart_contents_count();
		$sub_total = WC()->cart->get_cart_subtotal();
		$counter_attr = 'data-counter="' . $product_count . '"';
		if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) {
			$icon_class = $settings['select_icon']['value'];
		} else {
			$icon_class = 'eicon';
		}

		if(is_array($icon_class)){
			ob_start();
			\Elementor\Icons_Manager::render_icon( $settings['select_icon'], [ 'aria-hidden' => 'true' ] );
			$icon = ob_get_clean();
			$icon_wrapper = !empty($settings['select_icon']) ? '<span>'. $icon .'</span>' : '';
		}

		if ( !is_plugin_active('sastra-essential-addons-for-elementor-pro/sastra-essential-addons-for-elementor-pro.php') || 'none' == $settings['mini_cart_style'] ) {
			// global $woocommerce;
			$cart_url = wc_get_cart_url();
		} else {
			$cart_url = '#'; 
		}
		?>

		<span class="tmpcoder-mini-cart-toggle-wrap">
			<a href="<?php echo esc_url($cart_url, 'sastra-essential-addons-for-elementor'); ?>" class="tmpcoder-mini-cart-toggle-btn" aria-expanded="false">
				<?php 
				if ( 'none' !== $settings['toggle_text']) :
					if ( 'price' == $settings['toggle_text'] ) { ?>
						<span class="tmpcoder-mini-cart-btn-price">
							<?php echo wp_kses_post($sub_total); ?>
						</span>
					<?php } else { ?>
						<span class="tmpcoder-mini-cart-btn-text">
							<?php echo esc_html($settings['toggle_title']); ?>
						</span>
					<?php } 
				endif; ?>
				<span class="tmpcoder-mini-cart-btn-icon" <?php echo esc_attr($counter_attr, 'sastra-essential-addons-for-elementor'); ?>>
					<?php
					$icon_class = (is_array($icon_class)) ? 'custom-svg-icon' : $icon_class ;
					?>

					<i class="<?php echo esc_attr($icon_class, 'sastra-essential-addons-for-elementor'); ?>">
						<?php echo wp_kses( ($icon_class == 'custom-svg-icon' ? $icon : ''), tmpcoder_wp_kses_allowed_html() ); ?>
                        <span class="tmpcoder-mini-cart-icon-count <?php echo esc_attr($product_count ? '' : 'tmpcoder-mini-cart-icon-count-hidden'); ?>"><span><?php echo esc_html($product_count); ?></span></span>
                    </i>
				</span>
			</a>
		</span>
		<?php
	}

	public function render_close_cart_icon () {}

	public static function render_mini_cart($settings) {}
    
    protected function render() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<h4>'. esc_html__( 'WooCommerce is NOT active!', 'sastra-essential-addons-for-elementor' ) .'</h4>';
			return;
		}
    	
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if (isset($settings['show_mini_cart_update_qty']) && $settings['show_mini_cart_update_qty'] == 'yes') {
			
			add_filter( 'woocommerce_widget_cart_item_quantity', function( $html, $cart_item, $cart_item_key ) {
			    $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			    $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
			    $product_subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
			    $quantity = $cart_item['quantity'];
			    
			    ob_start();
			    ?>
			    <div class="tmpcoder-mini-cart-quantity">
			        <div class="mini-cart-quantity-row">
			            <div class="mini-cart-quantity mini-cart-qty-wrap">
			                <button class="mini-cart-minus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">-</button>
			                <input type="number" min="1" value="<?php echo esc_attr( $quantity ); ?>" data-key="<?php echo esc_attr( $cart_item_key ); ?>" class="mini-cart-qty-input" />
			                <button class="mini-cart-plus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">+</button>
			                <span class="mini-cart-loader" style="display:none;"></span>
			            </div>
			            <span class="quantity price-right"><?php echo wp_kses_post( $product_subtotal ); ?></span>
			        </div>
			    </div>
			    </div>
			    <?php
			    return ob_get_clean();

			}, 10, 3 );
		}

		$this->add_render_attribute(
			'mini_cart_attributes',
			[
				'data-animation' => (tmpcoder_is_availble() && isset($settings['mini_cart_entrance_speed'])) ? esc_html($settings['mini_cart_entrance_speed'], 'sastra-essential-addons-for-elementor') : '',
				'data-update-qty' => isset($settings['show_mini_cart_update_qty']) && $settings['show_mini_cart_update_qty'] == 'yes' ? true : false,

			]
		);

        echo '<div id="tmpcoder-mini-cart" class="tmpcoder-mini-cart-wrap woocommerce"' . wp_kses_post($this->get_render_attribute_string( 'mini_cart_attributes' ) ) . '>';
			echo '<span class="tmpcoder-mini-cart-inner">';
				$this->render_mini_cart_toggle($settings);
				if ( 'none' !== $settings['mini_cart_style'] ) {
					$this->render_mini_cart($settings);
				}
			echo '</span>';
        echo '</div>';
    }    
}
