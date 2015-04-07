/*global OO*/
( function ( mw, $ ) {
	/**
	 * This is a factory for MessagePoster objects, which allows a pluggable to way to script leaving a
	 * talk page message.
	 *
	 * @class mw.messagePoster.factory
	 * @singleton
	 */
	function MwMessagePosterFactory() {
		this.api = new mw.Api();
		this.contentModelToClass = {};
	}

	OO.initClass( MwMessagePosterFactory );

	// Note: This registration scheme is currently not compatible with LQT, since that doesn't
	// have its own content model, just islqttalkpage.  LQT pages will be passed to the wikitext
	// MessagePoster.
	/**
	 * Registers a MessagePoster subclass for a given content model.
	 *
	 * @param {string} contentModel Content model of pages this MessagePoster can post to
	 * @param {Function} messagePosterConstructor Constructor for MessagePoster
	 */
	MwMessagePosterFactory.prototype.register = function ( contentModel, messagePosterConstructor ) {
		if ( this.contentModelToClass[contentModel] !== undefined ) {
			throw new Error( 'The content model \'' + contentModel + '\' is already registered.' );
		}

		this.contentModelToClass[contentModel] = messagePosterConstructor;
	};

	/**
	 * Unregisters a given content model
	 * This is exposed for testing and should not normally be needed.
	 *
	 * @param {string} contentModel Content model to unregister
	 */
	MwMessagePosterFactory.prototype.unregister = function ( contentModel ) {
		delete this.contentModelToClass[contentModel];
	};

	/**
	 * Creates a MessagePoster, given a title.  A promise for this is returned.
	 * This works by determining the content model, then loading the corresponding
	 * module (which will register the MessagePoster class), and finally constructing it.
	 *
	 * This does not require the message and should be called as soon as possible, so it does the
	 * API and ResourceLoader requests in the background.
	 *
	 * @param {mw.Title} title Title that will be posted to
	 * @return {jQuery.Promise} Promise resolving to a mw.messagePoster.MessagePoster.
	 *   For failure, rejected with up to three arguments:
	 *
	 *   - errorCode Error code string
	 *   - error Error explanation
	 *   - details Further error details
	 */
	MwMessagePosterFactory.prototype.create = function ( title ) {
		var pageId, page, contentModel, moduleName,
			factory = this;

		return this.api.get( {
			action: 'query',
			prop: 'info',
			indexpageids: 1,
			titles: title.getPrefixedDb()
		} ).then( function ( result ) {
			if ( result.query.pageids.length > 0 ) {
				pageId = result.query.pageids[0];
				page = result.query.pages[pageId];

				contentModel = page.contentmodel;
				moduleName = 'mediawiki.messagePoster.' + contentModel;
				return mw.loader.using( moduleName ).then( function () {
					return factory.createForContentModel(
						contentModel,
						title
					);
				}, function () {
					return $.Deferred().reject( 'failed-to-load-module', 'Failed to load the \'' + moduleName + '\' module' );
				} );
			} else {
				return $.Deferred().reject( 'unexpected-response', 'Unexpected API response' );
			}
		}, function ( errorCode, details ) {
			return $.Deferred().reject( 'content-model-query-failed', errorCode, details );
		} ).promise();
	};

	/**
	 * Creates a MessagePoster instance, given a title and content model
	 *
	 * @private
	 *
	 * @param {string} contentModel Content model of title
	 * @param {mw.Title} title Title being posted to
	 * @return {mw.messagePoster.MessagePoster}
	 *
	 */
	MwMessagePosterFactory.prototype.createForContentModel = function ( contentModel, title ) {
		return new this.contentModelToClass[contentModel]( title );
	};

	mw.messagePoster = {
		factory: new MwMessagePosterFactory()
	};
}( mediaWiki, jQuery ) );
