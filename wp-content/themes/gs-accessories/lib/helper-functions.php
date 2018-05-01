<?php
/**
 * HELPER FUNCTIONS
 */

/**
 * Content Excerpt
 *
 * @param $content
 * @param $length
 * @param string $suffix
 *
 * @return string
 */
function content_excerpt( $content, $length, $suffix = '...' ) {
    $strlen = strlen($content);
    //var_dump($strlen);
    //var_dump($length);
    if ( $strlen > $length ) {
        $string = substr( $content, 0, $length );
        $exploded = explode( ' ', $string );
        array_pop( $exploded );
        $implode = implode( ' ', $exploded );
        $final = $implode . $suffix;
        return $final;
    } else {
        return $content;
    }

}

function percent_price($price, $percent) {

    $after_coupon_cost = $price * ( ( 100 - $percent ) / 100 );

    return $after_coupon_cost;
}

function get_coupon_array() {
    $coupon_array = array();
    $args = array('post_type' => 'coupons');
    $custom_query = new WP_Query($args);
    while( $custom_query->have_posts() ) {
      $custom_query->the_post();
      $coupon_percent_field = get_field('discount_percent');
      $coupon_name = strtolower(get_the_title());
      $coupon_array[$coupon_name] = $coupon_percent_field;
    }
    wp_reset_postdata();

    return $coupon_array;
}





