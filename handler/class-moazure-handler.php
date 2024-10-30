<?php
/**
 * OAuth Handler
 *
 * @package    oauth-handler
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * [Description Handle OAuth token, and resource_owner_info API]
 */
class MOAzure_Handler {

	/**
	 * Fetch Access Token
	 *
	 * @param mixed $tokenendpoint token endpoint of OAuth/OpendID provider.
	 * @param mixed $grant_type grant type of OAuth/OpendID provider.
	 * @param mixed $clientid client ID of OAuth/OpendID provider.
	 * @param mixed $clientsecret client secret of OAuth/OpendID provider.
	 * @param mixed $code authorization code obtained from OAuth/OpendID provider.
	 * @param mixed $redirect_url redirect URL configured in the plugin.
	 * @return [string]
	 */
	public function moazure_auth_code_grant( $tokenendpoint, $grant_type, $clientid, $clientsecret, $code, $redirect_url ) {

		$clientsecret = html_entity_decode( $clientsecret );
		$body         = array(
			'grant_type'    => $grant_type,
			'code'          => $code,
			'client_id'     => $clientid,
			'client_secret' => $clientsecret,
			'redirect_uri'  => $redirect_url,
		);
		$headers      = array(
			'Accept'        => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic ' . base64_encode( $clientid . ':' . $clientsecret ), //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Base64 encoding of client id and secret will be required for sending Authentication header in Token request.
			'Content-Type'  => 'application/x-www-form-urlencoded',
		);

		$response = wp_remote_post(
			$tokenendpoint,
			array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => $headers,
				'body'        => $body,
				'cookies'     => array(),
				'sslverify'   => false,
			)
		);

		if ( is_wp_error( $response ) ) {
			wp_die( $response ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $response is escaped before being passed in.
		}
		$response = $response['body'];
		if ( ! is_array( json_decode( $response, true ) ) ) {
			echo '<b>Response : </b><br>' . esc_html( $response );
			echo '<br><br>';
			exit( 'Invalid response received.' );
		}

		return $response;
	}

	/**
	 * Fetch Refresh Token
	 *
	 * @param mixed $tokenendpoint token endpoint of OAuth/OpendID provider.
	 * @param mixed $grant_type grant type of OAuth/OpendID provider.
	 * @param mixed $clientid client ID of OAuth/OpendID provider.
	 * @param mixed $clientsecret client secret of OAuth/OpendID provider.
	 * @param mixed $ref_token refresh token received previously.
	 * @param mixed $scope scope parameter.
	 * @param mixed $redirect_uri redirect uri parameter.
	 * @return mixed
	 */
	public function moazure_refresh_token_grant( $tokenendpoint, $grant_type, $clientid, $clientsecret, $ref_token, $scope = '', $redirect_uri = '' ) {
		$clientsecret = html_entity_decode( $clientsecret );
		$body         = array(
			'grant_type'    => $grant_type,
			'client_id'     => $clientid,
			'client_secret' => $clientsecret,
			'refresh_token' => $ref_token,
			'scope'         => $scope,
			'redirect_uri'  => $redirect_uri,
		);
		$headers      = array(
			'Accept'        => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic ' . base64_encode( $clientid . ':' . $clientsecret ), //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Base64 encoding of client id and secret will be required for sending Authentication header in Token request.
			'Content-Type'  => 'application/x-www-form-urlencoded',
		);

		$response = wp_remote_post(
			$tokenendpoint,
			array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => $headers,
				'body'        => $body,
				'cookies'     => array(),
				'sslverify'   => false,
			)
		);
		if ( is_wp_error( $response ) ) {
			wp_die( $response ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $response is escaped before being passed in.
		}
		$response = $response['body'];
		if ( ! is_array( json_decode( $response, true ) ) ) {
			echo '<b>Response : </b><br>' . esc_html( $response );
			echo '<br><br>';
			exit( 'Invalid response received.' );
		}

		return $response;
	}

	/**
	 * Fetch Client Token
	 *
	 * @param mixed $tokenendpoint token endpoint of OAuth/OpendID provider.
	 * @param mixed $grant_type grant type of OAuth/OpendID provider.
	 * @param mixed $clientid client ID of OAuth/OpendID provider.
	 * @param mixed $clientsecret client secret of OAuth/OpendID provider.
	 * @param mixed $scope the scope of application.
	 * @return mixed
	 */
	public function moazure_client_credentials_grant( $tokenendpoint, $grant_type, $clientid, $clientsecret, $scope ) {
		$clientsecret = html_entity_decode( $clientsecret );
		$body         = array(
			'grant_type'    => $grant_type,
			'client_id'     => $clientid,
			'client_secret' => $clientsecret,
			'scope'         => $scope,
		);
		$headers      = array(
			'Accept'        => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic ' . base64_encode( $clientid . ':' . $clientsecret ), //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Base64 encoding of client id and secret will be required for sending Authentication header in Token request.
			'Content-Type'  => 'application/x-www-form-urlencoded',
		);

		$response = wp_remote_post(
			$tokenendpoint,
			array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => $headers,
				'body'        => $body,
				'cookies'     => array(),
				'sslverify'   => false,
			)
		);
		if ( is_wp_error( $response ) ) {
			wp_die( $response ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $response is escaped before being passed in.
		}
		$response = $response['body'];
		if ( ! is_array( json_decode( $response, true ) ) ) {
			echo '<b>Response : </b><br>' . esc_html( $response );
			echo '<br><br>';
			exit( 'Invalid response received.' );
		}

		return $response;
	}

	/**
	 * OAuth grant handler function.
	 *
	 * @param string  $grant_type grant type parameter.
	 * @param string  $acc_token_url access token url parameter.
	 * @param array   $client_config client config parameter.
	 * @param string  $scope scope parameter.
	 * @param string  $ref_tkn refresh token parameter.
	 * @param string  $code code parameter.
	 * @param boolean $is_ms_app ms app parameter.
	 * @return array
	 */
	public function moazure_grant_handler( $grant_type, $acc_token_url, $client_config, $scope, $ref_tkn, $code, $is_ms_app ) {

		$token_response = array();

		if ( 'authorization_code' === $grant_type ) {
			$token_response = $this->moazure_auth_code_grant(
				$acc_token_url,
				'authorization_code',
				$client_config['clientid'],
				$client_config['clientsecret'],
				$code,
				$client_config['redirecturi']
			);
		} elseif ( 'refresh_token' === $grant_type ) {
			$token_response = $this->moazure_refresh_token_grant(
				$acc_token_url,
				'refresh_token',
				$client_config['clientid'],
				$client_config['clientsecret'],
				$ref_tkn,
				$scope,
			);
		} elseif ( 'client_credentials' === $grant_type ) {
			$token_response = $this->moazure_client_credentials_grant(
				$acc_token_url,
				'client_credentials',
				$client_config['clientid'],
				$client_config['clientsecret'],
				$scope,
			);
		}

		$token_response = json_decode( $token_response, true );
		$final_response = $this->moazure_response_handler( $token_response );

		if ( $final_response['status'] ) {
			return $final_response['data'];
		} else {
			// $this->moazure_res_error_handler( $final_response );
		}
	}

	/**
	 * Fetch ID token of OpenID provider.
	 *
	 * @param mixed $grant_type grant type of OAuth/OpendID provider.
	 * @param mixed $acc_token_url token endpoint of OAuth/OpendID provider.
	 * @param mixed $client_config client config parameter.
	 * @param mixed $scope scope parameter.
	 * @param mixed $ref_token refresh token parameter.
	 * @param mixed $is_ms_app ms app parameter.
	 * @param mixed $code code parameter.
	 * @return [string]
	 */
	public function moazure_get_token_res( $grant_type, $acc_token_url, $client_config, $scope, $ref_token = '', $is_ms_app = false, $code = '' ) {

		$azure_api = MOAzure_Azure_API::get_azure_api_obj();

		$response = $this->moazure_grant_handler( $grant_type, $acc_token_url, $client_config, $scope, $ref_token, $code, $is_ms_app );

		if ( ! empty( $response['id_token'] ) || ! empty( $response['access_token'] ) ) {
			return $response;
		} else {
			echo 'Invalid response received from OpenId Provider. Contact your administrator for more details.<br><br><b>Response : </b><br>' . esc_html( $response );
			exit;
		}
	}

	/**
	 * Get user information from id_token obtained from OpenID provider.
	 *
	 * @param mixed $id_token id_token obtained from OpenID provider.
	 * @return [array]
	 */
	public function get_resource_owner_from_id_token( $id_token ) {
		$id_array = explode( '.', $id_token );
		if ( isset( $id_array[1] ) ) {
			$id_body = base64_decode( str_pad( strtr( $id_array[1], '-_', '+/' ), strlen( $id_array[1] ) % 4, '=', STR_PAD_RIGHT ) ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- base64 will be required for getting contents from JWT token.
			if ( is_array( json_decode( $id_body, true ) ) ) {
				return json_decode( $id_body, true );
			}
		}
		echo 'Invalid response received.<br><b>Id_token : </b>' . esc_html( $id_token );
		exit;
	}

	/**
	 * Wrapper function to make requests
	 *
	 * @param string|mixed $url The URL to make a request against.
	 * @param mixed        $headers An associative array of headers to add to the request.
	 * @return mixed
	 */
	public function moazure_get_request( $url, $headers ) {
		$args = array(
			'headers' => $headers,
		);

		$response = wp_remote_get( esc_url_raw( $url ), $args );
		$response = ! empty( $response['body'] ) ? $this->moazure_response_handler( json_decode( $response['body'], true ) ) : array();

		return $response;
	}

	/**
	 * Function to handle response
	 *
	 * @param mixed $response response parameter.
	 * @return array
	 */
	public function moazure_response_handler( $response ) {

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {

			if ( empty( $response ) ) {
				return array(
					'status' => false,
					'data'   => array(
						'error'             => 'Unauthorized',
						'error_description' => 'Unexpected error occured',
					),
				);
			} elseif ( isset( $response['error'] ) ) {
				if ( ! empty( $response['error_description'] ) ) {
					return array(
						'status' => false,
						'data'   => array(
							'error'             => $response['error'],
							'error_description' => $response['error_description'],
						),
					);
				} elseif ( ! empty( $response['error']['code'] ) ) {
					return array(
						'status' => false,
						'data'   => array(
							'error'             => $response['error']['code'],
							'error_description' => $response['error']['message'],
						),
					);
				}
			}

			return array(
				'status' => true,
				'data'   => $response,
			);
		} else {
			return array(
				'status' => false,
				'data'   => array(
					'error'             => 'Request timeout',
					'error_description' => 'Unexpected error occurred! Please check your internet connection and try again.',
				),
			);
		}
	}
}
