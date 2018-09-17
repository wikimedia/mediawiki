( function () {
	var simpleSample, U_20AC, poop, mbSample,
		trimByteLength = require( 'mediawiki.String' ).trimByteLength;

	QUnit.module( 'mediawiki.String.trimByteLength', QUnit.newMwEnvironment() );

	// Simple sample (20 chars, 20 bytes)
	simpleSample = '12345678901234567890';

	// 3 bytes (euro-symbol)
	U_20AC = '\u20AC';

	// Outside of the BMP (pile of poo emoji)
	poop = '\uD83D\uDCA9'; // "💩"

	// Multi-byte sample (22 chars, 26 bytes)
	mbSample = '1234567890' + U_20AC + '1234567890' + U_20AC;

	/**
	 * Test factory for mw.String#trimByteLength
	 *
	 * @param {Object} options
	 * @param {string} options.description Test name
	 * @param {string} options.sample Sequence of characters to trim
	 * @param {string} [options.initial] Previous value of the sequence of characters, if any
	 * @param {Number} options.limit Length to trim to
	 * @param {Function} [options.fn] Filter function
	 * @param {string} options.expected Expected final value
	 */
	function byteLimitTest( options ) {
		var opt = $.extend( {
			description: '',
			sample: '',
			initial: '',
			limit: 0,
			fn: function ( a ) { return a; },
			expected: ''
		}, options );

		QUnit.test( opt.description, function ( assert ) {
			var res = trimByteLength( opt.initial, opt.sample, opt.limit, opt.fn );

			assert.strictEqual(
				res.newVal,
				opt.expected,
				'New value matches the expected string'
			);
		} );
	}

	byteLimitTest( {
		description: 'Limit using the maxlength attribute',
		limit: 10,
		sample: simpleSample,
		expected: '1234567890'
	} );

	byteLimitTest( {
		description: 'Limit using a custom value (multibyte)',
		limit: 14,
		sample: mbSample,
		expected: '1234567890' + U_20AC + '1'
	} );

	byteLimitTest( {
		description: 'Limit using a custom value (multibyte, outside BMP)',
		limit: 3,
		sample: poop,
		expected: ''
	} );

	byteLimitTest( {
		description: 'Limit using a custom value (multibyte) overlapping a byte',
		limit: 12,
		sample: mbSample,
		expected: '1234567890'
	} );

	byteLimitTest( {
		description: 'Pass the limit and a callback as input filter',
		limit: 6,
		fn: function ( val ) {
			var title = mw.Title.newFromText( String( val ) );
			// Return without namespace prefix
			return title ? title.getMain() : '';
		},
		sample: 'User:Sample',
		expected: 'User:Sample'
	} );

	byteLimitTest( {
		description: 'Pass the limit and a callback as input filter',
		limit: 6,
		fn: function ( val ) {
			var title = mw.Title.newFromText( String( val ) );
			// Return without namespace prefix
			return title ? title.getMain() : '';
		},
		sample: 'User:Example',
		// The callback alters the value to be used to calculeate
		// the length. The altered value is "Exampl" which has
		// a length of 6, the "e" would exceed the limit.
		expected: 'User:Exampl'
	} );

	byteLimitTest( {
		description: 'Input filter that increases the length',
		limit: 10,
		fn: function ( text ) {
			return 'prefix' + text;
		},
		sample: simpleSample,
		// Prefix adds 6 characters, limit is reached after 4
		expected: '1234'
	} );

	byteLimitTest( {
		description: 'Trim from insertion when limit exceeded',
		limit: 3,
		initial: 'abc',
		sample: 'zabc',
		// Trim from the insertion point (at 0), not the end
		expected: 'abc'
	} );

	byteLimitTest( {
		description: 'Trim from insertion when limit exceeded',
		limit: 3,
		initial: 'abc',
		sample: 'azbc',
		// Trim from the insertion point (at 1), not the end
		expected: 'abc'
	} );

	byteLimitTest( {
		description: 'Do not cut up false matching substrings in emoji insertions',
		limit: 12,
		initial: '\uD83D\uDCA9\uD83D\uDCA9', // "💩💩"
		sample: '\uD83D\uDCA9\uD83D\uDCB9\uD83E\uDCA9\uD83D\uDCA9', // "💩💹🢩💩"
		expected: '\uD83D\uDCA9\uD83D\uDCB9\uD83D\uDCA9' // "💩💹💩"
	} );

	byteLimitTest( {
		description: 'Unpaired surrogates do not crash',
		limit: 4,
		sample: '\uD800\uD800\uDFFF',
		expected: '\uD800'
	} );

}() );
