<?php
/**
 * IDP Attributes
 *
 * @package    display-idp-attributes
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Display Attribute values coming from OAuth/OpenID provider in a tabular format.
 */
class MOAzure_Attribute_Mapping {
	/**
	 * Variable to store attributes coming from IDP
	 *
	 * @var $attributes attributes coming from IDP.
	 */
	protected static $attributes;
	/**
	 * Initialize local $attributes variable.
	 */
	protected static function initialize_vars() {
		self::$attributes = MOAzure_Admin_Utils::moazure_get_option( 'moazure_test_attributes' );
	}

	/**
	 * CSS for the table to be dsiplayed for attribute mapping
	 */
	private static function emit_css() {
		?>
		<style>.mo-side-table{margin:10px 0px;color:#012970;}.mo-side-table-th {background-color: #0073c644; text-align: center; padding: 8px; border-width:1px; border-style:solid; border-color:#012970;}.mo-side-table-tr:nth-child(odd) {background-color: #0073c617;} .mo-side-table-td{padding:8px;border-width:1px; border-style:solid; border-color:#012970;word-break: break-all;}</style>
		<?php
	}
	/**
	 * Display list of attributes in table format.
	 */
	public static function emit_attribute_table() {
		self::initialize_vars();
		if ( false === self::$attributes || ! is_array( self::$attributes ) ) {
			return;
		}
		self::emit_css();
		?>
			<div id="mo_support_layout" class="mo_support_layout moazure_outer_div">
					<h2 class="moazure_attribute_map_heading" style="margin-top:5px;">Test Configuration</h2>
					<table class="mo-side-table">
						<tr class="mo-side-table-tr">
							<th class="mo-side-table-th moazure-rad">Attribute Name</th>
							<th class="mo-side-table-th moazure-rad">Attribute Value</th>
						</tr>
						<?php moazure_client_test_attrmapping_config( '', self::$attributes, 'mo-side-table-' ); ?>
					</table>
			</div>
		<?php
	}
}
