<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase -- Not changing file name because this is the main plugin file, and changing this would lead to deacivation of plugin for the active users.
/**
 * MiniOrange All-in-One Microsoft
 *
 * @package    login-with-azure
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Plugin Name: All-in-One Microsoft Office 365 Apps + Azure/EntraID Login
 * Plugin URI: miniorange-all-in-one-microsoft
 * Description: Our All-in-One Microsoft for WordPress plugin integrates various Microsoft 365 services into your WordPress site, enhancing user experience and productivity with seamless access to Azure AD/ Entra ID, Azure AD B2C, SharePoint, PowerBI, Outlook, and Dynamics CRM.
 * Version: 2.1.3
 * Author: miniOrange
 * Author URI: https://www.miniorange.com
 * License: MIT/Expat
 * License URI: https://docs.miniorange.com/mit-license
 * Text Domain: login-with-azure
 * Domain Path: /languages
 */

/**
 * Adding required files.
 */
require 'handler' . DIRECTORY_SEPARATOR . 'class-moazure-handler.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'class-moazure-widget.php';
require_once 'class-moazure-client-customer.php';
require plugin_dir_path( __FILE__ ) . 'includes' . DIRECTORY_SEPARATOR . 'class-moazure-client.php';
require 'views' . DIRECTORY_SEPARATOR . 'feedback-form.php';
require 'moazure-constants.php';
require_once 'class-moazure.php';
define( 'MO_AZURE_CSS_JS_VERSION', '2.0.2' );
define( 'MO_AZURE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

new MOAzure();
/**
 * Run the plugin.
 */
function moazure_client_run() {
	$plugin = new MOAzure_Client();
	$plugin->run();
}
moazure_client_run();

/**
 * Check if customer is registered.
 */
function moazure_is_customer_registered() {
	$email        = get_option( 'moazure_admin_email' );
	$customer_key = get_option( 'moazure_admin_customer_key' );
	if ( ! $email || ! $customer_key || ! is_numeric( trim( $customer_key ) ) ) {
		return 0;
	} else {
		return 1;
	}
}

/**
 * Check if cURL is installed.
 */
function moazure_is_curl_installed() {
	if ( is_array( get_loaded_extensions() ) && in_array( 'curl', get_loaded_extensions(), true ) ) {
		return 1;
	} else {
		return 0;
	}
}
