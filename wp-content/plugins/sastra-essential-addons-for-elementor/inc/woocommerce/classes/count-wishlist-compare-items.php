<?php
namespace TMPCODER\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TMPCODER_Count_Wishlist_Compare_Items setup
 *
 * @since 1.0
 */
class TMPCODER_Count_Wishlist_Compare_Items { 

    /**
    ** Constructor
    */
    public function __construct() {
        add_action( 'wp_ajax_count_wishlist_items',[$this, 'count_wishlist_items'] );
        add_action( 'wp_ajax_nopriv_count_wishlist_items',[$this, 'count_wishlist_items'] );
        add_action( 'wp_ajax_count_compare_items',[$this, 'count_compare_items'] );
        add_action( 'wp_ajax_nopriv_count_compare_items',[$this, 'count_compare_items'] );
    }

	// Add two new functions for handling cookies
	public function get_wishlist_from_cookie() {
        $blog_id = get_current_blog_id();
        $cookie_key = 'tmpcoder_wishlist_'.$blog_id;
        if (isset($_COOKIE['tmpcoder_wishlist'])) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE['tmpcoder_wishlist'])), true);
        } else if ( isset($_COOKIE[$cookie_key]) ) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE[$cookie_key])), true);
        }
        return array();
	}
    
    function get_compare_from_cookie() {
        $blog_id = get_current_blog_id();
        $cookie_key = 'tmpcoder_compare_'.$blog_id;
        if (isset($_COOKIE['tmpcoder_compare'])) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE['tmpcoder_compare'])), true);
        } else if ( isset($_COOKIE[$cookie_key]) ) {
            return json_decode(sanitize_text_field(wp_unslash($_COOKIE[$cookie_key])), true);
        }
        return array();
    }
    
    function count_wishlist_items() {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons') ) {
            exit; // Get out of here, the nonce is rotten!
        }

        $user_id = get_current_user_id();
        
        if ($user_id > 0) {
            if (is_multisite()) {
                $wishlist_key = 'tmpcoder_wishlist_'.get_current_blog_id();
            } else {
                $wishlist_key = 'tmpcoder_wishlist';
            }
            $wishlist = get_user_meta($user_id, $wishlist_key, true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }

        $product_data = [];

       $product_data['wishlist_count'] = sizeof($wishlist);
       $wishlist_product_array = [];

        $settings = [
            'element_addcart_simple_txt' => isset($_POST['element_addcart_simple_txt']) ? sanitize_text_field(wp_unslash($_POST['element_addcart_simple_txt'])) : '',
            'element_addcart_grouped_txt' => isset($_POST['element_addcart_grouped_txt']) ? sanitize_text_field(wp_unslash($_POST['element_addcart_grouped_txt'])) : '',
            'element_addcart_variable_txt' => isset($_POST['element_addcart_variable_txt']) ? sanitize_text_field(wp_unslash($_POST['element_addcart_grouped_txt'])) : ''
        ];
            
        foreach ($wishlist as $product_id) {
            $product = wc_get_product($product_id);

            if ($product) {
                $wishlist_product_array[] = [
                    'product_id' => $product_id,
                    'product_image' => $product->get_image(),
                    'product_title' => $product->get_title(),
                    'product_url' => $product->get_permalink(),
                    'product_price' => $product->get_price_html(),
                    'product_stock' => $product->get_stock_status() == 'instock' ? esc_html__('In Stock', 'sastra-essential-addons-for-elementor') : esc_html__('Out of Stock', 'sastra-essential-addons-for-elementor'),
                    'product_atc' => $this->render_product_add_to_cart($settings, $product)
                ];
            }
        }

        $product_data['wishlist_items'] = $wishlist_product_array;

       wp_send_json($product_data);

       wp_die();
    }
    
    function count_compare_items() {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons') ) {
            exit; // Get out of here, the nonce is rotten!
        }

        $user_id = get_current_user_id();
        
        if ($user_id > 0) {
            if (is_multisite()) {
                $compare_key = 'tmpcoder_compare_'.get_current_blog_id();
            } else {
                $compare_key = 'tmpcoder_compare';
            }
            $compare = get_user_meta($user_id, $compare_key, true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }

        $product_data = [];

       $product_data['compare_count'] = sizeof($compare);
       $product_data['compare_table'] = $this->compare_table();

       wp_send_json($product_data);

       wp_die();
    }

    public function compare_table() {

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons') ) {
            exit; // Get out of here, the nonce is rotten!
        }

        $user_id = get_current_user_id();
		$notification_hidden = '';
		$table_hidden = '';

        $settings = [
            'compare_empty_text' => isset($_POST['compare_empty_text']) ? sanitize_text_field(wp_unslash($_POST['compare_empty_text'])) : '',
            'remove_from_compare_text' => isset($_POST['remove_text']) ? sanitize_text_field(wp_unslash($_POST['remove_text'])) : '',
            'element_addcart_simple_txt' => isset($_POST['element_addcart_simple_txt']) ? sanitize_text_field(wp_unslash($_POST['element_addcart_simple_txt'])) : '',
            'element_addcart_grouped_txt' => isset($_POST['element_addcart_grouped_txt']) ? sanitize_text_field(wp_unslash($_POST['element_addcart_grouped_txt'])) : '',
            'element_addcart_variable_txt' => isset($_POST['element_addcart_variable_txt']) ? sanitize_text_field(wp_unslash($_POST['element_addcart_grouped_txt'])) : ''
        ];

		if ($user_id > 0) {
            if (is_multisite()) {
                $compare_key = 'tmpcoder_compare_'.get_current_blog_id();
            } else {
                $compare_key = 'tmpcoder_compare';
            }
			$compare = get_user_meta( get_current_user_id(), $compare_key, true );
		
			if ( ! $compare ) {
				$compare = array();
			}
		} else {
			$compare = $this->get_compare_from_cookie();
		}
		
        if ( ! $compare ) {
			$table_hidden = 'tmpcoder-hidden-element';
        } else {
			$notification_hidden = 'tmpcoder-hidden-element';
		}

        ob_start();

		echo wp_kses_post('<p class="tmpcoder-compare-empty '. $notification_hidden .'">'. esc_html($settings['compare_empty_text']) .'</p>');
        
        // Start the table
		echo '<div class="tmpcoder-compare-products '. esc_attr($table_hidden) .'">';
        echo '<table class="tmpcoder-compare-table">';
        
            // Create the first row of table headers
            echo '<tr>';
            echo '<th></th>'; // Blank space for top-left corner
            foreach ( $compare as $product_id ) {
                $product = wc_get_product( $product_id );
                if ( ! $product ) {
                    continue;
                }
                echo wp_kses_post('<th data-product-id="' . esc_attr($product->get_id()) . '"><button class="tmpcoder-compare-remove" data-product-id="'.esc_attr($product->get_id()) . '">'. esc_html($settings['remove_from_compare_text']) .'</button></th>');
            }
            echo '</tr>';
            
            // Create the remaining rows of the table
            $table_data = array(
                ['label' => esc_html__('Image', 'sastra-essential-addons-for-elementor'), 'type' => 'image'],
                ['label' => esc_html__('Name', 'sastra-essential-addons-for-elementor'), 'type' => 'text'],
                ['label' => esc_html__('Rating', 'sastra-essential-addons-for-elementor'), 'type' => 'text'],
                ['label' => esc_html__('Description', 'sastra-essential-addons-for-elementor'), 'type' => 'text'],
                ['label' => esc_html__('Price', 'sastra-essential-addons-for-elementor'), 'type' => 'text'],
                ['label' => esc_html__('SKU', 'sastra-essential-addons-for-elementor'), 'type' => 'text'],
                ['label' => esc_html__('Stock Status', 'sastra-essential-addons-for-elementor'), 'type' => 'text'],
                ['label' => esc_html__('Dimensions', 'sastra-essential-addons-for-elementor'), 'type' => 'text'],
                ['label' => esc_html__('Weight', 'sastra-essential-addons-for-elementor'), 'type' => 'text']
            );

            
            $all_attributes = array();
            
            foreach ( $compare as $product_id ) {
                $product = wc_get_product( $product_id );
                if ( ! $product ) {
                    continue;
                }
                $attributes = $product->get_attributes();
                foreach ( $attributes as $attribute ) {
                    $attribute_name = wc_attribute_label($attribute->get_name());
                    if ( !in_array($attribute_name, $all_attributes) ) {
                        $all_attributes[] = $attribute_name;
                    }
                }
            }
            foreach ( $all_attributes as $attribute_name ) {
                $table_data[] = array('label' => $attribute_name, 'type' => 'text');
            }

            foreach ( $table_data as $row ) {
                echo '<tr>';
                echo '<th>' . esc_html($row['label']) . '</th>';
                foreach ( $compare as $key => $product_id ) {
                    $product = wc_get_product( $product_id );
                    if ( ! $product ) {
                        continue;
                    }
                    echo '<td data-product-id="' . esc_attr($product->get_id()) . '">';
                    switch ( $row['type'] ) {
                        case 'image':
                            echo wp_kses_post('<a class="tmpcoder-compare-img-wrap" href="' . esc_url($product->get_permalink()) . '">' . $product->get_image() . '</a>');
							echo wp_kses_post('<div class="tmpcoder-compare-product-atc">' . $this->render_product_add_to_cart( $settings, $product ) . '</div>');
                            break;
                        case 'text':
                            if( in_array(strtolower($row['label']), ['description', 'sku']) ) {
                                echo wp_kses_post($product->get_data()[strtolower($row['label'])]);
                            } else if ( strtolower($row['label']) == 'name' ) {
                                echo wp_kses_post('<a class="tmpcoder-compare-product-name" href="' . $product->get_permalink() . '">'. esc_html($product->get_data()[strtolower($row['label'])]) .'</a>');
							} else if ( strtolower($row['label']) == 'price' ) {
                                echo wp_kses_post($product->get_price_html());
                            } else if ( strtolower($row['label']) == 'rating' ) {
                                $this->render_product_rating($product);
                            } else if ( strtolower($row['label']) == 'stock status' ) {
					            $stock_status = $product->get_stock_status();
                                echo esc_html($stock_status == 'instock' ? __('In Stock', 'sastra-essential-addons-for-elementor') : __('Out of Stock', 'sastra-essential-addons-for-elementor'));
                            } else if ( strtolower($row['label']) == 'dimensions' ) {

								if ( $product->has_dimensions() ) {
									$dimensions = sprintf(
										'<span class="tmpcoder-dimensions">%s</span>',
										wc_format_dimensions( $product->get_dimensions( false ) )
									);
									echo wp_kses_post($dimensions);
								}
								
							} else if ( strtolower($row['label']) == 'weight' ) {

								if ( $product->get_weight() ) {
									$weight = sprintf(
										'<span class="tmpcoder-weight">%s %s</span>',
										$product->get_weight(),
										get_option( 'woocommerce_weight_unit' )
									);
	
									echo wp_kses_post($weight);
								}

							} else {      
                                $attributes = $product->get_attributes();
                                $attribute_name = wc_attribute_label(strtolower($row['label']));

								foreach ($product->get_attributes($product_id) as $attr) {

									if ( strtolower($attr['name']) === strtolower($row['label']) ) {
										echo esc_html($attr['value']);
									}
								}

								// Product Attributes
								if (isset($attributes['pa_'.$attribute_name])) {
									// Get the value(s) of the 'dimensions' attribute for the product
									$attributes_value = $attributes['pa_'.$attribute_name]->get_options();
									$attributes_value_array = [];

									// Loop through the values and output them
									foreach ($attributes_value as $value) {
										$term = get_term($value);
										$attributes_value_array[] = $term->name;
									}

									echo esc_html(implode(' | ', $attributes_value_array));
								}
                            }
                            break;
                    }
                    echo '</td>';
                }
                echo '</tr>';
            }
        
        // Close the table
        echo '</table>';
		echo '</div>';

        return ob_get_clean();
    }

    public function render_product_rating($product) {

        // $rating_count = $product->get_rating_count();
		// $rating_amount = floatval( $product->get_average_rating() );
		// $round_rating = (int)$rating_amount;
        // $rating_icon = '&#9734;';

		// echo '<div class="tmpcoder-woo-rating">';

		// 	for ( $i = 1; $i <= 5; $i++ ) {
		// 		if ( $i <= $rating_amount ) {
		// 			echo '<i class="tmpcoder-rating-icon-full">'. $rating_icon .'</i>';
		// 		} elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) {
		// 			echo '<i class="tmpcoder-rating-icon-'. ( $rating_amount - $round_rating ) * 10 .'">'. $rating_icon .'</i>';
		// 		} else {
		// 			echo '<i class="tmpcoder-rating-icon-empty">'. $rating_icon .'</i>';
		// 		}
	    //  	}

		// echo '</div>';

		// Another option
		$rating  = $product->get_average_rating();
		$count   = $product->get_rating_count();
		return wc_get_rating_html( $rating, $count );
	}
    	
	// Render Add To Cart
	public function render_product_add_to_cart( $settings, $product ) {

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		ob_start();

		// Get Button Class
		$button_class = implode( ' ', array_filter( [
			'product_type_'. $product->get_type(),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
		] ) );

		$attributes = [
			'rel="nofollow"',
			'class="'. esc_attr($button_class) .' tmpcoder-button-effect '. (!$product->is_in_stock() && 'simple' === $product->get_type() ? 'tmpcoder-atc-not-clickable' : '').'"',
			'aria-label="'. esc_attr($product->add_to_cart_description()) .'"',
			'data-product_id="'. esc_attr($product->get_id()) .'"',
			'data-product_sku="'. esc_attr($product->get_sku()) .'"',
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();

		// Button Text
		if ( 'simple' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_simple_txt'];

			if ( 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
				array_push( $attributes, 'href="'. esc_url( get_permalink( $page_id ) .'/?add-to-cart='. get_the_ID() ) .'"' );
			} else {
				array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
			}
		} elseif ( 'grouped' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_grouped_txt'];
			array_push( $attributes, 'href="'. esc_url( $product->get_permalink() ) .'"' );
		} elseif ( 'variable' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_variable_txt'];
			array_push( $attributes, 'href="'. esc_url( $product->get_permalink() ) .'"' );
		} else {
			array_push( $attributes, 'href="'. esc_url( $product->get_product_url() ) .'"' );
			$button_HTML .= get_post_meta( get_the_ID(), '_button_text', true ) ? get_post_meta( get_the_ID(), '_button_text', true ) : 'Buy Product';
		}

			// Button HTML
		echo wp_kses_post('<a '. implode( ' ', $attributes ) .'><span>'. $button_HTML .'</span></a>');

		return \ob_get_clean();
	} 
}

new TMPCODER_Count_Wishlist_Compare_Items();