<?php
/**
 * Template Name: About Page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */

get_header(); ?>

	<div id="primary" class="content-area about-page-content-bg">
		<div class="max-width-wrap">
			<main id="main" class="site-main">

				<?php
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/content', 'page' );

				endwhile; // End of the loop.
				?>

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
