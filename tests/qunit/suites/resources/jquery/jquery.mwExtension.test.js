( function ( $ ) {
	QUnit.module( 'jquery.mwExtension', QUnit.newMwEnvironment() );

	QUnit.test( 'String functions', 7, function ( assert ) {
		assert.equal( $.trimLeft( '  foo bar  ' ), 'foo bar  ', 'trimLeft' );
		assert.equal( $.trimRight( '  foo bar  ' ), '  foo bar', 'trimRight' );
		assert.equal( $.ucFirst( 'foo' ), 'Foo', 'ucFirst' );

		assert.equal( $.escapeRE( '<!-- ([{+mW+}]) $^|?>' ),
			'<!\\-\\- \\(\\[\\{\\+mW\\+\\}\\]\\) \\$\\^\\|\\?>', 'escapeRE - Escape specials' );
		assert.equal( $.escapeRE( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' ),
			'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'escapeRE - Leave uppercase alone' );
		assert.equal( $.escapeRE( 'abcdefghijklmnopqrstuvwxyz' ),
			'abcdefghijklmnopqrstuvwxyz', 'escapeRE - Leave lowercase alone' );
		assert.equal( $.escapeRE( '0123456789' ), '0123456789', 'escapeRE - Leave numbers alone' );
	} );

	QUnit.test( 'isDomElement', 6, function ( assert ) {
		assert.strictEqual( $.isDomElement( document.createElement( 'div' ) ), true,
			'isDomElement: HTMLElement' );
		assert.strictEqual( $.isDomElement( document.createTextNode( '' ) ), true,
			'isDomElement: TextNode' );
		assert.strictEqual( $.isDomElement( null ), false,
			'isDomElement: null' );
		assert.strictEqual( $.isDomElement( document.getElementsByTagName( 'div' ) ), false,
			'isDomElement: NodeList' );
		assert.strictEqual( $.isDomElement( $( 'div' ) ), false,
			'isDomElement: jQuery' );
		assert.strictEqual( $.isDomElement( { foo: 1 } ), false,
			'isDomElement: Plain Object' );
	} );

	QUnit.test( 'isEmpty', 7, function ( assert ) {
		assert.strictEqual( $.isEmpty( 'string' ), false, 'isEmpty: "string"' );
		assert.strictEqual( $.isEmpty( '0' ), true, 'isEmpty: "0"' );
		assert.strictEqual( $.isEmpty( '' ), true, 'isEmpty: ""' );
		assert.strictEqual( $.isEmpty( 1 ), false, 'isEmpty: 1' );
		assert.strictEqual( $.isEmpty( [] ), true, 'isEmpty: []' );
		assert.strictEqual( $.isEmpty( {} ), true, 'isEmpty: {}' );

		// Documented behavior
		assert.strictEqual( $.isEmpty( { length: 0 } ), true, 'isEmpty: { length: 0 }' );
	} );

	QUnit.test( 'Comparison functions', 5, function ( assert ) {
		assert.ok( $.compareArray( [0, 'a', [], [2, 'b'] ], [0, 'a', [], [2, 'b'] ] ),
			'compareArray: Two deep arrays that are excactly the same' );
		assert.ok( !$.compareArray( [1], [2] ), 'compareArray: Two different arrays (false)' );

		assert.ok( $.compareObject( {}, {} ), 'compareObject: Two empty objects' );
		assert.ok( $.compareObject( { foo: 1 }, { foo: 1 } ), 'compareObject: Two the same objects' );
		assert.ok( !$.compareObject( { bar: true }, { baz: false } ),
			'compareObject: Two different objects (false)' );
	} );
}( jQuery ) );
