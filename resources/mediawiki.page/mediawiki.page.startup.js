( function ( mw, $ ) {

	mw.page = {};

	// Client profile classes for <html>
	// Allows for easy hiding/showing of JS or no-JS-specific UI elements
	$( 'html' )
		.addClass('client-js' )
		.removeClass( 'client-nojs' );

	// Initialize utilities as soon as the document is ready (mw.util.$content,
	// messageBoxNew, profile, tooltip access keys, Table of contents toggle, ..).
	// Enqueued into domready from here instead of mediawiki.page.ready to ensure that it gets enqueued
	// before other modules hook into document ready, so that mw.util.$content (defined by mw.util.init),
	// is defined for them.
	$( mw.util.init );

}( mediaWiki, jQuery ) );
