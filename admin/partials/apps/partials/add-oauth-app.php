<?php
/**
 * Update app settings
 *
 * @package    apps
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Display configuration panel for the particular app
 */
function moazure_add_oauth_app_page() {

	$apps_list = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_oauth_sso_config' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_oauth_sso_config' ) : array();
	$appname   = '';
	$appconfig = array();

	$app_type = '';
	foreach ( $apps_list as $key => $value ) {
		$appname   = $key;
		$appconfig = $value;
		break;
	}

	$redirect_uri = ! empty( $appconfig['redirecturi'] ) ? sanitize_text_field( wp_unslash( $appconfig['redirecturi'] ) ) : esc_url_raw( site_url() );

	$apptype = ! empty( $appconfig['apptype'] ) ? sanitize_text_field( wp_unslash( $appconfig['apptype'] ) ) : '';
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
	$appparam = ! empty( $_GET['app'] ) ? sanitize_text_field( wp_unslash( $_GET['app'] ) ) : '';

	$login_btn = isset( $appconfig['show_on_login_page'] ) ? $appconfig['show_on_login_page'] : true;

	if ( 'azure-b2c' === $appparam ) {
		$app_type = 'Azure B2C';
	} else {
		$app_type = 'Entra ID';
	}

	$app_scope = '';
	if ( empty( $appparam ) || 'entra-id' === $appparam ) {
		$app_scope = ! empty( $appconfig['scope'] ) && 'azure-b2c' !== $apptype ? esc_attr( $appconfig['scope'] ) : 'openid profile email';
	} else {
		$app_scope = ! empty( $appconfig['scope'] ) && $apptype === $appparam ? esc_attr( $appconfig['scope'] ) : 'openid';
	}

	?>
	<div class="moazure_table_layout moazure_outer_div">
		<div id="toggle2" class="moazure_configure_header moazure-flex">
			<div>
				<h3 class="moazure_configure_heading">
					<?php
					//phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
					esc_html_e( $app_type . ' SSO Configurations', 'all-in-one-microsoft' );
					?>
				</h3>
				<p class="moazure_desc" style="font-style: normal;">
					Set up the following fields according to the Azure application to integrate Single Sign-On on your website.
				</p>
			</div>
			<div class="moazure-flex">
				<span>
				<?php
				if ( 'Entra ID' === $app_type ) {
					?>
					<a href="https://plugins.miniorange.com/configure-wordpress-azure-sso?setup_guide=azure_ad&utm_source=wordpress%20azuread%20plugin%20readme&utm_medium=organic&utm_campaign=Traffic%20from%20readme" target="_blank" rel="noopener" class="moazure-setup-guide-button moazure-rad" style="text-decoration: none;" > Setup Guide </a> 
					<?php
				} elseif ( 'Azure B2C' === $app_type ) {
					?>
					<a href="https://plugins.miniorange.com/configure-wordpress-azure-sso?setup_guide=azure_b2c&utm_source=wordpress%20azureb2c%20plugin%20readme&utm_medium=organic&utm_campaign=Traffic%20from%20readme" target="_blank" rel="noopener" class="moazure-setup-guide-button moazure-rad" style="text-decoration: none;" > Setup Guide </a>
					<?php
				}
				?>
				</span>
			</div>
		</div>
		<div id="moazure_add_app">
			<?php
			if ( ! empty( $apptype ) ) {
				if ( ( ! empty( $appparam ) && $apptype !== $appparam ) || ( empty( $appparam ) && 'azure-b2c' === $apptype ) ) {
					?>
					<p class="moazure_upgrade_warning moazure-rad" style="text-align: center; color: red;">
						<b>
							<?php
							// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
							esc_html_e( 'Saving Configurations here will overwrite your configured ' . strtoupper( $apptype ) . ' application.', 'all-in-one-microsoft' );
							?>
						</b>
					</p>
					<?php
				}
			}
			?>
			<form id="form-common" name="form-common" method="post" action="">
				<?php wp_nonce_field( 'moazure_add_app_form', 'moazure_add_app_form_field' ); ?>
				<input type="hidden" name="option" value="moazure_add_app" />
				<input type="hidden" name="moazure_app_type" value="<?php echo ( ! empty( $appparam ) ) ? esc_attr( $appparam ) : ''; ?>"/>

				<table class="mo_settings_table moazure_configure_table">
					<tr class="moazure_configure_table_rows" id="moazure_display_app_name_div">
						<td class="moazure_contact_heading td_entra_app">
							<strong class="mo_strong" class="moazure_position"><?php esc_html_e( 'Display App Name', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :</strong>
							<p class="moazure_desc">This name will be displayed on the login button as "Login with *display name*"</p>
						</td>
						<td class="moazure_contact_heading td_entra_app">
							<input class="mo_table_textbox" type="text" required id="moazure_app_name" name="moazure_app_name" placeholder="Enter a name for your app" value="<?php echo ( ( ( empty( $appparam ) && 'azure-b2c' !== $apptype ) || $apptype === $appparam ) && ! empty( $appname ) ) ? esc_attr( $appname ) : ''; ?>" >
						</td>
					</tr>
					<tr class="moazure_configure_table_rows">
						<td class="moazure_contact_heading td_entra_app">
							<strong class="mo_strong" class="moazure_position"><?php esc_html_e( 'Redirect / Callback URI', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> : </strong>
							<div class="moazure_tooltip" style="display: inline;">
								<span class="mo_tooltiptext" style="width: 250px; margin-left:-175px;" id="moTooltip_info">
									How to configure?
									<ol style="text-align: left; padding: 5px;">
										<li>Copy the URL from here</li>
										<li>Go to Azure Web-App</li>
										<li>Navigate to Authentication tab</li>
										<li>Click on Add URI</li>
										<li>Paste the URL there</li>
									</ol>
								</span>
								<i class="fa fa-info-circle moazure_info " style="font-size:17px; align-items: center;vertical-align: middle;" aria-hidden="true"></i>
							</div>
							<p class="moazure_desc">This is the endpoint where the authorization server sends the user after they authorize or deny the application</p>
						</td>
						<td class="moazure_contact_heading td_entra_app">
							<input class="mo_table_textbox" id="callbackurl" type="text" name="moazuer_callback_url" placeholder="Enter the Redirect/Callback URI" value="<?php echo esc_attr( $redirect_uri ); ?>">
							&nbsp;&nbsp;
							<div class="moazure_tooltip" style="display: inline;">
								<span class="mo_tooltiptext" id="moTooltip_copy">Copy to clipboard</span>
								<i class="fa fa-clipboard fa-border" style="font-size:20px; align-items: center;vertical-align: middle;" aria-hidden="true" onclick="mooauth_copyUrl()" onmouseout="mooauth_outFunc()"></i>
							</div>
						</td>
					</tr>
					<tr class="moazure_configure_table_rows">
						<td class="moazure_contact_heading td_entra_app">
							<strong class="mo_strong"><?php esc_html_e( 'Application (client) ID', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :</strong>
							<p class="moazure_desc">This is a unique identifier assigned to an app registration, used to identify the app within Azure Active Directory</p>
						</td>
						<td class="moazure_contact_heading td_entra_app">
							<input class="mo_table_textbox" required="" type="text" id="moazure_client_id" name="moazure_client_id" placeholder="Enter Client ID from Azure app" value="<?php echo ( ( ( empty( $appparam ) && 'azure-b2c' !== $apptype ) || $apptype === $appparam ) && ! empty( $appconfig['clientid'] ) ) ? esc_attr( $appconfig['clientid'] ) : ''; ?>" >
						</td>
					</tr>
					<tr class="moazure_configure_table_rows">
						<td class="moazure_contact_heading td_entra_app">
							<strong class="mo_strong"><?php esc_html_e( 'Client Secret', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :</strong>
							<p class="moazure_desc">This is a confidential string used by applications to authenticate with Azure Active Directory (AD) when requesting access to resources or APIs</p>
						</td>
						<td class="moazure_contact_heading td_entra_app">
							<input class="mo_table_textbox" required="" type="password" id="moazure_client_secret" name="moazure_client_secret" placeholder="Enter Client Secret from Azure app" value="<?php echo ( ( ( empty( $appparam ) && 'azure-b2c' !== $apptype ) || $apptype === $appparam ) && ! empty( $appconfig['clientsecret'] ) ) ? esc_attr( $appconfig['clientsecret'] ) : ''; ?>" >
							<i class="fa fa-eye" onclick="moazure_showClientSecret()" id="show_button" style="margin-left:-30px; cursor:pointer;"></i>
						</td>
					</tr>
					<?php
					if ( 'Azure B2C' === $app_type ) {
						?>
						<tr class="moazure_configure_table_rows">
							<td class="moazure_contact_heading td_entra_app">
								<strong class="mo_strong"><?php esc_html_e( 'Tenant-Name', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :</strong>
								<p class="moazure_desc">
								The tenant name in Azure is the unique identifier for an organization's Azure instance</p>
							</td>
							<td class="moazure_contact_heading td_entra_app">
								<input class="mo_table_textbox" type="text" required id="moazure_b2c_tenant" name="moazure_b2c_tenant" placeholder="Enter the Tenant name" value="<?php echo ( ( ( empty( $appparam ) && 'azure-b2c' !== $apptype ) || $apptype === $appparam ) && ! empty( $appconfig['tenant-name'] ) ) ? esc_attr( $appconfig['tenant-name'] ) : ''; ?>" >
							</td>
						</tr>
						<tr class="moazure_configure_table_rows">
							<td class="moazure_contact_heading td_entra_app">
								<strong class="mo_strong"><?php esc_html_e( 'User-Flow/ Policy Name', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :</strong>
								<p class="moazure_desc">
								A policy name specifies the identity experience (e.g., sign-up, sign-in, or password reset) that users will follow during authentication in Azure AD B2C</p>
							</td>
							<td class="moazure_contact_heading td_entra_app">
								<input class="mo_table_textbox" type="text" required id="moazure_b2c_policy" name="moazure_b2c_policy" placeholder="Enter the Policy name" value="<?php echo ( ( ( empty( $appparam ) && 'azure-b2c' !== $apptype ) || $apptype === $appparam ) && ! empty( $appconfig['policy-name'] ) ) ? esc_attr( $appconfig['policy-name'] ) : ''; ?>" >
							</td>
						</tr>
						<?php
					} else {
						?>
						<tr class="moazure_configure_table_rows">
							<td class="moazure_contact_heading td_entra_app">
								<strong class="mo_strong"><?php esc_html_e( 'Directory (tenant) ID', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :</strong>
								<p class="moazure_desc">The tenant ID in Azure is a unique identifier for an Azure Active Directory (AAD) instance</p>
							</td>
							<td class="moazure_contact_heading td_entra_app">
								<input class="mo_table_textbox" type="text" required id="moazure_tenant_id" name="moazure_tenant_id" placeholder="Enter the tenant id" value="<?php echo ( ( ( empty( $appparam ) && 'azure-b2c' !== $apptype ) || $apptype === $appparam ) && ! empty( $appconfig['tenant-id'] ) ) ? esc_attr( $appconfig['tenant-id'] ) : ''; ?>" >
							</td>
						</tr> 
						<?php
					}
					?>
					<tr class="moazure_configure_table_rows">
						<td class="moazure_contact_heading td_entra_app">
							<strong class="mo_strong"><?php esc_html_e( 'Scope :', 'all-in-one-microsoft' ); ?></strong>
							<p class="moazure_desc">A scope defines the specific permissions or access levels an application is requesting for a resource</p>
						</td>
						<td class="moazure_contact_heading td_entra_app">
							<input class="mo_table_textbox" type="text" id="moazure_scope" name="moazure_scope"  value="<?php echo esc_attr( $app_scope ); ?>">
						</td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td class="moazure_contact_heading td_entra_app">
							<strong class="mo_strong"><?php esc_html_e( 'Enable Single Sign-On :', 'all-in-one-microsoft' ); ?></strong>
							<p class="moazure_desc">This will add a button on the WP login page using which you can initiate SSO</p>
						</td>
						<td class="moazure_contact_heading td_entra_app">
							<label class="moazure_switch" style="float: left;">
								<input class="mo_input_checkbox" id="toggleSwitch" type="checkbox" name="moazure_show_on_login_page" value="1"
								<?php
								echo $login_btn ? 'checked' : '';
								?>
								/>
								<span class="moazure_slider round"></span>
							</label>
						</td>
					</tr>
				</table>
				<div class="moazure-flex" style="justify-content: space-between;">
					<div class="moazure-flex moazure-app-submit">
						<div>							
							<input type="submit" name="submit" value="<?php esc_html_e( 'Save settings', 'all-in-one-microsoft' ); ?>" class="button button-large moazure_configure_btn moazure-rad" />
						</div>
						<div>
							<?php
							if ( ! empty( $apps_list ) && ( ( empty( $appparam ) && 'azure-b2c' !== $apptype ) || $apptype === $appparam ) ) {
								?>
								<input id="moazure_test_configuration" type="button" name="button" value="<?php esc_html_e( 'Test Configuration', 'all-in-one-microsoft' ); ?>" class="button button-large moazure_configure_btn moazure-rad" onclick="moazure_testConfiguration()" />
								<?php
							}
							?>
						</div>
					</div>
					<div class="moazure_troubleshoot_div">
						<a id="faq_button_id" class="mo_generic-btns-on-top moazure-rad moazure_header_link" href="
						<?php
						echo ! empty( $_SERVER['REQUEST_URI'] ) ? esc_attr(
							add_query_arg(
								array(
									'tab' => 'troubleshoot',
								),
								sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) )
							)
						) : '';
						?>
						">
							<span>
								<?php esc_html_e( 'Troubleshoot', 'all-in-one-microsoft' ); ?>
							</span>
						</a>
					</div>
				</div>
				<p class="moazure_upgrade_warning moazure-rad moazure-center">
					<strong class="mo_strong">*NOTE:&nbsp; </strong>
					<b><?php esc_html_e( 'Please configure', 'all-in-one-microsoft' ); ?> <a id="moazuer_attr_map" href='<?php echo esc_attr( admin_url( 'admin.php?page=moazure_settings&tab=attributemapping&app=' . $apptype ) ); ?>'><?php esc_html_e( 'Attribute Mapping', 'all-in-one-microsoft' ); ?></a> <?php esc_html_e( 'before trying Single Sign-On.', 'all-in-one-microsoft' ); ?></b>
				</p>
			</form>
		</div>
	</div>

	<script>

		var loginBtnValue = <?php echo wp_json_encode( $login_btn ); ?>;
		document.getElementById('toggleSwitch').checked = loginBtnValue;

		function moazure_testConfiguration() {
			let moazure_app_name = jQuery("#moazure_app_name").val();
			let myWindow = window.open('<?php echo esc_attr( site_url() ); ?>' + '/?option=moazure_oauth_redirect&test=true&app_name=' + moazure_app_name, "Test Attribute Configuration", "width=800, height=700");
		}

		function moazure_showClientSecret() {
			let field = document.getElementById("moazure_client_secret");
			let show_button = document.getElementById("show_button");
			if(field.type == "password"){
				field.type = "text";
				show_button.className = "fa fa-eye-slash";
			}
			else{
				field.type = "password";
				show_button.className = "fa fa-eye";
			}
		}

		function moazure_attr_role_redirect() {
			let azureApp = '<?php echo ( ! empty( $appconfig['apptype'] ) ) ? esc_html( $appconfig['apptype'] ) : 'entra-id'; ?>';
			let redUrl = '<?php echo esc_url_raw( admin_url() ); ?>' + 'admin.php?page=moazure_settings&tab=attributemapping&app=' + azureApp;
			window.location.href = redUrl;
		}

		function sso_reload_after_close() {
			window.location.reload();
		}
	</script>

	<?php
}
