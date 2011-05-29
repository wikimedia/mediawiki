module( 'mediawiki.util.js' );

test( '-- Initial check', function(){

	ok( mw.util, 'mw.util defined' );

});

test( 'rawurlencode', function(){

	equal( mw.util.rawurlencode( 'Test:A & B/Here' ), 'Test%3AA%20%26%20B%2FHere' );

});

test( 'wikiUrlencode', function(){

	equal( mw.util.wikiUrlencode( 'Test:A & B/Here' ), 'Test:A_%26_B/Here' );

});

test( 'wikiGetlink', function(){

	// Not part of startUp module
	mw.config.set( 'wgArticlePath', '/wiki/$1' );

	var hrefA = mw.util.wikiGetlink( 'Sandbox' );

	equal( hrefA, '/wiki/Sandbox', 'Simple title; Get link for "Sandbox"' );

	var hrefB = mw.util.wikiGetlink( 'Foo:Sandbox ? 5+5=10 ! (test)/subpage' );

	equal( hrefB, '/wiki/Foo:Sandbox_%3F_5%2B5%3D10_%21_%28test%29/subpage', 'Advanced title; Get link for "Foo:Sandbox ? 5+5=10 ! (test)/subpage"' );

});

test( 'wikiScript', function(){
	expect(2);

	mw.config.set({
		'wgScript': '/w/index.php',
		'wgScriptPath': '/w',
		'wgScriptExtension': '.php'
	});

	equal( mw.util.wikiScript(), mw.config.get( 'wgScript' ), 'Defaults to index.php and is equal to wgScript' );
	deepEqual( mw.util.wikiScript( 'api' ), '/w/api.php' );

});

test( 'addCSS', function(){
	expect(3);

	window.a = mw.util.addCSS( '#bodyContent { visibility: hidden; }' );
	ok(  a, 'function works' );
	deepEqual( a.disabled, false, 'property "disabled" is available and set to false' );

	var $b = $('#bodyContent');
	equal( $b.css('visibility'), 'hidden', 'Added style properties are in effect' );

});

test( 'toggleToc', function(){
	expect(3);

	deepEqual( mw.util.toggleToc(), null, 'Return null if there is no table of contents on the page.' );

	var tocHtml =
	'<table id="toc" class="toc"><tr><td>' +
		'<div id="toctitle">' +
			'<h2>Contents</h2>' +
			'<span class="toctoggle">&nbsp;[<a href="#" class="internal" id="togglelink">Hide</a>&nbsp;]</span>' +
		'</div>' +
		'<ul><li></li></ul>' +
	'</td></tr></table>';	
	var $toc = $(tocHtml).appendTo( 'body' );
	var $toggleLink = $( '#togglelink' );

	// Toggle animation is asynchronous
	// QUnit should not finish this test() untill they are all done
	stop();

	var actionC = function(){
		start();

		// Clean up
		$toc.remove();
	};
	var actionB = function(){
		deepEqual( mw.util.toggleToc( $toggleLink, actionC ), true, 'Return boolean true if the TOC is now visible.' );
	};
	var actionA = function(){
		deepEqual( mw.util.toggleToc( $toggleLink, actionB ), false, 'Return boolean false if the TOC is now hidden.' );
	};
	
	actionA();


});

test( 'getParamValue', function(){

	var url = 'http://mediawiki.org/?foo=wrong&foo=right#&foo=bad';

	equal( mw.util.getParamValue( 'foo', url ), 'right', 'Use latest one, ignore hash' );
	deepEqual( mw.util.getParamValue( 'bar', url ), null, 'Return null when not found' );

});

test( 'tooltipAccessKey', function(){

	equal( typeof mw.util.tooltipAccessKeyPrefix, 'string', 'mw.util.tooltipAccessKeyPrefix must be a string' );
	ok( mw.util.tooltipAccessKeyRegexp instanceof RegExp, 'mw.util.tooltipAccessKeyRegexp instance of RegExp' );
	ok( mw.util.updateTooltipAccessKeys, 'mw.util.updateTooltipAccessKeys' );

});

test( '$content', function(){

	ok( mw.util.$content instanceof jQuery, 'mw.util.$content instance of jQuery' );
	deepEqual( mw.util.$content.length, 1, 'mw.util.$content must have length of 1' );

});

test( 'addPortletLink', function(){

	var A = mw.util.addPortletLink( 'p-tb', 'http://mediawiki.org/wiki/ResourceLoader',
		'ResourceLoader', 't-rl', 'More info about ResourceLoader on MediaWiki.org ', 'l', '#t-specialpages' );

	ok( $.isDomElement( A ), 'addPortletLink returns a valid DOM Element according to $.isDomElement' );

	var B = mw.util.addPortletLink( "p-tb", "http://mediawiki.org/",
		"MediaWiki.org", "t-mworg", "Go to MediaWiki.org ", "m", A );

	equal( $( B ).attr( 'id' ), 't-mworg', 'Link has correct ID set' );
	equal( $( B ).closest( '.portal' ).attr( 'id' ), 'p-tb', 'Link was inserted within correct portlet' );
	equal( $( B ).next().attr( 'id' ), 't-rl', 'Link is in the correct position (by passing nextnode)' );

	var C = mw.util.addPortletLink( "p-tb", "http://mediawiki.org/wiki/RL/DM",
		"Default modules", "t-rldm", "List of all default modules ", "d", "#t-rl" );

	equal( $( C ).next().attr( 'id' ), 't-rl', 'Link is in the correct position (by passing CSS selector)' );

	// Clean up
	$( [A, B, C] ).remove();

});

test( 'jsMessage', function(){

	var a = mw.util.jsMessage( "MediaWiki is <b>Awesome</b>." );

	ok( a, 'Basic checking of return value' );

	// Clean up
	$( '#mw-js-message' ).remove();

});

test( 'validateEmail', function(){
	expect(6);

	deepEqual( mw.util.validateEmail( "" ), null, 'Should return null for empty string ' );
	deepEqual( mw.util.validateEmail( "user@localhost" ), true, 'Return true for a valid e-mail address' );

	// testEmailWithCommasAreInvalids
	deepEqual( mw.util.validateEmail( "user,foo@example.org" ), false, 'Emails with commas are invalid' );
	deepEqual( mw.util.validateEmail( "userfoo@ex,ample.org" ), false, 'Emails with commas are invalid' );

	// testEmailWithHyphens
	deepEqual( mw.util.validateEmail( "user-foo@example.org" ), true, 'Emails may contain a hyphen' );
	deepEqual( mw.util.validateEmail( "userfoo@ex-ample.org" ), true, 'Emails may contain a hyphen' );

});

test( 'isIPv6Address', function(){
	expect(6);

	// Based on IPTest.php > IPv6
	deepEqual( mw.util.isIPv6Address( "" ), false, 'Empty string is not an IP' );
	deepEqual( mw.util.isIPv6Address( ":fc:100::" ), false, 'IPv6 starting with lone ":"' );
	deepEqual( mw.util.isIPv6Address( "fc:100::" ), true );
	deepEqual( mw.util.isIPv6Address( "fc:100:a:d:1:e:ac::" ), true );
	deepEqual( mw.util.isIPv6Address( ":::" ), false );
	deepEqual( mw.util.isIPv6Address( "::0:" ), false );

});

test( 'isIPv4Address', function(){
	expect(3);

	// Based on IPTest.php > IPv4
	deepEqual( mw.util.isIPv4Address( "" ), false, 'Empty string is not an IP' );
	deepEqual( mw.util.isIPv4Address( "...." ), false );
	deepEqual( mw.util.isIPv4Address( "1.24.52.13" ), true );

});
