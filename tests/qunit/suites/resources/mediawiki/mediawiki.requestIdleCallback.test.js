( function ( mw ) {
	QUnit.module( 'mediawiki.requestIdleCallback', QUnit.newMwEnvironment( {
		setup: function () {
			var clock = this.clock = this.sandbox.useFakeTimers();

			this.sandbox.stub( mw, 'now', function () {
				return +new Date();
			} );

			this.tick = function ( forward ) {
				return clock.tick( forward || 1 );
			};

			// Always test the polyfill, not native
			this.mwRIC = mw.requestIdleCallback;
			mw.requestIdleCallback = mw.requestIdleCallbackInternal;
		},
		teardown: function () {
			mw.requestIdleCallback = this.mwRIC;
		}
	} ) );

	QUnit.test( 'callback', 1, function ( assert ) {
		var sequence;

		mw.requestIdleCallback( function () {
			sequence.push( 'x' );
		} );
		mw.requestIdleCallback( function () {
			sequence.push( 'y' );
		} );
		mw.requestIdleCallback( function () {
			sequence.push( 'z' );
		} );

		sequence = [];
		this.tick();
		assert.deepEqual( sequence, [ 'x', 'y', 'z' ] );
	} );

	QUnit.test( 'nested', 2, function ( assert ) {
		var sequence;

		mw.requestIdleCallback( function () {
			sequence.push( 'x' );
		} );
		// Task Y is a task that schedules another task.
		mw.requestIdleCallback( function () {
			function other() {
				sequence.push( 'y' );
			}
			mw.requestIdleCallback( other );
		} );
		mw.requestIdleCallback( function () {
			sequence.push( 'z' );
		} );

		sequence = [];
		this.tick();
		assert.deepEqual( sequence, [ 'x', 'z' ] );

		sequence = [];
		this.tick();
		assert.deepEqual( sequence, [ 'y' ] );
	} );

	QUnit.test( 'timeRemaining', 2, function ( assert ) {
		var sequence,
			tick = this.tick,
			jobs = [
				{ time: 10, key: 'a' },
				{ time: 20, key: 'b' },
				{ time: 10, key: 'c' },
				{ time: 20, key: 'd' },
				{ time: 10, key: 'e' }
			];

		debugger;
		mw.requestIdleCallback( function doWork( deadline ) {
			var job;
			while ( jobs[ 0 ] && deadline.timeRemaining() > 15 ) {
				job = jobs.shift();
				tick( job.time );
				sequence.push( job.key );
			}
			if ( jobs[ 0 ] ) {
				debugger;
				mw.requestIdleCallback( doWork );
			}
		} );

		sequence = [];
		debugger;
		tick();
		assert.deepEqual( sequence, [ 'a', 'b', 'c' ] );

		sequence = [];
		debugger;
		tick();
		assert.deepEqual( sequence, [ 'd', 'e' ] );
	} );

}( mediaWiki ) );
