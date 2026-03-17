<?php
/**
 * Template part for the support tab in welcome screen
 *
 * @package Epsilon Framework
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="feature-section two-col has-2-columns is-fullwidth">
	<div class="col column">
		<h3><i class="dashicons dashicons-sos"></i><?php esc_html_e( 'Contact Support', 'sastra-essential-addons-for-elementor' ); ?></h3>
		<p>
			<i><?php esc_html_e( 'We offer best support through our advanced support system.', 'sastra-essential-addons-for-elementor' ); ?></i>
		</p>
		<p><a target="_blank"  class="button button-primary" href="<?php echo esc_url( TMPCODER_SUPPORT_URL ); ?>"><?php esc_html_e( 'Contact Support', 'sastra-essential-addons-for-elementor' ); ?></a>
		</p>
	</div><!--/.col-->
</div><!--/.feature-section-->

