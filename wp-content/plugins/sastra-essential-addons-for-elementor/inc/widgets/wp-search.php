<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class TMPCODER_Search extends Widget_Base {
        
    public function get_name() {
        return 'tmpcoder-search';
    }

    public function get_title() {
        return esc_html__( 'Search Form (AJAX)', 'sastra-essential-addons-for-elementor' );
    }

    public function get_icon() {
        return 'tmpcoder-icon eicon-site-search';
    }

    public function get_categories() {
        return tmpcoder_show_theme_buider_widget_on('type_archive') ? ['tmpcoder-theme-builder-widgets'] : [ 'tmpcoder-widgets-category'];
    }

    public function get_keywords() {
        return [ 'search', 'search widget', 'ajax search' ];
    }

    public function get_style_depends() {
        return [ 'tmpcoder-animations-css', 'tmpcoder-link-animations-css', 'tmpcoder-button-animations-css', 'tmpcoder-loading-animations-css', 'tmpcoder-search' ];
    }

    public function get_script_depends() {
        return ['tmpcoder-search'];
    }

    public function get_custom_help_url() {
        return TMPCODER_NEED_HELP_URL;
    }

    public function add_section_style_ajax() {
        $this->start_controls_section(
            'section_style_ajax',
            [
                'label' => esc_html__( 'Ajax', 'sastra-essential-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ajax_search' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_list',
            [
                'label' => esc_html__( 'Search List', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color_hover',
            [
                'label' => esc_html__( 'Background Color (Hover)', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#F6F6F6',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch ul li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ajax_box_shadow',
                'selector' => '{{WRAPPER}} .tmpcoder-data-fetch'
            ]
        );

        $this->add_control(
            'search_list_item_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch ul li' => 'transition-duration: {{VALUE}}s',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_content_hr',
            [
                'label' => esc_html__( 'Horizontal Position', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
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
                'selectors_dictionary' => [
                    'left' => 'left: 0; right: auto;',
                    'center' => 'left: 50%; transform: translateX(-50%)',
                    'right' => 'right: 0; left: auto;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch' => '{{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'search_list_width',
            [
                'label' => esc_html__( 'Container Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 50,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'search_list_max_height',
            [
                'label' => esc_html__( 'Max Height', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch ul' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'search_list_top_distance',
            [
                'label' => esc_html__( 'Top Distance', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch' => 'margin-top: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'list_item_title',
            [
                'label' => esc_html__( 'List Item', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'search_list_item_bottom_distance',
            [
                'label' => esc_html__( 'Bottom Distance', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch ul li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'search_list_item_padding',
            [
                'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => [ 'px', ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch a.tmpcoder-ajax-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-data-fetch a.tmpcoder-ajax-title',
            ]
        );

        $this->add_responsive_control(
            'title_distance',
            [
                'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-ajax-search-content a.tmpcoder-ajax-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'heading_description',
            [
                'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#757575',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch p a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-search-admin-notice' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-data-fetch p a, {{WRAPPER}} .tmpcoder-search-admin-notice',
                'fields_options' => [
                    'typography'      => [
                        'default' => 'custom',
                    ],
                    'font_size'       => [
                        'default'    => [
                            'size' => '14',
                            'unit' => 'px',
                        ],
                    ]
                ],
            ]
        );

        $this->add_responsive_control(
            'description_distance',
            [
                'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-ajax-search-content p.tmpcoder-ajax-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'heading_image',
            [
                'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_ajax_thumbnails' => 'yes'
                ]
            ]
        );

        // $this->add_control_ajax_search_img_size();

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch a.tmpcoder-ajax-img-wrap' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-data-fetch .tmpcoder-ajax-search-content' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
                ],
                'condition' => [
                    'show_ajax_thumbnails' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'image_distance',
            [
                'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch a.tmpcoder-ajax-img-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_ajax_thumbnails' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'view_result_text_heading',
            [
                'label' => esc_html__( 'View Result', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'view_result_text_color',
            [
                'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} a.tmpcoder-view-result' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_result_text_color_hr',
            [
                'label' => esc_html__( 'Color (Hover)', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} a.tmpcoder-view-result:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_result_text_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#5729d9',
                'selectors' => [
                    '{{WRAPPER}} a.tmpcoder-view-result' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_result_text_bg_color_hr',
            [
                'label' => esc_html__( 'Background Color (Hover)', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#5729d9',
                'selectors' => [
                    '{{WRAPPER}} a.tmpcoder-view-result:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'view_result_typography',
                'selector' => '{{WRAPPER}} a.tmpcoder-view-result',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
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

        $this->add_control(
            'view_result_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} a.tmpcoder-view-result' => 'transition-duration: {{VALUE}}s',
                ],
            ]
        );

        $this->add_control(
            'view_result_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} a.tmpcoder-view-result' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'view_result_padding',
            [
                'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 5,
                    'right' => 10,
                    'bottom' => 5,
                    'left' => 10,
                ],
                'size_units' => [ 'px', ],
                'selectors' => [
                    '{{WRAPPER}} a.tmpcoder-view-result' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'heading_close_btn',
            [
                'label' => esc_html__( 'Close Button', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'close_btn_color',
            [
                'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch .tmpcoder-close-search' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'close_btn_size',
            [
                'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch .tmpcoder-close-search::before' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-data-fetch .tmpcoder-close-search' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'close_btn_position',
            [
                'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch .tmpcoder-close-search' => 'top: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'scrollbar_heading',
            [
                'label' => esc_html__( 'Scrollbar', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'scrollbar_color',
            [
                'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch ul::-webkit-scrollbar-thumb' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'scrollbar_width',
            [
                'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch ul::-webkit-scrollbar-thumb' => 'border-left-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-data-fetch ul::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + 3px);',
                ]
            ]
        );

        $this->add_control(
            'no_results_heading',
            [
                'label' => esc_html__( 'No Results', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'no_results_color',
            [
                'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch .tmpcoder-no-results' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'no_results_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-data-fetch .tmpcoder-no-results',
            ]
        );

        $this->add_responsive_control(
            'no_results_height',
            [
                'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch .tmpcoder-no-results' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'search_results_box_border_size',
            [
                'label' => esc_html__( 'Border Size', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'size_units' => [ 'px', ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'search_results_box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'search_results_box_padding',
            [
                'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => [ 'px', ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-data-fetch ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
    }

    public function add_section_ajax_pagination() {}

    public function add_section_style_ajax_pagination() {}

    public function add_control_ajax_search_img_size() {

        $intermediate_image_sizes = [];

        foreach ( get_intermediate_image_sizes() as $key=>$value ) {
            $intermediate_image_sizes[$value] = $value;
        }

        $this->add_control(
            'ajax_search_img_size',
            [
                'label' => esc_html__( 'Image Crop', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => $intermediate_image_sizes,
                'default' => 'thumbnail',
            ]
        );
    }

    public function add_control_search_query() {
        $search_post_type = tmpcoder_get_custom_types_of( 'post', false );
        $search_post_type = array_merge( [ 'all' => esc_html__( 'All', 'sastra-essential-addons-for-elementor' ) ], $search_post_type );

        foreach ( $search_post_type as $key => $value ) {
            if ( 'all' != $key ) {
                $search_post_type['pro-'. $key] = $value .' (Pro)';

                if ( 'all' != $key && 'post' != $key && 'page' != $key && 'product' != $key && 'e-landing-page' != $key && !tmpcoder_is_availble() ) {
                    $search_post_type['pro-'. $key] = $value .' (Pro)';
                }

                unset($search_post_type[$key]);
            }
        }

        $this->add_control(
            'search_query',
            [
                'label' => esc_html__( 'Select Query', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => $search_post_type,
                'default' => 'all',
            ]
        );
    }

    public function add_control_select_category() {
        $this->add_control(
            'select_category',
            [
                'label' => esc_html__( 'Category Filter (Pro)', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'tmpcoder-pro-control'
            ]
        );
    }

    public function add_control_all_cat_text() {
    }

    public function add_control_ajax_search() {
        $this->add_control(
            'ajax_search',
            [
                'label' => esc_html__( 'Enable Ajax Search', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );
    }

    public function add_control_number_of_results() {}

    public function add_control_open_in_new_page() {
        $this->add_control(
            'ajax_search_link_target',
            [
                'label' => esc_html__( 'Open Link in New Tab', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    public function add_control_show_ajax_thumbnails() {
        $this->add_control(
            'show_ajax_thumbnails',
            [
                'label' => esc_html__( 'Show Ajax Thumbnails', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    public function add_control_exclude_posts_without_thumbnail() {
        $this->add_control(
            'exclude_posts_without_thumbnail',
            [
                'label' => esc_html__( 'Exclude Results without Thumbnails', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes',
                    'show_ajax_thumbnails' => 'yes'
                ]
            ]
        );
    }

    public function add_control_show_description() {
        $this->add_control(
            'show_description',
            [
                'label' => esc_html__( 'Show Description', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    public function add_control_number_of_words_in_excerpt() {
        $this->add_control(
            'number_of_words_in_excerpt',
            [
                'label' => __( 'Description Number of Words', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 5,
                'step' => 1,
                'default' => 30,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes',
                    'show_description' => 'yes'
                ]
            ]
        );
    }

    public function add_control_show_view_result_btn() {
        $this->add_control(
            'show_view_result_btn',
            [
                'label' => esc_html__( 'Show View Results Button', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    public function add_control_view_result_text() {
        $this->add_control(
            'view_result_text',
            [
                'label' => esc_html__( 'View Results', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'View Results', 'sastra-essential-addons-for-elementor' ),
                'condition' => [
                    'show_view_result_btn' => 'yes',
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    public function add_control_no_results_text() {
        $this->add_control(
            'no_results_text',
            [
                'label' => esc_html__( 'No Resulsts Text', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'No Results Found', 'sastra-essential-addons-for-elementor' ),
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
    }

    protected function register_controls() {
        
        // Section: Search -----------
        $this->start_controls_section(
            'section_search',
            [
                'label' => esc_html__( 'Search', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

        $this->add_control_search_query();

        tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'search', 'search_query', ['pro-post', 'pro-page', 'pro-product', 'pro-product', 'pro-e-landing-page'] );

        if ( !tmpcoder_is_availble() ) {
            $this->add_control(
                'search_query_exp_pro_notice',
                [
                    'raw' => 'This option is available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-panel-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'tmpcoder-pro-notice',
                    'condition' => [
                        'search_query!' => ['all','post','page','product','e-landing-page','pro-post', 'pro-page', 'pro-product', 'pro-product', 'pro-e-landing-page'],
                    ]
                ]
            );
        }

        $this->add_control_select_category();

        $this->add_control_all_cat_text();

        $this->add_control_ajax_search();

        $this->add_control_number_of_results();

        $this->add_control_open_in_new_page();

        $this->add_control_show_ajax_thumbnails();

        $this->add_control_exclude_posts_without_thumbnail();

        $this->add_control_show_view_result_btn();

        $this->add_control_view_result_text();
        
        $this->add_control_show_description();

        $this->add_control_number_of_words_in_excerpt();

        $this->add_control_no_results_text();

        $this->add_control(
            'search_placeholder',
            [
                'label' => esc_html__( 'Placeholder', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Search...', 'sastra-essential-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'search_aria_label',
            [
                'label' => esc_html__( 'Aria Label', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Search', 'sastra-essential-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'search_btn',
            [
                'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'search_btn_style',
            [
                'label' => esc_html__( 'Style', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'inner',
                'options' => [
                    'inner' => esc_html__( 'Inner', 'sastra-essential-addons-for-elementor' ),
                    'outer' => esc_html__( 'Outer', 'sastra-essential-addons-for-elementor' ),
                ],
                'prefix_class' => 'tmpcoder-search-form-style-',
                'render_type' => 'template',
                'condition' => [
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_btn_disable_click',
            [
                'label' => esc_html__( 'Disable Button Click', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'search_btn_style' => 'inner',
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_btn_type',
            [
                'label' => esc_html__( 'Type', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'text' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
                    'icon' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
                ],
                'render_type' => 'template',
                'condition' => [
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_btn_text',
            [
                'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Go',
                'condition' => [
                    'search_btn_type' => 'text',
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'search_btn_icon',
            [
                'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fas fa-search',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'search_btn_type' => 'icon',
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_section_ajax_pagination();

        // Section: Request New Feature
        tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

        // Section: Pro Features
        tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'search', [
            'Custom Search Query - Only Posts or Pages',
            'Custom Search Query - Only Custom Post Types (Pro)',
            'More than 2 Results in Ajax Search',
            'Enable Taxonomy Filter (Pro)',
            'Ajax Search Results Pagination (Load More)'
        ] );

        // Styles
        // Section: Input ------------
        $this->start_controls_section(
            'section_style_input',
            [
                'label' => esc_html__( 'Input', 'sastra-essential-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_input_colors' );

        $this->start_controls_tab(
            'tab_input_normal_colors',
            [
                'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label' => esc_html__( 'Placeholder Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9e9e9e',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-search-form-input:-ms-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-search-form-input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-input' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-data-fetch' => 'border-color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .tmpcoder-search-form-input-wrap'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_focus_colors',
            [
                'label' => esc_html__( 'Focus', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'input_focus_color',
            [
                'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}}.tmpcoder-search-form-input-focus .tmpcoder-search-form-input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}}.tmpcoder-search-form-input-focus .tmpcoder-search-form-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_placeholder_color',
            [
                'label' => esc_html__( 'Placeholder Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9e9e9e',
                'selectors' => [
                    '{{WRAPPER}}.tmpcoder-search-form-input-focus .tmpcoder-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.tmpcoder-search-form-input-focus .tmpcoder-search-form-input:-ms-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.tmpcoder-search-form-input-focus .tmpcoder-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.tmpcoder-search-form-input-focus .tmpcoder-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.tmpcoder-search-form-input-focus .tmpcoder-search-form-input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}}.tmpcoder-search-form-input-focus .tmpcoder-search-form-input' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'input_focus_box_shadow',
                'selector' => '{{WRAPPER}}.tmpcoder-search-form-input-focus .tmpcoder-search-form-input-wrap'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'input_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-search-form-input, {{WRAPPER}} .tmpcoder-category-select-wrap, {{WRAPPER}} .tmpcoder-category-select',
            ]
        );

        $this->add_responsive_control(
            'input_align',
            [
                'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
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
                    '{{WRAPPER}} .tmpcoder-search-form-input' => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'input_border_size',
            [
                'label' => esc_html__( 'Border Size', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'size_units' => [ 'px', ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-data-fetch' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'input_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .tmpcoder-data-fetch' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                ],
                'size_units' => [ 'px', ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-category-select-wrap::before' => 'right: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-category-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_section();

        // Styles
        // Section: Select ------------
        $this->start_controls_section(
            'section_style_select',
            [
                'label' => esc_html__( 'Taxonomy Filter', 'sastra-essential-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'select_category' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'select_color',
            [
                'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-category-select-wrap' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-category-select' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'select_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-category-select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'select_border_color',
            [
                'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-category-select' => 'border-color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'select_border_size',
            [
                'label' => esc_html__( 'Border Size', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'size_units' => [ 'px', ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-category-select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'select_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-category-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .tmpcoder-category-select-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'select_width',
            [
                'label' => esc_html__( 'Select Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 400,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 230,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-category-select-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'options_heading',
            [
                'label' => esc_html__( 'Options', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'option_font_size',
            [
                'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-category-select option' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'optgroup_heading',
            [
                'label' => esc_html__( 'Options Group', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'optgroup_font_size',
            [
                'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-category-select optgroup' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        // Styles
        // Section: Button ------------
        $this->start_controls_section(
            'section_style_btn',
            [
                'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_btn' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_btn_colors' );

        $this->start_controls_tab(
            'tab_btn_normal_colors',
            [
                'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-submit' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-search-form-submit svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'default' => '#5729d9',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-submit' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .tmpcoder-search-form-submit',
                'condition' => [
                    'search_btn_style' => 'outer',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover_colors',
            [
                'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
            ]
        );


        $this->add_control(
            'btn_hv_text_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-submit:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-search-form-submit:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hv_bg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'default' => '#5729d9',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-submit:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hv_border_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-submit:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_hv_box_shadow',
                'selector' => '{{WRAPPER}} .tmpcoder-search-form-submit:hover',
                'condition' => [
                    'search_btn_style' => 'outer',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btn_width',
            [
                'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 125,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-submit' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_height',
            [
                'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
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
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}}.tmpcoder-search-form-style-outer .tmpcoder-search-form-submit' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'search_btn_style' => 'outer',
                ],
            ]
        );

        $this->add_control(
            'btn_gutter',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}.tmpcoder-search-form-style-outer.tmpcoder-search-form-position-right .tmpcoder-search-form-submit' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.tmpcoder-search-form-style-outer.tmpcoder-search-form-position-left .tmpcoder-search-form-submit' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'search_btn_style' => 'outer',
                ],
            ]
        );

        $this->add_control(
            'btn_position',
            [
                'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'right',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'tmpcoder-search-form-position-',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'label' => esc_html__( 'Typography', 'sastra-essential-addons-for-elementor' ),
                'selector' => '{{WRAPPER}} .tmpcoder-search-form-submit',
                'selector' => '{{WRAPPER}} .tmpcoder-search-form-submit svg',
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-submit svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-search-form-submit' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border_size',
            [
                'label' => esc_html__( 'Border Size', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'size_units' => [ 'px', ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-search-form-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_section();

        // Styles
        // Section: AJAX ------------
        $this->add_section_style_ajax();

        $this->add_section_style_ajax_pagination();
        
    }

    public function render_search_pagination($settings) {}

    protected function render_search_submit_btn() {
        $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

        $this->add_render_attribute(
        'button', [
            'class' => 'tmpcoder-search-form-submit',
            'aria-label' => $settings['search_aria_label'],
            'type' => 'submit',
        ]
        );

        if ( $settings['search_btn_disable_click'] ) {
            $this->add_render_attribute( 'button', 'disabled' );
        }

        if ( 'yes' === $settings['search_btn'] ) : ?>

        <?php echo wp_kses_post('<button '.$this->get_render_attribute_string( 'button' ).'>'); ?>
            <?php if ( 'icon' === $settings['search_btn_type'] && (!empty($settings['search_btn_icon']['value']) && '' !== $settings['search_btn_icon']['value']) ) : ?>

                <?php if (is_array($settings['search_btn_icon']['value'])) {

                    echo wp_kses(tmpcoder_render_svg_icon($settings['search_btn_icon']), tmpcoder_wp_kses_allowed_html());

                } else { ?>

                <i class="<?php echo esc_attr( $settings['search_btn_icon']['value'] ); ?>"></i>

                <?php } ?>

            <?php elseif( 'text' === $settings['search_btn_type'] && (!empty($settings['search_btn_text']) && '' !== $settings['search_btn_text']) ) : ?>
                <?php echo esc_html( $settings['search_btn_text'] ); ?>
            <?php endif; ?>
        </button>

        <?php
        endif;
    }
    
    protected function render() {
        // Get Settings
        $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

        $hidden_input = '';

        $this->add_render_attribute(
            'input', [
                'class' => 'tmpcoder-search-form-input',
                'placeholder' => $settings['search_placeholder'],
                'aria-label' => $settings['search_aria_label'],
                'type' => 'search',
                'name' => 's',
                'title' => esc_html__( 'Search', 'sastra-essential-addons-for-elementor' ),
                'value' => get_search_query(),
                'tmpcoder-query-type' => $settings['search_query'],
                'tmpcoder-taxonomy-type' => isset($settings['query_taxonomy_'. $settings['search_query']]) ? $settings['query_taxonomy_'. $settings['search_query']] : '',
                'number-of-results' => isset($settings['number_of_results']) && tmpcoder_is_availble() ? $settings['number_of_results'] : 2,
                'ajax-search' => isset($settings['ajax_search']) ? $settings['ajax_search'] : '',
                'show-description' => isset($settings['show_description']) ? $settings['show_description'] : '',
                'number-of-words' => isset($settings['number_of_words_in_excerpt']) ? $settings['number_of_words_in_excerpt'] : '',
                'show-ajax-thumbnails' => isset($settings['show_ajax_thumbnails']) ? $settings['show_ajax_thumbnails'] : '',
                'show-view-result-btn' => isset($settings['show_view_result_btn']) ? $settings['show_view_result_btn'] : '',
                'view-result-text' => isset($settings['view_result_text']) ? $settings['view_result_text'] : '',
                'no-results' => isset($settings['no_results_text']) ? esc_html($settings['no_results_text']) : '',
                'exclude-without-thumb' => isset($settings['exclude_posts_without_thumbnail']) ? $settings['exclude_posts_without_thumbnail'] : '',
                'link-target' => isset($settings['ajax_search_link_target']) && ( 'yes' === $settings['ajax_search_link_target'] ) ? '_blank'  : '_self',
            ]
        );

        ?>

        <form role="search" method="get" class="tmpcoder-search-form" action="<?php echo esc_url(home_url()); ?>">

            <div class="tmpcoder-search-form-input-wrap elementor-clearfix">
                <?php echo wp_kses('<input '. $this->get_render_attribute_string( 'input' ).' >', array(
                    'input' => array(
                        'class' => array(),
                        'placeholder' => array(),
                        'aria-label' => array(),
                        'type' => array(),
                        'name' => array(),
                        'title' => array(),
                        'value' => array(),
                        'tmpcoder-query-type' => array(),                        
                        'tmpcoder-taxonomy-type' => array(),
                        'number-of-results' => array(),
                        'ajax-search' => array(),
                        'show-description' => array(),
                        'number-of-words' => array(),
                        'show-ajax-thumbnails' => array(),
                        'show-view-result-btn' => array(),
                        'view-result-text' => array(),
                        'no-results' => array(),
                        'exclude-without-thumb' => array(),
                        'link-target' => array(),
                    )
                )); ?>
                <?php
                if ( $settings['search_btn_style'] === 'inner' ) {
                    $this->render_search_submit_btn();
                }
                ?>
            </div>

            <?php

            if ( $settings['search_btn_style'] === 'outer' ) {
                $this->render_search_submit_btn();
            }

            ?>
        </form>
        <div class="tmpcoder-data-fetch">
            <span class="tmpcoder-close-search"></span>
            <ul></ul>
            <?php if ( !tmpcoder_is_availble() && current_user_can( 'administrator' ) ) : ?>
                <p class="tmpcoder-search-admin-notice">More than 2 results are available in the PRO version (This notice is only visible to admin users)</p>
            <?php endif; ?>
        </div>
        
        <?php
    }
}