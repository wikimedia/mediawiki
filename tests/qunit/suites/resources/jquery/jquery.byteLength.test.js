( function ( $ ) {
	QUnit.module( 'jquery.byteLength', QUnit.newMwEnvironment() );

	QUnit.test( 'Simple text', 5, function ( assert ) {
		var azLc = 'abcdefghijklmnopqrstuvwxyz',
			azUc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			num = '0123456789',
			x = '*',
			space = '   ';

		assert.equal( $.byteLength( azLc ), 26, 'Lowercase a-z' );
		assert.equal( $.byteLength( azUc ), 26, 'Uppercase A-Z' );
		assert.equal( $.byteLength( num ), 10, 'Numbers 0-9' );
		assert.equal( $.byteLength( x ), 1, 'An asterisk' );
		assert.equal( $.byteLength( space ), 3, '3 spaces' );

	} );

	QUnit.test( 'Special text', 5, function ( assert ) {
		// http://en.wikipedia.org/wiki/UTF-8
		var u0024 = '$',
			u00A2 = '\u00A2',
			u20AC = '\u20AC',
			u024B62 = '\u024B62',
			// The normal one doesn't display properly, try the below which is the same
			// according to http://www.fileformat.info/info/unicode/char/24B62/index.htm
			u024B62alt = '\uD852\uDF62';

		assert.strictEqual( $.byteLength( u0024 ), 1, 'U+0024: 1 byte. $ (dollar sign)' );
		assert.strictEqual( $.byteLength( u00A2 ), 2, 'U+00A2: 2 bytes. \u00A2 (cent sign)' );
		assert.strictEqual( $.byteLength( u20AC ), 3, 'U+20AC: 3 bytes. \u20AC (euro sign)' );
		assert.strictEqual( $.byteLength( u024B62 ), 4, 'U+024B62: 4 bytes. \uD852\uDF62 (a Han character)' );
		assert.strictEqual( $.byteLength( u024B62alt ), 4, 'U+024B62: 4 bytes. \uD852\uDF62 (a Han character) - alternative method' );
	} );
}( jQuery ) );
