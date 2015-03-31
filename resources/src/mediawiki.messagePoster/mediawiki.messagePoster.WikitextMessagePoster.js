/*global OO*/
( function ( mw, $ ) {
	/**
	 * This is an implementation of MessagePoster for wikitext talk pages.
	 *
	 * @abstract
	 * @class
	 *
	 * @extends mw.messagePoster.MessagePoster
	 *
	 * @param {mw.Title} title Wikitext page in a talk namespace, to post to
	 */
	mw.messagePoster.WikitextMessagePoster = function MwWikitextMessagePoster( title ) {
		this.api = new mw.Api();
		this.title = title;
	};

	OO.inheritClass(
		mw.messagePoster.WikitextMessagePoster,
		mw.messagePoster.MessagePoster
	);

	/**
	 * @inheritdoc
	 */
	mw.messagePoster.WikitextMessagePoster.prototype.post = function ( subject, body ) {
		var dfd = $.Deferred(),
			promise = dfd.promise();

		mw.messagePoster.WikitextMessagePoster.parent.prototype.post.call( this, subject, body );

		// Add signature if needed
		if ( body.indexOf( '~~~' ) === -1 ) {
			body += '\n\n~~~~';
		}

		this.api.newSection(
			this.title,
			subject,
			body,
			{ redirect: true }
		).done( function ( resp, jqXHR ) {
			if ( resp.edit.result === 'Success' ) {
				dfd.resolve( resp, jqXHR );
			} else {
				// mediawiki.api.js checks for resp.error.  Are there actually cases where the
				// request fails, but it's not caught there?
				dfd.reject( 'api-unexpected' );
			}
		} ).fail( function ( code, details ) {
			dfd.reject( 'api-fail', code, details );
		} );

		return promise;
	};

	mw.messagePoster.factory.register( 'wikitext', mw.messagePoster.WikitextMessagePoster );
}( mediaWiki, jQuery ) );
