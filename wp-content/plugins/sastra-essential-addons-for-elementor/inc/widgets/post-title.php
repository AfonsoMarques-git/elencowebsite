<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Post_Title extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-post-title';
	}

	public function get_title() {
		return esc_html__( 'Post Title', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-site-title';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_post') ? [ 'tmpcoder-theme-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'post', 'title' ];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_title',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h1',
			]
		);

		$this->add_responsive_control(
            'post_title_align',
            [
                'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'label_block' => false,
                'options' => [
					'left'    => [
						'title' => __( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-title' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-post-title',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '30',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .tmpcoder-post-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-post-title',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-title' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if (tmpcoder_is_preview_mode()) {
			
			$title = get_the_title( tmpcoder_get_last_post_id() );

			echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .' class="tmpcoder-post-title">';
			echo esc_html($title);
			echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .'>';
		}
		else
		{

			echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .' class="tmpcoder-post-title">';
				echo esc_html(get_the_title());
			echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .'>';

		}
	}
	
}