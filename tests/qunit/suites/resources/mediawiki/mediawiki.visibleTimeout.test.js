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
			this.visibleTimeout = require( 'mediawiki.visibleTimeout' );
			this.visibleTimeout.setDocument( this.mockDocument );

			this.sandbox.useFakeTimers();
			// mw.now() doesn't respect the fake clock injected by useFakeTimers
			this.stub( mw, 'now', ( function () {
				return this.sandbox.clock.now;
			} ).bind( this ) );
		}
	} ) );

	QUnit.test( 'basic usage', function ( assert ) {
		var called = 0;

		this.visibleTimeout.set( function () {
			called++;
		}, 0 );
		assert.strictEqual( called, 0 );
		this.sandbox.clock.tick( 1 );
		assert.strictEqual( called, 1 );

		this.sandbox.clock.tick( 100 );
		assert.strictEqual( called, 1 );

		this.visibleTimeout.set( function () {
			called++;
		}, 10 );
		this.sandbox.clock.tick( 10 );
		assert.strictEqual( called, 2 );
	} );

	QUnit.test( 'can cancel timeout', function ( assert ) {
		var called = 0,
			timeout = this.visibleTimeout.set( function () {
				called++;
			}, 0 );

		this.visibleTimeout.clear( timeout );
		this.sandbox.clock.tick( 10 );
		assert.strictEqual( called, 0 );

		timeout = this.visibleTimeout.set( function () {
			called++;
		}, 100 );
		this.sandbox.clock.tick( 50 );
		assert.strictEqual( called, 0 );
		this.visibleTimeout.clear( timeout );
		this.sandbox.clock.tick( 100 );
		assert.strictEqual( called, 0 );
	} );

	QUnit.test( 'start hidden and become visible', function ( assert ) {
		var called = 0;

		this.mockDocument.hidden = true;
		this.visibleTimeout.set( function () {
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

		this.visibleTimeout.set( function () {
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
