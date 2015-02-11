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
			var params, apiPromise,
				api = this;

			params = {
				action: 'login',
				lgname: username,
				lgpassword: password
			};

			apiPromise = api.post( params );
			return apiPromise
				.then( function ( data ) {
					params.lgtoken = data.login.token;
					return api.post( params )
						.then( function ( data ) {
							var code;
							if ( data.login.result !== 'Success' ) {
								// Set proper error code whenever possible
								code = data.error && data.error.code || 'unknown';
								return $.Deferred().reject( code, data );
							}
							return data;
						} );
				} )
				.promise( { abort: apiPromise.abort } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.login
	 */

}( mediaWiki, jQuery ) );
