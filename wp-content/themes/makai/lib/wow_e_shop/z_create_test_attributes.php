<?php 

function create_wow_test_attributes() {		
		global $wpdb;	

		/// define( 'WOW_DIRE', TEMPLATEPATH . '/lib/wow_e_shop/' );
		$file_uri = WOW_DIRE . 'files/attributes.csv';
		if ( is_file( $file_uri ) ) {
			$entity_id = 1;
			$csv_file_default_data = file($file_uri, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			$db_field_definition = explode( ";", $csv_file_default_data[0] );			
			$code_column = null;
			foreach ( $db_field_definition as $column_index => $column_name ) {
				if ( $column_name == 'code' ) {	$code_column = $column_index;	continue;	}
			}
			unset($csv_file_default_data[0]);			

			if ( !empty($code_column) || ($code_column == 0) ) {
				foreach ( $csv_file_default_data as $line_index => $line_content ) {
					$attribute_definition = explode( ";", $line_content );					
					
					$query = $wpdb->prepare( "SELECT id FROM " . WOW_TABLE_ATTRIBUTE . " WHERE code = %s AND entity_id = %d", $attribute_definition[$code_column], $entity_id);
					$attribute_identifier = $wpdb->get_var($query);					
					if ( empty($attribute_identifier) ) {
						$attribute_def = array();
						$attribute_values = $default_value = null;
						foreach ( $db_field_definition as $column_index => $column_name ) {
							$column_name = trim($column_name);
							if ( !empty($column_name) ) {
						$attribute_def[$column_name] = ( !empty($attribute_definition[$column_index]) ) ? $attribute_definition[$column_index] : '';
							}
							switch ( $column_name ) {
								case 'available_values':
									$attribute_values = $attribute_definition[$column_index];
								case 'default_value':
									$default_value = $attribute_definition[$column_index];
								break;
							}
						} // foreach ( $db_field_definition as $column_index => $column_name )
						$attribute_def['entity_id'] = $entity_id;
					
					unset($attribute_def['available_values']);				
						
						$wpdb->insert(WOW_TABLE_ATTRIBUTE, $attribute_def);
						$last_attribute_id = $wpdb->insert_id;

						//	Create values for select element
						if ( !empty($attribute_values) ) {
							$list_of_values_to_create = explode( ',', $attribute_values );
							if ( !empty($list_of_values_to_create) ) {
								foreach ( $list_of_values_to_create as $value ) {
									// $value_element = explode( '!!', $value);
									$wpdb->insert( WOW_TABLE_ATTR_OPTIONS, array('status' => 'valid', 'attribute_id' => $last_attribute_id, 'label' => $value) );
									// $wpdb->insert(WOW_TABLE_ATTR_OPTIONS, array('status' => 'valid', 'attribute_id' => $last_attribute_id, 'label' => $value_element[0], 'value' => (!empty($value_element[1]) ? $value_element[1] : strtolower($value_element[0]))));
									/* 
									if ( $default_value == (!empty($value_element[1]) ? $value_element[1] : strtolower($value_element[0])) ) {
									$wpdb->update(WOW_TABLE_ATTRIBUTE, array('default_value' => $wpdb->insert_id), array('id' => $last_attribute_id, 'default_value' => $default_value));
									}
									 */
								} // foreach ( $list_of_values_to_create as $value )
							}
						} // if ( !empty($attribute_values) )

					} // if ( empty($attribute_identifier) )
				} // foreach ( $csv_file_default_data as $line_index => $line_content )
			}
		}  // if ( is_file( $file_uri ) )	
		
		
		
		
		
		$file_uri_2 = WOW_DIRE . 'files/attribute_set.csv';
		if ( is_file( $file_uri_2 ) ) {
			$entity_id = 1;
			$csv_file_default_data = file($file_uri_2, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			$db_field_definition = explode( ";", $csv_file_default_data[0] );		
			unset($csv_file_default_data[0]);			

				foreach ( $csv_file_default_data as $line_index => $line_content ) {
					$data_definition = explode( ";", $line_content );					
			
						$data_def = array();						
						foreach ( $db_field_definition as $column_index => $column_name ) {
							$column_name = trim($column_name);
							if ( !empty($column_name) ) {
						$data_def[$column_name] = $data_definition[$column_index];
							}							
						} // foreach ( $db_field_definition as $column_index => $column_name )
						$data_def['entity_id'] = $entity_id;
					
						$wpdb->insert(WOW_TABLE_ATTRIBUTE_SET, $data_def);				
				} // foreach ( $csv_file_default_data as $line_index => $line_content )		
		}  // if ( is_file( $file_uri ) )	
		
		
		
		$file_uri_3 = WOW_DIRE . 'files/attribute_set_section.csv';
		if ( is_file( $file_uri_3 ) ) {	
			$csv_file_default_data = file($file_uri_3, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			$db_field_definition = explode( ";", $csv_file_default_data[0] );		
			unset($csv_file_default_data[0]);			

				foreach ( $csv_file_default_data as $line_index => $line_content ) {
					$data_definition = explode( ";", $line_content );					
			
						$data_def = array();						
						foreach ( $db_field_definition as $column_index => $column_name ) {
							$column_name = trim($column_name);
							if ( !empty($column_name) ) {
						$data_def[$column_name] = $data_definition[$column_index];
							}							
						} // foreach ( $db_field_definition as $column_index => $column_name )						
					
						$attribute_set_id = $data_def['attribute_set_id'];
						$incl_attributes = $data_def['include_attributes'];
						unset($data_def['include_attributes']);
						
						$wpdb->insert(WOW_TABLE_ATTRIBUTE_SET_SECTION, $data_def);	
						$last_attr_section_id = $wpdb->insert_id;						

						//	Create values for incl_attributes
						if ( !empty($incl_attributes) ) {
							$list_of_values_to_create = explode( ',', $incl_attributes );							
										$num = 0;
								foreach ( $list_of_values_to_create as $atr_id ) {		$num = $num + 1;						
									$wpdb->insert(WOW_TABLE_ATTRIBUTE_SECTION_DET, array('entity_type_id' => 1, 'attribute_set_id' => $attribute_set_id, 'attribute_group_id' => $last_attr_section_id, 'attribute_id' => $atr_id, 'position' => $num));									
								} // foreach ( $list_of_values_to_create as $value )					
						} // if ( !empty($incl_attributes) )
									
				} // foreach ( $csv_file_default_data as $line_index => $line_content )		
		}  // if ( is_file( $file_uri ) )	
		
		
		
}


?>