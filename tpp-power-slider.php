<?php
/*
Plugin Name: TPP Power Slider
Plugin URI: https://github.com/lightbyte/tpp-power-slider
Description: A plugin to create custom sliders.
Version: 1.0
Author: Pedro Martín Valenciano
Author URI: https://tuprogramadorpersonal.com/
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Current WPBakery Page Builder version
 */
if ( ! defined( 'TPPPS_VERSION' ) ) {
	/**
	 *
	 */
	define( 'TPPPS_VERSION', '0.0.1' );
}

$dir = dirname( __FILE__ );
define( 'TPPPS_PLUGIN_DIR', $dir );
define( 'TPPPS_PLUGIN_FILE', __FILE__ );


require_once plugin_dir_path( __FILE__ ) . 'inc/tppps-admin.php';
global $tppps_manager;
if ( ! $tppps_manager ) {
	$tppps_manager = TpppsManager::getInstance();
}
