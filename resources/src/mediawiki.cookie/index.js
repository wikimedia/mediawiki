'use strict';

var config = require( './config.json' ),
	defaults = {
		prefix: config.prefix,
		domain: config.domain,
		path: config.path,
		expires: config.expires,
		secure: false
	};

/**
 * Manage cookies in a way that is syntactically and functionally similar
 * to the `WebRequest#getCookie` and `WebResponse#setcookie` methods in PHP.
 *
 * @author Sam Smith <samsmith@wikimedia.org>
 * @author Matthew Flaschen <mflaschen@wikimedia.org>
 *
 * @class mw.cookie
 * @singleton
 */
mw.cookie = {

	/**
	 * Set or delete a cookie.
	 *
	 * **Note:** If explicitly passing `null` or `undefined` for an options key,
	 * that will override the default. This is natural in JavaScript, but noted
	 * here because it is contrary to MediaWiki's `WebResponse#setcookie()` method
	 * in PHP.
	 *
	 * When using this for persistent storage of identifiers (e.g. for tracking
	 * sessions), be aware that persistence may vary slightly across browsers and
	 * browser versions, and can be affected by a number of factors such as
	 * storage limits (cookie eviction) and session restore features.
	 *
	 * Without an expiry, this creates a session cookie. In a browser, session cookies persist
	 * for the lifetime of the browser *process*. Including across tabs, page views, and windows,
	 * until the browser itself is *fully* closed, or until the browser clears all storage for
	 * a given website. An exception to this is if the user evokes a "restore previous
	 * session" feature that some browsers have.
	 *
	 * @param {string} key
	 * @param {string|null} value Value of cookie. If `value` is `null` then this method will
	 *   instead remove a cookie by name of `key`.
	 * @param {Object|Date|number} [options] Options object, or expiry date
	 * @param {Date|number|null} [options.expires=wgCookieExpiration] The expiry date of the cookie,
	 *  or lifetime in seconds. If `options.expires` is null or 0, then a session cookie is set.
	 * @param {string} [options.prefix=wgCookiePrefix] The prefix of the key
	 * @param {string} [options.domain=wgCookieDomain] The domain attribute of the cookie
	 * @param {string} [options.path=wgCookiePath] The path attribute of the cookie
	 * @param {boolean} [options.secure=false] Whether or not to include the secure attribute.
	 *   (Does **not** use the wgCookieSecure configuration variable)
	 */
	set: function ( key, value, options ) {
		var date;

		// The 'options' parameter may be a shortcut for the expiry.
		if ( arguments.length > 2 && ( !options || options instanceof Date || typeof options === 'number' ) ) {
			options = { expires: options };
		}
		// Apply defaults
		options = $.extend( {}, defaults, options );

		// Handle prefix
		key = options.prefix + key;
		// Don't pass invalid option to $.cookie
		delete options.prefix;

		if ( !options.expires ) {
			// Session cookie (null or zero)
			// Normalize to absent (undefined) for $.cookie.
			delete options.expires;
		} else if ( typeof options.expires === 'number' ) {
			// Lifetime in seconds
			date = new Date();
			date.setTime( Number( date ) + ( options.expires * 1000 ) );
			options.expires = date;
		}

		if ( value !== null ) {
			value = String( value );
		}

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
			prefix = defaults.prefix;
		}

		// Was defaultValue omitted?
		if ( arguments.length < 3 ) {
			defaultValue = null;
		}

		result = $.cookie( prefix + key );

		return result !== null ? result : defaultValue;
	}
};

if ( window.QUnit ) {
	module.exports = {
		setDefaults: function ( value ) {
			var prev = defaults;
			defaults = value;
			return prev;
		}
	};
}
