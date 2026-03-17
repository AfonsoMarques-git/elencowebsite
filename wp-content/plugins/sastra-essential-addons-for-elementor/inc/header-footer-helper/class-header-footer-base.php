<?php 

use Elementor\Core\Base\Elements_Iteration_Actions\Assets;
use Elementor\Core\Files\CSS\Post as Post_CSS;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once 'header-footer-elements.php';

/**
 * Checks if Header is enabled.
 *
 * @since  1.0.0
 * @return bool True if header is enabled. False if header is not enabled
 */
function tmpcoder_header_enabled() {
	$header_id = TMPCODER_Header_Footer_Elements::get_settings( 'type_header', '' );
	$status    = false;

	if ( '' !== $header_id ) {
		$status = true;
	}

	return apply_filters( 'tmpcoder_header_enabled', $status );
}

/**
 * Checks if Archive is enabled.
 *
 * @since  1.0.0
 * @return bool True if archive is enabled. False if archive is not enabled
 */

function tmpcoder_archive_enabled() {
	$archive_id = TMPCODER_Header_Footer_Elements::get_settings( 'type_archive', '' );
	$status    = false;

	if ( '' !== $archive_id ) {
		$status = true;
	}

	return apply_filters( 'tmpcoder_archive_enabled', $status );
}

/**
 * Checks if dynamic content is enabled.
 *
 * @since  1.0.0
 * @return bool True if dynamic content is enabled. False if dynamic content is not enabled
 */

function tmpcoder_dynamic_content_enabled($type) {
	
	$id = TMPCODER_Header_Footer_Elements::get_settings( $type, '' );
	$status = false;

	if ( '' !== $id ) {
		$status = true;
	}

	return apply_filters( 'tmpcoder_'.$type.'_enabled', $status );
}

/**
 * Checks if Footer is enabled.
 *
 * @since  1.0.0
 * @return bool True if header is enabled. False if header is not enabled.
 */
function tmpcoder_footer_enabled() {
	$footer_id = TMPCODER_Header_Footer_Elements::get_settings( 'type_footer', '' );
	$status    = false;

	if ( '' !== $footer_id ) {
		$status = true;
	}

	return apply_filters( 'tmpcoder_footer_enabled', $status );
}

/**
 * Checks if 404 page is enabled.
 *
 * @since  1.0.0
 * @return bool True if 404 page is enabled. False if 404 page is not enabled.
 */
function tmpcoder_404_page_enabled() {
	$not_found_page_id = TMPCODER_Header_Footer_Elements::get_settings( 'type_404', '' );
	$status    = false;

	if ( '' !== $not_found_page_id ) {
		$status = true;
	}

	return apply_filters( 'tmpcoder_404_page_enabled', $status );
}

/**
 * Get template Header ID
 *
 * @since  1.0.0
 * @return (String|boolean) header id if it is set else returns false.
 */
function tmpcoder_get_header_id() {
	$header_id = TMPCODER_Header_Footer_Elements::get_settings( 'type_header', '' );

	if ( '' === $header_id ) {
		$header_id = false;
	}

	return apply_filters( 'tmpcoder_get_header_id', $header_id );
}

/**
 * Get template Footer ID
 *
 * @since  1.0.0
 * @return (String|boolean) header id if it is set else returns false.
 */
function tmpcoder_get_footer_id() {

	$footer_id = TMPCODER_Header_Footer_Elements::get_settings( 'type_footer', '' );

	if ( '' === $footer_id ) {
		$footer_id = false;
	}

	return apply_filters( 'tmpcoder_get_footer_id', $footer_id );
}

/**
 * Get template 404 page ID
 *
 * @since  1.0.0
 * @return (String|boolean) 404 page id if it is set else returns false.
 */
function tmpcoder_get_404_page_id() {
	$not_found_page_id = TMPCODER_Header_Footer_Elements::get_settings( 'type_404', '' );

	if ( '' === $not_found_page_id ) {
		$not_found_page_id = false;
	}

	return apply_filters( 'tmpcoder_get_404_page_id', $not_found_page_id );
}

/**
 * Get template Before Footer ID
 *
 * @since  1.0.0
 * @return String|boolean before footer id if it is set else returns false.
 */
function tmpcoder_get_before_footer_id() {

	$before_footer_id = TMPCODER_Header_Footer_Elements::get_settings( 'type_before_footer', '' );

	if ( '' === $before_footer_id ) {
		$before_footer_id = false;
	}

	return apply_filters( 'tmpcoder_get_before_footer_id', $before_footer_id );
}

/**
 * Checks if Before Footer is enabled.
 *
 * @since  1.0.0
 * @return bool True if before footer is enabled. False if before footer is not enabled.
 */
function tmpcoder_is_before_footer_enabled() {

	$before_footer_id = TMPCODER_Header_Footer_Elements::get_settings( 'type_before_footer', '' );
	$status           = false;

	if ( '' !== $before_footer_id ) {
		$status = true;
	}

	return apply_filters( 'tmpcoder_before_footer_enabled', $status );
}

add_action('wp', function(){
    if ( tmpcoder_404_page_enabled() )
    {

        add_filter('404_template', static function(){
                add_filter(
                    'body_class',
                    static function($classes){
                        if (!in_array('error404', $classes, true)) {
                            $classes[] = 'error404';
                        }    
                        return $classes;
                    }
                );
                $template = TMPCODER_PLUGIN_DIR.'inc/header-footer-helper/templates/404-page.php';//get_template_part('new-404');
                return $template;
            },
            999
        );
    }
});

function tmpcoder_default_page_template() {
    global $post;
    if ( 'theme-advanced-hook' == $post->post_type 
    && 0 != count( get_page_templates( $post ) ) 
        && get_option( 'page_for_posts' ) != $post->ID // Not the page for listing posts
        && '' == $post->page_template // Only when page_template is not set
    ) {

        $post->page_template = "elementor_canvas"; // set template you want
    }
}
add_action('add_meta_boxes', 'tmpcoder_default_page_template', 1);

class TMPCODER_Theme_Layouts_Base {

    /**
	 * Instance of TMPCODER_Theme_Layouts_Base.
	 *
	 * @var TMPCODER_Theme_Layouts_Base
	 */
	private static $instance;

	/**
	** Instance of Elemenntor Frontend class.
	*
	** @var \Elementor\Frontend()
	*/
	private static $elementor_instance;

	/**
	** Get Current Theme.
	*/
	public $current_theme;

	/**
	** Sastra Themes Array.
	*/
	public $sastra_themes;

	/**
	** Constructor
	*/

	public function __construct( $only_hf = false ) {

		// Elementor Frontend
		self::$elementor_instance = \Elementor\Plugin::instance();

		// Ative Theme
		$this->current_theme = get_template();

		// Sastra Themes
		$this->sastra_themes = ['sastrawp','spexo', 'belliza'];  // sastra-elementor

		// Popular Themes
		if ( 'astra' === $this->current_theme ) {
			require_once(TMPCODER_PLUGIN_DIR . 'inc/admin/templates/views/astra/class-astra-compat.php');

		} elseif ( 'generatepress' === $this->current_theme ) {
			require_once(TMPCODER_PLUGIN_DIR . 'inc/admin/templates/views/generatepress/class-generatepress-compat.php');

		} elseif ( 'oceanwp' === $this->current_theme ) {
			require_once(TMPCODER_PLUGIN_DIR . 'inc/admin/templates/views/oceanwp/class-oceanwp-compat.php');

		} elseif ( 'storefront' === $this->current_theme ) {
			require_once(TMPCODER_PLUGIN_DIR . 'inc/admin/templates/views/storefront/class-storefront-compat.php');
		
		// Other Themes
		} else {
			add_action( 'wp', [ $this, 'global_compatibility' ] );
		}

		// Scripts and Styles
		add_action( 'wp_enqueue_scripts', [ $this, 'tmpcoder_enqueue_scripts' ] );

		// Theme Builder
		if ( !$only_hf ) { // Prevent Loading in Header or Footer Templates

			add_filter( 'template_include', [ $this, 'convert_to_canvas' ], 12 ); // 12 after WP Pages and WooCommerce.

			add_action( 'tmpcoder_elementor/page_templates/canvas/tmpcoder_print_content', [ $this, 'tmpcoder_canvas_page_content_display' ] );
		}
	}

	public function convert_to_canvas( $template ) {
    	$is_theme_builder_edit = \Elementor\Plugin::$instance->preview->is_preview_mode() && tmpcoder_is_theme_builder_template() ? true : false;

    	$_wp_page_template = get_post_meta(get_the_ID(), '_wp_page_template', true);

        if ( $this->tmpcoder_is_template_available('content') || $is_theme_builder_edit ) {
    		if ( (is_page() || is_single()) && 'elementor_canvas' === $_wp_page_template && !$is_theme_builder_edit ) {
    			return $template;
    		} else {
                return TMPCODER_PLUGIN_DIR . 'inc/admin/templates/tmpcoder-canvas.php';
    		}
    	} else {
    		return $template;
    	}
    }

	/**
	 *  Initiator
	*/
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new TMPCODER_Theme_Layouts_Base();
		}
		return self::$instance;
	}

	public function global_compatibility() {

		add_action( 'get_header', [ $this, 'tmpcoder_replace_header' ] );
		add_action( 'elementor/page_templates/canvas/before_content', [ $this, 'tmpcoder_add_canvas_header' ] );
		add_action( 'get_footer', [ $this, 'tmpcoder_replace_footer' ] );
		add_action( 'elementor/page_templates/canvas/after_content', [ $this, 'tmpcoder_add_canvas_footer' ], 9 );
	}

	/**
	** Header
	*/
	public function tmpcoder_replace_header() {

		if ( tmpcoder_header_enabled() ) {

			if ( ! in_array($this->current_theme, $this->sastra_themes) ) {
				require TMPCODER_PLUGIN_DIR . 'inc/admin/templates/views/theme-header.php';
			}
			else
			{
				require TMPCODER_PLUGIN_DIR . 'inc/admin/templates/views/spexo/theme-header-spexo.php';
			}
			
			$templates   = [];
			$templates[] = 'header.php';
			
			remove_all_actions( 'wp_head' ); // Avoid running wp_head hooks again.

			ob_start();
			?>
			<?php
			locate_template( $templates, true );
			ob_get_clean();
	    }
	}

	public function tmpcoder_add_canvas_header() {
	
		if ( tmpcoder_header_enabled() ) {
			// Render Tmpcoder Header
			TMPCODER_Header_Footer_Elements::get_header_content();
		}  
    }

	/**
	** Footer
	*/
	public function tmpcoder_replace_footer() {

		if ( tmpcoder_footer_enabled() ) {

			if ( ! in_array($this->current_theme, $this->sastra_themes) ) {

				require TMPCODER_PLUGIN_DIR . 'inc/admin/templates/views/theme-footer.php';
			}
			else
			{
				require TMPCODER_PLUGIN_DIR . 'inc/admin/templates/views/spexo/theme-footer-spexo.php';
			}

			$templates   = [];
			$templates[] = 'footer.php';
			
			remove_all_actions( 'wp_footer' ); // Avoid running wp_footer hooks again.

			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
	    }
	}

	public function tmpcoder_add_canvas_footer() {
  		
  		if ( tmpcoder_footer_enabled() ) {
  			// Render Tmpcoder Footer
			TMPCODER_Header_Footer_Elements::get_footer_content();
		}
    }

	/**
	** Theme Builder Content Display
	*/
	public function tmpcoder_canvas_page_content_display() {

		// Display Template
		$templates = $this->canvas_page_content_display_conditions();
		if ($templates) {
			if (\Elementor\Plugin::$instance->preview->is_preview_mode() && ($templates == 'type_single_post' || $templates == 'type_single_product')) {

				echo "<div class='tmpcoder-before-single-post-content-editor'>";
			}
			TMPCODER_Header_Footer_Elements::get_dynamic_content($templates);
			if (\Elementor\Plugin::$instance->preview->is_preview_mode() && ($templates == 'type_single_post' || $templates == 'type_single_product')) {
				echo "</div>";
			}
		}
	}

    /**
    ** Check if a Template has Conditions
    */
	public function tmpcoder_is_template_available( $type ) {
    	if ( 'content' === $type ) {
			return !is_null($this->canvas_page_content_display_conditions()) ? true : false;
    	} 
	}

	public function canvas_page_content_display_conditions(){
		$template = NULL;
		// Get Conditions
		if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
			$archives = tmpcoder_dynamic_content_enabled('type_product_archive');
			if (!$archives) {
				$archives = tmpcoder_dynamic_content_enabled('type_product_category');
			}
			$singles  = tmpcoder_dynamic_content_enabled('type_single_product');
		} else {
			$archives = tmpcoder_dynamic_content_enabled('type_archive');
			$singles  = tmpcoder_dynamic_content_enabled('type_single_post');
		}

        // if ( empty($archives) && empty($singles) ) {
        //     return NULL;
        // }

        // Reset
        $template = NULL;
            	
		// Archive Pages (includes search)

        if ($archives != NULL) {

            if ( (is_archive() || is_search()) ) {
            	$template = 'type_archive';
            	if ( !is_search() ) {

            		if (is_author() || is_date() || is_category() || is_tag()) {

	                    $template = 'type_archive';              
            		}
            		elseif ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
	                 	
	                 	if (is_product_category()) {
							$template = tmpcoder_dynamic_content_enabled('type_product_category') ? 'type_product_category' : NULL;		                 		
	                 	}
	                 	else
	                 	{
	                    	$template = tmpcoder_dynamic_content_enabled('type_product_archive') ? 'type_product_archive' : NULL;
	                 	}
	                }
            	}
            	else
            	{
        			$template = tmpcoder_dynamic_content_enabled('type_search_result_page') ? 'type_search_result_page' : NULL;
            	}
            }
            elseif(!is_front_page() && is_home()){

            	$template = 'type_archive';
            }
        }
		
			
		if (is_search()) {
			$template = tmpcoder_dynamic_content_enabled('type_search_result_page') ? 'type_search_result_page' : NULL;
		}

        // Single Pages
        if ( $singles != NULL && is_single() && !is_archive()) {
            $template = $this->tmpcoder_single_templates_conditions($singles);
        }	
        
	    return $template;
	}

	public static function get_dynamic_content_id($type) {

        $id = TMPCODER_Header_Footer_Elements::get_settings($type, '' );

        if ( '' === $id ) {
            $id = false;
        }

        return apply_filters( 'tmpcoder_get_'.$type.'_id', $id );
    }

	public function tmpcoder_single_templates_conditions(){
		global $post;

        // Get Posts
        $post_id   = is_null($post) ? '' : $post->ID;
        $post_type = is_null($post) ? '' : $post->post_type;

        // Reset
        $template = NULL;

        // Single Pages
        if ( is_single() || is_page() || is_404() ) {
        	
            if ( is_single() ) {
                // Blog Posts
                if ( 'product' == $post_type ) {
                    $template = 'type_single_product';
                } else {
                    $template = 'type_single_post';
                }
            } else {
                // Front page
                if ( is_front_page() ) {//TODO: is it a good check? - is_blog_archive()
                    $template = NULL;
                // Error 404 Page
                } elseif ( is_404() ) {
                    $template = tmpcoder_dynamic_content_enabled('type_404') ? 'type_404' : NULL;
                // Single Page
                } elseif ( is_page() ) {
                    $template = NULL;
                }
            }
        }
        return $template;
	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function tmpcoder_enqueue_scripts() {

		if ( class_exists( '\Elementor\Plugin' ) ) {
			$elementor = \Elementor\Plugin::instance();
			$elementor->frontend->enqueue_styles();
		}

		// Load Header Template CSS File
		$header_template_id = !empty(tmpcoder_get_header_id()) ? tmpcoder_get_header_id() : false;

		if ( false !== $header_template_id ) {

			// Load Header Template Assets (Elementor Widget)
			if ( ! self::$elementor_instance->preview->is_preview_mode() ) {
				$page_assets = get_post_meta( $header_template_id, Assets::ASSETS_META_KEY, true );
				if ( ! empty( $page_assets ) ) {
					self::$elementor_instance->assets_loader->enable_assets( $page_assets );
				}

				$css_file = Post_CSS::create( get_the_ID() );
				$css_file->enqueue();
			}

			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$header_css_file = new \Elementor\Core\Files\CSS\Post( $header_template_id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$header_css_file = new \Elementor\Post_CSS_File( $header_template_id );
			}
			$header_css_file->enqueue();
		}

		// Load Footer Template CSS File
		$footer_template_id = !empty(tmpcoder_get_footer_id()) ?  tmpcoder_get_footer_id() : false;

		if ( false !== $footer_template_id ) {

			// Load Footer Template Assets (Elementor Widget)
			if ( ! self::$elementor_instance->preview->is_preview_mode() ) {
				$page_assets = get_post_meta( $footer_template_id, Assets::ASSETS_META_KEY, true );
				if ( ! empty( $page_assets ) ) {
					self::$elementor_instance->assets_loader->enable_assets( $page_assets );
				}

				$css_file = Post_CSS::create( get_the_ID() );
				$css_file->enqueue();
			}

			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$footer_css_file = new \Elementor\Core\Files\CSS\Post( $footer_template_id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$footer_css_file = new \Elementor\Post_CSS_File( $footer_template_id );
			}

			$footer_css_file->enqueue();
		}

		// Load Canvas Content Template CSS File
		$canvas_conditions = $this->canvas_page_content_display_conditions();
		$canvas_template_id = !empty($canvas_conditions) ? $this->get_dynamic_content_id($canvas_conditions) : false;

		if ( false !== $canvas_template_id ) {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$canvas_css_file = new \Elementor\Core\Files\CSS\Post( $canvas_template_id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$canvas_css_file = new \Elementor\Post_CSS_File( $canvas_template_id );
			}

			$canvas_css_file->enqueue();
		}
	}
}

TMPCODER_Theme_Layouts_Base::instance();