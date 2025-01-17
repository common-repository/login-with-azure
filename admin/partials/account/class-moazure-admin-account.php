<?php
/**
 * Account
 *
 * @package    account
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Adding required files
 */
require 'partials' . DIRECTORY_SEPARATOR . 'register.php';
require 'partials' . DIRECTORY_SEPARATOR . 'verify-password.php';

/**
 * Create new user/Login existing user using miniOrange credentials.
 */
class MOAzure_Admin_Account {

	/**
	 * Show UI to register users / display logged in user information
	 */
	public static function register() {
		if ( ! moazure_is_customer_registered() ) {
			moazure_register_ui();
		} else {
			moazure_show_customer_info();
		}
	}

	/**
	 * Verify miniOrange credentials of the user
	 */
	public static function verify_password() {
		moazure_verify_password_ui();
	}
}
