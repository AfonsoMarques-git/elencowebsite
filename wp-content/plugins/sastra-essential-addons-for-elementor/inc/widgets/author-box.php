<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Author_Box extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-author-box';
	}

	public function get_title() {
		return esc_html__( 'Author Box', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-person';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_post') || tmpcoder_show_theme_buider_widget_on('type_archive') ? [ 'tmpcoder-theme-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'author', 'box', 'post', ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-author-box' ];
	}

	public function add_controls_group_author_name_links_to() {
		$this->add_control(
			'author_name_links_to',
			[
				'label' => esc_html__( 'Links To', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'Nothing', 'sastra-essential-addons-for-elementor' ),
					'posts' => esc_html__( 'Author Posts', 'sastra-essential-addons-for-elementor' ),
					'pro-ws' => esc_html__( 'Website (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'none',
				'condition' => [
					'author_name' => 'yes',
				]
			]
		);
	}

	public function add_controls_group_author_title_links_to() {
		$this->add_control(
			'author_title_links_to',
			[
				'label' => esc_html__( 'Links To', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'Nothing', 'sastra-essential-addons-for-elementor' ),
					'posts' => esc_html__( 'Author Posts', 'sastra-essential-addons-for-elementor' ),
					'pro-ws' => esc_html__( 'Website (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'none',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);
	}

	public function add_control_author_bio() {}

	public function add_section_style_bio() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_author_box',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'author_arrange',
			[
				'label' => esc_html__( 'Arrange', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'vertical',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'tmpcoder-author-box-arrange-'
			]
		);

		$this->add_control(
			'author_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'author_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name',
			[
				'label' => esc_html__( 'Show Name', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name_tag',
			[
				'label' => esc_html__( 'Name HTML Tag', 'sastra-essential-addons-for-elementor' ),
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
				'default' => 'h3',
				'condition' => [
					'author_name' => 'yes',
				]
			]
		);

		$this->add_controls_group_author_name_links_to();

		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'author-box', 'author_name_links_to', ['pro-ws'] );

		$this->add_control(
			'author_title',
			[
				'label' => esc_html__( 'Show Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_title_text',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Writer & Blogger',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_title_tag',
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
				'default' => 'h3',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);

		$this->add_controls_group_author_title_links_to();

		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'author-box', 'author_title_links_to', ['pro-ws'] );

		$this->add_control_author_bio();

		$this->add_control(
			'author_posts_link',
			[
				'label' => esc_html__( 'Show Author Posts Link', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'author_posts_link_text',
			[
				'label' => esc_html__( 'Posts Link Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'All Posts',
				'condition' => [
					'author_posts_link' => [ 'yes' ],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'author-box', [
			__('Link to Author Website.', 'sastra-essential-addons-for-elementor'),
			__('Show/Hide Author Biography (description).', 'sastra-essential-addons-for-elementor'),
		] );

		// Styles ====================
		// Section: Avatar -----------
		$this->start_controls_section(
			'section_style_avatar',
			[
				'label' => esc_html__( 'Avatar', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'avatar_align',
			[
				'label' => esc_html__( 'Center Image Vertically', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'align-self: center;',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-image' => '{{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Image Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 65,
				],
				'range' => [
					'px' => [
						'min' => 16,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-image' => 'width: {{SIZE}}px !important',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'avatar_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-author-box-arrange-vertical .tmpcoder-author-box-image' => 'margin-bottom: {{SIZE}}px',
					'body:not(.rtl) {{WRAPPER}}.tmpcoder-author-box-arrange-left .tmpcoder-author-box-image' => 'margin-right: {{SIZE}}px',
					'body.rtl {{WRAPPER}}.tmpcoder-author-box-arrange-left .tmpcoder-author-box-image' => 'margin-left: {{SIZE}}px',

					'body:not(.rtl) {{WRAPPER}}.tmpcoder-author-box-arrange-right .tmpcoder-author-box-image' => 'margin-left: {{SIZE}}px',
					'body.rtl {{WRAPPER}}.tmpcoder-author-box-arrange-right .tmpcoder-author-box-image' => 'margin-right: {{SIZE}}px',

				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'avatar_border',
				'fields_options' => [
					'border' => [
						'default' => '',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-author-box-image img',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'avatar_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'avatar_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-author-box-image',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Name -------------
		$this->start_controls_section(
			'section_style_name',
			[
				'label' => esc_html__( 'Name', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-name' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-author-box-name a' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-author-box-name',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '500',
					],
					'font_family'    => [
						'default' => 'Poppins',
					],
					'font_size' => [
						'default' => [
							'size' => '18',
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.2'
						]
					],
				]
			]
		);

		$this->add_responsive_control(
			'name_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-name' => 'margin-top: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'name_bot_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-name' => 'margin-bottom: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Title -------------
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
					'{{WRAPPER}} .tmpcoder-author-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-author-box-title a' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-author-box-title',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'    => [
						'default' => '500',
					],
					'font_family'    => [
						'default' => 'Poppins',
					],
					'font_size'      => [
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_responsive_control(
			'title_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-title' => 'margin-top: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_bot_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-title' => 'margin-bottom: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Biography --------
		$this->add_section_style_bio();

		// Styles ====================
		// Section: Author Posts Link
		$this->start_controls_section(
			'section_style_archive_link',
			[
				'label' => esc_html__( 'Author Posts Link', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_archive_link_style' );

		$this->start_controls_tab(
			'tab_grid_archive_link_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'archive_link_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'archive_link_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'archive_link_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'archive_link_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-author-box-btn'
			]
		);

		$this->add_control(
			'archive_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_archive_link_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'archive_link_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-author-box-btn:hover a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'archive_link_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'archive_link_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'archive_link_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 15,
					'bottom' => 5,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'archive_link_border_type',
			[
				'label' => esc_html__( 'Border Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'solid' => esc_html__( 'Solid', 'sastra-essential-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'sastra-essential-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'sastra-essential-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'sastra-essential-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'archive_link_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'archive_link_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'archive_link_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-author-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		if ( !tmpcoder_is_availble() ) {
			$settings['author_bio'] = 'yes';
			$settings['author_name_link_tab'] = '';
			$settings['author_title_link_tab'] = '';
		}

		// Get Author Info
		$object = get_queried_object();

		if (tmpcoder_is_preview_mode()) {
			$last_id = tmpcoder_get_last_post_id();
			$id = get_post_field( 'post_author', $last_id );
		}
		else
		{
			$id = $object->post_author;
		}

		$avatar = get_avatar( $id, 264 );
		$name = get_the_author_meta( 'display_name' ,$id);
		
		$title = $settings['author_title_text'];
		$biography = get_the_author_meta( 'description',$id );
		$website = get_the_author_meta( 'user_url' );
		$archive_url = get_author_posts_url( $id );
		$author_name_link = 'website' === $settings['author_name_links_to'] ? $website : $archive_url;
		$author_name_target = 'yes' === $settings['author_name_link_tab'] ? '_blank' : '_self';
		$author_name_has_website = 'website' === $settings['author_name_links_to'] && '' !== $website ? true : false;
		$author_title_link = 'website' === $settings['author_title_links_to'] ? $website : $archive_url;
		$author_title_target = 'yes' === $settings['author_title_link_tab'] ? '_blank' : '_self';
		$author_title_has_website = 'website' === $settings['author_title_links_to'] && '' !== $website ? true : false;

		// HTML
		echo '<div class="tmpcoder-author-box">';

			// Avatar
			if ( (!empty($settings['author_avatar']) && '' !== $settings['author_avatar']) && false !== $avatar ) {
				echo '<div class="tmpcoder-author-box-image">';
					if ( 'posts' === $settings['author_name_links_to'] || $author_name_has_website ) {
						echo '<a href="'. esc_url( $author_name_link ) .'" target="'. esc_attr($author_name_target) .'">'. wp_kses_post($avatar) .'</a>';
					} else {
						echo wp_kses_post($avatar);
					}
				echo '</div>';
			}

			// Wrap All Text Blocks
			echo '<div class="tmpcoder-author-box-text">';

			// Author Name
			if ( (!empty($settings['author_name']) && '' !== $settings['author_name']) && (!empty($name) && '' !== $name) ) {
				echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['author_name_tag']) ) .' class="tmpcoder-author-box-name">';
					if ( 'posts' === $settings['author_name_links_to'] || $author_name_has_website ) {
						echo '<a href="'. esc_url( $author_name_link ) .'" target="'. esc_attr($author_name_target) .'">'. esc_html($name) .'</a>';
					} else {
						echo esc_html($name);
					}
				echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['author_name_tag']) ) .'>';
			}

			// Author Title
			if ( '' !== $title && 'yes' === $settings['author_title'] ) {
				echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['author_title_tag']) ) .' class="tmpcoder-author-box-title">';
					if ( 'posts' === $settings['author_title_links_to'] || $author_title_has_website ) {
						echo '<a href="'. esc_url( $author_title_link ) .'" target="'. esc_attr($author_title_target) .'">'. wp_kses_post($title) .'</a>';
					} else {
						echo wp_kses_post($title);
					}
				echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['author_title_tag']) ) .'>';
			}

			// Author Biography
			if ( (!empty($settings['author_bio']) && '' !== $settings['author_bio']) && (!empty($biography) && '' !== $biography) ) {
				echo '<p class="tmpcoder-author-box-bio">'. wp_kses_post($biography) .'</p>';
			}

			// Author Posts Link
			if ( (!empty($settings['author_posts_link']) && '' !== $settings['author_posts_link']) && (!empty($name) && '' !== $name) ) {
				echo '<a href="'. esc_url( $archive_url ) .'" class="tmpcoder-author-box-btn">';
					echo esc_html( $settings['author_posts_link_text'] );
				echo '</a>';
			}

			echo '</div>'; // End .tmpcoder-author-box-text

		echo '</div>';
	}
	
}