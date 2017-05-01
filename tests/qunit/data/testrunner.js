/*global CompletenessTest, sinon */
( function ( $, mw, QUnit ) {
	'use strict';

	var mwTestIgnore, mwTester, addons;

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

	// Add a checkbox to QUnit header to toggle MediaWiki ResourceLoader debug mode.
	QUnit.config.urlConfig.push( {
		id: 'debug',
		label: 'Enable ResourceLoaderDebug',
		tooltip: 'Enable debug mode in ResourceLoader',
		value: 'true'
	} );

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

	/**
	 * SinonJS
	 *
	 * Glue code for nicer integration with QUnit setup/teardown
	 * Inspired by http://sinonjs.org/releases/sinon-qunit-1.0.0.js
	 * Fixes:
	 * - Work properly with asynchronous QUnit by using module setup/teardown
	 *   instead of synchronously wrapping QUnit.test.
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
	( function () {
		var orgModule = QUnit.module;

		QUnit.module = function ( name, localEnv ) {
			localEnv = localEnv || {};
			orgModule( name, {
				setup: function () {
					var config = sinon.getConfig( sinon.config );
					config.injectInto = this;
					sinon.sandbox.create( config );

					if ( localEnv.setup ) {
						localEnv.setup.call( this );
					}
				},
				teardown: function () {
					if ( localEnv.teardown ) {
						localEnv.teardown.call( this );
					}

					this.sandbox.verifyAndRestore();
				}
			} );
		};
	}() );

	// Extend QUnit.module to provide a fixture element.
	( function () {
		var orgModule = QUnit.module;

		QUnit.module = function ( name, localEnv ) {
			var fixture;
			localEnv = localEnv || {};
			orgModule( name, {
				setup: function () {
					fixture = document.createElement( 'div' );
					fixture.id = 'qunit-fixture';
					document.body.appendChild( fixture );

					if ( localEnv.setup ) {
						localEnv.setup.call( this );
					}
				},
				teardown: function () {
					if ( localEnv.teardown ) {
						localEnv.teardown.call( this );
					}

					fixture.parentNode.removeChild( fixture );
				}
			} );
		};
	}() );

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

			// Don't iterate over the module registry (the 'script' references would
			// be listed as untested methods otherwise)
			if ( val === mw.loader.moduleRegistry ) {
				return true;
			}

			return false;
		};

		mwTester = new CompletenessTest( mw, mwTestIgnore );
	}

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
			warn = mw.log.warn;
			error = mw.log.error;
			mw.log.warn = mw.log.error = $.noop;
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
			return $.extend( /*deep=*/true, {}, liveMessages.get(), custom );
		}

		/**
		 * @param {jQuery.Event} event
		 * @param {jqXHR} jqXHR
		 * @param {Object} ajaxOptions
		 */
		function trackAjax( event, jqXHR, ajaxOptions ) {
			ajaxRequests.push( { xhr: jqXHR, options: ajaxOptions } );
		}

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

					localEnv.setup.call( this );
				},

				teardown: function () {
					var timers, pending, $activeLen;

					localEnv.teardown.call( this );

					// Stop tracking ajax requests
					$( document ).off( 'ajaxSend', trackAjax );

					// Farewell, mock environment!
					mw.config = liveConfig;
					mw.messages = liveMessages;
					// Restore reference to mw.messages
					mw.jqueryMsg.setParserDefaults( {
						messages: liveMessages
					} );

					// As a convenience feature, automatically restore warnings if they're
					// still suppressed by the end of the test.
					restoreWarnings();

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
						$.fx.stop();

						throw new Error( 'Unfinished animations: ' + timers );
					}

					// Test should use fake XHR, wait for requests, or call abort()
					$activeLen = $.active;
					if ( $activeLen !== undefined && $activeLen !== 0 ) {
						pending = $.grep( ajaxRequests, function ( ajax ) {
							return ajax.xhr.state() === 'pending';
						} );
						if ( pending.length !== $activeLen ) {
							mw.log.warn( 'Pending requests does not match jQuery.active count' );
						}
						// Force requests to stop to give the next test a clean start
						$.each( pending, function ( i, ajax ) {
							mw.log.warn( 'Pending AJAX request #' + i, ajax.options );
							ajax.xhr.abort();
						} );
						ajaxRequests = [];

						throw new Error( 'Pending AJAX requests: ' + pending.length + ' (active: ' + $activeLen + ')' );
					}
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
	QUnit.module( 'test.mediawiki.qunit.testrunner', QUnit.newMwEnvironment( {
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

	QUnit.test( 'Setup', 3, function ( assert ) {
		assert.equal( mw.html.escape( 'foo' ), 'mocked', 'setup() callback was ran.' );
		assert.equal( mw.config.get( 'testVar' ), 'foo', 'config object applied' );
		assert.equal( mw.messages.get( 'testMsg' ), 'Foo.', 'messages object applied' );

		mw.config.set( 'testVar', 'bar' );
		mw.messages.set( 'testMsg', 'Bar.' );
	} );

	QUnit.test( 'Teardown', 2, function ( assert ) {
		assert.equal( mw.config.get( 'testVar' ), 'foo', 'config object restored and re-applied after test()' );
		assert.equal( mw.messages.get( 'testMsg' ), 'Foo.', 'messages object restored and re-applied after test()' );
	} );

	QUnit.test( 'Loader status', 2, function ( assert ) {
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

	QUnit.module( 'test.mediawiki.qunit.testrunner-after', QUnit.newMwEnvironment() );

	QUnit.test( 'Teardown', 3, function ( assert ) {
		assert.equal( mw.html.escape( '<' ), '&lt;', 'teardown() callback was ran.' );
		assert.equal( mw.config.get( 'testVar' ), null, 'config object restored to live in next module()' );
		assert.equal( mw.messages.get( 'testMsg' ), null, 'messages object restored to live in next module()' );
	} );

}( jQuery, mediaWiki, QUnit ) );
