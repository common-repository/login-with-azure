<?php
/**
 * Admin Menu
 *
 * @package    admin-menu
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Adding required files.
 */

require 'class-moazure-admin-utils.php';
require 'account' . DIRECTORY_SEPARATOR . 'class-moazure-admin-account.php';
require 'apps' . DIRECTORY_SEPARATOR . 'class-moazure-apps.php';
require 'support' . DIRECTORY_SEPARATOR . 'class-moazure-support.php';
require 'guides' . DIRECTORY_SEPARATOR . 'class-moazure-attribute-mapping.php';
require 'demo' . DIRECTORY_SEPARATOR . 'class-moazure-demo.php';
require 'troubleshoot' . DIRECTORY_SEPARATOR . 'class-moazure-troubleshoot.php';
require 'integrations' . DIRECTORY_SEPARATOR . 'class-moazure-integrations.php';
require 'integrations' . DIRECTORY_SEPARATOR . 'class-moazure-third-party-integrations.php';
require 'microsoft-apps' . DIRECTORY_SEPARATOR . 'class-moazure-ms-apps.php';
require 'microsoft-apps' . DIRECTORY_SEPARATOR . 'class-moazure-azure-api.php';
require 'microsoft-apps' . DIRECTORY_SEPARATOR . 'class-moazure-wp-api.php';

/**
 * Initialize CSS files
 *
 * @param mixed $hook WordPress hook.
 */
function moazure_plugin_settings_style( $hook ) {
	if ( 'toplevel_page_moazure_settings' !== $hook ) {
		return;
	}
	wp_enqueue_style( 'moazure_admin_style', plugin_dir_url( __DIR__ ) . 'css/admin.min.css', array(), MO_AZURE_CSS_JS_VERSION );
	wp_enqueue_style( 'moazure_admin_settings_style', plugin_dir_url( __DIR__ ) . 'css/style_settings.min.css', array(), MO_AZURE_CSS_JS_VERSION );
	wp_enqueue_style( 'moazure_admin_sharepoint_style', plugin_dir_url( __DIR__ ) . 'css/moazure-sps-style.min.css', array(), MO_AZURE_CSS_JS_VERSION );
	wp_enqueue_style( 'moazure_admin_settings_font_awesome', plugin_dir_url( __DIR__ ) . 'css/font-awesome.min.css', array(), '4.7.0' );
	wp_enqueue_style( 'moazure_admin_settings_phone_style', plugin_dir_url( __DIR__ ) . 'css/phone.min.css', array(), '0.0.2' );
	wp_enqueue_style( 'moazure_admin_settings_inteltelinput_style', plugin_dir_url( __DIR__ ) . 'css/intlTelInput.min.css', array(), '17.0.19' );
	wp_enqueue_style( 'moazure_admin_settings_jquery_ui_style', plugin_dir_url( __DIR__ ) . 'css/jquery-ui.min.css', array(), '1.12.1' );
	wp_enqueue_style( 'moazure_admin_settings_overall_font_style', plugin_dir_url( __DIR__ ) . 'css/fontNunito.min.css', array(), '1.0.0' );
}

/**
 * Initialize JS files
 *
 * @param mixed $hook WordPress hook.
 */
function moazure_plugin_settings_script( $hook ) {
	if ( 'toplevel_page_moazure_settings' !== $hook ) {
		return;
	}
	wp_enqueue_script( 'moazure_admin_script', plugin_dir_url( __DIR__ ) . 'js/admin.min.js', array(), $ver = MO_AZURE_CSS_JS_VERSION, false );
	wp_enqueue_script( 'moazure_admin_settings_script', plugin_dir_url( __DIR__ ) . 'js/settings.min.js', array(), $ver = MO_AZURE_CSS_JS_VERSION, false );
	wp_enqueue_script( 'moazure_admin_settings_phone_script', plugin_dir_url( __DIR__ ) . 'js/phone.min.js', array(), $ver = '0.8.3', false );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'moazure_admin_settings_jquery-ui3', includes_url() . 'js/jquery/ui/datepicker.min.js', array(), $ver = false, false );
	wp_enqueue_script( 'moazure_admin_settings_inteltelinput', plugin_dir_url( __DIR__ ) . 'js/intlTelInput.min.js', array(), $ver = '13.0.4', false );

	wp_localize_script(
		'moazure_admin_settings_script',
		'moazure_settings_data',
		array(
			'admin_url' => admin_url(),
		)
	);
}

/**
 * Display Main Menu
 */
function moazure_main_menu() {
	$today = gmdate( 'Y-m-d H:i:s' );
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
	$currenttab = ! empty( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'moazure_config';
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
	$currentapp = ! empty( $_GET['app'] ) ? sanitize_text_field( wp_unslash( $_GET['app'] ) ) : 'entra-id';

	MOAzure_Admin_Utils::curl_extension_check();
	MOAzure_Admin_Menu::show_menu( $currenttab, $currentapp );
	MOAzure_Admin_Menu::moazure_show_features( $currenttab, $currentapp );
	echo '<div class="moazure_free_plugin_container moazure-rad">';
		MOAzure_Admin_Menu::moazure_display_features( $currenttab, $currentapp );
	echo '<div id="moazure_settings" class="main_cont">
			<div class="moazure_container">';
	$notice_arr = ! empty( get_option( 'notice_settings' ) ) ? get_option( 'notice_settings' ) : array();
	if ( ! empty( $notice_arr ) ) {
		if ( 'success' === $notice_arr['msg_type'] ) {
			MOAzure_Admin_Utils::moazure_success_message( $notice_arr['msg_desc'] );
		} else {
			MOAzure_Admin_Utils::moazure_error_message( $notice_arr['msg_desc'] );
		}
	}
				MOAzure_Admin_Menu::show_tab( $currenttab, $currentapp );
			echo '</div>
			<div class="support_cont">';
				MOAzure_Admin_Menu::show_support_sidebar( $currenttab, $currentapp );
			echo '</div>
		</div>
	</div>';
}


/**
 * Migrate Customers
 *
 * @return true|false
 */
function moazure_migrate_customers() {
	if ( get_option( 'moazure_admin_customer_key' ) > 138200 ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Display data based on different tabs.
 */
class MOAzure_Admin_Menu {

	/**
	 * Show Menu
	 *
	 * @param mixed $currenttab current tab the user has clicked.
	 * @param mixed $currentapp current app the user has clicked.
	 */
	public static function show_menu( $currenttab, $currentapp ) {

		$app = ( 'azure-b2c' === $currentapp ) ? $currentapp : 'entra-id';

		?>
		<div class="moazure_plugin_body">
			<div class="container moazure-flex moazure_top_container">
				<div class="moazure-flex">
					<div>
						<img style="margin-right:1rem;" src="<?php echo esc_attr( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/mini.png">
					</div>
					<div class="moazure_plugin_heading">
						<h1 class="moazuer_h1"><b>All-in-One Microsoft</b></h1>
					</div>
				</div>
				
				<div>
					<a id="" class="mo_generic-btns-on-top moazure_header_link" href="https://plugins.miniorange.com/wordpress-azure-office365-integrations#pricing-plans" target="_blank" style="background-color: #0072c6!important; border-radius: 30px;color: white!important; padding: 10px;">
						<span>
							<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/partials/apps/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
						</span>
						<span>
							<?php esc_html_e( 'Check Premium Plans', 'all-in-one-microsoft' ); ?>
						</span>
					</a>
				</div>

				<div class="moazure_wrap moazure-flex">
					<div>
						<a id="" class="mo_generic-btns-on-top moazure_header_link" href="https://calendly.com/vishal-singh-inc/wordpress-azure-sso-setup" target="_blank" style="background-color: #0072c6!important; border-radius: 30px;color: white!important; padding: 10px;">
							<span>
								<?php esc_html_e( 'Schedule Free Setup', 'all-in-one-microsoft' ); ?>
							</span>
						</a>
					</div>
					<div>
						<a id="" class="mo_generic-btns-on-top moazure-rad moazure_header_link" href="
						<?php
						echo ! empty( $_SERVER['REQUEST_URI'] ) ? esc_attr(
							add_query_arg(
								array(
									'tab' => 'requestfordemo',
									'app' => $app,
								),
								sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) )
							)
						) : '';
						?>
						">
							<span>
								<?php esc_html_e( 'Request for Demo/Trial', 'all-in-one-microsoft' ); ?>
							</span>
						</a>
					</div>
					<div>
						<a id="" class="mo_generic-btns-on-top moazure-rad moazure_header_link" href="
						<?php
						echo ! empty( $_SERVER['REQUEST_URI'] ) ? esc_attr(
							add_query_arg(
								array(
									'tab' => 'account',
								),
								sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) )
							)
						) : '';
						?>
						">
							<span>
								<?php esc_html_e( 'Account Setup', 'all-in-one-microsoft' ); ?>
							</span>
						</a>
					</div>
				</div>
			</div>
			<br/>

		<style>
		.mo-add-new-hover:hover {
			color: white !important;
		}
		</style>
		<?php
	}

	/**
	 * Handle views according to current tab.
	 *
	 * @param mixed $currenttab current tab user is viewing.
	 * @param mixed $currentapp current app user is viewing.
	 */
	public static function moazure_show_features( $currenttab, $currentapp ) {

		?>
		<div>
			<div class="moazure-features moazure-flex">
				<div class="feat-app moazure-rad <?php echo ( 'entra-id' === $currentapp ) ? 'sel-app' : ''; ?>" data-tab="moazure_config" data-app="entra-id">
					<img class="feat-icon" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/entra-id.svg" alt="entra-id"><br/>
					<span class="feat-text"><?php esc_html_e( 'EntraID (AzureAD)', 'all-in-one-microsoft' ); ?></span>
				</div>
				<div class="feat-app moazure-rad <?php echo ( 'azure-b2c' === $currentapp ) ? 'sel-app' : ''; ?>" data-tab="moazure_config" data-app="azure-b2c">
					<img class="feat-icon" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/azure-b2c.svg" alt="azure-b2c"><br/>
					<span class="feat-text"><?php esc_html_e( 'Azure AD B2C', 'all-in-one-microsoft' ); ?></span>
				</div>
				<div class="feat-app moazure-rad <?php echo ( ! empty( $currentapp ) && 'sharepoint' === $currentapp ) ? 'sel-app' : ''; ?>" data-tab="sps_preview" data-app="sharepoint">
					<img class="feat-icon" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/sharepoint.svg" alt="sharepoint logo"><br/>
					<span class="feat-text"><?php esc_html_e( 'Sharepoint', 'all-in-one-microsoft' ); ?></span>
				</div>
				<div class="feat-app moazure-rad <?php echo ( ! empty( $currentapp ) && 'power-bi' === $currentapp ) ? 'sel-app' : ''; ?>" data-tab="pbi_app" data-app="power-bi">
					<img class="feat-icon" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/power-bi.svg" alt="power-bi logo"><br/>
					<span class="feat-text"><?php esc_html_e( 'Power BI', 'all-in-one-microsoft' ); ?></span>
				</div>
				<div class="feat-app moazure-rad <?php echo ( ! empty( $currentapp ) && 'outlook' === $currentapp ) ? 'sel-app' : ''; ?>" data-tab="requestfordemo" data-app="outlook">
					<img class="feat-icon" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/outlook.svg" alt="outlook logo"><br/>
					<span class="feat-text"><?php esc_html_e( 'Outlook', 'all-in-one-microsoft' ); ?></span>
				</div>
				<div class="feat-app moazure-rad <?php echo ( ! empty( $currentapp ) && 'dynamics-crm' === $currentapp ) ? 'sel-app' : ''; ?>" data-tab="requestfordemo" data-app="dynamics-crm">
					<img class="feat-icon" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/dcrm-365.svg" alt="dynamics-crm logo"><br/>
					<span class="feat-text"><?php esc_html_e( 'Dynamics CRM', 'all-in-one-microsoft' ); ?></span>
				</div>
			</div>
		</div>
		<br/>
		<?php
	}

	/**
	 * Handle views according to current tab.
	 *
	 * @param mixed $currenttab current tab user is viewing.
	 * @param mixed $currentapp current app user is viewing.
	 */
	public static function moazure_display_features( $currenttab, $currentapp ) {

		?>
		<div>
			<div id="tab">
				<h2 class="moazure_nav_tab_wrapper moazure-flex">
					<?php
					if ( 'entra-id' === $currentapp || 'azure-b2c' === $currentapp ) {
						?>
						<a id="tab-sso" href="admin.php?page=moazure_settings&tab=moazure_config&app=<?php echo ( 'azure-b2c' === $currentapp ) ? 'azure-b2c' : 'entra-id'; ?>" class="moazure_nav-tab anglebg moazure-rad
							<?php
							if ( 'moazure_config' === $currenttab ) {
								echo 'moazure_nav-tab-active';}
							?>
							">
									<?php esc_html_e( 'Configure Application', 'all-in-one-microsoft' ); ?>
						</a>
						<a id="tab-attrmapping" href="admin.php?page=moazure_settings&tab=attributemapping&app=<?php echo ( 'azure-b2c' === $currentapp ) ? 'azure-b2c' : 'entra-id'; ?>" class="moazure_nav-tab moazure-rad
							<?php
							if ( 'attributemapping' === $currenttab ) {
								echo 'moazure_nav-tab-active';}
							?>
							">
									<?php esc_html_e( 'Attribute/Role Mapping', 'all-in-one-microsoft' ); ?>
						</a>
						<a id="tab-signinsettings" href="admin.php?page=moazure_settings&tab=signinsettings&app=<?php echo ( 'azure-b2c' === $currentapp ) ? 'azure-b2c' : 'entra-id'; ?>" class="moazure_nav-tab moazure-rad
							<?php
							if ( 'signinsettings' === $currenttab ) {
								echo 'moazure_nav-tab-active';}
							?>
							">
									<?php esc_html_e( 'SSO Settings', 'all-in-one-microsoft' ); ?>
						</a>
						<a id="tab-customization" href="admin.php?page=moazure_settings&tab=customization&app=<?php echo ( 'azure-b2c' === $currentapp ) ? 'azure-b2c' : 'entra-id'; ?>" class="moazure_nav-tab moazure-rad
							<?php
							if ( 'customization' === $currenttab ) {
								echo 'moazure_nav-tab-active';}
							?>
							">
									<?php esc_html_e( 'Login Button Customization', 'all-in-one-microsoft' ); ?>
									<span>
										<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/partials/apps/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
									</span>
						</a>
						<a id="tab-customization" href="admin.php?page=moazure_settings&tab=troubleshoot&app=<?php echo ( 'azure-b2c' === $currentapp ) ? 'azure-b2c' : 'entra-id'; ?>" class="moazure_nav-tab moazure-rad
							<?php
							if ( 'troubleshoot' === $currenttab ) {
								echo 'moazure_nav-tab-active';}
							?>
							">
									<?php esc_html_e( 'Troubleshoot', 'all-in-one-microsoft' ); ?>
						</a>
						<?php
					} elseif ( 'sharepoint' === $currentapp ) {
						?>
						<a id="tab-preview" href="admin.php?page=moazure_settings&tab=sps_preview&app=sharepoint" class="moazure_nav-tab anglebg moazure-rad
							<?php
							if ( 'sps_preview' === $currenttab && 'sharepoint' === $currentapp ) {
								echo 'moazure_nav-tab-active';
							}
							?>
							">
									<?php esc_html_e( 'Configure Folders/ Files', 'all-in-one-microsoft' ); ?>
						</a>
						<a id="tab-sps-shortcode" href="admin.php?page=moazure_settings&tab=sps_shortcode&app=sharepoint"
							class="moazure_nav-tab anglebg moazure-rad
							<?php
							if ( 'sps_shortcode' === $currenttab && 'sharepoint' === $currentapp ) {
								echo 'moazure_nav-tab-active';}
							?>
							">
									<?php esc_html_e( 'Embed Sharepoint Library', 'all-in-one-microsoft' ); ?>
						</a>
						<a id="tab-sps-shortcode" href="admin.php?page=moazure_settings&tab=sps_embed&app=sharepoint"
							class="moazure_nav-tab anglebg moazure-rad
							<?php
							if ( 'sps_embed' === $currenttab && 'sharepoint' === $currentapp ) {
								echo 'moazure_nav-tab-active';}
							?>
							">
									<?php esc_html_e( 'Embedded View', 'all-in-one-microsoft' ); ?>
									<span>
										<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/partials/apps/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
									</span>
						</a>
						<a id="tab-sps-shortcode" href="admin.php?page=moazure_settings&tab=sps_advanced&app=sharepoint"
							class="moazure_nav-tab anglebg moazure-rad
							<?php
							if ( 'sps_advanced' === $currenttab && 'sharepoint' === $currentapp ) {
								echo 'moazure_nav-tab-active';}
							?>
							">
									<?php esc_html_e( 'Advanced Settings', 'all-in-one-microsoft' ); ?>
									<span>
										<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/partials/apps/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
									</span>
						</a>
						<?php
					} elseif ( 'power-bi' === $currentapp ) {
						?>
						<a id="pbi-app" href="admin.php?page=moazure_settings&tab=pbi_app&app=power-bi" class="moazure_nav-tab anglebg moazure-rad
							<?php
							if ( 'pbi_app' === $currenttab && 'power-bi' === $currentapp ) {
								echo 'moazure_nav-tab-active';
							}
							?>
							">
									<?php esc_html_e( 'Configure Power BI', 'all-in-one-microsoft' ); ?>
						</a>
						<a id="tab-sps-shortcode" href="admin.php?page=moazure_settings&tab=pbi_settings&app=power-bi"
							class="moazure_nav-tab anglebg moazure-rad
							<?php
							if ( 'pbi_settings' === $currenttab && 'power-bi' === $currentapp ) {
								echo 'moazure_nav-tab-active';}
							?>
							">
									<?php esc_html_e( 'Settings', 'all-in-one-microsoft' ); ?>
									<span>
										<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/partials/apps/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
									</span>
						</a>
						<?php
					} elseif ( 'outlook' === $currentapp ) {
						?>
						<a id="pbi-app" href="admin.php?page=moazure_settings&tab=requestfordemo&app=outlook" class="moazure_nav-tab anglebg moazure-rad
							<?php
							if ( 'requestfordemo' === $currenttab && 'outlook' === $currentapp ) {
								echo 'moazure_nav-tab-active';
							}
							?>
							">
									<?php esc_html_e( 'Request For Demo/Trial', 'all-in-one-microsoft' ); ?>
						</a>
						<?php
					} elseif ( 'dynamics-crm' === $currentapp ) {
						?>
						<a id="pbi-app" href="admin.php?page=moazure_settings&tab=requestfordemo&app=dynamics-crm" class="moazure_nav-tab anglebg moazure-rad
							<?php
							if ( 'requestfordemo' === $currenttab && 'dynamics-crm' === $currentapp ) {
								echo 'moazure_nav-tab-active';
							}
							?>
							">
									<?php esc_html_e( 'Request For Demo/Trial', 'all-in-one-microsoft' ); ?>
						</a>
						<?php
					}
					?>
				</h2>
				<hr class="mo-divider">
				<br>
			</div>
		</div>
		<?php
	}

	/**
	 * Function to display the sharepoint automatic application details
	 *
	 * @param string  $app var containing the app name.
	 * @param boolean $auto_app auto app parameter.
	 * @return void
	 */
	public static function ms_app_user_details( $app, $auto_app = false ) {

		if ( $auto_app ) {
			$user = MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_auto_app' );
		} else {
			$user = MOAzure_Admin_Utils::moazure_get_option( 'moazure_test_attributes' );
		}
		$user_upn = ! empty( $user ) ? $user['upn'] : '';
		?>
		<div id="mo_support_layout" class="mo_support_layout moazure_outer_div">
			<div class="moazure-flex" style="justify-content: space-between;">
				<h3 class='mo_app_heading moazure_configure_heading' style='margin: 0 0; font-size:20px'>
					<?php echo esc_html( strtoupper( $app ) ); ?> APP
				</h3>
				<div class="active-div">
					<div class="active-circle"></div>
					Active
				</div>
			</div>
			<hr class="mo-divider">
			<div class="moazure-flex" style="justify-content: space-between; margin-top: 0.5rem;">
				<div>
					<p style="margin-bottom: 0;">
						You are currently logged in with user
						<br/>
						<span style="color: #0072C6;"><?php echo esc_html( $user_upn ); ?></span>
						<br/>
					</p>
				</div>
				<div>
					<form name="moazure_app_logout_form" method="post" action="">
						<?php wp_nonce_field( 'moazure_app_logout_form', 'moazure_app_logout_form_field' ); ?>
						<input type="hidden" name="option" value="moazure_app_logout_option" />
						<input type="hidden" name="ms_app" value="<?php esc_html_e( $app, 'all-in-one-microsoft' ); ?>" />
						<input type="submit" class="button button-large moazure_delete_btn moazure-rad" name="moazure_app_logout" value="<?php esc_html_e( 'Logout', 'all-in-one-microsoft' ); ?>" />
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Handle views according to current tab.
	 *
	 * @param mixed $currenttab current tab user is viewing.
	 * @param mixed $currentapp current app user is viewing.
	 */
	public static function show_tab( $currenttab, $currentapp ) {

		$ms_apps_handler = new MOAzure_MS_Apps();

		if ( 'account' === $currenttab ) {
			if ( get_option( 'moazure_verify_customer' ) === 'true' ) {
				MOAzure_Admin_Account::verify_password();
			} elseif ( '' !== trim( get_option( 'moazure_admin_email' ) ) && trim( get_option( 'moazure_admin_api_key' ) ) === '' && 'true' !== get_option( 'moazure_new_registration' ) ) {
				MOAzure_Admin_Account::verify_password();
			} else {
				MOAzure_Admin_Account::register();
			}
		} elseif ( 'customization' === $currenttab && ( 'entra-setup' === $currentapp || 'entra-auto' === $currentapp || 'entra-id' === $currentapp || 'azure-b2c' === $currentapp ) ) {
				MOAzure_Apps::customization();
		} elseif ( 'signinsettings' === $currenttab && ( 'entra-setup' === $currentapp || 'entra-auto' === $currentapp || 'entra-id' === $currentapp || 'azure-b2c' === $currentapp ) ) {
			MOAzure_Apps::sign_in_settings();
		} elseif ( 'requestfordemo' === $currenttab ) {
			MOAzure_Demo::requestfordemo();
		} elseif ( 'attributemapping' === $currenttab && ( 'entra-setup' === $currentapp || 'entra-auto' === $currentapp || 'entra-id' === $currentapp || 'azure-b2c' === $currentapp ) ) {
			MOAzure_Apps::attribute_role_mapping();
		} elseif ( 'troubleshoot' === $currenttab ) {
			MOAzure_Troubleshoot::moazure_troubleshooting();
		} elseif ( 'sharepoint' === $currentapp || 'power-bi' === $currentapp ) {
			$ms_apps_handler->ms_apps_redirect( $currenttab );
		} else {
			MOAzure_Apps::add_oauth_app();
		}
	}

	/**
	 * Display Support Sidebar.
	 *
	 * @param mixed $currenttab current tab user is viewing.
	 * @param mixed $currentapp current app user is viewing.
	 */
	public static function show_support_sidebar( $currenttab, $currentapp ) {
		$appconfig = array();

		$app       = MOAzure_Admin_Utils::moazure_get_azure_app_config();
		$appconfig = ! empty( $app['config'] ) ? $app['config'] : array();

		$apptype       = ! empty( $appconfig['apptype'] ) ? sanitize_text_field( wp_unslash( $appconfig['apptype'] ) ) : '';
		$ms_entra_apps = MOAzure_Admin_Utils::moazure_get_option( 'moazure_ms_entra_apps' );

		if ( 'licensing' !== $currenttab ) {
			echo '<td style="vertical-align:top;padding-left:1%;" class="moazure_sidebar">';
			if ( 'attributemapping' === $currenttab && $apptype === $currentapp ) {
				echo esc_html( MOAzure_Attribute_Mapping::emit_attribute_table() );
			} elseif ( 'sharepoint' === $currentapp && ( ! empty( $ms_entra_apps['sps_auto'] ) || ! empty( $ms_entra_apps['sps_manual'] ) ) ) {
				$is_sps_auto = ! empty( $ms_entra_apps['sps_auto'] ) ? true : false;
				self::ms_app_user_details( $currentapp, $is_sps_auto );
				echo esc_html( MOAzure_Support::support() );
			} elseif ( 'power-bi' === $currentapp && ! empty( $ms_entra_apps['pbi_manual'] ) ) {
				self::ms_app_user_details( $currentapp );
				echo esc_html( MOAzure_Support::support() );
			} else {
				echo esc_html( MOAzure_Support::support() );
			}
			echo '</td>';
		}
	}
}
?>
