<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Archive_List extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-archive-list';
	}

	public function get_title() {
		return esc_html__( 'Archive List', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-bullet-list';
	}

	public function get_categories() {

		if ( tmpcoder_show_theme_buider_widget_on('type_single_post') || tmpcoder_show_theme_buider_widget_on('type_archive') ) {
			return [ 'tmpcoder-theme-builder-widgets'];
		}
		elseif (tmpcoder_show_theme_buider_widget_on('type_single_product') || tmpcoder_show_theme_buider_widget_on('type_product_category') || tmpcoder_show_theme_buider_widget_on('type_product_archive')) {
			return [ 'tmpcoder-woocommerce-builder-widgets'];
		}else{
			return [ 'tmpcoder-widgets-category' ];
		}
	}

	public function get_keywords() {
		return [ 'spexo', 'year', 'date', 'archive', 'list'];
	}

	public function get_custom_help_url() {
    	return TMPCODER_NEED_HELP_URL;
    }
	
	public function get_post_taxonomies() {
		$post_taxonomies = [];
		$post_taxonomies['daily'] = esc_html__( 'Daily', 'sastra-essential-addons-for-elementor' );
		$post_taxonomies['weekly'] = esc_html__( 'Weekly', 'sastra-essential-addons-for-elementor' );
		$post_taxonomies['monthly'] = esc_html__( 'Monthly', 'sastra-essential-addons-for-elementor' );
		$post_taxonomies['yearly'] = esc_html__( 'Yearly', 'sastra-essential-addons-for-elementor' );
		$post_taxonomies['postbypost'] = esc_html__( 'Post By Post', 'sastra-essential-addons-for-elementor' );

		return $post_taxonomies;
	}

    protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_taxonomy_list_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'query_heading',
			[
				'label' => esc_html__( 'Query', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'select_archive_type',
			[
				'label' => esc_html__( 'Select Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'monthly',
				'options' => $this->get_post_taxonomies(),
			]
		);

		$this->add_control(
			'item_limit',
			[
				'label'     => __( 'Item Limit', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3,
			]
		);

		$this->add_control(
			'item_order',
			[
				'label' => esc_html__( 'Select Order', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => ['ASC' => 'ASC', 'DESC' => 'DESC'],
				'default' => 'DESC',
			]
		);

		$this->add_control(
			'layout_heading',
			[
				'label' => esc_html__( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'taxonomy_list_layout',
			[
				'label' => esc_html__( 'Select Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'render_type' => 'template',
				'options' => [
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'horizontal' => [
						'title' => esc_html__( 'Horizontal', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
                'prefix_class' => 'tmpcoder-taxonomy-list-',
				'label_block' => false,
			]
		);

		$this->add_control(
			'show_tax_list_icon',
			[
				'label' => esc_html__( 'Show Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'tax_list_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'exclude_inline_options' => 'svg',
				'condition' => [
					'show_tax_list_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_archive_count',
			[
				'label' => esc_html__( 'Show Count', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => 'yes'
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Archive Style ---
		$this->start_controls_section(
			'section_style_tax',
			[
				'label' => esc_html__( 'Archive Style', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tax_style' );

		$this->start_controls_tab(
			'tax_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'tax_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li, {{WRAPPER}} .tmpcoder-taxonomy-list li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#00000000',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tax_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-taxonomy-list li',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tax_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'tax_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li:hover, {{WRAPPER}} .tmpcoder-taxonomy-list li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax1_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tax_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tax_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 8,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tax_border_type',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 1,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tax_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tax_radius',
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Icon --------
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_tax_list_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li svg' => 'height:{{SIZE}}{{UNIT}};width:{{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_distance',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li span' => 'margin-right: {{SIZE}}{{UNIT}};',
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

        $settings['show_sub_categories_on_click'] = '';

		ob_start();
		\Elementor\Icons_Manager::render_icon( $settings['tax_list_icon'], [ 'aria-hidden' => 'true' ] );
		$icon = ob_get_clean();
		$icon_wrapper = !empty($settings['tax_list_icon']) ? '<span>'. $icon .'</span>' : '';

     	echo '<ul class="tmpcoder-taxonomy-list">';

			$show_archive_count = false;
			$show_archive_count = $settings['show_archive_count'] == 'yes' ? true : false;

			$archive_args = [];
			$archive_args['type'] = $settings['select_archive_type'];
			$archive_args['show_post_count'] = $show_archive_count;
			$archive_args['limit'] = $settings['item_limit'];
			$archive_args['order'] = $settings['item_order'];

			if ($icon_wrapper) {
				$archive_args['before'] = $icon_wrapper;
			}

			$terms = wp_get_archives($archive_args);	
			
     	echo '</ul>';
    }
}