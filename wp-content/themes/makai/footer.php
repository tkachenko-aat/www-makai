
</div> <!-- page-content -->

</section> <!-- main content -->


<footer id="site_footer">

	<div class="foot_main">
		
			<div class="footer_inn">
				<div  id="futer_centr">
					<?php dynamic_sidebar( 'footer_centr' ); ?> 
				</div>
				
                
                <div id="menu3"> <?php wp_nav_menu( array( 'theme_location' => 'm3','fallback_cb'=> '' ) ); ?> </div>
                <div id="menu4"> <?php wp_nav_menu( array( 'theme_location' => 'm4','fallback_cb'=> '' ) ); ?> </div>
					
 				<?php /* <a href="http://chili-web.com.ua" target="_blank">Chili-web</a> // http://chili-web.eu/ */ ?>
				
			</div> <!-- footer_inn -->
		
	</div>

	<div class="foot_bot line">
		
			<div class="footer_inn">
            	<div class="copyright"><?php dynamic_sidebar( 'footer_copyright' ); ?></div>	
			</div>
		
	</div>

 
	    
<?php /*	<a id="scroll_to_top" title="<?php _e('Scroll to top') ?>"> <i class="ha ha-arrow ha-arrow-up"></i> </a> */ ?>

	<!-- chili-web.com.ua -->
</footer>






<?php /* ********** Спливаючі вікна ************* */ ?>

<?php /* Яваскрипти перенесені в footer */ ?>

<div id="overlay_2" class="overlay_fon" style="display: none; position: fixed; left: 0; right: 0; top: 0; bottom: 0; ">  </div> <!-- onClick="overlay_hide()" -->
  
  <?php /* Форма входу */ ?>
 <?php if (!is_user_logged_in()) : ?>
<div id="form_login_mini" class="lightb_window medium" style="display: none;">
	<a class="close_but btn-remove" onClick="overlay_hide()" title="<?php _e('Close') ?>"> <i class="ha ha-close"></i> </a>
	<div class="lightb_inner"> <?php include WOW_DIRE.'front_html_blocks/login_mini.php'; /* wow_e_shop *** login_mini *** */ ?> </div>
</div> 
 <?php endif ?>
  
<div class="lightb_window big" id="lightb_cart" style="display: none;">
	<a class="close_but btn-remove" onClick="overlay_hide()" title="<?php _e('Close') ?>"> <i class="ha ha-close"></i> </a> 
	<div class="lightb_inner"> </div>
</div>

<div class="lightb_window medium" id="lightb_contact_form_call_me" style="display: none;">
	<a class="close_but btn-remove" onClick="overlay_hide()" title="<?php _e('Close') ?>"> <i class="ha ha-close"></i> </a>
	<div class="lightb_inner">     	
		<div class="contact-form call_me">
<?php $form_name = 'contact_form_call_me'; ?>
<h4><?php _e('Call-back service') ?></h4>
<form name="<?php echo $form_name ?>" id="<?php echo $form_name ?>" method="post">
<ul class="c_form fields">
<li> <label for="call_customer_name"><?php _e('Name') ?></label> <div class="box"><input type="text" name="customer_name" id="call_customer_name" class="required" placeholder="<?php _e('Name') ?>" title="<?php _e('Name') ?>" value="" /></div> </li>
<li> <label for="call_customer_phone"><?php _e('Phone') ?></label> <div class="box"><input type="text" name="customer_phone" id="call_customer_phone" class="phone_mask<?php // jQuery mask ?> required" placeholder="<?php _e('Phone') ?>" title="<?php _e('Phone') ?>" value="" /></div> </li>
</ul>
<div class="but_line"><a class="button" onClick="do_contact_form('<?php echo $form_name ?>')"><span><?php _e('Submit') ?></span></a></div>
</form>
		</div>
	</div>
</div>

<?php /* **********  ************* */ ?>  






</div>
<!-- END: wrapper -->


 
<script type="text/javascript">
	window.addEventListener("DOMContentLoaded", function() { // after jQuery is loaded async. 
/// scroll_liver ///
jQuery(document).ready(function($) {
	$('.arrow_link a[href^="#"]').on('click',function (e) {	   
		e.preventDefault();
	    var target = this.hash;
	    var $target = $(target);
		var height_57 = $target.offset().top; 
		$('html, body').stop().animate({
	        'scrollTop': height_57 
	    }, 600, 'swing', function () {
	    });
	});
});
/// ___ scroll_liver ///

jQuery(document).ready(function($) {
var BRUSHED = window.BRUSHED || {};

BRUSHED.filter = function (){
	if($('#projects').length > 0){		
		var $container = $('#projects');
		
		
			$container.isotope({
			  // options
			 animationEngine: 'best-available',
	
			  itemSelector : '.item-thumbs',
			 layoutMode: 'fitRows',
  percentPosition: true,
  fitRows: {
    gutter: '.gutter-sizer'
  }
  
			});
	
	
		
		// filter items when filter link is clicked
		var $optionSets = $('#options .option-set'),
			$optionLinks = $optionSets.find('a');
	
		  $optionLinks.click(function(){
			var $this = $(this);
			// don't proceed if already selected
			if ( $this.hasClass('selected') ) {
			  return false;
			}
			var $optionSet = $this.parents('.option-set');
			$optionSet.find('.selected').removeClass('selected');
			$this.addClass('selected');
	  
			// make option object dynamically, i.e. { filter: '.my-filter-class' }
			var options = {},
				key = $optionSet.attr('data-option-key'),
				value = $this.attr('data-option-value');
			// parse 'false' as false boolean
			value = value === 'false' ? false : value;
			options[ key ] = value;
			if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
			  // changes in layout modes need extra logic
			  changeLayoutMode( $this, options )
			} else {
			  // otherwise, apply new options
			  $container.isotope( options );
			}
			
			return false;
		});
	}
}


BRUSHED.filter();

});

jQuery(document).ready(function($) {
$(".envira-gallery-image, .envira-gallery-link").attr("title", "");
});

    }, false); // __ after jQuery is loaded
</script>


<script type="text/javascript"> 

	window.addEventListener("DOMContentLoaded", function() { // after jQuery is loaded async. 
	
jQuery(document).ready(function($) {
	 $.fn.scrollToTop=function(){$(this).hide().removeAttr("href");
	 if($(window).scrollTop()!="0"){$(this).fadeIn("slow")}var scrollDiv=$(this);
	 $(window).scroll(function(){if($(window).scrollTop()<200){$(scrollDiv).fadeOut("slow")}
	 else{$(scrollDiv).fadeIn("slow")}});
	 $(this).click(function(){$("html, body").animate({scrollTop:0},"slow")})}
});

jQuery(document).ready(function($) {
$("#scroll_to_top").scrollToTop();
});







jQuery(document).ready(function($) { <?php /* Виїжджаюче меню */ ?>
$(".menu-hamb").click(function(){
		if ($(this).is(".open")) { 
	$(this).removeClass("open").addClass("close");
	$(this).next().addClass("expande");
    $(".main-menu").removeClass("main-menu-open");
    $(".wrapper").removeClass("wrap-ovfl-hid");
    $(".log_img").removeClass("log_img_invert");
		}
		else {
            $(this).removeClass("close").addClass("open");
            $(".main-menu").addClass("main-menu-open");
            $(".wrapper").addClass("wrap-ovfl-hid");
            $(".log_img").addClass("log_img_invert");
        }
	$(this).next().slideToggle("normal");
return false;
});

}); /// jQuery(document).ready(function($)


/// HEADER
jQuery(document).ready(function($) {

var lastScrollTop = 0;
var topElem = document.getElementById('top');
$(window).scroll(function(event){
	if(window.innerWidth > 780) {
var st = $(this).scrollTop();
if (st > lastScrollTop){
   // downscroll code
  $(topElem).removeClass("show").addClass("hide"); 
} else {
   // upscroll code
  $(topElem).removeClass("hide").addClass("show"); 
}
lastScrollTop = st;
if(st == 0){ $(topElem).removeClass("show")}
	}


});
	
});

    }, false); // __ after jQuery is loaded 



    



</script>


<?php include WOW_DIRE.'js/e_shop_scripts.php'; /* wow_e_shop *** e_shop_scripts *** */ ?>


<?php wp_footer(); ?>

</body>

</html>