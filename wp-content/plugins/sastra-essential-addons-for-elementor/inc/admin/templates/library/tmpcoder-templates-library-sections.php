<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * TMPCODER_Templates_Library_Sections setup
 *
 * @since 1.0
 */
class TMPCODER_Templates_Library_Sections {

	/**
	** Constructor
	*/
	public function __construct() {

		// Template Library Popup
		add_action( 'wp_ajax_tmpcoder_render_library_templates_sections', [ $this, 'render_library_templates_sections' ] );

	}

	/**
	** Template Library Popup
	*/
	public static function render_library_templates_sections() {
		$license = !tmpcoder_is_availble() ? 'free' : 'premium';

        $blocks_template = [];
        $response = wp_remote_get( TMPCODER_DEMO_IMPORT_API . 'prebuild-section/demo-listing.json', [
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

		?>

		<div class="tmpcoder-tplib-sidebar" data-license="<?php echo esc_attr($license); ?>">
			<div class="tmpcoder-tplib-filters-wrap">
				<div class="tmpcoder-tplib-filters">
					<h3>
						<span data-filter="all"><?php esc_html_e( 'Category', 'sastra-essential-addons-for-elementor' ); ?></span>
						<i class="fas fa-angle-down"></i>
					</h3>

					<div class="tmpcoder-tplib-filters-list">
						<ul>

							<li data-filter="all"><?php esc_html_e( 'All', 'sastra-essential-addons-for-elementor' ) ?></li>

							<?php

							foreach ($blocks_template as $title => $data) {
                                $catRow = array_values($data);
                                $catRow2 = isset($catRow[0]['category']) ? $catRow[0]['category'] : $title;
                                
								echo '<li data-filter="'. esc_attr($title) .'">'. esc_html(ucwords($catRow2)) .'</li>';

                                $catRow = array();
							}

							?>
						</ul>
					</div>
				</div>
			</div>
			<div class="tmpcoder-tplib-search">
				<input type="text" placeholder="Search Template">
				<i class="eicon-search"></i>
			</div>
		</div>

		<div class="tmpcoder-tplib-template-gird tmpcoder-tplib-sections-grid elementor-clearfix">
			<div class="tmpcoder-tplib-template-gird-inner">

			<?php

			foreach ($blocks_template as $title => $data) :
                $slug = self::create_slug($title);

				for ( $i=0; $i < count($data); $i++ ) :

                    $template_slug 	= array_keys($data)[$i];
                    $template_title = $data[$template_slug]['title'] != "" ? $data[$template_slug]['title'] : $title .' '. $template_slug;
                    $template_slug_for_image = !empty($data[$template_slug]['image']) ? $data[$template_slug]['image'] : TMPCODER_ADDONS_ASSETS_URL. 'images/placeholder.png';

					// $template_slug 	 = $slug .'-'. $data[$i];
					$template_class  = substr($template_slug, -4) == '-pro' && !tmpcoder_is_availble() ? ' tmpcoder-tplib-pro-wrap' : '';
					// $template_class  = substr($template_slug, -4) == '-pro' ? ' tmpcoder-tplib-pro-wrap' : '';
					$template_class .= strpos($template_slug, 'woo') && !class_exists( 'WooCommerce' ) ? ' tmpcoder-tplib-woo-wrap' : '';

					if (defined('TMPCODER_ADDONS_PRO_VERSION') && tmpcoder_is_availble()) {
						$template_class .= ' tmpcoder-tplib-pro-active';
					}
					?>

					<div class="tmpcoder-tplib-template-wrap<?php echo esc_attr($template_class); ?>" data-title="<?php echo esc_attr(strtolower($title)); ?>">
						<div class="tmpcoder-tplib-template" data-slug="<?php echo esc_attr($template_slug); ?>" data-filter="<?php echo esc_attr($slug); ?>" data-preview-type="image">
							<div class="tmpcoder-tplib-template-media">
								<img class="tmpcoder-lazyload-image" src="<?php echo esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/lazy-loader.gif'); ?>" data-src="<?php echo esc_url($template_slug_for_image); ?>">
								<div class="tmpcoder-tplib-template-media-overlay">
									<i class="eicon-eye"></i>
								</div>
							</div>
							<div class="tmpcoder-tplib-template-footer elementor-clearfix">
								<?php $title_v = $template_title;//$title .' '. esc_html($data[$i]);?>
								<?php if ( !defined('TMPCODER_ADDONS_PRO_VERSION') && ! tmpcoder_is_availble() ) : ?>
									<h3><?php echo substr($template_slug, -4) == '-pro' ? esc_html(str_replace('-pro', ' Pro', $title_v)) : esc_html(str_replace('-zzz', ' Pro', $title_v)); ?></h3>
								<?php else : ?>
									<h3><?php echo substr($template_slug, -4) == '-pro' ? esc_html(str_replace('-pro', '', $title_v)) : esc_html(str_replace('-zzz', '', $title_v)); ?></h3>
								<?php endif; ?>

								<?php if ( substr($template_slug, -4) == '-pro' && !tmpcoder_is_availble() ) : ?>
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

		if ( !(isset($current_screen) && 'sastra-addon_page_tmpcoder-prebuild-sections' === $current_screen->id) ) {
			exit;
		}
	}

    public static function create_slug($str, $delimiter = '-'){

        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    
    } 

}
