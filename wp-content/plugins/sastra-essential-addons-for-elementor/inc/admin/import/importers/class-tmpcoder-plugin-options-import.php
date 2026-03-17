<?php
/**
 * Customizer Site options importer class.
 *
 * @since  1.0.0
 * @package Spexo Addons for Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Site options importer class.
 *
 * @since  1.0.0
 */
class TMPCODER_Options_Import {

	/**
	 * Instance of TMPCODER_Options_Importer
	 *
	 * @since  1.0.0
	 * @var (Object) TMPCODER_Options_Importer
	 */
	private static $instance = null;

	/**
	 * Instanciate TMPCODER_Options_Importer
	 *
	 * @since  1.0.0
	 * @return (Object) TMPCODER_Options_Importer
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Site Options
	 *
	 * @since 1.0.0
	 *
	 * @return array    List of defined array.
	 */
	private static function site_options() {
		return array(
			'custom_logo',
			'nav_menu_locations',
			'show_on_front',
			'page_on_front',
			'page_for_posts',

			// Plugin: Elementor.
			'elementor_container_width',
			'elementor_cpt_support',
			'elementor_css_print_method',
			'elementor_default_generic_fonts',
			'elementor_disable_color_schemes',
			'elementor_disable_typography_schemes',
			'elementor_editor_break_lines',
			'elementor_exclude_user_roles',
			'elementor_global_image_lightbox',
			'elementor_page_title_selector',
			'elementor_scheme_color',
			'elementor_scheme_color-picker',
			'elementor_scheme_typography',
			'elementor_space_between_widgets',
			'elementor_stretched_section_container',
			'elementor_load_fa4_shim',
			'elementor_active_kit',
			'elementor_experiment-e_optimized_css_loading',
		);
	}

	/**
	 * Import site options.
	 *
	 * @since 1.0.0    Updated option if exist in defined option array 'site_options()'.
	 *
	 * @since  1.0.0
	 *
	 * @param  (Array) $options Array of site options to be imported from the demo.
	 */
	public function import_options( $options = array() ) {

		if ( ! isset( $options ) ) {
			return;
		}

        // Import Site Settings - Variable Define
        $site_settings = array();

		foreach ( $options as $option_name => $option_value ) {

			// Is option exist in defined array site_options()?
			if ( null !== $option_value ) {
				
				// Is option exist in defined array site_options()?
				if ( in_array( $option_name, self::site_options(), true ) ) {

					switch ( $option_name ) {

						case 'page_for_posts':
						case 'page_on_front':
								$this->update_page_id_by_option_value( $option_name, $option_value );
							break;

						// nav menu locations.
						case 'nav_menu_locations':
								$this->set_nav_menu_locations( $option_value );
							break;

						// insert logo.
						case 'custom_logo':
								$this->insert_logo( $option_value );
							break;

						case 'elementor_active_kit':
							if ( '' !== $option_value ) {
								$this->set_elementor_kit();
							}
							break;

						default:
							update_option( $option_name, $option_value );
							break;
					}
				}
			}

            // Get Import Site Settings
            if ( isset($option_value['settings']) ){
                if ( isset($option_value['settings']['system_colors']) ){
                    $site_settings = $option_value['settings'];
                }
            }
		}

        // Change Import Site Settings
        if ( !empty($site_settings) ){
            $elementor_active_kit_id = get_option('elementor_active_kit');
            if ( $elementor_active_kit_id != "" ){
                update_post_meta($elementor_active_kit_id, '_elementor_page_settings', $site_settings);
            }
        }
	}

	/**
	 * Update post option
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_elementor_kit() {

		// Update Elementor Theme Kit Option.
		$args = array(
			'post_type'   => 'elementor_library',
			'post_status' => 'publish',
			'numberposts' => 1,
			'meta_query'  => array(
				array(
					'key'   => '_tmpcoder_imported_post',
					'value' => '1',
				),
				array(
					'key'   => '_elementor_template_type',
					'value' => 'kit',
				),
			),
		);

		$query = get_posts( $args );
		if ( ! empty( $query ) && isset( $query[0] ) && isset( $query[0]->ID ) ) {
			update_option( 'elementor_active_kit', $query[0]->ID );
		}
	}

    private function tmpcoder_get_page_by_title( $page_title ) {
        $query = new WP_Query(
            array(
                'post_type'              => 'page',
                'title'                  => $page_title,
                'post_status'            => 'all',
                'posts_per_page'         => 1,
                'no_found_rows'          => true,
                'ignore_sticky_posts'    => true,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
                'orderby'                => 'post_date ID',
                'order'                  => 'ASC',
            )
        );
         
        if ( ! empty( $query->post ) ) {
            $page_got_by_title = $query->post;
        } else {
            $page_got_by_title = null;
        }
        return $page_got_by_title;
    }

	/**
	 * Update post option
	 *
	 * @since 1.0.0
	 *
	 * @param  string $option_name  Option name.
	 * @param  mixed  $option_value Option value.
	 * @return void
	 */
	private function update_page_id_by_option_value( $option_name, $option_value ) {
		if ( empty( $option_value ) ) {
			return;
		}

		$page = $this->tmpcoder_get_page_by_title( $option_value );
		if ( is_object( $page ) ) {
			update_option( $option_name, $page->ID );
		}
	}

	/**
	 * In WP nav menu is stored as ( 'menu_location' => 'menu_id' );
	 * In export we send 'menu_slug' like ( 'menu_location' => 'menu_slug' );
	 * In import we set 'menu_id' from menu slug like ( 'menu_location' => 'menu_id' );
	 *
	 * @since 1.0.0
	 * @param array $nav_menu_locations Array of nav menu locations.
	 */
	private function set_nav_menu_locations( $nav_menu_locations = array() ) {

		$menu_locations = array();

		// Update menu locations.
		if ( isset( $nav_menu_locations ) ) {

			foreach ( $nav_menu_locations as $menu => $value ) {

				$term = get_term_by( 'slug', $value, 'nav_menu' );

				if ( is_object( $term ) ) {
					$menu_locations[ $menu ] = $term->term_id;
				}
			}

			set_theme_mod( 'nav_menu_locations', $menu_locations );
		}
	}

	/**
	 * Insert Logo By URL
	 *
	 * @since 1.0.0
	 * @param  string $image_url Logo URL.
	 * @return void
	 */
	private function insert_logo( $image_url = '' ) {

		$downloaded_image = TMPCODER_Image_Importer::get_instance()->import(
			array(
				'url' => $image_url,
				'id'  => 0,
			)
		);

		if ( $downloaded_image['id'] ) {
			TMPCODER_Importer::instance()->track_post( $downloaded_image['id'] );
			set_theme_mod( 'custom_logo', $downloaded_image['id'] );
		}

	}

}
