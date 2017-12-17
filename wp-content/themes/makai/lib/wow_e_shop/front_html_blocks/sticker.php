<?php 
	$pop_meta = 'popular_prod'; $best_meta = 'bestseller_prod';
	// $pop_count = ''; $best_count = ''; 
	$options_5 = get_option('wow_settings_arr');
if($options_5['wow_product_popular_count_hits']) { $pop_meta = 'views'; $pop_count = $options_5['wow_product_popular_count_hits']; }
if($options_5['wow_product_bestsel_count_hits']) { $best_meta = 'prod_sales'; $best_count = $options_5['wow_product_bestsel_count_hits']; }	

$sticker_arr = array('recomend_prod', $best_meta, $pop_meta, 'discount', 'new_prod', 'special_price');
// 'action_prod' 
$attributes_1 = array();
foreach ($sticker_arr as $stick) {
	$stick_val = get_post_meta($post_id, $stick, true);
	if($stick_val and !in_array($stick, array('views', 'prod_sales'))) {
	$attributes_1[] = WOW_Attributes_Front::post_view_one_attribute($post_id, $stick);
	}
	elseif($stick == 'prod_sales' and $stick_val >= $best_count) { $attributes_1[] = array('code' => 'bestseller_prod', 'frontend_label' => __('Best seller product')); }
	elseif($stick == 'views' and $stick_val >= $pop_count) { $attributes_1[] = array('code' => 'popular_prod', 'frontend_label' => __('Popular product')); }
	// $attribute_1['atr_value'][0]
}
?>
<?php if(count($attributes_1)) : 
$sticker_attrib = $attributes_1[0];
$sticker_label = $sticker_attrib['frontend_label'];
	if($sticker_attrib['code'] == 'discount') { 
$sticker_label = '<span class="lab">'.$sticker_attrib['frontend_label'].'</span>';
$sticker_label .= '<span class="val"><span>-</span>'.implode(", ", $sticker_attrib['atr_value']).'</span>'; // $sticker_attrib['atr_value'][0]
if($sticker_attrib['frontend_unit']) { $sticker_label .= '<span class="unit">'.$sticker_attrib['frontend_unit'].'</span>'; }
	} // 'discount' 
?>
<div class="sticker <?php echo $sticker_attrib['code'] ?>" style="position: absolute; z-index: 2;"> <div class="sticker_mid"><span><?php echo $sticker_label ?></span></div> <?php // echo $post_id ?></div>
<?php endif; ?>