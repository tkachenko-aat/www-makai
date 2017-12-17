<?php

add_action('add_meta_boxes', 'add_wow_meta_boxes');

function add_wow_meta_boxes() {
 $types = WOW_Attributes_Front::get_attrSet_posttypes();
 foreach ($types as $type) {
	$post_type = $type;
add_meta_box('wow_attributes_box', __('Attributes'), 'post_attributes_list', $post_type, 'normal', 'high');
add_meta_box('wow_prod_parameter_box', __('Product parameters'), 'post_prod_parameters', $post_type, 'normal', 'high');
add_meta_box('wow_prod_order_parent', __('Parent').'. '.__('Order'), 'post_prod_order_parent', $post_type, 'side', 'default');
/* !!!! */ // add_meta_box('wow_prod_vip_status', __('VIP Status'), 'wow_prod_vip_status_box', $post_type, 'side', 'low');
remove_meta_box('postcustom', $post_type, 'normal');
 }

 $types_no_prod = WOW_Attributes_Front::get_attrSet_posttypes_no_prod();
 foreach ($types_no_prod as $post_type_no_prod) {
add_meta_box('wow_attributes_box', __('Attributes'), 'post_attributes_list', $post_type_no_prod, 'normal', 'high');
add_meta_box('wow_prod_order_parent', __('Parent').'. '.__('Order'), 'post_prod_order_parent', $post_type_no_prod, 'side', 'default');
remove_meta_box('postcustom', $post_type_no_prod, 'normal');
 }

/// Кнопка "Duplicate item"
        $args4 = array( 'public' => true, '_builtin' => false ); 
		$types_4_arr = get_post_types($args4);  $types_4_arr['post'] = 'post';
		unset($types_4_arr['wow_order'], $types_4_arr['c_form_order']);
 foreach ($types_4_arr as $post_type) {
 add_meta_box('wow_post_duplicate', __('Duplicate post'), 'post_duplicate_f', $post_type, 'side', 'high');
 }
		
/// Нові поля для сторінок. Використовуються атрибути типу textarea 
add_meta_box('wow_page_add_attributes', __('Page. New fields'), 'page_add_attributes', 'page', 'normal', 'low');
remove_meta_box('postcustom', 'page', 'normal');
}



/* Адмінка. Товар. Список атрибутів */

function post_attributes_arr($post_id) {
	global $wpdb;
	if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад	
	// $attributeSet_id = 1;
	$attributeSet_id = WOW_Attributes_Front::get_attributeSet_id($post_id);
	
	$attrib_groups_arr = $wpdb->get_results( "SELECT * FROM " . WOW_TABLE_ATTRIBUTE_SET_SECTION . " WHERE attribute_set_id = ".$attributeSet_id." AND status = 'valid' ORDER BY position ASC ", ARRAY_A );
	
	$groups_arr_2 = array();
	
	foreach ($attrib_groups_arr as $group) {
	$group_id = $group['id'];
	$table_atr = WOW_TABLE_ATTRIBUTE;  $table_details = WOW_TABLE_ATTRIBUTE_SECTION_DET;
	$atr_query = "SELECT $table_atr.* FROM $table_atr 			
				LEFT JOIN $table_details ON ($table_atr.id = $table_details.attribute_id )
				WHERE $table_details.attribute_group_id = $group_id	AND $table_atr.status = 'valid'
				ORDER BY $table_details.position";	
	$attributes_arr_2 = $wpdb->get_results( $atr_query, ARRAY_A );
	
	if (count($attributes_arr_2)) {
		foreach ($attributes_arr_2 as $key4 => $attribute) {	
		if (in_array($attribute['backend_input'], array('select', 'multiple-select'))) { 
		$options_arr = $wpdb->get_results( "SELECT * FROM " . WOW_TABLE_ATTR_OPTIONS . " WHERE attribute_id = ".$attribute['id']." ORDER BY position ASC ", ARRAY_A );
			if (function_exists('qtrans_getSortedLanguages')) { // Переклад		
			foreach ($options_arr as $key7 => $opti) {	
			$options_arr[$key7]['label'] = qtrans_use($q_config['language'], $opti['label'], true);	 
			}		
			} // -- Переклад
		$attributes_arr_2[$key4]['options'] = $options_arr;
		} // ('select', 'multiple-select')
			if (function_exists('qtrans_getSortedLanguages')) { // Переклад	
			$attributes_arr_2[$key4]['frontend_label'] = qtrans_use($q_config['language'], $attribute['frontend_label'], true);
			if($attribute['frontend_unit']) { $attributes_arr_2[$key4]['frontend_unit'] = qtrans_use($q_config['language'], $attribute['frontend_unit'], true); }
			} // -- Переклад 
		} // foreach ($attributes_arr_2 as $key4 => $attribute) 
		
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад	
			$group['name'] = qtrans_use($q_config['language'], $group['name'], true);
		} // -- Переклад
		$groups_arr_2[$group['code']] = array( 'id' => $group['id'], 'code' => $group['code'], 'name' => $group['name'], 'items' => $attributes_arr_2 );
		} // if (count($attributes_arr_2))
	
	} // foreach ($attrib_groups_arr as $group)

return $groups_arr_2;
}


function post_attributes_list() {
	global $wpdb;
	global $post;
	$groups_arr_2 = post_attributes_arr($post->ID);
	
/// Заг. налаштування сайту. Валюти 
$options_5 = get_option('wow_settings_arr');  $opt_currency = $options_5['wow_currency'];
$base_currency = $opt_currency['base'];  $b_symb = $base_currency;  
// if($opt_currency['symbols'][$base_currency]) { $b_symb = $opt_currency['symbols'][$base_currency]; }

	$post_meta_arr = get_post_custom();

$post_type = get_post_type( $post->ID ); 
$post_type_obj5 = get_post_type_object($post_type);  $p_type_name = $post_type_obj5->labels->name;
?>

<div class="field_2 kiko2 atr-set"> <span><?php _e('Attribute Set') ?>: </span>
<h4><?php echo $p_type_name; ?></h4>
</div>
	
	<?php foreach ($groups_arr_2 as $group) : ?>   
    
    <div class="group_attributes">
    <h3><?php echo $group['name'] ?></h3>
    <ul>
    <?php foreach ($group['items'] as $attribute) : 
	
	if(strpos($attribute['code'], 'price') !== false) { ///	
		$attribute['frontend_unit'] = $b_symb;
	} ///	
	?>
    <li class="atr_item">
    <div class="field_2">
    <?php $item_id = 'atr-'.$attribute['code']; ?>
    <label for="<?php echo $item_id ?>"><?php echo $attribute['frontend_label'] ?><?php if($attribute['frontend_unit']) { ?>, <span class="unit"> <?php echo $attribute['frontend_unit'] ?></span><?php } ?></label>
	
	<?php 
	$meta_key_1 = $attribute['code'];

	if ($attribute['backend_input'] != 'multiple-select' ) { 
	add_post_meta($post->ID, $meta_key_1, '', true);
	} 
	else { // $attribute['backend_input'] == 'multiple-select'	
	$post_meta_arr2 = $post_meta_arr[$attribute['code']];
	$kilk_4 = count($attribute['options']) - count($post_meta_arr2);
	if($kilk_4 > 0) {	 
	for ($i = 0; $i < $kilk_4; $i++) { add_post_meta($post->ID, $meta_key_1, ''); }
	}
	// foreach ($attribute['options'] as $option) { }
	} // $attribute['backend_input'] == 'multiple-select'
	
	$post_meta_arr_3 = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = $post->ID AND meta_key = '$meta_key_1'", ARRAY_A );
	
	if (count($post_meta_arr_3)) { ///
	
	if ($attribute['backend_input'] != 'multiple-select' ) {
	$meta_input_id = $post_meta_arr_3[0]['meta_id']; 
	$input_value = $post_meta_arr_3[0]['meta_value'];
	
	$item_input_name = 'meta['.$meta_input_id.'][value]';
	$item_input_key = 'meta['.$meta_input_id.'][key]';
	} 
	
	else { // $attribute['backend_input'] == 'multiple-select'	
	$sel_options2 = array();
	$post_meta_arr_multi = $post_meta_arr_3;
	foreach ($post_meta_arr_multi as $key2 => $m_item) {
	if ($m_item['meta_value']) { $sel_options2[$key2] = $m_item['meta_value']; }
	}
	$sel_options2_4 = array_flip($sel_options2);
	
	$options_arr_multi = $attribute['options'];
	// 1) додати мета-параметри до елементів масиву, які уже вибрані
	foreach ($options_arr_multi as $key3 => $option3) {
	if ( in_array($option3['id'], $sel_options2) ) { 
	$key_p = $sel_options2_4[$option3['id']];
	$options_arr_multi[$key3]['meta_par'] = $post_meta_arr_multi[$key_p];
	unset($post_meta_arr_multi[$key_p]);
	}
	} // foreach ($options_arr_multi as $key3 => $option3)
	$post_meta_arr_multi = array_values($post_meta_arr_multi);
	// 2) повторний цикл - додати мета-параметри до решти елементів масиву
	$key_38 = 0;
	foreach ($options_arr_multi as $key3 => $option3) { if(!$option3['meta_par']) { $options_arr_multi[$key3]['meta_par'] = $post_meta_arr_multi[$key_38]; $key_38 = $key_38 + 1; } }
	} // $attribute['backend_input'] == 'multiple-select'

	?>
        
<?php if ($attribute['backend_input'] == 'text' ) { ?>
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $attribute['code'] ?>" />
<input type="text" name="<?php echo $item_input_name ?>" id="<?php echo $item_id ?>" value="<?php echo $input_value ?>" />  
    
<?php } elseif ($attribute['backend_input'] == 'checkbox' ) { ?>
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $attribute['code'] ?>" />
<input type="checkbox" name="<?php echo $item_input_name ?>" id="<?php echo $item_id ?>" class="type-check" value="1" <?php if ($input_value == 1) { ?>checked="checked"<?php } ?> />

<?php } elseif ($attribute['backend_input'] == 'select' ) { ?>
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $attribute['code'] ?>" />    
<select name="<?php echo $item_input_name ?>" id="<?php echo $item_id ?>">
        	<option value="">  </option>
		<?php foreach ($attribute['options'] as $option) { ?>
        	<option value="<?php echo $option['id'] ?>" <?php if ($option['id'] == $input_value) { ?>selected="selected"<?php } ?>><?php echo $option['label'] ?></option>
		<?php } ?>
</select>

<?php } elseif ($attribute['backend_input'] == 'textarea' ) { ?>
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $attribute['code'] ?>" />
<textarea name="<?php echo $item_input_name ?>" class="atr_textarea" id="<?php echo $item_id ?>"><?php echo $input_value ?></textarea>


<?php } elseif ($attribute['backend_input'] == 'map' ) { 
attribute_map_input($attribute['code'], $input_value, $item_input_key, $item_input_name); ?>


<?php } elseif ($attribute['backend_input'] == 'date' ) { /// function scripts_for_datepicker ?>
<?php if($num_d != 1) { ?>
	<script>
jQuery(document).ready(function($) {  
	$( ".datepicker" ).datepicker( { 
  dateFormat: "dd.mm.yy", 
  changeYear: true 
  } ); 
});
	</script>
<?php } ?>
<?php $num_d = 1; ?>
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $attribute['code'] ?>" />
<input type="text" name="<?php echo $item_input_name ?>" class="datepicker" id="<?php echo $item_id ?>" value="<?php echo $input_value ?>" readonly="readonly" /> <span><strong><?php _e('Choose a date') ?></strong></span>


<?php } else { // ($attribute['backend_input'] == 'multiple-select' ) ?>
	<div class="multiple_b">
	<?php foreach ($options_arr_multi as $option) { 
	$m_input_id = $option['meta_par']['meta_id'];
	$inp_value = $option['meta_par']['meta_value'];
	
	$item_inp_name = 'meta['.$m_input_id.'][value]';
	$item_inp_key = 'meta['.$m_input_id.'][key]';
	?>
    <div class="line2">
    <input type="hidden" name="<?php echo $item_inp_key ?>" value="<?php echo $attribute['code'] ?>" />    
    <input type="checkbox" name="<?php echo $item_inp_name ?>" id="option-<?php echo $option['id'] ?>" value="<?php echo $option['id'] ?>" <?php if (in_array($option['id'], $sel_options2)) { ?>checked="checked"<?php } ?> /> <label class="cheki" for="option-<?php echo $option['id'] ?>"><?php echo $option['label'] ?></label>
    </div>
    <?php } ?>
    </div>

<?php } ?> 
    
    <?php } // if (count($post_meta_arr_3)) 
	else { ?>
    <div class="no_attrib"><?php echo __('Please, reload page to see the attribute.'); ?></div>
    <?php } ?>
    </div> <!-- field_2 -->
    </li>
    <?php endforeach; ?>
    </ul>
    </div>
	
	<?php 
	endforeach;
		
}


function scripts_for_datepicker($hook) {
	if( !in_array($hook, array('post.php', 'post-new.php')) ) { return; }
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style( 'datepicker_css', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
}
add_action( 'admin_enqueue_scripts', 'scripts_for_datepicker' );


function attribute_map_input($attr_code, $input_value, $item_input_key, $item_input_name) {
	/* Google Map */
 wp_register_script( 'fields_map', get_template_directory_uri().'/lib/wow_e_shop/js/fields_map.js', array(), '1.0', true );
 wp_enqueue_script( 'fields_map' );
$map_coords = array('address' => 'Kyiv, Ukraine', 'lat' => 50.42353284574126, 'lng' => 30.51974058151245);
if($input_value) {
$map_coords_2 = explode('||', $input_value);
$map_coords = array('address' => $map_coords_2[2], 'lat' => $map_coords_2[0], 'lng' => $map_coords_2[1]);
}
$options4 = get_option('site_add_settings_4');  $map_api = $options4['my_google_map_api'];
if(!$map_api) { $map_api = 'AIzaSyB26HqhWs5_arfnhGuRbUBh4limZ7PCRy8'; }
?>
<script>
my_google_map_api = '<?php echo $map_api ?>';
</script>
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $attr_code ?>" />

<div class="acf-google-map" data-zoom="14">
	<input type="hidden" class="map-value" name="<?php echo $item_input_name ?>" value="<?php echo $input_value; ?>" />
				<?php foreach( $map_coords as $coord => $coord_val ): ?>
		<input type="hidden" class="input-<?php echo $coord; ?>" value="<?php echo $coord_val; ?>" />
				<?php endforeach; ?>
			
			<div class="title">				
				<div class="has-value">
<div class="submitbox">
<a href="#" class="acf-sprite-remove ir submitdelete" title="<?php _e('Delete'); ?>"><?php _e('Delete'); ?></a>
</div>
					<h4><?php echo $map_coords['address']; ?></h4>
				</div>
 		
 <div class="no-value"> <?php /* <a href="#" class="acf-sprite-locate ir" title="<?php _e("Find current location"); ?>">Locate</a> */ ?> <input type="text" placeholder="<?php _e('Search for address...'); ?>" class="search" style="width:70%;" /> </div>
            				
			</div>			
			 <div class="canvas" style="height: 400px;"> </div>             		
</div>
<?php
}



function post_prod_parameters() {
	global $wpdb;
	global $post;
	$post_meta_arr = get_post_custom();
			
	$system_pars_arr = array( 
		'product_type' => array('label' => __('Product type'), 'b_input' => 'select', 'values' => 'simple, configurable, grouped', 'clas' => 'smal'),
		'visibility' => array('label' => __('Visibility'), 'b_input' => 'select', 'values' => 'catalog_search, catalog, search', 'clas' => 'smal fu_2'),	
		'configurable_atrs' => array('label' => __('Configurable attributes'), 'b_input' => 'multiple-select', 'clas' => ''),
		'configurable_ids' => array('label' => __('Child simple products'), 'b_input' => 'text', 'clas' => ''),
		'stock' => array('label' => __('Stock'), 'b_input' => 'text', 'clas' => ''),	
		'products_upsell' => array('label' => __('Upsell products'), 'b_input' => 'text', 'clas' => ''),
		'products_related' => array('label' => __('Related products'), 'b_input' => 'text', 'clas' => ''),
		'views' => array('label' => __('Views count'), 'b_input' => 'text', 'readonly' => '1', 'clas' => 'smal'),
		'prod_sales' => array('label' => __('Sales count'), 'b_input' => 'text', 'readonly' => '1', 'clas' => 'smal fu_2'),
	); //
	
	$prod_type = $post_meta_arr['product_type'][0];
	if(!in_array($prod_type, array('configurable', 'grouped'))) { unset($system_pars_arr['configurable_ids']); }
	if($prod_type != 'configurable') { unset($system_pars_arr['configurable_atrs']); }
	else {  // $prod_type == 'configurable'
		// $system_pars_arr['stock']['readonly'] = 1;
		
	$post_id = $post->ID;	
	$attributeSet_id = WOW_Attributes_Front::get_attributeSet_id($post_id);	
	$table_atr = WOW_TABLE_ATTRIBUTE;  $table_details = WOW_TABLE_ATTRIBUTE_SECTION_DET;	
		$atr_query_4 = "SELECT $table_atr.id, $table_atr.code, $table_atr.frontend_label FROM $table_atr 			
				LEFT JOIN $table_details ON ($table_atr.id = $table_details.attribute_id )
		WHERE $table_details.attribute_set_id = $attributeSet_id AND $table_atr.status = 'valid' AND $table_atr.is_configurable = 'yes' AND $table_atr.backend_input = 'select' ORDER BY $table_atr.filter_position ASC";						
	$attributes_arr_4 = $wpdb->get_results( $atr_query_4, ARRAY_A );
		
	}
?>

<div class="group_attributes pars">
	<ul>
    <?php foreach ($system_pars_arr as $key_1 => $parameter) : ?>
    <li class="sys_item <?php echo $parameter['clas'] ?>">
    <div class="field_2<?php if($key_1 == 'product_type' and $prod_type != 'simple') { ?> produ_configurable<?php } ?>">
    <?php $item_id = 'par-'.$key_1; ?>
    <label for="<?php echo $item_id ?>"><?php echo $parameter['label'] ?></label>
	
	<?php 
	$meta_key_1 = $key_1;	
	add_post_meta($post->ID, $meta_key_1, '', true);	
	$post_meta_arr_3 = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = $post->ID AND meta_key = '$meta_key_1'", ARRAY_A );
	
	if (count($post_meta_arr_3)) : ///
	$meta_input_id = $post_meta_arr_3[0]['meta_id']; 
	$input_value = $post_meta_arr_3[0]['meta_value'];	
		$item_input_name = 'meta['.$meta_input_id.'][value]';
		$item_input_key = 'meta['.$meta_input_id.'][key]';	
?>
        
<?php if ($parameter['b_input'] == 'text' ) : ?>
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $key_1 ?>" />
<?php $input_mode = 1;
if ($key_1 == 'configurable_ids') { 
$child_args_8 = array( 'post_type' => get_post_type(), 'post_parent' => $post->ID, 'order' => 'ASC', 'orderby' => 'menu_order' );  $children = get_children( $child_args_8 );
if(count($children)) { $conf_ids_arr = array_keys($children);  $input_mode = 2; }
} // if ($key_1 == 'configurable_ids') 
?>
<?php if($input_mode == 2) { ?>
<span class="text"><?php echo implode(', ', $conf_ids_arr) ?></span>
<?php } else { ?>
<input type="text" name="<?php echo $item_input_name ?>" id="<?php echo $item_id ?>" value="<?php echo $input_value ?>"<?php if ($parameter['readonly']) { ?> readonly="readonly"<?php } ?> />  
<?php } ?>
    
<?php elseif ($parameter['b_input'] == 'checkbox' ) : ?>
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $key_1 ?>" />
<input type="checkbox" name="<?php echo $item_input_name ?>" id="<?php echo $item_id ?>" class="type-check" value="1" <?php if ($input_value == 1) { ?>checked="checked"<?php } ?> />

<?php elseif ($parameter['b_input'] == 'select' ) : ?> 
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $key_1 ?>" />    
<select name="<?php echo $item_input_name ?>" id="<?php echo $item_id ?>">
	<?php $options_arr = explode(", ", $parameter['values']); 
	if($key_1 == 'product_type' and in_array($prod_type, array('configurable', 'grouped'))) { $options_arr = array($prod_type); } ?>
		<?php foreach ($options_arr as $option) { ?>
        	<option value="<?php echo $option ?>" <?php if ($option == $input_value) { ?>selected="selected"<?php } ?>><?php echo $option ?></option>
		<?php } ?>
</select>



<?php elseif ($parameter['b_input'] == 'multiple-select' and $key_1 == 'configurable_atrs') : ?>
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $key_1 ?>" />    

<input type="hidden" name="<?php echo $item_input_name ?>" id="<?php echo $item_id ?>" value="<?php echo $input_value ?>" />
<?php $input_value_arr = explode(',', $input_value); ?>
	
    <span id="configurable_atrs_list_94">
    <?php foreach ($attributes_arr_4 as $atrib) { $atrib_id = 'conf-atr-'.$atrib['code']; ?> <span class="line_lab_2"> <label class="line_lab" for="<?php echo $atrib_id ?>"><?php echo $atrib['code'] ?></label> <input type="checkbox" id="<?php echo $atrib_id ?>" name="<?php echo 'conf_'.$atrib['code'] ?>" value="<?php echo $atrib['code'] ?>" onchange="set_configurable_atrs_9()"<?php if(in_array($atrib['code'], $input_value_arr)) { ?> checked="checked"<?php } ?> /> </span> <?php } ?>
	</span>

<script type="text/javascript">
function set_configurable_atrs_9() {
	var c_atrs_list = document.getElementById("configurable_atrs_list_94");
	var input_checks = c_atrs_list.getElementsByTagName("input");
	 var rez_text = ''; //
		for (var i = 0; i < input_checks.length; i++) {			
 			if(input_checks[i].checked == true) { //	 
			 symb_1 = ',';  if(rez_text == '') { symb_1 = ''; }
			rez_text += symb_1 + input_checks[i].value;		
			}
		}
	// alert(rez_text);
	var rez_item_id = document.getElementById("<?php echo $item_id ?>");
	rez_item_id.value = rez_text;
}
</script>

<?php endif; 
  endif; ?>   
 
    </div> <!-- field_2 -->
    </li>
    <?php endforeach; ?>
    </ul>
</div>
<?php /* if (function_exists('qtrans_getSortedLanguages')) : /// мета поля з title різними мовами 
global $q_config;
// print_r($q_config['enabled_languages']);
?>
<div class="lang_titles">
    <?php foreach ($q_config['enabled_languages'] as $lang) : ?>
    <div class="lango">   	
	<?php  echo $lang.'  ';
	$meta_key_1 = 'title_'.$lang;	
	$lang_tit = get_post_meta($post->ID, $meta_key_1, true);
	echo $lang_tit;
?>  
    </div> <!-- lango -->
    <?php endforeach; ?>
</div>
<?php endif;  */ ?>
<?php	
}





function post_prod_order_parent() { 
global $post; ?>
<div class="order_parent">
<ul>
    <li class="pare4">
  <?php if($post->post_parent) { $parent_id = $post->post_parent; echo '<div class="parent produ_configurable">'.get_the_title($parent_id).'</div>'; } ?>
    <label for="parent_id"><?php _e('Parent') ?></label>
    <input type="text" name="parent_id" id="parent_id" value="<?php echo $post->post_parent ?>" />
    </li>
    <li class="order">
    <label for="menu_order"><?php _e('Order') ?></label>
    <input type="text" name="menu_order" id="menu_order" value="<?php echo $post->menu_order ?>" />
    </li>
</ul>
</div>
<?php 
}



function post_duplicate_f() { 
global $post; 
if( $_REQUEST['action'] == 'edit' ) : ?>
<div class="duplicate">
<a class="page-title-action" href="<?php echo get_edit_post_link($post->ID); ?>&duplicate=1" target="_blank"><?php _e('Duplicate item') ?></a>
</div>
<?php endif; 
}

add_action('admin_init', 'redirect_duplicate_5'); // add_action('wp_loaded', 'redirect_duplicate_5');

function redirect_duplicate_5() {
	global $pagenow;
if( ($pagenow == 'post.php') and ($_REQUEST['duplicate'] == 1) ) : 
$post_id = $_REQUEST['post'];
$post = get_post($post_id);
$new_post = array(
  'post_title'    => $post->post_title.' (2)', 
  'post_name'   => $post->post_name,
  'post_content'  => $post->post_content,
  'post_parent'  => $post->post_parent, ////// //////
  'menu_order'  => $post->menu_order, ////// ////// 
  'post_excerpt'  => $post->post_excerpt,  
  'post_author'   => $post->post_author,
  'post_type'   => $post->post_type,
  'post_status'   => $post->post_status,
  'comment_status'  => $post->comment_status,
  'ping_status'   => 'closed',
);
	$post_id_last = wp_insert_post( $new_post, $wp_error ); //////// ////// 

	$taxonomy_names = get_object_taxonomies($post);  $taxonomy = $taxonomy_names[0];
	$terms = wp_get_post_terms($post_id, $taxonomy);
	if ($terms) {  $term_ids = array(); 
	foreach($terms as $ind_term) { $term_ids[] = $ind_term->term_id; }  
	wp_set_object_terms($post_id_last, $term_ids, $taxonomy);
	}
	
	$post_meta_arr = get_post_custom($post_id);
	unset( $post_meta_arr['sku'], $post_meta_arr['views'], $post_meta_arr['prod_sales'], $post_meta_arr['rating_total'], $post_meta_arr['rating_count'], $post_meta_arr['rating'], $post_meta_arr['_edit_lock'], $post_meta_arr['_edit_last'] );
	foreach ($post_meta_arr as $meta_key_1 => $a_value_arr) {
		foreach ($a_value_arr as $value_1) { add_post_meta($post_id_last, $meta_key_1, $value_1); }
	}

	$mesag_id = 6; 
	$page_url = admin_url().'post.php?post='.$post_id_last.'&action=edit&message='.$mesag_id;
	wp_safe_redirect( $page_url ); // wp_redirect( $page_url2, 301 ); // wp_safe_redirect( $page_url );
        exit;
endif; // ($pagenow == 'post.php') and ($_REQUEST['duplicate'] == 1) 
}




/* !!!! */
function wow_prod_vip_status_box() {
		// $status_arr = WOW_Checkout_my_item::vip_status_array();
		$vip_status_labels = WOW_Checkout_my_item::vip_status_array();
		$options_5 = get_option('wow_settings_arr');
		$status_arr = $options_5['wow_advertisement_vip']['avail'];
		global $post;
		$post_status = $post->pinged;
	echo '<div class="vip_status order_stat '.$post_status.'">'.$vip_status_labels[$post_status].'</div>';
		
		echo '<span>'.__('Change status').'</span> ';
		echo '<select name="pinged" id="pinged">';
		echo '<option value="">'.__('No status').'</option>';
		foreach ($status_arr as $opt_key => $val) {
			$label = $vip_status_labels[$opt_key];
			echo '<option value="'.$opt_key.'"'; if($opt_key == $post_status) { echo 'selected="selected"'; } echo '>'.$label.'</option>';
		} 
		echo '</select>';

/* ****** Зміна статусу старих матеріалів ******* */
echo '<br>';
$period = $options_5['wow_advertisement_period']; // echo $period.'<br>';
// $publ_date = get_the_time( 'Y-m-d' ); 
// 'Y-m-d H:i:s' // 2001-03-10 17:16:18 (формат MySQL DATETIME)
$date_now = date_create(date('Y-m-d')); // date_create('2009-10-11') 
// $date_1 = date_create($publ_date); // date_create('2009-10-11')
// $interval_2 = date_diff($date_1, $date_now);
// $interval = $interval_2->format('%R%a'); // echo $interval;
$date_old_4 = $date_now;
date_sub($date_old_4, date_interval_create_from_date_string($period.' days')); // ('30 days')
// date_add($date93, date_interval_create_from_date_string('10 days')); // додати 10 днів 
$date_old_5 = $date_old_4->format('Y-m-d');
$date_old_arr = explode('-', $date_old_5);
$old_year = $date_old_arr[0]; $old_month = $date_old_arr[1]; $old_day = $date_old_arr[2];
// echo $old_year.'. '.$old_month.'. '.$old_day;
        // $args4 = array( 'public' => true, '_builtin' => false ); $types_arr = get_post_types($args4);
		// unset($types_arr['wow_order'], $types_arr['wow_my_item_order'], $types_arr['c_form_order']);
		$p_types = WOW_Attributes_Front::get_attrSet_posttypes();
$posts_args_4 = array (       
        'post_type'  => $p_types,
		// 'order' => 'ASC',	
		// 'orderby' => 'date',		
		'post_status' => 'publish',
		'date_query' => array(
			array(
			'before'    => array(
				'year'  => $old_year,
				'month' => $old_month,
				'day'   => $old_day,
			),
			),	
		),	
);
$status_4_query = new WP_Query($posts_args_4);
if( $status_4_query->have_posts() ) {
	while ($status_4_query->have_posts()) :  $status_4_query->the_post();
	wp_update_post( array('ID' => $post->ID, 'post_status' => 'draft', 'pinged' => '') );
	endwhile; 
}  wp_reset_query(); // if( $status_4_query->have_posts() )
/* ****** ______ ******* */

}







function page_add_attributes() { // only attributes with type 'textarea' 
	global $wpdb;
	global $post;
	if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад
	$post_meta_arr = get_post_custom();
	$page_atrs_val_arr = array();  $page_attributes = array();
	
		$table_atr = WOW_TABLE_ATTRIBUTE; 	
	$b_input_arr = array('textarea', 'map');  $b_input_2 = "('".implode("', '", $b_input_arr)."')";
	$atr_query_8 = "SELECT $table_atr.id, $table_atr.code, $table_atr.backend_input, $table_atr.frontend_label FROM $table_atr 					
	WHERE $table_atr.status = 'valid' AND $table_atr.backend_input IN $b_input_2 ORDER BY $table_atr.filter_position ASC";						
	$attributes_arr_8 = $wpdb->get_results( $atr_query_8, ARRAY_A ); // масив усіх доступних атрибутів 

	$meta_key_5 = 'page_add_attributes_list';
	add_post_meta($post->ID, $meta_key_5, '', true);
	$post_meta_arr_5 = $wpdb->get_row( "SELECT * FROM $wpdb->postmeta WHERE post_id = $post->ID AND meta_key = '$meta_key_5'", ARRAY_A );
	$meta_input_id = $post_meta_arr_5['meta_id']; 
	$page_atrs_input_val = $post_meta_arr_5['meta_value'];
	if($page_atrs_input_val) { $page_atrs_val_arr = explode(',', $page_atrs_input_val); }
	$page_atrs_input_name = 'meta['.$meta_input_id.'][value]';
	$page_atrs_input_key = 'meta['.$meta_input_id.'][key]';
	$page_atrs_line_id = 'page_add_attributes_box_9';
?>
<div class="box_5 choose_attrib">
<div> <em><?php _e('To see more available attributes, please, add new attributes with type "textarea", "map".'); ?></em> <br /><br /> </div>

<input type="hidden" name="<?php echo $page_atrs_input_key ?>" value="<?php echo $meta_key_5 ?>" />
<input type="hidden" name="<?php echo $page_atrs_input_name ?>" id="<?php echo $page_atrs_line_id ?>" value="<?php echo $page_atrs_input_val ?>" />

 <div class="show_b"><a onclick="page_atrs_9_show_list(this)"><?php _e('Show attributes list') ?></a></div>   
<div class="attrs_list">	
    <span id="page_atrs_list_95">
    <?php foreach ($attributes_arr_8 as $atrib) { 
	if(in_array($atrib['code'], $page_atrs_val_arr)) { $page_attributes[] = $atrib; }
	$atrib_id = 'page-atr-'.$atrib['code']; ?> 
    <span class="line_lab_2"> <label class="line_lab" for="<?php echo $atrib_id ?>"><?php echo $atrib['code'] ?></label> <input type="checkbox" id="<?php echo $atrib_id ?>" name="<?php echo 'page_'.$atrib['code'] ?>" value="<?php echo $atrib['code'] ?>" onchange="set_page_atrs_9()"<?php if(in_array($atrib['code'], $page_atrs_val_arr)) { ?> checked="checked"<?php } ?> /> </span>
	<?php } ?>
	</span>
<div> <em><?php _e('Please, check fields and save page to see new fields.'); ?></em> </div>
</div>

<script type="text/javascript">
function set_page_atrs_9() {
	var c_atrs_list = document.getElementById("page_atrs_list_95");
	var input_checks = c_atrs_list.getElementsByTagName("input");
	 var rez_text = ''; //
		for (var i = 0; i < input_checks.length; i++) {			
 			if(input_checks[i].checked == true) { //	 
			 symb_1 = ',';  if(rez_text == '') { symb_1 = ''; }
			rez_text += symb_1 + input_checks[i].value;		
			}
		}
	var rez_item_id = document.getElementById("<?php echo $page_atrs_line_id ?>");
	rez_item_id.value = rez_text;
}

function page_atrs_9_show_list(elem) {
	var blok_1 = elem.parentNode.parentNode;
	blok_1.className += ' act';
}
</script>

</div>
    
<?php if( count($page_attributes) ) : ?>    
    <div class="page_attributes group_attributes"> <br />
    <ul>
    <?php foreach ($page_attributes as $attribute) : 
			if (function_exists('qtrans_getSortedLanguages')) { // Переклад	
		$attribute['frontend_label'] = qtrans_use($q_config['language'], $attribute['frontend_label'], true);
			} // -- Переклад 
	?>
    <li class="atr_item">
    <div class="field_2">
    <?php $item_id = 'atr-'.$attribute['code']; ?>
    <label for="<?php echo $item_id ?>"><?php echo $attribute['frontend_label'] ?> <br /> <span class="small-code"> <?php echo $attribute['code'] ?></span></label>
	
	<?php 
	$meta_key_1 = $attribute['code'];
	add_post_meta($post->ID, $meta_key_1, '', true);	
	$post_meta_arr_3 = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = $post->ID AND meta_key = '$meta_key_1'", ARRAY_A );
	
	if (count($post_meta_arr_3)) { ///
	$meta_input_id = $post_meta_arr_3[0]['meta_id']; 
	$input_value = $post_meta_arr_3[0]['meta_value'];	
	$item_input_name = 'meta['.$meta_input_id.'][value]';
	$item_input_key = 'meta['.$meta_input_id.'][key]';
	?>

<?php if ($attribute['backend_input'] == 'map' ) { 
  attribute_map_input($attribute['code'], $input_value, $item_input_key, $item_input_name); ?>
<?php } else { // textarea ?>
<input type="hidden" name="<?php echo $item_input_key ?>" value="<?php echo $attribute['code'] ?>" />
<textarea name="<?php echo $item_input_name ?>" class="atr_textarea" id="<?php echo $item_id ?>"><?php echo $input_value ?></textarea>
<?php } ?>
    
    <?php } // if (count($post_meta_arr_3)) 
	else { ?>
    <div class="no_attrib"><?php _e('Please, reload page to see the attribute.'); ?></div>
    <?php } ?>
    </div> <!-- field_2 -->
    </li>
    <?php endforeach; ?>
    </ul>
    </div>	
<?php endif; 
		
}






function save_post_lang_titles($post_id) { /// мета поля з title різними мовами - 'title_ua' ....
if (function_exists('qtrans_getSortedLanguages')) : /// мета поля з title різними мовами 
$post_type = get_post_type($post_id);
$types_arr = get_post_types( array('public' => true, 'publicly_queryable' => true, '_builtin' => false ) );  
 unset($types_arr['wow_order'], $types_arr['wow_my_item_order']);
 
 if(in_array($post_type, $types_arr)) {
global $q_config;
	$post_7 = get_post($post_id); 
	$post_title = $post_7->post_title;	
	foreach ($q_config['enabled_languages'] as $lang) :
		$lang_title = qtrans_use($lang, $post_title, true);
		if(strpos($lang_title, '</p>') !== false) { $lang_title = ''; }
		$meta_key_1 = 'title_'.$lang;
		update_post_meta($post_id, $meta_key_1, $lang_title);
	endforeach;
 } // if(in_array($post_type, $types_arr))
endif; // qtrans 
}

add_action( 'save_post', 'save_post_lang_titles' );


?>