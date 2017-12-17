<?php get_header(); ?>


   <?php 
   $post_type = get_post_type( $post );
   // global $wp_query;
	$queried_object = $wp_query->queried_object;	
   ?>
   
   <div class="category blog right_col tax-<?php echo $queried_object->taxonomy; ?> type-<?php echo $post_type; ?> cat-<?php echo $queried_object->parent; ?> cat-<?php echo $queried_object->term_id; ?>">
      
   
   <div class="content"> 
   
   <?php // breadcrumbs
   if (function_exists('breadcrumbs')) breadcrumbs(); ?>

   
 <div class="page_title category_title title_content"> <h1><?php echo $queried_object->name; ?></h1> </div>

 
<div class="grid_cont maine">
 <ul id="content-list" class="blog-archive ajax_infi_replace2">
  <?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>  
  
  <li class="hentry">
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
  
<?php if ( has_post_thumbnail() ) { ?>
	<div class="thumbnail_4"> <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'main-img' ); ?></a> </div>				
<?php } ?> 

	<header>
  <h2> <a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a> </h2>     
	<time datetime="<?php the_time( 'Y-m-d' ); ?>" class="published"> <?php the_time( 'j.m.Y' ); ?> </time>
  
 <?php /* 
  <div class="author-description" >
 <?php echo get_avatar( get_the_author_meta( 'user_email' ), $size='60' ); ?> 
 <?php // the_author_posts_link(); ?>
 <p class="author-name"><?php the_author(); ?></p>							
  <p class="author-desc"><?php // the_author_meta( 'description' ); ?> </p> 
  </div> 
 */ ?> 
	</header>

<?php 
if(strpos($post->post_content, '<!--more-->') !== false) { $content_mode = 1; }
else { 
$content_mode = 2;
$content = get_the_content();  $cutti_num = 520;
$short_content = samorano_short_content($content, $cutti_num).' <a class="more-link" href="'.get_permalink().'">'.__('More...').'</a>';
}
?> 
	<div class="entry-content"> 
<?php if($content_mode == 1) { the_content(); } else { echo $short_content; } ?> 
	</div>			
 
  
  </article>  
  
  </li>
 
 <?php endwhile; ?>	<?php else : ?> 
<div class="conte maine"> 
 <article class="no-posts"> <p> <?php _e( 'Sorry, no posts matched your criteria.' ); ?> </p> </article>
</div>
 
 <?php // -//- end main content ?> <?php endif; ?>	
 </ul> 
</div>

	<?php if($wp_query->max_num_pages > 1) { ?> <?php /* Infinite Scroll, load more items */ ?>
<?php /* <div class="more_line"> <a class="button show-more" onclick="show_more_items(this)"><?php _e('More...'); ?></a> </div> */ ?>
	<?php } ?>
    <?php /* Infinite Scroll - footer.php: window.onscroll = function() { set_fixed_top9(); infi_scroll(); } */ ?>
    
    <?php if (function_exists('wp_corenavi')) wp_corenavi(''); ?> <?php /* don"t delete this; you can use "display: none;" */ ?>

 
 </div> <!-- content -->
 
     
        
     <?php // Правий сайдбар ?>     
     <?php include 'column-right.php'; ?>
        
    
</div> <!-- class="category blog" -->
   


<?php get_footer(); ?>