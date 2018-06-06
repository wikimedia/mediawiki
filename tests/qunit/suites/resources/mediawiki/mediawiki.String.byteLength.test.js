( function () {
	var byteLength = require( 'mediawiki.String' ).byteLength;

	QUnit.module( 'mediawiki.String.byteLength', QUnit.newMwEnvironment() );

	QUnit.test( 'Simple text', function ( assert ) {
		var azLc = 'abcdefghijklmnopqrstuvwxyz',
			azUc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			num = '0123456789',
			x = '*',
			space = '   ';

		assert.strictEqual( byteLength( azLc ), 26, 'Lowercase a-z' );
		assert.strictEqual( byteLength( azUc ), 26, 'Uppercase A-Z' );
		assert.strictEqual( byteLength( num ), 10, 'Numbers 0-9' );
		assert.strictEqual( byteLength( x ), 1, 'An asterisk' );
		assert.strictEqual( byteLength( space ), 3, '3 spaces' );

	} );

	QUnit.test( 'Special text', function ( assert ) {
		// https://en.wikipedia.org/wiki/UTF-8
		var u0024 = '$',
			// Cent symbol
			u00A2 = '\u00A2',
			// Euro symbol
			u20AC = '\u20AC',
			// Character \U00024B62 (Han script) can't be represented in javascript as a single
			// code point, instead it is composed as a surrogate pair of two separate code units.
			// http://codepoints.net/U+24B62
			// http://www.fileformat.info/info/unicode/char/24B62/index.htm
			u024B62 = '\uD852\uDF62';

		assert.strictEqual( byteLength( u0024 ), 1, 'U+0024' );
		assert.strictEqual( byteLength( u00A2 ), 2, 'U+00A2' );
		assert.strictEqual( byteLength( u20AC ), 3, 'U+20AC' );
		assert.strictEqual( byteLength( u024B62 ), 4, 'U+024B62 (surrogate pair: \\uD852\\uDF62)' );
	} );
}() );
