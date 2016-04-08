<?php
/*
	Version: 1.1
*/
class CarRental_Payments extends CarRental_Admin {

	private static $initiated = false;
	public static $db = array();
	
	public static function init() {
		global $wpdb, $carrental_db;
		
		self::$db = $carrental_db;
		
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
		
		// Save settings
		if (isset($_POST['save_settings'])) {
			unset($_POST['save_settings']);
			update_option('carrental_available_payments', serialize($_POST));
			self::set_flash_msg('success', __('Settings was successfully saved.', 'carrental'));
			Header('Location: ' . self::get_page_url('carrental-payments')); Exit;
		}
		
		
	}


	/**
	 * Initializes WordPress hooks
	 */
	public static function init_hooks() {
		if ( !current_user_can( 'manage_options' ) ) { return; }
		
		add_action( 'admin_menu', array( 'CarRental_Payments', 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( 'CarRental_Payments', 'load_resources' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __FILE__ ) . 'carrental-payments.php'), array( 'CarRental_Payments', 'admin_plugin_settings_link' ) );
		
		self::$initiated = true;
	}
	
	public static function admin_menu() {
	
		$hook = add_menu_page(__('Car Rental Payments', 'carrental'), __('Payments', 'carrental'), 'manage_options', 'carrental-payments', array( 'CarRental_Payments', 'display_page' ), plugin_dir_url( __FILE__ ) . '/assets/carrental_menu_icon.png');
		
	}

	public static function admin_plugin_settings_link($links) {
		$settings_link = '<a href="' . self::get_page_url() . '">' . __('Settings', 'carrental') . '</a>';
		array_unshift($links, $settings_link); 
		return $links; 
	}
	
  public static function display_page() {
  	$tpl = array();
  	self::view('carrental-payments', $tpl);
  }
  
  public static function get_page_url($page = 'carrental') {
		
		$arr = array('carrental-payments');
		
		if (in_array($page, $arr)) {
			$url = add_query_arg(array('page' => $page), admin_url('admin.php'));
		} else {
			$url = add_query_arg(array('page' => 'carrental'), admin_url('admin.php'));
		}

		return $url;
	}
	
  public static function view($name, array $args = array()) {
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}
		
		$cr_title = ucfirst(end(explode('-', $name)));
		
		$file = CARRENTAL_PAYMENTS__PLUGIN_DIR . 'views/'. $name . '.php';
		include($file);
		
	}
	
	public static function load_resources() {
		global $hook_suffix;
			
		$arr = array('carrental-payments');
		
		$exp = explode('_', $hook_suffix);
		$page = end($exp);
		
		if (in_array($page, $arr)) {
			
			
			wp_register_style( 'bootstrap.css', CARRENTAL_PAYMENTS__PLUGIN_URL . 'assets/bootstrap.css', array() );
			wp_enqueue_style( 'bootstrap.css');
			
			wp_register_style( 'carrental.css', CARRENTAL_PAYMENTS__PLUGIN_URL . 'assets/carrental.css', array() );
			wp_enqueue_style( 'carrental.css');
			
			wp_register_style( 'jquery-ui.css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css', array());
			wp_enqueue_style( 'jquery-ui.css');
			
			wp_register_style( 'jquery.dataTables.css', '//cdn.datatables.net/1.10.0/css/jquery.dataTables.css', array());
			wp_enqueue_style( 'jquery.dataTables.css');
			
			wp_deregister_script('jquery');
			wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', array());
			wp_enqueue_script('jquery');
			
			wp_deregister_script('jqueryui');
			wp_register_script('jqueryui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js', array());
			wp_enqueue_script('jqueryui');
			
			wp_register_script( 'bootstrap.min.js', CARRENTAL_PAYMENTS__PLUGIN_URL . 'assets/bootstrap.min.js', array() );
			wp_enqueue_script( 'bootstrap.min.js' );
			
			wp_register_style( 'jquery.dataTables.css', '//cdn.datatables.net/1.10.0/css/jquery.dataTables.css', array());
			wp_enqueue_style( 'jquery.dataTables.css');
			
			wp_register_script( 'jquery.dataTables.js', '//cdn.datatables.net/1.10.0/js/jquery.dataTables.js', array());
			wp_enqueue_script( 'jquery.dataTables.js' );
						
		}
	}
	
}


