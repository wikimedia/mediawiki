( function( $ ) {

	mw.page = {};

	/* Client profile classes for <html> */

	var prof = $.client.profile();
	$( 'html' )
		.addClass(
			'client-' + prof.name
			+ ' client-' + prof.name + '-' + prof.versionBase
			+ ' client-' + prof.layout
			+ ' client-' + prof.platform
			+ ' client-js' )
		.removeClass( 'client-nojs' );

} )( jQuery );
