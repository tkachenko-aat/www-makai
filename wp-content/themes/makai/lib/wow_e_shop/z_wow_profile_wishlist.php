<?php 

class WOW_Profile {
	
function edit_profile() {
		if (is_user_logged_in()) : 
	$po_arr = array_keys($_POST); 
	if (in_array('customer', $po_arr)) : 	
	// $user_id = wp_update_user( array( 'ID' => $user_id, 'user_url' => $website ) );
	$par = '';
	$current_user = wp_get_current_user();  $user_id = get_current_user_id();
	$user_arr = array_merge(array('ID' => $user_id), $_POST['customer']);
	wp_update_user( $user_arr );
	
	if(!empty($_POST['pass1']) or !empty($_POST['pass2'])) {		
		if ( ($_POST['pass1'] == $_POST['pass2']) and (strlen($_POST['pass1']) >= 6) ) {
		wp_update_user( array('ID' => $user_id, 'user_pass' => $_POST['pass1']) );
		$par = '?pass=true';
		}
		else { $par = '?pass=false'; }
	}
	
$user_meta_5_arr = array('newsletter'); // поля checkbox 
foreach ($user_meta_5_arr as $m_key) { update_user_meta( $user_id, $m_key, '' ); }

	// update_user_meta( $user_id, $meta_key, $meta_value, $prev_value );
	foreach ($_POST['user_meta'] as $m_key => $m_value) {
	update_user_meta( $user_id, $m_key, $m_value );
	}

	// $url_2 = strtok($_SERVER["REQUEST_URI"], '?');
	global $post;
	$url_2 = get_permalink($post->ID);
	$page_url = $url_2.$par; // $page_url = site_url($url_2).$par;
	wp_safe_redirect( $page_url );

	endif;  // ($_POST['customer'])
		endif;
}



function public_edit_item() {
	if($_POST['title']) : 
$options_5 = get_option('wow_settings_arr');
	$act_currency_arr = WOW_Product_List_Func::get_act_currency();
	$currency = $act_currency_arr['code'];	
	$kurs = $act_currency_arr['rate']; 
	$symb = $act_currency_arr['symbol']; 

$post_title = $_POST['title'];
$post_content = $_POST['description'];

	$customer = $_POST['customer'];
		$post_arr_2 = array();
		$post_arr_2['customer'] = $customer;
		$post_arr_2['currency'] = $currency;
		$post_arr_2['kurs'] = $kurs;
// $post_arr_s = serialize($post_arr_2);

$p_type = 'post';
if($_POST['taxonomy']) {
	$taxo = $_POST['taxonomy'];
	$p_type = get_taxonomy($taxo)->object_type[0];
}

$author = 1; 
if($_POST['p_author']) { $author = $_POST['p_author']; }
$new_post = array(
  'post_title'    => $post_title,
  // 'post_name'   => $post_name,
  'post_content'  => $post_content,
  // 'post_excerpt'  => $post_arr_s,
  'post_author' => $author,
  // 'pinged'   => 'pending',
  'post_type'   => $p_type, ///
  // 'post_status'   => 'pending', // 'publish' 
  // 'comment_status'  => 'closed',
  'ping_status'   => 'closed',
);

if($_POST['ID']) { // ///////// edit post
	$new_post['ID'] = $_POST['ID'];  $post_id = $new_post['ID'];
	wp_update_post( $new_post );
$post_arr_25 = array_merge(array('ID' => $new_post['ID'], 'post_author' => $author, 'post_title' => $post_title), $post_arr_2); //
}

else { // ////// add new post
	$new_post['post_status'] = 'pending';
	$post_id_last = wp_insert_post( $new_post, $wp_error );  $post_id = $post_id_last;

$post_arr_25 = array_merge(array('new_id' => $post_id_last, 'post_author' => $author, 'post_title' => $post_title), $post_arr_2); //
}


		
if($_POST['atrib']) {
$atrib_53_arr = array('show_phone', 'show_email');
foreach ($atrib_53_arr as $a_key) { update_post_meta($post_id, $a_key, ''); }

		$atrib_arr = $_POST['atrib'];
		foreach ($atrib_arr as $a_key => $a_value) {
			$meta_key_1 = $a_key;
			// $values = explode( ",", $a_value );
		update_post_meta($post_id, $meta_key_1, $a_value);
		}
}


if($_POST['term_id']) {	
$taxo_arr = get_taxonomies(array('public' => true));  unset($taxo_arr['post_format'], $taxo_arr['post_tag']);
wp_delete_object_term_relationships( $post_id, $taxo_arr );

// $taxono_ids = explode(',', $_POST['term_id']);
$taxono_ids = array_map('intval', explode(',', $_POST['term_id']));
$taxonomy = $_POST['taxonomy'];
wp_set_object_terms( $post_id, $taxono_ids, $taxonomy );
}



if($_FILES) {
	require_once(ABSPATH . 'wp-admin/includes/image.php');	
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
/* 
echo '<pre>';
 print_r($_FILES); echo ' ---- ';
 print_r($_POST);
echo '</pre>';
 */
$my_post = get_post($post_id);  $excerpt2 = $my_post->post_excerpt;	
$ids_12 = explode('ids="', $excerpt2); 
if(count($ids_12) > 1) { $ids_2 = $ids_12[1]; $ids_3 = explode('"', $ids_2); $ids_4 = $ids_3[0]; 
$img_ids_2 = explode(',', $ids_4); }
// $img_ids_2 - масив існуючої галереї (excerpt)

$post_imgs_arr = array();

$img_numb = 0;
foreach ($_FILES as $img_key => $up_img) : 
if($up_img['name']) {
$attach_id = media_handle_upload( $img_key, $post_id );
if ( !is_wp_error( $attach_id ) ) {
	$post_imgs_arr[$img_numb] = $attach_id;
	if ($img_ids_2) { unset($img_ids_2[$img_numb]); } // видалити малюнки з існуючої галереї (excerpt)
}
}
$img_numb++;
endforeach; 

/* 
echo '<pre>';
print_r($img_ids_2); echo ' ---- ';
print_r($post_imgs_arr);
echo '</pre>';
 */
if(count($post_imgs_arr)) {
	if(!count($img_ids_2)) { $post_imgs_arr = array_values($post_imgs_arr); }
if($post_imgs_arr[0]) { set_post_thumbnail( $post_id, $post_imgs_arr[0] ); }

if(count($img_ids_2)) { 
$post_imgs_arr = $img_ids_2 + $post_imgs_arr; $post_imgs_arr = array_unique($post_imgs_arr);
ksort($post_imgs_arr); 
}

// echo '<pre>'; print_r($post_imgs_arr); echo '</pre>'; 

$img_ids = implode (',', $post_imgs_arr);
$post_curr = array( 'ID' => $post_id, 'post_excerpt' => '[gallery link="file" columns="4" ids="'.$img_ids.'"]' );
wp_update_post( $post_curr );
} /// if(count($post_imgs_arr))


}

	/// вивести масив з інф-ю про публікацію	
	return $post_arr_25;
	
	endif;  // ($_POST['title'])
}



function post_statuses_list() {
	$statuses_arr = array( 'publish' => __('Published'), 'future' => __('Future'), 'draft' => __('Draft'), 'pending' => __('Pending'), 'private' => __('Private'), 'trash' => __('Trash'), 'auto-draft' => __('Auto-Draft'), 'inherit' => __('Inherit') );
	return $statuses_arr;
}

	
}



class WOW_Wishlist {

function select_wishlist_id() {
	$id;
		if (is_user_logged_in()) : 
	global $wpdb;
	$current_user = wp_get_current_user(); $user_id = get_current_user_id(); 
	$wish_2 = $wpdb->get_results("SELECT * FROM " . WOW_TABLE_WISHLIST . " WHERE status = 'valid' AND user_id = '$user_id' ORDER BY position ASC", ARRAY_A);
	if(count($wish_2)) {
		$id = $wish_2[0]['id'];
	}
	else {
		$wpdb->insert(WOW_TABLE_WISHLIST, array('user_id' => $user_id, 'label' => 'list-'.$user_id));
		$last_id = $wpdb->insert_id;
		$id = $last_id;
	}
		endif;
	return $id;
}

function cur_wishlist_array($wishlist_id) {
	global $wpdb;	
	$wish_arr = array();
	$wish_26 = $wpdb->get_row("SELECT * FROM " . WOW_TABLE_WISHLIST . " WHERE id = '$wishlist_id' ", ARRAY_A);
		$prods = $wish_26['products'];
		if ($prods) { $wish_arr = explode('|', $prods); }
	return $wish_arr;
}

function wishlist_save_data($wish_arr, $wishlist_id) {
	global $wpdb;
		if(count($wish_arr) > 0) {	
			$wish_new_txt = implode('|', $wish_arr);
		} else { $wish_new_txt = ''; }
	$wpdb->update(WOW_TABLE_WISHLIST, array('products' => $wish_new_txt), array('id' => $wishlist_id));
}

function wishlist_add_product() {
	$po_arr = array_keys($_POST); 
	if (in_array('wish_prod_id', $po_arr)) : 		
		$prod_added = array($_POST['wish_prod_id']);
		$wishlist_id = WOW_Wishlist::select_wishlist_id();
		$wish_ex_arr = WOW_Wishlist::cur_wishlist_array($wishlist_id);		
		$wish_new_arr_2 = array_merge($prod_added, $wish_ex_arr); //
		$wish_new_arr = array_unique($wish_new_arr_2);
		WOW_Wishlist::wishlist_save_data($wish_new_arr, $wishlist_id);
	endif;  // ($_POST['wish_prod_id'])
}

function wishlist_remove() {
	$po_arr = array_keys($_POST); 
	if (in_array('wish_remove', $po_arr)) : 
		if($_POST['wish_remove'] == 'all') { $wish_new_arr = array(); }
		else { // ($_POST['wish_remove'] != 'all')
		$rem_id = $_POST['wish_remove'];
		$wishlist_id = WOW_Wishlist::select_wishlist_id();
		$wish_ex_arr = WOW_Wishlist::cur_wishlist_array($wishlist_id);
		$wish_new_arr = $wish_ex_arr;
		$w_keyse = array_flip($wish_ex_arr); $w_key_1 = $w_keyse[$rem_id];
		unset($wish_new_arr[$w_key_1]);	
		} // 
		WOW_Wishlist::wishlist_save_data($wish_new_arr, $wishlist_id);
	endif;  // ($_POST['comp_remove'])	
}


}


 
?>