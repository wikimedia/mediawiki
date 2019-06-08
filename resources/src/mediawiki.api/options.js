/**
 * @class mw.Api.plugin.options
 */
( function () {

	var saveOptionsRequests = {};

	$.extend( mw.Api.prototype, {

		/**
		 * Asynchronously save the value of a single user option using the API. See #saveOptions.
		 *
		 * @param {string} name
		 * @param {string|null} value
		 * @return {jQuery.Promise}
		 */
		saveOption: function ( name, value ) {
			var param = {};
			param[ name ] = value;
			return this.saveOptions( param );
		},

		/**
		 * Asynchronously save the values of user options using the API.
		 *
		 * If a value of `null` is provided, the given option will be reset to the default value.
		 *
		 * Any warnings returned by the API, including warnings about invalid option names or values,
		 * are ignored. However, do not rely on this behavior.
		 *
		 * If necessary, the options will be saved using several sequential API requests. Only one promise
		 * is always returned that will be resolved when all requests complete.
		 *
		 * If a request from a previous #saveOptions call is still pending, this will wait for it to be
		 * completed, otherwise MediaWiki gets sad. No requests are sent for anonymous users, as they
		 * would fail anyway. See T214963.
		 *
		 * @param {Object} options Options as a `{ name: value, … }` object
		 * @return {jQuery.Promise}
		 */
		saveOptions: function ( options ) {
			var name, value, bundleable,
				grouped = [],
				promise;

			// Logged-out users can't have user options; we can't depend on mw.user, that'd be circular
			if ( mw.config.get( 'wgUserName' ) === null ) {
				return $.Deferred().reject( 'notloggedin' ).promise();
			}

			// If another options request to this API is pending, wait for it first
			if (
				saveOptionsRequests[ this.defaults.ajax.url ] &&
				// Avoid long chains of promises, they may cause memory leaks
				saveOptionsRequests[ this.defaults.ajax.url ].state() === 'pending'
			) {
				promise = saveOptionsRequests[ this.defaults.ajax.url ].then( function () {
					// Don't expose the old promise's result, it would be confusing
					return $.Deferred().resolve();
				}, function () {
					return $.Deferred().resolve();
				} );
			} else {
				promise = $.Deferred().resolve();
			}

			for ( name in options ) {
				value = options[ name ] === null ? null : String( options[ name ] );

				// Can we bundle this option, or does it need a separate request?
				if ( this.defaults.useUS ) {
					bundleable = name.indexOf( '=' ) === -1;
				} else {
					bundleable =
						( value === null || value.indexOf( '|' ) === -1 ) &&
						( name.indexOf( '|' ) === -1 && name.indexOf( '=' ) === -1 );
				}

				if ( bundleable ) {
					if ( value !== null ) {
						grouped.push( name + '=' + value );
					} else {
						// Omitting value resets the option
						grouped.push( name );
					}
				} else {
					if ( value !== null ) {
						promise = promise.then( function ( name, value ) {
							return this.postWithToken( 'csrf', {
								formatversion: 2,
								action: 'options',
								optionname: name,
								optionvalue: value
							} );
						}.bind( this, name, value ) );
					} else {
						// Omitting value resets the option
						promise = promise.then( function ( name ) {
							return this.postWithToken( 'csrf', {
								formatversion: 2,
								action: 'options',
								optionname: name
							} );
						}.bind( this, name ) );
					}
				}
			}

			if ( grouped.length ) {
				promise = promise.then( function () {
					return this.postWithToken( 'csrf', {
						formatversion: 2,
						action: 'options',
						change: grouped
					} );
				}.bind( this ) );
			}

			saveOptionsRequests[ this.defaults.ajax.url ] = promise;

			return promise;
		}

	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.options
	 */

}() );
