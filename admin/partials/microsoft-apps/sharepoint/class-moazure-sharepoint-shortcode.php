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
 * Class for handling the Sharepoint shortcode tab display and shortcode content rendering.
 */
class MOAzure_Sharepoint_Shortcode {

	/**
	 * Object variable.
	 *
	 * @var object variable to instantiate the class.
	 */
	private static $instance;

	/**
	 * Function to get the object of the class
	 *
	 * @return object
	 */
	public static function get_sps_shortcode_obj() {
		if ( ! isset( self::$instance ) ) {
			$class          = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	/**
	 * Function to display the sharepoint shortcode tab.
	 *
	 * @return void
	 */
	public function moazure_sps_shortcode_page() {

		$appconfig = array();

		$app       = MOAzure_Admin_Utils::moazure_get_azure_app_config();
		$appconfig = ! empty( $app['config'] ) ? $app['config'] : array();

		$apptype    = ! empty( $appconfig['apptype'] ) ? sanitize_text_field( wp_unslash( $appconfig['apptype'] ) ) : '';
		$is_ent_app = ! empty( get_option( 'moazure_entra_app_type' ) ) ? true : false;

		?>

			<div class="moazure_table_layout moazure_outer_div" style="min-height: fit-content;">
				<div>
					<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
						Embed Sharepoint Library using Shortcode
					</h3>			
					<p>Copy this shortcode and follow the below steps to embed sharepoint documents.</p>
				</div>
				<hr class='mo-divider'></br>
				<div class="shortcode-cont">
					<ol>
						<li>Copy the <b>Shortcode</b> given below</li>
						<div class="shortcode-class msapp-shortcodes moazure-flex" style="width: fit-content;">
							<span class="shortcode-text" id="moazure_sps_shortcode_text">
								<p>[MOAZURE_SPS_SHAREPOINT width="800px" height="800px"]</p>
							</span>
							<button class="moazure_sc_copy moazure_tooltip" id="moazure_sps_copy">
								<span class="mo_tooltiptext" id="copy_shortcode">Copy to clipboard</span>
								<i class="fa fa-clipboard fa-border" style="font-size:20px; align-items: center;vertical-align: middle;" aria-hidden="true"></i>
							</button>
						</div>
						<li>Go to the <a href="#">Pages</a> or <a href="#">Posts</a> from left pane of your WordPress</li>
						<li>Click on Add New "OR" select any existing post/ page on which you want to embed sharepoint library</li>
						<li>Click the "+" icon and search for <b>Shortcode</b></li>
						<li>Paste the copied shortcode into the shortcode block</li>
						<li>Preview changes and then click <b>Publish</b> or <b>Update</b></li>
					</ol>
				</div>
			</div>
			<script>
				const shortCode = document.getElementById('moazure_sps_copy');

				shortCode.addEventListener('click', function() {
					let shortCodeText = document.getElementById('moazure_sps_shortcode_text');

					let tempInput = document.createElement('input');
					tempInput.value = shortCodeText.innerText;
					document.body.appendChild(tempInput);

					tempInput.select();
					document.execCommand('copy');

					document.body.removeChild(tempInput);

					let tooltip = document.getElementById('copy_shortcode');
					tooltip.innerHTML = 'Copied!';
					setTimeout(function() {
						tooltip.innerHTML = 'Copy to Clipboard';
					}, 2000);
				});
			</script>
		<?php
	}

	/**
	 * Function to render and display the sharepoint library at shortcode.
	 *
	 * @param string|array $attrs shortcode attribute parameter.
	 * @param string       $content shortcode content parameter.
	 * @return string|false
	 */
	public static function moazure_sps_shortcode_render( $attrs = '', $content = '' ) {

		$sps_obj       = MOAzure_Sharepoint_Config::get_sps_config_obj();
		$ms_entra_apps = MOAzure_Admin_Utils::moazure_get_option( 'moazure_ms_entra_apps' );

		$attrs = shortcode_atts(
			array(
				'width'        => '800px',
				'height'       => '800px',
				'workspace_id' => '',
				'report_id'    => '',
			),
			$attrs,
			'MOAZURE_SPS_SHAREPOINT'
		);

		wp_enqueue_script( 'moazure_datatables_script', plugins_url() . '/login-with-azure/js/datatables.min.js', array( 'jquery' ), '2.0.3', true );
		wp_enqueue_style( 'moazure_datatables_style', plugins_url() . '/login-with-azure/css/datatables.min.css', array(), '2.0.3' );

		$sps_obj->set_localized_data();

		wp_enqueue_script( 'moazure-sps-script', plugins_url( '/js/moazure-sps-call.min.js', __FILE__ ), array( 'jquery' ), MO_AZURE_CSS_JS_VERSION, false );
		wp_localize_script( 'moazure-sps-script', 'moazure_sps_data', $sps_obj->localized_data );

		ob_start();
		if ( ! empty( $ms_entra_apps['sps_auto'] ) || ! empty( $ms_entra_apps['sps_manual'] ) ) {
			?>
			<div class="shortcode-cont" id="scroll_to" style="max-width: fit-content; margin: 0;">
				<?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $sps_obj->moazure_sps_doc_table( $attrs );
				?>
			</div>
			<script>
				const element = document.getElementById( 'scroll_to' );
				element.scrollIntoView();
			</script>
			<?php
		} else {
			return '<div style="text-align: center; color: #000; background-size:cover; border: 1px solid; background-color: #fff; padding: 20px;">Please configure Sharepoint Application to view the Sharepoint Library.</div>';
		}
		return ob_get_clean();
	}
}
