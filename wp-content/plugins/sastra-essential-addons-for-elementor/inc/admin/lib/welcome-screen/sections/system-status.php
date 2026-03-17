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

<div class="feature-section single-col has-2-columns-old is-fullwidth system-info-box common-box-shadow">
	<div class="col column"> 
        <span></span>
		<h3><?php esc_html_e( 'WordPress Environment', 'sastra-essential-addons-for-elementor' ); ?></h3>
		<table class="widefat striped system-status-table">
            <tr>
                <th><?php esc_html_e( 'Home URL:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td><?php echo esc_url( home_url() ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Site URL:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td><?php echo esc_url( site_url() ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'WP Content Path:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td><?php echo defined( 'WP_CONTENT_DIR' ) ? esc_html( WP_CONTENT_DIR ) : esc_html__( 'N/A', 'sastra-essential-addons-for-elementor' ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'WP Path:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td><?php echo defined( 'ABSPATH' ) ? esc_html( ABSPATH ) : esc_html__( 'N/A', 'sastra-essential-addons-for-elementor' ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'WP Version:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td><?php bloginfo( 'version' ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'WP Multisite:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td><?php echo ( is_multisite() ) ? '&#10004;' : '&ndash;'; ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'PHP Memory Limit:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td>
                <?php
						// Get the memory from PHP's configuration.
						$memory = ini_get( 'memory_limit' );
						// If we can't get it, fallback to WP_MEMORY_LIMIT.
						if ( ! $memory || -1 === $memory ) {
							$memory = wp_convert_hr_to_bytes( WP_MEMORY_LIMIT );
						}
						// Make sure the value is properly formatted in bytes.
						if ( ! is_numeric( $memory ) ) {
							$memory = wp_convert_hr_to_bytes( $memory );
						}
						?>
						<?php if ( $memory < 128000000 ) : ?>
							<span class="error">
								<?php /* translators: %1$s: Current value. %2$s: URL. */ ?>
								<?php echo wp_kses(sprintf( __( '%1$s - We recommend setting memory to at least <strong>128MB</strong>. Please define memory limit in <strong>wp-config.php</strong> file. To learn how, see: <a href="%2$s" target="_blank" rel="noopener noreferrer">Increasing memory allocated to PHP.</a>', 'sastra-essential-addons-for-elementor' ), esc_html( size_format( $memory ) ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ), array(
                                    'strong' => array(),
                                    'a' => array(
                                        'href' => array(),
                                        'target' => array(),
                                        'rel' => array(),
                                    )
                                ) ); ?>
							</span>
						<?php else : ?>
							<span class="success">
								<?php echo esc_html( size_format( $memory ) ); ?>
							</span>
						<?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'WP Debug Mode:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td>
                    <?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
                        <span class="success">&#10004;</span>
                    <?php else : ?>
                        <span class="no">&ndash;</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Language:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td><?php echo esc_html( get_locale() ); ?></td>
            </tr>
        </table>
	</div><!--/.col-->

    <div class="col column">
        <span></span>
		<h3><?php esc_html_e( 'Server Environment', 'sastra-essential-addons-for-elementor' ); ?></h3>
		<table class="widefat striped system-status-table">
            <tr>
                <th><?php esc_html_e( 'Server Info:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td><?php echo isset( ${'_SERVER'}['SERVER_SOFTWARE'] ) ? esc_html( sanitize_text_field( wp_unslash( ${'_SERVER'}['SERVER_SOFTWARE'] ) ) ) : esc_html__( 'Unknown', 'sastra-essential-addons-for-elementor' ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'PHP Version:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td>
                <?php
                    $php_version = null;
                    if ( defined( 'PHP_VERSION' ) ) {
                        $php_version = PHP_VERSION;
                    } elseif ( function_exists( 'phpversion' ) ) {
                        $php_version = phpversion();
                    }
                    if ( null === $php_version ) {
                        $message = esc_html__( 'PHP Version could not be detected.', 'sastra-essential-addons-for-elementor' );
                    } else {
                        if ( version_compare( $php_version, '7.3' ) >= 0 ) {
                            $message = $php_version;
                        } else {
                            $message = sprintf(
                                /* translators: %1$s: Current PHP version. %2$s: Recommended PHP version. %3$s: "WordPress Requirements" link. */
                                esc_html__( '%1$s. WordPress recommendation: %2$s or above. See %3$s for details.', 'sastra-essential-addons-for-elementor' ),
                                $php_version,
                                '7.3',
                                '<a href="https://wordpress.org/about/requirements/" target="_blank">' . esc_html__( 'WordPress Requirements', 'sastra-essential-addons-for-elementor' ) . '</a>'
                            );
                        }
                    }
                    echo wp_kses($message, array(
                        'a' => array(
                            'href' => array(),
                            'target' => array(),
                        )
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'PHP Post Max Size:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td><?php echo esc_html( size_format( wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) ) ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'PHP Time Limit:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td>
                <?php
                    $time_limit = ini_get( 'max_execution_time' );

                    if ( 180 > $time_limit && 0 !== $time_limit ) {
                        echo wp_kses('<span class="error">' . sprintf(
                            /* translators: %1$s: Current value. %2$s: URL. */
                             __( '%1$s - We recommend setting max execution time to at least 180.<br />See: <a href="%2$s" target="_blank" rel="noopener noreferrer">Increasing max execution to PHP</a>', 'sastra-essential-addons-for-elementor' ), $time_limit, 'https://wordpress.org/support/article/common-wordpress-errors/#specific-error-messages' ) . '</span>', array(
                                 'span' => array(
                                     'class' => array(),
                                 ),
                                 'br' => array(),
                                 'a' => array(
                                    'href'=> array(),
                                    'target'=> array(),
                                    'rel'=> array(),
                                 ),
                             ));
                    } else {
                        echo '<span class="success">' . esc_html( $time_limit ) . '</span>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'PHP Max Input Vars:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td>
                    <?php
                        $max_input_vars      = ini_get( 'max_input_vars' );
                        $required_input_vars = 1000;
                        // 1000 = Global Options.
                        if ( $max_input_vars < $required_input_vars ) {
                            
                            echo wp_kses('<span class="error">' . sprintf( 
                                /* translators: %1$s: Current value. $2%s: Recommended value. */
                                __( '%1$s - Recommended Value: %2$s.<br />Max input vars limitation will truncate POST data such as menus. Increasing max input vars limit on php.ini file.', 'sastra-essential-addons-for-elementor' ), $max_input_vars, '<strong>' . $required_input_vars . '</strong>' ) . '</span>', array(
                                    'span' => array(
                                        'class' => array(),
                                    ),
                                    'br' => array(),
                                    'strong' => array()
                                ));
                        } else {
                            echo '<span class="success">' . esc_html( $max_input_vars ) . '</span>';
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'ZipArchive:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td>
                    <?php echo class_exists( 'ZipArchive' ) ? '<span class="success">&#10004;</span>' : '<span class="error">ZipArchive is not installed on your server, but is required if you need to import demo content.</span>'; ?>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'MySQL Version:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td>
                    <?php global $wpdb; ?>
					<?php echo esc_html( $wpdb->db_version() ); ?>  
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Max Upload Size:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td><?php echo esc_html( size_format( wp_max_upload_size() ) ); ?></td>
            </tr>            
            <tr>
                <th><?php esc_html_e( 'WP Remote Get:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <?php
					$get_res = wp_safe_remote_get(
						TMPCODER_UPDATES_URL,
						[
							'decompress' => false,
							'user-agent' => 'tmpcoder-remote-get-test',
						]
					);
					?>
                <td>
                    <?php echo wp_kses_post(( ! is_wp_error( $get_res ) && $get_res['response']['code'] >= 200 && $get_res['response']['code'] < 300 ) ? '<span class="success">&#10004;</span>' : '<span class="error">'.sprintf(
                        /* translators: %s: Plugin Website Url  */
                        __('wp_remote_get() failed. Some theme features may not work. Please contact your hosting provider and make sure that %s is not blocked.', 'sastra-essential-addons-for-elementor'), TMPCODER_UPDATES_URL).'</span> <a href='.TMPCODER_FIX_IMPORT_ISSUE_DOC_LINK.'>'.esc_html('How to Fix ?').'</a>'); ?>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'WP Remote Post:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <?php

                    $post_res = wp_safe_remote_post(
                        TMPCODER_UPDATES_URL,
                        [
                            'decompress' => false,
                            'user-agent' => 'tmpcoder-remote-post-test',
                        ]
                    );

					?>
                <td>
                    <?php echo wp_kses_post(( ! is_wp_error( $post_res ) && $post_res['response']['code'] >= 200 && $post_res['response']['code'] < 300 ) ? '<span class="success">&#10004;</span>' : '<span class="error">'. sprintf(
                        /* translators: %s: Plugin Website Url */
                        __('wp_remote_post() failed. Some theme features may not work. Please contact your hosting provider and make sure that %s is not blocked.', 'sastra-essential-addons-for-elementor'), TMPCODER_UPDATES_URL ).'</span> <a href='.TMPCODER_FIX_IMPORT_ISSUE_DOC_LINK.'>'.esc_html('How to Fix ?').'</a>'); ?>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'GD Library:', 'sastra-essential-addons-for-elementor' ); ?></th>
                <td>
                    <?php
                    $info = esc_html__( 'Not Installed', 'sastra-essential-addons-for-elementor' );
                    if ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) {
                        $info    = esc_html__( 'Installed', 'sastra-essential-addons-for-elementor' );
                        $gd_info = gd_info();
                        if ( isset( $gd_info['GD Version'] ) ) {
                            $info = $gd_info['GD Version'];
                        }
                    }
                    echo esc_html( $info );
                    ?>
                </td>
            </tr>            
        </table>
	</div><!--/.col-->
</div><!--/.feature-section-->

