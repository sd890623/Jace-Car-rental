<div class="carrental-wrapper">
	
	<?php include CARRENTAL_PAYMENTS__PLUGIN_DIR . 'views/header.php'; ?>
	
	<div class="row">
		<div class="col-md-12 carrental-main-wrapper">
			<div class="carrental-main-content">
				
				<?php include CARRENTAL_PAYMENTS__PLUGIN_DIR . 'views/flash_msg.php'; ?>
				
				
				<!-- GLOBAL SETTINGS //-->
				<div class="panel panel-default">
					<div class="panel-heading"><h4 id="global-settings">Payment options settings</h4></div>
					<div class="panel-body">
					  
						<?php $payments = unserialize(get_option('carrental_available_payments')); ?>
					  
					  <form action="" method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
					
							<div class="row">
								<div class="col-md-6">
									
								  <div class="form-group">
								    <label for="carrental-payment" class="col-sm-3 control-label">Cash payment</label>
								    <div class="col-sm-9">
									    <label class="radio-inline"><input type="radio" name="payment[cash]" value="yes" <?= ((isset($payments['payment']['cash']) && $payments['payment']['cash'] == 'yes') ? 'checked="checked"' : '') ?>>&nbsp; Yes</label>
											<label class="radio-inline"><input type="radio" name="payment[cash]" value="no" <?= ((isset($payments['payment']['cash']) && $payments['payment']['cash'] == 'no') ? 'checked="checked"' : '') ?>>&nbsp; No</label>
								    </div>
								  </div>
									
									<div class="form-group">
								    <label for="carrental-payment" class="col-sm-3 control-label">CC Payment</label>
								    <div class="col-sm-9">
									    <label class="radio-inline"><input type="radio" name="payment[cc]" value="yes" <?= ((isset($payments['payment']['cc']) && $payments['payment']['cc'] == 'yes') ? 'checked="checked"' : '') ?>>&nbsp; Yes</label>
											<label class="radio-inline"><input type="radio" name="payment[cc]" value="no" <?= ((isset($payments['payment']['cc']) && $payments['payment']['cc'] == 'no') ? 'checked="checked"' : '') ?>>&nbsp; No</label>
								    </div>
								  </div>
								   
								  <div class="form-group">
								    <label for="carrental-payment" class="col-sm-3 control-label">PayPal Payment</label>
								    <div class="col-sm-9">
										<label class="radio-inline"><input type="radio" class="paypalpayment" name="payment[paypal]" value="yes" <?= ((isset($payments['payment']['paypal']) && $payments['payment']['paypal'] == 'yes') ? 'checked="checked"' : '') ?>>&nbsp; Yes</label>
											<label class="radio-inline"><input type="radio" class="paypalpayment" name="payment[paypal]" value="no" <?= ((isset($payments['payment']['paypal']) && $payments['payment']['paypal'] == 'no') ? 'checked="checked"' : '') ?>>&nbsp; No</label>
								    </div>
								  </div>
									
									<div id="paypal_options" <?= ((isset($payments['payment']['paypal']) && $payments['payment']['paypal'] == 'yes') ? '' : 'style="display:none;"') ?>>
										<div class="form-group">
											<label for="carrental_paypal_security_deposit" class="col-sm-3 control-label">PayPal Security deposit</label>
											<div class="col-sm-9">
												<div class="row">
													<div class="col-xs-3"><input type="text" name="carrental-paypal-security-deposit" id="carrental_paypal_security_deposit" class="form-control" value="<?= (isset($payments['carrental-paypal-security-deposit']) ? $payments['carrental-paypal-security-deposit'] : '') ?>"></div>
													<div class="col-xs-1"><h4>%</h4></div>
												</div>
												<p class="help-block">This amount will be required from client when booking the car by PayPal.</p>
											</div>
										</div>
										
										<div class="form-group">
											<label for="" class="col-sm-3 control-label">Round security deposit?</label>
											<div class="col-sm-9">
													<label class="radio-inline"><input type="radio" name="carrental-paypal-security-deposit-round" value="none" <?= ((isset($payments['carrental-paypal-security-deposit-round']) && $payments['carrental-paypal-security-deposit-round'] == 'none') ? 'checked="checked"' : '') ?>>&nbsp; None</label>
													<label class="radio-inline"><input type="radio" name="carrental-paypal-security-deposit-round" value="up" <?= ((isset($payments['carrental-paypal-security-deposit-round']) && $payments['carrental-paypal-security-deposit-round'] == 'up') ? 'checked="checked"' : '') ?>>&nbsp; Up</label>
													<label class="radio-inline"><input type="radio" name="carrental-paypal-security-deposit-round" value="down" <?= ((isset($payments['carrental-paypal-security-deposit-round']) && $payments['carrental-paypal-security-deposit-round'] == 'down') ? 'checked="checked"' : '') ?>>&nbsp; Down</label>
											</div>
										</div>
									</div>	
									
								  
								  <div class="form-group">
								    <label for="carrental-payment" class="col-sm-3 control-label">Bank Transfer</label>
								    <div class="col-sm-9">
									    <label class="radio-inline"><input type="radio" name="payment[bank]" value="yes" <?= ((isset($payments['payment']['bank']) && $payments['payment']['bank'] == 'yes') ? 'checked="checked"' : '') ?>>&nbsp; Yes</label>
											<label class="radio-inline"><input type="radio" name="payment[bank]" value="no" <?= ((isset($payments['payment']['bank']) && $payments['payment']['bank'] == 'no') ? 'checked="checked"' : '') ?>>&nbsp; No</label>
								    </div>
								  </div>
								  
								  <div class="form-group">
								    <label for="carrental_bank_account" class="col-sm-3 control-label">Bank account</label>
								    <div class="col-sm-9">
									    <input type="text" name="carrental-bank-account" id="carrental_bank_account" class="form-control" value="<?= (isset($payments['carrental-bank-account']) ? $payments['carrental-bank-account'] : '') ?>">
								    </div>
								  </div>
									
								  <!-- Submit //-->
								  <div class="form-group">
								  	<div class="col-sm-offset-3 col-sm-9">
								  		<button type="submit" name="save_settings" class="btn btn-warning">Confirm &amp; Save Settings</button>
								  	</div>
									</div>
									
								</div>
							</div>
							<!-- .row //-->
									
						</form>
					
					</div>
					<!-- .panel-body //-->
				</div>
				<!-- .panel .panel-default //-->
				
				
			</div>
		</div>
	</div>
	
</div>

<script type="text/javascript">
	$(function() {
		$('.paypalpayment').change(function(){
			if ($(this).val() == 'yes') {
				$('#paypal_options').show();
			} else {
				$('#paypal_options').hide();
			}
		});
	});
</script>