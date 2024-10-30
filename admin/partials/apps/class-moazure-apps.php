<?php
/**
 * App Handler
 *
 * @package    apps
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Adding required files
 */
require 'partials' . DIRECTORY_SEPARATOR . 'sign-in-settings.php';
require 'partials' . DIRECTORY_SEPARATOR . 'customization.php';
require 'partials' . DIRECTORY_SEPARATOR . 'add-oauth-app.php';
require 'partials' . DIRECTORY_SEPARATOR . 'attr-role-mapping.php';

/**
 * Manage App UI
 */
class MOAzure_Apps {

	/**
	 * Display Sign In Settings
	 */
	public static function sign_in_settings() {
		moazure_sign_in_settings_ui();
	}

	/**
	 * Display Customization tab
	 */
	public static function customization() {
		moazure_customization_ui();
	}

	/**
	 * Display the configuration panel for the app
	 */
	public static function add_oauth_app() {
		moazure_add_oauth_app_page();
	}

	/**
	 * Display the Attribute Mapping settings for the configured application
	 */
	public static function attribute_role_mapping() {
		moazure_attribute_role_mapping_ui();
	}
}
