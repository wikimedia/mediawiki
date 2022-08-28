( function () {
	'use strict';

	var EXPIRY_PREFIX = '_EXPIRY_';

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

		// Purge expired items once per page session
		var storage = this;
		setTimeout( function () {
			storage.clearExpired();
		}, 2000 );
	}

	/**
	 * Retrieve value from device storage.
	 *
	 * @param {string} key Key of item to retrieve
	 * @return {string|null|boolean} String value, null if no value exists, or false
	 *  if storage is not available.
	 */
	SafeStorage.prototype.get = function ( key ) {
		if ( this.isExpired( key ) ) {
			return null;
		}
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
	 * @param {number} [expiry] Number of seconds after which this item can be deleted
	 * @return {boolean} The value was set
	 */
	SafeStorage.prototype.set = function ( key, value, expiry ) {
		if ( key.slice( 0, EXPIRY_PREFIX.length ) === EXPIRY_PREFIX ) {
			throw new Error( 'Key can\'t have a prefix of ' + EXPIRY_PREFIX );
		}
		try {
			this.store.setItem( key, value );
			this.setExpires( key, expiry );
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
			this.setExpires( key );
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
	 * @param {number} [expiry] Number of seconds after which this item can be deleted
	 * @return {boolean} The value was set
	 */
	SafeStorage.prototype.setObject = function ( key, value, expiry ) {
		var json;
		try {
			json = JSON.stringify( value );
			return this.set( key, json, expiry );
		} catch ( e ) {}
		return false;
	};

	/**
	 * Set the expiry time for an item in the store
	 *
	 * @param {string} key Key name
	 * @param {number} [expiry] Number of seconds after which this item can be deleted,
	 *  omit to clear the expiry (either making the item never expire, or to clean up
	 *  when deleting a key).
	 */
	SafeStorage.prototype.setExpires = function ( key, expiry ) {
		if ( expiry ) {
			try {
				this.store.setItem(
					EXPIRY_PREFIX + key,
					Math.floor( Date.now() / 1000 ) + expiry
				);
			} catch ( e ) {}
		} else {
			try {
				this.store.removeItem( EXPIRY_PREFIX + key );
			} catch ( e ) {}
		}
	};

	// Minimum amount of time (in milliseconds) for an iteration involving localStorage access.
	var MIN_WORK_TIME = 3;

	/**
	 * Clear any expired items from the store
	 *
	 * @private
	 * @return {jQuery.Promise} Resolves when items have been expired
	 */
	SafeStorage.prototype.clearExpired = function () {
		var storage = this;
		return this.getExpiryKeys().then( function ( keys ) {
			return $.Deferred( function ( d ) {
				mw.requestIdleCallback( function iterate( deadline ) {
					while ( keys[ 0 ] !== undefined && deadline.timeRemaining() > MIN_WORK_TIME ) {
						var key = keys.shift();
						if ( storage.isExpired( key ) ) {
							storage.remove( key );
						}
					}
					if ( keys[ 0 ] !== undefined ) {
						// Ran out of time with keys still to remove, continue later
						mw.requestIdleCallback( iterate );
					} else {
						return d.resolve();
					}
				} );
			} );
		} );
	};

	/**
	 * Get all keys with expiry values
	 *
	 * @private
	 * @return {jQuery.Promise} Promise resolving with all the keys which have
	 *  expiry values (unprefixed), or as many could be retrieved in the allocated time.
	 */
	SafeStorage.prototype.getExpiryKeys = function () {
		var store = this.store;
		return $.Deferred( function ( d ) {
			mw.requestIdleCallback( function ( deadline ) {
				var prefixLength = EXPIRY_PREFIX.length;
				var keys = [];
				var length = 0;
				try {
					length = store.length;
				} catch ( e ) {}

				// Optimization: If time runs out, degrade to checking fewer keys.
				// We will get another chance during a future page view. Iterate forward
				// so that older keys are checked first and increase likelihood of recovering
				// from key exhaustion.
				//
				// We don't expect to have more keys than we can handle in 50ms long-task window.
				// But, we might still run out of time when other tasks run before this,
				// or when the device receives UI events (especially on low-end devices).
				for ( var i = 0; ( i < length && deadline.timeRemaining() > MIN_WORK_TIME ); i++ ) {
					var key = null;
					try {
						key = store.key( i );
					} catch ( e ) {}
					if ( key !== null && key.slice( 0, prefixLength ) === EXPIRY_PREFIX ) {
						keys.push( key.slice( prefixLength ) );
					}
				}
				d.resolve( keys );
			} );
		} ).promise();
	};

	/**
	 * Check if a given key has expired
	 *
	 * @private
	 * @param {string} key Key name
	 * @return {boolean} Whether key is expired
	 */
	SafeStorage.prototype.isExpired = function ( key ) {
		var expiry;
		try {
			expiry = this.store.getItem( EXPIRY_PREFIX + key );
		} catch ( e ) {
			return false;
		}
		return !!expiry && expiry < Math.floor( Date.now() / 1000 );
	};

	/**
	 * A safe interface to HTML5 `localStorage`.
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
	 * Example:
	 *
	 *     mw.storage.set( key, value, expiry );
	 *     mw.storage.set( key, value ); // stored indefinitely
	 *     mw.storage.get( key );
	 *
	 * Example:
	 *
	 *     var local = require( 'mediawiki.storage' ).local;
	 *     local.set( key, value, expiry );
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
