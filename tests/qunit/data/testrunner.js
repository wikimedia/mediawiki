( function ( $, mw, QUnit, undefined ) {
"use strict";

var mwTestIgnore, mwTester, addons;

/**
 * Add bogus to url to prevent IE crazy caching
 *
 * @param value {String} a relative path (eg. 'data/defineTestCallback.js'
 * or 'data/test.php?foo=bar').
 * @return {String} Such as 'data/defineTestCallback.js?131031765087663960'
 */
QUnit.fixurl = function (value) {
	return value + (/\?/.test( value ) ? '&' : '?')
		+ String( new Date().getTime() )
		+ String( parseInt( Math.random()*100000, 10 ) );
};

/**
 * Configuration
 */
QUnit.config.testTimeout = 5000;

/**
 * MediaWiki debug mode
 */
QUnit.config.urlConfig.push( 'debug' );

/**
 *  Load TestSwarm agent
 */
if ( QUnit.urlParams.swarmURL  ) {
	document.write( "<scr" + "ipt src='" + QUnit.fixurl( mw.config.get( 'wgScriptPath' )
		+ '/tests/qunit/data/testwarm.inject.js' ) + "'></scr" + "ipt>" );
}

/**
 * CompletenessTest
 */
// Adds toggle checkbox to header
QUnit.config.urlConfig.push( 'completenesstest' );

// Initiate when enabled
if ( QUnit.urlParams.completenesstest ) {

	// Return true to ignore
	mwTestIgnore = function ( val, tester, funcPath ) {

		// Don't record methods of the properties of constructors,
		// to avoid getting into a loop (prototype.constructor.prototype..).
		// Since we're therefor skipping any injection for
		// "new mw.Foo()", manually set it to true here.
		if ( val instanceof mw.Map ) {
			tester.methodCallTracker.Map = true;
			return true;
		}
		if ( val instanceof mw.Title ) {
			tester.methodCallTracker.Title = true;
			return true;
		}

		// Don't record methods of the properties of a jQuery object
		if ( val instanceof $ ) {
			return true;
		}

		return false;
	};

	mwTester = new CompletenessTest( mw, mwTestIgnore );
}

/**
 * Test environment recommended for all QUnit test modules
 */
// Whether to log environment changes to the console
QUnit.config.urlConfig.push( 'mwlogenv' );

/**
 * Reset mw.config to a fresh copy of the live config for each test();
 * @param override {Object} [optional]
 * @example:
 * <code>
 * module( .., newMwEnvironment() );
 *
 * test( .., function () {
 *     mw.config.set( 'foo', 'bar' ); // just for this test
 * } );
 *
 * test( .., function () {
 *     mw.config.get( 'foo' ); // doesn't exist
 * } );
 *
 *
 * module( .., newMwEnvironment({ quux: 'corge' }) );
 *
 * test( .., function () {
 *     mw.config.get( 'quux' ); // "corge"
 *     mw.config.set( 'quux', "grault" );
 * } );
 *
 * test( .., function () {
 *     mw.config.get( 'quux' ); // "corge"
 * } );
 * </code>
 */
QUnit.newMwEnvironment = ( function () {
	var liveConfig, freshConfigCopy, log;

	liveConfig = mw.config.values;

	freshConfigCopy = function ( custom ) {
		// "deep=true" is important here.
		// Otherwise we just create a new object with values referring to live config.
		// e.g. mw.config.set( 'wgFileExtensions', [] ) would not effect liveConfig,
		// but mw.config.get( 'wgFileExtensions' ).push( 'png' ) would as the array
		// was passed by reference in $.extend's loop.
		return $.extend({}, liveConfig, custom, /*deep=*/true );
	};

	log = QUnit.urlParams.mwlogenv ? mw.log : function () {};

	return function ( override ) {
		override = override || {};

		return {
			setup: function () {
				log( 'MwEnvironment> SETUP    for "' + QUnit.config.current.module
					+ ': ' + QUnit.config.current.testName + '"' );
				// Greetings, mock configuration!
				mw.config.values = freshConfigCopy( override );
			},

			teardown: function () {
				log( 'MwEnvironment> TEARDOWN for "' + QUnit.config.current.module
					+ ': ' + QUnit.config.current.testName + '"' );
				// Farewell, mock configuration!
				mw.config.values = liveConfig;
			}
		};
	};
}() );

/**
 * Add-on assertion helpers
 */
// Define the add-ons
addons = {

	// Expect boolean true
	assertTrue: function ( actual, message ) {
		strictEqual( actual, true, message );
	},

	// Expect boolean false
	assertFalse: function ( actual, message ) {
		strictEqual( actual, false, message );
	},

	// Expect numerical value less than X
	lt: function ( actual, expected, message ) {
		QUnit.push( actual < expected, actual, 'less than ' + expected, message );
	},

	// Expect numerical value less than or equal to X
	ltOrEq: function ( actual, expected, message ) {
		QUnit.push( actual <= expected, actual, 'less than or equal to ' + expected, message );
	},

	// Expect numerical value greater than X
	gt: function ( actual, expected, message ) {
		QUnit.push( actual > expected, actual, 'greater than ' + expected, message );
	},

	// Expect numerical value greater than or equal to X
	gtOrEq: function ( actual, expected, message ) {
		QUnit.push( actual >= expected, actual, 'greater than or equal to ' + expected, message );
	},

	// Backwards compatible with new verions of QUnit
	equals: window.equal,
	same: window.deepEqual
};

$.extend( QUnit, addons );
$.extend( window, addons );

})( jQuery, mediaWiki, QUnit );
