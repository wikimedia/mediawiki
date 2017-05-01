( function ( mw, $ ) {
	QUnit.module( 'mediawiki (mw.loader)', QUnit.newMwEnvironment( {
		setup: function () {
			mw.loader.store.enabled = false;
		},
		teardown: function () {
			mw.loader.store.enabled = false;
		}
	} ) );

	mw.loader.addSource(
		'testloader',
		QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/load.mock.php' )
	);

	/**
	 * The sync style load test (for @import). This is, in a way, also an open bug for
	 * ResourceLoader ("execute js after styles are loaded"), but browsers don't offer a
	 * way to get a callback from when a stylesheet is loaded (that is, including any
	 * `@import` rules inside). To work around this, we'll have a little time loop to check
	 * if the styles apply.
	 *
	 * Note: This test originally used new Image() and onerror to get a callback
	 * when the url is loaded, but that is fragile since it doesn't monitor the
	 * same request as the css @import, and Safari 4 has issues with
	 * onerror/onload not being fired at all in weird cases like this.
	 */
	function assertStyleAsync( assert, $element, prop, val, fn ) {
		var styleTestStart,
			el = $element.get( 0 ),
			styleTestTimeout = ( QUnit.config.testTimeout || 5000 ) - 200;

		function isCssImportApplied() {
			// Trigger reflow, repaint, redraw, whatever (cross-browser)
			var x = $element.css( 'height' );
			x = el.innerHTML;
			el.className = el.className;
			x = document.documentElement.clientHeight;

			return $element.css( prop ) === val;
		}

		function styleTestLoop() {
			var styleTestSince = new Date().getTime() - styleTestStart;
			// If it is passing or if we timed out, run the real test and stop the loop
			if ( isCssImportApplied() || styleTestSince > styleTestTimeout ) {
				assert.equal( $element.css( prop ), val,
					'style "' + prop + ': ' + val + '" from url is applied (after ' + styleTestSince + 'ms)'
				);

				if ( fn ) {
					fn();
				}

				return;
			}
			// Otherwise, keep polling
			setTimeout( styleTestLoop );
		}

		// Start the loop
		styleTestStart = new Date().getTime();
		styleTestLoop();
	}

	function urlStyleTest( selector, prop, val ) {
		return QUnit.fixurl(
			mw.config.get( 'wgScriptPath' ) +
				'/tests/qunit/data/styleTest.css.php?' +
				$.param( {
					selector: selector,
					prop: prop,
					val: val
				} )
		);
	}

	QUnit.test( 'Basic', 2, function ( assert ) {
		var isAwesomeDone;

		mw.loader.testCallback = function () {
			assert.strictEqual( isAwesomeDone, undefined, 'Implementing module is.awesome: isAwesomeDone should still be undefined' );
			isAwesomeDone = true;
		};

		mw.loader.implement( 'test.callback', [ QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/callMwLoaderTestCallback.js' ) ] );

		return mw.loader.using( 'test.callback', function () {
			assert.strictEqual( isAwesomeDone, true, 'test.callback module should\'ve caused isAwesomeDone to be true' );
			delete mw.loader.testCallback;

		}, function () {
			assert.ok( false, 'Error callback fired while loader.using "test.callback" module' );
		} );
	} );

	QUnit.test( 'Object method as module name', 2, function ( assert ) {
		var isAwesomeDone;

		mw.loader.testCallback = function () {
			assert.strictEqual( isAwesomeDone, undefined, 'Implementing module hasOwnProperty: isAwesomeDone should still be undefined' );
			isAwesomeDone = true;
		};

		mw.loader.implement( 'hasOwnProperty', [ QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/callMwLoaderTestCallback.js' ) ], {}, {} );

		return mw.loader.using( 'hasOwnProperty', function () {
			assert.strictEqual( isAwesomeDone, true, 'hasOwnProperty module should\'ve caused isAwesomeDone to be true' );
			delete mw.loader.testCallback;

		}, function () {
			assert.ok( false, 'Error callback fired while loader.using "hasOwnProperty" module' );
		} );
	} );

	QUnit.test( '.using( .. ) Promise', 2, function ( assert ) {
		var isAwesomeDone;

		mw.loader.testCallback = function () {
			assert.strictEqual( isAwesomeDone, undefined, 'Implementing module is.awesome: isAwesomeDone should still be undefined' );
			isAwesomeDone = true;
		};

		mw.loader.implement( 'test.promise', [ QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/callMwLoaderTestCallback.js' ) ] );

		return mw.loader.using( 'test.promise' )
		.done( function () {
			assert.strictEqual( isAwesomeDone, true, 'test.promise module should\'ve caused isAwesomeDone to be true' );
			delete mw.loader.testCallback;

		} )
		.fail( function () {
			assert.ok( false, 'Error callback fired while loader.using "test.promise" module' );
		} );
	} );

	QUnit.test( '.implement( styles={ "css": [text, ..] } )', 2, function ( assert ) {
		var $element = $( '<div class="mw-test-implement-a"></div>' ).appendTo( '#qunit-fixture' );

		assert.notEqual(
			$element.css( 'float' ),
			'right',
			'style is clear'
		);

		mw.loader.implement(
			'test.implement.a',
			function () {
				assert.equal(
					$element.css( 'float' ),
					'right',
					'style is applied'
				);
			},
			{
				all: '.mw-test-implement-a { float: right; }'
			}
		);

		return mw.loader.using( 'test.implement.a' );
	} );

	QUnit.test( '.implement( styles={ "url": { <media>: [url, ..] } } )', 7, function ( assert ) {
		var $element1 = $( '<div class="mw-test-implement-b1"></div>' ).appendTo( '#qunit-fixture' ),
			$element2 = $( '<div class="mw-test-implement-b2"></div>' ).appendTo( '#qunit-fixture' ),
			$element3 = $( '<div class="mw-test-implement-b3"></div>' ).appendTo( '#qunit-fixture' ),
			done = assert.async();

		assert.notEqual(
			$element1.css( 'text-align' ),
			'center',
			'style is clear'
		);
		assert.notEqual(
			$element2.css( 'float' ),
			'left',
			'style is clear'
		);
		assert.notEqual(
			$element3.css( 'text-align' ),
			'right',
			'style is clear'
		);

		mw.loader.implement(
			'test.implement.b',
			function () {
				// Note: done() must only be called when the entire test is
				// complete. So, make sure that we don't start until *both*
				// assertStyleAsync calls have completed.
				var pending = 2;
				assertStyleAsync( assert, $element2, 'float', 'left', function () {
					assert.notEqual( $element1.css( 'text-align' ), 'center', 'print style is not applied' );

					pending--;
					if ( pending === 0 ) {
						done();
					}
				} );
				assertStyleAsync( assert, $element3, 'float', 'right', function () {
					assert.notEqual( $element1.css( 'text-align' ), 'center', 'print style is not applied' );

					pending--;
					if ( pending === 0 ) {
						done();
					}
				} );
			},
			{
				url: {
					print: [ urlStyleTest( '.mw-test-implement-b1', 'text-align', 'center' ) ],
					screen: [
						// bug 40834: Make sure it actually works with more than 1 stylesheet reference
						urlStyleTest( '.mw-test-implement-b2', 'float', 'left' ),
						urlStyleTest( '.mw-test-implement-b3', 'float', 'right' )
					]
				}
			}
		);

		mw.loader.load( 'test.implement.b' );
	} );

	// Backwards compatibility
	QUnit.test( '.implement( styles={ <media>: text } ) (back-compat)', 2, function ( assert ) {
		var $element = $( '<div class="mw-test-implement-c"></div>' ).appendTo( '#qunit-fixture' );

		assert.notEqual(
			$element.css( 'float' ),
			'right',
			'style is clear'
		);

		mw.loader.implement(
			'test.implement.c',
			function () {
				assert.equal(
					$element.css( 'float' ),
					'right',
					'style is applied'
				);
			},
			{
				all: '.mw-test-implement-c { float: right; }'
			}
		);

		return mw.loader.using( 'test.implement.c' );
	} );

	// Backwards compatibility
	QUnit.test( '.implement( styles={ <media>: [url, ..] } ) (back-compat)', 4, function ( assert ) {
		var $element = $( '<div class="mw-test-implement-d"></div>' ).appendTo( '#qunit-fixture' ),
			$element2 = $( '<div class="mw-test-implement-d2"></div>' ).appendTo( '#qunit-fixture' ),
			done = assert.async();

		assert.notEqual(
			$element.css( 'float' ),
			'right',
			'style is clear'
		);
		assert.notEqual(
			$element2.css( 'text-align' ),
			'center',
			'style is clear'
		);

		mw.loader.implement(
			'test.implement.d',
			function () {
				assertStyleAsync( assert, $element, 'float', 'right', function () {
					assert.notEqual( $element2.css( 'text-align' ), 'center', 'print style is not applied (bug 40500)' );
					done();
				} );
			},
			{
				all: [ urlStyleTest( '.mw-test-implement-d', 'float', 'right' ) ],
				print: [ urlStyleTest( '.mw-test-implement-d2', 'text-align', 'center' ) ]
			}
		);

		mw.loader.load( 'test.implement.d' );
	} );

	// @import (bug 31676)
	QUnit.test( '.implement( styles has @import )', 7, function ( assert ) {
		var isJsExecuted, $element,
			done = assert.async();

		mw.loader.implement(
			'test.implement.import',
			function () {
				assert.strictEqual( isJsExecuted, undefined, 'script not executed multiple times' );
				isJsExecuted = true;

				assert.equal( mw.loader.getState( 'test.implement.import' ), 'executing', 'module state during implement() script execution' );

				$element = $( '<div class="mw-test-implement-import">Foo bar</div>' ).appendTo( '#qunit-fixture' );

				assert.equal( mw.msg( 'test-foobar' ), 'Hello Foobar, $1!', 'messages load before script execution' );

				assertStyleAsync( assert, $element, 'float', 'right', function () {
					assert.equal( $element.css( 'text-align' ), 'center',
						'CSS styles after the @import rule are working'
					);

					done();
				} );
			},
			{
				css: [
					'@import url(\''
						+ urlStyleTest( '.mw-test-implement-import', 'float', 'right' )
						+ '\');\n'
						+ '.mw-test-implement-import { text-align: center; }'
				]
			},
			{
				'test-foobar': 'Hello Foobar, $1!'
			}
		);

		mw.loader.using( 'test.implement.import' ).always( function () {
			assert.strictEqual( isJsExecuted, true, 'script executed' );
			assert.equal( mw.loader.getState( 'test.implement.import' ), 'ready', 'module state after script execution' );
		} );
	} );

	QUnit.test( '.implement( dependency with styles )', 4, function ( assert ) {
		var $element = $( '<div class="mw-test-implement-e"></div>' ).appendTo( '#qunit-fixture' ),
			$element2 = $( '<div class="mw-test-implement-e2"></div>' ).appendTo( '#qunit-fixture' );

		assert.notEqual(
			$element.css( 'float' ),
			'right',
			'style is clear'
		);
		assert.notEqual(
			$element2.css( 'float' ),
			'left',
			'style is clear'
		);

		mw.loader.register( [
			[ 'test.implement.e', '0', [ 'test.implement.e2' ] ],
			[ 'test.implement.e2', '0' ]
		] );

		mw.loader.implement(
			'test.implement.e',
			function () {
				assert.equal(
					$element.css( 'float' ),
					'right',
					'Depending module\'s style is applied'
				);
			},
			{
				all: '.mw-test-implement-e { float: right; }'
			}
		);

		mw.loader.implement(
			'test.implement.e2',
			function () {
				assert.equal(
					$element2.css( 'float' ),
					'left',
					'Dependency\'s style is applied'
				);
			},
			{
				all: '.mw-test-implement-e2 { float: left; }'
			}
		);

		return mw.loader.using( 'test.implement.e' );
	} );

	QUnit.test( '.implement( only scripts )', 1, function ( assert ) {
		mw.loader.implement( 'test.onlyscripts', function () {} );
		assert.strictEqual( mw.loader.getState( 'test.onlyscripts' ), 'ready' );
	} );

	QUnit.test( '.implement( only messages )', 2, function ( assert ) {
		assert.assertFalse( mw.messages.exists( 'bug_29107' ), 'Verify that the test message doesn\'t exist yet' );

		// jscs: disable requireCamelCaseOrUpperCaseIdentifiers
		mw.loader.implement( 'test.implement.msgs', [], {}, { bug_29107: 'loaded' } );
		// jscs: enable requireCamelCaseOrUpperCaseIdentifiers

		return mw.loader.using( 'test.implement.msgs', function () {
			assert.ok( mw.messages.exists( 'bug_29107' ), 'Bug 29107: messages-only module should implement ok' );
		}, function () {
			assert.ok( false, 'Error callback fired while implementing "test.implement.msgs" module' );
		} );
	} );

	QUnit.test( '.implement( empty )', 1, function ( assert ) {
		mw.loader.implement( 'test.empty' );
		assert.strictEqual( mw.loader.getState( 'test.empty' ), 'ready' );
	} );

	QUnit.test( 'Broken indirect dependency', 4, function ( assert ) {
		// don't emit an error event
		this.sandbox.stub( mw, 'track' );

		mw.loader.register( [
			[ 'test.module1', '0' ],
			[ 'test.module2', '0', [ 'test.module1' ] ],
			[ 'test.module3', '0', [ 'test.module2' ] ]
		] );
		mw.loader.implement( 'test.module1', function () {
			throw new Error( 'expected' );
		}, {}, {} );
		assert.strictEqual( mw.loader.getState( 'test.module1' ), 'error', 'Expected "error" state for test.module1' );
		assert.strictEqual( mw.loader.getState( 'test.module2' ), 'error', 'Expected "error" state for test.module2' );
		assert.strictEqual( mw.loader.getState( 'test.module3' ), 'error', 'Expected "error" state for test.module3' );

		assert.strictEqual( mw.track.callCount, 1 );
	} );

	QUnit.test( 'Circular dependency', 1, function ( assert ) {
		mw.loader.register( [
			[ 'test.circle1', '0', [ 'test.circle2' ] ],
			[ 'test.circle2', '0', [ 'test.circle3' ] ],
			[ 'test.circle3', '0', [ 'test.circle1' ] ]
		] );
		assert.throws( function () {
			mw.loader.using( 'test.circle3' );
		}, /Circular/, 'Detect circular dependency' );
	} );

	QUnit.test( 'Out-of-order implementation', 9, function ( assert ) {
		mw.loader.register( [
			[ 'test.module4', '0' ],
			[ 'test.module5', '0', [ 'test.module4' ] ],
			[ 'test.module6', '0', [ 'test.module5' ] ]
		] );
		mw.loader.implement( 'test.module4', function () {} );
		assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
		assert.strictEqual( mw.loader.getState( 'test.module5' ), 'registered', 'Expected "registered" state for test.module5' );
		assert.strictEqual( mw.loader.getState( 'test.module6' ), 'registered', 'Expected "registered" state for test.module6' );
		mw.loader.implement( 'test.module6', function () {} );
		assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
		assert.strictEqual( mw.loader.getState( 'test.module5' ), 'registered', 'Expected "registered" state for test.module5' );
		assert.strictEqual( mw.loader.getState( 'test.module6' ), 'loaded', 'Expected "loaded" state for test.module6' );
		mw.loader.implement( 'test.module5', function () {} );
		assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
		assert.strictEqual( mw.loader.getState( 'test.module5' ), 'ready', 'Expected "ready" state for test.module5' );
		assert.strictEqual( mw.loader.getState( 'test.module6' ), 'ready', 'Expected "ready" state for test.module6' );
	} );

	QUnit.test( 'Missing dependency', 13, function ( assert ) {
		mw.loader.register( [
			[ 'test.module7', '0' ],
			[ 'test.module8', '0', [ 'test.module7' ] ],
			[ 'test.module9', '0', [ 'test.module8' ] ]
		] );
		mw.loader.implement( 'test.module8', function () {} );
		assert.strictEqual( mw.loader.getState( 'test.module7' ), 'registered', 'Expected "registered" state for test.module7' );
		assert.strictEqual( mw.loader.getState( 'test.module8' ), 'loaded', 'Expected "loaded" state for test.module8' );
		assert.strictEqual( mw.loader.getState( 'test.module9' ), 'registered', 'Expected "registered" state for test.module9' );
		mw.loader.state( 'test.module7', 'missing' );
		assert.strictEqual( mw.loader.getState( 'test.module7' ), 'missing', 'Expected "missing" state for test.module7' );
		assert.strictEqual( mw.loader.getState( 'test.module8' ), 'error', 'Expected "error" state for test.module8' );
		assert.strictEqual( mw.loader.getState( 'test.module9' ), 'error', 'Expected "error" state for test.module9' );
		mw.loader.implement( 'test.module9', function () {} );
		assert.strictEqual( mw.loader.getState( 'test.module7' ), 'missing', 'Expected "missing" state for test.module7' );
		assert.strictEqual( mw.loader.getState( 'test.module8' ), 'error', 'Expected "error" state for test.module8' );
		assert.strictEqual( mw.loader.getState( 'test.module9' ), 'error', 'Expected "error" state for test.module9' );
		mw.loader.using(
			[ 'test.module7' ],
			function () {
				assert.ok( false, 'Success fired despite missing dependency' );
				assert.ok( true, 'QUnit expected() count dummy' );
			},
			function ( e, dependencies ) {
				assert.strictEqual( $.isArray( dependencies ), true, 'Expected array of dependencies' );
				assert.deepEqual( dependencies, [ 'test.module7' ], 'Error callback called with module test.module7' );
			}
		);
		mw.loader.using(
			[ 'test.module9' ],
			function () {
				assert.ok( false, 'Success fired despite missing dependency' );
				assert.ok( true, 'QUnit expected() count dummy' );
			},
			function ( e, dependencies ) {
				assert.strictEqual( $.isArray( dependencies ), true, 'Expected array of dependencies' );
				dependencies.sort();
				assert.deepEqual(
					dependencies,
					[ 'test.module7', 'test.module8', 'test.module9' ],
					'Error callback called with all three modules as dependencies'
				);
			}
		);
	} );

	QUnit.test( 'Dependency handling', 5, function ( assert ) {
		var done = assert.async();
		mw.loader.register( [
			// [module, version, dependencies, group, source]
			[ 'testMissing', '1', [], null, 'testloader' ],
			[ 'testUsesMissing', '1', [ 'testMissing' ], null, 'testloader' ],
			[ 'testUsesNestedMissing', '1', [ 'testUsesMissing' ], null, 'testloader' ]
		] );

		function verifyModuleStates() {
			assert.equal( mw.loader.getState( 'testMissing' ), 'missing', 'Module not known to server must have state "missing"' );
			assert.equal( mw.loader.getState( 'testUsesMissing' ), 'error', 'Module with missing dependency must have state "error"' );
			assert.equal( mw.loader.getState( 'testUsesNestedMissing' ), 'error', 'Module with indirect missing dependency must have state "error"' );
		}

		mw.loader.using( [ 'testUsesNestedMissing' ],
			function () {
				assert.ok( false, 'Error handler should be invoked.' );
				assert.ok( true ); // Dummy to reach QUnit expect()

				verifyModuleStates();

				done();
			},
			function ( e, badmodules ) {
				assert.ok( true, 'Error handler should be invoked.' );
				// As soon as server spits out state('testMissing', 'missing');
				// it will bubble up and trigger the error callback.
				// Therefor the badmodules array is not testUsesMissing or testUsesNestedMissing.
				assert.deepEqual( badmodules, [ 'testMissing' ], 'Bad modules as expected.' );

				verifyModuleStates();

				done();
			}
		);
	} );

	QUnit.test( 'Skip-function handling', 5, function ( assert ) {
		mw.loader.register( [
			// [module, version, dependencies, group, source, skip]
			[ 'testSkipped', '1', [], null, 'testloader', 'return true;' ],
			[ 'testNotSkipped', '1', [], null, 'testloader', 'return false;' ],
			[ 'testUsesSkippable', '1', [ 'testSkipped', 'testNotSkipped' ], null, 'testloader' ]
		] );

		function verifyModuleStates() {
			assert.equal( mw.loader.getState( 'testSkipped' ), 'ready', 'Module is ready when skipped' );
			assert.equal( mw.loader.getState( 'testNotSkipped' ), 'ready', 'Module is ready when not skipped but loaded' );
			assert.equal( mw.loader.getState( 'testUsesSkippable' ), 'ready', 'Module is ready when skippable dependencies are ready' );
		}

		return mw.loader.using( [ 'testUsesSkippable' ],
			function () {
				assert.ok( true, 'Success handler should be invoked.' );
				assert.ok( true ); // Dummy to match error handler and reach QUnit expect()

				verifyModuleStates();
			},
			function ( e, badmodules ) {
				assert.ok( false, 'Error handler should not be invoked.' );
				assert.deepEqual( badmodules, [], 'Bad modules as expected.' );

				verifyModuleStates();
			}
		);
	} );

	QUnit.asyncTest( '.load( "//protocol-relative" ) - T32825', 2, function ( assert ) {
		// This bug was actually already fixed in 1.18 and later when discovered in 1.17.
		// Test is for regressions!

		// Forge a URL to the test callback script
		var target = QUnit.fixurl(
			mw.config.get( 'wgServer' ) + mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/qunitOkCall.js'
		);

		// Confirm that mw.loader.load() works with protocol-relative URLs
		target = target.replace( /https?:/, '' );

		assert.equal( target.slice( 0, 2 ), '//',
			'URL must be relative to test relative URLs!'
		);

		// Async!
		// The target calls QUnit.start
		mw.loader.load( target );
	} );

	QUnit.asyncTest( '.load( "/absolute-path" )', 2, function ( assert ) {
		// Forge a URL to the test callback script
		var target = QUnit.fixurl(
			mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/qunitOkCall.js'
		);

		// Confirm that mw.loader.load() works with absolute-paths (relative to current hostname)
		assert.equal( target.slice( 0, 1 ), '/', 'URL is relative to document root' );

		// Async!
		// The target calls QUnit.start
		mw.loader.load( target );
	} );

	QUnit.test( 'Executing race - T112232', 2, function ( assert ) {
		var done = false;

		// The red herring schedules its CSS buffer first. In T112232, a bug in the
		// state machine would cause the job for testRaceLoadMe to run with an earlier job.
		mw.loader.implement(
			'testRaceRedHerring',
			function () {},
			{ css: [ '.mw-testRaceRedHerring {}' ] }
		);
		mw.loader.implement(
			'testRaceLoadMe',
			function () {
				done = true;
			},
			{ css: [ '.mw-testRaceLoadMe { float: left; }' ] }
		);

		mw.loader.load( [ 'testRaceRedHerring', 'testRaceLoadMe' ] );
		return mw.loader.using( 'testRaceLoadMe', function () {
			assert.strictEqual( done, true, 'script ran' );
			assert.strictEqual( mw.loader.getState( 'testRaceLoadMe' ), 'ready', 'state' );
		} );
	} );

	QUnit.test( 'Stale response caching - T117587', function ( assert ) {
		var count = 0;
		mw.loader.store.enabled = true;
		mw.loader.register( 'test.stale', 'v2' );
		assert.strictEqual( mw.loader.store.get( 'test.stale' ), false, 'Not in store' );

		mw.loader.implement( 'test.stale@v1', function () {
			count++;
		} );

		return mw.loader.using( 'test.stale' )
			.then( function () {
				assert.strictEqual( count, 1 );
				// After implementing, registry contains version as implemented by the response.
				assert.strictEqual( mw.loader.getVersion( 'test.stale' ), 'v1', 'Override version' );
				assert.strictEqual( mw.loader.getState( 'test.stale' ), 'ready' );
				assert.ok( mw.loader.store.get( 'test.stale' ), 'In store' );
			} )
			.then( function () {
				// Reset run time, but keep mw.loader.store
				mw.loader.moduleRegistry[ 'test.stale' ].script = undefined;
				mw.loader.moduleRegistry[ 'test.stale' ].state = 'registered';
				mw.loader.moduleRegistry[ 'test.stale' ].version = 'v2';

				// Module was stored correctly as v1
				// On future navigations, it will be ignored until evicted
				assert.strictEqual( mw.loader.store.get( 'test.stale' ), false, 'Not in store' );
			} );
	} );

	QUnit.test( 'Stale response caching - backcompat', function ( assert ) {
		var count = 0;
		mw.loader.store.enabled = true;
		mw.loader.register( 'test.stalebc', 'v2' );
		assert.strictEqual( mw.loader.store.get( 'test.stalebc' ), false, 'Not in store' );

		mw.loader.implement( 'test.stalebc', function () {
			count++;
		} );

		return mw.loader.using( 'test.stalebc' )
			.then( function () {
				assert.strictEqual( count, 1 );
				assert.strictEqual( mw.loader.getState( 'test.stalebc' ), 'ready' );
				assert.ok( mw.loader.store.get( 'test.stalebc' ), 'In store' );
			} )
			.then( function () {
				// Reset run time, but keep mw.loader.store
				mw.loader.moduleRegistry[ 'test.stalebc' ].script = undefined;
				mw.loader.moduleRegistry[ 'test.stalebc' ].state = 'registered';
				mw.loader.moduleRegistry[ 'test.stalebc' ].version = 'v2';

				// Legacy behaviour is storing under the expected version,
				// which woudl lead to whitewashing and stale values (T117587).
				assert.ok( mw.loader.store.get( 'test.stalebc' ), 'In store' );
			} );
	} );

	QUnit.test( 'require()', 6, function ( assert ) {
		mw.loader.register( [
			[ 'test.require1', '0' ],
			[ 'test.require2', '0' ],
			[ 'test.require3', '0' ],
			[ 'test.require4', '0', [ 'test.require3' ] ]
		] );
		mw.loader.implement( 'test.require1', function () {} );
		mw.loader.implement( 'test.require2', function ( $, jQuery, require, module ) {
			module.exports = 1;
		} );
		mw.loader.implement( 'test.require3', function ( $, jQuery, require, module ) {
			module.exports = function () {
				return 'hello world';
			};
		} );
		mw.loader.implement( 'test.require4', function ( $, jQuery, require, module ) {
			var other = require( 'test.require3' );
			module.exports = {
				pizza: function () {
					return other();
				}
			};
		} );
		return mw.loader.using( [ 'test.require1', 'test.require2', 'test.require3', 'test.require4' ] )
		.then( function ( require ) {
			var module1, module2, module3, module4;

			module1 = require( 'test.require1' );
			module2 = require( 'test.require2' );
			module3 = require( 'test.require3' );
			module4 = require( 'test.require4' );

			assert.strictEqual( typeof module1, 'object', 'export of module with no export' );
			assert.strictEqual( module2, 1, 'export a number' );
			assert.strictEqual( module3(), 'hello world', 'export a function' );
			assert.strictEqual( typeof module4.pizza, 'function', 'export an object' );
			assert.strictEqual( module4.pizza(), 'hello world', 'module can require other modules' );

			assert.throws( function () {
				require( '_badmodule' );
			}, /is not loaded/, 'Requesting non-existent modules throws error.' );
		} );
	} );

	QUnit.test( 'require() in debug mode', function ( assert ) {
		var path = mw.config.get( 'wgScriptPath' );
		mw.loader.register( [
			[ 'test.require.define', '0' ],
			[ 'test.require.callback', '0', [ 'test.require.define' ] ]
		] );
		mw.loader.implement( 'test.require.callback', [ QUnit.fixurl( path + '/tests/qunit/data/requireCallMwLoaderTestCallback.js' ) ] );
		mw.loader.implement( 'test.require.define', [ QUnit.fixurl( path + '/tests/qunit/data/defineCallMwLoaderTestCallback.js' ) ] );

		return mw.loader.using( 'test.require.callback' ).then( function ( require ) {
			var cb = require( 'test.require.callback' );
			assert.strictEqual( cb.immediate, 'Defined.', 'module.exports and require work in debug mode' );
			// Must use try-catch because cb.later() will throw if require is undefined,
			// which doesn't work well inside Deferred.then() when using jQuery 1.x with QUnit
			try {
				assert.strictEqual( cb.later(), 'Defined.', 'require works asynchrously in debug mode' );
			} catch ( e ) {
				assert.equal( null, String( e ), 'require works asynchrously in debug mode' );
			}
		}, function () {
			assert.ok( false, 'Error callback fired while loader.using "test.require.callback" module' );
		} );
	} );

}( mediaWiki, jQuery ) );
