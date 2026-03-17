<?php
namespace TMPCODER\Widgets;
use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Icons;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class TMPCODER_Product_Media_List extends Widget_Base {

    public function get_name() {
        return 'tmpcoder-product-media-list';
    }

    public function get_title() {
        return esc_html__( 'Product Media List', 'sastra-essential-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return tmpcoder_show_theme_buider_widget_on('type_single_product') ? [ 'tmpcoder-woocommerce-builder-widgets'] : [];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
            ]
        );
      
        $this->add_control(
            'enable_lightbox',
            [
                'label' => esc_html__( 'Enable Lightbox', 'sastra-essential-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'sastra-essential-addons-for-elementor' ),
                'label_off' => esc_html__( 'No', 'sastra-essential-addons-for-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'thumb_width',
            [
                'label' => esc_html__( 'Image Width', 'sastra-essential-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 600 ],
                    '%' => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-thumb-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumb_height',
            [
                'label' => esc_html__( 'Image Height', 'sastra-essential-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 800 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-thumb-wrapper img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumb_spacing',
            [
                'label' => esc_html__( 'Spacing Between Images', 'sastra-essential-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default' => [
                    'size' => 12,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-advanced-thumbnails .tmpcoder-thumb-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    public function render() {
        $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
    
        global $product;
        if ( ! is_a( $product, 'WC_Product' ) ) {
            $product = wc_get_product();
        }

        // Get Product
        if( tmpcoder_is_preview_mode() ){
            $lastId  = tmpcoder_get_last_product_id();
            // Get Product
            $product = wc_get_product($lastId);
        }
        else
        {
            // Get Product
            $product = wc_get_product();
        }

        if ( ! $product ) {
            return;
        }

        if ( ! $product ) return;

        $attachment_ids = $product->get_gallery_image_ids();
        $main_image_id = $product->get_image_id();

        echo '<div class="tmpcoder-advanced-thumbnails">';

        if ( $main_image_id ) {
            $main_img_url = wp_get_attachment_url( $main_image_id );
            $main_img_html = wp_get_attachment_image( $main_image_id, 'full', false, [
                'class' => 'woocommerce-product-gallery__image zoom tmpcoder-zoomable',
            ] );

            if ( $settings['enable_lightbox'] === 'yes' ) {
                echo '<a href="' . esc_url( $main_img_url ) . '" data-lightbox="product-gallery"><div class="tmpcoder-thumb-wrapper">' . wp_kses_post($main_img_html) . '</div></a>';
            } else {
                echo '<div class="tmpcoder-thumb-wrapper">' . wp_kses_post($main_img_html) . '</div>';
            }
        }

        foreach ( $attachment_ids as $attachment_id ) {
            $thumb_img_url = wp_get_attachment_url( $attachment_id );
            $thumb_html = wp_get_attachment_image( $attachment_id, 'full', false, [
                'class' => 'woocommerce-product-gallery__image zoom tmpcoder-zoomable',
            ] );

             if ( $settings['enable_lightbox'] === 'yes' ) {
                echo '<a href="' . esc_url( $thumb_img_url ) . '" data-lightbox="product-gallery"><div class="tmpcoder-thumb-wrapper">' . wp_kses_post($thumb_html) . '</div></a>';
            } else {
                echo '<div class="tmpcoder-thumb-wrapper">' . wp_kses_post($thumb_html) . '</div>';
            }
        }

        echo '</div>';
        return;

        // Default WooCommerce output
        if ( function_exists( 'woocommerce_show_product_images' ) ) {
            woocommerce_show_product_images();
        }
    }
}
