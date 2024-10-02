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
		const config = sinon.getConfig( sinon.config );
		config.injectInto = this;
		sinon.sandbox.create( config );
	} );
	QUnit.hooks.afterEach( function () {
		this.sandbox.verifyAndRestore();
	} );

	const deepClone = typeof structuredClone === 'function' ? structuredClone : function ( obj ) {
		return $.extend( /* deep */ true, {}, obj );
	};

	const liveConfig = mw.config.values;
	const liveMessages = mw.messages.values;
	const liveWarnFn = mw.log.warn;
	const liveErrorFn = mw.log.error;
	const noopFn = function () {};

	function suppressWarnings() {
		mw.log.warn = mw.log.error = noopFn;
	}

	function restoreWarnings() {
		mw.log.warn = liveWarnFn;
		mw.log.error = liveErrorFn;
	}

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
	QUnit.newMwEnvironment = function newMwEnvironment( localEnv ) {
		localEnv = localEnv || {};

		const orgBeforeEach = localEnv.beforeEach;
		const orgAfterEach = localEnv.afterEach;

		localEnv.beforeEach = function () {
			mw.config.values = deepClone( liveConfig );
			if ( localEnv.config ) {
				mw.config.set( localEnv.config );
			}

			// Start with a clean message store.
			// Optimization: Use fast empty object instead of deep clone to preserve
			// server response (on mediawiki-wmf-quibble with 2000 tests, reduces
			// newMwEnvironment_beforeEach from 3.7s to 0.7s). ResourceLoader runs
			// tests with lang=qqx so tests shouldn't rely on these anyway.
			mw.messages.values = {};
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
			let ret;
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
		const altPromises = [];

		// eslint-disable-next-line no-jquery/no-each-util
		$.each( arguments, ( i, arg ) => {
			const alt = $.Deferred();
			altPromises.push( alt );

			// Whether this one fails or not, forwards it to
			// the 'done' (resolve) callback of the alternative promise.
			arg.always( alt.resolve );
		} );

		return $.when( ...altPromises );
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
			const processedChildren = [];
			$( node ).contents().each( ( i, el ) => {
				if ( el.nodeType === Node.ELEMENT_NODE || el.nodeType === Node.TEXT_NODE ) {
					processedChildren.push( getDomStructure( el ) );
				}
			} );

			const attribs = {};
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
		const el = $( '<div>' ).append( html )[ 0 ];
		return getDomStructure( el );
	}

	const addons = {

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
			const actualStruct = getDomStructure( actual );
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
			const actual = getHtmlStructure( actualHtml ),
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
			const actual = getHtmlStructure( actualHtml ),
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

	QUnit.begin( () => {
		// Run a few quick environment checks to make sure the above is all working correctly.
		// We run this as a plugin callback with QUnit.onUncaughtException() instead of as
		// a normal QUnit.module() or QUnit.test() because:
		//
		// 1. This way it always runs first, instead of out of order when using `seed`,
		//    or when reloading which runs previously failed tests first.
		// 2. Reduce noise in the output.
		// 3. Makes it actually run instead of skipped when selecting a single module,
		//    or re-running a single test.
		const issues = [];
		function ensure( ok, issue ) {
			if ( !ok ) {
				issues.push( issue );
			}
		}
		const env = QUnit.newMwEnvironment( {
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
		} );

		env.beforeEach();
		ensure( mw.html.escape( 'foo' ) === 'mocked', 'newMwEnvironment did not call beforeEach()' );
		ensure( mw.config.get( 'testVar' ) === 'foo', 'newMwEnvironment did not apply config' );
		ensure( mw.messages.get( 'testMsg' ) === 'Foo.', 'newMwEnvironment did not apply messages' );

		mw.config.set( 'testVar', 'bar' );
		mw.messages.set( 'testMsg', 'Bar.' );
		env.afterEach();
		env.beforeEach();
		ensure( mw.config.get( 'testVar' ) === 'foo', 'newMwEnvironment failed to restore config' );
		ensure( mw.messages.get( 'testMsg' ) === 'Foo.', 'newMwEnvironment failed to restore messages' );

		env.afterEach();
		ensure( mw.html.escape( '<' ) === '&lt;', 'newMwEnvironment did not call afterEach()' );
		ensure( mw.config.get( 'testVar' ) === null, 'newMwEnvironment leaks config' );
		ensure( mw.messages.get( 'testMsg' ) === null, 'newMwEnvironment leaks messages' );

		mw.loader.getModuleNames().forEach( ( name ) => {
			const state = mw.loader.getState( name );
			if ( state === 'error' ) {
				issues.push( `Module "${ name }" in error state` );
			} else if ( state === 'missing' ) {
				issues.push( `Missing "${ name }" module dependency` );
			}
		} );

		if ( issues.length ) {
			QUnit.onUncaughtException( 'testrunner.js found the following issues:\n * ' + issues.join( '\n * ' ) );
		}
	} );

	QUnit.module( 'testrunner', () => {
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
	} );
}() );
