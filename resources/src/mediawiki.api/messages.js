/**
 * Allows to retrieve a specific or a set of
 * messages to be added to mw.messages and returned
 * by the Api.
 *
 * @class mw.Api.plugin.messages
 * @since 1.27
 */
( function () {
	'use strict';

	$.extend( mw.Api.prototype, {
		/**
		 * Get a set of messages.
		 *
		 * @param {string|string[]} messages Messages to retrieve
		 * @param {Object} [options] Additional parameters for the API call
		 * @return {jQuery.Promise}
		 */
		getMessages: function ( messages, options ) {
			var that = this;
			options = options || {};
			messages = Array.isArray( messages ) ? messages : [ messages ];
			return this.get( $.extend( {
				action: 'query',
				meta: 'allmessages',
				ammessages: messages.slice( 0, 50 ),
				amlang: mw.config.get( 'wgUserLanguage' ),
				formatversion: 2
			}, options ) ).then( function ( data ) {
				var result = {};

				data.query.allmessages.forEach( function ( obj ) {
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
					function ( innerResult ) {
						// Merge result objects
						return $.extend( result, innerResult );
					}
				);
			} );
		},

		/**
		 * Loads a set of messages and add them to mw.messages.
		 *
		 * @param {string|string[]} messages Messages to retrieve
		 * @param {Object} [options] Additional parameters for the API call
		 * @return {jQuery.Promise}
		 */
		loadMessages: function ( messages, options ) {
			return this.getMessages( messages, options ).then( mw.messages.set.bind( mw.messages ) );
		},

		/**
		 * Loads a set of messages and add them to mw.messages. Only messages that are not already known
		 * are loaded. If all messages are known, the returned promise is resolved immediately.
		 *
		 * @param {string[]} messages Messages to retrieve
		 * @param {Object} [options] Additional parameters for the API call
		 * @return {jQuery.Promise}
		 */
		loadMessagesIfMissing: function ( messages, options ) {
			var missing = messages.filter( function ( msg ) {
				// eslint-disable-next-line mediawiki/msg-doc
				return !mw.message( msg ).exists();
			} );

			if ( missing.length === 0 ) {
				return $.Deferred().resolve();
			}

			return this.loadMessages( missing, options );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.messages
	 */

}() );
