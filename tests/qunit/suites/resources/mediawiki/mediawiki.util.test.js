module( 'mediawiki.util', QUnit.newMwEnvironment() );

test( '-- Initial check', function() {
	expect(1);

	ok( mw.util, 'mw.util defined' );
});

test( 'rawurlencode', function() {
	expect(1);

	equal( mw.util.rawurlencode( 'Test:A & B/Here' ), 'Test%3AA%20%26%20B%2FHere' );
});

test( 'wikiUrlencode', function() {
	expect(1);

	equal( mw.util.wikiUrlencode( 'Test:A & B/Here' ), 'Test:A_%26_B/Here' );
});

test( 'wikiGetlink', function() {
	expect(3);

	// Not part of startUp module
	mw.config.set( 'wgArticlePath', '/wiki/$1' );
	mw.config.set( 'wgPageName', 'Foobar' );

	var hrefA = mw.util.wikiGetlink( 'Sandbox' );
	equal( hrefA, '/wiki/Sandbox', 'Simple title; Get link for "Sandbox"' );

	var hrefB = mw.util.wikiGetlink( 'Foo:Sandbox ? 5+5=10 ! (test)/subpage' );
	equal( hrefB, '/wiki/Foo:Sandbox_%3F_5%2B5%3D10_%21_%28test%29/subpage',
		'Advanced title; Get link for "Foo:Sandbox ? 5+5=10 ! (test)/subpage"' );

	var hrefC = mw.util.wikiGetlink();
	equal( hrefC, '/wiki/Foobar', 'Default title; Get link for current page ("Foobar")' );
});

test( 'wikiScript', function() {
	expect(2);

	mw.config.set({
		'wgScript': '/w/index.php',
		'wgScriptPath': '/w',
		'wgScriptExtension': '.php'
	});

	equal( mw.util.wikiScript(), mw.config.get( 'wgScript' ), 'Defaults to index.php and is equal to wgScript' );
	equal( mw.util.wikiScript( 'api' ), '/w/api.php', 'API path' );
});

test( 'addCSS', function() {
	expect(3);

	var $testEl = $( '<div>' ).attr( 'id', 'mw-addcsstest' ).appendTo( '#qunit-fixture' );

	var style = mw.util.addCSS( '#mw-addcsstest { visibility: hidden; }' );
	equal( typeof style, 'object', 'addCSS returned an object' );
	strictEqual( style.disabled, false, 'property "disabled" is available and set to false' );

	equal( $testEl.css( 'visibility' ), 'hidden', 'Added style properties are in effect' );

	// Clean up
	$( style.ownerNode ).remove();
});

test( 'toggleToc', function() {
	expect(4);

	strictEqual( mw.util.toggleToc(), null, 'Return null if there is no table of contents on the page.' );

	var	tocHtml =
	'<table id="toc" class="toc"><tr><td>' +
		'<div id="toctitle">' +
			'<h2>Contents</h2>' +
			'<span class="toctoggle">&nbsp;[<a href="#" class="internal" id="togglelink">Hide</a>&nbsp;]</span>' +
		'</div>' +
		'<ul><li></li></ul>' +
	'</td></tr></table>',
		$toc = $(tocHtml).appendTo( '#qunit-fixture' ),
		$toggleLink = $( '#togglelink' );

	strictEqual( $toggleLink.length, 1, 'Toggle link is appended to the page.' );

	// Toggle animation is asynchronous
	// QUnit should not finish this test() untill they are all done
	stop();

	var actionC = function() {
		start();
	};
	var actionB = function() {
		start(); stop();
		strictEqual( mw.util.toggleToc( $toggleLink, actionC ), true, 'Return boolean true if the TOC is now visible.' );
	};
	var actionA = function() {
		strictEqual( mw.util.toggleToc( $toggleLink, actionB ), false, 'Return boolean false if the TOC is now hidden.' );
	};

	actionA();
});

test( 'getParamValue', function() {
	expect(5);

	var	url1 = 'http://example.org/?foo=wrong&foo=right#&foo=bad';

	equal( mw.util.getParamValue( 'foo', url1 ), 'right', 'Use latest one, ignore hash' );
	strictEqual( mw.util.getParamValue( 'bar', url1 ), null, 'Return null when not found' );

	var url2 = 'http://example.org/#&foo=bad';
	strictEqual( mw.util.getParamValue( 'foo', url2 ), null, 'Ignore hash if param is not in querystring but in hash (bug 27427)' );

	var url3 = 'example.org?' + $.param({ 'TEST': 'a b+c' });
	strictEqual( mw.util.getParamValue( 'TEST', url3 ), 'a b+c', 'Bug 30441: getParamValue must understand "+" encoding of space' );

	var url4 = 'example.org?' + $.param({ 'TEST': 'a b+c d' }); // check for sloppy code from r95332 :)
	strictEqual( mw.util.getParamValue( 'TEST', url4 ), 'a b+c d', 'Bug 30441: getParamValue must understand "+" encoding of space (multiple spaces)' );
});

test( 'tooltipAccessKey', function() {
	expect(3);

	equal( typeof mw.util.tooltipAccessKeyPrefix, 'string', 'mw.util.tooltipAccessKeyPrefix must be a string' );
	ok( mw.util.tooltipAccessKeyRegexp instanceof RegExp, 'mw.util.tooltipAccessKeyRegexp instance of RegExp' );
	ok( mw.util.updateTooltipAccessKeys, 'mw.util.updateTooltipAccessKeys' );
});

test( '$content', function() {
	expect(2);

	ok( mw.util.$content instanceof jQuery, 'mw.util.$content instance of jQuery' );
	strictEqual( mw.util.$content.length, 1, 'mw.util.$content must have length of 1' );
});


/**
 * Portlet names are prefixed with 'p-test' to avoid conflict with core
 * when running the test suite under a wiki page.
 * Previously, test elements where invisible to the selector since only
 * one element can have a given id. 
 */
test( 'addPortletLink', function() {
	expect(7);

	var mwPanel = '<div id="mw-panel" class="noprint">\
	<h5>Toolbox</h5>\
	<div class="portlet" id="p-test-tb">\
		<ul class="body"></ul>\
	</div>\
</div>',
	vectorTabs = '<div id="p-test-views" class="vectorTabs">\
	<h5>Views</h5>\
	<ul></ul>\
</div>',
	$mwPanel = $(mwPanel).appendTo( '#qunit-fixture' ),
	$vectorTabs = $(vectorTabs).appendTo( '#qunit-fixture' );

	var tbRL = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/wiki/ResourceLoader',
		'ResourceLoader', 't-rl', 'More info about ResourceLoader on MediaWiki.org ', 'l' );

	ok( $.isDomElement( tbRL ), 'addPortletLink returns a valid DOM Element according to $.isDomElement' );

	var	tbMW = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/',
			'MediaWiki.org', 't-mworg', 'Go to MediaWiki.org ', 'm', tbRL ),
		$tbMW = $( tbMW );


	equal( $tbMW.attr( 'id' ), 't-mworg', 'Link has correct ID set' );
	equal( $tbMW.closest( '.portlet' ).attr( 'id' ), 'p-test-tb', 'Link was inserted within correct portlet' );
	equal( $tbMW.next().attr( 'id' ), 't-rl', 'Link is in the correct position (by passing nextnode)' );

	var tbRLDM = mw.util.addPortletLink( 'p-test-tb', '//mediawiki.org/wiki/RL/DM',
		'Default modules', 't-rldm', 'List of all default modules ', 'd', '#t-rl' );

	equal( $( tbRLDM ).next().attr( 'id' ), 't-rl', 'Link is in the correct position (by passing CSS selector)' );

	var caFoo = mw.util.addPortletLink( 'p-test-views', '#', 'Foo' );

	strictEqual( $tbMW.find( 'span').length, 0, 'No <span> element should be added for porlets without vectorTabs class.' );
	strictEqual( $( caFoo ).find( 'span').length, 1, 'A <span> element should be added for porlets with vectorTabs class.' );

	// Clean up
	$( [tbRL, tbMW, tbRLDM, caFoo] ).remove();
});

test( 'jsMessage', function() {
	expect(1);

	var a = mw.util.jsMessage( "MediaWiki is <b>Awesome</b>." );
	ok( a, 'Basic checking of return value' );

	// Clean up
	$( '#mw-js-message' ).remove();
});

test( 'validateEmail', function() {
	expect(6);

	strictEqual( mw.util.validateEmail( "" ), null, 'Should return null for empty string ' );
	strictEqual( mw.util.validateEmail( "user@localhost" ), true, 'Return true for a valid e-mail address' );

	// testEmailWithCommasAreInvalids
	strictEqual( mw.util.validateEmail( "user,foo@example.org" ), false, 'Emails with commas are invalid' );
	strictEqual( mw.util.validateEmail( "userfoo@ex,ample.org" ), false, 'Emails with commas are invalid' );

	// testEmailWithHyphens
	strictEqual( mw.util.validateEmail( "user-foo@example.org" ), true, 'Emails may contain a hyphen' );
	strictEqual( mw.util.validateEmail( "userfoo@ex-ample.org" ), true, 'Emails may contain a hyphen' );
});

test( 'isIPv6Address', function() {
	expect(40);

	// Shortcuts
	var	assertFalseIPv6 = function( addy, summary ) {
			return strictEqual( mw.util.isIPv6Address( addy ), false, summary );
		},
		assertTrueIPv6 = function( addy, summary ) {
			return strictEqual( mw.util.isIPv6Address( addy ), true, summary );
		};

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
	'fc:100:a:d:1:e:ac::'], function( i, addy ){
		assertTrueIPv6( addy, addy + ' is a valid IP' );
	});

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

	'fc:100:a:d:1:e:ac:0'], function( i, addy ){
		assertTrueIPv6( addy, addy + ' is a valid IP' );
	});

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
});

test( 'isIPv4Address', function() {
	expect(11);

	// Shortcuts
	var	assertFalseIPv4 = function( addy, summary ) {
			return strictEqual( mw.util.isIPv4Address( addy ), false, summary );
		},
		assertTrueIPv4 = function( addy, summary ) {
			return strictEqual( mw.util.isIPv4Address( addy ), true, summary );
		};

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
});
