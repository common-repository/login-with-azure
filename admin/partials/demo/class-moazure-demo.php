<?php
/**
 * Demo
 *
 * @package    demo
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Handle demo requests
 */
class MOAzure_Demo {

	/**
	 * Request for demo
	 */
	public static function requestfordemo() {
		self::demo_request();
	}

	/**
	 * Display UI to make demo request
	 */
	public static function demo_request() {

		// Get WordPress version.
		global $wp_version;

		$wp_version_trim = substr( $wp_version, 0, 3 );
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
		$appparam = ! empty( $_GET['app'] ) ? sanitize_text_field( wp_unslash( $_GET['app'] ) ) : 'entra-id';

		$contact_email = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_admin_email' ) ) ? sanitize_email( MOAzure_Admin_Utils::moazure_get_option( 'moazure_admin_email' ) ) : sanitize_email( MOAzure_Admin_Utils::moazure_get_option( 'admin_email' ) );

		?>						
			<!-- VIDEO DEMO DOWN -->
			<div class="mo_demo_layout moazure_contact_heading moazure_outer_div">
				<div class="moazure_request_demo_header">
					<div class="moazure_attribute_map_heading">
						<?php esc_html_e( 'Schedule a Demo with our Experts', 'all-in-one-microsoft' ); ?>
					</div>
				</div>
				<div style="display: flex; padding: 25px 0px">
					<div class="moazure_video_demo_container_form">
						<form method="post" action="">
						<?php wp_nonce_field( 'moazure_video_demo_request_form', 'moazure_video_demo_request_field' ); ?>
							<input type="hidden" name="option" value="moazure_video_demo_request_form" />
							<table class="moazure_video_demo_table mo_demo_table_layout">
								<tr>
									<td>
										<div>
											<strong class="mo_strong">Email id <p style="display:inline;color:red;">*</p>: </strong>
										</div>
										<div>
											<input type="text" class="moazure_request_demo_inputs" placeholder="We will use this email to setup the demo for you" name="moazure_video_demo_email" value="<?php echo esc_attr( $contact_email ); ?>">
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<strong class="mo_strong">Date<p style="display:inline;color:red;">*</p>: </strong>
										</div>
										<div>
											<input type="date" class="moazure_request_demo_inputs" name="moazure_video_demo_request_date" placeholder="Enter the date for demo">
										</div>
									</td>	
								</tr>
								<tr>
									<td>
										<div>
											<strong class="mo_strong">Local Time<p style="display:inline;color:red;">*</p>: </strong>
										</div>
										<div>
											<input type="time" class="moazure_request_demo_inputs" placeholder="Enter your time" name="moazure_video_demo_request_time">
											<input type="hidden" name="moazure_video_demo_time_diff" id="moazure_video_demo_time_diff">
										</div>
									</td>
								</tr>
								<tr>
									<td style="color:grey;">Eg:- 12:56, 18:30, etc.</td>
								</tr>
								<tr id="integration-list">
									<td colspan="2">
										<p>
											<strong class="mo_strong"><?php esc_html_e( 'Select the Integrations you are interested in (Optional)', 'all-in-one-microsoft' ); ?> :</strong>
										</p>
										<blockquote class="moazure_blockquote">
											<i><strong class="mo_strong">(<?php esc_html_e( 'Note', 'all-in-one-microsoft' ); ?>: </strong> <?php esc_html_e( 'All-Inclusive plan entitles all the integrations in the license cost itself.', 'all-in-one-microsoft' ); ?> )</i>
										</blockquote>
										<table>
										<?php
										$count = 0;
										foreach ( MOAzure_Integrations::$all_integrations as $key => $value ) {
											if ( true === $value['in_allinclusive'] ) {
												if ( 0 === $count ) {
													?>
												<tr>
													<td>
														<input type="checkbox" class="mo_input_checkbox moazure_demo_form_checkbox" <?php echo ( $value['app'] === $appparam ) ? 'checked' : ''; ?> style="margin:7px 5px 7px 5px" name="<?php echo esc_attr( $value['tag'] ); ?>" value="true"> <?php echo esc_html( $value['title'] ); ?>
														<br/>
													</td>
													<?php
													++$count;
												} elseif ( 1 === $count ) {
													?>
													<td>
														<input type="checkbox" class="mo_input_checkbox moazure_demo_form_checkbox" <?php echo ( $value['app'] === $appparam ) ? 'checked' : ''; ?> style="margin:7px 5px 7px 5px" name="<?php echo esc_attr( $value['tag'] ); ?>" value="true"> <?php echo esc_html( $value['title'] ); ?>
														<br/>
													</td>
												</tr>
													<?php
													$count = 0;
												}
											}
										}
										?>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<strong class="mo_strong">Usecase/ Any comments:<p style="display:inline;color:red;">*</p>: </strong>
										</div>
										<div>
											<textarea name="moazure_video_demo_request_usecase_text" class="moazure_request_demo_inputs" style="resize: vertical; height:150px;" minlength="15" placeholder="Example. Login into WordPress using Entra ID credentials, Restrict gmail.com accounts to my WordPress site etc."></textarea>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<input type="submit" name="submit" value="<?php esc_html_e( 'Submit Demo Request', 'all-in-one-microsoft' ); ?>" class="button button-large moazure_demo_request_btn" />
									</td>
								</tr>
							</table>
						</div>
						<div class="moazure_video_demo_container_gif_section mo_demo_table_layout">
							<div class="moazure_demo_request_message">
								Your overview <a style="color:#012970"><strong class="mo_strong">Video Demo</strong></a> will include
							</div>
							<div class="">
								<img class="moazure_video_demo_gif" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '/img/demo-img.jpg'; ?>" width="80%" alt="mo-demo-jpg">
							</div>
							<div class="moazure_demo_bottom_message" >
								<strong class="mo_strong">
									You can set up a screen share meeting with our developers to walk you through our plugin featuers.
								</strong>
								<div class="moazure_demo_bottom_message">
									<img class="moazure_demo_icon" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '/img/check.png'; ?>"  alt="">
									Overview of Plugin with all paid features.
								</div>	
								<div style="margin-top:10px">
									<img class="moazure_demo_icon" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '/img/support.png'; ?>"  alt="">
									Guided demo from a Developer via screen share meeting.
								</div>
							</div>
						</div>
					</div>	
				</form>					
			</div>

			<script>
				var d = new Date();
				var n = d.getTimezoneOffset();
				document.getElementById("moazure_video_demo_time_diff").value = n;
			</script>
		<?php
	}
}
