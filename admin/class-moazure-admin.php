<?php
/**
 * Admin Menu
 *
 * @package    admin-menu
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Add required files.
 */
require 'partials' . DIRECTORY_SEPARATOR . 'class-moazure-admin-menu.php';

/**
 * [Description Handle admin menu]
 */
class MOAzure_Admin {

	/**
	 * Name of the plugin installed.
	 *
	 * @var plugin_name name of the plugin.
	 */
	private $plugin_name;
	/**
	 * Version of the plugin installed
	 *
	 * @var version version of the plugin installed.
	 */
	private $version;

	/**
	 * Initilaize plugin name and version for the class object
	 *
	 * @param mixed $plugin_name name of the plugin installed.
	 * @param mixed $version plugin version.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_filter( 'plugin_action_links_' . MO_AZURE_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
	}

	// Function to add the Premium settings in Plugin's section.

	/**
	 * Handle URL actions.
	 *
	 * @param mixed $actions handle actions.
	 * @return [array]
	 */
	public function add_action_links( $actions ) {

		$url           = esc_url(
			add_query_arg(
				'page',
				'moazure_settings',
				get_admin_url() . 'admin.php'
			)
		);
		$url          .= '&tab=moazure_config&app=entra-id';
		$settings_link = "<a href='$url'>Configure</a>";
		array_push( $actions, $settings_link );
		return array_reverse( $actions );
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		if ( isset( $_REQUEST['tab'] ) && 'licensing' === $_REQUEST['tab'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce recommendation because we are fetching data from URL directly and not form submission.
			wp_enqueue_style( 'mo_oauth_bootstrap_css', plugins_url( 'css/bootstrap/bootstrap.min.css', __FILE__ ), array(), '5.1.3' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		if ( isset( $_REQUEST['tab'] ) && 'licensing' === $_REQUEST['tab'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce recommendation because we are fetching data from URL directly and not form submission.
			wp_enqueue_script( 'mo_oauth_modernizr_script', plugins_url( 'js/modernizr.min.js', __FILE__ ), array(), '3.6.0', false );
			wp_enqueue_script( 'mo_oauth_popover_script', plugins_url( 'js/bootstrap/popper.min.js', __FILE__ ), array(), '2.0.1', false );
			wp_enqueue_script( 'mo_oauth_bootstrap_script', plugins_url( 'js/bootstrap/bootstrap.min.js', __FILE__ ), array(), '5.1.3', false );
		}
	}

	/**
	 * Add Plugin menu in WordPress nav bar.
	 */
	public function admin_menu() {
		$slug = 'moazure_settings';
		add_menu_page(
			'MOAzure Settings  ' . esc_html__( 'All-in-One Microsoft', 'moazure_settings' ),
			MO_AZURE_ADMIN_MENU,
			'administrator',
			$slug,
			array( $this, 'menu_options' ),
			plugin_dir_url( __FILE__ ) . 'images/miniorange.png'
		);
		add_submenu_page(
			$slug,
			MO_AZURE_ADMIN_MENU,
			'SSO Configurations',
			'administrator',
			'moazure_settings'
		);
		add_submenu_page(
			$slug,
			MO_AZURE_ADMIN_MENU,
			'Sharepoint',
			'administrator',
			'?page=moazure_settings&tab=sps_preview&app=sharepoint'
		);
		add_submenu_page(
			$slug,
			MO_AZURE_ADMIN_MENU,
			'Power BI',
			'administrator',
			'?page=moazure_settings&tab=pbi_app&app=power-bi'
		);
	}

	/**
	 * Set host name and display the main plugin page.
	 */
	public function menu_options() {
		global $wpdb;
		update_option( 'host_name', 'https://login.xecurify.com' );
		moazure_main_menu();
	}
}
