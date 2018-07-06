<?php
/**
 * Template Name: PHP Tester
 *
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div class="max-width-wrap">
			<main id="main" class="site-main">

			<?php

			$args = array('p' => $data['id'], 'post_type' => 'rmas');
			$custom_query = new WP_Query($args);
			$user_email_text = false;
			while( $custom_query->have_posts() ) {

				$custom_query->the_post();
				$rma_number = get_field('rma_number');

				var_dump($rma_number);


			for ( $i = 0; $i < 5; $i++ ) {

				$item_field = get_field('return_item_' . ( $i + 1 ) );

								var_dump($item_field['item_name']);
								var_dump($item_field['item_name']);
								var_dump($item_field['item_name']);
								var_dump($item_field['item_name']);
								var_dump($item_field['item_name']);
								var_dump($item_field['item_name']);
								var_dump($item_field['item_name']);

			}

		}







			?>

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
