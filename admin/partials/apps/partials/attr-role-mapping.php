<?php
/**
 * Attribute Mapping
 *
 * @package    attribute-mapping
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Display Attribute Mapping
 */
function moazure_attribute_role_mapping_ui() {
	$appslist       = MOAzure_Admin_Utils::moazure_get_option( 'moazure_oauth_sso_config' );
	$attr_name_list = MOAzure_Admin_Utils::moazure_get_option( 'moazure_test_attributes' );

	if ( false !== $attr_name_list ) {
		$temp           = array();
		$attr_name_list = moazure_dropdown_attrmapping( '', $attr_name_list, $temp );
	}
	$currentapp     = array();
	$currentappname = '';
	if ( is_array( $appslist ) ) {
		foreach ( $appslist as $key => $value ) {
			$currentapp     = $value;
			$currentappname = $key;
			break;
		}
	}

	$default_role = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_default_role' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_default_role' ) : MOAzure_Admin_Utils::moazure_get_option( 'default_role' );

	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
	$appparam = ! empty( $_GET['app'] ) ? sanitize_text_field( wp_unslash( $_GET['app'] ) ) : ( ! empty( $currentapp['apptype'] ) ? sanitize_text_field( wp_unslash( $currentapp['apptype'] ) ) : '' );
	$apptype  = ! empty( $currentapp['apptype'] ) ? sanitize_text_field( wp_unslash( $currentapp['apptype'] ) ) : '';

	?>
	<div class="moazure_table_layout moazure_attribute_page_font moazure_outer_div" id="attribute-mapping">
		<form id="form-common" name="form-common" method="post" action="admin.php?page=moazure_settings&tab=attributemapping&app=<?php echo ( ! empty( $currentapp['apptype'] ) ) ? esc_attr( $currentapp['apptype'] ) : 'entra-id'; ?>">
			<?php wp_nonce_field( 'moazure_attr_role_mapping_form', 'moazure_attr_role_mapping_form_field' ); ?>
			<div class="moazure_attribute_map_header moazure-flex" style="padding-bottom: 0rem">
				<div>
					<div class="moazure_attribute_map_heading">
						<?php esc_html_e( 'Attribute Mapping ', 'all-in-one-microsoft' ); ?><br>
					</div>
					<p class="moazure_desc" style="font-style: normal;">
					Attribute mapping allows you to link Azure AD user attributes to corresponding WordPress user fields, ensuring synchronization of user data during Single Sign-On.
					</p>
				</div>
				<div>
					<div class="moazure_tooltip moazure_tooltip_float_right">
						<span class="mo_tooltiptext" style="bottom: 110%;" >How to map Attributes?</span>
						<a href="https://developers.miniorange.com/docs/oauth/wordpress/client/attribute-mapping" target="_blank" rel="noopener">
							<img class="moazure_guide_img" style="margin:0px;" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/moazure_info-icon.png" alt="miniOrange Premium Plans Logo" aria-hidden="true">
						</a>
					</div>
				</div>
			</div>
			<div class="moazure-flex" style="justify-content: space-between;">
				<?php
				if ( empty( $attr_name_list ) || $apptype !== $appparam ) {
					?>
					<div>	
						<p style="font-size:15px;"><?php wp_nonce_field( 'moazure_attr_role_mapping_form', 'moazure_attr_role_mapping_form_field' ); ?><?php esc_html_e( 'Perform ', 'all-in-one-microsoft' ); ?><b style="color:#dc2424;"><?php esc_html_e( 'Test Configuration', 'all-in-one-microsoft' ); ?></b><?php esc_html_e( ' first to map Azure attributes to the WordPress attributes.', 'all-in-one-microsoft' ); ?><br></p>
					</div>
					<?php
				}
				?>
			</div>
			<input type="hidden" name="option" value="moazure_attribute_mapping" />
			<input type="hidden" name="moazure_app_name" value="<?php echo esc_attr( $currentappname ); ?>">

			<table class="moazure_configure_table mo_settings_table" style="margin-left: -20px;">
				<tr id="moazure_email_attr_div">
					<td class="td_entra_app">
						<strong class="mo_strong"><?php esc_html_e( 'Username', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :</strong>
						<p class="moazure_desc">This will set the WordPress username based on the corresponding Azure attribute that you've mapped to it</p>
					</td>
					<td class="td_entra_app">
					<?php
					if ( ! empty( $attr_name_list ) && is_array( $attr_name_list ) ) {
						?>
						<select class=""
						<?php
						if ( MOAzure_Admin_Utils::moazure_get_option( 'moazure_attr_option' ) === 'manual' ) {
							echo 'style="display:none"';}
						?>
						id="moazure_username_attr_select" 
						<?php
						if ( MOAzure_Admin_Utils::moazure_get_option( 'moazure_attr_option' ) === false || MOAzure_Admin_Utils::moazure_get_option( 'moazure_attr_option' ) === 'automatic' ) {
							echo 'name="moazure_username_attr"';}
						?>
						>
							<option value="">----------- Select an Attribute -----------</option>
							<?php
							if ( ! empty( $attr_name_list ) && $apptype === $appparam ) {
								foreach ( $attr_name_list as $key => $value ) {
									echo "<option value='" . esc_attr( $value ) . "'";
									//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
									if ( ( ( ! empty( $currentapp['username_attr'] ) && $currentapp['username_attr'] === $value ) || ( ! empty( $currentapp['email_attr'] ) && $currentapp['email_attr'] === $value ) ) && $currentapp['apptype'] === $_GET['app'] ) {
										echo ' selected';
									} else {
										echo '';
									}
									echo ' >' . esc_attr( $value ) . '</option>';
								}
							}
							?>
						</select>
						<?php
					} else {
						?>
						<input class="mo_settings_table" required="" placeholder="Enter attribute name for Username" type="text" id="moazure_username_attr_input" 
						<?php
						if ( empty( $currentapp ) && ! is_array( $attr_name_list ) ) {
							echo 'disabled';}
						?>
						name="moazure_username_attr" value="<?php echo ( ! empty( $currentapp['username_attr'] ) ? esc_attr( $currentapp['username_attr'] ) : '' ); ?>" />
						</td>
						<td>
						</td>
						<?php
					}
					?>
			</tr>
			<?php
			echo '<tr id="mo_oauth_name_attr_div">
					<td><strong class="mo_strong">' . esc_html__( 'First Name:', 'all-in-one-microsoft' ) . '</strong></td>
					<td class="td_entra_app"><input class="moazure_input_disabled" required="" placeholder="' . esc_html__( 'Enter attribute name for First Name', 'all-in-one-microsoft' ) . '" disabled  type="text" value=""></td>
				</tr>
			<tr>
				<td><strong class="mo_strong">' . esc_html__( 'Last Name:', 'all-in-one-microsoft' ) . '</strong></td>
				<td class="td_entra_app"><input type="text" class="moazure_input_disabled" placeholder="' . esc_html__( 'Enter attribute name for Last Name', 'all-in-one-microsoft' ) . '"  disabled /></td>
			</tr>
			<tr>
				<td><strong class="mo_strong">' . esc_html__( 'Email:', 'all-in-one-microsoft' ) . '</strong></td>
				<td class="td_entra_app"><input type="text" class="moazure_input_disabled" placeholder="' . esc_html__( 'Enter attribute name for Email', 'all-in-one-microsoft' ) . '"  value="" disabled /></td>
			</tr>
			<tr>
				<td><strong class="mo_strong">' . esc_html__( 'Group/Role:', 'all-in-one-microsoft' ) . '</strong></td>
				<td class="td_entra_app"><input type="text" class="moazure_input_disabled" placeholder="' . esc_html__( 'Enter attribute name for Group/Role', 'all-in-one-microsoft' ) . '" value="" disabled /></td>
			</tr>
			<tr>
				<td><strong class="mo_strong">' . esc_html__( 'Display Name:', 'all-in-one-microsoft' ) . '</strong></td>
				<td class="td_entra_app"><input type="text" class="moazure_input_disabled" placeholder="' . esc_html__( 'Username', 'all-in-one-microsoft' ) . '" value="" disabled /></td>
			</tr>
			<tr>
				<td><strong class="mo_strong">' . esc_html__( ' Enable Role Mapping:', 'all-in-one-microsoft' ) . '</strong></td>
				<td class="td_entra_app"><input type="checkbox" class="mo_input_checkbox moazure_input_disabled" checked disabled></td>
			</tr>
			<tr>
				<td><strong class="mo_strong">' . esc_html__( ' Allow Duplicate Emails:', 'all-in-one-microsoft' ) . '</strong></td>
				<td class="td_entra_app"><input type="checkbox" class="mo_input_checkbox moazure_input_disabled" disabled></td>
			</tr>
			<tr><td colspan="3"><hr class="mo-divider"></td></tr>
			<tr></tr>
			<tr>
			<td  colspan="2">
				<h3 class="moazure_attribute_page_font">' . esc_html__( 'Map Custom Attributes ', 'all-in-one-microsoft' ) . '
					<a style="text-decoration: none;" target="_blank" href="https://plugins.miniorange.com/wordpress-azure-office365-integrations#pricing-plans" rel="noopener noreferrer">
						<span>
							<img class="moazure_premium-label" src="' . esc_url( dirname( plugin_dir_url( __FILE__ ) ) ) . '/images/moazure_premium-label.png" alt="miniOrange Premium Plans Logo">
						</span>
					</a>
				</h3>
			</td>
			<td><span style="float: right;"><div class="moazure_tooltip moazure_tooltip_float_right"><span class="mo_tooltiptext"  >How to map Custom Attributes?</span><a
                href="https://developers.miniorange.com/docs/oauth/wordpress/client/attribute-mapping#custom-attr-map" target="_blank"
                rel="noopener"><img class="moazure_guide_img" src="' . esc_url( dirname( plugin_dir_url( __FILE__ ) ) ) . '/images/moazure_info-icon.png" alt="miniOrange Premium Plans Logo" aria-hidden="true"></a></div>
            </span></td>
			</tr>
			<tr><td  colspan="2">
			<p>' . esc_html__( 'Map extra Azure attributes which you wish to be included in the user profile below', 'all-in-one-microsoft' ) . '</p></td>
			<td><span style="float: right;"><input disabled type="button" value="+" class="button button-primary mo_disabled_btn"  /><input disabled type="button" value="-" class="button button-primary mo_disabled_btn"   />
            </span></td>
			</tr>
			<tr><td style="width="30%"><input disabled class="moazure_input_disabled" type="text" placeholder="' . esc_html__( 'Enter field meta name', 'all-in-one-microsoft' ) . '" /></td>
			<td><input disabled type="text" placeholder="' . esc_html__( 'Enter attribute name from OAuth Provider', 'all-in-one-microsoft' ) . '" class="moazure_input_disabled" /></td>
			</tr>';
		?>
			<tr><td>
			<br>
			<input type="submit" name="submit" value="<?php esc_html_e( 'Save settings', 'all-in-one-microsoft' ); ?>"
			class="button button-large moazure_configure_btn" />
			</td></tr>
			</table>
		</form>
		</div>
		<div class="moazure_table_layout moazure_attribute_page_font moazure_outer_div" id="role-mapping">
		<div class="moazure_customization_header moazure-flex">
		<h3 class="moazure_signing_heading" style="margin-top:0px; margin-bottom:0px;"><?php esc_html_e( 'Role Mapping ', 'all-in-one-microsoft' ); ?></h3>
		<span>
			<div class="moazure_tooltip">
				<span class="mo_tooltiptext" style="bottom: 110%;">How to map Roles?</span>
				<a href="https://developers.miniorange.com/docs/oauth/wordpress/client/role-mapping" target="_blank" rel="noopener"><img class="moazure_guide_img" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/moazure_info-icon.png" alt="miniOrange Premium Plans Logo" aria-hidden="true"></a>
			</div>
		</span>
		</div><br>
		<p class="moazure_upgrade_warning" style="text-align: center; padding:12px"><b>NOTE: </b><?php esc_html_e( 'Default role will be assigned only to new users. You will have to manually change the role of Existing users.', 'all-in-one-microsoft' ); ?></p>
		<form id="role_mapping_form" name="f" method="post" action="">
		<?php wp_nonce_field( 'moazure_role_mapping_form_nonce', 'moazure_role_mapping_form_field' ); ?>
		<input class="mo_table_textbox moazure_input_disabled" required="" type="hidden"  name="moazure_app_name" value="<?php echo esc_attr( $currentappname ); ?>">
		<input class="moazure_input_disabled" type="hidden" name="option" value="moazure_save_role_mapping" />
		<div class="moazure-flex">
			<div class="" style="margin: 2rem 0;width: 26%;">
				<strong class="mo_strong">Default Role : </strong>
			</div>
			<div class="">
				<select id="moazure_def_role" name="moazure_default_role">
					<?php
					wp_dropdown_roles( $default_role );
					?>
				</select>
			</div>
		</div>
		<p><input disabled type="checkbox" class="mo_input_checkbox moazure_input_disabled"/><strong class="mo_strong"><?php esc_html_e( ' Keep existing user roles', 'all-in-one-microsoft' ); ?></strong>&nbsp;&nbsp;<small><?php esc_html_e( '( Role mapping won\'t apply to existing WordPress users )', 'all-in-one-microsoft' ); ?></small></p>
		<p><input disabled type="checkbox" class="mo_input_checkbox moazure_input_disabled" > <strong class="mo_strong"><?php esc_html_e( ' Do Not allow login if roles are not mapped here ', 'all-in-one-microsoft' ); ?></strong>&nbsp;&nbsp;<small><?php esc_html_e( '( We won\'t allow users to login if we don\'t find users role/group mapped below. )', 'all-in-one-microsoft' ); ?></small></p>
		<p><input disabled type="checkbox" class="mo_input_checkbox moazure_input_disabled" > <strong class="mo_strong"><?php esc_html_e( ' Role Mapping based on Email Domain ', 'all-in-one-microsoft' ); ?></strong></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><?php esc_html_e( '( This feature allows to map the roles based on email domain of the user when the email attribute is configured in Group Attributes Name. )', 'all-in-one-microsoft' ); ?></small></p>
		<div id="panel1">
			<table class="moazure_mapping_table" id="moazure_role_mapping_table" style="width:90%">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td style="width:50%"><b><?php esc_html_e( 'Group Attribute Value', 'all-in-one-microsoft' ); ?></b></td>
					<td style="width:50%"><b><?php esc_html_e( 'WordPress Role', 'all-in-one-microsoft' ); ?></b></td>
				</tr>
				<tr>
					<td><input disabled class="moazure_table_textbox moazure_input_disabled" type="text" placeholder="<?php esc_html_e( 'group name', 'all-in-one-microsoft' ); ?>" />
					</td>
					<td>
						<select disabled class="moazure_input_disabled" style="width:100%"  >
							<option><?php esc_html_e( 'Subscriber', 'all-in-one-microsoft' ); ?></option>
						</select>
					</td>
				</tr>
				</table>
				</br>
				<table class="moazure_mapping_table" style="width:90%;">
					<tr><td><a style="cursor:not-allowed"><u><?php esc_html_e( 'Add More Mapping', 'all-in-one-microsoft' ); ?></u></a><br><br></td><td>&nbsp;</td></tr>
					<tr>
						<td><input type="submit" class="button button-large moazure_configure_btn" value="<?php esc_html_e( 'Save Settings', 'all-in-one-microsoft' ); ?>" /></td>
						<td>&nbsp;</td>
					</tr>
				</table>
				</div>
			</form>
		</div>
	<?php
}

/**
 * Get desired attribute value from resource owner details.
 *
 * @param mixed $nestedprefix get nextson json variable.
 * @param mixed $resource_owner_details userinfo of the user performing the SSO.
 * @param mixed $temp variable to store data of nested loop.
 */
function moazure_dropdown_attrmapping( $nestedprefix, $resource_owner_details, $temp ) {
	foreach ( $resource_owner_details as $key => $resource ) {
		if ( is_array( $resource ) ) {
			if ( ! empty( $nestedprefix ) ) {
				$nestedprefix .= '.';
			}
			$temp         = moazure_dropdown_attrmapping( $nestedprefix . $key, $resource, $temp );
			$nestedprefix = rtrim( $nestedprefix, '.' );
		} elseif ( ! empty( $nestedprefix ) ) {
			array_push( $temp, $nestedprefix . '.' . $key );
		} else {
			array_push( $temp, $key );
		}
	}
	return $temp;
}
