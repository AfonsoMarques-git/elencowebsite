<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TMPCODER_Woo_Product_Content extends Widget_Base {
	
	public function get_name() {
		
		return 'tmpcoder-woo-product-content';
	}

	public function get_title() {
		return esc_html__( 'Product Content', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-post-content';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_product') ? [ 'tmpcoder-woocommerce-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'content', 'post', 'product' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, Controls_Manager::TAB_STYLE );
	}

	// Render Post Content

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
        	$post = get_post( $lastId );
        	setup_postdata( $lastId );
        }
        else
        {
            if ( is_object($product) ){
                $post = get_post( $product->get_id() );
                setup_postdata( $product->get_id() );
            }
        }

        if ( empty( $product ) ) {
            return;
        }
        
        if ($post) {
			
			$description = $product->get_description();
        
            // Print the description
            echo '<div class="tmpcoder-product-description">';
                echo '<p>';
                echo wp_kses($description, tmpcoder_wp_kses_allowed_html());
                echo '</p>';
            echo '</div>';
        } else {
            echo '<div class="tmpcoder-product-description">';
                echo '<p>'. esc_html__('Product not found', 'sastra-essential-addons-for-elementor') .'</p>';
            echo '</div>';
        }
	}

	public function render_plain_content() {}
}
