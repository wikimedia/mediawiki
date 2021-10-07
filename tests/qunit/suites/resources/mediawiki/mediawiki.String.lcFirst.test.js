( function () {
	var lcFirst = require( 'mediawiki.String' ).lcFirst;

	QUnit.module( 'mediawiki.String.lcFirst', QUnit.newMwEnvironment() );

	QUnit.test( 'lcFirst', function ( assert ) {
		assert.strictEqual( lcFirst( '' ), '', 'Empty string' );
		assert.strictEqual( lcFirst( '/' ), '/', 'Slash is unchanged' );
		assert.strictEqual( lcFirst( 'AB' ), 'aB', 'Upper case letters' );
		assert.strictEqual( lcFirst( 'ab' ), 'ab', 'Lower case letters' );
		assert.strictEqual( lcFirst( '\uD803' ), '\uD803', 'First surrogate only' );
		assert.strictEqual( lcFirst( '\uD803x' ), '\uD803x', 'First surrogate with char' );
		assert.strictEqual( lcFirst( '\uDC80' ), '\uDC80', 'Second surrogate only' );
		assert.strictEqual( lcFirst( '\uDC80x' ), '\uDC80x', 'Second surrogate with char' );
		assert.strictEqual( lcFirst( '\uD803\uDC80\uD803\uDCC0' ), '\uD803\uDCC0\uD803\uDCC0',
			'U+10C80 (OLD HUNGARIAN CAPITAL LETTER A) -> U+10CC0 (OLD HUNGARIAN SMALL LETTER A)'
		);
	} );
}() );
