<?php
/**
 * Template Name: Order History
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
										'post_type' => 'orders', 
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
											),
											array(
												'key' => 'paid',
												'value' => 'Paid in Full'
											)
										),
									);
								} else {
									$args = array(
										'post_type' => 'orders', 
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
											),
											array(
												'key' => 'paid',
												'value' => 'Paid in Full'
											)
										),
									);
								}

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
										$sub_total = get_field('sub_total');
										if ( ! $credit_applied = get_field('credit_applied') ) {
											$credit_applied = 'N/A';
										} else {
											$credit_applied = '$' . number_format($credit_applied, 2);
										}
										if ( ! $coupon_percent = get_field('coupon_percent') ) {
											$coupon_percent = 'N/A';
										} else {
											$coupon_percent = $coupon_percent . '%';
										}
										$total_charge = get_field('total_charge');
										?>

										<div class="order-details-wrap">
											<table style="margin-top: 30px;" class="widefat fixed" cellspacing="0">
												<thead>
													<tr class="alternate">
														<th>PO Number</th>
														<th>Date</th>
														<th>Sub Total</th>
														<th>Credit Applied</th>
														<th>Coupon Percent</th>
														<th>Total Charge</th>
														<th>Order Email</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?php echo 'GSA-Order-' . $order_id; ?></td>
														<td><?php echo $date; ?></td>
														<td><?php echo $sub_total; ?></td>
														<td><?php echo $credit_applied; ?></td>
														<td><?php echo $coupon_percent; ?></td>
														<td><?php echo $total_charge; ?></td>
														<td><a class="gs-button gs-resend-order-email">Resend</a></td>
													</tr>
												</tbody>
											</table>

											<div class="products-ordred" style="margin-top: 10px;">

												<table class="widefat fixed" cellspacing="0">
													<thead>
														<tr class="alternate">
															<th>Product</th>
															<th>Color</th>
															<th>Quantity</th>
															<th>Unit Cost</th>
															<th>Total Cost</th>
														</tr>
													</thead>
													<tbody>

														<?php $entries = get_field('product_entries'); 

														foreach( $entries as $entry ) {
															$product_name = $entry['product_name'];
															$product_id = $entry['product_id'];
															$product_color = $entry['product_color'];
															$product_quantity = $entry['product_quantity'];
															$unit_cost = $entry['unit_cost'];
															$cost_total = $entry['cost_total'];
															$cost_actual = intval(str_replace(array('$',','), '', $cost_total));
															$category_array = get_the_category($product_id);
															$cat_name = $category_array[0]->name;
															$cat_id = $category_array[0]->term_id;
															$payment = $cost_actual;
															$total_payment = ( $total_payment + $payment ); ?>

															<tr class="product-table-items">	
																<td><?php echo $product_name; ?></td>
																<td><?php echo $product_color; ?></td>
																<td><?php echo $product_quantity; ?></td>
																<td><?php echo $unit_cost; ?></td>
																<td><?php echo $cost_total; ?></td>

															</tr>

														<?php } ?>

													</tbody>

												</table>

											</div>

										</div>

									<?php } 

								} else { ?>
									<div class="gsa-no-orders-info">No orders for this period.</div>
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
