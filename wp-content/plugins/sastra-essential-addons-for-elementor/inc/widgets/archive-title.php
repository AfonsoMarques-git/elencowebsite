<?php
namespace TMPCODER\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Archive_Title extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-archive-title';
	}

	public function get_title() {
		return esc_html__( 'Archive Title/Desc', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-site-title';
	}

	public function get_categories() {

		if (tmpcoder_show_theme_buider_widget_on('type_archive')) {
			return [ 'tmpcoder-theme-builder-widgets' ];

		}elseif (tmpcoder_show_theme_buider_widget_on('type_product_archive') || tmpcoder_show_theme_buider_widget_on('type_product_category')) {
			return [ 'tmpcoder-woocommerce-builder-widgets'];
		}else{
			return [];
		}
	}

	public function get_keywords() {
		return [ 'spexo', 'archive', 'title', 'description', 'category', 'tag' ];
	}

	public function add_control_archive_description() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_title',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
            'post_title_align',
            [
                'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                ],
				'selectors_dictionary' => [
					'left' => 'text-align: left;',
					'center' => 'text-align: center; margin: 0 auto;',
					'right' => 'text-align: right; margin-left: auto;',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-archive-title' => '{{VALUE}}',
					'{{WRAPPER}} .tmpcoder-archive-title:after' => '{{VALUE}}',
					'{{WRAPPER}} .tmpcoder-archive-description' => '{{VALUE}}',
				],
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
					'P' => 'p'
				],
				'default' => 'h1',
			]
		);

		$this->add_control(
			'post_title_before_text',
			[
				'label' => esc_html__( 'Text Before Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
			]
		);

		$this->add_control(
			'tmpcoder_include_context',
			[
				'label' => esc_html__( 'Include Context', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control_archive_description();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'archive-title', [
			'Show/Hide Taxonomy (Category) Description, also change Color and Typography.',
		] );

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title & Description', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-archive-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_before_text_color',
			[
				'label'  => esc_html__( 'Before Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-archive-title span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '24',
							'unit' => 'px',
						],
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-archive-title'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .tmpcoder-archive-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-archive-title',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-archive-title' => 'mix-blend-mode: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_desc_heading',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'archive_description' => 'yes',
				],
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-archive-description' => 'color: {{VALUE}}',
				],
				'condition' => [
					'archive_description' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						],
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-archive-description',
				'condition' => [
					'archive_description' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_divider_heading',
			[
				'label' => esc_html__( 'Divider', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_divider_show',
			[
				'label' => esc_html__( 'Show Divider', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_responsive_control(
			'title_divider_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-archive-title:after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'title_divider_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_divider_height',
			[
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-archive-title:after' => 'height: {{SIZE}}px;',
				],
				'condition' => [
					'title_divider_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_divider_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-archive-title:after' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_divider_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_divider_distance_top',
			[
				'label' => esc_html__( 'Top Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 7,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-archive-title:after' => 'margin-top: {{SIZE}}px;',
				],
				'condition' => [
					'title_divider_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_divider_distance_bot',
			[
				'label' => esc_html__( 'Bottom Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-archive-title:after' => 'margin-bottom: {{SIZE}}px;',
				],
				'condition' => [
					'title_divider_show' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$tax = get_queried_object();
		
		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
		$post_title_tag = tmpcoder_validate_html_tags_wl( isset($settings['post_title_tag
			']) ? $settings['post_title_tag
			']:'', 'h1', $tags_whitelist );

		if ( !is_null($tax) || get_the_archive_title() ) {
			$title = isset($tax->post_title) ? $tax->post_title : $tax->name;
			$description = isset($tax->description) ? $tax->description : '';

			if (is_author()) {
				$title = get_the_author();
			}
			if (isset($settings['tmpcoder_include_context']) && $settings['tmpcoder_include_context'] == 'yes') {
				$title = get_the_archive_title();
				$title = preg_replace('/<span.*?>(.*?)<\/span>/', '$1', $title);
			}

			if ( function_exists('is_shop') && is_shop() ) {
				$title = $tax->label;
			}

			if (tmpcoder_is_elementor_editor_mode()) {
				$title = 'Archives';				
				$description = 'Lorem ipsum is a dummy or placeholder text commonly used in graphic design, publishing, and web development.';				
			}

			if ( '' !== $title ) {
				echo '<'. esc_attr($post_title_tag) .' class="tmpcoder-archive-title">';
					echo '<span>'. wp_kses_post($settings['post_title_before_text']) .'</span>'. esc_html($title);
				echo '</'. esc_attr($post_title_tag) .'>';
			}

			if ( tmpcoder_is_availble() ) {
				if ( isset($settings['archive_description']) && (!empty($description) && '' !== $description) && (!empty($settings['archive_description']) && '' !== $settings['archive_description']) ) {
					echo '<p class="tmpcoder-archive-description">'. wp_kses_post($description) .'</p>';
				}
			}
		} elseif ( is_search() ) {
			echo '<'. esc_attr($post_title_tag) .' class="tmpcoder-archive-title">';
				echo '<span>'. esc_html($settings['post_title_before_text']) .'</span>'. esc_html(get_search_query());
			echo '</'. esc_attr($post_title_tag) .'>';	
		} elseif (is_author()){
			echo '<'. esc_attr($post_title_tag) .' class="tmpcoder-archive-title">';
				echo '<span>'. esc_html($settings['post_title_before_text']) .'</span>'. esc_html(get_the_author());
			echo '</'. esc_attr($post_title_tag) .'>';	
		}
	}
	
}