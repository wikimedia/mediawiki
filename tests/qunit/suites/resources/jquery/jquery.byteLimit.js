module( 'jquery.byteLimit.js' );

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
var blti = 0;
/**
 * Test factory for $.fn.byteLimit
 *
 * @param $input {jQuery} jQuery object in an input element
 * @param useLimit {Boolean} Wether a limit should apply at all
 * @param limit {Number} Limit (if used) otherwise undefined
 * The limit should be less than 20 (the sample data's length) 
 */
var byteLimitTest = function( options ) {
	var opt = $.extend({
		description: '',
		$input: null,
		sample: '',
		useLimit: false,
		expected: 0,
		limit: null
	}, options);
	var i = blti++;

	test( opt.description, function() {

		opt.$input.appendTo( 'body' );
	
		// Simulate pressing keys for each of the sample characters
		$.addChars( opt.$input, opt.sample );
		var newVal = opt.$input.val();
	
		if ( opt.useLimit ) {
			expect(2);
	
			ltOrEq( $.byteLength( newVal ), opt.limit, 'Prevent keypresses after byteLimit was reached, length never exceeded the limit' );
			equal( $.byteLength( newVal ), opt.expected, 'Not preventing keypresses too early, length has reached the expected length' );
	
		} else {
			expect(1);
			equal( $.byteLength( newVal ), opt.expected, 'Unlimited scenarios are not affected, expected length reached' );
		}
	
		opt.$input.remove();
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
		.attr( {
			'type': 'text'
		}),
	sample: simpleSample,
	useLimit: false,
	expected: $.byteLength( simpleSample )
});

byteLimitTest({
	description: 'Limit using the maxlength attribute',
	$input: $( '<input>' )
		.attr( {
			'type': 'text',
			'maxlength': '10'
		})
		.byteLimit(),
	sample: simpleSample,
	useLimit: true,
	limit: 10,
	expected: 10
});

byteLimitTest({
	description: 'Limit using a custom value',
	$input: $( '<input>' )
		.attr( {
			'type': 'text'
		})
		.byteLimit( 10 ),
	sample: simpleSample,
	useLimit: true,
	limit: 10,
	expected: 10
});

byteLimitTest({
	description: 'Limit using a custom value, overriding maxlength attribute',
	$input: $( '<input>' )
		.attr( {
			'type': 'text',
			'maxLength': '10'
		})
		.byteLimit( 15 ),
	sample: simpleSample,
	useLimit: true,
	limit: 15,
	expected: 15
});

byteLimitTest({
	description: 'Limit using a custom value (multibyte)',
	$input: $( '<input>' )
		.attr( {
			'type': 'text'
		})
		.byteLimit( 14 ),
	sample: mbSample,
	useLimit: true,
	limit: 14,
	expected: 14 // (10 x 1-byte char) + (1 x 3-byte char) + (1 x 1-byte char)
});

byteLimitTest({
	description: 'Limit using a custom value (multibyte) overlapping a byte',
	$input: $( '<input>' )
		.attr( {
			'type': 'text'
		})
		.byteLimit( 12 ),
	sample: mbSample,
	useLimit: true,
	limit: 12,
	expected: 12 // 10 x 1-byte char. The next 3-byte char exceeds limit of 12, but 2 more 1-byte chars come in after.
});
