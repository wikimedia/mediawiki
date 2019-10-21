( function () {
	QUnit.module( 'mediawiki.loader', QUnit.newMwEnvironment( {
		setup: function ( assert ) {
			// Expose for load.mock.php
			mw.loader.testFail = function ( reason ) {
				assert.ok( false, reason );
			};

			this.useStubClock = function () {
				this.clock = this.sandbox.useFakeTimers();
				this.tick = function ( forward ) {
					return this.clock.tick( forward || 1 );
				};
				this.sandbox.stub( mw, 'requestIdleCallback', mw.requestIdleCallbackInternal );
			};
		},
		teardown: function () {
			mw.loader.maxQueryLength = 2000;
			// Teardown for StringSet shim test
			if ( this.nativeSet ) {
				window.Set = this.nativeSet;
				mw.redefineFallbacksForTest();
			}
			if ( this.resetStoreKey ) {
				localStorage.removeItem( mw.loader.store.key );
			}
			// Remove any remaining temporary statics
			// exposed for cross-file mocks.
			delete mw.loader.testCallback;
			delete mw.loader.testFail;
			delete mw.getScriptExampleScriptLoaded;
		}
	} ) );

	mw.loader.addSource( {
		testloader:
			QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/load.mock.php' )
	} );

	/**
	 * The sync style load test, for @import. This is, in a way, also an open bug for
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
			$element.css( 'height' );
			// eslint-disable-next-line no-unused-expressions
			el.innerHTML;
			// eslint-disable-next-line no-self-assign
			el.className = el.className;
			// eslint-disable-next-line no-unused-expressions
			document.documentElement.clientHeight;

			return $element.css( prop ) === val;
		}

		function styleTestLoop() {
			var styleTestSince = new Date().getTime() - styleTestStart;
			// If it is passing or if we timed out, run the real test and stop the loop
			if ( isCssImportApplied() || styleTestSince > styleTestTimeout ) {
				assert.strictEqual( $element.css( prop ), val,
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

	QUnit.test( '.using( .., Function callback ) Promise', function ( assert ) {
		var script = 0, callback = 0;
		mw.loader.testCallback = function () {
			script++;
		};
		mw.loader.implement( 'test.promise', [ QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/mwLoaderTestCallback.js' ) ] );

		return mw.loader.using( 'test.promise', function () {
			callback++;
		} ).then( function () {
			assert.strictEqual( script, 1, 'module script ran' );
			assert.strictEqual( callback, 1, 'using() callback ran' );
		} );
	} );

	QUnit.test( 'Prototype method as module name', function ( assert ) {
		var call = 0;
		mw.loader.testCallback = function () {
			call++;
		};
		mw.loader.implement( 'hasOwnProperty', [ QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/mwLoaderTestCallback.js' ) ], {}, {} );

		return mw.loader.using( 'hasOwnProperty', function () {
			assert.strictEqual( call, 1, 'module script ran' );
		} );
	} );

	// Covers mw.loader#sortDependencies (with native Set if available)
	QUnit.test( '.using() - Error: Circular dependency [StringSet default]', function ( assert ) {
		var done = assert.async();

		mw.loader.register( [
			[ 'test.set.circleA', '0', [ 'test.set.circleB' ] ],
			[ 'test.set.circleB', '0', [ 'test.set.circleC' ] ],
			[ 'test.set.circleC', '0', [ 'test.set.circleA' ] ]
		] );
		mw.loader.using( 'test.set.circleC' ).then(
			function done() {
				assert.ok( false, 'Unexpected resolution, expected error.' );
			},
			function fail( e ) {
				assert.ok( /Circular/.test( String( e ) ), 'Detect circular dependency' );
			}
		)
			.always( done );
	} );

	// @covers mw.loader#sortDependencies (with fallback shim)
	QUnit.test( '.using() - Error: Circular dependency [StringSet shim]', function ( assert ) {
		var done = assert.async();

		if ( !window.Set ) {
			assert.expect( 0 );
			done();
			return;
		}

		this.nativeSet = window.Set;
		window.Set = undefined;
		mw.redefineFallbacksForTest();

		mw.loader.register( [
			[ 'test.shim.circleA', '0', [ 'test.shim.circleB' ] ],
			[ 'test.shim.circleB', '0', [ 'test.shim.circleC' ] ],
			[ 'test.shim.circleC', '0', [ 'test.shim.circleA' ] ]
		] );
		mw.loader.using( 'test.shim.circleC' ).then(
			function done() {
				assert.ok( false, 'Unexpected resolution, expected error.' );
			},
			function fail( e ) {
				assert.ok( /Circular/.test( String( e ) ), 'Detect circular dependency' );
			}
		)
			.always( done );
	} );

	QUnit.test( '.load() - Error: Circular dependency', function ( assert ) {
		var capture = [];
		mw.loader.register( [
			[ 'test.load.circleA', '0', [ 'test.load.circleB' ] ],
			[ 'test.load.circleB', '0', [ 'test.load.circleC' ] ],
			[ 'test.load.circleC', '0', [ 'test.load.circleA' ] ]
		] );
		this.sandbox.stub( mw, 'trackError', function ( topic, data ) {
			capture.push( {
				topic: topic,
				error: data.exception && data.exception.message,
				source: data.source
			} );
		} );

		mw.loader.load( 'test.load.circleC' );
		assert.deepEqual(
			[ {
				topic: 'resourceloader.exception',
				error: 'Circular reference detected: test.load.circleB -> test.load.circleC',
				source: 'resolve'
			} ],
			capture,
			'Detect circular dependency'
		);
	} );

	QUnit.test( '.load() - Error: Circular dependency (direct)', function ( assert ) {
		var capture = [];
		mw.loader.register( [
			[ 'test.load.circleDirect', '0', [ 'test.load.circleDirect' ] ]
		] );
		this.sandbox.stub( mw, 'trackError', function ( topic, data ) {
			capture.push( {
				topic: topic,
				error: data.exception && data.exception.message,
				source: data.source
			} );
		} );

		mw.loader.load( 'test.load.circleDirect' );
		assert.deepEqual(
			[ {
				topic: 'resourceloader.exception',
				error: 'Circular reference detected: test.load.circleDirect -> test.load.circleDirect',
				source: 'resolve'
			} ],
			capture,
			'Detect a direct self-dependency'
		);
	} );

	QUnit.test( '.using() - Error: Unregistered', function ( assert ) {
		var done = assert.async();

		mw.loader.using( 'test.using.unreg' ).then(
			function done() {
				assert.ok( false, 'Unexpected resolution, expected error.' );
			},
			function fail( e ) {
				assert.ok( /Unknown/.test( String( e ) ), 'Detect unknown dependency' );
			}
		).always( done );
	} );

	QUnit.test( '.load() - Error: Unregistered', function ( assert ) {
		var capture = [];
		this.sandbox.stub( mw.log, 'warn', function ( str ) {
			capture.push( str );
		} );

		mw.loader.load( 'test.load.unreg' );
		assert.deepEqual( capture, [ 'Skipped unresolvable module test.load.unreg' ] );
	} );

	// Regression test for T36853
	QUnit.test( '.load() - Error: Missing dependency', function ( assert ) {
		var capture = [];
		this.sandbox.stub( mw, 'trackError', function ( topic, data ) {
			capture.push( {
				topic: topic,
				error: data.exception && data.exception.message,
				source: data.source
			} );
		} );

		mw.loader.register( [
			[ 'test.load.missingdep1', '0', [ 'test.load.missingdep2' ] ],
			[ 'test.load.missingdep', '0', [ 'test.load.missingdep1' ] ]
		] );
		mw.loader.load( 'test.load.missingdep' );
		assert.deepEqual(
			[ {
				topic: 'resourceloader.exception',
				error: 'Unknown module: test.load.missingdep2',
				source: 'resolve'
			} ],
			capture
		);
	} );

	QUnit.test( '.implement( styles={ "css": [text, ..] } )', function ( assert ) {
		var $element = $( '<div class="mw-test-implement-a"></div>' ).appendTo( '#qunit-fixture' );

		assert.notEqual(
			$element.css( 'float' ),
			'right',
			'style is clear'
		);

		mw.loader.implement(
			'test.implement.a',
			function () {
				assert.strictEqual(
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

	QUnit.test( '.implement( styles={ "url": { <media>: [url, ..] } } )', function ( assert ) {
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
						// T42834: Make sure it actually works with more than 1 stylesheet reference
						urlStyleTest( '.mw-test-implement-b2', 'float', 'left' ),
						urlStyleTest( '.mw-test-implement-b3', 'float', 'right' )
					]
				}
			}
		);

		mw.loader.load( 'test.implement.b' );
	} );

	// Backwards compatibility
	QUnit.test( '.implement( styles={ <media>: text } ) (back-compat)', function ( assert ) {
		var $element = $( '<div class="mw-test-implement-c"></div>' ).appendTo( '#qunit-fixture' );

		assert.notEqual(
			$element.css( 'float' ),
			'right',
			'style is clear'
		);

		mw.loader.implement(
			'test.implement.c',
			function () {
				assert.strictEqual(
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
	QUnit.test( '.implement( styles={ <media>: [url, ..] } ) (back-compat)', function ( assert ) {
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
					assert.notEqual( $element2.css( 'text-align' ), 'center', 'print style is not applied (T42500)' );
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

	QUnit.test( '.implement( messages before script )', function ( assert ) {
		mw.loader.implement(
			'test.implement.order',
			function () {
				assert.strictEqual( mw.loader.getState( 'test.implement.order' ), 'executing', 'state during script execution' );
				assert.strictEqual( mw.msg( 'test-foobar' ), 'Hello Foobar, $1!', 'messages load before script execution' );
			},
			{},
			{
				'test-foobar': 'Hello Foobar, $1!'
			}
		);

		return mw.loader.using( 'test.implement.order' ).then( function () {
			assert.strictEqual( mw.loader.getState( 'test.implement.order' ), 'ready', 'final success state' );
		} );
	} );

	// @import (T33676)
	QUnit.test( '.implement( styles with @import )', function ( assert ) {
		var $element,
			done = assert.async();

		mw.loader.implement(
			'test.implement.import',
			function () {
				$element = $( '<div class="mw-test-implement-import">Foo bar</div>' ).appendTo( '#qunit-fixture' );

				assertStyleAsync( assert, $element, 'float', 'right', function () {
					assert.strictEqual( $element.css( 'text-align' ), 'center',
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
			}
		);

		return mw.loader.using( 'test.implement.import' );
	} );

	QUnit.test( '.implement( dependency with styles )', function ( assert ) {
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
				assert.strictEqual(
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
				assert.strictEqual(
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

	QUnit.test( '.implement( only scripts )', function ( assert ) {
		mw.loader.implement( 'test.onlyscripts', function () {} );
		return mw.loader.using( 'test.onlyscripts', function () {
			assert.strictEqual( mw.loader.getState( 'test.onlyscripts' ), 'ready' );
		} );
	} );

	QUnit.test( '.implement( only messages )', function ( assert ) {
		assert.assertFalse( mw.messages.exists( 'T31107' ), 'Verify that the test message doesn\'t exist yet' );

		mw.loader.implement( 'test.implement.msgs', [], {}, { T31107: 'loaded' } );

		return mw.loader.using( 'test.implement.msgs', function () {
			assert.ok( mw.messages.exists( 'T31107' ), 'T31107: messages-only module should implement ok' );
		} );
	} );

	QUnit.test( '.implement( empty )', function ( assert ) {
		mw.loader.implement( 'test.empty' );
		return mw.loader.using( 'test.empty', function () {
			assert.strictEqual( mw.loader.getState( 'test.empty' ), 'ready' );
		} );
	} );

	QUnit.test( '.implement( package files )', function ( assert ) {
		var done = assert.async(),
			initJsRan = false,
			counter = 41;
		mw.loader.implement(
			'test.implement.packageFiles',
			{
				main: 'resources/src/foo/init.js',
				files: {
					'resources/src/foo/data/hello.json': { hello: 'world' },
					'resources/src/foo/foo.js': function ( require, module ) {
						counter++;
						module.exports = { answer: counter };
					},
					'resources/src/bar/bar.js': function ( require, module ) {
						var core = require( './core.js' );
						module.exports = { data: core.sayHello( 'Alice' ) };
					},
					'resources/src/bar/core.js': function ( require, module ) {
						module.exports = { sayHello: function ( name ) {
							return 'Hello ' + name;
						} };
					},
					'resources/src/foo/init.js': function ( require ) {
						initJsRan = true;
						assert.deepEqual( require( './data/hello.json' ), { hello: 'world' }, 'require() with .json file' );
						assert.deepEqual( require( './foo.js' ), { answer: 42 }, 'require() with .js file in same directory' );
						assert.deepEqual( require( '../bar/bar.js' ), { data: 'Hello Alice' }, 'require() with ../ of a file that uses same-directory require()' );
						assert.deepEqual( require( './foo.js' ), { answer: 42 }, 'require()ing the same script twice only runs it once' );
					}
				}
			},
			{},
			{},
			{}
		);
		mw.loader.using( 'test.implement.packageFiles' ).done( function () {
			assert.ok( initJsRan, 'main JS file is executed' );
			done();
		} );
	} );

	QUnit.test( '.addSource()', function ( assert ) {
		mw.loader.addSource( { testsource1: 'https://1.test/src' } );

		assert.throws( function () {
			mw.loader.addSource( { testsource1: 'https://1.test/src' } );
		}, /already registered/, 'duplicate pair from addSource(Object)' );

		assert.throws( function () {
			mw.loader.addSource( { testsource1: 'https://1.test/src-diff' } );
		}, /already registered/, 'duplicate ID from addSource(Object)' );
	} );

	// @covers mw.loader#batchRequest
	// This is a regression test because in the past we called getCombinedVersion()
	// for all requested modules, before url splitting took place.
	// Discovered as part of T188076, but not directly related.
	QUnit.test( 'Url composition (modules considered for version)', function ( assert ) {
		mw.loader.register( [
			// [module, version, dependencies, group, source]
			[ 'testUrlInc', 'url', [], null, 'testloader' ],
			[ 'testUrlIncDump', 'dump', [], null, 'testloader' ]
		] );

		mw.loader.maxQueryLength = 10;

		return mw.loader.using( [ 'testUrlIncDump', 'testUrlInc' ] ).then( function ( require ) {
			assert.propEqual(
				require( 'testUrlIncDump' ).query,
				{
					modules: 'testUrlIncDump',
					// Expected: Combine hashes only for the module in the specific HTTP request
					//   hash fnv132 => "13e9zzn"
					// Wrong: Combine hashes for all requested modules, before request-splitting
					//   hash fnv132 => "18kz9ca"
					version: '13e9z'
				},
				'Query parameters'
			);

			assert.strictEqual( mw.loader.getState( 'testUrlInc' ), 'ready', 'testUrlInc also loaded' );
		} );
	} );

	// @covers mw.loader#batchRequest
	// @covers mw.loader#buildModulesString
	QUnit.test( 'Url composition (order of modules for version) – T188076', function ( assert ) {
		mw.loader.register( [
			// [module, version, dependencies, group, source]
			[ 'testUrlOrder', 'url', [], null, 'testloader' ],
			[ 'testUrlOrder.a', '1', [], null, 'testloader' ],
			[ 'testUrlOrder.b', '2', [], null, 'testloader' ],
			[ 'testUrlOrderDump', 'dump', [], null, 'testloader' ]
		] );

		return mw.loader.using( [
			'testUrlOrderDump',
			'testUrlOrder.b',
			'testUrlOrder.a',
			'testUrlOrder'
		] ).then( function ( require ) {
			assert.propEqual(
				require( 'testUrlOrderDump' ).query,
				{
					modules: 'testUrlOrder,testUrlOrderDump|testUrlOrder.a,b',
					// Expected: Combined by sorting names after string packing
					//   hash fnv132 = "1knqzan"
					// Wrong: Combined by sorting names before string packing
					//   hash fnv132 => "11eo3in"
					version: '1knqz'
				},
				'Query parameters'
			);
		} );
	} );

	QUnit.test( 'Broken indirect dependency', function ( assert ) {
		this.useStubClock();

		// Don't actually emit an error event
		this.sandbox.stub( mw, 'trackError' );

		mw.loader.register( [
			[ 'test.module1', '0' ],
			[ 'test.module2', '0', [ 'test.module1' ] ],
			[ 'test.module3', '0', [ 'test.module2' ] ]
		] );
		mw.loader.implement( 'test.module1', function () {
			throw new Error( 'expected' );
		}, {}, {} );
		this.tick();

		assert.strictEqual( mw.loader.getState( 'test.module1' ), 'error', 'State of test.module1' );
		assert.strictEqual( mw.loader.getState( 'test.module2' ), 'error', 'State of test.module2' );
		assert.strictEqual( mw.loader.getState( 'test.module3' ), 'error', 'State of test.module3' );

		assert.strictEqual( mw.trackError.callCount, 1 );
	} );

	QUnit.test( 'Out-of-order implementation', function ( assert ) {
		this.useStubClock();

		mw.loader.register( [
			[ 'test.module4', '0' ],
			[ 'test.module5', '0', [ 'test.module4' ] ],
			[ 'test.module6', '0', [ 'test.module5' ] ]
		] );

		mw.loader.implement( 'test.module4', function () {} );
		this.tick();
		assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'State of test.module4' );
		assert.strictEqual( mw.loader.getState( 'test.module5' ), 'registered', 'State of test.module5' );
		assert.strictEqual( mw.loader.getState( 'test.module6' ), 'registered', 'State of test.module6' );

		mw.loader.implement( 'test.module6', function () {} );
		this.tick();
		assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'State of test.module4' );
		assert.strictEqual( mw.loader.getState( 'test.module5' ), 'registered', 'State of test.module5' );
		assert.strictEqual( mw.loader.getState( 'test.module6' ), 'loaded', 'State of test.module6' );

		mw.loader.implement( 'test.module5', function () {} );
		this.tick();
		assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'State of test.module4' );
		assert.strictEqual( mw.loader.getState( 'test.module5' ), 'ready', 'State of test.module5' );
		assert.strictEqual( mw.loader.getState( 'test.module6' ), 'ready', 'State of test.module6' );
	} );

	QUnit.test( 'Missing dependency', function ( assert ) {
		this.useStubClock();

		mw.loader.register( [
			[ 'test.module7', '0' ],
			[ 'test.module8', '0', [ 'test.module7' ] ],
			[ 'test.module9', '0', [ 'test.module8' ] ]
		] );

		mw.loader.implement( 'test.module8', function () {} );
		this.tick();
		assert.strictEqual( mw.loader.getState( 'test.module7' ), 'registered', 'Expected "registered" state for test.module7' );
		assert.strictEqual( mw.loader.getState( 'test.module8' ), 'loaded', 'Expected "loaded" state for test.module8' );
		assert.strictEqual( mw.loader.getState( 'test.module9' ), 'registered', 'Expected "registered" state for test.module9' );

		mw.loader.state( { 'test.module7': 'missing' } );
		this.tick();
		assert.strictEqual( mw.loader.getState( 'test.module7' ), 'missing', 'Expected "missing" state for test.module7' );
		assert.strictEqual( mw.loader.getState( 'test.module8' ), 'error', 'Expected "error" state for test.module8' );
		assert.strictEqual( mw.loader.getState( 'test.module9' ), 'error', 'Expected "error" state for test.module9' );

		mw.loader.implement( 'test.module9', function () {} );
		this.tick();
		assert.strictEqual( mw.loader.getState( 'test.module7' ), 'missing', 'Expected "missing" state for test.module7' );
		assert.strictEqual( mw.loader.getState( 'test.module8' ), 'error', 'Expected "error" state for test.module8' );
		assert.strictEqual( mw.loader.getState( 'test.module9' ), 'error', 'Expected "error" state for test.module9' );

		// Restore clock for QUnit and $.Deferred internals
		this.clock.restore();
		return mw.loader.using( [ 'test.module7' ] ).then(
			function () {
				throw new Error( 'Success fired despite missing dependency' );
			},
			function ( e, dependencies ) {
				assert.strictEqual( Array.isArray( dependencies ), true, 'Expected array of dependencies' );
				assert.deepEqual(
					dependencies,
					[ 'jquery', 'mediawiki.base', 'test.module7' ],
					'Error callback called with module test.module7'
				);
			}
		).then( function () {
			return mw.loader.using( [ 'test.module9' ] );
		} ).then(
			function () {
				throw new Error( 'Success fired despite missing dependency' );
			},
			function ( e, dependencies ) {
				assert.strictEqual( Array.isArray( dependencies ), true, 'Expected array of dependencies' );
				dependencies.sort();
				assert.deepEqual(
					dependencies,
					[ 'jquery', 'mediawiki.base', 'test.module7', 'test.module8', 'test.module9' ],
					'Error callback called with all three modules as dependencies'
				);
			}
		);
	} );

	QUnit.test( 'Dependency handling', function ( assert ) {
		mw.loader.register( [
			// [module, version, dependencies, group, source]
			[ 'testMissing', '1', [], null, 'testloader' ],
			[ 'testUsesMissing', '1', [ 'testMissing' ], null, 'testloader' ],
			[ 'testUsesNestedMissing', '1', [ 'testUsesMissing' ], null, 'testloader' ]
		] );

		function verifyModuleStates() {
			assert.strictEqual( mw.loader.getState( 'testMissing' ), 'missing', 'Module "testMissing" state' );
			assert.strictEqual( mw.loader.getState( 'testUsesMissing' ), 'error', 'Module "testUsesMissing" state' );
			assert.strictEqual( mw.loader.getState( 'testUsesNestedMissing' ), 'error', 'Module "testUsesNestedMissing" state' );
		}

		return mw.loader.using( [ 'testUsesNestedMissing' ] ).then(
			function () {
				verifyModuleStates();
				throw new Error( 'Error handler should be invoked.' );
			},
			function ( e, modules ) {
				// When the server sets state of 'testMissing' to 'missing'
				// it should bubble up and trigger the error callback of the job for 'testUsesNestedMissing'.
				assert.strictEqual( modules.indexOf( 'testMissing' ) !== -1, true, 'Triggered by testMissing.' );

				verifyModuleStates();
			}
		);
	} );

	QUnit.test( 'Skip-function handling', function ( assert ) {
		mw.loader.register( [
			// [module, version, dependencies, group, source, skip]
			[ 'testSkipped', '1', [], null, 'testloader', 'return true;' ],
			[ 'testNotSkipped', '1', [], null, 'testloader', 'return false;' ],
			[ 'testUsesSkippable', '1', [ 'testSkipped', 'testNotSkipped' ], null, 'testloader' ]
		] );

		return mw.loader.using( [ 'testUsesSkippable' ] ).then(
			function () {
				assert.strictEqual( mw.loader.getState( 'testSkipped' ), 'ready', 'Skipped module' );
				assert.strictEqual( mw.loader.getState( 'testNotSkipped' ), 'ready', 'Regular module' );
				assert.strictEqual( mw.loader.getState( 'testUsesSkippable' ), 'ready', 'Regular module with skippable dependency' );
			},
			function ( e, badmodules ) {
				// Should not fail and QUnit would already catch this,
				// but add a handler anyway to report details from 'badmodules
				assert.deepEqual( badmodules, [], 'Bad modules' );
			}
		);
	} );

	// This bug was actually already fixed in 1.18 and later when discovered in 1.17.
	QUnit.test( '.load( "//protocol-relative" ) - T32825', function ( assert ) {
		var target,
			done = assert.async();

		// URL to the callback script
		target = QUnit.fixurl(
			mw.config.get( 'wgServer' ) + mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/mwLoaderTestCallback.js'
		);
		// Ensure a protocol-relative URL for this test
		target = target.replace( /https?:/, '' );
		assert.strictEqual( target.slice( 0, 2 ), '//', 'URL is protocol-relative' );

		mw.loader.testCallback = function () {
			// Ensure once, delete now
			delete mw.loader.testCallback;
			assert.ok( true, 'callback' );
			done();
		};

		// Go!
		mw.loader.load( target );
	} );

	QUnit.test( '.load( "/absolute-path" )', function ( assert ) {
		var target,
			done = assert.async();

		// URL to the callback script
		target = QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/mwLoaderTestCallback.js' );
		assert.strictEqual( target.slice( 0, 1 ), '/', 'URL is relative to document root' );

		mw.loader.testCallback = function () {
			// Ensure once, delete now
			delete mw.loader.testCallback;
			assert.ok( true, 'callback' );
			done();
		};

		// Go!
		mw.loader.load( target );
	} );

	QUnit.test( 'Empty string module name - T28804', function ( assert ) {
		var done = false;

		assert.strictEqual( mw.loader.getState( '' ), null, 'State (unregistered)' );

		mw.loader.register( '', 'v1' );
		assert.strictEqual( mw.loader.getState( '' ), 'registered', 'State (registered)' );
		assert.strictEqual( mw.loader.getVersion( '' ), 'v1', 'Version' );

		mw.loader.implement( '', function () {
			done = true;
		} );

		return mw.loader.using( '', function () {
			assert.strictEqual( done, true, 'script ran' );
			assert.strictEqual( mw.loader.getState( '' ), 'ready', 'State (ready)' );
		} );
	} );

	QUnit.test( 'Executing race - T112232', function ( assert ) {
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
		// Enable store and stub timeout/idle scheduling
		this.sandbox.stub( mw.loader.store, 'enabled', true );
		this.sandbox.stub( window, 'setTimeout', function ( fn ) {
			fn();
		} );
		this.sandbox.stub( mw, 'requestIdleCallback', function ( fn ) {
			fn();
		} );

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
				assert.strictEqual( typeof mw.loader.store.get( 'test.stale' ), 'string', 'In store' );
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
		var script = 0;
		// Enable store and stub timeout/idle scheduling
		this.sandbox.stub( mw.loader.store, 'enabled', true );
		this.sandbox.stub( window, 'setTimeout', function ( fn ) {
			fn();
		} );
		this.sandbox.stub( mw, 'requestIdleCallback', function ( fn ) {
			fn();
		} );

		mw.loader.register( 'test.stalebc', 'v2' );
		assert.strictEqual( mw.loader.store.get( 'test.stalebc' ), false, 'Not in store' );

		mw.loader.implement( 'test.stalebc', function () {
			script++;
		} );

		return mw.loader.using( 'test.stalebc' )
			.then( function () {
				assert.strictEqual( script, 1, 'module script ran' );
				assert.strictEqual( mw.loader.getState( 'test.stalebc' ), 'ready' );
				assert.strictEqual( typeof mw.loader.store.get( 'test.stalebc' ), 'string', 'In store' );
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

	QUnit.test( 'No storing of group=private responses', function ( assert ) {
		var name = 'test.group.priv';

		// Enable store and stub timeout/idle scheduling
		this.sandbox.stub( mw.loader.store, 'enabled', true );
		this.sandbox.stub( window, 'setTimeout', function ( fn ) {
			fn();
		} );
		this.sandbox.stub( mw, 'requestIdleCallback', function ( fn ) {
			fn();
		} );

		// See ResourceLoaderStartUpModule::$groupIds
		mw.loader.register( name, 'x', [], 1 );
		assert.strictEqual( mw.loader.store.get( name ), false, 'Not in store' );

		mw.loader.implement( name, function () {} );
		return mw.loader.using( name ).then( function () {
			assert.strictEqual( mw.loader.getState( name ), 'ready' );
			assert.strictEqual( mw.loader.store.get( name ), false, 'Still not in store' );
		} );
	} );

	QUnit.test( 'No storing of group=user responses', function ( assert ) {
		var name = 'test.group.user';

		// Enable store and stub timeout/idle scheduling
		this.sandbox.stub( mw.loader.store, 'enabled', true );
		this.sandbox.stub( window, 'setTimeout', function ( fn ) {
			fn();
		} );
		this.sandbox.stub( mw, 'requestIdleCallback', function ( fn ) {
			fn();
		} );

		// See ResourceLoaderStartUpModule::$groupIds
		mw.loader.register( name, 'y', [], 0 );
		assert.strictEqual( mw.loader.store.get( name ), false, 'Not in store' );

		mw.loader.implement( name, function () {} );
		return mw.loader.using( name ).then( function () {
			assert.strictEqual( mw.loader.getState( name ), 'ready' );
			assert.strictEqual( mw.loader.store.get( name ), false, 'Still not in store' );
		} );
	} );

	QUnit.test( 'mw.loader.store.init - Invalid JSON', function ( assert ) {
		// Reset
		this.sandbox.stub( mw.loader.store, 'enabled', null );
		this.sandbox.stub( mw.loader.store, 'items', {} );
		this.resetStoreKey = true;
		localStorage.setItem( mw.loader.store.key, 'invalid' );

		mw.loader.store.init();
		assert.strictEqual( mw.loader.store.enabled, true, 'Enabled' );
		assert.strictEqual(
			$.isEmptyObject( mw.loader.store.items ),
			true,
			'Items starts fresh'
		);
	} );

	QUnit.test( 'mw.loader.store.init - Wrong JSON', function ( assert ) {
		// Reset
		this.sandbox.stub( mw.loader.store, 'enabled', null );
		this.sandbox.stub( mw.loader.store, 'items', {} );
		this.resetStoreKey = true;
		localStorage.setItem( mw.loader.store.key, JSON.stringify( { wrong: true } ) );

		mw.loader.store.init();
		assert.strictEqual( mw.loader.store.enabled, true, 'Enabled' );
		assert.strictEqual(
			$.isEmptyObject( mw.loader.store.items ),
			true,
			'Items starts fresh'
		);
	} );

	QUnit.test( 'mw.loader.store.init - Expired JSON', function ( assert ) {
		// Reset
		this.sandbox.stub( mw.loader.store, 'enabled', null );
		this.sandbox.stub( mw.loader.store, 'items', {} );
		this.resetStoreKey = true;
		localStorage.setItem( mw.loader.store.key, JSON.stringify( {
			items: { use: 'not me' },
			vary: mw.loader.store.vary,
			asOf: 130161 // 2011-04-01 12:00
		} ) );

		mw.loader.store.init();
		assert.strictEqual( mw.loader.store.enabled, true, 'Enabled' );
		assert.strictEqual(
			$.isEmptyObject( mw.loader.store.items ),
			true,
			'Items starts fresh'
		);
	} );

	QUnit.test( 'mw.loader.store.init - Good JSON', function ( assert ) {
		// Reset
		this.sandbox.stub( mw.loader.store, 'enabled', null );
		this.sandbox.stub( mw.loader.store, 'items', {} );
		this.resetStoreKey = true;
		localStorage.setItem( mw.loader.store.key, JSON.stringify( {
			items: { use: 'me' },
			vary: mw.loader.store.vary,
			asOf: Math.ceil( Date.now() / 1e7 ) - 5 // ~ 13 hours ago
		} ) );

		mw.loader.store.init();
		assert.strictEqual( mw.loader.store.enabled, true, 'Enabled' );
		assert.deepEqual(
			mw.loader.store.items,
			{ use: 'me' },
			'Stored items are loaded'
		);
	} );

	QUnit.test( 'require()', function ( assert ) {
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
		return mw.loader.using( [ 'test.require1', 'test.require2', 'test.require3', 'test.require4' ] ).then( function ( require ) {
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
				assert.strictEqual( String( e ), null, 'require works asynchrously in debug mode' );
			}
		} );
	} );

	QUnit.test( 'Implicit dependencies', function ( assert ) {
		var user = 0,
			site = 0,
			siteFromUser = 0;

		mw.loader.implement(
			'site',
			function () {
				site++;
			}
		);
		mw.loader.implement(
			'user',
			function () {
				user++;
				siteFromUser = site;
			}
		);

		return mw.loader.using( 'user', function () {
			assert.strictEqual( site, 1, 'site module' );
			assert.strictEqual( user, 1, 'user module' );
			assert.strictEqual( siteFromUser, 1, 'site ran before user' );
		} ).always( function () {
			// Reset
			mw.loader.moduleRegistry.site.state = 'registered';
			mw.loader.moduleRegistry.user.state = 'registered';
		} );
	} );

	QUnit.test( '.getScript() - success', function ( assert ) {
		var scriptUrl = QUnit.fixurl(
			mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/mediawiki.loader.getScript.example.js'
		);

		return mw.loader.getScript( scriptUrl ).then(
			function () {
				assert.strictEqual( mw.getScriptExampleScriptLoaded, true, 'Data attached to a global object is available' );
			}
		);
	} );

	QUnit.test( '.getScript() - failure', function ( assert ) {
		assert.rejects(
			mw.loader.getScript( 'https://example.test/not-found' ),
			/Failed to load script/,
			'Descriptive error message'
		);
	} );

}() );
