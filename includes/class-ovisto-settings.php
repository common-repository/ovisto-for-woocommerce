<?php
/**
 * Setup menus in WP admin.
 *
 * @author   Ovisto
 * @category Admin
 * @package  WooCommerce/Admin
 * @version  1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (!class_exists('OvistoSettings')) :

class OvistoSettings {

	/**
	 * Error messages.
	 *
	 * @var array
	 */
	private static $errors   = array();

	/**
	 * Update messages.
	 *
	 * @var array
	 */
	private static $messages = array();

	/**
	 * Hook in tabs.
	 */
	public function __construct() {

		// Add menus
		add_action( 'admin_menu', array($this, 'register_menu'));

		$settings = self::get_settings();
	}

    /**
     * Add menu items.
     */
    public function register_menu() {

        add_menu_page( __( 'OVISTO', 'ovisto' ), __( 'OVISTO', 'ovisto' ), 'manage_options', 'ovisto_settings', array( $this, 'ovisto_settings_page' ), null, 59.5);

    }

	/**
	 * Handles output of the ovisto settings page in admin.
	 */
	public static function ovisto_settings_page() {

		$settings = self::get_settings();

		// Save settings if data has been posted
		if ( ! empty( $_POST ) ) {
			self::save();

			$settings = self::get_settings_from($_POST);
		}

		// Add any posted messages
		if ( ! empty( $_GET['ovisto_error'] ) ) {
			self::add_error( stripslashes( $_GET['ovisto_error'] ) );
		}

		if ( ! empty( $_GET['ovisto_message'] ) ) {
			self::add_message( stripslashes( $_GET['ovisto_message'] ) );
		}

		include 'views/html-ovisto-settings.php';
	}

	/**
	 * Default options.
	 *
	 * Sets up the default options used on the settings page.
	 */
	public static function create_options() {

		add_option('ovisto_status_options_uninstall', 1);

		add_option('ovisto_active', 0);
		add_option('ovisto_partner_id', '');
		add_option('ovisto_target_div', '');
		add_option('ovisto_banner_hide', 0);
		add_option('ovisto_banner_heading', 'Ein Gutschein wartet auf Sie...');
		add_option('ovisto_banner_heading_css', '');
		add_option('ovisto_banner_css', 'text-align: center; border: 3px solid #ededed; margin-top: 20px;');
		add_option('ovisto_country', 'de');

	}

	/**
	 * Save options.
	 *
	 * Saves options used on the settings page.
	 */
	private function save() {

		// Check permissions accepting nonce
		if (empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'ovisto_settings_updated' )) {
			wp_die( __( 'Action failed. Please refresh the page and retry.', 'ovisto' ) );
		}

		$active_tab = 'general';
		if( isset( $_GET[ 'tab' ] ) ) {
			$active_tab = $_GET[ 'tab' ];
		} // end if

		if( $active_tab == 'general' ) {

			$this->saveGeneralTab();

		} else if ( $active_tab == 'display' ) {

			$this->saveDisplayTab();

		} else if ( $active_tab == 'contact' ) {

			$this->sendContactMail();

		}

    }

	/**
	 * Add a message.
	 * @param string $text
	 */
	public static function add_message( $text ) {
		self::$messages[] = $text;
	}

	/**
	 * Add an error.
	 * @param string $text
	 */
	public static function add_error( $text ) {
		self::$errors[] = $text;
	}

	/**
	 * Enrich settings with post params
	 * @param array $post
	 *
	 * @return array
	 */
	private function get_settings_from( array $post = array() ) {
		if ( count( $post ) != 0 ) {
			return array (
				'ovisto_active'             => isset($post['ovisto_active'])? $post['ovisto_active'] : '',
				'ovisto_partner_id'         => isset($post['ovisto_partner_id'])? $post['ovisto_partner_id'] : '',
				'ovisto_banner_target'      => isset($post['ovisto_banner_target'])? $post['ovisto_banner_target'] : '',
				'ovisto_banner_hide'        => isset($post['ovisto_banner_hide'])? $post['ovisto_banner_hide'] : '',
				'ovisto_banner_heading'     => isset($post['ovisto_banner_heading'])? $post['ovisto_banner_heading'] : '',
				'ovisto_banner_heading_css' => isset($post['ovisto_banner_heading_css'])? $post['ovisto_banner_heading_css'] : '',
				'ovisto_banner_css'         => isset($post['ovisto_banner_css'])? $post['ovisto_banner_css'] : '',
				'ovisto_country'            => isset($post['ovisto_country'])? $post['ovisto_country'] : '',
				'ovisto_contact_fullname'   => isset($post['ovisto_contact_fullname'])? $post['ovisto_contact_fullname'] : '',
				'ovisto_contact_email'      => isset($post['ovisto_contact_email'])? $post['ovisto_contact_email'] : '',
				'ovisto_contact_shop_url'   => isset($post['ovisto_contact_shop_url'])? $post['ovisto_contact_shop_url'] : '',
				'ovisto_contact_tel'        => isset($post['ovisto_contact_tel'])? $post['ovisto_contact_tel'] : '',
				'ovisto_contact_company'    => isset($post['ovisto_contact_company'])? $post['ovisto_contact_company'] : '',
				'ovisto_contact_message'    => isset($post['ovisto_contact_message'])? $post['ovisto_contact_message'] : ''
			);
		}
	}

	/**
	 * Get settings from wordpress options
	 *
	 * @return array
	 */
	private function get_settings() {

		global $current_user;

		return array (
			'ovisto_active'             => get_option('ovisto_active'),
			'ovisto_partner_id'         => get_option('ovisto_partner_id'),
			'ovisto_banner_target'      => get_option('ovisto_banner_target'),
			'ovisto_banner_hide'        => get_option('ovisto_banner_hide'),
			'ovisto_banner_heading'     => get_option('ovisto_banner_heading'),
			'ovisto_banner_heading_css' => get_option('ovisto_banner_heading_css'),
			'ovisto_banner_css'         => get_option('ovisto_banner_css'),
			'ovisto_country'            => get_option('ovisto_country'),

			'ovisto_contact_fullname'   => !empty($current_user->display_name)? $current_user->display_name : '' ,
			'ovisto_contact_email'      => get_option('admin_email'),
			'ovisto_contact_shop_url'   => get_option('siteurl'),
			'ovisto_contact_tel'        => '',
			'ovisto_contact_company'    => get_option('blogname'),
			'ovisto_contact_message'    => ''
		);

	}

	/**
	 * Get Ovisto plugin status
	 *
	 * @return int
	 */
	public function getIsActive()
	{
		return get_option('ovisto_active');
	}

	/**
	 * Get partner id
	 *
	 * @return string
	 */
	public function getPartnerID()
	{
		return get_option('ovisto_partner_id');
	}

	/**
	 * Get banner heading
	 *
	 * @return int
	 */
	public function getBannerHeading()
	{
		return get_option('ovisto_banner_heading');
	}

	/**
	 * Is banner heading shown
	 *
	 * @return bool
	 */
	public function showBannerHeading() {
		$targetDiv = $this->getTargetDiv();
		$headingTxt = $this->getHeading();

		return ( $this->isBannerHidden() || empty($headingTxt) || !empty($targetDiv) );
	}

	/**
	 * Get banner heading css
	 *
	 * @return string
	 */
	public function getBannerHeadingCSS()
	{
		if ($this->isBannerHidden()) {
			return 'display: none;';
		} else {
			get_option('ovisto_banner_heading_css');
		}
	}

	/**
	 * Get banner css
	 *
	 * @return string
	 */
	public function getBannerCSS()
	{
		$targetDiv = $this->getTargetDiv();
		if ( $this->isBannerHidden()){
			return "display: none;";
		}

		return get_option('ovisto_banner_css');
	}

	/**
	 * Is banner hidden
	 *
	 * @return bool
	 */
	public function isBannerHidden()
	{
		return get_option('ovisto_banner_hide');
	}

	/**
	 * Get banner target div
	 *
	 * @return string
	 */
	public function getTargetDiv()
	{
		if ($this->isBannerHidden() || empty(get_option('ovisto_target_div'))){
			return "ovistoBanner";
		}

		return get_option('ovisto_target_div');
	}

	/**
	 * Get user data from current user id
	 *
	 * @return bool|object
	 */
	public function getUserData($user_id)
	{
		if(!$user_id) {
			return '';
		}
		$udata = get_userdata( $user_id );
		return $udata->user_registered;
	}

	/**
	 * Get country
	 *
	 * @return mixed
	 */
	public function getCountry()
	{
		return get_option('ovisto_country');
	}

	/**
	 * Setup function triggered by plugin activation
	 */
	public static function install() {

		self::create_options();

	}

	/**
	 * Output messages + errors.
	 * @return string
	 */
	public static function show_messages() {
		if ( sizeof( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		} elseif ( sizeof( self::$messages ) > 0 ) {
			foreach ( self::$messages as $message ) {
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}
	}

	/**
	 * Save general tab
	 */
	private function saveGeneralTab() {

		$filter = array(
			'ovisto_active'             => FILTER_VALIDATE_BOOLEAN,
			'ovisto_partner_id'         => FILTER_SANITIZE_STRING,
			'ovisto_banner_target'      => FILTER_SANITIZE_STRING,
			'ovisto_banner_hide'        => FILTER_VALIDATE_BOOLEAN,
			'ovisto_country'            => FILTER_SANITIZE_STRING,
		);

		// Sanitize inputs & update them
		$post = filter_input_array( INPUT_POST, $filter );

		update_option('ovisto_active', $post['ovisto_active'] ? 1 : 0);
		update_option('ovisto_banner_hide', $post['ovisto_banner_hide'] ? 1 : 0);

		$target_div = sanitize_text_field($post['ovisto_banner_target']);
		update_option('ovisto_banner_target', $target_div);

		// Country
		$ovisto_country = $post['ovisto_country'];

		if ( $ovisto_country == 'de' || $ovisto_country == 'au' ) {
			update_option('ovisto_country', $ovisto_country);
		} else {
			self::add_error("Please check your country input.");
		}

		// Partner ID
		$partner_id = $post['ovisto_partner_id'];

		if ( strlen( $partner_id ) == 8 ) {
			update_option('ovisto_partner_id', $partner_id);
		} else {
			self::add_error("Your partner ID has an invalid length. Please check your partner ID input.");
		}

		if( !count( self::$errors ) ) {
			self::add_message("Settings saved.");
		}

	}

	/**
	 * Save general tab
	 */
	private function saveDisplayTab() {

		$filter = array(
			'ovisto_banner_heading'     => FILTER_SANITIZE_STRING,
			'ovisto_banner_heading_css' => FILTER_SANITIZE_STRING,
			'ovisto_banner_css'         => FILTER_SANITIZE_STRING,
		);

		// Sanitize inputs & update them
		$post = filter_input_array( INPUT_POST, $filter );

		$banner_heading = sanitize_text_field($post['ovisto_banner_heading']);
		update_option('ovisto_banner_heading', $banner_heading);

		$banner_heading_css = sanitize_text_field($post['ovisto_banner_heading_css']);
		update_option('ovisto_banner_heading_css', $banner_heading_css);

		$banner_css = sanitize_text_field($post['ovisto_banner_css']);
		update_option('ovisto_banner_css', $banner_css);

		if( !count( self::$errors ) ) {
			self::add_message("Settings saved.");
		}
	}

	/**
	 * Send contact mail to Ovisto
	 */
	private function sendContactMail() {

		global $phpmailer;

		// Make sure the PHPMailer class has been instantiated
		// (copied verbatim from wp-includes/pluggable.php)
		// (Re)create it, if it's gone missing
		if ( !is_object( $phpmailer ) || !is_a( $phpmailer, 'PHPMailer' ) ) {
			require_once ABSPATH . WPINC . '/class-phpmailer.php';
			require_once ABSPATH . WPINC . '/class-smtp.php';
			$phpmailer = new PHPMailer( true );
		}

		$filter = array(
			'ovisto_contact_fullname'   => FILTER_SANITIZE_STRING,
			'ovisto_contact_email'      => FILTER_SANITIZE_EMAIL,
			'ovisto_contact_shop_url'   => FILTER_SANITIZE_URL,
			'ovisto_contact_tel'        => FILTER_SANITIZE_STRING,
			'ovisto_contact_company'    => FILTER_SANITIZE_STRING,
			'ovisto_contact_message'    => FILTER_SANITIZE_STRING
		);

		// Sanitize inputs & update them
		$post = filter_input_array( INPUT_POST, $filter );

		$displayName = sanitize_text_field($post['ovisto_contact_fullname']);
		$email = sanitize_text_field($post['ovisto_contact_email']);
		$url = sanitize_text_field($post['ovisto_contact_shop_url']);
		$tel = sanitize_text_field($post['ovisto_contact_tel']);
		$company = sanitize_text_field($post['ovisto_contact_company']);
		$message = sanitize_text_field($post['ovisto_contact_message']);

		if (!empty($email) ) {

			$to = 'info@ovisto.com.au';
			$subject = 'New Contact message';

			// Start output buffering
			ob_start();
			    include('views/html-ovisto-mail-template.php');
			    $body = ob_get_contents();
			ob_end_clean();

			$headers = "Content-Type: text/html; charset=UTF-8";

			// (bool) Whether the email contents were sent successfully.
			$result = wp_mail($to, $subject, $body, $headers);


			if ( $result && !count( self::$errors )){
				self::add_message("Email sent.");
			} else {
				self::add_error("Something went wrong with your mail client. Please check your settings.");
			}
		} else {
			self::add_error("Please ensure that the email and message field is filled.");
		}

		// Destroy $phpmailer so it doesn't cause issues later
		unset($phpmailer);
	}
}

endif;