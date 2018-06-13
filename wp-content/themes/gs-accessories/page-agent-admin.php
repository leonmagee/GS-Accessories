<?php
/**
 * Template Name: Agent Admin Template
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div class="max-width-wrap">
			<main id="main" class="site-main">

				<header class="entry-header">
					<h1 class="entry-title">Agent Admin</h1>
				</header>

				<a href="#" class="gs-button">Add New Retailer</a>

				<?php 
					$order_submitting_user_id = 'user_7';
					$referring_agent_id = get_field('referring_agent', $order_submitting_user_id);
					var_dump($referring_agent_id['ID']);
				?>


				<?php
				echo "<br />";
				echo "<br />";
				echo "<br />";

				$current_user_id = 'user_' . get_current_user_id();
				var_dump($current_user_id);

				echo "<br />";
				echo "<br />";
				echo "<br />";
				//$variable = get_field('field_name', 'user_1');

				//$user_meta = get_user_meta($current_user_id, 'agent_percent', false);
				$category_payment_values = get_field('agent_percent', $current_user_id);
				//var_dump($user_meta);
				//get_user_meta($user_id, $key, $single); 

				foreach( $category_payment_values as $item ) {
					var_dump($item);
					echo "<br />";
					echo "<br />";
					echo "<br />";
				}

				?>

				here is some text!!!
				we need to get the category for each product from the admin.

				<div class="completed-orders-wrap">
					<?php 

						$args = array(
							'post_type' => 'orders', 
							'posts_per_page' => -1,
							'meta_key' => 'agent_id',
							'meta_value' => 12
						);

						$order_query = new WP_Query($args);

						while( $order_query->have_posts() ) {

							$order_query->the_post(); ?>

							<div><?php the_title(); ?></div>

						<?php }
						wp_reset_postdata();
					?>

				</div>

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
