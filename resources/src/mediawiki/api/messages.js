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
		 * @param {Array} messages Messages to retrieve
		 * @return {jQuery.Promise}
		 *  parameter)
		 */
		getMessages: function ( messages ) {
			var api = this,
				apiPromise;

			apiPromise = api.get( {
				action: 'query',
				meta: 'allmessages',
				ammessages: messages.join( '|' ),
				amlang: mw.config.get('wgUserLanguage')
			} );
			
			return apiPromise.then( function ( data ) {
				var result = [];
				if ( data && data.query && data.query.allmessages ) {
					$.each( data.query.allmessages, function ( i, obj ) {
						if ( obj['*'] ) {
							mw.messages.set( obj.name, obj['*'] );
							result[obj.name] = obj['*'];
						}
					} );
				}
				return result;
			} );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.messages
	 */

}( mediaWiki, jQuery ) );
