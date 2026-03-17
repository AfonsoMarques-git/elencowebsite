<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (!function_exists('tmpcoder_error_log')) {
	
	function tmpcoder_error_log(){
	}
}

/**
 * Functions
 *
 * @since 1.0.0
 * @package Spexo Addons for Elementor
 */

if ( ! function_exists( 'tmpcoder_is_valid_image' ) ) :
	/**
	 * Check for the valid image
	 *
	 * @param string $link  The Image link.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	function tmpcoder_is_valid_image( $link = '' ) {
		return preg_match( '/^((https?:\/\/)|(www\.))([a-z0-9-].?)+(:[0-9]+)?\/[\w\-\@]+\.(jpg|png|gif|jpeg|svg)\/?$/i', $link );
	}
endif;

if ( ! function_exists( 'tmpcoder_get_site_data' ) ) :
	/**
	 * Returns the value of the index for the Site Data
	 *
	 * @param string $index  The index value of the data.
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	function tmpcoder_get_site_data( $index = '' ) {
		$demo_data = get_option( 'tmpcoder_import_data ', array() );
		if ( ! empty( $demo_data ) && isset( $demo_data[ $index ] ) ) {
			return $demo_data[ $index ];
		}
		return '';
	}
endif;

/**
 * Check is valid URL
 *
 * @param string $url  The site URL.
 *
 * @since 1.0.0
 * @return string
 */

if (!function_exists('tmpcoder_is_valid_url')) {
	
	function tmpcoder_is_valid_url( $url = '' ) {
		if ( empty( $url ) ) {
			return false;
		}

		$parse_url = wp_parse_url( $url );
		if ( empty( $parse_url ) || ! is_array( $parse_url ) ) {
			return false;
		}

		$valid_hosts = array(
			'demo.skywebtech.co',
		);

		$api_domain_parse_url = wp_parse_url( Tmpcoder::get_instance()->get_api_domain() );
		$valid_hosts[] = $api_domain_parse_url['host'];

		// Validate host.
		if ( in_array( $parse_url['host'], $valid_hosts, true ) ) {
			return true;
		}

		return false;
	}
}

/**
 * Get all the posts to be reset.
 *
 * @since 1.0.0
 * @return array
 */
function tmpcoder_get_reset_post_data() {
	global $wpdb;

	$post_ids = $wpdb->get_col( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_tmpcoder_imported_post'" );

	return $post_ids;
}

/**
 * Get all the terms to be reset.
 *
 * @since 1.0.0
 * @return array
 */
function tmpcoder_get_reset_term_data() {
	global $wpdb;

	$term_ids = $wpdb->get_col( "SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key='_tmpcoder_imported_term'" );

	return $term_ids;
}

/**
 * Check if Import for Spexo Addons for Elementor is in progress
 *
 * @since 1.0.0
 * @return array
 */

function tmpcoder_has_import_started() {
	$has_import_started = get_transient( 'tmpcoder_import_started' );
	if ( 'yes' === $has_import_started ) {
		return true;
	}
	return false;
}

/**
 * Remove the post excerpt
 *
 * @param int $post_id  The post ID.
 * @since 1.0.0
 */
function tmpcoder_empty_post_excerpt( $post_id = 0 ) {
	
	if ( ! $post_id ) {
		return;
	}

	wp_update_post(
		array(
			'ID'           => $post_id,
			'post_excerpt' => '',
		)
	);
}