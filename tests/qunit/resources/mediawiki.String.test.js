QUnit.module( 'mediawiki.String', () => {
	const { byteLength } = require( 'mediawiki.String' );

	QUnit.test.each( 'byteLength()', {
		// Simple cases
		'lowercase a-z': [ 'abcdefghijklmnopqrstuvwxyz', 26 ],
		'uppercase A-Z': [ 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 26 ],
		'numbers 0-9': [ '0123456789', 10 ],
		'an asterisk': [ '*', 1 ],
		'3 spaces': [ '   ', 3 ],
		// Special text
		// https://en.wikipedia.org/wiki/UTF-8
		'U+0024 Dollar': [ '$', 1 ],
		'U+00A2 Cent': [ '\u00A2', 2 ],
		'U+20AC Euro': [ '\u20AC', 3 ],
		// Character U+24B62 (Han script) can't be represented in JavaScript as a single
		// code point, instead it is composed as a surrogate pair of two separate code units.
		// https://codepoints.net/U+24B62
		// https://www.fileformat.info/info/unicode/char/24B62/index.htm
		'U+24B62 Han surrogate': [ '\uD852\uDF62', 4 ]
	}, function ( assert, [ input, expected ] ) {
		assert.strictEqual( byteLength( input ), expected );
	} );

	const { charAt } = require( 'mediawiki.String' );

	QUnit.test( 'charAt() [simple]', function ( assert ) {
		var azLc = 'abcdefghijklmnopqrstuvwxyz';

		assert.strictEqual( charAt( azLc, 0 ), 'a', 'First char' );
		assert.strictEqual( charAt( azLc, 25 ), 'z', 'Last char' );
		assert.strictEqual( charAt( azLc, -1 ), '', 'Negative offset' );
		assert.strictEqual( charAt( azLc, 26 ), '', 'Big offset' );
	} );

	QUnit.test( 'charAt() UTF-16 text', function ( assert ) {
		assert.strictEqual( charAt( '\uD803\uDC80', 0 ), '\uD803\uDC80', 'U+10C80' );
		assert.strictEqual( charAt( '\uD803', 0 ), '\uD803', 'First surrogate only' );
		assert.strictEqual( charAt( '\uD803x', 0 ), '\uD803', 'First surrogate with char' );
		assert.strictEqual( charAt( '\uD803x', 1 ), 'x', 'Char after first surrogate' );
		assert.strictEqual( charAt( '\uD803\uDC80', 1 ), '\uDC80', 'Second surrogate only' );
		assert.strictEqual( charAt( '\uD803\uDC80x', 1 ), '\uDC80', 'Second surrogate with char' );
		assert.strictEqual( charAt( '\uD803\uDC80x', 2 ), 'x', 'Char after second surrogate' );
	} );

	const { lcFirst } = require( 'mediawiki.String' );

	QUnit.test.each( 'lcFirst()', {
		'empty string': [ '', '' ],
		'slash is unchanged': [ '/', '/' ],
		'ASCII uppercase': [ 'AB', 'aB' ],
		'ASCII lowercase unchanged': [ 'ab', 'ab' ],
		'first surrogate only': [ '\uD803', '\uD803' ],
		'first surrogate with char': [ '\uD803x', '\uD803x' ],
		'second surrogate only': [ '\uDC80', '\uDC80' ],
		'second surrogate with char': [ '\uDC80x', '\uDC80x' ],
		'from U+10C80 (Old Hungarian capital A) to U+10CC0 (Old Hungarian small A)': [
			'\uD803\uDC80\uD803\uDCC0',
			'\uD803\uDCC0\uD803\uDCC0'
		]
	}, function ( assert, [ input, expected ] ) {
		assert.strictEqual( lcFirst( input ), expected );
	} );

	const { ucFirst } = require( 'mediawiki.String' );

	QUnit.test.each( 'ucFirst()', {
		'empty string': [ '', '' ],
		'slash is unchanged': [ '/', '/' ],
		'ASCII uppercase unchanged': [ 'AB', 'AB' ],
		'ASCII lowercase': [ 'ab', 'Ab' ],
		'first surrogate only': [ '\uD803', '\uD803' ],
		'first surrogate with char': [ '\uD803x', '\uD803x' ],
		'second surrogate only': [ '\uDC80', '\uDC80' ],
		'second surrogate with char': [ '\uDC80x', '\uDC80x' ],
		'from U+10CC0 (Old Hungarian small A) to U+10C80 (Old Hungarian capital A)': [
			'\uD803\uDCC0\uD803\uDCC0',
			'\uD803\uDC80\uD803\uDCC0'
		]
	}, function ( assert, [ input, expected ] ) {
		assert.strictEqual( ucFirst( input ), expected );
	} );
} );
