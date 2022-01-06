<?php

/**
 * Widgets class.
 *
 * @category   Class
 * @package    ElementorReservit
 * @subpackage WordPress
 * @author     Emilien Mojocom <emilien@mojocom.fr>
 * @copyright  2022 Emilien Mojocom
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       link(https://www.mojocom.fr,Mojocom)
 */

namespace ElementorReservit;

// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

/**
 * Class Plugin
 *
 * Main Plugin class
 *
 * @since 1.0.0
 */
class Widgets
{

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function include_widgets_files()
	{
		require_once 'widgets/class-reservit.php';
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets()
	{
		// It's now safe to include Widgets files.
		$this->include_widgets_files();

		// Register the plugin widget classes.
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\reservit());
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct()
	{
		// Register the widgets.
		add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
	}


	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @access public
	 */
	public function widget_scripts()
	{
		$plugin = get_plugin_data(__FILE__, false, false);

		wp_register_style('reservit', plugin_dir_url(__FILE__) . 'assets/css/reservit.css', array(), $plugin['Version']);
		wp_register_script('reservit', plugin_dir_url(__FILE__) . 'assets/js/reservit.js', array('jquery'), $plugin['Version'], true);
		wp_register_script('pickadate', plugin_dir_url(__FILE__) . 'assets/js/pickadate.js', array('jquery'), $plugin['Version'], true);

	}

	public function get_style_depends()
	{
		$styles = array('reservit');

		return $styles;
	}

	public function get_script_depends()
	{
		$scripts = array('pickadate', 'reservit');

		return $scripts;
	}
}

// Instantiate the Widgets class.
Widgets::instance();
