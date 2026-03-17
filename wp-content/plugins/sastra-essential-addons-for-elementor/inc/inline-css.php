<?php

if (! defined('ABSPATH')) exit; // Exit if accessed directly

add_action('wp_enqueue_scripts', 'tmpcoder_add_dynamic_styles', 9999);
add_action('wp_enqueue_scripts', 'tmpcoder_dequeue_elementor_global__css', 9999);

if (!function_exists('tmpcoder_add_dynamic_styles')) {

	function tmpcoder_add_dynamic_styles()
	{

		$viewport_lg = 1025;
		$viewport_md = 768;

		if (class_exists('Elementor\Plugin')) {

			$elementor_active_kit = get_option('elementor_active_kit');

			$raw_kit_settings = get_post_meta($elementor_active_kit, '_elementor_page_settings', true);

			if ($raw_kit_settings) {

				if (isset($raw_kit_settings['viewport_mobile'])) {
					$viewport_md = $raw_kit_settings['viewport_mobile'];
				} else {
					if (isset($raw_kit_settings['viewport_md'])) {
						$viewport_md = $raw_kit_settings['viewport_md'];
					}
				}

				if (isset($raw_kit_settings['viewport_tablet'])) {
					$viewport_lg = $raw_kit_settings['viewport_tablet'];
				} else {
					if (isset($raw_kit_settings['viewport_lg'])) {
						$viewport_lg = $raw_kit_settings['viewport_lg'];
					}
				}
			}
		}

		$inline_css = '

		body {
			margin: 0;
			font-family: var(--theme-font-family)!important;
			font-size: var(--theme-font-size)!important;
			line-height: var(--theme-line-height)!important;
			letter-spacing: var(--theme-letter-spacing);
			font-weight: var(--theme-font-weight) !important;
			color: var(--theme-text-color);
			background-color: var(--theme-background-color) !important;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
			overflow-x: hidden !important;
		}

		h1, .entry-content h1, h1.elementor-heading-title {
			font-size: var(--heading1-font-size);
			font-family: var(--heading1-font-family);
			font-weight: var(--heading1-font-weight)!important;
			color: var(--heading1-text-color);
			line-height: var(--heading1-line-height);
			text-transform: var(--heading1-text-transform); 
			letter-spacing: var(--heading1-letter-spacing); 
		}

		h2, .entry-content h2, h2.elementor-heading-title {
			font-size: var(--heading2-font-size); 
			font-family: var(--heading2-font-family);
			font-weight: var(--heading2-font-weight);
			color: var(--heading2-text-color);
			line-height: var(--heading2-line-height);
			text-transform: var(--heading2-text-transform); 
			letter-spacing: var(--heading2-letter-spacing); 
		}
		h3, .entry-content h3, h3.elementor-heading-title {
			font-size: var(--heading3-font-size); 
			font-family: var(--heading3-font-family);
			font-weight: var(--heading3-font-weight);
			color: var(--heading3-text-color);
			line-height: var(--heading3-line-height);
			text-transform: var(--heading3-text-transform); 
			letter-spacing: var(--heading3-letter-spacing); 
		}
		h4, .entry-content h4, h4.elementor-heading-title {
			font-size: var(--heading4-font-size); 
			font-family: var(--heading4-font-family);
			font-weight: var(--heading4-font-weight);
			color: var(--heading4-text-color);
			line-height: var(--heading4-line-height);
			text-transform: var(--heading4-text-transform); 
			letter-spacing: var(--heading4-letter-spacing); 
		}
		h5, .entry-content h5, h5.elementor-heading-title {
			font-size: var(--heading5-font-size); 
			font-family: var(--heading5-font-family);
			font-weight: var(--heading5-font-weight);
			color: var(--heading5-text-color);
			line-height: var(--heading5-line-height);
			text-transform: var(--heading5-text-transform); 
			letter-spacing: var(--heading5-letter-spacing); 
		}
		h6, .entry-content h6, h6.elementor-heading-title {
			font-size: var(--heading6-font-size); 
			font-family: var(--heading6-font-family);
			font-weight: var(--heading6-font-weight);
			color: var(--heading6-text-color);
			line-height: var(--heading6-line-height);
			text-transform: var(--heading6-text-transform); 
			letter-spacing: var(--heading6-letter-spacing); 
		}
		.elementor-button,
		.elementor-widget-button .elementor-button,
		.elementor-widget-tmpcoder-button .tmpcoder-button,
		.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-a,
		.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-b {
			color:var(--button-text-color); 
			background-color:var(--theme-button-background);  
			border-color:var(--button-border-color);
			border-style:var(--button-border-type);
			border-top-width: var(--button-border-top);
			border-right-width:var(--button-border-right);
			border-bottom-width:var(--button-border-bottom);
			border-left-width:var(--button-border-left);
			font-family:var(--button-font-family); 
			font-weight:var(--button-font-weight); 
			text-align:var(--button-text-align); 
			text-transform:var(--button-text-transform); 
			font-size:var(--button-font-size); 
			line-height:var(--button-line-height); 
			letter-spacing:var(--button-letter-spacing); 
			border-radius:var(--button-border-radius); 
			padding-top:var(--button-padding-top);
			padding-right:var(--button-padding-right);
			padding-left:var(--button-padding-left);
			padding-bottom:var(--button-padding-bottom);
		}
		.elementor-widget-tmpcoder-button .tmpcoder-button:after
		{
			padding-top:var(--button-padding-top);
			padding-right:var(--button-padding-right);
			padding-left:var(--button-padding-left);
			padding-bottom:var(--button-padding-bottom);
		}
		.elementor-widget-tmpcoder-button .tmpcoder-button .tmpcoder-button-icon{
			fill:var(--button-text-color);
		}
		.elementor .elementor-widget-tmpcoder-button .tmpcoder-button-text,
		.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-a .tmpcoder-button-text-a,
		.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-b .tmpcoder-button-text-b {
			font-size: unset;
			font-weight: unset;
		}
		.elementor-widget-tmpcoder-button .tmpcoder-button-none:hover,
		.elementor-widget-tmpcoder-button [class*="elementor-animation"]:hover,
		.elementor-widget-tmpcoder-button .tmpcoder-button::before,
		.elementor-widget-tmpcoder-button .tmpcoder-button::after,
		.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-none:hover,
		.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button [class*="elementor-animation"]:hover,
		.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-effect::before,
		.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-effect::after {
			background-color: unset;
		}
		.elementor-button:hover,
		.elementor-widget-button .elementor-button:hover,
		.elementor-widget-tmpcoder-button .tmpcoder-button:hover {
			background-color:var(--theme-button-background-hover);  
			border-color:var(--button-border-hover-color);
			color: var(--button-text-hover-color);
			border-style:var(--button-border-hover-type);
			border-top-width: var(--button-border-hover-top);
			border-right-width:var(--button-border-hover-right);
			border-bottom-width:var(--button-border-hover-bottom);
			border-left-width:var(--button-border-hover-left);
		}
		.elementor-widget-tmpcoder-button .tmpcoder-button:hover:after{

		}
		.elementor-widget-tmpcoder-button .tmpcoder-button:hover .tmpcoder-button-icon{
			fill:var(--button-text-hover-color);
		}

		@media (max-width: ' . $viewport_lg . 'px) {
			body {
				font-size: var(--theme-font-size-tablet);
				line-height: var(--theme-line-height-tablet);
				letter-spacing: var(--theme-letter-spacing-tablet)
			}
			h1,.entry-content h1, h1.elementor-heading-title {
				font-size: var(--heading1-font-size-tablet);
				line-height: var(--heading1-line-height-tablet);
				letter-spacing: var(--heading1-letter-spacing-tablet); 
			}
			h2,.entry-content h2, h2.elementor-heading-title {
				font-size: var(--heading2-font-size-tablet);
				line-height: var(--heading2-line-height-tablet);
				letter-spacing: var(--heading2-letter-spacing-tablet); 
			}
			h3,.entry-content h3, h3.elementor-heading-title {
				font-size: var(--heading3-font-size-tablet);
				line-height: var(--heading3-line-height-tablet);
				letter-spacing: var(--heading3-letter-spacing-tablet); 
			}
			h4,.entry-content h4, h4.elementor-heading-title {
				font-size: var(--heading4-font-size-tablet);
				line-height: var(--heading4-line-height-tablet);
				letter-spacing: var(--heading4-letter-spacing-tablet); 
			}
			h5,.entry-content h5, h5.elementor-heading-title {
				font-size: var(--heading5-font-size-tablet);
				line-height: var(--heading5-line-height-tablet);
				letter-spacing: var(--heading5-letter-spacing-tablet); 
			}
			h6,.entry-content h6, h6.elementor-heading-title {
				font-size: var(--heading6-font-size-tablet);
				line-height: var(--heading6-line-height-tablet);
				letter-spacing: var(--heading6-letter-spacing-tablet); 
			}
			button,
			.elementor-button,
			.elementor-widget-button .elementor-button,
			.elementor-widget-tmpcoder-button .tmpcoder-button,
			.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-a,
			.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-b { 
				font-size:var(--button-font-size-tablet); 
				line-height:var(--button-line-height-tablet); 
				letter-spacing:var(--button-letter-spacing-tablet); 
				padding-top:var(--button-tablet-padding-top);
				padding-right:var(--button-tablet-padding-right);
				padding-left:var(--button-tablet-padding-left);
				padding-bottom:var(--button-tablet-padding-bottom);
			  }
			  .tmpcoder-navigation-menu__align-tablet-center nav ul {
				display: grid;
				text-align: center;
				align-items: center;
				justify-content: center!important;
			}
			.tmpcoder-navigation-menu__align-tablet-right nav ul {
				display: grid;
				text-align: right;
				align-items: right;
				justify-content: right!important;
			}
			.tmpcoder-navigation-menu__align-tablet-left nav ul {
				display: grid;
				text-align: left;
				align-items: left;
				justify-content: left!important;
			}
		}

		@media (max-width: ' . $viewport_md . 'px) {
			body {
				font-size: var(--theme-font-size-mobile);
				line-height: var(--theme-line-height-mobile);
				letter-spacing: var(--theme-letter-spacing-mobile)
			}
			h1,.entry-content h1, h1.elementor-heading-title {
				font-size: var(--heading1-font-size-mobile);
				line-height: var(--heading1-line-height-mobile);
				letter-spacing: var(--heading1-letter-spacing-mobile); 
			}
			h2,.entry-content h2, h2.elementor-heading-title {
				font-size: var(--heading2-font-size-mobile);
				line-height: var(--heading2-line-height-mobile);
				letter-spacing: var(--heading2-letter-spacing-mobile); 
			}
			h3,.entry-content h3, h3.elementor-heading-title {
				font-size: var(--heading3-font-size-mobile);
				line-height: var(--heading3-line-height-mobile);
				letter-spacing: var(--heading3-letter-spacing-mobile); 
			}
			h4,.entry-content h4, h4.elementor-heading-title {
				font-size: var(--heading4-font-size-mobile);
				line-height: var(--heading4-line-height-mobile);
				letter-spacing: var(--heading4-letter-spacing-mobile); 
			}
			h5,.entry-content h5, h5.elementor-heading-title {
				font-size: var(--heading5-font-size-mobile);
				line-height: var(--heading5-line-height-mobile);
				letter-spacing: var(--heading5-letter-spacing-mobile); 
			}
			h6,.entry-content h6, h6.elementor-heading-title {
				font-size: var(--heading6-font-size-mobile);
				line-height: var(--heading6-line-height-mobile);
				letter-spacing: var(--heading6-letter-spacing-mobile); 
			}
			button,
			.elementor-button,
			.elementor-widget-button .elementor-button,
			.elementor-widget-tmpcoder-button .tmpcoder-button,
			.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-a,
			.elementor-widget-tmpcoder-dual-button .tmpcoder-dual-button .tmpcoder-button-b { 
				font-size:var(--button-font-size-mobile); 
				line-height:var(--button-line-height-mobile); 
				letter-spacing:var(--button-letter-spacing-mobile); 
				padding-top:var(--button-mobile-padding-top);
				padding-right:var(--button-mobile-padding-right);
				padding-left:var(--button-mobile-padding-left);
				padding-bottom:var(--button-mobile-padding-bottom);
			}
			.tmpcoder-navigation-menu__align-mobile-center nav ul {
				display: grid;
				text-align: center;
				align-items: center;
				justify-content: center!important;
			}
			.tmpcoder-navigation-menu__align-mobile-right nav ul {
				display: grid;
				text-align: right;
				align-items: right;
				justify-content: right!important;
			}
			.tmpcoder-navigation-menu__align-mobile-left nav ul {
				display: grid;
				text-align: left;
				align-items: left;
				justify-content: left!important;
			}
		}
		';

		if (false === tmpcoder_is_elementor_kit_button_color_set()) {
			$inline_css .= '.wp-block-button .wp-block-button__link, .elementor-button,
			.elementor-widget-button .elementor-button,
			.elementor-button:visited,
			.elementor-widget-button .elementor-button:visited {color:var(--button-text-color);fill:var(--button-text-color);}';
			$inline_css .= ',.elementor-button:hover{color:var(--button-text-hover-color);fill:var(--button-text-hover-color);}';
		} else {
			$inline_css .= '.wp-block-button .wp-block-button__link, .elementor-button, .elementor-widget-button .elementor-button{color:var(--button-text-color);fill:var(--button-text-color);}';
		}

		wp_register_style('tmpcoder-frontend-global-options', false);
		wp_enqueue_style('tmpcoder-frontend-global-options');
		wp_add_inline_style('tmpcoder-frontend-global-options', tmpcoder_trim_css($inline_css));
	}

	function tmpcoder_dequeue_elementor_global__css()
	{

		/* Unregister elementor global css */

		wp_dequeue_style('elementor-global');
		wp_deregister_style('elementor-global');

		// Stopped because the pattern design was not proper.
		// wp_dequeue_style( 'global-styles' );
		// wp_deregister_style( 'global-styles' );
	}

	function tmpcoder_trim_css($css = '')
	{

		// Trim white space for faster page loading.
		if (! empty($css)) {
			$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
			$css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
			$css = str_replace(', ', ',', $css);
		}

		return $css;
	}

	function tmpcoder_is_elementor_kit_button_color_set()
	{
		$ele_btn_global_text_color = false;
		$ele_kit_id                = get_option('elementor_active_kit', false);
		if (false !== $ele_kit_id) {
			$ele_global_btn_data = get_post_meta($ele_kit_id, '_elementor_page_settings');
			// Elementor Global theme style button text color fetch value from database.
			$ele_btn_global_text_color = isset($ele_global_btn_data[0]['button_text_color']) ? $ele_global_btn_data[0]['button_text_color'] : $ele_btn_global_text_color;
		}
		return $ele_btn_global_text_color;
	}
}
