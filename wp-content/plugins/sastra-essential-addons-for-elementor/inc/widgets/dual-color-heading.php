<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Dual_Color_Heading extends Widget_Base {

	public function get_name() {
		return 'tmpcoder-dual-color-heading';
	}

	public function get_title() {
		return esc_html__('Dual Color Heading', 'sastra-essential-addons-for-elementor');
	}
	public function get_icon() {
		return 'tmpcoder-icon eicon-heading';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category' ];
	}

	public function get_keywords() {
		return [ 'Dual Color Heading'];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

    public function get_style_depends() {
		return [ 'tmpcoder-dual-color-heading' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Settings', 'sastra-essential-addons-for-elementor'),
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'dual_heading_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h2'
			]
		);

		$this->add_control(
			'content_style',
			[
				'label' => esc_html__('Select Layout', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon-top',
				'options' => [
					'default'  => esc_html__('Default', 'sastra-essential-addons-for-elementor'),
					'icon-top'  => esc_html__('Icon Top', 'sastra-essential-addons-for-elementor'),
					'desc-top'  => esc_html__('Description Top', 'sastra-essential-addons-for-elementor'),
					'icon-and-desc-top'  => esc_html__('Heading Bottom', 'sastra-essential-addons-for-elementor'),
				],
				'prefix_class' => 'tmpcoder-dual-heading-',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __('Alignment', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'sastra-essential-addons-for-elementor'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'sastra-essential-addons-for-elementor'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'sastra-essential-addons-for-elementor'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-heading-wrap' => 'text-align: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'primary_heading',
			[
				'label'   => __('Primary Heading', 'sastra-essential-addons-for-elementor'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Dual Color', 'sastra-essential-addons-for-elementor'),
				'separator' => 'before',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'secondary_heading',
			[
				'label'   => __('Secondary Heading', 'sastra-essential-addons-for-elementor'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Heading', 'sastra-essential-addons-for-elementor'),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => __('Show Description', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'sastra-essential-addons-for-elementor'),
				'label_off' => __('Hide', 'sastra-essential-addons-for-elementor'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => __('Description', 'sastra-essential-addons-for-elementor'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __('Description text here', 'sastra-essential-addons-for-elementor'),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_description' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label' => __('Show Icon', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'sastra-essential-addons-for-elementor'),
				'label_off' => __('Hide', 'sastra-essential-addons-for-elementor'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'feature_list_icon',
			[
				'label' => esc_html__('Select Icon', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-rocket',
					'library' => 'solid',
				],
				'condition' => [
					'show_icon' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		$this->start_controls_section(
			'primary_heading_styles',
			[
				'label' => esc_html__('Primary Heading', 'sastra-essential-addons-for-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'primary_heading_bg_color',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-dual-title .first'
			]
		);

		$this->add_control(
			'primary_heading_color',
			[
				'label' => __('Text Color', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#7B7B7B',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-title .first' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'primary_heading_border_color',
			[
				'label' => __('Border Color', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-title .first' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'primary_heading_typography',
				'label' => __('Typography', 'sastra-essential-addons-for-elementor'),
				'selector' => '{{WRAPPER}} .tmpcoder-dual-title .first',
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
							'size' => '32',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'primary_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-title .first' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'primary_heading_border_type',
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
					'{{WRAPPER}} .tmpcoder-dual-title .first' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'primary_heading_border_width',
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
					'{{WRAPPER}} .tmpcoder-dual-title .first' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'primary_heading_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'primary_heading_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-title .first' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'feature_list_title_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-title-wrap'  => 'margin-bottom: {{SIZE}}px;',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'feature_list_title_gutter',
			[
				'label' => esc_html__( 'Gutter', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-title .first'  => 'margin-right: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'secondary_heading_styles',
			[
				'label' => esc_html__('Secondary Heading', 'sastra-essential-addons-for-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'secondary_heading_bg_color',
				'label' => esc_html__( 'Background', 'sastra-essential-addons-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-dual-title .second'
			]
		);

		$this->add_control(
			'secondary_heading_color',
			[
				'label' => __('Text Color', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#9E5BE5',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-title .second' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'secondary_heading_border_color',
			[
				'label' => __('Border Color', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-title .second' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'secondary_heading_typography',
				'label' => __('Typography', 'sastra-essential-addons-for-elementor'),
				'selector' => '{{WRAPPER}} .tmpcoder-dual-title .second',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '600',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '32',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'secondary_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-title .second' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'secondary_heading_border_type',
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
					'{{WRAPPER}} .tmpcoder-dual-title .second' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'secondary_heading_border_width',
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
					'{{WRAPPER}} .tmpcoder-dual-title .second' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'secondary_heading_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'secondary_heading_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-title .second' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'general_styles_description',
			[
				'label' => esc_html__('Description', 'sastra-essential-addons-for-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_description' => 'yes'
				]
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __('Color', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#989898',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-heading-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __('Typography', 'sastra-essential-addons-for-elementor'),
				'selector' => '{{WRAPPER}} .tmpcoder-dual-heading-description',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'feature_list_description_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-heading-description'  => 'margin-bottom: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_styles_icon',
			[
				'label' => esc_html__('Icon', 'sastra-essential-addons-for-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __('Color', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-heading-icon-wrap' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-dual-heading-icon-wrap svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__('Size', 'sastra-essential-addons-for-elementor'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 35,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-heading-icon-wrap' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tmpcoder-dual-heading-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'feature_list_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-dual-heading-icon-wrap'  => 'margin-bottom: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );

		$this->add_inline_editing_attributes('title', 'none');
		$this->add_inline_editing_attributes('description', 'basic');
		$this->add_inline_editing_attributes('content', 'advanced');

        ?>
			<div class="tmpcoder-dual-heading-wrap">
				<div class="tmpcoder-dual-title-wrap">
					<<?php echo esc_attr( tmpcoder_validate_html_tag($settings['dual_heading_tag']) ); ?> class="tmpcoder-dual-title">
					<?php if (!empty($settings['primary_heading'])) : ?>
						<span class="first"><?php echo esc_html($settings['primary_heading']); ?></span>
					<?php endif; ?>
					
					<?php if (!empty($settings['secondary_heading'])) : ?>
						<span class="second"><?php echo esc_html($settings['secondary_heading']); ?></span>
					<?php endif; ?>
					</<?php echo esc_attr( tmpcoder_validate_html_tag($settings['dual_heading_tag']) ); ?>>
				</div>
				
				<?php if ('yes' == $settings['show_description']) { ?>
					<?php echo wp_kses_post('<div class="tmpcoder-dual-heading-description" '. $this->get_render_attribute_string('description').'>'. esc_html($settings['description']).'</div>'); ?>
				<?php } ?>

				<?php if ('yes' == $settings['show_icon']) { ?>
					<div class="tmpcoder-dual-heading-icon-wrap">
						<?php \Elementor\Icons_Manager::render_icon($settings['feature_list_icon'], ['aria-hidden' => 'true']); ?>
					</div>
				<?php } ?>

			</div>
		<?php
	}
}
