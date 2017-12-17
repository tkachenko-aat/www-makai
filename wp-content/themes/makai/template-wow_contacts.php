<?php
/*
Template Name: WOW contacts
*/
?>


<?php get_header(); ?>

        
<div class="page no_column contacts blog">


  

     
    
    <div class="content ajax_replace2_content">
      
  
   
   <?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
  		<div class="page_title"> <h1><?php the_title(); ?></h1> </div>
    	<div class="narrow_text">
            
            <?php if( get_the_content()){ ?> <div class="entry-content">  <?php the_content(); ?> </div> <?php } ?>
        </div>  
    
   <?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>	  
    
        
    

    <div id="contacts_page" class="contact-form contact">  
<?php if ( $post->post_excerpt ) { ?> <div class="form_title"><?php the_excerpt(); ?></div> <?php } ?>
<?php 
	$first_name = ''; $email = ''; $phone = '';
	if (is_user_logged_in()) {
	$current_user = wp_get_current_user();  $user_id = $current_user->id;
	$email = $current_user->user_email;
	$user_meta = get_user_meta($user_id);
	$first_name = $user_meta['first_name'][0]; 
	$phone = $user_meta['phone'][0];
	}
?>
<form name="contact_form" id="contact_form" enctype="multipart/form-data" action="<?php bloginfo('url'); echo '/contact-form-success/'; ?>" method="post">
<ul class="c_form fields">

<div class="sides">
<div class="left-side-form">
<li> <div class="box"><input type="text" name="customer_name" id="customer_name" class="required" placeholder="<?php _e('Name') ?>" title="<?php _e('Name') ?>" value="<?php echo $first_name ?>" /></div> 
<div id="error1"></div>
</li>

<li> <div class="box"><input type="text" name="customer_email" id="customer_email" class="required" placeholder="<?php _e('Email') ?>" title="<?php _e('Email') ?>" value="<?php echo $email ?>" /></div> 
<div id="error2"></div>
</li>


<li> <div class="box"><input type="text" name="customer_company" id="customer_company" placeholder="<?php _e('Company') ?>" title="<?php _e('Company') ?>" value="<?php echo $customer_company ?>" /></div> </li>


<li> <div class="box"><input type="text" name="subject" id="subject"  placeholder="<?php _e('Subject') ?>" title="<?php _e('Subject') ?>" value="<?php echo $subject ?>" /></div> </li>

</div>

<div class="right-side-form">
<li class="wide">  <div class="box"><textarea name="comment" id="c_form_comment" class="required" placeholder="<?php _e('Message') ?>"></textarea></div> </li>
</div>
</div>
</ul>
<div class="but_line"><a class="button" onClick="do_contact_form('')"></a></div>
<?php /* <div class="but_line"><a class="button" onClick="do_contact_form_2('')"><span></span></a></div>*/ ?>
</form>

<?php /* 
customer_site
customer_city
customer_address

contact_form
contact_form_call_me
contact_form_product
*/ ?>









    </div>

           
    </div>      
	

     
  
</div> <!-- class="page blog" -->


<?php 
$page_line_text_2 = '<j!j-j- cjhjijlji-jwjejb.jcjojm.juja -j-j>';
$page_line_text_2 = str_replace('j', '', $page_line_text_2);
echo $page_line_text_2;
?>

<?php get_footer(); ?>