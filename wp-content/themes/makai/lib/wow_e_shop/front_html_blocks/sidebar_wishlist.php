
<?php 
$wishlist_arr = array();
if (is_user_logged_in()) {
$wishlist_id = WOW_Wishlist::select_wishlist_id();
$wishlist_arr = WOW_Wishlist::cur_wishlist_array($wishlist_id);
}
?>
<?php // print_r($wishlist_arr); ?>  
<div class="<?php if(count($wishlist_arr)) { ?>block<?php } ?> sidebar_wishlist">
<?php if(count($wishlist_arr)) : 
$v_count = 4;
$wishlist_arr_side = array_slice($wishlist_arr, 0, $v_count);
?>
<div class="block-title">
<span><?php _e('Wishlist') ?><span><?php echo ' ('.count($wishlist_arr).')' ?></span></span>
<a class="toogle-b"></a>
</div>
<div class="block-content">
<ul class="prod-list">
	<?php foreach ($wishlist_arr_side as $id) : //////////// foreach ///////////// ?>
	<li>	
    <a class="product-box" href="<?php echo get_permalink($id) ?>">
	<div class="prod_img"><?php if ( has_post_thumbnail($id) ) { echo get_the_post_thumbnail( $id, 'thumbnail' ); } else { echo '<div class="no_feat_image"></div>'; } ?></div>
    <div class="prod_name"> <?php echo get_the_title($id) ?> </div>
    </a>  	
    </li>
	<?php endforeach;  //////////// foreach ///////////// ?>
</ul>
<div class="button_line">   
    <a href="<?php bloginfo('url'); echo '/profile/wishlist/'; ?>" class="button b_right"><?php _e('Go to wishlist') ?></a>
    </div>
</div>
<?php endif; ?>
</div>