<?php 


class WOW_Checkout {
	
function save_new_order() {
	if($_POST['customer']) :
	$_POST = stripslashes_deep($_POST); /// /* !!!! */
	
if($_POST['quick_order_products']) { // //// quick order
$products = $_POST['quick_order_products'];
$r_total_arr = array();
foreach ($products as $id => $p_qty) { 
$r_total_2 = WOW_Attributes_Front::cart_row_subtotal($id, $p_qty);  $r_total = $r_total_2['row_total'];
$r_total_arr[] = $r_total;
 }
$subtotal_base = array_sum($r_total_arr);
}
else {  // //// standart order
$products = WOW_Cart_Session::cart_array(); 
$subtotal_base = WOW_Cart_Session::cart_subtotal_base(); 
}

$options_5 = get_option('wow_settings_arr');
	$act_currency_arr = WOW_Product_List_Func::get_act_currency();
	$currency = $act_currency_arr['code'];	
	$kurs = $act_currency_arr['rate']; 
	$symb = $act_currency_arr['symbol']; 

		$customer = $_POST['customer'];
			foreach ($customer as $key_1 => $field_1) {
		$field_1 = str_replace(array("'", ":", ";", "{", "}"), "", $field_1); 
		$field_1 = str_replace('"', '', $field_1);
		$customer[$key_1] = $field_1;
			}		
  if($customer['city']) { $city_1 = explode('---', $customer['city']); $customer['city'] = $city_1[0]; }
		$pay_method = $_POST['payment_method'];
		$shipp_method = $_POST['shipping_method'];
		$comme = $_POST['comment'];
		
		$pay_shipp_arr = WOW_Checkout::pay_shipp_arrays($pay_method, $shipp_method);
		$shipp_arr = $pay_shipp_arr[$shipp_method];
		$shipp_price = 0;
		if($shipp_arr['price']) { $shipp_price = $shipp_arr['price']; } 
		if($shipp_arr['subtotal_free']) { if($subtotal_base >= $shipp_arr['subtotal_free']) { $shipp_price = 0; } }
		/* !!!! discount */
	$disc_arr = WOW_Attributes_Front::get_cart_discount();
	$disc_per_dw = $disc_arr['disc_per']; // 3 %
	$disc_base = $subtotal_base * $disc_per_dw / 100;
	/* subtotal - discount */ $subtotal_base = $subtotal_base - $disc_base;		
		
		$post_arr_2 = array();
		$post_arr_2['customer'] = $customer;
		$post_arr_2['payment_method'] = $pay_method;
		$post_arr_2['shipping_method'] = $shipp_method;
		$post_arr_2['products'] = $products;
		$post_arr_2['subtotal_base'] = $subtotal_base;
		$post_arr_2['shipp_price_base'] = $shipp_price;
		$post_arr_2['currency'] = $currency;
		$post_arr_2['kurs'] = $kurs;

$post_arr_s = serialize($post_arr_2);

$post_title = __('The order').' - '.$customer['first_name'].' '.$customer['last_name'];

$author = 1; 
if($_POST['p_author']) { $author = $_POST['p_author']; }
$new_post = array(
  'post_title'    => $post_title,
  // 'post_name'   => $post_name,
  'post_content'  => $comme,
  'post_excerpt'  => $post_arr_s,
  'post_author'   => $author,
  'pinged'   => 'pending',
  'post_type'   => 'wow_order', ///
  'post_status'   => 'private',
  // 'comment_status'  => 'closed',
  'ping_status'   => 'closed',
);
	
	
	if(count($products)) { 
	/// create order				
	$post_id_last = wp_insert_post( $new_post, $wp_error );
	wp_update_post( array('ID' => $post_id_last, 'post_title' => '#'.$post_id_last.' '.$post_title, 'post_name' => 'order-'.$post_id_last) );

$post_arr_25 = array_merge(array('order_id' => $post_id_last), $post_arr_2); //

/* send email */ WOW_Checkout::order_send_email($post_id_last, '');
	
	// count products on stock
	foreach ($products as $id => $p_qty) { 
	// $meta_arr = get_post_custom($id); $stock_2 = $meta_arr['stock'][0];
	$stock_2 = get_post_meta($id, 'stock', true);
		if($stock_2) { 
	$stock = $stock_2 - $p_qty; if($stock < 0) { $stock = 0; } /* ???? */
	update_post_meta($id, 'stock', $stock); 
		}
	$prod_sales_2 = get_post_meta($id, 'prod_sales', true);
	$prod_sales = $prod_sales_2 + 1;
	update_post_meta($id, 'prod_sales', $prod_sales);
	}
	
	// clear the shopping cart
	if(!$_POST['quick_order_products']) { $arr_2 = array(); WOW_Cart_Session::cart_save_data($arr_2); } 

/* ******* Зачистка session в БД (таблиця wp_options)  ******* */
wow_clear_database_cache();
/* *******  ******* */

	/// вивести масив з інф-ю про зроблене замовлення		
	return $post_arr_25;
	}
	
	else { return false; }	// if(!count($products))
	
	
	else : return false;	
	endif;  // ($_POST['customer'])

}



function order_send_email($order_id, $status) {
////////////////////// send email ////////////////////// 
	$labels_4_arr = array( 
		'first_name' => __('First Name'), 'last_name' => __('Last Name'), 'email' => __('Email'), 'phone' => __('Phone'), 'city' => __('City'), 'address' => __('Address'), 'payment_method' => __('Payment method'), 'shipping_method' => __('Shipping method'), 'subtotal' => __('Subtotal'), 'shipp_price' => __('Shipping'), 'grand_total' => __('Grand total'), 'status' => __('Status')
	); // 	
		$post2 = get_post($order_id);  
		$comme = $post2->post_content; 
		$post_title = $post2->post_title;
		
		$order_status = $post2->pinged;  $status_arr = WOW_Checkout::order_status_array();
		
		$excerpt = $post2->post_excerpt;		
		if ( !empty($excerpt) ) { $excerpt_arr = unserialize($excerpt); }
$products_24 = $excerpt_arr['products'];
$customer = $excerpt_arr['customer'];

$billing_arr = WOW_Checkout::order_billing_info($order_id);
$post_arr_24 = array(
	'status' => $status_arr[$order_status],
	'payment_method' => $billing_arr['pay_label'],
	'shipping_method' => $billing_arr['shipp_label'],
	'subtotal' => $billing_arr['cart_subtotal'],
	'shipp_price' => $billing_arr['shipp_price'],
	'grand_total' => $billing_arr['grand_total']
);
if($status == '') { unset($post_arr_24['status']); }
  
  $email_message .= '<h4>'.get_bloginfo('name').'. '.$post_title.'</h4>';
  foreach ($customer as $key_3 => $info_3) {
	  $email_message .= '<p>'.$labels_4_arr[$key_3].':  <strong>'.$info_3.'</strong> </p>';
  }  
   $email_message .= '</br>';
  
  $email_message .= __('Products').' :';
  $email_message .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">
  <thead>  <tr>  <th width="15%"></th>  <th width="10%">'.__('Sku').'</th>  <th width="35%">'.__('Product title').'</th>  <th width="15%">'.__('Price').'</th>  <th width="10%">'.__('Qty').'</th>  <th width="15%">'.__('Subtotal').'</th>  </tr>  </thead>
  <tbody>';
  foreach ($products_24 as $prod_id => $p_qty) { /* *** products *** */
	  		$row_price_arr = WOW_Cart_Session::cart_get_row_price($prod_id, $p_qty);			
			$sku = get_post_meta ($prod_id, 'sku', true);
			$thumb = ''; if ( has_post_thumbnail($prod_id) ) { $thumb = get_the_post_thumbnail($prod_id, 'thumbnail'); }
		$email_message .= '<tr>
		<td> <a title="'.get_the_title($prod_id).'">'.$thumb.'</a> </td>
		<td><span>'.$sku.'</span></td>
		<td> <h4><a href="'.get_permalink($prod_id).'" target="_blank">'.get_the_title($prod_id).'</a></h4></td> 
		<td><span class="price">'.$row_price_arr['item_price'].'</span></td>
		<td><span>'.$p_qty.'</span></td>
		<td><span class="price">'.$row_price_arr['row_total'].'</span></td>
		</tr>';
  } // foreach ($products_24 as $prod_id => $p_qty) 
  $email_message .= '</tbody></table> </br>';
  
  foreach ($post_arr_24 as $key_2 => $info_2) {
	  $email_message .= '<p>'.$labels_4_arr[$key_2].':  <strong>'.$info_2.'</strong> </p>';
  }
  
  if($comme) { $email_message .= '<p><em>'.__('Comment').':</em> </br>'.$comme.'</p>'; }
  $email_message .= '</br>';

$to = get_bloginfo('admin_email'); // $to = get_settings('admin_email');
$options_5 = get_option('wow_settings_arr');
if($options_5['wow_order_email']) { $to = $options_5['wow_order_email']; }
$from = get_bloginfo('admin_email');
$headers[] = 'From: '.get_bloginfo('name').' <'.$from.'>';
$email_subject = get_bloginfo('name').'. '.$post_title;
if($status == 'pay_success') { $email_subject = $email_subject.'. '.__('Payment successful!'); }
/*  function set_html_content_type() { return 'text/html'; } // внизу 
add_filter( 'wp_mail_content_type', 'set_html_content_type' ); */	 
// @mail($to, $email_subject, $email_message, $headers);  /// стандартна ф-я PHP mail 
	///// mail for admin //////
	wp_mail ($to, $email_subject, $email_message, $headers); // відправити повідомлення на email 

if($customer['email']) {
if($status != 'pay_success') {	
	$to_client = $customer['email'];	
	$page_6 = get_page_by_path('email-message');
	$title_6 = apply_filters('the_title', get_post_field('post_title', $page_6));
	$text_6_ex = apply_filters('the_excerpt', get_post_field('post_excerpt', $page_6));
	$text_6 = apply_filters('the_content', get_post_field('post_content', $page_6));
$logo_2 = '';
if (has_post_thumbnail($page_6->ID)) {
$logo_2 = '<div style="overflow: hidden;"> <a class="log_img" href="'.get_bloginfo('url').'" target="_blank" style="display: inline-block; vertical-align: middle; margin-right: 20px;">'.get_the_post_thumbnail($page_6->ID).'</a> <div class="descr" style="display: inline-block; vertical-align: middle; width: 300px;">'.get_bloginfo('description').'</div> </div>';
}
$email_message_2 = $logo_2.'<h3>'.$title_6.'</h3><div class="subtitle">'.$text_6_ex.'</div>'.$email_message.'<div>'.$text_6.'</div>'; // '<h3>'.$title_6.'</h3>'. 
	///// mail for customer //////
	wp_mail ($to_client, $email_subject, $email_message_2, $headers);
}
} // if($customer['email']) 
//////////////////// ____________send email //////////////////////
}



function pay_shipp_arrays($pay_key, $shipp_key) {
		$pay_active = array();
		$options_pay = get_option('wow_payment_methods');		
		if($options_pay) {
	$payment_methods = array();
	foreach ($options_pay as $key_2 => $method) {
	if($method['code']) { $m_key = $method['code']; } else { $m_key = $key_2; }
	$payment_methods[$m_key] = $method;	 
	}
	$pay_active = $payment_methods[$pay_key];
		}		
		
		$shipp_active = array();
		$options_shipp = get_option('wow_shipping_methods');
		if($options_shipp) {
	$shipping_methods = array();
	foreach ($options_shipp as $key_2 => $method) {
	if($method['code']) { $m_key = $method['code']; } else { $m_key = $key_2; }
	$shipping_methods[$m_key] = $method; 
	}
	$shipp_active = $shipping_methods[$shipp_key];
		}
		
		return array($pay_key => $pay_active, $shipp_key => $shipp_active);	
}


function order_billing_info($post_id) {
	$billing_arr = array();
	// $excerpt = get_the_excerpt($post_id);
	$post2 = get_post($post_id);  $excerpt = $post2->post_excerpt; /* !!!! */
		if ( !empty($excerpt) ) {
			$excerpt_arr = unserialize($excerpt); 
		
		$currency = $excerpt_arr['currency'];
		$kurs = $excerpt_arr['kurs'];
		$options_5 = get_option('wow_settings_arr');
		$symb = $currency;  $symb_2 = $options_5['wow_currency']['symbols'][$currency];
		if($symb_2) { $symb = $symb_2; }
		if (function_exists('qtrans_getSortedLanguages')) { global $q_config;  // Переклад
		$symb = qtrans_use($q_config['language'], $symb, true);
		} // -- Переклад
		
		$cart_subtotal_base = $excerpt_arr['subtotal_base'];
		$cart_subtotal_2 = $cart_subtotal_base * $kurs;  $cart_subtotal_2 = round($cart_subtotal_2, 2);
		$cart_subtotal = '<span class="price">'.$cart_subtotal_2.'<span> '.$symb.'</span></span>'; 		

	$shipp_price_base = $excerpt_arr['shipp_price_base'];
	$shipp_price_2 = $shipp_price_base * $kurs;  $shipp_price_2 = round($shipp_price_2, 2);
	$shipp_price = '<span class="price">'.$shipp_price_2.'<span> '.$symb.'</span></span>';
	
	$grand_total_base = $cart_subtotal_base + $shipp_price_base;
	$grand_total_2 = $grand_total_base * $kurs;  $grand_total_2 = round($grand_total_2, 2);
	$grand_total = '<span class="price">'.$grand_total_2.'<span> '.$symb.'</span></span>';
	
		$pay_key = $excerpt_arr['payment_method'];  $pay_label = $pay_key;
		$shipp_key = $excerpt_arr['shipping_method'];  $shipp_label = $shipp_key;
			 
		$pay_shipp_arr = WOW_Checkout::pay_shipp_arrays($pay_key, $shipp_key);
		$pay_arr = $pay_shipp_arr[$pay_key];  $shipp_arr = $pay_shipp_arr[$shipp_key];
		if($pay_arr) { $pay_label = $pay_arr['label']; }
		if($shipp_arr) { $shipp_label = $shipp_arr['label']; }
	if (function_exists('qtrans_getSortedLanguages')) { // Переклад				
			$pay_label = qtrans_use($q_config['language'], $pay_label, true);
			$shipp_label = qtrans_use($q_config['language'], $shipp_label, true);	 
	} // -- Переклад
			
	$billing_arr = array('pay_label' => $pay_label, 'shipp_label' => $shipp_label, 'cart_subtotal' => $cart_subtotal, 'shipp_price' => $shipp_price, 'grand_total' => $grand_total);
		} // if ( !empty($excerpt) )
		
		return $billing_arr;
}


function order_status_array() {
	$status_arr = array(
		'pending' => __('Pending'), 
		'processing' => __('Processing'),
		'onhold' => __('On-Hold'),
		'fulfillment' => __('Awaiting Fulfillment'),
		'completed' => __('Completed'),
		'cancelled' => __('Cancelled')
	);
	return $status_arr;
}



function pay_online($order_arr) {
/* *** цю ф-ю можна викликати і з обліклвого запису *** */
// array('webmoney', 'paypal', 'privat24', 'liqpay', 'online-bank');
$online_methods_arr = WOW_Settings::pay_online_methods_list();
	if(in_array($order_arr['payment_method'], $online_methods_arr)) : 
$p_method = $order_arr['payment_method'];
$order_id = $order_arr['order_id']; ///////// //////////

$pay_shipp_arr = WOW_Checkout::pay_shipp_arrays($p_method, 'courier');
$pay_info_arr = $pay_shipp_arr[$p_method]; 
$koef_2 = 1;  if($pay_info_arr['commission']) { $koef_2 = 1 + $pay_info_arr['commission']; }
$pay_purse_arr = explode(',', $pay_info_arr['pay_par_1']);  $pay_purse = $pay_purse_arr[0];  
$pay_key_1 = $pay_info_arr['pay_par_2'];
$payment_label = $pay_info_arr['label'];

$options_5 = get_option('wow_settings_arr'); 
$kurs = $order_arr['kurs'];
$order_currency = $order_arr['currency'];	
		$symb = $order_currency;  $symb_2 = $options_5['wow_currency']['symbols'][$order_currency];
		if($symb_2) { $symb = $symb_2; }
$grand_total_base = $order_arr['subtotal_base'] + $order_arr['shipp_price_base'];
$grand_total_2 = $grand_total_base * $kurs;  
$grand_total_7 = $grand_total_2 * $koef_2; // додаткова комісія для методу оплати 
$grand_total_7 = round($grand_total_7, 2);
$payment_desc = get_bloginfo('name').' - '.__('buy products').'. '.$order_arr['customer']['first_name'].' '.$order_arr['customer']['last_name'];

$success_url = get_permalink(get_page_by_path('checkout-success')); //  '/checkout-success'
$result_url = get_permalink(get_page_by_path('checkout-payment'));
$pay_failed_url = get_permalink(get_page_by_path('checkout-payment-failed'));
	$pay_html = '';
	
if($p_method == 'webmoney') { ////// /////////////// /////
    $grand_total_6 = $grand_total_7 * 1; // якщо валюта webmoney відрізняється від валюти сайту ....	
	// $wm_purse = 'Z145179295679'; // 'U377099351501'
	$wm_purse = $pay_purse;
	$pay_html .= '<form id="pay_online_form_webmoney" method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" accept-charset="WINDOWS-1251"> ';
    $pay_html .= '<div class="currency_w"><span>'.__('You must pay').': </span>'.$grand_total_6.' WM'.$wm_purse[0].'</div>';
	$pay_html .= '<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="'.$grand_total_6.'" />';
    $pay_html .= '<input type="hidden" name="LMI_PAYMENT_DESC" value="'.$payment_desc.'" />';  
	// $pay_html .= '<input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="'.$payment_desc.'">';
    $pay_html .= '<input type="hidden" name="LMI_PAYEE_PURSE" value="'.$wm_purse.'" />';
	$pay_html .= '<input type="hidden" name="LMI_PAYMENT_NO" value="'.$order_id.'" />';
    $pay_html .= '<input type="hidden" name="LMI_SIM_MODE" value="2">';
    $pay_html .= '<input type="hidden" name="LMI_RESULT_URL" value="'.$success_url.'" />';
	$pay_html .= '<input type="hidden" name="LMI_SUCCESS_URL" value="'.$success_url.'" />';
	$pay_html .= '<input type="hidden" name="LMI_FAIL_URL" value="'.$pay_failed_url.'" />';
	// LMI_SUCCESS_METHOD  LMI_FAIL_METHOD 
    $pay_html .= '<input type="hidden" name="payment" value="'.$order_id.'" />';
    $pay_html .= '<input type="submit" class="button pay_button" value="'.__('Go to payment').'">';
    $pay_html .= '</form>';
}

elseif($p_method == 'privat24') { ///////// ///////// /////
	$merchant_id = $pay_purse;
	$pay_html .= '<form id="pay_online_form_privat24" action="https://api.privatbank.ua/p24api/ishop" method="POST" accept-charset="UTF-8">';
    $pay_html .= '<div class="currency_w"><span>'.__('You must pay').': </span>'.$grand_total_7.' '.$symb.'</div>';
	$pay_html .= '<input type="hidden" name="amt" value="'.$grand_total_7.'" />';
    $pay_html .= '<input type="hidden" name="ccy" value="'.$order_currency.'" />';  // UAH
    $pay_html .= '<input type="hidden" name="merchant" value="'.$merchant_id.'" />';
	$pay_html .= '<input type="hidden" name="order" value="'.$order_id.'" />';
    $pay_html .= '<input type="hidden" name="details" value="'.$payment_desc.'" />';
	$pay_html .= '<input type="hidden" name="ext_details" value="'.$order_id.'" />';
	$pay_html .= '<input type="hidden" name="pay_way" value="privat24" />';	
    $pay_html .= '<input type="hidden" name="return_url" value="'.$success_url.'" />';
	$pay_html .= '<input type="hidden" name="server_url" value="'.$success_url.'" />';
    $pay_html .= '<input type="submit" class="button pay_button" value="'.__('Go to payment').'">';
    $pay_html .= '</form>';
}

elseif($p_method == 'liqpay') {  ///////// ///////// /////
// $public_key = 'i17631020423';  $private_key = '4z7DT3bRs52EMdhWATNt2Dpc3NBe5xhrFzoOzZkR';
$public_key = $pay_purse;
$private_key = $pay_key_1; 
$liqpay = new LiqPay($public_key, $private_key);
$liqpay_arr = array(
  'version'        => '3',
  'amount'         => $grand_total_7,
  'currency'       => $order_currency, // UAH 
  'description'    => $payment_desc,
  'order_id'       => $order_id,
  // 'pay_way'      => 'card',
  // 'sandbox'      => 1, // test mode 
  'server_url'       => $success_url,
  'result_url'       => $result_url
 );
if($pay_info_arr['test_mode'] == 1) { $liqpay_arr['sandbox'] = 1;  echo '__test_mode'; }
$pay_html = $liqpay->cnb_form( $liqpay_arr );
}

elseif($p_method == 'paypal') { ///////// ///////// /////
	$pay_html = '<FORM id="pay_online_form_paypal" ACTION="https://www.paypal.com/cgi-bin/webscr" METHOD="POST">
<INPUT TYPE="hidden" NAME="cmd" VALUE="_xclick">
<INPUT TYPE="hidden" NAME="business" VALUE="recipient@paypal.com">
<INPUT TYPE="hidden" NAME="undefined_quantity" VALUE="1">
<INPUT TYPE="hidden" NAME="item_name" VALUE="hat">
<INPUT TYPE="hidden" NAME="item_number" VALUE="123">
<INPUT TYPE="hidden" NAME="amount" VALUE="15.00">
<INPUT TYPE="hidden" NAME="shipping" VALUE="1.00">
<INPUT TYPE="hidden" NAME="shipping2" VALUE="0.50">
<INPUT TYPE="hidden" NAME="currency_code" VALUE="USD">
<INPUT TYPE="hidden" NAME="first_name" VALUE="John">
<INPUT TYPE="hidden" NAME="last_name" VALUE="Doe">
<INPUT TYPE="hidden" NAME="address1" VALUE="9 Elm Street">
<INPUT TYPE="hidden" NAME="address2" VALUE="Apt 5">
<INPUT TYPE="hidden" NAME="city" VALUE="Berwyn">
<INPUT TYPE="hidden" NAME="state" VALUE="PA">
<INPUT TYPE="hidden" NAME="zip" VALUE="19312">
<INPUT TYPE="hidden" NAME="lc" VALUE="US">
<INPUT TYPE="hidden" NAME="email" VALUE="buyer@domain.com">
<INPUT TYPE="hidden" NAME="night_phone_a" VALUE="610">
<INPUT TYPE="hidden" NAME="night_phone_b" VALUE="555">
<INPUT TYPE="hidden" NAME="night_phone_c" VALUE="1234">
<INPUT TYPE="submit" NAME="submit" class="button pay_button" value="'.__('Go to payment').'" >
</FORM>';
}

$pay_later_descr = __('You can do this in your account'); // __('You can do this manually after you receive our payment details'); //
// echo '___________<pre>'; print_r($pay_info_arr); echo '</pre>';
echo '<div class="payment_area"> 
<div class="col col_1"><div class="pay_icon '.$p_method.'"></div></div> 
<div class="col col_2"> 
<div class="deco method"><span>'.__('Payment method').': </span> '.$payment_label.'</div> <div class="deco tot"><span>'.__('Grand total').': </span> '.$grand_total_2.' '.$symb.'</div> 
<div class="payment">'.$pay_html.'</div> <div class="lat_link"><a href="'.$success_url.'?my_pay=later">'.__('Make Payment later').'</a><div class="comment">'.$pay_later_descr.'</div></div> </div>
</div>';

	endif;  // ($order_arr['payment_method'])
}


function pay_online_success() { 
$pay_success = 0;

	if($_POST['payment'] or $_POST['order_id']) : 
if($_POST['order_id']) { $order_id = $_POST['order_id']; } else { $order_id = $_POST['payment']; }
	if(strpos($order_id, 'order=') !== false) {
$fragss_11 = explode('order=', $order_id); 
$frag_1 = $fragss_11[1]; $fragss_3 = explode('&', $frag_1);  $order_id = $fragss_3[0]; 
	}
$pay_success = 1;
wp_update_post( array('ID' => $order_id, 'pinged' => 'fulfillment') );
	endif;  // ($_POST['payment'])	

	if($_POST['data']) : /// liqpay 
$data = $_POST['data'];
$post_61 = base64_decode($data);
$payment_arr = array();
$uuwg_2 = str_replace(array('{', '}', '"'), '', $post_61); 
$arr_2 = explode(',', $uuwg_2);
foreach ($arr_2 as $line_2) { 
	$line_2_arr = explode(':', $line_2); 
	$payment_arr[$line_2_arr[0]] = $line_2_arr[1];
}
$order_id = $payment_arr['order_id'];
if($order_id and !in_array($payment_arr['status'], array('failure'))) {
$pay_success = 1;
wp_update_post( array('ID' => $order_id, 'pinged' => 'fulfillment') );
}
	endif;  // ($_POST['payConfirm'])	

if($pay_success == 1) { // 
		if(get_post_meta($order_id, 'pay_success_email', true) != 1) {
	/* send email */ WOW_Checkout::order_send_email($order_id, 'pay_success');		
	add_post_meta($order_id, 'pay_success_email', 1, true);
		}
}

}



}




function wow_custom_types_in_2() { 
 
register_post_type( 'wow_order',
		array(
			'labels' => array(
				'name' => __( 'Orders' ),
				'singular_name' => __( 'Order' ),
				'add_new' => __( 'Add New Order' ),
				'add_new_item' => __( 'Add New Order' ),
				'edit' => __( 'Edit Order' ),
				'edit_item' => __( 'Edit Order' ),
			),			
			'supports' => array( 'title', 'editor' ),
			'public' => true,
			'show_in_menu' 			=> true,
			'menu_position' 	=> 54,	
			'show_in_nav_menus' 	=> false,
			'capabilities' => array( 'create_posts' => false ),
			'map_meta_cap' => true,
			// 'taxonomies' 			=> array(),
			// 'rewrite' 			=> true,
			'rewrite' => array('slug' => 'order', 'with_front' => false),
			// 'has_archive'			=> true,
			// 'exclude_from_search' 	=> true,	// !!!!	може викликати проблеми з показом категорій				
			// 'hierarchical' 			=> false,	
			'menu_icon' 	=> get_template_directory_uri() . '/lib/wow_e_shop/files/icon_orders.png',
			// 'show_in_menu' 			=> 'edit.php?post_type=' . $post_type_7,
			// 'publicly_queryable' 	=> false, // НЕ показувати на сайті (тільки в адмінці)			
		)
);

}

add_action( 'init', 'wow_custom_types_in_2', 2 );



 add_filter('manage_wow_order_posts_columns', 'wow_order_columns_head', 0);  
 add_action('manage_wow_order_posts_custom_column', 'wow_order_columns_content', 10, 2);

function wow_order_columns_head($columns) { 
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'products' => __( 'Products' ),
		'grandtotal' => __( 'Grand total' ),
		'date' => __( 'Date' ),
		'order_status' => __( 'Status' )
	);    
	return $columns;  
}

function wow_order_columns_content($column_name, $post_ID) {
    if ($column_name == 'grandtotal') {
		$billing_arr = WOW_Checkout::order_billing_info($post_ID);
		echo $billing_arr['grand_total'];	
	}
	elseif ($column_name == 'products') {	
		$excerpt = get_the_excerpt($post_ID);
		if ( !empty($excerpt) ) { $excerpt_arr = unserialize($excerpt); }
		$products = $excerpt_arr['products'];
		$products_2 = array();
			foreach ($products as $id => $p_qty) :
		$products_2[] = '<span class="tit">'.get_the_title($id).' <span>('.$p_qty.')</span></span>';
			endforeach;
		$products_txt = implode(', ', $products_2);
		echo $products_txt;
	}
	elseif ($column_name == 'order_status') {
		$post2 = get_post($post_ID);  $order_status = $post2->pinged;		
		$status_arr = WOW_Checkout::order_status_array();
		echo '<div class="order_stat '.$order_status.'">'.$status_arr[$order_status].'</div>';		
	}
}  


add_action('add_meta_boxes', 'add_wow_order_meta_boxes'); // 'admin_init'
 
function add_wow_order_meta_boxes() {
add_meta_box('wow_order_status', __('Status'), 'wow_order_status_box', 'wow_order', 'side', 'high');
add_meta_box('wow_order_client', __('Customer'), 'wow_order_client_box', 'wow_order', 'side', 'high');
add_meta_box('wow_order_billing', __('Payment and Shipping'), 'wow_order_billing_box', 'wow_order', 'side', 'high');
add_meta_box('wow_order_products', __('Products'), 'wow_order_products_box', 'wow_order', 'normal', 'high');

// remove_meta_box('postcustom', 'order_site', 'normal');
}


 
function wow_order_status_box() {
		global $post;
		$status_arr = WOW_Checkout::order_status_array();
		if($post->pinged == '' or $post->pinged == 'pending') {
		wp_update_post( array('ID' => $post->ID, 'pinged' => 'processing') );
		}
		global $post;
		$order_status = $post->pinged;
		echo '<div class="order_stat '.$order_status.'">'.$status_arr[$order_status].'</div>';
		
		echo '<span>'.__('Change status').'</span> ';
		echo '<select name="pinged" id="pinged">';	
		foreach ($status_arr as $opt_key => $label) {
        	echo '<option value="'.$opt_key.'"'; if($opt_key == $order_status) { echo 'selected="selected"'; } echo '>'.$label.'</option>';
		} 
		echo '</select>';
}

function wow_order_client_box() {
		global $post;
		$output = '';
			$post2 = get_post($post->ID);  $user_id = $post2->post_author;			
			if($user_id and $user_id != 1) { 
			$user_login = get_the_author_meta('user_login', $user_id);
			$output .= '<span class="authore">'.__('Registered user').':  <span class="bolde">'.$user_login.'</span></span></br></br>'; 
			}
			else { $output .= '<span class="authore">'.__('Unregistered user').'</span></br></br>'; }
		$excerpt = get_the_excerpt($post->ID);
		if ( !empty($excerpt) ) {			
			$excerpt_arr = unserialize($excerpt);
			$customer_arr = $excerpt_arr['customer'];
		foreach ($customer_arr as $key_4 => $value) {
	  			$output .= $key_4.':  <span class="bolde">'.$value.'</span></br>';
  		}			
			
			echo $output;
		}
}

function wow_order_billing_box() {
		global $post;
		$output = '';
		$billing_arr = WOW_Checkout::order_billing_info($post->ID);
				
	$output .= __('Payment method').':  <span class="bolde">'.$billing_arr['pay_label'].'</span></br></br>';
	$output .= __('Shipping method').':  <span class="bolde">'.$billing_arr['shipp_label'].'</span></br></br>';
				
	$output .= '<span class="u_total">'.__('Subtotal').':  '.$billing_arr['cart_subtotal'].'</span></br>';
	$output .= '<span class="u_total">'.__('Shipping').':  '.$billing_arr['shipp_price'].'</span></br>';
$output .= '<span class="u_total grand">'.__('Grand total').':  '.$billing_arr['grand_total'].'</span></br>';
			
		echo $output;
}


function wow_order_products_box() {
		global $post;
		$output = '';
		$excerpt = get_the_excerpt($post->ID);		
		if ( !empty($excerpt) ) {			
			$excerpt_arr = unserialize($excerpt); // echo '<pre>'; print_r($excerpt_arr); echo '</pre>'; 
			
			$products = $excerpt_arr['products'];
			$output .= '<ul class="prod-list">';
		foreach ($products as $id => $p_qty) :
	  		$row_price_arr = WOW_Cart_Session::cart_get_row_price($id, $p_qty);		
			$sku = get_post_meta ($id, 'sku', true);
			$thumb = ''; if ( has_post_thumbnail($id) ) { $thumb = get_the_post_thumbnail($id, 'thumbnail'); }
		$output .= '<li>
		<div class="colu prod_id"><span>'.$id.'</span></div>
		<div class="colu prod_img"> <a title="'.get_the_title($id).'">'.$thumb.'</a> </div>
		<div class="colu prod_sku"><span>'.$sku.'</span></div>
		<div class="colu prod_name"> <h4><a href="'.get_permalink($id).'" target="_blank">'.get_the_title($id).'</a></h4></div> 		
		<div class="colu prod_price"><span class="price">'.$row_price_arr['item_price'].'</span></div>    
		<div class="colu prod_qty"><span>'.$p_qty.'</span></div>     
		<div class="colu prod_price tot"><span class="price">'.$row_price_arr['row_total'].'</span></div>
		</li>';
  		endforeach;
			$output .= '</ul>';
			
		echo $output;
		} // if ( !empty($excerpt) )
}




/* 
function set_html_content_type() { return 'text/html'; }
add_filter( 'wp_mail_content_type', 'set_html_content_type' );
/// remove_filter( 'wp_mail_content_type', 'set_html_content_type' ); // ??
 */






class LiqPay
{
    private $_api_url = 'https://www.liqpay.com/api/';
    private $_checkout_url = 'https://www.liqpay.com/api/checkout';
    protected $_supportedCurrencies = array('EUR','UAH','USD','RUB','RUR');
    private $_public_key;
    private $_private_key;
    /**
     * Constructor.
     *
     * @param string $public_key
     * @param string $private_key
     * 
     * @throws InvalidArgumentException
     */
    public function __construct($public_key, $private_key)
    {
        if (empty($public_key)) {
            throw new InvalidArgumentException('public_key is empty');
        }
        if (empty($private_key)) {
            throw new InvalidArgumentException('private_key is empty');
        }
        $this->_public_key = $public_key;
        $this->_private_key = $private_key;
    }
    /**
     * Call API
     *
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public function api($path, $params = array())
    {
        if(!isset($params['version'])){
            throw new InvalidArgumentException('version is null');
        }
        $url         = $this->_api_url . $path;
        $public_key  = $this->_public_key;
        $private_key = $this->_private_key;        
        $data        = base64_encode(json_encode(array_merge(compact('public_key'), $params)));
        $signature   = base64_encode(sha1($private_key.$data.$private_key, 1));
        $postfields  = http_build_query(array(
           'data'  => $data,
           'signature' => $signature
        ));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return json_decode($server_output);
    }
    /**
     * cnb_form
     *
     * @param array $params
     *
     * @return string
     * 
     * @throws InvalidArgumentException
     */
    public function cnb_form($params)
    {        
         $language = 'ru';
        if (isset($params['language']) && $params['language'] == 'en') {
            $language = 'en';
        }
        $params    = $this->cnb_params($params);
        $data      = base64_encode( json_encode($params) );
        $signature = $this->cnb_signature($params);
        
        return sprintf('
            <form method="POST" action="%s" accept-charset="utf-8">
                %s
                %s
     <input type="submit" name="btn_text" class="button pay_button" value="'.__('Go to payment').'" />
            </form>
            ',
            $this->_checkout_url,
            sprintf('<input type="hidden" name="%s" value="%s" />', 'data', $data),
            sprintf('<input type="hidden" name="%s" value="%s" />', 'signature', $signature),
            $language
        );
    }
    /**
     * cnb_signature
     *
     * @param array $params
     *
     * @return string
     */
    public function cnb_signature($params)
    {
        $params      = $this->cnb_params($params);
        $private_key = $this->_private_key;
        $json      = base64_encode( json_encode($params) );
        $signature = $this->str_to_sign($private_key . $json . $private_key);
        return $signature;
    }
    /**
     * cnb_params
     *
     * @param array $params
     *
     * @return array $params
     */
    private function cnb_params($params)
    {
        
        $params['public_key'] = $this->_public_key;
        if (!isset($params['version'])) {
            throw new InvalidArgumentException('version is null');
        }
        if (!isset($params['amount'])) {
            throw new InvalidArgumentException('amount is null');
        }
        if (!isset($params['currency'])) {
           throw new InvalidArgumentException('currency is null');
        }
        if (!in_array($params['currency'], $this->_supportedCurrencies)) {
            throw new InvalidArgumentException('currency is not supported');
        }
        if ($params['currency'] == 'RUR') {
            $params['currency'] = 'RUB';
        }
        if (!isset($params['description'])) {
            throw new InvalidArgumentException('description is null');
        }
        return $params;
    }
    /**
     * str_to_sign
     *
     * @param string $str
     *
     * @return string
     */
    public function str_to_sign($str)
    {
        $signature = base64_encode(sha1($str,1));
        return $signature;
    }

/* 
	$merchant_id = $pay_purse;
	$pay_html .= '<form id="pay_online_form" action="https://ecommerce.liqpay.com/ecommerce/CheckOutPagen" method="POST">';
    $pay_html .= '<div class="currency_w"><span>'.__('You must pay').': </span>'.$grand_total_7.' '.$symb.'</div>';
	$pay_html .= '<input type="hidden" name="version" value="1.0.0" />';
	$pay_html .= '<input type="hidden" name="merid" value="'.$merchant_id.'" />';
	$pay_html .= '<input type="hidden" name="acqid" value="'.$gran1111.'" />';
	
	$pay_html .= '<input type="hidden" name="amount" value="'.$grand_total_7.'"/>';
    // $pay_html .= '<input type="hidden" name="1111ccy" value="'.$order_currency.'" />';  // UAH
    
	$pay_html .= '<input type="hidden" name="orderid" value="'.$order_id.'" />';
	
    $pay_html .= '<input type="hidden" name="orderdescription" value="'.$payment_desc.'" />';
		
    $pay_html .= '<input type="hidden" name="merrespurl" value="'.$success_url.'">';
	$pay_html .= '<input type="hidden" name="merrespurl2" value="'.$success_url.'">';
    $pay_html .= '<input type="submit" class="button pay_button" value="'.__('Go to payment').'">';
    $pay_html .= '</form>';
*/

}




 
?>