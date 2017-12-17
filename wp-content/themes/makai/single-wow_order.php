<?php get_header(); ?>

 
<?php 
	$post_type = get_post_type( $post );  
?>


<div class="post-w blog type-<?php echo $post_type ?>">


<?php include WOW_DIRE.'front_html_blocks/profile_menu.php'; /* wow_e_shop *** profile_menu *** */ ?>


    <?php // Лівий сайдбар ?>
    <?php include 'column-left.php'; ?>


<?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>

<div id="order_page" class="content">


  
<div class="cat_link order"> <h3><a href="<?php bloginfo('url'); echo '/profile/orders/'; ?>"><?php _e('My orders') ?></a></h3> </div>


<div class="order_co maine">

<?php 
$order_status = $post->pinged;
$status_arr = WOW_Checkout::order_status_array();

$billing_arr = WOW_Checkout::order_billing_info($post->ID);

$excerpt = get_the_excerpt($post->ID);
if ( !empty($excerpt) ) { $excerpt_arr = unserialize($excerpt); }
$customer_arr = $excerpt_arr['customer'];
$customer_2 = array();
if($customer_arr['email']) { $customer_2[] = $customer_arr['email']; }
if($customer_arr['phone']) { $customer_2[] = $customer_arr['phone']; }
$customer_contact = implode(', ', $customer_2);
$customer_3 = array();
if($customer_arr['country']) { $customer_3[] = $customer_arr['country']; }
if($customer_arr['city']) { $customer_3[] = $customer_arr['city']; }
if($customer_arr['address']) { $customer_3[] = $customer_arr['address']; }
$customer_address = implode(', ', $customer_3);

$products = $excerpt_arr['products'];
?>
<div class="page_title"> <h2><?php _e('Order information') ?></h2> </div>

<div class="order_head">	
    <div class="f_left">
    <time datetime="<?php the_time( 'Y-m-d' ); ?>" class="published"><?php the_time( 'd.m.Y' ); ?></time>
    <div class="ord_id"><?php _e('The order'); echo ' <span>'.$post->ID.'</span>'; ?></div>
    </div>
     <div class="f_right">
     <div class="status"><a class="stat_icon icon-<?php echo $order_status ?>" title="<?php echo $status_arr[$order_status] ?>"><i class="fa fa-shopping-cart"></i> <span><?php echo $status_arr[$order_status] ?></span></a></div>
     </div>
</div>
  
<div class="order_info">
<div class="line customer"><span class="lab"><?php _e('Customer') ?>:</span> <?php echo $customer_arr['first_name'].' '.$customer_arr['last_name'].', '.$customer_contact ?></div>
<?php if($customer_address) { ?> <div class="line address"><span class="lab"><?php _e('Shipping address') ?>:</span> <?php echo $customer_address ?></div> <?php } ?>
<div class="line pyment"><span class="lab"><?php _e('Payment method') ?>:</span> <?php echo $billing_arr['pay_label'] ?></div>
<div class="line shipping"><span class="lab"><?php _e('Shipping method') ?>:</span> <?php echo $billing_arr['shipp_label'] ?></div>
</div>

<?php //// no_feat_image 
$no_feat_image = '<img src="'.get_template_directory_uri().'/images/no_feat_image.png" class="no_feat" alt="no image" />'; 
$options_45 = get_option('site_media_settings_4'); $img_8_id = $options_45['no_feat_image_id']; 
if($img_8_id and wp_attachment_is_image($img_8_id)) { 
$no_feat_image = wp_get_attachment_image( $img_8_id, 'thumbnail' ); 
}
?> 
<div class="products">
    <div class="title"><h3><?php _e('Products') ?>:</h3></div>
    <div class="tab_head"> <div class="colu prod_img"></div> <div class="colu prod_sku"><?php _e('Sku') ?></div> <div class="colu prod_name"><?php _e('Product title') ?></div> <div class="colu prod_price"><?php _e('Price') ?></div> <div class="colu prod_qty"><?php _e('Qty') ?></div> <div class="colu prod_price tot"><?php _e('Subtotal') ?></div> </div>
    <ul class="prod-list">
	<?php foreach ($products as $prod_id => $p_qty) : //////////// foreach ///////////// ?>
    <?php $sku = get_post_meta($prod_id, 'sku', true); ?>
	<li>
	<div class="colu prod_img"> <a href="<?php echo get_permalink($prod_id); ?>" title="<?php echo get_the_title($prod_id); ?>" target="_blank">
	<?php $thumb_prod_id = $prod_id;  if(!has_post_thumbnail($prod_id) and wp_get_post_parent_id($prod_id)) { $thumb_prod_id = wp_get_post_parent_id($prod_id); } ?>
	<?php if ( has_post_thumbnail($thumb_prod_id) ) { echo get_the_post_thumbnail( $thumb_prod_id, 'thumbnail' ); } else { echo '<div class="inn">'.$no_feat_image.'</div>'; } ?>
    </a> </div>
  	
    <div class="colu prod_sku"> <span><?php echo $sku ?></span> </div>
    <div class="colu prod_name"> <h3><a href="<?php echo get_permalink($prod_id); ?>" target="_blank"><?php echo get_the_title($prod_id); ?></a></h3> </div>  
    
	<?php $row_price_arr = WOW_Cart_Session::cart_get_row_price($prod_id, $p_qty); ?>
    <div class="colu prod_price"><span class="price"><?php echo $row_price_arr['item_price'] ?></span></div>
    
    <div class="colu prod_qty"> <span><?php echo $p_qty ?></span> </div>
    
    <div class="colu prod_price tot"><span class="price"><?php echo $row_price_arr['row_total'] ?></span></div>
    </li>
	<?php endforeach;  //////////// foreach ///////////// ?>
    </ul>
</div>

<div class="order_info totals">
<div class="line subtotal"><span class="lab"><?php _e('Subtotal') ?>:</span> <span class="rez"><?php echo $billing_arr['cart_subtotal'] ?></span></div>
<div class="line shipping"><span class="lab"><?php _e('Shipping') ?>:</span> <span class="rez"><?php echo $billing_arr['shipp_price'] ?></span></div>
<div class="line grandtotal"><span class="lab"><?php _e('Grand total') ?>:</span> <span class="rez"><?php echo $billing_arr['grand_total'] ?></span></div>
</div>


<?php if(get_the_content()) { ?>		
			<div class="entry-content comme">            				
				<h3><?php _e('Comment') ?>:</h3>
				<?php the_content(); ?> 	
			</div>
<?php } ?>
            
</div> <!-- order_co  -->
  

 </div> <!-- content -->

<?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>	
 
 
   


</div> 

<?php get_footer(); ?>