( function ( $, mw, QUnit, undefined ) {
"use strict";

var mwTestIgnore, mwTester, addons;

/**
 * Add bogus to url to prevent IE crazy caching
 *
 * @param value {String} a relative path (eg. 'data/foo.js'
 * or 'data/test.php?foo=bar').
 * @return {String} Such as 'data/foo.js?131031765087663960'
 */
QUnit.fixurl = function ( value ) {
	return value + (/\?/.test( value ) ? '&' : '?')
		+ String( new Date().getTime() )
		+ String( parseInt( Math.random() * 100000, 10 ) );
};

/**
 * Configuration
 */

// When a test() indicates asynchronicity with stop(),
// allow 10 seconds to pass before killing the test(),
// and assuming failure.
QUnit.config.testTimeout = 10 * 1000;

// Add a checkbox to QUnit header to toggle MediaWiki ResourceLoader debug mode.
QUnit.config.urlConfig.push( 'debug' );

/**
 * Load TestSwarm agent
 */
// Only if the current url indicates that there is a TestSwarm instance watching us
// (TestSwarm appends swarmURL to the test suites url it loads in iframes).
// Otherwise this is just a simple view of Special:JavaScriptTest/qunit directly,
// no point in loading inject.js in that case. Also, make sure that this instance
// of MediaWiki has actually been configured with the required url to that inject.js
// script. By default it is false.
if ( QUnit.urlParams.swarmURL && mw.config.get( 'QUnitTestSwarmInjectJSPath' ) ) {
	document.write( "<scr" + "ipt src='" + QUnit.fixurl( mw.config.get( 'QUnitTestSwarmInjectJSPath' ) ) + "'></scr" + "ipt>" );
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
	var log, liveConfig, liveMsgs;

	liveConfig = mw.config.values;
	liveMsgs = mw.messages.values;

	function freshConfigCopy( custom ) {
		// "deep=true" is important here.
		// Otherwise we just create a new object with values referring to live config.
		// e.g. mw.config.set( 'wgFileExtensions', [] ) would not effect liveConfig,
		// but mw.config.get( 'wgFileExtensions' ).push( 'png' ) would as the array
		// was passed by reference in $.extend's loop.
		return $.extend({}, liveConfig, custom, /*deep=*/true );
	}

	function freshMsgsCopy( custom ) {
		return $.extend({}, liveMsgs, custom, /*deep=*/true );
	}

	log = QUnit.urlParams.mwlogenv ? mw.log : function () {};

	return function ( overrideConfig, overrideMsgs ) {
		overrideConfig = overrideConfig || {};
		overrideMsgs = overrideMsgs || {};

		return {
			setup: function () {
				log( 'MwEnvironment> SETUP    for "' + QUnit.config.current.module
					+ ': ' + QUnit.config.current.testName + '"' );
				// Greetings, mock environment!
				mw.config.values = freshConfigCopy( overrideConfig );
				mw.messages.values = freshMsgsCopy( overrideMsgs );
			},

			teardown: function () {
				log( 'MwEnvironment> TEARDOWN for "' + QUnit.config.current.module
					+ ': ' + QUnit.config.current.testName + '"' );
				// Farewell, mock environment!
				mw.config.values = liveConfig;
				mw.messages.values = liveMsgs;
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
