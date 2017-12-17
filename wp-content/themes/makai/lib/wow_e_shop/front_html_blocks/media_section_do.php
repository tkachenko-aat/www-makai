<div class="images-box">
<?php // $product_type = get_post_meta($post->ID, 'product_type', true); 
// $con_main_prod_id = WOW_Attributes_Front::configurable_prod_default(); 
$post_id = $post->ID;
$post_id_gen = $post->ID;

if(!has_post_thumbnail()) {
if($con_main_prod_id) { $post_id_gen = $con_main_prod_id; }
if(wp_get_post_parent_id($post_id)) { $post_id_gen = wp_get_post_parent_id($post_id); }
}
?>


	<?php if ( has_post_thumbnail($post_id_gen) ) : ?> 
<?php 
$po_arr = array_keys($_POST);
$main_img_id = "product-view-image-".$post_id;
 $img_size = 'main-img'; $onclick_action = 'onclick'; 
/* else { $img_size = 'medium-img'; $onclick_action = 'onMouseOver'; }*/
	
$gallery_arr = WOW_Attributes_Front::image_gallery();

// $gal_mode = 2; // 0 - режим "заміни"; 1 - режим "суто Lightbox"; 2 - режим "ЛУПИ"; //
$options_5 = get_option('wow_settings_arr');
$gal_mode = 0;  if($options_5['wow_gal_mode']) { $gal_mode = $options_5['wow_gal_mode']; } 
if(in_array('ajax_loadd', $po_arr) and $gal_mode == 1) { $gal_mode = 0; }

$id_edic = 1;  if($gal_mode == 1) { $id_edic = $post_id; }
?>

<?php $thumbnail_id = get_post_thumbnail_id($post_id_gen); 
$img_arr24 = wp_get_attachment_image_src( $thumbnail_id, ''); ?>
<?php /*
<div class="main-img" id="<?php echo $main_img_id ?>"> 

<a 
<?php if(!is_single()) { ?>href="<?php the_permalink() ?>"
<?php } elseif( $gal_mode == 2 ) { ?>class="cloud-zoom" href="<?php echo $img_arr24[0] ?>" rel="useWrapper: false, showTitle: true, zoomWidth:'414', zoomHeight:'372', adjustY:0, adjustX:10"
<?php } elseif( $gallery_arr['slb_enab'] and !in_array('ajax_loadd', $po_arr) ) { ?> href="<?php echo $img_arr24[0] ?>" data-rel="lightbox-gallery-<?php echo $id_edic ?>" <?php } ?>
>
<?php echo get_the_post_thumbnail($post_id_gen, $img_size); echo $gallery_arr['gallery_mode']; ?>
</a>

</div>
*/ ?>
<?php /* <img title="Sample Title"> */ ?>
    
	<?php if(count($gallery_arr['image_gallery'])) { /////////  ///////// /////////// image gallery
	$gallery_imgs = $gallery_arr['image_gallery'];
	?>
 
    <div class="image_gallery">
    
<?php if (count($gallery_imgs) > 1) : ?>
 <div class="hslider-nav post-<?php echo $post_id ?> hslider-prev"> <i class="ha ha-arrow ha-arrow-left"></i> </div>
 <div class="hslider-nav post-<?php echo $post_id ?> hslider-next"> <i class="ha ha-arrow ha-arrow-right"></i> </div>
<?php endif; ?>

    <div class="gallery-slider post-<?php echo $post_id ?>">
    <ul>
	<?php foreach($gallery_imgs as $img_id) : ?>
    <?php $img_ss_full = wp_get_attachment_image_src($img_id, ''); $img_ss_main = wp_get_attachment_image_src($img_id, $img_size);  
	 $attachment = get_post($img_id); $img_title = $attachment->post_excerpt;  ?>
    <li>
 <?php /*   <a 
<?php if( $gallery_arr['slb_enab'] and $gal_mode == 1) { ?>href="<?php echo $img_ss_full[0] ?>" data-rel="lightbox-gallery-<?php echo $id_edic ?>"
<?php } elseif( $gal_mode == 2 ) { ?>class="cloud-zoom-gallery" href="<?php echo $img_ss_full[0] ?>" rel="smallImage: '<?php echo $img_ss_main[0] ?>', imgTitle: '<?php echo $img_title ?>'"
<?php } else { echo $onclick_action; ?>="change_main_img('<?php echo $img_ss_main[0] ?>', '<?php echo $img_ss_full[0] ?>', '<?php echo $post_id ?>', this)"<?php } ?>
	> */ ?>

	<?php echo wp_get_attachment_image( $img_id, '' ) ?>
<?php /*    </a> */?>

    <?php /* onclick="change_main_img()"
	<img src="<?php echo $img_ss_thumb[0] ?>" width="<?php echo $img_ss_thumb[1] ?>" height="<?php echo $img_ss_thumb[2] ?>" /> */ 
	?>
    </li>
	<?php endforeach; ?>
    </ul>
    </div>
    </div>

<?php /* javascript change_main_img() - у e_shop_scripts.php (footer) */ ?>

<?php /* jquery  */ ?> <?php /* script jCarousel */ ?>
 
<script type="text/javascript">
<?php if(!in_array('ajax_loadd', $po_arr)) { ?> window.addEventListener("DOMContentLoaded", function() { // after jQuery is loaded <?php } ?> 
jQuery(document).ready(function($) {
		var slides_count = <?php echo count($gallery_imgs); ?>;  var count_42 = 1;
		var jcarousel = $('.gallery-slider.post-<?php echo $post_id ?>');
        jcarousel
            .on('jcarousel:reload jcarousel:create', function () {
                var carousel = $(this),
                    width = carousel.innerWidth();
					count_42 = slides_count;
                /*if (width >= 440) { width = width / 6; count_42 = slides_count - 6; } 
				else if (width >= 350) { width = width / 5; count_42 = slides_count - 5; }
                else if (width >= 250) { width = width / 4; count_42 = slides_count - 4; }
				else { width = width / 3; count_42 = slides_count - 3; }*/
                carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
				if(count_42 <= 0) { jcarousel.parent().addClass("no_slide_navi"); }
            })
            .jcarousel({ wrap: 'circular' });

        $('.hslider-prev.post-<?php echo $post_id ?>').jcarouselControl({ target: '-=1' });
        $('.hslider-next.post-<?php echo $post_id ?>').jcarouselControl({ target: '+=1' });
});    
<?php if(!in_array('ajax_loadd', $po_arr)) { ?> }, false); // __ after jQuery is loaded <?php } ?>
</script>
    
	<?php } ////////// /////// //////////// ___ image gallery ?>
    


<?php if($gal_mode == 2 ) { // need for ajax load (in conf. product) ?>
<script type="text/javascript">
jQuery(document).ready(function($) {  $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();  });
</script>
<?php } ?>



    <?php if ($gal_mode == 0) : if($post->post_excerpt) { ?>
   <div style=" display: none;">   <?php the_excerpt() ?> </div>
	<?php } endif; ?>

    
    <?php else: ?>
    
<?php //// no_feat_image 
$no_feat_image = '<img src="'.get_template_directory_uri().'/images/no_feat_image.png" class="no_feat" alt="no image" />'; 
$options_45 = get_option('site_media_settings_4'); $img_8_id = $options_45['no_feat_image_id']; 
if($img_8_id and wp_attachment_is_image($img_8_id)) { 
$no_feat_image = wp_get_attachment_image( $img_8_id, 'main-img' ); 
}
?>
    <div class="main-img inn"> <?php echo $no_feat_image ?> </div>
    
	<?php endif; // if has_post_thumbnail($post_id_gen) ?>  



          
</div>


<?php /* 
    <?php if (is_single()) : ?>
<?php if($product_type == 'configurable') { 
$conf_ids_arr = array();
$child_args_8 = array( 'post_type' => get_post_type(), 'post_parent' => $post->ID, 'order' => 'ASC', 'orderby' => 'menu_order' );
$children = get_children( $child_args_8 );
if(count($children)) {   $conf_ids_arr = array_keys($children); 
$conf_args_2 = array (       
        'post_type'  => 'any',
		'post__in' => $conf_ids_arr,
		'posts_per_page'   => -1,
		'order' => 'ASC',	
		'orderby' => 'id',		
		'post_status' => 'publish'
    );

$confi_query = new WP_Query($conf_args_2);
    if( $confi_query->have_posts() ) { ?>
<div class="conf_items_imgs" style=" display: none;">    
	<?php while ($confi_query->have_posts()) : $confi_query->the_post(); ?>
		<?php if($post->post_excerpt) { ?> <div><?php the_excerpt() ?></div> <?php } ?>
	<?php endwhile; ?>
</div>
<?php }  wp_reset_query(); ?> 
<?php } // if(count($children)) ?>
<?php } // ($product_type == 'configurable') ?>	
	<?php endif; ?>
 */ ?>