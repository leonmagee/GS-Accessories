<?php
/**
 * Template Name: Agent Admin Template
 *
 * @todo also query by month
 */
restricted_page();

if ( isset($_GET['data_month'])) {
	$current_month = intval($_GET['data_month']);
	$current_year = intval($_GET['data_year']);
	$monthName = DateTime::createFromFormat('m', $current_month)->format('F');
	$display_date = $monthName . ' ' . $current_year;

} else {
	$current_month = intval(date( 'n' ));
	$current_year = intval(date( 'Y' ));
	$display_date = date('F Y');
}

get_header(); ?>

<div id="primary" class="content-area">


	<div class="max-width-wrap">


		<main id="main" class="site-main">



			<div class="grid-x grid-padding-x">

				<div class="cell large-9">

					<header class="entry-header">
						<h1 class="entry-title">Commission Data - <?php echo $display_date; ?></h1>
					</header>

					<div class="month-info">
						<div class="change-date-form">
							<div class="agent-button-wrap">
								<a class="toggle gs-button">Change Date</a>
							</div>

							<?php 

							$months = array('January','February','March','April','May','June','July','August','September','October','November','December'); 

							$years = array();
							$current_year = intval(date('Y'));
							for ( $i = 2018; $i <= $current_year; $i++ ) {
								$years[] = $i;
							} ?>

							<form class="change-date-form-inner" method="POST" action="#">
								<input type="hidden" name="change-month-year" />
								<select name="month">
									<?php foreach( $months as $key => $month ) { 
										$month_val = ( $key + 1); ?>
										<option value="<?php echo $month_val; ?>"><?php echo $month; ?></option>
									<?php } ?>
								</select>

								<select name="year">
									<?php foreach( $years as $year ) { ?>
										<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
									<?php } ?>
								</select>

								<button type="submit" class="gs-button">Update</button>
							</form>
						</div>
					</div>

					<?php

					$current_user_id = 'user_' . get_current_user_id();
					$category_payment_values = get_field('agent_percent', $current_user_id);
					$cat_percent_array = array();

					foreach( $category_payment_values as $item ) {
						$cat_percent_array[$item['category']] = intval($item['percent']);
					} ?>

					<div class="completed-orders-wrap">
						<?php 

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
									'key' => 'agent_id',
									'value' => LV_LOGGED_IN_ID, 
								),
								array(
									'key' => 'paid',
									'value' => 'Paid in Full'
								)
							),
						);

						$order_query = new WP_Query($args);

						$total_payment = 0;
						if ( $order_query->have_posts() ) {
							while( $order_query->have_posts() ) {

								$order_query->the_post(); 
								$date = get_the_date();
								$purchaser_id = get_field('user_id');
								$company = get_field('company', 'user_' . $purchaser_id);
								$userdata = get_userdata($purchaser_id);
								$first = $userdata->user_firstname;
								$last = $userdata->user_lastname;
								$order_id = $post->ID;
								?>

								<div class="order-details-wrap">
									<table>
										<thead>
											<tr>
												<th>Order ID</th>
												<th>Retailer Name</th>
												<th>Company</th>
												<th>Date</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><?php echo $order_id; ?></td>
												<td><?php echo $first . ' ' . $last; ?></td>
												<td><?php echo $company; ?></td>
												<td><?php echo $date; ?></td>
											</tr>
										</tbody>
									</table>

									<div class="products-ordred">

										<table>
											<thead>
												<tr>
													<th>Product</th>
													<th>Color</th>
													<th>Quantity</th>
													<th>Total Cost</th>
													<th>Your Percent</th>
													<th>Payment</th>
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
													if ( ! ( $payment_percent = $cat_percent_array[$cat_id]) ) {
														$payment_percent = 0;
													}
													$payment = ( $cost_actual * ( $payment_percent / 100 ) );
													$total_payment = ( $total_payment + $payment ); ?>

													<tr>	
														<td><?php echo $product_name; ?></td>
														<td><?php echo $product_color; ?></td>
														<td><?php echo $product_quantity; ?></td>
														<td><?php echo $cost_total; ?></td>
														<td><?php echo $payment_percent; ?>%</td>
														<td>$<?php echo number_format( $payment, 2); ?></td>
													</tr>

												<?php } ?>

											</tbody>

										</table>

									</div>

								</div>

							<?php } 

						} else { ?>
							<div class="no-orders-info">No orders for this period.</div>
						<?php }
						wp_reset_postdata();
						?>

						<div class="agent-total-payment">
							<h3>Your Total Payment for <?php echo $display_date; ?>: <span>$<?php echo number_format($total_payment, 2); ?></span></h3>

						</div>

					</div>

				</div>

				<div class="cell large-3">

					<header class="entry-header">
						<h1 class="entry-title">Your Retailers</h1>
					</header>

					<div class="agent-right-sidebar-inner">

						<div class="agent-button-wrap">
							<a href="/register-user-agent" class="gs-button">Add New Retailer</a>
						</div>

						<?php

						$args = array(
							'posts_per_page' => -1,
							'meta_query' => array(
								array(
									'key' => 'referring_agent',
									'value' => LV_LOGGED_IN_ID,
								),
							),
						);

						// The Query
						$user_query = new WP_User_Query( $args );

						// User Loop
						if ( ! empty( $user_query->get_results() ) ) { ?>
							<div class="retailer-names-wrap">
								<?php foreach ( $user_query->get_results() as $user ) {
									$company = get_field('company', 'user_' . $user->ID);
									$address = get_field('address', 'user_' . $user->ID);
									$city = get_field('city', 'user_' . $user->ID);
									$state = get_field('state', 'user_' . $user->ID);
									$zip = get_field('zip', 'user_' . $user->ID);
									$phone_number = get_field('phone_number', 'user_' . $user->ID);
									$userdata_new = get_userdata($user->ID);
									$first_name = $userdata_new->user_firstname;
									$last_name = $userdata_new->user_lastname;
									$user_email = $userdata_new->user_email;
									?>
									<div class="retailer-name"><a class="company-name" href="#"><?php echo $company; ?> <i class="fa fa-plus-circle"></i></a>
									<div class="agent-details">
										<div>
											<strong><?php echo $first_name . ' ' . $last_name; ?></strong>
										</div>
										<div class="address">
											<?php echo $address; ?>
										</div>
										<div class="address">
											<?php echo $city; ?>, <?php echo $state; ?> <?php echo $zip; ?>
										</div>
										<div>
											<?php echo $phone_number; ?>
										</div>
										<div>
											<a href="mailto:<?php echo $user_email; ?>"><?php echo $user_email; ?></a>
										</div>
											
									</div>

									</div>


								<?php } ?>
							</div>
						<?php } else { ?>
							<div class="no-retailers">No Retailers found.</div>
						<?php }
						


						// $users_query = new WP_Query($args);
						// while( $users_query->have_posts() ) {
						// 	$users_query->the_post();

						// 	the_title();
						// }
						wp_reset_postdata();







						$order_query = new WP_Query($args);
						


						?>

					</div>

				</div>

			</div>

		</main><!-- #main -->
	</div>
</div><!-- #primary -->

<?php
get_footer();
