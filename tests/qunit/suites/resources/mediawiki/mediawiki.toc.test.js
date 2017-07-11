( function ( mw, $ ) {
	QUnit.module( 'mediawiki.toc', QUnit.newMwEnvironment( {
		setup: function () {
			// Prevent live cookies from interferring with the test
			this.stub( $, 'cookie' ).returns( null );
		}
	} ) );

	QUnit.test( 'toggleToc', function ( assert ) {
		var tocHtml, $toc, $toggleLink, $tocList;

		assert.strictEqual( $( '.toc' ).length, 0, 'There is no table of contents on the page at the beginning' );

		tocHtml = '<div id="toc" class="toc">' +
			'<div class="toctitle">' +
			'<h2>Contents</h2>' +
			'</div>' +
			'<ul><li></li></ul>' +
			'</div>';
		$toc = $( tocHtml );
		$( '#qunit-fixture' ).append( $toc );
		mw.hook( 'wikipage.content' ).fire( $( '#qunit-fixture' ) );

		$tocList = $toc.find( 'ul:first' );
		$toggleLink = $toc.find( '.togglelink' );

		assert.strictEqual( $toggleLink.length, 1, 'Toggle link is added to the table of contents' );

		assert.strictEqual( $tocList.is( ':hidden' ), false, 'The table of contents is now visible' );

		$toggleLink.click();
		return $tocList.promise().then( function () {
			assert.strictEqual( $tocList.is( ':hidden' ), true, 'The table of contents is now hidden' );

			$toggleLink.click();
			return $tocList.promise();
		} );
	} );
}( mediaWiki, jQuery ) );
