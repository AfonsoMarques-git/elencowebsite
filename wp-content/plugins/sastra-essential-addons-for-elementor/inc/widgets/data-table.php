<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Data_Table extends Widget_Base {
    public function get_name() {
		return 'tmpcoder-data-table';
	}

	public function get_title() {
		return esc_html__('Data Table', 'sastra-essential-addons-for-elementor');
	}
	public function get_icon() {
		return 'tmpcoder-icon eicon-table';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category' ];
	}

	public function get_keywords() {
		return ['data table', 'advanced', 'table', 'data', 'comparison table', 'table comparison'];
	}

	public function get_script_depends() {
		
		$depends = [ 'tmpcoder-table-to-excel-js' => true, 'tmpcoder-data-table' => true ];

		if ( !tmpcoder_elementor()->preview->is_preview_mode() ) {
			$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

			if ( $settings['enable_table_export'] != 'yes' ) {
				unset( $depends['tmpcoder-table-to-excel-js'] );
			}
		}
		return array_keys($depends); 
	}

	public function get_style_depends() {
		return ['tmpcoder-data-table'];
	}

    public function get_custom_help_url() {
    	return TMPCODER_NEED_HELP_URL;
    }

	public function add_control_choose_table_type() {
		$this->add_control(
			'choose_table_type',
			[
				'label' => esc_html__( 'Data Type', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'render_type' => 'template',
				'options' => [
					'custom' => esc_html__( 'Custom', 'sastra-essential-addons-for-elementor' ),
					'pro-cv' => esc_html__( 'CSV (Pro)', 'sastra-essential-addons-for-elementor' ),
				],
				'prefix_class' => 'tmpcoder-data-table-type-'
			]
		);
	}

	public function add_control_enable_table_export() {
		$this->add_control(
			'enable_table_export',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Export Buttons %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}

	public function add_control_export_excel_text() {}

	public function add_control_export_buttons_distance() {}

	public function add_control_table_search_input_padding() {}

	public function add_control_export_csv_text() {}

	public function add_section_export_buttons_styles() {}

	public function add_control_enable_table_search() {
		$this->add_control(
			'enable_table_search',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Search %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}

	public function add_section_search_styles() {}

	public function add_control_enable_table_sorting() {
		$this->add_control(
			'enable_table_sorting',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Sorting %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}

	public function add_control_active_td_bg_color() {}

	public function add_control_enable_custom_pagination() {
		$this->add_control(
			'enable_custom_pagination',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Pagination %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			]
		);
	}

	public function add_section_pagination_styles() {}

	public function add_control_stack_content_tooltip_section() {}

	public function add_repeater_args_content_tooltip() {
			return [
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Show Tooltip %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'tmpcoder-pro-control'
			];
	}

	public function add_repeater_args_content_tooltip_text() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_content_tooltip_show_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

    public function register_controls() {

		$this->start_controls_section(
			'section_preview',
			[
				'label' => esc_html__('General', 'sastra-essential-addons-for-elementor'),
			]
		);
		
		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_choose_table_type();

		// Upgrade to Pro Notice
		tmpcoder_upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'data-table', 'choose_table_type', ['pro-cv'] );


		$this->add_control_enable_table_export();

		$this->add_control_export_excel_text();

		$this->add_control_export_csv_text();

		$this->add_control_enable_table_search();

		$this->add_control_enable_table_sorting();

		$this->add_control_enable_custom_pagination();

		$this->add_control(
			'equal_column_width',
			[
				'label' => esc_html__('Equal Column Width', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'prefix_class' => 'tmpcoder-equal-column-width-'
			]
		);

		$this->add_control(
			'enable_row_pagination', 
			[
				'label' => esc_html__('Table Row Index', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'white_space_text',
			[
				'label' => esc_html__('Prevent Word Wrap', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'tmpcoder-table-text-nowrap-',
				'separator' => 'before'
			]
		);


        $this->add_control(
            'table_export_csv_button',
            [
                'label' => esc_html__('Export table as CSV file', 'sastra-essential-addons-for-elementor'),
                'type'  => Controls_Manager::BUTTON,
                'text'  => esc_html__('Export', 'sastra-essential-addons-for-elementor'),
                'event' => 'my-table-export',
				'separator' => 'before'
            ]
        );

		$this->end_controls_section();


		$this->start_controls_section(
			'section_header',
			[
				'label' => esc_html__('Header', 'sastra-essential-addons-for-elementor'),
				'condition' => [
					'choose_table_type' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'table_th', [
				'label' => esc_html__( 'Title', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Table Title' , 'sastra-essential-addons-for-elementor' ),
				'label_block' => true
			]
		);
		
		$repeater->add_responsive_control(
			'header_icon',
			[
				'label' => esc_html__('Media', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'header_icon_type',
			[
				'label' => esc_html__('Media Type', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__('Icon', 'sastra-essential-addons-for-elementor'),
					'image' => esc_html__('Image', 'sastra-essential-addons-for-elementor'),
				],
				'condition' => [
					'header_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'header_icon_position',
			[
				'label' => esc_html__('Media Position', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('Left', 'sastra-essential-addons-for-elementor'),
					'right' => esc_html__('Right', 'sastra-essential-addons-for-elementor'),
					'top' => esc_html__('Top', 'sastra-essential-addons-for-elementor'),
					'bottom' => esc_html__('Bottom', 'sastra-essential-addons-for-elementor'),
				],
				'condition' => [
					'header_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'choose_header_col_icon',
			[
				'label' => esc_html__('Select Icon', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'header_icon' => 'yes',
					'header_icon_type' => 'icon',
				]

			]
		);

		$repeater->add_control(
			'header_col_img',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'header_icon_type'	=> 'image'
				]
			]
		);

		$repeater->add_responsive_control(
			'header_col_img_size',
			[
				'label' => esc_html__( 'Image Size', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500
					]
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 100,
					'unit' => 'px'
				],
				'desktop_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-data-table-th-img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
				],
				'condition' => [
					'header_icon_type'	=> 'image'
				]
			]
		);
		
		$repeater->add_control(
			'header_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-header-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-header-icon svg' => 'fill: {{VALUE}}'
				],
				'condition' => [
					'header_icon' => 'yes',
					'header_icon_type'	=> 'icon'
				]
			]
		);

		$repeater->add_control(
			'header_th_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
				],
			]
		);

		$repeater->add_control(
			'header_th_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important'
				],
			]
		);

		$repeater->add_control(
			'header_colspan',
			[
				'label'			=> esc_html__( 'Col Span', 'sastra-essential-addons-for-elementor'),
				'type'			=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'separator' => 'before'
			]
		);

		$repeater->add_responsive_control(
			'th_individual_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'separator' => 'before',
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
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'table_header',
			[
				'label' => esc_html__( 'Repeater Table Header', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'table_th' => esc_html__( 'TABLE HEADER 1', 'sastra-essential-addons-for-elementor' ),
					],
					[
						'table_th' => esc_html__( 'TABLE HEADER 2', 'sastra-essential-addons-for-elementor' ),
					],
					[
						'table_th' => esc_html__( 'TABLE HEADER 3', 'sastra-essential-addons-for-elementor' ),
					],
					[
						'table_th' => esc_html__( 'TABLE HEADER 4', 'sastra-essential-addons-for-elementor' ),
					],
				],
				'title_field' => '{{{ table_th }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__('Content', 'sastra-essential-addons-for-elementor'),
				'condition' => [
					'choose_table_type' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'table_content_row_type',
			[
				'label' => esc_html__( 'Row Type', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'row',
				'label_block' => false,
				'options' => [
					'row' => esc_html__( 'Row', 'sastra-essential-addons-for-elementor'),
					'col' => esc_html__( 'Column', 'sastra-essential-addons-for-elementor'),
				]
			]
		);

		$repeater->add_control(
			'table_td', 
			[
				'label' => esc_html__( 'Content', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Content' , 'sastra-essential-addons-for-elementor' ),
				'show_label' => true,
				'separator' => 'before',
				'condition' => [
					'table_content_row_type' => 'col',
				]
			]
		);

		$repeater->add_control(
			'cell_link',
			[
				'label' => esc_html__( 'Content URL', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'sastra-essential-addons-for-elementor' ),
				'show_external' => true,
				'default' => [
					'url' => TMPCODER_NEED_HELP_URL,
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'table_content_row_type' => 'col',
				]
			]
		);

		$repeater->add_responsive_control(
			'td_icon',
			[
				'label' => esc_html__('Media', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);
		$repeater->add_control(
			'td_icon_type',
			[
				'label' => esc_html__('Media Type', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__('Icon', 'sastra-essential-addons-for-elementor'),
					'image' => esc_html__('Image', 'sastra-essential-addons-for-elementor')
				],
				'condition' => [
					'td_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'td_icon_position',
			[
				'label' => esc_html__('Media Position', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('Left', 'sastra-essential-addons-for-elementor'),
					'right' => esc_html__('Right', 'sastra-essential-addons-for-elementor'),
					'top' => esc_html__('Top', 'sastra-essential-addons-for-elementor'),
					'bottom' => esc_html__('Bottom', 'sastra-essential-addons-for-elementor'),
				],
				'condition' => [
					'td_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'choose_td_icon',
			[
				'label' => esc_html__('Select Icon', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type' => 'icon'
				]

			]
		);

		$repeater->add_control(
			'td_col_img',
			[
				'label' => esc_html__( 'Image', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type!'	=> ['none', 'icon']
				]
			]
		);

		$repeater->add_responsive_control(
			'td_col_img_size',
			[
				'label' => esc_html__( 'Image Size', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500
					]
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				// 'default' => [
				// 	'size' => 100,
				// 	'unit' => 'px'
				// ],
				// 'desktop_default' => [
				// 	'size' => 100,
				// 	'unit' => '%',
				// ],
				// 'tablet_default' => [
				// 	'size' => 100,
				// 	'unit' => '%',
				// ],
				// 'mobile_default' => [
				// 	'size' => 100,
				// 	'unit' => '%',
				// ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type!'	=> ['none', 'icon']
				]
			]
		);

        $repeater->add_responsive_control(
            'td_col_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'sastra-essential-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', 'em', 'rem',
				],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
					],
				],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-td-content-wrapper i:not(.fa-question-circle)' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type!'	=> ['none', 'image']
				]
			]
        );

		$repeater->add_control(
			'td_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}'
				],
				'condition' 	=> [
					'table_content_row_type' => 'col',
					'td_icon' => 'yes',
					'td_icon_type' => 'icon'
				]
			]
		);

		$repeater->add_control( 'content_tooltip', $this->add_repeater_args_content_tooltip() );

		$repeater->add_control( 'content_tooltip_text', $this->add_repeater_args_content_tooltip_text() );

		$repeater->add_control( 'content_tooltip_show_icon', $this->add_repeater_args_content_tooltip_show_icon() );

		$repeater->add_control(
			'td_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .tmpcoder-table-text' => 'color: {{VALUE}} !important'
				],
			]
		);

		$repeater->add_control(
			'td_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important'
				],
			]
		);

		$repeater->add_control(
			'td_background_color_hover',
			[
				'label' => esc_html__( 'Background Color (Hover)', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background-color: {{VALUE}} !important'
				],
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'table_content_row_colspan',
			[
				'label'			=> esc_html__( 'Col Span', 'sastra-essential-addons-for-elementor'),
				'type'			=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'label_block'	=> false,
				'separator' => 'before',
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'table_content_row_rowspan',
			[
				'label'			=> esc_html__( 'Row Span', 'sastra-essential-addons-for-elementor'),
				'type'			=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'label_block'	=> false,
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_responsive_control(
			'td_individual_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'separator' => 'before',
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
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}} !important;',
				],
			]
		);
	
		$this->add_control(
			'table_content_rows',
			[
				'label' => esc_html__( 'Repeater Table Rows', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'table_content_row_type' => 'row' ],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 1'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 2'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 3'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 4'
					],
					[ 'table_content_row_type' => 'row' ],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 1'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 2'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 3'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 4'
					],
				],
				'title_field' => '{{table_content_row_type}}::{{table_td}}',
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'data-table', [
			__('Import Table data from CSV file upload or URL', 'sastra-essential-addons-for-elementor'),
			__('Show/Hide Export Table data buttons', 'sastra-essential-addons-for-elementor'),
			__('Enable Live Search for Tables', 'sastra-essential-addons-for-elementor'),
			__('Enable Table Sorting option', 'sastra-essential-addons-for-elementor'),
			__('Enable Table Pagination. Divide Table items by pages', 'sastra-essential-addons-for-elementor'),
			__('Enable Tooltips on each cell', 'sastra-essential-addons-for-elementor'),
		] );

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__('General', 'sastra-essential-addons-for-elementor'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'table_responsive_width',
			[
				'label' => esc_html__( 'Table Min Width', 'sastra-essential-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500
					]
				],
				// 'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 600,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-table-container .tmpcoder-data-table' => 'min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-table-inner-container' => 'width: 100%;',
					'{{WRAPPER}} .tmpcoder-data-table' => 'width: 100%;',
				],
				// 'separator' => 'before'
			]
		);

		$this->add_control(
			'all_border_type',
			[
				'label' => esc_html__('Border', 'sastra-essential-addons-for-elementor' ),
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
					'{{WRAPPER}} .tmpcoder-table-inner-container' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} th.tmpcoder-table-th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} th.tmpcoder-table-th-pag' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} td.tmpcoder-table-td' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} td.tmpcoder-table-td-pag' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'all_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E4E4E4',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-table-inner-container' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} th.tmpcoder-table-th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} th.tmpcoder-table-th-pag' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} td.tmpcoder-table-td' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} td.tmpcoder-table-td-pag' => 'border-color: {{VALUE}}'
				],
				'condition' => [
					'all_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'all_border_width',
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
					'{{WRAPPER}} .tmpcoder-table-inner-container' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} th.tmpcoder-table-th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} th.tmpcoder-table-th-pag' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} td.tmpcoder-table-td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} td.tmpcoder-table-td-pag' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'all_border_type!' => 'none',
				]
			]
		);

		$this->add_responsive_control(
			'header_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-table-inner-container' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} table' => 'border-radius: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control_export_buttons_distance();

		$this->add_control_table_search_input_padding();

		$this->add_control(
			'hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-table-th' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .tmpcoder-table-th-pag' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .tmpcoder-table-th i' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .tmpcoder-table-th svg' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .tmpcoder-table-td' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .tmpcoder-table-td-pag' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .tmpcoder-table-td i' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .tmpcoder-table-td svg' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .tmpcoder-table-text' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size'
				],
				'separator' => 'before'
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'header_style',
			[
				'label' => esc_html__('Header', 'sastra-essential-addons-for-elementor'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'th_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'th_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} tr th' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'th_color_hover',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} th:hover' => 'color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'th_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} tr th:hover' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'th_typography',
				'selector' => '{{WRAPPER}} th',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'     => [
						'default' => '400',
					]
				],
			]
		);

		$this->add_responsive_control(
            'header_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'sastra-essential-addons-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 15,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .tmpcoder-data-table thead i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-data-table thead svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
				'separator' => 'before'
            ]
        );

		$this->add_responsive_control(
            'header_sorting_icon_size',
            [
                'label'      => esc_html__('Sorting Icon Size', 'sastra-essential-addons-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 12,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .tmpcoder-data-table thead .tmpcoder-sorting-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tmpcoder-data-table thead .tmpcoder-sorting-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
				'condition' => [
					'enable_table_sorting' => 'yes'
				]
            ]
        );

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

        $this->add_responsive_control(
            'header_image_space',
            [
                'label'      => esc_html__('Image Margin', 'sastra-essential-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .tmpcoder-data-table th img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

        $this->add_responsive_control(
            'header_icon_space',
            [
                'label'      => esc_html__('Icon Margin', 'sastra-essential-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .tmpcoder-data-table th i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-data-table th svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

		$this->add_responsive_control(
			'th_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'separator' => 'before',
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
				'prefix_class' => 'tmpcoder-table-align-items-',
				'selectors' => [
					'{{WRAPPER}} th:not(".tmpcoder-table-th-pag")' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-th-inner-cont' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-flex-column span' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-flex-column-reverse span' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-table-th' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'content_styles',
			[
				'label' => esc_html__('Content', 'sastra-essential-addons-for-elementor'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs(
			'cells_style_tabs'
		);

		$this->start_controls_tab(
			'cells_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'odd_cell_styles',
			[
				'label' => esc_html__('Odd Rows', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'odd_row_td_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} tbody tr:nth-child(odd) td.tmpcoder-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(odd) td a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td span' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'odd_row_td_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} tbody tr:nth-child(odd) td' => 'background-color: {{VALUE}}', // TODO: decide tr or td
				],
			]
		);

		$this->add_control(
			'even_cell_styles',
			[
				'label' => esc_html__('Even Rows', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'even_row_td_color',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} tbody tr:nth-child(even) td.tmpcoder-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(even) td span' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(even) td' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'even_row_td_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F3F3F3',
				'selectors' => [
					'{{WRAPPER}} tbody tr:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cells_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'odd_cell_hover_styles',
			[
				'label' => esc_html__('Odd Rows', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'odd_row_td_color_hover',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover span' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover.tmpcoder-table-text' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'odd_row_td_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tbody tr:nth-child(odd):hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'even_cell_hover_styles',
			[
				'label' => esc_html__('Even Rows', 'sastra-essential-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'even_row_td_color_hover',
			[
				'label'  => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover span' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover.tmpcoder-table-text' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'even_row_td_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tbody tr:nth-child(even):hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_active_td_bg_color();

		$this->add_control(
			'typograpphy_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'td_typography',
				'selector' => '{{WRAPPER}} td, {{WRAPPER}} i.fa-question-circle',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'     => [
						'default' => '400',
					]
				],
			]
		);

		$this->add_responsive_control(
            'tbody_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'sastra-essential-addons-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
				'separator' => 'before',
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 15,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .tmpcoder-data-table tbody i:not(.fa-question-circle)' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'tbody_image_size',
            [
                'label'      => esc_html__('Image Size', 'sastra-essential-addons-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'default'    => [
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .tmpcoder-data-table-th-img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

		$this->add_responsive_control(
			'td_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'separator' => 'before',
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'tbody_image_border_radius',
			[
				'label' => esc_html__( 'Image Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-data-table-th-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'td_img_space',
            [
                'label'      => esc_html__('Image Margin', 'sastra-essential-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .tmpcoder-data-table td img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

        $this->add_responsive_control(
            'td_icon_space',
            [
                'label'      => esc_html__('Icon Margin', 'sastra-essential-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .tmpcoder-data-table td i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-data-table td svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

		$this->add_responsive_control(
			'td_align',
			[
				'label' => esc_html__( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => ' eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => ' eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => ' eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} td:not(".tmpcoder-table-td-pag")' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-td-content-wrapper span' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tmpcoder-table-td' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->add_section_export_buttons_styles();

		$this->add_section_pagination_styles();

		$this->add_control_stack_content_tooltip_section();
	
    }

	public function render_content_tooltip($item) {}

	public function render_tooltip_icon($item) {}

	public function render_custom_pagination($settings, $countRows) {}

	protected function render_csv_data($url, $custom_pagination, $sorting_icon, $settings) {
		
		$url_ext = pathinfo($url, PATHINFO_EXTENSION);
		$url_ext2 = pathinfo($url);

		ob_start();
		if( $url_ext === 'csv' || str_contains($url_ext2['dirname'], 'docs.google.com/spreadsheets') ) {
			if (str_contains($url_ext2['dirname'], 'docs.google.com/spreadsheets')) {
				$url = $settings['table_insert_url']['url'];
			}
			$this->tmpcoder_parse_csv_to_table($url, $settings, $custom_pagination, $sorting_icon );
		} else {
			echo '<p class="tmpcoder-no-csv-file-found">'. esc_html__('Please provide a CSV file.', 'sastra-essential-addons-for-elementor') .'</p>';
		}
		return \ob_get_clean();

	}

	protected function tmpcoder_parse_csv_to_table($filename, $settings, $custom_pagination, $sorting_icon ) {

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

        global $wp_filesystem;

        // Load the WordPress filesystem.
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . '/wp-admin/includes/file.php';
        }

        // Initialize the WordPress filesystem.
        if ( ! WP_Filesystem( array(), ABSPATH, true ) ) {
            // Error handling.
        }

        // Check whether the file exists.
        if ( $wp_filesystem->exists( $filename ) ) {
            // Do something with the file.
        }

		$handle = $wp_filesystem->get_contents($filename);
        
        // Determine the delimiter
		$delimiter = $this->detect_csv_delimiter($filename);
		//display header row if true
		echo '<table class="tmpcoder-append-to-scope tmpcoder-data-table">';
		if ( 'yes' === $settings['display_header'] ) {
			
            $csvcontents = explode( $delimiter, strtok( $handle, "\n" ) );

			echo '<thead><tr class="tmpcoder-table-head-row tmpcoder-table-row">';
			foreach ($csvcontents as $headercolumn) {
				echo wp_kses("<th class='tmpcoder-table-th tmpcoder-table-text'>". $headercolumn ." ". $sorting_icon . "</th>", $allowed_tags );
			}
			echo '</tr></thead>';
		}
		echo '<tbody>';

		// displaying contents
		$countRows = 0;
		$oddEven = '';
		$csvcontents = explode( "\n", $handle );
    
        // Loop through each line in the CSV	
        foreach ( $csvcontents as $line ) {
            // Skip empty lines
            if ( empty( $line ) ) {
                continue;
            }
				$countRows++;
				$oddEven = $countRows % 2 == 0 ? 'tmpcoder-even' : 'tmpcoder-odd';
				echo '<tr class="tmpcoder-table-row  '. esc_attr($oddEven) .'">';
				
                // Explode the line into an array using the delimiter
                $columns = str_getcsv( $line, $delimiter );
            
                // Loop through each column in the line
                foreach ( $columns as $column ) {
					echo wp_kses('<td class="tmpcoder-table-td tmpcoder-table-text">'. $column .'</td>', $allowed_tags );
				}
				echo '</tr>';
		}
		echo '</tbody></table>';
		echo '</div>';
		echo '</div>';

		if ( 'yes' == $settings['enable_custom_pagination'] ) {
			$this->render_custom_pagination($settings, $countRows);
		} 
	}

	protected function detect_csv_delimiter($filename) {
		$delimiters = [',', ';'];
		$counts = [];
		$maxCount = 0;
		$bestDelimiter = ',';
		
		global $wp_filesystem;

        // Initialize the WP_Filesystem
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if ( ! WP_Filesystem() ) {
            return;
        }

        $file_contents 	= 	'';

        if ( $wp_filesystem->exists( $filename ) ) {
            $file_contents = $wp_filesystem->get_contents( $filename );
        } // End If Statement

        // Get the first line
        $firstLine = strtok( $file_contents, "\n" );
	
		foreach ($delimiters as $delimiter) {
			$counts[$delimiter] = count(str_getcsv($firstLine, $delimiter));
		}
	
		foreach ($counts as $delimiter => $count) {
			if ($count > $maxCount) {
				$maxCount = $count;
				$bestDelimiter = $delimiter;
			}
		}
	
		return $bestDelimiter;
	}

	public function render_th_icon($item) {
		ob_start();
		\Elementor\Icons_Manager::render_icon($item['choose_header_col_icon'] ?? '', ['aria-hidden' => 'true']);
		return ob_get_clean();
	}

	public function render_th_icon_or_image($item, $i) {
		$header_icon = '';
		
		if ( ($item['header_icon'] ?? '') === 'yes' && ($item['header_icon_type'] ?? '') === 'icon' ) {
			$header_icon = '<span class="tmpcoder-header-icon" style="display: inline-block; vertical-align: middle;">'. $this->render_th_icon($item) . '</span>';
		}

		if( ($item['header_icon'] ?? '') == 'yes' && ($item['header_icon_type'] ?? '') == 'image' ) {
			$settings['tmpcoder_table_th_img'.$i] = ['id' => $item['header_col_img']['id'] ?? 0];
			$header_icon = Group_Control_Image_Size::get_attachment_image_html( $settings, 'tmpcoder_table_th_img'.$i);
			$header_icon = str_replace( '<img', '<img class="tmpcoder-data-table-th-img"', $header_icon );
		}

        echo wp_kses( $header_icon, tmpcoder_wp_kses_allowed_html() );
	}

	public function render_td_icon($table_td, $j) {
		ob_start();
		\Elementor\Icons_Manager::render_icon($table_td[$j]['icon_item'] ?? '', ['aria-hidden' => 'true']);
		return ob_get_clean();
	}

	public function render_td_icon_or_image($table_td, $j) {
		$tbody_icon = '';
		
		if ( ($table_td[$j]['icon'] ?? '') === 'yes' && ($table_td[$j]['icon_type'] ?? '') == 'icon' ) {
			$tbody_icon = '<span class="tbl-heading-icon" style="display: inline-block; vertical-align: middle;">'. $this->render_td_icon($table_td, $j) . '</span>';
		}

		if ( ($table_td[$j]['icon'] ?? '') == 'yes' && ($table_td[$j]['icon_type'] ?? '') == 'image' ) { 
            $settings['tmpcoder_table_td_img'.$j] = ['id' => $table_td[$j]['col_img']['id'] ?? 0];
			$tbody_icon = Group_Control_Image_Size::get_attachment_image_html( $settings, 'tmpcoder_table_td_img'.$j);
			$tbody_icon = str_replace( '<img', '<img class="tmpcoder-data-table-th-img"', $tbody_icon );
		}
		echo wp_kses_post($tbody_icon);
	}

	public function render_search_export() {}

    protected function render() {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new ); 

		$table_tr = [];
		$table_td = [];
		?>

		<?php

		// Render Search and/or Export Buttons
		$this->render_search_export();
		
		$x = 0;
		
		$sorting_icon = ('yes' === $settings['enable_table_sorting'] && tmpcoder_is_availble() ) ? '<span class="tmpcoder-sorting-icon"><i class="fas fa-sort"></i></span>' : ''; 
		
		$this->add_render_attribute(
			'tmpcoder_table_inner_container_attributes',
			[
				'class' => ['tmpcoder-table-inner-container', 'yes' === $settings['enable_custom_pagination'] ? 'tmpcoder-hide-table-before-arrange' : ''],
				// 'data-table-columns' => !empty($settings['columns_number']) ? $settings['columns_number'] : '',
				'data-table-sorting' => $settings['enable_table_sorting'],
				'data-custom-pagination' => $settings['enable_custom_pagination'],
				'data-row-pagination' => $settings['enable_row_pagination'],
				'data-entry-info' => tmpcoder_is_availble() ? $settings['enable_entry_info'] : 'no',
				'data-rows-per-page' => isset($settings['table_items_per_page']) ? $settings['table_items_per_page'] : ''
			]
		);

		?>
		
		<div class="tmpcoder-table-container">
		<?php echo wp_kses_post('<div '. $this->get_render_attribute_string( 'tmpcoder_table_inner_container_attributes' ).'>'); ?>

		<?php if ( isset($settings['choose_csv_type']) && 'file' === $settings['choose_csv_type'] ) {

			echo wp_kses_post($this->render_csv_data($settings['table_upload_csv']['url'], $settings['enable_custom_pagination'], $sorting_icon, $settings));

		} elseif ( isset($settings['choose_csv_type']) && 'url' === $settings['choose_csv_type']) {

			echo wp_kses_post($this->render_csv_data(esc_url($settings['table_insert_url']['url']), esc_attr($settings['enable_custom_pagination']), $sorting_icon, $settings));

		} else {

			// Storing Data table content values
			$countRows = 0;

			foreach( $settings['table_content_rows'] as $content_row ) {
				$countRows++;
				$oddEven = $countRows % 2 == 0 ? 'tmpcoder-even' : 'tmpcoder-odd';
				$row_id = uniqid();

				if( $content_row['table_content_row_type'] == 'row' ) {
					$table_tr[] = [
						'id' => $row_id,
						'type' => $content_row['table_content_row_type'],
						'class' => ['tmpcoder-table-body-row', 'tmpcoder-table-row', 'elementor-repeater-item-'. esc_attr($content_row['_id']), esc_attr($oddEven)]
					];
				}

				if( $content_row['table_content_row_type'] == 'col' ) {

					$table_tr_keys = array_keys( $table_tr );
					$last_key = end( $table_tr_keys );

					$table_td[] = [
						'row_id' => isset($table_tr[$last_key]['id']) ? $table_tr[$last_key]['id'] : '',
						'type' => $content_row['table_content_row_type'] ?? '',
						'content' => $content_row['table_td'] ?? '',
						'colspan' => $content_row['table_content_row_colspan'] ?? '',
						'rowspan' => $content_row['table_content_row_rowspan'] ?? '',
						'link' => $content_row['cell_link'] ?? '',
						'external' => ($content_row['cell_link']['is_external'] ?? false) == true ? '_blank' : '_self',
						'icon_type' => $content_row['td_icon_type'] ?? '',
						'icon' => $content_row['td_icon'] ?? '',
						'icon_position' => $content_row['td_icon_position'] ?? '',
						'icon_item' => $content_row['choose_td_icon'] ?? '',
						'col_img' => $content_row['td_col_img'] ?? '',
						'class' => ['elementor-repeater-item-'. esc_attr($content_row['_id'] ?? ''), 'tmpcoder-table-td'],
						'content_tooltip' => $content_row['content_tooltip'] ?? '',
						'content_tooltip_text' => $content_row['content_tooltip_text'] ?? '',
						'content_tooltip_show_icon' => $content_row['content_tooltip_show_icon'] ?? ''
					];
				}
			} 

			?>

			<table class="tmpcoder-data-table" id="tmpcoder-data-table">
			<?php if ( $settings['table_header'] ) { ?>
					
				<thead>
					<tr class="tmpcoder-table-head-row tmpcoder-table-row">
					<?php $i = 0; foreach ($settings['table_header'] as $item) { 

						$this->add_render_attribute('th_class'. esc_attr($i), [
							'class' => ['tmpcoder-table-th', 'elementor-repeater-item-'. esc_attr($item['_id'])],
							'colspan' => $item['header_colspan'],
						]); 
						
						$this->add_render_attribute('th_inner_class'. esc_attr($i), [
							'class' => [($item['header_icon_position'] === 'top') ? 'tmpcoder-flex-column-reverse' : (($item['header_icon_position'] === 'bottom') ? 'tmpcoder-flex-column' : '')],
						]); ?>

						<?php echo wp_kses_post('<th '. $this->get_render_attribute_string('th_class'. esc_attr($i)).'>'); ?>
                            <?php echo wp_kses_post('<div '. $this->get_render_attribute_string('th_inner_class'. esc_attr($i)).'>'); ?>

								<?php $item['header_icon'] === 'yes'  && $item['header_icon_position'] == 'left' ? $this->render_th_icon_or_image($item, $i) : '' ?>
								
								<?php if ( '' !== $item['table_th'] ) :  ?>
									<span class="tmpcoder-table-text"><?php echo esc_html($item['table_th']); ?></span>
								<?php endif; ?>
								<?php $item['header_icon'] === 'yes' && $item['header_icon_position'] == 'right' ? $this->render_th_icon_or_image($item, $i) : '' ?>
								<?php echo wp_kses_post($sorting_icon); ?>
								<?php $item['header_icon'] === 'yes' && ($item['header_icon_position'] == 'top' || $item['header_icon_position'] == 'bottom')? $this->render_th_icon_or_image($item, $i) : '' ?>
								<?php echo wp_kses_post($sorting_icon); ?>
							</div>
						</th>
						<?php $i++; } ?>
					</tr>
				</thead>

				<tbody>
				<?php for( $i = 0 + $x; $i < count( $table_tr ) + $x; $i++ ) :

						$this->add_render_attribute('table_row_attributes'. esc_attr($i), [
							'class' => $table_tr[$i]['class'],
						]);

						?>
					<?php echo wp_kses_post('<tr '. $this->get_render_attribute_string('table_row_attributes'. esc_attr($i)).'>'); ?>
					<?php for( $j = 0; $j < count( $table_td ); $j++ ) {
							if( $table_tr[$i]['id'] == $table_td[$j]['row_id'] ) {
								$this->add_render_attribute('tbody_td_attributes'. esc_attr($i . $j), [
								'colspan' => $table_td[$j]['colspan'] > 1 ? $table_td[$j]['colspan'] : '',
								'rowspan' => $table_td[$j]['rowspan'] > 1 ? $table_td[$j]['rowspan'] : '',
								'class' => $table_td[$j]['class']
								]); ?>
								
                                <?php echo wp_kses_post('<td '.$this->get_render_attribute_string('tbody_td_attributes'. esc_attr($i . $j)).'>'); ?>

								<div class="tmpcoder-td-content-wrapper <?php echo esc_attr(('top' === $table_td[$j]['icon_position']) ? 'tmpcoder-flex-column' : (('bottom' === $table_td[$j]['icon_position']) ? 'tmpcoder-flex-column-reverse' : '')) ?>">

									<?php $table_td[$j]['icon'] === 'yes' && ($table_td[$j]['icon_position'] === 'left' || $table_td[$j]['icon_position'] === 'top' || $table_td[$j]['icon_position'] === 'bottom') ? $this->render_td_icon_or_image($table_td, $j) : '' ?>
									<?php if ( '' !== $table_td[$j]['content'] ) : 
										  if ( '' !== $table_td[$j]['link']['url'] ) : ?>
											<a href="<?php echo esc_url($table_td[$j]['link']['url']) ?>" target="<?php echo esc_attr($table_td[$j]['external']) ?>">
									<?php else : ?>
											<span>
									<?php endif; ?> 
											<span class="tmpcoder-table-text">
												<?php 
													echo wp_kses_post( $table_td[$j]['content'] );

													$this->render_tooltip_icon( $table_td[$j] );
													
													$this->render_content_tooltip( $table_td[$j] ); 
												?>
											</span>
										<?php if ( '' !== $table_td[$j]['link']['url'] ) : ?>
										</a>
										<?php else : ?>
										</span>
										<?php endif; ?>
									<?php endif;  ?>
									<?php $table_td[$j]['icon'] === 'yes' && $table_td[$j]['icon_position'] === 'right' ? $this->render_td_icon_or_image($table_td, $j) : '' ?>

								</div>

							</td>
							<?php }
							} ?>
					</tr>
			        <?php endfor; ?>
				</tbody>
			</table>
		</div>
		</div>
    	<?php }
			if ( 'yes' == $settings['enable_custom_pagination'] ) {
				$this->render_custom_pagination($settings, null);
			}
		}
  	}
}