<?php
/*
Template Name: WOW contact-form-success
*/
?>

<?php $save_new_form_order = WOW_Contact_Form::save_new_form_order(); ?>


<?php get_header(); ?>

        
<div class="page contact-form-success no_column blog">

  
   <?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>  
    
	 <div id="contact_form_succ_page" class="content ajax_replace2_content">
   
    
    <div class="success_main">     
    <?php // print_r($save_new_form_order); ?>
    </div>
        
    <?php if($save_new_form_order) { ?>
    <div class="page_title"> <h1><?php the_title(); ?></h1> </div>
    <div class="succes_text">
    <div class="entry-content"> 
	<?php if($_POST['is_project_planner']){ the_excerpt(); } else { the_content(); } ?>
    </div>
    </div>
    <?php } else { ?>  
    <p><?php _e('Contact form is failed.') ?></p>
	<?php } ?>
    
           
    </div>      
	
	<?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>	


     
  
</div> <!-- class="page blog" -->



<?php get_footer(); ?>