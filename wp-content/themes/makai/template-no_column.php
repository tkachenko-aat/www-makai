<?php
/*
Template Name: No_column
*/
?>


<?php get_header(); ?>

        
<div class="page no_column blog">

  
   <?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>  
    
	 <div class="content">
     
     <?php // breadcrumbs
   if (function_exists('breadcrumbs')) breadcrumbs(); ?>
 
    <div class="page_title title_content"> <h1><?php the_title(); ?></h1> </div>
	
	<div class="maine entry-content"> <?php the_content(); ?> </div>
           
    </div>      
	
	<?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>	


     
  
</div> <!-- class="page blog" -->



<?php get_footer(); ?>