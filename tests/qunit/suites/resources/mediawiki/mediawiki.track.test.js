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

	QUnit.test( 'trackSubscribe', 2, function ( assert ) {
		var sequence = [];
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

		mw.track( 'context', { key: 0 } );
		mw.trackSubscribe( 'context', function ( topic, data ) {
			assert.propEqual( {
					topic: this.topic,
					data: this.data,
					// Round timestamp to allow some delay in the test
					timeStamp: Math.round( this.timeStamp / 10000 )
				},
				{
					topic: topic,
					data: data,
					timeStamp: Math.round( mw.now() / 10000 )
				},
				'thisValue has topic, data and timeStamp'
			);
		} );
	} );
}( mediaWiki ) );
