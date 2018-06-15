<?php
/**
 * Template Name: Inventory Report
 */

restricted_page();
get_header(); 
?>

	<div id="primary" class="content-area">
		<div class="max-width-wrap">
			<main id="main" class="site-main">

				<h1 class="entry-title">Inventory Report</h1>

				<?php

					$args = array('post_type' => 'accessories');
					$custom_query = new WP_Query($args);
					while( $custom_query->have_posts() ) {
						$custom_query->the_post();

						?>
						
						    <?php $color_quantity = get_accessory_colors();
                            if ( $color_quantity ) { ?>
                            	<div class="accessory-inventory-item">
								<h4><?php the_title(); ?></h4>
                                <table class="single-accessory-table">
                                    <thead>
                                        <tr>
                                            <th>Color</th>
                                            <th>In Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                <?php foreach( $color_quantity as $item => $quantity ) { ?>
                                    <tr>
                                        <td><?php echo $item; ?></td>
                                        <td><?php echo $quantity; ?> Available</td>
                                    </tr>
                                <?php } ?>

                                    </tbody>
                                </table>
                                						</div>

                            <?php } 
					 }
					wp_reset_postdata();

				?>

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
