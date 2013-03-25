( function ( mw, $ ) {
	var loremIpsum = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.';

	QUnit.module( 'jquery.makeCollapsible', QUnit.newMwEnvironment() );

	function prepareCollapsible( html, options ) {
		return $( $.parseHTML( html ) )
			.appendTo( '#qunit-fixture' )
			// options might be undefined here - this is okay
			.makeCollapsible( options );
	}

	QUnit.test( 'basic operation with instantHide (synchronous test)', 2, function ( assert ) {
		var $collapsible, $content;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>',
			{ instantHide: true }
		);
		$content = $collapsible.find( '.mw-collapsible-content' );

		assert.assertTrue( $content.is( ':visible' ), 'content is visible' );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );

		assert.assertTrue( $content.is( ':hidden' ), 'after collapsing: content is hidden' );
	} );

	QUnit.test( 'initially collapsed - mw-collapsed class', 1, function ( assert ) {
		var $collapsible, $content;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible mw-collapsed">' + loremIpsum + '</div>'
		);
		$content = $collapsible.find( '.mw-collapsible-content' );

		// Synchronous - mw-collapsed should cause instantHide: true to be used on initial collapsing
		assert.assertTrue( $content.is( ':hidden' ), 'content is hidden' );
	} );

	QUnit.test( 'initially collapsed - options', 1, function ( assert ) {
		var $collapsible, $content;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>',
			{ collapsed: true }
		);
		$content = $collapsible.find( '.mw-collapsible-content' );

		// Synchronous - mw-collapsed should cause instantHide: true to be used on initial collapsing
		assert.assertTrue( $content.is( ':hidden' ), 'content is hidden' );
	} );

}( mediaWiki, jQuery ) );
