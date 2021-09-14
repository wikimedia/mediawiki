( function () {
	var charAt = require( 'mediawiki.String' ).charAt;

	QUnit.module( 'mediawiki.String.charAt', QUnit.newMwEnvironment() );

	QUnit.test( 'Simple text', function ( assert ) {
		var azLc = 'abcdefghijklmnopqrstuvwxyz';

		assert.strictEqual( charAt( azLc, 0 ), 'a', 'First char' );
		assert.strictEqual( charAt( azLc, 25 ), 'z', 'Last char' );
		assert.strictEqual( charAt( azLc, -1 ), '', 'Negative offset' );
		assert.strictEqual( charAt( azLc, 26 ), '', 'Big offset' );
	} );

	QUnit.test( 'UTF-16 text', function ( assert ) {
		assert.strictEqual( charAt( '\uD803\uDC80', 0 ), '\uD803\uDC80', 'U+10C80' );
		assert.strictEqual( charAt( '\uD803', 0 ), '\uD803', 'First surrogate only' );
		assert.strictEqual( charAt( '\uD803x', 0 ), '\uD803', 'First surrogate with char' );
		assert.strictEqual( charAt( '\uD803x', 1 ), 'x', 'Char after first surrogate' );
		assert.strictEqual( charAt( '\uD803\uDC80', 1 ), '\uDC80', 'Second surrogate only' );
		assert.strictEqual( charAt( '\uD803\uDC80x', 1 ), '\uDC80', 'Second surrogate with char' );
		assert.strictEqual( charAt( '\uD803\uDC80x', 2 ), 'x', 'Char after second surrogate' );
	} );
}() );
