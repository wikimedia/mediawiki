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
		 * @return {Promise} See mw.Api#post
		 */
		login: function ( username, password ) {
			var params, api = this;

			params = {
				action: 'login',
				lgname: username,
				lgpassword: password
			};

			return api.post( params )
				.then( function ( data ) {
					params.lgtoken = data.login.token;
					return api.post( params )
						.then( function ( data ) {
							var code;
							if ( data.login && data.login.result === 'Success' ) {
								return data;
							} else {
								// Set proper error code whenever possible
								code = data.error && data.error.code || 'unknown';
								return Promise.reject( [ code, data ] );
							}
						} );
				} );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.login
	 */

}( mediaWiki, jQuery ) );
