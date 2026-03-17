<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once TMPCODER_PLUGIN_DIR . 'inc/header-footer-helper/tmpcoder-plugin-advanced-hooks-loader.php';

/**
** TMPCODER_Templates_Loop setup
*/
class TMPCODER_Templates_Loop {

	/**
	** Loop Through Custom Templates
	*/
	public static function render_theme_builder_templates( $template ) {
		// WP_Query arguments
		$args = array (
			'post_type'   => array( TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE ),
			'post_status' => array( 'publish' ),
			'posts_per_page' => -1,
			'meta_key' => 'tmpcoder_template_type',
    		'meta_value' => $template,
            'meta_compare' => '=', // Use '=' for exact match
		);

		// The Query
		$user_templates = get_posts( $args );

		// The Loop
		echo '<ul class="tmpcoder-'. esc_attr($template) .'-templates-list tmpcoder-my-templates-list" data-pro="'. esc_attr(tmpcoder_is_availble()) .'">';

			if ( ! empty( $user_templates ) ) {
				foreach ( $user_templates as $user_template ) {

					$slug = $user_template->post_name;

					if ( !str_contains( $slug, 'user-' ) ) {
						continue;
					}

					$conditions2 = get_post_meta($user_template->ID, 'tmpcoder_target_include_locations',true);

                    $conditions = isset($conditions2['rule']) ? wp_json_encode(array_values($conditions2['rule'])) : '';
                    $specific_conditions = isset($conditions2['specific']) ? wp_json_encode($conditions2['specific']) : '';


					$edit_url = str_replace( 'edit', 'elementor', get_edit_post_link( $user_template->ID ) );
					$show_on_canvas = get_post_meta(tmpcoder_get_template_id($slug), 'tmpcoder_'. $template .'_show_on_canvas', true);

					echo '<li>';
				        echo '<h3 class="tmpcoder-title">'. esc_html($user_template->post_title) .'</h3>';

				        echo '<div class="tmpcoder-action-buttons">';
							// Activate
							echo '<span data-id="'.esc_attr($user_template->ID).'" id="current-layout-'.esc_attr($user_template->ID).'" class="tmpcoder-template-conditions button button-primary" data-conditions="'.esc_attr($conditions).'" data-specific="'.esc_attr($specific_conditions).'" data-slug="'. esc_attr($slug) .'" data-show-on-canvas="'. esc_attr($show_on_canvas) .'">'. esc_html__( 'Manage Conditions', 'sastra-essential-addons-for-elementor' ) .'</span>';
							// Edit
							echo '<a href="'. esc_url($edit_url) .'" class="tmpcoder-edit-template button button-primary">'. esc_html__( 'Edit Template', 'sastra-essential-addons-for-elementor' ) .'</a>';

							// Delete
							$one_time_nonce = wp_create_nonce( 'delete_post-' . $slug );

							echo '<span class="tmpcoder-delete-template button button-primary"  data-nonce="'. esc_attr($one_time_nonce) .'" data-slug="'. esc_attr($slug) .'" data-warning="'. esc_html__( 'Are you sure you want to delete this template?', 'sastra-essential-addons-for-elementor' ) .'"><span class="dashicons dashicons-trash"></span></span>';


				        echo '</div>';
					echo '</li>';
				}
			} else {
				echo '<li class="tmpcoder-no-templates">You currently don\'t have any templates!</li>';
			}

		echo '</ul>';

		// Restore original Post Data
		wp_reset_postdata();

	}

	/**
	** Loop Through My Templates
	*/
	public static function render_elementor_saved_templates() {

		// WP_Query arguments
		$args = array (
			'post_type' => array( 'elementor_library' ),
			'post_status' => array( 'publish' ),
			'meta_key' => '_elementor_template_type',
			'meta_value' => ['page', 'section', 'container'],
			'numberposts' => -1
		);

		// The Query
		$user_templates = get_posts( $args );

		// My Templates List
		echo '<ul class="tmpcoder-my-templates-list">';

		// The Loop
		if ( ! empty( $user_templates ) ) {
			foreach ( $user_templates as $user_template ) {
				// Edit URL
				$edit_url = str_replace( 'edit', 'elementor', get_edit_post_link( $user_template->ID ) );

				// List
				echo '<li>';
					echo '<h3 class="tmpcoder-title">'. esc_html($user_template->post_title) .'</h3>';
					
					echo '<span class="tmpcoder-action-buttons">';
						echo '<a href="'. esc_url($edit_url) .'" class="tmpcoder-edit-template button button-primary">'. esc_html__( 'Edit Template', 'sastra-essential-addons-for-elementor' ) .'</a>';

						// Delete
						$one_time_nonce = wp_create_nonce( 'delete_post-' . $user_template->post_name );

						echo '<span class="tmpcoder-delete-template button button-primary" data-nonce="'.esc_attr($one_time_nonce).'" data-slug="'. esc_attr($user_template->post_name) .'" data-warning="'. esc_html__( 'Are you sure you want to delete this template?', 'sastra-essential-addons-for-elementor' ) .'"><span class="dashicons dashicons-trash"></span></span>';
					echo '</span>';
				echo '</li>';
			}
		} else {
			echo '<li class="tmpcoder-no-templates">You don\'t have any templates yet!</li>';
		}
		
		echo '</ul>';

		// Restore original Post Data
		wp_reset_postdata();
	}

	/**
	** Render Conditions Popup
	*/
	public static function render_conditions_popup( $canvas = false, $template_id= false ) {

		// Active Tab
		$active_tab = isset( $_GET['layout_type'] ) ? sanitize_text_field( wp_unslash( $_GET['layout_type'] ) ) : 'type_header';// phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$post_id = tmpcoder_get_post_id_by_meta_key_and_meta_value('tmpcoder_template_type', $active_tab);

		if ($template_id) {
			$post_id = $template_id;
		}

	?>

    <div class="tmpcoder-condition-popup-wrap tmpcoder-admin-popup-wrap">
        <div class="tmpcoder-condition-popup tmpcoder-admin-popup">
            <header>
                <h2><?php esc_html_e( 'Where Would You Like to See Your Template Presented?', 'sastra-essential-addons-for-elementor' ); ?></h2>
               
                    <?php esc_html_e( 'Define the rules that establish how and where your Template appears on your website.', 'sastra-essential-addons-for-elementor' ); ?><br>
            </header>
            <div class="popup-loader-html">
	            <div class="welcome-backend-loader">
	                <img src="<?php echo esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/backend-loader.gif'); ?>" alt="" width="80" height="80" />
	            </div>
            </div>
            <span class="close-popup dashicons dashicons-no-alt"></span>
            <table class="tmpcoder-options-table widefat">
				<tbody>
		            <?php TMPCODER_Target_Rules_Fields::get_instance()->admin_styles(); ?>
		            <tr class="bsf-target-rules-row tmpcoder-options-row">
						<td class="bsf-target-rules-row-heading tmpcoder-options-row-heading">
							<label><?php esc_html_e( 'Display On', 'sastra-essential-addons-for-elementor' ); ?></label>
							<i class="bsf-target-rules-heading-help dashicons dashicons-editor-help"
								title="<?php esc_attr_e( 'Add locations for where this template should appear.', 'sastra-essential-addons-for-elementor' ); ?>"></i>
						</td>
						<td class="bsf-target-rules-row-content tmpcoder-options-row-content">

							<?php
							$include_locations = get_post_meta( $post_id, 'tmpcoder_target_include_locations', true );

							TMPCODER_Target_Rules_Fields::target_rule_settings_field(
								'bsf-target-rules-location',
								[
									'title'          => __( 'Display Rules', 'sastra-essential-addons-for-elementor' ),
									'value'          => '[{"type":"basic-global","specific":null}]',
									'tags'           => 'site,enable,target,pages',
									'rule_type'      => 'display',
									'add_rule_label' => __( 'Add Display Rule', 'sastra-essential-addons-for-elementor' ),
								],
								$include_locations
							);
							?>
						</td>
					</tr>
				</tbody>
			</table>

            <?php
           	// Pro Notice
			if ( ! tmpcoder_is_availble() ) {
				echo '<span class="tmpcoder-popup-pro-notice"><br>Conditions are fully suppoted in the <strong><a href="'. esc_url(TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-conditions-upgrade-pro#purchasepro') .'" target="_blank">Pro versions.</a></strong></span>';
			}

            ?>
            
            <!-- Action Buttons -->
            <span class="tmpcoder-save-conditions"><?php esc_html_e( 'Save Conditions', 'sastra-essential-addons-for-elementor' ); ?></span>
        </div>
    </div>

	<?php

	}


	/**
	** Render Create Template Popup
	*/
	public static function render_create_template_popup() {
	?>

    <!-- Custom Template Popup -->
    <div class="tmpcoder-user-template-popup-wrap tmpcoder-admin-popup-wrap">
        <div class="tmpcoder-user-template-popup tmpcoder-admin-popup">
        	<header>
	            <h2><?php esc_html_e( 'Templates are instrumental in boosting your efficiency at work!', 'sastra-essential-addons-for-elementor' ); ?></h2>
	            <p><?php esc_html_e( 'Utilize templates to generate various components of your website, allowing you to effortlessly reuse them whenever necessary with just one click.', 'sastra-essential-addons-for-elementor' ); ?></p>
			</header>

            <input type="text" name="user_template_title" class="tmpcoder-user-template-title" placeholder="<?php esc_html_e( 'Enter Template Title', 'sastra-essential-addons-for-elementor' ); ?>">
            <input type="hidden" name="user_template_type" class="user-template-type">
            <span class="tmpcoder-create-template"><?php esc_html_e( 'Create Template', 'sastra-essential-addons-for-elementor' ); ?></span>
            <span class="close-popup dashicons dashicons-no-alt"></span>
        </div>
    </div>

	<?php
	}

	/**
	** Render Create Template Popup
	*/
	public static function render_delete_template_confirm_popup() {
		?>
		<div class="tmpcoder-delete-template-confirm-popup-wrap tmpcoder-admin-popup-wrap">
            <div class="tmpcoder-delete-template-popup tmpcoder-admin-popup">
                <div id="tmpcoder-delete-template-confirm-popup">
					<header>
						<h2><?php esc_html_e( 'Are you sure you want to delete this template?', 'sastra-essential-addons-for-elementor' ); ?></h2>
						<p><?php echo wp_kses_post(__( 'This template and its settings will be <strong>permanently removed</strong> from your site. You <strong>won’t be able to recover it</strong> later.', 'sastra-essential-addons-for-elementor' )); ?></p>
					</header>
                    <div class="popup-action">
						<a class="button button-primary tmpcoder-delete-template-confirm-button"><?php esc_html_e('Delete Template', 'sastra-essential-addons-for-elementor') ?></a>
                        <a class="button button-secondary tmpcoder-delete-template-confirm-popup-close"><?php esc_html_e('Cancel', 'sastra-essential-addons-for-elementor') ?></a>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
	

	/**
	** Check if Library Template Exists
	*/
	public static function template_exists( $slug ) {
		$result = false;
		$tmpcoder_templates = get_posts( ['post_type' => 'tmpcoder_templates', 'posts_per_page' => '-1'] );

		foreach ( $tmpcoder_templates as $post ) {

			if ( $slug === $post->post_name ) {
				$result = true;
			}
		}

		return $result;
	}

}