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
	QUnit.test( 'testing hooks/triggers', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div class="mw-collapsible">' + loremIpsum + '</div>'
			),
			$content = $collapsible.find( '.mw-collapsible-content' ),
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
			} );

			// ...expanding happens here
			$toggle.trigger( 'click' );
		} );

		// ...collapsing happens here
		$toggle.trigger( 'click' );
	} );

	QUnit.test( 'basic operation (<div>)', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div class="mw-collapsible">' + loremIpsum + '</div>'
			),
			$content = $collapsible.find( '.mw-collapsible-content' ),
			$toggle = $collapsible.find( '.mw-collapsible-toggle' );

		assert.equal( $content.length, 1, 'content is present' );
		assert.equal( $content.find( $toggle ).length, 0, 'toggle is not a descendant of content' );

		assert.assertTrue( $content.is( ':visible' ), 'content is visible' );

		$collapsible.on( 'afterCollapse.mw-collapsible', function () {
			assert.assertTrue( $content.is( ':hidden' ), 'after collapsing: content is hidden' );

			$collapsible.on( 'afterExpand.mw-collapsible', function () {
				assert.assertTrue( $content.is( ':visible' ), 'after expanding: content is visible' );
			} );

			$toggle.trigger( 'click' );
		} );

		$toggle.trigger( 'click' );
	} );

	QUnit.test( 'basic operation (<table>)', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<table class="mw-collapsible">' +
					'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
					'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
					'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
				'</table>'
			),
			$headerRow = $collapsible.find( 'tr:first' ),
			$contentRow = $collapsible.find( 'tr:last' ),
			$toggle = $headerRow.find( 'td:last .mw-collapsible-toggle' );

		assert.equal( $toggle.length, 1, 'toggle is added to last cell of first row' );

		assert.assertTrue( $headerRow.is( ':visible' ), 'headerRow is visible' );
		assert.assertTrue( $contentRow.is( ':visible' ), 'contentRow is visible' );

		$collapsible.on( 'afterCollapse.mw-collapsible', function () {
			assert.assertTrue( $headerRow.is( ':visible' ), 'after collapsing: headerRow is still visible' );
			assert.assertTrue( $contentRow.is( ':hidden' ), 'after collapsing: contentRow is hidden' );

			$collapsible.on( 'afterExpand.mw-collapsible', function () {
				assert.assertTrue( $headerRow.is( ':visible' ), 'after expanding: headerRow is still visible' );
				assert.assertTrue( $contentRow.is( ':visible' ), 'after expanding: contentRow is visible' );
			} );

			$toggle.trigger( 'click' );
		} );

		$toggle.trigger( 'click' );
	} );

	function tableWithCaptionTest( $collapsible, test, assert ) {
		var $caption = $collapsible.find( 'caption' ),
			$headerRow = $collapsible.find( 'tr:first' ),
			$contentRow = $collapsible.find( 'tr:last' ),
			$toggle = $caption.find( '.mw-collapsible-toggle' );

		assert.equal( $toggle.length, 1, 'toggle is added to the end of the caption' );

		assert.assertTrue( $caption.is( ':visible' ), 'caption is visible' );
		assert.assertTrue( $headerRow.is( ':visible' ), 'headerRow is visible' );
		assert.assertTrue( $contentRow.is( ':visible' ), 'contentRow is visible' );

		$collapsible.on( 'afterCollapse.mw-collapsible', function () {
			assert.assertTrue( $caption.is( ':visible' ), 'after collapsing: caption is still visible' );
			assert.assertTrue( $headerRow.is( ':hidden' ), 'after collapsing: headerRow is hidden' );
			assert.assertTrue( $contentRow.is( ':hidden' ), 'after collapsing: contentRow is hidden' );

			$collapsible.on( 'afterExpand.mw-collapsible', function () {
				assert.assertTrue( $caption.is( ':visible' ), 'after expanding: caption is still visible' );
				assert.assertTrue( $headerRow.is( ':visible' ), 'after expanding: headerRow is visible' );
				assert.assertTrue( $contentRow.is( ':visible' ), 'after expanding: contentRow is visible' );
			} );

			$toggle.trigger( 'click' );
		} );

		$toggle.trigger( 'click' );
	}

	QUnit.test( 'basic operation (<table> with caption)', function ( assert ) {
		tableWithCaptionTest( prepareCollapsible(
			'<table class="mw-collapsible">' +
				'<caption>' + loremIpsum + '</caption>' +
				'<tr><th>' + loremIpsum + '</th><th>' + loremIpsum + '</th></tr>' +
				'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
				'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
			'</table>'
		), this, assert );
	} );

	QUnit.test( 'basic operation (<table> with caption and <thead>)', function ( assert ) {
		tableWithCaptionTest( prepareCollapsible(
			'<table class="mw-collapsible">' +
				'<caption>' + loremIpsum + '</caption>' +
				'<thead><tr><th>' + loremIpsum + '</th><th>' + loremIpsum + '</th></tr></thead>' +
				'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
				'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
			'</table>'
		), this, assert );
	} );

	function listTest( listType, test, assert ) {
		var $collapsible = prepareCollapsible(
				'<' + listType + ' class="mw-collapsible">' +
					'<li>' + loremIpsum + '</li>' +
					'<li>' + loremIpsum + '</li>' +
				'</' + listType + '>'
			),
			$toggleItem = $collapsible.find( 'li.mw-collapsible-toggle-li:first-child' ),
			$contentItem = $collapsible.find( 'li:last' ),
			$toggle = $toggleItem.find( '.mw-collapsible-toggle' );

		assert.equal( $toggle.length, 1, 'toggle is present, added inside new zeroth list item' );

		assert.assertTrue( $toggleItem.is( ':visible' ), 'toggleItem is visible' );
		assert.assertTrue( $contentItem.is( ':visible' ), 'contentItem is visible' );

		$collapsible.on( 'afterCollapse.mw-collapsible', function () {
			assert.assertTrue( $toggleItem.is( ':visible' ), 'after collapsing: toggleItem is still visible' );
			assert.assertTrue( $contentItem.is( ':hidden' ), 'after collapsing: contentItem is hidden' );

			$collapsible.on( 'afterExpand.mw-collapsible', function () {
				assert.assertTrue( $toggleItem.is( ':visible' ), 'after expanding: toggleItem is still visible' );
				assert.assertTrue( $contentItem.is( ':visible' ), 'after expanding: contentItem is visible' );
			} );

			$toggle.trigger( 'click' );
		} );

		$toggle.trigger( 'click' );
	}

	QUnit.test( 'basic operation (<ul>)', function ( assert ) {
		listTest( 'ul', this, assert );
	} );

	QUnit.test( 'basic operation (<ol>)', function ( assert ) {
		listTest( 'ol', this, assert );
	} );

	QUnit.test( 'basic operation when synchronous (options.instantHide)', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div class="mw-collapsible">' + loremIpsum + '</div>',
				{ instantHide: true }
			),
			$content = $collapsible.find( '.mw-collapsible-content' );

		assert.assertTrue( $content.is( ':visible' ), 'content is visible' );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );

		assert.assertTrue( $content.is( ':hidden' ), 'after collapsing: content is hidden' );
	} );

	QUnit.test( 'mw-made-collapsible data added', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div>' + loremIpsum + '</div>'
			);

		assert.equal( $collapsible.data( 'mw-made-collapsible' ), true, 'mw-made-collapsible data present' );
	} );

	QUnit.test( 'mw-collapsible added when missing', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div>' + loremIpsum + '</div>'
			);

		assert.assertTrue( $collapsible.hasClass( 'mw-collapsible' ), 'mw-collapsible class present' );
	} );

	QUnit.test( 'mw-collapsed added when missing', function ( assert ) {
		var $collapsible = prepareCollapsible(
			'<div>' + loremIpsum + '</div>',
				{ collapsed: true }
			);

		assert.assertTrue( $collapsible.hasClass( 'mw-collapsed' ), 'mw-collapsed class present' );
	} );

	QUnit.test( 'initial collapse (mw-collapsed class)', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div class="mw-collapsible mw-collapsed">' + loremIpsum + '</div>'
			),
			$content = $collapsible.find( '.mw-collapsible-content' );

		// Synchronous - mw-collapsed should cause instantHide: true to be used on initial collapsing
		assert.assertTrue( $content.is( ':hidden' ), 'content is hidden' );

		$collapsible.on( 'afterExpand.mw-collapsible', function () {
			assert.assertTrue( $content.is( ':visible' ), 'after expanding: content is visible' );
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'initial collapse (options.collapsed)', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div class="mw-collapsible">' + loremIpsum + '</div>',
				{ collapsed: true }
			),
			$content = $collapsible.find( '.mw-collapsible-content' );

		// Synchronous - collapsed: true should cause instantHide: true to be used on initial collapsing
		assert.assertTrue( $content.is( ':hidden' ), 'content is hidden' );

		$collapsible.on( 'afterExpand.mw-collapsible', function () {
			assert.assertTrue( $content.is( ':visible' ), 'after expanding: content is visible' );
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'clicks on links inside toggler pass through (options.linksPassthru)', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div class="mw-collapsible">' +
					'<div class="mw-collapsible-toggle">' +
						'Toggle <a href="#top">toggle</a> toggle <b>toggle</b>' +
					'</div>' +
					'<div class="mw-collapsible-content">' + loremIpsum + '</div>' +
				'</div>',
				// Can't do asynchronous because we're testing that the event *doesn't* happen
				{ instantHide: true }
			),
			$content = $collapsible.find( '.mw-collapsible-content' );

		$collapsible.find( '.mw-collapsible-toggle a' ).trigger( 'click' );
		assert.assertTrue( $content.is( ':visible' ), 'click event on link inside toggle passes through (content not toggled)' );

		$collapsible.find( '.mw-collapsible-toggle b' ).trigger( 'click' );
		assert.assertTrue( $content.is( ':hidden' ), 'click event on non-link inside toggle toggles content' );
	} );

	QUnit.test( 'collapse/expand text (data-collapsetext, data-expandtext)', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div class="mw-collapsible" data-collapsetext="Collapse me!" data-expandtext="Expand me!">' +
					loremIpsum +
				'</div>'
			),
			$toggleLink = $collapsible.find( '.mw-collapsible-toggle a' );

		assert.equal( $toggleLink.text(), 'Collapse me!', 'data-collapsetext is respected' );

		$collapsible.on( 'afterCollapse.mw-collapsible', function () {
			assert.equal( $toggleLink.text(), 'Expand me!', 'data-expandtext is respected' );
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'collapse/expand text (options.collapseText, options.expandText)', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div class="mw-collapsible">' + loremIpsum + '</div>',
				{ collapseText: 'Collapse me!', expandText: 'Expand me!' }
			),
			$toggleLink = $collapsible.find( '.mw-collapsible-toggle a' );

		assert.equal( $toggleLink.text(), 'Collapse me!', 'options.collapseText is respected' );

		$collapsible.on( 'afterCollapse.mw-collapsible', function () {
			assert.equal( $toggleLink.text(), 'Expand me!', 'options.expandText is respected' );
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'cloned collapsibles can be made collapsible again', function ( assert ) {
		var $collapsible = prepareCollapsible(
				'<div class="mw-collapsible">' + loremIpsum + '</div>'
			),
			$clone = $collapsible.clone() // clone without data and events
				.appendTo( '#qunit-fixture' ).makeCollapsible(),
			$content = $clone.find( '.mw-collapsible-content' );

		assert.assertTrue( $content.is( ':visible' ), 'content is visible' );

		$clone.on( 'afterCollapse.mw-collapsible', function () {
			assert.assertTrue( $content.is( ':hidden' ), 'after collapsing: content is hidden' );
		} );

		$clone.find( '.mw-collapsible-toggle a' ).trigger( 'click' );
	} );
}( mediaWiki, jQuery ) );
