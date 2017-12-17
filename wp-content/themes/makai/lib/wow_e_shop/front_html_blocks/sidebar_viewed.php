
<?php $viewed_arr = WOW_Viewed_Session::viewed_array('side'); ?> 
<?php // print_r($viewed_arr); ?>
<div class="<?php if(count($viewed_arr)) { ?>block<?php } ?> sidebar_viewed">
<?php if(count($viewed_arr)) : ?>
<div class="block-title">
<span><?php _e('Recently viewed products') ?></span>
<a class="toogle-b"></a>
</div>
<div class="block-content">
<ul class="prod-list">
	<?php foreach ($viewed_arr as $id) : //////////// foreach ///////////// ?>
	<li>	
    <a class="product-box" href="<?php echo get_permalink($id) ?>">
	<div class="prod_img"><?php if ( has_post_thumbnail($id) ) { echo get_the_post_thumbnail( $id, 'thumbnail' ); } else { echo '<div class="no_feat_image"></div>'; } ?></div>
    <div class="prod_name"> <?php echo get_the_title($id) ?> </div>
    </a>  	
    </li>
	<?php endforeach;  //////////// foreach ///////////// ?>
</ul>
</div>
<?php endif; ?>
</div>