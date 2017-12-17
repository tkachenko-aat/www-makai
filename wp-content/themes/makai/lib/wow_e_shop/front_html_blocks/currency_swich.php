<?php WOW_Product_List_Session::currency_change(); ?>

<?php $options_5 = get_option('wow_settings_arr');  $opt_currency = $options_5['wow_currency']; 
$currency_avail = $opt_currency['avail'];
$currency_arr = WOW_Settings::wow_currency_list();
$act_currency_arr = WOW_Product_List_Func::get_act_currency();
$act_currency = $act_currency_arr['code'];
if(count($currency_avail) > 1) {
?>
<div class="block sidebar_currency" id="sidebar_currency_v">
<?php // echo $act_currency ?>
<div class="op_select" id="currency_switch" title="<?php _e('Currency') ?>">
 <a class="select_title" onclick="select_open(this)"> <div class="inn"> <?php echo $currency_arr[$act_currency] ?> </div> <i class="ja ja-caret-down"></i> </a>           
            <div class="drop"> 
       		 <?php foreach ($currency_avail as $c_key => $c_val) : ?>                    
         <div class="op_option <?php if($c_key == $act_currency) { ?>selected<?php } ?>">  
  <a onclick="currency_change(this, '<?php echo $c_key ?>')" class="inn"> <?php echo $currency_arr[$c_key] ?> </a>  
         </div>                 
            	<?php endforeach; ?>  
            </div>             
</div>
</div>

<?php } // if(count($currency_avail) > 1) ?>          