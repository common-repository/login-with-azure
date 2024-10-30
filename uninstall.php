<?php
/**
 * Uninstall
 *
 * @package    uninstall
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'host_name' );
delete_option( 'moazure_admin_email' );
delete_option( 'moazure_admin_phone' );
delete_option( 'moazure_verify_customer' );
delete_option( 'moazure_admin_customer_key' );
delete_option( 'moazure_admin_api_key' );
delete_option( 'moazure_customer_token' );
delete_option( 'moazure_new_customer' );
delete_option( 'moazure_new_registration' );
delete_option( 'moazure_registration_status' );
delete_option( 'moazure_show_mo_server_message' );
delete_option( 'mo_existing_app_flow' );
delete_option( 'moazure_oauth_sso_config' );
delete_option( 'moazure_test_attributes' );
delete_option( 'moazure_attr_option' );
delete_option( 'moazure_sps_selected_site' );
delete_option( 'moazure_sps_selected_site_name' );
delete_option( 'moazure_sps_selected_drive' );
delete_option( 'moazure_sps_selected_drive_name' );
delete_option( 'moazure_sps_folder_path' );
delete_option( 'moazure_sps_shortcode_embed' );
