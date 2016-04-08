<div class="carrental-wrapper">
	
	<?php include CARRENTAL__PLUGIN_DIR . 'views/header.php'; ?>
	
	<div class="row">
	
		<div class="col-md-12 carrental-main-wrapper">
			<div class="carrental-main-content">
				
				<?php include CARRENTAL__PLUGIN_DIR . 'views/flash_msg.php'; ?>
				
				<div class="row">
					<div class="col-md-12">
						
						<?php if (isset($newsletter) && !empty($newsletter)) { ?>
						
							<table class="table table-striped" id="carrental-newsletter">
					      <thead>
					        <tr>
					          <th>Created</th>
					          <th>First name</th>
					          <th>Last name</th>
					          <th>E-mail</th>
					        </tr>
					      </thead>
					      <tbody>
								<?php foreach ($newsletter as $key => $val) { ?>
				      		<tr>
					          <td><?= (!empty($val->created) ? $val->created : '- Unknown -') ?></td>
					          <td><?= (!empty($val->first_name) ? $val->first_name : '- Unknown -') ?></td>
										<td><?= (!empty($val->last_name) ? $val->last_name : '- Unknown -') ?></td>
										<td><?= (!empty($val->email) ? $val->email : '- Unknown -') ?></td>
					        </tr>
				      	<?php } ?>
					    	</tbody>
					  	</table>
					  	
							
						<?php } else { ?>
							<div class="alert alert-info">
								<span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;
								<?= esc_html__( 'You do not have any User in Newsletter yet.', 'carrental' ); ?>
							</div>
						<?php } ?>
						
					</div>
				</div>
				
				
				
			</div>
		</div>
	</div>
	
</div>
