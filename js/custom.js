jQuery("body").on("click", ".site-header > .wrap .menu-toggle", function() {
    jQuery('body, html').toggleClass('flow-hidden');
 });

jQuery( document ).ready(function() {
jQuery('.footer-widgets-1,.footer-widgets-2,.footer-widgets-3,.footer-widgets-4,.footer-widgets-5').wrapAll('<div class="footer-main"></div>');
jQuery(window).scroll(function () {    
    if (jQuery(this).scrollTop() > 1) {
        jQuery(".site-header").css("top" , "0");
	    jQuery(".site-header").addClass("sticky");
      
    } else {       
        jQuery(".site-header").removeClass("sticky");
       
    }
});
});
jQuery(document).on('ready', function() {
    jQuery('a.close').click(function() {
    jQuery('.announcement-bar').hide();
});
    });
 