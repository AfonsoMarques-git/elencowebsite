<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Post_Comments extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-post-comments';
	}

	public function get_title() {
		return esc_html__( 'Post Comments', 'sastra-essential-addons-for-elementor' );
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_post') ? [ 'tmpcoder-theme-builder-widgets'] : [];
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-comments';
	}

	public function get_keywords() {
		return [ 'comments', 'post' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-post-comments'];
	}

	public function add_control_comments_avatar_size() {}

	public function add_control_avatar_gutter() {
		$this->add_responsive_control(
			'avatar_gutter',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .tmpcoder-comment-meta,body:not(.rtl) {{WRAPPER}}  .tmpcoder-comment-content' => 'margin-left: calc(60px + {{SIZE}}{{UNIT}});',
					'body.rtl {{WRAPPER}} .tmpcoder-comment-meta, body.rtl .tmpcoder-comment-content' => 'margin-right: calc(60px + {{SIZE}}{{UNIT}});',

					'body:not(.rtl) {{WRAPPER}}.tmpcoder-comment-reply-separate .tmpcoder-comment-reply' => 'margin-left: calc(60px + {{SIZE}}{{UNIT}});',
					'body.rtl {{WRAPPER}}.tmpcoder-comment-reply-separate .tmpcoder-comment-reply' => 'margin-right: calc(60px + {{SIZE}}{{UNIT}});',
				],
				'separator' => 'after',
			]
		);
	}

	public function add_control_comments_form_layout() {
		$this->add_control(
			'comments_form_layout',
			[
				'label' => esc_html__( 'Select Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-5',
				'options' => [
					'pro-s1' => esc_html__( 'Style 1 (Pro)', 'sastra-essential-addons-for-elementor' ),
					'style-2' => esc_html__( 'Style 2', 'sastra-essential-addons-for-elementor' ),
					'pro-s3' => esc_html__( 'Style 3 (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-s4' => esc_html__( 'Style 4 (Pro)', 'sastra-essential-addons-for-elementor' ),
					'style-5' => esc_html__( 'Style 5', 'sastra-essential-addons-for-elementor' ),
					'pro-s6' => esc_html__( 'Style 6 (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'separator' => 'before'
			]
		);
	}

	public function add_control_comment_form_placeholders() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_comments_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'section_title',
			[
				'label' => esc_html__( 'Show Section Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'comments_text_1',
			[
				'label' => esc_html__( 'One Comment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comment',
				'condition' => [
					'section_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'comments_text_2',
			[
				'label' => esc_html__( 'Multiple Comments', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comments',
				'condition' => [
					'section_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'comments_lists',
			[
				'label' => esc_html__( 'Show Comments Lists', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comments_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comments_date_time',
			[
				'label' => esc_html__( 'Show Date & Time', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control_comments_avatar_size();

		$this->add_control(
			'comments_reply_location',
			[
				'label' => esc_html__( 'Reply Location', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'separate',
				'options' => [
					'inline' => esc_html__( 'Inline', 'sastra-essential-addons-for-elementor' ),
					'separate' => esc_html__( 'Separate', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-comment-reply-',
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'comments_navigation_align',
			[
				'label' => __( 'Navigation Align', 'sastra-essential-addons-for-elementor' ),
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
				'default' => 'center',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comments_navigation_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'selectors_dictionary' => [
					'' => 'display: none;',
					'yes' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation a.prev' => '{{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comments-navigation a.next' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'comments_navigation_numbers',
			[
				'label' => esc_html__( 'Show Numbers', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'selectors_dictionary' => [
					'' => 'display: none;',
					'yes' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation .page-numbers:not(.prev):not(.next)' => '{{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Comment Form -----
		$this->start_controls_section(
			'section_comment_form',
			[
				'label' => esc_html__( 'Comment Form', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'comment_form_title',
			[
				'label' => esc_html__( 'Section Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Leave a Reply',
				'condition' => [
					'section_title' => 'yes'
				]
			]
		);

		$this->add_control_comments_form_layout();

		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'post-comments', 'comments_form_layout', ['pro-s1','pro-s3','pro-s4','pro-s6'] );

		$this->add_control(
			'comment_form_labels',
			[
				'label' => esc_html__( 'Show Labels', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control_comment_form_placeholders();

		$this->add_control(
			'comment_form_website',
			[
				'label' => esc_html__( 'Show Website Field', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_form_submit_text',
			[
				'label' => esc_html__( 'Submit Button Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Submit',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'reply_text',
			[
				'label' => esc_html__( 'Reply Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Reply',
			]
		);	

		$this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'post-comments', [
			'6 different Comment Form Layouts.',
			'Custom comment author Avatar Size.',
			'Comment Form - Show/Hide Input Placeholder Text (Set Placeholder Text instead of Input Labels).'
		] );

		// Styles ====================
		// Section: Section Title ----
		$this->start_controls_section(
			'section_style_section_title',
			[
				'label' => esc_html__( 'Section Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'section_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
            'section_title_align',
            [
                'label' => esc_html__( 'Align', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-wrap > h3' => 'text-align: {{VALUE}}',
				],
				'separator' => 'after'
            ]
        );

		$this->add_control(
			'section_title_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-wrap > h3' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_title_bd_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-wrap > h3' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'section_title_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-comments-wrap > h3',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '17',
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.5'
						]
					],
				]
			]
		);

		$this->add_control(
			'section_title_bd_type',
			[
				'label' => esc_html__( 'Border Style', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}} .tmpcoder-comments-wrap > h3' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'section_title_bd_width',
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
					'{{WRAPPER}} .tmpcoder-comments-wrap > h3' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'section_title_bd_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'section_title_space',
			[
				'label' => esc_html__( 'Bottom Space', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-wrap > h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Comments ---------
		$this->start_controls_section(
			'section_style_comments',
			[
				'label' => esc_html__( 'Comments', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'comment_odd_color',
			[
				'label' => esc_html__( 'Odd Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fcfcfc',
				'selectors' => [
					'{{WRAPPER}} .even .tmpcoder-post-comment' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_even_color',
			[
				'label' => esc_html__( 'Even Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fcfcfc',
				'selectors' => [
					'{{WRAPPER}} .odd .tmpcoder-post-comment' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_author_color',
			[
				'label' => esc_html__( 'By Post Author Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EFEFEF',
				'selectors' => [
					'{{WRAPPER}} .bypostauthor .tmpcoder-post-comment' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-comment' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comment_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-post-comment',
			]
		);

		$this->add_responsive_control(
			'comment_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_border_type',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-comment' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_border_width',
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
					'{{WRAPPER}} .tmpcoder-post-comment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'comment_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'comment_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-comment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'comment_spacing',
			[
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-comment' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comment_indent',
			[
				'label' => esc_html__( 'Nested Indent', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 24,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-list .children' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Avatar -----------
		$this->start_controls_section(
			'section_style_avatar',
			[
				'label' => esc_html__( 'Avatar', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'comments_avatar' => 'yes',
				],
			]
		);

		$this->add_control_avatar_gutter();

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
				'selector' => '{{WRAPPER}} .tmpcoder-comment-avatar',
			]
		);

		$this->add_control(
			'avatar_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Nickname ---------
		$this->start_controls_section(
			'section_style_nickname',
			[
				'label' => esc_html__( 'Nickname', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_nickname_style' );

		$this->start_controls_tab(
			'tab_nickname_normal',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'nickname_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-author span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comment-author a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'nickname_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-comment-author',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'nickname_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-author a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nickname_hover',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'nickname_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-author a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'nickname_space',
			[
				'label' => esc_html__( 'Bottom Space', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-author' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Date and Time ----
		$this->start_controls_section(
			'section_style_metadata',
			[
				'label' => esc_html__( 'Date and Time', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'metadata_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9B9B9B',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-metadata' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comment-metadata a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comment-reply:before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'metadata_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-comment-metadata',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Open Sans',
					],
					'font_size'      => [
						'default'    => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'metadata_space',
			[
				'label' => esc_html__( 'Bottom Space', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-metadata' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content (Comment Text)', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_link_color',
			[
				'label'  => esc_html__( 'Link Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-content a' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .tmpcoder-comment-content',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Open Sans',
					],
					'font_weight'    => [
						'default' => '400',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Reply Link -------
		$this->start_controls_section(
			'section_style_reply_link',
			[
				'label' => esc_html__( 'Reply Link', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_reply_link_style' );

		$this->start_controls_tab(
			'tab_reply_link_normal',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'reply_link_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'reply_link_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FCFCFC',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'reply_link_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'reply_link_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-comment-reply a',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'reply_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.6,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_reply_link_hover',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'reply_link_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'reply_link_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'reply_link_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'reply_link_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'reply_link_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'reply_link_border_type',
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
					'{{WRAPPER}} .tmpcoder-comment-reply a' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'reply_link_border_width',
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
					'{{WRAPPER}} .tmpcoder-comment-reply a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'reply_link_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'reply_link_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'reply_link_align',
			[
				'label' => __( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'right',
				'prefix_class' => 'tmpcoder-comment-reply-align-',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Navigation -------
		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => esc_html__( 'Navigation', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_navigation_style' );

		$this->start_controls_tab(
			'tab_navigation_normal',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'navigation_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comments-navigation span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'navigation_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comments-navigation span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'navigation_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comments-navigation span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'navigation_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-comments-navigation a, {{WRAPPER}} .tmpcoder-comments-navigation span',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'navigation_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_navigation_hover',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'navigation_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comments-navigation span.current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'navigation_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comments-navigation span.current' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'navigation_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comments-navigation span.current' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'navigation_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-comments-navigation span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'navigation_border_type',
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
					'{{WRAPPER}} .tmpcoder-comments-navigation a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-comments-navigation span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'navigation_border_width',
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
					'{{WRAPPER}} .tmpcoder-comments-navigation a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-comments-navigation span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'navigation_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'navigation_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comments-navigation a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-comments-navigation span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();	

		// Styles ====================
		// Section: Inputs -----------
		$this->start_controls_section(
			'section_style_inputs',
			[
				'label' => esc_html__( 'Fields (Input, Textarea)', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_responsive_control(
			'input_width',
			[
				'label' => esc_html__( 'Input Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 500,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form-fields' => 'width: {{SIZE}}{{UNIT}} !important;',
					
					],
				'separator' => 'after'
			]
		);
		$this->add_responsive_control(
			'textarea_width',
			[
				'label' => esc_html__( 'Textarea Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 500,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form-text' => 'width: {{SIZE}}{{UNIT}};',
					],
				
			]
		);

		$this->end_controls_section();
		

		// Styles ====================
		// Section: Comment Form Title
		$this->start_controls_section(
			'section_style_tmpcoder_title',
			[
				'label' => esc_html__( 'Comment Form Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'tmpcoder_title_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tmpcoder_title_bd_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply-title' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tmpcoder_title_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-comment-reply-title',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Raleway',
					],
					'font_weight'    => [
						'default' => '500',
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.5'
						]
					],
					'font_size'      => [
						'default'    => [
							'size' => '17',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'tmpcoder_title_bd_type',
			[
				'label' => esc_html__( 'Border Style', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}} .tmpcoder-comment-reply-title' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'tmpcoder_title_bd_width',
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
					'{{WRAPPER}} .tmpcoder-comment-reply-title' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tmpcoder_title_bd_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tmpcoder_title_top_space',
			[
				'label' => esc_html__( 'Top Space', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 85,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tmpcoder_title_bottom_space',
			[
				'label' => esc_html__( 'Bottom Space', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
            'tmpcoder_title_align',
            [
                'label' => esc_html__( 'Align', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-reply-title' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section();

		// Styles ====================
		// Section: Comment Form -----
		$this->start_controls_section(
			'section_style_comment_form',
			[
				'label' => esc_html__( 'Comment Form', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_comment_form_style' );

		$this->start_controls_tab(
			'tab_comment_form_normal',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'comment_form_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-comment-form textarea' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-comment-form label' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comment-form .logged-in-as a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-comment-form .logged-in-as .required-field-message' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_form_placeholder_color',
			[
				'label'  => esc_html__( 'Placeholder Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#B8B8B8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .tmpcoder-comment-form textarea::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]::-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-comment-form textarea::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
				'condition' => [
					'comment_form_placeholders' => 'yes'
				]
			]
		);

		$this->add_control(
			'comment_form_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comment-form textarea' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'comment_form_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DBDBDB',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comment-form textarea' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comment_form_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-comment-form label, {{WRAPPER}} .tmpcoder-comment-form input[type=text], {{WRAPPER}} .tmpcoder-comment-form textarea, {{WRAPPER}} .tmpcoder-comment-form .logged-in-as',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'comment_form_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.6,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]::placeholder' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]::-ms-input-placeholder' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-comment-form textarea' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_comment_form_hover',
			[
				'label' => __( 'Focus', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'comment_form_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-comment-form textarea:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_form_placeholder_color_hr',
			[
				'label'  => esc_html__( 'Placeholder Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#B8B8B8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]:focus::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .tmpcoder-comment-form textarea:focus::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]:focus::-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-comment-form textarea:focus::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
				'condition' => [
					'comment_form_placeholders' => 'yes'
				]
			]
		);

		$this->add_control(
			'comment_form_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]:focus' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-comment-form textarea:focus' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'comment_form_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-comment-form textarea:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'comment_form_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-comment-form textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'comment_form_border_type',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-comment-form textarea' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_form_border_width',
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
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-comment-form textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'comment_form_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'comment_form_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-comment-form input[type=text]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-comment-form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'comment_form_gutter',
			[
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .tmpcoder-comment-form-author' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',

					'body.rtl {{WRAPPER}} .tmpcoder-comment-form-author' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right:0;',

					'{{WRAPPER}} .tmpcoder-comment-form-email' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					
					'body:not(.rtl) {{WRAPPER}} .tmpcoder-comment-form-url' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .tmpcoder-comment-form-url' => 'margin-right: {{SIZE}}{{UNIT}};',

					'{{WRAPPER}} .tmpcoder-comment-form-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Submit Button ----
		$this->start_controls_section(
			'section_style_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_submit_button_style' );

		$this->start_controls_tab(
			'tab_submit_button_normal',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'submit_button_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-submit-comment' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-submit-comment' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'submit_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DBDBDB',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-submit-comment' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submit_button_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-submit-comment',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.5'
						]
					],
					'font_size'      => [
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'submit_button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-submit-comment' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submit_button_hover',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'submit_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-submit-comment:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4C48BD',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-submit-comment:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'submit_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-submit-comment:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'submit_button_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 45,
					'bottom' => 10,
					'left' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-submit-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'submit_button_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 25,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-submit-comment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'submit_button_border_type',
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
					'{{WRAPPER}} .tmpcoder-submit-comment' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'submit_button_border_width',
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
					'{{WRAPPER}} .tmpcoder-submit-comment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'submit_button_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'submit_button_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-submit-comment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'submit_button_align',
            [
                'label' => esc_html__( 'Align', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} .form-submit' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section();

	}

	// Outputs a comment in the HTML5 format
	public static function html5_comment( $comment, $args, $depth ) {
		// Get Settings
		$this_widget = $GLOBALS['tmpcoder_post_comments_widget'];
		$settings = $this_widget->get_settings();

		$settings['comments_avatar_size'] = 60;

		// Class, URL, Name
		$comment_class = implode( ' ', get_comment_class( $comment->has_children ? 'parent' : '', $comment ) );
		$author_url = get_comment_author_url( $comment );
		$author_name = get_comment_author( $comment );

		// Comment HTML
		echo '<li id="comment-'. esc_attr(get_comment_ID()) .'" class="'. esc_attr( $comment_class ) .'">';
		echo '<article class="tmpcoder-post-comment elementor-clearfix">';

			// Comment Avatar
			if ( 'yes' === $settings['comments_avatar'] ) {
				echo '<div class="tmpcoder-comment-avatar">';
					echo get_avatar( $comment, $settings['comments_avatar_size'] );
				echo '</div>';
			}

			// Comment Meta
			echo '<div class="tmpcoder-comment-meta">';
				// Comment Author
				echo '<div class="tmpcoder-comment-author">';
					if ( '' === $author_url ) {
						echo '<span>'. esc_html( $author_name ) .'</span>';
					} else {
						echo '<a href="'. esc_url( $author_url ) .'">'. esc_html( $author_name ) .'</a>';
					}
				echo '</div>';

				// Comment Metadata
				echo '<div class="tmpcoder-comment-metadata elementor-clearfix">';
					// Date and Time
					if ( 'yes' === $settings['comments_date_time'] ) {

						echo '<span>'. esc_html(get_comment_date( '', $comment )) . esc_html__( ' at ', 'sastra-essential-addons-for-elementor' ) . esc_html(get_comment_time()) .'</span>';

						// Edit Link
						edit_comment_link( esc_html__( 'Edit', 'sastra-essential-addons-for-elementor' ), ' | ', '' );
					}else{

						// Edit Link
						edit_comment_link( esc_html__( 'Edit', 'sastra-essential-addons-for-elementor' ) );
					}


					// Reply Button
					if ( 'inline' === $settings['comments_reply_location'] ) {
						comment_reply_link(
							array_merge( $args, [
								'depth' => $depth,
								'max_depth' => $args['max_depth'],
								'before' => '<div class="tmpcoder-comment-reply">',
								'after' => '</div>',
							] )
						);
					}

					// Moderation
					if ( '0' == $comment->comment_approved ) {
						echo '<p>'. esc_html__( 'Your comment is awaiting moderation.', 'sastra-essential-addons-for-elementor' ) .'</p>';
					}
				echo '</div>';
			echo '</div>';

			if ( 'yes' === $settings['comments_lists'] ) {
				
				// Comment Content
				echo '<div class="tmpcoder-comment-content">';
					comment_text( $comment );
				echo '</div>';
			}

			// Reply Button
			if ( 'separate' === $settings['comments_reply_location'] ) {
				comment_reply_link(
					array_merge( $args, [
						'depth' => $depth,
						'max_depth' => $args['max_depth'],
						'before' => '<div class="tmpcoder-comment-reply">',
						'after' => '</div>',
					] )
				);
			}

		echo '</article>';
		echo '</li>';

	}

	function tmpcoder_comment_reply_text( $link ) {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		$reply_text = "Reply";

		if (isset($settings['reply_text']) && !empty($settings['reply_text']) && $settings['reply_text'] != '') {
			$reply_text = $settings['reply_text'];
		}

		$link = str_replace( '>Reply<', '>'.$reply_text.'<', $link );
		return $link;
	}

	protected function render() {
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Temp log out user
		if ( $is_editor ) {
			$store_current_user = wp_get_current_user()->ID;
			wp_set_current_user( 0 );
		}


		//  Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		$GLOBALS['tmpcoder_post_comments_widget'] = $this;

		$last_id = '';
		
		if (tmpcoder_is_preview_mode()) {

			$last_id = tmpcoder_get_last_post_id();
			if ( ! comments_open( tmpcoder_get_last_post_id() ) ) {
				return;
			}
		}
		else
		{
			if ( ! comments_open( get_the_ID() ) ) {
				return;
			}
		}

		// Comments Count
		if (tmpcoder_is_preview_mode()) {

			$count = get_comments_number( tmpcoder_get_last_post_id() );
		}
		else
		{
			$count = get_comments_number( get_the_ID() );
		}

		add_filter( 'comment_reply_link', [$this, 'tmpcoder_comment_reply_text'] );

		// Comments Wrapper
		echo '<div class="tmpcoder-comments-wrap" id="comments">';

			// If comments are open or we have at least one comment
			if ( $count ) {

				if ( $count == 1 ) {
					$text = $count .' '. $settings['comments_text_1'];
				} elseif ( $count > 1 ) {
					$text = $count .' '. $settings['comments_text_2'];
				}

				// Comments
				if ( 'yes' === $settings['section_title'] ) {
					echo '<h3> '. esc_html($text) .'</h3>';
				}

				// Get Post Comments

				if (tmpcoder_is_preview_mode()) {

					$last_id = tmpcoder_get_last_post_id();
					$get_comments = get_comments( [ 'post_id' => $last_id ] );
				}
				else
				{
					$get_comments = get_comments( [ 'post_id' => get_the_ID() ] );
				}

				// Comments List HTML
				echo '<ul class="tmpcoder-comments-list">';
					wp_list_comments( [ 'callback' => [$this, 'html5_comment'] ], $get_comments );
				echo '</ul>';

				unset( $GLOBALS['tmpcoder_post_comments_widget'] );

				// Comments Navigation
				if ( get_comment_pages_count($get_comments) > 1 && get_option( 'page_comments' ) ) {
					echo '<div class="tmpcoder-comments-navigation tmpcoder-comments-navigation-'. esc_html($settings['comments_navigation_align']) .'">';
						paginate_comments_links([
							'base' => add_query_arg( 'cpage', '%#%' ),
							'format' => '',
							'total' => get_comment_pages_count($get_comments),
							'echo' => true,
							'add_fragment' => '#comments',
							'prev_text' => '<i class="eicon-arrow-left"></i> '. esc_html__( 'Previous', 'sastra-essential-addons-for-elementor' ),
							'next_text' => esc_html__( 'Next', 'sastra-essential-addons-for-elementor' ) .' <i class="eicon-arrow-right"></i>',
						]);
					echo '</div>';
				}
			}

			// Comment Form: Author, Email and Website Fields
			add_filter( 'comment_form_default_fields', function( $defaults ) {
				$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
				$author_label = $email_label = $url_label = '';
				$author_ph = $email_ph = $url_ph = '';
				$req = get_option( 'require_name_email' );

				// Labels
				if ( 'yes' === $settings['comment_form_labels'] ) {
					$author_label = '<label>'. esc_html__( 'Name', 'sastra-essential-addons-for-elementor' ) . ($req ? '<span>*</span>' : '') .'</label>';
					$email_label = '<label>'. esc_html__( 'Email', 'sastra-essential-addons-for-elementor' ) . ($req ? '<span>*</span>' : '') .'</label>';
					$url_label = '<label>'. esc_html__( 'Website', 'sastra-essential-addons-for-elementor' ) .'</label>';					
				}

				if ( !tmpcoder_is_availble() ) {
					$settings['comment_form_placeholders'] = '';
				}

				// Placeholders
				if ( 'yes' === $settings['comment_form_placeholders'] ) {
					$author_ph = esc_html__( 'Name', 'sastra-essential-addons-for-elementor' ) . ($req ? '*' : '');
					$email_ph = esc_html__( 'Email', 'sastra-essential-addons-for-elementor' ) . ($req ? '*' : '');
					$url_ph = esc_html__( 'Website', 'sastra-essential-addons-for-elementor' );
				}

				$fields = [
					// name
					'author' => '<div class="tmpcoder-comment-form-fields"> <div class="tmpcoder-comment-form-author">'. $author_label .
					'<input type="text" name="author" placeholder="'. esc_attr($author_ph) .'"/></div>',
					// Email
					'email' => '<div class="tmpcoder-comment-form-email">'. $email_label .
					'<input type="text" name="email" placeholder="'. esc_attr($email_ph) .'"/></div>',
					// Website
					'url' => '<div class="tmpcoder-comment-form-url">'. $url_label .
					'<input type="text" name="url" placeholder="'. esc_url($url_ph) .'"/></div></div>',
				];

				// Remove Website Field
				if ( empty($settings['comment_form_website']) || '' === $settings['comment_form_website'] ) {
					$fields['url'] = '</div>';
				}

				return $fields;
			} );

			// Comment Form Defaults
			add_filter( 'comment_form_defaults', function( $defaults ) {
				$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
				$text_label = $text_ph = '';
				$req = get_option( 'require_name_email' );

				// Text Input Label
				if ( 'yes' === $settings['comment_form_labels'] ) {
					$text_label = '<label>'. esc_html__( 'Message', 'sastra-essential-addons-for-elementor' ) . ($req ? '<span>*</span>' : '') .'</label>';
				}

				if ( !tmpcoder_is_availble() ) {
					$settings['comment_form_placeholders'] = '';
				}

				// Text Input Placeholder
				if ( 'yes' === $settings['comment_form_placeholders'] ) {
					$text_ph = esc_html__( 'Message', 'sastra-essential-addons-for-elementor' ) . ($req ? '*' : '');
				}

				// Form
				$defaults['id_form'] = 'tmpcoder-comment-form';
				$defaults['class_form'] = 'tmpcoder-comment-form tmpcoder-cf-'. esc_attr($settings['comments_form_layout']);

				// No Website Filed Class
				if ( empty($settings['comment_form_website']) || '' === $settings['comment_form_website'] ) {
					$defaults['class_form'] .= ' tmpcoder-cf-no-url';
				}

				// Title
				$defaults['title_reply'] = $settings['comment_form_title'];
				$defaults['title_reply_before'] = '<h3 id="tmpcoder-reply-title" class="tmpcoder-comment-reply-title">';
				$defaults['title_reply_after'] = '</h3>';

				// Text Field
				$defaults['comment_field']  = '<div class="tmpcoder-comment-form-text">'. $text_label;
				$defaults['comment_field'] .= '<textarea name="comment" placeholder="'. esc_attr($text_ph) .'" cols="45" rows="8" maxlength="65525"></textarea>';
				$defaults['comment_field'] .= '</div>';

				// Submit Button
				$defaults['id_submit'] = 'tmpcoder-submit-comment';
				$defaults['class_submit'] = 'tmpcoder-submit-comment';
				$defaults['label_submit'] = $settings['comment_form_submit_text'];

				return $defaults;
			} );

			// Form Output

			comment_form([], $last_id);

		echo '</div>'; // End .tmpcoder-comments-wrap


		// Logged-in user back.
		if ( $is_editor ) {
			wp_set_current_user( $store_current_user );
		}
	}
	
}