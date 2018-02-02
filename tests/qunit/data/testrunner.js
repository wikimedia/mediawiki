/* global sinon */
( function ( $, mw, QUnit ) {
	'use strict';

	var addons, nested;

	/**
	 * Make a safe copy of localEnv:
	 * - Creates a new object that inherits, instead of modifying the original.
	 *   This prevents recursion in the event that a test suite stores inherits
	 *   hooks object statically and passes it to multiple QUnit.module() calls.
	 * - Supporting QUnit 1.x 'setup' and 'teardown' hooks
	 *   (deprecated in QUnit 1.16, removed in QUnit 2).
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

	/**
	 * Add bogus to url to prevent IE crazy caching
	 *
	 * @param {string} value a relative path (eg. 'data/foo.js'
	 * or 'data/test.php?foo=bar').
	 * @return {string} Such as 'data/foo.js?131031765087663960'
	 */
	QUnit.fixurl = function ( value ) {
		return value + ( /\?/.test( value ) ? '&' : '?' )
			+ String( new Date().getTime() )
			+ String( parseInt( Math.random() * 100000, 10 ) );
	};

	/**
	 * Configuration
	 */

	// For each test() that is asynchronous, allow this time to pass before
	// killing the test and assuming timeout failure.
	QUnit.config.testTimeout = 60 * 1000;

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

	/**
	 * SinonJS
	 *
	 * Glue code for nicer integration with QUnit setup/teardown
	 * Inspired by http://sinonjs.org/releases/sinon-qunit-1.0.0.js
	 */
	sinon.assert.fail = function ( msg ) {
		QUnit.assert.ok( false, msg );
	};
	sinon.assert.pass = function ( msg ) {
		QUnit.assert.ok( true, msg );
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
		var orgModule = QUnit.module;
		QUnit.module = function ( name, localEnv, executeNow ) {
			var orgExecute, orgBeforeEach, orgAfterEach;
			if ( nested ) {
				// In a nested module, don't re-add our hooks, QUnit does that already.
				return orgModule.apply( this, arguments );
			}
			if ( arguments.length === 2 && typeof localEnv === 'function' ) {
				executeNow = localEnv;
				localEnv = undefined;
			}
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
			orgBeforeEach = localEnv.beforeEach;
			orgAfterEach = localEnv.afterEach;

			localEnv.beforeEach = function () {
				// Sinon sandbox
				var config = sinon.getConfig( sinon.config );
				config.injectInto = this;
				sinon.sandbox.create( config );

				// Fixture element
				this.fixture = document.createElement( 'div' );
				this.fixture.id = 'qunit-fixture';
				document.body.appendChild( this.fixture );

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
				this.fixture.parentNode.removeChild( this.fixture );
				return ret;
			};

			return orgModule( name, localEnv, executeNow );
		};
	}() );

	/**
	 * Reset mw.config and others to a fresh copy of the live config for each test(),
	 * and restore it back to the live one afterwards.
	 *
	 * @param {Object} [localEnv]
	 * @example (see test suite at the bottom of this file)
	 * </code>
	 */
	QUnit.newMwEnvironment = ( function () {
		var warn, error, liveConfig, liveMessages,
			MwMap = mw.config.constructor, // internal use only
			ajaxRequests = [];

		liveConfig = mw.config;
		liveMessages = mw.messages;

		function suppressWarnings() {
			if ( warn === undefined ) {
				warn = mw.log.warn;
				error = mw.log.error;
				mw.log.warn = mw.log.error = $.noop;
			}
		}

		function restoreWarnings() {
			// Guard against calls not balanced with suppressWarnings()
			if ( warn !== undefined ) {
				mw.log.warn = warn;
				mw.log.error = error;
				warn = error = undefined;
			}
		}

		function freshConfigCopy( custom ) {
			var copy;
			// Tests should mock all factors that directly influence the tested code.
			// For backwards compatibility though we set mw.config to a fresh copy of the live
			// config. This way any modifications made to mw.config during the test will not
			// affect other tests, nor the global scope outside the test runner.
			// This is a shallow copy, since overriding an array or object value via "custom"
			// should replace it. Setting a config property means you override it, not extend it.
			// NOTE: It is important that we suppress warnings because extend() will also access
			// deprecated properties and trigger deprecation warnings from mw.log#deprecate.
			suppressWarnings();
			copy = $.extend( {}, liveConfig.get(), custom );
			restoreWarnings();

			return copy;
		}

		function freshMessagesCopy( custom ) {
			return $.extend( /* deep */true, {}, liveMessages.get(), custom );
		}

		/**
		 * @param {jQuery.Event} event
		 * @param {jqXHR} jqXHR
		 * @param {Object} ajaxOptions
		 */
		function trackAjax( event, jqXHR, ajaxOptions ) {
			ajaxRequests.push( { xhr: jqXHR, options: ajaxOptions } );
		}

		return function ( orgEnv ) {
			var localEnv, orgBeforeEach, orgAfterEach;

			localEnv = makeSafeEnv( orgEnv );
			// MediaWiki env testing
			localEnv.config = localEnv.config || {};
			localEnv.messages = localEnv.messages || {};

			orgBeforeEach = localEnv.beforeEach;
			orgAfterEach = localEnv.afterEach;

			localEnv.beforeEach = function () {
				// Greetings, mock environment!
				mw.config = new MwMap();
				mw.config.set( freshConfigCopy( localEnv.config ) );
				mw.messages = new MwMap();
				mw.messages.set( freshMessagesCopy( localEnv.messages ) );
				// Update reference to mw.messages
				mw.jqueryMsg.setParserDefaults( {
					messages: mw.messages
				} );

				this.suppressWarnings = suppressWarnings;
				this.restoreWarnings = restoreWarnings;

				// Start tracking ajax requests
				$( document ).on( 'ajaxSend', trackAjax );

				if ( orgBeforeEach ) {
					return orgBeforeEach.apply( this, arguments );
				}
			};
			localEnv.afterEach = function () {
				var timers, pending, $activeLen, ret;

				if ( orgAfterEach ) {
					ret = orgAfterEach.apply( this, arguments );
				}

				// Stop tracking ajax requests
				$( document ).off( 'ajaxSend', trackAjax );

				// As a convenience feature, automatically restore warnings if they're
				// still suppressed by the end of the test.
				restoreWarnings();

				// Farewell, mock environment!
				mw.config = liveConfig;
				mw.messages = liveMessages;
				// Restore reference to mw.messages
				mw.jqueryMsg.setParserDefaults( {
					messages: liveMessages
				} );

				// Tests should use fake timers or wait for animations to complete
				// Check for incomplete animations/requests/etc and throw if there are any.
				if ( $.timers && $.timers.length !== 0 ) {
					timers = $.timers.length;
					$.each( $.timers, function ( i, timer ) {
						var node = timer.elem;
						mw.log.warn( 'Unfinished animation #' + i + ' in ' + timer.queue + ' queue on ' +
							mw.html.element( node.nodeName.toLowerCase(), $( node ).getAttrs() )
						);
					} );
					// Force animations to stop to give the next test a clean start
					$.timers = [];
					$.fx.stop();

					throw new Error( 'Unfinished animations: ' + timers );
				}

				// Test should use fake XHR, wait for requests, or call abort()
				$activeLen = $.active;
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
		if ( node.nodeType === Node.ELEMENT_NODE ) {
			children = $node.contents();
			processedChildren = [];
			for ( i = 0, len = children.length; i < len; i++ ) {
				el = children[ i ];
				if ( el.nodeType === Node.ELEMENT_NODE || el.nodeType === Node.TEXT_NODE ) {
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
		var el = $( '<div>' ).append( html )[ 0 ];
		return getDomStructure( el );
	}

	/**
	 * Add-on assertion helpers
	 */
	// Define the add-ons
	addons = {

		// Expect boolean true
		assertTrue: function ( actual, message ) {
			this.pushResult( {
				result: actual === true,
				actual: actual,
				expected: true,
				message: message
			} );
		},

		// Expect boolean false
		assertFalse: function ( actual, message ) {
			this.pushResult( {
				result: actual === false,
				actual: actual,
				expected: false,
				message: message
			} );
		},

		// Expect numerical value less than X
		lt: function ( actual, expected, message ) {
			this.pushResult( {
				result: actual < expected,
				actual: actual,
				expected: 'less than ' + expected,
				message: message
			} );
		},

		// Expect numerical value less than or equal to X
		ltOrEq: function ( actual, expected, message ) {
			this.pushResult( {
				result: actual <= expected,
				actual: actual,
				expected: 'less than or equal to ' + expected,
				message: message
			} );
		},

		// Expect numerical value greater than X
		gt: function ( actual, expected, message ) {
			this.pushResult( {
				result: actual > expected,
				actual: actual,
				expected: 'greater than ' + expected,
				message: message
			} );
		},

		// Expect numerical value greater than or equal to X
		gtOrEq: function ( actual, expected, message ) {
			this.pushResult( {
				result: actual >= true,
				actual: actual,
				expected: 'greater than or equal to ' + expected,
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

	/**
	 * Small test suite to confirm proper functionality of the utilities and
	 * initializations defined above in this file.
	 */
	QUnit.module( 'testrunner', QUnit.newMwEnvironment( {
		setup: function () {
			this.mwHtmlLive = mw.html;
			mw.html = {
				escape: function () {
					return 'mocked';
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

	QUnit.test( 'Setup', function ( assert ) {
		assert.equal( mw.html.escape( 'foo' ), 'mocked', 'setup() callback was ran.' );
		assert.equal( mw.config.get( 'testVar' ), 'foo', 'config object applied' );
		assert.equal( mw.messages.get( 'testMsg' ), 'Foo.', 'messages object applied' );

		mw.config.set( 'testVar', 'bar' );
		mw.messages.set( 'testMsg', 'Bar.' );
	} );

	QUnit.test( 'Teardown', function ( assert ) {
		assert.equal( mw.config.get( 'testVar' ), 'foo', 'config object restored and re-applied after test()' );
		assert.equal( mw.messages.get( 'testMsg' ), 'Foo.', 'messages object restored and re-applied after test()' );
	} );

	QUnit.test( 'Loader status', function ( assert ) {
		var i, len, state,
			modules = mw.loader.getModuleNames(),
			error = [],
			missing = [];

		for ( i = 0, len = modules.length; i < len; i++ ) {
			state = mw.loader.getState( modules[ i ] );
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

	QUnit.module( 'testrunner-after', QUnit.newMwEnvironment() );

	QUnit.test( 'Teardown', function ( assert ) {
		assert.equal( mw.html.escape( '<' ), '&lt;', 'teardown() callback was ran.' );
		assert.equal( mw.config.get( 'testVar' ), null, 'config object restored to live in next module()' );
		assert.equal( mw.messages.get( 'testMsg' ), null, 'messages object restored to live in next module()' );
	} );

	QUnit.module( 'testrunner-each', {
		beforeEach: function () {
			this.mwHtmlLive = mw.html;
		},
		afterEach: function () {
			mw.html = this.mwHtmlLive;
		}
	} );
	QUnit.test( 'beforeEach', function ( assert ) {
		assert.ok( this.mwHtmlLive, 'setup() ran' );
		mw.html = null;
	} );
	QUnit.test( 'afterEach', function ( assert ) {
		assert.equal( mw.html.escape( '<' ), '&lt;', 'afterEach() ran' );
	} );

	QUnit.module( 'testrunner-each-compat', {
		setup: function () {
			this.mwHtmlLive = mw.html;
		},
		teardown: function () {
			mw.html = this.mwHtmlLive;
		}
	} );
	QUnit.test( 'setup', function ( assert ) {
		assert.ok( this.mwHtmlLive, 'setup() ran' );
		mw.html = null;
	} );
	QUnit.test( 'teardown', function ( assert ) {
		assert.equal( mw.html.escape( '<' ), '&lt;', 'teardown() ran' );
	} );

	// Regression test for 'this.sandbox undefined' error, fixed by
	// ensuring Sinon setup/teardown is not re-run on inner module.
	QUnit.module( 'testrunner-nested', function () {
		QUnit.module( 'testrunner-nested-inner', function () {
			QUnit.test( 'Dummy', function ( assert ) {
				assert.ok( true, 'Nested modules supported' );
			} );
		} );
	} );

	QUnit.module( 'testrunner-hooks-outer', function () {
		var beforeHookWasExecuted = false,
			afterHookWasExecuted = false;
		QUnit.module( 'testrunner-hooks', {
			before: function () {
				beforeHookWasExecuted = true;

				// This way we can be sure that module `testrunner-hook-after` will always
				// be executed after module `testrunner-hooks`
				QUnit.module( 'testrunner-hooks-after' );
				QUnit.test(
					'`after` hook for module `testrunner-hooks` was executed',
					function ( assert ) {
						assert.ok( afterHookWasExecuted );
					}
				);
			},
			after: function () {
				afterHookWasExecuted = true;
			}
		} );

		QUnit.test( '`before` hook was executed', function ( assert ) {
			assert.ok( beforeHookWasExecuted );
		} );
	} );

}( jQuery, mediaWiki, QUnit ) );
