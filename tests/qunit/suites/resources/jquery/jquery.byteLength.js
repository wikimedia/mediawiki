module( 'jquery.byteLength.js' );

test( '-- Initial check', function() {
	expect(1);
	ok( $.byteLength, 'jQuery.byteLength defined' );
} );

test( 'Simple text', function() {
	expect(5);

	var	azLc = 'abcdefghijklmnopqrstuvwxyz',
		azUc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		num = '0123456789',
		x = '*',
		space = '   ';

	equal( $.byteLength( azLc ), 26, 'Lowercase a-z' );
	equal( $.byteLength( azUc ), 26, 'Uppercase A-Z' );
	equal( $.byteLength( num ), 10, 'Numbers 0-9' );
	equal( $.byteLength( x ), 1, 'An asterisk' );
	equal( $.byteLength( space ), 3, '3 spaces' );

} );

test( 'Special text', window.foo = function() {
	expect(5);

	// http://en.wikipedia.org/wiki/UTF-8 
	var	U_0024 = '\u0024',
		U_00A2 = '\u00A2',
		U_20AC = '\u20AC',
		U_024B62 = '\u024B62',
		// The normal one doesn't display properly, try the below which is the same
		// according to http://www.fileformat.info/info/unicode/char/24B62/index.htm
		U_024B62_alt = '\uD852\uDF62';

	strictEqual( $.byteLength( U_0024 ), 1, 'U+0024: 1 byte. \u0024 (dollar sign)' );
	strictEqual( $.byteLength( U_00A2 ), 2, 'U+00A2: 2 bytes. \u00A2 (cent sign)' );
	strictEqual( $.byteLength( U_20AC ), 3, 'U+20AC: 3 bytes. \u20AC (euro sign)' );
	strictEqual( $.byteLength( U_024B62 ), 4, 'U+024B62: 4 bytes. \uD852\uDF62 (a Han character)' );
	strictEqual( $.byteLength( U_024B62_alt ), 4, 'U+024B62: 4 bytes. \uD852\uDF62 (a Han character) - alternative method' );
} );
