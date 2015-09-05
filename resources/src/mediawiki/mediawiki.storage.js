( function ( mw ) {
	'use strict';

	/**
	 * Library for storing device specific information. It should be used for storing simple
	 * strings and is not suitable for storing large chunks of data.
	 *
	 * @class mw.storage
	 * @singleton
	 */
	mw.storage = {

		localStorage: window.localStorage,

		/**
		 * Retrieve value from device storage.
		 *
		 * @param {string} key Key of item to retrieve
		 * @return {string|boolean} False when localStorage not available, otherwise string
		 */
		get: function ( key ) {
			try {
				return mw.storage.localStorage.getItem( key );
			} catch ( e ) {}
			return false;
		},

		/**
		  * Set a value in device storage.
		  *
		  * @param {string} key Key name to store under
		  * @param {string} value Value to be stored
		  * @return {boolean} Whether the save succeeded or not
		  */
		set: function ( key, value ) {
			try {
				mw.storage.localStorage.setItem( key, value );
				return true;
			} catch ( e ) {}
			return false;
		},

		/**
		  * Remove a value from device storage.
		  *
		  * @param {string} key Key of item to remove
		  * @return {boolean} Whether the save succeeded or not
		  */
		remove: function ( key ) {
			try {
				mw.storage.localStorage.removeItem( key );
				return true;
			} catch ( e ) {}
			return false;
		}
	};

}( mediaWiki ) );
