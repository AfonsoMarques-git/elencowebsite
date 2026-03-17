<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Product_Rating extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-product-rating';
	}

	public function get_title() {
		return esc_html__( 'Product Rating', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-product-rating';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_product') ? [ 'tmpcoder-woocommerce-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-rating', 'product', 'rating' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-product-rating' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_rating',
			[
				'label' => esc_html__( 'Styles', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_rating_layout',
			[
				'label' => esc_html__( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'flex' => esc_html__('Horizontal', 'sastra-essential-addons-for-elementor'),
					'block' => esc_html__('Vertical', 'sastra-essential-addons-for-elementor'),
				],
                'prefix_class' => 'tmpcoder-product-rating-',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-rating' => 'display: {{VALUE}}; align-items: center;'
                ],
				'default' => 'flex',
			]
		);

		$this->add_control(
			'product_rating_show_text',
			[
				'label' => esc_html__( 'Show Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'tmpcoder-pr-show-text-'
			]
		);

		$this->add_responsive_control(
			'product_rating_alignment',
			[
				'label'        => esc_html__('Alignment', 'sastra-essential-addons-for-elementor'),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'    => [
						'title' => esc_html__('Left', 'sastra-essential-addons-for-elementor'),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__('Center', 'sastra-essential-addons-for-elementor'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__('Right', 'sastra-essential-addons-for-elementor'),
						'icon'  => 'eicon-text-align-right',
					]
				],
				'prefix_class' => 'tmpcoder-product-rating-',
				'default'      => 'left',
                'selectors' => [
                    '{{WRAPPER}}.tmpcoder-product-rating-block .tmpcoder-woo-rating' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}}.tmpcoder-product-rating-block .woocommerce-review-link' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}}.tmpcoder-product-rating-flex .tmpcoder-product-rating' => 'justify-content: {{VALUE}};'
                ],
				'separator'    => 'after',
			]
		);

		$this->add_control(
			'product_rating_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-woo-rating i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#D2CDCD',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-woo-rating i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_text_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} a.woocommerce-review-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_text_color_hover',
			[
				'label' => esc_html__( 'Text Hover Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} a.woocommerce-review-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_rating_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-product-rating .woocommerce-review-link',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'product_rating_tr_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-rating .woocommerce-review-link' => 'transition-duration: {{VALUE}}s;'
				],
			]
		);

		$this->add_control(
			'product_rating_size',
			[
				'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
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
					'{{WRAPPER}} .tmpcoder-woo-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Icon Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-woo-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-woo-rating span' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'product_rating_spacing',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Label Distance', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-product-rating-flex .tmpcoder-product-rating a.woocommerce-review-link' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-product-rating-block .tmpcoder-product-rating a.woocommerce-review-link' => 'margin-top: {{SIZE}}{{UNIT}}; display: block;',
				],
				'separator' => 'after'
			]
		);

        $this->end_controls_section();

        // Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, Controls_Manager::TAB_STYLE );
    }
    
    public function render_product_rating( $settings ) {
		
		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

        $rating_count = $product->get_rating_count();
		$rating_amount = floatval( $product->get_average_rating() );
		$round_rating = (int)$rating_amount;
        $rating_icon = '&#9734;';

		echo '<div class="tmpcoder-woo-rating">';

			for ( $i = 1; $i <= 5; $i++ ) {
				if ( $i <= $rating_amount ) {
					echo '<i class="tmpcoder-rating-icon-full">'. esc_html($rating_icon) .'</i>';
				} elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) {
					echo '<i class="tmpcoder-rating-icon-'. esc_attr( ( $rating_amount - $round_rating ) * 10 ) .'">'. esc_html($rating_icon) .'</i>';
				} else {
					echo '<i class="tmpcoder-rating-icon-empty">'. esc_html($rating_icon) .'</i>';
				}
	     	}

		echo '</div>';

		?>

        <a href="#reviews" class="woocommerce-review-link" rel="nofollow">

            <?php 
            
            	// Translators: %s is the icon.
            	echo wp_kses_post( sprintf( _n( '%s Review', '%s Reviews', 10, 'sastra-essential-addons-for-elementor' ), '<span class="count">' . esc_html( $rating_count ) . '</span>' ) );
        	?>
        </a>

		<?php
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

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

        echo '<div class="tmpcoder-product-rating">';
            $this->render_product_rating($settings);
        echo '</div>';
    }
}