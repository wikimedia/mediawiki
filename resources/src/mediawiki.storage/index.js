'use strict';

// Catch exceptions to avoid fatal in Chrome's "Block data storage" mode
// which throws when accessing the localStorage property itself, as opposed
// to the standard behaviour of throwing on getItem/setItem. (T148998)
var
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

/**
 * @classdesc A safe interface to HTML5 `localStorage` and `sessionStorage`.
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
 * See also <https://phabricator.wikimedia.org/T121646>.
 *
 * @example mw.storage.set( key, value, expiry );
 *     mw.storage.set( key, value ); // stored indefinitely
 *     mw.storage.get( key );
 *
 * @example var local = require( 'mediawiki.storage' ).local;
 *     local.set( key, value, expiry );
 *     local.get( key );
 *
 * @example mw.storage.session.set( key, value );
 *     mw.storage.session.get( key );
 *
 * @example var session = require( 'mediawiki.storage' ).session;
 *     session.set( key, value );
 *     session.get( key );
 *
 * This normalises differences across browsers and silences any and all
 * exceptions that may occur.
 *
 * **Note**: Data persisted via `sessionStorage` will persist for the lifetime
 * of the browser *tab*, not the browser *window*.
 * For longer-lasting persistence across tabs, refer to mw.storage or mw.cookie instead.
 *
 * @class MwSafeStorage
 * @extends SafeStorage
 * @hideconstructor
 */
var SafeStorage = require( './SafeStorage.js' );

/**
 * @type {MwSafeStorage}
 */
mw.storage = new SafeStorage( localStorage );

/**
 * A safe interface to HTML5 `sessionStorage`.
 *
 * @name MwSafeStorage.session
 * @type {SafeStorage}
 */
mw.storage.session = new SafeStorage( sessionStorage );

/**
 * Provides safe access to HTML5 session storage and local storage.
 * @exports mediawiki.storage
 */
module.exports = {
	/**
	 * Safe access to localStorage.
	 *
	 * @type {SafeStorage}
	 */
	local: mw.storage,
	/**
	 * Safe access to sessionStorage.
	 * @type {SafeStorage}
	 */
	session: mw.storage.session
};
