<?php
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Parallax_Scroll {

    /**
     * Load Script
     *
     * @var $load_script
     */
    private static $load_script = null;

    public function __construct() {

        // Register Scripts
        add_action( 'elementor/frontend/before_register_scripts', [ $this, 'tmpcoder_register_scripts' ], 998 );

        // Enqueue the required JS file.
        add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_action( 'elementor/element/section/section_background/after_section_end', [$this, 'register_controls'], 10);
        add_action( 'elementor/frontend/section/before_render', [$this, '_before_render'], 10, 1);
        add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );

        add_action( 'elementor/frontend/before_render', array( $this, 'check_script_enqueue' ) );

        // FLEXBOX
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10);
        add_action('elementor/frontend/container/before_render', [$this, '_before_render'], 10, 1);
        add_action( 'elementor/container/print_template', [ $this, '_print_template' ], 10, 2 );
    }

    public function register_controls( $element ) {
        $element->start_controls_section(
            'tmpcoder_section_parallax',
            [
                'tab' => Controls_Manager::TAB_STYLE,
                'label' => esc_html('Parallax - Spexo Addons'),
            ]
        );

        $element->add_control(
            'tmpcoder_parallax',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview editor-tmpcoder-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
                'separator' => 'after'
            ]
        );
        
        if ( 'on' === get_option('tmpcoder-parallax-background', 'on') ) {

        // $element->add_control(
        //     'parallax_video_tutorial',
        //     [
        //         'raw' => '<br><a href="#" target="_blank">Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></span></a>',
        //         'type' => Controls_Manager::RAW_HTML,
        //     ]
        // );

        $element->add_control(
            'tmpcoder_enable_jarallax',
            [
                'type'  => Controls_Manager::SWITCHER,
                'label' => __('Enable Background Parallax', 'sastra-essential-addons-for-elementor'),
                'default' => 'no',
                'label_on' => __('Yes', 'sastra-essential-addons-for-elementor'),
                'label_off' => __('No', 'sastra-essential-addons-for-elementor'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'prefix_class' => 'tmpcoder-jarallax-'
            ]
        );

        $element->add_control(
            'speed',
            [
                'label' => __( 'Animation Speed', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1.0,
                'max' => 2.0,
                'step' => 0.1,
                'default' => 1.4,
                'render_type' => 'template',
                'condition' => [
                    'tmpcoder_enable_jarallax' => 'yes'
                ]
            ]
        );

        if ( tmpcoder_is_availble() ) {
            if ( method_exists('\TMPCODER\Extensions\TMPCODER_Parallax_Scroll_Pro','add_control_ambient_animate') ){
                \TMPCODER\Extensions\TMPCODER_Parallax_Scroll_Pro::add_control_ambient_animate($element);
            }
        } else {
            $element->add_control(
                'ambient_animate',
                [
                    // Translators: %s is the icon.
                    'label' => sprintf( __( 'Ambient Animation %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
                    'type' => Controls_Manager::SWITCHER,
                    'classes' => 'tmpcoder-pro-control',
                    'condition' => [
                        'tmpcoder_enable_jarallax' => 'yes'
                    ]
                ]
            );
        }

        if ( tmpcoder_is_availble() ) {
            \TMPCODER\Extensions\TMPCODER_Parallax_Scroll_Pro::add_control_scroll_effect($element);
        } else {
            $element->add_control(
                'scroll_effect',
                [
                    'label' => __( 'Scrolling Effect', 'sastra-essential-addons-for-elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'scroll',
                    'options' => [
                        'scroll' => esc_html__( 'Scroll', 'sastra-essential-addons-for-elementor' ),
                        'scale'  => esc_html__( 'Zoom', 'sastra-essential-addons-for-elementor' ),
                        'pro-op' => esc_html__( 'Opacity (Pro)', 'sastra-essential-addons-for-elementor' ),
                        'pro-sclo' => esc_html__('Scale Opacity (Pro)', 'sastra-essential-addons-for-elementor'),
                        'pro-scrlo' => esc_html__( 'Scroll Opacity (Pro)', 'sastra-essential-addons-for-elementor' )
                    ],
                    'render_type' => 'template',
                    'condition' => [
                        'tmpcoder_enable_jarallax' => 'yes',
                        'ambient_animate!' => 'yes',
                    ]
                ]
            );

            // Upgrade to Pro Notice
            tmpcoder_upgrade_pro_notice( $element, Controls_Manager::RAW_HTML, 'parallax-background', 'scroll_effect', ['pro-op','pro-sclo','pro-scrlo'] );
        }

        $element->add_control(
            'bg_image',
            [
                'label' => __( 'Choose Image', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'render_type' => 'template',
                'condition' => [
                    'tmpcoder_enable_jarallax' => 'yes'
                ]
            ]
        );

        } // end if ( 'on' === get_option('tmpcoder-parallax-background', 'on') ) {

        $element->add_control(
            'parallax_type_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        if ( 'on' === get_option('tmpcoder-parallax-multi-layer', 'on') ) {

        // $element->add_control(
        //     'parallax_multi_video_tutorial',
        //     [
        //         'raw' => '<a href="#" target="_blank">Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></span></a>',
        //         'type' => Controls_Manager::RAW_HTML,
        //     ]
        // );

        $element->add_control(
            'tmpcoder_enable_parallax_hover',
            [
                'type'  => Controls_Manager::SWITCHER,
                'label' => __('Enable Multi Layer Parallax', 'sastra-essential-addons-for-elementor'),
                'default' => 'no',
                'label_on' => __('Yes', 'sastra-essential-addons-for-elementor'),
                'label_off' => __('No', 'sastra-essential-addons-for-elementor'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'prefix_class' => 'tmpcoder-parallax-'
            ]
        );

        $element->add_control(
            'invert_direction',
            [
                'type'  => Controls_Manager::SWITCHER,
                'label' => __('Invert Animation Direction', 'sastra-essential-addons-for-elementor'),
                'default' => 'no',
                'label_on' => __('Yes', 'sastra-essential-addons-for-elementor'),
                'label_off' => __('No', 'sastra-essential-addons-for-elementor'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'condition' => [
                    'tmpcoder_enable_parallax_hover' => 'yes'
                ]
            ]
        );

        $element->add_control(
            'scalar_speed',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Animation Speed', 'sastra-essential-addons-for-elementor' ),
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0.0,
                        'max' => 100.0,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10.0,
                ],
                'condition' => [
                    'tmpcoder_enable_parallax_hover' => 'yes'
                ]
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'repeater_bg_image',
            [
                'label' => __( 'Choose Image', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::MEDIA,
				// 'dynamic' => [
				// 	'active' => true,
				// ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'render_type' => 'template',
            ]
        );

        $repeater->add_responsive_control(
            'layer_width',
            [
                'label' => esc_html__( 'Image Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 0,
                'max' => 1000,
                'step' => 10,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.tmpcoder-parallax-ml-children' => 'width: {{SIZE}}px !important;',
                ],      
            ]
        );

        $repeater->add_responsive_control(
            'layer_position_hr',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Horizontal Position (%)', 'sastra-essential-addons-for-elementor' ),
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.tmpcoder-parallax-ml-children' => 'left: {{SIZE}}% !important;',
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_responsive_control(
            'layer_position_vr',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Vertical Position (%)', 'sastra-essential-addons-for-elementor' ),
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.tmpcoder-parallax-ml-children' => 'top: {{SIZE}}% !important;',
                ],
            ]
        );

        $repeater->add_control(
            'data_depth',
            [
                'label' => __( 'Data Depth', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1.0,
                'max' => 2.0,
                'step' => 0.1,
                'default' => 0.4,
                'render_type' => 'template',
            ]
        );

        $element->add_control(
            'hover_parallax',
            [
                'label' => __( 'Repeater List', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'layer_position_vr' => [
                            'unit' => '%',
                            'size' => 30,
                        ],
                        'layer_position_hr' => [
                            'unit' => '%',
                            'size' => 40,
                        ],
                    ],
                    [
                        'layer_position_vr' => [
                            'unit' => '%',
                            'size' => 60,
                        ],
                        'layer_position_hr' => [
                            'unit' => '%',
                            'size' => 20,
                        ],
                    ],
                ],
                'condition' => [
                    'tmpcoder_enable_parallax_hover' => 'yes'
                ]
            ]
        );

        if ( ! tmpcoder_is_availble() ) {
            $element->add_control(
                'paralax_repeater_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 2 Layers are available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'tmpcoder-pro-notice',
                    'condition' => [
                        'tmpcoder_enable_parallax_hover' => 'yes'
                    ]
                ]
            );
        }

        } // end if ( 'on' === get_option('tmpcoder-parallax-multi-layer', 'on') )

        $element->end_controls_section();
    }

    public function _before_render( $element ) {
        // bail if any other element but section
        // output buffer controlling functions removed elements from live preview

        if ( $element->get_name() !== 'section' && $element->get_name() !== 'container' ) return;

        $settings = $element->get_settings_for_display();

        // Parallax Background
        if ( 'on' === get_option('tmpcoder-parallax-background', 'on') ) {
            if ( 'yes' === $settings['tmpcoder_enable_jarallax'] ) { 
                $element->add_render_attribute( '_wrapper', [
                    'class' => 'tmpcoder-jarallax',
                    'speed-data' => (isset($settings['speed']) ? $settings['speed'] : ''),
                    'bg-image' => (isset($settings['bg_image']) && isset($settings['bg_image']['url']) ? $settings['bg_image']['url'] : ''),
                    'scroll-effect' => (isset($settings['scroll_effect']) ? $settings['scroll_effect'] : ''),
                    'ambient-animate' => (isset($settings['ambient_animate']) ? $settings['ambient_animate'] : ''),
                ] );

                // if ( 'on' === get_option('tmpcoder-parallax-background', 'on') ) {
                //     echo '<div '. $element->get_render_attribute_string( '_wrapper' ) .'></div>';
                // }
            }
        }

        // Parallax Multi Layer
        if ( 'on' === get_option('tmpcoder-parallax-multi-layer', 'on') ) {
            if ( $settings['tmpcoder_enable_parallax_hover'] == 'yes' ) {
                 if ( $settings['hover_parallax'] ) {

                    echo '<div class="tmpcoder-parallax-multi-layer" scalar-speed="'. esc_attr($settings['scalar_speed']['size']) .'" direction="'. esc_attr($settings['invert_direction']) .'" style="overflow: hidden;">';

                    foreach (  $settings['hover_parallax'] as $key => $item ) {
                        if ( $key < 2 || tmpcoder_is_availble() ) {
                            echo '<div data-depth="'. esc_attr($item['data_depth']) .'" style-top="'. esc_attr($item['layer_position_vr']['size']) .'%" style-left="'. esc_attr($item['layer_position_hr']['size']) .'%" class="tmpcoder-parallax-ml-children elementor-repeater-item-'. esc_attr($item['_id']) .'">';
                                echo '<img src="'. esc_url($item['repeater_bg_image']['url']) .'">';
                            echo '</div>';
                        }
                    }
                     
                    echo '</div>';
                 }
            }   
        }
    }

    public function _print_template( $template, $widget ) {
        ob_start();
        
        if ( 'on' === get_option('tmpcoder-parallax-background', 'on') ) {
            echo '<div class="tmpcoder-jarallax" speed-data-editor="{{settings.speed}}" scroll-effect-editor="{{settings.scroll_effect}}" bg-image-editor="{{settings.bg_image.url}}" ambient-animate-editor="{{settings.ambient_animate}}"></div>';
        }
        
        // Multi Layer
        if ( 'on' === get_option('tmpcoder-parallax-multi-layer', 'on') ) {
            if ( ! tmpcoder_is_availble() ) {
                ?>
                <# if ( settings.hover_parallax.length && settings.tmpcoder_enable_parallax_hover == 'yes') { #>
                    <div class="tmpcoder-parallax-multi-layer" direction="{{settings.invert_direction}}" scalar-speed="{{settings.scalar_speed.size}}" data-relative-input="true" style="overflow: hidden;">
                    <# _.each( settings.hover_parallax, function( item, index ) { #>
                    <# if ( index > 1 ) return; #>
                        <div data-depth="{{item.data_depth}}" class="tmpcoder-parallax-ml-children elementor-repeater-item-{{ item._id }}">  
                            <img src="{{item.repeater_bg_image.url}}">
                        </div>
                    <# }); #>
                    </div>
                <# } #>
                <?php
            } else {
                ?>
                <# if ( settings.hover_parallax.length && settings.tmpcoder_enable_parallax_hover == 'yes') { #>
                    <div class="tmpcoder-parallax-multi-layer" direction="{{settings.invert_direction}}" scalar-speed="{{settings.scalar_speed.size}}" data-relative-input="true" style="overflow: hidden;">
                    <# _.each( settings.hover_parallax, function( item ) { #>
                        <div data-depth="{{item.data_depth}}" class="tmpcoder-parallax-ml-children elementor-repeater-item-{{ item._id }}">  
                            <img src="{{item.repeater_bg_image.url}}">
                        </div>
                    <# }); #>
                    </div>
                <# } #>
                <?php
            }
        }

        $parallax_content = ob_get_contents();

        ob_end_clean();
        return $template . $parallax_content;
    }

    public static function enqueue_scripts() {
        
        if ( ! wp_script_is( 'tmpcoder-jarallax', 'enqueued' ) ) {
            wp_enqueue_script( 'tmpcoder-jarallax' );
        }

        if ( ! wp_script_is( 'tmpcoder-parallax-hover', 'enqueued' ) ) {
            wp_enqueue_script( 'tmpcoder-parallax-hover' );
        }
    }

    public function check_script_enqueue($element){

        if ( self::$load_script ) {
            return;
        }

        $settings = $element->get_active_settings();

        if ( (isset($settings[ 'tmpcoder_enable_jarallax' ]) && $settings[ 'tmpcoder_enable_jarallax' ] == 'yes') || (isset($settings[ 'tmpcoder_enable_parallax_hover' ]) && $settings[ 'tmpcoder_enable_parallax_hover' ] == 'yes') ) {

            $this->enqueue_scripts();

            self::$load_script = true;

            remove_action( 'elementor/frontend/before_render', array( $this, 'check_script_enqueue' ) );
        }
    }

    public function tmpcoder_register_scripts(){

        wp_register_script(
            'tmpcoder-jarallax',
            TMPCODER_PLUGIN_URI.'assets/js/lib/jarallax/jarallax'.tmpcoder_script_suffix().'.js',
            [ 'jquery' ],
            tmpcoder_get_plugin_version(),
            true
        );

        wp_register_script(
            'tmpcoder-parallax-hover',
            TMPCODER_PLUGIN_URI.'assets/js/lib/parallax/parallax'.tmpcoder_script_suffix().'.js',
            [ 'jquery' ],
            tmpcoder_get_plugin_version(),
            true
        );
    }

}

new TMPCODER_Parallax_Scroll();