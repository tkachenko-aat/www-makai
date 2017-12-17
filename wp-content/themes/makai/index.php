<?php /* ***** *** 1 product in category */
/// if( $wp_query->post_count == 1 ) { wp_safe_redirect( get_permalink($post->ID) ); }
?>

<?php get_header(); ?>


   <?php 
   $post_type = get_post_type( $post );
   // global $wp_query;
	$queried_object = $wp_query->queried_object;	

	/* ******* 'normal', 'categories_list', 'mixed' ******* */
	$taxo_view = $queried_object->term_view; // 'normal', 'categories_list', 'mixed'
   ?>
   
   <div class="category no_column blog tax-<?php echo $queried_object->taxonomy; ?> type-<?php echo $post_type; ?> cat-<?php echo $queried_object->parent; ?> cat-<?php echo $queried_object->term_id; ?>">
      

   
   
   <div class="content"> 
   
   
   
  
 
   
<!-- <div class="page_title category_title title_content"> <h1><?php /*echo $queried_object->name; */?></h1> </div>-->


<?php include 'list-categories_or_pages.php'; /* *** list-categories_or_pages *** */ ?>
		


<?php // main content ?> <?php if(have_posts()) : ?>

<div class="projects-blocks" id="projects">
<div class="gutter-sizer"></div>


 
 <ul id="thumbs">

  <?php while(have_posts()) : the_post(); ?>  

    <?php 
	$post_type = get_post_type( $post );   
	$taxonomy_names = get_object_taxonomies( $post );  $taxonomy = $taxonomy_names[0];
	$terms = wp_get_post_terms($post->ID, $taxonomy);
	$term_4 = $terms;
	?>   
              
		<li class="item-thumbs <?php   foreach($terms as $term){
	    if ($term->slug == 'projects') continue;
	    $categ = $term->slug; 
	    echo $categ." ";
 		}?>">


				<a href="<?php the_permalink(); ?>" >
  
                   <?php $thumb = '';
					if ( has_post_thumbnail() ) { $thumb = get_the_post_thumbnail( $post->ID, 'big-img' ); }
		   			if (class_exists('MultiPostThumbnails')) : 
					if ( MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'thumbnail-simple', NULL) ) { 
					$thumb = MultiPostThumbnails::get_the_post_thumbnail(get_post_type(),'thumbnail-simple', NULL,'big-img');
					}
					endif;	?> 
					<?php if ( $thumb ) { ?>
                    <?php echo $thumb; ?> 	
                    <?php } ?> 
                    
                    <div class="project_info">
                        <h3><?php the_title(); ?></h3>
                        <div class="conte"> <?php the_content(); ?> </div>
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
 					             
 
 <?php endwhile; // posts query ?> 
 
 </ul> 

</div>

  <?php endif; ?>	<?php // -//- end main content ?>    
   		

	
    
    



 
 
 <?php if($wp_query->max_num_pages > 1) { ?> <?php /* Infinite Scroll, load more items */ ?>
<?php /* <div class="more_line"> <a class="button show-more" onclick="show_more_items(this)"><?php _e('More...'); ?></a> </div> */ ?>
	<?php } ?>
    <?php /* Infinite Scroll - footer.php: window.onscroll = function() { set_fixed_top9(); infi_scroll(); } */ ?>
	
	<?php if (function_exists('wp_corenavi')) wp_corenavi(''); ?> <?php /* don"t delete this; you can use "display: none;" */ ?>	
 
 </div> <!-- content -->
 
            
    
</div> <!-- class="category blog" -->
   


<?php get_footer(); ?>