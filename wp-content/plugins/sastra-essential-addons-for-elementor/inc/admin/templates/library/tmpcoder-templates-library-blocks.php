<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TMPCODER_Templates_Library_Blocks setup
 *
 * @since 1.0
 */

class TMPCODER_Templates_Library_Blocks {

	/**
	** Constructor
	*/
	public function __construct() {
		// Template Library Popup
		add_action( 'wp_ajax_tmpcoder_render_library_templates_blocks', [ $this, 'render_library_templates_blocks' ] );
	}
	
	/**
	** Template Library Popup
	*/
	public static function render_library_templates_blocks() {
		$license = ! tmpcoder_is_availble() ? 'free' : 'premium';

        $blocks_template = [];
        $response = wp_remote_get( TMPCODER_DEMO_IMPORT_API . 'prebuild-block/demo-listing.json', [
            'timeout'   => 60,
            'sslverify' => false,
            'user-agent' => 'templatescoder-user-agent',
            'headers' => array( 'Referer' => site_url() ),
        ]);
		
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $headers = $response['headers']; // array of http header lines
            $req_body = $response['body']; // use the content
            if ( ! isset($req_body['message']) ){
                $blocks_template = json_decode($req_body, true);
            }
        }

        if (is_wp_error( $response )) {
        	echo '<div class="not-found-message">'.esc_html($response->get_error_message()).'</div>';
        	echo '<div class="tmpcoder-prebuilt-site-blog-link"><a target="_blank" href='.esc_url(TMPCODER_CURL_TIMEOUT_DOC_LINK).'>'.esc_html('How to fix it ?').' </a></div>';
        }

		?>

		<div class="tmpcoder-tplib-sidebar" data-license="<?php echo esc_attr($license); ?>">
			<div class="tmpcoder-pre-text">
				<h1><?php esc_html_e('Prebuilt Blocks', 'sastra-essential-addons-for-elementor'); ?></h1>
			    <p>
			        <span><?php esc_html_e('Ready to use blocks to speed up your desiging process..', 'sastra-essential-addons-for-elementor') ?></span>
			    </p>
			</div>
			
			<div class="tmpcoder-tplib-filters-wrap">
				<div class="filter-text">
				    <span>Filter Blocks</span>
				</div>
				<div class="tmpcoder-tplib-filters">
					<h3>
						<span data-filter="all"><?php esc_html_e( 'Category', 'sastra-essential-addons-for-elementor' ); ?></span>
						<i class="fas fa-angle-down"></i>
					</h3>
					<div class="tmpcoder-tplib-filters-list">
						<ul>
							<li data-filter="all"><?php esc_html_e( 'All', 'sastra-essential-addons-for-elementor' ) ?></li>

							<?php
							
							foreach ($blocks_template as $title => $slug) {
                                $catRow = array_values($slug);
                                $catRow2 = isset($catRow[0]['category']) ? $catRow[0]['category'] : $title;
                                
								echo '<li data-filter="'. esc_attr($title) .'">'. esc_html($catRow2) .'</li>';

                                $catRow = array();
							}

							?>
						</ul>
					</div>
				</div>

				<div class="tmpcoder-tplib-sub-filters">
					<ul>
						<li data-sub-filter="all" class="tmpcoder-tplib-activ-filter"><?php esc_html_e( 'All', 'sastra-essential-addons-for-elementor' ); ?></li>
						<li data-sub-filter="grid"><?php esc_html_e( 'Grid', 'sastra-essential-addons-for-elementor' ) ?></li>
						<li data-sub-filter="slider"><?php esc_html_e( 'Slider', 'sastra-essential-addons-for-elementor' ) ?></li>
						<li data-sub-filter="carousel"><?php esc_html_e( 'Carousel', 'sastra-essential-addons-for-elementor' ) ?></li>
					</ul>
				</div>
			</div>
			<!-- <div class="tmpcoder-upgrade-now-button">
		    	<a href="#" target="_blank" class="btn-link"><?php // esc_html_e('How To Use Prebuilt Blocks', 'sastra-essential-addons-for-elementor')?><i class="dashicons dashicons-video-alt3"></i></a>
			</div> -->
			<div class="tmpcoder-tplib-search">
				<input type="text" placeholder="Search Template">
				<i class="eicon-search"></i>
			</div>
		</div>
		
		<div class="tmpcoder-tplib-template-gird elementor-clearfix">
			<div class="tmpcoder-tplib-template-gird-inner">

			<?php
            
            // $blocks_template = [];
            $response = wp_remote_get( TMPCODER_DEMO_IMPORT_API . 'prebuild-block/demo-listing.json', [
                'timeout'   => 60,
                'sslverify' => false,
                'user-agent' => 'templatescoder-user-agent',
                'headers' => array( 'Referer' => site_url() ),
            ]);
            if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                $headers = $response['headers']; // array of http header lines
                $req_body    = $response['body']; // use the content
                if ( ! isset($req_body['message']) ){
                    // $blocks_template = json_decode($req_body, true);
                }
            }

			foreach ($blocks_template as $title => $data ) :
				$module_slug = $title;//$data[0];
				$blocks = $blocks_template;
                $title = str_replace('-', ' ', $module_slug);

				if ( !isset($blocks[$module_slug]) ) {
					continue;
				}

				for ( $i=0; $i < count($blocks[$module_slug]); $i++ ) :

					$template_slug 	= array_keys($blocks[$module_slug])[$i];
					$template_sub 	= isset($blocks[$module_slug][$template_slug]['sub']) ? $blocks[$module_slug][$template_slug]['sub'] : '';
					$template_title = $blocks[$module_slug][$template_slug]['title'] != "" ? $blocks[$module_slug][$template_slug]['title'] : $title .' '. $template_slug;
					$preview_type 	= $blocks[$module_slug][$template_slug]['type'];
					$preview_url 	= $blocks[$module_slug][$template_slug]['url'];

					$template_class = ( substr($template_slug, -4) == '-pro' && !tmpcoder_is_availble() ) ? ' tmpcoder-tplib-pro-wrap' : '';
					// $template_class = ( substr($template_slug, -4) == '-pro' ) ? ' tmpcoder-tplib-pro-wrap' : '';

					if (defined('TMPCODER_ADDONS_PRO_VERSION') && tmpcoder_is_availble()) {
						$template_class .= ' tmpcoder-tplib-pro-active';
					}

					$template_slug_for_image = !empty($blocks[$module_slug][$template_slug]['image']) ? $blocks[$module_slug][$template_slug]['image'] : TMPCODER_ADDONS_ASSETS_URL. 'images/placeholder.png';

					// Add Extra Keywords for Search
					$data_template_title = $template_title;
					if ( false !== strpos($title, 'Form Builder') ) {
						$data_template_title .= ' contact';
					} else if ( false !== strpos($title, 'Nav Menu') ) {
						$data_template_title .= ' header';
					} else if ( false !== strpos($title, 'Post Grid') ) {
						$data_template_title .= ' blog';
					}

					?>
					<div class="tmpcoder-tplib-template-wrap<?php echo esc_attr($template_class); ?>" data-title="<?php echo esc_attr(strtolower($data_template_title)); ?>">
						<div class="tmpcoder-tplib-template" data-slug="<?php echo esc_attr($template_slug); ?>" data-filter="<?php echo esc_attr($module_slug); ?>" data-sub-filter="<?php echo esc_attr($template_sub); ?>" data-preview-type="<?php echo esc_attr($preview_type); ?>" data-preview-url="<?php echo esc_attr($preview_url); ?>">
							<div class="tmpcoder-tplib-template-media">

								<img class="tmpcoder-lazyload-image" src="<?php echo esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/lazy-loader.gif'); ?>" data-src="<?php echo esc_url($template_slug_for_image); ?>" data-lazy-load />
								
								<div class="tmpcoder-tplib-template-media-overlay">
									<i class="eicon-eye"></i>
								</div>
							</div>
							<div class="tmpcoder-tplib-template-footer elementor-clearfix">
								<?php if ( !defined('TMPCODER_ADDONS_PRO_VERSION') && ! tmpcoder_is_availble() ) : ?>
									<h3><?php echo substr($template_slug, -4) == '-pro' ? esc_html(str_replace('-pro', ' Pro', $template_title)) : esc_html($template_title); ?></h3>
								<?php else : ?>
									<h3><?php echo substr($template_slug, -4) == '-pro' ? esc_html(str_replace('-pro', '', $template_title)) : esc_html($template_title); ?></h3>
								<?php endif; ?>

								<?php if ( substr($template_slug, -4) == '-pro' && !tmpcoder_is_availble()  ) : ?>
									<span class="tmpcoder-tplib-insert-template tmpcoder-tplib-insert-pro"><i class="eicon-flash"></i> <span><?php esc_html_e( 'Go Pro', 'sastra-essential-addons-for-elementor' ); ?></span></span>
								<?php else : ?>
									<span class="tmpcoder-tplib-insert-template"><i class="eicon-file-download"></i> <span><?php esc_html_e( 'Insert', 'sastra-essential-addons-for-elementor' ); ?></span></span>
								<?php endif; ?>
							</div>
						</div>
					</div>

				<?php endfor; ?>
			<?php endforeach;?>

			</div>
		</div>


		<?php

		$current_screen = get_current_screen();

		if ( !(isset($current_screen) && 'sastra-addon_page_tmpcoder-prebuild-blocks' === $current_screen->id) ) {
			exit;
		}
	}

}
