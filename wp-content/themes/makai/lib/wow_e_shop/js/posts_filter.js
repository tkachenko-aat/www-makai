// need prototype.js
function posts_filter(form_par) {
	
	if(form_par == 'sort_only') { // var filter_form = document.forms.sorting_form; 
	var filt_form_id = 'sorting_form_co';
	}
	else { var filt_form_id = 'filter_form_co'; }
	
	  var filter_form_arr = $(filt_form_id).serialize(true);  // filter_form.serialize(true) 
	  var filt_data = JSON.stringify(filter_form_arr);
	   
	    filt_data = filt_data.replace(/{/g, '').replace(/}/g, '').replace(/"/g, '').replace(/,/g, ';').replace(/:/g, '=');
	
	   var ruux = '';
	   ruux = filt_data.match(/\[([^\]]*)\]/g);	   
	   if(ruux != null) {
		ruux = ruux.toString();  
	   var ruux_arr = ruux.split(',');	
	   for (var i = 0; i < ruux_arr.length; i++) {
 		atr_values = ruux_arr[i].replace(/;/g, '-').replace('[', '').replace(']', '');
		filt_data = filt_data.replace(ruux_arr[i], atr_values);			
		} 
	   }
	
		/////// якщо параметр порожній - вирізати 
		var filt_2_arr = filt_data.split(';');
		for (var i = 0; i < filt_2_arr.length; i++) {
 			var arr_26 = filt_2_arr[i].split('=');  
			if(arr_26[1] == '') { var frag_11 = ';'; if(i == 0) { frag_11 = ''; }
			var frag_26 = frag_11 + filt_2_arr[i];
			filt_data = filt_data.replace(frag_26, '');
			}			
		}
		if(filt_data[0] == ';') { filt_data = filt_data.slice(1); }
		///////
	
		filt_data = filt_data.replace(/;/g, '&');
		
		var url_main = '';
		var url2 = window.location.href;		
	if (url2.search('/page/') != -1) {  
	url2_arr = url2.split('/page/');  url_main = url2_arr[0] + '/';
	}
	
	if(form_par == 'clear_all') {  ///// Clear all ///// 
var form_inputs = $(filt_form_id).getElementsByTagName('input');
for (var i = 0; i < form_inputs.length; i++) {
 if (form_inputs[i].type == 'checkbox') { form_inputs[i].checked = false; } ///
} // for
	filt_data = ''; 
	} 
	
	var url_final = url_main + '?' + filt_data;
	
/* if(form_par == 'prod_search') {
	var search_button = document.getElementById('prod_search_button')
	var search_linke_1 = search_button.getAttribute('href');
	search_button.setAttribute('href', '#');
	url_final = search_linke_1 + '?' + filt_data; 
} */
		
	if (isIE()) { window.location.href = url_final; }
	else {
	window.history.replaceState(null, '', url_final);  // window.history.pushState(null, '', url_final);
	window.location.reload();
	}
	
}



function isIE () {
  var myNav = navigator.userAgent.toLowerCase();
  return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}