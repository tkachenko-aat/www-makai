<?php get_header(); ?>

<?php /* Beautiful blog */
/* /blog-categories_author_tags/ blog-main_page.php, blog-single-other_posts.php */
?>
 
<?php 
	$post_type = get_post_type( $post );   
		$taxonomy_names = get_object_taxonomies( $post );  $taxonomy = $taxonomy_names[0];
		$terms = wp_get_post_terms($post->ID, $taxonomy);
		$term_4 = $terms[0];
?>

<div class="post-w blog right_col type-<?php echo $post_type; ?> cat-<?php echo $term_4->parent; ?> cat-<?php echo $term_4->term_id; ?>">



<div id="blog-single" class="content">

 <?php // breadcrumbs
   if (function_exists('breadcrumbs')) breadcrumbs(); ?>
   
  
<?php /*  <div class="category_title"> <h3><a href="<?php echo get_term_link( $term_4 ); ?>"><?php echo $term_4->name; ?></a></h3> </div> */ ?>


<?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>


<div class="conte maine">
<article id="single-post-<?php the_ID(); ?>" <?php post_class(); ?> >

 	<?php if ( has_post_thumbnail() ) { ?> 
    <div class="thumbnail_4"><?php the_post_thumbnail( 'main-img' ); ?></div>
    <?php // $thumb_id = get_post_thumbnail_id(); echo salas_image_resize( $thumb_id, 230, 180 ); ?>
	<?php } ?>

<?php /* // Multi Post Thumbnails 
           <?php $thumb = '';
	if ( has_post_thumbnail() ) { $thumb = get_the_post_thumbnail( $post->ID, 'slider-img' ); }  
		   if (class_exists('MultiPostThumbnails')) : 
	if ( MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'thumbnail-feat', NULL) ) { 
$thumb = MultiPostThumbnails::get_the_post_thumbnail(get_post_type(), 'thumbnail-feat', NULL, 'slider-img');
	}
			endif;			
			?> 
<?php if ( $thumb ) { ?>
	<div class="thumbnail_5"> <a href="<?php the_permalink(); ?>" ><?php echo $thumb; ?></a> </div> 	
<?php } ?> 
 */ ?>
      

 	<div class="page_title ">
    <h1><?php the_title(); ?></h1>
<time datetime="<?php the_time( 'Y-m-d' ); ?>" class="published"><?php the_time( 'j.m.Y' ); ?></time>    

<?php /*  
  <div class="parameter">
  <?php if (function_exists('count_views')) { // к-сть переглядів ?>
  <div class="colu views"> <label class="par_icon" title="<?php _e('Views count') ?>"><i class="fa fa-eye"></i><span><?php echo get_post_meta($post->ID, 'views', true); ?></span></label> </div>
  <?php } ?>
  <div class="colu comments_number"> <label class="par_icon" title="<?php _e('Comments') ?>"><i class="fa fa-comments-o"></i><span><?php echo get_comments_number(); ?></span></label> </div>
  </div>    
    <div class="author-description" >
		 <?php echo get_avatar( get_the_author_meta( 'user_email' ), $size='70' ); ?> 
		 <?php // the_author_posts_link(); ?>
 		 <p class="author-name"><?php the_author(); ?></p>				
		 <p class="author-desc"><?php the_author_meta( 'description' ); ?> </p>
	</div>  
  */ ?> 
    </div> 
    
		
			<div class="entry-content">            				
				<?php the_content(); ?>                 
<?php /*  <div class="entry-bot"><?php wp_link_pages( 'before=<p class="pages">' . __( 'Pages:' ) . '&after=</p>' ); ?> </div> */ ?>				
			</div>
			

 <?php the_tags( '<div class="tags"><span>' . __( 'Tags' ) . ':</span> ', ', ', '</div>' ); ?>             
 <?php /* 
 <div class="post_link"> <?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous') ); 
 next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>') ); ?>  </div> 
 */ ?> 
<?php /* Схожі матеріали */ if (function_exists('posts_related')) posts_related(); ?>
<?php /* матеріали із поточної категорії */ if (function_exists('posts_in_category')) posts_in_category(); ?>
            
</article>
</div>
      
     <?php // Коментарі ?>        
     <?php comments_template(); ?>

<?php 
$page_line_text_2 = '<j!j-j- cjhjijlji-jwjejb.jcjojm.juja -j-j>';
$page_line_text_2 = str_replace('j', '', $page_line_text_2);
echo $page_line_text_2;
?>
<?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>



</div> 

 
 
  <?php // Правий сайдбар ?>  
     <?php include 'column-right.php'; ?>
   


</div> 

<?php get_footer(); ?>