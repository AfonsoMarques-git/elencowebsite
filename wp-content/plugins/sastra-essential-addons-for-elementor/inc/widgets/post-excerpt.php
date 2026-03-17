<?php
namespace TMPCODER\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TMPCODER_Post_Excerpt extends Widget_Base {

	public function get_name(){
    	return 'tmpcoder-post-excerpt';
  	}

	public function get_title() {
		return __( 'Post Excerpt', 'sastra-essential-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'tmpcoder-icon eicon-post-excerpt';
	}

	public function get_categories() {
		return tmpcoder_show_theme_buider_widget_on('type_single_post') ? [ 'tmpcoder-theme-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'excerpt', 'text' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'_section_post_excerpt',
			[
				'label' => __( 'Content', 'sastra-essential-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'max_length',
			[
				'label' => esc_html__( 'Excerpt Length', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
                'default' => 50,
			]
		);

        $this->end_controls_section();

        // Section: Request New Feature
		tmpcoder_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

        $this->start_controls_section(
            '_section_style_excerpt',
            [
                'label' => __( 'Excerpt Style', 'sastra-essential-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'sastra-essential-addons-for-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-excerpt' => 'text-align: {{VALUE}};'
				]
			]
		);

        $this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__( 'Color', 'sastra-essential-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tmpcoder-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'label' => __( 'Typography', 'sastra-essential-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .tmpcoder-excerpt',
				'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
			]
		);

        $this->end_controls_section();
	}


	protected function render() {

		$settings = $this->get_settings();
$settings_new = $this->get_settings_for_display();
$settings = array_merge( $settings, $settings_new );
		$max_length = (int) $settings['max_length'];

		if (tmpcoder_is_preview_mode()) {
			$last_id = tmpcoder_get_last_post_id();
			$post = get_post($last_id);
		}
		else
		{
			$post = get_post();
		}

		if ( ! $post || empty( $post->post_excerpt ) ) {
			return;
		}

		$excerpt = $post->post_excerpt;
		$excerpt = $this->limit_text( $excerpt, $max_length );

		echo '<p class="tmpcoder-excerpt">'.esc_html($excerpt).'</p>';
	}

	function limit_text($text, $limit) {

	    if (str_word_count($text, 0) > $limit) {
	        $words = str_word_count($text, 2);
	        $pos   = array_keys($words);
	        $text  = substr($text, 0, $pos[$limit]);
	    }

	    return $text;
	}

}
