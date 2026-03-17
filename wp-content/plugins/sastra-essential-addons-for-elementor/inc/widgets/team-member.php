<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Team_Member extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-team-member';
	}

	public function get_title() {
		return esc_html__( 'Team Member', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-image-box';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'team member' ];
	}

	public function get_style_depends() {
		return [ 'tmpcoder-animations-css', 'tmpcoder-button-animations-css', 'tmpcoder-team-member' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_section_layout() {}

	public function add_section_image_overlay() {}

	public function add_section_style_overlay() {}

	protected function register_controls() {

		// Section: General ----------
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'member_image',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => TMPCODER_ADDONS_ASSETS_URL.'images/team-member.jpg',
				],
			]
		);

		$this->add_control(
			'member_name',
			[
				'label' => esc_html__( 'Name', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'John Doe',
			]
		);

		$this->add_control(
			'member_name_tag',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'default' => 'h3',
				'options' => [
					'h1' => esc_html__( 'H1', 'sastra-essential-addons-for-elementor' ),
					'h2' => esc_html__( 'H2', 'sastra-essential-addons-for-elementor' ),
					'h3' => esc_html__( 'H3', 'sastra-essential-addons-for-elementor' ),
					'h4' => esc_html__( 'H4', 'sastra-essential-addons-for-elementor' ),
					'h5' => esc_html__( 'H5', 'sastra-essential-addons-for-elementor' ),
					'h6' => esc_html__( 'H6', 'sastra-essential-addons-for-elementor' ),
					'div' => esc_html__( 'div', 'sastra-essential-addons-for-elementor' ),
					'span' => esc_html__( 'span', 'sastra-essential-addons-for-elementor' ),
					'p' => esc_html__( 'p', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'member_job',
			[
				'label' => esc_html__( 'Job', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sony CEO',
			]
		);

		$this->add_control(
			'member_description',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laorsoet non vitae lorem.',
			]
		);

		$this->add_control(
            'member_description_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_control(
			'member_divider',
			[
				'label' => esc_html__( 'Divider', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

        $this->add_control(
			'member_divider_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'before_job' => esc_html__( 'Before Job', 'sastra-essential-addons-for-elementor' ),
					'after_job' => esc_html__( 'After Job', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'after_job',
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'team_member_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Postioning Elements over Media and <br>Media Overlay</span> options are available<br> in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-team-member-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Section: Layout -----------
		$this->add_section_layout();

		// Section: Social Media -----
		$this->start_controls_section(
			'section_social_media',
			[
				'label' => esc_html__( 'Social Media', 'sastra-essential-addons-for-elementor' ),
			]
		);
		$this->add_control(
			'social_media',
			[
				'label' => esc_html__( 'Show Social Media', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'social_media_is_external',
			[
				'label' => esc_html__( 'Open in new window', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_media_nofollow',
			[
				'label' => esc_html__( 'Add nofollow', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_1',
			[
				'label' => esc_html__( 'Social 1', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_1',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-facebook-f',
					'library' => 'fa-brands',
				],
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_1',
			[
				'label' => esc_html__( 'Social URL', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_2',
			[
				'label' => esc_html__( 'Social 2', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_2',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-twitter',
					'library' => 'fa-brands',
				],
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_2',
			[
				'label' => esc_html__( 'Social URL', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_3',
			[
				'label' => esc_html__( 'Social 3', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_3',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-linkedin-in',
					'library' => 'fa-brands',
				],
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_3',
			[
				'label' => esc_html__( 'Social URL', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_4',
			[
				'label' => esc_html__( 'Social 4', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_4',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_4',
			[
				'label' => esc_html__( 'Social URL', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_5',
			[
				'label' => esc_html__( 'Social 5', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_5',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_5',
			[
				'label' => esc_html__( 'Social URL', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Section: Buttom -----------
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'member_btn',
			[
				'label' => esc_html__( 'Show Button', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'member_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'About Me',
				'condition' => [
					'member_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'member_btn_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'condition' => [
					'member_btn' => 'yes',
				],
				'show_label' => false,
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Overlay ---------------
		$this->add_section_image_overlay();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'team-member', [
			'Advanced Layout options - Move Elements over Image (Title, Job, Social Icons, etc...)',
			'Advanced Image Overlay Hover Animations',
		] );
		
		// Styles
		// Section: Image ------------
		$this->start_controls_section(
			'tmpcoder__section_style_image',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-media' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'image_align',
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
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-media' => 'text-align: -webkit-{{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'sastra-essential-addons-for-elementor' ),
				'default' => 'solid',
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
				'selector' => '{{WRAPPER}} .tmpcoder-member-media',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-member-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tmpcoder-member-content'
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 20,
					'right' => 15,
					'bottom' => 50,
					'left' => 15,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
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
					'{{WRAPPER}} .tmpcoder-member-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-member-name',
			]
		);

		$this->add_responsive_control(
			'name_distance',
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
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-name' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'name_align',
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
					'{{WRAPPER}} .tmpcoder-member-name' => 'text-align: {{VALUE}};',
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
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-job' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'job_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-member-job',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-job' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'job_align',
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
					'{{WRAPPER}} .tmpcoder-member-job' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Description
		$this->add_control(
			'description_section',
			[
				'label' => esc_html__( 'Description', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#545454',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-member-description',
			]
		);

		$this->add_responsive_control(
			'description_distance',
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'description_align',
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
					'{{WRAPPER}} .tmpcoder-member-description' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Divider
		$this->add_control(
			'divider_section',
			[
				'label' => esc_html__( 'Divider', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d1d1d1',				
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-divider:after' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_type',
			[
				'label' => esc_html__( 'Style', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'sastra-essential-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'sastra-essential-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'sastra-essential-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'sastra-essential-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'solid',		
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-divider:after' => 'border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => esc_html__( 'Weight', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-divider:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-divider:after' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_distance',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-divider:after' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],	
			]
		);

		$this->add_control(
			'divider_align',
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
                'prefix_class'	=> 'tmpcoder-team-member-divider-',
				'condition' => [
					'member_divider' => 'yes',
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
				'condition' => [
					'social_media' => 'yes',
				],
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
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-member-social svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social' => 'border-color: {{VALUE}}',
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
				'default' => '#045CB4',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-member-social:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#045CB4',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'social_trans_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

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
					'{{WRAPPER}} .tmpcoder-member-social' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-member-social svg' => 'transition-duration: {{VALUE}}s',
				],
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
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 17,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social' => 'font-size: {{SIZE}}{{UNIT}};',
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
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 37,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-member-social i' => 'line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-member-social svg' => 'line-height: {{SIZE}}{{UNIT}};',
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
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social' => 'margin-right: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_responsive_control(
			'social_distance',
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social-media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_align',
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
                'prefix_class'	=> 'tmpcoder-team-member-social-media-',
				'separator' => 'before',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social' => 'border-style: {{VALUE}};',
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
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .tmpcoder-member-social',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Button -----------
		$this->start_controls_section(
			'tmpcoder__section_style_btn',
			[
				'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'member_btn' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-member-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#045CB4',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-btn.tmpcoder-button-none:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-member-btn:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-member-btn:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#045CB4',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-member-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_section_anim_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'btn_animation',
			[
				'label' => esc_html__( 'Select Animation', 'sastra-essential-addons-for-elementor' ),
				'type' => 'tmpcoder-button-animations',
				'default' => 'tmpcoder-button-none',
			]
		);

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-btn' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-member-btn:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-member-btn:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'btn_animation_height',
			[
				'label' => esc_html__( 'Animation Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [					
					'{{WRAPPER}} [class*="tmpcoder-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} [class*="tmpcoder-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'btn_animation' => [ 
						'tmpcoder-button-underline-from-left',
						'tmpcoder-button-underline-from-center',
						'tmpcoder-button-underline-from-right',
						'tmpcoder-button-underline-reveal',
						'tmpcoder-button-overline-reveal',
						'tmpcoder-button-overline-from-left',
						'tmpcoder-button-overline-from-center',
						'tmpcoder-button-overline-from-right'
					]
				],
			]
		);

		$this->add_control(
			'btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-member-btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_align',
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
					'{{WRAPPER}} .tmpcoder-member-btn-wrap' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 8,
					'right' => 35,
					'bottom' => 8,
					'left' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-member-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_type',
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
					'{{WRAPPER}} .tmpcoder-member-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_border_width',
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
					'{{WRAPPER}} .tmpcoder-member-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius',
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
					'{{WRAPPER}} .tmpcoder-member-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Overlay ---------------
		$this->add_section_style_overlay();

	}

	protected function team_member_social_media() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		
		if ( (!empty($settings['social_icon_1']['value']) && '' !== $settings['social_icon_1']['value']) || (!empty($settings['social_icon_2']['value']) && '' !== $settings['social_icon_2']['value']) || (!empty($settings['social_icon_3']['value']) && '' !== $settings['social_icon_3']['value']) || (!empty($settings['social_icon_4']['value']) && '' !== $settings['social_icon_4']['value']) || (!empty($settings['social_icon_5']['value']) && '' !== $settings['social_icon_5']['value']) ) : 
		
		$this->add_render_attribute( 'social_attribute', 'class', 'tmpcoder-member-social' );
				
		if ( $settings['social_media_is_external'] ?? false ) {
			$this->add_render_attribute( 'social_attribute', 'target', '_blank' );
		}

		if ( $settings['social_media_nofollow'] ?? false ) {
			$this->add_render_attribute( 'social_attribute', 'nofollow', '' );
		}

		?>

		<div class="tmpcoder-member-social-media">
			
			<?php if ( $settings['social_icon_1']['value'] ?? '' ) : ?>
				<?php echo wp_kses_post('<a href="'.esc_url( $settings['social_url_1']['url'] ?? '' ).' " '.$this->get_render_attribute_string( 'social_attribute' ).'>'); ?>

					<?php 

					if (is_array($settings['social_icon_1']['value'] ?? '')) {
						echo wp_kses(tmpcoder_render_svg_icon($settings['social_icon_1'] ?? ''),tmpcoder_wp_kses_allowed_html());
					}
					else
					{
					?>
					<i class="<?php echo esc_html( $settings['social_icon_1']['value'] ?? '' ); ?>"></i>
					<?php } ?>

				</a>
			<?php endif; ?>
		
			<?php if ( $settings['social_icon_2']['value'] ?? '' ) : ?>
				<?php echo wp_kses_post('<a href="'.esc_url( $settings['social_url_2']['url'] ?? '' ).' " '.$this->get_render_attribute_string( 'social_attribute' ).'>'); ?>
					<?php 
					if (is_array($settings['social_icon_2']['value'] ?? '')) {
						echo wp_kses(tmpcoder_render_svg_icon($settings['social_icon_2'] ?? ''),tmpcoder_wp_kses_allowed_html());
					}
					else
					{ ?>
					<i class="<?php echo esc_html( $settings['social_icon_2']['value'] ?? '' ); ?>"></i>
					<?php } ?>
				</a>
			<?php endif; ?>

			<?php if ( $settings['social_icon_3']['value'] ?? '' ) : ?>
				<?php echo wp_kses_post('<a href="'.esc_url( $settings['social_url_3']['url'] ?? '' ).' " '.$this->get_render_attribute_string( 'social_attribute' ).'>'); ?>
					<?php
					if (is_array($settings['social_icon_3']['value'] ?? '')) {
						echo wp_kses(tmpcoder_render_svg_icon($settings['social_icon_3'] ?? ''),tmpcoder_wp_kses_allowed_html());
					}
					else
					{
						?>
						<i class="<?php echo esc_html( $settings['social_icon_3']['value'] ?? '' ); ?>"></i>
						<?php
					} 
					?>
				</a>
			<?php endif; ?>

			<?php if ( $settings['social_icon_4']['value'] ?? '' ) : ?>
				<?php echo wp_kses_post('<a href="'.esc_url( $settings['social_url_4']['url'] ?? '' ).' " '.$this->get_render_attribute_string( 'social_attribute' ).'>'); ?>

					<?php
					if (is_array($settings['social_icon_4']['value'] ?? '')) {
						echo wp_kses(tmpcoder_render_svg_icon($settings['social_icon_4'] ?? ''),tmpcoder_wp_kses_allowed_html());
					}
					else
					{ ?>
					<i class="<?php echo esc_html( $settings['social_icon_4']['value'] ?? '' ); ?>"></i>
					<?php } ?>
				</a>
			<?php endif; ?>

			<?php if ( $settings['social_icon_5']['value'] ?? '' ) : ?>
				<?php echo wp_kses_post('<a href="'.esc_url( $settings['social_url_5']['url'] ?? '' ).' " '.$this->get_render_attribute_string( 'social_attribute' ).'>'); ?>
					<?php
					if (is_array($settings['social_icon_5']['value'] ?? '')) {
						echo wp_kses(tmpcoder_render_svg_icon($settings['social_icon_5'] ?? ''),tmpcoder_wp_kses_allowed_html());
					}
					else
					{ ?>
					<i class="<?php echo esc_html( $settings['social_icon_5']['value'] ?? '' ); ?>"></i>
					<?php } ?>
				</a>
			<?php endif; ?>

		</div>
		
		<?php endif;
	}

	protected function team_member_button() {
		// Get Settings 
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		
		if ( !empty($settings['member_btn_text']) && '' !== ($settings['member_btn_text'] ?? '') ) {

			$this->add_render_attribute( 'btn_attribute', 'class', 'tmpcoder-member-btn tmpcoder-button-effect '. $this->get_settings()['btn_animation'] );
			$this->add_render_attribute( 'btn_attribute', 'href', $settings['member_btn_url']['url'] ?? '' );

			if ( $settings['member_btn_url']['is_external'] ?? false ) {
				$this->add_render_attribute( 'btn_attribute', 'target', '_blank' );
			}

			if ( $settings['member_btn_url']['nofollow'] ?? false ) {
				$this->add_render_attribute( 'btn_attribute', 'nofollow', '' );
			}

			echo '<div class="tmpcoder-member-btn-wrap">';
				echo wp_kses_post('<a '. $this->get_render_attribute_string( 'btn_attribute' ) .'>');
					echo '<span>'. esc_html($settings['member_btn_text'] ?? '') .'</span>';
				echo '</a>';
			echo '</div>';

		}
	}

	protected function team_member_content() {
		// Get Settings 
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		if ( ! tmpcoder_is_availble() ) {
			$settings['member_name_location'] = 'below';
			$settings['member_job_location'] = 'below';
			$settings['member_description_location'] = 'below';
			$settings['member_social_media_location'] = 'below';
			$settings['member_btn_location'] = 'below';
			$settings['member_divider_location'] = 'below';
		}
		
		if ( ( (!empty($settings['member_name']) && '' !== $settings['member_name']) && 'below' === $settings['member_name_location'] ) || 
			( (!empty($settings['member_job']) && '' !== $settings['member_job']) && 'below' === $settings['member_job_location'] ) || 
			( (!empty($settings['member_description']) && '' !== $settings['member_description']) && 'below' === $settings['member_description_location'] ) || 
			( 'yes' === $settings['social_media'] && 'below' === $settings['member_social_media_location'] ) || 
			( 'yes' === $settings['member_btn'] && 'below' === $settings['member_btn_location'] ) ) : ?>

		<div class="tmpcoder-member-content">
			<?php
				if ( (!empty($settings['member_name']) && '' !== $settings['member_name']) && 'below' === $settings['member_name_location'] ) {
					echo '<'. esc_attr( tmpcoder_validate_html_tag($settings['member_name_tag']) ) .' class="tmpcoder-member-name">';
						echo wp_kses_post( $settings['member_name'] );
					echo '</'. esc_attr( tmpcoder_validate_html_tag($settings['member_name_tag']) ) .'>';
				}
			?>

			<?php if ( 'yes' === $settings['member_divider'] && 'below' === $settings['member_divider_location'] && 'before_job' === $settings['member_divider_position'] ) : ?>
				<div class="tmpcoder-member-divider"></div>
			<?php endif; ?>

			<?php if ( (!empty($settings['member_job']) && '' !== $settings['member_job']) && 'below' === $settings['member_job_location'] ) : ?>
				<div class="tmpcoder-member-job"><?php echo esc_html( $settings['member_job'] ); ?></div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['member_divider'] && 'below' === $settings['member_divider_location'] && 'after_job' === $settings['member_divider_position'] ) : ?>
				<div class="tmpcoder-member-divider"></div>
			<?php endif; ?>

			<?php if ( (!empty($settings['member_description']) && '' !== $settings['member_description']) && 'below' === $settings['member_description_location'] ) : ?>
				<div class="tmpcoder-member-description"><?php echo wp_kses_post( $settings['member_description'] ); ?></div>
			<?php endif; ?>
			
			<?php 
				if( 'yes' === $settings['social_media'] && 'below' === $settings['member_social_media_location'] ) {
					$this->team_member_social_media();
				}
	 			
	 			if ( 'yes' === $settings['member_btn'] && 'below' === $settings['member_btn_location'] ) {
	 				$this->team_member_button();
	 			}
 			?>
		</div>

		<?php endif;

	}

	protected function team_member_overlay() {}

	protected function render() {
	// Get Settings 
	$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
	
	?>

	<div class="tmpcoder-team-member">
		
		<?php if ( !empty($settings['member_image']['url']) && '' !== $settings['member_image']['url'] ) : ?>
			<?php 

				$src = $settings['member_image']['url'];

				$settings[ 'image_size' ] = ['id' => $settings['member_image']['id']];
				$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size' );
				// Update the alt attribute
				$image_html = preg_replace( '/<img(.*?)alt="(.*?)"(.*?)>/i', '<img$1alt="'.$settings['member_name'].'"$3>', $image_html );
			?>

			<div class="tmpcoder-member-media">
				<div class="tmpcoder-member-image">
					<?php 
						if ($image_html) {
							echo wp_kses_post($image_html); 
						}
						else
						{
							echo "<img src='".esc_url($src)."'>";
						}
					?>
				</div>
				<?php $this->team_member_overlay(); ?>
			</div>
		<?php endif; ?>
		
		<?php $this->team_member_content(); ?>
	</div>

	<?php
	}

	public function render_th_icon($item) {
		ob_start();
		\Elementor\Icons_Manager::render_icon($item, ['aria-hidden' => 'true']);
		return ob_get_clean();
	}
}