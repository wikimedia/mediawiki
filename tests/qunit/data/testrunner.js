( function ( $, mw, QUnit, undefined ) {
/*global CompletenessTest */
/*jshint evil:true */
'use strict';

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
QUnit.config.urlConfig.push( {
	id: 'debug',
	label: 'Enable ResourceLoaderDebug',
	tooltip: 'Enable debug mode in ResourceLoader'
} );

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
QUnit.config.urlConfig.push( {
	id: 'completenesstest',
	label: 'Run CompletenessTest',
	tooltip: 'Run the completeness test'
} );

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
 * Reset mw.config and others to a fresh copy of the live config for each test(),
 * and restore it back to the live one afterwards.
 * @param localEnv {Object} [optional]
 * @example (see test suite at the bottom of this file)
 * </code>
 */
QUnit.newMwEnvironment = ( function () {
	var log, liveConfig, liveMessages;

	liveConfig = mw.config.values;
	liveMessages = mw.messages.values;

	function freshConfigCopy( custom ) {
		// "deep=true" is important here.
		// Otherwise we just create a new object with values referring to live config.
		// e.g. mw.config.set( 'wgFileExtensions', [] ) would not effect liveConfig,
		// but mw.config.get( 'wgFileExtensions' ).push( 'png' ) would as the array
		// was passed by reference in $.extend's loop.
		return $.extend( {}, liveConfig, custom, /*deep=*/true );
	}

	function freshMessagesCopy( custom ) {
		return $.extend( {}, liveMessages, custom, /*deep=*/true );
	}

	log = QUnit.urlParams.mwlogenv ? mw.log : function () {};

	return function ( localEnv ) {
		localEnv = $.extend( {
			// QUnit
			setup: $.noop,
			teardown: $.noop,
			// MediaWiki
			config: {},
			messages: {}
		}, localEnv );

		return {
			setup: function () {
				log( 'MwEnvironment> SETUP    for "' + QUnit.config.current.module
					+ ': ' + QUnit.config.current.testName + '"' );

				// Greetings, mock environment!
				mw.config.values = freshConfigCopy( localEnv.config );
				mw.messages.values = freshMessagesCopy( localEnv.messages );

				localEnv.setup();
			},

			teardown: function () {
				log( 'MwEnvironment> TEARDOWN for "' + QUnit.config.current.module
					+ ': ' + QUnit.config.current.testName + '"' );

				localEnv.teardown();

				// Farewell, mock environment!
				mw.config.values = liveConfig;
				mw.messages.values = liveMessages;
			}
		};
	};
}() );

// $.when stops as soon as one fails, which makes sense in most
// practical scenarios, but not in a unit test where we really do
// need to wait until all of them are finished.
QUnit.whenPromisesComplete = function () {
	var altPromises = [];

	$.each( arguments, function ( i, arg ) {
		var alt = $.Deferred();
		altPromises.push( alt );

		// Whether this one fails or not, forwards it to
		// the 'done' (resolve) callback of the alternative promise.
		arg.always( alt.resolve );
	});

	return $.when.apply( $, altPromises );
};

/**
 * Add-on assertion helpers
 */
// Define the add-ons
addons = {

	// Expect boolean true
	assertTrue: function ( actual, message ) {
		QUnit.push( actual === true, actual, true, message );
	},

	// Expect boolean false
	assertFalse: function ( actual, message ) {
		QUnit.push( actual === false, actual, false, message );
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
	}
};

$.extend( QUnit.assert, addons );

/**
 * Small test suite to confirm proper functionality of the utilities and
 * initializations in this file.
 */
var envExecCount = 0;
QUnit.module( 'mediawiki.tests.qunit.testrunner', QUnit.newMwEnvironment({
	setup: function () {
		envExecCount += 1;
		this.mwHtmlLive = mw.html;
		mw.html = {
			escape: function () {
				return 'mocked-' + envExecCount;
			}
		};
	},
	teardown: function () {
		mw.html = this.mwHtmlLive;
	},
	config: {
		testVar: 'foo'
	},
	messages: {
		testMsg: 'Foo.'
	}
}) );

QUnit.test( 'Setup', 3, function ( assert ) {
	assert.equal( mw.html.escape( 'foo' ), 'mocked-1', 'extra setup() callback was ran.' );
	assert.equal( mw.config.get( 'testVar' ), 'foo', 'config object applied' );
	assert.equal( mw.messages.get( 'testMsg' ), 'Foo.', 'messages object applied' );

	mw.config.set( 'testVar', 'bar' );
	mw.messages.set( 'testMsg', 'Bar.' );
});

QUnit.test( 'Teardown', 3, function ( assert ) {
	assert.equal( mw.html.escape( 'foo' ), 'mocked-2', 'extra setup() callback was re-ran.' );
	assert.equal( mw.config.get( 'testVar' ), 'foo', 'config object restored and re-applied after test()' );
	assert.equal( mw.messages.get( 'testMsg' ), 'Foo.', 'messages object restored and re-applied after test()' );
});

QUnit.module( 'mediawiki.tests.qunit.testrunner-after', QUnit.newMwEnvironment() );

QUnit.test( 'Teardown', 3, function ( assert ) {
	assert.equal( mw.html.escape( '<' ), '&lt;', 'extra teardown() callback was ran.' );
	assert.equal( mw.config.get( 'testVar' ), null, 'config object restored to live in next module()' );
	assert.equal( mw.messages.get( 'testMsg' ), null, 'messages object restored to live in next module()' );
});

}( jQuery, mediaWiki, QUnit ) );
