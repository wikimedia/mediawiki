( function ( $ ) {
	QUnit.module( 'jquery.hidpi', QUnit.newMwEnvironment() );

	QUnit.test( 'devicePixelRatio', 1, function ( assert ) {
		var devicePixelRatio = $.devicePixelRatio();
		assert.equal( typeof devicePixelRatio, 'number', '$.devicePixelRatio() returns a number' );
	} );

	QUnit.test( 'bracketedDevicePixelRatio', 1, function ( assert ) {
		var devicePixelRatio = $.devicePixelRatio();
		assert.equal( typeof devicePixelRatio, 'number', '$.bracketedDevicePixelRatio() returns a number' );
	} );

	QUnit.test( 'bracketDevicePixelRatio', 8, function ( assert ) {
		assert.equal( $.bracketDevicePixelRatio( 0.75 ), 1, '0.75 gives 1' );
		assert.equal( $.bracketDevicePixelRatio( 1 ), 1, '1 gives 1' );
		assert.equal( $.bracketDevicePixelRatio( 1.25 ), 1.5, '1.25 gives 1.5' );
		assert.equal( $.bracketDevicePixelRatio( 1.5 ), 1.5, '1.5 gives 1.5' );
		assert.equal( $.bracketDevicePixelRatio( 1.75 ), 2, '1.75 gives 2' );
		assert.equal( $.bracketDevicePixelRatio( 2 ), 2, '2 gives 2' );
		assert.equal( $.bracketDevicePixelRatio( 2.5 ), 2, '2.5 gives 2' );
		assert.equal( $.bracketDevicePixelRatio( 3 ), 2, '3 gives 2' );
	} );

	QUnit.test( 'matchSrcSet', 6, function ( assert ) {
		var srcset = 'onefive.png 1.5x, two.png 2x';

		// Nice exact matches
		assert.equal( $.matchSrcSet( 1, srcset ), null, '1.0 gives no match' );
		assert.equal( $.matchSrcSet( 1.5, srcset ), 'onefive.png', '1.5 gives match' );
		assert.equal( $.matchSrcSet( 2, srcset ), 'two.png', '2 gives match' );

		// Non-exact matches; should return the next-biggest specified
		assert.equal( $.matchSrcSet( 1.25, srcset ), null, '1.25 gives no match' );
		assert.equal( $.matchSrcSet( 1.75, srcset ), 'onefive.png', '1.75 gives match to 1.5' );
		assert.equal( $.matchSrcSet( 2.25, srcset ), 'two.png', '2.25 gives match to 2' );
	} );
}( jQuery ) );
