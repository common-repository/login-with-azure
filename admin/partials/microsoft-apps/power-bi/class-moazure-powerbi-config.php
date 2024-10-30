<?php
/**
 * Power BI config file
 *
 * @package    power-bi
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Class for handling the Power BI configurations and setup.
 */
class MOAzure_PowerBI_Config {

	/**
	 * Object variable
	 *
	 * @var object variable to instantiate the class.
	 */
	private static $instance;

	/**
	 * Function to get the object of the class
	 *
	 * @return object
	 */
	public static function get_pbi_config_obj() {
		if ( ! isset( self::$instance ) ) {
			$class          = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	/**
	 * Function to display the initial Power BI page
	 *
	 * @return void
	 */
	public function moazure_powerbi_initial_page() {
		$entra_app    = MOAzure_Admin_Utils::moazure_get_azure_app_config();
		$is_entra_app = ( ! empty( $entra_app['config'] && 'entra-id' === $entra_app['config']['apptype'] ) ) ? true : false;
		?>
		<div class="">
			<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
				Please connect Azure app to embed Power BI reports
			</h3>
			<hr class='mo-divider'></br>
			<div class="no-app moazure_outer_div">
				<div class="moazure-flex" style="justify-content: space-between;">
					<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
						Power BI Connection
					</h3>
					<a href="https://plugins.miniorange.com/configure-wordpress-azure-sso?setup_guide=powerbi" target="_blank" rel="noopener" class="moazure-setup-guide-button moazure-rad" style="text-decoration: none;" > Setup Guide </a>
				</div>
				<p class="moazure_app_desc">Integrate Power BI reports in the WordPress according to user access.</p>
					<?php
					if ( ! $is_entra_app ) {
						?>
						<p class="moazure_app_desc">Configure an Azure application and provide the "Report.Read.All" permission to integrate Power BI on your WordPress site.<br />Click on the below button to go to the Entra ID App configuration.</p>
						<div class="moazure-flex" style="justify-content: center; padding: 2% 5%;">
							<a id="pbi_auto_connect" href="admin.php?page=moazure_settings&tab=moazure_config&app=entra-id" class="button button-large moazure_configure_btn moazure-rad">
								<?php esc_html_e( 'Configure Entra ID App', 'all-in-one-microsoft' ); ?>
							</a>
						</div>
						<?php
					} else {
						?>
						<p class="moazure_app_desc">Configure an Azure application and provide the "Report.Read.All" permission to integrate Power BI on your WordPress site.<br />Click on the below button to use the configured Entra ID application for Power BI</p>
						<div class="moazure-flex" id="moazure_pbi_initial" style="justify-content: center; gap: 2rem; padding: 2% 5%;">
							<form method="post" action="" name="moazure_use_entra">
								<?php wp_nonce_field( 'moazure_use_entra_form', 'moazure_use_entra_form_field' ); ?>
								<input type="hidden" name="option" value="moazure_use_entra" />
								<input type="hidden" name="ms_app" value="power-bi" />
								<input type="submit" name="submit_use_entra" value="<?php esc_html_e( 'Use configured Entra ID App', 'all-in-one-microsoft' ); ?>" class="button button-large moazure_configure_btn moazure-rad" />
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
			function moazure_pbi_config_redirect() {			
				let redUrl = '<?php echo esc_url_raw( admin_url() ); ?>' + 'admin.php?page=moazure_settings&tab=pbi_app&app=power-bi';
				window.location.href = redUrl;
			}
			function reload_pbi_after_close() {
				window.location.reload();
			}
		</script>
		<?php
	}

	/**
	 * Function to display the Power BI page after Entra ID app setup
	 *
	 * @return void
	 */
	public function moazure_powerbi_app_page() {
		wp_enqueue_script( 'moazure-pbi-script', plugins_url( '/js/moazure-pbi-call.min.js', __FILE__ ), array( 'jquery' ), MO_AZURE_CSS_JS_VERSION, false );

		$shortcodes = ! empty( MOAzure_Admin_Utils::moazure_get_option( 'moazure_pbi_all_shortcodes' ) ) ? MOAzure_Admin_Utils::moazure_get_option( 'moazure_pbi_all_shortcodes' ) : array();

		$this->moazure_pbi_display_shortcodes( $shortcodes );
		$this->moazure_pbi_generate_shortcode( $shortcodes );
		?>
		<script>
			const shortCode = document.querySelectorAll('.moazure_sc_copy');

			shortCode.forEach(scEle => {
				scEle.addEventListener('click', function(event) {
					const shortcodeSpan = this.previousElementSibling;
					const shortcodeText = shortcodeSpan.textContent;
								
					navigator.clipboard.writeText(shortcodeText).then(() => {
						console.log('Shortcode copied successfully');
					}).catch(err => {
						console.error('Could not copy text: ', err);
					});

					const tooltip = this.querySelector('.mo_tooltiptext');
					tooltip.innerHTML = 'Copied!';

					setTimeout(function() {
						tooltip.innerHTML = 'Copy to Clipboard';
					}, 2000);
				});
			});
		</script>
		<?php
	}

	/**
	 * Function to display Power BI generated shortcodes
	 *
	 * @param array $shortcodes var containing shortcodes.
	 * @return void
	 */
	public function moazure_pbi_display_shortcodes( $shortcodes ) {
		if ( is_array( $shortcodes ) && ! empty( $shortcodes ) ) {
			?>
			<div class="moazure_table_layout moazure_outer_div" style="min-height: fit-content;">
				<div>
					<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
						Embed Power BI reports using Shortcode
					</h3>			
					<p>Copy the shortcode and follow the given steps to embed PowerBI reports.</p>
				</div>
				<hr class='mo-divider'></br>
				<div class="shortcode-cont">
					<h3>Steps to embed the shortcode</h3>
					<ol>
						<li>Copy the <b>Shortcode</b> given below</li>
						<li>Go to the <a href="#">Pages</a> or <a href="#">Posts</a> from left pane of your WordPress</li>
						<li>Click on Add New "OR" select any existing post/ page on which you want to embed sharepoint library</li>
						<li>Click the "+" icon and search for <b>Shortcode</b></li>
						<li>Paste the copied shortcode into the shortcode block</li>
						<li>Preview changes and then click <b>Publish</b> or <b>Update</b></li>
					</ol>
				</div>
				<br/>
				<div class="shortcode-class">
					<h3>Generated Shortcodes</h3>
					<div style="padding: 0 1rem;">
					<?php
					$i        = 1;
					$no_of_sc = count( $shortcodes );
					foreach ( $shortcodes as $value ) {
						?>
						<div>
							<strong class="mo_strong">
								Shortcode #<?php echo ( esc_html( $i ) ); ?>
							</strong>
							<br/><br/>
							<div class="moazure-flex" style="gap: 1rem;">
								<div class="moazure-flex msapp-shortcodes moazure-rad">
									<span class="pbi_generated_shortcodes" id="moazure_pbi_shortcode_text"><?php echo esc_html( $value ); ?></span>
									<button class="moazure_sc_copy moazure_tooltip" id="moazure_pbi_copy">
										<span class="mo_tooltiptext" id="copy_shortcode">Copy to clipboard</span>
										<i class="fa fa-clipboard fa-border" style="font-size:20px; align-items: center;vertical-align: middle;" aria-hidden="true"></i>
									</button>
								</div>
								<form method="post" action="" name="moazure_remove_pbi_sc" id="pbi_remove_form">
									<?php wp_nonce_field( 'moazure_remove_pbi_sc_form', 'moazure_remove_pbi_sc_form_field' ); ?>
									<input type="hidden" name="option" value="moazure_remove_pbi_shortcode" />
									<input type="hidden" name="pbi_shortcode_key" value="<?php echo esc_html( $i ); ?>" />
									<button type="submit" class="button button-large moazure_configure_btn moazure-rad" style="padding: 0 15px"><b>-</b></button>
								</form>
								<?php
								if ( $i === $no_of_sc ) {
									?>
									<button class="button button-large moazure_configure_btn moazure-rad" id="pbi_add_sc" style="padding: 0 15px"><b>+</b></button>
									<?php
								}
								?>
							</div>
						</div>
						<br/>
						<?php
						++$i;
					}
					?>
					</div>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Function to generate shortcode
	 *
	 * @param array $shortcodes var containing shortcodes.
	 * @return void
	 */
	public function moazure_pbi_generate_shortcode( $shortcodes ) {
		?>
		<div class="moazure_table_layout moazure_outer_div" id="gen_sc_div" style="display: <?php echo ! empty( $shortcodes ) ? 'none' : 'block'; ?>">
			<div class="moazure-flex" style="justify-content: space-between;">
				<div>
					<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
						Add Shortcode
					</h3>
					<p class="moazure_desc" style="font-style: normal;">
						Provide the following feilds to generate a shortcode for embedding Power BI reports
					</p>
				</div>
			</div>
			<hr class='mo-divider'>
			<form method="post" action="" name="moazure_report_form" id="report_form">
			<?php wp_nonce_field( 'moazure_pbi_shortcode_form', 'moazure_pbi_shortcode_form_field' ); ?>
				<input type="hidden" name="option" value="moazure_pbi_sc_config" />
	
				<table class="mo_settings_table moazure_configure_table">
					<tr class="moazure_configure_table_rows">
						<td class="moazure_contact_heading td_entra_app">
							<strong class="mo_strong moazure_position"><?php esc_html_e( 'Workspace ID', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> : </strong>
							<p class="moazure_desc">This is a unique identifier for a Power BI workspace, used to manage and access reports, datasets, and dashboards within that workspace</p>
						</td>
						<td class="moazure_contact_heading td_entra_app">
							<input class="mo_table_textbox" required id="moazure_pbi_workspaceid" type="text" name="moazure_pbi_workspaceid" placeholder="Enter the Workspace ID">
						</td>
					</tr>
					<tr class="moazure_configure_table_rows" id="moazure_display_app_name_div">
						<td class="moazure_contact_heading td_entra_app">
							<strong class="mo_strong moazure_position"><?php esc_html_e( 'Report ID', 'all-in-one-microsoft' ); ?><font style="color: red;">*</font> :</strong>
							<p class="moazure_desc">This is a unique identifier for a specific report, used to reference and manage the report within the Power BI service</p>
						</td>
						<td class="moazure_contact_heading td_entra_app">
							<input class="mo_table_textbox" type="text" required id="moazure_pbi_reportid" name="moazure_pbi_reportid" placeholder="Enter the Report ID" >
						</td>
					</tr>
					<tr class="moazure_configure_table_rows">
						<td class="moazure_contact_heading td_entra_app">
							<strong class="mo_strong"><?php esc_html_e( 'Height (in px)', 'all-in-one-microsoft' ); ?> :</strong>
							<p class="moazure_desc">It allows you to add the height of the to the Shortcode for embedding Power-BI report</p>
						</td>
						<td class="moazure_contact_heading td_entra_app">
							<input class="mo_table_textbox" type="text" id="moazure_pbi_hgt" name="moazure_pbi_hgt" placeholder="Enter the Height" value="500" >
						</td>
					</tr>
					<tr class="moazure_configure_table_rows">
						<td class="moazure_contact_heading td_entra_app">
							<strong class="mo_strong"><?php esc_html_e( 'Width (in px)', 'all-in-one-microsoft' ); ?> :</strong>
							<p class="moazure_desc">It allows you to add the width of the to the Shortcode for embedding Power-BI report</p>
						</td>
						<td class="moazure_contact_heading td_entra_app">
							<input class="mo_table_textbox" type="text" id="moazure_pbi_wdt" name="moazure_pbi_wdt" placeholder="Enter the Width" value="800" >
						</td>
					</tr>
				</table>
				<div class="moazure-flex" style="justify-content: space-between;">
					<div class="moazure-flex moazure-app-submit">
						<div>							
							<input type="submit" name="submit" value="<?php esc_html_e( 'Generate Shortcode', 'all-in-one-microsoft' ); ?>" class="button button-large moazure_configure_btn moazure-rad" />
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php
	}
}
