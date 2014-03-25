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
		 * @param {String} key
		 * @param {String} value If `value` is `undefined`, `null`, or `''`, then the cookie
		 *   is deleted
		 * @param {Date} [expire=wgCookieExpiration] The expiry date of the cookie. If
		 *   `expire` is `undefined`, then `wgCookieExpiration` is converted to milliseconds
		 *   and added to the current date. If `expire` is falsy, then a session cookie is
		 *   set
		 * @param {Object} [options]
		 * @param {String} [options.prefix=wgCookiePrefix] The prefix of the key
		 * @param {String} [options.domain=wgCookieDomain] The domain attribute of the
		 *   cookie
		 * @param {String} [options.path=wgCookiePath] The path attribute of the cookie
		 * @param {Boolean} [options.secure=wgCookieSecure] Whether or not to include the
		 *   secure attribute
		 */
		set: function ( key, value, expire, options ) {
			var config = mw.config.get( [
				'wgCookiePrefix',
				'wgCookieDomain',
				'wgCookiePath',
				'wgCookieSecure',
				'wgCookieExpiration' ] ),
				defaultOptions = {
					prefix: config.wgCookiePrefix,
					domain: config.wgCookieDomain,
					path: config.wgCookiePath,
					secure: config.wgCookieSecure
				};

			if ( value === '' ) {
				value = null;
			}

			options = options || {};

			if ( expire === undefined ) {
				expire = new Date();
				expire.setTime( +expire + ( config.wgCookieExpiration * 1000 ) );
			}

			options.expires = expire;
			options = $.extend( {}, defaultOptions, options );

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
		 * @param {String} key
		 * @param {String} [prefix=wgCookiePrefix] The prefix of the key. If `prefix` is
		 *   `undefined` or `null`, then `wgCookiePrefix` is used
		 * @param {String} [defaultValue]
		 * @return {String} If the cookie exists, then the value of the
		 *   cookie, otherwise `defaultValue`
		 */
		get: function ( key, prefix, defaultValue ) {
			var result;

			if ( prefix === undefined || prefix === null ) {
				prefix = mw.config.get( 'wgCookiePrefix' );
			}

			result = $.cookie( prefix + key );

			return result !== null ? result : defaultValue;
		}
	};

} ( mediaWiki, jQuery ) );
