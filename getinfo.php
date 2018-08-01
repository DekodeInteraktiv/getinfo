<?php
/**
 * The GetInfo Plugin.
 *
 * @package GetInfo
 */

/**
 * Plugin Name: GetInfo
 * Description: Get info
 * Version: 0.0.1
 * Author: Dekode
 * License: GPLv2 or later
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action(
	'template_redirect', function() {

		global $wpdb, $current_blog;

		// $info = is_ssl() ? 'https' : 'http'; // Works.
		// $info = domain_mapping_siteurl( false ); // Returns with http://
		$domain = $wpdb->get_var( "SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = '{$wpdb->blogid}' AND active = 1 LIMIT 1" ); // returns plain domain name.
		$protocol = is_ssl() ? 'https://' : 'http://';
		$info = untrailingslashit( $protocol . $domain );

		header( 'X-Info: ' . $info );
		header( 'X-Info-B: ' . $wpdb->blogid );
	}, 9
);
