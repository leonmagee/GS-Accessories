<?php
/**
 * Template Name: Agent Admin Template
 *
 * @todo also query by month
 */

get_header(); ?>

<div id="primary" class="content-area">
	<div class="max-width-wrap">
		<main id="main" class="site-main">

			<header class="entry-header">
				<h1 class="entry-title">Agent Admin</h1>
			</header>
	
			<div class="new-retailer-button-wrap">
				<a href="#" class="gs-button">Add New Retailer</a>
			</div>


			<?php

				if ( isset($_GET['data_month'])) {
					$current_month = intval($_GET['data_month']);
					$current_year = intval($_GET['data_year']);
					$monthName = DateTime::createFromFormat('m', $current_month)->format('F');
					$display_date = $monthName . ' ' . $current_year;

				} else {
					// get month
					$display_date = date( 'F Y' );
				}
			?>

			<div class="month-info">
				<h3>Commission Data - <?php echo $display_date; ?></h3>
				<div class="change-date-form">
					<h5><a class="toggle">Change Date</a></h5>

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
			}

			//var_dump($cat_percent_array);
			?>

			<div class="completed-orders-wrap">
				<?php 

				$args = array(
					'post_type' => 'orders', 
					'posts_per_page' => -1,
					'meta_key' => 'agent_id',
					'meta_value' => 12
				);

				$order_query = new WP_Query($args);

				$total_payment = 0;

				while( $order_query->have_posts() ) {

					$order_query->the_post(); 
					$date = get_the_date();
					$purchaser_id = get_field('user_id');
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
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $order_id; ?></td>
									<td><?php echo $first . ' ' . $last; ?></td>
									<td><?php echo $date; ?></td>
								</tr>
							</tbody>
						</table>

						<div class="products-ordred">

							<?php $entries = get_field('product_entries'); 

							foreach( $entries as $entry ) {
								$product_name = $entry['product_name'];
								$product_id = $entry['product_id'];
								$product_color = $entry['product_color'];
								$product_quantity = $entry['product_quantity'];
								$unit_cost = $entry['unit_cost'];
								$cost_total = $entry['cost_total'];
								$cost_actual = intval(str_replace('$', '', $cost_total));
								$category_array = get_the_category($product_id);
								$cat_name = $category_array[0]->name;
								$cat_id = $category_array[0]->term_id;
								if ( ! ( $payment_percent = $cat_percent_array[$cat_id]) ) {
									$payment_percent = 0;
								}
								$payment = ( $cost_actual * ( $payment_percent / 100 ) );
								$total_payment = ( $total_payment + $payment );
								//var_dump($cat_name . ' ' . $cat_id); ?>

								<div>Name: <?php echo $product_name; ?></div>
								<div>ID: <?php echo $product_id; ?></div>
								<div>Color: <?php echo $product_color; ?></div>
								<div>Quantity: <?php echo $product_quantity; ?></div>
								<div>Unit Cost: <?php echo $unit_cost; ?></div>
								<div>Total Cost: <?php echo $cost_total; ?></div>
								<div>Your Percent: <?php echo $payment_percent; ?>%</div>
								<div>Payment: $<?php echo number_format( $payment, 2); ?></div>
								<div><?php //echo $cat_name; ?></div>
								<div><?php //echo $cat_id; ?></div>
								<br />

							<?php } ?>

						</div>



					</div>

				<?php }
				wp_reset_postdata();
				?>

						<div>
							<h1>Your Total Payment for (Current Month)</h1>
							<h1>$<?php echo number_format($total_payment, 2); ?></h1>

						</div>

			</div>

		</main><!-- #main -->
	</div>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
