( function ( $ ) {
	var hasOwn = Object.prototype.hasOwnProperty,
		slice = Array.prototype.slice;

	if ( window.Map === undefined ) {
		/**
		 * Create an object that can be read from or written to from methods that allow
		 * interaction both with single and multiple properties at once.
		 *
		 *     @example
		 *
		 *     var collection, query, results;
		 *
		 *     // Create your address book
		 *     collection = new mw.Map();
		 *
		 *     // This data could be coming from an external source (eg. API/AJAX)
		 *     collection.set( {
		 *         'John Doe': 'john@example.org',
		 *         'Jane Doe': 'jane@example.org',
		 *         'George van Halen': 'gvanhalen@example.org'
		 *     } );
		 *
		 *     wanted = ['John Doe', 'Jane Doe', 'Daniel Jackson'];
		 *
		 *     // You can detect missing keys first
		 *     if ( !collection.exists( wanted ) ) {
		 *         // One or more are missing (in this case: "Daniel Jackson")
		 *         mw.log( 'One or more names were not found in your address book' );
		 *     }
		 *
		 *     // Or just let it give you what it can. Optionally fill in from a default.
		 *     results = collection.get( wanted, 'nobody@example.com' );
		 *     mw.log( results['Jane Doe'] ); // "jane@example.org"
		 *     mw.log( results['Daniel Jackson'] ); // "nobody@example.com"
		 *
		 * @class mw.Map
		 *
		 * @constructor
		 * @param {Object|boolean} [values] The value-baring object to be mapped. Defaults to an
		 *  empty object.
		 *  For backwards-compatibility with mw.config, this can also be `true` in which case values
		 *  are copied to the Window object as global variables (T72470). Values are copied in
		 *  one direction only. Changes to globals are not reflected in the map.
		 */
		window.Map = function ( values ) {
			this.values = values || {};
		};

		window.Map.prototype = {
			/**
			 * Obtain all registered keys
			 *
			 * @return {Object}
			 */
			entries: function () {
				return this.values;
			},
			/**
			 * Get the value of one or more keys.
			 *
			 * If called with no arguments, all values are returned.
			 *
			 * @param {string|Array} [selection] Key or array of keys to retrieve values for.
			 * @param {Mixed} [fallback=null] Value for keys that don't exist.
			 * @return {Mixed|Object| null} If selection was a string, returns the value,
			 *  If selection was an array, returns an object of key/values.
			 *  If no selection is passed, the 'values' container is returned. (Beware that,
			 *  as is the default in JavaScript, the object is returned by reference.)
			 */
			get: function ( selection, fallback ) {
				var results, i;
				// If we only do this in the `return` block, it'll fail for the
				// call to get() from the mutli-selection block.
				fallback = arguments.length > 1 ? fallback : null;

				if ( $.isArray( selection ) ) {
					selection = slice.call( selection );
					results = {};
					for ( i = 0; i < selection.length; i++ ) {
						results[ selection[ i ] ] = this.get( selection[ i ], fallback );
					}
					return results;
				}

				if ( typeof selection === 'string' ) {
					if ( !hasOwn.call( this.values, selection ) ) {
						return fallback;
					}
					return this.values[ selection ];
				}

				if ( selection === undefined ) {
					mw.log.warn( 'Use of `get` in this way is deprecated. Please use `entries`.' );
					return this.entries();
				}

				// Invalid selection key
				return null;
			},

			/**
			 * Set one or more key/value pairs.
			 *
			 * @param {string|Object} selection Key to set value for, or object mapping keys to values
			 * @param {Mixed} [value] Value to set (optional, only in use when key is a string)
			 * @return {boolean} True on success, false on failure
			 */
			set: function ( selection, value ) {
				var s;

				if ( $.isPlainObject( selection ) ) {
					for ( s in selection ) {
						this.values[ s ] = selection[ s ];
					}
					return true;
				}
				if ( typeof selection === 'string' && arguments.length > 1 ) {
					this.values[ selection ] = value;
					return true;
				}
				return false;
			},

			/**
			 * Check if a key exists
			 *
			 * @param {string} key to check
			 * @return {boolean} True if the key(s) exist
			 */
			has: function ( key ) {
				return typeof key === 'string' && hasOwn.call( this.values, key );
			},
		};
	}
}( jQuery ) );
