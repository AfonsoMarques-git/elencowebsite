<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load Target rules.
require_once TMPCODER_PLUGIN_DIR . 'inc/header-footer-helper/lib/target-rule/class-tmpcoder-target-rules-fields.php';
require_once (TMPCODER_PLUGIN_DIR . 'inc/header-footer-helper/class-header-footer-base.php');


if ( ! class_exists( 'TMPCODER_Advanced_Hooks_Loader' ) ) {

	class TMPCODER_Advanced_Hooks_Loader { 

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Member Variable
		 *
		 * @var $_actions
		 */
		public static $_action = 'advanced-hooks';

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {

			add_action( 'init', array( $this, 'advanced_hooks_post_type' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'tmpcoder_admin_enqueue_scripts_func' ) );
			
            // prebuild blocks menu register
            add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 90 );

            // prebuild blocks menu register
            add_action( 'admin_menu', array( $this, 'register_admin_menu_widgets' ), 100 );

            // prebuild blocks menu register
            add_action( 'admin_menu', array( $this, 'register_admin_menu_integrations' ), 100 );
            
            // support menu register
            add_action( 'admin_menu', array( $this, 'register_admin_menu_support' ), 120 );
            
            // upgrade menu register
            add_action( 'admin_menu', array( $this, 'register_admin_menu_upgrade' ), 130 );
			
			add_filter( 'manage_' . TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE . '_posts_columns', [ $this, 'set_shortcode_columns' ] );

			add_action( 'manage_' . TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE . '_posts_custom_column', [ $this, 'render_shortcode_column' ], 10, 2 );
			
			add_action( 'manage_' . TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE . '_posts_custom_column', [ $this, 'render_layout_type_column' ], 10, 2 );

			if ( is_admin() ) {

				add_action( 'manage_' . TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE . '_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
				
				add_filter( 'manage_' . TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE . '_posts_columns', array( $this, 'column_headings' ) );

			}

			// Custom layout tabs based on type.
			add_filter( 'views_edit-' . TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE, array( $this, 'admin_print_tabs' ) );

			// Show only active tab posts in custom layout.
			add_action( 'parse_query', array( $this, 'admin_query_filter_types' ) );

		}

		public function tmpcoder_admin_enqueue_scripts_func()
		{
			wp_enqueue_style( 'tmpcoder-admin-style', TMPCODER_PLUGIN_URI . 'inc/header-footer-helper/assets/css/tmpcoder-admin'.tmpcoder_script_suffix().'.css', [], tmpcoder_get_plugin_version() );

		    $is_dismissed = get_user_meta( get_current_user_id(), 'tmpcoder-popup' );

			$strings = [
				'addon_activate'    => esc_html__( 'Activate', 'sastra-essential-addons-for-elementor' ),
				'addon_activated'   => esc_html__( 'Activated', 'sastra-essential-addons-for-elementor' ),
				'addon_active'      => esc_html__( 'Active', 'sastra-essential-addons-for-elementor' ),
				'addon_deactivate'  => esc_html__( 'Deactivate', 'sastra-essential-addons-for-elementor' ),
				'addon_inactive'    => esc_html__( 'Inactive', 'sastra-essential-addons-for-elementor' ),
				'addon_install'     => esc_html__( 'Install', 'sastra-essential-addons-for-elementor' ),
				'theme_installed'   => esc_html__( 'Theme Installed', 'sastra-essential-addons-for-elementor' ),
				'plugin_installed'  => esc_html__( 'Plugin Installed', 'sastra-essential-addons-for-elementor' ),
				'addon_download'    => esc_html__( 'Download', 'sastra-essential-addons-for-elementor' ),
				'addon_exists'      => esc_html__( 'Already Exists.', 'sastra-essential-addons-for-elementor' ),
				'visit_site'        => esc_html__( 'Visit Website', 'sastra-essential-addons-for-elementor' ),
				'plugin_error'      => esc_html__( 'Could not install. Please download from WordPress.org and install manually.', 'sastra-essential-addons-for-elementor' ),
				'subscribe_success' => esc_html__( 'Your details are submitted successfully.', 'sastra-essential-addons-for-elementor' ),
				'subscribe_error'   => esc_html__( 'Encountered an error while performing your request.', 'sastra-essential-addons-for-elementor' ),
				'ajax_url'          => admin_url( 'admin-ajax.php' ),
				'nonce'             => wp_create_nonce( 'tmpcoder-admin-nonce' ),
				'popup_dismiss'     => false,
				'data_source'       => 'TMPCODER',
			];

			$strings = apply_filters( 'tmpcoder_admin_strings', $strings );

			wp_localize_script(
				'tmpcoder-admin-script',
				'tmpcoder_admin_data',
				$strings
			);
		}

		/**
		 * Create Advanced Hooks custom post type
		 */
		public function advanced_hooks_post_type() {
            if ( defined('TMPCODER_THEME') && defined('TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE') )
            {
                $labels = array(
                    'name'          => esc_html_x( 'Site Builder', 'advanced-hooks general name', 'sastra-essential-addons-for-elementor' ),
                    'singular_name' => esc_html_x( 'Custom Layout', 'advanced-hooks singular name', 'sastra-essential-addons-for-elementor' ),
                    'search_items'  => esc_html__( 'Search Theme Layouts', 'sastra-essential-addons-for-elementor' ),
                    'all_items'     => esc_html__( 'All Theme Layouts', 'sastra-essential-addons-for-elementor' ),
                    'edit_item'     => esc_html__( 'Edit Custom Layout', 'sastra-essential-addons-for-elementor' ),
                    'view_item'     => esc_html__( 'View Custom Layout', 'sastra-essential-addons-for-elementor' ),
                    'add_new'       => esc_html__( 'Add New', 'sastra-essential-addons-for-elementor' ),
                    'update_item'   => esc_html__( 'Update Custom Layout', 'sastra-essential-addons-for-elementor' ),
                    'add_new_item'  => esc_html__( 'Add New', 'sastra-essential-addons-for-elementor' ),
                    'new_item_name' => esc_html__( 'New Custom Layout Name', 'sastra-essential-addons-for-elementor' ),
                );

                $rest_support = true;

                // Rest support false if it is a old post with post meta code_editor set.
                if ( isset( $_GET['code_editor'] ) || ( isset( $_GET['post'] ) && 'code_editor' === get_post_meta( absint($_GET['post']), 'editor_type', true ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    $rest_support = false;
                }

                // Rest support true if it is a WordPress editor.
                if ( isset( $_GET['wordpress_editor'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    $rest_support = true;
                }

                $args = array(
                    'labels'              => $labels,
                    'show_in_menu'        => false,
                    'public'              => true,
                    'show_ui'             => true,
                    'can_export'          => true,
                    'show_in_nav_menus'   => false,
                    'exclude_from_search' => true,
                    'capability_type'     => 'post',
                    'hierarchical'        => false,
                    'supports'            => [ 'title', 'thumbnail', 'elementor' ],
                    'rewrite'             => array( 'slug' => apply_filters('tmpcoder_advanced_hooks_rewrite_slug', TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE ) ),
                );

                register_post_type( TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE, apply_filters('tmpcoder_advanced_hooks_post_type_args', $args ) );
            }
		}

		/**
		 * Register the admin menu for Theme Layouts
		 *
		 * @since  1.2.1
		 *         Moved the menu under Appearance -> Theme Layouts
		 */
		public function register_admin_menu() {
            if ( defined('TMPCODER_THEME') )
            {
                $custom_layouts_capability = apply_filters('tmpcoder_custom_layouts_capability', 'edit_theme_options' );

                add_submenu_page(
                    TMPCODER_THEME.'-welcome',
                    __( 'Prebuilt Blocks', 'sastra-essential-addons-for-elementor' ),
                    __( 'Prebuilt Blocks', 'sastra-essential-addons-for-elementor' ),
                    'manage_options',
                    'admin.php?page='.TMPCODER_THEME.'-welcome&tab=prebuilt-blocks'
                );
            }
		}

        public function register_admin_menu_widgets(){
            if ( defined('TMPCODER_THEME') )
            {
                add_submenu_page(
                    TMPCODER_THEME.'-welcome',
                    __( 'Widget Settings', 'sastra-essential-addons-for-elementor' ),
                    __( 'Widget Settings', 'sastra-essential-addons-for-elementor' ),
                    'manage_options',
                    'admin.php?page='.TMPCODER_THEME.'-welcome&tab=widgets'
                );
            }
        }

        public function register_admin_menu_integrations(){
            if ( defined('TMPCODER_THEME') )
            {
                add_submenu_page(
                    TMPCODER_THEME.'-welcome',
                    __( 'Settings', 'sastra-essential-addons-for-elementor' ),
                    __( 'Settings', 'sastra-essential-addons-for-elementor' ),
                    'manage_options',
                    'admin.php?page='.TMPCODER_THEME.'-welcome&tab=settings'
                );

                add_submenu_page(
                    TMPCODER_THEME.'-welcome',
                    __( 'Tools', 'sastra-essential-addons-for-elementor' ),
                    __( 'Tools', 'sastra-essential-addons-for-elementor' ),
                    'manage_options',
                    'admin.php?page='.TMPCODER_THEME.'-welcome&tab=tools'
                );
            }
        }

        public function register_admin_menu_support() {
            if ( defined('TMPCODER_THEME') )
            {
                add_submenu_page(
		            TMPCODER_THEME.'-welcome',
		            __( 'System Info', 'sastra-essential-addons-for-elementor' ),
		            __( 'System Info', 'sastra-essential-addons-for-elementor' ),
		            'manage_options',
		            'admin.php?page='.TMPCODER_THEME.'-welcome&tab=system-info'
		        );

                add_submenu_page(
		            TMPCODER_THEME.'-welcome',
		            __( 'Support', 'sastra-essential-addons-for-elementor' ),
		            __( 'Support', 'sastra-essential-addons-for-elementor' ),
		            'manage_options',
		            'tmpcoder-support',
		            TMPCODER_SUPPORT_URL
		        );
            }
		}

        public function register_admin_menu_upgrade() {
            if ( defined('TMPCODER_THEME') )
            {
                if ( !tmpcoder_is_availble() ){
                    add_submenu_page(
                        TMPCODER_THEME.'-welcome',
                        __( 'Upgrade', 'sastra-essential-addons-for-elementor' ),
                        __( 'Upgrade', 'sastra-essential-addons-for-elementor' ),
                        'manage_options',
                        'tmpcoder-upgrade',
                        'tmpcoder_addon_upgrade_page'
                    );
                }
            }
		}

		/**
		 * Adds the custom list table column content.
		 *
		 * @since 1.2.0
		 * @param array $column Name of column.
		 * @param int   $post_id Post id.
		 * @return void
		 */
		public function column_content( $column, $post_id ) {

			if ( 'tmpcoder_display_rules' == $column ) {

				$locations = get_post_meta( $post_id, 'tmpcoder_target_include_locations', true );
				if ( ! empty( $locations ) ) {
					echo '<div class="tmpcoder-advanced-headers-location-wrap" style="margin-bottom: 5px;">';
					echo '<strong>'.esc_html(__( 'Display:', 'sastra-essential-addons-for-elementor' )).' </strong>';
					$this->column_display_location_rules( $locations );
					echo '</div>';
				}

				$locations = get_post_meta( $post_id, 'tmpcoder_target_exclude_locations', true );
				if ( ! empty( $locations ) ) {
					echo '<div class="tmpcoder-advanced-headers-exclusion-wrap" style="margin-bottom: 5px;">';
					echo '<strong>'.esc_html(__( 'Exclusion:', 'sastra-essential-addons-for-elementor' )).' </strong>';
					$this->column_display_location_rules( $locations );
					echo '</div>';
				}

				$users = get_post_meta( $post_id, 'tmpcoder_target_user_roles', true );
				if ( isset( $users ) && is_array( $users ) ) {
					if ( isset( $users[0] ) && ! empty( $users[0] ) ) {
						$user_label = [];
						foreach ( $users as $user ) {
							$user_label[] = TMPCODER_Target_Rules_Fields::get_user_by_key( $user );
						}
						echo '<div class="tmpcoder-advanced-headers-users-wrap">';
						echo '<strong>'.esc_html(__( 'Users:', 'sastra-essential-addons-for-elementor' )).' </strong>';
						echo esc_html( join( ', ', $user_label ) );
						echo '</div>';
					}
				}
			}
		}

		/**
		 * Get Markup of Location rules for Display rule column.
		 *
		 * @param array $locations Array of locations.
		 * @return void
		 */
		public function column_display_location_rules( $locations ) {

			$location_label = [];

			$index = isset($locations['rule']) ? array_search( 'specifics', $locations['rule'] ) : [];

			if ( false !== $index && ! empty( $index ) ) {
				unset( $locations['rule'][ $index ] );
			}

			if ( isset( $locations['rule'] ) && is_array( $locations['rule'] ) ) {
				foreach ( $locations['rule'] as $location ) {
					$location_label[] = TMPCODER_Target_Rules_Fields::get_location_by_key( $location );
				}
			}
			if ( isset( $locations['specific'] ) && is_array( $locations['specific'] ) ) {
				foreach ( $locations['specific'] as $location ) {
					$location_label[] = TMPCODER_Target_Rules_Fields::get_location_by_key( $location );
				}
			}

			echo esc_html( join( ', ', $location_label ) );
		}

		function tmpcoder_addon_upgrade_page() {}

		/**
		 * Set shortcode column for template list.
		 *
		 * @param array $columns template list columns.
		 */
		function set_shortcode_columns( $columns ) {

			$date_column = $columns['date'];

			unset( $columns['date'] );

			$columns['layouts_type'] = __( 'Layouts Type', 'sastra-essential-addons-for-elementor' );
			$columns['date']      = $date_column;

			return $columns;
		}

		/**
		 * Display shortcode in template list column.
		 *
		 * @param array $column template list column.
		 * @param int   $post_id post id.
		 */
		function render_shortcode_column( $column, $post_id ) {
			
			switch ( $column ) {
				case 'shortcode':
					ob_start();
					?>
					<span class="tmpcoder-shortcode-col-wrap">
						<input type="text" onfocus="this.select();" readonly="readonly" value="[tmpcoder_template id='<?php echo esc_attr( $post_id ); ?>']" class="tmpcoder-large-text code">
					</span>

					<?php

					ob_get_contents();
					break;
			}
		}


		/**
		 * Display layouts type in template list column.
		 *
		 * @param array $column template list column.
		 * @param int   $post_id post id.
		 */
		function render_layout_type_column( $column, $post_id ) {
			
			switch ( $column ) {
				case 'layouts_type':
					ob_start();
					?>
					<span class="tmpcoder-shortcode-col-wrap">
						<strong><?php 
						$type = get_post_meta($post_id, 'tmpcoder_template_type',true);
							
						if (!empty($type)) {
							
							echo esc_html($this->custom_layout_types_arr($type));
						}
						else
						{
							echo "-";
						}
						?></strong>
					</span>

					<?php

					ob_get_contents();
					break;
			}
		}

		function get_layout_type($type)
		{
			$layout_type = [];
			$layout_type['type_header'] = __( 'Header', 'sastra-essential-addons-for-elementor' );
			$layout_type['type_footer'] = __( 'Footer', 'sastra-essential-addons-for-elementor' );
			$layout_type['type_404'] 	= __( '404 Page', 'sastra-essential-addons-for-elementor' );

			if (array_key_exists($type, $layout_type)) {
				
				return $layout_type[$type];	
			}
		}

		/**
		 * Adds or removes list table column headings.
		 *
		 * @param array $columns Array of columns.
		 * @return array
		 */
		public function column_headings( $columns ) {
			unset( $columns['date'] );
			$columns['tmpcoder_display_rules'] = __( 'Display Rules', 'sastra-essential-addons-for-elementor' );
			$columns['date']                       = __( 'Date', 'sastra-essential-addons-for-elementor' );
			return $columns;
		}

		/**
		 * Print admin tabs.
		 *
		 * Used to output the Theme Layouts on basis of their types.
		 *
		 * Fired by `views_edit-tmpcoder-advanced-hook` filter.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $views An array of available list table views.
		 *
		 * @return array An updated array of available list table views.
		 */
		public function admin_print_tabs( $views ) {

			$current_type = '';
			$active_class = ' nav-tab-active';
			$current_tab  = $this->get_active_tab();

			if ( ! empty( $_GET['layout_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$current_type = sanitize_text_field(wp_unslash($_GET['layout_type'])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$active_class = '';
			}

			$url_args = array(
				'post_type'   => TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE,
				'layout_type' => $current_tab,
			);

			$custom_layout_types = $this->custom_layout_types_arr();

			$baseurl = add_query_arg( $url_args, admin_url( 'edit.php' ) );

			?>
				<div class="nav-tab-wrapper tmpcoder-custom-layout-tabs-wrapper">
					<?php
					foreach ( $custom_layout_types as $type => $title ) {
						$type_url = esc_url( add_query_arg( 'layout_type', $type, $baseurl ) );
						$active_class = ( $current_type === $type ) ? ' nav-tab-active' : '';

						?>
							<a class="nav-tab<?php echo esc_attr( $active_class ); ?>" href="<?php echo esc_url( $type_url ); ?>">
							<?php
								echo esc_html( $title );
							?>
							</a>
						<?php
					}
					?>
				</div>
			<?php

			return $views;
		}

		/**
		 * Get default/active tab for custom layout admin tables.
		 *
		 * @since 1.0.0
		 * @param string $default default tab attr.
		 * @return string $current_tab
		 */
		public function get_active_tab( $default = '' ) {
			$current_tab = $default;

			if ( ! empty( $_GET['layout_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$current_tab = sanitize_text_field(wp_unslash($_GET['layout_type'])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

			return $current_tab;
		}

		/**
		 * Filter Theme Layouts in admin query.
		 *
		 * Update the Theme Layouts in the main admin query.
		 *
		 * Fired by `parse_query` action.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param WP_Query $query The `WP_Query` instance.
		 */
		public function admin_query_filter_types( WP_Query $query ) {
			global $pagenow, $typenow;

			if ( ! ( 'edit.php' === $pagenow && TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE === $typenow ) || ! empty( $query->query_vars['meta_key'] ) ) {
				return;
			}

			$current_tab = $this->get_active_tab();

			if ( isset( $query->query_vars['layout_type'] ) && '-1' === $query->query_vars['layout_type'] ) {
				unset( $query->query_vars['layout_type'] );
			}

			if ( empty( $current_tab ) ) {
				return;
			}

			$query->query_vars['meta_key']   = 'tmpcoder_template_type';
			$query->query_vars['meta_value'] = $current_tab;
		}

		/**
		 * Render Meta field.
		 *
		 * @param  POST $post Currennt post object which is being displayed.
		 */
		function tmpcoder_metabox_render( $post ) {
			$values            = get_post_custom( $post->ID );
			$template_type     = isset( $values['tmpcoder_template_type'] ) ? esc_attr( $values['tmpcoder_template_type'][0] ) : '';
			$display_on_canvas = isset( $values['display-on-canvas-template'] ) ? true : false;

			// We'll use this nonce field later on when saving.
			wp_nonce_field( 'tmpcoder_meta_nounce', 'tmpcoder_meta_nounce' );

			$custom_layout_types_arr = $this->custom_layout_types_arr();

			?>
			<table class="tmpcoder-options-table widefat">
				<tbody>
					<tr class="tmpcoder-options-row type-of-template">
						<td class="tmpcoder-options-row-heading">
							<label for="tmpcoder_template_type"><?php esc_html_e( 'Type of Template', 'sastra-essential-addons-for-elementor' ); ?></label>
						</td>
						<td class="tmpcoder-options-row-content">
							<select name="tmpcoder_template_type" id="tmpcoder_template_type">

								<option value="" <?php selected( $template_type, '' ); ?>><?php esc_html_e( 'Select Option', 'sastra-essential-addons-for-elementor' ); ?></option>

								<?php 

								if ($custom_layout_types_arr && is_array($custom_layout_types_arr))
								{
									foreach ($custom_layout_types_arr as $key => $value) { ?>
										<option value="<?php echo esc_attr($key) ?>" <?php selected( $template_type, $key ); ?>><?php
											echo esc_html($value); ?></option>
									<?php
								 	}
								}

								?>
							</select>
						</td>
					</tr>

					<?php $this->display_rules_tab(); ?>
					<tr class="tmpcoder-options-row tmpcoder-shortcode">
						<td class="tmpcoder-options-row-heading">
							<label for="tmpcoder_template_type"><?php esc_html_e( 'Shortcode', 'sastra-essential-addons-for-elementor' ); ?></label>
							<i class="tmpcoder-options-row-heading-help dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Copy this shortcode and paste it into your post, page, or text widget content.', 'sastra-essential-addons-for-elementor' ); ?>">
							</i>
						</td>
						<td class="tmpcoder-options-row-content">
							<span class="tmpcoder-shortcode-col-wrap">
								<input type="text" onfocus="this.select();" readonly="readonly" value="[tmpcoder_template id='<?php echo esc_attr( $post->ID ); ?>']" class="tmpcoder-large-text code">
							</span>
						</td>
					</tr>
					<tr class="tmpcoder-options-row enable-for-canvas">
						<td class="tmpcoder-options-row-heading">
							<label for="display-on-canvas-template">
								<?php esc_html_e( 'Enable Layout for Elementor Canvas Template?', 'sastra-essential-addons-for-elementor' ); ?>
							</label>
							<i class="tmpcoder-options-row-heading-help dashicons dashicons-editor-help" title="<?php esc_html_e( 'Enabling this option will display this layout on pages using Elementor Canvas Template.', 'sastra-essential-addons-for-elementor' ); ?>"></i>
						</td>
						<td class="tmpcoder-options-row-content">
							<input type="checkbox" id="display-on-canvas-template" name="display-on-canvas-template" value="1" <?php checked( $display_on_canvas, true ); ?> />
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}

		/**
		 * Markup for Display Rules Tabs.
		 *
		 * @since  1.0.0
		 */
		public function display_rules_tab() {
			// Load Target Rule assets.
			TMPCODER_Target_Rules_Fields::get_instance()->admin_styles();

			$include_locations = get_post_meta( get_the_id(), 'tmpcoder_target_include_locations', true );
			$exclude_locations = get_post_meta( get_the_id(), 'tmpcoder_target_exclude_locations', true );
			$users             = get_post_meta( get_the_id(), 'tmpcoder_target_user_roles', true );
			?>
			<tr class="bsf-target-rules-row tmpcoder-options-row">
				<td class="bsf-target-rules-row-heading tmpcoder-options-row-heading">
					<label><?php esc_html_e( 'Display On', 'sastra-essential-addons-for-elementor' ); ?></label>
					<i class="bsf-target-rules-heading-help dashicons dashicons-editor-help"
						title="<?php echo esc_attr__( 'Add locations for where this template should appear.', 'sastra-essential-addons-for-elementor' ); ?>"></i>
				</td>
				<td class="bsf-target-rules-row-content tmpcoder-options-row-content">
					<?php
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
			<?php
		}

		public function custom_layout_types_arr($key = '')
		{
			$custom_layout_types = array(
				'type_header'   => __( 'Header', 'sastra-essential-addons-for-elementor' ),
				'type_footer'   => __( 'Footer', 'sastra-essential-addons-for-elementor' ),
				'type_archive'   => __( 'Post Archive', 'sastra-essential-addons-for-elementor' ),
				'type_single_post'   => __( 'Single Post', 'sastra-essential-addons-for-elementor' ),
				'type_search_result_page'   => __( 'Search Results Page', 'sastra-essential-addons-for-elementor' ),
				'type_404' => __( '404 Page', 'sastra-essential-addons-for-elementor' ),
			);

			if ( class_exists('WooCommerce') ) {
				$custom_layout_types['type_product_category'] = __( 'Product Category', 'sastra-essential-addons-for-elementor' );
				$custom_layout_types['type_product_archive']  = __( 'Products Archive', 'sastra-essential-addons-for-elementor' );
				$custom_layout_types['type_single_product']   = __( 'Single Product', 'sastra-essential-addons-for-elementor' );
			}

			if ($key) {
				return isset($custom_layout_types[$key])?$custom_layout_types[$key]:'-';
			}
			else
			{
				return $custom_layout_types;
			}

		}

	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
TMPCODER_Advanced_Hooks_Loader::get_instance();
