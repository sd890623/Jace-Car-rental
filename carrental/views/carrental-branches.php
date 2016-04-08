<div class="container-fluid">
	
	<?php include CARRENTAL__PLUGIN_DIR . 'views/header.php'; ?>
	
	<div class="row">
	
		<div class="col-md-12 carrental-main-wrapper">
			<div class="carrental-main-content">
				
				<?php include CARRENTAL__PLUGIN_DIR . 'views/flash_msg.php'; ?>
				  		
				<div class="row">
					<div class="col-md-12">
						<?php if ($edit == true) { ?>
							<h3>Edit Branch no. <?= $detail->id_branch ?></h3>
						<?php } else { ?>
							<?php if (isset($_GET['deleted'])) { ?>
								<a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-branches')); ?>" class="btn btn-default" style="float:right;">Show normal</a>
							<?php } else { ?>
								<a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-branches')); ?>&amp;deleted" class="btn btn-default" style="float:right;">Show deleted</a>
							<?php } ?>
							
							<a href="javascript:void(0);" class="btn btn-success" id="carrental-branches-add-button"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Add new branch</a>
						<?php } ?>
						
						<div id="<?= (($edit == true) ? 'carrental-branches-edit-form' : 'carrental-branches-add-form') ?>" class="carrental-add-form">
							<form action="" method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
								<div class="row">
									<div class="col-md-11">
										
										<div class="alert alert-info">
											<p><span class="glyphicon glyphicon-share-alt"></span>&nbsp;&nbsp;Whichever field is left blank will not be used in branch detail.</p>
										</div>

										<!-- Name //-->
									  <div class="form-group">
									    <label for="carrental-name" class="col-sm-3 control-label">Name</label>
									    <div class="col-sm-9">
									    	<input type="text" name="name" class="form-control" id="carrental-name" value="<?= (($edit == true) ? $detail->name : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Internal ID //-->
									  <div class="form-group">
									    <label for="carrental-bid" class="col-sm-3 control-label">Internal ID</label>
									    <div class="col-sm-9">
									    	<input type="text" name="bid" class="form-control" id="carrental-bid" value="<?= (($edit == true) ? $detail->bid : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Country //-->
									  <div class="form-group">
									    <label for="carrental-country" class="col-sm-3 control-label">Country</label>
									    <div class="col-sm-9">
									    	<select name="country" class="form-control" id="carrental-country">
										    	<option value="">- select -</option>
										    	<?php $countries = CarRental_Admin::get_country_list(); ?>
										    	<?php foreach ($countries as $key => $val) { ?>
										    		<option value="<?= $key ?>" <?= (($edit == true && $key == $detail->country) ? 'selected="selected"' : '') ?>><?= $val ?></option>
										    	<?php } ?>
									    	</select>
									    </div>
									  </div>
									  
									  <!-- State/Province //-->
									  <div class="form-group">
									    <label for="carrental-state" class="col-sm-3 control-label">State / Province</label>
									    <div class="col-sm-9">
									    	<input type="text" name="state" class="form-control" id="carrental-state" value="<?= (($edit == true) ? $detail->state : '') ?>">
									    </div>
									  </div>
									  
									  <!-- City //-->
									  <div class="form-group">
									    <label for="carrental-city" class="col-sm-3 control-label">City</label>
									    <div class="col-sm-9">
									    	<input type="text" name="city" class="form-control" id="carrental-city" placeholder="Prague, London, Los Angeles, ..." value="<?= (($edit == true) ? $detail->city : '') ?>">
									    </div>
									  </div>
									  
									  <!-- ZIP //-->
									  <div class="form-group">
									    <label for="carrental-zip" class="col-sm-3 control-label">ZIP Code</label>
									    <div class="col-sm-9">
									    	<input type="text" name="zip" class="form-control" id="carrental-zip" value="<?= (($edit == true) ? $detail->zip : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Street //-->
									  <div class="form-group">
									    <label for="carrental-street" class="col-sm-3 control-label">Street</label>
									    <div class="col-sm-9">
									    	<input type="text" name="street" class="form-control" id="carrental-street" value="<?= (($edit == true) ? $detail->street : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Contact e-mail //-->
									  <div class="form-group">
									    <label for="carrental-email" class="col-sm-3 control-label">Contact e-mail</label>
									    <div class="col-sm-9">
									    	<input type="text" name="email" class="form-control" id="carrental-email" value="<?= (($edit == true) ? $detail->email : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Contact phone //-->
									  <div class="form-group">
									    <label for="carrental-phone" class="col-sm-3 control-label">Contact phone</label>
									    <div class="col-sm-9">
									    	<input type="text" name="phone" class="form-control" id="carrental-phone" value="<?= (($edit == true) ? $detail->phone : '') ?>">
									    </div>
									  </div>
									  
									  <!-- Description //-->
									  <div class="form-group">
									    <label for="carrental-description" class="col-sm-3 control-label">Description</label>
									    <div class="col-sm-9">
									    	<textarea class="form-control" name="description" id="carrental-description" rows="3"><?= (($edit == true) ? $detail->description : '') ?></textarea>
									    </div>
									  </div>
									  	
									  <!-- Business hours //-->
									  <div class="form-group">
									    <label for="carrental-description" class="col-sm-3 control-label">Business hours</label>
									    <div class="col-sm-9">
									    	<table class="table carrental-business-hours">
									    		<?php foreach (array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') as $key => $val) { ?>
									    			<tr>
										    			<td><?= $val ?></td>
										    			<td>&nbsp;from&nbsp;</td>
															<td><input type="text" name="hours[from][<?= $key ?>]" class="form-control" size="2" placeholder="HH:MM" value="<?= (($edit == true) ? $detail->hours[$key+1]['hours_from'] : '') ?>"></td>
										    			<td>&nbsp;to&nbsp;</td>
															<td><input type="text" name="hours[to][<?= $key ?>]" class="form-control" size="2" placeholder="HH:MM" value="<?= (($edit == true) ? $detail->hours[$key+1]['hours_to'] : '') ?>"></td>
										    		</tr>
									    		<?php } ?>
									    	</table>
									    	<p class="help-block">* Leave blank if branch closed.</p>
									    </div>
									  </div>
									  
									  <!-- Picture of branch //-->
									  <div class="form-group">
									    <label for="carrental-picture" class="col-sm-3 control-label">Picture of item or service</label>
									    <div class="col-sm-9">
									    	<?php if ($edit == true) { ?>
									    		<div class="panel panel-info">
													  <div class="panel-heading">Current picture</div>
													  <div class="panel-body">
													    <p><img src="<?= $detail->picture ?>" height="80"></p>
													  </div>
													</div>
													<p><strong>Or you can upload new picture for Branch:</strong></p>
									  		<?php } ?>
									    	<input type="file" name="picture" id="carrental-picture">
									    	<p class="help-block">Insert picture of the item or service, 400x400px.</p>
									    	<p><strong>Or you can delete current picture for Branch:</strong></p>
									    	<label><input type="checkbox" class="input-control" name="delete_picture" value="1">&nbsp;&nbsp;Delete picture</label>
									    </div>
									  </div>
									  
									  <!-- Active //-->
									  <div class="form-group">
									    <label for="carrental-active" class="col-sm-3 control-label">List branch</label>
									    <div class="col-sm-9">
									    	<label class="radio-inline">
												  <input type="radio" name="active" id="carrental-active" value="1" <?= (($edit == true && $detail->active == 1) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;Yes
												</label>
												<label class="radio-inline">
												  <input type="radio" name="active" id="carrental-active" value="0" <?= (($edit == true && $detail->active == 0) ? 'checked="checked"' : '') ?>>&nbsp;&nbsp;No
												</label>
												<p class="help-block">This will make the branch active or inactive on the contact page.</p>
									    </div>
									  </div>
									  
									  <!-- Submit //-->
									  <div class="form-group">
									  	<div class="col-sm-offset-3 col-sm-9">
									  		<?php if ($edit == true) { ?>
									  			<input type="hidden" name="id_branch" value="<?= $detail->id_branch ?>">
									  			<input type="hidden" name="current_picture" value="<?= $detail->picture ?>">
									  			<button type="submit" class="btn btn-warning" name="add_branch"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Confirm &amp; Save</button>
									  		<?php } else { ?>
									  			<button type="submit" class="btn btn-warning" name="add_branch"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Confirm &amp; Add</button>
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
						
						<?php if (isset($branches) && !empty($branches)) { ?>
						
							<table class="table table-striped" id="carrental-branches">
					      <thead>
					        <tr>
					          <th>#</th>
					          <th>Image</th>
					          <th>Name</th>
					          <th>Address</th>
					          <th>Description</th>
					          <th>Business hours</th>
					          <th>Action</th>
					        </tr>
					      </thead>
					      <tbody>
					      	<?php foreach ($branches as $key => $val) { ?>
					      		<tr>
						          <td>
												<input type="checkbox" class="input-control batch_processing" name="batch[]" value="<?= $val->id_branch ?>">&nbsp;
												<abbr title="Created: <?= $val->created ?>

<?= (!empty($val->updated) ? 'Updated: ' . $val->updated : '') ?>"><?= $val->id_branch ?></abbr>
											</td>
						          <td><img src="<?= $val->picture ?>" height="120"></td>
						          <td><strong><?= (!empty($val->name) ? $val->name : '- Unknown -') ?></strong><?php if ($val->active == 0) { ?><br><em class="branch-not-listed">(not listed)</em><?php } ?></td>
						          <td>
						          	<?= (!empty($val->city) ? $val->city . '<br>' : '') ?>
												<?= (!empty($val->street) ? $val->street . '<br>' : '') ?>
												<?= (!empty($val->zip) ? $val->zip . '<br>' : '') ?>
												<?= (!empty($val->country) ? $countries[$val->country] . '<br>' : '') ?>
												<?= (!empty($val->state) ? $val->state : '') ?>
												<br>
												<?= (!empty($val->email) ? $val->email . '<br>' : '') ?>
												<?= (!empty($val->phone) ? $val->phone . '<br>' : '') ?>
											</td>
											<td><p style="max-width:200px;"><?= $val->description ?></p></td>
											<td>
												<table class="table">
													<?php if (isset($val->hours) && !empty($val->hours)) { ?>
														<?php foreach ($val->hours as $kD => $vD) { ?>
															<tr>
																<td><?= CarRental_Admin::get_day_name($vD->day) ?></td>
																<td><?= substr($vD->hours_from, 0, 5) ?></td>
																<td><?= substr($vD->hours_to, 0, 5) ?></td>
															</tr>
														<?php } ?>
													<?php } ?>
												</table>
											</td>
						          <td>
												<form action="" method="post" class="form" role="form">
													<div class="form-group">
														<a href="<?= esc_url(CarRental_Admin::get_page_url('carrental-branches')); ?>&amp;edit=<?= $val->id_branch ?>" class="btn btn-primary btn-block">Modify</a>
													</div>
												</form>
												<form action="" method="post" class="form" role="form">
													<div class="form-group">
														<input type="hidden" name="id_branch" value="<?= $val->id_branch ?>">
														<button name="copy_branch" class="btn btn-warning btn-block">Copy</button>
													</div>
												</form>
												<?php if (isset($_GET['deleted'])) { ?>
													<form action="" method="post" class="form" role="form" onsubmit="return confirm('<?= __('Do you really want to restore this Branch?', 'carrental') ?>');">
														<div class="form-group">
															<input type="hidden" name="id_branch" value="<?= $val->id_branch ?>">
															<button name="restore_branch" class="btn btn-success btn-block">Restore</button>
														</div>
													</form>
												<?php } else { ?>
													<form action="" method="post" class="form" role="form" onsubmit="return confirm('<?= __('Do you really want to delete this Branch?', 'carrental') ?>');">
														<div class="form-group">
															<input type="hidden" name="id_branch" value="<?= $val->id_branch ?>">
															<button name="delete_branch" class="btn btn-danger btn-block">Delete</button>
														</div>
													</form>
												<?php } ?>
											</td>
						        </tr>
						        
					      	<?php } ?>
					      </tbody>
					    </table>
					    
					    <h4>Batch action on selected items</h4>
					    
					    <form action="" method="post" class="form" role="form" onsubmit="if (jQuery('[name=batch_processing_values]').val() == '') { alert('No Branch is selected to copy.'); return false }; return confirm('<?= __('Do you really want to copy selected Branches?', 'carrental') ?>');">
								<div class="form-group">
									<input type="hidden" name="batch_processing_values" value="">
									<button name="batch_copy_branch" class="btn btn-warning">Copy <span class="batch_processing_count"></span>selected Branches</button>
								</div>
							</form>
							
					    <form action="" method="post" class="form" role="form" onsubmit="if (jQuery('[name=batch_processing_values]').val() == '') { alert('No Branch is selected to delete.'); return false }; return confirm('<?= __('Do you really want to delete selected Branches?', 'carrental') ?>');">
								<div class="form-group">
									<input type="hidden" name="batch_processing_values" value="">
									<button name="batch_delete_branch" class="btn btn-danger">Delete <span class="batch_processing_count"></span>selected Branches</button>
								</div>
							</form>
							
						<?php } else { ?>
							<div class="alert alert-info">
								<span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;
								<?= esc_html__( 'You do not have any Branches created yet, please create one clicking on "Add New Branch".', 'carrental' ); ?>
							</div>
						<?php } ?>
						
					</div>
				</div>
				
				
				
			</div>
		</div>
	</div>
	
</div>
