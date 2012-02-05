( function () {

module( 'jquery.byteLimit', QUnit.newMwEnvironment() );

test( '-- Initial check', function() {
	expect(1);
	ok( $.fn.byteLimit, 'jQuery.fn.byteLimit defined' );
} );

// Basic sendkey-implementation
$.addChars = function( $input, charstr ) {
	var len = charstr.length;
	for ( var i = 0; i < len; i++ ) {
		// Keep track of the previous value
		var prevVal = $input.val();

		// Get the key code
		var code = charstr.charCodeAt(i);

		// Trigger event and undo if prevented
		var event = new jQuery.Event( 'keypress', { keyCode: code, which: code, charCode: code } );
		$input.trigger( event );
		if ( !event.isDefaultPrevented() ) {
			$input.val( prevVal + charstr.charAt(i) );
		}
	}
};

/**
 * Test factory for $.fn.byteLimit
 *
 * @param $input {jQuery} jQuery object in an input element
 * @param hasLimit {Boolean} Wether a limit should apply at all
 * @param limit {Number} Limit (if used) otherwise undefined
 * The limit should be less than 20 (the sample data's length)
 */
var byteLimitTest = function( options ) {
	var opt = $.extend({
		description: '',
		$input: null,
		sample: '',
		hasLimit: false,
		expected: '',
		limit: null
	}, options);

	test( opt.description, function() {

		opt.$input.appendTo( '#qunit-fixture' );

		// Simulate pressing keys for each of the sample characters
		$.addChars( opt.$input, opt.sample );
		var	rawVal = opt.$input.val(),
			fn = opt.$input.data( 'byteLimit-callback' ),
			newVal = $.isFunction( fn ) ? fn( rawVal ) : rawVal;

		if ( opt.hasLimit ) {
			expect(3);

			ltOrEq( $.byteLength( newVal ), opt.limit, 'Prevent keypresses after byteLimit was reached, length never exceeded the limit' );
			equal( $.byteLength( rawVal ), $.byteLength( opt.expected ), 'Not preventing keypresses too early, length has reached the expected length' );
			equal( rawVal, opt.expected, 'New value matches the expected string' );

		} else {
			expect(2);
			equal( newVal, opt.expected, 'New value matches the expected string' );
			equal( $.byteLength( newVal ), $.byteLength( opt.expected ), 'Unlimited scenarios are not affected, expected length reached' );
		}
	} );
};

var
	// Simple sample (20 chars, 20 bytes)
	simpleSample = '12345678901234567890',

	// 3 bytes (euro-symbol)
	U_20AC = '\u20AC',

	// Multi-byte sample (22 chars, 26 bytes)
	mbSample = '1234567890' + U_20AC + '1234567890' + U_20AC;

byteLimitTest({
	description: 'Plain text input',
	$input: $( '<input>' )
		.attr( 'type', 'text' ),
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
		.byteLimit( 6, function( val ) {
			// Invalid title
			if ( val == '' ) {
				return '';
			}

			// Return without namespace prefix
			return new mw.Title( '' + val ).getMain();
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
		.byteLimit( function( val ) {
			// Invalid title
			if ( val === '' ) {
				return '';
			}

			// Return without namespace prefix
			return new mw.Title( '' + val ).getMain();
		} ),
	sample: 'User:Sample',
	hasLimit: true,
	limit: 6, // 'Sample' length
	expected: 'User:Sample'
});

}() );