( function ( mw, $ ) {

	mw.page = {};

	// If a user has been autoblocked, two cookies are set.
	// Their values are replicated here in localStorage to guard against cookie-removal.
	// Ref: https://phabricator.wikimedia.org/T5233
	var prefix = mw.config.get( 'wgCookiePrefix' );
	if ( !mw.cookie.get( prefix + 'BlockID' ) && mw.storage.get( 'blockID' ) ) {
		// The block ID exists in storage, but not in the cookie.
		mw.cookie.set( prefix + 'BlockID', mw.storage.get( 'blockID' ) );
		mw.cookie.set( prefix + 'BlockHash', mw.storage.get( 'blockHash' ) );
	} else if ( parseInt( mw.cookie.get( prefix + 'BlockID' ), 10 ) > 0 ) {
		// The block ID exists in the cookie, but not in storage.
		// (When a block expires the cookie remains but its value is ''.)
		mw.storage.set( 'blockID', mw.cookie.get( prefix + 'BlockID' ) );
		mw.storage.set( 'blockHash', mw.cookie.get( prefix + 'BlockHash' ) );
	}

	$( function () {
		mw.util.init();

		/**
		 * Fired when wiki content is being added to the DOM
		 *
		 * It is encouraged to fire it before the main DOM is changed (when $content
		 * is still detached).  However, this order is not defined either way, so you
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

		var $diff = $( 'table.diff[data-mw="interface"]' );
		if ( $diff.length ) {
			/**
			 * Fired when the diff is added to a page containing a diff
			 *
			 * Similar to the {@link mw.hook#event-wikipage_content wikipage.content hook}
			 * $diff may still be detached when the hook is fired.
			 *
			 * @event wikipage_diff
			 * @member mw.hook
			 * @param {jQuery} $diff The root element of the MediaWiki diff (`table.diff`).
			 */
			mw.hook( 'wikipage.diff' ).fire( $diff.eq( 0 ) );
		}
	} );

}( mediaWiki, jQuery ) );
