<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Breadcrumb  extends Widget_Base{

    public function get_name(){
        return 'tmpcoder_breadcrumb';
    }

    public function get_title(){
        return 'Breadcrumb';
    }

    public function get_icon(){
        return 'tmpcoder-icon eicon-yoast';
    }

    public function get_categories(){
        return [ 'tmpcoder-widgets-category' ];
    }

    public function get_custom_help_url() {
        return TMPCODER_NEED_HELP_URL;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wc_style_warning',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The style of this widget can`t display in elementor editor, so pleace contact the frontend of your site', 'sastra-essential-addons-for-elementor' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_responsive_control(
            'section_alignment',
            [
                'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor'  ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor'  ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor'  ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor'  ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'sastra-essential-addons-for-elementor'  ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .page-banner' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => esc_html__( 'Show Title', 'sastra-essential-addons-for-elementor'  ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'sastra-essential-addons-for-elementor'  ),
                'label_off' => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor'  ),
                'return_value' => 'yes',
                'default' => 'yes',
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
					'p' => 'p'
				],
				'default' => 'h1',
                'condition' => ['show_title' => 'yes']
			]
		);

        $this->add_control(
        'page_title_color',
            [
                'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sub-banner-title,{{WRAPPER}} .page-banner .sub-banner-title span' => 'color: {{VALUE}}',
                ],
                'condition' => ['show_title' => 'yes']
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'sastra-essential-addons-for-elementor' ),
                'name' => 'title_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .sub-banner-title,{{WRAPPER}} .sub-banner-title span',
                'condition' => ['show_title' => 'yes']
            ]
        );

        
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sub-banner-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_title' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .sub-banner-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_title' => 'yes']
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'link_section',
            [
                'label' => esc_html__( 'Link (Breadcrumb)', 'sastra-essential-addons-for-elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'show_link',
            [
                'label' => esc_html__( 'Show Breadcrumb', 'sastra-essential-addons-for-elementor'  ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'sastra-essential-addons-for-elementor'  ),
                'label_off' => esc_html__( 'Hide', 'sastra-essential-addons-for-elementor'  ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );        

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__( 'Link Color', 'sastra-essential-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .page-banner label a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .page-banner .custom-delimiter' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .page-banner a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .page-banner span a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .woocommerce-breadcrumb span a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .woocommerce-breadcrumb' => 'color: {{VALUE}}',

                ],
                'condition' => ['show_link' => 'yes']
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => esc_html__( 'Link Hover Color', 'sastra-essential-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .page-banner label a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .woocommerce-breadcrumb span a:hover' => 'color: {{VALUE}}!important',
                ],
                'condition' => ['show_link' => 'yes']
            ]
        );

        $this->add_control(
            'lable_color',
            [
                'label' => esc_html__( 'Label Color', 'sastra-essential-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .page-banner label' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .page-banner span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .woocommerce-breadcrumb span' => 'color: {{VALUE}}',
                ],
                'condition' => ['show_link' => 'yes']
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'sastra-essential-addons-for-elementor' ),
                'name' => 'link_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .page-banner label a,{{WRAPPER}} .page-banner label, {{WRAPPER}} .page-banner .woocommerce-breadcrumb span, {{WRAPPER}}.page-banner .current-item-name, {{WRAPPER}} .woocommerce-breadcrumb, {{WRAPPER}} .page-banner .custom-delimiter',
                'condition' => ['show_link' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'link_spacing',
            [
                'label' => esc_html__( 'Space Between', 'sastra-essential-addons-for-elementor'  ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => ['size' => '5'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .page-banner label , {{WRAPPER}} .woocommerce-breadcrumb span' => 'padding-right: {{SIZE}}{{UNIT}} !important; padding-left:{{SIZE}}{{UNIT}}!important;',
                ],
                'condition' => ['show_link' => 'yes']
            ]
        );

        $this->add_control(
            'tmpcoder_enable_title_attribute',
            [
                'label' => esc_html__( 'Enable Title Attribute', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'render_type' => 'template',
                'condition' => ['show_link' => 'yes']
            ]
        );

        $this->end_controls_section();
    }

    protected function render(){

        $settings   = $this->get_settings_for_display();
        $show_title = !empty($settings['show_title']) && $settings['show_title'] != ''?$settings['show_title']:'notshow';

        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="page-banner">
                    <?php if ( is_404() ){ 
                     
                            if ($settings['show_title'] == 'yes') {

                                echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .' class="sub-banner-title '.esc_attr($show_title).'">';
                                echo esc_html( __('404', 'sastra-essential-addons-for-elementor' ) ); 
                                echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .'>';
                            }
                        
                        }else{

                        if (!is_home()) {
                            
                            if (is_archive()) {

                                $page_title = get_the_archive_title();

                                if (class_exists('WooCommerce')) {
                                    if (is_shop()) {

                                        $shop_page_id = get_option('woocommerce_shop_page_id');
                                            
                                        if ($shop_page_id)
                                        {
                                            $page_title = get_the_title($shop_page_id);
                                        }
                                        else
                                        {
                                            $page_title = get_the_archive_title();
                                        }
                                    }
                                }
                            }
                            elseif (is_singular()) {

                                if (tmpcoder_is_preview_mode()) {

                                    $post_meta = get_post_meta(get_the_ID(), 'tmpcoder_template_type');
                                    
                                    if (isset($post_meta[0]) && $post_meta[0] == 'type_single_post')
                                    {
                                        $page_title = get_the_title(tmpcoder_get_last_post_id());
                                    }
                                    elseif(isset($post_meta[0]) && $post_meta[0] == 'type_single_product')
                                    {
                                        $page_title = get_the_title(tmpcoder_get_last_product_id());
                                    }
                                    else
                                    {
                                        $page_title = get_the_title();
                                    }
                                }
                                else
                                {
                                    $page_title = get_the_title(get_queried_object_id());
                                }
                            }
                            else
                            {
                                $page_title = get_the_title(get_queried_object_id());
                            } 

                            if (is_search()) {

                                if ($settings['show_title'] == 'yes') {

                                    echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .' class="sub-banner-title '.esc_attr($show_title).'">';
                                    echo 'Search results for: '. get_search_query(); 
                                    echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .'>';
                                }
                             
                            }else{
                                if ($settings['show_title'] == 'yes') {
                                    echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .' class="sub-banner-title '.esc_attr($show_title).'">';                                                      
                                    echo wp_kses($page_title,['span','label','a']);    
                                    echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .'>';
                                }
                            }
                            
                        }else{

                            $page_title = get_the_archive_title();
                            if ($settings['show_title'] == 'yes') {
                                echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .' class="sub-banner-title '.esc_attr($show_title).'">';                              
                            }
                            echo wp_kses($page_title,['span','label','a']);    
                            echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['post_title_tag']) ) .'>';

                        }
                    }  

                    if( tmpcoder_is_preview_mode() ){

                        $post_meta = get_post_meta(get_the_ID(), 'tmpcoder_template_type');
                                        
                        if (isset($post_meta[0]) && $post_meta[0] == 'type_single_post')
                        {
                            global $post;
                            $post = get_post(tmpcoder_get_last_post_id());
                        }
                        else
                        {
                            if (class_exists('WooCommerce')) {
                                global $product;
                                $product = wc_get_product(tmpcoder_get_last_product_id());
                            } 
                        }
                    }

                    if (isset($settings['show_link']) && $settings['show_link'] == 'yes') {

                        $enable_title_attr = false;
                        if (isset($settings['tmpcoder_enable_title_attribute']) && $settings['tmpcoder_enable_title_attribute'] == 'yes') {
                            $enable_title_attr = true;        
                        }

                        tmpcoder_breadcrumb("",$enable_title_attr); 
                    }

                    ?>
                </div>
            </div>
        </div>

        <?php

    }    
}