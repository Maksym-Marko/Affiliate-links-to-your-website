<?php

/*
Plugin Name: Affiliate Links Expert
Plugin URI: https://github.com/Maxim-us/Affiliate-links-to-your-website
Description: As a website owner, you can easily manage all your affiliate data from the admin panel. Also it is useful for users who want to create affiliate links and share them through social media platforms or websites.
Author: Maksym Marko
Version: 2.3
Author URI: https://markomaksym.com.ua
*/

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/*
* Unique string - MXALFWP
*/

/*
* Define MXALFWP_PLUGIN_PATH
*
* E:\OpenServer\domains\my-domain.com\wp-content\plugins\affiliate-links-for-woocommerce-plugin\affiliate-links-for-woocommerce-plugin.php
*/
if (!defined('MXALFWP_PLUGIN_PATH')) {

	define( 'MXALFWP_PLUGIN_PATH', __FILE__ );

}

/*
* Define MXALFWP_PLUGIN_URL
*
* Return http://my-domain.com/wp-content/plugins/affiliate-links-for-woocommerce-plugin/
*/
if (!defined('MXALFWP_PLUGIN_URL')) {

	define( 'MXALFWP_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

}

/*
* Define MXALFWP_PLUGN_BASE_NAME
*
* 	Return affiliate-links-for-woocommerce-plugin/affiliate-links-for-woocommerce-plugin.php
*/
if (!defined( 'MXALFWP_PLUGN_BASE_NAME')) {

	define( 'MXALFWP_PLUGN_BASE_NAME', plugin_basename( __FILE__ ) );

}

/*
* Define MXALFWP_TABLE_SLUG
*/
if (!defined('MXALFWP_TABLE_SLUG')) {

	define( 'MXALFWP_TABLE_SLUG', 'mxalfwp_affiliate_links' );

}

/*
* Define MXALFWP_USERS_TABLE_SLUG
*/
if (!defined('MXALFWP_USERS_TABLE_SLUG')) {

	define( 'MXALFWP_USERS_TABLE_SLUG', 'mxalfwp_affiliate_users' );

}

/*
* Define MXALFWP_ORDERS_TABLE_SLUG
*/
if (!defined('MXALFWP_ORDERS_TABLE_SLUG')) {

	define( 'MXALFWP_ORDERS_TABLE_SLUG', 'mxalfwp_affiliate_orders' );

}

/*
* Define MXALFWP_PLUGIN_ABS_PATH
* 
* E:\OpenServer\domains\my-domain.com\wp-content\plugins\affiliate-links-for-woocommerce-plugin/
*/
if (!defined( 'MXALFWP_PLUGIN_ABS_PATH')) {

	define( 'MXALFWP_PLUGIN_ABS_PATH', dirname( MXALFWP_PLUGIN_PATH ) . '/' );

}

/*
* Define MXALFWP_PLUGIN_VERSION
*/
if (!defined('MXALFWP_PLUGIN_VERSION')) {

	// version
	define( 'MXALFWP_PLUGIN_VERSION', '2.3' ); // Must be replaced before production on for example '1.0'

}

/*
* Define MXALFWP_MAIN_MENU_SLUG
*/
if (!defined('MXALFWP_MAIN_MENU_SLUG')) {

	// version
	define( 'MXALFWP_MAIN_MENU_SLUG', 'mxalfwp-affiliate-links' );

}

/*
* Define MXALFWP_SINGLE_TABLE_ITEM_MENU
*/
if (!defined( 'MXALFWP_SINGLE_TABLE_ITEM_MENU')) {

	// single table item menu
	define( 'MXALFWP_SINGLE_TABLE_ITEM_MENU', 'mxalfwp-affiliate-links-analytics' );

}

/*
* Define MXALFWP_CREATE_TABLE_ITEM_MENU
*/
if (!defined('MXALFWP_CREATE_TABLE_ITEM_MENU')) {

	// table item menu
	define( 'MXALFWP_CREATE_TABLE_ITEM_MENU', 'mxalfwp-affiliate-links-create' );

}

/**
 * activation|deactivation
 */
require_once plugin_dir_path( __FILE__ ) . 'install.php';

/*
* Registration hooks
*/
// Activation
register_activation_hook( __FILE__, ['MXALFWPBasisPluginClass', 'activate'] );

// Deactivation
register_deactivation_hook( __FILE__, ['MXALFWPBasisPluginClass', 'deactivate'] );

/*
* Include the main MXALFWPAffiliateLinksForWoocommercePlugin class
*/
if (!class_exists('MXALFWPAffiliateLinksForWoocommercePlugin')) {

	require_once plugin_dir_path( __FILE__ ) . 'includes/final-class.php';

	/*
	* Translate plugin
	*/
	add_action( 'plugins_loaded', 'mxalfwp_translate' );

	function mxalfwp_translate()
	{

		load_plugin_textdomain( 'mxalfwp-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

}
