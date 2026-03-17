<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Add Theme Global Colors In Elementor Global Color List Start */

if (!function_exists('tmpcoder_get_palette_labels')) {

	function tmpcoder_get_palette_labels() {
		return array(
			__( 'Primary Color', 'sastra-essential-addons-for-elementor' ),
			__( 'Secondary Color', 'sastra-essential-addons-for-elementor' ),
			__( 'Accent Color', 'sastra-essential-addons-for-elementor' ),
			__( 'Theme Color 1', 'sastra-essential-addons-for-elementor' ),
			__( 'Theme Color 2', 'sastra-essential-addons-for-elementor' ),
			__( 'Theme Color 3', 'sastra-essential-addons-for-elementor' ),
			__( 'Theme Color 4', 'sastra-essential-addons-for-elementor' ),
			__( 'Body Background', 'sastra-essential-addons-for-elementor' ),
			__( 'Link Color', 'sastra-essential-addons-for-elementor' ),
			__( 'Link Hover Color', 'sastra-essential-addons-for-elementor' ),
			__( 'Global Border Color', 'sastra-essential-addons-for-elementor' ),
			__( 'Site Fonts', 'sastra-essential-addons-for-elementor' ),
			__( 'Site Button Font Color', 'sastra-essential-addons-for-elementor' ),
			__( 'Site Button Font Hover Color', 'sastra-essential-addons-for-elementor' ),
			__( 'Site Button Background Color', 'sastra-essential-addons-for-elementor' ),
			__( 'Site Button Background Hover Color', 'sastra-essential-addons-for-elementor' ),
			__( 'Button Border', 'sastra-essential-addons-for-elementor' ),
			__( 'Button Border Hover', 'sastra-essential-addons-for-elementor' ),
		);
	}
}

if (!function_exists('tmpcoder_get_palette_slugs')) {

	function tmpcoder_get_palette_slugs() {
		return array(
			'tmpcoder-primary-color',
			'tmpcoder-secondary-color',
			'tmpcoder-accent-color',
			'tmpcoder-body-background-color',
			'tmpcoder-theme-color-1',
			'tmpcoder-theme-color-2',
			'tmpcoder-theme-color-3',
			'tmpcoder-theme-color-4',
			'tmpcoder-link-color',
			'tmpcoder-link-hover-color',
			'tmpcoder-global-border-color',
			'tmpcoder-site-fonts-color',
			'tmpcoder-button-fonts-color',
			'tmpcoder-button-fonts-hover-color',
			'tmpcoder-button-background-color',
			'tmpcoder-button-background-hover-color',
			'tmpcoder-button-border-color',
			'tmpcoder-button-border-hover-color',
		);
	}
}

if (!function_exists('tmpcoder_get_theme_global_colors')) {
	
	function tmpcoder_get_theme_global_colors(){

		$global_palette = [];
		$data = get_option(TMPCODER_THEME_OPTION_NAME);
        if ( empty($data) ){
            $data = get_option('tmpcoder_global_theme_options_sastrawp');
        }

		if (!empty($data)) {

			$global_palette['palette'] = 
			[
				'0' => isset($data['primany_color']) ? $data['primany_color']:'',
				'1' => isset($data['secondary_color']) ? $data['secondary_color']:'',
				'2' => isset($data['accent_color']) ? $data['accent_color']:'',
				'3' => isset($data['theme_color_1']) ? $data['theme_color_1'] : '',
				'4' => isset($data['theme_color_2'])?$data['theme_color_2']:'',
				'5' => $data['theme_color_3'],
				'6' => isset($data['theme_color_4']['rgba']) ? $data['theme_color_4']['rgba'] : '#fff',
				'7' => isset($data['site_background_color']['background-color']) ? $data['site_background_color']['background-color'] : '',
				'8' => isset($data['link_color']) ? $data['link_color'] : '',
				'9' => isset($data['link_hover_color']) ? $data['link_hover_color'] : '',
				'10' => isset($data['global_border_color']) ? $data['global_border_color']:'',
				'11' => isset($data['site_fonts_options']['color']) ? $data['site_fonts_options']['color']:'',
				'12' => isset($data['button_style']['color']) ? $data['button_style']['color']:'',
				'13' => isset($data['site_button_text_hover']) ? $data['site_button_text_hover'] : '',
				'14' => isset($data['site_button_color']) ? $data['site_button_color']:'',
				'15' => isset($data['site_button_color_hover']) ? $data['site_button_color_hover'] : '',
				'16' => isset($data['button_border']['border-color']) ? $data['button_border']['border-color'] : '',
				'17' => isset($data['button_border_hover']['border-color']) ? $data['button_border_hover']['border-color'] : '',
			];
		}

		return $global_palette;
	}
}

if (!function_exists('tmpcoder_get_css_variable_prefix')) {
	
	function tmpcoder_get_css_variable_prefix() {
		return '--tmpcoder-color-';
	}
}

add_action( 'rest_request_after_callbacks', 'tmpcoder_elementor_add_theme_colors', 999, 3 );
add_filter( 'rest_request_after_callbacks', 'tmpcoder_display_global_colors_front_end', 999, 3 );
add_action( 'wp_head', 'tmpcoder_generate_global_elementor_style', 1 );

if (!function_exists('tmpcoder_elementor_add_theme_colors')) {
	
	function tmpcoder_elementor_add_theme_colors( $response, $handler, $request ) {

		$route = $request->get_route();

		if ( '/elementor/v1/globals' != $route ) {
			return $response;
		}

		$global_palette = tmpcoder_get_theme_global_colors();
		$data           = $response->get_data();
		$slugs          = tmpcoder_get_palette_slugs();
		$labels         = tmpcoder_get_palette_labels();

		if (isset($global_palette['palette'])) {
			$colors = array();
			foreach ( $global_palette['palette'] as $key => $color ) {

				$slug = $slugs[ $key ];
				// Remove hyphens from slug.
				$no_hyphens = str_replace( '-', '', $slug );

				$colors[ $no_hyphens ] = array(
					'id'    => esc_attr( $no_hyphens ),
					'title' => $labels[ $key ],
					'value' => $color,
				);
			}
            $precolors = $data['colors'];
            $data['colors'] = array_merge($colors, $precolors);
		}

		$response->set_data( $data );
		return $response;
	}
}

if (!function_exists('tmpcoder_display_global_colors_front_end')) {
	
	function tmpcoder_display_global_colors_front_end( $response, $handler, $request ) {

		$route = $request->get_route();

		if ( 0 !== strpos( $route, '/elementor/v1/globals' ) ) {
			return $response;
		}

		$slug_map      = array();
		$palette_slugs = tmpcoder_get_palette_slugs();

		foreach ( $palette_slugs as $key => $slug ) {
			// Remove hyphens as hyphens do not work with Elementor global styles.
			$no_hyphens              = str_replace( '-', '', $slug );
			$slug_map[ $no_hyphens ] = $key;
		}

		$rest_id = substr( $route, strrpos( $route, '/' ) + 1 );

		if ( ! in_array( $rest_id, array_keys( $slug_map ), true ) ) {
			return $response;
		}

		$colors = [];
		$colors = tmpcoder_get_theme_global_colors();
		$response = rest_ensure_response(
			array(
				'id'    => esc_attr( $rest_id ),
				'title' => tmpcoder_get_css_variable_prefix() . esc_html( $slug_map[ $rest_id ] ),
				'value' => (isset($colors['palette']) ? $colors['palette'][ $slug_map[ $rest_id ] ] : ''),
			)
		);
		return $response;	
	}
}

if (!function_exists('tmpcoder_generate_global_elementor_style')) {
	
	function tmpcoder_generate_global_elementor_style( $dynamic_css ) {

		$global_palette = tmpcoder_get_theme_global_colors();
		$palette_style  = array();
		$slugs          = tmpcoder_get_palette_slugs();
		$style          = array();

		if ( isset( $global_palette['palette'] ) ) {
			foreach ( $global_palette['palette'] as $color_index => $color ) {
				$variable_key           = '--e-global-color-' . str_replace( '-', '', $slugs[ $color_index ] );
				$style[ $variable_key ] = $color;
			}

			$palette_style[':root'] = $style;
			$dynamic_css           .= tmpcoder_parse_css($palette_style);
		}

        wp_register_style( 'tmpcoder-elementor-globle-variables', false );
		wp_enqueue_style( 'tmpcoder-elementor-globle-variables' );
		wp_add_inline_style( 'tmpcoder-elementor-globle-variables', $dynamic_css );
	}
}

if (!function_exists('tmpcoder_parse_css')) {
	
	function tmpcoder_parse_css( $css_output = array(), $min_media = '', $max_media = '' ) {

		$parse_css = '';
		if ( is_array( $css_output ) && count( $css_output ) > 0 ) {

			foreach ( $css_output as $selector => $properties ) {

				if ( null === $properties ) {
					break;
				}

				if ( ! count( $properties ) ) {
					continue;
				}

				$temp_parse_css   = $selector . '{';
				$properties_added = 0;

				foreach ( $properties as $property => $value ) {

					if ( '' == $value && 0 !== $value ) {
						continue;
					}

					$properties_added++;
					$temp_parse_css .= $property . ':' . $value . ';';
				}

				$temp_parse_css .= '}';

				if ( $properties_added > 0 ) {
					$parse_css .= $temp_parse_css;
				}
			}

			if ( '' != $parse_css && ( '' !== $min_media || '' !== $max_media ) ) {

				$media_css       = '@media ';
				$min_media_css   = '';
				$max_media_css   = '';
				$media_separator = '';

				if ( '' !== $min_media ) {
					$min_media_css = '(min-width:' . $min_media . 'px)';
				}
				if ( '' !== $max_media ) {
					$max_media_css = '(max-width:' . $max_media . 'px)';
				}
				if ( '' !== $min_media && '' !== $max_media ) {
					$media_separator = ' and ';
				}

				$media_css .= $min_media_css . $media_separator . $max_media_css . '{' . $parse_css . '}';

				return $media_css;
			}
		}

		return $parse_css;
	}
}

/* Add Theme Global Colors In Elementor Global Color List End */