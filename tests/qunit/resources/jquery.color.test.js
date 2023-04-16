QUnit.module( 'jquery.color', () => {

	QUnit.test( 'animate', async ( assert ) => {
		const $canvas = $( '<div>' )
			.css( 'background-color', '#fff' )
			.appendTo( '#qunit-fixture' );

		// eslint-disable-next-line no-jquery/no-animate
		await $canvas.animate( { 'background-color': '#000' }, 3 ).promise();

		const endColors = $.colorUtil.getRGB( $canvas.css( 'background-color' ) );
		assert.deepEqual( endColors, [ 0, 0, 0 ], 'end state' );
	} );

} );
