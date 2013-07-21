( function ( mw, $ ) {
	var loremIpsum = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.';

	QUnit.module( 'jquery.makeCollapsible', QUnit.newMwEnvironment() );

	function prepareCollapsible( html, options ) {
		return $( $.parseHTML( html ) )
			.appendTo( '#qunit-fixture' )
			// options might be undefined here - this is okay
			.makeCollapsible( options );
	}

	// This test is first because if it fails, then almost all of the latter tests are meaningless.
	QUnit.asyncTest( 'testing hooks/triggers', 4, function ( assert ) {
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

	QUnit.asyncTest( 'basic operation (<div>)', 3, function ( assert ) {
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

	QUnit.test( 'basic operation when synchronous (options.instantHide)', 2, function ( assert ) {
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

	QUnit.test( 'mw-made-collapsible data added', 1, function ( assert ) {
		var $collapsible;
		$collapsible = prepareCollapsible(
			'<div>' + loremIpsum + '</div>'
		);
		assert.equal( $collapsible.data( 'mw-made-collapsible' ), true, 'mw-made-collapsible data present' );
	} );

	QUnit.test( 'mw-collapsible added when missing', 1, function ( assert ) {
		var $collapsible;
		$collapsible = prepareCollapsible(
			'<div>' + loremIpsum + '</div>'
		);
		assert.assertTrue( $collapsible.hasClass( 'mw-collapsible' ), 'mw-collapsible class present' );
	} );

	QUnit.test( 'mw-collapsed added when missing', 1, function ( assert ) {
		var $collapsible;
		$collapsible = prepareCollapsible(
			'<div>' + loremIpsum + '</div>',
			{ collapsed: true }
		);
		assert.assertTrue( $collapsible.hasClass( 'mw-collapsed' ), 'mw-collapsed class present' );
	} );

	QUnit.asyncTest( 'initial collapse (mw-collapsed class)', 2, function ( assert ) {
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

	QUnit.asyncTest( 'initial collapse (options.collapsed)', 2, function ( assert ) {
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

	QUnit.test( 'clicks on links inside toggler pass through (options.linksPassthru)' , 2, function ( assert ) {
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

	QUnit.asyncTest( 'collapse/expand text (data-collapsetext, data-expandtext)', 2, function ( assert ) {
		var $collapsible, $toggleLink;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible" data-collapsetext="Collapse me!" data-expandtext="Expand me!">' +
				loremIpsum +
			'</div>'
		);
		$toggleLink = $collapsible.find( '.mw-collapsible-toggle a' );

		assert.equal( $toggleLink.text(), 'Collapse me!', 'data-collapsetext is respected' );

		$collapsible.on( 'afterCollapse.mw-collapsible', function () {
			assert.equal( $toggleLink.text(), 'Expand me!', 'data-expandtext is respected' );
			QUnit.start();
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.asyncTest( 'collapse/expand text (options.collapseText, options.expandText)', 2, function ( assert ) {
		var $collapsible, $toggleLink;
		$collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>',
			{ collapseText: 'Collapse me!', expandText: 'Expand me!' }
		);
		$toggleLink = $collapsible.find( '.mw-collapsible-toggle a' );

		assert.equal( $toggleLink.text(), 'Collapse me!', 'options.collapseText is respected' );

		$collapsible.on( 'afterCollapse.mw-collapsible', function () {
			assert.equal( $toggleLink.text(), 'Expand me!', 'options.expandText is respected' );
			QUnit.start();
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

}( mediaWiki, jQuery ) );
