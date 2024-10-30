<?php
/**
 * Customization
 *
 * @package    apps
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Display Customizations options for login button
 */
function moazure_customization_ui() {
	wp_enqueue_script( 'moazure_customize_icon_tab', esc_url( plugins_url( 'customization.min.js', __FILE__ ) ), array(), MO_AZURE_CSS_JS_VERSION, false );
	?>
		<div id="moazure_customization" class="moazure_table_layout moazure_app_customization moazure_outer_div">
			<div class="moazure_customization_header moazure-flex">
				<div class="moazure_attribute_map_heading">
					<b class="moazure_position">
						<?php esc_html_e( 'Customize Icons ', 'all-in-one-microsoft' ); ?>
					</b>
				</div>
				<div class="moazure_tooltip">
					<span class="mo_tooltiptext">Know how this is useful</span>
					<a style="text-decoration: none;" target="_blank" href="https://developers.miniorange.com/docs/oauth/wordpress/client/login-button-customization" rel="noopener noreferrer">
						<img class="moazure_guide_img" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/images/moazure_info-icon.png' ); ?>" alt="miniOrange Premium Plans Logo" aria-hidden="true">
					</a>
				</div>
			</div>

		<form id="form-common" name="form-common" class="moazure_customization_font" method="" action="admin.php?page=moazure_settings&tab=customization&app=
		<?php
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
		echo ! empty( $_GET['app'] ) ? esc_attr( $_GET['app'] ) : 'entra-id';
		?>
		">
			<div class="moazure-flex" style="text-align:center">
				<h2 id="moazure_customize_icon" class="moazure_switching_tab mo_active_div_css moazure-rad">
					<?php esc_html_e( 'Customize SSO button', 'all-in-one-microsoft' ); ?>
				</h2>
				<h2 id="mo_oauth_write_custom_code" class="moazure_switching_tab moazure-rad" style="border-bottom: 1px solid rgb(51, 122, 183);">
					<?php esc_html_e( 'Write your custom code', 'all-in-one-microsoft' ); ?>
				</h2>
			</div>
			<div class="moazure_custom_tab moazure_customize_SSO_buttons moazure-rad">
				<div class="moazure_custom_tab_item moazure_custom_tab_flex_grow">
					<div style="display:flex;margin: 1%;">
						<div class="moazure_custom_tab_item_2">
							<h3 class="moazure_h3_heading"> THEME </h3>
							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="moazure_icon_theme_default" name="moazure_icon_theme" value="default" onclick="moAzureIconsPreview(getArg()),moAzureThemeSelector(selectLoginTheme())">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Default</span>
							</label>

							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="mo_oauth_icon_theme_custom" name="moazure_icon_theme" value="custom"  onclick="moAzureIconsPreview(getArg()),moAzureThemeSelector(selectLoginTheme())">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Custom</span><br>
								<input type="color" class="moazure_custom_tab_margin_2 " style="margin: 10px 25px 20px !important;" id="mo_oauth_icon_color" name="mo_oauth_icon_color"  onclick="moAzureIconsPreview(getArg())">
							</label>

							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="mo_oauth_icon_theme_white" name="moazure_icon_theme" value="white"  onclick="moAzureIconsPreview(getArg()),moAzureThemeSelector(selectLoginTheme())">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">White</span>
							</label>

							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="mo_oauth_icon_theme_hover" name="moazure_icon_theme" value="hover"  onclick="moAzureIconsPreview(getArg()),moAzureThemeSelector(selectLoginTheme())">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Hover</span>
							</label>

							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="moazure_icon_theme_custom_hover" name="moazure_icon_theme" value="customhover"  onclick="moAzureIconsPreview(getArg()),moAzureThemeSelector(selectLoginTheme())">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Custom Hover</span><br>
								<input type="color" class="moazure_custom_tab_margin_2 " id="moazure_icon_custom_color" style="margin: 10px 25px 20px !important;" name="moazure_icon_custom_color" value="#008ec2" onclick="moAzureIconsPreview(getArg())">
							</label>

							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="moazure_icon_theme_smart" name="moazure_icon_theme" value="smart"  onclick="moAzureIconsPreview(getArg()),moAzureThemeSelector(selectLoginTheme())" checked="checked">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Smart</span><br>
								<input type="color" class="moazure_custom_tab_margin_2 " id="moazure_icon_smart_color_1" style="margin: 10px 15px 20px !important" name="moazure_icon_smart_color_1" value="#ff1f4b"  onclick="moAzureIconsPreview(getArg())">
								<br>
								<input type="color" class="moazure_custom_tab_margin_2 " id="moazure_icon_smart_color_2" style="margin: 10px 15px 20px !important;" name="moazure_icon_smart_color_2" value="#2008ff" onclick="moAzureIconsPreview(getArg())">
							</label>

							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="moazure_icon_theme_previous" name="moazure_icon_theme" value="previous"  onclick="moAzureIconsPreview(getArg()),moAzureThemeSelector(selectLoginTheme())">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Previous</span>
							</label>
						</div>

						<div class="moazure_custom_tab_item_2">
							<h3 class="moazure_h3_heading"> SHAPE </h3>
							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="moazure_icon_shape_round" name="moazure_icon_shape" value="circle"  onclick="moAzureIconsPreview(getArg()),moAzureShapeHandler()">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Round</span>
							</label>
							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="moazure_icon_shape_oval" name="moazure_icon_shape" value="oval"  onclick="moAzureIconsPreview(getArg()),moAzureShapeHandler()">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Round Edge</span>
							</label>
							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="moazure_icon_shape_square" name="moazure_icon_shape" value="square"  onclick="moAzureIconsPreview(getArg()),moAzureShapeHandler()">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Square</span>
							</label>
							<label>
								<input type="radio" class="moazure_custom_tab_margin_2 " id="moazure_icon_shape_longbutton" name="moazure_icon_shape" value="longbutton"  onclick="moAzureIconsPreview(getArg()),moAzureShapeHandler()" checked="checked">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Long Button</span>
							</label>
							<hr>
							<h3 class="moazure_h3_heading"> Effect </h3>
							<label>
								<input type="checkbox" class="moazure_custom_tab_margin_2 " id="mo_oauth_icon_effect_scale" name="mo_oauth_icon_effect_scale" value="scale"  onclick="moAzureIconsPreview(getArg())" checked="checked">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Transform</span>
							</label>
							<label>
								<input type="checkbox" class="moazure_custom_tab_margin_2 " id="mo_oauth_icon_effect_shadow" name="mo_oauth_icon_effect_shadow" value="shadow"  onclick="moAzureIconsPreview(getArg())" checked="checked">
								<span style="font-size: 1.1em;font-weight: 600; margin:5px 0px 0px 5px;">Shadow</span>
							</label>
						</div>
					</div>

					<div style="display:flex;margin: 1%; ">
						<div id="moazure_longbutton_parameter" class="moazure_custom_tab_item_3">
							<h3 class="moazure_h3_heading"> Size of the Icons </h3>
							<label class="moazure_custom_tab_margin_3">
								<span style="font-size: 1.1em;font-weight: 600;">Height&nbsp;: </span><br>
								<input type="text" id="moazure_icon_height" name="moazure_icon_height" value="35" style="width: 30%;margin-left: 5px;">
								<input type="button" id="moazure_height_plus" class="moazure_icon_dimension" style="margin-left: 2px;" value="+" onclick=" moAzureIconsPreview(getArg())">&nbsp;
								<input type="button" id="moazure_height_minus" class="moazure_icon_dimension" value="-" onclick="moAzureIconsPreview(getArg())">
							</label>
							<label class="moazure_custom_tab_margin_3">
								<span style="font-size: 1.1em;font-weight: 600;">Width&nbsp;&nbsp;: </span><br>
								<input type="text" id="moazure_icon_width" name="moazure_icon_width" value="260" style="width: 30%;margin-left: 5px;">
								<input type="button" id="moazure_width_plus" class="moazure_icon_dimension" value="+" onclick="moAzureIconsPreview(getArg())">&nbsp;
								<input type="button" id="moazure_width_minus" class="moazure_icon_dimension" value="-" onclick="moAzureIconsPreview(getArg())">
							</label>
							<label class="moazure_custom_tab_margin_3">
								<span style="font-size: 1.1em;font-weight: 600;">Curve &nbsp;&nbsp;: </span><br>
								<input type="text" id="moazure_icon_curve" name="moazure_icon_curve" value="6" style="width: 30%;margin-left: 5px;">
								<input type="button" id="moazure_curve_plus" class="moazure_icon_dimension" value="+" onclick=" moAzureIconsPreview(getArg())">&nbsp;
								<input type="button" id="moazure_curve_minus" class="moazure_icon_dimension" value="-" onclick="moAzureIconsPreview(getArg())">
							</label>
						</div>
						<div id="moazure_button_parameter" class="moazure_custom_tab_item_3" style="display: none">
							<h3 class="moazure_h3_heading"> Size of the Icons </h3>
							<label>
								<span style="font-size: 1.1em;font-weight: 600;">Icon Size : </span>
								<input type="text" id="moazure_icon_size" name=" moazure_icon_size" value="40" style="width: 30%; margin-left: 5px;">
								<input type="button" id="moazure_icon_plus" class="moazure_icon_dimension" value="+" onclick="moAzureIconsPreview(getArg())">&nbsp;
								<input type="button" id="moazure_icon_minus" class="moazure_icon_dimension" value="-" onclick="moAzureIconsPreview(getArg())">
							</label>
						</div>
						<div class="moazure_custom_tab_item_3">
							<h3 class="moazure_h3_heading"> Space Between the Icons </h3>
							<label class="moazure_custom_tab_margin_2">
								<input type="text" id="moazure_icon_margin" name=" moazure_icon_margin" value="10" style="width: 30%;margin-left: 5px;">
								<input type="button" id="moazure_space_icon_plus" class="moazure_icon_dimension" value="+" onclick="moAzureIconsPreview(getArg())">&nbsp;<input type="button" id="moazure_space_icon_minus" class="moazure_icon_dimension" value="-" onclick="moAzureIconsPreview(getArg())">
							</label>
						</div>
					</div>
				</div>

				<div class="moazure_custom_tab_item moazure_custom_tab_item_color moazure-rad">
					<?php
					$active_app = MOAzure_Admin_Utils::moazure_get_option( 'moazure_oauth_sso_config' );
					if ( ! MOAzure_Admin_Utils::moazure_get_option( 'moazure_oauth_sso_config' ) ) {
						?>
						<p>Please setup a SSO application.</p>
						<?php
					} else {
						?>
						<p class="moazure_customization_tab_notice moazure-rad"><strong>Note:-</strong>This feature is available in Standard and above plans.</p>
						<?php
						$app_details = MOAzure_Admin_Utils::moazure_get_option( 'moazure_oauth_sso_config' );
						$app_id      = array_key_first( $app_details );
						$displayname = 'Login with ' . $app_id;
						$appname     = $app_id;
						$icon        = '';
						$appname     = 'azure';

						?>
						<i id="moazure_default_icon_preview_<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $appname . 's.png' ); ?>" class=" fa moazure_default_icon_preview moazure_def_btn_<?php echo esc_attr( $appname ); ?>" >
							<img src=<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $appname . 's.png' ); ?> class="moazure_login_but_img">
							<span  class="moazure_login_button_font moazure_login_but_img_span"><?php echo esc_attr( $displayname ); ?></span>
						</i>
						<i id="moazure_custom_icon_preview_<?php echo esc_attr( $appname ); ?>" class=" fa moazure_custom_icon_preview " >
							<img src=<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $appname . 's.png' ); ?> class="moazure_login_but_img">
							<span  class="moazure_login_button_font moazure_login_but_img_span"><?php echo esc_attr( $displayname ); ?></span>
						</i>
						<i id="moazure_white_icon_preview_<?php echo esc_attr( $appname ); ?>" class=" fa moazure_white_icon_preview moazure_white_btn_<?php echo esc_attr( $appname ); ?>" >
							<img src=<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $appname . '.png' ); ?> class="moazure_login_but_img">
							<span  class="moazure_login_button_font moazure_login_but_img_span"><?php echo esc_attr( $displayname ); ?></span>
						</i>
						<i id="moazure_hover_icon_preview_<?php echo esc_attr( $appname ); ?>" class=" fa moazure_hover_icon_preview moazure_hov_btn_<?php echo esc_attr( $appname ); ?>">
							<img src=<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $appname . '.png' ); ?> class="moazure_login_but_img without_hover">
							<img src=<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $appname . 's.png' ); ?> class="moazure_login_but_img with_hover" style="display: none">
							<span  class="moazure_login_button_font moazure_login_but_img_span"><?php echo esc_attr( $displayname ); ?></span>
						</i>
						<i id="moazure_custom_hover_icon_preview_<?php echo esc_attr( $appname ); ?>" class=" fa moazure_custom_hover_icon_preview ">
							<img src=<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $appname . 's.png' ); ?> class="mo_oauth_login_but_custom_img without_hover">
							<img src=<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $appname . 's.png' ); ?> class="mo_oauth_login_but_custom_img with_hover" style="display: none">
							<span  class="moazure_login_button_font moazure_login_but_img_span"><?php echo esc_attr( $displayname ); ?></span>
						</i>
						<i id="moazure_smart_icon_preview_<?php echo esc_attr( $appname ); ?>" class=" fa moazure_smart_icon_preview " >
							<img src=<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $appname . 's.png' ); ?> class="moazure_login_but_img">
							<span  class="moazure_login_button_font moazure_login_but_img_span"><?php echo esc_attr( $displayname ); ?></span>
						</i>
						<i id="moazure_previous_icon_preview_<?php echo esc_attr( $appname ); ?>" class="fa fa-<?php echo 'lock'; ?> moazure_previous_icon_preview mo_oauth_lock_pad">
							<span  class="moazure_login_button_font"><?php echo esc_attr( $displayname ); ?></span>
						</i>
						<?php
					}
					?>
				</div>
			</div>

			<div class="moazure_write_custom_code_tab moazure_customize_SSO_buttons moazure-rad">
				<p style="font-weight: 600;color:red;text-align:center;font-size:initial">
					<?php esc_html_e( 'Save the settings to see the preview of the Custom CSS', 'all-in-one-microsoft' ); ?>
				</p>
				<table  class="mo_settings_table" >
					<tr>
						<td>
							<strong><?php esc_html_e( 'Custom CSS:', 'all-in-one-microsoft' ); ?></strong>
						</td>
						<td>							
						<br/>
							<textarea disabled type="text" class="moazure_input_disabled" id="moazure_icon_configure_css" style="resize: vertical; width:400px; height:150px;  margin:5% auto;" rows="6" name="moazure_icon_configure_css"></textarea>
							<br/>
							<strong><?php esc_html_e( 'Example CSS:', 'all-in-one-microsoft' ); ?></strong>
<pre>
	background: #7272dc;
	height:40px;
	width:300px;
	padding:8px;
	text-align:center;
	color:#fff;
</pre>
						</td>
					</tr>
					<tr>
						<td>
							<strong><?php esc_html_e( 'Logout Button Text:', 'all-in-one-microsoft' ); ?> </strong>
						</td>
						<td>
							<input disabled style="width:300px" type="text" class="moazure_input_disabled" id="moazure_custom_logout_text" name="moazure_custom_logout_text" placeholder="Howdy, ##user##  ##Logout##">
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<br><strong><?php esc_html_e( 'Example:', 'all-in-one-microsoft' ); ?></strong>
<pre>
	Text you enter: Howdy ##user## ##Sign Out##
	Text displayed: Howdy (username)  <u>Sign Out</u>
</pre>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div class="moazure_outer_padding" style="padding: 15px 0px;">
				<table class="moazure_custom_settings_table">
					<div class="moazure_notice_label moazure-flex moazure-rad" style="margin: 0px auto;">
						<div>
							<h4 style="font-size: 1rem"><?php esc_html_e( 'Apply above customized settings to the wp-admin SSO buttons:', 'all-in-one-microsoft' ); ?>
							</h4>
						</div>
						<div>
							<label class="moazure_switch">
								<input value="1" type="checkbox" style="background: #dcdad1" name="mo_apply_customized_setting_on_wp_admin" />
								<span class="moazure_slider round "></span>
							</label>
						</div>
					</div>
				</table>

				<table class="moazure_custom_settings_table">
					<tr>
						<h4 style="font-size: 1.2em"><?php esc_html_e( 'Customize display name (special charactors are allowed.)', 'all-in-one-microsoft' ); ?></h4>
					</tr>
					<?php
					$appslist    = is_array( MOAzure_Admin_Utils::moazure_get_option( 'moazure_oauth_sso_config' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_oauth_sso_config' ) : array();
					$displayname = '';
					foreach ( $appslist as $key => $val ) {
						$displayname = $key;
					}
					?>
					<tr>
						<td>
							<strong>
								<label class="moazure_fix_fontsize"><?php esc_html_e( 'Enter text to display on your login buttons:', 'all-in-one-microsoft' ); ?></label>&nbsp;&nbsp;<?php echo esc_attr( $displayname ); ?>
							</strong>
						</td>
						<td>
							<input class="moazure_textfield_css moazure_input_disabled" style="border: 1px solid ; width: 350px;" type="text" placeholder="SSO with : "/>
						</td>
					</tr>
				</table>    
				<hr>
				<table class="moazure_custom_settings_table" id="mo_custom_icon_table">
					<tr>
						<h4 style="font-size: 1.2em">Upload Custom Icons :</h4>
					</tr>
						<?php
						$displayname = 'No App Configured';
						foreach ( $appslist as $key => $val ) {
							$displayname = $key;
						}
						?>
					<tr>
						<td><strong> Application </strong></td>
						<td><strong> Custom Image for Icon</strong></td>
					</tr>
					<tr id="mo_custom_icon" class="rows">
						<td>
							<select style="width: 55%;" name="<?php echo 'mo_custom_icon_file'; ?>" id="wp_icon_list" >
								<option value="">Select App from List</option>
								<option value=""><?php echo esc_attr( $displayname ); ?></option>
							</select>
						</td>
						<td>
							<input  type="file" id="mo_custom_icon" name="custom_icon[]" class="moazure_input_disabled">
						</td>
					</tr>
					<tr>
						<td>
							<h4>
								<a class="moazure_input_disabled" style="cursor:not-allowed" id="add_icon">Add More Icons</a>
							</h4>
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<hr>
				<table class="moazure_custom_settings_table">
					<tr>
						<h4 style="font-size: 1.2em">
							<?php esc_html_e( 'Customize Connect with text on WP Login page', 'all-in-one-microsoft' ); ?>
						</h4>
					</tr>
					<tr>
						<div style="width: 100%;">
							<td>
								<strong>
									<label class="moazure_fix_fontsize"><?php esc_html_e( 'Enter text to show above login widget:', 'all-in-one-microsoft' ); ?></label>
								</strong>
							</td>
							<td>
								<input class="moazure_textfield_css moazure_input_disabled" style="border: 1px solid ; width: 350px;"  type="text" name="moazure_widget_customize_text" Placeholder="Connect with :" />
							</td>
						</div>
					</tr>
				</table>
				<hr>
				<h4 style="font-size: 1.2em">
					<?php esc_html_e( 'Customize Text to show user after Login', 'all-in-one-microsoft' ); ?>
				</h4>
				<table class="moazure_custom_settings_table">
					<tbody>
						<tr>
							<td>
								<strong><?php esc_html_e( 'Customize Logout Text: (Anchor tags are allowed)', 'all-in-one-microsoft' ); ?></strong>
							</td>
							<td>
								<input class="moazure_input_disabled" style="width:350px; border: 1px solid" type="text" id="moazure_custom_logout_text" name="moazure_custom_logout_text" placeholder="Howdy, ##user##  ##Logout##" value="">
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<strong><?php esc_html_e( 'Example:', 'all-in-one-microsoft' ); ?></strong>
<pre>
	Text you enter: Howdy ##user## ##Sign Out##
	Text displayed: Howdy (username)  <u>Sign Out</u>
</pre>
							</td>
						</tr>
						<tr>
							<td>
								<strong><?php esc_html_e( 'With Logout Link: (If unchecked, remove logout link)', 'all-in-one-microsoft' ); ?></strong>
							</td>
							<td>
								<input type="checkbox" name="mo_custom_html_with_logout_link" value="1">
							</td>
						</tr>
					</tbody>
				</table>

				<table class="moazure_custom_settings_table">
					<tr>
						<td></td>
						<td>
							<input type="submit" id="button_submit"name="submit" value="<?php esc_html_e( 'Save Settings', 'all-in-one-microsoft' ); ?>" style="margin:20px 0px; padding:2px 25px;" class="button button-primary button-large mo_disabled_btn" />
						</td>
					</tr>
				</table>
			</div>
		</form>
	</div>

	<script>
		jQuery(document).ready(function () {
			jQuery(".moazure_custom_tab").css("display","flex");
			jQuery(".moazure_write_custom_code_tab").css("display","none");
			jQuery(".moazure_outer_padding input").prop("disabled", true);
			jQuery("#moazure_customize_icon").click(function (){
				jQuery(".moazure_custom_tab").css("display","flex");
				jQuery(".moazure_write_custom_code_tab").css("display","none");
				jQuery("#moazure_customize_icon").addClass("mo_active_div_css");
				jQuery("#mo_oauth_write_custom_code").removeClass("mo_active_div_css");
				jQuery("#mo_oauth_write_custom_code").css("border-bottom","1px solid rgb(51, 122, 183)");
			});

			jQuery("#mo_oauth_write_custom_code").click(function (){
				jQuery(".moazure_custom_tab").css("display","none");
				jQuery(".moazure_write_custom_code_tab").css("display","block");			
				jQuery("#moazure_customize_icon").css("border-bottom","1px solid rgb(51, 122, 183)");
				jQuery("#mo_oauth_write_custom_code").addClass("mo_active_div_css");
				jQuery("#moazure_customize_icon").removeClass("mo_active_div_css");
			});	
		});

	</script>
	<?php
}
