#!/usr/bin/env php
<?php

require 'Test.php';

plan( 13 );

define( 'MEDIAWIKI', 1 );
require_ok( 'includes/Defines.php' );
require_ok( 'includes/GlobalFunctions.php' );
require_ok( 'includes/Sanitizer.php' );
require_ok( 'includes/normal/UtfNormal.php' );
require_ok( 'includes/ProfilerStub.php' ); # For removeHTMLtags


#
# decodeCharReferences
#

cmp_ok(
	Sanitizer::decodeCharReferences( '&eacute;cole' ),
	'==',
	"\xc3\xa9cole",
	'decode named entities'
);

cmp_ok(
	Sanitizer::decodeCharReferences( "&#x108;io bonas dans l'&#233;cole!" ),
	'==',
	"\xc4\x88io bonas dans l'\xc3\xa9cole!",
	'decode numeric entities'
);

cmp_ok(
	Sanitizer::decodeCharReferences( "&#x108;io bonas dans l'&eacute;cole!" ),
	'==',
	"\xc4\x88io bonas dans l'\xc3\xa9cole!",
	'decode mixed numeric/named entities'
);

cmp_ok(
	Sanitizer::decodeCharReferences(
		"&#x108;io bonas dans l'&eacute;cole! (mais pas &amp;#x108;io dans l'&#38;eacute;cole)"
	),
	'==',
	"\xc4\x88io bonas dans l'\xc3\xa9cole! (mais pas &#x108;io dans l'&eacute;cole)",
	'decode mixed complex entities'
);

cmp_ok( Sanitizer::decodeCharReferences( 'a & b' ), '==', 'a & b', 'Invalid ampersand' );

cmp_ok( Sanitizer::decodeCharReferences( '&foo;' ), '==', '&foo;', 'Invalid named entity' );

cmp_ok( Sanitizer::decodeCharReferences( "&#88888888888888;" ), '==', UTF8_REPLACEMENT, 'Invalid numbered entity' );

$wgUseTidy = false;
$wgUserHtml = true;
cmp_ok(
	Sanitizer::removeHTMLtags( '<div>Hello world</div />' ),
	'==',
	'<div>Hello world</div>',
	'Self-closing closing div'
);
