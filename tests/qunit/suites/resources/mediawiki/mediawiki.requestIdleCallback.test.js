( function ( mw ) {
	QUnit.module( 'mediawiki.requestIdleCallback', QUnit.newMwEnvironment( {
		setup: function () {
			var time = mw.now(),
				clock = this.clock = this.sandbox.useFakeTimers();

			this.tick = function ( forward ) {
				time += forward;
				clock.tick( forward );
			};
			this.sandbox.stub( mw, 'now', function () {
				return time;
			} );

			// Don't test the native version (if available)
			this.mwRIC = mw.requestIdleCallback;
			mw.requestIdleCallback = mw.requestIdleCallbackInternal;
		},
		teardown: function () {
			mw.requestIdleCallback = this.mwRIC;
		}
	} ) );

	// Basic scheduling of callbacks
	QUnit.test( 'callback', 5, function ( assert ) {
		var sequence, context,
			tick = this.tick;

		mw.requestIdleCallback( function ( deadline ) {
			sequence.push( 'x' );
			context.x = {
				left: deadline.timeRemaining()
			};
			tick( 30 );
		} );
		mw.requestIdleCallback( function ( deadline ) {
			tick( 5 );
			sequence.push( 'y' );
			context.y = {
				left: deadline.timeRemaining()
			};
			tick( 30 );
		} );
		// Task Z is not run in the first sequence because the
		// first two tasks consumed the available 50ms budget.
		mw.requestIdleCallback( function ( deadline ) {
			sequence.push( 'z' );
			context.z = {
				left: deadline.timeRemaining()
			};
			tick( 30 );
		} );

		sequence = [];
		context = {};
		tick( 1000 );
		assert.deepEqual( sequence, [ 'x', 'y' ] );
		assert.deepEqual( context, {
			x: { left: 50 },
			y: { left: 15 }
		} );

		sequence = [];
		context = {};
		tick( 1000 );
		assert.deepEqual( sequence, [ 'z' ] );
		assert.deepEqual( context, {
			z: { left: 50 }
		} );

		sequence = [];
		tick( 1000 );
		assert.deepEqual( sequence, [] );
	} );

	// One of the callbacks has a timeout restriction
	QUnit.test( 'timeout', 5, function ( assert ) {
		var sequence, context,
			tick = this.tick;

		mw.requestIdleCallback( function ( deadline ) {
			sequence.push( 'x' );
			context.x = {
				didTimeout: deadline.didTimeout,
				left: deadline.timeRemaining()
			};
			tick( 60 );
		} );
		mw.requestIdleCallback( function ( deadline ) {
			sequence.push( 'y' );
			context.y = {
				didTimeout: deadline.didTimeout,
				left: deadline.timeRemaining()
			};
			tick( 60 );
		} );
		// Task Z runs after 2s even though the budge is reached
		// because it has a timeout restriction.
		mw.requestIdleCallback( function ( deadline ) {
			sequence.push( 'z' );
			context.z = {
				didTimeout: deadline.didTimeout,
				left: deadline.timeRemaining()
			};
			tick( 60 );
		}, { timeout: 1500 } );

		sequence = [];
		context = {};
		tick( 1000 );
		assert.deepEqual( sequence, [ 'x' ] );
		assert.deepEqual( context, {
			x: {
				didTimeout: false,
				left: 50
			}
		} );

		sequence = [];
		context = {};
		tick( 1000 );
		assert.deepEqual( sequence, [ 'y', 'z' ] );
		assert.deepEqual( context, {
			y: {
				didTimeout: false,
				left: 50
			},
			z: {
				didTimeout: true,
				left: 0
			}
		} );

		sequence = [];
		tick( 1000 );
		assert.deepEqual( sequence, [] );
	} );

	// Reschedule the same callback within a callback
	QUnit.test( 'nest-reschedule', 3, function ( assert ) {
		var sequence,
			tick = this.tick;

		mw.requestIdleCallback( function () {
			sequence.push( 'x' );
			tick( 30 );
		} );
		// Task Y is a friendly task that checks timeRemaining before
		// doing its work. It reschedules itself because the remaining
		// time is too small.
		mw.requestIdleCallback( function myWork( deadline ) {
			// Pretend we need 35ms of working space
			if ( deadline.timeRemaining() < 35 ) {
				mw.requestIdleCallback( myWork );
				return;
			}
			sequence.push( 'y' );
			tick( 35 );
		} );
		mw.requestIdleCallback( function () {
			sequence.push( 'z' );
			tick( 30 );
		} );

		sequence = [];
		tick( 1000 );
		assert.deepEqual( sequence, [ 'x', 'z' ] );

		sequence = [];
		tick( 1000 );
		assert.deepEqual( sequence, [ 'y' ] );

		sequence = [];
		tick( 1000 );
		assert.deepEqual( sequence, [] );
	} );

	// Schedule new callbacks within a callback
	QUnit.test( 'nest-expand', 2, function ( assert ) {
		var sequence,
			tick = this.tick;

		mw.requestIdleCallback( function () {
			sequence.push( 'x' );
			mw.requestIdleCallback( function () {
				sequence.push( 'x-expand' );
			} );
		} );
		mw.requestIdleCallback( function () {
			sequence.push( 'y' );
		} );

		sequence = [];
		tick( 1000 );
		assert.deepEqual( sequence, [ 'x', 'y', 'x-expand' ] );

		sequence = [];
		tick( 1000 );
		assert.deepEqual( sequence, [] );
	} );

}( mediaWiki ) );
