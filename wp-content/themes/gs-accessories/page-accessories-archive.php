<?php
/**
 * Template Name: Accessories Archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div class="max-width-wrap accessories-archive">
			<main id="main" class="site-main">

                <?php

                $args = array('post_type' => 'accessories');
                $accessories_query = new WP_Query($args);    
                while ( $accessories_query->have_posts() ) {
                    $accessories_query->the_post(); ?>
                    <div class="accessorie-archive-item">
                        <a href="<?php the_permalink(); ?>">
                            <div class="grid-x">
                                <div class="cell medium-3 large-2 image-wrap">
                                    <?php 

                                    $image_gallery = get_field('image_gallery');
                                    if ( $image_gallery && $img_featured = array_shift($image_gallery)) { ?>
                                        
                                        <img src="<?php echo $img_featured['sizes']['medium']; ?>" />
                                    <?php

                                    } else { ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder-image-small.jpg" />
                                    <?php } ?>
                                </div>
                                <div class="cell medium-9 large-10 content-wrap">
                                    <h2><?php the_title(); ?></h2>
                                    <p><?php echo content_excerpt(get_field('accessory_text'),500); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php }            


				// while ( have_posts() ) : the_post();

				// 	get_template_part( 'template-parts/content', 'page' );

				// endwhile; // End of the loop.
				?>

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
