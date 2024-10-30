<?php
/**
 * Support
 *
 * @package    support
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * [Description Handle Customer Support]
 */
class MOAzure_Support {

	/**
	 * Call internal functions
	 */
	public static function support() {
		self::support_page();
	}

	/**
	 * Display Contact Us Form.
	 */
	public static function support_page() {

		$contact_email = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_admin_email' ) ) ? sanitize_email( MOAzure_Admin_Utils::moazure_get_option( 'moazure_admin_email' ) ) : sanitize_email( MOAzure_Admin_Utils::moazure_get_option( 'admin_email' ) );
		?>
		<div id="mo_support_layout" class="mo_support_layout moazue_support_outer_div">
			<div>
				<h3 class="moazure_contact_heading moazure_configure_heading" >
					<?php esc_html_e( 'Ask any questions', 'all-in-one-microsoft' ); ?>
				</h3>
				<div style="display: flex;align-items: center;gap:6px;">
					<div style="font-size:13px;">
						<?php esc_html_e( 'Need any help? Just send us a query and we will get back to you soon.', 'all-in-one-microsoft' ); ?>
					</div>
				</div>
				<form method="post" action="">
					<?php wp_nonce_field( 'moazure_support_form', 'moazure_support_form_field' ); ?>
					<input type="hidden" name="option" value="moazure_contact_us_query_option" />
					<div class="mo_oauth_contact">
						<table class="mo_settings_table" style="display: none;">
							<input type="email" class="mo_oauth_contact-input-fields" placeholder="Enter your email" name="moazure_contact_us_email" value="<?php echo esc_attr( $contact_email ); ?>" required />
							<input type="tel" id="contact_us_phone" class="mo_settings_table mo_oauth_contact-input-fields" placeholder="Enter your phone number" name="moazure_contact_us_phone" value="<?php echo esc_attr( MOAzure_Admin_Utils::moazure_get_option( 'moazure_admin_phone' ) ); ?>" pattern="[\+]\d{11,14}|[\+]\d{1,4}[\s]\d{9,10}|[\+]\d{1,4}[\s]" />
							<textarea cols="30" rows="4" placeholder="<?php esc_attr_e( 'Enter your query...', 'all-in-one-microsoft' ); ?>" name="moazure_contact_us_query" onkeypress="moazure_valid_query(this)" onkeyup="moazure_valid_query(this)" rows="4" style="resize: vertical;" onblur="moazure_valid_query(this)" required></textarea>

							<div class="moazure-flex" style="gap: 1rem;">
								<input id="moazure_send_plugin_config" class="mo_oauth_checkbox" type="checkbox" class="mo_input_checkbox" name="moazure_send_plugin_config" checked />
								<div class="mo_oauth_checkbox-content">
									<span class="mo_oauth_checkbox-info">
										<?php esc_html_e( 'Send Plugin Configuration', 'all-in-one-microsoft' ); ?>
										<div class="moazure_tooltip" style="display: inline;">
											<span class="mo_tooltiptext" style="width: 250px; margin-left:-175px;" id="moTooltip_info">
												Your Azure Client ID & Client Secret won't be shared.
											</span>
											<i class="fa fa-info-circle moazure_info " style="font-size:17px; align-items: center;vertical-align: middle;" aria-hidden="true"></i>
										</div>
									</span>
								</div>
							</div>
						</table>
						<div class="mo_oauth_setup-call moazure-flex moazure-rad">
							<label class="moazure_switch">
								<input id="moazure_setup_call" type="checkbox" class="mo_input_checkbox" style="background: #dcdad1" name="moazure_setup_call" />
								<span class="moazure_slider round"></span>
							</label>
							<p>
								<b>
									<label for="moazure_setup_call"></label>
									<?php esc_html_e( 'Setup a Call/ Screen-share session', 'all-in-one-microsoft' ); ?>
								</b>
							</p>
						</div>
						<div class="moazure-rad" id="moazure_setup_call_div">
							<table class="mo_settings_table" cellpadding="2" cellspacing="2">
								<tr>
									<td>
										<strong class="mo_strong">
												<?php esc_html_e( 'Issue', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :
										</strong>
									</td>
									<td>
										<select id="issue_dropdown" class="mo_callsetup_table_textbox" name="moazure_setup_call_issue">
											<option disabled selected>--------Select Issue type--------</option>
											<option id="sso_setup_issue">SSO Setup Issue</option>
											<option>Custom requirement</option>
											<option id="other_issue">Other</option>
										</select>
									</td>
								</tr>
								<tr id="setup_guide_link" style="display: none;">
									<td colspan="2">
										<?php esc_html_e( 'Have you checked the setup guide ', 'all-in-one-microsoft' ); ?><a href="https://plugins.miniorange.com/wordpress-single-sign-on-sso-with-oauth-openid-connect" target="_blank">here</a>?
									</td>
								</tr>
								<tr id="required_mark" style="display: none;">
									<td>
										<strong class="mo_strong">
											<?php esc_html_e( 'Description', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :
										</strong>
									</td>
									<td>
										<textarea id="issue_description" class="moazure_issue_description" onkeypress="moazure_valid_query(this)" placeholder="<?php esc_html_e( 'Enter your issue description here', 'all-in-one-microsoft' ); ?>" onkeyup="moazure_valid_query(this)" onblur="moazure_valid_query(this)" name="moazure_issue_description" rows="2" style="resize: vertical;"></textarea>
									</td>								
								</tr>
								<tr>
									<td>
										<strong class="mo_strong">
												<?php esc_html_e( 'Date', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :
										</strong>
									</td>
									<td>
										<input class="mo_callsetup_table_textbox" name="moazure_setup_call_date" type="text" id="calldate">
									</td>
								</tr>
								<tr>
									<td>
										<strong class="mo_strong">
											<?php esc_html_e( 'Local Time', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :
										</strong>
									</td>
									<td>
										<input class="mo_callsetup_table_textbox" name="moazure_setup_call_time" type="time" id="moazure_setup_call_time">
									</td>
								</tr>
							</table>
							<p>
								<?php esc_html_e( 'We are available from 3:30 to 18:30 UTC', 'all-in-one-microsoft' ); ?>
							</p>
							<input type="hidden" name="moazure_time_diff" id="moazure_time_diff">
						</div>
					<div style="text-align: center;">
						<input type="submit" name="submit" class="button button-large moazure_configure_btn"value="Submit" />
					</div>
				</form>
			</div>
		</div>

		<?php
	}
}
