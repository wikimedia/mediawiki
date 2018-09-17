( function () {
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
			this.sandbox.stub( mw, 'requestIdleCallback', mw.requestIdleCallbackInternal );
		}
	} ) );

	QUnit.test( 'callback', function ( assert ) {
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

	QUnit.test( 'nested', function ( assert ) {
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

	QUnit.test( 'timeRemaining', function ( assert ) {
		var sequence,
			tick = this.tick,
			jobs = [
				{ time: 10, key: 'a' },
				{ time: 20, key: 'b' },
				{ time: 10, key: 'c' },
				{ time: 20, key: 'd' },
				{ time: 10, key: 'e' }
			];

		mw.requestIdleCallback( function doWork( deadline ) {
			var job;
			while ( jobs[ 0 ] && deadline.timeRemaining() > 15 ) {
				job = jobs.shift();
				tick( job.time );
				sequence.push( job.key );
			}
			if ( jobs[ 0 ] ) {
				mw.requestIdleCallback( doWork );
			}
		} );

		sequence = [];
		tick();
		assert.deepEqual( sequence, [ 'a', 'b', 'c' ] );

		sequence = [];
		tick();
		assert.deepEqual( sequence, [ 'd', 'e' ] );
	} );

	if ( window.requestIdleCallback ) {
		QUnit.test( 'native', function ( assert ) {
			var done = assert.async();
			// Remove polyfill and clock stub
			mw.requestIdleCallback.restore();
			this.clock.restore();
			mw.requestIdleCallback( function () {
				assert.expect( 0 );
				done();
			} );
		} );
	}

}() );
