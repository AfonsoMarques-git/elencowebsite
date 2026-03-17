<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * TMPCODER Site Logo widget
 *
 * TMPCODER widget for Site Logo.
 *
 * @since 1.3.0
 */
class TMPCODER_Site_Logo extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'site-logo';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Site Logo', 'sastra-essential-addons-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'tmpcoder-icon eicon-site-logo';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'tmpcoder-widgets-category' ];
	}

	/**
	 * Register Site Logo controls.
	 *
	 * @since 1.5.7
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_content_site_logo_controls();
		$this->register_site_logo_styling_controls();
		$this->register_site_logo_caption_styling_controls();
	}

	/**
	 * Register Site Logo General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_content_site_logo_controls() {
		$this->start_controls_section(
			'section_site_image',
			[
				'label' => __( 'Site Logo', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'site_logo_fallback',
			[
				'label'       => __( 'Custom Image', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'yes'         => __( 'Yes', 'sastra-essential-addons-for-elementor' ),
				'no'          => __( 'No', 'sastra-essential-addons-for-elementor' ),
				'default'     => 'no',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'custom_image',
			[
				'label'     => __( 'Add Image', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'site_logo_fallback' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'site_logo_size',
				'label'   => __( 'Image Size', 'sastra-essential-addons-for-elementor' ),
				'default' => 'medium',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'              => __( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'left'   => [
						'title' => __( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'            => 'center',
				'selectors'          => [
					'{{WRAPPER}} .tmpcoder-site-logo-container, {{WRAPPER}} .tmpcoder-site-logo, {{WRAPPER}} .tmpcoder-caption-width figcaption' => 'text-align: {{VALUE}};',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'caption_source',
			[
				'label'   => __( 'Caption', 'sastra-essential-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'no'  => __( 'No', 'sastra-essential-addons-for-elementor' ),
					'yes' => __( 'Yes', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'no',
			]
		);

		$this->add_control(
			'caption',
			[
				'label'       => __( 'Custom Caption', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Enter caption', 'sastra-essential-addons-for-elementor' ),
				'condition'   => [
					'caption_source' => 'yes',
				],
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'link_to',
			[
				'label'   => __( 'Link', 'sastra-essential-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'sastra-essential-addons-for-elementor' ),
					'none'    => __( 'None', 'sastra-essential-addons-for-elementor' ),
					'file'    => __( 'Media File', 'sastra-essential-addons-for-elementor' ),
					'custom'  => __( 'Custom URL', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition'   => [
					'link_to' => 'custom',
				],
				'show_label'  => false,
			]
		);

		$this->add_control(
			'open_lightbox',
			[
				'label'     => __( 'Lightbox', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default' => __( 'Default', 'sastra-essential-addons-for-elementor' ),
					'yes'     => __( 'Yes', 'sastra-essential-addons-for-elementor' ),
					'no'      => __( 'No', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'link_to' => 'file',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label'   => __( 'View', 'sastra-essential-addons-for-elementor' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);
		$this->end_controls_section();
	}
	/**
	 * Register Site Image Style Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_site_logo_styling_controls() {

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		$this->start_controls_section(
			'section_style_site_logo_image',
			[
				'label' => __( 'Site logo', 'sastra-essential-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'              => __( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'unit' => '%',
				],
				'tablet_default'     => [
					'unit' => '%',
				],
				'mobile_default'     => [
					'unit' => '%',
				],
				'size_units'         => [ '%', 'px', 'vw' ],
				'range'              => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .tmpcoder-site-logo .tmpcoder-site-logo-container img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label'              => __( 'Max Width', 'sastra-essential-addons-for-elementor' ) . ' (%)',
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'unit' => '%',
				],
				'tablet_default'     => [
					'unit' => '%',
				],
				'mobile_default'     => [
					'unit' => '%',
				],
				'size_units'         => [ '%' ],
				'range'              => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .tmpcoder-site-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'separator_panel_style',
			[
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'site_logo_background_color',
			[
				'label'     => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-site-logo-set .tmpcoder-site-logo-container' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'site_logo_image_border',
			[
				'label'       => __( 'Border Style', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => false,
				'options'     => [
					'none'   => __( 'None', 'sastra-essential-addons-for-elementor' ),
					'solid'  => __( 'Solid', 'sastra-essential-addons-for-elementor' ),
					'double' => __( 'Double', 'sastra-essential-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'sastra-essential-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'sastra-essential-addons-for-elementor' ),
				],
				'selectors'   => [
					'{{WRAPPER}} .tmpcoder-site-logo-container .tmpcoder-site-logo-img' => 'border-style: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'site_logo_image_border_size',
			[
				'label'      => __( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'    => '1',
					'bottom' => '1',
					'left'   => '1',
					'right'  => '1',
					'unit'   => 'px',
				],
				'condition'  => [
					'site_logo_image_border!' => 'none',
				],
				'selectors'  => [
					'{{WRAPPER}} .tmpcoder-site-logo-container .tmpcoder-site-logo-img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'site_logo_image_border_color',
			[
				'label'     => __( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'condition' => [
					'site_logo_image_border!' => 'none',
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-site-logo-container .tmpcoder-site-logo-img' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'              => __( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .tmpcoder-site-logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_box_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .tmpcoder-site-logo img',
			]
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
			'normal',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label'     => __( 'Opacity', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-site-logo img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .tmpcoder-site-logo img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);
		$this->add_control(
			'opacity_hover',
			[
				'label'     => __( 'Opacity', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-site-logo:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'background_hover_transition',
			[
				'label'     => __( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-site-logo img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .tmpcoder-site-logo:hover img',
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'sastra-essential-addons-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	/**
	 * Register Site Logo style Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_site_logo_caption_styling_controls() {
		$this->start_controls_section(
			'section_style_caption',
			[
				'label'     => __( 'Caption', 'sastra-essential-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'caption_source!' => 'none',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_control(
			'caption_background_color',
			[
				'label'     => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .widget-image-caption' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .widget-image-caption',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'caption_text_shadow',
				'selector' => '{{WRAPPER}} .widget-image-caption',
			]
		);

		$this->add_responsive_control(
			'caption_padding',
			[
				'label'              => __( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .widget-image-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'frontend_available' => true,
			]
		);
		$this->add_responsive_control(
			'caption_space',
			[
				'label'              => __( 'Spacing', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'            => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors'          => [
					'{{WRAPPER}} .widget-image-caption' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: 0px;',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Check if the current widget has caption
	 *
	 * @access private
	 * @since 1.3.0
	 *
	 * @param array $settings returns settings.
	 *
	 * @return boolean
	 */
	private function has_caption( $settings ) {
		return ( ! empty( $settings['caption_source'] ) && 'no' !== $settings['caption_source'] );
	}

	/**
	 * Get the caption for current widget.
	 *
	 * @access private
	 * @since 1.3.0
	 * @param array $settings returns the caption.
	 *
	 * @return string
	 */
	private function get_caption( $settings ) {
		$caption = '';
		if ( 'yes' === $settings['caption_source'] ) {
			$caption = ! empty( $settings['caption'] ) ? $settings['caption'] : '';
		}
		return $caption;
	}

	/**
	 * Render Site Image output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @param array $size returns the size of an image.
	 * @access public
	 */
	public function site_image_url( $size ) {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		if ( ! empty( $settings['custom_image']['url'] ) ) {
			$logo = wp_get_attachment_image_src( $settings['custom_image']['id'], $size, true );
		}else if( function_exists('tmpcoder_get_theme_logo') && ! empty( tmpcoder_get_theme_logo() ) ){
            $logo = array( tmpcoder_get_theme_logo() );
		}else if ( function_exists('tmpcoder_get_theme_text') && ! empty( tmpcoder_get_theme_text() ) ) {
			$logo = array( tmpcoder_get_theme_text() );
        }
	 	else {
			$logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), $size, true );
		}
		return $logo[0];
	}

	/**
	 * Render Site Image output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() {
		$link     = '';
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$has_caption = $this->has_caption( $settings );
		$site_image = $this->site_image_url($settings['site_logo_size_size']);

		$this->add_render_attribute( 'wrapper', 'class', 'tmpcoder-site-logo tmpcoder-site-logo1' );

		if ( 'file' === $settings['link_to'] ) {
				$link = $site_image;
				$this->add_render_attribute( 'link', 'href', $link );
		} elseif ( 'default' === $settings['link_to'] ) {
			$link = site_url();
			$this->add_render_attribute( 'link', 'href', $link );
		} else {
			$link = $this->get_link_url( $settings );

			if ( $link ) {
				$this->add_link_attributes( 'link', $link );
			}
		}
		$class = '';
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$class = 'elementor-non-clickable';
		} else {
			$class = 'elementor-clickable';
		}
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); // get_render_attribute_string ?>>
		<?php if ( $has_caption ) : ?>
				<figure class="wp-caption">
		<?php endif; ?>
		<?php if ( $link ) : ?>
					<?php
					if ( 'no' === $settings['open_lightbox'] ) {
						$class = 'elementor-non-clickable';
					}
					?>
				<?php echo wp_kses_post('<a data-elementor-open-lightbox="'. esc_attr( $settings['open_lightbox'] ).'"  class="'. esc_attr( $class ).'" '. $this->get_render_attribute_string( 'link' ). '>'); ?>
		<?php endif; ?>
		<?php

        $size = $settings['site_logo_size_size'];

		$img_animation = '';

		if ( 'custom' !== $size ) {
			$image_size = $size;
		} else {
			require_once ELEMENTOR_PATH . 'includes/libraries/bfi-thumb/bfi-thumb.php';

			$image_dimension = $settings['site_logo_size_custom_dimension'];

			$image_size = [
				// Defaults sizes.
				0           => null, // Width.
				1           => null, // Height.

				'bfi_thumb' => true,
				'crop'      => true,
			];

			$has_custom_size = false;
			if ( ! empty( $image_dimension['width'] ) ) {
				$has_custom_size = true;
				$image_size[0]   = $image_dimension['width'];
			}

			if ( ! empty( $image_dimension['height'] ) ) {
				$has_custom_size = true;
				$image_size[1]   = $image_dimension['height'];
			}

			if ( ! $has_custom_size ) {
				$image_size = 'full';
			}	
		}

		if ( ! empty( $settings['custom_image']['url'] ) ) {
			$image_data = wp_get_attachment_image_src( $settings['custom_image']['id'], $image_size, true );
		} else if( function_exists('tmpcoder_get_theme_logo_details') && ! empty( tmpcoder_get_theme_logo_details() ) ){
            $image_data = tmpcoder_get_theme_logo_details();
		}else if ( function_exists('tmpcoder_get_theme_text') && ! empty( tmpcoder_get_theme_text() ) ) {
			$image_data = array( tmpcoder_get_theme_text() );
        } else if ( ! empty( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) ) ) ) {
			$image_data = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), $image_size, true );
			$image_data['attachment_id'] = get_theme_mod( 'custom_logo' );
        } else {
			echo wp_kses_post('<h2>'.get_bloginfo('name').'</h2>');
		}

		$site_image_class = 'elementor-animation-';

		if ( ! empty( $settings['hover_animation'] ) ) {
			$img_animation = $settings['hover_animation'];
		}
		if ( ! empty( $image_data ) ) {
			$image_url = isset($image_data['url']) ? $image_data['url'] : $image_data[0];

			$alt_text = '';
			$image_title = '';

			if ( isset($image_url) && $image_url != "" ) {

				if (isset($image_data['attachment_id']) && $image_data['attachment_id'] != '') {
					$image_id = $image_data['attachment_id'];		
				}
				elseif (isset($settings['custom_image']['id']) && $settings['custom_image']['id'] !='') {
					$image_id = $settings['custom_image']['id'];
				}
				else
				{
					$image_id = isset($image_data['id']) ? $image_data['id'] : $this->tmpcoder_get_image_id_from_url($image_url);
				}

				$alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);

				$image_title = get_the_title( $image_id );

				$settings[ 'site_logo_size' ] = ['id' => $image_id];
				$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'site_logo_size' );
			}
		}

		$class_animation = $site_image_class . $img_animation;

		?>
        <?php if ( isset($image_url) && $image_url != "" ){ ?>
			<div class="tmpcoder-site-logo-set">           
				<div class="tmpcoder-site-logo-container">
					<?php 
					if ($image_html) {

						$image_original_class = 'wp-image-'.$image_id;
						$new_class = $image_original_class.' '.$class_animation;	
						$image_html = str_replace($image_original_class, $new_class, $image_html);

					 	$image_html = preg_replace(
						    '/\salt="([^"]*)"/',
						    ' title="' . esc_attr( $image_title ) . '" alt="$1"',
						    $image_html
						);

						echo wp_kses_post($image_html);
					}
					else
					{
						?>
						<img class="tmpcoder-site-logo-img <?php echo esc_attr( $class_animation ); ?>"  src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr($alt_text); ?>"/>
						<?php
					}
					?>
				</div>
			</div>
        <?php } ?>
		<?php if ( $link ) : ?>
			</a>
		<?php endif; ?>
		<?php
		if ( $has_caption ) :
			$caption_text = $this->get_caption( $settings );
			?>
			<?php if ( ! empty( $caption_text ) ) : ?>
					<div class="tmpcoder-caption-width"> 
						<figcaption class="widget-image-caption wp-caption-text"><?php echo wp_kses_post( $caption_text ); ?></figcaption>
					</div>
			<?php endif; ?>
				</figure>
		<?php endif; ?>
		</div>  
		<?php
	}

    /**
	 * Retrieve Site Logo widget link URL.
	 *
	 * @since 1.3.0
	 * @access private
	 *
	 * @param array $settings returns settings.
	 * @return array|string|false An array/string containing the link URL, or false if no link.
	 */
	private function get_link_url( $settings ) {
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return $settings['link'];
		}

		if ( 'default' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return site_url();
		}
	}

	function tmpcoder_get_image_id_from_url($image_url='') {
	    global $wpdb;
	    $attachment_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $image_url));
	    return $attachment_id;
	}
}
