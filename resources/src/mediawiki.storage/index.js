/**
 * A safe interface to HTML5 `localStorage` and `sessionStorage`.
 *
 * This normalises differences across browsers and silences any and all
 * exceptions that may occur.
 *
 * **Note**: Storage keys are not automatically prefixed in relation to
 * MediaWiki and/or the current wiki. Always **prefix your keys** with "mw" to
 * avoid conflicts with gadgets, JavaScript libraries, browser extensions,
 * internal CDN or webserver cookies, and third-party applications that may
 * be embedded on the page.
 *
 * **Warning**: This API has limited storage space and does not use an expiry
 * by default. This means unused **keys are stored forever**, unless you
 * opt-in to the `expiry` parameter or otherwise make sure that your code
 * can rediscover and delete keys you created in the past.
 *
 * If you don't use the `expiry` parameter, avoid keys with variable
 * components as this leads to untracked keys that your code has no way
 * to know about and delete when the data is no longer needed. Instead,
 * store dynamic values in an object under a single constant key that you
 * manage or replace over time.
 * See also T121646.
 *
 * @example mw.storage.set( key, value, expiry );
 * mw.storage.set( key, value ); // stored indefinitely
 * mw.storage.get( key );
 *
 * @example var local = require( 'mediawiki.storage' ).local;
 * local.set( key, value, expiry );
 * local.get( key );
 *
 * @example mw.storage.session.set( key, value );
 * mw.storage.session.get( key );
 *
 * @example var session = require( 'mediawiki.storage' ).session;
 * session.set( key, value );
 * session.get( key );
 *
 * @module mediawiki.storage
 */
'use strict';

// Catch exceptions to avoid fatal in Chrome's "Block data storage" mode
// which throws when accessing the localStorage property itself, as opposed
// to the standard behaviour of throwing on getItem/setItem. (T148998)
const
	localStorage = ( function () {
		try {
			return window.localStorage;
		} catch ( e ) {}
	}() ),
	sessionStorage = ( function () {
		try {
			return window.sessionStorage;
		} catch ( e ) {}
	}() );

const SafeStorage = require( './SafeStorage.js' );

/**
 * Alias for {@link module:mediawiki.storage.local}.
 *
 * @type {SafeStorage}
 * @memberof mw
 * @property {SafeStorage} session Alias for {@link module:mediawiki.storage.session}.
 */
mw.storage = new SafeStorage( localStorage );
mw.storage.session = new SafeStorage( sessionStorage );

module.exports = {
	/**
	 * A safe interface to HTML5 `localStorage`.
	 *
	 * @type {SafeStorage}
	 */
	local: mw.storage,

	/**
	 * A safe interface to HTML5 `sessionStorage`.
	 *
	 * **Note**: Data persisted via `sessionStorage` will persist for the lifetime
	 * of the browser *tab*, not the browser *window*.
	 * For longer-lasting persistence across tabs, refer to mw.storage or mw.cookie instead.
	 *
	 * @type {SafeStorage}
	 */
	session: mw.storage.session
};
