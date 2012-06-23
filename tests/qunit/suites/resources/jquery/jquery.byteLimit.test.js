( function ( $ ) {
	var sample20simple, sample1mb, sample22mixed;

	module( 'jquery.byteLimit', QUnit.newMwEnvironment() );

	// Simple sample (20 chars, 20 bytes)
	sample20simple = '1234567890abcdefghij';

	// Euro-symbol (1 char, 3 bytes)
	sample1mb = '\u20AC';

	// Multi-byte sample (22 chars, 26 bytes)
	sample22mixed = '1234567890' + sample1mb + 'abcdefghij' + sample1mb;

	/**
	 * Basic emulation of character-by-charater insertion
	 * and triggering of keyup event after each character.
	 */
	function simulateKeyUps( $input, charstr ) {
		var i, code, event, liveVal,
			len = charstr.length;
		for ( i = 0; i < len; i += 1 ) {
			// Always use the live value as base
			liveVal = $input.val();

			// Get the key code for the to-be-inserted character
			code = charstr.charCodeAt( i );

			$input.val( liveVal + charstr.charAt( i ) );

			// Trigger keyup event
			event = new jQuery.Event( 'keyup', {
				keyCode: code,
				charCode: code
			} );
			$input.trigger( event );
		}
	}

	/**
	 * Test factory for $.fn.byteLimit
	 *
	 * @param Object options
	 */
	function byteLimitTest( options ) {
		var opt = $.extend({
			description: '',
			$input: null,
			sample: '',
			limit: false,
			expected: ''
		}, options);

		test( opt.description, function () {
			var rawVal, fn, useVal;

			opt.$input.appendTo( '#qunit-fixture' );

			simulateKeyUps( opt.$input, opt.sample );

			rawVal = opt.$input.val();
			fn = opt.$input.data( 'byteLimitCallback' );
			useVal = $.isFunction( fn ) ? fn( rawVal ) : rawVal;

			if ( opt.limit ) {
				expect( 3 );

				QUnit.ltOrEq(
					$.byteLength( useVal ),
					opt.limit,
					'Prevent keypresses after byteLimit was reached, length never exceeded the limit'
				);
				equal(
					$.byteLength( rawVal ),
					$.byteLength( opt.expected ),
					'Not preventing keypresses too early, length has reached the expected length'
				);
				equal( rawVal, opt.expected, 'New value matches the expected string' );

			} else {
				expect( 2 );
				equal( useVal, opt.expected, 'New value matches the expected string' );
				equal(
					$.byteLength( useVal ),
					$.byteLength( opt.expected ),
					'Unlimited scenarios are not affected, expected length reached'
				);
			}
		} );
	}

	test( '-- Initial check', function () {
		expect(1);
		ok( $.fn.byteLimit, 'jQuery.fn.byteLimit defined' );
	} );

	byteLimitTest({
		description: 'Plain text input',
		$input: $( '<input>' )
			.attr( 'type', 'text' ),
		sample: sample20simple,
		expected: sample20simple
	});

	byteLimitTest({
		description: 'No .byteLimit() parameters and no maxLength property - should not throw exceptions (bug 36310)',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.byteLimit(),
		sample: sample20simple,
		expected: sample20simple
	});

	byteLimitTest({
		description: 'maxLength property',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.prop( 'maxLength', '10' )
			.byteLimit(),
		sample: sample20simple,
		limit: 10,
		expected: '1234567890'
	});

	byteLimitTest({
		description: '.byteLimit( limit )',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.byteLimit( 10 ),
		sample: sample20simple,
		limit: 10,
		expected: '1234567890'
	});

	byteLimitTest({
		description: 'Limit passed to .byteLimit() takes precedence over maxLength property',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.prop( 'maxLength', '10' )
			.byteLimit( 15 ),
		sample: sample20simple,
		limit: 15,
		expected: '1234567890abcde'
	});

	byteLimitTest({
		description: '.byteLimit( limit ) - mixed - cut in simplepart',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.byteLimit( 14 ),
		sample: sample22mixed,
		limit: 14,
		expected: '1234567890' + sample1mb + 'a'
	});

	byteLimitTest({
		description: '.byteLimit( limit ) - mixed - cut in multibyte',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.byteLimit( 12 ),
		sample: sample22mixed,
		limit: 12,
		// The 3-byte symbol after 0 would have exceeded the 12 byte limit.
		// Later when the simulation resumed typing, the two simple characters
		// were allowed.
		expected: '1234567890' + 'ab'
	});

	byteLimitTest({
		description: '.byteLimit( limit, fn ) callback can alter the value to be check against',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.byteLimit( 6, function ( val ) {
				// Only construct mw.Title if the string is non-empty,
				// since mw.Title throws an exception on invalid titles.
				if ( val === '' ) {
					return '';
				}

				// Example: Use value without namespace prefix.
				return new mw.Title( String( val ) ).getMain();
			} ),
		sample: 'User:John',
		// Limit is 6, text "User:Sample" would normally be too long,
		// but in this case we test the callback's ability to only
		// apply the limit to part of the input. The part "John" in this
		// case is within the limit.
		limit: 6,
		// The callback only affects the comparison, the actual input field
		// should still contain the full value.
		expected: 'User:John'
	});

	byteLimitTest({
		description: '.byteLimit( fn ) combined with maxLength property',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.prop( 'maxLength', '6' )
			.byteLimit( function ( val ) {
				// Invalid title
				if ( val === '' ) {
					return '';
				}

				// Return without namespace prefix
				return new mw.Title( String( val ) ).getMain();
			} ),
		sample: 'User:Sample',
		limit: 6, // 'Sample' length
		expected: 'User:Sample'
	});

}( jQuery ) );
