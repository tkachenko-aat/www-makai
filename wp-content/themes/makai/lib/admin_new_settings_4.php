<?php // 
add_action('admin_init', 'admin_new_read_settings_4');

function admin_new_read_settings_4() {
	register_setting(
		'reading',                 // settings page
		'site_add_settings_4',          // option name
		'admin_setti_validate_49'  // validation callback
	);	
/* 
	add_settings_field(
		'home_title_1',      // id
		'___ Categories on Home',          // setting title
		'home_title_1_input',    // display callback
		'reading',                 // settings page
		'default'                  // settings section
	);
 */
	add_settings_field(
		'home_blog_cat_ids',    
		__('Home blog categories ids'),   
		'home_blog_cat_ids_input', 
		'reading',    
		'default'   
	);	
	add_settings_field(
		'team_ids',    
		__('Team id'),   
		'team_ids_input', 
		'reading',    
		'default'   
	);
	add_settings_field(
		'value_ids',    
		__('Value id'),   
		'value_ids_input', 
		'reading',    
		'default'   
	);
	add_settings_field(
		'my_google_map_api',    
		__('Google map API key'),   
		'my_google_map_api_input', 
		'reading',    
		'default'   
	);	
	

	register_setting(
		'media',                 // settings page
		'site_media_settings_4',          // option name
		'admin_setti_validate_49'  // validation callback
	);	
	add_settings_field(
		'no_feat_image_id',    
		__('Default image, when post has no image'),   
		'no_feat_image_id_input', 
		'media',    
		'default'   
	);

}

// Display and fill the form field
function home_title_1_input() {
	$options = get_option('site_add_settings_4');	$value = $options['home_title_1'];
	echo '<input type="text" id="home_title_1" name="site_add_settings_4[home_title_1]" value="'.$value.'" size="60" />  E.g., 1,4,27';
}

function home_blog_cat_ids_input() {
	$options = get_option('site_add_settings_4');	$value = $options['home_blog_cat_ids'];
	echo '<input type="text" id="home_blog_cat_ids" name="site_add_settings_4[home_blog_cat_ids]" value="'.$value.'" size="40" />  E.g., 1,4,27';
}

function team_ids_input() {
	$options = get_option('site_add_settings_4');	$value = $options['team_ids'];
	echo '<input type="text" id="team_ids" name="site_add_settings_4[team_ids]" value="'.$value.'" size="40" />  E.g., 1,4,27';
}

function value_ids_input() {
	$options = get_option('site_add_settings_4');	$value = $options['value_ids'];
	echo '<input type="text" id="value_ids" name="site_add_settings_4[value_ids]" value="'.$value.'" size="40" />  E.g., 1,4,27';
}


function my_google_map_api_input() {
	$options = get_option('site_add_settings_4');	$value = $options['my_google_map_api'];
	echo '<input type="text" id="my_google_map_api" name="site_add_settings_4[my_google_map_api]" value="'.$value.'" size="50" />  E.g., AIzaSyB26HqhWs5_krwnhSuRbUFh4limZ7PCRy5';
}

function no_feat_image_id_input() {
	$options = get_option('site_media_settings_4');	$value = $options['no_feat_image_id'];
	echo '<input type="text" id="no_feat_image_id" name="site_media_settings_4[no_feat_image_id]" value="'.$value.'" size="40" /> Insert attachment id here';
}	

// Validate user input
function admin_setti_validate_49( $input ) {	
	$valid = $input;
	return $valid;
} 





















// add_action('admin_menu', 'create_menu_5'); /* !!!! uncomment this line */

function create_menu_5() {		
	add_menu_page(__('Settings 5' ), __('Settings 5'), 'manage_options', 'admin_settings_5_page', 'admin_settings_5_page_f', '', 192);
}


function admin_settings_5_page_f() {
		?>
        <div class="wrap">         
            <div class="title">
        <div class="chili"> <a class="logo_2" href="http://chili-web.com.ua" target="_blank"><img src="http://chili-web.com.ua/wp-content/themes/chili-web/images/logo_black.png" /></a> <div class="desc"><a href="http://chili-web.eu" target="_blank">Chili-web</a> <br />Website development</div> </div>
            <h2><?php _e('Settings 5') ?></h2>  
            </div>      
   <?php if ($_REQUEST['settings-updated']) { ?> 
        <div id="message" class="updated"><p><?php _e('Settings saved.') ?></p></div>
   <?php } ?>
  
            <form method="post" action="options.php">
        <?php
                submit_button();				

				// This prints out all setting fields
                settings_fields( 'settings_5_page' );   
                do_settings_sections( 'settings_5_page' );
				
				// add beautiful dynamic fields		
				settings_5_options_list();									
				
                submit_button();
            ?>
            </form>
        </div>
        <?php
}



add_action('admin_init', 'admin_settings_5');

function admin_settings_5() {	
	register_setting(
		'settings_5_page',        // settings page
		'settings_5_1',          // option name
		'admin_setti_validate_49'  // validation callback
	);	
	register_setting(
		'settings_5_page',              
		'settings_5_2',    
		'admin_setti_validate_49' 
	);
}



function settings_5_options_list() {
	$input_name_51 = 'settings_5_1'; ///// ///// ///// ///// ///// 	
	$pref_51 = 'opt_1_';
	
	$input_name_52 = 'settings_5_2'; ///// ///// ///// ///// /////
	$pref_52 = 'opt_2_';

?>
<script type="text/javascript">
 var num_id = 9999;

function option_pp5_add_new(input_name_1, pref_1) {  
	num_id = num_id + 1;    
	option_id = 'sf7_opt-' + num_id;
	input_name = input_name_1 + '[' + pref_1 + num_id + '][label]';
	list_div_id = input_name_1 + '_list47';

	sf7_add_new(option_id, input_name, list_div_id);
}

function sf7_add_new(option_id, input_name, list_div_id) {
	option_new_div = document.createElement("div");
  	option_new_div.className = 'option-box';
  	option_new_div.id = option_id;
	
	var option_onclik = "sf7_opt_delete('" + option_id + "')";
	option_new_div.innerHTML = '<div class="colu name"><input type="text" name="' + input_name + '" value="" /></div> <span><?php echo __('All parameters will appear after saving') ?></span> <div class="position"> <div class="submitbox"><a class="submitdelete" onclick="' + option_onclik + '"><?php echo __('Delete') ?></a></div> </div>';	 
	
	var opt_list = document.getElementById(list_div_id);
	opt_list.appendChild(option_new_div);
}

function sf7_opt_delete(option_id) {
	var el = document.getElementById( option_id );
	el.parentNode.removeChild( el );
}
</script> 

 
<?php $status_arr = array('1' => __('Enable'), '9' => '__'.__('Disable')); ?>

    
<?php /* ************   *   *************** */ ?>    
        <h3><?php _e('Options list 1') ?></h3>
        
<?php $input_name_1 = $input_name_51;  ///// ///// ///// ///// ///// ///// ///// /////
// $input_name_51 = 'settings_5_1'; 
$pref_1 = $pref_51;    ///// ///// ///// ///// ///// ///// ///// /////
// $pref_51 = 'pay_';
?>    

    <div class="<?php echo $input_name_1 ?> field_2 options wide_opt">
      
    <div class="options-header"> <div class="colu name"><?php _e('Method name') ?></div> <div class="colu descr"><?php _e('Method description') ?></div> <div class="colu descr pay_par"><?php _e('Payment options') ?></div> <div class="colu m_price"><?php echo __('Commission').' (0.01)' ?></div> <div class="colu status"><?php _e('Status') ?></div> <div class="colu position"><?php _e('Position') ?></div> </div>
    
	<div id="<?php echo $input_name_1 ?>_list47" class="settings_5_list" >
    <?php 		
	$options_5 = array();
	if(get_option($input_name_1)) { $options_5 = get_option($input_name_1); }
	$position_arr = array();
	foreach ($options_5 as $key_2 => $method) {
	$pos = 99; if($method['position'] != '') { $pos = $method['position']; } $position_arr[$key_2] = $pos; 
	}
	asort($position_arr);
	
	$num = 0;
	
	foreach ($position_arr as $key_7 => $pos) :  //// ///// 
	$method = $options_5[$key_7];	
	$num++;
	$option_id = $pref_1.'opt-'.$num;  // $pref_1 = $pref_51; 
	?>
	<div class="option-box" id="<?php echo $option_id ?>">
	<div class="colu name"><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][label]" value="<?php echo $method['label'] ?>" /> <div class="m_code"><span><?php _e('Code') ?>:</span><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][code]" value="<?php echo $method['code'] ?>" /></div></div>
    <div class="colu descr"><textarea name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][descr]"><?php echo $method['descr'] ?></textarea></div>
    <div class="colu descr pay_par"> <div><textarea name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][pay_par_1]" placeholder="<?php _e('Bank account number, purse') ?>"><?php echo $method['pay_par_1'] ?></textarea></div> <div><textarea name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][pay_par_2]" placeholder=""><?php echo $method['pay_par_2'] ?></textarea></div> </div>
    <div class="colu m_price"><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][commission]" class="w_50" value="<?php echo $method['commission'] ?>" /></div>
    <div class="colu status">
    <select name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][status]">
    <?php foreach ($status_arr as $k_value => $label) { ?> <option value="<?php echo $k_value ?>" <?php if($k_value == $method['status']) { ?>selected="selected"<?php } ?>><?php echo $label ?></option> <?php } ?>
    </select>
    </div>
    <?php $pos = 11; if($method['position'] != '') { $pos = $method['position']; } ?>   
    <div class="colu position"> <input type="text" class="opt_pos" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][position]" value="<?php echo $pos ?>" /> <div class="submitbox"><a class="submitdelete" onclick="sf7_opt_delete('<?php echo $option_id ?>')"><?php _e('Delete') ?></a></div> </div>  
    </div>
    <?php endforeach; //// ///// ?>
    
    </div>
    
    <div class="line_r"><a class="button button-primary" onclick="option_pp5_add_new('<?php echo $input_name_1 ?>', '<?php echo $pref_1 ?>')"><?php _e('Add new item') ?></a> </div>
    </div> <!-- 11 -->
  


</br>
<?php /* ************   *   *************** */ ?>	
    <h3><?php _e('Options list 2') ?></h3>

<?php $input_name_1 = $input_name_52;  ///// ///// ///// ///// ///// ///// ///// /////
// $input_name_52 = 'settings_5_2'; 
$pref_1 = $pref_52;    ///// ///// ///// ///// ///// ///// ///// /////
// $pref_52 = 'pay_';
?>      
    
    <div class="<?php echo $input_name_1 ?> field_2 options wide_opt">
      
    <div class="options-header"> <div class="colu name"><?php _e('Method name') ?></div> <div class="colu descr"><?php _e('Method description') ?></div> <div class="colu m_price"><?php _e('Price') ?></div> <div class="colu type"><?php _e('Type of pricing') ?></div> <div class="colu free"><?php _e('Subtotal for free') ?></div> <div class="colu status"><?php _e('Status') ?></div> <div class="colu position"><?php _e('Position') ?></div> </div>
    
	<div id="<?php echo $input_name_1 ?>_list47" class="settings_5_list">
    <?php 	
	$type_price_arr = array('1' => __('By order'), '2' => __('By products count'), '3' => __('By products weight'));	
	
	$options_5 = array();
	if(get_option($input_name_1)) { $options_5 = get_option($input_name_1); }
	$position_arr = array();
	foreach ($options_5 as $key_2 => $method) {
	$pos = 99; if($method['position'] != '') { $pos = $method['position']; } $position_arr[$key_2] = $pos; 
	}
	asort($position_arr);
	
	$num = 0;
	
	foreach ($position_arr as $key_7 => $pos) :  //// ///// 
	$method = $options_5[$key_7];	
	$num++;
	$option_id = $pref_1.'opt-'.$num;  // $pref_1 = $pref_51; 
	?>    
	<div class="option-box" id="<?php echo $option_id ?>">
	<div class="colu name"><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][label]" value="<?php echo $method['label'] ?>" /> <div class="m_code"><span><?php _e('Code') ?>:</span><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][code]" value="<?php echo $method['code'] ?>" /></div></div>
    <div class="colu descr"><textarea name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][descr]"><?php echo $method['descr'] ?></textarea></div>
    <div class="colu m_price"><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][price]" class="w_50" value="<?php echo $method['price'] ?>" /></div>
    <div class="colu type"><select name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][type_price]">
    <?php foreach ($type_price_arr as $k_value => $label) { ?> <option value="<?php echo $k_value ?>" <?php if($k_value == $method['type_price']) { ?>selected="selected"<?php } ?>><?php echo $label ?></option> <?php } ?>
    </select></div>
    <div class="colu free"><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][subtotal_free]" class="w_50" value="<?php echo $method['subtotal_free'] ?>" /> </div>
    <div class="colu status"><select name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][status]">
    <?php foreach ($status_arr as $k_value => $label) { ?> <option value="<?php echo $k_value ?>" <?php if($k_value == $method['status']) { ?>selected="selected"<?php } ?>><?php echo $label ?></option> <?php } ?>
    </select></div>
    <?php $pos = 11; if($method['position'] != '') { $pos = $method['position']; } ?>
    <div class="colu position"> <input type="text" class="opt_pos" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][position]" value="<?php echo $pos ?>" /> <div class="submitbox"><a class="submitdelete" onclick="sf7_opt_delete('<?php echo $option_id ?>')"><?php echo __('Delete') ?></a></div> </div>
    </div>
    <?php endforeach; //// ///// ?>
    
    </div>
    
    <div class="line_r"><a class="button button-primary" onclick="option_pp5_add_new('<?php echo $input_name_1 ?>', '<?php echo $pref_1 ?>')"><?php echo __('Add new item') ?></a> </div>
    </div> <!-- shipp -->
    
    <pre><?php // print_r($options_shipp); ?></pre>
    
 


<?php
}




?>