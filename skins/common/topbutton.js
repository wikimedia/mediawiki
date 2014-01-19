(function( $ ) {
	$('body').append('<div id="go-to-top">Go to Top</div>');
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > 300) {
            jQuery('#go-to-top').fadeIn(500);
        } else {
            jQuery('#go-to-top').fadeOut(500);
        }
    });
    jQuery('#go-to-top').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, 500);
        return false;
    })
})( jQuery );
