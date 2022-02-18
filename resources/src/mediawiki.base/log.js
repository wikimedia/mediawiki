// This file extends the mw.log skeleton defined in startup/mediawiki.js.
// Code that is not needed by mw.loader is placed here.

/* eslint-disable no-console */

/**
 * @class mw
 * @singleton
 */

/**
 * Create a function that returns true for the first call from any particular call stack.
 *
 * @private
 * @return {Function}
 * @return {boolean|undefined} return.return True if the caller was not seen before.
 */
function stackSet() {
	// Optimisation: Don't create or compute anything for the common case
	// where deprecations are not triggered.
	var stacks;

	return function isFirst() {
		if ( !stacks ) {
			/* global Set */
			stacks = new Set();
		}
		var stack = new Error().stack;
		if ( !stacks.has( stack ) ) {
			stacks.add( stack );
			return true;
		}
	};
}

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
 * Create a function that logs a deprecation warning when called.
 *
 * Usage:
 *
 *     var deprecatedNoB = mw.log.makeDeprecated( 'hello_without_b', 'Use of hello without b is deprecated.' );
 *
 *     function hello( a, b ) {
 *       if ( b === undefined ) {
 *         deprecatedNoB();
 *         b = 0;
 *       }
 *       return a + b;
 *     }
 *
 *     hello( 1 );
 *
 *
 * @since 1.38
 * @param {string|null} key Name of the feature for deprecation tracker,
 *  or null for a console-only deprecation.
 * @param {string} msg Deprecation warning.
 * @return {Function}
 */
mw.log.makeDeprecated = function ( key, msg ) {
	// Support IE 11, Safari 5: Use ES6 Set conditionally. Fallback to not logging.
	var isFirst = window.Set ? stackSet() : function () {};

	return function maybeLog() {
		if ( isFirst() ) {
			if ( key ) {
				mw.track( 'mw.deprecate', key );
			}
			mw.log.warn( msg );
		}
	};
};

/**
 * Create a property on a host object that, when accessed, will log
 * a deprecation warning to the console.
 *
 * Usage:
 *
 *    mw.log.deprecate( window, 'myGlobalFn', myGlobalFn );
 *
 *    mw.log.deprecate( Thing, 'old', old, 'Use Other.thing instead', 'Thing.old'  );
 *
 *
 * @param {Object} obj Host object of deprecated property
 * @param {string} key Name of property to create in `obj`
 * @param {Mixed} val The value this property should return when accessed
 * @param {string} [msg] Optional extra text to add to the deprecation warning
 * @param {string} [logName] Name of the feature for deprecation tracker.
 *  Tracking is disabled by default, except for global variables on `window`.
 */
mw.log.deprecate = function ( obj, key, val, msg, logName ) {
	// Support IE 11, ES5: Use ES6 Set conditionally. Fallback to not logging.
	//
	// Support Safari 5.0: Object.defineProperty throws  "not supported on DOM Objects" for
	// Node or Element objects (incl. document)
	// Safari 4.0 doesn't have this method, and it was fixed in Safari 5.1.
	if ( !window.Set ) {
		obj[ key ] = val;
		return;
	}

	var maybeLog = mw.log.makeDeprecated(
		logName || ( obj === window ? key : null ),
		'Use of "' + ( logName || key ) + '" is deprecated.' + ( msg ? ' ' + msg : '' )
	);
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
};
