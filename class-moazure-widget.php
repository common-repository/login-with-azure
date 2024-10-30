<?php
/**
 * Widget
 *
 * @package    widget
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * [Add Widget Functionality]
 */
class MOAzure_Widget extends WP_Widget {

	/**
	 * Initialzie widget parameters.
	 */
	public function __construct() {
		update_option( 'host_name', 'https://login.xecurify.com' );
		add_action( 'wp_enqueue_scripts', array( $this, 'moazure_register_plugin_styles' ) );
		add_action( 'wp_logout', array( MOAzure_Admin_Utils::class, 'moazure_close_session' ) );
		add_action( 'login_form', array( $this, 'moazure_wplogin_form_button' ) );
		parent::__construct( 'moazure_widget', MO_AZURE_ADMIN_MENU, array( 'description' => __( 'All-in-One Microsoft', 'flw' ) ) );
	}

	/**
	 * Enqueue CSS for widget
	 */
	public function moazure_wplogin_form_style() {

		wp_enqueue_style( 'moazure_fontawesome', plugins_url( 'css/font-awesome.min.css', __FILE__ ), array(), '4.7.0' );
		wp_enqueue_style( 'moazure_wploginform', plugins_url( 'css/login-page.min.css', __FILE__ ), array(), MO_AZURE_CSS_JS_VERSION );
	}

	/**
	 * Display Login widget
	 */
	public function moazure_wplogin_form_button() {
		$appslist = get_option( 'moazure_oauth_sso_config' );
		if ( is_array( $appslist ) && count( $appslist ) > 0 ) {
			$this->moazure_load_login_script();
			foreach ( $appslist as $key => $app ) {

				if ( ! empty( $app['show_on_login_page'] ) && 1 === $app['show_on_login_page'] ) {

					$this->moazure_wplogin_form_style();

					echo '<br>';
					echo '<h4>Connect with :</h4><br>';
					echo '<div class="row">';

					$logo_class = $this->moazure_client_login_button_logo();

					echo '<a style="text-decoration:none" href="javascript:void(0)" onClick="moAzureLoginNew(\'' . esc_attr( $key ) . '\');">
						<div class="moazure_login_button">
							<div class="moazure_login_button_icon">
								<i class="' . esc_attr( $logo_class ) . '"></i>
							</div>
							<div class="moazure_login_button_text">
								<p>Login with ' . esc_attr( ucwords( $key ) ) . '</p>
							</div>
						</div>
					</a>
					</div><br><br>';
				}
			}
		}
	}

	/**
	 * Get logo class for the configured app.
	 */
	public function moazure_client_login_button_logo() {
		$logo_class = 'fa fa-windowslive';
		return $logo_class;
	}

	/**
	 * Echoes the widget content.
	 *
	 * @param mixed $args Display arguments including 'before_title', 'after_title',
	 *                         'before_widget', and 'after_widget'..
	 * @param mixed $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$wid_title = '';
		if ( ! empty( $instance['wid_title'] ) ) {
			$wid_title = $instance['wid_title'];
		}
		$wid_title = apply_filters( 'widget_title', $wid_title );
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $args['before_widget'] is html that needs to render on dom escaping will not render html.
		if ( ! empty( $wid_title ) ) {
			echo esc_attr( $args['before_title'] ) . esc_html( $wid_title ) . esc_attr( $args['after_title'] );
		}
		$this->moazure_login_form();
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $args['after_widget'] is html that needs to render on dom escaping will not render html.
	}

	/**
	 * MiniOrange method to override parent method to update a particular instance of a widget.
	 *
	 * @param mixed $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param mixed $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( isset( $new_instance['wid_title'] ) ) {
			$instance['wid_title'] = wp_strip_all_tags( $new_instance['wid_title'] );
		}

		return $instance;
	}

	/**
	 * Display login widget content.
	 */
	public function moazure_login_form() {
		global $post;
		$this->moazure_error_message();
		$appslist = get_option( 'moazure_oauth_sso_config' );
		if ( $appslist && count( $appslist ) > 0 ) {
			$apps_configured = true;
		}

		if ( ! is_user_logged_in() ) {

			if ( isset( $apps_configured ) && $apps_configured ) {

				$this->moazure_wplogin_form_style();
				$this->moazure_load_login_script();

				$style      = get_option( 'moazure_icon_width' ) ? 'width:' . get_option( 'moazure_icon_width' ) . ';' : '';
				$style     .= get_option( 'moazure_icon_height' ) ? 'height:' . get_option( 'moazure_icon_height' ) . ';' : '';
				$style     .= get_option( 'moazure_icon_margin' ) ? 'margin:' . get_option( 'moazure_icon_margin' ) . ';' : '';
				$custom_css = get_option( 'moazure_icon_configure_css' );
				if ( empty( $custom_css ) ) {
					echo '<style>.oauthloginbutton{background: #7272dc;height:40px;padding:8px;text-align:center;color:#fff;}</style>';
				} else {
					echo '<style>' . esc_html( $custom_css ) . '</style>';
				}

				if ( is_array( $appslist ) ) {
					foreach ( $appslist as $key => $app ) {
						$logo_class = $this->moazure_client_login_button_logo();

						echo '<a style="text-decoration:none" href="javascript:void(0)" onClick="moAzureLoginNew(\'' . esc_attr( $key ) . '\');">
							<div class="moazure_login_button">
								<div class="moazure_login_button_icon">
									<i class="' . esc_attr( $logo_class ) . '"></i>
								</div>
								<div class="moazure_login_button_text">
									<h3>Login with ' . esc_attr( ucwords( $key ) ) . '</h3>
								</div>
							</div>
						</a>';
					}
				}
			} else {
				echo '<div>No apps configured.</div>';
			}
		} else {
			$current_user       = wp_get_current_user();
			$link_with_username = __( 'Howdy, ', 'flw' ) . $current_user->display_name;
			echo '<div id="logged_in_user" class="login_wid">
			<li>' . esc_attr( $link_with_username ) . ' | <a href="' . esc_url( wp_logout_url( site_url() ) ) . '" >Logout</a></li>
		</div>';

		}
	}

	/**
	 * Load login script
	 */
	private function moazure_load_login_script() {
		?>
	<script type="text/javascript">

		function HandlePopupResult(result) {
			window.location.href = result;
		}

		function moAzureLoginNew(app_name) {
			window.location.href = '<?php echo esc_attr( site_url() ); ?>' + '/?option=moazure_oauth_redirect&app_name=' + app_name;
		}
	</script>
		<?php
	}



	/**
	 * Display Error message
	 */
	public function moazure_error_message() {
		MOAzure_Admin_Utils::moazure_start_session();
		if ( ! empty( $_SESSION['msg_class'] ) && ! empty( $_SESSION['msg'] ) ) {
			echo '<div class="' . esc_attr( $_SESSION['msg_class'] ) . '">' . esc_attr( $_SESSION['msg'] ) . '</div>';
			unset( $_SESSION['msg'] );
			unset( $_SESSION['msg_class'] );
		}
		MOAzure_Admin_Utils::moazure_write_close_session();
	}

	/**
	 * Register Plugin styles.
	 */
	public function moazure_register_plugin_styles() {
		wp_enqueue_style( 'style_login_widget', plugins_url( 'css/style_login_widget.min.css', __FILE__ ), array(), MO_AZURE_CSS_JS_VERSION );
	}
}

/**
 * Function for handling the test configuration.
 *
 * @param mixed $option_var option var parameter.
 * @return void
 */
function moazure_test_config_redirect( $option_var ) {

	if ( empty( $option_var ) ) {
		return;
	}

	MOAzure_Admin_Utils::moazure_start_session();
	$appname           = ! empty( $_REQUEST['app_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['app_name'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
	$state             = base64_encode( $appname );
	$authorization_url = '';
	if ( 'moazure_oauth_redirect' === $option_var ) {
		$app       = MOAzure_Admin_Utils::moazure_get_azure_app_config();
		$appconfig = ! empty( $app['config'] ) ? $app['config'] : array();

		if ( isset( $_REQUEST['test'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
			setcookie( 'moazure_test', true, time() + 3600, '/', '', true, true );
			$_SESSION['moazure_test'] = true;
		} else {
			setcookie( 'moazure_test', false, time() + 3600, '/', '', true, true );
			$_SESSION['moazure_test'] = false;
		}

		if ( empty( $appconfig ) ) {
			exit( 'Looks like you have not configured OAuth provider, please try to configure OAuth provider first' );
			// need to write the CSS HTML here.

		}

		$authorization_url = $appconfig['authorizeurl'];
		if ( strpos( $authorization_url, '?' ) !== false ) {
			$authorization_url = $authorization_url . '&prompt=select_account&client_id=' . $appconfig['clientid'] . '&scope=' . $appconfig['scope'] . '&redirect_uri=' . $appconfig['redirecturi'] . '&response_type=code&state=' . $state;
		} else {
			$authorization_url = $authorization_url . '?prompt=select_account&client_id=' . $appconfig['clientid'] . '&scope=' . $appconfig['scope'] . '&redirect_uri=' . $appconfig['redirecturi'] . '&response_type=code&state=' . $state;
		}
	} else {
		setcookie( $appname, true, time() + 3600, '/', '', true, true );
		$customer_tenant_id = 'common';
		$mo_client_id       = 'af7539f1-b05e-4d99-9655-47f73d0be528';

		$scope             = rawurlencode( 'openid offline_access user.read mail.read Sites.Read.All' );
		$host_url          = 'https://login.microsoftonline.com/' . $customer_tenant_id . '/oauth2/v2.0/authorize?prompt=select_account';
		$authorization_url = add_query_arg(
			array(
				'client_id'     => $mo_client_id,
				'scope'         => $scope,
				'redirect_uri'  => 'https://connect.xecurify.com',
				'response_type' => 'code',
				'state'         => home_url(),
			),
			$host_url
		);
	}
	$_SESSION['appname'] = $appname;

	MOAzure_Admin_Utils::moazure_write_close_session();

	header( 'Location: ' . $authorization_url );
	exit;
}

/**
 * Main SSO flow.
 */
function moazure_login_validate() {

	if ( ( ! empty( $_REQUEST['option'] ) ) && ( ( 'moazure_oauth_redirect' === $_REQUEST['option'] ) || ( 'moazure_sps_test_config' === $_REQUEST['option'] ) ) ) {
		moazure_test_config_redirect( $_REQUEST['option'] );
	}

	if ( ( ! empty( $_SERVER['REQUEST_URI'] ) && ( strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), '/oauthcallback' ) !== false || isset( $_REQUEST['code'] ) ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.

		MOAzure_Admin_Utils::moazure_start_session();

		// checking addiional condition for steam application.
		if ( isset( $_REQUEST['code'] ) || isset( $_REQUEST['openid_ns'] ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
			// exit from our control when user is already logged in. This it to prevent the issue with Ecwid Ecommerce plugin.
			if ( is_user_logged_in() && empty( $_COOKIE['moazure_test'] ) && empty( $_SESSION['moazure_test'] ) && empty( $_COOKIE['moazure_sps'] ) && empty( $_SESSION['moazure_sps'] ) && empty( $_SESSION['moazure_pbi'] ) && empty( $_COOKIE['moazure_pbi'] ) ) {
				return;
			}

			try {

				$currentappname  = '';
				$newapp          = array();
				$moazure_handler = new MOAzure_Handler();
				$azure_api       = MOAzure_Azure_API::get_azure_api_obj();
				$app             = MOAzure_Admin_Utils::moazure_get_azure_app_config();
				$appname         = ! empty( $app['name'] ) ? $app['name'] : '';
				$appconfig       = ! empty( $app['config'] ) ? $app['config'] : array();
				$ms_entra_apps   = MOAzure_Admin_Utils::moazure_get_option( 'moazure_ms_entra_apps' );

				if ( ( ! empty( $_SESSION['appname'] ) && 'moazure_sps' === $_SESSION['appname'] ) || ! empty( $_COOKIE['moazure_sps'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
					delete_option( 'moazure_auto_ref_token' );
					unset( $_SESSION['appname'] );
					setcookie( 'moazure_sps', '', time() - 3600, '/', '', true, true );

					MOAzure_Admin_Utils::moazure_write_close_session();

					$mo_client_config = $azure_api->get_mo_client_config();

					$ref_token = ! empty( get_option( 'moazure_auto_ref_token' ) ) ? get_option( 'moazure_auto_ref_token' ) : '';
					$code      = ! empty( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.

					$acc_token_url    = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
					$mo_client_id     = 'af7539f1-b05e-4d99-9655-47f73d0be528';
					$mo_client_secret = 'vv68Q~7m-8-qVZExLkxxy3EMvaQ1gfzEoRahZa_Y';
					$server_url       = 'https://connect.xecurify.com';

					if ( empty( $ref_token ) ) {
						$token_response = $moazure_handler->moazure_get_token_res(
							'authorization_code',
							$mo_client_config['accesstokenurl'],
							$mo_client_config,
							'',
							'',
							true,
							$code,
						);
					} else {
						$token_response = $moazure_handler->moazure_get_token_res(
							'refresh_token',
							$mo_client_config['accesstokenurl'],
							$mo_client_config,
							'',
							$ref_token,
							true,
							'',
						);
					}
					$refresh_token = '';
					$acc_token     = ! empty( $token_response['access_token'] ) ? $token_response['access_token'] : '';
					if ( ! empty( $token_response['refresh_token'] ) ) {
						$refresh_token = $token_response['refresh_token'];
						update_option( 'moazure_auto_ref_token', $token_response['refresh_token'] );
					} else {
						wp_die( 'Token not found in the Response. Please try again.', 'Token not found' );
					}
					if ( ! $acc_token ) {
						exit( 'Invalid token received.' );
					} else {
						$resource_owner = $moazure_handler->get_resource_owner_from_id_token( $acc_token );
					}

					$api_handler = new MOAzure_Azure_API();
					$res_sites   = $api_handler->moazure_sps_get_all_sites();
					$res_user    = $api_handler->moazure_sps_get_my_user();

					$user = array();
					if ( ! empty( $resource_owner ) ) {
						$user = array(
							'app_type'      => 'sps_automatic',
							'refresh_token' => $refresh_token,
							'name'          => $resource_owner['name'],
							'upn'           => $resource_owner['upn'],
						);
					}
					if ( ! empty( $res_sites['status'] ) ) {
						$response = $res_sites['data']['value'];

						$ms_entra_apps['sps_auto'] = true;
						MOAzure_Admin_Utils::moazure_update_option( 'moazure_ms_entra_apps', $ms_entra_apps );
						update_option( 'moazure_sps_test_status', 'success' );
						update_option( 'moazure_sps_test_details', $response );
						update_option( 'moazure_sps_auto_app', $user );

						echo '<div class="sps-test-container">
							<div class="sps-test-head sps-head-success">
								Connection Successful
							</div>
							<div class="sps-test-cont">
								<img width="20px" height="20px" style="margin-right:10px;" src="' . esc_url_raw( plugin_dir_url( __FILE__ ) . 'admin/partials/microsoft-apps/sharepoint/images/checked.png' ) . '" />' . ( count( $response ) === 0 ? 'No Sites Found' : ( count( $response ) . ' SharePoint sites fetched successfully' ) ) . '
							</div>
							<div class="sps-test-conn-cont">';
						foreach ( $response as $site ) {
							echo '<button class="sps-test-conn-obj" onclick="moazure_sps_site_docs(this)" data-id="' . esc_attr( $site['id'] ) . '" data-name="' . esc_attr( $site['displayName'] ) . '">' .
							esc_html( $site['displayName'] ) .
							'</button>';
						}
							echo '</div>
							<div class="sps-test-close-cont moazure-flex">
								<button id="moazure_sps_red" type="button" class="button button-large moazure_configure_btn moazure-rad" onclick="moazure_test_sps_red()">Preview Sharepoint Docs</button>
								<button id="moazure_sps_close" type="button" class="button button-large moazure_configure_btn moazure-rad" onclick="moazure_test_sps_close()">Close</button>
							</div>
						</div>';
					} elseif ( $res_sites['data']['error'] ) {
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_ms_entra_apps' );
						delete_option( 'moazure_auto_ref_token' );
						delete_option( 'moazure_sps_test_status' );
						delete_option( 'moazure_sps_test_details' );

						$err_msg  = $res_sites['data']['error'];
						$err_desc = $res_sites['data']['error_description'];

						echo '<div class="sps-test-container">
							<div class="sps-test-head sps-head-error">
								Connection Failed
							</div>
							<svg xmlns="http://www.w3.org/2000/svg" clip-rule="evenodd" fill-rule="evenodd" height="30" image-rendering="optimizeQuality" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" viewBox="0 0 254000 254000" width="30">
								<g id="图层_x0020_1">
									<path d="m127000 0c70129 0 127000 56871 127000 127000s-56871 127000-127000 127000-127000-56871-127000-127000 56871-127000 127000-127000zm-64190 172969 45969-45969-45969-45969c-2637-2638-2637-6941 0-9578l8643-8643c2637-2637 6940-2637 9578 0l45969 45969 45969-45969c2638-2637 6941-2637 9578 0l8643 8643c2637 2637 2637 6940 0 9578l-45969 45969 45969 45969c2637 2638 2637 6941 0 9578l-8643 8643c-2637 2637-6940 2637-9578 0l-45969-45969-45969 45969c-2638 2637-6941 2637-9578 0l-8643-8643c-2637-2637-2637-6940 0-9578z" fill="#ff4141"></path>
								</g>
							</svg>
							<br><br>
							<span class="" style="text-align: center;">' . esc_html( $err_msg ) . ' : ' . esc_html( $err_desc ) . '</span>
							<p style="text-align: center;">Please try again using a different Azure account or reach out to us using the Contact Us form given in the plugin.</p>
							<div class="sps-test-close-cont moazure-flex">
								<button id="moazure_sps_try" type="button" class="button button-large moazure_configure_btn moazure-rad" onclick="moazure_test_sps_try()">Try Again</button>
								<button id="moazure_sps_close" type="button" class="button button-large moazure_configure_btn moazure-rad" onclick="moazure_test_sps_close()">Close</button>
							</div>
						</div>';
					} else {
						MOAzure_Admin_Utils::moazure_delete_option( 'moazure_ms_entra_apps' );
						delete_option( 'moazure_auto_ref_token' );
						delete_option( 'moazure_sps_test_status' );
						delete_option( 'moazure_sps_test_details' );

						echo '<div class="sps-test-container">
							<div class="sps-test-head sps-head-error">
								Connection Failed
							</div>
							<svg xmlns="http://www.w3.org/2000/svg" clip-rule="evenodd" fill-rule="evenodd" height="30" image-rendering="optimizeQuality" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" viewBox="0 0 254000 254000" width="30">
								<g id="图层_x0020_1">
									<path d="m127000 0c70129 0 127000 56871 127000 127000s-56871 127000-127000 127000-127000-56871-127000-127000 56871-127000 127000-127000zm-64190 172969 45969-45969-45969-45969c-2637-2638-2637-6941 0-9578l8643-8643c2637-2637 6940-2637 9578 0l45969 45969 45969-45969c2638-2637 6941-2637 9578 0l8643 8643c2637 2637 2637 6940 0 9578l-45969 45969 45969 45969c2637 2638 2637 6941 0 9578l-8643 8643c-2637 2637-6940 2637-9578 0l-45969-45969-45969 45969c-2638 2637-6941 2637-9578 0l-8643-8643c-2637-2637-2637-6940 0-9578z" fill="#ff4141"></path>
								</g>
							</svg>
							<br><br>
							<span class="" style="text-align: center; ">An error has occurred while fetching the sites. Please try again or reach out to us.</span>
							<div class="sps-test-close-cont moazure-flex">
								<button id="moazure_sps_try" type="button" class="button button-large moazure_configure_btn moazure-rad" onclick="moazure_test_sps_try()">Try Again</button>
								<button id="moazure_sps_close" type="button" class="button button-large moazure_configure_btn moazure-rad" onclick="moazure_test_sps_close()">Close</button>
							</div>
						</div>';
					}

					?>
					<style>
						.sps-test-container {
							display: flex;
							align-items: center;
							flex-direction: column;
							border: 1px solid #eee;
							padding: 10px;
							border-radius: 2px;
						}

						.sps-test-head {
							width: 90%;
							padding: 2%;
							margin-bottom: 5%;
							text-align: center;
							font-size: 18pt;
							border-radius: 2px;
						}

						.sps-head-success {
							color: #3c763d;
							background-color: #dff0d8;
							border: 1px solid #AEDB9A;
						}

						.sps-head-error {
							color: #ff5c5c;
							background-color: #ffbcbc3b;
							border: 1px solid #ff000036;
						}

						.sps-test-cont {
							display: flex;
							justify-content: flex-start;
							align-items: center;
							margin: 10px;
							width: 90%;
						}

						.sps-test-conn-cont {
							width:90%;
							display:flex;
							justify-content:flex-start;
							align-items:flex-start;
							align-content: flex-start;
							flex-wrap:wrap;
							height:fit-content;
							overflow-y:scroll;
						}

						.sps-test-conn-cont::-webkit-scrollbar {
							display: none;
						}

						.sps-test-conn-obj {
							padding:10px;
							background-color:#eee;
							font-size:14px;
							margin:10px;
							border-radius:2px;
							display: flex;
							justify-content: center;
							align-items: center;
							border: 1px solid grey !important;
						}

						.sps-test-close-cont {
							margin: 5% 0;
						}

						.moazure_configure_btn {
							background-color: #0073c6ec !important;
							border: 0.1rem solid  #0072C6 !important;
							color:#ffffff !important;
							font-weight: 500 !important;
							font-size: 1rem !important;
							padding: 0.5rem 1rem;
							border-radius: 2px;
							cursor: pointer;
						}

						.moazure_configure_btn:hover {
							background-color: #0072C6 !important;
						}
					</style>
					<script>
						function moazure_test_sps_try() {
							window.location.href = '<?php echo esc_attr( admin_url() ); ?>' + 'admin.php/?option=moazure_sps_test_config&app_name=moazure_sps';
						}
						function moazure_sps_site_docs(divEle) {
							let dataId = divEle.getAttribute('data-id');
							let dataName = divEle.getAttribute('data-name');
							window.opener.sps_load_site_docs(dataId, dataName);
							window.close();
						}
						function moazure_test_sps_red() {
							closeRef = false;
							window.opener.moazure_sharepoint_doc_redirect();
							self.close();
						}
						function moazure_test_sps_close() {
							window.opener.reload_after_close();
							self.close();
						}
					</script>
					<?php
					exit();
				} elseif ( isset( $_REQUEST['state'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
					if ( ! $appconfig ) {
						exit( 'Application not configured.' );
					}

					$currentappname = ! empty( $_SESSION['appname'] ) ? sanitize_text_field( $_SESSION['appname'] ) : sanitize_text_field( wp_unslash( base64_decode( $_REQUEST['state'] ) ) ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended, WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- Base64 encoding will be required to fetch current app name from state. Sanitizing late for $_REQUEST['state'] as we need to sanitize after decode.
					if ( empty( $currentappname ) ) {
						return;
					}
					$username_attr = '';

					if ( $currentappname === $appname ) {
						if ( isset( $appconfig['username_attr'] ) ) {
							$username_attr = $appconfig['username_attr'];
						} elseif ( isset( $appconfig['email_attr'] ) ) {
							$username_attr = $appconfig['email_attr'];
						}
					}

					$appconfig['username_attr'] = ! empty( $username_attr ) ? $username_attr : '';
					$newapp[ $appname ]         = $appconfig;
					MOAzure_Admin_Utils::moazure_update_option( 'moazure_oauth_sso_config', $newapp );

					// Openid flow.
					$code = ! empty( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
					if ( isset( $_REQUEST['id_token'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
						$id_token = sanitize_text_field( wp_unslash( $_REQUEST['id_token'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
					} else {
						$token_response = $moazure_handler->moazure_get_token_res(
							'authorization_code',
							$appconfig['accesstokenurl'],
							$appconfig,
							$appconfig['scope'],
							'',
							false,
							$code,
						);

						$id_token = isset( $token_response['id_token'] ) ? $token_response['id_token'] : $token_response['access_token'];
					}

					if ( ! $id_token ) {
						exit( 'Invalid token received.' );
					} else {
						$resource_owner = $moazure_handler->get_resource_owner_from_id_token( $id_token );
					}

					$username = '';
					MOAzure_Admin_Utils::moazure_update_option( 'moazure_test_attributes', $resource_owner );

					// Test Configuration.
					if ( ! empty( $_COOKIE['moazure_test'] ) || ! empty( $_SESSION['moazure_test'] ) ) {
						setcookie( 'moazure_test', false, time() + 3600, '/', '', true, true );
						$_SESSION['moazure_test'] = false;

						echo '<div class="sso-test-container">
							<div class="sso-test-head sso-head-success">
								Connection Successful
							</div>
							<div class="sso-test-conn-cont">
								<table class="sso-test-table">
									<tr class="sso-test-table-tr">
										<th class="sso-test-table-th">' . esc_attr__( 'Attribute Name', 'all-in-one-microsoft' ) . '</th>
										<th class="sso-test-table-th">' . esc_attr__( 'Attribute Value', 'all-in-one-microsoft' ) . '</th>
									</tr>';
									moazure_client_test_attrmapping_config( '', $resource_owner, 'sso-test-table-' );
								echo '</table>
							</div>
							<div class="sso-test-close-cont moazure-flex">
								<button id="moazure_un_configure" type="button" class="button button-large moazure_configure_btn moazure-rad" onclick="moazure_un_config_red()">Configure Username</button>
								<button id="moazure_sso_close" type="button" class="button button-large moazure_configure_btn moazure-rad" onclick="moazure_sso_close()">Close</button>
							</div>
							<span class="moazure_upgrade_warning moazure-rad">
								<strong>*NOTE: </strong>We have added an SSO button on your WP Login Page, please open it in Incognito Window and Try performing SSO.
							</span>
						</div>';

						?>
						
						<style>
							.sso-test-container {
								display: flex;
								align-items: center;
								flex-direction: column;
								border: 1px solid #eee;
								padding: 10px;
								border-radius: 2px;
							}

							.sso-test-head {
								width: 90%;
								padding: 2%;
								margin-bottom: 5%;
								text-align: center;
								font-size: 18pt;
								border-radius: 2px;
							}

							.sso-head-success {
								color: #3c763d;
								background-color: #dff0d8;
								border: 1px solid #AEDB9A;
							}

							.sso-head-error {
								color: #ff5c5c;
								background-color: #ffbcbc3b;
								border: 1px solid #ff000036;
							}

							.sso-test-conn-cont {
								height:400;
								overflow-y:scroll;
							}

							.sso-test-table {
								color: #012970;
							}

							.sso-test-table-tr:nth-child(odd) {
								background-color: #0073c617;
							}

							.sso-test-table-th {
								background-color: #0073c644;
								text-align: center;
								padding: 8px;
							}

							.sso-test-table-td {
								padding: 8px;
								word-break: break-all;
							}

							.sso-test-close-cont {
								margin: 5% 0;
							}

							.moazure_configure_btn {
								background-color: #0073c6ec !important;
								border: 0.1rem solid  #0072C6 !important;
								color:#ffffff !important;
								font-weight: 500 !important;
								font-size: 1rem !important;
								padding: 0.5rem 1rem;
								border-radius: 2px;
								cursor: pointer;
							}

							.moazure_configure_btn:hover {
								background-color: #0072C6 !important;
							}

							.moazure_upgrade_warning {
								color: rgb(30, 30, 30);
								background-color: aliceblue;
								border-color: #ebccd1;
								border-radius:2px;
								padding: 0.75rem;
							}

							.moazure-rad {
								border-radius: 2px;
							}
						</style>
						
						<script>
							function moazure_un_config_red() {
								closeRef = false;
								window.opener.moazure_attr_role_redirect();
								self.close();
							}
							function moazure_sso_close() {
								window.opener.sso_reload_after_close();
								self.close();
							}
						</script>

						<?php
						exit();
					}

					MOAzure_Admin_Utils::moazure_update_option( 'moazure_auth_code', $code );

					if ( ! empty( $username_attr ) ) {
						$username = moazure_client_get_nested_attribute( $resource_owner, $username_attr );
					}

					if ( empty( $username ) || '' === $username ) {
						exit( 'Username not received. Check your <b>Attribute Mapping</b> configuration.' );
					}

					setcookie( 'sso_user', true, time() + 3600, '/', '', true, true );
					$_SESSION['sso_user'] = true;

					if ( ! empty( $ms_entra_apps['pbi_manual'] ) && isset( $token_response['refresh_token'] ) ) {
						$_SESSION['ref_token'] = $token_response['refresh_token'];
					}

					MOAzure_Admin_Utils::moazure_write_close_session();

					$user = get_user_by( 'login', $username );

					if ( $user ) {
						$user_id = $user->ID;
					} else {
						$user_id = 0;
						if ( moazure_migrate_customers() ) {
							$user = moazure_looped_user( $username );
						} else {
							$user = moazure_handle_user_registration( $username );
						}
					}
					if ( $user ) {
						wp_set_current_user( $user->ID );
						wp_set_auth_cookie( $user->ID );

						$redirect_to = get_option( 'moazure_redirect_url' );
						if ( has_action( 'mo_hack_login_session_redirect' ) ) {
							$token    = moazure_gen_rand_str();
							$password = moazure_gen_rand_str();
							$config   = array(
								'user_id'       => $user->ID,
								'user_password' => $password,
							);
							set_transient( $token, $config );
							do_action( 'mo_hack_login_session_redirect', $user, $password, $token, $redirect_to );
						}
						$user = get_user_by( 'ID', $user->ID );
						do_action( 'wp_login', $user->user_login, $user );

						if ( false === $redirect_to ) {
							$redirect_to = home_url();
						}

						wp_safe_redirect( $redirect_to );
						exit;
					}
				}
			} catch ( Exception $e ) {

				// Failed to get the access token or user details.

				exit( esc_attr( $e->getMessage() ) );

			}
		} else { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
			if ( isset( $_REQUEST['error_description'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
				exit( esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['error_description'] ) ) ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
			} elseif ( isset( $_REQUEST['error'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
				exit( esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['error'] ) ) ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
			}
			exit( 'Invalid response' );
		}

		MOAzure_Admin_Utils::moazure_write_close_session();
	}
}

/**
 * Handle user registration.
 *
 * @param mixed $username username for the current user.
 */
function moazure_handle_user_registration( $username ) {
	$random_password = wp_generate_password( 10, false );
	$default_role    = ! empty( get_option( 'moazure_default_role' ) ) ? get_option( 'moazure_default_role' ) : get_option( 'default_role' );

	if ( strlen( $username ) > 60 ) {
		wp_die( 'You are not allowed to login. Please contact your administrator' );
	}

	if ( preg_match( '/[+,\/~!#$%^&*():={}|;">?\/\\\\\/\\\\\']/', $username ) ) {
		wp_die( 'You are not allowed to login. Please contact your administrator' );
	}

	$user_id = wp_create_user( $username, $random_password );
	$user    = get_user_by( 'login', $username );
	wp_update_user(
		array(
			'ID'   => $user_id,
			'role' => $default_role,
		)
	);
	return $user;
}

/**
 * Handler User registration.
 *
 * @param mixed $temp_var temp var.
 */
function moazure_looped_user( $temp_var ) {
	return moazure_looped_redirect( $temp_var );
}

/**
 * Display attribute mapping in Test Configuration.
 *
 * @param mixed  $nestedprefix nested prefix.
 * @param mixed  $resource_owner_details resource owner details of the current user.
 * @param string $tr_class_prefix prefix for tr class.
 */
function moazure_client_test_attrmapping_config( $nestedprefix, $resource_owner_details, $tr_class_prefix = '' ) {

	$username_value = '';
	foreach ( $resource_owner_details as $key => $resource ) {
		if ( is_array( $resource ) || is_object( $resource ) ) {
			if ( ! empty( $nestedprefix ) ) {
				$nestedprefix .= '.';
			}
			moazure_client_test_attrmapping_config( $nestedprefix . $key, $resource, $tr_class_prefix );
			$nestedprefix = rtrim( $nestedprefix, '.' );
		} else {
			echo '<tr class="' . esc_attr( $tr_class_prefix ) . 'tr"><td class="moazure-rad ' . esc_attr( $tr_class_prefix ) . 'td">';
			if ( ! empty( $nestedprefix ) ) {
				$key = $nestedprefix . '.' . $key;
			}
			echo esc_html( $key ) . '</td><td class="moazure-rad ' . esc_attr( $tr_class_prefix ) . 'td">' . esc_html( $resource ) . '</td></tr>';

			$appslist       = get_option( 'moazure_oauth_sso_config' );
			$currentapp     = null;
			$currentappname = null;
			if ( is_array( $appslist ) ) {
				foreach ( $appslist as $currentappname => $currentapp ) {
					break;
				}
			}
			if ( strpos( $username_value, 'username' ) === false ) {
				if ( strpos( $key, 'username' ) !== false ) {
					$username_value = $key;
				} elseif ( strpos( $key, 'email' ) !== false && filter_var( $resource, FILTER_VALIDATE_EMAIL ) ) {
					$username_value = $key;
				}
			}
		}
	}

	if ( ! isset( $currentapp['username_attr'] ) && $username_value ) {
		$currentapp['username_attr'] = $username_value;
		$appslist[ $currentappname ] = $currentapp;
		update_option( 'moazure_oauth_sso_config', $appslist );
	}
}

/**
 * Get nested attribute.
 *
 * @param mixed $resource resource owner info.
 * @param mixed $key attriubte key.
 */
function moazure_client_get_nested_attribute( $resource, $key ) {
	if ( '' === $key ) {
		return '';
	}

	$keys = explode( '.', $key );
	if ( count( $keys ) > 1 ) {
		$current_key = $keys[0];
		if ( isset( $resource[ $current_key ] ) ) {
			return moazure_client_get_nested_attribute( $resource[ $current_key ], str_replace( $current_key . '.', '', $key ) );
		}
	} else {
		$current_key = $keys[0];
		if ( isset( $resource[ $current_key ] ) ) {
			return $resource[ $current_key ];
		}
	}
}

/**
 * Handle user registration.
 *
 * @param mixed $ejhi temp var.
 */
function moazure_looped_redirect( $ejhi ) {
	$user = moazure_handle_user_registration( $ejhi );
	return $user;
}

/**
 * Get prefix.
 *
 * @param mixed $type type of variable.
 * @return array
 */
function moazure_get_proper_prefix( $type ) {
	$letter = substr( $type, 0, 1 );
	$vowels = array( 'a', 'e', 'i', 'o', 'u' );
	return ( is_array( $vowels ) && in_array( $letter, $vowels, true ) ) ? ' an ' . $type : ' a ' . $type;
}

/**
 * Register widget.
 */
function moazure_register_widget() {
	register_widget( 'moazure_widget' );
}

/**
 * Check if DOING_AJAX is defined.
 */
function moazure_is_ajax_request() {
	return defined( 'DOING_AJAX' ) && DOING_AJAX;
}

/**
 * Valid html
 *
 * Helper function for escaping.
 *
 * @param array $args HTML to add to valid args.
 *
 * @return array valid html.
 **/
function moazure_get_valid_html( $args = array() ) {
	$retval = array(
		'strong' => array(),
		'em'     => array(),
		'b'      => array(),
		'i'      => array(),
		'a'      => array(
			'href'   => array(),
			'target' => array(),
		),
	);
	if ( ! empty( $args ) ) {
		return array_merge( $args, $retval );
	}
	return $retval;
}

/**
 * Check for REST API call.
 *
 * @return [type]
 */
function moazure_is_rest_api_call() {
	return ! empty( $_SERVER['REQUEST_URI'] ) ? strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), '/wp-json' ) === false : '';
}

/**
 * Generate random string.
 *
 * @param int $length length of the string to be generated.
 * @return string
 */
function moazure_gen_rand_str( $length = 10 ) {
	$characters        = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$characters_length = strlen( $characters );
	$random_string     = '';
	for ( $i = 0; $i < $length; $i++ ) {
		$random_string .= $characters[ wp_rand( 0, $characters_length - 1 ) ];
	}
	return $random_string;
}

	add_action( 'widgets_init', 'moazure_register_widget' );
	add_action( 'init', 'moazure_login_validate' );
?>
