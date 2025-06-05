QUnit.module( 'mediawiki.util', QUnit.newMwEnvironment( {
	messages: {
		// Used by accessKeyLabel in test for addPortletLink
		brackets: '[$1]',
		'word-separator': ' '
	}
} ), ( hooks ) => {
	const util = require( 'mediawiki.util' );

	hooks.beforeEach( function () {
		this.sandbox.useFakeTimers();

		$.fn.updateTooltipAccessKeys.setTestMode( true );
		mw.util.setOptionsForTest( {
			FragmentMode: [ 'legacy', 'html5' ],
			LoadScript: '/w/load.php'
		} );
	} );
	hooks.afterEach( () => {
		$.fn.updateTooltipAccessKeys.setTestMode( false );
		mw.util.setOptionsForTest();
	} );

	QUnit.test( 'rawurlencode', ( assert ) => {
		assert.strictEqual( util.rawurlencode( 'Test:A & B/Here' ), 'Test%3AA%20%26%20B%2FHere' );
	} );

	QUnit.test( 'escapeIdForAttribute', ( assert ) => {
		// Test cases are kept in sync with SanitizerTest.php
		const text = 'foo тест_#%!\'()[]:<>',
			legacyEncoded = 'foo_.D1.82.D0.B5.D1.81.D1.82_.23.25.21.27.28.29.5B.5D:.3C.3E',
			html5Encoded = 'foo_тест_#%!\'()[]:<>',
			// Settings: this is $wgFragmentMode
			legacy = [ 'legacy' ],
			legacyNew = [ 'legacy', 'html5' ],
			newLegacy = [ 'html5', 'legacy' ],
			allNew = [ 'html5' ];

		// Test cases are kept in sync with SanitizerTest.php
		[
			// Pure legacy: how MW worked before 2017
			[ legacy, text, legacyEncoded ],
			// Transition to a new world: legacy links with HTML5 fallback
			[ legacyNew, text, legacyEncoded ],
			// New world: HTML5 links, legacy fallbacks
			[ newLegacy, text, html5Encoded ],
			// Distant future: no legacy fallbacks
			[ allNew, text, html5Encoded ]
		].forEach( ( testCase ) => {
			mw.util.setOptionsForTest( { FragmentMode: testCase[ 0 ] } );

			assert.strictEqual( util.escapeIdForAttribute( testCase[ 1 ] ), testCase[ 2 ] );
		} );
	} );

	QUnit.test( 'escapeIdForLink', ( assert ) => {
		// Test cases are kept in sync with SanitizerTest.php
		const text = 'foo тест_#%!\'()[]:<>',
			legacyEncoded = 'foo_.D1.82.D0.B5.D1.81.D1.82_.23.25.21.27.28.29.5B.5D:.3C.3E',
			html5Encoded = 'foo_тест_#%!\'()[]:<>',
			// Settings: this is wgFragmentMode
			legacy = [ 'legacy' ],
			legacyNew = [ 'legacy', 'html5' ],
			newLegacy = [ 'html5', 'legacy' ],
			allNew = [ 'html5' ];

		[
			// Pure legacy: how MW worked before 2017
			[ legacy, text, legacyEncoded ],
			// Transition to a new world: legacy links with HTML5 fallback
			[ legacyNew, text, legacyEncoded ],
			// New world: HTML5 links, legacy fallbacks
			[ newLegacy, text, html5Encoded ],
			// Distant future: no legacy fallbacks
			[ allNew, text, html5Encoded ]
		].forEach( ( testCase ) => {
			mw.util.setOptionsForTest( { FragmentMode: testCase[ 0 ] } );

			assert.strictEqual( util.escapeIdForLink( testCase[ 1 ] ), testCase[ 2 ] );
		} );
	} );

	QUnit.test.each( 'percentDecodeFragment', [
		[ '', '' ],
		[ 'foo+bar', 'foo+bar' ],
		[ 'foo bar', 'foo bar' ],
		[ 'foo%2Bbar', 'foo+bar' ],
		[ 'foo%20bar', 'foo bar' ],
		[ 'foo%20bar%A', 'foo bar%A' ],
		[ 'Romeo+Juliet_%A_Ó_%2520', 'Romeo+Juliet_%A_Ó_%20' ],
		[ 'Romeo&Juliet=%A', 'Romeo&Juliet=%A' ],
		[ 'Romeo&Juliet=%20', 'Romeo&Juliet= ' ],
		[ '%2B%26%3D%23', '+&=#' ],
		[ '===', '===' ],
		[ '&&&', '&&&' ],
		[ '###', '###' ]
	], ( assert, data ) => {
		assert.strictEqual( util.percentDecodeFragment( data[ 0 ] ), data[ 1 ], data[ 0 ] );
	} );

	QUnit.test.each( 'wikiUrlencode', [
		[ 'Test:A & B/Here', 'Test:A_%26_B/Here' ],

		// NOTE: Keep in sync with wfUrlencodeTest.php#provideURLS
		[ '+', '%2B' ],
		[ '&', '%26' ],
		[ '=', '%3D' ],
		[ ':', ':' ],
		[ '/', '/' ],
		[ '~', '~' ],
		[ '\'', '%27' ],
		[ '[]', '%5B%5D' ],
		[ '<>', '%3C%3E' ],
		[
			';@$-_.!*()',
			';@$-_.!*()'
		]
	], ( assert, data ) => {
		assert.strictEqual( util.wikiUrlencode( data[ 0 ] ), data[ 1 ], data[ 0 ] );
	} );

	QUnit.test( 'getUrl', ( assert ) => {
		let href;
		mw.config.set( {
			wgScript: '/w/index.php',
			wgArticlePath: '/wiki/$1',
			wgPageName: 'Foobar'
		} );

		href = util.getUrl( 'Sandbox' );
		assert.strictEqual( href, '/wiki/Sandbox', 'simple title' );

		href = util.getUrl( 'Foo:Sandbox? 5+5=10! (test)/sub ' );
		assert.strictEqual( href, '/wiki/Foo:Sandbox%3F_5%2B5%3D10!_(test)/sub_', 'complex title' );

		// T149767
		href = util.getUrl( 'My$$test$$$$$title' );
		assert.strictEqual( href, '/wiki/My$$test$$$$$title', 'title with multiple consecutive dollar signs' );

		href = util.getUrl();
		assert.strictEqual( href, '/wiki/Foobar', 'default title' );

		href = util.getUrl( null, { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Foobar&action=edit', 'default title with query string' );

		href = util.getUrl( 'Sandbox', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Sandbox&action=edit', 'simple title with query string' );

		// Test fragments
		href = util.getUrl( 'Foo:Sandbox#Fragment', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Foo:Sandbox&action=edit#Fragment', 'namespaced title with query string and fragment' );

		href = util.getUrl( 'Sandbox#', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Sandbox&action=edit', 'title with query string and empty fragment' );

		href = util.getUrl( 'Sandbox', {} );
		assert.strictEqual( href, '/wiki/Sandbox', 'title with empty query string' );

		href = util.getUrl( '#Fragment' );
		assert.strictEqual( href, '#Fragment', 'empty title with fragment' );

		href = util.getUrl( '#Fragment', { action: 'edit' } );
		assert.strictEqual( href, '#Fragment', 'empty title with query string and fragment' );

		mw.util.setOptionsForTest( { FragmentMode: [ 'legacy' ] } );
		href = util.getUrl( 'Foo:Sandbox \xC4#Fragment \xC4', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Foo:Sandbox_%C3%84&action=edit#Fragment_.C3.84', 'title with query string, fragment, and special characters' );

		mw.util.setOptionsForTest( { FragmentMode: [ 'html5' ] } );
		href = util.getUrl( 'Foo:Sandbox \xC4#Fragment \xC4', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Foo:Sandbox_%C3%84&action=edit#Fragment_Ä', 'title with query string, fragment, and special characters' );

		href = util.getUrl( 'Foo:%23#Fragment', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Foo:%2523&action=edit#Fragment', 'title containing %23 (#), fragment, and a query string' );

		mw.util.setOptionsForTest( { FragmentMode: [ 'legacy' ] } );
		href = util.getUrl( 'Sandbox#+&=:;@$-_.!*/[]<>\'§', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Sandbox&action=edit#.2B.26.3D:.3B.40.24-_..21.2A.2F.5B.5D.3C.3E.27.C2.A7', 'fragment with various characters' );

		mw.util.setOptionsForTest( { FragmentMode: [ 'html5' ] } );
		href = util.getUrl( 'Sandbox#+&=:;@$-_.!*/[]<>\'§', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Sandbox&action=edit#+&=:;@$-_.!*/[]<>\'§', 'fragment with various characters' );
	} );

	QUnit.test( 'wikiScript', ( assert ) => {
		mw.util.setOptionsForTest( {
			LoadScript: '/w/l.php'
		} );
		mw.config.set( {
			// customized wgScript for T41103
			wgScript: '/w/i.php',
			wgScriptPath: '/w'
		} );

		assert.strictEqual( util.wikiScript(), '/w/i.php', 'default' );
		assert.strictEqual( util.wikiScript( 'index' ), '/w/i.php', 'index' );
		assert.strictEqual( util.wikiScript( 'load' ), '/w/l.php', 'load' );
		assert.strictEqual( util.wikiScript( 'api' ), '/w/api.php', 'api' );
		assert.strictEqual( util.wikiScript( 'rest' ), '/w/rest.php', 'rest' );
	} );

	QUnit.test( 'addCSS', ( assert ) => {
		const $el = $( '<div>' ).attr( 'id', 'mw-addcsstest' ).appendTo( '#qunit-fixture' );
		const style = util.addCSS( '#mw-addcsstest { visibility: hidden; }' );
		assert.strictEqual( typeof style, 'object', 'addCSS returned an object' );
		assert.false( style.disabled, 'property "disabled" is available and set to false' );

		assert.strictEqual( $el.css( 'visibility' ), 'hidden', 'Added style properties are in effect' );

		// Clean up
		$( style.ownerNode ).remove();
	} );

	QUnit.test( 'getParamValue', ( assert ) => {
		let url;

		url = 'http://example.org/?foo=wrong&foo=right#&foo=bad';
		assert.strictEqual( util.getParamValue( 'foo', url ), 'right', 'Use latest one, ignore hash' );
		assert.strictEqual( util.getParamValue( 'bar', url ), null, 'Return null when not found' );

		url = 'http://example.org/#&foo=bad';
		assert.strictEqual( util.getParamValue( 'foo', url ), null, 'Ignore hash if param is not in querystring but in hash (T29427)' );

		url = 'example.org?' + $.param( { TEST: 'a b+c' } );
		assert.strictEqual( util.getParamValue( 'TEST', url ), 'a b+c', 'T32441: getParamValue must understand "+" encoding of space' );

		url = 'example.org?' + $.param( { TEST: 'a b+c d' } ); // check for sloppy code from r95332 :)
		assert.strictEqual( util.getParamValue( 'TEST', url ), 'a b+c d', 'T32441: getParamValue must understand "+" encoding of space (multiple spaces)' );

		url = 'example.org?title=Autom%F3vil';
		assert.strictEqual( util.getParamValue( 'title', url ), null, 'T268058: getParamValue can return null on input it cannot decode.' );
	} );

	QUnit.test( 'getArrayParam', ( assert ) => {
		const params1 = new URLSearchParams( '?foo[]=a&foo[]=b&foo[]=c' );
		const params2 = new URLSearchParams( '?foo[0]=a&foo[1]=b&foo[2]=c' );
		const params3 = new URLSearchParams( '?foo[1]=b&foo[0]=a&foo[]=c' );
		const expected = [ 'a', 'b', 'c' ];
		assert.deepEqual( util.getArrayParam( 'foo', params1 ), expected,
			'array query parameters are parsed (implicit indexes)' );
		assert.deepEqual( util.getArrayParam( 'foo', params2 ), expected,
			'array query parameters are parsed (explicit indexes)' );
		assert.deepEqual( util.getArrayParam( 'foo', params3 ), expected,
			'array query parameters are parsed (mixed indexes, out of order)' );

		const paramsMissing = new URLSearchParams( '?foo[0]=a&foo[2]=c' );
		// eslint-disable-next-line no-sparse-arrays
		const expectedMissing = [ 'a', , 'c' ];
		assert.deepEqual( util.getArrayParam( 'foo', paramsMissing ), expectedMissing,
			'array query parameters are parsed (missing array item)' );

		const paramsWeird = new URLSearchParams( '?foo[0]=a&foo[1][1]=b&foo[x]=c' );
		const expectedWeird = [ 'a' ];
		assert.deepEqual( util.getArrayParam( 'foo', paramsWeird ), expectedWeird,
			'array query parameters are parsed (multi-dimensional or associative arrays are ignored)' );

		const paramsNotArray = new URLSearchParams( '?foo=a' );
		assert.deepEqual( util.getArrayParam( 'foo', paramsNotArray ), null,
			'non-array query parameters are ignored' );

		const paramsOther = new URLSearchParams( '?bar[]=a' );
		assert.deepEqual( util.getArrayParam( 'foo', paramsOther ), null,
			'other query parameters are ignored' );
	} );

	function getParents( link ) {
		return $( link ).parents( '#qunit-fixture *' ).toArray()
			.map( ( el ) => el.tagName + ( el.className && '.' + el.className ) + ( el.id && '#' + el.id ) );
	}

	QUnit.test( 'messageBox', ( assert ) => {
		const message = util.messageBox( 'test' );
		const errSpan = document.createElement( 'span' );
		errSpan.textContent = 'error';
		const errorMessage = util.messageBox( errSpan, 'error' );
		assert.strictEqual( message.querySelector( '.cdx-message__content' ).textContent, 'test' );
		assert.strictEqual( message.getAttribute( 'aria-live' ), 'polite' );
		assert.false( message.hasAttribute( 'role' ) );
		assert.strictEqual( errorMessage.querySelector( '.cdx-message__content span' ).textContent, 'error' );
		assert.strictEqual( errorMessage.getAttribute( 'role' ), 'alert' );
		assert.false( errorMessage.hasAttribute( 'aria-live' ) );
		assert.true( errorMessage.classList.contains( 'cdx-message--error' ) );
		const fragment = document.createDocumentFragment();
		fragment.appendChild( document.createTextNode( 'hello ' ) );
		fragment.appendChild( document.createTextNode( 'world!' ) );
		const warningMessage = util.messageBox( fragment, 'error' );
		assert.strictEqual( warningMessage.querySelector( '.cdx-message__content' ).textContent, 'hello world!' );
	} );

	QUnit.test( 'addPortlet does not append to DOM if no `before` is provided', ( assert ) => {
		$( '#qunit-fixture' ).html(
			'<div class="portlet" id="p-toolbox"></div>'
		);
		const portlet = util.addPortlet( 'test', 'Hello' );
		assert.true( portlet !== null, 'A portlet node is returned.' );
		assert.true( portlet.parentNode === null, 'Portlet has no parent node' );
	} );

	QUnit.test( 'addPortlet returns null if bad selector given', ( assert ) => {
		$( '#qunit-fixture' ).html(
			'<div class="portlet" id="p-toolbox"></div>'
		);
		const portlet = util.addPortlet( 'test', 'Hello', '#?saasp-toolbox' );
		assert.true( portlet === null, 'No portlet created.' );
	} );

	QUnit.test( 'addPortlet appends to DOM if before provided', ( assert ) => {
		$( '#qunit-fixture' ).html(
			'<div class="portlet" id="p-toolbox"></div>'
		);
		const portlet = util.addPortlet( 'test', 'Hello', '#p-toolbox' );
		assert.true( !!portlet, 'A portlet node is returned.' );
		assert.true( portlet.parentNode !== null, 'It is appended to the DOM' );
	} );

	QUnit.test( 'addPortletLink (Vector list)', ( assert ) => {
		$( '#qunit-fixture' ).html(
			'<div class="portlet" id="p-toolbox">' +
				'<h3>Tools</h3>' +
				'<div class="body"><ul></ul></div>' +
				'</div>'
		);
		const link = util.addPortletLink( 'p-toolbox', 'https://foo.test/',
			'Foo', 't-foo', 'Tooltip', 'l'
		);

		assert.domEqual(
			link,
			{
				tagName: 'LI',
				attributes: { id: 't-foo', class: 'mw-list-item mw-list-item-js' },
				contents: [
					{
						tagName: 'A',
						attributes: { href: 'https://foo.test/', title: 'Tooltip [test-l]', accesskey: 'l' },
						contents: [ 'Foo' ]
					}
				]
			},
			'Link element'
		);
		assert.propEqual(
			[ 'UL', 'DIV.body', 'DIV.portlet#p-toolbox' ],
			getParents( link ),
			'List structure'
		);
	} );

	QUnit.test( 'addPortletLink (Minerva list)', ( assert ) => {
		$( '#qunit-fixture' ).html( '<ul id="p-list"></ul>' );
		const link = util.addPortletLink( 'p-list', '#', 'Foo', 't-foo' );

		assert.domEqual(
			link,
			{
				tagName: 'LI',
				attributes: { id: 't-foo', class: 'mw-list-item mw-list-item-js' },
				contents: [
					{
						tagName: 'A',
						attributes: { href: '#' },
						contents: [ 'Foo' ]
					}
				]
			},
			'Link element'
		);
		assert.propEqual(
			getParents( link ),
			[ 'UL#p-list' ],
			'List structure'
		);
	} );

	QUnit.test( 'addPortletLink (nextNode option)', ( assert ) => {
		$( '#qunit-fixture' ).html( '<ul id="p-toolbox"></ul>' );
		const linkFoo = util.addPortletLink( 'p-toolbox', 'https://foo.test/',
			'Foo', 't-foo', 'Tooltip', 'l'
		);

		let link = util.addPortletLink( 'p-toolbox', '#',
			'Label', 't-node', null, null, linkFoo );
		assert.strictEqual( link.nextSibling, linkFoo, 'HTMLElement' );

		link = util.addPortletLink( 'p-toolbox', '#',
			'Label', 't-selector', null, null, '#t-foo' );
		assert.strictEqual( link.nextSibling, linkFoo, 'CSS selector' );

		link = util.addPortletLink( 'p-toolbox', '#',
			'Label', 't-jqueryobj', null, null, $( '#t-foo' ) );
		assert.strictEqual( link.nextSibling, linkFoo, 'jQuery object' );

		link = util.addPortletLink( 'p-toolbox', '#',
			'Label', 't-selector-unknown', null, null, '#t-nonexistent' );
		assert.strictEqual( link.nextSibling, null, 'non-matching CSS selector' );

		link = util.addPortletLink( 'p-toolbox', '#',
			'Label', 't-jqueryobj-empty', null, null, $( '#t-nonexistent' ) );
		assert.strictEqual( link.nextSibling, null, 'empty jQuery object' );
	} );

	QUnit.test( 'addPortletLink (accesskey option)', ( assert ) => {
		$( '#qunit-fixture' ).html( '<ul id="p-toolbox"></ul>' );

		const link = util.addPortletLink( 'p-toolbox', '#', 'Label', null, 'Tooltip [shift-x]', 'z' );
		assert.strictEqual(
			link.querySelector( 'a' ).title,
			'Tooltip [test-z]',
			'Change a pre-existing accesskey in a tooltip'
		);
	} );

	QUnit.test( 'addPortletLink (nested list)', ( assert ) => {
		// Regresion test for T37082
		$( '#qunit-fixture' ).html(
			'<ul id="p-toolbox">' +
				'<li id="x-foo"><a href="#">Foo</a></li>' +
				'<li id="x-bar"><ul><li id="quux"><a href="#">Quux</a></li></ul></li>' +
				'</ul>'
		);
		util.addPortletLink( 'p-toolbox', 'https://example.test/', 'Example' );

		assert.strictEqual(
			$( 'a[href="https://example.test/"]' ).length,
			1,
			'No duplicates created (T37082)'
		);
	} );

	QUnit.test( 'validateEmail', ( assert ) => {
		assert.strictEqual( util.validateEmail( '' ), null, 'Should return null for empty string ' );
		assert.true( util.validateEmail( 'user@localhost' ), 'Return true for a valid e-mail address' );

		// testEmailWithCommasAreInvalids
		assert.false( util.validateEmail( 'user,foo@example.org' ), 'Emails with commas are invalid' );
		assert.false( util.validateEmail( 'userfoo@ex,ample.org' ), 'Emails with commas are invalid' );

		// testEmailWithHyphens
		assert.true( util.validateEmail( 'user-foo@example.org' ), 'Emails may contain a hyphen' );
		assert.true( util.validateEmail( 'userfoo@ex-ample.org' ), 'Emails may contain a hyphen' );
	} );

	// Based on mediawiki/libs/IPUtils: provideInvalidIPv4Addresses
	QUnit.test.each( 'isIPv4Address invalid', [
		false,
		true,
		'',
		'abc',
		':',
		'124.24.52', // not enough quads
		'24.324.52.13', // outside of the IPv4 range
		'.24.52.13',
		'74.24.52.13/20' // Known difference: mw.util requires individual IP, not IP-range
	], ( assert, ip ) => {
		assert.false( util.isIPv4Address( ip ), String( ip ) );
	} );

	// Based on mediawiki/libs/IPUtils: provideValidIPv4Address
	QUnit.test.each( 'isIPv4Address valid', [
		'124.24.52.13',
		'1.24.52.13'
	], ( assert, ip ) => {
		assert.true( util.isIPv4Address( ip ), ip );
	} );

	// Based on mediawiki/libs/IPUtils: testisIPv6
	QUnit.test.each( 'isIPv6Address invalid', [
		false,
		true,
		':fc:100::', // starting with lone ":"
		'fc:100:::', // ending with a tripple ":::"
		'fc:300', // 2 words
		'fc:100:300', // 3 words
		'fc:100:a:d:1:e:ac:0::', // 8 words ending with "::"
		'fc:100:a:d:1:e:ac:0:1::', // 9 words ending with "::"
		':::',
		'::0:', // ending in a lone ":"
		'::fc:100:a:d:1:e:ac:0', // 8 words starting with "::"
		'::fc:100:a:d:1:e:ac:0:1', // 9 words
		':fc::100', // starting with lone ":"
		'fc::100:', // ending with lone ":"
		'fc:::100', // tripple ":::" in the middle
		'fc::100:a:d:1:e:ac:0', // 8 words containing double "::"
		'fc::100:a:d:1:e:ac:0:1' // 9 words
	], ( assert, ip ) => {
		assert.false( util.isIPv6Address( ip ), String( ip ) );
	} );

	// Based on mediawiki/libs/IPUtils: testisIPv6
	QUnit.test.each( 'isIPv6Address valid', [
		'::', // IPv6 zero address
		'fc::100', // 2 words with double "::"
		'fc::100:a', // 3 words with double "::"
		'fc::100:a:d', // 4 words with double "::"
		'fc::100:a:d:1', // 5 words with double "::"
		'fc::100:a:d:1:e', // 6 words with double "::"
		'fc::100:a:d:1:e:ac', // 7 words with double "::"
		'2001::df',
		'2001:5c0:1400:a::df',
		'2001:5c0:1400:a::df:2',
		'fc:100::',
		'fc:100:a::',
		'fc:100:a:d::',
		'fc:100:a:d:1::',
		'fc:100:a:d:1:e::',
		'fc:100:a:d:1:e:ac::',
		'::0',
		'::fc',
		'::fc:100',
		'::fc:100:a',
		'::fc:100:a:d',
		'::fc:100:a:d:1',
		'::fc:100:a:d:1:e',
		'::fc:100:a:d:1:e:ac',
		'fc:100:a:d:1:e:ac:0'
	], ( assert, ip ) => {
		assert.true( util.isIPv6Address( ip ), ip );
	} );

	QUnit.test.each( 'parseImageUrl', {
		'Hashed thumb with shortened path': {
			url: '//upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Princess_Alexandra_of_Denmark_%28later_Queen_Alexandra%2C_wife_of_Edward_VII%29_with_her_two_eldest_sons%2C_Prince_Albert_Victor_%28Eddy%29_and_George_Frederick_Ernest_Albert_%28later_George_V%29.jpg/939px-thumbnail.jpg',
			name: 'Princess Alexandra of Denmark (later Queen Alexandra, wife of Edward VII) with her two eldest sons, Prince Albert Victor (Eddy) and George Frederick Ernest Albert (later George V).jpg',
			width: 939,
			resizedUrl: '//upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Princess_Alexandra_of_Denmark_%28later_Queen_Alexandra%2C_wife_of_Edward_VII%29_with_her_two_eldest_sons%2C_Prince_Albert_Victor_%28Eddy%29_and_George_Frederick_Ernest_Albert_%28later_George_V%29.jpg/1000px-thumbnail.jpg'
		},
		'Hashed thumb with sha1-ed path': {
			url: '//upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Princess_Alexandra_of_Denmark_%28later_Queen_Alexandra%2C_wife_of_Edward_VII%29_with_her_two_eldest_sons%2C_Prince_Albert_Victor_%28Eddy%29_and_George_Frederick_Ernest_Albert_%28later_George_V%29.jpg/939px-ki708pr1r6g2dl5lbhvwdqxenhait13.jpg',
			name: 'Princess Alexandra of Denmark (later Queen Alexandra, wife of Edward VII) with her two eldest sons, Prince Albert Victor (Eddy) and George Frederick Ernest Albert (later George V).jpg',
			width: 939,
			resizedUrl: '//upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Princess_Alexandra_of_Denmark_%28later_Queen_Alexandra%2C_wife_of_Edward_VII%29_with_her_two_eldest_sons%2C_Prince_Albert_Victor_%28Eddy%29_and_George_Frederick_Ernest_Albert_%28later_George_V%29.jpg/1000px-ki708pr1r6g2dl5lbhvwdqxenhait13.jpg'
		},
		'Normal hashed directory thumbnail': {
			url: '/wiki/images/thumb/9/91/Anticlockwise_heliotrope%27s.jpg/99px-Anticlockwise_heliotrope%27s.jpg',
			name: 'Anticlockwise heliotrope\'s.jpg',
			width: 99,
			resizedUrl: '/wiki/images/thumb/9/91/Anticlockwise_heliotrope%27s.jpg/1000px-Anticlockwise_heliotrope%27s.jpg'
		},
		'Normal hashed directory thumbnail with complex thumbnail parameters': {
			url: '/wiki/images/thumb/8/80/Wikipedia-logo-v2.svg/langde-150px-Wikipedia-logo-v2.svg.png',
			name: 'Wikipedia-logo-v2.svg',
			width: 150,
			resizedUrl: '/wiki/images/thumb/8/80/Wikipedia-logo-v2.svg/langde-1000px-Wikipedia-logo-v2.svg.png'
		},
		'Width-like filename component': {
			url: '/wiki/images/thumb/1/10/Little_Bobby_Tables-100px-file.jpg/qlow-100px-Little_Bobby_Tables-100px-file.jpg',
			name: 'Little Bobby Tables-100px-file.jpg',
			width: 100,
			resizedUrl: '/wiki/images/thumb/1/10/Little_Bobby_Tables-100px-file.jpg/qlow-1000px-Little_Bobby_Tables-100px-file.jpg'
		},
		'Width-like filename component in non-ASCII filename': {
			url: '/wiki/images/thumb/1/10/Little_Bobby%22%3B_Tables-100px-file.jpg/qlow-100px-Little_Bobby%22%3B_Tables-100px-file.jpg',
			name: 'Little Bobby"; Tables-100px-file.jpg',
			width: 100,
			resizedUrl: '/wiki/images/thumb/1/10/Little_Bobby%22%3B_Tables-100px-file.jpg/qlow-1000px-Little_Bobby%22%3B_Tables-100px-file.jpg'
		},

		'Commons thumbnail': {
			url: '//upload.wikimedia.org/wikipedia/commons/thumb/8/80/Wikipedia-logo-v2.svg/150px-Wikipedia-logo-v2.svg.png',
			name: 'Wikipedia-logo-v2.svg',
			width: 150,
			resizedUrl: '//upload.wikimedia.org/wikipedia/commons/thumb/8/80/Wikipedia-logo-v2.svg/1000px-Wikipedia-logo-v2.svg.png'
		},
		'Full image': {
			url: '/wiki/images/9/91/Anticlockwise_heliotrope%27s.jpg',
			name: 'Anticlockwise heliotrope\'s.jpg',
			width: null
		},
		'thumb.php-based thumbnail': {
			url: 'http://localhost/thumb.php?f=Stuffless_Figaro%27s.jpg&width=180',
			name: 'Stuffless Figaro\'s.jpg',
			width: 180,
			resizedUrl: 'http://localhost/thumb.php?f=Stuffless_Figaro%27s.jpg&width=1000'
		},
		'thumb.php-based thumbnail with px width': {
			url: 'http://localhost/thumb.php?f=Stuffless_Figaro%27s.jpg&width=180px',
			name: 'Stuffless Figaro\'s.jpg',
			width: 180,
			resizedUrl: 'http://localhost/thumb.php?f=Stuffless_Figaro%27s.jpg&width=1000'
		},
		'thumb.php-based BC thumbnail': {
			url: 'http://localhost/thumb.php?f=Stuffless_Figaro%27s.jpg&w=180',
			name: 'Stuffless Figaro\'s.jpg',
			width: 180,
			resizedUrl: 'http://localhost/thumb.php?f=Stuffless_Figaro%27s.jpg&width=1000'
		},
		'Commons unhashed thumbnail': {
			url: '/wikipedia/commons/thumb/Wikipedia-logo-v2.svg/150px-Wikipedia-logo-v2.svg.png',
			name: 'Wikipedia-logo-v2.svg',
			width: 150,
			resizedUrl: '/wikipedia/commons/thumb/Wikipedia-logo-v2.svg/1000px-Wikipedia-logo-v2.svg.png'
		},
		'Commons unhashed thumbnail with complex thumbnail parameters': {
			url: '/wikipedia/commons/thumb/Wikipedia-logo-v2.svg/langde-150px-Wikipedia-logo-v2.svg.png',
			name: 'Wikipedia-logo-v2.svg',
			width: 150,
			resizedUrl: '/wikipedia/commons/thumb/Wikipedia-logo-v2.svg/langde-1000px-Wikipedia-logo-v2.svg.png'
		},
		'Unhashed local file': {
			url: '/wiki/images/Anticlockwise_heliotrope%27s.jpg',
			name: 'Anticlockwise heliotrope\'s.jpg',
			width: null
		},
		'Empty string': {
			url: ''
		},
		'String with only alphabet characters': {
			url: 'foo'
		},
		'Not a file path': {
			url: 'foobar.foobar'
		},
		'Space characters': {
			url: '/a/a0/blah blah blah'
		}
	}, ( assert, thisCase ) => {
		mw.util.setOptionsForTest( { GenerateThumbnailOnParse: false } );
		const data = mw.util.parseImageUrl( thisCase.url );
		if ( !thisCase.name ) {
			assert.strictEqual( data, null, 'return null' );
			return;
		}

		assert.strictEqual( typeof data, 'object', 'return object' );
		assert.strictEqual( data.name, thisCase.name, 'file name' );
		assert.strictEqual( data.width, thisCase.width, 'width' );
		if ( thisCase.resizedUrl ) {
			assert.strictEqual( typeof data.resizeUrl, 'function', 'resizeUrl type' );
			assert.strictEqual( data.resizeUrl( 1000 ), thisCase.resizedUrl, 'resizeUrl return' );
		} else {
			assert.strictEqual( data.resizeUrl, null, 'resizeUrl is not set' );
		}
	} );

	QUnit.test( 'parseImageUrl [no dynamic thumbnail generation]', function ( assert ) {
		mw.util.setOptionsForTest( { GenerateThumbnailOnParse: true } );
		this.sandbox.stub( mw.config.values, 'wgScript', '/w' );

		const resizeUrl = mw.util.parseImageUrl( '//upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Princess_Alexandra_of_Denmark_%28later_Queen_Alexandra%2C_wife_of_Edward_VII%29_with_her_two_eldest_sons%2C_Prince_Albert_Victor_%28Eddy%29_and_George_Frederick_Ernest_Albert_%28later_George_V%29.jpg/939px-thumbnail.jpg' ).resizeUrl;

		assert.strictEqual( typeof resizeUrl, 'function', 'resizeUrl is set' );
		assert.strictEqual( resizeUrl( 500 ), '/w?title=Special:Redirect/file/Princess_Alexandra_of_Denmark_(later_Queen_Alexandra,_wife_of_Edward_VII)_with_her_two_eldest_sons,_Prince_Albert_Victor_(Eddy)_and_George_Frederick_Ernest_Albert_(later_George_V).jpg&width=500', 'Resized URL is correct' );
	} );

	QUnit.test( 'escapeRegExp [normal]', ( assert ) => {
		const normal = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' +
			'abcdefghijklmnopqrstuvwxyz' +
			'0123456789';
		assert.strictEqual( mw.util.escapeRegExp( normal ), normal, 'Alphanumerals are left alone' );
	} );

	QUnit.test.each( 'escapeRegExp [specials]', [
		'\\',
		'{',
		'}',
		'(',
		')',
		'[',
		']',
		'|',
		'.',
		'?',
		'*',
		'+',
		'-',
		'^',
		'$'
	], ( assert, str ) => {
		assert.propEqual(

			str.match( new RegExp( mw.util.escapeRegExp( str ) ) ),
			[ str ],
			'confirm correct escaping by being able to match itself'
		);
	} );

	QUnit.test( 'debounce(Function, timeout)', async function ( assert ) {
		const fn = mw.util.debounce( ( data ) => {
			assert.step( data );
		}, 5 );

		fn( 'A' );
		setTimeout( () => {
			fn( 'B' );
		}, 1 );
		this.sandbox.clock.tick( 2 );
		setTimeout( () => {
			fn( 'C' );
		}, 1 );
		this.sandbox.clock.tick( 2 );
		this.sandbox.clock.tick( 10 );

		assert.verifySteps( [ 'C' ] );
	} );

	QUnit.test( 'debounce(Function, timeout, immediate=true)', async function ( assert ) {
		const fn = mw.util.debounce( ( data ) => {
			assert.step( data );
		}, 5, true );

		fn( 'A' );
		setTimeout( () => {
			fn( 'B' );
		}, 1 );
		this.sandbox.clock.tick( 2 );
		setTimeout( () => {
			fn( 'C' );
		}, 1 );
		this.sandbox.clock.tick( 2 );
		this.sandbox.clock.tick( 10 );

		assert.verifySteps( [ 'A' ] );
	} );

	QUnit.test( 'debounce(timeout, Function) [old signature]', async function ( assert ) {
		const fn = mw.util.debounce( 5, ( data ) => {
			assert.step( data );
		} );

		fn( 'A' );
		setTimeout( () => {
			fn( 'B' );
		}, 1 );
		this.sandbox.clock.tick( 2 );
		setTimeout( () => {
			fn( 'C' );
		}, 1 );
		this.sandbox.clock.tick( 2 );
		this.sandbox.clock.tick( 10 );

		assert.verifySteps( [ 'C' ] );
	} );

	QUnit.test( 'init (.mw-body-primary)', ( assert ) => {
		const node = $( '<div class="mw-body-primary mw-body">primary</div>' )[ 0 ];
		$( '#qunit-fixture' ).append(
			'<div id="mw-content-text"></div>',
			'<div class="mw-body"></div>',
			node
		);

		util.init();
		assert.strictEqual( mw.util.$content[ 0 ], node );
	} );

	QUnit.test( 'init (first of multiple .mw-body)', ( assert ) => {
		const node = $( '<div class="mw-body">first</div>' )[ 0 ];
		$( '#qunit-fixture' ).append(
			'<div id="mw-content-text"></div>',
			node,
			'<div class="mw-body">second</div>'
		);

		util.init();
		assert.true( util.$content instanceof $, 'jQuery object' );
		assert.strictEqual( mw.util.$content[ 0 ], node, 'node' );
		assert.strictEqual( mw.util.$content.length, 1, 'length' );
	} );

	QUnit.test( 'init (#mw-content-text fallback)', ( assert ) => {
		const node = $( '<div id="mw-content-text">fallback</div>' )[ 0 ];
		$( '#qunit-fixture' ).append(
			node
		);

		util.init();
		assert.true( util.$content instanceof $, 'jQuery object' );
		assert.strictEqual( mw.util.$content[ 0 ], node, 'node' );
		assert.strictEqual( mw.util.$content.length, 1, 'length' );
	} );

	QUnit.test( 'init (body fallback)', ( assert ) => {
		util.init();
		assert.true( util.$content instanceof $, 'jQuery object' );
		assert.strictEqual( mw.util.$content[ 0 ], document.body, 'node' );
		assert.strictEqual( mw.util.$content.length, 1, 'length' );
	} );

	QUnit.test.each( 'sanitizeIP', {
		'IPv6 with "::" and 2 words': [ 'FC:0:0:0:0:0:0:100', 'fc::100' ],
		'IPv6 with "::" and 3 words': [ 'FC:0:0:0:0:0:100:A', 'fc::100:a' ],
		'IPv6 with "::" and 4 words': [ 'FC:0:0:0:0:100:A:D', 'fc::100:a:d' ],
		'IPv6 with "::" and 5 words': [ 'FC:0:0:0:100:A:D:1', 'fc::100:a:d:1' ],
		'IPv6 with "::" and 6 words': [ 'FC:0:0:100:A:D:1:E', 'fc::100:a:d:1:e' ],
		'IPv6 with "::" and 7 words': [ 'FC:0:100:A:D:1:E:AC', 'fc::100:a:d:1:e:ac' ],
		// https://www.apnic.net/get-ip/faqs/what-is-an-ip-address/ipv6-address-types/
		'IPv6 with "::" and 2 words (Teredo)': [ '2001:0:0:0:0:0:0:DF', '2001::df' ],
		'IPv6 with "::" and 5 words (Teredo)': [ '2001:5C0:1400:A:0:0:0:DF', '2001:5c0:1400:a::df' ],
		'IPv6 with "::" and 6 words (Teredo)': [ '2001:5C0:1400:A:0:0:DF:2', '2001:5c0:1400:a::df:2' ],
		'IPv6 range with "::" and 6 words (Teredo)': [ '2001:DB8:A:0:0:0:0:123/64', '2001:db8:a::123/64' ],
		'IPv4 no change': [ '1.24.52.13', '1.24.52.13' ],
		'IPv4 strip leading 0s': [ '1.24.52.13', '01.024.052.013' ],
		'IPv4 strip multiple leading 0s': [ '1.2.5.1', '001.002.005.001' ],
		'IPv4 don\'t strip meaningful trailing 0s': [ '100.240.52.130', '100.240.52.130' ],
		'IPv4 strip meaningless multiple 0s': [ '0.0.52.0', '00.000.52.00' ],
		'IPv4 range strip meaningless multiple 0s': [ '0.0.52.0/32', '00.000.52.00/32' ],
		'Not an IP': [ 'not an IP', 'not an IP' ],
		'Empty string': [ null, ' ' ],
		'IPv4 trim whitespace from start and end of the string': [ '1.24.52.13', ' 1.24.52.13 ' ],
		'IPv6 starts with ::': [ '0:0:0:0:0:0:0:1', '::1' ],
		'IPv6 remove leading zeros from each block.': [ '2001:DB8:0:0:0:FF00:42:8329', '2001:0db8:0000:0000:0000:ff00:0042:8329' ],
		'IPv6 :: at the end': [ 'FE80:0:0:0:0:0:0:0/10', 'fe80::/10' ],
		'Non-IP string': [ 'UserName', 'UserName' ],
		'Non-string': [ null, null ]
	}, ( assert, [ expected, input ] ) => {
		assert.strictEqual( util.sanitizeIP( input ), expected );
	} );

	QUnit.test.each( 'prettifyIP', {
		'IPv6 change to lowercase': [ 'fc::100', 'FC::100' ],
		'IPv4 no change': [ '1.24.52.13', '1.24.52.13' ],
		'IPv4 range strip meaningless multiple 0s': [ '0.0.52.0/32', '00.000.52.00/32' ],
		'Empty string': [ null, ' ' ],
		'IPv6 range Replace consecutive zeros with :: ': [ '2001:db8:a::123/64', '2001:db8:a:0000:0000:0000:0000:123/64' ],
		'IPv6 middle only consecutive zeros with ::': [ '2001:db8::ff00:42:8329', '2001:DB8:0:0:0:FF00:42:8329' ],
		'IPv6 first longer consecutive zeros with ::': [ '2001::15:0:0:1a2b', '2001:0000:0000:0000:0015:0000:0000:1a2b' ],
		'IPv6 last longer consecutive zeros with ::': [ '2001:0:0:15::1a2b', '2001:0000:0000:0015:0000:0000:0000:1a2b' ],
		'IPv6 first of equal length consecutive zeros with ::': [ '2001::15:0:0:3:1a2b', '2001:0000:0000:0015:0000:0000:0003:1a2b' ]
	}, ( assert, [ expected, input ] ) => {
		assert.strictEqual( util.prettifyIP( input ), expected );
	} );

	QUnit.test.each( 'isTemporaryUser', {
		'prefix mismatch': [ '*$1', 'Test', false, true ],
		'prefix match': [ '*$1', '*Some user', true, true ],
		'suffix only match': [ '$1*', 'Some user*', true, true ],
		'suffix only mismatch': [ '$1*', 'Some user', false, true ],
		'prefix and suffix match': [ '*$1*', '*Unregistered 123*', true, true ],
		'prefix and suffix mismatch': [ '*$1*', 'Unregistered 123*', false, true ],
		'prefix and suffix zero length match': [ '*$1*', '**', true, true ],
		'prefix and suffix overlapping': [ '*$1*', '*', false, true ],
		'multiple patterns prefix match': [ [ '*$1', '~$1' ], '~Some user', true, true ],
		'multiple patterns prefix mismatch': [ [ '*$1', '~$1' ], 'Some user', false, true ],
		'multiple patterns suffix match': [ [ '*$1', '$1~' ], 'Some user~', true, true ],
		'multiple patterns suffix mismatch': [ [ '*$1', '$1~' ], 'Some user', false, true ],
		'multiple patterns prefix and suffix match': [ [ '*$1*', '$1~' ], '*Unregistered 123*', true, true ],
		'Auto create temporary user disabled': [ '*$1*', '*', false, false ],
		'null username': [ '*$1', null, false, true ]
	}, ( assert, username ) => {
		mw.util.setOptionsForTest( {
			AutoCreateTempUser: { enabled: username[ 3 ], matchPattern: username[ 0 ] }
		} );

		assert.strictEqual( util.isTemporaryUser( username[ 1 ] ), username[ 2 ] );
	} );

	QUnit.test.each( 'isTemporaryUser matchPattern null', {
		'prefix mismatch': [ '*$1', 'Test', false, true ],
		'prefix match': [ '*$1', '*Some user', true, true ]
	}, ( assert, username ) => {
		mw.util.setOptionsForTest( {
			AutoCreateTempUser: { enabled: username[ 3 ], genPattern: username[ 0 ], matchPattern: null }
		} );

		assert.strictEqual( util.isTemporaryUser( username[ 1 ] ), username[ 2 ] );
	} );

	QUnit.test( 'isInfinity', ( assert ) => {
		assert.true( util.isInfinity( 'indefinite' ) );
		assert.true( util.isInfinity( 'infinite' ) );
		assert.true( util.isInfinity( 'infinity' ) );
		assert.true( util.isInfinity( 'never' ) );
		assert.false( util.isInfinity( '' ) );
		assert.false( util.isInfinity( null ) );
	} );
} );
