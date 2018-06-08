<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */

if ( $current_url = $_SESSION['shopping_page'] ) {
    $continue_url = '/products/' . $current_url;
} else {
    $continue_url = '/products';
}

if ( isset( $_GET['added-to-cart'])) {
  if ($_GET['added-to-cart'] == 'true' ) {
    $success_notice = true;
} else {
    $success_notice = false;
}
} else {
  $success_notice = false;
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content single-accessory">
        <?php
        
        $wholesale_price = get_field('wholesale_price');

        $retail_price = get_field('retail_price');
        
        $market_price = get_field('market_price');
        
        $description_text = get_field('accessory_text');



        // protections
        $protections = get_field('accessory_protections');
        
        $additional_protections = get_field('additional_protections');

        $add_protections_array = array();

        if ( $additional_protections ) {
            foreach( $additional_protections as $protection ) {
                $add_protections_array[] = $protection['protection'];
            }
            if ( $protections ) {
                $combined_protections = array_merge($protections, $add_protections_array);
            } else {
                $combined_protections = $add_protections_array;
            }
        } else {
            $combined_protections = $protections; 
        }

        // features
        $features = get_field('accessory_features');
        
        $additional_features = get_field('additional_features');

        $add_features_array = array();

        if ( $additional_features ) {
            foreach( $additional_features as $feature ) {
                $add_features_array[] = $feature['feature'];
            }
            if ( $features ) {
                $combined_features = array_merge($features, $add_features_array);
            } else {
                $combined_features = $add_features_array;
            }
        } else {
            $combined_features = $features; 
        }

        // benefits
        $benefits = get_field('accessory_benefits');

        $additional_benefits = get_field('additional_benefits');

        $add_benefits_array = array();

        if ( $additional_benefits ) {
            foreach( $additional_benefits as $benefit ) {
                $add_benefits_array[] = $benefit['benefit'];
            }
            if ( $benefits ) {
                $combined_benefits = array_merge($benefits, $add_benefits_array);
            } else {
                $combined_benefits = $add_benefits_array;
            }
        } else {
            $combined_benefits = $benefits; 
        }

        // descriptions
        $description_array = array();
        $descriptions = get_field('accessory_descriptions');
        if ( $descriptions ) {
            foreach( $descriptions as $description ) {
                $description_array[] = $description['description'];
            }
        }


        ?>

        <div class="grid-x">
            <div class="image-wrap cell large-4">
                <?php $image_gallery = get_field('image_gallery');


                if ( $image_gallery && $img_featured = array_shift($image_gallery)) { ?>
                <div class="img-wrap-bg">
                    <a href="<?php echo $img_featured['sizes']['large']; ?>" rel="lightbox">
                        <img src="<?php echo $img_featured['sizes']['accessory_image']; ?>" />
                    </a>
                </div> 
                <?php } else { ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder-image.jpg" />
                <?php } ?>

                <div class="thumbnail-wrap">

                 <?php
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

            <div class="cell large-6 description-features-wrap">

                <?php if ( $success_notice ) { ?>
                <div class="cell medium-12 single-product-callout-success">
                    <div class="callout success">
                        <span>Product Added to Cart.</span><a href="/cart">View Cart</a><a href="/products">Continue Shopping</a><a href="<?php echo $continue_url; ?>">Go Back</a>                               
                    </div>
                </div>

                <?php } ?>


                <div class="main-grid-wrap-accessories">


                    <div class="css-grid-item description">

                        <?php 

                        $show_price = false;

                        if (is_user_logged_in() ) { 
                           if ( current_user_can('edit_posts') ) {
                            if ( current_user_can('delete_published_posts')) {
                                if ( $wholesale_price ) {
                                    $price_name = 'Wholesaler';
                                    $price_value = '$' . number_format($wholesale_price, 2);
                                    $show_price = true;
                                }
                            } else {
                                if ( $retail_price ) {
                                    $price_name = 'Retailer';
                                    $price_value = '$' . number_format($retail_price, 2);  
                                    $show_price = true;
                                }
                            }
                        } else {
                            if ( $market_price ) {
                                $price_name = 'Market';
                                $price_value = '$' . number_format($market_price, 2);
                                $show_price = true;
                            } 

                        } } else {
                            if ( $market_price ) {
                                $price_name = 'Market';
                                $price_value = '$' . number_format($market_price, 2);
                                $show_price = true;
                            } 
                        }
                        ?>

                        <?php if ( $show_price ) { ?>

                        <div class="price-wrap-outer">

                            <h4><?php echo $price_name; ?> Price</h4>

                            <div class="price-wrap">

                                <?php echo $price_value; ?>

                            </div>
                            <div class="price-description">
                                Per Unit
                            </div>

                        </div>

                        <?php } ?>

                        <div class="accessory-description">
                            <h4>Inventory</h4>
                            <?php 
                            $show_add_to_cart_form = true;
                            $color_quantity = get_field('colors_and_quantity'); 
                                if ( $color_quantity ) {
                                foreach( $color_quantity as $item ) { ?>
                                    <div class="color-quantity-item"><?php echo $item['color']; ?> - <?php echo $item['quantity']; ?> Available</div>
                                <?php }
                            } else {
                                echo "<span class='out-of-stock'>Out of Stock</span>";
                                $show_add_to_cart_form = false;
                            }


                            ?>
                        </div>



                        <div class="accessory-description">
                            <h4>Product Details</h4>
                            <?php echo $description_text; ?>
                        </div>

                    </div>


                    <div class="grid-x">

                        <div class="protections-wrap features-section-wrap cell large-6 margin-bottom-accessories">

                            <?php if ( $combined_protections ) { ?>

                            <div class="protections-inner-wrap">

                                <h4>Protections</h4>

                                <?php foreach( $combined_protections as $protection ) { ?>

                                    <div class="protection">
                                        <?php get_template_part('assets/svg/check-mark-protection'); ?>
                                        <span><?php echo $protection; ?></span>
                                    </div>

                                <?php } ?>

                            </div>

                            <?php } ?>


                        <?php if ( $description_array ) {  ?>
                            <div class="features-section descriptions">
                                <h4>Descriptions</h4>
                                <ul>
                                    <?php foreach( $description_array as $description ) { ?>
                                    <li>
                                        <?php get_template_part('assets/svg/icon-circle'); ?>
                                        <?php echo $description; ?>    
                                    </li>
                                    <?php } ?>
                                </ul>
                        </div>
                        <?php } ?>


                        </div>

                        <div class="protections-wrap features-section-wrap cell large-6 margin-bottom-accessories">

                            <?php if ( $combined_features ) { ?>

                            <div class="features-section">

                                <h4>Features</h4>

                                <ul>
                                    <?php foreach( $combined_features as $feature ) { ?>
                                    <li>
                                        <?php get_template_part('assets/svg/icon-square'); ?>
                                        <?php echo $feature; ?>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <?php } ?>

                            <?php if ( $combined_benefits ) { ?>
                            <div class="benefits features-section">
                                <h4>Benefits</h4>
                                <ul>
                                    <?php foreach( $combined_benefits as $benefit ) { ?>
                                    <li>
                                        <?php get_template_part('assets/svg/icon-star'); ?>
                                        <?php echo $benefit; ?>

                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <?php } ?>

                        </div>

                </div>

                </div>

            </div>


            <div class="cell large-2 margin-bottom-accessories">

                <div class="css-grid-item quantity">

                    <?php 

                    if ( $show_add_to_cart_form ) {
                    $colors = get_accessory_colors(); ?>

                    <div class="order-button-wrap">

                        <form method="POST" action="#">

                            <input type="hidden" name="add-one-accessory" value="<?php the_ID(); ?>" />

                            <input type="hidden" name="product" value="<?php echo $post->post_name; ?>" />

                            <div class="input-item">

                                <label>Quantity</label>

                                <?php 
                                    $counter = 1;
                                        foreach ( $colors as $color => $quantity ) {
                                            if ( $counter === 1 ) {
                                                $namer = 'quantity';
                                            } else {
                                                $namer = 'not-quantity';
                                            }
                                            $counter_class = 'item-' . $counter;
                                            $counter++;
                                        $color_class = strtolower(str_replace(' ', '-', $color));
                                    ?>

                                    <input class="quantity-input <?php echo $color_class . ' ' . $counter_class; ?> " name="<?php echo $namer; ?>" type="number" placeholder="Max <?php echo $quantity; ?>" />

                                <?php } ?>

                            </div>

                            <?php if ( $colors ) { ?>
                            <div class="input-item">

                                <label>Color</label>

                                <select name="color-select">

                                  <?php foreach ( $colors as $color => $quantity ) { ?>

                                  <option value="<?php echo $color; ?>"><?php echo $color; ?></option>

                                  <?php } ?>

                              </select>

                          </div>

                          <?php } else { $colors = false; }?>



                          <?php 
                          //if ( LV_LOGGED_IN_ID && current_user_can('edit_posts')) { 
                          if ( LV_LOGGED_IN_ID ) { ?>

                          <button type="submit" class="gs-button">Add To Cart</button>

                          <?php } else { ?>

                          <a data-open="login-modal" class="gs-button">Add To Cart</a>

                          <?php } ?>
                      </form>
                  </div>

              <?php } ?>

              </div>

              <div class="features-section">
                <h4>Reviews</h4>

                <?php
                echo do_shortcode('[site_reviews_summary assigned_to="post_id"]');

                ?>

                <button class="gs-button" data-open="reviewsModal">See Reviews</button>

            </div>

            <div class="features-section">

                <h4>Leave a Review</h4>

                <?php
                echo do_shortcode('[site_reviews_form assign_to="post_id" hide="email,name,terms,title"]');
                ?>

            </div>

        </div>

    </div>
</div>

</div><!-- .entry-content -->

</article><!-- #post-->

<div class="reveal" id="reviewsModal" data-reveal>
  <h1>Product Reviews</h1>
  <p>
    <?php echo do_shortcode('[site_reviews assigned_to="post_id" hide="title" count=10]'); ?>
</p>
<button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
</button>
</div>
