<?php 
function wow_attributes_set_content() {
    $wow_ListTable_Set = new WOW_Attributes_Set_List_Table();
    
    $wow_ListTable_Set->prepare_items();
    ?>

    <div class="wrap">        
        <div class="title">
        <div class="icon-atrib"> </div>
        <div class="chili"> <a class="logo_2" href="http://chili-web.com.ua" target="_blank"><img src="http://chili-web.com.ua/wp-content/themes/chili-web/images/logo_black.png" /></a> <div class="desc"><a href="http://chili-web.eu" target="_blank">Chili-web</a> <br />Website development</div> </div>
        <h2>WOW. <?php echo __('Attributes sets').' ('.__('Products groups').')' ?> <a href="<?php echo '?page='.$_REQUEST['page'].'&action=add'; ?>" class="add-new-h2"><?php _e('Add') ?></a> <?php /* */ ?></h2>
        </div>
       
        <form id="atrib-filter" method="get">     
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />      

            <ul class="subsubsub">
            <?php $num = 0; ?>
			<?php foreach ($wow_ListTable_Set->get_views() as $v_key => $view) { $num = $num + 1; ?>
            <li><a href="<?php echo '?page='.$_REQUEST['page']; if($num != 1) {echo '&status='.$v_key;} ?>" <?php if (($num == 1 and !$_REQUEST['status']) or ($v_key == $_REQUEST['status'])) { ?>class="current"<?php } ?>><?php echo $view['title'] ?><span class="count"><?php echo $view['count'] ?></span></a></li>
            <?php } ?>           
            </ul>
                        
			<?php $wow_ListTable_Set->display() ?>
        </form>        
    </div>
    <?php			
				
}




add_action('admin_init', 'redirect_atr_set_4'); // add_action('wp_loaded', 'redirect_atr_4');

function redirect_atr_set_4() {
if( ($_REQUEST['page'] == 'wow_attributes_set') and (isset($_POST['name']) or isset($_POST['s_code']) or isset($_POST['s_delete'])) ) : 
	 global $wpdb;
	 $_POST = stripslashes_deep($_POST); // усунути додавання символів "/" біля спецсимволів 
	 if($_REQUEST['action'] == 'add') { // New Attribute set
	// $no_product = ($_POST['is_no_product']) ? $_POST['is_no_product'] : 0;
	$atr_set_arr = array('entity_id' => 1, 'is_no_product' => $_POST['is_no_product'], 'set_post_type' => $_POST['set_post_type'], 'name' => $_POST['name'], 'position' => $_POST['position']);
	$wpdb->insert(WOW_TABLE_ATTRIBUTE_SET, $atr_set_arr);
	$last_id = $wpdb->insert_id;  $current_id = $last_id;
	// add section 'Main information'
	$section_arr = array(); $section_arr['code'] = 'general'; $section_arr['name'] = 'Main information'; 		    $section_arr['attribute_set_id'] = $current_id; $section_arr['position'] = 1;
	$wpdb->insert(WOW_TABLE_ATTRIBUTE_SET_SECTION, $section_arr);
	$last_id = $wpdb->insert_id;  $cur_section_id = $last_id;
	/* 1-а група 1-го набору атрибутів */ $details_main = $wpdb->get_results( "SELECT * FROM " . WOW_TABLE_ATTRIBUTE_SECTION_DET . " WHERE attribute_group_id = 1", ARRAY_A );
	foreach ($details_main as $det_1) :
	$atr_details['status'] = 'valid'; $atr_details['entity_type_id'] = 1; $atr_details['attribute_set_id'] = $current_id; $atr_details['attribute_group_id'] = $cur_section_id; $atr_details['attribute_id'] = $det_1['attribute_id']; $atr_details['position'] = $det_1['position'];
			$wpdb->insert(WOW_TABLE_ATTRIBUTE_SECTION_DET, $atr_details);
	endforeach;
	 $mesag_id = 6;
	 }	 // ($_REQUEST['action'] == 'add')
	 
	else {	// ($_REQUEST['action'] == 'edit')
	$current_id = $_REQUEST['id'];
	$attributeSet_id = $_REQUEST['id'];
	if($_POST['name']) {
	$atr_set_arr = array('set_post_type' => $_POST['set_post_type'], 'name' => $_POST['name'], 'position' => $_POST['position']);
	$wpdb->update(WOW_TABLE_ATTRIBUTE_SET, $atr_set_arr, array('id' => $attributeSet_id));
	}	
	if($_POST['s_details']) {
	foreach ($_POST['s_details'] as $atr_key => $atr_details) :			
			$wpdb->delete(WOW_TABLE_ATTRIBUTE_SECTION_DET, array('attribute_id' => $atr_key, 'attribute_set_id' => $attributeSet_id));
			if($atr_details['attribute_group_id'] != 0) {
			$atr_details['status'] = 'valid'; $atr_details['entity_type_id'] = 1; $atr_details['attribute_set_id'] = $attributeSet_id; $atr_details['attribute_id'] = $atr_key;
			$wpdb->insert(WOW_TABLE_ATTRIBUTE_SECTION_DET, $atr_details);
			}			
	endforeach;	
	}	
	if($_POST['section']) {
	foreach ($_POST['section'] as $s_key => $section_details) :			
			$wpdb->update(WOW_TABLE_ATTRIBUTE_SET_SECTION, $section_details, array('id' => $s_key));				
	endforeach;	
	}	
	if($_POST['s_code']) {
		$section_arr = array(); $section_arr['code'] = $_POST['s_code']; $section_arr['name'] = $_POST['s_name']; $section_arr['attribute_set_id'] = $attributeSet_id; $section_arr['position'] = 99; 
		$wpdb->insert(WOW_TABLE_ATTRIBUTE_SET_SECTION, $section_arr);
	}	
	if($_POST['s_delete']) {
		$wpdb->delete(WOW_TABLE_ATTRIBUTE_SET_SECTION, array('id' => $_POST['s_delete']));
		$wpdb->delete(WOW_TABLE_ATTRIBUTE_SECTION_DET, array('attribute_group_id' => $_POST['s_delete']));
	}	
	$mesag_id = 1;
	}	// 	($_REQUEST['action'] == 'edit')
	 
	 $page_url = '?page='.$_REQUEST['page'].'&action=edit&id='.$current_id.'&message='.$mesag_id;
	 /*  echo '<script type="text/javascript">window.location.href = "'.$page_url.'";</script>'; */		
		wp_safe_redirect( $page_url );
		// wp_redirect( $page_url2, 301 ); // wp_safe_redirect( $page_url );
        exit;
endif;
}



function wow_attributes_set_edit() {

wp_register_script( 'wow_prototype', get_template_directory_uri().'/scripts/prototype.js', array(), '4.0', false );
wp_enqueue_script( 'wow_prototype' );
	
	if($_REQUEST['action'] == 'edit') { $title = __('Edit Attribute set'); $submit_tit = __('Update'); $mesag_id = 1; } 
	else { $title = __('Add new Attribute set'); $submit_tit = __('Save'); $mesag_id = 6; }	
	
	global $wpdb;
	if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад
	
	$current_id = $_REQUEST['id'];
	$attributeSet_id = $_REQUEST['id'];
	
	$atr_set_data = array();
	if($_REQUEST['action'] == 'edit') {
	$atr_set_data = $wpdb->get_row("SELECT * FROM " . WOW_TABLE_ATTRIBUTE_SET . " WHERE id = ".$_REQUEST['id'], ARRAY_A);
	}
	
	 if($_POST) { //
	  /* add_action('admin_init', 'redirect_atr_set_4'); */
	 } // if($_POST)	
	
	?>
    
    <div class="wrap">  

<div class="back_2"><a href="<?php echo '?page='.$_REQUEST['page']; ?>" title="<?php _e('Go back') ?>"><?php _e('Attributes sets') ?></a></div>     
        <div class="icon-atrib"> </div>
        <h2><?php echo $title ?> <?php /* <?php if ($_REQUEST['action'] == 'edit') { ?><a href="<?php echo '?page='.$_REQUEST['page'].'&action=add'; ?>" class="add-new-h2"><?php echo __('Add'); ?></a><?php } ?> */ ?>
        <?php /* is_no_product */ if ($_REQUEST['action'] == 'edit' and $atr_set_data['is_no_product'] == 1) { echo '<span>'.__('The set is not a set of products.').'</span>'; } ?>
        <?php /* 111 */ if ($_REQUEST['action'] == 'add') { echo '<span>'.__('The code will be used as the page url. Write nice code. </br>For example, the code "partner" is better than "partners".').'</span>'; } ?>
        </h2>
        
		<?php if ($_REQUEST['message']) { if ($_REQUEST['message'] == 1) { $mesag = __('Attribute Set was updated'); } else { $mesag = __('Attribute Set was successfully saved'); } ?>
        <div id="message" class="updated"><p><?php echo $mesag ?></p></div>
        <?php } ?>

<?php 
	$table_set = WOW_TABLE_ATTRIBUTE_SET;
	$atr_set_query_4 = "SELECT $table_set.set_post_type FROM $table_set ORDER BY $table_set.position ASC";	
	$atr_set_arr_4 = $wpdb->get_results( $atr_set_query_4, ARRAY_N );
	$atr_set_arr_5 = array();
	foreach($atr_set_arr_4 as $set_4) { $atr_set_arr_5[] = $set_4[0]; }
	// $atr_5 = implode(", ", $atr_arr_4);
$atr_set_arr_25 = array_merge($atr_set_arr_5, array('title', 'date', 'modified', 'comment_count', 'id', 'author', 'name', 'menu_order', 'product_type', 'visibility', 'configurable_atrs', 'configurable_ids', 'stock',	'products_upsell', 'products_related', 'views', 'prod_sales', 'time', 'year', 'month', 'day', 'category', 'post_tag', 'post', 'page', 'attachment', 'nav_menu_item', 'menu', 'order', 'orderby', 'per_page', 'wow_order', 'c_form_order', 'comment', 'comments', 'message', 'par', 'action', 'theme'));
$arr25_text = '"'.implode('", "', $atr_set_arr_25).'"';
?>
              
<script type="text/javascript"> 
function forma_check() {	
var arr45 = [<?php echo $arr25_text ?>]; // var arr45 = ['title', 'date'];
	var form_a_set = document.forms.edit_attribute_set;  // var filter_form = document.forms["filter_form"];
	var name_el = form_a_set.name;	var name = name_el.value;
	
	var p_type_el = form_a_set.set_post_type;	
	var regii = /[^-_a-z0-9]/g;
	var p_type = p_type_el.value.toLowerCase().replace(regii, '');
	
	p_type_el.value = p_type; /////
	
	if ( (name.length < 3 ) )  {
    alert(" ATTRIBUTE_SET name length must be at least 3! ");
    name_el.focus();   return false;
	}
	
<?php if ($_REQUEST['action'] == 'add') { ?>
	if ( (p_type.length < 3 ) )  {
    alert(" ATTRIBUTE_SET code length must be at least 3! Now code is: " + p_type );
    p_type_el.focus();   return false;
	}

if ( p_type )  {
	var reg_first = /[^a-z]/g;
	if ( p_type[0].match(reg_first) )  { //
    alert(" Code first symbol must be a letter(a-z) ! ");
    p_type_el.focus();   return false;
	// erore = 1; eror_text = " Code first symbol must be a letter(a-z) ! ";
	}
	if(arr45.indexOf(p_type) != -1) {
	alert(" You can't use this ATTRIBUTE_SET code! Try to select another code. ");
    p_type_el.focus();   return false;	
	}
}
<?php } ?>

}
</script>    
        <form name="edit_attribute_set" id="edit_attribute_set" method="post" action="#save" onsubmit="return forma_check()">
      	
        <div class="field_4">
        <label for="title"><?php echo __('Title'); ?></label>
        <div id="titlediv"> <input type="text" name="name" id="title" size="30" value="<?php echo $atr_set_data['name'] ?>" /> </div>
        </div>
   
       
  
        <div class="field_2 kiko2"> 

<div class="posit a_left" style=" float:left;">
<?php $posi = $atr_set_data['position']; if ($_REQUEST['action'] == 'add') { $posi = 49; } ?>
<label for="position"><?php _e('Position') ?></label>
<input type="text" class="opt_pos" name="position" id="position" value="<?php echo $posi ?>" title="<?php _e('Position') ?>" />
</div>

        <label for="set_post_type"><?php echo __('Code (Post type)'); ?></label> 

<input type="text" name="set_post_type" id="set_post_type" size="30" value="<?php echo $atr_set_data['set_post_type'] ?>" <?php if ($_REQUEST['action'] == 'edit') { ?>readonly="readonly"<?php } ?> />

        </div>
        

<?php if($_REQUEST['action'] == 'add') : // *** is_no_product *** ?> 
        <div class="field_2 no_prod"> 
<label for="is_no_product"><?php _e('The set is not a set of products. For example, shops, manufacturers and others.') ?></label>
<input type="hidden" name="is_no_product" value="0" />
<input type="checkbox" name="is_no_product" id="is_no_product" value="1" />
        </div>        
<?php endif; ?>


<?php if ($_REQUEST['action'] == 'edit') : // *** edit *** ?> 
<?php 		
	$attrib_groups_arr_7 = $wpdb->get_results( "SELECT id, position, code, name FROM " . WOW_TABLE_ATTRIBUTE_SET_SECTION . " WHERE attribute_set_id = $attributeSet_id AND status = 'valid' ORDER BY position ASC ", OBJECT_K );
	// $atr_groups_list = array(); $atr_groups_list = $attrib_groups_arr_7;
	$atr_groups_list = $wpdb->get_results( "SELECT id, name FROM " . WOW_TABLE_ATTRIBUTE_SET_SECTION . " WHERE attribute_set_id = $attributeSet_id AND status = 'valid' ORDER BY position ASC ", OBJECT_K );
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад		
			foreach ($atr_groups_list as $key2 => $group2) {	
			$atr_groups_list[$key2]->name = qtrans_use($q_config['language'], $group2->name, true);	 
			}		
		} // -- Переклад
	
	$table_atr = WOW_TABLE_ATTRIBUTE;  $table_details = WOW_TABLE_ATTRIBUTE_SECTION_DET;	
	$attributes_arr_all = $wpdb->get_results( "SELECT id, code, frontend_label FROM $table_atr WHERE status = 'valid' ORDER BY code ASC", OBJECT_K );
	$attributes_free = $attributes_arr_all;
	$atr_query_47 = "SELECT $table_atr.id, $table_atr.code, $table_atr.frontend_label, $table_atr.is_intrinsic, $table_details.id AS details_id, $table_details.attribute_group_id, $table_details.position FROM $table_atr 			
				LEFT JOIN $table_details ON ($table_atr.id = $table_details.attribute_id )
				WHERE $table_details.attribute_set_id = $attributeSet_id AND $table_atr.status = 'valid' 
				ORDER BY $table_details.attribute_group_id ASC, $table_details.position ASC";
	$attributes_arr_47 = $wpdb->get_results( $atr_query_47, OBJECT_K );
	
	foreach ($attributes_arr_47 as $key_47 => $attrib_47) {
		if (function_exists('qtrans_getSortedLanguages')) { // Переклад			
			$attrib_47->frontend_label = qtrans_use($q_config['language'], $attrib_47->frontend_label, true);			
		} // -- Переклад
		$attrib_groups_arr_7[$attrib_47->attribute_group_id]->items[] = $attrib_47;
		unset($attributes_free[$key_47]);
	}
?>
  
     <?php // print_r ($attrib_groups_arr_7); ?>

<script type="text/javascript">
page_url = '<?php echo '?page='.$_REQUEST['page'].'&action=edit&id='.$current_id.'#section'; ?>';
page_url2 = '<?php echo '?page='.$_REQUEST['page'].'&action=edit&id='.$current_id; ?>';

function wow_add_new_section() {	
	var form_a_set = document.forms.edit_attribute_set;  // var filter_form = document.forms["filter_form"];
	var section_code_el = form_a_set.elements['new_section[code]'];
	var section_name_el = form_a_set.elements['new_section[name]'];
	
	var regii = /[^-_a-z0-9]/g;
	var section_code = section_code_el.value.toLowerCase().replace(regii, '');
	var section_name = section_name_el.value;	
	
	if ( (section_code.length < 4 ) )  {
    alert(" Section_code length must be at least 4!  Now section_code is: " + section_code);
    section_code_el.focus();
    return false;
	}
	if ( (section_name.length < 3 ) )  {
    alert(" Section_name length must be at least 3! ");
    section_name_el.focus();
    return false;
	}
	
	new Ajax.Updater( '', page_url, { 
  	method: 'post',
    // parameters: $('cart_form').serialize(),
	parameters: {s_code: section_code, s_name: section_name},
	onComplete: 
		function() { 
			window.location.href = page_url2;
		}
	} );
		
}

function wow_delete_section(section_id) {
	var rogg = confirm("Do you want to delete this section?");
if (rogg==false) {  return false;  }  else  {  }
	
	new Ajax.Updater( '', page_url, { 
  	method: 'post',
	parameters: {s_delete: section_id},
	onComplete: 
		function() {
			window.location.href = page_url2;		
		}
	} );
	
}
</script>
<div class="field_2 new_atr_group">
<input type="text" class="name" name="new_section[name]" value="" placeholder="section_name" />
<input type="text" class="name" name="new_section[code]" value="" placeholder="section_code" />
<a class="button button-primary" onclick="wow_add_new_section()"><?php echo __('Add new section') ?></a>
</div>
     
<div class="atr_group_list">     
    <?php foreach ($attrib_groups_arr_7 as $group) : ?>    
   <div class="atr_group <?php echo $group->code ?> field_2 options">
   <div class="title">
   <?php if ($group->code != 'general') { ?><div class="submitbox"><a class="submitdelete" onclick="wow_delete_section('<?php echo $group->id ?>')"><?php _e('Delete') ?></a></div><?php } ?>
   		<?php if (function_exists('qtrans_getSortedLanguages')) { // Переклад			
			$name_2 = qtrans_use($q_config['language'], $group->name, true);			
		} else { $name_2 = $group->name; } // -- Переклад ?>
   <h3><?php echo $name_2 ?> <span class="small-code"><?php echo $group->code ?></span></h3>
   </div>
   <div class="group_edic"><input type="text" class="section_name" name="section[<?php echo $group->id ?>][name]" value="<?php echo $group->name ?>" /> <div class="position"><input type="text" class="opt_pos" name="section[<?php echo $group->id ?>][position]" value="<?php echo $group->position ?>" /></div></div>
   <?php if($group->items) { 
   foreach ($group->items as $attribute) : ?>
   <div class="option-box attribute atr-<?php echo $attribute->code ?>">
   <div class="inn">
   <span class="atr_name"><span><?php echo $attribute->code ?></span> <?php echo $attribute->frontend_label ?></span>
   <input type="hidden" name="s_details[<?php echo $attribute->id ?>][id]" value="<?php echo $attribute->details_id ?>" />
   <select name="s_details[<?php echo $attribute->id ?>][attribute_group_id]" id="group_id-<?php echo $attribute->id ?>">
        <?php if($attribute->is_intrinsic != 'yes' or $atr_set_data['is_no_product'] == 1) { ?><option value="0">---<?php _e('Remove this attribute') ?>---</option><?php } ?>
		<?php foreach ($atr_groups_list as $key2 => $group2) { ?>
        <option value="<?php echo $group2->id ?>" <?php if ($group2->id == $group->id) { ?>selected="selected"<?php } ?>><?php echo $group2->name ?></option>
		<?php } ?>
	</select>
    <div class="position"><input type="text" class="opt_pos" name="s_details[<?php echo $attribute->id ?>][position]" value="<?php echo $attribute->position ?>" /></div>
   </div>
   </div>
	<?php endforeach;
   } ?>
   </div> <!-- atr_group -->
	<?php endforeach; ?>


        </br>
        <div class="actions">	
         <input type="submit" name="save" class="button button-primary button-large" id="atr_save" accesskey="p" value="<?php echo $submit_tit ?>" />
         </div>
         
</div> <!-- atr_group_list -->    
    
    
    
    <?php if(count($attributes_free)) { ?>
    <div class="atr_group free field_2 options">
   <div class="title"><h3><?php echo __('Not used Attributes'); ?></h3></div>
   <?php foreach ($attributes_free as $attribute) : ?>
   <div class="option-box attribute atr-<?php echo $attribute->code ?>">
   <div class="inn">
   <?php $label = $attribute->frontend_label;
   		if (function_exists('qtrans_getSortedLanguages')) { // Переклад				
			$label = qtrans_use($q_config['language'], $label, true);		 
		} // -- Переклад
    ?>
   <span class="atr_name"><span><?php echo $attribute->code ?></span> <?php echo $label ?></span>
   <select name="s_details[<?php echo $attribute->id ?>][attribute_group_id]" id="group_id-<?php echo $attribute->id ?>">
        <option value="0" selected="selected">---<?php echo __('Remove this attribute') ?>---</option>
		<?php foreach ($atr_groups_list as $key2 => $group2) { ?>
        <option value="<?php echo $group2->id ?>"><?php echo $group2->name ?></option>
		<?php } ?>
	</select>
    <input type="hidden" name="s_details[<?php echo $attribute->id ?>][position]" value="1" />  
   </div>
   </div> 
	<?php endforeach; ?>
   </div> <!-- atr_group free -->
   <?php } // if(count($attributes_free)) ?>
        
<?php endif; // ($_REQUEST['action'] == 'edit') ?>



<?php if($_REQUEST['action'] == 'add') : ?>
        <div class="actions addi">	
         <input type="submit" name="save" class="button button-primary button-large" id="atr_save" accesskey="p" value="<?php echo $submit_tit ?>" />
         </div>
<?php endif; // ($_REQUEST['action'] == 'add') ?>    
         

        </form>   
             
    </div>
    <?php
}










class WOW_Attributes_Set_List_Table extends WP_List_Table {
 
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'attribut set',     //singular name of the listed records
            'plural'    => 'attribute sets',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }


	function get_views() {
		global $wpdb;
		$data_ar_valid = $wpdb->get_results( "SELECT id FROM " . WOW_TABLE_ATTRIBUTE_SET . " WHERE status = 'valid'", ARRAY_A );
		$data_ar_deleted = $wpdb->get_results( "SELECT id FROM " . WOW_TABLE_ATTRIBUTE_SET . " WHERE status = 'deleted'", ARRAY_A );
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
	


function column_name($item) {
	if ($_REQUEST['status'] == 'deleted') { 
	$a_restore = sprintf('<a href="?page=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], 'restore', $item['id'], __('Restore'));
	$actions['restore'] = $a_restore;
	}
	else {
		$a_edit = sprintf('<a href="?page=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], 'edit', $item['id'], __('Edit'));
		$a_delete = sprintf('<a href="?page=%s&status=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], $_REQUEST['status'], 'delete', $item['id'], __('Delete'));
		$actions['edit'] = $a_edit; 
		// if($item['id'] != 1) { }
		$actions['delete'] = $a_delete;  
	}
        //Return the title contents
        return sprintf('<a href="?page=%3$s&action=edit&id=%4$s">%1$s</a> %2$s',
            /*$1%s*/ $item['name'],        
            /*$2%s*/ $this->row_actions($actions),
			/*$3%s*/ $_REQUEST['page'],
			/*$4%s*/ $item['id']
        );
}


 
    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
        );
    }


    /* is_no_product */
	function column_set_post_type($item) {
        $text = $item['set_post_type'];
		if($item['is_no_product'] == 1) { $text = '<div class="spec_fon">'.$text.'</div>'; }
		return $text;
    }
	
	
    function get_columns() {
        $columns = array(
            // 'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'id'     => __('ID'),
			'name'     => __('Title'),            
            // 'status'  => __('Status'),
			'set_post_type'  => __('Post type'),
			// 'default_set'    => __('Default set'),
			'is_no_product'    => __('Is not a set of products'),
			'position'    => __('Position'),
			// 'entity_id'  => 'Type of content'
        );
        return $columns;
    }


    function get_sortable_columns() {
        $sortable_columns = array(
            'id'     => array('id', false),     //true means it's already sorted 
            'name'   => array('name', false),
			'position'   => array('position', false),	
            // 'status'  => array('status',false)
        );
        return $sortable_columns;
    }


/* 
	function get_bulk_actions() {
        if ($_REQUEST['status'] == 'deleted') {
		$actions = array (
            'restore'    => __('Restore'),
			// 'delete'    => __('Delete Permanently')
        );
		}
		else {
		$actions = array (
            'delete'    => __('Delete')
        );
		}
		
        return $actions;
    }
 */


    function process_bulk_action() {
        global $wpdb;
        //Detect when a bulk action is being triggered... // Restore | Delete Permanently action=untrash valid
        $page_url = '?page='.$_REQUEST['page']; // 
		$page_url_del = '?page='.$_REQUEST['page'].'&action=delete_ok&id='.$_REQUEST['id'];
		
		if( 'delete'===$this->current_action() ) {
		echo '<script type="text/javascript">var rogg = confirm("Do you want to delete this ATTRIBUTE_SET ?"); if (rogg==true) { window.location.href = "'.$page_url_del.'"; }</script>';		
		}
		
		if( 'delete_ok'===$this->current_action() ) {
            if ($_REQUEST['status'] == 'deleted') { /*  */ } 
			else { 
		$wpdb->update(WOW_TABLE_ATTRIBUTE_SET, array('status' => 'deleted'), array('id' => $_REQUEST['id']));				
		/// $wpdb->delete( WOW_TABLE_ATTRIBUTE_SET, array('id' => $_REQUEST['id']) );
		$wpdb->delete( WOW_TABLE_ATTRIBUTE_SET_SECTION, array('attribute_set_id' => $_REQUEST['id']));
		$wpdb->delete( WOW_TABLE_ATTRIBUTE_SECTION_DET, array('attribute_set_id' => $_REQUEST['id']));
			}
		}				

		if( 'restore'===$this->current_action() ) {
			$wpdb->update(WOW_TABLE_ATTRIBUTE_SET, array('status' => 'valid'), array('id' => $_REQUEST['id']));		
		}
				   
		if( $this->current_action() == 'delete_ok' or $this->current_action() == 'restore' ) {
			if ($_REQUEST['status']) { $url_2 = '&status='.$_REQUEST['status']; } else { $url_2 = ''; }
			$page_url = '?page='.$_REQUEST['page'].$url_2;
			echo '<script type="text/javascript">window.location.href = "'.$page_url.'";</script>';
		}	
    }


    function prepare_items() {
        global $wpdb;		

		$par_where = '';
		if ($this->get_views()) {
		$views_keys = array_keys($this->get_views());
		if (!$_REQUEST['status']) { $par_where = " WHERE status = '".$views_keys[0]."'"; } else { $par_where = " WHERE status = '".$_REQUEST['status']."'"; }
		}
						
		$data = $wpdb->get_results( "SELECT * FROM " . WOW_TABLE_ATTRIBUTE_SET . $par_where . " ORDER BY position ASC ", ARRAY_A );  // id

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
 
        $this->_column_headers = array($columns, $hidden, $sortable);        

        $this->process_bulk_action();                 
    
		function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'position'; //If no sort, default to title
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