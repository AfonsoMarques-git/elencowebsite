<?php
namespace TMPCODER\Extensions\TMPCODER_Section_Floating_Effects;

// Elementor Classes.
use Elementor\Repeater;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Module For Premium Floating Effects Addon.
 */
class TMPCODER_Section_Floating_Effects {

	/**
	 * Load Script
	 *
	 * @var $load_script
	 */
	private static $load_script = null;

	/**
	 * Class object
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 * Class Constructor Function
	 */
	public function __construct() {

		// Enqueue the required JS file.
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Creates Premium Floating Effects tab at the end of advanced tab.
		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'register_controls' ), 10 );

		// Check if scripts should be loaded.
		add_action( 'elementor/frontend/widget/before_render', array( $this, 'check_script_enqueue' ) );
	}

	/**
	 * Enqueue scripts.
	 *
	 * Registers required dependencies for the extension and enqueues them.
	 *
	 * @since 1.6.5
	 * @access public
	 */
	public function enqueue_scripts() {

		if ( ! wp_script_is( 'tmpcoder-anime', 'enqueued' ) ) {
			wp_enqueue_script( 'tmpcoder-anime' );
		}

		if ( ! wp_script_is( 'tmpcoder-feffects', 'enqueued' ) ) {
			wp_enqueue_script( 'tmpcoder-feffects' );
		}
	}


	/**
	 * Register Floating Effects controls.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param object $element for current element.
	 */
	public function register_controls( $element ) {

		$element->start_controls_section(
			'section_tmpcoder_fe',
			array(
				'label' => esc_html('Floating Effects - Spexo Addons'),
				'tab' => Controls_Manager::TAB_ADVANCED
			)
		);

		$element->add_control(
			'tmpcoder_fe_switcher',
			array(
				'label'        => __( 'Enable Floating Effects', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'tmpcoder-floating-effects-',
				'render_type'  => 'template',
			)
		);

		$doc_link = TMPCODER_DOCUMENTATION_URL.'floating-effects/';

		$element->add_control(
			'tmpcoder_floating_effects_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<a href="' . esc_url( $doc_link ) . '" target="_blank">' . __( 'How to use Floating Effects for Elementor »', 'sastra-essential-addons-for-elementor' ) . '</a>',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
			)
		);

		$element->add_control(
			'tmpcoder_fe_target',
			array(
				'label'              => __( 'Custom CSS Selector', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'description'        => __( 'Set this option if you want to apply floating effects on specfic selector inside your widget. For example, .tmpcoder-dual-header-container', 'sastra-essential-addons-for-elementor' ),
				'dynamic'            => array( 'active' => true ),
				'label_block'        => true,
				'render_type'        => 'template',
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$pro_activated = tmpcoder_is_availble();

		if ( ! $pro_activated ) {
			$element->add_control(
				'floating_effects_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( 'The options in Style and Filters tabs are available in Spexo Addons Pro.', 'sastra-essential-addons-for-elementor' ) . '<a href="' . esc_url( TMPCODER_PURCHASE_PRO_URL ) . '" target="_blank">' . __( 'Upgrade now!', 'sastra-essential-addons-for-elementor' ) . '</a>',
					'content_classes' => 'tmpcoder-pro-upgrade-notice',
				)
			);
		}

		$element->start_controls_tabs( 'effects_tabs' );

		$element->start_controls_tab(
			'motion_effects_tab',
			array(
				'label'     => __( 'Motion', 'sastra-essential-addons-for-elementor' ),
				'condition' => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
			)
		);

		/**--------Translate Effect Controls---------*/
		$element->add_control(
			'tmpcoder_fe_translate_switcher',
			array(
				'label'              => __( 'Translate', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'tmpcoder_fe_Xtranslate',
			array(
				'label'              => __( 'Translate X', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 5,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min'  => -150,
						'max'  => 150,
						'step' => 1,
					),
				),
				'labels'             => array(
					__( 'From', 'sastra-essential-addons-for-elementor' ),
					__( 'To', 'sastra-essential-addons-for-elementor' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'tmpcoder_fe_switcher'           => 'yes',
					'tmpcoder_fe_translate_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'tmpcoder_fe_Ytranslate',
			array(
				'label'              => __( 'Translate Y', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 5,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min'  => -150,
						'max'  => 150,
						'step' => 1,
					),
				),
				'labels'             => array(
					__( 'From', 'sastra-essential-addons-for-elementor' ),
					__( 'To', 'sastra-essential-addons-for-elementor' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'tmpcoder_fe_switcher'           => 'yes',
					'tmpcoder_fe_translate_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_trans_duration',
			array(
				'label'              => __( 'Duration', 'sastra-essential-addons-for-elementor' ) . ' (ms)',
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'default'            => array(
					'unit' => 'px',
					'size' => 1000,
				),
				'condition'          => array(
					'tmpcoder_fe_switcher'           => 'yes',
					'tmpcoder_fe_translate_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_trans_delay',
			array(
				'label'              => __( 'Delay', 'sastra-essential-addons-for-elementor' ) . ' (ms)',
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'condition'          => array(
					'tmpcoder_fe_switcher'           => 'yes',
					'tmpcoder_fe_translate_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		/**--------Rotate Effect Controls---------*/
		$element->add_control(
			'tmpcoder_fe_rotate_switcher',
			array(
				'label'              => __( 'Rotate', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'tmpcoder_fe_Xrotate',
			array(
				'label'              => __( 'Rotate X', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 45,
					),
					'unit'  => 'deg',
				),
				'range'              => array(
					'deg' => array(
						'min' => -180,
						'max' => 180,
					),
				),
				'labels'             => array(
					__( 'From', 'sastra-essential-addons-for-elementor' ),
					__( 'To', 'sastra-essential-addons-for-elementor' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'tmpcoder_fe_switcher'        => 'yes',
					'tmpcoder_fe_rotate_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'tmpcoder_fe_Yrotate',
			array(
				'label'              => __( 'Rotate Y', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 45,
					),
					'unit'  => 'deg',
				),
				'range'              => array(
					'deg' => array(
						'min' => -180,
						'max' => 180,
					),
				),
				'labels'             => array(
					__( 'From', 'sastra-essential-addons-for-elementor' ),
					__( 'To', 'sastra-essential-addons-for-elementor' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'tmpcoder_fe_switcher'        => 'yes',
					'tmpcoder_fe_rotate_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'tmpcoder_fe_Zrotate',
			array(
				'label'              => __( 'Rotate Z', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 45,
					),
					'unit'  => 'deg',
				),
				'range'              => array(
					'deg' => array(
						'min' => -180,
						'max' => 180,
					),
				),
				'labels'             => array(
					__( 'From', 'sastra-essential-addons-for-elementor' ),
					__( 'To', 'sastra-essential-addons-for-elementor' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'tmpcoder_fe_switcher'        => 'yes',
					'tmpcoder_fe_rotate_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_rotate_duration',
			array(
				'label'              => __( 'Duration', 'sastra-essential-addons-for-elementor' ) . ' (ms)',
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'default'            => array(
					'unit' => 'px',
					'size' => 1000,
				),
				'condition'          => array(
					'tmpcoder_fe_switcher'        => 'yes',
					'tmpcoder_fe_rotate_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_rotate_delay',
			array(
				'label'              => __( 'Delay', 'sastra-essential-addons-for-elementor' ) . ' (ms)',
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'condition'          => array(
					'tmpcoder_fe_switcher'        => 'yes',
					'tmpcoder_fe_rotate_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		/**--------Scale Effect Controls---------*/
		$element->add_control(
			'tmpcoder_fe_scale_switcher',
			array(
				'label'              => __( 'Scale', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'tmpcoder_fe_Xscale',
			array(
				'label'              => __( 'Scale X', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 1,
						'to'   => 1.2,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'labels'             => array(
					__( 'From', 'sastra-essential-addons-for-elementor' ),
					__( 'To', 'sastra-essential-addons-for-elementor' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'tmpcoder_fe_switcher'       => 'yes',
					'tmpcoder_fe_scale_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'tmpcoder_fe_Yscale',
			array(
				'label'              => __( 'Scale Y', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 1,
						'to'   => 1.2,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'labels'             => array(
					__( 'From', 'sastra-essential-addons-for-elementor' ),
					__( 'To', 'sastra-essential-addons-for-elementor' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'tmpcoder_fe_switcher'       => 'yes',
					'tmpcoder_fe_scale_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_scale_duration',
			array(
				'label'              => __( 'Duration', 'sastra-essential-addons-for-elementor' ) . ' (ms)',
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'default'            => array(
					'unit' => 'px',
					'size' => 1000,
				),
				'condition'          => array(
					'tmpcoder_fe_switcher'       => 'yes',
					'tmpcoder_fe_scale_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_scale_delay',
			array(
				'label'              => __( 'Delay', 'sastra-essential-addons-for-elementor' ) . ' (ms)',
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'condition'          => array(
					'tmpcoder_fe_switcher'       => 'yes',
					'tmpcoder_fe_scale_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		/**--------Skew Effect Controls---------*/
		$element->add_control(
			'tmpcoder_fe_skew_switcher',
			array(
				'label'              => __( 'Skew', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'tmpcoder_fe_Xskew',
			array(
				'label'              => __( 'Skew X', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 20,
					),
					'unit'  => 'deg',
				),
				'range'              => array(
					'deg' => array(
						'min' => -180,
						'max' => 180,
					),
				),
				'labels'             => array(
					__( 'From', 'sastra-essential-addons-for-elementor' ),
					__( 'To', 'sastra-essential-addons-for-elementor' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'tmpcoder_fe_switcher'      => 'yes',
					'tmpcoder_fe_skew_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'tmpcoder_fe_Yskew',
			array(
				'label'              => __( 'Skew Y', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 20,
					),
					'unit'  => 'deg',
				),
				'range'              => array(
					'deg' => array(
						'min' => -180,
						'max' => 180,
					),
				),
				'labels'             => array(
					__( 'From', 'sastra-essential-addons-for-elementor' ),
					__( 'To', 'sastra-essential-addons-for-elementor' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'tmpcoder_fe_switcher'      => 'yes',
					'tmpcoder_fe_skew_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_skew_duration',
			array(
				'label'              => __( 'Duration', 'sastra-essential-addons-for-elementor' ) . ' (ms)',
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'default'            => array(
					'unit' => 'px',
					'size' => 1000,
				),
				'condition'          => array(
					'tmpcoder_fe_switcher'      => 'yes',
					'tmpcoder_fe_skew_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_skew_delay',
			array(
				'label'              => __( 'Delay', 'sastra-essential-addons-for-elementor' ) . ' (ms)',
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'condition'          => array(
					'tmpcoder_fe_switcher'      => 'yes',
					'tmpcoder_fe_skew_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'css_effects_tab',
			array(
				'label'     => __( 'Style', 'sastra-essential-addons-for-elementor' ),
				'condition' => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
			)
		);

		/**--------CSS Properties Effect Controls---------*/

		/**--------Opacity Effect Controls---------*/
		$element->add_control(
			'tmpcoder_fe_opacity_switcher',
			array(
				'label'              => __( 'Opacity', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		do_action( 'tmpcoder_floating_opacity_controls', $element );

		/**--------Background Color Effect Controls---------*/
		$element->add_control(
			'tmpcoder_fe_bg_color_switcher',
			array(
				'label'              => __( 'Background Color', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		do_action( 'tmpcoder_floating_bg_controls', $element );

		$element->end_controls_tab();

		$element->start_controls_tab(
			'filters_effects_tab',
			array(
				'label'     => __( 'Filters', 'sastra-essential-addons-for-elementor' ),
				'condition' => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
			)
		);

		/**--------  CSS Filter Blur Controls---------*/
		$element->add_control(
			'tmpcoder_fe_blur_switcher',
			array(
				'label'              => __( 'Blur', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		do_action( 'tmpcoder_floating_blur_controls', $element );

		/**--------  CSS Filter Contrast Controls---------*/
		$element->add_control(
			'tmpcoder_fe_contrast_switcher',
			array(
				'label'              => __( 'Contrast', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		do_action( 'tmpcoder_floating_contrast_controls', $element );

		/**--------  CSS Filter grayscale Controls---------*/
		$element->add_control(
			'tmpcoder_fe_gScale_switcher',
			array(
				'label'              => __( 'Grayscale', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		do_action( 'tmpcoder_floating_gs_controls', $element );

		/**--------  CSS Filter Hue Controls---------*/
		$element->add_control(
			'tmpcoder_fe_hue_switcher',
			array(
				'label'              => __( 'Hue', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		do_action( 'tmpcoder_floating_hue_controls', $element );

		/**--------  CSS Filter Brightness Controls---------*/
		$element->add_control(
			'tmpcoder_fe_brightness_switcher',
			array(
				'label'              => __( 'Brightness', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		do_action( 'tmpcoder_floating_brightness_controls', $element );

		/**--------  CSS Filter Saturation Controls---------*/
		$element->add_control(
			'tmpcoder_fe_saturate_switcher',
			array(
				'label'              => __( 'Saturation ', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		do_action( 'tmpcoder_floating_saturation_controls', $element );

		$element->end_controls_tab();

		$element->end_controls_tabs();

		/**-------- General Settings Controls---------*/
		$element->add_control(
			'tmpcoder_fe_general_settings_heading',
			array(
				'label'     => __( 'General Settings', 'sastra-essential-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
			)
		);

		$element->add_control(
			'tmpcoder_fe_direction',
			array(
				'label'              => __( 'Direction', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'alternate',
				'options'            => array(
					'normal'    => __( 'Normal', 'sastra-essential-addons-for-elementor' ),
					'reverse'   => __( 'Reverse', 'sastra-essential-addons-for-elementor' ),
					'alternate' => __( 'Alternate', 'sastra-essential-addons-for-elementor' ),
				),
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_loop',
			array(
				'label'              => __( 'Loop', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'default',
				'options'            => array(
					'default' => __( 'Infinite', 'sastra-essential-addons-for-elementor' ),
					'number'  => __( 'Custom', 'sastra-essential-addons-for-elementor' ),
				),
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_loop_number',
			array(
				'label'              => __( 'Number', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 3,
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
					'tmpcoder_fe_loop'     => 'number',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_easing',
			array(
				'label'              => __( 'Easing', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'easeInOutSine',
				'options'            => array(
					'linear'                  => __( 'Linear', 'sastra-essential-addons-for-elementor' ),
					'easeInOutSine'           => __( 'easeInOutSine', 'sastra-essential-addons-for-elementor' ),
					'easeInOutExpo'           => __( 'easeInOutExpo', 'sastra-essential-addons-for-elementor' ),
					'easeInOutQuart'          => __( 'easeInOutQuart', 'sastra-essential-addons-for-elementor' ),
					'easeInOutCirc'           => __( 'easeInOutCirc', 'sastra-essential-addons-for-elementor' ),
					'easeInOutBack'           => __( 'easeInOutBack', 'sastra-essential-addons-for-elementor' ),
					'steps'                   => __( 'Steps', 'sastra-essential-addons-for-elementor' ),
					'easeInElastic(1, .6)'    => __( 'Elastic In', 'sastra-essential-addons-for-elementor' ),
					'easeOutElastic(1, .6)'   => __( 'Elastic Out', 'sastra-essential-addons-for-elementor' ),
					'easeInOutElastic(1, .6)' => __( 'Elastic In Out', 'sastra-essential-addons-for-elementor' ),
				),
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
				'frontend_available' => true,

			)
		);

		$element->add_control(
			'tmpcoder_fe_ease_step',
			array(
				'label'              => __( 'Steps', 'sastra-essential-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5,
				'condition'          => array(
					'tmpcoder_fe_switcher' => 'yes',
					'tmpcoder_fe_easing'   => 'steps',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'tmpcoder_fe_disable_safari',
			array(
				'label'        => __( 'Disable Floating Effects On Safari', 'sastra-essential-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'tmpcoder-disable-fe-',
				'separator'    => 'before',
				'condition'    => array(
					'tmpcoder_fe_switcher' => 'yes',
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Check Script Enqueue
	 *
	 * Check if the script files should be loaded.
	 *
	 * @since 4.7.4
	 * @access public
	 *
	 * @param object $element for current element.
	 */
	public function check_script_enqueue( $element ) {

		if ( self::$load_script ) {
			return;
		}

		if ( 'yes' === $element->get_settings_for_display( 'tmpcoder_fe_switcher' ) ) {
			$this->enqueue_scripts();

			self::$load_script = true;

			remove_action( 'elementor/frontend/widget/before_render', array( $this, 'check_script_enqueue' ) );
		}
	}

	/**
	 * Creates and returns an instance of the class
	 *
	 * @since 4.2.5
	 * @access public
	 *
	 * @return object
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {

			self::$instance = new self();

		}

		return self::$instance;
	}
}

new TMPCODER_Section_Floating_Effects();