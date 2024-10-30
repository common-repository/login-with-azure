<?php
/**
 * OAuth
 *
 * @package    oauth
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Main class for handling and processing the Front end data.
 */
class MOAzure {

	/**
	 * Initializing required hooks
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'moazure_azure_save_settings' ), 11 );
		register_deactivation_hook( MO_AZURE_PLUGIN_BASENAME, array( $this, 'moazure_deactivate' ) );
		add_shortcode( 'MOAZURE_LOGIN', array( $this, 'moazure_shortcode_login' ) );
		add_action( 'admin_footer', array( $this, 'moazure_feedback_request' ) );
		add_action( 'upgrader_process_complete', array( $this, 'moazure_upgrade_hook' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'moazure_support_script_hook' ) );
		add_action( 'admin_init', array( MOAzure_Admin_Utils::class, 'moazure_db_migration' ) );

		add_shortcode( 'MOAZURE_SPS_SHAREPOINT', array( MOAzure_Sharepoint_Shortcode::class, 'moazure_sps_shortcode_render' ) );
		add_shortcode( 'MOAZURE_API_POWER_BI', array( MOAzure_PowerBI_Shortcode::class, 'moazure_pbi_shortcode_render' ) );
		add_action( 'rest_api_init', array( MOAzure_WP_API::class, 'moazure_rest_endpoints' ) );
	}

	/**
	 * Handle feedback request.
	 */
	public function moazure_feedback_request() {
		moazure_display_feedback_form();
	}

	/**
	 * Handle Client support script hooks
	 */
	public function moazure_support_script_hook() {
		if ( isset( $_REQUEST['page'] ) && 'moazure_settings' === $_REQUEST['page'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
			if ( ! ( isset( $_REQUEST['tab'] ) && 'licensing' === $_REQUEST['tab'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
				wp_enqueue_script( 'moazure_support_script', plugin_dir_url( __FILE__ ) . '/admin/js/clientSupport.min.js', array(), $ver = '10.0.0', $in_footer = false );
			}
			wp_enqueue_style( 'moazure_initial_plugin_style', plugin_dir_url( __FILE__ ) . '/admin/css/moazure_initial.min.css', array(), MO_AZURE_CSS_JS_VERSION );
		}
	}

	/**
	 * Handle widget text domain.
	 */
	public function mo_login_widget_text_domain() {
		load_plugin_textdomain( 'flw', false, basename( __DIR__ ) . DIRECTORY_SEPARATOR . 'languages' );
	}

	/**
	 * Check if a variable is null.
	 *
	 * @param mixed $value variable to check if null.
	 */
	public function moazure_check_empty_or_null( $value ) {
		if ( ! isset( $value ) || empty( trim( $value ) ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Delete options after plugin deactivation.
	 */
	public function moazure_deactivate() {
		delete_option( 'host_name' );
		delete_option( 'moazure_client_new_registration' );
		delete_option( 'moazure_admin_email' );
		delete_option( 'moazure_admin_phone' );
		delete_option( 'moazure_verify_customer' );
		delete_option( 'moazure_admin_customer_key' );
		delete_option( 'moazure_admin_api_key' );
		delete_option( 'moazure_new_customer' );
		delete_option( 'moazure_customer_token' );
		delete_option( 'moazure_registration_status' );
		delete_option( 'moazure_show_mo_server_message' );
	}

	/**
	 * Save settings in DB.
	 */
	public function moazure_azure_save_settings() {

		$notice_arr = array();

		if ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'change_miniorange' && isset( $_REQUEST['moazure_goto_login_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_goto_login_form_field'] ) ), 'moazure_goto_login_form' ) ) {
			if ( current_user_can( 'administrator' ) ) {
				$this->moazure_deactivate();
				return;
			}
		}

		if ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_register_customer' && isset( $_REQUEST['moazure_register_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_register_form_field'] ) ), 'moazure_register_form' ) ) {
			if ( current_user_can( 'administrator' ) ) {
				$email            = '';
				$phone            = '';
				$password         = '';
				$confirm_password = '';
				$fname            = '';
				$lname            = '';
				$company          = '';
				if ( ( empty( $_POST['email'] ) || empty( $_POST['password'] ) || empty( $_POST['confirmPassword'] ) ) || $this->moazure_check_empty_or_null( sanitize_text_field( wp_unslash( $_POST['email'] ) ) ) || $this->moazure_check_empty_or_null( $_POST['password'] ) || $this->moazure_check_empty_or_null( $_POST['confirmPassword'] ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- As we are not storing password in the database, so we can ignore sanitization.
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'All the fields are required. Please enter valid entries.';
					update_option( 'notice_settings', $notice_arr );
					return;
				} elseif ( strlen( $_POST['password'] ) < 8 || strlen( $_POST['confirmPassword'] ) < 8 ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- As we are not storing password in the database, so we can ignore sanitization.
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'Choose a password with minimum length 8.';
					update_option( 'notice_settings', $notice_arr );
					return;
				} else {
					$email            = ! empty( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
					$phone            = ! empty( $_POST['phone'] ) ? stripslashes( sanitize_text_field( wp_unslash( $_POST['phone'] ) ) ) : '';
					$password         = stripslashes( ( $_POST['password'] ) ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- As we are not storing password in the database, so we can ignore sanitization. Preventing use of sanitization in password will lead to removal of special characters. 
					$confirm_password = stripslashes( ( $_POST['confirmPassword'] ) ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- As we are not storing password in the database, so we can ignore sanitization. Preventing use of sanitization in password will lead to removal of special characters.
					$fname            = ! empty( $_POST['fname'] ) ? sanitize_text_field( wp_unslash( $_POST['fname'] ) ) : '';
					$lname            = ! empty( $_POST['lname'] ) ? sanitize_text_field( wp_unslash( $_POST['lname'] ) ) : '';
					$company          = ! empty( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
				}

				update_option( 'moazure_admin_email', $email );
				update_option( 'moazure_admin_phone', $phone );
				update_option( 'moazure_admin_fname', $fname );
				update_option( 'moazure_admin_lname', $lname );
				update_option( 'moazure_admin_company', $company );

				if ( moazure_is_curl_installed() === 0 ) {
					return $this->moazure_show_curl_error();
				}

				if ( strcmp( $password, $confirm_password ) === 0 ) {
					$customer = new MOAzure_Client_Customer();
					$email    = get_option( 'moazure_admin_email' );
					$content  = json_decode( $customer->check_customer(), true );
					if ( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND' ) === 0 ) {
						$response = json_decode( $customer->create_customer( $password ), true );
						if ( strcasecmp( $response['status'], 'SUCCESS' ) === 0 ) {
							$this->moazure_get_current_customer( $password );
							wp_safe_redirect( admin_url( '/admin.php?page=moazure_settings' ), 301 );
							exit;
						} if ( strcasecmp( $response['status'], 'FAILED' ) === 0 && strcasecmp( $response['message'], 'Email is not enterprise email.' ) === 0 ) {
							$notice_arr['msg_type'] = 'error';
							$notice_arr['msg_desc'] = 'Please use your Enterprise email for registration.';
						} elseif ( strcasecmp( $response['status'], 'TRANSACTION_LIMIT_EXCEEDED' ) === 0 ) {
							$notice_arr['msg_type'] = 'error';
							$notice_arr['msg_desc'] = 'The registration limit of plugin has been exceeded. Please send your query to samlsupport@xecurify.com.';
						} else {
							$notice_arr['msg_type'] = 'error';
							$notice_arr['msg_desc'] = 'Failed to create customer. Try again.';
						}
						update_option( 'notice_settings', $notice_arr );
						return;
					} else {
						$this->moazure_get_current_customer( $password );
					}
				} else {
					update_option( 'message', 'Passwords do not match.' );
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'Passwords do not match.';
					update_option( 'notice_settings', $notice_arr );
					delete_option( 'moazure_verify_customer' );
					return;
				}
			}
		}

		if ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_goto_login' && isset( $_REQUEST['moazure_goto_login_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_goto_login_form_field'] ) ), 'moazure_goto_login_form' ) ) {
			delete_option( 'moazure_new_registration' );
			update_option( 'moazure_verify_customer', 'true' );
		}

		if ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_verify_customer' && isset( $_REQUEST['moazure_verify_password_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_verify_password_form_field'] ) ), 'moazure_verify_password_form' ) ) {   // register the admin to miniOrange.
			if ( current_user_can( 'administrator' ) ) {
				if ( moazure_is_curl_installed() === 0 ) {
					return $this->moazure_show_curl_error();
				}
				// validation and sanitization.
				$email    = '';
				$password = '';
				if ( $this->moazure_check_empty_or_null( sanitize_text_field( wp_unslash( $_POST['email'] ) ) ) || $this->moazure_check_empty_or_null( $_POST['password'] ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- As we are not storing password in the database, so we can ignore sanitization.
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'All the fields are required. Please enter valid entries.';
					update_option( 'notice_settings', $notice_arr );
					return;
				} else {
					$email    = ! empty( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
					$password = ! empty( $_POST['password'] ) ? stripslashes( $_POST['password'] ) : ''; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- As we are not storing password in the database, so we can ignore sanitization.
				}
				update_option( 'moazure_admin_email', $email );
				$customer     = new MOAzure_Client_Customer();
				$content      = $customer->get_customer_key( $password );
				$customer_key = json_decode( $content, true );
				if ( json_last_error() === JSON_ERROR_NONE ) {
					update_option( 'moazure_admin_customer_key', $customer_key['id'] );
					update_option( 'moazure_admin_api_key', $customer_key['apiKey'] );
					update_option( 'moazure_customer_token', $customer_key['token'] );
					if ( isset( $customer_key['phone'] ) ) {
						update_option( 'moazure_admin_phone', $customer_key['phone'] );
					}
					$notice_arr['msg_type'] = 'success';
					$notice_arr['msg_desc'] = 'Customer retrieved successfully';
					update_option( 'notice_settings', $notice_arr );
					delete_option( 'moazure_verify_customer' );
					delete_option( 'moazure_new_registration' );
				} else {
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'Invalid username or password. Please try again.';
					update_option( 'notice_settings', $notice_arr );
					return;
				}
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_add_app' && isset( $_REQUEST['moazure_add_app_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_add_app_form_field'] ) ), 'moazure_add_app_form' ) ) {
			if ( current_user_can( 'administrator' ) ) {
				$scope        = '';
				$clientid     = ! empty( $_POST['moazure_client_id'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_client_id'] ) ) : '';
				$clientsecret = ! empty( $_POST['moazure_client_secret'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_client_secret'] ) ) : '';
				if ( $this->moazure_check_empty_or_null( $clientid ) || $this->moazure_check_empty_or_null( $clientsecret ) ) {
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'Invalid Client ID or Client Secret found.';
					update_option( 'notice_settings', $notice_arr );
					return;
				} else {
					$authorize_url      = '';
					$accesstoken_url    = '';
					$callback_url       = ! empty( $_POST['moazuer_callback_url'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['moazuer_callback_url'] ) ) ) : site_url();
					$scope              = ! empty( $_POST['moazure_scope'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['moazure_scope'] ) ) ) : '';
					$appname            = ! empty( $_POST['moazure_app_name'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['moazure_app_name'] ) ) ) : 'Azure';
					$app_type           = ! empty( $_POST['moazure_app_type'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_app_type'] ) ) : 'entra-id';
					$show_on_login_page = isset( $_POST['moazure_show_on_login_page'] ) ? (int) filter_var( sanitize_text_field( wp_unslash( $_POST['moazure_show_on_login_page'] ) ), FILTER_SANITIZE_NUMBER_INT ) : 0;

					$appslist  = ! empty( get_option( 'moazure_oauth_sso_config' ) ) ? get_option( 'moazure_oauth_sso_config' ) : array();
					$is_newapp = false;

					$email_attr = '';
					$name_attr  = '';
					$newapp     = array();
					if ( ! empty( $appslist ) ) {
						foreach ( $appslist as $key => $value ) {
							if ( $appname !== $key ) {
								$is_newapp = true;
							}
							$newapp = $value;
						}
					}
					$apptype = ! empty( $newapp['apptype'] ) ? $newapp['apptype'] : '';

					$newapp['apptype']      = $app_type;
					$newapp['clientid']     = $clientid;
					$newapp['clientsecret'] = $clientsecret;
					$newapp['scope']        = $scope;
					$newapp['redirecturi']  = $callback_url;

					if ( 'azure-b2c' === $app_type ) {
						$azure_b2c_tenant = ! empty( $_POST['moazure_b2c_tenant'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['moazure_b2c_tenant'] ) ) ) : '';
						$azure_b2c_policy = ! empty( $_POST['moazure_b2c_policy'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['moazure_b2c_policy'] ) ) ) : '';

						if ( $this->moazure_check_empty_or_null( $azure_b2c_policy ) || $this->moazure_check_empty_or_null( $azure_b2c_tenant ) ) {
							$notice_arr['msg_type'] = 'error';
							$notice_arr['msg_desc'] = 'Invalid Azure B2C Policy or Tenant Name found.';
							update_option( 'notice_settings', $notice_arr );
							return;
						}

						$authorize_url   = 'https://' . $azure_b2c_tenant . '.b2clogin.com/' . $azure_b2c_tenant . '.onmicrosoft.com/' . $azure_b2c_policy . '/oauth2/v2.0/authorize';
						$accesstoken_url = 'https://' . $azure_b2c_tenant . '.b2clogin.com/' . $azure_b2c_tenant . '.onmicrosoft.com/' . $azure_b2c_policy . '/oauth2/v2.0/token';

						$newapp['tenant-name']   = $azure_b2c_tenant;
						$newapp['policy-name']   = $azure_b2c_policy;
						$newapp['username_attr'] = ( ! empty( $newapp['username_attr'] ) && $apptype === $app_type ) ? sanitize_text_field( wp_unslash( $newapp['username_attr'] ) ) : '';

						if ( isset( $newapp['tenant-id'] ) ) {
							unset( $newapp['tenant-id'] );
						}
					} else {
						$tenant_id = ! empty( $_POST['moazure_tenant_id'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['moazure_tenant_id'] ) ) ) : '';

						if ( $this->moazure_check_empty_or_null( $tenant_id ) ) {
							$notice_arr['msg_type'] = 'error';
							$notice_arr['msg_desc'] = 'Invalid Tenant ID found.';
							update_option( 'notice_settings', $notice_arr );
							return;
						}

						$authorize_url   = 'https://login.microsoftonline.com/' . $tenant_id . '/oauth2/authorize';
						$accesstoken_url = 'https://login.microsoftonline.com/' . $tenant_id . '/oauth2/token';

						$newapp['tenant-id']     = $tenant_id;
						$newapp['username_attr'] = ( ! empty( $newapp['username_attr'] ) && $apptype === $app_type ) ? sanitize_text_field( wp_unslash( $newapp['username_attr'] ) ) : 'upn';

						if ( isset( $newapp['tenant-name'] ) || isset( $newapp['policy-name'] ) ) {
							unset( $newapp['tenant-name'] );
							unset( $newapp['policy-name'] );
						}
					}

					$newapp['authorizeurl']       = $authorize_url;
					$newapp['accesstokenurl']     = $accesstoken_url;
					$newapp['show_on_login_page'] = $show_on_login_page;

					if ( $is_newapp ) {
						$appslist = array();
					}

					$appslist[ $appname ] = $newapp;
					update_option( 'moazure_oauth_sso_config', $appslist );
					update_option( 'moazure_redirect_url', $callback_url );

					$notice_arr['msg_type'] = 'success';
					$notice_arr['msg_desc'] = 'Your settings are saved successfully.';

					update_option( 'notice_settings', $notice_arr );
				}
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_attribute_mapping' && isset( $_REQUEST['moazure_attr_role_mapping_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_attr_role_mapping_form_field'] ) ), 'moazure_attr_role_mapping_form' ) ) {

			if ( current_user_can( 'administrator' ) ) {
				$appname       = isset( $_POST['moazure_app_name'] ) ? stripslashes( sanitize_text_field( wp_unslash( $_POST['moazure_app_name'] ) ) ) : '';
				$username_attr = isset( $_POST['moazure_username_attr'] ) ? stripslashes( sanitize_text_field( wp_unslash( $_POST['moazure_username_attr'] ) ) ) : '';
				$attr_option   = isset( $_POST['moazure_attr_option'] ) ? stripslashes( sanitize_text_field( wp_unslash( $_POST['moazure_attr_option'] ) ) ) : '';
				if ( empty( $appname ) ) {
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'You MUST configure an application before you map attributes.';
					update_option( 'notice_settings', $notice_arr );
					return;
				}
				$appslist = get_option( 'moazure_oauth_sso_config' );
				foreach ( $appslist as $key => $currentapp ) {
					if ( $appname === $key ) {
						$currentapp['username_attr'] = $username_attr;
						$appslist[ $key ]            = $currentapp;
						break;
					}
				}

				update_option( 'moazure_oauth_sso_config', $appslist );

				$notice_arr['msg_type'] = 'success';
				$notice_arr['msg_desc'] = 'Your settings are saved successfully.';
				update_option( 'notice_settings', $notice_arr );
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_save_role_mapping' && isset( $_REQUEST['moazure_role_mapping_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_role_mapping_form_field'] ) ), 'moazure_role_mapping_form_nonce' ) ) {
			if ( current_user_can( 'administrator' ) ) {
				$def_role = isset( $_POST['moazure_default_role'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_default_role'] ) ) : 'Subscriber';

				update_option( 'moazure_default_role', $def_role );

				$notice_arr['msg_type'] = 'success';
				$notice_arr['msg_desc'] = 'Default Role saved successfully.';
				update_option( 'notice_settings', $notice_arr );
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_pbi_sc_config' && isset( $_REQUEST['moazure_pbi_shortcode_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_pbi_shortcode_form_field'] ) ), 'moazure_pbi_shortcode_form' ) ) {
			if ( current_user_can( 'administrator' ) ) {
				$wid    = ! empty( $_POST['moazure_pbi_workspaceid'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_pbi_workspaceid'] ) ) : '';
				$rid    = ! empty( $_POST['moazure_pbi_reportid'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_pbi_reportid'] ) ) : '';
				$width  = ! empty( $_POST['moazure_pbi_wdt'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_pbi_wdt'] ) ) : '800px';
				$height = ! empty( $_POST['moazure_pbi_hgt'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_pbi_hgt'] ) ) : '800px';

				if ( empty( $wid ) || empty( $rid ) ) {
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'Please provide valid values for Workspace and Resorce IDs.';
					update_option( 'notice_settings', $notice_arr );
					return;
				}

				if ( is_numeric( $height ) ) {
					$height = $height . 'px';
				}
				if ( is_numeric( $width ) ) {
					$width = $width . 'px';
				}

				$moazure_pbi_resids_arr = ! empty( get_option( 'moazure_pbi_resourceids' ) ) ? get_option( 'moazure_pbi_resourceids' ) : array();
				$shortcodes_array       = ! empty( get_option( 'moazure_pbi_all_shortcodes' ) ) ? get_option( 'moazure_pbi_all_shortcodes' ) : array();
				$generated_shortcode    = '[MOAZURE_API_POWER_BI workspace_id="' . $wid . '" report_id="' . $rid . '" width="' . $width . '" height="' . $height . '" ]';

				if ( ! empty( $shortcodes_array ) ) {

					if ( ! in_array( $generated_shortcode, $shortcodes_array, true ) && ( ! in_array( $rid . '=' . $wid, $moazure_pbi_resids_arr, true ) ) ) {
						array_push( $shortcodes_array, $generated_shortcode );

						$notice_arr['msg_type'] = 'success';
						$notice_arr['msg_desc'] = 'Shortcode generated successfully.';
						update_option( 'notice_settings', $notice_arr );
					} elseif ( in_array( $rid . '=' . $wid, $moazure_pbi_resids_arr, true ) ) {
						$shortcode_value = '[MOAZURE_API_POWER_BI workspace_id="' . $wid . '" report_id="' . $rid . '" ';
						$index           = 0;
						foreach ( $shortcodes_array as $shortcode ) {
							if ( str_contains( $shortcode, $shortcode_value ) ) {
								$shortcodes_array[ $index ] = $generated_shortcode;
							}
							++$index;
						}
						$notice_arr['msg_type'] = 'error';
						$notice_arr['msg_desc'] = 'Shortcode already exists.';
						update_option( 'notice_settings', $notice_arr );
					}
					update_option( 'moazure_pbi_all_shortcodes', $shortcodes_array );
				} else {
					$shortcodes_array = array( $generated_shortcode );
					update_option( 'moazure_pbi_all_shortcodes', $shortcodes_array );

					$notice_arr['msg_type'] = 'success';
					$notice_arr['msg_desc'] = 'Shortcode generated successfully.';
					update_option( 'notice_settings', $notice_arr );
				}

				$res_id = $rid . '=' . $wid;

				if ( ! in_array( $res_id, $moazure_pbi_resids_arr, true ) ) {
					array_push( $moazure_pbi_resids_arr, $res_id );
					update_option( 'moazure_pbi_resourceids', $moazure_pbi_resids_arr );
				}
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_pbi_all_sc_delete' && isset( $_REQUEST['moazure_pbi_all_sc_del_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_pbi_all_sc_del_form_field'] ) ), 'moazure_pbi_all_sc_del_form' ) ) {

			if ( current_user_can( 'administrator' ) ) {
				update_option( 'moazure_pbi_all_shortcodes', '' );
				update_option( 'moazure_pbi_resourceids', '' );

				$notice_arr['msg_type'] = 'success';
				$notice_arr['msg_desc'] = 'All shortcodes deleted successfully.';
				update_option( 'notice_settings', $notice_arr );
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_pbi_sc_delete' && isset( $_REQUEST['moazure_pbi_sc_del_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_pbi_sc_del_form_field'] ) ), 'moazure_pbi_sc_del_form' ) ) {
			if ( current_user_can( 'administrator' ) ) {
				$sc_num = ! empty( $_POST['sc_num'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_num'] ) ) : '';
				if ( ! empty( $sc_num ) || $sc_num === 0 ) {
					$shortcodes_array = ! empty( get_option( 'moazure_pbi_all_shortcodes' ) ) ? get_option( 'moazure_pbi_all_shortcodes' ) : array();
					$resourceid_array = ! empty( get_option( 'moazure_pbi_resourceids' ) ) ? get_option( 'moazure_pbi_resourceids' ) : array();
					unset( $shortcodes_array[ $sc_num ] );
					unset( $resourceid_array[ $sc_num ] );

					$shortcodes_array = array_values( $shortcodes_array );
					$resourceid_array = array_values( $resourceid_array );

					update_option( 'moazure_pbi_all_shortcodes', $shortcodes_array );
					update_option( 'moazure_pbi_resourceids', $resourceid_array );

					$notice_arr['msg_type'] = 'success';
					$notice_arr['msg_desc'] = 'Selected shortcode deleted successfully.';
					update_option( 'notice_settings', $notice_arr );
				}
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_contact_us_query_option' && isset( $_REQUEST['moazure_support_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_support_form_field'] ) ), 'moazure_support_form' ) ) {
			if ( current_user_can( 'administrator' ) ) {
				if ( moazure_is_curl_installed() === 0 ) {
					return $this->moazure_show_curl_error();
				}
				// Contact Us query.
				$email       = ! empty( $_POST['moazure_contact_us_email'] ) ? sanitize_email( wp_unslash( $_POST['moazure_contact_us_email'] ) ) : '';
				$phone       = ! empty( $_POST['moazure_contact_us_phone'] ) ? stripslashes( sanitize_text_field( wp_unslash( $_POST['moazure_contact_us_phone'] ) ) ) : '';
				$query       = ! empty( $_POST['moazure_contact_us_query'] ) ? stripslashes( sanitize_text_field( wp_unslash( $_POST['moazure_contact_us_query'] ) ) ) : '';
				$send_config = isset( $_POST['moazure_send_plugin_config'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_send_plugin_config'] ) ) : '0';
				$customer    = new MOAzure_Client_Customer();
				if ( $this->moazure_check_empty_or_null( $email ) || $this->moazure_check_empty_or_null( $query ) ) {
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'Please fill up Email and Query fields to submit your query.';
					update_option( 'notice_settings', $notice_arr );
					return;
				} else {
					$mo_call_setup           = ! empty( $_POST['moazure_setup_call'] );
					$mo_call_setup_validated = false;
					$issue_description       = null;

					if ( true === $mo_call_setup ) {
						$issue             = isset( $_POST['moazure_setup_call_issue'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_setup_call_issue'] ) ) : ''; // select.
						$call_date         = isset( $_POST['moazure_setup_call_date'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_setup_call_date'] ) ) : '';
						$issue_description = isset( $_POST['moazure_issue_description'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_issue_description'] ) ) : '';
						$time_diff         = isset( $_POST['moazure_time_diff'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_time_diff'] ) ) : '';  // timezone offset.
						$call_time         = isset( $_POST['moazure_setup_call_time'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_setup_call_time'] ) ) : ''; // time input.

						if ( ! ( $this->moazure_check_empty_or_null( $email ) || $this->moazure_check_empty_or_null( $issue ) || $this->moazure_check_empty_or_null( $call_date ) || $this->moazure_check_empty_or_null( $time_diff ) || $this->moazure_check_empty_or_null( $call_time ) ) ) {
							// Please modify the $time_diff to test for the different timezones.
							// Note - $time_diff for IST is -330.
							$hrs  = floor( abs( $time_diff ) / 60 );
							$mins = fmod( abs( $time_diff ), 60 );
							if ( 0 === $mins ) {
								$mins = '00';
							}
							$sign = '+';
							if ( $time_diff > 0 ) {
								$sign = '-';
							}
							$call_time_zone = 'UTC ' . $sign . ' ' . $hrs . ':' . $mins;
							$call_date      = gmdate( 'jS F', strtotime( $call_date ) );

							// code to convert local time to IST.
							$local_hrs      = explode( ':', $call_time )[0];
							$local_mins     = explode( ':', $call_time )[1];
							$call_time_mins = ( $local_hrs * 60 ) + $local_mins;
							$ist_time       = $call_time_mins + $time_diff + 330;
							$ist_date       = $call_date;
							if ( $ist_time > 1440 ) {
								$ist_time = fmod( $ist_time, 1440 );
								$ist_date = gmdate( 'jS F', strtotime( '1 day', strtotime( $call_date ) ) );
							} elseif ( $ist_time < 0 ) {
								$ist_time = 1440 + $ist_time;
								$ist_date = gmdate( 'jS F', strtotime( '-1 day', strtotime( $call_date ) ) );
							}
							$ist_hrs = floor( $ist_time / 60 );
							$ist_hrs = sprintf( '%02d', $ist_hrs );

							$ist_mins = fmod( $ist_time, 60 );
							$ist_mins = sprintf( '%02d', $ist_mins );

							$ist_time                = $ist_hrs . ':' . $ist_mins;
							$mo_call_setup_validated = true;
						}
					}
					if ( $mo_call_setup && $mo_call_setup_validated ) {
						$submited = $customer->submit_setup_call( $email, $issue, $issue_description, $query, $call_date, $call_time_zone, $call_time, $ist_date, $ist_time, $phone, $send_config );
					} elseif ( $mo_call_setup || $mo_call_setup_validated ) {
						$submited = false;
					} else {
						$submited = $customer->submit_contact_us( $email, $phone, $query, $send_config );
					}

					if ( false === $submited ) {
						$notice_arr['msg_type'] = 'error';
						$notice_arr['msg_desc'] = 'Your query could not be submitted. Please check if all the fields are correct and try again.';
						update_option( 'notice_settings', $notice_arr );
						return;
					} else {
						$notice_arr['msg_type'] = 'success';
						$notice_arr['msg_desc'] = 'Thanks for getting in touch! We shall get back to you shortly.';
						update_option( 'notice_settings', $notice_arr );
					}
				}
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_video_demo_request_form' && isset( $_REQUEST['moazure_video_demo_request_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_video_demo_request_field'] ) ), 'moazure_video_demo_request_form' ) ) {
			if ( current_user_can( 'administrator' ) ) {
				if ( moazure_is_curl_installed() === 0 ) {
					return $this->moazure_show_curl_error();
				}

				// video demo request.
				$email     = ! empty( $_POST['moazure_video_demo_email'] ) ? sanitize_email( wp_unslash( $_POST['moazure_video_demo_email'] ) ) : '';
				$call_date = isset( $_POST['moazure_video_demo_request_date'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_video_demo_request_date'] ) ) : '';
				$time_diff = isset( $_POST['moazure_video_demo_time_diff'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_video_demo_time_diff'] ) ) : ''; // timezone offset.
				$call_time = isset( $_POST['moazure_video_demo_request_time'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_video_demo_request_time'] ) ) : ''; // time input.
				$query     = ! empty( $_POST['moazure_video_demo_request_usecase_text'] ) ? stripslashes( sanitize_text_field( wp_unslash( $_POST['moazure_video_demo_request_usecase_text'] ) ) ) : '';

				$customer = new MOAzure_Client_Customer();

				if ( $this->moazure_check_empty_or_null( $email ) || $this->moazure_check_empty_or_null( $call_date ) || $this->moazure_check_empty_or_null( $query ) || $this->moazure_check_empty_or_null( $time_diff ) || $this->moazure_check_empty_or_null( $call_time ) ) {
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'Invalid/Empty fields found. Please fill up all the required (star marked) fields to submit your query.';
					update_option( 'notice_settings', $notice_arr );
					return;
				} else {

					$moazure_video_demo_request_validated = false;
					$email                                = ! empty( $_POST['moazure_video_demo_email'] ) ? sanitize_email( wp_unslash( $_POST['moazure_video_demo_email'] ) ) : '';
					$call_date                            = isset( $_POST['moazure_video_demo_request_date'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_video_demo_request_date'] ) ) : '';
					$time_diff                            = isset( $_POST['moazure_video_demo_time_diff'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_video_demo_time_diff'] ) ) : ''; // timezone offset.
					$call_time                            = isset( $_POST['moazure_video_demo_request_time'] ) ? sanitize_text_field( wp_unslash( $_POST['moazure_video_demo_request_time'] ) ) : ''; // time input.
					$query                                = ! empty( $_POST['moazure_video_demo_email'] ) ? stripslashes( sanitize_text_field( wp_unslash( $_POST['moazure_video_demo_request_usecase_text'] ) ) ) : '';

					if ( ! ( $this->moazure_check_empty_or_null( $email ) || $this->moazure_check_empty_or_null( $query ) || $this->moazure_check_empty_or_null( $call_date ) || $this->moazure_check_empty_or_null( $time_diff ) || $this->moazure_check_empty_or_null( $call_time ) ) ) {
						// Please modify the $time_diff to test for the different timezones.
						// Note - $time_diff for IST is -330.
						$hrs  = floor( abs( $time_diff ) / 60 );
						$mins = fmod( abs( $time_diff ), 60 );
						if ( 0 === $mins ) {
							$mins = '00';
						}
							$sign = '+';
						if ( $time_diff > 0 ) {
							$sign = '-';
						}
							$call_time_zone = 'UTC ' . $sign . ' ' . $hrs . ':' . $mins;
							$call_date      = gmdate( 'jS F', strtotime( $call_date ) );

							// code to convert local time to IST.
							$local_hrs      = explode( ':', $call_time )[0];
							$local_mins     = explode( ':', $call_time )[1];
							$call_time_mins = ( $local_hrs * 60 ) + $local_mins;
							$ist_time       = $call_time_mins + $time_diff + 330;
							$ist_date       = $call_date;
						if ( $ist_time > 1440 ) {
							$ist_time = fmod( $ist_time, 1440 );
							$ist_date = gmdate( 'jS F', strtotime( '1 day', strtotime( $call_date ) ) );
						} elseif ( $ist_time < 0 ) {
							$ist_time = 1440 + $ist_time;
							$ist_date = gmdate( 'jS F', strtotime( '-1 day', strtotime( $call_date ) ) );
						}
							$ist_hrs = floor( $ist_time / 60 );
							$ist_hrs = sprintf( '%02d', $ist_hrs );

							$ist_mins = fmod( $ist_time, 60 );
							$ist_mins = sprintf( '%02d', $ist_mins );

							$ist_time                             = $ist_hrs . ':' . $ist_mins;
							$moazure_video_demo_request_validated = true;
					}

					$integrations          = MOAzure_Integrations::$all_integrations;
					$integrations_selected = '';
					foreach ( $integrations as $key => $value ) {
						if ( isset( $_POST[ $value['tag'] ] ) && sanitize_text_field( wp_unslash( $_POST[ $value['tag'] ] ) ) === 'true' ) {
							$integrations_selected .= $value['title'] . ', ';
						}
					}
					$integrations_selected = rtrim( $integrations_selected, ', ' );
					if ( empty( $integrations_selected ) || is_null( $integrations_selected ) ) {
						$integrations_selected = 'No Integrations selected';
					}

					if ( $moazure_video_demo_request_validated ) {
						$customer->moazure_send_video_demo_alert( $email, $ist_date, $query, $ist_time, $integrations_selected, 'Demo : WP All-in-One Microsoft - ' . $email, $call_time_zone, $call_time, $call_date );

						$notice_arr['msg_type'] = 'success';
						$notice_arr['msg_desc'] = 'Thanks for getting in touch! We shall get back to you shortly.';
						update_option( 'notice_settings', $notice_arr );
						return;
					} else {
						$notice_arr['msg_type'] = 'error';
						$notice_arr['msg_desc'] = 'Your query could not be submitted. Please fill up all the required fields and try again.';
						update_option( 'notice_settings', $notice_arr );
						return;
					}
				}
			}
		} elseif ( isset( $_POST ['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_forgot_password_form_option' && isset( $_REQUEST['moazure_forgotpassword_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_forgotpassword_form_field'] ) ), 'moazure_forgotpassword_form' ) ) {

			if ( current_user_can( 'administrator' ) ) {
				if ( moazure_is_curl_installed() === 0 ) {
					$notice_arr['msg_type'] = 'error';
					$notice_arr['msg_desc'] = 'ERROR: PHP cURL extension is not installed or disabled. Resend OTP failed.';
					update_option( 'notice_settings', $notice_arr );
					return;
				}

				$email = get_option( 'moazure_admin_email' );

				$customer = new MOAzure_Client_Customer();
				$content  = json_decode( $customer->moazure_forgot_password( $email ), true );

				if ( strcasecmp( $content ['status'], 'SUCCESS' ) === 0 ) {
					$notice_arr['msg_type'] = 'success';
					$notice_arr['msg_desc'] = 'Your password has been reset successfully. Please enter the new password sent to ' . $email . '.';
					update_option( 'notice_settings', $notice_arr );
				}
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_change_email' && isset( $_REQUEST['moazure_change_email_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_change_email_form_field'] ) ), 'moazure_change_email_form' ) ) {
			// Adding back button.
			update_option( 'moazure_verify_customer', '' );
			update_option( 'moazure_registration_status', '' );
			update_option( 'moazure_new_registration', 'true' );
		} elseif ( isset( $_POST['moazure_client_feedback'] ) && sanitize_text_field( wp_unslash( $_POST['moazure_client_feedback'] ) ) === 'true' && isset( $_REQUEST['moazure_feedback_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_feedback_form_field'] ) ), 'moazure_feedback_form' ) ) {

			if ( current_user_can( 'administrator' ) ) {
				$user = wp_get_current_user();

				$message = 'Plugin Deactivated:';
				if ( isset( $_POST['moazure_deactivate_reason_select'] ) ) {
					$deactivate_reason = sanitize_text_field( wp_unslash( $_POST['moazure_deactivate_reason_select'] ) );
					$message          .= ': ' . $deactivate_reason;
				}

				$deactivate_reason_message = ! empty( $_POST['query_feedback'] ) ? sanitize_text_field( wp_unslash( $_POST['query_feedback'] ) ) : false;

				if ( isset( $deactivate_reason_message ) ) {
					$message .= ': ' . $deactivate_reason_message;
				}

				if ( isset( $_POST['rate'] ) ) {
					$rate_value = ! empty( $_POST['rate'] ) ? htmlspecialchars( sanitize_text_field( wp_unslash( $_POST['rate'] ) ) ) : '';
				}

				$rating = '[Rating: ' . $rate_value . ']';

				$email = ! empty( $_POST['query_mail'] ) ? sanitize_text_field( wp_unslash( $_POST['query_mail'] ) ) : '';
				if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
					$email = get_option( 'moazure_admin_email' );
				}

				$reply_required = '';
				if ( isset( $_POST['get_reply'] ) ) {
					$reply_required = sanitize_text_field( wp_unslash( $_POST['get_reply'] ) );
				}
				if ( empty( $reply_required ) ) {
					$reply_required = 'No';
					$reply          = '[Reply :' . $reply_required . ']';
				} else {
					$reply_required = 'Yes';
					$reply          = '[Reply :' . $reply_required . ']';
				}
				$reply = $rating . ' ' . $reply;

				$skip = ! empty( $_POST['moazure_feedback_skip'] ) ? true : false;

				$feedback_reasons = new MOAzure_Client_Customer();
				$submited         = json_decode( $feedback_reasons->moazure_send_email_alert( $email, $reply, $message, 'Feedback: WordPress ' . MO_AZURE_PLUGIN_NAME, $skip ), true );
				deactivate_plugins( __DIR__ . DIRECTORY_SEPARATOR . 'mo_oauth_settings.php' );
				if ( empty( $_POST['moazure_keep_settings_intact'] ) ) {
					$this->delete_options_on_deactivation();
				}
				wp_safe_redirect( self_admin_url( 'plugins.php?deactivate=true' ) );
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_app_logout_option' && isset( $_REQUEST['moazure_app_logout_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_app_logout_form_field'] ) ), 'moazure_app_logout_form' ) ) {
			if ( isset( $_POST['moazure_app_logout'] ) && 'Logout' === $_POST['moazure_app_logout'] ) {
				$app_to_logout = ! empty( $_POST['ms_app'] ) ? sanitize_text_field( wp_unslash( $_POST['ms_app'] ) ) : '';
				$ms_entra_apps = MOAzure_Admin_Utils::moazure_get_option( 'moazure_ms_entra_apps' );

				switch ( $app_to_logout ) {
					case 'sharepoint':
						$ms_entra_apps['sps_auto']   = false;
						$ms_entra_apps['sps_manual'] = false;
						MOAzure_Admin_Utils::moazure_update_option( 'moazure_ms_entra_apps', $ms_entra_apps );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_sps_test_status' );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_auto_ref_token' );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_sps_auto_app' );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_sps_selected_site' );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_sps_selected_site_name' );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_sps_selected_drive' );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_sps_selected_drive_name' );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_sps_all_drives' );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_sps_err' );
						break;

					case 'power-bi':
						$ms_entra_apps['pbi_manual'] = false;
						MOAzure_Admin_Utils::moazure_update_option( 'moazure_ms_entra_apps', $ms_entra_apps );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_pbi_all_shortcodes' );
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_pbi_resourceids' );
						break;

					default:
						break;
				}

				$notice_arr['msg_type'] = 'success';
				$notice_arr['msg_desc'] = 'You have successfully logged out of ' . esc_html( $app_to_logout ) . ' app. All settings for the same have been deleted.';
				update_option( 'notice_settings', $notice_arr );
			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_use_entra' && isset( $_REQUEST['moazure_use_entra_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_use_entra_form_field'] ) ), 'moazure_use_entra_form' ) ) {
			$ms_manual_app = ! empty( $_POST['ms_app'] ) ? sanitize_text_field( wp_unslash( $_POST['ms_app'] ) ) : '';
			$ms_entra_apps = MOAzure_Admin_Utils::moazure_get_option( 'moazure_ms_entra_apps' );

			switch ( $ms_manual_app ) {
				case 'sharepoint':
					$ms_entra_apps['sps_auto']   = false;
					$ms_entra_apps['sps_manual'] = true;
					MOAzure_Admin_Utils::moazure_update_option( 'moazure_ms_entra_apps', $ms_entra_apps );
					break;

				case 'power-bi':
					$ms_entra_apps['pbi_manual'] = true;
					MOAzure_Admin_Utils::moazure_update_option( 'moazure_ms_entra_apps', $ms_entra_apps );
					break;

				default:
					break;

			}
		} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'moazure_remove_pbi_shortcode' && isset( $_REQUEST['moazure_remove_pbi_sc_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['moazure_remove_pbi_sc_form_field'] ) ), 'moazure_remove_pbi_sc_form' ) ) {
			$pbi_shortcode_key = isset( $_POST['pbi_shortcode_key'] ) ? intval( $_POST['pbi_shortcode_key'] ) : '';

			$pbi_shortcodes  = MOAzure_Admin_Utils::moazure_get_option( 'moazure_pbi_all_shortcodes' );
			$pbi_resourceids = MOAzure_Admin_Utils::moazure_get_option( 'moazure_pbi_resourceids' );

			if ( ! empty( $pbi_shortcode_key ) || ( 0 === $pbi_shortcode_key ) ) {
				unset( $pbi_shortcodes[ $pbi_shortcode_key - 1 ] );
				$pbi_shortcodes = array_values( $pbi_shortcodes );

				unset( $pbi_resourceids[ $pbi_shortcode_key - 1 ] );
				$pbi_resourceids = array_values( $pbi_resourceids );
			}

			MOAzure_Admin_Utils::moazure_update_option( 'moazure_pbi_all_shortcodes', $pbi_shortcodes );
			MOAzure_Admin_Utils::moazure_update_option( 'moazure_pbi_resourceids', $pbi_resourceids );

			$notice_arr['msg_type'] = 'success';
			$notice_arr['msg_desc'] = 'Shortcode deleted successfully.';
			update_option( 'notice_settings', $notice_arr );
		}
	}


	/**
	 * Get customer
	 *
	 * @param mixed $password miniOrange password.
	 */
	public function moazure_get_current_customer( $password ) {
		$notice_arr   = array();
		$customer     = new MOAzure_Client_Customer();
		$content      = $customer->get_customer_key( $password );
		$customer_key = json_decode( $content, true );
		if ( json_last_error() === JSON_ERROR_NONE ) {
			update_option( 'moazure_admin_customer_key', $customer_key['id'] );
			update_option( 'moazure_admin_api_key', $customer_key['apiKey'] );
			update_option( 'moazure_customer_token', $customer_key['token'] );
			update_option( 'password', '' );
			delete_option( 'moazure_verify_customer' );
			delete_option( 'moazure_new_registration' );
			$notice_arr['msg_type'] = 'success';
			$notice_arr['msg_desc'] = 'Customer retrieved successfully';
			update_option( 'notice_settings', $notice_arr );
		} else {
			update_option( 'moazure_verify_customer', 'true' );
			$notice_arr['msg_type'] = 'error';
			$notice_arr['msg_desc'] = 'You already have an account with miniOrange. Please enter a valid password.';
			update_option( 'notice_settings', $notice_arr );
		}
	}

	/**
	 * Show curl error
	 */
	public function moazure_show_curl_error() {
		if ( moazure_is_curl_installed() === 0 ) {
			$notice_arr['msg_type'] = 'error';
			$notice_arr['msg_desc'] = 'PHP CURL extension is not installed or disabled. Please enable it to continue.';
			update_option( 'notice_settings', $notice_arr );
			return;
		}
	}

	/**
	 * Login via Shortcode
	 */
	public function moazure_shortcode_login() {
		if ( moazure_migrate_customers() || ! moazure_is_customer_registered() ) {
			return '<div class="moazure_premium_option_text" style="width: 100%; margin: 0px auto; text-align: center;border: 1px solid;padding-top: 25px;"><p>This feature is supported only in standard and higher versions.</p>
				<p><a href="#">Click Here</a> to see our full list of Features.</p></div>';
		}
		$mowidget = new MOAzure_Widget();
		return $mowidget->moazure_login_form();
	}

	/**
	 * Export Plugin config.
	 *
	 * @param bool $share_with export client_id/client_secret.
	 */
	public function moazure_export_plugin_config( $share_with = false ) {
		$appslist = ! empty( get_option( 'moazure_oauth_sso_config' ) ) ? get_option( 'moazure_oauth_sso_config' ) : array();
		$spserr   = ! empty( get_option( 'moazure_sps_err' ) ) ? get_option( 'moazure_sps_err' ) : array();

		if ( is_array( $appslist ) ) {
			foreach ( $appslist as $key => $value ) {
				$appconfig = $value;
				break;
			}
		}

		if ( $share_with ) {
			unset( $appconfig['clientid'] );
			unset( $appconfig['clientsecret'] );
		}

		$plugin_config = array();
		$plugin_config = is_array( $appconfig ) && is_array( $spserr ) ? array_merge( $plugin_config, $appconfig, $spserr ) : array();

		return $plugin_config;
	}

	/**
	 * Delete options on deactivation.
	 */
	public function delete_options_on_deactivation() {
		$this->moazure_deactivate();
		delete_option( 'moazure_admin_email' );
		delete_option( 'password' );
		delete_option( 'moazure_admin_fname' );
		delete_option( 'moazure_admin_lname' );
		delete_option( 'moazure_admin_company' );
		delete_option( 'moazure_oauth_sso_config' );
		delete_option( 'moazure_icon_width' );
		delete_option( 'moazure_icon_height' );
		delete_option( 'moazure_icon_margin' );
		delete_option( 'moazure_icon_configure_css' );
		delete_option( 'moazure_redirect_url' );
		delete_option( 'moazure_test_attributes' );
		delete_option( 'mo_oauth_setup_wizard_app' );
		delete_option( 'moazure_notice_messages' );
		delete_option( 'moazure_attr_option' );
		delete_option( 'moazure_activation_time' );
		delete_option( 'mo_oauth_apps_list' );
	}

	/**
	 * Upgrade hook
	 *
	 * @param mixed $mo_oauth_upgrader Oauth upgrader.
	 * @param mixed $mo_oauth_parameters_received oauth parameters.
	 */
	public function moazure_upgrade_hook( $mo_oauth_upgrader, $mo_oauth_parameters_received ) {
		$moazure_activation_time = get_option( 'moazure_activation_time' );
		if ( false === $moazure_activation_time ) {
			$activate_time = new DateTime();
			update_option( 'moazure_activation_time', $activate_time );
		}
	}
}
