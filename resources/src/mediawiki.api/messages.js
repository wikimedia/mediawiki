( function () {
	'use strict';

	Object.assign( mw.Api.prototype, /** @lends mw.Api.prototype */ {
		/**
		 * Get a set of messages.
		 *
		 * @since 1.27
		 * @param {string|string[]} messages Messages to retrieve
		 * @param {Object} [options] Additional parameters for the API call
		 * @return {jQuery.Promise<Object.<string, string>>}
		 */
		getMessages: function ( messages, options ) {
			const that = this;
			options = options || {};
			messages = Array.isArray( messages ) ? messages : [ messages ];
			return this.get( Object.assign( {
				action: 'query',
				meta: 'allmessages',
				ammessages: messages.slice( 0, 50 ),
				amlang: mw.config.get( 'wgUserLanguage' ),
				formatversion: 2
			}, options ) ).then( ( data ) => {
				const result = {};

				data.query.allmessages.forEach( ( obj ) => {
					if ( !obj.missing ) {
						result[ obj.name ] = obj.content;
					}
				} );

				// If no more messages are needed, return now, otherwise calls
				// itself recursively, because only 50 messages can be loaded
				// at a time. This limit of 50 comes from ApiBase::LIMIT_SML1;
				// ApiQueryAllMessages sets the 'ammessages' parameter to include
				// multiple values, and for users without the `apihighlimits` right
				// LIMIT_SML1 is the limit imposed on the number of values.
				if ( messages.length <= 50 ) {
					return result;
				}

				return that.getMessages( messages.slice( 50 ), options ).then(
					// Merge result objects
					( innerResult ) => Object.assign( result, innerResult )
				);
			} );
		},

		/**
		 * Loads a set of messages and add them to {@link mw.messages}.
		 *
		 * @param {string|string[]} messages Messages to retrieve
		 * @param {Object} [options] Additional parameters for the API call
		 * @return {jQuery.Promise}
		 */
		loadMessages: function ( messages, options ) {
			return this.getMessages( messages, options ).then( mw.messages.set.bind( mw.messages ) );
		},

		/**
		 * Loads a set of messages and add them to {@link mw.messages}. Only messages that are not already known
		 * are loaded. If all messages are known, the returned promise is resolved immediately.
		 *
		 * @since 1.27
		 * @param {string|string[]} messages Messages to retrieve
		 * @param {Object} [options] Additional parameters for the API call
		 * @return {jQuery.Promise}
		 */
		loadMessagesIfMissing: function ( messages, options ) {
			messages = Array.isArray( messages ) ? messages : [ messages ];
			const missing = messages.filter(
				// eslint-disable-next-line mediawiki/msg-doc
				( msg ) => !mw.message( msg ).exists()
			);

			if ( missing.length === 0 ) {
				return $.Deferred().resolve();
			}

			return this.loadMessages( missing, options );
		}
	} );

}() );
