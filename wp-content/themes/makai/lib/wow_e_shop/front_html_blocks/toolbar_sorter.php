<?php WOW_Product_List_Session::view_mode_change(); ?>

<?php $page_url_4 = str_replace('/', '', strtok($_SERVER["REQUEST_URI"], '?'));
$taxo = $wp_query->tax_query->queries[0]['taxonomy'];
if($taxo) { $taxo_2 = $taxo; } 
elseif($page_url_4 == 'advanced') { $taxo_2 = 'advanced'; }
$tax_parameters = WOW_Product_List_Func::get_tax_parameters($taxo_2);

if ($_GET) { if($page_url_4 == 'advanced') {
	$prod_type = 'advanced';  if($_REQUEST['par']) { $prod_type = $_REQUEST['par']; }
	$products_type_arr = WOW_Product_List_Func::get_front_products_type_arr();
	$tax_parameters['orderby'] = $products_type_arr[$prod_type]['args']['orderby'];
	$tax_parameters['order'] = $products_type_arr[$prod_type]['args']['order'];
} }

$options_5 = get_option('wow_settings_arr');

 ////////// //////////  //////////   ////////// 
$sort_inline = 0; /////////// //  0 - sort with select tag;  1 - sort inline 
?>


   <div class="toolbar title_content">
<form name="sorting_form" id="sorting_form_co" class="toolbar_form" method="GET" action="">

<?php if ($_GET) { $req_arr = array_keys($_GET); if (in_array('par', $req_arr)) { ?>
<input type="hidden" name="par" value="<?php echo $_REQUEST['par'] ?>" />
<?php } } ?>

<?php 
if(!$options_5['wow_view_mode_one']) : ?>
<div class="view_mode">
<label class="lab view_m"><?php _e('View mode') ?>:</label>
<?php 
$view_mode = WOW_Product_List_Func::get_view_mode();

// if($view_mode == 'grid') { $v_title = __('List'); $view_mode_new = 'list'; } else { $v_title = __('Grid'); $view_mode_new = 'grid'; }

$view_mode_arr = array('grid' => __('Grid'), 'list' => __('List'));
$mode_icons_arr = array('grid' => 'fa fa-th', 'list' => 'fa fa-th-list');
?>
<?php foreach ($view_mode_arr as $v_key => $v_label) : 
$mode_id = 'mode-'.$v_key;
?>
   <a class="v_mode <?php echo $v_key; if($v_key == $view_mode) { echo ' act'; } ?>" <?php if($v_key != $view_mode) { ?>onclick="view_mode_change('<?php echo $v_key ?>')" <?php } ?>title="<?php echo $v_label ?>"><i class="<?php echo $mode_icons_arr[$v_key] ?>" aria-hidden="true"></i> <span><?php echo $v_label ?></span></a>
   <?php /* 
   <input type="radio" name="view_mode" id="<?php echo $mode_id ?>" value="<?php echo $v_key ?>" <?php if($v_key == $view_mode) { ?>checked="checked" <?php } else { ?>onchange="do_sort_form(this)" <?php } ?>/> 
   <label class="v_mode <?php echo $v_key; if($v_key == $view_mode) { echo ' act'; } ?>" for="<?php echo $mode_id ?>" title="<?php echo $v_label ?>"><span><?php echo $v_label ?></span></label>
    */ ?>
<?php endforeach; ?>
</div>
<?php endif; ?>
   
   
<?php 
/* Сайт. Сортування - сторінка категорії (index.php). */
   $attributes_sorting = WOW_Attributes_Front::attributes_sorting();
   
   $base_sorting = array();
   // $base_sorte = array ('title', 'date', 'views'); 
   $base_sorte = $tax_parameters['base_sorting'];
   // $sorting_labels_arr = array('title' => __('Title'), 'date' => __('Date'), 'comment_count' => __('Comments'), 'views' => __('Views count'));
   $sorting_labels_arr = WOW_Product_List_Func::get_sorting_labels_arr();
   foreach ($base_sorte as $sort_key) {
	   $base_sorting[] = array('code' => $sort_key, 'frontend_label' => $sorting_labels_arr[$sort_key]);
   }
	
	$sorting_arr = array_merge($base_sorting, $attributes_sorting); // print_r($sorting_arr);
	
	
	// $sort_code = 'title'; $sort_dir = 'asc'; // $sort_code = 'date'; $sort_dir = 'desc';
	$sort_code = $tax_parameters['orderby']; 
	$sort_dir = $tax_parameters['order'];
	$per_page = $tax_parameters['per_page'];  $per_page_base = $tax_parameters['per_page'];
		if($_GET) { 
		$req_arr = array_keys($_GET);
		if (in_array('per_page', $req_arr)) { $per_page = $_GET['per_page']; }
		if (in_array('order', $req_arr)) { $sort_dir = $_GET['order']; }
		if (in_array('orderby', $req_arr)) { $sort_code = $_GET['orderby']; }
		} if($_GET) 

$per_page_2 = 0;  $per_page_2 = $per_page_base * 2;  // 
$per_page_3 = 0;  $per_page_3 = ceil( ($per_page_base * 4) / 100 ) * 100; 
if($view_mode == 'grid') { $per_page_3 = $per_page_3 * 1.2; }
$p_page_arr = array( 
	$per_page_base => array('value' => $per_page_base, 'label' => $per_page_base), 
	$per_page_2 => array('value' => $per_page_2, 'label' => $per_page_2),
	$per_page_3 => array('value' => $per_page_3, 'label' => $per_page_3), 	
	'all' => array('value' => -1, 'label' => __('All'))  // __('View all') // __('View All') 
);
?>



<div class="p_per_page"> 
			<label class="lab" for="posts_per_page_c"><?php _e('Show') // _e('Posts per page') ?></label> 
        <div class="select_box short">
        <i class="ja ja-caret-down"></i>
        <select name="per_page" id="posts_per_page_c" title="<?php _e('Show'); ?>" onchange="do_sort_form(this)"> 
        <?php foreach ($p_page_arr as $key_1 => $value_1) : ?>
      <option value="<?php echo $value_1['value'] ?>" <?php if ($value_1['value'] == $per_page) { ?>selected="selected"<?php } ?>><?php echo $value_1['label'] ?></option>
		<?php endforeach; ?>
		</select> <?php // onchange="document.forms.sorting_form.submit()" // ?>
        </div>
</div>    



   
<div class="sorting">   

   		<label class="lab" for="posts_sorting_c"><?php _e('Sort by'); ?></label>    

<?php if($sort_inline != 1) : // sort select ////////// ////////// ?>
        <div class="select_box">
        <i class="ja ja-caret-down"></i>
        <select name="orderby" id="posts_sorting_c" title="<?php _e('Sort by'); ?>" onchange="do_sort_form(this)"> 
        <?php foreach ($sorting_arr as $s_key => $sort_option) : ?>
      <option value="<?php echo $sort_option['code'] ?>" <?php if ($sort_option['code'] == $sort_code) { ?>selected="selected"<?php } ?>><?php echo $sort_option['frontend_label'] ?></option>
		<?php endforeach; ?>
		</select> <?php // onchange="document.forms.sorting_form.submit()" // ?>
        </div>
        

<?php else : // sort inline ////////// //////////  //////////
// Варіант сортування із конопками в ряд; варіанти "спочатку дешеві", "спочатку дорогі" ?>

<input type="hidden" name="orderby" value="<?php echo $sort_code ?>" />
<?php 
$diff_sorting = array(
	// 'title' => array('label_asc' => __('Title asc 114'), 'label_desc' => __('Title desc 227')),
	'price' => array('label_asc' => __('Cheap first'), 'label_desc' => __('Expensive first'))
	);
$diff_sorting_keys = array_keys($diff_sorting);

$desc_sort_arr = array ('date', 'comment_count', 'views');

$sorting_arr_2 = array();
?>
        <?php foreach ($sorting_arr as $sort_option) :  
		$s_code = $sort_option['code'];
		if(in_array($s_code, $diff_sorting_keys)) { 
		$sorting_arr_2[] = array('code' => $s_code, 'dir' => 'asc', 'frontend_label' => $diff_sorting[$s_code]['label_asc']);
		$sorting_arr_2[] = array('code' => $s_code, 'dir' => 'desc', 'frontend_label' => $diff_sorting[$s_code]['label_desc']);
		}
		elseif(in_array($s_code, $desc_sort_arr)) {
		$sorting_arr_2[] = array('code' => $s_code, 'dir' => 'desc', 'frontend_label' => $sort_option['frontend_label']);
		}
		else {
		$sorting_arr_2[] = array('code' => $s_code, 'dir' => 'asc', 'frontend_label' => $sort_option['frontend_label']);
		}
		endforeach; ?>
        
      <div class="sort_block">  
		<?php foreach ($sorting_arr_2 as $sort_option) : ?>
        <div class="sort_item <?php echo $sort_option['dir'] ?>">
        <a <?php if (($sort_option['code'] == $sort_code and $sort_option['dir'] == $sort_dir) or ($sort_option['code'] == $sort_code and !in_array($sort_option['code'], $diff_sorting_keys))) { ?>class="act"<?php } else { ?>onclick="do_sort_form_2('<?php echo $sort_option['code'] ?>', '<?php echo $sort_option['dir'] ?>')"<?php } ?> title="<?php echo $sort_option['frontend_label'] ?>"><?php echo $sort_option['frontend_label'] ?></a>
    	</div>
		<?php endforeach; ?>
      </div>
        
<?php endif; // sort inline ////////// ___ ////////// ?>

        
        
    
<?php 
if($sort_dir == 'desc') { $sort_title = __('change to ASC'); } else { $sort_title = __('change to DESC'); }

$sort_dir_arr = array('asc' => __('ASC'), 'desc' => __('DESC'));
$dir_icons_arr = array('asc' => 'ha ha-arrow ha-arrow-up', 'desc' => 'ha ha-arrow ha-arrow-down');
// array('asc' => 'fa fa-chevron-up', 'desc' => 'fa fa-chevron-down')
?>

<div class="s_dir_block"<?php if($sort_inline == 1) { ?> style=" display: none;"<?php } ?>>
<?php foreach ($sort_dir_arr as $sort_key => $sort_label) : 
$sort_id = 'toolbar_sort-'.$sort_key;
?>
   <div class="s_dir <?php echo $sort_key ?>"<?php if($sort_key == $sort_dir) { ?> style=" display: none;"<?php } ?>>
   <input type="radio" name="order" id="<?php echo $sort_id ?>" value="<?php echo $sort_key ?>" <?php if($sort_key == $sort_dir) { ?>checked="checked" <?php } else { ?>onchange="do_sort_form(this)" <?php } ?>/> 
   <label class="sort_dir <?php echo $sort_dir ?>" for="<?php echo $sort_id ?>" title="<?php echo $sort_title ?>"><i class="<?php echo $dir_icons_arr[$sort_dir] ?>"></i> <span><?php echo $sort_dir ?></span></label>
   </div>   
<?php endforeach; ?>  
</div>

</div>

</form>

<script type="text/javascript">
function do_sort_form(elem) {
	var form_par = '';
	/* '_desc' */ if(elem.value == 'date' || elem.value == 'views') { document.getElementById("toolbar_sort-desc").checked = true; }
	if(document.getElementById('filter_form_co')) {   
	var filter_form = document.forms.filter_form;  // var filter_form = document.forms["filter_form"];
	filter_form.elements[elem.name].value = elem.value; // filter_form.elements['sorti[code]'].value = '9997';	
	/* '_desc' */ if(elem.value == 'date' || elem.value == 'views') { filter_form.order.value = 'desc'; }
	}
	else { form_par = 'sort_only'; }
	posts_filter(form_par);  // filter_form.submit();
}

<?php if($sort_inline == 1) : // sort inline ////////// ////////// ?>
function do_sort_form_2(orderby_val, order_val) {
	var form_par = '';
	
	var sort_form = document.forms.sorting_form;
	sort_form.orderby.value = orderby_val;
	var radio_order_asc = document.getElementById("toolbar_sort-asc");
	var radio_order_desc = document.getElementById("toolbar_sort-desc");
	if(order_val == 'asc') { radio_order_asc.checked = true; } else { radio_order_desc.checked = true; }
	
	if(document.getElementById('filter_form_co')) {   
	var filter_form = document.forms.filter_form;  
	filter_form.orderby.value = orderby_val;
	filter_form.order.value = order_val; 	
	}
	else { form_par = 'sort_only'; }
	
	posts_filter(form_par);  // filter_form.submit(); 
}
<?php endif; // sort inline ////////// ___ ////////// ?> 
</script>
   </div> <!-- toolbar --> 
