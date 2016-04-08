<?php
/**
 * @package CarRental-Payments
 * @require CarRental 
 */
/*
Plugin Name: Ecalypse Car Rental - Payments
Plugin URI: http://ecalypse.com/wordpresscarrental/
Description: Ecalypse Car Rental enables complete rental management of cars, bikes and other equipment.
Version: 1.1
Author: HOGM s.r.o.
Author URI: http://ecalypse.com/
License: GPLv3
Text Domain: carrental-payments
*/

if ( !function_exists( 'add_action' ) ) {
	echo "I'm just a plugin, not much I can do when called directly."; exit;
}

if (!isset($_SESSION)) {
	session_regenerate_id();
	session_start();
}
    
define( 'CARRENTAL_PAYMENTS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CARRENTAL_PAYMENTS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
											
//register_activation_hook( __FILE__, array( 'CarRental_Payments', 'plugin_activation' ) );
//register_deactivation_hook( __FILE__, array( 'CarRental', 'plugin_deactivation' ) );

if (is_admin()) {
	
	require_once( CARRENTAL_PAYMENTS__PLUGIN_DIR . '../carrental/class.carrental-admin.php' );
	require_once( CARRENTAL_PAYMENTS__PLUGIN_DIR . 'class.carrental-payments.php' );
	add_action( 'init', array( 'CarRental_Payments', 'init' ) );
	
}
