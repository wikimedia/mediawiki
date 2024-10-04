( function () {
	QUnit.module( 'mw.requestIdleCallback', QUnit.newMwEnvironment( {
		beforeEach: function () {
			const clock = this.clock = this.sandbox.useFakeTimers();

			this.sandbox.stub( mw, 'now', () => Date.now() );

			this.tick = function ( forward ) {
				return clock.tick( forward || 1 );
			};

			// Always test the polyfill, not native
			this.sandbox.stub( mw, 'requestIdleCallback', mw.requestIdleCallbackInternal );
		}
	} ) );

	QUnit.test( 'callback', function ( assert ) {
		const sequence = [];

		mw.requestIdleCallback( () => {
			sequence.push( 'x' );
		} );
		mw.requestIdleCallback( () => {
			sequence.push( 'y' );
		} );
		mw.requestIdleCallback( () => {
			sequence.push( 'z' );
		} );

		this.tick();
		assert.deepEqual( sequence, [ 'x', 'y', 'z' ] );
	} );

	QUnit.test( 'nested', function ( assert ) {
		let sequence;

		mw.requestIdleCallback( () => {
			sequence.push( 'x' );
		} );
		// Task Y is a task that schedules another task.
		mw.requestIdleCallback( () => {
			function other() {
				sequence.push( 'y' );
			}
			mw.requestIdleCallback( other );
		} );
		mw.requestIdleCallback( () => {
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
		let sequence;
		const tick = this.tick,
			jobs = [
				{ time: 10, key: 'a' },
				{ time: 20, key: 'b' },
				{ time: 10, key: 'c' },
				{ time: 20, key: 'd' },
				{ time: 10, key: 'e' }
			];

		mw.requestIdleCallback( function doWork( deadline ) {
			let job;
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
			const done = assert.async();
			// Remove polyfill and clock stub
			mw.requestIdleCallback.restore();
			this.clock.restore();
			mw.requestIdleCallback( () => {
				assert.expect( 0 );
				done();
			} );
		} );
	}

}() );
