/* eslint-disable no-jquery/no-class-state */
QUnit.module( 'jquery.makeCollapsible', () => {
	const loremIpsum = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.';

	/**
	 * @param {string} html
	 * @param {Object|undefined} [options]
	 * @return {jQuery}
	 */
	function prepareCollapsible( html, options ) {
		return $( $.parseHTML( html ) )
			.appendTo( '#qunit-fixture' )
			.makeCollapsible( options );
	}

	// Check events first, because most other tests here assume that the events work correctly.
	QUnit.test( 'testing hooks/triggers', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>'
		);
		const $content = $collapsible.find( '.mw-collapsible-content' );
		const $toggle = $collapsible.find( '.mw-collapsible-toggle' );
		const seq = [];
		$collapsible.on( 'beforeCollapse.mw-collapsible', () => {
			seq.push( {
				beforeCollapse: $content.css( 'display' )
			} );
		} );
		$collapsible.on( 'afterCollapse.mw-collapsible', () => {
			seq.push( {
				afterCollapse: $content.css( 'display' )
			} );
		} );
		$collapsible.on( 'beforeExpand.mw-collapsible', () => {
			seq.push( {
				beforeExpand: $content.css( 'display' )
			} );
		} );
		$collapsible.on( 'afterExpand.mw-collapsible', () => {
			seq.push( {
				afterExpand: $content.css( 'display' )
			} );
		} );
		$toggle.trigger( 'click' ); // Click to collapse
		$toggle.trigger( 'click' ); // Click to expand

		// The element starts in expanded form by default.
		// In one full collapse-expand cycle, each event must fired once in exactly this order.
		assert.deepEqual( seq, [
			{ beforeCollapse: 'block' }, // content is visible
			{ afterCollapse: 'none' }, // content is hidden
			{ beforeExpand: 'none' },
			{ afterExpand: 'block' }
		] );
	} );

	QUnit.test( 'basic div operation', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>'
		);
		const $content = $collapsible.find( '.mw-collapsible-content' );
		const $toggle = $collapsible.find( '.mw-collapsible-toggle' );
		const seq = [
			{ init: $content.css( 'display' ) }
		];
		$collapsible.on( 'afterCollapse.mw-collapsible', () => seq.push( { afterCollapse: $content.css( 'display' ) } ) );
		$collapsible.on( 'afterExpand.mw-collapsible', () => seq.push( { afterExpand: $content.css( 'display' ) } ) );

		$toggle.trigger( 'click' ); // Collapse
		$toggle.trigger( 'click' ); // Expand

		assert.strictEqual( $content.length, 1, 'content is present' );
		assert.strictEqual( $content.find( $toggle ).length, 0, 'toggle is not a descendant of content' );
		assert.deepEqual( seq, [
			{ init: 'block' },
			{ afterCollapse: 'none' },
			{ afterExpand: 'block' }
		] );
	} );

	QUnit.test( 'basic table operation', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<table class="mw-collapsible">' +
				'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
				'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
				'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
			'</table>'
		);
		const $header = $collapsible.find( 'tr' ).first();
		const $content = $collapsible.find( 'tr' ).last();
		const $toggle = $header.find( 'td' ).last().find( '.mw-collapsible-toggle' );
		const seq = [
			'init', { header: $header.css( 'display' ), content: $content.css( 'display' ) }
		];
		$collapsible.on( 'afterCollapse.mw-collapsible', () => seq.push( 'afterCollapse', { header: $header.css( 'display' ), content: $content.css( 'display' ) } ) );
		$collapsible.on( 'afterExpand.mw-collapsible', () => seq.push( 'afterExpand', { header: $header.css( 'display' ), content: $content.css( 'display' ) } ) );

		$toggle.trigger( 'click' ); // Collapse
		$toggle.trigger( 'click' ); // Expand

		assert.strictEqual( $toggle.length, 1, 'toggle is added to last cell of first row' );
		assert.deepEqual( seq, [
			'init', { header: 'table-row', content: 'table-row' },
			// headerRow is visible, contentRow is hidden
			'afterCollapse', { header: 'table-row', content: 'none' },
			// headerRow is still visible, contentRow is visible now
			'afterExpand', { header: 'table-row', content: 'table-row' }
		] );
	} );

	QUnit.test.each( 'table support', {
		'table with caption': '<table class="mw-collapsible">' +
			'<caption>' + loremIpsum + '</caption>' +
			'<tr><th>' + loremIpsum + '</th><th>' + loremIpsum + '</th></tr>' +
			'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
			'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
			'</table>',
		'table with caption and thead': '<table class="mw-collapsible">' +
			'<caption>' + loremIpsum + '</caption>' +
			'<thead><tr><th>' + loremIpsum + '</th><th>' + loremIpsum + '</th></tr></thead>' +
			'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
			'<tr><td>' + loremIpsum + '</td><td>' + loremIpsum + '</td></tr>' +
		'</table>'
	}, ( assert, html ) => {
		const $collapsible = prepareCollapsible( html );
		const $caption = $collapsible.find( 'caption' );
		const $header = $collapsible.find( 'tr' ).first();
		const $content = $collapsible.find( 'tr' ).last();
		const $toggle = $caption.find( '.mw-collapsible-toggle' );
		const seq = [
			'init', {
				caption: $caption.css( 'display' ),
				header: $header.css( 'display' ),
				content: $content.css( 'display' )
			}
		];
		$collapsible.on( 'afterCollapse.mw-collapsible', () => seq.push( 'afterCollapse', {
			caption: $caption.css( 'display' ),
			header: $header.css( 'display' ),
			content: $content.css( 'display' )
		} ) );
		$collapsible.on( 'afterExpand.mw-collapsible', () => seq.push( 'afterExpand', {
			caption: $caption.css( 'display' ),
			header: $header.css( 'display' ),
			content: $content.css( 'display' )
		} ) );
		$toggle.trigger( 'click' );
		$toggle.trigger( 'click' );

		assert.strictEqual( $toggle.length, 1, 'toggle is added to the end of the caption' );
		assert.deepEqual( seq, [
			'init', { caption: 'table-caption', header: 'table-row', content: 'table-row' },
			// after collapsing: caption is still visible, rest hidden
			'afterCollapse', { caption: 'table-caption', header: 'none', content: 'none' },
			// after expanding: all visible
			'afterExpand', { caption: 'table-caption', header: 'table-row', content: 'table-row' }
		] );
	} );

	QUnit.test.each( 'basic list operation', { ul: 'ul', ol: 'ol' }, ( assert, listType ) => {
		const $collapsible = prepareCollapsible(
			'<' + listType + ' class="mw-collapsible">' +
				'<li>' + loremIpsum + '</li>' +
				'<li>' + loremIpsum + '</li>' +
			'</' + listType + '>'
		);
		const $toggleItem = $collapsible.find( 'li.mw-collapsible-toggle-li:first-child' );
		const $content = $collapsible.find( 'li' ).last();
		const $toggle = $toggleItem.find( '.mw-collapsible-toggle' );
		const seq = [
			'init', { toggle: $toggleItem.css( 'display' ), content: $content.css( 'display' ) }
		];
		$collapsible.on( 'afterCollapse.mw-collapsible', () => seq.push( 'afterCollapse', { toggle: $toggleItem.css( 'display' ), content: $content.css( 'display' ) } ) );
		$collapsible.on( 'afterExpand.mw-collapsible', () => seq.push( 'afterExpand', { toggle: $toggleItem.css( 'display' ), content: $content.css( 'display' ) } ) );

		$toggle.trigger( 'click' );
		$toggle.trigger( 'click' );

		assert.strictEqual( $toggle.length, 1, 'toggle is present, added inside new zeroth list item' );
		assert.deepEqual( seq, [
			'init', { toggle: 'list-item', content: 'list-item' },
			// only the content gets hidden
			'afterCollapse', { toggle: 'list-item', content: 'none' },
			'afterExpand', { toggle: 'list-item', content: 'list-item' }
		] );
	} );

	QUnit.test( 'basic operation when synchronous (options.instantHide)', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>',
			{ instantHide: true }
		);

		const $content = $collapsible.find( '.mw-collapsible-content' );
		assert.notStrictEqual( $content.css( 'display' ), 'none', 'content is visible' );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
		assert.strictEqual( $content.css( 'display' ), 'none', 'after collapsing: content is hidden' );
	} );

	QUnit.test( 'mw-made-collapsible data added', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div>' + loremIpsum + '</div>'
		);

		assert.true( $collapsible.data( 'mw-made-collapsible' ), 'mw-made-collapsible data present' );
	} );

	QUnit.test( 'mw-collapsible added when missing', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div>' + loremIpsum + '</div>'
		);

		assert.true( $collapsible.hasClass( 'mw-collapsible' ), 'mw-collapsible class present' );
	} );

	QUnit.test( 'mw-collapsed added when missing', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div>' + loremIpsum + '</div>',
			{ collapsed: true }
		);

		assert.true( $collapsible.hasClass( 'mw-collapsed' ), 'mw-collapsed class present' );
	} );

	QUnit.test( 'initial collapse (mw-collapsed class)', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible mw-collapsed">' + loremIpsum + '</div>'
		);
		const $content = $collapsible.find( '.mw-collapsible-content' );

		// Synchronous - mw-collapsed should cause instantHide: true to be used on initial collapsing
		assert.strictEqual( $content.css( 'display' ), 'none', 'content is hidden' );

		$collapsible.on( 'afterExpand.mw-collapsible', () => {
			assert.notStrictEqual( $content.css( 'display' ), 'none', 'after expanding: content is visible' );
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'initial collapse (options.collapsed)', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>',
			{ collapsed: true }
		);
		const $content = $collapsible.find( '.mw-collapsible-content' );

		// Synchronous - collapsed: true should cause instantHide: true to be used on initial collapsing
		assert.strictEqual( $content.css( 'display' ), 'none', 'content is hidden' );

		$collapsible.on( 'afterExpand.mw-collapsible', () => {
			assert.notStrictEqual( $content.css( 'display' ), 'none', 'after expanding: content is visible' );
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'clicks on links inside toggler pass through', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' +
				'<div class="mw-collapsible-toggle">' +
					'Toggle <a href="#">toggle</a> toggle <b>toggle</b>' +
				'</div>' +
				'<div class="mw-collapsible-content">' + loremIpsum + '</div>' +
			'</div>',
			// Can't do asynchronous because we're testing that the event *doesn't* happen
			{ instantHide: true }
		);
		const $content = $collapsible.find( '.mw-collapsible-content' );

		$collapsible.find( '.mw-collapsible-toggle a' ).trigger( 'click' );
		assert.notStrictEqual( $content.css( 'display' ), 'none', 'click event on link inside toggle passes through (content not toggled)' );

		$collapsible.find( '.mw-collapsible-toggle b' ).trigger( 'click' );
		assert.strictEqual( $content.css( 'display' ), 'none', 'click event on non-link inside toggle toggles content' );
	} );

	QUnit.test( 'click on non-link inside toggler counts as trigger', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' +
				'<div class="mw-collapsible-toggle">' +
					'Toggle <a>toggle</a> toggle <b>toggle</b>' +
				'</div>' +
				'<div class="mw-collapsible-content">' + loremIpsum + '</div>' +
			'</div>',
			{ instantHide: true }
		);
		const $content = $collapsible.find( '.mw-collapsible-content' );

		$collapsible.find( '.mw-collapsible-toggle a' ).trigger( 'click' );
		assert.strictEqual( $content.css( 'display' ), 'none', 'click event on link (with no href) inside toggle toggles content' );
	} );

	QUnit.test( 'collapse/expand text (data-collapsetext, data-expandtext)', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible" data-collapsetext="Collapse me!" data-expandtext="Expand me!">' +
				loremIpsum +
			'</div>'
		);
		const $toggleText = $collapsible.find( '.mw-collapsible-text' );

		assert.strictEqual( $toggleText.text(), 'Collapse me!', 'data-collapsetext is respected' );

		$collapsible.on( 'afterCollapse.mw-collapsible', () => {
			assert.strictEqual( $toggleText.text(), 'Expand me!', 'data-expandtext is respected' );
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'collapse/expand text (options.collapseText, options.expandText)', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>',
			{ collapseText: 'Collapse me!', expandText: 'Expand me!' }
		);
		const $toggleText = $collapsible.find( '.mw-collapsible-text' );

		assert.strictEqual( $toggleText.text(), 'Collapse me!', 'options.collapseText is respected' );

		$collapsible.on( 'afterCollapse.mw-collapsible', () => {
			assert.strictEqual( $toggleText.text(), 'Expand me!', 'options.expandText is respected' );
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'predefined toggle button and text (.mw-collapsible-toggle/.mw-collapsible-text)', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' +
				'<div class="mw-collapsible-toggle">' +
					'<span>[</span><span class="mw-collapsible-text">Toggle</span><span>]</span>' +
				'</div>' +
				'<div class="mw-collapsible-content">' + loremIpsum + '</div>' +
			'</div>',
			{ collapseText: 'Hide', expandText: 'Show' }
		);

		const $toggleText = $collapsible.find( '.mw-collapsible-text' );
		assert.strictEqual( $toggleText.text(), 'Toggle', 'predefined text remains' );

		$collapsible.on( 'afterCollapse.mw-collapsible', () => {
			assert.strictEqual( $toggleText.text(), 'Show', 'predefined text is toggled' );
		} );
		$collapsible.on( 'afterExpand.mw-collapsible', () => {
			assert.strictEqual( $toggleText.text(), 'Hide', 'predefined text is toggled back' );
		} );

		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
		$collapsible.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'cloned collapsibles can be made collapsible again', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>'
		);

		// clone without data and events
		const $clone = $collapsible.clone().appendTo( '#qunit-fixture' )
			.makeCollapsible();
		const $content = $clone.find( '.mw-collapsible-content' );
		assert.notStrictEqual( $content.css( 'display' ), 'none', 'content is visible' );

		$clone.on( 'afterCollapse.mw-collapsible', () => {
			assert.strictEqual( $content.css( 'display' ), 'none', 'after collapsing: content is hidden' );
		} );

		$clone.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'collapsibles in cloned elements are controlled by toggle clones', ( assert ) => {
		const $original = $( $.parseHTML( (
			'<div>' +
			'<div class="mw-collapsible mw-collapsed">' + loremIpsum + '</div>' +
			'</div>'
		) ) ).appendTo( '#qunit-fixture' );
		const $originalCollapsible = $original.find( '.mw-collapsible' )
			.makeCollapsible();
		const $originalContent = $original.find( '.mw-collapsible-content' );

		// clone with data and events
		const $clone = $original.clone( true ).appendTo( '#qunit-fixture' );
		const $cloneCollapsible = $clone.find( '.mw-collapsible' );
		const $cloneContent = $clone.find( '.mw-collapsible-content' );
		assert.strictEqual( $cloneContent.css( 'display' ), 'none', 'clone of content is hidden' );

		$cloneCollapsible.add( $originalCollapsible ).on( 'afterExpand.mw-collapsible', () => {
			assert.notStrictEqual( $cloneContent.css( 'display' ), 'none', 'after expanding clone: clone of content is visible' );
			assert.strictEqual( $originalContent.css( 'display' ), 'none', 'after expanding clone: original content is hidden' );
		} );

		$clone.find( '.mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'reveal hash fragment', ( assert ) => {
		const $collapsible = prepareCollapsible(
			'<div class="mw-collapsible mw-collapsed">' + loremIpsum + '<div id="español,a:nth-child(even)">' + loremIpsum + '</div></div>'
		);
		const fragment = document.getElementById( 'español,a:nth-child(even)' );
		const done = assert.async();

		assert.strictEqual( fragment.offsetParent, null, 'initial: fragment is hidden' );

		$collapsible.on( 'afterExpand.mw-collapsible', () => {
			assert.notStrictEqual( fragment.offsetParent, null, 'after hash change: fragment is visible' );
			done();
			window.location.hash = '';
		} );

		window.location.hash = 'espa%C3%B1ol,a:nth-child(even)';
	} );

	QUnit.test( 'T168689 - nested collapsible divs should keep independent state', ( assert ) => {
		const $collapsible1 = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>'
		);
		const $collapsible2 = prepareCollapsible(
			'<div class="mw-collapsible">' + loremIpsum + '</div>'
		);

		$collapsible1
			.append( $collapsible2 )
			.appendTo( '#qunit-fixture' ).makeCollapsible();

		$collapsible1.on( 'afterCollapse.mw-collapsible', () => {
			assert.true( $collapsible1.hasClass( 'mw-collapsed' ), 'after collapsing: parent is collapsed' );
			assert.false( $collapsible2.hasClass( 'mw-collapsed' ), 'after collapsing: child is not collapsed' );
			assert.true( $collapsible1.find( '> .mw-collapsible-toggle' ).hasClass( 'mw-collapsible-toggle-collapsed' ) );
			assert.false( $collapsible2.find( '> .mw-collapsible-toggle' ).hasClass( 'mw-collapsible-toggle-collapsed' ) );
		} ).find( '> .mw-collapsible-toggle' ).trigger( 'click' );
	} );

	QUnit.test( 'placeholder element for toggle', ( assert ) => {
		const $collapsibleOuter = $( $.parseHTML(
			'<div id="outer" class="mw-collapsible">' +
				'<div class="custom-wrapper">' +
					'<div class="mw-collapsible-toggle-placeholder"></div>' +
				'</div>' +
				'<div class="mw-collapsible-content">' +
					'<div id="inner" class="mw-collapsible">' +
						'<div class="custom-wrapper">' +
							'<div class="mw-collapsible-toggle-placeholder"></div>' +
						'</div>' +
						'<div class="mw-collapsible-content">' +
							loremIpsum +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>'
		) );
		const $collapsibleInner = $collapsibleOuter.find( '#inner' );
		$collapsibleOuter.appendTo( '#qunit-fixture' );
		// make both elements collapsible at once, instead of using prepareCollapsible(),
		// to better match mediawiki.page.ready and test that makeCollapsible() still keeps the collapsible separate
		$( '#qunit-fixture' ).find( '.mw-collapsible' ).makeCollapsible();

		assert.strictEqual( $collapsibleOuter.find( '.mw-collapsible-toggle' ).length, 2, 'two toggles' );
		const $toggleOuter = $collapsibleOuter.find( '.mw-collapsible-toggle' ).first();
		const $toggleInner = $collapsibleInner.find( '.mw-collapsible-toggle' );
		assert.true( $toggleOuter.parent().hasClass( 'custom-wrapper' ), 'toggle inside custom wrapper' );
		assert.true( $toggleInner.parent().hasClass( 'custom-wrapper' ), 'toggle inside custom wrapper' );
		$collapsibleInner.on( 'afterCollapse.mw-collapsible', () => {
			assert.true( $collapsibleInner.hasClass( 'mw-collapsed' ), 'after collapsing: inner is collapsed' );
			assert.false( $collapsibleOuter.hasClass( 'mw-collapsed' ), 'after collapsing: outer is not collapsed' );
			assert.true( $toggleInner.hasClass( 'mw-collapsible-toggle-collapsed' ) );
			assert.false( $toggleOuter.hasClass( 'mw-collapsible-toggle-collapsed' ) );
		} );
		$toggleInner.find( 'a' ).trigger( 'click' );
	} );

	QUnit.test( 'T364712 - toggle moved outside of collapsible should still work', ( assert ) => {
		const $wrapper = $( $.parseHTML( (
			'<div>' +
				'<p>' + loremIpsum + '</p>' +
				'<dl>' +
					'<dt>' + loremIpsum + '</dt>' +
					'<dd>' + loremIpsum + '</dd>' +
				'</dl>' +
			'</div>'
		) ) ).appendTo( '#qunit-fixture' );

		const $collapsible = $wrapper.find( 'dl' ).makeCollapsible( {
			collapsed: true
		} );
		$collapsible.children( '.mw-collapsible-toggle' ).each( function () {
			const $this = $( this );
			$this.parent().prev( 'p' ).append( $this );
		} );

		const $p = $wrapper.find( 'p' );
		const $toggle = $p.find( '.mw-collapsible-toggle' );
		const $content = $collapsible.find( '.mw-collapsible-content' );

		assert.strictEqual( $toggle.length, 1, 'toggle moved to p' );
		assert.strictEqual( $content.length, 1, 'content exists' );

		assert.true( $collapsible.hasClass( 'mw-collapsed' ) );
		assert.strictEqual( $content.css( 'display' ), 'none', 'content is hidden' );

		$toggle.trigger( 'click' );

		assert.false( $collapsible.hasClass( 'mw-collapsed' ), 'after expanding: toggle works after move' );
		assert.notStrictEqual( $content.css( 'display' ), 'none', 'after expanding: content is visible' );
	} );
} );
