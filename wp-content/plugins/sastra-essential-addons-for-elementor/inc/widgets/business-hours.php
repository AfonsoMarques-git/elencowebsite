<?php
namespace TMPCODER\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
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

class TMPCODER_Business_Hours extends Widget_Base {
		
	public function get_name() {
		return 'tmpcoder-business-hours';
	}

	public function get_title() {
		return esc_html__( 'Business Hours', 'sastra-essential-addons-for-elementor' );
	}

	public function get_icon() {
		return 'tmpcoder-icon eicon-clock-o';
	}

	public function get_categories() {
		return [ 'tmpcoder-widgets-category'];
	}

	public function get_keywords() {
		return [ 'business hours', 'opening Hours', 'opening times', 'currently Open' ];
	}

    public function get_custom_help_url() {
		return TMPCODER_NEED_HELP_URL;
    }

    public function get_style_depends() {
		return [ 'tmpcoder-business-hours' ];
	}

	public function add_repeater_args_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => [
				'value' => '',
				'library' => '',
			],
		];
	}

	public function add_repeater_args_highlight() {
		return [
			// Translators: %s is the icon.
			'label' => sprintf( __( 'Highlight this Item %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
			'classes' => 'tmpcoder-pro-control no-distance'
		];
	}

	public function add_repeater_args_highlight_color() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_highlight_bg_color() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_control_general_even_bg() {
		$this->add_control(
			'general_even_bg',
			[
				// Translators: %s is the icon.
				'label' => sprintf( __( 'Enable Even Color %s', 'sastra-essential-addons-for-elementor' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'tmpcoder-pro-control'
			]
		);	
	}

	public function add_control_general_even_bg_color() {}

	public function add_control_general_icon_color() {}

	public function add_control_general_hover_icon_color() {}

	public function add_control_general_icon_size() {}

	protected function register_controls() {
		
		// Section: Business Hours ---
		$this->start_controls_section(
			'tmpcoder__section_business_hours_items',
			[
				'label' => esc_html__( 'Business Hours', 'sastra-essential-addons-for-elementor' ),
			]
		);

		tmpcoder_library_buttons( $this, Controls_Manager::RAW_HTML );

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_business_hours_item' );

		$repeater->add_control(
			'day',
			[
				'label' => esc_html__( 'Day', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Monday',
			]
		);

		$repeater->add_control( 'icon', $this->add_repeater_args_icon() );

		$repeater->add_control(
			'time',
			[
				'label' => esc_html__( 'Time', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '08:00 AM - 05:00 PM',
				'separator' => 'before'
			]
		);

		$repeater->add_control( 'highlight', $this->add_repeater_args_highlight() );

		$repeater->add_control( 'highlight_color', $this->add_repeater_args_highlight_color() );

		$repeater->add_control( 'highlight_bg_color', $this->add_repeater_args_highlight_bg_color() );

		$repeater->add_control(
			'closed',
			[
				'label' => esc_html__( 'Closed', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'closed_text',
			[
				'label' => esc_html__( 'Closed Text', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Closed',
				'condition' => [
					'closed' => 'yes',
				],
			]
		);

		if ( ! tmpcoder_is_availble() ) {
			$repeater->add_control(
				'business_hours_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Custom Icon and Even/Odd Item Background Color</span> options are available in the <strong><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=rea-plugin-panel-business-hours-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Custom Icon and Even/Odd Item Background Color</span> options are available in the <strong><a href="'. admin_url('admin.php?page=sastra-addon-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'tmpcoder-pro-notice',
				]
			);
		}

		$this->add_control(
			'hours_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'day' => 'Monday',
					],
					[
						'day' => 'Tuesday',
					],
					[
						'day' => 'Wednesday',
					],
					[
						'day' => 'Thursday',
					],
					[
						'day' => 'Friday',
					],
					[
						'day' => 'Saturday',
						'time' => '08:00 AM - 01:00 PM',
					],
					[
						'day' => 'Sunday',
						'closed' => 'yes',
					],
				],
				'title_field' => '{{{ day }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		tmpcoder_pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'business-hours', [
			__('List Item Custom Icon options', 'sastra-essential-addons-for-elementor'),
            __('List Item Custom Text & Background Color options', 'sastra-essential-addons-for-elementor'),
			__('List Item Even/Odd Background Color option', 'sastra-essential-addons-for-elementor'),
		] );
		
		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'tmpcoder__section_style_general',
			[
				'label' => esc_html__( 'General', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_general_colors' );

		$this->start_controls_tab(
			'tab_general_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'general_day_color',
			[
				'label' => esc_html__( 'Day Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-day' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control_general_icon_color();

		$this->add_control(
			'general_time_color',
			[
				'label' => esc_html__( 'Time Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-time' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'general_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-hours-item' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tmpcoder-business-hours' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_general_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'sastra-essential-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'general_hover_day_color',
			[
				'label' => esc_html__( 'Day Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-hours .tmpcoder-business-hours-item:not(.tmpcoder-business-hours-item-closed):hover .tmpcoder-business-day' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control_general_hover_icon_color();

		$this->add_control(
			'general_hover_time_color',
			[
				'label' => esc_html__( 'Time Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-hours .tmpcoder-business-hours-item:not(.tmpcoder-business-hours-item-closed):hover .tmpcoder-business-time' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f7f7f7',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-hours .tmpcoder-business-hours-item:not(.tmpcoder-business-hours-item-closed):hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_general_even_bg();

		$this->add_control_general_even_bg_color();

		$this->add_control(
			'general_closed_section',
			[
				'label' => esc_html__( 'Closed', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_closed_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5729d9',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-hours-item-closed' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_closed_day_color',
			[
				'label' => esc_html__( 'Day Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-hours-item-closed .tmpcoder-business-day' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_closed_color',
			[
				'label' => esc_html__( 'Text Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-hours-item-closed .tmpcoder-business-closed' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_day_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Day Typography', 'sastra-essential-addons-for-elementor' ),
				'name' => 'general_day_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-business-day',
			]
		);

		$this->add_control_general_icon_size();

		$this->add_control(
			'general_time_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Time Typography', 'sastra-essential-addons-for-elementor' ),
				'name' => 'general_time_typography',
				'selector' => '{{WRAPPER}} .tmpcoder-business-time,{{WRAPPER}} .tmpcoder-business-closed',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_divider',
			[
				'label' => esc_html__( 'Divider', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_divider_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-hours-item:after' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'general_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_divider_type',
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
					'{{WRAPPER}} .tmpcoder-business-hours-item:after' => 'border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'general_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_divider_weight',
			[
				'label' => esc_html__( 'Weight', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-hours-item:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'general_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_padding',
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
					'{{WRAPPER}} .tmpcoder-business-hours-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-business-hours' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
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
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .tmpcoder-business-hours',
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
					'{{WRAPPER}} .tmpcoder-business-hours' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$item_count = 0;

		?>

		<div class="tmpcoder-business-hours">

			<?php

			foreach ( $settings['hours_items'] as $item ) : 

				if (   '' !== $item['day'] || '' !== $item['time'] ) : 

				$this->add_render_attribute( 'hours_item_attribute'. $item_count, 'class', 'tmpcoder-business-hours-item elementor-repeater-item-'.esc_attr( $item['_id'] ) );

				if ( 'yes' === $item['closed'] ) {
					$this->add_render_attribute( 'hours_item_attribute'. $item_count, 'class', 'tmpcoder-business-hours-item-closed' );
				}

				?>
				
				<?php echo wp_kses_post('<div '. $this->get_render_attribute_string( 'hours_item_attribute'. esc_attr($item_count) ).' >');
                ?>
					<?php if ( '' !== $item['day'] ) : ?>	
					<span class="tmpcoder-business-day">

						<?php
						if (is_array($item['icon']['value'])) {
						echo wp_kses(tmpcoder_render_svg_icon($item['icon']),tmpcoder_wp_kses_allowed_html());
						}
						else
						{
						 	if ( '' !== $item['icon']['value'] ) : 
						 		echo '<i class="'. esc_attr($item['icon']['value']) .'"></i>';
							endif;
						}
						?>

						<?php echo esc_html($item['day']); ?>
					</span>
					<?php endif; ?>

					<?php if ( 'yes' === $item['closed'] ) : ?>	
					<span class="tmpcoder-business-closed"><?php echo esc_html($item['closed_text']); ?></span>
					<?php elseif ( '' !== $item['time'] ) : ?>	
					<span class="tmpcoder-business-time"><?php echo esc_html($item['time']); ?></span>
					<?php endif; ?>

				</div>

				<?php

				endif;

				$item_count++;

			endforeach;

			?>

		</div>
		<?php
	}
}