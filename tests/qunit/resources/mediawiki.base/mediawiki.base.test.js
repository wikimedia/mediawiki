QUnit.module( 'mediawiki.base', ( hooks ) => {
	hooks.beforeEach( function () {
		this.clock = this.sandbox.useFakeTimers();
	} );

	QUnit.test( 'mw.hook - add() and fire()', ( assert ) => {
		mw.hook( 'test.basic' ).add( () => {
			assert.step( 'call' );
		} );

		mw.hook( 'test.basic' ).fire();
		assert.verifySteps( [ 'call' ] );
	} );

	QUnit.test( 'mw.hook - "hasOwnProperty" as hook name', ( assert ) => {
		mw.hook( 'hasOwnProperty' ).add( () => {
			assert.step( 'call' );
		} );

		mw.hook( 'hasOwnProperty' ).fire();
		assert.verifySteps( [ 'call' ] );
	} );

	QUnit.test( 'mw.hook - Number of arguments', ( assert ) => {
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

	QUnit.test( 'mw.hook - Variadic firing data and array data type', ( assert ) => {
		let data;
		mw.hook( 'test.data' ).add( ( one, two ) => {
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

	QUnit.test( 'mw.hook - Chainable', ( assert ) => {
		let hook;

		hook = mw.hook( 'test.chainable' );
		assert.strictEqual( hook.add(), hook, 'hook.add is chainable' );
		assert.strictEqual( hook.remove(), hook, 'hook.remove is chainable' );
		assert.strictEqual( hook.fire(), hook, 'hook.fire is chainable' );

		hook = mw.hook( 'test.detach' );
		const add = hook.add;
		const fire = hook.fire;
		add( ( data ) => {
			assert.step( data );
		} );
		fire( 'x' );
		assert.verifySteps( [ 'x' ], 'Data from detached method' );
	} );

	QUnit.test( 'mw.hook - Memory from before', ( assert ) => {
		mw.hook( 'test.fireBefore' )
			.fire( 'x' )
			.add( ( data ) => {
				assert.step( data );
			} );
		assert.verifySteps( [ 'x' ], 'Remember data from earlier firing' );

		mw.hook( 'test.fireTwiceBefore' )
			.fire( 'x1' )
			.fire( 'x2' )
			.add( ( data ) => {
				assert.step( data );
			} );
		assert.verifySteps( [ 'x2' ], 'Remember only the most recent firing' );
	} );

	QUnit.test( 'mw.hook - functions always registered before firing', ( assert ) => {
		mw.hook( 'test.register' ).fire();

		function onceHandler() {
			// The handler has already be registered so can be removed
			mw.hook( 'test.register' ).remove( onceHandler );
			assert.step( 'call' );
		}
		mw.hook( 'test.register' ).add( onceHandler );
		// Subsequent fire does nothing as handler has been removed
		mw.hook( 'test.register' ).fire();

		assert.verifySteps( [ 'call' ] );
	} );

	QUnit.test( 'mw.hook - Multiple consumers with memory between fires', ( assert ) => {
		mw.hook( 'test.many' )
			.add( ( data ) => {
				// Receive each fire as it happens
				assert.step( 'early ' + data );
			} )
			.fire( 'x' )
			.fire( 'y' )
			.fire( 'z' )
			.add( ( data ) => {
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

	QUnit.test( 'mw.hook - Memory is not wiped when consumed.', ( assert ) => {
		function handler( data ) {
			assert.step( data + '1' );
		}

		mw.hook( 'test.memory' ).fire( 'A' );
		mw.hook( 'test.memory' ).add( handler );
		mw.hook( 'test.memory' )
			.add( ( data ) => {
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

	QUnit.test( 'mw.hook - Unregistering handler.', ( assert ) => {
		function handler( data ) {
			assert.step( data );
		}

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

	QUnit.test( 'mw.hook - Limit impact of consumer errors T223352', ( assert ) => {
		mw.hook( 'test.catch' )
			.add( ( data ) => {
				assert.step( 'A' + data );
				throw new Error( 'Fail A' );
			} )
			.fire( '1' )
			.add( ( data ) => {
				assert.step( 'B' + data );
			} )
			.fire( '2' );

		assert.verifySteps( [ 'A1', 'B1', 'A2', 'B2' ] );

		assert.throws( function () {
			this.clock.tick( 10 );
		}, /Fail A/, 'Global error' );
	} );

	QUnit.test( 'mw.hook - Variadic add and remove', ( assert ) => {
		function callerA( data ) {
			assert.step( 'A' + data );
		}

		mw.hook( 'test.variadic' )
			.add(
				callerA,
				callerA,
				( data ) => {
					assert.step( 'B' + data );
				},
				callerA
			)
			.fire( '1' )
			.remove(
				() => 'was never here',
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

	QUnit.test( 'mw.hook - deprecate() [add first]', function ( assert ) {
		this.sandbox.stub( mw.log, 'warn', ( msg ) => {
			assert.step( 'warn: ' + msg );
		} );

		mw.hook( 'test.deprecated_first' ).add( ( a, b ) => {
			assert.step( 'call handler1: ' + a + b );
		} );
		mw.hook( 'test.deprecated_first' ).add( ( a, b ) => {
			assert.step( 'call handler2: ' + a + b );
		} );
		assert.verifySteps( [], 'no warning until we know it is deprecated' );

		mw.hook( 'test.deprecated_first' ).deprecate().fire( 'foo', 'bar' );
		assert.verifySteps( [
			'warn: mw.hook "test.deprecated_first" is deprecated.',
			'call handler1: foobar',
			'call handler2: foobar'
		] );

		// Confirm there are no duplicate warnings from the same source
		for ( let i = 0; i < 3; i++ ) {
			mw.hook( 'test.deprecated_first' ).add( ( a, b ) => {
				assert.step( 'call handler3: ' + a + b );
			} );
		}
		assert.verifySteps( [
			'warn: mw.hook "test.deprecated_first" is deprecated.',
			'call handler3: foobar',
			'call handler3: foobar',
			'call handler3: foobar'
		] );
	} );

	QUnit.test( 'mw.hook - deprecate() [extra msg]', function ( assert ) {
		this.sandbox.stub( mw.log, 'warn', ( msg ) => {
			assert.step( 'warn: ' + msg );
		} );

		mw.hook( 'test.deprecated_msg' )
			.deprecate( 'Use "something" instead.' )
			.fire( 'foo', 'bar' );

		mw.hook( 'test.deprecated_msg' ).add( ( a, b ) => {
			assert.step( 'call handler1: ' + a + b );
		} );

		assert.verifySteps( [
			'warn: mw.hook "test.deprecated_msg" is deprecated. Use "something" instead.',
			'call handler1: foobar'
		] );
	} );

	QUnit.test( 'mw.hook - deprecate() [add later]', function ( assert ) {
		this.sandbox.stub( mw.log, 'warn', ( msg ) => {
			assert.step( 'warn: ' + msg );
		} );

		mw.hook( 'test.deprecated_later' ).deprecate().fire( 'foo', 'bar' );

		assert.verifySteps( [], 'no warning unless hook is used' );

		mw.hook( 'test.deprecated_later' ).add( ( a, b ) => {
			assert.step( 'call handler1: ' + a + b );
		} );
		mw.hook( 'test.deprecated_later' ).add( ( a, b ) => {
			assert.step( 'call handler2: ' + a + b );
		} );

		assert.verifySteps( [
			'warn: mw.hook "test.deprecated_later" is deprecated.',
			'call handler1: foobar',
			'warn: mw.hook "test.deprecated_later" is deprecated.',
			'call handler2: foobar'
		] );
	} );

	QUnit.test( 'mw.log.makeDeprecated()', function ( assert ) {
		let track = [];
		let log = [];
		let fn;
		this.sandbox.stub( mw, 'track', ( topic, key ) => {
			if ( topic === 'mw.deprecate' ) {
				track.push( key );
			}
		} );
		this.sandbox.stub( mw.log, 'warn', ( msg ) => {
			log.push( msg );
		} );

		fn = mw.log.makeDeprecated( 'key', 'Warning.' );
		for ( let i = 0; i <= 3; i++ ) {
			fn();
		}
		assert.deepEqual( track, [ 'key' ], 'track' );
		assert.deepEqual( log, [ 'Warning.' ], 'log' );

		log = [];
		track = [];
		fn = mw.log.makeDeprecated( null, 'Warning.' );
		for ( let j = 0; j <= 3; j++ ) {
			fn();
		}
		assert.deepEqual( track, [], 'no track' );
		assert.deepEqual( log, [ 'Warning.' ], 'log without track' );
	} );

	QUnit.test( 'mw.log.deprecate()', function ( assert ) {
		let track = [];
		let log = [];
		this.sandbox.stub( mw, 'track', ( topic, key ) => {
			if ( topic === 'mw.deprecate' ) {
				track.push( key );
			}
		} );
		this.sandbox.stub( mw.log, 'warn', ( msg ) => {
			log.push( msg );
		} );
		function getFoo() {
			return 42;
		}

		const obj = {};
		mw.log.deprecate( obj, 'foo', getFoo );

		// By default only logging and no tracking
		assert.strictEqual( obj.foo(), 42, 'first return' );
		assert.deepEqual( track, [], 'once track' );
		assert.deepEqual( log, [ 'Use of "foo" is deprecated.' ], 'once log' );

		// Ignore later calls from the same source code line
		log = [];
		track = [];
		for ( let i = 0; i <= 3; i++ ) {
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

	QUnit.test( 'RLQ.push', ( assert ) => {
		/* global RLQ */
		let loaded = 0;
		mw.loader.implement( 'test.rlq-push', () => {
			loaded++;
		} );

		// Regression test for T208093
		let called = 0;
		RLQ.push( () => {
			called++;
		} );
		assert.strictEqual( called, 1, 'Invoke plain callbacks' );

		const done = assert.async();
		RLQ.push( [ 'test.rlq-push', function () {
			assert.strictEqual( loaded, 1, 'Load the required module' );
			done();
		} ] );
	} );
} );
