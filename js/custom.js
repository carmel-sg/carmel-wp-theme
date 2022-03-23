jQuery("body").on("click", ".site-header > .wrap .menu-toggle", function() {
    jQuery('body, html').toggleClass('flow-hidden');
 });
var topBheight = jQuery(".hello-bar").outerHeight();
jQuery(".site-header").css("top" , topBheight);
jQuery( document ).ready(function() {
if (jQuery( "body" ).hasClass('single')) {
jQuery( "header" ).addClass( 'header-black');
} 
jQuery( "<div class='has-custom-font-size is-style-outline common-btn has-normal-font-size newbtn'><a href='#' class='wp-block-button__link has-text-color'>JOIN US IN PERSON</a></div><div class='has-custom-font-size is-style-outline common-btn has-normal-font-size newbtn'><a href='#' class='wp-block-button__link has-text-color'>JOIN US ONLINE</a></div>" ).insertAfter( "#menu-main-menu" );  
jQuery('.footer-widgets-1,.footer-widgets-2,.footer-widgets-3,.footer-widgets-4,.footer-widgets-5').wrapAll('<div class="footer-main"></div>');
jQuery(window).scroll(function () {    
    if (jQuery(this).scrollTop() > 1) {
        jQuery(".site-header").css("top" , "0");
	    jQuery(".site-header").addClass("sticky");
      
    } else {       
        jQuery(".site-header").css("top" , topBheight);
        jQuery(".site-header").removeClass("sticky");
       
    }
});
});
jQuery(document).on('ready', function() {
    jQuery('a.close').click(function() {
    jQuery('.announcement-bar').hide();
});
    });
 