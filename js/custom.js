jQuery("body").on("click", ".site-header > .wrap .menu-toggle", function() {
    jQuery('body, html').toggleClass('flow-hidden');
//    e.preventDefault();
 });
//  toggle btn
var topBheight = jQuery(".hello-bar").outerHeight();
jQuery(".site-header").css("top" , topBheight);
jQuery( document ).ready(function() {
// var $html = jQuery("body");
// if ($html.hasClass('page-id-18') || $html.hasClass('single')) {
// // if (jQuery( "body" ).hasClass('page-id-18','single')) {
// jQuery( "header" ).addClass( 'header-white');
// }

if (jQuery( "body" ).hasClass('single')) {
jQuery( "header" ).addClass( 'header-black');
} 
//  else {
//   jQuery( "header" ).removeClass( 'header-white');
// }    
// if(jQuery('header').hasClass("header-white")) {
// jQuery('.title-area').html('<a href="http://server1.rvtechnologies.in/mount-caramel/"  class="logoblack"  rel="home" aria-current="page"><img width="253" height="48" src="http://server1.rvtechnologies.in/mount-caramel/wp-content/uploads/2022/02/footer-resize-1.png" class="custom-logo-qhite" alt="amin"></a>');
// jQuery('.title-area').prepend('<a href="http://server1.rvtechnologies.in/mount-caramel/"  class="logowhite"  rel="home" aria-current="page"><img src="http://server1.rvtechnologies.in/mount-caramel/wp-content/uploads/2022/02/logo.png" /></a>');


//jQuery('.wrap').html('<img width="253" height="48" src="http://server1.rvtechnologies.in/mount-caramel/wp-content/uploads/2022/02/logo.png" class="custom-logo-qhite" alt="amin">');

// }
// elseif(jQuery('header').hasClass("sticky")) {
// jQuery('.title-area').html('<img width="253" height="48" src="http://server1.rvtechnologies.in/mount-caramel/wp-content/uploads/2022/02/logo.png" class="custom-logo-qhite" alt="amin">');

// }

//jQuery( "#menu-test" ).append( "<a href="#">Join Us In Person</a>" ); 
 
jQuery( "<div class='has-custom-font-size is-style-outline common-btn has-normal-font-size newbtn'><a href='#' class='wp-block-button__link has-text-color'>JOIN US IN PERSON</a></div><div class='has-custom-font-size is-style-outline common-btn has-normal-font-size newbtn'><a href='#' class='wp-block-button__link has-text-color'>JOIN US ONLINE</a></div>" ).insertAfter( "#menu-main-menu" );  
jQuery('.footer-widgets-1,.footer-widgets-2,.footer-widgets-3,.footer-widgets-4,.footer-widgets-5').wrapAll('<div class="footer-main"></div>');
jQuery(window).scroll(function () {    
    if (jQuery(this).scrollTop() > 1) {
        // if (jQuery(window).width() > 991) {
            jQuery(".site-header").css("top" , "0");
	    jQuery(".site-header").addClass("sticky");
        // } 
    } else {
        // if (jQuery(window).width() > 991) {
            jQuery(".site-header").css("top" , topBheight);
           jQuery(".site-header").removeClass("sticky");
        // } 
    }
});
});
 