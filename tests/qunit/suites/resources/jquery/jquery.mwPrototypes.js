module( 'jquery.mwPrototypes.js' );

test( 'String functions', function() {

	equal( $.trimLeft( '  foo bar  ' ), 'foo bar  ', 'trimLeft' );
	equal( $.trimRight( '  foo bar  ' ), '  foo bar', 'trimRight' );
	equal( $.ucFirst( 'foo'), 'Foo', 'ucFirst' );

	equal( $.escapeRE( '<!-- ([{+mW+}]) $^|?>' ),
	 '<!\\-\\- \\(\\[\\{\\+mW\\+\\}\\]\\) \\$\\^\\|\\?>', 'escapeRE - Escape specials' );
	equal( $.escapeRE( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' ),
	 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'escapeRE - Leave uppercase alone' );
	equal( $.escapeRE( 'abcdefghijklmnopqrstuvwxyz' ),
	 'abcdefghijklmnopqrstuvwxyz', 'escapeRE - Leave lowercase alone' );
	equal( $.escapeRE( '0123456789' ), '0123456789', 'escapeRE - Leave numbers alone' );
});

test( 'Is functions', function() {

	strictEqual( $.isDomElement( document.getElementById( 'qunit-header' ) ), true,
	 'isDomElement: #qunit-header Node' );
	strictEqual( $.isDomElement( document.getElementById( 'random-name' ) ), false,
	 'isDomElement: #random-name (null)' );
	strictEqual( $.isDomElement( document.getElementsByTagName( 'div' ) ), false,
	 'isDomElement: getElementsByTagName Array' );
	strictEqual( $.isDomElement( document.getElementsByTagName( 'div' )[0] ), true,
	 'isDomElement: getElementsByTagName(..)[0] Node' );
	strictEqual( $.isDomElement( $( 'div' ) ), false,
	 'isDomElement: jQuery object' );
	strictEqual( $.isDomElement( $( 'div' ).get(0) ), true,
	 'isDomElement: jQuery object > Get node' );
	strictEqual( $.isDomElement( document.createElement( 'div' ) ), true,
	 'isDomElement: createElement' );
	strictEqual( $.isDomElement( { foo: 1 } ), false,
	 'isDomElement: Object' );

	strictEqual( $.isEmpty( 'string' ), false, 'isEmptry: "string"' );
	strictEqual( $.isEmpty( '0' ), true, 'isEmptry: "0"' );
	strictEqual( $.isEmpty( [] ), true, 'isEmptry: []' );
	strictEqual( $.isEmpty( {} ), true, 'isEmptry: {}' );

	// Documented behaviour
	strictEqual( $.isEmpty( { length: 0 } ), true, 'isEmptry: { length: 0 }' );
});

test( 'Comparison functions', function() {

	ok( $.compareArray( [0, 'a', [], [2, 'b'] ], [0, "a", [], [2, "b"] ] ),
	 'compareArray: Two deep arrays that are excactly the same' );
	ok( !$.compareArray( [1], [2] ), 'compareArray: Two different arrays (false)' );

	ok( $.compareObject( {}, {} ), 'compareObject: Two empty objects' );
	ok( $.compareObject( { foo: 1 }, { foo: 1 } ), 'compareObject: Two the same objects' );
	ok( !$.compareObject( { bar: true }, { baz: false } ),
	 'compareObject: Two different objects (false)' );
});
