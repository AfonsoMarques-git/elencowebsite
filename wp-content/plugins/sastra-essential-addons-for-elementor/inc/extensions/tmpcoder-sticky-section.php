<?php
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Core\Base\Module;
use Elementor\Core\Kits\Documents\Tabs\Settings_Layout;
use Elementor\Core\Responsive\Files\Frontend;
use Elementor\Plugin;
use Elementor\Core\Breakpoints\Manager;
use Elementor\Core\Breakpoints;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Sticky_Section {

    public function __construct() {
		add_action( 'elementor/element/section/section_background/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );

        // FLEXBOX
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10);
        add_action( 'elementor/container/print_template', [ $this, '_print_template' ], 10, 2 );
        add_action('elementor/frontend/container/before_render', [$this, '_before_render'], 10, 1);
    }

	public static function add_control_group_sticky_advanced_options($element) {

		$element->add_control(
			'sticky_advanced_pro_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<strong>Advanced Options</strong> are available in the <br><a href="'.TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-panel-advanced-stiky-upgrade-pro#purchasepro" target="_blank">Pro Version.</a> You\'ll have the ability to create impressive menu effects.',
				'content_classes' => 'tmpcoder-pro-notice',
                'condition' => [
                    'enable_sticky_section' => 'yes'
                ]
			]
		);
	}

    public function register_controls( $element ) {

		if ( ( 'section' === $element->get_name() || 'container' === $element->get_name() ) ) {

			$element->start_controls_section (
				'tmpcoder_section_sticky_section',
				[
					'tab'   => Controls_Manager::TAB_ADVANCED,
                    'label' => esc_html('Sticky Section - Spexo Addons'),
				]
			);

			$element->add_control(
				'tmpcoder_sticky_apply_changes',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<div class="elementor-update-preview editor-tmpcoder-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
					'separator' => 'after'
				]
			);

			$element->add_control (
				'enable_sticky_section',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Make This Section Sticky', 'sastra-essential-addons-for-elementor' ),
					'default' => 'no',
					'return_value' => 'yes',
					'prefix_class' => 'tmpcoder-sticky-section-',
					'render_type' => 'template',
				]
			);

			$element->add_control(
				'enable_on_devices',
				[
					'label' => esc_html__( 'Enable on Devices', 'sastra-essential-addons-for-elementor' ),
					'label_block' => true,
					'type' => Controls_Manager::SELECT2,
					'default' => ['desktop_sticky'],
					'options' => $this->breakpoints_manager(),
					'multiple' => true,
					'separator' => 'before',
					'condition' => [
						'enable_sticky_section' => 'yes'
					],

				]
			);
            
			$element->add_control (
				'position_type',
				[
					'label' => __( 'Position Type', 'sastra-essential-addons-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'sticky',
					'options' => [
						'sticky'  => __( 'Stick on Scroll', 'sastra-essential-addons-for-elementor' ),
						'fixed' => __( 'Fixed by Default', 'sastra-essential-addons-for-elementor' ),
					],
                    // 'selectors' => [
					// 	'{{WRAPPER}}' => 'position: {{VALUE}};',
                    // ],
					'render_type' => 'template',
					'condition' => [
						'enable_sticky_section' => 'yes'
					],
				]
			);
            
			$element->add_control (
				'sticky_type',
				[
					'label' => __( 'Sticky Relation', 'sastra-essential-addons-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'description' => __('Please switch to *Window* if you are going to use <span style="color: red;">*Advanced Options*</span>.', 'sastra-essential-addons-for-elementor'),
					'default' => 'sticky',
					'options' => [
						'sticky'  => __( 'Parent', 'sastra-essential-addons-for-elementor' ),
						'fixed' => __( 'Window', 'sastra-essential-addons-for-elementor' ),
					],
					'render_type' => 'template',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'position_type' => 'sticky'
					],
				]
			);
            
			$element->add_control (
				'position_location',
				[
					'label' => __( 'Location', 'sastra-essential-addons-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'top',
					'render_type' => 'template',
					'options' => [
						'top' => __( 'Top', 'sastra-essential-addons-for-elementor' ),
						'bottom'  => __( 'Bottom', 'sastra-essential-addons-for-elementor' ),
					],
					// 'selectors_dictionary' => [
					// 	'top' => 'top: {{position_offset.VALUE}}px; bottom: auto;',
					// 	'bottom' => 'bottom: {{position_offset.VALUE}}px; top: auto;'
					// ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'top: auto; bottom: auto; {{VALUE}}: {{position_offset.VALUE}}px;',
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
				]
			);
			
			$element->add_responsive_control(
				'position_offset',
				[
					'label' => __( 'Offset', 'sastra-essential-addons-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 500,
					'required' => true,
					'frontend_available' => true,
					'render_type' => 'template',
					'default' => 0,
					'widescreen_default' => 0,
					'laptop_default' => 0,
					'tablet_extra_default' => 0,
					'tablet_default' => 0,
					'mobile_extra_default' => 0,
					'mobile_default' => 0,
                    'selectors' => [
                        '{{WRAPPER}}' => 'top: auto; bottom: auto; {{position_location.VALUE}}: {{VALUE}}px;',
                        '{{WRAPPER}} + .tmpcoder-hidden-header' => 'top: {{VALUE}}px;',
                        // '{{WRAPPER}} + .tmpcoder-adminbar-replace-header' => 'top: 32px;'
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					],
				]
			);
                
            $element->add_control(
                'tmpcoder_z_index',
                [
                    'label' => esc_html__( 'Z-Index', 'sastra-essential-addons-for-elementor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => -99,
					'max' => 99999,
					'step' => 1,
                    'default' => 999,
                    'selectors' => [
						'{{WRAPPER}}' => 'z-index: {{VALUE}};',
                        '{{WRAPPER}} .tmpcoder-sticky-replace-header-yes + .tmpcoder-hidden-header' => 'z-index: {{VALUE}} !important;'
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
                ]
            );

			$element->add_control(
				'custom_breakpoints',
				[
					'label' => __( 'Breakpoints', 'sastra-essential-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HIDDEN,
					'default' => get_option('elementor_experiment-additional_custom_breakpoints'),
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
				]
			);

			$element->add_control(
				'active_breakpoints',
				[
					'label' => __( 'Active Breakpoints', 'sastra-essential-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HIDDEN,
					'default' => $this->breakpoints_manager_active(),
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
				]
			);

			if ( tmpcoder_is_availble() && defined('TMPCODER_ADDONS_PRO_VERSION') ) {
				if ( class_exists('TMPCODER\Extensions\TMPCODER_Sticky_Section_Pro') ) {
					\TMPCODER\Extensions\TMPCODER_Sticky_Section_Pro::add_control_group_sticky_advanced_options($element);
				}
			} else {
				$this->add_control_group_sticky_advanced_options($element);
			}

            $element->end_controls_section();            
        }
    }

	public function breakpoints_manager() {
		$active_breakpoints = [];
		foreach ( \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints() as $key => $value ) {
			$active_breakpoints[$key . '_sticky'] = esc_html( ucwords(preg_replace('/_/i', ' ', $key)) );
		}

		$active_breakpoints['desktop_sticky'] = esc_html__('Desktop', 'sastra-essential-addons-for-elementor');
		return $active_breakpoints;
	}

	public function breakpoints_manager_active() {
		$active_breakpoints = [];

		foreach ( $this->breakpoints_manager() as $key => $value ) {
			array_push($active_breakpoints, $key);
		}

		return $active_breakpoints;
	}
    
    public function _before_render( $element ) {
        if ( $element->get_name() !== 'section' && $element->get_name() !== 'container' ) {
            return;
        }
		
        $settings = $element->get_settings_for_display();

		if ($settings['enable_sticky_section'] !== 'yes') return;

		$tmpcoder_sticky_effects_offset_widescreen = isset($settings['tmpcoder_sticky_effects_offset_widescreen']) && !empty($settings['tmpcoder_sticky_effects_offset_widescreen']) ? $settings['tmpcoder_sticky_effects_offset_widescreen'] : 0;
		$tmpcoder_sticky_effects_offset_desktop = isset($settings['tmpcoder_sticky_effects_offset']) && !empty($settings['tmpcoder_sticky_effects_offset']) ? $settings['tmpcoder_sticky_effects_offset'] : $tmpcoder_sticky_effects_offset_widescreen;
		$tmpcoder_sticky_effects_offset_laptop =  isset($settings['tmpcoder_sticky_effects_offset_laptop']) && !empty($settings['tmpcoder_sticky_effects_offset_laptop']) ? $settings['tmpcoder_sticky_effects_offset_laptop'] : $tmpcoder_sticky_effects_offset_desktop;
		$tmpcoder_sticky_effects_offset_tablet_extra =  isset($settings['tmpcoder_sticky_effects_offset_tablet_extra']) && !empty($settings['tmpcoder_sticky_effects_offset_tablet_extra']) ? $settings['tmpcoder_sticky_effects_offset_tablet_extra'] : $tmpcoder_sticky_effects_offset_laptop;
		$tmpcoder_sticky_effects_offset_tablet =  isset($settings['tmpcoder_sticky_effects_offset_tablet']) && !empty($settings['tmpcoder_sticky_effects_offset_tablet']) ? $settings['tmpcoder_sticky_effects_offset_tablet'] : $tmpcoder_sticky_effects_offset_tablet_extra;
		$tmpcoder_sticky_effects_offset_mobile_extra =  isset($settings['tmpcoder_sticky_effects_offset_mobile_extra']) && !empty($settings['tmpcoder_sticky_effects_offset_mobile_extra']) ? $settings['tmpcoder_sticky_effects_offset_mobile_extra'] : $tmpcoder_sticky_effects_offset_tablet;
		$tmpcoder_sticky_effects_offset_mobile =  isset($settings['tmpcoder_sticky_effects_offset_mobile']) && !empty($settings['tmpcoder_sticky_effects_offset_mobile']) ? $settings['tmpcoder_sticky_effects_offset_mobile'] : $tmpcoder_sticky_effects_offset_mobile_extra;
		
        if ( $settings['enable_sticky_section'] === 'yes' ) {
            $element->add_render_attribute( '_wrapper', [
                'data-tmpcoder-sticky-section' => $settings['enable_sticky_section'],
                'data-tmpcoder-position-type' => $settings['position_type'],
                'data-tmpcoder-position-offset' => $settings['position_offset'],
                'data-tmpcoder-position-location' => $settings['position_location'],
				'data-tmpcoder-sticky-devices' => $settings['enable_on_devices'],
				'data-tmpcoder-custom-breakpoints' => $settings['custom_breakpoints'],
				'data-tmpcoder-active-breakpoints' => $this->breakpoints_manager_active(),
				'data-tmpcoder-z-index' => isset($settings['tmpcoder_z_index']) ? $settings['tmpcoder_z_index'] : '',
				'data-tmpcoder-sticky-hide' => isset($settings['sticky_hide']) ? $settings['sticky_hide'] : '',
				'data-tmpcoder-replace-header' => isset($settings['sticky_replace_header']) ? $settings['sticky_replace_header'] : '',
				'data-tmpcoder-animation-duration' => isset($settings['sticky_animation_duration']) ? $settings['sticky_animation_duration'] : '',
				'data-tmpcoder-sticky-type' => isset($settings['sticky_type']) ? $settings['sticky_type'] : '',
            ] );
        }
    }

    public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' && $widget->get_name() !== 'container' ) {
			return $template;
		}

		ob_start();

		?>

		<# if ( 'yes' === settings.enable_sticky_section) { #>
			<div class="tmpcoder-sticky-section-yes-editor" data-tmpcoder-z-index={{{settings.tmpcoder_z_index}}} data-tmpcoder-sticky-section={{{settings.enable_sticky_section}}} data-tmpcoder-position-type={{{settings.position_type}}} data-tmpcoder-position-offset={{{settings.position_offset}}} data-tmpcoder-position-location={{{settings.position_location}}} data-tmpcoder-sticky-devices={{{settings.enable_on_devices}}} data-tmpcoder-custom-breakpoints={{{settings.custom_breakpoints}}} data-tmpcoder-active-breakpoints={{{settings.active_breakpoints}}} data-tmpcoder-sticky-animation={{{settings.sticky_animation}}}  data-tmpcoder-offset-settings={{{settings.tmpcoder_sticky_effects_offset}}} data-tmpcoder-sticky-type={{{settings.sticky_type}}}></div>
		<# } #>   

		<?php
		
		// how to render attributes without creating new div using view.addRenderAttributes
		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}
}

new TMPCODER_Sticky_Section();