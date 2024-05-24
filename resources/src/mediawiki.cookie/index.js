'use strict';

var config = require( './config.json' ),
	defaults = {
		prefix: config.prefix,
		domain: config.domain,
		path: config.path,
		expires: config.expires,
		secure: false,
		sameSite: ''
	},
	jar = require( './jar.js' );

// define jQuery Cookie methods
require( './jquery.js' );

/**
 * Manage cookies in a way that is syntactically and functionally similar
 * to the `WebRequest#getCookie` and `WebResponse#setcookie` methods in PHP.
 *
 * @author Sam Smith <samsmith@wikimedia.org>
 * @author Matthew Flaschen <mflaschen@wikimedia.org>
 *
 * @module mediawiki.cookie
 * @example
 * mw.loader.using( 'mediawiki.cookie' ).then( () => {
 *   mw.cookie.set('hello', 'world' );
 * })
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
	 * @param {module:mediawiki.cookie~CookieOptions|Date|number} [options] Options object, or expiry date
	 * @memberof module:mediawiki.cookie
	 */

	set: function ( key, value, options ) {
		var prefix, date;

		// The 'options' parameter may be a shortcut for the expiry.
		if ( arguments.length > 2 && ( !options || options instanceof Date || typeof options === 'number' ) ) {
			options = { expires: options };
		}
		// Apply defaults
		options = Object.assign( {}, defaults, options );

		// Don't pass invalid option to jar.cookie
		prefix = options.prefix;
		delete options.prefix;

		if ( !options.expires ) {
			// Session cookie (null or zero)
			// Normalize to absent (undefined) for jar.cookie.
			delete options.expires;
		} else if ( typeof options.expires === 'number' ) {
			// Lifetime in seconds
			date = new Date();
			date.setTime( Number( date ) + ( options.expires * 1000 ) );
			options.expires = date;
		}

		// Ignore sameSiteLegacy (T344791)
		delete options.sameSiteLegacy;

		if ( value !== null ) {
			value = String( value );
		}

		jar.cookie( prefix + key, value, options );
	},

	/**
	 * Get the value of a cookie.
	 *
	 * @param {string} key
	 * @param {string} [prefix=wgCookiePrefix] The prefix of the key. If `prefix` is
	 *   `undefined` or `null`, then `wgCookiePrefix` is used
	 * @param {null|string} [defaultValue] defaults to null
	 * @return {string|null} If the cookie exists, then the value of the
	 *   cookie, otherwise `defaultValue`
	 * @memberof module:mediawiki.cookie
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

		result = jar.cookie( prefix + key );

		return result !== null ? result : defaultValue;
	},

	/**
	 * Get the value of a SameSite=None cookie, using the legacy ss0- cookie if needed.
	 *
	 * @param {string} key
	 * @param {string} [prefix=wgCookiePrefix] The prefix of the key. If `prefix` is
	 *   `undefined` or `null`, then `wgCookiePrefix` is used
	 * @param {null|string} [defaultValue]
	 * @return {string|null} If the cookie exists, then the value of the
	 *   cookie, otherwise `defaultValue`
	 * @memberof module:mediawiki.cookie
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
		jar,
		setDefaults: function ( value ) {
			var prev = defaults;
			defaults = value;
			return prev;
		}
	};
}
