<?php $currency = get_option('carrental_global_currency'); ?>
<div class="columns-2 break-md aside-on-left">
	<div class="column column-fixed">
		<div class="box box-clean">
			
			<div class="box-title mobile-toggle mobile-toggle-md" data-target="modify-search">
				<?= CarRental::t('Modify search') ?>
			</div>
			<!-- .box-title -->

			<div data-id="modify-search" class="box-inner-small box-border-bottom md-hidden">			
				
				<form action="" method="get" class="form form-vertical form-size-100" id="carrental_booking_form">
		
					<fieldset>

						<div class="control-group">
							<div class="control-field">
								<select name="el" id="carrental_enter_location" class="size-90">
									<option value=""><?= CarRental::t('Enter Location') ?></option>
									<?php if (isset($locations) && !empty($locations)) { ?>
										<?php $locations_no = count($locations); ?>
										<?php foreach ($locations as $key => $val) { ?>
											<option value="<?= $val->id_branch ?>" <?php if ((isset($_GET['el']) && (int) $_GET['el'] == $val->id_branch) || $locations_no == 1) { ?>selected<?php } ?>><?= $val->name ?></option>
										<?php } ?>	
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="control-group">
							<div class="control-field">
								<label><input name="dl" id="carrental_different_loc" type="checkbox" <?php if (isset($_GET['dl']) && $_GET['dl'] == 'on') { ?>checked<?php } ?>>&nbsp;&nbsp;<?= CarRental::t('Returning to Different location') ?></label>
							</div>
						</div>
						
						<div class="control-group">
							<div class="control-field">
								<select name="rl" id="carrental_return_location" class="size-90">
									<option value=""><?= CarRental::t('Return Location') ?></option>
									<?php if (isset($locations) && !empty($locations)) { ?>
										<?php $locations_no = count($locations); ?>
										<?php foreach ($locations as $key => $val) { ?>
											<option value="<?= $val->id_branch ?>" <?php if ((isset($_GET['rl']) && (int) $_GET['rl'] == $val->id_branch) || $locations_no == 1) { ?>selected<?php } ?>><?= $val->name ?></option>
										<?php } ?>	
									<?php } ?>
								</select>
							</div>
						</div>
	
						<div class="columns-2 control-group">
							<div class="column column-wide" style="width:60.5%">
								<div class="control-group">
									<div class="control-field">
										<span class="control-addon">
											<input type="text" class="control-input" name="fd" id="carrental_from_date" placeholder="<?= CarRental::t('Pick-up date') ?>" <?php if (isset($_GET['fd'])) { ?>value="<?= htmlspecialchars($_GET['fd']) ?>"<?php } ?>>
											<span class="control-addon-item">
												<span class="sprite-calendar"></span>
											</span>
										</span>
									</div>
								</div>
							</div>
	
							<div class="column column-thin" style="width:39.5%">
								<div class="control-group">
									<div class="control-field">
										<span class="control-addon">
											<select name="fh" id="carrental_from_hour" style="width: 85%; padding:2px 9px; -webkit-border-radius: 4px; border-radius: 4px; font-size: 12px; ">
												<option value=""><?= CarRental::t('Time') ?></option>
												<?php for ($x = 0; $x <= 23; $x++) { ?>
													<option value="<?= str_pad($x, 2, '0', STR_PAD_LEFT) ?>:00" <?php if (isset($_GET['fh']) && $_GET['fh'] == str_pad($x, 2, '0', STR_PAD_LEFT) . ':00') { ?>selected<?php } ?>><?= str_pad($x, 2, '0', STR_PAD_LEFT) ?>:00</option>
													<option value="<?= str_pad($x, 2, '0', STR_PAD_LEFT) ?>:30" <?php if (isset($_GET['fh']) && $_GET['fh'] == str_pad($x, 2, '0', STR_PAD_LEFT) . ':30') { ?>selected<?php } ?>><?= str_pad($x, 2, '0', STR_PAD_LEFT) ?>:30</option>
												<?php } ?>
											</select>
											
											<span class="control-addon-item display-none" style="right:-8px;">
												<span class="sprite-time"></span>
											</span>
										</span>	
										
									</div>
								</div>
							</div>
						</div>
						<!-- .columns-2 -->
	
						<div class="columns-2 control-group">
							<div class="column column-wide" style="width:60.5%">
								<div class="control-group">
									<div class="control-field">
										<span class="control-addon">
											<input type="text" class="control-input" name="td" id="carrental_to_date" placeholder="<?= CarRental::t('Return date') ?>" <?php if (isset($_GET['td'])) { ?>value="<?= htmlspecialchars($_GET['td']) ?>"<?php } ?>>
											<span class="control-addon-item">
												<span class="sprite-calendar"></span>
											</span>
										</span>
									</div>
								</div>
							</div>
	
							<div class="column column-thin" style="width:39.5%">
								<div class="control-group">
									<div class="control-field">
										<span class="control-addon">
											<select name="th" id="carrental_to_hour" style="width: 85%; padding:2px 9px; -webkit-border-radius: 4px; border-radius: 4px; font-size: 12px; ">
												<option value=""><?= CarRental::t('Time') ?></option>
												<?php for ($x = 0; $x <= 23; $x++) { ?>
													<option value="<?= str_pad($x, 2, '0', STR_PAD_LEFT) ?>:00" <?php if (isset($_GET['th']) && $_GET['th'] == str_pad($x, 2, '0', STR_PAD_LEFT) . ':00') { ?>selected<?php } ?>><?= str_pad($x, 2, '0', STR_PAD_LEFT) ?>:00</option>
													<option value="<?= str_pad($x, 2, '0', STR_PAD_LEFT) ?>:30" <?php if (isset($_GET['th']) && $_GET['th'] == str_pad($x, 2, '0', STR_PAD_LEFT) . ':30') { ?>selected<?php } ?>><?= str_pad($x, 2, '0', STR_PAD_LEFT) ?>:30</option>
												<?php } ?>
											</select>
											<span class="control-addon-item display-none" style="right:-8px;">
												<span class="sprite-time"></span>
											</span>
										</span>	
										
									</div>
								</div>
							</div>
						</div>
	
						<div class="control-group">
							<div class="control-field">
								<input type="hidden" name="page" value="carrental">
								<input type="hidden" name="order" value="name" id="carrental_order_input">
								<input type="hidden" name="book_now" value="ok">
								<input type="hidden" name="flt" value="">
								<input type="submit" name="search" value="<?= CarRental::t('SEARCH') ?>" id="carrental_book_now" class="btn btn-primary btn-block">
							</div>
							<!-- .control-field -->
						</div>
						<!-- .control-group -->

					</fieldset>
					
					<ul id="carrental_book_errors" style="margin:1em 2em;list-style-type:circle;color:tomato;"></ul>
				</form>
			</div>
			
			<?php include(TEMPLATEPATH . '/booking-javascript.php'); ?>
			
			<?php $theme_options = unserialize(get_option('carrental_theme_options')); ?>
			
			<?php if ((isset($theme_options['filter_price_range']) && $theme_options['filter_price_range'] == 1) ||
								(isset($theme_options['filter_extras']) && $theme_options['filter_extras'] == 1) ||
								(isset($theme_options['filter_fuel']) && $theme_options['filter_fuel'] == 1) ||
								(isset($theme_options['filter_passangers']) && $theme_options['filter_passangers'] == 1) ||
								(isset($theme_options['filter_vehicle_categories']) && $theme_options['filter_vehicle_categories'] == 1) ||
								(isset($theme_options['filter_vehicle_names']) && $theme_options['filter_vehicle_names'] == 1)) { ?>
			
			<div class="box-title mobile-toggle mobile-toggle-md" data-target="filter-results">
				<?= CarRental::t('Filter Results') ?>
			</div>
			<!-- .box-title -->

			<div data-id="filter-results" class="box-inner-small box-border-bottom md-hidden">
				
				<?php
					$flt = array();
					if (isset($_GET['flt']) && !empty($_GET['flt'])) {
						foreach (explode('|', $_GET['flt']) as $kD => $vD) {
							list($key, $val) = explode(':', $vD);
							$flt[$key] = $val;
						}
					}
				?>
				
				<form action="" method="post" class="form form-vertical">
					<fieldset>
						
						<?php if (isset($theme_options['filter_price_range']) && $theme_options['filter_price_range'] == 1) { ?>
							<div class="control-group">
								<div class="control-label">
									<label id="carrental_filter_price_range_label"><?= CarRental::t('Price range') ?></label>
								</div>
								<!-- .control-label -->
								<?php $showvat = get_option('carrental_show_vat'); ?>
								<?php 
									if ($showvat && $showvat == 'yes') {
										
									}
								?>
								<div class="control-field inputSliderWrapper" id="carrental_filter_price_range">
									<div class="full-width-oddo">
										<div class="half-oddo">
											<label for="from"><?= CarRental::t('From') ?>: (<?= (isset($_SESSION['carrental_currency']) ? $_SESSION['carrental_currency'] : $currency) ?>)</label>
											<input type="number" class="oddo inputSliderMin" min="0">	
										</div>

										<div class="half-oddo float-right-oddo">
											<label for="to"><?= CarRental::t('To') ?>: (<?= (isset($_SESSION['carrental_currency']) ? $_SESSION['carrental_currency'] : $currency) ?>)</label>
											<input type="number" class="oddo inputSliderMax" min="0">	
										</div>
									</div>
									<?php $priceSliderMin = isset($theme_options['filter_price_range_min']) && (int)$theme_options['filter_price_range_min'] >= 0 ? (int)$theme_options['filter_price_range_min'] : 0;?>
									<?php $priceSliderMax = isset($theme_options['filter_price_range_max']) && (int)$theme_options['filter_price_range_max'] >= 0 ? (int)$theme_options['filter_price_range_max'] : 500;?>
									<?php if (isset($_SESSION['carrental_currency']) && $_SESSION['carrental_currency'] != $currency) {
											$av_currencies = unserialize(get_option('carrental_available_currencies'));
											if (isset($av_currencies[$_SESSION['carrental_currency']])) {
												$rate = $av_currencies[$_SESSION['carrental_currency']];
												$priceSliderMin = floor($priceSliderMin/$rate);
												$priceSliderMax = ceil($priceSliderMax/$rate);
											}
									} ?>
									<div class="inputSlider slider" data-inputmin=".inputSliderMin" data-inputmax=".inputSliderMax" data-value="[<?= (isset($flt['spr']) ? (int) $flt['spr'] : $priceSliderMin) ?>,<?= (isset($flt['epr']) ? (int) $flt['epr'] : $priceSliderMax) ?>]" data-unit="<?= (isset($_SESSION['carrental_currency']) ? $_SESSION['carrental_currency'] : 'USD') ?>" data-min="<?php echo $priceSliderMin;?>" data-max="<?php echo $priceSliderMax;?>" data-step="10">
										<div class="slider-init"></div>
									</div>
									<!-- .slider -->
		
								</div>
								<!-- .control-field -->
							</div>
						<?php } ?>
						
						<?php if (isset($theme_options['filter_extras']) && $theme_options['filter_extras'] == 1) { ?>
							<div class="control-group">
								<div class="control-label">
									<label id="carrental_filter_extras_label"><?= CarRental::t('Extras') ?></label>
								</div>
								<!-- .control-label -->
								<div class="control-field" id="carrental_filter_extras">
									<label class="custom-inline"><input type="checkbox" name="nonac" value="1" <?php if (isset($flt['nac']) && $flt['nac'] == 1) { ?>checked<?php } ?>> <?= CarRental::t('Non AC') ?></label>
									<label class="custom-inline"><input type="checkbox" name="ac" value="1" <?php if (isset($flt['ac']) && $flt['ac'] == 1) { ?>checked<?php } ?>> <?= CarRental::t('AC') ?></label>
								</div>
								<!-- .control-field -->
							</div>
						<?php } ?>
						
						<?php if (isset($theme_options['filter_fuel']) && $theme_options['filter_fuel'] == 1) { ?>
							<div class="control-group">
								<div class="control-label">
									<label id="carrental_filter_fuel_label"><?= CarRental::t('Fuel') ?></label>
								</div>
								<!-- .control-label -->
								<div class="control-field" id="carrental_filter_fuel">
									<label class="custom-inline"><input type="checkbox" name="petrol" value="1" <?php if (isset($flt['pl']) && $flt['pl'] == 1) { ?>checked<?php } ?>> <?= CarRental::t('Petrol') ?></label>
									<label class="custom-inline"><input type="checkbox" name="diesel" value="1" <?php if (isset($flt['dl']) && $flt['dl'] == 1) { ?>checked<?php } ?>> <?= CarRental::t('Diesel') ?></label>
								</div>
								<!-- .control-field -->
							</div>
						<?php } ?>
						
						<?php if (isset($theme_options['filter_passengers']) && $theme_options['filter_passengers'] == 1) { ?>
							<div class="control-group">
								<div class="control-label">
									<label id="carrental_filter_passangers_label"><?= CarRental::t('Number of passangers') ?></label>
								</div>

								<!-- .control-label -->
								<div class="control-field" id="carrental_filter_passangers">								
									<div class="slider" data-value="[<?= (isset($flt['sp']) ? (int) $flt['sp'] : 1) ?>,<?= (isset($flt['ep']) ? (int) $flt['ep'] : 8) ?>]" data-min="1" data-max="8" data-step="1">
										<div class="slider-init"></div>
										<!-- .slider-init -->
										<input type="hidden" class="slider-input-start">
										<input type="hidden" class="slider-input-end">
									</div>
									<!-- .slider -->
		
								</div>
								<!-- .control-field -->
							</div>
						<?php } ?>
						
						<?php if (isset($theme_options['filter_vehicle_categories']) && $theme_options['filter_vehicle_categories'] == 1) { ?>
							<div class="control-group">
								<div class="control-label">
									<label id="carrental_filter_categories_label"><?= CarRental::t('Categories') ?></label>
								</div>
								
								<div class="control-field" id="carrental_filter_categories">
									<label class="custom-block"><input type="checkbox" id="categories_select_all"> <?= CarRental::t('Select All') ?></label>
									<?php $cats = (isset($flt['cats']) ? explode(',', $flt['cats']) : array()); ?>
									<?php if (isset($vehicle_cats) && !empty($vehicle_cats)) { ?>
										<?php foreach ($vehicle_cats as $key => $val) { ?>
											<label class="custom-block"><input type="checkbox" class="categories_checkall" name="categories[]" value="<?= $val->id_category ?>" <?php if (in_array($val->id_category, $cats)) { ?>checked<?php } ?>> <?= $val->name ?></label>		
										<?php } ?>
									<?php } ?>
								</div>
								
							</div>
						<?php } ?>
						
						<?php if (isset($theme_options['filter_vehicle_names']) && $theme_options['filter_vehicle_names'] == 1) { ?>
							<div class="control-group">
								<div class="control-label">
									<label id="carrental_filter_vehicles_label"><?= CarRental::t('Vehicles') ?></label>
								</div>
								<div class="control-field" id="carrental_filter_vehicles">
									<label class="custom-block"><input type="checkbox" id="vehicles_select_all"> <?= CarRental::t('Select All') ?></label>
									<?php $vh = (isset($flt['vh']) ? explode(',', $flt['vh']) : array()); ?>
									<?php if (isset($vehicle_names) && !empty($vehicle_names)) { ?>
										<?php foreach ($vehicle_names as $key => $val) { ?>
											<label class="custom-block"><input type="checkbox" class="vehicles_checkall" name="names[]" value="<?= $val->name ?>" <?php if (in_array($val->name, $vh)) { ?>checked<?php } ?>> <?= $val->name ?></label>		
										<?php } ?>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
						
						<br>
						<input type="button" value="<?= CarRental::t('Modify search') ?>" class="btn btn-primary btn-block modify_search">
						
					</fieldset>
				</form>
				
				<script type="text/javascript">
					
					jQuery(document).ready(function() {
						
						jQuery('.modify_search').on('click', function() {
							jQuery('#carrental_book_now').click();
						});
						
						jQuery('#categories_select_all').click(function() {
							var checked = !jQuery(this).data('checked');
							jQuery('.categories_checkall').prop('checked', checked);
					    jQuery(this).data('checked', checked);
						});
						
						jQuery('#vehicles_select_all').click(function() {
							var checked = !jQuery(this).data('checked');
							jQuery('.vehicles_checkall').prop('checked', checked);
					    jQuery(this).data('checked', checked);
						});
						
						jQuery('#carrental_filter_price_range_label').click(function() {
							jQuery('#carrental_filter_price_range').toggle('fast');
						});
						
						jQuery('#carrental_filter_extras_label').click(function() {
							jQuery('#carrental_filter_extras').toggle('fast');
						});
						
						jQuery('#carrental_filter_fuel_label').click(function() {
							jQuery('#carrental_filter_fuel').toggle('fast');
						});
						
						jQuery('#carrental_filter_passangers_label').click(function() {
							jQuery('#carrental_filter_passangers').toggle('fast');
						});
						
						jQuery('#carrental_filter_categories_label').click(function() {
							jQuery('#carrental_filter_categories').toggle('fast');
						});
						
						jQuery('#carrental_filter_vehicles_label').click(function() {
							jQuery('#carrental_filter_vehicles').toggle('fast');
						});
						
						jQuery('#carrental_set_order').on('click', function() {
							var value = jQuery(this).attr('rel').toLowerCase(); 
							jQuery('#carrental_order_input').val(value);
							jQuery('#carrental_booking_form').submit();
						});
						
						
						
					});
					
				
				</script>
				
			</div>
			<!-- .box-inner-small -->
			<?php } ?>
			
			<?php if (isset($theme_options['phone_number']) && !empty($theme_options['phone_number'])) { ?>
				<div class="box-inner-small">
					<div class="invert-columns-2 init-md">
						<div class="column">
							<div class="box box-inner-small box-contact box-contact-small">
								<div class="h2" style="text-align:center;margin-bottom:15px;">
									<?= CarRental::t('Make a reservation by phone') ?><br>
								</div>
								<div class="h2" style="font-size: 1.75em;margin:0;">
									<strong><?= $theme_options['phone_number'] ?></strong>
								</div>
								<span class="sprite-call-us-small"></span>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			
		</div>
		<!-- .box -->
	
	</div>
	<!-- .column -->
	
	<div class="column column-fluid">
				
		<div class="bordered-content">

			<div class="bordered-content-title">
				<div class="results">
					<?= $vehicles['count'] ?> <?php if ($vehicles['count'] == 1) { ?><?= CarRental::t('result') ?><?php } else { ?><?= CarRental::t('results') ?><?php } ?>
				</div>
				<!-- .results -->
				
				<?php if (isset($_GET['page'])) { ?>
					<div class="sort">
						<span class="label"><?= CarRental::t('Sort by') ?>:</span>
						<?php if (isset($_GET['order']) && $_GET['order'] == 'price') { ?>
							<?= CarRental::t('Price') ?> <span>|</span> <a href="javascript:void(0);" id="carrental_set_order" rel="name"><?= CarRental::t('Name') ?></a>
						<?php } else { ?>
							<a href="javascript:void(0);" id="carrental_set_order" rel="price"><?= CarRental::t('Price') ?></a> <span>|</span> <?= CarRental::t('Name') ?>
						<?php } ?>
					</div>
					<!-- .sort -->
				<?php } ?>
			</div>
			<!-- .bordered-content-title -->
			
			<?php $consumption = get_option('carrental_consumption'); ?>
			<?php if (!$consumption || empty($consumption)) { $consumption = 'eu'; } ?>
			<?php $distance_metric = get_option('carrental_distance_metric'); ?>
			<?php $currency = get_option('carrental_global_currency'); ?>
												
			<?php if (isset($vehicles['results']) && !empty($vehicles['results'])) { ?>
				<?php foreach ($vehicles['results'] as $key => $val) { ?>
				
					<div class="list-item list-item-car box box-white box-inner">
		
						<div class="list-item-media">
							<div class="pic-area">
								<?php $additional_pictures_count = 0; ?>
								<?php if (isset($val->additional_pictures) && !empty($val->additional_pictures)) { ?>
									<?php $val->additional_pictures = unserialize($val->additional_pictures); ?>
									<?php if (is_array($val->additional_pictures) && count($val->additional_pictures) > 0) { ?>
										<?php $additional_pictures_count = count($val->additional_pictures); ?>
									<?php } ?>
								<?php } ?>
								<p>
									<a href="<?= $val->picture ?>" data-lightbox="fleet-<?= $val->id_fleet ?>">
										<img src="<?= $val->picture ?>" alt="<?= $val->name ?>">
										<?php if ($additional_pictures_count > 0) { ?>
											<span class="btn btn-small btn-primary btn-book btn-absolute">Show more pictures <strong>(<?php echo $additional_pictures_count;?>)</strong></span>
										<?php } ?>
									</a>
								</p>
								<div class="hid-imgs">
									<?php if ($additional_pictures_count > 0) { ?>
										<?php foreach ($val->additional_pictures as $adPicture) { ?>
											<a href="<?= $adPicture ?>" data-lightbox="fleet-<?= $val->id_fleet ?>"></a>
										<?php } ?>
									<?php } ?>
							</div>
							</div>
							<p class="car-name"><?= $val->name ?></p>
						</div>
		
						<div class="list-item-content">
							<div class="columns-2 break-lg columns-equal-height">
								<div class="column">
									
									<?php if (isset($val->ac) && (int) $val->ac > 0) { ?>
										<div class="icon-text"><span class="sprite-snowflake"></span><?php if ($val->ac == 2) { ?>No <?php } ?><?= CarRental::t('A/C') ?></div>
									<?php } ?>
									<?php if (isset($val->luggage) && !empty($val->luggage)) { ?>
										<div class="icon-text"><span class="sprite-briefcase"></span><?= $val->luggage ?>&times; <?= CarRental::t('Luggage Quantity') ?></div>
									<?php } ?>
									<?php if (isset($val->seats) && !empty($val->seats)) { ?>
										<div class="icon-text"><span class="sprite-person"></span><?= $val->seats ?>&times; <?= CarRental::t('Persons') ?></div>
									<?php } ?>
									<?php if (isset($val->fuel) && !empty($val->fuel)) { ?>
										<div class="icon-text"><span class="sprite-fuel"></span><?= (($val->fuel == 1) ? CarRental::t('Petrol') : CarRental::t('Diesel')) ?></div>
									<?php } ?>
									<?php if (isset($val->consumption) && !empty($val->consumption)) { ?>
										<div class="icon-text"><span class="sprite-timeout"></span><abbr title="<?= CarRental::t('Average Consumption') ?>"><?= $val->consumption ?> <?= (($consumption == 'eu') ? 'l/100km' : 'MPG') ?></abbr></div>
									<?php } ?>
									<?php if (isset($val->description) && !empty($val->description)) { ?>
										<p class="carrental_car_details">
											
											<?php if (isset($val->transmission) && !empty($val->transmission)) { ?>
												<?= (($val->transmission == 1) ? CarRental::t('Transmission: Automatic') : CarRental::t('Transmission: Manual')) ?><br />
											<?php } ?>
											<?php if (isset($val->free_distance)) { ?>
												<?= CarRental::t('Free distance') ?>: <?php if ($val->free_distance > 0) { ?><?= $val->free_distance ?>&nbsp;<?= $distance_metric ?><?php } else { ?><?= CarRental::t('Unlimited') ?><?php } ?><br />
											<?php } ?>
											<?php if (isset($val->deposit)) { ?>
												<?= CarRental::t('Deposit') ?>: <?php if ($val->deposit > 0) { ?><?= $val->deposit ?>&nbsp;<?= $currency ?><?php } else { ?><?= CarRental::t('None') ?><?php } ?><br />
											<?php } ?>
											<br />
											<?php $fleet_description = unserialize($val->description); ?>
											<?php if ($fleet_description == false) { $fleet_description['gb'] = $val->description; } ?>
											<?php $lang = ((isset($_SESSION['carrental_language']) && !empty($_SESSION['carrental_language'])) ? $_SESSION['carrental_language'] : 'en_GB'); ?>
											<?php $lang = end(explode('_', $lang)); ?>
											<?= (isset($fleet_description[strtolower($lang)]) ? $fleet_description[strtolower($lang)] : $fleet_description['gb']) ?>
										</p>
									<?php } ?>
								</div>
		
								<div class="column align-right">
									<p>
										<?php if (isset($val->prices) && !empty($val->prices)) { ?>
											<?php $showvat = get_option('carrental_show_vat'); ?>
											<?php 
												if ((float) $val->prices['vat'] > 0 && $showvat && $showvat == 'yes') {
													$val->prices['price'] = $val->prices['price'] * (1 + ((float) $val->prices['vat'] / 100));
													$val->prices['total_rental'] = $val->prices['total_rental'] * (1 + ((float) $val->prices['vat'] / 100));
												}
											?>
											<span class="price"><?= $val->prices['cc_before'] ?><?= number_format($val->prices['price'], 2, '.', ',') ?><?= $val->prices['cc_after'] ?> <?php if ($val->prices['pr_type'] == 2) { ?><?= CarRental::t('per hour') ?><?php } else { ?><?= CarRental::t('per day') ?><?php } ?></span><br>
											<span class="additional" <?php if ($val->prices['maxprice_reached'] == true) { ?>style="color:tomato;" title="<?= CarRental::t('Maximum price for this vehicle was reached.') ?>"<?php } ?>><?= CarRental::t('Total Rental') ?> <?= $val->prices['cc_before'] ?><?= number_format($val->prices['total_rental'], 2, '.', ',') ?><?= $val->prices['cc_after'] ?></span>
										<?php } else { ?>
											<span class="additional"><?= CarRental::t('Not available') ?></span>
										<?php } ?>
									</p>
									<?php if (isset($_GET['page'])) { ?>
										<a href="javascript:void(0);" class="btn btn-small btn-book carrental_car_details_link" style="background-color:silver;"><?= CarRental::t('Show details') ?></a>
										<br><br>
										<a href="<?= $_SERVER['REQUEST_URI'] ?>&amp;id_car=<?= $val->id_fleet ?>" class="btn btn-small btn-primary btn-book"><?= CarRental::t('Book This Car') ?></a>
									<?php } else { ?>
										<a href="javascript:void(0);" class="btn btn-small btn-book carrental_car_details_link" style="background-color:silver;"><?= CarRental::t('Show details') ?></a>
										<br><br>
										<a href="javascript:void(0);" onclick="alert('<?= CarRental::t('Add booking details please (location, pick-up and return date.') ?>');" class="btn btn-small btn-primary btn-book"><?= CarRental::t('Book This Car') ?></a>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				
				<?php } ?>
			<?php } ?>
		</div>
		<!-- .bordered-content -->

	</div>
	<!-- .column -->

</div>
<!-- .columns-2 -->