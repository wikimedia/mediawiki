( function () {
	'use strict';

	/**
	 * Make a safe copy of localEnv:
	 * - Creates a new object that inherits, instead of modifying the original.
	 *   This prevents recursion in the event that a test suite stores inherits
	 *   hooks object statically and passes it to multiple QUnit.module() calls.
	 * - Supporting QUnit 1.x 'setup' and 'teardown' hooks
	 *   (deprecated in QUnit 1.16, removed in QUnit 2).
	 *
	 * @param {Object} localEnv
	 * @return {Object}
	 */
	function makeSafeEnv( localEnv ) {
		var wrap = localEnv ? Object.create( localEnv ) : {};
		if ( wrap.setup ) {
			wrap.beforeEach = wrap.beforeEach || wrap.setup;
		}
		if ( wrap.teardown ) {
			wrap.afterEach = wrap.afterEach || wrap.teardown;
		}
		return wrap;
	}

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

	// Create initial fixture element
	var fixture = document.createElement( 'div' );
	fixture.id = 'qunit-fixture';
	document.body.appendChild( fixture );

	// SinonJS
	//
	// Glue code for nicer integration with QUnit setup/teardown
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
	// Extend QUnit.module with:
	// - Add support for QUnit 1.x 'setup' and 'teardown' hooks
	// - Add a Sinon sandbox to the test context.
	// - Add a test fixture to the test context.
	( function () {
		var nested;
		var orgModule = QUnit.module;
		QUnit.module = function ( name, localEnv, executeNow ) {
			if ( nested ) {
				// In a nested module, don't re-add our hooks, QUnit does that already.
				return orgModule.apply( this, arguments );
			}
			if ( arguments.length === 2 && typeof localEnv === 'function' ) {
				executeNow = localEnv;
				localEnv = undefined;
			}
			var orgExecute;
			if ( executeNow ) {
				// Wrap executeNow() so that we can detect nested modules
				orgExecute = executeNow;
				executeNow = function () {
					var ret;
					nested = true;
					ret = orgExecute.apply( this, arguments );
					nested = false;
					return ret;
				};
			}

			localEnv = makeSafeEnv( localEnv );
			var orgBeforeEach = localEnv.beforeEach;
			var orgAfterEach = localEnv.afterEach;

			localEnv.beforeEach = function () {
				// Sinon sandbox
				var config = sinon.getConfig( sinon.config );
				config.injectInto = this;
				sinon.sandbox.create( config );

				if ( orgBeforeEach ) {
					return orgBeforeEach.apply( this, arguments );
				}
			};
			localEnv.afterEach = function () {
				var ret;
				if ( orgAfterEach ) {
					ret = orgAfterEach.apply( this, arguments );
				}

				this.sandbox.verifyAndRestore();

				return ret;
			};

			return orgModule( name, localEnv, executeNow );
		};
	}() );

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
		// eslint-disable-next-line no-undef
		var deepClone = typeof structuredClone === 'function' ? structuredClone : function ( obj ) {
			return $.extend( /* deep */ true, {}, obj );
		};
		var liveConfig = mw.config.values;
		var liveMessages = mw.messages.values;

		var warnFn;
		var errorFn;
		function suppressWarnings() {
			if ( warnFn === undefined ) {
				warnFn = mw.log.warn;
				errorFn = mw.log.error;
				mw.log.warn = mw.log.error = function () {};
			}
		}

		function restoreWarnings() {
			// Guard against calls not balanced with suppressWarnings()
			if ( warnFn !== undefined ) {
				mw.log.warn = warnFn;
				mw.log.error = errorFn;
				warnFn = errorFn = undefined;
			}
		}

		var ajaxRequests = [];

		/**
		 * @param {jQuery.Event} event
		 * @param {jQuery.jqXHR} jqXHR
		 * @param {Object} ajaxOptions
		 */
		function trackAjax( event, jqXHR, ajaxOptions ) {
			ajaxRequests.push( { xhr: jqXHR, options: ajaxOptions } );
		}

		return function ( orgEnv ) {
			var localEnv = makeSafeEnv( orgEnv );

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

				// Start tracking ajax requests
				$( document ).on( 'ajaxSend', trackAjax );

				if ( orgBeforeEach ) {
					return orgBeforeEach.apply( this, arguments );
				}
			};
			localEnv.afterEach = function () {
				var ret;
				if ( orgAfterEach ) {
					ret = orgAfterEach.apply( this, arguments );
				}

				// Stop tracking ajax requests
				$( document ).off( 'ajaxSend', trackAjax );

				// As a convenience feature, automatically restore warnings if they're
				// still suppressed by the end of the test.
				restoreWarnings();

				mw.config.values = liveConfig;
				mw.messages.values = liveMessages;

				// Tests should use fake timers or wait for animations to complete
				// Check for incomplete animations/requests/etc and throw if there are any.
				if ( $.timers && $.timers.length !== 0 ) {
					var timerLen = $.timers.length;
					// eslint-disable-next-line no-jquery/no-each-util
					$.each( $.timers, function ( i, timer ) {
						var node = timer.elem;
						var attribs = {};
						// eslint-disable-next-line no-jquery/no-each-util
						$.each( node.attributes, function ( j, attrib ) {
							attribs[ attrib.name ] = attrib.value;
						} );
						mw.log.warn( 'Unfinished animation #' + i + ' in ' + timer.queue + ' queue on ' +
							mw.html.element( node.nodeName.toLowerCase(), attribs )
						);
					} );
					// Force animations to stop to give the next test a clean start
					$.timers = [];
					$.fx.stop();

					throw new Error( 'Unfinished animations: ' + timerLen );
				}

				// Test should use fake XHR, wait for requests, or call abort()
				var $activeLen = $.active;
				var pending;
				if ( $activeLen !== undefined && $activeLen !== 0 ) {
					pending = ajaxRequests.filter( function ( ajax ) {
						return ajax.xhr.state() === 'pending';
					} );
					if ( pending.length !== $activeLen ) {
						mw.log.warn( 'Pending requests does not match jQuery.active count' );
					}
					// Force requests to stop to give the next test a clean start
					ajaxRequests.forEach( function ( ajax, i ) {
						mw.log.warn(
							'AJAX request #' + i + ' (state: ' + ajax.xhr.state() + ')',
							ajax.options
						);
						ajax.xhr.abort();
					} );
					ajaxRequests = [];

					throw new Error( 'Pending AJAX requests: ' + pending.length + ' (active: ' + $activeLen + ')' );
				}

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
		if ( node.nodeType === Node.ELEMENT_NODE ) {
			var processedChildren = [];
			$( node ).contents().each( function ( i, el ) {
				if ( el.nodeType === Node.ELEMENT_NODE || el.nodeType === Node.TEXT_NODE ) {
					processedChildren.push( getDomStructure( el ) );
				}
			} );

			var attribs = {};
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( node.attributes, function ( i, attrib ) {
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
		 * Assert strictly boolean true
		 *
		 * @param {Mixed} actual
		 * @param {string} [message]
		 */
		assertTrue: function ( actual, message ) {
			this.pushResult( {
				result: actual === true,
				actual: actual,
				expected: true,
				message: message
			} );
		},

		/**
		 * Assert strictly boolean false
		 *
		 * @param {Mixed} actual
		 * @param {string} [message]
		 */
		assertFalse: function ( actual, message ) {
			this.pushResult( {
				result: actual === false,
				actual: actual,
				expected: false,
				message: message
			} );
		},

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

	$.extend( QUnit.assert, addons );

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
	} ), function () {

		QUnit.test( 'beforeEach', function ( assert ) {
			assert.strictEqual( mw.html.escape( 'foo' ), 'mocked', 'callback ran' );
			assert.strictEqual( mw.config.get( 'testVar' ), 'foo', 'config applied' );
			assert.strictEqual( mw.messages.get( 'testMsg' ), 'Foo.', 'messages applied' );

			mw.config.set( 'testVar', 'bar' );
			mw.messages.set( 'testMsg', 'Bar.' );
		} );

		QUnit.test( 'afterEach', function ( assert ) {
			assert.strictEqual( mw.config.get( 'testVar' ), 'foo', 'config restored' );
			assert.strictEqual( mw.messages.get( 'testMsg' ), 'Foo.', 'messages restored' );
		} );

		QUnit.test( 'Loader status', function ( assert ) {
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

		QUnit.test( 'assert.htmlEqual', function ( assert ) {
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

		// Regression test for 'this.sandbox undefined' error, fixed by
		// ensuring Sinon setup/teardown is not re-run on inner module.
		QUnit.module( 'testrunner-nested-test', function () {
			QUnit.test( 'example', function ( assert ) {
				assert.true( true, 'nested modules supported' );
			} );
		} );

		var beforeEachRan = false;
		QUnit.module( 'testrunner-nested-hooks', {
			beforeEach: function () {
				beforeEachRan = true;
			}
		} );

		QUnit.test( 'beforeEach', function ( assert ) {
			assert.true( beforeEachRan );
		} );
	} );

	var setupRan = false;
	var teardownRan = false;
	QUnit.module( 'testrunner-compat', {
		// eslint-disable-next-line qunit/no-setup-teardown
		setup: function () {
			setupRan = true;
		},
		// eslint-disable-next-line qunit/no-setup-teardown
		teardown: function () {
			teardownRan = true;
		}
	} );
	QUnit.test( 'setup', function ( assert ) {
		assert.true( setupRan, 'callback ran' );
	} );
	QUnit.test( 'teardown', function ( assert ) {
		assert.true( teardownRan, 'callback ran' );
	} );

	QUnit.module( 'testrunner-next', QUnit.newMwEnvironment() );

	QUnit.test( 'afterEach', function ( assert ) {
		assert.strictEqual( mw.html.escape( '<' ), '&lt;', 'mock not leaked to next module' );
		assert.strictEqual( mw.config.get( 'testVar' ), null, 'config not leaked to next module' );
		assert.strictEqual( mw.messages.get( 'testMsg' ), null, 'messages not lekaed to next module' );
	} );

}() );
