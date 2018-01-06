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
        
            $description = get_field('accessory_text');

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

        ?>
        <div class="grid-x">
            <div class="image-wrap cell large-5">
                <?php $image_gallery = get_field('image_gallery');


                if ( $img_featured = array_shift($image_gallery)) {

                //if ( has_post_thumbnail() ) {
                //the_post_thumbnail(); ?>
                
                    <a href="<?php echo $img_featured['url']; ?>" rel="lightbox">
                        <img src="<?php echo $img_featured['url']; ?>" />
                    </a>
                <?php } else { ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder-image.jpg" />
                <?php } ?>

                <div class="thumbnail-wrap">

                     <?php //var_dump($image_gallery); 
                    
                    foreach( $image_gallery as $image ) {
                        $image_url = $image['sizes']['thumbnail']; ?>
                        <div class="img-wrap">
                            <a href="<?php echo $image['url']; ?>" rel="lightbox">
                                <img src="<?php echo $image_url; ?>" />
                            </a>
                        </div>
                    <?php } ?>

                </div>


            </div>
            <div class="cell large-7 description-features-wrap">
                <div class="accessory-description">
                <h4>Description</h4>
                    <?php echo $description; ?>
                </div>
                <div class="features-section">
                    <h4>Features</h4>
                    <ul>
                    <?php foreach( $combined_features as $feature ) { ?>
                        <li><?php echo $feature; ?></li>
                    <?php } ?>
                    </ul>
                </div>
                <div class="order-button-wrap">
                    <button class="gs-button">Request Item</button>
                </div>
            </div>
        </div>

	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'gs-accessories' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
