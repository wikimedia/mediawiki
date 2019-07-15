( function () {
	/**
	 * This is the abstract base class for MessagePoster implementations.
	 *
	 * @abstract
	 * @class
	 *
	 * @constructor
	 * @param {mw.Title} title Title to post to
	 */
	mw.messagePoster.MessagePoster = function MwMessagePoster() {};

	OO.initClass( mw.messagePoster.MessagePoster );

	/**
	 * Post a message (with subject and body) to a talk page.
	 *
	 * @abstract
	 * @param {string} subject Subject/topic title.  The amount of wikitext supported is
	 *   implementation-specific. It is recommended to only use basic wikilink syntax for
	 *   maximum compatibility.
	 * @param {string} body Body, as wikitext.  Signature code will automatically be added
	 *   by MessagePosters that require one, unless the message already contains the string
	 *   ~~~.
	 * @param {Object} [options] Message options. See MessagePoster implementations for details.
	 * @return {jQuery.Promise} Promise completing when the post succeeds or fails.
	 *   For failure, will be rejected with three arguments:
	 *
	 *   - primaryError - Primary error code.  For a mw.Api failure,
	 *       this should be 'api-fail'.
	 *   - secondaryError - Secondary error code.  For a mw.Api failure,
	 *       this, should be mw.Api's code, e.g. 'http', 'ok-but-empty', or the error passed through
	 *       from the server.
	 *   - details - Further details about the error
	 *
	 * @localdoc
	 * The base class currently does nothing, but could be used for shared analytics or
	 * something.
	 */
	mw.messagePoster.MessagePoster.prototype.post = function () {};
}() );
