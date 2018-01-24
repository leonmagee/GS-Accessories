<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content single-accessory">
        <?php

        $wholesale_price = get_field('wholesale_price');

        $dealer_price = get_field('dealer_price');
        
        $description = get_field('accessory_text');

        $protections = get_field('accessory_protections');

        $features = get_field('accessory_features');

        $additional_features = get_field('additional_features');
        $add_features_array = array();
        if ( $additional_features ) {
            foreach( $additional_features as $feature ) {
                $add_features_array[] = $feature['feature'];
            }
            $combined_features = array_merge($features, $add_features_array);
        } else {
         $combined_features = $features; 
     }

     $colors = get_field('accessory_colors');

     if ( $colors ) {
        $border_class = 'border-class';
    } else {
        $border_class = '';
    }

    ?>
    <div class="grid-x">
        <div class="image-wrap cell large-5">
            <?php $image_gallery = get_field('image_gallery');


            if ( $image_gallery && $img_featured = array_shift($image_gallery)) {

                //if ( has_post_thumbnail() ) {
                //the_post_thumbnail(); ?>
                <div class="img-wrap-bg">
                    <a href="<?php echo $img_featured['sizes']['large']; ?>" rel="lightbox">
                        <img src="<?php echo $img_featured['sizes']['accessory_image']; ?>" />
                    </a>
                </div> 
                <?php } else { ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder-image.jpg" />
                <?php } ?>

                <div class="thumbnail-wrap">

                     <?php //var_dump($image_gallery); 
                     if ( $image_gallery ) {
                        foreach( $image_gallery as $image ) {
                            $image_url = $image['sizes']['thumbnail']; ?>
                            <div class="img-wrap img-wrap-bg">
                                <a href="<?php echo $image['sizes']['large']; ?>" rel="lightbox">
                                    <img src="<?php echo $image_url; ?>" />
                                </a>
                            </div>
                            <?php } 
                        } ?>

                    </div>


                </div>
                <div class="cell large-7 description-features-wrap">

                    <?php if (is_user_logged_in() && $wholesale_price && $dealer_price ) { 
                            if ( current_user_can('edit_post')) { 
                                $price_name = 'Wholesale';
                                $price_value = number_format($wholesale_price, 2);

                            } else {
                                $price_name = 'Dealer';
                                $price_value = number_format($dealer_price, 2);
                            }

                        ?>
                    
                    <div class="price-wrap-outer">

                    <h4><?php echo $price_name; ?> Price</h4>

                        <div class="price-wrap">

                            $<?php echo $price_value; ?>
                            
                        </div>
                        <div class="price-description">
                            Per 1,000 Units
                        </div>
                        
                    </div>
                    <?php } ?>

                    <div class="accessory-description">
                        <h4>Description</h4>
                        <?php echo $description; ?>
                    </div>

                    <?php if ( $protections ) { ?>

                    <div class="protections-wrap">

                        <h4>Protections</h4>

                        <?php foreach( $protections as $protection ) {

                            if ( $protection == 'Overcharge Protection' ) { ?>

                            <div class="protection">
                                <?php get_template_part('assets/svg/over-charge-protection'); ?>
                                <span>Overcharge Protection</span>
                            </div>
                            <?php }

                            if ( $protection == 'Over-Voltage Protection' ) { ?>

                            <div class="protection">
                                <?php get_template_part('assets/svg/voltage-protection'); ?>
                                <span>Over-Voltage Protection</span>
                            </div>
                            <?php }

                            if ( $protection == 'Short Circuit Protection' ) { ?>

                            <div class="protection">
                                <?php get_template_part('assets/svg/short-circuit-protection'); ?>
                                <span>Short Circuit Protection</span>
                            </div>
                            <?php }

                            if ( $protection == 'Over-Current Protection' ) { ?>

                            <div class="protection">
                                <?php get_template_part('assets/svg/current-protection'); ?>
                                <span>Over-Current Protection</span>
                            </div>
                            <?php }

                        } ?>

                    </div>

                    <?php } ?>
                    <div class="features-section-wrap">
                        <?php if ( $combined_features ) { ?>
                        <div class="features-section <?php echo $border_class; ?>">
                            <h4>Features</h4>
                            <ul>
                                <?php foreach( $combined_features as $feature ) { ?>
                                <li><?php echo $feature; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php } ?>
                        <?php if ( $colors ) { ?>
                        <div class="features-section">
                            <h4>Colors</h4>
                            <ul>
                                <?php foreach( $colors as $color ) { ?>
                                <li><?php echo $color; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="order-button-wrap">
                        <a class="gs-button">Request Item</a>
                    </div>
                </div>
            </div>

        </div><!-- .entry-content -->

    </article><!-- #post-<?php the_ID(); ?> -->
