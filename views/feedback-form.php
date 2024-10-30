<?php
/**
 * Feedback Form
 *
 * @package    feedback-form
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Display Feedback form.
 */
function moazure_display_feedback_form() {
	if ( ! empty( $_SERVER['PHP_SELF'] ) && 'plugins.php' !== basename( sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) ) ) {
		return;
	}
	$deactivate_reasons = array(
		' Issues with SSO Setup',
		' Upgrading to Paid version',
		' Would like to go on a call with expert',
		' Would like to test a premium plugin',
		' Other Reasons',
	);
	wp_enqueue_style( 'wp-pointer' );
	wp_enqueue_script( 'wp-pointer' );
	wp_enqueue_script( 'utils' );
	wp_enqueue_style( 'moazure_admin_settings_style', plugin_dir_url( dirname( __FILE__ ) ) . '/admin/css/style_settings.min.css', array(), MO_AZURE_CSS_JS_VERSION );
	wp_enqueue_style( 'moazure_admin_settings_font_awesome', plugin_dir_url( dirname( __FILE__ ) ) . 'css/font-awesome.min.css', array(), '4.6.2' );
	$keep_settings_intact = true;
	?>

	<div id="moazure_feedback_modal" class="moazure_modal" style="margin: auto; text-align: center;">
		<div class="moazure_div_inside_modal">
			<h3>
				<b>Feedback Form</b>
				<span class="mo_close" id="moazure_close">&times;</span>
			</h3>
			<form name="f" method="post" action="" id="moazure_client_feedback">
				<?php wp_nonce_field( 'moazure_feedback_form', 'moazure_feedback_form_field' ); ?>
				<input type="hidden" name="moazure_client_feedback" value="true"/>
				<div class="moazure-idp-keep-conf-intact" id="mo_idp_keep_configuration_intact">
						<b>Keep Configuration Intact</b>
						<label class="moazure-switch">
							<input type="checkbox" class="mo_input_checkbox" name="moazure_keep_settings_intact" id="keepSettingsIntact" <?php echo esc_attr( $keep_settings_intact ) ? 'checked' : ''; ?>>
							<span class="moazure-slider moazure-round"></span>
						</label>
						<p style="margin:0rem;" class="mo_idp_keep_configuration_intact_descr">Enabling this would keep your settings intact when plugin is uninstalled. Please enable this option when you are updating to a Premium version.</p>
					</div>
				<div class="moazure-reaction">
					<div align="center">
						<div id="moazure_smi_rate" style="text-align:center" >
							<input class="moazure_rating_face moazure_radio_type" type="radio" name="rate" id="moazure_angry" value="1"/>
							<label for="moazure_angry"><img class="moazure_feedback_face" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . 'images/angry.png'; ?>" />
							</label>
							<input class="moazure_rating_face moazure_radio_type" type="radio" name="rate" id="moazure_sad" value="2"/>
							<label for="moazure_sad"><img class="moazure_feedback_face" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . 'images/sad.png'; ?>" />
							</label>
							<input class="moazure_rating_face moazure_radio_type" type="radio" name="rate" id="moazure_neutral" value="3"/>
							<label for="moazure_neutral"><img class="moazure_feedback_face" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . 'images/normal.png'; ?>" />
							</label>
							<input class="moazure_rating_face moazure_radio_type" type="radio" name="rate" id="moazure_smile" value="4"/>
							<label for="moazure_smile">
							<img class="moazure_feedback_face" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . 'images/smile.png'; ?>" />
							</label>
							<input class="moazure_rating_face moazure_radio_type" type="radio" name="rate" id="moazure_happy" value="5" checked/>
							<label for="moazure_happy"><img class="moazure_feedback_face" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . 'images/happy.png'; ?>" />
							</label>
						</div>

					<h4>Tell us what happened?<br></h4>

					<fieldset>
					<table style="width:85%;">
				<?php
					$count = 0;
				foreach ( $deactivate_reasons as $deactivate_reason ) {
					if ( 0 === $count ) {
						echo '<tr>';
						echo '<td class="mo_reason"><input type="radio" class="moazure_radio_type" name="moazure_deactivate_reason_select" id = "' . esc_attr( $deactivate_reason ) . '" value="' . esc_attr( $deactivate_reason ) . '" style="text-align:center; text-align-last: center;"><label for="' . esc_attr( $deactivate_reason ) . '">' . esc_attr( $deactivate_reason ) . '</label></td>';
						++$count;
					} elseif ( 1 === $count ) {
						echo '<td class="mo_reason"><input type="radio" class="moazure_radio_type" name="moazure_deactivate_reason_select" id = "' . esc_attr( $deactivate_reason ) . '" value="' . esc_attr( $deactivate_reason ) . '" style="text-align:center; text-align-last: center;"';
						echo checked( esc_attr( $deactivate_reason ) === ' Other Reasons' ) . ' ';
						echo ' ><label for="' . esc_attr( $deactivate_reason ) . '">' . esc_attr( $deactivate_reason ) . '</label></td>';
						echo '</tr>';
						$count = 0;
					}
				}
				?>
					</table>
					</fieldset>
					<textarea id="moazure_query_feedback" name="query_feedback" rows="3" style="margin: 10px -5px; width: 80%;" placeholder="Write your query here.."></textarea>
					<?php
					$email = get_option( 'moazure_admin_email' );
					if ( empty( $email ) ) {
						$user  = wp_get_current_user();
						$email = $user->user_email;
					}
					?>
					<div>
						<input type="email" id="moazure_query_mail" name="query_mail" style="margin-bottom: 10px; text-align:center; border:0px solid black; background:#f0f3f7; width:60%;" placeholder="your email address" required value="<?php echo esc_attr( $email ); ?>" readonly="readonly"/>
						<i class="fa fa-pencil" onclick="moazure_editName()" style="margin-left: -3%; cursor:pointer;"></i>
						</div>
						<div style="color: #012970; font-style: oblique; width: 100%; margin-bottom: 2%;">
						<input type="checkbox" class="mo_input_checkbox" name="get_reply" value="reply" checked>miniOrange representative will reach out to you at the email-address entered above.</input>
						</div>
					</div></div>
					<div class="mo_modal-footer">
						<div style="width: 80%; margin: 0 auto;">
						<input id="moazure_skip_feedback" type="submit" onclick="remove_skip_required()" name="moazure_feedback_skip"
							class="button" style="float: left; font-weight:700; width: 20%; color:#012970;" value="Skip"/>
						<input type="submit" name="moazure_feedback_submit"
							class="button button-primary button-large moazure_feedback_btn" value="Submit"/></div>
					</div>
				</div>
			</form>
			<form name="f" method="post" action="" id="moazure_feedback_form_close">
				<?php wp_nonce_field( 'moazure_skip_feedback_form', 'moazure_skip_feedback_form_field' ); ?>
				<input type="hidden" name="option" value="moazure_skip_feedback"/>
			</form>
		</div>
	</div>
	<script>

		function moazure_editName(){
			document.querySelector('#moazure_query_mail').removeAttribute('readonly');
			document.querySelector('#moazure_query_mail').focus();
			return false;
		}

		function remove_skip_required(){
			document.querySelector( '#moazure_deactivate_reason_select' ).removeAttribute( 'required' );
			return false;
		}

		jQuery('a[aria-label="Deactivate All-in-One Microsoft Office 365 Apps + Azure/EntraID Login"]').click(function () {
			var moazure_modal = document.getElementById('moazure_feedback_modal');
			var moazure_skip_feedback = document.getElementById('moazure_skip_feedback');
			var moazure_close = document.getElementById("moazure_close");
			moazure_modal.style.display = "block";

			moazure_close.onclick = function () {
				moazure_modal.style.display = "none";
				jQuery('#moazure_feedback_form_close').close();
			}

			window.onclick = function (event) {
				if (event.target == moazure_modal) {
					moazure_modal.style.display = "none";
				}
			}
			return false;

		});
	</script>
	<?php
}

?>
