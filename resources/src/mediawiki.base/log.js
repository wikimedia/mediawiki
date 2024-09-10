// This file extends the mw.log skeleton defined in startup/mediawiki.js.
// Code that is not needed by mw.loader is placed here.

/* eslint-disable no-console */

/**
 * Log debug messages and developer warnings to the browser console.
 *
 * See [mw.log()]{@link mw.log(2)} for verbose debug logging.
 *
 * @namespace mw.log
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
	let stacks;

	return function isFirst() {
		if ( !stacks ) {
			stacks = new Set();
		}
		const stack = new Error().stack;
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
 * @since 1.26
 * @method
 * @param {...Mixed} msg Messages to output to console
 */
mw.log.error = Function.prototype.bind.call( console.error, console );

/**
 * Create a function that logs a deprecation warning when called.
 *
 * @example
 * var deprecatedNoB = mw.log.makeDeprecated( 'hello_without_b', 'Use of hello without b is deprecated.' );
 *
 * function hello( a, b ) {
 *   if ( b === undefined ) {
 *     deprecatedNoB();
 *     b = 0;
 *   }
 *   return a + b;
 * }
 *
 * hello( 1 );
 *
 * @since 1.38
 * @param {string|null} key Name of the feature for deprecation tracker,
 *  or null for a console-only deprecation.
 * @param {string} msg Deprecation warning.
 * @return {Function}
 */
mw.log.makeDeprecated = function ( key, msg ) {
	const isFirst = stackSet();
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
 * @example
 * mw.log.deprecate( window, 'myGlobalFn', myGlobalFn );
 *
 * @example
 * mw.log.deprecate( Thing, 'old', old, 'Use Other.thing instead', 'Thing.old'  );
 *
 * @param {Object} obj Host object of deprecated property
 * @param {string} key Name of property to create in `obj`
 * @param {any} val The value this property should return when accessed
 * @param {string} [msg] Optional extra text to add to the deprecation warning
 * @param {string} [logName] Name of the feature for deprecation tracker.
 *  Tracking is disabled by default, except for global variables on `window`.
 */
mw.log.deprecate = function ( obj, key, val, msg, logName ) {
	const maybeLog = mw.log.makeDeprecated(
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
