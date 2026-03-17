<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Posts_Timeline extends Widget_Base {

	public function get_name() {
		return 'tmpcoder-posts-timeline';
	}

	public function get_title() {
		return esc_html__( 'Post/Story Timeline', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-time-line';
	}

	public function get_categories() {
		if (tmpcoder_show_theme_buider_widget_on('type_archive')) {
			return [ 'tmpcoder-theme-builder-widgets' ];
		}
		else
		{
			return [ 'tmpcoder-widgets-category' ];
		}
	}

	public function get_keywords() {
		return [ 'spexo', 'post timeline', 'blog', 'post', 'posts', 'timeline', 'posts timeline', 'story timeline', 'content timeline'];
	}

	public function get_script_depends() {

		$depends = [ 'swiper' => true, 'tmpcoder-aos-js' => true, 'tmpcoder-infinite-scroll' => true, 'tmpcoder-posts-timeline' => true ];

		if ( ! tmpcoder_elementor()->preview->is_preview_mode() ) {
			$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

			if ( $settings['timeline_layout'] != 'horizontal-bottom' ) {
				unset( $depends['swiper'] );
			}if ( $settings['timeline_animation'] == 'none' ) {
				unset( $depends['tmpcoder-aos-js'] );
			}if ($settings['show_pagination'] != 'yes' ) {
				unset( $depends['tmpcoder-infinite-scroll'] );
			}
		}

		return array_keys($depends);
	}

	public function get_style_depends() {

		$depends = [ 'swiper' => true, 'tmpcoder-animations-css' => true, 'tmpcoder-loading-animations-css' => true, 'tmpcoder-aos-css' => true, 'tmpcoder-posts-timeline' => true ];

		if ( ! tmpcoder_elementor()->preview->is_preview_mode() ) {
			$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

			if ( $settings['timeline_layout'] != 'horizontal-bottom' ) {
				unset( $depends['swiper'] );
			}if ( $settings['timeline_animation'] == 'none' ) {
				unset( $depends['tmpcoder-aos-css'] );
			}if ($settings['show_pagination'] != 'yes' ) {
				unset( $depends['tmpcoder-loading-animations-css'] );
			}
		}

		return array_keys($depends);
	}

    public function get_custom_help_url() {
    	return TMPCODER_NEED_HELP_URL;
    }
	
	public $my_query;
	public $src;	
	public $slidesToShow;	
	public $animation;
	public $animation_loadmore_left;
	public $animation_loadmore_right;		
	public $timeline_fill;
	public $show_readmore;		
	public $pagination_type;
	public $pagination_max_page;
	public $pagination_max_pages;
	public $animation_class;
	public $timeline_layout;
	public $timeline_layout_wrapper;
	public $item_url_count;				
	public $thumbnail_size;
	public $thumbnail_custom_dimension;
	public $show_year_label;
	public $timeline_year;            
	public $image;
	public $slides_to_show;
	public $horizontal_inner_class;
	public $horizontal_timeline_class;
	public $swiper_class;
	public $timeline_description;
	public $story_date_label;
	public $story_extra_label;
	public $timeline_story_title;
	public $title_key;
	public $year_key;
	public $date_label_key;
	public $extra_label_key;
	public $description_key;
	public $background_image;
	public $background_class;

	public function tmpcoder_aos_animation_array() {
		return [
			'none' => 'None',
			'fade' => 'Fade',
			'pro-fu' => 'Fade Up (pro)',
			'pro-fd' => 'Fade Down (pro)',
			'pro-fl' => 'Fade Left (pro)',
			'pro-fr' => 'Fade Right (pro)',
			'pro-fur' => 'Fade Up Right (pro)',
			'pro-ful' => 'Fade Up Left (pro)',
			'pro-fdr' => 'Fade Down Right (pro)',
			'pro-fdl' => 'Fade Down Left (pro)',
			'pro-flu' => 'Flip Up (pro)',
			'pro-fld' => 'Flip Down (pro)',
			'pro-flr' => 'Flip right (pro)',
			'pro-fll' => 'Flip Left (pro)',
			'pro-slu' => 'Slide Up (pro)',
			'pro-sll' => 'Slide Left (pro)',
			'pro-slr' => 'Slide Right (pro)',
			'pro-sld' => 'Slide Down (pro)',
			'pro-zmi' => 'Zoom In (pro)',
			'pro-zmo' => 'Zoom Out (pro)',
			'pro-zmiu' => 'Zoom In Up (pro)',
			'pro-zmid' => 'Zoom In Down (pro)',
			'pro-zmil' => 'Zoom In Left (pro)',
			'pro-zmir' => 'Zoom In Right (pro)',
			'pro-zmou' => 'Zoom Out Up (pro)',
			'pro-zmod' => 'Zoom Out Down (pro)',
			'pro-zmol' => 'Zoom Out Left (pro)',
			'pro-zmor' => 'Zoom Out Right (pro)',
	   ];
	}

	public function background_blend_modes() {
		return [
			'normal' => 'Normal',
			'multiply' => 'Multiply',
			'screen' => 'Screen',
			'overlay' => 'Overlay',
			'darken' => 'Darken',
			'lighten' => 'Lighten',
			'color-dodge' => 'Color-Dodge',
			'color-burn' => 'Color-Burn',
			'hard-light' => 'Hard-Light',
			'soft-light' => 'Soft-Light',
			'difference' => 'Difference',
			'exclusion' => 'Exclusion',
			'hue' => 'Hue',
			'saturation' => 'Saturation',
			'color' => 'Color',
			'luminosity' => 'Luminosity'
		];
	}

	public function add_control_slides_to_show() {
		$this->add_control(
			'slides_to_show',
			[
				'label' => __( 'Slides To Show', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '3',
				'max' => '4',
				'separator' => 'before',
				'render_type' => 'template',
				'condition'   => [
					'timeline_layout'   => [
					   'horizontal',
					   'horizontal-bottom'
					],
				]
			]
		);
	}

	public function add_control_swiper_loop() {
		$this->add_control(
			'swiper_loop',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Loop %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}

	public function add_control_group_autoplay() {
		$this->add_control(
			'swiper_autoplay',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Autoplay %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}

	public function add_control_show_pagination() {
		$this->add_control(
			'show_pagination',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Pagination %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);	
	}

	public function add_control_posts_per_page() {
        $this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::NUMBER,
				'render_type' => 'template',
				'default' => 3,
				'max' => 4,
                'min' => 0,
				'label_block' => false,
			]
		);
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general_section',
			[
				'label' => __( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );
	
		$this->add_control(
			'timeline_content',
			[
				'label' => __( 'Timeline Content', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'custom',
				'options'=>[
					'custom' => esc_html__('Custom', 'sastra-essential-addons-for-elementor'),
					'dynamic' => esc_html__('Dynamic', 'sastra-essential-addons-for-elementor')
				],
				'render_type' => 'template',
			]
		);
	
		$this->add_control(
			'timeline_layout',
			[
				'label' => __( 'Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'centered',
				'options'=>[
					'centered'=> esc_html__('Zig-Zag', 'sastra-essential-addons-for-elementor'),
					'one-sided'=> esc_html__('Line Left', 'sastra-essential-addons-for-elementor'),
					'one-sided-left'=> esc_html__('Line Right', 'sastra-essential-addons-for-elementor'),
					'horizontal-bottom'=> esc_html__('Line Top - Carousel', 'sastra-essential-addons-for-elementor'),
				],
				'render_type' => 'template',
			]
		);
	
		$this->add_control(
			'content_layout',
			[
				'label' => __( 'Media Position', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'image-top',
				'options'=>[
					'image-top' => esc_html__('Top', 'sastra-essential-addons-for-elementor'),
					'image-bottom' => esc_html__('Bottom', 'sastra-essential-addons-for-elementor'),
					// 'background' => esc_html__('Background', 'sastra-essential-addons-for-elementor'),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[ 
				'name' => 'tmpcoder_thumbnail_dynamic',
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);
	
		$this->add_control(
			'date_format',
			[
				'label' => __( 'Date Format', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'F j, Y',
				'options'=> [
					// Translators: %s is date.
					'F j, Y' => gmdate('F j, Y'),

					// Translators: %s is date.
					'Y-m-d' => gmdate('Y-m-d'),

					// Translators: %s is date.
					'Y, M, D' => gmdate('Y, M, D'),

					// Translators: %s is date.
					'm/d/Y' => gmdate('m/d/Y'),

					// Translators: %s is date.
					'd/m/Y' => gmdate('d/m/Y'),
				
					// Translators: %s is date.
					'j. F Y' => gmdate('j. F Y'),
				],
				'condition' => [
					'timeline_content' => 'dynamic',
				]
			]
		);

		$this->add_control(
			'timeline_fill',
			[
				'label' => esc_html__( 'Main Line Fill', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->add_control(
			'posts_icon',
			[
				'label' => __( 'Main Line Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-apple',
					'library' => 'solid',
				],
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);
		
		$this->add_control(
			'show_extra_label',
			[
				'label' => esc_html__( 'Show Extra Label', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'separator' => 'before',
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);
		
		$this->add_control_slides_to_show();

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'slides_to_show_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-posts-timeline-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
					'condition'   => [
						'timeline_layout'   => [
						   'horizontal',
						   'horizontal-bottom'
						],
					]
				]
			);
		}
				
		$this->add_control(
			'story_info_gutter',
			[
				'label' => __( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5,
				'condition'   => [
					'timeline_layout'   => [
					   'horizontal',
					   'horizontal-bottom'
					],
				]
			]
		);

		$this->add_control(
			'equal_height_slides',
			[
				'label' => esc_html__( 'Equal Height Slides', 'sastra-essential-addons-for-elementor' ),
				'description' => __('Make all slides the same height', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'auto-height',
				'default' => 'no',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'timeline_layout'   => [
					   'horizontal-bottom',
					   'horizontal'
					],
				]
			]
		);


		$this->add_control_swiper_loop();

		$this->add_control_group_autoplay();
				
		$this->add_control(
			'swiper_speed',
			[
				'label' => esc_html__( 'Carousel Speed', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'frontend_available' => true,
				'default' => 500,
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->add_control(
			'swiper_nav_icon',
			[
				'label' => esc_html__( 'Carousel Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle-left',
				'options' => tmpcoder_get_svg_icons_array( 'arrows', [
					'fas fa-angle-left' => esc_html__( 'Angle', 'sastra-essential-addons-for-elementor' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'sastra-essential-addons-for-elementor' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'sastra-essential-addons-for-elementor' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'sastra-essential-addons-for-elementor' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'sastra-essential-addons-for-elementor' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'sastra-essential-addons-for-elementor' ),
				] ),
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom'],
				],
			]
		);
		
		$this->add_control(
			'timeline_animation',
			[
				'label' => __( 'Entrance Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'fade',
				'separator' => 'before',
				'options' => $this->tmpcoder_aos_animation_array(),
				'condition'   => [
					'timeline_layout!'   => [
						'horizontal',
						'horizontal-bottom'
					 ],
				]
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'posts-timeline', 'timeline_animation', [
			'pro-fu',
			'pro-fd',
			'pro-fl',
			'pro-fr',
			'pro-fur',
			'pro-ful',
			'pro-fdr',
			'pro-fdl',
			'pro-flu',
			'pro-fld',
			'pro-flr',
			'pro-fll',
			'pro-slu',
			'pro-sll',
			'pro-slr',
			'pro-sld',
			'pro-zmi',
			'pro-zmo',
			'pro-zmiu',
			'pro-zmid',
			'pro-zmil',
			'pro-zmir',
			'pro-zmou',
			'pro-zmod',
			'pro-zmol',
			'pro-zmor',
		] );

		$this->add_control(
			'animation_offset',
			[
				'label' => esc_html__( 'Animation Offset', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 500,
				'frontend_available' => true,
				'default' => 150,
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'aos_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 2000,
				'frontend_available' => true,
				'default' => 600,
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control_show_pagination();
		
		$this->end_controls_section();

		$this->start_controls_section(
			'repeater_content_section',
			[
				'label' => __( 'Timeline Items', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'timeline_content' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs(
			'story_tabs'
		);

		$repeater->start_controls_tab(
			'content_tab',
			[
				'label' => __( 'Content', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$repeater->add_control(
			'main_line_label_heading',
			[
				'label' => esc_html__( 'Main Line Label', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$repeater->add_control(
			'repeater_show_year_label',
			[
				'label' => __( 'Show Label', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'sastra-essential-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'sastra-essential-addons-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$repeater->add_control(
			'repeater_year',
			[
				'label' => __( 'Label Text', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '2022',
				'condition' => [
					'repeater_show_year_label' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'main_line_label_icon',
			[
				'label' => esc_html__( 'Main Line Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'repeater_story_icon',
			[
				'label' => __( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-apple',
					'library' => 'solid',
				],
			]
		);

		$repeater->add_control(
			'extra_label_heading',
			[
				'label' => esc_html__( 'Extra Label', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$repeater->add_control(
			'repeater_show_extra_label',
			[
				'label' => esc_html__( 'Show Extra Label', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
			]
		);
		
		$repeater->add_control(
			'repeater_date_label',
			[
				'label' => __( 'Primary Label', 'sastra-essential-addons-for-elementor' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '01 Jan 2022',
				'condition' => [
					'repeater_show_extra_label' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_extra_label',
			[
				'label' => __( 'Secondary Label', 'sastra-essential-addons-for-elementor' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Secondaty Label',
				'condition' => [
					'repeater_show_extra_label' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_media',
			[
				'label' => esc_html__( 'Display Media', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => [
					'image' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
					'icon' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
					'video' => esc_html__( 'Video', 'sastra-essential-addons-for-elementor' ),
				],
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[ 
				'name' => 'tmpcoder_thumbnail',
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'repeater_media' => 'image'
				]
			]
		);
				
		$repeater->add_control(
			'repeater_youtube_video_url',
			[
				'label' => __( 'Youtube Video Link', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => '',
				'condition'   => [
					'repeater_media' => 'video',
				]
			]
		);

		$repeater->add_control(
			'repeater_image',
			[
				'label' => __( 'Choose Image', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'description' => __('Image Size will not work with default image', 'sastra-essential-addons-for-elementor'),
				'condition' => [
					'repeater_media' => 'image'
				]
			]
		);

		$repeater->add_control(
			'repeater_timeline_item_icon',
			[
				'label' => __( 'Media Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-address-book',
					'library' => 'solid',
				],
				'condition' => [
					'repeater_media' => 'icon'
				]
			]
		);

		$repeater->add_control(
			'repeater_story_title',
			[
				'label' => __( 'Item Title', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Timeline Story',
				'label_block' => true,
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'repeater_title_link',
			[
				'label' => esc_html__( 'Item Title URL', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
			]
		);

		$repeater->add_control(
			'repeater_description',
			[
				'label' => __( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => 'Add Description Here',
			]
		);
		
		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'repeater_advanced_tab',
			[
				'label' => __( 'STYLE', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$repeater->add_control(
			'show_custom_styles',
			[
				'label' => esc_html__( 'Custom Colors', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);

		$repeater->add_control(
			'item_main_styles',
			[
				'label' => __('Item', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_custom_styles' => 'yes'
				]				
			]
		);

		$repeater->add_control(
			'item_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-story-info-vertical.tmpcoder-data-wrap' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom-timeline {{CURRENT_ITEM}} .tmpcoder-story-info' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal {{CURRENT_ITEM}} .tmpcoder-story-info' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_story_border_color',
			[
				'label' => __( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-story-info' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}}.tmpcoder-left-aligned .tmpcoder-story-info-vertical' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}}.tmpcoder-right-aligned .tmpcoder-story-info-vertical' => 'border-color: {{VALUE}} !important;',

					'{{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide-line-top .tmpcoder-story-info:before' => 'border-bottom-color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide-line-bottom .tmpcoder-story-info:before' => 'border-top-color: {{VALUE}} !important',
					'{{WRAPPER}} {{CURRENT_ITEM}}.tmpcoder-left-aligned .tmpcoder-story-info-vertical:after' => 'border-left-color: {{VALUE}} !important',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .tmpcoder-wrapper .tmpcoder-both-sided-timeline .tmpcoder-left-aligned .tmpcoder-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
					'{{WRAPPER}} .tmpcoder-centered .tmpcoder-one-sided-timeline .tmpcoder-right-aligned-aligned .tmpcoder-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
				],
				'default' => '#5729d9',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_triangle_color',
			[
				'label' => __('Triangle', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_custom_styles' => 'yes'
				]			
			]
		);

		$repeater->add_control(
			'repeater_triangle_bgcolor',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-one-sided-timeline {{CURRENT_ITEM}}.tmpcoder-right-aligned .tmpcoder-data-wrap:after' => 'border-right-color: {{icon_bgcolor}}',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-one-sided-timeline-left {{CURRENT_ITEM}}.tmpcoder-left-aligned .tmpcoder-data-wrap:after' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-wrapper {{CURRENT_ITEM}}.tmpcoder-right-aligned .tmpcoder-data-wrap:after' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal {{CURRENT_ITEM}} .tmpcoder-story-info:before' => 'border-top-color: {{VALUE}} !important',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom {{CURRENT_ITEM}} .tmpcoder-story-info:before' => 'border-bottom-color: {{VALUE}} !important',
					'{{WRAPPER}} .tmpcoder-wrapper {{CURRENT_ITEM}}.tmpcoder-left-aligned .tmpcoder-data-wrap:after' => 'border-left-color: {{VALUE}} !important',
					'{{WRAPPER}} .tmpcoder-centered {{CURRENT_ITEM}} .tmpcoder-one-sided-timeline .tmpcoder-right-aligned .tmpcoder-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
					'{{WRAPPER}} .tmpcoder-wrapper {{CURRENT_ITEM}} .tmpcoder-one-sided-timeline-left .tmpcoder-left-aligned .tmpcoder-data-wrap:after' => 'border-left-color: {{VALUE}} !important',
				],
				'default' => '#5729d9',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_media_styles',
			[
				'label' => __('Media', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_custom_styles' => 'yes'
				]			
			]
		);

		$repeater->add_control(
			'repeater_overlay_bgcolor',
			[
				'label' => __( 'Overlay Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper {{CURRENT_ITEM}} .tmpcoder-timeline-story-overlay' => 'background-color: {{VALUE}}',
				],
				'default' => '#0000005E',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_media_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-timeline-media' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_item_content_styles',
			[
				'label' => __('Content', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_custom_styles' => 'yes'
				]			
			]
		);
		
		/*---- Story Title ----*/
		$repeater->add_control(
			'repeater_story_title_color',
			[
				'label' => __( 'Title Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-title' => 'color: {{VALUE}} !important;',
				],
				'default' => '#444444',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_description_color',
			[
				'label' => __( 'Description Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper {{CURRENT_ITEM}} .tmpcoder-description' => 'color: {{VALUE}};',
				],
				'default' => '#333333',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'item_content_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-timeline-content-wrapper' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_main_line_content_styles',
			[
				'label' => __('Main Line', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_custom_styles' => 'yes'
				]			
			]
		);
		
		$repeater->add_control(
			'repeater_timeline_icon_color',
			[
				'label' => __( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}  .tmpcoder-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}}  .tmpcoder-icon svg' => 'fill: {{VALUE}}'
				],
				'default' => '#000',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_icon_timeline_fill_color',
			[
				'label'  => esc_html__( 'Icon Fill Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-change-border-color.tmpcoder-icon i' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-change-border-color.tmpcoder-icon svg' => 'fill: {{VALUE}} !important;',
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);
		
		$repeater->add_control(
			'repeater_timeline_icon_bg_color',
			[
				'label' => __( 'Icon Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper {{CURRENT_ITEM}} .tmpcoder-icon' => 'background-color: {{VALUE}} !important;',
				],
				'default' => '#FFFFF',
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'repeater_icon_timeline_background_fill_color',
			[
				'label'  => esc_html__( 'Icon Background Fill Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-change-border-color.tmpcoder-icon' => 'background-color: {{VALUE}} !important;',
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				],
			]
		);

		$repeater->add_control(
			'repeater_icon_border_color',
			[
				'label'  => esc_html__( 'Icon Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper {{CURRENT_ITEM}} .tmpcoder-icon' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'show_custom_styles' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'item_icon_styles',
			[
				'label' => __('Media Icon', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'repeater_media' => 'icon',
				],				
			]
		);
		
		$repeater->add_control(
			'repeater_timeline_item_icon_color',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}  .tmpcoder-timeline-media i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}}  .tmpcoder-timeline-media svg' => 'fill: {{VALUE}}'
				],
				'condition' => [
					'repeater_media' => 'icon',
				],
				'default' => '#000',
			]
		);
		
		$repeater->add_control(
			'repeater_timeline_item_icon_bgcolor',
			[
				'label' => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-timeline-media' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'repeater_media' => 'icon',
				],
				'default' => '#FFF',
			]
		);
		
		$repeater->add_responsive_control(
			'repeater_timeline_item_icon_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 600,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-timeline-media i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-timeline-media svg' => 'width: {{SIZE}}{{UNIT}};',
					
				],
				'condition' => [
					'repeater_media' => 'icon',
				]
			]
		);
		
		$repeater->add_responsive_control(
			'repeater_timeline_item_icon_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-timeline-media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'repeater_media' => 'icon'
				],
			]
		);

		$repeater->add_responsive_control(
			'repeater_timeline_item_icon_alignment',
			[
				'label' => esc_html__( 'Align', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
                'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-timeline-media i' => 'display: block; text-align: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-timeline-media svg' => 'text-align: {{VALUE}};'
				],
				'condition' => [
					'repeater_media' => 'icon'
				]
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'timeline_repeater_list',
			[
				
				'label' => __( 'Content', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'repeater_story_title' => __( 'Timeline Item 1', 'sastra-essential-addons-for-elementor' ),
						'repeater_description' => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.', 'sastra-essential-addons-for-elementor'),
						'repeater_year'			=> __('2021', 'sastra-essential-addons-for-elementor'),
						'repeater_date_label'   => __('Jan 2021', 'sastra-essential-addons-for-elementor'),
						'repeater_extra_label'  => __('Company Established', 'sastra-essential-addons-for-elementor'),
						'repeater_story_icon' => [
							'value' => 'far fa-flag',
							'library' => 'solid'
						],
						'repeater_show_year_label' => 'yes',
						'repeater_image' =>[
							'url' => Utils::get_placeholder_image_src(),	
							'id' => '',						
						],
						'repeater_youtube_video_url' => '',
						'item_bg_color' => '#E71919',
						'repeater_triangle_bgcolor' => '#E71919',
						'repeater_overlay_bgcolor' => '#0000005E',
						'repeater_story_title_color' => '#FCFCFC',
						'repeater_description_color' => '#ECECEC',
						'repeater_timeline_icon_bg_color' => '',
						'item_content_border_color' => '#E8E8E8',
						'repeater_timeline_icon_color' => '#E8E8E8',
						'repeater_icon_timeline_fill_color' => '#E71919',
						'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
						'repeater_icon_border_color' => '#E8E8E8'
					],
					[
						'repeater_story_title' => __( 'Timeline Item 2', 'sastra-essential-addons-for-elementor' ),
						'repeater_description' => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.', 'sastra-essential-addons-for-elementor'),
						'repeater_year'			=> __('2021', 'sastra-essential-addons-for-elementor'),
						'repeater_date_label'   => __('March 2021', 'sastra-essential-addons-for-elementor'),
						'repeater_extra_label'  => __('New office in California', 'sastra-essential-addons-for-elementor'),
						'repeater_story_icon' => [
							'value' => 'far fa-paper-plane',
							'library' => 'solid'
						],
						'repeater_image' =>[
							'url' => Utils::get_placeholder_image_src(),
							'id' => '',							
						],
						'repeater_youtube_video_url' => '',
						'item_bg_color' => '#ECB824',
						'repeater_triangle_bgcolor' => '#ECB824',
						'repeater_overlay_bgcolor' => '#0000005E',
						'repeater_story_title_color' => '#FCFCFC',
						'repeater_description_color' => '#ECECEC',
						'repeater_timeline_icon_bg_color' => '',
						'item_content_border_color' => '#E8E8E8',
						'repeater_timeline_icon_color' => '#E8E8E8',
						'repeater_icon_timeline_fill_color' => '#ECB824',
						'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
						'repeater_icon_border_color' => '#E8E8E8'	
					],
					[
						'repeater_story_title' => __( 'Timeline Item 3', 'sastra-essential-addons-for-elementor' ),
						'repeater_description' => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.', 'sastra-essential-addons-for-elementor'),
						'repeater_year'			=> __('2022', 'sastra-essential-addons-for-elementor'),
						'repeater_date_label'   => __('April 2022', 'sastra-essential-addons-for-elementor'),
						'repeater_extra_label'  => __('First Product Launch', 'sastra-essential-addons-for-elementor'),
						'repeater_story_icon' => [
							'value' => 'far fa-lightbulb',
							'library' => 'solid'
						],
						'repeater_show_year_label' => 'yes',
						'repeater_image' =>[
							'url' => Utils::get_placeholder_image_src(),
							'id' => '',						
						],
						'repeater_youtube_video_url' => '',
						'item_bg_color' => '#1BE620',
						'repeater_triangle_bgcolor' => '#1BE620',
						'repeater_overlay_bgcolor' => '#0000005E',
						'repeater_story_title_color' => '#FCFCFC',
						'repeater_description_color' => '#FDFDFD',
						'item_content_border_color' => '#E8E8E8',
						'repeater_timeline_icon_bg_color' => '',
						'repeater_timeline_icon_color' => '#E8E8E8',
						'repeater_icon_timeline_fill_color' => '#1BE620',
						'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
						'repeater_icon_border_color' => '#E8E8E8'
					],
					[
						'repeater_story_title' => __( 'Timeline Item 4', 'sastra-essential-addons-for-elementor' ),
						'repeater_description' => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo.', 'sastra-essential-addons-for-elementor'),
						'repeater_year'			=> __('2022', 'sastra-essential-addons-for-elementor'),
						'repeater_date_label'   => __('September 2022', 'sastra-essential-addons-for-elementor'),
						'repeater_extra_label'  => __('Entering Stock Market', 'sastra-essential-addons-for-elementor'),
						'repeater_story_icon' => [
							'value' => 'fas fa-bolt',
							'library' => 'solid'
						],
						'repeater_image' =>[
							'url' => Utils::get_placeholder_image_src(),
							'id' => '',						
						],
						'repeater_youtube_video_url' => '',
						'item_bg_color' => '#D82F8E',
						'repeater_triangle_bgcolor' => '#D82F8E',
						'repeater_overlay_bgcolor' => '#0000005E',
						'repeater_story_title_color' => '#FCFCFC',
						'repeater_description_color' => '#F3F3F3',
						'item_content_border_color' => '#E8E8E8',
						'repeater_timeline_icon_bg_color' => '',
						'repeater_timeline_icon_color' => '#E8E8E8',
						'repeater_icon_timeline_fill_color' => '#D82F8E',
						'repeater_icon_timeline_background_fill_color' => '#FFFFFF',
						'repeater_icon_border_color' => '#E8E8E8'
					],
				],
				'title_field' => '{{{ repeater_story_title }}}',
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'timeline_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-posts-timeline-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->end_controls_section();

		// Get Available Post Types
		$post_types = $this->add_option_query_source();

		// Get Available Taxonomies
		$post_taxonomies = tmpcoder_get_custom_types_of( 'tax', false );


		$this->start_controls_section(
			'pagination_section',
			[
				'label' => __( 'Pagination', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'timeline_content' => 'dynamic',
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left'],
					'show_pagination' => 'yes'
				]
			]
		);
	
		$this->add_control(
			'pagination_type',
			[
				'label' => __( 'Pagination Type', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'load-more',
				'options'=>[
					'load-more' => __('Load More', 'sastra-essential-addons-for-elementor'),
					'infinite-scroll' => __('Infinite Scroll', 'sastra-essential-addons-for-elementor')
				],
				'condition' => [
					'show_pagination' => 'yes',
				]
			]
		);

		$this->add_control(
			'pagination_load_more_text',
			[
				'label' => esc_html__( 'Load More Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__('Load More', 'sastra-essential-addons-for-elementor'),
				'condition' => [
					'pagination_type' => 'load-more',
				]
			]
		);

		$this->add_control(
			'pagination_finish_text',
			[
				'label' => esc_html__( 'Finish Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'No more items.',
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
				]
			]
		);

		$this->add_control(
			'pagination_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'loader-1',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'loader-1' => esc_html__( 'Loader 1', 'sastra-essential-addons-for-elementor' ),
					'loader-2' => esc_html__( 'Loader 2', 'sastra-essential-addons-for-elementor' ),
					'loader-3' => esc_html__( 'Loader 3', 'sastra-essential-addons-for-elementor' ),
					'loader-4' => esc_html__( 'Loader 4', 'sastra-essential-addons-for-elementor' ),
					'loader-5' => esc_html__( 'Loader 5', 'sastra-essential-addons-for-elementor' ),
					'loader-6' => esc_html__( 'Loader 6', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
			]
		);
		
		$this->add_responsive_control(
			'pagination_alignment',
			[
				'label' => esc_html__( 'Align', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-pagination-loading' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'timeline_content' => ['dynamic'],
					'timeline_layout' => ['centered', 'one-sided']
				]
			]
		);
		
		$this->end_controls_section();
		

        $this->start_controls_section(
            'query_section',
            [
                'label' => __('Query', 'sastra-essential-addons-for-elementor'),
				'condition' => [
					'timeline_content' => 'dynamic',
				]
            ]
        );

        $this->add_control(
			'timeline_post_types',
			[
				'label' => esc_html__( 'Post Type', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'label_block' => false,
				'options' => $post_types,
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'posts-timeline', 'timeline_post_types', ['pro-rl'] );

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
					'timeline_post_types!' => [ 'current', 'related' ],
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
					'timeline_post_types' => 'related',
				],
			]
		);
		
		// Manual Selection
		foreach ( $post_types as $slug => $title ) {
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
						'timeline_post_types' => $slug,
						'query_selection' => 'manual',
					],
					'separator' => 'before',
				]
			);
		}

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
					'timeline_post_types!' => [ 'current', 'related' ],
					'query_selection' => 'dynamic',
				],
			]
		);

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
						'timeline_post_types' => $post_type,
						'query_selection' => 'dynamic',
					],
				]
			);
		}

		foreach ( $post_types as $slug => $title ) {
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
						'timeline_content' => 'dynamic',
						'timeline_post_types' => $slug,
						'timeline_post_types!' => [ 'current', 'related' ],
						'query_selection' => 'dynamic',
					],
				]
			);
		}

        $this->add_control_posts_per_page();

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'posts_per_page_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Posts are available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-posts-timeline-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

        $this->add_control(
			'order_posts',
			[
				'label' => esc_html__( 'Order By', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'label_block' => false,
				'options' => [
					'title' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor'),
					'date' => esc_html__( 'Date', 'sastra-essential-addons-for-elementor'),
				],
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);

        $this->add_control(
			'order_direction',
			[
				'label' => esc_html__( 'Order', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'label_block' => false,
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'sastra-essential-addons-for-elementor'),
					'DESC' => esc_html__( 'Descending', 'sastra-essential-addons-for-elementor'),
				],
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);

		$this->add_control(
			'query_not_found_text',
			[
				'label' => esc_html__( 'Not Found Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'No Posts Found!',
				'condition' => []
			]
		);

		$this->add_control(
			'query_exclude_no_images',
			[
				'label' => esc_html__( 'Exclude Items without Thumbnail', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'sastra-essential-addons-for-elementor'),
				'condition' => []
            ]
        );
		
		$this->add_responsive_control(
			'content_alignment_left',
			[
				'label' => esc_html__( 'Content Align', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-story-info' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-left-aligned .tmpcoder-story-info-vertical' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-left-aligned .tmpcoder-title-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-left-aligned .tmpcoder-description' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-left-aligned .tmpcoder-inner-date-label' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .tmpcoder-title-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .tmpcoder-description' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .tmpcoder-inner-date-label' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-title-wrap' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided-left', 'horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Align (Right)', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-story-info' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-story-info-vertical' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-title-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-description' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-inner-date-label' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-title-wrap' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided']
				]
			]
		);
		
		$this->add_control(
			'show_overlay',
			[
				'label' => esc_html__( 'Show Image Overlay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'separator' => 'before',
				'render_type' => 'template',
				'condition' => [
					'content_layout' => 'image-top'
				],
			]
		);

		$this->add_control(
			'show_on_hover',
			[
				'label' => esc_html__( 'Show Items on Hover', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'selectors_dictionary' => [
					'yes' => 'opacity: 0; transform: translateY(-50%); transition: all 0.5s ease',
					'no' => 'visibility: visible;',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-story-info' => '{{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal-timeline .swiper-slide:hover .tmpcoder-story-info' => 'opacity: 1; transform: translateY(0%);'
				],
				'condition' => [
					'timeline_layout' => ['horizontal']
				]
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'title_overlay',
			[
				'label' => esc_html__( 'Title Over Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top',
					'show_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => esc_html__( 'Show Date', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->add_control(
			'date_overlay',
			[
				'label' => esc_html__( 'Date Over Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				// 'render_type' => 'template',
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top',
					'timeline_content' => 'dynamic',
					'show_date' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => esc_html__( 'Show Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					// 'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'description_overlay',
			[
				'label' => esc_html__( 'Description Over Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top',
					'show_description' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'excerpt_count',
			[
				'label' => esc_html__( 'Excerpt Count', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 200,
				'render_type' => 'template',
				'frontend_available' => true,
				'default' => 10,
				'condition' => [
					'timeline_content' => 'dynamic',
					'show_description' => 'yes'
				]
			]
		);


		$this->add_control(
			'show_readmore',
			[
				'label' => esc_html__( 'Show Read More', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'timeline_content' => ['dynamic']
				]
			]
		);

		$this->add_control(
			'readmore_overlay',
			[
				'label' => esc_html__( 'Read More Over Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'show_overlay' => 'yes',
					'show_readmore' => 'yes',
					'content_layout' => 'image-top',
					'timeline_content' => ['dynamic']
				]
			]
		);

		$this->add_responsive_control (
			'readmore_content_alignment_left',
			[
				'label' => esc_html__( 'Align', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-left-aligned .tmpcoder-read-more-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-left-aligned .tmpcoder-read-more-button' => 'text-align: center;',
					'{{WRAPPER}} .swiper-wrapper .tmpcoder-read-more-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .tmpcoder-read-more-button' => 'text-align: center;',
				],
				'condition' => [
					'show_readmore' => 'yes',
					'timeline_content' => ['dynamic'],
					'timeline_layout!' => 'one-sided',
				]
			]
		);

		$this->add_responsive_control (
			'readmore_content_alignment',
			[
				'label' => esc_html__( 'Align (Right)', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-read-more-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-read-more-button' => 'text-align: center;',
				],
				'condition' => [
					'show_readmore' => 'yes',
					'timeline_content' => ['dynamic'],
					'timeline_layout' => ['centered', 'one-sided']
				]
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => esc_html__( 'Read More', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Read More',
				'condition' => [
					'show_readmore' => 'yes',
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'overlay_section',
			[
				'label' => __( 'Overlay', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'content_layout' => 'image-top',
					'show_overlay' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'overlay_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
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
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-story-overlay' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_hegiht',
			[
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
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
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-story-overlay' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'overlay_content_alignment_vertical',
			[
				'label' => esc_html__( 'Content Vertical Align', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-story-overlay' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top'
				]
			]
		);

		$this->add_responsive_control(
			'overlay_content_alignment_horizontal',
			[
				'label' => esc_html__( 'Content Horizontal Align', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-story-overlay p' => 'display: flex; justify-content: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-timeline-story-overlay div' => 'display: flex; justify-content: {{VALUE}};',
				],
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top'
				]
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-animations',
				'default' => 'none',
			]
		);

		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'posts-timeline', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );
		
		$this->add_control(
			'overlay_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-story-overlay' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-animation-wrap:hover .tmpcoder-timeline-story-overlay' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'sastra-essential-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
					'large' => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'large',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => tmpcoder_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->end_controls_section();



		



		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'posts-timeline', [
			'Add Unlimited Custom Timeline Items',
			'Unlimited Slides to Show option',
			'Carousel Autoplay and Autoplay Speed',
			'Pause on Hover',
			'Unlimited Posts Per Page option',
			'Advanced Pagination - Load More Button or Infinite Scroll options',
			'Advanced Entrance Animation Options',
		] );

		$this->start_controls_section(
			'content_styles_section',
			[
				'label' => __( 'Timeline Items', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'story_bgcolor',
			[
				'label' => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-data-wrap' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-story-info' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-story-info' => 'background-color: {{VALUE}}',
				],
				'default' => '#FFF',
			]
		);

		$this->add_control(
			'story_border_color',
			[
				'label' => __( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-story-info' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} .tmpcoder-story-info-vertical' => 'border-color: {{VALUE}} !important;',
				],
				'condition' => [
					'timeline_layout!' => 'centered'
				],
				'default' => '#5729d9',
			]
		);

		$this->add_control(
			'story_border_color_left',
			[
				'label' => __( 'Border Color (Left Aligned)', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-left-aligned .tmpcoder-story-info-vertical' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout' => 'centered',
				],
				'default' => '#5729d9',
			]
		);

		$this->add_control(
			'story_border_color_right',
			[
				'label' => __( 'Border Color (Right Aligned)', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-story-info-vertical' => 'border-color: {{VALUE}} !important;',
				],
				'condition' => [
					'timeline_layout' => 'centered',
				],
				'default' => '#5729d9',
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'timeline_item_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-story-info',
				'fields_options' => [
                    'box_shadow_type' =>
                        [ 
                            'default' =>'yes' 
                        ],
                    'box_shadow' => [
                        'default' =>
                            [
                                'horizontal' => 0,
                                'vertical' => 0,
                                'blur' => 20,
                                'spread' => 1,
                                'color' => 'rgba(0,0,0,0.1)'
                            ]
                    ]
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'timeline_item_shadow_vertical',
				'selector' => '{{WRAPPER}} .tmpcoder-story-info-vertical',
				'fields_options' => [
                    'box_shadow_type' =>
                        [ 
                            'default' =>'yes' 
                        ],
                    'box_shadow' => [
                        'default' =>
                            [
                                'horizontal' => 0,
                                'vertical' => 0,
                                'blur' => 20,
                                'spread' => 1,
                                'color' => 'rgba(0,0,0,0.1)'
                            ]
                    ]
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				]
			]
		);

		$this->add_responsive_control(
			'item_distance_from_line',
			[
				'label' => esc_html__( 'Distance From Line', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],			
				'default' => [
					'size' => 40,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 40,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline-left .tmpcoder-data-wrap' => 'margin-right: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);', 
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline .tmpcoder-data-wrap' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
					
					'{{WRAPPER}} .tmpcoder-centered .tmpcoder-left-aligned .tmpcoder-timeline-entry-inner .tmpcoder-data-wrap' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .tmpcoder-centered .tmpcoder-right-aligned .tmpcoder-timeline-entry-inner .tmpcoder-data-wrap' => 'margin-left: {{SIZE}}px;', //calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px)
					'{{WRAPPER}} .tmpcoder-centered .tmpcoder-one-sided-timeline .tmpcoder-right-aligned .tmpcoder-timeline-entry-inner .tmpcoder-data-wrap' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',

                    '{{WRAPPER}} .tmpcoder-centered .tmpcoder-one-sided-timeline .tmpcoder-extra-label' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
                    '{{WRAPPER}} .tmpcoder-one-sided-wrapper .tmpcoder-one-sided-timeline .tmpcoder-extra-label' => 'margin-left: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
                    '{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline-left .tmpcoder-timeline-entry .tmpcoder-extra-label' => 'margin-right: calc({{main_line_side_distance.SIZE}}px/2 + {{SIZE}}px);',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'item_distance_vertical',
			[
				'label' => esc_html__( 'Vertical Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
                    '{{WRAPPER}} .tmpcoder-timeline-centered .tmpcoder-year-wrap' => 'margin-bottom: {{SIZE}}px;',
                    '{{WRAPPER}} .tmpcoder-timeline-centered .tmpcoder-timeline-entry' => 'margin-bottom: {{SIZE}}px;',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'timeline_item_position',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Item Bottom Distance', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],			
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-story-info' => 'margin-bottom: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal'],
					'equal_height_slides!' => 'auto-height',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'timeline_item_position_equal_heights',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Item Bottom Distance', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-horizontal-timeline .swiper-slide.swiper-slide-line-bottom.auto-height .tmpcoder-story-info' => 'margin-bottom: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important; max-height: calc(100% - {{SIZE}}{{UNIT}} - {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important; height: calc(100% - {{SIZE}}{{UNIT}} - {{swiper_pagination_progressbar_bottom.SIZE}}{{swiper_pagination_progressbar_bottom.UNIT}}) !important;'
				],
				'condition' => [
					'timeline_layout' => 'horizontal',
					'equal_height_slides' => 'auto-height',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_responsive_control(
			'story_info_margin_top',
			[
				'label' => esc_html__( 'Item Top Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-horizontal-bottom-timeline .tmpcoder-story-info' => 'margin-top: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_top.SIZE}}{{swiper_pagination_progressbar_top.UNIT}}) !important; max-height: calc(100% - {{SIZE}}{{UNIT}}) !important;',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom-timeline .swiper-slide.auto-height .tmpcoder-story-info' => 'margin-top: calc({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_top.SIZE}}{{swiper_pagination_progressbar_top.UNIT}}) !important; max-height: calc(100% - {{SIZE}}{{UNIT}}) !important; height: calc(100% - ({{SIZE}}{{UNIT}} + {{swiper_pagination_progressbar_top.SIZE}}{{swiper_pagination_progressbar_top.UNIT}})) !important'
				],
				'separator' => 'before',
				'condition' => [
					'timeline_layout' => ['horizontal-bottom'],
				],
			]
		);

		$this->add_responsive_control(
			'story_padding',
			[
				'label' => esc_html__( 'Item Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-story-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-data-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
				],
			]
		);

		$this->add_responsive_control(
			'story_container_padding',
			[
				'label' => esc_html__( 'Container Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'description' => esc_html__('Apply this option to fix Box Shadow issue.', 'sastra-essential-addons-for-elementor'),
				'size_units' => [ 'px' ],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'left' => 10,
					'bottom' => 10,
					'unit' => 'px'
				],
				'tablet_default' => [
					'top' => 10,
					'right' => 10,
					'left' => 10,
					'bottom' => 10,
					'unit' => 'px',
				],
				'mobile_default' => [
					'top' => 10,
					'right' => 10,
					'left' => 10,
					'bottom' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-vertical' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-wrapper .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'timeline_item_border_type',
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
					'{{WRAPPER}} .tmpcoder-story-info' => 'border-style: {{VALUE}} !important;',
					'{{WRAPPER}} .tmpcoder-story-info' => 'border-style: {{VALUE}} !important;',
					'{{WRAPPER}} .tmpcoder-story-info-vertical' => 'border-style: {{VALUE}} !important;',
				],
				'separator' => 'before',
			]
		);

		
		$this->add_control(
			'timeline_item_border_width',
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
					'{{WRAPPER}} .tmpcoder-story-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .tmpcoder-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .tmpcoder-horizontal-timeline .tmpcoder-story-info:before' => 'top: calc( 100% + {{BOTTOM}}{{UNIT}} ) !important;',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom-timeline .tmpcoder-story-info:before' => 'bottom: calc( 100% + {{TOP}}{{UNIT}} ) !important;',
					'{{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-story-info-vertical.tmpcoder-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
					'{{WRAPPER}} .tmpcoder-left-aligned .tmpcoder-story-info-vertical.tmpcoder-data-wrap:after' => 'left: calc( 100% + {{LEFT}}{{UNIT}} ) !important;'
				],
				'condition' => [
					'timeline_layout!' => 'centered',
					'timeline_item_border_type!' => 'none'
				],
			]
		);

		$this->add_control(
			'timeline_item_border_width_left',
			[
				'label' => esc_html__( 'Border Width (Left Aligned)', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-story-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .tmpcoder-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .tmpcoder-both-sided-timeline .tmpcoder-left-aligned .tmpcoder-data-wrap:after' => 'left: calc( 100% + {{RIGHT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .tmpcoder-both-sided-timeline .tmpcoder-left-aligned .tmpcoder-data-wrap:after' => 'left: calc( 100% + {{RIGHT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .tmpcoder-both-sided-timeline .tmpcoder-left-aligned .tmpcoder-data-wrap:after' => 'right: calc( 103% + {{LEFT}}{{UNIT}} ) !important; left: auto !important',
				],
				'condition' => [
					'timeline_layout' => 'centered',
					'timeline_item_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'timeline_item_border_width_right',
			[
				'label' => esc_html__( 'Border Width (Right Aligned)', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .tmpcoder-right-aligned .tmpcoder-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
				],
				'condition' => [
					'timeline_layout' => 'centered',
					'timeline_item_border_type!' => 'none'
				]
			]
		);
		
		$this->add_control(
			'story_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-story-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .tmpcoder-story-info-vertical' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'media_style_section',
			[
				'label' => __( 'Media', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Image Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-media' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'media_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-media' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'media_item_border_type',
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
					'{{WRAPPER}} .tmpcoder-timeline-media' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'media_item_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-media' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'media_item_border_type!' => 'none'
				]
				// 'render_type' => 'template'
			]
		);

		$this->add_control(
			'media_item_radius',
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
					'{{WRAPPER}} .tmpcoder-timeline-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'media_item_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_style_section',
			[
				'label' => __( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [],
			]
		);

		$this->add_control(
			'item_content_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-content-wrapper' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_content_border_type',
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
					'{{WRAPPER}} .tmpcoder-timeline-content-wrapper' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'item_content_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-content-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'item_content_border_type!' => 'none'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'item_content_border_radius',
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
					'{{WRAPPER}} .tmpcoder-timeline-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
				],
			]
		);

		$this->add_responsive_control(
			'content_item_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'overlay_style_section',
			[
				'label' => __( 'Overlay', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_layout' => 'image-top',
					'show_overlay' => 'yes'
				],
			]
		);

		$this->add_control(
			'overlay_bgcolor',
			[
				'label' => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-timeline-story-overlay' => 'background-color: {{VALUE}}',
				],
				'default' => '#0000005E',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .tmpcoder-timeline-story-overlay',
			]
		);
		
		$this->add_control(
			'overlay_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-story-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'timeline_overlay_padding',
			[
				'label' => esc_html__( 'Overlay Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'separator' => 'before',
				'default' => [
					'top' => 25,
					'right' => 25,
					'bottom' => 25,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-story-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' =>[
					'show_overlay' => 'yes',
					'content_layout' => 'image-top',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_styles_section',
			[
				'label' => __( 'Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
		);
		
		/*---- Story Title ----*/
		$this->add_control(
			'story_title_color',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-title' => 'color: {{VALUE}}',
				],
				'default' => '#444444',
			]
		);

		$this->add_control(
			'story_title_bg_color',
			[
				'label' => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-title-wrap' => 'background-color: {{VALUE}} !important',
				],
				'default' => '#FFFFFF00',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-title',
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-title-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-inner-date-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-inner-date-label' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'label' => __('Typography', 'sastra-essential-addons-for-elementor'),
				'selector' => '{{WRAPPER}} .tmpcoder-inner-date-label',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '300',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'date_border_type',
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
					'{{WRAPPER}} .tmpcoder-inner-date-label' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_border_width',
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
					'{{WRAPPER}} .tmpcoder-inner-date-label' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'date_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'date_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-inner-date-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
					
		$this->start_controls_section(
			'description_styles_section',
			[
				'label' => __( 'Description', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-description' => 'color: {{VALUE}}',
				],
				'default' => '#808080',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography_description',
				'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-description',
			]
		);

        $this->add_control(
			'timeline_list_types',
			[
				'label' => esc_html__( 'List Style', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'label_block' => false,
				'description' => __('Apply this option for WYSIWYG lists', 'sastra-essential-addons-for-elementor'),
				'options' => [
					'none' => esc_html__('None', 'sastra-essential-addons-for-elementor'),
					'disc' => esc_html__('Disc', 'sastra-essential-addons-for-elementor'),
					'decimal'=> esc_html__('Number', 'sastra-essential-addons-for-elementor')
				],
				'prefix_class' => 'tmpcoder-list-style-',
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
			
		$this->start_controls_section(
			'readmore_styles_section',
			[
				'label' => __( 'Read More', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'timeline_content' => ['dynamic']
				]
			]
		);

		$this->start_controls_tabs(
			'readmore_style_tabs'
		);

		$this->start_controls_tab(
			'readmore_style_normal_tab',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_control(
			'readmore_color',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-read-more-button' => 'color: {{VAlUE}}',
				],
				'default' => '#fff',
			]
		);
		
		$this->add_control(
			'readmore_bg_color',
			[
				'label' => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-read-more-button' => 'background-color: {{VAlUE}}',
				],
				'default' => '#443DD7',
			]
		);

		$this->add_control(
			'readmore_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-read-more-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'read_more_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-read-more-button',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-read-more-button',
			]
		);

		$this->add_control(
			'read_more_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-read-more-button' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'readmore_border_type',
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
					'{{WRAPPER}} .tmpcoder-read-more-button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'readmore_item_border_width',
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
					'{{WRAPPER}} .tmpcoder-read-more-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'readmore_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 6,
					'right' => 13,
					'bottom' => 7,
					'left' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-read-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'readmore_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-read-more-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'readmore_border_radius',
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
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-read-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);
				
		$this->add_control(
			'readmore_color_hover',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-read-more-button:hover' => 'color: {{VAlUE}}',
				],
				'default' => '#ffA',
			]
		);
		
		$this->add_control(
			'readmore_bg_color_hover',
			[
				'label' => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-read-more-button:hover' => 'background-color: {{VAlUE}}',
				],
				'default' => '#433BD5',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
		
		$this->start_controls_section(
			'middle_line_styles_section',
			[
				'label' => __( 'Main Line', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'line_color',
			[
				'label' => __( 'Line Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-line::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-middle-line' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-timeline-centered .tmpcoder-year' => 'border-color: {{VALUE}}',

					'{{WRAPPER}} .tmpcoder-wrapper:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-wrapper:after' => 'background-color: {{VALUE}}',

					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-swiper-pagination.swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-swiper-pagination.swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-prev' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-next' => 'color: {{VALUE}}',
				],
				'default' => '#D6D6D6',
			]
		);
		
		$this->add_control(
			'swiper_progressbar_color',
			[
				'label' => __( 'Progress(Fill) Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-swiper-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-swiper-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				],
				'default' => '#5729d9',
			]
		);

		$this->add_control(
			'timeline_fill_color',
			[
				'label'  => esc_html__( 'Line Fill Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-fill' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .tmpcoder-change-border-color' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} .tmpcoder-vertical:before' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .tmpcoder-vertical:after' => 'background-color: {{VALUE}} !important;',
				],
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				],
			]
		);

		$this->add_control(
			'middle_line_width',
			[
				'label' => esc_html__( 'Line Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 4,
				],
				'selectors' => [
					// '{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-line::before' => 'transform: scaleX({{SIZE}}) !important;',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-middle-line' => 'width: {{SIZE}}px; transform: translate(-50%) !important',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-timeline-fill' => 'width: {{SIZE}}px; transform: translate(-50%)  !important;',
					
					// '{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-one-sided-timeline-left .tmpcoder-line::before' => 'transform: scaleX({{SIZE}}) translateX(50%) !important;',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-one-sided-timeline-left .tmpcoder-middle-line' => 'width: {{SIZE}}px; transform: translate(50%) !important;',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-one-sided-timeline-left .tmpcoder-timeline-fill' => 'width: {{SIZE}}px; transform: translate(50%) !important;',

					// '{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-one-sided-timeline .tmpcoder-line::before' => 'transform: scaleX({{SIZE}}) !important;',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-one-sided-timeline .tmpcoder-middle-line' => 'width: {{SIZE}}px; transform: translate(-50%)  !important;',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-one-sided-timeline .tmpcoder-timeline-fill' => 'width: {{SIZE}}px; transform: translate(-50%) !important;',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'swiper_pagination_progressbar_height',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'default' => 0.7,
				'step' => 0.1,		
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-swiper-pagination.swiper-pagination-progressbar' => 'transform: scaleY({{SIZE}}) translateX(-50%);',
				],
				'separator' => 'before',
				'condition' => [
					'timeline_layout' => ['horizontal-bottom', 'horizontal']
				],
			]
		);

		$this->add_responsive_control(
			'swiper_pagination_progressbar_bottom',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Bottom Distance', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],			
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-swiper-pagination.swiper-pagination-progressbar' => 'top: auto; bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-icon' => 'bottom: calc({{SIZE}}{{UNIT}} + 1px) !important;',
					'{{WRAPPER}} .tmpcoder-button-prev' => 'top: auto; bottom: calc({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .tmpcoder-button-next' => 'top: auto; bottom: calc({{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'timeline_layout' => ['horizontal']
				],
			]
		);

		$this->add_responsive_control(
			'swiper_pagination_progressbar_top',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Top Distance', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],			
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-swiper-pagination.swiper-pagination-progressbar' => 'bottom: auto; top: {{SIZE}}{{UNIT}} !important',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-icon' => 'position: absolute; top: calc({{SIZE}}{{UNIT}} + 1px) !important; left: 50%; transform: translate(-50%, -50%);',
					'{{WRAPPER}} .tmpcoder-button-prev' => 'bottom: auto; top: calc({{SIZE}}{{UNIT}} + 2px);',
					'{{WRAPPER}} .tmpcoder-button-next' => 'bottom: auto; top: calc({{SIZE}}{{UNIT}} + 2px);',
				],
				'condition' => [
					'timeline_layout' => ['horizontal-bottom']
				],
			]
		);

		$this->add_responsive_control(
			'main_line_side_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Side Distance', 'sastra-essential-addons-for-elementor' ),
				'description' => esc_html__('This option for Zig-Zag layout only works on mobile devices.', 'sastra-essential-addons-for-elementor'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 50,
					'unit' => 'px',
				],			
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline .tmpcoder-year-label' => 'left: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline .tmpcoder-middle-line' => 'left: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline .tmpcoder-timeline-fill' => 'left: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline .tmpcoder-icon' => 'left: calc({{SIZE}}px/2);',

					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline-left .tmpcoder-year-label' => 'right: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline-left .tmpcoder-middle-line' => 'right: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline-left .tmpcoder-timeline-fill' => 'right: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline-left .tmpcoder-icon' => 'right: calc({{SIZE}}px/2);',

					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .tmpcoder-both-sided-timeline .tmpcoder-year-label' => 'position: absolute; left: calc({{SIZE}}px/2);',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .tmpcoder-both-sided-timeline .tmpcoder-middle-line' => 'left: calc({{SIZE}}px/2);',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .tmpcoder-both-sided-timeline .tmpcoder-timeline-fill' => 'left: calc({{SIZE}}px/2);',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .tmpcoder-both-sided-timeline .tmpcoder-icon' => 'left: calc({{SIZE}}px/2); transform: translate(-50%, -50%) !important;',
				],
				'render_type' => 'template',
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'year_label_section',
			[
				'label' => __( 'Main Line Label', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'timeline_content' => 'custom'
				]
			]
		);
		
		$this->add_control(
			'year_label_color',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-year' => 'color: {{VALUE}}',
				],
				'default' => '#222222',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);
		
		$this->add_control(
			'year_label_bgcolor',
			[
				'label' => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-year' => 'background-color: {{VALUE}}',
				],
				'default' => '#fff',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);
		
		$this->add_control(
			'year_label_border_color',
			[
				'label' => __( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-year.tmpcoder-year-label' => 'border-color: {{VALUE}}',
				],
				'default' => '#E0E0E0',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'year_typography',
				'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-year',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);
		
		$this->add_responsive_control(
			'year_label_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 70,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-year-label' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template',
			]
		);
		
		$this->add_responsive_control(
			'year_label_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 41,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-year-label' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-year-wrap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'year_label_border_type',
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
					'{{WRAPPER}} .tmpcoder-year-label' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'year_label_border_size',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-year-label' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'year_label_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'year_label_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-year-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'icon_styles_section',
			[
				'label' => __( 'Main Line Icon', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-icon svg' => 'fill: {{VALUE}};',
				],
				'default' => '#666666',
			]
		);

		$this->add_control(
			'icon_timeline_fill_color',
			[
				'label'  => esc_html__( 'Fill Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-change-border-color.tmpcoder-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-change-border-color.tmpcoder-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				],
			]
		);

		$this->add_control(
			'icon_bgcolor',
			[
				'label' => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-icon' => 'background-color: {{VALUE}}',
				],
				'default' => '#FFFFFF',
			]
		);

		$this->add_control(
			'icon_timeline_background_fill_color',
			[
				'label'  => esc_html__( 'Background Fill Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-change-border-color.tmpcoder-icon' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				],
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EAEAEA',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-icon' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 17,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-icon i' => 'font-size: {{SIZE}}{{UNIT}} !important',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_bg_size',
			[
				'label' => esc_html__( 'Background Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-icon i' => 'display: block;',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; display: flex !important; justify-content: center !important; align-items: center !important;',
				],
				'render_type' => 'template',
			]
		);
		
		$this->add_control(
			'icon_border_type',
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
					'{{WRAPPER}} .tmpcoder-icon' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'icon_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit' => '%'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		
		$this->end_controls_section();	
			
		$this->start_controls_section(
			'label_styles_section',
			[
				'label' => __( 'Extra Label', 'sastra-essential-addons-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_extra_label' => 'yes',
				]
			]
		);

		$this->add_control(
			'extra_label_bg_color_dynamic',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-extra-label' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'label_bg_size',
			[
				'label' => esc_html__( 'Background Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 180,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-extra-label' => 'width: {{SIZE}}{{UNIT}}; height: auto;',

				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'label_right',
			[
				'label' => __( 'Label Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-both-sided-timeline .tmpcoder-timeline-entry.tmpcoder-left-aligned .tmpcoder-extra-label' => 'left: calc(100% + {{SIZE}}{{UNIT}})',
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-both-sided-timeline .tmpcoder-timeline-entry.tmpcoder-right-aligned .tmpcoder-extra-label' => 'right: calc(100% + {{SIZE}}{{UNIT}})',
				],
				'condition' => [
					'timeline_layout' => ['centered'],
				]
			]
		);

		$this->add_responsive_control(
			'label_padding',
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
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-extra-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_border_radius',
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
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-extra-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_section',
			[
				'label' => __( 'Primary Label', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_label_color',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper span.tmpcoder-label' => 'color: {{VALUE}}',
				],
				'default' => '#5729d9',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-extra-label span.tmpcoder-label',
			]
		);

		/*---- Secondary Label ----*/
		$this->add_control(
			'secondary_label_section',
			[
				'label' => __( 'Secondary Label', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'timeline_content' => 'custom'
				]
			]
		);

		$this->add_control(
			'secondary_label_color',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper span.tmpcoder-sub-label' => 'color: {{VALUE}}',
				],
				'condition' => [
					'timeline_content' => 'custom'
				],
				'default' => '#7A7A7A',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'secondary_label_typography',
				'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-wrapper span.tmpcoder-sub-label',
				'condition' => [
					'timeline_content' => 'custom'
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'triangle_styles',
			[
				'label' => esc_html__( 'Triangle', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'triangle_bgcolor',
			[
				'label' => __( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-one-sided-timeline .tmpcoder-data-wrap:after' => 'border-right-color: {{icon_bgcolor}}',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-one-sided-timeline-left .tmpcoder-data-wrap:after' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-right-aligned .tmpcoder-data-wrap:after' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-story-info:before' => 'border-top-color: {{VALUE}} !important',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-story-info:before' => 'border-bottom-color: {{VALUE}} !important',
					'{{WRAPPER}} .tmpcoder-wrapper .tmpcoder-left-aligned .tmpcoder-data-wrap:after' => 'border-left-color: {{VALUE}}',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .tmpcoder-wrapper .tmpcoder-both-sided-timeline .tmpcoder-left-aligned .tmpcoder-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
					'{{WRAPPER}} .tmpcoder-centered .tmpcoder-one-sided-timeline .tmpcoder-right-aligned .tmpcoder-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
				],
				'default' => '#FFFFFF',
				// 'separator' => 'after',
			]
		);
		
		$this->add_responsive_control(
			'story_triangle_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 11,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-story-info:before' => 'border-width: {{size}}{{UNIT}}; top: 100%; left: 50%; transform: translate(-50%);',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-story-info:before' => 'border-width: {{size}}{{UNIT}}; bottom: 100%; left: 50%; transform: translate(-50%);',
					'{{WRAPPER}} .tmpcoder-one-sided-timeline .tmpcoder-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{triangle_onesided_position_top.SIZE}}%; transform: translateY(-50%);',
					'{{WRAPPER}} .tmpcoder-one-sided-timeline-left .tmpcoder-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{triangle_onesided_position_top.SIZE}}%; transform: translateY(-50%);',
					'{{WRAPPER}} .tmpcoder-both-sided-timeline .tmpcoder-right-aligned .tmpcoder-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{arrow_bothsided_position_top.SIZE}}{{arrow_bothsided_position_top.UNIT}}; transform: translateY(-50%);',
					'{{WRAPPER}} .tmpcoder-both-sided-timeline .tmpcoder-left-aligned .tmpcoder-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{arrow_bothsided_position_top.SIZE}}{{arrow_bothsided_position_top.UNIT}}; transform: translateY(-50%);',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'triangle_onesided_position_top',
			[
				'label' => __( 'Position Top', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 30,
				],
				'selectors' => [

					'{{WRAPPER}} .tmpcoder-one-sided-timeline .tmpcoder-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
					'{{WRAPPER}} .tmpcoder-one-sided-timeline-left .tmpcoder-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
					'{{WRAPPER}} .tmpcoder-one-sided-timeline .tmpcoder-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(-50%, -50%) !important;',
					'{{WRAPPER}} .tmpcoder-one-sided-timeline-left .tmpcoder-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(50%,-50%) !important;',
				],
				'condition' => [
					'timeline_layout' => ['one-sided', 'one-sided-left']
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'arrow_bothsided_position_top',
			[
				'label' => __( 'Position Top', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 30,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-timeline-centered .tmpcoder-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-both-sided-timeline .tmpcoder-right-aligned .tmpcoder-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(50%, -50%) !important;',
					'{{WRAPPER}} .tmpcoder-timeline-centered.tmpcoder-one-sided-timeline  .tmpcoder-right-aligned .tmpcoder-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(-50%, -50%) !important;',
					'{{WRAPPER}} .tmpcoder-timeline-centered  .tmpcoder-left-aligned .tmpcoder-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(-50%, -50%) !important;',
					'{{WRAPPER}} .tmpcoder-timeline-centered .tmpcoder-extra-label' => 'top: {{size}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-centered .tmpcoder-one-sided-timeline .tmpcoder-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;', 
				],
				'condition' => [
					'timeline_layout' => ['centered']
				],
				'render_type' => 'template'
			]
		);

		$this->end_controls_section();

			$this->start_controls_section(
				'navigation_button_styles',
			[
				'label' => esc_html__( 'Navigation', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->start_controls_tabs(
			'navigation_style_tabs'
		);

		$this->start_controls_tab(
			'navigation_style_normal_tab',
			[
				'label' => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_control(
			'navigation_button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-next' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'navigation_button_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-prev i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-next i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-button-prev svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
					'{{WRAPPER}} .tmpcoder-button-next svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'navigation_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-prev' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-button-next' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-button-prev i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-button-next i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-button-prev svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-button-next svg' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'navigation_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-button-next svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-next svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',
					
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'navigation_icon_bg_size',
			[
				'label' => esc_html__( 'Box Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-button-next' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-button-prev' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-next' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-prev' => 'width: {{SIZE}}{{UNIT}}; text-align: center; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-next i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-prev i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-next svg' => ' text-align: center; line-height: 1.5;',
					'{{WRAPPER}} .tmpcoder-horizontal .tmpcoder-button-prev svg' => ' text-align: center; line-height: 1.5;',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-button-next i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-button-prev i' => 'width: {{SIZE}}{{UNIT}}; text-align: center; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-button-next svg' => 'text-align: center; line-height: 1.5;',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom .tmpcoder-button-prev svg' => 'text-align: center; line-height: 1.5;',
					'{{WRAPPER}} .tmpcoder-swiper-pagination.swiper-pagination-progressbar' => 'width: calc(100% - ({{SIZE}}px + 15px)*2);',
					'{{WRAPPER}} .tmpcoder-horizontal-bottom.swiper-container' => 'margin-left: {{SIZE}}px; margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .tmpcoder-horizontal.swiper-container' => 'margin-left: {{SIZE}}px; margin-right: {{SIZE}}px;',
				],
				'render_type' => 'template'
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'navigation_style_hover_tab',
			[
				'label' => __( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_control(
			'navigation_button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-prev:hover' => 'background-color: {{VALUE}}; cursor: pointer;',
					'{{WRAPPER}} .tmpcoder-button-next:hover' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'navigation_button_color_hover',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE1',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-button-prev:hover i' => 'color: {{VALUE}}; cursor: pointer; z-index: 11;',
					'{{WRAPPER}} .tmpcoder-button-next:hover i' => 'color: {{VALUE}}; cursor: pointer; z-index: 11;',
					'{{WRAPPER}} .tmpcoder-button-prev:hover svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
					'{{WRAPPER}} .tmpcoder-button-next:hover svg' => 'fill: {{VALUE}}; cursor: pointer; z-index: 11;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'timeline_content' => 'dynamic',
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_pagination_style' );

		$this->start_controls_tab(
			'tab_grid_pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);	

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'color: {{VALUE}}',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				]
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-pagination-finish' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-pagination a, {{WRAPPER}} .tmpcoder-grid-pagination > div > span',
			]
		);

		$this->add_control(
			'pagination_loader_color',
			[
				'label'  => esc_html__( 'Loader Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-double-bounce .tmpcoder-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-wave .tmpcoder-rect' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-spinner-pulse' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-chasing-dots .tmpcoder-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-three-bounce .tmpcoder-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-fading-circle .tmpcoder-circle:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_pagination_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'pagination_color_hr',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination a:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span:not(.tmpcoder-disabled-arrow):hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#045CB4',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span:not(.tmpcoder-disabled-arrow):hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span:not(.tmpcoder-disabled-arrow):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow_hr',
				'selector' => '{{WRAPPER}} .tmpcoder-grid-pagination a:hover, {{WRAPPER}} .tmpcoder-grid-pagination > div > span:not(.tmpcoder-disabled-arrow):hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pagination_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-pagination svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		;$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'loadmore_typography',
				'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-load-more-btn',
			]
		);

		$this->add_control(
			'pagination_border_type',
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
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_width',
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
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Timeline', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-disabled-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-grid-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-grid-pagination span.tmpcoder-grid-current-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}
	
	public function get_tax_query_args() {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$tax_query = [];

		if ( 'related' === $settings[ 'timeline_post_types' ] ) {

			$current_post_id = get_the_ID();
			if (tmpcoder_is_elementor_editor_mode()) {
				$current_post_id = tmpcoder_get_last_post_id();
			}

			$tax_query = [
				[
					'taxonomy' => $settings['query_tax_selection'],
					'field' => 'term_id',
					'terms' => wp_get_object_terms($current_post_id, $settings['query_tax_selection'], array( 'fields' => 'ids' ) ),
				]
			];
		} else {
			foreach ( get_object_taxonomies($settings[ 'timeline_post_types' ]) as $tax ) {
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

	// for frontend
	public function get_main_query_args() {
		$settings = $this->get_settings();
		$settings_new = $this->get_settings_for_display();
		$settings = array_merge( $settings, $settings_new );
		$author = ! empty( $settings[ 'query_author' ] ) ? implode( ',', $settings[ 'query_author' ] ) : '';

		// Get Paged
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		$posts_per_page =  (!tmpcoder_is_availble() && $settings['posts_per_page'] > 4) ? 4 : (empty($settings['posts_per_page']) ? 4 : $settings['posts_per_page']);

		$ids_array = '';

		$query_exclude__args = isset($settings[ 'query_exclude_'. $settings[ 'timeline_post_types' ] ]) ? $settings[ 'query_exclude_'. $settings[ 'timeline_post_types' ] ] : '' ;

		// if ($settings[ 'query_exclude_'. $settings[ 'timeline_post_types' ] ]) {
		if (isset($query_exclude__args) && (is_array($query_exclude__args) && count($query_exclude__args)) ) {
			
			$slug_args = [
			    'post_type'      => $settings[ 'timeline_post_types' ],
			    'posts_per_page' => -1,
			    'post_name__in'  => !empty($settings[ 'query_exclude_'. $settings[ 'timeline_post_types' ] ]) ? $settings[ 'query_exclude_'. $settings[ 'timeline_post_types' ] ] : '',
			    'fields'         => 'ids' 
			];

			$ids_array = get_posts( $slug_args );
		}

		// Dynamic
		$args = [
			'post_type' => $settings[ 'timeline_post_types' ],
			'tax_query' => $this->get_tax_query_args(),
			'post__not_in' => $ids_array,
			'posts_per_page' =>  $posts_per_page,
			'orderby' => $settings[ 'order_posts' ],
			'order' => $settings['order_direction'],
			'author' => $author,
			'paged' => $paged,
		];

		// Exclude Items without F/Image
		if ( 'yes' === $settings['query_exclude_no_images'] ) {
			$args['meta_key'] = '_thumbnail_id';
		}
		
		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_'. $settings[ 'timeline_post_types' ] ]) ) {
				$post_ids = $settings[ 'query_manual_'. $settings[ 'timeline_post_types' ] ];
			}

			$args = [
				'post_type' => $settings[ 'timeline_post_types' ],
				// 'post__in' => $post_ids,
				'post_name__in' => $post_ids,
				'posts_per_page' => $posts_per_page,
				'orderby' => '',  //  $settings[ 'query_randomize' ],
				'paged' => $paged,
			];
		}

		return $args;
	}

	public function get_max_num_pages( $settings ) {
		$query = new \WP_Query( $this->get_main_query_args() );
		$max_num_pages = intval( ceil( $query->max_num_pages ) );

		// Reset
		wp_reset_postdata();

		// $max_num_pages
		return $max_num_pages;
	}	
	
	public $content_alignment = '';

	public function content_and_animation_alignment($layout, $countItem, $settings) {

		if ( $layout != 'one-sided-left' ) {
			$this->content_alignment = "tmpcoder-right-aligned";
		}

		if ( $layout === 'one-sided-left' ) {
			$this->content_alignment = "tmpcoder-left-aligned"; 
		}
		
		if ( $layout == 'centered' ) {

			if ( $countItem % 2 == 0 ) { 
				$this->content_alignment = "tmpcoder-left-aligned";
			}	
			
			if ( preg_match('/right/i', $settings['timeline_animation']) ) {
				if ( 'tmpcoder-left-aligned' === $this->content_alignment ) {
					$this->animation = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
				} elseif ( 'tmpcoder-right-aligned' === $this->content_alignment  ) {
					$this->animation = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
				}
			}
			if ( preg_match('/left/i', $settings['timeline_animation']) ) {
				if ( 'tmpcoder-left-aligned' === $this->content_alignment ) {
					$this->animation = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
				} elseif ( 'tmpcoder-right-aligned' === $this->content_alignment  ) {
					$this->animation = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
				}
			}
		}

		if ( preg_match('/right/i', $settings['timeline_animation']) ) {
			$this->animation_loadmore_left = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
			$this->animation_loadmore_right = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
		} elseif ( preg_match('/left/i', $settings['timeline_animation']) ) {
			$this->animation_loadmore_left = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
			$this->animation_loadmore_right = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
		}
	}

    public function add_custom_horizontal_timeline_attributes($content, $settings, $index) {

			$this->timeline_description = $content['repeater_description'];
			$this->story_date_label = esc_html($content['repeater_date_label']);
			$this->story_extra_label = esc_html($content['repeater_extra_label']);
			$this->timeline_story_title = wp_kses_post($content['repeater_story_title']);
			$this->thumbnail_size = $content['tmpcoder_thumbnail_size'];
			$this->thumbnail_custom_dimension = $content['tmpcoder_thumbnail_custom_dimension'];
		              
			$this->show_year_label = esc_html($content['repeater_show_year_label']);
			$this->timeline_year = esc_html($content['repeater_year']);

			$this->title_key = $this->get_repeater_setting_key( 'repeater_story_title', 'timeline_repeater_list', $index );
			$this->year_key = $this->get_repeater_setting_key( 'repeater_year', 'timeline_repeater_list', $index );
			$this->date_label_key = $this->get_repeater_setting_key( 'repeater_date_label', 'timeline_repeater_list', $index );
			$this->extra_label_key = $this->get_repeater_setting_key( 'repeater_extra_label', 'timeline_repeater_list', $index );
			$this->description_key = $this->get_repeater_setting_key( 'repeater_description', 'timeline_repeater_list', $index );

			$this->background_image = $settings['content_layout'] === 'background' ? $content['repeater_image']['url'] : '';
			$this->background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';
			
			$this->add_inline_editing_attributes( $this->title_key, 'none' );
			$this->add_inline_editing_attributes( $this->year_key, 'none' );
			$this->add_inline_editing_attributes( $this->date_label_key, 'none' );
			$this->add_inline_editing_attributes( $this->extra_label_key, 'none' );
			$this->add_inline_editing_attributes( $this->description_key, 'advanced' );

			$this->add_render_attribute( $this->title_key, ['class'=> 'tmpcoder-title']);
			$this->add_render_attribute( $this->year_key, ['class'=> 'tmpcoder-year-label tmpcoder-year']);
			$this->add_render_attribute( $this->date_label_key, ['class'=> 'tmpcoder-label']);
			$this->add_render_attribute( $this->extra_label_key, ['class'=> 'tmpcoder-sub-label']);
			$this->add_render_attribute( $this->description_key, ['class'=> 'tmpcoder-description']);
                        
    }

	public function render_image_or_icon($content) {					
        if( ( isset($content['repeater_image']['id']) && $content['repeater_image']['id'] != "" ) ) {
            if($this->thumbnail_size == 'custom'){
                $custom_size =   [$this->thumbnail_custom_dimension['width'], $this->thumbnail_custom_dimension['height'] ];
                $this->image= wp_get_attachment_image($content['repeater_image']['id'], $custom_size , true);	
                
            }
            else{
                $this->image= wp_get_attachment_image($content['repeater_image']['id'], $this->thumbnail_size, true);                
            }
        } elseif (isset($content['repeater_image']['url']) && $content['repeater_image']['url'] != "") {

            $this->image = '<img src="'. esc_url($content['repeater_image']['url']) .'">';

        } elseif ($content['repeater_timeline_item_icon'] != '') {
            ob_start();
            \Elementor\Icons_Manager::render_icon( $content['repeater_timeline_item_icon'], [ 'aria-hidden' => 'true' ] );
            $icon_image = ob_get_clean();
            $this->image = $icon_image;
        }  else {
            $this->image ='';
        }
	}
	
	public function tmpcoder_render_swiper_navigation($settings) {
		echo '</div>
			<!-- Add Pagination -->        
			<div class="tmpcoder-swiper-pagination"></div>
			<!-- Add Arrows -->
			<div class="tmpcoder-button-prev tmpcoder-timeline-prev-arrow tmpcoder-timeline-prev-'. esc_attr($this->get_id()) .'">
				'. wp_kses_post(tmpcoder_get_icon( $settings['swiper_nav_icon'], '' )) .'
			</div>
			<div class="tmpcoder-button-next tmpcoder-timeline-next-arrow tmpcoder-timeline-next-'. esc_attr($this->get_id()) .'">
				'. wp_kses_post(tmpcoder_get_icon( $settings['swiper_nav_icon'], '' )) .'
			</div>
		</div>'; 
	}

	public function render_pagination($settings, $paged) {}

	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' tmpcoder-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' tmpcoder-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' tmpcoder-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' tmpcoder-anim-transparency';
			}
		}

		return $class;
	}

	public static function youtube_url ( $story_settings ) {
		if ( $story_settings['repeater_youtube_video_url'] != '' ) {
                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $story_settings['repeater_youtube_video_url'], $matches);
              
                if ( isset($matches[1]) ) {
                    $id = $matches[1];
                    $media = '<iframe width="100%" height="auto"
                    src="https://www.youtube.com/embed/'. esc_attr($id) .'" 
                    frameborder="0" allowfullscreen></iframe>';
                }
            } elseif ( empty($story_settings['repeater_youtube_video_url']) ) {
				$media = '';
			} else {
                $media = __("Wrong URL", 'sastra-essential-addons-for-elementor');
            }
			return $media;
	}

	public function horizontal_timeline_classes($settings) {
		
		$this->slidesToShow = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : 2;

		if ( ! tmpcoder_is_availble() && $this->slidesToShow > 4 ) {
			$this->slidesToShow = 4;
		}

		if ( $settings['timeline_layout'] == 'horizontal' ) {
			$horizontal_class = 'tmpcoder-horizontal-wrapper';
		} elseif ( $settings['timeline_layout'] == 'horizontal-bottom' ) {
			$horizontal_class = 'tmpcoder-horizontal-bottom-wrapper';
		}

		$this->horizontal_inner_class = $horizontal_class == 'tmpcoder-horizontal-wrapper' ? 'tmpcoder-horizontal' : 'tmpcoder-horizontal-bottom';

		$this->horizontal_timeline_class = $this->horizontal_inner_class == 'tmpcoder-horizontal' ? 'tmpcoder-horizontal-timeline' : 'tmpcoder-horizontal-bottom-timeline';

		$this->swiper_class = $this->horizontal_timeline_class === 'tmpcoder-horizontal-timeline' ? 'swiper-slide-line-bottom' : 'swiper-slide-line-top';

	}

	public function render_custom_vertical_timeline($layout, $settings, $data, $countItem ) {
		echo '
		<div class="tmpcoder-wrapper tmpcoder-vertical '. esc_attr($this->timeline_layout_wrapper) .'">
			<div class="tmpcoder-timeline-centered tmpcoder-line '. esc_attr($this->timeline_layout) .'">';
			echo '<div class="tmpcoder-middle-line"></div>';
			echo 'yes' === $this->timeline_fill ? '<div class="tmpcoder-timeline-fill" data-layout="'. esc_attr($layout) .'"></div>' : '';
			
			foreach ( $data as $index => $content ) {
				if ( ! tmpcoder_is_availble() && $index === 4 ) {
					break;
				}

				$repeater_title_link = isset($content['repeater_title_link']) && !empty($content['repeater_title_link']['url']) ? $content['repeater_title_link'] : '';

				if ( !empty( $content['repeater_title_link']['url'] ) ) {
					$this->add_link_attributes( 'repeater_title_link'. $this->item_url_count, $repeater_title_link );
				}

				$this->content_and_animation_alignment($layout, $countItem, $settings);
				
				$this->thumbnail_size = $content['tmpcoder_thumbnail_size'];
				$this->thumbnail_custom_dimension = $content['tmpcoder_thumbnail_custom_dimension'];

				$this->show_year_label = esc_html($content['repeater_show_year_label']);
				$this->timeline_year = esc_html($content['repeater_year']);

				$this->render_image_or_icon($content);

				$background_image = $settings['content_layout'] === 'background' ? $content['repeater_image']['url'] : '';
				$background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';

				if ( $content['repeater_show_year_label'] == 'yes' ) {
					echo '<span class="tmpcoder-year-wrap">';
						echo '<span class="tmpcoder-year-label tmpcoder-year">'. esc_html($content['repeater_year']) .'</span>';
					echo '</span>';
				}
				
				echo '<article class="tmpcoder-timeline-entry '. esc_attr($this->content_alignment) .' elementor-repeater-item-'. esc_attr($content['_id']) .'" data-item-id="elementor-repeater-item-'. esc_attr($content['_id']) .'">';
                    
                    if ( 'yes' === $content['repeater_show_extra_label'] ) {
                        echo !empty($content['repeater_date_label']) || !empty($content['repeater_extra_label']) ? '<time class="tmpcoder-extra-label" data-aos="'. esc_attr($this->animation) .'" data-aos-left="'. esc_attr($this->animation_loadmore_left) .'" data-aos-right="'. esc_attr($this->animation_loadmore_right) .'" data-animation-offset="'. esc_attr($settings['animation_offset']) .'" data-animation-duration="'. esc_attr($settings['aos_animation_duration']) .'">' : '';
                            echo !empty($content['repeater_date_label']) ? '<span class="tmpcoder-label">'. esc_html($content['repeater_date_label']) .'</span>' : '';
                            echo !empty($content['repeater_extra_label']) ? '<span class="tmpcoder-sub-label">'. esc_html($content['repeater_extra_label']) .'</span>' : '';
                        echo !empty($content['repeater_date_label']) || !empty($content['repeater_extra_label']) ? '</time>' : '';
                    }

					echo '<div class="tmpcoder-timeline-entry-inner">';

						echo '<div class="tmpcoder-main-line-icon tmpcoder-icon">';
							\Elementor\Icons_Manager::render_icon( $content['repeater_story_icon'], [ 'aria-hidden' => 'true' ] );
						echo '</div>';

						echo '<div class="tmpcoder-story-info-vertical tmpcoder-data-wrap '. esc_attr($background_class) .'"  data-aos="'. esc_attr($this->animation) .'" data-aos-left="'. esc_attr($this->animation_loadmore_left) .'" data-aos-right="'. esc_attr($this->animation_loadmore_right) .'" data-animation-offset="'. esc_attr($settings['animation_offset']) .'" data-animation-duration="'. esc_attr($settings['aos_animation_duration']) .'">';

							echo ($settings['content_layout'] === 'image-top' && !empty($this->image)) || ($settings['content_layout'] === 'image-top' && $content['repeater_youtube_video_url']) ? '<div class="tmpcoder-animation-wrap tmpcoder-timeline-media">'. wp_kses_post($this->image) : '';

							echo wp_kses((!empty($content['repeater_youtube_video_url']) && $settings['content_layout'] === 'image-top' ? '<div class="tmpcoder-timeline-iframe-wrapper"> '. $this->youtube_url($content) .' </div>' : ''), array(
                                'div'=> array(
                                    'class' => array(),
                                ),
                                'iframe' => array(
                                    'width' => array(),
                                    'height' => array(),
                                    'src' => array(),
                                    'frameborder' => array(),
                                    'allowfullscreen' => array(),
                                )
                            ));

								echo ($settings['show_overlay'] === 'yes' && !empty($this->image)) || ($settings['show_overlay'] === 'yes' && !empty($content['repeater_youtube_video_url'])) ? '<div class="tmpcoder-timeline-story-overlay '. esc_attr($this->animation_class) .'">' : '';


									if ( '' !== $repeater_title_link ) {
										echo !empty($content['repeater_story_title']) && 'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay'] ? wp_kses_post('<p class="tmpcoder-title-wrap"><a '. $this->get_render_attribute_string( 'repeater_title_link'. $this->item_url_count ) .' class="tmpcoder-title">'. esc_html($content['repeater_story_title']) .'</a></p>') : '';
									} else {
										echo !empty($content['repeater_story_title']) && 'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay'] ? '<p class="tmpcoder-title-wrap"><span class="tmpcoder-title">'. esc_html($content['repeater_story_title']) .'</span></p>' : '';
									}
									echo !empty($content['repeater_description']) && 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay'] ? '<div class="tmpcoder-description">'. wp_kses_post($content['repeater_description']) .'</div>' : '';

								echo ($settings['show_overlay'] === 'yes' && !empty($this->image)) || ($settings['show_overlay'] === 'yes' && !empty($content['repeater_youtube_video_url']))  ? '</div>' : '';

							echo ($settings['content_layout'] === 'image-top' && !empty($content['repeater_youtube_video_url'])) || ($settings['content_layout'] === 'image-top' && !empty($this->image)) || $settings['show_overlay'] === 'yes' ? '</div>' : '';

							echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] && !empty($content['repeater_story_title'])  || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] && !empty($content['repeater_description']) ?'<div class="tmpcoder-timeline-content-wrapper">' : '';
								echo  '<div class="tmpcoder-content-wrapper">'; //remove

								if ( '' !== $repeater_title_link ) {
									echo !empty($content['repeater_story_title']) && 'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] ? wp_kses_post('<p class="tmpcoder-title-wrap"><a '. $this->get_render_attribute_string( 'repeater_title_link'. $this->item_url_count ) .' class="tmpcoder-title">'. esc_html($content['repeater_story_title']) .'</a></p>') : '';
								} else {
									echo !empty($content['repeater_story_title']) && 'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] ? '<p class="tmpcoder-title-wrap"><span class="tmpcoder-title">'. esc_html($content['repeater_story_title']) .'</span></p>' : '';
								}

								echo !empty($content['repeater_description']) && 'yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay'] ? '<div class="tmpcoder-description">'. wp_kses_post($content['repeater_description']) .'</div>' : '';

								echo '</div>'; //remove

							echo wp_kses((!empty( $content['repeater_youtube_video_url'] ) && $settings['content_layout'] !== 'image-top' ? '<div class="tmpcoder-timeline-iframe-wrapper"> '. $this->youtube_url($content) .' </div>' : ''), array(
                                'div'=> array(
                                    'class' => array(),
                                ),
                                'iframe' => array(
                                    'width' => array(),
                                    'height' => array(),
                                    'src' => array(),
                                    'frameborder' => array(),
                                    'allowfullscreen' => array(),
                                )
                            ));

							echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] && !empty($content['repeater_story_title'])  || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] && !empty($content['repeater_description']) ? '</div>' : '';	

							echo ($settings['content_layout'] === 'image-bottom' && !empty($this->image)) ? '<div class="tmpcoder-animation-wrap tmpcoder-timeline-media">'. wp_kses_post($this->image) .'</div>' : '';
						echo '</div>
						</div>
				</article>';
						
				$countItem = $countItem +1;
				$this->item_url_count++;
				
			}
			echo'</div>    
			</div>';

	} // end of render_custom_vertical_timeline

	public function render_dynamic_vertical_timeline($settings, $arrow_bgcolor, $layout, $countItem, $paged ) {
				$layout_settings = [
					'pagination_type' => $settings['pagination_type'],
				];

				$this->add_render_attribute( 'grid-settings', [
					'data-settings' => wp_json_encode( $layout_settings ),
				] );

				wp_reset_postdata();
				
				if(!$this->my_query->have_posts()) {
					echo '<div> '. esc_html($settings['query_not_found_text']) .'</div>';
				}

				if ( $this->my_query->have_posts() ) { 
					echo '<div class="tmpcoder-wrapper tmpcoder-vertical '. esc_attr($this->timeline_layout_wrapper) .'">';
					echo '<div class="tmpcoder-timeline-centered tmpcoder-line '. esc_attr($this->timeline_layout) .'"  data-pagination="'. esc_attr($this->pagination_type) .'" data-max-pages="'. esc_attr($this->pagination_max_pages) .'" data-arrow-bgcolor="'. esc_attr($arrow_bgcolor) .'">';
					echo '<div class="tmpcoder-middle-line"></div>';
					echo 'yes' === $this->timeline_fill ? '<div class="tmpcoder-timeline-fill" data-layout="'. esc_attr($layout) .'"></div>' : '';

				while ( $this->my_query->have_posts() ) {
					global $wp_query;
					$counter = $wp_query->current_post++;
					$this->my_query->the_post();
					
					$id = get_post_thumbnail_id();
					$this->src = Group_Control_Image_Size::get_attachment_image_src( $id, 'tmpcoder_thumbnail_dynamic', $settings );

					$settings[ 'tmpcoder_thumbnail_dynamic' ] = ['id' => $id];
					$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'tmpcoder_thumbnail_dynamic' );

					$image_original_class = 'wp-image-'.$id;
					$custom_image_class = $image_original_class.' tmpcoder-thumbnail-image';
					$image_html = str_replace($image_original_class, $custom_image_class, $image_html);

					$this->content_and_animation_alignment($layout, $countItem, $settings);
					$background_image = $settings['content_layout'] === 'background' ? get_the_post_thumbnail_url() : '';
					$background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';

					echo '<article class="tmpcoder-timeline-entry '. esc_attr($this->content_alignment) .'" data-counter="'. esc_attr($countItem) .'">';
                        
                        if ( 'yes' === $settings['show_extra_label'] ) {
                            echo '<time class="tmpcoder-extra-label" data-aos="'. esc_attr($this->animation) .'" data-aos-left="'. esc_attr($this->animation_loadmore_left) .'" data-aos-right="'. esc_attr($this->animation_loadmore_right) .'" data-animation-offset="'. esc_attr($settings['animation_offset']) .'" data-animation-duration="'. esc_attr($settings['aos_animation_duration']) .'">
                                <span class="tmpcoder-label">'. esc_html(get_the_date($settings['date_format'])) .'</span>
                            </time>';
                        }

						echo '<div class="tmpcoder-timeline-entry-inner">';

							echo '<div class="tmpcoder-main-line-icon tmpcoder-icon">';
							\Elementor\Icons_Manager::render_icon( $settings['posts_icon'], [ 'aria-hidden' => 'true' ] );
							echo '</div>';
							echo '<div class="tmpcoder-story-info-vertical tmpcoder-data-wrap animated '. esc_attr($background_class) .'" data-aos="'. esc_attr($this->animation) .'" data-aos-left="'. esc_attr($this->animation_loadmore_left) .'" data-aos-right="'. esc_attr($this->animation_loadmore_right) .'" data-animation-offset="'. esc_attr($settings['animation_offset']) .'" data-animation-duration="'. esc_attr($settings['aos_animation_duration']) .'">';

							echo 'image-top' === $settings['content_layout'] && !empty($this->src) || 'yes' === $settings['show_overlay']  && !empty($this->src) ? wp_kses_post('<div class="tmpcoder-animation-wrap tmpcoder-timeline-media">'.$image_html.'') : '';
							
								echo ($settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url())) ? '<div class="tmpcoder-timeline-story-overlay '. esc_attr($this->animation_class) .'">' : '';

									echo  'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay'] ? '<p class="tmpcoder-title-wrap"><a class="tmpcoder-title" href="'. esc_url(get_the_permalink()) .'">'. esc_html(get_the_title()) .'</a></p>' : '';

									echo 'yes' === $settings['show_date'] && 'yes' === $settings['date_overlay'] ? '<div class="tmpcoder-inner-date-label">
										'. esc_html(get_the_date($settings['date_format'])) .'
									</div>' : '';
									
									echo !empty(get_the_content()) && 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay'] ? '<div class="tmpcoder-description">'. esc_html(wp_trim_words(get_the_content(), $settings['excerpt_count'])) .'</div>' : '';
									
									echo 'yes' === $this->show_readmore && 'yes' === $settings['readmore_overlay'] ? '<div class="tmpcoder-read-more-wrap"><a class="tmpcoder-read-more-button" href="'. esc_url(get_the_permalink()) .'">'. esc_html($settings['read_more_text']) .'</a></div>' : '';

								echo ($settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url())) ? '</div>' : '';
									
							echo ($settings['content_layout'] === 'image-top' && !empty($this->src)) || ($settings['show_overlay'] === 'yes' && !empty($this->src)) ? '</div>' : '';

							echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] || 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] || 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay']  ? '<div class="tmpcoder-timeline-content-wrapper">' : '';

									echo  'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] ? '<p class="tmpcoder-title-wrap"><a class="tmpcoder-title"  href="'. esc_url(get_the_permalink()) .'">'. esc_html(get_the_title()) .'</a></p>' : '';

									echo 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] ? '<div class="tmpcoder-inner-date-label">
										'. esc_html(get_the_date($settings['date_format'])) .'
									</div>' : '';

									echo !empty(get_the_content()) && 'yes' === $settings['show_description']  && 'yes' !== $settings['description_overlay'] ? '<div class="tmpcoder-description">'. esc_html(wp_trim_words(get_the_content(), $settings['excerpt_count'])) .'</div>' : '';

									echo 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay'] ? '<div class="tmpcoder-read-more-wrap"><a class="tmpcoder-read-more-button" href="'. esc_url(get_the_permalink()) .'">'. esc_html($settings['read_more_text']) .'</a></div>' : '';

							echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] || 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] || 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay']  ? '</div>' : '';		
								echo ($settings['content_layout'] === 'image-bottom' && !empty($this->src)) ? wp_kses_post('<div class="tmpcoder-animation-wrap tmpcoder-timeline-media">'.$image_html.'</div>') : '';

							echo '</div>';
					echo '</div>';
					echo '</article>';	

					$countItem++;
			}
			
			echo'</div>';  
			echo '</div>';


			// Pagination
			if(!($settings['posts_per_page'] >= wp_count_posts($settings['timeline_post_types'])->publish)) {
				$this->render_pagination($settings, $paged);
			}
		}
	} // end rendern_dynamic_vertical_timeline

	public function render_custom_horizontal_timeline( $settings, $autoplay, $loop, $dir, $data, $slidesHeight, $swiper_speed, $swiper_delay, $swiper_pause_on_hover ) {

		$this->horizontal_timeline_classes($settings);

		echo '<div class="tmpcoder-timeline-outer-container">';
		echo '<div class="tmpcoder-wrapper swiper-container '. esc_attr($this->horizontal_inner_class) .'" dir="'. esc_attr($dir) .'" data-slidestoshow = "'. esc_attr($this->slidesToShow) .'" data-autoplay="'. esc_attr($autoplay) .'" data-loop="'. esc_attr($loop) .'" data-swiper-speed="'. esc_attr($swiper_speed) .'" data-swiper-delay="'. esc_attr($swiper_delay) .'" data-swiper-poh="'. esc_attr($swiper_pause_on_hover) .'" data-swiper-space-between="'. esc_attr($settings['story_info_gutter']) .'">';

		echo '<div class="swiper-wrapper '. esc_attr($this->horizontal_timeline_class) .'">';
			if ( is_array($data) ) {
					foreach( $data as $index => $content ) {
						if ( ! tmpcoder_is_availble() && $index === 4 ) {
							break;
						}

						$repeater_title_link = isset($content['repeater_title_link']) && !empty($content['repeater_title_link']['url']) ? $content['repeater_title_link'] : '';

						if ( ! empty( $content['repeater_title_link']['url'] ) ) {
							$this->add_link_attributes( 'repeater_title_link'. $this->item_url_count, $content['repeater_title_link'] );
						}

						$this->add_custom_horizontal_timeline_attributes($content, $settings, $index);
				
						$this->thumbnail_custom_dimension = $content['tmpcoder_thumbnail_custom_dimension'];

						$this->render_image_or_icon($content);

						echo '<div class="swiper-slide '. esc_attr($this->swiper_class) .' '. esc_attr($slidesHeight) .' elementor-repeater-item-'. esc_attr($content['_id']) .'">';

							if ( 'yes' === $content['repeater_show_extra_label'] ) {
								echo !empty($this->story_date_label) || !empty($this->story_extra_label) ? '<div class="tmpcoder-extra-label" >' : '';
								  echo !empty($this->story_date_label) ? wp_kses_post('<span '. $this->get_render_attribute_string( $this->date_label_key ) .' >'. esc_html($this->story_date_label) .'</span>') : ''; 
								  echo !empty($this->story_extra_label) ? wp_kses_post('<span '. $this->get_render_attribute_string( $this->extra_label_key ) .' >'. esc_html($this->story_extra_label) .'</span>') : '';
								echo !empty($this->story_date_label) || !empty($this->story_extra_label) ? '</div>' : '';
							}

							echo '<div class="tmpcoder-main-line-icon tmpcoder-icon">';
							\Elementor\Icons_Manager::render_icon( $content['repeater_story_icon'], [ 'aria-hidden' => 'true' ] );
							echo'</div>'; 
							echo '<div class="tmpcoder-story-info '. esc_attr($this->background_class) .'">';

								echo !empty($this->image) && 'image-top' === $settings['content_layout'] || !empty($content['repeater_youtube_video_url']) && 'image-top' === $settings['content_layout'] ? '<div class="tmpcoder-animation-wrap tmpcoder-timeline-media">' : '';

									echo 'image-top' === $settings['content_layout'] ? wp_kses_post($this->image) : '';

									echo wp_kses( (!empty( $content['repeater_youtube_video_url'] ) && $settings['content_layout'] == 'image-top' ? '<div class="tmpcoder-timeline-iframe-wrapper">  '. $this->youtube_url($content) .' </div>' : ''), array(
                                        'div'=> array(
                                            'class' => array(),
                                        ),
                                        'iframe' => array(
                                            'width' => array(),
                                            'height' => array(),
                                            'src' => array(),
                                            'frameborder' => array(),
                                            'allowfullscreen' => array(),
                                        )
                                    ));

									echo 'yes' === $settings['show_overlay'] && !empty($this->image) || $settings['show_overlay'] === 'yes' && !empty($content['repeater_youtube_video_url']) ? '<div class="tmpcoder-timeline-story-overlay '. esc_attr($this->animation_class) .'">' : '';

										if ( '' !== $repeater_title_link ) {
											echo !empty($this->timeline_story_title) && 'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay'] ? wp_kses_post('<p class="tmpcoder-title-wrap"><a  '.
											$this->get_render_attribute_string( 'repeater_title_link'. $this->item_url_count ) . $this->get_render_attribute_string( $this->title_key ) .'>'. esc_html($this->timeline_story_title) .'</a></p>') : '';
										} else {
											echo !empty($this->timeline_story_title) && 'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay'] ? wp_kses_post('<p class="tmpcoder-title-wrap"><span  '. $this->get_render_attribute_string( $this->title_key ) .'>'. esc_html($this->timeline_story_title) .'</span></p>') : '';
										}

										echo !empty($this->timeline_description) && 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay'] ? wp_kses_post('<div '. $this->get_render_attribute_string( $this->description_key ) .'>'. wp_kses_post($this->timeline_description) .'</div>') : '';

									echo 'yes' === $settings['show_overlay'] && !empty($this->image) || $settings['show_overlay'] === 'yes' && !empty($content['repeater_youtube_video_url']) ? '</div>' : '';
								
								echo !empty($this->image) && 'image-top' === $settings['content_layout'] || !empty($content['repeater_youtube_video_url']) && 'image-top' === $settings['content_layout'] ? '</div>' : ''; 
									
								echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] && !empty($content['repeater_story_title'])  || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] && !empty($content['repeater_description']) ? '<div class="tmpcoder-timeline-content-wrapper">' : '';

									if ( '' !== $repeater_title_link ) {
										echo !empty($this->timeline_story_title) && 'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] ? wp_kses_post('<p class="tmpcoder-title-wrap"><a '. $this->get_render_attribute_string( $this->title_key ) . $this->get_render_attribute_string( 'repeater_title_link'. $this->item_url_count ) .'>'. esc_html($this->timeline_story_title) .'</a></p>') : '';
									} else {
										echo !empty($this->timeline_story_title) && 'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] ? wp_kses_post('<p class="tmpcoder-title-wrap"><span  '. $this->get_render_attribute_string( $this->title_key ) .'>'. esc_html($this->timeline_story_title) .'</span></p>') : '';
									}

									echo !empty($this->timeline_description ) && 'yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay'] ? wp_kses_post('<div '. $this->get_render_attribute_string( $this->description_key ) .'>'. wp_kses_post($this->timeline_description) .'</div>') : ''; 

									echo wp_kses((!empty( $content['repeater_youtube_video_url'] ) && $settings['content_layout'] !== 'image-top' ? '<div class="tmpcoder-timeline-iframe-wrapper">  '. $this->youtube_url($content) .' </div>' : ''), array(
                                        'div' => array(
                                            'class' => array(),
                                        ),
                                        'iframe' => array(
                                            'width' => array(),
                                            'height' => array(),
                                            'src' => array(),
                                            'frameborder' => array(),
                                            'allowfullscreen' => array(),
                                        )
                                    ));

								echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] && !empty($content['repeater_story_title'])  || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] && !empty($content['repeater_description']) ? '</div>' : '';	 

								echo 'image-bottom' === $settings['content_layout'] && !empty($this->image) ? '<div class="tmpcoder-animation-wrap tmpcoder-timeline-media">'. wp_kses_post($this->image) .'</div>' : '';
							echo '</div>';
						echo '</div>';
							
						$this->item_url_count++;
					}
				} 
				
				$this->tmpcoder_render_swiper_navigation($settings);
				echo '</div>';
	}
	
	public function render_dynamic_horizontal_timeline( $settings, $dir, $autoplay, $loop, $slidesHeight, $swiper_speed, $swiper_delay, $swiper_pause_on_hover ) {
		
		wp_reset_postdata();

		$this->horizontal_timeline_classes($settings);
	
		if(!$this->my_query->have_posts()) {
			echo '<div> '. esc_html($settings['query_not_found_text']) .'</div>';
		}
	
		if( $this->my_query->have_posts() ) { 
		
		echo '<div class="tmpcoder-timeline-outer-container">';
				echo '<div class="tmpcoder-wrapper swiper-container '. esc_attr($this->horizontal_inner_class) .'" dir="'. esc_attr($dir) .'" data-slidestoshow = "'. esc_attr($this->slidesToShow) .'" data-autoplay="'. esc_attr($autoplay) .'"  data-loop="'. esc_attr($loop) .'" data-swiper-speed="'. esc_attr($swiper_speed) .'" data-swiper-delay="'. esc_attr($swiper_delay) .'" data-swiper-poh="'. esc_attr($swiper_pause_on_hover) .'" data-swiper-space-between="'. esc_attr($settings['story_info_gutter']) .'">
					<div class="'. esc_attr($this->horizontal_timeline_class) .' swiper-wrapper">';
					while( $this->my_query->have_posts() ) {
						$this->my_query->the_post();

					$id = get_post_thumbnail_id();
					$this->src = Group_Control_Image_Size::get_attachment_image_src( $id, 'tmpcoder_thumbnail_dynamic', $settings );
						
					$settings[ 'tmpcoder_thumbnail_dynamic' ] = ['id' => $id];
					$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'tmpcoder_thumbnail_dynamic' );

					$image_original_class = 'wp-image-'.$id;
					$custom_image_class = $image_original_class.' tmpcoder-thumbnail-image';
					$image_html = str_replace($image_original_class, $custom_image_class, $image_html);

						$background_image = $settings['content_layout'] === 'background' ? get_the_post_thumbnail_url() : '';
						$background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';
						
					echo '<div class="swiper-slide  '. esc_attr($this->swiper_class) .'  '. esc_attr($slidesHeight) .'">';
						// TODO: apply animation class to other layouts as well
						echo '<div class="tmpcoder-story-info '. esc_attr($background_class) .'">';
						echo ($settings['content_layout'] === 'image-top' && !empty($this->src)) || ($settings['show_overlay'] === 'yes' && !empty($this->src)) ? '<div class="tmpcoder-animation-wrap tmpcoder-timeline-media">' : '';

						echo ($settings['content_layout'] === 'image-top' && !empty($this->src)) ? wp_kses_post($image_html) : '';

						echo ($settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url())) ? '<div class="tmpcoder-timeline-story-overlay '. esc_attr($this->animation_class) .'">' : '';

							echo 'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay'] ? '<p class="tmpcoder-title-wrap" ><a class="tmpcoder-title" href="'. esc_url(get_the_permalink()) .'">'. esc_html(get_the_title()) .'</a></p>' : '';
	
							echo 'yes' === $settings['show_date'] && 'yes' === $settings['date_overlay'] ? '<div class="tmpcoder-inner-date-label">
							'. esc_html(get_the_date($settings['date_format'])) .'
							</div>' : '';
	
							echo !empty(get_the_content()) && 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay'] ? '<div class="tmpcoder-description">'. esc_html(wp_trim_words(get_the_content(), $settings['excerpt_count'])) .'</div>' : '';
							
							echo 'yes' === $this->show_readmore && 'yes' === $settings['readmore_overlay'] ? '<div class="tmpcoder-read-more-wrap"><a class="tmpcoder-read-more-button" href="'. esc_url(get_the_permalink()) .'">'. esc_html($settings['read_more_text']) .'</a></div>' : '';
	
						echo ($settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url())) ? '</div>' : '';
						echo ($settings['content_layout'] === 'image-top' && !empty($this->src)) || ($settings['show_overlay'] === 'yes' && !empty($this->src)) ? '</div>' : '';
						
						echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] || 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] || 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay']  ? '<div class="tmpcoder-timeline-content-wrapper">' : '';
							echo 'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] ? '<p class="tmpcoder-title-wrap"><a class="tmpcoder-title" href="'. esc_url(get_the_permalink()) .'">'. esc_html(get_the_title()) .'</a></p>' : '';
		
							echo 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] ? '<div class="tmpcoder-inner-date-label">
							'. esc_html(get_the_date($settings['date_format'])) .'
							</div>' : '';
		
							echo !empty(get_the_content()) && 'yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay'] ? '<div class="tmpcoder-description">'. esc_html(wp_trim_words(get_the_content(), $settings['excerpt_count'])) .'</div>' : '';
		
							echo 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay'] ? '<div class="tmpcoder-read-more-wrap"><a class="tmpcoder-read-more-button" href="'. esc_url(get_the_permalink()) .'">'. esc_html($settings['read_more_text']) .'</a></div>' : '';

						echo 'yes' !== $settings['title_overlay'] && 'yes' === $settings['show_title'] || 'yes' !== $settings['description_overlay'] && 'yes' === $settings['show_description'] || 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] || 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay'] ? '</div>' : '';
	
						echo ($settings['content_layout'] === 'image-bottom' && !empty($this->src)) ? wp_kses_post('<div class="tmpcoder-animation-wrap tmpcoder-timeline-media">'.$image_html.'</div>') : '';

						echo '</div>';
	
						if ( 'yes' === $settings['show_extra_label'] ) {	
							echo '<div class="tmpcoder-extra-label">
								<span class="tmpcoder-label">
								'. esc_html(get_the_date($settings['date_format'])) .'
								</span>
							</div>';
						}

						echo '<div class="tmpcoder-main-line-icon tmpcoder-icon">';
							\Elementor\Icons_Manager::render_icon( $settings['posts_icon'], [ 'aria-hidden' => 'true' ] );
						echo'</div>'; 
					echo '</div>';
				}
	
				$this->tmpcoder_render_swiper_navigation($settings);
				echo '</div>';
			}
	}

	public function add_option_query_source() {
		$post_types = [];
		$post_types['post'] = esc_html__( 'Posts', 'sastra-essential-addons-for-elementor' );
		$post_types['page'] = esc_html__( 'Pages', 'sastra-essential-addons-for-elementor' );

		$custom_post_types = tmpcoder_get_custom_types_of( 'post', true );
		foreach( $custom_post_types as $slug => $title ) {
			if ( 'product' === $slug || 'e-landing-page' === $slug ) {
				continue;
			}

			if ( !tmpcoder_is_availble() ) {
				// $post_types['pro-'. substr($slug, 0, 2)] = esc_html( $title ) .' (Expert)';
			} else {
				$post_types[$slug] = esc_html( $title );
			}
		}

		// $post_types['current'] = esc_html__( 'Current Query', 'sastra-essential-addons-for-elementor' );
		// $post_types['pro-rl'] = esc_html__( 'Related Query (Pro)', 'sastra-essential-addons-for-elementor' );
		
		return $post_types;
	}

	protected function render() {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		global $paged;
		$paged = 1;
		$this->my_query = 'dynamic' === $settings['timeline_content'] ? new \WP_Query ($this->get_main_query_args()) : '';
		
		$layout = $settings['timeline_layout'];

		$this->animation = $settings['timeline_animation'];
		$this->animation_loadmore_left = '';
		$this->animation_loadmore_right = '';

		$this->timeline_fill = $settings['timeline_fill'];
		$this->show_readmore = !empty($settings['show_readmore']) ? $settings['show_readmore'] : '';

		$data = $settings['timeline_repeater_list'];
		
		$loop = ! tmpcoder_is_availble() && !isset($settings['swiper_loop']) ? '' : $settings['swiper_loop'];
		$autoplay = ! tmpcoder_is_availble() && !isset($settings['swiper_autoplay']) ? '' : $settings['swiper_autoplay'];

		// $this->pause_on_hover = ! tmpcoder_is_availble() && !isset($settings['pause_on_hover']) ? '' : $settings['pause_on_hover'];
		$swiper_delay = ! tmpcoder_is_availble() && !isset($settings['swiper_delay']) ? 0 : $settings['swiper_delay'];
		$swiper_pause_on_hover = ! tmpcoder_is_availble() && !isset($settings['swiper_pause_on_hover']) ? '' : $settings['swiper_pause_on_hover'];
		$swiper_speed = $settings['swiper_speed'];
		$slidesHeight = $settings['equal_height_slides'];

		$this->pagination_type = !empty($settings['pagination_type']) ? $settings['pagination_type'] : '';
		$this->pagination_max_pages = !empty($this->get_max_num_pages( $settings )) ? $this->get_max_num_pages( $settings ) : '';
		$arrow_bgcolor = isset($settings['triangle_bgcolor']) ? $settings['triangle_bgcolor'] : '';

		$animation_settings = [	
			'overlay_animation' => $settings['overlay_animation'], 
			'overlay_animation_size' => $settings['overlay_animation_size'],
			'overlay_animation_timing' => $settings['overlay_animation_timing'],
			'overlay_animation_tr' => $settings['overlay_animation_tr'],
		];

		$this->animation_class = $this->get_animation_class( $animation_settings, 'overlay' );
		
		$isRTL = is_rtl();
		$dir = '';
		if($isRTL){
			$dir = 'rtl';
		}

			if ( 'one-sided' === $layout ){
				$this->timeline_layout = "tmpcoder-one-sided-timeline";
				$this->timeline_layout_wrapper = "tmpcoder-one-sided-wrapper";
			} elseif ( 'centered' === $layout) {
				$this->timeline_layout = 'tmpcoder-both-sided-timeline';
				$this->timeline_layout_wrapper = 'tmpcoder-centered';
			} elseif ( 'one-sided-left' === $layout ) {
				$this->timeline_layout = "tmpcoder-one-sided-timeline-left";
				$this->timeline_layout_wrapper = "tmpcoder-one-sided-wrapper-left";
			} elseif ( 'horizontal' === $layout ) {
				$this->timeline_layout = "tmpcoder-horizontal-timeline";
				$this->timeline_layout_wrapper = "tmpcoder-horizontal-wrapper";
			}

			$countItem = !empty($countItem) ? $countItem : 0;
			$this->item_url_count = 0;

			if ( 'dynamic' === $settings['timeline_content'] && ('horizontal' === $layout || 'horizontal-bottom' === $layout) ) {

					$this->render_dynamic_horizontal_timeline ( $settings, $dir, $autoplay, $loop, $slidesHeight, $swiper_speed, $swiper_delay, $swiper_pause_on_hover );


			} elseif ( 'custom' === $settings['timeline_content'] && ('horizontal' === $layout || 'horizontal-bottom' === $layout) ) {

					$this->render_custom_horizontal_timeline( $settings, $autoplay, $loop, $dir, $data, $slidesHeight,  $swiper_speed, $swiper_delay, $swiper_pause_on_hover );

			} else {
				if( 'dynamic' === $settings['timeline_content'] ) {

					$this->render_dynamic_vertical_timeline($settings, $arrow_bgcolor, $layout, $countItem, $paged );

				} else {

					$this->render_custom_vertical_timeline($layout, $settings, $data, $countItem );

				}
			}
	}
}

new TMPCODER_Posts_Timeline();