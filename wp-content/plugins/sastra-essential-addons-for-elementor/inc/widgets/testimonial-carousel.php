<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Testimonial_Carousel extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-testimonial';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Carousel', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-testimonial-carousel';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category' ];
	}

	public function get_keywords() {
		return [ 'testimonial carousel', 'reviews', 'rating', 'stars' ];
	}
	
	public function get_script_depends() {
		return [ 'imagesloaded', 'tmpcoder-slick', 'tmpcoder-testimonial' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-testimonial' ];
	}

	public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_testimonial_amount() {
		$this->add_responsive_control(
			'testimonial_amount',
			[
				'label' => esc_html__( 'Columns', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 2,
				'widescreen_default' => 2,
				'laptop_default' => 2,
				'tablet_extra_default' => 2,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					'1' => esc_html__( 'One', 'sastra-essential-addons-for-elementor' ),
					'2' => esc_html__( 'Two', 'sastra-essential-addons-for-elementor' ),
					'pro-3' => esc_html__( 'Three (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-4' => esc_html__( 'Four (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-5' => esc_html__( 'Five (Pro)', 'sastra-essential-addons-for-elementor' ),
					'pro-6' => esc_html__( 'Six (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-testimonial-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
			]
		);
	}

	public function add_control_testimonial_icon() {
		$this->add_control(
			'testimonial_icon',
			[
				'label' => esc_html__( 'Select Quote Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'fas fa-quote-left' => esc_html__( 'Quote Left', 'sastra-essential-addons-for-elementor' ),
					'fas fa-quote-right' => esc_html__( 'Quote Right', 'sastra-essential-addons-for-elementor' ),
					'pro-svg' => esc_html__( 'SVG Icons (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'separator' => 'before',
			]
		);
	}

	public function add_control_testimonial_rating_score() {}

	public function add_repeater_args_social_media() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_media_is_external() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_media_nofollow() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_section_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_icon_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_url_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_section_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_icon_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_url_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_section_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_icon_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_url_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_section_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_icon_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_url_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_section_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_icon_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_url_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_control_stack_testimonial_autoplay() {}

	public function add_control_stack_nav_position() {}

	public function add_control_dots_hr() {}

	protected function register_controls() {

		// Section: Items -----------
		$this->start_controls_section(
			'tmpcoder__section_testimonial_items',
			[
				'label' => esc_html__( 'Items', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'testimonial_author',
			[
				'label' => esc_html__( 'Author', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'John Doe',
			]
		);

		$repeater->add_control(
			'testimonial_job',
			[
				'label' => esc_html__( 'Job', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sony CEO',
			]
		);

		$repeater->add_control(
			'testimonial_image',
			[
				'label' => esc_html__( 'Author Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'testimonial_logo_image',
			[
				'label' => esc_html__( 'Company Logo', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'testimonial_logo_url',
			[
				'label' => esc_html__( 'Logo URL', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'testimonial_logo_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
            'testimonial_title_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$repeater->add_control(
			'testimonial_title',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Awesome Theme',
			]
		);

		$repeater->add_control(
			'testimonial_rating_amount',
			[
				'label' => esc_html__( 'Rating', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10,
				'step' => 0.1,
				'default' => 4.5,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'testimonial_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.',
			]
		);

		$repeater->add_control(
			'testimonial_date',
			[
				'label' => esc_html__( 'Date', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '7 Days Ago',
			]
		);

		$repeater->add_control(
            'social_media_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$repeater->add_control( 'social_media', $this->add_repeater_args_social_media() );

		$repeater->add_control( 'social_media_is_external', $this->add_repeater_args_social_media_is_external() );

		$repeater->add_control( 'social_media_nofollow', $this->add_repeater_args_social_media_nofollow() );

		$repeater->add_control( 'social_section_1', $this->add_repeater_args_social_section_1() );

		$repeater->add_control( 'social_icon_1', $this->add_repeater_args_social_icon_1() );

		$repeater->add_control( 'social_url_1', $this->add_repeater_args_social_url_1() );

		$repeater->add_control( 'social_section_2', $this->add_repeater_args_social_section_2() );

		$repeater->add_control( 'social_icon_2', $this->add_repeater_args_social_icon_2() );

		$repeater->add_control( 'social_url_2', $this->add_repeater_args_social_url_2() );

		$repeater->add_control( 'social_section_3', $this->add_repeater_args_social_section_3() );

		$repeater->add_control( 'social_icon_3', $this->add_repeater_args_social_icon_3() );

		$repeater->add_control( 'social_url_3', $this->add_repeater_args_social_url_3() );

		$repeater->add_control( 'social_section_4', $this->add_repeater_args_social_section_4() );

		$repeater->add_control( 'social_icon_4', $this->add_repeater_args_social_icon_4() );

		$repeater->add_control( 'social_url_4', $this->add_repeater_args_social_url_4() );

		$repeater->add_control( 'social_section_5', $this->add_repeater_args_social_section_5() );

		$repeater->add_control( 'social_icon_5', $this->add_repeater_args_social_icon_5() );

		$repeater->add_control( 'social_url_5', $this->add_repeater_args_social_url_5() );

		$this->add_control(
			'testimonial_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'testimonial_rating_amount' => 4.5,
						'testimonial_title' => esc_html__( 'Awesome Theme', 'sastra-essential-addons-for-elementor' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'sastra-essential-addons-for-elementor' ),
						'testimonial_author' => esc_html__( 'John Doe', 'sastra-essential-addons-for-elementor' ),
						'testimonial_job' => esc_html__( 'Sony CEO', 'sastra-essential-addons-for-elementor' ),
						'testimonial_date' => esc_html__( '7 Days Ago', 'sastra-essential-addons-for-elementor' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
					[		
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'testimonial_rating_amount' => 5,
						'testimonial_title' => esc_html__( 'Simply The Best', 'sastra-essential-addons-for-elementor' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'sastra-essential-addons-for-elementor' ),
						'testimonial_author' => esc_html__( 'Tom Jones', 'sastra-essential-addons-for-elementor' ),
						'testimonial_job' => esc_html__( 'Tesla CMO', 'sastra-essential-addons-for-elementor' ),
						'testimonial_date' => esc_html__( '10.04.2018', 'sastra-essential-addons-for-elementor' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
					[	
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'testimonial_rating_amount' => 4,
						'testimonial_title' => esc_html__( 'Easy To Use', 'sastra-essential-addons-for-elementor' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'sastra-essential-addons-for-elementor' ),
						'testimonial_author' => esc_html__( 'Mark Wilson', 'sastra-essential-addons-for-elementor' ),
						'testimonial_job' => esc_html__( 'Apple Manager', 'sastra-essential-addons-for-elementor' ),
						'testimonial_date' => esc_html__( '5 Month Ago', 'sastra-essential-addons-for-elementor' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
					[	
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],		
						'testimonial_rating_amount' => 3.5,
						'testimonial_title' => esc_html__( 'Creative', 'sastra-essential-addons-for-elementor' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'sastra-essential-addons-for-elementor' ),
						'testimonial_author' => esc_html__( 'Bob Smith', 'sastra-essential-addons-for-elementor' ),
						'testimonial_job' => esc_html__( 'Doctor', 'sastra-essential-addons-for-elementor' ),
						'testimonial_date' => esc_html__( '6 Month Ago', 'sastra-essential-addons-for-elementor' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
				],
				'title_field' => '{{{ testimonial_title }}}',
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'testimonial_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Testimonials are available<br> in the <strong><a href="https://spexoaddons.com/?ref=rea-plugin-panel-testimonial-carousel-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->start_controls_section(
			'tmpcoder__section_settings',
			[
				'label' => esc_html__( 'Settings', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'testimonial_image_size',
				'default' => 'full',
			]
		);

		$this->add_control_testimonial_amount();

		$this->add_control(
			'testimonial_slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'frontend_available' => true,
				'default' => 2,
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'testimonial_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'widescreen_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'laptop_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_extra_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'mobile_extra_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-carousel .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-testimonial-carousel .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'testimonial_amount!' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'testimonial_nav',
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
					'{{WRAPPER}} .tmpcoder-testimonial-arrow' => 'display:{{VALUE}} !important;',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'testimonial_nav_hover',
			[
				'label' => esc_html__( 'Show on Hover', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'fade',
				'prefix_class'	=> 'tmpcoder-testimonial-nav-',
				'render_type' => 'template',
				'condition' => [
					'testimonial_nav' => 'yes',
				],
			]
		);


		$this->add_control(
			'testimonial_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg-angle-1-left',
				'options' => $this->tmpcoder_get_svg_icons_array( 'arrows', [
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
					'testimonial_nav' => 'yes',
				],
			]
		);


		$this->add_responsive_control(
			'testimonial_dots',
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
					'{{WRAPPER}} .tmpcoder-testimonial-dots' => 'display: {{VALUE}} !important;',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control_stack_testimonial_autoplay();

		$this->add_control(
			'testimonial_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'testimonial_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'sastra-essential-addons-for-elementor' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'sastra-essential-addons-for-elementor' ),
					'fade' => esc_html__( 'Fade', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'testimonial_effect_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,	
			]
		);

		// Icon
		$this->add_control_testimonial_icon();

		$this->add_control(//TODO: This option doesn't work properly
			'testimonial_icon_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Icon Position', 'sastra-essential-addons-for-elementor' ),
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top Content', 'sastra-essential-addons-for-elementor' ),
					'inner' => esc_html__( 'Inner Content', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'testimonial_icon!' => 'none',
				],
				'render_type' => 'template',
			]
		);

		// Rating
		$this->add_control(
			'testimonial_rating',
			[
				'label' => esc_html__( 'Rating', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'testimonial_rating_scale',
			[
				'label' => esc_html__( 'Scale', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 10,
				'condition' => [
					'testimonial_rating' => 'yes',
				],
			]
		);

		$this->add_control_testimonial_rating_score();

		$this->add_control(
			'testimonial_rating_style',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style_1' => 'Style 1',
					'style_2' => 'Style 2',
				],
				'default' => 'style_2',
				'render_type' => 'template',
				'prefix_class' => 'tmpcoder-testimonial-rating-',
				'condition' => [
					'testimonial_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'testimonial_unmarked_rating_style',
			[
				'label' => esc_html__( 'Unmarked Style', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'solid' => [
						'title' => esc_html__( 'Solid', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'fas fa-star',
					],
					'outline' => [
						'title' => esc_html__( 'Outline', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'far fa-star',
					],
				],
				'default' => 'outline',
				'condition' => [
					'testimonial_rating' => 'yes',
					'testimonial_rating_score' => '',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'tmpcoder__section_style_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'general_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-item'
			]
		);

		$this->add_responsive_control(
			'general_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 50,
					'left' => 5,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
				'label' => esc_html__( 'Border', 'sastra-essential-addons-for-elementor' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'tmpcoder__section_style_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-content-inner'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-content-inner',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 25,
					'right' => 25,
					'bottom' => 27,
					'left' => 25,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-left .tmpcoder-testimonial-meta' => 'padding-top: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-right .tmpcoder-testimonial-meta' => 'padding-top: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-top:not(.tmpcoder-testimonial-meta-align-center) .tmpcoder-testimonial-meta,
					 {{WRAPPER}}.tmpcoder-testimonial-meta-position-bottom:not(.tmpcoder-testimonial-meta-align-center) .tmpcoder-testimonial-meta' => 'padding: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label' => esc_html__( 'Margin', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-content-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-left .tmpcoder-testimonial-meta' => 'margin-top: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-right .tmpcoder-testimonial-meta' => 'margin-top: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-top:not(.tmpcoder-testimonial-meta-align-center) .tmpcoder-testimonial-meta,
					 {{WRAPPER}}.tmpcoder-testimonial-meta-position-bottom:not(.tmpcoder-testimonial-meta-align-center) .tmpcoder-testimonial-meta' => 'margin: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-content-inner' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_width',
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
					'{{WRAPPER}} .tmpcoder-testimonial-content-inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-left .tmpcoder-testimonial-content-inner:before' => 'left: calc(-22px - {{left}}{{UNIT}});',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-right .tmpcoder-testimonial-content-inner:before' => 'right: calc(-22px - {{right}}{{UNIT}});',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-top .tmpcoder-testimonial-content-inner:before' => 'top: calc(-15px - {{top}}{{UNIT}});',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-bottom .tmpcoder-testimonial-content-inner:before' => 'bottom: calc(-15px - {{bottom}}{{UNIT}});',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-content-inner' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
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
					'{{WRAPPER}} .tmpcoder-testimonial-content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		// Triangle
		$this->add_control(
			'content_triangle',
			[
				'label' => esc_html__( 'Triangle', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,				
				'default' => 'yes',
				'prefix_class' => 'tmpcoder-testimonial-triangle-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'triangle_color',
			[
				'label' => esc_html__( 'Triangle Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f7f7f7',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-content-inner:before' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'content_triangle' => 'yes',
				],
			]
		);

		// Icon
		$this->add_control(
			'icon_section',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-testimonial-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-testimonial-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'icon_align',
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
					'{{WRAPPER}} .tmpcoder-testimonial-icon' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Title
		$this->add_control(
			'title_section',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-title',
			]
		);

		$this->add_responsive_control(
			'title_distance',
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
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'title_align',
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
					'{{WRAPPER}} .tmpcoder-testimonial-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Content
		$this->add_control(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#444444',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-content',
			]
		);

		$this->add_responsive_control(
			'content_distance',
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'content_align',
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
					'{{WRAPPER}} .tmpcoder-testimonial-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Date
		$this->add_control(
			'date_section',
			[
				'label' => esc_html__( 'Date', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-date' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-date',
			]
		);

		$this->add_control(
			'date_align',
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
					'{{WRAPPER}} .tmpcoder-testimonial-date' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Rating
		$this->add_control(
			'rating_section',
			[
				'label' => esc_html__( 'Rating', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'rating_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'sastra-essential-addons-for-elementor' ),
					'bottom' => esc_html__( 'Bottom', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFD726',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-rating i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d8d8d8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-rating i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_score_color',
			[
				'label' => esc_html__( 'Score Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
                'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-rating' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'rating_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-testimonial-rating span' => 'margin-left: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_responsive_control(
			'rating_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
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
					'{{WRAPPER}} .tmpcoder-testimonial-rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'rating_color_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-rating span',
			]
		);

		$this->end_controls_section();	
		
		// Styles
		// Section: Meta -------------
		$this->start_controls_section(
			'section_style_meta',
			[
				'label' => esc_html__( 'Meta', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'meta_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'default' => 'bottom',
				'options' => [
					'top' => esc_html__( 'Top', 'sastra-essential-addons-for-elementor' ),
					'left' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
					'right' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
					'bottom' => esc_html__( 'Bottom', 'sastra-essential-addons-for-elementor' ),
					'extra' => esc_html__( 'Extra', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-testimonial-meta-position-',
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'meta_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-top .tmpcoder-testimonial-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					
					'body:not(.rtl) {{WRAPPER}}.tmpcoder-testimonial-meta-position-left .tmpcoder-testimonial-meta' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}}.tmpcoder-testimonial-meta-position-left .tmpcoder-testimonial-meta' => 'margin-left: {{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-right .tmpcoder-testimonial-meta' => 'margin-left: {{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-bottom .tmpcoder-testimonial-meta' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-extra .tmpcoder-testimonial-content-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_align',
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
				'prefix_class' => 'tmpcoder-testimonial-meta-align-',
				'separator' => 'before',
			]
		);

		// Image
		$this->add_control(
			'image_section',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
                'prefix_class'	=> 'tmpcoder-testimonial-image-position-',
                'condition' => [
                	'meta_position!' => 'extra'
                ]
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 16,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-image img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-top.tmpcoder-testimonial-meta-align-left .tmpcoder-testimonial-content-inner:before,
					{{WRAPPER}}.tmpcoder-testimonial-meta-position-bottom.tmpcoder-testimonial-meta-align-left .tmpcoder-testimonial-content-inner:before' => 'left: calc( {{content_padding.LEFT}}px + {{content_border_width.LEFT}}px + ({{SIZE}}px / 2) );',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-top.tmpcoder-testimonial-meta-align-right .tmpcoder-testimonial-content-inner:before,
					{{WRAPPER}}.tmpcoder-testimonial-meta-position-bottom.tmpcoder-testimonial-meta-align-right .tmpcoder-testimonial-content-inner:before' => 'right: calc( {{content_padding.RIGHT}}px + {{content_border_width.RIGHT}}px + ({{SIZE}}px / 2) );',
					'{{WRAPPER}}.tmpcoder-testimonial-meta-position-left .tmpcoder-testimonial-content-inner:before,
					{{WRAPPER}}.tmpcoder-testimonial-meta-position-right .tmpcoder-testimonial-content-inner:before' => 'top: calc( {{content_padding.TOP}}px + {{content_border_width.TOP}}px + ({{SIZE}}px / 2) );',
				],
			]
		);

		$this->add_responsive_control(
			'image_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-image-position-right .tmpcoder-testimonial-image' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-image-position-left .tmpcoder-testimonial-image' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-testimonial-image-position-center .tmpcoder-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'sastra-essential-addons-for-elementor' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-image img',
			]
		);

		$this->add_control(
			'image_border_radius',
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
					'{{WRAPPER}} .tmpcoder-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Name
		$this->add_control(
			'name_section',
			[
				'label' => esc_html__( 'Name', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-name',
			]
		);

		$this->add_responsive_control(
			'name_distance_top',
			[
				'label' => esc_html__( 'Top Distance', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-name' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_position' => [ 'left', 'right' ],
				],
			]
		);

		$this->add_responsive_control(
			'name_distance_bottom',
			[
				'label' => esc_html__( 'Bottom Distance', 'sastra-essential-addons-for-elementor' ),
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Job
		$this->add_control(
			'job_section',
			[
				'label' => esc_html__( 'Job', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'job_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b7b7b7',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-job' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'job_typography',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-job',
			]
		);

		$this->add_responsive_control(
			'job_distance',
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
					'{{WRAPPER}} .tmpcoder-testimonial-job' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Image
		$this->add_control(
			'logo_section',
			[
				'label' => esc_html__( 'Logo', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'logo_width',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-logo-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
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
					'{{WRAPPER}} .tmpcoder-testimonial-logo-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);
		
		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Social Media -----
		$this->start_controls_section(
			'tmpcoder__section_style_social_media',
			[
				'label' => esc_html__( 'Social Media', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_social_style' );

		$this->start_controls_tab(
			'tab_social_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'social_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#919191',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b5b5b5',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_social_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'social_hover_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#444444',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b5b5b5',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'social_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_box_size',
			[
				'label' => esc_html__( 'Box Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-testimonial-social i' => 'line-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_size',
			[
				'label' => esc_html__( 'Font Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 9,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_gutter',
			[
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social' => 'margin-right: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'social_border_type',
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
					'{{WRAPPER}} .tmpcoder-testimonial-social' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-social' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'social_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'social_border_radius',
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
					'{{WRAPPER}} .tmpcoder-testimonial-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
            'testimonial_style_social_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'social_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-testimonial-social',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Navigation -------
		$this->start_controls_section(
			'tmpcoder__section_style_nav',
			[
				'label' => esc_html__( 'Navigation', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-testimonial-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-arrow' => 'border-color: {{VALUE}};',
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
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-testimonial-arrow:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-arrow:hover' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .tmpcoder-testimonial-arrow' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-testimonial-arrow svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
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
						'min' => 5,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-testimonial-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
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
						'min' => 5,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 21,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .tmpcoder-testimonial-arrow' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_stack_nav_position();

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_dots',
			[
				'label' => esc_html__( 'Pagination', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_dots' );

		$this->start_controls_tab(
			'tab_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'dots_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d1d1d1',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			[
				'label' => esc_html__( 'Active', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'dots_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-dots .slick-active .tmpcoder-testimonial-dot' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'dots_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-dots .slick-active .tmpcoder-testimonial-dot' => 'border-color: {{VALUE}}',
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
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-dot' => 'width: {{SIZE}}{{UNIT}};',
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
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-dot' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);
		// $this->add_responsive_control(
		// 	'dots_size',
		// 	[
		// 		'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => ['px' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 5,
		// 				'max' => 50,
		// 			],
		// 		],				
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 7,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .tmpcoder-testimonial-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
		// 		],
		// 		'render_type' => 'template',
		// 		'separator' => 'before',
		// 	]
		// );

		$this->add_control(
			'dots_border_type',
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
					'{{WRAPPER}} .tmpcoder-testimonial-dot' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'dots_border_width',
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
					'{{WRAPPER}} .tmpcoder-testimonial-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'dots_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'dots_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit'		=> '%',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'dots_gutter',
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
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control_dots_hr();
		
		$this->add_responsive_control(
			'dots_vr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'sastra-essential-addons-for-elementor' ),
				'size_units' => [ '%','px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 1200,
					],
				],											
				'default' => [
					'unit' => '%',
					'size' => 96,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-testimonial-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	public function render_testimonial_image( $item ) {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		$settings[ 'testimonial_image_size' ] = ['id' => $item['testimonial_image']['id']];
		$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'testimonial_image_size' );
		// Update the alt attribute
		$image_html = preg_replace( '/<img(.*?)alt="(.*?)"(.*?)>/i', '<img$1alt="'.$item['testimonial_author'].'"$3>', $image_html );

		if ( ! $image_html ) {
			$image_src = $item['testimonial_image']['url'];
		}

		?>

		<?php if ( ! empty( $item['testimonial_image']['url'] ) ) : ?>
			<div class="tmpcoder-testimonial-image">

				<?php 

				if ( ! $image_html ) {
				?>	
					<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php 
					echo esc_attr( $item['testimonial_author'] ); ?>">
				<?php
				}
				else {
			 		echo wp_kses_post($image_html);
				}

			  	?>

			</div>
		<?php endif; ?>

	<?php
	}

	public function render_pro_element_social_media( $item, $item_count ) {}	

	public function render_testimonial_meta( $item, $item_count ) {
		$logo_element = 'div'; ?>
		
		<div class="tmpcoder-testimonial-meta-content-wrap">
			<?php if ( ! empty( $item['testimonial_author'] ) ) : ?>
				<div class="tmpcoder-testimonial-name"><?php echo wp_kses_post( $item['testimonial_author'] ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_job'] ) ) : ?>
				<div class="tmpcoder-testimonial-job"><?php echo wp_kses_post( $item['testimonial_job'] ); ?></div>
			<?php endif; ?>

			<?php
			if ( ! empty( $item['testimonial_logo_image']['url'] ) ) {
				
				$this->add_render_attribute( 'logo_attribute'. $item_count, 'class', 'tmpcoder-testimonial-logo-image elementor-clearfix' );

				if ( ! empty( $item['testimonial_logo_url']['url'] ) ) {

					$logo_element = 'a';

					$this->add_render_attribute( 'logo_attribute'. $item_count, 'href', $item['testimonial_logo_url']['url'] );

					if ( $item['testimonial_logo_url']['is_external'] ) {
						$this->add_render_attribute( 'logo_attribute'. $item_count, 'target', '_blank' );
					}

					if ( $item['testimonial_logo_url']['nofollow'] ) {
						$this->add_render_attribute( 'logo_attribute'. $item_count, 'nofollow', '' );
					}
				}

				echo wp_kses('<'. esc_attr( $logo_element ) .' '. $this->get_render_attribute_string( 'logo_attribute'. $item_count ) .'>', tmpcoder_wp_kses_allowed_html());

					$settings[ 'testimonial_logo_image' ] = ['id' => $item['testimonial_logo_image']['id']];
					$logo_image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'testimonial_logo_image' );

					// Update the alt attribute
					$logo_image_html = preg_replace( '/<img(.*?)alt="(.*?)"(.*?)>/i', '<img$1alt="'.$item['testimonial_author'].'"$3>', $logo_image_html );

					// Remove the title attribute
					$logo_image_html = preg_replace( '/<img(.*?)title="(.*?)"(.*?)>/i', '<img$1$3>', $logo_image_html );

					echo wp_kses_post($logo_image_html);

				echo '</'. esc_attr( $logo_element ) .'>';

			}

			$this->render_pro_element_social_media( $item, $item_count );

			?>

		</div>
		<?php
	}


	public function tmpcoder_testimonial_content( $item ) {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new ); ?>

		<div class="tmpcoder-testimonial-content-wrap">
			<div class="tmpcoder-testimonial-content-inner">
			<?php if ( $settings['testimonial_icon'] !== 'none' && $settings['testimonial_icon_position'] === 'top' ) : ?>
				<div class="tmpcoder-testimonial-icon">
					<?php echo wp_kses($this->tmpcoder_get_icon( $settings['testimonial_icon'], '' ), tmpcoder_wp_kses_allowed_html() ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_title'] ) ) : ?>
				<div class="tmpcoder-testimonial-title"><?php echo wp_kses_post( $item['testimonial_title'] ); ?></div>
			<?php endif; ?>

			<?php if ( $settings['rating_position'] === 'top' ) : ?>
				<?php $this->render_testimonial_rating( $item ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_content'] ) ) : ?>
				<div class="tmpcoder-testimonial-content">
					<?php if ( $settings['testimonial_icon'] !== 'none' && $settings['testimonial_icon_position'] === 'inner' ) : ?>
					<div class="tmpcoder-testimonial-icon">	
						<?php echo wp_kses($this->tmpcoder_get_icon( $settings['testimonial_icon'], '' ), tmpcoder_wp_kses_allowed_html()); ?>
					</div>
					<?php endif; ?>

					<p><?php echo wp_kses_post($item['testimonial_content']); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $settings['rating_position'] === 'bottom' ) : ?>
				<?php $this->render_testimonial_rating( $item ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_date'] ) ) : ?>
				<div class="tmpcoder-testimonial-date"><?php echo esc_html( $item['testimonial_date'] ); ?></div>
			<?php endif; ?>
			</div>
		</div>

	<?php
	}

	public function render_testimonial_rating( $item ) {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$rating_amount = $item['testimonial_rating_amount'];
		$round_rating = (int)$rating_amount;
		$rating_icon = '&#xE934;';

		if ( 'style_1' === $settings['testimonial_rating_style'] ) {
			if ( 'outline' === $settings['testimonial_unmarked_rating_style'] ) {
				$rating_icon = '&#xE933;';
			}
		} elseif ( 'style_2' === $settings['testimonial_rating_style'] ) {
			$rating_icon = '&#9733;';

			if ( 'outline' === $settings['testimonial_unmarked_rating_style'] ) {
				$rating_icon = '&#9734;';
			}
		}

		if ( 'yes' === $settings['testimonial_rating'] && ! empty( $rating_amount ) ) : ?>	

			<div class="tmpcoder-testimonial-rating">
			<?php for( $i = 1; $i <= $settings['testimonial_rating_scale']; $i++ ) : ?>
				<?php if ( $i <= $rating_amount ) : ?>
					<i class="tmpcoder-rating-icon-full"><?php echo esc_html($rating_icon); ?></i>
				<?php elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) : ?>
					<i class="tmpcoder-rating-icon-<?php echo esc_attr(( $rating_amount - $round_rating ) * 10); ?>"><?php echo esc_html($rating_icon); ?></i>
				<?php else : ?>
					<i class="tmpcoder-rating-icon-empty"><?php echo esc_html($rating_icon); ?></i>
				<?php endif; ?>
	     	<?php endfor; ?>

	     	<?php $this->render_pro_element_testimonial_score($rating_amount); ?>
			</div>

	<?php
		endif;
	}

	public function render_pro_element_testimonial_score($rating_amount) {}

	protected function render() {	
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$item_html = '';
		$item_count = 0;

		if ( empty( $settings['testimonial_items'] ) ) {
			return;
		}
		
		$is_rtl = is_rtl();
		$direction = $is_rtl ? 'rtl' : 'ltr';
		if ( ! tmpcoder_is_availble() ) {

			$settings['testimonial_autoplay'] = '';
			$settings['testimonial_autoplay_duration'] = 0;
			$settings['testimonial_pause_on_hover'] = '';
		}

		$options = [
			'rtl' => $is_rtl,
			'infinite' => ( $settings['testimonial_loop'] === 'yes' ),
			'speed' => absint( $settings['testimonial_effect_duration'] * 1000 ),
			'arrows' => true,
			'dots' => true,
			'autoplay' => ( $settings['testimonial_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( $settings['testimonial_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['testimonial_pause_on_hover'],
			'prevArrow' => '#tmpcoder-testimonial-prev-'. $this->get_id(),
			'nextArrow' => '#tmpcoder-testimonial-next-'. $this->get_id(),
			'sliderSlidesToScroll' => +$settings['testimonial_slides_to_scroll'],
		];

		$this->add_render_attribute( 'testimonial-caousel-attribute', [
			'class' => 'tmpcoder-testimonial-carousel',
			'dir' => esc_attr( $direction ),
			'data-slick' => wp_json_encode( $options ),
		] );

		$slider_nav_icon_location = '';
		// if (isset($settings['slider_nav_icon_location']) && !empty($settings['slider_nav_icon_location']) && $settings['slider_nav_icon_location'] != '') {
			
		// 	$slider_nav_icon_location = $settings['slider_nav_icon_location'];
		// }

		?>
		<div class="tmpcoder-testimonial-carousel-wrap <?php echo esc_attr($slider_nav_icon_location ?? '', 'sastra-essential-addons-for-elementor') ?>">
			
			<?php echo wp_kses_post('<div '. $this->get_render_attribute_string( 'testimonial-caousel-attribute' ).' data-slide-effect="'. esc_attr($settings['testimonial_effect'] ?? '').'">'); ?>
					
					<?php foreach ( $settings['testimonial_items'] as $key => $item ) : ?>

						<?php if ( ! tmpcoder_is_availble() && $key === 4 ) { break; } ?>
					
						<div class="tmpcoder-testimonial-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> elementor-clearfix">
							
							<div class="tmpcoder-testimonial-meta elementor-clearfix">
								<div class="tmpcoder-testimonial-meta-inner">
								<?php 
								$this->render_testimonial_image( $item );
								if (  $settings['meta_position'] !== 'extra' ) {
									$this->render_testimonial_meta( $item, $item_count );
								}
								?>
								</div>
							</div>

							<?php $this->tmpcoder_testimonial_content( $item ); ?>

							<?php if ( $settings['meta_position'] === 'extra' ) : ?>
								<div class="tmpcoder-testimonial-meta elementor-clearfix">
									<div class="tmpcoder-testimonial-meta-inner">
									<?php 
									if (  $settings['meta_position'] !== 'extra' ) {
										$this->render_testimonial_image( $item );
									}
									$this->render_testimonial_meta( $item, $item_count );
									?>
									</div>	
								</div>
							<?php endif; ?>

						</div>
						<?php
						$item_count++;
					endforeach;
					?>
			</div>

			<div class="tmpcoder-testimonial-controls">
				<div class="tmpcoder-testimonial-dots"></div>
			</div>

			<div class="tmpcoder-testimonial-arrow-container">
				<div class="tmpcoder-testimonial-prev-arrow tmpcoder-testimonial-arrow" id="<?php echo 'tmpcoder-testimonial-prev-'. esc_attr($this->get_id()); ?>">
					<?php echo wp_kses($this->tmpcoder_get_icon( $settings['testimonial_nav_icon'] ?? '', '' ) ?? '', tmpcoder_wp_kses_allowed_html());  ?>
				</div>
				<div class="tmpcoder-testimonial-next-arrow tmpcoder-testimonial-arrow" id="<?php echo 'tmpcoder-testimonial-next-'. esc_attr($this->get_id()); ?>">
					<?php echo wp_kses($this->tmpcoder_get_icon( $settings['testimonial_nav_icon'], '' ), tmpcoder_wp_kses_allowed_html() );  ?>
				</div>
			</div>
		</div>

	<?php
	}

	function tmpcoder_get_icon( $icon, $dir ) {
		if ( false !== strpos( $icon, 'svg-' ) ) {
			return $this->tmpcoder_get_svg_icon( $icon, $dir );

		} elseif ( false !== strpos( $icon, 'fa-' ) ) {
			$dir = '' !== $dir ? '-'. $dir : '';
			return wp_kses('<i class="'. esc_attr($icon . $dir) .'"></i>', [
				'i' => [
					'class' => []
				]
			]);
		} else {
			return '';
		}
	}

	/**
	** Get SVG Icon
	*/
	function tmpcoder_get_svg_icon( $icon, $dir ) {
		$style_attr = '';

		// Rotate Right
		if ( 'right' === $dir ) {
			$style_attr = 'style="transform: rotate(180deg); -webkit-transform: rotate(180deg);" ';
		}

		$icons = [
			// Arrows
			'svg-angle-1-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 283.4 512" style="enable-background:new 0 0 283.4 512;" xml:space="preserve"><g><polygon class="st0" points="54.5,256.3 283.4,485.1 256.1,512.5 0,256.3 0,256.3 27.2,229 256.1,0 283.4,27.4 "/></g></svg>', 
			'svg-angle-2-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 303.3 512" style="enable-background:new 0 0 303.3 512;" xml:space="preserve"><g><polygon class="st0" points="94.7,256 303.3,464.6 256,512 47.3,303.4 0,256 47.3,208.6 256,0 303.3,47.4 "/></g></svg>', 
			'svg-angle-3-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 291.4 512" style="enable-background:new 0 0 291.4 512;" xml:space="preserve"><g><path class="st0" d="M281.1,451.5c13.8,13.8,13.8,36.3,0,50.1c-13.8,13.8-36.3,13.8-50.1,0L10.4,281C3.5,274.1,0,265.1,0,256c0-9.1,3.5-18.1,10.4-25L231,10.4c13.8-13.8,36.3-13.8,50.1,0c6.9,6.9,10.4,16,10.4,25s-3.5,18.1-10.4,25L85.5,256L281.1,451.5z"/></g></svg>', 
			'svg-angle-4-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 259.6 512" style="enable-background:new 0 0 259.6 512;" xml:space="preserve"><g><path class="st0" d="M256.6,18.1L126.2,256.1l130.6,237.6c3.6,5.6,3.9,10.8,0.2,14.9c-0.2,0.2-0.2,0.3-0.3,0.3s-0.3,0.3-0.3,0.3c-3.9,3.9-10.3,3.6-14.2-0.3L2.9,263.6c-2-2.1-3.1-4.7-2.9-7.5c0-2.8,1-5.6,3.1-7.7L242,3.1c4.1-4.1,10.6-4.1,14.6,0l0,0C260.7,7.3,260.5,10.9,256.6,18.1z"/></g></svg>', 
			'svg-arrow-1-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 338.4" style="enable-background:new 0 0 512 338.4;" xml:space="preserve"><g><polygon class="st0" points="511.4,183.1 53.4,183.1 188.9,318.7 169.2,338.4 0,169.2 169.2,0 188.9,19.7 53.4,155.3 511.4,155.3 "/></g></svg>', 
			'svg-arrow-2-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 320.6" style="enable-background:new 0 0 512 320.6;" xml:space="preserve"><g><polygon class="st0" points="512,184.4 92.7,184.4 194.7,286.4 160.5,320.6 34.3,194.4 34.3,194.4 0,160.2 160.4,0 194.5,34.2 92.7,136 512,136 "/></g></svg>', 
			'svg-arrow-3-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 499.6 320.6" style="enable-background:new 0 0 499.6 320.6;" xml:space="preserve"><g><path class="st0" d="M499.6,159.3c0.3,7-2.4,13.2-7,17.9c-4.3,4.3-10.4,7-16.9,7H81.6l95.6,95.6c9.3,9.3,9.3,24.4,0,33.8c-4.6,4.6-10.8,7-16.9,7c-6.1,0-12.3-2.4-16.9-7L6.9,177.2c-9.3-9.3-9.3-24.4,0-33.8l16.9-16.9l0,0L143.3,6.9c9.3-9.3,24.4-9.3,33.8,0c4.6,4.6,7,10.8,7,16.9s-2.4,12.3-7,16.9l-95.6,95.6h393.7C488.3,136.3,499.1,146.4,499.6,159.3z"/></g></svg>', 
			'svg-arrow-4-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 499.6 201.3" style="enable-background:new 0 0 499.6 201.3;" xml:space="preserve"><g><polygon class="st0" points="0,101.1 126,0 126,81.6 499.6,81.6 499.6,120.8 126,120.8 126,201.3 "/></g></svg>', 
		
			// Blockquote
			'svg-blockquote-1' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 406.1" style="enable-background:new 0 0 512 406.1;" xml:space="preserve"><g><g id="Layer_2_1_" class="st0"><path class="st1" d="M510.6,301.8c0,57.6-46.7,104.3-104.3,104.3c-12.6,0-24.7-2.3-36-6.4c-28.3-9.1-64.7-29.1-82.8-76.3C218.9,145.3,477.7,0.1,477.7,0.1l6.4,12.3c0,0-152.4,85.7-132.8,200.8C421.8,170.3,510.1,220.2,510.6,301.8z"/><path class="st1" d="M234.6,301.8c0,57.6-46.7,104.3-104.3,104.3c-12.6,0-24.7-2.3-36-6.4c-28.3-9.1-64.7-29.1-82.8-76.3C-57.1,145.3,201.8,0.1,201.8,0.1l6.4,12.3c0,0-152.4,85.7-132.8,200.8C145.9,170.3,234.1,220.2,234.6,301.8z"/></g></g></svg>',
			'svg-blockquote-2' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 415.9" style="enable-background:new 0 0 512 415.9;" xml:space="preserve"><g><g class="st0"><polygon class="st1" points="512,0 303.1,208 303.1,415.9 512,415.9 "/><polygon class="st1" points="208.9,0 0,208 0,415.9 208.9,415.9 "/></g></g></svg>',
			'svg-blockquote-3' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 369.3" style="enable-background:new 0 0 512 369.3;" xml:space="preserve"><g><g class="st0"><polygon class="st1" points="240.7,0 240.7,240.5 88.1,369.3 88.1,328.3 131.4,240.5 0.3,240.5 0.3,0 "/><polygon class="st1" points="512,43.3 512,238.6 388.1,343.2 388.1,310 423.2,238.6 316.7,238.6 316.7,43.3 "/></g></g></svg>',
			'svg-blockquote-4' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 369.3" style="enable-background:new 0 0 512 369.3;" xml:space="preserve"><g><g class="st0"><g><path class="st1" d="M469.1,299.1c-62,79.7-148.7,69.8-148.7,69.8v-86.5c0,0,42.6-0.6,77.5-35.4c20.3-20.3,22.7-65.6,22.8-81.4h-101V-10.9H512v176.6C512.2,184.7,509.4,247.2,469.1,299.1z"/></g><g><path class="st1" d="M149.3,299.1c-62,79.7-148.7,69.8-148.7,69.8v-86.5c0,0,42.6-0.6,77.5-35.4c20.3-20.3,22.7-65.6,22.8-81.4H0V-10.9h192.2v176.6C192.4,184.7,189.7,247.2,149.3,299.1z"/></g></g></g></svg>',
			'svg-blockquote-5' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 422.1" style="enable-background:new 0 0 512 422.1;" xml:space="preserve"><g><g class="st0"><polygon class="st1" points="237,0 237,223.7 169.3,422.1 25.7,422.1 53.4,223.7 0,223.7 0,0 "/><polygon class="st1" points="512,0 512,223.7 444.3,422.1 300.7,422.1 328.4,223.7 275,223.7 275,0 "/></g></g></svg>',
			
			// Sharing
			'svg-sharing-1' => '<?xml version="1.0" ?><svg style="enable-background:new 0 0 48 48;" version="1.1" viewBox="0 0 48 48" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Icons"><g id="Icons_15_"><g><path d="M25.03766,20.73608v-3.7207c0-0.3799,0.4135-0.6034,0.7263-0.4023l9.3855,5.9218     c0.3017,0.19,0.3017,0.6146,0,0.8045l-5.1844,3.2738l-1.8659,1.1843l-2.3352,1.4749c-0.3129,0.2011-0.7263-0.0335-0.7263-0.4022     v-3.2403v-0.4916" style="fill:#5F83CF;"/><path d="M29.96506,26.61318l-1.8659,1.1843l-2.3352,1.4749c-0.3128,0.2011-0.7263-0.0335-0.7263-0.4022     v-3.2403v-0.4916c-2.5759,0.1057-5.718-0.3578-7.8439,0.6112c-1.9663,0.8963-3.5457,2.5639-4.2666,4.6015     c-0.1282,0.3623-0.2296,0.7341-0.3029,1.1114v-2.9721c0-1.128,0.2449-2.2513,0.7168-3.2759     c0.4588-0.9961,1.1271-1.8927,1.948-2.6196c0.8249-0.7306,1.8013-1.2869,2.8523-1.6189     c1.5111-0.4774,3.1532-0.4118,4.7155-0.3096c0.7252,0.0475,1.4538,0.0698,2.1808,0.0698" style="fill:#5F83CF;"/></g></g></g></svg>',
			'svg-sharing-2' => '<?xml version="1.0" ?><svg style="enable-background:new 0 0 48 48;" version="1.1" viewBox="0 0 48 48" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Icons"><g id="Icons_16_"><g><path d="M27.775,21.64385L27.775,21.64385l1-0.01h1v1.65l2.17-1.38l0.1-0.06l2.95-1.87l-5.22-3.29v0.87     v0.77h-1l-1-0.02l0,0" style="fill:#5F83CF;"/><path d="M28.775,18.32385c-0.33,0-0.67-0.01-1-0.02c-0.22-0.01-0.43-0.02-0.65-0.04     c-1.3358-0.0496-2.5105-0.0408-3.55,0.24c-0.5,0.16-0.97,0.38-1.41,0.67c-0.26,0.16-0.51,0.34-0.74,0.55     c-0.62,0.54-1.12,1.22-1.47,1.97c-0.35,0.77-0.54,1.62-0.54,2.47v2.24c0.06-0.29,0.13-0.57,0.23-0.84     c0.54-1.53,1.73-2.79,3.22-3.47c1.34-0.61,3.21-0.47,4.91-0.45c0.35,0,0.68,0,1-0.01" style="fill:#5F83CF;"/><path d="M31.945,23.63175l-1.8884,1.1873v3.8702c0,0.5422-0.5142,0.991-1.1499,0.991H16.0432     c-0.6357,0-1.1498-0.4488-1.1498-0.991v-8.7689c0-0.5515,0.5142-1.0002,1.1498-1.0002h3.5525h0.0037     c0.0561-0.0748,0.1739-0.2057,0.2393-0.2618c0.6731-0.5983,1.4864-1.0657,2.3465-1.3368     c0.0467-0.0187,0.0935-0.0281,0.1402-0.0374h-6.2821c-1.6734,0-3.0383,1.1872-3.0383,2.6362v8.7689     c0,1.449,1.3649,2.6269,3.0383,2.6269h12.8634c1.6734,0,3.0383-1.1779,3.0383-2.6269V23.63175z" style="fill:#F2F2F2;"/></g></g></g></svg>',

			'svg-icons' => '',
			
		];
		
		return $icons[$icon];
	}

	/**
	** Get SVG Icons Array
	*/

	function tmpcoder_get_svg_icons_array( $stack, $fa_icons ) {
		$svg_icons = [];

		if ( 'arrows' === $stack ) {
			$svg_icons['svg-angle-1-left'] = esc_html__( 'Angle', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-angle-2-left'] = esc_html__( 'Angle Bold', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-angle-3-left'] = esc_html__( 'Angle Bold Round', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-angle-4-left'] = esc_html__( 'Angle Plane', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-arrow-1-left'] = esc_html__( 'Arrow', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-arrow-2-left'] = esc_html__( 'Arrow Bold', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-arrow-3-left'] = esc_html__( 'Arrow Bold Round', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-arrow-4-left'] = esc_html__( 'Arrow Caret', 'sastra-essential-addons-for-elementor' );

		} elseif ( 'blockquote' === $stack ) {
			$svg_icons['svg-blockquote-1'] = esc_html__( 'Blockquote Round', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-blockquote-2'] = esc_html__( 'Blockquote ST', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-blockquote-3'] = esc_html__( 'Blockquote BS', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-blockquote-4'] = esc_html__( 'Blockquote Edges', 'sastra-essential-addons-for-elementor' );
			$svg_icons['svg-blockquote-5'] = esc_html__( 'Blockquote Quad', 'sastra-essential-addons-for-elementor' );

		} elseif ( 'sharing' === $stack ) {
			// $svg_icons['svg-sharing-1'] = esc_html__( 'sharing 1', 'sastra-essential-addons-for-elementor' );
			// $svg_icons['svg-sharing-2'] = esc_html__( 'sharing 2', 'sastra-essential-addons-for-elementor' );
		}

		// Merge FontAwesome and SVG icons
		return array_merge( $fa_icons, $svg_icons );
	}
}