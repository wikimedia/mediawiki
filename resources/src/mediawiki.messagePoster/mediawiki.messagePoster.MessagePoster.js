( function ( mw ) {
	/**
	 * This is the abstract base class for MessagePoster implementations.
	 *
	 * @abstract
	 *
	 * @param {mw.Title} title Title to post to
	 */
	mw.messagePoster.MessagePoster = function MwMessagePoster( title ) {};

	OO.initClass( mw.messagePoster.MessagePoster );

	/**
	 * Posts a message (with subject and body) to a talk page.
	 *
	 * @param {string} subject Subject/topic title; plaintext only (no wikitext or HTML)
	 * @param {string} body	Body, as wikitext.  Signature code will automatically be added
	 *   by MessagePosters that require one, unless the message already contains the string
	 *   ~~~.
	 * @return {jQuery.Promise} Promise completing when the post succeeds or fails.
	 * @return {Function} return.done
	 * @return {Function} return.fail
	 * @return {string} return.fail.primaryError Primary error code.  For a mw.Api failure,
	 *   this, should be 'api-fail'.
	 * @return {string} return.fail.secondaryError Secondary error code.  For a mw.Api failure,
	 *   this, should be mw.Api's code, e.g. 'http', 'ok-but-empty', or the error passed through
	 *   from the server.
	 * @return {Mixed} return.fail.details Further details about the error
	 *
	 * @localdoc
	 * The base class currently does nothing, but could be used for shared analytics or
	 * something.
	 */
	mw.messagePoster.MessagePoster.prototype.post = function ( subject, body ) {};
}( mediaWiki ) );
