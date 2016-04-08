<div class="carrental-wrapper">
	
	<?php include CARRENTAL__PLUGIN_DIR . 'views/header.php'; ?>
	
	<div class="row">
	
		<div class="col-md-12 carrental-main-wrapper">
			<div class="carrental-main-content">
				
				<?php include CARRENTAL__PLUGIN_DIR . 'views/flash_msg.php'; ?>
				
				<div class="row">
					<div class="col-md-12">
						
						<?php if ($edit == true) { ?>
							<h3>Edit vehicle: <?= $detail->name ?></h3>
						<?php } else { ?>
							<?php if (isset($_GET['deleted'])) { ?>
								<a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-fleet')); ?>" class="btn btn-default" style="float:right;">Show normal</a>
							<?php } else { ?>
								<a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-fleet')); ?>&amp;deleted" class="btn btn-default" style="float:right;">Show deleted</a>
							<?php } ?>
							
							<a href="javascript:void(0);" class="btn btn-success" id="carrental-fleet-add-button"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Add new vehicle</a>
						<?php } ?>
						
						<div id="<?= (($edit == true) ? 'carrental-fleet-edit-form' : 'carrental-fleet-add-form') ?>" class="carrental-add-form">
							<form action="" method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
								
								<div class="row">
									<div class="col-md-11">
										
										<div class="alert alert-info">
											<p><span class="glyphicon glyphicon-share-alt"></span>&nbsp;&nbsp;Whichever field is left blank will not be used in car description.</p>
											<p><span class="glyphicon glyphicon-share-alt"></span>&nbsp;&nbsp;Manage your <a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-settings')); ?>#vehicle-categories">Vehicle categories</a>, <a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-pricing')); ?>">Pricing schemes</a> and <a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-extras')); ?>">Extras</a> first.</p>
										</div>

										<!-- Name //-->
									  <div class="form-group">
									    <label for="carrental-type" class="col-sm-3 control-label">Name</label>
									    <div class="col-sm-9">
									    	<input type="text" name="name" class="form-control" id="carrental-type" placeholder="Ford Mondeo / SUV / Mid-size" value="<?= (($edit == true) ? $detail->name : '') ?>">
									    </div>
									  </div>
									  <?php //print_r($detail); ?>
									  									  <!-- Vehicle Avalability //-->
									  <div class="form-group">
									    <label for="carrental-availability" class="col-sm-3 control-label">Vehicle Status</label>
									    <div class="col-sm-9">
										    <select name="availability" id="carrental-availability" class="form-control">
										    	<option value="Available" <?php selected( 'Available',$detail->availability ); ?>>Available</option>
												<option value="Services" <?php selected( 'Services',$detail->availability ); ?>>Under Services</option>
												<option value="Draft" <?php selected( 'Draft',$detail->availability ); ?>>Draft</option>
												<option value="Private" <?php selected( 'Private',$detail->availability ); ?>>Private</option>
												<option value="Unavailable" <?php selected( 'Unavailable',$detail->availability ); ?>>Unavailable</option>
									    	</select>
									    </div>
									  </div>

										<!-- Force car to be available today //-->
									  <div class="form-group">
									    <label for="carrental-type" class="col-sm-3 control-label">Force Available date</label>
									    <div class="col-sm-9">
																	
																		<div class="form-group has-feedback">
																			<input type="text" name="forceAvailable" class="form-control pricing_datepicker" placeholder="Select date" value="<?= (($detail->forceAvailable != '0000-00-00') ? $detail->forceAvailable : '') ?>">
														    			<span class="glyphicon glyphicon-calendar form-control-feedback"></span>
														    	 		</div>
											<p class="help-block">This should be left blank (not today) for most of time. Only input date of today when you need to force vehicle to be available from now rather than tomorrow.</p>

									  </div>									  

									  <!-- Vehicle Category //-->
									  <div class="form-group">
									    <label for="carrental-category" class="col-sm-3 control-label">Vehicle category</label>
									    <div class="col-sm-9">
										    <select name="id_category" id="carrental-category" class="form-control">
										    	<option value="none">- none -</option>
										    	<?php if ($vehicle_categories && !empty($vehicle_categories)) { ?>
										    		<?php foreach ($vehicle_categories as $key => $val) { ?>
										    			<option value="<?= $val->id_category ?>" <?= (($edit == true && $detail->id_category == $val->id_category) ? 'selected="selected"' : '') ?>><?= $val->name ?></option>
										    		<?php } ?>
										    	<?php } ?>
									    	</select>
									    </div>
									  </div>
									  
									  <!-- Current location //-->
									  <div class="form-group">
									    <label for="carrental-location" class="col-sm-3 control-label">Current location</label>
									    <div class="col-sm-9">
										    <select name="id_branch" id="carrental-location" class="form-control">
										    	<option value="0">- none -</option>
										    	<option value="-1">Unassigned (unavailable for rent)</option>
										    	<?php if ($branches && !empty($branches)) { ?>
										    		<?php foreach ($branches as $key => $val) { ?>
										    			<option value="<?= $val->id_branch ?>" <?= (($edit == true && $detail->id_branch == $val->id_branch) ? 'selected="selected"' : '') ?>><?= $val->name ?></option>
										    		<?php } ?>
										    	<?php } ?>
									    	</select>
									    </div>
									  </div>
									  
									  <!-- Global Pricing Scheme //-->
									  <div class="form-group">
									    <label class="col-sm-3 control-label">Global Pricing scheme</label>
									    <div class="col-sm-9">
										    <select name="global_pricing_scheme" class="form-control">
										    	<option value="0">- none -</option>
										    	<?php if (isset($pricing) && !empty($pricing)) { ?>
											    	<?php foreach ($pricing as $key => $val) { ?>
											    		<option value="<?= $val->id_pricing ?>" <?= (($edit == true && $detail->global_pricing_scheme == $val->id_pricing) ? 'selected="selected"' : '') ?>><?= $val->name ?></option>
											    	<?php } ?>
											    <?php } ?>
									    	</select>
										    <p class="help-block">This pricing scheme is used when no other pricing scheme is active or usable.</p>
									    </div>
									  </div>
									  
									  <!-- Price Scheme //-->
									  <div class="form-group">
									    <label class="col-sm-3 control-label"><abbr title="Highest priority first!">Pricing scheme</abbr></label>
									    <div class="col-sm-9">
										    <div id="pricing_sort">
										    		
														<?php if ($edit == true && isset($detail->pricing) && !empty($detail->pricing)) { ?>
															<?php foreach ($detail->pricing as $key => $val) { ?>
																
																<!-- Price scheme row //-->
												    		<div class="row" style="position: relative;" class="sortable">
																  <div class="col-xs-5">
																  	<select name="pricing[]" class="form-control">
																    	<option value="0">- none -</option>
																    	<?php if (isset($pricing) && !empty($pricing)) { ?>
																	    	<?php foreach ($pricing as $kD => $vD) { ?>
																	    		<option value="<?= $vD->id_pricing ?>" <?= (($val->id_pricing == $vD->id_pricing) ? 'selected="selected"' : '') ?>><?= $vD->name ?></option>
																	    	<?php } ?>
																	    <?php } ?>
															    	</select>
															    </div>
																	<div class="col-xs-3">
																		<div class="form-group has-feedback">
																			<input type="text" name="pricing_from[]" class="form-control pricing_datepicker" placeholder="Valid from" value="<?= (($val->valid_from != '0000-00-00') ? $val->valid_from : '') ?>">
														    			<span class="glyphicon glyphicon-calendar form-control-feedback"></span>
														    		</div>
														    	</div>
																	<div class="col-xs-3">
																		<div class="form-group has-feedback">
														    			<input type="text" name="pricing_to[]" class="form-control pricing_datepicker" placeholder="Valid until" value="<?= (($val->valid_to != '0000-00-00') ? $val->valid_to : '') ?>">
														    			<span class="glyphicon glyphicon-calendar form-control-feedback"></span>
														    		</div>
														    	</div>
														    	<div class="col-xs-1">
														    		<span class="glyphicon glyphicon-sort" style="margin-top:9px;cursor:move;" title="Move up or down to sort Price scheme. Highest priority first!"></span>
																  </div>														
													    	</div><!-- .row //-->
											    	
															<?php } ?>
														<?php } ?>
										    		
										    	<div id="carrental-prices">
										    		<!-- Price scheme row //-->
										    		<div class="row" style="position: relative;" class="sortable">
														  <div class="col-xs-5">
														  	<select name="pricing[]" class="form-control">
														    	<option value="0">- none -</option>
														    	<?php if (isset($pricing) && !empty($pricing)) { ?>
															    	<?php foreach ($pricing as $key => $val) { ?>
															    		<option value="<?= $val->id_pricing ?>"><?= $val->name ?></option>
															    	<?php } ?>
															    <?php } ?>
													    	</select>
													    </div>
															<div class="col-xs-3">
																<div class="form-group has-feedback">
																	<input type="text" name="pricing_from[]" class="form-control pricing_datepicker" placeholder="Valid from">
												    			<span class="glyphicon glyphicon-calendar form-control-feedback"></span>
												    		</div>
												    	</div>
															<div class="col-xs-3">
																<div class="form-group has-feedback">
												    			<input type="text" name="pricing_to[]" class="form-control pricing_datepicker" placeholder="Valid until">
												    			<span class="glyphicon glyphicon-calendar form-control-feedback"></span>
												    		</div>
												    	</div>
												    	<div class="col-xs-1">
												    		<span class="glyphicon glyphicon-sort" style="margin-top:9px;cursor:move;" title="Move up or down to sort Price scheme. Highest priority first!"></span>
														  </div>														
											    	</div><!-- .row //-->
												  </div>
											    
													<div id="carrental-prices-insert"></div>
												</div>
									    	<a href="javascript:void(0);" id="carrental-add-pricing-scheme" class="btn btn-info btn-xs">Add Pricing Scheme</a>
									    </div>
									  </div>
									  
									  <!-- Extras //-->
									  <div class="form-group">
									    <label for="carrental-extras" class="col-sm-3 control-label">Extras</label>
									    <div class="col-sm-9">
									    	<?php if ($extras && !empty($extras)) { ?>
									    		<?php foreach ($extras as $key => $val) { ?>
									    			<div class="checkbox">
												    	<label>
															  <input type="checkbox" name="extras[]" value="<?= $val->id_extras ?>" <?= (($edit == true && !empty($detail->extras) && in_array($val->id_extras, explode(',', $detail->extras))) ? 'checked="checked"' : '') ?>>&nbsp; <?= $val->name ?>
															</label>
														</div>
									    		<?php } ?>
									    	<?php } ?>
									    </div>
									  </div>
									  
									  <!-- Minimum rental time //-->
									  <div class="form-group">
									    <label for="carrental-min-time" class="col-sm-3 control-label">Minimum rental time</label>
									    <div class="col-sm-9">
									    	<input type="text" name="min_rental_time" class="form-control" id="carrental-min-time" placeholder="In hours: 1, 2, 4, 8, 12, 24, ..." value="<?= (($edit == true) ? $detail->min_rental_time : '') ?>">
									    	<p class="help-block">In whole hours, minimum value = 1</p>
									    </div>
									  </div>
									  
									  <!-- Number of Seats //-->
									  <div class="form-group">
									    <label for="carrental-seats" class="col-sm-3 control-label">Seats</label>
									    <div class="col-sm-9">
									    	<input type="text" name="seats" class="form-control" id="carrental-seats" placeholder="Number of seats: 2, 4, 5, 6, 7, ..." value="<?= (($edit == true) ? $detail->seats : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Number of Doors //-->
									  <div class="form-group">
									    <label for="carrental-doors" class="col-sm-3 control-label">Doors</label>
									    <div class="col-sm-9">
									    	<input type="text" name="doors" class="form-control" id="carrental-doors" placeholder="Number of doors: 2, 4, 5" value="<?= (($edit == true) ? $detail->doors : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Number of Luggage //-->
									  <div class="form-group">
									    <label for="carrental-luggage" class="col-sm-3 control-label">Luggage</label>
									    <div class="col-sm-9">
									    	<input type="text" name="luggage" class="form-control" id="carrental-luggage" placeholder="Number of luggage: 2, 3, 4, 5, ..." value="<?= (($edit == true) ? $detail->luggage : '') ?>">
									    </div>
									  </div>
									  
									  <!-- drive type //-->
									  <div class="form-group">
									    <label for="carrental-luggage" class="col-sm-3 control-label">Drive</label>
									    <div class="col-sm-9">
									    	<input type="text" name="drive" class="form-control" id="carrental-luggage" placeholder="4WD,2WD" value="<?= (($edit == true) ? $detail->drive : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Transmission //-->
									  <div class="form-group">
									    <label for="carrental-transmission" class="col-sm-3 control-label">Transmission</label>
									    <div class="col-sm-9">
									    	<label class="radio-inline">
												  <input type="radio" name="transmission" id="carrental-transmission-automatic" value="0" <?= (($detail->transmission == 0) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;Not use
												</label>
												<label class="radio-inline">
												  <input type="radio" name="transmission" id="carrental-transmission-automatic" value="1" <?= (($detail->transmission == 1) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;Automatic
												</label>
												<label class="radio-inline">
												  <input type="radio" name="transmission" id="carrental-transmission-manual" value="2" <?= (($detail->transmission == 2) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;Manual
												</label>
									    </div>
									  </div>
									  
									  <!-- Free km / miles //-->
									  <div class="form-group">
									    <label for="carrental-free-dist" class="col-sm-3 control-label">Free distance (km/mi)</label>
									    <div class="col-sm-9">
									    	<input type="text" name="free_distance" class="form-control" id="carrental-free-dist" placeholder="Free distance in kilometers or miles." value="<?= (($edit == true) ? $detail->free_distance : '') ?>">
									    	<p class="help-block">0 = unlimited</p>
											</div>
									  </div>
									  
									  <!-- A/C //-->
									  <div class="form-group">
									    <label for="carrental-ac" class="col-sm-3 control-label">A/C</label>
									    <div class="col-sm-9">
									    	<label class="radio-inline">
												  <input type="radio" name="ac" id="carrental-ac-not" value="0" <?= (($detail->ac == 0) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;Not use
												</label>
												<label class="radio-inline">
												  <input type="radio" name="ac" id="carrental-ac-yes" value="1" <?= (($detail->ac == 1) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;Yes
												</label>
												<label class="radio-inline">
												  <input type="radio" name="ac" id="carrental-ac-no" value="2" <?= (($detail->ac == 2) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;No
												</label>
									    </div>
									  </div>
									  
									  <!-- Fuel //-->
									  <div class="form-group">
									    <label for="carrental-ac" class="col-sm-3 control-label">Fuel</label>
									    <div class="col-sm-9">
									    	<label class="radio-inline">
												  <input type="radio" name="fuel" id="carrental-fuel-not" value="0" <?= (($detail->fuel == 0) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;Not use
												</label>
												<label class="radio-inline">
												  <input type="radio" name="fuel" id="carrental-fuel-yes" value="1" <?= (($detail->fuel == 1) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;Petrol
												</label>
												<label class="radio-inline">
												  <input type="radio" name="fuel" id="carrental-fuel-no" value="2" <?= (($detail->fuel == 2) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;Diesel
												</label>
									    </div>
									  </div>
									  
									  <!-- Number of vehicles //-->
									  <div class="form-group">
									    <label for="carrental-number-vehicles" class="col-sm-3 control-label">Available vehicles</label>
									    <div class="col-sm-9">
									    	<input type="text" name="number_vehicles" class="form-control" id="carrental-number-vehicles" placeholder="Number of available vehicles." value="<?= (($edit == true) ? $detail->number_vehicles : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Consumption //-->
									  <div class="form-group">
									    <label for="carrental-consumption" class="col-sm-3 control-label">Consumption</label>
									    <div class="col-sm-9">
									    	<input type="text" name="consumption" class="form-control" id="carrental-consumption" placeholder="Vehicle consumption (in l/100 km or MPG)" value="<?= (($edit == true) ? $detail->consumption : '') ?>">
									    </div>
									  </div>
									  
									  <!--excess mileage price //-->
									  <div class="form-group">
									    <label for="carrental-excess-mileage" class="col-sm-3 control-label">Excess mileage price</label>
									    <div class="col-sm-9">
									    	<input type="text" name="excess_mileage" class="form-control" id="carrental-excess-mileage" placeholder="$10/km" value="<?= (($edit == true) ? $detail->excess_mileage : '') ?>">
									    </div>
									  </div>
									  
									  <!--fuel shortage price //-->
									  <div class="form-group">
									    <label for="carrental-fuel-shortage" class="col-sm-3 control-label">Fuel shortage price</label>
									    <div class="col-sm-9">
									    	<input type="text" name="fuel_shortage" class="form-control" id="carrental-fuel-shortage" placeholder="$10/litre" value="<?= (($edit == true) ? $detail->fuel_shortage : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Description //-->
									  <div class="form-group">
									    <label for="carrental-description" class="col-sm-3 control-label">Description</label>
									    <div class="col-sm-9">
									    	
									    	<ul class="nav nav-tabs" role="tablist">
												  <li role="presentation" class="active"><a href="javascript:void(0);" class="edit_fleet_description" data-value="gb">English (GB)</a></li>
												  <?php $available_languages = unserialize(get_option('carrental_available_languages')); ?>
													<?php if ($available_languages && !empty($available_languages)) { ?>
														<?php foreach ($available_languages as $key => $val) { ?>
												  		<li role="presentation"><a href="javascript:void(0);" class="edit_fleet_description" data-value="<?= strtolower($val['country-www']) ?>"><?= $val['lang'] ?> (<?= strtoupper($val['country-www']) ?>)</a></li>
														<?php } ?>
												  <?php } ?>
												</ul>
												
												<?php if ($edit == true) { ?>
													<?php $fleet_description = unserialize($detail->description); ?>
													<?php if ($fleet_description == false) { $fleet_description['gb'] = $detail->description; } ?>
												<?php } ?>
												
												<textarea class="form-control fleet_description fleet_description_gb" name="description[gb]" id="carrental-description" rows="3" placeholder="Brief description of cars in English (GB)."><?= ((isset($fleet_description['gb']) && !empty($fleet_description['gb'])) ? $fleet_description['gb'] : '') ?></textarea>
												<?php if ($available_languages && !empty($available_languages)) { ?>
													<?php foreach ($available_languages as $key => $val) { ?>
									    			<textarea class="form-control fleet_description fleet_description_<?= strtolower($val['country-www']) ?>" name="description[<?= strtolower($val['country-www']) ?>]" rows="3" placeholder="Brief description of cars in <?= $val['lang'] ?> (<?= strtoupper($val['country-www']) ?>)."><?= ((isset($fleet_description[strtolower($val['country-www'])]) && !empty($fleet_description[strtolower($val['country-www'])])) ? $fleet_description[strtolower($val['country-www'])] : '') ?></textarea>
									    		<?php } ?>
												<?php } ?>
									    	
									    </div>
									  </div>
									  
									  <!-- Deposit //-->
									  <div class="form-group">
									    <label for="carrental-deposit" class="col-sm-3 control-label">Deposit</label>
									    <div class="col-sm-9">
									    	<input type="text" name="deposit" class="form-control" id="carrental-deposit" placeholder="How much the deposit on the car will be." value="<?= (($edit == true) ? $detail->deposit : '') ?>">
									    </div>
									  </div>
									  
									  <!-- License registration number //-->
									  <div class="form-group">
									    <label for="carrental-license" class="col-sm-3 control-label">Plate No</label>
									    <div class="col-sm-9">
									    	<input type="text" name="license" class="form-control" id="carrental-license" value="<?= (($edit == true) ? $detail->license : '') ?>">
									    </div>
									  </div>
									  
									  <!-- VIN code //-->
									  <div class="form-group">
									    <label for="carrental-vin" class="col-sm-3 control-label">VIN code</label>
									    <div class="col-sm-9">
									    	<input type="text" name="vin" class="form-control" id="carrental-vin" value="<?= (($edit == true) ? $detail->vin : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Internal Car ID //-->
									  <div class="form-group">
									    <label for="carrental-internal-id" class="col-sm-3 control-label">Vehicle ID</label>
									    <div class="col-sm-9">
									    	<input type="text" name="internal_id" class="form-control" id="carrental-internal-id" value="<?= (($edit == true) ? $detail->internal_id : '') ?>">
									    </div>
									  </div>
									  
									   <!-- Class Code //-->
									  <div class="form-group">
									    <label for="carrental-class-code" class="col-sm-3 control-label">Class code</label>
									    <div class="col-sm-9">
									    	<input type="text" name="class_code" class="form-control" id="carrental-class-code" value="<?= (($edit == true) ? $detail->class_code : '') ?>">
									    </div>
									  </div>
									  									   <!-- Car Buying year //-->
									  <div class="form-group">
									    <label for="carrental-class-code" class="col-sm-3 control-label">Year</label>
									    <div class="col-sm-9">
									    	<input type="text" name="carYear" class="form-control" id="carrental-year" value="<?= (($edit == true) ? $detail->year : '') ?>">
									    </div>
									  </div>
									  <!-- Picture of vehicle //-->
									  <div class="form-group">
									    <label for="carrental-picture" class="col-sm-3 control-label">Main picture of vehicle</label>
									    <div class="col-sm-9">
									    	<?php if ($edit == true) { ?>
									    		<div class="panel panel-info">
													  <div class="panel-heading">Current picture</div>
													  <div class="panel-body">
													    <p><img src="<?= $detail->picture ?>" height="80"></p>
													  </div>
													</div>
													<p><strong>Or you can upload new picture for Vehicle:</strong></p>
									  		<?php } ?>
									    	<input type="file" name="picture" id="carrental-picture">
									    	<p class="help-block">Insert picture of the item or service, 400x400px.</p>
									    </div>
									  </div>
									  
									  <!-- Additional pictures of vehicle //-->
									  <div class="form-group">
									    <label for="carrental-picture" class="col-sm-3 control-label">Additional pictures</label>
									    <div class="col-sm-9">
									    		<div class="panel panel-info">
													  <div class="panel-heading">Additional pictures</div>
													  <div class="panel-body">
														  <ul class="additional-pictures" id="additional-pictures-ul">
															  
															  <?php if (isset($detail->additional_pictures) && !empty($detail->additional_pictures)) { ?>
																<?php $detail->additional_pictures = unserialize($detail->additional_pictures); ?>
																<?php if (is_array($detail->additional_pictures)) { ?>
																	<?php foreach ($detail->additional_pictures as $picture) { ?>
															  <li><input type="hidden" name="additional-pictures[]" value="<?php echo $picture;?>" class="media-input" /><img src="<?php echo $picture;?>" /><div class="buttons"><a href="#" class="btn btn-danger btn-block delete-button">X</a></div></li>
																	<?php } ?>
																<?php } ?>
															  <?php } ?>
														  </ul>
													  </div>
													</div>
									    	<button class="media-button">Add new picture</button>
									    </div>
									  </div>
									  
									  <!-- Submit //-->
									  <div class="form-group">
									  	<div class="col-sm-offset-3 col-sm-9">
									  		<?php if ($edit == true) { ?>
									  			<input type="hidden" name="id_fleet" value="<?= $detail->id_fleet ?>">
									  			<input type="hidden" name="current_picture" value="<?= $detail->picture ?>">
									  			<button type="submit" class="btn btn-warning" name="add_fleet"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Confirm &amp; Save</button>
									  		<?php } else { ?>
									  			<button type="submit" class="btn btn-warning" name="add_fleet"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Confirm &amp; Add</button>
									  		<?php } ?>
									  	</div>
										</div>
										
									</div>
								</div>
								
							</form>
						</div>
						
						<hr>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						
						<?php if (isset($fleet) && !empty($fleet)) { ?>
							
							<?php $distance_metric = get_option('carrental_distance_metric'); ?>
							<?php $consumption = get_option('carrental_consumption'); ?>
							<?php $currency = get_option('carrental_global_currency'); ?>
							
							<table class="table table-striped" id="carrental-fleet">
					      <thead>
					        <tr>
					          <th>#</th>
					          <th>Image</th>
					          <th>Name</th>
					          <th>Pricing schemes</th>
					          <th>Parameters</th>
					          <th>Parameters</th>
					          <th>Extras</th>
					          <th>Action</th>
					        </tr>
					      </thead>
					      <tbody>
					      	<?php foreach ($fleet as $key => $val) { ?>
					      		<tr>
						          <td>
						          	<?php //print_r($val); ?>
												<input type="checkbox" class="input-control batch_processing" name="batch[]" value="<?= $val->id_fleet ?>">&nbsp;
												<abbr title="Created: <?= $val->created ?>

<?= (!empty($val->updated) ? 'Updated: ' . $val->updated : '') ?>"><?= $val->id_fleet ?></abbr>
											</td>
						          <td><img src="<?= $val->picture ?>" height="120"></td>
						          <td>
												<strong><?= (!empty($val->name) ? $val->name : '- Unknown -') ?></strong>
												<?php if ($val->id_branch == -1) { ?>
													<br><small>Unassigned (unavailable for rent)</small>
												<?php } elseif (!empty($val->branch_name)) { ?>
													<br><small>(Loc : <?= $val->branch_name ?>)</small>
												<?php } ?>
											</td>
											<td>
												<?php if (!empty($val->pricing_name)) { ?>
													<p><a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-pricing')); ?>&amp;<?= (($val->pricing_type == 1) ? 'get_onetime_price' : 'get_day_ranges') ?>=<?= $val->global_pricing_scheme ?>" class="btn <?= (($val->pricing_type == 1) ? 'btn-info' : 'btn-success') ?> carrental_show_ranges"><?= $val->pricing_name ?></a></p>
													<?php if ($val->pricing_count > 0) { ?>
														<p><a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-pricing')); ?>&amp;get_fleet_price_schemes=<?= $val->id_fleet ?>" class="btn <?= (($val->pricing_type == 1) ? 'btn-info' : 'btn-success') ?> carrental_show_ranges">+ <?= $val->pricing_count ?> schemes</a></p>
													<?php } ?>
												<?php } else { ?>
													<p><em>- none -</em></p>
												<?php } ?>
											</td>
											<td>
												<table class="table carrental-fleet-parameters">
													<tr>
														<td>Status</td>
														<td><?= $val->availability ?> </td>
													</tr>
													<tr>
														<td>Seats/Doors/Luggage</td>
														<td><?= $val->seats ?>/<?= $val->doors ?>/<?= $val->luggage ?></td>
													</tr>
													<tr>
														<td>Transmission</td>
														<td>
															<?php if ($val->transmission == 1) { ?>
																Automatic
															<?php } elseif ($val->transmission == 2) { ?>
																Manual
															<?php } else { ?>
																Not use
															<?php } ?>
														</td>
													</tr>
													<tr>
														<td>Class</td>
														<td>
															<?php if ($val->class_code != null && $val->class_code != "")  ?>
																
															<?= $val->class_code  ?>		
															
														</td>
													</tr>
													<tr>
														<td>Fuel</td>
														<td>
															<?php if ($val->fuel == 1) { ?>
																Petrol
															<?php } elseif ($val->fuel == 2) { ?>
																Diesel
															<?php } else { ?>
																Not use
															<?php } ?>
														</td>
													</tr>
												</table>
											</td>
											<td>
												<table class="table table-condensed carrental-fleet-parameters">
													<tr>
														<td>Free distance</td>
														<td><?= $val->free_distance ?>&nbsp;<?= (!empty($distance_metric) ? ' ' . $distance_metric : '')?></td>
													</tr>
													<tr>
														<td>Consumption</td>
														<td><?= $val->consumption ?>&nbsp;<?php if (!empty($consumption)) { echo ($consumption == 'us' ? ' MPG' : ' l/100km'); } ?></td>
													</tr>
													<tr>
														<td>Available vehicles</td>
														<td><?= $val->number_vehicles ?></td>
													</tr>
													<tr>
														<td>Deposit</td>
														<td><?= $val->deposit ?>&nbsp;<?= (!empty($currency) ? ' ' . $currency : '')?></td>
													</tr>
												</table>
											</td>
											<td>
												<?php if ($extras && !empty($extras) && !empty($val->extras)) { ?>
												<ul>
									    		<?php foreach ($extras as $kD => $vD) { ?>
									    			<?php if (in_array($vD->id_extras, explode(',', $val->extras))) { ?>
									    				<li><?= $vD->name ?></li>
									    			<?php } ?>
									    		<?php } ?>
									    	</ul>
									    	<?php } ?>
											</td>
						          <td>
												<form action="" method="post" class="form" role="form">
													<div class="form-group">
														<a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-fleet')); ?>&amp;edit=<?= $val->id_fleet ?>" class="btn btn-primary btn-block">Modify</a>
													</div>
												</form>
												<form action="" method="post" class="form" role="form">
													<div class="form-group">
														<input type="hidden" name="id_fleet" value="<?= $val->id_fleet ?>">
														<button name="copy_fleet" class="btn btn-warning btn-block">Copy</button>
													</div>
												</form>
												<?php if (isset($_GET['deleted'])) { ?>
													<form action="" method="post" class="form" role="form" onsubmit="return confirm('<?= __('Do you really want to restore this Vehicle?', 'carrental') ?>');">
														<div class="form-group">
															<input type="hidden" name="id_fleet" value="<?= $val->id_fleet ?>">
															<button name="restore_fleet" class="btn btn-success btn-block">Restore</button>
														</div>
													</form>
												<?php } else { ?>
													<form action="" method="post" class="form" role="form" onsubmit="return confirm('<?= __('Do you really want to delete this Vehicle?', 'carrental') ?>');">
														<div class="form-group">
															<input type="hidden" name="id_fleet" value="<?= $val->id_fleet ?>">
															<button name="delete_fleet" class="btn btn-danger btn-block">Delete</button>
														</div>
													</form>
												<?php } ?>
											</td>
						        </tr>
						        
					      	<?php } ?>
					      </tbody>
					    </table>
					    
					    
					    <h4>Batch action on selected items</h4>
					    
					    <form action="" method="post" class="form" role="form" onsubmit="if (jQuery('[name=batch_processing_values]').val() == '') { alert('No Vehicle is selected to copy.'); return false }; return confirm('<?= __('Do you really want to copy selected Vehicles?', 'carrental') ?>');">
								<div class="form-group">
									<input type="hidden" name="batch_processing_values" value="">
									<button name="batch_copy_fleet" class="btn btn-warning">Copy <span class="batch_processing_count"></span>selected Vehicles</button>
								</div>
							</form>
							
					    <form action="" method="post" class="form" role="form" onsubmit="if (jQuery('[name=batch_processing_values]').val() == '') { alert('No Vehicle is selected to delete.'); return false }; return confirm('<?= __('Do you really want to delete selected Vehicles?', 'carrental') ?>');">
								<div class="form-group">
									<input type="hidden" name="batch_processing_values" value="">
									<button name="batch_delete_fleet" class="btn btn-danger">Delete <span class="batch_processing_count"></span>selected Vehicles</button>
								</div>
							</form>
					    
					    
						<?php } else { ?>
							<div class="alert alert-info">
								<span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;
								<?= esc_html__( 'You do not have any Vehicles created yet, please create one clicking on "Add New Vehicle".', 'carrental' ); ?>
							</div>
						<?php } ?>
							
					</div>
				</div>
				
				
				
			</div>
		</div>
	</div>
	
</div>
<script language="JavaScript">
var gk_media_init = function(button_selector)  {		
        jQuery(button_selector).click(function (event) {
            event.preventDefault();
            
            // check for media manager instance
            if(wp.media.frames.gk_frame) {
                wp.media.frames.gk_frame.open();
                return;
            }
            // configuration of the media manager new instance
            wp.media.frames.gk_frame = wp.media({
                title: 'Select image',
                multiple: true,
                library: {
                    type: 'image'
                },
                button: {
                    text: 'Use selected image'
                }
            });
 
            // Function used for the image selection and media manager closing
            var gk_media_set_image = function() {
                var selection = wp.media.frames.gk_frame.state().get('selection');
 
                // no selection
                if (!selection) {
                    return;
                }
				console.log(selection);
                // iterate through selected elements
                selection.each(function(attachment) {
                    var url = attachment.attributes.url;
					// add to additional images
					jQuery('#additional-pictures-ul').append('<li><input type="hidden" name="additional-pictures[]" value="'+url+'" class="media-input" /><img src="'+url+'" /><div class="buttons"><a href="#" class="btn btn-danger btn-block delete-button">X</a></div></li>');
                });				
            };
 
            // closing event for media manger
            //wp.media.frames.gk_frame.on('close', gk_media_set_image);
            // image selection event
            wp.media.frames.gk_frame.on('select', gk_media_set_image);
            // showing media manager
            wp.media.frames.gk_frame.open();
        });
   
};

gk_media_init('.media-button');

jQuery(document).ready(function () {
	jQuery( "#additional-pictures-ul" ).sortable({
		handle: 'img',
		cursor: 'move'
	});
	jQuery( "#additional-pictures-ul" ).disableSelection();
	
	jQuery( document ).on( 'mouseover', "#additional-pictures-ul li", function() {
		jQuery(this).children('.buttons').show();
	});
	
	jQuery( document ).on( 'mouseout', "#additional-pictures-ul li", function() {
		jQuery(this).children('.buttons').hide();
	});
	
	jQuery( document ).on( 'click', "#additional-pictures-ul li .delete-button", function(event) {
		event.preventDefault();		
		jQuery(this).parent().parent().remove();
	});
});
</script>