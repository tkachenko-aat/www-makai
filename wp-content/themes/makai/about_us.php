<?php
/*
Template Name: About us
*/
?>


<?php get_header(); ?>


<div class="page no_column blog about">
   
   <?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>  
    
    <div class="content">
    
         <div class="page_title"> <h1><?php the_title(); ?></h1> </div>  
         <div class="narrow_text">         
            <?php if( get_the_content()){ ?> <div class="entry-content"> <?php the_content(); ?> </div><?php } ?>   
         </div>

        <?php 
        $wide_slider = 1; // 0 - normal slider, 1 - wide slider 
        
        $sl_posts_args = array (
            'post_type'   => 'any', // 'post';  'any' - усі типи 
            'posts_per_page' => 30, // -1 
            'meta_key' => 'show_in_main_slider',
            'meta_value' => '1',
            // 'order' => 'DESC',   
            // 'orderby' => 'date', // 'title'
            'post_status' => 'publish'
        );
        $my_query_2 = new WP_Query($sl_posts_args);
        if( $my_query_2->have_posts() ) { ?>
        
    
    
    <script type="text/javascript">
        window.addEventListener("DOMContentLoaded", function() { // after jQuery is loaded async. 
    jQuery(document).ready(function($) {
        
        $('#main_slider_slides').slick({
        slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      fade: true,
      //asNavFor: '#slider-nav'
        });
        
        $('#slider-nav').slick({
      slidesToShow: 6,
      slidesToScroll: 0,
      arrows: false,
      asNavFor: '#main_slider_slides',
      //dots: true,
      centerMode: false,
      centerPadding: 0,
      focusOnSelect: true
    });
    
        $(".slide_more").click(function(){
        var block_1 = $(this);
            $(".slide").not(".slick-active").removeClass("my-active_slide");
            block_1.closest(".slide").toggleClass("my-active_slide");
            //block_1.html(block_1.text() == "<?php _e('Read more') ?>" ? "<?php _e('Close') ?>" : "<?php _e('Read more') ?>");
        }); 
    
    });
    
        }, false); // __ after jQuery is loaded
    </script>
    
    <div class="main_slider<?php if($wide_slider == 1) { ?> wide_slider<?php } ?>">   <!-- ... main_slider wide_slider -->           
    <div class="cycle_slider">
        <div class="items" id="main_slider_slides">
            <?php $num_1 = 0; ?>
            <?php while ($my_query_2->have_posts()) : 
            $num_1++;
            $my_query_2->the_post(); 
            global $more;  $more = 0; 
            ?>        

            <?php if ( has_post_thumbnail() ) { ?>
            <div class="slide" <?php if($num_1 == 1) { ?>style="display:block;"<?php } ?>>
            <div class="dark_slide"> 
            <div class="slide_thumb">
            <?php $thumb_id = get_post_thumbnail_id(); 
             if($wide_slider == 1) { echo '<div class="wide_img">'.salas_image_resize($thumb_id, 1920, 600).'</div>'; }
            ?>
            </div>

                <div class="post_text">
                    <?php the_content(); ?>
                </div>

                <div class="post_text_content">
                    <h3> <?php the_title(); ?></h3>
                    <div class="slide_more"><span class="more"><?php _e('<span class="plus">+</span> Read more') ?></span><span class="close"><?php _e('Close') ?></span></div>
                </div>



            </div> 
            </div>
            <?php } ?> 
            <?php endwhile; ?>
        </div>        
     </div> 
        
 </div> <!-- main_slider -->
 
        
        <div class="cycle_slider">
            <div class="items" id="slider-nav">
                <?php $num_1 = 0; ?>
                <?php while ($my_query_2->have_posts()) : 
                    $num_1++;
                $my_query_2->the_post(); 
                global $more;  $more = 0;  // необхідно для тегу <!--more-->
                ?>        
                
                <?php if ( has_post_thumbnail() ) { ?>
                <div class="slide" <?php if($num_1 == 1) { ?>style="display:block;"<?php } ?>> 
                <div class="slide_thumb">
                <div class="inn">
                    <?php $thumb_id = get_post_thumbnail_id(); echo salas_image_resize( $thumb_id, 165, 95 ); ?>
                </div> 
                </div>
                </div> 
                <?php } ?> 
                <?php endwhile; ?>
            </div>        
        </div>    
        

<?php } wp_reset_query(); ?>




        <?php  $short_descr_5 = WOW_Attributes_Front::post_view_one_attribute($post->ID, 'text_field'); ?>
        <?php if($short_descr_5['atr_value']) : $short_descr_1 = implode(', ', $short_descr_5['atr_value']); ?>
        <h2><?php echo $short_descr_1 ?>   </h2>        
        <?php endif; ?> 

   



<?php    
    $options4 = get_option('site_add_settings_4');
    $cat_id = $options4['team_ids']; 
    $term2 = get_term($cat_id, 'team-cat');
    $cat_title = $term2->name;
    $posts_args_7 = array (       
    'post_type'  => 'team',
    'posts_per_page'  => 20,
    'order' => 'ASC',   
    'orderby' => 'menu_order',      
    'tax_query' => array(
        array (
        'taxonomy' => 'team-cat', // 'category'
        // 'field' => 'term_id', // 'slug'
        'terms' => $cat_id // 'my-slug2'
        )
    ),
    'post_status' => 'publish'
);
    
    $query_7 = new WP_Query($posts_args_7);   
    if( $query_7->have_posts() ) { 
    ?>
    
    
        <div class="team-blocks">
            <ul>        
            <?php   
            while ($query_7->have_posts()) : 
            $query_7->the_post(); 
            global $more;  $more = 0;  // необхідно для тегу <!--more-->
            ?>  
    
                <li>    
                    <?php the_post_thumbnail( 'medium-img' ); ?>
                    <h3><?php the_title(); ?></h3>
                    <div class="entry-content"> <?php the_content(); ?> </div>
                </li>
    
            <?php endwhile; ?>
            </ul>

        </div>
    <?php }  wp_reset_query(); ?> 
    


<?php    
    $options4 = get_option('site_add_settings_4');
    $cat_id = $options4['value_ids']; 
    $term2 = get_term($cat_id, 'values-cat');
    $cat_title = $term2->name;
    $posts_args_7 = array (       
    'post_type'  => 'values',
    'posts_per_page'  => 20,
    'order' => 'ASC',   
    'orderby' => 'menu_order',      
    'tax_query' => array(
        array (
        'taxonomy' => 'values-cat', // 'category'
        // 'field' => 'term_id', // 'slug'
        'terms' => $cat_id // 'my-slug2'
        )
    ),
    'post_status' => 'publish'
);


    $query_7 = new WP_Query($posts_args_7);   
    if( $query_7->have_posts() ) { 
    ?>
    
<?php /*    <div class="blocks__btn">View our work</div>*/ ?>

<?php
/*        
        <?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post->ID, 'title_field'); ?>
        <?php if($short_descr_6['atr_value']) : $short_descr = implode(', ', $short_descr_6['atr_value']); ?>
        <h2><?php echo $short_descr ?>   </h2>        
        <?php endif; ?> 
        
        

        
        <div class="our_values">

        
            <ul>        
            <?php   
            while ($query_7->have_posts()) : 
            $query_7->the_post(); 
            global $more;  $more = 0;  // необхідно для тегу <!--more-->
            ?>  
    
                <li>
                    
                    
                     
                    <div class="value-tit">
                        <?php the_title(); ?>
                    </div>
                     <div class="value-cont">
                      <?php the_content(); ?>   
                      </div>
              
                </li>
    
            <?php endwhile; ?>
            </ul>
            

        
        </div>
*/?>
    <?php }  wp_reset_query(); ?>  



   
    
        

    </div>    <!-- content -->  
        
    <?php // -//- end main content ?> <?php endwhile; ?>    <?php else : ?>     <?php endif; ?> 

</div> <!-- class="page blog" -->



<?php get_footer(); ?>