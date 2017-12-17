<script type="text/javascript">
<?php /* need prototype.js */ ?>

<?php $options_5 = get_option('wow_settings_arr'); ?>

  
function overlay_hide(){
	 document.getElementById("overlay_2").style.display = "none";
	 
	 var lightb_windowss = document.getElementsByClassName("lightb_window"); 
	 for (var i = 0; i < lightb_windowss.length; i++) {  var wind1 = lightb_windowss[i];  wind1.style.display = "none";  }
}


function show_login_mini(){
document.getElementById("overlay_2").style.display = "block";
var messages2 = document.getElementsByClassName("messages_line");
	for (var i = 0; i < messages2.length; i++) {  var mess = messages2[i];  mess.innerHTML = '';  }
var login_mini = document.getElementById("form_login_mini");
var scroll_y = document.body.scrollTop || document.documentElement.scrollTop;
login_mini.style.top = scroll_y + 150 + "px";
login_mini.style.display = "block";
} 


function show_lightb_window(container_id) {
	document.getElementById("overlay_2").style.display = "block";	
	var lightb_cart = document.getElementById("lightb_cart");	
	var lightb_cart_content = lightb_cart.getElementsByClassName("lightb_inner")[0];  lightb_cart_content.innerHTML = ''; 
	cart_page2_container = document.createElement("div");  	
  	cart_page2_container.id = container_id; /* !! */
	lightb_cart_content.appendChild(cart_page2_container);

var scroll_y = document.body.scrollTop || document.documentElement.scrollTop;
lightb_cart.className += " small";
lightb_cart.style.top = scroll_y + 70 + "px";
lightb_cart.style.display = "block";
}


function ajax_prepare_html(element2) {		
	ajax_hloader = document.createElement("div");  
	var loader_img = 'hloader.gif';  if(element2.id == 'content-list') { loader_img = 'hloader-2.gif'; }	
  	ajax_hloader.className = 'hloader-p';   ajax_hloader.innerHTML = '<span class="hloader"> <img src="<?php bloginfo('template_url'); ?>/images/' + loader_img + '" alt="load" /> </span>';
	element2.appendChild(ajax_hloader);
	
	page_temp = document.createElement("div");  	
  	page_temp.id = 'ajax_page2_temp';  	page_temp.style.display = "none";
	element2.appendChild(page_temp);

	overlay4 = document.createElement("div");  	
  	overlay4.className = 'overlay_stat_4';  
	element2.appendChild(overlay4);
}


function page_replace_new(element2) {	
	page_new_info = $('ajax_page2_temp').down('.ajax_replace2_content').innerHTML;
	element2.innerHTML = page_new_info;
}

function sidebar_replace_new(sidebar_class) {   
	sidebar_new_info = $('ajax_page2_temp').down(sidebar_class).innerHTML;	
    $$(sidebar_class).each( function(el) { 
		el.innerHTML = sidebar_new_info;
		if(sidebar_class.indexOf("sidebar") != -1) { if(el.className.indexOf("block") == -1) { el.className += ' block'; } }
	} );
}


function addtocart(post_id, p_qty, forma_id) {	 
	 <?php if($options_5['wow_quick_order_mode']) { /* Режим швидких замовлень (без кошика) */ ?>
	 show_quick_order(post_id, p_qty, forma_id);
	 <?php } else { ?>
	 show_cart(post_id, p_qty, forma_id);
	 <?php } ?>
}


function show_cart(post_id, p_qty, forma_id) {
	show_lightb_window('cart_page');  var lig_cart = lightb_cart; ///
	ajax_prepare_html(cart_page2_container);

	var prod_parameters = {};
	if(forma_id) { prod_parameters = $(forma_id).serialize(true); }
	else if(post_id) { prod_parameters = {prod_id: post_id, qty: p_qty}; }
	prod_parameters['popupp'] = 1; ///
	
	new Ajax.Updater( page_temp.id, '<?php bloginfo('url'); echo '/cart/'; ?>', { 
	method: 'post',
	parameters: prod_parameters, // {id: '273', name_spisok: 'spisok25'} 
	evalScripts: true, //
	onComplete: 
		function() {			
			lig_cart.className = lig_cart.className.replace(/small/g, '');
			sidebar_replace_new('.sidebar_cart');
			page_replace_new(cart_page2_container);				
		}
	} );
} 


function update_cart() { // Оновлення вікна кошика
	var cart_page = document.getElementById("cart_page");
	ajax_prepare_html(cart_page); 
	
	var prod_parameters = $('form_update_cart').serialize(true);
	prod_parameters['popupp'] = 1; ///
	
  new Ajax.Updater( page_temp.id, '<?php bloginfo('url'); echo '/cart/'; ?>', { 
  	method: 'post',
    parameters: prod_parameters,
	evalScripts: true, //
	onComplete: 
		function() {
			sidebar_replace_new('.sidebar_cart');
			page_replace_new(cart_page); 
		}
	} );
}


function cart_item_delete(qty_id) {	 
		   var item_qty = document.getElementById(qty_id);	   
		   item_qty.setAttribute('value', 0);
		   var blok_li = item_qty.parentNode.parentNode.parentNode;
		   blok_li.style.display = "none";
		   
		update_cart();
}  


function show_quick_order(post_id, p_qty, forma_id) {
	show_lightb_window('checkout_page');  var lig_cart = lightb_cart; ///
	ajax_prepare_html(cart_page2_container);

	var prod_parameters = {};
	if(forma_id) { prod_parameters = $(forma_id).serialize(true); }
	else if(post_id) { prod_parameters = {quick_order_prod_id: post_id, qty: p_qty}; }
	prod_parameters['popupp'] = 1; ///
	
	new Ajax.Updater( page_temp.id, '<?php bloginfo('url'); echo '/checkout/'; ?>', { 
	method: 'post',
	parameters: prod_parameters, // {id: '273', name_spisok: 'spisok25'} 
	evalScripts: true, //
	onComplete: 
		function() {
			lig_cart.className = lig_cart.className.replace(/small/g, ''); 
			page_replace_new(cart_page2_container);	
		}
	} );
} 


function show_checkout_page() {
	show_lightb_window('checkout_page');  var lig_cart = lightb_cart; ///
	ajax_prepare_html(cart_page2_container);
	
	new Ajax.Updater( page_temp.id, '<?php bloginfo('url'); echo '/checkout/'; ?>', { 
	method: 'post',
	evalScripts: true, //
	parameters: { popupp: 1 }, // {id: '273', name_spisok: 'spisok25'} 
	onComplete: 
		function() {
			lig_cart.className = lig_cart.className.replace(/small/g, ''); 
			page_replace_new(cart_page2_container);	
		}
	} );
} 


function addto_compare(post_id) {
	 show_compare(post_id);
}

function show_compare(post_id) {
	show_lightb_window('compare_page');  var lig_cart = lightb_cart; ///
	ajax_prepare_html(cart_page2_container);
	
	new Ajax.Updater( page_temp.id, '<?php bloginfo('url'); echo '/compare/'; ?>', { 
	method: 'post',
	parameters: {comp_prod_id: post_id, popupp: 1}, // {id: '273', name_spisok: 'spisok25'} 
	evalScripts: true, //
	onComplete: 
		function() {			
			lig_cart.className = lig_cart.className.replace(/small/g, '');
			sidebar_replace_new('.sidebar_compare');
			page_replace_new(cart_page2_container);
		}
	} );
}

function remove_compare(par) { //
	var comp_page = document.getElementById("compare_page");
	ajax_prepare_html(comp_page); 
	
  new Ajax.Updater( page_temp.id, '<?php bloginfo('url'); echo '/compare/'; ?>', { 
  	method: 'post',
    parameters: {comp_remove: par, popupp: 1},
	evalScripts: true, //
	onComplete: 
		function() {
			sidebar_replace_new('.sidebar_compare');
			page_replace_new(comp_page);
		}
	} );
}



function addto_wishlist(post_id) {
	 <?php if (is_user_logged_in()) { ?>
	 show_wishlist(post_id);
	 <?php } else { ?>
	 show_login_mini();
	 <?php } ?>
}

function show_wishlist(post_id) {
	show_lightb_window('wishlist_page');  var lig_cart = lightb_cart; ///
	ajax_prepare_html(cart_page2_container);
	
	new Ajax.Updater( page_temp.id, '<?php bloginfo('url'); echo '/profile/wishlist/'; ?>', { 
	method: 'post',
	parameters: {wish_prod_id: post_id, popupp: 1}, // {id: '273', name_spisok: 'spisok25'} 
	evalScripts: true, //
	onComplete: 
		function() {			
			lig_cart.className = lig_cart.className.replace(/small/g, '');
			sidebar_replace_new('.sidebar_wishlist');
			page_replace_new(cart_page2_container);
		}
	} );
}

function remove_wishlist(par) { //
	var wish_page = document.getElementById("wishlist_page");
	ajax_prepare_html(wish_page);
	
  new Ajax.Updater( page_temp.id, '<?php bloginfo('url'); echo '/profile/wishlist/'; ?>', { 
  	method: 'post',
    parameters: {wish_remove: par, popupp: 1},
	evalScripts: true, //
	onComplete: 
		function() {
			sidebar_replace_new('.sidebar_wishlist');
			page_replace_new(wish_page);
			var current_url = document.location.href;
			if (current_url.search('/wishlist/page/') != -1) { document.location.href = '<?php bloginfo('url'); echo '/profile/wishlist/'; ?>'; }
		}
	} );
}



<?php /* Зміна кількості товарів у кошику і на сторінці товару */ ?>
 function qty_chan(par, qty_id, pagee) {		   
		   var item_qty = document.getElementById(qty_id);
		   var qtyy = parseInt(item_qty.value);
		   <?php /* якщо поле порожнє */ ?> if(item_qty.value == '') { qtyy = 1; }
		   // 'plus' 	'minus'  
		   if(par == 'minus') { if(qtyy > 1) { qtyy = qtyy - 1; } } else if(par == 'plus') { qtyy = qtyy + 1; }
		   item_qty.setAttribute('value', qtyy);
		   item_qty.value = qtyy;
			
			if (pagee == 'cart') {
			document.getElementById("button_update_cart").style.display = "block";
			document.getElementById("button_show_checkout").style.display = "none";		
			}		
}  


function qty_validate(evt, type) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  var key2 = key;
  key = String.fromCharCode( key );  
  if( type == 'int' ) { var regex = /[0-9]/; } else { var regex = /[0-9]|\./; } 
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}


function view_mode_change(mode) { //  Grid / List	
  var url2 = window.location.href;
  new Ajax.Updater( '', url2, { 
  	method: 'post',
    parameters: {view_mode: mode},
	onComplete: 
		function() {	
		// var url2 = window.location.href;	
		var url_main = url2;
	if (url2.search('/page/') != -1) {  
	url2_arr = url2.split('/page/'); url244_arr = url2_arr[1].split('/');
	var page_num_frag = '/page/' + url244_arr[0];
	url_main = url_main.replace(page_num_frag, '');
	}	
	window.location.href = url_main;
		} // onComplete function
	} );
}



function select_open(idElement) {
  var curve1 = idElement;  var curve = curve1.parentNode;
  curve.className += ' current';
	var op_selects = document.getElementsByClassName("op_select");
	for (var i = 0; i < op_selects.length; i++) { 
	var op_select_1 = op_selects[i];
	if ( op_select_1.className.indexOf("current") == -1 ) { op_select_1.className = 'op_select hidd'; }
	} // for 
  curve.className = curve.className.replace(/ current/g, '');
if (curve.className.indexOf("active") != -1) { curve.className = 'op_select hidd'; } else { curve.className = 'op_select active'; } 
}

function select_change(idElement) {
	var curve3 = idElement;
  var curve2 = curve3.parentNode;  var curve1 = curve2.parentNode;  var curve = curve1.parentNode;

curve.className = 'op_select hidd';
var act_value = curve3.children[0].innerHTML;
curve.children[0].children[0].innerHTML = act_value;

	if(curve.parentNode.className.indexOf("attrib_blok") != -1) { 
var filt_form = document.getElementById("filter_form_co");  filt_form.className += ' active';
curve1.children[0].style.display = "block";
	}
}

function currency_change(idElement, currency) {
	var curve3 = idElement;
  var curve2 = curve3.parentNode;   
  var curve1 = curve2.parentNode;
  var curve = curve1.parentNode;

curve.className = 'op_select hidd';
var act_value = curve3.innerHTML;
curve.children[0].children[0].innerHTML = act_value;

  var url2 = window.location.href;
  new Ajax.Updater( '', url2, { 
  	method: 'post',
    parameters: {act_currency: currency},
	onComplete: 
		function() {
	window.location.href = url2;
		} // onComplete function
	} );
}



<?php /* *** Product .Images-box *** */ 
$slb_enab = 0;
if (function_exists('Responsive_Lightbox')) { $slb_enab = 1; }
?>
function change_main_img(src_main, url_popup, post_id, idElement) { 
  var img_gal = idElement;
  var curv_li = img_gal.parentNode;  var curv_ul = curv_li.parentNode;
  var images_box = curv_ul.parentNode.parentNode.parentNode;
  // var view_image = document.getElementById("<?php // echo $main_img_id ?>");
  var view_image = images_box.children[0];
 
 var items_li = curv_ul.children;
 for (var i=0; i < items_li.length; i++) {
 	items_li[i].className = '';
 }
 curv_li.className = 'active';
 
/* view_image.children[0].setAttribute('src', src_main);
view_image.setAttribute('href', url_popup); */
view_image.children[0].children[0].setAttribute('src', src_main); 
<?php if (is_single()) { ?>
// view_image.innerHTML = '<a <?php if($slb_enab == 1) { ?>href="' + url_popup + '" data-rel="lightbox-gallery-' + post_id + '"<?php } ?>><img src="' + src_main + '" /></a>';
<?php } else { // category ?>
// view_image.children[0].children[0].setAttribute('src', src_main); 
<?php } ?>
}


<?php /* Flying effect (Cart effect sloowly) */ ?>
	<?php if($options_5['wow_to_cart_fly']) { ?>
	window.addEventListener("DOMContentLoaded", function() { // after jQuery is loaded async.
jQuery(document).ready(function($) {  
$(".button.btn-cart").click(function(){
	$(".lightb_window").hide(); $(".overlay_fon").hide();
	scrollik = true; 
	if ( scrollik == true ) { $("html, body").animate({scrollTop:0}, 1500); }
	setTimeout(function(){scrollik = false;}, 2000);
	var cart = $('.block.sidebar_cart .block-title');
	if ($(this).parent().is(".addtocart_b")) { 
	var imgtodrag = $(this).parents('.product-shop').find('.main-img img').eq(0); 
	} else { imgtodrag = $(this).parents('li.item').find('.product-image img').eq(0); }
		if (imgtodrag) {
			var imgclone = imgtodrag.clone()
        .offset({ top:imgtodrag.offset().top, left:imgtodrag.offset().left })				
                // .css({"opacity":"0.7", "position":"fixed", "left":"45%", "top":"48%", "height":"180px", "width":"180px", "z-index":"100"})
		.css({"opacity":"0.7", "position":"absolute", "height":"180px", "width":"180px", "z-index":"2"})					
		.appendTo($('body'))
        .animate({
                    'top':cart.offset().top + 10,
                    'left':cart.offset().left + 30,
                    'width':60,
                    'height':60,
					'opacity':0.3
                }, 1500);
        imgclone.animate({'width':0, 'height':0}, function(){ $(this).detach() });			
		} 
});	
});
    }, false); // __ after jQuery is loaded
	 <?php } ?>
	 


<?php /* *** Infinite Scroll, load more items *** 
footer.php: window.onscroll = function() { set_fixed_top9(); infi_scroll(); } */ ?>
function infi_page_replace_new(element2) {	
	page_new_info = $('ajax_page2_temp').down('.ajax_infi_replace2').innerHTML;
	for (var i=0; i < 3; i++) { element2.removeChild(element2.lastChild); } /// 
	old_conte = element2.innerHTML;
	element2.innerHTML = old_conte + page_new_info;
}

function show_more_items(button_elem) { // 
	if(button_elem) { var button_line = button_elem.parentNode; }
	next_butt = $('pagi').down('a.next');
if(!next_butt) {  if(button_elem) { button_line.style.display = "none"; }  } 
else {
	next_link = next_butt.href; // alert(next_link);
	var items_list = document.getElementById("content-list");
	ajax_prepare_html(items_list); 
	
  new Ajax.Updater( page_temp.id, next_link, { 	
	parameters: { popupp: 1 }, 
	evalScripts: true, //
	onComplete: 
		function() {
			sidebar_replace_new('.navigation');
			infi_page_replace_new(items_list); 
			items_list.className = items_list.className.replace(/ infi_no_more/g, '');
			if(button_elem) { 
			next_butt = $('pagi').down('a.next');  if(!next_butt) { button_line.style.display = "none"; }
			}
		}
	} );	
}
}

function infi_scroll() {
if( document.getElementById("content-list") ) {	
var items_list = document.getElementById("content-list");
if ( items_list.className.indexOf("infi_no_more") == -1 ) {
  var tops_2 = items_list.offsetTop;
  var height_2 = items_list.offsetHeight;
  var win_height = document.documentElement.clientHeight;
  var scroll_y = document.body.scrollTop || document.documentElement.scrollTop;
  if ( (tops_2 + height_2 + 20 - win_height - scroll_y) <= 0 ) { 
  show_more_items(); 
  items_list.className += " infi_no_more";
  } 
}
}
}



<?php /* *** Contact form, Call-back *** */ ?>

function show_contact_form(lightb_win_id) {
document.getElementById("overlay_2").style.display = "block";
var lightb_contact_form = document.getElementById(lightb_win_id);
	var scroll_y = document.body.scrollTop || document.documentElement.scrollTop;
	lightb_contact_form.style.top = scroll_y + 150 + "px";
lightb_contact_form.style.display = "block";
}


function do_contact_form(form_id) { 
contact_form_check_fields(form_id); /*  */ // errore 
if ( errore == 0 )  { 
var c_form_id;
var block_upd;
if(form_id) { c_form_id = form_id; } else { c_form_id = 'contact_form'; }

	if(c_form_id != 'contact_form') {
	var lightb_win_id = 'lightb_' + c_form_id;
var lightb_contact_form = document.getElementById(lightb_win_id);
	var lightb_z_co = lightb_contact_form.getElementsByClassName("lightb_inner")[0];
	block_upd = lightb_z_co;
	}
	else { block_upd = document.getElementById("contacts_page"); }

	/*if( document.forms[c_form_id].add_file ) {*/ $(c_form_id).submit();/* } /// ?? 
	else { // if no files
		
	ajax_prepare_html(block_upd); 

  new Ajax.Updater( page_temp.id, '<?php /*bloginfo('url'); echo '/contact-form-success/';*/ ?>', { 
  	method: 'post',
    parameters: $(c_form_id).serialize(),
	onComplete: 
		function() { page_replace_new(block_upd); }
	} );
	} // __ if no files */
}
}


function do_contact_form_2() { 
contact_form_check_fields(''); /*  */ // errore 
if ( errore == 0 )  { 
var c_form_id = 'contact_form';
var block_upd;

document.getElementById("overlay_2").style.display = "block";
var lightb_contact_form = document.getElementById("lightb_contact_form_call_me");
        var scroll_y = document.body.scrollTop || document.documentElement.scrollTop;
        lightb_contact_form.style.top = scroll_y + 150 + "px";
        lightb_contact_form.className += " small";
lightb_contact_form.style.display = "block";

        var lightb_z_co = lightb_contact_form.getElementsByClassName("lightb_inner")[0];
        lightb_z_co.innerHTML = '';
        block_upd = lightb_z_co;
                
        ajax_prepare_html(block_upd); 

  new Ajax.Updater( page_temp.id, '<?php bloginfo('url'); echo '/contact-form-success/'; ?>', { 
          method: 'post',
    parameters: $(c_form_id).serialize(),
        onComplete: 
                function() { 
                        lightb_contact_form.className = lightb_contact_form.className.replace(/small/g, '');
                        page_replace_new(block_upd); 
                        $(c_form_id).reset();
                }
        } );

}
}




function contact_form_check_fields(form_id) {
////// form_name = form_id ;
errore = 0; 
var form_name;
if(form_id) { form_name = form_id; } else { form_name = 'contact_form'; }
var forma = document.forms[form_name]; 
	var forme_arr5 = ['customer_name', 'customer_phone', 'customer_email', 'customer_site', 'customer_city', 'customer_address', 'customer_company', 'comment', 'subject'];
	var forme_arr4 = [];
		i2 = 0;
		for (var i = 0; i < forme_arr5.length; i++) { /////// ////
		inp_field_name = forme_arr5[i];  
		if(forma.elements[inp_field_name]) { forme_arr4[i2] = forme_arr5[i];  i2++; }
		} // //////// for 
			
		for (var i = 0; i < forme_arr4.length; i++) {  /////// ////
		inp_field_name = forme_arr4[i];
		inp_field4 = forma.elements[inp_field_name];		
  if ( inp_field4.className.indexOf("required") != -1 ) { 
   if ( inp_field4.value.length < 3 ) {
    inp_field4.focus();
	inp_field4.className += ' error'; 	errore = 1;	
	document.getElementById("error1").innerHTML="We're missing some info here. Please check";

  } else { inp_field4.className = inp_field4.className.replace(/error/g, ''); }
 if(inp_field4.parentNode.className.indexOf("select_box") != -1) {  if(inp_field4.className.indexOf("error") != -1) { inp_field4.parentNode.className += ' error'; } else { inp_field4.parentNode.className = inp_field4.parentNode.className.replace(/error/g, ''); }  }
  }		
		} // //////// for 

if(forma.elements['customer_email']) {  
var input_email = forma.elements['customer_email'];
  if ( input_email.className.indexOf("required") != -1 ) { 
   var reg_email = /^[\w\.\d-_]+@[\w\.\d-_]+\.\w{2,4}$/i;
   if ( !input_email.value.match(reg_email) ) {
    input_email.focus();
	input_email.className += ' error'; 	errore = 1;	
		document.getElementById("error2").innerHTML="We're missing some info here. Please check";
  } else { input_email.className = input_email.className.replace(/error/g, ''); }
  }   
} // 'customer_email' 

  if ( errore == 1 )  {
    return false;
	}
}

</script>