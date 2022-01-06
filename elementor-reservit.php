<?php

/**
 * Elementor Reservit WordPress Plugin
 *
 * @package ElementorReservit
 *
 * Plugin Name: Elementor Reservit
 * Description: Include Reservit into elementor
 * Version:     1.0.0
 * Author:      Emilien Mojocom
 * Text Domain: elementor-reservit
 */

define('ELEMENTOR_RESERVIT', __FILE__);

/**
 * Include the Elementor_reservit class.
 */
require plugin_dir_path(ELEMENTOR_RESERVIT) . 'class-elementor-reservit.php';
