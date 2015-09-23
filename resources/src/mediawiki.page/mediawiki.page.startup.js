( function ( mw, $ ) {

	// Support: MediaWiki < 1.26
	// Cached HTML will not yet have this from OutputPage::getHeadScripts.
	document.documentElement.className = document.documentElement.className
		.replace( /(^|\s)client-nojs(\s|$)/, '$1client-js$2' );

	mw.page = {};

	$( function () {
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
