/* eslint-disable no-console */
var log,
	StringSet = require( './StringSet' ),
	track = require( './track' ).track;

log = ( function () {
	// Also update the restoration of methods in mediawiki.log.js
	// when adding or removing methods here.
	var log = function () {},
		console = window.console;

	/**
	 * @class mw.log
	 * @singleton
	 */

	/**
	 * Write a message to the console's warning channel.
	 * Actions not supported by the browser console are silently ignored.
	 *
	 * @param {...string} msg Messages to output to console
	 */
	log.warn = console && console.warn && Function.prototype.bind ?
		Function.prototype.bind.call( console.warn, console ) :
		$.noop;

	/**
	 * Write a message to the console's error channel.
	 *
	 * Most browsers provide a stacktrace by default if the argument
	 * is a caught Error object.
	 *
	 * @since 1.26
	 * @param {Error|...string} msg Messages to output to console
	 */
	log.error = console && console.error && Function.prototype.bind ?
		Function.prototype.bind.call( console.error, console ) :
		$.noop;

	/**
	 * Create a property in a host object that, when accessed, will produce
	 * a deprecation warning in the console.
	 *
	 * @param {Object} obj Host object of deprecated property
	 * @param {string} key Name of property to create in `obj`
	 * @param {Mixed} val The value this property should return when accessed
	 * @param {string} [msg] Optional text to include in the deprecation message
	 * @param {string} [logName=key] Optional custom name for the feature.
	 *  This is used instead of `key` in the message and `mw.deprecate` tracking.
	 */
	log.deprecate = !Object.defineProperty ? function ( obj, key, val ) {
		obj[ key ] = val;
	} : function ( obj, key, val, msg, logName ) {
		var logged = new StringSet();
		logName = logName || key;
		msg = 'Use of "' + logName + '" is deprecated.' + ( msg ? ( ' ' + msg ) : '' );
		function uniqueTrace() {
			var trace = new Error().stack;
			if ( logged.has( trace ) ) {
				return false;
			}
			logged.add( trace );
			return true;
		}
		// Support: Safari 5.0
		// Throws "not supported on DOM Objects" for Node or Element objects (incl. document)
		// Safari 4.0 doesn't have this method, and it was fixed in Safari 5.1.
		try {
			Object.defineProperty( obj, key, {
				configurable: true,
				enumerable: true,
				get: function () {
					if ( uniqueTrace() ) {
						track( 'mw.deprecate', logName );
						log.warn( msg );
					}
					return val;
				},
				set: function ( newVal ) {
					if ( uniqueTrace() ) {
						track( 'mw.deprecate', logName );
						log.warn( msg );
					}
					val = newVal;
				}
			} );
		} catch ( err ) {
			obj[ key ] = val;
		}
	};

	return log;
}() );

module.exports = log;
