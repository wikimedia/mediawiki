( function ( $ ) {
	QUnit.module( 'jquery.color', QUnit.newMwEnvironment() );

	QUnit.asyncTest( 'animate', 3, function ( assert ) {
		var $canvas = $( '<div>' ).css( 'background-color', '#fff' );

		$canvas.animate( { backgroundColor: '#000' }, 4 ).promise().then( function() {
			var endColors = $.colorUtil.getRGB( $canvas.css( 'background-color' ) );
			assert.strictEqual( endColors[0], 0 );
                        assert.strictEqual( endColors[1], 0 );
                        assert.strictEqual( endColors[2], 0 );
			QUnit.start();
		} );
	} );
}( jQuery ) );
