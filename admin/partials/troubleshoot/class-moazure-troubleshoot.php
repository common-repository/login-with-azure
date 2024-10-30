<?php
/**
 * FAQ
 *
 * @package    faq
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Class for handling FAQ
 */
class MOAzure_Troubleshoot {

	/**
	 * Display Troubleshooting page
	 */
	public static function moazure_troubleshooting() {
		$errorjson   = wp_json_file_decode( __DIR__ . DIRECTORY_SEPARATOR . 'moazure_errorcode.json' );
		$faqjson     = wp_json_file_decode( __DIR__ . DIRECTORY_SEPARATOR . 'moazure_faq.json' );
		$app         = ( ! empty( $_REQUEST['app'] ) && 'azure-b2c' === $_REQUEST['app'] ) ? 'azure-b2c' : 'entra-id';
		$esc_allowed = array(
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
			),
			'style'  => array(
				'table',
				'tr',
				'td',
				'th',
			),
			'br'     => array(),
			'th'     => array( 'style' ),
			'strong' => array(),
			'b'      => array(),
			'table'  => array(),
			'h2'     => array(),
			'h3'     => array(),
			'h4'     => array(),
			'tr'     => array(),
			'h6'     => array(),
			'tbody'  => array(),
			'div'    => array(),
			'td'     => array(),
		);
		?>
		<div class="moazure_table_layout moazure_outer_div">
		<div>
		<h3 class='mo_app_heading' style='font-size:23px'>
		<?php esc_html_e( 'Troubleshooting', 'all-in-one-microsoft' ); ?>
		</h3>
		<hr class='mo-divider'><br>
		</div>
		<div class="moazure_error_faq_option">
			<div class="moazure_errorcodes_options">
				<h3 class='mo_app_heading'><?php esc_html_e( 'Error Codes', 'all-in-one-microsoft' ); ?></h3>
			</div>
			<div class="moazure_faq_options">
				<h3 class='mo_app_heading'><?php esc_html_e( 'FAQs', 'all-in-one-microsoft' ); ?></h3>
			</div>
		</div>
		<br><br>
		<div class="moazure_errorcodes">

		<?php
		if ( isset( $errorjson->$app ) ) {
			?>
				<table class="moazure_troubleshoot_table">
				<tr class='mo_troubleshoot_heading'>
					<td style='width:30%'>Error</td>
					<td>Description</td>
				</tr>
			<?php
			foreach ( $errorjson->$app as  $error ) {
					echo '<tr>';
						echo ' <td>' . esc_attr( $error->error ) . '</td>';
						echo '<td>' . wp_kses( $error->desc, $esc_allowed ) . '</td>';
					echo '</tr>';
			}
			?>
				</table>
			<?php
		}
		?>
			</div>
			<div class="moazure_faq">
			<table class="moazure_troubleshoot_table">
				<tr class='mo_troubleshoot_heading'>
					<td style='width:40%'>Error</td>
					<td>Description</td>
				</tr>
				<?php
				foreach ( $faqjson as  $faq => $desc ) {

						echo '<tr>';
							echo ' <td>' . esc_attr( $faq ) . '</td>';
							echo '<td>' . wp_kses( $desc, $esc_allowed ) . '</td>';
						echo '</tr>';
				}
				?>
				</table>

				Please refer to this for more <b><a href = 'https://faq.miniorange.com/kb/oauth-openid-connect/' target = '_blank'>FAQs</a></b>.
			</div>
		</div>
		<script>
			jQuery(document).ready(function () {
				jQuery(".moazure_errorcodes").css("display","block");
				jQuery(".moazure_faq").css("display","none");
				jQuery(".moazure_errorcodes_options").css("background-color", "rgb(237 243 255 / 61%)");

				jQuery(".moazure_errorcodes_options").click(function (){
				jQuery(".moazure_errorcodes_options").css("background-color", "rgb(237 243 255 / 61%)");
				jQuery(".moazure_faq_options").css("background-color","white");
				jQuery(".moazure_faq_options").css("border","none");
				jQuery(".moazure_faq").css("display","none");
				jQuery(".moazure_errorcodes").css("display","block");
			});

				jQuery(".moazure_faq_options").click(function (){
				jQuery(".moazure_errorcodes_options").css("border","none");
				jQuery(".moazure_errorcodes_options").css("background-color","white");
				jQuery(".moazure_faq_options").css("background-color", "rgb(237 243 255 / 61%)");
				jQuery(".moazure_faq").css("display","block");
				jQuery(".moazure_errorcodes").css("display","none");
			});

			});

		</script>
		<?php
	}
}
