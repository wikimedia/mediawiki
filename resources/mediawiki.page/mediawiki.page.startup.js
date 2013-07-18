( function ( mw, $ ) {

	mw.page = {};

	// Client profile classes for <html>
	// Allows for easy hiding/showing of JS or no-JS-specific UI elements
	$( 'html' )
		.addClass( 'client-js' )
		.removeClass( 'client-nojs' );

	$( document ).ready( function () {
		// Initialize utilities as soon as the document is ready (mw.util.$content,
		// messageBoxNew, profile, tooltip access keys, Table of contents toggle, ..).
		mw.util.init();

		mw.hook( 'wikipage.content' ).fire( $( '#mw-content-text' ) );
	} );

}( mediaWiki, jQuery ) );
