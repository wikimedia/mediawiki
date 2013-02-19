( function ( $ ) {
	QUnit.module( 'jquery.hidpi', QUnit.newMwEnvironment() );

	QUnit.test( 'devicePixelRatio', 1, function ( assert ) {
		var devicePixelRatio = $.devicePixelRatio();
		assert.equal( typeof devicePixelRatio, 'number', '$.devicePixelRatio() returns a number' );
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
