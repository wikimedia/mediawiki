( function () {
	/**
	 * Factory for MessagePoster objects. This provides a pluggable to way to script the action
	 * of adding a message to someone's talk page.
	 *
	 * Usage example:
	 *
	 *   function MyExamplePoster() {}
	 *   OO.inheritClass( MyExamplePoster, mw.messagePoster.MessagePoster );
	 *
	 *   mw.messagePoster.factory.register( 'mycontentmodel', MyExamplePoster );
	 *
	 * The JavaScript files(s) that register message posters for additional content
	 * models must be registered with MediaWiki via the `MessagePosterModule`
	 * extension attribute, like follows:
	 *
	 *    "MessagePosterModule": {
	 *         "localBasePath": "", // (required)
	 *         "scripts": [], // relative file path(s) (required)
	 *         "dependencies": [], // module name(s) (optional)
	 *    }
	 *
	 * @class mw.messagePoster.factory
	 * @singleton
	 */
	function MessagePosterFactory() {
		this.contentModelToClass = Object.create( null );
	}

	OO.initClass( MessagePosterFactory );

	// Note: This registration scheme is currently not compatible with LQT, since that doesn't
	// have its own content model, just islqttalkpage. LQT pages will be passed to the wikitext
	// MessagePoster.
	/**
	 * Register a MessagePoster subclass for a given content model.
	 *
	 * @param {string} contentModel Content model of pages this MessagePoster can post to
	 * @param {Function} constructor Constructor of a MessagePoster subclass
	 */
	MessagePosterFactory.prototype.register = function ( contentModel, constructor ) {
		if ( this.contentModelToClass[ contentModel ] ) {
			throw new Error( 'Content model "' + contentModel + '" is already registered' );
		}

		this.contentModelToClass[ contentModel ] = constructor;
	};

	/**
	 * Unregister a given content model.
	 * This is exposed for testing and should not normally be used.
	 *
	 * @param {string} contentModel Content model to unregister
	 */
	MessagePosterFactory.prototype.unregister = function ( contentModel ) {
		delete this.contentModelToClass[ contentModel ];
	};

	/**
	 * Create a MessagePoster for given a title.
	 *
	 * A promise for this is returned. It works by determining the content model, then loading
	 * the corresponding module (which registers the MessagePoster class), and finally constructing
	 * an object for the given title.
	 *
	 * This does not require the message and should be called as soon as possible, so that the
	 * API and ResourceLoader requests run in the background.
	 *
	 * @param {mw.Title} title Title that will be posted to
	 * @param {string} [apiUrl] api.php URL if the title is on another wiki
	 * @return {jQuery.Promise} Promise resolving to a mw.messagePoster.MessagePoster.
	 *   For failure, rejected with up to three arguments:
	 *
	 *   - errorCode Error code string
	 *   - error Error explanation
	 *   - details Further error details
	 */
	MessagePosterFactory.prototype.create = function ( title, apiUrl ) {
		var factory = this,
			api = apiUrl ? new mw.ForeignApi( apiUrl ) : new mw.Api();

		return api.get( {
			formatversion: 2,
			action: 'query',
			prop: 'info',
			titles: title.getPrefixedDb()
		} ).then( function ( data ) {
			var contentModel, page = data.query.pages[ 0 ];
			if ( !page ) {
				return $.Deferred().reject( 'unexpected-response', 'Unexpected API response' );
			}
			contentModel = page.contentmodel;
			if ( !factory.contentModelToClass[ contentModel ] ) {
				return $.Deferred().reject( 'content-model-unknown', 'No handler for "' + contentModel + '"' );
			}
			return new factory.contentModelToClass[ contentModel ]( title, api );
		}, function ( error, details ) {
			return $.Deferred().reject( 'content-model-query-failed', error, details );
		} );
	};

	mw.messagePoster = {
		factory: new MessagePosterFactory()
	};
}() );
