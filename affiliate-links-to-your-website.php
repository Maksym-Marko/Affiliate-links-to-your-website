<?php
/*
Plugin Name: Affiliate Links WooCommerce
Plugin URI: https://github.com/Maxim-us/Affiliate-links-to-your-website
Description: This plugin allows users to create affiliate links. When someone will buy a product on your site, you will know who's showed your store to this customer.
Author: Marko Maksym
Version: 1.0
Author URI: https://github.com/Maxim-us
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Unique string - MXALTYW
*/

/*
* Define MXALTYW_PLUGIN_PATH
*
* E:\OpenServer\domains\my-domain.com\wp-content\plugins\affiliate-links-to-your-website\affiliate-links-to-your-website.php
*/
if ( ! defined( 'MXALTYW_PLUGIN_PATH' ) ) {

	define( 'MXALTYW_PLUGIN_PATH', __FILE__ );

}

/*
* Define MXALTYW_PLUGIN_URL
*
* Return http://my-domain.com/wp-content/plugins/affiliate-links-to-your-website/
*/
if ( ! defined( 'MXALTYW_PLUGIN_URL' ) ) {

	define( 'MXALTYW_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

}

/*
* Define MXALTYW_PLUGN_BASE_NAME
*
* 	Return affiliate-links-to-your-website/affiliate-links-to-your-website.php
*/
if ( ! defined( 'MXALTYW_PLUGN_BASE_NAME' ) ) {

	define( 'MXALTYW_PLUGN_BASE_NAME', plugin_basename( __FILE__ ) );

}

/*
* Define MXALTYW_TABLE_SLUG
*/
if ( ! defined( 'MXALTYW_TABLE_SLUG' ) ) {

	define( 'MXALTYW_TABLE_SLUG', 'mxaltyw_table_slug' );

}

/*
* Define MXALTYW_PLUGIN_ABS_PATH
* 
* E:\OpenServer\domains\my-domain.com\wp-content\plugins\affiliate-links-to-your-website/
*/
if ( ! defined( 'MXALTYW_PLUGIN_ABS_PATH' ) ) {

	define( 'MXALTYW_PLUGIN_ABS_PATH', dirname( MXALTYW_PLUGIN_PATH ) . '/' );

}

/*
* Define MXALTYW_PLUGIN_VERSION
*/
if ( ! defined( 'MXALTYW_PLUGIN_VERSION' ) ) {

	// version
	define( 'MXALTYW_PLUGIN_VERSION', '1.0' ); // Must be replaced before production on for example '1.0'

}

/*
* Define MXALTYW_MAIN_MENU_SLUG
*/
if ( ! defined( 'MXALTYW_MAIN_MENU_SLUG' ) ) {

	// version
	define( 'MXALTYW_MAIN_MENU_SLUG', 'mxaltyw-affiliate-links-to-your-website-menu' );

}

/**
 * activation|deactivation
 */
require_once plugin_dir_path( __FILE__ ) . 'install.php';

/*
* Registration hooks
*/
// Activation
register_activation_hook( __FILE__, array( 'MXALTYW_Basis_Plugin_Class', 'activate' ) );

// Deactivation
register_deactivation_hook( __FILE__, array( 'MXALTYW_Basis_Plugin_Class', 'deactivate' ) );


/*
* Include the main MXALTYWAffiliateLinksToYourWebsite class
*/
if ( ! class_exists( 'MXALTYWAffiliateLinksToYourWebsite' ) ) {

	require_once plugin_dir_path( __FILE__ ) . 'includes/final-class.php';

	/*
	* Translate plugin
	*/
	add_action( 'plugins_loaded', 'mxaltyw_translate' );

	function mxaltyw_translate()
	{

		load_plugin_textdomain( 'mxaltyw-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

}