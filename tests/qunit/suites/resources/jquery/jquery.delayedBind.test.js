( function ( $ ) {
	QUnit.asyncTest( 'jquery.delayedBind with data option', 2, function ( assert ) {
		var $fixture = $( '<div>' ).appendTo( '#qunit-fixture' ),
			data = {
				magic: 'beeswax'
			},
			delay = 50;

		$fixture.delayedBind( delay, 'testevent', data, function ( e ) {
			assert.ok( true, 'testevent fired' );
			assert.ok( e.data === data, 'data is passed through delayedBind' );
			QUnit.start();
		} );

		// We'll trigger it thrice, but it should only happen once.
		$fixture.trigger( 'testevent', {} );
		$fixture.trigger( 'testevent', {} );
		$fixture.trigger( 'testevent', {} );
		$fixture.trigger( 'testevent', {} );
	} );

	QUnit.asyncTest( 'jquery.delayedBind without data option', 1, function ( assert ) {
		var $fixture = $( '<div>' ).appendTo( '#qunit-fixture' ),
			delay = 50;

		$fixture.delayedBind( delay, 'testevent', function () {
			assert.ok( true, 'testevent fired' );
			QUnit.start();
		} );

		// We'll trigger it thrice, but it should only happen once.
		$fixture.trigger( 'testevent', {} );
		$fixture.trigger( 'testevent', {} );
		$fixture.trigger( 'testevent', {} );
		$fixture.trigger( 'testevent', {} );
	} );
}( jQuery ) );
