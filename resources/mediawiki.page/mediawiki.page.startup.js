( function ( mw, $ ) {

	mw.page = {};

	// Client profile classes for <html>
	// Allows for easy hiding/showing of JS or no-JS-specific UI elements
	$( 'html' )
		.addClass( 'client-js' )
		.removeClass( 'client-nojs' );

	$( function () {
		// Initialize utilities as soon as the document is ready (mw.util.$content,
		// messageBoxNew, profile, tooltip access keys, Table of contents toggle, ..).
		// In the domready here instead of in mediawiki.page.ready to ensure that it gets enqueued
		// before other modules hook into domready, so that mw.util.$content (defined by
		// mw.util.init), is defined for them.
		mw.util.init();

		/**
		 * @event wikipage_content
		 * @member mw.hook
		 * @param {jQuery} $content
		 */
		mw.hook( 'wikipage.content' ).fire( $( '#mw-content-text' ) );
	} );

}( mediaWiki, jQuery ) );
