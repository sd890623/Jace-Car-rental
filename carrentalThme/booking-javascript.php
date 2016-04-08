<script type="text/javascript">

	jQuery(document).ready(function() {
		
		var branch_hours = {};
		
		<?php if (isset($locations) && !empty($locations)) { ?>
			<?php foreach ($locations as $key => $val) { ?>
				branch_hours[<?= $val->id_branch ?>] = {};
				<?php if (isset($val->hours) && !empty($val->hours)) { ?>
					<?php foreach ($val->hours as $kD => $vD) { ?> 
						branch_hours[<?= $val->id_branch ?>][<?= $vD->day ?>] = { 'from': '<?= $vD->hours_from ?>', 'to' : '<?= $vD->hours_to ?>'};
					<?php } ?>
				<?php } ?>
			<?php } ?>	
		<?php } ?>
			
		if (jQuery('#carrental_from_hour').length) {
			// call time update after page reload
			carrental_update_business_hours();
		}
		
		jQuery('#carrental_return_location').hide();
		
		<?php if (isset($_GET['rl']) && !empty($_GET['rl']) && isset($_GET['dl']) && $_GET['dl'] == 'on') { ?>
			jQuery('#carrental_return_location').show();
		<?php } ?>
		
		jQuery('#carrental_different_loc').click(function() {
			jQuery('#carrental_return_location').toggle('fast');
		});
		
		jQuery('.carrental_car_details').hide();
		jQuery('.carrental_car_details_link').click(function() {
			jQuery(this).parent().parent().find('.carrental_car_details').toggle('fast');
		});
		<?php $theme_options = unserialize(get_option('carrental_theme_options')); ?>
		jQuery('#carrental_from_date, #carrental_to_date').datepicker({
      //showOn: "both",
      beforeShow: carrental_customRange,
      dateFormat: "<?php echo date_format_js(isset($theme_options['date_format']) ? $theme_options['date_format'] : '');?>",
	  firstDay: "<?php echo (isset($theme_options['date_format_first_day']) ? (int)$theme_options['date_format_first_day'] : 0);?>",
	  dayNamesMin: [ "<?php echo  CarRental::t('Su')?>", "<?php echo  CarRental::t('Mo')?>", "<?php echo  CarRental::t('Tu')?>", "<?php echo  CarRental::t('Wu')?>", "<?php echo  CarRental::t('Th')?>", "<?php echo  CarRental::t('Fr')?>", "<?php echo  CarRental::t('Sa')?>" ],
	  monthNames: [ "<?php echo  CarRental::t('January')?>", "<?php echo  CarRental::t('February')?>", "<?php echo  CarRental::t('March')?>", "<?php echo  CarRental::t('April')?>", "<?php echo  CarRental::t('May')?>", "<?php echo  CarRental::t('June')?>", "<?php echo  CarRental::t('July')?>", "<?php echo  CarRental::t('August')?>", "<?php echo  CarRental::t('September')?>", "<?php echo  CarRental::t('October')?>", "<?php echo  CarRental::t('November')?>", "<?php echo  CarRental::t('December')?>" ],
	  dayNames: [ "<?php echo  CarRental::t('Sunday')?>", "<?php echo  CarRental::t('Monday')?>", "<?php echo  CarRental::t('Tuesday')?>", "<?php echo  CarRental::t('Wednesday')?>", "<?php echo  CarRental::t('Thursday')?>", "<?php echo  CarRental::t('Friday')?>", "<?php echo  CarRental::t('Saturday')?>" ],
	  nextText: "<?php echo  CarRental::t('Next')?>",
	  prevText: "<?php echo  CarRental::t('Prev')?>",
      minDate: 0,
      onSelect: function() {
		    carrental_update_business_hours();
		  }
  	});
  
		function carrental_customRange(input) {
	    if (input.id == 'carrental_to_date') {
        var minDate = jQuery.datepicker.parseDate('<?php echo date_format_js(isset($theme_options['date_format']) ? $theme_options['date_format'] : '');?>', jQuery('#carrental_from_date').val()); //new Date(jQuery.datepicker.formatDate('yy-mm-dd', );jQuery('#carrental_from_date').val());
        minDate.setDate(minDate.getDate());
        return { minDate: minDate };
	    }
	    return {}
		}
		
		jQuery('#carrental_enter_location').on('change', function() {
			carrental_update_business_hours();				
		});
		
		jQuery('#carrental_return_location').on('change', function() {
			carrental_update_business_hours();				
		});
		
		jQuery('#carrental_different_loc').on('click', function() {
			carrental_update_business_hours();				
		});
		
		jQuery('#carrental_booking_form').on('submit', function() {
			
			var errors = [];
			
			// Check enter location
			if (jQuery('#carrental_enter_location').val() > 0) {
			} else {
				errors.push('<?= CarRental::t('Please, select enter location.') ?>');	
			}
			
			// Check return location (if checked)
			if (jQuery('#carrental_different_loc:checked').val() == 'on') {
				if (jQuery('#carrental_return_location').val() > 0) {
				} else {
					errors.push('<?= CarRental::t('Please, select return location or disable the "Returning to Different location".') ?>');	
				}
			}
			
			// Check dates (from and to)
				var from_date = jQuery.datepicker.parseDate('<?php echo date_format_js(isset($theme_options['date_format']) ? $theme_options['date_format'] : '');?>', jQuery('#carrental_from_date').val());
				if (from_date != null && from_date != 'Invalid Date' && from_date >= new Date()) {
				} else {
					errors.push('<?= CarRental::t('Please, select pick-up date properly.') ?>');	
				}
				
				var to_date = jQuery.datepicker.parseDate('<?php echo date_format_js(isset($theme_options['date_format']) ? $theme_options['date_format'] : '');?>', jQuery('#carrental_to_date').val());
				if (to_date != null && to_date != 'Invalid Date' && to_date >= new Date()) {
				} else {
					errors.push('<?= CarRental::t('Please, select return date properly.') ?>');	
				}
			
			// Check times (from and to)
			
				if (jQuery('#carrental_from_hour').val() != '') {
				} else {
					errors.push('<?= CarRental::t('Please, select pick-up time properly.') ?>');	
				}
				
				if (jQuery('#carrental_to_hour').val() != '') {
				} else {
					errors.push('<?= CarRental::t('Please, select return time properly.') ?>');	
				}
			
			// Filters
			var flt = [];
			
				// Price range
				if (jQuery('#carrental_filter_price_range').is(':hidden') == false) {
					flt.push('spr:' + parseInt(jQuery('#carrental_filter_price_range .inputSliderMin').val()));
					flt.push('epr:' + parseInt(jQuery('#carrental_filter_price_range .inputSliderMax').val()));
				}
				
				// Extras
				if (jQuery('#carrental_filter_extras').is(':hidden') == false) {
					if (jQuery('[name=ac]').is(':checked') == true) {
						flt.push('ac:' + parseInt(jQuery('[name=ac]').val()));
					}
					if (jQuery('[name=nonac]').is(':checked') == true) {
						flt.push('nac:' + parseInt(jQuery('[name=nonac]').val()));
					}
				}
				
				// Fuel
				if (jQuery('#carrental_filter_fuel').is(':hidden') == false) {
					if (jQuery('[name=petrol]').is(':checked') == true) {
						flt.push('pl:' + parseInt(jQuery('[name=petrol]').val()));
					}
					if (jQuery('[name=diesel]').is(':checked') == true) {
						flt.push('dl:' + parseInt(jQuery('[name=diesel]').val()));
					}
				}
				
				// Passengers
				if (jQuery('#carrental_filter_passangers').is(':hidden') == false) {
					flt.push('sp:' + parseInt(jQuery('#carrental_filter_passangers .slider-input-start').val()));
					flt.push('ep:' + parseInt(jQuery('#carrental_filter_passangers .slider-input-end').val()));
				}
				
				// Categories
				if (jQuery('#carrental_filter_categories').is(':hidden') == false) {
					var cats = [];
					jQuery('.categories_checkall:checked').each(function() {
						cats.push(jQuery(this).val());
					});
					if (cats.length > 0) {
						flt.push('cats:' + cats.join(','));
					}
				}
				
				// Vehicles
				if (jQuery('#carrental_filter_vehicles').is(':hidden') == false) {
					var cats = [];
					jQuery('.vehicles_checkall:checked').each(function() {
						cats.push(jQuery(this).val());
					});
					if (cats.length > 0) {
						flt.push('vh:' + cats.join(','));
					}
				}
				
				if (flt.length > 0) {
					jQuery('[name=flt]').val(flt.join('|'));
				} else {
					jQuery('[name=flt]').val('');
				}
			
			if (errors.length == 0) {
				return true;
			} else {
				jQuery('#carrental_book_errors').html('<li>' + errors.join('</li><li>') + '</li>');
				return false;
			}
		});
		
		function carrental_update_business_hours() {
			try {
			
				var id_branch = jQuery('#carrental_enter_location').val();
				var id_branch_return = jQuery('#carrental_return_location').val();
				
				if (typeof id_branch_return === "undefined" || id_branch_return == '' || jQuery('#carrental_different_loc:checked').val() != 'on') {
					id_branch_return = id_branch;
				}
				
				if (typeof branch_hours[id_branch] !== "undefined" && branch_hours[id_branch] &&
						typeof branch_hours[id_branch_return] !== "undefined" && branch_hours[id_branch_return]) {
					
					var date_from = jQuery('#carrental_from_date').val();
					if (typeof date_from === "undefined" || date_from == '') {
						date_from = jQuery.datepicker.formatDate('<?php echo date_format_js(isset($theme_options['date_format']) ? $theme_options['date_format'] : '');?>', new Date());
					}
					
					var date_to = jQuery('#carrental_to_date').val();
					if (typeof date_to === "undefined" || date_to == '') {
						date_to = jQuery.datepicker.formatDate('<?php echo date_format_js(isset($theme_options['date_format']) ? $theme_options['date_format'] : '');?>', new Date());
					}
					
					// reformat to YYYY-MM-DD
					date_from = jQuery.datepicker.formatDate('yy-mm-dd', jQuery.datepicker.parseDate('<?php echo date_format_js(isset($theme_options['date_format']) ? $theme_options['date_format'] : '');?>', date_from));
					date_to = jQuery.datepicker.formatDate('yy-mm-dd', jQuery.datepicker.parseDate('<?php echo date_format_js(isset($theme_options['date_format']) ? $theme_options['date_format'] : '');?>', date_to));
					
					var full_date_from = new Date(date_from);
					var day_week_from = full_date_from.getDay();
					if (day_week_from == 0) { day_week_from = 7; } // sunday
					
					var full_date_to = new Date(date_to);
					var day_week_to = full_date_to.getDay();
					if (day_week_to == 0) { day_week_to = 7; } // sunday
					
					// DATE FROM
					if (typeof branch_hours[id_branch][day_week_from] !== "undefined" && branch_hours[id_branch][day_week_from]) {
					
						var from = branch_hours[id_branch][day_week_from]['from'].substring(0, 5); // get off seconds
						var to = branch_hours[id_branch][day_week_from]['to'].substring(0, 5);
						var prev_val = jQuery("#carrental_from_hour").val();
						
						jQuery("#carrental_from_hour").attr('disabled', false);
						jQuery('#carrental_from_hour').find('option').remove().end().append('<option value=""><?= CarRental::t('Time') ?></option>'); // delete all previous options
						
						for (x = parseInt(from); x <= parseInt(to); x++) {
							var hour = String(x);
							if (hour.length == 1) { hour = '0' + hour; }
							
							if (x == parseInt(from) && parseInt(from.substr(-2)) >= 30) {
								// Do not show
							} else {
								jQuery("#carrental_from_hour").append(new Option(hour + ':00', hour + ':00'));
							}
							
							if (x == parseInt(to) && parseInt(to.substr(-2)) < 30) {
								// Do not show
							} else {
								jQuery("#carrental_from_hour").append(new Option(hour + ':30', hour + ':30'));
							}
							
						}
						
						if (prev_val != '' && jQuery("#carrental_from_hour option[value='" + prev_val + "']").val() !== undefined) {
							jQuery("#carrental_from_hour").val(prev_val);
						}
					
					} else {
						jQuery('#carrental_from_hour').find('option').remove().end().append('<option value=""><?= CarRental::t('Closed') ?></option>'); // delete all previous options
						jQuery("#carrental_from_hour").attr('disabled', true);
					}
					
					
					// DATE TO
					if (typeof branch_hours[id_branch_return][day_week_to] !== "undefined" && branch_hours[id_branch_return][day_week_to]) {
						
						var from = branch_hours[id_branch_return][day_week_to]['from'].substring(0, 5); // get off seconds
						var to = branch_hours[id_branch_return][day_week_to]['to'].substring(0, 5);
						var prev_val = jQuery("#carrental_to_hour").val();
						
						jQuery("#carrental_to_hour").attr('disabled', false);
						jQuery('#carrental_to_hour').find('option').remove().end().append('<option value=""><?= CarRental::t('Time') ?></option>'); // delete all previous options
					
						for (x = parseInt(from); x <= parseInt(to); x++) {
							var hour = String(x);
							if (hour.length == 1) { hour = '0' + hour; }
							
							if (x == parseInt(from) && parseInt(from.substr(-2)) >= 30) {
								// Do not show
							} else {
								jQuery("#carrental_to_hour").append(new Option(hour + ':00', hour + ':00'));
							}
							
							if (x == parseInt(to) && parseInt(to.substr(-2)) < 30) {
								// Do not show
							} else {
								//if (prev_val == hour + ':30') { var selected = true; } else { var selected = false; }
								jQuery("#carrental_to_hour").append(new Option(hour + ':30', hour + ':30'));
							}
							
						}
						
						if (prev_val != '' && jQuery("#carrental_to_hour option[value='" + prev_val + "']").val() !== undefined) {
							jQuery("#carrental_to_hour").val(prev_val);
						}
					
					} else {
						jQuery('#carrental_to_hour').find('option').remove().end().append('<option value=""><?= CarRental::t('Closed') ?></option>');
						jQuery("#carrental_to_hour").attr('disabled', true);
					}
					
				}
				
			} catch(e) {
				alert(e);
			}
		}
		
	});


</script>