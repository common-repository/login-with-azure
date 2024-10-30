<?php
/**
 * Power BI shortcode display and render file.
 *
 * @package    power-bi
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Class for handling the Power BI shortcode tab display and report content rendering.
 */
class MOAzure_PowerBI_Shortcode {

	/**
	 * Object variable
	 *
	 * @var object variable to instantiate the class.
	 */
	private static $instance;

	/**
	 * Shortcode config variable
	 *
	 * @var array variable to store the shortcode fields.
	 */
	private $shortcode_config = array();

	/**
	 * Function to get the object of the class
	 *
	 * @return object
	 */
	public static function get_pbi_shortcode_obj() {
		if ( ! isset( self::$instance ) ) {
			$class          = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	/**
	 * Function to set the shortcode_config as per the fields in shortcode
	 *
	 * @param array $attrs contains the fields in the report shortcode.
	 * @return void
	 */
	private function set_shortcode_config( $attrs ) {

		$this->shortcode_config = array(
			'rid'    => $attrs['report_id'],
			'wid'    => $attrs['workspace_id'],
			'width'  => $attrs['width'],
			'height' => $attrs['height'],
		);
	}

	/**
	 * Function to render the Power BI report from shortcode.
	 *
	 * @param string $attrs shortcode attribute parameter.
	 * @param string $content shortcode content parameter.
	 * @return mixed
	 */
	public static function moazure_pbi_shortcode_render( $attrs = '', $content = '' ) {

		$attrs = shortcode_atts(
			array(
				'width'        => '800px',
				'height'       => '800px',
				'workspace_id' => '',
				'report_id'    => '',
			),
			$attrs,
			'MOAZURE_API_POWER_BI'
		);

		if ( empty( $attrs['workspace_id'] ) || empty( $attrs['report_id'] ) ) {
			return '<div style="text-align: center; color: #000; background-size:cover; border: 1px solid; background-color: #fff; padding: 20px;">Either Workspace or Report Id is not provided in the Shortcode.</div>';
		}

		$ms_entra_apps = MOAzure_Admin_Utils::moazure_get_option( 'moazure_ms_entra_apps' );
		$res_ids       = MOAzure_Admin_Utils::moazure_get_option( 'moazure_pbi_resourceids' );
		$res_id        = $attrs['workspace_id'] . '=' . $attrs['workspace_id'];

		$server_post_new_url = isset( $_SERVER['REQUEST_URI'] ) ? strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'wp-admin/post-new.php' ) : false;
		$server_post_url     = isset( $_SERVER['REQUEST_URI'] ) ? strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'wp-admin/post.php' ) : false;
		$server_pages_url    = isset( $_SERVER['REQUEST_URI'] ) ? strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'wp-json/wp/v2/pages' ) : false;

		if ( ! ( false === $server_post_new_url ) || ! ( false === $server_post_url ) || ! ( false === $server_pages_url ) ) {
			ob_start();
		}

		$instance = new self();

		if ( false === $server_post_new_url || ! ( false === $server_post_url ) || ! ( false === $server_pages_url ) ) {
			$is_user_logged_in = MOAzure_Admin_Utils::moazure_is_user_logged_in();
			$instance->set_shortcode_config( $attrs );

			MOAzure_Admin_Utils::moazure_start_session();

			if ( $is_user_logged_in && ( isset( $_SESSION['sso_user'] ) || isset( $_COOKIE['sso_user'] ) ) ) {
				if ( empty( $ms_entra_apps['pbi_manual'] ) ) {
					return '<div style="text-align: center; color: #000; background-size:cover; border: 1px solid; background-color: #fff; padding: 20px;">Please configure PowerBI Application to view the PowerBI Reports.</div>';
				} elseif ( empty( $res_ids ) ) {
					return '<div style="text-align: center; color: #000; background-size:cover; border: 1px solid; background-color: #fff; padding: 20px;">Either the PowerBI shortcodes have been deleted or not generated.</div>';
				}

				$content = $instance->moazure_pbi_report_content();
			} elseif ( $is_user_logged_in ) {
				return '<div style="text-align: center; color: #000; background-size:cover; border: 1px solid; background-color: #fff; padding: 20px;">The Power BI Report content is visible to only Entra ID users.</div>';
			} else {
				$content = $instance->moazure_pbi_not_sso_user_content();
			}

			MOAzure_Admin_Utils::moazure_write_close_session();
		}

		if ( ! ( false === $server_post_new_url ) || ! ( false === $server_post_url ) || ! ( false === $server_pages_url ) ) {
			ob_get_clean();
		}

		$content = empty( $content ) ? 'Report not found' : $content;

		return $content;
	}

	/**
	 * Function to show the content if user not logged in via SSO.
	 *
	 * @return mixed
	 */
	public function moazure_pbi_not_sso_user_content() {

		$config    = $this->shortcode_config;
		$loginpage = home_url() . '/wp-admin';
		$content   = '<div id="powerbi-embed-not-loggedin_user" style="text-align: center; color: #000; background-size:cover; border: 1px solid; background-color: #fff; padding: 20px;">          
			<span> Please <a onclick="moazure_pbi_login_redirect()" style="color:blue;cursor:pointer;text-decoration:underline;">login</a> via configured Entra ID App to view the Power BI content.</span>
		</div>
		<script>
			function moazure_pbi_login_redirect(){window.location.href="' . $loginpage . '";}
		</script>';
		return $content;
	}

	/**
	 * Function to render the report content.
	 *
	 * @return mixed
	 */
	public function moazure_pbi_report_content() {
		$config    = $this->shortcode_config;
		$azure_api = MOAzure_Azure_API::get_azure_api_obj();
		$azure_api->set_pbi_report_ep( $config );
		$report_res = $azure_api->moazure_pbi_get_report_content();

		if ( isset( $report_res['error'] ) || ! $report_res ) {
			$html = '<div id="powerbi-embed" style="text-align: center; color: #000; background-size:cover; border: 1px solid; background-color: #fff; padding: 20px;">
				<span>' . $report_res['error'] . ' : ' . $report_res['error_description'] . '</span>
				<span style="margin:20px;z-index:1"><a class="restrictedcontent_anchor" style="cursor: pointer;" onclick="window.location.href=\'' . home_url() . '\'">Go back to site</a></span>
				</div>';
			return $html;
		} else {
			$embedurl     = isset( $report_res['embedUrl'] ) ? $report_res['embedUrl'] : '';
			$access_token = $azure_api->get_access_token();
			$content      = '<div id="powerbi-embed' . $config['rid'] . '" style="width:' . $config['width'] . ';height:' . $config['height'] . '; max-width: -webkit-fill-available; max-height: -webkit-fill-available;">Loading Content...</div>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/powerbi-client/2.19.1/powerbi.min.js" integrity="sha512-JHwXCdcrWLbZo78KFRzEdGcFJX1DRR+gj/ufcoAVWNRrXCxUWj2W2Hxnw61nFfzfWAdWchR9FQcOFjCNcSJmbA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/powerbi-client/2.19.1/powerbi.js" integrity="sha512-Mxs/3Mam3+Beg4YdPJjPkwI7yN5GvsOx9J23MM03lrnAzIIGpZB3Eicz7H/TOEfMEyIJNXPAoufedL1I3Zc6Sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
			<script>
				embedConfiguration = {
					type:"report",
					embedUrl: "' . $embedurl . '",
					tokenType: window["powerbi-client"].models.TokenType.Aad,
					accessToken: "' . $access_token . '",
					settings: {
						filterPaneEnabled: false,
						navContentPaneEnabled: false
					}
				};
				var reportContainer = document.getElementById("powerbi-embed' . esc_js( $config['rid'] ) . '");
				var report = powerbi.embed(reportContainer, embedConfiguration);

			</script> 
			';
			return $content;
		}
	}
}
