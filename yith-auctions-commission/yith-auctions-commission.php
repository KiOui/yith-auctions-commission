<?php
/**
 * Plugin Name: YITH Auctions Commission
 * Description: An extension for YITH Auctions to add commissions to auctions
 * Plugin URI: https://github.com/KiOui/yith-auctions-commission
 * Version: 0.0.1
 * Author: Lars van Rhijn
 * Author URI: https://larsvanrhijn.nl/
 * Text Domain: yith-auctions-commission
 * Domain Path: /languages/
 *
 * @package yith-auctions-commission
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'YITHAC_PLUGIN_FILE' ) ) {
	define( 'YITHAC_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'YITHAC_PLUGIN_URI' ) ) {
	define( 'YITHAC_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}

if ( ! function_exists( 'yithac_load_plugin_if_woocommerce_enabled' ) ) {
	/**
	 * Load plugin only if WooCommerce is enabled.
	 */
	function yithac_load_plugin_if_woocommerce_enabled() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			if ( is_admin() && current_user_can( 'edit_plugins' ) ) {
				echo '<div class="notice notice-error"><p>' . esc_html( __( 'YITH Auctions Commission requires WooCommerce to be active. Please activate WooCommerce to use YITH Auctions Commission.', 'yith-auctions-commission' ) ) . '</p></div>';
			}
		} else if ( ! defined( 'YITH_WCACT_PREMIUM' ) ) {
			if ( is_admin() && current_user_can( 'edit_plugins' ) ) {
				echo '<div class="notice notice-error"><p>' . esc_html( __( 'YITH Auctions Commission requires YITH Auctions to be active. Please activate YITH Auctions to use YITH Auctions Commission.', 'yith-auctions-commission' ) ) . '</p></div>';
			}
		} else {
			include_once dirname( __FILE__ ) . '/includes/class-yithaccore.php';
			YithAcCore::instance();
		}
	}
}
add_action( 'plugins_loaded', 'yithac_load_plugin_if_woocommerce_enabled' );
