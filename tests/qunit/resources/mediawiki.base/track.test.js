QUnit.module( 'mediawiki.base/track', () => {

	QUnit.test( 'track', ( assert ) => {
		const sequence = [];
		mw.trackSubscribe( 'simple', ( topic, data ) => {
			sequence.push( [ topic, data ] );
		} );
		mw.track( 'simple', { key: 1 } );
		mw.track( 'simple', { key: 2 } );

		assert.deepEqual( sequence, [
			[ 'simple', { key: 1 } ],
			[ 'simple', { key: 2 } ]
		], 'Events after subscribing' );
	} );

	QUnit.test( 'trackSubscribe', ( assert ) => {
		const sequence = [];
		mw.track( 'before', { key: 1 } );
		mw.track( 'before', { key: 2 } );
		mw.trackSubscribe( 'before', ( topic, data ) => {
			sequence.push( [ topic, data ] );
		} );
		mw.track( 'before', { key: 3 } );

		assert.deepEqual( sequence, [
			[ 'before', { key: 1 } ],
			[ 'before', { key: 2 } ],
			[ 'before', { key: 3 } ]
		], 'Replay events from before subscribing' );

		mw.track( 'context', { key: 0 } );
		mw.trackSubscribe( 'context', function ( topic, data ) {
			assert.strictEqual( this.topic, topic, 'thisValue has topic' );
			assert.strictEqual( this.data, data, 'thisValue has data' );
		} );
	} );

	QUnit.test( 'trackUnsubscribe', ( assert ) => {
		const sequence = [];
		function unsubber( topic, data ) {
			sequence.push( [ topic, data ] );
		}

		mw.track( 'unsub', { key: 1 } );
		mw.trackSubscribe( 'unsub', unsubber );
		mw.track( 'unsub', { key: 2 } );
		mw.trackUnsubscribe( unsubber );
		mw.track( 'unsub', { key: 3 } );

		assert.deepEqual( sequence, [
			[ 'unsub', { key: 1 } ],
			[ 'unsub', { key: 2 } ]
		], 'Stop when unsubscribing' );
	} );

	QUnit.test( 'trackError', function ( assert ) {
		const fn = mw.track;
		function logError( topic, data ) {
			assert.step( typeof data === 'string' ? data : JSON.stringify( data ) );
		}
		this.sandbox.stub( console, 'log' );

		// Simulate startup time
		mw.track = undefined;

		assert.step( 'emit1' );
		mw.trackError( 'foo' );

		// Simulate mediawiki.base arriving
		mw.track = fn;

		assert.step( 'sub' );
		mw.trackSubscribe( 'resourceloader.exception', logError );

		assert.step( 'emit2' );
		mw.trackError( 'bar' );

		assert.verifySteps( [
			'emit1',
			'sub',
			'foo',
			'emit2',
			'bar'
		] );

		// Teardown
		mw.trackUnsubscribe( logError );
	} );
} );
