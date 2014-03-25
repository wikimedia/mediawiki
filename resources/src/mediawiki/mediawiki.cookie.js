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
		 * Note well that the default values for the `options.prefix`, `.domain`, `.path`
		 * and `.secure` parameters are only used when the parameter is `undefined`; `null`
		 * values aren't filtered out as they are in `WebResponse#setcookie`.
		 *
		 * @param {string} key
		 * @param {string} value Value of cookie. If `value` is `undefined`, `null`, or `''`,
		 *   then the cookie is deleted
		 * @param {Date} [expire=wgCookieExpiration] The expiry date of the cookie. If
		 *   `expire` is `undefined`, then the cookie expires `wgCookieExpiration` seconds
		 *   from now. If `expire` is any other falsy value, then a session cookie (expires
		 *   when the browser closes) is set.
		 * @param {Object} [options]
		 * @param {string} [options.prefix=wgCookiePrefix] The prefix of the key
		 * @param {string} [options.domain=wgCookieDomain] The domain attribute of the
		 *   cookie
		 * @param {string} [options.path=wgCookiePath] The path attribute of the cookie
		 * @param {boolean} [options.secure=false] Whether or not to include the
		 *   secure attribute; it defaults to false, **not** wgCookieSecure
		 */
		set: function ( key, value, expire, options ) {
			var config, defaultOptions;

			// wgCookieSecure is not used for now, since 'detect' could not work with
			// ResourceLoaderStartUpModule, since that module is not protocol-specific.
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

			if ( expire === undefined ) {
				expire = new Date();
				expire.setTime( Number( expire ) + ( config.wgCookieExpiration * 1000 ) );
			}

			options = $.extend( defaultOptions, options );
			options.expires = expire;

			$.cookie( options.prefix + key, value, {
				expires: options.expires,
				path: options.path,
				domain: options.domain,
				secure: options.secure
			} );
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
