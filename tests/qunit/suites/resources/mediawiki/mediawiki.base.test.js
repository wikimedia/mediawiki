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
			QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/mwLoaderTestCallback.js' )
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
