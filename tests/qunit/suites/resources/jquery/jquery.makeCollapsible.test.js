( function ( mw, $ ) {
	var loremIpsum = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.';

	QUnit.module( 'jquery.makeCollapsible', QUnit.newMwEnvironment() );

	function prepareCollapsible( html, options ) {
		return $( $.parseHTML( html ) )
			.appendTo( '#qunit-fixture' )
			// options might be undefined here - this is okay
			.makeCollapsible( options );
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
		$collapsible.on( 'beforeCollapse.mw-collapsible', function () {
			assert.assertTrue( $content.is( ':visible' ), 'first beforeCollapseExpand: content is visible' );
		} );
		$collapsible.on( 'afterCollapse.mw-collapsible', function () {
			assert.assertTrue( $content.is( ':hidden' ), 'first afterCollapseExpand: content is hidden' );

			// On expand...
			$collapsible.on( 'beforeExpand.mw-collapsible', function () {
				assert.assertTrue( $content.is( ':hidden' ), 'second beforeCollapseExpand: content is hidden' );
			} );
			$collapsible.on( 'afterExpand.mw-collapsible', function () {
				assert.assertTrue( $content.is( ':visible' ), 'second afterCollapseExpand: content is visible' );

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

		assert.equal( $content.length, 1, 'content is present' );
		assert.assertTrue( $content.is( ':visible' ), 'content is visible' );

		$collapsible.on( 'afterCollapse.mw-collapsible', function () {
			assert.assertTrue( $content.is( ':hidden' ), 'after collapsing: content is hidden' );
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

		assert.assertTrue( $content.is( ':visible' ), 'content is visible' );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );

		assert.assertTrue( $content.is( ':hidden' ), 'after collapsing: content is hidden' );
	} );

	QUnit.asyncTest( 'initially collapsed - mw-collapsed class', 2, function ( assert ) {
		var $collapsible, $content;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible mw-collapsed">' + loremIpsum + '</div>'
		);
		$content = $collapsible.find( '.mw-collapsible-content' );

		// Synchronous - mw-collapsed should cause instantHide: true to be used on initial collapsing
		assert.assertTrue( $content.is( ':hidden' ), 'content is hidden' );

		$collapsible.on( 'afterExpand.mw-collapsible', function () {
			assert.assertTrue( $content.is( ':visible' ), 'after expanding: content is visible' );
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

		// Synchronous - collapsed: true should cause instantHide: true to be used on initial collapsing
		assert.assertTrue( $content.is( ':hidden' ), 'content is hidden' );

		$collapsible.on( 'afterExpand.mw-collapsible', function () {
			assert.assertTrue( $content.is( ':visible' ), 'after expanding: content is visible' );
			QUnit.start();
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'premade toggler - options.linksPassthru' , 2, function ( assert ) {
		var $collapsible, $content;

		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' +
				'<div class="mw-collapsible-toggle">' +
					'Toggle <a href="#top">toggle</a> toggle <b>toggle</b>' +
				'</div>' +
				'<div class="mw-collapsible-content">' + loremIpsum + '</div>' +
			'</div>',
			// Can't do asynchronous because we're testing that the event *doesn't* happen
			{ instantHide: true }
		);
		$content = $collapsible.find( '.mw-collapsible-content' );

		$collapsible.find( '.mw-collapsible-toggle a' ).trigger( 'click' );
		assert.assertTrue( $content.is( ':visible' ), 'click event on link inside toggle passes through (content not toggled)' );

		$collapsible.find( '.mw-collapsible-toggle b' ).trigger( 'click' );
		assert.assertTrue( $content.is( ':hidden' ), 'click event on non-link inside toggle toggles content' );
	} );

}( mediaWiki, jQuery ) );
