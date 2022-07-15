( function () {
	QUnit.module( 'mediawiki.base', {
		beforeEach: function () {
			this.clock = sinon.useFakeTimers();
		},
		afterEach: function () {
			this.clock.restore();
		}
	} );

	QUnit.test( 'mw.hook - add() and fire()', function ( assert ) {
		mw.hook( 'test.basic' ).add( function () {
			assert.step( 'call' );
		} );

		mw.hook( 'test.basic' ).fire();
		assert.verifySteps( [ 'call' ] );
	} );

	QUnit.test( 'mw.hook - "hasOwnProperty" as hook name', function ( assert ) {
		mw.hook( 'hasOwnProperty' ).add( function () {
			assert.step( 'call' );
		} );

		mw.hook( 'hasOwnProperty' ).fire();
		assert.verifySteps( [ 'call' ] );
	} );

	QUnit.test( 'mw.hook - Number of arguments', function ( assert ) {
		// eslint-disable-next-line no-unused-vars
		mw.hook( 'test.numargs' ).add( function ( one, two ) {
			assert.step( String( arguments.length ) );
		} );

		mw.hook( 'test.numargs' ).fire( 'A' );
		mw.hook( 'test.numargs' ).fire( 'X', 'Y', 'Z' );

		assert.verifySteps(
			[ '1', '3' ],
			'Runs normally when number of arguments on fire() is different to those taken by the handler function'
		);
	} );

	QUnit.test( 'mw.hook - Variadic firing data and array data type', function ( assert ) {
		var data;
		mw.hook( 'test.data' ).add( function ( one, two ) {
			data = { arg1: one, arg2: two };
		} );

		mw.hook( 'test.data' ).fire( 'x', [ 'y', 'z' ] );
		assert.deepEqual( data, {
			arg1: 'x',
			// Make sure variadic arguments (array-like), and actual array values
			// are not confused with each other
			arg2: [ 'y', 'z' ]
		} );

		mw.hook( 'test.data' ).fire( [ '1', '2' ] );
		// Make sure that an array with two elements is
		// considered the first argument
		assert.deepEqual( data, {
			arg1: [ '1', '2' ],
			arg2: undefined
		} );
	} );

	QUnit.test( 'mw.hook - Chainable', function ( assert ) {
		var hook, add, fire;

		hook = mw.hook( 'test.chainable' );
		assert.strictEqual( hook.add(), hook, 'hook.add is chainable' );
		assert.strictEqual( hook.remove(), hook, 'hook.remove is chainable' );
		assert.strictEqual( hook.fire(), hook, 'hook.fire is chainable' );

		hook = mw.hook( 'test.detach' );
		add = hook.add;
		fire = hook.fire;
		add( function ( data ) {
			assert.step( data );
		} );
		fire( 'x' );
		assert.verifySteps( [ 'x' ], 'Data from detached method' );
	} );

	QUnit.test( 'mw.hook - Memory from before', function ( assert ) {
		mw.hook( 'test.fireBefore' )
			.fire( 'x' )
			.add( function ( data ) {
				assert.step( data );
			} );
		assert.verifySteps( [ 'x' ], 'Remember data from earlier firing' );

		mw.hook( 'test.fireTwiceBefore' )
			.fire( 'x1' )
			.fire( 'x2' )
			.add( function ( data ) {
				assert.step( data );
			} );
		assert.verifySteps( [ 'x2' ], 'Remember only the most recent firing' );
	} );

	QUnit.test( 'mw.hook - Multiple consumers with memory between fires', function ( assert ) {
		mw.hook( 'test.many' )
			.add( function ( data ) {
				// Receive each fire as it happens
				assert.step( 'early ' + data );
			} )
			.fire( 'x' )
			.fire( 'y' )
			.fire( 'z' )
			.add( function ( data ) {
				// Receive memory from last fire
				assert.step( 'late ' + data );
			} );

		assert.verifySteps( [
			'early x',
			'early y',
			'early z',
			'late z'
		] );
	} );

	QUnit.test( 'mw.hook - Memory is not wiped when consumed.', function ( assert ) {
		var handler = function ( data ) {
			assert.step( data + '1' );
		};

		mw.hook( 'test.memory' ).fire( 'A' );
		mw.hook( 'test.memory' ).add( handler );
		mw.hook( 'test.memory' )
			.add( function ( data ) {
				assert.step( data + '2' );
			} );

		mw.hook( 'test.memory' )
			.remove( handler )
			.add( handler );
		assert.verifySteps(
			[ 'A1', 'A2', 'A1' ],
			'Consuming a fired hook from the memory does not clear it.'
		);
	} );

	QUnit.test( 'mw.hook - Unregistering handler.', function ( assert ) {
		var handler = function ( data ) {
			assert.step( data );
		};

		mw.hook( 'test.remove' )
			.add( handler )
			.remove( handler )
			.fire( 'A' );

		assert.verifySteps( [], 'The handler was unregistered before the fired event.' );

		mw.hook( 'test.remove' )
			.add( handler )
			.remove( handler );

		assert.verifySteps( [ 'A' ], 'The handler runs with memory event before it is unregistered.' );
	} );

	QUnit.test( 'mw.hook - Limit impact of consumer errors T223352', function ( assert ) {
		mw.hook( 'test.catch' )
			.add( function callerA( data ) {
				assert.step( 'A' + data );
				throw new Error( 'Fail A' );
			} )
			.fire( '1' )
			.add( function callerB( data ) {
				assert.step( 'B' + data );
			} )
			.fire( '2' );

		assert.verifySteps( [ 'A1', 'B1', 'A2', 'B2' ] );

		assert.throws( function () {
			this.clock.tick( 10 );
		}, /Fail A/, 'Global error' );
	} );

	QUnit.test( 'mw.hook - Variadic add and remove', function ( assert ) {
		function callerA( data ) {
			assert.step( 'A' + data );
		}

		mw.hook( 'test.variadic' )
			.add(
				callerA,
				callerA,
				function callerB( data ) {
					assert.step( 'B' + data );
				},
				callerA
			)
			.fire( '1' )
			.remove(
				function callerC() {
					return 'was never here';
				},
				callerA
			)
			.fire( '2' )
			.remove( callerA )
			.fire( '3' );

		assert.verifySteps( [
			'A1', 'A1', 'B1', 'A1',
			'B2',
			'B3'
		], '"add" and "remove" support variadic arguments. ' +
				'"add" does not filter unique. ' +
				'"remove" removes all equal by reference. ' +
				'"remove" is silent if the function is not found'
		);
	} );

	QUnit.test( 'mw.log.makeDeprecated()', function ( assert ) {
		var track = [];
		var log = [];
		var fn;
		this.sandbox.stub( mw, 'track', function ( topic, key ) {
			if ( topic === 'mw.deprecate' ) {
				track.push( key );
			}
		} );
		this.sandbox.stub( mw.log, 'warn', function ( msg ) {
			log.push( msg );
		} );

		fn = mw.log.makeDeprecated( 'key', 'Warning.' );
		for ( var i = 0; i <= 3; i++ ) {
			fn();
		}
		assert.deepEqual( track, [ 'key' ], 'track' );
		assert.deepEqual( log, [ 'Warning.' ], 'log' );

		log = [];
		track = [];
		fn = mw.log.makeDeprecated( null, 'Warning.' );
		for ( var j = 0; j <= 3; j++ ) {
			fn();
		}
		assert.deepEqual( track, [], 'no track' );
		assert.deepEqual( log, [ 'Warning.' ], 'log without track' );
	} );

	QUnit.test( 'mw.log.deprecate()', function ( assert ) {
		var track = [];
		var log = [];
		this.sandbox.stub( mw, 'track', function ( topic, key ) {
			if ( topic === 'mw.deprecate' ) {
				track.push( key );
			}
		} );
		this.sandbox.stub( mw.log, 'warn', function ( msg ) {
			log.push( msg );
		} );
		function getFoo() {
			return 42;
		}

		var obj = {};
		mw.log.deprecate( obj, 'foo', getFoo );

		// By default only logging and no tracking
		assert.strictEqual( obj.foo(), 42, 'first return' );
		assert.deepEqual( track, [], 'once track' );
		assert.deepEqual( log, [ 'Use of "foo" is deprecated.' ], 'once log' );

		// Ignore later calls from the same source code line
		log = [];
		track = [];
		for ( var i = 0; i <= 3; i++ ) {
			obj.foo();
		}
		assert.deepEqual( track, [], 'multi track' );
		assert.deepEqual( log, [ 'Use of "foo" is deprecated.' ], 'multi log' );

		// Custom tracking and logging
		log = [];
		track = [];
		mw.log.deprecate( obj, 'foo', getFoo, 'Hey there!', 'obj.foo thing' );
		assert.strictEqual( obj.foo(), 42, 'return after custom' );
		assert.deepEqual( track, [ 'obj.foo thing' ], 'custom track' );
		assert.deepEqual( log, [ 'Use of "obj.foo thing" is deprecated. Hey there!' ], 'custom log' );
	} );

	QUnit.test( 'RLQ.push', function ( assert ) {
		/* global RLQ */
		var loaded = 0,
			called = 0,
			done = assert.async();
		mw.loader.testCallback = function () {
			loaded++;
			delete mw.loader.testCallback;
		};
		mw.loader.implement( 'test.rlq-push', [
			mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/mwLoaderTestCallback.js'
		] );

		// Regression test for T208093
		RLQ.push( function () {
			called++;
		} );
		assert.strictEqual( called, 1, 'Invoke plain callbacks' );

		RLQ.push( [ 'test.rlq-push', function () {
			assert.strictEqual( loaded, 1, 'Load the required module' );
			done();
		} ] );
	} );

}() );
