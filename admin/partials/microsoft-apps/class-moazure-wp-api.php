<?php
/**
 * WordPress rest endpoints for MS apps.
 *
 * @package    microsoft-apps
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Manage the WordPress's REST API endpoints for plugin.
 */
class MOAzure_WP_API {

	/**
	 * Stores the MOAzure_SPS_API object.
	 *
	 * @var object
	 */
	private static $api_obj;

	/**
	 * Stores the namespace for rest endpoints.
	 *
	 * @var string
	 */
	private static $namespace = 'login-with-azure/v1';

	/**
	 * Function to define sharepoint REST endpoints
	 *
	 * @return void
	 */
	public static function moazure_rest_endpoints() {

		self::$api_obj = new MOAzure_Azure_API();

		register_rest_route(
			self::$namespace,
			'/get-site',
			array(
				'methods'             => 'POST',
				'callback'            => array( self::class, 'moazure_sps_getsite_endpoint' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::$namespace,
			'/get-drive',
			array(
				'methods'             => 'POST',
				'callback'            => array( self::class, 'moazure_sps_getdrive_endpoint' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::$namespace,
			'/get-docs',
			array(
				'methods'             => 'POST',
				'callback'            => array( self::class, 'moazure_sps_getdocs_endpoint' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::$namespace,
			'/find-items',
			array(
				'methods'             => 'POST',
				'callback'            => array( self::class, 'moazure_sps_finditem_endpoint' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::$namespace,
			'/get-docs-by-path',
			array(
				'methods'             => 'POST',
				'callback'            => array( self::class, 'moazure_sps_get_docs_by_path' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Function to handle the getsite endpoint
	 *
	 * @param mixed $request WP_REST_Request instance.
	 * @return mixed
	 */
	public static function moazure_sps_getsite_endpoint( $request ) {

		$response_data = array();

		$data = json_decode( $request->get_body(), true );

		$site_res = self::$api_obj->moazure_sps_get_all_sites();

		$sel_site_id   = '';
		$sel_site_name = '';

		if ( 'defSite' === $data['site_fetch'] ) {
			$default_site = self::$api_obj->moazure_sps_get_default_site();
			if ( $default_site['status'] ) {
				$sel_site_id                   = $default_site['data']['id'];
				$sel_site_name                 = $default_site['data']['displayName'];
				$response_data['default_site'] = $default_site['data'];
			} elseif ( $site_res['status'] ) {
				$sel_site_id                   = $site_res['data']['value'][0]['id'];
				$sel_site_name                 = $site_res['data']['value'][0]['displayName'];
				$response_data['default_site'] = array(
					'id'          => $sel_site_id,
					'displayName' => $sel_site_name,
				);
			} else {
				update_option( 'moazure_sps_err', $default_site['data'] );
				return rest_ensure_response( $default_site['data'] );
			}

			update_option( 'moazure_sps_selected_site', $sel_site_id );
			update_option( 'moazure_sps_selected_site_name', $sel_site_name );

		}

		if ( $site_res['status'] ) {
			$sites                  = $site_res['data']['value'];
			$response_data['sites'] = $sites;
		} elseif ( 'defSite' !== $data['site_fetch'] ) {
			update_option( 'moazure_sps_err', $site_res['data'] );
			return rest_ensure_response( $site_res['data'] );
		}

		return rest_ensure_response( $response_data );
	}

	/**
	 * Function to handle the getdrive endpoint
	 *
	 * @param mixed $request WP_REST_Request instance.
	 * @return mixed
	 */
	public static function moazure_sps_getdrive_endpoint( $request ) {

		$response_data = array();

		$data = json_decode( $request->get_body(), true );

		$drive_res = self::$api_obj->moazure_sps_get_all_drives( $data['site_id'] );

		$sel_drive_id   = '';
		$sel_drive_name = '';

		update_option( 'moazure_sps_selected_site', $data['site_id'] );
		update_option( 'moazure_sps_selected_site_name', $data['site_name'] );

		if ( 'defDrive' === $data['drive_fetch'] ) {
			$default_drive = self::$api_obj->moazure_sps_get_default_drive( $data['site_id'] );
			if ( $default_drive['status'] ) {
				$sel_drive_id                   = $default_drive['data']['id'];
				$sel_drive_name                 = $default_drive['data']['name'];
				$response_data['default_drive'] = $default_drive['data'];
			} elseif ( $drive_res['status'] ) {
				$sel_drive_id                   = $drive_res['data']['value'][0]['id'];
				$sel_drive_name                 = $drive_res['data']['value'][0]['name'];
				$response_data['default_drive'] = array(
					'id'   => $sel_drive_id,
					'name' => $sel_drive_name,
				);
			} else {
				update_option( 'moazure_sps_err', $default_drive['data'] );
				return rest_ensure_response( $default_drive['data'] );
			}

			update_option( 'moazure_sps_selected_drive', $sel_drive_id );
			update_option( 'moazure_sps_selected_drive_name', $sel_drive_name );

		}

		update_option( 'moazure_sps_all_drives', $drive_res['data']['value'] );

		if ( $drive_res['status'] ) {
			$drives                  = $drive_res['data']['value'];
			$response_data['drives'] = $drives;
		} elseif ( 'defDrive' !== $data['drive_fetch'] ) {
			update_option( 'moazure_sps_err', $drive_res['data'] );
			return rest_ensure_response( $drive_res['data'] );
		}

		return rest_ensure_response( $response_data );
	}

	/**
	 * Function to handle the getdocs endpoint
	 *
	 * @param mixed $request WP_REST_Request instance.
	 * @return mixed
	 */
	public static function moazure_sps_getdocs_endpoint( $request ) {

		$data = json_decode( $request->get_body(), true );

		$doc_res = self::$api_obj->moazure_sps_get_drive_docs( $data['drive_id'] );

		$documents = array();

		if ( $doc_res['status'] ) {
			$documents = $doc_res['data']['value'];
			update_option( 'moazure_sps_selected_drive', $data['drive_id'] );
			update_option( 'moazure_sps_selected_drive_name', $data['drive_name'] );
			delete_option( 'moazure_sps_folder_path' );
		} else {
			update_option( 'moazure_sps_err', $doc_res['data'] );
			return rest_ensure_response( $doc_res['data'] );
		}

		return rest_ensure_response( $documents );
	}

	/**
	 * Function to handle the finditem endpoint
	 *
	 * @param mixed $request WP_REST_Request instance.
	 * @return mixed
	 */
	public static function moazure_sps_finditem_endpoint( $request ) {

		$data = json_decode( $request->get_body(), true );

		$searchitem_res = self::$api_obj->moazure_sps_search_through_drive_items( $data['drive_id'], $data['query_text'] );

		if ( $searchitem_res['status'] ) {
			$searchitems = $searchitem_res['data']['value'];
		} else {
			update_option( 'moazure_sps_err', $searchitem_res['data'] );
			return rest_ensure_response( $searchitem_res['data'] );
		}

		return rest_ensure_response( $searchitems );
	}

	/**
	 * Function to handle the docs by path endpoint
	 *
	 * @param mixed $request WP_REST_Request instance.
	 * @return mixed
	 */
	public static function moazure_sps_get_docs_by_path( $request ) {

		$data = json_decode( $request->get_body(), true );

		$doc_path_res = self::$api_obj->moazure_sps_get_docs_using_path( $data['doc_path'] );

		if ( $doc_path_res['status'] ) {
			$path_docs = $doc_path_res['data']['value'];
			update_option( 'moazure_sps_folder_path', $data['doc_path'] );
		} else {
			update_option( 'moazure_sps_err', $doc_path_res['data'] );
			return rest_ensure_response( $doc_path_res['data'] );
		}

		return rest_ensure_response( $path_docs );
	}
}
