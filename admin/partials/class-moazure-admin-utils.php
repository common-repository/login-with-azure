<?php
/**
 * Admin Utils
 *
 * @package    partials
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * [Description Handle Admin utils]
 */
class MOAzure_Admin_Utils {

	/**
	 * Function to get azure app config.
	 *
	 * @return array
	 */
	public static function moazure_get_azure_app_config() {
		$appconfig = array();
		$appname   = '';
		$apps      = self::moazure_get_option( 'moazure_oauth_sso_config' );
		if ( is_array( $apps ) && ! empty( $apps ) ) {
			foreach ( $apps as $key => $value ) {
				$appname   = $key;
				$appconfig = $value;
				break;
			}
		}

		$app_det = array(
			'name'   => $appname,
			'config' => $appconfig,
		);

		return $app_det;
	}

	/**
	 * Wrapper function for update option.
	 *
	 * @param string $option_name option name parameter.
	 * @param mixed  $option_value option value parameter.
	 * @return void
	 */
	public static function moazure_update_option( $option_name, $option_value ) {
		update_option( $option_name, $option_value );
	}

	/**
	 * Wrapper function for get option.
	 *
	 * @param string $option_name option name parameter.
	 * @return mixed
	 */
	public static function moazure_get_option( $option_name ) {
		$option_value = get_option( $option_name );
		return $option_value;
	}

	/**
	 * Wrapper function for delete option
	 *
	 * @param string $option_name option name parameter.
	 * @return void
	 */
	public static function moazure_delete_option( $option_name ) {
		delete_option( $option_name );
	}

	/**
	 * Wrapper function for is user logged in check.
	 *
	 * @return boolean
	 */
	public static function moazure_is_user_logged_in() {
		$is_login = is_user_logged_in() ? true : false;
		return $is_login;
	}

	/**
	 * Check Curl extension
	 */
	public static function curl_extension_check() {
		if ( is_array( get_loaded_extensions() ) && ! in_array( 'curl', get_loaded_extensions(), true ) ) {
			echo '<p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL extension</a> is not installed or disabled. Please install/enable it before you proceed.)</p>';
		}
	}

	/**
	 * Display error message.
	 *
	 * @param string $message error description.
	 * @return void
	 */
	public static function moazure_error_message( $message ) {
		$class = 'error';
		echo "<div style='display:flex; margin: 0px 0px 10px 0px; background-color: #ffbcbc3b' class='" . esc_attr( $class ) . "'><div><img style='margin-bottom:-12px' src='" . esc_url( plugin_dir_url( __FILE__ ) ) . "../../images/mo_oauth_error.png' ></div><div><p> &nbsp;&nbsp;" . esc_attr( $message ) . '</p></div></div>';

		delete_option( 'notice_settings' );
	}

	/**
	 * Dispaly success message.
	 *
	 * @param string $message success description.
	 * @return void
	 */
	public static function moazure_success_message( $message ) {
		$class = 'updated';
		echo "<div style='display:flex; margin: 0px 0px 10px 0px; background-color: #00ff042e;' class='" . esc_attr( $class ) . "'><div><img style='margin-bottom:-12px' src='" . esc_url( plugin_dir_url( __FILE__ ) ) . "../../images/mo_oauth_success.png' ></div><div><p> &nbsp;&nbsp;" . esc_attr( $message ) . '</p></div></div>';

		delete_option( 'notice_settings' );
	}

	/**
	 * Start user session.
	 *
	 * @return void
	 */
	public static function moazure_start_session() {

		if ( session_id() === '' || ! isset( $_SESSION ) ) {
			session_start();
		}
	}

	/**
	 * Destroy user session.
	 *
	 * @return void
	 */
	public static function moazure_close_session() {
		if ( ! session_id() ) {
			self::moazure_start_session();
		}
		session_destroy();
	}

	/**
	 * Writes session & then close.
	 *
	 * @return void
	 */
	public static function moazure_write_close_session() {
		if ( ! session_id() ) {
			self::moazure_start_session();
		}
		session_write_close();
	}

	/**
	 * Migrate db options from old plugin to the current one.
	 */
	public static function moazure_db_migration() {
		$moazure_prev_oauth_config = get_option( 'mo_oauth_apps_list' );
		$curr_config               = array();

		$is_config_migrated = ! empty( get_option( 'moazure_oauth_sso_config' ) ) ? true : false;

		if ( ! empty( $moazure_prev_oauth_config ) && is_array( $moazure_prev_oauth_config ) && ! $is_config_migrated ) {
			foreach ( $moazure_prev_oauth_config as $key => $value ) {
				// For backward compatibility.
				if ( ! isset( $value['policy'] ) && is_array( $value ) ) {
					$save_configuration['clientsecret']  = isset( $value['clientsecret'] ) ? sanitize_text_field( wp_unslash( $value['clientsecret'] ) ) : '';
					$save_configuration['clientid']      = isset( $value['clientid'] ) ? sanitize_text_field( wp_unslash( $value['clientid'] ) ) : '';
					$save_configuration['tenant-id']     = isset( $value['tenantid'] ) ? sanitize_text_field( wp_unslash( $value['tenantid'] ) ) : '';
					$save_configuration['scope']         = isset( $value['scope'] ) ? sanitize_text_field( wp_unslash( $value['scope'] ) ) : '';
					$save_configuration['username_attr'] = ! empty( $value['username_attr'] ) ? $value['username_attr'] : 'upn';
					$save_configuration['apptype']       = 'entra-id';
				} else {
					$save_configuration['tenant-name']   = isset( $value['tenant'] ) ? sanitize_text_field( wp_unslash( $value['tenant'] ) ) : '';
					$save_configuration['policy-name']   = isset( $value['policy'] ) ? sanitize_text_field( wp_unslash( $value['policy'] ) ) : '';
					$save_configuration['clientsecret']  = isset( $value['clientsecret'] ) ? sanitize_text_field( wp_unslash( $value['clientsecret'] ) ) : '';
					$save_configuration['clientid']      = isset( $value['clientid'] ) ? sanitize_text_field( wp_unslash( $value['clientid'] ) ) : '';
					$save_configuration['scope']         = isset( $value['scope'] ) ? sanitize_text_field( wp_unslash( $value['scope'] ) ) : '';
					$save_configuration['username_attr'] = ! empty( $value['username_attr'] ) ? $value['username_attr'] : '';
					$save_configuration['apptype']       = 'azure-b2c';
				}
				if ( empty( $save_configuration['tenant-id'] ) ) {
					// Get tenant ID from Authorize URL of Accesstoken URL.
					if ( ! empty( $value['authorizeurl'] ) ) {
						$tenantid_arr                    = explode( '/', $value['authorizeurl'] );
						$save_configuration['tenant-id'] = $tenantid_arr[3];
					} elseif ( ! empty( $value['accesstokenurl'] ) ) {
						$tenantid_arr                    = explode( '/', $value['accesstokenurl'] );
						$save_configuration['tenant-id'] = $tenantid_arr[3];
					}
				}
				$save_configuration['redirecturi']        = ! empty( $value['redirecturi'] ) ? sanitize_text_field( wp_unslash( $value['redirecturi'] ) ) : '';
				$save_configuration['authorizeurl']       = ! empty( $value['authorizeurl'] ) ? sanitize_text_field( wp_unslash( $value['authorizeurl'] ) ) : '';
				$save_configuration['accesstokenurl']     = ! empty( $value['accesstokenurl'] ) ? sanitize_text_field( wp_unslash( $value['accesstokenurl'] ) ) : '';
				$save_configuration['show_on_login_page'] = ! empty( $value['show_on_login_page'] ) ? $value['show_on_login_page'] : 1;
				$curr_config[ $key ]                      = $save_configuration;

				update_option( 'moazure_oauth_sso_config', $curr_config );
				update_option( 'moazure_test_attributes', get_option( 'mo_oauth_attr_name_list' ) );

				break;
			}
		}

		$moazure_prev_sps_config = get_option( 'moazure_sps_test_status' );
		$ms_entra_apps           = get_option( 'moazure_ms_entra_apps' );

		$is_sps_config = ( ! empty( $ms_entra_apps['sps_auto'] ) || ! empty( $ms_entra_apps['sps_manual'] ) ) ? true : false;

		if ( ! empty( $moazure_prev_sps_config ) && ! $is_sps_config ) {
			$ms_entra_apps['sps_auto'] = true;
			update_option( 'moazure_ms_entra_apps', $ms_entra_apps );

			$selected_site = self::moazure_get_option( 'moazure_sps_selected_site' );

			$moazure_handler = MOAzure_Azure_API::get_azure_api_obj();
			$drives          = $moazure_handler->moazure_sps_get_all_drives( $selected_site );

			self::moazure_update_option( 'moazure_sps_all_drives', $drives['data']['value'] );
		}
	}
}
