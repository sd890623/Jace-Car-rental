<?php
/*
	Version: 1.4.2
	
	@created: 2014-12-29
	@todo: ---
	
*/	
class CarRental {

	private static $initiated = false;
	public static $db = array();
	public static $hash_salt = '9D9D051447F79094306A54E8B28CFFD0C74DB5A6';
	
	public static function init() {
		global $wpdb, $carrental_db;
		
		self::$db = $carrental_db;
		
		if ( ! self::$initiated ) {
			self::init_hooks();
			//self::rewrite_rules();
		}
		
	}


	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		load_plugin_textdomain( 'carrental', false, dirname( plugin_basename( __FILE__ ) ));
		
		$primary_language = 'en_GB';
		$user_set_language = get_option('carrental_primary_language');
		if ($user_set_language && !empty($user_set_language)) {
			$primary_language = $user_set_language;
		}
		
		if (!isset($_SESSION['carrental_language'])) {
			$_SESSION['carrental_language'] = $primary_language;
		}
		
		if (isset($_SESSION['carrental_language']) && !isset($_SESSION['carrental_translations'])) {
			$_SESSION['carrental_translations'] = self::load_translations($_SESSION['carrental_language']);
		}
		
		self::$initiated = true;
	}
	
	public static function do_session_start() {
		if (!isset($_SESSION)) {
			session_regenerate_id();
			session_start();
		}
	}
	/*
	public static function rewrite_rules() {
		global $wp_rewrite;
		$available_languages = unserialize(get_option('carrental_available_languages'));
		if (empty($available_languages)) {
			$available_languages = array();
		}
		include dirname(realpath(__FILE__)) . '/languages.php';
		$urlLanguages = array();
		foreach ($available_languages as $lng_key => $lng) {
			$urlLanguages[] = $languages[$lng_key]['country-www'];
		}
		$flush = false;
		$rewrite_rules_array = (array)$wp_rewrite;
		if (!isset($rewrite_rules_array['('.implode('|', $urlLanguages).')/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$'])) {
			$flush = true;
		}
		add_rewrite_rule('('.implode('|', $urlLanguages).')/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$','index.php?year=$matches[2]&monthnum=$matches[3]&day=$matches[4]&lng=$matches[1]','top');
		add_rewrite_rule('('.implode('|', $urlLanguages).')/(.+?)(/[0-9]+)?/?$','index.php?pagename=$matches[2]&page=$matches[3]&lng=$matches[1]','top');
		if ($flush) {
			$wp_rewrite->flush_rules(false);
		}
	}
	
	public static function query_vars($public_query_vars) {
		$public_query_vars[] = "lng";
		return $public_query_vars;
	}
  */
  public static function t($string) {
  	
  	if (isset($_SESSION['carrental_translations']) && isset($_SESSION['carrental_translations'][$string]) && !empty($_SESSION['carrental_translations'][$string])) {
			return $_SESSION['carrental_translations'][$string];
		}
  	
		return $string;
	}
  
  /**
   *	Terms and conditions (in AJAX window)
   */
	public function carrental_terms_conditions() {
		try {
    	
    	$lang = 'en_GB';
    	if (!empty($_SESSION['carrental_language']) && strlen($_SESSION['carrental_language']) == 5) {
				$lang = $_SESSION['carrental_language'];
			}
			
    	$terms = get_option('carrental_terms_conditions_' . $lang);
    	if (!$terms || empty($terms)) {
				$terms = get_option('carrental_terms_conditions_en_GB');
			}
			
			print(nl2br($terms));
			exit;
    	
	  } catch (Exception $e) {
	  	return false;
	  }
	}
	 	   
  /**
   *	Manage booking
   */	   
  public static function carrental_manage_booking() {
		global $wpdb;
		
		try {
			
			// Check if order exists in database
			$exists = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM `' . CarRental::$db['booking'] . '`
																							 WHERE `id_order` = %s AND `email` = %s', $_POST['id_order'], $_POST['email']));
																							 
			if ((int) $exists > 0) {
				$order_hash = self::generate_hash($_POST['id_order'], $_POST['email']);
				Header('Location: ' . home_url() . '?page=carrental&summary=' . $order_hash); Exit;
			} else {
				$_SESSION['carrental_flash_msg'] = array('status' => 'danger', 'msg' => 'noexist');
				if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
					Header('Location: ' . $_SERVER['HTTP_REFERER']); Exit;
				} else {
					Header('Location: ' . home_url()); Exit;
				}
			}
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
  
  /**
   *	Summary
   */	   
  public static function carrental_summary() {
		
		$summary = self::get_order_summary($_GET['summary']);
		self::template('summary', array('summary' => $summary));
		
	}
	
	
  /**
   *	Booking 4/4
   */
	public static function carrental_confirm_reservation() {
		
		$order_hash = self::save_booking($_POST);
		
		if ((int) $_POST['paypal'] == 1 && (float) $_POST['total_rental'] > 0) {
			echo (float) $_POST['total_rental'];
			$paypal = get_option('carrental_paypal');
			$available_payments = unserialize(get_option('carrental_available_payments'));
			if (isset($available_payments) && isset($available_payments['carrental-paypal-security-deposit']) && (float)$available_payments['carrental-paypal-security-deposit'] > 0) {
				// if paypal security deposit is set
				$_POST['total_rental'] = $_POST['total_rental'] * ((float)$available_payments['carrental-paypal-security-deposit']/100);
				if (isset($available_payments['carrental-paypal-security-deposit-round'])) {
					if ($available_payments['carrental-paypal-security-deposit-round'] == 'up') {
						$_POST['total_rental'] = ceil($_POST['total_rental']);
					} elseif ($available_payments['carrental-paypal-security-deposit-round'] == 'down') {
						$_POST['total_rental'] = floor($_POST['total_rental']);
					} else {
						$_POST['total_rental'] = round($_POST['total_rental'], 2);
					}
				} else {
					$_POST['total_rental'] = round($_POST['total_rental'], 2);
				}
			}
			
		// Redirect to PayPal
	    $query = array();
	    $query['notify_url'] = '';
	    $query['cmd'] = '_xclick';
	    $query['business'] = $paypal;
	    $query['email'] = $_POST['email'];
	    $query['item_name'] = 'Car Rental Reservation #' . $order_hash;
	    $query['quantity'] = 1;
		$query['return'] = home_url(). '?page=carrental&summary='.$order_hash;		
	    $query['amount'] = number_format((float) $_POST['total_rental'], 2, '.', '');
			$query['currency_code'] = $_POST['currency_code'];
	    
	    // Prepare query string
	    $query_string = http_build_query($query);
	
	    Header('Location: https://www.paypal.com/cgi-bin/webscr?' . $query_string);
			
		} else {
			Header('Location: ' . home_url() . '?page=carrental&summary=' . $order_hash); Exit;
		}
		
	}
	
	
  /**
   *	Booking 3/4
   */	   
  public static function carrental_services_book() {
		
		// Locations + business hours
		$locations = self::get_locations();
		$vehicle_cats = self::get_vehicle_categories();
		$vehicle_names = self::get_vehicle_names();
		$vehicle = self::get_vehicle_detail($_GET['id_car'], $_GET);
		$extras = self::get_vehicle_extras($_GET['id_car'], $_GET);
		
		wp_register_style( 'jquery-ui.css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css', array());
		wp_enqueue_style( 'jquery-ui.css');
		
		self::template('services-book', array('locations' => $locations,
																			 'vehicle_cats' => $vehicle_cats,
																			 'vehicle_names' => $vehicle_names,
																			 'vehicle' => $vehicle,
																			 'extras' => $extras));
		
	}
	
	/**
	 *	Booking 2/4
	 */	 	
	public static function carrental_choose_car() {
		
		// Locations + business hours
		$locations = self::get_locations();
		$vehicle_cats = self::get_vehicle_categories();	
		$vehicle_names = self::get_vehicle_names();
		$vehicles = self::get_vehicles($_GET);
		
		wp_register_style( 'jquery-ui.css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css', array());
		wp_enqueue_style( 'jquery-ui.css');
		
		self::template('choose-car', array('locations' => $locations,
																			 'vehicle_cats' => $vehicle_cats,
																			 'vehicle_names' => $vehicle_names,
																			 'vehicles' => $vehicles));
		
	}
	
  public static function get_locations() {
		global $wpdb;
		
		try {
		
			$branches = $wpdb->get_results('SELECT * 
																		 	FROM `' . CarRental::$db['branch'] . '`
																		 	WHERE `deleted` IS NULL
																 	 			AND `active` = 1
																		 	ORDER BY `id_branch` DESC');
			
			$data = array();
			if ($branches && !empty($branches)) {
				foreach ($branches as $key => $val) {
					$data[$val->id_branch] = $val;
					$data[$val->id_branch]->hours = $wpdb->get_results(
																					 $wpdb->prepare('SELECT * FROM `' . CarRental::$db['branch_hours'] . '`
																												 	 WHERE `id_branch` = %d ORDER BY `day` ASC', $val->id_branch));
					
				}
			
			}
			
			return $data;
			
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	
	/**
	 *	Get location name
	 */	 	
	public function get_location_name($id_branch) {
		global $wpdb;
		
		try {
			return $wpdb->get_var($wpdb->prepare('SELECT `name` FROM `' . CarRental::$db['branch'] . '` WHERE `id_branch` = %d', $id_branch));
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	/**
	 *	Get location ID
	 */	 	
	public function get_location_id($id_branch) {
		global $wpdb;
		
		try {
			return $wpdb->get_var($wpdb->prepare('SELECT `bid` FROM `' . CarRental::$db['branch'] . '` WHERE `id_branch` = %d', $id_branch));
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	
	/**
	 *	Get vehicle categories
	 */	 	
	public function get_vehicle_categories() {
		global $wpdb;
		
		try {
			
			return $wpdb->get_results('SELECT vc.*,
																	 (SELECT COUNT(*) FROM `' . CarRental::$db['fleet'] . '` f
																	  WHERE f.`id_category` = vc.`id_category` AND f.`deleted` IS NULL) as `no_vehicles`
																 FROM `' . CarRental::$db['vehicle_categories'] . '` vc
																 WHERE `deleted` IS NULL
																 ORDER BY `id_category` ASC');
			
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	/**
	 *	Get vehicle names
	 */	 	
	public function get_vehicle_names() {
		global $wpdb;
		
		try {
			
			return $wpdb->get_results('SELECT f.`name`, COUNT(*) as `count`
																 FROM `' . CarRental::$db['fleet'] . '` f
																 WHERE `deleted` IS NULL
																 GROUP BY f.`name`
																 ORDER BY f.`name` ASC');
			
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	/**
	 *	Get vehicles (filters)
	 */	 	
	public function get_vehicles($filters) {
		global $wpdb;
		
		try {
			
			$limit = 20;
			$page = ((isset($filters['page']) && $filters['page'] > 0) ? (int) $filters['page'] : 1);
			$start = $limit * ($page - 1);
			$order = 'f.`name` ASC';
			
			// Apply filters
			$where = ' f.`deleted` IS NULL ';
			
				// Date filters
				if (!isset($filters['fd']) || empty($filters['fd'])) { $filters['fd'] = Date('Y-m-d'); }
				if (!isset($filters['fh']) || empty($filters['fh'])) { $filters['fh'] = '12:00'; }
				if (!isset($filters['td']) || empty($filters['td'])) { $filters['td'] = Date('Y-m-d', strtotime("+1 day")); }
				if (!isset($filters['th']) || empty($filters['th'])) { $filters['th'] = '12:00'; }
			
				$date_from = date('Y-m-d', strtotime($filters['fd'])) . ' ' . $filters['fh'];
				$date_to = date('Y-m-d', strtotime($filters['td'])) . ' ' . $filters['th'];
				
				// Promocode
				$promocode = ((isset($filters['promo']) && !empty($filters['promo'])) ? $filters['promo'] : NULL);
				
				// Any location search
				$anylocation = get_option('carrental_any_location_search');
				if ($anylocation && $anylocation == 'no' && !empty($filters['el'])) {
					$where .= ' AND f.`id_branch` = ' . (int) $filters['el'] . ' '; 
				}
				
				// Allow car overbooking
				$overbooking = get_option('carrental_overbooking');
				if ($overbooking && $overbooking == 'no') {
	    	
					// Check reservations for cars
					$where .= ' AND ((SELECT COUNT(*) FROM `' . CarRental::$db['booking'] . '` b
														WHERE b.`vehicle_id` = f.`id_fleet`
															AND b.`deleted` IS NULL
															AND ((b.`enter_date` <= "' . $wpdb->escape($date_from) . '" AND b.`return_date` >= "' . $wpdb->escape($date_from) . '") OR
																	 (b.`enter_date` <= "' . $wpdb->escape($date_to) . '" AND b.`return_date` >= "' . $wpdb->escape($date_to) . '") OR
																	 (b.`enter_date` >= "' . $wpdb->escape($date_from) . '" AND b.`return_date` <= "' . $wpdb->escape($date_to) . '"))
														) < f.`number_vehicles`) ';
					
				}
				
				// Additional filters
				$flt = array();
				
				if (isset($_GET['flt']) && !empty($_GET['flt'])) {
					foreach (explode('|', $_GET['flt']) as $kD => $vD) {
						list($key, $val) = explode(':', $vD);
						$flt[$key] = $val;
					}
				}
				
				// add from main filter
				if (isset($filters['cats']) && !isset($flt['cats'])) {
					$flt['cats'] = $filters['cats'];
				}
				
				// Filter: extras
				if (isset($flt['ac']) && (int) $flt['ac'] == 1 && (!isset($flt['nac']) || $flt['nac'] == 0)) {
					$where .= ' AND f.`ac` = 1 ';
				} elseif (isset($flt['nac']) && (int) $flt['nac'] == 1 && (!isset($flt['ac']) || $flt['ac'] == 0)) {
					$where .= ' AND f.`ac` = 0 ';
				}
				
				// Filter: fuel
				if (isset($flt['pl']) && (int) $flt['pl'] == 1 && (!isset($flt['dl']) || $flt['dl'] == 0)) {
					$where .= ' AND f.`fuel` = 1 ';
				} elseif (isset($flt['dl']) && (int) $flt['dl'] == 1 && (!isset($flt['pl']) || $flt['pl'] == 0)) {
					$where .= ' AND f.`fuel` = 2 ';
				}
				
				// Filter: passengers
				if (isset($flt['sp']) && isset($flt['ep']) && (int) $flt['ep'] > 0) {
					$where .= ' AND f.`seats` >= ' . (int) $flt['sp'] . ' ';
					$where .= ' AND f.`seats` <= ' . (int) $flt['ep'] . ' ';
				}
				
				// Filter: category
				if (isset($flt['cats']) && !empty($flt['cats'])) {
					$cats = explode(',', $flt['cats']);
					foreach ($cats as $key => $val) {
						$cats[$key] = (int) $val;
					}
					$where .= ' AND f.`id_category` IN (' . implode(',', $cats) . ') ';
				}
				
				// Filter: vehicle names
				if (isset($flt['vh']) && !empty($flt['vh'])) {
					$vh = explode(',', $flt['vh']);
					foreach ($vh as $key => $val) {
						$vh[$key] = $wpdb->escape($val);
					}
					$where .= ' AND f.`name` IN ("' . implode('","', $vh) . '") ';
				}
				
			$data = array();
			$sql = 'SELECT SQL_CALC_FOUND_ROWS f.*
						  FROM `' . CarRental::$db['fleet'] . '` f
						  WHERE ' . $where . '
						  ORDER BY ' . $order . '
							LIMIT ' . $start . ', ' . $limit;
			
			$data['results'] = $wpdb->get_results($sql);
			$data['count'] = $wpdb->get_var("SELECT FOUND_ROWS();");
    	
			// Get prices for results
			if ($data['results'] && !empty($data['results'])) {
				foreach ($data['results'] as $key => $val) {
					$data['results'][$key]->prices = self::get_prices('fleet', $val->id_fleet, $date_from, $date_to, $promocode);
					
					// Filter: price range
					if (isset($flt['spr']) && isset($flt['epr']) && (int) $flt['epr'] > 0) {
						if ((int) $flt['spr'] > $data['results'][$key]->prices['price'] ||
								(int) $flt['epr'] < $data['results'][$key]->prices['price']) {
							unset($data['results'][$key]);
							--$data['count'];
						}
					}
					
				}
			}
			
			
			// Sort by price
			if (isset($filters['order']) && !empty($filters['order']) && !empty($data['results'])) {
				if ($filters['order'] == 'price') {
					
					$prices = array();
					foreach ($data['results'] as $key => $val) {
						$prices[$key] = $val->prices['price'];
					}
					array_multisort($prices, SORT_NUMERIC, SORT_ASC, $data['results']);
					
				}
			}
			
			
    	return $data;
    	
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	/**
	 *	Get price for the vehicle
	 */	 	
	public function get_prices($type = 'fleet', $id, $date_from, $date_to, $promocode = NULL) {
		global $wpdb;
		
		try {
			
			$date_diff = abs(strtotime($date_to) - strtotime($date_from));
			$diff_days = intval($date_diff / 86400);
			$diff_hours = intval(($date_diff % 86400) / 3600);
			$diff_minutes = intval(($date_diff % 86400) / 60);
			
			if ($diff_days >= 1 && ($diff_hours > 0 || $diff_minutes > 0)) {
				++$diff_days; // If you pass by 30 minutes and more, it 1 day more
			}
			
			$pr_type = (($diff_days == 0 && $diff_hours > 0) ? 2 : 1); // 1 - days, 2 - hours
			$pr_value = (($pr_type == 2) ? $diff_hours : $diff_days);
			$db_name = (($type == 'extras') ? CarRental::$db['extras_pricing'] : CarRental::$db['fleet_pricing']);
			$db_name_global = (($type == 'extras') ? CarRental::$db['extras'] : CarRental::$db['fleet']);
			$id_column = (($type == 'extras') ? 'id_extras' : 'id_fleet');
			
			$data = array();
			$data['diff_days'] = $diff_days;
			$data['diff_hours'] = $diff_hours;
			$data['pr_type'] = $pr_type; // 1 - days, 2 - hours
			$data['pr_value'] = $pr_value; // days/hours
			$data['promocode'] = $promocode;
			
			$active_days = array();
			$day_from = Date('w', strtotime($date_from));
			$day_to = Date('w', strtotime($date_to));
			
			$active_days[] = $day_from;
			$counter = 1;
			while ($next_day < strtotime($date_to)) {
				$next_day = mktime(0,0,0, Date('m', strtotime($date_from)), Date('d', strtotime($date_from)) + $counter, Date('Y', strtotime($date_from)));
				if (!in_array(Date('w', $next_day), $active_days)) {
					$active_days[] = Date('w', $next_day);
				}
				++$counter;
			}			
			
			// try find pricing scheme
			$scheme = false;
			$schemes = $wpdb->get_results($wpdb->prepare('SELECT p.name, fp.`id_pricing`, p.`type`, p.`maxprice`, p.`currency`, p.`vat`, p.`onetime_price`, pr.`price`, p.`active_days`, p.`rate_id`, fp.`valid_to`, fp.`valid_from`, p.`promocode`
							FROM `' . $db_name . '` fp
							LEFT JOIN `' . CarRental::$db['pricing'] . '` p ON p.`id_pricing` = fp.`id_pricing`
							LEFT JOIN `' . CarRental::$db['pricing_ranges'] . '` pr ON pr.`id_pricing` = p.`id_pricing`
							WHERE fp.`' . $id_column . '` = %d
								AND p.`deleted` IS NULL
								AND p.`active` = 1
								AND (p.`promocode` = %s OR p.`promocode` = "")
								AND ((p.`type` = 2 AND pr.`type` = %d AND pr.`no_from` <= %d AND (pr.`no_to` >= %d || pr.`no_to` = 0)) OR 
										 (p.`type` = 1 AND p.`onetime_price` >= 0))
							ORDER BY fp.`priority` ASC
', $id, $promocode, $data['pr_type'], $data['pr_value'], $data['pr_value']));
			
			$date_from_only_day = strtotime(date('Y-m-d', strtotime($date_from)) . ' 00:00:00');
			$date_to_only_day = strtotime(date('Y-m-d', strtotime($date_to)) . ' 00:00:00');
			// DEBUG-MODE: echo '<br><br>Vypocet cen pro auto ID '.$id;
			foreach ($schemes as $s) {
				// DEBUG-MODE: echo '<br>Testuju schema '.$s->name.' = ';
				// if it is promo schema, then validate from and to date
				if ($s->promocode != '') {
					if (strtotime($s->valid_from) > $date_from_only_day || ($s->valid_to != '0000-00-00' && strtotime($s->valid_to) < $date_to_only_day)) {
						// DEBUG-MODE: echo 'Nevleze se cele do promocode';
						continue;
					}
				}
				
				// if schema is not for all days, then all days must be in schema days
				if ($s->active_days != '1;2;3;4;5;6;0' && $s->active_days != '') {
					$db_active_days = explode(';', $s->active_days);
					foreach ($active_days as $a) {
						if (!in_array($a, $db_active_days)) {
							// DEBUG-MODE: echo 'Vsechny dny nejsou v povolenych';
							continue 2;
						}
					}
				}
				
				if ($s->valid_to != '0000-00-00' && strtotime($s->valid_to) < $date_from_only_day) {
					// DEBUG-MODE: echo 'Schema konci driv nez zacatek rezervace.';
					continue;
				}
				
				if (strtotime($s->valid_from) > $date_to_only_day) {
					// DEBUG-MODE: echo 'Schema zacina pozdeji nez posledni datum rezervace.';
					continue;
				}
				
				if (strtotime($s->valid_from) > $date_from_only_day && strtotime($s->valid_from) > $date_to_only_day) {
					// DEBUG-MODE: echo 'Schema zacina pozdeji nez prvni datum rezervace a zacina take pozdeji nez posledni datum rezervace.';
					continue;
				}
				
				// DEBUG-MODE: echo '-- Pro datum od '.date('d.m.Y',$date_from_only_day).' do '.date('d.m.Y',$date_to_only_day).' Vybrano schema: '.$s->name;
				$scheme = $s;
				break;
			}
			
			if ($scheme && !empty($scheme)) {
				
			/* DEBUG echo 'Priority: ' . $id . ' => '; var_dump($scheme); echo '<br /><br />'; /**/
				
			// Get global price scheme
			} else {
				// DEBUG-MODE: echo '-- Pro datum od '.date('d.m.Y',$date_from_only_day).' do '.date('d.m.Y',$date_to_only_day).' Vybrano defaultni schema.';
				$sql = 'SELECT p.`id_pricing`, p.`type`, p.`maxprice`, p.`currency`, p.`vat`, p.`onetime_price`, pr.`price`, p.`active_days`, p.`rate_id`
									FROM `' . $db_name_global . '` f
									LEFT JOIN `' . CarRental::$db['pricing'] . '` p ON p.`id_pricing` = f.`global_pricing_scheme`
									LEFT JOIN `' . CarRental::$db['pricing_ranges'] . '` pr ON pr.`id_pricing` = p.`id_pricing`
									WHERE f.`' . $id_column . '` = %d
										AND p.`deleted` IS NULL
										AND p.`active` = 1
										AND (p.`promocode` = %s OR p.`promocode` = "")
										AND ((p.`type` = 2 AND pr.`type` = %d AND pr.`no_from` <= %d AND (pr.`no_to` >= %d || pr.`no_to` = 0)) OR 
										 		 (p.`type` = 1 AND p.`onetime_price` >= 0))
									LIMIT 1';
					$scheme = $wpdb->get_row($wpdb->prepare($sql, $id, $promocode, $data['pr_type'], $data['pr_value'], $data['pr_value']));
					
					if ($scheme && !empty($scheme)) {
					
						/* DEBUG echo 'Global: ' . $id . ' => '; var_dump($scheme); echo '<br /><br />'; /**/
						
					} else {					
						return false;
					}
			
			}
			
			
			// Get scheme
			$data['id_pricing'] = $scheme->id_pricing;
			$data['rate_id'] = $scheme->rate_id;
			$data['vat'] 				= $scheme->vat;
			$data['type'] 			= $scheme->type;
			$data['currency'] 	= $scheme->currency;
			$data['active_days'] 	= $scheme->active_days;
			
			// One-time price
			if ($data['type'] == 1) {
				$data['price'] = $scheme->onetime_price;
				$data['total_rental'] = $data['price'];
			
			// Time based
			} else {
				$data['price'] = $scheme->price; // Price per day/hour
				$data['total_rental'] = $data['price'] * $data['pr_value']; // Price * number of days/hours
			}
			
			$data['maxprice_reached'] = ((isset($scheme->maxprice) && (int) $scheme->maxprice > 0 && $data['total_rental'] > (int) $scheme->maxprice) ? true : false);
			
			if ($data['maxprice_reached'] == true) {
				$data['total_rental'] = $scheme->maxprice;
			}
			
			// Get currently set currency
			$global_currency = get_option('carrental_global_currency');
			$av_currencies = unserialize(get_option('carrental_available_currencies'));
			if (isset($_SESSION['carrental_currency']) && !empty($_SESSION['carrental_currency']) && isset($av_currencies[$_SESSION['carrental_currency']])) {
				$current_currency = $_SESSION['carrental_currency'];
			} else {
				$current_currency = $global_currency;
			}
			
			// Apply currency settings
			if ($scheme->currency != $current_currency) {
				
				if ($current_currency == $global_currency && isset($av_currencies[$scheme->currency])) {
				
					$rate = $av_currencies[$scheme->currency];
					$data['price'] *= $rate;
					$data['total_rental'] *= $rate;
					// echo 'Price:' . $scheme->price * $rate . "<br />"; // DEBUG
				
				} elseif ($scheme->currency != $global_currency) {
					
					$rate = round($av_currencies[$scheme->currency] / $av_currencies[$current_currency], 2);
					$data['price'] *= $rate;
					$data['total_rental'] *= $rate;
					//echo "Rate:" . $rate . "<br />"; // DEBUG
					//echo 'Price:' . $scheme->price * $rate . "<br />"; // DEBUG
				
				} elseif (isset($av_currencies[$current_currency])) {
				
					$rate = $av_currencies[$current_currency];
					$data['price'] /= $rate;
					$data['total_rental'] /= $rate;
					//echo "Rate:" . $rate . "<br />"; // DEBUG
					//echo 'Price:' . $scheme->price / $rate . "<br />"; // DEBUG
				
				}
				
			}
			
			$data['tax_price'] = $data['tax_total_rental'] = 0;
			if ((float) $data['vat'] > 0) {
				$data['tax_price'] = $data['price'] * ((float) $data['vat'] / 100);
				$data['tax_total_rental'] = $data['total_rental'] * ((float) $data['vat'] / 100);
			}
			
			$data['currency'] = $current_currency;
			$data['cc_before'] = self::get_currency_symbol('before', $data['currency']);
			$data['cc_after'] = self::get_currency_symbol('after', $data['currency']);
			
			return $data;
			
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	
	public function get_delivery_price() {
		global $wpdb;
		
		try {
		
			$price = get_option('carrental_delivery_price');
			
			// Get currently set currency
			$global_currency = get_option('carrental_global_currency');
			$av_currencies = unserialize(get_option('carrental_available_currencies'));
			if (isset($_SESSION['carrental_currency']) && !empty($_SESSION['carrental_currency']) && isset($av_currencies[$_SESSION['carrental_currency']])) {
				$current_currency = $_SESSION['carrental_currency'];
			} else {
				$current_currency = $global_currency;
			}
			
			// Apply currency settings
			if ($global_currency != $current_currency && isset($av_currencies[$current_currency])) {
				$rate = $av_currencies[$current_currency];
				$price /= $rate;
			}
			
			return $price;
			
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	/**
	 *	Get vehicle detail
	 */	 	
	public function get_vehicle_detail($id_fleet, $filters) {
		global $wpdb;
		
		try {
		
			// Apply filters
			
				// Date filters
				if (!isset($filters['fd']) || empty($filters['fd'])) { $filters['fd'] = Date('Y-m-d'); }
				if (!isset($filters['fh']) || empty($filters['fh'])) { $filters['fh'] = '12:00'; }
				if (!isset($filters['td']) || empty($filters['td'])) { $filters['td'] = Date('Y-m-d', strtotime("+1 day")); }
				if (!isset($filters['th']) || empty($filters['th'])) { $filters['th'] = '12:00'; }
			
				$date_from = date('Y-m-d', strtotime($filters['fd'])) . ' ' . $filters['fh'];
				$date_to = date('Y-m-d', strtotime($filters['td'])) . ' ' . $filters['th'];
				
				// Promocode
				$promocode = ((isset($filters['promo']) && !empty($filters['promo'])) ? $filters['promo'] : NULL);
				
			$sql = 'SELECT f.* FROM `' . CarRental::$db['fleet'] . '` f WHERE f.`id_fleet` = %d AND f.`deleted` IS NULL';
			$vehicle = $wpdb->get_row($wpdb->prepare($sql, $id_fleet));
								
			// Prices
			$vehicle->prices = self::get_prices('fleet', $vehicle->id_fleet, $date_from, $date_to, $promocode);
			
			return $vehicle;
			
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	
	/**
	 *	Get vehicle parameters
	 */	 	
	public function get_vehicle_parameters($id_vehicle) {
		global $wpdb;
		try {
    	
    	return $wpdb->get_row($wpdb->prepare('SELECT * FROM `' . CarRental::$db['fleet'] . '` WHERE `id_fleet` = %d', $id_vehicle));
    	
	  } catch (Exception $e) {
	  	return false;
  	}
	}
	
	/**
	 *	Get extras parameters
	 */	 	
	public function get_extras_parameters($id_extras) {
		global $wpdb;
		
		try {
			
			return $wpdb->get_row($wpdb->prepare('SELECT * FROM `' . CarRental::$db['extras'] . '` WHERE `id_extras` = %d', $id_extras));
			
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	/**
	 *	Get vehicle extras
	 */	 	
	public function get_vehicle_extras($id_fleet, $filters) {
		global $wpdb;
		
		try {
			
			// Apply filters
			
				// Date filters
				if (!isset($filters['fd']) || empty($filters['fd'])) { $filters['fd'] = Date('Y-m-d'); }
				if (!isset($filters['fh']) || empty($filters['fh'])) { $filters['fh'] = '12:00'; }
				if (!isset($filters['td']) || empty($filters['td'])) { $filters['td'] = Date('Y-m-d', strtotime("+1 day")); }
				if (!isset($filters['th']) || empty($filters['th'])) { $filters['th'] = '12:00'; }
			
				$date_from = date('Y-m-d', strtotime($filters['fd'])) . ' ' . $filters['fh'];
				$date_to = date('Y-m-d', strtotime($filters['td'])) . ' ' . $filters['th'];
				
				// Promocode
				$promocode = ((isset($filters['promo']) && !empty($filters['promo'])) ? $filters['promo'] : NULL);
				
			$sql = 'SELECT e.*
							FROM `' . CarRental::$db['fleet_extras'] . '` fe
							LEFT JOIN `' . CarRental::$db['extras'] . '` e ON e.`id_extras` = fe.`id_extras`
							WHERE fe.`id_fleet` = %d AND e.`deleted` IS NULL';
			
			$extras = $wpdb->get_results($wpdb->prepare($sql, $id_fleet));
			
			// Get prices for results
			if ($extras && !empty($extras)) {
				foreach ($extras as $key => $val) {
					$extras[$key]->prices = self::get_prices('extras', $val->id_extras, $date_from, $date_to, $promocode); 
				}
			}
			
			return $extras;
			
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	
	/**
	 *	Save booking to database
	 */		 	
	function save_booking($data) {
		global $wpdb;
		
		try {
			
			$id_order = self::generate_unique_order_id();
			
			// Get location details
			$enter_loc = $return_loc = self::get_location_name((int) $data['el']);
			if (!empty($data['rl']) && (int) $data['rl'] > 0 && $_GET['dl'] == 'on') {
				$return_loc = self::get_location_name((int) $data['rl']);
			}
			
			// Get vehicle details
			$vehicle = self::get_vehicle_parameters((int) $data['id_car']);
			$consumption_metric = get_option('carrental_consumption');
			$currency = get_option('carrental_global_currency');
			$distance_metric = get_option('carrental_distance_metric');
			
			$date_from = Date('Y-m-d H:i:s', strtotime($data['fd'] . ' ' . $data['fh']));
			$date_to = Date('Y-m-d H:i:s', strtotime($data['td'] . ' ' . $data['th']));
			
			// get vehicle price
			$main_price = self::get_prices('fleet', (int) $data['id_car'], $date_from, $date_to, $data['promo']);
			
			/*
			 * TSDweb integration (activated by another plugin)
			 */
			try {
				$tsd = unserialize(get_option('carrental_tsdweb'));
				if ($tsd && !empty($tsd) && is_array($tsd)) {
					if (defined('CARRENTAL_TSDWEB__PLUGIN_DIR') && CARRENTAL_TSDWEB__PLUGIN_DIR != '') {
						if (file_exists(CARRENTAL_TSDWEB__PLUGIN_DIR . DIRECTORY_SEPARATOR . 'class.carrental-tsdweb.php')) {
							require_once CARRENTAL_TSDWEB__PLUGIN_DIR . DIRECTORY_SEPARATOR . 'class.carrental-tsdweb.php';
							
							$data['bid_enter'] = $data['bid_return'] = self::get_location_id((int) $data['el']);
							if (!empty($data['rl']) && (int) $data['rl'] > 0 && $_GET['dl'] == 'on') {
								$data['bid_return'] = self::get_location_id((int) $data['rl']);
							}
							
							$data['class_code'] = $vehicle->class_code;
							$data['rate_id'] = $main_price['rate_id'];
							
							// Get month price
							$tf = strtotime($date_from);
							$monthly_date_to = Date('Y-m-d H:i:s', mktime(Date('H', $tf), Date('i', $tf), Date('s', $tf), Date('m', $tf) + Date('t', $tf), Date('d', $tf), Date('Y', $tf))); // + 1 month
							$monthly = self::get_prices('fleet', (int) $data['id_car'], $date_from, $monthly_date_to, $data['promo']);
							$data['monthly_rate'] = (float) $monthly['total_rental'] + (float) $monthly['tax_total_rental'];
							
							// Get week price
							$weekly_date_to = Date('Y-m-d H:i:s', mktime(Date('H', $tf), Date('i', $tf), Date('s', $tf), Date('m', $tf), Date('d', $tf) + 7, Date('Y', $tf))); // + 7 days
							$weekly = self::get_prices('fleet', (int) $data['id_car'], $date_from, $weekly_date_to, $data['promo']);
							$data['weekly_rate'] = (float) $weekly['total_rental'] + (float) $weekly['tax_total_rental'];
							
							// Get day price
							$daily_date_to = Date('Y-m-d H:i:s', mktime(Date('H', $tf), Date('i', $tf), Date('s', $tf), Date('m', $tf), Date('d', $tf) + 1, Date('Y', $tf))); // + 1 day
							$daily = self::get_prices('fleet', (int) $data['id_car'], $date_from, $daily_date_to, $data['promo']);
							$data['daily_rate'] = (float) $daily['total_rental'] + (float) $daily['tax_total_rental'];
							
							CarRental_Tsdweb::api_send_data($data);
							
						}
					}
				}
			} catch (Exception $e) {}
			/*
			 * END OF TSD Web integration
			 */
			
			$arr = array('id_order' 		=> $id_order,
									 'first_name' 	=> $data['first_name'],
									 'last_name' 		=> $data['last_name'],
									 'email' 				=> $data['email'],
									 'phone' 				=> $data['phone'],
									 'street' 			=> $data['street'],
									 'city' 				=> $data['city'],
									 'zip' 					=> $data['zip'],
									 'country' 			=> $data['country'],
									 'company' 			=> $data['company'],
									 'vat' 					=> $data['vat'],
									 'flight' 			=> $data['flight'],
									 'license' 			=> $data['license'],
									 'id_card'			=> $data['id_card'],
									 'terms'				=> $data['terms'],
									 'newsletter' 	=> $data['newsletter'],
									 'enter_loc' 		=> $enter_loc,
									 'enter_date' 	=> $date_from,
									 'return_loc' 	=> $return_loc,
									 'return_date' 	=> $date_to,
									 'vehicle' 			=> $vehicle->name,
									 'vehicle_id' 			=> $vehicle->id_fleet,
									 'vehicle_ac' 			=> $vehicle->ac,
									 'vehicle_luggage'	=> $vehicle->luggage,
									 'vehicle_seats'		=> $vehicle->seats,
									 'vehicle_fuel'			=> $vehicle->fuel,
									 'vehicle_picture'			=> $vehicle->picture,
									 'vehicle_consumption'	=> $vehicle->consumption,
									 'vehicle_consumption_metric'	=> $consumption_metric,
									 'vehicle_transmission' 	=> $vehicle->transmission,
									 'vehicle_free_distance'	=> $vehicle->free_distance . ' ' . $distance_metric,
									 'vehicle_deposit'				=> $vehicle->deposit . ' ' . $currency,
									 'payment_option' => $data['payment_selected_option'],
									 'comment' => $data['comment'],
									 );
									 
    	$wpdb->insert(CarRental::$db['booking'], $arr);
    	$id_booking = $wpdb->insert_id;
			
			// Add prices/extras
			
				// Vehicle price (+ tax)
					
					$arr = array('id_booking' => $id_booking,
											 'name' 			=> $vehicle->name . ', ' . $date_from . ' (' . $enter_loc . ') - ' . $date_to . ' (' . $return_loc . ')', 
											 'price' 			=> (float) $main_price['total_rental'],
											 'currency' 	=> $main_price['currency']);
					$wpdb->insert(CarRental::$db['booking_prices'], $arr);
					
					if ((float) $main_price['tax_total_rental'] > 0) {
						$arr = array('id_booking' => $id_booking,
												 'name' 			=> $main_price['vat'] . '% Value Added Tax',
												 'price' 			=> (float) $main_price['tax_total_rental'],
												 'currency' 	=> $main_price['currency']);
						$wpdb->insert(CarRental::$db['booking_prices'], $arr);
					}
				
				// Extras prices
					if (isset($data['extras']) && !empty($data['extras'])) {
						foreach ($data['extras'] as $key => $id_extras) {
							
							// @todo: More drivers.
							
							$extras_detail = self::get_extras_parameters((int) $id_extras);
							$extras_prices = self::get_prices('extras', (int) $id_extras, $date_from, $date_to, $data['promo']);
							$arr = array('id_booking' => $id_booking,
													 'name' 			=> $extras_detail->name,
													 'price' 			=> (float) $extras_prices['total_rental'],
													 'currency' 	=> $extras_prices['currency']);
							$wpdb->insert(CarRental::$db['booking_prices'], $arr);
							
							if ((float) $extras_prices['tax_total_rental'] > 0) {
								$arr = array('id_booking' => $id_booking,
														 'name' 			=> $extras_detail->name . ' - ' . $extras_prices['vat'] . '% Value Added Tax',
														 'price' 			=> (float) $extras_prices['tax_total_rental'],
														 'currency' 	=> $extras_prices['currency']);
								$wpdb->insert(CarRental::$db['booking_prices'], $arr);
							}
							
						}
					}
				
				// Car delivery price
					$delivery_price = self::get_delivery_price();
					if ($enter_loc != $return_loc && (float) $delivery_price > 0) {
						$arr = array('id_booking' => $id_booking,
												 'name' 			=> 'Car delivery to different location', 
												 'price' 			=> (float) $delivery_price,
												 'currency' 	=> $main_price['currency']);
						$wpdb->insert(CarRental::$db['booking_prices'], $arr);
					}
				
			// Add drivers
			if (isset($data['drv']) && !empty($data['drv'])) {
				foreach ($data['drv'] as $key => $val) {
					if (!empty($val['first_name']) && !empty($val['last_name']) && !empty($val['email']) && !empty($val['phone'])) {
						$arr = array('id_booking' 	=> $id_booking,
												 'first_name' 	=> $val['first_name'],
												 'last_name' 		=> $val['last_name'],
												 'email' 				=> $val['email'],
												 'phone' 				=> $val['phone'],
												 'street' 			=> $val['street'],
												 'city' 				=> $val['city'],
												 'zip' 					=> $val['zip'],
												 'country' 			=> $val['country'],
												 'license' 			=> $val['license'],
												 'id_card'			=> $val['id_card']
												 );
						$wpdb->insert(CarRental::$db['booking_drivers'], $arr);
					}
				}
			}
			
			$hash = self::generate_hash($id_order, $data['email']);
			
			// Send e-mail
				if (isset($_SESSION['carrental_language']) && !empty($_SESSION['carrental_language'])) {
					$emailBody = get_option('carrental_reservation_email_' . $_SESSION['carrental_language']);
					if ($emailBody == '') {
						$emailBody = get_option('carrental_reservation_email_en_GB');
					}
				} else {
					$emailBody = get_option('carrental_reservation_email_en_GB');
				}
				
				if (!empty($emailBody)) {
					
					$emailBody = str_replace('[CustomerName]', $data['first_name'] . " " . $data['last_name'], $emailBody);
					$emailBody = str_replace('[ReservationDetails]', $vehicle->name . ', ' . $date_from . ' (' . $enter_loc . ') - ' . $date_to . ' (' . $return_loc . ')', $emailBody);
					$emailBody = str_replace('[ReservationNumber]', $id_order, $emailBody);
					$emailBody = str_replace('[ReservationLink]', home_url() . '?page=carrental&summary=' . $hash, $emailBody);
					$emailBody = str_replace('[ReservationLinkStart]', '<a href="' . home_url() . '?page=carrental&summary=' . $hash . '">', $emailBody);
					$emailBody = str_replace('[ReservationLinkEnd]', '</a>', $emailBody);
					$emailBody = '<html><body>' . $emailBody . '</body></html>';
					$emailBody = nl2br($emailBody);
					
					$recipient = $data['email'];
					$subject = "Reservation confirmation #" . $id_order;
					
					$company = unserialize(get_option('carrental_company_info'));
		
					$email = ((isset($company['email']) && !empty($company['email'])) ? $company['email'] : 'admin@' . $_SERVER['SERVER_NAME']);
					$name = ((isset($company['name']) && !empty($company['name'])) ? $company['name'] : 'Car Rental WP Plugin');
					
					add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
					add_filter('wp_mail_from', create_function('', 'return "' . $email . '"; '));
					add_filter('wp_mail_from_name', create_function('', 'return "' . $name . '"; '));
					
					$book_send_email = get_option('carrental_book_send_email');
					if (empty($book_send_email)) {
						$book_send_email = array('client' => 1, 'admin' => 1);
					} else {
						$book_send_email = unserialize($book_send_email);
						if (!is_array($book_send_email)) {
							$book_send_email = array();
						}
						if (!isset($book_send_email['client'])) {
							$book_send_email['client'] = 1;
						}
						if (!isset($book_send_email['admin'])) {
							$book_send_email['admin'] = 1;
						}
					}
					
					if ($book_send_email['client'] == 1) {
						$res = wp_mail($recipient, $subject, $emailBody);						
					}					
					
					// Copy to admin
					if (isset($company['email']) && !empty($company['email']) && $book_send_email['admin'] == 1) {
						@wp_mail($company['email'], $subject, $emailBody);
					}
					// PHPmailer
					/* NOT USING 
					require 'phpmailer.class.php';
					require 'phpmailer.smtp.class.php';

					$mail = new PHPMailer;
					
					$smtp = unserialize(get_option('carrental_smtp'));
					if ($smtp && !empty($smtp) && !empty($smtp['server']) && !empty($smtp['email']) && !empty($smtp['pwd'])) {
						$mail->isSMTP();
						$mail->Host = $smtp['server'];
						$mail->SMTPAuth = true;
						$mail->Username = $smtp['email'];
						$mail->Password = $smtp['pwd'];
						$mail->SMTPSecure = (!empty($smtp['secure']) ? $smtp['secure'] : 'tls');
						$mail->Port = (!empty($smtp['port']) ? $smtp['port'] : 587);
					}
					
					$company = unserialize(get_option('carrental_company_info'));
					if (isset($company['email']) && !empty($company['email'])) {
						$mail->From = $company['email'];
					} else {
						$mail->From = 'admin@' . $_SERVER['SERVER_NAME'];
					}
					
					$mail->FromName = $_SERVER['SERVER_NAME'];
					$mail->addAddress($data['email'], $data['first_name'] . ' ' . $data['last_name']);
					$mail->isHTML(true);
					
					$mail->Subject = $subject;
					$mail->Body    = nl2br($emailBody);
					$mail->AltBody = strip_tags($emailBody);
					
					if(!$mail->send()) {
					  //echo 'Message could not be sent.';
					  //echo 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
					  //echo 'Message has been sent';
					}
					/**/
					
				}
				
			return $hash;
			
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	
	/**
	 *	Get order summary
	 */	 	
	public function get_order_summary($hash) {
		global $wpdb;
		
		try {
			
			
			$data['info'] = $wpdb->get_row($wpdb->prepare('SELECT * FROM `' . CarRental::$db['booking'] . '`
																										 WHERE MD5(CONCAT(`id_order`, %s, `email`)) = %s', self::$hash_salt, $hash));
			
			if ($data['info'] && !empty($data['info'])) {
			
				$data['prices'] = $wpdb->get_results($wpdb->prepare('SELECT * FROM `' . CarRental::$db['booking_prices'] . '`
																										 		 		 WHERE `id_booking` = %d', $data['info']->id_booking));
				
				$data['drivers'] = $wpdb->get_results($wpdb->prepare('SELECT * FROM `' . CarRental::$db['booking_drivers'] . '`
																										 		 			WHERE `id_booking` = %d', $data['info']->id_booking));
			
			}
			
			return $data;
																						
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }
	}
	
	
	/**
	 *	Generate unique random order ID string (8 characters, max. 10 loops)
	 */	 	
	function generate_unique_order_id() {
		global $wpdb;
		
		try {
			
			for ($x = 0; $x <= 10; $x++) {
				$id_order = strtoupper(substr(sha1(uniqid()), rand(0, 34), 8));
				$exists = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM `' . CarRental::$db['booking'] . '` WHERE `id_order` = %s LIMIT 1', $id_order));
				if ((int) $exists == 0) {
					return $id_order;
				}
			}
			
			return false;
			
		} catch (Exception $e) {
			return false;
	  	//return $e->getMessage();
	  }
	}
	
	function generate_hash($id_order, $email) {
		return md5($id_order . self::$hash_salt . $email);
	}
	
	function is_maxprice_reached($val) {
		$total_price = $val->price * $val->pr_value;
		return ((isset($val->maxprice) && (int) $val->maxprice > 0 && $total_price > (int) $val->maxprice) ? true : false);
	}
	
	function get_currency_symbol($place, $currency) {
		$currencies = array('USD' => array('before' => '$', 'after' => ''),
												'EUR' => array('before' => '€ ', 'after' => ''),
												'CZK' => array('before' => '', 'after' => ' Kč'),
												'GBP' => array('before' => '£', 'after' => ''));
		
		if (isset($currencies[$currency])) {
			return $currencies[$currency][$place];
		} else {
			return (($place == 'after') ? $currency : '');
		}
		
	}
  
	public static function view($name, array $args = array()) {
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}
		
		load_plugin_textdomain('carrental');
		
		$cr_title = ucfirst(end(explode('-', $name)));
		
		$file = CARRENTAL__PLUGIN_DIR . 'views/'. $name . '.php';
		include($file);
		
	}
	
	
	public static function template($name, array $args = array()) {
		//$args = apply_filters( 'akismet_view_arguments', $args, $name );
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}
		
		load_plugin_textdomain('carrental');
		
		$cr_title = ucfirst(end(explode('-', $name)));
		
		$file = TEMPLATEPATH . '/'. $name . '.php';
		include($file);
		
	}
	
	
	public static function get_country_list() {
		$arr = array ('AD' => 'Andorra',  'AE' => 'United Arab Emirates',  'AF' => 'Afghanistan',  'AG' => 'Antigua and Barbuda',  'AI' => 'Anguilla',  'AL' => 'Albania',  'AM' => 'Armenia',  'AN' => 'Netherlands Antilles',  'AO' => 'Angola',  'AQ' => 'Antarctica',  'AR' => 'Argentina',  'AS' => 'American Samoa',  'AT' => 'Austria',  'AU' => 'Australia',  'AW' => 'Aruba',  'AX' => 'Åland Islands',  'AZ' => 'Azerbaijan',  'BA' => 'Bosnia and Herzegovina',  'BB' => 'Barbados',  'BD' => 'Bangladesh',  'BE' => 'Belgium',  'BF' => 'Burkina Faso',  'BG' => 'Bulgaria',  'BH' => 'Bahrain',  'BI' => 'Burundi',  'BJ' => 'Benin',  'BL' => 'Saint Barthélemy',  'BM' => 'Bermuda',  'BN' => 'Brunei',  'BO' => 'Bolivia',  'BQ' => 'British Antarctic Territory',  'BR' => 'Brazil',  'BS' => 'Bahamas',  'BT' => 'Bhutan',  'BV' => 'Bouvet Island',  'BW' => 'Botswana',  'BY' => 'Belarus',  'BZ' => 'Belize',  'CA' => 'Canada',  'CC' => 'Cocos [Keeling] Islands',  'CD' => 'Congo - Kinshasa',  'CF' => 'Central African Republic',  'CG' => 'Congo - Brazzaville',  'CH' => 'Switzerland',  'CI' => 'Côte d’Ivoire',  'CK' => 'Cook Islands',  'CL' => 'Chile',  'CM' => 'Cameroon',  'CN' => 'China',  'CO' => 'Colombia',  'CR' => 'Costa Rica',  'CS' => 'Serbia and Montenegro',  'CT' => 'Canton and Enderbury Islands',  'CU' => 'Cuba',  'CV' => 'Cape Verde',  'CX' => 'Christmas Island',  'CY' => 'Cyprus',  'CZ' => 'Czech Republic',  'DD' => 'East Germany',  'DE' => 'Germany',  'DJ' => 'Djibouti',  'DK' => 'Denmark',  'DM' => 'Dominica',  'DO' => 'Dominican Republic',  'DZ' => 'Algeria',  'EC' => 'Ecuador',  'EE' => 'Estonia',  'EG' => 'Egypt',  'EH' => 'Western Sahara',  'ER' => 'Eritrea',  'ES' => 'Spain',  'ET' => 'Ethiopia',  'FI' => 'Finland',  'FJ' => 'Fiji',  'FK' => 'Falkland Islands',  'FM' => 'Micronesia',  'FO' => 'Faroe Islands',  'FQ' => 'French Southern and Antarctic Territories',  'FR' => 'France',  'FX' => 'Metropolitan France',  'GA' => 'Gabon',  'GB' => 'United Kingdom',  'GD' => 'Grenada',  'GE' => 'Georgia',  'GF' => 'French Guiana',  'GG' => 'Guernsey',  'GH' => 'Ghana',  'GI' => 'Gibraltar',  'GL' => 'Greenland',  'GM' => 'Gambia',  'GN' => 'Guinea',  'GP' => 'Guadeloupe',  'GQ' => 'Equatorial Guinea',  'GR' => 'Greece',  'GS' => 'South Georgia and the South Sandwich Islands',  'GT' => 'Guatemala',  'GU' => 'Guam',  'GW' => 'Guinea-Bissau',  'GY' => 'Guyana',  'HK' => 'Hong Kong SAR China',  'HM' => 'Heard Island and McDonald Islands',  'HN' => 'Honduras',  'HR' => 'Croatia',  'HT' => 'Haiti',  'HU' => 'Hungary',  'ID' => 'Indonesia',  'IE' => 'Ireland',  'IL' => 'Israel',  'IM' => 'Isle of Man',  'IN' => 'India',  'IO' => 'British Indian Ocean Territory',  'IQ' => 'Iraq',  'IR' => 'Iran',  'IS' => 'Iceland',  'IT' => 'Italy',  'JE' => 'Jersey',  'JM' => 'Jamaica',  'JO' => 'Jordan',  'JP' => 'Japan',  'JT' => 'Johnston Island',  'KE' => 'Kenya',  'KG' => 'Kyrgyzstan',  'KH' => 'Cambodia',  'KI' => 'Kiribati',  'KM' => 'Comoros',  'KN' => 'Saint Kitts and Nevis',  'KP' => 'North Korea',  'KR' => 'South Korea',  'KW' => 'Kuwait',  'KY' => 'Cayman Islands',  'KZ' => 'Kazakhstan',  'LA' => 'Laos',  'LB' => 'Lebanon',  'LC' => 'Saint Lucia',  'LI' => 'Liechtenstein',  'LK' => 'Sri Lanka',  'LR' => 'Liberia',  'LS' => 'Lesotho',  'LT' => 'Lithuania',  'LU' => 'Luxembourg',  'LV' => 'Latvia',  'LY' => 'Libya',  'MA' => 'Morocco',  'MC' => 'Monaco',  'MD' => 'Moldova',  'ME' => 'Montenegro',  'MF' => 'Saint Martin',  'MG' => 'Madagascar',  'MH' => 'Marshall Islands',  'MI' => 'Midway Islands',  'MK' => 'Macedonia',  'ML' => 'Mali',  'MM' => 'Myanmar [Burma]',  'MN' => 'Mongolia',  'MO' => 'Macau SAR China',  'MP' => 'Northern Mariana Islands',  'MQ' => 'Martinique',  'MR' => 'Mauritania',  'MS' => 'Montserrat',  'MT' => 'Malta',  'MU' => 'Mauritius',  'MV' => 'Maldives',  'MW' => 'Malawi',  'MX' => 'Mexico',  'MY' => 'Malaysia',  'MZ' => 'Mozambique',  'NA' => 'Namibia',  'NC' => 'New Caledonia',  'NE' => 'Niger',  'NF' => 'Norfolk Island',  'NG' => 'Nigeria',  'NI' => 'Nicaragua',  'NL' => 'Netherlands',  'NO' => 'Norway',  'NP' => 'Nepal',  'NQ' => 'Dronning Maud Land',  'NR' => 'Nauru',  'NT' => 'Neutral Zone',  'NU' => 'Niue',  'NZ' => 'New Zealand',  'OM' => 'Oman',  'PA' => 'Panama',  'PC' => 'Pacific Islands Trust Territory',  'PE' => 'Peru',  'PF' => 'French Polynesia',  'PG' => 'Papua New Guinea',  'PH' => 'Philippines',  'PK' => 'Pakistan',  'PL' => 'Poland',  'PM' => 'Saint Pierre and Miquelon',  'PN' => 'Pitcairn Islands',  'PR' => 'Puerto Rico',  'PS' => 'Palestinian Territories',  'PT' => 'Portugal',  'PU' => 'U.S. Miscellaneous Pacific Islands',  'PW' => 'Palau',  'PY' => 'Paraguay',  'PZ' => 'Panama Canal Zone',  'QA' => 'Qatar',  'RE' => 'Réunion',  'RO' => 'Romania',  'RS' => 'Serbia',  'RU' => 'Russia',  'RW' => 'Rwanda',  'SA' => 'Saudi Arabia',  'SB' => 'Solomon Islands',  'SC' => 'Seychelles',  'SD' => 'Sudan',  'SE' => 'Sweden',  'SG' => 'Singapore',  'SH' => 'Saint Helena',  'SI' => 'Slovenia',  'SJ' => 'Svalbard and Jan Mayen',  'SK' => 'Slovakia',  'SL' => 'Sierra Leone',  'SM' => 'San Marino',  'SN' => 'Senegal',  'SO' => 'Somalia',  'SR' => 'Suriname',  'ST' => 'São Tomé and Príncipe',  'SU' => 'Union of Soviet Socialist Republics',  'SV' => 'El Salvador',  'SY' => 'Syria',  'SZ' => 'Swaziland',  'TC' => 'Turks and Caicos Islands',  'TD' => 'Chad',  'TF' => 'French Southern Territories',  'TG' => 'Togo',  'TH' => 'Thailand',  'TJ' => 'Tajikistan',  'TK' => 'Tokelau',  'TL' => 'Timor-Leste',  'TM' => 'Turkmenistan',  'TN' => 'Tunisia',  'TO' => 'Tonga',  'TR' => 'Turkey',  'TT' => 'Trinidad and Tobago',  'TV' => 'Tuvalu',  'TW' => 'Taiwan',  'TZ' => 'Tanzania',  'UA' => 'Ukraine',  'UG' => 'Uganda',  'UM' => 'U.S. Minor Outlying Islands',  'US' => 'United States',  'UY' => 'Uruguay',  'UZ' => 'Uzbekistan',  'VA' => 'Vatican City',  'VC' => 'Saint Vincent and the Grenadines',  'VD' => 'North Vietnam',  'VE' => 'Venezuela',  'VG' => 'British Virgin Islands',  'VI' => 'U.S. Virgin Islands',  'VN' => 'Vietnam',  'VU' => 'Vanuatu',  'WF' => 'Wallis and Futuna',  'WK' => 'Wake Island',  'WS' => 'Samoa',  'YD' => "People's Democratic Republic of Yemen",  'YE' => 'Yemen',  'YT' => 'Mayotte',  'ZA' => 'South Africa',  'ZM' => 'Zambia',  'ZW' => 'Zimbabwe',  'ZZ' => 'Unknown or Invalid Region');
		asort($arr);
		reset($arr);
		return $arr;
	}
	
	public static function get_day_name($day) {
		$days = array(1 => __('Monday', 'carrental'),
									2 => __('Tuesday', 'carrental'),
									3 => __('Wednesday', 'carrental'),
									4 => __('Thursday', 'carrental'),
									5 => __('Friday', 'carrental'),
									6 => __('Saturday', 'carrental'),
									7 => __('Sunday', 'carrental'));
		return $days[$day];
	}

	/**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {
		global $wpdb, $carrental_db;
		
		try {
			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
			$charset_collate = '';
			
			if (!empty($wpdb->charset)) {
			  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
			}
			
			if (!empty($wpdb->collate)) {
			  $charset_collate .= " COLLATE {$wpdb->collate}";
			}
			
			// Branches
			$sql = "CREATE TABLE `" . $carrental_db['branch'] . "` (
							  `id_branch` int(11) NOT NULL AUTO_INCREMENT,
							  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							  `updated` datetime DEFAULT NULL,
							  `deleted` datetime DEFAULT NULL,
							  `active` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - yes, 0 - no',
							  `name` varchar(255) NOT NULL,
							  `country` char(2) NOT NULL,
							  `state` varchar(255) NOT NULL,
							  `city` varchar(255) NOT NULL,
							  `zip` varchar(30) NOT NULL,
							  `street` varchar(255) NOT NULL,
							  `email` varchar(255) NOT NULL,
							  `phone` varchar(255) NOT NULL,
							  `description` text NOT NULL,
							  `picture` varchar(255) DEFAULT NULL,
							  `bid` varchar(30) NOT NULL,
							  PRIMARY KEY (`id_branch`),
							  KEY `deleted` (`deleted`,`active`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			$sql = "CREATE TABLE `" . $carrental_db['branch_hours'] . "` (
							  `id_branch` int(11) NOT NULL,
							  `day` tinyint(4) NOT NULL,
							  `hours_from` time NOT NULL,
							  `hours_to` time NOT NULL,
							  UNIQUE KEY `id_branch` (`id_branch`,`day`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);

			// Fleet
			$sql = "CREATE TABLE `" . $carrental_db['fleet'] . "` (
							  `id_fleet` int(11) NOT NULL AUTO_INCREMENT,
							  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							  `updated` datetime DEFAULT NULL,
							  `deleted` datetime DEFAULT NULL,
							  `name` varchar(255) NOT NULL,
							  `id_category` int(11) NOT NULL DEFAULT '0',
							  `id_branch` int(11) DEFAULT NULL,
							  `global_pricing_scheme` int(11) NOT NULL,
							  `min_rental_time` int(11) NOT NULL,
							  `seats` int(11) NOT NULL,
							  `doors` int(11) NOT NULL,
							  `luggage` int(11) NOT NULL,
							  `transmission` tinyint(4) NOT NULL,
							  `free_distance` int(11) NOT NULL,
							  `ac` tinyint(4) NOT NULL,
							  `fuel` tinyint(4) NOT NULL COMMENT '1 - Petrol, 2 - Diesel',
							  `number_vehicles` int(11) NOT NULL,
							  `consumption` float NOT NULL,
							  `description` text NOT NULL,
							  `deposit` float NOT NULL,
							  `license` varchar(255) NOT NULL,
							  `vin` varchar(255) NOT NULL,
							  `internal_id` varchar(255) NOT NULL,
							  `picture` varchar(255) DEFAULT NULL,
							  `additional_pictures` text NULL DEFAULT NULL,
							  `class_code` varchar(15) NULL DEFAULT NULL,
							  PRIMARY KEY (`id_fleet`),
							  KEY `name` (`name`),
							  KEY `id_category` (`id_category`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			$sql = "CREATE TABLE `" . $carrental_db['fleet_extras'] . "` (
							  `id_fleet` int(11) NOT NULL,
							  `id_extras` int(11) NOT NULL,
							  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
							  UNIQUE KEY `id_fleet` (`id_fleet`,`id_extras`),
							  KEY `id_fleet_2` (`id_fleet`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			$sql = "CREATE TABLE `" . $carrental_db['fleet_pricing'] . "` (
							  `id_fp` int(11) NOT NULL AUTO_INCREMENT,
								`id_fleet` int(11) NOT NULL,
							  `id_pricing` int(11) NOT NULL,
							  `priority` int(11) NOT NULL,
							  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							  `valid_from` date DEFAULT NULL,
							  `valid_to` date DEFAULT NULL,
							  PRIMARY KEY (`id_fp`),
								KEY `id_fleet` (`id_fleet`),
							  KEY `id_pricing` (`id_pricing`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			// Extras
			$sql = "CREATE TABLE `" . $carrental_db['extras'] . "` (
							  `id_extras` int(11) NOT NULL AUTO_INCREMENT,
							  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							  `updated` datetime DEFAULT NULL,
							  `deleted` datetime DEFAULT NULL,
							  `name` varchar(255) NOT NULL,
							  `description` text NOT NULL,
							  `global_pricing_scheme` int(11) NOT NULL,
							  `internal_id` varchar(255) NOT NULL,
							  `max_additional_drivers` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - NO, 1 and more - YES',
							  `picture` varchar(255) DEFAULT NULL,
							  PRIMARY KEY (`id_extras`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			$sql = "CREATE TABLE `" . $carrental_db['extras_pricing'] . "` (
							  `id_ep` int(11) NOT NULL AUTO_INCREMENT,
  							`id_extras` int(11) NOT NULL,
							  `id_pricing` int(11) NOT NULL,
							  `priority` int(11) NOT NULL,
							  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							  `valid_from` date DEFAULT NULL,
							  `valid_to` date DEFAULT NULL,
							  PRIMARY KEY (`id_ep`),
								KEY `id_extras_2` (`id_extras`),
							  KEY `id_pricing` (`id_pricing`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			// Pricing
			$sql = "CREATE TABLE `" . $carrental_db['pricing'] . "` (
							  `id_pricing` int(11) NOT NULL AUTO_INCREMENT,
							  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							  `updated` datetime DEFAULT NULL,
							  `deleted` datetime DEFAULT NULL,
							  `type` tinyint(4) NOT NULL COMMENT '1 - One time, 2 - Time related',
							  `name` varchar(255) NOT NULL,
							  `currency` varchar(3) NOT NULL,
							  `onetime_price` float DEFAULT NULL,
							  `maxprice` float DEFAULT NULL,
							  `promocode` varchar(255) NOT NULL,
							  `active` tinyint(4) NOT NULL DEFAULT '1',
							  `vat` tinyint(4) NOT NULL,
							  `active_days` varchar(20) DEFAULT NULL,
							  `rate_id` varchar(20) DEFAULT NULL,
							  PRIMARY KEY (`id_pricing`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			$sql = "CREATE TABLE `" . $carrental_db['pricing_ranges'] . "` (
							  `id_pricing` int(11) NOT NULL,
							  `type` tinyint(4) NOT NULL COMMENT '1 - days, 2 - hours',
							  `no_from` int(11) NOT NULL,
							  `no_to` int(11) NOT NULL,
							  `price` float NOT NULL,
							  UNIQUE KEY `id_pricing_2` (`id_pricing`,`type`,`no_from`,`no_to`),
							  KEY `id_pricing` (`id_pricing`)
							) ENGINE=InnoDB {$charset_collate};;
							";
			dbDelta($sql);
			
			// Vehicle categories
			$sql = "CREATE TABLE `" . $carrental_db['vehicle_categories'] . "` (
							  `id_category` int(11) NOT NULL AUTO_INCREMENT,
							  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							  `updated` datetime DEFAULT NULL,
							  `deleted` datetime DEFAULT NULL,
							  `name` varchar(255) NOT NULL,
							  `picture` varchar(255) NOT NULL,
							  PRIMARY KEY (`id_category`),
							  KEY `deleted` (`deleted`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			// Translations
			$sql = "CREATE TABLE `" . $carrental_db['translations'] . "` (
							  `id_translation` int(11) NOT NULL AUTO_INCREMENT,
							  `lang` varchar(5) NOT NULL DEFAULT 'en_GB',
							  `original` varchar(255) NOT NULL,
							  `translation` varchar(255) NOT NULL,
							  PRIMARY KEY (`id_translation`),
							  UNIQUE KEY `lang_2` (`lang`,`original`),
							  KEY `lang` (`lang`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			// Booking
			$sql = "CREATE TABLE `" . $carrental_db['booking'] . "` (
							  `id_booking` int(11) NOT NULL AUTO_INCREMENT,
							  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							  `updated` datetime DEFAULT NULL,
							  `deleted` datetime DEFAULT NULL,
							  `paid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - NO, 1 - YES',
  							`id_order` varchar(10) NOT NULL,
							  `first_name` varchar(255) NOT NULL,
							  `last_name` varchar(255) NOT NULL,
							  `email` varchar(255) NOT NULL,
							  `phone` varchar(255) NOT NULL,
							  `street` varchar(255) NOT NULL,
							  `city` varchar(255) NOT NULL,
							  `zip` varchar(30) NOT NULL,
							  `country` varchar(2) NOT NULL,
							  `company` varchar(255) NOT NULL,
							  `vat` varchar(50) NOT NULL,
							  `flight` varchar(255) NOT NULL,
							  `license` varchar(255) NOT NULL,
							  `id_card` varchar(255) NOT NULL,
							  `terms` tinyint(4) NOT NULL,
							  `newsletter` tinyint(4) NOT NULL,
							  `enter_loc` varchar(255) NOT NULL,
							  `enter_date` datetime NOT NULL,
							  `return_loc` varchar(255) NOT NULL,
							  `return_date` datetime NOT NULL,
							  `vehicle` varchar(255) NOT NULL,
							  `vehicle_id` int(11) NOT NULL,
							  `vehicle_picture` varchar(255) NOT NULL,
							  `vehicle_ac` tinyint(4) NOT NULL,
							  `vehicle_luggage` tinyint(4) NOT NULL,
							  `vehicle_seats` tinyint(4) NOT NULL,
							  `vehicle_fuel` varchar(50) NOT NULL,
							  `vehicle_consumption` float NOT NULL,
							  `vehicle_consumption_metric` varchar(2) NOT NULL,
							  `vehicle_transmission` tinyint(4) NOT NULL,
							  `vehicle_free_distance` varchar(50) NOT NULL,
							  `vehicle_deposit` varchar(50) NOT NULL,
							  `payment_option` varchar(20) NOT NULL,
								`comment` text NOT NULL,
							  PRIMARY KEY (`id_booking`),
							  UNIQUE KEY `id_order` (`id_order`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			$sql = "CREATE TABLE `" . $carrental_db['booking_drivers'] . "` (
							  `id_driver` int(11) NOT NULL AUTO_INCREMENT,
							  `id_booking` int(11) NOT NULL,
							  `first_name` varchar(255) NOT NULL,
							  `last_name` varchar(255) NOT NULL,
							  `email` varchar(255) NOT NULL,
							  `phone` varchar(255) NOT NULL,
							  `street` varchar(255) NOT NULL,
							  `city` varchar(255) NOT NULL,
							  `zip` varchar(30) NOT NULL,
							  `country` varchar(2) NOT NULL,
							  `license` varchar(255) NOT NULL,
							  `id_card` varchar(255) NOT NULL,
							  PRIMARY KEY (`id_driver`),
							  KEY `id_booking` (`id_booking`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			$sql = "CREATE TABLE `" . $carrental_db['booking_prices'] . "` (
							  `id_prices` int(11) NOT NULL AUTO_INCREMENT,
							  `id_booking` int(11) NOT NULL,
							  `name` varchar(255) NOT NULL,
							  `price` float NOT NULL,
							  `currency` varchar(3) NOT NULL,
							  PRIMARY KEY (`id_prices`),
							  KEY `id_booking` (`id_booking`)
							) ENGINE=InnoDB {$charset_collate};";
			dbDelta($sql);
			
			// set email translation if not exists
			$email_body = get_option('carrental_reservation_email_en_GB');
			if (empty($email_body)) {
				$email_body = 'Dear [CustomerName],

thank you for your reservation. Here are your reservation details:
[ReservationDetails]
[ReservationNumber]

You can return to your reservation summary page anytime by going to this link:
[ReservationLink]

We are also sending this information to the email address you have provided.

If you would like to change the reservation details, you can do so by calling our office at:
+123 456 789 or by email example@example.org

[ReservationLinkStart]Click here[ReservationLinkEnd] to print your reservation - takes them to reservation summary print out.

Thank you for your business!';
				update_option('carrental_reservation_email_en_GB', $email_body);
			}
			
			return true;
			
		} catch (Exception $e) {
			return $e->getMessage();
	  }
	  
	}
	
	
	public function load_translations($lang) {
		global $wpdb;
		
		try {
			
			$sql = 'SELECT t.`original`, t.`translation`
							FROM `' . CarRental::$db['translations'] . '` t
							WHERE t.`lang` = %s
							ORDER BY t.`id_translation` ASC';
							
			$data = $wpdb->get_results($wpdb->prepare($sql, $lang));
			
			$translations = array();
			
			if ($data && !empty($data)) {
				foreach ($data as $val) {
					$translations[$val->original] = $val->translation;
				}
			}
			
			return $translations;
			
		} catch (Exception $e) {
	  	return $e->getMessage();
	  }	
	}
	
	/**
	 * Widget dropdown
	 */
	public function carrental_widget_dropdown( $widget, $form, $instance ) {
		
		$available_languages = unserialize(get_option('carrental_available_languages'));
		if (empty($available_languages)) { $available_languages = array(); }

		echo '<p><label for="carrental_language">' . __( 'Display on language:', 'carrental' ) . ' </label>';
		echo '<select id="carrental_language" name="carrental_language">';
		
			if (!empty($available_languages)) {
				foreach ($available_languages as $key => $language ) {
					$selected = ( $key == $instance['carrental_language'] ) ? 'selected' : null;
					echo '<option ' . $selected . ' value="' . $key . '">' . $language['lang'] . ' (' . strtoupper($language['country-www']) . ')</option>';
				}
			}
		
			$selected = ('en_GB' == $instance['carrental_language']) ? 'selected' : null;
			echo '<option ' . $selected . ' value="en_GB">English (GB)</option>';
			
			$selected = ('all' == $instance['carrental_language'] || !isset($instance['carrental_language'])) ? 'selected' : null;
			echo '<option ' . $selected . ' value="all">' . __( 'All Languages', 'carrental' ) . '</option>';
		
		echo '</select></p>';

	}
	
	
	/**
	 * Update widget
	 */
	public function carrental_widget_update($instance, $new_instance, $old_instance) {
		$instance["carrental_language"] = $_POST["carrental_language"];
		return $instance;
	}
	
	
	/**
	 * Display widget
	 */
	public function carrental_display_widget($instance, $widget, $args) {
		
		if ( isset($instance['carrental_language']) && $instance['carrental_language'] != $_SESSION['carrental_language'] && $instance['carrental_language'] != 'all') {
			return false;
		}
		
		return $instance;
		
	}
	
	
}


