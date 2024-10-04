// JavaScript compatibility tests to confirm that server and browser
// are behaving consistently and configured correctly.
QUnit.module( 'startup/jscompat', () => {

	QUnit.test( 'Unicode variable name', ( assert ) => {
		const ŝablono = true;

		assert.true( ŝablono, 'ŝablono' );
		assert.true( \u015dablono, '\\u015dablono' );
		assert.true( \u015Dablono, '\\u015Dablono' );
	} );

	function repeat( str, n ) {
		let out;
		if ( n <= 0 ) {
			return '';
		} else {
			out = [];
			out.length = n + 1;
			return out.join( str );
		}
	}

	QUnit.test.each( 'textarea strips newline (T14130)', [ 0, 1, 2, 3 ], ( assert, i ) => {
		const expected = repeat( '\n', i ) + 'some text';

		let $textarea;
		// When setting HTML, we expect exactly 1 newline to be stripped.
		$textarea = $( '<textarea>\n' + expected + '</textarea>' );
		assert.strictEqual( $textarea.val(), expected, 'read after setting HTML' );

		$textarea = $( '<textarea>' ).val( expected );
		assert.strictEqual( $textarea.val(), expected, 'read after setting value' );
	} );
} );
