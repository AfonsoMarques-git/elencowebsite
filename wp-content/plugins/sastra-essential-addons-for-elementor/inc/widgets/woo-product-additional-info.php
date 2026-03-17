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
use Elementor\Group_Control_Background;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Product_AdditionalInformation extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-product-additional-information';
	}

	public function get_title() {
		return esc_html__( 'Product Additional Information', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-product-info';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_product') ? [ 'tmpcoder-woocommerce-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'spexo', 'woocommerce', 'product-additional-information', 'product', 'additional information', 'information' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}
	
	public function get_script_depends() {
		return [ 'wc-single-product' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
		    'tmpcoder_extra_info_section',
		    [
		        'label' => __( 'Extra Info', 'sastra-essential-addons-for-elementor' ),
		        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		    ]
		);

		$repeater = new Repeater();

		$repeater->add_control(
		    'label',
		    [
		        'label' => __( 'Label', 'sastra-essential-addons-for-elementor' ),
		        'type' => \Elementor\Controls_Manager::TEXT,
		        'label_block' => true,
		        'dynamic' => [
					'active' => true,
				],
		    ]
		);

		$repeater->add_control(
		    'value',
		    [
		        'label' => __( 'Value', 'sastra-essential-addons-for-elementor' ),
		        'type' => \Elementor\Controls_Manager::WYSIWYG,
		        'label_block' => true,
		        'dynamic' => [
					'active' => true,
				],
		    ]
		);

		$this->add_control(
		    'tmpcoder_extra_info',
		    [
		        'label' => __( 'Extra Info Items', 'sastra-essential-addons-for-elementor' ),
		        'type' => \Elementor\Controls_Manager::REPEATER,
		        'fields' => $repeater->get_controls(),
		        'default' => [],
		        'title_field' => '{{ label }}',
		    ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'additional_info_syles',
			[
				'label' => esc_html__('Additional Information', 'sastra-essential-addons-for-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
		    'show_product_brand',
		    [
		        'label' => esc_html__( 'Show Product Brand', 'sastra-essential-addons-for-elementor' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => esc_html__( 'Show', 'sastra-essential-addons-for-elementor' ),
		        'label_off' => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor' ),
		        'return_value' => 'yes',
		        'default' => 'label_off',
		    ]
		);

		$this->add_control(
		    'disable_attribute_links',
		    [
		        'label' => esc_html__( 'Disable Attribute Links', 'sastra-essential-addons-for-elementor' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => esc_html__( 'Yes', 'sastra-essential-addons-for-elementor' ),
		        'label_off' => esc_html__( 'No', 'sastra-essential-addons-for-elementor' ),
		        'return_value' => 'yes',
		        'default' => '',
		        'separator' => 'before',
		    ]
		);

		$this->add_control(
		    'enable_extra_info',
		    [
		        'label' => __( 'Show Extra Info', 'sastra-essential-addons-for-elementor' ),
		        'type' => \Elementor\Controls_Manager::SWITCHER,
		        'label_on' => __( 'Show', 'sastra-essential-addons-for-elementor' ),
		        'label_off' => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
		        'return_value' => 'yes',
		        'default' => 'yes',
		    ]
		);

		$this->add_control(
			'additional_info_label',
			[
				'label'     => esc_html__('Attribute Name', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'additional_info_label_color',
			[
				'label'     => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#888888',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-additional-information table th' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_info_label_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-additional-information table th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'additional_info_th_typography',
				'label'          => esc_html__('Typography', 'sastra-essential-addons-for-elementor'),
				'selector'       => '{{WRAPPER}} .tmpcoder-product-additional-information tr :is(th)',
				'exclude'        => ['font_family', 'text_transform', 'text_decoration'],
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'sastra-essential-addons-for-elementor'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
					],
					'font_weight'    => [
						'default' => '400',
					],
					'text_transform' => [
						'default' => 'none',
					],
					'line_height'     => [
						'label'      => esc_html__('Line Height (px)', 'sastra-essential-addons-for-elementor'),
						'default' => [
							'size' => '19',
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
					'letter_spacing' => [
						'label'      => esc_html__('Letter Spacing (px)', 'sastra-essential-addons-for-elementor'),
						'size_units' => ['px'],
					],
				],
			]
		);

		$this->add_control(
			'additional_info_th_align',
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
					'{{WRAPPER}} .tmpcoder-product-additional-information table th' => 'text-align: {{VALUE}}',
				],
				'default' => 'left',
			]
		);

		$this->add_responsive_control(
			'additional_info_label_width',
			[
				'label'      => esc_html__('Width', 'sastra-essential-addons-for-elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 25,
				],
				'selectors'  => [
					'{{WRAPPER}} .tmpcoder-product-additional-information table th' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'additional_info_value_heading',
			[
				'label'     => esc_html__('Attribute Value', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::HEADING,
                'separator'  => 'before',
			]
		);

		$this->add_control(
			'additional_information_value_color',
			[
				'label'     => esc_html__('Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#101010',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-additional-information table td p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_information_value_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fdfdfd',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-additional-information table td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'additional_info_td_typography',
				'label'          => esc_html__('Typography', 'sastra-essential-addons-for-elementor'),
				'selector'       => '{{WRAPPER}} .tmpcoder-product-additional-information tr :is(td, p)',
				'exclude'        => ['font_family', 'text_transform', 'text_decoration'],
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'sastra-essential-addons-for-elementor'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
					],
					'font_weight'    => [
						'default' => '400',
					],
					'text_transform' => [
						'default' => 'none',
					],
					'line_height'     => [
						'label'      => esc_html__('Line Height (px)', 'sastra-essential-addons-for-elementor'),
						'default' => [
							'size' => '19',
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
					'letter_spacing' => [
						'label'      => esc_html__('Letter Spacing (px)', 'sastra-essential-addons-for-elementor'),
						'size_units' => ['px'],
					],
				],
			]
		);

		$this->add_responsive_control(
			'additional_info_padding',
			[
				'label'      => esc_html__('Padding', 'sastra-essential-addons-for-elementor'),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'      => '15',
					'right'    => '35',
					'bottom'   => '15',
					'left'     => '35',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'separator' => 'before',
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .tmpcoder-product-additional-information table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-product-additional-information table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'additional_info_divider_color',
			[
				'label'     => esc_html__('Divider (Border) Color', 'sastra-essential-addons-for-elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f2f2f2',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-additional-information table td' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-product-additional-information table th' => 'border-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'additional_info_border_width',
			[
				'label' => esc_html__( 'Divider (Border) Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-product-additional-information table tr:not(:last-child) td' => 'border-bottom-width: {{SIZE}}px; border-bottom-style: solid;',
					'{{WRAPPER}} .tmpcoder-product-additional-information table tr:not(:last-child) th' => 'border-bottom-width: {{SIZE}}px; border-bottom-style: solid;',
					'{{WRAPPER}}.tmpcoder-add-info-borders-yes .tmpcoder-product-additional-information table td' => 'border-width: {{SIZE}}px; border-style: solid;',
					'{{WRAPPER}}.tmpcoder-add-info-borders-yes .tmpcoder-product-additional-information table th' => 'border-width: {{SIZE}}px; border-style: solid;',
				],
			]
		);

		$this->add_control(
			'additional_info_show_borders',
			[
				'label' => esc_html__( 'Show Table Borders', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'tmpcoder-add-info-borders-'
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

        $heading = apply_filters( 'woocommerce_product_additional_information_heading', esc_html__( 'Additional information', 'sastra-essential-addons-for-elementor' ) );

        echo '<div class="tmpcoder-product-additional-information">';

    		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

        	if ( 'yes' === $settings['show_product_brand'] ) {
			    $brand_names = wp_get_post_terms( $product->get_id(), 'product_brand', [ 'fields' => 'names' ] );

			    if ( ! empty( $brand_names ) && ! is_wp_error( $brand_names ) ) {
			
					?>

					<table class="woocommerce-product-attributes shop_attributes tmpcoder-product-brand" aria-label="Product Details">
						<tbody>
							<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--dimensions">
								<th class="woocommerce-product-attributes-item__label" scope="row"><?php echo esc_html__( 'Brand ', 'sastra-essential-addons-for-elementor' ) ?></th>
								<td class="woocommerce-product-attributes-item__value"><?php echo esc_html( implode( ', ', $brand_names ) );?></td>
							</tr>
						</tbody>
					</table>

					<?php 
				}
			}

			if ( 'yes' === $settings['disable_attribute_links'] ) {
			    global $tmpcoder_disable_attribute_links;
			    $tmpcoder_disable_attribute_links = true;
			} else {
			    global $tmpcoder_disable_attribute_links;
			    $tmpcoder_disable_attribute_links = false;
			}

		    do_action('woocommerce_product_additional_information', $product);

		    $extra_info = isset($settings['tmpcoder_extra_info']) ? $settings['tmpcoder_extra_info'] : '';

			if ( ! empty( $extra_info ) ) {
			    echo '<table class="woocommerce-product-attributes shop_attributes tmpcoder-product-brand" aria-label="Product Details">';
			    echo '<tbody>';

			    foreach ( $extra_info as $item ) {
			        if ( empty( $item['label'] ) || empty( $item['value'] ) ) {
			            continue;
			        }

			        echo '<tr class="woocommerce-product-attributes-item">';
			        echo '<th class="woocommerce-product-attributes-item__label" scope="row">' . esc_html( $item['label'] ) . '</th>';
			        echo '<td class="woocommerce-product-attributes-item__value">' . wp_kses_post( $item['value'] ) . '</td>';
			        echo '</tr>';
			    }

			    echo '</tbody>';
			    echo '</table>';
			}

        echo '</div>';   
    }
}

add_filter( 'woocommerce_attribute', function( $text, $attribute, $values ) {
    global $tmpcoder_disable_attribute_links;

    if ( empty( $tmpcoder_disable_attribute_links ) ) {
        return $text;
    }

    // Strip anchor tags to disable links
    return wp_strip_all_tags( $text );

}, 10, 3 ); // <-- Only 3 args are available
