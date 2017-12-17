
<?php $cart_array = WOW_Cart_Session::cart_array();  $cart_count = count($cart_array); ?>
<div class="block sidebar_cart" id="sidebar_cart_v"> 
<div class="block-title">
 <a <?php if($cart_count) { ?>onclick="show_cart()" class="show"<?php } ?>>
 <i class="fa fa-shopping-basket" aria-hidden="true"></i>
    <?php if($cart_count) : ?>
    <span class="tit"><?php _e('My cart') ?></span>
    <?php $cart_subtotal = WOW_Cart_Session::cart_get_subtotal(); ?>
<?php if($cart_count == 1) { $prods = __('Product'); } 
elseif(in_array($cart_count, array(2, 3, 4))) { $prods = __('Products'); } 
else { $prods = __('Products.'); } 
$prods = str_replace('.', '', $prods);
?>
    <div class="pr_count"><span><?php echo $cart_count.'  ' ?></span> <?php echo $prods ?></div> 
    <div class="subtotal"><span class="price"><?php echo $cart_subtotal ?></span></div>
    <?php else : ?>
    <div class="no_items"><?php _e('Your cart is empty.') ?></div>
    <?php endif; ?>
 </a>
</div>
</div>