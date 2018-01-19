<?php
/**
 * Template Name: Shopping Cart
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */
get_header();
?>

<div id="primary" class="content-area">

  <div class="max-width-wrap accessories-archive">

   <main id="main" class="site-main">

    <h2>Cart</h2>


  <div class='cart-wrap'>

    <?php

    var_dump(unserialize($_SESSION['shopping_cart']));

    if ( $_SESSION['shopping_cart']) {
    $cart_data = unserialize($_SESSION['shopping_cart']);
    //var_dump($cart_data);
    foreach( $cart_data as $item ) {
    ?>

      <div class="cart-item">
        <div class="cart-property"><span>Product:</span><?php echo $item['product']; ?></div>
        <div class="cart-property"><span>Quantity:</span><?php echo $item['quantity']; ?></div>
        <div class="cart-property"><span>Color:</span><?php echo $item['color']; ?></div>
        <div class="cart-property"><a href="#">Remove</a></div>
      </div>

    <?php } }?>

  </div>







    </main><!-- #main -->
  </div>
</div><!-- #primary -->

<?php
get_footer();















