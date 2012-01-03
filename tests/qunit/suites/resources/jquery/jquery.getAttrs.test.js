module( 'jquery.getAttrs', QUnit.newMwEnvironment() );

test( '-- Initial check', function() {
	expect(1);
	ok( $.fn.getAttrs, 'jQuery.fn.getAttrs defined' );
} );

test( 'Check', function() {
	expect(1);
	var	attrs = {
			foo: 'bar',
			'class': 'lorem'
		},
		$el = $( '<div>', attrs );

	deepEqual( $el.getAttrs(), attrs, 'getAttrs() return object should match the attributes set, no more, no less' );
} );
