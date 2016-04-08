/*
Car Rental WP Plugin - 2014

v 1.4.1

*/

jQuery(document).ready(function($) {
	
	$('#carrental-fleet-add-form').hide();
	$('#carrental-extras-add-form').hide();
	$('#carrental-branches-add-form').hide();
	$('#carrental-booking-add-form').hide();
	$('#carrental-pricing-add-form').hide();
	$('#carrental-language-add-form').hide();
	$('#carrental-language-primary-form').hide();
	
  $('#carrental-fleet').dataTable({ stateSave: true });
	$('#carrental-extras').dataTable({ stateSave: true });
	$('#carrental-branches').dataTable({ stateSave: true });
	$('#carrental-booking').dataTable({ stateSave: true });
	$('#carrental-pricing').dataTable({ stateSave: true });
	$('#carrental-newsletter').dataTable({
		stateSave: true,
		"dom": 'T<"clear">lfrtip',
    "tableTools": {
      "sSwfPath": "../wp-content/plugins/carrental/assets/swf/copy_csv_xls_pdf.swf"
    }
	});
	
	
	$("#carrental-fleet-add-button").click(function() {
	  $("#carrental-fleet-add-form").toggle("slow");
	});

	$("#carrental-extras-add-button").click(function() {
	  $("#carrental-extras-add-form").toggle("slow");
	});
	
	$("#carrental-branches-add-button").click(function() {
	  $("#carrental-branches-add-form").toggle("slow");
	});
	
	$("#carrental-pricing-add-button").click(function() {
	  $("#carrental-pricing-add-form").toggle("slow");
	});
	
	$("#carrental-booking-add-button").click(function() {
	  $("#carrental-booking-add-form").toggle("slow");
	});
	
	$("#carrental-language-add-button").click(function() {
	  $("#carrental-language-add-form").toggle("slow");
	});
	
	$("#carrental-language-primary-button").click(function() {
	  $("#carrental-language-primary-form").toggle("slow");
	});
	
	$('#carrental-add-pricing-scheme').click(function() {
		carrental_add_pricing();
	});
	
	$("#carrental-hour-range-box-show").click(function() {
	  $("#carrental-hour-range-box").toggle("fast");
	});
	
	
	$('#carrental-add-av-currencies').click(function() {
		$('#carrental-av-currencies-insert').after($("#carrental-av-currencies").html());
	});
	
	$('#carrental-add-vehicle-category').click(function() {
		$('#carrental-vehicle-cats-insert').before('<tr>' + $("#carrental-vehicle-cats").html() + '</tr>');
	});
	
	$('#carrental-add-day-range').click(function() {
		$('#day-range-row-before').before('<tr>' + $("#day-range-row").html() + '</tr>');
	});
	
	$('#carrental-add-hour-range').click(function() {
		$('#hour-range-row-before').before('<tr>' + $("#hour-range-row").html() + '</tr>');
	});
	
	$('#carrental-fleet-add-form form').on('submit', function() {
		if ($('#carrental-type').val() == '') {
			alert('Sorry, Name of the vehicle should not be empty.');
			return false;
		}
	});
	
	$('#carrental-extras-add-form form').on('submit', function() {
		if ($('#carrental-name').val() == '') {
			alert('Sorry, Name of the item should not be empty.');
			return false;
		}
	});
	
	$('#carrental-branches-add-form form').on('submit', function() {
		if ($('#carrental-name').val() == '') {
			alert('Sorry, Name of the branch should not be empty.');
			return false;
		}
	});
	
	$('#carrental-pricing-add-form form').on('submit', function() {
		if ($('#carrental-name').val() == '') {
			alert('Sorry, Name of the scheme should not be empty.');
			return false;
		}
	});
	
	$(document).on('keyup', '[name=days\\[from\\]\\[\\]]', function() {
		carrental_check_ranges('days');
	});
	
	$(document).on('keyup', '[name=days\\[to\\]\\[\\]]', function() {
		carrental_check_ranges('days');
	});
	
	$(document).on('keyup', '[name=hours\\[from\\]\\[\\]]', function() {
		carrental_check_ranges('hours');
	});
	
	$(document).on('keyup', '[name=hours\\[to\\]\\[\\]]', function() {
		carrental_check_ranges('hours');
	});
	
	$('[name=currency]').on('change', function() {
		carrental_pricing_set_currency();
	});
	
	$('[name=type]').on('click', function() {
		if ($(this).val() == 1) {
			$('.type-onetime').show();
			$('.type-timerelated').hide();
		} else {
			$('.type-onetime').hide();
			$('.type-timerelated').show();
		}
		carrental_pricing_set_currency();
	});
	
	// Init
	$('#carrental-prices').hide();
	$('#days-range-help').hide();
	$('#hours-range-help').hide();
	
	carrental_add_pricing();
	carrental_pricing_set_currency();
	
	$("#pricing_sort").sortable();
	$("#pricing_sort").disableSelection();
										    
	$(document).on('click', '.pricing_datepicker', function() {
		$(this).datepicker({ dateFormat: 'yy-mm-dd' }).datepicker('show');
	});
	
	$(document).on('click', '.carrental_show_ranges', function() {
		var $dialog = $('<div>Loading...</div>')
				.load($(this).attr('href'))
				.dialog({
					autoOpen: true,
					title: 'Details',
					width: 700,
					height: 400,
					resizable: true
				});
		return false;
	});
	
	// Translations
	$('.carrental_translations_email_customers').hide();
	$('.carrental_translations_terms').hide();
	$('.carrental_translations_theme').hide();
	
	$(".carrental_translations_email_customers_toggle").click(function() {
	  $(".carrental_translations_email_customers").toggle("fast", function() {
			if ($(this).is(':hidden')) {
				$(".carrental_translations_email_customers_toggle").find('span').html('▼');
			} else {
				$(".carrental_translations_email_customers_toggle").find('span').html('▲');
			}	
		});
	});
	
	$(".carrental_translations_terms_toggle").click(function() {
	  $(".carrental_translations_terms").toggle("fast", function() {
			if ($(this).is(':hidden')) {
				$(".carrental_translations_terms_toggle").find('span').html('▼');
			} else {
				$(".carrental_translations_terms_toggle").find('span').html('▲');
			}	
		});
	});
	
	$(".carrental_translations_theme_toggle").click(function() {
	  $(".carrental_translations_theme").toggle("fast", function() {
			if ($(this).is(':hidden')) {
				$(".carrental_translations_theme_toggle").find('span').html('▼');
			} else {
				$(".carrental_translations_theme_toggle").find('span').html('▲');
			}	
		});
	});
	
	
	// Fleet translations
	$('.fleet_description').hide();
	$('.fleet_description_gb').show();
	
	$('.edit_fleet_description').click(function() {
		var lang = $(this).attr('data-value');
		$('.fleet_description').hide();
		$('.edit_fleet_description').parent().removeClass('active');
		$('.fleet_description_' + lang).show();
		$(this).parent().addClass('active');
	});
	
	
	$('.days-check-all').click(function() {
		if ($(this).is(':checked')) {
			$('.days-check').prop('checked', true);
		} else {
			$('.days-check').prop('checked', false);
		}
	});
	
	// Batch processing (fleet, extras, branches, pricing, booking)
	$(document).on('click', '.batch_processing', function() {
		var values = new Array();
		var values_delete = new Array();
		$('.batch_processing').each(function() {
			if ($(this).is(':checked')) {
				values.push($(this).val());
				if (parseInt($(this).attr('data-usage')) == 0) {
					values_delete.push($(this).val());
				}
			}
		});
		
		$('[name=batch_processing_values]').val(values.join(','));
		$('.batch_processing_count').html(((values.length > 0) ? values.length + ' ' : ''));
		
		$('[name=batch_processing_values_delete]').val(values_delete.join(','));
		$('.batch_processing_count_delete').html(((values_delete.length > 0) ? values_delete.length + ' ' : ''));

	});
	
	
});

function carrental_check_ranges(name) {
	var arr = [];
		
	jQuery('[name=' + name + '\\[from\\]\\[\\]]').each(function(i) {
		arr.push(jQuery(this).val());
	});
	
	jQuery('[name=' + name + '\\[to\\]\\[\\]]').each(function(i) {
		arr.push(jQuery(this).val());
	});
	
	arr.sort(function(a,b){return a - b});
	//$('#days-range-checker').html(arr);
	
	var results = [];
	for (var i = 0; i < arr.length - 1; i++) {
	  if (arr[i + 1] == arr[i]) {
	    results.push(arr[i]);
	  }
	}
	
	if (results != '') {
		jQuery('#' + name + '-range-help').show('fast');
	} else {
		jQuery('#' + name + '-range-help').hide('fast');
	}
	
}

function carrental_add_pricing() {
	var html = jQuery("#carrental-prices").html();
	jQuery('#carrental-prices-insert').before(html);
}

function carrental_pricing_set_currency() {
	jQuery('.addon-currency').html(jQuery('[name=currency]').val());	
}