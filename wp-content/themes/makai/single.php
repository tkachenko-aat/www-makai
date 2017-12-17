<?php get_header(); ?>

<?php 
$post_type = get_post_type( $post );   
$taxonomy_names = get_object_taxonomies( $post );  $taxonomy = $taxonomy_names[0];
$terms = wp_get_post_terms($post->ID, $taxonomy);
$term_4 = $terms[0];
?>

<div class="post-w no_column blog type-<?php echo $post_type; ?> cat-<?php echo $term_4->parent; ?> cat-<?php echo $term_4->term_id; ?>">


<?php $post_id = $post->ID;
$post_id_gen = $post->ID; ?>

	<div class="content">
    <?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>

		<div class="single_banner">
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


			<?php /*
            <div class="on_single_banner">
			<?php $thumb_logo = '';
            if (class_exists('MultiPostThumbnails')) : 
            if ( MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'thumbnail-feat', NULL) ) { 
            $thumb_logo = MultiPostThumbnails::get_the_post_thumbnail(get_post_type(),'thumbnail-feat', NULL,'');
            }
            endif;	?> 
            <?php if ( $thumb ) { ?>
                <div class="single_logo"> <?php echo $thumb_logo; ?> </div>

	            <?php $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'short_description');?>
	            <?php if($short_descr_6['atr_value']) :
		            $short_descr = implode(', ', $short_descr_6['atr_value']); ?>
                    <div class="entry-content"><?php echo $short_descr ?></div>
	            <?php endif; ?>

            <?php } ?>
         </div>
            */ ?>

        </div> <!--single_banner -->
        
        
              
		<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'back_col'); ?>
		<?php if($short_descr_6['atr_value']) : 
        $back_col = implode(', ', $short_descr_6['atr_value']); ?>
        <?php endif; ?>
        <?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'font_col'); ?>
		<?php if($short_descr_6['atr_value']) : 
        $font_col = implode(', ', $short_descr_6['atr_value']); ?>
        <?php endif; ?> 
		
     
     	<div class="wrap_cont backgr_5" style="background-color:<?php echo $back_col ?>; color:<?php echo $font_col ?>;">
        
            	<div class="page_title"> <h1 style="color:<?php echo $font_col ?>;"><?php the_title(); ?></h1> </div>
                <?php if( get_the_content()){ ?> <div class="entry-content"> <?php the_content(); ?> </div><?php } ?>
                
                <div class="info-tags">
                    <?php   foreach($terms as $term){
                    if ($term->name == 'Our work') continue;
                    $categ = $term->name." "; ?>
                    <span><?php echo $categ; ?></span><?php
                    }?>
                </div>
        
        </div>             
      
      	<div class="top_gal">
			<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'top_gal'); ?>
            <?php if($short_descr_6['atr_value']) : 
            $top_gal = implode(', ', $short_descr_6['atr_value']); ?>
            <?php endif; ?> 
            <?php   if ( function_exists( 'envira_gallery' ) ) { envira_gallery( $top_gal ); }  ?>
        </div> 
       

        <div class="sec1 single-section">
        	
        	<div class="sec-info" style="color:<?php echo $back_col ?>;">
            	<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'sec_1_tit'); ?>
                <?php if($short_descr_6['atr_value']) : 
                $sec_1_tit = implode(', ', $short_descr_6['atr_value']); ?>
                <span style="color:<?php echo $back_col ?>;"><?php /* _e('1')*/ ?></span>
                <h2><?php echo $sec_1_tit ?></h2>
                <?php endif; ?>
                
                <?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'sec_1_text'); ?>
                <?php if($short_descr_6['atr_value']) : 
                $sec_1_text = implode(', ', $short_descr_6['atr_value']); ?>
                <div class="sec-text"><?php echo $sec_1_text ?></div>
                <?php endif; ?>
            </div>
        
        	<div class="sec-gal">
				<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'sec_1_gal'); ?>
                <?php if($short_descr_6['atr_value']) : 
                $sec_gal1 = implode(', ', $short_descr_6['atr_value']); ?>
                <?php endif; ?> 
                <?php   if ( function_exists( 'envira_gallery' ) ) { envira_gallery( $sec_gal1 ); }  ?>
            </div>
             
		</div>            
            
         <div class="sec2 single-section">
         
         	
        	<div class="sec-info" style="color:<?php echo $back_col ?>;">
            
				<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'sec_2_tit'); ?>
                <?php if($short_descr_6['atr_value']) : 
                $sec_2_tit = implode(', ', $short_descr_6['atr_value']); ?>  
                <span style="color:<?php echo $back_col ?>;"><?php /* _e('2') */?></span>
                <h2><?php echo $sec_2_tit ?></h2>
                <?php endif; ?>
                
                <?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'sec_2_text'); ?>
                <?php if($short_descr_6['atr_value']) : 
                $sec_2_text = implode(', ', $short_descr_6['atr_value']); ?>
                <div class="sec-text"><?php echo $sec_2_text ?></div>
                <?php endif; ?>
                
           
            </div>
            
            <?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'sec_2_gal'); ?>
            <?php if($short_descr_6['atr_value']) : 
            $sec_gal2 = implode(', ', $short_descr_6['atr_value']); ?>
            <?php endif; ?>
             
                <div class="sec-gal">
                	<?php  if ( function_exists( 'envira_gallery' ) ) { envira_gallery( $sec_gal2 ); }  ?>
                </div>
		</div>            
            
         <div class="sec3 single-section"> 
         
         	
            
        	<div class="sec-info" style="color:<?php echo $back_col ?>;">
				<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'sec_3_tit'); ?>
                <?php if($short_descr_6['atr_value']) : 
                $sec_3_tit = implode(', ', $short_descr_6['atr_value']); ?>
                <span style="color:<?php echo $back_col ?>;"><?php /*  _e('3')*/ ?></span>
                <h2><?php echo $sec_3_tit ?></h2>
                <?php endif; ?>
                <?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'sec_3_text'); ?>
                <?php if($short_descr_6['atr_value']) : 
                $sec_3_text = implode(', ', $short_descr_6['atr_value']); ?>
                <div class="sec-text"><?php echo $sec_3_text ?></div>
                <?php endif; ?>
            </div>
           
              
            <?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'sec_3_gal'); ?>
            <?php if($short_descr_6['atr_value']) : 
            $sec_gal3 = implode(', ', $short_descr_6['atr_value']); ?>
            <?php endif; ?> 
            <div class="sec-gal">
            	<?php  if ( function_exists( 'envira_gallery' ) ) { envira_gallery( $sec_gal3 ); }  ?>
            </div>
		</div>
        
        
        
        <div class="quote">
			<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'quote'); ?>
			<?php if($short_descr_6['atr_value']) :
				$quote = implode(', ', $short_descr_6['atr_value']); ?>
                <span style="color:<?php echo $back_col ?>;" class="left-mark">&#10077;</span>
				<?php echo $quote ?>
                <span style="color:<?php echo $back_col ?>;">&#10078;</span>

				<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'quote_name'); ?>
				<?php if($short_descr_6['atr_value']) :
				$quote_name = implode(', ', $short_descr_6['atr_value']); ?>
                <h3><?php echo  $quote_name ?></h3>
			<?php endif; ?>
			<?php endif; ?>
        </div>


        <div class="one_img">
        	
      		<?php if ( has_excerpt() ) { ?> <?php the_excerpt(); ?>   <?php } ?> 
          
        </div>


        <div class="single-nav">

        <div class="site_link" style="color:<?php echo $back_col ?>;">   
            <?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'site_link'); ?>
			<?php if($short_descr_6['atr_value']) : 
            $site_link = implode(', ', $short_descr_6['atr_value']); ?>
            
             <?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post_id, 'site_link_text'); ?>
			<?php if($short_descr_6['atr_value']) : 
            $site_link_text = implode(', ', $short_descr_6['atr_value']); ?>
            
            <span><?php _e('visit:') ?></span>
            <a href="<?php echo 'http://'.$site_link ?>" target="_blank"><?php echo $site_link_text ?></a>
            <?php endif; ?>
            <?php endif; ?>
        </div>   


<div class="share"><span><?php _e('Share:') ?></span><?php dynamic_sidebar( 'share' ); ?></div>
 <?php // [wp_social_sharing social_options='facebook,twitter,pinterest' facebook_text='Facebook' twitter_text='Twitter' googleplus_text='Google+' pinterest_text="Pinterest" show_icons='0' before_button_text='' text_position='' social_image=''] ?>


<?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>     
     

<div class="single-nav-inn">
 <?php 
 // get_posts in same custom taxonomy
$postlist_args = array(
	'post_type'       => 'projects',
        'posts_per_page'  => -1,
	// 'post__not_in' => array($post_id_gen),
   'orderby'         => 'menu_order',
   'order'           => 'ASC',
	'post_status' => 'publish'
); 
$postlist = get_posts( $postlist_args );

// get ids of posts retrieved from get_posts
$ids = array();
foreach ($postlist as $thepost) {
   $ids[] = $thepost->ID;
}

// get and echo previous and next post in the same taxonomy
$thisindex = array_search($post_id_gen, $ids);
$key_last = count($ids) - 1;
$previd = $ids[$thisindex-1];  if (empty($previd)) { $previd = $ids[$key_last]; }  // echo '<br>previd:'.$previd;
$nextid = $ids[$thisindex+1];  if (empty($nextid)) { $nextid = $ids[0]; }  // echo '<br>nextid:'.$nextid;

if ( !empty($previd) ) {
   echo '<a rel="prev" href="' . get_permalink($previd). '" class="navi prev"><i class="ha ha-arrow ha-arrow-left"></i> <span>'.__('Previous').'</span> </a>';
}
if ( !empty($nextid) ) {
   echo '<a rel="next" href="' . get_permalink($nextid). '" class="navi next"><i class="ha ha-arrow ha-arrow-right"></i> <span>'.__('Next').'</span> </a>';
}
 ?> 
 </div>	
</div> <!-- single-nav-->




</div> <!-- content -->



</div> 
















<?php /* /1 code fragments/single-lightb_addtocart_form_5.php // wow_e_shop *** product addtocart_form for configurable with table mode *** */ ?>


<?php /*
global $post;
$con = get_post_meta( $post->ID, 'sec_1_gal', true );
echo do_shortcode( $con );
?>      
      <?php
global $post;
$con = get_post_meta( $post->ID, 'sec_2_gal', true );
echo do_shortcode( $con );
*/ ?>  







<?php get_footer(); ?>