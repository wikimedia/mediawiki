( function ( $ ) {
	var simpleSample, U_20AC, mbSample;

	QUnit.module( 'jquery.byteLimit', QUnit.newMwEnvironment() );

	// Simple sample (20 chars, 20 bytes)
	simpleSample = '12345678901234567890';

	// 3 bytes (euro-symbol)
	U_20AC = '\u20AC';

	// Multi-byte sample (22 chars, 26 bytes)
	mbSample = '1234567890' + U_20AC + '1234567890' + U_20AC;

	// Basic sendkey-implementation
	function addChars( $input, charstr ) {
		var len, i, prevVal, code, event;
		len = charstr.length;
		for ( i = 0; i < len; i += 1 ) {
			// Keep track of the previous value
			prevVal = $input.val();

			// Get the key code
			code = charstr.charCodeAt( i );

			// Trigger event and undo if prevented
			event = new jQuery.Event( 'keypress', {
				which: code,
				keyCode: code,
				charCode: code
			} );
			$input.trigger( event );
			if ( !event.isDefaultPrevented() ) {
				$input.val( prevVal + charstr.charAt( i ) );
			}
		}
	}

	/**
	 * Test factory for $.fn.byteLimit
	 *
	 * @param $input {jQuery} jQuery object in an input element
	 * @param hasLimit {Boolean} Wether a limit should apply at all
	 * @param limit {Number} Limit (if used) otherwise undefined
	 * The limit should be less than 20 (the sample data's length)
	 */
	function byteLimitTest( options ) {
		var opt = $.extend({
			description: '',
			$input: null,
			sample: '',
			hasLimit: false,
			expected: '',
			limit: null
		}, options);

		QUnit.test( opt.description, function ( assert ) {
			var rawVal, fn, newVal;

			opt.$input.appendTo( '#qunit-fixture' );

			// Simulate pressing keys for each of the sample characters
			addChars( opt.$input, opt.sample );
			rawVal = opt.$input.val();
			fn = opt.$input.data( 'byteLimit-callback' );
			newVal = $.isFunction( fn ) ? fn( rawVal ) : rawVal;

			if ( opt.hasLimit ) {
				QUnit.expect(3);

				assert.ltOrEq(
					$.byteLength( newVal ),
					opt.limit,
					'Prevent keypresses after byteLimit was reached, length never exceeded the limit'
				);
				assert.equal(
					$.byteLength( rawVal ),
					$.byteLength( opt.expected ),
					'Not preventing keypresses too early, length has reached the expected length'
				);
				assert.equal( rawVal, opt.expected, 'New value matches the expected string' );

			} else {
				QUnit.expect(2);
				assert.equal( newVal, opt.expected, 'New value matches the expected string' );
				assert.equal(
					$.byteLength( newVal ),
					$.byteLength( opt.expected ),
					'Unlimited scenarios are not affected, expected length reached'
				);
			}
		} );
	}

	byteLimitTest({
		description: 'Plain text input',
		$input: $( '<input>' )
			.attr( 'type', 'text' ),
		sample: simpleSample,
		hasLimit: false,
		expected: simpleSample
	});

	byteLimitTest({
		description: 'Plain text input. Calling byteLimit with no parameters and no maxLength property (bug 36310)',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.byteLimit(),
		sample: simpleSample,
		hasLimit: false,
		expected: simpleSample
	});

	byteLimitTest({
		description: 'Limit using the maxlength attribute',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.prop( 'maxLength', '10' )
			.byteLimit(),
		sample: simpleSample,
		hasLimit: true,
		limit: 10,
		expected: '1234567890'
	});

	byteLimitTest({
		description: 'Limit using a custom value',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.byteLimit( 10 ),
		sample: simpleSample,
		hasLimit: true,
		limit: 10,
		expected: '1234567890'
	});

	byteLimitTest({
		description: 'Limit using a custom value, overriding maxlength attribute',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.prop( 'maxLength', '10' )
			.byteLimit( 15 ),
		sample: simpleSample,
		hasLimit: true,
		limit: 15,
		expected: '123456789012345'
	});

	byteLimitTest({
		description: 'Limit using a custom value (multibyte)',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.byteLimit( 14 ),
		sample: mbSample,
		hasLimit: true,
		limit: 14,
		expected: '1234567890' + U_20AC + '1'
	});

	byteLimitTest({
		description: 'Limit using a custom value (multibyte) overlapping a byte',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.byteLimit( 12 ),
		sample: mbSample,
		hasLimit: true,
		limit: 12,
		expected: '1234567890' + '12'
	});

	byteLimitTest({
		description: 'Pass the limit and a callback as input filter',
		$input: $( '<input>' )
			.attr( 'type', 'text' )
			.byteLimit( 6, function ( val ) {
				// Invalid title
				if ( val === '' ) {
					return '';
				}

				// Return without namespace prefix
				return new mw.Title( String( val ) ).getMain();
			} ),
		sample: 'User:Sample',
		hasLimit: true,
		limit: 6, // 'Sample' length
		expected: 'User:Sample'
	});

	byteLimitTest({
		description: 'Limit using the maxlength attribute and pass a callback as input filter',
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
		hasLimit: true,
		limit: 6, // 'Sample' length
		expected: 'User:Sample'
	});

	QUnit.test( 'Confirm properties and attributes set', 5, function ( assert ) {
		var $el, $elA, $elB;

		$el = $( '<input>' )
			.attr( 'type', 'text' )
			.prop( 'maxLength', '7' )
			.appendTo( '#qunit-fixture' )
			.byteLimit();

		assert.strictEqual( $el.prop( 'maxLength' ), 7, 'Pre-set maxLength property unchanged' );

		$el = $( '<input>' )
			.attr( 'type', 'text' )
			.prop( 'maxLength', '7' )
			.appendTo( '#qunit-fixture' )
			.byteLimit( 12 );

		assert.strictEqual( $el.prop( 'maxLength' ), 12, 'maxLength property updated if value was passed to $.fn.byteLimit' );

		$elA = $( '<input>' )
			.addClass( 'mw-test-byteLimit-foo' )
			.attr( 'type', 'text' )
			.prop( 'maxLength', '7' )
			.appendTo( '#qunit-fixture' );

		$elB = $( '<input>' )
			.addClass( 'mw-test-byteLimit-foo' )
			.attr( 'type', 'text' )
			.prop( 'maxLength', '12' )
			.appendTo( '#qunit-fixture' );

		$el = $( '.mw-test-byteLimit-foo' );

		assert.strictEqual( $el.length, 2, 'Verify that there are no other elements clashing with this test suite' );

		$el.byteLimit();

		// Before bug 35294 was fixed, both $elA and $elB had maxLength set to 7,
		// because $.fn.byteLimit sets:
		// `limit = limitArg || this.prop( 'maxLength' ); this.prop( 'maxLength', limit )`
		// and did so outside the each() loop.
		assert.strictEqual( $elA.prop( 'maxLength' ), 7, 'maxLength was not incorrectly set on #1 when calling byteLimit on multiple elements (bug 35294)' );
		assert.strictEqual( $elB.prop( 'maxLength' ), 12, 'maxLength was not incorrectly set on #2 when calling byteLimit on multiple elements (bug 35294)' );
	});

}( jQuery ) );
