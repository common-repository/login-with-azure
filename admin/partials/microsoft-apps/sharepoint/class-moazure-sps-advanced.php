<?php
/**
 * Sharepoint Advanced Settings tab file.
 *
 * @package    sharepoint
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Function to display the Advanced Settings tab under Sharepoint.
 */
class MOAzure_SPS_Advanced {

    /**
	 * Object variable.
	 *
	 * @var object variable to instantiate the class.
	 */
	private static $instance;

	/**
	 * Localized data variable.
	 *
	 * @var array variable to pass data to javascript.
	 */
	public $localized_data;

	/**
	 * Function to get the object of the class
	 *
	 * @return object
	 */
	public static function get_sps_advanced_obj() {
		if ( ! isset( self::$instance ) ) {
			$class          = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	/**
	 * Function to display the advanced settings tab of Sharepoint.
	 *
	 * @return void
	 */
    public function moazure_sps_advanced_settings() {
        $this->moazure_sps_media_library();
		$this->moazure_sps_sync_news_articles();
		$this->moazure_sps_roles_folders_restriction();
    }

	/**
	 * Function to display the media library section in advanced settings tab.
	 *
	 * @return void
	 */
	public function moazure_sps_media_library() {
		?>
		<div class="moazure_table_layout moazure_outer_div" id="gen_sc_div" style="display: <?php echo ! empty( $shortcodes ) ? 'none' : 'block'; ?>">
			<div>
				<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
					Access Sharepoint Docs from Media Library
				</h3>
				<p class="moazure_desc" style="font-style: normal;">
					Enabling this option allows you to access all your SharePoint online files and folders from media library
				</p>
			</div>
			<hr class='mo-divider'>
			<br>
			<br>
			<!-- <div class="moazure_contact_heading td_entra_app">
			</div> -->
			<div class="moazure_contact_heading moazure-flex" style="gap: 2rem;">
				<strong class="mo_strong">Enable to access SharePoint Documents to media library : </strong>
				<label class="moazure_switch" style="float: left;">
					<input class="mo_input_checkbox" id="toggleSwitch" type="checkbox" name="moazure_show_on_login_page" disabled />
					<span class="moazure_slider round"></span>
				</label>
			</div>
			<br>
		</div>
		<?php
	}

	/**
	 * Function to display the sync news and articles tab in the advanced settings tab.
	 *
	 * @return void
	 */
	public function moazure_sps_sync_news_articles() {
		?>
		<div class="moazure_table_layout moazure_outer_div" id="gen_sc_div" style="display: <?php echo ! empty( $shortcodes ) ? 'none' : 'block'; ?>">
			<div>
				<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
					Sync News & Articles
				</h3>
				<p class="moazure_desc" style="font-style: normal;">
					Sync All your SharePoint online news and articles into the WordPress posts
				</p>
			</div>
			<hr class='mo-divider'>
			<br>
			<br>
			<div class="moazure_contact_heading moazure-flex" style="gap: 2rem;">
				<strong class="mo_strong" style="width: 300px;">Enable to Sync SharePoint Social News : </strong>
				<label class="moazure_switch" style="float: left;">
					<input class="mo_input_checkbox" id="toggleSwitch" type="checkbox" name="moazure_show_on_login_page" disabled />
					<span class="moazure_slider round"></span>
				</label>
			</div>
			<br>
			<div class="moazure_contact_heading moazure-flex" style="gap: 2rem;">
				<strong class="mo_strong" style="width: 300px;">Enable to Sync Sync SharePoint Social Articles : </strong>
				<label class="moazure_switch" style="float: left;">
					<input class="mo_input_checkbox" id="toggleSwitch" type="checkbox" name="moazure_show_on_login_page" disabled />
					<span class="moazure_slider round"></span>
				</label>
			</div>
			<br>
		</div>
		<?php
	}

	/**
	 * Function to display the roles/folders restriction section in advanced settings tab.
	 *
	 * @return void
	 */
	public function moazure_sps_roles_folders_restriction() {

		$wp_roles = new WP_Roles();
		$roles    = $wp_roles->get_names();
		?>
		<div class="moazure_table_layout moazure_outer_div" id="gen_sc_div" style="display: <?php echo ! empty( $shortcodes ) ? 'none' : 'block'; ?>">
			<div>
				<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
					Roles / Folders Restriction
				</h3>
				<p class="moazure_desc" style="font-style: normal;">
					Map your WordPress Roles / BuddyPress Groups / Membership Levels to Sharepoint site URL of folders to restrict files and folders
				</p>
			</div>
			<hr class='mo-divider'>
			<div>
				<table class="moazure_configure_table mo_settings_table" style="margin-left: -20px;">
					<?php
					if ( is_array( $roles ) && ! empty( $roles ) ) {
						foreach ( $roles as $role_value => $role_name ) {
							$configured_role_value = empty( $roles_configured ) ? '' : $roles_configured[ $role_value ];
							?>
							<tr>
								<td><strong class="mo_strong"><?php echo esc_html( $role_name ); ?></strong></td>
								<td class="td_entra_app"><input class="moazure_input_disabled" required="" placeholder="Enter SharePoint Server Relative URL of Folders" disabled  type="text" value=""></td>
							</tr>
							<?php
						}
					} else {
						?>
						<tr id="mo_oauth_name_attr_div">
							<td><strong class="mo_strong">Administrator</strong></td>
							<td class="td_entra_app"><input type="text" class="moazure_input_disabled" placeholder="Enter SharePoint Server Relative URL of Folders" disabled /></td>
						</tr>
						<tr>
							<td><strong class="mo_strong">Editor</strong></td>
							<td class="td_entra_app"><input type="text" class="moazure_input_disabled" placeholder="Enter SharePoint Server Relative URL of Folders" disabled /></td>
						</tr>
						<tr>
							<td><strong class="mo_strong">Author</strong></td>
							<td class="td_entra_app"><input type="text" class="moazure_input_disabled" placeholder="Enter SharePoint Server Relative URL of Folders" disabled /></td>
						</tr>
						<tr>
							<td><strong class="mo_strong">Contributor</strong></td>
							<td class="td_entra_app"><input type="text" class="moazure_input_disabled" placeholder="Enter SharePoint Server Relative URL of Folders" disabled /></td>
						</tr>
						<tr>
							<td><strong class="mo_strong">Subscriber</strong></td>
							<td class="td_entra_app"><input type="text" class="moazure_input_disabled" placeholder="Enter SharePoint Server Relative URL of Folders" disabled /></td>
						</tr>
						<?php
					}
					?>
					<br>
					<tr>
						<td>
							<input type="submit" name="submit" value="<?php esc_html_e( 'Save settings', 'all-in-one-microsoft' ); ?>" class="button button-primary button-large mo_disabled_btn" />
						</td>
					</tr>
				</table>
			</div>
			<br>
		</div>
		<?php
	}
}