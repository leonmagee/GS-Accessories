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
    <div class="grid-x">
        <?php
        $args = array('post_type' => 'accessories');
        $accessories_query = new WP_Query($args);    
        while ( $accessories_query->have_posts() ) {
            $accessories_query->the_post(); ?>
            <div class="small-6 medium-4 cell">
                <a href="<?php the_permalink(); ?>">
                    <div class="accessorie-archive-item">
                        <div class="archive-item-img-bg">

                            <?php 

                            $image_gallery = get_field('image_gallery');
                            if ( $image_gallery && $img_featured = array_shift($image_gallery)) { ?>

                            <img src="<?php echo $img_featured['sizes']['medium']; ?>" />

                            <?php

                        } else { ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder-image-small.jpg" />
                        <?php } ?>
                    </div>

                    <div class="archive-item-title">
                        <h2><?php the_title(); ?></h2>
                    </div>
                </div>
            </a>
        </div>
        <?php } ?>
    </div>
</main><!-- #main -->
</div>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
