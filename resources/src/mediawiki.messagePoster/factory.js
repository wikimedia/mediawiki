( function ( mw, $ ) {
	/**
	 * Factory for MessagePoster objects. This provides a pluggable to way to script the action
	 * of adding a message to someone's talk page.
	 *
	 * @class mw.messagePoster.factory
	 * @singleton
	 */
	function MessagePosterFactory() {
		this.contentModelToClass = {};
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
		if ( this.contentModelToClass[ contentModel ] !== undefined ) {
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
			var contentModel, moduleName, page = data.query.pages[ 0 ];
			if ( !page ) {
				return $.Deferred().reject( 'unexpected-response', 'Unexpected API response' );
			}
			contentModel = page.contentmodel;
			moduleName = 'mediawiki.messagePoster.' + contentModel;
			return mw.loader.using( moduleName ).then( function () {
				return factory.createForContentModel(
					contentModel,
					title,
					api
				);
			}, function () {
				return $.Deferred().reject( 'failed-to-load-module', 'Failed to load "' + moduleName + '"' );
			} );
		}, function ( error, details ) {
			return $.Deferred().reject( 'content-model-query-failed', error, details );
		} );
	};

	/**
	 * Creates a MessagePoster instance, given a title and content model
	 *
	 * @private
	 * @param {string} contentModel Content model of title
	 * @param {mw.Title} title Title being posted to
	 * @param {mw.Api} api mw.Api instance that the instance should use
	 * @return {mw.messagePoster.MessagePoster}
	 */
	MessagePosterFactory.prototype.createForContentModel = function ( contentModel, title, api ) {
		return new this.contentModelToClass[ contentModel ]( title, api );
	};

	mw.messagePoster = {
		factory: new MessagePosterFactory()
	};
}( mediaWiki, jQuery ) );
