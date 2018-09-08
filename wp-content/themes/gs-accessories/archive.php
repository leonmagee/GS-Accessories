<?php
/**
 * Template Name: Accessories Archive Isotope
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */

get_header(); 

$current_cat = get_queried_object()->slug;
$cat_title = ucwords(str_replace('-', ' ', $current_cat));

$_SESSION['shopping_page'] = $current_cat;

?>

<div id="primary" class="content-area">
  <div class="max-width-wrap accessories-archive">
   <main id="main" class="site-main">
    <h1 class="entry-title"><?php echo $cat_title; ?></h1>

    <div class="grid-x isotope-content isotope">
        <?php
        $args = array(
            'post_type' => 'accessories',
            'category_name' => $current_cat,
            'post__not_in' => LV_HIDE_ACCESSORIES
        );
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

                            <img src="<?php echo $img_featured['sizes']['accessory_image']; ?>" />

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
get_footer();
