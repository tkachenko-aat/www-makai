<?php 
$prod_id = $post->ID;
$rating_arr = WOW_Rating_Session::rating_array();
?>
<div class="rating_box">
<?php if(!in_array($prod_id, $rating_arr)) { ?>
<div class="my_rating" style="border: solid 1px rgba(0,0,0,0);"> 
<span class="lab"><?php _e('Rate this product') // 'Rate product and vote' ?></span>
<form name="rating_<?php echo $prod_id ?>">
<?php for ($i = 1; $i <= $rating_max; $i++) { ?>
	<input type="radio" class="star" name="prod_rating_<?php echo $prod_id ?>" value="<?php echo $i ?>" />
<?php } ?>
</form>
</div>
<a class="button small rate" onClick="do_product_rating('<?php echo $prod_id ?>')"><?php _e('Vote') ?></a>
<?php } else { ?>
<span class="already lab"><?php _e('You have already rated this product') ?></span>
<?php } ?>
</div>

<script type="text/javascript">
function do_product_rating(prod_id) { 
		var url3 = window.location.href;
var form_name = 'rating_' + prod_id;
var rating_input_name = 'prod_rating_' + prod_id;
var rating_inp = document.forms[form_name].elements[rating_input_name];

if(rating_inp.value == '') {
	rating_inp[0].parentNode.parentNode.className += ' error';
} else {
  new Ajax.Updater( '', url3, { 
  	method: 'post',
    parameters: {rating_prod_id: prod_id, rating_value: rating_inp.value},
	onComplete: 
		function() {
			window.location.reload(); //
		}
  } );
}
}
</script>