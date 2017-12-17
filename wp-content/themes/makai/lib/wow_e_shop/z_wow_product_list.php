<?php // Posts filter  // WOW_Product_List_Func  // Posts per page in categories


/* Posts filter and sorting */
function add_posts_filter( $query ) {	
	$page_url_4 = str_replace('/', '', strtok($_SERVER["REQUEST_URI"], '?'));
	if ( (!is_admin() && is_archive() && $query->is_main_query() && !is_date()) or ($page_url_4 == 'advanced') ) : 
	    // is_author()
		if($page_url_4 == 'advanced' and $query->query_vars['query_name'] != 'advanced') { return; }
		
		$taxo = $query->tax_query->queries[0]['taxonomy'];
		if($taxo) { $taxo_2 = $taxo; } 
		elseif($page_url_4 == 'advanced') { $taxo_2 = 'advanced'; }
		$tax_parameters = WOW_Product_List_Func::get_tax_parameters($taxo_2);
		
		if ($_GET) { if($page_url_4 == 'advanced') { /// 'advanced'
	$prod_type = 'advanced';  if($_REQUEST['par']) { $prod_type = $_REQUEST['par']; }
	$products_type_arr = WOW_Product_List_Func::get_front_products_type_arr();
	$tax_parameters['orderby'] = $products_type_arr[$prod_type]['args']['orderby'];
	$tax_parameters['order'] = $products_type_arr[$prod_type]['args']['order'];
		if($prod_type == 'advanced') { $query->query_vars['post_parent'] = 0; } ////
		} } /// --'advanced'
		
		$orderby_meta_f = 'meta_value_num';		
		$per_page = $tax_parameters['per_page'];
		$order = $tax_parameters['order'];
		$orderby = $tax_parameters['orderby'];
			if ($_GET) {
		$req_arr = array_keys($_GET);
		if (in_array('per_page', $req_arr)) { $per_page = $_GET['per_page']; }
		if (in_array('order', $req_arr)) { $order = $_GET['order']; }
		if (in_array('orderby', $req_arr)) { $orderby = $_GET['orderby']; }
			} // if ($_GET) 
			if (function_exists('qtrans_getSortedLanguages')) : // qtrans
			if ($orderby == 'title') {
				global $q_config;
				$orderby = 'title_'.$q_config['language'];
				$orderby_meta_f = 'meta_value';
			}
			endif; // -- qtrans 

		// set posts_per_page
		$query->query_vars['posts_per_page'] = $per_page; // posts_per_page // 10 
		
		// сортування 
		if(in_array($orderby, array('title', 'date', 'modified', 'comment_count', 'ID', 'author', 'name', 'menu_order'))) {	
			$query->set( 'orderby', $orderby ); // 'title' 
		} else {
			$query->set( 'meta_key', $orderby );
			$query->set( 'orderby', $orderby_meta_f ); // 'meta_value_num', 'meta_value' 
		}
		$query->set( 'order', $order ); // 'ASC'
		// $query->query_vars['order'] = $order; // 'ASC' 		
		/*
		// $query->set( 'orderby', 'id' );
		$query->set( 'meta_query', array (
			array (
			'key' => 'myfild_1__type',
			'value' => 1, // array(2, 5) // (for 'BETWEEN')
			// 'compare' => 'BETWEEN' // 'NOT LIKE' // 'BETWEEN'
			),		
		) 
		);
		 */
	if ($_GET) {
		$req_arr = array_keys($_GET);
		if (!in_array('par', $req_arr)) { /// /* якщо є "?par=", фільтр НЕ працює */
	$act_filter_arr = array();
	// $act_filter_arr['relation'] = 'AND'; // 'AND', 'OR'
   foreach ($_GET as $f_key => $f_value) {
	   if(!in_array($f_key, array('orderby', 'order', 'per_page', 'view_mode', 'par'))) {
	   $compare = '';
	   if(strpos($f_value, '--') !== false)  { $separator = '--'; $compare = 1; } else { $separator = '-'; }
	   $act_values = explode($separator, $f_value); // explode('-', $f_value)
	   $act_arr_1 = array('key' => $f_key, 'value' => $act_values, 'compare' => 'IN', 'type' => 'NUMERIC');
	   if($compare == 1) { $act_arr_1['compare'] = 'BETWEEN'; }
	   $act_filter_arr[] = $act_arr_1; // array('key' => $f_key, 'value' => $act_values);
	   }
   } // end foreach
	$query->set( 'meta_query', $act_filter_arr );
		} /// 'par' 
	} // if ($_GET)	

  endif;	
}

add_action( 'pre_get_posts', 'add_posts_filter' );





class WOW_Product_List_Func {


function get_act_currency() {
$options_5 = get_option('wow_settings_arr');  $opt_currency = $options_5['wow_currency'];
$act_currency = $opt_currency['main'];
$currency_avail = $opt_currency['avail'];
	if(count($currency_avail) > 1) {
	global $wp_session;
	if($wp_session['currency'] and in_array($wp_session['currency'], $currency_avail)) { $act_currency = $wp_session['currency']; } 
	}
$act_currency_arr['code'] = $act_currency;
$symb = $act_currency;  if($opt_currency['symbols'][$act_currency]) { $symb = $opt_currency['symbols'][$act_currency]; } 
$act_currency_arr['symbol'] = $symb;
$kurs = 1;  
	if($opt_currency['rates'][$act_currency]) { 
$kurs = $opt_currency['rates'][$act_currency];  $kurs = str_replace(',', '.', $kurs);
if(preg_match("/[^0-9.]/", $kurs) or (substr_count($kurs, '.') > 1)) { $kurs = 1; }
	}
$act_currency_arr['rate'] = $kurs;
return $act_currency_arr;
}


function get_sorting_labels_arr() {
	$sorting_labels_arr = array('title' => __('Title'), 'menu_order' => __('Order'), 'date' => __('Date'), 'comment_count' => __('Comments'), 'views' => __('Views count'), 'rating' => __('Rating'));
	return $sorting_labels_arr;
}


function get_view_mode() {
	$view_mode = 'list';
	$options_5 = get_option('wow_settings_arr'); 
	if($options_5['wow_view_mode']) { $view_mode = $options_5['wow_view_mode']; }
	if(!$options_5['wow_view_mode_one']) {
	global $wp_session;
	if($wp_session['view_mode']) { $view_mode = $wp_session['view_mode']; } // if($_GET['view_mode']) { $view_mode = $_GET['view_mode']; } 
	}
return $view_mode;
}


function get_tax_parameters($taxo) {
		$options_pp_arr = array();
		if(get_option('posts_pp_arr')) { $options_pp_arr = get_option('posts_pp_arr'); }
		$options_pp_arr['advanced'] = array();
		$options_5 = get_option('wow_settings_arr');
		
		$list_c = get_option('posts_per_page');  $grid_c = 12;		
		if($options_5['wow_prod_count_list']) { $list_c = $options_5['wow_prod_count_list']; }
		if($options_5['wow_prod_count_grid']) { $grid_c = $options_5['wow_prod_count_grid']; }		
		if($options_pp_arr[$taxo]['p_count']) { $list_c = $options_pp_arr[$taxo]['p_count']; }
		if($options_pp_arr[$taxo]['p_count_grid']) { $grid_c = $options_pp_arr[$taxo]['p_count_grid']; }		
			$view_mode = WOW_Product_List_Func::get_view_mode();
		if($view_mode == 'grid') { $posts_count = $grid_c; }  else { $posts_count = $list_c; }
		
		$orderby = 'date';  $order = 'desc'; // 'title' 
		if($options_pp_arr[$taxo]['orderby']) { $orderby = $options_pp_arr[$taxo]['orderby']; }
		if($options_pp_arr[$taxo]['order']) { $order = $options_pp_arr[$taxo]['order']; }
		
		$base_sorting = array ('title', 'date', 'views'); 
		if($options_5['wow_base_sorting']) { $base_sorting = $options_5['wow_base_sorting']; }
		if($options_pp_arr[$taxo]['base_sorting']) { $base_sorting = $options_pp_arr[$taxo]['base_sorting']; }
				
		$tax_parameters['orderby'] = $orderby;
		$tax_parameters['order'] = $order;
		$tax_parameters['per_page'] = $posts_count;
		$tax_parameters['base_sorting'] = $base_sorting;
	
return $tax_parameters;
}



function get_front_products_type_arr() {
	$pop_meta_query = 'popular_prod'; $best_meta_query = 'bestseller_prod';
	$pop_count = ''; $best_count = ''; 
	$options_5 = get_option('wow_settings_arr');
if($options_5['wow_product_popular_count_hits']) { $pop_meta_query = 'views'; $pop_count = $options_5['wow_product_popular_count_hits']; }
if($options_5['wow_product_bestsel_count_hits']) { $best_meta_query = 'prod_sales'; $best_count = $options_5['wow_product_bestsel_count_hits']; }	
	
	$products_type_arr = array ( 
		'popular_prod' => array('label' => __('Popular products'), 'args' => array('orderby' => 'views', 'order' => 'desc', 'meta_query' => $pop_meta_query, 'min_hits' => $pop_count)),
		'bestseller_prod' => array('label' => __('Best seller products'), 'args' => array('orderby' => 'prod_sales', 'order' => 'desc', 'meta_query' => $best_meta_query, 'min_hits' => $best_count)),
		'recomend_prod' => array('label' => __('Recommended products'), 'args' => array('orderby' => 'date', 'order' => 'desc', 'meta_query' => 'recomend_prod')),
		'action_prod' => array('label' => __('Action products'), 'args' => array('orderby' => 'date', 'order' => 'desc', 'meta_query' => 'action_prod')),
		'new_prod' => array('label' => __('New products'), 'args' => array('orderby' => 'date', 'order' => 'desc', 'meta_query' => 'new_prod')),
		'special_price' => array('label' => __('Special Price'), 'args' => array('orderby' => 'views', 'order' => 'desc', 'meta_query' => 'special_price')),
		
		'advanced' => array('label' => __('advanced'), 'args' => array('orderby' => 'views', 'order' => 'desc', 'meta_query' => '')),
		
	); //
return $products_type_arr;
}


function get_front_products_args($p_type, $per_page_2) {
	$products_type_arr = WOW_Product_List_Func::get_front_products_type_arr();
	$args_2 = $products_type_arr[$p_type]['args'];
	$p_types = WOW_Attributes_Front::get_attrSet_posttypes();  // array('product_1', 'product_2');	
	if($per_page_2) { $per_page = $per_page_2; }
	else {
	$tax_parameters_2 = WOW_Product_List_Func::get_tax_parameters($p_type);
	$per_page = $tax_parameters_2['per_page'];
	}
	
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // pagination
	$products_args = array (
		'post_status' => 'publish',
		'post_type'	=> $p_types, // $p_types 'post';  'any' - усі типи 
		'posts_per_page'    => $per_page, // К-сть матеріалів на сторінці 
		'paged' => $paged, // pagination
		'order' => $args_2['order'],	
		// 'orderby' => 'id', // 'title'
        // 'post__not_in'		=> array($post->ID),
		// 'post__in' => $wishlist_array,
		// 'meta_key' => 'myfild_1',
		// 'meta_value' => '1',
    );

	$orderby = $args_2['orderby'];
	if(in_array($orderby, array('title', 'date', 'modified', 'comment_count', 'ID', 'author', 'name'))) {
		$products_args['orderby'] = $orderby; 
	} else {	
		$products_args['meta_key'] = $orderby;
		$products_args['orderby'] = 'meta_value_num';		
	}

	if($args_2['meta_query']) {
		$products_args['meta_query'] = array ( array('key' => $args_2['meta_query'], 'value' => 1, 'compare' => '=', 'type' => 'NUMERIC') );
		/* для "популярні" і "хіти продажу": к-сть переглядів, купівель - більше 3-х */
		if(in_array($args_2['meta_query'], array('views', 'prod_sales'))) { 
		$products_args['meta_query'][0]['value'] = $args_2['min_hits']; $products_args['meta_query'][0]['compare'] = '>=';
		}		
		if(in_array($args_2['meta_query'], array('special_price'))) { 
		$products_args['meta_query'][0]['value'] = 0; $products_args['meta_query'][0]['compare'] = '>';
		}	
	}
	if(is_tax() or is_category()) {
		global $wp_query;
		$queried_object = $wp_query->queried_object;
		$products_args['tax_query'] = array ( array('taxonomy' => $queried_object->taxonomy, 'terms' => $queried_object->term_id) );
	}

return $products_args;
}



}






/* ************   * Posts per page in categories  *************** */

function posts_per_page_in_cats_f() {
		?>
        <div class="wrap">         
            <div class="title"> 
        <div class="chili"> <a class="logo_2" href="http://chili-web.com.ua" target="_blank"><img src="http://chili-web.com.ua/wp-content/themes/chili-web/images/logo_black.png" /></a> <div class="desc"><a href="http://chili-web.eu" target="_blank">Chili-web</a> <br />Website development</div> </div>        
            <h2><?php _e('Posts per page in categories (taxonomies). Sorting') ?></h2>  
            </div>     
   <?php if ($_REQUEST['settings-updated']) { ?> 
        <div id="message" class="updated"><p><?php _e('Settings saved.') ?></p></div>
   <?php } ?>            
            <form method="post" action="options.php">
            <?php
                submit_button();
				
				// This prints out all setting fields
                settings_fields( 'posts_per_page_set' );   
                do_settings_sections( 'posts_per_page_set' );				
				
				
				Posts_pp_Settings::taxonomies_list();
				
				
                submit_button();
            ?>
            </form>
        </div>
        <?php
}



add_action('admin_init', 'posts_per_page_settings');

function posts_per_page_settings(){
	
	register_setting(
		'posts_per_page_set',                // settings page
		'posts_pp_arr',         // option name
		'admin_settings_validate_2'  // validation callback
	);
/* 
	add_settings_section(
            'default', 
            'Settings', 
            'print_info_s', 
            'posts_per_page_set' 
    );	
	add_settings_field(
		'posts_pp_1',    
		'Параметр 1 ...',   
		'posts_pp_1_input', 
		'posts_per_page_set',    
		'default'   
	);
 */	
							
}

function print_info_s() {
}

// Validate user input
function admin_settings_validate_2( $input ) {	 
	$valid = $input;	
	return $valid;
} 




class Posts_pp_Settings {
	
function taxonomies_list() {
	// $sort_arr = array('title' => __('Title'), 'date' => __('Date'), 'comment_count' => __('Comments'), 'views' => __('Views count'));
	$sort_arr = WOW_Product_List_Func::get_sorting_labels_arr();
	$sort_arr_2 = $sort_arr;  unset( $sort_arr_2['menu_order'] );
	
	$orderby_arr = array_merge($sort_arr, array('price' => __('Price')));

	$order_arr = array('asc' => __('ASC'), 'desc' => __('DESC'));

$taxo_arr = get_taxonomies(array('public' => true));
unset($taxo_arr['post_format']);
?>

        <h3><?php _e('Taxonomies') ?></h3>
    
    <div class="cat_types field_2 options wide_opt">
      
    <div class="options-header"> <div class="colu name"><?php _e('Taxonomy name') ?></div> <div class="colu p_count"><?php _e('Posts per page') ?></div> <div class="colu p_count grid"><?php _e('p/p/page (Grid)') ?></div> <div class="colu orderby"><?php _e('Sort by') ?></div> <div class="colu order"><?php _e('Position') ?></div>  <div class="colu base_sort"><?php _e('Sorting parameters') ?></div> </div>
    
	<div class="cat_list" >
    <?php //////
	$options_pp_arr = array();
	if(get_option('posts_pp_arr')) { $options_pp_arr = get_option('posts_pp_arr'); }
		
	$input_name_1 = 'posts_pp_arr';	

	
	foreach ($taxo_arr as $tax ) :  //// ///// 
	$tax_info = $options_pp_arr[$tax];
	?>
	<div class="option-box" id="tax-<?php echo $tax ?>">    
	<div class="colu name"> <span><?php echo get_taxonomy($tax)->labels->name ?></span> </div>
    <div class="colu p_count"> <input type="text" class="" name="<?php echo $input_name_1 ?>[<?php echo $tax ?>][p_count]" value="<?php echo $tax_info['p_count'] ?>" /> </div>
    <div class="colu p_count grid"> <input type="text" class="" name="<?php echo $input_name_1 ?>[<?php echo $tax ?>][p_count_grid]" value="<?php echo $tax_info['p_count_grid'] ?>" /> </div>
    <div class="colu orderby"><select name="<?php echo $input_name_1 ?>[<?php echo $tax ?>][orderby]">
    <?php foreach ($orderby_arr as $orderby_k => $label) { ?> <option value="<?php echo $orderby_k ?>" <?php if($orderby_k == $tax_info['orderby']) { ?>selected="selected"<?php } ?>><?php echo $label ?></option> <?php } ?>
    </select></div>
    <div class="colu order"><select name="<?php echo $input_name_1 ?>[<?php echo $tax ?>][order]">
    <?php foreach ($order_arr as $order_k => $label) { ?> <option value="<?php echo $order_k ?>" <?php if($order_k == $tax_info['order']) { ?>selected="selected"<?php } ?>><?php echo $label ?></option> <?php } ?>
    </select></div>
    <div class="colu base_sort">
    <input type="hidden" name="<?php echo $input_name_1 ?>[<?php echo $tax ?>][base_sorting]" value="0" />   
	<?php foreach ($sort_arr_2 as $s_key => $s_label) { $sort_id = $tax.'-sort-'.$s_key; ?> <label class="line_lab" for="<?php echo $sort_id ?>"><?php echo $s_label ?></label> <input type="checkbox" 
    id="<?php echo $sort_id ?>" name="<?php echo $input_name_1 ?>[<?php echo $tax ?>][base_sorting][<?php echo $s_key ?>]" value="<?php echo $s_key ?>"<?php if($tax_info['base_sorting'][$s_key]) { ?> checked="checked"<?php } ?> /> <?php } ?>
    </div>  
    </div>
    <?php endforeach; //// ///// ?>
    
    </div>    
      
    </div> <!-- categories_types -->
 
<?php
}

}



?>