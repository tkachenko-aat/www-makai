<?php // $product_type = get_post_meta($post->ID, 'product_type', true); ?>

     <?php if($product_type == 'configurable') : ?>
     
     <div class="configurable">
     <?php $config_options = WOW_Attributes_Front::configurable_prod_options(); 
	 
	 if(count($config_options['attributes'])) : 
	 
$attributes_arr_7 = array();	
	 ?>
<?php // print_r($config_options['table_2_atrs']); ?>



<?php $config_arr = array_keys($config_options);
if(!in_array('table_2_atrs-9', $config_arr)) : // /////////// // standart mode 
/* Якщо використовується таблиця з опціями (цінами), замінити код на 'table_2_atrs' */ ?>

<div class="configurable_desc"><?php _e('Please select product options') ?></div>
<form name="configurable_form" class="c_form<?php if(count($config_options['attributes']) == 1) { ?> simple_conf<?php } ?>" > 
<?php foreach($config_options['attributes'] as $key4 => $config_atr) : 

$show_colors = 0;  if(strpos($config_atr['code'], 'color') !== false and count($config_options['attributes']) > 1) { $show_colors = 1; }
$attributes_arr_7[] = $config_atr['code'];  ?>
<div class="con_attribute">
<div class="tit"> <h4><?php echo $config_atr['frontend_label'] ?></h4> </div>
<ul>

<?php foreach($config_atr['atr_options'] as $opt) { 
$opt_id = 'opt-'.$config_atr['code'].'-'.$opt['id'];

$label_style = '';  $label_class = 'con_item';
if($show_colors == 1) {
$color_code = 'EEE';  if($opt['color_code']) { $color_code = $opt['color_code']; }
$label_style = 'style=" background: #'.$color_code.';"';
$label_class = 'con_item show_colors';
}
?>
<li>
    <input type="radio" name="<?php echo $config_atr['code'] ?>" id="<?php echo $opt_id ?>" value="<?php echo $opt['id'] ?>" onchange="do_config_product('<?php echo $config_atr['code'] ?>')" class="gut_radio"<?php if(($key4 != 0) or ($opt['stock'] == 'out_of_stock')) { ?> disabled="disabled"<?php } ?> />
    <label for="<?php echo $opt_id ?>" class="<?php echo $label_class ?>" title="<?php echo $opt['label'] ?>" <?php echo $label_style ?>>
    <?php if(count($config_options['attributes']) == 1) { ?><div class="p_image"><?php if(has_post_thumbnail($opt['product_id'])) { echo get_the_post_thumbnail($opt['product_id'], 'thumbnail'); } else { echo '<div class="inn"> <img src="'.get_template_directory_uri().'/images/no_feat_image.png" class="no_feat" /> </div>'; } ?></div><?php } ?>
    <span><?php echo $opt['label'] ?></span>
    </label>
</li>
<?php } // foreach($config_atr['atr_options'] as $opt) ?>
</ul>
</div>
<?php endforeach; ?>
</form>







<?php else : // ($config_options['table_2_atrs']) // ///////// // table with products options ?>
<?php $prod_table_arr = $config_options['table_2_atrs']; 
$t_head_arr = $prod_table_arr[0];
unset($prod_table_arr[0]);
// $width_4 = (count($t_head_arr) * 65) + 140 - 5; 
?>
<div class="box-content produht_table">
<div class="title"> <?php dynamic_sidebar( 'prod_table_title' ); ?> </div>
<div id="configurable_prod_table" class="prod_table" <?php /* style="max-width: <?php echo $width_4 ?>px;"  */ ?> >
<div class="row row-0">
<?php $num_1 = 0;
foreach($t_head_arr as $key1 => $t_head_item) { 
$num_1++; ?>
<div class="colu colu-<?php echo $num_1 ?> option-<?php echo $key1 ?>"><div class="inn">
<?php if($num_1 == 1) { ?> <div class="lab_1"><?php echo $t_head_item[0] ?></div> <div class="lab_2"><?php echo $t_head_item[1] ?></div> <?php } else { echo $t_head_item; } ?>
</div></div>
<?php } ?>
</div>
<?php $num = 0;
foreach($prod_table_arr as $key8 => $table_roww) : 
$num++; ?>
<div class="row row-<?php echo $num ?> atr-option-<?php echo $key8 ?>">
<?php $num_2 = 0;
foreach($table_roww as $key9 => $table_item) { 
$num_2++; ?>
<div class="colu colu-<?php echo $num_2 ?> option-<?php echo $key9 ?>"><div class="inn">
<?php if($num_2 == 1) { ?><?php echo $table_item['text'] ?><?php } ?>
<?php if($table_item['prod_id']) { ?> <div class="salas" onmouseover="p_table_hover_4('colu-<?php echo $num_2 ?>', 'row-<?php echo $num ?>')" onmouseout="p_table_hover_4('')"><?php if($table_item['in_stock']) { ?><a onclick="show_addtocart_form_4('<?php echo $table_item['prod_id'] ?>', '<?php echo $table_item['label'] ?>')" title="<?php // echo $table_item['label'] ?>"><?php } ?> <?php echo $table_item['price'] ?> <?php if($table_item['in_stock']) { ?></a><?php } ?></div> <?php } ?> 
</div></div>
<?php } ?>
</div>
<?php endforeach; ?>
</div>
</div> <?php /* javascript - function p_table_hover_4(col_clas, row_clas) */ ?>

<?php endif; // ?>





<?php // print_r($attributes_arr_7); ?>     
<script type="text/javascript">
function do_config_product(atr_code) {
	var products_atrs_arr = <?php echo json_encode($config_options['products_atrs']) ?>;
	var prod_arr = <?php echo json_encode($config_options['prod_arr']) ?>;
	var attributes_arr = <?php echo json_encode($attributes_arr_7) ?>;
	
    var con_form = document.forms.configurable_form;
	var elem_act = con_form.elements[atr_code];
	// var elem_act_val = elem_act.value;
	
	var input_qty = document.getElementById("qty");
	var please_select5 = document.getElementById("config_prod_please_select");	
		
	if(products_atrs_arr[atr_code] && (typeof products_atrs_arr[atr_code] != 'function')) { /////////////
	var next_atr = products_atrs_arr[atr_code]['next']; // alert(next_atr);
	var next_av_values = products_atrs_arr[atr_code]['options'][elem_act.value]; // alert(next_av_values);
	
	var el_next_2 = con_form.elements[next_atr];
	for (var i = 0; i < el_next_2.length; i++) {			
 		el_next_2[i].checked = false;
		if(next_av_values.indexOf(el_next_2[i].value) != -1) { el_next_2[i].disabled = false; }
		else { el_next_2[i].disabled = true; }
		// alert(el_next_2[i].value);
	}
			please_select5.style.display = "block";
	} // if(products_atrs_arr[atr_code]) ////////////////
	
	else { // ////////// last attribute
	var prod_ids = Object.keys(prod_arr);
	var prod_id_activ = prod_ids[0];
	// alert(attributes_arr);
	for (var i2 = 0; i2 < prod_ids.length; i2++) {
		non_prod_id = 0;
		for (var i = 0; i < attributes_arr.length; i++) {
			var atr_val_54 = con_form.elements[attributes_arr[i]].value;
			if(prod_arr[prod_ids[i2]]['options'][attributes_arr[i]] != atr_val_54) { non_prod_id = 1; }
			// alert(prod_arr[prod_ids[i2]][attributes_arr[i]]);
		}
		if(non_prod_id == 0) { prod_id_activ = prod_ids[i2]; }
	} // for prod_ids 
	// prod_url = prod_arr[prod_id_activ]['url'];
	/* базова операція: заміна id товару у формі */
	input_qty.setAttribute('name', 'product_form[' + prod_id_activ + ']');
	 
	 update_page_config_prod(prod_id_activ); 
	 	 please_select5.style.display = "none";
		 sel_config_prod_note('hide');
		 	 
	} // ////////// __ last attribute 		
	// var obj_71 = JSON.stringify(products_atrs);
	// var obj_72 = JSON.stringify(products_atrs['manufacturer']);
}


function update_page_config_prod(prod_id_activ) {
<?php /* ajax_prepare_html(), sidebar_replace_new(), page_replace_new() - розміщені в e_shop_scripts.php (footer) */ ?>
	/* оновлення блоків на сторінці товару ... */
	var prod_arr = <?php echo json_encode($config_options['prod_arr']) ?>;
	prod_url = prod_arr[prod_id_activ]['url']; 
	
	var prod_info_sect = document.getElementById("product-information");
	ajax_prepare_html(prod_info_sect); 
	
  new Ajax.Updater( page_temp.id, prod_url, { 
  	method: 'post',
	parameters: {ajax_loadd: 1},
	evalScripts: true, //
	onComplete: 
		function() { 
			sidebar_replace_new('.images-box');
			sidebar_replace_new('.prod-avail-sku');
			sidebar_replace_new('.main_price_box');
			// .short-descr / .descr 
			page_replace_new(prod_info_sect); /* !!!! */
		}
	} );
}


function sel_config_prod_note(par) {
	var notice_text = "<?php _e('Product options are not selected!') ?>";
	var con_notice = document.getElementById("config_prod_note_error_text");
		if(par != 'hide') {
	con_notice.className += ' error';
	con_notice.innerHTML = notice_text;
		}
		else { con_notice.className = 'form_notice'; con_notice.innerHTML = ''; }
}



function show_addtocart_form_4(prod_id, prod_label){
		   var item_qty = document.getElementById("lig_qty");
		   var prod_form_name = 'product_form[' + prod_id + ']';
		   item_qty.setAttribute('name', prod_form_name);
		   item_qty.setAttribute('value', 1);  item_qty.value = 1;
		   // item_qty.name = qtyy;
		   var prod_name_div = document.getElementById("lightb_addtocart_prod_name");
		   prod_name_div.innerHTML = prod_label;
document.getElementById("overlay_2").style.display = "block";
var addtocart_form = document.getElementById("lightb_addtocart_form");
var scroll_y = document.body.scrollTop || document.documentElement.scrollTop;
addtocart_form.style.top = scroll_y + 150 + "px";
addtocart_form.style.display = "block";
} 




function p_table_hover_4(col_clas, row_clas) {
	var prod_table = document.getElementById("configurable_prod_table");
	var tabl_cols = prod_table.getElementsByClassName("colu");
 for (var i = 0; i < tabl_cols.length; i++) { 
 var colu_l = tabl_cols[i];  colu_l.className = colu_l.className.replace(/act/g, '');
 }
 	if(col_clas && row_clas) {
	var row_0_my_col = prod_table.getElementsByClassName("row-0")[0].getElementsByClassName(col_clas)[0];
	row_0_my_col.className += " act";
	var col_0_my_row = prod_table.getElementsByClassName(row_clas)[0].getElementsByClassName("colu-1")[0];
	col_0_my_row.className += " act";
	}
}
</script>



<?php endif; // count($config_options['attributes']) ?>

     </div>
 
	 <?php endif; // ($product_type == 'configurable') ?>


