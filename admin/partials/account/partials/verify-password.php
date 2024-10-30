<?php
/**
 * Verify-Password
 *
 * @package    verify-password-ui
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * When a user attempts to register with an already registered email address, display the UI for logging in with miniOrange.
 */
function moazure_verify_password_ui() {

	$notice_arr = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'notice_settings' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'notice_settings' ) : array();
	if ( ! empty( $notice_arr ) ) {
		if ( 'success' === $notice_arr['msg_type'] ) {
			MOAzure_Admin_Utils::moazure_success_message( $notice_arr['msg_desc'] );
		} else {
			MOAzure_Admin_Utils::moazure_error_message( $notice_arr['msg_desc'] );
		}
	}
	?>
		<form name="f" method="post" action="">
			<?php wp_nonce_field( 'moazure_verify_password_form', 'moazure_verify_password_form_field' ); ?>
			<input type="hidden" name="option" value="moazure_verify_customer" />
			<div class="moazure_table_layout moazure_outer_div" id="moazure_register">				
				<div id="toggle1" class="moazure_customization_header">
					<h3 class="moazure_signing_heading" style="margin:0px;"><?php esc_html_e( 'Login with miniOrange', 'all-in-one-microsoft' ); ?></h3> 
				</div>
				<div class="moazure_contact_heading" id="panel1">
					<p class="moazure_paragraph_div" style="padding-left:20px;"><b>It seems you already have an account with miniOrange. Please enter your miniOrange email and password.</b></p>
					<table class="mo_settings_table moazure_configure_table">
						<tr>
							<td>
								<b>Email<font style="color: red;">*</font> :</b>
							</td>
							<td>
								<input class="mo_table_textbox" type="email" name="email" required placeholder="person@example.com" value="<?php echo esc_attr( MOAzure_Admin_Utils::moazure_get_option( 'moazure_admin_email' ) ); ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<b>Password<font style="color: red;">*</font> :</b>
							</td>
							<td>
								<input class="mo_table_textbox" required type="password"name="password" placeholder="Choose your password" />
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input type="submit" style="padding:0px 20px;" name="submit" value="<?php esc_attr_e( 'Login', 'all-in-one-microsoft' ); ?>" class="button button-large moazure_configure_btn" /></form>

								<input type="button" style="padding:0px 20px;" name="back-button" id="moazure_back_button" onclick="document.getElementById('moazure_change_email_form').submit();" value="<?php esc_attr_e( 'Sign up', 'all-in-one-microsoft' ); ?>" class="button button-large moazure_configure_btn" />

								<form id="moazure_change_email_form" method="post" action="">
									<?php wp_nonce_field( 'moazure_change_email_form', 'moazure_change_email_form_field' ); ?>
									<input type="hidden" name="option" value="moazure_change_email" />
								</form></td>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><h4><b><a href="#moazure_forgot_password_link" style="color:#3582c4;">Click here if you forgot your password?</a></b></h4></td>
						</tr>
					</table>
				</div>
			</div>

		<form name="f" method="post" action="" id="moazure_forgotpassword_form">
			<?php wp_nonce_field( 'moazure_forgotpassword_form', 'moazure_forgotpassword_form_field' ); ?>
			<input type="hidden" name="option" value="moazure_forgot_password_form_option"/>
		</form>
		<script>
			jQuery("a[href=\"#moazure_forgot_password_link\"]").click(function(){
				jQuery("#moazure_forgotpassword_form").submit();
			});
		</script>

		<?php
}
