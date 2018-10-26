( function () {
	QUnit.module( 'mediawiki.base' );

	QUnit.test( 'mw.hook - basic', function ( assert ) {
		var q = [];
		mw.hook( 'test.hook.basic' ).add( function () {
			q.push( 'basic' );
		} );

		mw.hook( 'test.hook.basic' ).fire();
		assert.deepEqual( q, [ 'basic' ], 'Callback' );
	} );

	QUnit.test( 'mw.hook - name', function ( assert ) {
		var q = [];
		mw.hook( 'hasOwnProperty' ).add( function () {
			q.push( 'prototype' );
		} );

		mw.hook( 'hasOwnProperty' ).fire();
		assert.deepEqual( q, [ 'prototype' ], 'Callback' );
	} );

	QUnit.test( 'mw.hook - data', function ( assert ) {
		var q;

		mw.hook( 'test.hook.data' ).add( function ( data1, data2 ) {
			q = [ data1, data2 ];
		} );
		mw.hook( 'test.hook.data' ).fire( 'example', [ 'two' ] );

		assert.deepEqual( q,
			[
				'example',
				[ 'two' ]
			],
			'Data containing a string and an array'
		);
	} );

	QUnit.test( 'mw.hook - chainable', function ( assert ) {
		var hook, add, fire, q = [];

		hook = mw.hook( 'test.hook.chainable' );
		assert.strictEqual( hook.add(), hook, 'hook.add is chainable' );
		assert.strictEqual( hook.remove(), hook, 'hook.remove is chainable' );
		assert.strictEqual( hook.fire(), hook, 'hook.fire is chainable' );

		hook = mw.hook( 'test.hook.detach' );
		add = hook.add;
		fire = hook.fire;
		add( function ( x, y ) {
			q.push( x, y );
		} );
		fire( 'x', 'y' );
		assert.deepEqual( q, [ 'x', 'y' ], 'Contextless firing with data' );
	} );

	QUnit.test( 'mw.hook - memory before', function ( assert ) {
		var q;

		q = [];
		mw.hook( 'test.hook.fireBefore' ).fire().add( function () {
			q.push( 'X' );
		} );
		assert.deepEqual( q, [ 'X' ], 'Remember previous firing for newly added handler' );

		q = [];
		mw.hook( 'test.hook.fireTwiceBefore' ).fire( 'Y1' ).fire( 'Y2' ).add( function ( data ) {
			q.push( data );
		} );
		assert.deepEqual( q, [ 'Y2' ], 'Remember only the most recent firing' );
	} );

	QUnit.test( 'mw.hook - memory before and after', function ( assert ) {
		var q1 = [], q2 = [];
		mw.hook( 'test.hook.many' )
			.add( function ( chr ) {
				q1.push( chr );
			} )
			.fire( 'x' ).fire( 'y' ).fire( 'z' )
			.add( function ( chr ) {
				q2.push( chr );
			} );

		assert.deepEqual( q1, [ 'x', 'y', 'z' ], 'Multiple fires after callback addition' );
		assert.deepEqual( q2, [ 'z' ], 'Last fire applied to new handler' );
	} );

	QUnit.test( 'mw.hook - data variadic', function ( assert ) {
		var q = [];
		function callback( chr ) {
			q.push( chr );
		}

		mw.hook( 'test.hook.variadic' )
			.add(
				callback,
				callback,
				function ( chr ) {
					q.push( chr );
				},
				callback
			)
			.fire( 'x' )
			.remove(
				function () {
					'not-added';
				},
				callback
			)
			.fire( 'y' )
			.remove( callback )
			.fire( 'z' );

		assert.deepEqual(
			q,
			[ 'x', 'x', 'x', 'x', 'y', 'z' ],
			'"add" and "remove" support variadic arguments. ' +
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
