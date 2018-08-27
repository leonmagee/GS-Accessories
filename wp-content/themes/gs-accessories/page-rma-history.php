<?php
/**
 * Template Name: RMA History
 *
 */


if ( isset($_POST['change-month-year-admin'])) {

	$current_month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_SPECIAL_CHARS);

	$current_year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$monthName = DateTime::createFromFormat('m', $current_month)->format('F');

	$display_date = $monthName . ' ' . $current_year;
}
else {
	$current_month = intval(date( 'n' ));
	$current_year = intval(date( 'Y' ));
	$display_date = 'for ' . date('F Y');
}

$gsa_date_rage_query = false;

if ( isset($_POST['change-date-range-admin'])) {
	$gsa_date_rage_query = true;
	$datepicker_start = filter_input(INPUT_POST, 'datepicker-start', FILTER_SANITIZE_SPECIAL_CHARS);
	$datepicker_end = filter_input(INPUT_POST, 'datepicker-end', FILTER_SANITIZE_SPECIAL_CHARS);
	if ( $datepicker_start && $datepicker_end ) {
		$display_date = 'for ' . $datepicker_start . ' - ' . $datepicker_end;
	} elseif( $datepicker_start ) {
		$display_date = 'for ' . $datepicker_start . ' - present';
	} elseif( $datepicker_end ) {
		$display_date = 'through ' . $datepicker_end;
	}
}

get_header(); ?>

<div id="primary" class="content-area">
	<div class="max-width-wrap">
		<main id="main" class="site-main">


			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">

					<div class="wrap gsa-sales-admin-page">

						<div class="sales-report-wrap">

							<div class="forms-wrap">

								<div class="month-choice form-item-group">

									<div class="change-date-form">
										<?php 

										$months = array('January','February','March','April','May','June','July','August','September','October','November','December'); 

										$years = array();
										$current_year = intval(date('Y'));
										for ( $i = 2018; $i <= $current_year; $i++ ) {
											$years[] = $i;
										} ?>

										<form class="change-date-form-inner" method="POST" action="#">

											<h5>Sort by Month, Year</h5>

											<div class="change-date-inner-new">
												<input type="hidden" name="change-month-year-admin" />
												<div>
													<select name="month">
														<?php foreach( $months as $key => $month ) {
															$month_val = ( $key + 1); 
															if ( $month_val === intval($current_month) ) {
																$selected = 'selected="true"';
															} else {
																$selected = '';
															}
															?>
															<option <?php echo $selected; ?> value="<?php echo $month_val; ?>"><?php echo $month; ?></option>
														<?php } ?>
													</select>
												</div>
												<div>
													<select name="year">
														<?php foreach( $years as $year ) { ?>
															<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
														<?php } ?>
													</select>
												</div>


												<div>
													<button type="submit" class="button button-primary">Update</button>
												</div>
											</div>
										</form>
									</div>
								</div>

								<div class="range-choice form-item-group">
									<h5>Sort by Date Range</h5>
									<form method="POST">
										<input type="hidden" name="change-date-range-admin" />
										<div class="date-range-input">
											<label>Starting Date:</label>
											<input type="text" name="datepicker-start" id="datepicker_start" />
										</div>
										<div class="date-range-input">
											<label>Ending Date:</label>
											<input type="text" name="datepicker-end" id="datepicker_end" />
										</div>

										<div>
											<button type="submit" class="button button-primary">Update</button>
										</div>

									</form>
								</div>

							</div>

							<div class="completed-orders-wrap">

								<?php 

								if ( $gsa_date_rage_query ) {

									$args = array(
										'post_type' => 'rmas', 
										'posts_per_page' => -1,
										'date_query' => array(
											array(
												'before'  => $datepicker_end,
												'after' => $datepicker_start,
											),
										),
										'meta_query' => array(
											array(
												'key' => 'user_id',
												'value' => LV_LOGGED_IN_ID
											)
										),
									);
								} else {
									$args = array(
										'post_type' => 'rmas', 
										'posts_per_page' => -1,
										'date_query' => array(
											array(
												'year'  => $current_year,
												'month' => $current_month,
											),
										),
										'meta_query' => array(
											array(
												'key' => 'user_id',
												'value' => LV_LOGGED_IN_ID
											)
										),
									);
								}

								$icon_url = get_site_url() . '/wp-admin/images/loading.gif';

								$order_query = new WP_Query($args);

								$total_payment = 0;
								if ( $order_query->have_posts() ) {
									while( $order_query->have_posts() ) {
										global $post;
										$order_query->the_post(); 
										$date = get_the_date();
										$purchaser_id = get_field('user_id');
										// $paid = get_field('paid');
										// if ( $paid === 'Paid in Full' ) {
										// 	$status = 'Paid';
										// } else {
										// 	$status = 'Pending';
										// }
										$order_id = $post->ID;
										if ( ! $rma_status = get_field('rma_status') ) {
											$rma_status = 'Pending';
										}

										
										?>

										<div class="order-details-wrap">
											<div class="callout success">Email Sent!</div>
											<div class="callout alert">Email Not Sent!</div>
											<table style="margin-top: 30px;" class="widefat fixed" cellspacing="0">
												<thead>
													<tr class="alternate">
														<th>RMA Number</th>
														<th>Date</th>
														<th>RMA Status</th>
														<th>RMA Email</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?php echo 'GSA-RMA-' . $order_id; ?></td>
														<td><?php echo $date; ?></td>
														<td><?php echo $rma_status; ?></td>
														<td>
															<?php if ( $rma_status === 'Completed' ) { ?>
															<a class="gs-button gs-resend-rma-email">Resend</a><img class="gsa_spinner" src="<?php echo $icon_url; ?>" />
															<input type="hidden" name="gsa-hidden-post-id" value="<?php echo $order_id; ?>" />
															<input type="hidden" name="gsa-email-address-admin" value="<?php echo LV_LOGGED_IN_EMAIL; ?>" />
														<?php } ?>
														</td>
													</tr>
												</tbody>
											</table>

											<div class="products-ordred" style="margin-top: 10px;">

												<?php 

												$return_items = array(
													get_field('return_item_1'),
													get_field('return_item_2'),
													get_field('return_item_3'),
													get_field('return_item_4'),
													get_field('return_item_5'),
												);

												$counter = 0;
												foreach( $return_items as $entry ) {
													$counter++;
													$quantity = $entry['quantity'];
													$item_name = $entry['item_name'];
													$unit_price = $entry['unit_price'];
													if ( $unit_price ) {
														$unit_price = '$' . number_format($unit_price, 2);
													}
													$serial_number = $entry['serial_number'];
													$po_number = $entry['po_number'];
													$date_purchased = $entry['date_purchased'];
													$desc = $entry['return_problem_description'];

													if ( $quantity || $item_name || $unit_price || $serial_number || $po_number || $date_purchased || $desc ) {

														?>

														<div class="table-push-down-wrap">
															<h5>Return Item <?php echo $counter; ?></h5>
															<table class="widefat fixed" cellspacing="0">
																<thead>
																	<tr class="alternate">
																		<th>Quantity</th>
																		<th>Item Name</th>
																		<th>Unit Price</th>
																		<th>IMEI or S/N</th>
																		<th>PO Number</th>
																		<th>Date Purchased</th>
																	</tr>
																</thead>
																<tbody>

																	<tr class="product-table-items">	
																		<td><?php echo $quantity; ?></td>
																		<td><?php echo $item_name; ?></td>
																		<td><?php echo $unit_price; ?></td>
																		<td><?php echo $serial_number; ?></td>
																		<td><?php echo $po_number; ?></td>
																		<td><?php echo $date_purchased; ?></td>
																	</tr>
																	<tr class="product-table-items">	
																		<td style="background-color: #FFF" colspan="6"><?php echo $desc; ?></td>
																	</tr>

																</tbody>

															</table>

														</div>

														<?php 
													}
												} ?>

											</div>

										</div>

									<?php } 

								} else { ?>
									<div class="gsa-no-orders-info">No RMAs for this period.</div>
								<?php }
								wp_reset_postdata();
								?>

							</div>

						</div>

					</div>

				</div><!-- .entry-content -->

			</article>

		</main><!-- #main -->
	</div>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
