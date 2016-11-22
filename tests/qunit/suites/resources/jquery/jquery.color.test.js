( function ( $ ) {
	QUnit.module( 'jquery.color', QUnit.newMwEnvironment() );

	QUnit.test( 'animate', function ( assert ) {
		var done = assert.async(),
			$canvas = $( '<div>' ).css( 'background-color', '#fff' ).appendTo( '#qunit-fixture' );

		$canvas.animate( { 'background-color': '#000' }, 3 ).promise()
			.done( function () {
				var endColors = $.colorUtil.getRGB( $canvas.css( 'background-color' ) );
				assert.deepEqual( endColors, [ 0, 0, 0 ], 'end state' );
			} )
			.always( done );
	} );
}( jQuery ) );
