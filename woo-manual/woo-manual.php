<?php

/**
 * Plugin Name:       Woo product manual
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Upload product manual with product.
 * Version:           1.0.0
 * Author:            Khalid Ahmed
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       woo-manual
 */

if( ! defined( 'ABSPATH' ) ) {
	exit;
}

# Include autoload file
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Plugin Class name
 */
final class My_Woo_Manual {
	/**
	 * Plugin version
	 */
	const version = "1.0";

	/**
	 * Define constructor
	 */
	private function __construct() {
		$this->define_constants();

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
	}

	/**
	 * initialize singleton instance
	 *
	 * @return \My_Woo_Manual
	 */
	public static function init(){
		static $instance  = false;

		if(! $instance) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * define required plugins constants
	 *
	 * @return void
	 */
	public function define_constants(){
		define( 'WOO_MANUAL_VERSION', self::version );
		define( 'WOO_MANUAL_FILE', __FILE__ );
		define( 'WOO_MANUAL_PATH', __DIR__ );
		define( 'WOO_MANUAL_URL', plugins_url('', WOO_MANUAL_FILE ) );
		define( 'WOO_MANUAL_ASSETS', WOO_MANUAL_URL . '/assets' );
	}

	/**
	 * initialize the plugin
	 *
	 * @return void
	 */

	public function init_plugin(){
		new \Woo\Manual\Metabox();
	}

	/**
	 * Do stuff upon plugin activation
	 *
	 * @return void
	 */
	public function activate(){
		$installed = get_option("wd_br_installed");
		if( ! $installed ) {
			update_option('wd_br_installed', time());
		}
		update_option('wd_br_installed', WOO_MANUAL_VERSION);
	}
}

/**
 * iniitalize the main plugin
 *
 * @return \My_Woo_Manual
 */
function start_my_woo_manual(){
	return My_Woo_Manual::init();
}

/**
 * Kick of plugin
 */
start_my_woo_manual();