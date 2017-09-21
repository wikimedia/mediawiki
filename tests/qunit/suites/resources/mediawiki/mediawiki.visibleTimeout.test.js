( function ( mw ) {

	QUnit.module( 'mediawiki.visibleTimeout', QUnit.newMwEnvironment( {
		setup: function () {
			// Document with just enough stuff to make the tests work.
			var listeners = [];
			this.mockDocument = {
				hidden: false,
				addEventListener: function ( type, listener ) {
					if ( type === 'visibilitychange' ) {
						listeners.push( listener );
					}
				},
				removeEventListener: function ( type, listener ) {
					var i;
					if ( type === 'visibilitychange' ) {
						i = listeners.indexOf( listener );
						if ( i >= 0 ) {
							listeners.splice( i, 1 );
						}
					}
				},
				// Helper function to swap visibility and run listeners
				toggleVisibility: function () {
					var i;
					this.hidden = !this.hidden;
					for ( i = 0; i < listeners.length; i++ ) {
						listeners[ i ]();
					}
				}
			};
			mw.setVisibleTimeout.debugOverrideDocument( this.mockDocument );

			this.sandbox.useFakeTimers();
		}
	} ) );

	QUnit.test( 'basic usage', function ( assert ) {
		var called = 0;

		mw.setVisibleTimeout( function () {
			called++;
		}, 0 );
		this.sandbox.clock.tick( 1 );
		assert.strictEqual( called, 1 );

		mw.setVisibleTimeout( function () {
			called++;
		}, 10 );
		this.sandbox.clock.tick( 10 );
		assert.strictEqual( called, 2 );
	} );

	QUnit.test( 'can cancel timeout', function ( assert ) {
		var called = 0,
			timeout = mw.setVisibleTimeout( function () {
				called++;
			}, 0 );

		// If the timeout is not cleared the function above
		// will fire and fail the test.
		mw.clearVisibleTimeout( timeout );
		this.sandbox.clock.tick( 10 );
		assert.strictEqual( called, 0 );
	} );

	QUnit.test( 'start hidden and become visible', function ( assert ) {
		var called = 0;

		this.mockDocument.hidden = true;
		mw.setVisibleTimeout( function () {
			called++;
		}, 0 );
		this.sandbox.clock.tick( 10 );
		assert.strictEqual( called, 0 );

		this.mockDocument.toggleVisibility();
		this.sandbox.clock.tick( 10 );
		assert.strictEqual( called, 1 );
	} );

	QUnit.test( 'timeout is cumulative', function ( assert ) {
		var called = 0;

		mw.setVisibleTimeout( function () {
			called++;
		}, 100 );
		this.sandbox.clock.tick( 50 );
		assert.strictEqual( called, 0 );

		this.mockDocument.toggleVisibility();
		this.sandbox.clock.tick( 1000 );
		assert.strictEqual( called, 0 );

		this.mockDocument.toggleVisibility();
		this.sandbox.clock.tick( 50 );
		assert.strictEqual( called, 1 );
	} );
}( mediaWiki ) );
