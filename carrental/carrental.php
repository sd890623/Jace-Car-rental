<?php
/**
 * @package CarRental
 */
/*
Plugin Name:JACE Car Rental
Plugin URI: http://ecalypse.com/wordpresscarrental/
Description: JACE Car Rental enables complete rental management of cars, bikes and other equipment.
Version: 1.4.2
Author: melmel
Author URI: http://ecalypse.com/
License: GPLv3
Text Domain: carrental
- OK-
*/

if ( !function_exists( 'add_action' ) ) {
	echo "I'm just a plugin, not much I can do when called directly."; exit;
}

if (!isset($_SESSION)) {
	session_regenerate_id();
	session_start();
}
    
define( 'CARRENTAL_VERSION', '1.4.2');
define( 'CARRENTAL__MINIMUM_WP_VERSION', '3.9' );
define( 'CARRENTAL__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CARRENTAL__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CARRENTAL_UPDATE_URL', 'http://ecalypse.com/?page=carrental-admin&key=5ed09538b985fc962d09bd09593b5b0e23be013e');

global $carrental_db, $wpdb;
$carrental_db = array('branch' 							=> $wpdb->prefix . 'carrental_branches',
											'branch_hours' 				=> $wpdb->prefix . 'carrental_branches_hours',
											'extras' 							=> $wpdb->prefix . 'carrental_extras',
											'extras_pricing' 			=> $wpdb->prefix . 'carrental_extras_pricing',
											'fleet' 							=> $wpdb->prefix . 'carrental_fleet',
											'fleet_pricing'				=> $wpdb->prefix . 'carrental_fleet_pricing',
											'vehicle_categories' 	=> $wpdb->prefix . 'carrental_vehicle_categories',
											'fleet_extras'				=> $wpdb->prefix . 'carrental_fleet_extras',
											'pricing' 						=> $wpdb->prefix . 'carrental_pricing',
											'pricing_ranges' 			=> $wpdb->prefix . 'carrental_pricing_ranges',
											'booking' 						=> $wpdb->prefix . 'carrental_booking',
											'booking_drivers' 		=> $wpdb->prefix . 'carrental_booking_drivers',
											'booking_prices' 			=> $wpdb->prefix . 'carrental_booking_prices',
											'translations' 				=> $wpdb->prefix . 'carrental_translations',
											
											);
											
register_activation_hook( __FILE__, array( 'CarRental', 'plugin_activation' ) );
//register_deactivation_hook( __FILE__, array( 'CarRental', 'plugin_deactivation' ) );

require_once( CARRENTAL__PLUGIN_DIR . 'class.carrental.php' );
require_once( CARRENTAL__PLUGIN_DIR . 'class.carrental-widget.php' );

add_action( 'init', array( 'CarRental', 'do_session_start' ), 1);
add_action( 'init', array( 'CarRental', 'init' ) );
//add_filter('query_vars', array( 'CarRental', 'query_vars' ) );
// Add dropdown to widgets
add_action( 'in_widget_form', array( 'CarRental', 'carrental_widget_dropdown' ), 10, 3 );

// Update dropdown value on widget update
add_filter( 'widget_update_callback', array( 'CarRental', 'carrental_widget_update' ), 10, 4 );

// Filter widgets by language
add_filter( 'widget_display_callback', array( 'CarRental', 'carrental_display_widget' ), 10, 3 );
		
if ( is_admin() ) {
	require_once( CARRENTAL__PLUGIN_DIR . 'class.carrental-admin.php' );
	add_action( 'init', array( 'CarRental_Admin', 'init' ) );
}

if (isset($_GET['page']) == 'carrental' || isset($_POST['page']) == 'carrental') {
	
	// Confirm reservation
	if (isset($_POST['confirm_reservation'])) {
		add_action('template_include', array( 'CarRental', 'carrental_confirm_reservation'));
	}
	

	
	// Manage booking
	if (isset($_POST['manage_booking'])) {
		add_action('template_include', array( 'CarRental', 'carrental_manage_booking'));
	}
	
	// Terms and conditions
	if (isset($_GET['terms'])) {
		add_action('template_include', array( 'CarRental', 'carrental_terms_conditions'));
	}
	
	if (isset($_GET['confirmPage'])) {
		add_action('template_include', array( 'CarRental', 'carrental_checkout_summary'));
	}
	
	// Booking 4/4
	if (isset($_GET['summary'])) {
		add_action('template_include', array( 'CarRental', 'carrental_summary'));
	}

	elseif (isset($_POST['checkout_reservation'])) {
		add_action('template_include', array( 'CarRental', 'carrental_checkout_reservation'));
	}
			
	// Booking 3/4
	 elseif (isset($_GET['id_car'])) {
		add_action('template_include', array( 'CarRental', 'carrental_services_book'));
		
	// Booking 2/4
	} elseif (isset($_GET['book_now'])) {
		add_action('template_include', array( 'CarRental', 'carrental_choose_car'));
	}
	
	// Change currency
	if (isset($_GET['change_currency']) && !empty($_GET['currency']) && strlen($_GET['currency']) == 3) {
		$_SESSION['carrental_currency'] = trim($_GET['currency']);
		Header('Location: ' . $_SERVER['HTTP_REFERER']); Exit;
	}
	
	// Change language
	if (isset($_GET['change_language']) && !empty($_GET['language']) && strlen($_GET['language']) == 5) {
		$_SESSION['carrental_language'] = trim($_GET['language']);
		unset($_SESSION['carrental_translations']);
		Header('Location: ' . $_SERVER['HTTP_REFERER']); Exit;
	}
	
}
