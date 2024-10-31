<?php
/**
 * Plugin Name: OVISTO WooCommerce Plugin
 * Description: OVISTO WooCommerce Plugin.
 * Version: 1.0.2
 * Author: OVISTO
 * Author URI: https://ovisto.com.au/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 4.7.0
 * Tested up to: 4.7.2
 *
 * @package WooCommerce
 * @category Core
 * @author WooThemes
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('OvistoWoo')) :

/**
 * Main Ovisto WooCommerce Class.
 *
 * @class OvistoWc
 *
 */
final class OvistoWoo {

	/**
	 * OvistoWoo version.
	 *
	 * @var string
	 */
	public static $version = '1.0.2';

	/**
	 * The single instance of the class.
	 *
	 * @var OvistoSettings
	 */
	protected static $_settings = null;

	/**
	 * The single instance of the class.
	 *
	 * @var OvistoWoo
	 */
	protected static $_instance = null;

	/**
	 * Main OvistoWoo Instance.
	 *
	 * Ensures only one instance of OvistoWoo is loaded or can be loaded.
	 *
	 * @static
	 * @see OvistoWoo()
	 * @return OvistoWoo - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		include_once( 'includes/class-ovisto-settings.php' );
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init() {
		$this->init_hooks();
	}

	/**
	 * Enqueue styles.
	 *
	 * @static
	 */
	public static function enqueue_styles() {
		// Register admin styles
		wp_register_style( 'ovisto_admin_menu_styles', self::plugin_url() . '/assets/css/menu.css', array(), self::$version );

		// Sitewide menu CSS
		wp_enqueue_style( 'ovisto_admin_menu_styles' );
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		register_activation_hook( __FILE__, array( $this->get_settings(), 'install' ) );


		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		// Override WooCommerce thank-you.php template
		add_filter( 'woocommerce_locate_template', array( $this, 'ovisto_locate_template' ), 10, 3 );
		// Settings link
		add_filter( 'plugin_action_links_' . OVISTO_PLUGIN_BASENAME, array( __CLASS__, 'plugin_action_links' ) );
	}

	/**
	 * OvistoWoo Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init();
	}

	/**
	 * Define Ovisto WooCommerce Constants.
	 */
	private function define_constants() {

		$this->define( 'OVISTO_PLUGIN_FILE', __FILE__ );
		$this->define( 'OVISTO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'OVISTO_VERSION', self::$version );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Get the plugin url.
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Injecting own thank-you.php by overwriting template location method and swapping path.
	 * There is no hook or action available.
	 *
	 * @param $template
	 * @param $template_name
	 * @param $template_path
	 *
	 * @return string
	 */
	public function ovisto_locate_template( $template, $template_name, $template_path ) {
		global $woocommerce;

		$_template = $template;

		if ( !$template_path ) {
			$template_path = $woocommerce->template_url;
		}

		$plugin_path  = $this->plugin_path() . '/woocommerce/';

		// Look within passed path within the theme - this is priority
		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name
			)
		);

		// Modification: Get the template from this plugin, if it exists
		if ( !$template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		// Use default template
		if ( !$template ) {
			$template = $_template;
		}

		// Return what we found
		return $template;
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=ovisto_settings' ) . '" title="' . esc_attr( __( 'View Ovisto WooCommerce Settings', 'ovisto' ) ) . '">' . __( 'Settings', 'ovisto' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * OvistoWoo Settings Instance.
	 *
	 * Ensures only one instance of OvistoSettings is loaded or can be loaded.
	 *
	 * @static
	 * @return OvistoSettings - Settings instance.
	 */
	public function get_settings() {
		if ( is_null( self::$_settings ) ) {
			self::$_settings = new OvistoSettings();
		}

		return self::$_settings;
	}
}

endif;

/**
 * Main instance of Ovisto WooCommerce.
 *
 * Returns the main instance of Ovisto WooCommerce to prevent the need to use globals.
 *
 * @return OvistoWoo
 */
function OvistoWoo() {
	return OvistoWoo::instance();
}

// Global for backwards compatibility.
$GLOBALS['ovisto_woo'] = OvistoWoo();