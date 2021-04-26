// This file extends the mw.log skeleton defined in startup/mediawiki.js.
// Code that is not needed by mw.loader is placed here.

/* eslint-disable no-console */

/**
 * @class mw
 * @singleton
 */

/**
 * Write a message to the browser console's error channel.
 *
 * Most browsers also print a stacktrace when calling this method if the
 * argument is an Error object.
 *
 * This method is a no-op in browsers that don't implement the Console API.
 *
 * @since 1.26
 * @param {...Mixed} msg Messages to output to console
 */
mw.log.error = console.error ?
	Function.prototype.bind.call( console.error, console ) :
	function () {};

/**
 * Create a property on a host object that, when accessed, will produce
 * a deprecation warning in the console.
 *
 * @param {Object} obj Host object of deprecated property
 * @param {string} key Name of property to create in `obj`
 * @param {Mixed} val The value this property should return when accessed
 * @param {string} [msg] Optional text to include in the deprecation message
 * @param {string} [logName] Name for the feature for logging and tracking
 *  purposes. Except for properties of the window object, tracking is only
 *  enabled if logName is set.
 */
mw.log.deprecate = function ( obj, key, val, msg, logName ) {
	var stacks;
	function maybeLog() {
		var name = logName || key,
			trace = new Error().stack;
		if ( !stacks ) {
			/* global Set */
			stacks = new Set();
		}
		if ( !stacks.has( trace ) ) {
			stacks.add( trace );
			if ( logName || obj === window ) {
				mw.track( 'mw.deprecate', name );
			}
			mw.log.warn(
				'Use of "' + name + '" is deprecated.' + ( msg ? ' ' + msg : '' )
			);
		}
	}

	// Support IE 11, ES5: Use ES6 Set conditionally. Fallback to not logging.
	//
	// Support Safari 5.0: Object.defineProperty throws  "not supported on DOM Objects" for
	// Node or Element objects (incl. document)
	// Safari 4.0 doesn't have this method, and it was fixed in Safari 5.1.
	if ( window.Set ) {
		Object.defineProperty( obj, key, {
			configurable: true,
			enumerable: true,
			get: function () {
				maybeLog();
				return val;
			},
			set: function ( newVal ) {
				maybeLog();
				val = newVal;
			}
		} );
	} else {
		obj[ key ] = val;
	}
};
