<?php
/**
 * Plugin Name: WooCommerce Referral System
 * Plugin URI: http://woocommerce.com/products/woocommerce-extension/
 * Description: Its a referral system for woocommerce plugin.  <a target="_blank" href="https://woocommerce.com/products/referral-system-for-woocommerce/" >Click here to review the plugin!</a>
 * Version: 1.3.11.6
 * Author: Codup.io
 * Author URI: http://codup.io/
 * Text Domain: codup-wc-referral-system
 * Domain Path: /languages
 * WC requires at least: 3.8.0
 * WC tested up to: 8.4.0
 * Woo: 4891277:01076a97b164c25fc2b091f343fcf72b
 *
 * @package codup-wc-referral-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define constants.
 */
define( 'WCRS_PLUGIN_DIR', __DIR__ );
define( 'WCRS_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WCRS_TEMP_DIR', WCRS_PLUGIN_DIR . '/templates' );
define( 'WCRS_ASSETS_DIR_URL', WCRS_PLUGIN_DIR_URL . 'assets' );
if ( ! defined( 'WCRS_ABSPATH' ) ) {
	define( 'WCRS_ABSPATH', __DIR__ );
}
define( 'WCRS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Hook into the plugins_loaded action
add_action( 'plugins_loaded', 'wcrs_load_after_woocommerce' );

function wcrs_load_after_woocommerce() {
	// Check if WooCommerce is active
	if ( class_exists( 'WooCommerce' ) ) {
		// Include the necessary files
		require_once WCRS_PLUGIN_DIR . '/includes/class-referral-system-mixpanel.php';
		require_once WCRS_PLUGIN_DIR . '/includes/referral-system-dependencies.php';
		require_once WCRS_PLUGIN_DIR . '/includes/functions.php';
		require_once WCRS_PLUGIN_DIR . '/includes/class-referral-system-core.php';
		require_once WCRS_PLUGIN_DIR . '/includes/class-referral-system-settings.php';
		require_once WCRS_PLUGIN_DIR . '/contact_us/class-wcrs-contact-us.php';
		require_once WCRS_PLUGIN_DIR . '/vendor/autoload.php';

	}
}
