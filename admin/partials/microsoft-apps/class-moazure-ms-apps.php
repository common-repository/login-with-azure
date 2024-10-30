<?php
/**
 * Microsoft Apps admin settings view file.
 *
 * @package    microsoft-apps
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

require 'sharepoint' . DIRECTORY_SEPARATOR . 'class-moazure-sharepoint-config.php';
require 'sharepoint' . DIRECTORY_SEPARATOR . 'class-moazure-sharepoint-shortcode.php';
require 'sharepoint' . DIRECTORY_SEPARATOR . 'class-moazure-sps-embed-view.php';
require 'sharepoint' . DIRECTORY_SEPARATOR . 'class-moazure-sps-advanced.php';
require 'power-bi' . DIRECTORY_SEPARATOR . 'class-moazure-powerbi-config.php';
require 'power-bi' . DIRECTORY_SEPARATOR . 'class-moazure-powerbi-shortcode.php';
require 'power-bi' . DIRECTORY_SEPARATOR . 'class-moazure-pbi-settings.php';
require 'class-moazure-apps-enum.php';

/**
 * Class to handle the admin display of microsoft apps.
 */
class MOAzure_MS_Apps {

	/**
	 * Entra ID app var.
	 *
	 * @var array var containing the entra id application config.
	 */
	private $entra_app = array();

	/**
	 * MS entra apps var.
	 *
	 * @var array var containing the app config type for microsoft apps.
	 */
	private $ms_entra_apps = array();

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->set_ms_entra_apps();
	}

	/**
	 * Function to set MS entra apps.
	 *
	 * @return void
	 */
	public function set_ms_entra_apps() {
		$this->ms_entra_apps = MOAzure_Admin_Utils::moazure_get_option( 'moazure_ms_entra_apps' );
	}

	/**
	 * Function to set entra id app.
	 *
	 * @return void
	 */
	public function set_entra_app() {
		$app             = MOAzure_Admin_Utils::moazure_get_azure_app_config();
		$this->entra_app = $app['config'];
	}

	/**
	 * Function to redirect to MS app as per the tab.
	 *
	 * @param string $tab tab parameter.
	 * @return void
	 */
	public function ms_apps_redirect( $tab ) {

		$ms_apps = $this->ms_entra_apps;

		switch ( $tab ) {

			case 'sps_preview':
				$ms_app_handler = MOAzure_Sharepoint_Config::get_sps_config_obj();
				if ( ! empty( $ms_apps['sps_auto'] ) || ! empty( $ms_apps['sps_manual'] ) ) {
					$ms_app_handler->moazure_sharepoint_app_page();
				} else {
					$ms_app_handler->moazure_sharepoint_initial_page();
				}
				break;

			case 'sps_shortcode':
				$ms_app_handler = MOAzure_Sharepoint_Shortcode::get_sps_shortcode_obj();
				$ms_app_handler->moazure_sps_shortcode_page();
				break;

			case 'sps_embed':
				$ms_app_handler = MOAzure_SPS_Embed_View::get_sps_embed_view_obj();
				$ms_app_handler->moazure_sps_embed_view_page();
				break;

			case 'sps_advanced':
				$ms_app_handler = MOAzure_SPS_Advanced::get_sps_advanced_obj();
				$ms_app_handler->moazure_sps_advanced_settings();
				break;

			case 'pbi_app':
				$ms_app_handler = MOAzure_PowerBI_Config::get_pbi_config_obj();
				if ( ! empty( $ms_apps['pbi_manual'] ) ) {
					$ms_app_handler->moazure_powerbi_app_page();
				} else {
					$ms_app_handler->moazure_powerbi_initial_page();
				}
				break;

			case 'pbi_settings':
				$ms_app_handler = MOAzure_PBI_Settings::get_pbi_settings_obj();
				$ms_app_handler->moazure_pbi_settings();
				break;

			default:
				break;
		}
	}
}
