( function ( $ ) {
	QUnit.module( 'jquery.getAttrs', QUnit.newMwEnvironment() );

	QUnit.test( 'getAttrs()', 1, function ( assert ) {
		var attrs = {
				foo: 'bar',
				'class': 'lorem',
				'data-foo': 'data value'
			},
			$el = $( '<div>' ).attr( attrs );

		assert.propEqual( $el.getAttrs(), attrs, 'keys and values match' );
	} );
}( jQuery ) );
