( function () {
	'use strict';

	/**
	 * Provides an API for getting and setting cookies that is
	 * syntactically and functionally similar to the server-side cookie
	 * API (`WebRequest#getCookie` and `WebResponse#setcookie`).
	 *
	 * @author Sam Smith <samsmith@wikimedia.org>
	 * @author Matthew Flaschen <mflaschen@wikimedia.org>
	 * @author Timo Tijhof <krinklemail@gmail.com>
	 *
	 * @class mw.cookie
	 * @singleton
	 */
	mw.cookie = {

		/**
		 * Set or delete a cookie.
		 *
		 * While this is natural in JavaScript, contrary to `WebResponse#setcookie` in PHP, the
		 * default values for the `options` properties only apply if that property isn't set
		 * already in your options object (e.g. passing `{ secure: null }` or `{ secure: undefined }`
		 * overrides the default value for `options.secure`).
		 *
		 * @param {string} key
		 * @param {string|null} value Value of cookie. If `value` is `null` then this method will
		 *   instead remove a cookie by name of `key`.
		 * @param {Object|Date} [options] Options object, or expiry date
		 * @param {Date|number|null} [options.expires] The expiry date of the cookie, or lifetime in seconds.
		 *
		 *   If `options.expires` is null, then a session cookie is set.
		 *
		 *   By default cookie expiration is based on `wgCookieExpiration`. Similar to `WebResponse`
		 *   in PHP, we set a session cookie if `wgCookieExpiration` is 0. And for non-zero values
		 *   it is interpreted as lifetime in seconds.
		 *
		 * @param {string} [options.prefix=wgCookiePrefix] The prefix of the key
		 * @param {string} [options.domain=wgCookieDomain] The domain attribute of the cookie
		 * @param {string} [options.path=wgCookiePath] The path attribute of the cookie
		 * @param {boolean} [options.secure=false] Whether or not to include the secure attribute.
		 *   (Does **not** use the wgCookieSecure configuration variable)
		 */
		set: function ( key, value, options ) {
			var config, defaultOptions, date;

			// wgCookieSecure is not used for now, since 'detect' could not work with
			// ResourceLoaderStartUpModule, as module cache is not fragmented by protocol.
			config = mw.config.get( [
				'wgCookiePrefix',
				'wgCookieDomain',
				'wgCookiePath',
				'wgCookieExpiration'
			] );

			defaultOptions = {
				prefix: config.wgCookiePrefix,
				domain: config.wgCookieDomain,
				path: config.wgCookiePath,
				secure: false
			};

			// Options argument can also be a shortcut for the expiry
			// Expiry can be a Date or null
			if ( $.type( options ) !== 'object' ) {
				// Also takes care of options = undefined, in which case we also don't need $.extend()
				defaultOptions.expires = options;
				options = defaultOptions;
			} else {
				options = $.extend( defaultOptions, options );
			}

			// Default to using wgCookieExpiration (lifetime in seconds).
			// If wgCookieExpiration is 0, that is considered a special value indicating
			// all cookies should be session cookies by default.
			if ( options.expires === undefined && config.wgCookieExpiration !== 0 ) {
				date = new Date();
				date.setTime( Number( date ) + ( config.wgCookieExpiration * 1000 ) );
				options.expires = date;
			} else if ( typeof options.expires === 'number' ) {
				// Lifetime in seconds
				date = new Date();
				date.setTime( Number( date ) + ( options.expires * 1000 ) );
				options.expires = date;
			} else if ( options.expires === null ) {
				// $.cookie makes a session cookie when options.expires is omitted
				delete options.expires;
			}

			// Process prefix
			key = options.prefix + key;
			delete options.prefix;

			// Process value
			if ( value !== null ) {
				value = String( value );
			}

			// Other options are handled by $.cookie
			$.cookie( key, value, options );
		},

		/**
		 * Get the value of a cookie.
		 *
		 * @param {string} key
		 * @param {string} [prefix=wgCookiePrefix] The prefix of the key. If `prefix` is
		 *   `undefined` or `null`, then `wgCookiePrefix` is used
		 * @param {Mixed} [defaultValue=null]
		 * @return {string|null|Mixed} If the cookie exists, then the value of the
		 *   cookie, otherwise `defaultValue`
		 */
		get: function ( key, prefix, defaultValue ) {
			var result;

			if ( prefix === undefined || prefix === null ) {
				prefix = mw.config.get( 'wgCookiePrefix' );
			}

			// Was defaultValue omitted?
			if ( arguments.length < 3 ) {
				defaultValue = null;
			}

			result = $.cookie( prefix + key );

			return result !== null ? result : defaultValue;
		}
	};

}() );
