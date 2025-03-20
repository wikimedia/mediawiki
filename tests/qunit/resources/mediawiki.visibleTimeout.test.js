QUnit.module( 'mediawiki.visibleTimeout', QUnit.newMwEnvironment( {
	beforeEach: function () {
		// Document with just enough stuff to make the tests work.
		const listeners = [];
		this.mockDocument = {
			hidden: false,
			addEventListener: function ( type, listener ) {
				if ( type === 'visibilitychange' ) {
					listeners.push( listener );
				}
			},
			removeEventListener: function ( type, listener ) {
				let i;
				if ( type === 'visibilitychange' ) {
					i = listeners.indexOf( listener );
					if ( i >= 0 ) {
						listeners.splice( i, 1 );
					}
				}
			},
			// Helper function to swap visibility and run listeners
			toggleVisibility: function () {
				let i;
				this.hidden = !this.hidden;
				for ( i = 0; i < listeners.length; i++ ) {
					listeners[ i ]();
				}
			}
		};
		this.visibleTimeout = require( 'mediawiki.visibleTimeout' );
		this.visibleTimeout.init( this.mockDocument );

		this.sandbox.useFakeTimers();
		// mw.now() doesn't respect the fake clock injected by useFakeTimers
		this.stub( mw, 'now', () => this.sandbox.clock.now );
	},
	afterEach: function () {
		// Restore
		this.visibleTimeout.init();
	}
} ) );

QUnit.test( 'visibleTimeoutId is always a positive integer', function ( assert ) {
	let called = 0,
		visibleTimeoutId = this.visibleTimeout.set( () => {
			called++;
		}, 0 );

	// First value for visibleTimeoutId should be 1
	assert.strictEqual( visibleTimeoutId, 1 );
	this.visibleTimeout.clear( visibleTimeoutId );
	assert.strictEqual( called, 0 );

	visibleTimeoutId = this.visibleTimeout.set( () => {
		called++;
	}, 100 );
	assert.strictEqual( visibleTimeoutId, 2 );
	this.visibleTimeout.clear( visibleTimeoutId );
	assert.strictEqual( called, 0 );

	visibleTimeoutId = this.visibleTimeout.set( () => {
		called++;
	}, 0 );
	assert.strictEqual( visibleTimeoutId, 3 );
	assert.strictEqual( called, 0 );

	// VisibleTimeoutId should still be incremented even when
	// the previous value was not cleared
	visibleTimeoutId = this.visibleTimeout.set( () => {
		called++;
	}, 0 );
	assert.strictEqual( visibleTimeoutId, 4 );
	assert.strictEqual( called, 0 );
} );

QUnit.test( 'basic usage when visible', function ( assert ) {
	let called = 0;

	this.visibleTimeout.set( () => {
		called++;
	}, 0 );
	assert.strictEqual( called, 0 );
	this.sandbox.clock.tick( 1 );
	assert.strictEqual( called, 1 );

	this.sandbox.clock.tick( 100 );
	assert.strictEqual( called, 1 );

	this.visibleTimeout.set( () => {
		called++;
	}, 10 );
	this.sandbox.clock.tick( 10 );
	assert.strictEqual( called, 2 );
} );

QUnit.test( 'basic usage - fallback assumes visible', function ( assert ) {
	const mockDoc = {
		addEventListener: function () {},
		removeEventListener: function () {}
	};
	const visible = require( 'mediawiki.visibleTimeout' );
	visible.init( mockDoc );

	let called = 0;

	visible.set( () => {
		called++;
	}, 1 );
	// expect call after next tick
	assert.strictEqual( called, 0 );
	this.sandbox.clock.tick( 1 );
	assert.strictEqual( called, 1 );

	// expect call only once
	this.sandbox.clock.tick( 100 );
	assert.strictEqual( called, 1 );

	visible.set( () => {
		called++;
	}, 10 );
	// expect call not until after delay has elapsed
	assert.strictEqual( called, 1 );
	this.sandbox.clock.tick( 2 );
	assert.strictEqual( called, 1 );

	// expect after this
	this.sandbox.clock.tick( 9 );
	assert.strictEqual( called, 2 );
} );

QUnit.test( 'can cancel timeout', function ( assert ) {
	let called = 0,
		timeout = this.visibleTimeout.set( () => {
			called++;
		}, 0 );

	this.visibleTimeout.clear( timeout );
	this.sandbox.clock.tick( 10 );
	assert.strictEqual( called, 0 );

	timeout = this.visibleTimeout.set( () => {
		called++;
	}, 100 );
	this.sandbox.clock.tick( 50 );
	assert.strictEqual( called, 0 );
	this.visibleTimeout.clear( timeout );
	this.sandbox.clock.tick( 100 );
	assert.strictEqual( called, 0 );
} );

QUnit.test( 'start hidden and become visible', function ( assert ) {
	let called = 0;

	this.mockDocument.hidden = true;
	this.visibleTimeout.set( () => {
		called++;
	}, 0 );
	this.sandbox.clock.tick( 10 );
	assert.strictEqual( called, 0 );

	this.mockDocument.toggleVisibility();
	this.sandbox.clock.tick( 10 );
	assert.strictEqual( called, 1 );
} );

QUnit.test( 'timeout is cumulative', function ( assert ) {
	let called = 0;

	this.visibleTimeout.set( () => {
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
