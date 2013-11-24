( function ( mw, $ ) {
	QUnit.module( 'mediawiki.util', QUnit.newMwEnvironment( {
		setup: function () {
			this.taPrefix = mw.util.tooltipAccessKeyPrefix;
			mw.util.tooltipAccessKeyPrefix = 'ctrl-alt-';
		},
		teardown: function () {
			mw.util.tooltipAccessKeyPrefix = this.taPrefix;
		}
	} ) );

	QUnit.test( 'rawurlencode', 1, function ( assert ) {
		assert.equal( mw.util.rawurlencode( 'Test:A & B/Here' ), 'Test%3AA%20%26%20B%2FHere' );
	} );

	QUnit.test( 'wikiUrlencode', 1, function ( assert ) {
		assert.equal( mw.util.wikiUrlencode( 'Test:A & B/Here' ), 'Test:A_%26_B/Here' );
	} );

	QUnit.test( 'getUrl', 4, function ( assert ) {
		// Not part of startUp module
		mw.config.set( 'wgArticlePath', '/wiki/$1' );
		mw.config.set( 'wgPageName', 'Foobar' );

		var href = mw.util.getUrl( 'Sandbox' );
		assert.equal( href, '/wiki/Sandbox', 'Simple title; Get link for "Sandbox"' );

		href = mw.util.getUrl( 'Foo:Sandbox ? 5+5=10 ! (test)/subpage' );
		assert.equal( href, '/wiki/Foo:Sandbox_%3F_5%2B5%3D10_%21_%28test%29/subpage',
			'Advanced title; Get link for "Foo:Sandbox ? 5+5=10 ! (test)/subpage"' );

		href = mw.util.getUrl();
		assert.equal( href, '/wiki/Foobar', 'Default title; Get link for current page ("Foobar")' );

		href = mw.util.getUrl( 'Sandbox', { action: 'edit' } );
		assert.equal( href, '/wiki/Sandbox?action=edit',
			'Simple title with query string; Get link for "Sandbox" with action=edit' );
	} );

	QUnit.test( 'wikiScript', 4, function ( assert ) {
		mw.config.set( {
			'wgScript': '/w/i.php', // customized wgScript for bug 39103
			'wgLoadScript': '/w/l.php', // customized wgLoadScript for bug 39103
			'wgScriptPath': '/w',
			'wgScriptExtension': '.php'
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

	QUnit.asyncTest( 'toggleToc', 4, function ( assert ) {
		var tocHtml, $toggleLink;

		function actionC() {
			QUnit.start();
		}

		function actionB() {
			assert.strictEqual( mw.util.toggleToc( $toggleLink, actionC ), true, 'Return boolean true if the TOC is now visible.' );
		}

		function actionA() {
			assert.strictEqual( mw.util.toggleToc( $toggleLink, actionB ), false, 'Return boolean false if the TOC is now hidden.' );
		}

		assert.strictEqual( mw.util.toggleToc(), null, 'Return null if there is no table of contents on the page.' );

		tocHtml = '<div id="toc" class="toc">' +
			'<div id="toctitle">' +
			'<h2>Contents</h2>' +
			'<span class="toctoggle">&nbsp;[<a href="#" class="internal" id="togglelink">Hide</a>&nbsp;]</span>' +
			'</div>' +
			'<ul><li></li></ul>' +
			'</div>';
		$( tocHtml ).appendTo( '#qunit-fixture' );
		$toggleLink = $( '#togglelink' );

		assert.strictEqual( $toggleLink.length, 1, 'Toggle link is appended to the page.' );

		actionA();
	} );

	QUnit.test( 'getParamValue', 5, function ( assert ) {
		var url;

		url = 'http://example.org/?foo=wrong&foo=right#&foo=bad';
		assert.equal( mw.util.getParamValue( 'foo', url ), 'right', 'Use latest one, ignore hash' );
		assert.strictEqual( mw.util.getParamValue( 'bar', url ), null, 'Return null when not found' );

		url = 'http://example.org/#&foo=bad';
		assert.strictEqual( mw.util.getParamValue( 'foo', url ), null, 'Ignore hash if param is not in querystring but in hash (bug 27427)' );

		url = 'example.org?' + $.param( { 'TEST': 'a b+c' } );
		assert.strictEqual( mw.util.getParamValue( 'TEST', url ), 'a b+c', 'Bug 30441: getParamValue must understand "+" encoding of space' );

		url = 'example.org?' + $.param( { 'TEST': 'a b+c d' } ); // check for sloppy code from r95332 :)
		assert.strictEqual( mw.util.getParamValue( 'TEST', url ), 'a b+c d', 'Bug 30441: getParamValue must understand "+" encoding of space (multiple spaces)' );
	} );

	QUnit.test( 'tooltipAccessKey', 4, function ( assert ) {
		assert.equal( typeof mw.util.tooltipAccessKeyPrefix, 'string', 'tooltipAccessKeyPrefix must be a string' );
		assert.equal( $.type( mw.util.tooltipAccessKeyRegexp ), 'regexp', 'tooltipAccessKeyRegexp is a regexp' );
		assert.ok( mw.util.updateTooltipAccessKeys, 'updateTooltipAccessKeys is non-empty' );

		'Example [a]'.replace( mw.util.tooltipAccessKeyRegexp, function ( sub, m1, m2, m3, m4, m5, m6 ) {
			assert.equal( m6, 'a', 'tooltipAccessKeyRegexp finds the accesskey hint' );
		} );
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

		assert.ok( $.isDomElement( tbRL ), 'addPortletLink returns a valid DOM Element according to $.isDomElement' );

		tbMW = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/',
			'MediaWiki.org', 't-mworg', 'Go to MediaWiki.org ', 'm', tbRL );
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
				title: 'Go to MediaWiki.org [ctrl-alt-m]',
				accesskey: 'm'
			},
			'Validate attributes of anchor tag in created element'
		);

		assert.equal( $tbMW.closest( '.portlet' ).attr( 'id' ), 'p-test-tb', 'Link was inserted within correct portlet' );
		assert.strictEqual( $tbMW.next()[0], tbRL, 'Link is in the correct position (by passing nextnode)' );

		cuQuux = mw.util.addPortletLink( 'p-test-custom', '#', 'Quux', null, 'Example [shift-x]', 'q' );
		$cuQuux = $( cuQuux );

		assert.equal( $cuQuux.find( 'a' ).attr( 'title' ), 'Example [ctrl-alt-q]', 'Existing accesskey is stripped and updated' );

		assert.equal(
			$( '#p-test-custom #c-barmenu ul li' ).length,
			1,
			'addPortletLink did not add the item to all <ul> elements in the portlet (bug 35082)'
		);

		tbRLDM = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/wiki/RL/DM',
			'Default modules', 't-rldm', 'List of all default modules ', 'd', '#t-rl' );

		assert.equal( $( tbRLDM ).next().attr( 'id' ), 't-rl', 'Link is in the correct position (by passing CSS selector)' );

		caFoo = mw.util.addPortletLink( 'p-test-views', '#', 'Foo' );

		assert.strictEqual( $tbMW.find( 'span' ).length, 0, 'No <span> element should be added for porlets without vectorTabs class.' );
		assert.strictEqual( $( caFoo ).find( 'span' ).length, 1, 'A <span> element should be added for porlets with vectorTabs class.' );

		addedAfter = mw.util.addPortletLink( 'p-test-tb', '#', 'After foo', 'post-foo', 'After foo', null, $( tbRL ) );
		assert.strictEqual( $( addedAfter ).next()[0], tbRL, 'Link is in the correct position (by passing a jQuery object as nextnode)' );

		// test case - nonexistent id as next node
		tbRLDMnonexistentid = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/wiki/RL/DM',
			'Default modules', 't-rldm-nonexistent', 'List of all default modules ', 'd', '#t-rl-nonexistent' );

		assert.equal( tbRLDMnonexistentid, $( '#p-test-tb li:last' )[0], 'Nonexistent id as nextnode adds the portlet at end' );

		// test case - empty jquery object as next node
		tbRLDMemptyjquery = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/wiki/RL/DM',
			'Default modules', 't-rldm-empty-jquery', 'List of all default modules ', 'd', $( '#t-rl-nonexistent' ) );

		assert.equal( tbRLDMemptyjquery, $( '#p-test-tb li:last' )[0], 'Empty jquery as nextnode adds the portlet at end' );
	} );

	QUnit.test( 'jsMessage', 1, function ( assert ) {
		var a = mw.util.jsMessage( 'MediaWiki is <b>Awesome</b>.' );
		assert.ok( a, 'Basic checking of return value' );

		// Clean up
		$( '#mw-js-message' ).remove();
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
		// Shortcuts
		function assertFalseIPv6( addy, summary ) {
			return assert.strictEqual( mw.util.isIPv6Address( addy ), false, summary );
		}

		function assertTrueIPv6( addy, summary ) {
			return assert.strictEqual( mw.util.isIPv6Address( addy ), true, summary );
		}

		// Based on IPTest.php > testisIPv6
		assertFalseIPv6( ':fc:100::', 'IPv6 starting with lone ":"' );
		assertFalseIPv6( 'fc:100:::', 'IPv6 ending with a ":::"' );
		assertFalseIPv6( 'fc:300', 'IPv6 with only 2 words' );
		assertFalseIPv6( 'fc:100:300', 'IPv6 with only 3 words' );

		$.each(
			['fc:100::',
				'fc:100:a::',
				'fc:100:a:d::',
				'fc:100:a:d:1::',
				'fc:100:a:d:1:e::',
				'fc:100:a:d:1:e:ac::'], function ( i, addy ) {
				assertTrueIPv6( addy, addy + ' is a valid IP' );
			} );

		assertFalseIPv6( 'fc:100:a:d:1:e:ac:0::', 'IPv6 with 8 words ending with "::"' );
		assertFalseIPv6( 'fc:100:a:d:1:e:ac:0:1::', 'IPv6 with 9 words ending with "::"' );

		assertFalseIPv6( ':::' );
		assertFalseIPv6( '::0:', 'IPv6 ending in a lone ":"' );

		assertTrueIPv6( '::', 'IPv6 zero address' );
		$.each(
			['::0',
				'::fc',
				'::fc:100',
				'::fc:100:a',
				'::fc:100:a:d',
				'::fc:100:a:d:1',
				'::fc:100:a:d:1:e',
				'::fc:100:a:d:1:e:ac',

				'fc:100:a:d:1:e:ac:0'], function ( i, addy ) {
				assertTrueIPv6( addy, addy + ' is a valid IP' );
			} );

		assertFalseIPv6( '::fc:100:a:d:1:e:ac:0', 'IPv6 with "::" and 8 words' );
		assertFalseIPv6( '::fc:100:a:d:1:e:ac:0:1', 'IPv6 with 9 words' );

		assertFalseIPv6( ':fc::100', 'IPv6 starting with lone ":"' );
		assertFalseIPv6( 'fc::100:', 'IPv6 ending with lone ":"' );
		assertFalseIPv6( 'fc:::100', 'IPv6 with ":::" in the middle' );

		assertTrueIPv6( 'fc::100', 'IPv6 with "::" and 2 words' );
		assertTrueIPv6( 'fc::100:a', 'IPv6 with "::" and 3 words' );
		assertTrueIPv6( 'fc::100:a:d', 'IPv6 with "::" and 4 words' );
		assertTrueIPv6( 'fc::100:a:d:1', 'IPv6 with "::" and 5 words' );
		assertTrueIPv6( 'fc::100:a:d:1:e', 'IPv6 with "::" and 6 words' );
		assertTrueIPv6( 'fc::100:a:d:1:e:ac', 'IPv6 with "::" and 7 words' );
		assertTrueIPv6( '2001::df', 'IPv6 with "::" and 2 words' );
		assertTrueIPv6( '2001:5c0:1400:a::df', 'IPv6 with "::" and 5 words' );
		assertTrueIPv6( '2001:5c0:1400:a::df:2', 'IPv6 with "::" and 6 words' );

		assertFalseIPv6( 'fc::100:a:d:1:e:ac:0', 'IPv6 with "::" and 8 words' );
		assertFalseIPv6( 'fc::100:a:d:1:e:ac:0:1', 'IPv6 with 9 words' );
	} );

	QUnit.test( 'isIPv4Address', 11, function ( assert ) {
		// Shortcuts
		function assertFalseIPv4( addy, summary ) {
			assert.strictEqual( mw.util.isIPv4Address( addy ), false, summary );
		}

		function assertTrueIPv4( addy, summary ) {
			assert.strictEqual( mw.util.isIPv4Address( addy ), true, summary );
		}

		// Based on IPTest.php > testisIPv4
		assertFalseIPv4( false, 'Boolean false is not an IP' );
		assertFalseIPv4( true, 'Boolean true is not an IP' );
		assertFalseIPv4( '', 'Empty string is not an IP' );
		assertFalseIPv4( 'abc', '"abc" is not an IP' );
		assertFalseIPv4( ':', 'Colon is not an IP' );
		assertFalseIPv4( '124.24.52', 'IPv4 not enough quads' );
		assertFalseIPv4( '24.324.52.13', 'IPv4 out of range' );
		assertFalseIPv4( '.24.52.13', 'IPv4 starts with period' );

		assertTrueIPv4( '124.24.52.13', '124.24.52.134 is a valid IP' );
		assertTrueIPv4( '1.24.52.13', '1.24.52.13 is a valid IP' );
		assertFalseIPv4( '74.24.52.13/20', 'IPv4 ranges are not recogzized as valid IPs' );
	} );
}( mediaWiki, jQuery ) );
