<?php get_header(); ?>


<div class="page no_column not_found blog">
    
	 <div class="content">
     <div class="narrow_text">
            <div class="page_title"> <h1><?php _e('Page not found'); ?></h1> </div>
            <?php if( get_the_content()){ ?> <div class="entry-content"> <?php the_content(); ?> </div><?php } ?>
            <div class="big-text">404</div>
        </div>
      
  
           
    </div>      
 
  
</div>



<?php get_footer(); ?>