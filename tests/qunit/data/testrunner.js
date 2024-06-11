( function () {
	'use strict';

	// For each test that is asynchronous, allow this time to pass before
	// killing the test and assuming timeout failure.
	QUnit.config.testTimeout = 60 * 1000;

	QUnit.dump.maxDepth = QUnit.config.maxDepth = 20;

	// Reduce default animation duration from 400ms to 0ms for unit tests
	// eslint-disable-next-line no-underscore-dangle
	$.fx.speeds._default = 0;

	// Add a checkbox to QUnit header to toggle MediaWiki ResourceLoader debug mode.
	QUnit.config.urlConfig.push( {
		id: 'debug',
		label: 'Enable ResourceLoaderDebug',
		tooltip: 'Enable debug mode in ResourceLoader',
		value: 'true'
	} );

	// Integrate SinonJS with QUnit
	//
	// - Add a Sinon sandbox to the test context that is automatically
	//   restored at the end of each test.
	// - Forward sinon assertions to QUnit.
	//
	// Inspired by http://sinonjs.org/releases/sinon-qunit-1.0.0.js
	sinon.assert.fail = function ( msg ) {
		QUnit.assert.true( false, msg );
	};
	sinon.assert.pass = function ( msg ) {
		QUnit.assert.true( true, msg );
	};
	sinon.config = {
		injectIntoThis: true,
		injectInto: null,
		properties: [ 'spy', 'stub', 'mock', 'sandbox' ],
		// Don't fake timers by default
		useFakeTimers: false,
		useFakeServer: false
	};
	QUnit.hooks.beforeEach( function () {
		// Sinon sandbox
		var config = sinon.getConfig( sinon.config );
		config.injectInto = this;
		sinon.sandbox.create( config );
	} );
	QUnit.hooks.afterEach( function () {
		this.sandbox.verifyAndRestore();
	} );

	/**
	 * Ensure mw.config and other `mw` singleton state is prestine for each test.
	 *
	 * Example:
	 *
	 *     QUnit.module('mw.myModule', QUnit.newMwEnvironment() );
	 *
	 *     QUnit.module('mw.myModule', QUnit.newMwEnvironment( {
	 *         config: {
	 *             wgServer: 'https://example.org'
	 *         },
	 *         messages: {
	 *             'monday-short': 'Monday'
	 *         }
	 *     } );
	 *
	 * @param {Object} [localEnv]
	 * @param {Object} [localEnv.config]
	 * @param {Object} [localEnv.messages]
	 */
	QUnit.newMwEnvironment = ( function () {
		var deepClone = typeof structuredClone === 'function' ? structuredClone : function ( obj ) {
			return $.extend( /* deep */ true, {}, obj );
		};
		var liveConfig = mw.config.values;
		var liveMessages = mw.messages.values;
		var liveWarnFn = mw.log.warn;
		var liveErrorFn = mw.log.error;

		function suppressWarnings() {
			mw.log.warn = mw.log.error = function () {};
		}

		function restoreWarnings() {
			mw.log.warn = liveWarnFn;
			mw.log.error = liveErrorFn;
		}

		return function newMwEnvironment( localEnv ) {
			localEnv = localEnv || {};

			var orgBeforeEach = localEnv.beforeEach;
			var orgAfterEach = localEnv.afterEach;

			localEnv.beforeEach = function () {
				mw.config.values = deepClone( liveConfig );
				if ( localEnv.config ) {
					mw.config.set( localEnv.config );
				}

				mw.messages.values = deepClone( liveMessages );
				if ( localEnv.messages ) {
					mw.messages.set( localEnv.messages );
				}

				this.suppressWarnings = suppressWarnings;
				this.restoreWarnings = restoreWarnings;

				if ( orgBeforeEach ) {
					return orgBeforeEach.apply( this, arguments );
				}
			};
			localEnv.afterEach = function () {
				var ret;
				if ( orgAfterEach ) {
					ret = orgAfterEach.apply( this, arguments );
				}

				// For convenience and to avoid leakage, always restore after each test.
				// Restoring earlier is allowed.
				restoreWarnings();

				mw.config.values = liveConfig;
				mw.messages.values = liveMessages;

				// Stop animations to ensure a clean start for the next test
				$.timers = [];
				$.fx.stop();

				return ret;
			};
			return localEnv;
		};
	}() );

	/**
	 * Wait for multiple promises to have finished.
	 *
	 * This differs from `$.when`, which stops as soon as one fails,
	 * which makes sense in a production context, but not in a test
	 * where we really do need to wait until all are finished before
	 * moving on.
	 *
	 * @return {jQuery.Promise}
	 */
	QUnit.whenPromisesComplete = function () {
		var altPromises = [];

		// eslint-disable-next-line no-jquery/no-each-util
		$.each( arguments, ( i, arg ) => {
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
		if ( node.nodeType === Node.ELEMENT_NODE ) {
			var processedChildren = [];
			$( node ).contents().each( ( i, el ) => {
				if ( el.nodeType === Node.ELEMENT_NODE || el.nodeType === Node.TEXT_NODE ) {
					processedChildren.push( getDomStructure( el ) );
				}
			} );

			var attribs = {};
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( node.attributes, ( i, attrib ) => {
				attribs[ attrib.name ] = attrib.value;
			} );

			return {
				tagName: node.tagName,
				attributes: attribs,
				contents: processedChildren
			};
		} else {
			// Should be text node
			return node.textContent;
		}
	}

	/**
	 * Get structure of node for this HTML.
	 *
	 * @param {string} html HTML markup for one or more nodes.
	 * @return {Object}
	 */
	function getHtmlStructure( html ) {
		var el = $( '<div>' ).append( html )[ 0 ];
		return getDomStructure( el );
	}

	var addons = {

		/**
		 * Assert numerical value less than X
		 *
		 * @param {Mixed} actual
		 * @param {number} expected
		 * @param {string} [message]
		 */
		lt: function ( actual, expected, message ) {
			this.pushResult( {
				result: actual < expected,
				actual: actual,
				expected: 'less than ' + expected,
				message: message
			} );
		},

		/**
		 * Assert numerical value less than or equal to X
		 *
		 * @param {Mixed} actual
		 * @param {number} expected
		 * @param {string} [message]
		 */
		ltOrEq: function ( actual, expected, message ) {
			this.pushResult( {
				result: actual <= expected,
				actual: actual,
				expected: 'less than or equal to ' + expected,
				message: message
			} );
		},

		/**
		 * Assert numerical value greater than X
		 *
		 * @param {Mixed} actual
		 * @param {number} expected
		 * @param {string} [message]
		 */
		gt: function ( actual, expected, message ) {
			this.pushResult( {
				result: actual > expected,
				actual: actual,
				expected: 'greater than ' + expected,
				message: message
			} );
		},

		/**
		 * Assert numerical value greater than or equal to X
		 *
		 * @param {Mixed} actual
		 * @param {number} expected
		 * @param {string} [message]
		 */
		gtOrEq: function ( actual, expected, message ) {
			this.pushResult( {
				result: actual >= true,
				actual: actual,
				expected: 'greater than or equal to ' + expected,
				message: message
			} );
		},

		/**
		 * Asserts that two DOM nodes are structurally equivalent.
		 *
		 * @param {HTMLElement} actual
		 * @param {Object} expectedStruct
		 * @param {string} message Assertion message.
		 */
		domEqual: function ( actual, expectedStruct, message ) {
			var actualStruct = getDomStructure( actual );
			this.pushResult( {
				result: QUnit.equiv( actualStruct, expectedStruct ),
				actual: actualStruct,
				expected: expectedStruct,
				message: message
			} );
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
			this.pushResult( {
				result: QUnit.equiv( actual, expected ),
				actual: actual,
				expected: expected,
				message: message
			} );
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

			this.pushResult( {
				result: !QUnit.equiv( actual, expected ),
				actual: actual,
				expected: expected,
				message: message,
				negative: true
			} );
		}
	};

	Object.assign( QUnit.assert, addons );

	// Small test suite to confirm proper functionality of the utilities and
	// initializations defined above in this file.
	QUnit.module( 'testrunner', QUnit.newMwEnvironment( {
		beforeEach: function () {
			this.mwHtmlLive = mw.html;
			mw.html = {
				escape: function () {
					return 'mocked';
				}
			};
		},
		afterEach: function () {
			mw.html = this.mwHtmlLive;
		},
		config: {
			testVar: 'foo'
		},
		messages: {
			testMsg: 'Foo.'
		}
	} ), () => {

		QUnit.test( 'beforeEach', ( assert ) => {
			assert.strictEqual( mw.html.escape( 'foo' ), 'mocked', 'callback ran' );
			assert.strictEqual( mw.config.get( 'testVar' ), 'foo', 'config applied' );
			assert.strictEqual( mw.messages.get( 'testMsg' ), 'Foo.', 'messages applied' );

			mw.config.set( 'testVar', 'bar' );
			mw.messages.set( 'testMsg', 'Bar.' );
		} );

		QUnit.test( 'afterEach', ( assert ) => {
			assert.strictEqual( mw.config.get( 'testVar' ), 'foo', 'config restored' );
			assert.strictEqual( mw.messages.get( 'testMsg' ), 'Foo.', 'messages restored' );
		} );

		QUnit.test( 'Loader status', ( assert ) => {
			var modules = mw.loader.getModuleNames();
			var error = [];
			var missing = [];

			for ( var i = 0; i < modules.length; i++ ) {
				var state = mw.loader.getState( modules[ i ] );
				if ( state === 'error' ) {
					error.push( modules[ i ] );
				} else if ( state === 'missing' ) {
					missing.push( modules[ i ] );
				}
			}

			assert.deepEqual( error, [], 'Modules in error state' );
			assert.deepEqual( missing, [], 'Modules in missing state' );
		} );

		QUnit.test( 'assert.htmlEqual', ( assert ) => {
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

		var beforeEachRan = false;
		QUnit.module( 'testrunner-nested-hooks', {
			beforeEach: function () {
				beforeEachRan = true;
			}
		} );

		QUnit.test( 'beforeEach', ( assert ) => {
			assert.true( beforeEachRan );
		} );
	} );

	QUnit.module( 'testrunner-next' );

	QUnit.test( 'afterEach', ( assert ) => {
		assert.strictEqual( mw.html.escape( '<' ), '&lt;', 'mock not leaked to next module' );
		assert.strictEqual( mw.config.get( 'testVar' ), null, 'config not leaked to next module' );
		assert.strictEqual( mw.messages.get( 'testMsg' ), null, 'messages not lekaed to next module' );
	} );

}() );
