<?php
/**
 * Sharepoint library configure and preview file.
 *
 * @package    sharepoint
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Class for handling the Sharepoint configuration and library preview.
 */
class MOAzure_Sharepoint_Config {

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
	public static function get_sps_config_obj() {
		if ( ! isset( self::$instance ) ) {
			$class          = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	/**
	 * Function to define the data which will be used for sharepoint
	 *
	 * @return void
	 */
	public function set_localized_data() {

		$selected_site      = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_selected_site' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_selected_site' ) : '';
		$selected_site_name = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_selected_site_name' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_selected_site_name' ) : '';

		$selected_drive      = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_selected_drive' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_selected_drive' ) : '';
		$selected_drive_name = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_selected_drive_name' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_selected_drive_name' ) : '';

		$selected_doc_path = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_folder_path' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_folder_path' ) : '';

		$breadcrumb = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_current_breadcrumb' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_current_breadcrumb' ) : '<span>Home</span>';

		$mime_types = MOAzure_Apps_Enum::MIME_TYPES;

		$localized_data = array(
			'url'                   => esc_url( home_url( '/' ) ),
			'site_id'               => $selected_site,
			'site_name'             => $selected_site_name,
			'drive_id'              => $selected_drive,
			'drive_name'            => $selected_drive_name,
			'doc_path'              => $selected_doc_path,
			'mime_types'            => $mime_types,
			'breadcrumb'            => $breadcrumb,
			'filetype_col'          => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/type-col.svg' ),
			'sharepoint_site'       => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/sharepoint-site.svg' ),
			'drive_lib'             => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/drive-lib.svg' ),
			'drop_arrow'            => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/drop-arrow.svg' ),
			'image_icon'            => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/image.svg' ),
			'zip_icon'              => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/zip-file.svg' ),
			'folder_icon_url'       => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/folder.svg' ),
			'file_icon'             => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/file.png' ),
			'worddoc_icon'          => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/msword_file.png' ),
			'exceldoc_icon'         => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/msexcel_file.png' ),
			'pdfdoc_icon'           => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/pdf_file.png' ),
			'empty_doc_icon'        => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/empty-doc.svg' ),
			'empty_search_doc_icon' => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/empty-search-doc.svg' ),
			'download'              => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/download.svg' ),
			'redirect'              => esc_url_raw( plugin_dir_url( __FILE__ ) . 'images/redirect.svg' ),
		);

		$this->localized_data = $localized_data;
	}

	/**
	 * Function to get the localized data.
	 *
	 * @return array
	 */
	public function get_localized_data() {
		return $this->localized_data;
	}

	/**
	 * Function to display the initial config page.
	 *
	 * @return void
	 */
	public function moazure_sharepoint_initial_page() {
		$entra_app    = MOAzure_Admin_Utils::moazure_get_azure_app_config();
		$is_entra_app = ( ! empty( $entra_app['config'] && 'entra-id' === $entra_app['config']['apptype'] ) ) ? true : false;
		?>
		<div class="">
			<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
				Please connect Sharepoint to preview the Sharepoint Folders/Files
			</h3>
			<hr class='mo-divider'></br>
			<div class="no-app moazure_outer_div">
				<div class="moazure-flex" style="justify-content: space-between;">
					<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
						Automatic Connection
					</h3>
					<a href="https://plugins.miniorange.com/configure-wordpress-azure-sso?setup_guide=sharepoint&utm_source=wordpress%20plugin%20readme&utm_medium=organic&utm_campaign=Traffic%20from%20readme" target="_blank" rel="noopener" class="moazure-setup-guide-button moazure-rad" style="text-decoration: none;" > Setup Guide </a>
				</div>			
				<p class="moazure_app_desc">Effortlessly integrate your WordPress site with SharePoint and OneDrive sites using our pre-integrated application.<br />Click on the below button to connect to sharepoint automatically.</p>
				<div class="moazure-flex" style="justify-content: center; padding: 2% 5%;">
					<button id="moazure_sps_test_config" type="button" class="button button-large moazure_configure_btn moazure-rad" onclick="moazure_sps_auto_connect('<?php echo esc_attr( admin_url() ); ?>')"><?php esc_html_e( 'Connect', 'all-in-one-microsoft' ); ?></button>
				</div>
			</div>

			<br />

			<div class="no-app moazure_outer_div">
				<div class="moazure-flex" style="justify-content: space-between;">
					<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
						Manual Connection
					</h3>
					<a href="https://plugins.miniorange.com/configure-wordpress-azure-sso?setup_guide=sharepoint&utm_source=wordpress%20plugin%20readme&utm_medium=organic&utm_campaign=Traffic%20from%20readme" target="_blank" rel="noopener" class="moazure-setup-guide-button moazure-rad" style="text-decoration: none;" > Setup Guide </a>
				</div>
				<p class="moazure_app_desc">Integrate Sharepoint Site Documents in the WordPress according to user access.</p>
					<?php
					if ( ! $is_entra_app ) {
						?>
						<p class="moazure_app_desc">Configure an Azure application and provide the "Sites.Read.All" permission to integrate Sharepoint on your WordPress site.<br />Click on the below button to go to the Entra ID App configuration.</p>
						<div class="moazure-flex" style="justify-content: center; padding: 2% 5%;">
							<a id="pbi_auto_connect" href="admin.php?page=moazure_settings&tab=moazure_config&app=entra-id" class="button button-large moazure_configure_btn moazure-rad">
								<?php esc_html_e( 'Configure Entra ID App', 'all-in-one-microsoft' ); ?>
							</a>
						</div>
						<?php
					} else {
						?>
						<p class="moazure_app_desc">Configure an Azure application and provide the "Sites.Read.All" permission to integrate Sharepoint on your WordPress site.<br />Click on the below button to use the configured Entra ID application for Sharepoint</p>
						<div class="moazure-flex" id="moazure_sps_initial" style="justify-content: center; gap: 2rem; padding: 2% 5%;">
							<form method="post" action="" name="moazure_use_entra">
								<?php wp_nonce_field( 'moazure_use_entra_form', 'moazure_use_entra_form_field' ); ?>
								<input type="hidden" name="option" value="moazure_use_entra" />
								<input type="hidden" name="ms_app" value="sharepoint" />
								<input type="submit" name="submit_use_entra" value="<?php esc_html_e( 'Use configured Entra App', 'all-in-one-microsoft' ); ?>" class="button button-large moazure_configure_btn moazure-rad" />
							</form>
							
							<button class="button button-primary button-large mo_disabled_btn" >Add New Entra ID App
								<span>
									<img class="moazure_premium-label" src="<?php echo esc_url( plugins_url( '/../../apps/images/moazure_premium-label.png', __FILE__ ) ); ?>" alt="miniOrange Standard Plans Logo">
								</span>								
							</button>
						</div>
						<?php
					}
					?>
			</div>
		</div>
		<script>
			function moazure_sharepoint_doc_redirect() {
				let redUrl = '<?php echo esc_url_raw( admin_url() ); ?>' + 'admin.php?page=moazure_settings&tab=sps_preview&app=sharepoint';
				window.location.href = redUrl;
			}
			function reload_after_close() {
				window.location.reload();
			}
		</script>
		<?php
	}

	/**
	 * Function to display Sites and Drives dropdown
	 *
	 * @return void
	 */
	public function moazure_sps_site_drive() {

		$data   = $this->localized_data;
		$drives = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_all_drives' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_sps_all_drives' ) : array();
		$count  = 0;
		?>
			<div class="sd-cont">
				<div class="sd-div">
					<div class="moazure-flex">
						<div style="width: 4rem; margin-right: 1rem;">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" style="enable-background:new 0 0 128 128;" version="1.1" viewBox="0 0 128 128" xml:space="preserve">
								<circle class="st6" cx="63.7" cy="36.5" r="36.7"/>
								<circle class="st7" cx="94.3" cy="70" r="33.9"/>
								<circle class="st8" cx="68.5" cy="102.1" r="26"/>
								<path class="st9" d="M59.5,97.2h-53c-3.5,0-6.4-2.9-6.4-6.4V38.6c0-3.5,2.9-6.4,6.4-6.4h53c3.5,0,6.4,2.9,6.4,6.4v52.2  C65.9,94.3,63.1,97.2,59.5,97.2z"/>
								<g>
									<path class="st5" d="M22.3,75.9c2.1,1.2,5.4,2.3,8.8,2.3c4.2,0,6.6-2,6.6-4.9c0-2.7-1.8-4.3-6.4-6c-6-2.1-9.9-5.3-9.9-10.4   c0-5.9,5-10.3,12.9-10.3c3.9,0,6.8,0.9,8.7,1.8l-1.6,5.3C40.1,53,37.6,52,34.2,52c-4.2,0-6,2.2-6,4.3c0,2.8,2.1,4,7,5.9   c6.3,2.4,9.3,5.5,9.3,10.6c0,5.8-4.4,10.7-13.8,10.7c-3.9,0-7.9-1.1-9.9-2.2L22.3,75.9z"/>
								</g>
								<path class="st48" d="M65.9,38c0,0.2,0,0.4,0,0.6v52.2c0,3.5-2.9,6.4-6.4,6.4H42.8l-0.2,5.7h22.4c3.5,0,6.4-2.9,6.4-6.4V44.3  C71.3,41.1,69,38.5,65.9,38z"/>
							</svg>
						</div>
						<div class="defsite-loader" id="defsite_loader">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="background: inherit; display: block; shape-rendering: auto;" width="20%" height="10%" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
								<circle cx="84" cy="50" r="10" fill="#408ee0">
									<animate attributeName="r" repeatCount="indefinite" dur="0.25s" calcMode="spline" keyTimes="0;1" values="10;0" keySplines="0 0.5 0.5 1" begin="0s"/>
									<animate attributeName="fill" repeatCount="indefinite" dur="1s" calcMode="discrete" keyTimes="0;0.25;0.5;0.75;1" values="#408ee0;#408ee0;#408ee0;#408ee0;#408ee0" begin="0s"/>
								</circle>
								<circle cx="16" cy="50" r="10" fill="#408ee0">
									<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="0s"/>
									<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="0s"/>
								</circle>
								<circle cx="50" cy="50" r="10" fill="#408ee0">
									<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.25s"/>
									<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.25s"/>
								</circle>
								<circle cx="84" cy="50" r="10" fill="#408ee0">
									<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.5s"/>
									<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.5s"/>
								</circle>
								<circle cx="16" cy="50" r="10" fill="#408ee0">
									<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.75s"/>
									<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.75s"/>
								</circle>
							</svg>
						</div>
						<div class="para-cont">
							<p class="site-para" id="moazure_sps_site"><?php echo esc_html( $data['site_name'] ); ?></p>
						</div>
						<div class="site-dropdown">
							<button class="site-toggle" id="moazure_sps_site_toggle">
								<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 28 28" viewBox="0 0 28 28">
									<switch>
										<foreignObject height="1" requiredExtensions="http://ns.adobe.com/AdobeIllustrator/10.0/" width="1"/>
										<g>
											<path d="m13.3 20.2-11-11c-.4-.4-.4-1 0-1.4s1-.4 1.4 0l10.3 10.3 10.3-10.3c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-11 11c-.2.2-.4.3-.7.3s-.5-.1-.7-.3z" fill="#303030"/>
										</g>
									</switch>
								</svg>
							</button>
							<!-- Sites to be generated dynamically -->
							<div class="site-menu" id="site_menu" style="display: none;">
								<div class="allsite-loader" id="allsite_loader">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="background: inherit; display: block; shape-rendering: auto;" width="100px" height="50px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
										<circle cx="84" cy="50" r="10" fill="#408ee0">
											<animate attributeName="r" repeatCount="indefinite" dur="0.25s" calcMode="spline" keyTimes="0;1" values="10;0" keySplines="0 0.5 0.5 1" begin="0s"/>
											<animate attributeName="fill" repeatCount="indefinite" dur="1s" calcMode="discrete" keyTimes="0;0.25;0.5;0.75;1" values="#408ee0;#408ee0;#408ee0;#408ee0;#408ee0" begin="0s"/>
										</circle>
										<circle cx="16" cy="50" r="10" fill="#408ee0">
											<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="0s"/>
											<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="0s"/>
										</circle>
										<circle cx="50" cy="50" r="10" fill="#408ee0">
											<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.25s"/>
											<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.25s"/>
										</circle>
										<circle cx="84" cy="50" r="10" fill="#408ee0">
											<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.5s"/>
											<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.5s"/>
										</circle>
										<circle cx="16" cy="50" r="10" fill="#408ee0">
											<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.75s"/>
											<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.75s"/>
										</circle>
									</svg>
								</div>
								<div id="site_item">							
	
								</div>
							</div>
						</div>                      
					</div>
					<div>                        
						<a id="tab-sps-shortcode" href="admin.php?page=moazure_settings&tab=sps_shortcode&app=sharepoint" class="button button-large moazure_configure_btn">
							<?php esc_html_e( 'Embed Sharepoint Library', 'all-in-one-microsoft' ); ?>
						</a>
					</div>
				</div>

				<div class="moazure-flex" style="position: relative; justify-content: space-between">
					<div class="alldrive-loader" id="alldrive_loader">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="background: inherit; display: block; shape-rendering: auto;" width="10%" height="10%" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
							<circle cx="84" cy="50" r="10" fill="#408ee0">
								<animate attributeName="r" repeatCount="indefinite" dur="0.25s" calcMode="spline" keyTimes="0;1" values="10;0" keySplines="0 0.5 0.5 1" begin="0s"/>
								<animate attributeName="fill" repeatCount="indefinite" dur="1s" calcMode="discrete" keyTimes="0;0.25;0.5;0.75;1" values="#408ee0;#408ee0;#408ee0;#408ee0;#408ee0" begin="0s"/>
							</circle>
							<circle cx="16" cy="50" r="10" fill="#408ee0">
								<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="0s"/>
								<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="0s"/>
							</circle>
							<circle cx="50" cy="50" r="10" fill="#408ee0">
								<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.25s"/>
								<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.25s"/>
							</circle>
							<circle cx="84" cy="50" r="10" fill="#408ee0">
								<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.5s"/>
								<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.5s"/>
							</circle>
							<circle cx="16" cy="50" r="10" fill="#408ee0">
								<animate attributeName="r" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.75s"/>
								<animate attributeName="cx" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.75s"/>
							</circle>
						</svg>
					</div>
					<div class="para-cont" id="moazure_sps_drive">
						<?php
						foreach ( $drives as $key => $drive ) {
							if ( $count < 4 ) {
								?>
								<p class="drive-para" id="<?php echo esc_attr( $drive['id'] ); ?>"><?php echo esc_html( $drive['name'] ); ?></p>
								<?php
								++$count;
							}
						}
						?>
					</div>
					<div class="drive-dots" id="moazure_drive_dots" style="display: <?php echo empty( $drives[ $count ] ) ? 'none' : 'inherit'; ?>">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="15" height="15" x="0" y="0" viewBox="0 0 426.667 426.667" style="enable-background:new 0 0 512 512" xml:space="preserve">
							<g>
								<circle cx="42.667" cy="213.333" r="42.667" fill="#696969" opacity="1" data-original="#696969"/>
								<circle cx="213.333" cy="213.333" r="42.667" fill="#696969" opacity="1" data-original="#696969"/>
								<circle cx="384" cy="213.333" r="42.667" fill="#696969" opacity="1" data-original="#696969"/>
							</g>
						</svg>
					</div>
					<div class="drive-dots-menu" id="drive_dots_menu-new" style="display: none;">
						<?php
						foreach ( $drives as $key => $drive ) {
							if ( $key >= $count ) {
								?>
								<p class="drive-dots-para" id="<?php echo esc_attr( $drive['id'] ); ?>"><?php echo esc_html( $drive['name'] ); ?></p>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Function to display sharepoint documents table
	 *
	 * @param array $attrs attribute of sharepoint shortcode.
	 * @return void
	 */
	public function moazure_sps_doc_table( $attrs = array() ) {

		$data = $this->localized_data;

		if ( ! empty( $attrs ) ) {
			$width  = $attrs['width'];
			$height = $attrs['height'];
		}

		wp_enqueue_style( 'moazure-doc-style', plugins_url( '/css/moazure-doc-embed.min.css', __FILE__ ), array(), MO_AZURE_CSS_JS_VERSION );

		?>
			<div class="sps-sc-err" id="sps_sc_err" <?php echo ( ! empty( $attrs ) ) ? 'style="width: ' . esc_attr( $width ) . '; height: ' . esc_attr( $height ) . '; margin: auto;"' : ''; ?>>
				<div class="error-cont">
					<svg xmlns="http://www.w3.org/2000/svg" clip-rule="evenodd" fill-rule="evenodd" height="30" image-rendering="optimizeQuality" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" viewBox="0 0 254000 254000" width="30">
						<g id="图层_x0020_1">
							<path d="m127000 0c70129 0 127000 56871 127000 127000s-56871 127000-127000 127000-127000-56871-127000-127000 56871-127000 127000-127000zm-64190 172969 45969-45969-45969-45969c-2637-2638-2637-6941 0-9578l8643-8643c2637-2637 6940-2637 9578 0l45969 45969 45969-45969c2638-2637 6941-2637 9578 0l8643 8643c2637 2637 2637 6940 0 9578l-45969 45969 45969 45969c2637 2638 2637 6941 0 9578l-8643 8643c-2637 2637-6940 2637-9578 0l-45969-45969-45969 45969c-2638 2637-6941 2637-9578 0l-8643-8643c-2637-2637-2637-6940 0-9578z" fill="#ff4141"/>
						</g>
					</svg>
					<br/>
					<span id="res_err">An error has occurred while fetching documents.</span>
				</div>
			</div>
			<div class="doc-div" id="sps_docs" <?php echo ( ! empty( $attrs ) ) ? 'style="width: ' . esc_attr( $width ) . '; height: ' . esc_attr( $height ) . '; margin: auto;"' : ''; ?>>
				<div class="table-start">
					<div class="breadcrumb-cont moazure-flex" id="moazure_sps_breadcrumb">
						<p class="bread-cont moazure-rad <?php echo empty( $data['doc-path'] ) ? 'bread-sel' : ''; ?>"></p>
					</div>
					<div class="search-cont">
						<div class="refresh-div">
							<button class="refresh-btn copytooltip" id="moazure_sps_refresh">
								<img style="margin: 0px auto;" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'images/refresh.svg' ); ?>" width="70%">
								<span class="refresh-content copytooltiptext">Click here to refresh and fetch all current documents from sharepoint</span>
							</button>
						</div>
					</div>
				</div>

				<table class="doc-table" id="moazure_sps_table" style="width: 100%;">

					<!-- Table body to be generated dynamically -->
					<tbody class="dt-body" id="moazure_sps_table_body">

					</tbody>
				</table>
			</div>
		<?php
	}

	/**
	 * Function to display the preview page of sharepoint library on admin side.
	 *
	 * @return void
	 */
	public function moazure_sharepoint_app_page() {

		wp_enqueue_script( 'moazure_datatables_script', plugins_url() . '/login-with-azure/js/datatables.min.js', array( 'jquery' ), '2.0.3', true );
		wp_enqueue_style( 'moazure_datatables_style', plugins_url() . '/login-with-azure/css/datatables.min.css', array(), '2.0.3' );

		$this->set_localized_data();
		wp_enqueue_script( 'moazure-sps-script', plugins_url( '/js/moazure-sps-call.min.js', __FILE__ ), array( 'jquery' ), MO_AZURE_CSS_JS_VERSION, false );
		wp_localize_script( 'moazure-sps-script', 'moazure_sps_data', $this->localized_data );
		?>
		<div class="moazure_table_layout moazure_outer_div">
			<div class="sps-admin-err" id="sps_admin_err">
				<div class="error-cont">
					<svg xmlns="http://www.w3.org/2000/svg" clip-rule="evenodd" fill-rule="evenodd" height="30" image-rendering="optimizeQuality" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" viewBox="0 0 254000 254000" width="30">
						<g id="图层_x0020_1">
							<path d="m127000 0c70129 0 127000 56871 127000 127000s-56871 127000-127000 127000-127000-56871-127000-127000 56871-127000 127000-127000zm-64190 172969 45969-45969-45969-45969c-2637-2638-2637-6941 0-9578l8643-8643c2637-2637 6940-2637 9578 0l45969 45969 45969-45969c2638-2637 6941-2637 9578 0l8643 8643c2637 2637 2637 6940 0 9578l-45969 45969 45969 45969c2637 2638 2637 6941 0 9578l-8643 8643c-2637 2637-6940 2637-9578 0l-45969-45969-45969 45969c-2638 2637-6941 2637-9578 0l-8643-8643c-2637-2637-2637-6940 0-9578z" fill="#ff4141"/>
						</g>
					</svg>
					<br/><br/>
					<span class="res-err" id="res_err">An error has occurred while fetching response.</span>
				</div>
				<div class="perm-step1" id="sps_spec_err">
					<p>Please check if you have provided the required permissions in your Azure Application.</p>
					<p><a href="https://plugins.miniorange.com/configure-wordpress-azure-sso?setup_guide=sharepoint" target="_blank"><u>How to configure Permissions in Entra-ID?</u></a></p>
					<p>Once done with the permissions, please click the following button or simply refresh the page.</p>
				</div>
				<div class="perm-step2" id="sps_gen_err">
					<p>Please reach out to us using the <b>Contact Us</b> form on the right side of this screen for assistance.</p>
				</div>
				<div>
					<button class="ref-btn button button-large moazure_configure_btn" id="refetch_sps">Fetch Again</button>
				</div>
			</div>

			<div class="sddt-container"  id="sharepoint_display">
				<!-- Div for Selecting Site and Drive -->
				<?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->moazure_sps_site_drive();
				?>

				<!-- Documents Table -->
				<?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->moazure_sps_doc_table();
				?>

			</div>
		</div>
		<?php
	}
}
