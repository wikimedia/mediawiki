/**
 * Some misc JavaScript compatibility tests,
 * just to make sure the environments we run in are consistent.
 */
( function () {
	QUnit.module( 'mediawiki.jscompat', QUnit.newMwEnvironment() );

	QUnit.test( 'Variable with Unicode letter in name', function ( assert ) {
		var orig, ŝablono;

		orig = 'some token';
		ŝablono = orig;

		assert.deepEqual( ŝablono, orig, 'ŝablono' );
		assert.deepEqual( \u015dablono, orig, '\\u015dablono' );
		assert.deepEqual( \u015Dablono, orig, '\\u015Dablono' );
	} );

	/*
	// Not that we need this. ;)
	// This fails on IE 6-8
	// Works on IE 9, Firefox 6, Chrome 14
	...( 'Keyword workaround: "if" as variable name using Unicode escapes', function ( assert ) {
		var orig = "another token";
		\u0069\u0066 = orig;
		assert.deepEqual( \u0069\u0066, orig, '\\u0069\\u0066' );
	});
	*/

	/*
	// Not that we need this. ;)
	// This fails on IE 6-9
	// Works on Firefox 6, Chrome 14
	...( 'Keyword workaround: "if" as member variable name using Unicode escapes', function ( assert ) {
		var orig = "another token";
		var foo = {};
		foo.\u0069\u0066 = orig;
		assert.deepEqual( foo.\u0069\u0066, orig, 'foo.\\u0069\\u0066' );
	});
	*/

	QUnit.test( 'Stripping of single initial newline from textarea\'s literal contents (T14130)', function ( assert ) {
		var maxn, n,
			expected, $textarea;

		maxn = 4;

		function repeat( str, n ) {
			var out;
			if ( n <= 0 ) {
				return '';
			} else {
				out = [];
				out.length = n + 1;
				return out.join( str );
			}
		}

		for ( n = 0; n < maxn; n++ ) {
			expected = repeat( '\n', n ) + 'some text';

			$textarea = $( '<textarea>\n' + expected + '</textarea>' );
			assert.strictEqual( $textarea.val(), expected, 'Expecting ' + n + ' newlines (HTML contained ' + ( n + 1 ) + ')' );

			$textarea = $( '<textarea>' ).val( expected );
			assert.strictEqual( $textarea.val(), expected, 'Expecting ' + n + ' newlines (from DOM set with ' + n + ')' );
		}
	} );
}() );
