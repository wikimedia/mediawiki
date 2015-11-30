( function ( mw ) {
	'use strict';

	/**
	 * A wrapper for a Storage interface (either localStorage or sessionStorage)
	 * that is safe to call on all browsers.
	 *
	 * @class SafeStorage
	 *
	 * @constructor
	 * @param {Object} [store] The Storage instance to use as the underlying store.
	 */
	function SafeStorage( store ) {
		var self = this;

		self.store = store;

		/**
		 * Retrieve value from device storage.
		 *
		 * @param {string} key Key of item to retrieve
		 * @return {string|boolean} False when localStorage not available, otherwise string
		 */
		self.get = function ( key ) {
			try {
				return self.store.getItem( key );
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
		self.set = function ( key, value ) {
			try {
				self.store.setItem( key, value );
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
		self.remove = function ( key ) {
			try {
				self.store.removeItem( key );
				return true;
			} catch ( e ) {}
			return false;
		};
	}

	mw.storage = new SafeStorage( window.localStorage );
	mw.storage.session = new SafeStorage( window.sessionStorage );

}( mediaWiki ) );
