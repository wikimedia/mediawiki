QUnit.module( 'jquery.getAttrs', QUnit.newMwEnvironment() );

QUnit.test( 'Check', 1, function ( assert ) {
	var	attrs = {
			foo: 'bar',
			'class': 'lorem'
		},
		$el = jQuery( '<div>', attrs );

	assert.deepEqual( $el.getAttrs(), attrs, 'getAttrs() return object should match the attributes set, no more, no less' );
} );
