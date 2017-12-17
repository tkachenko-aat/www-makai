<?php  
/* 
// додати поля до таблиці term_taxonomy 
$wpdb->query("ALTER TABLE $wpdb->term_taxonomy ADD `term_thumbnail` varchar(32) default '' AFTER `count`");
$wpdb->query("ALTER TABLE $wpdb->term_taxonomy ADD `term_view` varchar(32) default '' AFTER `count`");
$wpdb->query("ALTER TABLE $wpdb->term_taxonomy ADD `term_order` bigint(20) default 11 AFTER `count`");
 */

/* 
add_filter( 'get_terms_args', 'my_sort_terms', 10, 2 );
function my_sort_terms( $args, $taxonomies ) {
    $args['orderby'] = 'count'; // 'count', 'slug'
	$args['order'] = 'DESC';
    return $args;
}
*/
// !!!! сортувати категорії (такс. од.) по створеному полю "term_order" 
add_filter('get_terms_orderby', 'sort_terms_gut', 10, 3);
function sort_terms_gut( $orderby, $args, $taxonomies ){
	return 'tt.term_order+0';
}


function admin_taxonomies_view_8() {
	
$taxo_arr = get_taxonomies(array('public' => true));
unset($taxo_arr['post_format']);

foreach ($taxo_arr as $taxo ) :
add_filter('manage_edit-' . $taxo . '_columns', array('WOW_categories_Func', 'category_manage_columns'));
add_filter('manage_' . $taxo . '_custom_column', array('WOW_categories_Func', 'category_manage_columns_content'), 10, 3);
add_action($taxo . '_edit_form_fields', array('WOW_categories_Func', 'category_edit_fields'));
// add_action($taxo . '_add_form_fields', array('WOW_categories_Func', 'category_edit_fields'));
add_action($taxo . '_edit_form_fields', array('WOW_categories_Func', 'category_valid_slug'));
add_action($taxo . '_add_form_fields', array('WOW_categories_Func', 'category_valid_slug'));
add_action('created_' . $taxo, array('WOW_categories_Func', 'category_fields_saver'), 10, 2);
add_action('edited_' . $taxo, array('WOW_categories_Func', 'category_fields_saver'), 10, 2);
endforeach;
}

add_action( 'init', 'admin_taxonomies_view_8' );



class WOW_categories_Func {

function category_fields_saver($term_id){
		global $wpdb;
		$tax = get_term_by('id', $term_id, $_REQUEST['taxonomy']);
			$tax_id = $tax->term_taxonomy_id;
		$upd_arr = array();
		if ( $_POST['category-thumbnail'] ) {			
			$attach_id = intval( $_POST['category-thumbnail'] ); 
			if ( $_POST['category-thumbnail'] == -1 ) { $attach_id = ''; }
			$upd_arr['term_thumbnail'] = $attach_id; 
		}
		if ( $_POST['term_view'] ) { 
			$term_view = $_POST['term_view'];
			$upd_arr['term_view'] = $term_view;			
		} ///
		if ( $_POST['term_order'] ) { 
			$term_order = $_POST['term_order'];
			$upd_arr['term_order'] = $term_order;	
		}
		//	$upd_arr = array('term_thumbnail' => $attach_id, 'term_view' => $term_view);			
			$wpdb->update($wpdb->term_taxonomy, $upd_arr, array('term_taxonomy_id' => $tax_id));			

	if ( isset($_POST['cat_content']) ) {
		$cat_content = $_POST['cat_content'];
		update_term_meta( $term_id, 'cat_content', $cat_content );
	}
}


function category_manage_columns($columns){
    unset( $columns["cb"] );
	unset( $columns["description"] );

    $custom_array = array(
			'cb' => '<input type="checkbox" />',
			'term_thumbnail' => __('Thumbnail')
    );
	$custom_array_2 = array(			
			'term_order' => __('Num'), // __('Position')
			'term_view' => __('Show content')
    );
    $columns = array_merge( $custom_array, $columns, $custom_array_2 );

    return $columns;
}


function category_manage_columns_content($string, $column_name, $category_id){
		global $wpdb;
		$cat_data = get_term_by('id', $category_id, $_REQUEST['taxonomy'], ARRAY_A);		
		if($column_name == 'term_thumbnail') {
			$image_2 = '';
			if( $cat_data['term_thumbnail'] ) {
				$image_2 = wp_get_attachment_image( $cat_data['term_thumbnail'], 'thumbnail' );	
			}
			$content = '<div class="cat_image">'.$image_2.'</div>';
		}
		else { $content = $cat_data[$column_name]; }
		
    	return $content;
	}


function category_valid_slug() {
	global $wpdb;
	$form_id = 'addtag';  $tax_id = 0;
	if($_REQUEST['tag_ID']) {
$form_id = 'edittag';
$tax = get_term_by('id', $_REQUEST['tag_ID'], $_REQUEST['taxonomy']);
$tax_id = $tax->term_id;
	}
	$table_set = WOW_TABLE_ATTRIBUTE_SET;		
	$atr_set_arr_4 = $wpdb->get_results( "SELECT $table_set.set_post_type FROM $table_set ORDER BY $table_set.position ASC", ARRAY_N );
	$atr_set_arr_5 = array();
	foreach($atr_set_arr_4 as $set_4) { $atr_set_arr_5[] = $set_4[0]; }
	$terms_arr_4 = $wpdb->get_results( "SELECT $wpdb->terms.* FROM $wpdb->terms", ARRAY_A );
	$terms_arr_5 = array();
	foreach($terms_arr_4 as $term_4) { if($term_4['term_id'] != $tax_id) { $terms_arr_5[] = $term_4['slug']; } }
	$pages_arr_4 = $wpdb->get_results( "SELECT $wpdb->posts.post_name FROM $wpdb->posts WHERE post_type = 'page'", ARRAY_N );
	$pages_arr_5 = array();
	foreach($pages_arr_4 as $page_4) { $pages_arr_5[] = $page_4[0]; }

$taxo_arr_25 = array_merge($atr_set_arr_5, $terms_arr_5, $pages_arr_5, array('title', 'date', 'modified', 'comment_count', 'id', 'author', 'name', 'menu_order', 'stock', 'views', 'time', 'year', 'month', 'day', 'category', 'post_tag', 'post', 'page', 'attachment', 'nav_menu_item', 'menu', 'order', 'orderby', 'per_page', 'wow_order', 'c_form_order', 'comment', 'comments', 'message', 'par', 'action', 'theme'));
$arr25_text = '"'.implode('", "', $taxo_arr_25).'"';
?>
<?php // print_r($terms_arr_4); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
/// $("#<?php echo $form_id ?>").submit(function() {  alert('1112'); 
$("#submit").click(function() { 
	var arr45 = [<?php echo $arr25_text ?>];
	var slug_el = $("#<?php echo $form_id ?> input[name=slug]");
	var regii = /[^-_a-z0-9]/g;  var slug = slug_el.val().toLowerCase().replace(regii, '');	
	slug_el.val(slug); /////
if ( slug )  {
	if(arr45.indexOf(slug) != -1) {
	<?php if(!$_REQUEST['tag_ID']) { ?> slug_el.val(slug + '___2'); <?php } ?>
	alert("You can't use this slug! Try to select another slug.");   
	slug_el.focus();   return false;	
	}
}
else { <?php if($_REQUEST['tag_ID']) { ?> slug_el.focus();  return false; <?php } ?> }
});
	});
</script>
<?php
}


function category_edit_fields($term) {	
		if(function_exists('wp_enqueue_media')) { wp_enqueue_media(); } 
		else { wp_enqueue_script('media-upload'); } 		
$term_id = $term->term_id;
// $tax = get_term_by('id', $_REQUEST['tag_ID'], $_REQUEST['taxonomy']); 
// $tax_id = $term->term_taxonomy_id;
?>
<div class="cat-thumbnail field_4">
<?php $image_src_1 = get_template_directory_uri().'/lib/wow_e_shop/files/1.jpg'; ?>
<script type="text/javascript">
jQuery(document).on( 'click', '.set-category-thumbnail', function(e) { // #add_picture_to_category
		e.preventDefault();
		/// jQuery( this ).addClass( 'wps-bton-loading' );
		// Open media gallery
		var uploader_category = wp.media({
					title : 'Add category thumbnail',
					multiple : false
				}).on('select', function() {
					var selected_picture = uploader_category.state().get( 'selection' );
					var attachment = selected_picture.first().toJSON();					
					/// var attachee = JSON.stringify(attachment.sizes.thumbnail.url);					
					//console.log( attachment );
				jQuery( '#category-thumbnail' ).val( attachment.id );			
				jQuery( '.cat_thumbnail img' ).attr( 'src', attachment.sizes.thumbnail.url); // attachment.url					
				/// jQuery( '#add_picture_to_category' ).removeClass( 'wps-bton-loading' );
				}).open();		
		jQuery('#create-thumbnail').hide();
		jQuery('#del-category-thumbnail').show();
}); /////////////
jQuery(document).on( 'click', '#del-category-thumbnail', function(e) { //
		e.preventDefault();			
		jQuery( '#category-thumbnail' ).val(-1); 
		jQuery( '.cat_thumbnail img' ).attr( 'src', '<?php echo $image_src_1 ?>');	
		jQuery('#create-thumbnail').show();
		jQuery('#del-category-thumbnail').hide();	
});
</script>
<?php // print_r($term); ?>
<h3><?php _e('Thumbnail') ?></h3>
<div id="create-thumbnail"<?php if($term->term_thumbnail) { ?> style="display: none;"<?php } ?>>
	<p class="submit">
		<input type="button" name="submit" id="add_picture_to_category" class="button button-primary set-category-thumbnail" value="<?php _e('add Category image') ?>" />
	</p>
</div>

<div id="preview-thumbnail">
	<a class="cat_thumbnail set-category-thumbnail" title="<?php _e('edit Thumbnail'); ?>" id="reset-category-thumbnail">
 <?php if($term->term_thumbnail) { echo wp_get_attachment_image( $term->term_thumbnail, 'thumbnail' ); } else { ?>
    <img src="<?php echo $image_src_1 ?>" />
    <?php } ?>
    </a>
	<div class="remove-thumb">
    <a href="#" id="del-category-thumbnail" title="<?php _e('remove Category image') ?>"<?php if(!$term->term_thumbnail) { ?> style="display: none;"<?php } ?>><?php _e('remove Category image') ?></a>
	</div>
</div>

<input type="hidden" name="category-thumbnail" id="category-thumbnail" value="<?php echo $term->term_thumbnail ?>" />

</div>

<div class="cat_order field_4">
<div class="c_lab"> <h3><?php _e('Position') ?></h3> </div>
	<input type="text" name="term_order" id="term_order" value="<?php echo $term->term_order ?>" />
</div>

<div class="cat_mode field_4">
<div class="c_lab"> <h3><?php _e('Show content') ?></h3> </div>
<?php $cat_mode_arr = array('normal', 'categories_list', 'mixed'); ?>
        <select name="term_view" id="term_view">
        <?php foreach ($cat_mode_arr as $mode) : ?> 
        	<option value="<?php echo $mode ?>" <?php if ($mode == $term->term_view) { ?>selected="selected"<?php } ?>><?php echo $mode ?></option>
		<?php endforeach; ?>
		</select>
</div>


<?php $cat_content = get_term_meta( $term_id, 'cat_content', true ); ?>
<tr class="form-field ">
        <th scope="row"><label for="cat-cat_content"><?php _e('Category content') ?></label></th>
    <td> 
<?php /* <textarea name="cat_content" id="cat-cat_content" class="atr_textarea"><?php echo esc_attr($cat_content) ?></textarea> */ ?>
    <?php $settings = array( 'textarea_rows' => '20', 'quicktags' => false, 'tinymce' => true);
	wp_editor(html_entity_decode(stripcslashes($cat_content)), 'cat_content', $settings); ?>
    </td>
</tr>



<?php
} // function category_edit_fields()



function get_term_post_count4($term){ // get_term_post_count4($term_id, $taxonomy)
	// $term4 = get_term($term_id, $taxonomy);
	$tot_count = $term->count;
	$child_terms = get_terms( $term->taxonomy, array('child_of' => $term->term_id) );
	if(count($child_terms)) {
	foreach ($child_terms as $term_4) {  $tot_count += $term_4->count;  }
	}
	return $tot_count;
}



}
?>