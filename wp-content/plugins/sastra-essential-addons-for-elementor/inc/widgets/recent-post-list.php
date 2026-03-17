<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

class TMPCODER_Post_List extends Widget_Base {

	public function get_name() {
        return 'tmpcoder-recent-post-list';
    }

	public function get_title() {
		return __( 'Recent Post List', 'sastra-essential-addons-for-elementor' );
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
	
	public function get_icon() {
		return 'tmpcoder-icon eicon-editor-list-ul';
	}

	public function get_keywords() {
		return [ 'posts', 'post', 'post-list', 'list', 'news' ];
	}

	/**
	 * Get a list of All Post Types
	 *
	 * @return array
	 */
	public function get_post_types() {
		$post_types = $this->tmpcoder_get_post_types( [], [ 'elementor_library', 'attachment' ] );
		return $post_types;
	}

	/**
	 * Register widget content controls
	 */

	protected function register_controls() {

		$this->start_controls_section(
			'_section_post_list',
			[
				'label' => __( 'List', 'sastra-essential-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'post_type',
			[
				'label'   => __( 'Source', 'sastra-essential-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_post_types(),
				'default' => key( $this->get_post_types() ),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'     => __( 'Item Limit', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3,
				'dynamic'   => [ 'active' => true ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_settings',
			[
				'label' => __( 'Settings', 'sastra-essential-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'view',
			[
				'label'          => __( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'label_block'    => false,
				'type'           => Controls_Manager::CHOOSE,
				'default'        => 'list',
				'options'        => [
					'list'   => [
						'title' => __( 'List', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => __( 'Inline', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					],
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'feature_image',
			[
				'label'        => __( 'Featured Image', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'sastra-essential-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'feature_image_pos',
			[
				'label'                => __( 'Image Position', 'sastra-essential-addons-for-elementor' ),
				'label_block'          => false,
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'left',
				'options'              => [
					'left' => [
						'title' => __( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'top'  => [
						'title' => __( 'Top', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					],
				],
				'style_transfer'       => true,
				'condition'            => [
					'feature_image' => 'yes',
				],
				'selectors_dictionary' => [
					'left' => 'flex-direction: row',
					'top'  => 'flex-direction: column',
				],
				'selectors'            => [
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item a' => '{{VALUE}};',
					'{{WRAPPER}} .tmpcoder-post-list-item a img' => 'margin-right: 0px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'post_image',
				'default'   => 'thumbnail',
				'exclude'   => [
					'custom',
				],
				'condition' => [
					'feature_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'list_icon',
			[
				'label'        => __( 'List Icon', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'sastra-essential-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'feature_image!' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => __( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'default'     => [
					'value'   => 'far fa-check-circle',
					'library' => 'reguler',
				],
				'condition'   => [
					'list_icon'      => 'yes',
					'feature_image!' => 'yes',
				],
			]
		);

		$this->add_control(
			'content',
			[
				'label'        => __( 'Show Content', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'sastra-essential-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'meta',
			[
				'label'        => __( 'Show Meta', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'sastra-essential-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'author_meta',
			[
				'label'        => __( 'Author', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'sastra-essential-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'product_price',
			[
				'label'        => __( 'Price', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'sastra-essential-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'meta' => 'yes',
					'post_type' => 'product',
				],
			]
		);

		$this->add_control(
			'author_icon',
			[
				'label'     => __( 'Author Icon', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'far fa-user',
					'library' => 'reguler',
				],
				'condition' => [
					'meta'        => 'yes',
					'author_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_meta',
			[
				'label'        => __( 'Date', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'sastra-essential-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_icon',
			[
				'label'     => __( 'Date Icon', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'far fa-calendar-check',
					'library' => 'reguler',
				],
				'condition' => [
					'meta'      => 'yes',
					'date_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_meta',
			[
				'label'        => __( 'Category', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'sastra-essential-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'meta'      => 'yes',
					'post_type' => [ 'post', 'product' ],
				],
			]
		);

		$this->add_control(
			'category_icon',
			[
				'label'     => __( 'Category Icon', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'far fa-folder-open',
					'library' => 'reguler',
				],
				'condition' => [
					'meta'          => 'yes',
					'category_meta' => 'yes',
					'post_type' => [ 'post', 'product' ],
				],
			]
		);

		$this->add_control(
			'meta_position',
			[
				'label'     => __( 'Meta Position', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bottom',
				'options'   => [
					'top'    => __( 'Top', 'sastra-essential-addons-for-elementor' ),
					'bottom' => __( 'Bottom', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => __( 'Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'h1' => [
						'title' => __( 'H1', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h1',
					],
					'h2' => [
						'title' => __( 'H2', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h2',
					],
					'h3' => [
						'title' => __( 'H3', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h3',
					],
					'h4' => [
						'title' => __( 'H4', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h4',
					],
					'h5' => [
						'title' => __( 'H5', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h5',
					],
					'h6' => [
						'title' => __( 'H6', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h6',
					],
				],
				'default' => 'h4',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'item_align',
			[
				'label'                => __( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'               => true,
				'selectors_dictionary' => [
					'left'   => 'justify-content: flex-start',
					'center' => 'justify-content: center',
					'right'  => 'justify-content: flex-end',
				],
				'selectors'            => [
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item a' => '{{VALUE}};',
				],
				'condition'            => [
					'view'              => 'list',
					'feature_image_pos' => 'left',
				],
			]
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	
	/**
	 * Register widget style controls
 	*/

	protected function register_style_controls() {
		$this->__post_list_style_controls();
		$this->__title_style_controls();
		$this->__icon_image_style_controls();
		$this->__excerpt_style_controls();
		$this->__meta_style_controls();
	}

	protected function __post_list_style_controls() {

		$this->start_controls_section(
			'_section_post_list_style',
			[
				'label' => __( 'List', 'sastra-essential-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'list_item_common',
			[
				'label' => __( 'Common', 'sastra-essential-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'list_item_margin',
			[
				'label'      => __( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'list_item_padding',
			[
				'label'      => __( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'list_item_background',
				'label'    => __( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item',
			]
		);

		$this->add_control(
			'list_item_hover_background',
			[
				'label'     => __( 'Background Hover Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item:hover' => 'background-color: {{VALUE}}',
				],
			]
		);		

		$this->add_control(
			'element_animation_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item' => 'transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item .tmpcoder-post-list-icon i' => 'transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item .tmpcoder-post-list-title' => 'transition-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'list_item_box_shadow',
				'label'    => __( 'Box Shadow', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'list_item_border',
				'label'    => __( 'Border', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item',
			]
		);

		$this->add_responsive_control(
			'list_item_border_radius',
			[
				'label'      => __( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'advance_style',
			[
				'label'        => __( 'Advance Style', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'sastra-essential-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_responsive_control(
			'list_item_first',
			[
				'label'     => __( 'First Item', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'advance_style' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'list_item_first_child_margin',
			[
				'label'      => __( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item:first-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'advance_style' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'list_item_first_child_border',
				'label'     => __( 'Border', 'sastra-essential-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item:first-child',
				'condition' => [
					'advance_style' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'list_item_last',
			[
				'label'     => __( 'Last Item', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'advance_style' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'list_item_last_child_margin',
			[
				'label'      => __( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item:last-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'advance_style' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'list_item_last_child_border',
				'label'     => __( 'Border', 'sastra-essential-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item:last-child',
				'condition' => [
					'advance_style' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __title_style_controls() {

		$this->start_controls_section(
			'_section_post_list_title_style',
			[
				'label' => __( 'Title', 'sastra-essential-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-post-list-title',
			]
		);

		$this->start_controls_tabs( 'title_tabs' );
		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_hover_tab',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'title_hvr_color',
			[
				'label'     => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item:hover .tmpcoder-post-list-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item:hover .tmpcoder-post-list-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-post-list .tmpcoder-post-list-item:hover .tmpcoder-post-list-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function __icon_image_style_controls() {

		$this->start_controls_section(
			'_section_list_icon_feature_iamge_style',
			[
				'label'      => __( 'Icon & Feature Image', 'sastra-essential-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'feature_image',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'name'     => 'list_icon',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} span.tmpcoder-post-list-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'feature_image!' => 'yes',
					'list_icon'      => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'     => __( 'Font Size', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} span.tmpcoder-post-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image!' => 'yes',
					'list_icon'      => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_line_height',
			[
				'label'     => __( 'Line Height', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} span.tmpcoder-post-list-icon' => 'line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image!' => 'yes',
					'list_icon'      => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'     => __( 'Image Width', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list-item a img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'     => __( 'Image Height', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list-item a img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_boder',
				'label'     => __( 'Border', 'sastra-essential-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .tmpcoder-post-list-item a img',
				'condition' => [
					'feature_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_boder_radius',
			[
				'label'      => __( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tmpcoder-post-list-item a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'feature_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin_right',
			[
				'label'     => __( 'Margin Right', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
					'size' => '15',
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} span.tmpcoder-post-list-icon'   => 'margin-right: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} .tmpcoder-post-list-item a img' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} span.tmpcoder-post-list-icon'   => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .tmpcoder-post-list-item a img' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image_pos' => 'left',
				],
			]
		);

		$this->add_responsive_control(
			'feature_margin_bottom',
			[
				'label'     => __( 'Margin Bottom', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
					'size' => '15',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list-item a img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image_pos' => 'top',
				],
			]
		);

		$this->add_control(
	        'hover_animation',
	        [
	            'label' => esc_html__( 'Image Hover Animation', 'sastra-essential-addons-for-elementor' ),
	            'type' => Controls_Manager::HOVER_ANIMATION,
	        ]
	    );

		$this->end_controls_section();
	}

	protected function __excerpt_style_controls() {

		$this->start_controls_section(
			'_section_list_excerpt_style',
			[
				'label'     => __( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'label'    => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-post-list-excerpt p',
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list-excerpt p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_space',
			[
				'label'     => __( 'Space Top', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list-excerpt' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __meta_style_controls() {

		$this->start_controls_section(
			'_section_list_meta_style',
			[
				'label'     => __( 'Meta', 'sastra-essential-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_typography',
				'label'    => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-post-list-meta-wrap span',
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list-meta-wrap span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_space',
			[
				'label'     => __( 'Space Between', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list-meta-wrap span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-post-list-meta-wrap span:last-child' => 'margin-right: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'meta_box_margin',
			[
				'label'      => __( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tmpcoder-post-list-meta-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_icon_heading',
			[
				'label'     => __( 'Meta Icon', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_icon_color',
			[
				'label'     => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list-meta-wrap span i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_icon_space',
			[
				'label'     => __( 'Space Between', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-post-list-meta-wrap span i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if ( ! $settings['post_type'] ) {
			return;
		}

		$settings['show_post_by'] = 'recent';

		$args = [
			'post_status'      => 'publish',
			'post_type'        => $settings['post_type'],
			'suppress_filters' => false,
		];

		if ( 'recent' === $settings['show_post_by'] ) {
			$args['posts_per_page'] = $settings['posts_per_page'];
		}

		$customize_title = [];
		$ids             = [];

		if ( 'selected' === $settings['show_post_by'] ) {
			$args['posts_per_page'] = -1;
			$lists                  = $settings[ 'selected_list_' . $settings['post_type'] ];

			if ( ! empty( $lists ) ) {
				foreach ( $lists as $index => $value ) {
					//trim function to remove extra space before post ID
					if ( is_array( $value['post_id'] ) ) {
						$post_id = ! empty( $value['post_id'][0] ) ? trim( $value['post_id'][0] ) : '';
					} else {
						$post_id = ! empty( $value['post_id'] ) ? trim( $value['post_id'] ) : '';
					}
					$ids[] = $post_id;
					if ( $value['title'] ) {
						$customize_title[ $post_id ] = $value['title'];
					}
				}
			}

			$args['post__in'] = (array) $ids;
			$args['orderby']  = 'post__in';
		}

		if ( 'selected' === $settings['show_post_by'] && empty( $ids ) ) {
			$posts = [];
		} else {
			$posts = get_posts( $args );
		}

		$this->add_render_attribute( 'wrapper', 'class', [ 'tmpcoder-post-list-wrapper' ] );
		$this->add_render_attribute( 'wrapper-inner', 'class', [ 'tmpcoder-post-list' ] );
		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'wrapper-inner', 'class', [ 'tmpcoder-post-list-inline' ] );
		}
		$this->add_render_attribute( 'item', 'class', [ 'tmpcoder-post-list-item' ] );

		$hover_animation = !empty($settings['hover_animation']) && $settings['hover_animation']!=''?'elementor-animation-'.$settings['hover_animation']:'';

		if ( count( $posts ) !== 0 ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<ul <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?> >
					<?php foreach ( $posts as $post ) : ?>
						<li <?php $this->print_render_attribute_string( 'item' ); ?>>
							<a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>">
								<?php
								if ( 'yes' === $settings['feature_image'] ) :
									echo get_the_post_thumbnail( $post->ID, $settings['post_image_size'], ['class' => $hover_animation ] );
								elseif ( 'yes' === $settings['list_icon'] && $settings['icon'] ) :
									echo '<span class="tmpcoder-post-list-icon">';
										if (is_array($settings['icon']['value'])) {
											echo wp_kses(tmpcoder_render_svg_icon($settings['icon']),tmpcoder_wp_kses_allowed_html());
										}
										else
										{
										 	if ( !empty($settings['icon']['value']) && '' !== $settings['icon']['value'] ) : 
										 		echo '<i class="'. esc_attr($settings['icon']['value']) .'"></i>';
											endif;
										}
									echo '</span>';
								endif;
								?>
								<div class="tmpcoder-post-list-content">
									<?php
									$title = $post->post_title;
									if ( 'selected' === $settings['show_post_by'] && array_key_exists( $post->ID, $customize_title ) ) {
										$title = $customize_title[ $post->ID ];
									}
									if ( 'top' !== $settings['meta_position'] && $title ) {
										echo wp_kses_post(sprintf(
											'<%1$s %2$s>%3$s</%1$s>',
											tmpcoder_validate_html_tag( $settings['title_tag'] ),
											'class="tmpcoder-post-list-title"',
											esc_html( $title )
										));
									}
									?>
									<?php if ( 'yes' === $settings['meta'] ) : ?>
										<div class="tmpcoder-post-list-meta-wrap">


											<?php

											if ( 'yes' === $settings['product_price'] ) :
												?>
												<span class="tmpcoder-post-list-price">
												<?php

												$product = wc_get_product( $post->ID );
												echo wp_kses_post(get_woocommerce_currency_symbol().$product->get_price());
												?>
												</span>
											<?php endif; ?>

											<?php
											if ( 'yes' === $settings['author_meta'] ) :
												?>
												<span class="tmpcoder-post-list-author">
												<?php
												if ( $settings['author_icon'] ) :
													Icons_Manager::render_icon( $settings['author_icon'], [ 'aria-hidden' => 'true' ] );
												endif;
												echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) );
												?>
												</span>
											<?php endif; ?>

											<?php if ( 'yes' === $settings['date_meta'] ) : ?>
												<span class="tmpcoder-post-list-date">
													<?php
													if ( $settings['date_icon'] ) :
														Icons_Manager::render_icon( $settings['date_icon'], [ 'aria-hidden' => 'true' ] );
													endif;
													echo get_the_date( get_option( 'date_format' ), $post->ID );
													?>
												</span>
											<?php endif; ?>

											<?php
											if ( ( 'post' === $settings['post_type'] || 'product' === $settings['post_type'] ) && 'yes' === $settings['category_meta'] ) :
												$taxonomy = 'category';
												if ( 'product' === $settings['post_type'] ) {
													$taxonomy = 'product_cat';
												}
												$categories = get_the_terms( $post->ID, $taxonomy );
												if ( ! $categories || is_wp_error( $categories ) ) {
													$categories = array();
												}
												?>
												<span class="tmpcoder-post-list-category">
												<?php
												if ( $settings['category_icon'] ) :
													Icons_Manager::render_icon( $settings['category_icon'], [ 'aria-hidden' => 'true' ] );
												endif;
												echo ( ! empty( $categories ) ) ? esc_html( $categories[0]->name ) : '';
												?>
												</span>
											<?php endif; ?>

										</div>
									<?php endif; ?>
									<?php
									if ( 'top' === $settings['meta_position'] && $title ) {
										echo wp_kses_post(sprintf(
											'<%1$s %2$s>%3$s</%1$s>',
											tmpcoder_validate_html_tag( $settings['title_tag'] ),
											'class="tmpcoder-post-list-title"',
											esc_html( $title )
										));
									}
									?>
									<?php if ( 'yes' === $settings['content'] ) : ?>
										<div class="tmpcoder-post-list-excerpt">
											<?php
											if ( has_excerpt( $post->ID ) ) {
												echo wp_kses_post(sprintf(
													'<p>%1$s</p>',
													wp_trim_words( get_the_excerpt( $post->ID ) )
												));
											} else {
												echo wp_kses_post(sprintf(
													'<p>%1$s</p>',
													wp_trim_words( get_the_content( null, false, $post->ID ), 25, '.' )
												));
											}
											?>
										</div>
									<?php endif; ?>
								</div>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php
		else :
			esc_html(sprintf(
				'%1$s %2$s %3$s',
				__( 'No ', 'sastra-essential-addons-for-elementor' ),
				esc_html( $settings['post_type'] ),
				__( 'Found', 'sastra-essential-addons-for-elementor' )
			));
		endif;
	}

	/**
	 * Get All Post Types
	 * @param array $args
	 * @param array $diff_key
	 * @return array|string[]|WP_Post_Type[]
	 */
	function tmpcoder_get_post_types($args = [], $diff_key = []) {
		$default = [
			'public'            => true,
			'show_in_nav_menus' => true,
		];
		$args       = array_merge($default, $args);
		$post_types = get_post_types($args, 'objects');
		$post_types = wp_list_pluck($post_types, 'label', 'name');

		if (!empty($diff_key)) {
			$post_types = array_diff_key($post_types, $diff_key);
		}
		return $post_types;
	}

	/**
	 * Escaped title html tags
	 *
	 * @param string $tag input string of title tag
	 * @return string $default default tag will be return during no matches
	 */

	function tmpcoder_escape_tags($tag, $default = 'span', $extra = []) {

		$supports = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];

		$supports = array_merge($supports, $extra);

		if (!in_array($tag, $supports, true)) {
			return $default;
		}

		return $tag;
	}


}
