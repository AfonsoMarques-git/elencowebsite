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
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Advanced_Slider extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-advanced-slider';
	}

	public function get_title() {
		return esc_html__( 'Advanced Slider/Carousel', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-media-carousel';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category' ];
	}

	public function get_keywords() {
		return [ 'image slider', 'slideshow', 'image carousel', 'template slider', 'posts slider' ];
	}
	
	public function get_script_depends() {
		return [ 'imagesloaded', 'tmpcoder-slick', 'tmpcoder-advanced-slider' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-animations-css', 'tmpcoder-advanced-slider' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }
		
	public function add_control_slider_effect() {
		$this->add_control(
			'slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'sastra-essential-addons-for-elementor' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'sastra-essential-addons-for-elementor' ),
					'sl_vl' => esc_html__( 'Sl Vertical (Pro)', 'sastra-essential-addons-for-elementor' ),
					'fade' => esc_html__( 'Fade', 'sastra-essential-addons-for-elementor' ),
				],
				'separator' => 'before'
			]
		);
	}

	public function add_control_slider_nav_hover() {
		$this->add_control(
			'slider_nav_hover',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show on Hover %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control no-distance'
			]
		);
	}

	public function add_control_slider_dots_layout() {
		$this->add_control(
			'slider_dots_layout',
			[
				'label' => esc_html__( 'Pagination Layout', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'sastra-essential-addons-for-elementor' ),
					'pro-vr' => esc_html__( 'Vertical (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-slider-dots-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_slider_autoplay() {
		$this->add_control(
			'slider_autoplay',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Autoplay %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}

	public function add_control_slider_autoplay_duration() {}

	public function add_control_slider_pause_on_hover() {
		$this->add_control(
			'pause_on_hover',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Pause on Hover %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control no-distance'
			]
		);
	}

	public function add_control_slider_scroll_btn() {
		$this->add_control(
			'slider_scroll_btn',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Scroll to Section Button %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}

	public function add_repeater_args_slider_item_bg_kenburns() {
		return [
			// Translators: %s is the icon.
			'label' => sprintf( __( 'Ken Burn Effect %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
			'conditions' => [
				'terms' => [
					[
						'name' => 'slider_item_bg_image[url]',
						'operator' => '!=',
						'value' => '',
					],
				],
			],
			'classes' => 'tmpcoder-pro-control'
		];
	}

	public function add_repeater_args_slider_item_bg_zoom() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_slider_content_type() {
		return [
            'custom' => esc_html__( 'Custom', 'sastra-essential-addons-for-elementor' ),
            'pro-tm' => esc_html__( 'Elementor Template (Pro)', 'sastra-essential-addons-for-elementor' ),
        ];
	}

	public function add_repeater_args_slider_select_template() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_slider_item_link_type() {
		return [
			'label' => esc_html__( 'Link Type', 'sastra-essential-addons-for-elementor' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
				'pro-cstm' => esc_html__( 'Custom URL (Pro)', 'sastra-essential-addons-for-elementor' ),
				'pro-yt'  => esc_html__( 'Youtube (Pro)', 'sastra-essential-addons-for-elementor' ),
				'pro-vm'  => esc_html__( 'Vimeo (Pro)', 'sastra-essential-addons-for-elementor' ),
				'pro-md'  => esc_html__( 'Custom Video (Pro)', 'sastra-essential-addons-for-elementor' )
			],
			'condition' => [
				'slider_content_type' => 'custom'
			],
			'separator' => 'before'
		];
	}

	public function add_section_style_scroll_btn() {}

	public function add_control_slider_amount() {
		$this->add_responsive_control(
			'slider_amount',
			[
				'label' => esc_html__( 'Columns (Carousel)', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 1,
				'widescreen_default' => 1,
				'laptop_default' => 1,
				'tablet_extra_default' => 1,
				'tablet_default' => 1,
				'mobile_extra_default' => 1,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'sastra-essential-addons-for-elementor' ),
					2 => esc_html__( 'Two', 'sastra-essential-addons-for-elementor' ),
					'pro-3' => esc_html__( 'Three (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-4' => esc_html__( 'Four (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-5' => esc_html__( 'Five (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-6' => esc_html__( 'Six (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-adv-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'slider_effect!' => 'slide_vertical'
				]
			]
		);
	}

	public function add_control_slides_to_scroll() {
		$this->add_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 2,
				'prefix_class' => 'tmpcoder-adv-slides-to-scroll-',
				'render_type' => 'template',
				'frontend_available' => true,
				'default' => 1,
				'condition' => [
					'slider_effect!' => 'slide_vertical'
				]
			]
		);
	}

	public function add_control_stack_slider_nav_position() {}

	public function add_control_slider_dots_hr() {}

	protected function register_controls() {

		// Section: Slides -----------
		$this->start_controls_section(
			'tmpcoder__section_slides',
			[
				'label' => esc_html__( 'Slides', 'sastra-essential-addons-for-elementor' ),
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'posts_slider_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Looking for a <strong>Post Slider or Carousel?</strong>, <ul><li>1. Search for the <strong>"Post Slider"</strong> in widgets</li><li>2. Add <strong>"Posts Grid/Slider/Carousel"</strong></li><li>3. Navigate to <strong>"Layout"</strong> section</li><li>4. Select Layout: <strong>"Slider / Carousel"</strong></li></ul>', 'sastra-essential-addons-for-elementor' ),
				'separator' => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
            'slider_content_type',
            [
                'label' => esc_html__( 'Content Type', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => $this->add_repeater_args_slider_content_type(),
            ]
        );

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_content_type', ['pro-tm'] );

		$repeater->add_control( 'slider_select_template', $this->add_repeater_args_slider_select_template() );

		$repeater->add_control(
			'slider_content_type_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$repeater->start_controls_tabs( 'tabs_slider_item' );

		$repeater->start_controls_tab(
			'tab_slider_item_background',
			[
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$repeater->add_control(
			'slider_item_bg_image',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '',
					'id' => 0,
				],
				'condition' => [
					'slider_content_type' => 'custom'
				],
			]
		);

		$repeater->add_control(
			'slider_item_bg_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => esc_html__( 'Cover', 'sastra-essential-addons-for-elementor' ),
					'contain' => esc_html__( 'Contain', 'sastra-essential-addons-for-elementor' ),
					'auto' => esc_html__( 'Auto', 'sastra-essential-addons-for-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-slider-item-bg' => 'background-size: {{VALUE}}',
				],
			]
		);

		$repeater->add_control( 'slider_item_link_type', $this->add_repeater_args_slider_item_link_type() );

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_item_link_type', ['pro-cstm', 'pro-yt', 'pro-vm', 'pro-md'] );

		$repeater->add_control(
			'vimeo_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => 'Please Upload Background Image',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
					'slider_item_link_type' => 'video-vimeo'
				]
			]
		);

		$repeater->add_control(
			'slider_item_bg_image_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'show_label' => false,
				'condition' => [
					'slider_item_link_type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'hosted_url',
			[
				'label' => esc_html__( 'Choose File', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'media_type' => 'video',
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => 'video-media',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_src',
			[
				'label' => esc_html__( 'Video URL', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_loop',
			[
				'label' => esc_html__( 'Loop', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo','video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_mute',
			[
				'label' => esc_html__( 'Mute', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_controls',
			[
				'label' => esc_html__( 'Controls', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_start',
			[
				'label' => esc_html__( 'Start Time', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'description' => esc_html__( 'Specify a start time (in seconds)', 'sastra-essential-addons-for-elementor' ),
				'frontend_available' => true,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo'],
					'slider_item_video_loop!' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_end',
			[
				'label' => esc_html__( 'End Time', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'description' => esc_html__( 'Specify an end time (in seconds)', 'sastra-essential-addons-for-elementor' ),
				'frontend_available' => true,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => 'video-youtube',
					'slider_item_video_loop!' => 'yes',
				],
			]
		);

		$repeater->add_control( 'slider_item_bg_kenburns', $this->add_repeater_args_slider_item_bg_kenburns() );

		$repeater->add_control( 'slider_item_bg_zoom', $this->add_repeater_args_slider_item_bg_zoom() );

		$repeater->add_control(
			'overlay_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$repeater->add_control(
			'slider_item_overlay',
			[
				'label' => esc_html__( 'Background Overlay', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'slider_content_type' => 'custom'
				]
			]
		);

		$repeater->add_control(
			'slider_item_overlay_bg',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(236,64,122,0.8)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-slider-item-overlay' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'slider_item_overlay' => 'yes',
					'slider_content_type' => 'custom'
				],
			]
		);

		$repeater->add_control(
			'slider_item_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
					'multiply' => esc_html__( 'Multiply', 'sastra-essential-addons-for-elementor' ),
					'screen' => esc_html__( 'Screen', 'sastra-essential-addons-for-elementor' ),
					'overlay' => esc_html__( 'Overlay', 'sastra-essential-addons-for-elementor' ),
					'darken' => esc_html__( 'Darken', 'sastra-essential-addons-for-elementor' ),
					'lighten' => esc_html__( 'Lighten', 'sastra-essential-addons-for-elementor' ),
					'color-dodge' => esc_html__( 'Color-dodge', 'sastra-essential-addons-for-elementor' ),
					'color-burn' => esc_html__( 'Color-burn', 'sastra-essential-addons-for-elementor' ),
					'hard-light' => esc_html__( 'Hard-light', 'sastra-essential-addons-for-elementor' ),
					'soft-light' => esc_html__( 'Soft-light', 'sastra-essential-addons-for-elementor' ),
					'difference' => esc_html__( 'Difference', 'sastra-essential-addons-for-elementor' ),
					'exclusion' => esc_html__( 'Exclusion', 'sastra-essential-addons-for-elementor' ),
					'hue' => esc_html__( 'Hue', 'sastra-essential-addons-for-elementor' ),
					'saturation' => esc_html__( 'Saturation', 'sastra-essential-addons-for-elementor' ),
					'color' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
					'luminosity' => esc_html__( 'luminosity', 'sastra-essential-addons-for-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-slider-item-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
					'slider_item_overlay' => 'yes',
					'slider_content_type' => 'custom'
				],
				'frontend_available' => true
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_slider_item_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$repeater->add_control(
			'slider_show_content',
			[
				'label' => esc_html__( 'Show Sldier Content', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'slider_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'sastra-essential-addons-for-elementor' ),
					'h2' => esc_html__( 'H2', 'sastra-essential-addons-for-elementor' ),
					'h3' => esc_html__( 'H3', 'sastra-essential-addons-for-elementor' ),
					'h4' => esc_html__( 'H4', 'sastra-essential-addons-for-elementor' ),
					'h5' => esc_html__( 'H5', 'sastra-essential-addons-for-elementor' ),
					'h6' => esc_html__( 'H6', 'sastra-essential-addons-for-elementor' ),
					'div' => esc_html__( 'Div', 'sastra-essential-addons-for-elementor' )
				],
				'default' => 'h2',
				'condition' => [
					'slider_show_content' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'slider_item_title',
			[
				'label'  	=> esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type'   	=> Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Slide Title',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_sub_title_tag',
			[
				'label' => esc_html__( 'Sub Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'sastra-essential-addons-for-elementor' ),
					'h2' => esc_html__( 'H2', 'sastra-essential-addons-for-elementor' ),
					'h3' => esc_html__( 'H3', 'sastra-essential-addons-for-elementor' ),
					'h4' => esc_html__( 'H4', 'sastra-essential-addons-for-elementor' ),
					'h5' => esc_html__( 'H5', 'sastra-essential-addons-for-elementor' ),
					'h6' => esc_html__( 'H6', 'sastra-essential-addons-for-elementor' ),
					'div' => esc_html__( 'Div', 'sastra-essential-addons-for-elementor' )
				],
				'default' => 'h3',
				'condition' => [
					'slider_show_content' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'slider_item_sub_title',
			[
				'label'  	=> esc_html__( 'Sub Title', 'sastra-essential-addons-for-elementor' ),
				'type'   	=> Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Slide Sub Title',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_description',
			[
				'label'   	=> esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type'    	=> Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Slider Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_1_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_responsive_control(
			'slider_item_btn_1',
			[
				'label' => esc_html__( 'Button Primary', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'frontend_available' => true,
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-block'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-slider-primary-btn' => 'display:{{VALUE}};',
				],
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_text_1',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button 1',
				'condition' => [
					'slider_item_btn_1' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_icon_1',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'slider_item_btn_1' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_url_1',
			[
				'label' => esc_html__( 'Link', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'slider_item_btn_1' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_2_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_responsive_control(
			'slider_item_btn_2',
			[
				'label' => esc_html__( 'Button Secondary', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'frontend_available' => true,
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-block'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-slider-secondary-btn' => 'display:{{VALUE}};',
				],
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_text_2',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button 2',
				'condition' => [
					'slider_item_btn_2' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_icon_2',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'slider_item_btn_2' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_url_2',
			[
				'label' => esc_html__( 'Link', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'slider_item_btn_2' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'slider_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						
						'slider_item_title' => esc_html__( 'Slide 1 Title', 'sastra-essential-addons-for-elementor' ),
						'slider_item_sub_title' => esc_html__( 'Slide 1 Sub Title', 'sastra-essential-addons-for-elementor' ),
						'slider_item_description' => esc_html__( 'Slider 1 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'sastra-essential-addons-for-elementor' ),
						'slider_item_btn_text_1' => esc_html__( 'Button 1', 'sastra-essential-addons-for-elementor' ),
						'slider_item_btn_text_2' => esc_html__( 'Button 2', 'sastra-essential-addons-for-elementor' ),
						'slider_item_overlay_bg' => '#605BE59C',
					],
					[
						
						'slider_item_title' => esc_html__( 'Slide 2 Title', 'sastra-essential-addons-for-elementor' ),
						'slider_item_sub_title' => esc_html__( 'Slide 2 Sub Title', 'sastra-essential-addons-for-elementor' ),
						'slider_item_description' => esc_html__( 'Slider 2 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'sastra-essential-addons-for-elementor' ),
						'slider_item_btn_text_1' => esc_html__( 'Button 1', 'sastra-essential-addons-for-elementor' ),
						'slider_item_btn_text_2' => esc_html__( 'Button 2', 'sastra-essential-addons-for-elementor' ),
						'slider_item_overlay_bg' => '#AB47BCAB',
					],
					[
						
						'slider_item_title' => esc_html__( 'Slide 3 Title', 'sastra-essential-addons-for-elementor' ),
						'slider_item_sub_title' => esc_html__( 'Slide 3 Sub Title', 'sastra-essential-addons-for-elementor' ),
						'slider_item_description' => esc_html__( 'Slider 3 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'sastra-essential-addons-for-elementor' ),
						'slider_item_btn_text_1' => esc_html__( 'Button 1', 'sastra-essential-addons-for-elementor' ),
						'slider_item_btn_text_2' => esc_html__( 'Button 2', 'sastra-essential-addons-for-elementor' ),
						'slider_item_overlay_bg' => '#EF535094',
					],
				],
				'title_field' => '{{{ slider_item_title }}}',
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'slider_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-advanced-slider-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Section: Slider Options ---
		$this->start_controls_section(
			'tmpcoder__section_slider_options',
			[
				'label' => esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'slider_image_size',
				'default' => 'full',
			]
		);

		$this->add_control(
			'slider_image_type',
			[
				'label' => esc_html__( 'Media Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'background',
				'options' =>  [
					'background' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
					'image' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' )
				]
			]
		);

		$this->add_control(
			'slider_content_overflow',
			[
				'label' => esc_html__( 'Overflow', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'sastra-essential-addons-for-elementor' ),
					'hidden' => esc_html__( 'Hidden', 'sastra-essential-addons-for-elementor' )
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-slider-wrap' => 'overflow: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'slider_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1500,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-slider' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-slider-item' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'slider_image_type' => 'background'
				]
			]
		);

		$this->add_control_slider_amount();

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'slider_columns_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Slider Columns</span> option is fully supported<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-advanced-slider-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->add_control_slides_to_scroll();

		$this->add_control(
			'slides_amount_hidden',
			[
				'type' => Controls_Manager::HIDDEN,
				'prefix_class' => 'tmpcoder-adv-slider-columns-',
				'default' => 1,
				'condition' => [
					'slider_effect' => 'slide_vertical'
				]
			]
		);

		$this->add_control(
			'slides_to_scroll_hidden',
			[
				'type' => Controls_Manager::HIDDEN,
				'prefix_class' => 'tmpcoder-adv-slides-to-scroll-',
				'default' => 1,
				'condition' => [
					'slider_effect' => 'slide_vertical'
				]
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
						'max' => 300,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-advanced-slider .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-advanced-slider .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'slider_amount!' => '1',
				],	
			]
		);

		$this->add_responsive_control(
			'slider_title',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-title' => 'display:{{VALUE}};',
				],
				'separator' => 'before',
                'frontend_available' => true,
			]
		);

		$this->add_control(
			'slider_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'sastra-essential-addons-for-elementor' ),
					'h2' => esc_html__( 'H2', 'sastra-essential-addons-for-elementor' ),
					'h3' => esc_html__( 'H3', 'sastra-essential-addons-for-elementor' ),
					'h4' => esc_html__( 'H4', 'sastra-essential-addons-for-elementor' ),
					'h5' => esc_html__( 'H5', 'sastra-essential-addons-for-elementor' ),
					'h6' => esc_html__( 'H6', 'sastra-essential-addons-for-elementor' ),
					'div' => esc_html__( 'Div', 'sastra-essential-addons-for-elementor' )
				],
				'default' => 'h2',
				'condition' => [
					'slider_title' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'slider_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-sub-title' => 'display:{{VALUE}};',
				],
                'frontend_available' => true,
			]
		);

		$this->add_control(
			'slider_sub_title_tag',
			[
				'label' => esc_html__( 'Sub Title HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'sastra-essential-addons-for-elementor' ),
					'h2' => esc_html__( 'H2', 'sastra-essential-addons-for-elementor' ),
					'h3' => esc_html__( 'H3', 'sastra-essential-addons-for-elementor' ),
					'h4' => esc_html__( 'H4', 'sastra-essential-addons-for-elementor' ),
					'h5' => esc_html__( 'H5', 'sastra-essential-addons-for-elementor' ),
					'h6' => esc_html__( 'H6', 'sastra-essential-addons-for-elementor' ),
					'div' => esc_html__( 'Div', 'sastra-essential-addons-for-elementor' )
				],
				'default' => 'h3',
				'condition' => [
					'slider_sub_title' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'slider_description',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-description' => 'display:{{VALUE}};',
				],
				'separator' => 'after',
                'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow' => 'display:{{VALUE}} !important;',
				],
                'frontend_available' => true,
			]
		);

		$this->add_control_slider_nav_hover();

		$this->add_control(
			'slider_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg-angle-1-left',
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
					'slider_nav' => 'yes',
				],
				'separator' => 'after',
                'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slider_dots',
			[
				'label' => esc_html__( 'Pagination', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-table'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-dots' => 'display:{{VALUE}};',
				],
				'render_type' => 'template',
                'frontend_available' => true,
			]
		);

		$this->add_control_slider_dots_layout();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_dots_layout', ['pro-vr'] );

		$this->add_control_slider_scroll_btn();

		$this->add_control(
			'slider_scroll_btn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-double-down',
					'library' => 'fa-solid',
				],
				'condition' => [
					'slider_scroll_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_scroll_btn_url',
			[
				'label' => esc_html__( 'Button URL', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'slider_scroll_btn' => 'yes',
				],
			]
		);

		$this->add_control_slider_autoplay();

		$this->add_control_slider_autoplay_duration();

		$this->add_control_slider_pause_on_hover();

		$this->add_control_slider_effect();

		$this->add_control(
			'slider_effect_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,	
			]
		);

		$this->add_control(
			'slider_content_animation',
			[
				'label' => esc_html__( 'Content Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-animations-alt',
				'default' => 'none',
				'condition' => [
					'slider_effect' => 'fade',
				],
			]
		);

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_content_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );
		
		$this->add_control(
			'slider_content_anim_size',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation Size', 'sastra-essential-addons-for-elementor' ),
				'default' => 'large',
				'options' => [
					'small' => esc_html__( 'Small', 'sastra-essential-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
					'large' => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'slider_content_animation!' => 'none',
					'slider_effect' => 'fade',
				],
			]
		);

		$this->add_control(
			'slider_content_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-animation .tmpcoder-cv-outer' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'slider_content_animation!' => 'none',
					'slider_effect' => 'fade',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'advanced-slider', [
			__('Add Unlimited Slides', 'sastra-essential-addons-for-elementor'),
			__('Youtube & Vimeo Video Support', 'sastra-essential-addons-for-elementor'),
			__('Custom Video Support', 'sastra-essential-addons-for-elementor'),
			__('Vertical Sliding', 'sastra-essential-addons-for-elementor'),
			__('Elementor Templates Slider option', 'sastra-essential-addons-for-elementor'),
			__('Scroll to Section Button', 'sastra-essential-addons-for-elementor'),
			__('Ken Burn Effect', 'sastra-essential-addons-for-elementor'),
			__('Columns (Carousel) 1,2,3,4,5,6', 'sastra-essential-addons-for-elementor'),
			__('Unlimited Slides to Scroll option', 'sastra-essential-addons-for-elementor'),
			__('Slider/Carousel Autoplay options', 'sastra-essential-addons-for-elementor'),
			__('Advanced Navigation Positioning', 'sastra-essential-addons-for-elementor'),
			__('Advanced Pagination Positioning', 'sastra-essential-addons-for-elementor'),
		] );
		
		// Styles
		// Section: Slider Content ---
		$this->start_controls_section(
			'tmpcoder__section_style_slider_content',
			[
				'label' => esc_html__( 'Slider Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'slider_content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-content' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
            'slider_content_hr',
            [
                'label' => esc_html__( 'Horizontal Position', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'default' => 'center',
				'widescreen_default' => 'center',
				'laptop_default' => 'center',
				'tablet_extra_default' => 'center',
				'tablet_default' => 'center',
				'mobile_extra_default' => 'center',
				'mobile_default' => 'center',
				'selectors_dictionary' => [
					'left' => 'float: left',
					'center' => 'margin: 0 auto; float: unset;',
					'right' => 'float: right'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-content' => '{{VALUE}};',
				],
            ]
        );

		$this->add_responsive_control(
			'slider_content_vr',
			[
				'label' => esc_html__( 'Vertical Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'middle',
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
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-cv-inner' => 'vertical-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}} .tmpcoder-slider-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 100,
					],
					'px' => [
						'min' => 200,
						'max' => 1500,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 750,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Styles
		// Section: Title ------------
		$this->start_controls_section(
			'tmpcoder__section_style_slider_title',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'slider_title_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-title *' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-title *' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slider_title_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-slider-title *',
			]
		);

		$this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 200,
                        'max' => 1500,
                    ],
                ],
                'size_units' => [ '%', 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-title' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'slider_title_padding',
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
					'{{WRAPPER}} .tmpcoder-slider-title *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_title_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-title *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Sub Title ------------
		$this->start_controls_section(
			'tmpcoder__section_style_slider_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'slider_sub_title_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-sub-title *' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_sub_title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-sub-title *' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slider_sub_title_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-slider-sub-title *',
			]
		);

		$this->add_responsive_control(
            'sub_title_width',
            [
                'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 200,
                        'max' => 1500,
                    ],
                ],
                'size_units' => [ '%', 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-sub-title' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'slider_sub_title_padding',
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
					'{{WRAPPER}} .tmpcoder-slider-sub-title *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_sub_title_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-sub-title *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
		// Styles
		// Section: Description ------------
		$this->start_controls_section(
			'tmpcoder__section_style_slider_description',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'slider_description_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,		
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-description p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_description_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-description p' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slider_description_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-slider-description p',
			]
		);

		$this->add_responsive_control(
            'description_width',
            [
                'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 200,
                        'max' => 1500,
                    ],
                ],
                'size_units' => [ '%', 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .tmpcoder-slider-description' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'slider_description_padding',
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
					'{{WRAPPER}} .tmpcoder-slider-description p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_description_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 30,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-description p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// Styles
		// Section: Button Primary ---
		$this->start_controls_section(
			'tmpcoder__section_style_btn_1',
			[
				'label' => esc_html__( 'Button Primary', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style_1' );

		$this->start_controls_tab(
			'tab_btn_normal_1',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color_1',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-slider-primary-btn'
			]
		);

		$this->add_control(
			'btn_color_1',
			[
				'label'     => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-slider-primary-btn svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_1',
			[
				'label'     => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_box_shadow_1',
				'selector' => '{{WRAPPER}} .tmpcoder-slider-primary-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_1',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color_1',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-slider-primary-btn:hover'
			]
		);

		$this->add_control(
			'btn_hover_color_1',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-slider-primary-btn:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color_1',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow_1',
				'selector' => '{{WRAPPER}} .tmpcoder-slider-primary-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_transition_duration_1',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-slider-primary-btn svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_typography_1_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography_1',
				'selector' => '{{WRAPPER}} .tmpcoder-slider-primary-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_icon_size_1',
			[
				'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-slider-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'btn_icon_distance_1',
			[
				'label' => esc_html__( 'Icon Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn i' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-slider-primary-btn svg' => 'margin-left: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'btn_icon_top_distance_1',
			[
				'label' => esc_html__( 'Icon Top Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -30,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn i' => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-slider-primary-btn svg' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding_1',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 12,
					'right' => 25,
					'bottom' => 12,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_margin_1',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'btn_border_type_1',
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
					'{{WRAPPER}} .tmpcoder-slider-primary-btn' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_width_1',
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
					'{{WRAPPER}} .tmpcoder-slider-primary-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type_1!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius_1',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-primary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		
		// Styles
		// Section: Button Secondary --------
		$this->start_controls_section(
			'tmpcoder__section_style_btn_2',
			[
				'label' => esc_html__( 'Button Secondary', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style_2' );

		$this->start_controls_tab(
			'tab_btn_normal_2',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color_2',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-slider-secondary-btn'
			]
		);

		$this->add_control(
			'btn_color_2',
			[
				'label'     => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_2',
			[
				'label'     => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_box_shadow_2',
				'selector' => '{{WRAPPER}} .tmpcoder-slider-secondary-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_2',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color_2',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-slider-secondary-btn:hover'
			]
		);

		$this->add_control(
			'btn_hover_color_2',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color_2',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow_2',
				'selector' => '{{WRAPPER}} .tmpcoder-slider-secondary-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_transition_duration_2',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_typography_2_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography_2',
				'selector' => '{{WRAPPER}} .tmpcoder-slider-secondary-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_icon_size_2',
			[
				'label' => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'btn_icon_distance_2',
			[
				'label' => esc_html__( 'Icon Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn i' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn svg' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_icon_top_distance_2',
			[
				'label' => esc_html__( 'Icon Top Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -30,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn i' => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn svg' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
			

		$this->add_responsive_control(
			'btn_padding_2',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 12,
					'right' => 25,
					'bottom' => 12,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_margin_2',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'btn_border_type_2',
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
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_width_2',
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
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type_2!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius_2',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-secondary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
		

		// Styles
		// Section: Scroll Button -----------
		$this->add_section_style_scroll_btn();

		// Styles
		// Section: Video Icon -------
		$this->start_controls_section(
			'tmpcoder__section_style_slider_video_btn',
			[
				'label' => esc_html__( 'Video Icon', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slider_video_btn_size',
			[
				'label' => esc_html__( 'Video Icon Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'medium',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'small' => esc_html__( 'Small', 'sastra-essential-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'sastra-essential-addons-for-elementor' ),
					'large' => esc_html__( 'Large', 'sastra-essential-addons-for-elementor' ),
				],
				'frontend_available' => true,
				// 'prefix_class' => 'tmpcoder-slider-video-icon-size-%s',
			]
		);
	
		$this->add_control(
			'slider_video_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-video-btn' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		// Styles
		// Section: Navigation ---
		$this->start_controls_section(
			'tmpcoder__section_style_slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_slider_nav_style' );

		$this->start_controls_tab(
			'tab_slider_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'slider_nav_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-slider-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_slider_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'slider_nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-slider-arrow:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'slider_nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-slider-arrow svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_nav_font_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_nav_size',
			[
				'label' => esc_html__( 'Box Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'slider_nav_border_type',
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
					'{{WRAPPER}} .tmpcoder-slider-arrow' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'slider_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control_stack_slider_nav_position();

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Pagination ---
		$this->start_controls_section(
			'tmpcoder__section_style_slider_dots',
			[
				'label' => esc_html__( 'Pagination', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_slider_dots' );

		$this->start_controls_tab(
			'tab_slider_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'slider_dots_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.35)',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_dots_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_slider_dots_active',
			[
				'label' => esc_html__( 'Active', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'slider_dots_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-dots .slick-active .tmpcoder-slider-dot' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_dots_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-dots .slick-active .tmpcoder-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'slider_dots_width',
			[
				'label' => esc_html__( 'Box Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-dot' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'slider_dots_height',
			[
				'label' => esc_html__( 'Box Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-dot' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'slider_dots_border_type',
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
					'{{WRAPPER}} .tmpcoder-slider-dot' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'slider_dots_border_width',
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
					'{{WRAPPER}} .tmpcoder-slider-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'slider_dots_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'slider_dots_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit' => '%',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_dots_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],							
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-slider-dots-horizontal .tmpcoder-slider-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-slider-dots-vertical .tmpcoder-slider-dot' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_control_slider_dots_hr();
		
		$this->add_responsive_control(
			'slider_dots_vr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 96,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-slider-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	public function load_slider_template( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		$edit_link = '<span class="tmpcoder-template-edit-btn" data-permalink="'. esc_url(get_permalink( $id )) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_tmpcoder_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	public function render_pro_element_slider_scroll_btn() {}

	protected function render() {
		$settings = $this->get_settings();
		$settings_new = $this->get_settings_for_display();
		$settings = array_merge( $settings, $settings_new );
		$slider_html = '';
		$item_count = 0;

		if ( empty( $settings['slider_items'] ) ) {
			return;
		}
		
		foreach ( $settings['slider_items'] as $key => $item ) {

			// Skip if item is not valid array
			if ( !is_array($item) ) {
				continue;
			}

			if ( ! tmpcoder_is_availble() && $key === 4 ) {
				break;
			}

			if ( ! tmpcoder_is_availble() ) {
				if ( 'pro-3' == $settings['slider_amount'] || 'pro-4' == $settings['slider_amount'] || 'pro-5' == $settings['slider_amount'] || 'pro-6' == $settings['slider_amount'] ) {
					$settings['slider_amount'] = 2;
				}

				$item['slider_content_type'] = 'custom';
			}
 
			// Load Template
			if ( 'template' === $item['slider_content_type'] ) {

				$slider_html .= '<div class="tmpcoder-slider-item elementor-repeater-item-'. esc_attr($item['_id']) .'">';
			
					$slider_html .= $this->load_slider_template( $item['slider_select_template'] );

				$slider_html .= '</div>';

			// Or Build Custom
			} elseif( 'custom' === $item['slider_content_type'] ) {
				if ( ! tmpcoder_is_availble() ) {
					$item['slider_item_link_type'] = 'none';
				}

				$item_type = $item['slider_item_link_type'] ?? '';
				$item_url = $item['slider_item_bg_image_url']['url'] ?? '';
				$btn_url_1 = $item['slider_item_btn_url_1']['url'] ?? '';
				$btn_element_1 = 'div';
				$btn_attribute_1 = '';
				$icon_html_1 = $item['slider_item_btn_text_1'] ?? '';
				$btn_url_2 = $item['slider_item_btn_url_2']['url'] ?? '';
				$btn_element_2 = 'div';
				$btn_attribute_2 = '';
				$icon_html_2 = $item['slider_item_btn_text_2'] ?? '';
				$ken_burn_class = '';
				$item_bg_image_url = '';
				$item_bg_image_html = '';
				
				if( isset($item['slider_item_bg_image']) && !empty($item['slider_item_bg_image']) && isset($item['slider_item_bg_image']['source']) && ($item['slider_item_bg_image']['source'] ?? '') == 'url' ) {
					$item_bg_image_url = $item['slider_item_bg_image']['url'] ?? '';

					$settings['slider_image_size'] = ['id' => $item['slider_item_bg_image']['id'] ?? 0];
					$item_bg_image_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'slider_image_size');
				} elseif( isset($item['slider_item_bg_image']) && !empty($item['slider_item_bg_image']) ) {

					$item_bg_image_url = Group_Control_Image_Size::get_attachment_image_src( $item['slider_item_bg_image']['id'] ?? 0, 'slider_image_size', $settings );

					$settings['slider_image_size'] = ['id' => $item['slider_item_bg_image']['id'] ?? 0];
					$item_bg_image_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'slider_image_size');
				}

				$item_video_src = $item['slider_item_video_src'] ?? '';
				$item_video_start = $item['slider_item_video_start'] ?? '';
				$item_video_end = $item['slider_item_video_end'] ?? '';

				if ( $item_type === 'video-media' ) {
					$item_video_src = $item['hosted_url']['url'] ?? '';
				}

				if ( '' !== ($item['slider_item_btn_icon_1']['value'] ?? '') ) {
					ob_start();
					Icons_Manager::render_icon( $item['slider_item_btn_icon_1'], [ 'aria-hidden' => 'true' ] );
					$icon_html_1 .= ob_get_clean();
				}

				if ( '' !== ($item['slider_item_btn_icon_2']['value'] ?? '') ) {
					ob_start();
					Icons_Manager::render_icon( $item['slider_item_btn_icon_2'], [ 'aria-hidden' => 'true' ] );
					$icon_html_2 .= ob_get_clean();	
				}

				// Slider Ken Burns Effect
				if ( ($item['slider_item_bg_kenburns'] ?? '') === 'yes' ) {
					$ken_burn_class = ' tmpcoder-ken-burns-'. ($item['slider_item_bg_zoom'] ?? '');
				}

				$this->add_render_attribute( 'slider_item'. $item_count, 'class', 'tmpcoder-slider-item elementor-repeater-item-'. ($item['_id'] ?? '') );

				if ( strpos( $item_type, 'video' ) !== false && ! empty( $item_video_src ) ) {

					$this->add_render_attribute( 'slider_item'. $item_count, 'class', 'tmpcoder-slider-video-item' );

					$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-autoplay', $item['slider_item_video_autoplay'] ?? '' );

					if ( $item_type === 'video-youtube' ) {
						preg_match('![?&]{1}v=([^&]+)!', $item_video_src, $item_video_id );

						$item_bg_image_url = 'https://i.ytimg.com/vi_webp/'. $item_video_id[1] .'/maxresdefault.webp';
						
						if ( 'yes' === ($item['slider_item_video_autoplay'] ?? '') ) {
							$item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] .'?autoplay=1';
						} else {
							$item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] . '?enablejsapi=1';
						}

						if ( $item['slider_item_video_mute'] === 'yes' ) {
							$item_video_src .= '&mute=1';
						}

						if ( $item['slider_item_video_controls'] !== 'yes') {
							$item_video_src .= '&controls=0';
						}

						if ( $item['slider_item_video_loop'] === 'yes' ) {
							$item_video_src .= '&loop=1&playlist='. $item_video_id[1];
						} else {
							if ( ! empty( $item_video_start ) ) {
								$item_video_src .= '&start='. $item_video_start;
							}

							if ( ! empty( $item_video_end ) ) {
								$item_video_src .= '&end='. $item_video_end;
							}
						}

					} elseif ( $item_type === 'video-vimeo' ) {
		          
		                $item_video_src = str_replace( 'vimeo.com', 'player.vimeo.com/video', $item_video_src );

						$item_video_src .= '?autoplay=1&title=0&portrait=0&byline=0';

						if ( $item['slider_item_video_mute'] === 'yes' ) {
							$item_video_src .= '&muted=1';
						}

						if ( $item['slider_item_video_controls'] !== 'yes') {
							$item_video_src .= '&controls=0';
						}

						if ( $item['slider_item_video_loop'] === 'yes' ) {
							$item_video_src .= '&loop=1';
						} elseif ( ! empty( $item_video_start ) ) {
							$item_video_src .= '&#t='. gmdate( 'H', $item_video_start ) .'h'. gmdate( 'i', $item_video_start ) .'m'. gmdate( 's', $item_video_start ) .'s';
						}
						
					} elseif ( $item_type === 'video-media' ) {
							$item_video_src = $item['hosted_url']['url'];
							$item_video_mute = $item['slider_item_video_mute'] === 'yes' ? 'muted' : '';
							$item_video_loop = $item['slider_item_video_loop'] === 'yes' ? 'loop' : '';
							$item_video_controls = $item['slider_item_video_controls'] === 'yes' ? 'controls' : '';

							$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-mute', $item_video_mute );
							$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-loop', $item_video_loop );
							$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-controls', $item_video_controls );
					}

					$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-src', $item_video_src );
				}

				$slider_item_attribute = $this->get_render_attribute_string( 'slider_item'. $item_count );

				$slider_html .= '<div '. $slider_item_attribute .'>';

				if ( 'image' == $settings['slider_image_type'] ) {

					$slider_html .= '<img class="tmpcoder-slider-img" src="'. esc_url($item_bg_image_url) .'" />';

				} else {
					// Slider Background Image
					$bg_style = '';
					if ( !empty($item_bg_image_url) ) {
						$bg_style = ' style="background-image: url(' . esc_url($item_bg_image_url) . ');"';
					}
					$slider_html .= '<div class="tmpcoder-slider-item-bg '. esc_attr($ken_burn_class) .'"' . $bg_style . '></div>';
				}

				if ( 'slide_vertical' === $settings['slider_effect'] ) {
					$slider_amount = 1;
				} else {
					$slider_amount = +$settings['slider_amount'];
				}

				// Slider Overlay
				$slider_overlay_html = '';
				if ( ($item['slider_item_overlay'] ?? '') === 'yes' ) {
					// if ( $slider_amount === 1 || $item['slider_item_blend_mode'] !== 'normal' ) {
					if ( $slider_amount === 1 || (isset($item['slider_item_blend_mode']) && ($item['slider_item_blend_mode'] ?? '') !== 'normal') ) { 	
						$slider_html .= '<div class="tmpcoder-slider-item-overlay"></div>';
					} else {
						$slider_overlay_html = '<div class="tmpcoder-slider-item-overlay"></div>';
					}
				} 

				// Slider Content Attributes
				$this->add_render_attribute( 'slider_container'. $item_count, 'class', 'tmpcoder-cv-container' );	
				$this->add_render_attribute( 'slider_outer'. $item_count, 'class', 'tmpcoder-cv-outer' );

				if ( $settings['slider_effect'] != 'fade' ) {
					$settings['slider_content_animation'] = 'none';
				}

				if ( $settings['slider_content_animation'] !== 'none' ) {
					if ( $slider_amount === 1 ) {
						$this->add_render_attribute( 'slider_container'. $item_count, 'class', 'tmpcoder-slider-animation' );
						$this->add_render_attribute( 'slider_outer'. $item_count, 'class', 'tmpcoder-anim-transparency tmpcoder-anim-size-'. $settings['slider_content_anim_size'] .' tmpcoder-overlay-'. $settings['slider_content_animation'] );
					} elseif ( !empty( $item_bg_image_url ) && ($item['slider_item_video_autoplay'] ?? '') !== 'yes' ) {
						$this->add_render_attribute( 'slider_container'. $item_count, 'class', 'tmpcoder-slider-animation tmpcoder-animation-wrap' );
						$this->add_render_attribute( 'slider_outer'. $item_count, 'class', 'tmpcoder-anim-transparency tmpcoder-anim-size-'. $settings['slider_content_anim_size'] .' tmpcoder-overlay-'. $settings['slider_content_animation'] );
					}
				}

				// Slider Content
				$slider_html .= '<div '. $this->get_render_attribute_string( 'slider_container'. $item_count ) .'>';

					// Slider Link Type
					if ( ! empty( $item_url ) && $item_type === 'custom' ) {

						$this->add_render_attribute( 'slider_item_url'. $item_count, 'href', $item_url );

					if ( $item['slider_item_bg_image_url']['is_external'] ?? false ) {
						$this->add_render_attribute( 'slider_item_url'. $item_count, 'target', '_blank' );
					}

					if ( $item['slider_item_bg_image_url']['nofollow'] ?? false ) {
						$this->add_render_attribute( 'slider_item_url'. $item_count, 'nofollow', '' );
					}

						$slider_html .= '<a class="tmpcoder-slider-item-url" '. $this->get_render_attribute_string( 'slider_item_url'. $item_count ) .'></a>';

					}

					$slider_html .= '<div '. $this->get_render_attribute_string( 'slider_outer'. $item_count ) .'>';
						$slider_html .= '<div class="tmpcoder-cv-inner">';
							
							// Slider Overlay
							$slider_html .= $slider_overlay_html;
							if ( 'yes' === $item['slider_show_content'] ) {

							$slider_html .= '<div class="tmpcoder-slider-content">';

								//  Video Icon
								if ( strpos( $item_type, 'video' ) !== false && $item['slider_item_video_autoplay'] !== 'yes' ) {
									$slider_html .= '<div class="tmpcoder-slider-video-btn">';
										$slider_html .= '<i class="fas fa-play"></i>';
									$slider_html .= '</div>';
								}

								//  Slider Title
								if ( $settings['slider_title'] === 'yes' && ! empty( $item['slider_item_title'] ) ) {
								$slider_html .= '<div class="tmpcoder-slider-title">';
									if ( '' !== $item['slider_title_tag'] ) {
										$slider_html .= '<' . tmpcoder_validate_html_tag( $item['slider_title_tag'] ) . '>'. wp_kses_post($item['slider_item_title']) .'</'. tmpcoder_validate_html_tag( $item['slider_title_tag'] ) .'>';
									} else {
										$slider_html .= '<' . tmpcoder_validate_html_tag( $settings['slider_title_tag'] ) . '>'. wp_kses_post($item['slider_item_title']) .'</'. tmpcoder_validate_html_tag( $settings['slider_title_tag'] ) .'>';
									}
								$slider_html .= '</div>';
								}	
								
								// Slider Sub Title
								if ( $settings['slider_sub_title'] === 'yes' && ! empty( $item['slider_item_sub_title'] ) ) {
								$slider_html .= '<div class="tmpcoder-slider-sub-title">';
									if ( '' !== $item['slider_sub_title_tag'] ) {
										$slider_html .= '<' . tmpcoder_validate_html_tag( $item['slider_sub_title_tag'] ) . '>'. wp_kses_post($item['slider_item_sub_title']) .'</' . tmpcoder_validate_html_tag( $item['slider_sub_title_tag'] ) . '>';
									} else {
										$slider_html .= '<' . tmpcoder_validate_html_tag( $settings['slider_sub_title_tag'] ) . '>'. wp_kses_post($item['slider_item_sub_title']) .'</' . tmpcoder_validate_html_tag( $settings['slider_sub_title_tag'] ) . '>';
									}
								$slider_html .= '</div>';
								}							

								// Slider Description
								if ( $settings['slider_description'] === 'yes' && ! empty( $item['slider_item_description'] ) ) {
									$slider_html .= '<div class="tmpcoder-slider-description">';	
										$slider_html .= '<p>'. wp_kses_post($item['slider_item_description']) .'</p>';
									$slider_html .= '</div>';
								}
								
								// Slider Button Secondary
								if ( ! empty( $btn_url_1 ) ) {
									
									$btn_element_1 = 'a';

									$this->add_render_attribute( 'primary_btn_url'. $item_count, 'href', $btn_url_1 );

									if ( $item['slider_item_btn_url_1']['is_external'] ) {
										$this->add_render_attribute( 'primary_btn_url'. $item_count, 'target', '_blank' );
									}

									if ( $item['slider_item_btn_url_1']['nofollow'] ) {
										$this->add_render_attribute( 'primary_btn_url'. $item_count, 'nofollow', '' );
									}

									$btn_attribute_1 = $this->get_render_attribute_string( 'primary_btn_url'. $item_count );
								}
				
								// Slider Button Secondary
								if ( ! empty( $btn_url_2 ) ) {
									
									$btn_element_2 = 'a';

									$this->add_render_attribute( 'secondary_btn_url'. $item_count, 'href', $btn_url_2 );

									if ( $item['slider_item_btn_url_2']['is_external'] ) {
										$this->add_render_attribute( 'secondary_btn_url'. $item_count, 'target', '_blank' );
									}

									if ( $item['slider_item_btn_url_2']['nofollow'] ) {
										$this->add_render_attribute( 'secondary_btn_url'. $item_count, 'nofollow', '' );
									}

									$btn_attribute_2 = $this->get_render_attribute_string( 'secondary_btn_url'. $item_count );
								}

								$slider_html .= '<div class="tmpcoder-slider-btns">';
								
								if ( $item['slider_item_btn_1'] === 'yes' && ! empty( $icon_html_1 ) ) {
									$slider_html .= '<'. $btn_element_1 .' class="tmpcoder-slider-primary-btn" '. $btn_attribute_1 .'>'. $icon_html_1 .'</'. $btn_element_1 .'>';
								}

								if ( $item['slider_item_btn_2'] === 'yes' && ! empty( $icon_html_2 ) ) {
									$slider_html .= '<'. $btn_element_2 .' class="tmpcoder-slider-secondary-btn" '. $btn_attribute_2 .'>'. $icon_html_2 .'</'. $btn_element_2 .'>';
								}
					
								$slider_html .= '</div>';
								
							$slider_html .= '</div>';
							} else {
								//  Video Icon
								if ( strpos( $item_type, 'video' ) !== false && $item['slider_item_video_autoplay'] !== 'yes' ) {
									$slider_html .= '<div class="tmpcoder-slider-video-btn">';
										$slider_html .= '<i class="fas fa-play"></i>';
									$slider_html .= '</div>';
								}
							}

							$slider_html .= '</div>';
						$slider_html .= '</div>';
					$slider_html .= '</div>';
				$slider_html .= '</div>';

				$item_count++;

			}
		}

		if ( ! tmpcoder_is_availble() ) {
			$settings['slider_autoplay'] = '';
			$settings['slider_autoplay_duration'] = 0;
			$settings['slider_pause_on_hover'] = '';
		}

		if ( 'sl_vl' === $settings['slider_effect'] ) {
			$settings['slider_effect'] = 'slide';
		}

		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

		$slider_video_btn_widescreen = isset($settings['slider_video_btn_size_widescreen']) && !empty($settings['slider_video_btn_size_widescreen']) ? $settings['slider_video_btn_size_widescreen'] : $settings['slider_video_btn_size'];
		$slider_video_btn_desktop = isset($settings['slider_video_btn_size']) && !empty($settings['slider_video_btn_size']) ? $settings['slider_video_btn_size'] : $slider_video_btn_widescreen;
		$slider_video_btn_laptop =  isset($settings['slider_video_btn_size_laptop']) && !empty($settings['slider_video_btn_size_laptop']) ? $settings['slider_video_btn_size_laptop'] : $slider_video_btn_desktop;
		$slider_video_btn_tablet_extra =  isset($settings['slider_video_btn_size_tablet_extra']) && !empty($settings['slider_video_btn_size_tablet_extra']) ? $settings['slider_video_btn_size_tablet_extra'] : $slider_video_btn_laptop;
		$slider_video_btn_tablet =  isset($settings['slider_video_btn_size_tablet']) && !empty($settings['slider_video_btn_size_tablet']) ? $settings['slider_video_btn_size_tablet'] : $slider_video_btn_tablet_extra;
		$slider_video_btn_mobile_extra =  isset($settings['slider_video_btn_size_mobile_extra']) && !empty($settings['slider_video_btn_size_mobile_extra']) ? $settings['slider_video_btn_size_mobile_extra'] : $slider_video_btn_tablet;
		$slider_video_btn_mobile =  isset($settings['slider_video_btn_size_mobile']) && !empty($settings['slider_video_btn_size_mobile']) ? $settings['slider_video_btn_size_mobile'] : $slider_video_btn_mobile_extra;

		$slider_options = [
			'rtl' => $slider_is_rtl,
			'speed' => absint( $settings['slider_effect_duration'] * 1000 ),
			'arrows'=> true,
			'dots' 	=> true,
			'autoplay' => ( $settings['slider_autoplay'] === 'yes' ),
			'autoplaySpeed'=> absint( $settings['slider_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['slider_pause_on_hover'],
			'prevArrow' => '#tmpcoder-slider-prev-'. $this->get_id(),
			'nextArrow' => '#tmpcoder-slider-next-'. $this->get_id(),
			'vertical' => 'slide_vertical' === $settings['slider_effect'] ? true : false,
			'adaptiveHeight' => true
		];

		$this->add_render_attribute( 'advanced-slider-attribute', [
			'class' => 'tmpcoder-advanced-slider',
			'dir' => esc_attr( $slider_direction ),
			'data-slick' => wp_json_encode( $slider_options ),
			'data-video-btn-size' => wp_json_encode(
				[
					'widescreen' => $slider_video_btn_widescreen,
					'desktop' => $slider_video_btn_desktop,
					'laptop' => $slider_video_btn_laptop,
					'tablet_extra' => $slider_video_btn_tablet_extra,
					'tablet' => $slider_video_btn_tablet,
					'mobile_extra' => $slider_video_btn_mobile_extra,
					'mobile' => $slider_video_btn_mobile
				]
			)
		] );

		?>

		<!-- Advanced Slider -->
		<div class="tmpcoder-advanced-slider-wrap">
			
			<?php 
            $kses_defaults = wp_kses_allowed_html( 'post' );
            $svg_args = array(
                'style'   => array(),
                'svg'   => array(
                    'version' => true,
                    'xmlns:xlink' => true,
                    'x' => true,
                    'y' => true,
                    'style' => true,
                    'xml:space' => true,
                    'class'           => true,
                    'aria-hidden'     => true,
                    'aria-labelledby' => true,
                    'role'            => true,
                    'xmlns'           => true,
                    'width'           => true,
                    'height'          => true,
                    'viewbox'         => true, // <= Must be lower case!
                ),
                'g'     => array( 
                    'fill' => true,
                    'id' => true,
                    'class' => true,
                ),
                'title' => array( 'title' => true ),
                'path'  => array( 
                    'd'               => true, 
                    'fill'            => true,
                    'style' => true,
                ),
                'polygon' => array(
                    'class' => true,
                    'points' => true,
                ),
            );
            $allowed_tags = array_merge( $kses_defaults, $svg_args );


            echo wp_kses('<div '. $this->get_render_attribute_string( 'advanced-slider-attribute' ) .' data-slide-effect="'. esc_attr($settings['slider_effect']) .'">
				'. $slider_html .'
			</div>', $allowed_tags);
            ?>
			<div class="tmpcoder-slider-controls">
				<div class="tmpcoder-slider-dots"></div>
			</div>

			<div class="tmpcoder-slider-arrow-container">
				<div class="tmpcoder-slider-prev-arrow tmpcoder-slider-arrow" id="<?php echo 'tmpcoder-slider-prev-'. esc_attr($this->get_id()); ?>">
					<?php
                    echo wp_kses( tmpcoder_get_icon( $settings['slider_nav_icon'], '' ), $allowed_tags ); ?>
				</div>
				<div class="tmpcoder-slider-next-arrow tmpcoder-slider-arrow" id="<?php echo 'tmpcoder-slider-next-'. esc_attr($this->get_id()); ?>">
					<?php 
                    echo wp_kses( tmpcoder_get_icon( $settings['slider_nav_icon'], '' ), $allowed_tags ); ?>
				</div>
			</div>
			
			<?php $this->render_pro_element_slider_scroll_btn(); ?>

		</div>
		<?php
	}
}