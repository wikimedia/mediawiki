( function () {
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
	 * A wrapper for the HTML5 Storage interface (`localStorage` or `sessionStorage`)
	 * that is safe to call in all browsers.
	 *
	 * @class mw.SafeStorage
	 * @private
	 * @param {Object|undefined} store The Storage instance to wrap around
	 */
	function SafeStorage( store ) {
		this.store = store;
	}

	/**
	 * Retrieve value from device storage.
	 *
	 * @param {string} key Key of item to retrieve
	 * @return {string|null|boolean} String value, null if no value exists, or false
	 *  if storage is not available.
	 */
	SafeStorage.prototype.get = function ( key ) {
		try {
			return this.store.getItem( key );
		} catch ( e ) {}
		return false;
	};

	/**
	 * Set a value in device storage.
	 *
	 * @param {string} key Key name to store under
	 * @param {string} value Value to be stored
	 * @return {boolean} The value was set
	 */
	SafeStorage.prototype.set = function ( key, value ) {
		try {
			this.store.setItem( key, value );
			return true;
		} catch ( e ) {}
		return false;
	};

	/**
	 * Remove a value from device storage.
	 *
	 * @param {string} key Key of item to remove
	 * @return {boolean} Whether the key was removed
	 */
	SafeStorage.prototype.remove = function ( key ) {
		try {
			this.store.removeItem( key );
			return true;
		} catch ( e ) {}
		return false;
	};

	/**
	 * Retrieve JSON object from device storage.
	 *
	 * @param {string} key Key of item to retrieve
	 * @return {Object|null|boolean} Object, null if no value exists or value
	 *  is not JSON-parseable, or false if storage is not available.
	 */
	SafeStorage.prototype.getObject = function ( key ) {
		var json = this.get( key );

		if ( json === false ) {
			return false;
		}

		try {
			return JSON.parse( json );
		} catch ( e ) {}

		return null;
	};

	/**
	 * Set an object value in device storage by JSON encoding
	 *
	 * @param {string} key Key name to store under
	 * @param {Object} value Object value to be stored
	 * @return {boolean} The value was set
	 */
	SafeStorage.prototype.setObject = function ( key, value ) {
		var json;
		try {
			json = JSON.stringify( value );
			return this.set( key, json );
		} catch ( e ) {}
		return false;
	};

	/**
	 * A safe interface to HTML5 `localStorage`.
	 *
	 * This normalises differences across browsers and silences any and all
	 * exceptions that may occur.
	 *
	 * **Note**: Storage keys are not automatically prefixed in relation to
	 * MediaWiki and/or the current wiki. Always **prefix your keys** with "mw" to
	 * avoid conflicts with other JavaScript libraries, gadgets, or third-party
	 * JavaScript.
	 *
	 * **Warning**: There is no expiry feature in this API. This means **keys
	 * are stored forever**, unless you re-discover and delete them manually.
	 * Avoid keys with variable components. Instead store dynamic values
	 * together under a single key so that you avoid leaving garbage behind,
	 * which would fill up the limited space available.
	 * See also <https://phabricator.wikimedia.org/T121646>.
	 *
	 * Example:
	 *
	 *     mw.storage.set( key, value );
	 *     mw.storage.get( key );
	 *
	 * Example:
	 *
	 *     var local = require( 'mediawiki.storage' ).local;
	 *     local.set( key, value );
	 *     local.get( key );
	 *
	 * @class
	 * @singleton
	 * @extends mw.SafeStorage
	 */
	mw.storage = new SafeStorage( localStorage );

	/**
	 * A safe interface to HTML5 `sessionStorage`.
	 *
	 * This normalises differences across browsers and silences any and all
	 * exceptions that may occur.
	 *
	 * **Note**: Data persisted via `sessionStorage` will persist for the lifetime
	 * of the browser *tab*, not the browser *window*.
	 * For longer-lasting persistence across tabs, refer to mw.storage or mw.cookie instead.
	 *
	 * Example:
	 *
	 *     mw.storage.session.set( key, value );
	 *     mw.storage.session.get( key );
	 *
	 * Example:
	 *
	 *     var session = require( 'mediawiki.storage' ).session;
	 *     session.set( key, value );
	 *     session.get( key );
	 *
	 * @class
	 * @singleton
	 * @extends mw.SafeStorage
	 */
	mw.storage.session = new SafeStorage( sessionStorage );

	module.exports = {
		local: mw.storage,
		session: mw.storage.session
	};

}() );
