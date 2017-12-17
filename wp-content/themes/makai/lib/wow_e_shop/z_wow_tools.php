<?php 
function wow_tools_page_f() {	

	set_time_limit(6000);
	
/* при імпорті не створювати деякі розміри зображень */
function salas_remove_image_sizes_2($sizes) {
    unset($sizes['medium']);  /// 'medium' 
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'salas_remove_image_sizes_2');

// print_r($_FILES);
if($_FILES) {
	/*
	require_once(ABSPATH . 'wp-admin/includes/image.php');	
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	 */
	global $wpdb;	
	
	$post_type = 'post';
	if($_POST['import_post_type']) { $post_type = $_POST['import_post_type']; }

		$file_uri_5 = $_FILES['posts_table']['tmp_name'];
		if ( is_file( $file_uri_5 ) ) {
			$csv_file_data = file($file_uri_5, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			$db_field_definition = explode( "^", $csv_file_data[0] );		
			unset($csv_file_data[0]);		

				foreach ( $csv_file_data as $line_index => $line_content ) {
					$data_definition = explode( "^", $line_content );					
						
						$data_def = array();						
						foreach ( $db_field_definition as $column_index => $column_name ) {
							$column_name = trim($column_name);
							if ( !empty($column_name) ) {
						$data_def[$column_name] = $data_definition[$column_index];
							}							
						} // foreach ( $db_field_definition as $column_index => $column_name )							
					
if($data_def['post_title'] or $data_def['sku']) : // start creating post
	$new_post = array(
  'post_title'    => $data_def['post_title'],
  'post_name'   => $data_def['post_name'],
  'post_content'  => $data_def['post_content'],
  'post_parent'  => $data_def['post_parent'], ////// //////
  'menu_order'  => $data_def['menu_order'], ////// ////// 
  // 'post_excerpt'  => $data_def['post_excerpt'],  
  // 'post_category' => explode( ",", $data_def['post_category'] ), // array(12,15)
  // 'post_author'   => 1,
  'post_type'   => $post_type,
  'post_status'   => 'publish',
  // 'comment_status'  => 'closed',
  'ping_status'   => 'closed',
);

if($data_def['sku']) {
	$sku = $data_def['sku'];
	$post_meta_arr_7 = $wpdb->get_row( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'sku' AND meta_value = '$sku'", ARRAY_A );
	$post_id_2 = $post_meta_arr_7['post_id']; 
	if($post_id_2 and (get_post_type($post_id_2) == $post_type)) { $my_post_id = $post_id_2; }
}

if($my_post_id) { // ///////// edit post
	$new_post['ID'] = $my_post_id;  $post_id = $my_post_id;
	$post_pars_6 = array('post_title', 'post_name', 'post_content', 'post_parent', 'menu_order');
	foreach ($post_pars_6 as $par_6) { if(!$new_post[$par_6]) { unset( $new_post[$par_6] ); } }
	unset( $new_post['post_status'] );
	wp_update_post( $new_post );
}

else { // ////// add new post
	$post_id_last = wp_insert_post( $new_post, $wp_error );  $post_id = $post_id_last;
				if($wp_error) { $errors_9[] = $wp_error; }
}
		
		if($data_def['post_category']) { // включити матеріал в категорії (таксон. одиниці)
		$taxono_ids = explode( ",", $data_def['post_category'] );
		$taxonomy_names = get_object_taxonomies( $post_type );
		$taxonomy = $taxonomy_names[0];
		wp_set_object_terms($post_id, $taxono_ids, $taxonomy); // wp_set_object_terms( $post_id, $category_ids, 'category');
		}
		
		/*  */ echo '<pre>'; echo get_the_title($post_id); echo '</pre>';
		
		$atrib_arr = $data_def;  unset( $atrib_arr['post_title'], $atrib_arr['post_name'], $atrib_arr['post_content'], $atrib_arr['post_excerpt'], $atrib_arr['post_category'], $atrib_arr['image_gallery'] );
				
		foreach ($atrib_arr as $a_key => $a_value) {
			$meta_key_1 = $a_key;
			if($my_post_id) { // // edit post
				// update_post_meta($post_id, $meta_key_1, '');
				update_post_meta($post_id, $meta_key_1, $a_value);
			} else { // // add new post
			$values = explode( ",", $a_value );
			foreach ($values as $value_1) { add_post_meta($post_id, $meta_key_1, $value_1); }
			}
		} // foreach 
		
if($data_def['image_gallery']) : // 'post_thumbnail'
$image_gal_4 = preg_replace('/[^0-9a-zA-Z.,-_]*/', '', $data_def['image_gallery']);
if($image_gal_4) { $image_gal_arr = explode(',', $image_gal_4); $image_gal_arr = array_unique($image_gal_arr); }

if(count($image_gal_arr) and !has_post_thumbnail($post_id)) : 
$post_imgs_arr = array();
foreach ($image_gal_arr as $image_1) {

$filename = $image_1;
$wp_upload_dir = wp_upload_dir();
$file_url = $wp_upload_dir['baseurl'].'/import/'.$filename;
$file_path = $wp_upload_dir['basedir'].'/import/'.$filename;
if (is_file($file_path)) {
/*	
$wp_filetype = wp_check_filetype(basename($filename), null );
$attachment = array(
    'guid' => $wp_upload_dir['baseurl'] . '/' . basename($filename),
    'post_mime_type' => $wp_filetype['type'],
    'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
    'post_content' => '',
	'post_status' => 'inherit'
); 
$attach_id = wp_insert_attachment( $attachment, $file_path, $post_id );
$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
wp_update_attachment_metadata( $attach_id, $attach_data );
set_post_thumbnail( $post_id, $attach_id );
 */
$image4 = media_sideload_image($file_url, $post_id);
$last_at = $wpdb->get_row( "SELECT MAX(ID) FROM $wpdb->posts", ARRAY_A );  $lastid = $last_at['MAX(ID)'];
$post_imgs_arr[] = $lastid;

echo 'insert image ...';

} // (is_file($file_path))

} // foreach ($image_gal_arr as $image_1) 

if(count($post_imgs_arr)) { set_post_thumbnail( $post_id, $post_imgs_arr[0] ); } // ////// Featured image 

if(count($post_imgs_arr) > 1) {
$img_ids = implode (',', $post_imgs_arr);
$post_last = array( 'ID' => $post_id, 'post_excerpt' => '[gallery link="file" columns="4" ids="'.$img_ids.'"]' );
wp_update_post( $post_last );
}

endif; // if(count($image_gal_arr))

endif; // if($data_def['image_gallery']) ////////// ///////


else: $errors_9[] = 1;
endif; // if($data_def['post_title'] or $data_def['sku'])								
				} // foreach ( $csv_file_data as $line_index => $line_content )
						
		
		/*  */ // echo '<pre>'; print_r($errors_9); echo '</pre>';
		// echo __('Count of imported posts:').' '.count($csv_file_data);
		
		
		}  // if ( is_file( $file_uri_5 ) )	

	// print_r($errors_9);
	if($errors_9) { $import_rez = 'fail_import'; } else { $import_rez = 'import'; }	
	$page_url = '?page='.$_REQUEST['page'].'&action='.$import_rez.'&total='.count($csv_file_data);
	echo '<script type="text/javascript">window.location.href = "'.$page_url.'";</script>';
	
} // if($_FILES)
?>
<div class="wrap">  
              
        <div class="icon-atrib import"> </div>
        <?php  ?>
        <h2><?php echo __('Import posts'); echo ' ('.__('.csv file').')'; ?></h2>  

<script type="text/javascript">
function import_forma_check() {
	var form_imp = document.forms.import_posts;
	var file_el = form_imp.posts_table;
	var file_name = file_el.value;
	
	name_24 = file_name.split('.');   ext = name_24[name_24.length-1];
	
	if ( (file_name.length < 3 ) )  {
    alert(" File name length must be at least 3! " );
    return false;
	}
	
	if ( ext != 'csv' )  {
    alert(" Only .csv files! " );
    return false;
	}
	
// var erore = 0; var eror_text = '';
}
</script>
<form name="import_posts" id="import_postsooo" method="post" action="#go" enctype="multipart/form-data" onsubmit="return import_forma_check()" >

<div class="field_2 importi"> <label for="import_post_type"><?php echo __('Post type'); ?></label>
<?php  
$args4 = array(
   'public'   => true,
   '_builtin' => false // If true, will return WordPress default post types. Use false to return only custom post types
   );
$types_arr = get_post_types($args4);  unset($types_arr['wow_order'], $types_arr['c_form_order']);
$types_arr['post'] = 'post';
?>
<?php if (count($types_arr)) { ?>
<select name="import_post_type" id="import_post_type" >			
		<?php foreach ($types_arr as $type_key => $type4) : ?>
        	<option value="<?php echo $type_key ?>"><?php echo $type4 ?></option>
		<?php endforeach; ?>        	       
</select>
<?php } ?> 
</div>

<input type="file" name="posts_table" id="posts_table" value="" />

<input type="submit" class="button button-primary button-large" id="import_go" accesskey="p" value="<?php _e('Import!') ?>" />

<span class="right_desc"><?php _e('To test the import process, first upload a small number of products (10 products).') ?><br /><?php _e('If the products contain images, import can take a long time (more than 10 minutes).') ?></span>
</form>

<?php if($_REQUEST['action'] == 'import') { ?>
<div class="import_rez"> <?php echo __('The operation was completed successfully'); ?></br> <?php echo __('Count of imported posts:').' '.$_REQUEST['total']; ?> </div>
<?php } elseif($_REQUEST['action'] == 'fail_import') { ?>
<div class="import_rez fail"> <?php echo __('The operation failed'); ?> </div>
<?php } ?>

<div class="note">
</br></br>
<strong><a href="<?php echo get_template_directory_uri().'/lib/wow_e_shop/files/products_table_import.xls'; ?>">Зразок таблиці для імпорту товарів</a></strong>. </br></br>
Основним ідентифікатором товару є параметр "sku" - код товару (1-а колонка). Цей параметр може бути не обовязковим. Але для можливості масового оновлення інформації про товари (наприклад, оновлення цін) шляхом імпорту параметр "sku" є обовязковим. </br>
Щоб включити в таблицю новий атрибут товару, додайте нове поле з назвою, такою ж, як код цього атрибуту. Код атрибуту можна переглянути в адмінці у списку атрибутів. </br></br>

Якщо потрібен не новий імпорт, а лише оновлення інформації про існуючі товари, то у таблиці слід залишити тільки поле "sku" і поля з тими атрибутами, які необхідно оновити. </br></br>

Як відомо, конфігурований товар – це об’єднання простих товарів, а не товар, що реально існує. Імпорт конфігурованих товарів має свої особливості і проходить у 2 етапи. </br>
1-й етап: імпорт таблиці зі списком самих конфігурованих товарів (ці товари не існують реально); у полі "product_type" необхідно написати "configurable", "post_parent" - не заповнювати. Після успішного імпорту на цьому етапі в адмінці можна переглянути ID всіх товарів. </br>
2-й етап: імпорт таблиці зі списком простих товарів, які будуть включені у конфігуровані товари; поле "product_type" можна не заповнювати, у полі "post_parent" необхідно написати ID відповідного конфігурованого товару. </br></br>

Поле для назв малюнків: "image_gallery" (1_1.jpg, 1_2.jpg, 1_3.jpg). Перший у списку малюнок стає головним зображенням. </br>
Папка для малюнків: /wp-content/uploads/import . </br></br>

Використовувати .csv файли (вміст .xls файлу можна скопіювати в .csv). Доцільно редагувати їх в програмі OpenOffice. </br>
Кодування - UTF-8. Як розділювач використовувати символ "^". 2-й розділювач - порожній (без жодних символів). </br> 
Щоб зберегти файл, вибирайте команду "Зберегти як", і в діалоговому вікні поставте галочку "Змінити налаштування фільтра". </br></br>
</div>



</br></br>
<h2><?php _e('Clear the cache of database'); ?></h2>  

<form name="clear_db" method="post" action="#clear" >
<div class="field_2 cleare"> 
<input type="submit" name="clear_database" class="button button-primary button-large" accesskey="p" value="<?php _e('Clear cache!') ?>" />
</div>
</form>

<?php 
if($_POST['clear_database']) { 
	wow_clear_database_cache();
}

?>



</div>
<?php 
} // function wow_tools_page_f() 




function wow_clear_database_cache() {
/* ******* Зачистка session в БД (таблиця wp_options)  ******* */
$min_counte = 300; // мін. к-сть рядків _wp_session_ у таблиці для виконання зачистки
if (is_admin()) { $min_counte = 20; }
$recent_counte = 20; // к-сть останніх рядків, які слід залишити 
global $wpdb;
$options_arr5 = $wpdb->get_results( "SELECT option_id FROM $wpdb->options WHERE $wpdb->options.option_name LIKE ('_wp_session_%') ORDER BY $wpdb->options.option_id DESC" , ARRAY_A );
if(count($options_arr5) > $min_counte) {
	$arr5_4 = array_slice($options_arr5, 0, $recent_counte);
	$opt_ids_arr7 = array();
	foreach ($arr5_4 as $key_2 => $value) { $opt_ids_arr7[] = $value['option_id']; }
	$recent_opt_ids = "(".implode(", ", $opt_ids_arr7).")";
	$query7 = "DELETE FROM $wpdb->options WHERE $wpdb->options.option_name LIKE ('_wp_session_%') AND $wpdb->options.option_id NOT IN $recent_opt_ids";
	$wpdb->query($query7);
	$query8 = "DELETE FROM $wpdb->options WHERE $wpdb->options.option_name LIKE ('%transient_%')";
	$wpdb->query($query8);
}
/* *******  ******* */
}

?>