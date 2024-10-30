<?php
/**
 * Constants
 *
 * @package    constants
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

if ( ! defined( 'MO_AZURE_PLUGIN_NAME' ) ) {
	define( 'MO_AZURE_PLUGIN_NAME', 'All-in-One Microsoft' );
}
if ( ! defined( 'MO_AZURE_README_PLUGIN_NAME' ) ) {
	define( 'MO_AZURE_README_PLUGIN_NAME', 'All-in-One Microsoft Office 365 Apps + Azure/EntraID Login' );
}
if ( ! defined( 'MO_AZURE_README_PLUGIN_URI' ) ) {
	define( 'MO_AZURE_README_PLUGIN_URI', 'all-in-one-microsoft' );
}
if ( ! defined( 'MO_AZURE_AREA_OF_INTEREST' ) ) {
	define( 'MO_AZURE_AREA_OF_INTEREST', 'WP Azure Login' );
}
if ( ! defined( 'MO_AZURE_ADMIN_MENU' ) ) {
	define( 'MO_AZURE_ADMIN_MENU', 'All-in-One Microsoft' );
}
if ( ! defined( 'MO_AZURE_PLUGIN_SLUG' ) ) {
	define( 'MO_AZURE_PLUGIN_SLUG', 'all-in-one-microsoft' );
}
if ( ! defined( 'MO_AZURE_CLIENT_DEAL_DATE' ) ) {
	define( 'MO_AZURE_CLIENT_DEAL_DATE', '2021-12-31 23:59:59' );
}
if ( ! defined( 'MO_AZURE_DISCOUNT_URL' ) ) {
	if ( gmdate( 'Y-m-d H:i:s' ) <= MO_AZURE_CLIENT_DEAL_DATE ) {
		define( 'MO_AZURE_DISCOUNT_URL', '<p><font style="color:red; font-size:20px;"><a href="https://plugins.miniorange.com/wordpress-oauth-sso-end-of-the-year-deals" target="_blank"><u>CLICK HERE</u> </a> to know end of year deal</font></p>' );
	} else {
		define( 'MO_AZURE_DISCOUNT_URL', '' );
	}
}
