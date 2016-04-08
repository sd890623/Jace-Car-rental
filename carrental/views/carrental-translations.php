<div class="carrental-wrapper">
	
	<?php include CARRENTAL__PLUGIN_DIR . 'views/header.php'; ?>
	
	<div class="row">
		<div class="col-md-12 carrental-main-wrapper">
			<div class="carrental-main-content">
				
				<?php include CARRENTAL__PLUGIN_DIR . 'views/flash_msg.php'; ?>
				
				<div class="row">
					<div class="col-md-11">
						
						<?php $current_lang = (isset($_GET['language']) ? $_GET['language'] : NULL); ?>
						<?php $available_languages = unserialize(get_option('carrental_available_languages')); ?>
						
						<ul class="nav nav-pills">
							<?php if ($available_languages && !empty($available_languages)) { ?>
								<?php foreach ($available_languages as $key => $val) { ?>
						  		<li <?php if ($current_lang == $key) { ?>class="active"<?php } ?>><a href="<?= CarRental_Admin::get_page_url('carrental-translations') ?>&amp;language=<?= $key ?>"><?= $val['lang'] ?> (<?= strtoupper($val['country-www']) ?>)</a></li>
								<?php } ?>
						  <?php } ?>
						  
						  <li <?php if ($current_lang == 'en_GB') { ?>class="active"<?php } ?>><a href="<?= CarRental_Admin::get_page_url('carrental-translations') ?>&amp;language=en_GB">English (GB)</a></li>
						  <li><a href="javascript:void(0);" id="carrental-language-add-button"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Add new language</a></li>
						  <li><a href="javascript:void(0);" id="carrental-language-primary-button"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Set primary language</a></li>
						
						</ul>
						
						<div id="carrental-language-add-form" class="carrental-add-form">
							<form role="form" action="" method="post">
								<div class="row">
									<div class="col-md-6">
									  <div class="form-group">
									    <label for="selectLanguage">Language</label>
									    <select class="form-control" name="language" id="selectLanguage">
									    	<option value="0">- select -</option>
									    	<?php foreach ($languages as $key => $val) { ?>
									    		<option value="<?= $key ?>"><?= $val['lang'] ?> (<?= strtoupper($val['country-www']) ?>)</option>
									    	<?php } ?>
									    </select>
									  </div>
									  <button type="submit" class="btn btn-warning" name="add_language"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Add new language</button>
									</div>
								</div>
							</form>
						</div>
						
						<div id="carrental-language-primary-form" class="carrental-add-form">
							<form role="form" action="" method="post">
								<div class="row">
									<div class="col-md-6">
									  <div class="form-group">
									    <label for="selectLanguage">Language</label>
									    <?php
									    	$primary_language = 'en_GB';
												$user_set_language = get_option('carrental_primary_language');
												if ($user_set_language && !empty($user_set_language)) {
													$primary_language = $user_set_language;
												}
											?>
											<p>Current primary language is: <strong><?= $languages[$primary_language]['lang'] ?> (<?= strtoupper($languages[$primary_language]['country-www']) ?>)</strong></p>
									    <select class="form-control" name="language" id="selectLanguage">
									    	<option value="en_GB" <?php if ($primary_language == 'en_GB') { ?>selected<?php } ?>>English (UK)</option>
									    	<?php if ($available_languages && !empty($available_languages)) { ?>
													<?php foreach ($available_languages as $key => $val) { ?>
											  		<option value="<?= $key ?>" <?php if ($primary_language == $key) { ?>selected<?php } ?>><?= $val['lang'] ?> (<?= strtoupper($val['country-www']) ?>)</option>
													<?php } ?>
											  <?php } ?>
									    </select>
									  </div>
									  <button type="submit" class="btn btn-warning" name="primary_language"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Set language as primary</button>
									</div>
								</div>
							</form>
						</div>
						
						<hr>
						
						<?php if (!empty($current_lang)) { ?>
							
							<!-- THEME //-->
							<div class="panel panel-warning">
								<div class="panel-heading"><h4><a href="javascript:void(0);" class="carrental_translations_theme_toggle"><span>▼</span>&nbsp;&nbsp;Theme translations</a></h4></div>
							  <div class="panel-body carrental_translations_theme">
							  	
							    <form role="form" action="" method="post">
									  
								  	<?php if ($translations_theme && !empty($translations_theme)) { ?>
									  	<?php foreach ($translations_theme as $key => $val) { ?>
										  	<div class="form-group">
										  		<div class="row">
										  			<div class="col-md-6">
										  				<?= $key ?>
										  			</div>
										  			<div class="col-md-6">
										  				<input type="hidden" class="form-control" name="translation[key][]" value="<?= htmlspecialchars($key) ?>">
										  				<input type="text" class="form-control" name="translation[val][]" value="<?= htmlspecialchars($val) ?>">
										  			</div>
										  		</div>
										  	</div>
									  	<?php } ?>
								  	<?php } else { ?>
								  		Sorry, there are no translatable strings in the theme.
								  	<?php } ?>
								  	
									  <input type="hidden" name="language" value="<?= $current_lang ?>">
									  <button type="submit" class="btn btn-warning" name="language_save_theme_translations"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Confirm &amp; Save</button>
									</form>
								</div>
							</div>
							
							<!-- E-MAIL //-->
							<div class="panel panel-warning">	
								<div class="panel-heading"><h4><a href="javascript:void(0);" class="carrental_translations_email_customers_toggle"><span>▼</span>&nbsp;&nbsp;E-mail for customers</a></h4></div>
							  <div class="panel-body carrental_translations_email_customers">
							  	
							  	<?php $email_body = get_option('carrental_reservation_email_' . $current_lang); ?>
							  	
							    <form role="form" action="" method="post">
									  <div class="form-group">
									    <label for="reservation_email">Reservation e-mail</label>
									    <textarea class="form-control" rows="20" id="reservation_email" name="reservation_email">
<?php if (!empty($email_body)) { ?>
<?= $email_body ?>
<?php } else { ?>
Dear [CustomerName],

thank you for your reservation. Here are your reservation details:
[ReservationDetails]
[ReservationNumber]

You can return to your reservation summary page anytime by going to this link:
[ReservationLink]

We are also sending this information to the email address you have provided.

If you would like to change the reservation details, you can do so by calling our office at:
+123 456 789 or by email example@example.org

[ReservationLinkStart]Click here[ReservationLinkEnd] to print your reservation - takes them to reservation summary print out.

Thank you for your business!
<?php } ?>
									    </textarea>
									  </div>
									  <div class="form-group">
									  	<p><strong>Available variables</strong></p>
									  	<ul style="margin-left:20px;list-style-type:circle;">
									  		<li><strong>[CustomerName]</strong> -> John Doe, Phil Smith, ...</li>
									  		<li><strong>[ReservationDetails]</strong> -> Dates, Address, Selected Car, Price</li>
									  		<li><strong>[ReservationNumber]</strong> -> #123456</li>
									  		<li><strong>[ReservationLink]</strong> -> http://example.org/reservation/123456</li>
									  		<li><strong>[ReservationLinkStart]</strong>Any text<strong>[ReservationLinkEnd]</strong></li>
									  	</ul>
									  </div>
									  <p>*Do not change this page unless you know what you are doing.</p>
									  <input type="hidden" name="language" value="<?= $current_lang ?>">
									  <button type="submit" class="btn btn-warning" name="language_save_email"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Confirm &amp; Save</button>
									</form>
							  </div>
							</div>
							
							
							<!-- TERMS and CONDITIONS //-->
							<div class="panel panel-warning">	
								<div class="panel-heading"><h4><a href="javascript:void(0);" class="carrental_translations_terms_toggle"><span>▼</span>&nbsp;&nbsp;Terms &amp; Conditions</a></h4></div>
							  <div class="panel-body carrental_translations_terms">
							  	
							  	<?php $terms_body = get_option('carrental_terms_conditions_' . $current_lang); ?>
							  	
							    <form role="form" action="" method="post">
									  <div class="form-group">
									    <label for="terms_conditions">Terms &amp; Conditions</label>
									    <textarea class="form-control" rows="20" id="terms_conditions" name="terms_conditions">
<?php if (!empty($terms_body)) { ?>
<?= $terms_body ?>
<?php } else { ?>
Terms and Conditions

...
<?php } ?>
									    </textarea>
									  </div>
									  
									  <p>*Do not change this page unless you know what you are doing.</p>
									  <input type="hidden" name="language" value="<?= $current_lang ?>">
									  <button type="submit" class="btn btn-warning" name="language_save_terms"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Confirm &amp; Save</button>
									</form>
							  </div>
							</div>
							
							<?php
					    	$primary_language = 'en_GB';
								$user_set_language = get_option('carrental_primary_language');
								if ($user_set_language && !empty($user_set_language)) {
									$primary_language = $user_set_language;
								}
							?>
							<?php if ($current_lang != 'en_GB' && $primary_language != $current_lang) { ?>
								<form action="" method="post" class="form" role="form" onsubmit="return confirm('<?= __('Do you really want to disable this language?', 'carrental') ?>');">
									<input type="hidden" name="language" value="<?= $current_lang ?>">
									<button class="btn btn-danger" name="disable_language"><span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Disable this language</button>
									<p class="help-block">
										* Translation will not be deleted. All translations will reappear after you reenable the same language by clicking 'Add new language'
									</p>
								</form>
								
							<?php } else { ?>
								<p class="help-block">
									* This language cannot be disabled. It's set as a primary or is it default language.
								</p>
							<?php } ?>
								
							<?php if ($current_lang != 'en_GB' && $primary_language != $current_lang) { ?>
								<form action="" method="post" class="form" role="form" onsubmit="return confirm('<?= __('Do you really want to deactivate this language?', 'carrental') ?>');">
									<input type="hidden" name="language" value="<?= $current_lang ?>">
									<?php if ((isset($available_languages[$current_lang]['active']) && $available_languages[$current_lang]['active']) || !isset($available_languages[$current_lang]['active'])) { ?>
										<button class="btn btn-danger" name="deactivate_language"><span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Deactivate this language</button>
									<?php } else { ?>
										<button class="btn btn-success" name="activate_language"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Activate this language</button>
									<?php } ?>
									<p class="help-block">
										* Disable language on theme.
									</p>
								</form>
								
							<?php } else { ?>
								<p class="help-block">
									* This language cannot be deactivated. It's set as a primary or is it default language.
								</p>
							<?php } ?>
									
						<?php } else { ?>
							<p>
								Please, select language to edit or create new language.
							</p>
						<?php } ?>
						
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
</div>