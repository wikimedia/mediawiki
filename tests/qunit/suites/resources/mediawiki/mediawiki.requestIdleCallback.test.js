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
	QUnit.test( 'callback', 3, function ( assert ) {
		var sequence,
			tick = this.tick;

		mw.requestIdleCallback( function () {
			sequence.push( 'x' );
			tick( 30 );
		} );
		mw.requestIdleCallback( function () {
			tick( 5 );
			sequence.push( 'y' );
			tick( 30 );
		} );
		// Task Z is not run in the first sequence because the
		// first two tasks consumed the available 50ms budget.
		mw.requestIdleCallback( function () {
			sequence.push( 'z' );
			tick( 30 );
		} );

		sequence = [];
		tick( 1000 );
		assert.deepEqual( sequence, [ 'x', 'y' ] );

		sequence = [];
		tick( 1000 );
		assert.deepEqual( sequence, [ 'z' ] );

		sequence = [];
		tick( 1000 );
		assert.deepEqual( sequence, [] );
	} );

	// Schedule new callbacks within a callback that tick
	// the clock. If the budget is exceeded, the newly scheduled
	// task is delayed until the next idle period.
	QUnit.test( 'nest-tick', 3, function ( assert ) {
		var sequence,
			tick = this.tick;

		mw.requestIdleCallback( function () {
			sequence.push( 'x' );
			tick( 30 );
		} );
		// Task Y is a task that schedules another task.
		mw.requestIdleCallback( function () {
			function other() {
				sequence.push( 'y' );
				tick( 35 );
			}
			mw.requestIdleCallback( other );
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

	// Schedule new callbacks within a callback that run quickly.
	// Note how the newly scheduled task gets to run as part of the
	// current idle period (budget allowing).
	QUnit.test( 'nest-quick', 2, function ( assert ) {
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
