<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Base\Document;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Lottie_Animations extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-lottie-animations';
	}

	public function get_title() {
		return esc_html__( 'Lottie Animations', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-lottie';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category' ];
	}

	public function get_keywords() {
		return [ 'lottie', 'animation', 'animations', 'svg' ];
	}
	
	public function get_script_depends() {
		return [ 'tmpcoder-lottie-animations-lib', 'tmpcoder-lottie-animations' ];
	}

    public function get_custom_help_url() {
    	return TMPCODER_NEED_HELP_URL;
    }
	
	protected function register_controls() {

		// Section: Settings ---------
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' ),
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'source',
			[
				'label'   => __( 'File Source', 'sastra-essential-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'url'  => __( 'External URL', 'sastra-essential-addons-for-elementor' ),
					'file' => __( 'Media File', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'url',
			]
		);

		$this->add_control(
			'json_url',
			[
				'label'       => __( 'Animation JSON URL', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default'	  => 'https://assets3.lottiefiles.com/packages/lf20_ghs9bkkc.json',
				'description' => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
				'label_block' => true,
				'condition'   => [
					'source' => 'url',
				],
			]
		);

		$this->add_control(
			'json_file',
			array(
				'label'              => __( 'Upload JSON File', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::MEDIA,
				'media_type'         => 'application/json',
				'frontend_available' => true,
				'condition'          => [
					'source' => 'file',
				]
			)
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'reverse',
			[
				'label'        => __( 'Reverse', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'condition' => [
					'trigger!' => 'scroll'
				]
			]
		);

		$this->add_control(
			'speed',
			array(
				'label'   => __( 'Animation Speed', 'sastra-essential-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 0.1,
				'max'     => 3,
				'step'    => 0.1,
			)
		);

		$this->add_control(
			'trigger',
			[
				'label' => __( 'Trigger', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options'            => array(
					'none'     => __( 'None', 'sastra-essential-addons-for-elementor' ),
					'viewport' => __( 'Viewport', 'sastra-essential-addons-for-elementor' ),
					'hover'    => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
					'scroll'   => __( 'Scroll', 'sastra-essential-addons-for-elementor' ),
				),
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'animate_view',
			array(
				'label'     => __( 'Viewport', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'sizes' => array(
						'start' => 0,
						'end'   => 100,
					),
					'unit'  => '%',
				),
				'labels'    => array(
					__( 'Bottom', 'sastra-essential-addons-for-elementor' ),
					__( 'Top', 'sastra-essential-addons-for-elementor' ),
				),
				'scales'    => 1,
				'handles'   => 'range',
				'condition' => array(
					'trigger'         => array( 'scroll', 'viewport' ),
				),
			)
		);
		
		$this->add_responsive_control(
			'animation_size',
			array(
				'label'       => __( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 50,
				),
				'range'       => array(
					'px' => array(
						'min' => 1,
						'max' => 800,
					),
					'em' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'render_type' => 'template',
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} .tmpcoder-lottie-animations svg' => 'width: 100% !important; height: 100% !important;',
					'{{WRAPPER}} .tmpcoder-lottie-animations' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);
		
		$this->add_responsive_control(
			'rotate',
			array(
				'label'       => __( 'Rotate (degrees)', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => __( 'Set rotation value in degrees', 'sastra-essential-addons-for-elementor' ),
				'range'       => array(
					'px' => array(
						'min' => -180,
						'max' => 180,
					),
				),
				'default'     => array(
					'size' => 0,
				),
				'selectors'   => array(
					'{{WRAPPER}} .tmpcoder-lottie-animations' => 'transform: rotate({{SIZE}}deg)',
				),
			)
		);
		
		$this->add_responsive_control(
			'animation_align',
			array(
				'label'     => __( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .tmpcoder-lottie-animations-wrapper' => 'display: flex; justify-content: {{VALUE}}; align-items: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'lottie_renderer',
			[
				'label'        => __( 'Render As', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'svg'    => __( 'SVG', 'sastra-essential-addons-for-elementor' ),
					'canvas' => __( 'Canvas', 'sastra-essential-addons-for-elementor' ),
				),
				'default'      => 'svg',
				'prefix_class' => 'tmpcoder-lottie-',
				'render_type'  => 'template',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'render_notice',
			[
				'raw'             => __( 'Set render type to canvas if you\'re having performance issues on the page.', 'sastra-essential-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		
		$this->add_control(
			'link_switcher',
			[
				'label' => __( 'Wrapper Link', 'sastra-essential-addons-for-elementor' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'link_selection',
			[
				'label'       => __( 'Link Type', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'url'  => __( 'URL', 'sastra-essential-addons-for-elementor' ),
					'link' => __( 'Existing Page', 'sastra-essential-addons-for-elementor' ),
				),
				'default'     => 'url',
				'label_block' => true,
				'condition'   => array(
					'link_switcher' => 'yes',
				),
			]
		);
		
		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default'     => array(
					'url' => '#',
				),
				'placeholder' => TMPCODER_NEED_HELP_URL,
				'label_block' => true,
				'condition'   => array(
					'link_switcher'  => 'yes',
					'link_selection' => 'url',
				),
			)
		);

		$this->add_control(
			'existing_link',
			array(
				'label' => __( 'Existing Page', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_posts_by_post_type',
				'query_slug' => 'page',
				'multiple' => false,
				'label_block' => true,
				'condition' => array(
					'link_switcher'  => 'yes',
					'link_selection' => 'link',
				),
			)
		);


		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		$this->start_controls_section(
			'lottie_styles',
			[
				'label' => __( 'Animation', 'sastra-essential-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_lottie' );

		$this->start_controls_tab(
			'tab_lottie_normal',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label'     => __( 'Opacity', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tmpcoder-lottie-animations' => 'opacity: {{SIZE}}',
				),
			]
		);

		$this->add_control(
			'hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-lottie-animations' => 'transition-duration: {{VALUE}}s;'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .tmpcoder-lottie-animations',
			)
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_lottie_hover',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'hover_opacity',
			array(
				'label'     => __( 'Opacity', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tmpcoder-lottie-animations:hover' => 'opacity: {{SIZE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'hover_css_filters',
				'selector' => '{{WRAPPER}} .tmpcoder-lottie-animations:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section(); // End Controls Section
	
	}

	public function lottie_attributes($settings) {
		$attributes = [
			'loop' => $settings['loop'],
			'autoplay' => $settings['autoplay'],
			/// TODO: reverse
			'speed' => $settings['speed'],
			'trigger' => $settings['trigger'],
			'reverse' => $settings['reverse'],
			'scroll_start'  => isset( $settings['animate_view']['sizes']['start'] ) ? $settings['animate_view']['sizes']['start'] : '0',
			'scroll_end'    => isset( $settings['animate_view']['sizes']['end'] ) ? $settings['animate_view']['sizes']['end'] : '100',
			'lottie_renderer' => $settings['lottie_renderer']
		];

		return wp_json_encode($attributes);
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$lottie_json = 'url' === $settings['source'] ? esc_url($settings['json_url']) : $settings['json_file']['url'];
		$lottie_link = 'url' === $settings['link_selection'] ? $settings['link']['url'] : get_permalink(get_page_by_path(isset($settings['existing_link']) ? $settings['existing_link'] : '#' ));

		

		if ( '' === $lottie_json ) {
			$lottie_json = TMPCODER_PLUGIN_URI .'assets/default.json';
		}

		$lottie_animation = 'yes' === $settings['link_switcher']
				? '<a href="'. esc_url($lottie_link) .'"><div class="tmpcoder-lottie-animations" data-settings="'. esc_attr($this->lottie_attributes($settings)) .'" data-json-url="'. esc_url($lottie_json) .'"></div></a>'
				: '<div class="tmpcoder-lottie-animations" data-settings="'. esc_attr($this->lottie_attributes($settings)) .'" data-json-url="'. esc_url($lottie_json) .'"></div>';

		echo '<div class="tmpcoder-lottie-animations-wrapper">';
			echo wp_kses($lottie_animation, tmpcoder_wp_kses_allowed_html());
		echo '</div>';
	}
}
