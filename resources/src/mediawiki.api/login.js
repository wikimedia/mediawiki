( function () {
	'use strict';

	Object.assign( mw.Api.prototype, /** @lends mw.Api.prototype */ {
		/**
		 * @param {string} username
		 * @param {string} password
		 * @return {jQuery.Promise} See [post()]{@link mw.Api#post}
		 */
		login: function ( username, password ) {
			const params = {
				action: 'login',
				lgname: username,
				lgpassword: password
			};

			const apiPromise = this.post( params );

			let innerPromise;
			return apiPromise
				.then( ( data ) => {
					params.lgtoken = data.login.token;
					innerPromise = this.post( params )
						.then( ( response ) => {
							let code;
							if ( response.login.result !== 'Success' ) {
								// Set proper error code whenever possible
								code = response.error && response.error.code || 'unknown';
								return $.Deferred().reject( code, response );
							}
							return response;
						} );
					return innerPromise;
				} )
				.promise( {
					abort: function () {
						apiPromise.abort();
						if ( innerPromise ) {
							innerPromise.abort();
						}
					}
				} );
		}
	} );

}() );
