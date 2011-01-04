<?php
/**
 * @package WP Offline
 * @author Frank Bültge
 * @version 0.5
 */

/*
Plugin Name: WP Offline
Plugin URI: http://bueltge.de/wordpress-27-offline-nutzen/710/
Description: Deactivate autoupdate for core, plugins and themes
Version: 0.5
Author: Frank B&uuml;ltge
Author URI: http://bueltge.de/
Last Change: 04.01.2011
*/

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

remove_action('init', 'wp_schedule_update_checks');
?>