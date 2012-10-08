( function ( $, mw ) {
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
		var c, len;
		for ( c = 0, len = charstr.length; c < len; c += 1 ) {
			$input
				.val( function ( i, val ) {
					// Add character to the value
					return val + charstr.charAt( c );
				} )
				.trigger( 'change' );
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

		QUnit.asyncTest( opt.description, opt.hasLimit ? 3 : 2, function ( assert ) {
		setTimeout( function () {
			var rawVal, fn, effectiveVal;

			opt.$input.appendTo( '#qunit-fixture' );

			// Simulate pressing keys for each of the sample characters
			addChars( opt.$input, opt.sample );

			rawVal = opt.$input.val();
			fn = opt.$input.data( 'byteLimit.callback' );
			effectiveVal = fn ? fn( rawVal ) : rawVal;

			if ( opt.hasLimit ) {
				assert.ltOrEq(
					$.byteLength( effectiveVal ),
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
				assert.equal(
					$.byteLength( effectiveVal ),
					$.byteLength( opt.expected ),
					'Unlimited scenarios are not affected, expected length reached'
				);
				assert.equal( rawVal, opt.expected, 'New value matches the expected string' );
			}
			QUnit.start();
		}, 10 );
		} );
	}

	byteLimitTest({
		description: 'Plain text input',
		$input: $( '<input type="text"/>' ),
		sample: simpleSample,
		hasLimit: false,
		expected: simpleSample
	});

	byteLimitTest({
		description: 'Plain text input. Calling byteLimit with no parameters and no maxlength attribute (bug 36310)',
		$input: $( '<input type="text"/>' )
			.byteLimit(),
		sample: simpleSample,
		hasLimit: false,
		expected: simpleSample
	});

	byteLimitTest({
		description: 'Limit using the maxlength attribute',
		$input: $( '<input type="text"/>' )
			.attr( 'maxlength', '10' )
			.byteLimit(),
		sample: simpleSample,
		hasLimit: true,
		limit: 10,
		expected: '1234567890'
	});

	byteLimitTest({
		description: 'Limit using a custom value',
		$input: $( '<input type="text"/>' )
			.byteLimit( 10 ),
		sample: simpleSample,
		hasLimit: true,
		limit: 10,
		expected: '1234567890'
	});

	byteLimitTest({
		description: 'Limit using a custom value, overriding maxlength attribute',
		$input: $( '<input type="text"/>' )
			.attr( 'maxlength', '10' )
			.byteLimit( 15 ),
		sample: simpleSample,
		hasLimit: true,
		limit: 15,
		expected: '123456789012345'
	});

	byteLimitTest({
		description: 'Limit using a custom value (multibyte)',
		$input: $( '<input type="text"/>' )
			.byteLimit( 14 ),
		sample: mbSample,
		hasLimit: true,
		limit: 14,
		expected: '1234567890' + U_20AC + '1'
	});

	byteLimitTest({
		description: 'Limit using a custom value (multibyte) overlapping a byte',
		$input: $( '<input type="text"/>' )
			.byteLimit( 12 ),
		sample: mbSample,
		hasLimit: true,
		limit: 12,
		expected: '1234567890' + '12'
	});

	byteLimitTest({
		description: 'Pass the limit and a callback as input filter',
		$input: $( '<input type="text"/>' )
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
		$input: $( '<input type="text"/>' )
			.attr( 'maxlength', '6' )
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

	QUnit.test( 'Confirm properties and attributes set', 4, function ( assert ) {
		var $el, $elA, $elB;

		$el = $( '<input type="text"/>' )
			.attr( 'maxlength', '7' )
			.appendTo( '#qunit-fixture' )
			.byteLimit();

		assert.strictEqual( $el.attr( 'maxlength' ), '7', 'maxlength attribute unchanged for simple limit' );

		$el = $( '<input type="text"/>' )
			.attr( 'maxlength', '7' )
			.appendTo( '#qunit-fixture' )
			.byteLimit( 12 );

		assert.strictEqual( $el.attr( 'maxlength' ), '12', 'maxlength attribute updated for custom limit' );

		$el = $( '<input type="text"/>' )
			.attr( 'maxlength', '7' )
			.appendTo( '#qunit-fixture' )
			.byteLimit( 12, function ( val ) {
				return val;
			} );

		assert.strictEqual( $el.attr( 'maxlength' ), undefined, 'maxlength attribute removed for limit with callback' );

		$elA = $( '<input type="text"/>' )
			.addClass( 'mw-test-byteLimit-foo' )
			.attr( 'maxlength', '7' )
			.appendTo( '#qunit-fixture' );

		$elB = $( '<input type="text"/>' )
			.addClass( 'mw-test-byteLimit-foo' )
			.attr( 'maxlength', '12' )
			.appendTo( '#qunit-fixture' );

		$el = $( '.mw-test-byteLimit-foo' );

		assert.strictEqual( $el.length, 2, 'Verify that there are no other elements clashing with this test suite' );

		$el.byteLimit();
	});

}( jQuery, mediaWiki ) );
