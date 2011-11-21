( function( $ ) {

	mw.page = {};

	/* Client profile classes for <html> */
	/* Allows for easy hiding/showing of JS or no-JS-specific UI elements */

	$( 'body' )
		.addClass('client-js' )
		.removeClass( 'client-nojs' );

} )( jQuery );
