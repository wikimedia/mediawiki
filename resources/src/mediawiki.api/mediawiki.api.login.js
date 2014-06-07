/**
 * Make the two-step login easier.
 * @author Niklas Laxström
 * @class mw.Api.plugin.login
 * @since 1.22
 */
( function ( mw, $ ) {
	'use strict';

	$.extend( mw.Api.prototype, {
		/**
		 * @param {string} username
		 * @param {string} password
		 * @return {jQuery.Promise} See mw.Api#post
		 */
		login: function ( username, password ) {
			var params, request,
				deferred = $.Deferred(),
				api = this;

			params = {
				action: 'login',
				lgname: username,
				lgpassword: password
			};

			request = api.post( params );
			request.fail( deferred.reject );
			request.done( function ( data ) {
				params.lgtoken = data.login.token;
				api.post( params )
					.fail( deferred.reject )
					.done( function ( data ) {
						var code;
						if ( data.login && data.login.result === 'Success' ) {
							deferred.resolve( data );
						} else {
							// Set proper error code whenever possible
							code = data.error && data.error.code || 'unknown';
							deferred.reject( code, data );
						}
					} );
			} );

			return deferred.promise( { abort: request.abort } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.login
	 */

}( mediaWiki, jQuery ) );
