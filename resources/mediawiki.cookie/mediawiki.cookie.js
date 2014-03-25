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
		 * Sets a cookie.
		 *
		 * @param {String} key
		 * @param {String} value
		 * @param {Number} [expire] The number of *seconds* until the cookie expires. If
		 *   `expire` is 0, then `wgCookieExpiration` is used. If `expire` is `null` or
		 *   `undefined`, or `wgCookieExpiration` is falsy, then a session cookie is set
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
				},
				seconds;

			options = options || {};

			if ( expire === 0 ) {
				expire = config.wgCookieExpiration ? config.wgCookieExpiration : null;
			}

			if ( expire > 0 ) {
				seconds = expire;
				expire = new Date();
				expire.setTime( ( +expire + seconds ) * 1000 );
			}

			options.expires = expire;
			options = $.extend( {}, defaultOptions, options );

			$.cookie( options.prefix + key, value, options );
		},

		/**
		 * Gets the value of a cookie.
		 *
		 * @param {String} key
		 * @param {String} [prefix=wgCookiePrefix] The prefix of the key
		 * @param {String} [defaultValue]
		 * @return {String} If the cookie exists, then the value of the
		 *   cookie, otherwise `defaultValue`
		 */
		get: function ( key, prefix, defaultValue ) {
			if ( prefix === undefined || prefix === null ) {
				prefix = mw.config.get( 'wgCookiePrefix' );
			}

			return $.cookie( prefix + key ) || defaultValue;
		}
	};

} ( mediaWiki, jQuery ) );
