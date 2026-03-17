<?php


/**
 * Template Name: Default
 */

use \Spexo_Addons_Elementor\Classes\Helper;
use TMPCODER\Widgets\Product_Grid;
use \Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$product = wc_get_product( get_the_ID() );
if ( ! $product ) {
	error_log( '$product not found in ' . __FILE__ );
	return;
}	

global $tmpcoder_settings;
$tmpcoder_settings = $settings;

if ( ! function_exists( 'tmpcoder_render_wishlist_button' ) ) {
	function tmpcoder_render_wishlist_button() {
		global $tmpcoder_settings;
		if ( ! empty( $tmpcoder_settings ) ) {
			Helper::render_product_wishlist_button( $tmpcoder_settings, 'tmpcoder-wishlist-btn' );
		}
	}
}

if ( ! function_exists( 'tmpcoder_render_product_thumbnail' ) ) {
	function tmpcoder_render_product_thumbnail() {
		global $product, $tmpcoder_settings;
		
		// Remove default WooCommerce thumbnail
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

		if ( ! empty( $tmpcoder_settings ) ) {
			Helper::render_product_thumbnail( $tmpcoder_settings );
		}
	}
}

if ( has_post_thumbnail() ) {
	$settings[ 'tmpcoder_image_size_customize' ] = [
		'id' => get_post_thumbnail_id(),
	];
	$settings['tmpcoder_image_size_customize_size'] = $settings['tmpcoder_product_grid_image_size_size'];
	$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings,'tmpcoder_image_size_customize' );
}

$title_tag = isset( $settings['tmpcoder_product_grid_title_html_tag'] ) ? Helper::tmpcoder_validate_html_tag($settings['tmpcoder_product_grid_title_html_tag'])  : 'h2';
$should_print_compare_btn = isset( $settings['show_compare'] ) && 'yes' === $settings['show_compare'];

if ( tmpcoder_is_availble() ) {
    $should_print_wishlist_btn = isset( $settings['tmpcoder_product_grid_wishlist'] ) && 'yes' === $settings['tmpcoder_product_grid_wishlist'];
}
// Improvement
$grid_style_preset = isset($settings['tmpcoder_product_grid_style_preset']) ? $settings['tmpcoder_product_grid_style_preset'] : '';
$list_style_preset = isset($settings['tmpcoder_product_list_style_preset']) ? $settings['tmpcoder_product_list_style_preset'] : '';
$sale_badge_align  = isset( $settings['tmpcoder_product_sale_badge_alignment'] ) ? esc_attr( $settings['tmpcoder_product_sale_badge_alignment'] ) : '';
$sale_badge_preset = isset( $settings['tmpcoder_product_sale_badge_preset'] ) ? esc_attr( $settings['tmpcoder_product_sale_badge_preset'] ) : '';
// should print vars
$should_print_rating = isset( $settings['tmpcoder_product_grid_rating'] ) && 'yes' === $settings['tmpcoder_product_grid_rating'];
$should_print_quick_view = isset( $settings['tmpcoder_product_grid_quick_view'] ) && 'yes' === $settings['tmpcoder_product_grid_quick_view'];
$should_print_image_clickable = isset( $settings['tmpcoder_product_grid_image_clickable'] ) && 'yes' === $settings['tmpcoder_product_grid_image_clickable'];
$should_print_price = isset( $settings['tmpcoder_product_grid_price'] ) && 'yes' === $settings['tmpcoder_product_grid_price'];
$should_print_excerpt = isset( $settings['tmpcoder_product_grid_excerpt'] ) && ('yes' === $settings['tmpcoder_product_grid_excerpt'] && has_excerpt());
$widget_id = isset($settings['tmpcoder_widget_id']) ? $settings['tmpcoder_widget_id'] : '';

$sale_badge_text = !empty($settings['tmpcoder_product_sale_text']) ? $settings['tmpcoder_product_sale_text'] :  __( 'Sale!', 'sastra-essential-addons-for-elementor' );
$stock_out_badge_text = !empty($settings['tmpcoder_product_stockout_text']) ?$settings['tmpcoder_product_stockout_text'] : __( 'Stock <br/> Out', 'sastra-essential-addons-for-elementor' );
$is_show_badge = $settings['tmpcoder_show_product_sale_badge'];

$quick_view_setting = [
	'widget_id' => $widget_id,
	'product_id' => $product->get_id(),
	'page_id' => $settings['tmpcoder_page_id'],
];
$product_wrapper_classes = implode( " ", apply_filters( 'tmpcoder_product_wrapper_class', [], $product->get_id(), 'eicon-woocommerce' ) );

$product_data = [
	'id'     => get_the_ID(),
	'title'  => '<div class="tmpcoder-product-title">
                                <a href="' . esc_url( $product->get_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">' .
	            sprintf( '<%1$s class="woocommerce-loop-product__title">%2$s</%1$s>', $title_tag, $product->get_title() )
	            . '</a></div>',
	'ratings' => $should_print_rating ? wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) : '',
	'price'   => $should_print_price ? '<div class="tmpcoder-product-price">' . $product->get_price_html() . '</div>' : ''
];

if ( $should_print_rating ) {
	$avg_rating = $product->get_average_rating();
	if( $avg_rating > 0 ){
		$product_data['ratings'] = wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
	} else {
		$product_data['ratings'] = Helper::tmpcoder_rating_markup( $product->get_average_rating(), $product->get_rating_count() );
	}
}

$should_print_rating = isset( $settings['tmpcoder_product_grid_rating'] ) && 'yes' === $settings['tmpcoder_product_grid_rating'];

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );	

if ( $should_print_rating ) {

	if ( ! function_exists( 'tmpcoder_render_custom_rating' ) ) {
		function tmpcoder_render_custom_rating() {
			global $product;

			if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
				return;
			}

			$avg_rating = $product->get_average_rating();

			if ( $avg_rating > 0 ) {
				echo wc_get_rating_html( $avg_rating, $product->get_rating_count() );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo Helper::tmpcoder_rating_markup( $avg_rating, $product->get_rating_count() );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	add_action( 'woocommerce_after_shop_loop_item_title', 'tmpcoder_render_custom_rating', 5 );
}

add_action( 'woocommerce_before_shop_loop_item_title', 'tmpcoder_render_product_thumbnail', 9 );

add_filter('woocommerce_sale_flash', function($text, $post, $product) use( $sale_badge_text ) {
	return '<span class="onsale" data-notification="default">'. $sale_badge_text .'</span>';
}, 10, 3);

if ( $should_print_compare_btn ) {
	add_action( 'woocommerce_after_shop_loop_item', [
		'\TMPCODER\Widgets\Product_Grid',
		'print_compare_button',
	] );
}

if ( $should_print_wishlist_btn ) {
    add_action( '[woocommerce_after_shop_loop_item]', 'tmpcoder_render_wishlist_button' );
}

$thumb_size = isset($settings['tmpcoder_product_grid_image_size_size']) ? $settings['tmpcoder_product_grid_image_size_size'] : '';
global $tmpcoder_thumb_default;
add_filter( 'single_product_archive_thumbnail_size', function( $size ) use ( $thumb_size ) {
	global $tmpcoder_thumb_default;
	$tmpcoder_thumb_default = $size;
	return  ! empty( $thumb_size ) ? $thumb_size : $size ;
} );

wc_get_template_part( 'content', 'product' );

add_filter( 'single_product_archive_thumbnail_size', function( $size ) {
	global $tmpcoder_thumb_default;
	return ! empty( $tmpcoder_thumb_default ) ? $tmpcoder_thumb_default : $size;
} );

if ( $should_print_compare_btn ) {
	remove_action( 'woocommerce_after_shop_loop_item', [
		'\TMPCODER\Widgets\Product_Grid',
		'print_compare_button',
	] );
}

if ( $should_print_wishlist_btn ) {
    remove_action( 'woocommerce_after_shop_loop_item', 'tmpcoder_render_wishlist_button' );
}
