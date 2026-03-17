<?php
namespace TMPCODER\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Content_Ticker extends Widget_Base {

	public function get_name() {
		return 'tmpcoder-content-ticker';
	}

	public function get_title() {
		return esc_html__( 'Content Ticker', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-carousel';
	}

	public function get_categories() {

		if (tmpcoder_show_theme_buider_widget_on('type_header')) {
			return [ 'tmpcoder-header-builder-widgets'];
		}
		elseif (tmpcoder_show_theme_buider_widget_on('type_archive')) {
			return [ 'tmpcoder-theme-builder-widgets' ];
		}
		else
		{
			return [ 'tmpcoder-widgets-category' ];
		}
	}

	public function get_keywords() {
		return [ 'blog', 'content ticker', 'news ticker', 'post ticker', 'posts ticker' ];
	}

	public function get_script_depends() {
		$depends = [ 'tmpcoder-slick' => true, 'tmpcoder-marquee' => true, 'tmpcoder-content-ticker' => true ];

		if ( ! tmpcoder_elementor()->preview->is_preview_mode() ) {
			$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

			if ( $settings['type_select'] != 'slider' ) {
				unset( $depends['tmpcoder-slick'] );
			}if ( $settings['type_select'] != 'marquee' ) {
				unset( $depends['tmpcoder-marquee'] );
			}
		}

		return array_keys($depends);
	}

	public function get_style_depends() {
		return [ 'tmpcoder-content-ticker' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_post_type() {
		$this->add_control(
			'post_type',
			[
				'label' => esc_html__( 'Select Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'sastra-essential-addons-for-elementor' ),
					'pro-cm' => esc_html__( 'Custom (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);
	}

	public function add_control_slider_effect() {
		$this->add_control(
			'slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'sastra-essential-addons-for-elementor' ),
				'default' => 'hr-slide',
				'options' => [
					'hr-slide' => esc_html__( 'Horizontal Slide', 'sastra-essential-addons-for-elementor' ),
					'pro-tp' => esc_html__( 'Typing (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-fd' => esc_html__( 'Fade (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-vs' => esc_html__( 'Vertical Slide (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-ticker-effect-',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);
	}

	public function add_control_slider_effect_cursor() {}

	public function add_control_heading_icon_type() {
		$this->add_control(
			'heading_icon_type',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Select Type', 'sastra-essential-addons-for-elementor' ),
				'default' => 'fontawesome',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'fontawesome' => esc_html__( 'FontAwesome', 'sastra-essential-addons-for-elementor' ),
					'pro-cc' => esc_html__( 'Circle (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);
	}

	public function add_control_type_select() {
		$this->add_control(
			'type_select',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Select Type', 'sastra-essential-addons-for-elementor' ),
				'default' => 'slider',
				'options' => [
					'slider' => esc_html__( 'Slider', 'sastra-essential-addons-for-elementor' ),
					'pro-mq' => esc_html__( 'Marquee (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);
	}

	public function add_control_marquee_direction() {}

	public function add_control_marquee_pause_on_hover() {}

	public function add_control_marquee_effect_duration() {}

	public function add_section_ticker_items() {}

	// private array $post_types = [];
	public $post_types;

	public function add_control_query_source() {

		// Get Available Post Types
		$this->post_types = [];
		$this->post_types['post'] = esc_html__( 'Posts', 'sastra-essential-addons-for-elementor' );
		$this->post_types['page'] = esc_html__( 'Pages', 'sastra-essential-addons-for-elementor' );

		$custom_post_types = tmpcoder_get_custom_types_of( 'post', true );
		foreach( $custom_post_types as $slug => $title ) {
			if ( 'product' === $slug || 'e-landing-page' === $slug ) {
				continue;
			}

			if ( ! tmpcoder_is_availble() ) {
				$this->post_types['pro-'. substr($slug, 0, 2)] = esc_html( $title ) .' (Pro)';
			} else {
				$this->post_types[$slug] = esc_html( $title );
			}
		}

		$this->post_types['pro-pd'] = 'Products (Pro)';
		$this->post_types['pro-ft'] = 'Featured (Pro)';
		$this->post_types['pro-sl'] = 'On Sale (Pro)';

		$this->add_control(
			'query_source',
			[
				'label' => esc_html__( 'Source', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->post_types,
			]
		);
	}

	protected function register_controls() {

		// Section: General ----------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_post_type();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'content-ticker', 'post_type', ['pro-cm'] );

		$this->add_control(
			'link_type',
			[
				'label' => esc_html__( 'Link Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'title' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
					'image' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
					'image-title' => esc_html__( 'Image & Title', 'sastra-essential-addons-for-elementor' ),
					'box' => esc_html__( 'Box', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'image-title',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Query ------------
		$this->start_controls_section(
			'section_ticker_query',
			[
				'label' => esc_html__( 'Query', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'post_type' => 'dynamic',
				],
			]
		);

		$this->add_control_query_source();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'content-ticker', 'query_source', ['pro-pd', 'pro-ft', 'pro-sl'] );
		
		// Get Available Taxonomies
		$post_taxonomies = tmpcoder_get_custom_types_of( 'tax', false );

		// Get Available Meta Keys
		$post_meta_keys = tmpcoder_get_custom_meta_keys();

		$this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Selection', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'sastra-essential-addons-for-elementor' ),
					'manual' => esc_html__( 'Manual', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'query_source!' => [ 'current', 'related' ],
				],
			]
		);

		$this->add_control(
			'query_tax_selection',
			[
				'label' => esc_html__( 'Selection Taxonomy', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $post_taxonomies,
				'condition' => [
					'query_source' => 'related',
				],
			]
		);

		$this->add_control(
			'query_author',
			[
				'label' => esc_html__( 'Authors', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-ajax-select2',
				'options' => 'ajaxselect2/get_users',
				'multiple' => true,
				'label_block' => true,
				'separator' => 'before',
				'condition' => [
					'query_source!' => [ 'current', 'related' ],
					'query_selection' => 'dynamic',
				],
			]
		);

		// Taxonomies
		foreach ( $post_taxonomies as $slug => $title ) {
			global $wp_taxonomies;
			$post_type = '';

			if ( isset($wp_taxonomies[$slug]) && isset($wp_taxonomies[$slug]->object_type[0]) ) {
				$post_type = $wp_taxonomies[$slug]->object_type[0];
			}

			$this->add_control(
				'query_taxonomy_'. $slug,
				[
					'label' => $title,
					'type' => 'tmpcoder-ajax-select2',
					'options' => 'ajaxselect2/get_taxonomies',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'query_source' => $post_type,
						'query_selection' => 'dynamic',
					],
				]
			);
		}

		// Exclude
		foreach ( $this->post_types as $slug => $title ) {
			if ( 'featured' !== $slug && 'sale' !== $slug ) {
				$this->add_control(
					'query_exclude_'. $slug,
					[
						'label' => esc_html__( 'Exclude ', 'sastra-essential-addons-for-elementor' ) . $title,
						'type' => 'tmpcoder-ajax-select2',
						'options' => 'ajaxselect2/get_posts_by_post_type',
						'query_slug' => $slug,
						'multiple' => true,
						'label_block' => true,
						'condition' => [
							'query_source' => $slug,
							'query_source!' => [ 'current', 'related' ],
							'query_selection' => 'dynamic',
						],
					]
				);
			}
		}

		// Manual Selection
		foreach ( $this->post_types as $slug => $title ) {

			$this->add_control(
				'query_manual_'. $slug,
				[
					'label' => esc_html__( 'Select ', 'sastra-essential-addons-for-elementor' ) . $title,
					'type' => 'tmpcoder-ajax-select2',
					'options' => 'ajaxselect2/get_posts_by_post_type',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'query_source' => $slug,
						'query_selection' => 'manual',
					],
					'separator' => 'before',
				]
			);
		}

		$this->add_control(
			'query_posts_per_page',
			[
				'label' => esc_html__( 'Items Per Page', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 0,
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);

		$this->add_control(
			'query_offset',
			[
				'label' => esc_html__( 'Offset', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);


		$this->add_control(
			'post_order',
			[
				'label' => esc_html__( 'Order', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'sastra-essential-addons-for-elementor' ),
					'DESC' => esc_html__( 'Descending', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

    	$this->add_control(
			'post_orderby',
			[
				'label' => esc_html__( 'Order By', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'sastra-essential-addons-for-elementor' ),
					'modified' => esc_html__( 'Last Modified', 'sastra-essential-addons-for-elementor' ),
					'rand' => esc_html__( 'Rand', 'sastra-essential-addons-for-elementor' ),
					'title' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
					'ID' => esc_html__( 'Post ID', 'sastra-essential-addons-for-elementor' ),
					'author' => esc_html__( 'Post Author', 'sastra-essential-addons-for-elementor' ),
					'comment_count' => esc_html__( 'Comment Count', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'element_select_filter',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => $this->get_related_taxonomies(),
			]
		);

		$this->add_control(
			'post_meta_keys_filter',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => wp_json_encode( $post_meta_keys[0] ),
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Ticker Items ---------
		$this->add_section_ticker_items();

		// Section: Heading ----------
		$this->start_controls_section(
			'section_heading',
			[
				'label' => esc_html__( 'Heading', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'heading_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Hot News',
			]
		);

		$this->add_responsive_control(
			'heading_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 120,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'tmpcoder-ticker-heading-position-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'left' => [
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
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_icon_section',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control_heading_icon_type();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'content-ticker', 'heading_icon_type', ['pro-cc'] );

		$this->add_control(
			'heading_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
				'condition' => [
					'heading_icon_type' => 'fontawesome',
				],
			]
		);

		$this->add_control(
			'heading_icon_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'tmpcoder-ticker-heading-icon-position-',
				'condition' => [
					'heading_icon_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'heading_icon_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ticker-heading-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ticker-icon-circle' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ticker-icon-circle:before' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}}; margin-top: calc(-{{SIZE}}{{UNIT}} / 2);margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .tmpcoder-ticker-icon-circle:after' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}}; margin-top: calc(-{{SIZE}}{{UNIT}} / 2);margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
				],
				'condition' => [
					'heading_icon_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'heading_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-ticker-heading-icon-position-left .tmpcoder-ticker-heading-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-ticker-heading-icon-position-right .tmpcoder-ticker-heading-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'heading_icon_type!' => 'none',
				],
			]
		);

		// Triangle
		$this->add_control(
			'heading_triangle',
			[
				'label' => esc_html__( 'Triangle', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,				
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_triangle_position',
			[
				'label' => esc_html__( 'Vertical Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'top',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'prefix_class' => 'tmpcoder-ticker-heading-triangle-',
				'render_type' => 'template',
				'condition' => [
					'heading_triangle' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'heading_triangle_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],			
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-ticker-heading-position-left .tmpcoder-ticker-heading:before' => 'right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-ticker-heading-position-right .tmpcoder-ticker-heading:before' => 'left: -{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'heading_triangle' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_link',
			[
				'label' => esc_html__( 'Link', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'separator' => 'before',
				
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'image_switcher',
			[
				'label' => esc_html__( 'Show Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
				'condition' => [
					'image_switcher' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-slider' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ticker-item' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control_type_select();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'content-ticker', 'type_select', ['pro-mq'] );

		$this->add_responsive_control(
			'slider_amount',
			[
				'label' => esc_html__( 'Number of Slides', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => 4,
				'widescreen_default' => 4,
				'laptop_default' => 4,
				'tablet_extra_default' => 4,
				'tablet_default' => 3,
				'mobile_extra_default' => 3,
				'mobile_default' => 1,
				'min' => 1,
				'max' => 10,
				'prefix_class' => 'tmpcoder-ticker-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'slider_effect' => 'hr-slide',
					'type_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'frontend_available' => true,
				'default' => 1,
				'widescreen_default' => 1,
				'laptop_default' => 1,
				'tablet_extra_default' => 1,
				'tablet_default' => 1,
				'mobile_extra_default' => 1,
				'mobile_default' => 1,
				'prefix_class' => 'tmpcoder-ticker-slides-to-scroll-',
				'render_type' => 'template',
				'condition' => [
					'slider_effect' => 'hr-slide',
					'type_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'slider_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],			
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-slider .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ticker-slider .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-ticker-marquee .tmpcoder-ticker-item' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',		
				'separator' => 'before',
				'conditions' => [
       		    	'relation' => 'or',
					'terms' => [
						[
							'name' => 'type_select',
							'operator' => '=',
							'value' => 'marquee',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'type_select',
									'operator' => '=',
									'value' => 'slider',
								],
								[
									'name' => 'slider_amount',
									'operator' => '!=',
									'value' => '1',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',			
				'separator' => 'before',
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'slider_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle-left',
				'options' => [
					'fas fa-angle-left' => esc_html__( 'Angle', 'sastra-essential-addons-for-elementor' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'sastra-essential-addons-for-elementor' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'sastra-essential-addons-for-elementor' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'type_select' => 'slider',
					'slider_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_nav_style',
			[
				'label' => esc_html__( 'Style', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'sastra-essential-addons-for-elementor' ),
					'vertical' => esc_html__( 'Vertical', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-ticker-arrow-style-',
				'condition' => [
					'type_select' => 'slider',
					'slider_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_nav_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'tmpcoder-ticker-arrow-position-',
				'render_type' => 'template',		
				'condition' => [
					'type_select' => 'slider',
					'slider_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'slider_autoplay_duration',
			[
				'label' => esc_html__( 'Autoplay Speed', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'min' => 0,
				'max' => 15,
				'step' => 0.5,
				'frontend_available' => true,
				'condition' => [
					'type_select' => 'slider',
					'slider_autoplay' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'slider_pause_on_hover',
			[
				'label' => esc_html__( 'Pause Slide on Hover', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'slider_autoplay' => 'yes',
					'type_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'slider_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);
		
		$this->add_control_slider_effect();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'content-ticker', 'slider_effect', ['pro-tp', 'pro-fd', 'pro-vs'] );

		$this->add_control_slider_effect_cursor();

		$this->add_control(
			'slider_effect_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,	
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);

		$this->add_control_marquee_direction();

		$this->add_control_marquee_pause_on_hover();

		$this->add_control_marquee_effect_duration();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'content-ticker', [
			__('Add Custom Ticker Items (Instead of loading Dynamically)', 'sastra-essential-addons-for-elementor'),
			__('Marquee Animation - a Smooth Animation with Direction option', 'sastra-essential-addons-for-elementor'),
			__('Slider Animation options - Typing, Fade & Vertical Slide', 'sastra-essential-addons-for-elementor'),
			__('Heading Icon Type - Animated Circle', 'sastra-essential-addons-for-elementor'),
			__('Custom Post Types Support (Pro)', 'sastra-essential-addons-for-elementor'),
		] );
		
		// Styles
		// Section: Heading ----------
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Heading', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_heading_colors' );

		$this->start_controls_tab(
			'tab_heading_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'heading_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ticker-heading::before' => 'border-right-color: {{VALUE}};background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ticker-heading-icon svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ticker-icon-circle' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ticker-icon-circle::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ticker-icon-circle::after' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_heading_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'heading_hover_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ticker-heading:hover:before' => 'border-right-color: {{VALUE}};background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading:hover .tmpcoder-ticker-heading-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ticker-heading:hover .tmpcoder-ticker-heading-icon svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ticker-heading:hover .tmpcoder-ticker-icon-circle' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ticker-heading:hover .tmpcoder-ticker-icon-circle::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-ticker-heading:hover .tmpcoder-ticker-icon-circle::after' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-ticker-heading svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',

				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-ticker-heading-text',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'heading_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_border_type',
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
					'{{WRAPPER}} .tmpcoder-ticker-heading' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'heading_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-heading' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'heading_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'heading_border_radius',
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
					'{{WRAPPER}} .tmpcoder-ticker-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_input',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-content-ticker-inner' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-ticker-gradient:after' => 'background-image: linear-gradient(to right,rgba(255,255,255,0),{{VALUE}});',
					'{{WRAPPER}} .tmpcoder-ticker-gradient:before' => 'background-image: linear-gradient(to left,rgba(255,255,255,0),{{VALUE}});',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-content-ticker',
			]
		);

		$this->add_control(
			'content_gradient_position',
			[
				'label' => esc_html__( 'Gradient Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'left' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
					'right' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
					'both' => esc_html__( 'Both', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-ticker-gradient-type-',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 30,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-content-ticker-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_type',
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
					'{{WRAPPER}} .tmpcoder-content-ticker' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-content-ticker' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#dbdbdb',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-content-ticker' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-content-ticker' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		// Title
		$this->add_control(
			'content_title_section',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_title_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-ticker-title',
			]
		);
		
		$this->start_controls_tabs( 'tabs_content_title_colors' );

		$this->start_controls_tab(
			'tab_content_title_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);


		$this->add_control(
			'content_title_color',
			[
				'label' => esc_html__( 'Title Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-title, .tmpcoder-ticker-title a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-ticker-title:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content_title_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'content_hover_title_color',
			[
				'label' => esc_html__( 'Title Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-title:hover a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-ticker-title:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .tmpcoder-ticker-title',
			]
		);

		// Image
		$this->add_control(
			'content_image_section',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_image_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-image' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Navigation -------
		$this->start_controls_section(
			'section_style_nav',
			[
				'label' => esc_html__( 'Navigation', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'type_select' => 'slider',
					'slider_nav' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_nav_style' );

		$this->start_controls_tab(
			'tab_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'nav_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#045CB4',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_font_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_size',
			[
				'label' => esc_html__( 'Box Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],			
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-ticker-arrow-style-vertical .tmpcoder-ticker-prev-arrow' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-ticker-arrow-style-horizontal .tmpcoder-ticker-prev-arrow' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'nav_border_type',
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
					'{{WRAPPER}} .tmpcoder-ticker-arrow' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-ticker-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	// Get Taxonomies Related to Post Type
	public function get_related_taxonomies() {
		$relations = [];
		$this->post_types = tmpcoder_get_custom_types_of( 'post', false );

		foreach ( $this->post_types as $slug => $title ) {
			$relations[$slug] = [];

			foreach ( get_object_taxonomies( $slug ) as $tax ) {
				array_push( $relations[$slug], $tax );
			}
		}

		return wp_json_encode( $relations );
	}

	// Main Query Args
	public function get_main_query_args() {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$author = ! empty( $settings[ 'query_author' ] ) ? implode( ',', $settings[ 'query_author' ] ) : '';

		in_array( $settings[ 'query_source' ], ['pro-pd', 'pro-ft', 'pro-sl'] ) ? $settings[ 'query_source' ] = 'post' : '';

		$ids_array = '';

		$query_exclude__args = isset($settings[ 'query_exclude_'. $settings[ 'query_source' ] ]) ? $settings[ 'query_exclude_'. $settings[ 'query_source' ] ] : '' ;

		if (isset($query_exclude__args) && (is_array($query_exclude__args) && count($query_exclude__args)) ) {

			$slug_args = [
			    'post_type'      => $settings[ 'query_source' ],
			    'posts_per_page' => -1,
			    'post_name__in'  => isset($settings[ 'query_exclude_'. $settings[ 'query_source' ] ]) ? $settings[ 'query_exclude_'. $settings[ 'query_source' ] ] : '',
			    'fields'         => 'ids' 
			];

			$ids_array = get_posts( $slug_args );
		}

		// Dynamic
		$args = [
			'post_type' => $settings[ 'query_source' ],
			'tax_query' => $this->get_tax_query_args(),
			'post__not_in' => $ids_array,
			'posts_per_page' => $settings['query_posts_per_page'],
			'orderby' => $settings[ 'post_orderby' ],
			'order' => $settings[ 'post_order' ],
			'author' => $author,
			'offset' => $settings[ 'query_offset' ],
		];

		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_'. $settings[ 'query_source' ] ]) ) {
				$post_ids = $settings[ 'query_manual_'. $settings[ 'query_source' ] ];
			}

			$args = [
				'post_type' => $settings[ 'query_source' ],
				// 'post__in' => $post_ids,
				'post_name__in' => $post_ids,
				'orderby' => $settings[ 'post_orderby' ],
				'order' => $settings[ 'post_order' ],
			];
		}

		// Get Post Type
		if ( 'current' === $settings[ 'query_source' ] ) {
			global $wp_query;

			$args = $wp_query->query_vars;
			$args['posts_per_page'] = $settings['query_posts_per_page'];
			$args['orderby'] = $settings['post_orderby'];
		}

		// Related
		if ( 'related' === $settings[ 'query_source' ] ) {
			$args = [
				'post_type' => get_post_type( get_the_ID() ),
				'tax_query' => $this->get_tax_query_args(),
				'post__not_in' => [ get_the_ID() ],
				'ignore_sticky_posts' => 1,
				'posts_per_page' => $settings['query_posts_per_page'],
				'orderby' => $settings[ 'post_orderby' ],
				'order' => $settings[ 'post_order' ],
				'offset' => $settings[ 'query_offset' ],
			];
		}

		if ( 'featured' === $settings[ 'query_source' ] ) {
			$args['post_type'] = 'product';
			$tax_query[] = [
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN', // or 'NOT IN' to exclude feature products
			];
			$args['tax_query'] = $tax_query;
		}

		if ( 'sale' === $settings[ 'query_source' ] ) {
			$args['post_type'] = 'product';
			$meta_query[] = [
				'relation' => 'OR',
				[ // Simple products type
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				],
				[ // Variable products type
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				]
			];

			$args['meta_query'] = $meta_query;
		}

		return $args;
	}

	// Taxonomy Query Args
	public function get_tax_query_args() {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$tax_query = [];

		if ( 'related' === $settings[ 'query_source' ] ) {
			$tax_query = [
				[
					'taxonomy' => $settings['query_tax_selection'],
					'field' => 'term_id',
					'terms' => wp_get_object_terms( get_the_ID(), $settings['query_tax_selection'], array( 'fields' => 'ids' ) ),
				]
			];
		} else {
			foreach ( get_object_taxonomies($settings[ 'query_source' ]) as $tax ) {
				if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
					array_push( $tax_query, [
						'taxonomy' => $tax,
						'field' => 'slug',
						'terms' => $settings[ 'query_taxonomy_'. $tax ]
					] );
				}
			}
		}

		return $tax_query;
	}

	// Dynamic Content Ticker
	public function tmpcoder_content_ticker_dynamic() {
		//  Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
	
		// Get Posts
		$posts = new \WP_Query( $this->get_main_query_args() );
			
		if ( $posts->have_posts() ) :

		while ( $posts->have_posts() ) : $posts->the_post();

				$image_id = get_post_thumbnail_id();
				$settings[ 'image_size' ] = ['id' => $image_id];
				$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size' );
				// Update the alt attribute
					
				if ('' === get_post_meta( $image_id, '_wp_attachment_image_alt', true )) {
					$image_html = preg_replace( '/<img(.*?)alt="(.*?)"(.*?)>/i', '<img$1alt="'.get_the_title().'"$3>', $image_html );
				}
			?>

			<div class="tmpcoder-ticker-item">

				<?php if ( 'box' === $settings['link_type'] ): ?>
				<a class="tmpcoder-ticker-link" href="<?php echo esc_url( get_the_permalink() ); ?>"></a>	
				<?php endif; ?>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="tmpcoder-ticker-image">
						
						<?php
						if ( 'image' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
							echo '<a  href="'. esc_url( get_the_permalink() ).'">';
						}

						if ( 'yes' === $settings['image_switcher'] && $image_html ) {	
							echo wp_kses_post($image_html);
						}
					
						if ( 'image' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
							echo '</a>';
						}
						?>

					</div>
				<?php endif; ?>

				<h3 class="tmpcoder-ticker-title">
					<div class="tmpcoder-ticker-title-inner">
					<?php
					if ( 'title' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
						echo '<a href="'. esc_url( get_the_permalink() ).'">';
					}

					the_title();
				
					if ( 'title' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
						echo '</a>';
					}
					?>
					</div>
				</h3>

			</div>

		<?php

		endwhile;

		// reset
		wp_reset_postdata();

		// Loop: End
		endif;
	}


	// Custom Content Ticker
	public function tmpcoder_content_ticker_custom() {}

	public function tmpcoder_content_ticker_heading() {

		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$heading_element = 'div';
		$heading_link =  $settings['heading_link']['url'];

		$this->add_render_attribute( 'heading_attribute', 'class', 'tmpcoder-ticker-heading' );

		if ( '' !== $heading_link ) {

			$heading_element = 'a';

			$this->add_render_attribute( 'heading_attribute', 'href', $settings['heading_link']['url'] );

			if ( $settings['heading_link']['is_external'] ) {
				$this->add_render_attribute( 'heading_attribute', 'target', '_blank' );
			}

			if ( $settings['heading_link']['nofollow'] ) {
				$this->add_render_attribute( 'heading_attribute', 'nofollow', '' );
			}
		}


		if ( ! tmpcoder_is_availble() ) {
			$settings['heading_icon_type'] = ( 'pro-cc' == $settings['heading_icon_type'] ) ? 'none' : $settings['heading_icon_type'];
		}

		?>

        <?php echo wp_kses_post('<'. esc_html($heading_element).' '.$this->get_render_attribute_string( 'heading_attribute' ).'>');
        ?>
			<span class="tmpcoder-ticker-heading-text"><?php echo esc_html( $settings['heading_text'] ); ?></span>
			<span class="tmpcoder-ticker-heading-icon">
				<?php if ( 'fontawesome' === $settings['heading_icon_type'] ): ?>	
				<?php \Elementor\Icons_Manager::render_icon( $settings['heading_icon'] ); ?>
				<?php elseif ( 'circle' === $settings['heading_icon_type'] ) : ?>
				<span class="tmpcoder-ticker-icon-circle"></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($heading_element); ?>>

		<?php
	}

	public function tmpcoder_content_ticker_slider() {
		
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';
		if ( ! tmpcoder_is_availble() ) {
			$settings['slider_effect'] = 'hr-slide';
		}

		$slider_options = [
			'rtl' => $slider_is_rtl,
			'infinite' => ( $settings['slider_loop'] === 'yes' ),
			'speed' => absint( $settings['slider_effect_duration'] * 1000 ),
			'autoplay' => ( $settings['slider_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( $settings['slider_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['slider_pause_on_hover'],
			'arrows' => false,
		];

		if ( $settings['slider_effect'] === 'vr-slide' ) {
			$slider_options['vertical'] = true;
		}

		if ( $settings['slider_nav'] === 'yes' ) {
			$slider_options['arrows'] = true;
			$slider_options['prevArrow'] = '<div class="tmpcoder-ticker-prev-arrow tmpcoder-ticker-arrow"><i class="'. esc_attr($settings['slider_nav_icon'] ?? '') .'"></i></div>';
			$slider_options['nextArrow'] = '<div class="tmpcoder-ticker-next-arrow tmpcoder-ticker-arrow"><i class="'. esc_attr($settings['slider_nav_icon'] ?? '') .'"></i></div>';
		}

		$this->add_render_attribute( 'ticker-slider-attribute', [
			'class' => 'tmpcoder-ticker-slider',
			'dir' => esc_attr( $slider_direction ),
			'data-slick' => wp_json_encode( $slider_options ),
		] );


		if ( 'none' !== $settings['content_gradient_position'] ) {
			$this->add_render_attribute( 'ticker-slider-attribute','class', 'tmpcoder-ticker-gradient' );
		}

		if ( ! tmpcoder_is_availble() ) {
			$settings['post_type'] = 'dynamic';
		}

		?>

		<?php echo wp_kses_post('<div '. $this->get_render_attribute_string( 'ticker-slider-attribute' ).' data-slide-effect="'. esc_attr($settings['slider_effect'] ?? '').'">'); ?>
			<?php
				if ( 'dynamic' === $settings['post_type'] ) {
					$this->tmpcoder_content_ticker_dynamic();
				} else {
					$this->tmpcoder_content_ticker_custom();
				}
			?>
		</div>

		<div class="tmpcoder-ticker-slider-controls"></div>

		<?php

	}

	public function tmpcoder_content_ticker_marquee() {}

	protected function render() {

		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		?>

		<!-- Content Ticker Slider -->
		<div class="tmpcoder-content-ticker">

			<?php

			if ( (!empty($settings['heading_text']) && '' !== $settings['heading_text']) || 'none' !== $settings['heading_icon_type'] ) {
				$this->tmpcoder_content_ticker_heading(); 
			}

			?>

			<div class="tmpcoder-content-ticker-inner">
				
				<?php

				if ( !tmpcoder_is_availble() ) {
					$settings['type_select'] = 'slider';
				}

				if ( 'slider' === $settings['type_select'] ) {
					$this->tmpcoder_content_ticker_slider();
				} else {
					$this->tmpcoder_content_ticker_marquee();
				}
				
				?>
				
			</div>

		</div>

		<?php
	}
}