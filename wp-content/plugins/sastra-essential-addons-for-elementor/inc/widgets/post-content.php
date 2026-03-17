<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Post_Content extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-post-content';
	}

	public function get_title() {
		return esc_html__( 'Post Content', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-post-content';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_post') ? [ 'tmpcoder-theme-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'post', 'content' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_responsive_control(
            'post_content_align',
            [
                'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
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
					'justify' => [
						'title' => __( 'Justified', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-content' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_link_color',
			[
				'label'  => esc_html__( 'Link Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-content a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_link_hover_color',
			[
				'label'  => esc_html__( 'Link Hover Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-content a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-post-content',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'title_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-content a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		echo '<div class="tmpcoder-post-content">';
			
			if (tmpcoder_is_preview_mode()) {
				
				$get_the_content = get_the_content('','',tmpcoder_get_last_post_id());

				echo wp_kses($get_the_content, tmpcoder_wp_kses_allowed_html());

			} else {

				the_content();
			}
		echo '</div>';
	}
}