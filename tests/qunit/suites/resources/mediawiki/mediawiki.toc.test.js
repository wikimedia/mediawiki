( function ( mw, $ ) {
	QUnit.module( 'mediawiki.toc', QUnit.newMwEnvironment() );

	QUnit.asyncTest( 'toggleToc', 4, function ( assert ) {
		var tocHtml, $toggleLink, $tocList;

		function actionC() {
			QUnit.start();
		}

		function actionB() {
			assert.strictEqual( $tocList.is( ':hidden' ), true, 'Return boolean true if the TOC is now visible.' );
			$toggleLink.click();
			$tocList.promise().done( actionC );
		}

		function actionA() {
			assert.strictEqual( $tocList.is( ':hidden' ), false, 'Return boolean false if the TOC is now hidden.' );
			$toggleLink.click();
			$tocList.promise().done( actionB );
		}

		assert.strictEqual( $( '#toc' ).length, 0, 'Return 0 if there is no table of contents on the page.' );

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

		assert.strictEqual( $toggleLink.length, 1, 'Toggle link is appended to the page.' );

		actionA();
	} );
}( mediaWiki, jQuery ) );
