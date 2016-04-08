<div class="carrental-wrapper">
	
	<?php include CARRENTAL__PLUGIN_DIR . 'views/header.php'; ?>
	
	<div class="row">
		<div class="col-md-12 carrental-main-wrapper">
			<div class="carrental-main-content">
				
				<?php include CARRENTAL__PLUGIN_DIR . 'views/flash_msg.php'; ?>
				
				<div class="row">
					<div class="col-md-12">
					
						<?php if ($edit == true) { ?>
							<h3>Edit booking: #<?= $detail['info']->id_order ?></h3>
						<?php } else { ?>
							<?php if (isset($_GET['deleted'])) { ?>
								<a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-booking')); ?>" class="btn btn-default" style="float:right;">Show normal</a>
							<?php } else { ?>
								<a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-booking')); ?>&amp;deleted" class="btn btn-default" style="float:right;">Show deleted</a>
							<?php } ?>
							
							<a href="javascript:void(0);" class="btn btn-success" id="carrental-booking-add-button"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Add new booking</a>
							
						<?php } ?>
						
						<div id="<?= (($edit == true) ? 'carrental-booking-edit-form' : 'carrental-booking-add-form') ?>" class="carrental-add-form">
							<form action="" method="post" role="form" class="form-horizontal">
								
								<div class="row">
									<div class="col-md-11">
										
										<!-- Enter date //-->
									  <div class="form-group">
									    <label for="carrental-enter-date" class="col-sm-3 control-label">Enter date and time</label>
									    <div class="col-sm-6">
									    	<input type="text" name="enter_date" class="form-control pricing_datepicker" id="carrental-enter-date" value="<?= (($edit == true) ? Date('Y-m-d', strtotime($detail['info']->enter_date)) : '') ?>">
									    </div>
									    <div class="col-sm-3">
									    	<input type="text" name="enter_date_hour" class="form-control" placeholder="12:00" value="<?= (($edit == true) ? Date('H:i', strtotime($detail['info']->enter_date)) : '') ?>">
									    </div>
									  </div>
									  
										<!-- Enter location //-->
									  <div class="form-group">
									    <label for="carrental-enter-location" class="col-sm-3 control-label">Enter location</label>
									    <div class="col-sm-9">
									    	<select name="enter_loc" class="form-control">
										    	<option value="- none -">- none -</option>
										    	<?php if ($branches && !empty($branches)) { ?>
										    		<?php foreach ($branches as $key => $val) { ?>
										    			<option value="<?= $val->name ?>" <?= (($edit == true && $detail['info']->enter_loc == $val->name) ? 'selected="selected"' : '') ?>><?= $val->name ?></option>
										    		<?php } ?>
										    	<?php } ?>
									    	</select>
									    </div>
									  </div>
									  
										<!-- Return date //-->
									  <div class="form-group">
									    <label for="carrental-return-date" class="col-sm-3 control-label">Return date and time</label>
									    <div class="col-sm-6">
									    	<input type="text" name="return_date" class="form-control pricing_datepicker" id="carrental-return-date" value="<?= (($edit == true) ? Date('Y-m-d', strtotime($detail['info']->return_date)) : '') ?>">
									    </div>
									    <div class="col-sm-3">
									    	<input type="text" name="return_date_hour" class="form-control" placeholder="12:00" value="<?= (($edit == true) ? Date('H:i', strtotime($detail['info']->return_date)) : '') ?>">
									    </div>
									  </div>
									  
										<!-- Return location //-->
									  <div class="form-group">
									    <label for="carrental-enter-location" class="col-sm-3 control-label">Return location</label>
									    <div class="col-sm-9">
									    	<select name="return_loc" class="form-control">
										    	<option value="- none -">- none -</option>
										    	<?php if ($branches && !empty($branches)) { ?>
										    		<?php foreach ($branches as $key => $val) { ?>
										    			<option value="<?= $val->name ?>" <?= (($edit == true && $detail['info']->return_loc == $val->name) ? 'selected="selected"' : '') ?>><?= $val->name ?></option>
										    		<?php } ?>
										    	<?php } ?>
									    	</select>
									    </div>
									  </div>
										
										<!-- Return location //-->
									  <div class="form-group">
									    <label for="carrental-enter-location" class="col-sm-3 control-label">Vehicle</label>
									    <div class="col-sm-4">
									    	<?php if (!empty($detail['info']->vehicle_picture)) { ?>
													<img src="<?= $detail['info']->vehicle_picture ?>" height="60">
													&nbsp;
												<?php } ?>
												<h4><?= $detail['info']->vehicle ?></h4>
												
											</div>
																    <div class="col-sm-2">
																    	
									    	<select name="status" class="form-control status-control">
									   
										    	
										    	<?php if ($fleet && !empty($fleet)) { 

										    		?>

										    			<option value="Pending Approval" <?php selected( 'Pending Approval',$detail['info']->status );?>>Pending Approval</option>
										    			<option value="Approved" <?php selected( 'Approved',$detail['info']->status );?>>Approved</option>
										    			<option value="Rejected" <?php selected( 'Rejected',$detail['info']->status );?>>Rejected</option>
										    			<option value="Cancelled" <?php selected( 'Cancelled',$detail['info']->status );?>>Cancelled</option>
										    		
										    	<?php } ?>
									    	</select>
									    </div>
									    <div class="col-sm-3">
									    	<select name="change_vehicle" class="form-control">
									    		<?php if ($edit == true) { ?>
										    		<option value="0">Do not change vehicle</option>
										    	<?php } else { ?>
										    		<option value="0">- Select vehicle -</option>
										    	<?php } ?>
										    	
										    	<?php if ($fleet && !empty($fleet)) { ?>
										    		<?php foreach ($fleet as $key => $val) { ?>
										    			<option value="<?= $val->id_fleet ?>"><?= $val->name ?></option>
										    		<?php } ?>
										    	<?php } ?>
									    	</select>
									    </div>
									  </div>
									  
									  										<!-- Reject reasons -->
										<div class="form-group rejectInfo">
									    <label for="carrental-comment" class="col-sm-3 control-label ">Reject Info</label>
									    <div class="col-sm-9">
									    	<textarea name="rejectInfo" class="form-control" id="carrental-rejectInfo"><?= (($edit == true) ? $detail['info']->rejectInfo : '') ?></textarea>
									    </div>
									  </div>

									  <div class="form-group">
									  	<div class="col-sm-3"></div>
									    <div class="col-sm-9">
									    	<h3>Driver details</h3>
									    </div>
									  </div>
									  
									  <!-- First name //-->
									  <div class="form-group">
									    <label for="carrental-first-name" class="col-sm-3 control-label">First name</label>
									    <div class="col-sm-9">
									    	<input type="text" name="first_name" class="form-control" id="carrental-first-name" value="<?= (($edit == true) ? $detail['info']->first_name : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Last name //-->
									  <div class="form-group">
									    <label for="carrental-last-name" class="col-sm-3 control-label">Last name</label>
									    <div class="col-sm-9">
									    	<input type="text" name="last_name" class="form-control" id="carrental-last-name" value="<?= (($edit == true) ? $detail['info']->last_name : '') ?>">
									    </div>
									  </div>
									  
									  <!-- date of birth //-->
									  <div class="form-group">
									    <label for="carrental-date-of-birth" class="col-sm-3 control-label">date of birth</label>
									    <div class="col-sm-9">
									    	<input type="text" name="date_of_birth" class="form-control" id="carrental-date-of-birth" value="<?= (($edit == true) ? $detail['info']->date_of_birth : '') ?>">
									    </div>
									  </div>
									  
									  <!-- landline number //-->
									  <div class="form-group">
									    <label for="carrental-landline-number" class="col-sm-3 control-label">landline number</label>
									    <div class="col-sm-9">
									    	<input type="text" name="landline_number" class="form-control" id="carrental-landline-number" value="<?= (($edit == true) ? $detail['info']->landline_number : '') ?>">
									    </div>
									  </div>
									 
									  
									  <!-- Contact e-mail //-->
									  <div class="form-group">
									    <label for="carrental-email" class="col-sm-3 control-label">Contact e-mail</label>
									    <div class="col-sm-9">
									    	<input type="text" name="email" class="form-control" id="carrental-email" value="<?= (($edit == true) ? $detail['info']->email : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Contact phone //-->
									  <div class="form-group">
									    <label for="carrental-phone" class="col-sm-3 control-label">Contact phone</label>
									    <div class="col-sm-9">
									    	<input type="text" name="phone" class="form-control" id="carrental-phone" value="<?= (($edit == true) ? $detail['info']->phone : '') ?>">
									    </div>
									  </div>
									  
									  
									  <!-- Street //-->
									  <div class="form-group">
									    <label for="carrental-street" class="col-sm-3 control-label">Street</label>
									    <div class="col-sm-9">
									    	<input type="text" name="street" class="form-control" id="carrental-street" value="<?= (($edit == true) ? $detail['info']->street : '') ?>">
									    </div>
									  </div>
									 	
									 	<!-- City //-->
									  <div class="form-group">
									    <label for="carrental-city" class="col-sm-3 control-label">City</label>
									    <div class="col-sm-9">
									    	<input type="text" name="city" class="form-control" id="carrental-city" placeholder="Prague, London, Los Angeles, ..." value="<?= (($edit == true) ? $detail['info']->city : '') ?>">
									    </div>
									  </div>
									  
									  <!-- ZIP //-->
									  <div class="form-group">
									    <label for="carrental-zip" class="col-sm-3 control-label">ZIP Code</label>
									    <div class="col-sm-9">
									    	<input type="text" name="zip" class="form-control" id="carrental-zip" value="<?= (($edit == true) ? $detail['info']->zip : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Country //-->
									  <div class="form-group">
									    <label for="carrental-country" class="col-sm-3 control-label">Country</label>
									    <div class="col-sm-9">
									    	<select name="country" class="form-control" id="carrental-country">
										    	<option value="none">- select -</option>
										    	<?php $countries = CarRental_Admin::get_country_list(); ?>
										    	<?php foreach ($countries as $key => $val) { ?>
										    		<option value="<?= $key ?>" <?= (($edit == true && $key == $detail['info']->country) ? 'selected="selected"' : '') ?>><?= $val ?></option>
										    	<?php } ?>
									    	</select>
									    </div>
									  </div>
									  
									  <!-- Company name //-->
									  <div class="form-group">
									    <label for="carrental-company" class="col-sm-3 control-label">Company name</label>
									    <div class="col-sm-9">
									    	<input type="text" name="company" class="form-control" id="carrental-company" value="<?= (($edit == true) ? $detail['info']->company : '') ?>">
									    </div>
									  </div>
									  
									  <!-- VAT //-->
									  <div class="form-group">
									    <label for="carrental-vat" class="col-sm-3 control-label">VAT no.</label>
									    <div class="col-sm-9">
									    	<input type="text" name="vat" class="form-control" id="carrental-vat" value="<?= (($edit == true) ? $detail['info']->vat : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Flight no. //-->
									  <div class="form-group">
									    <label for="carrental-flight" class="col-sm-3 control-label">Flight no.</label>
									    <div class="col-sm-9">
									    	<input type="text" name="flight" class="form-control" id="carrental-flight" value="<?= (($edit == true) ? $detail['info']->flight : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Driver's license no //-->
									  <div class="form-group">
									    <label for="carrental-license-no" class="col-sm-3 control-label">Driver's license no.</label>
									    <div class="col-sm-9">
									    	<input type="text" name="license" class="form-control" id="carrental-license-no" value="<?= (($edit == true) ? $detail['info']->license : '') ?>">
									    </div>
									  </div>
									  
									  <!--  license expiry date //-->
									  <div class="form-group">
									    <label for="carrental-landline-number" class="col-sm-3 control-label"> License Expiry Date</label>
									    <div class="col-sm-9">
									    	<input type="text" name="license_expiry_date" class="form-control" id="carrental-license-expiry-date" value="<?= (($edit == true) ? $detail['info']->license_expiry_date : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Drive licnese issue Country //-->
									  <div class="form-group">
									    <label for="carrental-country" class="col-sm-3 control-label">Country of issue</label>
									    <div class="col-sm-9">
									    	<select name="issue_country" class="form-control" id="carrental-country">
										    	<option value="none">- select -</option>
										    	<?php $countries = CarRental_Admin::get_country_list(); ?>
										    	<?php foreach ($countries as $key => $val) { ?>
										    		<option value="<?= $key ?>" <?= (($edit == true && $key == $detail['info']->country) ? 'selected="selected"' : '') ?>><?= $val ?></option>
										    	<?php } ?>
									    	</select>
									    </div>
									  </div>
									  
									  <!-- Passport / ID number //-->
									  <div class="form-group">
									    <label for="carrental-id-no" class="col-sm-3 control-label">Passport or ID no.</label>
									    <div class="col-sm-9">
									    	<input type="text" name="id_card" class="form-control" id="carrental-id-no" value="<?= (($edit == true) ? $detail['info']->id_card : '') ?>">
									    </div>
									  </div>
									  
										<!-- Payment option //-->
									  <div class="form-group">
									    <label for="carrental-payment" class="col-sm-3 control-label">Payment option</label>
									    <div class="col-sm-9">
									    	<select name="payment_option" class="form-control" id="carrental-payment" >
									    		<option value="">Undefined</option>
									    		<option <?= (($edit == true && $detail['info']->payment_option == 'cash') ? 'selected="selected"' : '') ?> value="cash">Cash</option>
													<option <?= (($edit == true && $detail['info']->payment_option == 'cc') ? 'selected="selected"' : '') ?> value="cc">Credit Card</option>
													<option <?= (($edit == true && $detail['info']->payment_option == 'paypal') ? 'selected="selected"' : '') ?> value="paypal">PayPal</option>
													<option <?= (($edit == true && $detail['info']->payment_option == 'bank') ? 'selected="selected"' : '') ?> value="bank">Bank transfer</option>
									    	</select>
									    </div>
									  </div>
									  
									   <!-- check in mileage-->
									  <div class="form-group">
									    <label for="carrental-check-in-mileage" class="col-sm-3 control-label">Check in mileage</label>
									    <div class="col-sm-9">
									    	<input type="text" name="check_in_mileage" class="form-control" id="carrental-check-in-mileage" value="<?= (($edit == true) ? $detail['info']->check_in_mileage : '') ?>">
									    </div>
									  </div>
									  
									  <!-- check out mileage-->
									  <div class="form-group">
									    <label for="carrental-check-out-mileage" class="col-sm-3 control-label">Check out mileage</label>
									    <div class="col-sm-9">
									    	<input type="text" name="check_out_mileage" class="form-control" id="carrental-check-out-mileage" value="<?= (($edit == true) ? $detail['info']->check_out_mileage : '') ?>">
									    </div>
									  </div>
									  
									  <!-- check in fuel-->
									  <div class="form-group">
									    <label for="carrental-check-in-fuel" class="col-sm-3 control-label">Check in fuel</label>
									    <div class="col-sm-9">
									    	<input type="text" name="check_in_fuel" class="form-control" id="carrental-check-in-fuel" value="<?= (($edit == true) ? $detail['info']->check_in_fuel : '') ?>">
									    </div>
									  </div>
									  
									  <!-- check out mileage-->
									  <div class="form-group">
									    <label for="carrental-check-out-fuel" class="col-sm-3 control-label">Check out fuel</label>
									    <div class="col-sm-9">
									    	<input type="text" name="check_out_fuel" class="form-control" id="carrental-check-out-fuel" value="<?= (($edit == true) ? $detail['info']->check_out_fuel : '') ?>">
									    </div>
									  </div>
										
										<!-- Comment -->
										<div class="form-group">
									    <label for="carrental-comment" class="col-sm-3 control-label">Comments</label>
									    <div class="col-sm-9">
									    	<textarea name="comment" class="form-control" id="carrental-comment"><?= (($edit == true) ? $detail['info']->comment : '') ?></textarea>
									    </div>
									  </div>

										
										<div class="form-group">
									  	<div class="col-sm-3"></div>
									    <div class="col-sm-9">
									    	<h3>Additional drivers</h3>
									    </div>
									  </div>
									  
									  
									  <?php if ($edit == true && isset($detail['drivers'])) { ?>
									  	<?php foreach ($detail['drivers'] as $key => $val) { ?>
									  		<?php $drv = $key + 1; ?>
									  		
									  		<div class="form-group additional_driver">
											    <label class="col-sm-3 control-label">
														<a href="javascript:void(0);" class="btn btn-xs btn-danger delete_driver">Delete</a>
														&nbsp;&nbsp;&nbsp;Driver no. <?= $drv ?>
													</label>
											    <div class="col-sm-9">
											    	
													  <div class="form-group">
													    <div class="col-sm-6">
													    	<input type="text" name="drv[first_name][]" class="form-control" placeholder="First name" value="<?= $val->first_name ?>">
													    </div>
													    <div class="col-sm-6">
													    	<input type="text" name="drv[last_name][]" class="form-control" placeholder="Last name" value="<?= $val->last_name ?>">
													    </div>
													  </div>
													  
													  <div class="form-group">
													    <div class="col-sm-6">
													    	<input type="text" name="drv[email][]" class="form-control" placeholder="E-mail" value="<?= $val->email ?>">
													    </div>
													    <div class="col-sm-6">
													    	<input type="text" name="drv[phone][]" class="form-control" placeholder="Phone" value="<?= $val->phone ?>">
													    </div>
													  </div>
													  
													  <!-- Street //-->
													  <div class="form-group">
													    <div class="col-sm-6">
													    	<input type="text" name="drv[street][]" class="form-control" placeholder="Street" value="<?= $val->street ?>">
													    </div>
													    <div class="col-sm-6">
													    	<input type="text" name="drv[city][]" class="form-control" placeholder="City" value="<?= $val->city?>">
													    </div>
													  </div>
													 	
													  <!-- ZIP //-->
													  <div class="form-group">
													    <div class="col-sm-4">
													    	<input type="text" name="drv[zip][]" class="form-control" placeholder="ZIP" value="<?= $val->zip ?>">
													    </div>
													    <div class="col-sm-8">
													    	<select name="drv[country][]" class="form-control">
														    	<option value="none">- select -</option>
														    	<?php $countries = CarRental_Admin::get_country_list(); ?>
														    	<?php foreach ($countries as $kD => $vD) { ?>
														    		<option value="<?= $kD ?>" <?= (($edit == true && $kD == $val->country) ? 'selected="selected"' : '') ?>><?= $vD ?></option>
														    	<?php } ?>
													    	</select>
													    </div>
													  </div>
													  											    	
											    </div>
											  </div>
									  		
									  	<?php } ?>
									  <?php } ?>
									  
									  <div class="form-group additional_driver additional_driver_new">
									    <label class="col-sm-3 control-label">
												<a href="javascript:void(0);" class="btn btn-xs btn-danger delete_driver">Delete</a>
												&nbsp;&nbsp;&nbsp;New driver												
											</label>
									    <div class="col-sm-9">
									    	
											  <div class="form-group">
											    <div class="col-sm-6">
											    	<input type="text" name="drv[first_name][]" class="form-control" placeholder="First name">
											    </div>
											    <div class="col-sm-6">
											    	<input type="text" name="drv[last_name][]" class="form-control" placeholder="Last name">
											    </div>
											  </div>
											  
											  <div class="form-group">
											    <div class="col-sm-6">
											    	<input type="text" name="drv[email][]" class="form-control" placeholder="E-mail">
											    </div>
											    <div class="col-sm-6">
											    	<input type="text" name="drv[phone][]" class="form-control" placeholder="Phone">
											    </div>
											  </div>
											  
											  <div class="form-group">
											    <div class="col-sm-6">
											    	<input type="text" name="drv[street][]" class="form-control" placeholder="Street">
											    </div>
											    <div class="col-sm-6">
											    	<input type="text" name="drv[city][]" class="form-control" placeholder="City">
											    </div>
											  </div>
											 	
											  <div class="form-group">
											    <div class="col-sm-4">
											    	<input type="text" name="drv[zip][]" class="form-control" placeholder="ZIP">
											    </div>
											    <div class="col-sm-8">
											    	<select name="drv[country][]" class="form-control">
												    	<option value="none">- select -</option>
												    	<?php $countries = CarRental_Admin::get_country_list(); ?>
												    	<?php foreach ($countries as $kD => $vD) { ?>
												    		<option value="<?= $kD ?>"><?= $vD ?></option>
												    	<?php } ?>
											    	</select>
											    </div>
											  </div>
											  
											  
											  <div class="form-group">
											    <div class="col-sm-6">
											    	<input type="text" name="drv[license][]" class="form-control" placeholder="license">
											    </div>
											  </div>
											 	
											  <div class="form-group">
											    <div class="col-sm-4">
											    	<input type="text" name="drv[license_expiry_date][]" class="form-control" placeholder="license expiry date">
											    </div>
											    <div class="col-sm-8">
											    	<select name="drv[issue_country][]" class="form-control">
												    	<option value="none">- Country of issue -</option>
												    	<?php $countries = CarRental_Admin::get_country_list(); ?>
												    	<?php foreach ($countries as $kD => $vD) { ?>
												    		<option value="<?= $kD ?>"><?= $vD ?></option>
												    	<?php } ?>
											    	</select>
											    </div>
											  </div>
											  		    	
									    </div>
									  </div>
									  
									  <div class="form-group additional_driver_new_button">
									  	<label class="col-sm-3 control-label"></label>
									    <div class="col-sm-9">
									  		<a href="javascript:void(0);" class="btn btn-success add_another_driver">Add another driver</a>
									  	</div>
									  </div>
									  
									  <script type="text/javascript">
									  
									  	jQuery(document).ready(function() {
												
												jQuery('.additional_driver_new').hide();
												
												jQuery(document).on('click', '.delete_driver', function() {
													jQuery(this).parent().parent().remove();
												});
												
												jQuery('.add_another_driver').on('click', function() {
													jQuery('.additional_driver_new_button').before('<div class="form-group additional_driver">' + jQuery('.additional_driver_new').html() + '</div>');
												});
												
											});
									  
									  </script>
									  
									  <div class="form-group">
									  	<div class="col-sm-3"></div>
									    <div class="col-sm-9">
									    	<h3>Prices</h3>
									    </div>
									  </div>
										
										
										<?php $currency = array(get_option('carrental_global_currency')); ?>
										<?php $av_currencies = unserialize(get_option('carrental_available_currencies')); ?>
										<?php if (!empty($av_currencies)) { $av_currencies = array_keys($av_currencies); $currency = array_merge($currency, $av_currencies); } ?>
										
										<?php if (isset($detail['prices']) && !empty($detail['prices'])) { ?>
											<?php foreach ($detail['prices'] as $key => $val) { ?>
												<div class="form-group price_row">
											    <label class="col-sm-3 control-label">
														<a href="javascript:void(0);" class="btn btn-xs btn-danger delete_price">Delete</a>
														&nbsp;&nbsp;&nbsp;Row no. <?= $key+1 ?>
													</label>
											    <div class="col-sm-9">
													  <div class="form-group">
													    <div class="col-sm-8">
													    	<input type="text" name="prices[name][]" class="form-control" value="<?= (($edit == true) ? $val->name : '') ?>">
													    </div>
													    <div class="col-sm-2">
													    	<div class="form-group">
															    <input type="text" name="prices[price][]" class="form-control" value="<?= (($edit == true) ? $val->price : '') ?>">
															  </div>
													    </div>
													    <div class="col-sm-2">
													    	<div class="form-group">
															    <select name="prices[currency][]" class="form-control price_currency" style="width:70%;margin-left:1em;padding: 3px 3px;height: 2.35em;">
															    	<?php foreach ($currency as $cc) { ?>
																			<option value="<?= $cc ?>" <?php if ($edit == true && $val->currency == $cc) { ?>selected<?php } ?>><?= $cc ?></option>
																		<?php } ?>
															    </select>
															  </div>
													    </div>
													  </div>
											    </div>
											  </div>
											  
											<?php } ?>
										<?php } ?>
										
										<div class="form-group price_new">
									    <label class="col-sm-3 control-label">
												<a href="javascript:void(0);" class="btn btn-xs btn-danger delete_price">Delete</a>
												&nbsp;&nbsp;&nbsp;New row
											</label>
									    <div class="col-sm-9">
											  <div class="form-group">
											    <div class="col-sm-8">
											    	<input type="text" name="prices[name][]" class="form-control" placeholder="Description">
											    </div>
											    <div class="col-sm-2">
											    	<div class="form-group">
													    <input type="text" name="prices[price][]" class="form-control" placeholder="Price">
													  </div>
											    </div>
											    <div class="col-sm-2">
											    	<div class="form-group">
													    <select name="prices[currency][]" class="form-control price_currency" style="width:70%;margin-left:1em;padding: 3px 3px;height: 2.35em;">
													    	<?php foreach ($currency as $cc) { ?>
																	<option value="<?= $cc ?>"><?= $cc ?></option>
																<?php } ?>
													    </select>
													  </div>
											    </div>
											  </div>
									    </div>
									  </div>
										
										
									  <div class="form-group price_new_button">
									  	<label class="col-sm-3 control-label"></label>
									    <div class="col-sm-9">
									  		<a href="javascript:void(0);" class="btn btn-success add_price">Add another row</a>
									  	</div>
									  </div>
									  
									  <script type="text/javascript">
									  
									  	jQuery(document).ready(function() {
												
												jQuery('.price_new').hide();
												
												jQuery(document).on('click', '.delete_price', function() {
													jQuery(this).parent().parent().remove();
												});
												
												jQuery('.add_price').on('click', function() {
													jQuery('.price_new_button').before('<div class="form-group price_row">' + jQuery('.price_new').html() + '</div>');
													jQuery('.price_currency').val(jQuery('.price_currency').first().val());
												});
												
												jQuery(document).on('change', '.price_currency', function() {
													var currency = jQuery(this).val();
													jQuery('.price_currency').val(currency);
												});
												
											});
									  
									  </script>
									  
									  <!-- Submit //-->
									  <div class="form-group">
									  	<div class="col-sm-offset-3 col-sm-9">
									  		<?php if ($edit == true) { ?>
									  			<input type="hidden" name="id_booking" value="<?= $detail['info']->id_booking ?>">
									  			<button type="submit" class="btn btn-warning" name="add_booking"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Confirm &amp; Save</button>
									  		<?php } else { ?>
									  			<button type="submit" class="btn btn-warning" name="add_booking"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Confirm &amp; Add</button>
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
						
						<?php if (isset($booking) && !empty($booking)) { ?>
						
							<table class="table table-striped" id="carrental-extras">
					      <thead>
					        <tr>
					          <th>#</th>
					          <th>Vehicle</th>
					          <th>Enter date</th>
					          <th>Enter loc.</th>
					          <th>Return date</th>
					          <th>Return loc.</th>
					          <th>Price</th>
					          <th>Order ID</th>
					          <th>Status</th>
					          <th>Action</th>
					        </tr>
					      </thead>
					      <tbody>
					      	
					      	<?php foreach ($booking as $key => $val) { ?>
				      		<tr>
					          <td>
											<input type="checkbox" class="input-control batch_processing" name="batch[]" value="<?= $val->id_booking ?>">&nbsp;
											<abbr title="Created: <?= $val->created ?>
								<?= (!empty($val->updated) ? 'Updated: ' . $val->updated : '') ?>"><?= $val->id_booking ?></abbr>
										<td><strong><?= (!empty($val->vehicle) ? $val->vehicle : '- Unknown -') ?></strong></td>
										<td><?= $val->enter_date ?></td>
										<td><?= $val->enter_loc ?></td>
					          <td><?= $val->return_date ?></td>
					          <td><?= $val->return_loc ?></td>
					          <td><?= CarRental::get_currency_symbol('before', $val->currency) ?><?= number_format($val->total_rental, 2, '.', ',') ?><?= CarRental::get_currency_symbol('after', $val->currency) ?></td>
					          <td><a href="<?= esc_url(home_url('/')); ?>?page=carrental&summary=<?= $val->hash ?>" target="_blank" class="btn btn-info btn-xs">Show #<?= $val->id_order ?></a></td>
					          <td>
					          	<?php if (!empty($val->status)) { ?>
					          		
												<p><?= $val->status ?></p>
											<?php } else { ?>
												&mdash;
											<?php } ?>
										</td>
										<td>
											<form action="" method="post" class="form-inline" role="form">
												<div class="form-group">
													<a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-booking')); ?>&amp;edit=<?= $val->id_booking ?>" class="btn btn-xs btn-primary">Modify</a>
												</div>
											</form>
											<form action="" method="post" class="form-inline" role="form">
												<div class="form-group">
													<input type="hidden" name="id_booking" value="<?= $val->id_booking ?>">
													<button name="copy_booking" class="btn btn-xs btn-warning">Copy</button>
												</div>
											</form>
											<?php if (isset($_GET['deleted'])) { ?>
												<form action="" method="post" class="form-inline" role="form" onsubmit="return confirm('<?= __('Do you really want to restore this Booking?', 'carrental') ?>');">
													<div class="form-group">
														<input type="hidden" name="id_booking" value="<?= $val->id_booking ?>">
														<button name="restore_booking" class="btn btn-xs btn-success">Restore</button>
													</div>
												</form>
											<?php } else { ?>
												<form action="" method="post" class="form-inline" role="form" onsubmit="return confirm('<?= __('Do you really want to delete this Booking?', 'carrental') ?>');">
													<div class="form-group">
														<input type="hidden" name="id_booking" value="<?= $val->id_booking ?>">
														<button name="delete_booking" class="btn btn-xs btn-danger">Delete</button>
													</div>
												</form>
											<?php } ?>
										</td>
					        </tr>
									<?php } ?>
					      </tbody>
					    </table>
							
					    <h4>Batch action on selected items</h4>
					    
					    <form action="" method="post" class="form" role="form" onsubmit="if (jQuery('[name=batch_processing_values]').val() == '') { alert('No Booking is selected to copy.'); return false }; return confirm('<?= __('Do you really want to copy selected Bookings?', 'carrental') ?>');">
								<div class="form-group">
									<input type="hidden" name="batch_processing_values" value="">
									<button name="batch_copy_booking" class="btn btn-warning">Copy <span class="batch_processing_count"></span>selected Bookings</button>
								</div>
							</form>
							
					    <form action="" method="post" class="form" role="form" onsubmit="if (jQuery('[name=batch_processing_values]').val() == '') { alert('No Booking is selected to delete.'); return false }; return confirm('<?= __('Do you really want to delete selected Bookings?', 'carrental') ?>');">
								<div class="form-group">
									<input type="hidden" name="batch_processing_values" value="">
									<button name="batch_delete_booking" class="btn btn-danger">Delete <span class="batch_processing_count"></span>selected Bookings</button>
								</div>
							</form>
					    
					    
						<?php } else { ?>
							<div class="alert alert-info">
								<span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;
								<?= esc_html__( 'There are no active Bookings.', 'carrental' ); ?>
							</div>
						<?php } ?>
						
					</div>
				</div>
				
				
				
			</div>
		</div>
	</div>
	
</div>



