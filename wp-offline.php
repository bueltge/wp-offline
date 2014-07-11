<?php
/**
 * @package    WordPress
 * @subpackage WP Offline Mode (plugin)
 * @author     Frank Bültge
 */

/**
 * Plugin Name: Offline Mode
 * Plugin URI:  http://bueltge.de/wordpress-27-offline-nutzen/710/
 * Description: Deactivate autoupdate for core, plugins, themes and disable http calls
 * Version:     0.9
 * Author:      Frank Bültge
 * Author URI:  http://bueltge.de/
 * Last Change: 07/11/2014
 */

! defined( 'ABSPATH' ) && exit;

// block external URL requests
define( 'WP_HTTP_BLOCK_EXTERNAL', TRUE );
define( 'WP_ACCESSIBLE_HOSTS', FALSE );
define( 'FS_METHOD', FALSE );
// disable cron
define( 'DISABLE_WP_CRON', TRUE );

add_action( 'plugins_loaded', array( 'Offline_Mode', 'get_object' ), 10 );

class Offline_Mode {

	/**
	 * The class object
	 *
	 * @var    String
	 */
	static protected $class_object = NULL;

	/**
	 * Save unset scripts
	 * @var array
	 */
	protected $externel_scripts = array();

	protected $externel_styles = array();

	/**
	 * Load the object and get the current state
	 *
	 * @return String $class_object
	 */
	public static function get_object() {

		if ( NULL == self::$class_object ) {
			self::$class_object = new self;
		}

		return self::$class_object;
	}

	/**
	 * Construct, set all relevant method, filter
	 */
	public function __construct() {

		// disable http calls, maybe the same how the const WP_HTTP_BLOCK_EXTERNAL
		add_filter( 'pre_http_request', '__return_true', 100 );

		// HTTP Related Filters
		// disable transports
		// see wp-includes/class-http.php for filter hooks
		add_filter( 'http_request_timeout', '__return_zero' );
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

		add_filter( 'get_avatar', array( $this, 'replace_avatar' ), 1, 5 );

		add_action( 'wp_default_styles', array( $this, 'block_styles' ), 9999 );
		add_action( 'wp_default_scripts', array( $this, 'block_scripts' ), 9999 );
	}

	/**
	 * Filter the avatar, return nothing
	 *
	 * @param string            $avatar      Image tag for the user's avatar.
	 * @param int|object|string $id_or_email A user ID, email address, or comment object.
	 * @param int               $size        Square avatar width and height in pixels to retrieve.
	 * @param string            $alt         Alternative text to use in the avatar image tag.
	 *                                       Default empty.
	 *
	 * @return string
	 */
	public function replace_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

		$avatar = '';

		return $avatar;
	}

	public function block_styles( $styles ) {

		if ( ! isset( $styles->registered ) ) {
			return $styles;
		}

		// if it give registered styles
		foreach ( $styles->registered as $style => $attributes ) {

			// if external url, then set src attribute to null
			if ( strpos( $attributes->src, '//' ) !== FALSE ) {
				$this->externel_styles[]                        = $attributes->handle;
				$styles->registered[ $attributes->handle ]->src = NULL;
			}
		}

		return $styles;
	}

	/**
	 * Filter default scripts, unset external url
	 *
	 * @param $scripts
	 *
	 * @return mixed
	 */
	public function block_scripts( $scripts ) {

		if ( ! isset( $scripts->registered ) ) {
			return $scripts;
		}

		// if it give registered scripts
		foreach ( $scripts->registered as $script => $attributes ) {

			// if external url, then set src attribute to null
			if ( strpos( $attributes->src, '//' ) !== FALSE ) {
				$this->externel_scripts[]                        = $attributes->handle;
				$scripts->registered[ $attributes->handle ]->src = NULL;
			}
		}

		return $scripts;
	}

}