<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists('TMPCODER_Target_Rules_Fields') ) {
    require_once __DIR__.'/lib/target-rule/class-tmpcoder-target-rules-fields.php';
}

if ( ! class_exists('TMPCODER_Header_Footer_Elements') ){

    class TMPCODER_Header_Footer_Elements {

        /**
         * Instance of Elemenntor Frontend class.
         *
         * @var \Elementor\Frontend()
         */
        private static $elementor_instance;

        /**
         * Instance of TMPCODER_Header_Footer_Elements
         *
         * @var TMPCODER_Header_Footer_Elements
         */
        private static $_instance = null;

        /**
         * Instance of TMPCODER_Header_Footer_Elements
         *
         * @return TMPCODER_Header_Footer_Elements Instance of TMPCODER_Header_Footer_Elements
         */
        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct(){

            $is_elementor_callable = ( defined( 'ELEMENTOR_VERSION' ) && is_callable( 'Elementor\Plugin::instance' ) ) ? true : false;

		    if ( $is_elementor_callable ) {
			    self::$elementor_instance = Elementor\Plugin::instance();

                add_shortcode( 'tmpcoder_template', [ $this, 'render_template' ] );

            }            
        }

        /**
         * Callback to shortcode.
         *
         * @param array $atts attributes for shortcode.
         */
        public function render_template( $atts ) {
            $atts = shortcode_atts(
                [
                    'id' => '',
                ],
                $atts,
                'tmpcoder_template'
            );

            $id = ! empty( $atts['id'] ) ? apply_filters( 'tmpcoder_render_template_id', intval( $atts['id'] ) ) : '';

            if ( empty( $id ) ) {
                return '';
            }

            if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                $css_file = new \Elementor\Core\Files\CSS\Post( $id );
            } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
                // Load elementor styles.
                $css_file = new \Elementor\Post_CSS_File( $id );
            }
                $css_file->enqueue();

            return self::$elementor_instance->frontend->get_builder_content_for_display( $id );
        }

        /**
         * Prints the Header content.
         */
        public static function show_builder_content($content) {
            
            echo wp_kses( $content, tmpcoder_wp_kses_allowed_html() );
            
        }

        /**
         * Prints the Header content.
         */
        public static function get_header_content() {

            do_action( 'tmpcoder_wp_body_open' );
            if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
                echo "<div class='tmpcoder-before-header-content-editor'>";
            }

            self::show_builder_content( self::$elementor_instance->frontend->get_builder_content_for_display( tmpcoder_get_header_id() ) );

            if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
                echo "</div>";
            }
        }

        /**
         * Get template content ID
         *
         * @since  1.0.0
         * @return (String|boolean) dynamic content id if it is set else returns false.
        */

        public static function get_dynamic_content_id($type) {

            $id = TMPCODER_Header_Footer_Elements::get_settings($type, '' );

            if ( '' === $id ) {
                $id = false;
            }
            
            return apply_filters( 'tmpcoder_get_'.$type.'_id', $id );
        }

        /**
         * Prints the dynamic content.
        */

        public static function get_dynamic_content($type) {

            self::show_builder_content( self::$elementor_instance->frontend->get_builder_content_for_display( self::get_dynamic_content_id($type) ) );
        }

        /**
         * Prints the Footer content.
         */
        public static function get_footer_content() {

            if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
                echo "<div class='tmpcoder-before-footer-content-editor'>";
            }

            echo "<div class='footer-width-fixer'>";
            self::show_builder_content( self::$elementor_instance->frontend->get_builder_content_for_display( tmpcoder_get_footer_id() ) );
            echo '</div>';

            if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
                echo "</div>";
            }

        }

        /**
         * Prints the Before Footer content.
         */
        public static function get_before_footer_content() {
            echo "<div class='footer-width-fixer'>";
            self::show_builder_content( self::$elementor_instance->frontend->get_builder_content_for_display( tmpcoder_get_before_footer_id() ) );
            echo '</div>';
        }

        /**
         * Prints the 404 Page content.
         */
        public static function get_404_page_content() {
            echo "<div class='404-part-width-fixer'>";
            self::show_builder_content( self::$elementor_instance->frontend->get_builder_content_for_display( tmpcoder_get_404_page_id() ) );
            echo '</div>';
        }

        /**
         * Get option for the plugin settings
         *
         * @param  mixed $setting Option name.
         * @param  mixed $default Default value to be received if the option value is not stored in the option.
         *
         * @return mixed.
         */
        public static function get_settings( $setting = '', $default = '' ) {
            if ( 'type_header' == $setting || 'type_footer' == $setting || 'type_before_footer' == $setting || 'type_404' == $setting || 'type_archive' == $setting  || 'type_single_post' == $setting || 'type_search_result_page' == $setting || 'type_product_archive' == $setting  || 'type_single_product' == $setting || 'type_product_category' == $setting ) {

                $templates = self::tmpcoder_get_template_id( $setting );

                $template = ! is_array( $templates ) ? $templates : $templates[0];

                $template = apply_filters( "tmpcoder_get_settings_{$setting}", $template );

                return $template;
            }
        }

        /**
         * Get header or footer template id based on the meta query.
         *
         * @param  String $type Type of the template header/footer.
         *
         * @return Mixed       Returns the header or footer template id if found, else returns string ''.
         */
        public static function tmpcoder_get_template_id( $type ) {

            $option = [
                'location'  => 'tmpcoder_target_include_locations',
                'exclusion' => 'tmpcoder_target_exclude_locations',
                'users'     => 'tmpcoder_target_user_roles',
            ];

            $tmpcoder_templates = TMPCODER_Target_Rules_Fields::get_instance()->get_posts_by_conditions(TMPCODER_THEME_ADVANCED_HOOKS_POST_TYPE, $option );

            foreach ( $tmpcoder_templates as $template ) {
                if ( get_post_meta( absint( $template['id'] ), 'tmpcoder_template_type', true ) === $type ) {
                    return $template['id'];
                }
            }

            return '';
        }
    }

    TMPCODER_Header_Footer_Elements::instance();
}
