( function () {
	/**
	 * This is an implementation of MessagePoster for wikitext talk pages.
	 *
	 * @class mw.messagePoster.WikitextMessagePoster
	 * @extends mw.messagePoster.MessagePoster
	 *
	 * @constructor
	 * @param {mw.Title} title Wikitext page in a talk namespace, to post to
	 * @param {mw.Api} api mw.Api object to use
	 */
	function WikitextMessagePoster( title, api ) {
		this.api = api;
		this.title = title;
	}

	OO.inheritClass(
		WikitextMessagePoster,
		mw.messagePoster.MessagePoster
	);

	/**
	 * @inheritdoc
	 */
	WikitextMessagePoster.prototype.post = function ( subject, body ) {
		mw.messagePoster.WikitextMessagePoster.parent.prototype.post.call( this, subject, body );

		// Add signature if needed
		if ( body.indexOf( '~~~' ) === -1 ) {
			body += '\n\n~~~~';
		}

		return this.api.newSection(
			this.title,
			subject,
			body,
			{ redirect: true }
		).then( function ( resp, jqXHR ) {
			if ( resp.edit.result === 'Success' ) {
				return $.Deferred().resolve( resp, jqXHR );
			} else {
				// mw.Api checks for response error.  Are there actually cases where the
				// request fails, but it's not caught there?
				return $.Deferred().reject( 'api-unexpected' );
			}
		}, function ( code, details ) {
			return $.Deferred().reject( 'api-fail', code, details );
		} ).promise();
	};

	mw.messagePoster.factory.register( 'wikitext', WikitextMessagePoster );
	mw.messagePoster.WikitextMessagePoster = WikitextMessagePoster;
}() );
