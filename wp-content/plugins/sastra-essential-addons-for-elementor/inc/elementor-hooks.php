<?php

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

if ( !class_exists('TMPCODER_Elementor_Hooks') ){
    class TMPCODER_Elementor_Hooks {

        public function __construct()
        {

            add_action( 'elementor/element/column/layout/before_section_end', [$this, 'column_addons_controls'], 10, 2);

            add_action( 'elementor/element/before_section_end', [$this, 'tmpcoder_customize_elementor_section_control'], 10, 3 );

            add_action( 'elementor/document/wrapper_attributes', [$this, 'tmpcoder_document_wrapper_attributes'], 10, 2 );

            add_action( 'elementor/element/image-carousel/section_style_navigation/after_section_end', function( $element, $args ) {
                
                $element->start_controls_section(
                    'image_carousel_style_section', // Unique ID for the section
                    [
                        'label' => __( 'Addons Style Options', 'sastra-essential-addons-for-elementor' ),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    ]
                );

                $element->add_responsive_control(
                    'image_carousel_image_margin',
                    [
                        'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                        'selectors' => [
                            '{{WRAPPER}} .swiper-slide .swiper-slide-inner img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
            
                $element->end_controls_section();
            }, 10, 2 );

        }

        public function tmpcoder_layout_conditions(){

            $templates = TMPCODER_Theme_Layouts_Base::instance();
            $is_layout_on = $templates->canvas_page_content_display_conditions() != "" ? $templates->canvas_page_content_display_conditions() : '';

            return ( $is_layout_on != '' && (is_home() || is_tax() || is_singular() || is_category() || is_search() || is_tag() || is_year() || is_month() || is_author() || (function_exists('is_product_category') && is_product_category()) || (function_exists('is_shop') && is_shop()) ) );
        }
        
        public function tmpcoder_document_wrapper_attributes($attributes, $document){
            // $attributes, $this
            if ( isset($attributes['class']) ){
                if ( function_exists('is_product') && is_product() ){

                    $data_elementor_id = $attributes['data-elementor-id'];
                    $tmpcoder_template_type = get_post_meta($data_elementor_id, 'tmpcoder_template_type', true);
                    if ( $tmpcoder_template_type != 'type_header' && $tmpcoder_template_type != 'type_footer' ){
                        $attributes['class'] = $attributes['class'].' product';
                    }
                }
            }
            return $attributes;
        }

        function tmpcoder_customize_elementor_section_control( $element, $section_id, $args ) {

            if ( 'section' === $element->get_name() && ('section_layout' === $section_id || 'section_layout_container' === $section_id)) {

                $element->add_control(
                    'custom_section_position',
                    [
                        'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => [
                            '' => esc_html__( 'default', 'sastra-essential-addons-for-elementor' ),
                            'unset' => esc_html__( 'Unset', 'sastra-essential-addons-for-elementor' ),
                        ],
                        'default' => '',
                        'prefix_class' => 'tmpcoder-custom-section-position-',
                    ]
                );

                $element->add_control(
                    'custom_section_dynamic_spacing',
                    [
                        'label' => esc_html__( 'Dynamic Spacing', 'sastra-essential-addons-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => [
                            '' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
                            'padding-right' => esc_html__( 'Padding Right', 'sastra-essential-addons-for-elementor' ),
                            'padding-left' => esc_html__( 'Padding Left', 'sastra-essential-addons-for-elementor' ),
                        ],
                        'default' => '',
                        'prefix_class' => 'tmpcoder-dynamic-',
                        'condition' => ['layout' => 'full_width'],
                    ]
                );

                $element->add_control(
                    'custom_section_justify_content',
                    [
                        'label' => esc_html__('Justify Content', 'sastra-essential-addons-for-elementor'),
                        'type' => \Elementor\Controls_Manager::CHOOSE,
                        'label_block' => true,
                        'options' => [
                            'start' => [
                                'title' => esc_html__( 'Start', 'sastra-essential-addons-for-elementor' ),
                                'icon' => 'eicon-justify-start-h',
                            ],
                            'center' => [
                                'title' => esc_html__( 'Middle', 'sastra-essential-addons-for-elementor' ),
                                'icon' => 'eicon-justify-center-h',
                            ],
                            'end' => [
                                'title' => esc_html__( 'End', 'sastra-essential-addons-for-elementor' ),
                                'icon' => 'eicon-justify-end-h',
                            ],
                            'space-between' => [
                                'title' => esc_html__( 'Space Between', 'sastra-essential-addons-for-elementor' ),
                                'icon' => 'eicon-justify-space-between-h',
                            ],
                            'space-around' => [
                                'title' => esc_html__( 'Space Around', 'sastra-essential-addons-for-elementor' ),
                                'icon' => 'eicon-justify-space-around-h',
                            ],
                            'space-evenly' => [
                                'title' => esc_html__( 'Space Evenly', 'sastra-essential-addons-for-elementor' ),
                                'icon' => 'eicon-justify-space-evenly-h',
                            ],
                        ],
                        'default' => '',
                        'prefix_class' => 'tmpcoder-custom-justify-content-',
                        'selectors' => [
                            '{{WRAPPER}} .elementor-container' => 'justify-content: {{VALUE}};',
                        ],
                    ]
                );
            }
        }

        function column_addons_controls( \Elementor\Element_Column $element , $args) {
            
            $element->add_control(
                'custom_column_position',
                [
                    'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__( 'default', 'sastra-essential-addons-for-elementor' ),
                        'unset' => esc_html__( 'Unset', 'sastra-essential-addons-for-elementor' ),
                    ],
                    'default' => '',
                    'prefix_class' => 'tmpcoder-custom-column-position-',
                ]
            );

            $element->add_responsive_control(
                '_elco_column_width',
                [
                    'label'       => __( 'Custom Column Width', 'sastra-essential-addons-for-elementor' ),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'separator'   => 'before',
                    'label_block' => true,
                    'description' => __( 'Here you can set the column width the way you always wanted to! e.g 250px, 50%, calc(100% - 250px)', 'sastra-essential-addons-for-elementor' ),
                    'selectors'   => [
                        '{{WRAPPER}}.elementor-column' => 'width: {{VALUE}};',
                    ],
                ]
            );
    
            $element->add_responsive_control(
                '_elco_column_order',
                [
                    'label'          => __( 'Column Order', 'sastra-essential-addons-for-elementor' ),
                    'type'           => \Elementor\Controls_Manager::NUMBER,
                    'style_transfer' => true,
                    'selectors'      => [
                        '{{WRAPPER}}.elementor-column' => '-webkit-box-ordinal-group: calc({{VALUE}} + 1 ); -ms-flex-order:{{VALUE}}; order: {{VALUE}};',
                    ],
                    'description'    => sprintf(
                        /* translators: 1: Start of anchor 2: End of anchor */
                        esc_html__( 'Column ordering is a great addition for responsive design. You can learn more about CSS order property from %1$sMDN%2$s.', 'sastra-essential-addons-for-elementor' ),
                        '<a
    href="https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout/Ordering_Flex_Items#The_order_property" target="_blank">',
                        '</a>'
                    ),
                ]
            );
        }
    }
    new TMPCODER_Elementor_Hooks();
}

// Custom CSS
if ( 'on' === get_option('tmpcoder-custom-css', 'on') ) {
    require TMPCODER_PLUGIN_DIR . 'inc/extensions/tmpcoder-custom-css.php';
}

// Parallax
if ( 'on' === get_option('tmpcoder-parallax-background', 'on') || 'on' === get_option('tmpcoder-parallax-multi-layer', 'on') ) {
    require TMPCODER_PLUGIN_DIR . 'inc/extensions/tmpcoder-parallax.php';
}

// Particles
if ( 'on' === get_option('tmpcoder-particles', 'on') ) {
    require TMPCODER_PLUGIN_DIR . 'inc/extensions/tmpcoder-particles.php';
} 

// Sticky Header
if ( 'on' === get_option('tmpcoder-sticky-section', 'on') ) {
    require TMPCODER_PLUGIN_DIR . 'inc/extensions/tmpcoder-sticky-section.php';
}

// Templates Library
require TMPCODER_PLUGIN_DIR . 'inc/admin/includes/tmpcoder-templates-library.php';

// Floating Effects
if ( 'on' === get_option('tmpcoder-floating-effects', 'on') ) {
    require TMPCODER_PLUGIN_DIR.'inc/extensions/tmpcoder-floating-effects.php';
    add_action( 'elementor/frontend/after_register_scripts', 'tmpcoder_register_floating_effects_scripts' );
}

function tmpcoder_register_floating_effects_scripts(){

    wp_register_script(
        'tmpcoder-anime',
        TMPCODER_PLUGIN_URI . 'assets/js/lib/floating-effects/anime' . tmpcoder_script_suffix() . '.js',
        array( 'jquery' ),
        tmpcoder_get_plugin_version(),
        true
    );

    wp_register_script(
        'tmpcoder-feffects',
        TMPCODER_PLUGIN_URI . 'assets/js/lib/floating-effects/floating-effects' . tmpcoder_script_suffix() . '.js',
        array( 'jquery' ),
        tmpcoder_get_plugin_version(),
        true
    );

    wp_localize_script(
        'tmpcoder-feffects',
        'TmpcoderFESettings',
        array(
            'papro_installed' => true,
        )
    );
}

