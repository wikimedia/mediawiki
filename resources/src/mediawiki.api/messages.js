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
		 * @param {Array} messages Messages to retrieve
		 * @param {Object} [options] Additional parameters for the API call
		 * @return {jQuery.Promise}
		 */
		getMessages: function ( messages, options ) {
			options = options || {};
			return this.get( $.extend( {
				action: 'query',
				meta: 'allmessages',
				ammessages: messages,
				amlang: mw.config.get( 'wgUserLanguage' ),
				formatversion: 2
			}, options ) ).then( function ( data ) {
				var result = {};

				data.query.allmessages.forEach( function ( obj ) {
					if ( !obj.missing ) {
						result[ obj.name ] = obj.content;
					}
				} );

				return result;
			} );
		},

		/**
		 * Loads a set of messages and add them to mw.messages.
		 *
		 * @param {Array} messages Messages to retrieve
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
		 * @param {Array} messages Messages to retrieve
		 * @param {Object} [options] Additional parameters for the API call
		 * @return {jQuery.Promise}
		 */
		loadMessagesIfMissing: function ( messages, options ) {
			var missing = messages.filter( function ( msg ) {
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
