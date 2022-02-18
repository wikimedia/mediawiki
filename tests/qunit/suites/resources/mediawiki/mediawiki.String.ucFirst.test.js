( function () {
	var ucFirst = require( 'mediawiki.String' ).ucFirst;

	QUnit.module( 'mediawiki.String.ucFirst', QUnit.newMwEnvironment() );

	QUnit.test( 'ucFirst', function ( assert ) {
		assert.strictEqual( ucFirst( '' ), '', 'Empty string' );
		assert.strictEqual( ucFirst( '/' ), '/', 'Slash is unchanged' );
		assert.strictEqual( ucFirst( 'AB' ), 'AB', 'Upper case letters' );
		assert.strictEqual( ucFirst( 'ab' ), 'Ab', 'Lower case letters' );
		assert.strictEqual( ucFirst( '\uD803' ), '\uD803', 'First surrogate only' );
		assert.strictEqual( ucFirst( '\uD803x' ), '\uD803x', 'First surrogate with char' );
		assert.strictEqual( ucFirst( '\uDC80' ), '\uDC80', 'Second surrogate only' );
		assert.strictEqual( ucFirst( '\uDC80x' ), '\uDC80x', 'Second surrogate with char' );
		assert.strictEqual( ucFirst( '\uD803\uDCC0\uD803\uDCC0' ), '\uD803\uDC80\uD803\uDCC0',
			'U+10CC0 (OLD HUNGARIAN SMALL LETTER A) -> U+10C80 (OLD HUNGARIAN CAPITAL LETTER A)'
		);
	} );
}() );
