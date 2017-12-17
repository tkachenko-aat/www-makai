<?php
/*
Template Name: WOW checkout-success
*/
?>

<?php $save_new_order = WOW_Checkout::save_new_order(); ?>

<?php get_header(); ?>

        
<div class="page checkout-success no_column blog">

  
   <?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>  
    
	 <div id="checkout_succ_page" class="content ajax_replace2_content">	
   
    
    <div class="success_main"> 

   <?php // print_r($save_new_order); ?>

<?php 
/* 
if($save_new_order and $_POST['payment_method']) { WOW_Checkout::pay_online($save_new_order); } 
else { WOW_Checkout::pay_online_success(); }
 */
 
/*
Адмінка. Payment methods. Payment options 
Приват 24 - тільки 1-й рядок. номер типу 153117 (merchant id; він доступний після реєстрації мерчанта у Приват 24)
Webmoney - тільки 1-й рядок. номер гаманця типу U387095351503 (або декілька гаманців, через кому)
Liqpay - 2 рядки (public_key, private_key). 
 */
?>

    </div>
    
<?php // array('webmoney', 'paypal', 'privat24', 'liqpay', 'online-bank');
$online_methods_arr = WOW_Settings::pay_online_methods_list();

$page_7_status = 4; // текст (із підсторінки) залежно від методу оплати - розкоментувати 2 рядки
// $page_7 = get_page_by_path('checkout-success/payment_method_'.$save_new_order['payment_method']);
// if($page_7) { $page_7_status = get_post_field( 'post_status', $page_7->ID ); }
?> 

<div class="conte maine">
    <?php if($save_new_order and in_array($page_7_status, array('publish', 'private', 'pending'))) { 
	// Існує окрема підсторінка з текстом про виконане замовлення; URL - payment_method_cash, payment_method_bank ... ; Статус стор. - 'pending' ?>
    <div class="page_title"> <h3><?php the_title(); ?></h3> </div>
    <div class="entry-content checkout_succ"> <?php echo apply_filters('the_content', get_post_field('post_content', $page_7->ID)); ?> </div>
    
    <?php } elseif($save_new_order and !in_array($save_new_order['payment_method'], $online_methods_arr)) { 
	// звичайні методи (не онлайн) без спец. опису ?>
    <div class="page_title"> <h3><?php the_title(); ?></h3> </div>
    <div class="entry-content checkout_succ"> <?php the_content(); ?> </div>
    
    <?php } elseif($_POST['payment'] or $_POST['order_id']) { // оплата успішна ?>
    <div class="payment_succ"> <?php the_excerpt(); ?> </div>
    <div class="page_title confi"> <h3><?php the_title(); ?></h3> </div>
    <div class="entry-content checkout_succ confi"> <?php the_content(); ?> </div> 	

    <?php } elseif($_GET['my_pay']) { // оплатити пізніше ?>
    <div class="page_title confi"> <h3><?php the_title(); ?></h3> </div>
    <div class="entry-content checkout_succ confi"> <?php the_content(); ?> </div> 	

    <?php } elseif(!$save_new_order) { ?>
    <p class="no_items"><?php _e('You have no items to checkout.') ?></p>
    <p><?php _e('Checkout is failed.') ?></p>    
	<?php } ?>    

</div>
           
    </div>      
	
	<?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>	



     
<div style="display: none;">
<?php include WOW_DIRE.'front_html_blocks/sidebar_cart.php'; /* wow_e_shop *** sidebar_cart *** */ ?>
</div>

  
</div> <!-- class="page blog" -->



<?php get_footer(); ?>