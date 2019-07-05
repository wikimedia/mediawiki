( function () {
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
			'<div class="toctitle" lang="en" dir="ltr">' +
			'<h2>Contents</h2>' +
			'</div>' +
			'<ul><li></li></ul>' +
			'</div>';
		$toc = $( tocHtml );
		$( '#qunit-fixture' ).append( $toc );
		mw.hook( 'wikipage.content' ).fire( $( '#qunit-fixture' ) );

		$tocList = $toc.find( 'ul' ).first();
		$toggleLink = $toc.find( '.togglelink' );

		assert.strictEqual( $toggleLink.length, 1, 'Toggle link is added to the table of contents' );

		// eslint-disable-next-line no-jquery/no-class-state
		assert.strictEqual( $toc.hasClass( 'tochidden' ), false, 'The table of contents is now visible' );

		$toggleLink.trigger( 'click' );
		return $tocList.promise().then( function () {
			// eslint-disable-next-line no-jquery/no-class-state
			assert.strictEqual( $toc.hasClass( 'tochidden' ), true, 'The table of contents is now hidden' );
			$toggleLink.trigger( 'click' );
			return $tocList.promise();
		} );
	} );
}() );
