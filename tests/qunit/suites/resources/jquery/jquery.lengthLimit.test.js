( function ( $, mw ) {
	var simpleSample, U_20AC, poop, mbSample;

	QUnit.module( 'jquery.lengthLimit', QUnit.newMwEnvironment() );

	// Simple sample (20 chars, 20 bytes)
	simpleSample = '12345678901234567890';

	// 3 bytes (euro-symbol)
	U_20AC = '\u20AC';

	// Outside of the BMP (pile of poo emoji)
	poop = '\uD83D\uDCA9'; // "💩"

	// Multi-byte sample (22 chars, 26 bytes)
	mbSample = '1234567890' + U_20AC + '1234567890' + U_20AC;

	// Basic sendkey-implementation
	function addChars( $input, charstr ) {
		var c, len;

		function x( $input, i ) {
			// Add character to the value
			return $input.val() + charstr.charAt( i );
		}

		for ( c = 0, len = charstr.length; c < len; c += 1 ) {
			$input
				.val( x( $input, c ) )
				.trigger( 'change' );
		}
	}

	/**
	 * Test factory for $.fn.byteLimit
	 *
	 * @param {Object} options
	 * @param {string} options.description Test name
	 * @param {jQuery} options.$input jQuery object in an input element
	 * @param {string} options.sample Sequence of characters to simulate being
	 *  added one by one
	 * @param {string} options.expected Expected final value of `$input`
	 */
	function byteLimitTest( options ) {
		var opt = $.extend( {
			description: '',
			$input: null,
			sample: '',
			expected: ''
		}, options );

		QUnit.test( opt.description, function ( assert ) {
			opt.$input.appendTo( '#qunit-fixture' );

			// Simulate pressing keys for each of the sample characters
			addChars( opt.$input, opt.sample );

			assert.equal(
				opt.$input.val(),
				opt.expected,
				'New value matches the expected string'
			);
		} );
	}

	byteLimitTest( {
		description: 'Plain text input',
		$input: $( '<input>' ).attr( 'type', 'text' ),
		sample: simpleSample,
		expected: simpleSample
	} );

	byteLimitTest( {
		description: 'Plain text input. Calling byteLimit with no parameters and no maxlength attribute (T38310)',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.byteLimit(),
		sample: simpleSample,
		expected: simpleSample
	} );

	byteLimitTest( {
		description: 'Limit using the maxlength attribute',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.attr( 'maxlength', '10' )
			.byteLimit(),
		sample: simpleSample,
		expected: '1234567890'
	} );

	byteLimitTest( {
		description: 'Limit using a custom value',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.byteLimit( 10 ),
		sample: simpleSample,
		expected: '1234567890'
	} );

	byteLimitTest( {
		description: 'Limit using a custom value, overriding maxlength attribute',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.attr( 'maxlength', '10' )
			.byteLimit( 15 ),
		sample: simpleSample,
		expected: '123456789012345'
	} );

	byteLimitTest( {
		description: 'Limit using a custom value (multibyte)',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.byteLimit( 14 ),
		sample: mbSample,
		expected: '1234567890' + U_20AC + '1'
	} );

	byteLimitTest( {
		description: 'Limit using a custom value (multibyte, outside BMP)',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.byteLimit( 3 ),
		sample: poop,
		expected: ''
	} );

	byteLimitTest( {
		description: 'Limit using a custom value (multibyte) overlapping a byte',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.byteLimit( 12 ),
		sample: mbSample,
		expected: '123456789012'
	} );

	byteLimitTest( {
		description: 'Pass the limit and a callback as input filter',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.byteLimit( 6, function ( val ) {
				var title = mw.Title.newFromText( String( val ) );
				// Return without namespace prefix
				return title ? title.getMain() : '';
			} ),
		sample: 'User:Sample',
		expected: 'User:Sample'
	} );

	byteLimitTest( {
		description: 'Limit using the maxlength attribute and pass a callback as input filter',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.attr( 'maxlength', '6' )
			.byteLimit( function ( val ) {
				var title = mw.Title.newFromText( String( val ) );
				// Return without namespace prefix
				return title ? title.getMain() : '';
			} ),
		sample: 'User:Sample',
		expected: 'User:Sample'
	} );

	byteLimitTest( {
		description: 'Pass the limit and a callback as input filter',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.byteLimit( 6, function ( val ) {
				var title = mw.Title.newFromText( String( val ) );
				// Return without namespace prefix
				return title ? title.getMain() : '';
			} ),
		sample: 'User:Example',
		// The callback alters the value to be used to calculeate
		// the length. The altered value is "Exampl" which has
		// a length of 6, the "e" would exceed the limit.
		expected: 'User:Exampl'
	} );

	byteLimitTest( {
		description: 'Input filter that increases the length',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.byteLimit( 10, function ( text ) {
				return 'prefix' + text;
			} ),
		sample: simpleSample,
		// Prefix adds 6 characters, limit is reached after 4
		expected: '1234'
	} );

	// Regression tests for T43450
	byteLimitTest( {
		description: 'Input filter of which the base exceeds the limit',
		$input: $( '<input>' ).attr( 'type', 'text' )
			.byteLimit( 3, function ( text ) {
				return 'prefix' + text;
			} ),
		sample: simpleSample,
		expected: ''
	} );

	QUnit.test( 'Confirm properties and attributes set', function ( assert ) {
		var $el;

		$el = $( '<input>' ).attr( 'type', 'text' )
			.attr( 'maxlength', '7' )
			.appendTo( '#qunit-fixture' )
			.byteLimit();

		assert.strictEqual( $el.attr( 'maxlength' ), '7', 'maxlength attribute unchanged for simple limit' );

		$el = $( '<input>' ).attr( 'type', 'text' )
			.attr( 'maxlength', '7' )
			.appendTo( '#qunit-fixture' )
			.byteLimit( 12 );

		assert.strictEqual( $el.attr( 'maxlength' ), '12', 'maxlength attribute updated for custom limit' );

		$el = $( '<input>' ).attr( 'type', 'text' )
			.attr( 'maxlength', '7' )
			.appendTo( '#qunit-fixture' )
			.byteLimit( 12, function ( val ) {
				return val;
			} );

		assert.strictEqual( $el.attr( 'maxlength' ), undefined, 'maxlength attribute removed for limit with callback' );

		$( '<input>' ).attr( 'type', 'text' )
			.addClass( 'mw-test-byteLimit-foo' )
			.attr( 'maxlength', '7' )
			.appendTo( '#qunit-fixture' );

		$( '<input>' ).attr( 'type', 'text' )
			.addClass( 'mw-test-byteLimit-foo' )
			.attr( 'maxlength', '12' )
			.appendTo( '#qunit-fixture' );

		$el = $( '.mw-test-byteLimit-foo' );

		assert.strictEqual( $el.length, 2, 'Verify that there are no other elements clashing with this test suite' );

		$el.byteLimit();
	} );

	QUnit.test( 'Trim from insertion when limit exceeded', function ( assert ) {
		var $el;

		// Use a new <input> because the bug only occurs on the first time
		// the limit it reached (T42850)
		$el = $( '<input>' ).attr( 'type', 'text' )
			.appendTo( '#qunit-fixture' )
			.byteLimit( 3 )
			.val( 'abc' ).trigger( 'change' )
			.val( 'zabc' ).trigger( 'change' );

		assert.strictEqual( $el.val(), 'abc', 'Trim from the insertion point (at 0), not the end' );

		$el = $( '<input>' ).attr( 'type', 'text' )
			.appendTo( '#qunit-fixture' )
			.byteLimit( 3 )
			.val( 'abc' ).trigger( 'change' )
			.val( 'azbc' ).trigger( 'change' );

		assert.strictEqual( $el.val(), 'abc', 'Trim from the insertion point (at 1), not the end' );
	} );

	QUnit.test( 'Do not cut up false matching substrings in emoji insertions', function ( assert ) {
		var $el,
			oldVal = '\uD83D\uDCA9\uD83D\uDCA9', // "💩💩"
			newVal = '\uD83D\uDCA9\uD83D\uDCB9\uD83E\uDCA9\uD83D\uDCA9', // "💩💹🢩💩"
			expected = '\uD83D\uDCA9\uD83D\uDCB9\uD83D\uDCA9'; // "💩💹💩"

		// Possible bad results:
		// * With no surrogate support:
		//   '\uD83D\uDCA9\uD83D\uDCB9\uD83E\uDCA9' "💩💹🢩"
		// * With correct trimming but bad detection of inserted text:
		//   '\uD83D\uDCA9\uD83D\uDCB9\uDCA9' "💩💹�"

		$el = $( '<input>' ).attr( 'type', 'text' )
			.appendTo( '#qunit-fixture' )
			.byteLimit( 12 )
			.val( oldVal ).trigger( 'change' )
			.val( newVal ).trigger( 'change' );

		assert.strictEqual( $el.val(), expected, 'Pasted emoji correctly trimmed at the end' );
	} );

	byteLimitTest( {
		description: 'Unpaired surrogates do not crash',
		$input: $( '<input>' ).attr( 'type', 'text' ).byteLimit( 4 ),
		sample: '\uD800\uD800\uDFFF',
		expected: '\uD800'
	} );

}( jQuery, mediaWiki ) );
