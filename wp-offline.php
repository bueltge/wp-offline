<?php
/**
 * @package    WordPress
 * @subpackage WP Offline (plugin)
 * @author     Frank Bültge
 */

/**
 * Plugin Name: WP Offline
 * Plugin URI:  http://bueltge.de/wordpress-27-offline-nutzen/710/
 * Description: Deactivate autoupdate for core, plugins, themes and disable http calls
 * Version:     0.8
 * Author:      Frank B&uuml;ltge
 * Author URI:  http://bueltge.de/
 * Last Change: 04/05/2012
 */

// block external URL requests 
define( 'WP_HTTP_BLOCK_EXTERNAL', TRUE );
define( 'WP_ACCESSIBLE_HOSTS', FALSE );
define( 'FS_METHOD', FALSE );
// disable cron
define( 'DISABLE_WP_CRON', TRUE );

// disable http calls, maybe the same how the const WP_HTTP_BLOCK_EXTERNAL
add_filter( 'pre_http_request', '__return_true', 100 );

// HTTP Related Filters
// disable transports
// see wp-includes/class-http.php for filter hooks
add_filter( 'http_request_timeout', '__return_zero');
add_filter( 'http_request_redirection_count', '__return_zero' );
add_filter( 'lock_local_requests', '__return_true' );
add_filter( 'use_fsockopen_transport', '__return_false' );
add_filter( 'use_http_extension_transport', '__return_false' );
add_filter( 'use_curl_transport', '__return_false' );
add_filter( 'use_streams_transport', '__return_false' );
add_filter( 'use_fopen_transport', '__return_false' );
add_filter( 'use_fsockopen_transport', '__return_false' );
add_filter( 'https_local_ssl_verify', '__return_false' );
add_filter( 'https_ssl_verify', '__return_false' );

// see wp-includes/update.php for init hooks
remove_action( 'admin_init', '_maybe_update_core' );
remove_action( 'wp_version_check', 'wp_version_check' );
wp_clear_scheduled_hook( 'wp_version_check' );

remove_action( 'load-plugins.php', 'wp_update_plugins' );
remove_action( 'load-update.php', 'wp_update_plugins' );
remove_action( 'load-update-core.php', 'wp_update_plugins' );
remove_action( 'admin_init', '_maybe_update_plugins' );
remove_action( 'wp_update_plugins', 'wp_update_plugins' );
wp_clear_scheduled_hook( 'wp_update_plugins' );

remove_action( 'load-themes.php', 'wp_update_themes' );
remove_action( 'load-update.php', 'wp_update_themes' );
remove_action( 'load-update-core.php', 'wp_update_themes' );
remove_action( 'admin_init', '_maybe_update_themes' );
remove_action( 'wp_update_themes', 'wp_update_themes' );
wp_clear_scheduled_hook( 'wp_update_themes' );

remove_action( 'init', 'wp_schedule_update_checks' );
