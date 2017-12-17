<?php 
function wow_attributes_list_content() {       
	//Create an instance of our package class...
    $wow_ListTable = new WOW_Attributes_List_Table();
    
    $wow_ListTable->prepare_items();
	?> 

    <div class="wrap">        
        <div class="title"> 
        <div class="icon-atrib"> </div> 
        <div class="chili"> <a class="logo_2" href="http://chili-web.com.ua" target="_blank"><img src="http://chili-web.com.ua/wp-content/themes/chili-web/images/logo_black.png" /></a> <div class="desc"><a href="http://chili-web.eu" target="_blank">Chili-web</a> <br />Website development</div> </div>
        <h2>WOW. <?php _e('Attributes') ?> <a href="<?php echo '?page='.$_REQUEST['page'].'&action=add'; ?>" class="add-new-h2"><?php _e('Add') ?></a></h2>
       </div>
       
        <form id="atrib-filter" method="get">     
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />      
      
            <ul class="subsubsub">
            <?php $num = 0; ?>
			<?php foreach ($wow_ListTable->get_views() as $v_key => $view) { $num = $num + 1; ?>
            <li><a href="<?php echo '?page='.$_REQUEST['page']; if($num != 1) {echo '&status='.$v_key;} ?>" <?php if (($num == 1 and !$_REQUEST['status']) or ($v_key == $_REQUEST['status'])) { ?>class="current"<?php } ?>><?php echo $view['title'] ?><span class="count"><?php echo $view['count'] ?></span></a></li>
            <?php } ?>           
            </ul>
            
			<?php $wow_ListTable->display() ?>
        </form>        
    </div>
    <?php			
				
}



add_action('admin_init', 'redirect_atr_4'); // add_action('wp_loaded', 'redirect_atr_4');

function redirect_atr_4() {
if( ($_REQUEST['page'] == 'wow_attributes') and isset($_POST['frontend_label']) ) : 
	 global $wpdb;
	 $_POST = stripslashes_deep($_POST); // усунути додавання символів "/" біля спецсимволів 
	 $updated_arr = $_POST;
	 unset($updated_arr['save']); // add_options
	 if($_POST['add_options']) { unset($updated_arr['options']); unset($updated_arr['add_options']); }
	 
	if($_REQUEST['action'] == 'add') {
	 $wpdb->insert(WOW_TABLE_ATTRIBUTE, $updated_arr);
	$last_attribute_id = $wpdb->insert_id;  $current_id = $last_attribute_id;
/* !! */ // $wpdb->insert(WOW_TABLE_ATTRIBUTE_SECTION_DET, array('entity_type_id' => 1, 'attribute_set_id' => 1, 'attribute_group_id' => 1, 'attribute_id' => $last_attribute_id, 'position' => 1));
	$mesag_id = 6;
	}	 
	else {	// ($_REQUEST['action'] == 'edit')
	$wpdb->update(WOW_TABLE_ATTRIBUTE, $updated_arr, array('id' => $_REQUEST['id']));
	 $current_id = $_REQUEST['id'];
	 if($_POST['add_options']) {
		 $wpdb->delete(WOW_TABLE_ATTR_OPTIONS, array('attribute_id' => $current_id));
		 if($_POST['options']) { 
		 	foreach ($_POST['options'] as $option) :
			$option['attribute_id'] = $current_id; 
			$wpdb->insert(WOW_TABLE_ATTR_OPTIONS, $option);
			endforeach;
		 }
	   }
	$mesag_id = 1;
	}  // ($_REQUEST['action'] == 'edit')
	 	
	 $page_url = '?page='.$_REQUEST['page'].'&action=edit&id='.$current_id.'&message='.$mesag_id;
	 /*  echo '<script type="text/javascript">window.location.href = "'.$page_url.'";</script>'; */		
		wp_safe_redirect( $page_url );
		// wp_redirect( $page_url2, 301 ); // wp_safe_redirect( $page_url );
        exit;
endif;
}



function wow_attributes_edit_item_content() {
	
 wp_register_script( 'wow_jscolor', get_template_directory_uri().'/lib/wow_e_shop/js/jscolor/jscolor.js', array(), NULL, false );
 wp_enqueue_script( 'wow_jscolor' ); /// color picker 
	
	if($_REQUEST['action'] == 'edit') { $title = __('Edit Attribute'); $submit_tit = __('Update'); $mesag_id = 1; } 
	else { $title = __('Add new Attribute').'<span>'.__('After creating an attribute, you must include it in the Attribute set').'</span>'; $submit_tit = __('Save'); $mesag_id = 6; }	
	
	global $wpdb;
	
	$atr_data = array();
	if($_REQUEST['action'] == 'edit') {
	$atr_data = $wpdb->get_row("SELECT * FROM " . WOW_TABLE_ATTRIBUTE . " WHERE id = ".$_REQUEST['id'], ARRAY_A);
	}
	
	 if($_POST) { 
		 /* function redirect_atr_4() */
	 } // if($_POST)	
	?> 
    
    <div class="wrap">  
         
 	<div class="back_2"><a href="<?php echo '?page='.$_REQUEST['page']; ?>" title="<?php _e('Go back') ?>"><?php _e('Attributes') ?></a></div>   
        <div class="icon-atrib"> </div>
        <h2><?php echo $title ?> <?php if ($_REQUEST['action'] == 'edit') { ?><a href="<?php echo '?page='.$_REQUEST['page'].'&action=add'; ?>" class="add-new-h2"><?php echo __('Add'); ?></a><?php } ?></h2>
        
		<?php if ($_REQUEST['message']) { if ($_REQUEST['message'] == 1) { $mesag = __('Attribute was updated'); } else { $mesag = __('Attribute was successfully saved'); } ?>
        <div id="message" class="updated"><p><?php echo $mesag ?></p></div>
        <?php } ?>

<?php 
	$table_atr = WOW_TABLE_ATTRIBUTE;
	$atr_query_4 = "SELECT $table_atr.code FROM $table_atr ORDER BY $table_atr.code ASC";	
	$atr_arr_4 = $wpdb->get_results( $atr_query_4, ARRAY_N );
	$atr_arr_5 = array();
	foreach($atr_arr_4 as $atr_4) { $atr_arr_5[] = $atr_4[0]; }
	// $atr_5 = implode(", ", $atr_arr_4); 
$atr_arr_25 = array_merge($atr_arr_5, array('title', 'date', 'modified', 'comment_count', 'id', 'author', 'name', 'menu_order', 'product_type', 'visibility', 'configurable_atrs', 'configurable_ids', 'stock',	'products_upsell', 'products_related', 'views', 'prod_sales', 'time', 'year', 'month', 'day', 'category', 'post_tag', 'post', 'page', 'attachment', 'nav_menu_item', 'menu', 'order', 'orderby', 'per_page', 'wow_order', 'c_form_order', 'comment', 'comments', 'message', 'par', 'action', 'theme'));
$arr25_text = '"'.implode('", "', $atr_arr_25).'"';
?>
        
<script type="text/javascript">
function atr_forma_check() {	
var erore = 0; var eror_text = '';
var arr45 = [<?php echo $arr25_text ?>]; // var arr45 = ['title', 'date'];

	var form_atr = document.forms.edit_attribute;  // var filter_form = document.forms["filter_form"];
	var name_el = form_atr.frontend_label;
	var name = name_el.value;
	
	var code_el = form_atr.code;	
	var regii = /[^_a-z0-9]/g;
	var code = code_el.value.toLowerCase().replace(/-/g, '_').replace(regii, '');

	code_el.value = code;
			
	if ( (name.length < 3 ) )  {
    alert(" ATTRIBUTE name length must be at least 3! " );
    name_el.focus();
    return false;
	}

<?php if ($_REQUEST['action'] == 'add') { ?>		
	if ( code.length < 3 )  {
	erore = 1; eror_text = " ATTRIBUTE code length must be at least 3! Now code is: " + code;  
	}
	
if ( code )  {
	var reg_first = /[^a-z]/g;
	if ( code[0].match(reg_first) )  { //
	erore = 1; eror_text = " ATTRIBUTE code first symbol must be a letter(a-z)! ";
	}
	if(arr45.indexOf(code) != -1) { 
	erore = 1; eror_text = " You can't use this ATTRIBUTE code! Try to select another code. "; 
	}
}
<?php } ?>
	
	if(erore == 1) { alert(eror_text); code_el.focus(); return false; }
}
</script>           
        <form name="edit_attribute" id="edit_attribute" method="post" action="#save" onsubmit="return atr_forma_check()" >
        
        <input type="hidden" name="entity_id" value="1" />
      	
        <div class="field_4">
        <div class="title_box">
        <label for="title"><?php echo __('Title'); ?></label>
        <div id="titlediv"> <input type="text" name="frontend_label" id="title" size="30" value="<?php echo $atr_data['frontend_label'] ?>" /> </div>
        </div>

     <div class="field_24"><label for="frontend_unit"><?php echo __('Measurement unit'); ?></label> <input type="text" name="frontend_unit" id="frontend_unit" size="30" value="<?php echo $atr_data['frontend_unit'] ?>" /> <span>E.g., kilogram, meter</span> </div>        
        </div>

<?php if (($atr_data['backend_input'] == 'checkbox') or in_array($atr_data['code'], array('discount', 'special_price'))) { ?>
        <div class="field_2"> 
        <label for="frontend_label_2"><?php _e('Title 2') ?></label> <input type="text" class="wide" name="frontend_label_2" id="frontend_label_2" size="80" value="<?php echo $atr_data['frontend_label_2'] ?>" placeholder="<?php _e('Action products') ?>" /> 
     	</div>
<?php } ?>
                
        <div class="field_2"> 
        <label for="code"><strong><?php echo __('Attribute code'); ?></strong></label> <input type="text" name="code" id="code" size="30" value="<?php echo $atr_data['code'] ?>" <?php if ($_REQUEST['action'] == 'edit') { ?>readonly="readonly"<?php } ?> /> 
     	</div>
        
        <div class="field_2"> <div class="feat_field">
        <label for="backend_input"><strong><?php echo __('Input type'); ?></strong></label>  
<?php $backend_input_arr = array('text', 'select', 'multiple-select', 'checkbox', 'textarea', 'date', 'map'); ?>
        <select name="backend_input" id="backend_input" <?php if ($_REQUEST['action'] == 'edit') { ?>disabled="disabled"<?php } ?> >
        <?php foreach ($backend_input_arr as $_input) : ?>    
        	<option value="<?php echo $_input ?>" <?php if ($_input == $atr_data['backend_input']) { ?>selected="selected"<?php } ?>><?php echo $_input ?></option>
		<?php endforeach; ?>
		</select>
        </div> </div>
        
        
        <?php 
		$yes_no_arr = array('no' => __('No'), 'yes' => __('Yes'));  $yes_no_arr_2 = array('yes' => __('Yes'), 'no' => __('No')); 
		/* !! */ // $attrib_groups_arr = $wpdb->get_results( "SELECT id, name FROM " . WOW_TABLE_ATTRIBUTE_SET_SECTION . " WHERE attribute_set_id = 1 AND status = 'valid' ORDER BY position ASC ", ARRAY_A );
		?>
        
        <div class="field_2"> <label for="is_required"><?php echo __('Is required'); ?></label>    
        <select name="is_required" id="is_required">
        <?php foreach ($yes_no_arr as $_input_key => $_input_tit) : ?>
        	<option value="<?php echo $_input_key ?>" <?php if ($_input_key == $atr_data['is_required']) { ?>selected="selected"<?php } ?>><?php echo $_input_tit ?></option>
		<?php endforeach; ?>
		</select>
        </div>
        
        <div class="field_2"> <label for="is_visible_in_front"><?php echo __('Is visible in front'); ?></label>    
        <select name="is_visible_in_front" id="is_visible_in_front">
        <?php foreach ($yes_no_arr_2 as $_input_key => $_input_tit) : ?>
        	<option value="<?php echo $_input_key ?>" <?php if ($_input_key == $atr_data['is_visible_in_front']) { ?>selected="selected"<?php } ?>><?php echo $_input_tit ?></option>
		<?php endforeach; ?>
		</select>
        </div>
        
        <div class="field_2"> <label for="is_visible_in_front_listing"><?php echo __('Is visible in listing'); ?></label>    
        <select name="is_visible_in_front_listing" id="is_visible_in_front_listing">
        <?php foreach ($yes_no_arr as $_input_key => $_input_tit) : ?>
        	<option value="<?php echo $_input_key ?>" <?php if ($_input_key == $atr_data['is_visible_in_front_listing']) { ?>selected="selected"<?php } ?>><?php echo $_input_tit ?></option>
		<?php endforeach; ?>
		</select>
        </div>
        
        <div class="field_2"> <label for="is_filterable"><?php echo __('Is filterable'); ?></label>    
        <select name="is_filterable" id="is_filterable">
        <?php foreach ($yes_no_arr as $_input_key => $_input_tit) : ?>
        	<option value="<?php echo $_input_key ?>" <?php if ($_input_key == $atr_data['is_filterable']) { ?>selected="selected"<?php } ?>><?php echo $_input_tit ?></option>
		<?php endforeach; ?>
		</select>
        <input type="text" class="opt_pos" name="filter_position" value="<?php echo $atr_data['filter_position'] ?>" title="<?php echo __('Position in filter'); ?>" />
        </div>
        
    <div class="field_2"> <label for="is_used_for_sort_by"><?php echo __('Is used for sort by'); ?></label>
    <select name="is_used_for_sort_by" id="is_used_for_sort_by"<?php if ($_REQUEST['action'] == 'add' or !in_array($atr_data['backend_input'], array('text', 'select')) ) { ?> disabled="disabled"<?php } ?>>
        <?php foreach ($yes_no_arr as $_input_key => $_input_tit) : ?>
        	<option value="<?php echo $_input_key ?>" <?php if ($_input_key == $atr_data['is_used_for_sort_by']) { ?>selected="selected"<?php } ?>><?php echo $_input_tit ?></option>
		<?php endforeach; ?>
		</select>
        <input type="text" class="opt_pos" name="sorting_position" value="<?php echo $atr_data['sorting_position'] ?>" title="<?php echo __('Position in sorting'); ?>" />
        </div>
       
       <div class="field_2"> <label for="is_comparable"><?php echo __('Is comparable'); ?></label>    
        <select name="is_comparable" id="is_comparable">
        <?php foreach ($yes_no_arr as $_input_key => $_input_tit) : ?>
        	<option value="<?php echo $_input_key ?>" <?php if ($_input_key == $atr_data['is_comparable']) { ?>selected="selected"<?php } ?>><?php echo $_input_tit ?></option>
		<?php endforeach; ?>
		</select>
        </div>
       
       <div class="field_2"> <label for="is_configurable"><?php echo __('Is configurable'); ?></label>    
        <select name="is_configurable" id="is_configurable"<?php if ($_REQUEST['action'] == 'add' or !in_array($atr_data['backend_input'], array('select')) ) { ?> disabled="disabled"<?php } ?>>
        <?php foreach ($yes_no_arr as $_input_key => $_input_tit) : ?>
        	<option value="<?php echo $_input_key ?>" <?php if ($_input_key == $atr_data['is_configurable']) { ?>selected="selected"<?php } ?>><?php echo $_input_tit ?></option>
		<?php endforeach; ?>
		</select>
        </div>

       <div class="field_2"> <label for="is_visible_in_advanced_search"><?php echo __('Is used for advanced search'); ?></label>    
        <select name="is_visible_in_advanced_search" id="is_visible_in_advanced_search">
        <?php foreach ($yes_no_arr as $_input_key => $_input_tit) : ?>
        	<option value="<?php echo $_input_key ?>" <?php if ($_input_key == $atr_data['is_visible_in_advanced_search']) { ?>selected="selected"<?php } ?>><?php echo $_input_tit ?></option>
		<?php endforeach; ?>
		</select>
        </div>        
       
       
       
        <?php /* **** OPTIONS **** */ ?>
        
        <?php if (in_array($atr_data['backend_input'], array('select', 'multiple-select'))) { 
		$options_arr = $wpdb->get_results( "SELECT * FROM " . WOW_TABLE_ATTR_OPTIONS . " WHERE attribute_id = ".$_REQUEST['id']." ORDER BY position ASC ", ARRAY_A );
		?>         
        
<script type="text/javascript"> 
var opt_list_count = <?php echo count($options_arr); ?>;
var num_id = opt_list_count;

function qf9_add_new() {
	num_id = num_id + 1;    option_id = 'qf9_opt-' + num_id;
	
	option_new_div = document.createElement("div");
  	option_new_div.className = 'option-box';
  	option_new_div.id = option_id;
	
	var option_onclik = "qf9_opt_delete('" + option_id + "')";
	option_new_div.innerHTML = '<input type="text" class="opt_label" name="options[' + num_id + '][label]" value="" />	<div class="position"> <div class="submitbox"><a class="submitdelete" onclick="' + option_onclik + '"><?php echo __('Delete') ?></a></div> <input type="text" class="opt_pos" name="options[' + num_id + '][position]" value="1" /> </div>';	
	
	var opt_list = document.getElementById("attribut_options_list45");
	opt_list.appendChild(option_new_div);
}

function qf9_opt_delete(option_id) {
	var el = document.getElementById( option_id );
	el.parentNode.removeChild( el );
}

</script> 
        
        <h3><?php echo __('Attribute options'); ?></h3>
        <div class="field_2 options">
        <input type="hidden" name="add_options" value="1" />
<div class="options-header a_options"> <div class="colu name option_lab"><?php _e('Option label') ?></div> <?php if(strpos($atr_data['code'], 'color') !== false) { ?><div class="colu color_code"><?php _e('Color code') ?></div><?php } ?> <div class="colu position"><?php _e('Position') ?></div> </div>
                
        <div class="options_list" id="attribut_options_list45"> 
         <?php $num = 0; ?>
		 <?php foreach ($options_arr as $option) :   $num = $num + 1; $option_id2 = 'qf9_opt-'.$num; ?>
        <div class="option-box" id="<?php echo $option_id2 ?>">
        <input type="hidden" name="options[<?php echo $num ?>][id]" value="<?php echo $option['id'] ?>" />
            <input type="text" class="opt_label" name="options[<?php echo $num ?>][label]" value="<?php echo $option['label'] ?>" />
            <span class="small-code"><?php echo $option['id'] ?></span>
        	<div class="position">
            <div class="submitbox"><a class="submitdelete" onclick="qf9_opt_delete('<?php echo $option_id2 ?>')"><?php echo __('Delete') ?></a></div>
            <?php if(strpos($atr_data['code'], 'color') !== false) { ?>
            <?php $opt_color_id = 'opt_color_code-'.$num; 
			$color_code = 'FFF'; if($option['color_code']) { $color_code = $option['color_code']; } ?>
            <div class="color_sect"><input type="text" class="color" name="options[<?php echo $num ?>][color_code]" id="<?php echo $opt_color_id ?>" value="<?php echo $option['color_code'] ?>" /> <a class="color_code_set" style=" background: #<?php echo $color_code ?>;" title="<?php _e('Set color code') ?>"></a> </div>
            <?php } ?>
            <input type="text" class="opt_pos" name="options[<?php echo $num ?>][position]" value="<?php echo $option['position'] ?>" /> 
            </div>
        </div>
        <?php endforeach; ?>
       </div>
        
        </br> <a class="button button-primary" onclick="qf9_add_new()"><?php echo __('Add new option') ?></a> </br></br>
        <div class="bot_text"><span class="description"><?php _e('Attribute code must contain text "color" to enable field Color code') ?></span></div>
        </div> <!-- field_2 options --> 
<?php } // **** OPTIONS **** ?>
        
        
        </br>
        <div class="actions">	
         <input type="submit" name="save" class="button button-primary button-large" id="atr_save" accesskey="p" value="<?php echo $submit_tit ?>" />
         </div>
        </form>        



<div class="note">
<br /><br />
<?php _e('If you want to use attribute as additional field on pages, the type must be set as "textarea".') ?> <br /><br />
<?php _e('If it is price attribute, the attribute code must contain text "price"') ?> <br />
<?php _e('You can create Attribute like "price_qty_2"') ?> <br />
 <br />

</div>



    </div>
    <?php
}










if(!class_exists('WP_List_Table')) {    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );  }


class WOW_Attributes_List_Table extends WP_List_Table {
 
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'attribut',     //singular name of the listed records
            'plural'    => 'attributes',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
	
	function get_views() {
		global $wpdb;
		$data_ar_valid = $wpdb->get_results( "SELECT id FROM " . WOW_TABLE_ATTRIBUTE . " WHERE status = 'valid'", ARRAY_A );
		$data_ar_deleted = $wpdb->get_results( "SELECT id FROM " . WOW_TABLE_ATTRIBUTE . " WHERE status = 'deleted'", ARRAY_A );
		$list_views = array (
		'valid' => array (          
            'title'     => __('Published').' ',
			'count'     => count($data_ar_valid)          
        ),
		'deleted' => array (          
            'title'     => __('Trash').' ',
			'count'     => count($data_ar_deleted)          
        )
		);
		
		return $list_views;
	}

	
	function column_default($item, $column_name){
        switch($column_name){
            case 'rating':
            case 'director':
                return $item[$column_name];
            default:
                return print_r($item[$column_name], true); //Show the whole array for troubleshooting purposes
        }
    } 
	


    function column_frontend_label($item) {        
        //Build row actions // __('Delete') __('Delete Permanently') __('Restore'),
        if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад
		
		if ($_REQUEST['status'] == 'deleted') {
		$actions = array (
            'restore'      => sprintf('<a href="?page=%s&status=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], $_REQUEST['status'], 'restore', $item['id'], __('Restore')),
            'delete'    => sprintf('<a href="?page=%s&status=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], $_REQUEST['status'], 'delete', $item['id'], __('Delete Permanently')),
        );
		/* !!!! */ if($item['is_intrinsic'] == 'yes') { unset($actions['delete']); } 
		}
		else {
		$actions = array (
            'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], 'edit', $item['id'], __('Edit')),
            'delete'    => sprintf('<a href="?page=%s&status=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], $_REQUEST['status'], 'delete', $item['id'], __('Delete')),
        ); 
		}
        //Return the title contents
        	if (function_exists('qtrans_getSortedLanguages')) { // Переклад			
			$label = qtrans_use($q_config['language'], $item['frontend_label'], true);			
			} else { $label = $item['frontend_label']; } // -- Переклад
			
			if($item['is_intrinsic'] == 'yes') { $label = '<span class="bolde">'.$label.'</span>'; }
		
		return sprintf('<a href="?page=%3$s&action=edit&id=%4$s">%1$s</a> %2$s',
            /*$1%s*/ $label,        
            /*$2%s*/ $this->row_actions($actions),
			/*$3%s*/ $_REQUEST['page'],
			/*$4%s*/ $item['id']
        );
    }


 
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
        );
    }


    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'id'     => __('ID'),
			'frontend_label'     => __('Title'),
            'code'    => __('Attribute code'),
			'backend_input'    => __('Input type'),
			'is_visible_in_front'  => __('In front'),
			'is_visible_in_front_listing'  => __('In listing'),
			'is_filterable'  => __('Filterable'),
			// 'is_used_for_sort_by'  => __('Sorting'),
            // 'status'  => __('Status'),		
        );
        return $columns;
    }


    function get_sortable_columns() {
        $sortable_columns = array(
            'frontend_label'     => array('frontend_label',false),     //true means it's already sorted
            'code'    => array('code',false),
			'backend_input'    => array('backend_input', false),
			// 'is_visible_in_front'    => array('is_visible_in_front',false),
			// 'is_visible_in_front_listing'    => array('is_visible_in_front_listing',false),
			'is_filterable'    => array('is_filterable',false),
			'is_used_for_sort_by'    => array('is_used_for_sort_by',false),      
        );
        return $sortable_columns;
    }


    function get_bulk_actions() {
        if ($_REQUEST['status'] == 'deleted') {
		$actions = array (
            'restore'    => __('Restore'),
			'delete'    => __('Delete Permanently')
        );
		}
		else {
		$actions = array (
            'delete'    => __('Delete')
        );
		}
		
        return $actions;
    }


    function process_bulk_action() {
        global $wpdb;
        //Detect when a bulk action is being triggered... // Restore | Delete Permanently action=untrash valid
        if( 'delete'===$this->current_action() ) {
            if ($_REQUEST['status'] == 'deleted') { $wpdb->delete( WOW_TABLE_ATTRIBUTE, array('id' => $_REQUEST['id']) ); } 
			else { $wpdb->update(WOW_TABLE_ATTRIBUTE, array('status' => 'deleted'), array('id' => $_REQUEST['id'])); }			
			// wp_die('Items deleted (or they would be if we had items to delete)!');
        }
		elseif( 'restore'===$this->current_action() ) {
			$wpdb->update(WOW_TABLE_ATTRIBUTE, array('status' => 'valid'), array('id' => $_REQUEST['id']));		
		}
   
		if( $this->current_action() == 'delete' or $this->current_action() == 'restore' ) {	
			if ($_REQUEST['status']) { $url_2 = '&status='.$_REQUEST['status']; } else { $url_2 = ''; }
			$page_url = '?page='.$_REQUEST['page'].$url_2;
			echo '<script type="text/javascript">window.location.href = "'.$page_url.'";</script>';
		}	
    }


    function prepare_items() {
        global $wpdb;		
		// $data = $this->attributes_list_data;
		// $data_ar_valid = $wpdb->get_results( "SELECT id FROM " . WOW_TABLE_ATTRIBUTE . " WHERE status = 'valid'", ARRAY_A );	
		$par_where = '';
		if ($this->get_views()) {
		$views_keys = array_keys($this->get_views());
		if (!$_REQUEST['status']) { $par_where = " WHERE status = '".$views_keys[0]."'"; } else { $par_where = " WHERE status = '".$_REQUEST['status']."'"; }
		}
		
		$data = $wpdb->get_results( "SELECT * FROM " . WOW_TABLE_ATTRIBUTE . $par_where . " ORDER BY code ASC ", ARRAY_A );  // ORDER BY id DESC		            

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
 
        $this->_column_headers = array($columns, $hidden, $sortable);        

        $this->process_bulk_action();                 
    
		function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'code'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        // usort($data, 'usort_reorder');       
    

        $current_page = $this->get_pagenum();        
        $total_items = count($data);
		$per_page = 100;
		
		$data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data; 
      
 
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }


}




?>