<?php

function wow_settings_page_f() {
		?>
        <div class="wrap">         
            <div class="title">
        <div class="chili"> <a class="logo_2" href="http://chili-web.com.ua" target="_blank"><img src="http://chili-web.com.ua/wp-content/themes/chili-web/images/logo_black.png" /></a> <div class="desc"><a href="http://chili-web.eu" target="_blank">Chili-web</a> <br />Website development</div> </div>
            <h2>WOW. <?php _e('E-shop settings') ?></h2>  
            </div>      
   <?php if ($_REQUEST['settings-updated']) { ?> 
        <div id="message" class="updated"><p><?php _e('Settings saved.') ?></p></div>
   <?php } ?>


<h2 class="nav-tab-wrapper">

<?php	$cur_tab_key = ( isset($_GET['tab']) ? $_GET['tab'] : 'wow_page' ); ////////////// /////////

$tabs_arr = array('wow_page' => __('General settings'), 'pay_shipp' => __('Payment and shipping methods'));
	
	foreach ( $tabs_arr as $tab_key => $tab_name ) {
	$tab_link = esc_url(admin_url('admin.php?page=wow_settings&tab='.$tab_key));
	echo '<a class="nav-tab ' . ($cur_tab_key == $tab_key ? 'nav-tab-active' : ''). '"' . ($cur_tab_key == $tab_key ? '' : 'href="' . $tab_link . '"'). '>' . $tab_name . '</a>';
	} 
?>
</h2>
   
            <form method="post" action="options.php">
        <?php
                submit_button();
				

				// This prints out all setting fields
                settings_fields( $cur_tab_key );   
                do_settings_sections( $cur_tab_key );
				
				
				if(in_array($cur_tab_key, array('pay_shipp'))) {					
				WOW_Settings::shipping_methods();	
				} 
				
				
                submit_button();
            ?>
            </form>
        </div>
        <?php
}



add_action('admin_init', 'wow_settings');

function wow_settings(){
	
	register_setting(
		'wow_page',                // settings page
		'wow_settings_arr',         // option name
		'admin_settings_validate'  // validation callback
	);
	
	/* * payment methods * */
	register_setting(
		'pay_shipp',              
		'wow_payment_methods',    
		'admin_settings_validate' 
	);
	/* * shipping methods * */
	register_setting(
		'pay_shipp',               
		'wow_shipping_methods',     
		'admin_settings_validate' 
	);
	/* * cities * */
	register_setting(
		'pay_shipp',               
		'wow_cities_list',     
		'admin_settings_validate' 
	);

	add_settings_section(
            'shop_type', // ID
            __('Type of e-shop. Add to cart effect'), // Title
            'print_section_info', // Callback
            'wow_page' // Page
    );
	add_settings_section(
            'default', 
            __('Default Settings'), 
            'print_section_info', 
            'wow_page' 
    );
	add_settings_section(
            'checkout_and_currency', 
            __('Checkout and currency'), 
            'print_section_info', 
            'wow_page' 
    );
					
	add_settings_field(
		'wow_quick_order_mode',    
		__('Quick order mode (without cart)'),   
		'wow_quick_order_mode_input', 
		'wow_page',    
		'shop_type'   
	);
		
	add_settings_field(
		'wow_to_cart_fly',    
		__('Add to cart effect'),   
		'wow_to_cart_fly_input', 
		'wow_page',    
		'shop_type'   
	);
	
	add_settings_field(
		'wow_currency',    
		__('Currency'),   
		'wow_currency_input', 
		'wow_page',    
		'checkout_and_currency'   
	);	
		
	add_settings_field(
		'wow_currency_precision',    
		__('Currency precision'),  
		'wow_currency_precision_input', 
		'wow_page',    
		'checkout_and_currency'   
	);

	add_settings_field(
		'wow_view_mode',    
		__('Default view mode'),
		'wow_view_mode_input',
		'wow_page',    
		'default'
	);	

	add_settings_field(
		'wow_view_mode_one',    
		__('Only one view mode'),
		'wow_view_mode_one_input',
		'wow_page',    
		'default'
	);	

	add_settings_field(
		'wow_prod_count_list',    
		__('Products per page (List)'),
		'wow_prod_count_list_input',
		'wow_page',    
		'default'
	);	

	add_settings_field(
		'wow_prod_count_grid',    
		__('Products per page (Grid)'),
		'wow_prod_count_grid_input',
		'wow_page',    
		'default'
	);	

	add_settings_field(
		'wow_base_sorting',    
		__('Basic sort options'),
		'wow_base_sorting_input',
		'wow_page',    
		'default'
	);	

	add_settings_field(
		'wow_product_popular_count_hits',    
		__('Count of views to become Popular product'),
		'wow_product_popular_count_hits_input',
		'wow_page',    
		'default'
	);	
	
	add_settings_field(
		'wow_product_bestsel_count_hits',    
		__('Count of sales to become Best seller product'),
		'wow_product_bestsel_count_hits_input',
		'wow_page',    
		'default'
	);

	add_settings_field(
		'wow_gal_mode',    
		__('Image gallery. Mode'),   
		'wow_gal_mode_input', 
		'wow_page',    
		'default'   
	);	

 	add_settings_field(
		'wow_cart_discount_perc',    
		__('Discount'),   
		'wow_cart_discount_perc_input', 
		'wow_page',    
		'checkout_and_currency'   
	);	

	add_settings_field(
		'wow_min_cart_subtotal',    
		__('Min cart subtotal'),   
		'wow_min_cart_subtotal_input', 
		'wow_page',    
		'checkout_and_currency'   
	);	
	
	add_settings_field(
		'wow_checkout_fields',    
		__('Checkout fields'),   
		'wow_checkout_fields_input', 
		'wow_page',    
		'checkout_and_currency'   
	);	
/* 
	add_settings_field(
		'wow_city_comment',    
		__('City comment'),   
		'wow_city_comment_input', 
		'wow_page',    
		'default'   
	);

	add_settings_field(
		'wow_city_comment_2',    
		__('City comment 2'),   
		'wow_city_comment_2_input', 
		'wow_page',    
		'default'   
	);
 */		
	add_settings_field(
		'wow_payment_comment_1',    
		__('Payment. General comment'),   
		'wow_payment_comment_1_input', 
		'wow_page',    
		'checkout_and_currency'   
	);	

	add_settings_field(
		'wow_shipping_comment_1',    
		__('Shipping. General comment'),   
		'wow_shipping_comment_1_input', 
		'wow_page',    
		'checkout_and_currency'   
	);	

	add_settings_field(
		'wow_order_email',    
		__('Email for orders'),   
		'wow_order_email_input', 
		'wow_page',    
		'checkout_and_currency'   
	);
								
}


function print_section_info() {
}



function wow_quick_order_mode_input() {
	$options = get_option('wow_settings_arr');	$value_2 = $options['wow_quick_order_mode'];
	echo '<input type="hidden" name="wow_settings_arr[wow_quick_order_mode]" value="" />';
	$check = ''; if($value_2) { $check = ' checked="checked"'; }
	echo '<label for="wow_quick_order_mode">quick order mode</label>  ';
	echo '<input type="checkbox" id="wow_quick_order_mode" name="wow_settings_arr[wow_quick_order_mode]" value="1"'.$check.' />';
}

function wow_to_cart_fly_input() {
	$options = get_option('wow_settings_arr');	$value_2 = $options['wow_to_cart_fly'];
	echo '<input type="hidden" name="wow_settings_arr[wow_to_cart_fly]" value="" />';
	$check = ''; if($value_2) { $check = ' checked="checked"'; }
	echo '<label for="wow_to_cart_fly">Flying effect</label>  ';
	echo '<input type="checkbox" id="wow_to_cart_fly" name="wow_settings_arr[wow_to_cart_fly]" value="1"'.$check.' />';
}

function wow_currency_input() {
	$options = get_option('wow_settings_arr');	$value_4 = $options['wow_currency'];
	$currency_arr = WOW_Settings::wow_currency_list();
echo '<div class="currency_blok">';
	echo '<div class="line_1">';
	foreach ($currency_arr as $c_key => $c_label) : 
	$cur_id = 'cur-'.$c_key;
	$check = ''; if($value_4['avail'][$c_key]) { $check = ' checked="checked"'; }
	echo '<span class="line_lab_2">'; 
	echo '<label class="line_lab" for="'.$cur_id.'">'.$c_label.'</label> ';
	echo '<input type="checkbox" id="'.$cur_id.'" name="wow_settings_arr[wow_currency][avail]['.$c_key.']" value="'.$c_key.'"'.$check.' />'; 
	echo '</span>';
	endforeach;
	echo '</div>';

	echo '<div class="line_1">'; // kurs 
	echo '<strong>'.__('Currency rates').': </strong>';
	if(is_array($value_4['avail'])) {
	foreach ($value_4['avail'] as $c_key => $c_val) : 
	$cur_id = 'cur_r-'.$c_key;
	$c_value = $value_4['rates'][$c_key];
	$clas_4 = '';
	if(preg_match("/[^0-9.]/", $c_value) or (substr_count($c_value, '.') > 1)) { $clas_4 = 'error'; }
	echo '<span class="line_lab_2">'; 
	echo '<label class="line_lab" for="'.$cur_id.'">'.$c_key.'</label> ';
	echo '<input type="text" id="'.$cur_id.'" name="wow_settings_arr[wow_currency][rates]['.$c_key.']" class="'.$clas_4.'" value="'.$c_value.'" placeholder="1" size="15" />';
	echo '</span>';
	endforeach;
	}
	echo '<span class="line_lab">E.g., 2.45</span>';
	echo '</div>';

	echo '<div class="line_1">'; // symbols 
	echo '<strong>'.__('Currency symbols').': </strong>';
	if(is_array($value_4['avail'])) {
	foreach ($value_4['avail'] as $c_key => $c_val) : 
	$cur_id = 'cur_s-'.$c_key;	
	echo '<span class="line_lab_2">';
	echo '<label class="line_lab" for="'.$cur_id.'">'.$c_key.'</label> ';
	echo '<input type="text" id="'.$cur_id.'" name="wow_settings_arr[wow_currency][symbols]['.$c_key.']" value="'.$value_4['symbols'][$c_key].'" placeholder="'.$c_key.'" size="20" />';
	echo '</span>';
	endforeach;
	}
	echo '</div>';
	
	echo '<div class="line_1 maine_curr">';
	$curr_settings = array('base' => __('Base currency'), 'main' => __('Main currency').'. '.__('Is visible to site visitors'));
	foreach ($curr_settings as $key4 => $label4) : 
	echo '<div class="colu wid"> <label class="lab4">'.$label4.'</label>';
	echo '<select name="wow_settings_arr[wow_currency]['.$key4.']">';
	foreach ($currency_arr as $c_key => $c_label) { 
	$check = ''; if($value_4[$key4] == $c_key) { $check = ' selected="selected"'; }
	echo '<option value="'.$c_key.'"'.$check.'>'.$c_label.'</option>';
	} // foreach 
	echo '</select>';
	echo '</div>';
	endforeach;
	echo '</div>';
echo '</div>';
}


function wow_currency_precision_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_currency_precision'];
	$clas_4 = '';
	if(preg_match("/[^0-9]/", $value)) { $clas_4 = 'error'; }
	echo '<input type="text" id="wow_currency_precision" name="wow_settings_arr[wow_currency_precision]" class="'.$clas_4.'" value="'.$value.'" size="12" /> E.g., 2';
}

function wow_view_mode_input() {
	$options = get_option('wow_settings_arr');	$value_2 = $options['wow_view_mode'];
	// $check = ''; if($value_2) { $check = ' checked="checked"'; }
	echo '<input type="hidden" name="wow_settings_arr[wow_view_mode]" value="" />';
	$view_mode_arr = array('grid' => __('Grid'), 'list' => __('List'));
	foreach ($view_mode_arr as $v_key => $v_label) : 
	$mode_id = 'mode-'.$v_key;
	$check = ''; if($value_2 == $v_key) { $check = ' checked="checked"'; }
	echo '<span class="line_lab_2">';
	echo '<label class="line_lab" for="'.$mode_id.'">'.$v_label.'</label>  ';
	echo '<input type="radio" id="'.$mode_id.'" name="wow_settings_arr[wow_view_mode]" value="'.$v_key.'"'.$check.' />';
	echo '</span>';
	endforeach;
}
 
function wow_view_mode_one_input() {
	$options = get_option('wow_settings_arr');	$value_2 = $options['wow_view_mode_one'];
	echo '<input type="hidden" name="wow_settings_arr[wow_view_mode_one]" value="" />';
	$check = ''; if($value_2) { $check = ' checked="checked"'; }
	echo '<label for="wow_view_mode_one">1 view mode</label>  ';
	echo '<input type="checkbox" id="wow_view_mode_one" name="wow_settings_arr[wow_view_mode_one]" value="1"'.$check.' />';
}

function wow_prod_count_list_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_prod_count_list'];
	echo '<input type="text" id="wow_prod_count_list" name="wow_settings_arr[wow_prod_count_list]" value="'.$value.'" size="12" /> E.g., 8';
}

function wow_prod_count_grid_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_prod_count_grid'];
	echo '<input type="text" id="wow_prod_count_grid" name="wow_settings_arr[wow_prod_count_grid]" value="'.$value.'" size="12" /> E.g., 12';
}

function wow_base_sorting_input() {
	$options = get_option('wow_settings_arr');	$value_2 = $options['wow_base_sorting'];
	// $sort_arr = array('title' => __('Title'), 'date' => __('Date'), 'comment_count' => __('Comments'), 'views' => __('Views count'));
	echo '<input type="hidden" name="wow_settings_arr[wow_base_sorting]" value="0" />';
	$sort_arr = WOW_Product_List_Func::get_sorting_labels_arr();
	foreach ($sort_arr as $s_key => $s_label) : 
	$sort_id = 'sort-'.$s_key;
	$check = '';  if(is_array($value_2)) { if($value_2[$s_key]) { $check = ' checked="checked"'; } }
	echo '<span class="line_lab_2">';
	echo '<label class="line_lab" for="'.$sort_id.'">'.$s_label.'</label>  ';
	echo '<input type="checkbox" id="'.$sort_id.'" name="wow_settings_arr[wow_base_sorting]['.$s_key.']" value="'.$s_key.'"'.$check.' />';
	echo '</span>';
	endforeach;
}

function wow_product_popular_count_hits_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_product_popular_count_hits'];
	echo '<input type="text" id="wow_product_popular_count_hits" name="wow_settings_arr[wow_product_popular_count_hits]" value="'.$value.'" size="12" /> <span>E.g., 10. Leave blank to use custom attribute "popular_prod"</span>';
}

function wow_product_bestsel_count_hits_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_product_bestsel_count_hits'];
	echo '<input type="text" id="wow_product_bestsel_count_hits" name="wow_settings_arr[wow_product_bestsel_count_hits]" value="'.$value.'" size="12" /> <span>E.g., 4. Leave blank to use custom attribute "bestseller_prod"</span>';
}

function wow_gal_mode_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_gal_mode'];
	$gal_mode_arr = array( '0' => __('Classic'), '1' => __('Lightbox only'), '2' => __('Cloud zoom') );
	echo '<select name="wow_settings_arr[wow_gal_mode]">';
	foreach ($gal_mode_arr as $key_2 => $label_2) { 
	$check = ''; if($value == $key_2) { $check = ' selected="selected"'; }
	echo '<option value="'.$key_2.'"'.$check.'>'.$label_2.'</option>';
	} // foreach 
	echo '</select>';
}

function wow_cart_discount_perc_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_cart_discount_perc'];
	echo '<input type="text" id="wow_cart_discount_perc" name="wow_settings_arr[wow_cart_discount_perc]" value="'.$value.'" size="12" /> <strong>Discount for registered users, %</strong> | E.g., 4';
}

function wow_min_cart_subtotal_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_min_cart_subtotal'];
	echo '<input type="text" id="wow_min_cart_subtotal" name="wow_settings_arr[wow_min_cart_subtotal]" value="'.$value.'" size="12" /> E.g., 50';
}

function wow_checkout_fields_input() {
	$options = get_option('wow_settings_arr');	$value_4 = $options['wow_checkout_fields']; /////
	echo '<div class="line_1 fields">';
	$checkout_fields = array('first_name' => __('Name'), 'last_name' => __('Last Name'), 'email' => __('Email'), 'phone' => __('Phone'), 'city' => __('City'), 'address' => __('Address'), 'comment' => __('Comment'));
	$opt4_arr = array('simple' => __('Simple'), 'required' => __('Is required'), 'hide' => __('Hide'));
	foreach ($checkout_fields as $key4 => $label4) : 
	echo '<div class="colu with_fon"> <label class="lab4">'.$label4.'</label>';
	$dis_1 = ''; // if($key4 == 'first_name') {$dis_1 = ' disabled="disabled"';}
	echo '<select name="wow_settings_arr[wow_checkout_fields]['.$key4.'][status]"'.$dis_1.'>';
	foreach ($opt4_arr as $c_key => $c_label) { 
	$check = ''; if($value_4[$key4]['status'] == $c_key) { $check = ' selected="selected"'; }
	echo '<option value="'.$c_key.'"'.$check.'>'.$c_label.'</option>';
	} // foreach 
	echo '</select>';
	echo '<textarea name="wow_settings_arr[wow_checkout_fields]['.$key4.'][label]" cols="32" rows="1" placeholder="'.$label4.'">'.$value_4[$key4]['label'].'</textarea>';
	echo '</div>';
	endforeach;
	echo '</div>';
}

function wow_city_comment_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_city_comment'];
	echo '<textarea id="wow_city_comment" name="wow_settings_arr[wow_city_comment]" cols="120" rows="2">'.$value.'</textarea>';
}

function wow_city_comment_2_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_city_comment_2'];
	echo '<textarea id="wow_city_comment_2" name="wow_settings_arr[wow_city_comment_2]" cols="120" rows="2">'.$value.'</textarea>';
}

function wow_payment_comment_1_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_payment_comment_1'];
	echo '<textarea id="wow_payment_comment_1" name="wow_settings_arr[wow_payment_comment_1]" cols="120" rows="2">'.$value.'</textarea>';
}

function wow_shipping_comment_1_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_shipping_comment_1'];
	echo '<textarea id="wow_shipping_comment_1" name="wow_settings_arr[wow_shipping_comment_1]" cols="120" rows="2">'.$value.'</textarea>';
}
    
function wow_order_email_input() {
	$options = get_option('wow_settings_arr');	$value = $options['wow_order_email'];
	echo '<input type="text" id="wow_order_email" name="wow_settings_arr[wow_order_email]" value="'.$value.'" size="40" /> <span>Leave blank to use default admin email</span>';
}



// Validate user input
function admin_settings_validate( $input ) {	 
	$valid = $input;	
	return $valid;
} 






class WOW_Settings {


function wow_currency_list() {
	// 'RUB' => __('Russia Ruble'),
	return array( 'USD' => __('US Dollar'), 'EUR' => __('Euro'), 'UAH' => __('Ukraine Hryvnia'), 'GBP' => __('United Kingdom Pound'), 'PLN' => __('Poland Zloty'), 'CNY' => __('China Yuan'), 'CLP' => __('Chile Peso') );
}

function pay_online_methods_list() {
	/// robokassa, qiwi ....
	return array('webmoney', 'paypal', 'privat24', 'liqpay', 'online-bank');
}


function shipping_methods() {
	$pref_pay = 'pay_';
	$pref_shipp = 'shipp_';
	$pref_city = 'city_';
?>
<script type="text/javascript">
 var num_id = 9999;

function opt_pay_add_new() {  
	num_id = num_id + 1;    
	option_id = 'sf7_opt-' + num_id;
	input_name = 'wow_payment_methods[<?php echo $pref_pay ?>' + num_id + '][label]';
	list_div_id = 'payment_methods_list47';

	sf7_add_new(option_id, input_name, list_div_id);
}

function opt_shipp_add_new() {  
	num_id = num_id + 1;    
	option_id = 'sf7_opt-' + num_id;
	input_name = 'wow_shipping_methods[<?php echo $pref_shipp ?>' + num_id + '][label]';
	list_div_id = 'shipping_methods_list47';

	sf7_add_new(option_id, input_name, list_div_id);
}

function opt_city_add_new() {  
	num_id = num_id + 1;    
	option_id = 'sf7_opt-' + num_id;
	input_name = 'wow_cities_list[<?php echo $pref_city ?>' + num_id + '][label]';
	list_div_id = 'cities_list47';

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
        <h3><?php _e('Payment methods') ?></h3>
    
    <div class="payment field_2 options wide_opt">
      
    <div class="options-header"> <div class="colu name"><?php _e('Method name') ?></div> <div class="colu descr"><?php _e('Method description') ?></div> <div class="colu descr pay_par"><?php _e('Payment options') ?></div> <div class="colu m_price"><?php echo __('Commission').' (0.01)' ?></div> <div class="colu status"><?php _e('Status') ?></div> <div class="colu position"><?php _e('Position') ?></div> </div>
    
	<div id="payment_methods_list47" class="pay_list" >
    <?php 
	$options_pay = array();
	if(get_option('wow_payment_methods')) { $options_pay = get_option('wow_payment_methods'); }
	$position_arr = array();
	foreach ($options_pay as $key_2 => $method) {
	$pos = 99; if($method['position'] != '') { $pos = $method['position']; } $position_arr[$key_2] = $pos; 
	}
	asort($position_arr);
	// array('webmoney', 'paypal', 'privat24', 'liqpay', 'online-bank');
	$online_methods_arr = WOW_Settings::pay_online_methods_list();
	
	$input_name_1 = 'wow_payment_methods';  
	$pref_1 = $pref_pay;  // $pref_pay = 'pay_';
	$num = 0;
	
	foreach ($position_arr as $key_7 => $pos) :  //// ///// 
	$method = $options_pay[$key_7];	
	$num++;
	$option_id = $pref_1.'opt-'.$num;  // $pref_1 = $pref_pay; 
	?>
	<div class="option-box" id="<?php echo $option_id ?>">
	<div class="colu name"><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][label]" value="<?php echo $method['label'] ?>" /> <div class="m_code"><span><?php _e('Code') ?>:</span><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][code]" value="<?php echo $method['code'] ?>" <?php if(in_array($method['code'], $online_methods_arr)) { ?>readonly="readonly"<?php } ?> /></div></div>
    <div class="colu descr"><textarea name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][descr]"><?php echo $method['descr'] ?></textarea></div>
    <div class="colu descr pay_par"> <div><textarea name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][pay_par_1]" placeholder="<?php _e('Bank account number, purse') ?>"><?php echo $method['pay_par_1'] ?></textarea></div> <div><textarea name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][pay_par_2]" placeholder=""><?php echo $method['pay_par_2'] ?></textarea></div> </div>
    <div class="colu m_price"><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][commission]" class="w_50" value="<?php echo $method['commission'] ?>" /></div>
    <div class="colu status">
    <select name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][status]">
    <?php foreach ($status_arr as $k_value => $label) { ?> <option value="<?php echo $k_value ?>" <?php if($k_value == $method['status']) { ?>selected="selected"<?php } ?>><?php echo $label ?></option> <?php } ?>
    </select>
<?php if(in_array($method['code'], $online_methods_arr)) { ?><div class="test"><label class="line_lab" for="test_mode-<?php echo $num ?>"><?php _e('Test') ?></label> <input type="checkbox" id="test_mode-<?php echo $num ?>" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][test_mode]" value="1"<?php if($method['test_mode'] == 1) { ?> checked="checked"<?php } ?> /></div><?php } ?>
    </div>
    <?php $pos = 11; if($method['position'] != '') { $pos = $method['position']; } ?>   
    <div class="colu position"> <input type="text" class="opt_pos" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][position]" value="<?php echo $pos ?>" /> <div class="submitbox"<?php if(in_array($method['code'], $online_methods_arr)) { ?> style="display:none;"<?php } ?>><a class="submitdelete" onclick="sf7_opt_delete('<?php echo $option_id ?>')"><?php echo __('Delete') ?></a></div> </div>  
    </div>
    <?php endforeach; //// ///// ?>
    
    </div>
    
    <div class="line_r"><a class="button button-primary" onclick="opt_pay_add_new()"><?php echo __('Add new method') ?></a> </div>
    </div> <!-- payment -->
  


</br>
<?php /* ************   *   *************** */ ?>	
    <h3><?php _e('Shipping methods') ?></h3>
    
    <div class="shipp field_2 options wide_opt">
      
    <div class="options-header"> <div class="colu name"><?php _e('Method name') ?></div> <div class="colu descr"><?php _e('Method description') ?></div> <div class="colu m_price"><?php _e('Price') ?></div> <div class="colu type"><?php _e('Type of pricing') ?></div> <div class="colu free"><?php _e('Subtotal for free') ?></div> <div class="colu status"><?php _e('Status') ?></div> <div class="colu position"><?php _e('Position') ?></div> </div>
    
	<div id="shipping_methods_list47" class="shipp_list" >
    <?php 
	$options_shipp = array();
	if(get_option('wow_shipping_methods')) { $options_shipp = get_option('wow_shipping_methods'); }
	$position_arr = array();
	foreach ($options_shipp as $key_2 => $method) { 
	$pos = 99; if($method['position'] != '') { $pos = $method['position']; } $position_arr[$key_2] = $pos; 
	}
	asort($position_arr);
		
	$input_name_1 = 'wow_shipping_methods';  
	$pref_1 = $pref_shipp;  // $pref_shipp = 'shipp_';
	$type_price_arr = array('1' => __('By order'), '2' => __('By products count'), '3' => __('By products weight'));	
	$num = 0;
	
	foreach ($position_arr as $key_7 => $pos) :  //// ///// 
	$method = $options_shipp[$key_7];
	$num++;
	$option_id = $pref_1.'opt-'.$num;  // $pref_1 = $pref_shipp; 
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
    
    <div class="line_r"><a class="button button-primary" onclick="opt_shipp_add_new()"><?php echo __('Add new method') ?></a> </div>
    </div> <!-- shipp -->
    
    <pre><?php // print_r($options_shipp); ?></pre>
    
        
    
    
    </br>
<?php /* ************   *   *************** */ ?>	
    <h3><?php _e('Cities for shipping') ?></h3>
    
    <div class="cities field_2 options">
      
    <div class="options-header"> <div class="colu name"><?php _e('City') ?></div> <div class="colu m_price"><?php _e('Shipping ratio') ?></div> </div>
    
	<div id="cities_list47" class="cities_list" >
    <?php 
	$options_cities = array();
	if(get_option('wow_cities_list')) { $options_cities = get_option('wow_cities_list'); }
	$position_arr = array();
	foreach ($options_cities as $key_2 => $city) { 
	$pos = 99; if($city['position'] != '') { $pos = $city['position']; } $position_arr[$key_2] = $pos; 
	}
	asort($position_arr);
		
	$input_name_1 = 'wow_cities_list';  
	$pref_1 = $pref_city;  // $pref_city = 'city_';
	$num = 0;
	
	foreach ($position_arr as $key_7 => $pos) :  //// ///// 
	$city = $options_cities[$key_7];
	$num++;
	$option_id = $pref_1.'opt-'.$num;  // $pref_1 = $pref_city; 
	?>
	<div class="option-box" id="<?php echo $option_id ?>">
	<div class="colu name"><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][label]" value="<?php echo $city['label'] ?>" /></div>    
    <div class="colu m_price"><input type="text" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][price]" class="w_50" value="<?php echo $city['price'] ?>" /></div>   
    <?php $pos = 91; if($city['position'] != '') { $pos = $city['position']; } ?>
    <div class="colu position"> <input type="text" class="opt_pos" name="<?php echo $input_name_1 ?>[<?php echo $pref_1.$num ?>][position]" value="<?php echo $pos ?>" /> <div class="submitbox"><a class="submitdelete" onclick="sf7_opt_delete('<?php echo $option_id ?>')"><?php echo __('Delete') ?></a></div> </div>
    </div>
    <?php endforeach; //// ///// ?>
    
    </div>
    
    <div class="line_r"><a class="button button-primary" onclick="opt_city_add_new()"><?php echo __('Add new city') ?></a> </div>
    </div> <!-- cities -->



<?php
}

}



?>