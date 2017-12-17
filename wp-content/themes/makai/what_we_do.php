<?php
/*
Template Name: What we do
*/
?>


<?php get_header(); ?>

        
<div class="page no_column blog studio">
  
   <?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>  
    
	
  
    
    
	

	<div class="content">
    
    <div class="page_title"> <h1><?php the_title(); ?></h1> </div>
        
        <div class="narrow_text">
            
                <?php if( get_the_content()){ ?> <div class="entry-content"> <?php the_content(); ?> </div><?php } ?>
        </div>   

       



   
		<?php  
        // Блок із дочірніми (сусідніми) матеріалами. // можна розмістити в товарах, на сторінках ...
        $parent_id = 0;
        $child_args_4 = array( 'post_parent' => $post->ID );
        $children = get_children( $child_args_4 );
        if(count($children)) { $parent_id = $post->ID;  }
        elseif($post->post_parent) { $parent_id = $post->post_parent;  }
        
        if( $parent_id ) : 
        $regions_args_5 = array (       
            'post_type'  => 'any',
            'post_parent' => $parent_id,
            'post__not_in'  => array($post->ID),
            'posts_per_page' => -1,
            'order' => 'ASC', 
            'orderby' => 'menu_order',
            'post_status' => 'publish'
            );
        
        $regi_query = new WP_Query($regions_args_5);
            if( $regi_query->have_posts() ) : ?>     
            
            <div class="services">  
                <div class="three-float-items">
                    <ul>
                    <?php while ($regi_query->have_posts()) : 
                      $regi_query->the_post(); ////// ?>
                        <li> 
                        <?php if ( has_post_thumbnail() ) { ?>
                            <?php the_post_thumbnail( 'medium-img' ); ?>	
                            <div class="services-title">
                            <h3><?php the_title(); ?></h3>
                            </div>
                            <?php the_content(); ?>		
                        <?php } ?> 		
                        </li>
                    <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        <?php endif;  wp_reset_query(); 
        endif; 
        ?>   
   

 

        
    



   
    </div>    <!-- content -->  
		
	<?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>	

</div> <!-- class="page blog" -->



<?php get_footer(); ?>