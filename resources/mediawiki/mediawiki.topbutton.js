( function( mw, $ ) {
	$('body').append('<div id="gotoTop">'+mw.message( 'gototop-button' )+'</div>');
	$(window).scroll(function() {
		if ($(this).scrollTop() > 300) {
			$('#gotoTop').fadeIn(500);
		} else {
			$('#gotoTop').fadeOut(500);
		}
	});
	$('#gotoTop').click(function(event) {
		event.preventDefault();
		$('html, body').animate({scrollTop: 0}, 500);
		return false;
	});
}( mediaWiki, jQuery ) );
