<form action="" method="get" class="form form-request form-vertical form-size-100" id="carrental_booking_form">
											
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
		
		<div class="control-group">
			<div class="control-field">
				<div class="columns-2 control-group">
					<div class="column">
						<div class="columns-2">
							<div class="column column-wider">
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

							<div class="column column-thiner">
								
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
					</div>

					<div class="column">
						<div class="columns-2">
							<div class="column column-wider">
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

							<div class="column column-thiner">
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
					</div>
				</div>
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-field align-right">
				<input type="hidden" name="page" value="carrental">
				<button type="submit" name="book_now" class="btn btn-primary" id="carrental_book_now"><?= CarRental::t('BOOK NOW') ?></button>
			</div>
		</div>
		
	</fieldset>
	
	<ul id="carrental_book_errors" style="margin:1em 2em;list-style-type:circle;color:tomato;"></ul>
</form>