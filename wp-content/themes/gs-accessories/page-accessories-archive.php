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

        <div class="cat_archive_wrap">

            <?php 

            /**
            * get list of categories here...
            */

            $hide_array = [];

            if ( LV_HIDE_PRODUCTS ) {

                foreach( LV_HIDE_PRODUCTS as $item ) {
                    $hide_array[] = trim($item['category_title']);
                }
            }

            $custom_field_cats = get_field('categories','option');


            foreach( $custom_field_cats as $cat) { 

                if ( in_array($cat['title'], $hide_array) ) {
                    continue;
                }
                ?>

            <div class="cat_archive_item">

                <a href="<?php echo site_url() . '/products/' . $cat['url']; ?>">

                    <?php //var_dump( $cat['image']['sizes']['cats_image']); ?>

                    <img src="<?php echo $cat['image']['sizes']['cats_image']; ?>" />

                    <h3><?php echo $cat['title']; ?></h3>

                </a>

            </div>


            <?php } ?>

        </div>

    </main><!-- #main -->
</div>
</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
