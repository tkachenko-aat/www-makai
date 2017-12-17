<!--  <div class="front_sliders"> </div> -->
<?php 
/* 
$products_type_arr = array( 
		'popular_prod' => array('label' => __('Popular products'), 'orderby' => 'views', 'order' => 'desc', 'meta_query' => ''),
		'recomend_prod' => array('label' => __('Recommended products'), 'orderby' => 'date', 'order' => 'desc', 'meta_query' => 'recomend_prod'),
		
	); // 
 */

//// no_feat_image 
$no_feat_image = '<img src="'.get_template_directory_uri().'/images/no_feat_image.png" class="no_feat" alt="no image" />'; 
$options_45 = get_option('site_media_settings_4'); $img_8_id = $options_45['no_feat_image_id']; 
if($img_8_id and wp_attachment_is_image($img_8_id)) { 
$no_feat_image = wp_get_attachment_image( $img_8_id, 'medium-img' ); 
}
 
$products_type_arr = WOW_Product_List_Func::get_front_products_type_arr();
$type_arr_2 = array('recomend_prod', 'bestseller_prod', 'popular_prod', 'action_prod', 'new_prod', 'special_price');
if(is_tax() or is_category()) { $type_arr_2 = array('recomend_prod', 'popular_prod'); }
 
 
foreach($type_arr_2 as $prod_type) : ///////////// ****
$prod_args = WOW_Product_List_Func::get_front_products_args($prod_type, 15); // 15 - заг. к-сть товарів

// echo '<pre>'; print_r($prod_args); echo '</pre>';

$prod_s_query = new WP_Query($prod_args);

if( $prod_s_query->have_posts() ) : 

$prod_label = $products_type_arr[$prod_type]['label'];
$attr_labels = WOW_Attributes_Front::get_attribute_labels($prod_type);
if($attr_labels['frontend_label_2']) { $prod_label = $attr_labels['frontend_label_2']; } // 
?>


<div class="box-content co_<?php echo $prod_type ?>">
    
<?php    
// $slider_count = 4;
// if(is_tax() or is_category()) { $slider_count = 3; }
// $slider_scroll = 1;
// $slider_speed = 300;
?>

<?php /* jquery  */ ?> <?php /* script jCarousel */ ?>
    
     <div class="tit"><h4><?php echo $prod_label ?></h4> <a class="view_all" href="<?php bloginfo('url'); echo '/advanced/?par='.$prod_type; ?>"><?php _e('View all') ?></a></div>   
  
<div class="hslider-container">   

<script type="text/javascript">
	window.addEventListener("DOMContentLoaded", function() { // after jQuery is loaded async. 
jQuery(document).ready(function($) {
        var slides_count = <?php echo $prod_s_query->post_count; ?>;  var count_42 = 1;
		var jcarousel = $('.<?php echo $prod_type ?>.horizontal-slider');
        jcarousel
            .on('jcarousel:reload jcarousel:create', function () {
                var carousel = $(this),
                    width = carousel.innerWidth();
                if (width >= 750) { width = width / 4; count_42 = slides_count - 4; } 
				else if (width >= 550) { width = width / 3; count_42 = slides_count - 3; }
                else if (width >= 320) { width = width / 2; count_42 = slides_count - 2; }
                carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
				if(count_42 <= 0) { jcarousel.parent().addClass("no_slide_navi"); }
            })
            .jcarousel({ wrap: 'circular' });

        $('.hslider-prev.<?php echo $prod_type ?>').jcarouselControl({ target: '-=1' });
        $('.hslider-next.<?php echo $prod_type ?>').jcarouselControl({ target: '+=1' });

        $('.controls.<?php echo $prod_type ?>')
            .on('jcarouselpagination:active', 'a', function() { $(this).addClass('activeSlide'); })
            .on('jcarouselpagination:inactive', 'a', function() { $(this).removeClass('activeSlide'); })
            .on('click', function(e) { e.preventDefault(); })
            .jcarouselPagination({
                perPage: 1,
                item: function(page) { return '<a href="#' + page + '">' + page + '</a>'; }
            });
});
    }, false); // __ after jQuery is loaded
</script>
       
            
   	<div class="<?php echo $prod_type ?> horizontal-slider">        
			
            <ul>                
				<?php while ($prod_s_query->have_posts()) : 
				$prod_s_query->the_post(); 		
				// global $more;  $more = 0;  // необхідно для тегу <!--more--> ?>
					<li class="item">
                    <div class="slider_lift">
                        
           <div class="prod-image">
	<a class="product-image" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium-img' ); } else { echo '<div class="inn">'.$no_feat_image.'</div>'; } ?></a> 					
           </div>
		<?php $content = get_the_content();  $cutti_num = 240; ?>
        <h5 class="product-name"><a href="<?php the_permalink(); ?>" title="<?php // the_title(); ?>" aria-label="<?php echo samorano_short_content($content, $cutti_num); ?>"><?php echo the_title(); ?></a></h5>              
              <div class="actions">
      		<?php $product_price = WOW_Attributes_Front::product_get_price(); ?>
        <div class="price-box"><?php echo $product_price ?></div>  
                    
                    <?php $stock_2 = get_post_meta ($post->ID, 'stock', true); ?>               
					<?php if($stock_2 > 0 or $stock_2 == '') : ?>
      <?php $product_type = get_post_meta ($post->ID, 'product_type', true); ?>
            <div class="addtocart"> <a <?php if($product_type == 'configurable') { ?>href="<?php the_permalink(); ?>"<?php } else { ?>onclick="addtocart('<?php the_ID() ?>', '1')"<?php } ?> class="button btn-cart"><?php _e('Add to cart') ?></a> </div>
                    <?php else: ?>
              <div class="availability out-of-stock"><span><?php _e('Out of stock') ?></span></div>
                    <?php endif; ?>
              </div>
					 <?php // $count = get_post_meta($post->ID, 'views', true); echo $count; ?>
                        </div>
					</li>
                    
				<?php endwhile; // ?>
			</ul>
	</div>

    
    <?php if ($prod_s_query->post_count > 1) : ?>
 <div class="hslider-nav hslider-prev <?php echo $prod_type ?>"> <i class="fa fa-chevron-left"></i> </div>
 <div class="hslider-nav hslider-next <?php echo $prod_type ?>"> <i class="fa fa-chevron-right"></i> </div>
        
        <div class="controls <?php echo $prod_type ?>"> </div>   
    <?php endif; ?>        

    
</div>
 
   
</div>

<?php endif;  wp_reset_query(); ?>

<?php endforeach; // ($products_type_arr as $products_type) ///// ********* ?>


<?php if (is_front_page()) : ?>
<div class="link_advanc"><a href="<?php bloginfo('url'); echo '/advanced/'; ?>"><?php _e('Advanced search') ?></a></div>
<?php endif; ?>
