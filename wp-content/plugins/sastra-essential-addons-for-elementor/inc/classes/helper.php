<?php

namespace Spexo_Addons_Elementor\Classes;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Plugin;

class Helper
{
	const TMPCODER_ALLOWED_HTML_TAGS = [
		'article',
		'aside',
		'div',
		'footer',
		'h1',
		'h2',
		'h3',
		'h4',
		'h5',
		'h6',
		'header',
		'main',
		'nav',
		'p',
		'section',
		'span',
	];

    /**
     * Include a file with variables
     *
     * @param $file_path
     * @param $variables
     *
     * @return string
     * @since  4.2.2
     */
    public static function include_with_variable( $file_path, $variables = [])
    {
        if (file_exists($file_path)) {
            extract($variables);

            ob_start();

            include $file_path;

            return ob_get_clean();
        }

        return '';
    }

    /**
     * Get All POst Types
     * @return array
     */
    public static function get_post_types()
    {
        $post_types = get_post_types(['public' => true, 'show_in_nav_menus' => true], 'objects');
        $post_types = wp_list_pluck($post_types, 'label', 'name');

        return array_diff_key($post_types, ['elementor_library', 'attachment']);
    }

    public static function get_query_args($settings = [], $post_type = 'post')
    {
        $settings = wp_parse_args( $settings, [
            'post_type'      => $post_type,
            'posts_ids'      => [],
            'orderby'        => 'date',
            'order'          => 'desc',
            'posts_per_page' => 3,
            'offset'         => 0,
            'post__not_in'   => [],
        ] );

        $args = [
            'orderby'             => $settings['orderby'],
            'order'               => $settings['order'],
            'ignore_sticky_posts' => 1,
            'post_status'         => 'publish',
            'posts_per_page'      => $settings['posts_per_page'],
            'offset'              => $settings['offset'],
        ];

        if ( 'by_id' === $settings['post_type'] ) {
            $args['post_type'] = 'any';
            $args['post__in']  = empty( $settings['posts_ids'] ) ? [ 0 ] : $settings['posts_ids'];
        } else {
            $args['post_type'] = $settings['post_type'];
            $args['tax_query'] = [];

            $taxonomies = get_object_taxonomies( $settings['post_type'], 'objects' );

            foreach ( $taxonomies as $object ) {
                $setting_key = $object->name . '_ids';

                if ( ! empty( $settings[ $setting_key ] ) ) {
                    $args['tax_query'][] = [
                        'taxonomy' => $object->name,
                        'field'    => 'term_id',
                        'terms'    => $settings[ $setting_key ],
                    ];
                }
            }

            if ( ! empty( $args['tax_query'] ) ) {
                $args['tax_query']['relation'] = 'AND';
            }
        }

        if ( $args['orderby'] === 'most_viewed' ) {
            $args['orderby']  = 'meta_value_num';
            $args['meta_key'] = '_eael_post_view_count';
        }

        if ( ! empty( $settings['authors'] ) ) {
            $args['author__in'] = $settings['authors'];
        }

        if ( ! empty( $settings['post__not_in'] ) ) {
            $args['post__not_in'] = $settings['post__not_in'];
        }

        if( 'product' === $post_type && function_exists('whols_lite') ){
            $args['meta_query'] = array_filter( apply_filters( 'woocommerce_product_query_meta_query', $args['meta_query'], new \WC_Query() ) );
        }

        return $args;
    }

    /**
     * Get allowed Types
     * @return array
    */

    public static function get_allowed_post_types() {
        $post_types = get_option( 'tmpcoder_allowed_post_types' );

        if ( empty( $post_types ) ) {
            return self::get_post_types();
        }

        $post_types = array_filter( $post_types, function( $value ) {
            return $value;
        } );

        if ( empty( $post_types ) ) {
            return [];
        }

        $post_types = array_intersect_key( self::get_post_types(), $post_types );

        return $post_types;
     }

    /**
     * POst Orderby Options
     *
     * @return array
     */
    public static function get_post_orderby_options()
    {
	    $orderby = array(
		    'ID'            => __( 'Post ID', 'sastra-essential-addons-for-elementor' ),
		    'author'        => __( 'Post Author', 'sastra-essential-addons-for-elementor' ),
		    'title'         => __( 'Title', 'sastra-essential-addons-for-elementor' ),
		    'date'          => __( 'Date', 'sastra-essential-addons-for-elementor' ),
		    'modified'      => __( 'Last Modified Date', 'sastra-essential-addons-for-elementor' ),
		    'parent'        => __( 'Parent Id', 'sastra-essential-addons-for-elementor' ),
		    'rand'          => __( 'Random', 'sastra-essential-addons-for-elementor' ),
		    'comment_count' => __( 'Comment Count', 'sastra-essential-addons-for-elementor' ),
		    'most_viewed'   => __( 'Most Viewed', 'sastra-essential-addons-for-elementor' ),
		    'menu_order'    => __( 'Menu Order', 'sastra-essential-addons-for-elementor' )
	    );

        return $orderby;
    }

    /**
     * Get Post Categories
     *
     * @return array
     */
    public static function get_terms_list($taxonomy = 'category', $key = 'term_id')
    {
        $options = [];
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ]);

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->{$key}] = $term->name;
            }
        }

        return $options;
    }

    /**
     * Get all Authors
     *
     * @return array
     */
	public static function get_authors_list() {
		$args = [
			'capability'          => [ 'edit_posts' ],
			'has_published_posts' => true,
			'fields'              => [
				'ID',
				'display_name',
			],
		];

		// Capability queries were only introduced in WP 5.9.
		if ( version_compare( $GLOBALS['wp_version'], '5.9-alpha', '<' ) ) {
			$args['who'] = 'authors';
			unset( $args['capability'] );
		}

		$users = get_users( $args );

		if ( ! empty( $users ) ) {
			return wp_list_pluck( $users, 'display_name', 'ID' );
		}

		return [];
	}

    public static function get_dynamic_args(array $settings, array $args)
    {
	    if ( $settings['post_type'] === 'source_dynamic' && ( is_archive() || is_search() ) ) {
            $data = get_queried_object();

            if (isset($data->post_type)) {
                $args['post_type'] = $data->post_type;
                $args['tax_query'] = [];
            } else {
                global $wp_query;
                $args['post_type'] = $wp_query->query_vars['post_type'];
                if(!empty($wp_query->query_vars['s'])){
                    $args['s'] = $wp_query->query_vars['s'];
                    $args['offset'] = 0;
                }
            }

            if ( isset( $data->taxonomy ) ) {
                $args[ 'tax_query' ][] = [
                    'taxonomy' => $data->taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $data->term_id,
                ];
            }

            if ( isset($data->taxonomy) ) {
                $args[ 'tax_query' ][] = [
                    'taxonomy' => $data->taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $data->term_id,
                ];
            }

            if (get_query_var('author') > 0) {
                $args['author__in'] = get_query_var('author');
            }

            if (get_query_var('s')!='') {
                $args['s'] = get_query_var('s');
            }

            if (get_query_var('year') || get_query_var('monthnum') || get_query_var('day')) {
                $args['date_query'] = [
                    'year' => get_query_var('year'),
                    'month' => get_query_var('monthnum'),
                    'day' => get_query_var('day'),
                ];
            }

            if (!empty($args['tax_query'])) {
                $args['tax_query']['relation'] = 'AND';
            }

            $args[ 'meta_query' ] = [ 'relation' => 'AND' ];
            $show_stock_out_products = isset( $settings['tmpcoder_product_out_of_stock_show'] ) ? $settings['tmpcoder_product_out_of_stock_show'] : 'yes';

            if ( get_option( 'woocommerce_hide_out_of_stock_items' ) == 'yes' || 'yes' !== $show_stock_out_products  ) {
                $args[ 'meta_query' ][] = [
                    'key'   => '_stock_status',
                    'value' => 'instock'
                ];
            }
            if( 'product' === $args['post_type'] && function_exists('whols_lite') ){
                $args['meta_query'] = array_filter( apply_filters( 'woocommerce_product_query_meta_query', $args['meta_query'], new \WC_Query() ) );
            }
        }

        return $args;
    }

    public static function tmpcoder_get_widget_settings( $page_id, $widget_id ) {
        $document = Plugin::$instance->documents->get( $page_id );
        $settings = [];
        if ( $document ) {
            $elements    = Plugin::instance()->documents->get( $page_id )->get_elements_data();
            $widget_data = self::find_element_recursive( $elements, $widget_id );
            if (!empty($widget_data) && is_array($widget_data)) {
                $widget      = Plugin::instance()->elements_manager->create_element_instance( $widget_data );
            }
            if ( !empty($widget) ) {
                $settings    = $widget->get_settings_for_display();
            }
        }
        return $settings;
    }

    /**
     * Get Widget data.
     *
     * @param array  $elements Element array.
     * @param string $form_id  Element ID.
     *
     * @return bool|array
     */
    public static function find_element_recursive( $elements, $form_id ) {

        foreach ( $elements as $element ) {
            if ( $form_id === $element['id'] ) {
                return $element;
            }

            if ( ! empty( $element['elements'] ) ) {
                $element = self::find_element_recursive( $element['elements'], $form_id );

                if ( $element ) {
                    return $element;
                }
            }
        }

        return false;
    }

	/**
	 * tmpcoder_pagination
     * Generate post pagination
     *
	 * @param $args array wp_query param
	 * @param $settings array Elementor widget setting data
	 *
     * @access public
	 * @return string|void
     * @since 3.3.0
	 */
	public static function tmpcoder_pagination($args, $settings) {

		$pagination_Count          = intval( $args['total_post'] ?? 0 );
		$paginationLimit           = intval( $settings['tmpcoder_product_grid_products_count'] ) ?: 4;
		$pagination_Paginationlist = ceil( $pagination_Count / $paginationLimit );
		$widget_id                 = sanitize_key( $settings['tmpcoder_widget_id'] );
		$page_id                   = intval( $settings['tmpcoder_page_id'] );
		$next_label                = $settings['pagination_next_label'];
		$adjacents                 = "2";
		$setPagination             = "";
		$template_info             = [
			'dir'       => 'free',
			'file_name'  => 'default',
			'name'      => $settings['tmpcoder_widget_name']
		];

        if ( ! empty( $settings['tmpcoder_dynamic_template_Layout'] ) ) {
            $template_info['file_name'] = $settings['tmpcoder_dynamic_template_Layout'];
        } else if ( ! empty( $settings['tmpcoder_product_grid_template'] ) ) {
            $template_info['file_name'] = $settings['tmpcoder_product_grid_template'];
        }

		if( $pagination_Paginationlist > 0 ){

			$setPagination .="<nav id='{$widget_id}-tmpcoder-pagination' class='tmpcoder-woo-pagination' data-plimit='$paginationLimit' data-totalpage ='{$args['total_post']}' data-widgetid='{$widget_id}' data-pageid='$page_id' data-args='".http_build_query( $args )."'  data-template='".json_encode( $template_info, 1 )."'>";
			    $setPagination .="<ul class='page-numbers'>";

                    if ( $pagination_Paginationlist < 7 + ($adjacents * 2) ){
                        for ( $pagination = 1; $pagination <= $pagination_Paginationlist; $pagination ++ ) {
                            $active        = ( $pagination == 0 || $pagination == 1 ) ? 'current' : '';
	                        $setPagination .= sprintf("<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>" , esc_attr( $active ) ,esc_html( $pagination ) );
                        }

                    } else if ( $pagination_Paginationlist >= 5 + ($adjacents * 2) ){
                        for ( $pagination = 1; $pagination <= 4 + ( $adjacents * 2 ); $pagination ++ ) {
                            $active        = ( $pagination == 0 || $pagination == 1 ) ? 'current' : '';
	                        $setPagination .= sprintf("<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>" ,esc_attr( $active ) ,esc_html( $pagination ) );
                        }

                        $setPagination .="<li class='pagitext dots'>...</li>";
                        $setPagination .= sprintf("<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>" ,esc_attr( $active ) ,esc_html( $pagination ) );
                    }

                    if ($pagination_Paginationlist > 1) {
                        $setPagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-pnumber='2'>".esc_html( $next_label )."</a></li>";
                    }

                $setPagination .="</ul>";
			$setPagination .="</nav>";

			return $setPagination;
		}
	}

	public static function tmpcoder_product_quick_view($product, $settings, $widget_id) {

		$sale_badge_align  = isset( $settings['tmpcoder_product_sale_badge_alignment'] ) ? $settings['tmpcoder_product_sale_badge_alignment'] : '';
		$sale_badge_preset = isset( $settings['tmpcoder_product_sale_badge_preset'] ) ? $settings['tmpcoder_product_sale_badge_preset'] : '';
		$sale_text         = ! empty( $settings['tmpcoder_product_carousel_sale_text'] ) ? $settings['tmpcoder_product_carousel_sale_text'] : (! empty( $settings['tmpcoder_product_sale_text'] ) ? $settings['tmpcoder_product_sale_text'] :( !empty( $settings['tmpcoder_product_gallery_sale_text'] ) ? $settings['tmpcoder_product_gallery_sale_text'] : 'Sale!' ));
		$stockout_text     = ! empty( $settings['tmpcoder_product_carousel_stockout_text'] ) ? $settings['tmpcoder_product_carousel_stockout_text'] : (! empty( $settings['tmpcoder_product_stockout_text'] ) ? $settings['tmpcoder_product_stockout_text'] : ( !empty($settings['tmpcoder_product_gallery_stockout_text']) ? $settings['tmpcoder_product_gallery_stockout_text'] : 'Stock Out' ));
        $tag               = ! empty( $settings['tmpcoder_product_quick_view_title_tag'] ) ? self::tmpcoder_validate_html_tag( $settings['tmpcoder_product_quick_view_title_tag'] ) : 'h1';
        
        remove_action( 'tmpcoder_woo_single_product_summary', 'woocommerce_template_single_title', 5 );
        add_action( 'tmpcoder_woo_single_product_summary', function () use ( $tag ) {
            printf('<%1$s class="tmpcoder-product-quick-view-title product_title entry-title">%2$s</%1$s>',esc_html( $tag ), wp_kses( get_the_title(), Helper::tmpcoder_allowed_tags() ));
        }, 5 );

	    ?>

		<div id="eaproduct<?php echo esc_attr( $widget_id . $product->get_id() ); ?>" class="tmpcoder-product-popup
		tmpcoder-product-zoom-in woocommerce">
			<div class="tmpcoder-product-modal-bg"></div>
			<div class="tmpcoder-product-popup-details">
				<div id="product-<?php esc_attr( get_the_ID() ); ?>" <?php post_class( 'product' ); ?>>
					<div class="tmpcoder-product-image-wrap">
						<?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo ( ! $product->is_in_stock() ? '<span class="tmpcoder-onsale outofstock '.esc_attr( $sale_badge_preset ).' '.esc_attr( $sale_badge_align ).'">'. Helper::tmpcoder_wp_kses( $stockout_text ) .'</span>' : ($product->is_on_sale() ? '<span class="tmpcoder-onsale '.esc_attr( $sale_badge_preset ).' '.esc_attr( $sale_badge_align ).'">' . Helper::tmpcoder_wp_kses( $sale_text ) . '</span>' : '') );
						do_action( 'tmpcoder_woo_single_product_image' );
						?>
					</div>
					<div class="tmpcoder-product-details-wrap">
						<?php do_action( 'tmpcoder_woo_single_product_summary' ); ?>
					</div>
				</div>
				<button class="tmpcoder-product-popup-close"><i class="fas fa-times"></i></button>
			</div>

		</div>
	<?php
	}

	public static function tmpcoder_avoid_redirect_to_single_page() {
		return '';
	}

	public static function tmpcoder_woo_product_grid_actions() {
		add_filter( 'woocommerce_add_to_cart_form_action', self::tmpcoder_avoid_redirect_to_single_page(), 10 );
		add_action( 'tmpcoder_woo_before_product_loop', 'woocommerce_output_all_notices', 30 );
	}

	/**
	 * tmpcoder_validate_html_tag
	 * @param $tag
	 * @return mixed|string
	 */
    public static function tmpcoder_validate_html_tag( $tag ){
	    return in_array( strtolower( (string) $tag ), self::TMPCODER_ALLOWED_HTML_TAGS ) ? $tag : 'div';
    }

	/**
     *
     * Strip tag based on allowed html tag
	 * tmpcoder_wp_kses
	 * @param $text
	 * @return string
	 */
	public static function tmpcoder_wp_kses( $text ) {
        if ( empty( $text ) ) {
            return '';
        }
		return wp_kses( $text, self::tmpcoder_allowed_tags(), array_merge( wp_allowed_protocols(), [ 'data' ] ) );
	}

    /**
     * List of allowed protocols for wp_kses
     *
	 * tmpcoder_allowed_protocols
	 * @return array
	 */
    public static function tmpcoder_allowed_protocols( $extra = [] ) {
        $protocols = array_merge( wp_allowed_protocols(), [ 'data' ] );
        if ( count( $extra ) > 0 ) {
			$protocols = array_merge( $protocols, $extra );
		}
        return $protocols;
	}

	/**
     * List of allowed html tag for wp_kses
     *
	 * tmpcoder_allowed_tags
	 * @return array
	 */
	public static function tmpcoder_allowed_tags( $extra = [] ) {
		$allowed_tags = [
			'a'       => [
				'href'   => [],
				'title'  => [],
				'class'  => [],
				'rel'    => [],
				'id'     => [],
				'style'  => [],
				'target' => [],
				'data-elementor-open-lightbox' => [],
			],
			'q'       => [
				'cite'  => [],
				'class' => [],
				'id'    => [],
			],
			'img'     => [
				'src'    => [],
				'alt'    => [],
				'title'  => [],
				'height' => [],
				'width'  => [],
				'class'  => [],
				'id'     => [],
				'style'  => []
			],
			'span'    => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'dfn'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'time'    => [
				'datetime' => [],
				'class'    => [],
				'id'       => [],
				'style'    => [],
			],
			'cite'    => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'hr'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'b'       => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'p'       => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'i'       => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'u'       => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			's'       => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'br'      => [],
			'em'      => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'code'    => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'mark'    => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'small'   => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'abbr'    => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'strong'  => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'del'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'ins'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'sub'     => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'sup'     => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'div'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'strike'  => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'acronym' => [],
			'h1'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h2'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h3'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h4'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h5'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h6'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'button'  => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'center'  => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'ul'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'ol'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'li'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'table'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'dir'   => [],
				'align' => [],
			],
			'thead'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'tbody'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'tfoot'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'th'      => [
				'class'   => [],
				'id'      => [],
				'style'   => [],
				'align'   => [],
				'colspan' => [],
				'rowspan' => [],
			],
			'tr'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'td'     => [
				'class'   => [],
				'id'      => [],
				'style'   => [],
				'align'   => [],
				'colspan' => [],
				'rowspan' => [],
			],
			'header' => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'iframe' => [
				'class'  => [],
				'id'     => [],
				'style'  => [],
				'title'  => [],
				'width'  => [],
				'height' => [],
				'src'    => []
			]
		];

		if ( count( $extra ) > 0 ) {
			$allowed_tags = array_merge_recursive( $allowed_tags, $extra );
		}

		return apply_filters( 'tmpcoder_allowed_tags', $allowed_tags );
	}

	/**
	 * Sanitize a 'relation' operator.
	 *
	 * @param string $relation Raw relation key from the query argument.
	 *
	 * @return string Sanitized relation ('AND' or 'OR').
	 * @since 5.3.2
	 *
	 */
	public static function tmpcoder_sanitize_relation( $relation ) {
		if ( 'OR' === strtoupper( $relation ) ) {
			return 'OR';
		} else {
			return 'AND';
		}
	}

    /**
     * Get all ordered products by the user
     * @return boolean|array order ids
     * @since 5.8.9
     */
    public static function tmpcoder_get_all_user_ordered_products() {
        $user_id = get_current_user_id();

        if( ! $user_id ) {
            return false;
        }

        $args = array(
            'customer_id' => $user_id,
            'limit' => -1,
        );

        $orders = wc_get_orders($args);
        $product_ids = [];

        foreach( $orders as $order ){
            $items = $order->get_items();
            
            foreach($items as $item){
                $product_ids[] = $item->get_product_id();
            }
        }

        return $product_ids;
    }

    public static function tmpcoder_rating_markup( $rating, $count ) {
        $html = '';
		if ( 0 == $rating ) {
			$html  = '<div class="tmpcoder-star-rating star-rating">';
			$html .= wc_get_star_rating_html( $rating, $count );
			$html .= '</div>';
		}
		return $html;
	}

    //WooCommerce Helper Function
    public static function get_product_variation( $product_id = false ) {
		return wc_get_product( get_the_ID() );
	}
    
    public static function get_product( $product_id = false ) {
		if ( 'product_variation' === get_post_type() ) {
			return self::get_product_variation( $product_id );
		}
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			$product = wc_get_product();
		}
		return $product;
	}

    public static function tmpcoder_e_optimized_markup(){
        return Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
    }

    public static function render_product_wishlist_button( $settings, $class ) {
        global $product;
        
        if ( !tmpcoder_is_availble() ) {
            return;
        }

        // If NOT a Product
        if ( is_null( $product ) ) {
            return;
        }

        $user_id = get_current_user_id();
        
        if ($user_id > 0) {
            if (is_multisite()) {
                $wishlist_key = 'tmpcoder_wishlist_'.get_current_blog_id();
            } else {
                $wishlist_key = 'tmpcoder_wishlist';
            }
            $wishlist = get_user_meta( get_current_user_id(), $wishlist_key, true );
        } else {
			$blog_id = get_current_blog_id();
			$cookie_key = 'tmpcoder_wishlist_' . $blog_id;
		
			if (isset($_COOKIE['tmpcoder_wishlist'])) {
				$wishlist = json_decode(sanitize_text_field(wp_unslash($_COOKIE['tmpcoder_wishlist'])), true);
			} else if (isset($_COOKIE[$cookie_key])) {
				$wishlist = json_decode(sanitize_text_field(wp_unslash($_COOKIE[$cookie_key])), true);
			} else {
				$wishlist = array();
			}
        }
        
        if ( ! $wishlist ) {
            $wishlist = array();
        }

        // $popup_notification_animation = isset($this->get_settings_for_display()['popup_notification_animation']) ? $this->get_settings_for_display()['popup_notification_animation'] : '';
        // $popup_notification_fade_out_in = isset($this->get_settings_for_display()['popup_notification_fade_out_in']) ? $this->get_settings_for_display()['popup_notification_fade_out_in'] : '';
        // $popup_notification_animation_duration = isset($this->get_settings_for_display()['popup_notification_animation_duration']) ? $this->get_settings_for_display()['popup_notification_animation_duration'] : '';

        $wishlist_attributes = [
            'data-wishlist-url' => get_option('tmpcoder_wishlist_page') ? get_option('tmpcoder_wishlist_page') : '',
        //  'data-atw-popup='. $settings['element_show_added_to_wishlist_popup'],
        //  'data-atw-animation='. $popup_notification_animation,
        //  'data-atw-fade-out-in='. $popup_notification_fade_out_in,
        //  'data-atw-animation-time='. $popup_notification_animation_duration,
        //  'data-open-in-new-tab='. $settings['element_open_links_in_new_tab']
        ];

        // $wishlist_attributes = [];

        $button_HTML = '';
        $page_id = get_queried_object_id();
        
        $button_add_title = '';
        $button_remove_title = '';
        $add_to_wishlist_content = '';
        $remove_from_wishlist_content = '';

        if ( 'yes' === $settings['show_icon'] ) {
            $add_to_wishlist_content .= '<i class="far fa-heart"></i>';
            $remove_from_wishlist_content .= '<i class="fas fa-heart"></i>';
        }

        if ( 'yes' === $settings['show_text'] ) {
            $add_to_wishlist_content .= ' <span>'. esc_html($settings['add_to_wishlist_text']) .'</span>';
        } else {
            $button_add_title = 'title='. esc_attr($settings['add_to_wishlist_text']);
            $button_remove_title = 'title='. esc_attr($settings['remove_from_wishlist_text']);
        }

        if ( 'yes' === $settings['show_text'] ) {
            $remove_from_wishlist_content .= ' <span>'. esc_html($settings['remove_from_wishlist_text']) .'</span>';
        }

        echo '<div class="'. esc_attr($class) .'">';
            echo '<div class="inner-block">';
    
            $remove_button_hidden = !in_array( $product->get_id(), $wishlist ) ? 'tmpcoder-button-hidden' : '';
            $add_button_hidden = in_array( $product->get_id(), $wishlist ) ? 'tmpcoder-button-hidden' : '';
        
            // '. implode( ' ', $wishlist_attributes ) .'
            echo '<button class="tmpcoder-wishlist-add '. esc_attr($add_button_hidden) .'" '. esc_attr($button_add_title) .' data-product-id=' . esc_attr($product->get_id()) . ''. ' ' . esc_attr(implode( ' ', $wishlist_attributes )) .' >'. wp_kses_post($add_to_wishlist_content) .'</button>';
            echo '<button class="tmpcoder-wishlist-remove '. esc_attr($remove_button_hidden) .'" '. esc_attr($button_remove_title) .' data-product-id="' . esc_attr($product->get_id()) . '">'. wp_kses_post($remove_from_wishlist_content) .'</button>';

            echo '</div>';
        echo '</div>';
    }

	// Render Post Thumbnail
	public static function render_product_thumbnail( $settings ) {
		$id  = get_post_thumbnail_id();

		$settings[ 'image_effects_animation_timing'] = 'ease-default';

		$src = Group_Control_Image_Size::get_attachment_image_src( $id, 'tmpcoder_product_grid_image_size', $settings );

		$settings['tmpcoder_product_grid_image_size'] = ['id' => $id];
		$product_image_html = Group_Control_Image_Size::get_attachment_image_html($settings,'tmpcoder_product_grid_image_size');

		$image_original_class = 'wp-image-'.$id;
		$custom_image_class = $image_original_class.' tmpcoder-anim-timing-'.$settings[ 'image_effects_animation_timing'];
		$product_image_html = str_replace($image_original_class, $custom_image_class, $product_image_html);

		if ('' === get_post_meta( $id, '_wp_attachment_image_alt', true )) {
			$product_image_html = preg_replace( '/<img(.*?)alt="(.*?)"(.*?)>/i', '<img$1alt="'.get_the_title().'"$3>', $product_image_html );
		}

		if ( 
			get_post_meta(get_the_ID(), 'tmpcoder_secondary_image_id') 
			&& !empty(get_post_meta(get_the_ID(), 'tmpcoder_secondary_image_id')) 
			&& tmpcoder_get_settings('tmpcoder_meta_secondary_image_product') === 'on'
		) {
		
			$src2 = Group_Control_Image_Size::get_attachment_image_src( get_post_meta(get_the_ID(), 'tmpcoder_secondary_image_id')[0], 'tmpcoder_product_grid_image_size', $settings );

			$settings['tmpcoder_product_grid_image_size'] = ['id' => get_post_meta(get_the_ID(), 'tmpcoder_secondary_image_id', true)];
			$second_image_html = Group_Control_Image_Size::get_attachment_image_html($settings,'tmpcoder_product_grid_image_size');

			$second_original_class = 'wp-image-'.get_post_meta(get_the_ID(), 'tmpcoder_secondary_image_id', true);
			$second_image_class = $second_original_class.' tmpcoder-anim-timing-'.$settings[ 'image_effects_animation_timing'];
			$product_image_html = str_replace($second_original_class, $second_image_class.' tmpcoder-hidden-img', $product_image_html);
		} else {
			$settings['secondary_img_on_hover'] = 'no';
			$second_image_html = '';
			$src2 = '';
		}

		// if ( has_post_thumbnail() ) {

			echo '<div class="tmpcoder-grid-media-wrap">';
			echo '<div class="tmpcoder-grid-image-wrap" data-src="'. esc_url( $src ) .'"  data-img-on-hover="'. esc_attr($settings['secondary_img_on_hover']) .'" data-src-secondary="'. esc_url( $src2 ) .'">';

				if (!has_post_thumbnail() && isset($settings['tmpcoder_fallback_image_switch']) && $settings['tmpcoder_fallback_image_switch'] == 'yes') {
						
					if (isset($settings['tmpcoder_fallback_image']['url']) && $settings['tmpcoder_fallback_image']['url'] != '') {
						$product_image_html = '<img src="'.esc_url($settings['tmpcoder_fallback_image']['url']).'" width="366" height="366" alt="'.esc_attr(get_the_title()).'">';
						$src = $settings['tmpcoder_fallback_image']['url'];
					}
				}

				echo wp_kses_post($product_image_html); 

				if ( 'yes' == $settings['secondary_img_on_hover'] && !empty($src2)) {
					echo wp_kses_post($second_image_html);
				}
			echo '</div>';
			echo '</div>';
		// }
	}

}
