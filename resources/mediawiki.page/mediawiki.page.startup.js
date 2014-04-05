( function ( mw, $ ) {

	mw.page = {};

	// Client profile classes for <html>
	// Allows for easy hiding/showing of JS or no-JS-specific UI elements
	$( 'html' )
		.addClass( 'client-js' )
		.removeClass( 'client-nojs' );

	$( function () {
		// Initialize utilities as soon as the document is ready (mw.util.$content).
		// In the domready here instead of in mediawiki.page.ready to ensure that it gets enqueued
		// before other modules hook into domready, so that mw.util.$content (defined by
		// mw.util.init), is defined for them.
		mw.util.init();

		/**
		 * Fired when wiki content is being added to the DOM
		 *
		 * It is encouraged to fire it before the main DOM is changed (when $content
		 * is still detatched).  However, this order is not defined either way, so you
		 * should only rely on $content itself.
		 *
		 * This includes the ready event on a page load (including post-edit loads)
		 * and when content has been previewed with LivePreview.
		 *
		 * @event wikipage_content
		 * @member mw.hook
		 * @param {jQuery} $content The most appropriate element containing the content,
		 *   such as #mw-content-text (regular content root) or #wikiPreview (live preview
		 *   root)
		 */
		mw.hook( 'wikipage.content' ).fire( $( '#mw-content-text' ) );
	} );

}( mediaWiki, jQuery ) );
