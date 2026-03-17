<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Taxonomy_List extends Widget_Base {
	
	public function get_name() {
		return 'tmpcoder-taxonomy-list';
	}

	public function get_title() {
		return esc_html__( 'Taxonomy List', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-editor-list-ul';
	}

	public function get_categories() {

		if (tmpcoder_show_theme_buider_widget_on('type_single_post') || tmpcoder_show_theme_buider_widget_on('type_archive')) {
			return [ 'tmpcoder-theme-builder-widgets'];
		}
		elseif (tmpcoder_show_theme_buider_widget_on('type_single_product') || tmpcoder_show_theme_buider_widget_on('type_product_category') || tmpcoder_show_theme_buider_widget_on('type_product_archive')) {
			return [ 'tmpcoder-woocommerce-builder-widgets'];
		}else{
			return [ 'tmpcoder-widgets-category' ];
		}
	}

	public function get_script_depends() {
		return [ 'tmpcoder-taxonomy-list' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-taxonomy-list' ];
	}

	public function get_keywords() {
		return [ 'taxonomy-list', 'taxonomy', 'category', 'categories', 'tag', 'list'];
	}

	public function add_section_style_toggle_icon() {}

	public function get_post_taxonomies() {
		$post_taxonomies = [];
		$post_taxonomies['category'] = esc_html__( 'Categories', 'sastra-essential-addons-for-elementor' );
		$post_taxonomies['post_tag'] = esc_html__( 'Tags', 'sastra-essential-addons-for-elementor' );

		$custom_post_taxonomies = tmpcoder_get_custom_types_of( 'tax', true );
		
		foreach( $custom_post_taxonomies as $slug => $title ) {
			if ( 'product_tag' === $slug || 'product_cat' === $slug ) {
				continue;
			}

			$post_taxonomies['pro-'. substr($slug, 0, 2)] = esc_html( $title ) .' (Pro)';
		}

		if (class_exists( 'WooCommerce' ) ) {

			$post_taxonomies['product_cat'] = esc_html__( 'Product Categories', 'sastra-essential-addons-for-elementor' );
			$post_taxonomies['product_tag'] = esc_html__( 'Product Tags', 'sastra-essential-addons-for-elementor' );
		}

		return $post_taxonomies;
	}

	public function add_controls_group_sub_category_filters() {
		$this->add_control(
			'show_sub_categories',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Sub Categories %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control',
			]
		);

		$this->add_control(
			'show_sub_children',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Sub Children %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control',
			]
		);

		$this->add_control(
			'show_sub_categories_on_click',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Children on Click %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control',
			]
		);
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
			'query_tax_selection',
			[
				'label' => esc_html__( 'Select Taxonomy', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $this->get_post_taxonomies(),
			]
		);

		$this->add_control(
			'query_hide_empty',
			[
				'label' => esc_html__( 'Hide Empty', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes'
			]
		);

		$this->add_controls_group_sub_category_filters();

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
			'show_tax_count',
			[
				'label' => esc_html__( 'Show Count', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'disable_links',
			[
				'label' => esc_html__( 'Disable Links', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => '',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'open_in_new_page',
			[
				'label' => esc_html__( 'Open in New Page', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => 'yes',
				// 'separator' => 'before',
				'condition' => [
					'disable_links!' => 'yes'
				]
			]
		);

		$this->add_control(
			'tmpcoder_enable_title_attribute',
			[
				'label' => esc_html__( 'Enable Title Attribute', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'render_type' => 'template',
				'condition' => [
					'disable_links!' => 'yes'
				]
			]
		);

        $this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'taxonomy-list', [
			'Query Custom Post Type Taxonomies (categories).',
			'Show/Hide Sub Taxonomies',
			'Show Sub Taxonomies On Click',
		] );

		// Styles ====================
		// Section: Taxonomy Style ---
		$this->start_controls_section(
			'section_style_tax',
			[
				'label' => esc_html__( 'Taxonomy Style', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span' => 'color: {{VALUE}}'
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span' => 'background-color: {{VALUE}}'
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span' => 'border-color: {{VALUE}}'
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span' => 'transition-duration: {{VALUE}}s'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tax_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-taxonomy-list li a, {{WRAPPER}} .tmpcoder-taxonomy-list li>span',
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'tax1_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'tax1_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span:hover' => 'border-color: {{VALUE}}'
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span' => 'border-style: {{VALUE}};'
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-taxonomy-list li>span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li span svg' => 'fill: {{VALUE}}',
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .tmpcoder-taxonomy-list li i:not(.tmpcoder-tax-dropdown)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-taxonomy-list li svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		$this->add_section_style_toggle_icon();
    }

	public function get_tax_wrapper_open_tag( $settings, $term_id, $open_in_new_page, $term_name='' ) {
		if ( 'yes' == $settings['disable_links'] ) {
			echo '<span>';
		} else {

			if ($settings['tmpcoder_enable_title_attribute'] != 'yes') {
				$term_name = '';
			}

			echo '<a title="'.esc_attr($term_name).'" target="'. esc_attr($open_in_new_page) .'" href="'. esc_url(get_term_link($term_id)) .'">';
		}
	}

	public function get_tax_wrapper_close_tag( $settings ) {
		if ( 'yes' == $settings['disable_links'] ) {
			echo '</span>';
		} else {
			echo '</a>';
		}
	}

    protected function render() {
		// Get Settings
        $settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		$open_in_new_page = $settings['open_in_new_page'] ? '_blank' : '_self';

		ob_start();
		\Elementor\Icons_Manager::render_icon( $settings['tax_list_icon'], [ 'aria-hidden' => 'true' ] );
		$icon = ob_get_clean();
		$icon_wrapper = !empty($settings['tax_list_icon']) ? '<span>'. $icon .'</span>' : '';

		// 	'hide_empty' => 'yes' === $settings['query_hide_empty']
		$settings['query_tax_selection'] = str_contains($settings['query_tax_selection'], 'pro-') ? 'category' : $settings['query_tax_selection'];
		
         echo '<ul class="tmpcoder-taxonomy-list" data-show-on-click="'. esc_attr($settings['show_sub_categories_on_click']) .'">';
		$terms = get_terms( [ 'taxonomy' => $settings['query_tax_selection'], 'hide_empty' => 'yes' === $settings['query_hide_empty'], 'parent' => 0, 'child_of' => 0 ] );

        foreach ($terms as $key => $term) {
        	$cat_class = ' class="tmpcoder-taxonomy"';
			$data_parent_term_id = $term->term_id;

			if ( 'yes' === $settings['show_sub_categories'] ) {
				$children = get_terms(  [ 'taxonomy' => $settings['query_tax_selection'], 'hide_empty' => 'yes' === $settings['query_hide_empty'], 'parent' => $term->term_id ] );
			} else {
				$children = [];
			}
        	
            echo wp_kses_post('<li '. $cat_class . ' data-term-id="'.esc_attr($data_parent_term_id) .'">');
				$toggle_icon = !empty($children) && ('vertical' === $settings['taxonomy_list_layout']) && ('yes' === $settings['show_sub_categories_on_click']) ? '<i class="fas fa-caret-right tmpcoder-tax-dropdown" aria-hidden="true"></i>' : '';
				$this->get_tax_wrapper_open_tag( $settings, $term->term_id, $open_in_new_page, $term->name );
					echo wp_kses('<span class="tmpcoder-tax-wrap">'. $toggle_icon . ' ' . $icon_wrapper .'<span>'. esc_html($term->name) .'</span></span>', tmpcoder_wp_kses_allowed_html());
		            echo ($settings['show_tax_count']) ? '<span><span class="tmpcoder-term-count">&nbsp;('. esc_html($term->count) .')</span></span>' : '';
				$this->get_tax_wrapper_close_tag( $settings );
            echo '</li>';

			foreach ($children as $term) :
				$hidden_class = $settings['show_sub_categories_on_click'] == 'yes' ? ' tmpcoder-sub-hidden' : '';
				$sub_class = $term->parent > 0 ? ' class="tmpcoder-sub-taxonomy' . $hidden_class . '"' : '';
				$data_child_term_id = $data_parent_term_id;
				$data_item_id = $term->term_id;

				if ( 'yes' === $settings['show_sub_categories'] && 'yes' === $settings['show_sub_children'] ) {
					$grand_children = get_terms( [ 'taxonomy' => $settings['query_tax_selection'], 'hide_empty' => 'yes' === $settings['query_hide_empty'], 'parent' => $term->term_id ] );
				} else {
					$grand_children = [];
				}
				
				echo wp_kses_post('<li '. $sub_class . ' data-term-id="child-'. esc_attr($data_child_term_id) .'" data-id="'. esc_attr($data_item_id) .'">');
				$toggle_icon = !empty($grand_children) && ('vertical' === $settings['taxonomy_list_layout']) && ('yes' === $settings['show_sub_categories_on_click']) ? '<i class="fas fa-caret-right tmpcoder-tax-dropdown" aria-hidden="true"></i>' : '';
					$this->get_tax_wrapper_open_tag( $settings, $term->term_id, $open_in_new_page, $term->name );
						echo wp_kses('<span class="tmpcoder-tax-wrap">'. $toggle_icon . ' ' . $icon_wrapper .'<span>'. esc_html($term->name) .'</span></span>', array(
                            'span' => array(
                                'class' => ''
                            ),
                            'i' => array(
                                'class' => ''
                            ),
                        ));
						echo ($settings['show_tax_count']) ? '<span><span class="tmpcoder-term-count">&nbsp;('. esc_html($term->count) .')</span></span>' : '';
					$this->get_tax_wrapper_close_tag( $settings );
				echo '</li>';
	
				foreach ($grand_children as $term) :
					$hidden_class = $settings['show_sub_categories_on_click'] == 'yes' ? ' tmpcoder-sub-hidden' : '';
					$sub_class = $term->parent > 0 ? ' class="tmpcoder-inner-sub-taxonomy' . $hidden_class . '" ' : '';
					$data_grandchild_term_id = ' data-parent-id="'. $data_item_id .'" data-term-id="grandchild-'. $data_child_term_id .'"';
					$grandchild_id = $term->term_id;

					if ( 'yes' === $settings['show_sub_categories'] && 'yes' === $settings['show_sub_children'] ) {
						$great_grand_children = get_terms( [ 'taxonomy' => $settings['query_tax_selection'], 'hide_empty' => 'yes' === $settings['query_hide_empty'], 'parent' => $term->term_id ] );
					} else {
						$great_grand_children = [];
					}
					
					echo wp_kses_post('<li '. $sub_class .' '. $data_grandchild_term_id .' data-id="'. esc_attr($grandchild_id) .'" >');
						$this->get_tax_wrapper_open_tag( $settings, $term->term_id, $open_in_new_page, $term->name );
							echo wp_kses('<span class="tmpcoder-tax-wrap">'. $toggle_icon . ' ' . $icon_wrapper .'<span>'. esc_html($term->name) .'</span></span>', array(
                                'span' => array(
                                    'class' => ''
                                ),
                                'i' => array(
                                    'class' => ''
                                ),
                            ));
							echo ($settings['show_tax_count']) ? '<span><span class="tmpcoder-term-count">&nbsp;('. esc_html($term->count) .')</span></span>' : '';
						$this->get_tax_wrapper_close_tag( $settings );
					echo '</li>';

					foreach($great_grand_children as $term) :
						$sub_class = $term->parent > 0 ? ' class="tmpcoder-inner-sub-taxonomy-2' . $hidden_class . '"' : '';
						$data_great_grandchild_term_id = ' data-parent-id="'. $grandchild_id .'" data-term-id="great-grandchild-'. $data_child_term_id .'"';
					
						echo wp_kses_post('<li '. $sub_class .' '. $data_great_grandchild_term_id .' >');
							$this->get_tax_wrapper_open_tag( $settings, $term->term_id, $open_in_new_page, $term->name );
								echo wp_kses('<span class="tmpcoder-tax-wrap">'. $icon_wrapper .'<span>'. esc_html($term->name) .'</span></span>', array(
                                    'span' => array(
                                        'class' => array(),
                                    )
                                ));
								echo wp_kses( ($settings['show_tax_count'] ? '<span><span class="tmpcoder-term-count">&nbsp;('. esc_html($term->count) .')</span></span>' : ''), array(
                                    'span' => array(
                                        'class' => array(),
                                    )
                                ));
							$this->get_tax_wrapper_close_tag( $settings );
						echo '</li>';
					
					endforeach;
					
	
				endforeach;
				

			endforeach;
        }

     	echo '</ul>';
    }
}