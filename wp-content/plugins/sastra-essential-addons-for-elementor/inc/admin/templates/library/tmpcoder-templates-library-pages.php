<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TMPCODER_Templates_Library_Pages setup
 *
 * @since 1.0
 */
class TMPCODER_Templates_Library_Pages {

	/**
	** Constructor
	*/
	public function __construct() {

		// Template Library Popup
		add_action( 'wp_ajax_tmpcoder_render_library_templates_pages', [ $this, 'render_library_templates_pages' ] );
		add_action( 'wp_ajax_tmpcoder_render_library_templates_pages_grid_items', [ $this, 'render_library_templates_pages_grid_items' ] );

	}

	/**
	** Template Library Popup
	*/
	public static function render_library_templates_pages() {
		$license = ! tmpcoder_is_availble() ? 'free' : 'premium';

		?>

		<div class="tmpcoder-tplib-sidebar" data-license="<?php echo esc_attr($license); ?>">
			<div class="tmpcoder-tplib-price" data-filter="mixed">
				<h3>
					<span><?php esc_html_e( 'Mixed', 'sastra-essential-addons-for-elementor' ); ?></span>
					<i class="fas fa-angle-down"></i>
				</h3>
				
				<div class="tmpcoder-tplib-price-list">
					<ul>
						<li data-filter="mixed"><?php esc_html_e( 'Mixed', 'sastra-essential-addons-for-elementor' ); ?></li>
						<li data-filter="free"><?php esc_html_e( 'Free', 'sastra-essential-addons-for-elementor' ); ?></li>
						<li data-filter="pro"><?php esc_html_e( 'Premium', 'sastra-essential-addons-for-elementor' ); ?></li>
					</ul>
				</div>
			</div>
			<div class="tmpcoder-tplib-search">
				<input type="text" placeholder="Search Template">
				<i class="eicon-search"></i>
			</div>
		</div>

		<div class="tmpcoder-tplib-template-gird elementor-clearfix">
			<div class="tmpcoder-tplib-template-gird-inner">


			</div>

		<div class="tmpcoder-tplib-template-gird-loading">
			<div class="tmpcoder-wave">
				<div class="tmpcoder-rect tmpcoder-rect1"></div>
				<div class="tmpcoder-rect tmpcoder-rect2"></div>
				<div class="tmpcoder-rect tmpcoder-rect3"></div>
				<div class="tmpcoder-rect tmpcoder-rect4"></div>
				<div class="tmpcoder-rect tmpcoder-rect5"></div>
			</div>
		</div>
		
		<?php

		wp_die();
	}

	/**
	** Template Library Grid Items
	*/
	public static function render_library_templates_pages_grid_items() {

        if ( !isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'tmpcoder-library-frontend-js')  || !current_user_can( 'manage_options' ) ) {
            exit; // Get out of here, the nonce is rotten!
        }

        $blocks_template = [];
        $response = wp_remote_get( TMPCODER_DEMO_IMPORT_API . 'template-kit/page-listing.json', [
            'timeout'   => 60,
            'sslverify' => false,
            'user-agent' => 'templatescoder-user-agent',
            'headers' => array( 'Referer' => site_url() ),
        ]);
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $headers = $response['headers']; // array of http header lines
            $req_body    = $response['body']; // use the content
            if ( ! isset($req_body['message']) ){
                $blocks_template = json_decode($req_body, true);
            }
        }

		$kits = $blocks_template;

		$page = isset($_POST['page']) ? absint($_POST['page']) : 1;
		$kits_per_page = 10; // should be set to 10
		
		$start = ($page - 1) * $kits_per_page;
		$end = $start + $kits_per_page;
		
		$kits = array_slice($kits, $start, $kits_per_page, true);
		
		foreach( $kits as $kit => $data ) :
			
			foreach( $data['pages'] as $key => $page ) :

				$template_title = $data['name'] .' - '. str_replace('-', ' ', ucwords($page));
				$template_price = ('pro' === $data['price'] && tmpcoder_is_availble()) ? 'pro' : 'free';
				$template_class = 'pro' === $template_price ? ' tmpcoder-tplib-pro-wrap' : '';
				$preview_url = $kit . '/' . $page; // $data['preview'][$key]

                $template_image = !empty($data['preview'][$key]) ? $data['preview'][$key] : TMPCODER_ADDONS_ASSETS_URL. 'images/placeholder.png';

		?>

			<div class="tmpcoder-tplib-template-wrap<?php echo esc_attr($template_class); ?>" style="position:absolute;top:9999999999px;" data-title="<?php echo esc_attr(strtolower($template_title)); ?>" data-price="<?php echo esc_attr($template_price); ?>">
				<div class="tmpcoder-tplib-template" data-slug="<?php echo esc_attr($page); ?>" data-kit="<?php echo esc_attr($kit); ?>" data-preview-type="iframe" data-preview-url="<?php echo esc_attr($preview_url); ?>">
					<div class="tmpcoder-tplib-template-media">
						<img class="tmpcoder-lazyload-image" src="<?php echo esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/lazy-loader.gif'); ?>" data-src="<?php echo esc_url($template_image); ?>" data-lazy-load>
						<div class="tmpcoder-tplib-template-media-overlay">
							<i class="eicon-eye"></i>
						</div>
					</div>
					<div class="tmpcoder-tplib-template-footer elementor-clearfix">
						<h3>
							<span><?php echo esc_html($data['name']) ?></span>
							<span><?php echo '&nbsp;- '. esc_html(ucwords($page)); ?></span>
						</h3>

						<?php if ( 'pro' === $data['price'] && !tmpcoder_is_availble() ) : ?>
							<span class="tmpcoder-tplib-insert-template tmpcoder-tplib-insert-pro"><i class="eicon-flash"></i> <span><?php esc_html_e( 'Go Pro', 'sastra-essential-addons-for-elementor' ); ?></span></span>
						<?php else : ?>
							<span class="tmpcoder-tplib-insert-template"><i class="eicon-file-download"></i> <span><?php esc_html_e( 'Insert', 'sastra-essential-addons-for-elementor' ); ?></span></span>
						<?php endif; ?>
					</div>
				</div>
			</div>

		<?php endforeach;

		endforeach;

		wp_die();
	}
}
