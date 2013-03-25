( function ( mw, $ ) {
	var loremIpsum = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.';

	QUnit.module( 'jquery.makeCollapsible', QUnit.newMwEnvironment() );

	function prepareCollapsible( html, options ) {
		var $parsed = $( $.parseHTML( html ) );
		$parsed.appendTo( '#qunit-fixture' );
		// options might be undefined here - this is okay
		$parsed.makeCollapsible( options );
		return $parsed;
	}

	QUnit.asyncTest( 'testing hooks (triggers)', 4, function ( assert ) {
		var $collapsible, $content, $toggle;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>'
		);
		$content = $collapsible.find( '.mw-collapsible-content' );
		$toggle = $collapsible.find( '.mw-collapsible-toggle' );

		// In one full collapse-expand cycle, each event will be fired once

		// On collapse...
		$collapsible.on( 'beforeCollapse.mw-collapse', function () {
			assert.ok( $content.is( ':visible' ), 'first beforeCollapseExpand: content is visible' );
		} );
		$collapsible.on( 'afterCollapse.mw-collapse', function () {
			assert.ok( $content.is( ':hidden' ), 'first afterCollapseExpand: content is hidden' );

			// On expand...
			$collapsible.on( 'beforeExpand.mw-collapse', function () {
				assert.ok( $content.is( ':hidden' ), 'second beforeCollapseExpand: content is hidden' );
			} );
			$collapsible.on( 'afterExpand.mw-collapse', function () {
				assert.ok( $content.is( ':visible' ), 'second afterCollapseExpand: content is visible' );

				QUnit.start();
			} );

			// ...expanding happens here
			$toggle.trigger( 'click' );
		} );

		// ...collapsing happens here
		$toggle.trigger( 'click' );
	} );

	QUnit.asyncTest( 'basic operation', 3, function ( assert ) {
		var $collapsible, $content;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>'
		);
		$content = $collapsible.find( '.mw-collapsible-content' );

		assert.ok( $content.length, 'content is present' );
		assert.ok( $content.is( ':visible' ), 'content is visible' );

		$collapsible.on( 'afterCollapse.mw-collapse', function () {
			assert.ok( $content.is( ':hidden' ), 'after collapsing: content is hidden' );
			QUnit.start();
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'basic operation with instantHide (synchronous test)', 2, function ( assert ) {
		var $collapsible, $content;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>',
			{ instantHide: true }
		);
		$content = $collapsible.find( '.mw-collapsible-content' );

		assert.ok( $content.is( ':visible' ), 'content is visible' );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );

		assert.ok( $content.is( ':hidden' ), 'after collapsing: content is hidden' );
	} );

	QUnit.asyncTest( 'initially collapsed - mw-collapsed class', 2, function ( assert ) {
		var $collapsible, $content;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible mw-collapsed">' + loremIpsum + '</div>'
		);
		$content = $collapsible.find( '.mw-collapsible-content' );

		// Synchronous - mw-collapsed should cause instantHide: true to be used on initial collapsing
		assert.ok( $content.is( ':hidden' ), 'content is hidden' );

		$collapsible.on( 'afterExpand.mw-collapse', function () {
			assert.ok( $content.is( ':visible' ), 'after expanding: content is visible' );
			QUnit.start();
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.asyncTest( 'initially collapsed - options', 2, function ( assert ) {
		var $collapsible, $content;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>',
			{ collapsed: true }
		);
		$content = $collapsible.find( '.mw-collapsible-content' );

		// Synchronous - mw-collapsed should cause instantHide: true to be used on initial collapsing
		assert.ok( $content.is( ':hidden' ), 'content is hidden' );

		$collapsible.on( 'afterExpand.mw-collapse', function () {
			assert.ok( $content.is( ':visible' ), 'after expanding: content is visible' );
			QUnit.start();
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );
}( mediaWiki, jQuery ) );
