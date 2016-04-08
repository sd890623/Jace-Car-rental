<?php
/**
 * The main template file
 *
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage CarRental
 * @since CarRental 2.0
 */

get_header(); ?>
	
	<section class="intro">
			
			<div>
					<?php $theme_options = unserialize(get_option('carrental_theme_options')); ?>
					<div class="slideshow-item booking" <?php if (isset($theme_options['picture_homepage']) && !empty($theme_options['picture_homepage'])) { ?>style="background-image:url('<?= htmlspecialchars($theme_options['picture_homepage']) ?>');"<?php } ?>>
						
						<div class="slideshow-item-wrap">						
							<div class="slideshow-item-content">
									
								<div class="tabs">
									<ul class="tabs-navigation">
										<li class="tabs-navigation-active">
											<a href="javascript:void(0);" data-tab-target="quick-book"><?= CarRental::t('QUICK BOOK') ?></a>
										</li>
										<li class="tabs-navigation-link">
											<a href="javascript:void(0);" data-tab-target="manage-booking"><?= CarRental::t('MANAGE BOOKING') ?></a>
										</li>
									</ul>

									<div class="tabs-content">
										
										<div data-tab-id="quick-book" class="tabs-content-tab tabs-content-tab-active">
											
											<?php carrental_get_booking_form(); ?>
											
										</div>
										<!-- .tabs-content-tab -->

										<div data-tab-id="manage-booking" class="tabs-content-tab">
											<form action="" method="post" class="form form-request form-vertical form-size-100">
												<fieldset>
													
													<div class="control-group">
														<div class="control-label">
															<label for="carrental_order_number"><?= CarRental::t('Order Number') ?>:</label>
														</div>
														<div class="control-field">
															<input type="text" name="id_order" id="carrental_order_number" class="control-input">
														</div>
													</div>
													
													<div class="control-group">
														<div class="control-label">
															<label for="carrental_order_email"><?= CarRental::t('Your E-mail') ?>:</label>
														</div>
														<div class="control-field">
															<input type="text" name="email" id="carrental_order_email" class="control-input">
														</div>
													</div>
													
													<div class="control-group">
														<div class="control-field align-right">
															<input type="hidden" name="page" value="carrental">
															<button type="submit" name="manage_booking" class="btn btn-primary"><?= CarRental::t('SHOW ORDER DETAILS') ?></button>	
														</div>
													</div>
													
												</fieldset>
											</form>
										</div>
										<!-- .tabs-content-tab -->

									</div>
									<!-- .tabs-content -->
								</div>
								<!-- .tabs -->					

							</div>
							<!-- .slideshow-item-content -->			
							
						</div>
						<!-- .slideshow-item-wrap -->				

					</div>


			</div>
			<!-- .container -->

		</section>
		<!-- .intro -->
	
	<hr>
	
	<?php $theme_options = unserialize(get_option('carrental_theme_options')); ?>
	<section class="content">	

		<div class="container">
						
			<div class="columns-3 main-links">
				
				<div class="column">
					
					<?php if (isset($theme_options['our_cars_page']) && !empty($theme_options['our_cars_page'])) { ?>
						<h2>
							<a href="<?= get_permalink($theme_options['our_cars_page']) ?>" title="" class="item">
								<span class="item-thumb">
									<span class="sprite-cars"></span>
								</span>
								<span class="item-content high">
									<?= CarRental::t('Our Cars') ?>
								</span>
							</a>
						</h2>
					<?php } ?>
					
				</div>
				<!-- .column -->

				<div class="column">
					
					<?php if (isset($theme_options['our_locations_page']) && !empty($theme_options['our_locations_page'])) { ?>
						<h2>
							<a href="<?= get_permalink($theme_options['our_locations_page']) ?>" title="" class="item">
								<span class="item-thumb">
									<span class="sprite-location"></span>
								</span>
								<span class="item-content additional">
									<?= CarRental::t('Our Locations') ?>
								</span>
							</a>
						</h2>
					<?php } ?>
				</div>
				<!-- .column -->

				<div class="column">
				
					<?php if (isset($theme_options['manage_booking_page']) && !empty($theme_options['manage_booking_page'])) { ?>
						<h2>
							<a href="<?= get_permalink($theme_options['manage_booking_page']) ?>" title="" class="item">
								<span class="item-thumb">
									<span class="sprite-manage-booking"></span>
								</span>
								<span class="item-content extra">
									<?= CarRental::t('Manage Booking') ?>
								</span>
							</a>
						</h2>
					<?php } ?>
				</div>
				<!-- .column -->

			</div>
			<!-- .columns-4 -->

			<div class="columns-3">
				
				<div class="column">
					
					<?php if ( is_active_sidebar( 'main-content-1' ) ) { ?>
						<div id="main-content-1">
							<?php dynamic_sidebar( 'main-content-1' ); ?>
						</div><!-- #secondary -->
					<?php } ?>
					
				</div>
				<!-- .column -->

				<div class="column">
					
					<?php if ( is_active_sidebar( 'main-content-2' ) ) { ?>
						<div id="main-content-2">
							<?php dynamic_sidebar( 'main-content-2' ); ?>
						</div><!-- #secondary -->
					<?php } ?>

				</div>
				<!-- .column -->

				<div class="column">
					
					<?php if ( is_active_sidebar( 'main-content-3' ) ) { ?>
						<div id="main-content-3">
							<?php dynamic_sidebar( 'main-content-3' ); ?>
						</div><!-- #secondary -->
					<?php } ?>

				</div>
				<!-- .column -->

			</div>
			<!-- .columns-3 -->

			<?php if ( is_active_sidebar( 'main-content-full-size' ) ) { ?>
				<div id="main-content-4">
					<?php dynamic_sidebar( 'main-content-full-size' ); ?>
				</div><!-- #secondary -->
			<?php } ?>
		</div>
		<!-- .container -->
		
	</section>
	<!-- .content -->		
		
<?php get_footer(); ?>