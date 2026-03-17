<?php
/**
 * Class trait Ajax_Handler file
 *
 * @package Spexo-addons-for-elementor\Traits
 */

namespace Spexo_Addons_Elementor\Traits;

use Spexo_Addons_Elementor\Classes\AllTraits;
use Spexo_Addons_Elementor\Classes\Elements_Manager;
use Spexo_Addons_Elementor\Classes\Helper as HelperClass;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Essential Addons ajax request handler
 *
 * Manage all ajax request for EA
 *
 * @class       Ajax_Handler
 * @since       5.0.9
 * @package     Spexo-addons-for-elementor\Traits
 */
trait Ajax_Handler {
	//use Template_Query;
	/**
	 * init_ajax_hooks
	 */
	public function init_ajax_hooks() {

		add_action( 'wp_ajax_load_more', array( $this, 'ajax_load_more' ) );
		add_action( 'wp_ajax_nopriv_load_more', array( $this, 'ajax_load_more' ) );

		add_action( 'wp_ajax_woo_product_pagination_product', array( $this, 'tmpcoder_woo_pagination_product_ajax' ) );
		add_action( 'wp_ajax_nopriv_woo_product_pagination_product', array( $this, 'tmpcoder_woo_pagination_product_ajax' ) );

		add_action( 'wp_ajax_woo_product_pagination', array( $this, 'tmpcoder_woo_pagination_ajax' ) );
		add_action( 'wp_ajax_nopriv_woo_product_pagination', array( $this, 'tmpcoder_woo_pagination_ajax' ) );

		add_action( 'wp_ajax_tmpcoder_product_add_to_cart', array( $this, 'tmpcoder_product_add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_tmpcoder_product_add_to_cart', array( $this, 'tmpcoder_product_add_to_cart' ) );

		add_action( 'wp_ajax_woo_checkout_update_order_review', [ $this, 'woo_checkout_update_order_review' ] );
		add_action( 'wp_ajax_nopriv_woo_checkout_update_order_review', [ $this, 'woo_checkout_update_order_review' ] );

		add_action( 'wp_ajax_nopriv_tmpcoder_product_quickview_popup', [ $this, 'tmpcoder_product_quickview_popup' ] );
		add_action( 'wp_ajax_tmpcoder_product_quickview_popup', [ $this, 'tmpcoder_product_quickview_popup' ] );

		add_action( 'wp_ajax_nopriv_tmpcoder_product_gallery', [ $this, 'ajax_tmpcoder_product_gallery' ] );
		add_action( 'wp_ajax_tmpcoder_product_gallery', [ $this, 'ajax_tmpcoder_product_gallery' ] );

		add_action( 'wp_ajax_tmpcoder_get_token', [ $this, 'tmpcoder_get_token' ] );
		add_action( 'wp_ajax_nopriv_tmpcoder_get_token', [ $this, 'tmpcoder_get_token' ] );

		add_action( 'tmpcoder_before_woo_pagination_product_ajax_start', [ $this, 'tmpcoder_yith_wcwl_ajax_disable' ] );
		add_action( 'tmpcoder_before_ajax_load_more', [ $this, 'tmpcoder_yith_wcwl_ajax_disable' ] );
	}

	/**
	 * Ajax Load More
	 * This function is responsible for get the post data.
	 * It will return HTML markup with AJAX call and with normal call.
	 *
	 * @access public
	 * @return false|void of a html markup with AJAX call.
	 * @return array of content and found posts count without AJAX call.
	 * @since 3.1.0
	 */
	public function ajax_load_more() {
		$ajax = wp_doing_ajax();

		do_action( 'tmpcoder_before_ajax_load_more', $_REQUEST );

		wp_parse_str( $_POST['args'], $args );
		$args['post_status'] = 'publish';

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::tmpcoder_sanitize_relation( $args['date_query']['relation'] );
		}

		if ( empty( $_POST['nonce'] ) ) {
			$err_msg = __( 'Insecure form submitted without security token', 'sastra-essential-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'load_more' ) && ! wp_verify_nonce( $_POST['nonce'], 'spexo-elementor-addons' ) ) {
			$err_msg = __( 'Security token did not match', 'sastra-essential-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'sastra-essential-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing', 'sastra-essential-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		$settings = HelperClass::tmpcoder_get_widget_settings( $page_id, $widget_id );

		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'sastra-essential-addons-for-elementor' ) ] );
		}

		$settings['tmpcoder_widget_id'] = $widget_id;
		$settings['tmpcoder_page_id']   = $page_id;
		$html                       = '';
		$class                      = '\\' . str_replace( '\\\\', '\\', $_REQUEST['class'] );
		$args['offset']             = (int) $args['offset'] + ( ( (int) $_REQUEST['page'] - 1 ) * (int) $args['posts_per_page'] );

		if ( isset( $_REQUEST['taxonomy'] ) && isset( $_REQUEST['taxonomy']['taxonomy'] ) && $_REQUEST['taxonomy']['taxonomy'] != 'all' ) {
			$args['tax_query'] = [
				$this->sanitize_taxonomy_data( $_REQUEST['taxonomy'] ),
			];

			$relation = isset( $settings['relation_cats_tags'] ) ? $settings['relation_cats_tags'] : 'OR';
			$args['tax_query'] = $this->tmpcoder_terms_query_multiple( $args['tax_query'], $relation );
		}

		
		if ( $class === '\TMPCODER\Widgets\Product_Grid' ) {
			do_action( 'tmpcoder_woo_before_product_loop', $settings['tmpcoder_product_grid_style_preset'] );
		}

		$link_settings = [
			'image_link_nofollow'         => ! empty( $settings['image_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'image_link_target_blank'     => ! empty( $settings['image_link_target_blank'] ) ? 'target="_blank"' : '',
			'title_link_nofollow'         => ! empty( $settings['title_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'title_link_target_blank'     => ! empty( $settings['title_link_target_blank'] ) ? 'target="_blank"' : '',
			'read_more_link_nofollow'     => ! empty( $settings['read_more_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'read_more_link_target_blank' => ! empty( $settings['read_more_link_target_blank'] ) ? 'target="_blank"' : '',
		];

		$template_info = $this->tmpcoder_sanitize_template_param( $_REQUEST['template_info'] );

		if ( $template_info ) {

			if ( $template_info['dir'] === 'theme' ) {
				$dir_path = $this->retrive_theme_path();
			} else if ( $template_info['dir'] === 'pro' ) {
				$dir_path = sprintf( "%sinc", TMPCODER_PLUGIN_DIR );
			} else {
				$dir_path = sprintf( "%sinc", TMPCODER_PLUGIN_DIR );
			}

			$file_path = realpath( sprintf(
				'%s/woocommerce/%s/%s',
				$dir_path,
				$template_info['name'],
				$template_info['file_name']
			) );

			// echo $file_path;

			if ( ! $file_path || 0 !== strpos( $file_path, realpath( $dir_path ) ) ) {
				wp_send_json_error( 'Invalid template', 'invalid_template', 400 );
			}

			if ( $file_path ) {
				$query = new \WP_Query( $args );
				$found_posts = $query->found_posts;
				$iterator = 0;

				if ( $query->have_posts() ) {
					if ( $class === '\TMPCODER\Widgets\Product_Grid' && boolval( $settings['show_add_to_cart_custom_text'] ) ) {

						$add_to_cart_text = [
							'add_to_cart_simple_product_button_text'   => $settings['add_to_cart_simple_product_button_text'],
							'add_to_cart_variable_product_button_text' => $settings['add_to_cart_variable_product_button_text'],
							'add_to_cart_grouped_product_button_text'  => $settings['add_to_cart_grouped_product_button_text'],
							'add_to_cart_external_product_button_text' => $settings['add_to_cart_external_product_button_text'],
							'add_to_cart_default_product_button_text'  => $settings['add_to_cart_default_product_button_text'],
						];
						$this->change_add_woo_checkout_update_order_reviewto_cart_text( $add_to_cart_text );
					}

					if ( $class === '\Spexo_Addons_Elementor\Pro\Elements\Dynamic_Filterable_Gallery' ) {
						$html .= "<div class='found_posts' style='display: none;'>{$found_posts}</div>";
					}

					while ( $query->have_posts() ) {
						$query->the_post();

						$html .= HelperClass::include_with_variable( $file_path, [
							'settings'      => $settings,
							'link_settings' => $link_settings,
							'iterator'      => $iterator
						] );
						$iterator ++;
					}
				} else {
					$html .= __( '<p class="no-posts-found">No posts found!</p>', 'sastra-essential-addons-for-elementor' );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}

		if ( $class === '\TMPCODER\Widgets\Product_Grid' ) {
			do_action( 'tmpcoder_woo_after_product_loop', $settings['tmpcoder_product_grid_style_preset'] );
		}
		while ( ob_get_status() ) {
			ob_end_clean();
		}
		if ( function_exists( 'gzencode' ) ) {
			$response = gzencode( wp_json_encode( $html ) );

			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Encoding: gzip' );
			header( 'Content-Length: ' . strlen( $response ) );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $response;
		} else {
			echo wp_kses_post( $html );
		}
		wp_die();
	}

	/**
	 * Woo Pagination Product Ajax
	 * get product list when pagination number/dot click by ajax
	 *
	 * @access public
	 * @return void of a html markup with AJAX call.
	 * @since 3.1.0
	 */
	public function tmpcoder_woo_pagination_product_ajax() {

		check_ajax_referer( 'spexo-elementor-addons', 'security' );

		do_action( 'tmpcoder_before_woo_pagination_product_ajax_start', $_REQUEST );

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'sastra-essential-addons-for-elementor' );
			wp_send_json_error( $err_msg );
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing', 'sastra-essential-addons-for-elementor' );
			wp_send_json_error( $err_msg );
		}

		$settings = HelperClass::tmpcoder_get_widget_settings( $page_id, $widget_id );
		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'sastra-essential-addons-for-elementor' ) ] );
		}
		$settings['tmpcoder_page_id']   = $page_id;
		$settings['tmpcoder_widget_id'] = $widget_id;
		wp_parse_str( $_REQUEST['args'], $args );
		$args['post_status'] = array_intersect( (array) $settings['tmpcoder_product_grid_products_status'], [ 'publish', 'draft', 'pending', 'future' ] );

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::tmpcoder_sanitize_relation( $args['date_query']['relation'] );
		}

		$paginationNumber = absint( $_POST['number'] );
		$paginationLimit  = absint( $_POST['limit'] );

		$args['posts_per_page'] = $paginationLimit;

		if ( $paginationNumber == "1" ) {
			$paginationOffsetValue = "0";
		} else {
			$paginationOffsetValue = ( $paginationNumber - 1 ) * $paginationLimit;
			$args['offset']        = $paginationOffsetValue;
		}


		$template_info = $this->tmpcoder_sanitize_template_param( $_REQUEST['templateInfo'] );

		$this->set_widget_name( $template_info['name'] );
		$template = realpath( $this->get_template( $template_info['file_name'] ) );

		ob_start();
		$query = new \WP_Query( $args );
		if ( $query->have_posts() ) {
			if ( isset( $template_info['name'] ) && $template_info['name'] === 'eicon-woocommerce' && boolval( $settings['show_add_to_cart_custom_text'] ) ){
				$add_to_cart_text = [
					'add_to_cart_simple_product_button_text'   => $settings['add_to_cart_simple_product_button_text'],
					'add_to_cart_variable_product_button_text' => $settings['add_to_cart_variable_product_button_text'],
					'add_to_cart_grouped_product_button_text'  => $settings['add_to_cart_grouped_product_button_text'],
					'add_to_cart_external_product_button_text' => $settings['add_to_cart_external_product_button_text'],
					'add_to_cart_default_product_button_text'  => $settings['add_to_cart_default_product_button_text'],
				];
				$this->change_add_woo_checkout_update_order_reviewto_cart_text( $add_to_cart_text );
			}

			while ( $query->have_posts() ) {
				$query->the_post();
				include( $template );
			}
			wp_reset_postdata();
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo ob_get_clean();
		wp_die();
	}

	/**
	 * Woo Pagination Ajax
	 * Return pagination list for product post type while used Product_Grid widget
	 *
	 * @access public
	 * @return void of a html markup with AJAX call.
	 * @since unknown
	 */
	public function tmpcoder_woo_pagination_ajax() {

		check_ajax_referer( 'spexo-elementor-addons', 'security' );

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'sastra-essential-addons-for-elementor' );
			wp_send_json_error( $err_msg );
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing', 'sastra-essential-addons-for-elementor' );
			wp_send_json_error( $err_msg );
		}

		$settings = HelperClass::tmpcoder_get_widget_settings( $page_id, $widget_id );

		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'sastra-essential-addons-for-elementor' ) ] );
		}

		$settings['tmpcoder_page_id'] = $page_id;
		wp_parse_str( $_REQUEST['args'], $args );

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::tmpcoder_sanitize_relation( $args['date_query']['relation'] );
		}

		$paginationNumber          = absint( $_POST['number'] );
		$paginationLimit           = absint( $_POST['limit'] );
		$pagination_Count          = intval( $args['total_post'] );
		$pagination_Paginationlist = ceil( $pagination_Count / $paginationLimit );
		$last                      = ceil( $pagination_Paginationlist );
		$paginationprev            = $paginationNumber - 1;
		$paginationnext            = $paginationNumber + 1;

		if ( $paginationNumber > 1 ) {
			$paginationprev;
		}
		if ( $paginationNumber < $last ) {
			$paginationnext;
		}

		$adjacents                    = "2";
		$next_label                   = sanitize_text_field( $settings['pagination_next_label'] );
		$prev_label                   = sanitize_text_field( $settings['pagination_prev_label'] );
		$settings['tmpcoder_widget_name'] = realpath( sanitize_file_name( $_REQUEST['template_name'] ) );
		$setPagination                = "";

		if ( $pagination_Paginationlist > 0 ) {

			$setPagination .= "<ul class='page-numbers'>";

			if ( 1 < $paginationNumber ) {
				$setPagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers'   data-pnumber='" . esc_attr( $paginationprev ) . "' >" . esc_html( $prev_label ) . "</a></li>";
			}

			if ( $pagination_Paginationlist < 7 + ( $adjacents * 2 ) ) {

				for ( $pagination = 1; $pagination <= $pagination_Paginationlist; $pagination ++ ) {
					$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
				}

			} else if ( $pagination_Paginationlist > 5 + ( $adjacents * 2 ) ) {

				if ( $paginationNumber < 1 + ( $adjacents * 2 ) ) {
					for ( $pagination = 1; $pagination <= 4 + ( $adjacents * 2 ); $pagination ++ ) {

						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
					}
					$setPagination .= "<li class='pagitext dots'>...</li>";
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );

				} elseif ( $pagination_Paginationlist - ( $adjacents * 2 ) > $paginationNumber && $paginationNumber > ( $adjacents * 2 ) ) {
					$active        = '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), 1 );
					$setPagination .= "<li class='pagitext dots'>...</li>";
					for ( $pagination = $paginationNumber - $adjacents; $pagination <= $paginationNumber + $adjacents; $pagination ++ ) {
						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
					}

					$setPagination .= "<li class='pagitext dots'>...</li>";
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $last ) );

				} else {
					$active        = '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), 1 );
					$setPagination .= "<li class='pagitext dots'>...</li>";
					for ( $pagination = $last - ( 2 + ( $adjacents * 2 ) ); $pagination <= $last; $pagination ++ ) {
						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
					}
				}

			} else {
				for ( $pagination = 1; $pagination <= $pagination_Paginationlist; $pagination ++ ) {
					$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
				}

			}

			if ( $paginationNumber < $pagination_Paginationlist ) {
				$setPagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-pnumber='" . esc_attr( $paginationnext ) . "' > " . esc_html( $next_label ) . " </a></li>";
			}

			$setPagination .= "</ul>";
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $setPagination;
		wp_die();
	}

	/**
	 * Product Add to Cart
	 * added product in cart through ajax
	 *
	 * @access public
	 * @return void of a html markup with AJAX call.
	 * @since unknown
	 */
	public function tmpcoder_product_add_to_cart() {

		$ajax       = wp_doing_ajax();
		$cart_items = isset( $_POST['cart_item_data'] ) ? $_POST['cart_item_data'] : [];
		$variation  = [];
		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $key => $value ) {
				if ( preg_match( "/^attribute*/", $value['name'] ) ) {
					$variation[ $value['name'] ] = sanitize_text_field( $value['value'] );
				}
			}
		}

		if ( isset( $_POST['product_data'] ) ) {
			foreach ( $_POST['product_data'] as $item ) {
				$product_id   = isset( $item['product_id'] ) ? sanitize_text_field( $item['product_id'] ) : 0;
				$variation_id = isset( $item['variation_id'] ) ? sanitize_text_field( $item['variation_id'] ) : 0;
				$quantity     = isset( $item['quantity'] ) ? sanitize_text_field( $item['quantity'] ) : 0;

				if ( $variation_id ) {
					WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
				} else {
					WC()->cart->add_to_cart( $product_id, $quantity );
				}
			}
		}
		wp_send_json_success();
	}

	/**
	 * Woo Checkout Update Order Review
	 * return order review data
	 *
	 * @access public
	 * @return void
	 * @since 4.0.0
	 */
	public function woo_checkout_update_order_review() {
		$setting = $_POST['orderReviewData'];
		ob_start();
		AllTraits::checkout_order_review_default( $setting );
		$woo_checkout_update_order_review = ob_get_clean();

		wp_send_json(
			array(
				'order_review' => $woo_checkout_update_order_review,
			)
		);
	}

	/**
	 * Tmpcoder Product Quick View Popup
	 * Retrieve product quick view data
	 *
	 * @access public
	 * @return void
	 * @since 4.0.0
	 */
	public function tmpcoder_product_quickview_popup() {
		//check nonce
		check_ajax_referer( 'spexo-elementor-addons', 'security' );
		$widget_id  = sanitize_key( $_POST['widget_id'] );
		$product_id = absint( $_POST['product_id'] );
		$page_id    = absint( $_POST['page_id'] );

		if ( $widget_id == '' && $product_id == '' && $page_id == '' ) {
			wp_send_json_error();
		}

		global $post, $product;
		$product = wc_get_product( $product_id );
		$post    = get_post( $product_id );
		setup_postdata( $post );

		$settings = $this->tmpcoder_get_widget_settings( $page_id, $widget_id );
		ob_start();
		HelperClass::tmpcoder_product_quick_view( $product, $settings, $widget_id );
		$data = ob_get_clean();
		wp_reset_postdata();

		wp_send_json_success( $data );
	}

	/**
	 * Ajax Tmpcoder Product Gallery
	 * Retrieve product quick view data
	 *
	 * @access public
	 * @return false|void
	 * @since 4.0.0
	 */
	public function ajax_tmpcoder_product_gallery() {

		$ajax = wp_doing_ajax();

		wp_parse_str( $_POST['args'], $args );
		$args['post_status'] = 'publish';
		$args['offset']      = $args['offset'] ?? 0;

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::tmpcoder_sanitize_relation( $args['date_query']['relation'] );
		}

		if ( empty( $_POST['nonce'] ) ) {
			$err_msg = __( 'Insecure form submitted without security token', 'sastra-essential-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'tmpcoder_product_gallery' ) ) {
			$err_msg = __( 'Security token did not match', 'sastra-essential-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'sastra-essential-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing', 'sastra-essential-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		$settings = HelperClass::tmpcoder_get_widget_settings( $page_id, $widget_id );
		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'sastra-essential-addons-for-elementor' ) ] );
		}

		if ( $widget_id == '' && $page_id == '' ) {
			wp_send_json_error();
		}

		$settings['tmpcoder_widget_id'] = $widget_id;
		$settings['tmpcoder_page_id']   = $page_id;
		$args['offset']             = (int) $args['offset'] + ( ( (int) $_REQUEST['page'] - 1 ) * (int) $args['posts_per_page'] );

		if ( isset( $_REQUEST['taxonomy'] ) && isset( $_REQUEST['taxonomy']['taxonomy'] ) && $_REQUEST['taxonomy']['taxonomy'] != 'all' ) {
			$args['tax_query'] = [
				$this->sanitize_taxonomy_data( $_REQUEST['taxonomy'] ),
			];

			$relation = isset( $settings['relation_cats_tags'] ) ? $settings['relation_cats_tags'] : 'OR';
			if ( 'and' === strtolower( $relation ) ) {
				if ( 'product_cat' === $_REQUEST['taxonomy']['taxonomy'] && ! empty( $settings['tmpcoder_product_gallery_tags'] ) ) {
					$args['tax_query'][] = [
						'taxonomy' => 'product_tag',
						'field'    => 'term_id',
						'terms'    => $settings['tmpcoder_product_gallery_tags'],
						'operator' => 'IN',
					];
				}
				if ( 'product_tag' === $_REQUEST['taxonomy']['taxonomy'] && ! empty( $settings['tmpcoder_product_gallery_categories'] ) ) {
					$args['tax_query'][] = [
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $settings['tmpcoder_product_gallery_categories'],
						'operator' => 'IN',
					];
				}
			}

			$args['tax_query'] = $this->tmpcoder_terms_query_multiple( $args['tax_query'], $relation );

			if ( $settings[ 'tmpcoder_product_gallery_product_filter' ] == 'featured-products' ) {
				$args[ 'tax_query' ][] = [
					'relation' => 'AND',
					[
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
					],
					[
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => [ 'exclude-from-search', 'exclude-from-catalog' ],
						'operator' => 'NOT IN',
					],
				];
			}


		}

		$template_info = $this->tmpcoder_sanitize_template_param( $_REQUEST['template_info'] );

		if ( $template_info ) {

			if ( $template_info['dir'] === 'theme' ) {
				$dir_path = $this->retrive_theme_path();
			} else if ( $template_info['dir'] === 'pro' ) {
				$dir_path = sprintf( "%sinc", TMPCODER_PLUGIN_DIR );
			} else {
				$dir_path = sprintf( "%sinc", TMPCODER_PLUGIN_DIR );
			}

			$file_path = realpath( sprintf(
				'%s/woocommerce/%s/%s',
				$dir_path,
				$template_info['name'],
				$template_info['file_name']
			) );

			if ( ! $file_path || 0 !== strpos( $file_path, realpath( $dir_path ) ) ) {
				wp_send_json_error( 'Invalid template', 'invalid_template', 400 );
			}

			$html = '';
			if ( $file_path ) {
				$query = new \WP_Query( $args );

				if ( $query->have_posts() ) {

					do_action( 'tmpcoder_woo_before_product_loop' );

					while ( $query->have_posts() ) {
						$query->the_post();
						$html .= HelperClass::include_with_variable( $file_path, [ 'settings' => $settings ] );
					}
					
					do_action( 'tmpcoder_woo_after_product_loop' );

					$html .= '<div class="tmpcoder-max-page" style="display:none;">'. ceil($query->found_posts / absint( $args['posts_per_page'] ) ) . '</div>';

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $html;
					wp_reset_postdata();
				}
			}
		}
		wp_die();
	}

	public function tmpcoder_terms_query_multiple( $args_tax_query = [], $relation = 'OR' ){
		if ( strpos($args_tax_query[0]['taxonomy'], '|') !== false ) {
			$args_tax_query_item = $args_tax_query[0];

			//Query for category and tag
			$args_multiple['tax_query'] = [];

			if( isset( $args_tax_query_item['terms'] ) ){
				$args_multiple['tax_query'][] = [
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $args_tax_query_item['terms'],
				];
			}

			if( isset( $args_tax_query_item['terms_tag'] ) ){
				$args_multiple['tax_query'][] = [
					'taxonomy' => 'product_tag',
					'field' => 'term_id',
					'terms' => $args_tax_query_item['terms_tag'],
				];
			}


			if ( count( $args_multiple['tax_query'] ) ) {
				$args_multiple['tax_query']['relation'] = $relation;
			}

			$args_tax_query = $args_multiple['tax_query'];
		}

		if( isset( $args_tax_query[0]['terms_tag'] ) ){
			if( 'product_tag' === $args_tax_query[0]['taxonomy'] ){
				$args_tax_query[0]['terms'] = $args_tax_query[0]['terms_tag'];
			}
			unset($args_tax_query[0]['terms_tag']);
		}

		return $args_tax_query;
	}

	/**
	 * Get nonce token through ajax request
	 *
	 * @since 5.1.13
	 * @return void
	 */
	public function tmpcoder_get_token() {
		$nonce = wp_create_nonce( 'spexo-elementor-addons' );
		if ( $nonce ) {
			wp_send_json_success( [ 'nonce' => $nonce ] );
		}
		wp_send_json_error( __( 'you are not allowed to do this action', 'sastra-essential-addons-for-elementor' ) );
	}

	public function tmpcoder_yith_wcwl_ajax_disable( $request ) {
		add_filter( 'option_yith_wcwl_ajax_enable', function ( $data ) {
			return 'no';
		} );
	}

}
