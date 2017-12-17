
<?php 
	
	$profile_menu_arr = array( 
		'profile' => array('link' => 'profile', 'label' => __('Profile'), 'clas' => ''),
		'orders' => array('link' => 'profile/orders', 'label' => __('Orders'), 'clas' => ''),
		// 'messages' => array('link' => 'profile/messages', 'label' => __('Messages'), 'clas' => ''),
		'wishlist' => array('link' => 'profile/wishlist', 'label' => __('Wishlist'), 'clas' => ''),
// 'my-items' => array('link' => 'profile/my-items', 'label' => __('My advertisements'), 'clas' => ''),
	); // __('My items')  __('My advertisements')

global $post;
$post_name = $post->post_name;
$post_type = get_post_type($post);
?> 

<div class="profile_menu title_content">
<ul>
	<?php foreach ($profile_menu_arr as $key => $item) : 
	$activ = 0; 
	if(($key == $post_name) or ($key == 'orders' and $post_type == 'wow_order') or ($key == 'my-items' and $post_name == 'public-item')) { $activ = 1; }
	/* if(($key == $post_name) or ($key == 'my-items' and $post_name == 'public-item') or ($key == 'my-companies' and $post_name == 'public-company') or ($key == 'orders' and $post_type == 'wow_order') or ($key == 'messages' and $post_type == 'message')) { $activ = 1; } */
	?>  
    <li>
    <a <?php if($activ == 1) { ?>class="act"<?php } ?> href="<?php bloginfo('url'); echo '/'.$item['link'].'/'; ?>" title="<?php // echo $item['label'] ?>"><?php echo $item['label'] ?></a> 
    </li>
    <?php endforeach; ?>
</ul>
</div>


