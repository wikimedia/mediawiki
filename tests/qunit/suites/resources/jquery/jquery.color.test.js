( function ( $ ) {
	QUnit.module( 'jquery.color', QUnit.newMwEnvironment( {
		setup: function () {
			this.clock = this.sandbox.useFakeTimers();
		}
	} ) );

	QUnit.test( 'animate', 1, function ( assert ) {
		var $canvas = $( '<div>' ).css( 'background-color', '#fff' );

		$canvas.animate( { backgroundColor: '#000' }, 10 ).promise().then( function () {
			var endColors = $.colorUtil.getRGB( $canvas.css( 'background-color' ) );
			assert.deepEqual( endColors, [0, 0, 0], 'end state' );
		} );

		this.clock.tick( 20 );
	} );
}( jQuery ) );
