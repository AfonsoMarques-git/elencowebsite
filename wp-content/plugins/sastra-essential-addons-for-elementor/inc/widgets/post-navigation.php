<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class TMPCODER_Post_Navigation extends Widget_Base {
    
    public function get_name() {
        return 'tmpcoder-post-navigation';
    }

    public function get_title() {
        return esc_html__( 'Post Navigation', 'sastra-essential-addons-for-elementor' );
    }

    public function get_icon() {
        return 'tmpcoder-icon eicon-post-navigation';
    }

    public function get_categories() {
        return tmpcoder_show_theme_buider_widget_on('type_single_post') ? [ 'tmpcoder-theme-builder-widgets'] : [];
    }

    public function get_style_depends() {
        return [ 'tmpcoder-post-navigation'];
    }

    public function get_keywords() {
        return [ 'navigation', 'arrows', 'pagination' ];
    }

    public function add_control_display_on_separate_lines() {
        $this->add_responsive_control(
            'display_on_separate_lines',
            [
                'label' => esc_html__( 'Display on Separate Lines', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'classes' => 'tmpcoder-pro-control',
                'condition' => [
                    'post_nav_layout' => 'static'
                ],
            ]
        );
    }

    public function add_control_post_nav_layout() {
        $this->add_control(
            'post_nav_layout',
            [
                'label' => esc_html__( 'Layout', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'static',
                'options' => [
                    'static' => esc_html__( 'Static Left/Right', 'sastra-essential-addons-for-elementor' ),
                    'pro-fx' => esc_html__( 'Fixed Left/Right (Pro)', 'sastra-essential-addons-for-elementor' ),
                    'pro-fd' => esc_html__( 'Fixed Default (Pro)', 'sastra-essential-addons-for-elementor' ),
                ],
            ]
        );
    }

    public function add_control_post_nav_fixed_default_align() {}

    public function add_control_post_nav_fixed_vr() {}
    
    public function add_control_post_nav_arrows_loc() {}
    
    public function add_control_post_nav_title() {}

    public function add_controls_group_post_nav_image() {}

    public function add_controls_group_post_nav_back() {}

    public function add_control_post_nav_query() {}

    public function add_controls_group_post_nav_overlay_style() {}

    public function add_control_post_nav_align_vr() {}

    public function add_section_style_post_nav_back_btn() {}

    public function add_section_style_post_nav_title() {}

    protected function register_controls() {

        // Tab: Content ==============
        // Section: General ----------
        $this->start_controls_section(
            'section_post_navigation',
            [
                'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control_post_nav_layout();

        // Upgrade to Pro Notice
        tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'post-navigation', 'post_nav_layout', ['pro-fx', 'pro-fd'] );

        $this->add_control_post_nav_fixed_default_align();

        $this->add_control_post_nav_fixed_vr();

        $this->add_control(
            'post_nav_arrows',
            [
                'label' => esc_html__( 'Show Arrows', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before'
            ]
        );

        $this->add_control_post_nav_arrows_loc();
        
        $this->add_control(
            'post_nav_arrow_icon',
            [
                'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
                'type' => 'tmpcoder-arrow-icons',
                'default' => 'svg-angle-2-left',
                'condition' => [
                    'post_nav_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'post_nav_labels',
            [
                'label' => esc_html__( 'Show Labels', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'post_nav_layout!' => 'fixed',
                ]
            ]
        );

        $this->add_control(
            'post_nav_prev_text',
            [
                'label' => esc_html__( 'Previous Text', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Previous Post',
                'condition' => [
                    'post_nav_labels' => 'yes',
                    'post_nav_layout!' => 'fixed',
                ]
            ]
        );

        $this->add_control(
            'post_nav_next_text',
            [
                'label' => esc_html__( 'Next Text', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Next Post',
                'condition' => [
                    'post_nav_labels' => 'yes',
                    'post_nav_layout!' => 'fixed',
                ]
            ]
        );

        $this->add_control_post_nav_title();

        $this->add_controls_group_post_nav_image();

        $this->add_controls_group_post_nav_back();

        $this->add_control(
            'post_nav_dividers',
            [
                'label' => esc_html__( 'Show Dividers', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'post_nav_layout' => 'static'
                ],
            ]
        );

        $this->add_control_display_on_separate_lines();

        $this->add_control_post_nav_query();

        $this->end_controls_section();

        // Section: Request New Feature
        tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

        // Section: Pro Features
        tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'post-navigation', [
            'Set Navigation Query - Force to navigate posts through specific Taxonomy (category).',
            'Advanced Layout Options - Fixed Left-Right, Fixed Bottom.',
            'Multiple Navigation Arrows Locations.',
            'Show/Hide Post Title.',
            'Show/Hide Post Thumbnail, Show on hover or set as Navigation Label Background.',
            'Show/Hide Back Button - Set custom link to any page to go back to.',
            'Display Navigation on Separate Lines'
        ] );

        // Styles ====================
        // Section: General ----------
        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'post_nav_layout!' => 'fixed'
                ]
            ]
        );

        $this->add_controls_group_post_nav_overlay_style();

        $this->add_control(
            'post_nav_background',
            [
                'label'  => esc_html__( 'Section Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'post_nav_divider_color',
            [
                'label'  => esc_html__( 'Divider Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation-wrap' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-post-nav-divider' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'post_nav_layout' => 'static',
                    'post_nav_dividers' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'post_nav_divider_width',
            [
                'label' => esc_html__( 'Divider Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 5,
                    ],
                ],              
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-nav-divider' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-navigation-wrap' => 'border-width: {{SIZE}}{{UNIT}} 0 {{SIZE}}{{UNIT}} 0;',
                ],
                'condition' => [
                    'post_nav_layout' => 'static',
                    'post_nav_dividers' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'post_nav_padding',
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
                    '{{WRAPPER}} .tmpcoder-post-navigation-wrap.tmpcoder-post-nav-dividers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-nav-bg-images .tmpcoder-post-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control_post_nav_align_vr();

        $this->end_controls_section();

        // Styles ====================
        // Section: Arrows -----------
        $this->start_controls_section(
            'section_style_post_nav_arrow',
            [
                'label' => esc_html__( 'Arrows', 'sastra-essential-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'post_nav_arrows' => 'yes'
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_post_nav_arrow_style' );

        $this->start_controls_tab(
            'tab_grid_post_nav_arrow_normal',
            [
                'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'post_nav_arrow_color',
            [
                'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#5729d9',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-post-navigation svg path' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'post_nav_arrow_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation i' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'post_nav_arrow_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'default' => '#E8E8E8',
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation i' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_post_nav_arrow_hover',
            [
                'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'post_nav_arrow_color_hr',
            [
                'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation i:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper:hover svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'post_nav_arrow_bg_color_hr',
            [
                'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation i:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'post_nav_arrow_border_color_hr',
            [
                'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation i:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'post_nav_arrow_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation i' => 'transition: color {{VALUE}}s, background-color {{VALUE}}s, border-color {{VALUE}}s',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper svg' => 'transition: fill {{VALUE}}s',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper' => 'transition: background-color {{VALUE}}s, border-color {{VALUE}}s',
                    '{{WRAPPER}} .tmpcoder-post-nav-fixed.tmpcoder-post-nav-hover img' => 'transition: all {{VALUE}}s ease',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'post_nav_arrow_size',
            [
                'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],              
                'default' => [
                    'unit' => 'px',
                    'size' => 7,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-navigation svg' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-navigation-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-navigation-wrap svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'post_nav_arrow_width',
            [
                'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],              
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation-wrap i' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-navigation i' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-nav-fixed.tmpcoder-post-nav-prev img' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-nav-fixed.tmpcoder-post-nav-next img' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'post_nav_arrow_height',
            [
                'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],              
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-navigation-wrap i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-navigation i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-nav-fixed.tmpcoder-post-navigation img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'post_nav_arrow_distance',
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
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-nav-prev i' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-nav-prev .tmpcoder-posts-navigation-svg-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-nav-next i' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-post-nav-next .tmpcoder-posts-navigation-svg-wrapper' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'post_nav_layout!' => 'fixed',
                ]
            ]
        );

        $this->add_control(
            'post_nav_arrow_border_type',
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
                    '{{WRAPPER}} .tmpcoder-post-navigation i' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'post_nav_arrow_border_width',
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
                    '{{WRAPPER}} .tmpcoder-post-navigation i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'post_nav_arrow_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'post_nav_arrow_radius',
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
                    '{{WRAPPER}} .tmpcoder-post-navigation i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-posts-navigation-svg-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Styles ====================
        // Section: Back Button ------
        $this->add_section_style_post_nav_back_btn();

        // Styles ====================
        // Section: Labels -----------
        $this->start_controls_section(
            'section_style_post_nav_label',
            [
                'label' => esc_html__( 'Labels', 'sastra-essential-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'post_nav_labels' => 'yes',
                    'post_nav_layout!' => 'fixed'
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_grid_post_nav_label_style' );

        $this->start_controls_tab(
            'tab_grid_post_nav_label_normal',
            [
                'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'post_nav_label_color',
            [
                'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#5729d9',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-nav-labels span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .tmpcoder-post-nav-labels span',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom',
                    ],
                    'font_size'      => [
                        'default' => [
                            'size' => '15',
                            'unit' => 'px',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'post_nav_label_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-nav-labels span' => 'transition: color {{VALUE}}s',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_grid_post_nav_label_hover',
            [
                'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'post_nav_label_color_hr',
            [
                'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#54595f',
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-post-nav-labels span:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Styles ====================
        // Section: Title ------------
        $this->add_section_style_post_nav_title();

    }

    // Arrow Icon
    public function render_arrow_by_location( $settings, $location, $dir ) {
        if ( 'fixed' === $settings['post_nav_layout'] || !tmpcoder_is_availble() ) {
            $settings['post_nav_arrows_loc'] = 'separate';
        }

        if ( 'yes' === $settings['post_nav_arrows'] && $location === $settings['post_nav_arrows_loc'] ) {
            if (  false !== strpos( $settings['post_nav_arrow_icon'], 'svg-' ) ) {
                echo '<div class="tmpcoder-posts-navigation-svg-wrapper">';    
                echo wp_kses(tmpcoder_get_icon( $settings['post_nav_arrow_icon'], $dir ), tmpcoder_wp_kses_allowed_html()); 
                echo '</div>'; 

            } else {
                echo wp_kses(tmpcoder_get_icon( $settings['post_nav_arrow_icon'], $dir ), tmpcoder_wp_kses_allowed_html() ); 
            }
        }
    }

    protected function render() {
        // Get Settings
        $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

        if ( !tmpcoder_is_availble() ) {
            $settings['post_nav_image'] = '';
            $settings['post_nav_image_bg'] = '';
            $settings['post_nav_back'] = '';
            $settings['post_nav_title'] = '';
        }
        wp_reset_postdata();

        // Set Query
        $nav_query = isset($settings['post_nav_query']) ? $settings['post_nav_query'] : 'all';

        // Get Previous and Next Posts
        if ( 'all' === $nav_query || !tmpcoder_is_availble() ) {
            $prev_post = get_adjacent_post( false, '', true );
            $next_post = get_adjacent_post( false, '', false );
        } else {
            $prev_post = get_adjacent_post( true, '', true, $nav_query );
            $next_post = get_adjacent_post( true, '', false, $nav_query );
        }

        // Layout Class
        $layout_class = 'tmpcoder-post-navigation tmpcoder-post-nav-'. $settings['post_nav_layout'];

        // Show Image on Hover
        if ( (isset($settings['post_nav_image_hover']) && 'yes' === $settings['post_nav_image_hover']) ) {
            $layout_class .= ' tmpcoder-post-nav-hover';
        }

        $prev_image_url = '';
        $next_image_url = '';
        $prev_post_bg = '';
        $next_post_bg = '';

        // Image URLs
        if ( ! empty($prev_post) && 'yes' === $settings['post_nav_image'] ) {

            $prev_img_id = get_post_thumbnail_id( $prev_post->ID );
            $settings[ 'post_nav_image_width_crop' ] = ['id' => $prev_img_id];
            $prev_image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'post_nav_image_width_crop' );
            $prev_image_url = Group_Control_Image_Size::get_attachment_image_src( $prev_img_id, 'post_nav_image_width_crop', $settings );
        }
        if ( ! empty($next_post) && 'yes' === $settings['post_nav_image'] ) {
            $next_img_id = get_post_thumbnail_id( $next_post->ID );
            $next_image_url = Group_Control_Image_Size::get_attachment_image_src( $next_img_id, 'post_nav_image_width_crop', $settings );
            $settings[ 'post_nav_image_width_crop' ] = ['id' => $next_img_id];
            $next_image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'post_nav_image_width_crop' );
        }

        // Background Images
        if ( 'yes' === $settings['post_nav_image'] && 'yes' === $settings['post_nav_image_bg'] ) {
            if ( 'fixed' !== $settings['post_nav_layout'] ) {
                if ( ! empty($prev_post) ) {
                    // $prev_post_bg = ' style="background-image: url('. esc_url($prev_image_url) .')"';
                    $prev_post_bg = 'background-image: url('. esc_url($prev_image_url).')';
                }

                if ( ! empty($next_post) ) {
                    // $next_post_bg = ' style="background-image: url('. esc_url($next_image_url) .')"';
                    $next_post_bg = 'background-image: url('.esc_url($next_image_url).')';
                }
            }
        }

        // Navigation Wrapper
        if ( 'fixed' !== $settings['post_nav_layout'] ) {
            // Layout Class
            $wrapper_class = 'tmpcoder-post-nav-'. $settings['post_nav_layout'] .'-wrap';

            // Dividers
            if ( 'static' === $settings['post_nav_layout'] && 'yes' === $settings['post_nav_dividers'] ) {
                $wrapper_class .= ' tmpcoder-post-nav-dividers';
            }

            // Background Images
            if ( 'yes' === $settings['post_nav_image'] && 'yes' === $settings['post_nav_image_bg'] ) {
                $wrapper_class .= ' tmpcoder-post-nav-bg-images';
            }

            echo '<div class="tmpcoder-post-navigation-wrap elementor-clearfix '. esc_attr($wrapper_class) .'">';
        }

        // Previous Post
        echo '<div class="tmpcoder-post-nav-prev '. esc_attr($layout_class) .'" style="'.esc_attr($prev_post_bg).'">'; 
            if ( ! empty($prev_post) ) {
                echo '<a href="'. esc_url( get_permalink($prev_post->ID) ) .'" class="elementor-clearfix">';
                    // Left Arrow
                    $this->render_arrow_by_location( $settings, 'separate', 'left' );

                    // Post Thumbnail
                    if ( 'yes' === $settings['post_nav_image'] ) {
                        if ( (empty($settings['post_nav_image_bg']) || '' === $settings['post_nav_image_bg']) || 'fixed' === $settings['post_nav_layout'] ) {
                            echo wp_kses_post($prev_image_html);
                        }
                    }

                    // Label & Title
                    if ( 'fixed' !== $settings['post_nav_layout'] ) {
                        echo '<div class="tmpcoder-post-nav-labels">';
                            // Prev Label
                            if ( 'yes' === $settings['post_nav_labels'] ) {
                                echo '<span>';
                                    $this->render_arrow_by_location( $settings, 'label', 'left' );
                                    echo esc_html($settings['post_nav_prev_text']);
                                echo '</span>';
                            }

                            // Post Title
                            if ( 'yes' === $settings['post_nav_title'] ) {
                                echo '<h5>';
                                    $this->render_arrow_by_location( $settings, 'title', 'left' );
                                    echo esc_html( get_the_title($prev_post->ID) );
                                echo '</h5>';
                            }
                        echo '</div>';
                    }
                echo '</a>';

                // Image Overlay
                if ( 'yes' === $settings['post_nav_image_bg'] ) {
                    echo '<div class="tmpcoder-post-nav-overlay"></div>';
                }
            }
        echo '</div>';

        // Back to Posts
        if ( 'fixed' !== $settings['post_nav_layout'] && 'yes' === $settings['post_nav_back'] ) {
            echo '<div class="tmpcoder-post-nav-back">';
                echo '<a href="'. esc_url($settings['post_nav_back_link'] ) .'">';
                    echo '<span></span>';
                    echo '<span></span>';
                    echo '<span></span>';
                    echo '<span></span>';
                echo '</a>';
            echo '</div>';
        }

        // Middle Divider
        if ( 'static' === $settings['post_nav_layout'] && 'yes' === $settings['post_nav_dividers'] && (empty($settings['post_nav_back']) || '' === $settings['post_nav_back']) ) {
            echo '<div class="tmpcoder-post-nav-divider"></div>';
        }

        // Next Post
        echo wp_kses('<div class="tmpcoder-post-nav-next '. esc_attr($layout_class) .'" style="'.esc_attr($next_post_bg).'">', array(
            'div' => array(
                'class'=> array(),
                'style'=> array(),
            ),
        ));
            if ( ! empty($next_post) ) {
                echo '<a href="'. esc_url(get_permalink($next_post->ID)).'" class="elementor-clearfix">';
                    // Label & Title
                    if ( 'fixed' !== $settings['post_nav_layout'] ) {
                        echo '<div class="tmpcoder-post-nav-labels">';
                            // Next Label
                            if ( 'yes' === $settings['post_nav_labels'] ) {
                                echo '<span>';
                                    echo esc_html($settings['post_nav_next_text']);
                                    $this->render_arrow_by_location( $settings, 'label', 'right' );
                                echo '</span>';
                            }

                            // Post Title
                            if ( 'yes' === $settings['post_nav_title'] ) {
                                echo '<h5>';
                                    echo esc_html( get_the_title($next_post->ID) );
                                    $this->render_arrow_by_location( $settings, 'title', 'right' );
                                echo '</h5>';
                            }
                        echo '</div>';
                    }

                    // Post Thumbnail
                    if ( 'yes' === $settings['post_nav_image'] ) {
                        if ( (empty($settings['post_nav_image_bg']) || '' === $settings['post_nav_image_bg']) || 'fixed' === $settings['post_nav_layout'] ) {
                            echo wp_kses_post($next_image_html);
                        }
                    }

                    // Right Arrow
                    $this->render_arrow_by_location( $settings, 'separate', 'right' );
                echo '</a>';

                // Image Overlay
                if ( 'yes' === $settings['post_nav_image_bg'] ) {
                    echo '<div class="tmpcoder-post-nav-overlay"></div>';
                }
            }
        echo '</div>';

        // End Navigation Wrapper
        if ( 'fixed' !== $settings['post_nav_layout'] ) {
            echo '</div>';
        }

    }
    
}