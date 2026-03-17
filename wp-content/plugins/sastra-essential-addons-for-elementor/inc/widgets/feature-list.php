<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Feature_List extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-feature-list';
	}

	public function get_title() {
		return esc_html__( 'Feature List', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-editor-list-ul';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_script_depends() {
		return [ 'tmpcoder-feature-list'];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-feature-list'];
	}

	public function get_keywords() {
		return [ 'features', 'feature list', 'icon list' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }
    
	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_feature_list_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_responsive_control(
			'list_layout',
			[
				'label' => esc_html__( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'tablet_default' => 'left',
				'mobile_default' => 'center',
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
                'prefix_class' => 'tmpcoder-feature%s-list-',
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-item' => 'justify-content: {{VALUE}}',
				],
								
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					]
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-feature-list-left .tmpcoder-feature-list-item' => 'align-items: {{VALUE}}',
					'{{WRAPPER}}.tmpcoder-feature-list-right .tmpcoder-feature-list-item' => 'align-items: {{VALUE}}'
				],
				'condition' => [
					'list_layout!' => 'center', 
				]
			]
		);

		$this->add_control(
			'feature_list_content_alignment',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start'    => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'prefix_class' => 'tmpcoder-feature-list-align-',
				'render_type' => 'template',
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-item' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'list_layout' => 'center', 
				]
			]
		);

        $this->add_control(
            'feature_list_icon_shape',
            [
                'label'       => esc_html__( 'Icon Shape', 'sastra-essential-addons-for-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'square',
                'label_block' => false,
                'options'     => [
                    'square'  => esc_html__( 'Square', 'sastra-essential-addons-for-elementor' ),
                    'rhombus' => esc_html__( 'Rhombus', 'sastra-essential-addons-for-elementor' )
                ],
				'separator' => 'before',
				'prefix_class' => 'tmpcoder-feature-list-'
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'large',
			]
		);

		$this->add_control(
			'feature_list_title_tag',
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
				'default' => 'h2'
			]
		);

		$this->add_control(
			'feature_list_line',
			[
				'label' => esc_html__( 'Show Line', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'prefix_class' => 'tmpcoder-feature-list-line-',
				'separator' => 'before',
				'default' => 'yes',
				'condition' => [
					'list_layout' => ['left', 'right']
				]
			]
		);

		$this->add_responsive_control(
			'list_item_spacing_v',
			[
				'label' => esc_html__( 'Vertical Spacing', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'list_item_spacing_h',
			[
				'label' => esc_html__( 'Horizontal Spacing', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}}.tmpcoder-feature-list-left .tmpcoder-feature-list-icon-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-feature-list-right .tmpcoder-feature-list-icon-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				// 'condition' => [
				// 	'list_layout!' => 'center'
				// ]
			]
		);

		$this->add_responsive_control(
			'list_item_title_distance',
			[
				'label' => esc_html__( 'Title Distance', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'list_item_media_distance',
			[
				'label' => esc_html__( 'Media Distance', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'list_layout' => 'center'
				] 
			]
		);

        $this->end_controls_section();
        
		// Tab: Content ==============
		// Section: Content ----------
        $this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs(
			'list_tabs'
		);

		$repeater->start_controls_tab(
			'content_tab',
			[
				'label' => __( 'Content', 'sastra-essential-addons-for-elementor' ),
			]
		);

        $repeater->add_control(
            'feature_list_media_type',
            [
                'label'       => esc_html__( 'Media Type', 'sastra-essential-addons-for-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'icon' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
                    'image' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' )
                ],
                'default'     => 'icon',
                'label_block' => false,
            ]
        );

		$repeater->add_control(
			'list_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'label_block' => false,
                'skin' => 'inline',
				'condition' => [
					'feature_list_media_type' => 'icon'
				]
			]
		);

		$repeater->add_control(
			'list_image',
			[
				'label' => esc_html__( 'Choose Image', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'skin' => 'inline',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'feature_list_media_type' => 'image'
				]
			]
		);

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'List Title' , 'sastra-essential-addons-for-elementor' ),
				'separator' => 'before',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_title_url',
			[
				'label' => esc_html__( 'Title Link', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'List Content', 'sastra-essential-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Type your description here', 'sastra-essential-addons-for-elementor' ),
				'rows' => 10,
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'styles_tab',
			[
				'label' => __( 'Style', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$repeater->add_control(
			'feature_list_custom_styles',
			[
				'label' => esc_html__( 'Custom Styles', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$repeater->add_control(
			'feature_list_title_color_unique',
			[
				'label' => esc_html__( 'Title Color', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-feature-list-title a.tmpcoder-feature-list-url' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-feature-list-title' => 'color: {{VALUE}}'
				],
				'condition' => [
					'feature_list_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'feature_list_icon_color_unique',
			[
				'label'  => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-feature-list-icon-inner-wrap i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-feature-list-icon-inner-wrap svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'feature_list_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'feature_list_icon_wrapper_bg_color_unique',
			[
				'label'  => esc_html__( 'Icon Bg Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#966CE6',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-feature-list-icon-inner-wrap' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'feature_list_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'feature_list_icon_wrapper_border_color_unique',
			[
				'label'  => esc_html__( 'Icon Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#046bd2',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-feature-list-icon-inner-wrap' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'feature_list_custom_styles' => 'yes'
				]
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Feature List', 'sastra-essential-addons-for-elementor' ),
						'list_content' => esc_html__( 'Add multiple feature items, set different icons or images for each feature and also give custom links if needed.', 'sastra-essential-addons-for-elementor' ),
						'list_icon' => [
							'value' => 'fas fa-rocket',
							'library' => 'solid'
						],
					],
					[
						'list_title' => esc_html__( 'Key Features', 'sastra-essential-addons-for-elementor' ),
						'list_content' => esc_html__( 'Choose your style from three different layouts and two unique icon background shapes.', 'sastra-essential-addons-for-elementor' ),
						'list_icon' => [
							'value' => 'far fa-flag',
							'library' => 'solid'
						],
						'feature_list_custom_styles' => 'yes',
						'feature_list_icon_wrapper_bg_color_unique' => '#966CE6'
					],
					[
						'list_title' => esc_html__( 'Connector Line', 'sastra-essential-addons-for-elementor' ),
						'list_content' => esc_html__( 'Show a connector line between each icon, changes its color and style to fit your unique design. ', 'sastra-essential-addons-for-elementor' ),
						'list_icon' => [
							'value' => 'fas fa-grip-lines-vertical',
							'library' => 'solid'
						],
					],
					[
						'list_title' => esc_html__( 'Custom Styles', 'sastra-essential-addons-for-elementor' ),
						'list_content' => esc_html__( 'Easily customize every aspect of your list from widget styles but also you can give custom colors to each item as well.', 'sastra-essential-addons-for-elementor' ),
						'list_icon' => [
							'value' => 'fas fa-paint-brush',
							'library' => 'solid'
						],
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Tab: STYLE ==============
		// Section: Icon ----------
		$this->start_controls_section(
			'section_feature_list_icon_styles',
			[
				'label' => esc_html__( 'Media', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feature_list_icon_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-icon-inner-wrap i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-feature-list-icon-inner-wrap svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'feature_list_icon_wrapper_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#046bd2',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-icon-inner-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'feature_list_icon_wrapper_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-icon-inner-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'feature_list_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-icon-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-feature-list-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'feature_list_icon_wrapper_size',
			[
				'label' => esc_html__( 'Box Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 75,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-icon-inner-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
				]
			]
		);

		$this->add_responsive_control(
			'feature_list_icon_vertical_offset',
			[
				'label' => esc_html__( 'Adjust Vertical Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'range' => [
					'px' => [
						'min' => -20,
						'max' => 20,
					],
					'em' => [
						'min' => -2,
						'max' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-icon-wrap' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

		$this->add_control(
			'feature_list_icon_wrapper_border_type',
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
					'{{WRAPPER}} .tmpcoder-feature-list-icon-inner-wrap' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feature_list_icon_wrapper_border_width',
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
					'{{WRAPPER}} .tmpcoder-feature-list-icon-inner-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'feature_list_icon_wrapper_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'feature_list_icon_wrapper_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-icon-inner-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();
		

		// Tab: STYLE ==============
		// Section: Line ----------
		$this->start_controls_section(
			'section_feature_list_line_styles',
			[
				'label' => esc_html__( 'Line', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'feature_list_line' => 'yes'
				]
			]
		);

		$this->add_control(
			'feature_list_line_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#046bd2',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-line' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'feature_list_line_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-line' => 'border-left-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

        $this->add_control(
            'feature_list_line_border_type',
            [
                'label'       => esc_html__( 'Type', 'sastra-essential-addons-for-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'solid',
                'label_block' => false,
                'options'     => [
                    'solid'  => esc_html__( 'Solid', 'sastra-essential-addons-for-elementor' ),
                    'dashed' => esc_html__( 'Dashed', 'sastra-essential-addons-for-elementor' ),
                    'dotted' => esc_html__( 'Dotted', 'sastra-essential-addons-for-elementor' ),
                ],
                'selectors'   => [
                    '{{WRAPPER}} .tmpcoder-feature-list-line' => 'border-left-style: {{VALUE}};',
                ]
            ]
        );

		$this->end_controls_section();

		// Tab: STYLE ==============
		// Section: Title & Description ----------
		$this->start_controls_section(
			'section_feature_list_title_&_description_styles',
			[
				'label' => esc_html__( 'Title & Description', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'feature_list_title_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-feature-list-title a.tmpcoder-feature-list-url' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'feature_list_title_hover_color',
			[
				'label'  => esc_html__( 'Hover Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-title:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-feature-list-title a.tmpcoder-feature-list-url:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'feature_list_title',
				'selector' => '{{WRAPPER}} .tmpcoder-feature-list-title',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '500',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '20',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'description_heading',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'feature_list_description_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6E6B6B',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-feature-list-description,
					.tmpcoder-feature-list-description a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'feature_list_description',
				'selector' => '{{WRAPPER}} .tmpcoder-feature-list-description',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

        if ( $settings['list'] ) {
			$count_items = 0;
			echo '<div class="tmpcoder-feature-list-wrap">';
                echo '<ul class="tmpcoder-feature-list">';
                    foreach (  $settings['list'] as $item ) {
						$this->add_link_attributes( 'list_title_url'. $count_items, $item['list_title_url'] );
                        echo '<li class="tmpcoder-feature-list-item elementor-repeater-item-' . esc_attr( $item['_id'] ) .'">';
							echo '<div class="tmpcoder-feature-list-icon-wrap">';
							echo '<span class="tmpcoder-feature-list-line"></span>';
								echo '<div class="tmpcoder-feature-list-icon-inner-wrap">';
									if ( 'icon' === $item['feature_list_media_type'] ) {
										\Elementor\Icons_Manager::render_icon( $item['list_icon'], [ 'aria-hidden' => 'true' ] );
									} else {
										$settings[ 'layout_image_crop' ] = ['id' => $item['list_image']['id']];
										$main_thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'layout_image_crop' );
										echo wp_kses_post($main_thumbnail_html);
									}
								echo '</div>';
                            echo '</div>';
                            echo '<div class="tmpcoder-feature-list-content-wrap">';
								if ( empty($item['list_title_url']) ) {
									echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['feature_list_title_tag']) ) .' class="tmpcoder-feature-list-title">'. wp_kses_post($item['list_title']) .'</'. esc_attr( tmpcoder_validate_html_tag($settings['feature_list_title_tag']) ) .'>';
								} else {
									echo wp_kses_post('<'. tmpcoder_validate_html_tag($settings['feature_list_title_tag']) .' class="tmpcoder-feature-list-title"><a class="tmpcoder-feature-list-url" '. $this->get_render_attribute_string( 'list_title_url'. esc_attr($count_items) ) .'>'. wp_kses_post($item['list_title']) .'</a></'. tmpcoder_validate_html_tag($settings['feature_list_title_tag']) .'>') ;
								}
                                echo '<p class="tmpcoder-feature-list-description">'. wp_kses_post($item['list_content']) .'</p>';
                            echo '</div>';
                        echo '</li>';
						$count_items++;
                    }
                echo '</ul>';
			echo '</div>';
		}
    }
}