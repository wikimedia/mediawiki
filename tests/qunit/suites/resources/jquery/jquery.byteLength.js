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
	expect(4);

	// http://en.wikipedia.org/wiki/UTF-8 
	var	U_0024 = '\u0024',
		U_00A2 = '\u00A2',
		U_20AC = '\u20AC',
		U_024B62 = '\u024B62';

	strictEqual( $.byteLength( U_0024 ), 1, 'U+0024: 1 byte (dollar sign) $' );
	strictEqual( $.byteLength( U_00A2 ), 2, 'U+00A2: 2 bytes (cent sign) &#162;' );
	strictEqual( $.byteLength( U_20AC ), 3, 'U+20AC: 3 bytes (euro sign) &#8364;' );
	strictEqual( $.byteLength( U_024B62 ), 4, 'U+024B62: 4 bytes &#150370; \U00024B62 ' );
} );
