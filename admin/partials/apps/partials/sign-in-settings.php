<?php
/**
 * Sign in Settings
 *
 * @package    apps
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Display Sign in Settings UI
 */
function moazure_sign_in_settings_ui() {
	?>
	<div  id="wid-shortcode" class="moazure_table_layout moazure_attribute_page_font moazure_outer_div">
		<div class="moazure_customization_header moazure-flex">
			<div class="moazure_attribute_map_heading">
				<b class="moazure_position"><?php esc_html_e( 'Sign in options', 'all-in-one-microsoft' ); ?></b>
			</div>
			<div class="moazure_tooltip">
				<span class="mo_tooltiptext">Know how this is useful</span>
				<a style="text-decoration: none;" target="_blank" href="https://developers.miniorange.com/docs/oauth/wordpress/client/login-options" rel="noopener noreferrer">
				<img class="moazure_guide_img" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/moazure_info-icon.png" alt="miniOrange Premium Plans Logo" aria-hidden="true"></a>
			</div>
		</div>
		<h4><?php esc_html_e( 'Option 1: Use a Widget', 'all-in-one-microsoft' ); ?></h4>
		<ol>
			<li><?php esc_html_e( 'Go to Appearances > Widgets.', 'all-in-one-microsoft' ); ?></li>
			<li>Select <b>"<?php echo esc_attr( MO_AZURE_ADMIN_MENU ); ?>"</b>.
				<?php esc_html_e( 'Drag and drop to your favourite location and save.', 'all-in-one-microsoft' ); ?>
			</li>
		</ol>

		<br/>

		<h4><?php esc_html_e( 'Option 2: Use a Shortcode', 'all-in-one-microsoft' ); ?>
			<small>
				<a href="https://plugins.miniorange.com/wordpress-azure-office365-integrations#pricing-plans" target="_blank" rel="noopener noreferrer">
					<span style="border:none">
						<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
					</span>
				</a>
			</small>
		</h4>
		<ul>
			<li><?php esc_html_e( 'Place shortcode', 'all-in-one-microsoft' ); ?>
				<b>[MOAZURE_LOGIN]</b>
				<?php esc_html_e( 'in WordPress pages or posts.', 'all-in-one-microsoft' ); ?>
			</li>
		</ul>
	</div>

<!--div class="moazure_premium_option_text"><span style="color:red;">*</span>This is a premium feature.
		<a href="admin.php?page=moazure_settings&tab=licensing">Click Here</a> to see our full list of Premium Features.</div-->
	<div id="advanced_settings_sso" class="moazure_table_layout moazure_outer_div">
		<form id="signing_setting_form" name="f" method="post" action="">
		<?php wp_nonce_field( 'moazure_role_mapping_form_nonce', 'moazure_role_mapping_form_field' ); ?>
			<div class="moazure_customization_header moazure-flex">
				<div class="moazure_attribute_map_heading">
					<?php esc_html_e( 'WordPress User Profile Sync-up Settings', 'all-in-one-microsoft' ); ?>
					<small>
						<a href="https://plugins.miniorange.com/wordpress-azure-office365-integrations#pricing-plans" target="_blank" rel="noopener noreferrer">
							<span style="border:none">
								<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
							</span>
						</a>
					</small>
				</div>
				<div class="moazure_tooltip moazure_tooltip_float_right">
					<span class="mo_tooltiptext">About Auto Create Users</span>
					<a style="text-decoration: none;" target="_blank" href="https://developers.miniorange.com/docs/oauth/wordpress/client/auto-register-users" rel="noopener noreferrer">
						<img class="moazure_guide_img" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/moazure_info-icon.png" alt="miniOrange Premium Plans Logo" aria-hidden="true">
					</a>
				</div>
			</div>

			<table class="moazure_mapping_table" style="width:90%; border-collapse: collapse; line-height:200%">
				<tbody>
					<tr>
						<td>
							<font style="font: size 14px;">
								<?php esc_html_e( 'Auto register Users', 'all-in-one-microsoft' ); ?>
							</font>
							<br>
							<?php esc_html_e( '(If unchecked, only existing users will be able to log-in)', 'all-in-one-microsoft' ); ?>
							</p>
						</td>
						<td>
							<input disabled="true" type="checkbox" class="mo_input_checkbox moazure_input_disabled" checked>
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Keep Existing Users', 'all-in-one-microsoft' ); ?>
							</font>
							<br>
							<?php esc_html_e( '(If checked, existing users\' attributes will NOT be overwritten when they log-in)', 'all-in-one-microsoft' ); ?>
							</p>
						</td>
						<td>
							<input disabled="true" type="checkbox" class="mo_input_checkbox moazure_input_disabled">
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Keep Existing Email Attribute', 'all-in-one-microsoft' ); ?>
							</font>
							<br>
							<?php esc_html_e( '(If checked, existing users\' only email attribute will NOT be overwritten when they log-in)', 'all-in-one-microsoft' ); ?>
							</p>
						</td>
						<td>
							<input disabled="true" type="checkbox" class="mo_input_checkbox moazure_input_disabled">
						</td>
					</tr>
					<tr class="mo-divider">
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<h3 class="moazure_signing_heading" style="font-size:18px;">
								<?php esc_html_e( 'SSO Settings', 'all-in-one-microsoft' ); ?>
								<small>
									<a href="https://plugins.miniorange.com/wordpress-azure-office365-integrations#pricing-plans" target="_blank" rel="noopener noreferrer">
										<span style="border:none">
											<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
										</span>
									</a>
								</small>
							</h3>
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Custom redirect URL after login', 'all-in-one-microsoft' ); ?>
							</font>
							<br>
							<?php esc_html_e( '(Keep blank in case you want users to redirect to page from where SSO originated)', 'all-in-one-microsoft' ); ?>
							</p>
						</td>
						<td>
							<input disabled="true" class="moazure_input_disabled" type="text" style="width:100%;">
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Hide & Disable WP Login / Block WordPress Login', 'all-in-one-microsoft' ); ?>
							</font>
							<br>
							<?php esc_html_e( '(Keep blank in case you want users to redirect to page from where SSO originated)', 'all-in-one-microsoft' ); ?>
							</p>
						</td>
						<td>
							<input disabled="true" type="checkbox" class="mo_input_checkbox moazure_input_disabled">
						</td>
					</tr>
					<tr class="mo-divider">
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<h3 class="moazure_signing_heading" style="font-size:18px;">
								<?php esc_html_e( 'Logout Settings', 'all-in-one-microsoft' ); ?>
								<small>
									<a href="https://plugins.miniorange.com/wordpress-azure-office365-integrations#pricing-plans" target="_blank" rel="noopener noreferrer">
										<span style="border:none">
											<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
										</span>
									</a>
								</small>
							</h3>
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Custom redirect URL after logout', 'all-in-one-microsoft' ); ?>
							</font>
						</td>
						<td>
							<input disabled="true" class="moazure_input_disabled" type="text" style="width:100%;">
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Confirm when logging out', 'all-in-one-microsoft' ); ?>
							</font>
							<br>
							<?php esc_html_e( '(If checked, users will be ASKED to confirm if they want to log-out, when they click the widget/shortcode logout button)', 'all-in-one-microsoft' ); ?>
							</p>
						</td>
						<td>
							<input disabled="true" type="checkbox" class="mo_input_checkbox moazure_input_disabled">
						</td>
					</tr>
					<tr class="mo-divider">
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<h3 class="moazure_signing_heading" style="font-size:18px;">
								<?php esc_html_e( 'WordPress Site Access Control (Security Settings)', 'all-in-one-microsoft' ); ?>
								<small>
									<a href="https://plugins.miniorange.com/wordpress-azure-office365-integrations#pricing-plans" target="_blank" rel="noopener noreferrer">
										<span style="border:none">
											<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
										</span>
									</a>
								</small>
							</h3>
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Restrict site to logged in users', 'all-in-one-microsoft' ); ?>
							</font>
							<br>
							<?php esc_html_e( '(Users will be auto redirected to OAuth login if not logged in)', 'all-in-one-microsoft' ); ?>
							</p>
						</td>
						<td>
							<input disabled="true" type="checkbox" class="mo_input_checkbox moazure_input_disabled">
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Allowed Domains / Whitelisted Domains', 'all-in-one-microsoft' ); ?>
							</font>
							<br>
							(Comma separated domains ex.
							domain1.com,domain2.com etc)
							</p>
						</td>
						<td>
							<input disabled="true" class="moazure_input_disabled" type="text" placeholder="domain1.com,domain2.com" style="width:100%;">
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Restricted Domains / Blacklisted Domains', 'all-in-one-microsoft' ); ?>
							</font>
							<br>
							(Comma separated domains ex.
							domain1.com,domain2.com etc)
							</p>
						</td>
						<td>
							<input disabled="true" class="moazure_input_disabled" type="text" placeholder="domain1.com,domain2.com" style="width:100%;">
						</td>
					</tr>
					<tr class="mo-divider">
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<h3 class="moazure_signing_heading" style="font-size:18px;">
								<?php esc_html_e( 'SSO Window Settings', 'all-in-one-microsoft' ); ?>
								<small>
									<a href="https://plugins.miniorange.com/wordpress-azure-office365-integrations#pricing-plans" target="_blank" rel="noopener noreferrer">
										<span style="border:none">
											<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
										</span>
									</a>
								</small>
							</h3>
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Open login window in Popup', 'all-in-one-microsoft' ); ?>
							</font>
							<br>
							<?php esc_html_e( '(Keep blank in case you want users to redirect to page from where SSO originated)', 'all-in-one-microsoft' ); ?>
							</p>
						</td>
						<td>
							<input disabled="true" type="checkbox" class="mo_input_checkbox moazure_input_disabled">
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Enable Single Login Flow', 'all-in-one-microsoft' ); ?>
							</font>
						</td>
						<td>
							<input disabled="true" type="checkbox" class="mo_input_checkbox moazure_input_disabled">
						</td>
					</tr>
					<tr class="mo-divider">
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<h3 class="moazure_signing_heading" style="font-size:18px;">
								<?php esc_html_e( 'User Login Audit / Login Reports', 'all-in-one-microsoft' ); ?>
								<small>
									<a href="https://plugins.miniorange.com/wordpress-azure-office365-integrations#pricing-plans" target="_blank" rel="noopener noreferrer">
										<span style="border:none">
											<img class="moazure_premium-label" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/images/moazure_premium-label.png' ); ?>" alt="miniOrange Standard Plans Logo">
										</span>
									</a>
								</small>
							</h3>
						</td>
					</tr>
					<tr>
						<td>
							<font style="font-size:14px;">
								<?php esc_html_e( 'Enable User login reports', 'all-in-one-microsoft' ); ?>
							</font>
							</p>
						</td>
						<td>
							<input disabled="true" type="checkbox" class="mo_input_checkbox moazure_input_disabled">
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input disabled type="submit" class="button button-primary button-large mo_disabled_btn" value="<?php esc_html_e( 'Save Settings', 'all-in-one-microsoft' ); ?>">
						</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<?php
}
