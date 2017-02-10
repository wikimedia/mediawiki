var log = require( './log' ),
	hasOwn = Object.prototype.hasOwnProperty,
	mw = require( './mediawiki' ),
	slice = Array.prototype.slice;

/**
 * Alias property to the global object.
 *
 * @private
 * @static
 * @param {mw.Map} map
 * @param {string} key
 * @param {Mixed} value
 */
function setGlobalMapValue( map, key, value ) {
	map.internalValues[ key ] = value;
	log.deprecate(
			window,
			key,
			value,
			// Deprecation notice for mw.config globals (T58550, T72470)
			map === mw.config && 'Use mw.config instead.'
	);
}

/**
 * Create an object that can be read from or written to via methods that allow
 * interaction both with single and multiple properties at once.
 *
 * @private
 * @class mw.Map
 *
 * @constructor
 * @param {boolean} [global=false] Whether to synchronise =values to the global
 *  window object (for backwards-compatibility with mw.config; T72470). Values are
 *  copied in one direction only. Changes to globals do not reflect in the map.
 */
function Map( global ) {
	this.internalValues = {};
	if ( global === true ) {
		// Override #set to also set the global variable
		this.set = function ( selection, value ) {
			var s;

			if ( $.isPlainObject( selection ) ) {
				for ( s in selection ) {
					setGlobalMapValue( this, s, selection[ s ] );
				}
				return true;
			}
			if ( typeof selection === 'string' && arguments.length ) {
				setGlobalMapValue( this, selection, value );
				return true;
			}
			return false;
		};
	}

	// Deprecated since MediaWiki 1.28
	log.deprecate(
		this,
		'values',
		this.internalValues,
		'mw.Map#values is deprecated. Use mw.Map#get() instead.',
		'Map-values'
	);
}

Map.prototype = {
	constructor: Map,

	/**
	 * Get the value of one or more keys.
	 *
	 * If called with no arguments, all values are returned.
	 *
	 * @param {string|Array} [selection] Key or array of keys to retrieve values for.
	 * @param {Mixed} [fallback=null] Value for keys that don't exist.
	 * @return {Mixed|Object| null} If selection was a string, returns the value,
	 *  If selection was an array, returns an object of key/values.
	 *  If no selection is passed, the internal container is returned. (Beware that,
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
			if ( !hasOwn.call( this.internalValues, selection ) ) {
				return fallback;
			}
			return this.internalValues[ selection ];
		}

		if ( selection === undefined ) {
			return this.internalValues;
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
				this.internalValues[ s ] = selection[ s ];
			}
			return true;
		}
		if ( typeof selection === 'string' && arguments.length > 1 ) {
			this.internalValues[ selection ] = value;
			return true;
		}
		return false;
	},

	/**
	 * Check if one or more keys exist.
	 *
	 * @param {Mixed} selection Key or array of keys to check
	 * @return {boolean} True if the key(s) exist
	 */
	exists: function ( selection ) {
		var s;

		if ( $.isArray( selection ) ) {
			for ( s = 0; s < selection.length; s++ ) {
				if ( typeof selection[ s ] !== 'string' || !hasOwn.call( this.internalValues, selection[ s ] ) ) {
					return false;
				}
			}
			return true;
		}
		return typeof selection === 'string' && hasOwn.call( this.internalValues, selection );
	}
};

module.exports = Map;
