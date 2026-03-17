<?php
namespace TMPCODER\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Elementor_Template extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-elementor-template';
	}

	public function get_title() {
		return esc_html__( 'Global Templates', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-document-file';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'spexo', 'elementor', 'template', 'load' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	return TMPCODER_NEED_HELP_URL;
    }

	protected function register_controls() {
		
		// Section: General ----------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'select_template' ,
			[
				'label' => esc_html__( 'Select Template', 'sastra-essential-addons-for-elementor' ),
				'type'  => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				// Translators: %s is the menus link.
				'description' => sprintf( __( 'Go to <a href="%s" target="_blank">Spexo Addons > Site Builder</a> to manage your templates.', 'sastra-essential-addons-for-elementor' ), admin_url( 'admin.php?page=spexo-welcome&tab=site-builder&layout_type=type_global_template' ) ),
			]
		);

		// Restore original Post Data
		wp_reset_postdata();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );
	}
		

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if ( !empty($settings['select_template']) && '' !== $settings['select_template'] ) {
			$id = $settings['select_template'];

			if ( defined('ICL_LANGUAGE_CODE') ) {
				$default_language_code = apply_filters('wpml_default_language', null);

				if ( ICL_LANGUAGE_CODE !== $default_language_code ) {
					$id = icl_object_id($id, 'elementor_library', false, ICL_LANGUAGE_CODE);
				}
			}

			$edit_link = '<span class="tmpcoder-template-edit-btn" data-permalink="'. esc_url(get_permalink($id)) .'">Edit Template</span>';
		
			$type = get_post_meta(get_the_ID(), '_tmpcoder_template_type', true) || get_post_meta($id, '_elementor_template_type', true);
			$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

			// PHPCS - should not be escaped.
			echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}