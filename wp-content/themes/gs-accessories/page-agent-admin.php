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

				<div class="completed-orders-wrap">
					<?php 

						$args = array('post_type' => 'orders');

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
