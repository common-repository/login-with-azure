<?php
/**
 * Customer
 *
 * @package    customer
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/** MINIORANGE enables user to log in through OAuth to apps such as Cognito, Azure, Google, EVE Online etc.
	Copyright (C) 2015  miniOrange

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

 * @package      miniOrange OAuth
 * @license      https://docs.miniorange.com/mit-license MIT/Expat
 */

/**
	This library is miniOrange Authentication Service.
	Contains Request Calls to Customer service.
 **/

require_once 'class-moazure.php';

/**
 * [Description Handle Customer APIs]
 */
class MOAzure_Client_Customer {

	/**
	 * Customer Email
	 *
	 * @var [string]
	 */
	public $email;
	/**
	 * Customer Phone Number
	 *
	 * @var [string]
	 */
	public $phone;

	/**
	 * Customer Key
	 *
	 * @var string
	 */
	private $default_customer_key = '16555';
	/**
	 * API Key
	 *
	 * @var string
	 */
	private $default_api_key = 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';

	/**
	 * Create Customer
	 *
	 * @param mixed $password miniOrange password of the user.
	 */
	public function create_customer( $password ) {
		$url         = get_option( 'host_name' ) . '/moas/rest/customer/add';
		$this->email = get_option( 'moazure_admin_email' );
		$this->phone = get_option( 'moazure_admin_phone' );
		$first_name  = get_option( 'moazure_admin_fname' );
		$last_name   = get_option( 'moazure_admin_lname' );
		$company     = get_option( 'moazure_admin_company' );

		$fields       = array(
			'companyName'    => $company,
			'areaOfInterest' => MO_AZURE_AREA_OF_INTEREST,
			'firstname'      => $first_name,
			'lastname'       => $last_name,
			'email'          => $this->email,
			'phone'          => $this->phone,
			'password'       => $password,
		);
		$field_string = wp_json_encode( $fields );
		$headers      = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args         = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Get Customer Key
	 *
	 * @param mixed $password miniOrange password of the user.
	 */
	public function get_customer_key( $password ) {
		$url          = get_option( 'host_name' ) . '/moas/rest/customer/key';
		$email        = get_option( 'moazure_admin_email' );
		$fields       = array(
			'email'    => $email,
			'password' => $password,
		);
		$field_string = wp_json_encode( $fields );

		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Submit Contact us
	 *
	 * @param mixed $email customer email.
	 * @param mixed $phone customer phone number.
	 * @param mixed $query support query.
	 * @param bool  $send_config configuration.
	 * @return [bool]
	 */
	public function submit_contact_us( $email, $phone, $query, $send_config = true ) {
		global $current_user;
		wp_get_current_user();

		$mo_oauth       = new MOAzure();
		$plugin_config  = $mo_oauth->moazure_export_plugin_config( true );
		$config_to_send = wp_json_encode( $plugin_config, JSON_UNESCAPED_SLASHES );
		$plugin_version = get_plugin_data( __DIR__ . DIRECTORY_SEPARATOR . 'mo_oauth_settings.php' )['Version'];

		$query        = '[WP ' . MO_AZURE_PLUGIN_NAME . ' ' . $plugin_version . '] ' . sanitize_text_field( $query );
		$query       .= '<br><br>Config String:<br><pre style="border:1px solid #444;padding:10px;"><code>' . $config_to_send . '</code></pre>';
		$fields       = array(
			'firstName' => $current_user->user_firstname,
			'lastName'  => $current_user->user_lastname,
			'company'   => ! empty( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '',
			'email'     => $email,
			'ccEmail'   => 'samlsupport@xecurify.com',
			'phone'     => $phone,
			'query'     => $query,
		);
		$field_string = wp_json_encode( $fields, JSON_UNESCAPED_SLASHES );

		$url = get_option( 'host_name' ) . '/moas/rest/customer/contact-us';

		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);
		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			return false;
		} elseif ( 200 !== $response['response']['code'] ) {
			return false;
		}
		return true;
	}

	/**
	 * Call setup
	 *
	 * @param mixed $email customer email.
	 * @param mixed $issue issue faced by customer.
	 * @param mixed $issue_description complete description.
	 * @param mixed $desc description.
	 * @param mixed $call_date date to setup call.
	 * @param mixed $call_time_zone timezone to setup call.
	 * @param mixed $call_time time for he scheduled call.
	 * @param mixed $ist_date IST date.
	 * @param mixed $ist_time IST time.
	 * @param mixed $phone phone number of the user.
	 * @param bool  $send_config configuration.
	 * @return [bool]
	 */
	public function submit_setup_call( $email, $issue, $issue_description, $desc, $call_date, $call_time_zone, $call_time, $ist_date, $ist_time, $phone, $send_config = true ) {
		if ( ! $this->check_internet_connection() ) {
			return;
		}
		$url = get_option( 'host_name' ) . '/moas/api/notify/send';

		$plugin_version = get_plugin_data( __DIR__ . DIRECTORY_SEPARATOR . 'mo_oauth_settings.php' )['Version'];

		$customer_key = $this->default_customer_key;
		$api_key      = $this->default_api_key;

		$current_time_in_millis = self::get_timestamp();
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$from_email             = $email;
		$subject                = 'Call Request: WordPress ' . MO_AZURE_PLUGIN_NAME . ' ' . $plugin_version;
		$site_url               = site_url();

		global $user;
		$user = wp_get_current_user();

		$mo_oauth       = new MOAzure();
		$plugin_config  = $mo_oauth->moazure_export_plugin_config( true );
		$config_to_send = wp_json_encode( $plugin_config, JSON_UNESCAPED_SLASHES );
		$desc          .= '<br><br>Config String:<br><pre style="border:1px solid #444;padding:10px;"><code>' . $config_to_send . '</code></pre>';

		if ( $issue_description ) {
			$content = ! empty( $_SERVER['SERVER_NAME'] ) ? '<div>Hello,<br><br>First Name : ' . $user->user_firstname . '<br><br>Last Name : ' . $user->user_lastname . '<br><br>Company : <a href="' . esc_attr( sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) ) . '" target="_blank" >' . esc_attr( sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) ) . '</a><br><br>Email : <a href="mailto:' . $from_email . '" target="_blank">' . $from_email . '</a><br><br>Preferred time (' . $call_time_zone . ') : ' . $call_time . ', ' . $call_date . '<br><br>IST time : ' . $ist_time . ', ' . $ist_date . '<br><br>Issue : ' . $issue . ' <b>:</b> ' . $issue_description . '<br><br>Description : ' . $desc . '</div>' : '';
		} else {
			$content = ! empty( $_SERVER['SERVER_NAME'] ) ? '<div>Hello,<br><br>First Name : ' . $user->user_firstname . '<br><br>Last Name : ' . $user->user_lastname . '<br><br>Company : <a href="' . esc_attr( sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) ) . '" target="_blank" >' . esc_attr( sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) ) . '</a><br><br>Email : <a href="mailto:' . $from_email . '" target="_blank">' . $from_email . '</a><br><br>Preferred time (' . $call_time_zone . ') : ' . $call_time . ', ' . $call_date . '<br><br>IST time : ' . $ist_time . ', ' . $ist_date . '<br><br>Issue : ' . $issue . '<br><br>Description : ' . $desc . '</div>' : '';
		}
		$fields                   = array(
			'customerKey' => $customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customer_key,
				'fromEmail'   => $from_email,
				'bccEmail'    => 'samlsupport@xecurify.com',
				'fromName'    => 'miniOrange',
				'toEmail'     => 'samlsupport@xecurify.com',
				'toName'      => 'samlsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string             = wp_json_encode( $fields );
		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		} elseif ( 200 !== $response['response']['code'] ) {
			return false;
		}

		return true;
	}

	/**
	 * Get Timestamp
	 *
	 * @return [string]
	 */
	public function get_timestamp() {
		$url     = get_option( 'host_name' ) . '/moas/rest/mobile/get-timestamp';
		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => array(),
			'timeout'     => '15',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Check Customer.
	 *
	 * @return [string]
	 */
	public function check_customer() {
			$url   = get_option( 'host_name' ) . '/moas/rest/customer/check-if-exists';
			$email = get_option( 'moazure_admin_email' );

			$fields       = array(
				'email' => $email,
			);
			$field_string = wp_json_encode( $fields );
			$headers      = array(
				'Content-Type'  => 'application/json',
				'charset'       => 'UTF - 8',
				'Authorization' => 'Basic',
			);
			$args         = array(
				'method'      => 'POST',
				'body'        => $field_string,
				'timeout'     => '15',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => $headers,

			);

			$response = wp_remote_post( $url, $args );
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				echo 'Something went wrong: ' . esc_attr( $error_message );
				exit();
			}

			return wp_remote_retrieve_body( $response );
	}

	/**
	 * Send Email
	 *
	 * @param mixed $email customer email.
	 * @param mixed $reply reply to.
	 * @param mixed $message email message.
	 * @param mixed $subject email subject.
	 * @param bool  $skip skip.
	 *
	 * @return [type]
	 */
	public function moazure_send_email_alert( $email, $reply, $message, $subject, $skip = false ) {

		if ( ! $this->check_internet_connection() ) {
			return;
		}
		$url = get_option( 'host_name' ) . '/moas/api/notify/send';

		$plugin_version = get_plugin_data( __DIR__ . DIRECTORY_SEPARATOR . 'mo_oauth_settings.php' )['Version'];

		$customer_key = $this->default_customer_key;
		$api_key      = $this->default_api_key;

		$current_time_in_millis = self::get_timestamp();
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$customer_key_header    = 'Customer-Key: ' . $customer_key;
		$timestamp_header       = 'Timestamp: ' . $current_time_in_millis;
		$authorization_header   = 'Authorization: ' . $hash_value;
		$from_email             = $email;
		$site_url               = site_url();
		$time_spent_in_plugin   = '0 days 0 hours';

		global $user;
		$user  = wp_get_current_user();
		$query = '[WP ' . MO_AZURE_PLUGIN_NAME . ' ' . $plugin_version . '] : ' . sanitize_text_field( $message );

		$content = ! empty( $_SERVER['SERVER_NAME'] ) ? '<div >Hello, <br><br>First Name :' . $user->user_firstname . '<br><br>Last  Name :' . $user->user_lastname . '   <br><br>Company :<a href="' . esc_attr( sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) ) . '" target="_blank" >' . esc_attr( sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) ) . '</a><br><br>Email :<a href="mailto:' . $from_email . '" target="_blank">' . $from_email . '</a><br><br>Query :' . $query : '';
		if ( false === $skip ) {
			$content .= '<br><br>' . $reply;
			$content .= '</div>';
		} else {
			$content .= '<br><br><p style="color:red;">Do not reply here, customer has skipped feedback</p></div>';
		}

		$fields                   = array(
			'customerKey' => $customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customer_key,
				'fromEmail'   => $from_email,
				'bccEmail'    => 'samlsupport@xecurify.com',
				'fromName'    => 'Xecurify',
				'toEmail'     => 'samlsupport@xecurify.com',
				'toName'      => 'samlsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string             = wp_json_encode( $fields );
		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}
		return $response['body'];
	}

	/**
	 * Send Video Demo alert.
	 *
	 * @param mixed $email customer email address.
	 * @param mixed $ist_date date for scheduling demo.
	 * @param mixed $query Demo query.
	 * @param mixed $ist_time time for scheduling demo.
	 * @param mixed $integrations_selected selected azure integrations.
	 * @param mixed $subject email subject.
	 * @param mixed $call_time_zone scheduled time zone.
	 * @param mixed $call_time sceduled time for meeting.
	 * @param mixed $call_date scheduled date for setting up the demo.
	 * @return [void]
	 */
	public function moazure_send_video_demo_alert( $email, $ist_date, $query, $ist_time, $integrations_selected, $subject, $call_time_zone, $call_time, $call_date ) {

		if ( ! $this->check_internet_connection() ) {
			return;
		}
		$url = get_option( 'host_name' ) . '/moas/api/notify/send';

		$customer_key = $this->default_customer_key;
		$api_key      = $this->default_api_key;

		$current_time_in_millis = self::get_timestamp();
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$customer_key_header    = 'Customer-Key: ' . $customer_key;
		$timestamp_header       = 'Timestamp: ' . $current_time_in_millis;
		$authorization_header   = 'Authorization: ' . $hash_value;
		$from_email             = $email;
		$site_url               = site_url();

		global $user;
		$user    = wp_get_current_user();
		$content = '<div >Hello, </a><br><br>Email :<a href="mailto:' . $from_email . '" target="_blank">' . $from_email . '</a><br><br> Customer local time (' . $call_time_zone . ') : ' . $call_time . ' on ' . $call_date . '<br><br>IST format    : ' . $ist_time . ' on ' . $ist_date . '<br><br>Azure Integrations : ' . $integrations_selected . '<br><br>Query : [Demo Request]<br>' . $query . '<br><br>WordPress Site URL: ' . $site_url . '</div>';

		$fields                   = array(
			'customerKey' => $customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customer_key,
				'fromEmail'   => $from_email,
				'bccEmail'    => 'samlsupport@xecurify.com',
				'fromName'    => 'Xecurify',
				'toEmail'     => 'samlsupport@xecurify.com',
				'toName'      => 'samlsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string             = wp_json_encode( $fields );
		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}
	}

	/**
	 * Reset password
	 *
	 * @param mixed $email customer email.
	 * @return [string]
	 */
	public function moazure_forgot_password( $email ) {
		$url = get_option( 'host_name' ) . '/moas/rest/customer/password-reset';
		/* The customer Key provided to you */
		$customer_key = get_option( 'moazure_admin_customer_key' );

		/* The customer API Key provided to you */
		$api_key = get_option( 'moazure_admin_api_key' );

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$current_time_in_millis = self::get_timestamp();

		/* Creating the Hash using SHA-512 algorithm */
		$string_to_hash = $customer_key . $current_time_in_millis . $api_key;
		$hash_value     = hash( 'sha512', $string_to_hash );

		$customer_key_header  = 'Customer-Key: ' . $customer_key;
		$timestamp_header     = 'Timestamp: ' . number_format( $current_time_in_millis, 0, '', '' );
		$authorization_header = 'Authorization: ' . $hash_value;

		$fields = '';

		// *check for otp over sms/email
		$fields = array(
			'email' => $email,
		);

		$field_string = wp_json_encode( $fields );

		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Checking Internet connection
	 *
	 * @return [bool]
	 */
	public function check_internet_connection() {
		return (bool) @fsockopen( 'login.xecurify.com', 443, $errno, $errstr, 5 ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fsockopen,WordPress.PHP.NoSilencedErrors.Discouraged -- Using default PHP function to check socket connection.
	}
}
