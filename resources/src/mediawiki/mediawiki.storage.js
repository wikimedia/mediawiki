( function ( mw ) {
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
	 * A wrapper for an HTML5 Storage interface (`localStorage` or `sessionStorage`)
	 * that is safe to call on all browsers.
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
	 * @return {string|boolean} False when localStorage not available, otherwise string
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
	 * @return {boolean} Whether the save succeeded or not
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
	 * @return {boolean} Whether the save succeeded or not
	 */
	SafeStorage.prototype.remove = function ( key ) {
		try {
			this.store.removeItem( key );
			return true;
		} catch ( e ) {}
		return false;
	};

	/**
	 * A wrapper for the HTML5 `localStorage` interface
	 * that is safe to call on all browsers.
	 *
	 * @class
	 * @singleton
	 * @extends mw.SafeStorage
	 */
	mw.storage = new SafeStorage( localStorage );

	/**
	 * A wrapper for the HTML5 `sessionStorage` interface
	 * that is safe to call on all browsers.
	 *
	 * @class
	 * @singleton
	 * @extends mw.SafeStorage
	 */
	mw.storage.session = new SafeStorage( sessionStorage );

}( mediaWiki ) );
