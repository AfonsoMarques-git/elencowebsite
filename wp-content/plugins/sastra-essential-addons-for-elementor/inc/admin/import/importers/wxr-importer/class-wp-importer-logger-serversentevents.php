<?php
/**
 * WordPress Importer
 * https://github.com/humanmade/WordPress-Importer
 *
 * Released under the GNU General Public License v2.0
 * https://github.com/humanmade/WordPress-Importer/blob/master/LICENSE
 *
 * @since 1.0.0
 *
 * @package WordPress Importer
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'TMPCODER_Importer_Logger_ServerSentEvents' ) && class_exists( 'TMPCODER_Importer_Logger' ) ) {

	/**
	 * Import Log ServerSendEvents
	 *
	 * @since 1.0.0
	 */
	class TMPCODER_Importer_Logger_ServerSentEvents extends TMPCODER_Importer_Logger {

		/**
		 * Logs with an arbitrary level.
		 *
		 * @param mixed  $level Log level.
		 * @param string $message Log message.
		 * @param array  $context Log context.
		 * @return void
		 */
		
		public function log( $level, $message, array $context = array() ) {

			$data = compact( 'level', 'message' );

			switch ( $level ) {
				case 'emergency':
				case 'alert':
				case 'critical':
				case 'error':
				case 'warning':
				case 'notice':
				case 'info':
					
					echo "event: log\n";
					echo 'data: ' . wp_json_encode( $data ) . "\n\n";
					
					flush();
					break;

				case 'debug':
					if ( defined( 'IMPORT_DEBUG' ) && IMPORT_DEBUG ) {
						
						echo "event: log\n";
						echo 'data: ' . wp_json_encode( $data ) . "\n\n";
						
						flush();
						break;
					}
					break;
			}
		}
	}
}
