<?php 


class WOW_Contact_Form {
	
function save_new_form_order() {
	if($_POST['customer_name']) : 
	$_POST = stripslashes_deep($_POST); /// /* !!!! */
	
		$customer = $_POST['customer_name'];
		$phone = $_POST['customer_phone'];
		$email = $_POST['customer_email'];
		$company = $_POST['customer_company'];
		
		$project_type = $_POST['project_type'];
		
		if(is_array($project_type)) { $project_type = implode(", ", $project_type); }
		
		$project_budget = $_POST['project_budget'];
	
		$subject = $_POST['subject'];
		$comme = $_POST['comment'];
		$ask_product = $_POST['product_id'];

if($subject) { $comme = '<p><strong>'.$subject.'</strong></p><p>'.$comme.'</p>'; }

if($project_type) { 
$post_title = __('Project planner').' - '.$project_type;
$comme = '<p>'.__('Type').': '.$project_type.'</p><p>'.__('Budget').': '.$project_budget.'</p><p>'.$comme.'</p>';
} 
elseif($ask_product) { 
$prod_title = get_the_title($ask_product);  $prod_sku = get_post_meta($ask_product, 'sku', true);
$post_title = __('Product details').' - '.$prod_title.' ('.$prod_sku.')';
$comme = '<p>'.__('Product').': '.$prod_title.'</p><p>'.__('Sku').': '.$prod_sku.'</p><p>'.$comme.'</p>';
}
elseif($comme) { $post_title = __('Contact form').' - '.$customer; }
else { $post_title = __('Call back').' - '.$customer.' - '.$phone;  $comme = $customer.' <br>'.$phone; }

		$post_arr_2 = array();
		$post_arr_2['customer_name'] = $customer;
		$post_arr_2['customer_phone'] = $phone;
		if($email) { $post_arr_2['customer_email'] = $email; }
		if($company) { $post_arr_2['customer_company'] = $company; }
		if($ask_product) { $post_arr_2['product_id'] = $ask_product; }

$post_arr_s = serialize($post_arr_2);

// $author = 1; if($_POST['p_author']) { $author = $_POST['p_author']; }
$new_post = array(
  'post_title'    => $post_title,
  // 'post_name'   => $post_name,
  'post_content'  => $comme,
  'post_excerpt'  => $post_arr_s,
//  'post_author'   => $author,
  'post_type'   => 'c_form_order', ///
  'post_status'   => 'publish',
  // 'comment_status'  => 'closed',
  'ping_status'   => 'closed',
);	
	
	$post_id_last = wp_insert_post( $new_post, $wp_error );
	


		if($_FILES) {
	require_once(ABSPATH . 'wp-admin/includes/image.php');	
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
foreach ($_FILES as $img_key => $up_img) : 
if($up_img['name']) {
$attach_id = media_handle_upload( $img_key, $post_id_last );
if ( !is_wp_error( $attach_id ) ) { /*  */ 
	$attach_title = get_the_title($attach_id);  $attach_link = get_post_field('guid', $attach_id);
	$text_link = '<p> <a href="'.$attach_link.'">'.$attach_title.'</a> </p>';
	$content_2 = $comme . $text_link;
	$new_post_2 = array('ID' => $post_id_last, 'post_content' => $content_2);
	wp_update_post( $new_post_2 );
}
}
endforeach; 
		} // if($_FILES) 



////////////////////// send email
  $email_message .= '<p>'.$post_title.'</p>';
  foreach ($post_arr_2 as $key_2 => $info_2) {
	  $email_message .= '<p>'.$key_2.':  <strong>'.$info_2.'</strong> </p>';
  }
  $email_message .= '</br> <div>'.__('Comment').': </br>'.$comme.'</div>';

// $to = get_settings('admin_email');
$to = get_bloginfo('admin_email');
$from = get_bloginfo('admin_email');
$email_subject = $post_title;
$headers[] = 'From: '.get_bloginfo('name').' <'.$from.'>';
/*  function set_html_content_type() { return 'text/html'; } // внизу 
add_filter( 'wp_mail_content_type', 'set_html_content_type' ); */	 
// @mail($to, $email_subject, $email_message, $headers);  /// стандартна ф-я PHP mail 
wp_mail ($to, $email_subject, $email_message, $headers); // відправити повідомлення на email 

if($email) {
	$to_client = $email;	
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

} // if($customer['email']) 

//////////////////// ____________send email


	return $post_arr_2;
	
	else : return false;	
	
	endif;  // ($_POST['customer_name'])

}


}


function wow_custom_types_in_3() { 
 
register_post_type( 'c_form_order',
		array(
			'labels' => array(
				'name' => __( 'Contact forms' ).', '.__( 'Call back' ),
				'menu_name' => __( 'Contact forms' ),
				'singular_name' => __( 'Contact form' ),
				// 'add_new' => __( 'Add New' ),
				// 'add_new_item' => __( 'Add New' ),			
				'edit_item' => __( 'Edit Contact form' ),
			),			
			'supports' => array( 'title', 'editor' ),
			'public' => true,
			'show_in_menu' 	=> true,
			'menu_position' 	=> 55,
			'show_in_nav_menus' 	=> false,
			'capabilities' => array( 'create_posts' => false ),
			'map_meta_cap' => true,	
			// 'taxonomies' 			=> array(),
			'rewrite' 			=> true,
			'menu_icon' 	=> get_template_directory_uri() . '/lib/wow_e_shop/files/icon_contacts.png',
			'publicly_queryable' 	=> false, // НЕ показувати на сайті (тільки в адмінці)			
		)
);

}

add_action( 'init', 'wow_custom_types_in_3', 2 );



add_action('add_meta_boxes', 'add_wow_form_order_meta_boxes'); // 'admin_init'
 
function add_wow_form_order_meta_boxes() {
add_meta_box('wow_form_order_client', __('Customer'), 'wow_form_order_client_box', 'c_form_order', 'side', 'high');
}

 
function wow_form_order_client_box() {
		global $post;
		$output = '';
	$excerpt = get_the_excerpt($post->ID);
		if ( !empty($excerpt) ) {			
			$excerpt_arr = unserialize($excerpt);		
		foreach ($excerpt_arr as $key_4 => $value) {
	  			$output .= $key_4.':  <span class="bolde">'.$value.'</span></br>';
  		}		
			
			echo $output;
		}
}




function set_html_content_type() { return 'text/html'; }
add_filter( 'wp_mail_content_type', 'set_html_content_type' );
/// remove_filter( 'wp_mail_content_type', 'set_html_content_type' ); // ??


 
?>