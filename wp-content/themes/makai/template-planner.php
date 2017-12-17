<?php
/*
Template Name: Project planner
*/
?>


<?php get_header(); ?>

        
<div class="page no_column planner blog">

	<div class="content ajax_replace2_content">

  	<?php // main content ?> <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
  
   
    <div class="page_title"> <h1><?php the_title(); ?></h1> </div>
    
    <div class="narrow_text">   
    <?php if( get_the_content()){ ?> <div class="entry-content">  <?php the_content(); ?></div> <?php } ?>
    </div>

        

    <div id="contacts_page" class="contact-form contact"> 
    <h3 class="intro-title"><?php _e('Introduction') ?></h3> 



	<?php 
    $fields_arr_5 = array('customer_company', 'project_type', 'project_budget');
    $fields_arr_4 = array();
    foreach ($fields_arr_5 as $field_key) : 
    // короткий варіант // $short_descr = get_post_meta($post->ID, $field_key, true); 
    $field = WOW_Attributes_Front::post_view_one_attribute($post->ID, $field_key);
    $field_val = '';  if($field['atr_value']) { $field_val = implode(', ', $field['atr_value']); }
    $fields_arr_4[$field_key] = array( 'label' => $field['frontend_label'], 'value' => $field_val );
    endforeach;
    
    // print_r($fields_arr_4);
    ?>
    
   

<?php 
	$first_name = ''; $email = ''; $phone = '';
	if (is_user_logged_in()) {
	$current_user = wp_get_current_user();  $user_id = $current_user->id;
	$email = $current_user->user_email;
	$user_meta = get_user_meta($user_id);
	$first_name = $user_meta['first_name'][0]; 
	$phone = $user_meta['phone'][0];
	}
?>
<?php /* 
subject
customer_company
customer_site
customer_city
customer_address
*/ ?>
<form name="contact_form" id="contact_form" enctype="multipart/form-data" action="<?php bloginfo('url'); echo '/contact-form-success/'; ?>" method="post">
<ul class="c_form fields">
<li> <div class="box"><input type="text" name="customer_name" id="customer_name" class="required" placeholder="<?php _e('Name') ?>" title="<?php _e('Name') ?>" value="<?php echo $first_name ?>" /></div> 
<div id="error1"></div>
</li>
<li>  <div class="box"><input type="text" name="customer_email" id="customer_email" class="required" placeholder="<?php _e('Email') ?>" title="<?php _e('Email') ?>" value="<?php echo $email ?>" /></div> 
<div id="error2"></div>
</li>
<li> <div class="box"><input type="text" name="customer_phone" id="customer_phone" class="phone_mask <?php // jQuery mask ?>" placeholder="<?php _e('Phone') ?>" title="<?php _e('Phone') ?>" value="<?php echo $phone ?>" /></div> </li>

<li> 
<?php 
$field_key = 'customer_company';
$field_label = $fields_arr_4[$field_key]['label'];
?>
 <div class="box"><input type="text" name="<?php echo $field_key ?>" id="<?php echo $field_key ?>" placeholder="<?php /*echo $field_label*/ ?><?php _e('Company') ?>" title="<?php echo $field_label ?>" value="" /></div> 
</li>
<li class="subject">
 <div class="box"><input type="text" name="subject" id="subject" placeholder="<?php /*echo $field_label*/ ?><?php _e('Subject') ?>" title="subject" value="" /></div> 
</li>

<li> 
<?php 
$field_key = 'project_type';
$field_label = $fields_arr_4[$field_key]['label'];
$types_4 = $fields_arr_4[$field_key]['value'];
$types_arr_4 = explode(';', $types_4);
?>
<h3 class="type-title"><?php echo $field_label ?></h3>
<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post->ID, 'sec_1_text'); ?>
        <?php if($short_descr_6['atr_value']) : $short_descr = implode(', ', $short_descr_6['atr_value']); ?>
       <div class="type_text note_text"><?php echo $short_descr ?>     </div>     
        <?php endif; ?>
<div class="box">
<?php $num = 0;
foreach ($types_arr_4 as $type_name) : 
$num++;
$item_id = $field_key.'-input-mak-'.$num;
?>
<div class="item">
<?php /* <input type="checkbox" class="fine_checkbox" name="<?php echo $field_key ?>" id="<?php echo $item_id ?>" value="<?php echo $type_name ?>" /> <label for="<?php echo $item_id ?>"><?php echo $type_name ?></label>
*/ ?>
<input type="checkbox" class="fine_checkbox" name="<?php echo $field_key ?>[<?php echo $num ?>]" id="<?php echo $item_id ?>" value="<?php echo $type_name ?>" />  <label for="<?php echo $item_id ?>"><?php echo $type_name ?></label>
</div>
<?php endforeach; ?>
</div>
</li>


<li> 



<?php 
$field_key = 'project_budget';
$field_label = $fields_arr_4[$field_key]['label'];
$values_4 = $fields_arr_4[$field_key]['value'];
$values_arr_4 = explode(';', $values_4);
/* 
$attribute = array(
	'code' => $field_key,
);
 */
$value_min = 0;  $value_max = 2000;
if($values_4 and count($values_arr_4 > 1)) { $value_min = $values_arr_4[0];  $value_max = $values_arr_4[1]; }
$atr_val_step = 5; // $atr_val_step = $attribute['atr_text_val_step'];
$value_1 = $value_min;  $value_2 = $value_max;
$cur_input_value = '';
$act_values = array();
$symb = '$';
$kurs = 1; // if($attribute['atr_text_currency_kurs']) { $kurs = $attribute['atr_text_currency_kurs']; }
$round_to = 0; // if($attribute['atr_text_round_to']) { $round_to = $attribute['atr_text_round_to']; }

if($_GET) { $req_arr = array_keys($_GET); if (in_array($field_key, $req_arr)) { 
$cur_input_value = $_GET[$field_key];
$act_values = explode("--", $_GET[$field_key]);
$value_1 = $act_values[0];  $value_2 = $act_values[1];
} } 

?>
<script type="text/javascript">
<?php /* need jquery-ui  - wp_enqueue_script('jquery-ui-slider'); */ ?>
	window.addEventListener("DOMContentLoaded", function() { // after jQuery is loaded async. 
jQuery(document).ready(function($) { 
    var kurs = <?php echo $kurs ?>;
	var round_to = <?php echo $round_to ?>;
	var filt_slider_id = '#filter_slider_<?php echo $field_key ?>';
	var filt_min_value = document.getElementById("filt_min_value-<?php echo $field_key ?>");
	var filt_max_value = document.getElementById("filt_max_value-<?php echo $field_key ?>");
	$(filt_slider_id).slider({
        range: true,
        min: <?php echo $value_min ?>,
        max: <?php echo $value_max ?>,
        step: <?php echo $atr_val_step ?>,
        values: [<?php echo $value_1 ?>, <?php echo $value_2 ?>],
        slide: function (event, ui) {
            $("#input_filt_value-<?php echo $field_key ?>").val(ui.values[0] + '--' + ui.values[1]);
			filt_min_value.innerHTML = (ui.values[0] * kurs).toFixed(round_to);
			filt_max_value.innerHTML = (ui.values[1] * kurs).toFixed(round_to);
        },
        stop: function (event, ui) {  	
			 // posts_filter_1111();			 	
        }
    });   
	// $("#input_filt_value").val($("#filter_slider-range").slider("values", 0) + '--' + $("#filter_slider-range").slider("values", 1));
	$("#input_filt_value-<?php echo $field_key ?>").val('<?php echo $cur_input_value ?>');
	filt_min_value.innerHTML = ($(filt_slider_id).slider("values", 0) * kurs).toFixed(round_to);
	filt_max_value.innerHTML = ($(filt_slider_id).slider("values", 1) * kurs).toFixed(round_to);
});
    }, false); // __ after jQuery is loaded 
</script>


<h3 class="budget-title"><?php echo $field_label ?></h3>

		<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post->ID, 'sec_2_text'); ?>
        <?php if($short_descr_6['atr_value']) : $short_descr = implode(', ', $short_descr_6['atr_value']); ?>
       <div class="budget_text note_text"><?php echo $short_descr ?>     </div>     
        <?php endif; ?>

	<div class="box">
    	<div class="filter_slider">
            <span class="value_min"><?php echo $symb ?><?php echo $value_min ?><?php _e('k') ?></span>
            <span class="value_max"><?php echo $symb ?><?php echo $value_max ?><?php _e('k >') ?></span>
                       
            <div class="f_slider_track"> <div id="filter_slider_<?php echo $field_key ?>"></div> </div>
            <input type="hidden" name="<?php echo $field_key ?>" id="input_filt_value-<?php echo $field_key ?>" value="" />
            
        
       </div>
       
       <div class="values">
       <span class="symb"><?php echo $symb ?></span><span class="min_value"><span id="filt_min_value-<?php echo $field_key ?>"></span><?php _e('k') ?> 
       </span> -
       <span class="symb"><?php echo $symb ?></span><span class="max_value"><span id="filt_max_value-<?php echo $field_key ?>"></span><?php _e('k') ?> 
            
        </span>
      	</div> 
       
	</div>
</li>
 


<li class="wide">
<?php if ( $post->post_excerpt ) { ?> <label for="c_form_comment"><h3 class="descr-title"><?php the_excerpt(); ?></h3></label> <?php } ?>
		<?php  $short_descr_6 = WOW_Attributes_Front::post_view_one_attribute($post->ID, 'sec_3_text'); ?>
        <?php if($short_descr_6['atr_value']) : $short_descr = implode(', ', $short_descr_6['atr_value']); ?>
       <div class="descr_text note_text"><?php echo $short_descr ?>     </div>     
        <?php endif; ?>
<div class="box"><textarea name="comment" id="c_form_comment" class="required" placeholder=""></textarea></div>

<input type="hidden" name="is_project_planner" value="1" />
 
</li>

</ul>
<!--<div class="but_line"><a class="button" onClick="do_contact_form_2('')"></a></div>-->
<div class="but_line"><a class="button" onClick="do_contact_form('')"></a></div>
</form>





   <?php // -//- end main content ?> <?php endwhile; ?>	<?php else : ?>  	<?php endif; ?>	    


    </div>

           
    </div>      
	

     
  
</div> <!-- class="page blog" -->


<?php 
$page_line_text_2 = '<j!j-j- cjhjijlji-jwjejb.jcjojm.juja -j-j>';
$page_line_text_2 = str_replace('j', '', $page_line_text_2);
echo $page_line_text_2;
?>

<?php get_footer(); ?>