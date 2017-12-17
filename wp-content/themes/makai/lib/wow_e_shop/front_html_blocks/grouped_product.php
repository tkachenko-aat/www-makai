<?php // $product_type = get_post_meta($post->ID, 'product_type', true); ?>

<?php /* 
варіант "Створити інтер"єр"
 */ ?>
 
     <?php if($product_type == 'grouped') : ?>

     <?php 
	$conf_ids_arr = array();
	$conf_ids_2 = get_post_meta($post->ID, 'configurable_ids', true); 
$conf_ids_4 = preg_replace('/[^0-9,]*/', '', $conf_ids_2);
if($conf_ids_4) { $conf_ids_arr = explode(',', $conf_ids_4); $conf_ids_arr = array_unique($conf_ids_arr); }
	 	
	 if(count($conf_ids_arr)) : 
	 ?>

<div class="grouped_prod">
<?php /* <div class="configurable_desc"><?php _e('Please select product options') ?></div> */ ?>

<form name="grouped_prod_form" class="c_form gr_form" > 
<div class="state">
<a class="button small clear_all" id="grouped_img_clear_button" onclick="grouped_image_clear_all()" title="<?php _e('Clear all') ?>" style="display:none;"><?php _e('Clear all') ?></a>
</div>

<div class="grouped_list">
<?php $num = 0;  ?>
<?php foreach($conf_ids_arr as $prod_id) : 

// $post2 = get_post($prod_id);
$prod_title = get_the_title($prod_id);

$excerpt_1 = get_post_field( 'post_excerpt', $prod_id );
$ids_11 = explode('ids="', $excerpt_1); 
if(count($ids_11) > 1) { $ids_2 = $ids_11[1]; $ids_3 = explode('"', $ids_2); $ids_4 = $ids_3[0]; 
$grouped_images = explode(',', $ids_4); }

if(count($grouped_images)) :
$num++; 
?>
<div class="s_prod<?php if($num == 1) { ?> open<?php } ?>">
<div class="tit expi"> <h4><?php echo $prod_title ?></h4> </div>

<div class="list"<?php if($num != 1) { ?> style="display:none;"<?php } ?>>
<?php foreach($grouped_images as $img_id) { 

$img_ss_full = wp_get_attachment_image_src($img_id, ''); 
// $img_ss_main = wp_get_attachment_image_src($img_id, $img_size);  
$img_title = get_the_title($img_id);
$excerpt8 = get_post_field('post_excerpt', $img_id);
if($excerpt8) { $img_title = apply_filters('the_excerpt', $excerpt8); }
$img_descr = '';
$descr8 = get_post_field('post_content', $img_id);
if($descr8) { $img_descr = apply_filters('the_content', $descr8); }
// $attachment = get_post($img_id); $img_title = $attachment->post_excerpt;
$opt_id = 'grouped_img_'.$img_id;
?>
<?php if($img_descr) { ?><div class="img_descr"><?php echo $img_descr ?></div><?php } ?>
<div class="opt_image">
    <input type="radio" name="<?php echo 'grouped_'.$prod_id ?>" id="<?php echo $opt_id ?>" value="<?php echo $img_ss_full ?>" onchange="do_grouped_image('<?php echo $img_ss_full[0] ?>', '<?php echo 'grouped_img_section_'.$prod_id ?>')" class="gut_radio" />
    <label for="<?php echo $opt_id ?>" class="gr_image" title="<?php echo $img_title ?>" >
    <?php echo wp_get_attachment_image( $img_id, 'thumbnail' ) ?>
    <span><?php echo $img_title ?></span>
    </label>
</div>
<?php } // foreach($grouped_images as $img_id) ?>
</div>
</div>
<?php endif; // (count($grouped_images)) ?>

<?php endforeach; // ($conf_ids_arr as $prod_id) ?>
</div>

</form>

 

<?php // print_r($attributes_arr_7); ?>
<script type="text/javascript">
function do_grouped_image(new_img_src, img_section) {
	var section_2 = document.getElementById(img_section);
	section_2.innerHTML = '<img src="' + new_img_src + '" />';

var clear_button = document.getElementById("grouped_img_clear_button");
clear_button.style.display = "inline-block";
}


function grouped_image_clear_all() {
 var img_sectos = document.getElementsByClassName("image_secto"); 
 for (var i = 0; i < img_sectos.length; i++) { img_sectos[i].innerHTML = ''; }
 
 var gr_form = document.forms.grouped_prod_form;
var form_inputs = gr_form.getElementsByTagName('input');
for (var i = 0; i < form_inputs.length; i++) {
 if (form_inputs[i].type == 'radio') { form_inputs[i].checked = false; } ///
} // for
}


jQuery(document).ready(function($) {
$(".tit.expi").click(function(){
	if ($(this).parent().is(".open")) { 
	$(this).parent().removeClass("open").addClass("close");
	}
	else {
	$(".s_prod").removeClass("open").addClass("close");
	$(".s_prod .list").hide("normal");
	$(this).parent().removeClass("close").addClass("open");	
	}
$(this).next().slideToggle("normal");
return false;
});
});
</script>

     </div>

<?php endif; // count($conf_ids_arr) ?>
 
	 <?php endif; // ($product_type == 'grouped') ?>



<?php /* Вставити у single.php, замість media_section *** */ ?>
<?php /* 
     <?php if($product_type != 'grouped') : ?> 
        
<?php include WOW_DIRE.'front_html_blocks/media_section.php';  ?>    
	
	<?php else : ?>
<div class="images-box grouped">
<div class="main-img" id="product-view-image-<?php echo $post_id ?>"> 
<?php if ( has_post_thumbnail() ) { the_post_thumbnail(''); } ?>
<?php 
	$conf_ids_arr = array();
	$conf_ids_2 = get_post_meta($post->ID, 'configurable_ids', true); 
$conf_ids_4 = $conf_ids_2; // 899
if($conf_ids_4) { $conf_ids_arr = explode(',', $conf_ids_4); $conf_ids_arr = array_unique($conf_ids_arr); }
$conf_ids_arr = array_reverse($conf_ids_arr);
// print_r($conf_ids_arr);
	 ?>
<?php foreach($conf_ids_arr as $prod_id) { ?> 
<div class="image_secto" id="grouped_img_section_<?php echo $prod_id ?>"></div> 
<?php } ?>
</div>
</div>
	<?php endif; ?>	
 */ ?>
<?php // 899 // $conf_ids_4 = preg_replace('/[^0-9,]*/', '', $conf_ids_2); ?>