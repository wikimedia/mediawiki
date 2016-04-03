( function ( mw, $ ) {
	var
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

			[ true,  '::', 'IPv6 zero address' ],

			[ false, '::fc:100:a:d:1:e:ac:0', 'IPv6 with "::" and 8 words' ],
			[ false, '::fc:100:a:d:1:e:ac:0:1', 'IPv6 with 9 words' ],

			[ false, ':fc::100', 'IPv6 starting with lone ":"' ],
			[ false, 'fc::100:', 'IPv6 ending with lone ":"' ],
			[ false, 'fc:::100', 'IPv6 with ":::" in the middle' ],

			[ true,  'fc::100', 'IPv6 with "::" and 2 words' ],
			[ true,  'fc::100:a', 'IPv6 with "::" and 3 words' ],
			[ true,  'fc::100:a:d', 'IPv6 with "::" and 4 words' ],
			[ true,  'fc::100:a:d:1', 'IPv6 with "::" and 5 words' ],
			[ true,  'fc::100:a:d:1:e', 'IPv6 with "::" and 6 words' ],
			[ true,  'fc::100:a:d:1:e:ac', 'IPv6 with "::" and 7 words' ],
			[ true,  '2001::df', 'IPv6 with "::" and 2 words' ],
			[ true,  '2001:5c0:1400:a::df', 'IPv6 with "::" and 5 words' ],
			[ true,  '2001:5c0:1400:a::df:2', 'IPv6 with "::" and 6 words' ],

			[ false, 'fc::100:a:d:1:e:ac:0', 'IPv6 with "::" and 8 words' ],
			[ false, 'fc::100:a:d:1:e:ac:0:1', 'IPv6 with 9 words' ]
		];

	Array.prototype.push.apply( IPV6_CASES,
		$.map( [
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
		], function ( el ) {
			return [ [ true, el, el + ' is a valid IP' ] ];
		} )
	);

	QUnit.module( 'mediawiki.util', QUnit.newMwEnvironment( {
		setup: function () {
			$.fn.updateTooltipAccessKeys.setTestMode( true );
		},
		teardown: function () {
			$.fn.updateTooltipAccessKeys.setTestMode( false );
		},
		messages: {
			// Used by accessKeyLabel in test for addPortletLink
			brackets: '[$1]',
			'word-separator': ' '
		}
	} ) );

	QUnit.test( 'rawurlencode', 1, function ( assert ) {
		assert.equal( mw.util.rawurlencode( 'Test:A & B/Here' ), 'Test%3AA%20%26%20B%2FHere' );
	} );

	QUnit.test( 'escapeId', 17, function ( assert ) {
		mw.config.set( 'wgExperimentalHtmlIds', false );
		$.each( {
			'+': '.2B',
			'&': '.26',
			'=': '.3D',
			':': ':',
			';': '.3B',
			'@': '.40',
			$: '.24',
			'-_.': '-_.',
			'!': '.21',
			'*': '.2A',
			'/': '.2F',
			'[]': '.5B.5D',
			'<>': '.3C.3E',
			'\'': '.27',
			'§': '.C2.A7',
			'Test:A & B/Here': 'Test:A_.26_B.2FHere',
			'A&B&amp;C&amp;amp;D&amp;amp;amp;E': 'A.26B.26amp.3BC.26amp.3Bamp.3BD.26amp.3Bamp.3Bamp.3BE'
		}, function ( input, output ) {
			assert.equal( mw.util.escapeId( input ), output );
		} );
	} );

	QUnit.test( 'wikiUrlencode', 11, function ( assert ) {
		assert.equal( mw.util.wikiUrlencode( 'Test:A & B/Here' ), 'Test:A_%26_B/Here' );
		// See also wfUrlencodeTest.php#provideURLS
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
			assert.equal( mw.util.wikiUrlencode( input ), output );
		} );
	} );

	QUnit.test( 'getUrl', 13, function ( assert ) {
		var href;
		mw.config.set( {
			wgScript: '/w/index.php',
			wgArticlePath: '/wiki/$1',
			wgPageName: 'Foobar'
		} );

		href = mw.util.getUrl( 'Sandbox' );
		assert.equal( href, '/wiki/Sandbox', 'simple title' );

		href = mw.util.getUrl( 'Foo:Sandbox? 5+5=10! (test)/sub ' );
		assert.equal( href, '/wiki/Foo:Sandbox%3F_5%2B5%3D10!_(test)/sub_', 'complex title' );

		href = mw.util.getUrl();
		assert.equal( href, '/wiki/Foobar', 'default title' );

		href = mw.util.getUrl( null, { action: 'edit' } );
		assert.equal( href, '/w/index.php?title=Foobar&action=edit', 'default title with query string' );

		href = mw.util.getUrl( 'Sandbox', { action: 'edit' } );
		assert.equal( href, '/w/index.php?title=Sandbox&action=edit', 'simple title with query string' );

		// Test fragments
		href = mw.util.getUrl( 'Foo:Sandbox#Fragment', { action: 'edit' } );
		assert.equal( href, '/w/index.php?title=Foo:Sandbox&action=edit#Fragment', 'namespaced title with query string and fragment' );

		href = mw.util.getUrl( 'Sandbox#', { action: 'edit' } );
		assert.equal( href, '/w/index.php?title=Sandbox&action=edit', 'title with query string and empty fragment' );

		href = mw.util.getUrl( 'Sandbox', {} );
		assert.equal( href, '/wiki/Sandbox', 'title with empty query string' );

		href = mw.util.getUrl( '#Fragment' );
		assert.equal( href, '/wiki/#Fragment', 'empty title with fragment' );

		href = mw.util.getUrl( '#Fragment', { action: 'edit' } );
		assert.equal( href, '/w/index.php?action=edit#Fragment', 'epmty title with query string and fragment' );

		href = mw.util.getUrl( 'Foo:Sandbox \xC4#Fragment \xC4', { action: 'edit' } );
		assert.equal( href, '/w/index.php?title=Foo:Sandbox_%C3%84&action=edit#Fragment_.C3.84', 'title with query string, fragment, and special characters' );

		href = mw.util.getUrl( 'Foo:%23#Fragment', { action: 'edit' } );
		assert.equal( href, '/w/index.php?title=Foo:%2523&action=edit#Fragment', 'title containing %23 (#), fragment, and a query string' );

		href = mw.util.getUrl( '#+&=:;@$-_.!*/[]<>\'§', { action: 'edit' } );
		assert.equal( href, '/w/index.php?action=edit#.2B.26.3D:.3B.40.24-_..21.2A.2F.5B.5D.3C.3E.27.C2.A7', 'fragment with various characters' );
	} );

	QUnit.test( 'wikiScript', 4, function ( assert ) {
		mw.config.set( {
			// customized wgScript for T41103
			wgScript: '/w/i.php',
			// customized wgLoadScript for T41103
			wgLoadScript: '/w/l.php',
			wgScriptPath: '/w'
		} );

		assert.equal( mw.util.wikiScript(), mw.config.get( 'wgScript' ),
			'wikiScript() returns wgScript'
		);
		assert.equal( mw.util.wikiScript( 'index' ), mw.config.get( 'wgScript' ),
			'wikiScript( index ) returns wgScript'
		);
		assert.equal( mw.util.wikiScript( 'load' ), mw.config.get( 'wgLoadScript' ),
			'wikiScript( load ) returns wgLoadScript'
		);
		assert.equal( mw.util.wikiScript( 'api' ), '/w/api.php', 'API path' );
	} );

	QUnit.test( 'addCSS', 3, function ( assert ) {
		var $el, style;
		$el = $( '<div>' ).attr( 'id', 'mw-addcsstest' ).appendTo( '#qunit-fixture' );

		style = mw.util.addCSS( '#mw-addcsstest { visibility: hidden; }' );
		assert.equal( typeof style, 'object', 'addCSS returned an object' );
		assert.strictEqual( style.disabled, false, 'property "disabled" is available and set to false' );

		assert.equal( $el.css( 'visibility' ), 'hidden', 'Added style properties are in effect' );

		// Clean up
		$( style.ownerNode ).remove();
	} );

	QUnit.test( 'getParamValue', 5, function ( assert ) {
		var url;

		url = 'http://example.org/?foo=wrong&foo=right#&foo=bad';
		assert.equal( mw.util.getParamValue( 'foo', url ), 'right', 'Use latest one, ignore hash' );
		assert.strictEqual( mw.util.getParamValue( 'bar', url ), null, 'Return null when not found' );

		url = 'http://example.org/#&foo=bad';
		assert.strictEqual( mw.util.getParamValue( 'foo', url ), null, 'Ignore hash if param is not in querystring but in hash (bug 27427)' );

		url = 'example.org?' + $.param( { TEST: 'a b+c' } );
		assert.strictEqual( mw.util.getParamValue( 'TEST', url ), 'a b+c', 'Bug 30441: getParamValue must understand "+" encoding of space' );

		url = 'example.org?' + $.param( { TEST: 'a b+c d' } ); // check for sloppy code from r95332 :)
		assert.strictEqual( mw.util.getParamValue( 'TEST', url ), 'a b+c d', 'Bug 30441: getParamValue must understand "+" encoding of space (multiple spaces)' );
	} );

	QUnit.test( 'tooltipAccessKey', 4, function ( assert ) {
		this.suppressWarnings();

		assert.equal( typeof mw.util.tooltipAccessKeyPrefix, 'string', 'tooltipAccessKeyPrefix must be a string' );
		assert.equal( $.type( mw.util.tooltipAccessKeyRegexp ), 'regexp', 'tooltipAccessKeyRegexp is a regexp' );
		assert.ok( mw.util.updateTooltipAccessKeys, 'updateTooltipAccessKeys is non-empty' );

		'Example [a]'.replace( mw.util.tooltipAccessKeyRegexp, function ( sub, m1, m2, m3, m4, m5, m6 ) {
			assert.equal( m6, 'a', 'tooltipAccessKeyRegexp finds the accesskey hint' );
		} );

		this.restoreWarnings();
	} );

	QUnit.test( '$content', 2, function ( assert ) {
		assert.ok( mw.util.$content instanceof jQuery, 'mw.util.$content instance of jQuery' );
		assert.strictEqual( mw.util.$content.length, 1, 'mw.util.$content must have length of 1' );
	} );

	/**
	 * Portlet names are prefixed with 'p-test' to avoid conflict with core
	 * when running the test suite under a wiki page.
	 * Previously, test elements where invisible to the selector since only
	 * one element can have a given id.
	 */
	QUnit.test( 'addPortletLink', 13, function ( assert ) {
		var pTestTb, pCustom, vectorTabs, tbRL, cuQuux, $cuQuux, tbMW, $tbMW, tbRLDM, caFoo,
			addedAfter, tbRLDMnonexistentid, tbRLDMemptyjquery;

		pTestTb = '\
		<div class="portlet" id="p-test-tb">\
			<h3>Toolbox</h3>\
			<ul class="body"></ul>\
		</div>';
		pCustom = '\
		<div class="portlet" id="p-test-custom">\
			<h3>Views</h3>\
			<ul class="body">\
				<li id="c-foo"><a href="#">Foo</a></li>\
				<li id="c-barmenu">\
					<ul>\
						<li id="c-bar-baz"><a href="#">Baz</a></a>\
					</ul>\
				</li>\
			</ul>\
		</div>';
		vectorTabs = '\
		<div id="p-test-views" class="vectorTabs">\
			<h3>Views</h3>\
			<ul></ul>\
		</div>';

		$( '#qunit-fixture' ).append( pTestTb, pCustom, vectorTabs );

		tbRL = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/wiki/ResourceLoader',
			'ResourceLoader', 't-rl', 'More info about ResourceLoader on MediaWiki.org ', 'l'
		);

		assert.ok( tbRL && tbRL.nodeType, 'addPortletLink returns a DOM Node' );

		tbMW = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/',
			'MediaWiki.org', 't-mworg', 'Go to MediaWiki.org', 'm', tbRL );
		$tbMW = $( tbMW );

		assert.propEqual(
			$tbMW.getAttrs(),
			{
				id: 't-mworg'
			},
			'Validate attributes of created element'
		);

		assert.propEqual(
			$tbMW.find( 'a' ).getAttrs(),
			{
				href: '//mediawiki.org/',
				title: 'Go to MediaWiki.org [test-m]',
				accesskey: 'm'
			},
			'Validate attributes of anchor tag in created element'
		);

		assert.equal( $tbMW.closest( '.portlet' ).attr( 'id' ), 'p-test-tb', 'Link was inserted within correct portlet' );
		assert.strictEqual( $tbMW.next()[ 0 ], tbRL, 'Link is in the correct position (nextnode as Node object)' );

		cuQuux = mw.util.addPortletLink( 'p-test-custom', '#', 'Quux', null, 'Example [shift-x]', 'q' );
		$cuQuux = $( cuQuux );

		assert.equal( $cuQuux.find( 'a' ).attr( 'title' ), 'Example [test-q]', 'Existing accesskey is stripped and updated' );

		assert.equal(
			$( '#p-test-custom #c-barmenu ul li' ).length,
			1,
			'addPortletLink did not add the item to all <ul> elements in the portlet (bug 35082)'
		);

		tbRLDM = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/wiki/RL/DM',
			'Default modules', 't-rldm', 'List of all default modules ', 'd', '#t-rl' );

		assert.strictEqual( $( tbRLDM ).next()[ 0 ], tbRL, 'Link is in the correct position (CSS selector as nextnode)' );

		caFoo = mw.util.addPortletLink( 'p-test-views', '#', 'Foo' );

		assert.strictEqual( $tbMW.find( 'span' ).length, 0, 'No <span> element should be added for porlets without vectorTabs class.' );
		assert.strictEqual( $( caFoo ).find( 'span' ).length, 1, 'A <span> element should be added for porlets with vectorTabs class.' );

		addedAfter = mw.util.addPortletLink( 'p-test-tb', '#', 'After foo', 'post-foo', 'After foo', null, $( tbRL ) );
		assert.strictEqual( $( addedAfter ).next()[ 0 ], tbRL, 'Link is in the correct position (jQuery object as nextnode)' );

		// test case - nonexistent id as next node
		tbRLDMnonexistentid = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/wiki/RL/DM',
			'Default modules', 't-rldm-nonexistent', 'List of all default modules ', 'd', '#t-rl-nonexistent' );

		assert.equal( tbRLDMnonexistentid, $( '#p-test-tb li:last' )[ 0 ], 'Fallback to adding at the end (nextnode non-matching CSS selector)' );

		// test case - empty jquery object as next node
		tbRLDMemptyjquery = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/wiki/RL/DM',
			'Default modules', 't-rldm-empty-jquery', 'List of all default modules ', 'd', $( '#t-rl-nonexistent' ) );

		assert.equal( tbRLDMemptyjquery, $( '#p-test-tb li:last' )[ 0 ], 'Fallback to adding at the end (nextnode as empty jQuery object)' );
	} );

	QUnit.test( 'validateEmail', 6, function ( assert ) {
		assert.strictEqual( mw.util.validateEmail( '' ), null, 'Should return null for empty string ' );
		assert.strictEqual( mw.util.validateEmail( 'user@localhost' ), true, 'Return true for a valid e-mail address' );

		// testEmailWithCommasAreInvalids
		assert.strictEqual( mw.util.validateEmail( 'user,foo@example.org' ), false, 'Emails with commas are invalid' );
		assert.strictEqual( mw.util.validateEmail( 'userfoo@ex,ample.org' ), false, 'Emails with commas are invalid' );

		// testEmailWithHyphens
		assert.strictEqual( mw.util.validateEmail( 'user-foo@example.org' ), true, 'Emails may contain a hyphen' );
		assert.strictEqual( mw.util.validateEmail( 'userfoo@ex-ample.org' ), true, 'Emails may contain a hyphen' );
	} );

	QUnit.test( 'isIPv6Address', 40, function ( assert ) {
		$.each( IPV6_CASES, function ( i, ipCase ) {
			assert.strictEqual( mw.util.isIPv6Address( ipCase[ 1 ] ), ipCase[ 0 ], ipCase[ 2 ] );
		} );
	} );

	QUnit.test( 'isIPv4Address', 11, function ( assert ) {
		$.each( IPV4_CASES, function ( i, ipCase ) {
			assert.strictEqual( mw.util.isIPv4Address( ipCase[ 1 ] ), ipCase[ 0 ], ipCase[ 2 ] );
		} );
	} );

	QUnit.test( 'isIPAddress', 51, function ( assert ) {
		$.each( IPV4_CASES, function ( i, ipCase ) {
			assert.strictEqual( mw.util.isIPv4Address( ipCase[ 1 ] ), ipCase[ 0 ], ipCase[ 2 ] );
		} );

		$.each( IPV6_CASES, function ( i, ipCase ) {
			assert.strictEqual( mw.util.isIPv6Address( ipCase[ 1 ] ), ipCase[ 0 ], ipCase[ 2 ] );
		} );
	} );
}( mediaWiki, jQuery ) );
