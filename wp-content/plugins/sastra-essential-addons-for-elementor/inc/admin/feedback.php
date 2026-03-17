<?php

namespace SpexoAddons\Admin\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TMPCODER_Feedback
 */

class TMPCODER_Feedback {

	/**
	 * Class instance
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 * Constructor for the class
	 */
	private function __construct() {

		add_action( 'admin_footer-plugins.php', array( $this, 'tmpcoder_create_popup' ) );

		add_action( 'wp_ajax_tmpcoder_handle_feedback_action', array( $this, 'tmpcoder_send' ) );
	}


	public static function tmpcoder_send() {

		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'tmpcoder_feedback_nonce' ) ) {
		    wp_send_json_error( 'Invalid request.' );
		}

		$response = array( 'success' => false );

		if ( isset( $_POST['data'] ) && is_array($_POST['data'])) {
	        // Sanitize each element of the plugins array
	        $data = array_map('sanitize_text_field',wp_unslash($_POST['data']));
	    }

		$reason      = isset( $data['feedback'] ) ? sanitize_text_field( $data['feedback'] ) : '';
		$suggestions = isset( $data['suggestions'] ) ? sanitize_textarea_field( $data['suggestions'] ) : '';
		$anonymous   = isset( $data['anonymous'] ) ? (bool) $data['anonymous'] : false;


		if ( ! is_string( $reason ) || empty( $reason ) ) {
			return false;
		}

		$wordpress = self::collect_wordpress_data( true );

		if ( ! empty( $suggestions ) ) {
			$wordpress['deactivated_plugin']['uninstall_details'] .= $suggestions;
		}

		if ( ! $anonymous ) {
			$wordpress['deactivated_plugin']['uninstall_details'] .= ( empty( $wordpress['deactivated_plugin']['uninstall_details'] ) ? '' : PHP_EOL . PHP_EOL ) . 'Domain: ' . self::get_site_domain();
		}

		$body = array(
			'uninstall_reason'  => $reason,
			'uninstall_details' => $suggestions,
			'extra_info'		=> $wordpress,
			'is_pro'			=> isset($_POST['is_pro']) ? sanitize_text_field(wp_unslash( $_POST['is_pro'] )) : ''
		);

		$req_params = array(
            'action'    => 'save_user_feedback',
            'theme'     => TMPCODER_CURRENT_THEME_NAME,
            'version'   => TMPCODER_CURRENT_THEME_VERSION,
            'plugin'   => 'sastra-essential-addons-for-elementor',
            'plugin_version'   => (defined('TMPCODER_PLUGIN_VER') ? TMPCODER_PLUGIN_VER : ''),
        );

		$api_url = TMPCODER_UPDATES_URL;

		$response = wp_safe_remote_request(
        	add_query_arg($req_params,$api_url),
			array(
				'headers'     => array(
					// 'Content-Type' => 'application/json',
					'Referer' => site_url()
				),
				'body'        => wp_json_encode( $body ),
				'timeout'     => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),
				'method'      => 'POST',
				'httpversion' => '1.1',
				'user-agent' => 'templatescoder-user-agent',
			)
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->get_error_message() );
		}

		if ( ! isset( $response['response'] ) || ! is_array( $response['response'] ) ) {
			wp_send_json_error( 'REQUEST UNKNOWN' );
		}

		if ( ! isset( $response['body'] ) ) {
			wp_send_json_error( 'REQUEST PAYLOAD EMPTY' );
		}

		wp_send_json_success( ( $response['body'] ) );
	}

	/**
	 * Method generates Feedback popup
	 */
	public function tmpcoder_create_popup() {

		$plugin_data = get_plugin_data( TMPCODER_PLUGIN_FILE );

		?>
			<div class="tmpcoder-deactivation-popup hidden" data-type="wrapper" data-slug="<?php echo esc_attr($plugin_data['TextDomain']); ?>">
				<div class="overlay">
					<div class="close"><i class="dashicons dashicons-no"></i></div>
					<div class="body">
						<section class="title-wrap">
							<div class="tmpcoder-img-wrap">
								<img src="<?php echo esc_url( TMPCODER_ADDONS_ASSETS_URL.'images/spexo-logo-web.svg' ); ?>">
							</div>
						</section>
						<section class="messages-wrap">
							<p><?php echo esc_html(__( 'Would you quickly give us your reason for doing so?', 'sastra-essential-addons-for-elementor' )); ?></p>
						</section>
						<section class="options-wrap">
							<label>
								<input type="radio" checked="" name="feedback" value="Temporary deactivation">
								<?php echo esc_html( __( 'Temporary deactivation', 'sastra-essential-addons-for-elementor' ) ); ?>
							</label>
							<label>
								<input type="radio" name="feedback" value="Set up is too difficult">
								<?php echo esc_html( __( 'Set up is too difficult', 'sastra-essential-addons-for-elementor' ) ); ?>
							</label>
							<label>
								<input type="radio" name="feedback" value="Causes issues with Elementor">
								<?php echo esc_html( __( 'Causes issues with Elementor', 'sastra-essential-addons-for-elementor' ) ); ?>
							</label>
							<label>
								<input type="radio" name="feedback" value="Lack of documentation">
								<?php echo esc_html( __( 'Lack of documentation', 'sastra-essential-addons-for-elementor' ) ); ?>
							</label>
							<label>
								<input type="radio" name="feedback" value="Not the features I wanted">
								<?php echo esc_html( __( 'Not the features I wanted', 'sastra-essential-addons-for-elementor' ) ); ?>
							</label>
							<label>
								<input type="radio" name="feedback" value="Found a better plugin">
								<?php echo esc_html( __( 'Found a better plugin', 'sastra-essential-addons-for-elementor' ) ); ?>
							</label>
							<label>
								<input type="radio" name="feedback" value="Incompatible with theme or plugin">
								<?php echo esc_html( __( 'Incompatible with theme or plugin', 'sastra-essential-addons-for-elementor' ) ); ?>
							</label>
							<label>
								<input type="radio" name="feedback" value="Other">
								<?php echo esc_html( __( 'Other', 'sastra-essential-addons-for-elementor' ) ); ?>
							</label>
						</section>
						<section class="messages-wrap hidden" data-feedback>
							<p class="hidden" data-feedback="Set up is too difficult"><?php echo esc_html( __( 'What was the difficult part?', 'sastra-essential-addons-for-elementor' ) ); ?></p>
							<p class="hidden" data-feedback="Causes issues with Elementor"><?php echo esc_html( __( 'What was the issue?', 'sastra-essential-addons-for-elementor' ) ); ?></p>
							<p class="hidden" data-feedback="Lack of documentation"><?php echo esc_html( __( 'What can we describe more?', 'sastra-essential-addons-for-elementor' ) ); ?></p>
							<p class="hidden" data-feedback="Not the features I wanted"><?php echo esc_html( __( 'How could we improve?', 'sastra-essential-addons-for-elementor' ) ); ?></p>
							<p class="hidden" data-feedback="Found a better plugin"><?php echo esc_html( __( 'Can you mention it?', 'sastra-essential-addons-for-elementor' ) ); ?></p>
							<p class="hidden" data-feedback="Incompatible with theme or plugin"><?php echo esc_html( __( 'With what plugin or theme is incompatible?', 'sastra-essential-addons-for-elementor' ) ); ?></p>
							<p class="hidden" data-feedback="Other"><?php echo esc_html( __( 'Please specify:', 'sastra-essential-addons-for-elementor' ) ); ?></p>
						</section>
						<section class="options-wrap hidden" data-feedback>
							<label>
								<textarea name="suggestions" rows="2"></textarea>
							</label>
						</section>

						<section class="buttons-wrap clearfix">
							<button data-text="<?php echo esc_html__( 'Deactivating...', 'sastra-essential-addons-for-elementor' ); ?>" class="tmpcoder-deactivate-btn" data-action="deactivation"><?php echo esc_html__('Skip & Deactivate', 'sastra-essential-addons-for-elementor'); ?></button>
						</section>
					</div>

				</div>
			</div>
			<?php
	}

	private static function collect_wordpress_data( $detailed = true ) {

		$current_plugin = get_plugin_data( TMPCODER_PLUGIN_FILE );

		// Plugin data
		$data = array(
			'extra_info' => array(
				'version' => $current_plugin['Version'],
				'memory'  => 'Memory: ' . size_format( wp_convert_hr_to_bytes( ini_get( 'memory_limit' ) ) ),
				'time'    => 'Time: ' . ini_get( 'max_execution_time' ),
				'deactivate' => 'Deactivation: ' . gmdate( 'j F, Y', time() )
			),
		);

		if (function_exists('tmpcoder_pro_get_plugin_version')) {
			$data['extra_info']['pro_version'] = tmpcoder_pro_get_plugin_version();
		}

		if ( $detailed ) {

            $data['extra'] = array(
                'locale'      => ( get_bloginfo( 'version' ) >= 4.7 ) ? get_user_locale() : get_locale(),
                'themes'      => self::get_themes(),
                'plugins'     => self::get_plugins(),
            );
		}

		return $data;
	}

	/**
	 * Get a list of installed plugins
	 */
	private static function get_plugins() {

		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		// $plugins   = get_plugins();
		$option    = get_option( 'active_plugins', array() );
		$active    = array();

		foreach ( $option as $id ) {
			$id = explode( '/', $id );
			$id = ucwords( str_replace( '-', ' ', $id[0] ) );

			$active[] = $id;
		}

		return array(
			// 'installed' => $installed,
			'active'    => $active,
		);
	}

	/**
	 * Get current themes
	 *
	 * @return array
	 */
	private static function get_themes() {

		$theme = wp_get_theme();

		return array(
			// 'installed' => self::get_installed_themes(),
			'active'    => array(
				'name'    => $theme->get( 'Name' ),
			),
		);
	}

	/**
	 * Get an array of installed themes
	 *
	 * @return array
	 */
	private static function get_installed_themes() {
		$installed = wp_get_themes();
		$theme     = get_stylesheet();
		$data      = array();

		foreach ( $installed as $slug => $info ) {
			if ( $slug === $theme ) {
				continue;
			}

			$data[ $slug ] = array(
				'name'    => $info->get( 'Name' ),
			);
		}

		return $data;
	}

	private static function get_site_domain() {
		return function_exists( 'wp_parse_url' ) ? wp_parse_url( get_home_url(), PHP_URL_HOST ) : false;
	}

	/**
	 * Creates and returns an instance of the class
	 *
	 * @since 3.20.9
	 * @access public
	 *
	 * @return object
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {

			self::$instance = new self();

		}

		return self::$instance;
	}
}

TMPCODER_Feedback::get_instance();