<?php
namespace Spexo_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class TMPCODER_Admin_Bar {

	public static function init() {
		add_action( 'admin_bar_menu', [__CLASS__, 'add_toolbar_items'], 500 );
		add_action( 'wp_enqueue_scripts', [__CLASS__, 'enqueue_assets'] );
		add_action( 'admin_enqueue_scripts', [__CLASS__, 'enqueue_assets'], 10 );
		// Fallback: Ensure inline script is printed on welcome page using admin_head
		// This specifically targets the prebuilt-blocks tab issue
		add_action( 'admin_head', [__CLASS__, 'ensure_welcome_page_inline_script'], 999 );
		// Register AJAX handler for both admin and frontend (wp_ajax_ works for logged-in users on both)
		add_action( 'wp_ajax_tmpcoder_clear_cache', [__CLASS__, 'clear_cache' ] );
	}
	
	/**
	 * Fallback method to ensure inline script is printed on welcome page
	 * This specifically targets the prebuilt-blocks tab issue where footer scripts may not print
	 * 
	 * Root Cause: The welcome screen page (admin.php?page=spexo-welcome) uses a custom rendering
	 * method that includes admin-header.php but may not properly print footer scripts. This fallback
	 * ensures the clear-cache functionality works by printing the script directly in admin_head.
	 */
	public static function ensure_welcome_page_inline_script() {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		
		// Check if we're on the spexo-welcome page
		$screen = get_current_screen();
		$is_welcome_page = false;
		
		if ( $screen && 'toplevel_page_spexo-welcome' === $screen->id ) {
			$is_welcome_page = true;
		} elseif ( isset( $_GET['page'] ) && 'spexo-welcome' === sanitize_key( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$is_welcome_page = true;
		}
		
		if ( $is_welcome_page ) {
			// Ensure jQuery is loaded (required dependency)
			wp_enqueue_script( 'jquery' );
			
			// Get the localized data
			$localized_data = [
				'nonce'    => wp_create_nonce( 'tmpcoder_clear_cache' ),
				'_wpnonce' => wp_create_nonce( 'tmpcoder_feedback_nonce' ),
				'_wpnonce_' => wp_create_nonce( 'tmpcoder-plugin-notice-js' ),
				'post_id'  => get_queried_object_id(),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			];
			
			// Get the inline script
			$inline_script = self::get_clear_cache_inline_script();
			
			// Build the localized data script
			$localized_script = '/* <![CDATA[ */' . "\n";
			$localized_script .= 'if (typeof SpexoAdmin === "undefined") {' . "\n";
			$localized_script .= '	var SpexoAdmin = ' . wp_json_encode( $localized_data ) . ';' . "\n";
			$localized_script .= '}' . "\n";
			$localized_script .= '/* ]]> */';
			
			// Print localized data and inline script directly in head
			// This ensures it loads even if footer scripts aren't printing properly
			// Use wp_print_inline_script_tag() for WordPress 5.7+, fallback for older versions
			if ( function_exists( 'wp_print_inline_script_tag' ) ) {
				// WordPress 5.7+ - use the standard function which handles escaping properly
				wp_print_inline_script_tag( $localized_script );
				wp_print_inline_script_tag( $inline_script );
			} else {
				// Fallback for older WordPress versions (pre-5.7)
				// The script content is controlled by us and contains only safe JavaScript code
				// Direct output is safe here because:
				// 1. The script is generated internally by our code (get_clear_cache_inline_script method)
				// 2. All user data is properly escaped via wp_json_encode() in $localized_data
				// 3. The JavaScript code itself is static and controlled, no user input
				// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
				// The $localized_script uses wp_json_encode() which properly escapes all data
				echo '<script type="text/javascript">' . "\n";
				echo $localized_script . "\n";
				echo '</script>' . "\n";
				// The $inline_script is static JavaScript code generated internally, no user input
				echo '<script type="text/javascript">' . "\n";
				echo $inline_script . "\n";
				echo '</script>' . "\n";
				// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
	
	/**
	 * Get the clear cache inline script content
	 * 
	 * @return string The inline script content
	 */
	private static function get_clear_cache_inline_script() {
		return "
		(function($) {
			'use strict';
			
			$(document).ready(function() {
				var \$clearCache = $('.tmpcoderjs-clear-cache');
				var \$tmpcoderMenu = $('#toplevel_page_spexo-addons .toplevel_page_spexo-addons .wp-menu-name');
				var menuText = \$tmpcoderMenu.text();
				\$tmpcoderMenu.text(menuText.replace(/\\s/, ''));
				
				\$clearCache.on('click', 'a', function(e) {
					e.preventDefault();
					var type = 'all';
					var \$m = $(e.delegateTarget);
					
					if (\$m.hasClass('tmpcoder-clear-page-cache')) {
						type = 'page';
					}
					
					\$m.addClass('tmpcoder-clear-cache--init');
					
					if (\$clearCache.hasClass('tools-btn')) {
						$('.welcome-backend-loader').fadeIn();
						$('.tmpcoder-theme-welcome').css('opacity', '0.5');
					}
					
					$.post(SpexoAdmin.ajax_url, {
						action: 'tmpcoder_clear_cache',
						type: type,
						nonce: SpexoAdmin.nonce,
						post_id: SpexoAdmin.post_id
					}).done(function(res) {
						\$m.removeClass('tmpcoder-clear-cache--init').addClass('tmpcoder-clear-cache--done');
						
						if (\$clearCache.hasClass('tools-btn')) {
							$('.welcome-backend-loader').fadeOut();
							$('.tmpcoder-theme-welcome').css('opacity', '1');
							$('.tmpcoder-settings-saved').stop().fadeIn(500).delay(1000).fadeOut(1000);
						} else {
							var \$targetContainer = $('#wpbody').length ? $('#wpbody') : $('body');
							\$targetContainer.append('<div class=\"tmpcoder-css-regenerated tmpcoder-settings-saved\"><span>Assets Regenerated</span><span class=\"dashicons dashicons-smiley\"></span></div>');
							
							$('.tmpcoder-css-regenerated').css({
								position: 'fixed',
								zIndex: '99999',
								top: '60px',
								right: '30px',
								padding: '15px 25px',
								borderRadius: '3px',
								color: '#fff',
								background: '#562ad5',
								boxShadow: '0 2px 10px 3px rgba(0, 0, 0, .2)',
								textTransform: 'uppercase',
								fontWeight: '600',
								letterSpacing: '1px'
							});
							
							$('.tmpcoder-css-regenerated').stop().fadeIn(500).delay(1000).fadeOut(1000);
						}
					}).fail(function() {
						\$m.removeClass('tmpcoder-clear-cache--init');
						if (\$clearCache.hasClass('tools-btn')) {
							$('.welcome-backend-loader').fadeOut();
							$('.tmpcoder-theme-welcome').css('opacity', '1');
						}
					});
				});
			});
		})(jQuery);
		";
	}

	public static function clear_cache() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! check_ajax_referer( 'tmpcoder_clear_cache', 'nonce' ) ) {
			wp_send_json_error();
		}

		$type = '';
		if (isset($_POST['type'])) {
			$type = sanitize_text_field(wp_unslash($_POST['type']));	
		}

		$post_id = isset( $_POST['post_id'] ) ? absint($_POST['post_id']) : 0;
		$assets_cache = new Assets_Cache( $post_id );
		if ( $type === 'page' ) {
			$assets_cache->delete();
		} elseif ( $type === 'all' ) {
			$assets_cache->delete_all();
			if (tmpcoder_is_elementor_editor()) {
				\Elementor\Plugin::$instance->files_manager->clear_cache();
		 	} 
		}
		
		wp_send_json_success();
	}

	public static function enqueue_assets( $hook = '' ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$custom_css = '#wp-admin-bar-spexo-addons > .ab-item > img {
		    margin-top: -4px;
		    width: 18px;
		    height: 18px;
		    vertical-align: text-bottom;
		    display: inline-block;
		}

		.tmpcoder-admin-bar-image {
			width: 10px !important;
		    height: 10px !important;
		    display: inline-block;
		    margin-right: 5px !important;
		    // filter: brightness(0);
    		// opacity: 0.6;
		}

		#wp-admin-bar-spexo-addons .ab-item .dashicons {
		    position: relative;
		    top: 7px;
		    display: inline-block;
		    font-weight: normal;
		    font-style: normal;
		    font-variant: normal;
		    font-size: inherit;
		    font-family: dashicons;
		    line-height: 1;

		    -webkit-font-smoothing: antialiased;
		    -moz-osx-font-smoothing: grayscale;
		    text-rendering: auto;
		}

		#wp-admin-bar-spexo-addons .ab-item .dashicons-update-alt:before {
		    content: "\f113";
		}

		#wp-admin-bar-spexo-addons .tmpcoder-clear-cache--done .ab-item > i {
		    color: #46b450;
		}

		#wp-admin-bar-spexo-addons .tmpcoder-clear-cache--init .ab-item > i {
		    -webkit-animation: tmpcoder-inifinite-rotate .5s infinite linear;
		            animation: tmpcoder-inifinite-rotate .5s infinite linear;
		}

		@-webkit-keyframes tmpcoder-inifinite-rotate {
		    from {
		        -webkit-transform: rotate(0deg);
		                transform: rotate(0deg);
		    }
		    to {
		        -webkit-transform: rotate(359deg);
		                transform: rotate(359deg);
		    }
		}

		@keyframes tmpcoder-inifinite-rotate {
		    from {
		        -webkit-transform: rotate(0deg);
		                transform: rotate(0deg);
		    }
		    to {
		        -webkit-transform: rotate(359deg);
		                transform: rotate(359deg);
		    }
		}';

		wp_register_style( 'tmpcoder-admin-bar-cach', false );
		wp_enqueue_style( 'tmpcoder-admin-bar-cach' );
		wp_add_inline_style( 'tmpcoder-admin-bar-cach', $custom_css );

		// Register and enqueue jQuery first (dependency)
		wp_enqueue_script( 'jquery' );
		
		// Register a minimal script handle for inline script attachment
		// This ensures the inline script is properly attached and works everywhere
		$inline_script_handle = 'spexo-elementor-addons-clear-cache';
		wp_register_script( $inline_script_handle, false, ['jquery'], TMPCODER_PLUGIN_VER, true );
		wp_enqueue_script( $inline_script_handle );
		
		// Localize script data for clear cache functionality
		wp_localize_script(
			$inline_script_handle,
			'SpexoAdmin',
			[
				'nonce'    => wp_create_nonce( 'tmpcoder_clear_cache' ),
				'_wpnonce' => wp_create_nonce( 'tmpcoder_feedback_nonce' ),
				'_wpnonce_' => wp_create_nonce( 'tmpcoder-plugin-notice-js' ),
				'post_id'  => get_queried_object_id(),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			]
		);
		
		// Add inline script for clear cache AJAX functionality
		// This ensures it works on all admin pages and frontend
		$clear_cache_inline_script = "
		(function($) {
			'use strict';
			
			$(document).ready(function() {
				var \$clearCache = $('.tmpcoderjs-clear-cache');
				var \$tmpcoderMenu = $('#toplevel_page_spexo-addons .toplevel_page_spexo-addons .wp-menu-name');
				var menuText = \$tmpcoderMenu.text();
				\$tmpcoderMenu.text(menuText.replace(/\\s/, ''));
				
				\$clearCache.on('click', 'a', function(e) {
					e.preventDefault();
					var type = 'all';
					var \$m = $(e.delegateTarget);
					
					if (\$m.hasClass('tmpcoder-clear-page-cache')) {
						type = 'page';
					}
					
					\$m.addClass('tmpcoder-clear-cache--init');
					
					if (\$clearCache.hasClass('tools-btn')) {
						$('.welcome-backend-loader').fadeIn();
						$('.tmpcoder-theme-welcome').css('opacity', '0.5');
					}
					
					$.post(SpexoAdmin.ajax_url, {
						action: 'tmpcoder_clear_cache',
						type: type,
						nonce: SpexoAdmin.nonce,
						post_id: SpexoAdmin.post_id
					}).done(function(res) {
						\$m.removeClass('tmpcoder-clear-cache--init').addClass('tmpcoder-clear-cache--done');
						
						if (\$clearCache.hasClass('tools-btn')) {
							$('.welcome-backend-loader').fadeOut();
							$('.tmpcoder-theme-welcome').css('opacity', '1');
							$('.tmpcoder-settings-saved').stop().fadeIn(500).delay(1000).fadeOut(1000);
						} else {
							var \$targetContainer = $('#wpbody').length ? $('#wpbody') : $('body');
							\$targetContainer.append('<div class=\"tmpcoder-css-regenerated tmpcoder-settings-saved\"><span>Assets Regenerated</span><span class=\"dashicons dashicons-smiley\"></span></div>');
							
							$('.tmpcoder-css-regenerated').css({
								position: 'fixed',
								zIndex: '99999',
								top: '60px',
								right: '30px',
								padding: '15px 25px',
								borderRadius: '3px',
								color: '#fff',
								background: '#562ad5',
								boxShadow: '0 2px 10px 3px rgba(0, 0, 0, .2)',
								textTransform: 'uppercase',
								fontWeight: '600',
								letterSpacing: '1px'
							});
							
							$('.tmpcoder-css-regenerated').stop().fadeIn(500).delay(1000).fadeOut(1000);
						}
					}).fail(function() {
						\$m.removeClass('tmpcoder-clear-cache--init');
						if (\$clearCache.hasClass('tools-btn')) {
							$('.welcome-backend-loader').fadeOut();
							$('.tmpcoder-theme-welcome').css('opacity', '1');
						}
					});
				});
			});
		})(jQuery);
		";
		
		wp_add_inline_script( $inline_script_handle, $clear_cache_inline_script );
		
		// Enqueue the main admin.js file for other functionality (deactivation popup, rating notices, etc.)
		wp_enqueue_script(
			'spexo-elementor-addons-admin',
			TMPCODER_PLUGIN_URI . 'assets/js/admin/admin'.tmpcoder_script_suffix().'.js',
			['jquery', $inline_script_handle],
			TMPCODER_PLUGIN_VER,
			true
		);
		
		// Also localize the main script with the same data for other AJAX calls
		wp_localize_script(
			'spexo-elementor-addons-admin',
			'SpexoAdmin',
			[
				'nonce'    => wp_create_nonce( 'tmpcoder_clear_cache' ),
				'_wpnonce' => wp_create_nonce( 'tmpcoder_feedback_nonce' ),
				'_wpnonce_' => wp_create_nonce( 'tmpcoder-plugin-notice-js' ),
				'post_id'  => get_queried_object_id(),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			]
		);
	}

	public static function add_toolbar_items( \WP_Admin_Bar $admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$icon = '<i class="dashicons dashicons-update-alt"></i> ';
		$websites_icon = sprintf( '<img class="tmpcoder-admin-bar-image" src="%s">', TMPCODER_ADDONS_ASSETS_URL .'images/prebuilt-websites-admin-bar.svg' );
		$blocks_icon = sprintf( '<img class="tmpcoder-admin-bar-image" src="%s">', TMPCODER_ADDONS_ASSETS_URL .'images/prebuilt-block-admin-bar.svg' ); 
		$builder_icon = sprintf( '<img class="tmpcoder-admin-bar-image" src="%s">', TMPCODER_ADDONS_ASSETS_URL .'images/site-builder-admin-bar.svg' ); 

		$admin_bar->add_menu( [
			'id'    => 'spexo-addons',
			'title' => sprintf( '<img src="%s"> Spexo Addons', TMPCODER_ADDONS_ASSETS_URL .'images/logo-40x40.svg' ),
			'href'  => tmpcoder_get_dashboard_link(),
			'meta'  => [
				'title' => __( 'Spexo Addons', 'sastra-essential-addons-for-elementor' ),
			]
		] );

		$admin_bar->add_menu( [
			'id'     => 'tmpcoder-clear-all-cache',
			'parent' => 'spexo-addons',
			'title'  => $icon . __( 'Regenerate Cache', 'sastra-essential-addons-for-elementor' ),
			'href'   => '#',
			'meta'   => [
				'class' => 'tmpcoderjs-clear-cache tmpcoder-clear-all-cache',
			]
		] );

		if ( in_array(get_template(), array('sastrawp', 'spexo') ) ) {

			$admin_bar->add_menu( [
				'id'     => 'tmpcoder-prebuilt-websites',
				'parent' => 'spexo-addons',
				'title'  => $websites_icon . __( 'Prebuilt Websites', 'sastra-essential-addons-for-elementor' ),
				'href'   => admin_url('admin.php?page=tmpcoder-import-demo'),
				'meta'   => [
					'class' => '',
				]
			] );
		}

		$admin_bar->add_menu( [
			'id'     => 'tmpcoder-prebuilt-blocks',
			'parent' => 'spexo-addons',
			'title'  => $blocks_icon . __( 'Prebuilt Blocks', 'sastra-essential-addons-for-elementor' ),
			'href'   => admin_url('admin.php?page=spexo-welcome&tab=prebuilt-blocks'),
			'meta'   => [
				'class' => '',
			]
		] );

		$admin_bar->add_menu( [
			'id'     => 'tmpcoder-site-builder',
			'parent' => 'spexo-addons',
			'title'  => $builder_icon . __( 'Site Builder', 'sastra-essential-addons-for-elementor' ),
			'href'   => admin_url('admin.php?page=spexo-welcome&tab=site-builder'),
			'meta'   => [
				'class' => '',
			]
		] );
	}
}

TMPCODER_Admin_Bar::init();