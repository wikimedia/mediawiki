( function ( mw ) {
	QUnit.module( 'mediawiki.track' );

	QUnit.test( 'track', 1, function ( assert ) {
		var sequence = [];
		mw.trackSubscribe( 'simple', function ( topic, data ) {
			sequence.push( [ topic, data ] );
		} );
		mw.track( 'simple', { key: 1 } );
		mw.track( 'simple', { key: 2 } );

		assert.deepEqual( sequence, [
			[ 'simple', { key: 1 } ],
			[ 'simple', { key: 2 } ]
		], 'Events after subscribing' );
	} );

	QUnit.test( 'trackSubscribe', 4, function ( assert ) {
		var now,
			sequence = [];
		mw.track( 'before', { key: 1 } );
		mw.track( 'before', { key: 2 } );
		mw.trackSubscribe( 'before', function ( topic, data ) {
			sequence.push( [ topic, data ] );
		} );
		mw.track( 'before', { key: 3 } );

		assert.deepEqual( sequence, [
			[ 'before', { key: 1 } ],
			[ 'before', { key: 2 } ],
			[ 'before', { key: 3 } ]
		], 'Replay events from before subscribing' );

		now = mw.now();
		mw.track( 'context', { key: 0 } );
		mw.trackSubscribe( 'context', function ( topic, data ) {
			assert.strictEqual( this.topic, topic, 'thisValue has topic' );
			assert.strictEqual( this.data, data, 'thisValue has data' );
			assert.assertTrue( this.timeStamp >= now, 'thisValue has sane timestamp' );
		} );
	} );
}( mediaWiki ) );
