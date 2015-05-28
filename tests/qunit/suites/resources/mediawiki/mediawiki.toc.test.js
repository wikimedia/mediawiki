( function ( mw, $ ) {
	QUnit.module( 'mediawiki.toc', QUnit.newMwEnvironment( {
		setup: function () {
			// Prevent live cookies like mw_hidetoc=1 from interferring with the test
			this.stub( $, 'cookie' ).returns( null );
		}
	} ) );

	QUnit.asyncTest( 'toggleToc', 4, function ( assert ) {
		var tocHtml, $toggleLink, $tocList;

		assert.strictEqual( $( '#toc' ).length, 0, 'There is no table of contents on the page at the beginning' );

		tocHtml = '<div id="toc" class="toc">' +
			'<div id="toctitle">' +
			'<h2>Contents</h2>' +
			'</div>' +
			'<ul><li></li></ul>' +
			'</div>';
		$( tocHtml ).appendTo( '#qunit-fixture' );
		mw.hook( 'wikipage.content' ).fire( $( '#qunit-fixture' ) );

		$tocList = $( '#toc ul:first' );
		$toggleLink = $( '#togglelink' );

		assert.strictEqual( $toggleLink.length, 1, 'Toggle link is added to the table of contents' );

		assert.strictEqual( $tocList.is( ':hidden' ), false, 'The table of contents is now visible' );

		$toggleLink.click();
		$tocList.promise().done( function () {
			assert.strictEqual( $tocList.is( ':hidden' ), true, 'The table of contents is now hidden' );

			$toggleLink.click();
			$tocList.promise().done( function () {
				QUnit.start();
			} );
		} );
	} );
}( mediaWiki, jQuery ) );
