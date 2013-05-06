/*global CompletenessTest */
/*jshint evil: true */
( function ( $, mw, QUnit, undefined ) {
	'use strict';

	var mwTestIgnore, mwTester,
		addons,
		envExecCount,
		ELEMENT_NODE = 1,
		TEXT_NODE = 3;

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

	QUnit.config.requireExpects = true;

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
		jQuery.getScript( QUnit.fixurl( mw.config.get( 'QUnitTestSwarmInjectJSPath' ) ) );
	}

	/**
	 * CompletenessTest
	 *
	 * Adds toggle checkbox to header
	 */
	QUnit.config.urlConfig.push( {
		id: 'completenesstest',
		label: 'Run CompletenessTest',
		tooltip: 'Run the completeness test'
	} );

	// Initiate when enabled
	if ( QUnit.urlParams.completenesstest ) {

		// Return true to ignore
		mwTestIgnore = function ( val, tester ) {

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
	 *
	 * Whether to log environment changes to the console
	 */
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
			// Tests should mock all factors that directly influence the tested code.
			// For backwards compatibility though we set mw.config to a copy of the live config
			// and extend it with the (optionally) given custom settings for this test
			// (instead of starting blank with only the given custmo settings).
			// This is a shallow copy, so we don't end up with settings taking an array value
			// extended with the custom settings - setting a config property means you override it,
			// not extend it.
			return $.extend( {}, liveConfig, custom );
		}

		function freshMessagesCopy( custom ) {
			return $.extend( /*deep=*/true, {}, liveMessages, custom );
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
		} );

		return $.when.apply( $, altPromises );
	};

	/**
	 * Recursively convert a node to a plain object representing its structure.
	 * Only considers attributes and contents (elements and text nodes).
	 * Attribute values are compared strictly and not normalised.
	 *
	 * @param {Node} node
	 * @return {Object|string} Plain JavaScript value representing the node.
	 */
	function getDomStructure( node ) {
		var $node, children, processedChildren, i, len, el;
		$node = $( node );
		if ( node.nodeType === ELEMENT_NODE ) {
			children = $node.contents();
			processedChildren = [];
			for ( i = 0, len = children.length; i < len; i++ ) {
				el = children[i];
				if ( el.nodeType === ELEMENT_NODE || el.nodeType === TEXT_NODE ) {
					processedChildren.push( getDomStructure( el ) );
				}
			}

			return {
				tagName: node.tagName,
				attributes: $node.getAttrs(),
				contents: processedChildren
			};
		} else {
			// Should be text node
			return $node.text();
		}
	}

	/**
	 * Gets structure of node for this HTML.
	 *
	 * @param {string} html HTML markup for one or more nodes.
	 */
	function getHtmlStructure( html ) {
		var el = $( '<div>' ).append( html )[0];
		return getDomStructure( el );
	}

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
		},

		/**
		 * Asserts that two HTML strings are structurally equivalent.
		 *
		 * @param {string} actualHtml Actual HTML markup.
		 * @param {string} expectedHtml Expected HTML markup
		 * @param {string} message Assertion message.
		 */
		htmlEqual: function ( actualHtml, expectedHtml, message ) {
			var actual = getHtmlStructure( actualHtml ),
				expected = getHtmlStructure( expectedHtml );

			QUnit.push(
				QUnit.equiv(
					actual,
					expected
				),
				actual,
				expected,
				message
			);
		},

		/**
		 * Asserts that two HTML strings are not structurally equivalent.
		 *
		 * @param {string} actualHtml Actual HTML markup.
		 * @param {string} expectedHtml Expected HTML markup.
		 * @param {string} message Assertion message.
		 */
		notHtmlEqual: function ( actualHtml, expectedHtml, message ) {
			var actual = getHtmlStructure( actualHtml ),
				expected = getHtmlStructure( expectedHtml );

			QUnit.push(
				!QUnit.equiv(
					actual,
					expected
				),
				actual,
				expected,
				message
			);
		}
	};

	$.extend( QUnit.assert, addons );

	/**
	 * Small test suite to confirm proper functionality of the utilities and
	 * initializations defined above in this file.
	 */
	envExecCount = 0;
	QUnit.module( 'mediawiki.tests.qunit.testrunner', QUnit.newMwEnvironment( {
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
	} ) );

	QUnit.test( 'Setup', 3, function ( assert ) {
		assert.equal( mw.html.escape( 'foo' ), 'mocked-1', 'extra setup() callback was ran.' );
		assert.equal( mw.config.get( 'testVar' ), 'foo', 'config object applied' );
		assert.equal( mw.messages.get( 'testMsg' ), 'Foo.', 'messages object applied' );

		mw.config.set( 'testVar', 'bar' );
		mw.messages.set( 'testMsg', 'Bar.' );
	} );

	QUnit.test( 'Teardown', 3, function ( assert ) {
		assert.equal( mw.html.escape( 'foo' ), 'mocked-2', 'extra setup() callback was re-ran.' );
		assert.equal( mw.config.get( 'testVar' ), 'foo', 'config object restored and re-applied after test()' );
		assert.equal( mw.messages.get( 'testMsg' ), 'Foo.', 'messages object restored and re-applied after test()' );
	} );

	QUnit.test( 'Loader status', 2, function ( assert ) {
		var i, len, state,
			modules = mw.loader.getModuleNames(),
			error = [],
			missing = [];

		for ( i = 0, len = modules.length; i < len; i++ ) {
			state = mw.loader.getState( modules[i] );
			if ( state === 'error' ) {
				error.push( modules[i] );
			} else if ( state === 'missing' ) {
				missing.push( modules[i] );
			}
		}

		assert.deepEqual( error, [], 'Modules in error state' );
		assert.deepEqual( missing, [], 'Modules in missing state' );
	} );

	QUnit.test( 'htmlEqual', 8, function ( assert ) {
		assert.htmlEqual(
			'<div><p class="some classes" data-length="10">Child paragraph with <a href="http://example.com">A link</a></p>Regular text<span>A span</span></div>',
			'<div><p data-length=\'10\'  class=\'some classes\'>Child paragraph with <a href=\'http://example.com\' >A link</a></p>Regular text<span>A span</span></div>',
			'Attribute order, spacing and quotation marks (equal)'
		);

		assert.notHtmlEqual(
			'<div><p class="some classes" data-length="10">Child paragraph with <a href="http://example.com">A link</a></p>Regular text<span>A span</span></div>',
			'<div><p data-length=\'10\'  class=\'some more classes\'>Child paragraph with <a href=\'http://example.com\' >A link</a></p>Regular text<span>A span</span></div>',
			'Attribute order, spacing and quotation marks (not equal)'
		);

		assert.htmlEqual(
			'<label for="firstname" accesskey="f" class="important">First</label><input id="firstname" /><label for="lastname" accesskey="l" class="minor">Last</label><input id="lastname" />',
			'<label for="firstname" accesskey="f" class="important">First</label><input id="firstname" /><label for="lastname" accesskey="l" class="minor">Last</label><input id="lastname" />',
			'Multiple root nodes (equal)'
		);

		assert.notHtmlEqual(
			'<label for="firstname" accesskey="f" class="important">First</label><input id="firstname" /><label for="lastname" accesskey="l" class="minor">Last</label><input id="lastname" />',
			'<label for="firstname" accesskey="f" class="important">First</label><input id="firstname" /><label for="lastname" accesskey="l" class="important" >Last</label><input id="lastname" />',
			'Multiple root nodes (not equal, last label node is different)'
		);

		assert.htmlEqual(
			'fo&quot;o<br/>b&gt;ar',
			'fo"o<br/>b>ar',
			'Extra escaping is equal'
		);
		assert.notHtmlEqual(
			'foo&lt;br/&gt;bar',
			'foo<br/>bar',
			'Text escaping (not equal)'
		);

		assert.htmlEqual(
			'foo<a href="http://example.com">example</a>bar',
			'foo<a href="http://example.com">example</a>bar',
			'Outer text nodes are compared (equal)'
		);

		assert.notHtmlEqual(
			'foo<a href="http://example.com">example</a>bar',
			'foo<a href="http://example.com">example</a>quux',
			'Outer text nodes are compared (last text node different)'
		);

	} );

	QUnit.module( 'mediawiki.tests.qunit.testrunner-after', QUnit.newMwEnvironment() );

	QUnit.test( 'Teardown', 3, function ( assert ) {
		assert.equal( mw.html.escape( '<' ), '&lt;', 'extra teardown() callback was ran.' );
		assert.equal( mw.config.get( 'testVar' ), null, 'config object restored to live in next module()' );
		assert.equal( mw.messages.get( 'testMsg' ), null, 'messages object restored to live in next module()' );
	} );

}( jQuery, mediaWiki, QUnit ) );
