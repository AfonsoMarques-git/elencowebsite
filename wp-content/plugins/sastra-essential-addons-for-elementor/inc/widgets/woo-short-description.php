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

class TMPCODER_Woo_Short_Description extends Widget_Base {

	public function get_name() {
		return 'tmpcoder-woo-short-description';
	}

	public function get_title() {
		return esc_html__( 'Product Excerpt', 'sastra-essential-addons-for-elementor' );
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_product') ? [ 'tmpcoder-woocommerce-builder-widgets'] : [];
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-product-description';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'text', 'description', 'product' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_description_style',
			[
				'label' => esc_html__( 'Style', 'sastra-essential-addons-for-elementor' ),
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-product-details__short-description',
			]
		);

		$this->add_responsive_control(
	        'spacing',
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
	                '.woocommerce {{WRAPPER}} .woocommerce-product-details__short-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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

		global $product;

		if( tmpcoder_is_preview_mode() ){
        	$last_id = tmpcoder_get_last_product_id();
			$product = wc_get_product($last_id);

			$short_description = get_the_excerpt( $last_id );
            $short_description = apply_filters( 'woocommerce_short_description', $short_description );
            if ( empty( $short_description ) ) { 
                echo '<p>'.esc_html__('The short description does not set this product.', 'sastra-essential-addons-for-elementor').'</p>'; 
            }else{
                ?>
                    <div class="woocommerce-product-details__short-description"><?php echo wp_kses_post( $short_description ); ?></div>
                <?php
            }
        }
        else
        {
			$product = wc_get_product();
			if ( ! $product ) {
				return;
			}

			wc_get_template( 'single-product/short-description.php' );
        }
	}

	public function render_plain_content() {}

	public function get_group_name() {
		return 'woocommerce';
	}
}
