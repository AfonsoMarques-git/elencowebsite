<?php

namespace Spexo_Addons_Elementor\Traits;

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

use Elementor\Plugin;
use \Spexo_Addons_Elementor\Classes\Helper as HelperClass;

trait Helper
{
    use Template_Query;

    public function tmpcoder_load_more_button_style() {
        return do_action( 'tmpcoder/controls/load_more_button_style', $this );
    }

    public function tmpcoder_read_more_button_style() {
        return do_action( 'tmpcoder/controls/read_more_button_style', $this );
    }

    public function tmpcoder_controls_custom_positioning( $_1, $_2, $_3, $_4 ) {
        return do_action( 'tmpcoder/controls/custom_positioning', $this, $_1, $_2, $_3, $_4 );
    }

    public function tmpcoder_get_all_types_post() {
        return HelperClass::get_post_types();
    }

	/**
	 * It returns the widget settings provided the page id and widget id
	 * @param int $page_id Page ID where the widget is used
	 * @param string $widget_id the id of the widget whose settings we want to fetch
	 *
	 * @return array
	 */
	public function tmpcoder_get_widget_settings( $page_id, $widget_id ) {
		$document = Plugin::$instance->documents->get( $page_id );
		$settings = [];
		if ( $document ) {
			$elements    = Plugin::instance()->documents->get( $page_id )->get_elements_data();
			// $widget_data = $this->find_element_recursive( $elements, $widget_id );
            $widget_data = HelperClass::find_element_recursive( $elements, $widget_id );
            if(!empty($widget_data)) {
                $widget      = Plugin::instance()->elements_manager->create_element_instance( $widget_data );
                if ( $widget ) {
                    $settings    = $widget->get_settings_for_display();
                }
            }
		}
		return $settings;
	}
	
    public function print_load_more_button($settings, $args, $plugin_type = 'free')
    {
        //@TODO; not all widget's settings contain posts_per_page name exactly, so adjust the settings before passing here or run a migration and make all settings key generalize for load more feature.
        if (!isset($this->page_id)) {
            if ( Plugin::$instance->documents->get_current() ) {
                $this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
            }else{
                $this->page_id = null;
            }
        }

	    $max_page = empty( $args['max_page'] ) ? false : $args['max_page'];
	    unset( $args['max_page'] );

        if ( isset( $args['found_posts'] ) && $args['found_posts'] <= $args['posts_per_page'] ){
	        $this->add_render_attribute( 'load-more', [ 'class' => 'hide-load-more' ] );
	        unset( $args['found_posts'] );
        }

	    $this->add_render_attribute( 'load-more', [
		    'class'          => "tmpcoder-load-more-button",
		    'id'             => "tmpcoder-load-more-btn-" . $this->get_id(),
		    'data-widget-id' => $this->get_id(),
		    'data-widget'    => $this->get_id(),
		    'data-page-id'   => $this->page_id,
		    'data-template'  => json_encode( [
			    'dir'       => $plugin_type,
			    'file_name' => $settings['loadable_file_name'],
			    'name'      => $this->process_directory_name()
		    ],
			    1 ),
		    'data-class'     => get_class( $this ),
		    'data-layout'    => isset( $settings['layout_mode'] ) ? $settings['layout_mode'] : "",
		    'data-page'      => 1,
		    'data-args'      => http_build_query( $args ),
	    ]);

	    if ( $max_page ) {
		    $this->add_render_attribute( 'load-more', [ 'data-max-page' => $max_page ] );
	    }

        if ( $args['posts_per_page'] != '-1' ) {
            $this->add_render_attribute( 'load-more-wrap', 'class', 'tmpcoder-load-more-button-wrap' );
        
            if ( "tmpcoder-dynamic-filterable-gallery" == $this->get_name() ){
                $this->add_render_attribute( 'load-more-wrap', 'class', 'dynamic-filter-gallery-loadmore' );
            }
            
            if ( 'infinity' === $settings['show_load_more'] ) {
                $this->add_render_attribute( 'load-more-wrap', 'class', 'tmpcoder-infinity-scroll' );
                $this->add_render_attribute( 'load-more-wrap', 'data-offset', esc_attr( $settings['load_more_infinityscroll_offset'] ) );
            } else if ( ! ( 'true' == $settings['show_load_more'] || 1 == $settings['show_load_more'] || 'yes' == $settings['show_load_more'] ) ){
                $this->add_render_attribute( 'load-more-wrap', 'class', 'tmpcoder-force-hide' );
            }

            do_action( 'tmpcoder/global/before-load-more-button', $settings, $args, $plugin_type );
            ?>
            <div <?php $this->print_render_attribute_string( 'load-more-wrap' ); ?>>
                <button <?php $this->print_render_attribute_string( 'load-more' ); ?>>
                    <span class="tmpcoder-btn-loader button__loader"></span>
                    <span class="tmpcoder_load_more_text"><?php echo esc_html($settings['show_load_more_text']) ?></span>
                </button>
            </div>
            <?php 
            do_action( 'tmpcoder/global/after-load-more-button', $settings, $args, $plugin_type );
        }
    }

    public function tmpcoder_product_grid_script(){
		if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
			if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
				wp_enqueue_script( 'zoom' );
			}
			if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
				wp_enqueue_script( 'flexslider' );
			}
			if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
				wp_enqueue_script( 'photoswipe-ui-default' );
				wp_enqueue_style( 'photoswipe-default-skin' );
				if ( has_action( 'wp_footer', 'woocommerce_photoswipe' ) === false ) {
					add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
				}
			}
            wp_enqueue_script( 'wc-add-to-cart-variation' );
			wp_enqueue_script( 'wc-single-product' );
		}
	}

	/**
	* Rating Markup
	*/
	public function tmpcoder_rating_markup( $html, $rating, $count ) {

		if ( 0 == $rating ) {
			$html  = '<div class="tmpcoder-star-rating star-rating">';
			$html .= wc_get_star_rating_html( $rating, $count );
			$html .= '</div>';
		}
		return $html;
	}

	public function tmpcoder_product_wrapper_class( $classes, $product_id, $widget_name ) {

		if ( ! is_plugin_active( 'woo-variation-swatches-pro/woo-variation-swatches-pro.php' ) ) {
			return $classes;
		}

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return $classes;
		}

		if ( $product->is_type( 'variable' ) ) {
			$classes[] = 'wvs-archive-product-wrapper';
		}

		return $classes;
	}

	public function tmpcoder_woo_cart_empty_action() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		if ( isset( $_GET['empty_cart'] ) && 'yes' === esc_html( $_GET['empty_cart'] ) ) {
			WC()->cart->empty_cart();
		}
	}

	public function change_add_woo_checkout_update_order_reviewto_cart_text( $add_to_cart_text ) {
		add_filter( 'woocommerce_product_add_to_cart_text', function ( $default ) use ( $add_to_cart_text ) {
			global $product;
			switch ( $product->get_type() ) {
				case 'external':
					return $add_to_cart_text[ 'add_to_cart_external_product_button_text' ];
					break;
				case 'grouped':
					return $add_to_cart_text[ 'add_to_cart_grouped_product_button_text' ];
					break;
				case 'simple':
					return $add_to_cart_text[ 'add_to_cart_simple_product_button_text' ];
					break;
				case 'variable':
					return $add_to_cart_text[ 'add_to_cart_variable_product_button_text' ];
					break;
				default:
					return $default;
			}
		} );
	}

    /**
	 * return file path which are store in theme Template directory
	 * @param $file
	 */
	public function retrive_theme_path() {
		$current_theme = wp_get_theme();
		return sprintf(
			'%s/%s',
			$current_theme->theme_root,
			$current_theme->stylesheet
		);
	}

	/**
	 * tmpcoder_wpml_template_translation
	 * @param $id
	 * @return mixed|void
	 */
    public function tmpcoder_wpml_template_translation($id){
	    $postType = get_post_type( $id );
	    if ( 'elementor_library' === $postType ) {
		    return apply_filters( 'wpml_object_id', $id, $postType, true );
	    }
	    return $id;
    }

	/**
	 * tmpcoder_sanitize_template_param
     * Removes special characters that are illegal in filenames
     *
	 * @param array $template_info
	 *
     * @access public
	 * @return array
     * @since 5.0.4
	 */
    public function tmpcoder_sanitize_template_param( $template_info ){
	    $template_info = array_map( 'sanitize_text_field', $template_info );
	    return array_map( 'sanitize_file_name', $template_info );
    }

	/**
	 * sanitize_taxonomy_data
     * Sanitize all value for tax query
     *
	 * @param array $tax_list taxonomy param list
	 *
     * @access protected
	 * @return array|array[]|string[]
	 * @since 5.0.4
	 */
    public function sanitize_taxonomy_data( $tax_list ){
	    return array_map( function ( $param ) {
		    return is_array( $param ) ? array_map( 'sanitize_text_field', $param ) : sanitize_text_field( $param );
	    }, $tax_list );
    }
}
