<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Pricing_Table extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-pricing-table';
	}

	public function get_title() {
		return esc_html__( 'Pricing Table', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-price-table';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'price table', 'pricing table', 'features table' ];
	}

	public function get_style_depends() {

		$depends = [ 'tmpcoder-button-animations-css' => true, 'tmpcoder-pricing-table' => true ];

		if ( ! tmpcoder_elementor()->preview->is_preview_mode() ) {
			$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

			if ( $settings['btn_animation'] == 'tmpcoder-button-none' ) {
				unset( $depends['tmpcoder-button-animations-css'] );
			}
		}

		return array_keys($depends);
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

	public function add_repeater_args_feature_tooltip() {
		return [
			// Translators: %s is the icon.
			'label' => sprintf( __( 'Show Tooltip %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
			'type' => Controls_Manager::SWITCHER,
			'classes' => 'tmpcoder-pro-control'
		];
	}

	public function add_repeater_args_feature_tooltip_text() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_feature_tooltip_show_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_contro_stack_feature_tooltip_section() {}

	public function add_controls_group_feature_even_bg() {
		$this->add_control(
			'feature_even_bg',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Enable Even Color %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control no-distance'
			]
		);
	}

	protected function register_controls() {

		// Section: Elements ---------
		$this->start_controls_section(
			'section_pricing_items',
			[
				'label' => esc_html__( 'Price Table', 'sastra-essential-addons-for-elementor' ),
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		if ( ! tmpcoder_is_availble() ) {
			$this->add_control(
				'pricing_table_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Feature Item Tooltip and Even/Odd Feature Item Background Color</span> options are available in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-pricing-table-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$repeater = new Repeater();

		$repeater->add_control(
			'type_select',
			[
				'label' => esc_html__( 'Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'heading',
				'options' => [
					'heading' => esc_html__( 'Heading', 'sastra-essential-addons-for-elementor' ),
					'price' => esc_html__( 'Price', 'sastra-essential-addons-for-elementor' ),
					'feature' => esc_html__( 'Feature', 'sastra-essential-addons-for-elementor' ),
					'text' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
					'button' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
					'divider' => esc_html__( 'Divider', 'sastra-essential-addons-for-elementor' ),
				],
				'separator' => 'after',
			]
		);

		$repeater->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Awesome Title',
				'condition' => [
					'type_select' => 'heading',
				],
			]
		);

		$repeater->add_control(
			'heading_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Subtitle text',
				'condition' => [
					'type_select' => 'heading',
				],
			]
		);

		$repeater->add_control(
			'heading_icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'icon' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
					'image' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'type_select' => 'heading',
				],

			]
		);

		$repeater->add_control(
			'heading_image',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type_select' => 'heading',
					'heading_icon_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'text',
			[
				'label' => '',
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' =>'Text Element',
				'condition' => [
					'type_select' => 'text',
				],
			]
		);

		$repeater->add_control(
			'price',
			[
				'label' => esc_html__( 'Price', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '59',
				'condition' => [
					'type_select' => 'price',
				],
			]
		);

		$repeater->add_control(
			'sub_price',
			[
				'label' => esc_html__( 'Sub Price', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '99',
				'condition' => [
					'type_select' => 'price',
				],
			]
		);

		$repeater->add_control(
			'currency_symbol',
			[
				'label' => esc_html__( 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'dollar' => '&#36; ' ._x( 'Dollar', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'euro' => '&#128; ' ._x( 'Euro', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'pound' => '&#163; ' ._x( 'Pound Sterling', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'ruble' => '&#8381; ' ._x( 'Ruble', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'peso' => '&#8369; ' ._x( 'Peso', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'krona' => 'kr ' ._x( 'Krona', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'lira' => '&#8356; ' ._x( 'Lira', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'franc' => '&#8355; ' ._x( 'Franc', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'baht' => '&#3647; ' ._x( 'Baht', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'shekel' => '&#8362; ' ._x( 'Shekel', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'won' => '&#8361; ' ._x( 'Won', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'yen' => '&#165; ' ._x( 'Yen/Yuan', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'guilder' => '&fnof; ' ._x( 'Guilder', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'peseta' => '&#8359 ' ._x( 'Peseta', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'real' => 'R$ ' ._x( 'Real', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'rupee' => '&#8360; ' ._x( 'Rupee', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'indian_rupee' => '&#8377; ' ._x( 'Rupee (Indian)', 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
					'custom' => esc_html__( 'Custom', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'dollar',
				'condition' => [
					'type_select' => 'price',
				],
			]
		);

		$repeater->add_control(
			'currency',
			[
				'label' => esc_html__( 'Currency', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '$',
				'condition' => [
					'type_select' => 'price',
					'currency_symbol' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'sale',
			[
				'label' => esc_html__( 'Sale', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'type_select' => 'price',
				],
			]
		);

		$repeater->add_control(
			'old_price',
			[
				'label' => esc_html__( 'Old Price', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '55',
				'condition' => [
					'type_select' => 'price',
					'sale' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'preiod',
			[
				'label' => esc_html__( 'Period', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '/Month',
				'condition' => [
					'type_select' => 'price',
				],
			]
		);

		$repeater->add_control(
			'feature_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Awesome Feature',
				'condition' => [
					'type_select' => 'feature',
				],
			]
		);

		$repeater->add_control(
			'feature_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(84,89,95,1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-pricing-table-feature-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-pricing-table-feature-inner svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'feature',
				],
			]
		);

		$repeater->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button',
				'condition' => [
					'type_select' => 'button',
				],
			]
		);

		$repeater->add_control(
			'btn_id',
			[
				'label' => esc_html__( 'Button ID', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => 'button-id',
				'condition' => [
					'type_select' => 'button',
				],
			]
		);

		$repeater->add_control(
			'btn_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'show_label' => false,
				'condition' => [
					'type_select' => 'button',
				],
			]
		);

		$repeater->add_control(
			'btn_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__( 'Before', 'sastra-essential-addons-for-elementor' ),
					'after' => esc_html__( 'After', 'sastra-essential-addons-for-elementor' ),
				],
				'condition' => [
					'type_select' => 'button',
				],

			]
		);

		$repeater->add_control(
			'select_icon',
			[
				'label' => esc_html__( 'Select Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'conditions' => [
       		    	'relation' => 'or',
					'terms' => [
						[
							'name' => 'type_select',
							'operator' => '=',
							'value' => 'feature',
						],
						[
							'name' => 'type_select',
							'operator' => '=',
							'value' => 'button',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'type_select',
									'operator' => '=',
									'value' => 'heading',
								],
								[
									'name' => 'heading_icon_type',
									'operator' => '=',
									'value' => 'icon',
								],
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'feature_linethrough',
			[
				'label' => esc_html__( 'Line Through', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'type_select' => 'feature',
				],
			]
		);

		$repeater->add_control(
			'feature_linethrough_text_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} span.tmpcoder-pricing-table-ftext-line-yes span' => 'color: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'feature',
					'feature_linethrough' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'feature_linethrough_color',
			[
				'label' => esc_html__( 'Line Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} span.tmpcoder-pricing-table-ftext-line-yes' => 'color: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'feature',
					'feature_linethrough' => 'yes',
				],
			]
		);

		$repeater->add_control( 'feature_tooltip', $this->add_repeater_args_feature_tooltip() );

		$repeater->add_control( 'feature_tooltip_text', $this->add_repeater_args_feature_tooltip_text() );

		$repeater->add_control( 'feature_tooltip_show_icon', $this->add_repeater_args_feature_tooltip_show_icon() );

		$repeater->add_control(
			'divider_style',
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
				'default' => 'dashed',		
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-divider' => 'border-top-style: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'divider',
				],
			]
		);

		$repeater->add_control(
			'divider_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F9F9F9',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'divider',
				],
			]
		);

		$repeater->add_control(
			'divider_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-pricing-table-divider' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'divider',
				],
			]
		);

        $repeater->add_responsive_control(
			'divider_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-pricing-table-divider' => 'width: {{SIZE}}{{UNIT}};',
				],	
				'condition' => [
					'type_select' => 'divider',
				],
			]
		);

        $repeater->add_responsive_control(
			'divider_height',
			[
				'label' => esc_html__( 'Height', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-pricing-table-divider' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],	
				'condition' => [
					'type_select' => 'divider',
				],
			]
		);

		$this->add_control(
			'pricing_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'type_select' => 'heading',
						'select_icon' => [ 'value' => 'far fa-gem', 'library' => 'fa-regular' ],
					],
					[
						'type_select' => 'price',
					],
					[
						'type_select' => 'feature',
						'feature_text' => 'Awesome Feature 1',
						'feature_linethrough' => 'yes',
						'feature_icon_color' => '#7a7a7a',
						'feature_linethrough_text_color' => '#7a7a7a',
						'feature_linethrough_color' => '#7a7a7a',
						'select_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ],
					],
					[
						'type_select' => 'feature',
						'feature_text' => 'Awesome Feature 2',
						'feature_icon_color' => 'rgba(84,89,95,1)',
						'select_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ],
					],
					[
						'type_select' => 'feature',
						'feature_text' => 'Awesome Feature 3',
						'feature_icon_color' => 'rgba(97,206,112,1)',
						'select_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ],
					],
					[
						'type_select' => 'feature',
						'feature_text' => 'Awesome Feature 4',
						'feature_icon_color' => 'rgba(97,206,112,1)',
						'select_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ],
					],
					[
						'type_select' => 'feature',
						'feature_text' => 'Awesome Feature 5',
						'feature_icon_color' => 'rgba(97,206,112,1)',
						'select_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ],
					],
					[
						'type_select' => 'button',
						'select_icon' => '',
					],
					[
						'type_select' => 'text',
					],
				],
				'title_field' => '{{{ type_select }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Badge -----------
		$this->start_controls_section(
			'section_badge',
			[
				'label' => esc_html__( 'Badge', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'badge_style',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Style', 'sastra-essential-addons-for-elementor' ),
				'default' => 'corner',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'corner' => esc_html__( 'Corner', 'sastra-essential-addons-for-elementor' ),
					'cyrcle' => esc_html__( 'Cyrcle', 'sastra-essential-addons-for-elementor' ),
					'flag' => esc_html__( 'Flag', 'sastra-essential-addons-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'badge_title',
			[
				'label' => esc_html__( ' Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sale',
				'condition' => [
					'badge_style!' => 'none',
				],
			]
		);

		$this->add_control(
            'badge_hr_position',
            [
                'label' => esc_html__( 'Horizontal Position', 'sastra-essential-addons-for-elementor' ),
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
                    ]
                ],
                'condition' => [
					'badge_style!' => 'none',
				],
            ]
        );

        $this->add_responsive_control(
			'badge_cyrcle_size',
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
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-badge-cyrcle .tmpcoder-pricing-table-badge-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],	
				'condition' => [
					'badge_style' => 'cyrcle',
					'badge_style!' => 'none',
				],
			]
		);

        $this->add_responsive_control(
			'badge_cyrcle_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-badge-cyrcle' => 'transform: translateX({{badge_cyrcle_side_distance.SIZE}}%) translateY({{SIZE}}%);',
				],	
				'condition' => [
					'badge_style' => 'cyrcle',
					'badge_style!' => 'none',
				],
			]
		);

        $this->add_responsive_control(
			'badge_cyrcle_side_distance',
			[
				'label' => esc_html__( 'Side Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-badge-cyrcle' => 'transform: translateX({{SIZE}}%) translateY({{badge_cyrcle_top_distance.SIZE}}%);',
				],	
				'condition' => [
					'badge_style' => 'cyrcle',
					'badge_style!' => 'none',
				],
			]
		);

        $this->add_responsive_control(
			'badge_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 27,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-badge-corner .tmpcoder-pricing-table-badge-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg);',
					'{{WRAPPER}} .tmpcoder-pricing-table-badge-flag' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'badge_style!' => [ 'none', 'cyrcle' ],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Hover Animation --
		$this->start_controls_section(
			'tmpcoder__section_hv_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'hv_animation',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'sastra-essential-addons-for-elementor' ),
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
					'slide' => esc_html__( 'Slide', 'sastra-essential-addons-for-elementor' ),
					'bounce' => esc_html__( 'Bounce', 'sastra-essential-addons-for-elementor' ),
				],
                'prefix_class'	=> 'tmpcoder-pricing-table-animation-',
			]
		);

		$this->add_control(
			'hv_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-pricing-table-animation-slide' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.tmpcoder-pricing-table-animation-bounce' => '-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}}.tmpcoder-pricing-table-animation-zoom' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'pricing-table', [
			'List Item Advanced Tooltip',
			'List Item Even/Odd Background Color',
		] );
		
		// Styles
		// Section: Heading ----------
		$this->start_controls_section(
			'tmpcoder__section_style_heading',
			[
				'label' => esc_html__( 'Heading', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'heading_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-heading'
			]
		);

		$this->add_responsive_control(
			'heading_section_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 27,
					'right' => 20,
					'bottom' => 25,
					'left' => 20,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'title_section',
			[
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_title_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2d2d2d',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_title_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-title',
			]
		);

		$this->add_control(
			'heading_title_distance',
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'sub_title_section',
			[
				'label' => esc_html__( 'Sub Title', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_sub_title_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#919191',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-sub-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_sub_title_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-sub-title',
			]
		);

		$this->add_control(
			'icon_section',
			[
				'label' => esc_html__( 'Icon / Image', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_icon_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-pricing-table-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_icon_positon',
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
                'prefix_class'	=> 'tmpcoder-pricing-table-heading-',
			]
		);

		$this->add_responsive_control(
			'heading_icon_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} .tmpcoder-pricing-table-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-pricing-table-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-pricing-table-icon img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_icon_distance',
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}.tmpcoder-pricing-table-heading-left .tmpcoder-pricing-table-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-pricing-table-heading-center .tmpcoder-pricing-table-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tmpcoder-pricing-table-heading-right .tmpcoder-pricing-table-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Price -----------
		$this->start_controls_section(
			'tmpcoder__section_style_price',
			[
				'label' => esc_html__( 'Price', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'price_wrap_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605be5',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-price'
			]
		);

		$this->add_responsive_control(
			'price_wrap_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 40,
					'right' => 20,
					'bottom' => 30,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'price_section',
			[
				'label' => esc_html__( 'Price', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-price',
			]
		);

		$this->add_control(
			'sub_price_section',
			[
				'label' => esc_html__( 'Sub Price', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'sub_price_size',
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
					'size' => 19,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-sub-price' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sub_price_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'top',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-sub-price' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'currency_section',
			[
				'label' => esc_html__( 'Currency Symbol', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'currency_size',
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
					'size' => 24,
				],		
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-currency' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'currency_hr_position',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'before',
				'options' => [
					'before' => [
						'title' => esc_html__( 'Before', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => esc_html__( 'After', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
			]
		);

		$this->add_control(
			'currency_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'top',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-currency' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'old_price_section',
			[
				'label' => esc_html__( 'Old Price', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'old_price_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-old-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'old_price_size',
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
					'size' => 20,
				],		
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-old-price' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'old_price_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'middle',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-old-price' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'period_section',
			[
				'label' => esc_html__( 'Period', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'period_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-preiod' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'period_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-preiod',
			]
		);

		$this->add_control(
			'period_hr_position',
			[
				'label' => esc_html__( 'Position', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'below' => esc_html__( 'Below', 'sastra-essential-addons-for-elementor' ),
					'beside' => esc_html__( 'Beside', 'sastra-essential-addons-for-elementor' ),
				],
				'default' => 'below',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Features ----------
		$this->start_controls_section(
			'tmpcoder__section_style_features',
			[
				'label' => esc_html__( 'Features', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feature_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f9f9f9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table section' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_controls_group_feature_even_bg();

		$this->add_responsive_control(
			'feature_section_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-feature-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feature_section_top_distance',
			[
				'label' => esc_html__( 'List Top Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
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
					'{{WRAPPER}} .tmpcoder-pricing-table-feature:first-of-type' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'feature_section_bot_distance',
			[
				'label' => esc_html__( 'List Bottom Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
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
					'{{WRAPPER}} .tmpcoder-pricing-table-feature:last-of-type' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'feature_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-feature span > span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'feature_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-feature',
			]
		);

		$this->add_responsive_control(
			'feature_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'flex-start' => 'justify-content: flex-start; text-align: left;',
					'center' => 'justify-content: center; text-align: center;',
					'flex-end' => 'justify-content: flex-end; text-align: right;',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-feature-inner' => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'feature_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 357,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-feature-inner' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'feature_icon_section',
			[
				'label' => esc_html__( 'Icon', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'feature_icon_size',
			[
				'label' => esc_html__( 'Size', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-feature-icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tmpcoder-pricing-table-feature-inner svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_contro_stack_feature_tooltip_section();

		$this->add_control(
			'feature_divider',
			[
				'label' => esc_html__( 'Divider', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => esc_html__( 'Off', 'sastra-essential-addons-for-elementor' ),
				'label_on' => esc_html__( 'On', 'sastra-essential-addons-for-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feature_divider_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d6d6d6',				
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-feature:after' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'feature_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'feature_divider_type',
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
				'default' => 'dashed',		
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-feature:after' => 'border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'feature_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'feature_divider_weight',
			[
				'label' => esc_html__( 'Weight', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-feature:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'feature_divider_width',
			[
				'label' => esc_html__( 'Width', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-feature:after' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_divider' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button -----------
		$this->start_controls_section(
			'tmpcoder__section_style_btn',
			[
				'label' => esc_html__( 'Button', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_section_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-button'
			]
		);

		$this->add_control(
			'btn_section_padding',
			[
				'label' => esc_html__( 'Section Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 30,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_section_padding_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#2B2B2B',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-btn'
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#61ce70',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-btn.tmpcoder-button-none:hover, {{WRAPPER}} .tmpcoder-pricing-table-btn:before, {{WRAPPER}} .tmpcoder-pricing-table-btn:after'
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-btn:hover',
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
					'{{WRAPPER}} .tmpcoder-pricing-table-btn' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-pricing-table-btn:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .tmpcoder-pricing-table-btn:after' => 'transition-duration: {{VALUE}}s',
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
						'max' => 10,
					],
				],
				'size_units' => [ 'px' ],
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
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 40,
					'bottom' => 10,
					'left' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .tmpcoder-pricing-table-btn' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .tmpcoder-pricing-table-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .tmpcoder-pricing-table-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Text -------------
		$this->start_controls_section(
			'tmpcoder__section_style_text',
			[
				'label' => esc_html__( 'Text', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'text_section_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-text'
			]
		);

		$this->add_control(
			'text_section_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 70,
					'bottom' => 30,
					'left' => 70,
				],
				'size_units' => [ 'px'],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'default' => '#a5a5a5',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-text' => 'color: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-text'
			]
		);

		$this->add_control(
			'text_align',
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
					'{{WRAPPER}} .tmpcoder-pricing-table-text' => 'text-align: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Badge ------------
		$this->start_controls_section(
			'tmpcoder__section_style_badge',
			[
				'label' => esc_html__( 'Badge', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'badge_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-badge-inner' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'default' => '#e83d17',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table-badge-inner' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-pricing-table-badge-flag:before' => ' border-top-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'label' => esc_html__( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-badge-inner'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'badge_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table-badge-inner'
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
				'{{WRAPPER}} .tmpcoder-pricing-table-badge .tmpcoder-pricing-table-badge-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Wrapper ----------
		$this->start_controls_section(
			'section_style_wrapper',
			[
				'label' => esc_html__( 'Wrapper', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_wrapper_style' );

		$this->start_controls_tab(
			'tab_wrapper_normal',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table'
			]
		);

		$this->add_control(
			'wrapper_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_wrapper_hover',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_bg_hover_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table:hover'
			]
		);

		$this->add_control(
			'wrapper_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_hover_box_shadow',
				'selector' => '{{WRAPPER}} .tmpcoder-pricing-table:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'wrapper_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'wrapper_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'wrapper_border_type',
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
					'{{WRAPPER}} .tmpcoder-pricing-table' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'wrapper_border_width',
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
					'{{WRAPPER}} .tmpcoder-pricing-table' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'wrapper_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-pricing-table' => 'border-radius: calc({{SIZE}}{{UNIT}} + 2px);',
					'{{WRAPPER}} .tmpcoder-pricing-table-item-first' => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-top-right-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-pricing-table-item-last' => 'border-bottom-left-radius: {{SIZE}}{{UNIT}}; border-bottom-right-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	private function get_currency_symbol( $symbol_name ) {
		$symbols = [
			'dollar' => '&#36;',
			'euro' => '&#128;',
			'pound' => '&#163;',
			'ruble' => '&#8381;',
			'peso' => '&#8369;',
			'krona' => 'kr',
			'lira' => '&#8356;',
			'franc' => '&#8355;',
			'shekel' => '&#8362;',
			'baht' => '&#3647;',
			'won' => '&#8361;',
			'yen' => '&#165;',
			'guilder' => '&fnof;',
			'peseta' => '&#8359',
			'real' => 'R$',
			'rupee' => '&#8360;',
			'indian_rupee' => '&#8377;',
		];

		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}
		
	protected function render() {

	$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
	$item_count = 0;

	if ( empty( $settings['pricing_items'] ) ) {
		return;
	}

	?>

	<div class="tmpcoder-pricing-table">
		<?php foreach ( $settings['pricing_items'] as $key => $item ) :

			if ( ! tmpcoder_is_availble() ) {
				$item['feature_tooltip'] = '';
				$item['feature_tooltip_text'] = '';
			}

			// Fisrt and Last Item Classes
			if ( 0 === $key ) {
				$rep_item_class = ' tmpcoder-pricing-table-item-first';
			} elseif ( count($settings['pricing_items']) - 1 === $key ) {
				$rep_item_class = ' tmpcoder-pricing-table-item-last';
			} else {
				$rep_item_class = '';
			}

			if ( $item['type_select'] === 'feature' ) : ?>

			<section class="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?> tmpcoder-pricing-table-item tmpcoder-pricing-table-<?php echo esc_attr( $item['type_select'] . $rep_item_class ); ?>">
				<div class="tmpcoder-pricing-table-feature-inner">

					<?php
						if (is_array($item['select_icon']['value'])) {
					 		echo wp_kses($this->render_th_icon($item),tmpcoder_wp_kses_allowed_html());
						}
						else
						{
						 	if ( '' !== $item['select_icon']['value'] ) : ?>
							<i class="tmpcoder-pricing-table-feature-icon <?php echo esc_attr( $item['select_icon']['value'] ); ?>">
							</i>
							<?php endif;
						}
					?>
					
					<span class="tmpcoder-pricing-table-feature-text tmpcoder-pricing-table-ftext-line-<?php echo esc_attr( $item['feature_linethrough'] ); ?>">
						<span>
						<?php
							echo wp_kses_post($item['feature_text']);

							if ( 'yes' === $item['feature_tooltip'] && 'yes' === $item['feature_tooltip_show_icon'] ) {
								echo '&nbsp;&nbsp;<i class="far fa-question-circle"></i>';
							}

						?>
						</span>
					</span>

					<?php if ( $item['feature_tooltip'] === 'yes' && ! empty( $item['feature_tooltip_text'] ) ) : ?>
						<div class="tmpcoder-pricing-table-feature-tooltip"><?php echo wp_kses_post($item['feature_tooltip_text']); ?></div>						
					<?php endif; ?>							
				</div>
			</section>

			<?php else : ?>

			<div class="elementor-repeater-item-<?php echo esc_html($item['_id']); ?> tmpcoder-pricing-table-item tmpcoder-pricing-table-<?php echo esc_attr( $item['type_select'] . $rep_item_class ); ?>">		
			
			<?php if ( $item['type_select'] === 'heading' ) : ?>

				<div class="tmpcoder-pricing-table-headding-inner">
			
					<?php if ( $item['heading_icon_type'] === 'icon' && '' !== $item['select_icon']['value'] ) : ?>
						<div class="tmpcoder-pricing-table-icon">
							<?php 
							\Elementor\Icons_Manager::render_icon( $item['select_icon'], [ 'aria-hidden' => 'true' ] );
							?>
						</div>
					<?php elseif ( $item['heading_icon_type'] === 'image' && ! empty( $item['heading_image']['url'] ) ) : ?>
						<div class="tmpcoder-pricing-table-icon">
							<?php 
							$settings[ 'layout_image_crop' ] = ['id' => $item['heading_image']['id']];
							$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'layout_image_crop' );
							echo wp_kses_post($image_html);
							?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $item['heading_title'] ) || ! empty( $item['heading_sub_title'] ) ) : ?>
						<div class="tmpcoder-pricing-table-title-wrap">
							<?php if ( ! empty( $item['heading_title'] ) ) : ?>	
								<h3 class="tmpcoder-pricing-table-title"><?php echo wp_kses_post($item['heading_title']); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $item['heading_sub_title'] ) ) : ?>	
								<span class="tmpcoder-pricing-table-sub-title"><?php echo wp_kses_post($item['heading_sub_title']); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>		
		
			<?php elseif ( $item['type_select'] === 'price' ) : ?>

				<div class="tmpcoder-pricing-table-price-inner">
					<?php if ( $item['sale'] === 'yes' && ! empty( $item['old_price'] ) ) : ?>	
						<span class="tmpcoder-pricing-table-old-price"><?php echo esc_html($item['old_price']); ?></span>
					<?php endif; ?>

					<?php
						if ( 'none' !== $item['currency_symbol'] && 'custom' !== $item['currency_symbol'] && $settings['currency_hr_position'] === 'before' ) {
							echo '<span class="tmpcoder-pricing-table-currency">'. esc_html($this->get_currency_symbol($item['currency_symbol'])) .'</span>';
						} elseif ( 'custom' === $item['currency_symbol'] ) {
							if ( ! empty( $item['currency'] ) && $settings['currency_hr_position'] === 'before' ) {
								echo '<span class="tmpcoder-pricing-table-currency">'. esc_html($item['currency']) .'</span>';
							}
						}
					?>
					
					<?php if ( ! empty( $item['price'] ) ) : ?>	
						<span class="tmpcoder-pricing-table-main-price"><?php echo esc_html($item['price']); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $item['sub_price'] ) ) : ?>	
						<span class="tmpcoder-pricing-table-sub-price"><?php echo esc_html($item['sub_price']); ?></span>
					<?php endif; ?>

					<?php
						if ( 'none' !== $item['currency_symbol'] && 'custom' !== $item['currency_symbol'] && $settings['currency_hr_position'] === 'after' ) {
							echo '<span class="tmpcoder-pricing-table-currency">'. esc_html($this->get_currency_symbol($item['currency_symbol'])) .'</span>';
						} elseif ( 'custom' === $item['currency_symbol'] ) {
							if ( ! empty( $item['currency'] ) && $settings['currency_hr_position'] === 'after' ) {
								echo '<span class="tmpcoder-pricing-table-currency">'. esc_html($item['currency']) .'</span>';
							}
						}
					?>

					<?php if ( ! empty( $item['preiod'] ) && $settings['period_hr_position'] === 'beside' ) : ?>	
						<div class="tmpcoder-pricing-table-preiod"><?php echo esc_html($item['preiod']); ?></div>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $item['preiod'] ) && $settings['period_hr_position'] === 'below' ) : ?>	
					<div class="tmpcoder-pricing-table-preiod"><?php echo esc_html($item['preiod']); ?></div>
				<?php endif; ?>
		
			<?php elseif ( $item['type_select'] === 'text' && ! empty( $item['text'] ) ) : ?>

				<?php echo wp_kses_post($item['text']); ?>

			<?php elseif ( $item['type_select'] === 'button' && ( ! empty( $item['btn_text'] ) || '' !== $item['select_icon']['value'] ) ) :
				
				if (  '' !== $item['btn_url']['url'] ) {
					$this->add_render_attribute( 'btn_attribute'. $item_count, 'href', $item['btn_url']['url'] );
	
					if ( $item['btn_url']['is_external'] ) :
						$this->add_render_attribute( 'btn_attribute'. $item_count, 'target', '_blank' );
					endif;
	
					if ( $item['btn_url']['nofollow'] ) :
						$this->add_render_attribute( 'btn_attribute'. $item_count, 'nofollow', '' );
					endif;
				}

				if ( '' !== $item['btn_id'] ) :
					$this->add_render_attribute( 'btn_attribute' . $item_count, 'id', esc_html( $item['btn_id']) );
				endif;

				?>

				<?php echo wp_kses_post('<a class="tmpcoder-pricing-table-btn tmpcoder-button-effect '.esc_html($this->get_settings()['btn_animation']).' " '.$this->get_render_attribute_string( 'btn_attribute'. $item_count ).'>');?>
					<span>

						<?php if ( '' !== $item['select_icon']['value'] &&  $item['btn_position'] === 'before' ) : ?>
						<i class="<?php echo esc_attr( $item['select_icon']['value'] ); ?>"></i>
						<?php endif; ?>

						<?php echo esc_html($item['btn_text']); ?>

						<?php if ( '' !== $item['select_icon']['value'] &&  $item['btn_position'] === 'after' ) : ?>
						<i class="<?php echo esc_attr( $item['select_icon']['value'] ); ?>"></i>

					</span>
					<?php endif; ?>
				</a>
				
			<?php elseif ( $item['type_select'] === 'divider' ) : ?>

				<div class="tmpcoder-pricing-table-divider"></div>
				
			<?php endif; ?>

			</div>
			<?php

			endif; 
			$item_count++;

		endforeach;

		if ( $settings['badge_style'] !== 'none' && ! empty( $settings['badge_title'] ) ) :

			$this->add_render_attribute( 'tmpcoder-pricing-table-badge-attr', 'class', 'tmpcoder-pricing-table-badge tmpcoder-pricing-table-badge-'. esc_attr($settings[ 'badge_style']) );
			if ( ! empty( $settings['badge_hr_position'] ) ) :
				$this->add_render_attribute( 'tmpcoder-pricing-table-badge-attr', 'class', 'tmpcoder-pricing-table-badge-'. esc_attr($settings['badge_hr_position']) );
			endif;
			
			?>
				<?php echo wp_kses_post('<div '.$this->get_render_attribute_string( 'tmpcoder-pricing-table-badge-attr' ).'>'); ?>	

				<div class="tmpcoder-pricing-table-badge-inner"><?php echo esc_html($settings['badge_title']); ?></div>	
			</div>
		<?php endif; ?>
	</div>

	<?php
	}

	public function render_th_icon($item) {
		ob_start();
		\Elementor\Icons_Manager::render_icon($item['select_icon'], ['aria-hidden' => 'true']);
		return ob_get_clean();
	}
}