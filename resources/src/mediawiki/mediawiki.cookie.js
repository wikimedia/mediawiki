( function ( mw, $ ) {
	'use strict';

	/**
	 * Provides an API for getting and setting cookies that is
	 * syntactically and functionally similar to the server-side cookie
	 * API (`WebRequest#getCookie` and `WebResponse#setcookie`).
	 *
	 * @author Sam Smith <samsmith@wikimedia.org>
	 *
	 * @class mw.cookie
	 * @singleton
	 */
	mw.cookie = {

		/**
		 * Sets or deletes a cookie.
		 *
		 * While this is natural in JavaScript, contrary to `WebResponse#setcookie` in PHP, the
		 * default values for the `options` properties only apply if that property isn't set
		 * already in your options object (e.g. passing `{ secure: null }` or `{ secure: undefined }`
		 * overrides the default value for `options.secure`).
		 *
		 * @param {string} key
		 * @param {string} value Value of cookie. If `value` is `undefined`, `null`, or `''`,
		 *   then the cookie is removed
		 * @param {Object|Date} [options] Options object, or expiry date
		 * @param {Date|boolean} [options.expires=wgCookieExpiration] The expiry date of the cookie.
		 *   By default the cookie expires `wgCookieExpiration` seconds from now. If set to null,
		 *   then a session cookie is set (expires when the browser is closed).
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

			// To match server behavior; see method documentation
			if ( value === '' ) {
				value = null;
			}

			// Options argument can also be a shortcut for the expiry
			// Expiry can be a Date or null
			if ( $.type( options ) !== 'object' ) {
				// Also takes care of options = undefined, in which case we also don't need $.extend()
				defaultOptions.expires = options;
				options = defaultOptions;
			} else {
				options = $.extend( defaultOptions, options );
			}

			// $.cookie makes session cookies when expiry is omitted,
			// however our default is to expire wgCookieExpiration seconds from now.
			if ( options.expires === undefined ) {
				date = new Date();
				date.setTime( Number( date ) + ( config.wgCookieExpiration * 1000 ) );
				options.expires = date;
			} else if ( options.expires === null ) {
				// $.cookie makes a session cookie when expires is omitted
				delete options.expires;
			}

			// Process prefix
			key = options.prefix + key;
			delete options.prefix;

			// Other options are handled by $.cookie
			$.cookie( key, value, options );
		},

		/**
		 * Gets the value of a cookie.
		 *
		 * @param {string} key
		 * @param {string} [prefix=wgCookiePrefix] The prefix of the key. If `prefix` is
		 *   `undefined` or `null`, then `wgCookiePrefix` is used
		 * @param {Mixed} [defaultValue=null]
		 * @return {string} If the cookie exists, then the value of the
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

} ( mediaWiki, jQuery ) );
