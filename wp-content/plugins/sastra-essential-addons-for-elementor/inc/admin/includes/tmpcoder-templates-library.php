<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TMPCODER_Templates_Library setup
 *
 * @since 1.0
 */
class TMPCODER_Templates_Library {

	/**
	** Constructor
	*/
	public function __construct() {

		// Templates Library
        require TMPCODER_PLUGIN_DIR . 'inc/admin/includes/tmpcoder-templates-actions.php';
        require TMPCODER_PLUGIN_DIR . 'inc/admin/templates/library/tmpcoder-templates-library-blocks.php';
        require TMPCODER_PLUGIN_DIR . 'inc/admin/templates/library/tmpcoder-templates-library-sections.php';
        require TMPCODER_PLUGIN_DIR . 'inc/admin/templates/library/tmpcoder-templates-library-pages.php';

        add_action( 'current_screen', [ $this, 'tmpcoder_redirect_to_options_page' ] );

		// Template Actions
		new TMPCODER_Templates_Actions();

		// Add Blocks to Library
		new TMPCODER_Templates_Library_Blocks();

		// Add Sections to Library
		new TMPCODER_Templates_Library_Sections();

		// Add Pages to Library
		new TMPCODER_Templates_Library_Pages();

	}

	/**
	** Register Templates Library
	*/
	public function tmpcoder_redirect_to_options_page() {
		if ( get_current_screen()->post_type == TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE && isset($_GET['action']) && $_GET['action'] == 'edit' ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$elementor_template_type = isset($_GET['post']) ? sanitize_text_field(wp_unslash($_GET['post'])) : '';// phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$redirect_url = esc_url_raw( admin_url( 'admin.php?page=spexo-welcome&tab=site-builder' ) );
			wp_safe_redirect( $redirect_url );
			exit;

		}
	}
}

new TMPCODER_Templates_Library();