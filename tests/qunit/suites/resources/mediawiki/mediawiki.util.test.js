( function () {
	var util = require( 'mediawiki.util' ),
		// Based on IPTest.php > testisIPv4
		IPV4_CASES = [
			[ false, false, 'Boolean false is not an IP' ],
			[ false, true, 'Boolean true is not an IP' ],
			[ false, '', 'Empty string is not an IP' ],
			[ false, 'abc', '"abc" is not an IP' ],
			[ false, ':', 'Colon is not an IP' ],
			[ false, '124.24.52', 'IPv4 not enough quads' ],
			[ false, '24.324.52.13', 'IPv4 out of range' ],
			[ false, '.24.52.13', 'IPv4 starts with period' ],

			[ true, '124.24.52.13', '124.24.52.134 is a valid IP' ],
			[ true, '1.24.52.13', '1.24.52.13 is a valid IP' ],
			[ false, '74.24.52.13/20', 'IPv4 ranges are not recognized as valid IPs' ]
		],

		// Based on IPTest.php > testisIPv6
		IPV6_CASES = [
			[ false, ':fc:100::', 'IPv6 starting with lone ":"' ],
			[ false, 'fc:100:::', 'IPv6 ending with a ":::"' ],
			[ false, 'fc:300', 'IPv6 with only 2 words' ],
			[ false, 'fc:100:300', 'IPv6 with only 3 words' ],

			[ false, 'fc:100:a:d:1:e:ac:0::', 'IPv6 with 8 words ending with "::"' ],
			[ false, 'fc:100:a:d:1:e:ac:0:1::', 'IPv6 with 9 words ending with "::"' ],

			[ false, ':::' ],
			[ false, '::0:', 'IPv6 ending in a lone ":"' ],

			[ true, '::', 'IPv6 zero address' ],

			[ false, '::fc:100:a:d:1:e:ac:0', 'IPv6 with "::" and 8 words' ],
			[ false, '::fc:100:a:d:1:e:ac:0:1', 'IPv6 with 9 words' ],

			[ false, ':fc::100', 'IPv6 starting with lone ":"' ],
			[ false, 'fc::100:', 'IPv6 ending with lone ":"' ],
			[ false, 'fc:::100', 'IPv6 with ":::" in the middle' ],

			[ true, 'fc::100', 'IPv6 with "::" and 2 words' ],
			[ true, 'fc::100:a', 'IPv6 with "::" and 3 words' ],
			[ true, 'fc::100:a:d', 'IPv6 with "::" and 4 words' ],
			[ true, 'fc::100:a:d:1', 'IPv6 with "::" and 5 words' ],
			[ true, 'fc::100:a:d:1:e', 'IPv6 with "::" and 6 words' ],
			[ true, 'fc::100:a:d:1:e:ac', 'IPv6 with "::" and 7 words' ],
			[ true, '2001::df', 'IPv6 with "::" and 2 words' ],
			[ true, '2001:5c0:1400:a::df', 'IPv6 with "::" and 5 words' ],
			[ true, '2001:5c0:1400:a::df:2', 'IPv6 with "::" and 6 words' ],

			[ false, 'fc::100:a:d:1:e:ac:0', 'IPv6 with "::" and 8 words' ],
			[ false, 'fc::100:a:d:1:e:ac:0:1', 'IPv6 with 9 words' ]
		];

	Array.prototype.push.apply( IPV6_CASES,
		[
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
		].map( function ( el ) {
			return [ true, el, el + ' is a valid IP' ];
		} )
	);

	QUnit.module( 'mediawiki.util', QUnit.newMwEnvironment( {
		setup: function () {
			$.fn.updateTooltipAccessKeys.setTestMode( true );
			this.origConfig = mw.util.setOptionsForTest( {
				FragmentMode: [ 'legacy', 'html5' ],
				LoadScript: '/w/load.php'
			} );
		},
		teardown: function () {
			$.fn.updateTooltipAccessKeys.setTestMode( false );
			mw.util.setOptionsForTest( this.origConfig );
		},
		messages: {
			// Used by accessKeyLabel in test for addPortletLink
			brackets: '[$1]',
			'word-separator': ' '
		}
	} ) );

	QUnit.test( 'rawurlencode', function ( assert ) {
		assert.strictEqual( util.rawurlencode( 'Test:A & B/Here' ), 'Test%3AA%20%26%20B%2FHere' );
	} );

	QUnit.test( 'escapeIdForAttribute', function ( assert ) {
		// Test cases are kept in sync with SanitizerTest.php
		var text = 'foo тест_#%!\'()[]:<>',
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
		].forEach( function ( testCase ) {
			mw.util.setOptionsForTest( { FragmentMode: testCase[ 0 ] } );

			assert.strictEqual( util.escapeIdForAttribute( testCase[ 1 ] ), testCase[ 2 ] );
		} );
	} );

	QUnit.test( 'escapeIdForLink', function ( assert ) {
		// Test cases are kept in sync with SanitizerTest.php
		var text = 'foo тест_#%!\'()[]:<>',
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
		].forEach( function ( testCase ) {
			mw.util.setOptionsForTest( { FragmentMode: testCase[ 0 ] } );

			assert.strictEqual( util.escapeIdForLink( testCase[ 1 ] ), testCase[ 2 ] );
		} );
	} );

	QUnit.test( 'wikiUrlencode', function ( assert ) {
		assert.strictEqual( util.wikiUrlencode( 'Test:A & B/Here' ), 'Test:A_%26_B/Here' );
		// See also wfUrlencodeTest.php#provideURLS
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( {
			'+': '%2B',
			'&': '%26',
			'=': '%3D',
			':': ':',
			';@$-_.!*': ';@$-_.!*',
			'/': '/',
			'~': '~',
			'[]': '%5B%5D',
			'<>': '%3C%3E',
			'\'': '%27'
		}, function ( input, output ) {
			assert.strictEqual( util.wikiUrlencode( input ), output );
		} );
	} );

	QUnit.test( 'getUrl', function ( assert ) {
		var href;
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
		assert.strictEqual( href, '/wiki/#Fragment', 'empty title with fragment' );

		href = util.getUrl( '#Fragment', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?action=edit#Fragment', 'empty title with query string and fragment' );

		mw.util.setOptionsForTest( { FragmentMode: [ 'legacy' ] } );
		href = util.getUrl( 'Foo:Sandbox \xC4#Fragment \xC4', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Foo:Sandbox_%C3%84&action=edit#Fragment_.C3.84', 'title with query string, fragment, and special characters' );

		mw.util.setOptionsForTest( { FragmentMode: [ 'html5' ] } );
		href = util.getUrl( 'Foo:Sandbox \xC4#Fragment \xC4', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Foo:Sandbox_%C3%84&action=edit#Fragment_Ä', 'title with query string, fragment, and special characters' );

		href = util.getUrl( 'Foo:%23#Fragment', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?title=Foo:%2523&action=edit#Fragment', 'title containing %23 (#), fragment, and a query string' );

		mw.util.setOptionsForTest( { FragmentMode: [ 'legacy' ] } );
		href = util.getUrl( '#+&=:;@$-_.!*/[]<>\'§', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?action=edit#.2B.26.3D:.3B.40.24-_..21.2A.2F.5B.5D.3C.3E.27.C2.A7', 'fragment with various characters' );

		mw.util.setOptionsForTest( { FragmentMode: [ 'html5' ] } );
		href = util.getUrl( '#+&=:;@$-_.!*/[]<>\'§', { action: 'edit' } );
		assert.strictEqual( href, '/w/index.php?action=edit#+&=:;@$-_.!*/[]<>\'§', 'fragment with various characters' );
	} );

	QUnit.test( 'wikiScript', function ( assert ) {
		mw.util.setOptionsForTest( {
			LoadScript: '/w/l.php'
		} );
		mw.config.set( {
			// customized wgScript for T41103
			wgScript: '/w/i.php',
			wgScriptPath: '/w'
		} );

		assert.strictEqual( util.wikiScript(), mw.config.get( 'wgScript' ),
			'wikiScript() returns wgScript'
		);
		assert.strictEqual( util.wikiScript( 'index' ), mw.config.get( 'wgScript' ),
			'wikiScript( index ) returns wgScript'
		);
		assert.strictEqual( util.wikiScript( 'load' ), '/w/l.php',
			'wikiScript( load ) returns /w/l.php'
		);
		assert.strictEqual( util.wikiScript( 'api' ), '/w/api.php', 'API path' );
	} );

	QUnit.test( 'addCSS', function ( assert ) {
		var $el, style;
		$el = $( '<div>' ).attr( 'id', 'mw-addcsstest' ).appendTo( '#qunit-fixture' );

		style = util.addCSS( '#mw-addcsstest { visibility: hidden; }' );
		assert.strictEqual( typeof style, 'object', 'addCSS returned an object' );
		assert.strictEqual( style.disabled, false, 'property "disabled" is available and set to false' );

		assert.strictEqual( $el.css( 'visibility' ), 'hidden', 'Added style properties are in effect' );

		// Clean up
		$( style.ownerNode ).remove();
	} );

	QUnit.test( 'getParamValue', function ( assert ) {
		var url;

		url = 'http://example.org/?foo=wrong&foo=right#&foo=bad';
		assert.strictEqual( util.getParamValue( 'foo', url ), 'right', 'Use latest one, ignore hash' );
		assert.strictEqual( util.getParamValue( 'bar', url ), null, 'Return null when not found' );

		url = 'http://example.org/#&foo=bad';
		assert.strictEqual( util.getParamValue( 'foo', url ), null, 'Ignore hash if param is not in querystring but in hash (T29427)' );

		url = 'example.org?' + $.param( { TEST: 'a b+c' } );
		assert.strictEqual( util.getParamValue( 'TEST', url ), 'a b+c', 'T32441: getParamValue must understand "+" encoding of space' );

		url = 'example.org?' + $.param( { TEST: 'a b+c d' } ); // check for sloppy code from r95332 :)
		assert.strictEqual( util.getParamValue( 'TEST', url ), 'a b+c d', 'T32441: getParamValue must understand "+" encoding of space (multiple spaces)' );
	} );

	function getParents( link ) {
		return $( link ).parents( '#qunit-fixture *' ).toArray()
			.map( function ( el ) {
				return el.tagName + ( el.className && '.' + el.className ) + ( el.id && '#' + el.id );
			} );
	}

	QUnit.test( 'addPortletLink (Vector list)', function ( assert ) {
		var link;

		$( '#qunit-fixture' ).html(
			'<div class="portlet" id="p-toolbox">' +
				'<h3>Tools</h3>' +
				'<div class="body"><ul></ul></div>' +
				'</div>'
		);
		link = util.addPortletLink( 'p-toolbox', 'https://foo.test/',
			'Foo', 't-foo', 'Tooltip', 'l'
		);

		assert.domEqual(
			link,
			{
				tagName: 'LI',
				attributes: { id: 't-foo' },
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

	QUnit.test( 'addPortletLink (Minerva list)', function ( assert ) {
		var link;

		$( '#qunit-fixture' ).html( '<ul id="p-list"></ul>' );
		link = util.addPortletLink( 'p-list', '#', 'Foo', 't-foo' );

		assert.domEqual(
			link,
			{
				tagName: 'LI',
				attributes: { id: 't-foo' },
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

	QUnit.test( 'addPortletLink (nextNode option)', function ( assert ) {
		var linkFoo, link;

		$( '#qunit-fixture' ).html( '<ul id="p-toolbox"></ul>' );
		linkFoo = util.addPortletLink( 'p-toolbox', 'https://foo.test/',
			'Foo', 't-foo', 'Tooltip', 'l'
		);

		link = util.addPortletLink( 'p-toolbox', '#',
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

	QUnit.test( 'addPortletLink (accesskey option)', function ( assert ) {
		var link;
		$( '#qunit-fixture' ).html( '<ul id="p-toolbox"></ul>' );

		link = util.addPortletLink( 'p-toolbox', '#', 'Label', null, 'Tooltip [shift-x]', 'z' );
		assert.strictEqual(
			link.querySelector( 'a' ).title,
			'Tooltip [test-z]',
			'Change a pre-existing accesskey in a tooltip'
		);
	} );

	QUnit.test( 'addPortletLink (nested list)', function ( assert ) {
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

	QUnit.test( 'validateEmail', function ( assert ) {
		assert.strictEqual( util.validateEmail( '' ), null, 'Should return null for empty string ' );
		assert.strictEqual( util.validateEmail( 'user@localhost' ), true, 'Return true for a valid e-mail address' );

		// testEmailWithCommasAreInvalids
		assert.strictEqual( util.validateEmail( 'user,foo@example.org' ), false, 'Emails with commas are invalid' );
		assert.strictEqual( util.validateEmail( 'userfoo@ex,ample.org' ), false, 'Emails with commas are invalid' );

		// testEmailWithHyphens
		assert.strictEqual( util.validateEmail( 'user-foo@example.org' ), true, 'Emails may contain a hyphen' );
		assert.strictEqual( util.validateEmail( 'userfoo@ex-ample.org' ), true, 'Emails may contain a hyphen' );
	} );

	QUnit.test( 'isIPv6Address', function ( assert ) {
		IPV6_CASES.forEach( function ( ipCase ) {
			assert.strictEqual( util.isIPv6Address( ipCase[ 1 ] ), ipCase[ 0 ], ipCase[ 2 ] );
		} );
	} );

	QUnit.test( 'isIPv4Address', function ( assert ) {
		IPV4_CASES.forEach( function ( ipCase ) {
			assert.strictEqual( util.isIPv4Address( ipCase[ 1 ] ), ipCase[ 0 ], ipCase[ 2 ] );
		} );
	} );

	QUnit.test( 'isIPAddress', function ( assert ) {
		IPV4_CASES.forEach( function ( ipCase ) {
			assert.strictEqual( util.isIPv4Address( ipCase[ 1 ] ), ipCase[ 0 ], ipCase[ 2 ] );
		} );

		IPV6_CASES.forEach( function ( ipCase ) {
			assert.strictEqual( util.isIPv6Address( ipCase[ 1 ] ), ipCase[ 0 ], ipCase[ 2 ] );
		} );
	} );

	QUnit.test( 'escapeRegExp', function ( assert ) {
		var specials, normal;

		specials = [
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
		];

		normal = [
			'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'abcdefghijklmnopqrstuvwxyz',
			'0123456789'
		].join( '' );

		specials.forEach( function ( str ) {
			assert.propEqual( str.match( new RegExp( mw.util.escapeRegExp( str ) ) ), [ str ], 'Match ' + str );
		} );

		assert.strictEqual( mw.util.escapeRegExp( normal ), normal, 'Alphanumerals are left alone' );
	} );

	QUnit.test( 'debounce', function ( assert ) {
		var fn,
			q = [],
			done = assert.async();

		fn = mw.util.debounce( 0, function ( data ) {
			q.push( data );
		} );

		fn( 1 );
		fn( 2 );
		fn( 3 );

		setTimeout( function () {
			assert.deepEqual(
				q,
				[ 3 ],
				'Last one ran'
			);
			done();
		} );
	} );

	QUnit.test( 'init (.mw-body-primary)', function ( assert ) {
		var node = $( '<div class="mw-body-primary mw-body">primary</div>' )[ 0 ];
		$( '#qunit-fixture' ).append(
			'<div id="mw-content-text"></div>',
			'<div class="mw-body"></div>',
			node
		);

		util.init();
		assert.strictEqual( mw.util.$content[ 0 ], node );
	} );

	QUnit.test( 'init (first of multiple .mw-body)', function ( assert ) {
		var node = $( '<div class="mw-body">first</div>' )[ 0 ];
		$( '#qunit-fixture' ).append(
			'<div id="mw-content-text"></div>',
			node,
			'<div class="mw-body">second</div>'
		);

		util.init();
		assert.ok( util.$content instanceof $, 'jQuery object' );
		assert.strictEqual( mw.util.$content[ 0 ], node, 'node' );
		assert.strictEqual( mw.util.$content.length, 1, 'length' );
	} );

	QUnit.test( 'init (#mw-content-text fallback)', function ( assert ) {
		var node = $( '<div id="mw-content-text">fallback</div>' )[ 0 ];
		$( '#qunit-fixture' ).append(
			node
		);

		util.init();
		assert.ok( util.$content instanceof $, 'jQuery object' );
		assert.strictEqual( mw.util.$content[ 0 ], node, 'node' );
		assert.strictEqual( mw.util.$content.length, 1, 'length' );
	} );

	QUnit.test( 'init (body fallback)', function ( assert ) {
		util.init();
		assert.ok( util.$content instanceof $, 'jQuery object' );
		assert.strictEqual( mw.util.$content[ 0 ], document.body, 'node' );
		assert.strictEqual( mw.util.$content.length, 1, 'length' );
	} );
}() );
