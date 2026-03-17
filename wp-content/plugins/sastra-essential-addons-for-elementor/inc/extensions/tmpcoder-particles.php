<?php
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Particles {

	private static $_instance = null;

	/**
	 * Load Script
	 *
	 * @var $load_script
	 */
	private static $load_script = null;

	public $default_particles = '{"particles":{"number":{"value":80,"density":{"enable":true,"value_area":800}},"color":{"value":"#000000"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.5,"random":false,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":true,"distance":150,"color":"#000000","opacity":0.4,"width":1},"move":{"enable":true,"speed":6,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"window","events":{"onhover":{"enable":true,"mode":"repulse"},"onclick":{"enable":true,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}' ;

	public function __construct() {

		// Register Scripts
    	add_action( 'elementor/frontend/before_register_scripts', [ $this, 'tmpcoder_register_scripts' ], 998 );

    	// Enqueue the required JS file.
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'elementor/element/section/section_background/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );
		add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );

        add_action( 'elementor/frontend/before_render', array( $this, 'check_script_enqueue' ) );

        // FLEXBOX
        add_action( 'elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10 );
        add_action( 'elementor/frontend/container/before_render', [$this, '_before_render'], 10, 1 );
        add_action( 'elementor/container/print_template', [ $this, '_print_template' ], 10, 2 );
        
        
	}

	public function register_controls( $element ) {

		if ( ( 'section' === $element->get_name() || 'container' === $element->get_name() ) ) {

		$element->start_controls_section (
			'tmpcoder_section_particles',
			[
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => esc_html('Particles - Spexo Addons'),
			]
		);

		$element->add_control(
			'tmpcoder_particles_apply_changes',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-update-preview editor-tmpcoder-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
				'separator' => 'after'
			]
		);

		$element->add_control (
			'tmpcoder_enable_particles',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Enable Particles Background', 'sastra-essential-addons-for-elementor' ),
				'default' => 'no',
				'return_value' => 'yes',
				'prefix_class' => 'tmpcoder-particle-',
				'render_type' => 'template',
			]
		);

        if ( tmpcoder_is_availble() && defined('TMPCODER_ADDONS_PRO_VERSION') ) {
            \TMPCODER\Extensions\TMPCODER_Particles_Pro::add_control_which_particle($element);
        } else {
			$element->add_control (
				'which_particle',
				[
					'label' => __( 'Select Style', 'sastra-essential-addons-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'tmpcoder_particle_json_custom',
					'options' => [
						'tmpcoder_particle_json_custom'  => __( 'Custom', 'sastra-essential-addons-for-elementor' ),
						'pro-pjs' => __( 'Predefined (Pro)', 'sastra-essential-addons-for-elementor' ),
					],
					'condition' => [
						'tmpcoder_enable_particles' => 'yes'
					]
				]
			);

			// Upgrade to Pro Notice
			tmpcoder_upgrade_pro_notice( $element, Controls_Manager::RAW_HTML, 'particles', 'which_particle', ['pro-pjs'] );
        }

		$this->custom_json_particles( $this->default_particles, $element );

		if ( tmpcoder_is_availble() && defined('TMPCODER_ADDONS_PRO_VERSION') ) {
            \TMPCODER\Extensions\TMPCODER_Particles_Pro::add_control_group_predefined_particles($element);
		}

        $element->end_controls_section();

        } // end if()

    }

	public function custom_json_particles($array, $element) {
		$element->add_control(
			'tmpcoder_particle_json_custom_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-control-field-description',
				'raw' => __('<a href="https://vincentgarreau.com/particles.js/" target="_blank">Click here</a> to generate JSON for the below field.', 'sastra-essential-addons-for-elementor'),
				'condition'   => [
					'which_particle' => 'tmpcoder_particle_json_custom',
					'tmpcoder_enable_particles' => 'yes'
				],
			]
		);

		$element->add_control(
			'tmpcoder_particle_json_custom',
			[
				'type'        => Controls_Manager::CODE,
				'label'       => esc_html__( 'Enter Custom JSON', 'sastra-essential-addons-for-elementor' ),
				'default'     => $array,
				'render_type' => 'template',
				'condition'   => [
					'which_particle' => 'tmpcoder_particle_json_custom',
					'tmpcoder_enable_particles' => 'yes'
				],
			]
		);
	}

	public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' && $widget->get_name() !== 'container' ) {
			return $template;
		}
	
		ob_start();

		echo '<div class="tmpcoder-particle-wrapper" id="tmpcoder-particle-{{ view.getID() }}" data-tmpcoder-particles-editor="{{ settings[settings.which_particle] }}" particle-source="{{settings.which_particle}}" tmpcoder-quantity="{{settings.quantity}}" tmpcoder-color="{{settings.particles_color}}" tmpcoder-speed="{{settings.particles_speed}}" tmpcoder-shape="{{settings.particles_shape}}" tmpcoder-size="{{settings.particles_size}}"></div>';

		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}

	public function _before_render( $element ) {
		if ( $element->get_name() !== 'section' && $element->get_name() !== 'container' ) {
			return;
		}

		$settings = $element->get_settings();

		if ( $settings['tmpcoder_enable_particles'] === 'yes' ) {
			$settings['which_particle'] = 'pro-pjs' === $settings['which_particle'] ? 'tmpcoder_particle_json_custom' : $settings['which_particle'];
			
			if ( ! tmpcoder_is_availble() ) {
				$element->add_render_attribute( '_wrapper', [
					'data-tmpcoder-particles' => $settings[$settings['which_particle']],
					'particle-source' => $settings['which_particle'],
				] );
			} else {
				$element->add_render_attribute( '_wrapper', [
					'data-tmpcoder-particles' => $settings[$settings['which_particle']],
					'particle-source' => $settings['which_particle'],
					'tmpcoder-quantity' => $settings['quantity'],
					'tmpcoder-color' => $settings['particles_color'],
					'tmpcoder-speed' => $settings['particles_speed'],
					'tmpcoder-shape' => $settings['particles_shape'],
					'tmpcoder-size' => $settings['particles_size']
				] );
			}
		}
	}

    public static function enqueue_scripts() {

		if ( ! wp_script_is( 'tmpcoder-particles', 'enqueued' ) ) {
			wp_enqueue_script( 'tmpcoder-particles' );
		}
	}


	public function check_script_enqueue($element){

		if ( self::$load_script ) {
			return;
		}

		$settings = $element->get_active_settings();

		if ( isset($settings[ 'tmpcoder_enable_particles' ]) && $settings[ 'tmpcoder_enable_particles' ] == 'yes' ) {

			$this->enqueue_scripts();

			self::$load_script = true;

			remove_action( 'elementor/frontend/before_render', array( $this, 'check_script_enqueue' ) );
		}
	}

	public function tmpcoder_register_scripts(){

		wp_register_script(
            'tmpcoder-particles',
             TMPCODER_PLUGIN_URI . 'assets/js/lib/particles/particles'.tmpcoder_script_suffix().'.js',
             [ 'jquery' ],
            '3.0.6',
            true
      	);

	}

}

new TMPCODER_Particles();
