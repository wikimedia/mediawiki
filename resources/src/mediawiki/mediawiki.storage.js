( function ( mw ) {
	'use strict';
	var storage;

	/**
	 * Library for storing device specific information. It should be used for storing simple
	 * strings and is not suitable for storing large chunks of data.
	 * @class mw.settings
	 * @singleton
	 */
	storage = {
		isLocalStorageSupported: false,
		/**
		 * Retrieve value from device storage.
		 *
		 * @param {String} key of item to retrieve
		 * @returns {String|Boolean} false when localStorage not available, otherwise string
		 */
		get: function ( key ) {
			if ( this.isLocalStorageSupported ) {
				return localStorage.getItem( key );
			} else {
				return false;
			}
		},

		/**
		 * Set a value in device storage.
		 *
		 * @param {String} key key name to store under.
		 * @param {String} value to be stored.
		 * @returns {Boolean} whether the save succeeded or not.
		 */
		set: function ( key, value ) {
			try {
				localStorage.setItem( key, value );
				return true;
			} catch ( e ) {
				return false;
			}
		},

		/**
		 * Remove a value from device storage.
		 *
		 * @param {String} key of item to remove.
		 * @returns {Boolean} whether the save succeeded or not.
		 */
		remove: function ( key ) {
			if ( this.isLocalStorageSupported ) {
				localStorage.removeItem( key );
				return true;
			} else {
				return false;
			}
		}
	};

	mw.storage = storage;
	// See if local storage is supported
	try {
		localStorage.setItem( 'localStorageTest', 'localStorageTest' );
		localStorage.removeItem( 'localStorageTest' );
		storage.isLocalStorageSupported = true;
	} catch ( e ) {
		// Already set. No body needed.
	}

}( mediaWiki ) );
