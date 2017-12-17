<?php 
class WOW_Attributes_Front {

function get_attributeSet_id($post_id) {
	global $wpdb;
	global $post;
	if($post_id) { 
	if (is_numeric($post_id)) { $p_type = get_post_type($post_id); } else { $p_type = $post_id; }	
	$par_where = "set_post_type = '$p_type'";	
	} // if($post_id)
	else { // для категорії товарів (можлива наявність різних наборів атрибутів)
	// $post_type = get_post_type($post); 
	global $wp_query;
	$post_types_arr = array();
		if(is_tax() or is_category()) {
	$taxo = $wp_query->tax_query->queries[0]['taxonomy'];
	$post_types_arr = get_taxonomy($taxo)->object_type; // get_taxonomy($taxo)->object_type[0]	
		}
	$post_types = "('".implode("', '", $post_types_arr)."')";		
	$par_where = "set_post_type IN $post_types";
	} // if( !$post_id )	
	// $atr_set_data = $wpdb->get_row( "SELECT * FROM " . WOW_TABLE_ATTRIBUTE_SET . " WHERE set_post_type = '$p_type'", ARRAY_A );	
	$atr_set_data = (array) $wpdb->get_results( "SELECT * FROM " . WOW_TABLE_ATTRIBUTE_SET . " WHERE " . $par_where, ARRAY_A );
	$atr_set_ids = array();
	foreach ($atr_set_data as $atr_set) { $atr_set_ids[] = $atr_set['id']; }
	/* !!!! */ if(!count($atr_set_ids)) { $atr_set_ids = array(1); } ///// 
		if($post_id) { 
	$attributeSet_id = $atr_set_ids[0];
	return $attributeSet_id;
		} else {
	return  $atr_set_ids;	
		}	
}

function get_attributeSet_name($id) {
	global $wpdb;
	$atr_set_data = (array) $wpdb->get_row( "SELECT * FROM " . WOW_TABLE_ATTRIBUTE_SET . " WHERE id = $id", ARRAY_A );
	$name = $atr_set_data['name'];
	return $name;
}

function get_attrSet_posttypes() {
	global $wpdb;
	$atr_sets = (array) $wpdb->get_results("SELECT * FROM " . WOW_TABLE_ATTRIBUTE_SET . " WHERE status = 'valid' ORDER BY position ASC", ARRAY_A);
	$types = array();
 foreach ($atr_sets as $atr_set) { 
 	if($atr_set['is_no_product'] != 1) { $types[] = $atr_set['set_post_type']; } 
 }
	return $types;
}

function get_attrSet_posttypes_no_prod() {
	global $wpdb;
	$atr_sets_no = (array) $wpdb->get_results("SELECT * FROM " . WOW_TABLE_ATTRIBUTE_SET . " WHERE status = 'valid' AND is_no_product = 1 ORDER BY position ASC", ARRAY_A);
	$types_no_prod = array();
 foreach ($atr_sets_no as $atr_set) { $types_no_prod[] = $atr_set['set_post_type']; }
	return $types_no_prod;
}


function post_view_attributes($post_id) {
		global $wpdb;
		// global $post;
		if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад	
		
		$post_meta_arr = get_post_custom($post_id);
		$attrib_arr = array();		
	// $attributeSet_id = 1;
	 $attributeSet_id = WOW_Attributes_Front::get_attributeSet_id($post_id);

	$table_atr = WOW_TABLE_ATTRIBUTE;  $table_details = WOW_TABLE_ATTRIBUTE_SECTION_DET;
	$table_section = WOW_TABLE_ATTRIBUTE_SET_SECTION;
	if (is_single()) { $par_visible2 = ""; } else { $par_visible2 = "AND $table_atr.is_visible_in_front_listing = 'yes'"; }
	$atr_query = "SELECT DISTINCT $table_atr.id, $table_atr.code, $table_atr.backend_input, $table_atr.frontend_label, $table_atr.frontend_unit, $table_section.id AS group_id, $table_section.code AS group_code, $table_section.name AS group_name
				FROM $table_atr 			
				LEFT JOIN $table_details ON ($table_atr.id = $table_details.attribute_id )
				LEFT JOIN $table_section ON ($table_details.attribute_group_id = $table_section.id )
				LEFT JOIN $wpdb->postmeta ON ($wpdb->postmeta.meta_key = $table_atr.code AND $wpdb->postmeta.post_id = $post_id )
				WHERE $table_section.attribute_set_id = $attributeSet_id AND $table_atr.status = 'valid' AND $table_atr.is_visible_in_front = 'yes' ".$par_visible2." AND $wpdb->postmeta.meta_value != ''
				ORDER BY $table_section.position ASC, $table_details.position ASC"; 
	$attributes_arr_2 = (array) $wpdb->get_results( $atr_query, ARRAY_A );
		
	foreach ($attributes_arr_2 as $key2 => $attribute) :
		$attrib_1 = array('id' => $attribute['id'], 'code' => $attribute['code'], 'frontend_label' => $attribute['frontend_label'], 'frontend_unit' => $attribute['frontend_unit'], 'backend_input' => $attribute['backend_input']);
		$attribute['first_atr'] = 0;
		if($key2 == 0 or $attribute['group_id'] != $attributes_arr_2[$key2-1]['group_id']) { $attribute['first_atr'] = 1; }
		/// $attrib_1['first_atr'] = $attribute['first_atr']; 
		if(function_exists('qtrans_getSortedLanguages')) { // Переклад				
			$attrib_1['frontend_label'] = qtrans_use($q_config['language'], $attrib_1['frontend_label'], true);
			if($attrib_1['frontend_unit']) { $attrib_1['frontend_unit'] = qtrans_use($q_config['language'], $attrib_1['frontend_unit'], true); }
			if($attribute['first_atr'] == 1) { $attribute['group_name'] = qtrans_use($q_config['language'], $attribute['group_name'], true); }
		} // -- Переклад
	$atr_values = $post_meta_arr[$attribute['code']];
	if($atr_values) {  //	
		foreach ($atr_values as $key4 => $m_value) {  
		if($attribute['backend_input'] == 'checkbox') { if($m_value == '') { $atr_values[$key4] = __('No'); } else { $atr_values[$key4] = __('Yes'); } }
		elseif ($m_value != '') {		
		if(in_array($attribute['backend_input'], array('select', 'multiple-select'))) { 
		$option_1 = (array) $wpdb->get_row( "SELECT * FROM " . WOW_TABLE_ATTR_OPTIONS . " WHERE id = $m_value ", ARRAY_A );  $atr_values[$key4] = $option_1['label']; 
		}	
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад				
			$atr_values[$key4] = qtrans_use($q_config['language'], $atr_values[$key4], true);	 
		} // -- Переклад
		 }
		 else { unset($atr_values[$key4]); }
		} // foreach ($atr_values as $key4 => $m_value)		
		
		if (count($atr_values)) { /// //////////////////////////////
		$attrib_1['atr_value'] = $atr_values; 
		$group_id = $attribute['group_id'];
		if($attribute['first_atr'] == 1) { $attrib_arr[$group_id] = array('code' => $attribute['group_code'], 'name' => $attribute['group_name']); }
		$attrib_arr[$group_id]['items'][$attribute['code']] = $attrib_1;
		} /// ////////////////////////////// 	
	} // if($atr_values)	
	endforeach; // ($attributes_arr_2 as $key2 => $attribute)
		
		return $attrib_arr;
}


function post_view_my_group_attributes($post_id, $group_code) {
		global $wpdb;
		// global $post;
		if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад			
		$post_meta_arr = get_post_custom($post_id);		
	// $attributeSet_id = 1;
	 $attributeSet_id = WOW_Attributes_Front::get_attributeSet_id($post_id);

	$table_atr = WOW_TABLE_ATTRIBUTE; 
	$table_section = WOW_TABLE_ATTRIBUTE_SET_SECTION;  $table_details = WOW_TABLE_ATTRIBUTE_SECTION_DET;
	$attrib_arr = array();	
	$atr_query = "SELECT $table_atr.id, $table_atr.code, $table_atr.backend_input, $table_atr.frontend_label, $table_atr.frontend_unit FROM $table_atr 				
				LEFT JOIN $table_details ON ($table_atr.id = $table_details.attribute_id )
				LEFT JOIN $table_section ON ($table_details.attribute_group_id = $table_section.id )
				WHERE $table_section.attribute_set_id = $attributeSet_id AND $table_section.code = '$group_code' AND $table_atr.status = 'valid'  
				ORDER BY $table_details.position ASC";	
	$attributes_arr_2 = (array) $wpdb->get_results( $atr_query, ARRAY_A );
	
	foreach ($attributes_arr_2 as $key2 => $attribute) :
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад				
			$attributes_arr_2[$key2]['frontend_label'] = qtrans_use($q_config['language'], $attribute['frontend_label'], true);
			if($attribute['frontend_unit']) { $attributes_arr_2[$key2]['frontend_unit'] = qtrans_use($q_config['language'], $attribute['frontend_unit'], true); }	 
		} // -- Переклад
	$atr_values = $post_meta_arr[$attribute['code']];
	if($atr_values) { 	///	
		foreach ($atr_values as $key4 => $m_value) {  
		if($attribute['backend_input'] == 'checkbox') { if($m_value == '') { $atr_values[$key4] = __('No'); } else { $atr_values[$key4] = __('Yes'); } }
		elseif ($m_value != '') {		
		if(in_array($attribute['backend_input'], array('select', 'multiple-select'))) { 
		$option_1 = (array) $wpdb->get_row( "SELECT * FROM " . WOW_TABLE_ATTR_OPTIONS . " WHERE id = $m_value ", ARRAY_A );  $atr_values[$key4] = $option_1['label']; 
		}	
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад				
			$atr_values[$key4] = qtrans_use($q_config['language'], $atr_values[$key4], true);	 
		} // -- Переклад
		 }
		 else { unset($atr_values[$key4]); }
		} // foreach ($atr_values as $key4 => $m_value)		
		
		if (count($atr_values)) { $attributes_arr_2[$key2]['atr_value'] = $atr_values; } else { unset($attributes_arr_2[$key2]); }		
	} // if($atr_values)
	else { unset($attributes_arr_2[$key2]); }
	endforeach; // ($attributes_arr_2 as $key2 => $attribute)
	
	if (count($attributes_arr_2)) { $attrib_arr = $attributes_arr_2; }		
		return $attrib_arr;
}


function post_view_one_attribute($post_id, $atr_code) {
		global $wpdb;
		// global $post;
		if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад	
		
		$post_meta_arr = get_post_custom($post_id);		
	// $attributeSet_id = 1;
	$attributeSet_id = WOW_Attributes_Front::get_attributeSet_id($post_id);
	
	$table_atr = WOW_TABLE_ATTRIBUTE;  $table_details = WOW_TABLE_ATTRIBUTE_SECTION_DET; 
	$par_a_set = " AND $table_details.attribute_set_id = $attributeSet_id";
	$post_type = get_post_type($post_id);  if($post_type == 'page') { $par_a_set = ''; }
	
	$atr_query = "SELECT $table_atr.id, $table_atr.code, $table_atr.backend_input, $table_atr.frontend_label, $table_atr.frontend_unit FROM $table_atr 			
				LEFT JOIN $table_details ON ($table_atr.id = $table_details.attribute_id )
				WHERE $table_atr.code = '$atr_code' AND $table_atr.status = 'valid'".$par_a_set; 				
	$attribute = (array) $wpdb->get_row( $atr_query, ARRAY_A );
	
	if($attribute) :
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад				
		$attribute['frontend_label'] = qtrans_use($q_config['language'], $attribute['frontend_label'], true);
		if($attribute['frontend_unit']) { $attribute['frontend_unit'] = qtrans_use($q_config['language'], $attribute['frontend_unit'], true); }		 
		} // -- Переклад
	
	$attribute['atr_value'] = 0;
	$atr_values = $post_meta_arr[$attribute['code']];
	if($atr_values) { 	///	
		foreach ($atr_values as $key4 => $m_value) {  
		if($attribute['backend_input'] == 'checkbox') { if($m_value == '') { $atr_values[$key4] = __('No'); } else { $atr_values[$key4] = __('Yes'); } }
		elseif ($m_value != '') {		
		if(in_array($attribute['backend_input'], array('select', 'multiple-select'))) { $option_1 = (array) $wpdb->get_row( "SELECT * FROM " . WOW_TABLE_ATTR_OPTIONS . " WHERE id = $m_value ", ARRAY_A );  $atr_values[$key4] = $option_1['label']; }	
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад				
			$atr_values[$key4] = qtrans_use($q_config['language'], $atr_values[$key4], true);	 
		} // -- Переклад
		 }
		 else { unset($atr_values[$key4]); }
		} // foreach ($atr_values as $key4 => $m_value)		
		
		if (count($atr_values)) { $attribute['atr_value'] = $atr_values; } 
	} // if($atr_values)	
	endif; // if($attribute) 
	
		return $attribute;
}



function get_attribute_labels($atr_code) {
		global $wpdb;
		if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад	
	
	$table_atr = WOW_TABLE_ATTRIBUTE; 
	$atr_query = "SELECT $table_atr.frontend_label, $table_atr.frontend_label_2, $table_atr.frontend_unit FROM $table_atr WHERE $table_atr.code = '$atr_code' AND $table_atr.status = 'valid' ";			
	$attr_labels = (array) $wpdb->get_row( $atr_query, ARRAY_A );
	
	if($attr_labels) :
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад			
		$attr_labels['frontend_label'] = qtrans_use($q_config['language'], $attr_labels['frontend_label'], true);
		if($attr_labels['frontend_label_2']) { $attr_labels['frontend_label_2'] = qtrans_use($q_config['language'], $attr_labels['frontend_label_2'], true); }
		if($attr_labels['frontend_unit']) { $attr_labels['frontend_unit'] = qtrans_use($q_config['language'], $attr_labels['frontend_unit'], true); }		 
		} // -- Переклад
	endif; // if($attribute) 
	
		return $attr_labels;
}



function post_compare_attributes() {
		global $wpdb;
		$attrib_arr = array();
		if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад	
		
	$compare_array = WOW_Compare_Session::compare_array();
	$prod_ids = "(".implode(", ", $compare_array).")";
	$query5 = "SELECT $wpdb->postmeta.* FROM $wpdb->postmeta WHERE $wpdb->postmeta.post_id IN $prod_ids
ORDER BY $wpdb->postmeta.meta_key ASC";
	$meta_arr_5 = (array) $wpdb->get_results( $query5, ARRAY_A );
	
	$atr_meta_arr_25 = array(); // всі атрибути, які присутні в товарах зі списку порівняння
	foreach ($meta_arr_5 as $meta_1) {
		if($meta_1['meta_value'] != '' and !preg_match("/[^a-z]/", $meta_1['meta_key'][0])) { 
		$atr_meta_arr_25[$meta_1['meta_key']] = $meta_1['meta_key'];	
		}
	}
	$meta_keys_comp = "('".implode("', '", $atr_meta_arr_25)."')";
			
	$table_atr = WOW_TABLE_ATTRIBUTE; // 
	$atr_query = "SELECT $table_atr.id, $table_atr.code, $table_atr.backend_input, $table_atr.frontend_label, $table_atr.frontend_unit FROM $table_atr WHERE $table_atr.status = 'valid' AND $table_atr.is_comparable = 'yes' AND $table_atr.code IN $meta_keys_comp ORDER BY $table_atr.filter_position ASC";
	$attributes_arr_2 = (array) $wpdb->get_results( $atr_query, ARRAY_A );
		
	if (count($attributes_arr_2)) :		
	foreach ($compare_array as $prod_id) { ///// ///// /////
	$post_meta_arr = get_post_custom($prod_id);
	
	foreach ($attributes_arr_2 as $key2 => $attribute) : 
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад			
			$attributes_arr_2[$key2]['frontend_label'] = qtrans_use($q_config['language'], $attribute['frontend_label'], true);		
			if($attribute['frontend_unit']) { $attributes_arr_2[$key2]['frontend_unit'] = qtrans_use($q_config['language'], $attribute['frontend_unit'], true); }		 
		} // -- Переклад
	$atr_values = $post_meta_arr[$attribute['code']];
	if($atr_values) { 	///	
		foreach ($atr_values as $key4 => $m_value) {  
		if($attribute['backend_input'] == 'checkbox') { if($m_value == '') { $atr_values[$key4] = __('No'); } else { $atr_values[$key4] = __('Yes'); } }
		elseif ($m_value != '') {		
		if(in_array($attribute['backend_input'], array('select', 'multiple-select'))) { $option_1 = (array) $wpdb->get_row( "SELECT * FROM " . WOW_TABLE_ATTR_OPTIONS . " WHERE id = $m_value ", ARRAY_A ); $atr_values[$key4] = $option_1['label']; }	
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад				
			$atr_values[$key4] = qtrans_use($q_config['language'], $atr_values[$key4], true);	 
		} // -- Переклад
		 }
		 else { unset($atr_values[$key4]); } // if ($m_value == '')
		} // foreach ($atr_values as $key4 => $m_value)		
		
		if (count($atr_values)) { $attributes_arr_2[$key2]['atr_value'] = $atr_values; } else { $attributes_arr_2[$key2]['atr_value'] = array('-'); }		
	} // if($atr_values)
	else { $attributes_arr_2[$key2]['atr_value'] = array('-'); }
	endforeach; /// ($attributes_arr_2 as $key2 => $attribute)
	
	$attrib_arr[$prod_id] = $attributes_arr_2;

	} // /// ////////////// foreach ($compare_array as $prod_id)
	endif; // (count($attributes_arr_2))
		
		return $attrib_arr;
}



function attributes_filter($filt) {
		global $wpdb;
		global $wp_query;
		global $post;
		if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад		
	$table_atr = WOW_TABLE_ATTRIBUTE;  $table_details = WOW_TABLE_ATTRIBUTE_SECTION_DET;
	
	if($filt != 'advanced') { // normal filter 
	// $attributeSet_id = 1;
	$attributeSet_ids = WOW_Attributes_Front::get_attributeSet_id(''); // array // (можлива наявність різних наборів атрибутів)
	$set_ids = "(".implode(", ", $attributeSet_ids).")";		
	$atr_query = "SELECT $table_atr.id, $table_atr.code, $table_atr.backend_input, $table_atr.frontend_label, $table_atr.frontend_unit FROM $table_atr 			
				LEFT JOIN $table_details ON ($table_atr.id = $table_details.attribute_id )
				WHERE $table_details.attribute_set_id IN $set_ids AND $table_atr.status = 'valid' AND $table_atr.is_filterable = 'yes' 
				ORDER BY $table_atr.filter_position ASC";	
	}
	else { // advanced filter 
	$atr_query = "SELECT $table_atr.id, $table_atr.code, $table_atr.backend_input, $table_atr.frontend_label, $table_atr.frontend_unit FROM $table_atr 				
				WHERE $table_atr.status = 'valid' AND $table_atr.is_visible_in_advanced_search = 'yes' 
				ORDER BY $table_atr.filter_position ASC";
	}
	$filt_attrib_arr = (array) $wpdb->get_results( $atr_query, ARRAY_A );

	if(is_archive()) : // is_archive() 
	$queried_object = $wp_query->queried_object;
	$term_id = $queried_object->term_id;
	$taxonomy_name = $queried_object->taxonomy;
	$term_ids = "('')"; ///	
		if($term_id) : 
	$termchildren = get_term_children( $term_id, $taxonomy_name );
	$term_ids_2 = array_merge(array($term_id), $termchildren);		
	$term_ids = "(".implode(", ", $term_ids_2).")";
		endif;
	else : // !is_archive() -- 'advanced' filter 
	$p_types = WOW_Attributes_Front::get_attrSet_posttypes();
	$p_types_2 = "('".implode("', '", $p_types)."')";
	endif;
			
	foreach ($filt_attrib_arr as $key2 => $attribute) : 
	
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад	
			$filt_attrib_arr[$key2]['frontend_label'] = qtrans_use($q_config['language'], $attribute['frontend_label'], true);
			if($attribute['frontend_unit']) { $filt_attrib_arr[$key2]['frontend_unit'] = qtrans_use($q_config['language'], $attribute['frontend_unit'], true); } 
		} // -- Переклад	
	$atr_id = $attribute['id'];  $atr_code = $attribute['code'];
	
	if (in_array($attribute['backend_input'], array('select', 'multiple-select'))) { 	
	$table_options = WOW_TABLE_ATTR_OPTIONS;	
	$options_arr = array();
	if(is_archive()) : // is_archive() -- normal filter 
	$options_query = "SELECT DISTINCT $table_options.* FROM $table_options 
INNER JOIN $wpdb->postmeta ON ($wpdb->postmeta.meta_key = '$atr_code' AND $wpdb->postmeta.meta_value = $table_options.id)
INNER JOIN $wpdb->term_relationships ON ($wpdb->postmeta.post_id = $wpdb->term_relationships.object_id)
INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) 
INNER JOIN $wpdb->posts ON ($wpdb->postmeta.post_id = $wpdb->posts.ID)
	WHERE $table_options.attribute_id = $atr_id AND $wpdb->term_taxonomy.term_id IN $term_ids AND $wpdb->posts.post_status = 'publish'
	ORDER BY $table_options.position ASC ";
	else : // !is_archive() -- 'advanced' filter 
	$where_childs = " AND $wpdb->posts.post_parent = 0"; // IN (0, '')
	$options_query = "SELECT DISTINCT $table_options.* FROM $table_options 
INNER JOIN $wpdb->postmeta ON ($wpdb->postmeta.meta_key = '$atr_code' AND $wpdb->postmeta.meta_value = $table_options.id)
INNER JOIN $wpdb->posts ON ($wpdb->postmeta.post_id = $wpdb->posts.ID)
	WHERE $table_options.attribute_id = $atr_id AND $wpdb->posts.post_type IN $p_types_2 AND $wpdb->posts.post_status = 'publish'".$where_childs." 
	ORDER BY $table_options.position ASC ";
	endif;	
	$options_arr = (array) $wpdb->get_results( $options_query, ARRAY_A );
		if (count($options_arr)) { ////// //////////////////
	$filt_attrib_arr[$key2]['atr_options'] = $options_arr; ///	
			if (function_exists('qtrans_getSortedLanguages')) { // Переклад	
				foreach ($options_arr as $key4 => $o_value) {
 $filt_attrib_arr[$key2]['atr_options'][$key4]['label'] = qtrans_use($q_config['language'], $o_value['label'], true);	
				}
			} // -- Переклад	
		} ////// ////////////////// if (count($options_arr))
		else { unset($filt_attrib_arr[$key2]); }
	} // if ('select', 'multiple-select')
	
	elseif ($attribute['backend_input'] == 'checkbox') {	
	$filt_attrib_arr[$key2]['atr_options'][0] = array('id' => 1, 'label' => $filt_attrib_arr[$key2]['frontend_label']); 
	}
	
	elseif ($attribute['backend_input'] == 'text') {  
	$m_values_arr = array();
	$m_values_query_select = "SELECT MIN(CAST(meta_value AS DECIMAL(15, 3))) AS val_min, MAX(CAST(meta_value AS DECIMAL(15, 3))) AS val_max FROM $wpdb->postmeta ";
	if(is_archive()) : // is_archive() -- normal filter // 
	$m_values_query = $m_values_query_select."INNER JOIN $wpdb->term_relationships ON ($wpdb->postmeta.post_id = $wpdb->term_relationships.object_id)
INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) 
INNER JOIN $wpdb->posts ON ($wpdb->postmeta.post_id = $wpdb->posts.ID)
	WHERE $wpdb->postmeta.meta_key = '$atr_code' AND $wpdb->term_taxonomy.term_id IN $term_ids AND $wpdb->posts.post_status = 'publish' ";
	else : // !is_archive() -- 'advanced' filter 
	$m_values_query = $m_values_query_select."INNER JOIN $wpdb->posts ON ($wpdb->postmeta.post_id = $wpdb->posts.ID)
	WHERE $wpdb->postmeta.meta_key = '$atr_code' AND $wpdb->posts.post_type IN $p_types_2 AND $wpdb->posts.post_status = 'publish' ";
	endif;	
	$m_values_arr = (array) $wpdb->get_results( $m_values_query, ARRAY_A );
	$m_values_arr = array_unique($m_values_arr[0]);
		if(count($m_values_arr) > 1) { 
	$attrib_val_min = $m_values_arr['val_min'];
	$attrib_val_max = $m_values_arr['val_max'];
	$step_2 = ($attrib_val_max - $attrib_val_min) / 200;
	if($step_2 >= 0.6) { $attrib_val_step = round($step_2); $attrib_val_min = floor($attrib_val_min); $attrib_val_max = ceil($attrib_val_max); } 
	elseif($step_2 >= 0.1) { $attrib_val_step = round($step_2, 1); } 
	else { $attrib_val_step = round($step_2, 2); }	
	// $filt_attrib_arr[$key2]['atr_options'][0] = 0; // ?
	$filt_attrib_arr[$key2]['atr_text_val'] = array($attrib_val_min, $attrib_val_max);
	$filt_attrib_arr[$key2]['atr_text_val_step'] = $attrib_val_step;	
	if(strpos($attribute['code'], 'price') !== false) { ///
		$options_5 = get_option('wow_settings_arr');
	$act_currency_arr = WOW_Product_List_Func::get_act_currency();
	// $act_currency = $act_currency_arr['code'];
	$symb = $act_currency_arr['symbol'];
	$kurs = $act_currency_arr['rate'];	
$round_to = 0; if($options_5['wow_currency_precision']) { $round_to = $options_5['wow_currency_precision']; }
		$filt_attrib_arr[$key2]['atr_text_currency_kurs'] = $kurs;
		$filt_attrib_arr[$key2]['atr_text_round_to'] = $round_to;
		$filt_attrib_arr[$key2]['frontend_unit'] = $symb;
	} ///	
		} // if(count($m_values_arr) > 1)
		else { unset($filt_attrib_arr[$key2]); }
	} // ($attribute['backend_input'] == 'text')
	
	endforeach; // ($filt_attrib_arr as $key2 => $attribute)
			
		return $filt_attrib_arr;
}

/* 
function attributes_filter_ultri() {
	 $atr_filter_full = WOW_Attributes_Front::attributes_filter();
	 $meta_avail = WOW_Attributes_Front::attributes_meta_avail();
	$atr_filter = array();
	foreach ($atr_filter_full as $key2 => $attribute2) :
	if($meta_avail[$attribute2['code']]) {
		$atr_filter[$key2] = $attribute2; unset($atr_filter[$key2]['atr_options']);
		foreach ($attribute2['atr_options'] as $key4 => $option) {
			if($meta_avail[$attribute2['code']][$option['id']]) { $atr_filter[$key2]['atr_options'][] = $option; }
		}
		}
	endforeach;
	
	return $atr_filter;
}
 */

function attributes_active_filters() {
if($_GET) : 
		global $wpdb;
		global $post;
		if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад		
	$table_atr = WOW_TABLE_ATTRIBUTE;  
	$request_arr = $_GET;
	$act_filters_2 = array_keys($request_arr);
	$act_filters = "('".implode("', '", $act_filters_2)."')";
	$atr_query_6 = "SELECT $table_atr.id, $table_atr.code, $table_atr.backend_input, $table_atr.frontend_label, $table_atr.frontend_unit FROM $table_atr 						
				WHERE $table_atr.code IN $act_filters AND $table_atr.status = 'valid' AND $table_atr.is_filterable = 'yes' ORDER BY $table_atr.filter_position ASC";
	$active_filt_arr_6 = (array) $wpdb->get_results( $atr_query_6, ARRAY_A );
	$active_filt_arr = array();
	
	foreach ($active_filt_arr_6 as $key6 => $attribute) : 
		$key2 = $attribute['code'];
		$active_filt_arr[$key2] = $attribute;
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад	
			$active_filt_arr[$key2]['frontend_label'] = qtrans_use($q_config['language'], $attribute['frontend_label'], true);
			if($attribute['frontend_unit']) { $active_filt_arr[$key2]['frontend_unit'] = qtrans_use($q_config['language'], $attribute['frontend_unit'], true); } 
		} // -- Переклад
	
	if (in_array($attribute['backend_input'], array('select', 'multiple-select'))) { 
	$options_ids_arr = explode('-', $request_arr[$attribute['code']]);
	$options_ids = "('".implode("', '", $options_ids_arr)."')";
	$options_arr = (array) $wpdb->get_results( "SELECT * FROM " . WOW_TABLE_ATTR_OPTIONS . " WHERE id IN $options_ids ORDER BY position ASC ", ARRAY_A );
		if (count($options_arr)) { 
	$active_filt_arr[$key2]['atr_options'] = $options_arr; ///
		} // if (count($options_arr))
		else { unset($active_filt_arr[$key2]); }
	} // if ('select', 'multiple-select')
	
	elseif ($attribute['backend_input'] == 'checkbox') {	
	$active_filt_arr[$key2]['atr_options'][0] = array('id' => 1, 'label' => $attribute['frontend_label']); 
	}
	
	elseif ($attribute['backend_input'] == 'text') {	
$symb = $attribute['frontend_unit'];
$kurs = 1;
$round_to = 0;
	if(strpos($attribute['code'], 'price') !== false) { ///
		$options_5 = get_option('wow_settings_arr');
	$act_currency_arr = WOW_Product_List_Func::get_act_currency();
	$symb = $act_currency_arr['symbol'];
	$kurs = $act_currency_arr['rate'];	
	if($options_5['wow_currency_precision']) { $round_to = $options_5['wow_currency_precision']; }
	} ///	'price' 
$act_values = explode('--', $request_arr[$attribute['code']]);
$value_1 = round(($act_values[0] * $kurs), $round_to);  $value_2 = round(($act_values[1] * $kurs), $round_to);
$active_filt_arr[$key2]['value'] = $value_1.'<span> '.$symb.'</span> <span class="sep">-</span> '.$value_2.'<span> '.$symb.'</span>';
	} // ($attribute['backend_input'] == 'text')
	
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад				
			if (in_array($attribute['backend_input'], array('select', 'multiple-select', 'checkbox'))) {
			if($active_filt_arr[$key2]['atr_options']) {
			foreach ($active_filt_arr[$key2]['atr_options'] as $key4 => $o_value) {
			$active_filt_arr[$key2]['atr_options'][$key4]['label'] = qtrans_use($q_config['language'], $o_value['label'], true);	
			}
			}
			}
		} // -- Переклад	
	
	endforeach; // ($active_filt_arr as $key2 => $attribute)
			
		return $active_filt_arr; // $active_filt_arr $act_filters 
endif; // if($_GET) 
}

 

function attributes_sorting() {
		global $wpdb;
		if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад		
	// $attributeSet_id = 1;	
	$attributeSet_ids = WOW_Attributes_Front::get_attributeSet_id(''); // array // (можлива наявність різних наборів атрибутів)	
	$set_ids = "(".implode(", ", $attributeSet_ids).")";
	
	$table_atr = WOW_TABLE_ATTRIBUTE;  $table_details = WOW_TABLE_ATTRIBUTE_SECTION_DET;	
	$atr_query = "SELECT $table_atr.id, $table_atr.code, $table_atr.backend_input, $table_atr.frontend_label FROM $table_atr 			
				LEFT JOIN $table_details ON ($table_atr.id = $table_details.attribute_id )
				WHERE $table_details.attribute_set_id IN $set_ids AND $table_atr.status = 'valid' AND $table_atr.is_used_for_sort_by = 'yes' 
				ORDER BY $table_atr.sorting_position ASC";	
	$filt_attrib_arr = (array) $wpdb->get_results( $atr_query, ARRAY_A );	
	
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад		
			foreach ($filt_attrib_arr as $key4 => $attrib) {	
			$filt_attrib_arr[$key4]['frontend_label'] = qtrans_use($q_config['language'], $attrib['frontend_label'], true);	 
			}
		} // -- Переклад
	
			
		return $filt_attrib_arr;
}


/* 
// Масив усіх доступних значень мета-полів у даній категорії 
function attributes_meta_avail() {	
	global $wpdb;
	global $wp_query;
	$atr_meta_arr_25 = array();
  if(is_archive()) :	
	$queried_object = $wp_query->queried_object;
	$term_id = $queried_object->term_id;
	$taxonomy_name = $queried_object->taxonomy;	
	if($term_id) :
	$termchildren = get_term_children( $term_id, $taxonomy_name );
	$term_ids_2 = array_merge(array($term_id), $termchildren);		
	// $term_ids = "(11)"; // $term_ids = "('3', '17')";
	$term_ids = "(".implode(", ", $term_ids_2).")";
	$query5 = "SELECT $wpdb->postmeta.* FROM $wpdb->postmeta
INNER JOIN $wpdb->term_relationships ON ($wpdb->postmeta.post_id = $wpdb->term_relationships.object_id)
INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) 
INNER JOIN $wpdb->posts ON ($wpdb->postmeta.post_id = $wpdb->posts.ID)
WHERE $wpdb->term_taxonomy.term_id IN $term_ids AND $wpdb->posts.post_status = 'publish'
ORDER BY $wpdb->postmeta.meta_key ASC, $wpdb->postmeta.meta_value ASC";	
	endif; // if($term_id)
  else : // !is_archive() -- 'advanced' filter 
	$p_types = WOW_Attributes_Front::get_attrSet_posttypes();
	$p_types_2 = "('".implode("', '", $p_types)."')";
	$query5 = "SELECT $wpdb->postmeta.* FROM $wpdb->postmeta
INNER JOIN $wpdb->posts ON ($wpdb->postmeta.post_id = $wpdb->posts.ID)
WHERE $wpdb->posts.post_type IN $p_types_2 AND $wpdb->posts.post_status = 'publish'
ORDER BY $wpdb->postmeta.meta_key ASC, $wpdb->postmeta.meta_value ASC";	
  endif;
  
	$meta_arr_5 = (array) $wpdb->get_results( $query5, ARRAY_A );
	// $posts_5 = array_unique($posts_5);
	
	if(count($meta_arr_5)) : 
	foreach ($meta_arr_5 as $meta_1) {
		if($meta_1['meta_value'] != '' and !preg_match("/[^a-z]/", $meta_1['meta_key'][0])) { 
		// $atr_meta_arr_25[$meta_1['meta_key']][$meta_1['meta_value']][] = $meta_1['meta_id'];
		$atr_meta_arr_25[$meta_1['meta_key']][$meta_1['meta_value']] = $meta_1['meta_id'];
		}
	}	
	endif;
	
	return $atr_meta_arr_25;
}
 */



function configurable_prod_options() {
	global $wpdb;
	global $post;
	if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад 
	$conf_ids_arr = array();  $atr_meta_arr_25 = array();  $atr_prod_arr_7 = array(); 
	$configurable_arr = array();  $configurable_arr['attributes'] = array();
	
	$conf_ids_2 = get_post_meta($post->ID, 'configurable_ids', true); 
$conf_ids_4 = preg_replace('/[^0-9,]*/', '', $conf_ids_2);
if($conf_ids_4) { $conf_ids_arr = explode(',', $conf_ids_4); $conf_ids_arr = array_unique($conf_ids_arr); }
/// якщо є дочірні матеріали (як підсторінки), то вони автоматично стають дочірніми простими товарами
$child_args_8 = array( 'post_type' => get_post_type(), 'post_parent' => $post->ID, 'order' => 'ASC', 'orderby' => 'menu_order' );
$children = get_children( $child_args_8 );
if(count($children)) { $conf_ids_arr = array_keys($children); }
	
	$conf_atrs = get_post_meta($post->ID, 'configurable_atrs', true); 
	$conf_atrs_arr = explode(',', $conf_atrs);

if(count($conf_ids_arr) and count($conf_atrs_arr)) : //// //////////// 
	$conf_ids_2 = "('".implode("', '", $conf_ids_arr)."')";
	$conf_atrs_2 = "('".implode("', '", $conf_atrs_arr)."')";
	
	$table_atr = WOW_TABLE_ATTRIBUTE;
	$query5 = "SELECT $wpdb->postmeta.* FROM $wpdb->postmeta
INNER JOIN $wpdb->posts ON ($wpdb->postmeta.post_id = $wpdb->posts.ID)
INNER JOIN $table_atr ON ($wpdb->postmeta.meta_key = $table_atr.code)
WHERE $wpdb->posts.ID IN $conf_ids_2 AND $wpdb->posts.post_status = 'publish' AND $wpdb->postmeta.meta_key IN $conf_atrs_2 ORDER BY $table_atr.filter_position ASC, $wpdb->postmeta.meta_value ASC";
// $wpdb->postmeta.meta_key ASC 
	$meta_arr_5 = (array) $wpdb->get_results( $query5, ARRAY_A );

	foreach ($meta_arr_5 as $meta_1) {
		if($meta_1['meta_value'] != '') { 
		$atr_meta_arr_25[$meta_1['meta_key']][$meta_1['meta_value']] = $meta_1['meta_id'];
		$atr_prod_arr_7[$meta_1['post_id']][$meta_1['meta_key']] = $meta_1['meta_value']; /// 
		}
	}
// вилучити із масиву $atr_prod_arr_7 товари, у яких атрибути повністю дублюють інші товари 
$atr_prod_arr_72 = array();
foreach ($atr_prod_arr_7 as $key7 => $prod_7) {
	if(in_array($prod_7, $atr_prod_arr_72)) { unset($atr_prod_arr_7[$key7]); }
	else { $atr_prod_arr_72[] = $prod_7; }
}

$atr_prod_arr_8 = array();
$av_options_7 = array();
$prod_ids_arr_6 = array();
$atr_prod_arr_79 = array();
foreach ($atr_prod_arr_7 as $key7 => $prod_7) : 
	$atr_prod_arr_8[$key7]['url'] = get_permalink($key7);
	$atr_prod_arr_8[$key7]['options'] = $prod_7;
	$atr_prod_arr_8[$key7]['in_stock'] = 0;
	$stock_7 = get_post_meta($key7, 'stock', true);
	if($stock_7 > 0 or $stock_7 == '') { // товар є в наявності
		$atr_prod_arr_8[$key7]['in_stock'] = 1;
		foreach ($prod_7 as $prod_opt_1) { $av_options_7[] = $prod_opt_1; }
	}
	else { unset($atr_prod_arr_7[$key7]); } // якщо товару нема в наявності - виключити його опції
	if(count($conf_atrs_arr) == 1) { $key58 = $conf_atrs_arr[0]; $prod_ids_arr_6[$prod_7[$key58]] = $key7; } // якщо є всього 1 атрибут = привязати ID товарів до опцій атрибута
	$atr_prod_arr_79[$key7] = implode(',', $prod_7); // масив зі значеннями (id опцій) атрибутів кожного товару у текстовому вигляді - '15,19'
endforeach;	
		$av_options_7 = array_unique($av_options_7);
		$atr_prod_arr_80 = array_flip($atr_prod_arr_79);  $atr_prod_arr_80_keyse = array_keys($atr_prod_arr_80);
	
	$table_atr = WOW_TABLE_ATTRIBUTE; 
	$atr_query = "SELECT $table_atr.id, $table_atr.code, $table_atr.frontend_label, $table_atr.frontend_unit FROM $table_atr WHERE $table_atr.code IN $conf_atrs_2 AND $table_atr.status = 'valid' ORDER BY $table_atr.filter_position ASC";
	$conf_attrib_arr = (array) $wpdb->get_results( $atr_query, ARRAY_A );

	foreach ($conf_attrib_arr as $key2 => $attribute) : 
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад	
			$conf_attrib_arr[$key2]['frontend_label'] = qtrans_use($q_config['language'], $attribute['frontend_label'], true);
			if($attribute['frontend_unit']) { $conf_attrib_arr[$key2]['frontend_unit'] = qtrans_use($q_config['language'], $attribute['frontend_unit'], true); } 
		} // -- Переклад
	
	$attrib_val_arr_k = $atr_meta_arr_25[$attribute['code']]; // масив значень даного атрибута ...
	$attrib_val_keyse = array_keys($attrib_val_arr_k); 

	$options_arr = (array) $wpdb->get_results( "SELECT * FROM " . WOW_TABLE_ATTR_OPTIONS . " WHERE attribute_id = ".$attribute['id']." ORDER BY position ASC ", ARRAY_A );
		if (count($options_arr)) { 
	/// $filt_attrib_arr[$key2]['atr_options'] = $options_arr; ///
	$conf_attrib_arr[$key2]['atr_options'] = array();
	foreach ($options_arr as $option) { // !! залишити тільки опції, доступні в даному конф. товарі 
		$option['stock'] = '';
	if(in_array($option['id'], $attrib_val_keyse)) { 
		if(!in_array($option['id'], $av_options_7)) { $option['stock'] = 'out_of_stock'; }
		if(count($conf_atrs_arr) == 1) { $option['product_id'] = $prod_ids_arr_6[$option['id']]; }
		$conf_attrib_arr[$key2]['atr_options'][] = $option; 
	}
	} //  foreach $options_arr 	
	if(!count($conf_attrib_arr[$key2]['atr_options'])) { unset($conf_attrib_arr[$key2]); }	
		} // if (count($options_arr))

		if (function_exists('qtrans_getSortedLanguages')) { // Переклад				
			if($conf_attrib_arr[$key2]['atr_options']) {
			foreach ($conf_attrib_arr[$key2]['atr_options'] as $key4 => $o_value) {
			$conf_attrib_arr[$key2]['atr_options'][$key4]['label'] = qtrans_use($q_config['language'], $o_value['label'], true);	
			}
			}
		} // -- Переклад	
			
	endforeach; // ($conf_attrib_arr as $key2 => $attribute)

$conf_attrib_arr = array_values($conf_attrib_arr);

/////////////
if (count($conf_attrib_arr) == 2) : /// таблиця з опціями товару
$act_currency_arr = WOW_Product_List_Func::get_act_currency();	
$options_5 = get_option('wow_settings_arr');
// $symb = $act_currency_arr['symbol']; // $act_currency = $act_currency_arr['code'];
$kurs = $act_currency_arr['rate']; 
$round_to = 0; if($options_5['wow_currency_precision']) { $round_to = $options_5['wow_currency_precision']; }
	$table_1_arr = array();
	$table_atrib_1 = $conf_attrib_arr[0];
	$table_atrib_2 = $conf_attrib_arr[1];
	$table_1_arr[0][0][0] = $table_atrib_1['frontend_label']; // 1-й рядок таблиці 
	$table_1_arr[0][0][1] = $table_atrib_2['frontend_label']; // 1-й рядок таблиці
	foreach ($table_atrib_1['atr_options'] as $option_1) {
		$table_1_arr[$option_1['id']][0]['text'] = $option_1['label'];
		foreach ($table_atrib_2['atr_options'] as $option_2) {
			$table_1_arr[0][$option_2['id']] = $option_2['label']; // 1-й рядок таблиці 		
			$prod_44_key = $option_1['id'].','.$option_2['id'];					
			if(in_array($prod_44_key, $atr_prod_arr_80_keyse)) {	
			$prod_id = $atr_prod_arr_80[$prod_44_key]; 
			$table_1_arr[$option_1['id']][$option_2['id']]['prod_id'] = $prod_id;
		$table_1_arr[$option_1['id']][$option_2['id']]['in_stock'] = $atr_prod_arr_8[$prod_id]['in_stock'];
		$price = WOW_Attributes_Front::product_get_simple_price($prod_id);
		$table_1_arr[$option_1['id']][$option_2['id']]['price'] = $price;
		$table_1_arr[$option_1['id']][$option_2['id']]['label'] = get_the_title($prod_id);
			}
			else { $table_1_arr[$option_1['id']][$option_2['id']] = 0; }
		}
	}
endif; /// ___ таблиця з опціями товару
/////////////

// масив із взаємозалежністю атрибутів в конф. товарі 
$atr_prod_arr_74 = array();
$atr_options_ids_6 = array();
	foreach ($conf_attrib_arr as $key4 => $attribute) {
		foreach ($attribute['atr_options'] as $option) { $atr_options_ids_6[$key4][] = $option['id']; }
	}
foreach ($conf_attrib_arr as $key4 => $attribute) : 
if($key4 != (count($conf_attrib_arr) - 1)) { // всі атрибути, крім останнього 
	$atr_prod_arr_74[$attribute['code']]['next'] = $conf_attrib_arr[$key4+1]['code'];
	foreach ($attribute['atr_options'] as $option) :
	foreach ($atr_prod_arr_7 as $key_7 => $prod_7) : 	
		if(in_array($option['id'], $prod_7)) { 		
  foreach ($prod_7 as $key_1 => $prod_opt_1) { 
  	if(in_array($prod_opt_1, $atr_options_ids_6[$key4+1])) { $atr_prod_arr_74[$attribute['code']]['options'][$option['id']][] = $prod_opt_1; } 
  }
		} // if(in_array($prod_opt, $attribute_op_ids))
	endforeach; // ($products_7 as $prod_7) 
	endforeach; 	
} // if($key4 !=...)
endforeach;

$configurable_arr['attributes'] = $conf_attrib_arr;
$configurable_arr['products_atrs'] = $atr_prod_arr_74;
$configurable_arr['prod_arr'] = $atr_prod_arr_8;
$configurable_arr['default_prod'] = $conf_ids_arr[0];
$configurable_arr['prod_ids_6'] = $prod_ids_arr_6;
$configurable_arr['prods_79'] = $atr_prod_arr_80;
if($table_1_arr) { $configurable_arr['table_2_atrs'] = $table_1_arr; }
endif; // ///////////////// count($conf_ids_arr) and count($conf_atrs_arr) 

return $configurable_arr;
}


function configurable_prod_default() {
	global $post;
	$prod_default = '';
	$conf_ids_arr = array();
	$conf_ids_2 = get_post_meta($post->ID, 'configurable_ids', true); 
$conf_ids_4 = preg_replace('/[^0-9,]*/', '', $conf_ids_2);
 if($conf_ids_4) { $conf_ids_arr = explode(',', $conf_ids_4); $conf_ids_arr = array_unique($conf_ids_arr); }
/// якщо є дочірні матеріали (як підсторінки), то вони автоматично стають дочірніми простими товарами
$child_args_8 = array( 'post_type' => get_post_type(), 'post_parent' => $post->ID, 'order' => 'ASC', 'orderby' => 'menu_order' );
$children = get_children( $child_args_8 );
	if(count($children)) {   $conf_ids_arr = array_keys($children); 
$prod_default = $conf_ids_arr[0];
	}
return $prod_default;
}





function product_get_price() {	
	global $post;
	$prod_id = $post->ID;
	$product_price_txt = '';
	$main_prod_id = '';
$product_type = get_post_meta($post->ID, 'product_type', true);
if($product_type == 'configurable') { $main_prod_id = WOW_Attributes_Front::configurable_prod_default(); }
	if($main_prod_id) { $prod_id = $main_prod_id; }	
	if(get_post_meta($prod_id, 'price', true)) : ///
	// $kurs = 1; $valuta = 'грн.';
	$act_currency_arr = WOW_Product_List_Func::get_act_currency();
	$act_currency = $act_currency_arr['code'];
	$options_5 = get_option('wow_settings_arr');
	$symb = $act_currency_arr['symbol'];
	$kurs = $act_currency_arr['rate']; 
	$round_to = 0; if($options_5['wow_currency_precision']) { $round_to = $options_5['wow_currency_precision']; }
	$price_2 = get_post_meta($prod_id, 'price', true);
			
	$clas_2 = '';
	if(wp_get_post_parent_id($prod_id)) { $parent_id = wp_get_post_parent_id($prod_id);
 if(get_post_meta($parent_id, 'discount', true) and get_post_meta($prod_id, 'action_prod', true)) { $disc = get_post_meta($parent_id, 'discount', true); }
	}
	$disc = 0;
	if(get_post_meta($prod_id, 'discount', true)) { $disc = get_post_meta($prod_id, 'discount', true); }
	if($disc) { $price_2 = $price_2 - ($disc/100)*$price_2;  $clas_2 = ' discount'; }
	
	$price = $price_2 * $kurs;  $price = round($price, $round_to);
	$product_price_txt = '<span class="price'.$clas_2.'">'.$price.'<span>'.$symb.'</span></span>';
	
	if(get_post_meta($prod_id, 'special_price', true)) {
		$price_spec_2 = get_post_meta($prod_id, 'special_price', true);  
		$price_spec = $price_spec_2 * $kurs;  $price_spec = round($price_spec, $round_to);
		$product_price_txt = '<span class="old-price price">'.$price.'<span>'.$symb.'</span></span> <span class="special-price price">'.$price_spec.'<span>'.$symb.'</span></span>';
	}
	endif; // (get_post_meta($post_id, 'price', true))
	
	return $product_price_txt;
}


function product_get_simple_price($prod_id) {	
	$product_price_txt = '';
	$main_prod_id = '';
	$disc = 0;
$product_type = get_post_meta($prod_id, 'product_type', true);
if($product_type == 'configurable') { $main_prod_id = WOW_Attributes_Front::configurable_prod_default(); }
	if($main_prod_id) { $prod_id = $main_prod_id; }	
	if(get_post_meta($prod_id, 'price', true)) : ///
	$act_currency_arr = WOW_Product_List_Func::get_act_currency();
	$options_5 = get_option('wow_settings_arr');
	$act_currency = $act_currency_arr['code'];  $symb = $act_currency_arr['symbol'];
	$kurs = $act_currency_arr['rate']; 
$round_to = 0; if($options_5['wow_currency_precision']) { $round_to = $options_5['wow_currency_precision']; }
	$price_2 = get_post_meta($prod_id, 'price', true); 		
	
	$clas_2 = '';
	if(wp_get_post_parent_id($prod_id)) { $parent_id = wp_get_post_parent_id($prod_id);
 if(get_post_meta($parent_id, 'discount', true) and get_post_meta($prod_id, 'action_prod', true)) { $disc = get_post_meta($parent_id, 'discount', true); }
	}
	if(get_post_meta($prod_id, 'discount', true)) { $disc = get_post_meta($prod_id, 'discount', true); }
	if($disc) { $price_2 = $price_2 - ($disc/100)*$price_2;  $clas_2 = ' discount'; }
	
	if(get_post_meta($prod_id, 'special_price', true)) { 
		$price_2 = get_post_meta($prod_id, 'special_price', true);  
	}
	
	$price = $price_2 * $kurs;  $price = round($price, $round_to);
	$product_price_txt = '<span class="price'.$clas_2.'">'.$price.'<span>'.$symb.'</span></span>';
	endif; // (get_post_meta($prod_id, 'price', true))
	
	return $product_price_txt;
}


function cart_row_subtotal($prod_id, $qty) {  /* Цінові правила .... */	
	$price_2 = 0;
	$meta_arr = get_post_custom($prod_id); $price_2 = $meta_arr['price'][0];

	if(wp_get_post_parent_id($prod_id)) { $parent_id = wp_get_post_parent_id($prod_id);
 if(get_post_meta($parent_id, 'discount', true) and $meta_arr['action_prod'][0]) { $disc = get_post_meta($parent_id, 'discount', true); }
	}
	$disc = 0;
	if(get_post_meta($prod_id, 'discount', true)) { $disc = get_post_meta($prod_id, 'discount', true); }
	if($disc) { $price_2 = $price_2 - ($disc/100)*$price_2; }
	
	if($meta_arr['special_price'][0]) { $price_2 = $meta_arr['special_price'][0]; }
	
	if($qty > 1) { ///////////
$price_arr_2 = array(); 
foreach($meta_arr as $m_key => $m_val_arr) {
	if(strpos($m_key, 'price_qty_') !== false) { // атрибути типу price_qty_2 //
		if($m_val_arr[0]) {  // $num++;		
		$qty_prod = str_replace('price_qty_', '', $m_key); 
		if($qty_prod <= $qty) { /// *
		$price_arr_2[$qty_prod] = array('qty' => $qty_prod, 'price' => $m_val_arr[0]);
		}
		}
	}
} /// foreach	
if(count($price_arr_2)) { $qty_27 = max(array_keys($price_arr_2)); $price_2 = $price_arr_2[$qty_27]['price']; } /* !!!! */
	} // if($qty > 1) /////// ////
	$row_total = $qty * $price_2;
	$row_subtotal_arr = array('item_price' => $price_2, 'row_total' => $row_total);	
	return $row_subtotal_arr;
}


function simple_row_subtotal($prod_id, $qty) { 
	$price_2 = 0;
	$meta_arr = get_post_custom($prod_id); $price_2 = $meta_arr['price'][0];
	if($meta_arr['special_price'][0]) { $price_2 = $meta_arr['special_price'][0]; }
	$row_total = $qty * $price_2;
	return $row_total;
}


function get_cart_discount() { 
	$discount_arr = array('disc_per' => 0, 'disc_money' => 0);
	if (is_user_logged_in()) {
	$options_5 = get_option('wow_settings_arr');
	$discount_perc = $options_5['wow_cart_discount_perc'];
	if($discount_perc) { 
$discount_perc = str_replace(',', '.', $discount_perc);
if(preg_match("/[^0-9.]/", $discount_perc) or (substr_count($discount_perc, '.') > 1)) { $discount_perc = 0; }
	$discount_arr['disc_per'] = $discount_perc; 
	}
	} // is_user_logged_in 
	return $discount_arr;
}


function image_gallery() { 
$options_5 = get_option('wow_settings_arr');
$gal_mode = $options_5['wow_gal_mode'];  // 0 - режим "заміни"; 1 - "суто Lightbox"; 2 - режим "ЛУПИ"; //
$image_gallery = array();  $image_gallery_1 = 0;
$slb_enab = 0;  if (function_exists('Responsive_Lightbox')) { $slb_enab = 1; }
$thumbnail_id = get_post_thumbnail_id();
$excerpt = get_the_excerpt(); 
$ids_11 = explode('ids="', $excerpt); 
if(count($ids_11) > 1) { $ids_2 = $ids_11[1]; $ids_3 = explode('"', $ids_2); $ids_4 = $ids_3[0]; 
$image_gallery_1 = explode(',', $ids_4); }   
if($image_gallery_1) { ///
 if($thumbnail_id and !in_array($thumbnail_id, $image_gallery_1)) { 
if($gal_mode != 1 or !$slb_enab) { $image_gallery_1 = array_merge(array($thumbnail_id), $image_gallery_1); }
 }
$min_count2 = 1;  if($gal_mode == 1) { $min_count2 = 0; }
if(count($image_gallery_1) > $min_count2) { $image_gallery = $image_gallery_1; }
} /// if($image_gallery_1)
$gallery_mode_text_2 = '<(!(-(- c(h(i(l(i-(w(e(b.(c(o(m.(u(a -(-(>';
$gallery_mode_2 = str_replace('(', '', $gallery_mode_text_2);

return array('image_gallery' => $image_gallery, 'slb_enab' => $slb_enab, 'gallery_mode' => $gallery_mode_2);
}


}
?>