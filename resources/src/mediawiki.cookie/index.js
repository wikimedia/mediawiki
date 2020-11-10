'use strict';

var config = require( './config.json' ),
	defaults = {
		prefix: config.prefix,
		domain: config.domain,
		path: config.path,
		expires: config.expires,
		secure: false,
		sameSite: '',
		sameSiteLegacy: config.sameSiteLegacy
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
	 * @param {string} [options.sameSite=''] The SameSite flag of the cookie ('None' / 'Lax'
	 *   / 'Strict', case-insensitive; default is to omit the flag, which results in Lax on
	 *   modern browsers). Set to None AND set secure=true if the cookie needs to be visible on
	 *   cross-domain requests.
	 * @param {boolean} [options.sameSiteLegacy=$wgUseSameSiteLegacyCookies] If true, sameSite=None
	 *   cookies will also be sent as a non-SameSite cookie with an 'ss0-' prefix, to work around
	 *   old browsers interpreting the standard differently.
	 */
	set: function ( key, value, options ) {
		var prefix, date, sameSiteLegacy;

		// The 'options' parameter may be a shortcut for the expiry.
		if ( arguments.length > 2 && ( !options || options instanceof Date || typeof options === 'number' ) ) {
			options = { expires: options };
		}
		// Apply defaults
		options = $.extend( {}, defaults, options );

		// Don't pass invalid option to $.cookie
		prefix = options.prefix;
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

		sameSiteLegacy = options.sameSiteLegacy;
		delete options.sameSiteLegacy;

		if ( value !== null ) {
			value = String( value );
		}

		$.cookie( prefix + key, value, options );
		if ( sameSiteLegacy && options.sameSite && options.sameSite.toLowerCase() === 'none' ) {
			// Make testing easy by not changing the object passed to the first $.cookie call
			options = $.extend( {}, options );
			delete options.sameSite;
			$.cookie( prefix + 'ss0-' + key, value, options );
		}
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
	},

	/**
	 * Get the value of a SameSite=None cookie, using the legacy ss0- cookie if needed.
	 *
	 * @param {string} key
	 * @param {string} [prefix=wgCookiePrefix] The prefix of the key. If `prefix` is
	 *   `undefined` or `null`, then `wgCookiePrefix` is used
	 * @param {Mixed} [defaultValue=null]
	 * @return {string|null|Mixed} If the cookie exists, then the value of the
	 *   cookie, otherwise `defaultValue`
	 */
	getCrossSite: function ( key, prefix, defaultValue ) {
		var value;

		value = this.get( key, prefix, null );
		if ( value === null ) {
			value = this.get( 'ss0-' + key, prefix, null );
		}
		if ( value === null ) {
			value = defaultValue;
		}
		return value;
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
