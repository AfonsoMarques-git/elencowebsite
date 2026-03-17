<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Post_Thumbnail extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-post-media';
	}

	public function get_title() {
		return esc_html__( 'Post Thumbnail', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-featured-image';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_post') ? [ 'tmpcoder-theme-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'image', 'media', 'post', 'thumbnail', 'video', 'gallery' ];
	}

	protected function register_controls() {

		// Get Available Meta Keys

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_featured_media',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'featured_media_image_crop',
				'default' => 'full',
			]
		);

		$this->add_responsive_control(
            'featured_media_align',
            [
                'label' => esc_html__( 'Align', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'label_block' => false,
                'options' => [
					'left'    => [
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
					'{{WRAPPER}} .tmpcoder-featured-media-wrap' => 'text-align: {{VALUE}}',
				],
            ]
        );

		$this->add_control(
			'featured_media_caption',
			[
				'label' => esc_html__( 'Featured Image Caption', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'image_link_url',
			[
				'label' => esc_html__( 'Link', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'media-file' => esc_html__( 'Media File', 'sastra-essential-addons-for-elementor' ),
					'custom-url' => esc_html__( 'Custom Url', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'none',
			]
		);

		$this->add_control(
			'image_custom_link',
			[
				'label'       => __( 'Link', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'image_link_url' => 'custom-url',
				],
				'show_label'  => false,
			]
		);


		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles ====================
		// Section: Image ------------
		$this->start_controls_section(
			'section_style_media',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'media_width',
			[
				'label'   => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => [
					'auto'=> esc_html__( 'Auto', 'sastra-essential-addons-for-elementor' ),
					'custom' => esc_html__( 'Custom', 'sastra-essential-addons-for-elementor' ),
				],
				'selectors_dictionary' => [
					'auto' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-image' => 'width: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'media_custom_width',
			[
				'label' => esc_html__( 'Custom Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-image' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-featured-media-image img' => 'width: 100%;',
				],
				'condition' => [
					'media_width' => 'custom'
				]
			]
		);

		$this->add_responsive_control(
			'media_custom_height',
			[
				'label' => esc_html__( 'Custom Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-image' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-featured-media-image img' => 'height: 100%;',
				],
				'condition' => [
					'media_width' => 'custom'
				]
			]
		);

		$this->add_responsive_control(
			'media_max_width',
			[
				'label' => esc_html__( 'Max Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-image' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'media_width' => 'custom'
				]
			]
		);


		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => esc_html__( 'Opacity', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label' => esc_html__( 'Opacity', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}}:hover img',
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} img',
			]
		);


		$this->end_controls_section();

		// Styles ====================
		// Section: Image Caption ----
		$this->start_controls_section(
			'section_style_image_caption',
			[
				'label' => esc_html__( 'Image Caption', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'featured_media_caption',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'image_caption_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-caption span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_caption_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-caption span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_caption_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-caption span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_caption_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-featured-media-caption span',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'image_caption_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-featured-media-caption span'
			]
		);

		$this->add_control(
			'image_caption_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-caption span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_caption_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-caption span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_caption_border_type',
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
					'{{WRAPPER}} .tmpcoder-featured-media-caption span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_caption_border_width',
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
					'{{WRAPPER}} .tmpcoder-featured-media-caption span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'image_caption_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_caption_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-caption span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'image_caption_align_vr',
            [
                'label' => esc_html__( 'Vertical Align', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'flex-end',
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
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-caption' => 'align-items: {{VALUE}}',
				],
				'separator' => 'before',
            ]
        );

		$this->add_control(
            'image_caption_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-featured-media-caption' => 'justify-content: {{VALUE}}',
				],
            ]
        );

		$this->end_controls_section();
	}
	
	// Render Post Thumbnail
	public function render_post_thumbnail( $settings, $id ) {
		$lightbox = '';
        $src = Group_Control_Image_Size::get_attachment_image_src( $id, 'featured_media_image_crop', $settings );
		$caption = wp_get_attachment_caption( $id );
		$settings[ 'featured_media_image_crop' ] = ['id' => $id];
		$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'featured_media_image_crop' );

		$show_caption = $settings['featured_media_caption'];
		
		$class_animation = '';
		$attribute_value = '';

		if ( ! empty( $settings['hover_animation'] ) ) {
			$class_animation = ' elementor-animation-' . $settings['hover_animation'];
		}

        // Render Image
		if ( $src != "" ) {
			echo '<div class="tmpcoder-featured-media-image '.esc_attr($class_animation, 'sastra-essential-addons-for-elementor').'" data-src="'. esc_url( $src ) .'">';
				
				if ( 'yes' === $show_caption && '' !== $caption ) {
					echo '<div class="tmpcoder-featured-media-caption">';
						echo '<span>'. esc_html( $caption ) .'</span>';
					echo '</div>';
				}

				if (isset($settings['image_link_url']) && $settings['image_link_url'] == 'media-file')
				{
					
					echo wp_kses_post('<a href="'.$src.'">'.$image_html.'</a>');
				}
				elseif (isset($settings['image_link_url']) && $settings['image_link_url'] == 'custom-url')
				{

					$link = $settings['image_custom_link'];
					$target = '';
					if (isset($link['is_external']) && $link['is_external'] == 'on') {
						$target = 'target=_blank';
					}

					$nofollow = '';
					if (isset($link['nofollow']) && $link['nofollow'] == 'on') {
						$nofollow = 'ref=nofollow';
					}

					if (isset($link['custom_attributes']) && $link['custom_attributes'] != '') {
								
						$custom_attributes = explode(',', $link['custom_attributes']);

						if (is_array($custom_attributes)) {
							
							foreach ($custom_attributes as $key => $value) {
								
								$attribute = explode('|', $value);
							
								if ($attribute[0] != '' && $attribute[1] != '') {
									
									$attribute_value .= $attribute[0].'="'.$attribute[1].'" ';
								}
							}
						}			
					}

					echo wp_kses_post('<a href="'.esc_url($link['url']).'" '.$attribute_value.' '.esc_attr($nofollow).' '.esc_attr($target).'>'.$image_html.'</a>');					
				}
				else
				{
					echo wp_kses_post($image_html);
				}

			echo '</div>';
		}/*else{
            echo esc_html(__('No Featured Image', 'sastra-essential-addons-for-elementor'));
        }*/
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if (tmpcoder_is_preview_mode()) {
			$thumb_id = get_post_thumbnail_id(tmpcoder_get_last_post_id());
		}
		else
		{
			$thumb_id = get_post_thumbnail_id();
		}

		$post_format = 'standard';
		$post_id = get_the_ID();

		if(null !== tmpcoder_get_settings('tmpcoder_post_video_display_mode') && tmpcoder_get_settings('tmpcoder_post_video_display_mode') == 'video_vimeo_url'){
			$meta_key = 'tmpcoder_vimeo_video_url';	
		}
		else
		{
			$meta_key = 'tmpcoder_custom_video_url';
		}

		// get the meta value of video attachment
		if(!empty($meta_key) && tmpcoder_is_availble() && '' !== tmpcoder_get_settings('tmpcoder_video_settings_options')){

			if (tmpcoder_is_preview_mode()) {
				$post_id = tmpcoder_get_last_post_id();
				$meta_key = 'tmpcoder_featured_video_uploading';
				$meta_ket = get_post_meta($post_id, $meta_key, true);

				$meta_ket = get_post_meta($post_id, $meta_key, true);
				$tmpcoder_currnt_pty = get_post_type($post_id);
				$get_reg_settins = tmpcoder_get_settings('tmpcoder_video_settings_options');

				if(!empty($get_reg_settins) && in_array($tmpcoder_currnt_pty, $get_reg_settins) && tmpcoder_is_availble()){

					$autplyvideo = tmpcoder_get_settings("tmpcoder_post_autoplay_video") ? tmpcoder_get_settings("tmpcoder_post_autoplay_video") : 0;
					$popupvideo = 1;

					/* Vimeo Video */
					if(null !== tmpcoder_get_settings('tmpcoder_post_video_display_mode') && tmpcoder_get_settings('tmpcoder_post_video_display_mode') == 'video_vimeo_url'){	

						if(!empty(get_post_meta($post_id, 'tmpcoder_vimeo_video_url', true))){

							$vimeovideoURL = get_post_meta($post_id, 'tmpcoder_vimeo_video_url', true);
				
							$vimimgid = (int) substr(wp_parse_url($vimeovideoURL, PHP_URL_PATH), 1);
							$responsearry = wp_remote_get("http://vimeo.com/api/v2/video/$vimimgid.php");

							$resp_body = unserialize(wp_remote_retrieve_body($responsearry));

							/*Popup Video*/
							if(!empty($popupvideo) && $popupvideo == 1){
								echo wp_kses(                                    
                                    '<div class="tmpcoder-model-popup pfvthumvido"><button class="pfv-vvideo-playbtton ytp-button" aria-label="Play" onclick="tmpcoder_vimeo_popup_open('.$post_id.','.$autplyvideo.');"><img src="'.esc_url(TMPCODER_PRO_PLUGIN_URI.'assets/images/play-video-icon.png').'" /></button><a href="javascript:void(0);" onclick="tmpcoder_vimeo_popup_open('.$post_id.','.$autplyvideo.');"><img class="vidthumimg" src="'.$resp_body[0]['thumbnail_large'].'"/></a>

									<div id="tmpcoder_video_popup_lightbox_'.$post_id.'" class="pfv_vvideo_lightbox">
									  <a class="boxclose" id="boxclose" onclick="tmpcoder_vimeo_popup_close('.$post_id.');"></a>
									  <div style="padding:56.25% 0 0 0;position:relative;"><iframe id="pfviframeVideo_'.$post_id.'" src="https://player.vimeo.com/video/'.$vimimgid.'?autoplay='.$autplyvideo.'" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div>
									</div>

									<div id="tmpcoder_video_fadelayout_'.$post_id.'" class="pfv_video_fadelayout" onClick="tmpcoder_vimeo_popup_close('.$post_id.');">
									</div>
								</div>'
                                , tmpcoder_wp_kses_allowed_html()
                                );
							}
						}
						else
						{
							echo '<div class="tmpcoder-featured-media-wrap" data-caption="'. esc_attr( $post_format ) .'">';
								$this->render_post_thumbnail( $settings, $thumb_id );
							echo '</div>';
						}
					}
					else
					{
						if(!empty(get_post_meta($post_id, 'tmpcoder_custom_video_url', true))){
							$videoURL = get_post_meta($post_id, 'tmpcoder_custom_video_url', true); 
							// YouTube video url
							if(!empty($videoURL)){

								$urlArr = explode("v=", $videoURL);
								if (isset($urlArr[1])) {
									
									if (strpos($urlArr[1], '&') !== false) {
									    $filterValu = explode("&",$urlArr[1]);
									    $youtubeVideoId = $filterValu[0];
									}
									else{
										$youtubeVideoId = $urlArr[1];
									}
								}
							}
							
							if (isset($youtubeVideoId)) {
								
								// Generate youtube thumbnail url
								$thumbURL = 'http://img.youtube.com/vi/'.$youtubeVideoId.'/maxresdefault.jpg';
								/* Popup Youtube Video */
								if(!empty($popupvideo) && $popupvideo == 1){

									echo '<div class="tmpcoder-model-popup pfvthumvido"><button class="pfv-vvideo-playbtton ytp-button" aria-label="Play" onclick="'.esc_attr('tmpcoder_youtube_lightbox_open('.$post_id.','.$autplyvideo.')').'"><img src="'.esc_url(TMPCODER_PRO_PLUGIN_URI.'assets/images/play-video-icon.png').'" /></button><a href="#" ><img class="vidthumimg" src="'.esc_url($thumbURL).'"/></a>

											<div id="'.esc_attr('tmpcoder_video_popup_lightbox_'.$post_id).'" class="pfv_vvideo_lightbox">
											  <a class="boxclose" id="boxclose" onclick="'.esc_attr('ytube_lightbox_close('.$post_id.')').'"></a>
											  <iframe id="'.esc_attr('pfviframeVideo_'.$post_id).'" src="'.esc_url('https://www.youtube.com/embed/'.$youtubeVideoId).'" allow="autoplay;" style="height:480px;width:100%;"></iframe>				  
											</div>
											<div id="'.esc_attr('tmpcoder_video_fadelayout_'.$post_id).'" class="pfv_video_fadelayout" onClick="'.esc_attr('ytube_lightbox_close('.$post_id.')').'">
											</div>
										</div>';
								}
							}
						}
						else
						{
							echo '<div class="tmpcoder-featured-media-wrap" data-caption="'. esc_attr( $post_format ) .'">';
								$this->render_post_thumbnail( $settings, $thumb_id );
							echo '</div>';
						}
					}
				}
				else
				{
					echo '<div class="tmpcoder-featured-media-wrap" data-caption="'. esc_attr( $post_format ) .'">';
						$this->render_post_thumbnail( $settings, $thumb_id );
					echo '</div>';
				}
			}
			else
			{
				the_post_thumbnail();
			}
		}
		else
		{
			echo '<div class="tmpcoder-featured-media-wrap" data-caption="'. esc_attr( $post_format ) .'">';
				$this->render_post_thumbnail( $settings, $thumb_id );
			echo '</div>';
		}
	}
}