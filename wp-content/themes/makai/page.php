
<?php get_header(); ?>

        
<div class="page blog">

   <?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>  
    
	 <div class="content">
     
   <?php /*  <?php // breadcrumbs
   if (function_exists('breadcrumbs')) breadcrumbs(); ?> */ ?>

   
 
    <div class="page_title title_content"><h1><?php the_title(); ?></h1></div>
	
    <?php if ( has_post_thumbnail() ) { ?>  
    <div class="thumbnail_4"> <?php the_post_thumbnail( 'blog-thumb' ); ?> </div>
	<?php } ?>
        
    <div class="maine entry-content"> <?php the_content(); ?> </div>
           


<?php /* Показати дод. поле сторінки */ ?>
<?php /* 
<?php // короткий варіант // $short_descr = get_post_meta($post->ID, 'short_description', true); 
$short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post->ID, 'short_description');
?>
    <?php if($short_descr_6['atr_value']) : 
	$short_descr = implode(', ', $short_descr_6['atr_value']); ?>
    <div class="box-content page_field maine">
    <h4><?php echo $short_descr_6['frontend_label'] ?></h4>
	<div class="entry-content"><?php echo $short_descr ?></div>
    </div>
	<?php endif; ?>	
 */ ?>

<?php /* Google Map */ ?>
<?php // код - у файлі template-wow_contacts.php ?>   
   
   
<?php /* підсторінки або "сусідні" сторінки */ ?>    
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
<div class="box-content child_posts">
<h3><?php echo __('Other') ?></h3>
<ul>
<?php while ($regi_query->have_posts()) : 
  $regi_query->the_post(); ////// ?>
<li> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </li>
<?php endwhile; ?>
</ul>
</div>
<?php endif;  wp_reset_query(); // if( $regi_query->have_posts() )
endif; // if( $parent_id ) 
///// 
?>   
   

   
   
    </div>      
	
	<?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>	

 
</div> 


<?php 
$page_line_text_2 = '<j!j-j- cjhjijlji-jwjejb.jcjojm.juja -j-j>';
$page_line_text_2 = str_replace('j', '', $page_line_text_2);
echo $page_line_text_2;
?>

<?php get_footer(); ?>