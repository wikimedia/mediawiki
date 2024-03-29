/**
 * Base library for MediaWiki.
 */
/* global $CODE */

( function () {
	'use strict';

	var con = window.console;

	/**
	 * ES3 compatible class similar to [ES6 Map]{@link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map}.
	 *
	 * @class mw.Map
	 * @classdesc Create an object that can be read from or written to via methods that allow
	 * interaction both with single and multiple properties at once.
	 *
	 * @example
	 * const map = new mw.Map();
	 * map.set( 'foo', 5 );
	 * alert( 5 === map.get( 'foo' ) );
	 */
	function Map() {
		this.values = Object.create( null );
	}

	Map.prototype = {
		constructor: Map,

		/**
		 * Get the value of one or more keys.
		 *
		 * If called with no arguments, all values are returned.
		 *
		 * @memberof mw.Map
		 * @param {string|Array} [selection] Key or array of keys to retrieve values for.
		 * @param {any} [fallback=null] Value for keys that don't exist.
		 * @return {any|Object|null} If selection was a string, returns the value,
		 *  If selection was an array, returns an object of key/values.
		 *  If no selection is passed, a new object with all key/values is returned.
		 */
		get: function ( selection, fallback ) {
			if ( arguments.length < 2 ) {
				fallback = null;
			}

			if ( typeof selection === 'string' ) {
				return selection in this.values ?
					this.values[ selection ] :
					fallback;
			}

			var results;
			if ( Array.isArray( selection ) ) {
				results = {};
				for ( var i = 0; i < selection.length; i++ ) {
					if ( typeof selection[ i ] === 'string' ) {
						results[ selection[ i ] ] = selection[ i ] in this.values ?
							this.values[ selection[ i ] ] :
							fallback;
					}
				}
				return results;
			}

			if ( selection === undefined ) {
				results = {};
				for ( var key in this.values ) {
					results[ key ] = this.values[ key ];
				}
				return results;
			}

			// Invalid selection key
			return fallback;
		},

		/**
		 * Set one or more key/value pairs.
		 *
		 * @memberof mw.Map
		 * @param {string|Object} selection Key to set value for, or object mapping keys to values
		 * @param {any} [value] Value to set (optional, only in use when key is a string)
		 * @return {boolean} True on success, false on failure
		 */
		set: function ( selection, value ) {
			// Use `arguments.length` because `undefined` is also a valid value.
			if ( arguments.length > 1 ) {
				// Set one key
				if ( typeof selection === 'string' ) {
					this.values[ selection ] = value;
					return true;
				}
			} else if ( typeof selection === 'object' ) {
				// Set multiple keys
				for ( var key in selection ) {
					this.values[ key ] = selection[ key ];
				}
				return true;
			}
			return false;
		},

		/**
		 * Check if a given key exists in the map.
		 *
		 * @memberof mw.Map
		 * @param {string} selection Key to check
		 * @return {boolean} True if the key exists
		 */
		exists: function ( selection ) {
			return typeof selection === 'string' && selection in this.values;
		}
	};

	/**
	 * Write a verbose message to the browser's console in debug mode.
	 *
	 * This method is mainly intended for verbose logging. It is a no-op in production mode.
	 * In ResourceLoader debug mode, it will use the browser's console.
	 *
	 * @ignore
	 * @param {...string} msg Messages to output to console.
	 */
	var log = function () {
		$CODE.consoleLog();
	};

	/**
	 * Write a message to the browser console's warning channel.
	 *
	 * @memberof mw.log
	 * @method warn
	 * @param {...string} msg Messages to output to console
	 */
	log.warn = Function.prototype.bind.call( con.warn, con );

	/**
	 * Base library for MediaWiki. Exposed globally as `mw`, with `mediaWiki` as alias.
	 *
	 * @namespace mw
	 * @singleton
	 * @hideconstructor
	 * @static
	 */
	var mw = {
		/**
		 * Get the current time, measured in milliseconds since January 1, 1970 (UTC).
		 *
		 * On browsers that implement the Navigation Timing API, this function will produce
		 * floating-point values with microsecond precision that are guaranteed to be monotonic.
		 * On all other browsers, it will fall back to using `Date`.
		 *
		 * @memberof mw
		 * @return {number} Current time
		 */
		now: function () {
			// Optimisation: Cache and re-use the chosen implementation.
			// Optimisation: Avoid startup overhead by re-defining on first call instead of IIFE.
			var perf = window.performance;
			var navStart = perf && perf.timing && perf.timing.navigationStart;

			// Define the relevant shortcut
			mw.now = navStart && perf.now ?
				function () {
					return navStart + perf.now();
				} :
				Date.now;

			return mw.now();
		},

		/**
		 * List of all analytic events emitted so far.
		 *
		 * Exposed only for use by mediawiki.base.
		 *
		 * @private
		 * @property {Array}
		 */
		trackQueue: [],

		/**
		 * Track `'resourceloader.exception'` event and send it to the window console.
		 *
		 * This exists for internal use by mw.loader only, to remember and buffer
		 * very early events for `mw.trackSubscribe( 'resourceloader.exception' )`
		 * even while `mediawiki.base` and `mw.track` are still in-flight.
		 *
		 * @private
		 * @param {Object} data
		 * @param {Error} [data.exception]
		 * @param {string} data.source Error source
		 * @param {string} [data.module] Name of module which caused the error
		 */
		trackError: function ( data ) {
			if ( mw.track ) {
				mw.track( 'resourceloader.exception', data );
			} else {
				mw.trackQueue.push( { topic: 'resourceloader.exception', data: data } );
			}

			// Log an error message to window.console, even in production mode.
			var e = data.exception;
			var msg = ( e ? 'Exception' : 'Error' ) +
				' in ' + data.source +
				( data.module ? ' in module ' + data.module : '' ) +
				( e ? ':' : '.' );

			con.log( msg );

			// If we have an exception object, log it to the warning channel to trigger
			// proper stacktraces in browsers that support it.
			if ( e ) {
				con.warn( e );
			}
		},

		/**
		 * Library that predates the ES6 Map class with similar functionality.
		 *
		 * @type {mw.Map}
		 */
		Map: Map,

		/**
		 * Map of configuration values.
		 *
		 * Check out [the complete list of configuration values](https://www.mediawiki.org/wiki/Manual:Interface/JavaScript#mw.config)
		 * on mediawiki.org.
		 *
		 * @memberof mw
		 * @type {mw.Map}
		 */
		config: new Map(),

		/**
		 * Store for messages.
		 *
		 * @memberof mw
		 * @type {mw.Map}
		 */
		messages: new Map(),

		/**
		 * Store for templates associated with a module.
		 *
		 * @type {mw.Map}
		 * @memberof mw
		 */
		templates: new Map(),

		// Expose mw.log
		log: log

		// mw.loader is defined in a separate file that is appended to this
	};

	// Attach to window and globally alias
	window.mw = window.mediaWiki = mw;

	$CODE.undefineQUnit();
}() );
