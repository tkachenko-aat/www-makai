<?php /* Фільтри формату select */ ?>
<?php // if(is_archive()) : ?> 
<?php if(is_archive()) { $filt = ''; } 
elseif(is_page() or is_front_page()) { $filt = 'advanced'; }
$attributes_filter = WOW_Attributes_Front::attributes_filter($filt);
?> 

<?php if(count($attributes_filter)) : ?>
<div class="block sidebar_filter open">
<?php if($filt != 'advanced') { ?>
<div class="block-title">
<span><?php _e('Products filter') ?></span>
<a class="toogle-b"></a>
</div>
<?php } ?>
<div class="block-content" style="display:block;">
<form name="filter_form" id="filter_form_co" method="GET" action="">

<?php if($_GET) { ///////////// active filters ?>
<div class="state">
<?php $active_filters = WOW_Attributes_Front::attributes_active_filters(); ?>
<div class="button_line"> <a class="button small clear_all" onclick="posts_filter('clear_all')" title="<?php _e('Clear all') ?>"><?php _e('Clear all') ?></a> </div>
</div>
<?php } ////////// __ active filters ?>

<div class="attributes_filt">
<?php foreach ($attributes_filter as $attribute) : ?>
<div class="attrib_blok" id="filt-<?php echo $attribute['code'] ?>">

<?php if($attribute['backend_input'] != 'text') : ?>
<div class="op_select">
<?php $atr_tit = '<b>'.$attribute['frontend_label'].'</b>';  $act_values = array(); 
if($_GET) { $req_arr = array_keys($_GET); if (in_array($attribute['code'], $req_arr)) {
$atr_tit = $active_filters[$attribute['code']]['atr_options'][0]['label']; 
$act_values = explode("-", $_GET[$attribute['code']]); 
} } ?>
 <a class="select_title" title="<?php echo $attribute['frontend_label'] ?>" onclick="select_open(this)"> <div class="inn"> <?php echo $atr_tit ?> </div> <i class="ja ja-caret-down"></i> </a> 


<div class="drop">

<div class="op_option all_options" <?php if(!$_GET[$attribute['code']]) { ?>style="display:none;"<?php } ?>>
<?php $item_id_1 = 'filt-'.$attribute['code'].'-all'; ?>
<input type="radio" id="<?php echo $item_id_1 ?>" name="<?php echo $attribute['code'] ?>" value="" /> 
<label for="<?php echo $item_id_1 ?>" onclick="select_change(this)" class="inn"><span style="display:none;"><b><?php echo $attribute['frontend_label'] ?></b></span> <span><?php _e('View all') ?><?php // _e('All options') ?></span></label>
</div>

<?php $num = 0; ?>
<?php foreach ($attribute['atr_options'] as $option) { 
$num = $num + 1; 
$item_id = 'filt-'.$attribute['code'].'-'.$option['id']; // $item_id = $attribute['code'].'-'.$num; 
?>
<div class="op_option <?php if(in_array($option['id'], $act_values)) { ?>selected<?php } ?>">
<input type="radio" id="<?php echo $item_id ?>" name="<?php echo $attribute['code'] ?>" value="<?php echo $option['id'] ?>" <?php if(in_array($option['id'], $act_values)) { ?>checked="checked" <?php } ?>/> 

<label for="<?php echo $item_id ?>" onclick="select_change(this)" class="inn"><span><?php echo $option['label'] ?><?php if($attribute['frontend_unit']) { ?> <span class="unit"><?php echo $attribute['frontend_unit'] ?></span><?php } ?></span></label>
</div>
<?php } ?>
</div>

</div>

<?php else : /// $attribute['backend_input'] == 'text' ?>
<?php endif; ?>

</div>
<?php endforeach; ?>
</div>

<div class="filt_button"> 
<div class="non_act_fon"></div>
<a class="button a_search" onclick="posts_filter()" title="<?php _e('Search') ?>"><?php _e('Go') ?></a> 
</div>


<?php 
// $sort_code = 'title'; $sort_dir = 'asc'; // $sort_code = 'date'; $sort_dir = 'desc';
$sort_code = ''; $sort_dir = ''; $per_page = '';
	if($_GET) { 
		$req_arr = array_keys($_GET);
		if (in_array('per_page', $req_arr)) { $per_page = $_GET['per_page']; }
		if (in_array('order', $req_arr)) { $sort_dir = $_GET['order']; }
		if (in_array('orderby', $req_arr)) { $sort_code = $_GET['orderby']; }
	} if($_GET) 
/*
$view_mode = WOW_Product_List_Func::get_view_mode();
  */
?>

<?php if($_GET) { $req_arr = array_keys($_GET); if (in_array('par', $req_arr)) { ?>
<input type="hidden" name="par" value="<?php echo $_REQUEST['par'] ?>" />
<?php } } ?>
<input type="hidden" name="orderby" value="<?php echo $sort_code ?>" />
<input type="hidden" name="order" value="<?php echo $sort_dir ?>" />
<input type="hidden" name="per_page" value="<?php echo $per_page ?>" />
<?php /* <input type="hidden" name="view_mode" value="<?php echo $view_mode ?>" /> */ ?>
</form>
</div>
<?php /* script 'posts_filter()' - підключено в head (/lib/wow_attributes/js/posts_filter.js) */ ?>
</div> <!--  -->
<?php endif; ?>
<?php // endif; // (is_archive()) ?>