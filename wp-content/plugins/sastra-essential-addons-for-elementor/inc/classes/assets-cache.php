<?php
namespace Spexo_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Assets_Cache {

	const FILE_PREFIX = 'tmpcoder-';

	const BASE_DIR = 'spexo-addons';

	const CSS_DIR = 'css';

	protected static $is_common_loaded = false;

	/**
	 * @var int
	 */
	protected $post_id = 0;

	/**
	 * @var Widgets_Cache
	 */
	protected $widgets_cache = null;

	protected $upload_path;

	protected $upload_url;

	public function __construct( $post_id = 0, Widgets_Cache $widget_cache_instance = null ) {
		$this->post_id = $post_id;
		$this->widgets_cache = $widget_cache_instance;

		$upload_dir = wp_upload_dir();
		$this->upload_path = trailingslashit( $upload_dir['basedir'] );
		$this->upload_url = trailingslashit( $upload_dir['baseurl'] );
		// Mixed content issue overcome when using ssl
		$this->upload_url = ( is_ssl()? str_replace( 'http://', 'https://', $this->upload_url ): $this->upload_url );
	}

	public function get_widgets_cache() {
		if ( is_null( $this->widgets_cache ) ) {
			$this->widgets_cache = new Widgets_Cache( $this->get_post_id() );
		}
		return $this->widgets_cache;
	}

	public function get_cache_dir_name() {
		return trailingslashit( self::BASE_DIR ) . trailingslashit( self::CSS_DIR );
	}

	public function get_post_id() {
		return $this->post_id;
	}

	public function get_cache_dir() {
		return wp_normalize_path( $this->upload_path . $this->get_cache_dir_name() );
	}

	public function get_cache_url() {
		return $this->upload_url . $this->get_cache_dir_name();
	}

	public function get_file_name() {
		return $this->get_cache_dir() . self::FILE_PREFIX . "{$this->get_post_id()}.css";
	}

	public function get_file_url() {
		return $this->get_cache_url() . self::FILE_PREFIX . "{$this->get_post_id()}.css";
	}

	public function cache_exists() {
		return file_exists( $this->get_file_name() );
	}

	public function has() {
		if ( ! $this->cache_exists() ) {
			$this->save();
		}
		return $this->cache_exists();
	}

	public function delete() {
		if ( $this->cache_exists() ) {
			wp_delete_file( $this->get_file_name() );
		}
	}

	public function delete_all() {
		$files = glob( $this->get_cache_dir() . '*' );
		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				wp_delete_file( $file );
			}
		}
	}

	public function enqueue() {

		if ( $this->has() ) {
			wp_enqueue_style(
				'spexo-elementor-addons-' . $this->get_post_id(),
				$this->get_file_url(),
				[ 'elementor-frontend' ],
				TMPCODER_PLUGIN_VER . '.' . get_post_modified_time()
			);
		}
	}

	public function save() {
		$widgets           = $this->get_widgets_cache()->get();
		// $widgets_map       = Widgets_Manager::get_widgets_map();
		$widgets_map       = tmpcoder_get_all_widgtes();
		$widgets_processed = [];
		$css               = '';


		foreach ( $widgets as $widget_key ) {

			if ( isset( $widgets_processed[ $widget_key ] ) )
			{
				continue;
			}

			$is_pro = '';
			$css   .= $this->get_css( $widget_key, $is_pro );
			$widgets_processed[ $widget_key ] = true;
		}

		if ( empty( $css ) ) {
			return;
		}

		$css .= sprintf( '/** Widgets: %s **/', implode( ', ', array_keys( $widgets_processed ) ) );

		if ( ! is_dir( $this->get_cache_dir() ) ) {
			wp_mkdir_p( $this->get_cache_dir() );
		}

		global $wp_filesystem;

        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        if ( WP_Filesystem() ) {
            $wp_filesystem->put_contents( $this->get_file_name(), $css, FS_CHMOD_FILE ); 
        }
	}

	protected function get_css( $files_name, $is_pro = false ) {
		$css = '';

		if ($files_name == 'post-grid' || $files_name == 'media-grid' || $files_name == 'woo-grid' || $files_name == 'woo-category-grid-pro' || $files_name == 'category-grid-pro' || $files_name == 'magazine-grid' ) {
			$files_name = 'grid-widgets';
		}if ($files_name == 'nav-menu' || $files_name == 'mega-menu' ) {
			$files_name = 'menu';
		}if ($files_name == 'archive-list' || $files_name == 'taxonomy-list' ) {
			$files_name = 'taxonomy-list';
		}if ($files_name == 'my-account-pro' ) {
			$files_name = 'my-account-page-pro';
		}if ($files_name == 'page-checkout' ) {
			$files_name = 'page-checkout-pro';
		}if ($files_name == 'product-notice' ) {
			$files_name = 'product-notice-pro';
		}

		$files_name = $files_name.tmpcoder_script_suffix(); 

		$file_path = TMPCODER_PLUGIN_DIR."assets/css/widgets/{$files_name}.css";
		$file_url  = TMPCODER_PLUGIN_URI."assets/css/widgets/{$files_name}.css";

		if ( file_exists($file_path) && is_readable( $file_path ) ) {
			$css .= file_get_contents($file_path);
		};

		return $css;
	}
}