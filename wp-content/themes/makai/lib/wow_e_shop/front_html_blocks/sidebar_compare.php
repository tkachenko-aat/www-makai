
<?php $compare_arr = WOW_Compare_Session::compare_array(); ?>
<?php // print_r($compare_arr); ?>
<div class="<?php if(count($compare_arr)) { ?>block<?php } ?> sidebar_compare">
<?php if(count($compare_arr)) : 
$v_count = 4;
$compare_arr_side = array_slice($compare_arr, 0, $v_count);
?>
<div class="block-title">
<span><?php _e('Compare list') ?><span><?php echo ' ('.count($compare_arr).')' ?></span></span>
<a class="toogle-b"></a>
</div>
<div class="block-content">
<ul class="prod-list">
	<?php foreach ($compare_arr_side as $id) : //////////// foreach ///////////// ?>
	<li>	
    <a class="product-box" href="<?php echo get_permalink($id) ?>">
	<div class="prod_img"><?php if ( has_post_thumbnail($id) ) { echo get_the_post_thumbnail( $id, 'thumbnail' ); } else { echo '<div class="no_feat_image"></div>'; } ?></div>
    <div class="prod_name"> <?php echo get_the_title($id) ?> </div>
    </a>  	
    </li>
	<?php endforeach;  //////////// foreach ///////////// ?>
</ul>
<div class="button_line">   
    <a onclick="show_compare()" class="button b_right"><?php _e('Show compare list') ?></a>
    </div>
</div>
<?php endif; ?>
</div>