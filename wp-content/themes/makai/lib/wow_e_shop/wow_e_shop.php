<?php 

// wow_e_shop.php

global $wpdb;
$wow_pref = 'wow__';
$pref_avalue = 'attribute_value_';

define('WOW_TABLE_ATTRIBUTE', $wpdb->prefix . $wow_pref . 'attribute');
define('WOW_TABLE_ATTR_OPTIONS', $wpdb->prefix . $wow_pref . $pref_avalue . 'options');
define('WOW_TABLE_ATTRIBUTE_SET', $wpdb->prefix . $wow_pref . 'attribute_set');
define('WOW_TABLE_ATTRIBUTE_SET_SECTION', $wpdb->prefix . $wow_pref . "attribute_set_section");
define('WOW_TABLE_ATTRIBUTE_SECTION_DET', $wpdb->prefix . $wow_pref . "attribute_set_section_details");
define('WOW_TABLE_WISHLIST', $wpdb->prefix . $wow_pref . 'wishlist');

define('WOW_ATTRIBUTE_LIST_URL', 'wow_attributes');
define('WOW_ATTRIBUTE_SET_LIST_URL', 'wow_attributes_set');
define('WOW_SETTINGS_URL', 'wow_settings');
// define('WOW_IMPORT_POSTS_URL', 'wow_import_posts');
define('WOW_TOOLS_URL', 'wow_tools');

 
 

add_action('admin_menu', 'create_wow_menu');

function create_wow_menu() {
	$attrib_display_func = 'wow_attributes_list_content';  $attrib_set_display_func = 'wow_attributes_set_content';
	if($_REQUEST['action'] == 'edit' or $_REQUEST['action'] == 'add') { $attrib_display_func = 'wow_attributes_edit_item_content'; $attrib_set_display_func = 'wow_attributes_set_edit'; } // action=edit
	
	add_menu_page(__('WOW-shop'), __('WOW-shop'), 'edit_pages', WOW_ATTRIBUTE_LIST_URL, $attrib_display_func, '', 56);
		add_submenu_page(WOW_ATTRIBUTE_LIST_URL, __('Attributes'), __('Attributes'), 'edit_pages', WOW_ATTRIBUTE_LIST_URL, $attrib_display_func); /// //
		add_submenu_page(WOW_ATTRIBUTE_LIST_URL, __('Attributes sets').' ('.__('Products groups').')', __('Attributes sets').' ('.__('Products groups').')', 'edit_pages', WOW_ATTRIBUTE_SET_LIST_URL, $attrib_set_display_func);
		add_submenu_page(WOW_ATTRIBUTE_LIST_URL, __('WOW settings'), __('WOW settings'), 'manage_options', WOW_SETTINGS_URL, 'wow_settings_page_f');
		add_submenu_page(WOW_ATTRIBUTE_LIST_URL, 'WOW '.__('Tools'), 'WOW '.__('Tools'), 'edit_pages', WOW_TOOLS_URL, 'wow_tools_page_f');
				
	add_menu_page(__('Per page. Sorting' ), __('Per page. Sorting'), 'manage_options', 'posts_per_page_in_cats', 'posts_per_page_in_cats_f', '', 57);

	add_admin_menu_separator(26); //// separator
}

function add_admin_menu_separator($position) {
	global $menu;
	$index = 0;
	foreach($menu as $offset => $section) {
		if (substr($section[2],0,9)=='separator')
		    $index++;
		if ($offset>=$position) {
			$menu[$position] = array('','read',"separator{$index}",'','wp-menu-separator');
			break;
	    }
	}
	ksort( $menu );
}


include 'z_attributes_list_content.php';
include 'z_attributes_set_content.php';
include 'z_wow_settings_page.php';
include 'z_wow_profile_wishlist.php';
include 'z_wow_tools.php'; // import


// Posts filter  // WOW_Product_List_Func  // Posts per page in categories
include 'z_wow_product_list.php';


$table_name1 = WOW_TABLE_ATTRIBUTE;
if($wpdb->get_var("show tables like '$table_name1'") != $table_name1) {
add_action('after_switch_theme', 'create_wow_attributes_tables');
add_action('after_switch_theme', 'create_wow_test_attributes');
add_action('after_switch_theme', 'create_wow_pages');
add_action('after_switch_theme', 'write_wow_settings');
add_action('after_switch_theme', 'insert_no_feat_image');
}

include 'z_create_tables.php';
include 'z_create_test_attributes.php'; 

function create_wow_pages() {
	$pages_arr = array( 
		'contacts' => 'template-wow_contacts.php', /// 
		'contact-form-success' => 'template-wow_form_success.php',
		'compare' => 'template-wow_compare.php',
		'advanced' => 'template-wow_advanced.php',
		'cart' => 'template-wow_cart.php',
		'checkout' => 'template-wow_checkout.php',
		'checkout-success' => 'template-wow_success.php',
		'checkout-payment' => '',
		'checkout-payment-failed' => '',
		'email-message' => '',
		// 'public-item' => 'template-wow_public_item.php',
		// 'public-company' => 'template-wow_public_item.php', // 
		'profile' => 'template-wow_profile.php',
		'wishlist' => 'template-wow_wishlist.php',
		'orders' => 'template-wow_orders.php',
		// 'messages' => 'template-wow_messages.php',
		// 'my-items' => 'template-wow_my_items.php',
		// 'my-companies' => 'template-wow_my_items.php' // 	
	); //

		if(!get_option('page_on_front')) {
	$new_page_home = array( 'post_title' => 'Home', 'post_name' => 'home', 'post_type' => 'page', 'menu_order' => 0, 'post_status' => 'publish');				
	$home_page_id = wp_insert_post( $new_page_home, $wp_error );
	update_option('show_on_front', 'page');  update_option('page_on_front', $home_page_id);
		} // if(!get_option('page_on_front')) 
	$menu_order = 200;
	foreach ($pages_arr as $name => $page_template) {
		$menu_order = $menu_order + 1;
		$parent = 0;  if($parent_profile) { $parent = $parent_profile; }
	$new_page = array( 'post_title' => $name, 'post_name' => $name, 'post_type' => 'page', 'post_parent' => $parent, 'menu_order' => $menu_order, 'post_status' => 'publish');				
		$page_id = wp_insert_post( $new_page, $wp_error );
		if($page_template) { add_post_meta($page_id, '_wp_page_template', $page_template); }
		if($name == 'profile') { $parent_profile = $page_id; }
	} // foreach ($pages_arr as $name => $page_template)

	$page_61 = get_page_by_path('checkout-success');
	wp_update_post( array('ID' => $page_61->ID, 'post_content' => 'The order was received.', 'post_excerpt' => 'Payment successful!') );	
	$page_62 = get_page_by_path('contact-form-success');
	wp_update_post( array('ID' => $page_62->ID, 'post_content' => 'Your application has been received.') );
}

function write_wow_settings() {
	$wow_settings_1 = array(
		'wow_currency' => array('avail' => array('USD' => 'USD', 'EUR' => 'EUR'), 'base' => 'USD', 'main' => 'USD', 'symbols' => 0, 'rates' => 0), 
		'wow_currency_precision' => 2,
		'wow_quick_order_mode' => '',
		'wow_to_cart_fly' => '',
		'wow_view_mode' => '',
		'wow_view_mode_one' => '',
		'wow_prod_count_list' => '',
		'wow_prod_count_grid' => '',
		'wow_base_sorting' => 0,
		'wow_product_popular_count_hits' => '',
		'wow_product_bestsel_count_hits' => '',
		'wow_gal_mode' => 0,
		'wow_cart_discount_perc' => '',
		'wow_min_cart_subtotal' => '',
		'wow_checkout_fields' => 0,
		'wow_payment_comment_1' => '',
		'wow_shipping_comment_1' => '',
		'wow_order_email' => '',
	);
	$options_pay_1 = array( 
		'pay_1' => array('label' => __('Cash'), 'code' => 'cash', 'status' => 1),
		'pay_2' => array('label' => __('Bank payment'), 'code' => 'bank', 'status' => 1),
		'pay_3' => array('label' => __('Webmoney'), 'code' => 'webmoney', 'status' => 9),
		'pay_4' => array('label' => __('Privat24'), 'code' => 'privat24', 'status' => 9),
		'pay_5' => array('label' => __('Liqpay'), 'code' => 'liqpay', 'status' => 9),
		'pay_6' => array('label' => __('Paypal'), 'code' => 'paypal', 'status' => 9),			
	);
	$options_shipp_1 = array( 
		'shipp_1' => array('label' => __('Courier'), 'code' => 'courier', 'price' => 10, 'status' => 1),
		'shipp_2' => array('label' => __('Pickup from the warehouse'), 'code' => 'pickup', 'price' => 0, 'status' => 1),		
	);
	$options_posts_page = array( 
		'category' => array('orderby' => 'date', 'order' => 'desc'),
		'prod-cat' => array('orderby' => 'views', 'order' => 'desc', 'base_sorting' => array('title' => 'title', 'views' => 'views')),	
	);	
update_option('wow_settings_arr', $wow_settings_1);
update_option('wow_payment_methods', $options_pay_1);
update_option('wow_shipping_methods', $options_shipp_1);
update_option('posts_pp_arr', $options_posts_page);
}

function insert_no_feat_image() {
global $wpdb;
$img_name = 'no_feat_image.png';
$file_url = get_template_directory_uri().'/images/'.$img_name;
$file_path = TEMPLATEPATH.'/images/'.$img_name;
if (is_file($file_path)) {
$image4 = media_sideload_image($file_url, 0);
$last_at = $wpdb->get_row( "SELECT MAX(ID) FROM $wpdb->posts", ARRAY_A );  $lastid = $last_at['MAX(ID)'];
	$options_media_1 = array( 'no_feat_image_id' => $lastid );
	update_option('site_media_settings_4', $options_media_1);
}
}



/* Адмінка. Товар. Список атрибутів та інші блоки */
include 'z_post_attributes_list.php';


add_action('admin_init', 'add_wow_css');
function add_wow_css() {
wp_enqueue_style('wow_attrib_css', get_template_directory_uri().'/lib/wow_e_shop/css/wow_e_shop.css', array(), NULL);
}

add_action( 'wp_enqueue_scripts', 'add_wow_js' );
// if ( !is_admin() ) {  }
function add_wow_js() {
//// підкючити скрипти із теми у файлі functions.php ////
// prototype.js
// jquery
// j_carousel
// jquery.cycle 
// jquery-ui-slider 
wp_register_script( 'wow_filter_js', get_template_directory_uri().'/lib/wow_e_shop/js/posts_filter.js', array(), '1.0', true );
wp_enqueue_script( 'wow_filter_js' );
// if ( is_admin() ) {}
}



function add_count_views_0() { // admin, додати к-сть переглядів = 0, якщо поле ще не існує
	global $pagenow;
	if( in_array($pagenow, array('post.php')) ) { 
		global $post;
		add_post_meta($post->ID, 'views', 0, true);
	}
}
add_action('admin_head', 'add_count_views_0');

function count_views() { // К-сть переглядів. !!! вставити раніше, ніж function wow_add_product_viewed()
	if(is_single()) {
        global $post;
      $viewed_arr = WOW_Viewed_Session::viewed_array('');
	  if(!in_array($post->ID, $viewed_arr)) { 
	  $count = get_post_meta($post->ID, 'views', true);
      $count = $count + 1;
      update_post_meta($post->ID, 'views', $count);
	  } // if(!in_array($post->ID, $viewed_arr))
   } 
}
add_action('wp_head', 'count_views');



if ( ! class_exists('Recursive_ArrayAccess') ) { require_once( 'session/class-recursive-arrayaccess.php' ); }
if ( ! class_exists('WP_Session') ) { require_once( 'session/class-wp-session.php' ); }

include 'z_wow_session.php';

function wow_session() {
 global $wp_session;
 $wp_session = WP_Session::get_instance();
}
add_action( 'init', 'wow_session' ); /* !!!! // вилучити, якщо не потрібно WP_Session */ 

function wow_add_product_viewed() {
	if ( is_single() ) { 
	WOW_Viewed_Session::viewed_add_product();
	}
}
add_action( 'wp_head', 'wow_add_product_viewed' );


function set_prod_rating() { // 
	if($_POST) : 
if($_POST['rating_prod_id']) : 
$prod_id = $_POST['rating_prod_id'];
$rat_value = $_POST['rating_value'];
	$rating_total = get_post_meta($prod_id, 'rating_total', true);
    $rating_total = $rating_total + $rat_value;
    update_post_meta($prod_id, 'rating_total', $rating_total); /// /////
	$rating_count = get_post_meta($prod_id, 'rating_count', true);
    $rating_count = $rating_count + 1;
    update_post_meta($prod_id, 'rating_count', $rating_count);	
	
	$rating = round(($rating_total / $rating_count), 2);
	update_post_meta($prod_id, 'rating', $rating);
	
WOW_Rating_Session::rating_add_product($prod_id);
endif;
	endif;
}
add_action('wp_head', 'set_prod_rating');


function session_clear_data_4() { // Зачистка сесії при виході 
 global $wp_session;
 $wp_session = WP_Session::get_instance();
    $arr_2 = array(); 
	WOW_Cart_Session::cart_save_data($arr_2);
	WOW_Viewed_Session::viewed_save_data($arr_2);
	WOW_Compare_Session::compare_save_data($arr_2);
	// WOW_Product_List_Session::product_list_save_data($arr_2);
}
add_action('wp_logout', 'session_clear_data_4');



/* include 'z_wow_checkout_order.php'; /* !!!! // вилучити, якщо не потрібні замовлення */

include 'z_wow_form_order.php';





/* *** Використовувати Excerpt для галереї фото. Включити віз. редактор ***  */ 
add_action('add_meta_boxes', 'add_excerpt_editor_meta_boxes');

function add_excerpt_editor_meta_boxes() {
	global $post_type;
	$box_title = __('Excerpt');
	if(!in_array($post_type, array('page'))) { $box_title = __('Media gallery (excerpt)'); }
	if (isset($post_type) && post_type_supports($post_type, 'excerpt')) { 
	remove_meta_box('postexcerpt', $post_type, 'normal');
	add_meta_box('postexcerpt', $box_title, 'wow_editor_post_excerpt_box', $post_type, 'normal', 'high');
	}
}

function wow_editor_post_excerpt_box($post) {
	global $post_type;
		if(!in_array($post_type, array('page'))) { 
	echo '<p class="media_6"><em>'; _e('<b>To begin, the Featured Image must be set.</b></br> Add only Media gallery into this field.</br> Do not include Featured Image in this gallery!'); echo '</em></p>';
		}
	$settings = array( 'textarea_rows' => '12', 'quicktags' => false, 'tinymce' => true);
	wp_editor(html_entity_decode(stripcslashes($post->post_excerpt)), 'excerpt', $settings);	
}

add_filter('the_excerpt', 'do_shortcode');
/* ?? крім того, у плагіні simple-lightbox знайти "add_filter('the_content'..." і додати відповідні рядки для ф-ї the_excerpt */



function wow_custom_types_in() {

global $wpdb;
$atr_sets_2 = $wpdb->get_results( "SELECT * FROM " . WOW_TABLE_ATTRIBUTE_SET . " WHERE status = 'valid' ORDER BY position ASC", ARRAY_A );

$menu_position = 27;  if(count($atr_sets_2) > 26) { $menu_position = 257; }
/* !!!! General Categories */ $prod_cat = 0; // 0 ; 1 - with General Categories 
	
foreach ($atr_sets_2 as $set_id => $atr_set ) : 
$set_p_type = $atr_set['set_post_type'];

if($set_p_type and !preg_match("/[^0-9a-z-_]/", $set_p_type) and !preg_match("/[^a-z]/", $set_p_type[0])) : /////

$a_menu_name = mb_substr(strip_tags($atr_set['name']), 0, 14, 'utf-8');
$tax_arr = array($set_p_type.'-cat'); if($prod_cat == 1) { $tax_arr = array($set_p_type.'-cat', 'prod-cat'); }
$menu_icon = 'icon_product.png';  if($atr_set['is_no_product'] == 1) { $menu_icon = 'icon_kil.png'; }
register_post_type( $set_p_type,
		array (
			'labels' => array( 'name' => $atr_set['name'], 'menu_name' => $a_menu_name, 'singular_name' => $set_p_type, 'add_new' => __( 'Add New' ), 'add_new_item' => __( 'Add New' ).' '.$atr_set['name'], 'edit_item' => __( 'Edit' ).' '.$atr_set['name'], ),		
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments' ),
			'public' => true,
			'show_in_menu' 	=> true,
			'menu_position' 	=> $menu_position,
			'show_in_nav_menus'  => false,		
			// 'hierarchical' => true,
			'taxonomies' 		=> $tax_arr, // 
			// 'rewrite' => array('slug' => 'producti', 'with_front' => false),
			'rewrite' 	  => true,
			'menu_icon' 	=> get_template_directory_uri().'/lib/wow_e_shop/files/'.$menu_icon,		
		)
);

register_taxonomy($set_p_type.'-cat', array($set_p_type), array (
			'labels' => array( 'name' => $atr_set['name'].' - '.__( 'Categories' ), 'menu_name' => __( 'Categories' ), 'singular_name' => __( 'Category' ).' - '.$atr_set['name'], 'edit_item' => __( 'Edit Category' ).' - '.$atr_set['name'], ),					
			'public' => true,	
			'hierarchical' => true,		
			'show_in_nav_menus' => true,
			'rewrite' => array('slug' => $set_p_type.'s', 'with_front' => false, 'hierarchical' => true),	
			// 'rewrite' 	=> true,
));

endif;
endforeach;

	if($prod_cat == 1) {  
$prod_types = WOW_Attributes_Front::get_attrSet_posttypes();
register_taxonomy('prod-cat', $prod_types, array (
			'labels' => array( 'name' => __( 'General Categories' ), 'menu_name' => __( 'General Categories' ), 'singular_name' => __( 'Category' ), 'edit_item' => __( 'Edit Category' ), ),					
			'public' => true,	
			'hierarchical' => true,		
			'show_in_nav_menus' => true,
			'rewrite' => array('slug' => 'prods', 'with_front' => false, 'hierarchical' => true),
			// 'rewrite' 	=> true,
));
	} // if($prod_cat == 1)
}

add_action( 'init', 'wow_custom_types_in', 2 );



function add_admin_gut_columns() {
$p_types = WOW_Attributes_Front::get_attrSet_posttypes();
/// add_filter('manage_product_posts_columns', 'product_columns_head', 0);
foreach ($p_types as $p_type ) :  
 add_filter('manage_'.$p_type.'_posts_columns', 'post_columns_head', 0);  
 add_action('manage_'.$p_type.'_posts_custom_column', 'post_columns_content', 10, 2);
endforeach;

$p_types_2 = WOW_Attributes_Front::get_attrSet_posttypes_no_prod();
foreach ($p_types_2 as $p_type ) :  
 add_filter('manage_'.$p_type.'_posts_columns', 'post_columns_head_2', 0);  
 add_action('manage_'.$p_type.'_posts_custom_column', 'post_columns_content', 10, 2);
endforeach;
}
add_action('admin_init', 'add_admin_gut_columns');


function post_columns_head($columns) { 
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'id' => __( 'ID' ),
		'sku' => __( 'Sku' ),
		'thumbnail' => __( 'Thumbnail' ),
		'title' => __( 'Title' ),
		'product_type' => __( 'Product type' ),
		'cats' => __( 'Categories' ),
		'price' => __( 'Price' ),
		// 'comments' => __( 'Comments' ),
		'date' => __( 'Date' )
	);    
	return $columns;  
}  
function post_columns_head_2($columns) { 
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'id' => __( 'ID' ),
		// 'sku' => __( 'Sku' ),
		'thumbnail' => __( 'Thumbnail' ),
		'title' => __( 'Title' ),
		// 'product_type' => __( 'Product type' ),
		'cats' => __( 'Categories' ),
		// 'price' => __( 'Price' ),
		// 'comments' => __( 'Comments' ),
		'date' => __( 'Date' )
	);    
	return $columns;  
}  

function post_columns_content($column_name, $post_ID) {  
	if (function_exists('qtrans_getSortedLanguages')) { global $q_config; } // -- Переклад	
$options_5 = get_option('wow_settings_arr');  $opt_currency = $options_5['wow_currency'];
$base_currency = $opt_currency['base'];
$b_symb = $base_currency;  
// if($opt_currency['symbols'][$base_currency]) { $b_symb = $opt_currency['symbols'][$base_currency]; }
    if ($column_name == 'id') {
		echo $post_ID;		
	}
	if ($column_name == 'sku') {
		$sku = get_post_meta($post_ID, 'sku', true); 
		echo $sku;		
	}
	if ($column_name == 'product_type') {
		$product_type = get_post_meta($post_ID, 'product_type', true);	
		echo '<div class="produ_'.$product_type.'">'.$product_type.'</div>';	
	}	
	if ($column_name == 'price') {
		$price = get_post_meta($post_ID, 'price', true);
	  if ($price) { echo '<span class="price">'.$price.'<span class="symb"> '.$b_symb.'</span></span>'; }
	}
	if ($column_name == 'thumbnail') {
		if ( has_post_thumbnail($post_ID)) { echo get_the_post_thumbnail($post_ID, 'thumbnail'); }
	}
	if ($column_name == 'cats') { 	
		$post_type = get_post_type($post_ID);
		$taxonomy = get_post_type_object($post_type)->taxonomies[0];
		$categorys_4 = wp_get_object_terms($post_ID, $taxonomy);
		$categorys_5 = array();
		foreach ($categorys_4 as $cat)  {	
			$cat_name = $cat->name;	
			if (function_exists('qtrans_getSortedLanguages')) { // Переклад	
			$cat_name = qtrans_use($q_config['language'], $cat_name, true);
			} // -- Переклад 
			$categorys_5[] = $cat_name;
		}
		$categorys = implode (", ", $categorys_5); 
		echo $categorys;
    } 
}  





include 'z_wow_front_attributes.php';


include 'z_wow_cat_images.php'; // малюнки і вигляд (товари або підкатегорії) категорій (таксон. одиниць)



?>