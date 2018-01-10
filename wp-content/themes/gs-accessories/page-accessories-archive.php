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



    <div id="filters" class="isotope-menu">

          <div class="isotope-button-group">
            
           <a href="#" id="isotope-reset" class="button secondary active" >All Products</a>

            <?php $all_cats = get_categories(); //var_dump($all_cats); 

            foreach( $all_cats as $cat ) { 

              if ( $cat->name != 'Uncategorized' ) {
              ?>

           <a href="#" class="button cat secondary" data-filter=".<?php echo $cat->slug; ?>">
                <?php echo $cat->name; ?></a>

            <?php }

            } ?>

        </div>

    </div>



    <div class="grid-x isotope-content isotope">
        <?php
        $args = array('post_type' => 'accessories');
        $accessories_query = new WP_Query($args);    
        while ( $accessories_query->have_posts() ) {
            $accessories_query->the_post(); 

//var_dump($post->ID);
            $cats = get_the_category();
            //var_dump($cats);

            $cat_string = '';
            foreach( $cats as $cat ) {
                $cat_string .= $cat->slug . ' ';
            }

            //var_dump($cat_string);

            ?>
            <div class="small-6 medium-4 large-3 cell isotope-item <?php echo $cat_string; ?>">
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
