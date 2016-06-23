/**
 * Allows to retrieve a specific or a set of
 * messages to be added to mw.messages and returned
 * by the Api.
 *
 * @class mw.Api.plugin.messages
 * @since 1.27
 */
( function ( mw, $ ) {
	'use strict';

	$.extend( mw.Api.prototype, {
		/**
		 * Get a set of messages.
		 *
		 * @param {Array} messages Messages to retrieve
		 * @return {jQuery.Promise}
		 */
		getMessages: function ( messages ) {
			return this.get( {
				action: 'query',
				meta: 'allmessages',
				ammessages: messages,
				amlang: mw.config.get( 'wgUserLanguage' ),
				formatversion: 2
			} ).then( function ( data ) {
				var result = {};

				$.each( data.query.allmessages, function ( i, obj ) {
					if ( !obj.missing ) {
						result[ obj.name ] = obj.content;
					}
				} );

				return result;
			} );
		},

		/**
		 * Loads a set of mesages and add them to mw.messages.
		 *
		 * @param {Array} messages Messages to retrieve
		 * @return {jQuery.Promise}
		 */
		loadMessages: function ( messages ) {
			return this.getMessages( messages ).then( $.proxy( mw.messages, 'set' ) );
		},

		/**
		 * Loads a set of mesages and add them to mw.messages. Only messages that are not already known
		 * are loaded. If all messages are known, the returned promise is resolved immediately.
		 *
		 * @param {Array} messages Messages to retrieve
		 * @return {jQuery.Promise}
		 */
		loadMessagesIfMissing: function ( messages ) {
			var missing = messages.filter( function ( msg ) {
				return !mw.message( msg ).exists();
			} );

			if ( missing.length === 0 ) {
				return $.Deferred().resolve();
			}

			return this.getMessages( missing ).then( $.proxy( mw.messages, 'set' ) );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.messages
	 */

}( mediaWiki, jQuery ) );
