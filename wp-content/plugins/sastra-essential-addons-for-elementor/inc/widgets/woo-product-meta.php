<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Product_Meta extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-product-meta';
	}

	public function get_title() {
		return esc_html__( 'Product Meta', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-post-info';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_product') ? [ 'tmpcoder-woocommerce-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-meta', 'product', 'meta' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-product-meta' ];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_meta_styles',
			[
				'label' => esc_html__( 'Styles', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_meta_layout',
			[
				'label' => esc_html__( 'Select Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
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
                'prefix_class' => 'tmpcoder-product-meta-',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-product-meta .product_meta' => 'display: flex; flex-direction: {{VALUE}};'
                ],
				'default' => 'column',
				'label_block' => false,
			]
		);

		$this->add_responsive_control(
			'meta_align',
			[
				'label'     => esc_html__('Alignment', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::CHOOSE,
				'default' => 'left',
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
				'prefix_class' => 'tmpcoder-product-meta-',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-meta .product_meta' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'product_meta_gutter',
			[
				'label' => esc_html__( 'List Gutter', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-product-meta-column .product_meta span:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-product-meta-row .product_meta span:not(last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'meta_label_title',
			[
				'label'     => esc_html__('Title', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_title_color',
			[
				'label'     => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-meta .product_meta :is(.sku_wrapper, .posted_in, .tagged_as)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_meta_value',
			[
				'label'     => esc_html__('Value', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_value_color',
			[
				'label'     => esc_html__('Value Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-meta .product_meta :is(.sku, .posted_in a, .tagged_as a)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_value_link_hover_color',
			[
				'label'     => esc_html__('Link Hover Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5729d9',
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-meta .product_meta :is(.posted_in a, .tagged_as a):hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_meta_typography',
				'label' => esc_html__('Typography', 'sastra-essential-addons-for-elementor'),
				'selector' => '{{WRAPPER}} .tmpcoder-product-meta .product_meta :is(a, span, .sku_wrapper, .posted_in, .tagged_as)',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'       => [
						'label'      => esc_html__('Font Size (px)', 'sastra-essential-addons-for-elementor'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					],
					'font_weight'     => [
						'default' => '500',
					],
					'text_transform'  => [
						'default' => 'none',
					],
					'line_height'     => [
						'label'      => esc_html__('Line Height (px)', 'sastra-essential-addons-for-elementor'),
						'default' => [
							'size' => '17',
							'unit' => 'px',
						],
						'size_units' => ['px'],
						'tablet_default' => [
							'unit' => 'px',
						],
						'mobile_default' => [
							'unit' => 'px',
						],
					],
				],
			]
		);

		$this->add_control(
			'meta_sku_hide',
			[
				'label'        => esc_html__('SKU', 'sastra-essential-addons-for-elementor'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'sastra-essential-addons-for-elementor'),
				'label_off'    => esc_html__('Hide', 'sastra-essential-addons-for-elementor'),
				'default'      => "yes",
				'return_value' => "yes",
				'prefix_class' => 'tmpcoder-product-meta-sku-',
				'selectors'    => [
					'{{WRAPPER}}.tmpcoder-product-meta-column .tmpcoder-product-meta .sku_wrapper' => 'display: inline-block;',
					'{{WRAPPER}}.tmpcoder-product-meta-row .tmpcoder-product-meta .sku_wrapper'	=> 'display: inline-block;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_category_hide',
			[
				'label'        => esc_html__('Category', 'sastra-essential-addons-for-elementor'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'sastra-essential-addons-for-elementor'),
				'label_off'    => esc_html__('Hide', 'sastra-essential-addons-for-elementor'),
				'default'      => "yes",
				'return_value' => "yes",
				'prefix_class' => 'tmpcoder-product-meta-cat-',
				'selectors'    => [
					'{{WRAPPER}}.tmpcoder-product-meta-column .tmpcoder-product-meta .posted_in' => 'display: inline-block;',
					'{{WRAPPER}}.tmpcoder-product-meta-row .tmpcoder-product-meta .posted_in'	=> 'display: inline-block;',
				],
			]
		);

		$this->add_control(
			'meta_tag_hide',
			[
				'label'        => esc_html__('Tag', 'sastra-essential-addons-for-elementor'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'sastra-essential-addons-for-elementor'),
				'label_off'    => esc_html__('Hide', 'sastra-essential-addons-for-elementor'),
				'default'      => "yes",
				'return_value' => "yes",
				'prefix_class' => 'tmpcoder-product-meta-tag-',
				'selectors'    => [
					'{{WRAPPER}}.tmpcoder-product-meta-column .tmpcoder-product-meta .tagged_as'							=> 'display: inline-block;',
					'{{WRAPPER}}.tmpcoder-product-meta-row .tmpcoder-product-meta .tagged_as'	=> 'display: inline-block;',
				],
			]
		);

		$this->add_control(
			'tmpcoder_enable_title_attribute',
			[
				'label' => esc_html__( 'Enable Title Attribute', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'render_type' => 'template',
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

        $post = get_post( $product->get_id() );
        setup_postdata( $product->get_id() );

        ob_start();
	    woocommerce_template_single_meta();
	    $meta_html = ob_get_clean();

	    echo '<div class="tmpcoder-product-meta">';

		    if (isset($settings['tmpcoder_enable_title_attribute']) && $settings['tmpcoder_enable_title_attribute'] == 'yes') {

			    $meta_html = preg_replace_callback(
				    '/<a[^>]+>([^<]+)<\/a>/i',
				    function( $matches ) {
				        $name = esc_attr( trim( $matches[1] ) );
				        return str_replace('<a', '<a title="' . $name . '"', $matches[0]);
				    },
				    $meta_html
				);
		    }

	    echo wp_kses($meta_html, tmpcoder_wp_kses_allowed_html());

	    echo '</div>';

    }
}