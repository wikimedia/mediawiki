( function ( mw ) {
	'use strict';
	var settings,
		prefix = 'mw-setting-';
	/**
	 * Library for storing device specific information. It should be used for storing simple
	 * strings and is not suitable for storing large chunks of data.
	 * @class mw.settings
	 * @singleton
	 */
	settings = {
		isLocalStorageSupported: false,
		/**
		 * Retrieve value from device storage.
		 *
		 * @param {String} key of item to retrieve
		 * @returns {String|Boolean} false when localStorage not available, otherwise string
		 */
		get: function ( key ) {
			if ( this.isLocalStorageSupported ) {
				return localStorage.getItem( prefix + key );
			} else {
				return false;
			}
		},

		/**
		 * Set a value in device storage.
		 *
		 * @param {String} key key name to store under.
		 * @param {String} value to be stored.
		 * @throws {Exception} when localStorage is not available or has no room.
		 */
		set: function ( key, value ) {
			localStorage.setItem( prefix + key, value );
		},

		/**
		 * Remove a value from device storage.
		 *
		 * @param {String} key of item to remove.
		 * @throws {Exception} when localStorage is not available.
		 */
		remove: function ( key ) {
			if ( this.isLocalStorageSupported ) {
				localStorage.removeItem( prefix + key );
			} else {
				throw 'LocalStorage not supported';
			}
		}
	};

	mw.settings = settings;
	// See if local storage is supported
	try {
		localStorage.setItem( 'localStorageTest', 'localStorageTest' );
		localStorage.removeItem( 'localStorageTest' );
		settings.isLocalStorageSupported = true;
	} catch ( e ) {
		// Already set. No body needed.
	}

}( mediaWiki ) );
