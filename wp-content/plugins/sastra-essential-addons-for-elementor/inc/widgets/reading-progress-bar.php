<?php
namespace TMPCODER\Widgets;
// Elementor classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Reading_Progress_Bar extends Widget_Base {
    public function get_name() {
        return 'tmpcoder-reading-progress-bar';
    }

    public function get_title() {
        return esc_html__( 'Reading Progress Bar', 'sastra-essential-addons-for-elementor' );
    }

    public function get_icon() {
        return 'tmpcoder-icon eicon-skill-bar';
    }

    public function get_categories() {
        return [ 'tmpcoder-widgets-category'];
    }

    public function get_script_depends() {
        return [ 'tmpcoder-reading-progress-bar'];
    }

    public function get_style_depends() {
        return [ 'tmpcoder-reading-progress-bar'];
    }

    public function get_keywords() {
        return [ 'reading progress bar', 'skills bar', 'percentage bar', 'scroll' ];
    }

    public function get_custom_help_url() {
        return TMPCODER_NEED_HELP_URL;
    }
    
    public function register_controls() {
        
		$this->start_controls_section(
			'reading_progress_bar',
			[
                'tab' => Controls_Manager::TAB_CONTENT,
				'label' => __( 'Reading Progress Bar - Spexo Addons for Elementor', 'sastra-essential-addons-for-elementor' ),
			]
        );

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'rpb_apply_changes',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-update-preview editor-tmpcoder-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
				'separator' => 'after'
			]
		);

		$this->add_control(
			'rpb_info',
			[
				'raw' => esc_html__( 'Please scroll down a page to see how Progress Bar in works action.', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'rpb_height',
			[
				'label' => __( 'Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
                'render_type' => 'template',
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'.tmpcoder-reading-progress-bar-container' => 'height: {{SIZE}}{{UNIT}} !important',
					'.tmpcoder-reading-progress-bar-container .tmpcoder-reading-progress-bar' => 'height: {{SIZE}}{{UNIT}} !important',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'progress_bar_position',
			[
				'label' => __( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'top',
				'render_type' => 'template',
				'separator' => 'before',
				'options' => [
					'top' => __( 'Top', 'sastra-essential-addons-for-elementor' ),
					'bottom' => __( 'Bottom', 'sastra-essential-addons-for-elementor' ),
				],
				'selectors_dictionary' => [
					'top' => 'top: 0px; bottom: auto;',
					'bottom' => 'bottom: 0px; top: auto;',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-reading-progress-bar-container' => '{{VALUE}}',
				]
			]
		);

		$this->add_control(
			'rpb_background_type',
			[
				'label' => __( 'Background Type', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'transparent',
				'render_type' => 'template',
				'options' => [
					'transparent' => __( 'Transparent', 'sastra-essential-addons-for-elementor' ),
					'colored' => __( 'Colored', 'sastra-essential-addons-for-elementor' ),
				]
			]
		);

		$this->add_control(
			'rpb_background_color',
			[
				'label' => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#C5C5C6',
				'selectors' => [
					'.tmpcoder-reading-progress-bar-container' => 'background-color: {{VALUE}};'
				],
				'condition' => [
					'rpb_background_type' => 'colored'
				]
			]
		);

		$this->add_control(
			'rpb_fill_color',
			[
				'label' => __( 'Fill Color', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'.tmpcoder-reading-progress-bar-container .tmpcoder-reading-progress-bar' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'rpb_transition_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-reading-progress-bar' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				]
			]
		);

        $this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );
        
	}

	protected function render() {
    	$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		$this->add_render_attribute( 'tmpcoder-rpb-attrs', [
			'class' => 'tmpcoder-reading-progress-bar-container',
			'data-background-type' => $settings['rpb_background_type'],
		] );

        echo wp_kses_post('<div '. $this->get_render_attribute_string('tmpcoder-rpb-attrs') .'><div class="tmpcoder-reading-progress-bar"></div></div>');
	}
}