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


function debug_domain_mapping_siteurl( $setting ) {
	global $wpdb, $current_blog;

	// To reduce the number of database queries, save the results the first time we encounter each blog ID.
	static $return_url = array();

	$wpdb->dmtable = $wpdb->base_prefix . 'domain_mapping';

	if ( ! isset( $return_url[ $wpdb->blogid ] ) ) {
		header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
		$s = $wpdb->suppress_errors();

		if ( get_site_option( 'dm_no_primary_domain' ) == 1 ) {
			header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
			$domain = $wpdb->get_var( "SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = '{$wpdb->blogid}' AND domain = '" . $wpdb->escape( $_SERVER['HTTP_HOST'] ) . "' LIMIT 1" );
			if ( null == $domain ) {
				header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
				$return_url[ $wpdb->blogid ] = untrailingslashit( get_original_url( 'siteurl' ) );
				return $return_url[ $wpdb->blogid ];
			}
		} else {
			header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
			// get primary domain, if we don't have one then return original url.
			$domain = $wpdb->get_var( "SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = '{$wpdb->blogid}' AND active = 1 LIMIT 1" );
			if ( null == $domain ) {
				header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
				$return_url[ $wpdb->blogid ] = untrailingslashit( get_original_url( 'siteurl' ) );
				return $return_url[ $wpdb->blogid ];
			}
		}

		$wpdb->suppress_errors( $s );
		$protocol = is_ssl() ? 'https://' : 'http://';
		if ( $domain ) {
			header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
			$return_url[ $wpdb->blogid ] = untrailingslashit( $protocol . $domain );
			$setting = $return_url[ $wpdb->blogid ];
			header( sprintf( 'X-Info-%d: %s', __LINE__, $setting ) );
		} else {
			header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
			$return_url[ $wpdb->blogid ] = false;
		}
	} elseif ( $return_url[ $wpdb->blogid ] !== false ) {
		header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
		$setting = $return_url[ $wpdb->blogid ];
	}

	header( sprintf( 'X-Info-%d: %s', __LINE__, $setting ) );
	return $setting;
}

function debug_redirect_to_mapped_domain() {
	global $current_blog, $wpdb;

	// don't redirect the main site
	if ( is_main_site() ) {
		header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
		return;
	}
	// don't redirect post previews
	if ( isset( $_GET['preview'] ) && $_GET['preview'] == 'true' ) {
		header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
		return;
	}

	// don't redirect theme customizer (WP 3.4)
	if ( isset( $_POST['customize'] ) && isset( $_POST['theme'] ) && $_POST['customize'] == 'on' ) {
		header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
		return;
	}

	$protocol = is_ssl() ? 'https://' : 'http://';
	header( sprintf( 'X-Info-%d: %s', __LINE__, $protocol ) );
	$url = domain_mapping_siteurl( false );
	header( sprintf( 'X-Info-%d: %s', __LINE__, $url ) );
	if ( $url && $url != untrailingslashit( $protocol . $current_blog->domain . $current_blog->path ) ) {
		$redirect = get_site_option( 'dm_301_redirect' ) ? '301' : '302';
		if ( ( defined( 'VHOST' ) && constant( 'VHOST' ) != 'yes' ) || ( defined( 'SUBDOMAIN_INSTALL' ) && constant( 'SUBDOMAIN_INSTALL' ) == false ) ) {
			header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
			$_SERVER['REQUEST_URI'] = str_replace( $current_blog->path, '/', $_SERVER['REQUEST_URI'] );
		}
		header( sprintf( 'X-Info-%d: %s', __LINE__, $url ) );
		header( "Location: {$url}{$_SERVER[ 'REQUEST_URI' ]}", true, $redirect );
		exit;
	}

	header( sprintf( 'X-Info-%d: %s', __LINE__, '1' ) );
}


add_action(
	'template_redirect', function() {

		// $info = is_ssl() ? 'https' : 'http'; // Works.
		// $info = domain_mapping_siteurl( false ); // Returns with http://
		/*
		global $wpdb, $current_blog;
		$domain = $wpdb->get_var( "SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = '{$wpdb->blogid}' AND active = 1 LIMIT 1" ); // returns plain domain name.
		$protocol = is_ssl() ? 'https://' : 'http://';
		$info = untrailingslashit( $protocol . $domain ); // returns https://22julisenteret.no
		*/
		// debug_domain_mapping_siteurl( false ); // This returns the correct URL with https://
		// header( 'X-Info: ' . $info );
		debug_redirect_to_mapped_domain();
		remove_action( 'template_redirect', 'redirect_to_mapped_domain' );
		$info = is_ssl() ? 'https' : 'http';
		header( sprintf( 'X-Info-%d: %s', __LINE__, $info ) );
	}, 9
);

add_action(
	'template_redirect', function() {
		$info = is_ssl() ? 'https' : 'http';
		header( sprintf( 'X-Info-%d: %s', __LINE__, $info ) );
	}, 11
);
