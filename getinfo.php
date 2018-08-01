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
 * Network: true
 * License: GPLv2 or later
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action(
	'template_redirect', function() {
		$info = is_ssl() ? 'https' : 'http';
		header( 'X-Info: ' . $info );
	}, 9
);
