<script type="text/javascript"> 
 function show_log_forgot(logg){
 if (logg == 'forgot')  { var loginform_display = "none"; var forgot_display = "block"; } else { var loginform_display = "block"; var forgot_display = "none"; }
 document.getElementById("log_login").style.display = loginform_display;
 document.getElementById("log_forgot").style.display = forgot_display;
 
var messages2 = document.getElementsByClassName("messages_line");
	for (var i = 0; i < messages2.length; i++) {  var mess = messages2[i];  mess.innerHTML = '';  }
}


function mini_login() {
	var login_mini = document.getElementById("form_login_mini");
	var login_mini_co = login_mini.getElementsByClassName("lightb_inner")[0];
	var div_failed = document.getElementById("login_failed");

	var login_m_form = document.forms.login_mini;
	var input_log = login_m_form.elements['log']; var input_pwd = login_m_form.elements['pwd'];
	if(input_log.value.length < 1 || input_pwd.value.length < 1) { 
	div_failed.innerHTML = '<span class="error message"><?php _e('Fill the field!') ?></span>';
	
	} else {	
	ajax_prepare_html(login_mini_co);  <?php /* ajax_prepare_html() - в e_shop_scripts.php (footer) */ ?>
	<?php // echo site_url('wp-login.php') ?>

new Ajax.Updater( '', '<?php echo wp_login_url() ?>', { 
	method: 'post', 
	parameters: $('mini_login_form').serialize(),
	onComplete: 
		function(data) {		// function(data)
		var respons = data.responseText;	
		if (respons.indexOf('loginform') == -1) { 
  			login_mini.style.display = "none";
  			window.location.reload();
  		} 
  		else { // login_temp2.innerHTML = '';
  			ajax_hloader.parentNode.removeChild( ajax_hloader );
			page_temp.parentNode.removeChild( page_temp );
  			div_failed.innerHTML = '<span class="error message"><?php _e('<strong>ERROR</strong>: Invalid username or incorrect password.') ?></span>';
  		}	 
		}
});

	} /// if(input_log.value.length > 1)
}


// Forgot Password
function forgotpass() {
	var messeg = document.getElementById("forgotpass_mess");
	var input_f_email = document.forms.lostpass.user_login;
	var reg_email = /^[\w\.\d-_]+@[\w\.\d-_]+\.\w{2,4}$/i;
		if ( input_f_email.value.match(reg_email) ) {
new Ajax.Updater('', '<?php echo site_url('wp-login.php?action=lostpassword') ?>', { method: 'post', parameters: $('form-forgot').serialize() } ) ; //   
  messeg.innerHTML = '<span class="succes message"><?php _e('Check your e-mail for the confirmation link.') ?></span>';
  setTimeout(function() { messeg.innerHTML = ''; }, 7000)	
		}
		else { messeg.innerHTML = '<span class="error message"><?php _e('<strong>ERROR</strong>: Invalid username or e-mail.') ?></span>'; } 
}


function enter_login(evt, type) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  if (key == 13) { 
  	if (type == 'login') { mini_login(); } else if (type == 'forgot') { forgotpass(); }
  }
}
</script>


<div class="block sidebar_login"> 
       
    <div class="block-title"><span><?php _e('Log In') ?></span></div>
    
    <div class="block-content"> 
    
<div id="log_login" class="log_m">
<form name="login_mini" id="mini_login_form" action="<?php echo site_url('wp-login.php') ?>" method="post">
              <ul class="fields">
              <li>   
			<label class="long_text" for="log"><?php _e('E-mail or Username') // 'Login Name' ?></label>
<div class="box"><input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" onkeypress="enter_login(event, 'login')" placeholder="<?php _e('E-mail or Username') ?>" title="<?php _e('E-mail or Username') ?>" /></div>
               </li>
               <li>      
					<label for="pwd"><?php _e('Password') ?></label>
<div class="box"><input type="password" name="pwd" id="pwd" onkeypress="enter_login(event, 'login')" placeholder="<?php _e('Password') ?>" title="<?php _e('Password') ?>" /></div>
               </li>               
               <li class="remm">  
	            <input type="checkbox" name="rememberme" id="rememberme" class="fine_checkbox" checked="checked" value="forever" />
                    <label for="rememberme"> <?php _e('Remember Me') ?> </label>
               </li>
               <li> <div class="messages_line" id="login_failed"> </div> </li>
               </ul>      
     					
            <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
	<div class="login-links">
           <a class="f_left" onClick="show_log_forgot('forgot')"><?php _e('Lost Password') ?></a> 
    <input type="button" class="button" onclick="mini_login()" value="<?php _e('Log In') ?>" />
 	<?php /* <input type="submit" name="submit" value="<?php _e('Log In') ?>" class="bt_login" /> */ ?>
    </div>
        
        <div class="actions reg">
    <a href="<?php echo wp_registration_url(); ?>" class="oop_reg"><?php _e('Register') ?></a>
		</div>        	
</form>

<?php // print_r($attributes_1); ?>              
  </div> <!-- log_loginform  log_m  Email -->
  

<div id="log_forgot" class="log_m" style="display: none;"> 
        
    <h3><?php _e('Lost Password') ?></h3>
<form name="lostpass" id="form-forgot" action="#<?php // echo site_url('wp-login.php?action=lostpassword') ?>" method="post">
   
       <div class="roww forgot_text"><?php _e('Please enter your username or email address. You will receive a link to create a new password via email.') ?></div>
       <ul class="fields dzembroni">
       <li>
               <label for="f_email_address"><?php _e('Email') ?></label>           
    <div class="box"> <input type="text" name="user_login" id="f_email_address" value="" onkeypress="enter_login(event, 'forgot')" placeholder="<?php _e('Email') ?>" title="<?php _e('Email') ?>" /> </div>
       </li> 
       </ul>           
             
    <div class="messages_line" id="forgotpass_mess"> </div>   
    
    <div class="login-links">    
 <a onClick="show_log_forgot('login')" class="f_left"> <small>&laquo; </small><?php _e('Back to Login') ?> </a> 
        <button type="button" onclick="forgotpass()" class="button"> <span><?php _e('Submit') ?></span> </button>
    </div>
</form>
    </div>  <!-- log_forgot  log_m -->  
<?php /* 
/// переклад текстів - системні фрази

Get New Password
<strong>ERROR</strong>: Enter a username or e-mail address.
<strong>ERROR</strong>: There is no user registered with that email address.
<strong>ERROR</strong>: Invalid username or e-mail.
Please enter a valid email address. 

Check your e-mail for your new password.
A message will be sent to your email address.  // не стандартна фраза 
 */ ?>



</div>
    
     </div>    