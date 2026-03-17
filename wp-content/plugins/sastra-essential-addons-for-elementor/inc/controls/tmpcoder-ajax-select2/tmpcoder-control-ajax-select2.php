<?php
namespace TMPCODER;
use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists('TMPCODER_Control_Ajax_Select2') ) {
	
	class TMPCODER_Control_Ajax_Select2 extends Base_Data_Control {

		public function get_type() {
			return 'tmpcoder-ajax-select2';
		}

		public function enqueue() {
			wp_register_script( 'tmpcoder-control-ajax-select2', TMPCODER_PLUGIN_URI . 'inc/controls/tmpcoder-ajax-select2/js/tmpcoder-control-ajax-select2'.tmpcoder_script_suffix().'.js', [ 'jquery' ], tmpcoder_get_plugin_version(), false );
			wp_enqueue_script( 'tmpcoder-control-ajax-select2' );
		}

		protected function get_default_settings() {
			return [
				'options' => [],
				'multiple' => false,
				'select2options' => [],
				'query_slug' => '',
			];
		}

		public function content_template() {
			$control_uid = $this->get_control_uid();
			?>
			<div class="elementor-control-field">
				<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
				<div class="elementor-control-input-wrapper">
	                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
					<select id="<?php echo esc_attr($control_uid); ?>" class="elementor-control-type-tmpcoder-ajaxselect2" {{ multiple }} data-query-slug="{{data.query_slug}}" data-setting="{{ data.name }}" data-rest-url="<?php echo esc_attr( get_rest_url(). 'tmpcoder' . '/{{data.options}}/' ); ?>">
					</select>
				</div>
			</div>
			<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
			<?php
		}
	}
}

if (!class_exists('TMPCODER_Control_Animations')) {
	
	class TMPCODER_Control_Animations extends Base_Data_Control {

		/**
		* List of animations.
		*/
		private static $_animations;

		/**
		* Get control type.
		*/

		public function get_type() {
			return 'tmpcoder-animations';
		}

		/**
		* Get animations list.
		* Retrieve the list of all the available animations.
		*/
		public static function get_animations() {

			if ( is_null( self::$_animations ) ) {
				self::$_animations = [
					'Fade' => [
					'fade-in' => 'Fade In',
					'fade-out' => 'Fade Out',
					],
					'Slide' => [
						'pro-sltp' => 'Top (Pro)',
						'pro-slrt' => 'Right (Pro)',
						'pro-slxrt' => 'X Right (Pro)',
						'pro-slbt' => 'Bottom (Pro)',
						'pro-sllt' => 'Left (Pro)',
						'pro-slxlt' => 'X Left (Pro)',
					],
					'Skew' => [
						'pro-sktp' => 'Top (Pro)',
						'pro-skrt' => 'Right (Pro)',
						'pro-skbt' => 'Bottom (Pro)',
						'pro-sklt' => 'Left (Pro)',
					],
					'Scale' => [
						'pro-scup' => 'Up (Pro)',
						'pro-scdn' => 'Down (Pro)',
					],
					'Roll' => [
						'pro-rllt' => 'Left (Pro)',
						'pro-rlrt' => 'Right (Pro)',
					],
				];
			}
				
			if ( tmpcoder_is_availble() && defined('TMPCODER_ADDONS_PRO_VERSION') ) {
				self::$_animations = \TMPCODER\Inc\Controls\TMPCODER_Control_Animations_Pro::tmpcoder_animations();
			}

			return self::$_animations;
		}

		/**
		* Render animations control template.
		*
		* Used to generate the control HTML in the editor using Underscore JS
		* template. The variables for the class are available using `data` JS
		* object.
		*/
		public function content_template() {
			$control_uid = $this->get_control_uid();
			?>
			<div class="elementor-control-field">
				<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
				<div class="elementor-control-input-wrapper">
					<select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
						<option value="none"><?php echo esc_html__( 'None', 'sastra-essential-addons-for-elementor' ); ?></option>
						<?php foreach ( self::get_animations() as $animations_group_name => $animations_group ) : ?>
							<optgroup label="<?php echo esc_attr($animations_group_name); ?>">
								<?php foreach ( $animations_group as $animation_name => $animation_title ) : ?>
									<option value="<?php echo esc_attr($animation_name); ?>"><?php echo esc_html($animation_title); ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
			<?php
		}
	}
}

if (!class_exists('TMPCODER_Control_Animations_Alt')) {
	
	class TMPCODER_Control_Animations_Alt extends TMPCODER_Control_Animations {

		/**
		* Get control type.
		*/
		public function get_type() {
			return 'tmpcoder-animations-alt';
		}

		/**
		* Render animations control template.
		*
		* Used to generate the control HTML in the editor using Underscore JS
		* template. The variables for the class are available using `data` JS
		* object.
		*/
		
		public function content_template() {
			$animations = self::get_animations();
			$control_uid = $this->get_control_uid();

			// Remove Extra
			unset($animations['Slide']['slide-x-right']);
			unset($animations['Slide']['slide-x-left']);
			?>
			<div class="elementor-control-field">
				<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
				<div class="elementor-control-input-wrapper">
					<select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
						<option value="none"><?php echo esc_html__( 'None', 'sastra-essential-addons-for-elementor' ); ?></option>
						<?php foreach ( $animations as $animations_group_name => $animations_group ) : ?>
							<optgroup label="<?php echo esc_attr($animations_group_name); ?>">
								<?php foreach ( $animations_group as $animation_name => $animation_title ) : ?>
									<option value="<?php echo esc_attr($animation_name); ?>"><?php echo esc_html($animation_title); ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
			<?php
		}
	}
}

if (!class_exists('TMPCODER_Control_Button_Animations')) {
	
	class TMPCODER_Control_Button_Animations extends Base_Data_Control {

		/**
		* List of animations.
		*/
		private static $_animations;

		/**
		* Get control type.
		*/
		public function get_type() {
			return 'tmpcoder-button-animations';
		}

		/**
		* Get animations list.
		* Retrieve the list of all the available animations.
		*/
		public static function get_animations() {
			if ( is_null( self::$_animations ) ) {
				self::$_animations = [
					'Animations' => [
						'tmpcoder-button-none' => esc_html__( 'None', 'sastra-essential-addons-for-elementor' ),
						'pro-wnt' => esc_html__( 'Winona + Text (Pro)', 'sastra-essential-addons-for-elementor' ),
						'pro-rlt' => esc_html__( 'Ray Left + Text (Pro)', 'sastra-essential-addons-for-elementor' ),
						'pro-rrt' => esc_html__( 'Ray Right + Text (Pro)', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-wayra-left' => esc_html__( 'Wayra Left', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-wayra-right' => esc_html__( 'Wayra Right', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-isi-left' => esc_html__( 'Isi Left', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-isi-right' => esc_html__( 'Isi Right', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-aylen' => esc_html__( 'Aylen', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-antiman' => esc_html__( 'Antiman', 'sastra-essential-addons-for-elementor' ),
					],
					'2D Animations' => [
						'elementor-animation-grow' => esc_html__( 'Grow', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-shrink' => esc_html__( 'Shrink', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-pulse' => esc_html__( 'Pulse', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-pulse-grow' => esc_html__( 'Pulse Grow', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-pulse-shrink' => esc_html__( 'Pulse Shrink', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-push' => esc_html__( 'Push', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-pop' => esc_html__( 'Pop', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-bounce-in' => esc_html__( 'Bounce In', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-bounce-out' => esc_html__( 'Bounce Out', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-rotate' => esc_html__( 'Rotate', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-grow-rotate' => esc_html__( 'Grow Rotate', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-float' => esc_html__( 'Float', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-sink' => esc_html__( 'Sink', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-bob' => esc_html__( 'Bob', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-hang' => esc_html__( 'Hang', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-skew' => esc_html__( 'Skew', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-skew-forward' => esc_html__( 'Skew Forward', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-skew-backward' => esc_html__( 'Skew Backward', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-wobble-horizontal' => esc_html__( 'Wobble Horizontal', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-wobble-vertical' => esc_html__( 'Wobble Vertical', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-wobble-to-bottom-right' => esc_html__( 'Wobble To Bottom Right', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-wobble-to-top-right' => esc_html__( 'Wobble To Top Right', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-wobble-top' => esc_html__( 'Wobble Top', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-wobble-bottom' => esc_html__( 'Wobble Bottom', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-wobble-skew' => esc_html__( 'Wobble Skew', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-buzz' => esc_html__( 'Buzz', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-buzz-out' => esc_html__( 'Buzz Out', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-forward' => esc_html__( 'Forward', 'sastra-essential-addons-for-elementor' ),
						'elementor-animation-backward' => esc_html__( 'Backward', 'sastra-essential-addons-for-elementor' ),
					],
					'Background Animations' => [
						'tmpcoder-button-back-pulse' => esc_html__( 'Back Pulse', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-sweep-to-right' => esc_html__( 'Sweep To Right', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-sweep-to-left' => esc_html__( 'Sweep To Left', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-sweep-to-bottom' => esc_html__( 'Sweep To Bottom', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-sweep-to-top' => esc_html__( 'Sweep To top', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-bounce-to-right' => esc_html__( 'Bounce To Right', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-bounce-to-left' => esc_html__( 'Bounce To Left', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-bounce-to-bottom' => esc_html__( 'Bounce To Bottom', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-bounce-to-top' => esc_html__( 'Bounce To Top', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-radial-out' => esc_html__( 'Radial Out', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-radial-in' => esc_html__( 'Radial In', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-rectangle-in' => esc_html__( 'Rectangle In', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-rectangle-out' => esc_html__( 'Rectangle Out', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-shutter-in-horizontal' => esc_html__( 'Shutter In Horizontal', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-shutter-out-horizontal' => esc_html__( 'Shutter Out Horizontal', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-shutter-in-vertical' => esc_html__( 'Shutter In Vertical', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-shutter-out-vertical' => esc_html__( 'Shutter Out Vertical', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-underline-from-left' => esc_html__( 'Underline From Left', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-underline-from-center' => esc_html__( 'Underline From Center', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-underline-from-right' => esc_html__( 'Underline From Right', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-underline-reveal' => esc_html__( 'Underline Reveal', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-overline-reveal' => esc_html__( 'Overline Reveal', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-overline-from-left' => esc_html__( 'Overline From Left', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-overline-from-center' => esc_html__( 'Overline From Center', 'sastra-essential-addons-for-elementor' ),
						'tmpcoder-button-overline-from-right' => esc_html__( 'Overline From Right', 'sastra-essential-addons-for-elementor' ),
					]
				];
			}

			if ( tmpcoder_is_availble() && defined('TMPCODER_ADDONS_PRO_VERSION') ) {
				self::$_animations = \TMPCODER\Inc\Controls\TMPCODER_Control_Animations_Pro::tmpcoder_button_animations();
			}

			return self::$_animations;
		}

		/**
		* Render animations control template.
		*
		* Used to generate the control HTML in the editor using Underscore JS
		* template. The variables for the class are available using `data` JS
		* object.
		*/
		public function content_template() {
			$control_uid = $this->get_control_uid();
			?>
			<div class="elementor-control-field">
				<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
				<div class="elementor-control-input-wrapper">
					<select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
						<?php foreach ( self::get_animations() as $animations_group_name => $animations_group ) : ?>
							<optgroup label="<?php echo esc_attr($animations_group_name); ?>">
								<?php foreach ( $animations_group as $animation_name => $animation_title ) : ?>
									<option value="<?php echo esc_attr($animation_name); ?>"><?php echo esc_html($animation_title); ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
			<?php
		}
		
	}
}