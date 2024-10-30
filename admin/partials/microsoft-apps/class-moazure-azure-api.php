<?php
/**
 * File containing Microsoft Apps APIs and data rendering functions.
 *
 * @package    microsoft-apps
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Class for handling the Microsoft Apps APIs and functionalities.
 */
class MOAzure_Azure_API {

	/**
	 * Object variable.
	 *
	 * @var object variable to instantiate the class.
	 */
	private static $instance;

	/**
	 * Access token var.
	 *
	 * @var string var containing the access token.
	 */
	private $access_token;

	/**
	 * Token endpoint var.
	 *
	 * @var string var containing the endpoint to fetch access token.
	 */
	private $token_ep;

	/**
	 * Report endpoint var.
	 *
	 * @var string var containing the endpoint to fetch the Power BI report content.
	 */
	private $report_ep;

	/**
	 * App scope var.
	 *
	 * @var string var containing app scope.
	 */
	private $scope;

	/**
	 * Refresh token var.
	 *
	 * @var string var containing the refresh token.
	 */
	private $refresh_token;

	/**
	 * API header var.
	 *
	 * @var array var containing the API headers.
	 */
	private $headers;

	/**
	 * App config var.
	 *
	 * @var array var containing the Entra ID app config.
	 */
	private $appconfig;

	/**
	 * Sharepoint refresh token var.
	 *
	 * @var string var containing the sharepoint refresh token.
	 */
	private $sps_ref_token;

	/**
	 * MS manual app var.
	 *
	 * @var boolean var to check if the ms app is connected manually.
	 */
	private $is_ms_manual = false;

	/**
	 * Common token endpoint var.
	 *
	 * @var string var containing the common endpoint to fetch the token.
	 */
	private $common_token_ep = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';

	/**
	 * MO Client ID var.
	 *
	 * @var string var containing the miniOrange client id.
	 */
	private $mo_client_id = 'af7539f1-b05e-4d99-9655-47f73d0be528';

	/**
	 * MO Client Secret var.
	 *
	 * @var string var containing the miniOrange client secret.
	 */
	private $mo_client_secret = 'vv68Q~7m-8-qVZExLkxxy3EMvaQ1gfzEoRahZa_Y';

	/**
	 * MO server url var.
	 *
	 * @var string var containing the miniOrange server url.
	 */
	private $mo_server_url = 'https://connect.xecurify.com';

	/**
	 * SPS all site endpoint var.
	 *
	 * @var string var containing the endpoint to fetch all sharepoint sites.
	 */
	private $sps_all_site_ep = 'https://graph.microsoft.com/v1.0/sites?search=*&\$select=id,displayName';

	/**
	 * SPS default site endpoint var.
	 *
	 * @var string var containing the endpoint to fetch default sharepoint site.
	 */
	private $sps_def_site_ep = 'https://graph.microsoft.com/v1.0/sites/root';

	/**
	 * SPS all drive endpoint var.
	 *
	 * @var string var containing the endpoint to fetch all sharepoint drives.
	 */
	private $sps_all_drive_ep = 'https://graph.microsoft.com/v1.0/sites/%s/drives';

	/**
	 * SPS default drive endpoint var.
	 *
	 * @var string var containing the endpoint to fetch default sharepoint drive.
	 */
	private $sps_def_drive_ep = 'https://graph.microsoft.com/v1.0/sites/%s/drive';

	/**
	 * SPS docs endpoint var.
	 *
	 * @var string var containing the endpoint to fetch the sharepoint docs.
	 */
	private $sps_docs_ep = 'https://graph.microsoft.com/v1.0/drives/%s/root/children';

	/**
	 * SPS folder items endpoint var.
	 *
	 * @var string var containing the endpoint to fetch the items in a folder.
	 */
	private $sps_folder_items_ep = 'https://graph.microsoft.com/v1.0/sites/%s/drives/%s/items/%s/children';

	/**
	 * SPS docs by path endpoint var.
	 *
	 * @var string var containing the endpoint to fetch sharepoint documents by path.
	 */
	private $sps_docs_by_path_ep = 'https://graph.microsoft.com/v1.0%s:/children';

	/**
	 * SPS search drive item endpoint var.
	 *
	 * @var string var containing the endpoint to search the items in a drive.
	 */
	private $sps_search_drive_items_ep = 'https://graph.microsoft.com/v1.0/drives/%s/root/search(q="%s")';

	/**
	 * SPS user endpoint var.
	 *
	 * @var string var containing the endpoint to fetch the sharepoint user.
	 */
	private $sps_user_ep = 'https://graph.microsoft.com/v1.0/me';

	/**
	 * Function to get the object of the class
	 *
	 * @return object
	 */
	public static function get_azure_api_obj() {
		if ( ! isset( self::$instance ) ) {
			$class          = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	/**
	 * Function to set appconfig.
	 *
	 * @return void
	 */
	public function set_appconfig() {
		$app             = MOAzure_Admin_Utils::moazure_get_azure_app_config();
		$this->appconfig = $app['config'];
	}

	/**
	 * Function to set sharepoint refresh token.
	 *
	 * @return void
	 */
	public function set_sps_refresh_token() {
		$this->sps_ref_token = MOAzure_Admin_Utils::moazure_get_option( 'moazure_auto_ref_token' );
	}

	/**
	 * Function to set access token.
	 *
	 * @param string $acc_tkn access token parameter.
	 * @return void
	 */
	public function set_access_token( $acc_tkn ) {
		$this->access_token = $acc_tkn;
	}

	/**
	 * Function to get the access token.
	 *
	 * @return string
	 */
	public function get_access_token() {
		return $this->access_token;
	}

	/**
	 * Function to get miniOrange client config.
	 *
	 * @return array
	 */
	public function get_mo_client_config() {
		$mo_client_config = array(
			'accesstokenurl' => $this->common_token_ep,
			'clientid'       => $this->mo_client_id,
			'clientsecret'   => $this->mo_client_secret,
			'redirecturi'    => $this->mo_server_url,
		);

		return $mo_client_config;
	}

	/**
	 * Function to set the MS app config type.
	 *
	 * @return void
	 */
	public function set_is_ms_manual() {
		$ms_auto_apps = MOAzure_Admin_Utils::moazure_get_option( 'moazure_ms_entra_apps' );
		if ( ! empty( $ms_auto_apps['sps_manual'] ) ) {
			$this->is_ms_manual = true;
		}
	}

	/**
	 * Function to set refresh token.
	 *
	 * @param string $token refresh token parameter.
	 * @return void
	 */
	public function set_refresh_token( $token ) {
		$this->refresh_token = $token;
	}

	/**
	 * Function to set token endpoint.
	 *
	 * @param string $tenant_id tenant id parameter.
	 * @return void
	 */
	public function set_token_ep( $tenant_id ) {
		$this->token_ep = 'https://login.microsoftonline.com/' . $tenant_id . '/oauth2/v2.0/token';
	}

	/**
	 * Function to get token endpoint.
	 *
	 * @return string
	 */
	public function get_token_ep() {
		return $this->token_ep;
	}

	/**
	 * Function to get common token endpoint.
	 *
	 * @return string
	 */
	public function get_common_token_ep() {
		return $this->common_token_ep;
	}

	/**
	 * Function to set Power BI report endpoint.
	 *
	 * @param array $pbi_config power bi config parameter.
	 * @return void
	 */
	public function set_pbi_report_ep( $pbi_config ) {
		$this->report_ep = 'https://api.powerbi.com/v1.0/myorg/groups/' . $pbi_config['wid'] . '/reports/' . $pbi_config['rid'];
	}

	/**
	 * Function to get Power BI report endpoint.
	 *
	 * @return string
	 */
	public function get_pbi_report_ep() {
		return $this->report_ep;
	}

	/**
	 * Function to set scope.
	 *
	 * @param string $scope scope parameter.
	 * @return void
	 */
	public function set_scope( $scope ) {
		$this->scope = $scope;
	}

	/**
	 * Function to get scope.
	 *
	 * @return string
	 */
	public function get_scope() {
		return $this->scope;
	}

	/**
	 * Function to set Azure API headers.
	 *
	 * @return void
	 */
	public function set_headers() {
		$this->headers = array(
			'Authorization' => 'Bearer ' . $this->access_token,
			'Content-Type'  => 'application/json',
		);
	}

	/**
	 * Function to execute the sharepoint defaults.
	 *
	 * @return array
	 */
	public function moazure_set_sps_defaults() {

		$this->set_sps_refresh_token();
		$this->set_is_ms_manual();
		$appconfig = array();

		if ( ! empty( $this->sps_ref_token ) ) {
			$appconfig = $this->get_mo_client_config();
		} else {
			$this->set_appconfig();
			$appconfig = $this->appconfig;
			$this->set_token_ep( $appconfig['tenant-id'] );
		}

		$this->set_scope( 'https://graph.microsoft.com/.default' );

		return $appconfig;
	}

	/**
	 * Funtion to fetch default sharepoint site.
	 *
	 * @return array
	 */
	public function moazure_sps_get_default_site() {

		$appconfig = $this->moazure_set_sps_defaults();
		$token_ep  = ! empty( $this->token_ep ) ? $this->token_ep : $appconfig['accesstokenurl'];

		$default_site = $this->moazure_ms_apps_common( $this->sps_ref_token, $appconfig, $token_ep, $this->scope, $this->sps_def_site_ep, $this->is_ms_manual );

		return $default_site;
	}

	/**
	 * Function to fetch default drive of a specific site.
	 *
	 * @param string $site_id Contains the id of selected sharepoint site.
	 * @return array
	 */
	public function moazure_sps_get_default_drive( $site_id ) {

		$appconfig = $this->moazure_set_sps_defaults();
		$token_ep  = ! empty( $this->token_ep ) ? $this->token_ep : $appconfig['accesstokenurl'];

		$def_drive_ep = sprintf( $this->sps_def_drive_ep, $site_id );

		$default_drive = $this->moazure_ms_apps_common( $this->sps_ref_token, $appconfig, $token_ep, $this->scope, $def_drive_ep, $this->is_ms_manual );

		return $default_drive;
	}

	/**
	 * Function to fetch all sharepoint sites.
	 *
	 * @return array
	 */
	public function moazure_sps_get_all_sites() {

		$appconfig = $this->moazure_set_sps_defaults();
		$token_ep  = ! empty( $this->token_ep ) ? $this->token_ep : $appconfig['accesstokenurl'];

		$all_sites = $this->moazure_ms_apps_common( $this->sps_ref_token, $appconfig, $token_ep, $this->scope, $this->sps_all_site_ep, $this->is_ms_manual );

		return $all_sites;
	}

	/**
	 * Function to fetch all drives of a specific site.
	 *
	 * @param string $site_id contains the id of the selected sharepoint site.
	 * @return array
	 */
	public function moazure_sps_get_all_drives( $site_id ) {

		$appconfig = $this->moazure_set_sps_defaults();
		$token_ep  = ! empty( $this->token_ep ) ? $this->token_ep : $appconfig['accesstokenurl'];

		$all_drive_ep = sprintf( $this->sps_all_drive_ep, $site_id );

		$all_drives = $this->moazure_ms_apps_common( $this->sps_ref_token, $appconfig, $token_ep, $this->scope, $all_drive_ep, $this->is_ms_manual );

		return $all_drives;
	}

	/**
	 * Function to fetch all docs of a specific drive.
	 *
	 * @param string $drive_id contains the id of the selected drive.
	 * @return array
	 */
	public function moazure_sps_get_drive_docs( $drive_id ) {

		$appconfig = $this->moazure_set_sps_defaults();
		$token_ep  = ! empty( $this->token_ep ) ? $this->token_ep : $appconfig['accesstokenurl'];

		$docs_ep = sprintf( $this->sps_docs_ep, $drive_id );

		$docs = $this->moazure_ms_apps_common( $this->sps_ref_token, $appconfig, $token_ep, $this->scope, $docs_ep, $this->is_ms_manual );

		return $docs;
	}

	/**
	 * Function to search for files or folders in a site’s drives by keyword.
	 *
	 * @param string $drive_id contains the id of the selected drive.
	 * @param string $query_text contains the text used as the search query.
	 * @return array
	 */
	public function moazure_sps_search_through_drive_items( $drive_id, $query_text ) {

		$appconfig = $this->moazure_set_sps_defaults();
		$token_ep  = ! empty( $this->token_ep ) ? $this->token_ep : $appconfig['accesstokenurl'];

		$drive_items_ep = sprintf( $this->sps_search_drive_items_ep, $drive_id, $query_text );

		$drive_items = $this->moazure_ms_apps_common( $this->sps_ref_token, $appconfig, $token_ep, $this->scope, $drive_items_ep, $this->is_ms_manual );

		return $drive_items;
	}

	/**
	 * Function to fetch documents using the path.
	 *
	 * @param string $path contains the file/folder path.
	 * @return array
	 */
	public function moazure_sps_get_docs_using_path( $path ) {

		$appconfig = $this->moazure_set_sps_defaults();
		$token_ep  = ! empty( $this->token_ep ) ? $this->token_ep : $appconfig['accesstokenurl'];

		$docs_by_path_ep = sprintf( $this->sps_docs_by_path_ep, $path );

		$docs_by_path = $this->moazure_ms_apps_common( $this->sps_ref_token, $appconfig, $token_ep, $this->scope, $docs_by_path_ep, $this->is_ms_manual );

		return $docs_by_path;
	}

	/**
	 * Function to get the Sharepoint user details.
	 *
	 * @return array
	 */
	public function moazure_sps_get_my_user() {

		$appconfig = $this->moazure_set_sps_defaults();
		$token_ep  = ! empty( $this->token_ep ) ? $this->token_ep : $appconfig['accesstokenurl'];

		$my_user = $this->moazure_ms_apps_common( $this->sps_ref_token, $appconfig, $token_ep, $this->scope, $this->sps_user_ep, $this->is_ms_manual );

		return $my_user;
	}

	/**
	 * Function to get the Power BI report content.
	 *
	 * @return array
	 */
	public function moazure_pbi_get_report_content() {

		MOAzure_Admin_Utils::moazure_start_session();
		$ref_tkn = ! empty( $_SESSION['ref_token'] ) ? sanitize_text_field( wp_unslash( $_SESSION['ref_token'] ) ) : '';
		MOAzure_Admin_Utils::moazure_write_close_session();

		$this->set_appconfig();
		$this->set_token_ep( $this->appconfig['tenant-id'] );
		$this->set_scope( 'https://analysis.windows.net/powerbi/api/.default offline_access' );

		$report_res = $this->moazure_ms_apps_common( $ref_tkn, $this->appconfig, $this->token_ep, $this->scope, $this->report_ep );

		$report_data = $report_res['status'] ? $report_res['data'] : array();
		return $report_data;
	}

	/**
	 * Function to perform the OAuth calls to token retrieval.
	 *
	 * @param string  $ref_token refresh token parameter.
	 * @param array   $appconfig appconfig parameter.
	 * @param string  $token_endpoint token endpoint parameter.
	 * @param string  $scope scope parameter.
	 * @param string  $azure_get_ep azure get endpoint parameter.
	 * @param boolean $is_ms_manual microsoft manual app parameter.
	 * @return array
	 */
	public function moazure_ms_apps_common( $ref_token, $appconfig, $token_endpoint, $scope, $azure_get_ep, $is_ms_manual = false ) {

		$moazure_handler = new MOAzure_Handler();
		$code            = MOAzure_Admin_Utils::moazure_get_option( 'moazure_auth_code' );

		if ( $is_ms_manual ) {
			$token_response = $moazure_handler->moazure_get_token_res(
				'client_credentials',
				$token_endpoint,
				$appconfig,
				$this->scope,
				'',
				true,
				'',
			);
		} elseif ( ! empty( $ref_token ) ) {
				$token_response = $moazure_handler->moazure_get_token_res(
					'refresh_token',
					$token_endpoint,
					$appconfig,
					$this->scope,
					$ref_token,
					true,
					'',
				);
		} else {
			$token_response = $moazure_handler->moazure_get_token_res(
				'authorization_code',
				$token_endpoint,
				$appconfig,
				$scope,
				'',
				true,
				$code,
			);
		}

		$this->access_token = ! empty( $token_response['access_token'] ) ? $token_response['access_token'] : '';
		$this->set_headers();

		$response = $moazure_handler->moazure_get_request( $azure_get_ep, $this->headers );

		return $response;
	}
}
