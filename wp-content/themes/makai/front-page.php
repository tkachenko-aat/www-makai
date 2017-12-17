<?php
/*
Template Name: Homepage Template
*/
?>

<?php get_header(); ?>


<div class="home_page no_column blog"> 

	<?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
      

	<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );?>
	<div class="fullscreen" style="background-image: url('<?php echo $thumb['0'];?>')">
	 
        <div class="home_text inn">
            <div class="narrow_text">
                <div class="page_title"> <h1><?php the_title(); ?></h1> </div>
                    <?php if( get_the_content() ) { ?> <div class="entry-content">  <?php the_content(); ?> </div> <?php } ?>
                <div class="arrow_link">
                    <a href="#arrow_link_block"><i class="fa" aria-hidden="true"></i></a>
                </div>          
            </div> 
            
        </div>
        
	</div> <!--fullscreen -->


   <div class="content">	 

        <div id="arrow_link_block">
        	<div class="narrow_text"> 
            	<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post->ID, 'title_field'); ?>
                <?php if($short_descr_6['atr_value']) : $short_descr = implode(', ', $short_descr_6['atr_value']); ?>
                	<h2><?php echo $short_descr ?>  </h2>         
                <?php endif; ?>	
                    
                <?php the_excerpt(); ?> 
                
        	</div>
        </div> <!--arrow_link_block -->



   
   

   
<?php    
    $options4 = get_option('site_add_settings_4');
    $cat_id = $options4['home_blog_cat_ids']; 
    $term2 = get_term($cat_id, 'projects-cat');
    $cat_title = $term2->name;
    $posts_args_7 = array (       
	'post_type'  => 'projects',
	'posts_per_page'  => 20,
	'order' => 'DESC',	
	//'orderby' => 'menu_order',		
	'tax_query' => array(
		array (
		'taxonomy' => 'projects-cat', // 'category'
		// 'field' => 'term_id', // 'slug'
		'terms' => $cat_id // 'my-slug2'
		)
	),
	'post_status' => 'publish'
);

    
    $query_7 = new WP_Query($posts_args_7);   
    if( $query_7->have_posts() ) { 
    ?>
    
    
    
    
		<div class="projects-blocks wide_text">
        
        <?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post->ID, 'short_description'); ?>
                <?php if($short_descr_6['atr_value']) : $short_descr = implode(', ', $short_descr_6['atr_value']); ?>
                	<h2><?php echo $short_descr ?>  </h2>         
                <?php endif; ?>	
    	
        
            <ul>		
            <?php	
            while ($query_7->have_posts()) : 
            $query_7->the_post(); 
            global $more;  $more = 0;  // необхідно для тегу <!--more-->
	            $taxonomy_names = get_object_taxonomies( $post );  $taxonomy = $taxonomy_names[0];
	            $terms = wp_get_post_terms($post->ID, $taxonomy);

	            $content = get_the_content();
	            $short_content = apply_filters('the_title', get_post_meta($post->ID, 'short_description', true));
	            if($short_content) { $content = $short_content; }
            ?> 
            <?php $order = get_post_field( 'menu_order', $post->ID); ?> 
    
                <li class="item" <?php if($order == 1) { ?> style="display:none;"<?php } ?>>
               		
  					<a href="<?php the_permalink(); ?>">
                    
                    <?php if ( has_post_thumbnail() ) { ?>  
                     <div class="banner_img"><?php the_post_thumbnail( 'banner' ); ?> </div>
                    <?php } ?>
                    
                    <?php $thumb = '';
					if ( has_post_thumbnail() ) { $thumb = get_the_post_thumbnail( $post->ID, 'mobile-img' ); } 
		   			if (class_exists('MultiPostThumbnails')) : 
					if ( MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'thumbnail-simple', NULL) ) { 
					$thumb = MultiPostThumbnails::get_the_post_thumbnail(get_post_type(),'thumbnail-simple', NULL,'mobile-img');
					}
					endif;	?> 
					<?php if ( $thumb ) { ?>
                        <div class="mobile_img"> <?php echo $thumb; ?> </div> 	
                    <?php } ?> 
                    
                    
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="project_info">
                    <?php else : ?>
                    <div class="project_info project_info-visible">
                    <?php endif; ?>
						<h3><?php the_title(); ?></h3>
                        <div class="con"> <?php echo $content; ?> </div>
                        <div class="info-tags">
		                    <?php foreach($terms as $term_1) { ?>
                                <span><?php echo $term_1->name ?><em>, </em></span>
		                    <?php } ?>
                        </div>
                    </div>
                    
                          
                    </a> 
                   <div class="proj_but_mob">
                   <a href="<?php the_permalink(); ?>" class="button"> More info</a>
                   </div>
              
                </li>
    
            <?php endwhile; ?>
            </ul>
            
            

    	</div>
	<?php }  wp_reset_query(); ?>    


  
	<?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>	


	</div>    <!-- content -->  
	

</div> <!-- class="home_page blog" -->



<?php get_footer(); ?>