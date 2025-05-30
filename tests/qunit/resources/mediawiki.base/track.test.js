QUnit.module( 'mediawiki.base/track', () => {

	QUnit.test( 'track', ( assert ) => {
		const sequence = [];
		mw.trackSubscribe( 'test.single', ( topic, data ) => {
			sequence.push( [ topic, data ] );
		} );
		mw.track( 'test.single', { key: 1 } );
		mw.track( 'test.single', { key: 2 } );

		assert.deepEqual( sequence, [
			[ 'test.single', { key: 1 } ],
			[ 'test.single', { key: 2 } ]
		], 'Events after subscribing' );

		sequence.length = 0;
		mw.trackSubscribe( 'test.multi', ( topic, num, options = {} ) => {
			sequence.push( [ num, options ] );
		} );
		mw.track( 'test.multi', 42 );
		mw.track( 'test.multi', 12, { name: 'Foo' } );

		assert.deepEqual( sequence, [
			[ 42, {} ],
			[ 12, { name: 'Foo' } ]
		] );
	} );

	QUnit.test( 'trackSubscribe', ( assert ) => {
		const sequence = [];
		let thisValue;
		mw.track( 'before', { key: 1 } );
		mw.track( 'before', { key: 2 } );
		mw.trackSubscribe( 'before', function ( topic, data ) {
			'use strict';
			sequence.push( [ topic, data ] );
			thisValue = this;
		} );
		mw.track( 'before', { key: 3 } );

		assert.deepEqual( sequence, [
			[ 'before', { key: 1 } ],
			[ 'before', { key: 2 } ],
			[ 'before', { key: 3 } ]
		], 'Replay events from before subscribing' );

		assert.strictEqual( thisValue, undefined, 'thisValue' );
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
			if ( typeof data !== 'string' ) {
				// eslint-disable-next-line no-console
				console.error( 'trackError test: unexpected non-string data', data );
				data = JSON.stringify( data );
			}
			assert.step( data );
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
