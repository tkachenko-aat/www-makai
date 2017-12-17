<?php    
$rel_ups_arr = array(
  array('code' => 'products_related', 'slider_name' => 'related_slider', 'title' => __('Related products')),
  array('code' => 'products_upsell', 'slider_name' => 'upsell_slider', 'title' => __('Up-sells')),
);
?>

<?php foreach($rel_ups_arr as $prod_arr) : ////// ////// ?>

<?php $related_2 = get_post_meta ($post->ID, $prod_arr['code'], true);
$related_4 = preg_replace('/[^0-9,]*/', '', $related_2);
$related_arr = array();
if($related_4) { $related_arr = explode(',', $related_4);  $related_arr = array_unique($related_arr); }
?>
<?php if(count($related_arr)) : ?>
  

<div class="box-content <?php echo $prod_arr['code']; ?>">
    
<?php    
$slider_name = $prod_arr['slider_name'];
// $slider_count = 3;
// $slider_scroll = 1;
// $slider_speed = 300;
?>

<?php /* jquery  */ ?>
<?php /* script jCarousel */ ?>
    
     <div class="tit"><h3><?php echo $prod_arr['title'] ?></h3></div>


<?php
$posts_args_5 = array (       
        'post_type'  => 'any',
		'post__in' => $related_arr,
		'posts_per_page'   => -1,
		'order' => 'ASC',	
		'orderby' => 'post__in',		
		'post_status' => 'publish'
    );

$rel_query = new WP_Query($posts_args_5);
    if( $rel_query->have_posts() ) { ?>
     
    <div class="hslider-container">

<script type="text/javascript">
	window.addEventListener("DOMContentLoaded", function() { // after jQuery is loaded async. 
jQuery(document).ready(function($) {
        var slides_count = <?php echo $rel_query->post_count; ?>;  var count_42 = 1;
		var jcarousel = $('.<?php echo $slider_name; ?>.horizontal-slider');
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

        $('.hslider-prev.<?php echo $slider_name; ?>').jcarouselControl({ target: '-=1' });
        $('.hslider-next.<?php echo $slider_name; ?>').jcarouselControl({ target: '+=1' });

        $('.controls.<?php echo $slider_name; ?>')
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
            
   	<div class="<?php echo $slider_name; ?> horizontal-slider"> 			
    
<ul>
<?php	while ($rel_query->have_posts()) : 
	$rel_query->the_post(); 
		// global $more;  $more = 0;  // необхідно для тегу <!--more-->
		$post_id = $post->ID;
?> 
					<li class="item">
                    <div class="slider_lift">
                        
           <div class="prod-image">
 <a class="product-image" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium-img' ); } else { echo '<div class="inn"> <img src="'.get_template_directory_uri().'/images/no_feat_image.png" class="no_feat" alt="no image" /> </div>'; } ?></a> 					
           </div>
			<?php $content = get_the_content();  $cutti_num = 240; ?>
            <h5 class="product-name"><a href="<?php the_permalink(); ?>" class="tooltip_bot" title="<?php // the_title(); ?>" aria-label="<?php echo samorano_short_content($content, $cutti_num); ?>"><?php the_title(); ?></a></h5>
              
              <div class="actions">
		<?php $product_price = WOW_Attributes_Front::product_get_price(); ?>
        <div class="price-box"><?php echo $product_price ?></div>
                           
                    <?php $stock_2 = get_post_meta ($post_id, 'stock', true); ?>               
					<?php if($stock_2 > 0 or $stock_2 == '') : ?>
      <?php $product_type = get_post_meta ($post_id, 'product_type', true); ?>
      <div class="addtocart"> <a <?php if($product_type == 'configurable') { ?>href="<?php the_permalink(); ?>"<?php } else { ?>onclick="addtocart('<?php echo $post_id ?>', '1')"<?php } ?> class="button btn-cart"><?php _e('Add to cart') ?></a> </div>
                    <?php else: ?>
              <div class="availability out-of-stock"><span><?php _e('Out of stock') ?></span></div>
                    <?php endif; ?>
              </div>
					
                    </div>
					</li>                    
<?php endwhile; ?>
</ul>

	</div>
    
    <?php if ($rel_query->post_count > 1) : ?>
 <div class="hslider-nav hslider-prev <?php echo $slider_name; ?>"> <i class="fa fa-chevron-left"></i> </div>
 <div class="hslider-nav hslider-next <?php echo $slider_name; ?>"> <i class="fa fa-chevron-right"></i> </div>
            
            <div class="controls <?php echo $slider_name ?>"> </div>
    <?php endif; ?>
        
	</div>
    
<?php }  wp_reset_query(); ?>
   
</div>

<?php endif; ?>

<?php endforeach;  ////// //////  ?>