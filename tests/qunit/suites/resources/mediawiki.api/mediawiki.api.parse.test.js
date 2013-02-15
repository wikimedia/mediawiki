( function ( mw, $ ) {
	QUnit.module( 'mediawiki.api.parse', QUnit.newMwEnvironment() );

	QUnit.asyncTest( 'Hello world', function ( assert ) {
		var api;
		QUnit.expect( 6 );

		api = new mw.Api();

		api.parse( '\'\'\'Hello world\'\'\'' )
			.done( function ( html ) {
				// Parse into a document fragment instead of comparing HTML, due to
				// presence of Tidy influencing whitespace.
				// Html also contains "NewPP report" comment.
				var $res = $( '<div>' ).html( html ).children(),
					res = $res.get( 0 );
				assert.equal( $res.length, 1, 'Response contains 1 element' );
				assert.equal( res.nodeName.toLowerCase(), 'p', 'Response is a paragraph' );
				assert.equal( $res.children().length, 1, 'Response has 1 child element' );
				assert.equal( $res.children().get( 0 ).nodeName.toLowerCase(), 'b', 'Child element is a bold tag' );
				// Trim since Tidy may or may not mess with the spacing here
				assert.equal( $.trim( $res.text() ), 'Hello world', 'Response contains given text' );
				assert.equal( $res.find( 'b' ).text(), 'Hello world', 'Bold tag wraps the entire, same, text' );

				QUnit.start();
			} );
	} );
}( mediaWiki, jQuery ) );
