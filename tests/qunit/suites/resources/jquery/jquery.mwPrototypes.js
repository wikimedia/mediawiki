module( 'jquery.mwPrototypes.js' );

test( 'String functions', function(){

	equal( $j.trimLeft( '  foo bar  ' ), 'foo bar  ', 'trimLeft' );
	equal( $j.trimRight( '  foo bar  ' ), '  foo bar', 'trimRight' );
	equal( $j.ucFirst( 'foo'), 'Foo', 'ucFirst' );

	equal( $j.escapeRE( '<!-- ([{+mW+}]) $^|?>' ),
	 '<!\\-\\- \\(\\[\\{\\+mW\\+\\}\\]\\) \\$\\^\\|\\?>', 'escapeRE - Escape specials' );
	equal( $j.escapeRE( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' ),
	 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'escapeRE - Leave uppercase alone' );
	equal( $j.escapeRE( 'abcdefghijklmnopqrstuvwxyz' ),
	 'abcdefghijklmnopqrstuvwxyz', 'escapeRE - Leave lowercase alone' );
	equal( $j.escapeRE( '0123456789' ), '0123456789', 'escapeRE - Leave numbers alone' );

});

test( 'Is functions', function(){

	deepEqual( $j.isDomElement( document.getElementById( 'qunit-header' ) ), true,
	 'isDomElement: #qunit-header Node' );
	deepEqual( $j.isDomElement( document.getElementById( 'random-name' ) ), false,
	 'isDomElement: #random-name (null)' );
	deepEqual( $j.isDomElement( document.getElementsByTagName( 'div' ) ), false,
	 'isDomElement: getElementsByTagName Array' );
	deepEqual( $j.isDomElement( document.getElementsByTagName( 'div' )[0] ), true,
	 'isDomElement: getElementsByTagName(..)[0] Node' );
	deepEqual( $j.isDomElement( $j( 'div' ) ), false,
	 'isDomElement: jQuery object' );
	deepEqual( $j.isDomElement( $j( 'div' ).get(0) ), true,
	 'isDomElement: jQuery object > Get node' );
	deepEqual( $j.isDomElement( document.createElement( 'div' ) ), true,
	 'isDomElement: createElement' );
	deepEqual( $j.isDomElement( { foo: 1 } ), false,
	 'isDomElement: Object' );

	equal( $j.isEmpty( 'string' ), false, 'isEmptry: "string"' );
	equal( $j.isEmpty( '0' ), true, 'isEmptry: "0"' );
	equal( $j.isEmpty( [] ), true, 'isEmptry: []' );
	equal( $j.isEmpty( {} ), true, 'isEmptry: {}' );
	// Documented behaviour
	equal( $j.isEmpty( { length: 0 } ), true, 'isEmptry: { length: 0 }' );

});

test( 'Comparison functions', function(){

	ok( $j.compareArray( [0, 'a', [], [2, 'b'] ], [0, "a", [], [2, "b"] ] ),
	 'compareArray: Two deep arrays that are excactly the same' );
	ok( !$j.compareArray( [1], [2] ), 'compareArray: Two different arrays (false)' );

	ok( $j.compareObject( {}, {} ), 'compareObject: Two empty objects' );
	ok( $j.compareObject( { foo: 1 }, { foo: 1 } ), 'compareObject: Two the same objects' );
	ok( !$j.compareObject( { bar: true }, { baz: false } ),
	 'compareObject: Two different objects (false)' );

});